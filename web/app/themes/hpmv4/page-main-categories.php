<?php
/*
Template Name: Main Categories
*/
if ( !empty( $_GET ) ) {
	if ( !empty( $_GET['q'] ) ) {
		$q = $_GET['q'];
		if ( str_contains( 'source', $q ) || str_contains( 'by', $q ) ) {
			$q_a = str_replace( [ 'source:', 'by', '+' ], [ '', '', ' '], $q );
			$q_a = sanitize_title( $q_a );
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: //www.houstonpublicmedia.org/articles/author/' . $q_a );
			exit;
		} elseif ( str_contains( 'tag', $q ) ) {
			$tag = str_replace( [ 'tag:', ' ' ], [ '', '-' ], $q );
			$tag = sanitize_title( $tag );
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: //www.houstonpublicmedia.org/tag/' . $tag );
			exit;
		} elseif ( str_contains( 'category', $q ) ) {
			$tag = str_replace( [ 'category:', ' ' ], [ '', '-' ], $q );
			$tag = sanitize_title( $tag );
			header( "HTTP/1.1 301 Moved Permanently" );
			header( 'Location: //www.houstonpublicmedia.org/tag/' . $tag );
			exit;
		}
	}
}

get_header(); ?>
	<style>
		.page.page-template-page-main-categories #main {
			background-color: transparent;
		}
		.page.page-template-page-main-categories #search-results {
			padding-top: 0px !important;
		}
	</style>
	<div id="primary" class="content-area">
<?php
				$c = 0;
				$exclude = [];
				if ( have_posts() ) {
					the_post();
					$main_cat = $wp_query->query_vars['pagename']; ?>
		<main id="main" class="site-main <?php echo $main_cat; ?>" role="main">
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>

			<?php
				}
				if ( $main_cat == 'education' ) {
					$main_cat .= '-news';
				} ?>
			<div id="top-schedule-wrap" class="column-right">
				<nav id="category-navigation" class="category-navigation" role="navigation">
					<h4><?php echo str_replace( '-news', '', $main_cat ); ?> Features and Series</h4>
					<?php
						if ( $main_cat == 'news' ) {
							$nav_id = 2184;
						} elseif ( $main_cat == 'arts-culture' ) {
							$nav_id = 2185;
						} elseif ( $main_cat == 'education-news' ) {
							$nav_id = 2186;
						}
						wp_nav_menu([
							'menu_class' => 'nav-menu',
							'menu' => $nav_id
						]);
					?>
				</nav>
                <div class="sidebar-ad">
					<h4>Support Comes From</h4>
                    <div id="div-gpt-ad-1394579228932-1">
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                        </script>
                    </div>
                </div>
				<?php
						$pod = new WP_Query([
							'post_type' => 'podcasts',
                            'tag__not_in' => [ 48498 ],
							'tag' => str_replace( '-news', '', $main_cat )
						]);
                        //print_r($pod);
						if ( $pod->have_posts() ) { ?>
				<div class="podcasts highlights">
					<h4><?php echo str_replace( '-news', '', $main_cat ); ?> Podcasts</h4>
						<?php
							while ( $pod->have_posts() ) {
								$pod->the_post();
								get_template_part( 'content', get_post_type() );
							} ?>
				</div>
						<?php
							} ?>
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-2">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
						</script>
					</div>
				</div>
			</div>
                <section id="search-results">
			<?php
				if ( $main_cat == 'education-news' ) {
					$main_cat_pull = 'education-news,texas-originals,uh-moment';
				} else {
					$main_cat_pull = $main_cat;
				}
				$args = [
					'category_name' => $main_cat_pull,
					'post_type' => 'post',
					'post_status' => 'publish',
					'category__not_in' => 0,
					'ignore_sticky_posts' => 1,
					'posts_per_page' => 20
				];

				$orig_post = $post;
				global $post;
				$q = new WP_Query( $args );
				$ka = 0;
				while ( $q->have_posts() ) {
					$q->the_post();
					if ( !in_array( get_the_ID(), $exclude ) ) {

						get_template_part( 'content', get_post_type() );
						$ka++;
						$c++;
					}
				}
				$post = $orig_post;
				wp_reset_query();
			?>
			</section>
			<div class="readmore">
				<a href="/topics/<?php echo $main_cat; ?>/page/2">View More <?PHP the_title(); ?></a>
			</div>
		</main>
	</div>
<?php get_footer(); ?>