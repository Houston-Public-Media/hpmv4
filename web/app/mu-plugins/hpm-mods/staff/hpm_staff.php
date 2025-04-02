<?php
/**
 * Support for Staff Directory, departments/categories, and staff bios
 */
add_action( 'init', 'create_staff_post' );
add_action( 'init', 'create_staff_taxonomies' );
function create_staff_post(): void {
	register_post_type( 'staff', [
		'labels' => [
			'name' => __( 'Staff' ),
			'singular_name' => __( 'Staff' ),
			'menu_name' => __( 'Staff' ),
			'add_new' => __( 'Add New Staff' ),
			'add_new_item' => __( 'Add New Staff' ),
			'edit_item' => __( 'Edit Staff' ),
			'new_item' => __( 'New Staff' ),
			'view_item' => __( 'View Staff' ),
			'search_items' => __( 'Search Staff' ),
			'not_found' => __( 'Staff Not Found' ),
			'not_found_in_trash' => __( 'Staff not found in trash' ),
			'all_items' => __( 'All Staff' ),
			'archives' => __( 'Staff Archives' ),
		],
		'description' => 'Staff Members of Houston Public Media',
		'public' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-groups',
		'has_archive' => true,
		'rewrite' => [
			'slug' => __( 'staff' ),
			'with_front' => false,
			'feeds' => false,
			'pages' => true
		],
		'supports' => [ 'title', 'editor', 'thumbnail', 'author' ],
		'taxonomies' => [ 'staff_category' ],
		'capability_type' => [ 'hpm_staffer','hpm_staffers' ],
		'map_meta_cap' => true,
		'show_in_graphql' => true,
		'graphql_single_name' => 'Staff',
		'graphql_plural_name' => 'Staff'
	]);
}

