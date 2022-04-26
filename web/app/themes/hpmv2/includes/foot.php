<?php
function author_footer( $id ) {
	$output = '';
	$coauthors = get_coauthors( $id );
	foreach ( $coauthors as $k => $coa ) :
		$temp = '';
		$author_trans = get_transient( 'hpm_author_'.$coa->user_nicename );
		if ( !empty( $author_trans ) ) :
			$output .= $author_trans;
			continue;
		endif;
		$local = false;
		if ( is_a( $coa, 'wp_user' ) ) :
			$author = new WP_Query( [
				'post_type' => 'staff',
				'post_status' => 'publish',
				'meta_query' => [ [
					'key' => 'hpm_staff_authid',
					'compare' => '=',
					'value' => $coa->ID
				] ]
			] );
		elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) :
			if ( !empty( $coa->linked_account ) ) :
				$authid = get_user_by( 'login', $coa->linked_account );
				$author = new WP_Query( [
					'post_type' => 'staff',
					'post_status' => 'publish',
					'meta_query' => [ [
						'key' => 'hpm_staff_authid',
						'compare' => '=',
						'value' => $authid->ID
					] ]
				] );
			endif;
		endif;
		if ( !empty( $author ) && $author->have_posts() ) :
			$local = true;
			$meta = $author->post->hpm_staff_meta;
		endif;
		$temp .= "
	<div class=\"author-inner-wrap\">
		<div class=\"author-info-wrap\">
			<div class=\"author-image\">" .
		         ( $local ? get_the_post_thumbnail( $author->post->ID, 'post-thumbnail', [ 'alt' => $author->post->post_title ] ) : '' ) .
		         "</div>
			<div class=\"author-info\">
				<h2>" . ( $local ? $author->post->post_title : $coa->display_name ) . "</h2>
				<h3>" . ( $local ? $meta['title'] : '' ) . "</h3>
				<div class=\"author-social\">";
		if ( $local ) :
			if ( !empty( $meta['facebook'] ) ) :
				$temp .= '<div class="social-icon"><a href="'.$meta['facebook'].'" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a></div>';
			endif;
			if ( !empty( $meta['twitter'] ) ) :
				$temp .= '<div class="social-icon"><a href="'.$meta['twitter'].'" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a></div>';
			endif;
			if ( !empty( $meta['linkedin'] ) ) :
				$temp .= '<div class="social-icon"><a href="'.$meta['linkedin'].'" target="_blank"><span class="fab fa-linkedin-in" aria-hidden="true"></span></a></div>';
			endif;
			if ( !empty( $meta['email'] ) ) :
				$temp .= '<div class="social-icon"><a href="mailto:'.$meta['email'].'" target="_blank"><span class="fas fa-envelope" aria-hidden="true"></span></a></div>';
			endif;
			$author_bio = $author->post->post_content;
			if ( preg_match( '/Biography pending/', $author_bio ) ) :
				$author_bio = '';
			endif;
		else :
			if ( !empty( $coa->user_email ) ) :
				$temp .= '<div class="social-icon"><a href="mailto:'.$coa->user_email.'" target="_blank"><span class="fas fa-envelope" aria-hidden="true"></span></a></div>';
			endif;
			if ( !empty( $coa->website ) ) :
				$temp .= '<div class="social-icon"><a href="'.$coa->website.'" target="_blank"><span class="fas fa-home" aria-hidden="true"></span></a></div>';
			endif;
		endif;
		$temp .= "
				</div>
				<p>" . ( $local ? wp_trim_words( $author_bio, 50, '...' ) : '' ) . "</p>
				<p>" . ( $local ? '<a href="' . get_the_permalink( $author->post->ID ) . '">More Information</a>' : '' ) ."</p>
			</div>
		</div>
		<div class=\"author-other-stories\">";
		$q = new WP_query([
			'posts_per_page' => 4,
			'post_type' => 'post',
			'post_status' => 'publish',
			'author_name' => $coa->user_nicename
		]);
		if ( $q->have_posts() ) :
			$temp .= "
			<h4>Recent Stories</h4>
			<ul>";
			foreach ( $q->posts as $qp ) :
				$temp .= '<li><h2 class="entry-title"><a href="'.esc_url( get_permalink( $qp->ID ) ).'" rel="bookmark">'.$qp->post_title.'</a></h2></li>';
			endforeach;
			$temp .= "
			</ul>
			<p><a href=\"/articles/author/".$coa->user_nicename."\">More Articles by This Author</a></p>";
		endif;
		$temp .= "
		</div>
	</div>";
		set_transient( 'hpm_author_'.$coa->user_nicename, $temp, 7200 );
		$output .= $temp;
	endforeach;
	return $output;
}

