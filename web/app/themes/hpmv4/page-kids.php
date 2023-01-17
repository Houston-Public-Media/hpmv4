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
		@media screen and (min-width: 30.0625em) {
			#station-module {
				height: 650px;
			}
			.category-list-wrapper p {
				font-size: 1.5em;
			}
		}
		@media screen and (min-width: 52.5em) {
			#station-module {
				height: 750px;
			}
			.category-list-wrapper p {
				font-size: 2em;
			}
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
		.column-right.kids-sidebar {
			background-color: var(--main-element-background);
			padding: 2em 1em 1em;
			margin: 0;
			width: 100%;
		}
		.column-right.kids-sidebar .sidebar-ad {
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
		body.page.page-template-page-kids table {
			width: 100%;
			background-color: white;
			margin-bottom: 1em;
		}
		body.page.page-template-page-kids table tr td,
		body.page.page-template-page-kids table tr th {
			padding: 0.5em;
			margin: 0;
			text-align: center;
		}
		body.page.page-template-page-kids table tbody tr td:nth-child(1) {
			width: 25%;
			color: rgb(37,158,163);
			font: normal 1.25em/1.25em 'PBSKids',arial,sans-serif;
		}
		body.page.page-template-page-kids table tbody tr td:nth-child(2) {
			width: 75%;
			font: 400 1.25em/1.25em var(--hpm-font-main);
		}
		body.page.page-template-page-kids table thead tr {
			background-color: rgb(37,158,163);
		}
		body.page.page-template-page-kids table thead tr th {
			color: white;
			font: normal 1.25em/1.25em 'PBSKids',var(--hpm-font-main);
		}
		body.page.page-template-page-kids table tbody tr:nth-child(2n+0) {
			background-color: rgb(218,236,234);
		}
		body.page.page-template-page-kids table tbody tr:nth-child(2n+1) > td:nth-child(1) {
			background-color: rgb(197,224,222);
		}
		body.page.page-template-page-kids .kids-schedule {
			background-color: rgb(23,177,189);
			margin: 0;
			width: 100%;
		}
		.kids-schedule h1 {
			width: 90%;
			margin: 0.5em 5%;
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
		body.page.page-template-page-kids .kids-schedule .card.card-medium :is(a,p,ul,ul li) {
			color:#55565a;
			text-decoration: none;
			margin-bottom: 0;
			font-size: 100%;
		}
		body.page.page-template-page-kids .column-left {
			display: block !important;
		}
		.kids-schedule.kids-ahl h1 {
			margin-top: 0;
			padding-top: 0.5rem;
		}
		@media screen and (min-width: 34em) {
			.kids-schedule .column-left article.card.card-medium .thumbnail-wrap {
				order: initial;
			}
			.kids-schedule .column-left article.card.card-medium header {
				order: initial;
				padding: 0 0 0 1em;
			}
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
			.column-right.kids-sidebar {
				margin: 0 1% 1em;
				padding: 1em;
				width: 31.5%;
			}
			.kids-schedule h1 {
				width: 100%;
				margin: 0.5em 0;
			}
			.kids-schedule.kids-ahl h1 {
				margin-top: 0;
			}
			body.page.page-template-page-kids table {
				width: 50%;
				margin: 0 25% 1em;
			}
			body.page.page-template-page-kids .kids-schedule {
				padding: 0 1em;
			}
		}
		@media screen and (min-width: 64.0625em) {
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
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) {
				the_post(); ?>
			<header class="page-header">
				<?php the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' ); ?>
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/29132048/HPMKids-Logo-11.png" alt="Houston Public Media Kids" id="head-logo">
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/14164215/Cat-2.png" alt="Cat" id="head-cat">
				<img src="https://cdn.hpm.io/wp-content/uploads/2016/01/14164220/Kids-2.png" alt="Ready Jet Go Kids" id="head-kids">
			</header>
			<?php
				the_content( );
			} ?>
		</main>
	</div>
<?php get_footer(); ?>
