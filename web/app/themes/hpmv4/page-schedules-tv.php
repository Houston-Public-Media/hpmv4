<?php
/*
Template Name: TV Schedule
*/
	get_header(); ?>
	<style>
		body.page.page-template-page-schedules-tv #main {
			background-color: transparent;
		}
		body.page.page-template-page-schedules-tv .page-header .page-title {

			text-transform: uppercase;
			/*font-size: 2.5em;
			font-family: var(--hpm-font-condensed);
            color: #00b0bc;*/
			margin-bottom: 0.5em;
		}
		#main h2 {
			color: var(--main-blue);
			margin-bottom: 1rem;
		}
		body.page.page-template-page-schedules-tv :is(.column-left,.column-right) {
			background-color: var(--main-element-background);
			padding: 1rem;
		}
        .schedule_subhead__dark .schedule_subhead__control-time button
        {
            background: #237bbd !important;
            background-color: #237bbd !important;
        }
		@media screen and (min-width: 64.0625em) {
			body.page-template-page-schedules-tv #content {
				max-width: 92em;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) {
				the_post(); ?>
			<header class="page-header">
				<h1 class="page-title entry-title"><?php the_title(); ?></h1>
				<div id="station-social"></div>
			</header>
		<?php
				the_content();
			} ?>
		</main>
	</div>
<?php get_footer(); ?>