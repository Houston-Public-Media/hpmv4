<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
get_header();
$articles = hpm_homepage_articles(); ?>
	<style>
		#station-schedules {
			background-color: var(--main-element-background);
		}
		#station-schedules h4 {
			border-bottom: 0.125em solid var(--main-red);
			padding: 0.25em 1em;
			margin: 0;
			font: 400 2rem var(--hpm-font-condensed);
		}
		#station-schedules .station-now-play {
			padding: 0.5em 1em;
			border-bottom: 0.125em solid var(--main-background);
			min-height: 5em;
			display: grid;
			grid-template-columns: 30% 70%;
			align-items: center;
			gap: 1rem;
		}
		#station-schedules .station-now-play:last-child {
			border: 0;
		}
		#station-schedules .station-now-play > * {
			width: 100%;
		}
		#station-schedules .station-now-play h5 {
			padding: 0;
			margin: 0;
			font-size: 1rem;
			text-align: right;
		}
		#station-schedules .station-now-play h5 a {
			font-weight: 700;
			text-transform: uppercase;
		}
		#station-schedules .station-now-play h3 {
			font-weight: 100;
			font-size: 1.25rem;
			font-family: var(--hpm-font-condensed);
			padding: 0 0.5rem 0 0;
			margin: 0;
			color: var(--main-headline);
		}
		@media screen and (min-width: 34rem) {
			#station-schedules {
				display: grid;
				grid-template-columns: 50% 50%;
				width: 100%;
			}
			#station-schedules h4 {
				grid-column: 1/-1;
			}
			#station-schedules .station-now-play:nth-child(even) {
				border-right: 1px solid #808080;
			}
		}
		@media screen and (min-width: 52.5rem) {
			#station-schedules {
				display: block;
				width: 100%;
			}
			#station-schedules .station-now-play:nth-child(even) {
				border-right: 0;
			}
		}
	</style>
	<div id="primary" class="content-area">
	<?php election_homepage(); ?>
		<main id="main" class="site-main" role="main">
			<div id="float-wrap">
				<div class="article-wrap">
<?php
	$artnum = count( $articles );
	if ( $artnum % 2 !== 0 ) {
		unset( $articles[ $artnum - 1 ] );
	}
	foreach ( $articles as $ka => $va ) {
		if ( $ka == 4 ) { ?>
				</div>
				<aside id="top-schedule-wrap" class="column-right">
					<?PHP echo HPM_Promos::generate_static( 'sidebar' ); ?>
					<div id="station-schedules">
						<h4>ON AIR</h4>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8</a></h5>
							<div class="hpm-nowplay" data-station="tv81" data-upnext="false"><?php echo hpm_now_playing( 'tv8.1' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.2 (Create)</a></h5>
							<div class="hpm-nowplay" data-station="tv82" data-upnext="false"><?php echo hpm_now_playing( 'tv8.2' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.3 (PBS Kids)</a></h5>
							<div class="hpm-nowplay" data-station="tv83" data-upnext="false"><?php echo hpm_now_playing( 'tv8.3' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.4 (NHK)</a></h5>
							<h3>NHK World Japan</h3>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.5 (World)</a></h5>
							<div class="hpm-nowplay" data-station="tv84" data-upnext="false"><?php echo hpm_now_playing( 'tv8.4' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/news887">News 88.7</a></h5>
							<div class="hpm-nowplay" data-station="news" data-upnext="false"><?php echo hpm_now_playing( 'news887' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/classical">Classical</a></h5>
							<div class="hpm-nowplay" data-station="classical" data-upnext="false"><?php echo hpm_now_playing( 'classical' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/mixtape">Mixtape</a></h5>
							<div class="hpm-nowplay" data-station="mixtape" data-upnext="false"><?php echo hpm_now_playing( 'mixtape' ); ?></div>
						</div>
					</div>
					<?php hpm_top_posts(); ?>
					<section class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-1">
							<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });</script>
						</div>
					</section>
				</aside>
				<div class="article-wrap">
<?php
		} elseif ( $ka == 12 ) { ?>
				</div>
				<aside id="npr-side" class="column-right">
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
				<div class="article-wrap">
<?php
		}
		$post = $va;
		get_template_part( 'content', get_post_format() );
	} ?>
				</div>
			</div>
		</main>
	</div>
<?php get_footer(); ?>