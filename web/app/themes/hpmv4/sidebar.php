<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
	echo HPM_Promos::generate_static( 'sidebar' ); ?>
<section class="sidebar-ad">
	<h4>Support Comes From</h4>
<?php
	if ( $pagename == 'about' ) { ?>
	<div id="div-gpt-ad-1579034137004-0">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1579034137004-0'); });
		</script>
	</div>
<?php
	} else { ?>
	<div id="div-gpt-ad-1394579228932-1">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
		</script>
	</div>
<?php
	} ?>
</section>
<?php
	global $post;
	if ( !empty( $post ) ) {
		$tags = wp_get_post_tags( $post->ID );

		if ( $tags ) {
			$tag_ids = [];
			foreach( $tags as $individual_tag ) {
				$tag_ids[] = $individual_tag->term_id;
			}
			$args = [
				'tag__in' => $tag_ids,
				'post__not_in' => [ $post->ID ],
				'posts_per_page'=> 4,
				'ignore_sticky_posts'=> 1
			];
			$my_query = new WP_Query( $args );
			if ( $my_query->have_posts() ) { ?>
<section class="highlights">
	<h4>Related</h4>
<?php
				while ( $my_query->have_posts() ) {
					$my_query->the_post();
					get_template_part( 'content', get_post_format() );
				} ?>
</section>
<?php
			}
		}
	}
	wp_reset_query();
	hpm_top_posts(); ?>
<section class="sidebar-ad">
	<h4>Support Comes From</h4>
	<div id="div-gpt-ad-1394579228932-2">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
		</script>
	</div>
</section>