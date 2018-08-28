<?php
/*
Template Name: Vietnam Stories
*/

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="column-span">
                <?PHP while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header vietheader">
					    <?php the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' ); ?>
					</header><!-- .entry-header -->
					<div class="entry-content">
					    <?php the_content(); ?>
					</div><!-- .entry-content -->
					<footer class="entry-footer">
				        <?PHP edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-## -->
			    <?php endwhile; ?>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>