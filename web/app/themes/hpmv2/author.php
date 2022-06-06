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
					<div class="author-wrap-left">
						<?PHP the_post_thumbnail( 'medium', array( 'alt' => get_the_title(), 'class' => 'author-thumb' ) ); ?>
						<h1 class="entry-title"><?php echo $curauth->display_name; ?></h1>
						<h3><?php echo $author['title']; ?></h3>
				<?php
						if (
							!empty( $author['facebook'] ) ||
							!empty( $author['twitter'] ) ||
							!empty( $author['linkedin'] ) ||
							!empty( $author['email'] )
						) : ?>
						<div class="social-wrap">
					<?php
							if ( !empty( $author['facebook'] ) ) : ?>
							<div class="social-icon facebook">
								<a href="<?php echo $author['facebook']; ?>" target="_blank"><?php echo hpm_svg_output( 'facebook' ); ?></a>
							</div>
				<?php
							endif;
							if ( !empty( $author['twitter'] ) ) : ?>
							<div class="social-icon twitter">
								<a href="<?php echo $author['twitter']; ?>" target="_blank"><?php echo hpm_svg_output( 'twitter' ); ?></a>
							</div>
				<?php
							endif;
							if ( !empty( $author['linkedin'] ) ) : ?>
								<div class="social-icon linkedin">
									<a href="<?php echo $author['linkedin']; ?>" target="_blank"><?php echo hpm_svg_output( 'linkedin' ); ?></a>
								</div>
				<?php
							endif;
							if ( !empty( $author['email'] ) ) : ?>
							<div class="social-icon envelope">
								<a href="mailto:<?php echo $author['email']; ?>" target="_blank"><?php echo hpm_svg_output( 'envelope' ); ?></a>
							</div>
				<?php
							endif; ?>
						</div>
				<?php
						endif; ?>
					</div>
					<div class="author-info-wrap">
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
					<h1 class="entry-title"><?php echo $curauth->display_name; ?></h1>
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
					<h1 class="entry-title"><?php echo $curauth->display_name; ?></h1>
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
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<section id="search-results">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				get_template_part( 'content', get_post_type() );
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
