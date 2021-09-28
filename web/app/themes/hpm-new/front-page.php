<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
get_header();
$articles = hpm_homepage_articles(); ?>
	<style>
		#station-schedules {
			background-color: white;
		}
		#station-schedules h4 {
			border-bottom: 0.125em solid var(--main-red);
			padding: 0.25em 1em;
			margin: 0;
			font: 400 1.75em/1.75em var(--hpm-font-condensed);
		}
		#station-schedules .station-now-play {
			padding: 0.5em 1em;
			border-bottom: 0.125em solid #f5f5f5;
			min-height: 4.5em;
			display: flex;
			flex-flow: row wrap;
			align-items: center;
		}
		#station-schedules .station-now-play:last-child {
			border: 0;
		}
		#station-schedules .station-now-play > * {
			width: 100%;
		}

		#station-schedules .station-now-play h5 {
			padding: 0;
			margin: 0 1em 0 0;
		}
		#station-schedules .station-now-play h5 a {
			font: 700 1em/1em var(--hpm-font-main);
			text-transform: uppercase;
		}
		#station-schedules .station-now-play h3 {
			font: 100 1.25em/1.5em var(--hpm-font-main);
			padding: 0;
			margin: 0;
			color: #55565a;
		}
	</style>
	<div id="primary" class="content-area">
	<?php election_homepage(); ?>
		<main id="main" class="site-main" role="main">
			<section>
<?php
	$artnum = count( $articles );
	if ( $artnum % 2 !== 0 ) :
		unset( $articles[ $artnum - 1 ] );
	endif;
	foreach ( $articles as $ka => $va ) :
		if ( $ka == 4 ) : ?>
			</section>
			<aside id="top-schedule-wrap">
				<section id="station-schedules">
					<h4>ON AIR</h4>
					<div class="station-now-play">
						<h5><a href="/tv8">TV 8</a></h5>
						<div class="hpm-nowplay" data-station="tv81" data-upnext="false"><?php echo hpmv2_nowplaying( 'tv8.1' ); ?></div>
					</div>
					<div class="station-now-play">
						<h5><a href="/tv8">TV 8.2 (Create)</a></h5>
						<div class="hpm-nowplay" data-station="tv82" data-upnext="false"><?php echo hpmv2_nowplaying( 'tv8.2' ); ?></div>
					</div>
					<div class="station-now-play">
						<h5><a href="/tv8">TV 8.3 (PBS Kids)</a></h5>
						<div class="hpm-nowplay" data-station="tv83" data-upnext="false"><?php echo hpmv2_nowplaying( 'tv8.3' ); ?></div>
					</div>
					<div class="station-now-play">
						<h5><a href="/tv8">TV 8.4 (World)</a></h5>
						<div class="hpm-nowplay" data-station="tv84" data-upnext="false"><?php echo hpmv2_nowplaying( 'tv8.4' ); ?></div>
					</div>
					<div class="station-now-play">
						<h5><a href="/news887">News 88.7</a></h5>
						<div class="hpm-nowplay" data-station="news" data-upnext="false"><?php echo hpmv2_nowplaying( 'news887' ); ?></div>
					</div>
					<div class="station-now-play">
						<h5><a href="/classical">Classical</a></h5>
						<div class="hpm-nowplay" data-station="classical" data-upnext="false"><?php echo hpmv2_nowplaying( 'classical' ); ?></div>
					</div>
					<div class="station-now-play">
						<h5><a href="/mixtape">Mixtape</a></h5>
						<div class="hpm-nowplay" data-station="mixtape" data-upnext="false"><?php echo hpmv2_nowplaying( 'mixtape' ); ?></div>
					</div>
				</section>
				<?php hpm_top_posts(); ?>
				<section class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });</script>
					</div>
				</section>
				<section class="highlights">
					<h4>News from NPR</h4>
					<?php echo hpm_nprapi_output(); ?>
				</section>
				<section class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-2">
						<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });</script>
					</div>
				</section>
			</aside>
			<section>
<?php
		endif;
		$post = $va;
		get_template_part( 'content', get_post_format() );
	endforeach; ?>
			</section>
		</main>
	</div>
<?php get_footer(); ?>