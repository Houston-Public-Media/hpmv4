<?php
function hpm_audio_shortcode( $html, $attr ) {
	global $wpdb;
	$post_id = get_post() ? get_the_ID() : 0;
	static $instance = 0;
	$instance++;
	$supported = false;
	$audio_type = '';
	$default_types = wp_get_audio_extensions();
	foreach ( $default_types as $type ) :
		if ( !empty( $attr[ $type ] ) ) :
			$supported = true;
			$audio_type = $type;
		endif;
	endforeach;

	if ( !$supported ) :
		return '&nbsp;';
	endif;

	if ( !empty( $attr['id'] ) ) :
		$audio_id = $attr['id'];
	else :
		$audio_id = $attr['instance'];
	endif;
	$audio_title = 'Listen';
	$audio_url = $attr[ $audio_type ];

	$sg_file = get_post_meta( $post_id, 'hpm_podcast_enclosure', true );
	if ( !empty( $sg_file ) ) :
		$s3_parse = parse_url( $audio_url );
		$s3_path = pathinfo( $s3_parse['path'] );
		$sg_parse = parse_url( $sg_file['url'] );
		$sg_path = pathinfo( $sg_parse['path'] );
		if ( $s3_path['basename'] === $sg_path['basename'] ) :
			$audio_url = $sg_file['url'];
		else :
			$audio_url = str_replace( 'http:', 'https:', $audio_url );
		endif;
	else :
		$audio_url = str_replace( 'http:', 'https:', $audio_url );
	endif;
	if ( strpos( $audio_url, '?' ) === false ) :
		$audio_url .= '?';
	else :
		$audio_url .= '&';
	endif;
	$html = '';
	if ( is_amp_endpoint() || is_feed() ) :
		$html .= '<div class="amp-audio-wrap"><amp-audio width="360" height="33" src="'.$audio_url.'source=amp-article"><div fallback><p>Your browser doesn’t support HTML5 audio</p></div><source type="audio/mpeg" src="'.$audio_url.'source=amp-article"></amp-audio></div>';
	else :
		if ( is_admin() ) :
			$html .= '<link rel="stylesheet" id="fontawesome-css" href="https://cdn.hpm.io/assets/css/font-awesome.min.css" type="text/css" media="all"><link rel="stylesheet" id="hpmv2-css" href="https://cdn.hpm.io/assets/css/style.css" type="text/css" media="all"><script type="text/javascript" src="/wp/wp-includes/js/jquery/jquery.js"></script><script type="text/javascript" src="https://cdn.hpm.io/assets/js/jplayer/jquery.jplayer.min.js"></script>';
		else :
			wp_enqueue_script( 'jplayer' );
		endif;
		if ( in_array( 'small', $attr ) ) :
			$player_class = 'jp-audio jp-float';
		else :
			$player_class = 'jp-audio';
		endif;
		$html .= "
<div id=\"jquery_jplayer_{$audio_id}\" class=\"jp-jplayer\"></div>
<div id=\"jp_container_{$audio_id}\" class=\"{$player_class}\" role=\"application\" aria-label=\"media player\">
	<div class=\"jp-type-single\">
		<div class=\"jp-gui jp-interface\">
			<div class=\"jp-controls\">
				<button class=\"jp-play\" role=\"button\" tabindex=\"0\">
					<span class=\"fa fa-play\" aria-hidden=\"true\"></span>
				</button>
				<button class=\"jp-pause\" role=\"button\" tabindex=\"0\">
					<span class=\"fa fa-pause\" aria-hidden=\"true\"></span>
				</button>
			</div>
			<div class=\"jp-progress-wrapper\">
				<div class=\"jp-progress\">
					<div class=\"jp-seek-bar\">
						<div class=\"jp-play-bar\"></div>
					</div>
				</div>
				<div class=\"jp-details\">
					<div class=\"jp-title\" aria-label=\"title\">&nbsp;</div>
				</div>
				<div class=\"jp-time-holder\">
					<span class=\"jp-current-time\" role=\"timer\" aria-label=\"time\"></span> /<span class=\"jp-duration\" role=\"timer\" aria-label=\"duration\"></span>
				</div>
			</div>
		</div>
	</div>";
	if ( !is_admin() && !empty( $attr['id'] ) ) :
		$html .= "
	<a href=\"#\" class=\"jp-audio-embed\"><span class=\"fa fa-code\"></span></a>
	<div class=\"jp-audio-embed-popup\" id=\"jp_container_{$audio_id}-popup\">
		<div class=\"jp-audio-embed-wrap\">
			<p>To embed this piece of audio in your site, please use this code:</p>
			<div class=\"jp-audio-embed-code\">
				&lt;iframe src=\"https://embed.hpm.io/{$audio_id}/{$post_id}\" style=\"height: 115px; width: 100%;\"&gt;&lt;/iframe&gt;
			</div>
			<div class=\"jp-audio-embed-close\">X</div>
		</div>
	</div>";
	endif;
	$html .= "
</div>
<div class=\"screen-reader-text\">
	<script type=\"text/javascript\">
		jQuery(document).ready(function($){
			$(\"#jquery_jplayer_{$audio_id}\").jPlayer({
				ready: function () {
					$(this).jPlayer(\"setMedia\", {
						title: \"".htmlentities( wp_trim_words( $audio_title, 10, '...' ), ENT_COMPAT | ENT_HTML5, 'UTF-8', false )."\",
						mp3: \"{$audio_url}source=jplayer-article\"
					});
				},
				swfPath: \"https://cdn.hpm.io/assets/js/jplayer\",
				supplied: \"mp3\",
				preload: \"metadata\",
				cssSelectorAncestor: \"#jp_container_{$audio_id}\",
				wmode: \"window\",
				useStateClassSkin: true,
				autoBlur: false,
				smoothPlayBar: true,
				keyEnabled: true,
				remainingDuration: false,
				toggleDuration: true
			});";
		if ( !is_admin() ) :
			$html .= "
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.play, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					ga('send', 'event', 'jPlayer', 'Play', mediaName, playerTime);
					ga('hpmRollup.send', 'event', 'jPlayer', 'Play', mediaName, playerTime);
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.pause, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					if (playerTime<100) {
						ga('send', 'event', 'jPlayer', 'Pause', mediaName, playerTime);
						ga('hpmRollup.send', 'event', 'jPlayer', 'Pause', mediaName, playerTime);
					}
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.seeking, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					ga('send', 'event', 'jPlayer', 'Seeking', mediaName, playerTime);
					ga('hpmRollup.send', 'event', 'jPlayer', 'Seeking', mediaName, playerTime);
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.seeked, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					if (playerTime>0) {
						ga('send', 'event', 'jPlayer', 'Seeked', mediaName, playerTime);
						ga('hpmRollup.send', 'event', 'jPlayer', 'Seeked', mediaName, playerTime);
					} else {
						ga('send', 'event', 'jPlayer', 'Stopped', mediaName, playerTime);
						ga('hpmRollup.send', 'event', 'jPlayer', 'Stopped', mediaName, playerTime);
					}
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.ended, function(event) {
					var playerTime = 100;
					var mediaName = event.jPlayer.status.src;
					ga('send', 'event', 'jPlayer', 'Ended', mediaName, playerTime);
					ga('hpmRollup.send', 'event', 'jPlayer', 'Ended', mediaName, playerTime);
				}
			);";
		endif;
		$html .= "
		});
	</script>
