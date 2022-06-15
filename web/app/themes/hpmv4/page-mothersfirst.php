<?php
/*
Template Name: Mothers First
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
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
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
			--color-background: #42486A;
			--color-credit: #EB8A7D;
			--color-resource: #90AFDE;
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
			color: rgb(80, 127, 145);
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
			background-image: url(https://cdn.hpm.io/assets/images/Mothers-First-Mobile-1200x1800.jpg);
		}
		.page-template-page-mothersfirst article {
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
		.m1-head,
		.m1-resources {
			padding: 2em 1em;
			background-color: var(--color-background);
		}
		.m1-credit {
			padding: 1em;
			text-align: center;
			background-color: #EB8A7D;
		}
		.m1-credit p {
			font-size: 1em;
			font-weight: 200;
			color: white;
			margin: 0;
			padding: 0;
		}
		.m1-credit p a {
			color: white;
		}
		.m1-head p {
			font-size: 1.125em;
			font-weight: 200;
			color: white;
		}
		.m1-head p a {
			color: white;
			text-decoration: underline;
		}
		.m1-head h3 {
			font-size: 1.75em;
			font-weight: 400;
			text-align: center;
		}
		.m1-head h3 a {
			color: var(--color-resource);
		}
		.m1-eps {
			padding: 2em 0;
		}
		.m1-eps article {
			padding: 0 0 2em 0;
		}
		.m1-eps .episode-video p {
			margin: 0;
		}
		.m1-eps .episode-content,
		.m1-eps .episode-audio {
			padding: 1em 2em;
		}
		.page-content .m1-eps p {
			font-size: 1.125em;
		}
		.m1-eps h1 {
			text-transform: capitalize;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 600;
			font-size: 2em;
			color: #42486A;
			border-bottom: 1px solid #42486A;
			padding: 0 0 0.5em;
			margin: 0 0 0.5em 0;
			line-height: 1em;
		}
		.m1-eps h3 {
			text-transform: capitalize;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 400;
			font-size: 1.5em;
			color: #C9AAAC;
			border: 0;
			margin: 0;
			padding: 0;
		}
		.m1-eps .jp-type-single {
			background-color: transparent;
		}
		.m1-resources h2 {
			text-transform: initial;
			font-weight: 600;
			margin: 0 0 0.5em 0;
		}
		.m1-resources h3 {
			color: white;
			font-weight: 400;
			background-color: var(--color-credit);
			margin: 0;
			padding: 0.5em 1em;
			font-size: 1.25em;
		}
		.m1-resources ul {
			color: white;
			margin: 0 0 2em 0;
			padding: 0.5em 2em 1em;
			font-family: Montserrat, Arial, Helvetica, sans-serif;
			font-weight: 200;
			background-color: #757DA9;
		}
		.m1-resources ul li a {
			color: white;
			text-decoration: underline;
		}
		.m1-button-wrap {
			display: flex;
			align-content: center;
			flex-flow: row wrap;
		}
		.m1-button-wrap .m1-button {
			width: 100%;
			padding: 0.5em 1em;
			margin: 0 0 1em 0;
			background-color: #90AFDE;
			position: relative;
			height: 0;
			padding: 0 0 calc(100%/2.75) 0;
		}
		.m1-button-wrap .m1-button a {
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
		.m1-button-wrap .m1-button a img {
			width: 32px;
			margin-top: 0.25em;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/1);
				background-image: url(https://cdn.hpm.io/assets/images/Mothers-First-Tablet-1600x1600.jpg);
			}
			.m1-button-wrap {
				display: flex;
				align-content: center;
				justify-content: space-between;
				flex-flow: row nowrap;
			}
			.m1-button-wrap .m1-button {
				width: 32%;
				padding: 0 0 calc(32%/2) 0;
			}
			.m1-button-wrap .m1-button a {
				font-size: 1.375em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/2);
				background-image: url(https://cdn.hpm.io/assets/images/Mothers-First-Desktop-2400x1200.jpg);
			}
			.page-template-page-mothersfirst article {
				padding: 0;
				margin: 0;
				width: 100%;
				border-right: 0;
				float: none;
			}
			.m1-head {
				padding: 2em 6em;
			}
			.page-template-page-mothersfirst .m1-eps article {
				padding: 1em 2em 2em;
				display: grid;
				grid-template-columns: 55% 45%;
				grid-template-rows: auto auto;
			}
			.m1-eps .episode-video {
				grid-column-start: 1;
				grid-row-start: 1;
			}
			.m1-eps .episode-audio {
				grid-column-start: 1;
				grid-row-start: 2;
				padding-bottom: 0;
			}
			.m1-eps .episode-content {
				grid-column-start: 2;
				grid-row-start: span 2;
				padding: 0 2em;
			}
			.m1-button-wrap .m1-button {
				padding: 0 0 calc(32%/2.5) 0;
			}
			.m1-button-wrap .m1-button a {
				font-size: 1.5em;
			}
			.m1-resources {
				padding: 2em;
			}
		}
	</style>
<?php get_footer(); ?>