<?PHP
/**
 * Functions or modifications related to plugins or things that aren't directly theme-related
 */
require SITE_ROOT . '/vendor/autoload.php';
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\Filter;
/**
 * Adds ability for anyone who can edit others' posts to be able to create and manage guest authors
 */
add_filter( 'coauthors_guest_author_manage_cap', 'capx_filter_guest_author_manage_cap' );
function capx_filter_guest_author_manage_cap( $cap ): string {
	return 'edit_others_posts';
}

/**
 * Anyone who can publish on the site can publish to Apple News
 */
add_filter( 'apple_news_publish_capability', 'publish_to_apple_news_cap' );
function publish_to_apple_news_cap( $cap ): string {
	return 'publish_posts';
}

add_action( 'publish_post', 'hpm_apple_news_exclude', 10, 2 );
add_action( 'save_post', 'hpm_apple_news_exclude', 10, 2 );
add_action( 'owf_update_published_post', 'hpm_apple_news_exclude', 10, 2 );

function hpm_apple_news_exclude( $post_id, $post ): void {
	$cats = get_the_category( $post_id );
	foreach ( $cats as $c ) {
		if ( $c->term_id == 27876 ) {
			apply_filters( 'apple_news_skip_push', true, $post_id );
		}
	}
}

function hpm_versions() {
	$transient = get_transient( 'hpm_versions' );
	if ( !empty( $transient ) ) {
		return $transient;
	} else {
		$remote = wp_remote_get( esc_url_raw( "https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/version.json" ) );
		if ( is_wp_error( $remote ) ) {
			return false;
		} else {
			$api = wp_remote_retrieve_body( $remote );
			$json = json_decode( $api, TRUE );
		}

		set_transient( 'hpm_versions', $json, 3 * 60 );
		return $json;
	}
}

/*
 * Removes unnecessary metadata from the document head
 */
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Disable support for WordPress Emojicons, because we will never use them and don't need the extra overhead
 */
function disable_wp_emojicons(): void {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

function disable_emojicons_tinymce( $plugins ): array {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, [ 'wpemoji' ] );
	} else {
		return [];
	}
}
add_action( 'init', 'disable_wp_emojicons' );

/*
 * Adding variables to the WordPress query setup for special sections and external data pulls
 */
function add_query_vars( $aVars ): array {
	$aVars[] = "sched_station";
	$aVars[] = "sched_year";
	$aVars[] = "sched_month";
	$aVars[] = "sched_day";
	$aVars[] = "npr_id";
	return $aVars;
}
add_filter( 'query_vars', 'add_query_vars' );

/*
 * Creating new rewrite rules to feed those special sections and external data pulls
 */
function add_rewrite_rules( $aRules ): array {
	$aNewRules = [
		'^(news887|classical)/schedule/([0-9]{4})/([0-9]{2})/([0-9]{2})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_year=$matches[2]&sched_month=$matches[3]&sched_day=$matches[4]',
		'^(news887|classical)/schedule/([0-9]{4})/([0-9]{2})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_year=$matches[2]&sched_month=$matches[3]&sched_day=01',
		'^(news887|classical)/schedule/([0-9]{4})/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]&sched_year=$matches[2]&sched_month=01&sched_day=01',
		'^(news887|classical)/schedule/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]',
		'^(news887|classical)/?$' => 'index.php?pagename=$matches[1]&sched_station=$matches[1]',
		'^npr/([0-9]{4})/([0-9]{2})/([0-9]{2})/([a-z\-0-9]+)/([a-z0-9\-]+)/?' => 'index.php?pagename=npr-articles&npr_id=$matches[4]'
	];
	return $aNewRules + $aRules;
}
add_filter( 'rewrite_rules_array', 'add_rewrite_rules' );

/**
 *  Add new options for Cron Schedules
 */

add_filter( 'cron_schedules', 'hpm_cron_updates', 10, 2 );

function hpm_cron_updates( $schedules ) {
	$schedules['hpm_1min'] = [
		'interval' => 60,
		'display' => __( 'Every Minute' )
	];
	$schedules['hpm_2min'] = [
		'interval' => 120,
		'display' => __( 'Every Other Minute' )
	];
	$schedules['hpm_15min'] = [
			'interval' => 900,
			'display' => __( 'Every 15 Minutes' )
	];
	$schedules['hpm_30min'] = [
			'interval' => 1800,
			'display' => __( 'Every 30 Minutes' )
	];
	$schedules['hpm_2hours'] = [
		'interval' => 7200,
		'display' => __( 'Every Two Hours' )
	];
	$schedules['hpm_weekly'] = [
		'interval' => 604800,
		'display' => __( 'Every Week' )
	];
	return $schedules;
}

/*
 * Save local copies of today's schedule JSON from NPR Composer2 into site transients
 */
function hpm_schedules( $station, $date ) {
	if ( empty( $station ) || empty( $date ) ) {
		return false;
	}
	$api = get_transient( 'hpm_' . $station . '_' . $date );
	if ( !empty( $api ) ) {
		return $api;
	}
	$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/" . $station . "/day?date=" . $date . "&format=json" ) );
	if ( is_wp_error( $remote ) ) {
		return false;
	} else {
		$api = wp_remote_retrieve_body( $remote );
		$json = json_decode( $api, TRUE );
	}
	$c = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$c = $c + $offset;
	$now = getdate( $c );
	$old = $now[0] - 86400;
	$new = $now[0] + 432000;
	$date_exp = explode( '-', $date );
	$dateunix = mktime( 0, 0, 0, $date_exp[1], $date_exp[2], $date_exp[0] );
	if ( $dateunix > $old && $dateunix < $new ) {
		set_transient( 'hpm_' . $station . '_' . $date, $json, 300 );
	}
	return $json;
}

/*
 * Log errors in wp-content/debug.log when debugging is enabled.
 */
if ( !function_exists( 'log_it' ) ) {
	function log_it( $message ): void {
		error_log( print_r( $message, true ) );
	}
}

/*
 * Add checkbox to post editor in order to hide last modified time in the post display (single.php)
 */
add_action( 'post_submitbox_misc_actions', 'hpm_no_mod_time' );
function hpm_no_mod_time(): bool {
	global $post;
	if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) {
		return false;
	}
	if ( $post->post_type == 'post' ) {
		$value = get_post_meta( $post->ID, 'hpm_no_mod_time', true );
		$checked = ( !empty( $value ) ? ' checked="checked" ' : '' );
		echo '<div class="misc-pub-section misc-pub-section-last"><input type="checkbox"' . $checked . ' value="1" name="hpm_no_mod_time" /><label for="hpm_no_mod_time">Hide Last Modified Time?</label></div>';
	}
	return true;
}

add_action( 'save_post', 'save_hpm_no_mod_time' );
function save_hpm_no_mod_time(): bool {
	global $post;
	if ( empty( $post ) || $post->post_type != 'post' ) {
		return false;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}
	if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) {
		return false;
	}
	if ( empty( $post->ID ) ) {
		return false;
	}
	$value = ( !empty( $_POST['hpm_no_mod_time'] )  ? 1 : 0 );

	update_post_meta( $post->ID, 'hpm_no_mod_time', $value );
	return true;
}

/*
 *  If post is in "Houston" or "Harris County" category, add "Local" category
 */
add_action( 'save_post', 'hpm_local_cat_check' );
function hpm_local_cat_check(): bool {
	global $post;
	if ( empty( $post ) || $post->post_type != 'post' ) {
		return false;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}
	if ( empty( $post->ID ) ) {
		return false;
	}
	$cat = wp_get_post_categories( $post->ID );
	if ( ( in_array( 36052, $cat ) || in_array( 32567, $cat ) ) && !in_array( 2113, $cat ) ) {
		$cat[] = 2113;
		wp_set_object_terms( $post->ID, $cat, 'category' );
	}
	return true;
}

/*
 * Disallow certain MIME types from being accepted by the media uploader
 */
