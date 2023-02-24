<?php
/**
 * Allows for creating a podcast feed from any category, along with templating, caching, and uploading the media files to an external server
 */

class HPM_Podcasts {

	/**
	 * @var HPM_Media_Upload
	 */
	protected HPM_Media_Upload $process_upload;
	protected array $options;
	protected string $last_update;

	public function __construct() {
		define( 'HPM_PODCAST_PLUGIN_DIR', plugin_dir_path(__FILE__) );
		define( 'HPM_PODCAST_PLUGIN_URL', plugin_dir_url(__FILE__) );
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		add_action( 'init', [ $this, 'create_type' ] );
	}
	/**
	 * Init
	 */

	public function init(): void {
		$this->options = get_option( 'hpm_podcast_settings' );
		$this->last_update = get_option( 'hpm_podcast_last_update' );

		require_once HPM_PODCAST_PLUGIN_DIR . 'classes' . DIRECTORY_SEPARATOR . 'class-background-process.php';

		$this->process_upload = new HPM_Media_Upload();

		add_filter( 'cron_schedules', [ $this, 'cron' ], 10, 2 );
		add_action( 'hpm_podcast_update_refresh', [ $this, 'generate' ] );
		add_filter( 'pre_update_option_hpm_podcast_settings', [ $this, 'options_clean' ], 10, 2 );

		// Add edit capabilities to selected roles
		add_action( 'admin_init', [ $this, 'add_role_caps' ], 999 );

		// Setup metadata for podcast feeds
		add_action( 'load-post.php', [ $this, 'meta_setup' ] );
		add_action( 'load-post-new.php', [ $this, 'meta_setup' ] );

		// Register page templates
		remove_all_actions( 'do_feed_rss2' );
		add_action( 'do_feed_rss2', [ $this, 'feed_template' ], 10, 1 );

		// Create menu in Admin Dashboard
		add_action( 'admin_menu', [ $this, 'create_menu' ] );


		add_action( 'pre_get_posts', [ $this, 'meta_query' ] );

		// Add filter for the_content to display podcast tune-in/promo
		add_filter( 'the_content', [ $this, 'article_footer' ], 10 );
		add_filter( 'get_the_excerpt', [ $this, 'remove_foot_filter' ], 9 );
		add_filter( 'get_the_excerpt', [ $this, 'add_foot_filter' ], 11 );
		add_action( 'wp_head', [ $this, 'add_feed_head' ], 100 );

		if ( !array_key_exists( 'hpm_filter_text' , $GLOBALS['wp_filter'] ) ) {
			add_filter( 'hpm_filter_text', 'wptexturize' );
			add_filter( 'hpm_filter_text', 'convert_smilies' );
			add_filter( 'hpm_filter_text', 'convert_chars' );
			add_filter( 'hpm_filter_text', 'wpautop' );
			add_filter( 'hpm_filter_text', 'shortcode_unautop' );
			add_filter( 'hpm_filter_text', 'do_shortcode' );
		}

		// Register WP-REST API endpoints
		add_action( 'rest_api_init', function() {
			register_rest_route( 'hpm-podcast/v1', '/refresh', [
				'methods'  => 'GET',
				'callback' => [ $this, 'generate' ],
				'permission_callback' => function() {
					return true;
				}
			] );

			register_rest_route( 'hpm-podcast/v1', '/list', [
				'methods'  => 'GET',
				'callback' => [ $this, 'list' ],
				'permission_callback' => function() {
					return true;
				}
			] );

			register_rest_route( 'hpm-podcast/v1', '/list/(?P<feed>[a-zA-Z0-9\-_]+)', [
				'methods'  => 'GET',
				'callback' => [ $this, 'json_feed' ],
				'args' => [
					'feed' => [
						'required' => true
					]
				],
				'permission_callback' => function() {
					return true;
				}
			] );

			register_rest_route( 'hpm-podcast/v1', '/upload/(?P<feed>[a-zA-Z0-9\-_]+)/(?P<id>[\d]+)/(?P<attach>[\d]+)', [
				'methods'  => 'GET',
				'callback' => [ $this, 'upload'],
				'args' => [
					'id' => [
						'required' => true
					],
					'feed' => [
						'required' => true
					]
				],
				'permission_callback' => function() {
					return true;
				}
			] );

			register_rest_route( 'hpm-podcast/v1', '/upload/(?P<id>[\d]+)/progress', [
				'methods'  => 'GET',
				'callback' => [ $this, 'upload_progress'],
				'args' => [
					'id' => [
						'required' => true
					]
				],
				'permission_callback' => function() {
					return true;
				}
			] );
		} );

		// Make sure that the proper cron job is scheduled
		if ( !wp_next_scheduled( 'hpm_podcast_update_refresh' ) ) {
			wp_schedule_event( time(), $this->options['recurrence'], 'hpm_podcast_update_refresh' );
		}
	}

	/**
	 * Add in new cron schedule options
	 *
	 * @param $schedules
	 *
	 * @return mixed
	 */
	public function cron( $schedules ): mixed {
		$schedules['hpm_15min'] = [
			'interval' => 900,
			'display' => __( 'Every 15 Minutes' )
		];
		$schedules['hpm_30min'] = [
			'interval' => 1800,
			'display' => __( 'Every 30 Minutes' )
		];
		return $schedules;
	}

	public function options_clean( $new_value, $old_value ): array {
		$find = [ '{/$}', '{^/}' ];
		$replace = [ '', '' ];
		foreach ( $new_value['credentials'] as $credk => $credv ) {
			foreach ( $credv as $k => $v ) {
				if ( !empty( $v ) && ( $k != 'key' || $k != 'secret' || $k != 'password' ) ) {
					$new_value['credentials'][ $credk ][ $k ] = preg_replace( $find, $replace, $v );
				} elseif ( $k == 'key' || $k == 'secret' || $k == 'password' ) {
					if ( $v == 'Set in wp-config.php' ) {
						$new_value['credentials'][ $credk ][ $k ] = '';
					}
				}
			}
		}
		if ( $new_value['recurrence'] != $old_value['recurrence'] ) {
			if ( wp_next_scheduled( 'hpm_podcast_update_refresh' ) ) {
				wp_clear_scheduled_hook( 'hpm_podcast_update_refresh' );
			}
			wp_schedule_event( time(), $new_value['recurrence'], 'hpm_podcast_update_refresh' );
		}
		return $new_value;
	}

	public function feed_template(): void {
		if ( 'podcasts' === get_query_var( 'post_type' ) ) {
			load_template( get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'single-podcasts.php' );
		} elseif ( file_exists( get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'feed-rss2.php' ) ) {
			load_template( get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'feed-rss2.php' );
		} else {
			get_template_part( 'feed', 'rss2' );
		}
	}

	public function meta_setup(): void {
		add_action( 'add_meta_boxes', [ $this, 'add_meta' ] );
		add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
	}

	public function add_meta(): void {
		add_meta_box(
			'hpm-podcast-meta-class',
			esc_html__( 'Podcast Metadata', 'hpm-podcasts' ),
			[ $this, 'podcast_feed_meta' ],
			'podcasts',
			'normal',
			'core'
		);
		add_meta_box(
			'hpm-podcast-meta-class',
			esc_html__( 'Podcast Feed Information', 'hpm-podcasts' ),
			[ $this, 'podcast_episode_meta' ],
			'post',
			'advanced',
			'default'
		);
		add_meta_box(
			'hpm-show-meta-class',
			esc_html__( 'Social and Show Info', 'hpmv4' ),
			[ $this, 'show_meta_box' ],
			'shows',
			'normal',
			'core'
		);
	}

