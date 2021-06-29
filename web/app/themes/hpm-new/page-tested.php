<?php
/*
Template Name: Tested
*/
	get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/qmq1vwk.css">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<?php
						the_title( '<h1 class="page-title screen-reader-text">', '</h1>' ); ?>
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
			--color-background: #1F54A0;
			--color-credit: #98C9E3;
			--color-resource: #98C9E3;
		}
		.page-content {
			overflow: hidden;
			padding: 0;
		}
		.page-content p {
			margin-bottom: 1em;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
		}
		.page-content a {
			color: var(--color-background);
			font-weight: 400;
		}
		.page-header {
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			height: 0;
			padding-right: 0;
			padding-left: 0;
			padding-top: 0;
			margin: 0 !important;
			padding-bottom: calc(100%/0.666667);
			position: relative;
			background-image: url(https://cdn.hpm.io/assets/images/Tested_Landing-Page_Banner_1200x1800.jpeg);
		}
		.page-template-page-tested article {
			padding: 0;
			margin: 0;
		}
		h2 {
			font-size: 200%;
			padding: 0;
			margin: 0;
			color: var(--color-resource);
			text-transform: uppercase;
			font-family: Montserrat,Arial, Helvetica, sans-serif;
			font-weight: 800;
		}
		.tested-head,
		.tested-resources {
			padding: 2em 1em;
			background-color: var(--color-background);
		}
		.tested-credit {
			padding: 1em;
			text-align: center;
			background-color: #EB8A7D;
		}
		.tested-credit p {
			font-size: 1em;
			font-weight: 200;
			color: white;
			margin: 0;
			padding: 0;
		}
		.tested-credit p a {
			color: white;
		}
		.tested-head p {
			font-size: 1.125em;
			font-weight: 200;
			color: white;
		}
		.tested-head p a {
			color: white;
			text-decoration: underline;
		}
		.tested-head h3 {
			font-size: 1.75em;
			font-weight: 400;
			text-align: center;
		}
		.tested-head h3 a {
			color: var(--color-resource);
		}
		.tested-eps {
			padding: 2em 0;
		}
		.tested-eps article {
			padding: 0 0 2em 0;
		}
		.tested-eps .episode-video p {
			margin: 0;
		}
		.tested-eps .episode-content,
		.tested-eps .episode-audio {
			padding: 1em 2em;
		}
		.page-content .tested-eps p {
			font-size: 1.125em;
		}
		.tested-eps h1 {
			text-transform: capitalize;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 600;
			font-size: 2em;
			color: var(--color-background);
			border-bottom: 1px solid var(--color-background);
			padding: 0 0 0.5em;
			margin: 0 0 0.5em 0;
			line-height: 1em;
		}
		.tested-eps h3 {
			text-transform: capitalize;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 1.5em;
			color: var(--color-background);
			border: 0;
			margin: 0;
			padding: 0;
		}
		.tested-eps .jp-type-single {
			background-color: transparent;
		}
		.tested-eps .jp-gui.jp-interface .jp-controls button {
			background-color: transparent;
			width: 4em;
			height: 4em;
		}
		.tested-eps .jp-gui.jp-interface .jp-controls button .fa {
			font-size: 3.25em;
			color: var(--color-background);
		}
		.tested-eps .jp-gui.jp-interface .jp-progress-wrapper {
			position: relative;
			padding: 1em 0.5em;
		}
		.tested-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-progress {
			margin: 0;
			background-color: rgb(79, 79, 79);
			z-index: 9;
			position: relative;
		}
		.tested-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-progress .jp-seek-bar {
			z-index: 11;
		}
		.tested-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
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
		.tested-resources h2 {
			text-transform: initial;
			font-weight: 600;
			margin: 0 0 0.5em 0;
		}
		.tested-resources h3 {
			color: white;
			font-weight: 400;
			background-color: var(--color-credit);
			margin: 0;
			padding: 0.5em 1em;
			font-size: 1.25em;
		}
		.tested-resources ul {
			color: white;
			margin: 0 0 2em 0;
			padding: 0 1em;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 200;
		}
		.tested-resources ul li a {
			color: white;
			text-decoration: underline;
		}
		.tested-button-wrap {
			display: flex;
			align-content: center;
			flex-flow: row wrap;
		}
		.tested-button-wrap .tested-button {
			width: 100%;
			padding: 0.5em 1em;
			margin: 0 0 1em 0;
			background-color: #90AFDE;
			position: relative;
			height: 0;
			padding: 0 0 calc(100%/2.75) 0;
		}
		.tested-button-wrap .tested-button a {
			color: white;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-size: 1.5em;
			display: flex;
			align-content: center;
			align-items: center;
			justify-content: center;
			text-align: center;
			position: absolute;
			flex-flow: column wrap;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
		}
		.tested-button-wrap .tested-button a img {
			width: 32px;
			margin-top: 0.25em;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/1);
				background-image: url(https://cdn.hpm.io/assets/images/Tested_Landing-Page_Banner_1600x1600.jpeg);
			}
			.tested-eps .jp-gui.jp-interface .jp-details {
				display: none;
			}
			.tested-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
				top: 1.25em;
			}
			.tested-button-wrap {
				display: flex;
				align-content: center;
				justify-content: space-between;
				flex-flow: row nowrap;
			}
			.tested-button-wrap .tested-button {
				width: 32%;
				padding: 0 0 calc(32%/2) 0;
			}
			.tested-button-wrap .tested-button a {
				font-size: 1.375em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/2);
				background-image: url(https://cdn.hpm.io/assets/images/Tested_Landing-Page_Banner_2400x1200.jpeg);
			}
			.page-template-page-tested article {
				padding: 0;
				margin: 0;
				width: 100%;
				border-right: 0;
				float: none;
			}
			.tested-head {
				padding: 2em 6em;
			}
			.page-template-page-tested .tested-eps article {
				padding: 1em 2em 2em;
				display: grid;
				grid-template-columns: 55% 45%;
				grid-template-rows: auto auto;
			}
			.tested-eps .episode-video {
				grid-column-start: 1;
				grid-row-start: 1;
			}
			.tested-eps .episode-audio {
				grid-column-start: 1;
				grid-row-start: 2;
				padding-bottom: 0;
			}
			.tested-eps .episode-content {
				grid-column-start: 2;
				grid-row-start: span 2;
				padding: 0 2em;
			}
			.tested-button-wrap .tested-button {
				padding: 0 0 calc(32%/2.5) 0;
			}
			.tested-button-wrap .tested-button a {
				font-size: 1.5em;
			}
			.tested-resources {
				padding: 2em;
			}
		}
	</style>
<?php get_footer(); ?>