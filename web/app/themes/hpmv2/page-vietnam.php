<?php
/*
Template Name: Vietnam Landing
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
			<?php
                wp_reset_query();
				/*$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
				if ( !empty( $cat_no ) ) :
					$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
					$term = reset( $terms );
					$cat = new WP_query( array(
						'cat' => $cat_no,
						'orderby' => 'date',
						'order'   => 'DESC',
                        'posts_per_page' => 15
					) );
					if ( $cat->have_posts() ) : ?>
            <section id="search-results">
		<?php
						while ( $cat->have_posts() ) : $cat->the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
						if ( $cat->found_posts > 15 ) : ?>
                <div class="readmore">
                    <a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
                </div>
                <?php
                        endif;
		                wp_reset_postdata(); ?>
            </section>
			<?php
					endif;
				endif; ?>

			<aside class="column-right">
                <?php get_template_part( 'sidebar', 'none' ); ?>
			</aside><?php */ ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>