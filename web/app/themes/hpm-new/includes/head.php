<?php
function hpm_site_header() { ?>
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>">&nbsp;</a>
					</div>
					<section>
						<div id="top-schedule">
							<div class="top-schedule-label"><button type="button" aria-expanded="false" aria-controls="top-schedule-link-wrap" ><span class="fas fa-calendar" aria-hidden="true"></span>Schedules</button></div>
							<div class="top-schedule-link-wrap" id="top-schedule-link-wrap">
								<div class="top-schedule-links"><a href="/tv8">TV 8 Guide</a></div>
								<div class="top-schedule-links"><a href="/news887">News 88.7</a></div>
								<div class="top-schedule-links"><a href="/classical">Classical</a></div>
								<div class="top-schedule-links"><a href="/mixtape">Mixtape</a></div>
							</div>
						</div>
						<div id="top-listen"><button data-href="/listen-live" data-dialog="480:855"><span class="fas fa-microphone" aria-hidden="true"></span>Listen</button></div>
						<div id="top-watch"><button data-href="/watch-live" data-dialog="820:850"><span class="fas fa-tv" aria-hidden="true"></span>Watch</button></div>
					</section>
					<div id="top-donate"><a href="/donate"><span class="fas fa-heart" aria-hidden="true"></span><br /><span class="top-mobile-text">Donate</span></a></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<button id="top-mobile-menu"><span class="icon" aria-hidden="true"></span><br /><span class="top-mobile-text"></span></button>
						<div class="nav-wrap">
							<div id="top-search"><span class="fas fa-search" aria-hidden="true"></span><?php get_search_form(); ?></div>
							<?php
								// Primary navigation menu.
								wp_nav_menu([
									'menu_class' => 'nav-menu',
									'theme_location' => 'head-main',
									'walker' => new HPMv2_Menu_Walker
								]);
							?>
						</div>
					</nav>
				</div>
			</header><?php
}

