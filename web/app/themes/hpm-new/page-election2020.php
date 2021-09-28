<?php
/*
Template Name: Election 2020
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
		.entry-content {
			overflow: hidden;
			padding: 2.5em 1em 1em;
		}
		.entry-content p {
			margin-bottom: 1em;
		}
		@media screen and (min-width: 34em) {
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/4);
				margin: 0 !important;
				background-image: url(https://cdn.hpm.io/assets/images/Election-2020_tablet-2x.jpg);
			}
		}
		@media screen and (min-width: 52.5em) {
			.page-template-page-election2020 article {
				width: 100%;
				float: none;
				border-right: 0;
			}
			.page-header {
				padding-right: 0;
				padding-left: 0;
				padding-top: 0;
				padding-bottom: calc(100%/6);
				margin: 0 !important;
				background-image: url(https://cdn.hpm.io/assets/images/Election-2020_-desktop-2x.jpg);
			}
		}
	</style>
<?php get_footer(); ?>