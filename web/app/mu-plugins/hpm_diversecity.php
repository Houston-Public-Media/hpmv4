<?php
/**
 * @link 			https://github.com/jwcounts
 * @since  			20170906
 * @package  		HPM-DiverseCity
 *
 * @wordpress-plugin
 * Plugin Name: 	HPM DiverseCity
 * Plugin URI: 		https://github.com/jwcounts
 * Description: 	Custom post types and miscellaneous functions for the HPM DiverseCity initiative
 * Version: 		20170906
 * Author: 			Jared Counts
 * Author URI: 		http://www.houstonpublicmedia.org/staff/jared-counts/
 * License: 		GPL-2.0+
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	hpmv2
 *
 * Works best with Wordpress 4.6.0+
 */
/*
add_action( 'init', 'create_hpm_dc_stories' );
function create_hpm_dc_stories() {
	register_post_type( 'dc-stories',
		array(
			'labels' => array(
				'name' => __( 'DiverseCity Stories' ),
				'singular_name' => __( 'DiverseCity Story' ),
				'menu_name' => __( 'DC Stories' ),
				'add_new_item' => __( 'Add New DiverseCity Story' ),
				'edit_item' => __( 'Edit DiverseCity Story' ),
				'new_item' => __( 'New DiverseCity Story' ),
				'view_item' => __( 'View DiverseCity Story' ),
				'search_items' => __( 'Search DiverseCity Stories' ),
				'not_found' => __( 'DiverseCity Story Not Found' ),
				'not_found_in_trash' => __( 'DiverseCity Story not found in trash' )
			),
			'description' => 'User-submitted stories for the HPM DiverseCity Project',
			'public' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-groups',
			'has_archive' => true,
			'rewrite' => array(
				'slug' => __( 'dc-stories' ),
				'with_front' => false,
				'feeds' => false,
				'pages' => true
			),
			'supports' => array( 'title', 'editor' ),
			'capability_type' => array( 'hpm_dc_story', 'hpm_dc_stories' ),
			'map_meta_cap' => true
		)
	);
}
add_action('admin_init','hpm_dc_stories_add_role_caps',999);
function hpm_dc_stories_add_role_caps() {
	// Add the roles you'd like to administer the custom post types
	$roles = array('editor','administrator');

	// Loop through each role and assign capabilities
	foreach($roles as $the_role) :
		$role = get_role($the_role);
		$role->add_cap( 'read' );
		$role->add_cap( 'read_hpm_dc_story');
		$role->add_cap( 'read_private_hpm_dc_stories' );
		$role->add_cap( 'edit_hpm_dc_story' );
		$role->add_cap( 'edit_hpm_dc_stories' );
		$role->add_cap( 'edit_others_hpm_dc_stories' );
		$role->add_cap( 'edit_published_hpm_dc_stories' );
		$role->add_cap( 'publish_hpm_dc_stories' );
		$role->add_cap( 'delete_others_hpm_dc_stories' );
		$role->add_cap( 'delete_private_hpm_dc_stories' );
		$role->add_cap( 'delete_published_hpm_dc_stories' );
	endforeach;
}

add_action( 'load-post.php', 'hpm_dc_stories_setup' );
add_action( 'load-post-new.php', 'hpm_dc_stories_setup' );
function hpm_dc_stories_setup() {
	add_action( 'add_meta_boxes', 'hpm_dc_stories_add_meta' );
	add_action( 'save_post', 'hpm_dc_stories_save_meta', 10, 2 );
}

function hpm_dc_stories_add_meta( ) {
	add_meta_box(
		'hpm-dc-stories-meta-class',
		esc_html__( 'Story Metadata', 'example' ),
		'hpm_dc_stories_meta_box',
		'dc-stories',
		'advanced',
		'high'
	);
}

function hpm_dc_stories_meta_box( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'hpm_dc_stories_class_nonce' ); 
	$exists_type = metadata_exists( 'post', $object->ID, 'hpm_dc_story_type' );

	$dc_story_types = array(
		0 => array( 'Audio/Video', 'dc-audio-video' ),
		1 => array( 'Audio/Video with Quote', 'dc-av-quote' ),
		2 => array( 'Quote Only', 'dc-quote' ),
		3 => array( 'Story', 'dc-story-link' )
	);
	
	if ( $exists_type ) :
		$dc_type = get_post_meta( $object->ID, 'hpm_dc_story_type', true );
		if ( empty( $dc_type ) ) :
			$dc_type = array( 'type' => '', 'author_desc' => '' );
		endif;
	else :
		$dc_type = array( 'type' => '', 'author_desc' => '' );
	endif; ?>
	<h3><?PHP _e( "Story Type", 'example' ); ?></h3>
	<p><?PHP _e( "Select the post category for this podcast:", 'example' ); ?></p>
	<p>
		<label for="hpm-dc-story-type"><?php _e( "Story Type:", 'example' ); ?></label>
		<select name="hpm-dc-story-type" id="hpm-dc-story-type">
				<option value=""<?PHP echo ( empty( $dc_type['type'] ) ? " selected" : "" ); ?>><?PHP _e( "Select One", 'example' ); ?></option>
<?php
	foreach ( $dc_story_types as $dct ) : ?>
				<option value="<?PHP echo $dct[1]; ?>"<?PHP if ($dct[1] == $dc_type['type']) { echo " selected"; } ?>><?PHP _e( $dct[0], 'example' ); ?></option>
<?PHP
	endforeach;
?>
			</select>
		</p>
		<h3><?PHP _e( "Author Description", 'example' ); ?></h3>
		<p><strong><?PHP _e( "What term would you use to describe the author of this story?  Examples: 'Houston Resident', 'Football Player', 'Flamenco Dancer'", 'example' ); ?></strong><br />
		<label for="hpm-dc-story-auth"><?php _e( "Author Description:", 'example' ); ?></label> <input type="text" id="hpm-dc-story-auth" name="hpm-dc-story-auth" value="<?PHP echo $dc_type['author_desc']; ?>" placeholder="Genderless Space Pirate" style="width: 60%;" /></p>
<?php }

function hpm_dc_stories_save_meta( $post_id, $post ) {
	if ( $post->post_type == 'dc-stories' ) :
		if ( !isset( $_POST['hpm_dc_stories_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_dc_stories_class_nonce'], basename( __FILE__ ) ) )
			return $post_id;

		$post_type = get_post_type_object( $post->post_type );

		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		$hpm_dc_type = array(
			'type' => $_POST['hpm-dc-story-type'],
			'author_desc' => ( isset( $_POST['hpm-dc-story-auth'] ) ? sanitize_text_field( $_POST['hpm-dc-story-auth'] ) : '' )
		);

		$exists_type = metadata_exists( 'post', $post_id, 'hpm_dc_story_type' );
		
		if ( $exists_type ) :
			update_post_meta( $post_id, 'hpm_dc_story_type', $hpm_dc_type );
		else :
			add_post_meta( $post_id, 'hpm_dc_story_type', $hpm_dc_type, true );
		endif;
	endif;
}
*/
/**
	DiverseCity In-Page Shortcode
*/
function diversecity_display_shortcode( $atts ) {
	/* global $hpm_constants;
	if ( empty( $hpm_constants ) ) :
		$hpm_constants = array();
	endif; */
	extract( shortcode_atts( array(
		'section' => '',
		'ids' => ''
	), $atts, 'multilink' ) );
	$i_exp = explode( ',', $ids);
	$args = array(
		'ignore_sticky_posts' => 1
	);
	$output = '';
	/* if ( !empty( $hpm_constants ) ) :
		$args['post__not_in'] = $hpm_constants;
	endif; */
	global $post;
	switch ( $section ) {
		case "banner" :
			if ( !empty( $i_exp[0] ) ) :
				$args['post__in'] = $i_exp;
			else :
				$args['category_name'] = 'diversecity';
			endif;
			$args['posts_per_page'] = 1;
			$article = new WP_query( $args );
			if ( $article->have_posts() ) :
				while ( $article->have_posts() ) : $article->the_post();
					$postClass = get_post_class();
					$fl_array = preg_grep("/felix-type-/", $postClass);
					$fl_arr = array_keys( $fl_array );
					$postClass[$fl_arr[0]] = 'dc-top';
					$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><h3 class="toptag">Featured Story</h3><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), 'large' ).')"><a class="post-thumbnail" href="'.get_the_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><div class="entry-header-wrap"><h2 class="entry-title"><a href="'.get_the_permalink().'" rel="bookmark">'.get_the_title().'</a></h2><p><a href="'.get_the_permalink().'" rel="bookmark">'.get_the_excerpt().' &gt;&gt;</a></p></div></header></article>';
					// $hpm_constants[] = get_the_ID();
				endwhile;
			endif;
			break;
		case "photos" :
			global $wpdb;
			$photos = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_type = 'attachment' AND post_parent = 183317 AND post_mime_type LIKE 'image%' ORDER BY post_date DESC LIMIT 3",TRUE);
			foreach ( $photos as $p ) :
				$img = wp_get_attachment_image_src( $p->ID, 'large' );
				$output .= '<div class="photo-grid"><div class="thumbnail-wrap" style="background-image: url('.$img[0].')"><a class="post-thumbnail" href="/diversecity/photo-series/" aria-hidden="true"></a></div></div>';
			endforeach;
			break;
		case "shapes" :
			$article = array();
			$args['category_name'] = 'how-it-shapes-us';
			$args['posts_per_page'] = 3;
			if ( !empty( $i_exp[0] ) ) :
				$args['post__in'] = $i_exp;
				$args['orderby'] = 'post__in';
				$c = count( $i_exp );
				if ( $c != $args['posts_per_page'] ) :
					$diff = $args['posts_per_page'] - $c;
					$args['posts_per_page'] = $c;
					$article[] = new WP_Query( $args );
					unset($args['post__in']);
					unset($args['orderby']);
					$args['post__not_in'] = $i_exp;
					$args['posts_per_page'] = $diff;
				endif;
			endif;
			$article[] = new WP_query( $args );
			foreach ( $article as $art ) :
				if ( $art->have_posts() ) :
					while ( $art->have_posts() ) : $art->the_post();
						$postClass = get_post_class();
						$fl_array = preg_grep("/felix-type-/", $postClass);
						$fl_arr = array_keys( $fl_array );
						unset($postClass[$fl_arr[0]]);
						$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), 'large' ).')"><a class="post-thumbnail" href="'.get_the_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><h2 class="entry-title"><a href="'.get_the_permalink().'" rel="bookmark">'.get_the_title().'</a></h2></header></article>';
						//$hpm_constants[] = get_the_ID();
					endwhile;
				endif;
			endforeach;
			break;
		case "conversations" :
			if ( !empty( $i_exp[0] ) ) :
				$args['post__in'] = $i_exp;
			endif;
			$args['category_name'] = 'conversations';
			$args['posts_per_page'] = 1;
			$article = new WP_query( $args );
			if ( $article->have_posts() ) :
				while ( $article->have_posts() ) : $article->the_post();
					$postClass = get_post_class();
					$fl_array = preg_grep("/felix-type-/", $postClass);
					$fl_arr = array_keys( $fl_array );
					unset($postClass[$fl_arr[0]]);
					$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), 'large' ).')"><a class="post-thumbnail" href="'.get_the_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><h2 class="entry-title"><a href="'.get_the_permalink().'" rel="bookmark">'.get_the_title().'</a></h2></header></article>';
					//$hpm_constants[] = get_the_ID();
				endwhile;
			endif;
			break;
		case "sounds-flavors" :
			$args['category_name'] = 'sounds-flavors';
			$args['posts_per_page'] = 3;
			if ( !empty( $i_exp[0] ) ) :
				$args['post__in'] = $i_exp;
				$args['orderby'] = 'post__in';
				$c = count( $i_exp );
				if ( $c != $args['posts_per_page'] ) :
					$diff = $args['posts_per_page'] - $c;
					$args['posts_per_page'] = $c;
					$article[] = new WP_Query( $args );
					unset($args['post__in']);
					unset($args['orderby']);
					$args['post__not_in'] = $i_exp;
					$args['posts_per_page'] = $diff;
				endif;
			endif;
			$article[] = new WP_query( $args );
			foreach ( $article as $art ) :
				if ( $art->have_posts() ) :
					while ( $art->have_posts() ) : $art->the_post();
						$postClass = get_post_class();
						$fl_array = preg_grep("/felix-type-/", $postClass);
						$fl_arr = array_keys( $fl_array );
						unset($postClass[$fl_arr[0]]);
						$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), 'large' ).')"><a class="post-thumbnail" href="'.get_the_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><h2 class="entry-title"><a href="'.get_the_permalink().'" rel="bookmark">'.get_the_title().'</a></h2></header></article>';
					//$hpm_constants[] = get_the_ID();
					endwhile;
				endif;
			endforeach;
			break;
		case "influencers" :
			$args['category_name'] = 'influencers';
			$args['posts_per_page'] = 2;
			if ( !empty( $i_exp[0] ) ) :
				$args['post__in'] = $i_exp;
				$args['orderby'] = 'post__in';
				$c = count( $i_exp );
				if ( $c != $args['posts_per_page'] ) :
					$diff = $args['posts_per_page'] - $c;
					$args['posts_per_page'] = $c;
					$article[] = new WP_Query( $args );
					unset($args['post__in']);
					unset($args['orderby']);
					$args['post__not_in'] = $i_exp;
					$args['posts_per_page'] = $diff;
				endif;
			endif;
			$article[] = new WP_query( $args );
			foreach ( $article as $art ) :
				if ( $art->have_posts() ) :
					while ( $art->have_posts() ) : $art->the_post();
						$postClass = get_post_class();
						$fl_array = preg_grep("/felix-type-/", $postClass);
						$fl_arr = array_keys( $fl_array );
						unset($postClass[$fl_arr[0]]);
						$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), 'large' ).')"><a class="post-thumbnail" href="'.get_the_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><h2 class="entry-title"><a href="'.get_the_permalink().'" rel="bookmark">'.get_the_title().'</a></h2></header></article>';
						//$hpm_constants[] = get_the_ID();
					endwhile;
				endif;
			endforeach;
			break;
		case "stories" :
			if ( !empty( $i_exp[0] ) ) :
				$args['post__in'] = $i_exp;
			endif;
			$args['post_type'] = 'dc-stories';
			$args['posts_per_page'] = 1;
			$article = new WP_query( $args );
			if ( $article->have_posts() ) :
				while ( $article->have_posts() ) : $article->the_post();
					$postClass = get_post_class();
					$dc_type = get_post_meta( get_the_ID(), 'hpm_dc_story_type', true );
					if ( !empty( $dc_type['type'] ) ) :
						$postClass[] = $dc_type['type'];
					endif;
					$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="entry-content">'.apply_filters( 'the_content', get_the_content() ).'</div><p class="dc-author">'.get_the_title().'</p>';
					if ( !empty( $dc_type['author_desc'] ) ) :
						$output .= '<p class="dc-desc">'.$dc_type['author_desc'].'</p>';
					endif;
					$output .= '</article>';
				endwhile;
			endif;
			break;
	}
	return $output;
}
add_shortcode( 'diversecity', 'diversecity_display_shortcode' );