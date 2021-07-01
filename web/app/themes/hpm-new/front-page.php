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
			min-height: 3em;
		}
		#station-schedules .station-now-play h5 {
			display: block;
			float: left;
			padding: 0.5em 0.5em 0 0;
			margin: 0;
		}
		#station-schedules .station-now-play h5 a {
			font: 700 1em/1em var(--hpm-font-main);
			text-transform: uppercase;
		}
		#station-schedules .station-now-play h3 {
			font: 100 1.25em/1.5em var(--hpm-font-main);
			padding: 0.25em 0;
			margin: 0;
			color: #55565a;
		}
		#station-schedules .station-now-play p {
			color: #808284;
			font-size: 0.8125em;
		}
		#in-depth {
			border: 0.125em solid var(--main-red);
			background-color: white;
			padding: 0;
			width: 100%;
		}
		#in-depth article {
			margin: 0;
			padding: 0;
			width: 100%;
		}
		#in-depth article h2 {
			margin-bottom: 0.5em;
		}
		#in-depth article .entry-header {
			padding: 1em 1em 0.5em 1em;
		}
		#in-depth article .entry-summary {
			padding: 0 1em 1em 1em;
		}
		#in-depth article .entry-summary p {
			margin: 0;
			font: 500 1.25em/1.25em var(--hpm-font-main);
			color: #646464;
		}
		#in-depth h4 {
			background-color: var(--main-red);
			display: inline-block;
			margin: 0 0 0.5em 0;
			padding: 0.3125em;
			text-transform: uppercase;
			color: white;
			font-family: var(--hpm-font-main);
			font-weight: 700;
		}
		#float-wrap #in-depth article .entry-header h2 a {
			font: 400 1.5em/1.25em var(--hpm-font-condensed);
		}
	</style>
	<div id="primary" class="content-area">
	<?php election_homepage(); ?>
		<main id="main" class="site-main" role="main">
			<div class="article-cards column-left">
<?php
	$artnum = count( $articles );
	if ( $artnum % 2 !== 0 ) :
		unset( $articles[ $artnum - 1 ] );
	endif;
	foreach ( $articles as $ka => $va ) :
		if ( $ka == 4 ) : ?>
				</div>
				<aside id="top-schedule-wrap" class="column-right">
					<section id="station-schedules">
						<h4>ON AIR</h4>
						<div class="station-now-play-wrap">
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8</a></h5>
								<div class="hpm-nowplay" data-station="tv81" data-upnext="false"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.2 (Create)</a></h5>
								<div class="hpm-nowplay" data-station="tv82" data-upnext="false"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.3 (PBS Kids)</a></h5>
								<div class="hpm-nowplay" data-station="tv83" data-upnext="false"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.4 (World)</a></h5>
								<div class="hpm-nowplay" data-station="tv84" data-upnext="false"></div>
							</div>
						</div>
						<div class="station-now-play-wrap">
							<div class="station-now-play">
								<h5><a href="/news887">News 88.7</a></h5>
								<div class="hpm-nowplay" data-station="news" data-upnext="false"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/classical">Classical</a></h5>
								<div class="hpm-nowplay" data-station="classical" data-upnext="false"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/mixtape">Mixtape</a></h5>
								<div class="hpm-nowplay" data-station="mixtape" data-upnext="false"></div>
							</div>
						</div>
					</section>
					<section id="in-depth">
						<h4>News 88.7 In-Depth</h4>
						<?php hpm_priority_indepth(); ?>
					</section>
					<?php hpm_top_posts(); ?>
					<section class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-1">
							<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });</script>
						</div>
					</section>
				</aside>
				<div class="article-cards column-left">
<?php
		elseif ( $ka == 12 ) : ?>
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
				<div class="article-cards column-left">
<?php
		endif;
		$post = $va;
		get_template_part( 'content', get_post_format() );
	endforeach; ?>
				</div>
			</div>
		</main>
	</div>
<?php get_footer(); ?>