	public function show_meta_box( $object, $box ): void {
		wp_nonce_field( basename( __FILE__ ), 'hpm_show_class_nonce' );

		$hpm_show_social = get_post_meta( $object->ID, 'hpm_show_social', true );
		if ( empty( $hpm_show_social ) ) {
			$hpm_show_social = [ 'fb' => '', 'twitter' => '', 'yt' => '', 'sc' => '', 'insta' => '', 'tumblr' => '', 'snapchat' => '' ];
		}

		$hpm_show_meta = get_post_meta( $object->ID, 'hpm_show_meta', true );
		if ( empty( $hpm_show_meta ) ) {
			$hpm_show_meta = [
				'times' => '',
				'hosts' => '',
				'ytp' => '',
				'podcast' => '',
				'banners' => [
					'mobile' => '',
					'tablet' => '',
					'desktop' => '',
				]
			];
		}

		$hpm_shows_cat = get_post_meta( $object->ID, 'hpm_shows_cat', true );
		global $post;
		$post_old = $post;
		include __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'show-meta.php';
		wp_reset_query();
		$post = $post_old;
	}

	/**
	 * Adds a textarea for podcast feed-specific excerpts.
	 *
	 * Also, if you are storing your media files on another server, an option to assign your media file to a certain
	 * feed, so that the files can be organized on the remote server, will appear, as well as an area for manual URL entry.
	 */
	public function podcast_episode_meta( $object, $box ): void {
		$pods = $this->options;
		global $post;
		$post_old = $post;
		wp_nonce_field( basename( __FILE__ ), 'hpm_podcast_class_nonce' );
		$hpm_pod_desc = get_post_meta( $object->ID, 'hpm_podcast_ep_meta', true );
		if ( empty( $hpm_pod_desc ) ) {
			$hpm_pod_desc = [ 'title' => '', 'feed' => '', 'description' => '', 'episode' => '', 'season' => '', 'episodeType'
			=> 'full' ];
		}
		include __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR .'podcast-post-meta.php';
		wp_reset_query();
		$post = $post_old;
	}

