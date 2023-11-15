<?php
function hpm_audio_shortcode( $html, $attr ): string {
	$post_id = get_post() ? get_the_ID() : 0;
	static $instance = 0;
	$instance++;
	$supported = false;
	$audio_type = '';
	$default_types = wp_get_audio_extensions();
	foreach ( $default_types as $type ) {
		if ( !empty( $attr[ $type ] ) ) {
			$supported = true;
			$audio_type = $type;
		}
	}

	if ( !$supported ) {
		return '&nbsp;';
	}

	if ( !empty( $attr['id'] ) ) {
		$audio_id = $attr['id'];
		$audio_data_title = get_the_title( $attr['id'] );
	} else {
		$audio_id = $instance;
	}
	$audio_title = 'Listen';
	$audio_url = $attr[ $audio_type ];
	$preload = 'metadata';
	$sg_file = get_post_meta( $post_id, 'hpm_podcast_enclosure', true );
	if ( !empty( $sg_file ) ) {
		$s3_parse = parse_url( $audio_url );
		$s3_path = pathinfo( $s3_parse['path'] );
		$sg_parse = parse_url( $sg_file['url'] );
		$sg_path = pathinfo( $sg_parse['path'] );
		if ( $s3_path['basename'] === $sg_path['basename'] ) {
			$audio_url = $sg_file['url'];
		} else {
			$audio_url = str_replace( 'http:', 'https:', $audio_url );
		}
		$preload = "none";
	} else {
		$audio_url = str_replace( 'http:', 'https:', $audio_url );
	}
	if ( !str_contains( $audio_url, '?' ) ) {
		$audio_url .= '?';
	} else {
		$audio_url .= '&';
	}
	$html = '';
	if ( is_feed() || amp_is_request() ) {
		$html .= '<audio preload="' . $preload . '" src="'.$audio_url.'source=rss-feed"><source type="audio/mpeg" src="'.$audio_url.'source=rss-feed"></audio>';
	} else {
		wp_enqueue_script('hpm-plyr');
		$html .= '<div class="article-player-wrap">'.
				'<h3>'.htmlentities( wp_trim_words( $audio_title, 10, '...' ), ENT_COMPAT | ENT_HTML5, 'UTF-8', false ) .'</h3>'.
				'<audio class="js-player" id="audio-' . $audio_id . '" data-title="' . ( !empty( $audio_data_title ) ? urlencode( $audio_data_title ) : '' ) . '" controls preload="' . $preload . '">'.
					'<source src="'.$audio_url.'source=plyr-article" type="audio/mpeg" />'.
				'</audio>';
		if ( !is_admin() && !in_array( $post_id, [ 0, 58036 ] ) ) {
			$html .= '<button class="plyr-audio-embed" data-id="' . $audio_id .'">' . hpm_svg_output( 'code' ) . '</button>' .
				'<div class="plyr-audio-embed-popup" id="plyr-' . $audio_id . '-popup">' .
					'<div class="plyr-audio-embed-wrap">' .
						'<p>To embed this piece of audio in your site, please use this code:</p>' .
						'<div class="plyr-audio-embed-code">' .
							'&lt;iframe src="https://embed.hpm.io/' . $audio_id . '/' . $post_id . '" style="height: 115px; width: 100%;"&gt;&lt;/iframe&gt;' .
						'</div>' .
						'<div class="plyr-audio-embed-close">X</div>' .
					'</div>' .
				'</div>';
		}
		$html .= '</div>';
	}
	return $html;
}
add_filter( 'wp_audio_shortcode_override', 'hpm_audio_shortcode', 10, 2 );

function hpm_nprapi_audio_shortcode( $text ): string {
	$matches = [];
	preg_match_all( '/' . get_shortcode_regex() . '/', $text, $matches );

	$tags = $matches[2];
	$args = $matches[3];
	foreach( $tags as $i => $tag ) {
		if ( $tag == "audio" ) {
			$atts = shortcode_parse_atts( $args[$i] );
			if ( !empty( $atts['mp3'] ) ) {
				$a_tag = '<figure><figcaption>Listen to the story audio:</figcaption><audio controls src="' . $atts['mp3'] . '">Your browser does not support the <code>audio</code> element.</audio></figure>';
				$text = str_replace( '<p>'.$matches[0][$i].'</p>', $a_tag, $text );
				$text = str_replace( $matches[0][$i], $a_tag, $text );
			}
		}
	}
	return $text;
}
add_filter( 'npr_ds_shortcode_filter', 'hpm_nprapi_audio_shortcode', 10, 1 );

