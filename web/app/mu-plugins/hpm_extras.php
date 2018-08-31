<?php
/**
 * @link 			https://github.com/jwcounts
 * @since  			20170906
 * @package  		HPM-Extras
 *
 * @wordpress-plugin
 * Plugin Name: 	HPM Extras
 * Plugin URI: 		https://github.com/jwcounts
 * Description: 	Various HPM-related functions that don't fit into the HPMv2 theme
 * Version: 		20170906
 * Author: 			Jared Counts
 * Author URI: 		http://www.houstonpublicmedia.org/staff/jared-counts/
 * License: 		GPL-2.0+
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	hpmv2
 *
 * Works best with Wordpress 4.6.0+
 */

// Functions or modifications related to plugins or things that aren't directly theme-related

/**
 * Adds ability for anyone who can edit others' posts to be able to create and manage guest authors
 */
add_filter( 'coauthors_guest_author_manage_cap', 'capx_filter_guest_author_manage_cap' );
function capx_filter_guest_author_manage_cap( $cap ) {
	return 'edit_others_posts';
}

/**
 * Anyone who can publish can publish to Apple News
 */
add_filter( 'apple_news_publish_capability', 'publish_to_apple_news_cap' );
function publish_to_apple_news_cap( $cap ) {
	return 'publish_posts';
}

add_action( 'publish_post', 'hpm_apple_news_exclude', 10, 2 );
add_action( 'save_post', 'hpm_apple_news_exclude', 10, 2 );
add_action( 'owf_update_published_post', 'hpm_apple_news_exclude', 10, 2 );

function hpm_apple_news_exclude( $post_id, $post ) {
	$cats = get_the_category( $post_id );
	foreach ( $cats as $c ) :
		if ( $c->term_id == 27876 ) :
			apply_filters( 'apple_news_skip_push', true, $post_id );
		endif;
	endforeach;
}

/*
 * Add script so that javascript is detected and saved as a class on the body element
 */
function hpmv2_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'hpmv2_javascript_detection', 0 );

/*
 * Removes unnecessary metadata from the document head
 */
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Disable support for Wordpress Emojicons, because we will never use them and don't need the extra overhead
 */
function disable_wp_emojicons() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) :
		return array_diff( $plugins, array( 'wpemoji' ) );
	else :
		return array();
	endif;
}
add_action( 'init', 'disable_wp_emojicons' );

/*
 * Adding variables to the Wordpress query setup for special sections and external data pulls
 */
function add_query_vars($aVars) {
	$aVars[] = "sched_station";
	$aVars[] = "sched_year";
	$aVars[] = "sched_month";
	$aVars[] = "sched_day";
	$aVars[] = "sched_tv_query";
	$aVars[] = "sched_endpoint";
	$aVars[] = "npr_id";
	$aVars[] = "hpm_slug";
	$aVars[] = "hpm_slug_extra";
	$aVars[] = "hpm_epno";
	$aVars[] = "dc_id";
	$aVars[] = "dc_year";
	$aVars[] = "dc_month";
	$aVars[] = "dc_day";
	$aVars[] = "dc_slug";
	$aVars[] = "hm_old_id";
	return $aVars;
}
add_filter('query_vars', 'add_query_vars');

/*
 * Creating new rewrite rules to feed those special sections and external data pulls
 */
function add_rewrite_rules($aRules) {
	$aNewRules = array(
		'^(news887|classical|tv8)/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=day',
		'^(news887|classical|tv8)/schedule/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=day',
		'^(news887|classical|tv8)/schedule/([0-9]{4})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=day&sched_year=$matches[2]&sched_month=01&sched_day=01',
		'^(news887|classical|tv8)/schedule/([0-9]{4})/([0-9]{2})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=day&sched_year=$matches[2]&sched_month=$matches[3]&sched_day=01',
		'^(news887|classical|tv8)/schedule/([0-9]{4})/([0-9]{2})/([0-9]{2})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=day&sched_year=$matches[2]&sched_month=$matches[3]&sched_day=$matches[4]',
		'^(news887|classical)/schedule/(week)/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=$matches[2]',
		'^(news887|classical)/schedule/(week)/([0-9]{4})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=$matches[2]&sched_year=$matches[3]&sched_month=01&sched_day=01',
		'^(news887|classical)/schedule/(week)/([0-9]{4})/([0-9]{2})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=$matches[2]&sched_year=$matches[3]&sched_month=$matches[4]&sched_day=01',
		'^(news887|classical)/schedule/(week)/([0-9]{4})/([0-9]{2})/([0-9]{2})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_enpoint=$matches[2]&sched_year=$matches[3]&sched_month=$matches[4]&sched_day=$matches[5]',
		'^(tv8)/schedule/(search|episode|program)/([^/]+)/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_endpoint=$matches[2]&sched_tv_query=$matches[3]',
		'^npr/([0-9]{4})/([0-9]{2})/([0-9]{2})/([0-9]{9})/([a-z0-9\-]+)/?' => 'index.php?pagename=npr-articles&npr_id=$matches[4]',
		'^diversecity/([0-9]{4})/([0-9]{2})/([0-9]{2})/([0-9]+)/([a-z0-9\-]+)/?' => 'index.php?p=$matches[4]',
		'^hm-old/([0-9]+)/?$' => 'index.php?pagename=redirect&hm_old_id=$matches[1]'
	);
	$aRules = $aNewRules + $aRules;
	return $aRules;
}
add_filter('rewrite_rules_array', 'add_rewrite_rules');

/**
 *  Add new options for Cron Schedules
 */

add_filter( 'cron_schedules', 'hpm_cron_updates', 10, 2 );

function hpm_cron_updates( $schedules ) {
	$schedules['hpm_1min'] = array(
		'interval' => 60,
		'display' => __( 'Every Minute' )
	);
	$schedules['hpm_2min'] = array(
		'interval' => 120,
		'display' => __( 'Every Other Minute' )
	);
	$schedules['hpm_2hours'] = array(
		'interval' => 7200,
		'display' => __( 'Every Two Hours' )
	);
	$schedules['hpm_weekly'] = array(
		'interval' => 604800,
		'display' => __( 'Every Week' )
	);
	return $schedules;
}

/*
 * Save local copies of today's schedule JSON from NPR Composer2 into site transients
 */
function hpmv2_schedules( $station, $date ) {
	if ( empty( $station ) || empty( $date ) ) :
		return false;
	endif;
	$api = get_transient( 'hpm_' . $station . '_' . $date );
	if ( !empty( $api ) ) :
		return $api;
	endif;
	$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/".$station."/day?date=".$date."&format=json" ) );
	if ( is_wp_error( $remote ) ) :
		return false;
	else :
		$api = wp_remote_retrieve_body( $remote );
		$json = json_decode( $api, TRUE );
	endif;
	$c = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$c = $c + $offset;
	$now = getdate( $c );
	$old = $now[0] - 86400;
	$new = $now[0] + 432000;
	$date_exp = explode( '-', $date );
	$dateunix = mktime( 0, 0, 0, $date_exp[1], $date_exp[2], $date_exp[0] );
	if ( $dateunix > $old && $dateunix < $new ) :
		set_transient( 'hpm_' . $station . '_' . $date, $json, 300 );
	endif;
	return $json;
}