</div>";
	endif;
	return $html;
}

add_filter( 'wp_audio_shortcode_override', 'hpm_audio_shortcode', 10, 2 );

function hpm_nprapi_audio_shortcode( $text ) {
	$matches = [];
	preg_match_all( '/' . get_shortcode_regex() . '/', $text, $matches );

	$tags = $matches[2];
	$args = $matches[3];
	foreach( $tags as $i => $tag ) :
		if ( $tag == "audio" ) :
			$atts = shortcode_parse_atts( $args[$i] );
			if ( !empty( $atts['mp3'] ) ) :
				$a_tag = '<figure><figcaption>Listen to the story audio:</figcaption><audio controls src="' . $atts['mp3'] . '">Your browser does not support the <code>audio</code> element.</audio></figure>';
				$text = str_replace( '<p>'.$matches[0][$i].'</p>', $a_tag, $text );
				$text = str_replace( $matches[0][$i], $a_tag, $text );
			endif;
		endif;
	endforeach;
	return $text;
}
add_filter( 'npr_ds_shortcode_filter', 'hpm_nprapi_audio_shortcode', 10, 1 );

function hpm_apple_news_audio( $text ) {
	$matches = [];
	preg_match_all( '/' . get_shortcode_regex() . '/', $text, $matches );

	$tags = $matches[2];
	$args = $matches[3];
	foreach( $tags as $i => $tag ) :
		if ( $tag == "audio" ) :
			$atts = shortcode_parse_atts( $args[$i] );
			if ( !empty( $atts['mp3'] ) ) :
				$a_tag = '<p><audio controls src="' . $atts['mp3'] . '"></audio></p>';
				$text = str_replace( '<p>'.$matches[0][$i].'</p>', $a_tag, $text );
				$text = str_replace( $matches[0][$i], $a_tag, $text );
			endif;
		endif;
	endforeach;
	log_it( $text );
	return $text;
}
add_filter( 'apple_news_exporter_content_pre', 'hpm_apple_news_audio', 10, 1 );


