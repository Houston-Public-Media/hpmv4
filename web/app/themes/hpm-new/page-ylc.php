<?php
/*
Template Name: Young Leaders Council
*/
get_header(); ?>
	<style>
		#div-gpt-ad-1488818411584-0,
		#foot-banner {
			display: none;
		}
		.page-template-page-ylc #content {
			width: 100%;
			margin: 0;
			max-width: 100%;
		}
		#main > article {
			grid-column: 1 / -1 !important;
			padding: 0 !important;
		}
		.page-template-page-ylc .page-header {
			flex-flow: row wrap;
			justify-content: center;
			align-content: center;
			align-items: center;
			display: flex;
			position: relative;
			background-color: transparent;
			background-repeat: no-repeat;
			background-size: cover;
			background-image: url('https://cdn.hpm.io/assets/images/graffiti_hero2x-mobile.jpg');
			background-position: center center;
		}
		.page-template-page-ylc .page-header h2 {
			color: white;
			text-align: center;
			font-size: 200%;
			margin-bottom: 1rem;
		}
		.page-template-page-ylc .page-header .page-title {
			color: white;
			text-align: center;
			font-size: 190%;
		}
		.page-template-page-ylc .page-header .header-logo {
			width: 55%;
			margin: 0 22.5% 0.25em;
		}
		.page-template-page-ylc .page-header .down {
			position: absolute;
			bottom: 0.125rem;
			text-align: center;
			display: block;
			width: 100%;
			color: white;
			left: 0;
			right: 0;
			font-size: 150%;
			border: 0;
			outline: 0;
			background-color: transparent;
		}
		.page-template-page-ylc .page-content {
			padding: 0;
		}
		.page-template-page-ylc .page-content section {
			padding: 2rem;
		}
		section h1 {
			color: #59595B;
			font-weight: 700;
		}
		section h1 span {
			border-bottom: 5px solid var(--main-red);
		}
		section p {
			font-weight: 100;
			font-size: 1.25rem;
			color: #59595B;
			margin-bottom: 1rem;
			word-wrap: break-word;
		}
		#ylc-intro img {
			margin-bottom: 1rem;
		}
		#ylc-intro h2 {
			color: #59595B;
			font-weight: 700;
			font-size: 1.5rem;
			margin-bottom: 1.25rem;
		}
		#ylc-intro h2 span {
			color: var(--main-red);
		}
		section .ylc-wrapper {
			overflow: hidden;
		}
		.ylc-grid-contain,
		.ylc-ambassadors-contain {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
		}
		.ylc-ambassadors-contain div {
			padding: 1rem;
			flex: 0 0 100%;
		}
		.page-template-page-ylc .page-content section#ylc-roster {
			padding: 2rem 0;
		}
		section#ylc-roster h1 {
			text-align: center;
			position: relative;
		}
		section#ylc-roster h1:before {
			content: '';
			position: absolute;
			bottom: -10px;
			width: 50%;
			left: 25%;
			border-bottom: 5px solid var(--main-red);
		}
		section .ylc-wrapper .ylc-roster-item {
			padding: 1rem;
			flex: 0 0 50%;
		}
		section .ylc-wrapper .ylc-roster-item:hover,
		#ylc-prev-class:hover {
			opacity: 0.75;
			transition: opacity .2s ease-out;
			cursor: pointer;
		}
		#ylc-prev-class {
			background-color: var(--main-red);
			color: white;
			font-weight: 700;
			font-size: 1.5rem;
			text-align: center;
			padding: 1rem;
			border-radius: 10px;
			width: 80%;
			margin: 1rem 10%;
		}
		.ylc-prev-class {
			display: none;
		}
		.ylc-prev-class.active {
			display: block;
		}
		#ylc-involve {
			background-color: transparent;
			background-repeat: no-repeat;
			background-size: cover;
			background-image: url('https://cdn.hpm.io/assets/images/deepened_involvement2x.jpg');
			background-position: center center;
		}
		section#ylc-involve {
			padding: 3rem 2rem;
		}
		section#ylc-involve h1 {
			color: white;
		}
		section#ylc-involve p,
		section#ylc-join p {
			color: white;
		}
		#ylc-join,
		#ylc-success {
			background-color: #59595B;
		}
		section#ylc-join h1,
		section#ylc-success h1 {
			text-align: center;
			color: white;
		}
		section#ylc-join p span,
		section#ylc-join p a,
		section#ylc-success p span,
		section#ylc-success p a {
			font-weight: 700;
		}
		section#ylc-join p.ylc-button {
			text-align: center;
		}
		section#ylc-join p.ylc-button a {
			text-align: center;
			width: 50%;
			color: white;
			background-color: #52C2DD;
			padding: 0.5em 1.125rem;
			font-weight: 500;
		}
		#ylc-success img {
			width: 50%;
			margin: 0 25% 0.5rem;
		}
		section#ylc-success p {
			color: white;
			text-align: center;
		}
		section#ylc-success .column-third {
			padding: 1rem;
		}
		#ylc-overlay {
			position: fixed;
			z-index: 10001;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			flex-flow: row nowrap;
			justify-content: center;
			align-content: center;
			align-items: center;
			background-color: rgba(0,0,0,0.5);
			display: none;
			opacity: 0;
		}
		#ylc-overlay.ylc-active {
			display: flex;
			opacity: 1;
			transition: opacity .2s ease-out;
		}
		#ylc-overlay #ylc-overlay-wrap {
			position: relative;
			padding: 0;
			width: 90%;
			background-color: #707070;
			border-radius: 8px;
		}
		#ylc-overlay-img img {
			vertical-align: bottom;
			width: 50%;
			margin: 0 25%;
		}
		#ylc-overlay-person {
			padding: 1rem;
		}
		#ylc-overlay-person h1 {
			margin: 0;
			color: white;
			font-weight: 700;
		}
		#ylc-overlay-person h3 {
			margin: 0;
			color: white;
			font-weight: 100;
			font-size: 1rem;
		}
		#ylc-overlay-quote {
			border-top: 1px solid white;
		}
		#ylc-overlay-quote blockquote {
			border: 0;
			padding: 1em;
			font-weight: 500;
			font-size: 1em;
			color: white;
			position: relative;
		}
		#ylc-overlay-quote blockquote:before {
			content: '“';
			position: absolute;
			top: 0;
			left: 0.125em;
			opacity: 0;
			color: #52C2DD;
			font-weight: 700;
			font-size: 3rem;
		}
		#ylc-overlay-controls {
			text-align: right;
			padding: 0.25rem 1rem 0.5rem;
			color: white;
			font-weight: 700;
			font-size: 1.125rem;
		}
		#ylc-overlay-controls span {
			color: #52C2DD;
		}
		#ylc-overlay-controls span:hover {
			opacity: 0.75;
			transition: opacity .2s ease-out;
			cursor: pointer;
		}
		#ylc-prev:before {
			content: '<';
			color: white;
			padding: 0 0.25rem 0 0;
		}
		#ylc-next:after {
			content: '>';
			color: white;
			padding: 0 0 0 0.25rem;
		}
		#ylc-close {
			position: absolute;
			top: 0;
			right: 0;
			z-index: 11000;
			color: white;
			font-size: 2.5rem;
			line-height: 1rem;
			padding: 0 0.125rem;
		}
		#ylc-close:hover {
			cursor: pointer;
		}
		#ylc-close .fa {
			font-size: 2.5rem;
		}
		@media screen and (min-width: 34em) {
			.page-template-page-ylc .page-header h2 {
				font-size: 250%;
			}
			.page-template-page-ylc .page-header .page-title {
				font-size: 280%;
			}
			.page-template-page-ylc .page-header .header-logo {
				width: 38%;
				margin: 0 31% 0.25rem;
			}
			#ylc-intro img {
				float: left;
				width: 50%;
				padding: 0 1rem 1rem 0;
			}
			.page-template-page-ylc .page-header .down {
				font-size: 200%;
			}
			section .ylc-wrapper .ylc-roster-item {
				flex: 0 0 25%;
			}
			section#ylc-roster h1:before {
				width: 25%;
				left: 37.5%;
			}
			section#ylc-involve h1,
			section#ylc-involve p {
				width: 50%;
			}
			.ylc-ambassadors-contain div {
				flex: 0 0 50%;
			}
			#ylc-prev-class {
				width: 60%;
				margin: 1em 20%;
			}
			#ylc-overlay #ylc-overlay-wrap {
				width: 97%;
				overflow: hidden;
			}
			#ylc-overlay .ylc-overlay-group {
				display: flex;
				flex-flow: row wrap;
				justify-content: center;
				align-content: center;
				align-items: center;
				float: left;
				width: 60%;
			}
			#ylc-overlay-img {
				width: 40%;
				float: left;
			}
			#ylc-overlay-img img {
				width: 100%;
				margin: 0;
			}
			#ylc-overlay-quote {
				width: 60%;
				float: left;
			}
			#ylc-overlay-quote blockquote {
				padding: 1em 2em 2em;
			}
			#ylc-overlay-quote blockquote:before {
				opacity: 0.5;
			}
			#ylc-overlay-controls {
				position: absolute;
				bottom: 0;
				right: 0;
			}
			#ylc-overlay-person {
				border-right: 1px solid white;
				width: 82%;
			}
			#ylc-close {
				position: static;
				top: 0;
				right: 0;
				background-color: transparent;
				width: 18%;
				font-size: 3rem;
				line-height: 1rem;
				padding: 0 0.25rem;
				text-align: center;
			}
		}
		@media screen and (min-width: 52.5em) {
			section .ylc-wrapper {
				max-width: 64rem;
				margin: 0 auto;
			}
			section#ylc-intro img {
				padding: 0 2rem 2rem 0;
			}
			section#ylc-involve h1,
			section#ylc-involve p {
				width: 40%;
			}
			section#ylc-join h1 {
				margin-bottom: 1rem;
			}
			section#ylc-join p {
				width: 80%;
				margin: 0 10% 1.5rem;
			}
			section#ylc-involve {
				padding: 4rem 2rem;
			}
			section#ylc-roster h1:before {
				width: 20%;
				left: 40%;
			}
			#ylc-prev-class {
				width: 40%;
				margin: 1rem 30%;
			}
			.page-template-page-ylc .page-header h2 {
				font-size: 325%;
				margin-bottom: 0.75rem;
			}
			.page-template-page-ylc .page-header .header-logo {
				width: 35%;
				margin: 0 32.5% 0.25rem;
				max-width: 20rem;
			}
			.page-template-page-ylc .page-header .page-title {
				font-size: 300%;
			}
			#ylc-overlay #ylc-overlay-wrap {
				width: 73%;
			}
			section#ylc-roster h1 {
				margin-bottom: 1rem;
			}
		}
		@media screen and (min-width: 64.0625em) {
			section .ylc-wrapper {
				max-width: 75rem;
			}
			section#ylc-involve .ylc-wrapper {
				padding-left: 2rem;
			}
			section#ylc-involve {
				padding: 7rem 2rem;
			}
			.page-template-page-ylc .page-header h2 {
				font-size: 400%;
				margin-bottom: 0.75rem;
			}
			.page-template-page-ylc .page-header .header-logo {
				width: 27%;
				margin: 0 36.5% 0.25rem;
			}
			#ylc-overlay #ylc-overlay-wrap {
				width: 52%;
				max-width: 46.75rem;
			}
			.ylc-form div.wpforms-container-full {
				width: 60%;
			}
			section#ylc-intro {
				padding-top: 3rem;
			}
			section#ylc-intro img {
				padding: 0 2rem 0 0;
			}
			section#ylc-intro h2 {
				font-size: 207%;
			}
			section#ylc-intro p {
				font-size: 150%;
				padding-right: 2rem;
			}
			section .ylc-wrapper .ylc-roster-item {
				flex: 0 0 16.666667%;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?PHP while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="page-header">
						<h2><?php echo get_the_excerpt(); ?></h2>
						<div class="header-logo">
							<a href="/" rel="home" title="Houston Public Media homepage"><img src="https://cdn.hpm.io/assets/images/HPM-PBS-NPR-Reverse.png" alt="Houston Public Media, a service of the University of Houston" /></a>
						</div>
						<h1 class="page-title"><?php the_title(); ?></h1>
						<button class="down scrollto">
							<i class="fas fa-chevron-down" aria-hidden="true"></i>
						</button>
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
	<script>
		function modalSwitch(dataId,modal) {
			var dIndexSp = dataId.split('-');
			var dInt = parseInt(dIndexSp[2]);
			var roster = document.querySelectorAll('.'+dIndexSp[0]+'-'+dIndexSp[1]);
			if ( dInt - 1 == 0 ) {
				var prev = roster.length;
			} else {
				var prev = dInt - 1;
			}
			if ( dInt + 1 == roster.length + 1 ) {
				var next = 1;
			} else {
				var next = dInt + 1;
			}
			var current = document.querySelector('#'+dataId);
			document.querySelector('#ylc-prev').setAttribute('data-item', 'ylc-'+dIndexSp[1]+'-'+prev);
			document.querySelector('#ylc-next').setAttribute('data-item', 'ylc-'+dIndexSp[1]+'-'+next);
			var name = current.getAttribute('data-name');
			var title = current.getAttribute('data-title');
			var quote = current.getAttribute('data-quote');
			var fTitle = title.replace(/\|\|/g, '<br />');
			document.querySelector('#ylc-overlay-person > h1').innerHTML = name;
			document.querySelector('#ylc-overlay-person > h3').innerHTML = fTitle;
			document.querySelector('#ylc-overlay-quote > blockquote').innerHTML = quote;
			var image = current.children[0];
			document.querySelector('#ylc-overlay-img').innerHTML = '<img src="'+image.getAttribute('src')+'" alt="'+image.getAttribute('alt')+'" title="'+image.getAttribute('title')+'">';
			if (modal) {
				document.querySelector('#ylc-overlay').classList.add('ylc-active');
			}
		}
		document.addEventListener('DOMContentLoaded', () => {
			var main = document.querySelector('#main').getBoundingClientRect();
			var winhigh = window.innerHeight;
			var header_height = winhigh - main.top - window.scrollY;
			document.querySelector('.page-template-page-ylc .page-header').style.height = header_height+'px';
			document.querySelector('button.down').addEventListener('click', (e) => {
				e.preventDefault();
				var offset = document.querySelector('.page-content').offsetTop;
				window.scrollTo(0, offset-(4*16));
			});
			Array.from(document.querySelectorAll('#ylc-close,#ylc-overlay')).forEach((navC) => {
				navC.addEventListener('click', (e) => {
					e.preventDefault();
					document.querySelector('#ylc-overlay').classList.remove('ylc-active');
				});
			});
			document.querySelector('#ylc-overlay-wrap').addEventListener('click', (e) => {
				e.stopPropagation();
			});
			Array.from(document.querySelectorAll('#ylc-next,#ylc-prev')).forEach((navB) => {
				navB.addEventListener('click', (e) => {
					e.preventDefault();
					e.stopPropagation();
					modalSwitch(navB.getAttribute('data-item'), false);
				});
			});
			var members = document.querySelectorAll('.ylc-roster-item');
			Array.from(members).forEach((member) => {
				member.addEventListener('click', (e) => {
					modalSwitch(member.id, true);
				});
			});
			document.querySelector('#ylc-prev-class').addEventListener('click', (e) => {
				document.querySelector('.ylc-prev-class').classList.toggle('active');
			});
			document.addEventListener('keyup', (e) => {
				if (document.querySelector('#ylc-overlay').classList.contains('ylc-active')) {
					if (e.which == 37) {
						console.log( 'Keyboard Previous' );
						var dIndex = document.querySelector('#ylc-prev').getAttribute('data-item');
						modalSwitch(dIndex,false);
					} else if (e.which == 39) {
						console.log( 'Keyboard Next' );
						var dIndex = document.querySelector('#ylc-next').getAttribute('data-item');
						modalSwitch(dIndex,false);
					}
				}
			});
		});
	</script>
<?php get_footer(); ?>