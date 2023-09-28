<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
global $ka;
$indepth = false;
$breaking = false;
$extra = 'card';
$size = 'thumbnail';
if ( $ka !== null ) {
	if ( $ka == 0 ) {
		$extra .= ' card-large';
		$size = 'large';
	} elseif ( $ka == 1 ) {
		$extra .= ' card-medium';
	}
}
$postClass = get_post_class();
if ( is_home() && $ka !== null ) {
	$hpm_priority = get_option( 'hpm_priority' );
	$indepth_slot = (int)$hpm_priority['inDepthnumber'] - 1;
	if ( in_array( 'category-in-depth', $postClass ) && $ka === $indepth_slot ) {
		$indepth = true;
	} elseif ( in_array( 'tag-breaking-news', $postClass ) && $ka === 0 ) {
		$breaking = true;
	}
} ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $extra ); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $size ) ?></a>
	<?php } ?>
	<div class="card-content">
		<header class="entry-header">
			<?php echo ( $indepth ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.houstonpublicmedia.org/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
			<?php // echo ( $breaking ? '<h3 class="breaking-news">Breaking News</h3>' : '' ); ?>
			<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php
				if ( is_front_page() ) {
					$alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
					if ( !empty( $alt_headline ) ) {
						echo $alt_headline;
					} else {
						the_title();
					}
				} else {
					the_title();
				} ?></a></h2>
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
				_x( 'Posted on', 'Used before publish date.', 'hpmv4' ),
				$time_string
			);
			echo " &middot; ".$summary; ?></p>
		</div>
		<footer class="entry-footer">
			<?php
				$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
				if ( $tags_list ) {
					printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
						_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
						$tags_list
					);
				}
				edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
		</footer>
	</div>
</article>