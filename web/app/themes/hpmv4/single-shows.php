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
        <div class="row about-party">
            <div class="col-sm-9">
            <h2 class="title no-bar"> <strong><span>ABOUT <?php echo $show_title; ?></span></strong> </h2>
            <div class="show-content">
                <?php echo apply_filters( 'the_content', $show_content ); ?>
            </div>
            </div>
            <div class="col-sm-3">
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
            <h2 class="title red-bar"> <strong><span>All Stories</span></strong> </h2>
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




            <div>
    <?php
        if ( $cat->found_posts > 15 ) {
            wp_pagenavi( array( 'query' => $cat ) );
        }
    ?>
    <p>&nbsp;</p></div>
    </div>
    </main>
    </div>
<?php get_footer(); ?>