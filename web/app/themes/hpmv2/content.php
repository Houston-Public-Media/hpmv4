<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if ( has_post_thumbnail() ) : ?>
	<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
	</div>
	<div class="search-result-content">
	<?php
		else : ?>
	<div class="search-result-content-full">
	<?php
		endif; ?>
		<header class="entry-header">
			<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
		</header><!-- .entry-header -->
		<div class="entry-summary">
			<p>
		<?php
			$summary = strip_tags( get_the_excerpt() );
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	
			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				get_the_date( 'F j, Y' )
			);
	
			printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
				$time_string
			);
			echo " &middot; ".$summary; 
		?>
			</p>
		</div><!-- .entry-summary -->
	
		<?php if ( 'post' == get_post_type() ) : ?>
	
			<footer class="entry-footer">
				<?php 
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) :
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					endif;
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
			</footer><!-- .entry-footer -->
	
		<?php else : ?>
	
			<?php edit_post_link( __( 'Edit', 'hpmv2' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>
	
		<?php endif; ?>
	</div>
</article><!-- #post-## -->