function hpm_houston_matters_check() {
	$hm_air = get_transient( 'hpm_hm_airing' );
	if ( !empty( $hm_air ) ) :
		return $hm_air;
	endif;
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$date = date( 'Y-m-d', $t );
	$hm_airtimes = [
		9 => false,
		15 => false
	];
	$remote = wp_remote_get( esc_url_raw( "https://api.composer.nprstations.org/v1/widget/519131dee1c8f40813e79115/day?date=".$date."&format=json" ) );
	if ( is_wp_error( $remote ) ) :
		return false;
	else :
		$api = wp_remote_retrieve_body( $remote );
		$json = json_decode( $api, TRUE );
		foreach ( $json['onToday'] as $j ) :
			if ( $j['program']['name'] == 'Houston Matters with Craig Cohen' ) :
				if ( $j['start_time'] == '09:00' ) :
					$hm_airtimes[9] = true;
				endif;
			elseif ( $j['program']['name'] == 'Town Square with Ernie Manouse' ) :
				if ( $j['start_time'] == '15:00' ) :
					$hm_airtimes[15] = true;
				endif;
			endif;
		endforeach;
	endif;
	set_transient( 'hpm_hm_airing', $hm_airtimes, 3600 );
	return $hm_airtimes;
}

function hpm_chartbeat() {
	global $wp_query;
	$id = $wp_query->get_queried_object_id();
	$anc = get_post_ancestors( $id );
	if ( !in_array( 61383, $anc ) && WP_ENV !== 'development' ) :
		$auth = get_coauthors( $id );
		$auth_temp = [];
		$authors = '';
		if ( empty( $auth ) || is_front_page() ) :
			$authors = 'Houston Public Media';
		else :
			foreach ( $auth as $a ) :
				$auth_temp[] = $a->display_name;
			endforeach;
			$authors = implode( ', ', $auth_temp );
		endif; ?>
		<script type='text/javascript'>
			var _sf_async_config={};
			/** CONFIGURATION START **/
			_sf_async_config.uid = 33583;
			_sf_async_config.domain = 'houstonpublicmedia.org';
			_sf_async_config.useCanonical = true;
			_sf_async_config.sections = "<?php echo ( is_front_page() ? 'News, Arts & Culture, Education' : str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', $id ) ) ) );
			?>";
			_sf_async_config.authors = "<?php echo $authors; ?>";
			(function(){
				function loadChartbeat() {
					window._sf_endpt=(new Date()).getTime();
					var e = document.createElement('script');
					e.setAttribute('language', 'javascript');
					e.setAttribute('type', 'text/javascript');
					e.setAttribute('src', '//static.chartbeat.com/js/chartbeat.js');
					document.body.appendChild(e);
				}
				var oldonload = window.onload;
				window.onload = (typeof window.onload != 'function') ?
					loadChartbeat : function() { oldonload(); loadChartbeat(); };
			})();
		</script>
<?php
	endif;
}

