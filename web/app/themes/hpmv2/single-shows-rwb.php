<?php
/*
Template Name: Red, White and Blue
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
				$categories = get_the_category();
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
				endif;
			endwhile; ?>
			<div id="shows-youtube">
				<div id="youtube-wrap">
					<div class="column-right">
						<h3>About</h3>
						<div class="show-content">
							<?php echo apply_filters( 'the_content', $show_content ); ?>
						</div>
		</div>
				<?php
					$json = hpm_youtube_playlist( $show['ytp'], 10 );
					foreach ( $json as $tubes ) :
						$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
						if ( $c == 0 ) : ?>
					<div id="youtube-main">
						<div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
							<span class="fab fa-youtube" id="play-button"></span>
						</div>
						<h2><?php echo $tubes['snippet']['title']; ?></h2>
						<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						<p class="desc"><?php echo $tubes['snippet']['description']; ?></p>
					</div>
					<div id="youtube-upcoming">
						<h4>Previous Episodes</h4>
					<?php
						endif; ?>
						<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdate="<?php echo date( 'F j, Y', $pubtime); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
							<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
							<h2><?php echo $tubes['snippet']['title']; ?></h2>
							<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						</div>
					<?php
						$c++;
					endforeach; ?>
					</div>
					<div class="readmore">
						<a href="<?php echo $social['yt']; ?>">View More Episodes</a>
					</div>
				</div>
			</div>
			<div id="rwb-austin">
				<div class="rwb-austin-desc">
					<img src="https://cdn.hpm.io/assets/images/austin-polland.jpg" alt="Mr. Polland Goes to Austin" />
					<div class="rwb-austin-desc-wrap">
						<h3>Mr. Polland Goes to Austin</h3>
						<p>Host Gary Polland travels to the Texas Capitol to check in with Texas legislators as they conduct the business of the 86th legislative session. This special edition includes exclusive interviews with Speaker of the House Dennis Bonnen, Senator Paul Bettencourt (R-Houston), Representative John Zerwas (R-Richmond), Representative Harold Dutton (D-Houston), and Representative James White (R-Hillister).</p>
					</div>
				</div>
				<div class="rwb-austin-slideshow">
				<?php
					$a_json = hpm_youtube_playlist( 'PLGHyNdqkLN-CFayr3GPM4r4zDDBGqpAmP', 15 );
					foreach ( $a_json as $aj ) : ?>
					<div>
						<p><iframe src="https://www.youtube.com/embed/<?php echo $aj['snippet']['resourceId']['videoId']; ?>?rel=0&showinfo=0&enablejsapi=1" width="560" height="315" frameborder="0" allowfullscreen="allowfullscreen" id="<?php echo $aj['snippet']['resourceId']['videoId']; ?>"></iframe></p>
						<h4><?php echo htmlentities( $aj['snippet']['title'], ENT_COMPAT ); ?></h4>
					</div>
				<?php
					endforeach; ?>
				</div>
			</div>
			<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick.min.css" />
			<link rel="stylesheet" href="https://cdn.hpm.io/assets/js/slick/slick-theme.css" />
			<script src="https://cdn.hpm.io/assets/js/slick/slick.min.js"></script>
			<script>
				jQuery(document).ready(function($){
					var options = { slidesToShow: 3, rows: 1, slidesToScroll: 3, infinite: false, autoplay: false, lazyLoad: 'ondemand', responsive: [ { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } }, { breakpoint: 800, settings: { slidesToShow: 2, slidesToScroll: 2, rows: 1 } }, { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, rows: 2 } }] };
					$('.rwb-austin-slideshow').slick(options);
				});
			</script>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>