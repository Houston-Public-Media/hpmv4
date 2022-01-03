<?php

/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage HPMv2
 * @since HPMv2 1.0
 */

get_header();
if ( is_preview() ) : ?>
	<div id="preview-warn">You're viewing a preview. Some things might be a little squirrelly. --The Management</div>
<?php endif; ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
<?PHP while ( have_posts() ) : the_post(); $postClass = get_post_class(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
			<?php echo ( in_array( 'category-in-depth', $postClass ) ? '<a href="/topics/in-depth/" class="indepth"><img src="https://cdn.hpm.io/assets/images/inDepth-logo-300.png" alt="News 88.7 inDepth" /></a>' : '' ); ?>
				<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
				<?php
				the_title( '<h1 class="entry-title">', '</h1>' );
				the_excerpt();
				$single_id = get_the_ID(); ?>
				<div class="byline-date">
					<?PHP
					coauthors_posts_links(' / ', ' / ', '<address class="vcard author">', '</address>', true);
					echo " | ";
					$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
					$pub = get_the_time('U');
					$mod = get_the_modified_time('U');
					$desc = $mod - $pub;
					$mod_time = get_post_meta($single_id, 'hpm_no_mod_time', true);
					if ($pub !== $mod && $desc > 900 && $mod > $pub && $mod_time == 0) :
						$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> (Last Updated: <time class="updated" datetime="%3$s">%4$s</time>)';
					endif;

					$time_string = sprintf(
						$time_string,
						esc_attr(get_the_date('c')),
						get_the_date(),
						esc_attr(get_the_modified_date('c')),
						get_the_modified_date()
					);

					printf(
						'<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
						_x('Posted on', 'Used before publish date.', 'hpmv2'),
						$time_string
					);
					?>
				</div>
			</header>
			<?php hpm_article_share(); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<footer class="entry-footer">
				<div class="tags-links">
				<?PHP
					$cat_list = get_the_category_list(' ', _x(' ', 'Used between list items, there is a space after the comma.', 'hpmv2'));
					if ($cat_list) :
						echo $cat_list;
					endif;
					$tags_list = get_the_tag_list('', _x(' ', 'Used between list items, there is a space after the comma.', 'hpmv2'));
					if ($tags_list) :
						echo $tags_list;
					endif;
					edit_post_link(__('Edit', 'hpmv2'), '<span class="edit-link">', '</span>');
				?>
				</div>
				<div id="author-wrap"><?php echo author_footer( get_the_ID() ); ?></div>
			</footer>
		</article>
<?php endwhile; ?>
		<aside>
			<?php
			$categories = get_the_category( get_the_ID() );
			foreach ( $categories as $cats ) :
				$anc = get_ancestors( $cats->term_id, 'category' );
				if ( in_array( 9, $anc ) ) :
					$series = new WP_query( [
						'cat' => $cats->term_id,
						'orderby' => 'date',
						'order'   => 'ASC',
						'posts_per_page' => 7,
						'post__not_in' => get_the_ID()
					]);
					if ( $series->have_posts() ) :
						$series_page = new WP_query( [ 'meta_key' => 'hpm_series_cat', 'meta_value' => $cats->term_id, 'post_type' => 'page' ] );
						if ( $series_page->have_posts() ) :
							while ( $series_page->have_posts() ) :
								$series_page->the_post();
								$series_link = get_the_permalink();
							endwhile;
							wp_reset_postdata();
						else :
							$series_link = "/topics/" . $cats->slug;
						endif; ?>
						<section class="highlights">
							<h4><a href="<?php echo $series_link; ?>">More from <?php echo $cats->cat_name; ?></a></h4>
							<?php
							while ($series->have_posts()) :
								$series->the_post();
								get_template_part( 'content', get_post_format() );
							endwhile; ?>
						</section>
			<?php
					endif;
				elseif ( $cats->term_id == 12 ) : ?>
					<div class="sidebar-ad">
						<h4>Support Comes From</h4>
						<p><a href="https://www.texasmutual.com/employers/pr/2021-dividends?utm_source=Banner&utm_medium=Website&utm_campaign=Bauer"><img src="https://cdn.hpm.io/assets/images/FinalOTJ_300x250.gif" alt="We are on the job with them. Helping keep Texas strong. Texas Mutual Workers Compensation Insurance." /></a></p>
					</div>
			<?php
				endif;
			endforeach;
			wp_reset_postdata();
			get_template_part('sidebar', 'none'); ?>
		</aside>
	</main>
</div>
<?php get_footer(); ?>