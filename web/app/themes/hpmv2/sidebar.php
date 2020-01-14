<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
?>
<div class="sidebar-ad">
	<h4>Support Comes From</h4>
<?php
	if ( $pagename == 'about' ) : ?>
	<div id="div-gpt-ad-1579034137004-0">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1579034137004-0'); });
		</script>
	</div>
<?php
	else : ?>
	<div id="div-gpt-ad-1394579228932-1">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
		</script>
	</div>
<?php
	endif; ?>
</div>
<?php
	global $post;
	$tags = wp_get_post_tags($post->ID);

	if ($tags) :
		$tag_ids = array();
		foreach($tags as $individual_tag):
			$tag_ids[] = $individual_tag->term_id;
		endforeach;
		$args = array(
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page'=> 4,
			'ignore_sticky_posts'=> 1
		);
		$my_query = new wp_query( $args );
		if ( $my_query->have_posts() ) : ?>
			<div id="related-posts">
				<h4>Related</h4>
				<?php
				while( $my_query->have_posts() ) :
					$my_query->the_post(); ?>
					<article class="related-content">
						<div class="related-image">
							<a href="<?PHP the_permalink(); ?>" class="post-thumbnail"><?PHP the_post_thumbnail( 'thumbnail' ); ?></a>
						</div>
						<div class="related-text">
							<h2><a href="<?php the_permalink(); ?>"><?PHP the_title(); ?></a></h2>
						</div>
					</article>
					<?php
				endwhile; ?>
			</div>
			<?php
		endif;
	endif;
	wp_reset_query();
	hpm_top_posts();
?>
<div class="sidebar-ad">
	<h4>Support Comes From</h4>
	<div id="div-gpt-ad-1394579228932-2">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
		</script>
	</div>
</div>