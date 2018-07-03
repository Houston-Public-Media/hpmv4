<?php
/**
 * @link 			https://github.com/jwcounts
 * @since  			20170906
 * @package  		HPM-Shows
 *
 * @wordpress-plugin
 * Plugin Name: 	HPM Shows
 * Plugin URI: 		https://github.com/jwcounts
 * Description: 	A custom post type for setting up show pages
 * Version: 		20170906
 * Author: 			Jared Counts
 * Author URI: 		http://www.houstonpublicmedia.org/staff/jared-counts/
 * License: 		GPL-2.0+
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	hpmv2
 *
 * Works best with Wordpress 4.6.0+
 */
add_action( 'init', 'create_hpm_shows' );
function create_hpm_shows() {
	register_post_type( 'shows',
		array(
			'labels' => array(
				'name' => __( 'Shows' ),
				'singular_name' => __( 'Show' ),
				'menu_name' => __( 'Shows' ),
				'add_new_item' => __( 'Add New Show' ),
				'edit_item' => __( 'Edit Show' ),
				'new_item' => __( 'New Show' ),
				'view_item' => __( 'View Show' ),
				'search_items' => __( 'Search Shows' ),
				'not_found' => __( 'Show Not Found' ),
				'not_found_in_trash' => __( 'Show not found in trash' )
			),
			'description' => 'Information pertaining to locally-produced shows',
			'public' => true,
			'menu_position' => 20,
			'menu_icon' => 'dashicons-video-alt3',
			'has_archive' => true,
			'rewrite' => array(
				'slug' => __( 'shows' ),
				'with_front' => false,
				'feeds' => false,
				'pages' => true
			),
			'supports' => array( 'title', 'editor', 'thumbnail' ),
			'taxonomies' => array( 'post_tag' ),
			'capability_type' => array( 'hpm_show','hpm_shows' ),
			'map_meta_cap' => true,
		)
	);
}
add_action('admin_init','hpm_show_add_role_caps',999);
function hpm_show_add_role_caps() {
	// Add the roles you'd like to administer the custom post types
	$roles = array('editor','administrator');

	// Loop through each role and assign capabilities
	foreach($roles as $the_role) :
		$role = get_role($the_role);
		$role->add_cap( 'read' );
		$role->add_cap( 'read_hpm_show');
		$role->add_cap( 'read_private_hpm_shows' );
		$role->add_cap( 'edit_hpm_show' );
		$role->add_cap( 'edit_hpm_shows' );
		$role->add_cap( 'edit_others_hpm_shows' );
		$role->add_cap( 'edit_published_hpm_shows' );
		$role->add_cap( 'publish_hpm_shows' );
		$role->add_cap( 'delete_others_hpm_shows' );
		$role->add_cap( 'delete_private_hpm_shows' );
		$role->add_cap( 'delete_published_hpm_shows' );
	endforeach;
}

add_action( 'load-post.php', 'hpm_show_setup' );
add_action( 'load-post-new.php', 'hpm_show_setup' );
function hpm_show_setup() {
	add_action( 'add_meta_boxes', 'hpm_show_add_meta' );
	add_action( 'save_post', 'hpm_show_save_meta', 10, 2 );
}

function hpm_show_add_meta() {
	add_meta_box(
		'hpm-show-meta-class',
		esc_html__( 'Social and Show Info', 'example' ),
		'hpm_show_meta_box',
		'shows',
		'normal',
		'core'
	);
}

