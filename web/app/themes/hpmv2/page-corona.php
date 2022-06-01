<?php
/*
Template Name: Corona
*/
get_header(); ?>
	<style>
		:root {
			--plyr-audio-control-color: #ed1c24;
		}
		#div-gpt-ad-1488818411584-0 {
			display: none !important;
		}
		.page-content {
			overflow: hidden;
			padding: 1em 0;
		}
		body.page #main {
			background-color: transparent;
		}
		.page-content p {
			margin-bottom: 1em;
		}
		.page-header {
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
			height: 0;
			padding-right: 0;
			padding-left: 0;
			padding-top: 0;
			padding-bottom: calc(100%/1.5);
			position: relative;
			background-image: url(https://cdn.hpm.io/assets/images/covid19_Mobile.png);
		}
		.page-header .page-header-wrap {
			display: flex;
			align-content: center;
			align-items: center;
			justify-items: flex-start;
			width: 100%;
			height: 100%;
			position: absolute;
			flex-flow: row wrap
		}
		.page-header .page-header-wrap div {
			padding: 1em;
		}
		.page-header h1 {
			color: white;
			margin: 0;
			font-family: var(--hpm-font-main);
			font-weight: 900;
			width: 100%;
			font-size: 500%;
			line-height: 100%;
		}
		.page-header p {
			font-size: 125%;
			width: 100%;
			font-family: var(--hpm-font-main);
			font-weight: 500;
			color: white;
			margin: 0;
		}
		.page.page-template-page-corona #main > article {
			padding: 0;
			margin: 0;
		}
		.corona-links a {
			width: 100%;
			margin: 0 0 1em;
			padding: 1em;
			font-size: 125%;
			color: #000;
			background-color: rgba( 255, 0, 0, 0.2 );
			display: block;
			position: relative;
		}
		.corona-links a svg {
			width: 2rem;
			height: 2rem;
			float: left;
			margin-right: 0.5rem;
			fill: var(--main-red);
		}
		.corona-links p {
			margin: 0;
			padding: 0;
		}
		h2 {
			font-size: 150%;
			border-bottom: 1px solid #808080;
			padding: 0 0 0.5em 0;
			margin-bottom: 1em;
			color: #cc0000;
		}
		.column-left article h2,
		#search-results article h2 {
			padding: 0;
			font-size: 125%;
			border: 0;
		}
		#npr-side article h2 {
			padding: 0;
			font-size: 125%;
			border: 0;
			margin: 0;
		}
		#search-results article .entry-summary {
			padding: 0;
		}
		#search-results article {
			display: flex;
			justify-content: center;
			align-content: center;
			align-items: center;
			flex-flow: column nowrap;
		}
		#search-results article .thumbnail-wrap {
			padding: 0 0 calc(100%/1.5) 0;
			width: 100%;
			height: 0;
		}
		#search-results article.town-square-feature {
			background-color: #005790;
			padding: 1em;
			display: flex;
			align-items: center;
		}
		#search-results article.town-square-feature a {
			color: #ffce16;
		}
		#search-results article.town-square-feature .img-wrap {
			width: 100%;
			padding-bottom: 1em;
		}
		#search-results article.town-square-feature .img-wrap p {
			margin: 0;
			padding: 0;
			color: white;
			text-align: center;
		}
		#search-results article.town-square-feature .img-wrap img {
			height: max(18vh, 12rem);
			object-fit: cover;
			width: 100%;
		}
		@supports (aspect-ratio: 1) {
			#search-results article.town-square-feature .img-wrap img {
				aspect-ratio: 3/2;
				height: auto;
			}
		}
		#search-results article.town-square-feature .article-player-wrap {
			background: var(--plyr-audio-controls-background,#fff);
			padding: 0;
		}
		#search-results article.town-square-feature .article-player-wrap h2 {
			padding: 0.5em 1.125em 0;
			margin: 0;
			font-size: 100%;
		}
		#search-results article.town-square-feature .article-player-wrap h2 a {
			color: #005790;
		}
		.corona-local {
			background-color: rgba(255,0,0,0.1);
		}
		.corona-local h2 {
			background-color: #cc0000;
			color: white;
			padding: 0.5rem 1rem;
		}
		.corona-local h3 {
			padding: 0 1rem;
		}
		.corona-local ul {
			padding: 0 1rem 1rem 2rem;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/4);
				background-image: url(https://cdn.hpm.io/assets/images/covid19_Tablet.png);
			}
			#search-results article .thumbnail-wrap {
				float: left;
				width: 33%;
				padding: 0 0 calc(33%/1.5) 0;
				margin: 0;
			}
			#search-results article.town-square-feature .img-wrap {
				float: left;
				width: 33%;
				padding-bottom: 0;
			}
			#search-results article {
				flex-flow: row nowrap;
			}
			#search-results article.town-square-feature .entry-header {
				float: right;
				width: 67%;
				padding-left: 1em;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page.page-template-page-corona #main > article {
				width: 100%;
				float: none;
				border-right: 0;
			}
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/6);
				background-image: url(https://cdn.hpm.io/assets/images/covid19_Desktop.png);
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<h1 class="page-title screen-reader-text"><?php echo get_the_title(); ?></h1>
				</header><!-- .entry-header -->
				<div class="page-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->
				<footer class="page-footer">
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
		<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>