function custom_upload_mimes ( $existing_mimes = [] ): array {
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
add_filter( 'upload_mimes', 'custom_upload_mimes' );

/*
 * Finds the last 5 entries in the specified YouTube playlist and saves into a site transient
 */
function hpm_youtube_playlist( $key, $num = 5 ): array {
	$list = get_transient( 'hpm_yt_' . $key . '_' . $num );
	if ( !empty( $list ) ) {
		return $list;
	}
	$remote = wp_remote_get( esc_url_raw( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId=' . $key . '&key=' . HPM_YT_API_KEY ) );
	if ( is_wp_error( $remote ) || $remote['response']['code'] !== 200 ) {
		return [];
	} else {
		$yt = wp_remote_retrieve_body( $remote );
		$json = json_decode( $yt, TRUE );
	}
	$totalResults = $json['pageInfo']['totalResults'];
	$resultsPerPage = $json['pageInfo']['resultsPerPage'];
	$times = [ strtotime( $json['items'][0]['snippet']['publishedAt'] ), strtotime( $json['items'][1]['snippet']['publishedAt'] ), strtotime( $json['items'][2]['snippet']['publishedAt'] ) ];
	if ( $times[0] > $times[1] && $times[1] > $times[2] ) {
		$new2old = TRUE;
	} elseif ( $times[2] > $times[1] && $times[1] > $times[0] ) {
		$new2old = FALSE;
	} else {
		$new2old = TRUE;
	}
	if ( $new2old ) {
		$items = $json['items'];
	} else {
		if ( $totalResults > $resultsPerPage ) {
			$pages = floor( $totalResults / $resultsPerPage );
			for ( $i = 0; $i < $pages; $i++ ) {
				if ( !empty( $json['nextPageToken'] ) ) {
					$remote = wp_remote_get( esc_url_raw( 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId=' . $key . '&pageToken=' . $json['nextPageToken'] . '&key=' . HPM_YT_API_KEY ) );
					if ( is_wp_error( $remote ) || $remote['response']['code'] !== 200 ) {
						return [];
					} else {
						$yt = wp_remote_retrieve_body( $remote );
						$json = json_decode( $yt, TRUE );
					}
				}
			}
		}
		$items = array_reverse( $json['items'] );
	}
	$json_r = array_slice( $items, 0, $num );
	set_transient( 'hpm_yt_' . $key . '_' . $num, $json_r, 600 );
	return $json_r;
}

function hpm_youtube_playlist_rss( $key, $num = 0 ): array {
	$list = get_transient( 'hpm_yt_' . $key );
	if ( !empty( $list ) ) {
		return $list;
	}
	$remote = wp_remote_get( esc_url_raw( 'https://www.youtube.com/feeds/videos.xml?playlist_id=' . $key ) );
	if ( is_wp_error( $remote ) || $remote['response']['code'] !== 200 ) {
		return [];
	} else {
		$yt = wp_remote_retrieve_body( $remote );
		$dom = simplexml_load_string( $yt );
		$json = json_decode( json_encode( $dom ), true );
	}
	$items = $json['entry'];
	if ( $num > 0 ) {
		$items = array_slice( $items, 0, $num );
	}
	set_transient( 'hpm_yt_' . $key, $items, 600 );
	return $items;
}

/*
 * Ping Facebook's OpenGraph servers whenever a post is published, in order to prime their cache
 */
function hpm_facebook_ping( $arg1 ): bool {
	$perma = get_permalink( $arg1 );
	$url = 'https://graph.facebook.com';
	$data = [ 'id' => $perma, 'scrape' => 'true' ];
	$options = [
		'headers' => [
			"Content-type" => "application/x-www-form-urlencoded"
		],
		'body' => $data
	];
	$remote = wp_remote_get( esc_url_raw( $url ), $options );
	if ( is_wp_error( $remote ) ) {
		return false;
	}
	return true;
}
function hpm_facebook_ping_schedule( $post_id, $post ): void {
	if ( WP_ENV == 'production' ) {
		wp_schedule_single_event( time() + 60, 'hpm_facebook_ping', [ $post_id ] );
	}
}

add_action( 'publish_post', 'hpm_facebook_ping_schedule', 10, 2 );

add_action( 'owf_update_published_post', 'update_post_meta_info', 10, 2 );

/**
 * @param $original_post_id
 * @param $revised_post
 *
 * Copy over any metadata from an article revision to its original
 */
function update_post_meta_info( $original_post_id, $revised_post ): void {
	$post_meta_keys = get_post_custom_keys( $revised_post->ID );
	if ( empty( $post_meta_keys ) ) {
		return;
	}

	foreach ( $post_meta_keys as $meta_key ) {
		$meta_key_trim = trim( $meta_key );
		if ( '_' == $meta_key_trim[0] || str_contains( $meta_key_trim, 'oasis' ) ) {
			continue;
		}
		$revised_meta_values = get_post_custom_values( $meta_key, $revised_post->ID );
		$original_meta_values = get_post_custom_values( $meta_key, $original_post_id );

		// find the bigger array of the two
		$meta_values_count = count( $revised_meta_values ) > count( $original_meta_values ) ? count( $revised_meta_values ) : count( $original_meta_values );

		// loop through the meta values to find what's added, modified and deleted.
		for ( $i = 0; $i < $meta_values_count; $i++) {
			$new_meta_value = "";
			// delete if the revised post doesn't have that key
			if ( count( $revised_meta_values ) >= $i + 1 ) {
				$new_meta_value = maybe_unserialize( $revised_meta_values[$i] );
			} else {
				$old_meta_value = maybe_unserialize( $original_meta_values[$i] );
				delete_post_meta( $original_post_id, $meta_key, $old_meta_value );
				continue;
			}

			// old meta values got updated, so simply update it
			if ( count( $original_meta_values ) >= $i + 1 ) {
				$old_meta_value = maybe_unserialize( $original_meta_values[$i] );
				update_post_meta( $original_post_id, $meta_key, $new_meta_value, $old_meta_value );
			}

			// new meta values got added, so add it
			if ( count( $original_meta_values ) < $i + 1 ) {
				add_post_meta( $original_post_id, $meta_key, $new_meta_value );
			}
		}
	}
}

/**
 * Cron task to pull top 5 most-viewed stories from the last 3 days
 * @throws \Google\Exception
 * @throws \Google\ApiCore\ValidationException
 * @throws \Google\ApiCore\ApiException
 */
function analyticsPull_update(): void {
	$analytics = new BetaAnalyticsDataClient([
		'credentials' => SITE_ROOT . '/../client_secrets.json'
	]);
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$then = $now[0] - 172800;
	$match = [];
	$result = $analytics->runReport([
		'property' => 'properties/253228385',
		'dateRanges' => [
			new DateRange([
				'start_date' => date( "Y-m-d", $then ),
				'end_date' => date( "Y-m-d", $now[0] ),
			]),
		],
		'dimensions' => [
			new Dimension([ 'name' => 'pagePath' ])
		],
		'metrics' => [
			new Metric([ 'name' => 'screenPageViews' ])
		],
		'dimensionFilter' => new FilterExpression([
			'filter' => new Filter([
				'field_name' => 'pagePath',
				'string_filter' => new Filter\StringFilter([
					'match_type' => Filter\StringFilter\MatchType::BEGINS_WITH,
					'value' => '/articles/'
				])
			])
		]),
		'limit' => 5
	]);
	$output = '<ul class="list-none news-links list-dashed">';
	foreach ( $result->getRows() as $row ) {
		preg_match( '/\/articles\/[a-z0-9\-\/]+\/[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/([0-9]+)\/(.+)/', $row->getDimensionValues()[0]->getValue(), $match );
		if ( !empty( $match ) ) {
			$imageBlock = '';
			$title = get_the_title( $match[1] );
			if ( empty( $title ) ) {
				$title = ucwords( str_replace( [ '-', '/' ], [ ' ', '' ] , $match[2] ) );
			}
			if ( has_post_thumbnail( $match[1] ) ) {
				$imageBlock = get_the_post_thumbnail( $match[1], 'thumbnail' );
			}

			$output .= '<li><a href="' . $row->getDimensionValues()[0]->getValue() . '" rel="bookmark"><span>' . $title . '</span></a></li>';
		}
	}
	$output .= "</ul>";
	update_option( 'hpm_most_popular', $output );
}

function analyticsPull() {
	return get_option( 'hpm_most_popular' );
}

add_action( 'hpm_analytics', 'analyticsPull_update' );
$timestamp = wp_next_scheduled( 'hpm_analytics' );
if ( empty( $timestamp ) ) {
	wp_schedule_event( time(), 'hourly', 'hpm_analytics' );
}

function get_post_id_by_slug( $slug ) {
	$post = get_page_by_path( $slug, null, 'post' );
	return $post?->ID;
}

/**
 * @return mixed|string
 * Pull NPR API articles and save them to a transient
 */
function hpm_nprapi_output( $api_id = 1001, $num = 4 ): mixed {
	$npr = get_transient( 'hpm_nprapi_' . $api_id );
	if ( !empty( $npr ) ) {
		return $npr;
	}
	$output = '<ul class="list-none news-links link-thumb">';
	$npr = new NPR_CDS_WP();
	$npr->request([
		'collectionIds' => $api_id,
		'profileIds' => [ 'story', 'publishable', 'renderable', 'buildout' ],
		'limit' => $num,
		'sort' => 'publishDateTime:desc',
		'ownerHrefs' => 'https://organization.api.npr.org/v4/services/s1'
	]);
	$npr->parse();
	if ( !empty( $npr->stories ) ) {
		foreach ( $npr->stories as $story ) {
			$image_url = '';
			$npr_date = strtotime( $story->publishDateTime );
			if ( !empty( $story->images[0] ) ) {
				$image_id = $npr->extract_asset_id( $story->images[0]->href );
				$image_asset = $story->assets->{$image_id};
				foreach ( $image_asset->enclosures as $enclosure ) {
					if ( in_array( 'primary', $enclosure->rels ) ) {
						$image_url = $npr->get_image_url( $enclosure );
					}
				}
			}
			$output .= '<li><a href="/npr/' . date( 'Y/m/d/', $npr_date ) . $story->id . '/' . sanitize_title( $story->title ) . '/" rel="bookmark"><span>' . $story->title . '</span><span class="img-w75">' . ( !empty( $image_url['url'] ) ? '<img src="' . $image_url['url'] . '" alt="' .
				( !empty( $story->teaser ) ? strip_tags( $story->teaser ) : $story->title ) .
				'" loading="lazy" />' : '' ) .'</span></a></li>';
		}
	}
	$output .= "</ul>";
	set_transient( 'hpm_nprapi_' . $api_id, $output, 300 );
	return $output;
}

/*NPR News Function Testing starts here*/

function hpmnpr_nprapi_output( $api_id = 1001, $num = 50, $per_page = 10 ): mixed {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $npr = get_transient( 'hpmnpr_nprapi_' . $api_id );
    if ( !empty( $npr ) ) {
        return $npr;
    }
    $npr = new NPR_CDS_WP();
    $npr->request([
        'collectionIds' => $api_id,
        'profileIds' => [ 'story', 'publishable', 'renderable', 'buildout' ],
        'limit' => $num,
        'sort' => 'publishDateTime:desc',
        'ownerHrefs' => 'https://organization.api.npr.org/v4/services/s1'
    ]);
    $npr->parse();
    $nprStories = $npr->stories;
    $total_items = count( $nprStories );
    $total_pages = ceil( $total_items / $per_page );
    $offset = ( $paged - 1 ) * $per_page;
    $paged_stories = array_slice( $nprStories, $offset, $per_page );
    $output = '<section id="search-results">';

    if ( !empty( $paged_stories ) ) {
        $npr = new NPR_CDS_WP(); // Needed for helper methods
        foreach ( $paged_stories as $story ) {
            $image_url = '';
            $npr_date = strtotime( $story->publishDateTime );

            if ( !empty( $story->images[0] ) ) {
                $image_id = $npr->extract_asset_id( $story->images[0]->href );
                $image_asset = $story->assets->{$image_id};
                foreach ( $image_asset->enclosures as $enclosure ) {
                    if ( in_array( 'primary', $enclosure->rels ) ) {
                        $image_url = $npr->get_image_url( $enclosure );
                    }
                }
            }
            $output .='<article>' .
                ( !empty( $image_url['url'] ) ? '<img class="post-thumbnail" src="' . $image_url['url'] . '" alt="' .
                    ( !empty( $story->teaser ) ? strip_tags( $story->teaser ) : $story->title ) .
                    '" loading="lazy" />' : '' ) . '<div class="card-content"><header class="entry-header"><h2 class="entry-title"><a href="/npr/' . date( 'Y/m/d/', $npr_date ) . $story->id . '/' . sanitize_title( $story->title ) . '/" rel="bookmark"><span>' . $story->title . '</span></a></h2></header><div class="entry-summary"><p>' . $story->teaser . '</p></div></div></article>';

        }
    } else {
        $output .= '<li>No stories available.</li>';
    }
    if ( $total_pages > 1 ) {
        $pagination = paginate_links([
            'base'      => get_pagenum_link(1) . '%_%',
            'format'    => 'page/%#%/',
            'current'   => $paged,
            'total'     => $total_pages,
            'prev_text' => '« Prev',
            'next_text' => 'Next »',
            'type'      => 'list',
        ]);

        if ( $pagination ) {
            $output .= '<div><div class="wp-pagenavi">' . $pagination . '</div></div>';
        }
        $output .= '</section>';
    }
    return $output;
}

/*NPR News Function testing starts here*/



/**
 * Hide the Comments menu in Admin because we don't use it
 */
function remove_menus(): void {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );

function hpm_election_night(): string {
	$output = '';
	$args = [
		'p' => 248126,
		'post_type'  => 'page',
		'post_status' => 'publish'
	];
	$election = new WP_Query( $args );
	if ( !empty( $election->post->post_content ) ) {
		$output = $election->post->post_content;
	}
	return $output;
}
add_shortcode( 'election_night', 'hpm_election_night' );

function wpdocs_set_html_mail_content_type(): string {
	return 'text/html';
}

add_action( 'admin_footer-post-new.php', 'hpm_https_check' );
add_action( 'admin_footer-post.php', 'hpm_https_check' );
add_action( 'admin_footer-post-new.php', 'hpm_npr_api_contributor' );
add_action( 'admin_footer-post.php', 'hpm_npr_api_contributor' );

function hpm_https_check(): void {
	if ( 'post' !== $GLOBALS['post_type'] ) {
		return;
	}
	if ( !current_user_can( 'publish_posts' ) ) {
		return;
	}
	global $post; ?>
	<style>
		#hpm-check {
			max-width: 1000px;
		}
		#hpm-check[open]::backdrop {
			background-color: rgba(0, 0, 0, 0.5);
			-webkit-backdrop-filter: blur(5px);
			backdrop-filter: blur(5px);
		}
		#hpm-check img {
			width: 70%;
			margin: 0 15%;
		}
		#hpm-check a:has(img) {
			display: block;
		}
		#hpm-check form {
			text-align:  right;
		}
		#hpm-check form button {
			background-color: #135e96;
			color: white;
			padding: 0.5rem;
			font-size: 125%;
		}
	</style>
	<dialog id="hpm-check">
		<div id="hpm-check-content"></div>
		<form method="dialog">
			<button>Dismiss</button>
		</form>
	</dialog>
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			document.querySelector('#postimagediv .inside').innerHTML += '<p class="hide-if-no-js"><a href="/wp/wp-admin/edit.php?page=hpm-image-preview&p=<?php echo $post->ID; ?>" id="hpm-image-preview" style="color: white; font-weight: bolder; background-color: #0085ba; padding: 5px; text-decoration: none;">Preview featured image</a></p>';
			document.querySelector('#hpm-image-preview').addEventListener('click', (e) => {
				e.preventDefault();
				let href = e.target.getAttribute('href');
				window.open(href, 'HPM Featured Image Preview', "width=850,height=800");
			});
			let pButtons = document.querySelectorAll("#publish, #save-post, #workflow_submit");
			Array.from(pButtons).forEach((pB) => {
				pB.addEventListener('click', (e) => {
					let content = wp.editor.getContent('content');
					let dialog = document.querySelector('#hpm-check');
					let dialogContent = document.querySelector('#hpm-check-content');
					let postTitle = document.querySelector('#title');
					let altTitle = document.querySelector('#hpm-alt-headline');
					let seoTitle = document.querySelector('#hpm-seo-headline');
					postTitle.classList.remove('hpm-editor-error');
					altTitle.classList.remove('hpm-editor-error');
					seoTitle.classList.remove('hpm-editor-error');
					if ( content.includes('src="http://') ) {
						e.preventDefault();
						dialogContent.innerHTML = '<h2>Using Insecure Embeds</h2><p>This post contains an embed or image from an insecure source. Please check and see if that embed is available via HTTPS.</p><p>To check this:</p><ol><li>Look for any &lt;img&gt; or &lt;iframe&gt; tags in your HTML</li><li>>Find the src="" attribute and copy the URL</li><li>Change \'http:\' to \'https:\' and paste it into your browser</li></ol><p>If it loads correctly, then great! Update the URL in your HTML</p><p>If you have any questions, email <a href="mailto:jcounts@houstonpublicmedia.org?subject=Question%20About%20HTTPS%20in%2-WordPress">jcounts@houstonpublicmedia.org</a><p>';
						dialog.showModal();
						return false;
					} else if ( content.includes('alt=""') ) {
						e.preventDefault();
						dialogContent.innerHTML = '<h2>Image Alt Text Needed</h2><p>This post contains images with <strong>empty alt text tags</strong>. Alt text (or alternative text) is what displays in the event an image doesn\'t load, or is read by a screen reader, and <strong>typically describes the content of the image</strong>. Leaving these blank can <strong>cause problems for both accessibility and search engine optimization</strong>.</p><h3>Steps to Fix</h3><p>In the Visual editor mode, click on the image and click the pencil icon:<br /><a href="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-visual-image-edit.png.webp" target="_blank"><img src="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-visual-image-edit.png.webp" alt="Clicking on an image in the editor reveals alignment tools as well as an edit button" /></a></p><p>In the popup, fill in the box at the top marked "Alternative Text":<br /><a href="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-visual-image-data.png.webp" target="_blank"><img src="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-visual-image-data.png.webp" alt="In the modal popup, fill in the top box marked Alternative Text" /></a></p><p>In the Text mode, look for the alt attribute in any <code>&lt;img&gt;</code> tags and enter your text there:<br /><a href="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-html.png.webp" target="_blank"><img src="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-html.png.webp" alt="Look for any occurrences of alt that do not have any text in between the quotes" /></a></p><p>You can also enter the alt text in the Media Library popup when uploading the image:<br /><a href="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-media-library.png.webp" target="_blank"><img src="https://cdn.houstonpublicmedia.org/assets/images/wp-alt-text-media-library.png.webp" alt="The Media Library also contains a field for alt text" /></a></p>';
						dialog.showModal();
						return false;
					} else if ( postTitle.value.length > 110 || altTitle.value.length > 110 || seoTitle.value.length > 110 ) {
						e.preventDefault();
						let tooLongOutput = '<h2>One (or more) of your headlines is too long</h2><p>See below. Please rewrite it to be 100 characters or fewer.</p><ul class="ul-disc">';
						if ( postTitle.value.length > 110 ) {
							tooLongOutput += '<li>Main Headline <strong>(' + postTitle.value.length + ' characters)</strong></li>';
							postTitle.classList.add('hpm-editor-error');
						}
						if ( altTitle.value.length > 110 ) {
							tooLongOutput += '<li>Alternate/Homepage Headline <strong>(' + altTitle.value.length + ' characters)</strong></li>';
							altTitle.classList.add('hpm-editor-error');
						}
						if ( seoTitle.value.length > 110 ) {
							tooLongOutput += '<li>SEO Headline <strong>(' + seoTitle.value.length + ' characters)</strong></li>';
							seoTitle.classList.add('hpm-editor-error');
						}
						tooLongOutput += '</ul>';
						dialogContent.innerHTML = tooLongOutput;
						dialog.showModal();
						return false;
					} else {
						return true;
					}
				});
			});
		});
	</script>
	<style>
		.hpm-editor-error {
			border: 2px solid red !important;
		}
	</style>