function hpm_show_meta_box( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'hpm_show_class_nonce' );

	$hpm_show_social = get_post_meta( $object->ID, 'hpm_show_social', true );
	if ( empty( $hpm_show_social ) ) :
		$hpm_show_social = array( 'fb' => '', 'twitter' => '', 'yt' => '', 'sc' => '', 'insta' => '', 'tumblr' => '', 'snapchat' => '' );
	endif;

	$hpm_show_meta = get_post_meta( $object->ID, 'hpm_show_meta', true );
	if ( empty( $hpm_show_meta ) ) :
		$hpm_show_meta = array( 'times' => '', 'hosts' => '', 'ytp' => '', 'itunes' => '', 'podcast' => '', 'gplay' => '' );
	endif;

	$hpm_shows_cat = get_post_meta( $object->ID, 'hpm_shows_cat', true );
	if ( empty( $hpm_shows_cat ) ) :
		$hpm_shows_cat = '';
		$top_story = "<p><em>Please select a Show category and click 'Save' or 'Update'</em></p>";
	else :
		$top = get_post_meta( $object->ID, 'hpm_shows_top', true );
		$top_story = '<label for="hpm-shows-top">Top Story:</label><select name="hpm-shows-top" id="hpm-shows-top"><option value="None">No Top Story</option>';
		$cat = new WP_query( array(
			'cat' => $hpm_shows_cat,
			'post_status' => 'publish',
			'posts_per_page' => 25,
			'post_type' => 'post',
			'ignore_sticky_posts' => 1
		) );
		if ( $cat->have_posts() ) :
			while ( $cat->have_posts() ) : $cat->the_post();
				$top_story .= '<option value="'.get_the_ID().'" '.selected( $top, get_the_ID(), FALSE ).'>'.get_the_title().'</option>';
			endwhile;
		endif;
		wp_reset_query();
		$top_story .= '</select><br />';
	endif; ?>
	<p><?PHP _e( "Select the category for this show", 'example' ); ?></p>
	<?php
	wp_dropdown_categories(array(
		'show_option_all' => __("Select One"),
		'taxonomy'        => 'category',
		'name'            => 'hpm-shows-cat',
		'orderby'         => 'name',
		'selected'        => $hpm_shows_cat,
		'hierarchical'    => true,
		'depth'           => 5,
		'show_count'      => false,
		'hide_empty'      => false,
	)); ?>
	<p><?PHP _e( "Which story should appear first?", 'example' ); ?></p>
	<?php echo $top_story; ?>
	<p><?PHP _e( "Enter the social account names and show information in the boxes below.", 'example' ); ?></p>
	<h4><?PHP _e( "Show Information", 'example' ); ?></h4>
	<ul>
		<li><label for="hpm-show-times"><?php _e( "Show Times:", 'example' ); ?></label> <input type="text" id="hpm-show-times" name="hpm-show-times" value="<?PHP echo $hpm_show_meta['times']; ?>" placeholder="Tuesdays at 8pm, etc." style="width: 60%;" /></li>
		<li><label for="hpm-show-hosts"><?php _e( "Hosts:", 'example' ); ?></label> <input type="text" id="hpm-show-hosts" name="hpm-show-hosts" value="<?PHP echo $hpm_show_meta['hosts']; ?>" placeholder="Ernie, Big Bird, etc." style="width: 60%;" /></li>
	</ul>

	<h4><?php _e( "Podcast Feeds:", 'example' ); ?></h4>
	<p><?php _e( "If this show has/is a podcast, enter your RSS feed and iTunes link here." , 'example' ); ?><br />
	<label for="hpm-show-pod"><?php _e( "RSS: ", 'example' ); ?></label><input type="text" id="hpm-show-pod" name="hpm-show-pod" value="<?PHP echo $hpm_show_meta['podcast']; ?>" placeholder="RSS Feed" style="width: 55%;" /><br />
	<label for="hpm-show-itunes"><?php _e( "iTunes: ", 'example' ); ?></label><input type="text" id="hpm-show-itunes" name="hpm-show-itunes" value="<?PHP echo $hpm_show_meta['itunes']; ?>" placeholder="iTunes Link" style="width: 55%;" /><br />
	<label for="hpm-show-gplay"><?php _e( "Google Play: ", 'example' ); ?></label><input type="text" id="hpm-show-gplay" name="hpm-show-gplay" value="<?PHP echo $hpm_show_meta['gplay']; ?>" placeholder="Google Play Link" style="width: 55%;" /></p>

	<h4><?php _e( "YouTube Playlist ID:", 'example' ); ?></h4>
	<p><?php _e( "If this is a TV show with a playlist of videos on YouTube, enter the ID here to populate the player." , 'example' ); ?><br />
	<label for="hpm-show-ytp"><i><?php _e( "https://www.youtube.com/playlist?list=", 'example' ); ?></i></label><input type="text" id="hpm-show-ytp" name="hpm-show-ytp" value="<?PHP echo $hpm_show_meta['ytp']; ?>" placeholder="YouTube Gobbldeegook" style="width: 40%;" /></p>

	<h4><?PHP _e( "Social Accounts", 'example' ); ?></h4>
	<ul>
		<li>
			<label for="hpm-social-fb"><?php _e( "Facebook:", 'example' ); ?></label> <i>https://facebook.com/</i><input type="text" id="hpm-social-fb" name="hpm-social-fb" value="<?PHP echo $hpm_show_social['fb']; ?>" placeholder="page.name" style="width: 33%;" />
		</li>
		<li>
			<label for="hpm-social-twitter"><?php _e( "Twitter:", 'example' ); ?></label> <i>https://twitter.com/</i><input type="text" id="hpm-social-twitter" name="hpm-social-twitter" value="<?PHP echo $hpm_show_social['twitter']; ?>" placeholder="handle" style="width: 33%;" />
		</li>
		<li>
			<label for="hpm-social-yt"><?php _e( "YouTube:", 'example' ); ?></label> <input type="text" id="hpm-social-yt" name="hpm-social-yt" value="<?PHP echo $hpm_show_social['yt']; ?>" placeholder="YouTube Channel or Playlist URL" style="width: 33%;" />
		</li>
		<li>
			<label for="hpm-social-sc"><?php _e( "SoundCloud:", 'example' ); ?></label> <i>https://soundcloud.com/</i><input type="text" id="hpm-social-sc" name="hpm-social-sc" value="<?PHP echo $hpm_show_social['sc']; ?>" placeholder="account-name" style="width: 33%;" />
		</li>
		<li>
			<label for="hpm-social-insta"><?php _e( "Instagram:", 'example' ); ?></label> <i>https://instagram.com/</i><input type="text" id="hpm-social-insta" name="hpm-social-insta" value="<?PHP echo $hpm_show_social['insta']; ?>" placeholder="account.name" style="width: 33%;" />
		</li>
		<li>
			<label for="hpm-social-tumblr"><?php _e( "Tumblr:", 'example' ); ?></label> <input type="text" id="hpm-social-tumblr" name="hpm-social-tumblr" value="<?PHP echo $hpm_show_social['tumblr']; ?>" placeholder="Tumblr URL" style="width: 33%;" />
		</li>
		<li>
			<label for="hpm-social-snapchat"><?php _e( "Snapchat:", 'example' ); ?></label> <i>http://www.snapchat.com/add/</i><input type="text" id="hpm-social-snapchat" name="hpm-social-snapchat" value="<?PHP echo $hpm_show_social['snapchat']; ?>" placeholder="Snapchat Username" style="width: 33%;" />
		</li>
