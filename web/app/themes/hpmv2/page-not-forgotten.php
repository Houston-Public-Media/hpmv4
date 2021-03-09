<?php
/*
Template Name: Not Forgotten
*/ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<?php wp_head(); ?>
		<style>
			body.page-template-page-not-forgotten {
				background-color: black;
			}
			.page-template-page-not-forgotten #masthead {
				width: 100%;
				max-width: 100% !important;
				position: fixed;
				padding: 0.5em;
				z-index: 9999;
				border: 0;
				height: 5.5em;
				background-color: transparent;
				transition: background-color 0.5s;
				flex-flow: row nowrap;
				align-items: center;
				display: flex;
			}
			.page-template-page-not-forgotten #masthead .site-title,
			.page-template-page-not-forgotten #masthead .site-nav {
				display: none;
			}
			.page-template-page-not-forgotten #masthead div {
				width: 33.33333%;
			}
			.page-template-page-not-forgotten #masthead div.site-nav {
				text-align: right;
				font-size: 150%;
			}
			.page-template-page-not-forgotten #masthead div.site-nav a {
				color: white;
			}
			.page-template-page-not-forgotten #masthead:before,
			.page-template-page-not-forgotten #masthead:after {
				content: none;
			}
			.page-template-page-not-forgotten #masthead.active {
				background-color: rgba( 0, 0, 0, 0.85 );
				transition: background-color 0.5s;
			}
			.page-template-page-not-forgotten #masthead .site-branding {
				background-color: transparent !important;
				padding: 0 !important;
			}
			.page-template-page-not-forgotten #masthead .site-branding img {
				max-height: 4.5em !important;
				max-width: 8.5em;
			}
			.page-template-page-not-forgotten #masthead h1 {
				margin: 0;
				text-align: center;
			}
			.page-template-page-not-forgotten #masthead h1 a {
				color: white;
			}
			.page-template-page-not-forgotten #content {
				width: 100% !important;
				min-width: 100% !important;
				max-width: 100% !important;
			}
			body.page.page-template-page-not-forgotten #main {
				background-color: transparent;
			}
			.page-template-page-not-forgotten .page-header {
				background: rgb(68,68,68);
				background: radial-gradient(circle, rgba(68,68,68,1) 0%, rgba(0,0,0,1) 100%);
				margin-bottom: 6em;
				flex-flow: column nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
				position: relative;
			}
			.page-template-page-not-forgotten .page-header .down {
				width: 100%;
				display: block;
				position: absolute;
				bottom: 0;
				color: white;
				text-align: center;
				transition: opacity 0.5s;
			}
			.page-template-page-not-forgotten .page-header .down .fa {
				font-size: 3em;
				line-height: 0.5em;
			}
			.page-template-page-not-forgotten .page-header h1 {
				text-align: center;
				padding: 0 1em;
				color: white;
				font-size: 300%
			}
			.page-template-page-not-forgotten .page-header h2 {
				text-align: center;
				padding: 0 1em;
				color: white;
				font-size: 150%;
			}
			section#nf-head p {
				color: white;
			}
			section#nf-profiles article {
				border: 0 !important;
				background-color: white !important;
				margin-bottom: 2em;
			}
			section#nf-profiles article:hover {
				cursor: pointer;
			}
			section#nf-profiles article h1 {
				margin: 0;
			}
			section#nf-profiles article .profile-full {
				display: none;
			}
			#nf-msg-overlay {
				position: fixed;
				z-index: 10001;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				flex-flow: row nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
				visibility: hidden;
				background-color: rgba(0,0,0,0.75);
			}
			#nf-msg-overlay.nf-active {
				visibility: visible;
			}
			#nf-msg-overlay #nf-msg-wrap {
				position: relative;
				padding: 1em;
				background-color: white;
				max-height: 85%;
				overflow-y: scroll;
			}
			#nf-msg-overlay #nf-msg-wrap #nf-msg {
				width: 100%;
				position: relative;
			}
			#nf-msg-overlay #nf-msg-wrap #nf-msg figure.wp-caption {
				padding: 0;
			}
			#nf-msg-overlay #nf-msg-wrap #nf-msg figure.wp-caption img {
				width: 100%;
			}
			#nf-msg-overlay #nf-msg-wrap #nf-msg p {
				padding: 0 0 1em 0;
			}
			#nf-msg-overlay #nf-close {
				position: absolute;
				top: 0.5em;
				right: 0.5em;
			}
			#nf-close:hover,
			#nf-next:hover,
			#nf-previous:hover {
				cursor: pointer;
			}
			#nf-msg-overlay .fa,
			#nf-msg-overlay .fas {
				color: white;
				width: 1em;
				font-size: 2em;
				display: block;
				text-align: center;
				line-height: 1.125em;
			}
			.modal-open {
				position: fixed;
				height: 100vh;
			}
			@media screen and (min-width: 34em) {
				.page-template-page-not-forgotten #masthead.active .site-title,
				.page-template-page-not-forgotten #masthead.active .site-nav {
					display: block;
				}
				section#nf-profiles {
					display: flex;
					flex-flow: row wrap;
					justify-content: space-between;
					max-width: 75em;
					margin: 0 auto;
				}
				section#nf-head {
					max-width: 75em;
					margin: 0 auto;
				}
				section#nf-profiles article {
					width: 48%;
					padding: 1em;
				}
			}
			@media screen and (min-width: 52.5em) {
				section#nf-profiles article {
					width: 32%;
					padding: 1em;
				}
				#nf-msg-overlay #nf-msg-wrap {
					max-width: 60em;
				}
				#nf-msg-overlay .fa, #nf-msg-overlay .fas {
					font-size: 3em;
				}
			}
		</style>
	</head>
	<body <?php body_class(); ?>>
	<?php if ( !empty( $_GET['browser'] ) && $_GET['browser'] == 'inapp' ) : ?>
		<script>setCookie('inapp','true',1);</script>
		<style>#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }</style>
	<?php endif; ?><div id="fb-root"></div>
		<script>window.fbAsyncInit = function() { FB.init({ appId: '523938487799321', xfbml: true, version: 'v10.0' });}; (function(d, s, id){ var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/sdk.js"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv2' ); ?></a>
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<a href="/" rel="home" title="Houston Public Media"><img src="https://cdn.hpm.io/assets/images/HPM-PBS-NPR-White.png" alt="Houston Public Media" /></a>
			</div><!-- .site-branding -->
			<div class="site-title"><h1><a href="/not-forgotten/">Not Forgotten</a></h1></div>
			<div class="site-nav"><a href="#about">About</a></div>
		</header><!-- .site-header -->
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<header class="page-header">
							<h1 class="page-title"><?php echo get_the_title(); ?></h1>
							<h2><?php echo get_the_excerpt(); ?></h2>
							<a class="down scrollto" href="#main-content">
								<span class="fa fa-angle-double-down"></span>
							</a>
						</header><!-- .entry-header -->
						<div class="page-content" id="main-content">
							<?php the_content(); ?>
						</div><!-- .entry-content -->
						<div id="nf-msg-overlay">
							<div id="nf-previous"><span class="fas fa-chevron-left"></span></div>
							<div id="nf-msg-wrap">
								<div id="nf-msg" data-current=""></div>
							</div>
							<div id="nf-next"><span class="fas fa-chevron-right"></span></div>
							<div id="nf-close"><span class="fa fa-times"></span></div>
						</div>
					</main><!-- .site-main -->
				</div><!-- .content-area -->
			</div><!-- .site-content -->
			<script>
				function nfdimensions($) {
					$('#main .page-header').css('height', $(window).height() + 'px');
				}
				function navSlide() {
					const scroll_top = jQuery(window).scrollTop();
					if (scroll_top >= jQuery(window).height()/2) {
						jQuery('#masthead').addClass('active');
					} else {
						jQuery('#masthead').removeClass('active');
					}
				}
				jQuery(document).ready(function ($) {
					nfdimensions($);
					$(window).on('resize', function () {
						nfdimensions($);
					});
					$( window ).on('scroll', function() { navSlide(); document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`); });
					window.eventType = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
					$('#nf-close').on(eventType, function (event) {
						event.stopPropagation();
						$('#nf-msg-overlay').removeClass('nf-active');
						$('body').removeClass('modal-open');
						var scroll = document.body.style.top;
						window.scrollTo(0, parseInt(scroll || '0') * -1);
					});
					$('a.down').on(eventType, function (event) {
						event.preventDefault();
						$('html, body').animate({
							scrollTop: $('.page-content').offset().top
						}, 500);
					});
					$('.nf-profile').on(eventType, function (event) {
						event.stopPropagation();
						const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
						const body = document.body;
						body.style.top = `-${scrollY}`;
						var message = $(this).children('.profile-full').html();
						var current = $(this).attr('data-profile-num');
						$('#nf-msg-overlay').addClass('nf-active');
						$('#nf-msg').html(message);
						$('#nf-msg').attr('data-current', current);
						$('body').addClass('modal-open');
					});
					$('#nf-next').on(eventType, function (event) {
						var current = parseInt($('#nf-msg').attr('data-current'));
						var profiles = document.querySelectorAll('.nf-profile');
						var next = current + 1;
						if (next > profiles.length) {
							next = 1;
						}
						$('#nf-msg').attr('data-current', next);
						var message = $('#nf-profile-'+next).children('.profile-full').html();
						$('#nf-msg').html(message);
						$('#nf-msg-wrap').scrollTop(0);
					});
					$('#nf-previous').on(eventType, function (event) {
						var current = parseInt($('#nf-msg').attr('data-current'));
						var profiles = document.querySelectorAll('.nf-profile');
						var prev = current - 1;
						if (prev == 0) {
							prev = profiles.length;
						}
						$('#nf-msg').attr('data-current', prev);
						var message = $('#nf-profile-'+prev).children('.profile-full').html();
						$('#nf-msg').html(message);
						$('#nf-msg-wrap').scrollTop(0);
					});
				});
			</script>
<?php get_footer(); ?>
<script>
// const showDialog = () => {
//   document.getElementById('dialog').classList.add('show');

//   const body = document.body;
//   body.style.position = 'fixed';
//   body.style.top = `-${scrollY}`;
// };
// const closeDialog = () => {
//   const body = document.body;
//   const scrollY = body.style.top;
//   body.style.position = '';
//   body.style.top = '';
//   window.scrollTo(0, parseInt(scrollY || '0') * -1);
//   document.getElementById('dialog').classList.remove('show');
// };
// window.addEventListener('scroll', () => {
//   document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
// });
</script>