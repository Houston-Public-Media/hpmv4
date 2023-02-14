<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
$pod_link = get_post_meta( get_the_ID(), 'hpm_pod_link', true ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
	<a class="post-thumbnail" href="<?php echo $pod_link['page']; ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
	<?php } ?>
	<div class="card-content">
		<header class="entry-header">
			<h3>Podcast</h3>
			<h2 class="entry-title"><a href="<?php echo $pod_link['page']; ?>" rel="bookmark"><?php	the_title(); ?></a></h2>
            <div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
		</header>
		<div class="entry-summary">
			<p><?php echo get_the_excerpt(); ?></p>
			<ul>
				<li><a href="<?php echo $pod_link['page']; ?>">Episode Archive</a></li>
			<?php if ( !empty( $pod_link['rss-override'] ) ) { ?>
				<li><a href="<?php echo $pod_link['rss-override']; ?>">RSS Feed</a></li>
			<?php } else { ?>
				<li><a href="<?php the_permalink(); ?>">RSS Feed</a></li>
			<?php } ?>
			</ul>
			<div class="podcast-episode-info">
				<h3>Available on:</h3>
				<?php echo HPM_Podcasts::show_social( get_the_ID(), false, '', true ); ?>
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