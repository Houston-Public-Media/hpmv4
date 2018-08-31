<?php
/*
Template Name: Election 2016
*/

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header column-left" style="padding: 0.5em;">
				<img src="https://cdn.hpm.io/assets/images/HPM_Election2016Banner.png" alt="Houston Public Media coverage of Election 2016" />
				<h1 class="page-title screen-reader-text"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->
			<div class="column-right page-content">
				<?PHP
					the_content( sprintf(
						__( 'Continue reading %s', 'hpmv2' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );
				?>
			</div>
<?php
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	if ( $now[0] > mktime( 19, 0, 0, 10, 19, 2016 ) && $now[0] < mktime( 12, 0, 0, 10, 20, 2016 ) ) : ?>
			<div class="column-left">
				<article class="post type-post status-publish format-standard has-post-thumbnail hentry category-election-2016 category-politics category-news felix-type-a">
					<div class="thumbnail-wrap" style="background-image: url(http://cdnmo.coveritlive.com/media/avitars/201610/phps2odndclintontrump.jpg)">
						<a class="post-thumbnail" href="//www.houstonpublicmedia.org/articles/news/2016/10/18/173674/txdecides-live-blog-the-final-presidential-debate" aria-hidden="true"></a>
					</div>
					<header class="entry-header">
						<h3>Election 2016</h3>
						<h2 class="entry-title"><a href="//www.houstonpublicmedia.org/articles/news/2016/10/18/173674/txdecides-live-blog-the-final-presidential-debate/" rel="bookmark">#TXDecides: Final Presidential Debate</a></h2>
					</header><!-- .entry-header -->
				</article>
			</div>
<?php
	endif;
				$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
				if ( !empty( $cat_no ) ) :
					$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
					$term = reset( $terms );
					$cat = new WP_query( array(
						'cat' => $cat_no,
						'orderby' => 'date',
						'order'   => 'DESC',
					) );
					if ( $cat->have_posts() ) : ?>
				<section id="search-results">
		<?php
						while ( $cat->have_posts() ) : $cat->the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
						wp_reset_postdata(); ?>
					<div class="readmore">
						<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
					</div>
				</section>
			<?php
					endif;
				endif; ?>
			<div id="npr-side" class="column-right">
				<div id="national-news">
					<h4>News from NPR</h4>
					<?PHP 
						$npr = file_get_contents("http://api.npr.org/query?id=139482413&fields=title,teaser,image,storyDate&requiredAssets=image,audio,text&startNum=0&dateType=story&output=JSON&numResults=4&apiKey=MDAyMTgwNzc5MDEyMjQ4ODE4MjMyYTExMA001");
						$npr_json = json_decode($npr,TRUE);
						foreach ($npr_json['list']['story'] as $story) :
							$npr_date = strtotime($story['storyDate']['$text']);  ?>
					<article class="national-content">
						<?php
							if ( !empty( $story['image'][0]['src'] ) ) : ?>
						<div class="national-image" style="background-image: url(<?PHP echo $story['image'][0]['src']; ?>)">
							<a href="//www.houstonpublicmedia.org/npr/<?PHP echo date('Y/m/d/',$npr_date).$story['id']."/".sanitize_title($story['title']['$text'])."/"; ?>" class="post-thumbnail"></a>
						</div>
						<div class="national-text">
						<?php
							else : ?>
						<div class="national-text-full">
						<?php
							endif; ?>
							<h2><a href="/npr/<?PHP echo date('Y/m/d/',$npr_date).$story['id']."/".sanitize_title($story['title']['$text'])."/"; ?>"><?PHP echo $story['title']['$text']; ?></a></h2>
							<p class="screen-reader-text"><?PHP echo $story['teaser']['$text']; ?></p>
						</div>
					</article>
					<?PHP
						endforeach; ?>
				</div>
			</div>
			<aside class="column-right">
				<div class="sidebar-ad">
					<div id="div-gpt-ad-1394579228932-1">
						<h4>Support Comes From</h4>
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
			</aside>
			<aside class="column-right">
			<?php
				$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true ); 
				if ( !empty( $embeds['twitter'] ) ) : ?>
				<section id="embeds">
				<?php
					if ( !empty( $embeds['twitter'] ) ) : ?>
					<h4>Twitter</h4>
					<?php 
						echo $embeds['twitter']; 
					endif; ?>
				</section>
			<?php
				endif; ?>
			</aside>
		<?php
			endwhile; ?>
            <aside class="column-right">
                <div class="sidebar-ad">
                    <div id="div-gpt-ad-1394579228932-2">
                        <h4>Support Comes From</h4>
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
                        </script>
                    </div>
                </div>
            </aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
