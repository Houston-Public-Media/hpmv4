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
				$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
				$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true );
				$header_back = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$show_title = get_the_title();
				$show_content = get_the_content();
				$page_head_style = '';
				$page_head_class = '';
				if ( !empty( $show['banners']['mobile'] ) || !empty( $show['banners']['tablet'] ) || !empty( $show['banners']['desktop'] ) ) :
					$page_head_class = ' shows-banner-variable';
					foreach ( $show['banners'] as $bk => $bv ) :
						if ( $bk == 'mobile' ) :
							$page_head_style .= ".page-header.shows-banner-variable { background-image: url(".wp_get_attachment_url( $bv )."); }";
						elseif ( $bk == 'tablet' ) :
							$page_head_style .= " @media screen and (min-width: 30.0625em) { .page-header.shows-banner-variable { background-image: url(".wp_get_attachment_url( $bv )."); } }";
						elseif ( $bk == 'desktop' ) :
							$page_head_style .= " @media screen and (min-width: 50.0625em) { .page-header.shows-banner-variable { background-image: url(".wp_get_attachment_url( $bv )."); } }";
						endif;
					endforeach;
				elseif ( !empty( $header_back[0] ) ) :
					$page_head_style = ".page-header { background-image: url($header_back[0]); }";
				else :
					$page_head_class = ' no-back';
				endif;
				if ( !empty( $page_head_style ) ) :
					echo "<style>".$page_head_style."</style>";
				endif; ?>
			<header class="page-header<?php echo $page_head_class; ?>">
				<h1 class="page-title<?php echo (!empty( $header_back ) ? ' screen-reader-text' : ''); ?>"><?php the_title(); ?></h1>
			</header>
			<?php
				$no = $sp = $c = 0;
				foreach( $show as $sk => $sh ) :
					if ( !empty( $sh ) && $sk != 'banners' ) :
						$no++;
					endif;
				endforeach;
				foreach( $social as $soc ) :
					if ( !empty( $soc ) ) :
						$no++;
					endif;
				endforeach;
				if ( $no > 0 ) : ?>
			<div id="station-social">
			<?php
					if ( !empty( $show['times'] ) ) : ?>
				<h3><?php echo $show['times']; ?></h3>
			<?php
					endif;
					echo HPM_Podcasts::show_social( $show['podcast'], false, get_the_ID() ); ?>
			</div>
			<?php
				endif;?>
		<?php
			endwhile; ?>
			<aside class="column-right grid-item stamp">
				<h3>About <?php echo $show_title; ?></h3>
				<div class="show-content">
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</div>
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
			</aside>
			<div class="column-left">
				<p><iframe src="//hpm-rss.streamguys1.com/player/playlist2005181441075.html" style="width: 100%; height: 500px;"></iframe></p>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>