function create_staff_taxonomies(): void {
	register_taxonomy('staff_category', 'staff', [
		'hierarchical' => true,
		'labels' => [
			'name' => _x( 'Staff Category', 'taxonomy general name' ),
			'singular_name' => _x( 'staff-category', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Staff Categories' ),
			'all_items' => __( 'All Staff Categories' ),
			'parent_item' => __( 'Parent Staff Category' ),
			'parent_item_colon' => __( 'Parent Staff Category:' ),
			'edit_item' => __( 'Edit Staff Category' ),
			'update_item' => __( 'Update Staff Category' ),
			'add_new_item' => __( 'Add New Staff Category' ),
			'new_item_name' => __( 'New Staff Category Name' ),
			'menu_name' => __( 'Staff Categories' )
		],
		'public' => true,
		'rewrite' => [
			'slug' => 'staff-category',
			'with_front' => false,
			'hierarchical' => true
		]
	]);
}

add_action( 'admin_init', 'hpm_staff_add_role_caps', 999 );
function hpm_staff_add_role_caps(): void {
	// Add the roles you'd like to administer the custom post types
	$roles = [ 'editor', 'administrator', 'author' ];

	// Loop through each role and assign capabilities
	foreach( $roles as $the_role ) {
		$role = get_role( $the_role );
		$role->add_cap( 'read' );
		$role->add_cap( 'read_hpm_staffer');
		if ( $the_role !== 'author' ) {
			$role->add_cap( 'add_hpm_staffer' );
			$role->add_cap( 'add_hpm_staffers' );
			$role->add_cap( 'read_private_hpm_staffers' );
			$role->add_cap( 'edit_hpm_staffer' );
			$role->add_cap( 'edit_hpm_staffers' );
			$role->add_cap( 'edit_others_hpm_staffers' );
			$role->add_cap( 'edit_published_hpm_staffers' );
			$role->add_cap( 'publish_hpm_staffers' );
			$role->add_cap( 'delete_others_hpm_staffers' );
			$role->add_cap( 'delete_private_hpm_staffers' );
			$role->add_cap( 'delete_published_hpm_staffers' );
		} else {
			$role->remove_cap( 'add_hpm_staffer' );
			$role->remove_cap( 'add_hpm_staffers' );
			$role->remove_cap( 'read_private_hpm_staffers' );
			$role->add_cap( 'edit_hpm_staffer' );
			$role->add_cap( 'edit_hpm_staffers' );
			$role->remove_cap( 'edit_others_hpm_staffers' );
			$role->remove_cap( 'edit_published_hpm_staffers' );
			$role->remove_cap( 'publish_hpm_staffers' );
			$role->remove_cap( 'delete_others_hpm_staffers' );
			$role->remove_cap( 'delete_private_hpm_staffers' );
			$role->remove_cap( 'delete_published_hpm_staffers' );
		}
	}
}

add_action( 'load-post.php', 'hpm_staff_setup' );
add_action( 'load-post-new.php', 'hpm_staff_setup' );
function hpm_staff_setup(): void {
	add_action( 'add_meta_boxes', 'hpm_staff_add_meta' );
	add_action( 'save_post', 'hpm_staff_save_meta', 10, 2 );
}

function hpm_staff_add_meta(): void {
	add_meta_box(
		'hpm-staff-meta-class',
		esc_html__( 'Title, Social Media, Etc.', 'example' ),
		'hpm_staff_meta_box',
		'staff',
		'normal',
		'core'
	);
}

function hpm_staff_meta_box( $object, $box ): void {
	wp_nonce_field( basename( __FILE__ ), 'hpm_staff_class_nonce' );

	$hpm_staff_meta = get_post_meta( $object->ID, 'hpm_staff_meta', true );
	if ( empty( $hpm_staff_meta ) ) {
		$hpm_staff_meta = [ 'pronouns' => '', 'title' => '', 'email' => '', 'twitter' => '', 'facebook' => '', 'linkedin' => '', 'phone' => '', 'fediverse' => '', 'bluesky' => '' ];
	}

	$hpm_staff_alpha = get_post_meta( $object->ID, 'hpm_staff_alpha', true );
	if ( empty( $hpm_staff_alpha ) ) {
		$hpm_staff_alpha = [ '', '' ];
	} else {
		$hpm_staff_alpha = explode( '|', $hpm_staff_alpha );
	}

	$hpm_staff_authid = get_post_meta( $object->ID, 'hpm_staff_authid', true ); ?>
	<p><?PHP _e( "Enter the staff member's details below", 'example' ); ?></p>
	<ul>
		<li><label for="hpm-staff-name-first"><?php _e( "First Name: ", 'example' ); ?></label> <input type="text" id="hpm-staff-name-first" name="hpm-staff-name-first" value="<?PHP echo ( !empty( $hpm_staff_alpha[1] ) ? $hpm_staff_alpha[1] : '' ); ?>" placeholder="Kenny" style="width: 60%;" /></li>
		<li><label for="hpm-staff-name-last"><?php _e( "Last Name: ", 'example' ); ?></label> <input type="text" id="hpm-staff-name-last" name="hpm-staff-name-last" value="<?PHP echo ( !empty( $hpm_staff_alpha[0] ) ? $hpm_staff_alpha[0] : '' ); ?>" placeholder="Loggins" style="width: 60%;" /></li>
		<li><label for="hpm-staff-name-last"><?php _e( "Pronouns: ", 'example' ); ?></label> <input type="text" id="hpm-staff-pronouns" name="hpm-staff-pronouns" value="<?PHP echo ( !empty( $hpm_staff_meta['pronouns'] ) ? $hpm_staff_meta['pronouns'] : '' ); ?>" placeholder="He/Him" style="width: 60%;" /></li>
		<li><label for="hpm-staff-title"><?php _e( "Job Title: ", 'example' ); ?></label> <input type="text" id="hpm-staff-title" name="hpm-staff-title" value="<?PHP echo ( !empty( $hpm_staff_meta['title'] ) ? $hpm_staff_meta['title'] : '' ); ?>" placeholder="Top Gun" style="width: 60%;" /></li>
		<li><label for="hpm-staff-email"><?php _e( "Email: ", 'example' ); ?></label> <input type="text" id="hpm-staff-email" name="hpm-staff-email" value="<?PHP echo ( !empty( $hpm_staff_meta['email'] ) ? $hpm_staff_meta['email'] : '' ); ?>" placeholder="highway@thedanger.zone" style="width: 60%;" /></li>
		<li><label for="hpm-staff-fb"><?php _e( "Facebook: ", 'example' ); ?></label> <input type="text" id="hpm-staff-fb" name="hpm-staff-fb" value="<?PHP echo ( !empty( $hpm_staff_meta['facebook'] ) ? $hpm_staff_meta['facebook'] : '' ); ?>" placeholder="https://facebook.com/first.last" style="width: 60%;" /></li>
		<li><label for="hpm-staff-twitter"><?php _e( "Twitter: ", 'example' ); ?></label> <input type="text" id="hpm-staff-twitter" name="hpm-staff-twitter" value="<?PHP echo ( !empty( $hpm_staff_meta['twitter'] ) ? $hpm_staff_meta['twitter'] : '' ); ?>" placeholder="https://twitter.com/houpubmedia" style="width: 60%;" /></li>
		<li><label for="hpm-staff-linkedin"><?php _e( "LinkedIn: ", 'example' ); ?></label> <input type="text" id="hpm-staff-linkedin" name="hpm-staff-linkedin" value="<?PHP echo ( !empty( $hpm_staff_meta['linkedin'] ) ? $hpm_staff_meta['linkedin'] : '' ); ?>" placeholder="https://linkedin.com/in/example" style="width: 60%;" /></li>
		<li><label for="hpm-staff-fediverse"><?php _e( "Fediverse: ", 'example' ); ?></label> <input type="text" id="hpm-staff-fediverse" name="hpm-staff-fediverse" value="<?PHP echo ( !empty( $hpm_staff_meta['fediverse'] ) ? $hpm_staff_meta['fediverse'] : '' ); ?>" placeholder="Mastodon, Threads, ActivityPub, etc." style="width: 60%;" /></li>
		<li><label for="hpm-staff-bluesky"><?php _e( "Bluesky: ", 'example' ); ?></label> <input type="text" id="hpm-staff-bluesky" name="hpm-staff-bluesky" value="<?PHP echo ( !empty( $hpm_staff_meta['bluesky'] ) ? $hpm_staff_meta['bluesky'] : '' ); ?>" placeholder="Bluesky, ATProto, etc." style="width: 60%;" /></li>
		<li><label for="hpm-staff-phone"><?php _e( "Phone: ", 'example' ); ?></label> <input type="text" id="hpm-staff-phone" name="hpm-staff-phone" value="<?PHP echo ( !empty( $hpm_staff_meta['phone'] ) ? $hpm_staff_meta['phone'] : '' ); ?>" placeholder="(713) 555-5555" style="width: 60%;" /></li>
		<li><label for="hpm-staff-author"><?php _e( "Author ID:", 'example' ); ?></label> <?php
			wp_dropdown_users([
				'show_option_none' => 'None',
				'show' => 'display_name',
				'echo' => true,
				'selected' => $hpm_staff_authid,
				'include_selected' => true,
				'name' => 'hpm-staff-author',
				'id' => 'hpm-staff-author'
			]); ?></li>
	</ul>
<?php }

function hpm_staff_save_meta( $post_id, $post ) {
	if ( $post->post_type == 'staff' ) {
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['hpm_staff_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_staff_class_nonce'], basename( __FILE__ ) ) ) {
			return $post_id;
		}

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return $post_id;
		}

		/* Get the posted data and sanitize it for use as an HTML class. */
		$hpm_staff = [
			'title'		=> ( isset( $_POST['hpm-staff-title'] ) ? sanitize_text_field( $_POST['hpm-staff-title'] ) : '' ),
			'pronouns'	=> ( isset( $_POST['hpm-staff-pronouns'] ) ? sanitize_text_field( $_POST['hpm-staff-pronouns'] ) : '' ),
			'email'		=> ( isset( $_POST['hpm-staff-email'] ) ? sanitize_text_field( $_POST['hpm-staff-email'] ) : '' ),
			'facebook'	=> ( isset( $_POST['hpm-staff-fb'] ) ? sanitize_text_field( $_POST['hpm-staff-fb'] ) : '' ),
			'twitter'	=> ( isset( $_POST['hpm-staff-twitter'] ) ? sanitize_text_field( $_POST['hpm-staff-twitter'] ) : '' ),
			'linkedin'	=> ( isset( $_POST['hpm-staff-linkedin'] ) ? sanitize_text_field( $_POST['hpm-staff-linkedin'] ) : '' ),
			'phone'	=> ( isset( $_POST['hpm-staff-phone'] ) ? sanitize_text_field( $_POST['hpm-staff-phone'] ) : ''),
			'fediverse'	=> ( isset( $_POST['hpm-staff-fediverse'] ) ? sanitize_text_field( $_POST['hpm-staff-fediverse'] ) : ''),
			'bluesky'	=> ( isset( $_POST['hpm-staff-bluesky'] ) ? sanitize_text_field( $_POST['hpm-staff-bluesky'] ) : '')
		];
		$hpm_first = ( isset( $_POST['hpm-staff-name-first'] ) ? sanitize_text_field( $_POST['hpm-staff-name-first'] ) : '' );
		$hpm_last = ( isset( $_POST['hpm-staff-name-last'] ) ? sanitize_text_field( $_POST['hpm-staff-name-last'] ) : '' );
		$hpm_staff_alpha = $hpm_last."|".$hpm_first;
		$hpm_staff_authid = ( isset( $_POST['hpm-staff-author'] ) ? sanitize_text_field( $_POST['hpm-staff-author'] ) : '' );

		update_post_meta( $post_id, 'hpm_staff_authid', $hpm_staff_authid );
		update_post_meta( $post_id, 'hpm_staff_meta', $hpm_staff );
		update_post_meta( $post_id, 'hpm_staff_alpha', $hpm_staff_alpha );
	}
	return $post_id;
}

