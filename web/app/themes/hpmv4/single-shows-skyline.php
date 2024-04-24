<?php
/*
Template Name: Skyline Sessions
Template Post Type: shows
*/
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */

wp_enqueue_script('jquery');
get_header(); ?>
	<link rel="stylesheet" href="https://cdn.houstonpublicmedia.org/assets/js/slick/slick.min.css" />
	<link rel="stylesheet" href="https://cdn.houstonpublicmedia.org/assets/js/slick/slick-theme.css" />
	<style>
		body.single-shows #station-social {
			padding: 1em;
			background-color: var(--main-element-background);
			overflow: hidden;
			width: 100%;
		}
		body.single-shows .page-header {
			padding: 0;
		}
		body.single-shows .page-header .page-title {
			padding: 1rem;
		}
		body.single-shows .page-header.banner #station-social {
			margin: 0 0 1em 0;
		}
		body.single-shows #station-social h3 {
			font-size: 1.5em;
			font-family: var(--hpm-font-condensed);
			color: #3f1818;
			margin-bottom: 1rem;
		}
		#float-wrap aside {
			background-color: var(--main-element-background);
		}
		body.single-shows .podcast-badges {
			justify-content: center;
		}
		.show-content > * + * {
			margin-top: 1rem;
		}
		section#country-covers {
			max-width: 100%;
			background-color: rgb(181,159,109);
			background-image: url(https://cdn.houstonpublicmedia.org/assets/images/tan_mobile.png);
			background-position: center center;
			background-repeat: no-repeat;
			background-size: cover;
			margin-bottom: 1rem;
		}
		section#country-covers #shows-youtube {
			margin: 0;
			background-color: transparent !important;
		}
		section#country-covers .column-right {
			width: 100%;
			margin: 0;
		}
		section#country-covers .column-right img {
			margin-bottom: 0.5em;
		}
		section#country-covers .column-right .show-content p {
			color: #1F2F42;
			font-family: var(--hpm-font-main);
			font-size: 112.5%;
		}
		section#country-covers .column-right .show-content h2 {
			color: #f5f5f5;
			font-family: var(--hpm-font-main);
			font-weight: bolder;
			padding-bottom: 0.25em;
			border-bottom: 1px solid #f5f5f5;
		}
		section#country-covers #shows-youtube #youtube-main {
			padding: 0;
			background-color: transparent;
		}
		section#country-covers #shows-youtube #youtube-main h2 {
			text-transform: none;
			color: #1F2F42;
			margin-bottom: 0.25em;
		}
		section#country-covers #shows-youtube #youtube-main p {
			color: #f5f5f5;
			font: normal 1.125em/1.25em var(--hpm-font-main);
		}
		section#country-covers #shows-youtube #youtube-upcoming {
			margin: 1em 2.5%;
			width: 95%;
			background-color: transparent;
			overflow: visible;
		}
		section#country-covers #shows-youtube #youtube-upcoming .youtube h2 {
			color: #1F2F42;
		}
		section#country-covers #shows-youtube #youtube-upcoming .youtube {
			padding: 1em 0;
			flex-flow: row nowrap;
			justify-content: left;
			align-content: center;
			align-items: center;
			display: flex;
			position: relative;
		}
		section#country-covers #shows-youtube #youtube-upcoming .youtube h2 {
			font-family: var(--hpm-font-condensed);
		}
		section#country-covers .slick-prev:before,
		section#country-covers .slick-next:before {
			color: white !important;
		}
		.single.shows-template-single-shows-skyline #main aside,
		.single.shows-template-single-shows-skyline #main article.post {
			order: initial;
		}
		#youtube-wrap {
			display: block !important;
		}
		@media screen and (min-width: 34em) {
			body.single-shows #station-social {
				display: grid;
				grid-template-columns: 1fr 1.25fr;
				align-items: center;
			}
			body.single-shows #station-social.station-no-social {
				grid-template-columns: 1fr !important;
			}
			body.single-shows #station-social h3 {
				margin-bottom: 0;
			}
			section#country-covers .column-right {
				float: right;
				width: 31%;
				margin: 0 0 1em 3%;
			}
			section#country-covers #youtube-main {
				float: left;
				width: 66%;
				margin: 0 0 1em 0;
			}
			section#country-covers #shows-youtube #youtube-upcoming {
				clear: both;
				border: 0;
			}
			section#country-covers #shows-youtube #youtube-upcoming .youtube {
				width: 100%;
				float: none;
				display: block;
				padding: 1em;
			}
			section#country-covers #shows-youtube #youtube-upcoming .youtube img {
				width: 100%;
				float: none;
				padding: 0 0 0.5em 0;
			}
			section#country-covers #shows-youtube #youtube-upcoming .youtube h2 {
				margin: 0;
			}
			section#country-covers #shows-youtube {
				margin: 0;
				padding: 2em;
			}
			section#country-covers .slick-prev:before,
			section#country-covers .slick-next:before {
				font-size: 40px !important;
			}
			section#country-covers .slick-prev,
			section#country-covers .slick-next {
				width: 40px !important;
				height: 40px !important;
			}
			section#country-covers .slick-prev,
			section#country-covers .slick-next {
				width: 40px !important;
				height: 40px !important;
			}
			section#country-covers .slick-next {
				right: -40px !important;
			}
			section#country-covers .slick-prev {
				left: -40px !important;
			}
		}
		@media screen and (min-width: 52.5em) {
			body.single-shows #station-social {
				grid-template-columns: 2fr 3fr;
			}
			body.single-shows #station-social.station-no-social {
				grid-template-columns: 1fr !important;
			}
			body.shows-template-single-shows-skyline #float-wrap article.card {
				margin: 0 0.75% 1em;
				width: 48.5%;
				padding: 1em 1.5em;
			}
			body.shows-template-single-shows-skyline #float-wrap.column-span article.card {
				margin: 0 0.75% 1em;
				width: 31.5%;
				padding: 1em 1.5em;
			}
			section#country-covers #shows-youtube #youtube-wrap {
				background-color: transparent;
				overflow: visible;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
			while ( have_posts() ) {
				the_post();
				$show_id = get_the_ID();
				$show = get_post_meta( $show_id, 'hpm_show_meta', true );
				$show_title = get_the_title();
				$show_content = get_the_content();
				$episodes = HPM_Podcasts::list_episodes( $show_id );
				echo HPM_Podcasts::show_header( $show_id );
			} ?>
			<section id="country-covers">
				<div id="shows-youtube">
					<div id="youtube-wrap">
						<div class="column-right">
							<a href="http://claimittexas.org" target="_blank"><img src="https://cdn.houstonpublicmedia.org/assets/images/cc_logo_sponsor2x.png" alt="Skyline Sessions Country Covers" class="" /></a>
							<div class="show-content">
								<p><em>Country Covers</em> is a spin-off of our digital music series <em>Skyline Sessions</em> and features a variety of musicians performing their favorite country classics and sharing personal stories of their love for country music. <em>Country Covers</em> is Houston Public Media's companion piece to Ken Burns' new documentary series <em>Country Music</em>.</p>
							</div>
						</div>
