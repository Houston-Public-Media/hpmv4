<?php
    $ID = $wp_query->post->ID;
    $coauthors = get_coauthors( $ID );
    foreach ( $coauthors as $coa ) :
        $author_profile[] = get_author_posts_url( $coa->ID, $coa->user_nicename );
    endforeach;
	$attach_id = get_post_thumbnail_id( $ID );
	$attach_media = get_attached_media( 'audio', $ID );
	$publish_date = get_the_date( 'c', $ID );
	if ( !empty( $attach_id ) ) :
		$feature_img = wp_get_attachment_image_src( $attach_id, 'large' );
		$thumb_meta = array(
			'width' => $feature_img[1],
			'height' => $feature_img[2],
			'mime-type' => get_post_mime_type( $attach_id )
		);
		$thumb = $feature_img[0];
	else :
        $thumb = array( 'https://cdn.hpm.io/assets/images/diversecity/HPMDiverseCity1920x1080.png' );
        $thumb_meta = array(
            'width' => 1920,
            'height' => 1080,
            'mime-type' => 'image/png'
        );
    endif;
    $head_title = strip_tags( get_the_title( $ID ) );
    $head_perma = get_the_permalink( $ID );
    $head_excerpt = htmlentities( strip_tags( get_excerpt_by_id( $ID ) ), ENT_QUOTES );
    $head_categories = get_the_category( $ID );
    $head_tags = wp_get_post_tags( $ID );
    $keywords = array();
    foreach( $head_categories as $hcat ) :
        $keywords[] = $hcat->name;
    endforeach;
    foreach( $head_tags as $htag ) :
        $keywords[] = $htag->name;
    endforeach;
    $word_count = word_count( $ID );
    $npr_retrieved_story = get_post_meta( $ID, 'npr_retrieved_story', 1 );
    $npr_story_id = get_post_meta( $ID, 'npr_story_id', 1 );
    $thumb = str_replace( 'http://', 'https://', $thumb );
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<script type='text/javascript'>var _sf_startpt=(new Date()).getTime()</script>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="<?PHP echo strip_tags( $head_excerpt ); ?>" />
        <meta name="keywords" content="<?PHP echo implode( ', ', $keywords ); ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="bitly-verification" content="7777946f1a0a"/>
        <meta name="google-site-verification" content="QOrBnMZ1LXDA9tL3e5WmFUU-oI3JUbDRotOWST1P_Dg" />
        <link rel="shortcut icon" href="https://cdn.hpm.io/assets/images/favicon.ico">
        <link rel="icon" type="image/png" href="https://cdn.hpm.io/assets/images/favicon-192x192.png" sizes="192x192">
        <link rel="apple-touch-icon" sizes="180x180" href="https://cdn.hpm.io/assets/images/apple-touch-icon-180x180.png">
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed" href="<?php bloginfo('rss2_url'); ?>" />
        <meta name="apple-itunes-app" content="app-id=530216229" />
        <meta name="google-play-app" content="app-id=com.jacobsmedia.KUHFV3" />
        <meta property="fb:app_id" content="523938487799321" />
        <meta property="fb:admins" content="37511993" />
        <meta property="fb:pages" content="27589213702" />
        <meta property="fb:pages" content="183418875085596" />
        <meta property="og:image" content="<?php echo $thumb; ?>" />
        <meta property="og:image:url" content="<?php echo $thumb; ?>" />
        <meta property="og:image:height" content="<?php echo $thumb_meta['height']; ?>" />
        <meta property="og:image:width" content="<?php echo $thumb_meta['width']; ?>" />
        <meta property="og:image:type" content="<?php echo $thumb_meta['mime-type']; ?>" />
        <meta property="og:image:secure_url" content="<?php echo $thumb; ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo $head_title; ?> | Houston Public Media" />
        <meta property="og:url" content="<?php echo $head_perma; ?>"/>
        <meta property="og:site_name" content="Houston Public Media" />
        <meta property="og:description" content="<?php echo $head_excerpt; ?>" />
        <meta property="article:published_time" content="<?php echo $publish_date; ?>" />
        <meta property="article:publisher" content="https://www.facebook.com/houstonpublicmedia/" />
		<?php
		if ( !empty( $head_categories ) ) :
			foreach( $head_categories as $hcat ) : ?>
                <meta property="article:tag" content="<?php echo $hcat->name; ?>" />
				<?php
			endforeach;
		endif;
		if ( !empty( $head_tags ) ) :
			foreach( $head_tags as $htag ) : ?>
                <meta property="article:tag" content="<?php echo $htag->name; ?>" />
				<?php
			endforeach;
		endif;
		foreach ( $author_profile as $aup ) : ?>
            <meta property="article:author" content="<?php echo $aup; ?>" />
			<?php
		endforeach; ?>
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:site" content="@houstonpubmedia" />
        <meta name="twitter:creator" content="@houstonpubmedia" />
        <meta name="twitter:title" content="<?php echo $head_title; ?> | Houston Public Media" />
        <meta name="twitter:image" content="<?php echo $thumb; ?>" />
        <meta name="twitter:url" content="<?php echo $head_perma; ?>" />
        <meta name="twitter:description" content="<?php echo $head_excerpt; ?>">
        <meta name="twitter:widgets:link-color" content="#000000">
        <meta name="twitter:widgets:border-color" content="#000000">
        <meta name="twitter:partner" content="tfwp">
        <meta name="datePublished" content="<?php echo $publish_date; ?>" />
        <meta name="story_id" content="<?php echo $npr_story_id; ?>" />
        <meta name="has_audio" content="<?php echo ( empty( $attach_media ) ? '0' : '1' ); ?>" />
        <meta name="programs" content="none" />
        <meta name="category" content="<?php echo ( empty( $keywords[0] ) ? '' : $keywords[0] ); ?>" />
        <meta name="org_id" content="220" />
        <meta name="author" content="<?php echo ( $npr_retrieved_story == 1 ? get_post_meta( $ID, 'npr_byline', 1 ) : coauthors( ', ', ', ', '', '', false ) ); ?>" />
        <meta name="wordCount" content="<?php echo $word_count; ?>" />