<?php
}

function hpm_npr_api_contributor(): void {
	if ( 'post' !== $GLOBALS['post_type'] ) {
		return;
	}
	$user = wp_get_current_user();
	if ( !in_array( 'contributor', $user->roles ) ) {
		return;
	} ?>
	<script>
		jQuery(document).ready(function($){
			$('#send_to_api').prop('checked', false);
		});
	</script>
<?php
}

if ( !function_exists( 'hpm_add_allowed_tags' ) ) {
	function hpm_add_allowed_tags( $tags ) {
		$tags['script'] = [
			'src' => true,
		];
		$tags['iframe'] = [
			'src' => true,
		];
		return $tags;
	}
	add_filter( 'wp_kses_allowed_html', 'hpm_add_allowed_tags' );
}

if ( empty( wp_next_scheduled( 'oasiswf_auto_delete_history_schedule' ) ) ) {
	wp_schedule_event( time(), 'daily', 'oasiswf_auto_delete_history_schedule' );
}

add_action( 'rest_api_init', 'custom_register_coauthors' );
function custom_register_coauthors(): void {
	register_rest_field( 'post',
		'coauthors',
		[
			'get_callback' => 'custom_get_coauthors',
			'update_callback' => null,
			'schema' => null,
		]
	);
}

function custom_get_coauthors( $object, $field_name, $request ): array {
	$coauthors = get_coauthors( $object['id'] );
	$authors = [];
	foreach ( $coauthors as $coa ) {
		$author_meta = [
			'biography' => '',
			'image' => '',
			'metadata' => []
		];
		$guest = true;
		if ( is_a( $coa, 'wp_user' ) ) {
			$guest = false;
		} elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) {
			if ( !empty( $coa->linked_account ) ) {
				$authid = get_user_by( 'login', $coa->linked_account );
				if ( is_a( $authid, 'wp_user' ) ) {
					$guest = false;
					$staff = new WP_Query([
						'post_type' => 'staff',
						'post_status' => 'publish',
						'posts_per_page' => 1,
						'meta_query' => [[
							'key' => 'hpm_staff_authid',
							'compare' => '=',
							'value' => $authid->ID
						]]
					]);
					if ( $staff->have_posts() ) {
						$staff->the_post();
						$author_meta['biography'] = do_shortcode( get_the_content() );
						$author_meta['metadata'] = get_post_meta( get_the_ID(), 'hpm_staff_meta', true );
						if ( has_post_thumbnail( get_the_ID() ) ) {
							$attach = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
							if ( $attach !== false ) {
								$author_meta['image'] = $attach;
							}
						}
					}
				}
			}
		}
		$authors[] = [
			'display_name' => $coa->display_name,
			'user_nicename' => $coa->user_nicename,
			'guest_author' => $guest,
			'extra' => $author_meta
		];
	}
	return $authors;
}

