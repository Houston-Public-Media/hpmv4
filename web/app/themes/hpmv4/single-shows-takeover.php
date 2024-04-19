<?php
/*
Template Name: The Takeover
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
		.shows-template-single-shows-takeover article .post-thumbnail :is(img,picture) {
			aspect-ratio: 1 / 1;
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
						<span class="badge-title">SUBSCRIBE, STREAM &amp; FOLLOW US ON</span>
					   <?php echo HPM_Podcasts::show_social( $show['podcast'], false, $show_id ); ?>
				   </div>
				</div>
			</div>
			<section id="search-results">
				<h2 class="title red-bar"> <strong><span>Episodes</span></strong> </h2>
<?php
	$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
	$terms = get_terms( [ 'include'  => $cat_no, 'taxonomy' => 'category' ] );
	$term = reset( $terms );
	$cat_args = [
		'cat' => $cat_no,
		'orderby' => 'date',
		'order'   => 'DESC',
		'posts_per_page' => 6,
		'ignore_sticky_posts' => 1
	];
	$cat = new WP_Query( $cat_args );
	if ( $cat->have_posts() ) {
		while ( $cat->have_posts() ) {
			$cat->the_post();
			$hpm_pod_desc = get_post_meta( get_the_ID(), 'hpm_podcast_ep_meta', true );
			if ( empty( $hpm_pod_desc['title'] ) ) {
				$ep_title = get_the_title();
			} else {
				$ep_title = $hpm_pod_desc['title'];
			} ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
					<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
					<div class="card-content">
						<header class="entry-header">
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo $ep_title; ?></a></h2>
							<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>' ); ?> </div>
						</header>
						<div class="entry-summary">
						<p><?php echo strip_tags( get_the_excerpt() ); ?></p>
<?php
			preg_match( '/(\[audio.+\]\[\/audio\])/', get_the_content(), $match );
			if ( !empty( $match[0]) ) {
				echo do_shortcode( $match[0] );
			}
?>
					</div>
						<footer class="entry-footer">
<?php
			$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
			if ( $tags_list ) {
				printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
					_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
					$tags_list
				);
			}
			edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
						</footer>
					</div>
				</article>
<?php
		}
	} ?>
			</section>
			<aside class="column-right">
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-2">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
						</script>
					</div>
				</div>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>