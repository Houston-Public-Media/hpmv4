<?php
/*
Template Name: Black History Month
*/
	wp_enqueue_script('jquery');
	get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/wsl1bre.css">
	<style>
		#div-gpt-ad-1488818411584-0 {
			display: none;
		}
		:root {
			--bhm-red: #e40001;
			--bhm-green: #319d30;
			--bhm-yellow: #ffff01;
		}
		.page-content {
			overflow: hidden;
			padding: 0;
		}
		.page-content p {
			margin-bottom: 1em;
		}
		.page-content a {
			color: var(--bhm-red);
			font-weight: 400;
		}
		#page {
			background-position: 0px 0px;
			background-repeat: repeat-x;
			background-image: url(https://cdn.hpm.io/assets/images/BHM_pattern-top-clip.png);
		}
		.page-header {
			margin: 0 !important;
			width: 100%;
			padding: 3em 1em 2em;
			background-color: transparent !important;
		}
		.page-header h2 {
			text-align: center;
			font: 500 1.5em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			margin: 0 0 0.25em 0;
			color: #808080;
			text-transform: uppercase;
			letter-spacing: 2px;
			padding: 0;
		}
		.page-header h1 {
			text-transform: uppercase;
			text-align: center;
			font: 800 3em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			margin: 0;
			color: #e6e6e6;
			letter-spacing: 2px;
		}
		body.page-template-page-black-history {
			background-image: url(https://cdn.hpm.io/assets/images/BHM_pattern-tile-nobg3.png);
			background-color: #000 !important;
		}
		.page-template-page-black-history article {
			padding: 0;
			margin: 0;
			background-color: transparent !important;
		}
		.bhm-section-wrap {
			background-color: white;
			margin-bottom: 2em;
		}
		#main > article {
			grid-column: 1 / -1 !important;
		}
		#foot-banner {
			display: none;
		}
		body.page-template-page-black-history #main {
			background-color: transparent !important;
		}
		.site-content section {
			width: 100%;
			/* margin-bottom: 1em; */
			padding: 1em 0;
		}
		.site-content section:nth-child(n+2) {
			padding: 0 0 1em;
		}
		.site-content section .bhm-prop-wrap {
			display: flex;
			align-items: center;
			justify-content: center;
			flex-flow: column nowrap;
			padding: 0 0 1.625em;
			background-position: 0 100%;
			background-repeat: repeat-x;
			background-image: url(https://cdn.hpm.io/assets/images/BHM_pattern-top-clip-bg.png);
		}
		.site-content section div.bhm-img img {
			display: block;
		}
		.site-content section .bhm-wrap {
			padding: 1em;
			width: 100%;
		}
		.site-content section .bhm-wrap.bhm-wide {
			padding: 1em !important;
			width: 100% !important;
		}
		.site-content section#nprprogramming .bhm-prop-wrap,
		.site-content section#pbsprogramming .bhm-prop-wrap {
			align-items: flex-start;
		}
		.site-content section .bhm-wrap.bhm-list {
			padding-top: 0;
		}
		.site-content section .bhm-wrap.bhm-list:nth-child(2) {
			padding-bottom: 0;
		}
		.site-content section.bhm-1 .bhm-prop-wrap {
			background-color: var(--bhm-red);
		}
		.site-content section.bhm-2 .bhm-prop-wrap {
			background-color: var(--bhm-green);
		}
		.site-content section.bhm-3 .bhm-prop-wrap {
			background-color: var(--bhm-yellow);
		}
		.site-content section .bhm-prop-wrap h1 {
			font: 500 2em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			text-transform: uppercase;
			margin-bottom: 0.25em;
		}
		.site-content .page-content section .bhm-prop-wrap p {
			margin: 0;
		}
		.site-content section.bhm-1 .bhm-prop-wrap p,
		.site-content section.bhm-1 .bhm-prop-wrap h1,
		.site-content section.bhm-1 .bhm-prop-wrap p a,
		.site-content section.bhm-1 .bhm-prop-wrap li,
		.site-content section.bhm-2 .bhm-prop-wrap p,
		.site-content section.bhm-2 .bhm-prop-wrap h1,
		.site-content section.bhm-2 .bhm-prop-wrap p a,
		.site-content section.bhm-2 .bhm-prop-wrap li {
			color: white;
		}
		.site-content section .bhm-prop-wrap h2 {
			font: 500 1.75em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			margin-bottom: 0.25em;
			color: white;
		}
		.site-content section .bhm-prop-wrap a {
			text-decoration: underline;
		}
		.site-content section .bhm-prop-wrap ul li p a {
			color: #59595B;
		}
		.site-content section h1 {
			margin-bottom: 0;
		}
		div#bhm-nav {
			background-color: var(--accent-black-3);
		}
		div#bhm-nav ul {
			justify-content: center;
			align-content: center;
			align-items: stretch;
			flex-flow: row wrap;
			display: flex;
			list-style: none;
			padding: 0;
			margin: 0;
			border-top: 0.125em solid white;
		}
		div#bhm-nav ul li {
			padding: 1em;
			margin: 0;
			text-align: center;
			justify-content: center;
			align-content: center;
			align-items: center;
			flex-flow: row nowrap;
			display: flex;
			width: 50%;
			border: 0.125em solid white;
			border-top: 0;
		}
		.page-content div#bhm-nav ul li a {
			font: 500 1.25em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			text-transform: uppercase;
		}
		.site-content section .bhm-videos {
			width: 95%;
			margin-left: 2.5%;
			margin-right: 2.5%;
			padding: 1em;
		}
		.site-content section .bhm-videos .slick-slide {
			padding: 0 0.5em;
		}
		.slick-dots li.slick-active button:before,
		.slick-dots li button:before {
			color: #000;
		}
		.page-content ul.slick-dots {
			list-style: none !important;
			right: 0;
			left: 0;
		}
		.slick-prev, .slick-next {
			width: 35px !important;
			height: 35px !important;
		}
		.slick-prev:before, .slick-next:before {
			font-size: 35px !important;
		}
		.site-content .page-content section .bhm-prop-wrap ul li ul {
			margin-left: 1em;
			list-style: none;
		}
		.site-content .page-content section .bhm-prop-wrap ul li p:nth-child(n+2) {
			margin-left: 1em;
		}
		.bhm-wrap .jp-type-single {
			background-color: transparent;
		}
		.bhm-wrap .jp-gui.jp-interface .jp-controls button {
			background-color: transparent;
			width: 4em;
			height: 4em;
		}
		.bhm-wrap .jp-gui.jp-interface .jp-controls button .fa {
			font-size: 3.25em;
			color: var(--bhm-green);
		}
		.bhm-2 .bhm-wrap .jp-gui.jp-interface .jp-controls button .fa {
			color: var(--bhm-red);
		}
		.bhm-wrap .jp-gui.jp-interface .jp-progress-wrapper {
			position: relative;
			padding: 1em 0.5em;
		}
		.bhm-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-progress {
			margin: 0;
			background-color: rgb(79, 79, 79);
			z-index: 9;
			position: relative;
		}
		.bhm-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-progress .jp-seek-bar {
			z-index: 11;
		}
		.bhm-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
			position: absolute;
			top: 1.5em;
			right: 1em;
			z-index: 10;
			float: none;
			width: auto;
			display: inline;
			padding: 0;
			color: white;
		}
		@media screen and (min-width: 34em) {
			.page-header h2 {
				font: 500 1.75em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			}
			.page-header h1 {
				font: 800 4em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			}
			.site-content section {
				padding: 1em;
			}
			.site-content section:nth-child(n+2) {
				padding: 0 1em 1em;
			}
			.site-content section .bhm-prop-wrap {
				display: flex;
				flex-flow: row wrap;
			}
			.site-content section div.bhm-img {
				width: 50%;
				padding: 0 1em 0 0;
			}
			.site-content section#blackchurch div.bhm-img img {
				padding: 2em 1em 0;
			}
			.site-content section .bhm-wrap {
				width: 50%;
			}
			.site-content section.bhm-flip .bhm-prop-wrap {
				flex-flow: row-reverse wrap;
			}
			.site-content section.bhm-flip div.bhm-img {
				padding: 0 0 0 1em;
			}
			div#bhm-nav ul li {
				width: 25%;
			}
			.site-content section .bhm-wrap.bhm-list:nth-child(2) {
				padding-bottom: 1em;
			}
			.bhm-wrap .jp-gui.jp-interface .jp-details {
				display: none;
			}
			.bhm-wrap .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
				top: 1.25em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-header h2 {
				font: 500 2em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			}
			.page-header h1 {
				font: 800 6em/1em 'futura-pt-condensed',helvetica,arial,sans-serif;
			}
			.page-template-page-black-history article {
				padding: 0;
				margin: 0;
				width: 100%;
				border-right: 0;
				float: none;
			}
			.site-content section .bhm-section-wrap {
				padding: 1em 2em;
			}
			.site-content section .bhm-wrap {
				padding: 0 1em;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<?php the_title( '<h1 class="page-title screen-reader-text">', '</h1>' ); ?>
					<h2>Houston Public Media Celebrates</h2>
					<h1>Black History</h1>
				</header><!-- .entry-header -->
				<div class="page-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
				<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<?php
				endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<link rel="stylesheet" href="https://cdn.hpm.io/static/js/slick/slick.min.css" />
	<link rel="stylesheet" href="https://cdn.hpm.io/static/js/slick/slick-theme.css" />
	<script src="https://cdn.hpm.io/static/js/slick/slick.min.js"></script>
	<script>
		jQuery(document).ready(function($){
			var options = {
				slidesToShow: 2,
				slidesToScroll: 2,
				infinite: false,
				adaptiveHeight: false,
				autoplay: false,
				dots: true,
				speed: 500,
				responsive: [{
					breakpoint: 800,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2,
						rows: 1
					}
				}, {
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1,
						rows: 2
					}
				}]
			};
			$('#bhm-carousel-1').slick(options);
			$('#bhm-carousel-2').slick(options);
			$('#bhm-carousel-3').slick(options);
		});
	</script>
<?php get_footer(); ?>



