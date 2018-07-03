<?php
/*
Template Name: Main Categories
*/
if ( !empty( $_GET ) ) :
	if ( !empty( $_GET['q'] ) ) :
		$q = $_GET['q'];
		if ( preg_match( '/^source/', $q ) || preg_match( '/^by/', $q ) ) :
			$q_a = str_replace( array('source:','by','+'),array('','',' '),$q );
			$q_a = sanitize_title($q_a);
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: //www.houstonpublicmedia.org/articles/author/'.$q_a);
			exit;
		elseif ( preg_match( '/^tag/', $q ) ) :
			$tag = str_replace( array('tag:',' '),array('','-'),$q );
			$tag = sanitize_title($tag);
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: //www.houstonpublicmedia.org/tag/'.$tag);
			exit;
		elseif ( preg_match( '/^category/', $q ) ) :
			$tag = str_replace( array('category:',' '),array('','-'),$q );
			$tag = sanitize_title($tag);
			header("HTTP/1.1 301 Moved Permanently");
			header('Location: //www.houstonpublicmedia.org/tag/'.$tag);
			exit;
		endif;
	endif;
endif;

get_header(); ?>
	<div id="primary" class="content-area">
<?php
				$c = 0;
				$exclude = array();
				if ( have_posts() ) :
					the_post(); 
					$main_cat = $wp_query->query_vars['pagename']; ?>
		<main id="main" class="site-main <?php echo $main_cat; ?>" role="main">
			
			<header class="page-header">
				<h1 class="page-title"><?php the_title(); ?></h1>
			</header>
			<div id="float-wrap">
				<div class="grid-sizer"></div>
			<?php
				endif;
				if ( $main_cat == 'education' ) :
					$main_cat .= '-news';
				endif;
				if ( $main_cat == 'arts-culture' ) :
					$hpm_priority = get_option( 'hpm_priority' );
					$stickies = array(
		                'ids' => array(),
		                'spaces' => array()
		            );
					if ( !empty( $hpm_priority['arts']['top'] ) ) :
						$stickies['ids'][] = $hpm_priority['arts']['top'];
					    $stickies['spaces'][$hpm_priority['arts']['top']] = 'felix-type-a';
					endif;
					if ( !empty( $hpm_priority['arts']['bottom'] ) ) :
						$stickies['ids'][] = $hpm_priority['arts']['bottom'];
						$stickies['spaces'][$hpm_priority['arts']['bottom']] = 'felix-type-b';
					endif;
					if ( !empty( $stickies['ids'] ) ) :
						$sticky_args = array(
							'posts_per_page' => 2,
							'post__in'  => $stickies['ids'],
							'orderby' => 'post__in',
							'ignore_sticky_posts' => 1
						);
						$sticky_query = new WP_Query( $sticky_args );
						if ( $sticky_query->have_posts() ) :
							while ( $sticky_query->have_posts() ) :
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
		                        elseif ( $stickies['spaces'][$sticky_id] == 'felix-type-b' ) :
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
				endif; ?>
			<div id="top-schedule-wrap" class="column-right stamp grid-item">
				<nav id="category-navigation" class="category-navigation" role="navigation">
					<h4><?php echo str_replace('-news','',$main_cat); ?> Features and Series</h4>
					<?php
						if ( $main_cat == 'news' ) :
							$nav_id = 2184;
						elseif ( $main_cat == 'arts-culture' ) :
							$nav_id = 2185;
						elseif ( $main_cat == 'education-news' ) :
							$nav_id = 2186;
						endif;
						wp_nav_menu( array(
							'menu_class' => 'nav-menu',
							'menu' => $nav_id
						) );
					?>
				</nav>
                <div class="sidebar-ad">
                    <div id="div-gpt-ad-1394579228932-1">
                        <h4>Support Comes From</h4>
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
                        </script>
                    </div>
                </div>
				<?php
						$pod = new WP_Query( array(
								'post_type' => 'podcasts',
								'tag' => str_replace('-news','',$main_cat)
							)
						);
						if ( $pod->have_posts() ) : ?>
				<div class="podcasts">
					<h4><?php echo str_replace('-news','',$main_cat); ?> Podcasts</h4>
						<?php
							while ( $pod->have_posts() ) :
								$pod->the_post();
								$postClass = get_post_class();
								$postClass = implode( ' ', $postClass );
								$postClass = str_replace( ' felix-type-d', '', $postClass );
								$pod_link = get_post_meta( get_the_ID(), 'hpm_pod_link', true ); ?>
					<article id="post-<?php the_ID(); ?>" class="<?php echo $postClass; ?>">
						<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
							<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
						</div>
						<header class="entry-header">
							<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( $pod_link['page'] ) ), '</a></h2>' ); ?>
						</header><!-- .entry-header -->
					</article>
							<?php
								endwhile; ?>
				</div>
						<?php
							endif; ?>
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
				if ( $main_cat == 'education-news' ) :
					$main_cat_pull = 'education-news,texas-originals,uh-moment';
				else :
					$main_cat_pull = $main_cat;
				endif;
				$args = array(
					'category_name' => $main_cat_pull,
					'post_type' => 'post',
					'post_status' => 'publish',
					'category__not_in' => 0,
					'ignore_sticky_posts' => 1,
					'posts_per_page' => 21
				);
				
				$orig_post = $post;
				global $post;
				$q = new WP_Query( $args );
				while ( $q->have_posts() ) :
					$q->the_post();
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
						if ( in_array( 'has-post-thumbnail', $postClass ) ) : ?>
					<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
						<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
					</div>
					<?php
						endif;
					?>
					<header class="entry-header">
						<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header><!-- .entry-header -->
				</article>
			<?php
						$c++;
					endif;
				endwhile;
				$post = $orig_post;
				wp_reset_query();
			?>
			</div><!-- #float-wrap -->
			<div class="readmore">
				<a href="//www.houstonpublicmedia.org/topics/<?php echo $main_cat; ?>/page/2">View More <?PHP the_title(); ?></a>
			</div>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>