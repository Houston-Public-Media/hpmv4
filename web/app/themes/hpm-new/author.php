<?php
	$curauth = $wp_query->queried_object;
	if ( is_a( $curauth, 'wp_user' ) ) :
		$author_check = new WP_Query( [
			'post_type' => 'staff',
			'post_status' => 'publish',
			'meta_query' => [ [
				'key' => 'hpm_staff_authid',
				'compare' => '=',
				'value' => $curauth->ID
			] ]
		] );
	elseif ( !empty( $curauth->type ) && $curauth->type == 'guest-author' ) :
		if ( !empty( $curauth->linked_account ) ) :
			$authid = get_user_by( 'login', $curauth->linked_account );
			$author_check = new WP_Query( [
				'post_type' => 'staff',
				'post_status' => 'publish',
				'meta_query' => [ [
					'key' => 'hpm_staff_authid',
					'compare' => '=',
					'value' => $authid->ID
				] ]
			] );
		else :
			$author_check = '';
		endif;
	else :
		$author_check = '';
	endif;
	get_header();
?>
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
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="page-header">
				<div id="author-wrap">
		<?php
			if ( !empty( $author_check ) && is_a( $author_check, 'wp_query' ) ) :
				if ( $author_check->have_posts() ) :
					while ( $author_check->have_posts() ) :
						$author_check->the_post();
						$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE ); ?>
					<div class="author-info">
						<?PHP the_post_thumbnail( 'medium', [ 'alt' => get_the_title() ] ); ?>
						<h2><?php echo $curauth->display_name; ?></h2>
						<h3><?php echo $author['title']; ?></h3>
				<?php
						if ( !empty($author['facebook'] ) || !empty( $author['twitter'] ) ) :?>
						<div class="social-wrap">
					<?php
							if ( !empty( $author['facebook'] ) ) : ?>
							<div class="social-icon facebook">
								<a href="<?php echo $author['facebook']; ?>" rel="noopener" title="<?php echo $curauth->display_name; ?> on Facebook" target="_blank"><span class="fab fa-facebook-f" aria-hidden="true"></span></a>
							</div>
				<?php
							endif;
							if ( !empty( $author['twitter'] ) ) : ?>
							<div class="social-icon twitter">
								<a href="<?php echo $author['twitter']; ?>" rel="noopener" title="<?php echo $curauth->display_name; ?> on Twitter" target="_blank"><span class="fab fa-twitter" aria-hidden="true"></span></a>
							</div>
				<?php
							endif;
							if ( !empty( $author['linkedin'] ) ) : ?>
								<div class="social-icon linkedin">
									<a href="<?php echo $author['linkedin']; ?>" rel="noopener" title="<?php echo $curauth->display_name; ?> on LinkedIn" target="_blank"><span class="fab fa-linkedin-in" aria-hidden="true"></span></a>
								</div>
					<?php
								endif;
							if (!empty($author['email'])) : ?>
							<div class="social-icon">
								<a href="mailto:<?php echo $author['email']; ?>" rel="noopener" title="Email <?php echo $curauth->display_name; ?>" target="_blank"><span class="fas fa-envelope" aria-hidden="true"></span></a>
							</div>
				<?php
							endif; ?>
						</div>
				<?php
						endif; ?>
				<?php
	                    $author_bio = get_the_content();
	                    if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" ) :
	                        $author_bio = '';
	                    endif;
	                    echo apply_filters( 'hpm_filter_text', $author_bio ); ?>
					</div>
			<?php
					endwhile;
				else : ?>
					<h2><?php echo $curauth->display_name; ?></h2>
			<?php
					if ( !empty( $curauth->user_email ) || !empty( $curauth->website ) ) : ?>
					<ul>
			<?php
						if ( !empty( $curauth->website ) ) : ?>
						<li><a href="<?php echo $curauth->website; ?>" target="_blank">More from this author</a></li>
			<?php
						endif;
						if ( !empty( $curauth->user_email ) ) : ?>
						<li><a href="mailto:<?php echo $curauth->user_email; ?>">Contact this author</a></li>
			<?php
						endif; ?>
					</ul>
		<?php
					endif;
				endif;
			else : ?>
					<h2><?php echo $curauth->display_name; ?></h2>
			<?php
					if ( !empty( $curauth->user_email ) || !empty( $curauth->website ) ) : ?>
					<ul>
			<?php
						if ( !empty( $curauth->website ) ) : ?>
						<li><a href="<?php echo $curauth->website; ?>" target="_blank">More from this author</a></li>
			<?php
						endif;
						if ( !empty( $curauth->user_email ) ) : ?>
						<li><a href="mailto:<?php echo $curauth->user_email; ?>">Contact this author</a></li>
			<?php
						endif; ?>
					</ul>
		<?php
					endif;

			endif;
			wp_reset_query(); ?>
				</div>
			</header>
			<aside>
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<section class="archive">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_format() );
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text' => __( '&lt;', 'hpmv2' ),
				'next_text' => __( '&gt;', 'hpmv2' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv2' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );
		endif; ?>
			</section>
		</main>
	</section>
<?php get_footer(); ?>
