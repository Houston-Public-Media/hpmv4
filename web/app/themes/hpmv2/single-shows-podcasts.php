<?php
/*
Template Name: Podcast
Template Post Type: shows
*/
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post();
				$show_name = $post->post_name;
				$social = get_post_meta( get_the_ID(), 'hpm_show_social', true );
				$show = get_post_meta( get_the_ID(), 'hpm_show_meta', true );
				$header_back = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$show_title = get_the_title();
				$show_content = get_the_content();
				$page_head_class = HPM_Podcasts::show_banner( get_the_ID() ); ?>
			<header class="page-header<?php echo $page_head_class; ?>">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>
			<?php
				$no = $sp = $c = 0;
				foreach( $show as $sk => $sh ) :
					if ( !empty( $sh ) && $sk != 'banners' ) :
						$no++;
					endif;
				endforeach;
				foreach( $social as $soc ) :
					if ( !empty( $soc ) ) :
						$no++;
					endif;
				endforeach;
				if ( $no > 0 ) : ?>
			<div id="station-social">
			<?php
					if ( !empty( $show['times'] ) ) : ?>
				<h3><?php echo $show['times']; ?></h3>
			<?php
					endif;
					echo HPM_Podcasts::show_social( '', false, get_the_ID() ); ?>
			</div>
			<?php
				endif;?>
		<?php
			endwhile; ?>
			<div id="float-wrap">
				<div class="grid-sizer"></div>
<?php
	if ( !empty( $show['podcast'] ) ) :
		/* $pod_link = get_post_meta( $show['podcast'], 'hpm_pod_link', true );
		if ( !empty( $pod_link['itunes'] ) ) :
			echo '<article class="felix-type-a"><iframe allow="autoplay *; encrypted-media *; fullscreen *" frameborder="0" height="450" style="width:100%;overflow:hidden;background:transparent;" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-storage-access-by-user-activation allow-top-navigation-by-user-activation" src="'.str_replace( 'https://podcasts.apple.com', 'https://embed.podcasts.apple.com', $pod_link['itunes'] ).'"></iframe></article>';
		endif; */
		$last_id = get_post_meta( $show['podcast'], 'hpm_pod_last_id', true );
		$enc = get_post_meta( $last_id['id'], 'hpm_podcast_enclosure', true );
		$audio = '[audio mp3="' . $enc['url'] . '"][/audio]'; ?>
				<article class="felix-type-b" id="hpm-show-podcast">
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
				<style>
					#float-wrap article#hpm-show-podcast.felix-type-b {
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
					#hpm-show-podcast .jp-type-single {
						background-color: transparent;
					}
					#hpm-show-podcast .jp-gui.jp-interface .jp-controls button {
						background-color: transparent;
						width: 4em;
						height: 4em;
					}
					#hpm-show-podcast .jp-gui.jp-interface .jp-controls button .fa {
						font-size: 3.25em;
					}
					#hpm-show-podcast .jp-gui.jp-interface .jp-progress-wrapper {
						position: relative;
						padding: 1em 0.5em;
					}
					#hpm-show-podcast .jp-gui.jp-interface .jp-progress-wrapper .jp-progress {
						margin: 0;
						background-color: rgb(79, 79, 79);
						z-index: 9;
						position: relative;
					}
					#hpm-show-podcast .jp-gui.jp-interface .jp-progress-wrapper .jp-progress .jp-seek-bar {
						z-index: 11;
					}
					#hpm-show-podcast .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
						position: absolute;
						top: 1.5em;
						right: 1em;
						z-index: 10;
						float: none;
						width: auto;
						display: inline;
						padding: 0;
						color: white;
					}
					@media screen and (min-width: 34em) {
						#float-wrap article#hpm-show-podcast.felix-type-b {
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
						#hpm-show-podcast .jp-gui.jp-interface .jp-details {
							display: none;
						}
						#hpm-show-podcast .jp-gui.jp-interface .jp-progress-wrapper .jp-time-holder {
							top: 1.25em;
						}
					}
					@media screen and (min-width: 52.5em) {
						#float-wrap article#hpm-show-podcast.felix-type-b {
							width: 64.5%;
							margin: 0 0.75% 1em;
						}
					}
				</style>
<?php
	endif; ?>
				<aside class="column-right">
					<h3>About <?php echo $show_title; ?></h3>
					<div class="show-content">
						<?php echo apply_filters( 'the_content', $show_content ); ?>
					</div>
			<?php
						if ( $show_name == 'skyline-sessions' || $show_name == 'music-in-the-making' ) :
							$googletag = 'div-gpt-ad-1470409396951-0';
						else :
							$googletag = 'div-gpt-ad-1394579228932-1';
						endif; ?>
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="<?php echo $googletag; ?>">
							<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('<?php echo $googletag; ?>'); });
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
			if ( !empty( $top ) && $top !== 'None' ) :
				$top_art = new WP_query( [ 'p' => $top ] );
				$cat_args['posts_per_page'] = 14;
				$cat_args['post__not_in'] = [ $top ];
				if ( $top_art->have_posts() ) :
					while ( $top_art->have_posts() ) : $top_art->the_post();
						get_template_part( 'content', get_post_type() );
					endwhile;
					$post_num = 14;
				endif;
				wp_reset_query();
			endif;
			$cat = new WP_query( $cat_args );
			if ( $cat->have_posts() ) :
				while ( $cat->have_posts() ) : $cat->the_post();
					get_template_part( 'content', get_post_type() );
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
							<span class="fa fa-play" id="play-button"></span>
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
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>