add_filter( 'media_send_to_editor', 'hpm_audio_shortcode_insert', 10, 8 );
function hpm_audio_shortcode_insert ( $html, $id, $attachment ) {
	if ( strpos( $html, '[audio' ) !== FALSE ) :
		$html = str_replace( '][/audio]', ' id="'.$id.'"][/audio]', $html );
	endif;
	return $html;
}


function article_display_shortcode( $atts ) {
	global $hpm_constants;
	if ( empty( $hpm_constants ) ) :
		$hpm_constants = [];
	endif;
	$article = [];
	extract( shortcode_atts( [
		'num' => 1,
		'tag' => '',
		'category' => '',
		'type' => 'd',
		'post_id' => ''
	], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	];
	if ( !empty( $hpm_constants ) ) :
		$args['post__not_in'] = $hpm_constants;
	endif;
	if ( !empty( $category ) ) :
		$args['category_name'] = $category;
	endif;
	if ( !empty( $tag ) ) :
		$args['tag_slug__in'][] = $tag;
	endif;
	if ( !empty( $post_id ) ) :
		$i_exp = explode( ',', $post_id );
		foreach ( $i_exp as $ik => $iv ) :
			$i_exp[$ik] = trim( $iv );
		endforeach;
		$args['post__in'] = $i_exp;
		$args['orderby'] = 'post__in';
		$c = count( $i_exp );
		if ( $c != $args['posts_per_page'] ) :
			$diff = $args['posts_per_page'] - $c;
			$args['posts_per_page'] = $c;
			unset( $args['category_name'] );
			$article[] = new WP_Query( $args );
			unset( $args['post__in'] );
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
			$args['post__not_in'] = array_merge( $hpm_constants, $i_exp );
			$args['posts_per_page'] = $diff;
			if ( !empty( $category ) ) :
				$args['category_name'] = $category;
			endif;
		endif;
	endif;
	$article[] = new WP_query( $args );
	$output = '<div class="grid-sizer"></div>';
	ob_start();
	foreach ( $article as $art ) :
		if ( $art->have_posts() ) :
			while ( $art->have_posts() ) : $art->the_post();
				if ( $type == 'search' ) :
					get_template_part( 'content', get_post_format() );
				else :
					$postClass = get_post_class();
					$fl_array = preg_grep("/felix-type-/", $postClass);
					$fl_arr = array_keys( $fl_array );
					$postClass[$fl_arr[0]] = 'felix-type-'.$type;
					$postClass[] = 'grid-item';
					if ( $type == 'a' ) :
						$thumbnail_type = 'large';
						$postClass[] = 'grid-item--width2';
					elseif ( $type == 'b' ) :
						$thumbnail_type = 'thumbnail';
						$postClass[] = 'grid-item--width2';
					else :
						$thumbnail_type = 'thumbnail';
					endif;
					$hpm_constants[] = get_the_ID();
					$overline = hpm_top_cat( get_the_ID() );
					$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), $thumbnail_type ).')"><a class="post-thumbnail" href="'.get_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><h3>'.$overline.'</h3><h2 class="entry-title"><a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a></h2></header></article>';
				endif;
			endwhile;
		endif;
	endforeach;
	wp_reset_query();
	$getContent = ob_get_contents();
	ob_end_clean();
	if ( $type == 'search' ) :
		$output = $getContent;
	endif;
	return $output;
}
add_shortcode( 'hpm_articles', 'article_display_shortcode' );

