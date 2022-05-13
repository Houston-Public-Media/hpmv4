<?php
/*
Template Name: Default Show
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
<?php
				if ( !empty( $show['podcast'] ) ) :
					$pod_link = get_post_meta( $show['podcast'], 'hpm_pod_link', true );
					if ( !empty( $pod_link['itunes'] ) ) :
						//echo '<article class="card card-medium"><iframe allow="autoplay *; encrypted-media *; fullscreen *" frameborder="0" height="450" style="width:100%;overflow:hidden;background:transparent;" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-storage-access-by-user-activation allow-top-navigation-by-user-activation" src="'.str_replace( 'https://podcasts.apple.com', 'https://embed.podcasts.apple.com', $pod_link['itunes'] ).'"></iframe></article>';
					endif;
				endif;
				foreach ( $episodes as $ka => $va ) :
					$post = $va;
					get_template_part( 'content', get_post_format() );
				endforeach;
				if ( count( $episodes ) > 15 ) :
					$cat_no = get_post_meta( $show_id, 'hpm_shows_cat', true );
					$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
					$term = reset( $terms ); ?>
			<?php endif; ?>
			</section>
			<div class="readmore">
				<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
			</div>
		<?php if ( !empty( $show['ytp'] ) ) : ?>
			<section id="shows-youtube">
			<?php
				$json = hpm_youtube_playlist( $show['ytp'] );
				foreach ( $json as $c => $tubes ) :
					$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
					if ( $c == 0 ) : ?>
				<div id="youtube-main">
					<div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
						<?php echo hpm_svg_output( 'play' ); ?>
					</div>
					<h2><?php echo $tubes['snippet']['title']; ?></h2>
					<p class="desc"><?php echo $tubes['snippet']['description']; ?></p>
					<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
				</div>
				<div id="youtube-upcoming">
					<h4>Past Shows</h4>
			<?php
					endif; ?>
					<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdate="<?php echo date( 'F j, Y', $pubtime); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
						<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
						<h2><?php echo $tubes['snippet']['title']; ?></h2>
						<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
					</div>
			<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>
		</main>
	</div>
<?php get_footer(); ?>