<?php
/*
Template Name: HPM Newscasts
Template Post Type: shows
*/
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post();
				$show_name = $post->post_name;
				$show_id = get_the_ID();
				$show = get_post_meta( $show_id, 'hpm_show_meta', true );
				$show_title = get_the_title();
				$show_content = get_the_content();
				$episodes = HPM_Podcasts::list_episodes( $show_id );
				echo HPM_Podcasts::show_header( $show_id );
			endwhile; ?>
			<aside>
				<section>
					<h3>About <?php echo $show_title; ?></h3>
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</section>
				<section class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</section>
			</aside>
			<section>
				<p style="grid-column: 1 / -1 !important;"><iframe src="//hpm-rss.streamguys1.com/player/playlist2005181441075.html" style="width: 100%; height: 500px;"></iframe></p>
			</section>
		</main>
	</div>
<?php get_footer(); ?>