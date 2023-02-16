<?php
	$curauth = $wp_query->get_queried_object();
	if ( is_a( $curauth, 'wp_user' ) ) {
		$author_check = new WP_Query( [
			'post_type' => 'staff',
			'post_status' => 'publish',
			'meta_query' => [ [
				'key' => 'hpm_staff_authid',
				'compare' => '=',
				'value' => $curauth->ID
			] ]
		] );
	} elseif ( !empty( $curauth->type ) && $curauth->type == 'guest-author' ) {
		if ( !empty( $curauth->linked_account ) ) {
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
		} else {
			$author_check = '';
		}
	} else {
		$author_check = '';
    }
	get_header();
?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="page-header">
				<div id="author-wrap">
		<?php
			if ( !empty( $author_check ) && is_a( $author_check, 'wp_query' ) ) {
				if ( $author_check->have_posts() ) {
					while ( $author_check->have_posts() ) {
						$author_check->the_post();
						$author = get_post_meta( get_the_ID(), 'hpm_staff_meta', TRUE ); ?>
					<div class="author-wrap-left">
						<?PHP the_post_thumbnail( 'medium', array( 'alt' => get_the_title(), 'class' => 'author-thumb' ) ); ?>
						<h1 class="entry-title"><?php echo $curauth->display_name; ?></h1>
						<?php // echo ( !empty( $author['pronouns'] ) ? '<p class="staff-pronouns">(' . $author['pronouns'] . ')</p>' : '' ) ?>
						<h3><?php echo $author['title']; ?></h3>
				<?php
						if (
							!empty( $author['facebook'] ) ||
							!empty( $author['twitter'] ) ||
							!empty( $author['linkedin'] ) ||
							!empty( $author['email'] )
						) { ?>
						<div class="icon-wrap">
						<?php
							echo ( !empty( $staff['phone'] ) ? '<div class="service-icon phone"><a href="tel://+1' . str_replace( [ '(', ')', ' ', '-', '.' ], [ '', '', '', '', '' ], $staff['phone'] ) . '" title="Call ' . $curauth->display_name . ' at ' . $staff['phone'] . '">' . hpm_svg_output( 'phone' ) . '</a></div>' : '' );
							echo ( !empty( $author['facebook'] ) ? '<div class="service-icon facebook"><a href="' . $author['facebook'] . '" target="_blank">' . hpm_svg_output( 'facebook' ) . ' </a></div>' : '' );
							echo ( !empty( $author['twitter'] ) ? '<div class="service-icon twitter"><a href="' . $author['twitter'] . '" target="_blank">' . hpm_svg_output( 'twitter' ) . '</a></div>' : '' );
							echo ( !empty( $author['linkedin'] ) ? '<div class="service-icon linkedin"><a href="' . $author['linkedin'] . '" target="_blank">' . hpm_svg_output( 'linkedin' ) . '</a></div>' : '' );
							echo ( !empty( $author['email'] ) ? '<div class="service-icon envelope"><a href="mailto:' . $author['email'] . '" target="_blank">' . hpm_svg_output( 'envelope' ) . '</a></div>' : '' );
						} ?>
						</div>
					</div>
					<div class="author-info-wrap">
				<?php
						$author_bio = get_the_content();
						if ( $author_bio == "<p>Biography pending.</p>" || $author_bio == "<p>Biography pending</p>" ) {
							$author_bio = '';
						}
						echo apply_filters( 'hpm_filter_text', $author_bio ); ?>
					</div>
			<?php
					}
				} else { ?>
					<h1 class="entry-title"><?php echo $curauth->display_name; ?></h1>
			<?php
					if ( !empty( $curauth->user_email ) || !empty( $curauth->website ) ) { ?>
					<ul>
					<?php
						echo ( !empty( $curauth->website ) ? '<li><a href="' . $curauth->website . '" target="_blank">More from this author</a></li>' : '' );
						echo ( !empty( $curauth->user_email ) ? '<li><a href="mailto:' . $curauth->user_email .'">Contact this author</a></li>' : '' );
					} ?>
					</ul>
		<?php
				}
			} else { ?>
					<h1 class="entry-title"><?php echo $curauth->display_name; ?></h1>
			<?php
				if ( !empty( $curauth->user_email ) || !empty( $curauth->website ) ) { ?>
					<ul>
					<?php
						echo ( !empty( $curauth->website ) ? '<li><a href="' . $curauth->website . '" target="_blank">More from this author</a></li>' : '' );
						echo ( !empty( $curauth->user_email ) ? '<li><a href="mailto:' . $curauth->user_email . '">Contact this author</a></li>' : '' ); ?>
					</ul>
		<?php
				}

			}
			wp_reset_query(); ?>
				</div>
			</header>
			<aside class="column-right">
				<?php get_template_part( 'sidebar', 'none' ); ?>
			</aside>
			<section id="search-results">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'content', get_post_type() );
			}

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text' => __( '&lt;', 'hpmv4' ),
				'next_text' => __( '&gt;', 'hpmv4' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'hpmv4' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		} else {
			get_template_part( 'content', 'none' );
		} ?>
			</section>
		</main>
	</section>
<?php get_footer(); ?>
