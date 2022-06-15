<?php
/*
Template Name: Strategic Plan
*/
get_header(); ?>
	<style>
		.page-template-page-strategic article {
			padding: 0;
		}
		.page-template-page-strategic article .entry-content {
			padding: 1.875em;
		}
		.page-template-page-strategic article .entry-header {
			padding: 0;
			overflow: hidden;
		}
		.page-template-page-strategic article .entry-header .entry-title {
			color: white;
			background-color: rgb(205,23,49);
			padding: 1em 1em 0.5em 1em;
			border-bottom: 1px solid white;
			font-family: var(--hpm-font-main);
			font-weight: 500;
		}
		.page-template-page-strategic article .entry-header .entry-title span {
			font-family: var(--hpm-font-main);
			font-weight: 100;
		}
		.page-template-page-strategic article .entry-header .plan-colorbar {
			height: 0.5em;
			float: left;
			margin: 0 !important;
		}
		.page-template-page-strategic article .entry-header .plan-colorbar:nth-child(2) {
			background-color: rgb(205,23,49);
			width: 10%;
		}
		.page-template-page-strategic article .entry-header .plan-colorbar:nth-child(3) {
			background-color: rgb(8,86,107);
			width: 25%;
		}
		.page-template-page-strategic article .entry-header .plan-colorbar:nth-child(4) {
			background-color: rgb(169,204,69);
			width: 45%;
		}
		.page-template-page-strategic article .entry-header .plan-colorbar:nth-child(5) {
			background-color: rgb(239,177,66);
			width: 20%;
		}
		@media screen and (min-width: 52.5em) {
			.page.page-template-page-strategic #main > article {
				width: 100%;
				float: none;
				border-right: 0;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php
						the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    <div class="plan-colorbar"></div>
                    <div class="plan-colorbar"></div>
                    <div class="plan-colorbar"></div>
                    <div class="plan-colorbar"></div>
				</header>
				<div class="entry-content">
					<?php
						if ( has_post_thumbnail() ) :
					?>
					<div class="post-thumbnail">
						<?php
							the_post_thumbnail( 'hpm-large' );
							$thumb_caption = get_post(get_post_thumbnail_id())->post_excerpt;
							if (!empty($thumb_caption)) :
								echo "<p>".$thumb_caption."</p>";
							endif;
						?>
					</div>
					<?PHP
						endif;
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
			<?php
				endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>