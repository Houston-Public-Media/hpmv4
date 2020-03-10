<?php
/*
Template Name: Corona
*/
get_header(); ?>
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
	<style>
		#div-gpt-ad-1488818411584-0 {
			display: none !important;
		}
		.page-content {
			overflow: hidden;
			padding: 2.5em 1em 1em;
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
			font-family: 'MiloOT-XBold',arial,helvetica,sans-serif;
			width: 100%;
			font-size: 500%;
			line-height: 100%;
		}
		.page-header p {
			font-size: 125%;
			width: 100%;
			font-family: 'MiloOT-Medi',arial,helvetica,sans-serif;
			color: white;
			margin: 0;
		}
		.page-template-page-corona article {
			padding: 0;
			margin: 0;
		}
		.corona-links a {
			width: 90%;
			margin: 0 5% 1em;
			padding: 1em 1em 1em 3em;
			font-size: 125%;
			color: #000;
			background-color: rgba( 255, 0, 0, 0.2 );
			display: block;
			position: relative;
		}
		.corona-links a .fa {
			color: #cc0000;
			font-size: 1.5em;
			position: absolute;
			top: 0.55em;
			left: 0.375em;
			text-align: center;
			width: 1.5em;
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
		}
		.corona-local {
			background-color: rgba(255,0,0,0.1);
		}
		.corona-local h2 {
			background-color: #cc0000;
			color: white;
			padding: 0.5em 1em;
		}
		.corona-local ul {
			padding: 0 1em 4em 1em;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/4);
				background-image: url(https://cdn.hpm.io/assets/images/covid19_Tablet.png);
			}
		}
		@media screen and (min-width: 50.0625em) {
			.page-template-page-corona article {
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
<?php get_footer(); ?>