	/**
	 * Set up metadata for this feed: iTunes categories, episode archive link, iTunes link, Google Play link, number of
	 * episodes in the feed, feed-specific analytics, etc.
	 *
	 * @param $object
	 * @param $box
	 *
	 * @return void
	 */
	public function podcast_feed_meta( $object, $box ): void {
		wp_nonce_field( basename( __FILE__ ), 'hpm_podcast_class_nonce' );
		$exists_cat  = metadata_exists( 'post', $object->ID, 'hpm_pod_cat' );
		$exists_link = metadata_exists( 'post', $object->ID, 'hpm_pod_link' );
		$exists_prod = metadata_exists( 'post', $object->ID, 'hpm_pod_prod' );

		if ( $exists_cat ) {
			$hpm_podcast_cat = get_post_meta( $object->ID, 'hpm_pod_cat', true );
			if ( empty( $hpm_podcast_cat ) ) {
				$hpm_podcast_cat = '';
			}
		} else {
			$hpm_podcast_cat = '';
		}
		if ( $exists_link ) {
			$hpm_podcast_link = get_post_meta( $object->ID, 'hpm_pod_link', true );
			if ( empty( $hpm_podcast_link ) ) {
				$hpm_podcast_link = [
					'page'         => '',
					'limit'        => 0,
					'itunes'       => '',
					'gplay'        => '',
					'spotify'      => '',
					'stitcher'     => '',
					'radiopublic'  => '',
					'pcast'        => '',
					'overcast'     => '',
					'amazon'       => '',
					'tunein'       => '',
					'pandora'      => '',
					'iheart'       => '',
					'categories'   => [ 'first' => '', 'second' => '', 'third' => '' ],
					'type'         => 'episodic',
					'rss-override' => ''
				];
			}
		} else {
			$hpm_podcast_link = [
				'page'         => '',
				'limit'        => 0,
				'itunes'       => '',
				'gplay'        => '',
				'spotify'      => '',
				'stitcher'     => '',
				'radiopublic'  => '',
				'pcast'        => '',
				'overcast'     => '',
				'amazon'       => '',
				'tunein'       => '',
				'pandora'      => '',
				'iheart'       => '',
				'categories'   => [ 'first' => '', 'second' => '', 'third' => '' ],
				'type'         => 'episodic',
				'rss-override' => ''
			];
		}
		if ( $exists_prod ) {
			$hpm_podcast_prod = get_post_meta( $object->ID, 'hpm_pod_prod', true );
			if ( empty( $hpm_podcast_prod ) ) {
				$hpm_podcast_prod = 'internal';
			}
		} else {
			$hpm_podcast_prod = 'internal';
		}
		include __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'podcast-feed-meta.php';
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

		if ( $post_type == 'podcasts' || $post_type == 'post' ) {
			if ( empty( $_POST['hpm_podcast_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_podcast_class_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
		} elseif ( $post_type == 'shows' ) {
			if ( empty( $_POST['hpm_show_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_show_class_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}
		}

		if ( $post->post_type == 'podcasts' ) {
			$hpm_podcast_cat = $_POST['hpm-podcast-cat'];
			$hpm_podcast_prod = $_POST['hpm-podcast-prod'];
			$hpm_podcast_link = [
				'page' => ( sanitize_text_field( $_POST['hpm-podcast-link'] ) ?? '' ),
				'itunes' => ( sanitize_text_field( $_POST['hpm-podcast-link-itunes'] ) ?? '' ),
				'gplay' => ( sanitize_text_field( $_POST['hpm-podcast-link-gplay'] ) ?? '' ),
				'spotify' => ( sanitize_text_field( $_POST['hpm-podcast-link-spotify'] ) ?? '' ),
				'stitcher' => ( sanitize_text_field( $_POST['hpm-podcast-link-stitcher'] ) ?? '' ),
				'radiopublic' => ( sanitize_text_field( $_POST['hpm-podcast-link-radiopublic'] ) ?? '' ),
				'pcast' => ( sanitize_text_field( $_POST['hpm-podcast-link-pcast'] ) ?? '' ),
				'overcast' => ( sanitize_text_field( $_POST['hpm-podcast-link-overcast'] ) ?? '' ),
				'amazon' => ( sanitize_text_field( $_POST['hpm-podcast-link-amazon'] ) ?? '' ),
				'tunein' => ( sanitize_text_field( $_POST['hpm-podcast-link-tunein'] ) ?? '' ),
				'pandora' => ( sanitize_text_field( $_POST['hpm-podcast-link-pandora'] ) ?? '' ),
				'iheart' => ( sanitize_text_field( $_POST['hpm-podcast-link-iheart'] ) ?? '' ),
				'limit' => ( sanitize_text_field( $_POST['hpm-podcast-limit'] ) ?? 0 ),
				'categories' => [
					'first' => $_POST['hpm-podcast-icat-first'],
					'second' => $_POST['hpm-podcast-icat-second'],
					'third' => $_POST['hpm-podcast-icat-third']
				],
				'type' => $_POST['hpm-podcast-type'],
				'rss-override' => ( sanitize_text_field( $_POST['hpm-podcast-rss-override'] ) ?? '' )
			];

			update_post_meta( $post_id, 'hpm_pod_cat', $hpm_podcast_cat );
			update_post_meta( $post_id, 'hpm_pod_link', $hpm_podcast_link );
			update_post_meta( $post_id, 'hpm_pod_prod', $hpm_podcast_prod );
		} elseif ( $post->post_type == 'shows' ) {
			/* Get the posted data and sanitize it for use as an HTML class. */
			$hpm_social = [
				'fb' => ( sanitize_text_field( $_POST['hpm-social-fb'] ) ?? '' ),
				'twitter' => ( sanitize_text_field( $_POST['hpm-social-twitter'] ) ?? '' ),
				'yt' => ( sanitize_text_field( $_POST['hpm-social-yt'] ) ?? '' ),
				'sc' => ( sanitize_text_field( $_POST['hpm-social-sc'] ) ?? '' ),
				'insta' => ( sanitize_text_field( $_POST['hpm-social-insta'] ) ?? ''),
				'tumblr' => ( sanitize_text_field( $_POST['hpm-social-tumblr'] ) ?? ''),
				'snapchat' => ( sanitize_text_field( $_POST['hpm-social-snapchat'] ) ?? '')
			];

			$hpm_show = [
				'times'	=> ( $_POST['hpm-show-times'] ?? '' ),
				'hosts'	=> ( sanitize_text_field( $_POST['hpm-show-hosts'] ) ?? '' ),
				'ytp' => ( sanitize_text_field( $_POST['hpm-show-ytp'] ) ?? '' ),
				'podcast' => ( $_POST['hpm-show-pod'] ?? '' ),
				'banners' => [
					'mobile' => ( sanitize_text_field( $_POST['hpm-show-banner-mobile-id'] ) ?? '' ),
					'tablet' => ( sanitize_text_field( $_POST['hpm-show-banner-tablet-id'] ) ?? '' ),
					'desktop' => ( sanitize_text_field( $_POST['hpm-show-banner-desktop-id'] ) ?? '' ),
				]

			];

			$hpm_shows_cat = ( sanitize_text_field( $_POST['hpm-shows-cat'] ) ?? '' );
			$hpm_shows_top = ( sanitize_text_field( $_POST['hpm-shows-top'] ) ?? '' );

			update_post_meta( $post_id, 'hpm_show_social', $hpm_social );
			update_post_meta( $post_id, 'hpm_show_meta', $hpm_show );
			update_post_meta( $post_id, 'hpm_shows_cat', $hpm_shows_cat );
			update_post_meta( $post_id, 'hpm_shows_top', $hpm_shows_top );
		} elseif ( $post->post_type == 'post' ) {
			$hpm_podcast = [
				'feed' => ( $_POST['hpm-podcast-ep-feed'] ?? '' ),
				'title' => ( preg_replace( '/(&)([^amp])/', '&amp;$2', $_POST['hpm-podcast-title'] ) ?? '' ),
				'description' => ( balanceTags( strip_shortcodes( $_POST['hpm-podcast-description'] ), true ) ?? '' ),
				'episode' => ( sanitize_text_field( $_POST['hpm-podcast-episode'] ) ?? '' ),
				'episodeType' => ( $_POST['hpm-podcast-episodetype'] ?? 'full' ),
				'season' => ( sanitize_text_field( $_POST['hpm-podcast-season'] ) ?? '' )
			];

			update_post_meta( $post_id, 'hpm_podcast_ep_meta', $hpm_podcast );

			$sg_url = ( isset( $_POST['hpm-podcast-sg-file'] ) ? sanitize_text_field( $_POST['hpm-podcast-sg-file'] ) : '' );

			$hpm_enclose = get_post_meta( $post_id, 'hpm_podcast_enclosure', true );

			if ( !empty( $this->options['upload-media'] ) ) {
				if ( !empty( $sg_url ) ) {
					if ( !empty( $hpm_enclose ) ) {
						if ( $hpm_enclose['url'] !== $sg_url ) {
							$hpm_enclose['url'] = $sg_url;
							update_post_meta( $post_id, 'hpm_podcast_enclosure', $hpm_enclose );
						}
					}
				}
			}
		}
		return $post_id;
	}

	/**
	 * Create custom post type to house our podcast feeds
	 */
	public static function create_type(): void {
		register_post_type( 'podcasts', [
			'labels' => [
				'name' => __( 'Podcasts' ),
				'singular_name' => __( 'Podcast' ),
				'menu_name' => __( 'Podcasts' ),
				'add_new_item' => __( 'Add New Podcast' ),
				'edit_item' => __( 'Edit Podcast' ),
				'new_item' => __( 'New Podcast' ),
				'view_item' => __( 'View Podcast' ),
				'search_items' => __( 'Search Podcasts' ),
				'not_found' => __( 'Podcast Not Found' ),
				'not_found_in_trash' => __( 'Podcast not found in trash' )
			],
			'description' => 'All of Houston Public Media\'s podcasting information, including links, content, and more',
			'public' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-playlist-audio',
			'has_archive' => true,
			'rewrite' => [
				'slug' => __( 'podcasts' ),
				'with_front' => false,
				'feeds' => true,
				'pages' => true
			],
			'supports' => [ 'title', 'editor', 'thumbnail', 'author', 'excerpt' ],
			'taxonomies' => [ 'post_tag' ],
			'capability_type' => [ 'hpm_podcast', 'hpm_podcasts' ],
			'map_meta_cap' => true,
			'show_in_graphql' => true,
			'graphql_single_name' => 'Podcast',
			'graphql_plural_name' => 'Podcasts'
		]);

		register_post_type( 'shows', [
			'labels' => [
				'name' => __( 'Shows' ),
				'singular_name' => __( 'Show' ),
				'menu_name' => __( 'Shows' ),
				'add_new_item' => __( 'Add New Show' ),
				'edit_item' => __( 'Edit Show' ),
				'new_item' => __( 'New Show' ),
				'view_item' => __( 'View Show' ),
				'search_items' => __( 'Search Shows' ),
				'not_found' => __( 'Show Not Found' ),
				'not_found_in_trash' => __( 'Show not found in trash' )
			],
			'description' => 'Listings and content for Houston Public Media\'s locally-produced shows for TV, radio, and the web',
			'public' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-video-alt3',
			'has_archive' => true,
			'rewrite' => [
				'slug' => __( 'shows' ),
				'with_front' => false,
				'feeds' => false,
				'pages' => true
			],
			'supports' => [ 'title', 'editor', 'thumbnail' ],
			'taxonomies' => [ 'post_tag' ],
			'capability_type' => [ 'hpm_show','hpm_shows' ],
			'map_meta_cap' => true,
			'show_in_graphql' => true,
			'graphql_single_name' => 'Show',
			'graphql_plural_name' => 'Shows'
		]);
	}

	public function meta_query( $query ): void {
		if ( $query->is_archive() && $query->is_main_query() && !is_admin() ) {
			if ( $query->get( 'post_type' ) == 'podcasts' || $query->get( 'post_type' ) == 'shows' ) {
				$query->set( 'tag__not_in', [ 48498 ] );
			}
		}
	}

	public static function list_inactive( $type ): void {
		echo '<h2>Inactive</h2>';
		$items = new WP_Query([
			'post_type' => $type,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'tag' => 'inactive',
			'orderby' => 'post_title',
			'order' => 'ASC'
		]);
		if ( $items->have_posts() ) {
			while ( $items->have_posts() ) {
				$items->the_post();
				get_template_part( 'content', $type );
			}
		}
	}

	/**
	 * Add capabilities to the selected roles (default is admin only)
	 */
	public function add_role_caps(): void {
		$pods = $this->options;
		foreach( $pods['roles'] as $the_role ) {
			$role = get_role( $the_role );
			$role->add_cap( 'read' );
			$role->add_cap( 'read_hpm_podcast');
			$role->add_cap( 'read_private_hpm_podcasts' );
			$role->add_cap( 'edit_hpm_podcast' );
			$role->add_cap( 'edit_hpm_podcasts' );
			$role->add_cap( 'edit_others_hpm_podcasts' );
			$role->add_cap( 'edit_published_hpm_podcasts' );
			$role->add_cap( 'publish_hpm_podcasts' );
			$role->add_cap( 'delete_others_hpm_podcasts' );
			$role->add_cap( 'delete_private_hpm_podcasts' );
			$role->add_cap( 'delete_published_hpm_podcasts' );
			$role->add_cap( 'read_hpm_show');
			$role->add_cap( 'read_private_hpm_shows' );
			$role->add_cap( 'edit_hpm_show' );
			$role->add_cap( 'edit_hpm_shows' );
			$role->add_cap( 'edit_others_hpm_shows' );
			$role->add_cap( 'edit_published_hpm_shows' );
			$role->add_cap( 'publish_hpm_shows' );
			$role->add_cap( 'delete_others_hpm_shows' );
			$role->add_cap( 'delete_private_hpm_shows' );
			$role->add_cap( 'delete_published_hpm_shows' );
		}
	}

	/**
	 * Creates the Settings menu in the Admin Dashboard
	 */
	public function create_menu(): void {
		add_submenu_page( 'edit.php?post_type=podcasts', 'HPM Podcast Settings', 'Settings', 'manage_options', 'hpm-podcast-settings', [ $this, 'settings_page' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Registers the settings group for HPM Podcasts
	 */
	public function register_settings(): void {
		register_setting( 'hpm-podcast-settings-group', 'hpm_podcast_settings' );
	}

	/**
	 * Creates the Settings menu in the Admin Dashboard
	 */
	public function settings_page(): void {
		$pods = $this->options;
		$pods_last = $this->last_update;
		$upload_sftp = ' hidden';
		if ( $pods_last !== 'none' ) {
			$last_refresh = date( 'F j, Y @ g:i A', $pods_last );
		} else {
			$last_refresh = 'Never';
		}
		include __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'podcast-admin.php';
	}

	/**
	 * Uploads
	 *
	 * @param WP_REST_Request $request This function accepts a rest request to process data.
	 *
	 * @return mixed
	 */
	public function upload( WP_REST_Request $request ): mixed {
		if ( empty( $request['feed'] ) ) {
			return new WP_Error( 'rest_api_sad', esc_html__( 'Unable to upload media. Please choose a podcast feed.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		} elseif ( empty( $request['id'] ) ) {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No post ID provided, cannot upload media. Please save your post and try again.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		}

		$this->process_upload->data( [ 'id' => $request['id'], 'feed' => $request['feed'], 'attach' => $request['attach'] ] )->dispatch();
		//update_post_meta( $request['id'], 'hpm_podcast_status', [ 'status' => 'in-progress', 'message' => esc_html__( 'Upload process initializing.', 'hpm-podcasts' ) ] );

		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'Podcast upload started successfully.', 'hpm-podcasts' ), 'data' => [ 'status' => 200 ] ] );
	}

	/**
	 * Upload progress reports
	 *
	 * @param WP_REST_Request $request This function accepts a rest request to process data.
	 *
	 * @return WP_HTTP_Response|WP_REST_Response|WP_Error
	 */
	public function upload_progress( WP_REST_Request $request ): WP_HTTP_Response|WP_REST_Response|WP_Error {
		if ( empty( $request['id'] ) ) {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No post ID provided, cannot find upload status. Please save your post and try again.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		}

		$status = get_post_meta( $request['id'], 'hpm_podcast_status', true );

		if ( empty( $status ) ) {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No upload status found, please try your upload again.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		} else {
			if ( $status['status'] == 'error' ) {
				return new WP_Error( 'rest_api_sad', esc_html__( $status['message'], 'hpm-podcasts' ), [ 'status' => 500 ] );
			} elseif ( $status['status'] == 'in-progress' ) {
				return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( $status['message'], 'hpm-podcasts' ), 'data' => [ 'current' => 'in-progress', 'status' => 200 ] ] );
			} elseif ( $status['status'] == 'success' ) {
				delete_post_meta( $request['id'], 'hpm_podcast_status', '' );
				$data = get_post_meta( $request['id'], 'hpm_podcast_enclosure', true );
				return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( $status['message'], 'hpm-podcasts' ), 'data' => [ 'url' => $data['url'], 'current' => 'success', 'status' => 200 ] ] );
			}
		}
	}

	/**
	 * Pull a list of podcasts, generate the feeds, and save them as flat XML files in the database
	 *
	 * @return WP_HTTP_Response|WP_REST_Response|WP_Error
	 */
	static public function generate( WP_REST_Request $request = null ): WP_HTTP_Response|WP_REST_Response|WP_Error {
		$pods = get_option( 'hpm_podcast_settings' );
		$ds = DIRECTORY_SEPARATOR;
		$protocol = 'https://';
		$json = [
			'version' => 'https://jsonfeed.org/version/1',
			'title' => '',
			'home_page_url' => '',
			'feed_url' => '',
			'description' => '',
			'icon' => '',
			'favicon' => '',
			'categories' => [],
			'keywords' => [],
			'author' => [
				'name' => '',
				'email' => ''
			],
			'items' => []
		];

		$podcasts = new WP_Query([
			'post_type' => 'podcasts',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => [[
				'key' => 'hpm_pod_prod',
				'compare' => '=',
				'value' => 'internal'
			]]
		]);

		$xsl = str_replace( 'http://', $protocol, get_stylesheet_directory_uri() . $ds . 'podcast.xsl' );

		if ( !empty( $pods['recurrence'] ) ) {
			if ( $pods['recurrence'] == 'hpm_5min' ) {
				$frequency = '5';
			} elseif ( $pods['recurrence'] == 'hpm_15min' ) {
				$frequency = '15';
			} elseif ( $pods['recurrence'] == 'hpm_30min' ) {
				$frequency = '30';
			} elseif ( $pods['recurrence'] == 'hourly' ) {
				$frequency = '60';
			} else {
				$frequency = '60';
			}
		} else {
			$frequency = '60';
		}
		global $post;
		if ( $podcasts->have_posts() ) {
			while ( $podcasts->have_posts() ) {
				$podcasts->the_post();
				$pod_id = get_the_ID();
				$catslug = get_post_meta( $pod_id, 'hpm_pod_cat', true );
				$podlink = get_post_meta( $pod_id, 'hpm_pod_link', true );
				$last_id = get_post_meta( $pod_id, 'hpm_pod_last_id', true );
				$current_post = $post;
				$podcast_title = $podcasts->post->post_name;
				$perpage = -1;
				if ( !empty( $podlink['limit'] ) && $podlink['limit'] != 0 && is_numeric( $podlink['limit'] ) ) {
					$perpage = $podlink['limit'];
				}
				$podeps = new WP_Query([
					'post_type' => 'post',
					'post_status' => 'publish',
					'cat' => $catslug,
					'posts_per_page' => $perpage,
					'meta_query' => [[
						'key' => 'hpm_podcast_enclosure',
						'compare' => 'EXISTS'
					]]
				]);
				if ( $podeps->have_posts() && $request === null ) {
					$first_id = $podeps->post->ID;
					$modified = get_the_modified_date('U', $first_id );
					if ( !empty( $last_id['id'] ) && $last_id['id'] == $first_id && $last_id['modified'] == $modified ) {
						continue;
					}
				}
				$main_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$favicon = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumb' );
				$categories = [];
				foreach ( $podlink['categories'] as $pos => $cats ) {
					$categories[$pos] = explode( '||', $cats );
				}
				$pod_tags = wp_get_post_tags( $pod_id );
				$pod_tag_array = [];
				foreach ( $pod_tags as $t ) {
					$pod_tag_array[] = $t->name;
				}

				$json['title'] = get_the_title();
				$json['home_page_url'] = $podlink['page'];
				$json['feed_url'] = get_the_permalink().'feed/json';
				$json['description'] = get_the_content();
				$json['icon'] = $main_image[0];
				$json['favicon'] = $favicon[0];
				$json['author']['name'] = $pods['owner']['name'];
				$json['author']['email'] = $pods['owner']['email'];
				$json['keywords'] = $pod_tag_array;
				foreach ( $categories as $cats ) {
					foreach ( $cats as $ca ) {
						$json['categories'][] = $ca;
					}
				}
				$json['items'] = [];

				ob_start();
				echo "<?xml version=\"1.0\" encoding=\"" . get_option( 'blog_charset' ) . "\"?>\n<?xml-stylesheet type=\"application/xml\" media=\"screen\" href=\"" . $xsl . "\"?>\n";
				do_action( 'rss_tag_pre', 'rss2' ); ?>
<rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:atom="http://www.w3.org/2005/Atom" <?php do_action( 'rss2_ns' ); ?>>
	<channel>
		<title><?php the_title_rss(); ?></title>
		<atom:link href="<?php echo get_the_permalink(); ?>" rel="self" type="application/rss+xml" />
		<link><?php echo $podlink['page']; ?></link>
		<description><![CDATA[<?php the_content_feed(); ?>]]></description>
		<language><?php bloginfo_rss( 'language' ); ?></language>
		<copyright>&#x2117; &amp; &#xA9; <?PHP echo date('Y'); ?> Houston Public Media</copyright>
		<ttl><?php echo $frequency; ?></ttl>
		<pubDate><?php echo date('r'); ?></pubDate>
		<itunes:summary><![CDATA[<?php the_content_feed(); ?>]]></itunes:summary>
		<itunes:owner>
			<itunes:name><![CDATA[<?php echo $pods['owner']['name']; ?>]]></itunes:name>
			<itunes:email><?php echo $pods['owner']['email']; ?></itunes:email>
		</itunes:owner>
		<itunes:keywords><![CDATA[<?php echo implode( ', ', $pod_tag_array ); ?>]]></itunes:keywords>
		<itunes:subtitle><?PHP echo get_the_excerpt();  ?></itunes:subtitle>
		<itunes:author><?php echo $pods['owner']['name']; ?></itunes:author>
		<itunes:explicit><?php
			if ( in_array( 'explicit', $pod_tag_array ) ) {
				echo "yes";
			} else {
				echo "no";
			} ?></itunes:explicit>
		<itunes:type><?php echo $podlink['type']; ?></itunes:type>
<?PHP
		foreach ( $categories as $podcat ) {
			if ( count( $podcat ) == 2 ) { ?>
		<itunes:category text="<?PHP echo htmlentities( $podcat[0] ); ?>">
			<itunes:category text="<?PHP echo htmlentities( $podcat[1] ); ?>" />
		</itunes:category>
<?PHP
			} else {
				if ( !empty( $podcat[0] ) ) { ?>
		<itunes:category text="<?PHP echo htmlentities( $podcat[0] ); ?>" />
<?PHP
				}
			}
		}
		if ( !empty( $main_image ) ) { ?>
		<itunes:image href="<?PHP echo $main_image[0]; ?>" />
		<image>
			<url><?php echo $main_image[0]; ?></url>
			<title><?PHP the_title_rss(); ?></title>
		</image><?php
		}
		do_action( 'rss2_head' );
		if ( $podeps->have_posts() ) {
			while ( $podeps->have_posts() ) {
				$podeps->the_post();
				$epid = get_the_ID();
				if ( $podeps->current_post == 0 ) {
					$last = [ 'id' => $epid, 'modified' => get_the_modified_time( 'U' ) ];
					update_post_meta( $pod_id, 'hpm_pod_last_id', $last );
				}
				$a_meta = get_post_meta( $epid, 'hpm_podcast_enclosure', true );
				if ( empty( $a_meta ) ) {
					continue;
				}
				$pod_image = wp_get_attachment_image_src( get_post_thumbnail_id( $epid ), 'full' );
				$tags = wp_get_post_tags( $epid );
				$tag_array = [];
				foreach ( $tags as $t ) {
					$tag_array[] = $t->name;
				}
				$pod_desc = get_post_meta( $epid, 'hpm_podcast_ep_meta', true );

				$media_file = str_replace( 'http://', $protocol, $a_meta['url'] );

				if ( !empty( $pod_desc['title'] ) ) {
					$item_title = $pod_desc['title'];
				} else {
					$item_title = get_the_title();
				}

				$content = "<p>".strip_shortcodes( get_the_content() )."</p>";
				$json['items'][] = [
					'id' => $epid,
					'title' => $item_title,
					'permalink' => get_permalink(),
					'content_html' => $content,
					'content_text' => strip_shortcodes( wp_strip_all_tags( get_the_content() ) ),
					'excerpt' => get_the_excerpt(),
					'date_published' => get_the_date( 'c', '' ),
					'date_modified' => get_the_modified_date( 'c', '' ),
					'author' => coauthors( '; ', '; ', '', '', false ),
					'thumbnail' => ( is_array( $pod_image ) ? $pod_image[0] : '' ),
					'attachments' => [
						'url' => $media_file,
						'mime_type' => $a_meta['mime'],
						'filesize' => $a_meta['filesize'],
						'duration_in_seconds' => $a_meta['length']
					],
					'season' => ( !empty( $pod_desc['season'] ) ? $pod_desc['season'] : '' ),
					'episode' => ( !empty( $pod_desc['episode'] ) ? $pod_desc['episode'] : '' ),
					'episodeType' => ( !empty( $pod_desc['episodeType'] ) ? $pod_desc['episodeType'] : '' )
				]; ?>
		<item>
			<title><?php echo $item_title; ?></title>
			<link><?php the_permalink(); ?></link>
			<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true, $epid ), false); ?></pubDate>
			<guid isPermaLink="true"><?php the_permalink(); ?></guid>
			<description><![CDATA[<?php echo ( !empty( $pod_desc['description'] ) ? $pod_desc['description'] : $content ); ?>]]></description>
			<author><?php
				if ( function_exists( 'coauthors' ) ) {
					echo str_replace( '&', 'and', coauthors( ', ', ', ', '', '', false ) );
				} else {
					echo str_replace( '&', 'and', get_the_author() );
				} ?></author>
			<itunes:author><?php
				if ( function_exists( 'coauthors' ) ) {
					echo str_replace( '&', 'and', coauthors( ', ', ', ', '', '', false ) );
				} else {
					echo str_replace( '&', 'and', get_the_author() );
				} ?></itunes:author>
			<itunes:keywords><![CDATA[<?php echo implode( ',', $tag_array ); ?>]]></itunes:keywords>
			<itunes:summary><![CDATA[<?php echo ( !empty( $pod_desc['description'] ) ? $pod_desc['description'] : $content ); ?>]]></itunes:summary>
<?php
				if ( !empty( $pod_image ) ) { ?>
			<itunes:image href="<?PHP echo $pod_image[0]; ?>"/>
<?php
				} ?>
			<itunes:explicit><?php
				if ( in_array( 'explicit', $tag_array ) ) {
					echo "yes";
				} else {
					echo "no";
				}; ?></itunes:explicit>
			<enclosure url="<?PHP echo $media_file; ?>" length="<?PHP echo $a_meta['filesize']; ?>" type="<?php echo $a_meta['mime']; ?>" />
			<itunes:duration><?PHP echo $a_meta['length']; ?></itunes:duration>
<?php
				if ( !empty( $pod_desc['episode'] ) ) { ?>
			<itunes:episode><?php echo $pod_desc['episode']; ?></itunes:episode>
<?php
				}
				if ( !empty( $pod_desc['episodeType'] ) ) { ?>
			<itunes:episodeType><?php echo $pod_desc['episodeType']; ?></itunes:episodeType>
<?php
				}
				if ( !empty( $pod_desc['season'] ) ) { ?>
			<itunes:season><?php echo $pod_desc['season']; ?></itunes:season>
<?php
				}
				do_action( 'rss2_item' ); ?>
		</item>
<?php
			}
		}
		wp_reset_query();
		$post = $current_post; ?>
	</channel>
</rss><?php
				$getContent = ob_get_contents();
				ob_end_clean();
				update_option( 'hpm_podcast-'.$podcast_title, $getContent, false );
				update_option( 'hpm_podcast-json-'.$podcast_title, json_encode( $json ), false );
				//sleep(2);
			}
			$t = time();
			$offset = get_option( 'gmt_offset' ) * 3600;
			$time = $t + $offset;
			$date = date( 'F j, Y @ g:i A', $time );
			update_option( 'hpm_podcast_last_update', $time, false );
			return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'Podcast feeds successfully updated!', 'hpm-podcasts' ), 'data' => [ 'date' => $date, 'timestamp' => $time, 'status' =>
				200 ] ] );
		} else {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No podcast feeds have been defined. Please create one and try again.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		}
	}