<?php wp_head(); ?>
		<link rel="stylesheet" id="hpmv2-style-diverse-css" href="https://cdn.hpm.io/assets/css/diversecity/diversecity.css" type="text/css" media="all">
		<link rel="stylesheet" id="hpmv2-style-diverse-icons" href="https://cdn.hpm.io/assets/css/diversecity/Genericons-Neue.min.css" type="text/css" media="all">
		<script async='async' src='https://www.googletagservices.com/tag/js/gpt.js'></script>
		<script>
			var googletag = googletag || {};
			googletag.cmd = googletag.cmd || [];
            googletag.cmd.push(function() {
                var dfpWide = window.innerWidth;
                if ( dfpWide > 1000 ) {
                    googletag.defineSlot('/9147267/HPM_Under_Nav', [970, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
	                document.getElementById('div-gpt-ad-1488818411584-0').style.width = '970px';
                }
                else if ( dfpWide <= 1000 && dfpWide > 730 ) {
                    googletag.defineSlot('/9147267/HPM_Under_Nav', [728, 90], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
	                document.getElementById('div-gpt-ad-1488818411584-0').style.width = '728px';
                }
                else if ( dfpWide <= 730 ) {
                    googletag.defineSlot('/9147267/HPM_Under_Nav', [320, 50], 'div-gpt-ad-1488818411584-0').addService(googletag.pubads());
	                document.getElementById('div-gpt-ad-1488818411584-0').style.width ='320px';
                }
                googletag.pubads().collapseEmptyDivs();
                googletag.enableServices();
            });
        </script>
		<?php hpm_google_tracker(); ?>
	</head>
    <?php
        $body_class = get_body_class();
        $body_class[] = 'dc';
    ?>
	<body class="<?php echo implode( ' ', $body_class ); ?>">
		<?php do_action( 'body_open' ); ?>
		<script>window.fbAsyncInit = function() { FB.init({ appId: '523938487799321', xfbml: true, version: 'v2.6' });}; (function(d, s, id){ var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/sdk.js"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv2' ); ?></a>
		<div class="container">
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="Home">&nbsp;</a>
					</div>
					<div class="dc-logo"><a href="/diversecity" rel="bookmark" title="DiverseCity Home"></a></div>
					<div id="top-mobile-menu" class="dc-top-menu"><span class="genericons-neue genericons-neue-menu" aria-hidden="true"></span></div>
				</div><!-- .site-branding -->
				<nav id="site-navigation" class="main-navigation" role="navigation">
					<div class="dc-nav-container">
						<div id="top-search"><div class="dc-search-wrap"><span class="genericons-neue genericons-neue-search" aria-hidden="true"></span><?php get_search_form(); ?><div id="dc-search-close"><span class="genericons-neue genericons-neue-close-alt" aria-hidden="true"></span></div></div></div>
						<?php wp_nav_menu( array( 'menu_class' => 'nav-menu', 'menu' => 10731 ) ); ?>
						<div class="clear"></div>
						<div id="dc-social">
							<div id="dc-search"><a href="#"><span class="genericons-neue genericons-neue-search" aria-hidden="true"></span></a></div>
							<div id="dc-facebook"><a href="https://www.facebook.com/houstonpublicmedia"><span class="fa fa-facebook" aria-hidden="true"></span></a></div>
							<div id="dc-twitter"><a href="https://twitter.com/houstonpubmedia"><span class="fa fa-twitter" aria-hidden="true"></span></a></div>
							<div id="dc-insta"><a href="https://instagram.com/houstonpubmedia"><span class="fa fa-instagram" aria-hidden="true"></span></a></div>
						</div>
					</div>
				</nav><!-- .main-navigation -->
			</header><!-- .site-header -->
		</div>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
                <!-- /9147267/HPM_Under_Nav -->
                <div id='div-gpt-ad-1488818411584-0'>
                    <script>
                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1488818411584-0'); });
                    </script>
                </div>