add_filter( 'manage_edit-staff_columns', 'hpm_edit_staff_columns' ) ;
function hpm_edit_staff_columns( $columns ): array {
	return [
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name' ),
		'job_title' => __( 'Title' ),
		'staff_category' => __( 'Departments' ),
		'authorship' => __( 'Author?' )
	];
}

add_action( 'manage_staff_posts_custom_column', 'hpm_manage_staff_columns', 10, 2 );
function hpm_manage_staff_columns( $column, $post_id ): void {
	global $post;
	$staff_meta = get_post_meta( $post_id, 'hpm_staff_meta', true );
	$staff_authid = get_post_meta( $post_id, 'hpm_staff_authid', true );
	switch ( $column ) {
		case 'job_title' :
			if ( empty( $staff_meta['title'] ) ) {
				echo __( 'None' );
			} else {
				echo __( $staff_meta['title'] );
			}
			break;
		case 'authorship' :
			if ( empty( $staff_authid ) || $staff_authid < 0 ) {
				echo __( 'No' );
			} else {
				echo __( 'Yes' );
			}
			break;
		case 'staff_category' :
			$terms = get_the_terms( $post_id, 'staff_category' );
			if ( !empty( $terms ) ) {
				$out = [];
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( [ 'post_type' => $post->post_type, 'staff_category' => $term->slug ], 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'staff_category', 'display' ) )
					);
				}
				echo join( ', ', $out );
			} else {
				_e( 'No Department Affiliations' );
			}
			break;
		default :
			break;
	}
}

