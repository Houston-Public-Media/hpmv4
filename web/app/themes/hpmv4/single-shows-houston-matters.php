<?php
/*
Template Name: Houston Matters
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
	}
	$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
	$top = get_post_meta( get_the_ID(), 'hpm_shows_top', true );
	$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
	$term = reset( $terms );
	$HMExcludedIds = [];
	$ta = 0;
	$topcat_args = [
		'cat' => $cat_no,
		'orderby' => 'date',
		'order'   => 'DESC',
		'posts_per_page' => 1,
		'ignore_sticky_posts' => 1
	];
	$tposts = new WP_Query( $topcat_args ); ?>
			<div class="houston-matters-page">
				<div class="about-houston-block">
					<div class="houston-content d-flex">
<?php
	if( $tposts->have_posts() ) {
		while ( $tposts->have_posts() ) {
			$tposts->the_post();
			$HMExcludedIds = get_the_ID(); ?>
						<div class="image-wrapper">
							<h2 class="title no-bar uppercase"> <strong><span>the latest</span></strong></h2>
							<?php if ( has_post_thumbnail() ) { ?>
							<a class="image-box" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ) ?></a>
							<?php } ?>
							<h2 class="date-title"> <strong><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></strong></h2>
                            <p><?php echo get_the_excerpt(get_the_ID());  ?></p>
						</div>
<?php
			$topcat_args['posts_per_page'] = 3;
			$topcat_args['post__not_in'] = [ $HMExcludedIds ];
		}
	} ?>
						<div class="content-wrapper">
							<div class="content-box">
								<h3 class="content-title">About <?php echo $show_title; ?></h3>
								<?php echo apply_filters( 'the_content', $show_content ); ?>
							</div>
							<div class="episode-box">
								<h3 class="content-title">Latest EPISODES</h3>
								<ul class="episode-list">
<?php
	$cat4 = new WP_Query( $topcat_args );
	if ( $cat4->have_posts() ) {
		while ( $cat4->have_posts() ) {
			$cat4->the_post();
			$HMExcludedIds = get_the_ID();
			$topcat_args['posts_per_page'] = 14;
			$topcat_args['post__not_in'] = [ $HMExcludedIds ]; ?>
									<li class="list-item">
										<a class="list-link" href="<?php the_permalink(); ?>">
											<img class="list-icon" src="https://cdn.houstonpublicmedia.org/assets/images/hpm-play-icon.png" alt="play-icon">
											<div class="list-content">
												<?php echo get_the_title(); ?>
											</div>
										</a>
									</li>
<?php
		}
	} ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div id="station-social" class="station-social">
				   <div class="badges-box">
						<span class="badge-title">SUBSCRIBE, STREAM &amp; FOLLOW US ON</span>
					   <?php echo HPM_Podcasts::show_social( $show['podcast'], false, $show_id ); ?>
				   </div>
				</div>
				<div class="episodes-block">
					<h2 class="title red-bar"> <strong><span>MORE stories</span></strong> </h2>
					<div class="row">
<?php
	global $ka;
	$ka = 0;
	$tag_ids = [];
	$cat = new WP_Query( $topcat_args );
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
		<?php }
			get_template_part('content', "shows");
		$hmcounter++;
		}
	} ?>
					</div>
				</div>
			</div>
<?php
		if ( $cat->found_posts > 15 ) {
			echo hpm_custom_pagination( $cat->max_num_pages, 4, "/topics/houston-matters/page/" );
		} ?>
			<p>&nbsp;</p>
		</main>
	</div>
<?php get_footer(); ?>