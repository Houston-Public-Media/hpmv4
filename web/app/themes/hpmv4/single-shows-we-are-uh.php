<?php
/*
Template Name: We Are UH
Template Post Type: shows
*/
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */

get_header(); ?>
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
		}
		@media screen and (min-width: 52.5em) {
			body.single-shows #station-social {
				grid-template-columns: 2fr 3fr;
			}
			body.single-shows #station-social.station-no-social {
				grid-template-columns: 1fr !important;
			}
		}
		[data-theme="dark"] body.single-shows #station-social h3 {
			color: var(--accent-red-4);
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
				echo HPM_Podcasts::show_header( $show_id );
			} ?>
			<div class="party-politics-page">
				<div class="about-party">
					<h2 class="title no-bar"> <strong><span>ABOUT <?php echo $show_title; ?></span></strong> </h2>
					<div class="show-content">
						<?php echo apply_filters( 'the_content', $show_content ); ?>
					</div>
				</div>
				<div id="station-social" class="station-social">
					<div class="badges-box">
						<span class="badge-title">SUBSCRIBE,  STREAM  &  FOLLOW US ON</span>
						<?php echo HPM_Podcasts::show_social( $show['podcast'], false, $show_id ); ?>
					</div>
				</div>
				<div class="row text-content">
					<div class="col-sm-12 col-lg-8">
						<div class="the-latest-block">
							<h2 class="title red-bar"> <strong><span>the latest</span></strong> </h2>
							<?php
							if ( !empty( $show['ytp'] ) ) {
								$json = hpm_youtube_playlist( $show['ytp'], 50 );
								if ( !empty( $json ) ) {
									$public = [];
									foreach ( $json as $k => $v ) {
										if ( !str_contains( strtolower( $v['snippet']['title'] ), 'private video' ) ) {
											$public[] = $k;
										}
									}
									if ( !empty( $public ) ) {
										$tubes = $json[ $public[0] ];
										unset( $public[0] );
										$yt_title = $tubes['snippet']['title'];

										$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
										$ytimage = $tubes['snippet']['thumbnails']['default']['url'];
										if ( !empty( $tubes['snippet']['thumbnails']['high']['url'] ) ) {
											$ytimage = $tubes['snippet']['thumbnails']['high']['url'];
										} elseif ( !empty( $tubes['snippet']['thumbnails']['standard']['url'] ) ) {
											$ytimage = $tubes['snippet']['thumbnails']['standard']['url'];
										}
										$yt_desc_trim = $tubes['snippet']['description']; ?>
										<div class="episodes-content" id="youtube-main">
											<div class="image-wrapper">
												<div id="youtube-player" style="background-image: url( '<?php echo $ytimage; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $yt_title, ENT_COMPAT ); ?>">
													<?php echo hpm_svg_output( 'play' ); ?>
												</div>
											</div>
											<div class="content-wrapper">
												<span class="date"><?php echo date( 'F j, Y', $pubtime); ?></span>
												<h2 class="content-title"><?php echo $yt_title; ?></h2>
												<div class="desc-wrap"> <p class="desc"><?php echo $yt_desc_trim; ?></p><button type="button" class="yt-readmore">Read More...</button></div>
												<dialog id="yt-dialog">
													<div class="yt-dialog-content">
														<h3></h3>
														<p></p>
														<ul class="dialog-actions">
															<li><button type="button" data-action="dismiss">Dismiss</button></li>
														</ul>
													</div>
												</dialog>
												<script>
													const dialog = document.getElementById("yt-dialog");
													const readMore = document.querySelector("#youtube-main .yt-readmore");

													readMore.addEventListener("click", ({ target }) => {
														var desc = document.querySelector("#youtube-main .desc");
														var title = document.querySelector("#youtube-main h2");
														dialog.querySelector("p").innerHTML = desc.innerHTML;
														dialog.querySelector("h3").textContent = title.textContent;
														dialog.showModal();
													});

													dialog.addEventListener("click", ({ target }) => {
														if (target.matches('dialog') || target.matches('[data-action="dismiss"]')) {
															dialog.close();
														}
													});
												</script>
											</div>
										</div>
										<?php
									}
								}
							} ?>
						</div>
					</div>
					<div class="col-sm-12 col-lg-4">
						<div class="sidebar-ad">
							<h4>Support Comes From</h4>
							<div id="div-gpt-ad-1394579228932-1">
								<script type='text/javascript'>
									googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
								</script>
							</div>
						</div>
					</div>
				</div>
				<div class="episodes-block">
					<h2 class="title red-bar"> <strong><span>MORE episodes</span></strong> </h2>
					<div class="row">
<?php
	if ( !empty( $json ) && !empty( $public ) ) {
		$hmcounter = 0;
		foreach ( $public as $p ) {
			if ( $hmcounter == 5 ) { ?>
						<div class="col-sm-6 col-md-4">
							<div class="sidebar-ad">
								<h4>Support Comes From</h4>
								<div id="div-gpt-ad-1394579228932-2">
									<script type='text/javascript'>
										googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
									</script>
								</div>
							</div>
						</div><?php
			}
			$tubes = $json[ $p ];
			$yt_title = $tubes['snippet']['title'];
			$ytimage = $tubes['snippet']['thumbnails']['default']['url'];
			if ( !empty( $tubes['snippet']['thumbnails']['high']['url'] ) ) {
				$ytimage = $tubes['snippet']['thumbnails']['high']['url'];
			} elseif ( !empty( $tubes['snippet']['thumbnails']['standard']['url'] ) ) {
				$ytimage = $tubes['snippet']['thumbnails']['standard']['url'];
			}
			$yt_desc_trim = $tubes['snippet']['description']; ?>
						<div class="col-sm-6 col-md-4">
							<div class="episodes-content">
								<a class="post-thumbnail" href="https://www.youtube.com/watch?v=<?php echo $tubes['snippet']['resourceId']['videoId']; ?>"><img width="450" height="253" src="<?php echo $ytimage; ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="<?php echo $yt_title; ?>>" decoding="async" loading="lazy" /></a>
								<div class="content-wrapper">
									<h4 class="content-title"><a href="https://www.youtube.com/watch?v=<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" rel="bookmark"><?php echo $yt_title; ?></a></h4>
									<p><?php echo $yt_desc_trim; ?></p>
								</div>
							</div>
						</div><?php
			$hmcounter++;
		}
	} ?>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
		</main>
	</div>
<?php get_footer(); ?>