add_action( 'rest_api_init', 'custom_register_featured_media' );
function custom_register_featured_media(): void {
	register_rest_field( 'post',
		'featured_media_url',
		[
			'get_callback' => 'custom_get_featured_media',
			'update_callback' => null,
			'schema' => null,
		]
	);
}

function custom_get_featured_media( $object, $field_name, $request ): string {
	$media = get_the_post_thumbnail_url( $object['id'], 'medium' );
	if ( $media === false ) {
		return '';
	} else {
		return $media . '.webp';
	}
}

add_action( 'rest_api_init', 'custom_register_primary_category' );
function custom_register_primary_category(): void {
	register_rest_field( 'post',
		'primary_category',
		[
			'get_callback' => 'custom_get_primary_category',
			'update_callback' => null,
			'schema' => null,
		]
	);
}

function custom_get_primary_category( $object, $field_name, $request ) {
	$epc = get_post_meta( $object['id'], 'epc_primary_category', true );
	if ( $epc != false ) {
		$cat = get_category( $epc );
		if ( !empty( $cat ) ) {
			return [
				'name' => $cat->name,
				'slug' => $cat->slug,
				'id' => $cat->term_id,
				'taxonomy' => $cat->taxonomy,
				'parent' => $cat->parent
			];
		}
	}
	return [];
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
			'source' => 'wp-rss',
			'id' => 'https://www.texasstandard.org/'
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
			'id' => 'https://think.kera.org/wp-json/wp/v2/posts'
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
	$trans = 'hpm_' . sanitize_title( $name ) . '-' . $date;
	if ( empty( $shows[ $name ] ) ) {
		return $output;
	} else {
		if ( $shows[ $name ]['source'] == 'npr' ) {
			$transient = get_transient( $trans );
			if ( !empty( $transient ) ) {
				return $transient;
			}
			$npr = new NPR_CDS_WP();
			$npr->request([
				'collectionIds' => $shows[ $name ]['id'],
				'profileIds' => 'story,renderable,buildout',
				'limit' => 30,
				'sort' => 'publishDateTime:desc',
				'publishDateTime' => $date
			]);
			$npr->parse();
			if ( empty( $npr->stories[0] ) ) {
				return $output;
			}

			$output .= "<details class=\"progsegment\"><summary>Segments for {$date}</summary><ul>";
			foreach ( $npr->stories as $j ) {
				$link = '#';
				foreach ( $j->webPages as $jl ) {
					if ( !empty( $jl->rels ) && in_array( 'canonical', $jl->rels ) ) {
						$link = $jl->href;
					}
				}
				$output .= '<li><a href="' . $link . '" target="_blank">' . $j->title . '</a></li>';
			}
			$output .= "</ul></details>";
			set_transient( $trans, $output, HOUR_IN_SECONDS );
		} elseif ( $shows[ $name ]['source'] == 'regex' ) {
			if ( $name == 'BBC World Service' ) {
				$offset = str_replace( '-', '', get_option( 'gmt_offset' ) );
				$output .= "<details class=\"progsegment\"><summary>Schedule</summary><ul><li><a href=\"{$shows[ $name]['id']}{$dx[0]}/{$dx[1]}/{$dx[2]}?utcoffset=-0{$offset}:00\" target=\"_blank\">BBC Schedule for {$date}</a></li></ul></details>";
				return $output;
			}
		} elseif ( $shows[ $name ]['source'] == 'wp-rss' ) {
			$transient = get_transient( $trans );
			if ( !empty( $transient ) ) {
				return $transient;
			} else {
				$url = $shows[ $name ]['id'] . str_replace( '-', '/', $date ) . "/feed/";
				$remote = wp_remote_get( esc_url_raw( $url ) );
				if ( is_wp_error( $remote ) ) {
					return $output;
				} else {
					$dom = simplexml_load_string( wp_remote_retrieve_body( $remote ) );
					$json = json_decode( json_encode( $dom ), true );
					$title = strtolower( 'Texas Standard For ' . date( 'F j, Y', $du ) );
					$set = false;
					if ( !empty( $json ) ) {
						if ( isset( $json['channel']['item']['title'] ) ) {
							if ( strtolower( $json['channel']['item']['title'] ) === $title ) {
								$output .= '<details class="progsegment"><summary>Program for ' . $date . '</summary><ul><li><a href="' . $json['channel']['item']['link'] . '" target="_blank">' . $json['channel']['item']['title'] . '</a></li></ul></details>';
								$set = true;
							}
						} else {
							if ( !empty( $json['channel']['item'] ) ) {
								foreach ( $json['channel']['item'] as $item ) {
									if ( !$set ) {
										if ( strtolower( $item['title'] ) === $title ) {
											$output .= '<details class="progsegment"><summary>Program for ' . $date . '</summary><ul><li><a href="' . $item['link'] . '" target="_blank">' . $item['title'] . '</a></li></ul></details>';
											$set = true;
										}
									}
								}
							}
						}
					}
				}
				set_transient( $trans, $output, HOUR_IN_SECONDS );
			}
		} elseif ( $shows[ $name ]['source'] == 'wp' ) {
			$transient = get_transient( $trans );
			if ( !empty( $transient ) ) {
				return $transient;
			} else {
				$url = $shows[ $name ]['id'] . "?before=" . $dt . "T00:00:00&after=" . $date . "T00:00:00";
				$remote = wp_remote_get( esc_url_raw( $url ) );
				if ( is_wp_error( $remote ) ) {
					return $output;
				} else {
					$api = wp_remote_retrieve_body( $remote );
					$json = json_decode( $api );
					if ( !empty( $json ) ) {
						$output .= "<details class=\"progsegment\"><summary>Segments for {$date}</summary><ul>";
						foreach ( $json as $j ) {
							$output .= '<li><a href="' . $j->link . '" target="_blank">' . $j->title->rendered . '</a></li>';
						}
						$output .= "</ul></details>";
					}
				}
				set_transient( $trans, $output, HOUR_IN_SECONDS );
			}
		} elseif ( $shows[ $name ]['source'] == 'local' ) {
			if ( $name == 'Houston Matters' ) {
				$hm = new WP_Query([
					'year' => $dx[0],
					'monthnum' => $dx[1],
					'day' => $dx[2],
					'cat' => 58,
					'post_type' => 'post',
					'post_status' => 'publish',
					'ignore_sticky_posts' => 1
				]);
				if ( $hm->have_posts() ) {
					$output .= "<details class=\"progsegment\"><summary>Segments for {$date}</summary><ul>";
					while ( $hm->have_posts() ) {
						$hm->the_post();
						$output .= '<li><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></li>';
					}
					$output .= '</ul></details>';
				}
				wp_reset_query();
			} else {
				return $output;
			}
		} else {
			return $output;
		}
	}
	return $output;
}

