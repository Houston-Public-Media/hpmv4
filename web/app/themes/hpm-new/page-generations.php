<?php
/*
Template Name: Generations on the Rise
*/
get_header(); ?>
<style>
#main > article {
	grid-column: 1 / -1 !important;
	padding: 0 !important;
}
.page-title {
	display: block !important;
}
#div-gpt-ad-1488818411584-0,
#foot-banner {
	display: none;
}
.page-header {
	padding: 0;
	margin: 0;
	background-color: #006E97;
	background-image: url(https://cdn.hpm.io/assets/images/genrise_herobkg.jpg);
	background-position: center center;
	background-repeat: no-repeat;
	background-size: cover;
}
.page-header h1 {
	font: 500 1.5em/1.25em brandon-grotesque,var(--hpm-font-main);
	color: white;
	margin: 0;
	padding: 0 1em 1em;
	text-align: center;
}
#generations-orgs {
	padding: 0 4.5em 2em;
	font: 400 1em/2em brandon-grotesque,var(--hpm-font-main);
	text-align: center;
	color: white;
}
#generations-logo {
	padding: 1.5em 5.5em 0;
}
.page-template-page-generations section {
	padding: 2em 1em;
	margin: 0;
	position: relative;
}
section#generations-top {
	background-color: #006E97;
}
section#generations-top h1 {
	margin: 0;
	font: 400 1.5em/1.25em brandon-grotesque,var(--hpm-font-main);
	color: white;
	text-align: center;
}
section#generations-top h1 a {
	color: #A6C12F;
}
.page-template-page-generations section#generations-videos article {
	margin-bottom: 2em;
	padding-bottom: 1em;
	background-image: url(https://cdn.hpm.io/assets/images/houstonfirst_black2x.png);
	background-repeat: no-repeat;
	background-position: right bottom;
	background-size: 6em;
}
#generations-videos .iframe-embed {
	padding-bottom: calc(100%/1.5) !important;
}
.page-template-page-generations section#generations-videos {
	padding: 2em 0;
}
section#generations-videos h2 {
	color: #1881A1;
	font: 700 1.75em/1.25em brandon-grotesque,var(--hpm-font-main);
	margin: 0;
	text-transform: uppercase;
}
section#generations-videos h3 {
	font: 400 1.25em/1.25em brandon-grotesque,var(--hpm-font-main);
	margin: 0 0 1em 0;
	padding: 0 0 0.5em 0;
	border-bottom: 0.0625em solid #1881A1;
	color: #449EBB;
}
section#generations-videos .generations-video-wrap:nth-child(2) {
	padding: 2em 1em 1em;
}
section#generations-videos h3 .generations-videos-title {
	font-weight: 500;
}
section#generations-videos p {
	color: #424242;
	font: 400 1.25em/1.4em brandon-grotesque,var(--hpm-font-main);
	margin: 0;
}
section#generations-feature {
	background-color: #1881A1;
}
section#generations-feature img {
	width: 33%;
	padding: 0 1em 0 0;
	float: left;
}
section#generations-feature .generations-feature-wrap {
	padding: 1em 0;
}
section#generations-feature h3 {
	color: white;
	font: 700 1.125em/1.25em brandon-grotesque,var(--hpm-font-main);
}
section#generations-feature h2 {
	font: 700 1.75em/1.5em brandon-grotesque,var(--hpm-font-main);
	color: #A6C12F;
}
section#generations-feature p {
	font: 300 1em/1.5em brandon-grotesque,var(--hpm-font-main);
	color: white;
	margin: 0;
}
section#generations-feature #generations-feature-arrows {
	position: absolute;
	top: -2.25em;
	right: 1em;
	margin: 0;
	width: 8em;
	display: none;
}
section#generations-footer {
	background-color: #006E97;
}
section#generations-footer p {
	font: 700 0.75em/1em brandon-grotesque,var(--hpm-font-main);
	color: white;
	margin: 0;
	text-align: center;
}
section#generations-footer p a {
	font-weight: 400;
	color: white;
}
div.gotr-month {
	display: none;
}
div.gotr-month.gotr-month-active {
	display: block;
}
.page-template-page-generations section#generations-months {
	padding: 0;
	background-color: #006E97;
}
section#generations-months ul {
	justify-content: center;
	align-content: center;
	align-items: stretch;
	flex-flow: row nowrap;
	display: flex;
	list-style: none;
	padding: 0;
	margin: 0;
	border-top: 0.125em solid white;
}
section#generations-months ul li {
	padding: 1em;
	margin: 0;
	text-align: center;
	flex-grow: 1;
	flex-shrink: 1;
	flex-basis: 0;
	justify-content: center;
	align-content: center;
	align-items: center;
	flex-flow: row nowrap;
	display: flex;
	border-right: 0.125em solid white;
}
section#generations-months ul li p {
	font: 400 1.125em/1.125em brandon-grotesque,var(--hpm-font-main);
	color: white;
	margin: 0;
}
section#generations-months ul li:last-child {
	border-right: none;
}
section#generations-months ul li.gotr-month-active {
	background-color: rgba(255,255,255,0.2);
}
section#generations-months ul li:hover {
	cursor: pointer;
	opacity: 0.75;
}
@media screen and (min-width: 34em) {
	.page-template-page-generations .page-header {
		padding: 0;
		margin: 0;
		background-color: #006E97;
		background-image: url(https://cdn.hpm.io/assets/images/GenRise_Banner_arrows.png), url(https://cdn.hpm.io/assets/images/genrise_herobkg2x.jpg);
		background-position: right top, center center;
		background-repeat: no-repeat, no-repeat;
		background-size: 100%, cover;
		overflow: hidden;
	}
	.page-template-page-generations .page-header h1 {
		font: 500 1.75em/1.25em brandon-grotesque,var(--hpm-font-main);
		color: white;
		margin: 0;
		padding: 7.5em 1em 0.5em;
		float: left;
		width: 50%;
	}
	#generations-orgs {
		padding: 0 4em 1em;
		width: 50%;
		float: left;
	}
	#generations-logo {
		width: 50%;
		float: left;
		padding: 1.5em 3.5em 0;
	}
	.page-template-page-generations section#generations-videos article {
		overflow: hidden;
		padding: 1em 0 2em;
		background-position: 95% bottom;
	}
	section#generations-videos .generations-video-wrap:nth-child(2) {
		padding: 1em 4em;
	}
	.page-template-page-generations section#generations-feature {
		padding: 2em 4em;
	}
	section#generations-feature img {
		width: 20%;
	}
	section#generations-feature h2 {
		font-size: 200%;
	}
	.page-template-page-generations section#generations-footer {
		padding: 1em;
	}
	section#generations-feature #generations-feature-arrows {
		padding: 0;
		float: none;
	}
}
@media screen and (min-width: 52.5em) {
	section#generations-videos {
		overflow: hidden;
	}
	.page-template-page-generations .page-header h1 {
		padding: 8.5em 1em 1em;
		font-size: 200%;
	}
	#generations-orgs {
		padding: 0 5.5em 1em;
	}
	#generations-logo {
		padding: 1.5em 5em 0;
	}
	section#generations-top {
		padding: 2em 11em;
	}
	.page-template-page-generations article {
		width: 100%;
		border: 0;
		float: none;
	}
	.page-template-page-generations section#generations-videos article {
		flex-flow: row wrap;
		justify-content: left;
		align-content: center;
		align-items: flex-start;
		display: flex;
	}
	section#generations-videos .generations-video-wrap {
		width: 50%;
	}
	section#generations-videos .generations-video-wrap:nth-child(1) {
		padding-left: 2em;
	}
	section#generations-videos .generations-video-wrap:nth-child(2) {
		padding: 0 4em 0 2em;
	}
	section#generations-videos h3 {
		font-size: 125%;
		margin-bottom: 0.5em;
	}
	section#generations-videos p {
		font-size: 100%;
	}
	.page-template-page-generations section#generations-feature {
		padding: 2em 4em;
	}
	section#generations-feature div.gotr-month {
		flex-flow: row wrap;
		justify-content: left;
		align-content: center;
		align-items: center;
		display: none;
	}
	section#generations-feature div.gotr-month.gotr-month-active {
		display: flex;
	}
	section#generations-feature img {
		width: 30%;
		padding: 0 2em 0 0;
	}
	section#generations-feature .generations-feature-wrap {
		padding: 1em;
		width: 70%;
	}
	section#generations-feature h2,
	section#generations-feature h3 {
		margin-bottom: 0;
	}
}
@media screen and (min-width: 64.0625em) {
	.page-template-page-generations section#generations-feature {
		padding: 2em 8em;
	}
	section#generations-top {
		padding: 2em 13em;
	}
	section#generations-top h1 {
		font-size: 175%;
	}
	#generations-logo {
		padding: 1.5em 7em 0;
	}
	#generations-orgs {
		padding: 0 7.5em 1em;
	}
	.page-template-page-generations .page-header h1 {
		padding: 9em 2em 1em;
		font-size: 225%;
	}
	section#generations-videos p {
		font-size: 125%;
	}
}
</style>
<link rel="stylesheet" href="https://use.typekit.net/gsg7chk.css">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?PHP while ( have_posts() ) :
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
				</header>
				<div class="page-content">
					<?php echo get_the_content(); ?>
				</div>
				<footer class="page-footer">
					<?PHP
					$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv2' ) );
					if ( $tags_list ) {
						printf( '<p class="screen-reader-text"><span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span></p>',
							_x( 'Tags', 'Used before tag names.', 'hpmv2' ),
							$tags_list
						);
					}
					edit_post_link( __( 'Edit', 'hpmv2' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>
			</article>
		<?php endwhile; ?>
		</main>
	</div>
<?php get_footer(); ?>