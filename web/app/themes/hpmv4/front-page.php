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
			min-height: 40px;
			text-align: center;
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
			#hm-top.livestream-show {
				display: none;
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
        .VerticalVideoCarousel__header{ display: none !important; }
	</style>
	<div id="primary" class="content-area home-page">
<?php
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	$now = getdate( $t );
	$hm_air = HPM_Liveshows::liveshow_check();
	$temp = HPM_Liveshows::get_all();
	$talkshow = '';
	$priority_start = 3;
	$priority_end = 8;
	$ytlive = get_option( 'hpm_ytlive_talkshows' );
	if ( !empty( $hm_air[ $now['hours'] ] ) ) {
		foreach ( $temp as $k => $v ) {
			if (
				(
					( $v['recurring'] == 1 && in_array( $now['wday'], $v['recurring_pattern'] ) ) ||
					( $v['recurring'] == 0 && $v['once_date'] == date( 'Y-m-d', $now[0] ) )
				) &&
				$v['start_hour'] <= $now['hours'] &&
				$v['end_hour'] > $now['hours'] &&
				!empty( $ytlive[ $k ]['id'] )
			) {
				$talkshow = $k;
			}
		}
	}
	if ( WP_ENV !== 'production' ) {
		$streamtest = '';
		if ( !empty( $_GET['streamtest'] ) ) {
			$streamtest = esc_html( $_GET['streamtest'] );
		}
		if ( !empty( $streamtest ) ) {
			$talkshow = $streamtest;
		}
	}
	if ( !empty( $talkshow ) ) {
		$priority_start = 1;
		$priority_end = 6;
	} ?>
		<section class="section breaking-news container-fluid" style="padding-bottom: 0 !important;">
			<div class="row">
				<?php echo HPM_Liveshows::show_top_articles( $articles, $talkshow ); ?>
			</div>
		</section>
		<section class="section short-news" style="padding-top: 0 !important;">
			<ul class="list-none d-flex">
			<?php
				foreach ( $articles as $ka => $va ) {
					$post = $va;
					if ( $ka >= $priority_start && $ka < $priority_end ) { ?>
						<li>
							<h4 class="text-light-gray text-center" style="color:#237bbd;"><?php echo hpm_top_cat( $va->ID ); ?></h4>
							<h3><a href="<?php echo get_the_permalink( $va->ID );?>"><?php echo get_the_title( $va->ID ); ?></a></h3>
						</li>
						<?php
					}
					if ( $ka == $priority_end ) {
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
				// In-Depth
				$indepth = false;
				$extra = 'card card-medium';
				$size = 'thumbnail';
				$postClass = get_post_class();
				if ( in_array( 'category-in-depth', $postClass ) ) {
					$indepth = true;
				} ?>
				<div class="col-12 col-lg-9">
					<div class="news-slider">
						<div class="row">
							<div class="col-sm-6">
								<div class="news-slider-info">
									<?php echo ( $indepth ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.houstonpublicmedia.org/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
									<h4 class="text-light-gray"><?php echo hpm_top_cat( get_the_ID() ); ?></h4>
									<h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php
										$alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
										if ( !empty( $alt_headline ) ) {
											echo $alt_headline;
										} else {
											the_title();
										} ?></a></h2>
									<p><?php echo strip_tags( get_the_excerpt() ); ?></p>
								</div>
							</div>
							<div class="col-sm-6">
							<?php if ( has_post_thumbnail() ) { ?>
								<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $size ) ?></a>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<aside class="col-lg-3 indepth-sidebar">
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
		<section class="section ads-full text-center">
			<div class="page-banner">
				<a href="/space/" title="Artemis II Moon MIssion">
					<picture>
						<source srcset="https://cdn.houstonpublicmedia.org/assets/images/Artemis-II-moon-mission-Mobile.png.webp" type="image/webp" media="(max-width: 34em)">
						<source srcset="https://cdn.houstonpublicmedia.org/assets/images/Artemis-II-moon-mission-Tablet.png.webp" type="image/webp" media="(max-width: 52.5em)">
						<source srcset="https://cdn.houstonpublicmedia.org/assets/images/Artemis-II-moon-mission-Desktop.png.webp" type="image/webp">
						<img decoding="async" src="https://cdn.houstonpublicmedia.org/assets/images/Artemis-II-moon-mission-Desktop.png" alt="Artemis II Moon MIssion">
					</picture>
				</a>
			</div>
<?php /* ?>
 			<div class="page-banner">
				<a href="/elections-2026/" title="2026 Election">
					<picture>
					<source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-2026-PENCIL-AD-BANNER_MOBILE_1200x400.png.webp" type="image/webp" media="(max-width: 34em)">
					<source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-2026-PENCIL-AD-BANNER_TABLET_1600x200-1.png.webp" type="image/webp" media="(max-width: 52.5em)">
					<source srcset="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-2026-PENCIL-AD-BANNER_DESKTOP_1800x94-opt-1.png.webp" type="image/webp">
					<img decoding="async" src="https://cdn.houstonpublicmedia.org/assets/images/ELECTION-2026-PENCIL-AD-BANNER_DESKTOP_1800x94-opt-1.png" alt="2026 Election">
					</picture>
				</a>
			</div><?php */ ?>
		</section>
		<section class="section news-list">
			<div class="row">
				<div class="col-sm-12 col-lg-8 news-list-left">
					<div class="row">
					<?php
						// Local News
						$excludedIds = get_option( 'hpm_priority' )['homepage'];
						$catArrays = get_option( 'hpm_modules' );
						$cat_args = [
							'include' => $catArrays['homepage'],
							'orderby' => 'include'
						];
						$categories = get_categories( $cat_args );
						$rowCount = 0;
						$catCounter = 0;
						foreach ( $categories as $category ) {
							if ( $catCounter <= 1 ) {
								$args = [
									'showposts' => 5,
									'category__in' => [ $category->term_id ],
									'ignore_sticky_posts' => 1,
									'posts_per_page' => 4,
									'post__not_in' => $excludedIds,
									'category__not_in' => [ 0, 1, 7636, 28, 37840, 54338, 60 ]
								];
								$posts = get_posts( $args ); ?>
						<div class="col-sm-6">
							<h2 class="title">
								<strong><?php echo $category->name; ?></strong>
							</h2>
							<ul class="list-none news-links">
					<?php
								if ( $posts ) {
									foreach ( $posts as $post ) {
										setup_postdata( $post );  ?>
								<li><a href="<?php the_permalink(); ?>"><span class="cat-title"><?php echo hpm_top_cat( get_the_ID() ) ?></span> <?php the_title() ?></a></li>
					<?php
									}
								} ?>
							</ul>
						</div>
					<?php
								$rowCount++;
								if ( $rowCount % 2 == 0 ) echo '</div><div class="row">';
							}
							$catCounter++;
						} ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-4 news-list-right most-view">
					<h2 class="title title-full">
						<strong>Most <span>Viewed</span></strong>
					</h2>
					<div class="news-links list-dashed">
						<?php hpm_top_posts(); ?>
					</div>
					<div style="padding-top: 15px;">
						<h2 class="title title-full">
							<strong>Contact Us</strong>
						</h2>
						<p style="font-weight: bold; font-size: 16px;">Have a News Tip? <a href="/newstips">Tell Houston Public Media</a></p>
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
		<?php
			// Local Shows
			$HMArticles = hpm_showLatestArticlesbyShowID( 58 );
			$PPArticles = hpm_showLatestArticlesbyShowID( 11524 );
			$HHArticles = hpm_showLatestArticlesbyShowID( 64721 ); ?>
		<section class="section radio-list">
			<h2 class="title">
				<strong>THIS WEEK on <span>TALK RADIO</span></strong>
			</h2>
			<div class="row">
				<div class="col-sm-4">
					<h3 class="title-style2">
						<strong>HOUSTON <span>MATTERS</span></strong>
					</h3>
					<div class="image">
						<a href="/shows/houston-matters/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Houston-Matters-with-Craig-Cohen-Logo.png.webp" alt="Houston Matters with Craig Cohen" /></a>
					</div>
					<ul class="list-none news-links">
					<?php
						foreach ( $HMArticles as $ka => $va ) {
							$post = $va; ?>
						<li style="font-size: 0.9rem;">
							<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
						</li>
					<?php
						} ?>
					</ul>
				</div>
				<div class="col-sm-4">
					<h3 class="title-style4">
						<strong>HELLO <span>HOUSTON</span></strong>
					</h3>
					<div class="image">
						<a href="/shows/hello-houston/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Talk-Show-Web-MainPg-Show-Cover.png.webp" alt="Hello Houston: Where Houston Talks!" /></a>
					</div>
					<ul class="list-none news-links">
					<?php
						foreach ( $HHArticles as $ka => $va ) {
							$post = $va; ?>
						<li style="font-size: 0.9rem;">
							<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
						</li>
					<?php
						} ?>
					</ul>
				</div>
				<div class="col-sm-4">
					<h3 class="title-style3">
						<strong>PARTY <span>POLITICS</span></strong>
					</h3>
					<div class="image">
						<a href="/shows/party-politics/"><img src="https://cdn.houstonpublicmedia.org/assets/images/Party-Politics-Logo.png.webp" alt="Party Politics" /></a>
					</div>
					<ul class="list-none news-links">
					<?php
						foreach ( $PPArticles as $ka => $va ) {
							$post = $va; ?>
						<li style="font-size: 0.9rem;">
							<a href="<?php echo get_the_permalink( $post ); ?>"><?php echo get_the_title( $post ); ?></a>
						</li>
					<?php
						} ?>
					</ul>
				</div>
			</div>
		</section>
		<?php get_template_part("content", "verticalvideos"); ?>
		<section class="section news-list">
			<div class="row">
				<div class="col-sm-12 col-lg-8 news-list-left">
					<div class="row">
					<?php
						// Local News Bottom
						$rowCount = 0;
						$catCounter = 0;
						foreach ( $categories as $category ) {
							if ( $catCounter > 1 && $catCounter <= 3) {
								$args = [
									'showposts' => 5,
									'category__in' => [ $category->term_id ],
									'ignore_sticky_posts' => 1,
									'posts_per_page' => 4,
									'post__not_in' => $excludedIds,
									'category__not_in' => [ 0, 1, 7636, 28, 37840, 54338, 60 ]
								];
								$posts = get_posts( $args ); ?>
						<div class="col-sm-6">
							<h2 class="title">
								<strong><?php echo $category->name; ?></strong>
							</h2>
							<ul class="list-none news-links">
					<?php
								if ( $posts ) {
									foreach ( $posts as $post ) {
										setup_postdata( $post );  ?>
								<li><a href="<?php the_permalink(); ?>"><span class="cat-title"><?php echo hpm_top_cat( get_the_ID() ); ?></span> <?php the_title(); ?></a></li>
					<?php
									}
								} ?>
							</ul>
						</div>
					<?php
								$rowCount++;
								if ( $rowCount % 2 == 0 ) echo '</div><div class="row">';
							}
							$catCounter++;
						} ?>
					</div>
				</div>
				<div class="col-sm-12 col-lg-4 news-list-right news-schedule">
					<h2 class="title title-full">
						<strong>ON-AIR <span>SCHEDULE</span></strong>
					</h2>
					<div id="station-schedules">
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8</a></h5>
							<div class="hpm-nowplay" data-station="tv81"><?php echo hpm_now_playing( 'tv8.1' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.2 (Create)</a></h5>
							<div class="hpm-nowplay" data-station="tv82"><?php echo hpm_now_playing( 'tv8.2' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.3 (PBS Kids)</a></h5>
							<div class="hpm-nowplay" data-station="tv83"><?php echo hpm_now_playing( 'tv8.3' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.4 (NHK World)</a></h5>
							<div class="hpm-nowplay" data-station="tv84"><?php echo hpm_now_playing( 'tv8.4' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/tv8">TV 8.6 (ALL ARTS)</a></h5>
							<div class="hpm-nowplay" data-station="tv86"><?php echo hpm_now_playing( 'tv8.6' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/news887">News 88.7</a></h5>
							<div class="hpm-nowplay" data-station="news"><?php echo hpm_now_playing( 'news887' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/classical">Classical</a></h5>
							<div class="hpm-nowplay" data-station="classical"><?php echo hpm_now_playing( 'classical' ); ?></div>
						</div>
						<div class="station-now-play">
							<h5><a href="/thevibe/">The Vibe</a></h5>
							<div class="hpm-nowplay" data-station="thevibe"><?php echo hpm_now_playing( 'thevibe' ); ?></div>
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
					<?php echo hpm_nprapi_output( 1002 ); ?>
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
					<?php
						// Local News Footer
						$rowCount = 0;
						$catCounter = 0;
						foreach ( $categories as $category ) {
							if ( $catCounter > 3 ) {
								$args = [
									'showposts' => 4,
									'category__in' => [ $category->term_id ],
									'ignore_sticky_posts' => 1,
									'posts_per_page' => 4,
									'post__not_in' => $excludedIds,
									'category__not_in' => [ 0, 1, 7636, 28, 37840, 54338, 60 ]
								];
								$posts = get_posts( $args ); ?>
						<div class="col-sm-12">
							<h2 class="title">
								<strong><?php echo $category->name; ?></strong>
							</h2>
							<ul class="list-none news-footerlinks">
					<?php
								if ( $posts ) {
									foreach ( $posts as $post ) {
										setup_postdata( $post );  ?>
								<li><a href="<?php the_permalink(); ?>" rel="bookmark"><span class="cat-title"><?php echo hpm_top_cat( get_the_ID() ); ?></span> <?php the_title(); ?></a></li>
					<?php
									}
								} ?>
							</ul>
						</div>
					<?php
								$rowCount++;
							}
							$catCounter++;
						} ?>
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
		<section class="section">
			<?php // Interactives ?>
			<h2 class="title">
				<strong>HOUSTON PUBLIC MEDIA'S <span>INTERACTIVES</span></strong>
			</h2>
			<div class="row">
				<div class="col-sm-4">
					<div class="card mb-3">
						<div class="card-header" style="min-height: 42px !important;">
							<a style="text-decoration: none; color:#fff;" href="/hurricane-tropical-storm-tracker-texas-houston">Hurricane &amp; Tropical Storm Tracker</a>
						</div>
						<div class="row g-0">
							<div class="col-md-12">
								<a href="/hurricane-tropical-storm-tracker-texas-houston"><img src="https://cdn.houstonpublicmedia.org/assets/images/Hurricane-and-storm-tracker-hpm-interactives.png.webp" alt="Hurricane and Tropical Storm Tracker" style="padding: 6px;" /></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="card mb-3">
						<div class="card-header" style="min-height: 42px !important;">
							<a style="text-decoration: none; color:#fff;" href="/texas-houston-power-outage-tracker-map/">Texas Power Outage Tracker Map</a>
						</div>
						<div class="row g-0">
							<div class="col-md-12">
								<a href="/texas-houston-power-outage-tracker-map/"><img src="https://cdn.houstonpublicmedia.org/assets/images/texas-power-outage-tracker-map.png.webp" alt="Texas Power Outage Tracker Map" style="padding: 6px;" /></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="card mb-3">
						<div class="card-header" style="min-height: 42px !important;">
							<a style="text-decoration: none; color:#fff;" href="/houston-weather-temperatures-heat-map/">Heat Tracker Map</a>
						</div>
						<div class="row g-0">
							<div class="col-md-12">
								<a href="/houston-weather-temperatures-heat-map/">
									<img src="https://cdn.houstonpublicmedia.org/assets/images/Heat-Tracker-Interactive-Map.png.webp" alt="Heat Tracker Map - Houston Public Media" style="padding: 6px;" />
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<?php get_footer(); ?>