/*
 * Pull and display what is currently playing on TV and Radio from their respective services
 *
 * Set up Cron job to update these every 2 minutes
 */
function hpmv2_nowplaying ( $station ) {
	return get_option( 'hpm_'.$station.'_nowplay' );
}

function hpmv2_nowplaying_update () {
	$stations = array( 'news887', 'classical', 'tv8.1', 'tv8.2', 'tv8.3', 'tv8.4' );
	foreach ( $stations as $station ) :
		if ( $station == 'news887' ) :
			$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/now?format=json" ) );
			if ( is_wp_error( $remote ) ) :
				continue;
			else :
				$json = wp_remote_retrieve_body( $remote );
				$dom = json_decode( $json, true );
			endif;
			$output = "<h3>".str_replace('&','&amp;',$dom['onNow']['program']['name'])."</h3>";
			update_option( 'hpm_'.$station.'_nowplay', $output );
		elseif ($station == 'classical') :
			$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/51913211e1c8408134a6d347/now?format=json&show_song=true" ) );
			if ( is_wp_error( $remote ) ) :
				continue;
			else :
				$json = wp_remote_retrieve_body( $remote );
				$dom = json_decode( $json, true );
			endif;
			if ( !empty( $dom['onNow']['song']['ensembles'] ) ) :
				$ense = $dom['onNow']['song']['ensembles'].' - ';
			else :
				$ense = '';
			endif;
			if (!empty($dom['onNow']['song'])) :
				$descs = array();
				if (!empty($dom['onNow']['song']['composerName'])) :
					$descs[] = "Composer: ".$dom['onNow']['song']['composerName'];
				endif;

				if (!empty($dom['onNow']['song']['conductor'])) :
					$descs[] = "Conductor: ".$dom['onNow']['song']['conductor'];
				endif;

				if (!empty($dom['onNow']['song']['copyright']) && !empty($dom['onNow']['song']['catalogNumber'])) :
					$descs[] = "Catalog Number: ".$dom['onNow']['song']['copyright']." ".$dom['onNow']['song']['catalogNumber'];
				endif;

				$desc = implode( ', ', $descs );
				$output = "<h3>".$ense.str_replace('&','&amp;',$dom['onNow']['song']['trackName'])."</h3><p>".$desc."</p>";
			else :
				$output = "<h3>".$ense.str_replace('&','&amp;',$dom['onNow']['program']['name'])."</h3>";
			endif;
			update_option( 'hpm_'.$station.'_nowplay', $output );
		elseif ( $station == 'tv8.1' || $station == 'tv8.2' || $station == 'tv8.3' || $station == 'tv8.4' ) :
			$channel = str_replace( 'tv8.', '', $station );
			$url = 'http://pw.myersinfosys.com/kuht/whats-on-now.xml?col_no='.$channel;
			$remote = wp_remote_get( esc_url_raw( $url ) );
			if ( is_wp_error( $remote ) ) :
				continue;
			else :
				$body = wp_remote_retrieve_body( $remote );
				$xml = simplexml_load_string( $body );
				$r = $xml->xpath('//ser-title');
				$output = "<h3>".$r[0]->__toString()."</h3>";
				update_option( 'hpm_' . $station . '_nowplay', $output );
			endif;
		endif;
	endforeach;
}

add_action( 'hpm_nowplay_update', 'hpmv2_nowplaying_update' );
$timestamp = wp_next_scheduled( 'hpm_nowplay_update' );
if ( empty( $timestamp ) ) :
	wp_schedule_event( time(), 'hpm_2min', 'hpm_nowplay_update' );
endif;

/*
 * Log errors in wp-content/debug.log when debugging is enabled.
 */
if ( !function_exists( 'log_it' ) ) :
	function log_it( $message ) {
		if( WP_DEBUG === true ) :
			if ( is_array( $message ) || is_object( $message ) ) :
				error_log( print_r( $message, true ) );
			else :
				error_log( $message );
			endif;
		endif;
	}
endif;

/*
 * Add checkbox to post editor in order to hide last modified time in the post display (single.php)
 */
add_action( 'post_submitbox_misc_actions', 'hpm_no_mod_time' );
function hpm_no_mod_time() {
	global $post;
	if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) return false;
	if ( $post->post_type == 'post' ) {
		$value = get_post_meta( $post->ID, 'hpm_no_mod_time', true );
		$checked = ! empty( $value ) ? ' checked="checked" ' : '';
		echo '<div class="misc-pub-section misc-pub-section-last"><input type="checkbox"' . $checked . 'value="1" name="hpm_no_mod_time" /><label for="hpm_no_mod_time">Hide Last Modified Time?</label></div>';
	}
}

add_action( 'save_post', 'save_hpm_no_mod_time');
function save_hpm_no_mod_time( ) {
	global $post;
	if ( empty( $post ) || $post->post_type != 'post' ) return false;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
	if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) return false;
	if ( empty( $post->ID ) ) return false;
	$value = ( !empty( $_POST['hpm_no_mod_time'] )  ? 1 : 0 );

	update_post_meta( $post->ID, 'hpm_no_mod_time', $value );
}

/*
 * Disallow certain MIME types from being accepted by the media uploader
 */
function custom_upload_mimes ( $existing_mimes=array() ) {
	unset( $existing_mimes['exe'] );
	unset( $existing_mimes['wav'] );
	unset( $existing_mimes['ra|ram'] );
	unset( $existing_mimes['mid|midi'] );
	unset( $existing_mimes['wma'] );
	unset( $existing_mimes['wax'] );
	unset( $existing_mimes['swf'] );
	unset( $existing_mimes['class'] );
	unset( $existing_mimes['js'] );
	return $existing_mimes;
}
add_filter('upload_mimes', 'custom_upload_mimes');

/*
 * Finds the last 5 entries in the specified YouTube playlist and saves into a site transient
 */
