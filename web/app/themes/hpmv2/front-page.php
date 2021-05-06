<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
get_header();
$articles = hpm_homepage_articles(); ?>
	<div id="primary" class="content-area">
	<?php election_homepage(); ?>
		<main id="main" class="site-main" role="main">
			<div id="float-wrap">
				<div class="article-wrap">
<?php
	$artnum = count( $articles );
	if ( $artnum % 2 !== 0 ) :
		unset( $articles[ $artnum - 1 ] );
	endif;
	foreach ( $articles as $ka => $va ) :
		if ( $ka == 4 ) : ?>
				</div>
				<div id="top-schedule-wrap" class="column-right">
					<div id="station-schedules">
						<h4>ON AIR</h4>
						<div class="station-now-play-wrap">
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8</a></h5>
								<div class="nowplay-tv81"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.2 (Create)</a></h5>
								<div class="nowplay-tv82"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.3 (PBS Kids)</a></h5>
								<div class="nowplay-tv83"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.4 (World)</a></h5>
								<div class="nowplay-tv84"></div>
							</div>
						</div>
						<div class="station-now-play-wrap">
							<div class="station-now-play">
								<h5><a href="/news887">News 88.7</a></h5>
								<div class="nowplay-news"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/classical">Classical</a></h5>
								<div class="nowplay-classical"></div>
							</div>
							<div class="station-now-play">
								<h5><a href="/mixtape">Mixtape</a></h5>
								<div class="nowplay-mixtape"></div>
							</div>
						</div>
						<script>hpmNowPlaying('all',false);</script>
					</div>
					<div id="in-depth">
						<h4>News 88.7 In-Depth</h4>
						<?php hpm_priority_indepth(); ?>
					</div>
					<?php hpm_top_posts(); ?>
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-1">
							<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });</script>
						</div>
					</div>
				</div>
				<div class="article-wrap">
<?php
		elseif ( $ka == 13 ) : ?>
				</div>
				<div id="npr-side" class="column-right">
					<div id="national-news">
						<h4>News from NPR</h4>
						<?php echo hpm_nprapi_output(); ?>
					</div>
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<div id="div-gpt-ad-1394579228932-2">
							<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });</script>
						</div>
					</div>
				</div>
				<div class="article-wrap">
<?php
		else :
			$postClass = get_post_class( $va->ID );
			$search = 'felix-type-';
			$felix_type = array_filter($postClass, function($el) use ($search) {
				return ( strpos($el, $search) !== false );
			});
			$felix = 'felix-type-d';
			if ( $ka == 0 ) :
				$felix = 'felix-type-a';
			elseif ( $ka == 1 ) :
				$felix = 'felix-type-b';
			endif;
			if ( !empty( $felix_type ) ) :
				$key = array_keys( $felix_type );
				$postClass[$key[0]] = $felix;
			else :
				$postClass[] = $felix;
			endif; ?>
				<article id="post-<?php echo $va->ID; ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
					<?php
						if ( has_post_thumbnail( $va->ID ) ) : ?>
					<div class="thumbnail-wrap" style="background-image: url(<?php echo get_the_post_thumbnail_url( $va->ID, 'thumbnail' ); ?>)">
						<a class="post-thumbnail" href="<?php the_permalink( $va->ID ); ?>" aria-hidden="true"></a>
					</div>
					<?php
						endif; ?>
					<header class="entry-header">
						<h3><?php echo hpm_top_cat( $va->ID ); ?></h3>
						<h2 class="entry-title"><a href="<?php the_permalink( $va->ID ); ?>" rel="bookmark"><?php echo $va->post_title; ?></a></h2>
						<div class="screen-reader-text">
							<?PHP
								coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true );
								echo '<p>' . get_the_excerpt( $va->ID ) . '</p>';
							?>
						</div>
					</header>
				</article>
<?PHP
		endif;
	endforeach; ?>
				</div>
			</div>
		</main>
	</div>
<?php get_footer(); ?>