function hpm_header_info() {
	global $wp_query;
	$reqs = [
		'description' => 'Houston Public Media provides informative, thought-provoking and entertaining content through a multi-media platform that includes TV 8, News 88.7 and HPM Classical and reaches a combined weekly audience of more than 1.5 million.',
		'keywords' => [ 'Houston Public Media', 'KUHT', 'TV 8', 'Houston Public Media Schedule', 'Educational TV Programs', 'independent program broadcasts', 'University of Houston', 'nonprofit', 'NPR News', 'KUHF', 'Classical Music', 'Arts &amp; Culture', 'News 88.7' ],
		'permalink' => 'https://www.houstonpublicmedia.org',
		'title' => 'Houston Public Media',
		'thumb' => 'https://cdn.hpm.io/assets/images/HPM-logo-OGimage-2.jpg',
		'thumb_meta' => [
			'width' => 1200,
			'height' => 630,
			'mime-type' => 'image/jpeg'
		],
		'og_type' => 'website',
		'author' => [],
		'publish_date' => '',
		'modified_date' => '',
		'word_count' => 0,
		'npr_byline' => '',
		'npr_story_id' => '',
		'hpm_section' => '',
		'has_audio' => 0
	];

	if ( is_home() || is_404() ) :
		// Do Nothing
	else :
		$ID = $wp_query->queried_object_id;

		if ( is_archive() ) :
			if ( is_post_type_archive() ) :
				$obj = get_post_type_object( get_post_type() );
				$reqs['permalink'] = get_post_type_archive_link( get_post_type() );
				$reqs['title'] = $obj->labels->name . ' | Houston Public Media';
				$reqs['description'] = wp_strip_all_tags( $obj->description, true );
			else :
				$reqs['permalink'] = get_the_permalink( $ID );
				$reqs['title'] = $wp_query->queried_object->name . ' | Houston Public Media';
			endif;
		elseif ( is_single() || is_page() || get_post_type() == 'embeds' ) :
			$attach_id = get_post_thumbnail_id( $ID );
			if ( !empty( $attach_id ) ) :
				$feature_img = wp_get_attachment_image_src( $attach_id, 'large' );
				$reqs['thumb_meta'] = [
					'width' => $feature_img[1],
					'height' => $feature_img[2],
					'mime-type' => get_post_mime_type( $attach_id )
				];
				$reqs['thumb'] = $feature_img[0];
			endif;
			$reqs['title'] = wp_strip_all_tags( get_the_title( $ID ), true ) . ' | Houston Public Media';
			$reqs['permalink'] = get_the_permalink( $ID );
			$reqs['description'] = htmlentities( wp_strip_all_tags( get_excerpt_by_id( $ID ), true ), ENT_QUOTES );
			$reqs['og_type'] = 'article';
			$coauthors = get_coauthors( $ID );
			foreach ( $coauthors as $coa ) :
				$author_fb = '';
				if ( is_a( $coa, 'wp_user' ) ) :
					$author_check = new WP_Query( [
						'post_type' => 'staff',
						'post_status' => 'publish',
						'meta_query' => [ [
							'key' => 'hpm_staff_authid',
							'compare' => '=',
							'value' => $coa->ID
						] ]
					] );
					if ( $author_check->have_posts() ) :
						$author_meta = get_post_meta( $author_check->post->ID, 'hpm_staff_meta', true );
						if ( !empty( $author_meta['facebook'] ) ) :
							$author_fb = $author_meta['facebook'];
						endif;
					endif;
				elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) :
					if ( !empty( $coa->linked_account ) ) :
						$authid = get_user_by( 'login', $coa->linked_account );
						$author_check = new WP_Query( [
							'post_type' => 'staff',
							'post_status' => 'publish',
							'meta_query' => [ [
								'key' => 'hpm_staff_authid',
								'compare' => '=',
								'value' => $authid->ID
							] ]
						] );
						if ( $author_check->have_posts() ) :
							$author_meta = get_post_meta( $author_check->post->ID, 'hpm_staff_meta', true );
							if ( !empty( $author_meta['facebook'] ) ) :
								$author_fb = $author_meta['facebook'];
							endif;
						endif;
					endif;
				endif;
				$reqs['author'][] = [
					'profile' => ( !empty( $author_fb ) ? $author_fb : get_author_posts_url( $coa->ID, $coa->user_nicename ) ),
					'first_name' => $coa->first_name,
					'last_name' => $coa->last_name,
					'username' => $coa->user_nicename
				];
			endforeach;
			$reqs['publish_date'] = get_the_date( 'c', $ID );
			$reqs['modified_date'] = get_the_modified_date( 'c', $ID );
			$reqs['description'] = htmlentities( wp_strip_all_tags( get_excerpt_by_id( $ID ), true ), ENT_QUOTES );
			$head_categories = get_the_category( $ID );
			$head_tags = wp_get_post_tags( $ID );
			$reqs['keywords'] = [];
			foreach( $head_categories as $hcat ) :
				$reqs['keywords'][] = $hcat->name;
			endforeach;
			foreach( $head_tags as $htag ) :
				$reqs['keywords'][] = $htag->name;
			endforeach;
			if ( get_post_type() === 'post' ) :
				$reqs['word_count'] = word_count( $ID );
				$reqs['has_audio'] = ( preg_match( '/\[audio/', $wp_query->post->post_content ) ? 1 : 0 );
				$npr_retrieved_story = get_post_meta( $ID, 'npr_retrieved_story', 1 );
				$reqs['npr_story_id'] = get_post_meta( $ID, 'npr_story_id', 1 );
				$reqs['hpm_section'] = hpm_top_cat( $ID );
				$reqs['npr_byline'] = ( $npr_retrieved_story == 1 ? get_post_meta( $ID, 'npr_byline', 1 ) : coauthors( ', ', ', ', '', '', false ) );
			elseif ( get_post_type() === 'staff' ) :
				$reqs['og_type'] = 'profile';
			endif;
		elseif ( is_author() ) :
			global $curauth;
			global $author_check;
			$reqs['og_type'] = 'profile';
			$reqs['permalink'] = get_author_posts_url( $curauth->ID, $curauth->user_nicename );
			$reqs['title'] = $curauth->display_name." | Houston Public Media";
			if ( !empty( $author_check ) ) :
				while ( $author_check->have_posts() ) :
					$author_check->the_post();
					$head_excerpt = htmlentities( wp_strip_all_tags( get_the_content(), true ), ENT_QUOTES );
					if ( !empty( $head_excerpt ) && $head_excerpt !== 'Biography pending.' ) :
						$reqs['description'] = $head_excerpt;
					endif;
					$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE );
					$head_categories = get_the_terms( get_the_ID(), 'staff_category' );
					if ( !empty( $head_categories ) ) :
						$reqs['keywords'] = [];
						foreach( $head_categories as $hcat ) :
							$reqs['keywords'][] = $hcat->name;
						endforeach;
					endif;
					$reqs['title'] = $curauth->display_name.", ".$author['title']." | Houston Public Media";
				endwhile;
				wp_reset_query();
			endif;
		elseif ( is_page_template( 'page-npr-articles.php' ) ) :
			global $nprdata;
			$reqs['title'] = $nprdata['title'];
			$reqs['permalink'] = $nprdata['permalink'];
			$reqs['description'] = htmlentities( wp_strip_all_tags( $nprdata['excerpt'], true ), ENT_QUOTES );
			$reqs['keywords'] = $nprdata['keywords'];
			$reqs['thumb'] = $nprdata['image']['src'];
			$reqs['thumb_meta'] = [
				'width' => $nprdata['image']['width'],
				'height' => $nprdata['image']['height'],
				'mime-type' => $nprdata['image']['mime-type']
			];
			$reqs['publish_date'] = $nprdata['date'];
		endif;
	endif;
