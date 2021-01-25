<?php
/*
Template Name: Black History Month
*/
	get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/wsl1bre.css">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<?php the_title( '<h1 class="page-title screen-reader-text">', '</h1>' ); ?>
					<h2>Houston Public Media Celebrates</h2>
					<h1>Black History Month</h1>
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
			/* background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			height: 0;
			padding-right: 0;
			padding-left: 0;
			padding-top: 0;
			margin: 0 !important;
			padding-bottom: calc(100%/1.5);
			position: relative;
			background-image: url(https://cdn.hpm.io/assets/images/Black-History-Month_Banners_Mobile.png); */
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
		}
		.bhm-section-wrap {
			background-color: white;
			margin-bottom: 2em;
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
		.site-content section.bhm-2 .bhm-prop-wrap p,
		.site-content section.bhm-2 .bhm-prop-wrap h1,
		.site-content section.bhm-2 .bhm-prop-wrap p a {
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
		/* div#bhm-nav ul li:last-child {
			border-right: none;
		} */
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
				flex-flow: row nowrap;
			}
			.site-content section div.bhm-img {
				width: 50%;
				padding: 0 1em 0 0;
			}
			.site-content section .bhm-wrap {
				width: 50%;
			}
			.site-content section.bhm-flip .bhm-prop-wrap {
				flex-flow: row-reverse nowrap;
			}
			.site-content section.bhm-flip div.bhm-img {
				padding: 0 0 0 1em;
			}
			div#bhm-nav ul li {
				width: 33.3333%;
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
			div#bhm-nav ul li {
				width: 16.6666%;
			}
			.site-content section .bhm-section-wrap {
				padding: 1em 2em;
			}
			.site-content section .bhm-wrap {
				padding: 0 1em;
			}
		}
	</style>
<?php get_footer(); ?>