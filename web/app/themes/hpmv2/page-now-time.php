<?php
/*
Template Name: Now is the Time
Template Post Type: page
*/

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
	while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<h1 class="page-title screen-reader-text"><?php the_title(); ?></h1>
			</header>
<?php
	endwhile; ?>
			<div id="shows-youtube">
				<div id="youtube-wrap">
<?php
	$c = 0;
	$yt = file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=50&playlistId=PLGHyNdqkLN-DCYiUO6nd4CzGJWaM0kLkn&key=AIzaSyBHSGTRPfGElaMTniNCtHNbHuGHKcjPRxw');
	$json = json_decode($yt,TRUE);
	foreach ( $json['items'] as $tubes ) :
		if ( $c == 0 ) : ?>
					<div id="youtube-main">
						<div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['maxres']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
							<span class="fa fa-play" id="play-button"></span>
						</div>
						<h2><?php echo $tubes['snippet']['title']; ?></h2>
					</div>
					<div id="youtube-upcoming">
						<h4>Up Next</h4>
<?php
		endif; ?>
						<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
							<img src="<?php echo $tubes['snippet']['thumbnails']['maxres']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
							<h2><?php echo $tubes['snippet']['title']; ?></h2>
						</div>
<?php
		$c++;
	endforeach; ?>
					</div>
				</div>
			</div>
			<style>
				.page-header {
					background-image: url(https://cdn.hpm.io/assets/images/nitt-header-mobile.png);
					height: 0;
					padding: 0;
					overflow: visible;
					background-repeat: no-repeat;
					background-size: contain;
					background-position: left top;
					width: 100%;
					background-color: transparent;
					position: relative;
					padding-bottom: calc(100%/2.5);
				}
				#shows-youtube {
					padding: 0;
				}
				#shows-youtube #youtube-main {
					margin: 0;
					padding: 0;
				}
				#shows-youtube #youtube-main h2 {
					padding: 0 1em;
					margin: 0;
				}
				#shows-youtube #youtube-upcoming {
					width: 100%;
					margin: 1em 0;
					border-bottom: 0.125em solid #f5f5f5;
				}
				#shows-youtube #youtube-upcoming .youtube:nth-child(2) {
					border-top: 0.125em solid #f5f5f5;
				}
				#shows-youtube #youtube-upcoming .youtube img {
					float: none;
					width: 100%;
					padding: 0 0 1em 0;
				}
				#shows-youtube #youtube-upcoming .youtube h2 {
					margin: 0;
				}
				@media screen and (min-width: 30.0625em) {
					.page-header {
						background-image: url(https://cdn.hpm.io/assets/images/nitt-header-tablet.png);
						padding-bottom: calc(100%/4);
					}
					#shows-youtube #youtube-main {
						width: 100%;
					}
					#shows-youtube #youtube-upcoming .youtube {
						height: 18.5em;
					}
					#shows-youtube #youtube-upcoming .youtube:nth-child(2n) {
						border-right: 0.125em solid #f5f5f5;
					}
					#shows-youtube #youtube-upcoming .youtube:nth-child(3) {
						border-top: 0.125em solid #f5f5f5;
					}
				}
				@media screen and (min-width: 50.0625em) {
					.page-header {
						background-image: url(https://cdn.hpm.io/assets/images/nitt-header.png);
						padding-bottom: calc(100%/6);
					}
					#shows-youtube #youtube-main {
						width: 67%;
						height: 0;
						padding: 0;
						padding-bottom: calc(67%/1.4888889);
					}
					#shows-youtube #youtube-main h2 {
						padding: 0 1em 1em;
					}
					#shows-youtube #youtube-upcoming {
						width: 31%;
						height: 0;
						padding: 0;
						margin: 0 0 0 2%;
						padding-bottom: calc(67%/1.4888889);
						overflow-y: scroll;
						border: 0;
					}
					#shows-youtube #youtube-upcoming .youtube:nth-child(2n) {
						border-right: 0;
					}
					#shows-youtube #youtube-upcoming .youtube:nth-child(3) {
						border-top: 0;
					}
					#shows-youtube #youtube-upcoming .youtube {
						height: auto;
					}
					#shows-youtube #youtube-upcoming .youtube h2 {
						padding: 0 0.5em;
					}
					#shows-youtube #youtube-wrap {
						background-color: transparent;
					}
				}
			</style>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>