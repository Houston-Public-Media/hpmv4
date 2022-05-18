<?php
/*
Template Name: Default Show
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
			$show_id = get_the_ID();
			$show = get_post_meta( $show_id, 'hpm_show_meta', true );
			$show_title = get_the_title();
			$show_content = get_the_content();
			$episodes = HPM_Podcasts::list_episodes( $show_id );
			echo HPM_Podcasts::show_header( $show_id );
			endwhile; ?>
			<div id="float-wrap">
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
			if ( !empty( $top ) && $top !== 'None' ) :
				$top_art = new WP_query( [ 'p' => $top ] );
				$cat_args['posts_per_page'] = 14;
				$cat_args['post__not_in'] = [ $top ];
				if ( $top_art->have_posts() ) :
					while ( $top_art->have_posts() ) : $top_art->the_post();
						$postClass = get_post_class();
						$fl_array = preg_grep("/felix-type-/", $postClass);
						$fl_arr = array_keys( $fl_array );
						if ( has_post_thumbnail() ) :
							$postClass[$fl_arr[0]] = 'felix-type-a';
						else :
							$postClass[$fl_arr[0]] = 'felix-type-b';
						endif;
						$thumbnail_type = 'large'; ?>
						<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
							<?php
							if ( has_post_thumbnail() ) : ?>
								<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url($thumbnail_type); ?>)">
									<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
								</div>
							<?php
							endif; ?>
							<header class="entry-header">
								<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
								<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
							</header><!-- .entry-header -->
						</article>
					<?PHP
					endwhile;
					$post_num = 14;
				endif;
				wp_reset_query();
			endif;
			$cat = new WP_query( $cat_args );
			if ( $cat->have_posts() ) :
				while ( $cat->have_posts() ) : $cat->the_post();
					$postClass = get_post_class();
					$fl_array = preg_grep("/felix-type-/", $postClass);
					$fl_arr = array_keys( $fl_array );
					if ( $cat->current_post == 0 && empty( $top_art ) ) :
						if ( has_post_thumbnail() ) :
							$postClass[$fl_arr[0]] = 'felix-type-a';
						else :
							$postClass[$fl_arr[0]] = 'felix-type-b';
						endif;
					else :
						$postClass[$fl_arr[0]] = 'felix-type-d';
					endif;
					if ( in_array( 'felix-type-a', $postClass ) ) :
						$thumbnail_type = 'large';
					else :
						$thumbnail_type = 'thumbnail';
					endif; ?>
					<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
						<?php
						if ( has_post_thumbnail() ) : ?>
							<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url($thumbnail_type); ?>)">
								<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
							</div>
						<?php
						endif; ?>
						<header class="entry-header">
							<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
						</header><!-- .entry-header -->
					</article>
				<?PHP
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
							<span class="fas fa-play" id="play-button"></span>
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