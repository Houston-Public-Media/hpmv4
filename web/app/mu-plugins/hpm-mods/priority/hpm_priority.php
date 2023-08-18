<?php
add_action( 'pre_update_option_hpm_priority', function( $old_value, $value ) {
	$number = $old_value['number'];
	if ( $number !== count( $old_value['homepage'] ) ) {
		$new = [];
		for ( $i = 0; $i < $number; $i++ ) {
			if ( empty( $old_value['homepage'][ $i ] ) ) {
				$new[ $i ] = '';
			} else {
				$new[ $i ] = $old_value['homepage'][ $i ];
			}
		}
		$old_value['homepage'] = $new;
	}
	return $old_value;
}, 10, 2 );

add_action( 'update_option_hpm_priority', function( $old_value, $value ) {
	wp_cache_delete( 'hpm_priority', 'options' );
}, 10, 2 );

add_action( 'rest_api_init', function() {
	register_rest_route( 'hpm-priority/v1', '/list', [
		'methods'  => 'GET',
		'callback' => 'hpm_priority_json_list',
		'permission_callback' => function() {
			return true;
		}
	] );
} );

function hpm_priority_json_list(): WP_HTTP_Response|WP_REST_Response|WP_Error {
	$hpm_priority = get_option( 'hpm_priority' );
	if ( empty( $hpm_priority['inDepthnumber'] ) ) {
		$hpm_priority['inDepthnumber'] = 2;
	}
	$output = [];
	$indepth_slot = (int)$hpm_priority['inDepthnumber'] - 1;
	if ( !empty( $hpm_priority['homepage'] ) ) {
		if ( empty( $hpm_priority['homepage'][ $indepth_slot ] ) ) {
			$indepth = new WP_Query([
				'posts_per_page' => 2,
				'cat' => 29328,
				'ignore_sticky_posts' => 1,
				'post_status' => 'publish'
			]);
			if ( $indepth->have_posts() ) {
				if ( $hpm_priority['homepage'][0] == $indepth->posts[0]->ID ) {
					$hpm_priority['homepage'][ $indepth_slot ] = $indepth->posts[1]->ID;
				} else {
					$hpm_priority['homepage'][ $indepth_slot ] = $indepth->posts[0]->ID;
				}
			}
		}
		$sticknum = count( $hpm_priority['homepage'] );
		$sticky_args = [
			'posts_per_page' => $sticknum,
			'post__in'  => $hpm_priority['homepage'],
			'orderby' => 'post__in',
			'ignore_sticky_posts' => 1
		];
		$sticky_query = new WP_Query( $sticky_args );
		if ( $sticky_query->have_posts() ) {
			foreach ( $sticky_query->posts as $stp ) {
				$arr = [
					'ID' => $stp->ID,
					'title' => $stp->post_title,
					'excerpt' => $stp->post_excerpt,
					'picture' => get_the_post_thumbnail_url( $stp->ID, 'medium' )
				];
				$output[] = $arr;
			}
		}
	}
	if ( $output ) {
		return rest_ensure_response( [ 'code' => 'rest_api_success', 'message' => esc_html__( 'HPM Priority Homepage Story List', 'hpm-priority' ), 'data' => [ 'articles' => $output, 'status' => 200 ] ] );
	} else {
		return new WP_Error( 'rest_api_sad', esc_html__( 'There has been an error, please try again later.', 'hpm-priority' ), [ 'status' => 500 ] );
	}
}

// create custom plugin settings menu
add_action('admin_menu', 'hpm_priority_create_menu');

function hpm_priority_create_menu(): void {
	add_submenu_page( 'edit.php', 'HPM Post Priority Settings', 'Priority Posts', 'edit_others_posts', 'hpm-priority-settings', 'hpm_priority_settings_page' );
	add_action( 'admin_init', 'hpm_priority_register_settings' );
}

/**
 * Registers the settings group for HPM Priority
 */
function hpm_priority_register_settings(): void {
	register_setting( 'hpm-priority-settings-group', 'hpm_priority' );
}