add_filter( 'xmlrpc_enabled', '__return_false' );

function hpm_image_preview_page(): void {
	$hook = add_submenu_page( 'edit.php', 'Featured Image Preview', 'Featured Image Preview', 'edit_posts', 'hpm-image-preview', function() {} );
	add_action( 'load-' . $hook, function() {
		$post_id = sanitize_text_field( $_GET['p'] );
		$top_cat = hpm_top_cat( $post_id );
		$title = get_the_title( $post_id );
		$postClass = get_post_class( '', $post_id ); ?>
<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
	<head>
		<meta charset="UTF-8">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>HPM Featured Image Preview</title>
		<link rel="stylesheet" id="hpmv4-style-css"  href="/app/themes/hpmv4/style.css" type="text/css" media="all" />
		<style>
			@media screen and (min-width: 52.5em) {
				.article-wrap {
					width: 100%;
					margin: 1em auto;
				}
				.article-wrap :is(article.card.card-large,article.card.card-medium) {
					width: 95%;
					margin: 0 2.5% 1em;
				}
				.article-wrap article.card {
					margin: 0 auto 1em;
					width: 45%;
				}
			}
		</style>
	</head>
	<body class="home blog">
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<div class="article-wrap">
<?php
		if ( empty( $post_id ) ) { ?>
							<h2 style="width: 100%;">Enter the ID number of the post you want to preview</h2>
							<form action="" method="GET">
								<input type="hidden" name="page" value="hpm-image-preview" />
								<input type="number" name="p" value="" />
								<input type="submit" value="Submit" />
							</form>
<?php
		} elseif ( !in_array( 'has-post-thumbnail', $postClass ) ) { ?>
							<h2 style="width: 100%;">The article you're previewing doesn't have a featured image. Set one in the editor and refresh this page.</h2>
<?php
		} elseif ( is_user_logged_in() && current_user_can( 'edit_post', $post_id ) ) { ?>
							<article <?php post_class( 'card card-large', $post_id ); ?>>
								<a class="post-thumbnail" href="#"><?php echo get_the_post_thumbnail( $post_id, 'large' ); ?></a>
								<div class="card-content">
									<header class="entry-header">
										<h3><?php echo $top_cat; ?></h3>
										<h2 class="entry-title"><a href="#" rel="bookmark"><?php echo $title; ?></a></h2>
									</header>
								</div>
							</article>
							<article <?php post_class( 'card card-medium', $post_id ); ?>>
								<a class="post-thumbnail" href="#"><?php echo get_the_post_thumbnail( $post_id, 'thumb' ); ?></a>
								<div class="card-content">
									<header class="entry-header">
										<h3><?php echo $top_cat; ?></h3>
										<h2 class="entry-title"><a href="#" rel="bookmark"><?php echo $title; ?></a></h2>
									</header>
								</div>
							</article>
							<article <?php post_class( 'card', $post_id ); ?>>
								<a class="post-thumbnail" href="#"><?php echo get_the_post_thumbnail( $post_id, 'thumb' ); ?></a>
								<div class="card-content">
									<header class="entry-header">
										<h3><?php echo $top_cat; ?></h3>
										<h2 class="entry-title"><a href="#" rel="bookmark"><?php echo $title; ?></a></h2>
									</header>
								</div>
							</article>
<?php
		} ?>
						</div>
					</main>
				</div>
			</div>
		</div>
	</body>
</html><?php
		exit;
	});
}

