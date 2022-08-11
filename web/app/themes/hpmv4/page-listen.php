<?php
/*
Template Name: Listen Live
*/
get_header(); ?>
<style>
	body.page-template-page-listen .np-selector-wrap {
		display: flex;
		border-right: 0.125em solid rgb(230,230,230);
	}
	body.page-template-page-listen .np-selector-wrap div {
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
	body.page-template-page-listen .np-selector-wrap div:hover {
		opacity: 0.8;
		cursor: pointer;
	}
	body.page-template-page-listen .np-selector-wrap div.active {
		color: rgb(34,175,186);
		border-bottom: 0.125em solid rgb(255,255,255);
		border-top: 0.125em solid rgb(34,175,186);
		background-color: rgb(255,255,255);
	}
	body.page-template-page-listen video, body.page-template-page-listen object {
		opacity: 0;
	}
	body.page-template-page-listen .player-wrap {
		background-color: white;
		padding: 0.5em;
		overflow: hidden;
		border-left: 0.125em solid rgb(230,230,230);
		border-right: 0.125em solid rgb(230,230,230);
		border-bottom: 0.125em solid rgb(230,230,230);
	}
	body.page-template-page-listen #np-classical,
	body.page-template-page-listen #np-mixtape {
		display: none;
	}
	body.page-template-page-listen .np-info {
		float: left;
		width: 50%;
		padding: 0 0.5em 1em;
		margin-bottom: 0.5em;
	}
	body.page-template-page-listen .np-info ul {
		list-style: none;
		margin: 0;
	}
	body.page-template-page-listen .np-info h4 {
		font-size: 1.125em;
		padding: 0;
		margin-bottom: 0.5em;
	}
	body.page-template-page-listen .np-info p {
		padding: 0;
	}
	body.page-template-page-listen .np-info ul li {
		padding: 0.25em 0;
		margin: 0;
	}
	body.page-template-page-listen .np-info ul li a {
		text-decoration: underline;
	}
	body.page-template-page-listen .player-wrap h3 {
		font: 700 1.125em/1em var(--hpm-font-main);
		padding: 1em 2.5% 0;
		color: rgb(75,76,80);
		text-transform: uppercase;
	}
	body.page-template-page-listen :is(footer,#foot-banner) {
		display: none;
	}
	body.page.page-template-page-listen #main > article {
		width: 100%;
		border: 0;
		padding: 0;
		margin: 0;
	}
	body.page-template-page-listen #main {
		background-color: transparent;
	}
	body.page-template-page-listen article .entry-header {
		background-color: white;
		padding: 0;
		width: 1px;
	}
	body.page-template-page-listen article .entry-content {
		padding: 0.5em 0 !important;
	}
	body.page-template-page-listen .sgplayer {
		width: 100%;
		height: 650px;
		display:inline-block;
		margin: 0;
	}
	body.page-template-page-listen #primary {
		max-width: 30em;
		margin: 0 auto;
	}
	.nav-active-menu nav#site-navigation, nav#site-navigation:focus-within {
		position: fixed;
		left: 60vw;
		height: 100vh;
		width: 40vw;
		overflow-y: scroll;
		overflow-x: hidden;
	}
	.player-wrap {
		margin-top: 0 !important;
	}
	@media screen and (min-width: 34em) {
		body.nav-active-menu #top-mobile-close {
			left: calc(60vw - 5rem);
		}
	}
	@media screen and (min-width: 52.5em) {
		body.page-template-page-listen.nav-active-menu {
			position: fixed;
		}
		body.page-template-page-listen #masthead,
		body.page-template-page-listen .container {
			height: auto;
			border: 0;
			padding: 0;
		}
		body.page-template-page-listen #primary {
			max-width: 30em;
			margin: 0 auto;
		}
		body.page-template-page-listen.watch-tv #primary {
			max-width: 60em;
			margin: 0 auto;
		}
		body.page-template-page-listen #masthead,
		body.page-template-page-listen #content {
			max-width: 100% !important;
			min-width: 100% !important;
		}
		body.page-template-page-listen #main {
			background-color: transparent;
			min-height: auto;
		}
		body.page-template-page-listen article .entry-content {
			padding: 0.25em 0 0 !important;
		}
	}
	@media screen and (min-width: 64.0625em) {
		body.page-template-page-listen #masthead,
		body.page-template-page-listen .container {
			height: auto;
		}
	}
</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header screen-reader-text">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header>
				<div class="entry-content">
                    <?php echo get_the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>
