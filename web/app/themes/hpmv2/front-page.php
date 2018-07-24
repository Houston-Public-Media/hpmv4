<?php
/**
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */
get_header();
$exclude = array();
$c = 0;
$t = time();
$offset = get_option('gmt_offset')*3600;
$t = $t + $offset;
$now = getdate($t);
if ( !empty( $_GET['testtime'] ) ) :
	$tt = explode( '-', $_GET['testtime'] );
	$now = getdate( mktime( $tt[0], $tt[1], 0, $tt[2], $tt[3], $tt[4] ) );
endif; ?>
	<div id="primary" class="content-area">
<?php /*
		$election_args = array(
			'p' => 248126,
			'post_type'  => 'page',
			'post_status' => 'publish'
		);
		$election = new WP_Query( $election_args );
		if ( $election->have_posts() ) :
			while ( $election->have_posts() ) :
				$election->the_post();
				the_content();
			endwhile;
			wp_reset_postdata();
		endif; */ ?>
		<main id="main" class="site-main" role="main">
			<div id="float-wrap">
				<div class="grid-sizer"></div>
				
		<?php
			// Sticky Posts
			$hpm_priority = get_option( 'hpm_priority' );
			$stickies = array(
				'ids' => array(),
				'spaces' => array()
			);
			$sticky = 'homepage';
			foreach ( $hpm_priority[$sticky] as $ks => $vs ) :
				if ( $ks == 0 ) :
					$stickies['spaces'][$vs] = 'felix-type-a';
				elseif ( $ks == 1 ) :
					$stickies['spaces'][$vs] = 'felix-type-b';
				else :
					$stickies['spaces'][$vs] = 'felix-type-d';
				endif;
				$stickies['ids'][] = $vs;
			endforeach;
			if ( !empty( $stickies['ids'] ) ) :
				$sticknum = count( $stickies['ids'] );
				$sticky_args = array(
					'posts_per_page' => $sticknum,
					'post__in'  => $stickies['ids'],
					'orderby' => 'post__in',
					'ignore_sticky_posts' => 1
				);
				$sticky_query = new WP_Query( $sticky_args );
				if ( $sticky_query->have_posts() ) :
					while ( $sticky_query->have_posts() ) :
						if ( $c == 4 ) : ?>
				<div id="top-schedule-wrap" class="column-right grid-item stamp">
					<div id="station-schedules">
						<h4>ON AIR</h4>
						<div class="station-now-play-wrap">
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8</a></h5>
								<?PHP echo hpmv2_nowplaying('tv8.1'); ?>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.2 (Create)</a></h5>
								<?PHP echo hpmv2_nowplaying('tv8.2'); ?>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.3 (PBS Kids)</a></h5>
								<?PHP echo hpmv2_nowplaying('tv8.3'); ?>
							</div>
							<div class="station-now-play">
								<h5><a href="/tv8">TV 8.4 (World)</a></h5>
								<?PHP echo hpmv2_nowplaying('tv8.4'); ?>
							</div>
						</div>
						<div class="station-now-play-wrap">
							<div class="station-now-play">
								<h5><a href="/news887">News 88.7</a></h5>
								<?PHP echo hpmv2_nowplaying('news887'); ?>
							</div>
							<div class="station-now-play">
								<h5><a href="/classical">Classical</a></h5>
								<?PHP echo hpmv2_nowplaying('classical'); ?>
							</div>
							<div class="station-now-play">
								<h5><a href="/mixtape">Mixtape</a></h5>
								<div id="mixtape"></div>
								<script>
									var getJSON = function(url, callback) {
										var xhr = new XMLHttpRequest();
										xhr.open('GET', url, true);
										xhr.responseType = 'json';
										xhr.onload = function() {
											var status = xhr.status;
											if (status === 200) {
												callback(null, xhr.response);
											} else {
												callback(status, xhr.response);
											}
										};
										xhr.send();
									};
									function update() {
										getJSON( "https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/mixtape.json",
											function(err, data) {
												if (err !== null) {
													console.log(err);
												} else {
													document.getElementById('mixtape').innerHTML = '<h3>'+data[0]+' - '+data[1]+'</h3><p>Album: '+data[2]+'</p>';
													masonLoad();
												}
											}
										);
									}
									document.addEventListener("DOMContentLoaded", function() {
										update();
										setInterval("update()", 60000);
									});
								</script>
							</div>
						</div>
					</div>
					<div id="in-depth">
						<h4>News 88.7 In-Depth</h4>
						<?php
							$indepth = array(
								'posts_per_page' => 1,
								'cat' => 29328,
								'ignore_sticky_posts' => 1
							);
							$indepth_query = new WP_Query( $indepth );
							if ( $indepth_query->have_posts() ) :
								while ( $indepth_query->have_posts() ) : $indepth_query->the_post();
									$postClass = get_post_class();
									$search = 'felix-type-';
									$felix_type = array_filter($postClass, function($el) use ($search) {
										return ( strpos($el, $search) !== false );
									});
									if ( !empty( $felix_type ) ) :
										$key = array_keys( $felix_type );
										unset( $postClass[$key[0]] );
									endif; ?>
									<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
										<?php
										if ( has_post_thumbnail() ) : ?>
											<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
												<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
											</div>
										<?php
										endif; ?>
										<header class="entry-header">
										<?php
											the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
											the_excerpt(); ?>
											<div class="screen-reader-text">
											<?php
												coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true );
												$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

												$time_string = sprintf( $time_string,
													esc_attr( get_the_date( 'c' ) ),
													get_the_date( 'F j, Y' )
												);

												printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
													_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
													$time_string
												); ?>
											</div>
										</header><!-- .entry-header -->
									</article>
						<?php
								endwhile;
							endif;
							wp_reset_query();
						?>
					</div>
					<?php hpm_top_posts(); ?>
					<?php //echo get_option( 'hpm_houstonpubmedia_tweets' ); ?>
					<div class="sidebar-ad">
						<div id="div-gpt-ad-1394579228932-1">
							<h4>Support Comes From</h4>
							<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
							</script>
						</div>
					</div>
				</div>
