<?php
/*
Template Name: Kids
*/

get_header(); ?>
	<style>
		@font-face {
			font-family: 'PBSKids';
			src: url('https://cdn.hpm.io/assets/fonts/pbskidsheadline-regular-webfont.ttf') format('truetype'), url('https://cdn.hpm.io/assets/fonts/pbskidsheadline-regular-webfont.woff') format('woff'), url('https://cdn.hpm.io/assets/fonts/pbskidsheadline-regular-webfont.eot') format('eot');
			font-weight: normal;
			font-style: normal;
		}
		#station-module {
	height: 450px;
}
.category-list-wrapper {
	display: flex;
	flex-flow: row nowrap;
	justify-content: center;
	align-content: center;
	align-items: center;
	position: relative;
	background-color: #00edff;
	padding: 0.5em;
	box-sizing: border-box;
	font-size: 13.3px;
}
.category-list-wrapper p {
	font-family: 'PBSKids',arial,helvetica,sans-serif;
	color: white;
	margin: 0 1em;
	font-size: 1.25em;
}
.category-list-wrapper .live-streaming-button {
	background-color: #ff8b00;
	min-width: 4.8em;
	height: 5.8em;
	border-radius: 1em;
	margin: 0.7em 0.5em 0 0.25em;
	cursor: pointer;
	position: relative;
}
.category-list-wrapper .live-streaming-button .live-streaming-title {
	font-family: 'PBSKids', Arial, Helvetica, sans-serif;
	font-size: 0.8em;
	font-weight: normal;
	font-style: normal;
	line-height: 1.2;
	letter-spacing: 1px;
	position: absolute;
	text-transform: uppercase;
	text-align: center;
	line-height: normal;
	left: 0;
	right: 0;
	bottom: 0.5em;
	z-index: 1;
	color: white;
}
.hidden-important {
	display: none !important;
	visibility: hidden !important;
}
.category-list-wrapper .live-streaming-button .live-streaming-series-logo {
	width: 3.8em;
	height: 3.8em;
	border-radius: 3.8em;
	background: #fff;
	overflow: hidden;
	position: relative;
	margin: 0.25em auto 0;
}
.category-list-wrapper .live-streaming-button .live-streaming-series-logo img {
	position: absolute;
	left: 0;
	bottom: 0;
	max-width: 100%;
	max-height: 100%;
	border-radius: 3.8em;
}
.category-list-wrapper .live-streaming-button:after {
	content: "";
	position: absolute;
	top: -0.7em;
	height: 0.7em;
	width: 100%;
	background: url(https://cms-tc.pbskids.org/nationalvideoplayer/resources/img/antenna.svg) no-repeat center top;
}
#kids-younger, #kids-older {
	overflow: hidden;
	padding: 1em;
	width: 100%;
	border-top: 0.125em solid white;
}
#kids-younger ul, #kids-older ul {
	margin: 0;
	list-style: none;
}
#kids-older {
	background-color: rgb(241,168,47);
}
#kids-younger {
	background-color: rgb(118,199,219);
}
#kids-older h3 {
	text-align: center;
	color: white;
	font-size: 2em;
}
#kids-younger ul li, #kids-older ul li {
	text-align: center;
	float: left;
}
#kids-younger ul li {
	width: 50%;
}
#kids-older ul {
	border: 0.5em solid rgb(231,228,57);
	overflow: hidden;
	background-color: rgb(231,228,57);
}
#kids-older ul li {
	width: 50%;
	border: 0.5em solid rgb(231,228,57);
}
#kids-older ul li a {
	display: block;
	line-height: 0;
}
#kids-younger ul li img, #kids-older ul li img {
	width: 100%;
}
body.page.page-template-page-kids #main {
	background-color: rgb(166,239,24);
	position: relative;
	display: block !important;
}
body.page.page-template-page-kids .page-header {
	background-color: transparent;
	background-image: url('https://cdn.hpm.io/wp-content/uploads/2016/01/14164222/White-lines-2.png');
	background-position: center center;
	background-repeat: no-repeat;
	background-size: 110% auto;
	position: relative;
	height: 10em;
	border-bottom: 0.25em solid white;
}
body.page.page-template-page-kids .page-header #head-logo {
	position: absolute;
	top: 1em;
	left: 25%;
	max-height: 8em;
	z-index: 100;
}
body.page.page-template-page-kids .page-header #head-cat {
	position: absolute;
	bottom: 0;
	left: 0.5em;
	max-height: 6.5em;
	z-index: 95;
}
body.page.page-template-page-kids .page-header #head-kids {
	position: absolute;
	bottom: 0;
	right: 0.5em;
	max-height: 6.5em;
	z-index: 95;
}
#main > aside.column-right.kids-sidebar {
	background-color: white;
	padding: 2em 1em 1em;
	margin: 0;
	width: 100%;
}
#main > aside.column-right.kids-sidebar .sidebar-ad {
	padding: 0;
	margin: 0;
}
#kids-nav {
	background-color: rgb(231,228,57);
	width: 100%;
	padding: 0 0.5em;
	overflow: hidden;
}
#kids-nav .kids-nav-container {
	width: 85%;
	margin: 0 auto;
}
#kids-nav .kids-nav-container a {
	width: 50%;
	float: left;
	padding: 0 2em;
}
@media screen and (min-width: 30.0625em) {
	#station-module {
		height: 650px;
	}
	.category-list-wrapper p {
		font-size: 1.5em;
	}
}
@media screen and (min-width: 34em) {
	#kids-nav .kids-nav-container {
		width: 75%;
		margin: 0 auto;
	}
	#kids-nav .kids-nav-container a {
		padding: 0 3em;
	}
	body.page.page-template-page-kids .page-header {
		height: 13em;
	}
	body.page.page-template-page-kids .page-header #head-logo {
		left: 31%;
		max-height: 95%;
		top: 0.5em;
	}
	body.page.page-template-page-kids .page-header #head-cat {
		left: 2em;
		max-height: 11em;
	}
	body.page.page-template-page-kids .page-header #head-kids {
		right: 2em;
		max-height: 11em;
	}
	#kids-older ul,
	#kids-younger ul {
		display: flex;
		flex-flow: row wrap;
		justify-content: center;
		align-items: center;
		align-content: center;
	}
	#kids-older ul li,
	#kids-younger ul li {
		width: 33.333333%;
	}
}
@media screen and (min-width: 52.5em) {
	#station-module {
		height: 750px;
	}
	.category-list-wrapper p {
		font-size: 2em;
	}
	#kids-younger, #kids-older {
		padding: 2em;
	}
	#kids-nav {
		position: absolute;
		top: 3em;
		right: 0;
		background-color: transparent;
		width: 21em;
	}
	#kids-nav .kids-nav-container {
		width: 100%;
		margin: 0;
	}
	#kids-nav .kids-nav-container a {
		padding: 0;
	}
	body.page.page-template-page-kids .page-header {
		height: 15em;
	}
	body.page.page-template-page-kids .page-header #head-logo {
		top: 1em;
		left: 0.5em;
		max-height: 85%;
	}
	body.page.page-template-page-kids .page-header #head-cat {
		left: 26.5%;
		max-height: 75%;
	}
	body.page.page-template-page-kids .page-header #head-kids {
		left: 45%;
		right: auto;
		max-height: 75%;
	}
	body.page.page-template-page-kids .column-left {
		margin: 0;
		width: 66%;
	}
	#main > aside.column-right.kids-sidebar {
		margin: 0 1% 1em;
		padding: 1em;
		width: 31.5%;
	}
}
@media screen and (min-width: 52.5em) {
	body.page.page-template-page-kids .page-header {
		background-position: left top;
		background-size: auto;
		height: 18.75em;
	}
	#kids-nav {
		position: absolute;
		top: 5em;
		right: 0;
		background-color: transparent;
		width: 23em;
	}
	body.page.page-template-page-kids .page-header #head-logo {
		top: 2em;
		left: 0.5em;
		max-height: 80%;
	}
}
.lah-schedule .lah-wrap {
	display: flex;
}
.lah-schedule {
	margin-bottom: 2em;
	width: 100%;
	overflow-x: scroll;
}
.lah-wrap {
	width: 1000px;
}
.lah-schedule h2, .lah-schedule h3 {
	font-family: 'PBSKids',var(--hpm-font-main);
	color: white;
	font-size: 150%;
}
.lah-col {
	flex-direction: column;
	display: flex;
	width: 17%;
}
.lah-col.lah-time {
	width: 7.5%;
}
.lah-col div {
	width: 100%;
	height: 50px;
	display: flex;
	text-align: center;
	padding: 5px;
	justify-content: center;
	align-items: center;
	border: 1px solid rgb(23,177,189);
	background-color: white;
	color: black;
	font-family: 'PBSKids',var(--hpm-font-main);
}
.lah-col.lah-time div,
.lah-col div.lah-col-head {
	color: rgb(23,177,189);
}
body.page.page-template-page-kids .kids-schedule .lah-col div a {
	text-align: center;
	color: #000;
}
.lah-col div.lah-young,
.lah-legend .lah-legend-young span {
	background-color: rgb(246,188,188);
}
.lah-col div.lah-middle,
.lah-legend .lah-legend-middle span {
	background-color: rgb(147,216,236);
}
.lah-col div.lah-high,
.lah-legend .lah-legend-high span {
	background-color: rgb(248,211,144);
}
.lah-col div.lah-science,
.lah-legend .lah-legend-science span {
	background-color: rgb(233,243,205);
}
.lah-col div.lah-sstudies,
.lah-legend .lah-legend-sstudies span {
	background-color: rgb(196,235,238);
}
.lah-col div.lah-ela,
.lah-legend .lah-legend-ela span {
	background-color: rgb(207,188,219);
}
.lah-col div.lah-math,
.lah-legend .lah-legend-math span {
	background-color: rgb(252,170,129);
}
.lah-legend {
	background-color: white;
	font-family: 'PBSKids',var(--hpm-font-main);
	padding: 0.5em;
	margin-bottom: 1em;
	display: flex;
	justify-content: space-evenly;
	width: 100%;
	flex-flow: row wrap;
}
.lah-legend div {
	padding: 0 0.5em;
	display: flex;
	align-items: center;
}
.lah-legend span {
	width: 2em;
	height: 1em;
	display: inline-block;
	margin-right: 0.5em;
}
.lah-col div.lah-60 {
	height: 100px;
}
.lah-col div.lah-90 {
	height: 150px;
}
.lah-col div.lah-120 {
	height: 200px;
}
.lah-col div.lah-150 {
	height: 250px;
}
.lah-col div.lah-180 {
	height: 300px;
}
.lah-col div.lah-100 {
	height: 166.66667px;
}
.lah-col div.lah-65 {
	height: 108.33333px;
}
.lah-col div.lah-70 {
	height: 116.666669px;
}
.ahl-tile-wrap {
	display: flex;
	align-content: center;
	justify-content: space-between;
	flex-flow: row wrap;
	margin-bottom: 1em;
}
.ahl-tile-wrap .ahl-tile {
	width: 100%;
	padding: 0;
	height: 0;
	padding-bottom: calc(100%/1.1);
	margin: 0 0 1em 0;
	position: relative;
	overflow: hidden;
}
.ahl-tile-wrap .ahl-tile a {
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	display: block;
}
.ahl-tile-wrap .ahl-tile .ahl-title {
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: 2000;
}
.entry-content.kids-ahl {
	padding: 1rem;
}
body.page.page-template-page-kids .kids-schedule.kids-ahl .ahl-tile-wrap .ahl-tile .ahl-title a {
	position: static;
	width: 100%;
	padding: 0.5em 1em;
	text-align: center;
	color: white;
	text-decoration: none;
	font: normal 1.375em/1em 'PBSKids',var(--hpm-font-main);
	height: 3em;
	display: flex;
	justify-items: center;
	align-items: center;
	justify-content: center;
}
.ahl-tile-wrap .ahl-tile:nth-child(1) .ahl-title a,
.ahl-tile-wrap .ahl-tile:nth-child(7) .ahl-title a {
	background-color: #FFCE16;
}
.ahl-tile-wrap .ahl-tile:nth-child(2) .ahl-title a {
	background-color: #A9CF38;
}
.ahl-tile-wrap .ahl-tile:nth-child(3) .ahl-title a,
.ahl-tile-wrap .ahl-tile:nth-child(9) .ahl-title a {
	background-color: #FE704E;
}
.ahl-tile-wrap .ahl-tile:nth-child(4) .ahl-title a {
	background-color: #2638C4;
}
.ahl-tile-wrap .ahl-tile:nth-child(5) .ahl-title a {
	background-color: #EB8F30;
}
.ahl-tile-wrap .ahl-tile:nth-child(6) .ahl-title a {
	background-color: #6A1B9A;
}
.ahl-tile-wrap .ahl-tile:nth-child(8) .ahl-title a {
	background-color: #A9CF38;
	padding: 0.5em;
	height: 3.25em;
	font-size: 1.25em;
}
.kids-ahl .column-right {
	padding: 1em;
	background-color: white;
	margin-bottom: 2em;
}
.kids-ahl .column-right h1 {
	font-family: 'PBSKids',var(--hpm-font-main);
	color: #17b1bd;
	margin: 0 0 0.5em;
	font-size: 150%;
}
body.page.page-template-page-kids .kids-schedule.kids-ahl .ahl-sched-links ul {
	margin: 0;
	list-style: none;
	padding: 0;
	width: 100%;
}
body.page.page-template-page-kids .kids-schedule.kids-ahl .ahl-sched-links ul li {
	padding: 0 0 0.75em 0;
	margin: 0;
	text-align: center;
}
body.page.page-template-page-kids .kids-schedule.kids-ahl .ahl-sched-links ul li a {
	font-family: 'PBSKids',var(--hpm-font-main);
	color: white;
	background-color: #0061AF;
	font-size: 125%;
	padding: 0.5em;
	display: block;
}
.ahl-8-3 {
	width: 100%;
	overflow-x: scroll;
	margin-bottom: 2em;
	padding: 0.5em;
	background-color: white;
}
.ahl-8-3-schedule {
	display: grid;
	grid-template-columns: [time] 15% [show] auto [grade] 10% [objective] 25% [end];
	grid-template-rows: auto;
	gap: 0;
	align-items: stretch;
	align-content: center;
	justify-items: stretch;
	justify-content: stretch;
	width: 800px;
}
.ahl-8-3-schedule div {
	font-family: 'PBSKids',var(--hpm-font-main);
	padding: 0.5em;
	display: flex;
	align-items: center;
}
.ahl-8-3-schedule > div:nth-child(-n+4) {
	font-weight: bolder;
	border-bottom: 1px solid black;
	font-size: 125%;
}
.ahl-8-3-schedule > div:nth-child(8n+5),
.ahl-8-3-schedule > div:nth-child(8n+6),
.ahl-8-3-schedule > div:nth-child(8n+7),
.ahl-8-3-schedule > div:nth-child(8n+8) {
	background-color: #ebebeb;
}
body.page.page-template-page-kids .kids-schedule.kids-ahl .ahl-8-3-schedule div a {
	text-decoration: none;
	color: #00b0bc;
}
body.page.page-template-page-kids .kids-schedule.kids-ahl a {
	text-decoration: none;
}
body.page.page-template-page-kids .kids-schedule {
	background-color: rgb(23,177,189);
	margin: 0;
	width: 100%;
}
.kids-schedule h1 {
	font-family: 'PBSKids',var(--hpm-font-main);
	color: white;
}
body.page.page-template-page-kids .kids-schedule p {
	color: white;
	padding-bottom: 1em;
	font-size: 112.5%;
}
body.page.page-template-page-kids .kids-schedule a {
	color: white;
	text-decoration: underline
}
body.page.page-template-page-kids .kids-schedule ul {
	list-style: disc outside none;
}
body.page.page-template-page-kids .kids-schedule ul li {
	padding-bottom: 0.5em;
}
body.page.page-template-page-kids .kids-schedule ul li a {
	color: rgb(23,177,189);
	text-decoration: none;
}
@media screen and (min-width: 34em) {
	.ahl-tile-wrap .ahl-tile {
		width: 45%;
		padding-bottom: calc(45%/1.1);
	}
}
@media screen and (min-width: 52.5em) {
	.lah-wrap {
		width: 100%;
	}
	.lah-schedule {
		overflow: visible;
	}
	.lah-schedule h3 {
		display: inline-block;
		float: left;
	}
	.lah-legend {
		width: 66%;
		float: right;
	}
	.ahl-tile-wrap .ahl-tile {
		width: 32%;
		padding-bottom: calc(32%/1);
	}
	.ahl-8-3 {
		width: 66%;
		overflow-x: visible;
		float: left;
	}
	.ahl-8-3-schedule {
		width: 100%;
	}
}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<?php the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' ); ?>
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/29132048/HPMKids-Logo-11.png" alt="Houston Public Media Kids" id="head-logo">
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/14164215/Cat-2.png" alt="Cat" id="head-cat">
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/14164220/Kids-2.png" alt="Ready Jet Go Kids" id="head-kids">
			</header>
			<?php
				the_content( );
			endwhile; ?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
