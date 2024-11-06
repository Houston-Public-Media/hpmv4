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
			border-radius: 0px;
			border-color: #237bbd;
		}
		.card-header {
			background-color: #237bbd;
			color:#fff;
			font-weight: bold;
			border-radius: 0px;
			min-height: 56px;
		}
		.card-header:first-child {
			border-radius: 0px;
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
/* Election specific style starts here*/
		.row-height {
			height: 44.5px;
			text-align: center;
			padding-top: 5px;
			background-color: #237bbd;
			color: #ffffff !important;
		}
		.row-height h2
		{
			color: #ffffff;
			font-size: 24px;
		}
		.cat-title {
			text-transform: uppercase;
			font-weight: bold;
			color: #237bbd;
			font-size: 14px;
			line-height: 30px;
		}
		.breaking-news a :is(picture,img)
		{
			aspect-ratio: 3 / 2;
			object-fit: cover;
		}
		.rowpadding :is(picture,img)
		{
			aspect-ratio: 3 / 2;
			object-fit: cover;
		}
		.electionlinktopnews {
			background-color:white;
			width:95%;
			height:95%;
			margin: 0 auto;
		}
		.electionnews-img > img {
			height:50px;
			width:50px;
			float:left;
			padding:5px;
		}
		.electionlinktopnews ul{
			list-style: none;
		}
		:is(.electionlinktopnews) li a {
			color: var(--base);
			text-decoration: none;
			font-weight: bold;
		}
		/* Election specific style ends here*/

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
		section#breaking-news {
			padding: 1rem;
			display: grid;
			gap: 1rem;
			.bn-hero {
				display: grid;
				align-items: center;
				picture, img {
					aspect-ratio: 3 / 2;
					object-fit: cover;
				}
			}
			h1.mainnews-title {
				font-size: 2rem;
				padding-bottom: 0.5rem;
				a {
					text-decoration: none;
				}
			}
			.electionnews-links {
				display: grid;
				gap: 0;
				padding: 0;
				> .electionnews-link-single + .electionnews-link-single {
					border-top: 1px solid #808080;
				}
				.electionnews-link-single {
					padding: 1rem 0;
					display: grid;
					align-items: center;
					a {
						display: grid;
						grid-template-columns: 2fr 1fr;
						font-size: 1rem;
						font-weight: normal;
						align-items: center;
						gap: 0 0.5rem;
						text-decoration: none;
						color: var(--base);
						picture, img {
							aspect-ratio: 3 / 2;
							object-fit: cover;
							grid-column: 2;
							grid-row: 1 / span 2;
						}
					}
				}
			}
			@media (width >= 52.5em) {
				grid-template-columns: 2fr 1fr;
				gap: 1rem;
				.bn-hero {
					border-right: 1px solid #808080;
					padding-right: 1rem;
				}
			}
		}
		section#short-news {
			padding: 1rem 1rem 2rem;
			display: grid;
			gap: 1.5rem;
			border-top: 2px solid #808080;
			.horizontalnews {
				a {
					display: grid;
					gap: 1rem;
					grid-template-columns: 1fr 2fr;
					align-items: center;
					text-decoration: none;
					font-size: 1rem;
					picture, img {
						aspect-ratio: 3 / 2;
						object-fit: cover;
					}
				}
			}
			@media (width >= 52.5em) {
				grid-template-columns: 1fr 1fr 1fr;
			}
		}
		section#npr-embed {
			padding: 1rem;
			position: relative;
			margin-bottom: 1rem;
			&::after {
				content: "";
				display: block;
				width: 66%;
				position: absolute;
				bottom: 0;
				height: 1px;
				background-color: #808080;
				margin: 0 17%;
			}
		}
	</style>
	<div id="primary" class="content-area home-page">
		<section id="npr-embed">
			<div data-pym-loader data-child-src="https://apps.npr.org/2024-election-results/bop.html?embedded=true&stateName=Texas&stateAbbrev=TX&section=key-races&showHeader=true&races=senate%2Chouse%2Cpresident&options=national&race=45870" id="responsive-embed-bop">Loading...</div>
			<script src="https://pym.nprapps.org/npr-pym-loader.v2.min.js"></script>
		</section>

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
		<section id="breaking-news">
<?php
			foreach ( $articles as $ka => $va ) {
				$post = $va;
				$post_title = get_the_title( $post );
				if ( is_front_page() ) {
					$alt_headline = get_post_meta( $post->ID, 'hpm_alt_headline', true );
					if ( !empty( $alt_headline ) ) {
						$post_title = $alt_headline;
					}
				}
				$summary = strip_tags( get_the_excerpt( $post ) );
				if ( $ka == 0 ) {
					echo ' <div class="bn-hero"><div class="image-wrapper"><h1 class="mainnews-title"><strong><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . $post_title . '</a></strong></h1><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . get_the_post_thumbnail( $post, $post->ID ) . ' </a></div></div><div class="electionnews-links">';
				} elseif ( $ka > 0 && $ka < 5 ) {
					echo '<div class="electionnews-link-single"><a href="' . get_the_permalink( $post ) . '"><span class="cat-title">' . hpm_top_cat( $post->ID ) . '</span><span>' . get_the_title( $post ) . '</span>' . get_the_post_thumbnail( $post,"thumbnail", $post->ID ) . ' </a></div>';
				} elseif ( $ka === 5 ) {
					echo '</div>';
				}
			} ?>
		</section>
		<section id="short-news">
			<?php
				foreach ( $articles as $ka => $va ) {
					$post = $va;
					if ( $ka >= 5 && $ka < 8 ) { ?>
			<div class="horizontalnews">
				<a href="<?php the_permalink();?>"><?php echo get_the_post_thumbnail(); ?><p><?php the_title(); ?></p></a>
			</div>
<?php
					}
					if ( $ka == 8 ) {
						$indepthArtcle = $post;
					}
				} ?>
		</section>
		<!-- Option One Ends here -->

		<section class="section">
			<div class="row">
				<?php get_template_part("content", "indepth"); ?>
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
		<section class="section ads-full text-center">
			<div class="page-banner">
				<a href="/2024election" title="2024 Election">
					<picture>
						<source srcset="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-mobile.png.webp" type="image/webp" media="(max-width: 34em)">
						<source srcset="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-tablet.png.webp" type="image/webp" media="(max-width: 52.5em)">
						<source srcset="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-Desktop.png.webp" type="image/webp">
						<img decoding="async" src="https://cdn.houstonpublicmedia.org/assets/images/General-Election-2024-Homepage-Ad-Desktop.png" alt="2024 Election">
					</picture>
				</a>
			</div>
		</section>
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
				<div class="col-sm-12 col-lg-8 news-list-left">
					<h2 class="title">
						<strong>News from <span>NPR</span></strong>
					</h2>
					<?php echo hpm_nprapi_output( 1002 ); ?>
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