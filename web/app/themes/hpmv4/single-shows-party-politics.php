<?php
/*
Template Name: Default Show
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
			justify-content: flex-end;
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
        //$options = get_post_meta( $id, 'hpm_show_meta', true );
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
            <div class="col-sm-9 col-md-12">
                <div class="the-latest-block">
                    <h2 class="title red-bar"> <strong><span>the latest</span></strong> </h2>

                    <?php
                    if ( !empty( $show['ytp'] ) ) {
                        $json = hpm_youtube_playlist( $show['ytp'] );
                        if ( !empty( $json ) ) {
                            $c = 0; ?>

                            <?php
                            foreach ( $json as $tubes ) {
                                $yt_title = str_replace( $show_title . ' | ', '', $tubes['snippet']['title'] );
                                $pubtime = strtotime( $tubes['snippet']['publishedAt'] );
                                if ( $c == 0 && !str_contains( $yt_title, 'Private Video' ) ) { ?>
                                    <div class="episodes-content" id="youtube-main">
                                        <a href="" class="image-wrapper">
                                            <div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $yt_title, ENT_COMPAT ); ?>">
                                                <?php echo hpm_svg_output( 'play' ); ?>
                                            </div>
                                        </a>
                                        <div class="content-wrapper">
                                            <span class="date"><?php echo date( 'F j, Y', $pubtime); ?></span>
                                            <h3 class="content-title"><?php echo $yt_title; ?></h3>
                                            <div class="desc-wrap"> <?php echo str_replace( "\n", "<br />", $tubes['snippet']['description'] ); ?></p><button type="button" class="yt-readmore">Read More...</button></div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    }?>
                </div>
                </p>
            </div>
            <div class="col-sm-3 col-md-12">
                <div class="sidebar-ad">
                    <h4>Support Comes From</h4>
                    <div id="div-gpt-ad-1394579228932-1">
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                        </script>
                    </div>
                </div>
            </div>
            <div>
            </div></div>



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
if ( $cat->have_posts() ) {
    while ( $cat->have_posts() ) {
        $cat->the_post();
        //echo "Tras che: ".get_post_type();
        get_template_part( 'content', "shows" );

        $ka += 3;
    }
} ?>


            </div>

        </div>
    </div>

	<!--<div class="houston-matters-page">
		<div class="about-houston-block">
            <div class="houston-content d-flex">
				<div class="image-wrapper">
					<h2 class="title no-bar uppercase"> <strong><span>the latest</span></strong> </h2>
					<a href="" class="image-box">
						<img src="<?php /*echo get_template_directory_uri(); */?>/images/Banner-mobile.png" alt="Banner-mobile">
					</a>
					<h2 class="date-title"> <strong><a href="">Outsiders’ views of Texas (Sept. 22, 2023)</a></strong> </h2>
                </div>
                <div class="content-wrapper">
					<div class="content-box">
						<h3 class="content-title">About HOUSTON MATTERS</h3>
						<p>
							This week, Co-hosts Brandon Rottinghaus and Jeronimo Cortina discuss House speaker Kevin
							McCarthy's capitulation to the House Freedom Caucus on an impeachment inquiry into President
							Biden, the ongoing court fight over Governor Greg Abbott's border buoys, and the ongoing
							impeachment trial of Texas attorney general Ken Paxton, among other stories.
						</p>
					</div>
					<div class="episode-box">
						<h3 class="content-title">Latest EPISODES</h3>
						<ul class="episode-list">
							<li class="list-item">
							<a class="list-link" href="">
								<img class="list-icon" src="<?php /*echo get_template_directory_uri(); */?>/images/play-icon.png" alt="play-icon">
								<div class="list-content">
									Kinder survey: Houstonians show an increased desire for  walkab
								</div>
							</a>
							</li>
							<li class="list-item">
								<a class="list-link" href="">
									<img class="list-icon" src="<?php /*echo get_template_directory_uri(); */?>/images/play-icon.png" alt="play-icon">
									<div class="list-content">
										Kinder survey: Houstonians show an increased desire for  walkab
									</div>
								</a>
							</li>
							<li class="list-item">
								<a class="list-link" href="">
									<img class="list-icon" src="<?php /*echo get_template_directory_uri(); */?>/images/play-icon.png" alt="play-icon">
									<div class="list-content">
										Kinder survey: Houstonians show an increased desire for  walkab
									</div>
								</a>
							</li>
						</ul>
					</div>
                </div>
            </div>
        </div>
        <div id="station-social" class="station-social">
           <div class="badges-box">
				<span class="badge-title">SUBSCRIBE,  STREAM  &  FOLLOW US ON</span>
				<ul class="podcast-badges">
					<li>
						<a href="https://podcasts.apple.com/us/podcast/party-politics-houston-public-media/id1221484897?mt=2" rel="noopener" target="_blank" title="Subscribe on Apple Podcasts">
							<img src="https://cdn.houstonpublicmedia.org/assets/images/podcasts/apple.png" alt="Subscribe on Apple Podcasts" title="Subscribe on Apple Podcasts">
						</a>
					</li>
					<li>
						<a href="https://podcasts.google.com/feed/aHR0cHM6Ly93d3cuaG91c3RvbnB1YmxpY21lZGlhLm9yZy9wb2RjYXN0cy9wYXJ0eS1wb2xpdGljcy8" rel="noopener" target="_blank" title="Subscribe on Google Podcasts">
							<img src="https://cdn.houstonpublicmedia.org/assets/images/podcasts/google_podcasts.png" alt="Subscribe on Google Podcasts" title="Subscribe on Google Podcasts">
						</a>
					</li>
					<li><a href="https://open.spotify.com/show/2imRSf1dUBb5Kwt44WDWVE?si=9vWqWqe_SW-0Ath_rOAjvg" rel="noopener" target="_blank" title="Subscribe on Spotify">
							<img src="https://cdn.houstonpublicmedia.org/assets/images/podcasts/spotify.png" alt="Subscribe on Spotify" title="Subscribe on Spotify">
						</a></li>
					<li>
						<a href="http://localhost/podcasts/party-politics/" target="_blank" title="Subscribe via RSS">
							<img src="https://cdn.houstonpublicmedia.org/assets/images/podcasts/rss.png" alt="Subscribe via RSS" title="Subscribe via RSS">
						</a>
					</li>
					<li class="service-icon youtube">
						<a href="https://www.youtube.com/playlist?list=PLGHyNdqkLN-B_O8hrz0L10z67y8G84fLc" rel="noopener" target="_blank" title="YouTube">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path
									d="M472.5,146.9c-5.2-19.6-20.6-35.1-40-40.3c-35.3-9.5-177-9.5-177-9.5s-141.7,0-177,9.5 c-19.5,5.2-34.8,20.7-40,40.3C29,182.4,29,256.6,29,256.6s0,74.2,9.5,109.7c5.2,19.6,20.6,34.4,40,39.7c35.3,9.5,177,9.5,177,9.5 s141.7,0,177-9.5c19.5-5.2,34.8-20,40.1-39.7c9.5-35.6,9.5-109.7,9.5-109.7S482,182.4,472.5,146.9z M209.2,324V189.3l118.4,67.4 L209.2,324L209.2,324z">
								</path>
							</svg>
						</a>
					</li>
					<li class="service-icon twitter">
						<a href="https://twitter.com/hpmpolitics" rel="noopener"
							target="_blank" title="Twitter">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
								<path
									d="M435.5,163.9c0.3,4,0.3,8,0.3,12c0,122.5-93.2,263.6-263.6,263.6C119.8,439.6,71,424.4,30,398 c7.5,0.9,14.6,1.1,22.4,1.1c43.3,0,83.2-14.6,115-39.6c-40.7-0.9-74.9-27.5-86.6-64.2c5.7,0.9,11.5,1.4,17.5,1.4 c8.3,0,16.6-1.1,24.4-3.2c-42.4-8.6-74.3-45.9-74.3-90.9v-1.1c12.3,6.9,26.7,11.2,41.9,11.8c-25-16.6-41.3-45-41.3-77.2 c0-17.2,4.6-33,12.6-46.7c45.6,56.2,114.1,92.9,191,96.9c-1.4-6.9-2.3-14.1-2.3-21.2c0-51.1,41.3-92.6,92.6-92.6 c26.7,0,50.8,11.2,67.7,29.3c20.9-4,41-11.8,58.8-22.4c-6.9,21.5-21.5,39.6-40.7,51.1c18.6-2,36.7-7.2,53.3-14.3 C469.4,134.4,453.6,150.7,435.5,163.9L435.5,163.9z">
								</path>
							</svg>
						</a>
					</li>
				</ul>
		   </div>
        </div>
        <div class="episodes-block red-line">
            <h2 class="title red-bar"> <strong><span>MORE STORIES</span></strong> </h2>
			<div class="row">
                <div class="col-sm-4">
                    <div class="episodes-content">
                        <a href="" class="image-wrapper"> <img
                                src="<?php /*echo get_template_directory_uri(); */?>/images/stories-thumb.jpg"
                                alt="party-thumb"> </a>
                        <div class="content-wrapper">
                            <h4 class="content-title">
                                <a href="" >Are we still living in the Texas depicted in “King of the Hill?”</a>
                            </h4>
                            <p>Mayor Sylvester Turner is seeking
								temporary funding while METRO
								considers establishing its own bike
								share program to replace it.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="episodes-content">
                        <a href="" class="image-wrapper"> <img
                                src="<?php /*echo get_template_directory_uri(); */?>/images/stories-thumb.jpg"
                                alt="stories-thumb"> </a>
                        <div class="content-wrapper">
                            <h4  class="content-title">
                                <a href="" >Remember how all those Californians were moving to Texas? Some aren’t staying.</a>
                            </h4>
                            <p>Mayor Sylvester Turner is seeking
								temporary funding while METRO
								considers establishing its own bike
								share program to replace it.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="episodes-content">
                        <a href="" class="image-wrapper"> <img
                                src="<?php /*echo get_template_directory_uri(); */?>/images/stories-thumb.jpg"
                                alt="stories-thumb"> </a>
                        <div class="content-wrapper">
                            <h4  class="content-title">
                                <a href="" >BCycle, Houston’s bike share program, may get a temporary reprieve</a>
                            </h4>
							<p>Mayor Sylvester Turner is seeking
								temporary funding while METRO
								considers establishing its own bike
								share program to replace it.</p>
                        </div>
                    </div>
                </div>
				<div class="col-sm-4">
                    <div class="episodes-content">
                        <a href="" class="image-wrapper"> <img
                                src="<?php /*echo get_template_directory_uri(); */?>/images/stories-thumb.jpg"
                                alt="stories-thumb"> </a>
                        <div class="content-wrapper">
                            <h4 class="content-title">
                                <a href="" >Are we still living in the Texas depicted in “King of the Hill?”</a>
                            </h4>
                            <p>Mayor Sylvester Turner is seeking
								temporary funding while METRO
								considers establishing its own bike
								share program to replace it.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="episodes-content">
                        <a href="" class="image-wrapper"> <img
                                src="<?php /*echo get_template_directory_uri(); */?>/images/stories-thumb.jpg"
                                alt="stories-thumb"> </a>
                        <div class="content-wrapper">
                            <h4  class="content-title">
                                <a href="" >Remember how all those Californians were moving to Texas? Some aren’t staying.</a>
                            </h4>
                            <p>Mayor Sylvester Turner is seeking
								temporary funding while METRO
								considers establishing its own bike
								share program to replace it.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="episodes-content">
                        <a href="" class="image-wrapper"> <img
                                src="<?php /*echo get_template_directory_uri(); */?>/images/stories-thumb.jpg"
                                alt="stories-thumb"> </a>
                        <div class="content-wrapper">
                            <h4  class="content-title">
                                <a href="" >BCycle, Houston’s bike share program, may get a temporary reprieve</a>
                            </h4>
							<p>Mayor Sylvester Turner is seeking
								temporary funding while METRO
								considers establishing its own bike
								share program to replace it.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

            <div>
    <?php
        if ( $cat->found_posts > 15 ) {
            wp_pagenavi( array( 'query' => $cat ) );
        }
    ?>
    <p>&nbsp;</p></div>

    </main>
    </div>
<?php get_footer(); ?>