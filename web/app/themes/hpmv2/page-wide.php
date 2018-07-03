<?php
/*
Template Name: Full-Width Page
*/
	get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post();
				if ( has_post_thumbnail() ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: 0;">
				<header class="entry-header" style="padding: 0 0 1em 0;">
					<div class="post-thumbnail" style="margin: 0;">
					<?php
						the_post_thumbnail( 'full' );
						the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' );
						$thumb_caption = get_post(get_post_thumbnail_id())->post_excerpt;
						if (!empty($thumb_caption)) :
							echo "<p>".$thumb_caption."</p>";
						endif; ?>
					</div><!-- .post-thumbnail -->
				<?PHP
					else : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
				<?php
					the_title( '<h1 class="entry-title">', '</h1>' );
					endif; ?>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
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
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>