function hpm_youtube_playlist( $key ) {
	$list = get_transient( 'hpm_yt_'.$key );
	if ( !empty( $list ) ) :
		return $list;
	endif;
	$remote = wp_remote_get( esc_url_raw( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='.$key.'&key=AIzaSyBHSGTRPfGElaMTniNCtHNbHuGHKcjPRxw' ) );
	if ( is_wp_error( $remote ) ) :
		return false;
	else :
		$yt = wp_remote_retrieve_body( $remote );
		$json = json_decode( $yt, TRUE );
	endif;
	$totalResults = $json['pageInfo']['totalResults'];
	$resultsPerPage = $json['pageInfo']['resultsPerPage'];
	$times = array( strtotime( $json['items'][0]['snippet']['publishedAt'] ), strtotime( $json['items'][1]['snippet']['publishedAt'] ), strtotime( $json['items'][2]['snippet']['publishedAt'] ) );
	if ( $times[0] > $times[1] && $times[1] > $times[2] ) :
		$new2old = TRUE;
	elseif ( $times[2] > $times[1] && $times[1] > $times[0] ) :
		$new2old = FALSE;
	else :
		$new2old = TRUE;
	endif;
	if ( $new2old ) :
		$items = $json['items'];
	else :
		if ( $totalResults > $resultsPerPage ) :
			$pages = floor( $totalResults / $resultsPerPage );
			for ( $i=0; $i < $pages; $i++ ) :
				if ( !empty( $json['nextPageToken'] ) ) :
					$remote = wp_remote_get( esc_url_raw( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId='.$key.'&pageToken='.$json['nextPageToken'].'&key=AIzaSyBHSGTRPfGElaMTniNCtHNbHuGHKcjPRxw' ) );
					if ( is_wp_error( $remote ) ) :
						return false;
					else :
						$yt = wp_remote_retrieve_body( $remote );
						$json = json_decode( $yt, TRUE );
					endif;
				endif;
			endfor;
		endif;
		$items = array_reverse( $json['items'] );
	endif;
	$json_r = array_slice( $items, 0, 5 );
	set_transient( 'hpm_yt_'.$key, $json_r, 300 );
	return $json_r;
}

/**
 * Push 25 most recent local stories to NPR API
 */
//function hpm_npr_update() {
//	global $wpdb;
//	$msgs = array();
//	$now = getdate();
//	wp_set_current_user( 1 );
//	$posts = $wpdb->get_results(
//		"SELECT wp_posts.*
//              FROM wp_posts,wp_users
//              WHERE wp_posts.post_type = 'post'
//                AND wp_posts.post_title NOT LIKE 'Engines of%'
//                AND wp_posts.post_status = 'publish'
//                AND wp_posts.post_author NOT IN (0,1,42,59)
//                AND wp_posts.post_author = wp_users.ID
//                AND wp_posts.post_content NOT LIKE '%[gallery%'
//                AND wp_posts.post_excerpt != ''
//              ORDER BY wp_posts.post_date DESC
//              LIMIT 25" , OBJECT);
//	foreach ( $posts as $p ) :
//		$npr_id_before = get_post_meta( $p->ID, 'npr_story_id', true );
//		$nprone = get_post_meta( $p->ID, '_send_to_nprone', true );
//		$media = get_attached_media( 'audio' );
//		if ( !empty( $media ) && empty( $nprone ) ) :
//			update_post_meta( $p->ID, '_send_to_nprone', 1 );
//		endif;
//		$time_since = $now[0] - strtotime( $p->post_modified_gmt );
//		if ( empty( $npr_id_before ) || ( !empty( $npr_id_before ) && ( $time_since < 1800 ) ) ) :
//			nprstory_api_push( $p->ID, $p );
//			$error_temp = get_post_meta( $p->ID, 'npr_push_story_error', true );
//			if ( !empty( $error_temp ) ) :
//				$msgs[] = $error_temp;
//			endif;
//		endif;
//	endforeach;
//	if ( !empty( $msgs ) ) :
//		$msgs[] = date('r');
//		wp_mail( 'jcounts@houstonpublicmedia.org', 'NPR Story Push Results', implode("\n\n",$msgs) );
//	endif;
//}
//
//add_action( 'hpm_nprapi_push', 'hpm_npr_update' );
//$timestamp = wp_next_scheduled( 'hpm_nprapi_push' );
//if ( empty( $timestamp ) ) :
//	wp_schedule_event( time(), 'hourly', 'hpm_nprapi_push' );
//endif;

/*
 * Ping Facebook's OpenGraph servers whenever a post is published, in order to prime their cache
 */
function hpm_facebook_ping( $arg1 ) {
	$perma = get_permalink( $arg1 );
	$url = 'http://graph.facebook.com';
	$data = array('id' => $perma, 'scrape' => 'true');
	$options = array(
		'headers' => array(
			"Content-type" => "application/x-www-form-urlencoded"
		),
		'body' => $data
	);
	$remote = wp_remote_get( esc_url_raw( $url ), $options );
	if ( is_wp_error( $remote ) ) :
		return false;
	else :
		return true;
	endif;
}
function hpm_facebook_ping_schedule( $post_id, $post ) {
	if ( WP_ENV == 'production' ) :
		wp_schedule_single_event( time() + 60, 'hpm_facebook_ping', array( $post_id ) );
	endif;
}

add_action( 'publish_post', 'hpm_facebook_ping_schedule', 10, 2 );

add_action( 'owf_update_published_post', 'update_post_meta_info', 10, 2 );

/**
 * @param $original_post_id
 * @param $revised_post
 *
 * Copy over any metadata from an article revision to its original
 */
function update_post_meta_info( $original_post_id, $revised_post ) {
	$post_meta_keys = get_post_custom_keys($revised_post->ID);
	if ( empty( $post_meta_keys ) ) :
		return;
	endif;

	foreach ( $post_meta_keys as $meta_key ) :
		$meta_key_trim = trim($meta_key);
		if ( '_' == $meta_key_trim{0} || strpos($meta_key_trim,'oasis') !== false ) :
			continue;
		endif;
		$revised_meta_values = get_post_custom_values( $meta_key, $revised_post->ID );
		$original_meta_values = get_post_custom_values( $meta_key, $original_post_id );

		// find the bigger array of the two
		$meta_values_count = count( $revised_meta_values ) > count($original_meta_values) ? count( $revised_meta_values ) : count($original_meta_values);

		// loop through the meta values to find what's added, modified and deleted.
		for( $i = 0; $i < $meta_values_count; $i++) :
			$new_meta_value = "";
			// delete if the revised post doesn't have that key
			if ( count( $revised_meta_values ) >= $i+1 ) :
				$new_meta_value = maybe_unserialize( $revised_meta_values[$i] );
			else :
				$old_meta_value = maybe_unserialize( $original_meta_values[$i] );
				delete_post_meta( $original_post_id, $meta_key, $old_meta_value );
				continue;
			endif;

			// old meta values got updated, so simply update it
			if ( count( $original_meta_values ) >= $i+1 ) :
				$old_meta_value = maybe_unserialize( $original_meta_values[$i] );
				update_post_meta( $original_post_id, $meta_key, $new_meta_value, $old_meta_value );
			endif;

			// new meta values got added, so add it
			if ( count( $original_meta_values ) < $i+1 ) :
				add_post_meta( $original_post_id, $meta_key, $new_meta_value );
			endif;

		endfor;
	endforeach;
}

/**
 * Authorization function for accessing Google Analytics API
 * @return Google_Service_Analytics
 */
function initializeAnalytics()
{
	$KEY_FILE_LOCATION = SITE_ROOT . '/client_secrets.json';

	// Create and configure a new client object.
	$client = new Google_Client();
	$client->setApplicationName("Hello Analytics Reporting");
	$client->setAuthConfig($KEY_FILE_LOCATION);
	$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
	$analytics = new Google_Service_Analytics($client);

	return $analytics;
}

/**
 * Cron task to pull top 5 most-viewed stories from the last 3 days
 */
function analyticsPull_update() {
	require_once SITE_ROOT . '/vendor/autoload.php';
	$analytics = initializeAnalytics();
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	$then = $now[0] - 172800;
	$result = $analytics->data_ga->get(
		'ga:142153354',
		date( "Y-m-d", $then ),
		date( "Y-m-d", $now[0] ),
		'ga:visits',
		array(
			'filters' => 'ga:pagePath=@/articles',
			'dimensions'  => 'ga:pagePath',
			'metrics'     => 'ga:pageviews,ga:uniquePageviews',
			'sort'        => '-ga:pageviews,-ga:uniquePageviews',
			'max-results' => '5',
			'output'      => 'json'
		)
	);
	$output = "<ul>";
	foreach ( $result->rows as $row ) :
		preg_match( '/\/articles\/([a-z0-9\-\/]+)\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/([0-9]{1,6})\/.+/', $row[0], $match );
		if ( !empty( $match ) ) :
			$output .= '<li><h2 class="entry-title"><a href="'.$row[0].'" rel="bookmark">'.get_the_title( $match[2] ).'</a></h2></li>';
		endif;
	endforeach;
	$output .= "</ul>";
	update_option( 'hpm_most_popular', $output );
}

function analyticsPull() {
	return get_option( 'hpm_most_popular' );
}

add_action( 'hpm_analytics', 'analyticsPull_update' );
$timestamp = wp_next_scheduled( 'hpm_analytics' );
if ( empty( $timestamp ) ) :
	wp_schedule_event( time(), 'hourly', 'hpm_analytics' );
endif;


/**
 * @return mixed|string
 * Pull NPR API articles and save them to a transient
 */
function hpm_nprapi_output() {
	$npr = get_transient( 'hpm_nprapi' );
	if ( !empty( $npr ) ) :
		return $npr;
	endif;
	$output = '';
	$remote = wp_remote_get( esc_url_raw( "https://api.npr.org/query?id=1001&fields=title,teaser,image,storyDate&requiredAssets=image,audio,text&startNum=0&dateType=story&output=JSON&numResults=4&apiKey=MDAyMTgwNzc5MDEyMjQ4ODE4MjMyYTExMA001" ) );
	if ( is_wp_error( $remote ) ) :
		return "<p></p>";
	else :
		$npr = wp_remote_retrieve_body( $remote );
		$npr_json = json_decode( $npr, TRUE );
	endif;
	foreach ( $npr_json['list']['story'] as $story ) :
		$npr_date = strtotime($story['storyDate']['$text']);
		$output .= '<article class="national-content">';
		if ( !empty( $story['image'][0]['src'] ) ) :
			$output .= '<div class="national-image" style="background-image: url('.$story['image'][0]['src'].')"><a href="//www.houstonpublicmedia.org/npr/'.date('Y/m/d/',$npr_date).$story['id'].'/'.sanitize_title($story['title']['$text']).'/" class="post-thumbnail"></a></div><div class="national-text">';
		else :
			$output .= '<div class="national-text-full">';
		endif;
		$output .= '<h2><a href="//www.houstonpublicmedia.org/npr/'.date('Y/m/d/',$npr_date).$story['id'].'/'.sanitize_title($story['title']['$text']).'/">'.$story['title']['$text'].'</a></h2><p class="screen-reader-text">'
		           .$story['teaser']['$text'].'</p></div></article>';
	endforeach;
	set_transient( 'hpm_nprapi', $output, 300 );
	return $output;
}

/**
 * Set up post type and tools for emergency info at the top of the site
 */
add_action( 'init', 'create_hpm_emergency' );
function create_hpm_emergency() {
	register_post_type( 'emergency',
		array(
			'labels' => array(
				'name' => __( 'Emergency Info' ),
				'singular_name' => __( 'Emergency Info' ),
				'menu_name' => __( 'Emergency Info' ),
				'add_new_item' => __( 'Add New Emergency Info' ),
				'edit_item' => __( 'Edit Emergency Info' ),
				'new_item' => __( 'New Emergency Info' ),
				'view_item' => __( 'View Emergency Info' ),
				'search_items' => __( 'Search Emergency Info' ),
				'not_found' => __( 'Emergency Info Not Found' ),
				'not_found_in_trash' => __( 'Emergency Info not found in trash' )
			),
			'description' => 'Emergency info for top of the homepage',
			'public' => false,
			'show_ui' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-warning',
			'has_archive' => false,
			'rewrite' => false,
			'supports' => array( 'title' ),
			'can_export' => false,
			'capability_type' => array('hpm_emergency','hpm_emergencies'),
			'map_meta_cap' => true
		)
	);
}
add_action('admin_init','hpm_br_emer_add_role_caps',999);
function hpm_br_emer_add_role_caps() {
	// Add the roles you'd like to administer the custom post types
	$roles = array('editor','administrator');

	// Loop through each role and assign capabilities
	foreach($roles as $the_role) :
		$role = get_role($the_role);
		$role->add_cap( 'read' );
		$role->add_cap( 'read_hpm_emergency');
		$role->add_cap( 'read_private_hpm_emergencies' );
		$role->add_cap( 'edit_hpm_emergency' );
		$role->add_cap( 'edit_hpm_emergencies' );
		$role->add_cap( 'edit_others_hpm_emergencies' );
		$role->add_cap( 'edit_published_hpm_emergencies' );
		$role->add_cap( 'publish_hpm_emergencies' );
		$role->add_cap( 'delete_others_hpm_emergencies' );
		$role->add_cap( 'delete_private_hpm_emergencies' );
		$role->add_cap( 'delete_published_hpm_emergencies' );
	endforeach;
}

add_action( 'load-post.php', 'hpm_break_setup' );
add_action( 'load-post-new.php', 'hpm_break_setup' );
function hpm_break_setup() {
	add_action( 'add_meta_boxes', 'hpm_break_add_meta' );
	add_action( 'save_post', 'hpm_break_save_meta', 10, 2 );
	add_action( 'post_submitbox_misc_actions', 'hpm_break_unpub_date' );
}

function hpm_break_add_meta() {
	global $post;
	if ( $post->post_type == 'emergency' ) :
		add_meta_box(
			'hpm-break-meta-class',
			esc_html__( 'Link URL', 'example' ),
			'hpm_break_meta_box',
			$post->post_type,
			'normal',
			'core'
		);
	endif;
}

function hpm_break_meta_box( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'hpm_break_class_nonce' );
	$exists_meta = metadata_exists( 'post', $object->ID, 'hpm_break_meta' );

	if ( $exists_meta ) :
		$hpm_break_meta = get_post_meta( $object->ID, 'hpm_break_meta', true );
		if ( empty( $hpm_break_meta ) ) :
			$hpm_break_meta = '';
		endif;
	else :
		$hpm_break_meta = '';
	endif;

	?>
	<p><?PHP _e( "Enter the URL you would like this item to link to.  If you don't want offer a link, either leave it blank or type a #.", 'example' ); ?></p>
	<ul>
		<li><label for="hpm-break-url"><?php _e( "URL:", 'example' ); ?></label> <input type="text" id="hpm-break-url" name="hpm-break-url" value="<?PHP echo $hpm_break_meta; ?>" placeholder="http://highway2.thedanger.zone/" style="width: 60%;" /></li>
	</ul>
<?php }

function hpm_break_unpub_date() {
	global $post;
	if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) return false;
	if ( $post->post_type == 'emergency' ) :
		$endtime = get_post_meta( $post->ID, 'hpm_break_end_time', true );
		$offset = get_option('gmt_offset')*3600;
		if ( empty( $endtime ) ) :
			$t = time() + $offset + ( 24 * HOUR_IN_SECONDS );
		else :
			$t = $endtime + $offset;
		endif;
		$timeend = array(
			'mon' => date( 'm', $t),
			'day' => date( 'd', $t),
			'year' => date( 'Y', $t),
			'hour' => date( 'H', $t),
			'min' => date( 'i', $t)
		);

		?>
		<div class="misc-pub-section curtime misc-pub-curtime">
			<span id="endtimestamp">End Date:</span>
			<fieldset id="endtimestampdiv">
				<legend class="screen-reader-text">End date and time</legend>
				<div class="timestamp-wrap">
					<label>
						<span class="screen-reader-text">Month</span>
						<select id="hpm_break_end_mon" name="hpm_break[end][mon]">
							<option value="01" data-text="Jan" <?PHP selected( $timeend['mon'], '01', TRUE ); ?>>01-Jan</option>
							<option value="02" data-text="Feb" <?PHP selected( $timeend['mon'], '02', TRUE ); ?>>02-Feb</option>
							<option value="03" data-text="Mar" <?PHP selected( $timeend['mon'], '03', TRUE ); ?>>03-Mar</option>
							<option value="04" data-text="Apr" <?PHP selected( $timeend['mon'], '04', TRUE ); ?>>04-Apr</option>
							<option value="05" data-text="May" <?PHP selected( $timeend['mon'], '05', TRUE ); ?>>05-May</option>
							<option value="06" data-text="Jun" <?PHP selected( $timeend['mon'], '06', TRUE ); ?>>06-Jun</option>
							<option value="07" data-text="Jul" <?PHP selected( $timeend['mon'], '07', TRUE ); ?>>07-Jul</option>
							<option value="08" data-text="Aug" <?PHP selected( $timeend['mon'], '08', TRUE ); ?>>08-Aug</option>
							<option value="09" data-text="Sep" <?PHP selected( $timeend['mon'], '09', TRUE ); ?>>09-Sep</option>
							<option value="10" data-text="Oct" <?PHP selected( $timeend['mon'], '10', TRUE ); ?>>10-Oct</option>
							<option value="11" data-text="Nov" <?PHP selected( $timeend['mon'], '11', TRUE ); ?>>11-Nov</option>
							<option value="12" data-text="Dec" <?PHP selected( $timeend['mon'], '12', TRUE ); ?>>12-Dec</option>
						</select>
					</label>
					<label>
						<span class="screen-reader-text">Day</span>
						<input type="text" id="hpm_break_end_day" name="hpm_break[end][day]" value="<?php echo $timeend['day']; ?>" size="2" maxlength="2" autocomplete="off">
					</label>,
					<label>
						<span class="screen-reader-text">Year</span>
						<input type="text" id="hpm_break_end_year" name="hpm_break[end][year]" value="<?php echo $timeend['year']; ?>" size="4" maxlength="4" autocomplete="off">
					</label> @
					<label>
						<span class="screen-reader-text">Hour</span>
						<input type="text" id="hpm_break_end_hour" name="hpm_break[end][hour]" value="<?php echo $timeend['hour']; ?>" size="2" maxlength="2" autocomplete="off">
					</label>:
					<label>
						<span class="screen-reader-text">Minute</span>
						<input type="text" id="hpm_break_end_min" name="hpm_break[end][min]" value="<?php echo $timeend['min']; ?>" size="2" maxlength="2" autocomplete="off">
					</label>
				</div>
			</fieldset>
		</div>
		<style>
			.curtime #endtimestamp {
				padding: 2px 0 1px 0;
				display: inline !important;
				height: auto !important;
			}
			.curtime #endtimestamp:before {
				content: "\f145";
				position: relative;
				top: -1px;
				font: normal 20px/1 dashicons;
				speak: none;
				display: inline-block;
				margin-left: -1px;
				padding-right: 3px;
				vertical-align: top;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				color: #82878c;
			}
			#endtimestampdiv {
				padding-top: 5px;
				line-height: 23px;
			}
			#endtimestampdiv select {
				height: 21px;
				line-height: 14px;
				padding: 0;
				vertical-align: top;
				font-size: 12px;
			}
			#endtimestampdiv input {
				border-width: 1px;
				border-style: solid;
			}
			#hpm_break_end_day,
			#hpm_break_end_hour,
			#hpm_break_end_min {
				width: 2em;
			}
			#hpm_break_end_year,
			#hpm_break_end_day,
			#hpm_break_end_hour,
			#hpm_break_end_min {
				padding: 1px;
				font-size: 12px;
			}
		</style>
		<?php
	endif;
}

