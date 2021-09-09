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
	<style>
		#main > section#country-covers {
			max-width: 100%;
			background-color: rgb(181,159,109);
			background-image: url(https://cdn.hpm.io/assets/images/tan_mobile.png);
			background-position: center center;
			background-repeat: no-repeat;
			background-size: cover;
			grid-column: 1 / -1 !important;
			display: block !important;
		}
		#main > aside {
			grid-row: auto;
		}
		section#country-covers #shows-youtube {
			margin: 0;
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
		section#country-covers #shows-youtube #youtube-upcoming .youtube h2 {
			color: #1F2F42;
		}
		section#country-covers #shows-youtube #youtube-upcoming .youtube h2 {
			font-family: var(--hpm-font-condensed);
		}
		#shows-youtube #youtube-upcoming {
			display: grid;
			grid-auto-flow: column;
			grid-gap: 1rem;
			overflow-x: auto;
			scroll-snap-type: x mandatory;
			padding: 0 0 1.5rem;
			-webkit-overflow-scrolling: touch;
			background-color: transparent;
			margin: 0;
			width: 100%;
		}

		#shows-youtube #youtube-upcoming > .youtube {
			width: min(55ch, 60vw);
			scroll-snap-align: center;
			scroll-snap-stop: always;
			border: 0 !important;
		}
		@media screen and (min-width: 34em) {
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
		}
		@media screen and (min-width: 52.5em) {
			section#country-covers #shows-youtube #youtube-wrap {
				background-color: transparent;
				overflow: visible;
			}
		}
	</style>
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
			<section id="country-covers">
				<div id="shows-youtube">
					<div id="youtube-wrap">
						<div class="column-right">
							<a href="http://claimittexas.org" target="_blank"><img src="https://cdn.hpm.io/assets/images/cc_logo_sponsor2x.png" alt="Skyline Sessions Country Covers" /></a>
							<div class="show-content">
								<p><em>Country Covers</em> is a spin-off of our digital music series <em>Skyline Sessions</em> and features a variety of musicians performing their favorite country classics and sharing personal stories of their love for country music.</p>
							</div>
						</div>
<?php			$json = hpm_youtube_playlist( 'PL1bastN9fY1iS4PbKjIgEE6dPebMeuJzB', 50 );
				$r = rand( 0, count( $json ) - 1 ); ?>
						<div id="youtube-main">
							<div id="youtube-player" style="background-image: url( '<?php echo $json[$r]['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $json[$r]['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $json[$r]['snippet']['title'], ENT_COMPAT ); ?>">
								<span class="fab fa-youtube" id="play-button"></span>
							</div>
							<h2><?php echo $json[$r]['snippet']['title']; ?></h2>
							<p class="desc"><?php echo $json[$r]['snippet']['description']; ?></p>
						</div>
						<div id="youtube-upcoming">
						<?php foreach ( $json as $tubes ) : ?>
							<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
								<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" />
								<h2><?php echo $tubes['snippet']['title']; ?></h2>
							</div>
						<?php endforeach; ?>
						</div>
					</div>
				</div>
			</section>
			<aside>
				<section>
					<h3>About <?php echo $show_title; ?></h3>
					<?php echo apply_filters( 'the_content', $show_content ); ?>
				</section>
				<section class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1470409396951-0">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1470409396951-0'); });
						</script>
					</div>
				</section>
			</aside>
			<section>
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
					get_template_part( 'content', get_post_format() );
				endwhile;
			endif;
			wp_reset_query(); ?>
			</section>
			<div class="readmore">
				<a href="/topics/in-studio/page/2">View More Performances</a>
			</div>
			<section>
<?php
			if ( $others->have_posts() ) :
				while ( $others->have_posts() ) : $others->the_post();
					get_template_part( 'content', get_post_format() );
				endwhile;
			endif;
			wp_reset_query(); ?>
			</section>
			<div class="readmore">
				<a href="/topics/skyline-sessions/page/2">View More Related Articles</a>
			</div>
		</main>
	</div>
<?php get_footer(); ?>