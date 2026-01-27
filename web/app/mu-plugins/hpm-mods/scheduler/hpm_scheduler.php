<?php
/**
 * Allows for creating a podcast feed from any category, along with templating, caching, and uploading the media files to an external server
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class HPM_Scheduler {
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
		add_action( 'init', [ $this, 'create_type' ] );
	}

	/**
	 * Init
	 */
	public function init(): void {
		add_action( 'admin_init', [ $this, 'add_role_caps' ], 999 );
		add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
		add_action( 'hpm_scheduler_cleanup', [ $this, 'cleanup' ] );
		add_filter( 'manage_edit-scheduler_columns', [ $this, 'edit_columns' ] );
		add_action( 'manage_scheduler_posts_custom_column', [ $this, 'manage_columns' ], 10, 2 );
		add_action( 'admin_head', [ $this, 'hide_publish_button' ] );

		// Make sure that the proper cron job is scheduled
		if ( ! wp_next_scheduled( 'hpm_scheduler_cleanup' ) ) {
			wp_schedule_event( time(), 'daily', 'hpm_scheduler_cleanup' );
		}
	}

	public function hide_publish_button(): void {
		global $post;
		if ( $post !== null && $post->post_type == 'scheduler' ) {
			$meta = get_post_meta( $post->ID, 'hpm_scheduler_meta', true );
			if ( !empty( $meta ) && !empty( $meta['type'] ) ) {
				return;
			} ?>
			<script type="text/javascript">
				window.onload = function() { document.getElementById('publish').disabled = true; }
			</script>
			<?php
		}
	}

	public function create_type(): void {
		register_post_type( 'scheduler', [
			'labels'               => [
				'name'               => __( 'Scheduler' ),
				'singular_name'      => __( 'Scheduler' ),
				'menu_name'          => __( 'Scheduler' ),
				'add_new'            => __( 'Add New Scheduler' ),
				'add_new_item'       => __( 'Add New Scheduler' ),
				'edit_item'          => __( 'Edit Scheduler' ),
				'new_item'           => __( 'New Scheduler' ),
				'view_item'          => __( 'View Scheduler' ),
				'search_items'       => __( 'Search Schedulers' ),
				'not_found'          => __( 'Scheduler Not Found' ),
				'not_found_in_trash' => __( 'Scheduler not found in trash' ),
				'all_items'          => __( 'All Schedulers' ),
				'archives'           => __( 'Scheduler Archives' ),
			],
			'description'          => 'Scheduling system for content and redirections',
			'public'               => false,
			'show_ui'              => true,
			'show_in_admin_bar'    => true,
			'show_in_rest'         => false,
			'menu_position'        => 20,
			'menu_icon'            => 'dashicons-warning',
			'has_archive'          => false,
			'rewrite'              => false,
			'supports'             => [ 'title' ],
			'can_export'           => false,
			'capability_type'      => [ 'hpm_scheduler', 'hpm_schedulers' ],
			'map_meta_cap'         => true,
			'register_meta_box_cb' => [ $this, 'add_meta' ],
			'show_in_graphql' =>   false
		]);
	}

	public function add_role_caps(): void {
		// Add the roles you'd like to administer the custom post types
		$roles = [ 'administrator', 'editor' ];

		// Loop through each role and assign capabilities
		foreach ( $roles as $the_role ) {
			$role = get_role( $the_role );
			$role->add_cap( 'read' );
			$role->add_cap( 'read_hpm_scheduler' );
			$role->add_cap( 'read_private_hpm_schedulers' );
			$role->add_cap( 'edit_hpm_scheduler' );
			$role->add_cap( 'edit_hpm_schedulers' );
			$role->add_cap( 'edit_others_hpm_schedulers' );
			$role->add_cap( 'edit_published_hpm_schedulers' );
			$role->add_cap( 'publish_hpm_schedulers' );
			$role->add_cap( 'delete_others_hpm_schedulers' );
			$role->add_cap( 'delete_private_hpm_schedulers' );
			$role->add_cap( 'delete_published_hpm_schedulers' );
		}
	}

	public function add_meta(): void {
		add_meta_box(
			'hpm-scheduler-meta-class',
			esc_html__( 'Scheduled Update Information', 'hpmv4' ),
			[ $this, 'meta_box' ],
			'scheduler',
			'normal',
			'core'
		);
	}

	public function meta_box( $object, $box ): void {
		global $wpdb;
		wp_nonce_field( basename( __FILE__ ), 'hpm_scheduler_class_nonce' );
		$hpm_scheduler = get_post_meta( $object->ID, 'hpm_scheduler_meta', true );
		if ( empty( $hpm_scheduler ) ) {
			$hpm_scheduler = [
				'type' => '',
				'id' => 0,
				'redirect_data' => '',
				'title' => '',
				'content' => '',
				'featured_image' => 0,
				'banners' => [
					'mobile' => 0,
					'tablet' => 0,
					'desktop' => 0
				],
				'completed' => 0
			];
		}
		$editor_opts = [
			'editor_height' => 200,
			'media_buttons' => false,
			'quicktags' => true,
			'teeny' => true,
			'wpautop' => false,
			'tinymce' => false,
			'drag_drop_upload' => false
		];
		$podcasts = new WP_Query([
			'post_type' => 'podcasts',
			'post_status' => 'publish',
			'orderby' => 'name',
			'order' => 'ASC',
			'posts_per_page' => -1
		]);
		$shows = new WP_Query([
			'post_type' => 'shows',
			'post_status' => 'publish',
			'orderby' => 'name',
			'order' => 'ASC',
			'posts_per_page' => -1
		]);
		$redirects = $wpdb->get_results("SELECT * FROM `wp_redirection_items` WHERE (`status` = 'enabled') AND (`group_id` = 1) AND (`match_url` = 'regex') ORDER BY `last_access` DESC LIMIT 100 OFFSET 0;");
		if ( !empty( $hpm_scheduler['completed'] ) ) { ?>
		<h3><span style="font-weight: bolder; font-style: italic; color: red;"><?PHP _e( "This scheduled change has been completed", 'hpmv4' ); ?></span></h3>
		<?php 	} ?>
		<h3><?PHP _e( "What type of content are you updating?", 'hpmv4' ); ?> <span style="font-weight: bolder; font-style: italic; color: red;"><?PHP _e( "REQUIRED", 'hpmv4' ); ?></span></h3>
		<p><label for="hpm-scheduler-type"><?php _e( "Type:", 'hpmv4' ); ?></label>
			<select id="hpm-scheduler-type" name="hpm-scheduler-type">
				<option value="">Select Type</option>
				<option value="redirect" <?PHP selected( $hpm_scheduler['type'], 'redirect' ); ?>>Redirection</option>
				<option value="page" <?PHP selected( $hpm_scheduler['type'], 'page' ); ?>>Page</option>
				<option value="show" <?PHP selected( $hpm_scheduler['type'], 'show' ); ?>>Show</option>
				<option value="podcast" <?PHP selected( $hpm_scheduler['type'], 'podcast' ); ?>>Podcast</option>
			</select>
		</p>
		<p class="scheduler-podcast">
			<label for="hpm-scheduler-podcast-id"><?php _e( "Podcast to Update:", 'hpmv4' ); ?></label>
			<select name="hpm-scheduler-podcast-id" id="hpm-scheduler-podcast-id">
				<option value="0"<?PHP selected( 0, $hpm_scheduler['id']); ?>><?PHP _e( "Select One", 'hpmv4' ); ?></option>
<?php
				if ( $podcasts->have_posts() ) {
					while ( $podcasts->have_posts() ) {
						$podcasts->the_post(); ?>
						<option value="<?PHP echo get_the_ID(); ?>"<?PHP selected( $hpm_scheduler['id'], get_the_ID() );?>><?PHP the_title(); ?></option>
						<?php
					}
				} ?>
			</select>
		</p>
		<p class="scheduler-show">
			<label for="hpm-scheduler-show-id"><?php _e( "Show Page to Update:", 'hpmv4' ); ?></label>
			<select name="hpm-scheduler-show-id" id="hpm-scheduler-show-id">
				<option value="0"<?PHP selected( 0, $hpm_scheduler['id'] ); ?>><?PHP _e( "Select One", 'hpmv4' ); ?></option>
				<?php
				if ( $shows->have_posts() ) {
					while ( $shows->have_posts() ) {
						$shows->the_post(); ?>
						<option value="<?PHP echo get_the_ID(); ?>"<?PHP selected( $hpm_scheduler['id'], get_the_ID() );?>><?PHP the_title(); ?></option>
						<?php
					}
				} ?>
			</select>
		</p>
		<p class="scheduler-redirect">
			<label for="hpm-scheduler-redirect-id"><?php _e( "Redirection to Update:", 'hpmv4' ); ?></label>
			<select name="hpm-scheduler-redirect-id" id="hpm-scheduler-redirect-id">
				<option value="0"<?PHP selected( 0, $hpm_scheduler['id'] ); ?>><?PHP _e( "Select One", 'hpmv4' ); ?></option>
				<?php
				foreach ( $redirects as $r ) { ?>
					<option value="<?PHP echo $r->id; ?>"<?PHP selected( $hpm_scheduler['id'], $r->id );?>><?PHP echo $r->url; ?></option>
<?php
				} ?>
			</select>
		</p>
		<p class="scheduler-page"><?php
			wp_dropdown_pages([
				'selected' => $hpm_scheduler['id'],
				'name' => 'hpm-scheduler-page-id',
				'id' => 'hpm-scheduler-page-id',
				'show_option_none' => "Select One",
				'option_none_value' => "0"
			]); ?></p>
		<p class="scheduler-redirect"><label for="hpm-scheduler-redirect-data"><?php _e('URL to Redirect to: ', 'hpmv4' ); ?></label><input type="text" id="hpm-scheduler-redirect-data" name="hpm-scheduler-redirect-data" value="<?php echo $hpm_scheduler['redirect_data']; ?>" style="max-width: 100%; width: 800px;" /></p>
		<p class="scheduler-page scheduler-show scheduler-podcast"><label for="hpm-scheduler-title"><?php _e('New title: ', 'hpmv4' ); ?></label><input type="text" id="hpm-scheduler-title" name="hpm-scheduler-title" value="<?php echo $hpm_scheduler['title']; ?>" style="max-width: 100%; width: 800px;" /></p>
		<div class="scheduler-page scheduler-show scheduler-podcast">
			<label for="hpm-scheduler-content"><?php _e( "New Content: ", 'hpmv4' ); ?></label><br />
			<?php
			$editor_opts = [
					'editor_height' => 400,
					'media_buttons' => false,
					'teeny' => true
			];
			wp_editor( $hpm_scheduler['content'], 'hpm-scheduler-content', $editor_opts );
			?>
		</div>
		<div class="hpm-scheduler-banner-wrap scheduler-page scheduler-show scheduler-podcast">
			<h3><?PHP _e( "Banner Images", 'hpm-podcasts' ); ?></h3>
			<p>Use the buttons below to select your images to update</p>
<?php
			$hpm_mobile_url = $hpm_tablet_url = $hpm_desktop_url = $hpm_featured_url = '';
			if ( !empty( $hpm_scheduler['banners']['mobile'] ) ) {
				$hpm_mobile_temp = wp_get_attachment_image_src( $hpm_scheduler['banners']['mobile'], 'medium' );
				$hpm_mobile_url = ' style="background-image: url(' . $hpm_mobile_temp[0] . ')"';
			}
			if ( !empty( $hpm_scheduler['banners']['tablet'] ) ) {
				$hpm_tablet_temp = wp_get_attachment_image_src( $hpm_scheduler['banners']['tablet'], 'medium' );
				$hpm_tablet_url = ' style="background-image: url(' . $hpm_tablet_temp[0] . ')"';
			}
			if ( !empty( $hpm_scheduler['banners']['desktop'] ) ) {
				$hpm_desktop_temp = wp_get_attachment_image_src( $hpm_scheduler['banners']['desktop'], 'medium' );
				$hpm_desktop_url = ' style="background-image: url(' . $hpm_desktop_temp[0] . ')"';
			}
			if ( !empty( $hpm_scheduler['featured_image'] ) ) {
				$hpm_featured_temp = wp_get_attachment_image_src( $hpm_scheduler['featured_image'], 'medium' );
				$hpm_featured_url = ' style="background-image: url(' . $hpm_featured_temp[0] . ')"';
			} ?>
			<div class="hpm-scheduler-banner scheduler-page scheduler-show scheduler-podcast">
				<div class="hpm-scheduler-banner-image" id="hpm-scheduler-banner-featured"<?php echo $hpm_featured_url; ?>></div>
				<button class="hpm-scheduler-banner-select button button-primary" data-show="featured">Featured Image</button>
				<input value="<?php echo $hpm_scheduler['featured_image']; ?>" type="hidden" id="hpm-scheduler-banner-featured-id" name="hpm-scheduler-banner-featured-id" />
				<?php echo ( !empty( $hpm_scheduler['featured_image'] ) ? '<button class="hpm-scheduler-banner-remove button button-secondary" data-show="featured" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
			</div>
			<div class="hpm-scheduler-banner scheduler-page scheduler-show">
				<div class="hpm-scheduler-banner-image" id="hpm-scheduler-banner-mobile"<?php echo $hpm_mobile_url; ?>></div>
				<button class="hpm-scheduler-banner-select button button-primary" data-show="mobile">Mobile</button>
				<input value="<?php echo $hpm_scheduler['banners']['mobile']; ?>" type="hidden" id="hpm-scheduler-banner-mobile-id" name="hpm-scheduler-banner-mobile-id" />
				<?php echo ( !empty( $hpm_scheduler['banners']['mobile'] ) ? '<button class="hpm-scheduler-banner-remove button button-secondary" data-show="mobile" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
			</div>
			<div class="hpm-scheduler-banner scheduler-page scheduler-show">
				<div class="hpm-scheduler-banner-image" id="hpm-scheduler-banner-tablet"<?php echo $hpm_tablet_url; ?>></div>
				<button class="hpm-scheduler-banner-select button button-primary" data-show="tablet">Tablet</button>
				<input value="<?php echo $hpm_scheduler['banners']['tablet']; ?>" type="hidden" id="hpm-scheduler-banner-tablet-id" name="hpm-scheduler-banner-tablet-id" />
				<?php echo ( !empty( $hpm_scheduler['banners']['tablet'] ) ? '<button class="hpm-scheduler-banner-remove button button-secondary" data-show="tablet" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
			</div>
			<div class="hpm-scheduler-banner scheduler-page scheduler-show">
				<div class="hpm-scheduler-banner-image" id="hpm-scheduler-banner-desktop"<?php echo $hpm_desktop_url; ?>></div>
				<button class="hpm-scheduler-banner-select button button-primary" data-show="desktop">Desktop</button>
				<input value="<?php echo $hpm_scheduler['banners']['desktop']; ?>" type="hidden" id="hpm-scheduler-banner-desktop-id" name="hpm-scheduler-banner-desktop-id" />
				<?php echo ( !empty( $hpm_scheduler['banners']['desktop'] ) ? '<button class="hpm-scheduler-banner-remove button button-secondary" data-show="desktop" style="border-color: red; color: red;">Remove</button>' : '' ); ?>
			</div>
		</div>
		<script>
			function capitalizeFirstLetter(string) {
				return string[0].toUpperCase() + string.slice(1);
			}
			function showHideType( scheduler, $ ) {
				let typeVal = scheduler.val();
				$('.scheduler-show, .scheduler-page, .scheduler-podcast, .scheduler-redirect').hide();
				$('.scheduler-'+typeVal).show();
				document.getElementById('publish').disabled = typeVal === '';
			}
			jQuery(document).ready(function($){
				$('.hpm-scheduler-banner-select').click(function(e){
					e.preventDefault();
					let size = $(this).attr('data-show');
					let frame = wp.media({
						title: 'Choose Your ' + capitalizeFirstLetter(size) + ' Banner',
						library: {type: 'image'},
						multiple: false,
						button: {text: 'Set ' + capitalizeFirstLetter(size) + ' Banner'}
					});
					frame.on('select', function(){
						let sizes = frame.state().get('selection').first().attributes.sizes;
						let thumb = sizes.full.url;
						if ( typeof sizes.medium !== 'undefined' ) {
							thumb = sizes.medium.url;
						}
						let attachId = frame.state().get('selection').first().id;
						$('#hpm-scheduler-banner-'+size).css( 'background-image', 'url('+thumb+')' )
						$('#hpm-scheduler-banner-'+size+'-id').val(attachId);
					});
					frame.open();
				});
				$('.hpm-scheduler-banner-remove').click(function(e){
					e.preventDefault();
					let size = $(this).attr('data-show');
					$('#hpm-scheduler-banner-'+size).css( 'background-image', '' )
					$('#hpm-scheduler-banner-'+size+'-id').val('');
				});
				let schedulerType = $( "#hpm-scheduler-type" );
				showHideType( schedulerType, $ );
				schedulerType.change(function() { showHideType($(this), $) } );
			});
		</script>
		<style>
			.hpm-scheduler-banner-wrap {
				overflow: hidden;
			}
			.hpm-scheduler-banner {
				width: 20%;
				padding: 1em;
				float: left;
				text-align: center;
			}
			.hpm-scheduler-banner .hpm-scheduler-banner-image {
				height: 0;
				width: 100%;
				padding-bottom: calc(100% / 1.5);
				background-repeat: no-repeat;
				background-size: cover;
				background-position: top center;
				border: 1px dotted #bfbfbf;
				margin-bottom: 0.5em;
			}
			.scheduler-page, .scheduler-podcast, .scheduler-redirect, .scheduler-show {
				display: none;
			}
		</style>
		<?php
	}

	public function save_meta( $post_id, $post ) {
		if ( $post->post_type == 'scheduler' ) {
			/* Verify the nonce before proceeding. */
			if ( ! isset( $_POST['hpm_scheduler_class_nonce'] ) || ! wp_verify_nonce( $_POST['hpm_scheduler_class_nonce'], basename( __FILE__ ) ) ) {
				return $post_id;
			}

			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );

			/* Check if the current user has permission to edit the post. */
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
				return $post_id;
			}

			$hpm_scheduler = [
				'type' => ( !empty( $_POST['hpm-scheduler-type'] ) ? $_POST['hpm-scheduler-type'] : '' ),
				'id' => 0,
				'redirect_data' => ( !empty( $_POST['hpm-scheduler-redirect-data'] ) ? sanitize_url( $_POST['hpm-scheduler-redirect-data'] ) : '' ),
				'title' => ( !empty( $_POST['hpm-scheduler-title'] ) ? sanitize_text_field( $_POST['hpm-scheduler-title'] ) : '' ),
				'content' => ( !empty( $_POST['hpm-scheduler-content'] ) ? balanceTags( $_POST['hpm-scheduler-content'], true ) : '' ),
				'featured_image' => ( !empty( $_POST['hpm-scheduler-banner-featured-id'] ) ? $_POST['hpm-scheduler-banner-featured-id'] : 0 ),
				'banners' => [
					'mobile' => ( !empty( $_POST['hpm-scheduler-banner-mobile-id'] ) ? $_POST['hpm-scheduler-banner-mobile-id'] : 0 ),
					'tablet' => ( !empty( $_POST['hpm-scheduler-banner-tablet-id'] ) ? $_POST['hpm-scheduler-banner-tablet-id'] : 0 ),
					'desktop' => ( !empty( $_POST['hpm-scheduler-banner-desktop-id'] ) ? $_POST['hpm-scheduler-banner-desktop-id'] : 0 )
				],
				'completed' => 0
			];
			if ( !empty( $hpm_scheduler["type"] ) && !empty( $_POST['hpm-scheduler-' . $hpm_scheduler['type'] . '-id'] ) ) {
				$hpm_scheduler['id'] = $_POST['hpm-scheduler-' . $hpm_scheduler['type'] . '-id'];
			}
			update_post_meta( $post_id, 'hpm_scheduler_meta', $hpm_scheduler );
		}
		return $post_id;
	}

	public static function cleanup(): void {
		$t = time();
		$offset = get_option( 'gmt_offset' ) * 3600;
		$t = $t + $offset - ( 60 * 60 * 24 * 7 );
		$now = getdate( $t );
		$args = [
			'post_type' => 'scheduler',
			'posts_per_page' => -1,
			'date_query' => [[
				'before'    => [
					'year'  => $now['year'],
					'month' => $now['mon'],
					'day'   => $now['mday'],
				],
				'inclusive' => true
			]]
		];
		$scheduler = new WP_Query( $args );
		if ( $scheduler->have_posts() ) {
			while ( $scheduler->have_posts() ) {
				$scheduler->the_post();
				$meta = get_post_meta( get_the_ID(), 'hpm_scheduler_meta', true );
				if ( $meta['completed'] === '1' ) {
					wp_trash_post( get_the_ID() );
				}
			}
		}
	}

	public static function update(): void {
		global $wpdb;
		$t = time();
		$offset = get_option( 'gmt_offset' ) * 3600;
		$t = $t + $offset;
		$now = getdate( $t );
		$args = [
			'post_type' => 'scheduler',
			'posts_per_page' => -1,
			'date_query' => [[
				'year'  => $now['year'],
				'month' => $now['mon'],
				'day'   => $now['mday'],
				'hour'  => $now['hours'],
			]]
		];
		$scheduler = new WP_Query( $args );
		if ( $scheduler->have_posts() ) {
			while ( $scheduler->have_posts() ) {
				$scheduler->the_post();
				$meta = get_post_meta( get_the_ID(), 'hpm_scheduler_meta', true );
				$success = '1';
				if ( empty( $meta['type'] ) ) {
					continue;
				}
				if ( $meta['completed'] === '1' ) {
					continue;
				}
				if ( $meta['type'] === 'redirect' ) {
					if ( !empty( $meta['redirect_data'] ) && !empty( $meta['id'] ) ) {
						$result = $wpdb->update( 'wp_redirection_items', [ 'action_data' => $meta['redirect_data'] ], [ 'id' => $meta['id'] ] );
						if ( $result === false ) {
							$success = '0';
						}
					}
				} else {
					if ( !empty( $meta['title'] ) || !empty( $meta['content'] ) ) {
						$args = [ 'ID' => $meta['id'], ];
						if ( !empty( $meta['title'] ) ) {
							$args['post_title'] = $meta['title'];
						}
						if ( !empty( $meta['content'] ) ) {
							$args['post_content'] = $meta['content'];
						}
						$result = wp_update_post( $args, true );
						if ( is_wp_error( $result ) ) {
							$success = '0';
						}
					}
					if ( !empty( $meta['banners']['mobile'] ) || !empty( $meta['banners']['tablet'] ) || !empty( $meta['banners']['desktop'] ) ) {
						if ( $meta['type'] === 'page' ) {
							$options = get_post_meta( $meta['id'], 'hpm_page_options', true );
							$options['banner'] = $meta['banners'];
							$result = update_post_meta( $meta['id'], 'hpm_page_options', $options );
							if ( $result === false ) {
								$success = '0';
							}
						} elseif ( $meta['type'] == 'show' ) {
							$options = get_post_meta( $meta['id'], 'hpm_show_meta', true );
							if ( empty( $options ) ) {
								$options = [
									'times' => '',
									'hosts' => '',
									'ytp' => '',
									'podcast' => ''
								];
							}
							$options['banners'] = $meta['banners'];
							$result = update_post_meta( $meta['id'], 'hpm_show_meta', $options );
							if ( $result === false ) {
								$success = '0';
							}
						}
					}
					if ( !empty( $meta['featured_image'] ) ) {
						$result = update_post_meta( $meta['id'], '_thumbnail_id', $meta['featured_image'] );
						if ( $result === false ) {
							$success = '0';
						}
					}
				}
				$meta['completed'] = $success;
				update_post_meta( get_the_ID(), 'hpm_scheduler_meta', $meta );
			}
		}
	}

	public function edit_columns( $columns ): array {
		return [
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Name' ),
			'scheduler_type' => __( 'Type' ),
			'date' => __( 'Date' ),
			'completion' => __( 'Completion' )
		];
	}

	public function manage_columns( $column, $post_id ): void {
		global $post;
		$meta = get_post_meta( $post->ID, 'hpm_scheduler_meta', true );
		switch( $column ) {
			case 'scheduler_type' :
				if ( empty( $meta ) || empty( $meta['type'] ) ) {
					echo __( 'None' );
				} else {
					echo __( ucwords( str_replace( '-', ' ', $meta['type'] ) ) );
				}
				break;
			case 'completion' :
				if ( empty( $meta ) || empty( $meta['completed'] ) ) {
					echo __( 'Pending' );
				} else {
					echo __( 'Completed' );
				}
			default :
				break;
		}
	}
}
new HPM_Scheduler();