function hpm_break_save_meta( $post_id, $post ) {
	if ( $post->post_type == 'emergency' ) :
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['hpm_break_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_break_class_nonce'], basename( __FILE__ ) ) )
			return $post_id;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) :
			return $post_id;
		endif;

		$hpend = $_POST['hpm_break']['end'];

		foreach ( $hpend as $hpe ) :
			if ( !is_numeric( $hpe ) || $hpe == '' ) :
				return $post_id;
			endif;
		endforeach;

		$offset = get_option('gmt_offset')*3600;
		$endtime = mktime( $hpend['hour'], $hpend['min'], 0, $hpend['mon'], $hpend['day'], $hpend['year'] ) - $offset;
		update_post_meta( $post_id, 'hpm_break_end_time', $endtime );

		/* Get the posted data and sanitize it for use as an HTML class. */
		$hpm_meta = ( !empty( $_POST['hpm-break-url'] ) ? sanitize_text_field( $_POST['hpm-break-url'] ) : '' );

		$exists_meta = metadata_exists( 'post', $post_id, 'hpm_break_meta' );

		if ( $exists_meta ) :
			update_post_meta( $post_id, 'hpm_break_meta', $hpm_meta );
		else :
			add_post_meta( $post_id, 'hpm_break_meta', $hpm_meta, true );
		endif;
	endif;
}

