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
	body.page-template-page-listen #masthead #top-listen,
	body.page-template-page-listen #masthead #top-watch {
		display: none;
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
	body.page-template-page-listen article .entry-header h1 {
		font: 400 2em/1em var(--hpm-font-condensed);
		text-transform: uppercase;
	}
	body.page-template-page-listen footer {
		display: none;
	}
	body.page-template-page-listen article {
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
		padding: 1em;
	}
	body.page-template-page-listen article .entry-content {
		padding: 0.5em 0 !important;
	}
	body.page-template-page-listen #top-schedule {
		display: none;
	}
	body.page-template-page-listen #div-gpt-ad-1394579228932-0 {
		display: none;
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
		body.page-template-page-listen #emergency {
			min-width: auto;
		}
		body.page-template-page-listen #masthead .site-branding {
			padding-top: 0;
		}
		body.page-template-page-listen #masthead .site-branding .site-logo {
			padding: 0.5em 0 0.5em 0.75em;
			width: 12em;
		}
		body.page-template-page-listen #masthead .site-branding .site-logo a {
			background-image: url( https://cdn.hpm.io/assets/images/HPM-PBS-NPR-Reverse.png );
			background-position: left center;
		}
		body.page-template-page-listen #masthead span.top-mobile-text {
			font-size: 60%;
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
		body.page-template-page-listen #masthead #top-schedule {
			display: none;
		}
		body.page-template-page-listen #masthead nav#site-navigation {
			position: fixed;
			background-color: rgb(255,255,255);
			height: 100%;
			z-index: 900;
			top: 0;
			left: 100%;
			bottom: 0;
			border-left: 0.25em solid rgb(153,197,211);
			overflow-y: scroll;
			overflow-x: hidden;
		}
		body.page-template-page-listen #masthead #top-search {
			position: static;
			width: 100%;
			padding: 1.5em;
			background-color: rgb(242,243,243);
		}
		body.page-template-page-listen #masthead #top-search .fa,
		body.page-template-page-listen #masthead #top-search .fab,
		body.page-template-page-listen #masthead #top-search .fas {
			position: absolute;
			top: 0.375em;
			left: 0.25em;
			color: rgb(81,82,86);
			font-size: 3em;
		}
		body.page-template-page-listen #masthead #top-search .search-form {
			display: block;
			position: static;
		}
		body.page-template-page-listen #masthead #top-search .search-field {
			border: 0;
			outline: 0;
			background-color: transparent;
			padding: 0 0 0 1.25em;
			text-transform: lowercase;
			font: 400 2em/1em var(--hpm-font-main);
		}
		body.page-template-page-listen #masthead,
		body.page-template-page-listen #content {
			max-width: 100% !important;
			min-width: 100% !important;
		}
		body.page-template-page-listen #masthead {
			background-color: #C8102E;
		}
		body.page-template-page-listen #masthead #top-donate {
			right: 1em;
			padding: 1em;
			position: absolute;
			background-color: rgba(0,0,0,0.25);
		}
		body.page-template-page-listen #masthead .site-branding .site-logo {
			height: 4em;
		}
		body.page-template-page-listen #masthead #top-mobile-menu,
		body.page-template-page-listen #masthead #top-donate {
			display: block;
			height: 4em;
			width: 4em;
			font-size: 100%;
		}
		body.page-template-page-listen #masthead #top-mobile-menu {
			left: calc(100% - 64px);
			line-height: 112.5%;
		}
		body.page-template-page-listen.nav-active-menu #masthead #top-mobile-menu {
			left: calc(100% - 29em);
			position: fixed;
		}
		body.page-template-page-listen.nav-active-menu #masthead nav#site-navigation {
			left: calc(100% - 25em);
			width: 25em !important;
		}
		body.page-template-page-listen #masthead #top-mobile-menu .fa,
		body.page-template-page-listen #masthead #top-donate .fa,
		body.page-template-page-listen #masthead #top-mobile-menu .fab,
		body.page-template-page-listen #masthead #top-donate .fab,
		body.page-template-page-listen #masthead #top-mobile-menu .fas,
		body.page-template-page-listen #masthead #top-donate .fas {
			font: 900 1.75em/0.9em 'Font Awesome 5 Free';
		}
		body.page-template-page-listen #masthead #top-donate a {
			text-transform: uppercase !important;
			background-color: transparent;
			padding: 0;
			font-family: var(--hpm-font-main);
			line-height: 112.5%;
		}
		body.page-template-page-listen #masthead #top-donate a .top-mobile-text {
			margin-left: -3px;
		}
		body.page-template-page-listen #main {
			background-color: transparent;
			min-height: auto;
		}
		body.page-template-page-listen article .entry-content {
			padding: 0.25em 0 0 !important;
		}
		body.page-template-page-listen #masthead nav#site-navigation ul {
			display: block;
		}
		body.page-template-page-listen #masthead nav#site-navigation ul li {
			text-align: left;
			position: initial;
		}
		body.page-template-page-listen #masthead nav#site-navigation div.nav-top,
		body.page-template-page-listen #masthead nav#site-navigation .nav-top a {
			padding: 0.5em 0 0.5em 2em;
			font: 700 1.5em/1.5em var(--hpm-font-main);
		}
		body.page-template-page-listen li.nav-top.menu-item-has-children div.nav-top:after {
			content: '\f0da';
			display: inline-block;
			-webkit-font-smoothing: antialiased;
			font: 900 .75em/1 'Font Awesome 5 Free';
			float: right;
			padding-right: 1em;
			position: relative;
			top: 0.25em;
		}
		body.page-template-page-listen #masthead nav#site-navigation ul li ul {
			display: none;
			position: absolute;
			top: 0;
			background-color: white;
			margin: 0;
			width: 100%;
			padding: 0;
			height: 100%;
			transform: translate3d(100%,0,0);
			transition: transform .2s ease-out;
			z-index: 9999;
		}
		body.page-template-page-listen #masthead nav#site-navigation ul li ul li.nav-back {
			display: block;
		}
		body.page-template-page-listen #masthead nav#site-navigation .nav-top ul li a {
			padding: 0.25em 0 0.25em 2em;
		}
		body.page-template-page-listen #masthead nav#site-navigation ul li.nav-active ul {
			display: block;
			transform: translate3d(0,0,0);
			transition: transform .2s ease-out;
		}
	}
	@media screen and (min-width: 64.0625em) {
		body.page-template-page-listen #masthead,
		body.page-template-page-listen .container {
			height: auto;
		}
		body.page-template-page-listen #masthead #top-donate a {
			font: normal 1em/1em var(--hpm-font-main);
		}
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