function hpm_hm_banner() {
	wp_reset_query();
	global $wp_query;
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	if ( !empty( $_GET['testtime'] ) ) :
		$tt = explode( '-', $_GET['testtime'] );
		$now = getdate( mktime( $tt[0], $tt[1], 0, $tt[2], $tt[3], $tt[4] ) );
	endif;
	$anc = get_post_ancestors( get_the_ID() );
	$bans = [ 135762, 290722, 303436, 303018, 315974 ];
	$hm_air = hpm_houston_matters_check();
	if ( empty( $wp_query->post ) ) :
		return '';
	endif;
	if ( !in_array( 135762, $anc ) && !in_array( get_the_ID(), $bans ) && $wp_query->post->post_type !== 'embeds' ) :
		if ( ( $now['wday'] > 0 && $now['wday'] < 6 ) && ( $now['hours'] == 9 || $now['hours'] == 15 ) && $hm_air[ $now['hours'] ] ) : ?>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var topBanner = document.getElementById('hm-top');
			topBanner.innerHTML = '<?php echo ( $now['hours'] == 15 ? '<p><span><a href="/listen-live/"><strong>Town Square</strong> is on the air now!</a> Join the conversation:</span> Call <strong>888.486.9677</strong> | Email <a href="mailto:talk@townsquaretalk.org">talk@townsquaretalk.org</a> | <a href="/listen-live/">Listen Live</a></p>': '<p><span><a href="/listen-live/"><strong>Houston Matters</strong> is on the air now!</a> Join the conversation:</span> Call <strong>713.440.8870</strong> | Email <a href="mailto:talk@houstonmatters.org">talk@houstonmatters.org</a> | <a href="/listen-live/">Listen Live</a></p>' ); ?>';
			<?php echo ( $now['hours'] == 15 ? 'topBanner.classList.add(\'townsquare\');' : '' ); ?>
			for (i = 0; i < topBanner.length; ++i) {
				topBanner[i].addEventListener('click', function() {
					var attr = this.id;
					if ( typeof attr !== typeof undefined && attr !== false) {
						gaAll('send', 'event', 'Top Banner', 'click', attr);
					}
				});
			}
		});
	</script>
<?php
		endif;
	endif;
}
/* **
 * Persistent Player Setup
 */