/**
 * Hide the Comments menu in Admin because we don't use it
 */
function remove_menus(){
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );

function hpm_render_tweet( $j ) {
	$find = array( '/\n/' );
	$replace = array( '<br />' );
	$offset = get_option( 'gmt_offset' ) * 3600;
	$time = strtotime( $j['created_at'] ) + $offset;
	$date = date( 'F j, Y, g:i A', $time );
	$date_diff = hpm_time_diff( $time );
	$text = "<p>".$j['full_text']."</p>";
	$ent = $j['entities'];
	$output = '';
	if ( !empty( $ent['hashtags'] ) ) :
		foreach( $ent['hashtags'] as $h ) :
			$text = str_replace( '#'.$h['text'], '<a href="https://twitter.com/hashtag/'.$h['text'].'">#'.$h['text'].'</a>', $text );
		endforeach;
	endif;
	if ( !empty( $ent['symbols'] ) ) :
		foreach( $ent['symbols'] as $s ) :
			$text = str_replace( '$'.$s['text'], '<a href="https://twitter.com/search?q=%24'.$s['text'].'&src=ctag">$'.$s['text'].'</a>', $text );
		endforeach;
	endif;
	if ( !empty( $ent['user_mentions'] ) ) :
		foreach( $ent['user_mentions'] as $u ) :
			$text = str_replace( '@'.$u['screen_name'], '<a href="https://twitter.com/'.$u['screen_name'].'">@'.$u['screen_name'].'</a>', $text );
		endforeach;
	endif;
	if ( !empty( $ent['urls'] ) ) :
		foreach( $ent['urls'] as $url ) :
			if ( $j['is_quote_status'] && strpos( $url['expanded_url'], $j['quoted_status_id_str'] ) !== false ) :
				$url_r = '';
			else :
				$url_r = '<a href="'.$url['expanded_url'].'">'.$url['url'].'</a>';
			endif;
			$text = str_replace( $url['url'], $url_r, $text );
		endforeach;
	endif;
	if ( !empty( $ent['media'] ) ) :
		$text = str_replace( $ent['media'][0]['url'], '', $text );
		$media_out = '<div class="tweet-photos tweet-photos-'.count( $j['extended_entities']['media'] ).'">';
		foreach( $j['extended_entities']['media'] as $k => $m ) :
			$media_out .= '<div class="tweet-photo" style="background-image: url('.$m['media_url_https'].');"><a href="'.$m['expanded_url'].'"></a></div>';
		endforeach;
		$media_out .= '</div>';
		$text .= $media_out;
	endif;
	$text = preg_replace( $find, $replace, $text );
	$output .= '<div class="tweet-head"><div class="tweet-avi" style="background-image: url('.$j['user']['profile_image_url_https'].');"><a href="https://twitter.com/'.$j['user']['screen_name'].'" target="_blank"></a></div><div class="tweet-user"><h2><a href="https://twitter.com/'.$j['user']['screen_name'].'" target="_blank">'.$j['user']['name'].'</a></h2><h3><a href="https://twitter.com/'.$j['user']['screen_name'].'" target="_blank">@'.$j['user']['screen_name'].'</a></h3></div><div class="tweet-time"><span class="tweet-time-full" title="'.$date.'">'.$date_diff.'</span></div></div><div class="tweet-body">'.$text.'</div>';
	return $output;
}

