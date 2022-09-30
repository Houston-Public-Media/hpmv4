<?php
/*
Template Name: Support Sub
*/
get_header(); ?>
	<style>
		.page.page-template-page-support-sub .page-content {
			padding: 2em;
			width: 100%;
			margin: 0;
		}
		.page-template-page-support-sub .page-header {
			padding: 1em !important;
		}
		.page.page-template-page-support-sub .page-header-wrap {
			color: white;
			flex-grow: 2;
		}
		.page-template-page-support-sub .page-content ul {
			list-style: disc outside none;
			padding: 0 0 1em 2em;
		}
		.page-template-page-support-sub.matching-info .page-content aside {
			background-color: var(--main-background);
		}
		.page-template-page-support-sub.matching-info .page-content aside h2 {
			padding-top: 0;
		}
		.page.page-template-page-support-sub .page-header {
			background-color: var(--main-red);
			padding: 1em 1em 0 1em;
		}
		.page.page-template-page-support-sub .page-header .page-title {
			color: white;
			margin: 0;
			font-family: var(--hpm-font-main);
			font-weight: 700;
		}
		.page.page-template-page-support-sub .page-header p {
			color: white;
			margin: 0;
		}
		.page.page-template-page-support-sub .page-header :is(img,picture) {
			width: auto;
			max-width: 300px;
			padding: 0 1em;
		}
		.page.page-template-page-support-sub .page-content h2 {
			padding: 0;
			color: var(--main-red);
			font-family: var(--hpm-font-main); font-weight: 700;
		}
		.page.page-template-page-support-sub p {
			color: var(--main-text);
			font: 100 1.25em/1.125em var(--hpm-font-main);
			margin-bottom: 1em;
		}
		.page.page-template-page-support-sub .page-content h2 {
			color: #016D94;
			padding: 1em 0 0.25em;
			margin-bottom: 1em;
			border-bottom: 2px solid #016D94;
			font-size: 1.5em;
		}
		@media screen and (min-width: 34em) {
			.page.page-template-page-support-sub .page-header {
				flex-flow: row nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
			}
			.page.page-template-page-support-sub .page-header-wrap {
				width: 70%;
				padding-bottom: 1em;
			}
			.page-template-page-support-sub .page-content .column-left ul {
				display: grid;
				grid-template-columns: 1fr 1fr;
			}
		}
		@media screen and (min-width: 52.5em) {
			.page.page-template-page-support-sub #main > article {
				border: 0;
				width: 100%;
				float: none;
				margin: 0;
			}
			.page.page-template-page-support-sub .page-header {
				flex-flow: row nowrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				display: flex;
			}
			.page.page-template-page-support-sub .page-header-wrap {
				width: 50%;
				padding: 0;
			}
			.page-template-page-support-sub .page-content {
				width: 80%;
				margin: 0 10%;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: 0;">
				<header class="page-header">
					<div class="page-header-wrap">
						<h1 class="page-title"><?php echo get_the_title(); ?></h1>
						<?php the_excerpt(); ?>
					</div>
					<?php the_post_thumbnail(); ?>
				</header>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
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
				</footer>
			</article>
		<?php endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>