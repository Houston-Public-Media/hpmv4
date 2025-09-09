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
			padding: 0.5em;
			border-bottom: 0.125em solid var(--main-background);
			min-height: 3.5rem;
			display: grid;
			grid-template-columns: 3fr 7fr;
			align-items: center;
			gap: 1rem;
			border-bottom: dotted 2px #000 !important;
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
			font-size: 0.825rem;
			font-family: var(--hpm-font-condensed);
			padding: 0 0.5rem 0 0;
			margin: 0;
			color: var(--main-headline);
			text-decoration: none;
		}
		.text-light-gray a {
			color:#237bbd;
			font-weight: bold;
			text-decoration: none;
		}
		.news-listing h4 a {
			color:#237bbd;
			font-size: 15px;
			text-decoration: none;
		}
		.news-listing p {
			font-size: 0.9em;
		}
		.news-main {
			padding-bottom: 1rem;
		}
		.news-main p {
			font-size: 0.9em;
		}
		.flex-row {
			display: flex;
			justify-content: center;
		}
        .card {
            border-radius: 0;
            border-color: #237bbd;
        }
        .card-header {
            background-color: #237bbd;
            color:#fff;
            font-weight: bold;
            border-radius: 0;
            min-height: 56px;
        }
        .card-header:first-child {
            border-radius: 0;
        }
        .card-title {
            font-size: 13px;
            font-weight: bold;
            color: #237bbd;
        }
        .card-body {
            padding-top: 5px;
        }
		.page-banner {
			display: grid;
			justify-content: center;
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
<?php
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$hm_air = hpm_houston_matters_check();
	$ytlive = get_option( 'hpm_ytlive_talkshows' );
	$talkshow = '';
	if ( ( $now['wday'] > 0 && $now['wday'] < 6 ) && !empty( $hm_air[ $now['hours'] ] ) && $hm_air[ $now['hours'] ] ) {
		if ( $now['hours'] == 9 ) {
			$talkshow = 'houston-matters';
		} elseif ( $now['hours'] == 11 || $now['hours'] == 12 ) {
			$talkshow = 'hello-houston';
		}
	}

	if ( !empty( $talkshow ) ) { ?>
				<div class="col-12 col-lg-9">
					<div class="news-slider <?php echo $talkshow;?>">
						<div class="news-slide-item">
							<p class="iframe-embed"><iframe id="<?php echo $ytlive[ $talkshow ]['id']; ?>" width="560" height="315" src="https://www.youtube.com/embed/<?php echo $ytlive[ $talkshow ]['id']; ?>?enablejsapi=1" title="<?php echo $ytlive[ $talkshow ]['title']; ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></p>
						</div>
						<div class="news-slide-item">
							<h4 class="text-light-gray"><?php echo ucwords( str_replace( '-', ' ', $talkshow ) ); ?></h4>
							<h2><a href="https://www.youtube.com/watch?v=<?php echo $ytlive[ $talkshow ]['id']; ?>" rel="bookmark"><?php echo $ytlive[ $talkshow ]['title']; ?></a></h2>
							<p><?php echo strip_tags( explode( '</p>', $ytlive[ $talkshow ]['description'] )[0] ); ?></p>
						</div>
					</div>
				</div><?php
	} else {
		get_template_part("content", "indepth");
	}
?>
				<aside class="col-lg-3">
					<?PHP echo HPM_Promos::generate_static( 'sidebar' ); ?>
				</aside>
				<div class="news-list-right most-view homepage-mobile-gdc pb-4 pt-4 hidden">
					<h2 class="title title-full">
						<strong>Support Comes <span>From</span></strong>
					</h2>
					<div class="sidebar-ad">
						<div id="div-gpt-ad-1394579228932-3">
							<script>if (window.innerWidth < 1000) { googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-3'); });}</script>
						</div>
					</div>
				</div>
			</div>
		</section>
        <?php /* ?><section class="section ads-full text-center">
            <div class="page-banner">
                <a href="/2024election/" title="2024 Election">
                    <picture>
                    <source srcset="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-mobile.png.webp" type="image/webp" media="(max-width: 34em)">
                    <source srcset="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-tablet.png.webp" type="image/webp" media="(max-width: 52.5em)">
                    <source srcset="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-Desktop.png.webp" type="image/webp">
                    <img decoding="async" src="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-Desktop.png" alt="2024 Election">
                    </picture>
                </a>
            </div>
        </section><?php */ ?>
		<section class="section news-list">
			<div class="row">
				<div class="col-sm-12 col-lg-8 news-list-left">
					<div class="row">
						<?php get_template_part( "content", "localnews" ); ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-4 news-list-right most-view">
					<h2 class="title title-full">
						<strong>Most <span>Viewed</span></strong>
					</h2>
					<div class="news-links list-dashed">
						<?php hpm_top_posts(); ?>
					</div>
				</div>
				<div class="news-list-right most-view homepage-mobile-gdc pb-4 pt-4 hidden">
					<h2 class="title title-full">
						<strong>Support Comes <span>From</span></strong>
					</h2>
					<div class="sidebar-ad">
						<div id="div-gpt-ad-1394579228932-4">
							<script>if (window.innerWidth < 1000) { googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-4'); });}</script>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php get_template_part("content", "localshows") ?>
		<section class="section news-list">
			<div class="row">
				<div class="col-sm-12 col-lg-8 news-list-left">
					<div class="row">
						<?php get_template_part("content", "localnewsbottom") ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-4 news-list-right news-schedule">
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
							<h5><a href="/tv8">TV 8.6 (World)</a></h5>
							<div class="hpm-nowplay" data-station="tv86" data-upnext="false"><?php echo hpm_now_playing( 'tv8.6' ); ?></div>
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
							<h5><a href="/thevibe/">The Vibe</a></h5>
							<div class="hpm-nowplay" data-station="thevibe" data-upnext="false"><?php echo hpm_now_playing( 'thevibe' ); ?></div>
						</div>
					</div>
				</div>
		</section>
		<section class="section news-list news-list-full">
			<div class="row">
				<div class="col-sm-12 col-lg-8 news-list-left">
					<h2 class="title">
						<strong>News from <span>NPR</span></strong>
					</h2>
					<?php echo hpm_nprapi_output( 1002, 4 ); ?>
                    <div style="text-align: right;"><a href="/npr-news" style="font-weight: bold; color:#237bbd; font-size: 13px; text-decoration: none;">View all NPR stories</a></div>
				</div>
				<div class="col-sm-12 col-lg-4 news-list-right most-view homepage-desktop-gdc hidden">
					<h2 class="title title-full">
						<strong>Support Comes <span>From</span></strong>
					</h2>
					<div class="sidebar-ad">
						<div id="div-gpt-ad-1394579228932-1">
							<script>if (window.innerWidth >= 1000) { googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });}</script>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-lg-8 news-list-left">
					<div class="row">
						<?php get_template_part( "content", "localnewsfooter" ); ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-4 news-list-right most-view homepage-desktop-gdc hidden">
					<div class="sidebar-ad">
						<div id="div-gpt-ad-1394579228932-2">
							<script>if (window.innerWidth >= 1000) { googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); }); }</script>
						</div>
					</div>
				</div>
			</div>
		</section>
        <?php get_template_part("content", "interactives"); ?>
	</div>
<?php get_footer(); ?>