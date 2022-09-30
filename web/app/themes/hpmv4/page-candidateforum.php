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
					$tags_list = get_the_tag_list('', _x(' ', 'Used between list items, there is a space after the comma.', 'hpmv4'));
					if ($tags_list) {
						printf(
							'<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x('Tags', 'Used before tag names.', 'hpmv4'),
							$tags_list
						);
					}
					edit_post_link(__('Edit', 'hpmv4'), '<span class="edit-link">', '</span>'); ?>
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
		<?php endwhile; ?>
	</main><!-- .site-main -->
</div><!-- .content-area -->
<style>
	body.page.page-template-page-candidateforum #main {
		background-color: transparent !important;
	}

	.page-content {
		overflow: hidden;
		padding: 1em 0;
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
	.page-template-page-candidateforum h2 a {
		color: #00b0bc;
	}
	section.column-left article,
	aside.column-right {
		padding: 1em;
		overflow: hidden;
		background-color: var(--main-element-background);
		margin-bottom: 2em;
	}

	section.column-left,
	aside.column-right {
		width: 100%;
		margin: 0 0 2em 0;
	}

	section h1 {
		margin-bottom: 0.25em;
	}

	.candidates div {
		margin-bottom: 2em;
		padding: 1em;
		background-color: var(--main-background);
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

		section.column-left,
		aside.column-right {
			width: 95%;
			margin: 0 2.5% 2em;
		}
		.candidates {
			display: flex;
			flex-flow: row nowrap;
		}
		.candidates div {
			width: 47.5%;
			margin: 0 1.25%;
		}

		@media screen and (min-width: 52.5em) {
			.page.page-template-page-candidateforum #main > article {
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

			aside.column-right {
				margin: 0 0 2em 1.5%;
				width: 31.5%;
			}
			section.column-left {
				width: 67%;
				margin: 0 0 2em 0;
			}
		}
</style>
<?php get_footer(); ?>