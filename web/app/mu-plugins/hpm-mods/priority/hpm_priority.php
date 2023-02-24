<?php
add_action('update_option_hpm_priority', function( $old_value, $value ) {
	wp_cache_delete( 'hpm_priority', 'options' );
}, 10, 2);

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
		<p style="background-color: yellow; font-style: italic; font-size: 1rem;"><?php _e('<strong>NOTE:</strong> If position #2 is left blank, that slot will show the latest inDepth article. Otherwise, the slot will show the selected article.', 'hpmv4' ); ?></p>
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
										foreach ( $priority['homepage'] as $kp => $vp ) {
											$position = $kp + 1;
											if ( $kp == 1 ) { ?>
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
				$("#hpm-indepth-clear").click(function (event) {
					event.preventDefault();
					$('#indepth-1').val('');
					$('#hpm_priority-indepth-1').val('');

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