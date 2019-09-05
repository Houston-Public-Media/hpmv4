<?php
/*
Plugin Name:       HPM Postscript
Plugin URI:        https://github.com/jwcounts/
Description:       Add classes to body tag and <code>post_class()</code>.
Version:           1.0.0
Author:            Barrett Golding and Jared Counts
Author URI:        https://rjionline.org/
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:       postscript
Domain Path:       /languages/
Plugin Prefix:     postscript
*/

/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 */

/* ------------------------------------------------------------------------ *
 * Plugin init and uninstall
 * ------------------------------------------------------------------------ */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( defined( 'POSTSCRIPT_VERSION' ) ) {
    return;
}

define( 'POSTSCRIPT_VERSION', '1.0.0' );


/* ------------------------------------------------------------------------ *
 * Meta Box for the Post Edit screen.
 * ------------------------------------------------------------------------ */

/**
 * Displays meta box on post editor screen (both new and edit pages).
 */
function postscript_meta_box_setup() {
    $user    = wp_get_current_user();
    $roles   = [ 'administrator' ];

    // Add meta boxes only for allowed user roles.
    if ( array_intersect( $roles, $user->roles ) ) {
        // Add meta box.
        add_action( 'add_meta_boxes', 'postscript_add_meta_box' );

        // Save post meta.
        add_action( 'save_post', 'postscript_save_post_meta', 10, 2 );
    }
}
add_action( 'load-post.php', 'postscript_meta_box_setup' );
add_action( 'load-post-new.php', 'postscript_meta_box_setup' );


function postscript_metabox_admin_notice() {
    $postscript_meta = get_post_meta( get_the_id(), 'postscript_meta', true );
    ?>
    <div class="error">
    <?php var_dump( $_POST ) ?>
        <p><?php _e( 'Error!', 'postscript' ); ?></p>
    </div>
    <?php
}

/**
 * Creates meta box for the post editor screen (for user-selected post types).
 *
 * Passes array of user-setting options to callback.
 *
 * @uses postscript_get_options()   Safely gets option from database.
 */
function postscript_add_meta_box() {
    $options = [
		'user_roles' => [ 'administrator' ],
		'post_types' => [ 'post', 'page' ],
		'allow' => [ 'class_body' => 'on', 'class_post' => 'on' ]
	];

    add_meta_box(
        'postscript-meta',
        esc_html__( 'Postscript', 'postscript' ),
        'postscript_meta_box_callback',
        $options['post_types'],
        'side',
        'default',
        $options
    );
}

/**
 * Builds HTML form for the post meta box.
 *
 * Form elements are checkboxes to select script/style handles (stored as tax terms),
 * and text fields for entering body/post classes (stored in same post-meta array).
 *
 * Form elements are printed only if allowed on Setting page.
 * Callback function passes array of settings-options in args ($box):
 *
 * postscript_get_options() returns:
 * Array
 * (   // Settings used by meta-box:
 *     [user_roles] => Array
 *         (
 *             [0] => {role_key}
 *             [1] => {role_key}
 *         )
 *
 *     [post_types] => Array
 *         (
 *             [0] => {post_type_key}
 *             [1] => {post_type_key}
 *         )
 *
 *     [allow] => Array
 *         (
 *             [urls_script] => 1
 *             [urls_style]  => 1
 *             [class_body] => on
 *             [class_post] => on
 *         )
 *     // Not used by meta-box:
 *     [add_script]    => {style_handle}
 *     [add_style]     => {script_handle}
 *     [remove_script] => {style_handle}
 *     [remove_style]  => {script_handle}
 *     [version]       => 1.0.0
 * )
 *
 * get_post_meta( $post_id, 'postscript_meta', true ) returns:
 * Array
 * (
 *     [url_style] => http://example.com/my-post-style.css
 *     [url_script] => http://example.com/my-post-script.js
 *     [url_script_2] => http://example.com/my-post-script-2.js
 *     [class_body] => my-post-body-class
 *     [class_post] => my-post-class
 * )
 * @param  Object $post Object containing the current post.
 * @param  array  $box  Array of meta box id, title, callback, and args elements.
 */
function postscript_meta_box_callback( $post, $box ) {
    $post_id = $post->ID;
    wp_nonce_field( basename( __FILE__ ), 'postscript_meta_nonce' );

    // Display text fields for: URLs (style/script) and classes (body/post).
    $opt_allow       = $box['args']['allow'];
    $postscript_meta = get_post_meta( $post_id, 'postscript_meta', true );

    if ( isset ( $opt_allow['class_body'] ) ) { // Admin setting allows body_class() text field. ?>
    <p>
        <label for="postscript-class-body"><?php _e( 'Body class:', 'postscript' ); ?></label><br />
        <input class="widefat" type="text" name="postscript_meta[class_body]" id="postscript-class-body" value="<?php if ( isset ( $postscript_meta['class_body'] ) ) { echo sanitize_html_class( $postscript_meta['class_body'] ); } ?>" size="30" />
    </p>
    <?php } ?>
    <?php if ( isset ( $opt_allow['class_post'] ) ) { // Admin setting allows post_class() text field. ?>
    <p>
        <label for="postscript-class-post"><?php _e( 'Post class:', 'postscript' ); ?></label><br />
        <input class="widefat" type="text" name="postscript_meta[class_post]" id="postscript-class-post" value="<?php if ( isset ( $postscript_meta['class_post'] ) ) { echo sanitize_html_class( $postscript_meta['class_post'] ); } ?>" size="30" />
    </p>
    <?php
    }
}