add_action( 'admin_menu', 'hpm_image_preview_page' );


/* ------------------------------------------------------------------------ *
 * post_class() and body_class support pulled from PostScript plugin
 *   by Barrett Golding (https://rjionline.org/)
 * ------------------------------------------------------------------------ */

/**
 * Displays meta box on post editor screen (both new and edit pages).
 */
function postscript_meta_box_setup(): void {
	$user = wp_get_current_user();
	$roles = [ 'administrator' ];

	// Add meta boxes only for allowed user roles.
	if ( array_intersect( $roles, $user->roles ) ) {
		add_action( 'add_meta_boxes', 'postscript_add_meta_box' );
		add_action( 'save_post', 'postscript_save_post_meta', 10, 2 );
	}
}
add_action( 'load-post.php', 'postscript_meta_box_setup' );
add_action( 'load-post-new.php', 'postscript_meta_box_setup' );


function postscript_metabox_admin_notice(): void {
	$postscript_meta = get_post_meta( get_the_id(), 'postscript_meta', true ); ?>
	<div class="error">
	<?php var_dump( $_POST ) ?>
		<p><?php _e( 'Error!', 'postscript' ); ?></p>
	</div>
	<?php
}

/**
 * Creates meta box for the post editor screen (for user-selected post types).
 * Passes array of user-setting options to callback.
 *
 * @uses postscript_get_options()   Safely gets option from database.
 */
function postscript_add_meta_box(): void {
	$options = [
		'user_roles' => [ 'administrator' ],
		'post_types' => [ 'post', 'page', 'shows' ],
		'allow' => [ 'class_body' => 'on', 'class_post' => 'on' ]
	];

	add_meta_box(
		'postscript-meta',
		esc_html__( 'Postscript', 'postscript' ),
		'postscript_meta_box_callback',
		$options['post_types'],
		'side',
		'default',
		$options
	);
}

/**
 * Builds HTML form for the post meta box.
 * Form elements are text fields for entering body/post classes (stored in same post-meta array).
 * Form elements are printed only if allowed on Setting page.
 *
 * @param Object $post Object containing the current post.
 * @param array $box  Array of meta box id, title, callback, and args elements.
 */
function postscript_meta_box_callback( object $post, array $box ): void {
	$post_id = $post->ID;
	wp_nonce_field( basename( __FILE__ ), 'postscript_meta_nonce' );

	// Display text fields for: URLs (style/script) and classes (body/post).
	$opt_allow = $box['args']['allow'];
	$postscript_meta = get_post_meta( $post_id, 'postscript_meta', true );

	if ( isset ( $opt_allow['class_body'] ) ) { // Admin setting allows body_class() text field. ?>
	<p>
		<label for="postscript-class-body"><?php _e( 'Body class:', 'postscript' ); ?></label><br />
		<input class="widefat" type="text" name="postscript_meta[class_body]" id="postscript-class-body" value="<?php if ( isset ( $postscript_meta['class_body'] ) ) { echo sanitize_html_class( $postscript_meta['class_body'] ); } ?>" size="30" />
	</p>
<?php
	}
	if ( isset ( $opt_allow['class_post'] ) ) { // Admin setting allows post_class() text field. ?>
	<p>
		<label for="postscript-class-post"><?php _e( 'Post class:', 'postscript' ); ?></label><br />
		<input class="widefat" type="text" name="postscript_meta[class_post]" id="postscript-class-post" value="<?php if ( isset ( $postscript_meta['class_post'] ) ) { echo sanitize_html_class( $postscript_meta['class_post'] ); } ?>" size="30" />
	</p>
<?php
	}
}

/**
 * Saves the meta box form data upon submission.
 *
 * @param int     $post_id  Post ID.
 * @param WP_Post $post     Post object.
 *
 * @uses  postscript_sanitize_data()    Sanitizes $_POST array.
 *
 */
function postscript_save_post_meta( int $post_id, WP_Post $post ): int {
	// Checks save status
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'postscript_meta_nonce' ] ) && wp_verify_nonce( $_POST[ 'postscript_meta_nonce' ], basename( __FILE__ ) ) ? 'true' : 'false' );

	// Exits script depending on save status
	if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
		return 0;
	}

	// Get the post type object (to match with current user capability).
	$post_type = get_post_type_object( $post->post_type );

	// Check if the current user has permission to edit the post.
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	$meta_key = 'postscript_meta';
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	// If any user-submitted form fields have a value.
	// (implode() reduces array values to a string to do the check).
	if ( isset( $_POST['postscript_meta'] ) && implode( $_POST['postscript_meta'] ) ) {
		$form_data  = postscript_sanitize_data( $_POST['postscript_meta'] );
	} else {
		$form_data  = null;
	}

	// Add post-meta, if none exists, and if user entered new form data.
	if ( $form_data && '' == $meta_value ) {
		add_post_meta( $post_id, $meta_key, $form_data, true );
	} elseif ( $form_data && $form_data != $meta_value ) {
		update_post_meta( $post_id, $meta_key, $form_data );
	} elseif ( null == $form_data && $meta_value ) {
		delete_post_meta( $post_id, $meta_key );
	}
	return $post_id;
}

/**
 * Sanitizes values in an one- and multi-dimensional arrays.
 * Used by post meta-box form before writing post-meta to database
 * and by Settings API before writing option to database.
 *
 * @link https://tommcfarlin.com/input-sanitization-with-the-wordpress-settings-api/
 *
 * @since	0.4.0
 *
 * @param array $data
 *
 * @return   array	$input_clean  The sanitized input.
 */
function postscript_sanitize_data( $data = [] ): array {
	// Initialize a new array to hold the sanitized values.
	$data_clean = [];

	// Check for non-empty array.
	if ( ! is_array( $data ) || ! count( $data )) {
		return [];
	}

	// Traverse the array and sanitize each value.
	foreach ( $data as $key => $value) {
		// For one-dimensional array.
		if ( ! is_array( $value ) && ! is_object( $value ) ) {
			// Remove blank lines and whitespaces.
			$value = preg_replace( '/^\h*\v+/m', '', trim( $value ) );
			$value = str_replace( ' ', '', $value );
			$data_clean[ $key ] = sanitize_text_field( $value );
		}

		// For multidimensional array.
		if ( is_array( $value ) ) {
			$data_clean[ $key ] = postscript_sanitize_data( $value );
		}
	}

	return $data_clean;
}

/**
 * Sanitizes values in an one-dimensional array.
 * (Used by post meta-box form before writing post-meta to database.)
 *
 * @link https://tommcfarlin.com/input-sanitization-with-the-wordpress-settings-api/
 * @since 0.4.0
 * @param array   $input        The address input.
 * @return array  $input_clean  The sanitized input.
 */