<?php
			// PL1bastN9fY1iS4PbKjIgEE6dPebMeuJzB
			$json = hpm_youtube_playlist( 'PL1bastN9fY1iS4PbKjIgEE6dPebMeuJzB', 50 );
			$r = rand( 0, count( $json ) - 1 ); ?>
						<div id="youtube-main">
							<div id="youtube-player" style="background-image: url( '<?php echo $json[$r]['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $json[$r]['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $json[$r]['snippet']['title'], ENT_COMPAT ); ?>">
								<?php echo hpm_svg_output( 'play' ); ?>
							</div>
							<h2><?php echo $json[$r]['snippet']['title']; ?></h2>
							<p class="desc"><?php echo $json[$r]['snippet']['description']; ?></p>
						</div>
						<div id="youtube-upcoming">
<?php
			foreach ( $json as $tubes ) { ?>
							<div>
								<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
									<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
									<h2><?php echo $tubes['snippet']['title']; ?></h2>
								</div>
							</div>
						<?php
			} ?>
						</div>
					</div>
				</div>
			</section>
			<div class="party-politics-page">
				<div class="row about-party">
					<div class="col-sm-9">
						<h2 class="title no-bar"> <strong><span>ABOUT <?php echo $show_title; ?></span></strong> </h2>
						<div class="show-content">
							<?php echo apply_filters( 'the_content', $show_content ); ?>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="sidebar-ad">
							<h4>Support Comes From</h4>
							<div id="div-gpt-ad-1470409396951-0">
								<script type='text/javascript'>
									googletag.cmd.push(function() { googletag.display('div-gpt-ad-1470409396951-0'); });
								</script>
							</div>
						</div>
					</div>
				</div>
				<div class="episodes-block">
					<h2 class="title red-bar"> <strong><span>All Stories</span></strong> </h2>
					<div class="row">
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

			if ( $studio->have_posts() ) {
				while ( $studio->have_posts() ) {
					$studio->the_post();
					get_template_part( 'content', 'shows' );
				}
			}
			wp_reset_query(); ?>
					</div>
				</div>
				<?php echo hpm_custom_pagination( $studio->max_num_pages, 4, "/topics/in-studio/page/"); ?>
				<div class="episodes-block">
					<div class="row">
<?php
			if ( $others->have_posts() ) {
				while ( $others->have_posts() ) {
					$others->the_post();
					get_template_part( 'content', 'shows' );
				}
			}
			wp_reset_query(); ?>
					</div>
				</div>
				<?php echo hpm_custom_pagination( $studio->max_num_pages, 4, "/topics/skyline-sessions/page/" ); ?>
			</div>
		</main>
	</div>
	<script src="https://cdn.houstonpublicmedia.org/assets/js/slick/slick.min.js"></script>
	<script>
		jQuery(document).ready(function($){
			let options = { slidesToShow: 3, rows: 1, slidesToScroll: 3, infinite: false, autoplay: false, lazyLoad: 'ondemand', responsive: [ { breakpoint: 1024, settings: { slidesToShow: 3, slidesToScroll: 3 } }, { breakpoint: 800, settings: { slidesToShow: 3, slidesToScroll: 3, rows: 1 } }, { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1, rows: 3 } }] };
			$('#youtube-upcoming').slick(options);
		});
	</script>
<?php get_footer(); ?>