add_action('restrict_manage_posts', 'hpm_filter_post_type_by_taxonomy');
function hpm_filter_post_type_by_taxonomy(): void {
	global $typenow;
	$taxonomy  = 'staff_category';
	if ( $typenow == 'staff' ) {
		$selected      = ( $_GET[ $taxonomy ] ?? '' );
		$info_taxonomy = get_taxonomy( $taxonomy );
		wp_dropdown_categories([
			'show_option_all' => __("Show All $info_taxonomy->label"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'hierarchical'    => true,
			'depth'           => 3,
			'show_count'      => true,
			'hide_empty'      => true,
		]);
	}
}

add_filter('parse_query', 'hpm_convert_id_to_term_in_query');
function hpm_convert_id_to_term_in_query( $query ): void {
	global $pagenow;
	$taxonomy  = 'staff_category';
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset( $q_vars['post_type'] ) && $q_vars['post_type'] == 'staff' && !empty( $q_vars[ $taxonomy ] ) ) {
		$term = get_term_by('id', $q_vars[ $taxonomy ], $taxonomy );
		$q_vars[ $taxonomy ] = $term->slug;
	}
}

/*
 * Changes number of posts loaded when viewing the staff directory
 */
function staff_meta_query( $query ): void {
	if (
		$query->is_archive() &&
		$query->is_main_query() &&
		(
			$query->get( 'post_type' ) == 'staff' ||
			!empty( $query->get( 'staff_category' ) )
		)
	) {
		$query->set( 'meta_query', [ 'hpm_staff_alpha' => [ 'key' => 'hpm_staff_alpha' ] ] );
		$query->set( 'orderby', 'meta_value' );
		$query->set( 'order', 'ASC' );
		if ( !is_admin() ) {
			$query->set( 'posts_per_page', -1 );
		}
		if ( !is_admin() && empty( $query->get( 'staff_category' ) ) ) {
			$query->set( 'tax_query', [[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'hosts', 'executive-team', 'department-leaders', 'daily-and-weekly-radio-shows', 'news-team', 'radio-operations', 'digital-operations' ],
				'operator' => 'NOT IN'
			]] );
		}
	}
}
add_action( 'pre_get_posts', 'staff_meta_query' );

