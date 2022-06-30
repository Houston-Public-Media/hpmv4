<?php
	wp_deregister_style( 'gutenberg-pdfjs' );
	wp_deregister_style( 'wp-block-library' );
	wp_deregister_style( 'wp-block-library-theme' );
	wp_deregister_style( 'wpforms-gutenberg-form-selector' );
?><!doctype html>
<html amp <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		<?php $this->load_parts( [ 'style' ] ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>
<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?> single">
	<div class="container">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<div class="site-logo">
					<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston"><svg data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true" class="hpm-logo"><text class="hpm-logo-text" x="0" y="68">Houston Public Media</text><text class="hpm-logo-service" x="5" y="130">A SERVICE OF THE UNIVERSITY OF HOUSTON</text><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"></polygon><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"></polygon><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"></polygon><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"></path><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"></path><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"></path><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"></path><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"></path><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"></path><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"></path><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"></path></svg></a>
				</div>
				<section>
					<div id="top-schedule">
						<div class="top-schedule-label"><button aria-label="View Schedules" type="button" aria-expanded="false" aria-controls="top-schedule-link-wrap"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M68.6,199.1h373.8c5.8,0,10.6,4.8,10.6,10.6v229.2c0,23.4-19,42.3-42.3,42.3H100.3c-23.4,0-42.3-19-42.3-42.3 V209.7C58,203.8,62.8,199.1,68.6,199.1z M453,160.3v-31.7c0-23.4-19-42.3-42.3-42.3h-42.3V40.4c0-5.8-4.8-10.6-10.6-10.6h-35.3 c-5.8,0-10.6,4.8-10.6,10.6v45.8H199.1V40.4c0-5.8-4.8-10.6-10.6-10.6h-35.3c-5.8,0-10.6,4.8-10.6,10.6v45.8h-42.3 c-23.4,0-42.3,19-42.3,42.3v31.7c0,5.8,4.8,10.6,10.6,10.6h373.8C448.2,170.9,453,166.1,453,160.3z"></path></svg>Schedules</button></div>
						<div class="top-schedule-link-wrap" id="top-schedule-link-wrap">
							<div class="top-schedule-links"><a href="/tv8">TV 8</a></div>
							<div class="top-schedule-links"><a href="/news887">News 88.7</a></div>
							<div class="top-schedule-links"><a href="/classical">Classical</a></div>
							<div class="top-schedule-links"><a href="/mixtape">Mixtape</a></div>
						</div>
					</div>
					<div id="top-listen"><button aria-label="Listen Live" data-href="/listen-live" data-dialog="480:855"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M256,340.5c46.7,0,84.5-37.9,84.5-84.5V115.1c0-46.7-37.9-84.5-84.5-84.5s-84.5,37.9-84.5,84.5V256 C171.5,302.7,209.3,340.5,256,340.5z M396.9,199.6h-14.1c-7.8,0-14.1,6.3-14.1,14.1V256c0,65.9-56.8,118.7-124,112.2 c-58.6-5.7-101.5-58.4-101.5-117.2v-37.3c0-7.8-6.3-14.1-14.1-14.1h-14.1c-7.8,0-14.1,6.3-14.1,14.1v35.4 c0,78.9,56.3,149.3,133.9,160v30.1h-49.3c-7.8,0-14.1,6.3-14.1,14.1v14.1c0,7.8,6.3,14.1,14.1,14.1h140.9c7.8,0,14.1-6.3,14.1-14.1 v-14.1c0-7.8-6.3-14.1-14.1-14.1h-49.3v-29.7C352.6,399.1,411,334.3,411,256v-42.3C411,205.9,404.7,199.6,396.9,199.6z"></path></svg>Listen</button></div>
					<div id="top-watch"><button aria-label="Watch Live" data-href="/watch-live" data-dialog="820:850"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M448.1,74.7H63.9C45.2,74.7,30,89.9,30,108.6v226c0,18.7,15.2,33.9,33.9,33.9h169.5v22.6H109.1 c-6.2,0-11.3,5.1-11.3,11.3V425c0,6.2,5.1,11.3,11.3,11.3h293.8c6.2,0,11.3-5.1,11.3-11.3v-22.6c0-6.2-5.1-11.3-11.3-11.3H278.6 v-22.6h169.5c18.7,0,33.9-15.2,33.9-33.9v-226C482,89.9,466.8,74.7,448.1,74.7z M436.8,323.3H75.2V119.9h361.6V323.3z"></path></svg>Watch</button></div>
				</section>
				<div id="top-donate"><a href="/donate"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"><path d="M438.1,85.3c-48.4-41.2-120.3-33.8-164.7,12L256,115.2l-17.4-17.9c-44.3-45.8-116.4-53.2-164.7-12 c-55.4,47.3-58.4,132.2-8.7,183.5L236,445.2c11,11.4,29,11.4,40,0l170.8-176.4C496.5,217.5,493.6,132.6,438.1,85.3L438.1,85.3z"></path></svg><br><span class="top-mobile-text">Donate</span></a></div>
			</div>
		</header>
	</div>
	<div id="page" class="hfeed site">
		<div id="content" class="site-content">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<?PHP while ( have_posts() ) : the_post(); $postClass = get_post_class(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php echo hpm_pub_time_banner( get_the_time( 'U' ) ); ?>
							<h3><?php echo hpm_top_cat( get_the_ID() ); ?></h3>
							<?php
								the_title('<h1 class="entry-title">', '</h1>');
								the_excerpt();
								$single_id = get_the_ID();
							?>
							<div class="byline-date">
								<?PHP
								coauthors_posts_links(' / ', ' / ', '<address class="vcard author">', '</address>', true);
								echo " | ";
								$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
								$pub = get_the_time( 'U' );
								$mod = get_the_modified_time( 'U' );
								$desc = $mod - $pub;
								$mod_time = get_post_meta( $single_id, 'hpm_no_mod_time', true );
								if ( $pub !== $mod && $desc > 900 && $mod > $pub && $mod_time == 0 ) :
									$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> (Last Updated: <time class="updated" datetime="%3$s">%4$s</time>)';
								endif;

								$time_string = sprintf(
									$time_string,
									esc_attr( get_the_date('c') ),
									get_the_date(),
									esc_attr( get_the_modified_date('c') ),
									get_the_modified_date()
								);

								printf(
									'<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
									_x('Posted on', 'Used before publish date.', 'hpmv4'),
									$time_string
								);
								?>
							</div>
						</header>
						<div class="entry-content">
							<?php the_content(); ?>
						</div>
						<footer class="entry-footer">
							<div class="tags-links">
							<?PHP
								$cat_list = get_the_category_list( ' ', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
								if ( $cat_list ) :
									echo $cat_list;
								endif;
								$tags_list = get_the_tag_list( '', _x( ' ', 'Used between list items, there is a space after the comma.', 'hpmv4' ) );
								if ( $tags_list ) :
									echo $tags_list;
								endif;
								edit_post_link( __( 'Edit', 'hpmv4' ), '<span class="edit-link">', '</span>' );
							?>
							</div>
						</footer>
					</article>
					<?php endwhile; ?>
					<aside class="column-right">
					<?php
						global $post;
						if ( !empty( $post ) ) :
							$tags = wp_get_post_tags( $post->ID );
							if ( $tags ) :
								$tag_ids = [];
								foreach( $tags as $individual_tag ) :
									$tag_ids[] = $individual_tag->term_id;
								endforeach;
								$args = [
									'tag__in' => $tag_ids,
									'post__not_in' => [ $post->ID ],
									'posts_per_page'=> 4,
									'ignore_sticky_posts'=> 1
								];
								$my_query = new wp_query( $args );
								if ( $my_query->have_posts() ) : ?>
						<section class="highlights">
							<h4>Related</h4>
							<?php
									while( $my_query->have_posts() ) :
										$my_query->the_post();
										get_template_part( 'content', get_post_format() );
									endwhile; ?>
						</section>
					<?php
								endif;
								wp_reset_query();
							endif;
						endif;
						hpm_top_posts(); ?>
					</aside>
					<div id="author-wrap">
						<?php echo author_footer( get_the_ID() ); ?>
					</div>
				</main>
			</div>
		</div>
		<footer id="colophon" class="site-footer" role="contentinfo">
			<section>
				<div class="foot-logo">
					<?php echo hpm_svg_output( 'hpm' ); ?>
				</div>
				<div class="foot-hpm">
					<h3>Houston Public Media</h3>
					<nav id="second-navigation" class="footer-navigation" role="navigation">
						<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 1956 ] ); ?>
						<div class="clear"></div>
					</nav>
				</div>
				<div class="foot-comply">
					<h3>Compliance</h3>
					<nav id="third-navigation" class="footer-navigation" role="navigation">
						<?php wp_nav_menu( [ 'menu_class' => 'nav-menu', 'menu' => 42803 ] ); ?>
						<div class="clear"></div>
					</nav>
				</div>
				<div class="foot-newsletter">
					<h3>Subscribe to Our Newsletters</h3>
					<h4><a href="https://www.houstonpublicmedia.org/news/today-in-houston-newsletter/">Today in Houston</a></h4>
					<p>Let the Houston Public Media newsroom help you start your day.</p>
					<h4><a href="https://www.houstonpublicmedia.org/support/newslettereguide-signup/">This Week</a></h4>
					<p>Get highlights, trending news, and behind-the-scenes insights from Houston Public Media delivered to your inbox each week.</p>
				</div>
				<div class="foot-contact">
					<p class="foot-button"><a href="/contact-us/">Contact Us</a></p>
					<p>4343 Elgin, Houston, TX 77204-0008</p>
					<div class="social-wrap">
					<div class="social-icon facebook">
							<a href="https://www.facebook.com/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'facebook' ); ?></a>
						</div>
						<div class="social-icon twitter">
							<a href="https://twitter.com/houstonpubmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'twitter' ); ?></svg></a>
						</div>
						<div class="social-icon instagram">
							<a href="https://instagram.com/houstonpubmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'instagram' ); ?></a>
						</div>
						<div class="social-icon youtube">
							<a href="https://www.youtube.com/user/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'youtube' ); ?></a>
						</div>
						<div class="social-icon linkedin">
							<a href="https://linkedin.com/company/houstonpublicmedia" rel="noopener" target="_blank"><?php echo hpm_svg_output( 'linkedin' ); ?></a>
						</div>
					</div>
				</div>
			</section>
			<div class="foot-tag">
				<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the <a href="https://www.uh.edu" rel="noopener" target="_blank">University of Houston</a></p>
				<p>Copyright &copy; <?php echo date('Y'); ?></p>
			</div>
		</footer>
	</div>
	<?php do_action( 'amp_post_template_footer', $this ); ?>
	<amp-analytics type="chartbeat">
		<script type="application/json">
			{
				"vars": {
					"uid": "33583",
					"domain": "houstonpublicmedia.org",
					"sections": "<?php echo str_replace( '&amp;', '&', wp_strip_all_tags( get_the_category_list( ', ', 'multiple', $this->ID ) ) ); ?>",
					"authors": "<?php coauthors( ',', ',', '', '', true ); ?>",
					"title": "<?php echo wp_kses_data( $amp_title ); ?>",
					"canonicalPath": "<?php echo get_the_permalink(); ?>"
				}
			}
		</script>
	</amp-analytics>
</body>
</html>