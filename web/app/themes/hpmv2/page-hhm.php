<?php
/*
Template Name: Hispanic Heritage
*/
	get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/hua6upg.css">
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
			--hhm-green: #41BB93;
			--hhm-red: #ED5423;
			--hhm-yellow: #FFBE00;
			--hhm-beige: #F5E7C4;
		}
		.page-content {
			overflow: hidden;
			padding: 0;
		}
		.page-content p {
			margin-bottom: 1em;
			font-family: 'MiloOT-Light', Arial, Helvetica, sans-serif;
		}
		.page-content a {
			color: var(--hhm-red);
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
			padding-bottom: calc(100%/1.5);
			position: relative;
			background-image: url(https://cdn.hpm.io/assets/images/HHM-show-banner-mobile-2x.jpeg);
		}
		.page-template-page-hhm article {
			padding: 0;
			margin: 0;
		}
		h2 {
			font-size: 200%;
			padding: 0;
			margin: 0;
			color: var(--hhm-beige);
			font-family: oswald,Arial, Helvetica, sans-serif;
			font-weight: 400;
		}
		.hhm-head {
			padding: 1em;
			background-color: var(--hhm-green);
		}
		.hhm-head h2 {
			font-weight: 600;
			font-size: 175%;
		}
		.hhm-head p {
			color: white;
			margin: 0;
		}
		.hhm-resources {
			padding: 2em 1em;
			background-color: var(--hhm-green);
			width: 100%;
		}
		.hhm-eps {
			padding: 0 0 2em 0;
			width: 100%;
		}
		.hhm-eps article {
			padding: 0 0 2em 0;
		}
		.hhm-eps .episode-video p {
			margin: 0;
		}
		.hhm-eps .episode-content {
			padding: 1em 2em 0;
		}
		.hhm-eps .episode-audio {
			padding: 0 2em 1em;
		}
		.page-content .hhm-eps p {
			font-size: 1.125em;
		}
		#hhm {
			overflow: hidden;
			padding-top: 2em;
		}
		.hhm-eps h1 {
			text-transform: capitalize;
			font-family: oswald, Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 2em;
			color: var(--hhm-red);
			border-bottom: 1px solid #808080;
			padding: 0 0 0.5em;
			margin: 0 0 0.5em 0;
			line-height: 1em;
		}
		.hhm-eps h3 {
			text-transform: capitalize;
			font-family: oswald, Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 1.5em;
			color: var(--hhm-red);
			border: 0;
			margin: 0;
			padding: 0;
		}
		.hhm-eps .jp-type-single {
			background-color: transparent;
		}
		.hhm-eps .jp-gui.jp-interface .jp-controls button {
			background-color: transparent;
			width: 4em;
			height: 4em;
		}
		.hhm-eps .jp-gui.jp-interface .jp-controls button .fa {
			font-size: 3.25em;
			color: var(--hhm-red);
		}
		.hhm-eps .jp-gui.jp-interface .jp-progress-wrapper {
			position: relative;
			padding: 1em 0.5em;
		}
		.hhm-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-progress {
			margin: 0;
			background-color: rgb(79, 79, 79);
			z-index: 9;
			position: relative;
		}
		.hhm-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-progress .jp-seek-bar {
			z-index: 11;
		}
		.hhm-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
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
		.hhm-resources h2 {
			font-weight: 600;
			margin: 0 0 0.5em 0;
		}
		.hhm-resources ul {
			color: white;
			margin: 0 0 2em 0;
			padding: 0.5em 2em 1em;
			font-family: 'MiloOT-Light', Arial, Helvetica, sans-serif;
			font-weight: 400;
		}
		.hhm-resources ul li a {
			color: white;
			text-decoration: none;
		}
		.hhm-coming h3 {
			display: block;
			padding: 1em;
			margin: 0;
			color: var(--hhm-red);
			background-color: var(--hhm-beige);
			text-transform: initial;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/4);
				background-image: url(https://cdn.hpm.io/assets/images/HHM-show-banner-tablet-2x.jpeg);
			}
			.hhm-eps .jp-gui.jp-interface .jp-details {
				display: none;
			}
			.hhm-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
				top: 1.25em;
			}
			.hhm-resources div {
				display: flex;
				flex-flow: row nowrap;
			}
			.hhm-resources div p {
				width: 50%;
				padding: 0 1em;
			}
			.hhm-eps article {
				display: flex;
				flex-flow: row wrap;
			}
			.hhm-eps .episode-video {
				width: 50%;
				padding: 1em;
			}
			.hhm-eps .episode-content {
				width: 50%;
			}
			.hhm-eps .episode-audio {
				width: 100%;
			}
			.hhm-eps .episode-content {
				padding: 1em 1em 0;
			}
		}
		@media screen and (min-width: 50.0625em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/6);
				background-image: url(https://cdn.hpm.io/assets/images/HHM-show-banner-desktop-2x.jpeg);
			}
			.page-template-page-hhm article {
				padding: 0;
				margin: 0;
				width: 100%;
				border-right: 0;
				float: none;
			}
			.page-template-page-hhm .hhm-eps article {
				padding: 1em 0 2em;
			}
			.hhm-eps .episode-video {
				padding: 1em 0 1em 1em;
			}
			.hhm-eps .episode-audio {
				padding-bottom: 0;
			}
			.hhm-button-wrap .hhm-button {
				padding: 0 0 calc(32%/2.5) 0;
			}
			.hhm-button-wrap .hhm-button a {
				font-size: 1.5em;
			}
			.hhm-resources {
				padding: 2em;
				float: right;
				width: 31.5%;
				margin-left: 1.833333%;
			}
			.hhm-eps {
				width: 65%;
				float: left;
				margin-right: 1.666667%;
			}
			.hhm-resources div {
				flex-flow: row wrap;
			}
			.hhm-resources div p {
				width: 100%;
				padding: 0;
			}
		}
	</style>
<?php get_footer(); ?>