<?php
/*
Template Name: Party Politics
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
		$episodes = HPM_Podcasts::list_episodes( $show_id );
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
		$json = hpm_youtube_playlist( $show['ytp'], 1 );
		if ( !empty( $json ) ) {
			$c = 0;
			foreach ( $json as $tubes ) {
				$yt_title = str_replace( $show_title . ' | ', '', $tubes['snippet']['title'] );
				$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
				$ytimage = $tubes['snippet']['thumbnails']['default']['url'];
				if ( !empty( $tubes['snippet']['thumbnails']['high']['url'] ) ) {
					$ytimage = $tubes['snippet']['thumbnails']['high']['url'];
				} elseif ( !empty( $tubes['snippet']['thumbnails']['standard']['url'] ) ) {
					$ytimage = $tubes['snippet']['thumbnails']['standard']['url'];
				}
				if ( $c == 0 && !str_contains( $yt_title, 'Private Video' ) ) { ?>
							<div class="episodes-content" id="youtube-main">
								<div class="image-wrapper">
									<div id="youtube-player" style="background-image: url( '<?php echo $ytimage; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $yt_title, ENT_COMPAT ); ?>">
										<?php echo hpm_svg_output( 'play' ); ?>
									</div>
								</div>
								<div class="content-wrapper">
									<span class="date"><?php echo date( 'F j, Y', $pubtime); ?></span>
									<h2 class="content-title"><?php echo $yt_title; ?></h2>
									<div class="desc-wrap"> <p class="desc"><?php echo str_replace( "\n", "<br />", $tubes['snippet']['description'] ); ?></p><button type="button" class="yt-readmore">Read More...</button></div>
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
	$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
	$top =  get_post_meta( get_the_ID(), 'hpm_shows_top', true );
	$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
	$term = reset( $terms );
	$cat_args = [
		'cat' => $cat_no,
		'orderby' => 'date',
		'order'   => 'DESC',
		'posts_per_page' => 15,
		'ignore_sticky_posts' => 1
	];
	global $ka;
	$ka = 0;
	$tag_ids = [];
	if ( !empty( $top ) && $top !== 'None' ) {
		$top_art = new WP_Query( [ 'p' => $top ] );
		$cat_args['posts_per_page'] = 14;
		$cat_args['post__not_in'] = [ $top ];
		if ( $top_art->have_posts() ) {
			while ( $top_art->have_posts() ) {
				$top_art->the_post();
				get_template_part( 'content', get_post_type() );
				$ka += 3;
				if ( $show_id === 380127 || $show_id === 119016 ) {
					$tags = wp_get_post_tags( get_the_ID() );
					if ( $tags ) {
						foreach ( $tags as $individual_tag ) {
							if ( ! in_array( $individual_tag->term_id, $tag_ids ) ) {
								$tag_ids[] = $individual_tag->term_id;
							}
						}
					}
				}
			}
			$post_num = 14;
		}
		wp_reset_query();
	}
	$cat = new WP_Query( $cat_args );
    $hmcounter = 0;
	if ( $cat->have_posts() ) {
		while ( $cat->have_posts() ) {
			$cat->the_post();
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
                </div>
            <?php }
            get_template_part( 'content', "shows" );
            $hmcounter++;
			$ka += 3;
		}
	} ?>
					</div>
				</div>
			</div>
<?php
	if ( $cat->found_posts > 15 ) {
		echo hpm_custom_pagination($cat->max_num_pages, 4, "/topics/party-politics/page/");
	} ?>
			<p>&nbsp;</p>
		</main>
	</div>
<?php get_footer(); ?>