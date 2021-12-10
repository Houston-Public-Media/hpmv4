<?php
	$staff = get_post_meta( $wp_query->queried_object->ID, 'hpm_staff_meta', true );
	$staff_authid = get_post_meta( $wp_query->queried_object->ID, 'hpm_staff_authid', true );
	if ( !empty( $staff_authid ) ) :
		$curstaff = [
			'first_name' => get_the_author_meta( 'first_name', $staff_authid ),
			'last_name' => get_the_author_meta( 'last_name', $staff_authid ),
			'user_nicename' => get_the_author_meta( 'user_nicename', $staff_authid )
		];
	else :
		$staff_name = explode( ' ', $wp_query->queried_object->post_title );
		$staff_first_name = array_shift( $staff_name );
		$staff_last_name = implode( ' ', $staff_name );
		$curstaff = [
			'first_name' => $staff_first_name,
			'last_name' => $staff_last_name,
			'user_nicename' => $wp_query->queried_object->post_name
		];
	endif;
	get_header(); ?>
	<style>
		.author-info h3 {
			font-size: 1.125rem;
		}
		@media screen and (min-width: 64.25rem) {
			.page-header {
				grid-column: 1 / span 2;
				grid-row-start: -2;
			}
		}
	</style>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
			while ( have_posts() ) : the_post(); ?>
			<header class="page-header">
				<div id="author-wrap">
					<div class="author-info">
						<?PHP the_post_thumbnail( 'medium', [ 'alt' => get_the_title() ] ); ?>
						<h2><?php the_title(); ?></h2>
						<h3><?php echo $staff['title']; ?></h3>
			<?php
				if ( !empty( $staff ) ): ?>
						<div class="social-wrap">
				<?php
					if (!empty( $staff['facebook'] ) ) : ?>
							<div class="social-icon facebook">
								<a href="<?php echo $staff['facebook']; ?>" rel="noopener" title="<?php the_title(); ?> on Facebook" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a>
							</div>
			<?php
					endif;
					if ( !empty( $staff['twitter'] ) ) : ?>
							<div class="social-icon twitter">
								<a href="<?php echo $staff['twitter']; ?>" rel="noopener" title="<?php the_title(); ?> on Twitter" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
							</div>
			<?php
					endif;
					if ( !empty( $staff['linkedin'] ) ) : ?>
							<div class="social-icon linkedin">
								<a href="<?php echo $staff['linkedin']; ?>" rel="noopener" title="<?php the_title(); ?> on LinkedIn" target="_blank"><span class="fab fa-linkedin-in" aria-hidden="true"></span></a>
							</div>
			<?php
					endif;
					if ( !empty( $staff['email'] ) ) : ?>
							<div class="social-icon">
								<a href="mailto:<?php echo $staff['email']; ?>" title="Email <?php the_title(); ?>" target="_blank"><span class="fas fa-envelope" aria-hidden="true"></span></a>
							</div>
			<?php
					endif; ?>
						</div>
				<?php
						$author_bio = get_the_content();
						if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" ) :
							$author_bio = '';
						endif;
						echo apply_filters( 'hpm_filter_text', $author_bio );
				?>
					</div>
				</div>
			<?php
				endif; ?>
			</header>
		<?php
			endwhile; ?>
			<aside>
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
		<?php
			if ( !empty( $staff_authid ) && $staff_authid > 0 ) :
				$nice_name = get_the_author_meta( 'user_nicename', $staff_authid );
				$auth = new WP_query( [
					'author' => $staff_authid,
					'posts_per_page' => 15,
					'post_type' => 'post',
					'post_status' => 'publish'
				 ] );
				if ( $auth->have_posts() ) : ?>
			<section class="archive">
		<?php
					while ( $auth->have_posts() ) : $auth->the_post();
						get_template_part( 'content', get_post_format() );
					endwhile;
						wp_reset_postdata(); ?>
				<div class="readmore">
					<a href="/articles/author/<?php echo $nice_name; ?>/page/2">View More Stories</a>
				</div>
			</section>
				<?php
					endif;
				endif; ?>
		</main>
	</div>
<?php get_footer(); ?>
