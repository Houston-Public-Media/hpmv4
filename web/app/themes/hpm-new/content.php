<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
global $ka;
$indepth = false;
$extra = 'card';
$size = 'thumbnail';
if ( $ka !== null ) :
	if ( $ka == 0 ) :
		$extra .= ' card-large';
		$size = 'large';
	elseif ( $ka == 1 ) :
		$extra .= ' card-medium';
	endif;
endif;
$postClass = get_post_class();
if ( is_home() && in_array( 'category-in-depth', $postClass ) && ( $ka !== null && $ka == 1 ) ) :
	$indepth = true;
endif; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $extra ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"><?php the_post_thumbnail( $size ) ?></a>
	<?php endif; ?>
	<div class="card-content">
		<header class="entry-header">
			<?php echo ( $indepth ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.hpm.io/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
			<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php
				if ( is_front_page() ) :
					$alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
					if ( !empty( $alt_headline ) ) :
						echo $alt_headline;
					else :
						the_title();
					endif;
				else :
					the_title();
				endif; ?></a></h2>
            <div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
		</header>
		<div class="entry-summary">
			<p><?php
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
			echo " &middot; ".$summary; ?></p>
		</div>
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
		</footer>
	</div>
</article>