<?php }

function hpm_show_save_meta( $post_id, $post ) {
	if ($post->post_type == 'shows') :
		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['hpm_show_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_show_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

		/* Get the posted data and sanitize it for use as an HTML class. */
		$hpm_social = array(
			'fb'		=> ( isset( $_POST['hpm-social-fb'] ) ? sanitize_text_field( $_POST['hpm-social-fb'] ) : '' ),
			'twitter'	=> ( isset( $_POST['hpm-social-twitter'] ) ? sanitize_text_field( $_POST['hpm-social-twitter'] ) : '' ),
			'yt'	 	=> ( isset( $_POST['hpm-social-yt'] ) ? sanitize_text_field( $_POST['hpm-social-yt'] ) : '' ),
			'sc'		=> ( isset( $_POST['hpm-social-sc'] ) ? sanitize_text_field( $_POST['hpm-social-sc'] ) : '' ),
			'insta'		=> ( isset( $_POST['hpm-social-insta'] ) ? sanitize_text_field( $_POST['hpm-social-insta'] ) : ''),
			'tumblr'	=> ( isset( $_POST['hpm-social-tumblr'] ) ? sanitize_text_field( $_POST['hpm-social-tumblr'] ) : ''),
			'snapchat'	=> ( isset( $_POST['hpm-social-snapchat'] ) ? sanitize_text_field( $_POST['hpm-social-snapchat'] ) : '')
		);

		$hpm_show = array(
			'times'	=> ( isset( $_POST['hpm-show-times'] ) ? $_POST['hpm-show-times'] : '' ),
			'hosts'	=> ( isset( $_POST['hpm-show-hosts'] ) ? sanitize_text_field( $_POST['hpm-show-hosts'] ) : '' ),
			'ytp'	=> ( isset( $_POST['hpm-show-ytp'] ) ? sanitize_text_field( $_POST['hpm-show-ytp'] ) : '' ),
			'itunes'	=> ( isset( $_POST['hpm-show-itunes'] ) ? sanitize_text_field( $_POST['hpm-show-itunes'] ) : '' ),
			'podcast'	=> ( isset( $_POST['hpm-show-pod'] ) ? sanitize_text_field( $_POST['hpm-show-pod'] ) : '' ),
			'gplay'	=> ( isset( $_POST['hpm-show-gplay'] ) ? sanitize_text_field( $_POST['hpm-show-gplay'] ) : '' )
		);

		$hpm_shows_cat = ( isset( $_POST['hpm-shows-cat'] ) ? sanitize_text_field( $_POST['hpm-shows-cat'] ) : '' );
		$hpm_shows_top = ( isset( $_POST['hpm-shows-top'] ) ? sanitize_text_field( $_POST['hpm-shows-top'] ) : '' );

		update_post_meta( $post_id, 'hpm_show_social', $hpm_social );
		update_post_meta( $post_id, 'hpm_show_meta', $hpm_show );
		update_post_meta( $post_id, 'hpm_shows_cat', $hpm_shows_cat );
		update_post_meta( $post_id, 'hpm_shows_top', $hpm_shows_top );
	endif;
}

function shows_meta_query( $query ) {
	if ( $query->is_archive() && $query->is_main_query() ) :
		$show_check = $query->get( 'post_type' );
		if ( $show_check == 'shows' ) :
			$query->set( 'orderby', 'post_title' );
			$query->set( 'order', 'ASC' );
		endif;
	endif;
}
add_action( 'pre_get_posts', 'shows_meta_query' );