function postscript_sanitize_array( array $input ): array {
	// Initialize a new array to hold the sanitized values.
	$input_clean = [];

	// Traverse the array and sanitize each value.
	foreach ( $input as $key => $val ) {
		$input_clean[ $key ] = sanitize_text_field( $val );
	}

	return $input_clean;
}

function postscript_remove_empty_lines( $string ): array|string|null {
	return preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string );
}

/**
 * Adds user-entered class(es) to the body tag.
 *
 * @uses postscript_get_options()   Safely gets option from database.
 * @return  array $classes  WordPress defaults and user-added classes
 */
function postscript_class_body( $classes ): array {
	$post_id = get_the_ID();
	$options = [
		'user_roles' => [ 'administrator' ],
		'post_types' => [ 'post', 'page', 'shows' ],
		'allow' => [ 'class_body' => 'on', 'class_post' => 'on' ]
	];

	if ( !empty( $post_id ) && isset( $options['allow']['class_body'] ) ) {
		// Get the custom post class.
		$postscript_meta = get_post_meta( $post_id, 'postscript_meta', true );

		// If a post class was input, sanitize it and add it to the body class array.
		if ( !empty( $postscript_meta['class_body'] ) ) {
			$classes[] = sanitize_html_class( $postscript_meta['class_body'] );
		}
	}

	return $classes;
}
add_filter( 'body_class', 'postscript_class_body' );


/**
 * Adds user-entered class(es) to the post class list.
 *
 * @uses postscript_get_options()   Safely gets option from database.
 * @return  array $classes  WordPress defaults and user-added classes
 */
function postscript_class_post( $classes ): array {
	$post_id = get_the_ID();
	$options = [
		'user_roles' => [ 'administrator' ],
		'post_types' => [ 'post', 'page', 'shows' ],
		'allow' => [ 'class_body' => 'on', 'class_post' => 'on' ]
	];

	if ( !empty( $post_id ) && isset( $options['allow']['class_post'] ) ) {
		// Get the custom post class.
		$postscript_meta = get_post_meta( $post_id, 'postscript_meta', true );

		// If a post class was input, sanitize it and add it to the post class array.
		if ( !empty( $postscript_meta['class_post'] ) ) {
			$classes[] = sanitize_html_class( $postscript_meta['class_post'] );
		}
	}

	return $classes;
}
add_filter( 'post_class', 'postscript_class_post' );

add_action( 'load-post.php', 'hpm_alt_headline_setup' );
add_action( 'load-post-new.php', 'hpm_alt_headline_setup' );
function hpm_alt_headline_setup(): void {
	add_action( 'add_meta_boxes', 'hpm_alt_headline_add_meta' );
	add_action( 'save_post', 'hpm_alt_headline_save_meta', 10, 2 );
}

function hpm_alt_headline_add_meta(): void {
	add_meta_box(
		'hpm-alt-headline-meta-class',
		esc_html__( 'Alternate Headlines', 'example' ),
		'hpm_alt_headline_meta_box',
		'post',
		'normal',
		'high'
	);
}

function hpm_alt_headline_meta_box( $object, $box ): void {
	$placeholder = [
		'Diana was still alive hours before she died',
		'Missing woman unwittingly joins search party looking for herself',
		'Meatball sandwich horseplay leads to two deaths, family betrayal, two trials',
		'Patrick Stewart surprises fan with a life-threatening illness',
		'Homicide victims rarely talk to police',
		'"We hate math," say 4 in 10 - a majority of Americans',
		'Breathing oxygen linked to staying alive',
		'China may be using sea to hide its submarines',
		'Federal agents raid gun shop, find weapons',
		'Missippli\'s literacy program shows improvement',
		'Northfield plans to plan strategic plan',
		'State population to double by 2040; babies to blame',
		'Survey finds fewer deer after hunt',
		'Barbershop singers bring joy to school for deaf',
		'Woman missing since she got lost',
		'Miracle cure kills fifth patient'
	];
	$max = count( $placeholder ) - 1;
	$rand = rand( 0, $max );
	$rand2 = rand( 0, $max );
	wp_nonce_field( basename( __FILE__ ), 'hpm_alt_headline_class_nonce' );
	$alt_headline = get_post_meta( $object->ID, 'hpm_alt_headline', true );
	$seo_headline = get_post_meta( $object->ID, 'hpm_seo_headline', true ); ?>
	<p>If you would like to provide an alternate headline for use on the homepage, please enter it here.</p>
	<label for="hpm-alt-headline"><strong><?php _e( "Homepage Headline:", 'hpm-podcasts' ); ?></strong></label><br /><textarea id="hpm-alt-headline" name="hpm-alt-headline" placeholder="<?php echo $placeholder[$rand]; ?>" style="width: 100%;" rows="2"><?PHP echo $alt_headline; ?></textarea>
	<p>If you would like to provide an alternate headline for SEO/Facebook OpenGraph/Twitter/etc., please enter it here.</p>
	<label for="hpm-seo-headline"><strong><?php _e( "SEO Headline:", 'hpm-podcasts' ); ?></strong></label><br /><textarea id="hpm-seo-headline" name="hpm-seo-headline" placeholder="<?php echo $placeholder[$rand2]; ?>" style="width: 100%;" rows="2"><?PHP echo $seo_headline; ?></textarea>
<?php
}