function article_display_shortcode_temp( $atts ) {
	global $hpm_constants;
	if ( empty( $hpm_constants ) ) :
		$hpm_constants = [];
	endif;
	$article = [];
	extract( shortcode_atts( [
		'num' => 1,
		'tag' => '',
		'category' => '',
		'type' => 'd',
		'overline' => '',
		'post_id' => ''
	], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	];
	if ( !empty( $hpm_constants ) ) :
		$args['post__not_in'] = $hpm_constants;
	endif;
	if ( !empty( $category ) ) :
		$args['category_name'] = $category;
	endif;
	if ( !empty( $tag ) ) :
		$args['tag_slug__in'][] = $tag;
	endif;
	if ( !empty( $post_id ) ) :
		$i_exp = explode( ',', $post_id );
		foreach ( $i_exp as $ik => $iv ) :
			$i_exp[$ik] = trim( $iv );
		endforeach;
		$args['post__in'] = $i_exp;
		$args['orderby'] = 'post__in';
		$c = count( $i_exp );
		if ( $c != $args['posts_per_page'] ) :
			$diff = $args['posts_per_page'] - $c;
			$args['posts_per_page'] = $c;
			unset( $args['category_name'] );
			$article[] = new WP_Query( $args );
			unset( $args['post__in'] );
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
			$args['post__not_in'] = array_merge( $hpm_constants, $i_exp );
			$args['posts_per_page'] = $diff;
			if ( !empty( $category ) ) :
				$args['category_name'] = $category;
			endif;
		endif;
	endif;
	$article[] = new WP_query( $args );
	$output = '<div class="grid-sizer"></div>';
	foreach ( $article as $art ) :
		if ( $art->have_posts() ) :
			while ( $art->have_posts() ) : $art->the_post();
				ob_start();
				get_template_part( 'content', get_post_format() );
				$var = ob_get_contents();
				ob_end_clean();
				$output .= $var;
				$hpm_constants[] = get_the_ID();
			endwhile;
		endif;
	endforeach;
	return $output;
}
add_shortcode( 'hpm_articles_temp', 'article_display_shortcode_temp' );

function article_list_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'num' => 1,
		'category' => '',
		'tag' => '',
		'post_id' => ''
	), $atts, 'multilink' ) );
	$args = array(
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	);
	$extra = '';
	if ( !empty( $category ) ) :
		$args['category_name'] = $category;
		$extra = '<li><a href="/topics/'.$category.'/">Read More...</a></li>';
	endif;
	if ( !empty( $tag ) ) :
		$args['tag_slug__in'][] = $tag;
	endif;
	if ( !empty( $post_id ) ) :
		$args['p'] = $post_id;
		$args['posts_per_page'] = 1;
	endif;
	$article = new WP_query( $args );
	$output = '<ul>';
	if ( $article->have_posts() ) :
		while ( $article->have_posts() ) : $article->the_post();
			$output .= '<li><a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a></li>';
		endwhile;
		$output .= $extra;
	else :
		$output .= "<li>Coming Soon</li>";
	endif;
	$output .= '</ul>';
	return $output;
}
add_shortcode( 'hpm_article_list', 'article_list_shortcode' );

function hpm_npr_article_shortcode( $atts ) {
	extract( shortcode_atts( [
		'category' => 1001,
		'num' => 4
	], $atts, 'multilink' ) );
	$npr = get_transient( 'hpm_nprapi_'.$category );
	if ( !empty( $npr ) ) :
		return $npr;
	endif;
	$output = '';
	$remote = wp_remote_get( esc_url_raw( "https://api.npr.org/query?id=".$category."&fields=title,teaser,image,storyDate&requiredAssets=image,audio,text&startNum=0&dateType=story&output=JSON&numResults=4&apiKey=MDAyMTgwNzc5MDEyMjQ4ODE4MjMyYTExMA001" ) );
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
	set_transient( 'hpm_nprapi_'.$category, $output, 600 );
	return $output;
}
add_shortcode( 'hpm_npr_articles', 'hpm_npr_article_shortcode' );

/**
 * Cron job for updating at-home learning page schedule
 */
