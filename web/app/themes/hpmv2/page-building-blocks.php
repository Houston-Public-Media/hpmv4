<?php
/*
Template Name: Building Blocks
*/
	get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/kmt5qno.css" />
	<style>
		@font-face {
			font-family: 'PBS-Sans';
			src: url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans.woff2') format('woff2'),
			url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans.woff') format('woff'),
			url('https://cdn.hpm.io/assets/fonts/pbs-sans/PBSSans.ttf') format('truetype');
			font-display: auto;
			font-weight: 400;
			font-style: normal;
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<img src="https://cdn.hpm.io/assets/images/building-blocks_title-explosion-burst-graphic.png" alt="Building Blocks logo" />
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
			--bblocks-green: #d9e021;
			--bblocks-red: #fb5326;
			--bblocks-blue: #00b1ff;
		}
		.page-content {
			overflow: hidden;
			padding: 0;
		}
		.page-content p {
			margin-bottom: 1em;
			font-family:  Arial, Helvetica, sans-serif;
		}
		.page-content a {
			color: var(--bblocks-blue);
			font-weight: 400;
		}
		.page-header {
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			padding: 0;
			margin: 0 !important;
			position: relative;
			background-image: url(https://cdn.hpm.io/assets/images/building-blocks_background-sm.jpg);
		}
		.page-template-page-building-blocks article {
			padding: 0;
			margin: 0;
		}
		h2 {
			font-size: 200%;
			padding: 0;
			margin: 0;
			color: var(--bblocks-red);
			text-transform: uppercase;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: 800;
		}
		.bblocks-resources {
			padding: 2em 1em;
			background-color: var(--bblocks-green);
		}
		.bblocks-head {
			padding: 2em 1em;
			background-color: var(--bblocks-red);
		}
		.bblocks-head p {
			font-size: 1.125em;
			font-weight: 200;
			color: #e6e6e6;
			font-family: 'PBS-Sans', Arial, Helvetica, sans-serif;
		}
		.bblocks-head p a {
			color: var(--bblocks-green);
			text-decoration: underline;
		}
		.bblocks-head h3 {
			font-size: 1.75em;
			font-weight: 400;
			text-align: center;
			font-family: 'blockhead-unplugged', Arial, Helvetica, sans-serif;
		}
		.bblocks-head h3 a {
			color: var(--bblocks-green);
		}
		.bblocks-eps {
			padding: 2em 0;
		}
		.bblocks-eps article {
			padding: 0 0 2em 0;
		}
		.bblocks-eps .episode-video p {
			margin: 0;
		}
		.bblocks-eps .episode-content,
		.bblocks-eps .episode-audio {
			padding: 1em 2em;
		}
		.page-content .bblocks-eps p {
			font-size: 1.125em;
			color: #666666;
			font-family: 'PBS-Sans', Arial, Helvetica, sans-serif;
		}
		.bblocks-eps h1 {
			text-transform: capitalize;
			font-family: 'blockhead-unplugged', Arial, Helvetica, sans-serif;
			font-weight: 600;
			font-size: 2.125em;
			color: var(--bblocks-blue);
			border-bottom: 2px solid var(--bblocks-green);
			padding: 0 0 0.5em;
			margin: 0 0 0.5em 0;
			line-height: 1em;
			letter-spacing: -3px;
		}
		.bblocks-eps h3 {
			text-transform: capitalize;
			font-family: 'blockhead-unplugged', Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 1.5em;
			color: var(--bblocks-blue);
			border: 0;
			margin: 0;
			padding: 0;
		}
		.bblocks-eps .jp-type-single {
			background-color: transparent;
		}
		.bblocks-eps .jp-gui.jp-interface .jp-controls button {
			background-color: transparent;
			width: 4em;
			height: 4em;
		}
		.bblocks-eps .jp-gui.jp-interface .jp-controls button .fa {
			font-size: 3.25em;
			color: var(--bblocks-blue);
		}
		.bblocks-eps .jp-gui.jp-interface .jp-progress-wrapper {
			position: relative;
			padding: 1em 0.5em;
		}
		.bblocks-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-progress {
			margin: 0;
			background-color: var(--bblocks-green);
			z-index: 9;
			position: relative;
		}
		.bblocks-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-progress .jp-seek-bar {
			z-index: 11;
		}
		.bblocks-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
			position: absolute;
			top: 1.5em;
			right: 1em;
			z-index: 10;
			float: none;
			width: auto;
			display: inline;
			padding: 0;
			color: black;
		}
		.bblocks-resources h2 {
			text-transform: initial;
			font-weight: 600;
			margin: 0 0 0.5em 0;
			font-family: 'blockhead-unplugged', Arial, Helvetica, sans-serif;
		}
		.bblocks-resources h3 {
			color: var(--bblocks-blue);
			font-weight: 400;
			background-color: var(--bblocks-green);
			margin: 0;
			padding: 0.5em 1em;
			font-size: 1.25em;
		}
		.bblocks-resources ul {
			color: var(--bblocks-red);
			margin: 0 0 2em 0;
			padding: 0 1em;
			font-family: 'PBS-Sans', Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 1.25em;
		}
		.bblocks-resources ul li a {
			color: var(--bblocks-blue);
			text-decoration: underline;
		}
		.bblocks-button-wrap {
			display: flex;
			align-content: center;
			flex-flow: row wrap;
		}
		.bblocks-button-wrap .bblocks-button {
			width: 100%;
			padding: 0.5em 1em;
			margin: 0 0 1em 0;
			background-color: #90AFDE;
			position: relative;
			height: 0;
			padding: 0 0 calc(100%/2.75) 0;
		}
		.bblocks-button-wrap .bblocks-button a {
			color: white;
			font-family:  Arial, Helvetica, sans-serif;
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
		.bblocks-button-wrap .bblocks-button a img {
			width: 32px;
			margin-top: 0.25em;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding: 0;
				background-image: url(https://cdn.hpm.io/assets/images/building-blocks_background.jpg);
			}
			.bblocks-eps .jp-gui.jp-interface .jp-details {
				display: none;
			}
			.bblocks-eps .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
				top: 1.25em;
			}
			.bblocks-button-wrap {
				display: flex;
				align-content: center;
				justify-content: space-between;
				flex-flow: row nowrap;
			}
			.bblocks-button-wrap .bblocks-button {
				width: 32%;
				padding: 0 0 calc(32%/2) 0;
			}
			.bblocks-button-wrap .bblocks-button a {
				font-size: 1.375em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-template-page-building-blocks article {
				padding: 0;
				margin: 0;
				width: 100%;
				border-right: 0;
				float: none;
			}
			.bblocks-head {
				padding: 2em 6em;
			}
			.page-template-page-building-blocks .bblocks-eps article {
				padding: 1em 2em 2em;
				display: grid;
				grid-template-columns: 55% 45%;
				grid-template-rows: auto auto;
			}
			.bblocks-eps .episode-video {
				grid-column-start: 1;
				grid-row-start: 1;
			}
			.bblocks-eps .episode-audio {
				grid-column-start: 1;
				grid-row-start: 2;
				padding-bottom: 0;
			}
			.bblocks-eps .episode-content {
				grid-column-start: 2;
				grid-row-start: span 2;
				padding: 0 2em;
			}
			.bblocks-button-wrap .bblocks-button {
				padding: 0 0 calc(32%/2.5) 0;
			}
			.bblocks-button-wrap .bblocks-button a {
				font-size: 1.5em;
			}
			.bblocks-resources {
				padding: 2em;
			}
		}
	</style>
<?php get_footer(); ?>