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
					echo HPM_Podcasts::show_social( $show['podcast'], false, get_the_ID() ); ?>
			</div>
			<?php
				endif;?>
		<?php
			endwhile;
			$t = time();
			$offset = get_option('gmt_offset')*3600;
			$t = $t + $offset;
			$now = getdate($t);
			if ( !empty( $_GET['testtime'] ) ) :
				$tt = explode( '-', $_GET['testtime'] );
				$now = getdate( mktime( $tt[0], $tt[1], 0, $tt[2], $tt[3], $tt[4] ) );
			endif;
			if ( $now[0] > mktime( 17, 0, 0, 8, 19, 2019 ) ) :?>
			<section id="country-covers">
				<div id="shows-youtube">
					<div id="youtube-wrap">
						<div class="column-right">
							<a href="http://claimittexas.org" target="_blank"><img src="https://cdn.hpm.io/assets/images/cc_logo_sponsor2x.png" alt="Skyline Sessions Country Covers" class="" /></a>
							<div class="show-content">
								<p><em>Country Covers</em> is a spin-off of our digital music series <em>Skyline Sessions</em> and features a variety of musicians performing their favorite country classics and sharing personal stories of their love for country music. <em>Country Covers</em> is Houston Public Media's companion piece to Ken Burns' new documentary series <em>Country Music</em>.</p>
								<h2>Watch on TV 8</h2>
								<p><strong>Thursday, September 8</strong> | 9pm & 11:30pm<br /><strong>Sunday, September 15</strong> | 3:30pm<br /><strong>Tuesday, September 17</strong> | 11pm
								</p>
							</div>
						</div>
<?php
					// PL1bastN9fY1iS4PbKjIgEE6dPebMeuJzB
					$json = hpm_youtube_playlist( 'PL1bastN9fY1iS4PbKjIgEE6dPebMeuJzB', 50 );
					$r = rand( 0, count( $json ) - 1 ); ?>
						<div id="youtube-main">
							<div id="youtube-player" style="background-image: url( '<?php echo $json[$r]['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $json[$r]['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $json[$r]['snippet']['title'], ENT_COMPAT ); ?>">
								<span class="fab fa-youtube" id="play-button"></span>
							</div>
							<h2><?php echo $json[$r]['snippet']['title']; ?></h2>
							<p class="desc"><?php echo $json[$r]['snippet']['description']; ?></p>
						</div>
						<div id="youtube-upcoming">
<?php
						foreach ( $json as $tubes ) : ?>
							<div>
								<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
									<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
									<h2><?php echo $tubes['snippet']['title']; ?></h2>
								</div>
							</div>
						<?php
						endforeach; ?>
						</div>
					</div>
				</div>
			</section>
			<section id="country-music">
				<img src="https://cdn.hpm.io/assets/images/cm_kenburns_logo2x.png" alt="Country Music, a Film by Ken Burns">
				<p>The first 4 episodes will air nightly from Sunday, September 15, through Wednesday, September 18, and the final four episodes will air nightly from Sunday, September 22, through Wednesday, September 25. Each episode will premiere at 7:00pm.</p>
			</section>
<?php
			endif; ?>
			<aside class="column-right">
				<h3>About <?php echo $show_title; ?></h3>
				<div class="show-content">
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</div>
			<?php
						if ( $show_name == 'skyline-sessions' || $show_name == 'music-in-the-making' ) :
							$googletag = 'div-gpt-ad-1470409396951-0';
						else :
							$googletag = 'div-gpt-ad-1394579228932-1';
						endif; ?>
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="<?php echo $googletag; ?>">
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
	<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick.min.css" />
			<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick-theme.css" />
			<script src="https://cdn.hpm.io/assets/js/slick/slick.min.js"></script>
			<script>
				jQuery(document).ready(function($){
					var options = { slidesToShow: 3, rows: 1, slidesToScroll: 3, infinite: false, autoplay: false, lazyLoad: 'ondemand', responsive: [ { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } }, { breakpoint: 800, settings: { slidesToShow: 3, slidesToScroll: 3, rows: 1 } }, { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, rows: 3 } }] };
					$('#youtube-upcoming').slick(options);
				});
			</script>
<?php get_footer(); ?>