function hpm_time_diff( $then ) {
	if ( !is_numeric( $then ) ) :
		return 'Unix times only plz';
	else :
		$c = time();
		$offset = get_option( 'gmt_offset' ) * 3600;
		$now = $c + $offset;
		if ( $now < $then ) :
			return "Whoa there cowboy, check your dates";
		else :
			$diff = $now - $then;
			if ( $diff < 60 ) :
				return $diff."s";
			elseif ( $diff >= 60 && $diff < 3600 ) :
				return round( $diff/60 )."m";
			elseif ( $diff >= 3600 && $diff < 86400 ) :
				return round( $diff/3600 )."h";
			elseif ( $diff >= 86400 && $diff < 31536000 ) :
				return round( $diff/86400 )."d";
			else :
				return round( $diff/31536000 )."y";
			endif;
		endif;
	endif;
}

function hpm_tweets( $account, $num ) {
	if ( empty( $account ) ) :
		return "Please provide an account name";
	endif;
	if ( empty( $num ) ) :
		$num = 20;
	endif;
	$output = '';
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$account.'&count='.$num.'&include_rts=true&tweet_mode=extended';
	$opts = array( 'headers' => array( "Authorization" => "Bearer AAAAAAAAAAAAAAAAAAAAAHC03AAAAAAAZo5NNz4NqlK6%2FjlJjcnhScYP3FQ%3DLR2gnzwqO2dn1SUGolipkUULalisg6DOpRfKlEVqzvYw7XtKfs" ) );
	$remote = wp_remote_get( esc_url_raw( $url ), $opts );
	if ( is_wp_error( $remote ) ) :
		echo "Sorry, no tweets right now.";
	else :
		$raw = wp_remote_retrieve_body( $remote );
		$json = json_decode( $raw, true );
		$output .= '<aside id="twitter-home"><h1>Tweets by <a href="https://twitter.com/'.$account.'">@'.$account.'</a></h1><div id="twitter-wrap">';
		foreach ( $json as $j ) :
			if ( !empty( $j['retweeted_status'] ) ) :
				$r = $j['retweeted_status'];
				if ( $r['is_quote_status'] ) :
					$output .= '<div class="tweet">'.hpm_render_tweet( $r ).'<div class="tweet">'.hpm_render_tweet( $r['quoted_status'] ).'</div><p class="tweet-rt">🔄 Retweeted by <a 
href="https://twitter.com/'.$j['user']['screen_name'].'" target="_blank">'.$j['user']['name'].'</a></p></div>';
				else :
					$output .= '<div class="tweet">'.hpm_render_tweet( $r ).'<p class="tweet-rt">🔄 Retweeted by <a href="https://twitter.com/'.$j['user']['screen_name'].'" 
target="_blank">'.$j['user']['name'].'</a></p></div>';
				endif;
			elseif ( $j['is_quote_status'] ) :
				$output .= '<div class="tweet">'.hpm_render_tweet( $j ).'<div class="tweet">'.hpm_render_tweet( $j['quoted_status'] ).'</div></div>';
			else :
				$output .= '<div class="tweet">'.hpm_render_tweet( $j ).'</div>';
			endif;
		endforeach;
		$output .= '</div><p style="text-align:center;"><a href="https://twitter.com/'.$account.'" class="readmorelarge">Read More</a></p></aside>';
	endif;
	update_option( 'hpm_'.$account.'_tweets', $output, false );
}

