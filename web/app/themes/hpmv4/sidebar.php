<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
	echo HPM_Promos::generate_static( 'sidebar' );
	//if ( !is_single() && get_post_type() !== 'post' ) { ?>
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
	//}
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
<section class="highlights col-sm-6 col-md-12">
	<div class="row">
		<div class="col-12 news-list-right most-view">
			<h2 class="title title-full">
				<strong>Related</strong>
			</h2>
			<ul class="list-none news-links list-dashed">
<?php
				while ( $my_query->have_posts() ) {
					$my_query->the_post();
					if ( has_post_thumbnail() ) $imgblock = get_the_post_thumbnail( "thumbnail" ); ?>
					<li>
						<a href="<?php the_permalink(); ?>"><span><?php the_title(); ?></span><span class="img-w150"><?php the_post_thumbnail( "thumbnail" ); ?></span></a>
					</li>
<?php
				} ?>
		</div>
	</div>
</section>
<?php
			}
		}
	}
	wp_reset_query(); ?>
<section class="section news-list col-sm-6 col-md-12">
	<div class="row">
		<div class="col-12 news-list-right most-view">
			<h2 class="title title-full">
				<strong>Most <span>Viewed</span></strong>
			</h2>
			<div class="news-links list-dashed">
				<?php hpm_top_posts(); ?>
			</div>
		</div>
	</div>
</section>
<?php
	//if ( !is_single() && get_post_type() !== 'post' ) { ?>
<section class="sidebar-ad col-sm-6 col-md-12">
	<h4>Support Comes From</h4>
	<div id="div-gpt-ad-1394579228932-2">
		<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
		</script>
	</div>
</section>
<?php //} ?>