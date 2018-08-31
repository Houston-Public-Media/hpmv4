<?php
/**
 * @link 			https://github.com/jwcounts
 * @since  			20180710
 * @package  		HPM-Priority
 *
 * @wordpress-plugin
 * Plugin Name: 	HPM Priority
 * Plugin URI: 		https://github.com/jwcounts
 * Description: 	Setup for priority slots on the homepage
 * Version: 		20180710
 * Author: 			Jared Counts
 * Author URI: 		http://www.houstonpublicmedia.org/staff/jared-counts/
 * License: 		GPL-2.0+
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	hpmv2
 *
 * Works best with Wordpress 4.6.0+
 */

add_action('update_option_hpm_priority', function( $old_value, $value ) {
	wp_cache_delete( 'hpm_priority', 'options' );
}, 10, 2);

// create custom plugin settings menu
add_action('admin_menu', 'hpm_priority_create_menu');

function hpm_priority_create_menu() {
	add_submenu_page( 'edit.php', 'HPM Post Priority Settings', 'Priority Posts', 'edit_others_posts', 'hpm-priority-settings', 'hpm_priority_settings_page' );
	add_action( 'admin_init', 'hpm_priority_register_settings' );
}

/**
 * Registers the settings group for HPM Priority
 */
function hpm_priority_register_settings() {
	register_setting( 'hpm-priority-settings-group', 'hpm_priority' );
}

function hpm_priority_settings_page() {
	$priority = get_option( 'hpm_priority' );
	$recents = array();
	$recent = new WP_Query(
		array(
			'post_status' => 'publish',
			'posts_per_page' => 150,
			'post_type' => 'post',
			'order' => 'DESC',
			'orderby' => 'date',
			'category__not_in' =>  array( 0, 1, 7636 )
		)
	);
	if ( $recent->have_posts() ) :
		while( $recent->have_posts() ) : $recent->the_post();
			$recent_id = get_the_ID();
			$recents[ $recent_id ] = get_the_title();
		endwhile;
	endif;
	$recents[268795] = '2018 Elections: Everything You Need To Know For Early Voting';
	$recents[268894] = 'CHECK OUT: Your Voting Guide 2018 For Houston And Harris County';
	$recents[277306] = 'Texas Beware, 2018 Hurricane Season Expected To Be More Active Than Usual'; ?>
	<div class="wrap">
		<?php settings_errors(); ?>
		<h1><?php _e('Post Prioritization', 'hpmv2' ); ?></h1>
		<p><?php _e('This page displays the posts that are currently set as "Priority Posts" on the homepage and main landing pages.', 'hpmv2' ); ?></p>
		<form method="post" action="options.php" id="hpm-priority-slots">
			<?php settings_fields( 'hpm-priority-settings-group' ); ?>
			<?php do_settings_sections( 'hpm-priority-settings-group' ); ?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-1">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								<div class="handlediv" title="Click to toggle"><br></div>
								<h2 class="hndle"><span><?php _e('Homepage/News', 'hpmv2' ); ?></span></h2>
								<div class="inside">
									<table class="wp-list-table widefat fixed striped posts">
										<thead>
											<tr>
												<th scope="col" class="manage-column column-author">Position</th>
												<th scope="col" class="manage-column">Current Post</th>
												<th scope="col" class="manage-column column-tags">Change to ID?</th>
											</tr>
										</thead>
										<tbody>
									<?php
										foreach ( $priority['homepage'] as $kp => $vp ) :
											$position = $kp + 1; ?>
											<tr valign="top">
												<th scope="row">Position <?PHP echo $position; ?></th>
												<td>
													<label class="screen-reader-text"><?php _e( "Current Article in Homepage Position ".$position.":", 'hpmv2' ); ?></label>
													<select id="hpm_priority-homepage-<?php echo $kp; ?>" class="hpm-priority-select homepage-select">
														<option value=""></option>
														<?php
														foreach( $recents as $k => $v ) : ?>
															<option value="<?php echo $k; ?>"<?php selected( $vp, $k, TRUE ); ?>><?php echo
																$v; ?></option>
															<?php
														endforeach; ?>
													</select>
												</td>
												<td><label for="hpm_priority[homepage][<?php echo $kp; ?>]" class="screen-reader-text"><?php _e('Change To?', 'hpmv2' ); ?></label><input type="number" name="hpm_priority[homepage][<?php echo $kp; ?>]" id="homepage-<?php echo $kp; ?>" class="homepage-select-input" value="<?php echo $vp; ?>" style="max-width: 100%;" /></td>
											</tr>
									<?php
										endforeach; ?>
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
					var postId = $(this).val();
					var slotId = $(this).attr('id');
					var slot = slotId.split('-');
					$('#' + slot[1] + '-' + slot[2]).val(postId);
					if (postId !== '') {
						$("." + slot[1] + "-select").each(function () {
							var selectId = $(this).attr('id');
							var selectSlot = selectId.split('-');
							if (selectId !== slotId) {
								if ($(this).val() === postId) {
									$(this).val('');
									$('#' + selectSlot[1] + '-' + selectSlot[2]).val('');
								}
							}
						});
					}
				});
				$( "input[type=number]" ).keyup(function(){
					var inputId = $(this).attr('id');
					var inputType = inputId.split('-');
					var inputVal = $(this).val();
					$('#hpm_priority-' + inputId).val(inputVal);
					if ( inputVal !== '' ) {
						$("." + inputType[0] + "-select-input").each(function () {
							var selectId = $(this).attr('id');
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