?>
		<script type='text/javascript'>var _sf_startpt=(new Date()).getTime();</script>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="<?PHP echo $reqs['description']; ?>" />
		<meta name="keywords" content="<?php echo implode( ', ', $reqs['keywords'] ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="bitly-verification" content="7777946f1a0a"/>
		<meta name="google-site-verification" content="WX07OGEaNirk2km8RjRBernE0mA7_QL6ywgu6NXl1TM" />
		<meta name="theme-color" content="#00566C">
		<link rel="icon" sizes="48x48" href="https://cdn.hpm.io/assets/images/favicon/icon-48.png">
		<link rel="icon" sizes="96x96" href="https://cdn.hpm.io/assets/images/favicon/icon-96.png">
		<link rel="icon" sizes="144x144" href="https://cdn.hpm.io/assets/images/favicon/icon-144.png">
		<link rel="icon" sizes="192x192" href="https://cdn.hpm.io/assets/images/favicon/icon-192.png">
		<link rel="icon" sizes="256x256" href="https://cdn.hpm.io/assets/images/favicon/icon-256.png">
		<link rel="icon" sizes="384x384" href="https://cdn.hpm.io/assets/images/favicon/icon-384.png">
		<link rel="icon" sizes="512x512" href="https://cdn.hpm.io/assets/images/favicon/icon-512.png">
		<link rel="apple-touch-icon" sizes="57x57" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-120.png">
		<link rel="apple-touch-icon" sizes="152x152" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-152.png">
		<link rel="apple-touch-icon" sizes="167x167" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-167.png">
		<link rel="apple-touch-icon" sizes="180x180" href="https://cdn.hpm.io/assets/images/favicon/apple-touch-icon-180.png">
		<link rel="mask-icon" href="https://cdn.hpm.io/assets/images/favicon/safari-pinned-tab.svg" color="#ff0000">
		<meta name="msapplication-config" content="https://cdn.hpm.io/assets/images/favicon/config.xml" />
		<link rel="manifest" href="/manifest.webmanifest">
		<meta name="apple-itunes-app" content="app-id=1549226694,app-argument=<?php echo $reqs['permalink']; ?>" />
		<meta name="google-play-app" content="app-id=com.jacobsmedia.KUHFV3" />
		<meta property="fb:app_id" content="523938487799321" />
		<meta property="fb:admins" content="37511993" />
		<meta property="fb:pages" content="27589213702" />
		<meta property="fb:pages" content="183418875085596" />
		<meta property="og:type" content="<?php echo $reqs['og_type'] ?>" />
		<meta property="og:title" content="<?php echo $reqs['title']; ?>" />
		<meta property="og:url" content="<?php echo $reqs['permalink']; ?>"/>
		<meta property="og:site_name" content="Houston Public Media" />
		<meta property="og:description" content="<?php echo $reqs['description']; ?>" />
		<meta property="og:image" content="<?php echo $reqs['thumb']; ?>" />
		<meta property="og:image:url" content="<?php echo $reqs['thumb']; ?>" />
		<meta property="og:image:height" content="<?php echo $reqs['thumb_meta']['height']; ?>" />
		<meta property="og:image:width" content="<?php echo $reqs['thumb_meta']['width']; ?>" />
		<meta property="og:image:type" content="<?php echo $reqs['thumb_meta']['mime-type']; ?>" />
		<meta property="og:image:secure_url" content="<?php echo $reqs['thumb']; ?>" />
		<script>var timeOuts = [];</script>
<?php
	if ( ( is_single() || is_page_template( 'page-npr-articles.php' ) ) && get_post_type() !== 'staff' && get_post_type() !== 'embeds' ) : ?>
		<meta property="article:content_tier" content="free" />
		<meta property="article:published_time" content="<?php echo $reqs['publish_date']; ?>" />
		<meta property="article:modified_time" content="<?php echo $reqs['modified_date']; ?>" />
		<meta property="article:publisher" content="https://www.facebook.com/houstonpublicmedia/" />
		<meta property="article:section" content="<?php echo $reqs['hpm_section']; ?>" />
<?php
		if ( !empty( $reqs['keywords'] ) ) :
			foreach( $reqs['keywords'] as $keys ) : ?>
		<meta property="article:tag" content="<?php echo $keys; ?>" />
<?php
			endforeach;
		endif;
		foreach ( $reqs['author'] as $aup ) : ?>
		<meta property="article:author" content="<?php echo $aup['profile']; ?>" />
<?php
		endforeach;
	endif;
	if ( is_author() || ( is_single() && get_post_type() === 'staff' ) ) : ?>
		<meta property="profile:first_name" content="<?php echo $curauth->first_name; ?>">
		<meta property="profile:last_name" content="<?php echo $curauth->last_name; ?>">
		<meta property="profile:username" content="<?php echo $curauth->user_nicename; ?>">
