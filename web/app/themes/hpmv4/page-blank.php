<?php
/*
Template Name: Blank Wrapper
*/
	get_header(); ?>
	<style>
		.page.page-template-page-blank #content {
			width: 100%;
			max-width: 100%;
			margin: 0;
		}
		body.page.page-template-page-blank {
			background-color: white;
		}
		@media screen and (min-width: 52.5em) {
			.page.page-template-page-blank #main > article {
				margin: 0;
				float: none;
				width: 100%;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) {
				the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
				<div class="entry-content">
					<?php
						if ( has_post_thumbnail() ) {
					?>
					<div class="post-thumbnail">
						<?php
							the_post_thumbnail( 'hpm-large' );
							$thumb_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
							if ( !empty( $thumb_caption ) ) {
								echo "<p>" . $thumb_caption . "</p>";
							}
						?>
					</div>
					<?PHP
						}
						the_content();
					?>
				</div>
				<footer class="entry-footer">
				<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv4' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
		<?php } ?>
		</main>
	</div>
<?php get_footer(); ?>