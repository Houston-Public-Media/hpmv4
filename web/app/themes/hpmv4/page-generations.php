<?php
/*
Template Name: Generations on the Rise
*/
get_header(); ?>
	<link rel="stylesheet" href="https://use.typekit.net/gsg7chk.css">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP
			while ( have_posts() ) :
				the_post();
				$extitle = explode( ': ', get_the_title() ); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<div id="generations-logo">
							<img src="https://cdn.hpm.io/assets/images/genrise_logo2x.png" alt="Generations on the Rise by Houston Public Media, in partnership with Houston First" />
						</div>
						<h1 class="page-title"><?php echo $extitle[1]; ?></h1>
						<div id="generations-orgs">
							In Partnership With<br />
							<a href="https://www.houstonfirst.com/"><img src="https://cdn.hpm.io/assets/images/houstonfirst_white2x.png" alt="Houston First" id="generations-houstonfirst" /></a>
						</div>
					</header><!-- .entry-header -->
					<div class="page-content">
						<?php echo get_the_content(); ?>
					</div><!-- .entry-content -->

					<footer class="page-footer">
						<?PHP
						$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
						if ( $tags_list ) {
							printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
								_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
								$tags_list
							);
						}
						edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-footer -->
				</article><!-- #post-## -->
		<?php
			endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>