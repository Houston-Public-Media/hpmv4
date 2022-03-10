<?php
/*
Template Name: NPR Content
*/
	if ( isset( $wp_query->query_vars['npr_id'] ) ) :
		$npr_id = urldecode( $wp_query->query_vars['npr_id'] );
	endif;
	$nprdata = hpm_pull_npr_story( $npr_id );
	get_header(); ?>
	<style>
		article .entry-content .fullattribution img { max-width: 1px; max-height: 1px; }
	</style>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
				<header class="entry-header">
					<h3><?php echo $nprdata['slug']; ?></h3>
					<h1 class="entry-title"><?php echo $nprdata['title']; ?></h1>
					<p><?php echo $nprdata['excerpt']; ?></p>
					<div class="byline-date">
					<?PHP
						foreach ( $nprdata['bylines'] as $k => $byline ) :
							if ( $k > 0 ) :
								echo ' / ';
							endif;
							echo '<address class="vcard author">';
							$bl = $byline['name'];
							if ( !empty( $byline['link'] ) ) :
								$bl = '<a href="' . $byline['link'] . '">' . $bl . '</a>';
							endif;
							echo $bl;
							echo '</address>';
						endforeach;
						echo " | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

						$time_string = sprintf( $time_string,
							esc_attr( date( 'c', strtotime( $nprdata['date'] ) ) ),
							$nprdata['date']
						);

						printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
							$time_string
						);
					?>
					</div>
				</header><!-- .entry-header -->
				<div class="entry-content">
					<?php
						if ( !empty( $nprdata['audio'] ) ) :
							echo do_shortcode( '[audio mp3="' . $nprdata['audio'][0] . '"][/audio]' );
						endif;
						echo $nprdata['body'];
						hpm_article_share( $nprdata );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-footer">
					<div class="tags-links">
						<span class="screen-reader-text">Tags </span>
						<?php echo implode( ' ', $nprdata['keywords_html'] ); ?>
					</div>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<aside class="column-right">
			<?php
				if ( !empty( $nprdata['related'] ) ) : ?>
				<div id="related-posts">
					<h4>Related</h4>
					<ul>
				<?php
					foreach ( $nprdata['related'] as $related ) : ?>
						<li><h2 class="entry-title"><a href="<?php echo $related['link']; ?>" rel="bookmark" target="_blank"><?PHP echo $related['text']; ?></a></h2></li>
				<?php
					endforeach; ?>
					</ul>
				</div>
			<?php
				endif;
				get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>