function hpm_apple_news_audio( $text ): string {
	global $post;
	global $wpdb;
	$id = $post->ID;
	$matches = [];
	preg_match_all( '/' . get_shortcode_regex() . '/', $text, $matches );

	$tags = $matches[2];
	$args = $matches[3];
	foreach( $tags as $i => $tag ) {
		if ( $tag == "audio" ) {
			$atts = shortcode_parse_atts( $args[$i] );
			if ( !empty( $atts ) ) {
				$a_tag = '';
				if ( !empty( $atts['id'] ) ) {
					$a_tag = '<audio src="' . wp_get_attachment_url( $atts['id'] ) . '"></audio>';
				} elseif ( !empty( $atts['mp3'] ) ) {
					$a_tag = '<audio src="' . $atts['mp3'] . '"></audio>';
				}
				$text = str_replace( '<p>'.$matches[0][$i].'</p>', $a_tag, $text );
				$text = str_replace( $matches[0][$i], $a_tag, $text );
			}
		}
	}
	$terms = get_the_terms( $id, 'category' );
	$show = 0;
	foreach( $terms as $t ) {
		$cats = get_ancestors( $t->term_id, 'category', 'taxonomy' );
		if ( in_array( 5, $cats ) ) {
			$show = $t->term_id;
		}
	}
	if ( $show !== 0 ) {
		$res = $wpdb->get_results( "SELECT wp_posts.*
			FROM wp_posts
			LEFT JOIN wp_postmeta AS tr1 ON (wp_posts.ID = tr1.post_id)
			WHERE
				( tr1.meta_key = 'hpm_shows_cat' OR tr1.meta_key = 'hpm_pod_cat' ) AND
				tr1.meta_value = $show AND
				wp_posts.post_status = 'publish' AND
				( wp_posts.post_type = 'shows' OR wp_posts.post_type = 'podcasts' ) " );
		if ( !empty( $res ) ) {
			if ( $res[0]->post_type == 'shows' ) {
				$text .= '<p><strong><em>For more information and episodes, visit the <a href="' . get_the_permalink(
					$res[0]->ID ) . '">' . $res[0]->post_title . ' show page</a>.</em></strong></p>';
			} elseif ( $res[0]->post_type == 'podcasts' ) {
				$podmeta = get_post_meta( $res[0]->ID, 'hpm_pod_link', true );
				$text .= '<p><strong><em>For more information and episodes, visit the <a href="' . $podmeta['page'] . '">' . $res[0]->post_title . ' show page</a>.</em></strong></p>';
			}
		}
	}
	return $text;
}
add_filter( 'apple_news_exporter_content_pre', 'hpm_apple_news_audio', 10, 1 );

function hpm_audio_shortcode_insert ( $html, $id, $attachment ): string {
	if ( str_contains( $html, '[audio' ) ) {
		$html = str_replace( '][/audio]', ' id="' . $id . '"][/audio]', $html );
	}
	return $html;
}
add_filter( 'media_send_to_editor', 'hpm_audio_shortcode_insert', 10, 8 );

function article_display_shortcode ( $atts ): bool|string {
	global $hpm_constants;
	if ( empty( $hpm_constants ) ) {
		$hpm_constants = [];
	}
	$article = [];
	extract( shortcode_atts( [
		'num' => 1,
		'tag' => '',
		'category' => '',
		'type' => 'd',
		'post_id' => '',
		'exclude' => ''
	], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1,
		'post_type' => 'post',
		'post_status' => 'publish'
	];
	if ( !empty( $hpm_constants ) ) {
		$args['post__not_in'] = $hpm_constants;
	}
	if ( !empty( $exclude ) ) {
		if ( preg_match( '/[0-9,]+/', $exclude ) ) {
			$excl_exp = explode( ',', $exclude );
			if ( empty( $args['post__not_in'] ) ) {
				$args['post__not_in'] = $excl_exp;
			} else {
				$args['post__not_in'] = array_merge( $args['post__not_in'], $excl_exp );
			}
		}
	}
	if ( !empty( $category ) && !empty( $tag ) ) {
		$args['tax_query'] = [
			'relation' => 'OR',
			[
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => [ $category ]
			],
			[
				'taxonomy' => 'post_tag',
				'field'    => 'slug',
				'terms'    => [ $tag ]
			]
		];
	} else {
		if ( !empty( $category ) ) {
			$args['category_name'] = $category;
		}
		if ( !empty( $tag ) ) {
			$args['tag_slug__in'][] = $tag;
		}
	}
	if ( !empty( $post_id ) ) {
		$i_exp = explode( ',', $post_id );
		foreach ( $i_exp as $ik => $iv ) {
			$i_exp[$ik] = trim( $iv );
		}
		$args['post__in'] = $i_exp;
		$args['orderby'] = 'post__in';
		$c = count( $i_exp );
		if ( $c != $args['posts_per_page'] ) {
			$diff = $args['posts_per_page'] - $c;
			$args['posts_per_page'] = $c;
			unset( $args['category_name'] );
			$article[] = new WP_Query( $args );
			unset( $args['post__in'] );
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
			$args['post__not_in'] = array_merge( $hpm_constants, $i_exp );
			$args['posts_per_page'] = $diff;
			if ( !empty( $category ) ) {
				$args['category_name'] = $category;
			}
		}
	}
	$article[] = new WP_query( $args );
	global $ka;
	if ( $type == 'a' ) {
		$ka = 0;
	} elseif ( $type == 'b' ) {
		$ka = 1;
	}
	ob_start();
	foreach ( $article as $art ) {
		if ( $art->have_posts() ) {
			while ( $art->have_posts() ) {
				$art->the_post();
				get_template_part( 'content', get_post_format() );
				if ( isset( $ka ) ) {
					$ka += 2;
				}
				$hpm_constants[] = get_the_ID();
			}
		}
	}
	wp_reset_query();
	$getContent = ob_get_contents();
	ob_end_clean();
	return $getContent;
}
add_shortcode( 'hpm_articles', 'article_display_shortcode' );

function article_list_shortcode( $atts ): string {
	extract( shortcode_atts( [
		'num' => 1,
		'category' => '',
		'tag' => '',
		'post_id' => ''
	], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	];
	$extra = '';
	if ( !empty( $category ) ) {
		$args['category_name'] = $category;
		$extra = '<li><a href="/topics/' . $category . '/">Read More...</a></li>';
	}
	if ( !empty( $tag ) ) {
		$args['tag_slug__in'][] = $tag;
	}
	if ( !empty( $post_id ) ) {
		$args['p'] = $post_id;
		$args['posts_per_page'] = 1;
	}
	$article = new WP_query( $args );
	$output = '<ul>';
	if ( $article->have_posts() ) {
		while ( $article->have_posts() ) {
			$article->the_post();
			$output .= '<li><a href="' . get_permalink() . '" rel="bookmark">' . get_the_title() . '</a></li>';
		}
		$output .= $extra;
	} else {
		$output .= "<li>Coming Soon</li>";
	}
	$output .= '</ul>';
	wp_reset_query();
	return $output;
}
add_shortcode( 'hpm_article_list', 'article_list_shortcode' );

function hpm_npr_article_shortcode( $atts ): string {
	extract( shortcode_atts( [
		'category' => 1001,
		'num' => 4
	], $atts, 'multilink' ) );
	return hpm_nprapi_output( $category, $num );
}
add_shortcode( 'hpm_npr_articles', 'hpm_npr_article_shortcode' );

/**
 * Cron job for updating at-home learning page schedule
 */
function hpm_athome_sched_update(): string {
	// Pull cached transient from Redis
	$output = get_transient( 'hpm_athome_sched' );
	/**
	 *  If WP_Debug is enabled, ignore the transient and regenerate
	 *  If the transient is not empty, serve it, otherwise move forward
	 */
	if ( WP_DEBUG ) {
		$output = '';
	} else {
		if ( !empty( $output ) ) {
			return $output;
		}
	}

	// Determine the current time in GMT and adjust to timezone
	$t = time();

	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$cutoff = mktime( 0, 0, 0, 9, 6, 2020 ) + $offset;
	// Set up data structure for the week to display
	$week = [
		1 => [
			'name' => 'Monday',
			'date' => '',
			'date_unix' => '',
			'data' => [
				'8.1' => [],
				'8.4' => []
			]
		],
		2 => [
			'name' => 'Tuesday',
			'date' => '',
			'date_unix' => '',
			'data' => [
				'8.1' => [],
				'8.4' => []
			]
		],
		3 => [
			'name' => 'Wednesday',
			'date' => '',
			'date_unix' => '',
			'data' => [
				'8.1' => [],
				'8.4' => []
			]
		],
		4 => [
			'name' => 'Thursday',
			'date' => '',
			'date_unix' => '',
			'data' => [
				'8.1' => [],
				'8.4' => []
			]
		],
		5 => [
			'name' => 'Friday',
			'date' => '',
			'date_unix' => '',
			'data' => [
				'8.1' => [],
				'8.4' => []
			]
		]
	];
	// Set up data structure for temporary schedule
	$temp = [];
	// Time columns to put on the front and back of the schedules
	// Could probably script this, but I was in a hurry
	$timecol = [
		'8.1' => '<div class="lah-col lah-time"><div class="lah-col-head"></div><div>6:00am</div><div>6:30am</div><div>7:00am</div><div>7:30am</div><div>8:00am</div><div>8:30am</div><div>9:00am</div><div>9:30am</div><div>10:00am</div><div>10:30am</div><div>11:00am</div><div>11:30am</div><div>12:00pm</div><div>12:30pm</div><div>1:00pm</div><div>1:30pm</div><div>2:00pm</div><div>2:30pm</div><div>3:00pm</div><div>3:30pm</div><div>4:00pm</div><div>4:30pm</div><div>5:00pm</div><div>5:30pm</div></div>',
		'8.4' => '<div class="lah-col lah-time"><div class="lah-col-head"></div><div>11:00am</div><div>11:30am</div><div>12:00pm</div><div>12:30pm</div><div>1:00pm</div><div>1:30pm</div><div>2:00pm</div><div>2:30pm</div><div>3:00pm</div><div>3:30pm</div></div>'
	];

	/**
	 * Determine the nearest Monday
	 * 	-- If Monday, set that day as Monday
	 * 	-- If Tuesday - Friday, find previous Monday
	 * 	-- If Saturday - Sunday, find next Monday
	 */
	$monday_unix = 0;
	if ( $now['wday'] >= 1 && $now['wday'] <= 5 ) {
		$monday_unix = ( $now[0] - ( ( $now['wday'] - 1 ) * 86400 ) );
	} elseif ( $now['wday'] == 0 ) {
		$monday_unix = ( $now[0] + 86400 );
	} elseif ( $now['wday'] == 6 ) {
		$monday_unix = ( $now[0] + ( 2 * 86400 ) );
	}

	// Loop through week data structure and determine dates and Unix times for each day
	$week[1]['date'] = date( "Ymd" , $monday_unix );
	$week[1]['date_unix'] = $monday_unix;
	for ( $i = 2; $i < 6; $i++ ) {
		$week[$i]['date'] = date( "Ymd" , $monday_unix + ( ( $i - 1 ) * 86400 ) );
		$week[$i]['date_unix'] = $monday_unix + ( ( $i - 1 ) * 86400 );
	}

	/**
	 * Set up context for API pulls
	 * HPM_PBS_TVSS is a global that contains the authorization header token that starts with 'X-PBSAuth:'
	 * You can get an access token by filing a ticket here: https://docs.pbs.org/display/tvsapi#TVSchedulesService(TVSS)API-Access
	*/
	$opts = [
		'http' => [
			'method' => "GET",
			'header' => HPM_PBS_TVSS
		]
	];
	$url_base = "https://tvss.services.pbs.org/tvss/kuht/";


	/**
	 * Loop though the week and pull the actual schedule data
	 * 8.1 is our main public channel, and 8.4 is our WORLD broadcast
	*/
	foreach ( $week as $k => $w ) {
		$url1 = $url_base."day/".$w['date']."/623006be-27ab-40ab-aea7-208777d02ab1";
		$url4 = $url_base."day/".$w['date']."/afc37341-cecf-45a4-ac81-0ed31542d4c9";

		$context = stream_context_create( $opts );
		$result1 = file_get_contents( $url1, FALSE, $context );
		$result4 = file_get_contents( $url4, FALSE, $context );
		// Data arrives as JSON, so we decode it into an associative array
		$data1 = json_decode( $result1, true );
		$data4 = json_decode( $result4, true );
		$week[$k]['data']['8.1'] = $data1['feeds'][0]['listings'];
		$week[$k]['data']['8.4'] = $data4['feeds'][0]['listings'];
	}

	// Build the head of each schedule and put it into our temp array
	$temp['8.1'] = '<div class="lah-schedule"><h2>Channel 8.1 At-Home Learning Schedule with Links to Learning Resources</h2><h3>Week of ' . date( 'F j, Y', $monday_unix ) . '</h3><div class="lah-legend"><div class="lah-legend-young"><span></span> Grades PreK-3</div><div class="lah-legend-middle"><span></span> Grades 4-8</div><div class="lah-legend-high"><span></span> Grades 9-12</div></div><div class="lah-wrap">'.$timecol['8.1'];
	if ( $monday_unix > $cutoff ) {
		$temp['8.4'] = '<div class="lah-schedule"><h2 id="tv8.4">Channel 8.4 At-Home Learning Schedule</h2><h3>Week of ' . date( 'F j, Y', $monday_unix ) . '</h3><div class="lah-legend"><div class="lah-legend-science"><span></span> Science</div><div class="lah-legend-sstudies"><span></span> Social Studies</div><div class="lah-legend-ela"><span></span> English/Language Arts</div><div class="lah-legend-math"><span></span> Math</div></div><div class="lah-wrap">'.$timecol['8.4'];
	} else {
		$temp['8.4'] = '<div class="lah-schedule"><h2 id="tv8.4">Channel 8.4 At-Home Learning Schedule with Links to Learning Resources</h2><h3>Week of ' . date( 'F j, Y', $monday_unix ) . '</h3><div class="lah-legend"><div class="lah-legend-science"><span></span> Science</div><div class="lah-legend-sstudies"><span></span> Social Studies</div><div class="lah-legend-ela"><span></span> English/Language Arts</div><div class="lah-legend-math"><span></span> Math</div></div><div class="lah-wrap">'.$timecol['8.4'];
	}


	/**
	 * Wheels within wheels, my friend. Well, loops within loops
	 * Loops through the days in the week
	 */
	foreach ( $week as $w ) {
		// Loop through the daily data for each channel
		foreach ( $w['data'] as $dk => $dv ) {
			// Setup the header for each daily column
			$temp[ $dk ] .= '<div class="lah-col lah-' . strtolower( $w['name'] ) . '"><div class="lah-col-head">' . $w['name'] . '<br />' . date( 'm/d/Y', $w['date_unix'] ) . '</div>';
			// Loop through each entry of the current day
			foreach ( $dv as $pv ) {
				// Determine if the program is in the right timeframe for the channel
				if (
					( $dk === '8.1' && $pv['start_time'] >= 600 && $pv['start_time'] < 1800 ) ||
					( $dk === '8.4' && $pv['start_time'] >= 1100 && $pv['start_time'] < 1600 )
				) {
					// Set up a generic CSS class
					$class = 'lah-' . $pv['minutes'];
					/**
					 * Modify CSS class to reflect grade level on main channel
					 * This is mostly based on timeframes but there might be some wiggle
					 */
					if ( $dk == '8.1' ) {
						if ( $pv['start_time'] < 1200 ) {
							$class .= ' lah-young';
						} elseif ( $pv['start_time'] < 1500 ) {
							$class .= ' lah-middle';
						} else {
							$class .= ' lah-high';
						}

					/**
					 * Modify CSS class to reflect subject on WORLD channel
					 * This is partially based on timeframe, but ELA gets preempted at least 2 days a week in favor of social studies
					 * Had to make some best guesses, and look ahead at the schedule to adjust the exemptions
					 */
					} else {
						if ( $pv['start_time'] < 1300 ) {
							if ( str_contains( 'Math', $pv['title'] ) ) {
								$class .= ' lah-math';
							} else {
								$class .= ' lah-science';
							}
						} else {
							if ( $pv['title'] == 'American Masters' || $pv['title'] == 'Poetry in America' || $pv['title'] == 'Great Performances' ) {
								$class .= ' lah-ela';
							} elseif ( str_contains( 'Amazing Human Body', $pv['title'] ) ) {
								$class .= ' lah-science';
							} else {
								$class .= ' lah-sstudies';
							}
						}
					}
					if ( strlen( $pv['title'] ) > 40 ) {
						$exp_title = explode( ':', $pv['title'] );
						$show_title = wp_trim_words( trim( $exp_title[0] ), 9, '&hellip;' );
					} else {
						$show_title = wp_trim_words( trim( $pv['title'] ), 9, '&hellip;' );
					}
					// Create the schedule entries and concatenate them onto the temp schedule
					if ( $monday_unix > $cutoff ) {
						$temp[ $dk ] .= '<div class="' . $class . '">' . $show_title . '</div>';
					} else {
						$temp[ $dk ] .= '<div class="' . $class . '"><a title="' . $pv['title'] . ' Episode Information" href="./resources/#s'. date( 'w-', $w['date_unix'] ) . $pv['start_time'] . '-' . $dk . '">' . $show_title . '</a></div>';
					}
				}
			}
			// Close out the column
			$temp[ $dk ] .= '</div>';
		}
	}
	// Close out the temp schedules
	$temp['8.1'] .= $timecol['8.1'] . '</div></div>';
	$temp['8.4'] .= $timecol['8.4'] . '</div></div>';

	/**
	 * Concatenate the channel schedules along with a hidden time stamp
	 * Makes it easier to ensure that the cron job is running and the schedule is updating
	*/
	if ( $monday_unix > $cutoff ) {
		$output = $temp['8.4'] . '<p style="display: none;">Last Update: ' . date( 'Y/m/d H:i:s', $t ) . '</p>';
	} else {
		$output = $temp['8.1'] . $temp['8.4'] . '<p style="display: none;">Last Update: ' . date( 'Y/m/d H:i:s', $t ) . '</p>';
	}
	// Save the output as a site transient in Redis and output
	set_transient( 'hpm_athome_sched', $output, 7200 );
	return $output;
}
add_shortcode( 'hpm_athome', 'hpm_athome_sched_update' );

add_action( 'hpm_athome_update', 'hpm_athome_sched_update' );
$timestamp = wp_next_scheduled( 'hpm_athome_update' );
if ( empty( $timestamp ) ) {
	wp_schedule_event( time(), 'hourly', 'hpm_athome_update' );
}

function hpm_programs_shortcode( $atts ): string {
	extract( shortcode_atts( [
		'channel' => 'news'
	], $atts, 'multilink' ) );
	if ( empty( $channel ) ) {
		return 'EMPTY';
	}
	$out = get_transient( 'hpm_programs_' . $channel );
	if ( empty( $out ) ) {
		return "Transient Empty";
	}
	return $out;
}
add_shortcode( 'hpm_programs', 'hpm_programs_shortcode' );

function hpm_careers_trans(): string {
	$output = get_transient( 'hpm_careers' );
	if ( !empty( $output ) ) {
		return $output;
	}

	$url = 'https://uhs.taleo.net/careersection/rest/jobboard/searchjobs?lang=en&portal=8100120292';
	$options =[
		'body' => '{"multilineEnabled":false,"sortingSelection":{"sortBySelectionParam":"3","ascendingSortingOrder":"false"},"fieldData":{"fields":{"KEYWORD":""},"valid":true},"filterSelectionParam":{"searchFilterSelections":[{"id":"POSTING_DATE","selectedValues":[]},{"id":"ORGANIZATION","selectedValues":["14400120292","166300023214"]},{"id":"JOB_TYPE","selectedValues":[]},{"id":"JOB_FIELD","selectedValues":[]},{"id":"JOB_SCHEDULE","selectedValues":[]}]},"advancedSearchFiltersSelectionParam":{"searchFilterSelections":[{"id":"ORGANIZATION","selectedValues":[]},{"id":"LOCATION","selectedValues":[]},{"id":"JOB_FIELD","selectedValues":[]},{"id":"JOB_NUMBER","selectedValues":[]},{"id":"URGENT_JOB","selectedValues":[]},{"id":"EMPLOYEE_STATUS","selectedValues":[]},{"id":"STUDY_LEVEL","selectedValues":[]},{"id":"JOB_SHIFT","selectedValues":[]}]},"pageNo":1}',
		'headers' => [
			'Referer' => 'https://uhs.taleo.net/careersection/ex1_uhs/jobsearch.ftl?f=ORGANIZATION(14400120292)',
			'Origin' => 'https://uhs.taleo.net',
			'X-Requested-With' => 'XMLHttpRequest',
			'tz' => 'GMT-06:00',
			'tzname' => 'America/Chicago',
			'Pragma' => 'no-cache',
			'Content-Type' => 'application/json',
			'Cookie' => 'locale=en'
		]
	];
	$result = wp_remote_post( $url, $options );
	if ( is_wp_error( $result ) ) {
		return $output;
	}

	if ( $result['response']['code'] !== 200 ) {
		return $output;
	}
	$body = wp_remote_retrieve_body( $result );
	if ( empty( $body ) ) {
		return $output;
	}

	$json = json_decode( $body, true );
	$desc = json_decode( file_get_contents( 'https://hpmwebv2.s3-us-west-2.amazonaws.com/assets/taleo.json' ), true );
	if ( empty( $json['requisitionList'] ) ) {
		set_transient( 'hpm_careers', $output, 900 );
		return $output;
	}
	$output .= '<svg hidden xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><use href="#hpm-job-link"></use><symbol id="hpm-job-link"><path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/></symbol></svg>';
	foreach ( $json['requisitionList'] as $j ) {
		if ( !in_array( $j['contestNo'], $desc['exclude'] ) ) {
			if ( !empty( $desc[ $j['contestNo'] ]['title'] ) ) {
				$title = $desc[ $j['contestNo'] ]['title'];
			} else {
				$title = trim( $j['column'][0] );
			}
			$output .= '<details id="' . $j['contestNo'] . '"><summary>' . $title . '</strong></summary><div class="job-link" title="Click for a direct link to this job posting" data-job="#' . $j['contestNo'] . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><use href="#hpm-job-link"></use></svg></div>';
			if ( !empty( $desc[ $j['contestNo'] ]['description'] ) ) {
				$output .= $desc[ $j['contestNo'] ]['description'];
			}
			$output .= '<p><a href="https://uhs.taleo.net/careersection/ex1_uhs/jobdetail.ftl?job=' . $j['contestNo'] . '&tz=GMT-06%3A00&tzname=America%2FChicago">Click here to apply</a></p></details>';
		}
	}
	set_transient( 'hpm_careers', $output, 900 );
	return $output;
}
add_shortcode( 'hpm_careers', 'hpm_careers_trans' );

function hpm_townsquare_covid( $atts ): string {
	global $hpm_constants;
	if ( empty( $hpm_constants ) ) {
		$hpm_constants = [];
	}
	extract( shortcode_atts( [], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => 1,
		'ignore_sticky_posts' => 1,
		'category_name' => 'town-square+coronavirus',
		'post_type' => 'post',
		'post_status' => 'publish',
		'meta_query' => [[
			'key' => 'hpm_podcast_enclosure',
			'compare' => 'EXISTS'
		]]
	];
	$art = new WP_Query( $args );
	$output = '';
	if ( $art->have_posts() ) {
		while ( $art->have_posts() ) {
			$art->the_post();
			$postClass = get_post_class();
			$postClass[] = 'town-square-feature';
			$hpm_constants[] = get_the_ID();
			$podcast = get_post_meta( get_the_ID(), 'hpm_podcast_enclosure', true );
			wp_enqueue_script('hpm-plyr');
			$output .= '<article class="'.implode( ' ', $postClass ).'">' .
				'<div class="img-wrap">' .
					'<p><a href="/shows/town-square/" aria-hidden="true"><img src="https://cdn.houstonpublicmedia.org/assets/images/town-square-logo.webp" alt="Town Square with Ernie Manouse logo" /></a></p>' .
					'<p><a href="/listen-live/">Listen Live</a> at 3pm or<br /><a href="/podcasts/town-square/">Download the Podcast</a></p>' .
				'</div>' .
				'<header class="entry-header">' .
					'<h3><a href="/shows/town-square/">The Latest from Town Square</a></h3>'.
					'<div class="article-player-wrap">' .
						'<h2 class="entry-title"><a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a></h2>' .
						'<audio class="js-player" controls preload="metadata">' .
							'<source src="'.$podcast['url'].'source=plyr-article" type="audio/mpeg" />' .
						'</audio>' .
					'</div>' .
				'</header>' .
			'</article>';
		}
	}
	wp_reset_query();
	return $output;
}
add_shortcode( 'covid_ts', 'hpm_townsquare_covid' );

function hpm_indepth_bug(): string {
	return '<div class="in-post-bug in-depth"><a href="/topics/in-depth/">Click here for more inDepth features.</a></div>';
}
add_shortcode( 'hpm_indepth', 'hpm_indepth_bug' );

function hpm_newsletter_bug(): string {
	//return '<div class="in-post-bug newsletter"><a href="/news/today-in-houston-newsletter/" target="_blank">Let the Houston Public Media newsroom help you start your day. Subscribe to <span>Today&nbsp;in&nbsp;Houston</span>.</a></div>';
	return '';
}
add_shortcode( 'hpm_newsletter', 'hpm_newsletter_bug' );

remove_shortcode( 'gallery', 'gallery_shortcode' );
add_shortcode( 'gallery', 'hpm_splide_gallery' );

function hpm_splide_gallery( $attr ): string {
	global $post;
	$output = '';

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	// extract the shortcode attributes into the current variable space
	extract( shortcode_atts([
		// standard WP [gallery] shortcode options
		'order'        => 'ASC',
		'orderby'      => 'menu_order ID',
		'id'           => $post->ID,
		'itemtag'      => 'dl',
		'icontag'      => 'dt',
		'captiontag'   => 'dd',
		'columns'      => 3,
		'size'         => 'thumbnail',
		'include'      => '',
		'exclude'      => '',
		'ids'          => ''
	], $attr ) );

	// the id of the current post, or a different post if specified in the shortcode
	$id = intval( $id );

	// random MySQL ordering doesn't need two attributes
	if ( $order == 'RAND' ) {
		$orderby = 'none';
	}

	// use the given IDs of images
	if ( !empty( $ids ) ) {
		$include = $ids;
	}

	// fetch the images
	if ( !empty( $include ) ) {
		// include only the given image IDs
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( [ 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ] );
		$attachments = [];
		foreach ( $_attachments as $val ) {
			$attachments[ $val->ID ] = $val;
		}
		if ( !empty( $ids ) ) {
			$sortedAttachments = [];
			$ids = preg_replace( '/[^0-9,]+/', '', $ids );
			$idsArray = explode( ',', $ids );
			foreach ( $idsArray as $aid ) {
				if ( array_key_exists( $aid, $attachments ) ) {
					$sortedAttachments[ $aid ] = $attachments[ $aid ];
				}
			}
			$attachments = $sortedAttachments;
		}
	} elseif ( !empty( $exclude ) ) {
		// exclude certain image IDs
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( [ 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ] );
	} else {
		// default: all images attached to this post/page
		$attachments = get_children( [ 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ] );
	}

	// output nothing if we didn't find any images
	if ( empty( $attachments ) ) {
		return $output;
	}

	// output the individual images when displaying as a news feed
	if ( is_feed() ) {
		$output .= "\n";
		foreach ( $attachments as $attachmentId => $attachment ) {
			list( $src, $w, $h ) = wp_get_attachment_image_src( $attachmentId, 'medium' );
			$output .= '<img src="' . $src . '" width="' . $w . '" height="' . $h . '">' . "\n";
		}
		return $output;
	}

	if ( amp_is_request() ) {
		add_action( 'amp_post_template_css', function() { ?>
			<script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
			<script async custom-element="amp-fit-text" src="https://cdn.ampproject.org/v0/amp-fit-text-0.1.js"></script>
			<?php
		} );
		$output .= '<amp-carousel class="carousel1" layout="responsive" height="400" width="500" type="slides">';
	} else {
		wp_enqueue_script( 'hpm-splide' );
		wp_enqueue_style( 'hpm-splide-css' );
		$output .= '<figure class="wp-block-image"><div class="splide"><div class="splide__track"><ul class="splide__list">';
	}


	foreach ( $attachments as $attachmentId => $attachment ) {
		$thumb = wp_get_attachment_image_src( $attachmentId, 'medium' );
		$big = wp_get_attachment_image_src( $attachmentId, 'full' );
		$credit = get_post_meta( $attachmentId, '_wp_attachment_source_name', true );
		$link = get_post_meta( $attachmentId, '_wp_attachment_source_url', true );
		if ( !empty( $credit ) && !empty( $link ) ) {
			$mcredit = ' (Photo Credit: <a href="' . $link . '" rel="noopener noreferrer dofollow" target="_blank">' . $credit . '</a>)';
		} elseif ( !empty( $credit ) && empty( $link ) ) {
			$mcredit = ' (Photo Credit: ' . $credit . ')';
		} else {
			$mcredit = '';
		}
		if ( !empty( $attachment->post_excerpt ) ) {
			$description = $attachment->post_excerpt . $mcredit;
		} elseif ( !empty( $attachment->post_title ) ) {
			$description = $attachment->post_title . $mcredit;
		} else {
			$description = $mcredit;
		}
		$alt = str_replace( '"', '&quot;', strip_tags( $description ) );
		if ( amp_is_request() ) {
			$meta = get_post_meta( $attachmentId, '_wp_attachment_metadata', true );
			$output .= '<div class="slide"><amp-img src="' . $thumb[0] . '" layout="responsive" height="' . $meta['height'] . '" width="' . $meta['width'] . '" alt="' . $alt . '"></amp-img><div class="caption">' . $description . '</div></div>';
		} else {
			$output .= '<li class="splide__slide"><a href="' . $big[0] . '" target="_blank" title="Click for full size"><img data-splide-lazy="' . $thumb[0] . '" alt="' . $alt . '"></a><div>' . $description . '</div></li>';
		}
	}
	if ( amp_is_request() ) {
		$output .= '</amp-carousel>';
	} else {
		$output .= '</div></div></ul></figure>';
	}

	return $output;
}

function hpm_waterlines_shortcode(): string {
	$args = [
		'posts_per_page' => -1,
		'ignore_sticky_posts' => 1,
		'post_type' => 'post',
		'post_status' => 'publish',
		'category_name' => 'below-the-waterlines',
		'order' => 'ASC'
	];
	$article = new WP_Query( $args );
	$output = '';
	$c = 0;
	if ( $article->have_posts() ) {
		while ( $article->have_posts() ) {
			$article->the_post();
			$meta = get_post_meta( get_the_ID(), 'hpm_podcast_enclosure', true );
			if ( !empty( $meta ) ) {
				$ep_meta = get_post_meta( get_the_ID(), 'hpm_podcast_ep_meta', true );
				if ( $c == 0 ) {
					$output .= '<div class="podcast-player-wrap">' .
								'<audio id="player" playsinline preload="none">' .
									'<source src="' . $meta['url'] . '" type="audio/mpeg" />' .
								'</audio>' .
							'</div>' .
							'<nav id="pod">' .
								'<div class="pod-playlist">' .
									'<ul>';
				}
				if ( !empty( $ep_meta['title'] ) ) {
					$title = $ep_meta['title'];
				} else {
					$title = get_the_title();
				}
				$output .= '<li' . ( $c == 0 ? ' class="pod-active"' : '' ) . ' data-audio="' . $meta['url'] . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M434.9,219L124.2,35.3C99,20.4,60.3,34.9,60.3,71.8v367.3c0,33.1,35.9,53.1,63.9,36.5l310.7-183.6 C462.6,275.6,462.7,235.3,434.9,219L434.9,219z"></path></svg><p>' . $title . '</p></li>';
				$c++;
			}
		}
		if ( !empty( $output ) ) {
			$output .= '</ul></div></nav>';
		}
	}
	wp_reset_query();
	return $output;
}
add_shortcode( 'hpm_waterlines_pod', 'hpm_waterlines_shortcode' );

function hpm_impact_shortcode(): string {
	$output = '';
	$page_id = get_the_ID() ? get_the_ID() : 0;
	if ( $page_id == 0 ) {
		return $output;
	}
	$args = [
		'post_parent'    => $page_id,
		'post_type'      => 'attachment',
		'post_mime_type' => 'application/pdf',
		'posts_per_page' => -1,
		'post_status'	 => 'inherit'
	];
	$media = new WP_query( $args );
	if ( empty( $media->posts ) ) {
		return $output;
	}
	$output .= '<section class="impact-current">';
	$prev = [];
	foreach ( $media->posts as $k => $m ) {
		if ( $k <= 3 ) {
			$output .= '<div><a href="' . wp_get_attachment_url( $m->ID ) . '"><img src="' . wp_get_attachment_thumb_url( $m->ID ) . '" alt="' . $m->post_excerpt . '">' . $m->post_excerpt . '</a></div>';
		} else {
			$temp = [
				'url' => wp_get_attachment_url( $m->ID ),
				'title' => $m->post_excerpt
			];
			preg_match( '/([0-9]{4})[\-_]([0-9]{2})/', $m->post_name, $match );
			if ( !empty( $match ) ) {
				$prev[ $match[1] ][ $match[2] ] = $temp;
			}
		}
	}
	foreach ( $prev as $pk => $pv ) {
		ksort( $prev[ $pk ] );
	}
	$output .= '</section><section class="impact-archive"><h2>Previous Reports</h2>';
	foreach ( $prev as $pk => $pv ) {
		$output .= '<div><h3>' . $pk . '</h3><ul>';
		foreach ( $pv as $ppv ) {
			$output .= '<li><a href="' . $ppv['url'] . '">' . $ppv['title'] . '</a></li>';
		}
		$output .= '</ul></div>';
	}
	$output .= "</section>";
	return $output;
}
add_shortcode( 'hpm_impact', 'hpm_impact_shortcode' );

function hpm_pull_podcasts_shortcode( $atts ): string {
	$output = '';
	$podcasts = get_option( 'hpm_pull_podcasts' );
	if ( empty( $podcasts ) ) {
		$podcasts = hpm_pull_podcasts_update();
	}
	extract( shortcode_atts( [ 'pod' => '', 'time' => '' ], $atts, 'multilink' ) );
	if ( empty( $pod ) || empty( $podcasts[ $pod ] ) ) {
		return $output;
	}
	$output .= <<<EOT
<article id="{$pod}">
	<header>
		<div class="art-wrap">
			<div class="big-time">{$time}<br /><span>pm</span></div>
			<img src="{$podcasts[ $pod ]['image']}" alt="{$podcasts[ $pod ]['title']} podcast artwork" class="podcast-art" />
		</div>
		<div class="title-wrap">
			<h1>{$podcasts[ $pod ]['title']}</h1>
			<p>{$podcasts[ $pod ]['description']}</p>
		</div>
		<div class="audio-wrap">
			<h3>Listen to the latest episode!<br /><em>{$podcasts[ $pod ]['latest-title']}</em></h3>
			<div>
				<audio class="js-player" id="audio-{$pod}" data-title="{$podcasts[ $pod ]['title']}: {$podcasts[ $pod ]['latest-title']}" controls preload="none">
					<source src="{$podcasts[ $pod ]['latest-audio']}" type="audio/mpeg" />
				</audio>
			</div>
		</div>
	</header>
</article>

EOT;
	return $output;
}
add_shortcode( 'hpm_pull_podcasts', 'hpm_pull_podcasts_shortcode' );

function hpm_pull_podcasts_update(): array {
	$podcasts = get_option( 'hpm_pull_podcasts' );
	if ( empty( $podcasts ) ) {
		$podcasts = [
			'notes-from-america' => [
				'feed' => 'http://feeds.feedburner.com/unitedstatesofanxiety',
				'title' => 'Notes from America with Kai Wright',
				'image' => '',
				'description' => '<em>Notes from America with Kai Wright</em> is a show about the unfinished business of our history and its grip on our future.',
				'latest-audio' => '',
				'latest-title' => ''
			],
			'our-body-politic' => [
				'feed' => 'https://feeds.simplecast.com/_xaPhs1s',
				'title' => 'Our Body Politic',
				'image' => '',
				'description' => 'Created and hosted by award-winning journalist Farai Chideya, <em>Our Body Politic</em> is unapologetically centered on not just how women of color experience the major political events of today, but how they&#039;re impacting those very issues.',
				'latest-audio' => '',
				'latest-title' => ''
			],
			'latino-usa' => [
				'feed' => 'https://latinousa.feeds.futuromedia.org/',
				'title' => 'Latino USA',
				'image' => '',
				'description' => '<em>Latino USA</em> offers insight into the lived experiences of Latino communities and is a window on the current and emerging cultural, political and social ideas impacting Latinos and the nation.',
				'latest-audio' => '',
				'latest-title' => ''
			],
			'embodied' => [
				'feed' => 'https://embodied.feed.wunc.org/',
				'title' => 'Embodied',
				'image' => '',
				'description' => 'Sex and relationships are intimate &mdash; and sometimes intimidating to talk about. In <em>Embodied</em>, host Anita Rao guides us on an exploration of our brains and our bodies that touches down in taboo territory.',
				'latest-audio' => '',
				'latest-title' => ''
			],
			'i-see-u' => [
				'feed' => 'https://www.houstonpublicmedia.org/podcasts/i-see-u/',
				'title' => 'I SEE U with Eddie Robinson',
				'image' => '',
				'description' => 'Hosted by Houston Public Media’s Eddie Robinson, <em>I SEE U</em> explores cultural identity through the stories of people and places that have been transformed by the effects of long-standing biases. Eddie guides fascinating conversations with newsmakers who share their personal histories, their struggles and their triumphs.',
				'latest-audio' => '',
				'latest-title' => ''
			]
		];
	}
	foreach ( $podcasts as $k => $v ) {
		$remote = wp_remote_get( $v['feed'] );
		if ( is_wp_error( $remote ) ) {
			continue;
		}
		$feed = wp_remote_retrieve_body( $remote );
		$dom = simplexml_load_string( $feed );
		$ns = $dom->getNamespaces( true );
		$image = $dom->channel->image->url;
		if ( empty( $image ) ) {
			$temp = $dom->channel->children( $ns['itunes'] )->image;
			foreach ( $temp->attributes() as $tk => $tv ) {
				if ( $tk == 'href' ) {
					$image = $tv;
				}
			}
		}
		$enclose = $dom->channel->item[0]->enclosure;
		foreach ( $enclose->attributes() as $ek => $ev ) {
			if ( $ek == 'url' ) {
				$podcasts[ $k ]['latest-audio'] = $ev->__toString();
			}
		}
		$podcasts[ $k ]['image'] = $image->__toString();
		//$podcasts[ $k ]['description'] = trim( $dom->channel->description );
		$podcasts[ $k ]['latest-title'] = $dom->channel->item[0]->title->__toString();
	}
	update_option( 'hpm_pull_podcasts', $podcasts, false );
	return $podcasts;
}

add_action( 'hpm_pull_podcasts', 'hpm_pull_podcasts_update' );
$timestamp = wp_next_scheduled( 'hpm_pull_podcasts' );
if ( empty( $timestamp ) ) {
	wp_schedule_event( time(), 'hpm_30min', 'hpm_pull_podcasts' );
}

/* **shortcode** */
function hpm_donation_events_shortcode(): string {
    $args = [
        'posts_per_page' => 4,
        'ignore_sticky_posts' => 1,
        'post_type' => 'event',
        'post_status' => 'publish',
        'order' => 'ASC'
    ];
    $article = new WP_Query( $args );
    $output = '';
    if ( $article->have_posts() ) {
        //$output .= '<ul>';
        while ( $article->have_posts() ) {
            $article->the_post();
            $title = get_the_title();
            $summary = strip_tags( get_the_excerpt() );
            //$output .= '<li><div class="box-img"><a href="'.get_the_permalink().'">'.get_the_post_thumbnail('','post-thumbnail').'</a></div><h3 class="text-light-gray">'.$title.'</h3><p>'.$summary.'</p></li>';
            $output .= '<div class="col-sm-3"><div class="opportunity-img"><a href="'.get_the_permalink().'">'.get_the_post_thumbnail('','post-thumbnail').'</a></div><div class="opportunity-block"><h5>'.$title.'</h5><p>'.$summary.'</p><a href="'.get_the_permalink().'" class="btn outline"> View More </a></div></div>';
        }
        if ( !empty( $output ) ) {
            //$output .= '</ul></div>';
        }
    }
    wp_reset_query();
    return $output;
}
add_shortcode( 'hpm_donation_events', 'hpm_donation_events_shortcode' );