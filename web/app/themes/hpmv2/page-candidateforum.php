<?php
/*
Template Name: Candidate Forum
*/
get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?PHP while (have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="page-header">
					<h1 class="page-title screen-reader-text"><?php echo get_the_title(); ?></h1>
				</header><!-- .entry-header -->
				<div class="page-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->
				<footer class="page-footer">
					<?PHP
					$tags_list = get_the_tag_list('', _x(' ', 'Used between list items, there is a space after the comma.', 'hpmv2'));
					if ($tags_list) {
						printf(
							'<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x('Tags', 'Used before tag names.', 'hpmv2'),
							$tags_list
						);
					}
					edit_post_link(__('Edit', 'hpmv2'), '<span class="edit-link">', '</span>'); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
		<?php endwhile; ?>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<style>
	.page-content {
		overflow: hidden;
		padding: 2.5em 1em 1em;
	}

	.page-content p {
		margin-bottom: 1em;
	}

	.page-header {
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
		height: 0;
		margin: 0 !important;
		padding-right: 0;
		padding-left: 0;
		padding-top: 0;
		padding-bottom: calc(100%/1.5);
		position: relative;
		background-image: url(https://cdn.hpm.io/assets/images/HOBBY_Election-2020-Forum_mobile.jpg);
	}

	.page-template-page-candidateforum article {
		padding: 0;
		margin: 0;
	}

	#search-results article .entry-summary {
		padding: 0;
	}

	#search-results article {
		display: flex;
		justify-content: center;
		align-content: center;
		align-items: center;
	}

	@media screen and (min-width: 34em) {
		.page-header {
			padding-right: 0;
			padding-left: 0;
			padding-top: 0;
			padding-bottom: calc(100%/4);
			margin: 0 !important;
			background-image: url(https://cdn.hpm.io/assets/images/HOBBY_Election-2020-Forum_tablet.jpg);
		}
	}

	@media screen and (min-width: 50.0625em) {
		.page-template-page-candidateforum article {
			width: 100%;
			float: none;
			border-right: 0;
		}

		.page-header {
			padding-right: 0;
			padding-left: 0;
			padding-top: 0;
			padding-bottom: calc(100%/6);
			margin: 0 !important;
			background-image: url(https://cdn.hpm.io/assets/images/HOBBY_Election-2020-Forum_desktop.jpg);
		}
	}
</style>
<?php get_footer(); ?>