function hpm_alt_headline_save_meta( $post_id, $post ) {
	if ( $post->post_type == 'post' ) {
		if ( !isset( $_POST['hpm_alt_headline_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_alt_headline_class_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		$post_type = get_post_type_object( $post->post_type );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}
		$alt = get_post_meta( $post_id, 'hpm_alt_headline', true );
		if ( !empty( $_POST['hpm-alt-headline'] ) ) {
			update_post_meta( $post_id, 'hpm_alt_headline', sanitize_text_field( $_POST['hpm-alt-headline'] ) );
		} elseif ( !empty( $alt ) ) {
			delete_post_meta( $post_id, 'hpm_alt_headline', '' );
		}

		$seo = get_post_meta( $post_id, 'hpm_seo_headline', true );
		if ( !empty( $_POST['hpm-seo-headline'] ) ) {
			update_post_meta( $post_id, 'hpm_seo_headline', sanitize_text_field( $_POST['hpm-seo-headline'] ) );
		} elseif ( !empty( $seo ) ) {
			delete_post_meta( $post_id, 'hpm_seo_headline', '' );
		}
	}
	return $post_id;
}

/*
 * Modify page title for articles with alternate SEO headlines
 */
function hpm_article_seo_title( $title ) {
	global $wp_query;
	if ( $wp_query->is_single() && !empty( $wp_query->post->ID ) ) {
		$seo_headline = get_post_meta( $wp_query->post->ID, 'hpm_seo_headline', true );
		if ( !empty( $seo_headline ) ) {
			return wp_strip_all_tags( $seo_headline ) . ' | Houston Public Media';
		}
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'hpm_article_seo_title' );

add_action( 'load-post.php', 'hpm_page_script_setup' );
add_action( 'load-post-new.php', 'hpm_page_script_setup' );
function hpm_page_script_setup(): void {
	add_action( 'add_meta_boxes', 'hpm_page_script_add_meta' );
	add_action( 'save_post', 'hpm_page_script_save_meta', 10, 2 );
}

function hpm_page_script_add_meta(): void {
	$user = wp_get_current_user();
	if ( in_array( 'administrator', $user->roles ) ) {
		add_meta_box(
			'hpm-page-script-meta-class',
			esc_html__( 'Injectable Scripts or Styling', 'example' ),
			'hpm_page_script_meta_box',
			[ 'post', 'page', 'embeds', 'shows' ],
			'normal',
			'high'
		);
	}
}

function hpm_page_script_meta_box( $object, $box ): void {
	wp_nonce_field( basename( __FILE__ ), 'hpm_page_script_class_nonce' );
	$page_script = get_post_meta( $object->ID, 'hpm_page_script', true );
	if ( empty( $page_script ) ) {
		$page_script = [
			'head' => '',
			'foot' => ''
		];
	} ?>
	<p>If you have styling or scripts that you would like to include, put them in here</p>
	<p><label for="hpm-page-script-head"><strong><?php _e( "Header:", 'hpm-podcasts' ); ?></strong></label><br /><?php
		$editor_opts = [
			'editor_height' => 200,
			'media_buttons' => false,
			'quicktags' => true,
			'teeny' => true,
			'wpautop' => false,
			'tinymce' => false,
			'drag_drop_upload' => false
		];
		wp_editor( $page_script['head'], 'hpm-page-script-head', $editor_opts );
	?></p>
	<p><label for="hpm-page-script-foot"><strong><?php _e( "Footer:", 'hpm-podcasts' ); ?></strong></label><br /><?php
		wp_editor( $page_script['foot'], 'hpm-page-script-foot', $editor_opts ); ?></p>
<?php
}

function hpm_page_script_save_meta( $post_id, $post ) {
	$user = wp_get_current_user();
	if ( in_array( 'administrator', (array) $user->roles ) ) {
		if ( !isset( $_POST['hpm_page_script_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_page_script_class_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		$post_type = get_post_type_object( $post->post_type );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		if ( empty( $_POST['hpm-page-script-head'] ) && empty( $_POST['hpm-page-script-foot'] ) ) {
			delete_post_meta( $post_id, 'hpm_page_script' );
		} else {
			$page_script = [
				'head' => $_POST['hpm-page-script-head'],
				'foot' => $_POST['hpm-page-script-foot']
			];
			update_post_meta( $post_id, 'hpm_page_script', $page_script );
		}
	}
	return $post_id;
}

add_action( 'wp_footer', function() {
	global $wp_query;
	$page_id = $wp_query->get_queried_object_id();
	$types = [ 'post', 'page', 'embeds', 'shows' ];
	$post_type = get_post_type( $page_id );
	if ( in_array( $post_type, $types ) ) {
		$page_script = get_post_meta( $page_id, 'hpm_page_script', true );
		if ( !empty( $page_script['foot'] ) ) {
			echo $page_script['foot'];
		}
	}
}, 999 );
add_action( 'wp_head', function() {
	global $wp_query;
	$page_id = $wp_query->get_queried_object_id();
	$types = [ 'post', 'page', 'embeds', 'shows' ];
	$post_type = get_post_type( $page_id );
	if ( in_array( $post_type, $types ) ) {
		$page_script = get_post_meta( $page_id, 'hpm_page_script', true );
		if ( !empty( $page_script['head'] ) ) {
			echo $page_script['head'];
		}
	}
}, 200 );


function hpm_now_playing( $station ) {
	return get_option( 'hpm_' . $station . '_nowplay' );
}

function hpm_now_playing_update(): void {
	$stations = [
		'news887' => 'https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/now?format=json',
		'classical' => 'https://api.composer.nprstations.org/v1/widget/51913211e1c8408134a6d347/now?format=json&show_song=true',
		'thevibe' => 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/thevibe.json',
		'tv8.1' => 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.1.json',
		'tv8.2' => 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.2.json',
		'tv8.3' => 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.3.json',
		'tv8.4' => 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.4.json',
		'tv8.6' => 'https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/nowplay/tv8.6.json'
	];
	foreach ( $stations as $k => $v ) {
		$output = '<h3>';
		$remote = wp_remote_get( esc_url_raw( $v ) );
		if ( is_wp_error( $remote ) ) {
			continue;
		} else {
			$data = json_decode( wp_remote_retrieve_body( $remote ), true );
		}
		if ( str_contains( $k, 'tv' ) ) {
			$output .= $data['airlist'][0]['version']['series']['series-title'];
		} elseif ( $k === 'thevibe' ) {
			$output .= $data['artist'] . ' - ' . $data['song'];
		} else {
			if ( empty( $data['onNow']['song'] ) ) {
				$output .= $data['onNow']['program']['name'];
			} else {
				if ( !empty( $data['onNow']['song']['composerName'] ) ) {
					$output .= $data['onNow']['song']['composerName'] . ' - ';
				}
				$output .= str_replace( '&', '&amp;', $data['onNow']['song']['trackName'] );
			}
		}
		$output .= '</h3>';
		update_option( 'hpm_' . $k . '_nowplay', $output, false );
	}
}

add_action( 'hpm_nowplay_update', 'hpm_now_playing_update' );
$timestamp = wp_next_scheduled( 'hpm_nowplay_update' );
if ( empty( $timestamp ) ) {
	wp_schedule_event( time(), 'hpm_2min', 'hpm_nowplay_update' );
}

function hpm_weather(): string {
	$output = get_transient( 'hpm_weather' );
	if ( !empty( $output ) ) {
		return $output;
	}
	$c = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$c = $c + $offset;
	$remote = wp_remote_get( esc_url_raw( "https://api.openweathermap.org/data/2.5/weather?lat=29.7265396&lon=-95.3415406&units=imperial&appid=" . HPM_OPEN_WEATHER ) );
	if ( !is_wp_error( $remote ) ) {
		$weather = json_decode( wp_remote_retrieve_body( $remote ) );
		$output .= '<h3 style="color: white; font-size: 0.875rem;">' . date( "F d, Y", $c ) . '</h3>' .
			'<div style="display: grid; grid-template-columns: 2rem 1fr; align-items: center; gap: 0.5rem; padding-top: 0.25rem;">' .
				'<div><img src="https://cdn.houstonpublicmedia.org/assets/images/weather/' . $weather->weather[0]->icon .'.png.webp" alt="' . $weather->weather[0]->description . '"></div>' .
				'<div style="font-size: 1.875rem;">' . round( $weather->main->temp ) . ' &deg;F</div>' .
			'</div>';
		$api_output = [
			'icon' => 'https://cdn.houstonpublicmedia.org/assets/images/weather/' . $weather->weather[0]->icon . '.png.webp',
			'description' =>  $weather->weather[0]->description,
			'temperature' =>  (string)round( $weather->main->temp ) . ' &deg;F',
		];
		set_transient( 'hpm_weather_api', $api_output, 180 );
		set_transient( 'hpm_weather', $output, 180 );
	}
	return $output;
}

function hpm_ytlive_update(): void {
	$temp = [
		'houston-matters' => [],
		'hello-houston' => []
	];
	$option = get_option( 'hpm_ytlive_talkshows' );
	if ( empty( $option ) ) {
		$option = $temp;
	}
	$t = getdate();
	$today = mktime( 0, 0, 0, $t['mon'], $t['mday'], $t['year'] );
	$tomorrow = $today + 86400;
	$remote = wp_remote_get( esc_url_raw( "https://cdn.houstonpublicmedia.org/assets/ytlive.json" ) );
	if ( is_wp_error( $remote ) ) {
		return;
	} else {
		$json = json_decode( wp_remote_retrieve_body( $remote ), true );
		foreach( $json as $item ) {
			$date = strtotime( $item['start'] );
			if ( str_contains( $item['title'], 'Houston Matters' ) ) {
				$temp['houston-matters'][ $date ] = $item;
			} elseif ( str_contains( $item['title'], 'Hello Houston' ) ) {
				$temp['hello-houston'][ $date ] = $item;
			}
		}
	}

	ksort( $temp['houston-matters'] );
	ksort( $temp['hello-houston'] );
	foreach( $temp as $show => $event ) {
		foreach ( $event as $date => $meta ) {
			$prev = $option[ $show ]['start'];
			if ( $date >= $today && $date <= $tomorrow && $date > $prev ) {
				$option[ $show ] = $meta;
			}
		}
	}
	update_option( 'hpm_ytlive_talkshows', $option );
}

add_action( 'hpm_ytlive', 'hpm_ytlive_update' );
$timestamp = wp_next_scheduled( 'hpm_ytlive' );
if ( empty( $timestamp ) ) {
	wp_schedule_event( time(), 'hpm_15min', 'hpm_ytlive' );
}