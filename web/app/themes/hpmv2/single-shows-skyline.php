<?php
/*
Template Name: Skyline Sessions
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
					if ( !empty( $show['gplay'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $show['gplay']; ?>" target="_blank" title="Google Play Podcasts Feed"><span class="fa fa-google" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $show['podcast'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $show['podcast']; ?>" target="_blank" title="Podcast Feed"><span class="fa fa-rss" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $show['itunes'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $show['itunes']; ?>" target="_blank" title="iTunes Feed"><span class="fa fa-apple" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['snapchat'] ) ) : ?>
				<div class="station-social-icon">
					<a href="http://www.snapchat.com/add/<?php echo $social['snapchat']; ?>" target="_blank" title="Snapchat"><span class="fa fa-snapchat-ghost" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['tumblr'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $social['tumblr']; ?>" target="_blank" title="Tumblr"><span class="fa fa-tumblr" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['insta'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://instagram.com/<?php echo $social['insta']; ?>" target="_blank" title="Instagram"><span class="fa fa-instagram" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['sc'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://soundcloud.com/<?php echo $social['sc']; ?>" target="_blank" title="SoundCloud"><span class="fa fa-soundcloud" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['yt'] ) ) : ?>
				<div class="station-social-icon">
					<a href="<?php echo $social['yt']; ?>" target="_blank" title="YouTube"><span class="fa fa-youtube-play" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['twitter'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://twitter.com/<?php echo $social['twitter']; ?>" target="_blank" title="Twitter"><span class="fa fa-twitter" aria-hidden="true"></span></a>
				</div>
			<?php
					endif;
					if ( !empty( $social['fb'] ) ) : ?>
				<div class="station-social-icon">
					<a href="https://www.facebook.com/<?php echo $social['fb']; ?>" target="_blank" title="Facebook"><span class="fa fa-facebook" aria-hidden="true"></span></a>
				</div>
			<?php
					endif; ?>
			</div>
			<?php 
				endif;?>
		<?php
			endwhile; ?>
			<aside class="column-right">
				<h3>About <?php echo $show_title; ?></h3>
				<div class="show-content">
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</div>
			<?php
						echo HPM_Listings::generate( $show_name );
						if ( $show_name == 'skyline-sessions' || $show_name == 'music-in-the-making' ) :
							$googletag = 'div-gpt-ad-1470409396951-0';
						else :
							$googletag = 'div-gpt-ad-1394579228932-1';
						endif; ?>
				<div class="sidebar-ad">
					<div id="<?php echo $googletag; ?>">
						<h4>Support Comes From</h4>
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('<?php echo $googletag; ?>'); });
						</script>
					</div>
				</div>
			</aside>
			<div id="float-wrap" class="column-left">
		<?php
			$studio = new WP_Query([
				'category__in' => [ 38141 ],
				'posts_per_page' => 14,
				'ignore_sticky_posts' => 1
			]);
			$others = new WP_Query([
				'category__in' => [ 68 ],
				'category__not_in' => [ 38141 ],
				'posts_per_page' => 6,
				'ignore_sticky_posts' => 1
			]);
			
			if ( $studio->have_posts() ) :
				while ( $studio->have_posts() ) : $studio->the_post();
					$postClass = get_post_class();
					$postClass[] = 'grid-item'; ?>
					<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
						<?php
						if ( has_post_thumbnail() ) : ?>
							<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
								<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
							</div>
						<?php
						endif; ?>
						<header class="entry-header">
							<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
						</header><!-- .entry-header -->
					</article>
				<?PHP
				endwhile;
			endif;
			wp_reset_query(); ?>
				<div class="readmore" style="clear: both; width: 100%">
					<a href="/topics/in-studio/page/2">View More Performances</a>
				</div>
			</div>
				
				<div id="float-wrap" class="column-span">
<?php
			if ( $others->have_posts() ) :
				while ( $others->have_posts() ) : $others->the_post();
					$postClass = get_post_class();
					$postClass[] = 'grid-item'; ?>
					<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
						<?php
						if ( has_post_thumbnail() ) : ?>
							<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
								<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
							</div>
						<?php
						endif; ?>
						<header class="entry-header">
							<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
						</header><!-- .entry-header -->
					</article>
				<?PHP
				endwhile;
			endif;
			wp_reset_query(); ?>
			<div class="readmore" style="clear: both; width: 100%">
				<a href="/topics/skyline-sessions/page/2">View More Related Articles</a>
			</div>
			</div>
			
		</main><!-- .site-main -->
	</div><!-- .content-area -->
	<style>
		.single.shows-template-single-shows-skyline #main aside,
		.single.shows-template-single-shows-skyline #main article.post {
			-webkit-box-ordinal-group: initial;
			-moz-box-ordinal-group: initial;
			-ms-flex-order: initial;
			-webkit-order: initial;
			order: initial;
		}
	</style>
<?php get_footer(); ?>