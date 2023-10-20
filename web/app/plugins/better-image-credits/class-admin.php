<?php

class BetterImageCreditsAdmin {

	function __construct( $plugin ) {
		$this->plugin = $plugin;
		add_filter( 'attachment_fields_to_edit', [ $this, 'add_fields' ], 10, 2 );
		add_filter( 'attachment_fields_to_save', [ $this, 'save_fields' ], 10 , 2 );

		add_filter( 'manage_media_columns', [ $this, 'manage_media_columns' ] );
		add_action( 'manage_media_custom_column', [ $this, 'manage_media_custom_column' ], 10, 2 );

		global $pagenow;
		if ( 'upload.php' == $pagenow ) {
			add_filter( 'posts_search', [ $this, 'media_search' ] );
		}

		add_action( 'admin_footer-upload.php', [ $this, 'add_bulk_actions' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_action_bulk_credits', [ $this, 'bulk_credits' ] );
		add_action( 'admin_action_-1', [ $this, 'bulk_credits' ] ); // Bottom dropdown (assumes top dropdown = default value)
		add_action( 'wp_ajax_license_search', [ $this, 'license_search_callback' ] );
		add_action( 'wp_ajax_license_url_search', [ $this, 'license_url_search_callback' ] );
	}

	function license_search_callback() {
		global $wpdb;
		$term = $_REQUEST['term'];

		$query = $wpdb->prepare( "
				SELECT DISTINCT meta_value
				FROM {$wpdb->postmeta}
				WHERE meta_key = '_wp_attachment_license'
				AND meta_value LIKE %s
				ORDER BY meta_value", "{$term}%" );

		$licenses = $wpdb->get_col( $query );
		echo json_encode( array_values( array_filter( $licenses ) ) );
		wp_die();
	}

	function license_url_search_callback() {
		global $wpdb;
		$term = ( isset( $_REQUEST['term'] ) ) ? $_REQUEST['term'] : false;
		$lic = ( isset( $_REQUEST['lic'] ) ) ? $_REQUEST['lic'] : false;

		if ( $lic ) {
			$query = $wpdb->prepare( "
					SELECT DISTINCT meta_value
					FROM {$wpdb->postmeta}
					WHERE meta_key = '_wp_attachment_license_url'
					AND post_id IN (SELECT post_id
						FROM {$wpdb->postmeta}
						WHERE meta_key = '_wp_attachment_license'
						AND meta_value = %s)
					ORDER BY meta_value", $lic );
		} else {
			$query = $wpdb->prepare( "
					SELECT DISTINCT meta_value
					FROM {$wpdb->postmeta}
					WHERE meta_key = '_wp_attachment_license_url'
					AND meta_value LIKE %s
					ORDER BY meta_value", "{$term}%" );
		}

		$urls = $wpdb->get_col( $query );
		echo json_encode( array_values( array_filter( $urls ) ) );
		wp_die();
	}

	function add_fields( $form_fields, $post ) {
		$mime = get_post_mime_type( $post->ID );

		if ( preg_match( '|image/.+|', $mime ) ) {
			$form_fields['credits_source'] = $this->get_field( $post,
					'credits_source', '_wp_attachment_source_name',
					__( 'Credits', 'better-image-credits' ),
					__( 'Source name', 'better-image-credits' ) );

			$form_fields['credits_link'] = $this->get_field( $post,
					'credits_link', '_wp_attachment_source_url',
					__( 'Link', 'better-image-credits' ),
					__( 'Source URL', 'better-image-credits' ),
					'widefat', 'url' );

			$form_fields['license'] = $this->get_field( $post,
					'license', '_wp_attachment_license',
					__( 'License', 'better-image-credits' ),
					__( 'License type', 'better-image-credits' ),
					'widefat license-auto' );

			$form_fields['license_link'] = $this->get_field( $post,
					'license_link', '_wp_attachment_license_url',
					__( 'License link', 'better-image-credits' ),
					__( 'License URL', 'better-image-credits' ),
					'widefat license-url-auto', 'url' );
		}

		return $form_fields;
	}

	function get_field( $post, $fid, $value, $label, $helps, $classes = 'widefat', $type = 'text' ) {
		$value = get_post_meta( $post->ID, $value, true );
		return [
			'label' => $label,
			'input' => 'html',
			'html'  => "<input type='$type' class='$classes' placeholder='$helps' name='attachments[{$post->ID}][$fid]' value='$value'>"
		];
	}

	function save_fields( $post, $attachment ) {
		if ( isset( $attachment['credits_source'] ) ) {
			update_post_meta( $post['ID'], '_wp_attachment_source_name', esc_attr( $attachment['credits_source'] ) );
		}

		if ( isset( $attachment['credits_link'] ) ) {
			update_post_meta( $post['ID'], '_wp_attachment_source_url', esc_url( $attachment['credits_link'] ) );
		}

		if ( isset( $attachment['license'] ) ) {
			update_post_meta( $post['ID'], '_wp_attachment_license', esc_attr( $attachment['license'] ) );
		}

		if ( isset( $attachment['license_link'] ) ) {
			update_post_meta( $post['ID'], '_wp_attachment_license_url', esc_url( $attachment['license_link'] ) );
		}

		return $post;
	}

	function manage_media_columns( $defaults ) {
		$defaults['credits'] = __( 'Credits', 'better-image-credits' );
		$defaults['license'] = __( 'License', 'better-image-credits' );
		return $defaults;
	}

	function manage_media_custom_column( $column, $post_id ) {
		if ( $column === 'credits' ) {
			$credit_source = esc_attr( get_post_meta( $post_id, '_wp_attachment_source_name', true ) );
			$credit_link = esc_url( get_post_meta( $post_id, '_wp_attachment_source_url', true ) );

			if ( !empty( $credit_source ) ) {
				if ( empty($credit_link ) ) {
					echo $credit_source;
				} else {
					echo '<a href="' . $credit_link . '">' . $credit_source . '</a>';
				}
			}
		}

		if ( $column === 'license' ) {
			$license = esc_attr( get_post_meta( $post_id, '_wp_attachment_license', true ) );
			$license_link = esc_url( get_post_meta( $post_id, '_wp_attachment_license_url', true ) );

			if ( !empty( $license ) ) {
				if ( empty( $license_link ) ) {
					echo $license;
				} else {
					echo '<a href="' . $license_link . '">' . $license . '</a>';
				}
			}
		}
	}

	function enqueue_scripts( $hook ) {
		wp_enqueue_script( 'credits-admin', plugins_url( 'admin.js', __FILE__ ), [ 'jquery-ui-autocomplete' ], '1.0', true );

		if ( 'upload.php' == $hook ) {
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
		}
	}

	function add_bulk_actions() { ?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('select[name^="action"] option:last-child').before('<option value="bulk_credits"><?php echo esc_attr(__( 'Image Credits', 'better-image-credits')); ?></option>');
				$('#doaction,#doaction2').click(function() {
					if ($('select[name="action"]').val() == 'bulk_credits' ||
							$('select[name="action2"]').val() == 'bulk_credits') {
						$('#dialog-credits').dialog({
							resizable: false,
						    modal: true,
						    buttons: {
						      	'<?php _e('OK', 'better-image-credits'); ?>': function() {
						        	$(this).dialog('close');
						        	$('#dialog-credits input').appendTo('#posts-filter');
						      		$('#posts-filter').submit();
						        },
						        '<?php _e('Cancel'); ?>': function() {
						        	$(this).dialog('close');
						        }
						    }
						 });
						 return false;
					}
				});
			});
		</script>
		<div id="dialog-credits" title="<?php _e('Image Credits', 'better-image-credits'); ?>" style="display:none">
			<p><?php _e('Leave the fields blank to remove credits information.', 'better-image-credit'); ?></p>
  			<p>
  				<label for="credits_source"><?php _e('Credits', 'better-image-credits'); ?>:</label><br>
  				<input type="text" class="text widefat" placeholder="<?php _e('Source name', 'better-image-credits'); ?>" name="credits_source" value="">
  			</p>
  			<p>
  				<label for="credits_link"><?php _e('Link', 'better-image-credits'); ?>:</label><br>
  				<input type="text" class="text widefat" placeholder="<?php _e('Source URL', 'better-image-credits'); ?>" name="credits_link" value="">
  			</p>
  			<p>
  				<label for="license"><?php _e('License', 'better-image-credits'); ?>:</label><br>
  				<input type="text" class="text widefat" placeholder="<?php _e('License type', 'better-image-credits'); ?>" name="license" value="">
  			</p>
  			<p>
  				<label for="license_link"><?php _e('License link', 'better-image-credits'); ?>:</label><br>
  				<input type="text" class="text widefat" placeholder="<?php _e('License URL', 'better-image-credits'); ?>" name="license_link" value="">
  			</p>
  		</div>
		<?php
	}

	function bulk_credits() {
		if ( empty( $_REQUEST['action'] ) || ( 'bulk_credits' != $_REQUEST['action'] && 'bulk_credits' != $_REQUEST['action2'] ) ) {
			return;
		}
		if ( empty( $_REQUEST['media'] ) || !is_array( $_REQUEST['media'] ) ) {
			return;
		}
		check_admin_referer( 'bulk-media' );
		$ids = array_map( 'intval', $_REQUEST['media'] );

		foreach ( $ids as $id ) {
			$mime = get_post_mime_type( $id );

			if ( preg_match( '|image/.+|', $mime ) ) {
				update_post_meta( $id, '_wp_attachment_source_name', esc_attr( $_REQUEST['credits_source'] ) );
				update_post_meta( $id, '_wp_attachment_source_url', esc_url( $_REQUEST['credits_link'] ) );
				update_post_meta( $id, '_wp_attachment_license', esc_attr( $_REQUEST['license'] ) );
				update_post_meta( $id, '_wp_attachment_license_url', esc_url( $_REQUEST['license_link'] ) );
			}
		}

		wp_redirect( admin_url( 'upload.php' ) );
	}

	function media_search( $search ) {
		global $wpdb;

		// Original search string:
		// AND (((wp_posts.post_title LIKE '%search-string%') OR (wp_posts.post_content LIKE '%search-string%')))
		$s = get_query_var( 's' );
		$extra = "{$wpdb->posts}.ID IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key IN ('_wp_attachment_source_name', '_wp_attachment_license') AND meta_value LIKE '%{$s}%')";
		return str_replace( 'AND ((', 'AND (((' . $extra . ') OR ', $search );;
	}

}
