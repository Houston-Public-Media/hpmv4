<?php
/**
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
get_header();
$articles = hpm_homepage_articles();
$indepthArtcle[] = null;
$tras = null; ?>
	<style>
		#station-schedules {
			background-color: var(--main-element-background);
		}
		#station-schedules h4 {
			border-bottom: 0.125em solid var(--main-blue);
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

			font-size: 1.25rem;
			font-family: var(--hpm-font-condensed);
			padding: 0 0.5rem 0 0;
			margin: 0;
			color: var(--main-headline);
		}
		.text-light-gray a{
			color:#237bbd;
			font-weight: bold;
		}
		.news-listing h4 a
		{
			color:#237bbd;
			font-size: 15px;
		}
		.news-listing p {
			font-size: 0.9em;
		}
		.news-main p {
			font-size: 0.9em;
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
	<div id="primary" class="content-area home-page">
		<section class="section breaking-news container-fluid" style="padding-bottom: 0px !important;">
			<div class="row">
				<?php echo hpm_showTopthreeArticles( $articles ); ?>
			</div>
		</section>

		<section class="section short-news" style="padding-top: 0px !important;">
			<ul class="list-none d-flex">
			<?php
				foreach ( $articles as $ka => $va ) {
					$post = $va;
					if ( $ka >= 3 && $ka < 8 ) {
						get_template_part("content", "topnewsrail");
					}
					if ( $ka == 8 ) {
						$indepthArtcle = $post;
					}
				} ?>
			</ul>
		</section>
		<!-- /.short-news -->
		<section class="section ads-full">
			<?php
			if ( !is_page_template( 'page-listen.php' ) && !is_page_template( 'page-blank.php' ) ) { ?>
				<!-- /9147267/HPM_Under_Nav -->
				<div id='div-gpt-ad-1488818411584-0'>
					<script>
						googletag.cmd.push(function() { googletag.display('div-gpt-ad-1488818411584-0'); });
					</script>
				</div>
<?php
			} ?>
		</section>
		<section class="section">
			<div class="row">
				<?php get_template_part("content", "indepth"); ?>
				<aside class="col-lg-3">
					<?PHP echo HPM_Promos::generate_static( 'sidebar' ); ?>
				</aside>
			</div>
		</section>
		<section class="section news-list">
			<div class="row">
				<div class="col-sm-12 col-lg-9 news-list-left">
					<div class="row">
						<?php get_template_part( "content", "localnews" ); ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-3 news-list-right most-view">
					<h2 class="title title-full">
						<strong>Most <span>Viewed</span></strong>
					</h2>
					<div class="news-links list-dashed">
						<?php hpm_top_posts(); ?>
					</div>
				</div>
			</div>
		</section>
		<?php get_template_part("content", "localshows") ?>
		<section class="section news-list">
			<div class="row">
				<div class="col-sm-12 col-lg-9 news-list-left">
					<div class="row">
						<?php get_template_part("content", "localnewsbottom") ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-3 news-list-right news-schedule">
					<h2 class="title title-full">
						<strong>ON-AIR <span>SCHEDULE</span></strong>
					</h2>
					<div id="station-schedules">
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
				</div>
		</section>
		<section class="section news-list news-list-full">
			<div class="row">
				<div class="col-sm-12 col-lg-9 news-list-left">
					<h2 class="title">
						<strong>News from <span>NPR</span></strong>
					</h2>
					<?php echo hpm_nprapi_output(); ?>
				</div>
				<div class="col-sm-12 col-lg-3 news-list-right most-view">
					<h2 class="title title-full">
						<strong>Support Comes <span>From</span></strong>
					</h2>
					<div id="div-gpt-ad-1394579228932-1">
						<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });</script>
					</div>
				</div>
			</div>
		</section> 
	</div>
<?php get_footer(); ?>