function hpm_athome_sched_update() {
	// Pull cached transient from Redis
	$output = get_transient( 'hpm_athome_sched' );
	/**
	 *  If WP_Debug is enabled, ignore the transient and regenerate
	 *  If the transient is not empty, serve it, otherwise move forward
	 */
	if ( WP_DEBUG ) :
		$output = '';
	else :
		if ( !empty( $output ) ) :
			return $output;
		endif;
	endif;

	// Determine the current time in GMT and adjust to timezone
	$t = time();
	$t = mktime( 0, 0, 0, 6, 15, 2020 );
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$cutoff = mktime( 0, 0, 0, 6, 14, 2020 ) + $offset;
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
	$temp = [
		'8.1' => '',
		'8.4' => ''
	];
	// Time columns to put on the front and back of the schedules
	// Could probably script this but I was in a hurry
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
	if ( $now['wday'] >= 1 && $now['wday'] <= 5 ) :
		$monday_unix = ( $now[0] - ( ( $now['wday'] - 1 ) * 86400 ) );
	elseif ( $now['wday'] == 0 ) :
		$monday_unix = ( $now[0] + 86400 );
	elseif ( $now['wday'] == 6 ) :
		$monday_unix = ( $now[0] + ( 2 * 86400 ) );
	endif;

	// Loop through week data structure and determine dates and Unix times for each day
	$week[1]['date'] = date( "Ymd" , $monday_unix );
	$week[1]['date_unix'] = $monday_unix;
	for ( $i = 2; $i < 6; $i++ ) :
		$week[$i]['date'] = date( "Ymd" , $monday_unix + ( ( $i - 1 ) * 86400 ) );
		$week[$i]['date_unix'] = $monday_unix + ( ( $i - 1 ) * 86400 );
	endfor;

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
	$url_base = "https://services.pbs.org/tvss/kuht/";


	/**
	 * Loop though the week and pull the actual schedule data
	 * 8.1 is our main public channel, and 8.4 is our WORLD broadcast
	*/
	foreach ( $week as $k => $w ) :
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
	endforeach;

	// Build the head of each schedule and put it into our temp array
	$temp['8.1'] = '<div class="lah-schedule"><h2>Channel 8.1 At-Home Learning Schedule with Links to Learning Resources</h2><h3>Week of ' . date( 'F j, Y', $monday_unix ) . '</h3><div class="lah-legend"><div class="lah-legend-young"><span></span> Grades PreK-3</div><div class="lah-legend-middle"><span></span> Grades 4-8</div><div class="lah-legend-high"><span></span> Grades 9-12</div></div><div class="lah-wrap">'.$timecol['8.1'];
	$temp['8.4'] = '<div class="lah-schedule"><h2>Channel 8.4 At-Home Learning Schedule with Links to Learning Resources</h2><h3>Week of ' . date( 'F j, Y', $monday_unix ) . '</h3><div class="lah-legend"><div class="lah-legend-science"><span></span> Science</div><div class="lah-legend-sstudies"><span></span> Social Studies</div><div class="lah-legend-ela"><span></span> English/Language Arts</div><div class="lah-legend-math"><span></span> Math</div></div><div class="lah-wrap">'.$timecol['8.4'];



	/**
	 * Wheels within wheels, my friend. Well, loops within loops
	 * Loops through the days in the week
	 */
	foreach ( $week as $w ) :
		// Loop through the daily data for each channel
		foreach ( $w['data'] as $dk => $dv ) :
			// Setup the header for each daily column
			$temp[ $dk ] .= '<div class="lah-col lah-' . strtolower( $w['name'] ) . '"><div class="lah-col-head">' . $w['name'] . '<br />' . date( 'm/d/Y', $w['date_unix'] ) . '</div>';
			// Loop through each entry of the current day
			foreach ( $dv as $pv ) :
				// Determine if the program is in the right timeframe for the channel
				if (
					( $dk === '8.1' && $pv['start_time'] >= 600 && $pv['start_time'] < 1800 ) ||
					( $dk === '8.4' && $pv['start_time'] >= 1100 && $pv['start_time'] < 1600 )
				) :
					// Set up a generic CSS class
					$class = 'lah-' . $pv['minutes'];
					/**
					 * Modify CSS class to reflect grade level on main channel
					 * This is mostly based on timeframes but there might be some wiggle
					 */
					if ( $dk === '8.1' ) :
						if ( $monday_unix > $cutoff ) :
							if ( $pv['start_time'] >= 600 && $pv['start_time'] < 1200 ) :
								$class .= ' lah-young';
							elseif ( $pv['start_time'] >= 1200 && $pv['start_time'] < 1500 ) :
								$class .= ' lah-middle';
							elseif ( $pv['start_time'] >= 1500 ) :
								$class .= ' lah-high';
							endif;
						else :
							if ( $pv['start_time'] >= 600 && $pv['start_time'] < 800 ) :
								$class .= ' lah-young';
							elseif ( $pv['start_time'] >= 800 && $pv['start_time'] < 1300 ) :
								$class .= ' lah-middle';
							elseif ( $pv['start_time'] >= 1300 ) :
								$class .= ' lah-high';
							endif;
						endif;

					/**
					 * Modify CSS class to reflect subject matter on WORLD channel
					 * This is partially based on timeframe, but ELA gets preempted at least 2 days a week in favor of social studies
					 * Had to make some best guesses, and am looking ahead at the schedule to adjust the exemptions
					 */
					elseif ( $dk === '8.4' ) :
						if ( $pv['start_time'] < 1300 ) :
							if (
								preg_match( '/Math/', $pv['title'] )
							) :
								$class .= ' lah-math';
							else :
								$class .= ' lah-science';
							endif;
						elseif ( $pv['start_time'] >= 1300 ) :
							if ( $pv['title'] == 'American Masters' || $pv['title'] == 'Poetry in America' || $pv['title'] == 'Great Performances' ) :
								$class .= ' lah-ela';
							elseif ( preg_match( '/Amazing Human Body/', $pv['title'] ) ) :
								$class .= ' lah-science';
							else :
								$class .= ' lah-sstudies';
							endif;
						/* elseif ( $pv['start_time'] >= 1300 && $pv['start_time'] < 1500 ) :
							$class .= ' lah-sstudies';
						elseif ( $pv['start_time'] >= 1500 ) :
							if (
								preg_match( '/John Lewis/', $pv['title'] ) ||
								preg_match( '/Rick Steves/', $pv['title'] ) ||
								preg_match( '/John Lewis/', $pv['title'] ) ||
								preg_match( '/Tiananmen/', $pv['title'] ) ||
								preg_match( '/American Experience/', $pv['title'] ) ||
								preg_match( '/Shanghai/', $pv['title'] ) ||
								preg_match( '/Summoned/', $pv['title'] ) ||
								preg_match( '/Cuban/', $pv['title'] ) ||
								preg_match( '/Shanghai/', $pv['title'] ) ||
								preg_match( '/Africa/', $pv['title'] )
							) :
								$class .= ' lah-sstudies';
							else :
								$class .= ' lah-ela';
							endif; */
						endif;
					endif;
					// Create the schedule entries and concatenate them onto the temp schedule
					$temp[ $dk ] .= '<div class="' . $class . '"><a title="' . $pv['title'] . ' Episode Information" href="./resources/#s'. date( 'w-', $w['date_unix'] ) . $pv['start_time'] . '-' . $dk . '">' . wp_trim_words( $pv['title'], 9, '&hellip;' ) . '</a></div>';
				endif;
			endforeach;
			// Close out the column
			$temp[ $dk ] .= '</div>';
		endforeach;
	endforeach;
	// Close out the temp schedules
	$temp['8.1'] .= $timecol['8.1'] . '</div></div>';
	$temp['8.4'] .= $timecol['8.4'] . '</div></div>';

	/**
	 * Concatenate the channel schedules along with a hidden time stamp
	 * Makes it easier to ensure that the cron job is running and the schedule is updating
	*/
	$output = $temp['8.1'] . $temp['8.4'] . '<p style="display: none;">Last Update: ' . date( 'Y/m/d H:i:s', $t ) . '</p>';
	// Save the output as a site transient in Redis and output
	set_transient( 'hpm_athome_sched', $output, 7200 );
	return $output;
}
add_shortcode( 'hpm_athome', 'hpm_athome_sched_update' );

add_action( 'hpm_athome_update', 'hpm_athome_sched_update' );
$timestamp = wp_next_scheduled( 'hpm_athome_update' );
if ( empty( $timestamp ) ) :
	wp_schedule_event( time(), 'hourly', 'hpm_athome_update' );
endif;

/**
 * Cron job for updating at-home learning page schedule
 */
function hpm_artspace_trans() {
	return get_transient( 'hpm_artspace' );

}
add_shortcode( 'hpm_artspace', 'hpm_artspace_trans' );