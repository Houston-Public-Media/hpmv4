<?php
/*
Template Name: DFP Test
*/
	get_header('test'); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php 						
						the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<p class="byline-date screen-reader-text">
					<?PHP
						coauthors_posts_links( ', ', ', ', '', '', true );
						echo " | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

						if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
							$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> (Updated: <time class="updated" datetime="%3$s">%4$s</time>';
						}

						$time_string = sprintf( $time_string,
							esc_attr( get_the_date( 'c' ) ),
							get_the_date(),
							esc_attr( get_the_modified_date( 'c' ) ),
							get_the_modified_date()
						);

						printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
							$time_string
						);
					?>
					</p>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						if ( has_post_thumbnail() ) :
					?>
					<div class="post-thumbnail">
						<?php 
							the_post_thumbnail( 'hpm-large' );
							$thumb_caption = get_post(get_post_thumbnail_id())->post_excerpt;
							if (!empty($thumb_caption)) :
								echo "<p>".$thumb_caption."</p>";
							endif;
						?>
					</div><!-- .post-thumbnail -->
					<?PHP
						endif;
						the_content( sprintf(
							__( 'Continue reading %s', 'hpmv2' ),
							the_title( '<span class="screen-reader-text">', '</span>', false )
						) );
					?>
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

<?php get_footer(); ?>
