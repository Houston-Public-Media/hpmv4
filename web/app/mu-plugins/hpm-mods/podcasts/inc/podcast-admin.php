<?php
	$podcasts = new WP_Query([
		'post_type' => 'podcasts',
		'post_status' => 'publish',
		'orderby' => 'name',
		'order' => 'ASC'
	]); ?>
<div class="wrap">
	<h1><?php _e('Podcast Administration', 'hpm-podcasts' ); ?></h1>
	<?php settings_errors(); ?>
	<p><?php _e('The following sections will walk you through all of the data we need to gather to properly set up your podcast feeds.', 'hpm-podcasts' ); ?></p>
	<p><em>Feeds last refreshed: <span class="hpm-last-refresh-time"><?php echo $last_refresh; ?></span></em></p>
	<form method="post" action="options.php">
		<?php settings_fields( 'hpm-podcast-settings-group' ); ?>
		<?php do_settings_sections( 'hpm-podcast-settings-group' ); ?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">
					<div>
						<div class="postbox">
							<div class="postbox-header"><h2><?php _e('Ownership Information', 'hpm-podcasts' ); ?></h2></div>
							<div class="inside">
								<p><?php _e('iTunes and other podcasting directories ask for you to give a name and email address of the "owner" of the podcast, which can be a single person or an organization.', 'hpm-podcasts' ); ?></p>
								<table class="form-table">
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[owner][name]"><?php _e('Owner Name', 'hpm-podcasts' ); ?></label></th>
										<td><input type="text" name="hpm_podcast_settings[owner][name]" value="<?php echo $pods['owner']['name']; ?>" class="regular-text" /></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[owner][email]"><?php _e('Owner Email', 'hpm-podcasts' ); ?></label></th>
										<td><input type="email" name="hpm_podcast_settings[owner][email]" value="<?php echo $pods['owner']['email']; ?>" class="regular-text" /></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div>
						<div class="postbox">
							<div class="postbox-header"><h2><?php _e('User Roles', 'hpm-podcasts' ); ?></h2></div>
							<div class="inside">
								<p><?php _e('Select all of the user roles that you would like to be able to manage your podcast feeds.  Anyone
										who can create new posts can create an episode of a podcast, but only the roles selected here can
										create, alter, or delete podcast feeds.', 'hpm-podcasts' ); ?></p>
								<p><?php _e('To select more than one, hold down Ctrl (on Windows) or Command (on Mac) and click the roles you want included.', 'hpm-podcasts' ); ?></p>
								<table class="form-table">
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[roles]"><?php _e('Select Your Roles', 'hpm-podcasts' );
												?></label></th>
										<td>
											<select name="hpm_podcast_settings[roles][ ]" multiple class="regular-text">
												<?php foreach ( get_editable_roles() as $role_name => $role_info ) { ?>
													<option value="<?php echo $role_name; ?>"<?php echo ( in_array( $role_name, $pods['roles'] ) ? " selected" : '' ); ?>><?php echo $role_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div>
						<div class="postbox">
							<div class="postbox-header"><h2><?php _e('Background Tasks', 'hpm-podcasts' ); ?></h2></div>
							<div class="inside">
								<p><?php _e('To save server resources, we use a cron job to generate a flat XML file.  Use the options below to choose how often you want to run that job.', 'hpm-podcasts' ); ?></p>
								<table class="form-table">
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[recurrence]"><?php _e('Select Your Time Period', 'hpm-podcasts' );
												?></label></th>
										<td>
											<select name="hpm_podcast_settings[recurrence]" class="regular-text">
												<option value="hourly" <?php selected( $pods['recurrence'], 'hourly', TRUE );
												?>>Hourly</option>
												<option value="hpm_30min" <?php selected( $pods['recurrence'], 'hpm_30min', TRUE ); ?>>Every 30 Minutes</option>
												<option value="hpm_15min" <?php selected( $pods['recurrence'], 'hpm_15min', TRUE ); ?>>Every 15 Minutes</option>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div>
						<div class="postbox">
							<div class="postbox-header"><h2><?php _e('Upload Options', 'hpm-podcasts' ); ?></h2></div>
							<div class="inside">
								<p><?php _e('**NOTE**: Please do not include any leading or trailing slashes in your domains, URLs, folder names, etc. You can include slashes within them (e.g. you might store your files in the "files/podcasts" folder, but the public URL is "http://example.com/podcasts").', 'hpm-podcasts' );
									?></p>
								<table class="form-table">
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[upload-flats]"><?php _e('Flat XML File Upload?', 'hpm-podcasts' );
												?></label></th>
										<td>
											<select name="hpm_podcast_settings[upload-flats]" class="regular-text" id="hpm-flats">
												<option value="database" <?php selected( $pods['upload-flats'], 'database',	TRUE); ?>>Database</option>
											</select>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[upload-media]"><?php _e('Media File Upload?', 'hpm-podcasts' );
												?></label></th>
										<td>
											<select name="hpm_podcast_settings[upload-media]" class="regular-text" id="hpm-media">
												<option value="sftp" <?php selected( $pods['upload-media'], 'sftp', TRUE); ?>>FTP</option>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div id="hpm-sftp" class="hpm-uploads">
						<div class="postbox">
							<div class="postbox-header"><h2><?php _e('FTP Credentials', 'hpm-podcasts' ); ?></h2></div>
							<div class="inside">
								<p><?php _e("If you aren't comfortable storing your FTP password in your database, you can define it as a Wordpress default.  Add the following line to your wp-config.php file:",	'hpm-podcasts' );
									?></p>
								<pre>define('HPM_SFTP_PASSWORD', 'YOUR_SFTP_PASSWORD');</pre>
								<table class="form-table">
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[credentials][sftp][host]"><?php _e('FTP Host', 'hpm-podcasts' ); ?></label></th>
										<td><input type="text" name="hpm_podcast_settings[credentials][sftp][host]" value="<?php echo $pods['credentials']['sftp']['host']; ?>" class="regular-text" placeholder="URL or IP
											Address" /></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[credentials][sftp][url]"><?php _e('FTP Public URL', 'hpm-podcasts' ); ?></label></th>
										<td><input type="text" name="hpm_podcast_settings[credentials][sftp][url]" value="<?php echo $pods['credentials']['sftp']['url']; ?>" class="regular-text" placeholder="http://ondemand.example.com" /></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[credentials][sftp][username]"><?php _e('FTP Username', 'hpm-podcasts' ); ?></label></th>
										<td><input type="text" name="hpm_podcast_settings[credentials][sftp][username]" value="<?php echo
											$pods['credentials']['sftp']['username']; ?>" class="regular-text" placeholder="thisguy"
											/></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[credentials][sftp][password]"><?php _e('FTP Password', 'hpm-podcasts' ); ?></label></th>
										<td><input name="hpm_podcast_settings[credentials][sftp][password]" <?php
											if ( defined( 'HPM_SFTP_PASSWORD' ) ) {
												echo 'value="Set in wp-config.php" disabled type="text" ';
											} else {
												echo 'value ="'.$pods['credentials']['sftp']['password'].'" type="password" ';
											} ?>class="regular-text" placeholder="P@assw0rd" /></td>
									</tr>
									<tr valign="top">
										<th scope="row"><label for="hpm_podcast_settings[credentials][sftp][folder]"><?php _e('FTP Folder', 'hpm-podcasts' ); ?></label></th>
										<td><input type="text" name="hpm_podcast_settings[credentials][sftp][folder]" value="<?php echo $pods['credentials']['sftp']['folder']; ?>" class="regular-text" placeholder="folder" /></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div>
						<div class="postbox">
							<div class="postbox-header"><h2><?php _e('Feed Refresh', 'hpm-podcasts' ); ?></h2></div>
							<div class="inside">
								<p><?php _e("Made some changes to your podcast feeds and don't want to wait for the cron job to fire? Click the button below to force a refresh.", 'hpm-podcasts'	); ?></p>
								<p><em>Feeds last refreshed: <span class="hpm-last-refresh-time"><?php echo $last_refresh; ?></span></em></p>
								<table class="form-table">
									<tr valign="top">
										<th scope="row"><label><?php _e('Force Feed Refresh?', 'hpm-podcasts' );
												?></label></th>
										<td><a href="#" class="button button-secondary" id="hpm-pods-refresh">Refresh Feeds</a></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<br class="clear" />
		<?php submit_button(); ?>
	</form>
	<script>
		jQuery(document).ready(function($){
			$('#hpm-pods-refresh').click(function(e){
				e.preventDefault();
				$(this).after( ' <img id="hpm-refresh-spinner" src="<?PHP echo WP_SITEURL; ?>/wp-includes/images/spinner.gif">' );
				$.ajax({
					type: 'GET',
					url: '/wp-json/hpm-podcast/v1/refresh',
					data: '',
					beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', '<?php echo wp_create_nonce( 'wp_rest' ); ?>' );
					},
					success: function (response) {
						$('#hpm-refresh-spinner').remove();
						$('.hpm-last-refresh-time').html(response.data.date);
						$( '<div class="notice notice-success is-dismissible"><p>'+response.message+'</p></div>' ).insertBefore( $('#hpm-pods-refresh').closest('table.form-table') );
					},
					error: function (response) {
						$('#hpm-refresh-spinner').remove();
						if (typeof response.responseJSON.message !== 'undefined') {
							$( '<div class="notice notice-error is-dismissible"><p>'+response.responseJSON.message+'</p></div>' ).insertBefore( $('#hpm-pods-refresh').closest('table.form-table') );
						} else {
							console.log(response);
							$( '<div class="notice notice-error is-dismissible">There was an error while performing this function. Please consult your javascript console for more information.</div>' ).insertBefore( $('#hpm-pods-refresh').closest('table.form-table') );
						}
					}
				});
			});
		});
	</script>
</div>