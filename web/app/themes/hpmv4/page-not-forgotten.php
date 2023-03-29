<?php
/*
Template Name: Not Forgotten
*/ ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<head>
		<?php wp_head(); ?>
		<link rel="stylesheet" href="https://use.typekit.net/wue8rzh.css">
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
				height: 4.5em;
				background-color: transparent;
				transition: background-color 0.5s;
				flex-flow: row nowrap;
				align-items: center;
				display: flex;
				justify-content: space-between;
			}
			.page-template-page-not-forgotten #masthead .site-title,
			.page-template-page-not-forgotten #masthead .site-nav {
				display: none;
			}
			.page-template-page-not-forgotten #masthead div.site-nav {
				text-align: right;
				font-size: 112.5%;
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
			.page-template-page-not-forgotten #masthead.active .site-nav {
				display: block;
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
				font-size: 2em;
				font-family: minion-3, serif;
				font-weight: 400;
				font-style: italic;
			}
			.page-template-page-not-forgotten #masthead h1 a {
				color: #f4b572;
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
				background-color: #000000;
				background-image: url(https://cdn.houstonpublicmedia.org/assets/images/NotForgotten_Landing-Page_Art_1500x1500.jpg);
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;
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
				background-color: transparent;
				border: 0;
			}
			button:hover {
				cursor: pointer;
				opacity: 0.75;
			}
			.page-template-page-not-forgotten .page-header .down svg {
				width: 4rem;
				fill: white;
			}
			.page-template-page-not-forgotten .page-header img {
				width: 85%;
			}
			section#nf-head p {
				color: white;
			}
			section#nf-profiles {
				padding: 0 1em;
			}
			section#nf-profiles article {
				border: 0 !important;
				background-color: #014d60 !important;
				margin-bottom: 2em;
			}
			section#nf-profiles article:hover {
				cursor: pointer;
			}
			section#nf-profiles article h1 {
				margin: 0;
				color: #f4b572;
				font-family: minion-3, serif;
				font-weight: 400;
				font-style: normal;
				text-align: center;
			}
			section#nf-profiles article .profile-full {
				display: none;
			}
			#nf-msg blockquote h2 {
				font-weight: bolder;
				font-style: italic;
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
			.modal-open {
				position: fixed;
				height: 100vh;
			}
			.article-player-wrap {
				margin-bottom: 1em;
			}
			#nf-msg-overlay svg {
				width: 3rem;
				fill: white;
			}
			section#nf-head > * + * {
				margin-top: 1rem;
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
				.page-template-page-not-forgotten #masthead .site-branding img {
					max-width: 10em;
				}
				.page-template-page-not-forgotten #masthead div {
					width: 33.33333%;
				}
				.page-template-page-not-forgotten .page-header img {
					width: 66%;
				}
			}
			@media screen and (min-width: 52.5em) {
				section#nf-profiles {
					justify-content: center;
				}
				section#nf-profiles article {
					width: 31.333333%;
					padding: 1em;
					margin: 0 1% 2em;
				}
				#nf-msg-overlay #nf-msg-wrap {
					max-width: 60em;
				}
				.page-template-page-not-forgotten .page-header img {
					width: 50%;
				}
				.article-player-wrap {
					float: left;
				}
			}
			@media screen and (min-width: 64.5em) {
				.page-template-page-not-forgotten .page-header img {
					width: 45%;
				}
			}
		</style>
	</head>
	<body <?php body_class(); ?>>
	<?php if ( !empty( $_GET['browser'] ) && $_GET['browser'] == 'inapp' ) { ?>
		<script>setCookie('inapp','true',1);</script>
		<style>#foot-banner, #top-donate, #masthead nav#site-navigation .nav-top.nav-donate, .top-banner { display: none; }</style>
	<?php } ?>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'hpmv4' ); ?></a>
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<a href="/" rel="home" title="Houston Public Media"><img src="https://cdn.houstonpublicmedia.org/assets/images/HPM-PBS-NPR-White.png" alt="Houston Public Media" /></a>
			</div>
			<div class="site-title"><h1><a href="/not-forgotten/">Not Forgotten</a></h1></div>
			<div class="site-nav"><a href="/coronavirus/">COVID News</a></div>
		</header>
		<div id="page" class="hfeed site">
			<div id="content" class="site-content">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<header class="page-header">
							<img src="https://cdn.houstonpublicmedia.org/assets/images/NotForgotten_Logo.svg" alt="<?php echo get_the_title() . ": " . get_the_excerpt(); ?>" />
							<button class="down scrollto">
								<?php echo hpm_svg_output( 'angle-double-down' ); ?>
							</button>
						</header>
						<div class="page-content" id="main-content">
							<?php the_content(); ?>
						</div>
						<div id="nf-msg-overlay">
							<div id="nf-previous"><?php echo hpm_svg_output( 'chevron-left' ); ?></div>
							<div id="nf-msg-wrap">
								<div id="nf-msg" data-current=""></div>
							</div>
							<div id="nf-next"><?php echo hpm_svg_output( 'chevron-right' ); ?></div>
							<div id="nf-close"><?php echo hpm_svg_output( 'times' ); ?></div>
						</div>
					</main>
				</div>
			</div>
			<script>
				function nfdimensions() {
					document.querySelector('#main .page-header').style.height = window.innerHeight + 'px';
				}
				function navSlide() {
					const scroll_top = window.scrollY;
					if (scroll_top >= window.innerHeight / 2) {
						document.querySelector('#masthead').classList.add('active');
					} else {
						document.querySelector('#masthead').classList.remove('active');
					}
				}
				document.addEventListener('DOMContentLoaded', () => {
					nfdimensions();
					window.addEventListener('scroll', () => { navSlide(); document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`); });
					window.addEventListener('resize', () => { nfdimensions() });
					window.eventType = ((document.ontouchstart !== null) ? 'click' : 'touchstart');
					document.querySelector('#nf-close').addEventListener(eventType, (e) => {
						e.stopPropagation();
						document.querySelector('#nf-msg-overlay').classList.remove('nf-active');
						document.body.classList.remove('modal-open');
						let scroll = document.body.style.top;
						window.scrollTo(0, parseInt(scroll || '0') * -1);
						hpm.players.forEach((player) => {
							player.pause();
						});
						let current = msg.getAttribute('data-current');
						if (msg.hasChildNodes) {
							document.querySelector('#nf-profile-'+current).append(msg.firstChild);
						}
					});
					document.querySelector('button.down').addEventListener(eventType, (e) => {
						e.preventDefault();
						let offset = document.querySelector('.page-content').offsetTop;
						window.scrollTo(0, offset-(4*16));
					});
					let profiles = document.querySelectorAll('.nf-profile');
					let msg = document.querySelector('#nf-msg');
					let navigation = document.querySelectorAll('#nf-next, #nf-previous');
					Array.from(navigation).forEach((navi) => {
						navi.addEventListener(eventType, (e) => {
							let current = parseInt(msg.getAttribute('data-current'));
							let next, message;
							if (navi.id === 'nf-next') {
								next = current + 1;
							} else {
								next = current - 1;
							}
							if (next > profiles.length) {
								next = 1;
							} else if (next === 0) {
								next = profiles.length;
							}
							msg.setAttribute('data-current', next);
							let nProf = document.querySelector('#nf-profile-'+next);
							Array.from(nProf.children).forEach((child) => {
								if (child.classList.contains('profile-full')) {
									message = child;
								}
							});
							if (msg.hasChildNodes) {
								document.querySelector('#nf-profile-'+current).append(msg.firstChild);
							}
							msg.append(message);
							document.querySelector('#nf-msg-wrap').scrollTo(0,0);
							hpm.players.forEach((player) => {
								player.pause();
							});
						});
					});
					Array.from(profiles).forEach((profile) => {
						profile.addEventListener(eventType, (e) => {
							e.stopPropagation();
							const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
							const body = document.body;
							var message;
							body.style.top = `-${scrollY}`;
							Array.from(profile.children).forEach((child) => {
								if (child.classList.contains('profile-full')) {
									message = child;
								}
							});
							let currentMsg = msg.getAttribute('data-current');
							if ( currentMsg !== '') {
								if (msg.hasChildNodes) {
									document.querySelector('#nf-profile-'+currentMsg).append(msg.firstChild);
								}
							}
							let current = profile.getAttribute('data-profile-num');
							document.querySelector('#nf-msg-overlay').classList.add('nf-active');
							msg.append(message);
							msg.setAttribute('data-current', current);
							document.body.classList.add('modal-open');
						});
					});
				});
			</script>
<?php get_footer(); ?>