<?php
/**
 * The template for displaying show pages
 *
 * @package WordPress
 * @subpackage HPMv4
 * @since HPMv4 4.0
 */
global $post;
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php
	$aurthorName = "";
	while ( have_posts() ) {
		the_post();
		$aurthorName = $post->post_name;
		$staff = get_post_meta( get_the_ID(), 'hpm_staff_meta', true );
		$staff_authid = get_post_meta( get_the_ID(), 'hpm_staff_authid', true );
		$staff_pic = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' ); ?>
			<header class="page-header">
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-4">
<?php
		if ( !empty( $staff_pic ) ) { ?>
								<img src="<?PHP	echo $staff_pic[0]; ?>" class="author-thumb"  alt="<?php the_title(); ?>"/>
<?php
								} ?>
							</div>
							<div class="col-8">
								<h1 class="entry-title"><?php the_title(); ?></h1>
								<?php echo ( !empty( $staff['pronouns'] ) ? '<p class="staff-pronouns">' . $staff['pronouns'] . '</p>' : '' ); ?>
								<h3><?php echo $staff['title']; ?></h3>
<?php
		if ( !empty( $staff ) ) { ?>
								<div class="icon-wrap">
<?php
			if ( !empty( $staff['phone'] ) ) { ?>
									<div class="service-icon phone">
										<a href="tel://+1<?php echo str_replace( [ '(', ')', ' ', '-', '.' ], [ '', '', '', '', '' ], $staff['phone'] ); ?>" title="Call <?php the_title(); ?> at <?php echo $staff['phone']; ?>" data-phone="<?php echo $staff['phone']; ?>"><?php echo hpm_svg_output( 'phone' ); ?><span class="screen-reader-text" >Call</span></a>
									</div>
<?php
			}
			if ( !empty( $staff['facebook'] ) ) { ?>
									<div class="service-icon facebook">
										<a href="<?php echo $staff['facebook']; ?>" target="_blank"><?php echo hpm_svg_output( 'facebook' ); ?><span class="screen-reader-text" >Facebook</span></a>
									</div>
<?php
			}
			if ( !empty( $staff['twitter'] ) ) { ?>
									<div class="service-icon twitter">
										<a href="<?php echo $staff['twitter']; ?>" target="_blank"><?php echo hpm_svg_output( 'twitter' ); ?><span class="screen-reader-text" >Twitter</span></a>
									</div>
<?php
			}
			if ( !empty( $staff['linkedin'] ) ) { ?>
									<div class="service-icon linkedin">
										<a href="<?php echo $staff['linkedin']; ?>" target="_blank"><?php echo hpm_svg_output( 'linkedin' ); ?><span class="screen-reader-text" >Linkedln</span></a>
									</div>
<?php
			}
			if ( !empty( $staff['email'] ) ) { ?>
									<div class="service-icon envelope">
										<a href="mailto:<?php echo $staff['email']; ?>" target="_blank"><?php echo hpm_svg_output( 'envelope' ); ?><span class="screen-reader-text" >Email</span></a>
									</div>
<?php
			} }?>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="staff-bio">
<?php
		if ( !empty( $staff ) ) {
			$author_bio = get_the_content();
			if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" ) {
				$author_bio = '';
			}
			echo apply_filters( 'hpm_filter_text', $author_bio ); ?>
				</div>
<?php
		} ?>
			</header>
<?php
	} ?>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
<?php
	if ( !empty( $staff_authid ) && $staff_authid > 0 ) {
		$nice_name = get_the_author_meta( 'user_nicename', $staff_authid );
		$auth = new WP_Query([
			'author' => $staff_authid,
			'posts_per_page' => 15,
			'post_type' => 'post',
			'post_status' => 'publish'
			] );
		if ( $auth->have_posts() ) { ?>
			<section id="search-results">
<?php
			while ( $auth->have_posts() ) {
				$auth->the_post();
				get_template_part( 'content', get_post_type() );
			}
			wp_reset_postdata();
			echo hpm_custom_pagination( $auth->max_num_pages, 4, "/articles/author/" . $aurthorName . "/page/" ); ?>
			</section>
<?php
		}
	} ?>
		</main>
	</div>
<?php get_footer(); ?>