/* add_action( 'wp_head', function() {
	if ( $_SERVER['HTTP_X_ORIGINAL_HOST'] !== 'jcounts.ngrok.io' && !is_admin() ) :
		echo '<script type="module"> import hotwiredTurbo from \'https://cdn.skypack.dev/@hotwired/turbo\'; </script>';
		wp_enqueue_script( 'hpm-jpp', get_template_directory_uri().'/js/jppTurbo.js', [ 'hpm-plyr' ], date('Y-m-d-H') );
		wp_enqueue_style( 'hpm-persistent', get_template_directory_uri().'/js/persistent.css', [], date('Y-m-d-H') );
	endif;
}, 100 ); */
function hpm_persistent_player() {
	if ( $_SERVER['HTTP_X_ORIGINAL_HOST'] !== 'jcounts.ngrok.io' && !is_admin() ) : ?>
		<div id="jpp-player-persist" data-turbo-permanent>
			<div id="jpp-player-wrap"><audio id="jpp-player" controls crossorigin playsinline preload="none"></audio></div>
			<div id="jpp-menu-wrap">
				<aside id="jpp-menu">
					<button data-section="streams" id="jpp-button-streams" class="jpp-menu-section jpp-button-active">Streams</button>
					<button data-section="podcasts" id="jpp-button-podcasts" class="jpp-menu-section">Podcasts</button>
				</aside>
				<div id="jpp-submenus">
					<aside id="jpp-streams" class="jpp-section-active"></aside>
					<aside id="jpp-podcasts"></aside>
				</div>
				<div id="jpp-now-playing">Now Playing: Nothing yet...</div>
			</div>
			<div id="jpp-button-wrap">
				<button id="jpp-button-menu"><span class="fas fa-bars"></span></button>
			</div>
			<div id="sprite-plyr" hidden=""><!--?xml version="1.0" encoding="UTF-8"?--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><symbol id="plyr-airplay" viewBox="0 0 18 18"><path d="M16 1H2a1 1 0 00-1 1v10a1 1 0 001 1h3v-2H3V3h12v8h-2v2h3a1 1 0 001-1V2a1 1 0 00-1-1z"></path><path d="M4 17h10l-5-6z"></path></symbol><symbol id="plyr-captions-off" viewBox="0 0 18 18"><path d="M1 1c-.6 0-1 .4-1 1v11c0 .6.4 1 1 1h4.6l2.7 2.7c.2.2.4.3.7.3.3 0 .5-.1.7-.3l2.7-2.7H17c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1H1zm4.52 10.15c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41C8.47 4.96 7.46 3.76 5.5 3.76c-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69zm7.57 0c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41c-.28-1.15-1.29-2.35-3.25-2.35-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69z" fill-rule="evenodd" fill-opacity=".5"></path></symbol><symbol id="plyr-captions-on" viewBox="0 0 18 18"><path d="M1 1c-.6 0-1 .4-1 1v11c0 .6.4 1 1 1h4.6l2.7 2.7c.2.2.4.3.7.3.3 0 .5-.1.7-.3l2.7-2.7H17c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1H1zm4.52 10.15c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41C8.47 4.96 7.46 3.76 5.5 3.76c-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69zm7.57 0c1.99 0 3.01-1.32 3.28-2.41l-1.29-.39c-.19.66-.78 1.45-1.99 1.45-1.14 0-2.2-.83-2.2-2.34 0-1.61 1.12-2.37 2.18-2.37 1.23 0 1.78.75 1.95 1.43l1.3-.41c-.28-1.15-1.29-2.35-3.25-2.35-1.9 0-3.61 1.44-3.61 3.7 0 2.26 1.65 3.69 3.63 3.69z" fill-rule="evenodd"></path></symbol><symbol id="plyr-download" viewBox="0 0 18 18"><path d="M9 13c.3 0 .5-.1.7-.3L15.4 7 14 5.6l-4 4V1H8v8.6l-4-4L2.6 7l5.7 5.7c.2.2.4.3.7.3zm-7 2h14v2H2z"></path></symbol><symbol id="plyr-enter-fullscreen" viewBox="0 0 18 18"><path d="M10 3h3.6l-4 4L11 8.4l4-4V8h2V1h-7zM7 9.6l-4 4V10H1v7h7v-2H4.4l4-4z"></path></symbol><symbol id="plyr-exit-fullscreen" viewBox="0 0 18 18"><path d="M1 12h3.6l-4 4L2 17.4l4-4V17h2v-7H1zM16 .6l-4 4V1h-2v7h7V6h-3.6l4-4z"></path></symbol><symbol id="plyr-fast-forward" viewBox="0 0 18 18"><path d="M7.875 7.171L0 1v16l7.875-6.171V17L18 9 7.875 1z"></path></symbol><symbol id="plyr-logo-vimeo" viewBox="0 0 18 18"><path d="M17 5.3c-.1 1.6-1.2 3.7-3.3 6.4-2.2 2.8-4 4.2-5.5 4.2-.9 0-1.7-.9-2.4-2.6C5 10.9 4.4 6 3 6c-.1 0-.5.3-1.2.8l-.8-1c.8-.7 3.5-3.4 4.7-3.5 1.2-.1 2 .7 2.3 2.5.3 2 .8 6.1 1.8 6.1.9 0 2.5-3.4 2.6-4 .1-.9-.3-1.9-2.3-1.1.8-2.6 2.3-3.8 4.5-3.8 1.7.1 2.5 1.2 2.4 3.3z"></path></symbol><symbol id="plyr-logo-youtube" viewBox="0 0 18 18"><path d="M16.8 5.8c-.2-1.3-.8-2.2-2.2-2.4C12.4 3 9 3 9 3s-3.4 0-5.6.4C2 3.6 1.3 4.5 1.2 5.8 1 7.1 1 9 1 9s0 1.9.2 3.2c.2 1.3.8 2.2 2.2 2.4C5.6 15 9 15 9 15s3.4 0 5.6-.4c1.4-.3 2-1.1 2.2-2.4.2-1.3.2-3.2.2-3.2s0-1.9-.2-3.2zM7 12V6l5 3-5 3z"></path></symbol><symbol id="plyr-muted" viewBox="0 0 18 18"><path d="M12.4 12.5l2.1-2.1 2.1 2.1 1.4-1.4L15.9 9 18 6.9l-1.4-1.4-2.1 2.1-2.1-2.1L11 6.9 13.1 9 11 11.1zM3.786 6.008H.714C.286 6.008 0 6.31 0 6.76v4.512c0 .452.286.752.714.752h3.072l4.071 3.858c.5.3 1.143 0 1.143-.602V2.752c0-.601-.643-.977-1.143-.601L3.786 6.008z"></path></symbol><symbol id="plyr-pause" viewBox="0 0 18 18"><path d="M6 1H3c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h3c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1zm6 0c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h3c.6 0 1-.4 1-1V2c0-.6-.4-1-1-1h-3z"></path></symbol><symbol id="plyr-pip" viewBox="0 0 18 18"><path d="M13.293 3.293L7.022 9.564l1.414 1.414 6.271-6.271L17 7V1h-6z"></path><path d="M13 15H3V5h5V3H2a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1v-6h-2v5z"></path></symbol><symbol id="plyr-play" viewBox="0 0 18 18"><path d="M15.562 8.1L3.87.225c-.818-.562-1.87 0-1.87.9v15.75c0 .9 1.052 1.462 1.87.9L15.563 9.9c.584-.45.584-1.35 0-1.8z"></path></symbol><symbol id="plyr-restart" viewBox="0 0 18 18"><path d="M9.7 1.2l.7 6.4 2.1-2.1c1.9 1.9 1.9 5.1 0 7-.9 1-2.2 1.5-3.5 1.5-1.3 0-2.6-.5-3.5-1.5-1.9-1.9-1.9-5.1 0-7 .6-.6 1.4-1.1 2.3-1.3l-.6-1.9C6 2.6 4.9 3.2 4 4.1 1.3 6.8 1.3 11.2 4 14c1.3 1.3 3.1 2 4.9 2 1.9 0 3.6-.7 4.9-2 2.7-2.7 2.7-7.1 0-9.9L16 1.9l-6.3-.7z"></path></symbol><symbol id="plyr-rewind" viewBox="0 0 18 18"><path d="M10.125 1L0 9l10.125 8v-6.171L18 17V1l-7.875 6.171z"></path></symbol><symbol id="plyr-settings" viewBox="0 0 18 18"><path d="M16.135 7.784a2 2 0 01-1.23-2.969c.322-.536.225-.998-.094-1.316l-.31-.31c-.318-.318-.78-.415-1.316-.094a2 2 0 01-2.969-1.23C10.065 1.258 9.669 1 9.219 1h-.438c-.45 0-.845.258-.997.865a2 2 0 01-2.969 1.23c-.536-.322-.999-.225-1.317.093l-.31.31c-.318.318-.415.781-.093 1.317a2 2 0 01-1.23 2.969C1.26 7.935 1 8.33 1 8.781v.438c0 .45.258.845.865.997a2 2 0 011.23 2.969c-.322.536-.225.998.094 1.316l.31.31c.319.319.782.415 1.316.094a2 2 0 012.969 1.23c.151.607.547.865.997.865h.438c.45 0 .845-.258.997-.865a2 2 0 012.969-1.23c.535.321.997.225 1.316-.094l.31-.31c.318-.318.415-.781.094-1.316a2 2 0 011.23-2.969c.607-.151.865-.547.865-.997v-.438c0-.451-.26-.846-.865-.997zM9 12a3 3 0 110-6 3 3 0 010 6z"></path></symbol><symbol id="plyr-volume" viewBox="0 0 18 18"><path d="M15.6 3.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4C15.4 5.9 16 7.4 16 9c0 1.6-.6 3.1-1.8 4.3-.4.4-.4 1 0 1.4.2.2.5.3.7.3.3 0 .5-.1.7-.3C17.1 13.2 18 11.2 18 9s-.9-4.2-2.4-5.7z"></path><path d="M11.282 5.282a.909.909 0 000 1.316c.735.735.995 1.458.995 2.402 0 .936-.425 1.917-.995 2.487a.909.909 0 000 1.316c.145.145.636.262 1.018.156a.725.725 0 00.298-.156C13.773 11.733 14.13 10.16 14.13 9c0-.17-.002-.34-.011-.51-.053-.992-.319-2.005-1.522-3.208a.909.909 0 00-1.316 0zm-7.496.726H.714C.286 6.008 0 6.31 0 6.76v4.512c0 .452.286.752.714.752h3.072l4.071 3.858c.5.3 1.143 0 1.143-.602V2.752c0-.601-.643-.977-1.143-.601L3.786 6.008z"></path></symbol></svg></div>
		</div><?php
	endif;
}
add_action( 'wp_footer', 'hpm_chartbeat', 100 );
add_action( 'wp_footer', 'hpm_hm_banner', 100 );
//add_action( 'wp_footer', 'hpm_persistent_player', 101 );