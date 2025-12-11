<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'pre_update_option_hpm_breakingnews', function( $old_value, $value ) {
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
add_action('admin_menu', 'hpm_breakingnews_create_menu');
function hpm_breakingnews_create_menu(): void {
	add_submenu_page( 'edit.php', 'HPM Breaking News Settings', 'Breaking News', 'edit_others_posts', 'hpm-breakingnews-settings', 'hpm_breakingnews_settings_page' );
	add_action( 'admin_init', 'hpm_breakingnews_register_settings' );
}
/**
 * Registers the settings group for HPM Home Page Modules
 */
function hpm_breakingnews_register_settings(): void {
	register_setting( 'hpm-breakingnews-settings-group', 'hpm_breakingnews' );
}
add_action( 'update_option_hpm_breakingnews', function( $old_value, $value ) {
	wp_cache_delete( 'hpm_breakingnews', 'options' );
}, 10, 2 );
function hpm_breakingnews_settings_page(): void {
	$brekingnews = get_option( 'hpm_breakingnews', [
		'homepage' => [ '' ],
		'expirationdate' => [ '' ],
		'type' => '',
		'number' => 1
	]);
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
	}
	?>
	<div class="wrap">
		<?php settings_errors(); ?>
		<h1><?php _e('Breaking News Alert', 'hpmv4' ); ?></h1>
		<p><?php _e('This page displays the news articles that are currently set as "Breaking and developing news" on all pages.', 'hpmv4' ); ?></p>
		<form method="post" action="options.php" id="hpm-breakingnews-slots">
			<?php settings_fields( 'hpm-breakingnews-settings-group' ); ?>
			<?php do_settings_sections( 'hpm-breakingnews-settings-group' ); ?>
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
											<th scope="col" class="manage-column column-author">News Type</th>
											<th scope="col" class="manage-column">Latest Articles</th>
											<th scope="col" class="manage-column">Expiration Time</th>

										</tr>
										</thead>
										<tbody>
										<?php

										foreach ( $brekingnews['homepage'] as $kp => $vp ) {
											//echo $brekingnews['expirationdate'][$kp];
											$position = $kp + 1;
											if ( $kp == 0 ) { ?>
												<tr style="border: 2px solid red;">
												<th scope="row">Breaking News or a Developing Story?</th>
											<?php } else { ?>
												<tr>
												<th scope="row">Developing Story</th>
											<?php } ?>
											<td>
												<label for="hpm_breakingnews-homepage-<?php echo $kp; ?>" class="screen-reader-text"><?php _e( "Current Article in Homepage Position ".$position.":", 'hpmv4' ); ?></label>
												<select name="hpm_breakingnews-homepage-<?php echo $kp; ?>" id="hpm_breakingnews-homepage-<?php echo $kp; ?>" class="hpm_breakingnews-select homepage-select">
													<option value=""></option>
													<?php
													foreach( $recents as $k => $v ) { ?>
														<option value="<?php echo $k; ?>"<?php selected( $vp, $k, TRUE ); ?>><?php echo	$v; ?></option>
														<?php
													} ?>
												</select>
												<input type="hidden" name="hpm_breakingnews[homepage][<?php echo $kp; ?>]" id="homepage-<?php echo $kp; ?>" class="homepage-select-input" value="<?php echo $vp; ?>" style="max-width: 100%;" />
											</td>
											<td>
												<label for="hpm_breakingnews-expirationdate-<?php echo $kp; ?>" class="screen-reader-text"><?php _e( "Breaking news expiration date", 'hpmv4' ); ?></label>
												<select name="hpm_breakingnews-expirationdate-<?php echo $kp; ?>" id="hpm_breakingnews-expirationdate-<?php echo $kp; ?>" class="hpm_breakingnews-expirationdate-select expirationdate-select">
													<option value=""></option>
													<option value="1" <?php selected( $brekingnews['expirationdate'][$kp] , 1 ); ?>>1 Hour</option>
													<option value="2" <?php selected( $brekingnews['expirationdate'][$kp] , 2 ); ?>>2 Hours</option>
													<option value="3" <?php selected( $brekingnews['expirationdate'][$kp] , 3 ); ?>>3 Hours</option>
													<option value="4" <?php selected( $brekingnews['expirationdate'][$kp] , 4 ); ?>>4 Hours</option>
													<option value="5" <?php selected( $brekingnews['expirationdate'][$kp] , 5 ); ?>>5 Hours</option>
													<option value="6" <?php selected( $brekingnews['expirationdate'][$kp] , 6 ); ?>>6 Hours</option>
													<option value="7" <?php selected( $brekingnews['expirationdate'][$kp] , 7 ); ?>>7 Hours</option>
													<option value="8" <?php selected( $brekingnews['expirationdate'][$kp] , 8 ); ?>>8 Hours</option>
													<option value="9" <?php selected( $brekingnews['expirationdate'][$kp] , 9 ); ?>>9 Hours</option>
													<option value="10" <?php selected( $brekingnews['expirationdate'][$kp] , 10 ); ?>>10 Hours</option>
													<option value="11" <?php selected( $brekingnews['expirationdate'][$kp] , 11 ); ?>>11 Hours</option>
													<option value="12" <?php selected( $brekingnews['expirationdate'][$kp] , 12 ); ?>>12 Hours</option>
													<option value="13" <?php selected( $brekingnews['expirationdate'][$kp] , 13 ); ?>>13 Hours</option>
													<option value="14" <?php selected( $brekingnews['expirationdate'][$kp] , 14 ); ?>>14 Hours</option>
													<option value="15" <?php selected( $brekingnews['expirationdate'][$kp] , 15 ); ?>>15 Hours</option>
													<option value="16" <?php selected( $brekingnews['expirationdate'][$kp] , 16 ); ?>>16 Hours</option>
													<option value="17" <?php selected( $brekingnews['expirationdate'][$kp] , 17 ); ?>>17 Hours</option>
													<option value="18" <?php selected( $brekingnews['expirationdate'][$kp] , 18 ); ?>>18 Hours</option>
													<option value="19" <?php selected( $brekingnews['expirationdate'][$kp] , 19 ); ?>>19 Hours</option>
													<option value="20" <?php selected( $brekingnews['expirationdate'][$kp] , 20 ); ?>>20 Hours</option>
													<option value="21" <?php selected( $brekingnews['expirationdate'][$kp] , 21 ); ?>>21 Hours</option>
													<option value="22" <?php selected( $brekingnews['expirationdate'][$kp] , 22 ); ?>>22 Hours</option>
													<option value="23" <?php selected( $brekingnews['expirationdate'][$kp] , 23 ); ?>>23 Hours</option>
													<option value="24" <?php selected( $brekingnews['expirationdate'][$kp] , 24 ); ?>>24 Hours</option>

												</select>
												<input type="hidden" name="hpm_breakingnews[expirationdate][<?php echo $kp; ?>]" id="expirationdate-<?php echo $kp; ?>" class="expirationdate-select-input" value="<?php echo $brekingnews['expirationdate'][$kp]; ?>" style="max-width: 100%;" />
											</td>


											</tr>
											<?php
										} ?>


										</tbody>
									</table>
									<p>
										<label for="hpm_breakingnews[type]" class="screen-reader-text"><?php _e( "News Type:", 'hpmv4' ); ?></label>
										<select name="hpm_breakingnews[type]" id="hpm_breakingnews[type]">
											<option value=""></option>
											<option value="Breaking News" <?php selected( $brekingnews['type'] , "Breaking News", TRUE ); ?>>Breaking News</option>
											<option value="Developing Story" <?php selected( $brekingnews['type'] , "Developing Story", TRUE ); ?>>Developing Story</option>
										</select>

										<input type="hidden" required="required" name="hpm_breakingnews[number]" id="homepage-number" class="homepage-select-input" value="<?php echo ( !empty( $brekingnews['number'] ) ? $brekingnews['number'] : count( $brekingnews['homepage'] ) ); ?>" style="width: 150px;" /></p>
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
				$( ".hpm_breakingnews-select" ).change(function () {
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
				$( ".hpm_breakingnews-expirationdate-select" ).change(function () {
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
			});
		</script>
	</div>
<?php
}
?>