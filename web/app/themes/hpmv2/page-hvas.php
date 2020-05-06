<?php
/*
Template Name: Arts Virtual
*/
	get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="page-header"></div>
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header screen-reader-text">
					<?php
						the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				<div class="entry-content">
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
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<style>
		.page-content {
			overflow: hidden;
			padding: 2.5em 1em 1em;
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
			background-image: url(https://cdn.hpm.io/assets/images/havs_mobile2x.jpeg);
		}
		.hvas-contain {
			padding-bottom: 2em;
		}
		.hvas-contain h2 {
			text-align: center;
			font-size: 175%;
			padding: 0.5em;
			margin: 0;
			color: white;
		}
		.hvas-contain .hvas-contain-org {
			padding: 1em;
			margin-bottom: 1em;
		}
		.hvas-contain .hvas-contain-org h3 {
			margin: 0;
			padding: 0;
		}
		.hvas-contain .hvas-contain-org ul {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		.hvas-contain .hvas-contain-org ul li {
			margin: 0;
			padding: 0.5em 0;
			color: #404040;
		}
		.hvas-contain .hvas-contain-org ul li a {
			text-decoration: underline;
			color: #404040;
		}
		.hvas-contain.music h2 {
			background-color: #C8102E;
		}
		.hvas-contain.arts h2 {
			background-color: #FFCE16;
		}
		.hvas-contain.museum h2 {
			background-color: #4FC4CD;
		}
		.hvas-contain.music h3 {
			color: #C8102E;
		}
		.hvas-contain.arts h3 {
			color: #FFCE16;
		}
		.hvas-contain.museum h3 {
			color: #4FC4CD;
		}
		.hvas-contain.music .hvas-contain-org {
			background-color: #FAE9EC;
		}
		.hvas-contain.arts .hvas-contain-org {
			background-color: #FFF7E6;
		}
		.hvas-contain.museum .hvas-contain-org {
			background-color: #E7F7F8;
		}
		.hvas-contain {
			display: none;
		}
		.hvas-contain.hvas-active {
			display: block;
		}
		.hvas-select {
			width: 100%;
			border-bottom: 1px solid #808080;
			display: flex;
		}
		.hvas-select div {
			width: 33.333333%;
			text-align: center;
			padding: 0.5em 0;
			border-bottom: 3px solid transparent;
			position: relative;
			bottom: -2px;
			font: normal 1.5em/1.125em 'MiloOT-Bold',arial,helvetica,sans-serif;
			z-index: 100;
		}
		.hvas-select div:hover {
			cursor: pointer;
		}
		#hvas-music {
			color: #C8102E;
		}
		#hvas-music.hvas-active {
			border-bottom: 3px solid #C8102E;
			background-color: #f6f6f6;
		}
		#hvas-museum {
			color: #4FC4CD;
		}
		#hvas-museum.hvas-active {
			border-bottom: 3px solid #4FC4CD;
			background-color: #f6f6f6;
		}
		#hvas-arts {
			color: #FFCE16;
		}
		#hvas-arts.hvas-active {
			border-bottom: 3px solid #FFCE16;
			background-color: #f6f6f6;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/4);
				background-image: url(https://cdn.hpm.io/assets/images/havs_tablet2x.jpeg);
			}
		}
		@media screen and (min-width: 50.0625em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/6);
				background-image: url(https://cdn.hpm.io/assets/images/havs_desktop2x.jpeg);
			}
		}
	</style>
	<script>
		jQuery(document).ready(function($){
			$('.hvas-select div').click(function(e){
				e.preventDefault();
				var id = $(this).attr('id');
				if ( !$(this).hasClass('hvas-active') ) {
					$('.hvas-select div').removeClass('hvas-active');
					$('.hvas-contain').removeClass('hvas-active');
					$(this).addClass('hvas-active');
					$('#'+id+'-tab').addClass('hvas-active');
				} else {
					return false;
				}
			});
		});</script>
<?php get_footer(); ?>