function hpm_priority_settings_page(): void {
	$priority = get_option( 'hpm_priority' );
	if ( empty( $priority['inDepthnumber'] ) ) {
		$priority['inDepthnumber'] = 2;
	}
	$recents = $indepths = [];
	$recent = new WP_Query([
		'post_status' => 'publish',
		'posts_per_page' => 150,
		'post_type' => 'post',
		'order' => 'DESC',
		'orderby' => 'date',
		'category__not_in' =>  [ 0, 1, 7636 ]
	]);
	if ( $recent->have_posts() ) {
		while( $recent->have_posts() ) {
			$recent->the_post();
			$recent_id = get_the_ID();
			$recents[ $recent_id ] = get_the_title();
		}
	} ?>
	<div class="wrap">
		<?php settings_errors(); ?>
		<h1><?php _e('Post Prioritization', 'hpmv4' ); ?></h1>
		<p><?php _e('This page displays the posts that are currently set as "Priority Posts" on the homepage and main landing pages.', 'hpmv4' ); ?></p>
		<p style="background-color: yellow; font-style: italic; font-size: 1rem;"><?php _e('<strong>NOTE:</strong> If position #' . $priority['inDepthnumber'] . ' is left blank, that slot will show the latest inDepth article. Otherwise, the slot will show the selected article.', 'hpmv4' ); ?></p>
		<form method="post" action="options.php" id="hpm-priority-slots">
			<?php settings_fields( 'hpm-priority-settings-group' ); ?>
			<?php do_settings_sections( 'hpm-priority-settings-group' ); ?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-1">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<div class="postbox-header"><h2 class="hndle ui-sortable-handle"><?php _e('Homepage', 'hpm-podcasts' ); ?></h2></div>
								<div class="inside">
									<table class="wp-list-table widefat fixed striped posts">
										<thead>
											<tr>
												<th scope="col" class="manage-column column-author">Position</th>
												<th scope="col" class="manage-column">Current Post</th>
												<th scope="col" class="manage-column column-tags">Change to ID?</th>
												<th scope="col" class="manage-column column-author">Clear?</th>
											</tr>
										</thead>
										<tbody>
									<?php
										$inDepthSlotNumber = (int)$priority['inDepthnumber'] - 1;
										foreach ( $priority['homepage'] as $kp => $vp ) {
											$position = $kp + 1;
											if ( $kp == $inDepthSlotNumber ) { ?>
											<tr valign="top" style="border: 0.25rem solid #00566c;">
												<th scope="row">Position <?PHP echo $position; ?><br /><strong>inDepth Position</strong></th>
											<?php } else { ?>
											<tr valign="top">
												<th scope="row">Position <?PHP echo $position; ?></th>
											<?php } ?>
												<td>
													<label class="screen-reader-text"><?php _e( "Current Article in Homepage Position ".$position.":", 'hpmv4' ); ?></label>
													<select id="hpm_priority-homepage-<?php echo $kp; ?>" class="hpm-priority-select homepage-select">
														<option value=""></option>
<?php
														foreach( $recents as $k => $v ) { ?>
															<option value="<?php echo $k; ?>"<?php selected( $vp, $k, TRUE ); ?>><?php echo	$v; ?></option>
<?php
														} ?>
													</select>
												</td>
												<td><label for="hpm_priority[homepage][<?php echo $kp; ?>]" class="screen-reader-text"><?php _e('Change To?', 'hpmv4' ); ?></label><input type="number" name="hpm_priority[homepage][<?php echo $kp; ?>]" id="homepage-<?php echo $kp; ?>" class="homepage-select-input" value="<?php echo $vp; ?>" style="max-width: 100%;" /></td>
												<td><button class="hpm-clear button button-primary" data-position="<?php echo $kp; ?>">Reset</button></td>
											</tr>
<?php
										} ?>
										</tbody>
									</table>
									<p><label for="hpm_priority[number]"><?php _e('Number of slots: ', 'hpmv4' ); ?></label><input type="number" name="hpm_priority[number]" id="homepage-number" class="homepage-select-input" value="<?php echo ( !empty( $priority['number'] ) ? $priority['number'] : count( $priority['homepage'] ) ); ?>" style="width: 150px;" /></p>
                                    <p><label for="hpm_priority[inDepthnumber]"><?php _e('inDepth Slot Number: ', 'hpmv4' ); ?></label><input type="number" name="hpm_priority[inDepthnumber]" id="inDepthnumber" class="homepage-select-input" value="<?php echo ( !empty( $priority['inDepthnumber'] ) ? $priority['inDepthnumber'] : '' ); ?>" style="width: 150px;" /></p>
								</div>
							</div>
						</div>
						<?php submit_button(); ?>
						<br class="clear" />
					</div>
				</div>
			</div>
		</form>
		<script>
			jQuery(document).ready(function($){
				$( ".hpm-priority-select" ).change(function () {
					let postId = $(this).val();
					let slotId = $(this).attr('id');
					let slot = slotId.split('-');
					$('#' + slot[1] + '-' + slot[2]).val(postId);
					if (postId !== '') {
						$("." + slot[1] + "-select").each(function () {
							let selectId = $(this).attr('id');
							let selectSlot = selectId.split('-');
							if (selectId !== slotId) {
								if ($(this).val() === postId) {
									$(this).val('');
									$('#' + selectSlot[1] + '-' + selectSlot[2]).val('');
								}
							}
						});
					}
				});
				$("button.hpm-clear").click(function (event) {
					event.preventDefault();
					let pos = $(this).attr('data-position');
					$('#hpm_priority-homepage-' + pos).val('');
					$('#homepage-' + pos).val('');
				});
				$( "input[type=number]" ).keyup(function(){
					let inputId = $(this).attr('id');
					let inputType = inputId.split('-');
					let inputVal = $(this).val();
					$('#hpm_priority-' + inputId).val(inputVal);
					if ( inputVal !== '' ) {
						$("." + inputType[0] + "-select-input").each(function () {
							let selectId = $(this).attr('id');
							if (selectId !== inputId) {
								if ($(this).val() === inputVal) {
									$(this).val('');
									$('#hpm_priority-' + selectId).val('');
								}
							}
						});
					}
				});
			});
		</script>
	</div>
	<?php
}