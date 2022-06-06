<?php
/*
Template Name: Young Leaders Council
*/
get_header(); ?>
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
							<?php echo hpm_svg_output( 'chevron-down' ) ?>
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