	/**
	 * Retrieve metadata from an audio file's ID3 tags
	 *
	 * (Including from WP Media API since it isn't available during JSON API calls)
	 *
	 * @param string $file Path to file.
	 *
	 * @return array|bool Returns array of metadata, if found.
	 */
	private function audio_meta( string $file ): bool|array {
		if ( !file_exists( $file ) ) {
			return false;
		}
		$metadata = [];

		if ( !defined( 'GETID3_TEMP_DIR' ) ) {
			define( 'GETID3_TEMP_DIR', get_temp_dir() );
		}

		if ( !class_exists( 'getID3', false ) ) {
			require( ABSPATH . WPINC . '/ID3/getid3.php' );
		}
		$id3 = new getID3();
		$data = $id3->analyze( $file );

		if ( !empty( $data['audio'] ) ) {
			unset( $data['audio']['streams'] );
			$metadata = $data['audio'];
		}

		if ( !empty( $data['fileformat'] ) ) {
			$metadata['fileformat'] = $data['fileformat'];
		}
		if ( !empty( $data['filesize'] ) ) {
			$metadata['filesize'] = (int) $data['filesize'];
		}
		if ( !empty( $data['mime_type'] ) ) {
			$metadata['mime_type'] = $data['mime_type'];
		}
		if ( !empty( $data['playtime_seconds'] ) ) {
			$metadata['length'] = (int) round( $data['playtime_seconds'] );
		}
		if ( !empty( $data['playtime_string'] ) ) {
			$metadata['length_formatted'] = $data['playtime_string'];
		}

		$this->add_id3_data( $metadata, $data );

		return $metadata;
	}

