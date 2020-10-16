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
	$anc = get_post_ancestors( get_the_ID() );
	if ( !in_array( 61383, $anc ) && WP_ENV !== 'development' ) : ?>
		<script type='text/javascript'>
			var _sf_async_config={};
			/** CONFIGURATION START **/
			_sf_async_config.uid = 33583;
			_sf_async_config.domain = 'houstonpublicmedia.org';
			_sf_async_config.useCanonical = true;
			_sf_async_config.sections = "<?php echo ( is_front_page() ? 'News, Arts & Culture, Education' : str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', get_the_ID() ) ) ) );
			?>";
			_sf_async_config.authors = "<?php echo ( is_front_page() ? 'Houston Public Media' : coauthors( ',', ',', '', '', false ) ); ?>";
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

function hpm_masonry() {
	wp_reset_query();
	global $wp_query;
	$post_type = get_post_type();
	if ( is_page_template( 'page-main-categories.php' ) || is_front_page() || ( $post_type == 'shows' && !is_page_template( 'single-shows-health-matters.php' ) && !is_page_template( 'single-shows-skyline.php' ) ) || is_page_template( 'page-series-tiles.php' ) ) :
		// if ( get_the_ID() != 61247 ) : ?>
	<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
	<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js"></script>
	<script>
		function masonLoad() {
			var isActive = false;
			if ( window.wide > 800 )
			{
				imagesLoaded( '#float-wrap', function() {
					var msnry = new Masonry( '#float-wrap', {
						itemSelector: '.grid-item',
						stamp: '.stamp',
						columnWidth: '.grid-sizer'
					});
					isActive = true;
				});
<?php
		/*
			Manually set the top pixel offset of the NPR articles box on the homepage, since Masonry doesn't calculate offsets for stamped elements
		*/
			if ( is_front_page() ) : ?>
				var topSched = document.querySelector('#top-schedule-wrap').getBoundingClientRect().height;
				document.getElementById('npr-side').style.cssText += 'top: '+topSched+'px';
<?php
			endif; ?>
			}
			else
			{
				if ( isActive ) {
					msnry.destroy();
					isActive = !isActive;
				}
				var gridItem = document.querySelectorAll('.grid-item');
				for ( i = 0; i < gridItem.length; ++i ) {
					gridItem[i].removeAttribute('style');
				}
			}
		}
		document.addEventListener("DOMContentLoaded", function() {
			masonLoad();
			var resizeTimeout;
			function resizeThrottler() {
				if ( !resizeTimeout ) {
					resizeTimeout = setTimeout(function() {
						resizeTimeout = null;
						masonLoad();
					}, 66);
				}
			}
			window.addEventListener("resize", resizeThrottler(), false);
			window.setTimeout(masonLoad(), 5000);
		});
	</script>
<?php
			// endif;
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
	if ( !in_array( 135762, $anc ) && !in_array( get_the_ID(), $bans ) ) :
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
						ga('send', 'event', 'Top Banner', 'click', attr);
						ga('hpmRollup.send', 'event', 'Top Banner', 'click', attr);
					}
				});
			}
		});
	</script>
<?php
		endif;
	endif;
}
add_action( 'wp_footer', 'hpm_chartbeat', 100 );
add_action( 'wp_footer', 'hpm_masonry', 99 );
add_action( 'wp_footer', 'hpm_hm_banner', 100 );