<?php
	endif; ?>
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:site" content="@houstonpubmedia" />
		<meta name="twitter:creator" content="@houstonpubmedia" />
		<meta name="twitter:title" content="<?php echo $reqs['title']; ?>" />
		<meta name="twitter:image" content="<?php echo $reqs['thumb']; ?>" />
		<meta name="twitter:url" content="<?php echo $reqs['permalink']; ?>" />
		<meta name="twitter:description" content="<?php echo $reqs['description']; ?>">
		<meta name="twitter:widgets:link-color" content="#000000">
		<meta name="twitter:widgets:border-color" content="#000000">
		<meta name="twitter:partner" content="tfwp">
<?php
	if ( is_single() && get_post_type() !== 'staff' && get_post_type() !== 'embeds' ) : ?>
		<meta name="datePublished" content="<?php echo $reqs['publish_date']; ?>" />
		<meta name="story_id" content="<?php echo $reqs['npr_story_id']; ?>" />
		<meta name="has_audio" content="<?php echo $reqs['has_audio']; ?>" />
		<meta name="programs" content="none" />
		<meta name="category" content="<?php echo $reqs['hpm_section']; ?>" />
		<meta name="org_id" content="220" />
		<meta name="author" content="<?php echo $reqs['npr_byline']; ?>" />
		<meta name="wordCount" content="<?php echo $reqs['word_count']; ?>" />
<?php
	endif;
}
add_action( 'wp_head', 'hpm_header_info', 1 );
add_action( 'wp_head', 'hpm_google_tracker', 100 );

function hpm_body_open() {
	global $wp_query;
	if ( !empty( $_GET['browser'] ) && $_GET['browser'] == 'inapp' ) : ?>
	<script>setCookie('inapp','true',1);</script>
	<style>#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }</style>
<?php endif; ?>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv2' ); ?></a>
<?php
	if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) : ?>
		<div class="container">
			<?php hpm_site_header(); ?>
		</div>
		<?php echo hpm_talkshows(); ?>
<?php
	elseif ( is_page_template( 'page-listen.php' ) ) : ?>
		<div class="container">
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>">&nbsp;</a>
					</div>
					<div id="top-donate"><a href="/donate"><span class="fas fa-heart" aria-hidden="true"></span><br /><span class="top-mobile-text">Donate</span></a></div>
					<div id="top-mobile-menu"><span class="fas fa-bars" aria-hidden="true"></span><br /><span class="top-mobile-text">Menu</span></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div id="top-search"><span class="fas fa-search" aria-hidden="true"></span><?php get_search_form(); ?></div>
						<?php
							wp_nav_menu( array(
								'menu_class' => 'nav-menu',
								'menu' => 12244,
								'walker' => new HPMv2_Menu_Walker
							) ); ?>
						<div class="clear"></div>
					</nav>
				</div>
			</header>
		</div>
<?php
	endif; ?>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
<?php
	if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) : ?>
			<!-- /9147267/HPM_Under_Nav -->
				<div id='div-gpt-ad-1488818411584-0'>
					<script>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1488818411584-0'); });
					</script>
				</div>
<?php
	endif;
}
add_action( 'body_open', 'hpm_body_open' );

function hpm_talkshows() {
	wp_reset_query();
	global $wp_query;
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	$output = '';
	$anc = get_post_ancestors( get_the_ID() );
	$bans = [ 135762, 290722, 303436, 303018, 315974 ];
	$hm_air = hpm_houston_matters_check();
	if ( !in_array( 135762, $anc ) && !in_array( get_the_ID(), $bans ) && $wp_query->post->post_type !== 'embeds' ) :
		if ( ( $now['wday'] > 0 && $now['wday'] < 6 ) && ( $now['hours'] == 9 || $now['hours'] == 15 ) && $hm_air[ $now['hours'] ] ) :
			if ( $now['hours'] == 15 ) :
				$output .= '<div id="hm-top" class="townsquare"><p><span><a href="/listen-live/"><strong>Town Square</strong> is on the air now!</a> Join the conversation:</span> Call <strong>888.486.9677</strong> | Email <a href="mailto:talk@townsquaretalk.org">talk@townsquaretalk.org</a> | <a href="/listen-live/">Listen Live</a></p></div>';
			else :
				$output .= '<div id="hm-top"><p><span><a href="/listen-live/"><strong>Houston Matters</strong> is on the air now!</a> Join the conversation:</span> Call <strong>713.440.8870</strong> | Email <a href="mailto:talk@houstonmatters.org">talk@houstonmatters.org</a> | <a href="/listen-live/">Listen Live</a></p></div>';
			endif;
		endif;
	endif;
	return $output;
}