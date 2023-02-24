<?php
/**
 * Required plugins and setup
 */
define( 'HPM_MODS_DIR', plugin_dir_path( __FILE__ ) );
define( 'HPM_MODS_URL', plugin_dir_url( __FILE__ ) );

require( HPM_MODS_DIR . 'extras/hpm_extras.php' );
require( HPM_MODS_DIR . 'podcasts/main.php' );
require( HPM_MODS_DIR . 'priority/hpm_priority.php' );
require( HPM_MODS_DIR . 'promos/hpm_promos.php' );
require( HPM_MODS_DIR . 'series/hpm_series.php' );
require( HPM_MODS_DIR . 'staff/hpm_staff.php' );
require( HPM_MODS_DIR . 'embeds/hpm_embeds.php' );
require( HPM_MODS_DIR . 'social/social_post.php' );

register_activation_hook( __FILE__, 'hpm_mods_activate' );
register_deactivation_hook( __FILE__, 'hpm_mods_deactivate' );

function hpm_mods_activate(): void {
	$pods = [
		'owner' => [
			'name' => '',
			'email' => ''
		],
		'recurrence' => 'hourly',
		'roles' => ['editor','administrator'],
		'upload-media' => 'sftp',
		'upload-flats' => 'database',
		'credentials' => [
			'sftp' => [
				'host' => '',
				'url' => '',
				'username' => '',
				'password' => '',
				'folder' => ''
			]
		]
	];
	$old = get_option( 'hpm_podcast_settings' );
	if ( empty( $old ) ) {
		update_option( 'hpm_podcast_settings', $pods, false );
	}
	update_option( 'hpm_podcast_last_update', 'none', false );
	HPM_Podcasts::create_type();
	flush_rewrite_rules();
	if ( ! wp_next_scheduled( 'hpm_podcast_update_refresh' ) ) {
		wp_schedule_event( time(), 'hourly', 'hpm_podcast_update_refresh' );
	}
}

function hpm_mods_deactivate(): void {
	wp_clear_scheduled_hook( 'hpm_podcast_update_refresh' );
	delete_option( 'hpm_podcast_settings' );
	delete_option( 'hpm_podcast_last_update' );
	flush_rewrite_rules();
}