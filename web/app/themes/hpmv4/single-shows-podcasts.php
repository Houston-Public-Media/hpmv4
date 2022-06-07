<?php
/*
Template Name: Podcast
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
			background-color: white;
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
			background-color: white;
		}
		body.single-shows .podcast-badges {
			justify-content: flex-end;
		}
		.show-content > * + * {
			margin-top: 1rem;
		}
		#float-wrap article#hpm-show-podcast.card.card-medium {
			padding: 1em 1.5em;
			flex-flow: row wrap;
			margin: 2em 0;
			width: 100%;
		}
		#hpm-show-podcast h2 {
			width: 100%;
			margin: 0;
			padding: 0;
		}
		.podcast-pane {
			width: 100%;
			padding: 0.5em 0;
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
			#float-wrap article#hpm-show-podcast.card.card-medium {
				padding: 1em;
				flex-flow: row wrap;
			}
			#hpm-show-podcast h2 {
				padding: 0;
			}
			.podcast-pane:nth-child(2) {
				width: 60%;
				padding: 1em 1em 0 0;
			}
			.podcast-pane:nth-child(3) {
				width: 40%;
				padding: 1em 0 0 0;
			}
		}
		@media screen and (min-width: 52.5em) {
			body.single-shows #station-social {
				grid-template-columns: 1fr 2fr;
			}
			body.single-shows #station-social.station-no-social {
				grid-template-columns: 1fr !important;
			}
			#float-wrap article#hpm-show-podcast.card.card-medium {
				width: 64.5%;
				margin: 0 0.75% 1em;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post();
			$show_id = get_the_ID();
			$show = get_post_meta( $show_id, 'hpm_show_meta', true );
			$show_title = get_the_title();
			$show_content = get_the_content();
			$episodes = HPM_Podcasts::list_episodes( $show_id );
			echo HPM_Podcasts::show_header( $show_id );
			endwhile; ?>
			<div id="float-wrap">
<?php
	if ( !empty( $show['podcast'] ) ) :
		$last_id = get_post_meta( $show['podcast'], 'hpm_pod_last_id', true );
		$enc = get_post_meta( $last_id['id'], 'hpm_podcast_enclosure', true );
		$audio = '[audio mp3="' . $enc['url'] . '"][/audio]'; ?>
				<article class="card card-medium" id="hpm-show-podcast">
					<h2><?php echo $show_title; ?> Podcast</h2>
					<div class="podcast-pane">
						<p>Listen to the Latest Episode</p>
						<?php echo do_shortcode( $audio ); ?>
					</div>
					<div class="podcast-pane">
						<p>Or subscribe in your favorite app</p>
						<?php echo HPM_Podcasts::show_social( $show['podcast'], false, '' ); ?>
					</div>
				</article>
<?php
	endif; ?>
				<aside class="column-right">
					<h3>About <?php echo $show_title; ?></h3>
					<div class="show-content">
						<?php echo apply_filters( 'the_content', $show_content ); ?>
					</div>
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-1">
							<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
							</script>
						</div>
					</div>
				</aside>
				<div class="article-wrap">
		<?php
			$cat_no = get_post_meta( get_the_ID(), 'hpm_shows_cat', true );
			$top =  get_post_meta( get_the_ID(), 'hpm_shows_top', true );
			$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
			$term = reset( $terms );
			$cat_args = array(
				'cat' => $cat_no,
				'orderby' => 'date',
				'order'   => 'DESC',
				'posts_per_page' => 15,
				'ignore_sticky_posts' => 1
			);
			global $ka;
			$ka = 0;
			if ( !empty( $top ) && $top !== 'None' ) :
				$top_art = new WP_query( [ 'p' => $top ] );
				$cat_args['posts_per_page'] = 14;
				$cat_args['post__not_in'] = [ $top ];
				if ( $top_art->have_posts() ) :
					while ( $top_art->have_posts() ) : $top_art->the_post();
						get_template_part( 'content', get_post_type() );
						$ka += 2;
					endwhile;
					$post_num = 14;
				endif;
				wp_reset_query();
			endif;
			$cat = new WP_query( $cat_args );
			if ( $cat->have_posts() ) :
				while ( $cat->have_posts() ) : $cat->the_post();
					get_template_part( 'content', get_post_type() );
					$ka += 2;
				endwhile;
			endif; ?>
				</div>
			</div>
		<?php
			if ( $cat->found_posts > 15 ) : ?>
			<div class="readmore">
				<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
			</div>
		<?php
			endif;
			if ( !empty( $show['ytp'] ) ) : ?>
			<div id="shows-youtube">
				<div id="youtube-wrap">
				<?php
					$json = hpm_youtube_playlist( $show['ytp'] );
					foreach ( $json as $tubes ) :
						$pubtime = strtotime( $tubes['snippet']['publishedAt'] );
						if ( $c == 0 ) : ?>
					<div id="youtube-main">
						<div id="youtube-player" style="background-image: url( '<?php echo $tubes['snippet']['thumbnails']['high']['url']; ?>' );" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>">
							<?php echo hpm_svg_output( 'play' ) ?>
						</div>
						<h2><?php echo $tubes['snippet']['title']; ?></h2>
						<p class="desc"><?php echo $tubes['snippet']['description']; ?></p>
						<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
					</div>
					<div id="youtube-upcoming">
						<h4>Past Shows</h4>
					<?php
						endif; ?>
						<div class="youtube" id="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-ytid="<?php echo $tubes['snippet']['resourceId']['videoId']; ?>" data-yttitle="<?php echo htmlentities( $tubes['snippet']['title'], ENT_COMPAT ); ?>" data-ytdate="<?php echo date( 'F j, Y', $pubtime); ?>" data-ytdesc="<?php echo htmlentities($tubes['snippet']['description']); ?>">
							<img src="<?php echo $tubes['snippet']['thumbnails']['medium']['url']; ?>" alt="<?php echo $tubes['snippet']['title']; ?>" />
							<h2><?php echo $tubes['snippet']['title']; ?></h2>
							<p class="date"><?php echo date( 'F j, Y', $pubtime); ?></p>
						</div>
					<?php
						$c++;
					endforeach; ?>
					</div>
				</div>
			</div>
	<?php
			endif; ?>
		</main>
	</div>
<?php get_footer(); ?>