	/**
	 * Parse ID3v2, ID3v1, and getID3 comments to extract usable data
	 *
	 * (Including from WP Media API since it isn't available during JSON API calls)
	 *
	 * @param array $metadata An existing array with data
	 * @param array $data Data supplied by ID3 tags
	 */
	private function add_id3_data( array &$metadata, array $data ): void {
		foreach ( [ 'id3v2', 'id3v1' ] as $version ) {
			if ( !empty( $data[ $version ]['comments'] ) ) {
				foreach ( $data[ $version ]['comments'] as $key => $list ) {
					if ( 'length' !== $key && ! empty( $list ) ) {
						$metadata[ $key ] = wp_kses_post( reset( $list ) );
						// Fix bug in byte stream analysis.
						if ( 'terms_of_use' === $key && str_starts_with( $metadata[ $key ], 'yright notice.' ) ) {
							$metadata[ $key ] = 'Cop' . $metadata[ $key ];
						}
					}
				}
				break;
			}
		}

		if ( !empty( $data['id3v2']['APIC'] ) ) {
			$image = reset( $data['id3v2']['APIC']);
			if ( ! empty( $image['data'] ) ) {
				$metadata['image'] = [
					'data' => $image['data'],
					'mime' => $image['image_mime'],
					'width' => $image['image_width'],
					'height' => $image['image_height']
				];
			}
		} elseif ( !empty( $data['comments']['picture'] ) ) {
			$image = reset( $data['comments']['picture'] );
			if ( !empty( $image['data'] ) ) {
				$metadata['image'] = [
					'data' => $image['data'],
					'mime' => $image['image_mime']
				];
			}
		}
	}