<?php
						endif;
						$sticky_query->the_post();
						$sticky_id = get_the_ID();
						$exclude[] = $sticky_id;
						$postClass = get_post_class();
						$postClass[] = 'pinned';
						$postClass[] = 'grid-item';
						$postClass[] = 'grid-item--width2';
						$fl_array = preg_grep("/felix-type-/", $postClass);
						$fl_arr = array_keys( $fl_array );
						$postClass[$fl_arr[0]] = $stickies['spaces'][$sticky_id];

						if ( $stickies['spaces'][$sticky_id] == 'felix-type-a' ) :
							$thumbnail_type = 'large';
						else :
							$thumbnail_type = 'thumbnail';
						endif; ?>
						<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
							<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url($thumbnail_type); ?>)">
								<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
							</div>
							<header class="entry-header">
								<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
								<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								<div class="screen-reader-text"><?PHP coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true ); ?> </div>
							</header><!-- .entry-header -->
						</article>
						<?PHP
						$c++;
					endwhile;
					wp_reset_postdata();
				endif;
			endif;
			while ( have_posts() ) :
				if ($c == 12) : ?>
				<div id="npr-side" class="column-right grid-item stamp">
					<div id="national-news">
						<h4>News from NPR</h4>
						<?php echo hpm_nprapi_output(); ?>
					</div>
					<div class="sidebar-ad">
						<div id="div-gpt-ad-1394579228932-2">
							<h4>Support Comes From</h4>
							<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
							</script>
						</div>
					</div>
				</div>
			<?php
					$c++;
				endif;
				the_post();
				if ( !in_array( get_the_ID(), $exclude ) ) :
					$postClass = get_post_class();
					$postClass[] = 'grid-item';
					$search = 'felix-type-';
					$felix_type = array_filter($postClass, function($el) use ($search) {
						return ( strpos($el, $search) !== false );
					});
					if ( !empty( $felix_type ) ) :
						$key = array_keys( $felix_type );
						$postClass[$key[0]] = 'felix-type-d';
					else :
						$postClass[] = 'felix-type-d';
					endif; ?>
				<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
					<?php
						if ( has_post_thumbnail() ) : ?>
					<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
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
				endif;
				$c++;
			endwhile;
			?>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>