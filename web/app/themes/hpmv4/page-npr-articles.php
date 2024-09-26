<?php
/*
Template Name: NPR Content
*/
	$npr_id = 0;
	if ( isset( $wp_query->query_vars['npr_id'] ) ) {
		$npr_id = urldecode( $wp_query->query_vars['npr_id'] );
	}
	$nprdata = hpm_pull_npr_story( $npr_id );
	if ( empty( $nprdata['title'] ) ) {
		status_header(404);
		include( get_404_template() );
		exit;
	}

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
						<div class="byline-date-text">
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
						<?php hpm_article_share( $nprdata ); ?>
					</div>
				</header>
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
				<div class="row">
					<div class="col-12 news-list-right most-view">
						<h2 class="title title-full">
							<strong>Related</strong>
						</h2>
						<ul class="list-none news-links list-dashed">
				<?php
					foreach ( $nprdata['related'] as $related ) { ?>
							<li>
								<a href="<?php echo $related['link']; ?>" rel="bookmark" target="_blank"><span><?PHP echo $related['text']; ?></span></a>
							</li>
				<?php
					} ?>
						</ul>
					</div>
				</div>
			<?php
				}
				get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		</main>
	</div>
<?php get_footer(); ?>