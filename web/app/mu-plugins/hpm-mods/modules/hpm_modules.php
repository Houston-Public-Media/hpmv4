<?php
add_action( 'pre_update_option_hpm_modules', function( $old_value, $value ) {
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
// create custom plugin settings menu
add_action('admin_menu', 'hpm_modules_create_menu');
function hpm_modules_create_menu(): void {
	add_submenu_page( 'edit.php', 'HPM Post Modules Settings', 'Modules Posts', 'edit_others_posts', 'hpm-modules-settings', 'hpm_modules_settings_page' );
	add_action( 'admin_init', 'hpm_modules_register_settings' );
}
/**
 * Registers the settings group for HPM Home Page Modules
 */
function hpm_modules_register_settings(): void {
	register_setting( 'hpm-modules-settings-group', 'hpm_modules' );
}
add_action( 'update_option_hpm_modules', function( $old_value, $value ) {
	wp_cache_delete( 'hpm_modules', 'options' );
}, 10, 2 );
function hpm_modules_settings_page(): void {
	$modules = get_option( 'hpm_modules', [
		'homepage' => [ '' ],
		'number' => 1
	] ); ?>
	<div class="wrap">
		<?php settings_errors(); ?>
		<h1><?php _e('Post Modules', 'hpmv4' ); ?></h1>
		<p><?php _e('This page displays the category modules that are currently set as "current Posts" on the homepage and main landing pages.', 'hpmv4' ); ?></p>
		<form method="post" action="options.php" id="hpm-modules-slots">
			<?php settings_fields( 'hpm-modules-settings-group' ); ?>
			<?php do_settings_sections( 'hpm-modules-settings-group' ); ?>
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
											<th scope="col" class="manage-column">Current Category</th>
										</tr>
										</thead>
										<tbody>
										<?php
										foreach ( $modules['homepage'] as $kp => $vp ) {
											$position = $kp + 1;?>
												<tr valign="top">
												<th scope="row">Module <?PHP echo $position; ?></th>
											<td>
												<label class="screen-reader-text"><?php _e( "Current Module in Homepage Position ".$position.":", 'hpmv4' ); ?></label>
												<?php
													wp_dropdown_categories([
														'show_option_all'	=> __("Select One"),
														'taxonomy'			=> 'category',
														'name'				=> 'hpm_modules[homepage][' . $kp . ']',
														'orderby'			=> 'name',
														'selected'			=> $vp,
														'hierarchical'		=> true,
														'depth'				=> 3,
														'show_count'		=> false,
														'hide_empty'		=> true
													]);
												?>
											</td>
											</tr>
											<?php
										} ?>
										</tbody>
									</table>
									<p><label for="hpm_modules[number]"><?php _e('Number of slots: ', 'hpmv4' ); ?></label><input type="text"  required="required" name="hpm_modules[number]" id="homepage-number" class="homepage-select-input" value="<?php echo ( !empty( $modules['number'] ) ? $modules['number'] : count( $modules['homepage'] ) ); ?>" style="width: 150px;" /></p>
									<div style="visibility: hidden; color: red;" id="sloterrMsg">Please enter even numbers only.</div>
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
				$( ".hpm-modules-select" ).change(function () {
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
				$( "#homepage-number" ).keyup(function(){
					let inputVal = $(this).val();
					var numeric = inputVal.replace(/[^0-9]+/,"");
					// Check if input is numeric and even, if not empty field
					// if (numeric.length != inputVal.length || numeric%2 != 0) {
						// $(this).val('');
						// $('#sloterrMsg').css('visibility','visible');
					// }
				});
			});
		</script>
	</div>
<?php
}
?>