function hpm_tweet_dl() {
	hpm_tweets( 'houstonpubmedia', 25 );
}

add_action( 'hpm_tweets', 'hpm_tweet_dl' );
$timestamp = wp_next_scheduled( 'hpm_tweets' );
if ( empty( $timestamp ) ) :
	wp_schedule_event( time(), 'hpm_1min', 'hpm_tweets' );
endif;

function wpdocs_set_html_mail_content_type() {
    return 'text/html';
}

function hpm_logins_cleanup() {
	$logins = get_option( 'limit_login_logged' );
	if ( !empty( $logins ) ) :
		$temp = $ips = array();
		$output = '';
		$sheet = array(
			'Logins' => array(),
			'IPs' => array()
		);
		$sheet['Logins'][] = array( 'Date', 'User IP', 'Server IP', 'Attempted Login Name', 'Lockouts', 'Gateway' );
		$sheet['IPs'][] = array( 'User IP', 'Number of Lockouts', 'Country', 'Region', 'City', 'ISP' );
		foreach	( $logins as $k => $v ) :
			$ip = explode( ',', $k );
			foreach ( $v as $kk => $vv ) :
				$name = $kk;
				$date = $vv['date'];
				$gateway = $vv['gateway'];
				$count = $vv['counter'];
			endforeach;
			$user = trim( $ip[0] );
			$server = ( empty( $ip[1] ) ? '' : trim( $ip[1] ) );
			$temp[$date] = array( $user, $server, $name, $count, $gateway );
			if ( !isset( $ips[$user] ) ) :
				$ips[$user] = 1;
			else :
				$ips[$user]++;
			endif;
		endforeach;
		krsort( $temp );
		arsort( $ips );
		foreach ( $temp as $kt => $vt ) :
			$sheet['Logins'][] = array( date( 'r', $kt ), $vt[0], $vt[1], $vt[2], $vt[3], $vt[4] );
		endforeach;
		$count = 0;
		foreach ( $ips as $ki => $vi ) :
			if ( $count == 150 ) :
				sleep( 65 );
				$count = 0;
			endif;
			$look = file_get_contents( 'http://ip-api.com/json/'.$ki );
			$json = json_decode( $look, true );
			$count++;
			$sheet['IPs'][] = array( $ki, $vi, $json['country'], $json['regionName'], $json['city'], $json['isp'] );
		endforeach;

		foreach ( $sheet as $ks => $vs ) :
			$output .= '<h2>'.$ks.'</h2><table width="100%" border="1" cellspacing="0" cellpadding="2">';
			foreach ( $vs as $kvs => $vvs ) :
				if ( $kvs == 0 ) :
					$output .= '<thead><tr>';
					foreach ( $vvs as $vvvs ) :
						$output .= '<th>'.$vvvs.'</th>';
					endforeach;
					$output .= '</tr></thead><tbody>';
				else :
					$output .= '<tr>';
					foreach ( $vvs as $vvvs ) :
						$output .= '<td>'.$vvvs.'</td>';
					endforeach;
					$output .= '</tr>';
				endif;
			endforeach;
			$output .= '</tbody></table><p>&nbsp;</p>';
		endforeach;
		add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
		wp_mail( 'jcounts@houstonpublicmedia.org', 'Login Attempts Blocked', $output );
		update_option( 'limit_login_logged', array() );
		remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
	endif;
}

add_action( 'hpm_logins', 'hpm_logins_cleanup' );
$timestamp = wp_next_scheduled( 'hpm_logins' );
if ( empty( $timestamp ) ) :
	wp_schedule_event( time(), 'hpm_weekly', 'hpm_logins' );
endif;

add_action( 'admin_footer-post-new.php', 'hpm_https_check' );
add_action( 'admin_footer-post.php', 'hpm_https_check' );

function hpm_https_check() {
	if ( 'post' !== $GLOBALS['post_type'] ) :
		return;
	endif; ?>
	<script>
		jQuery(document).ready(function($){
			$('#publish, #save-post, #workflow_submit').on('click', function(e){
				var content = $('#content').val();
				if ( content.includes('src="http://') ) {
					e.preventDefault();
					alert( 'This post contains an embed or image from an insecure source.\nPlease check and see if that embed is available via HTTPS.\n\nTo check this:\n\n\t1.  Look for any <img> or <iframe> tags in your HTML\n\t2.  Find the src="" attribute and copy the URL\n\t3.  Change \'http:\' to \'https:\' and paste it into your browser\n\t4.  If it loads correctly, then great! Update the URL in your HTML\n\nIf you have any questions, email jcounts@houstonpublicmedia.org' );
					return false;
				} else {
					return true;
				}
			});
		});
	</script>
	<?php
}

if ( !function_exists('hpm_add_allowed_tags' ) ) {
	function hpm_add_allowed_tags( $tags ) {
		$tags['script'] = array(
			'src' => true,
		);
		return $tags;
	}
	add_filter( 'wp_kses_allowed_html', 'hpm_add_allowed_tags' );
}

if ( empty( wp_next_scheduled( 'oasiswf_auto_delete_history_schedule' ) ) ) :
	wp_schedule_event(time(), 'daily', 'oasiswf_auto_delete_history_schedule');
endif;

add_action( 'rest_api_init', 'custom_register_coauthors' );
function custom_register_coauthors() {
	register_rest_field( 'post',
		'coauthors',
		[
			'get_callback'    => 'custom_get_coauthors',
			'update_callback' => null,
			'schema'          => null,
		]
	);
}

function custom_get_coauthors( $object, $field_name, $request ) {
	$coauthors = get_coauthors($object['id']);

	$authors = [];
	foreach ( $coauthors as $author ) {
		$authors[] = [
			'display_name' => $author->display_name,
			'user_nicename' => $author->user_nicename
		];
	};
	return $authors;
}

function hpm_versions() {
	$transient = get_transient( 'hpm_versions' );
	if ( !empty( $transient ) ) :
		return $transient;
	else :
		$remote = wp_remote_get( esc_url_raw( "https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/version.json" ) );
		if ( is_wp_error( $remote ) ) :
			return false;
		else :
			$api = wp_remote_retrieve_body( $remote );
			$json = json_decode( $api, TRUE );
		endif;

		set_transient( 'hpm_versions', $json, 5 * 60 );
		return $json;
	endif;
}

