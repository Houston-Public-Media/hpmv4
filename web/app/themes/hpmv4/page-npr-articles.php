<?php
/*
Template Name: NPR Content
*/
	if ( isset( $wp_query->query_vars['npr_id'] ) ) {
		$npr_id = urldecode( $wp_query->query_vars['npr_id'] );
	}
	$nprdata = hpm_pull_npr_story( $npr_id );
	get_header(); ?>
	<style>
		article .entry-content .fullattribution img { max-width: 1px; max-height: 1px; }
	</style>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
				<header class="entry-header">
					<?php echo hpm_pub_time_banner( strtotime( $nprdata['date'] ) ); ?>
					<h3><?php echo $nprdata['slug']; ?></h3>
					<h1 class="entry-title"><?php echo $nprdata['title']; ?></h1>
					<p><?php echo $nprdata['excerpt']; ?></p>
					<div class="byline-date">
					<?PHP
						foreach ( $nprdata['bylines'] as $k => $byline ) {
							if ( $k > 0 ) {
								echo ' / ';
							}
							echo '<address class="vcard author">';
							$bl = $byline['name'];
							if ( !empty( $byline['link'] ) ) {
								$bl = '<a href="' . $byline['link'] . '">' . $bl . '</a>';
							}
							echo $bl;
							echo '</address>';
						}
						echo " | ";
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

						$time_string = sprintf( $time_string,
							esc_attr( date( 'c', strtotime( $nprdata['date'] ) ) ),
							$nprdata['date']
						);

						printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x( 'Posted on', 'Used before publish date.', 'hpmv4' ),
							$time_string
						);
					?>
					</div>
				</header>
				<?php hpm_article_share( $nprdata ); ?>
				<div class="entry-content">
					<?php echo do_shortcode( $nprdata['body'] ); ?>
				</div>
				<footer class="entry-footer">
					<div class="tags-links">
						<span class="screen-reader-text">Tags </span>
						<?php echo implode( ' ', $nprdata['keywords_html'] ); ?>
					</div>
				</footer>
			</article>
			<aside class="column-right">
			<?php
				if ( !empty( $nprdata['related'] ) ) { ?>
				<section class="highlights">
					<h4>Related</h4>
					<ul>
				<?php
					foreach ( $nprdata['related'] as $related ) { ?>
						<li><h2 class="entry-title"><a href="<?php echo $related['link']; ?>" rel="bookmark" target="_blank"><?PHP echo $related['text']; ?></a></h2></li>
				<?php
					} ?>
					</ul>
				</section>
			<?php
				}
				get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>