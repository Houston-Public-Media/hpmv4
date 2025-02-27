<?php
/*
Template Name: Hello Houston
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
        article{
            padding: 0.5em !important;
        }
        article.staff
        {
            background-color: #363636;
            border-bottom: none;
            color:#fff;
            margin: 10px;
        }
        article.staff h2, article.staff h2 a
        {
            color:#fff !important;
            text-decoration: none;
        }
        article.staff .entry-summary p
        {
            color:#fff !important;
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
global $post;
	while ( have_posts() ) {
		the_post();
		$show_id = get_the_ID();
		$show = get_post_meta( $show_id, 'hpm_show_meta', true );
		$show_title = get_the_title();
		$show_content = get_the_content();
        $episodes = HPM_Podcasts::list_episodes( $show_id );
		echo HPM_Podcasts::show_header( $show_id );

        $cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
        $top = get_post_meta( get_the_ID(), 'hpm_shows_top', true );
        $terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
        $term = reset( $terms );
        $ta = 0;
        $latest_ep_args = [
            'cat' => 62609,
            'orderby' => 'date',
            'order'   => 'DESC',
            'posts_per_page' => 3,
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
            'post_type' => 'post'
        ];
        $latest_ep_args['posts_per_page'] = 16;
	}

    ?>
			<div class="houston-matters-page">
				<div class="about-houston-block">
                    <?php echo do_shortcode( $post->post_content ); ?>
				</div>

				<div class="episodes-block" style="padding-top: 15px;">
					<h2 class="title blue-bar"> <strong><span>MORE Stories</span></strong> </h2>
                    <div class="row">
                        <?php
                        global $ka;
                        $ka = 0;
                        $tag_ids = [];
                        $cat = new WP_Query( $latest_ep_args );
                        $hmcounter = 0;
                        if ( $cat->have_posts() ) {
                            while ( $cat->have_posts() ) {
                                $cat->the_post();
                                if ( $hmcounter == 2 ) { ?>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="sidebar-ad">
                                            <h4>Support Comes From</h4>
                                            <div id="div-gpt-ad-1394579228932-1">
                                                <script type='text/javascript'>
                                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else if ( $hmcounter == 7 ) { ?>
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
                                    <?php
                                }
                                get_template_part( 'content', "shows" );
                                $hmcounter++;
                            }
                        } ?>
                    </div>

				</div>
			</div>
<?php
		echo hpm_custom_pagination( $cat->max_num_pages, 4, "/topics/hello-houston/page/" ); ?>
			<p>&nbsp;</p>
		</main>
	</div>
<?php get_footer(); ?>