function hpm_segments( $name, $date ) {
	$shows = [
		'Morning Edition' => [
			'source' => 'npr',
			'id' => 3
		],
		'1A' => [
			'source' => 'npr',
			'id' => 65
		],
		'Texas Standard' => [
			'source' => 'regex',
			'id' => 'http://www.texasstandard.org/stories/texas-standard-for-'
		],
		'Fresh Air' => [
			'source' => 'npr',
			'id' => 13
		],
		'Houston Matters' => [
			'source' => 'local'
		],
		'Think' => [
			'source' => 'wp',
			'id' => 'http://think.kera.org/wp-json/wp/v2/posts'
		],
		'Here and Now' => [
			'source' => 'npr',
			'id' => 60
		],
		'All Things Considered' => [
			'source' => 'npr',
			'id' => 2
		],
		'BBC World Service' => [
			'source' => 'regex',
			'id' => 'https://www.bbc.co.uk/schedules/p00fzl9p/'
		],
		'Weekend Edition Saturday' => [
			'source' => 'npr',
			'id' => 7
		],
		'Weekend Edition Sunday' => [
			'source' => 'npr',
			'id' => 10
		],
		'TED Radio Hour' => [
			'source' => 'npr',
			'id' => 57
		],
		'Ask Me Another' => [
			'source' => 'npr',
			'id' => 58
		],
		'Wait Wait... Don\'t Tell Me!' => [
			'source' => 'npr',
			'id' => 35
		],
		'Latino USA' => [
			'source' => 'npr',
			'id' => 22
		]
	];
	$output = '';
	$dx = explode( '-', $date );
	$du = mktime( 0,0,0, $dx[1], $dx[2], $dx[0] );
	$dt = date( 'Y-m-d', $du + DAY_IN_SECONDS );
	$trans = 'hpm_'.sanitize_title( $name ).'-'.$date;
	if ( empty( $shows[$name] ) ) :
		return $output;
	else :
		if ( $shows[$name]['source'] == 'npr' ) :
			$transient = get_transient( $trans );
			if ( !empty( $transient ) ) :
				return $transient;
			else :
				$url = "https://api.npr.org/query?id={$shows[$name]['id']}&fields=title&output=JSON&numResults=20&date={$date}&apiKey=MDAyMTgwNzc5MDEyMjQ4ODE4MjMyYTExMA001";
				$remote = wp_remote_get( esc_url_raw( $url ) );
				if ( is_wp_error( $remote ) ) :
					return $output;
				else :
					$api = wp_remote_retrieve_body( $remote );
					$json = json_decode( $api, TRUE );
					if ( !empty( $json['list']['story'] ) ) :
						$output .= "<div class=\"progsegment\"><h4>{$name} Segments for {$date}</h4><ul>";
						foreach ( $json['list']['story'] as $j ) :
							foreach ( $j['link'] as $jl ) :
								if ( $jl['type'] == 'html' ) :
									$link = $jl['$text'];
								endif;
							endforeach;
							$output .= '<li><a href="'.$link.'" target="_blank">'.$j['title']['$text'].'</a></li>';
						endforeach;
						$output .= "</ul></div>";
					endif;
				endif;
				set_transient( $trans, $output, HOUR_IN_SECONDS );
			endif;
		elseif ( $shows[$name]['source'] == 'regex' ) :
			if ( $name == 'BBC World Service' ) :
				$offset = str_replace( '-', '', get_option( 'gmt_offset' ) );
				$output .= "<div class=\"progsegment\"><ul><li><a href=\"{$shows[$name]['id']}{$dx[0]}/{$dx[1]}/{$dx[2]}?utcoffset=-0{$offset}:00\" target=\"_blank\">BBC Schedule for {$date}</a></li></ul></div>";
			elseif ( $name == 'Texas Standard' ) :
				$dstr = date( 'F-j-Y', $du );
				$dstrdisp = date( 'F j, Y', $du );
				$output .= "<div class=\"progsegment\"><ul><li><a href=\"{$shows[$name]['id']}".strtolower( $dstr )."/\" target=\"_blank\">Texas Standard for ".$dstrdisp."</a></li></ul></div>";
			else :
				return $output;
			endif;
		elseif ( $shows[$name]['source'] == 'wp' ) :
			$transient = get_transient( $trans );
			if ( !empty( $transient ) ) :
				return $transient;
			else :
				$url = $shows[$name]['id']."?before=".$dt."T00:00:00&after=".$date."T00:00:00";
				$remote = wp_remote_get( esc_url_raw( $url ) );
				if ( is_wp_error( $remote ) ) :
					return $output;
				else :
					$api = wp_remote_retrieve_body( $remote );
					$json = json_decode( $api );
					if ( !empty( $json ) ) :
						$output .= "<div class=\"progsegment\"><h4>{$name} Segments for {$date}</h4><ul>";
						foreach ( $json as $j ) :
							$output .= '<li><a href="'.$j->link.'" target="_blank">'.$j->title->rendered.'</a></li>';
						endforeach;
						$output .= "</ul></div>";
					endif;
				endif;
				set_transient( $trans, $output, HOUR_IN_SECONDS );
			endif;
		elseif ( $shows[$name]['source'] == 'local' ) :
			if ( $name == 'Houston Matters' ) :
				$hm = new WP_Query( [
					'year' => $dx[0],
					'monthnum' => $dx[1],
					'day' => $dx[2],
					'cat' => 58,
					'post_type' => 'post',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1
				] );
				if ( $hm->have_posts() ) :
					$output .= "<div class=\"progsegment\"><h4>{$name} Segments for {$date}</h4><ul>";
					while( $hm->have_posts() ) :
						$hm->the_post();
						$output .= '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
					endwhile;
					$output .= '</ul></div>';
				endif;
				wp_reset_query();
			else :
				return $output;
			endif;
		else :
			return $output;
		endif;
	endif;
	return $output;
}

add_filter( 'xmlrpc_enabled', '__return_false' );

//function hpm_npr_stories_update() {
//	$nprs = new WP_Query([
//		'post_type' => 'post',
//		'post_status' => 'publish',
//		'posts_per_page' => -1,
//		'meta_query' => [[
//			'key' => 'npr_retrieved_story',
//			'value' => 1
//		]],
//		'date_query' => [[
//			'after' => '3 days ago',
//			'inclusive' => true
//		]]
//	]);
//
//	if ( $nprs->have_posts() ) :
//		foreach ( $nprs->posts as $npr ) :
//			$api_id = get_post_meta( $npr->ID, NPR_STORY_ID_META_KEY, TRUE );
//			$api = new NPRAPIWordpress();
//			$params = [ 'id' => $api_id, 'apiKey' => get_option( 'ds_npr_api_key' ) ];
//			$api->request( $params, 'query', get_option( 'ds_npr_api_pull_url' ) );
//			$api->parse();
//			if ( empty( $api->message ) || $api->message->level != 'warning' ) :
//				nprstory_error_log( 'updating story for API ID='.$api_id );
//				$story = $api->update_posts_from_stories( true );
//			endif;
//		endforeach;
//	endif;
//	wp_reset_query();
//	return true;
//}
//
//add_action( 'hpm_npr_stories', 'hpm_npr_stories_update' );
//$timestamp = wp_next_scheduled( 'hpm_npr_stories' );
//if ( empty( $timestamp ) ) :
//	wp_schedule_event( time(), 'hpm_2hours', 'hpm_npr_stories' );
//endif;
wp_clear_scheduled_hook('hpm_npr_stories');