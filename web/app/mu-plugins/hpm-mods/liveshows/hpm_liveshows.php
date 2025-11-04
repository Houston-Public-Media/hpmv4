<?php
class HPM_Liveshows {
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		add_action( 'init', [ $this, 'create_type' ] );
	}
	public function init(): void {
		// Add edit capabilities to selected roles
		add_action( 'admin_init', [ $this, 'add_role_caps' ], 999 );

		// Setup metadata for podcast feeds
		add_action( 'load-post.php', [ $this, 'meta_setup' ] );
		add_action( 'load-post-new.php', [ $this, 'meta_setup' ] );

		add_action( 'hpm_ytlive', [ $this, 'ytlive_update' ] );

		if ( !wp_next_scheduled( 'hpm_ytlive' ) ) {
			wp_schedule_event( time(), 'hpm_15min', 'hpm_ytlive' );
		}
		// Register WP-REST API endpoints
		add_action( 'rest_api_init', function() {
			register_rest_route( 'hpm-liveshow/v1', '/list', [
				'methods'  => 'GET',
				'callback' => [ $this, 'list' ],
				'permission_callback' => function() {
					return true;
				}
			]);
		});
	}
	public function ytlive_update(): void {
		$temp = [];
		$names = self::get_all();
		foreach ( $names as $k => $v ) {
			$temp[ $k ] = [];
		}
		$option = get_option( 'hpm_ytlive_talkshows' );
		if ( empty( $option ) ) {
			$option = $temp;
		}
		$now = time();
		$offset = get_option( 'gmt_offset' ) * 3600;
		$now += $offset;
		$t = getdate( $now );
		$today = mktime( 0, 0, 0, $t['mon'], $t['mday'], $t['year'] );
		$tomorrow = $today + 86400;
		$remote = wp_remote_get( esc_url_raw( "https://cdn.houstonpublicmedia.org/assets/ytlive.json" ) );
		if ( is_wp_error( $remote ) ) {
			return;
		} else {
			$json = json_decode( wp_remote_retrieve_body( $remote ), true );
			foreach( $json as $item ) {
				$date = strtotime( $item['start'] ) + $offset;
				foreach ( $names as $name_slug => $name ) {
					if ( str_contains( $item['title'], $name['title'] ) ) {
						$temp[ $name_slug ][ $date ] = $item;
					}
				}
			}
		}
		foreach ( $temp as $t ) {
			ksort( $t );
		}
		foreach( $temp as $show => $event ) {
			foreach ( $event as $date => $meta ) {
				if ( !empty( $option[ $show ]['start'] ) ) {
					$prev = strtotime( $option[ $show ]['start'] );
					if ( $date >= $today && $date <= $tomorrow && $date > $prev ) {
						$option[ $show ] = $meta;
					}
				} else {
					if ( $date >= $today && $date <= $tomorrow ) {
						$option[ $show ] = $meta;
					}
				}

			}
		}
		update_option( 'hpm_ytlive_talkshows', $option );
	}

	public function meta_setup(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_meta' ] );
		add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
	}

	public function add_meta(): void {
		add_meta_box(
			'hpm-liveshow-meta-class',
			esc_html__( 'Live Show Metadata', 'hpm-live' ),
			[ $this, 'liveshow_meta' ],
			'liveshows',
			'normal',
			'core'
		);
	}

	/**
	 * Set up metadata for this embed: responsiveness and branding.
	 *
	 * @param $object
	 * @param $box
	 *
	 * @return void
	 */
	public function liveshow_meta( $object, $box ): void {
		wp_nonce_field( basename( __FILE__ ), 'hpm_liveshow_class_nonce' );
		$hpm_liveshow = get_post_meta( $object->ID, 'hpm_liveshow', true );
		if ( empty( $hpm_liveshow ) ) {
			$hpm_liveshow = [
				'start_hour' => 0,
				'end_hour' => 0,
				'once_date' => date( 'Y-m-d' ),
				'email' => '',
				'phone' => '',
				'recurring' => 1,
				'recurring_pattern' => []
			];
		} ?>
		<p>
			<strong><label for="hpm-liveshow-start-hour"><?PHP _e( "Show Start Hour", 'hpm-liveshows' ); ?></label></strong>
			<select name="hpm-liveshow-start-hour" id="hpm-liveshow-start-hour">
				<?php for ( $i = 0; $i < 24; $i++ ) { ?>
				<option value="<?php echo $i; ?>"<?php selected( $hpm_liveshow['start_hour'], $i, TRUE ); ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<strong><label for="hpm-liveshow-end-hour"><?PHP _e( "Show End Hour", 'hpm-liveshows' ); ?></label></strong>
			<select name="hpm-liveshow-end-hour" id="hpm-liveshow-end-hour">
				<?php for ( $i = 0; $i < 24; $i++ ) { ?>
					<option value="<?php echo $i; ?>"<?php selected( $hpm_liveshow['end_hour'], $i, TRUE ); ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>
		<p><label for="hpm-liveshow-email"><?php _e( "Show Email:", 'hpm-liveshow' ); ?></label> <input type="text" id="hpm-liveshow-email" name="hpm-liveshow-email" value="<?php echo $hpm_liveshow['email']; ?>" placeholder="kloggins@danger.zone" style="width: 60%;" /></p>
		<p><label for="hpm-liveshow-phone"><?php _e( "Show Phone Number:", 'hpm-liveshow' ); ?></label> <input type="text" id="hpm-liveshow-phone" name="hpm-liveshow-phone" value="<?php echo $hpm_liveshow['phone']; ?>" placeholder="(555) 867-5309" style="width: 60%;" /></p>
		<p><strong><label for="hpm-liveshow-recurring"><?PHP _e( "Is this an ongoing show?", 'hpm-liveshows' ); ?></label></strong> <select name="hpm-liveshow-recurring" id="hpm-liveshow-recurring">
				<option value="0"<?PHP selected( $hpm_liveshow['recurring'], 0, TRUE ); ?>><?PHP _e( "No", 'hpm-liveshows' ); ?></option>
				<option value="1"<?PHP selected( $hpm_liveshow['recurring'], 1, TRUE ); ?>><?PHP _e( "Yes", 'hpm-liveshows' ); ?></option>
			</select>
		</p>
		<div id="hpm-liveshow-is-recurring" class="hpm-liveshow-recur<?php echo ( $hpm_liveshow['recurring'] == 0 ? ' hidden' : '' ); ?>">
			<p><?php _e( "What days does it air on?", 'hpm-liveshow' ); ?></p>
			<ul style="margin-inline-start: 1rem;">
				<li><input type="checkbox" value="0" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-sunday" <?php if ( in_array( 0, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-sunday">Sunday</label></li>
				<li><input type="checkbox" value="1" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-monday" <?php if ( in_array( 1, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-monday">Monday</label></li>
				<li><input type="checkbox" value="2" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-tuesday" <?php if ( in_array( 2, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-tuesday">Tuesday</label></li>
				<li><input type="checkbox" value="3" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-wednesday" <?php if ( in_array( 3, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-wednesday">Wednesday</label></li>
				<li><input type="checkbox" value="4" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-thursday" <?php if ( in_array( 4, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-thursday">Thursday</label></li>
				<li><input type="checkbox" value="5" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-friday" <?php if ( in_array( 5, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-friday">Friday</label></li>
				<li><input type="checkbox" value="6" name="hpm-liveshow-recur[]" id="hpm-liveshow-recur-saturday" <?php if ( in_array( 6, $hpm_liveshow['recurring_pattern'] ) ) { echo "checked "; } ?>/> <label for="hpm-liveshow-recur-saturday">Saturday</label></li>
			</ul>
		</div>
		<div id="hpm-liveshow-is-not-recurring" class="hpm-liveshow-recur<?php echo ( $hpm_liveshow['recurring'] == 1 ? ' hidden' : '' ); ?>">
			<p><label for="hpm-liveshow-once-date"><?php _e( "What day will this air?", 'hpm-liveshow' ); ?></label> <input type="date" id="hpm-liveshow-once-date" name="hpm-liveshow-once-date" value="<?php echo $hpm_liveshow['once_date']; ?>" /></p>
		</div>
		<script>
			document.addEventListener('DOMContentLoaded', () => {
				let isRecurring = document.querySelector('#hpm-liveshow-is-recurring');
				let isNotRecurring = document.querySelector('#hpm-liveshow-is-not-recurring');
				let recurBoxes = document.querySelectorAll('.hpm-liveshow-recur');
				document.querySelector('#hpm-liveshow-recurring').addEventListener('change', () => {
					Array.from(recurBoxes).forEach(recur => {
						if (!recur.classList.contains('hidden')) {
							recur.classList.add('hidden');
						}
					});
					let select = document.querySelector('#hpm-liveshow-recurring').value;
					if ( select === "0" ) {
						isNotRecurring.classList.remove('hidden');
					} else {
						isRecurring.classList.remove('hidden');
					}
				});
			});
		</script>
		<?php
	}

	/**
	 * Save the above metadata in postmeta
	 *
	 * @param $post_id
	 * @param $post
	 *
	 * @return mixed
	 */
	public function save_meta( $post_id, $post ): mixed {
		$post_type = get_post_type_object( $post->post_type );
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}
		if ( $post->post_type == 'liveshows' ) {
			if ( empty( $_POST['hpm_liveshow_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_liveshow_class_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			$hpm_liveshow = [
				'start_hour' => $_POST['hpm-liveshow-start-hour'],
				'end_hour' => $_POST['hpm-liveshow-end-hour'],
				'once_date' => $_POST['hpm-liveshow-once-date'],
				'email' => ( !empty( $_POST['hpm-liveshow-email'] ) ? sanitize_text_field( $_POST['hpm-liveshow-email'] ) : '' ),
				'phone' => ( !empty( $_POST['hpm-liveshow-phone'] ) ? sanitize_text_field( $_POST['hpm-liveshow-phone'] ) : '' ),
				'recurring' => $_POST['hpm-liveshow-recurring'],
				'recurring_pattern' => ( !empty( $_POST['hpm-liveshow-recur'] ) ? $_POST['hpm-liveshow-recur'] : [] ),
			];

			update_post_meta( $post_id, 'hpm_liveshow', $hpm_liveshow );
		}
		return $post_id;
	}

	/**
	 * Create custom post type to house our embeds
	 */
	public function create_type(): void {
		register_post_type( 'liveshows', [
			'labels' => [
				'name' => __( 'Live Shows' ),
				'singular_name' => __( 'Live Show' ),
				'menu_name' => __( 'Live Shows' ),
				'add_new' => __( 'Add New Live Show' ),
				'add_new_item' => __( 'Add New Live Show' ),
				'edit_item' => __( 'Edit Live Show' ),
				'new_item' => __( 'New Live Show' ),
				'view_item' => __( 'View Live Show' ),
				'search_items' => __( 'Search Live Showss' ),
				'not_found' => __( 'Live Show Not Found' ),
				'not_found_in_trash' => __( 'Live Show Not Found in Trash' ),
				'all_items' => __( 'All Live Shows' ),
				'archives' => __( 'Live Show Archives' ),
			],
			'description' => 'Live streaming shows for display on the website',
			'public' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-media-code',
			'has_archive' => false,
			'supports' => [ 'title' ],
			'taxonomies' => [],
			'capability_type' => [ 'hpm_liveshow', 'hpm_liveshows' ],
			'map_meta_cap' => true,
			'show_in_graphql' => true,
			'graphql_single_name' => 'Live Show',
			'graphql_plural_name' => 'Live Shows'
		]);
	}

	/**
	 * Add capabilities to the selected roles (default is admin/editor)
	 */
	public function add_role_caps(): void {
		$roles = [ 'editor', 'administrator' ];
		foreach( $roles as $the_role ) {
			$role = get_role( $the_role );
			$role->add_cap( 'read' );
			$role->add_cap( 'read_hpm_liveshow');
			$role->add_cap( 'read_private_hpm_liveshows' );
			$role->add_cap( 'edit_hpm_liveshow' );
			$role->add_cap( 'edit_hpm_liveshows' );
			$role->add_cap( 'edit_others_hpm_liveshows' );
			$role->add_cap( 'edit_published_hpm_liveshows' );
			$role->add_cap( 'publish_hpm_liveshows' );
			$role->add_cap( 'delete_others_hpm_liveshows' );
			$role->add_cap( 'delete_private_hpm_liveshows' );
			$role->add_cap( 'delete_published_hpm_liveshows' );
		}
	}

	/**
	 * Return list of active podcast feeds with feed URLs and most recent files
	 *
	 * @return WP_Error|WP_HTTP_Response|WP_REST_Response
	 */
	public function list( WP_REST_Request $request = null ): WP_Error|WP_HTTP_Response|WP_REST_Response {
		$list = get_transient( 'hpm_liveshows_list' );
		if ( !empty( $list ) ) {
			return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'Live Shows list', 'hpm-liveshows' ), 'data' => [ 'list' => $list, 'status' =>	200 ] ] );
		}
		$_SERVER['HTTPS'] = 'on';
		$list = self::get_all();
		set_transient( 'hpm_liveshows_list', $list, 86400 );
		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'Live Shows list', 'hpm-liveshows' ), 'data' => [ 'list' => $list, 'status' => 200 ] ] );
	}

	public static function get_all(): array {
		$temp = [];
		$query = new WP_Query([
			'post_type' => 'liveshows',
			'post_status' => 'publish',
			'posts_per_page' => -1
		]);
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id = get_the_ID();
				$liveshow = get_post_meta( $id, 'hpm_liveshow', true );
				$temp[ $query->post->post_name ] = $liveshow;
				$temp[ $query->post->post_name ]['title'] = get_the_title();
			}
		}
		return $temp;
	}

	public static function liveshow_check(): array {
		$hm_airtimes = get_transient( 'hpm_hm_airing' );
		if ( !empty( $hm_airtimes ) ) {
			return $hm_airtimes;
		}
		$temp = self::get_all();
		$hm_airtimes = [];
		foreach ( $temp as $show ) {
			for ( $i = $show['start_hour']; $i < $show['end_hour']; $i++ ) {
				$hm_airtimes[ $i ] = false;
			}
		}

		$t = time();
		$offset = get_option( 'gmt_offset' ) * 3600;
		$t = $t + $offset;
		$date = date( 'Y-m-d', $t );

		$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/day?date=" . $date . "&format=json" ) );
		if ( is_wp_error( $remote ) ) {
			return $hm_airtimes;
		} else {
			$api = wp_remote_retrieve_body( $remote );
			$json = json_decode( $api, TRUE );
			foreach ( $json['onToday'] as $j ) {
				foreach ( $temp as $show ) {
					if ( str_contains( $j['program']['name'], $show['title'] ) ) {
						$starttime = explode( ';', $j['start_time'] );
						$startint = intval( $starttime[0] );
						if ( $startint == $show['start_hour'] ) {
							for ( $i = $show['start_hour']; $i < $show['end_hour']; $i++ ) {
								$hm_airtimes[ $i ] = true;
							}
						}
					}
				}
			}
		}
		set_transient( 'hpm_hm_airing', $hm_airtimes, 600 );
		return $hm_airtimes;
	}
	public static function display_banner(): string {
		wp_reset_query();
		global $wp_query;
		$t = time();
		$offset = get_option( 'gmt_offset' ) * 3600;
		$t = $t + $offset;
		$now = getdate( $t );
		$output = '';
		if ( empty( $wp_query->post ) || $wp_query->post->post_type == 'embeds' ) {
			return $output;
		}
		$hm_air = self::liveshow_check();
		$temp = self::get_all();

		$ytlive = get_option( 'hpm_ytlive_talkshows' );
		if ( !empty( $hm_air[ $now['hours'] ] ) ) {
			foreach ( $temp as $k => $v ) {
				if ( $v['recurring'] == 1 &&
					in_array( $now['wday'], $v['recurring_pattern'] ) &&
					$v['start_hour'] <= $now['hours'] &&
					$v['end_hour'] > $now['hours']
				) {
					$class = $k;
					$outs = [];
					if ( !empty( $v['email'] ) ) {
						$outs[] = '<a href="mailto:' . $v['email'] . '">Email</a>';
					}
					if ( !empty( $v['phone'] ) ) {
						$outs[] = '<a href="tel://+1' . $v['phone'] . '">Call/Text</a>';
					}
					if ( !empty( $ytlive[ $k ]['id'] ) ) {
						$outs[] = '<a href="https://www.youtube.com/watch?v=' . $ytlive[ $k ]['id'] . '">Watch</a>';
						$class .= " livestream-show";
					}
					$outs[] = '<a href="/listen-live/">Listen</a>';
					$output .= '<div id="hm-top" class="' . $class . '"><p><span><a href="https://www.youtube.com/watch?v=' . $ytlive[ $k ]['id'] . '"><strong>' . $v['title'] . '</strong> is live!</a> Join the conversation:</span> ' . implode( ' | ', $outs ) . '</p></div>';
				} elseif ( $v['recurring'] == 0 &&
					$v['start_hour'] <= $now['hours'] &&
					$v['end_hour'] > $now['hours'] &&
					$v['once_date'] == date( 'Y-m-d', $now[0] )
				) {
					$outs = [];
					$class = $k;
					if ( !empty( $v['email'] ) ) {
						$outs[] = '<a href="mailto:' . $v['email'] . '">Email</a>';
					}
					if ( !empty( $v['phone'] ) ) {
						$outs[] = '<a href="tel://+1' . $v['phone'] . '">Call/Text</a>';
					}
					if ( !empty( $ytlive[ $k ]['id'] ) ) {
						$outs[] = '<a href="https://www.youtube.com/watch?v=' . $ytlive[ $k ]['id'] . '">Watch</a>';
						$class .= " livestream-show";
					} else {
						$outs[] = '<a href="https://www.youtube.com/@HoustonPublicMedia/streams">Watch</a>';
					}
					$outs[] = '<a href="/listen-live/">Listen</a>';
					$output .= '<div id="hm-top" class="' . $class . '"><p><span><a href="https://www.youtube.com/@HoustonPublicMedia/streams"><strong>' . $v['title'] . '</strong> is live!</a> Join the conversation:</span> ' . implode( ' | ', $outs ) . '</p></div>';
				}
			}
		}
		return $output;
	}

	public static function show_top_articles( $articles, $talkshow = '' ): string {
		$result = "";
		$talkshow_display = false;

		if ( count( $articles ) > 0 ) {
			foreach ( $articles as $ka => $va ) {
				$post = $va;
				$post_title = get_the_title( $post );
				if ( is_front_page() ) {
					$alt_headline = get_post_meta( $post->ID, 'hpm_alt_headline', true );
					if ( !empty( $alt_headline ) ) {
						$post_title = $alt_headline;
					}
				}
				$summary = strip_tags( get_the_excerpt( $post ) );
				if ( $ka == 0 ) {
					if ( in_array( 'tag-breaking-news-button', get_post_class( '', $post->ID ) ) ) {
						$breakingNewsButton = '<div class="blue-label"><strong>Breaking News | </strong><span>'.hpm_top_cat( $post->ID ).'</span></div>';
					} else {
						$breakingNewsButton = '';
					}
					$result .= '<div class="col-lg-8 col-sm-12 breaking-news-first">'.
						'<div class="row news-main">'.
						'<div class="col-sm-5">' .
						$breakingNewsButton . '<h1><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . $post_title . '</a></h1>'.
						'<p style="font-size: 0.875rem;">' . $summary . '</p>'.
						'</div>'.
						'<div class="col-sm-7">'.
						'<div class="box-img breaking-news-img"><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . get_the_post_thumbnail( $post, $post->ID ) . ' </a></div>'.
						'</div>'.
						'</div>'.
						'</div>'.
						'<div class="col-lg-4 col-sm-12">';
				} elseif ( $ka == 1 || $ka == 2 ) {
					$temp = self::get_all();
					if ( !empty( $talkshow ) && !$talkshow_display && !empty( $temp[ $talkshow ] ) && $temp[ $talkshow ]['recurring'] == 1 ) {
						$ytlive = get_option( 'hpm_ytlive_talkshows' );
						$outs = [];
						if ( !empty( $temp[ $talkshow ]['email'] ) ) {
							$outs[] = '<a href="mailto:' . $temp[ $talkshow ]['email'] . '">Email</a>';
						}
						if ( !empty( $temp[ $talkshow ]['phone'] ) ) {
							$outs[] = '<a href="tel://+1' . $temp[ $talkshow ]['phone'] . '">Call/Text</a>';
						}
						if ( !empty( $ytlive[ $talkshow ]['id'] ) ) {
							$outs[] = '<a href="https://www.youtube.com/watch?v=' . $ytlive[ $talkshow ]['id'] . '">Watch</a>';
						}
						$outs[] = '<a href="/listen-live/" data-href="/listen-live" data-dialog="480:855">Listen</a>';
						$result .= '<div class="news-slider ' . $talkshow .'">'.
							'<div class="news-slide-item nav-listen-live">'.
								'<h4>WATCH LIVE</h4>'.
								'<h2><a href="https://www.youtube.com/watch?v=' . $ytlive[ $talkshow ]['id'] .'" rel="bookmark">'. $temp[ $talkshow ]['title'] .'</a></h2>'.
								'<p class="iframe-embed"><iframe id="' . $ytlive[ $talkshow ]['id'] . '" width="560" height="315" src="https://www.youtube.com/embed/' . $ytlive[ $talkshow ]['id'] . '?enablejsapi=1" title="' . $ytlive[ $talkshow ]['title'] .'" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></p>' .
								'<p style="text-align: center;">' . implode( ' | ', $outs ) . '</p>' .
							'</div>' .
								'<img src="https://cdn.houstonpublicmedia.org/assets/images/icons/' . $talkshow .'-logo.webp" alt="' . $temp[ $talkshow ]['title'] . '" width="256" height="218" class="talkshow-logo" />' .
							'</div>';
						$talkshow_display = true;
					} elseif ( !$talkshow_display ) {
						if ( $ka == 1 ) {
							$result .= '<ul class="news-listing row">';
						}
						$result .= '<li class="col-lg-12 col-sm-6">'.
							'<div class="d-flex flex-row-reverse">'.
							'<div class="col-5">'.
							'<div class="box-img"><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . get_the_post_thumbnail( $post, get_the_ID() ) . '</a></div>'.
							'</div>'.
							'<div class="col-7">'.
							'<h4 class="text-light-gray" style="color:#237bbd;"><a href="' . get_the_permalink( $post ) . '">' . hpm_top_cat( $post->ID ) . '</a></h4>'.
							'<p><a href="' . get_the_permalink( $post ) . '">' . get_the_title( $post ) . '</a></p>'.
							'</div>'.
							'</div>'.
							'</li>';
						if ( $ka == 2 ) {
							$result .= '</ul></div>';
						}
					}
				}
			}
		}
		wp_reset_query();
		return $result;
	}
}
new HPM_Liveshows();