function hpm_staff_tax_template( $taxonomy_template ) {
	global $post;
	if ( is_tax( 'staff_category' ) ) {
		return get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'archive-staff.php';
	}
	return $taxonomy_template;
}
add_filter( 'taxonomy_template', 'hpm_staff_tax_template' );

function hpm_staff_echo( $query ): void {
	$main_query = $query;
	$cat = $main_query->get( 'staff_category' );
	$exempt = [ 'hosts', 'executive-team', 'department-leaders' ];
	if ( empty( $cat ) ) {
		echo '<h2>Executive Team</h2><div class="staff-grid">';
		$args = [
			'post_type' => 'staff',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => 1,
			'tax_query' => [[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'executive-team' ]
			]],
			'meta_query' => [ 'hpm_staff_alpha' => [ 'key' => 'hpm_staff_alpha' ] ],
			'orderby' => 'meta_value',
			'order' => 'ASC'
		];
		$el = new WP_Query( $args );
		while ( $el->have_posts() ) {
			$el->the_post();
			get_template_part( 'content', 'staff' );
		}
		echo '</div><h2>Executive Team Support Staff</h2><div class="staff-grid">';
		$args['tax_query'] = [
			'relation' => 'AND',
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'executive-team-support-staff' ]
			],
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'executive-team' ],
				'operator' => 'NOT IN'
			]
		];
		$dh = new WP_Query( $args );
		while ( $dh->have_posts() ) {
			$dh->the_post();
			get_template_part( 'content', 'staff' );
		}

		echo '</div><h2>Department Leaders</h2><div class="staff-grid">';

		$args['tax_query'] = [
			'relation' => 'AND',
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'hosts' ]
			],
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'executive-team', 'executive-team-support-staff' ],
				'operator' => 'NOT IN'
			]
		];
		$dh = new WP_Query( $args );
		while ( $dh->have_posts() ) {
			$dh->the_post();
			get_template_part( 'content', 'staff' );
		}
		echo '</div><h2>Talk Show Hosts</h2><div class="staff-grid">';
		$args['tax_query'] = [
			'relation' => 'AND',
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'hosts' ]
			],
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'executive-team', 'department-leaders', 'executive-team-support-staff' ],
				'operator' => 'NOT IN'
			]
		];
		$ts = new WP_Query( $args );
		while ( $ts->have_posts() ) {
			$ts->the_post();
			get_template_part( 'content', 'staff' );
		}

		echo '</div><h2>News &amp; Content</h2><div class="staff-grid">';
		$args['tax_query'] = [
			'relation' => 'AND',
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'daily-and-weekly-radio-shows', 'news-team', 'radio-operations', 'digital-operations' ]
			],
			[
				'taxonomy' => 'staff_category',
				'field' => 'slug',
				'terms' => [ 'executive-team', 'department-leaders', 'hosts' ],
				'operator' => 'NOT IN'
			]
		];
		$ts = new WP_Query( $args );
		while ( $ts->have_posts() ) {
			$ts->the_post();
			get_template_part( 'content', 'staff' );
		}

		echo '</div><h2>Houston Public Media Staff</h2>';
	} elseif ( !in_array( $cat, $exempt ) ) {
		$main_query->posts = hpm_staff_sort( $main_query->posts );
	}
	echo '<div class="staff-grid">';
	while ( $main_query->have_posts() ) {
		$main_query->the_post();
		get_template_part( 'content', 'staff' );
	}
	echo "</div>";
	wp_reset_query();
}

function hpm_staff_sort( $posts ): array {
	$out = $first = [];
	$exempt = [ 'hosts', 'executive-team', 'department-leaders' ];
	foreach ( $posts as $p ) {
		$lead = false;
		$cat = get_terms( [ 'taxonomy' => 'staff_category', 'object_ids' => $p->ID ] );
		foreach ( $cat as $c ) {
			if ( in_array( $c->slug, $exempt ) ) {
				$lead = true;
			}
		}
		if ( $lead ) {
			$first[] = $p;
		} else {
			$out[] = $p;
		}
	}
	return array_merge( $first, $out );
}