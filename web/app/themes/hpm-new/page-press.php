<?php
/*
Template Name: Press Room
*/

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header column-left">
				<h1 class="page-title" style="margin-bottom: 0.5em;"><?php the_title(); ?></h1>
				<p>The latest press releases and information about Houston Public Media.</p>
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
			<aside class="column-right clear">
				<div class="sidebar-ad">
					<h4>Support Comes From</h4>
					<div id="div-gpt-ad-1394579228932-1">
						<script type='text/javascript'>
							googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-1'); });
						</script>
					</div>
				</div>
			</aside>
			<aside class="column-right clear">
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
            <aside class="column-right clear">
                <div class="sidebar-ad">
					<h4>Support Comes From</h4>
                    <div id="div-gpt-ad-1394579228932-2">
                        <script type='text/javascript'>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1394579228932-2'); });
                        </script>
                    </div>
                </div>
            </aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
