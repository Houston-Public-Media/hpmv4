<?php
/*
Template Name: Listen Live
*/
get_header(); ?>
	<style>
		#masthead {
			max-width: 100%;
		}
		.np-selector-wrap {
			display: flex;
			border-right: 0.125em solid rgb(230,230,230);
		}
		.np-selector-wrap div {
			flex-basis: 1;
			flex-grow: 2;
			text-align: center;
			font: 100 21px/25px var(--hpm-font-main);
			color: #58585b;
			padding: 0.5em 1em;
			background-color: rgb(245,245,245);
			border-top: 0.125em solid rgb(230,230,230);
			border-bottom: 0.125em solid rgb(196,196,196);
			border-left: 0.125em solid rgb(230,230,230);
		}
		.np-selector-wrap div:hover {
			opacity: 0.8;
			cursor: pointer;
		}
		.np-selector-wrap div.active {
			color: rgb(34,175,186);
			border-bottom: 0.125em solid rgb(255,255,255);
			border-top: 0.125em solid rgb(34,175,186);
			background-color: rgb(255,255,255);
		}
		video, object {
			opacity: 0;
		}
		.player-wrap {
			background-color: white;
			padding: 0.5em;
			overflow: hidden;
			border-left: 0.125em solid rgb(230,230,230);
			border-right: 0.125em solid rgb(230,230,230);
			border-bottom: 0.125em solid rgb(230,230,230);
		}
		#np-classical,
		#np-mixtape {
			display: none;
		}
		.np-info {
			float: left;
			width: 50%;
			padding: 0 0.5em 1em;
			margin-bottom: 0.5em;
		}
		.np-info ul {
			list-style: none;
			margin: 0;
		}
		.np-info h4 {
			font-size: 1.125em;
			padding: 0;
			margin-bottom: 0.5em;
		}
		.np-info p {
			padding: 0;
		}
		.np-info ul li {
			padding: 0.25em 0;
			margin: 0;
		}
		.np-info ul li a {
			text-decoration: underline;
		}
		article {
			grid-column: 1 / -1 !important;
			padding: 0 !important;
			background-color: transparent !important;
		}
		#main {
			background-color: transparent !important;
			padding: 0;
		}
		article .entry-content {
			padding: 0.5em 0 !important;
		}
		#top-schedule,
		#div-gpt-ad-1394579228932-0,
		footer,
		#masthead #top-listen,
		#masthead #top-watch,
		#foot-banner {
			display: none;
		}
		.sgplayer {
			width: 100%;
			height: 650px;
			display:inline-block;
			margin: 0;
		}
		#content {
			max-width: 30em;
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header screen-reader-text">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->
				<div class="entry-content">
                    <?php echo get_the_content(); ?>
				</div><!-- .entry-content -->
			</article><!-- #post-## -->
		<?php endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>