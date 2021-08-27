<?php
/*
Template Name: Main Categories
*/
	if ( !empty( $_GET ) && !empty( $_GET['q'] ) ) :
		$q = $_GET['q'];
		if ( preg_match( '/^source/', $q ) || preg_match( '/^by/', $q ) ) :
			$q_a = str_replace( [ 'source:', 'by', '+' ], [ '', '', ' ' ], $q );
			$q_a = sanitize_title( $q_a );
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: /articles/author/' . $q_a );
			exit;
		elseif ( preg_match( '/^tag/', $q ) ) :
			$tag = str_replace( [ 'tag:',' ' ], [ '', '-' ], $q );
			$tag = sanitize_title( $tag );
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: /tag/' . $tag );
			exit;
		elseif ( preg_match( '/^category/', $q ) ) :
			$tag = str_replace( [ 'category:', ' ' ], [ '', '-' ], $q );
			$tag = sanitize_title( $tag );
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: /tag/' . $tag );
			exit;
		endif;
	endif;
	get_header();
	$main_cat = $wp_query->query_vars['pagename'];
	$main_cat_name = $main_cat;
	$main_cat_pull = $main_cat;
	if ( $main_cat == 'education' ) :
		$main_cat_name .= '-news';
		$main_cat_pull = 'education-news,education,uh-moment';
	endif;

	$cats = new WP_Query( [
		'category_name' => $main_cat_pull,
		'post_type' => 'post',
		'post_status' => 'publish',
		'category__not_in' => 0,
		'ignore_sticky_posts' => 1,
		'posts_per_page' => 20
	] );
	if ( $cats->have_posts() ) :
		foreach ( $cats->posts as $wpp ) :
			$articles[] = $wpp;
		endforeach;
	endif;

	$pod = new WP_Query( [
		'post_type' => 'podcasts',
		'post_status' => 'publish',
		'tag' => $main_cat
	]);
	if ( have_posts() ) : the_post(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main" style="background-color: transparent;">
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>
<?php
	endif; ?>
			<section>
<?php
	foreach ( $articles as $ka => $va ) :
		if ( $ka == 4 ) : ?>
			</section>
			<aside>
				<nav id="category-navigation" class="category-navigation highlights" role="navigation">
					<h4><?php echo $main_cat; ?> Features and Series</h4>
					<?php
						if ( $main_cat_name == 'news' ) :
							$nav_id = 2184;
						elseif ( $main_cat_name == 'arts-culture' ) :
							$nav_id = 2185;
						elseif ( $main_cat_name == 'education-news' ) :
							$nav_id = 2186;
						endif;
						wp_nav_menu( array(
							'menu_class' => 'nav-menu',
							'menu' => $nav_id
						) );
					?>
				</nav>
                <section class="sidebar-ad">
					<h4>Support Comes From</h4>
                    <div id="div-gpt-ad-1394579228932-1">
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                        </script>
                    </div>
				</section>
			<?php if ( $pod->have_posts() ) : ?>
				<section class="highlights podcasts">
					<h4><?php echo $main_cat; ?> Podcasts</h4>
				<?php
					while ( $pod->have_posts() ) : $pod->the_post();
						get_template_part( 'content', 'podcasts' );
					endwhile; ?>
				</section>
			<?php
				endif;
				wp_reset_query(); ?>
				<section class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-2">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
						</script>
					</div>
				</section>
			</aside>
			<section>
<?php
		endif;
		$post = $va;
		get_template_part( 'content', get_post_format() );
	endforeach;
	wp_reset_query(); ?>
			</section>
			<div class="readmore">
				<a href="/topics/<?php echo $main_cat; ?>/page/2">View More <?PHP the_title(); ?></a>
			</div>
		</main>
	</div>
<?php get_footer(); ?>