/**
 * Saves the meta box form data upon submission.
 *
 * @uses  postscript_sanitize_data()    Sanitizes $_POST array.
 *
 * @param int     $post_id    Post ID.
 * @param WP_Post $post       Post object.
 */
function postscript_save_post_meta( $post_id, $post ) {
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'postscript_meta_nonce' ] ) && wp_verify_nonce( $_POST[ 'postscript_meta_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
        return;
    }

    // Get the post type object (to match with current user capability).
    $post_type = get_post_type_object( $post->post_type );

    // Check if the current user has permission to edit the post.
    if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
        return $post_id;
    }

    $meta_key   = 'postscript_meta';
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    // If any user-submitted form fields have a value.
    // (implode() reduces array values to a string to do the check).
    if ( isset( $_POST['postscript_meta'] ) && implode( $_POST['postscript_meta'] ) ) {
        $form_data  = postscript_sanitize_data( $_POST['postscript_meta'] );
    } else {
        $form_data  = null;
    }

    // Add post-meta, if none exists, and if user entered new form data.
    if ( $form_data && '' == $meta_value ) {
        add_post_meta( $post_id, $meta_key, $form_data, true );

    // Update post-meta if user changed existing post-meta values in form.
    } elseif ( $form_data && $form_data != $meta_value ) {
        update_post_meta( $post_id, $meta_key, $form_data );

    // Delete existing post-meta if user cleared all post-meta values from form.
    } elseif ( null == $form_data && $meta_value ) {
        delete_post_meta( $post_id, $meta_key );

    // Any other possibilities?
    } else {
        return;
    }
}



/**
 * Sanitizes values in an one- and multi- dimensional arrays.
 *
 * Used by post meta-box form before writing post-meta to database
 * and by Settings API before writing option to database.
 *
 * @link https://tommcfarlin.com/input-sanitization-with-the-wordpress-settings-api/
 *
 * @since    0.4.0
 *
 * @param    array    $input        The address input.
 * @return   array    $input_clean  The sanitized input.
 */
function postscript_sanitize_data( $data = array() ) {
    // Initialize a new array to hold the sanitized values.
    $data_clean = array();

    // Check for non-empty array.
    if ( ! is_array( $data ) || ! count( $data )) {
        return array();
    }

    // Traverse the array and sanitize each value.
    foreach ( $data as $key => $value) {
        // For one-dimensional array.
        if ( ! is_array( $value ) && ! is_object( $value ) ) {
            // Remove blank lines and whitespaces.
            $value = preg_replace( '/^\h*\v+/m', '', trim( $value ) );
            $value = str_replace( ' ', '', $value );
            $data_clean[ $key ] = sanitize_text_field( $value );
        }

        // For multidimensional array.
        if ( is_array( $value ) ) {
            $data_clean[ $key ] = postscript_sanitize_data( $value );
        }
    }

    return $data_clean;
}

/**
 * Sanitizes values in an one-dimensional array.
 * (Used by post meta-box form before writing post-meta to database.)
 *
 * @link https://tommcfarlin.com/input-sanitization-with-the-wordpress-settings-api/
 *
 * @since    0.4.0
 *
 * @param    array    $input        The address input.
 * @return   array    $input_clean  The sanitized input.
 */
function postscript_sanitize_array( $input ) {
    // Initialize a new array to hold the sanitized values.
    $input_clean = array();

    // Traverse the array and sanitize each value.
    foreach ( $input as $key => $val ) {
        $input_clean[ $key ] = sanitize_text_field( $val );
    }

    return $input_clean;
}

function postscript_remove_empty_lines( $string ) {
    return preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string );
    // preg_replace( '/^\h*\v+/m', '', $string );
}

/**
 * Adds user-entered class(es) to the body tag.
 *
 * @uses postscript_get_options()   Safely gets option from database.
 * @return  array $classes  WordPress defaults and user-added classes
 */
function postscript_class_body( $classes ) {
    $post_id = get_the_ID();
    $options = [
		'user_roles' => [ 'administrator' ],
		'post_types' => [ 'post', 'page' ],
		'allow' => [ 'class_body' => 'on', 'class_post' => 'on' ]
	];

    if ( ! empty( $post_id ) && isset( $options['allow']['class_body'] ) ) {
        // Get the custom post class.
        $postscript_meta = get_post_meta( $post_id, 'postscript_meta', true );

        // If a post class was input, sanitize it and add it to the body class array.
        if ( ! empty( $postscript_meta['class_body'] ) ) {
            $classes[] = sanitize_html_class( $postscript_meta['class_body'] );
        }
    }

    return $classes;
}
add_filter( 'body_class', 'postscript_class_body' );


/**
 * Adds user-entered class(es) to the post class list.
 *
 * @uses postscript_get_options()   Safely gets option from database.
 * @return  array $classes  WordPress defaults and user-added classes
 */
function postscript_class_post( $classes ) {
    $post_id = get_the_ID();
    $options = [
		'user_roles' => [ 'administrator' ],
		'post_types' => [ 'post', 'page' ],
		'allow' => [ 'class_body' => 'on', 'class_post' => 'on' ]
	];

    if ( ! empty( $post_id ) && isset( $options['allow']['class_post'] ) ) {
        // Get the custom post class.
        $postscript_meta = get_post_meta( $post_id, 'postscript_meta', true );

        // If a post class was input, sanitize it and add it to the post class array.
        if ( ! empty( $postscript_meta['class_post'] ) ) {
            $classes[] = sanitize_html_class( $postscript_meta['class_post'] );
        }
    }

    return $classes;
}
add_filter( 'post_class', 'postscript_class_post' );
