<?php
/*
Template Name: Election Night 2018
*/

get_header();
$embeds = get_post_meta( get_the_ID(), 'hpm_series_embeds', true );
$cat_no = get_post_meta( get_the_ID(), 'hpm_series_cat', true );
$terms = get_terms( array( 'include'  => $cat_no, 'taxonomy' => 'category' ) );
$term = reset( $terms );
$cat = new WP_query( array(
	'cat' => $cat_no,
	'orderby' => 'date',
	'order'   => 'DESC',
) );
$page_content = $wp_query->post->post_content;
echo $embeds['bottom']; ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="column-span">
			<header class="page-header">
				<h1 class="entry-title"><?php echo get_the_title(); ?></h1>
			</header><!-- .entry-header -->
<?PHP
	echo apply_filters( 'hpm_filter_text', $page_content ); /*?>
	<section id="search-results">
		<h2 class="election newsroom">Election 2018 Updates  <a class="jump" href="#content" title="Jump to Top"><i class="fa fa-arrow-up" aria-hidden="true"></i></a></h2>
<?php
	if ( $cat->have_posts() ) :
		while ( $cat->have_posts() ) : $cat->the_post();
			get_template_part( 'content', get_post_format() );
		endwhile;
	endif; ?>
				<div class="readmore">
					<a href="/topics/<?php echo $term->slug; ?>/page/2">View More <?php echo $term->name; ?></a>
				</div>
			</section><?php */ ?>
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
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>