	/**
	 * Generate podcast feed promo at the bottom of article content
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function article_footer( $content ): string {
		if ( is_single() && in_the_loop() && is_main_query() ) {
			$meta = get_post_meta( get_the_ID(), 'hpm_podcast_ep_meta', true );
			if ( !empty( $meta['feed'] ) ) {
				$poids = new WP_Query([
					'name' => $meta['feed'],
					'post_status' => 'publish',
					'post_type' => 'podcasts',
					'posts_per_page' => 1
				]);
				if ( $poids->have_posts() ) {
					$content .= HPM_Podcasts::show_social( $poids->post->ID, true, '', true );
				}
			}
		}
		return $content;
	}

	public static function show_social( $pod_id = '', $lede = false, $show_id = '', $full_list = false ): string {
		$temp = $output = $template = '';
		$badges = 'https://cdn.hpm.io/assets/images/podcasts/';
		if ( !empty( $show_id ) ) {
			$template = get_post_meta( $show_id, '_wp_page_template', true );
		}

		if ( !empty( $pod_id ) && $template !== 'single-shows-podcast.php' ) {
			$pod_link = get_post_meta( $pod_id, 'hpm_pod_link', true );
			if ( !empty( $pod_link['itunes'] ) ) {
				$temp .= '<li><a href="' . $pod_link['itunes'] . '" rel="noopener" target="_blank" title="Subscribe on Apple Podcasts"><img src="' . $badges . 'apple.png" alt="Subscribe on Apple Podcasts" title="Subscribe on Apple Podcasts"></a></li>';
			}
			if ( !empty( $pod_link['gplay'] ) ) {
				$temp .= '<li><a href="' . $pod_link['gplay'] . '" rel="noopener" target="_blank" title="Subscribe on Google Podcasts"><img src="' . $badges . 'google_podcasts.png" alt="Subscribe on Google Podcasts" title="Subscribe on Google Podcasts"></a></li>';
			}
			if ( !empty( $pod_link['spotify'] ) ) {
				$temp .= '<li><a href="' . $pod_link['spotify'] . '" rel="noopener" target="_blank" title="Subscribe on Spotify"><img src="' . $badges . 'spotify.png" alt="Subscribe on Spotify" title="Subscribe on Spotify"></a></li>';
			}
			if ( $full_list ) {
				if ( !empty( $pod_link['stitcher'] ) ) {
					$temp .= '<li><a href="' . $pod_link['stitcher'] . '" rel="noopener" target="_blank" title="Subscribe on Stitcher"><img src="' . $badges . 'stitcher.png" alt="Subscribe on Stitcher" title="Subscribe on Stitcher"></a></li>';
				}
				if ( !empty( $pod_link['tunein'] ) ) {
					$temp .= '<li><a href="' . $pod_link['tunein'] . '" rel="noopener" target="_blank" title="Subscribe on TuneIn"><img src="' . $badges . 'tunein.png" alt="Subscribe on TuneIn" title="Subscribe on TuneIn"></a></li>';
				}
				if ( !empty( $pod_link['iheart'] ) ) {
					$temp .= '<li><a href="' . $pod_link['iheart'] . '" rel="noopener" target="_blank" title="Subscribe on iHeart"><img src="' . $badges . 'iheart_radio.png" alt="Subscribe on iHeart" title="Subscribe on iHeart"></a></li>';
				}
				if ( !empty( $pod_link['pandora'] ) ) {
					$temp .= '<li><a href="' . $pod_link['pandora'] . '" rel="noopener" target="_blank" title="Subscribe on Pandora"><img src="' . $badges . 'pandora.png" alt="Subscribe on Pandora" title="Subscribe on Pandora"></a></li>';
				}
				if ( !empty( $pod_link['radiopublic'] ) ) {
					$temp .= '<li><a href="' . $pod_link['radiopublic'] . '" rel="noopener" target="_blank" title="Subscribe on RadioPublic"><img src="' . $badges . 'radio_public.png" alt="Subscribe on RadioPublic" title="Subscribe on RadioPublic"></a></li>';
				}
				if ( !empty( $pod_link['pcast'] ) ) {
					$temp .= '<li><a href="' . $pod_link['pcast'] . '" rel="noopener" target="_blank" title="Subscribe on Pocket Casts"><img src="' . $badges . 'pocketcasts.png" alt="Subscribe on Pocket Casts" title="Subscribe on Pocket Casts"></a></li>';
				}
				if ( !empty( $pod_link['overcast'] ) ) {
					$temp .= '<li><a href="' . $pod_link['overcast'] . '" rel="noopener" target="_blank" title="Subscribe on Overcast"><img src="' . $badges . 'overcast.png" alt="Subscribe on Overcast" title="Subscribe on Overcast"></a></li>';
				}
				if ( !empty( $pod_link['amazon'] ) ) {
					$temp .= '<li><a href="' . $pod_link['amazon'] . '" rel="noopener" target="_blank" title="Subscribe on Amazon Music"><img src="' . $badges . 'amazon.png" alt="Subscribe on Amazon Music" title="Subscribe on Amazon Music"></a></li>';
				}
			}
			$temp .= '<li><a href="' . ( !empty( $pod_link['rss-override'] ) ? $pod_link['rss-override'] : get_permalink( $pod_id ) ).'" target="_blank" title="Subscribe via RSS"><img src="' . $badges . 'rss.png" alt="Subscribe via RSS" title="Subscribe via RSS"></a></li>';
		}
		if ( !empty( $show_id ) ) {
			$social = get_post_meta( $show_id, 'hpm_show_social', true );
			if ( !empty( $social['insta'] ) ) {
				$temp .= '<li class="service-icon instagram"><a href="https://instagram.com/' . $social['insta'] . '" rel="noopener" target="_blank" title="Instagram">' . hpm_svg_output( 'instagram' ) . '</a></li>';
			}
			if ( !empty( $social['yt'] ) ) {
				$temp .= '<li class="service-icon youtube"><a href="' . $social['yt'] . '" rel="noopener" target="_blank" title="YouTube">' . hpm_svg_output( 'youtube' ) . '</a></li>';
			}
			if ( !empty( $social['twitter'] ) ) {
				$temp .= '<li class="service-icon twitter"><a href="https://twitter.com/' . $social['twitter'] . '" rel="noopener" target="_blank" title="Twitter">' . hpm_svg_output( 'twitter' ) . '</a></li>';
			}
			if ( !empty( $social['fb'] ) ) {
				$temp .= '<li class="service-icon facebook"><a href="https://www.facebook.com/' . $social['fb'] . '" rel="noopener" target="_blank" title="Facebook">' . hpm_svg_output( 'facebook' ) . '</a></li>';
			}
		}
		if ( !empty( $pod_link ) && $lede ) {
			$output = '<p>&nbsp;</p><div class="podcast-episode-info"><h3>This article is part of the <em><a href="' . $pod_link['page'] . '">' . get_the_title( $pod_id ) . '</a></em> podcast</h3><ul class="podcast-badges">' . $temp . '</ul></div>';
		} else {
			if ( !empty( $temp ) ) {
				$output = '<ul class="podcast-badges">' . $temp . '</ul>';
			}
		}
		return $output;
	}

	public static function show_header( $id ): string {
		$temp = '';
		$options = get_post_meta( $id, 'hpm_show_meta', true );
		$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
		$count = 0;
		foreach ( $options['banners'] as $op ) {
			if ( !empty( $op ) ) {
				$count++;
			}
		}

		if ( $count > 0 ) {
			$temp .= '<div class="page-banner"><picture>';
			foreach ( $options['banners'] as $bk => $bv ) {
				if ( !empty( $bv ) ) {
					if ( $bk == 'mobile' ) {
						$temp .= '<source srcset="' . wp_get_attachment_url( $bv ) . '" media="(max-width: 34em)" />';
					} elseif ( $bk == 'tablet' ) {
						$temp .= '<source srcset="' . wp_get_attachment_url( $bv ) . '" media="(max-width: 52.5em)" />';
					} elseif ( $bk == 'desktop' ) {
						$temp .= '<source srcset="' . wp_get_attachment_url( $bv ) . '" />';
					}
				}
			}
			$default = $options['banners']['desktop'] ?? $options['banners']['tablet'] ?? $options['banners']['mobile'];
			$temp .= '<img src="' . wp_get_attachment_url( $default ) . '" alt="' . get_the_title( $id ) . ' page banner" /></picture></div>';
		}
		$output =
			'<header class="page-header' . ( !empty( $temp ) ? ' banner' : '' ) . '">' .
				'<h1 class="page-title"' . ( !empty( $temp ) ? ' hidden' : '' ) . '>' . get_the_title( $id ) . '</h1>' .
				$temp;
		$no = 0;
		foreach( $options as $sk => $sh ) {
			if ( !empty( $sh ) && $sk != 'banners' ) {
				$no++;
			}
		}
		foreach( $social as $soc ) {
			if ( !empty( $soc ) ) {
				$no++;
			}
		}
		if ( $no > 0 ) {
			$social = HPM_Podcasts::show_social( $options['podcast'], false, $id );
			$output .= '<div id="station-social"' . ( empty( $social ) ? ' class="station-no-social"' : '' ) . '>';
			$output .= '<h3>' . ( !empty( $options['times'] ) ? $options['times'] : '' ) .'</h3>';
			$output .= $social . '</div>';
		}
		$output .= '</header>';
		return $output;
	}

	public static function list_episodes( $show_id ): array {
		$episodes = [];
		$cat_no = get_post_meta( $show_id, 'hpm_shows_cat', true );
		$top =  get_post_meta( $show_id, 'hpm_shows_top', true );
		$cat_args = [
			'cat' => $cat_no,
			'orderby' => 'date',
			'order'   => 'DESC',
			'posts_per_page' => 16,
			'ignore_sticky_posts' => 1
		];
		if ( !empty( $top ) && $top !== 'None' ) {
			$top_art = new WP_Query( [ 'p' => $top ] );
			$cat_args['posts_per_page']--;
			$cat_args['post__not_in'] = [ $top ];
			if ( $top_art->have_posts() ) {
				foreach ( $top_art->posts as $tp ) {
					$episodes[] = $tp;
				}
			}
		}
		$cat = new WP_Query( $cat_args );
		if ( $cat->have_posts() ) {
			foreach ( $cat->posts as $cp ) {
				$episodes[] = $cp;
			}
		}
		return $episodes;
	}

	public function remove_foot_filter( $content ) {
		if ( has_filter( 'the_content', [ $this, 'article_footer' ] ) ) {
			remove_filter( 'the_content', [ $this, 'article_footer' ] );
		}
		return $content;
	}

	public function add_foot_filter( $content ) {
		add_filter( 'the_content', [ $this, 'article_footer' ], 15 );
		return $content;
	}

	public function add_feed_head(): void {
		global $wp_query;
		if ( !is_home() && !is_404() && get_post_type() === 'shows' ) {
			$ID = $wp_query->get_queried_object_id();
			$show_meta = get_post_meta( $ID, 'hpm_show_meta', true );
			if ( !empty( $show_meta['podcast'] ) ) { ?>
		<link rel="alternate" type="application/rss+xml" title="<?php echo get_the_title( $ID ); ?> Podcast Feed" href="<?php echo get_the_permalink( $show_meta['podcast'] ); ?>" />
<?php
			} else {
				$show_cat = get_post_meta( $ID, 'hpm_shows_cat', true ); ?>
		<link rel="alternate" type="application/rss+xml" title="<?php echo get_the_title( $ID ); ?> RSS Feed" href="<?php echo get_term_feed_link( $show_cat ); ?>" />
<?php
			}
		}
	}

	/**
	 * Return list of active podcast feeds with feed URLs and most recent files
	 *
	 * @param WP_REST_Request|null $request
	 *
	 * @return WP_Error|string
	 */
	public function list( WP_REST_Request $request = null ): WP_Error|string {
		$list = get_transient( 'hpm_podcasts_list' );
		if ( !empty( $list ) ) {
			return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'Podcast feed list', 'hpm-podcasts' ), 'data' => [ 'list' => $list, 'status' =>	200 ] ] );
		}
		$protocol = 'https://';
		$_SERVER['HTTPS'] = 'on';
		$list = [];

		$podcasts = new WP_Query([
			'post_type' => 'podcasts',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => [[
				'key' => 'hpm_pod_prod',
				'compare' => '=',
				'value' => 'internal'
			]]
		]);
		if ( $podcasts->have_posts() ) {
			global $post;
			while ( $podcasts->have_posts() ) {
				$temp = [
					'image' => [
						'full' => [],
						'medium' => [],
						'thumbnail' => []
					],
					'latest_episode' => [
						'audio' => '',
						'title' => '',
						'link' => ''
					]
				];
				$podcasts->the_post();
				$pod_id = get_the_ID();
				$podlink = get_post_meta( $pod_id, 'hpm_pod_link', true );
				$last_id = get_post_meta( $pod_id, 'hpm_pod_last_id', true );
				$image_full = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$image_medium = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
				$image_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id() );
				$temp['image']['full'] = [
					'url' => $image_full[0],
					'width' => $image_full[1],
					'height' => $image_full[2]
				];
				$temp['image']['medium'] = [
					'url' => $image_medium[0],
					'width' => $image_medium[1],
					'height' => $image_medium[2]
				];
				$temp['image']['thumbnail'] = [
					'url' => $image_thumbnail[0],
					'width' => $image_thumbnail[1],
					'height' => $image_thumbnail[2]
				];
				$temp['feed'] = ( !empty( $podlink['rss-override'] ) ? $podlink['rss-override'] : get_permalink( $pod_id ) );
				$temp['archive'] = $podlink['page'];
				$temp['slug'] = $post->post_name;
				unset( $podlink['page'] );
				unset( $podlink['rss-override'] );
				unset( $podlink['categories'] );
				unset( $podlink['limit'] );
				unset( $podlink['type'] );
				$temp['external_links'] = $podlink;
				$temp['name'] = get_the_title();
				$temp['description'] = get_the_content();
				$a_meta = get_post_meta( $last_id['id'], 'hpm_podcast_enclosure', true );
				$temp['latest_episode']['audio'] = str_replace( 'http://', $protocol, $a_meta['url'] );
				$temp['latest_episode']['title'] = get_the_title( $last_id['id'] );
				$temp['latest_episode']['link'] = get_the_permalink( $last_id['id'] );
				$temp['feed_json'] = WP_HOME . '/wp-json/hpm-podcast/v1/list/' . $post->post_name;
				$list[] = $temp;
			}
		} else {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No podcast feeds have been defined. Please create one and try again.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		}
		set_transient( 'hpm_podcasts_list', $list, 3600 );
		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'Podcast feed list', 'hpm-podcasts' ), 'data' => [ 'list' => $list, 'status' => 200 ] ] );
	}

	/**
	 * JSON version of requested podcast feed
	 *
	 * @param WP_REST_Request $request This function accepts a rest request to process data.
	 *
	 * @return mixed
	 */
	public function json_feed( WP_REST_Request $request ): mixed {
		if ( empty( $request['feed'] ) ) {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No podcast feed specified. Please choose a podcast feed.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		}

		$output = get_option( 'hpm_podcast-json-'.$request['feed'] );
		if ( !$output ) {
			return new WP_Error( 'rest_api_sad', esc_html__( 'No podcast feed specified. Please choose a podcast feed.', 'hpm-podcasts' ), [ 'status' => 500 ] );
		}

		$oj = json_decode( $output, true );
		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'JSON-formatted feed for ' . $oj['title'], 'hpm-podcasts' ), 'data' => [ 'feed' => $oj, 'status' => 200 ] ] );
	}
}
new HPM_Podcasts();