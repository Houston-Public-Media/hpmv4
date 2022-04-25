<?php
/**
 * @package WordPress
 * @subpackage HPM_v2
 * @since HPM 2.0
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since HPM 2.0
 */
function hpmv2_setup() {

	/*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'hpmv2', get_template_directory() . '/languages' );

	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages and set specific image sizes
	 */
	add_theme_support( 'post-thumbnails', [ 'post','page','shows','staff','podcasts' ] );


	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus([
		'head-main' => __( 'Main Header Menu', 'hpmv2' ),
		'footer' => __( 'Footer Menu', 'hpmv2' )
	]);

	/*
	 * Switch default core markup for search form, comment form, and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption' ] );
}
add_action( 'after_setup_theme', 'hpmv2_setup' );

/*
	Add excerpts to pages
*/
function wpcodex_add_excerpt_support_for_pages() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'wpcodex_add_excerpt_support_for_pages' );
add_filter( 'the_content', 'shortcode_unautop');

/*
 * Enqueue Typekit, FontAwesome, stylesheets, etc.
 */
$hpm_test = ( !empty( $_GET['version'] ) ? $_GET['version'] : '' );
if ( $hpm_test !== '-mod' ) :
	$hpm_test = '';
endif;
//$hpm_test = '-mod';
define('HPM_TEST', $hpm_test);
function hpm_scripts() {
	$versions = hpm_versions();
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'fontawesome', 'https://cdn.hpm.io/assets/fonts/fontawesome/css/all.css', [], '5.14.0' );

	// Load our main stylesheet.
	if ( WP_ENV == 'development' ) :
		wp_enqueue_style( 'hpm-style', get_template_directory_uri().'/style.css', [], time() );
		wp_enqueue_script( 'hpm-js', get_template_directory_uri().'/js/main'.HPM_TEST.'.js', [], date('Y-m-d-H'), true );
	else :
		wp_enqueue_style( 'hpm-style', 'https://cdn.hpm.io/assets/css/hpm.css', [], $versions['css'] );
		wp_enqueue_script( 'hpm-js', 'https://cdn.hpm.io/assets/js/hpm.js', [], $versions['js'], true );
	endif;

	wp_register_script( 'hpm-plyr', 'https://cdn.hpm.io/assets/js/plyr/plyr.js', [], $versions['js'], true );
	wp_register_script( 'hpm-splide', 'https://cdn.hpm.io/assets/js/splide-settings.js', [ 'hpm-splide-js' ], $versions['js'], true );
	wp_register_script( 'hpm-splide-js', 'https://cdn.hpm.io/assets/js/splide.min.js', [], $versions['js'], true );
	wp_register_style( 'hpm-splide-css', 'https://cdn.hpm.io/assets/css/splide.min.css', [], $versions['css'] );

	wp_deregister_script( 'wp-embed' );
	wp_deregister_script( 'better-image-credits' );
	wp_deregister_style( 'better-image-credits' );
	wp_deregister_style( 'gutenberg-pdfjs' );
	wp_deregister_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'hpm_scripts' );

/*
 * Modifies homepage query
 */
function homepage_meta_query( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) :
		$priority = get_option('hpm_priority');
		if ( !empty( $priority['homepage'] ) ) :
			$query->set( 'post__not_in', $priority['homepage'] );
		endif;
		$query->set( 'post_status', 'publish' );
		$query->set( 'category__not_in', [ 0, 1, 7636, 28, 37840 ] );
		$query->set( 'ignore_sticky_posts', 1 );
		$query->set( 'posts_per_page', 25 );
	endif;
}
add_action( 'pre_get_posts', 'homepage_meta_query' );

function hpm_exclude_category( $query ) {
	if ( $query->is_feed ) {
		$query->set('cat', '-37840');
	}
	return $query;
}
add_filter( 'pre_get_posts', 'hpm_exclude_category' );

/**
 * Load extra includes
 */
require( get_template_directory() . '/includes/amp.php' );
require( get_template_directory() . '/includes/google.php' );
if ( WP_ENV == 'development' ) :
	require( get_template_directory() . '/includes/head'.HPM_TEST.'.php' );
else :
	require( get_template_directory() . '/includes/head.php' );
endif;
require( get_template_directory() . '/includes/foot.php' );
require( get_template_directory() . '/includes/shortcodes.php' );

/*
 * Modification to the normal Menu Walker to add <div> elements in certain locations and remove links with '#' hrefs
 */
class HPMv2_Menu_Walker extends Walker_Nav_Menu {
	function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		foreach ( $classes as $k => $v ) :
			if ( strpos( $v, 'nav-' ) === FALSE && strpos( $v, 'has-children' ) === FALSE  ) :
				unset( $classes[$k] );
			endif;
		endforeach;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$id = '';
		$output .= $indent . '<li' . $id . $class_names .'>';
		$atts = [];
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$attributes = '';

		foreach ( $atts as $attr => $value ) :
			if ( ! empty( $value ) ) :
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			endif;
		endforeach;

		$item_output = $args->before;
		if ( $item->url == '#' ) :
			if ( $depth > 0 && !in_array( 'nav-back', $classes ) ) :
				$item_output .= '<div class="nav-top-head">';
			else :
				$item_output .= '<div tabindex="0" aria-expanded="false" aria-controls="'.$item->post_name.'-dropdown">';
			endif;
		else :
			if ( strpos( $item->url, WP_HOME ) === false && strpos( $attributes, 'noopener' ) === false ) :
				$attributes .= ' rel="noopener"';
			endif;
			$item_output .= '<a'. $attributes .' tabindex="0">';
		endif;
		if ( $item->url !== '#' && in_array( 'nav-passport', $classes ) ) :
			$item_output .= '<span style="text-indent:-9999px;">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span><?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="pbs-passport-logo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 488.8 80" style="enable-background:new 0 0 488.8 80;" xml:space="preserve" aria-hidden="true"> <style type="text/css"> .st0{fill:#0A145A;} .st1{fill:#5680FF;} .st2{fill:#FFFFFF;} </style> <g> <g> <path class="st0" d="M246.2,18c2.6,1.2,4.8,3.1,6.3,5.5s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2H232v15.6h-11.1V16h15.2C239.5,15.9,243,16.6,246.2,18z M241.1,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.9-2.2-5.2c-1.6-1.3-3.6-1.9-5.7-1.8H232v14.5h3C237.2,39.5,239.4,38.7,241.1,37.2L241.1,37.2z"/> <path class="st0" d="M284.5,31.4c2.6,2.6,3.9,6.1,3.9,10.7v21.8H280l-1.2-3c-1.3,1.1-2.9,2-4.5,2.6c-1.9,0.7-4,1.1-6.1,1.1 c-3.1,0.1-6.2-0.9-8.5-2.9c-2.2-2.1-3.4-5-3.2-8.1c0-4.2,1.6-7.2,4.7-9c3.6-2,7.6-2.9,11.7-2.8c1.7,0,3.4,0.1,5.1,0.4 c0.1-1.7-0.4-3.4-1.4-4.8c-0.9-1.1-2.8-1.7-5.6-1.7c-1.9,0-3.8,0.2-5.6,0.7c-1.9,0.4-3.8,1.1-5.6,1.9v-8.6c4.2-1.5,8.6-2.3,13-2.3 C278,27.5,281.9,28.8,284.5,31.4z M268.4,55.5c0.9,0.7,2,1.1,3.2,1c2.3-0.1,4.5-0.8,6.3-2.1v-5.7c-1.1-0.1-2.2-0.2-3.3-0.2 c-1.8-0.1-3.6,0.3-5.3,1c-1.3,0.6-2.1,1.9-2,3.4C267.2,53.9,267.6,54.8,268.4,55.5z"/> <path class="st0" d="M294.5,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.7-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.8,0.5,10,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.2,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C302,64.7,298.1,64,294.5,62.6z"/> <path class="st0" d="M325.1,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.8-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.7,0.5,9.9,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.1,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C332.5,64.7,328.7,64,325.1,62.6z"/> <path class="st0" d="M386.9,32.3c3.2,3.2,4.9,7.7,4.9,13.7c0.1,3.4-0.6,6.7-2.1,9.8c-1.3,2.7-3.3,5-5.9,6.6 c-2.7,1.6-5.8,2.4-9,2.3c-2.4,0.1-4.8-0.4-7.1-1.3v15.1h-10.5V30.4c5.2-1.8,10.7-2.8,16.2-2.9C379.1,27.5,383.6,29.1,386.9,32.3z M378.6,52.8c1.5-2.1,2.3-4.6,2.2-7.2c0-3-0.7-5.2-2.1-6.8s-3.5-2.5-5.7-2.4c-1.8,0-3.6,0.3-5.4,0.8v17.1c1.6,0.8,3.3,1.1,5,1.1 C374.9,55.6,377.1,54.6,378.6,52.8z"/> <path class="st0" d="M404.6,62.4c-2.8-1.5-5.1-3.7-6.6-6.4c-1.7-3.1-2.5-6.5-2.4-10c-0.1-3.5,0.7-6.9,2.4-9.9 c1.5-2.7,3.9-4.9,6.6-6.4c3-1.5,6.3-2.3,9.6-2.2c3.3,0,6.5,0.7,9.4,2.2c2.8,1.4,5.1,3.6,6.7,6.3c1.6,2.9,2.5,6.2,2.4,9.5 c0.1,3.6-0.7,7.1-2.4,10.2c-1.5,2.8-3.8,5.1-6.6,6.6c-3,1.6-6.3,2.3-9.6,2.3C410.8,64.7,407.5,63.9,404.6,62.4z M419.6,53.1 c1.4-1.7,2.1-4.2,2.1-7.4c0.2-2.4-0.6-4.9-2-6.8c-1.3-1.6-3.4-2.6-5.5-2.5c-2.1-0.1-4.2,0.8-5.5,2.4c-1.4,1.6-2.1,4-2.1,7.1 s0.7,5.5,2.1,7.2c2.5,3,6.9,3.4,10,1C419.1,53.8,419.4,53.5,419.6,53.1L419.6,53.1z"/> <path class="st0" d="M461,28.2v10.1c-0.7-0.2-1.4-0.4-2.1-0.5c-0.8-0.1-1.5-0.2-2.3-0.2c-1.5,0-3.1,0.4-4.4,1.1 c-1.3,0.7-2.3,1.6-3.2,2.8v22.4h-10.6V28.4h9.1l1.3,4.4c0.9-1.5,2.1-2.8,3.6-3.6c1.7-0.9,3.5-1.3,5.4-1.3 C458.9,27.8,460,27.9,461,28.2z"/> <path class="st0" d="M479.6,36.2v14.5c-0.1,1.4,0.3,2.8,1.1,4c1,1,2.4,1.5,3.8,1.4c1.4,0,2.7-0.2,4-0.6v8c-1,0.4-2.1,0.6-3.1,0.8 c-1.3,0.2-2.7,0.3-4,0.3c-4.1,0-7.2-1-9.3-3.1c-2-2.1-3.1-5.1-3.1-9V36.2h-5.5v-7.8h5.5v-7.7l10.6-2.9v10.6h9.2v7.8H479.6z"/> </g> <g> <path class="st0" d="M25.3,17.9c2.6,1.2,4.8,3,6.3,5.4s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2h-4.1v15.7H0V16h15.2C18.7,15.9,22.1,16.6,25.3,17.9z M20.2,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.8-2.1-5.1c-1.6-1.3-3.6-1.9-5.7-1.8h-3.3v14.5h3C16.4,39.5,18.6,38.7,20.2,37.2z"/> <path class="st0" d="M70.1,41.8c2,2.1,3,5,2.9,7.9c0.1,4-1.6,7.8-4.7,10.3s-7.5,3.8-13.2,3.8H38.3V16h15.6c5.2,0,9.1,1,11.9,3 c2.7,2,4.1,5,4.1,9c0.1,2.2-0.5,4.5-1.8,6.3c-1.1,1.7-2.6,3-4.4,3.7C66.1,38.6,68.4,39.9,70.1,41.8z M49.4,24.3v10.8h3.2 c1.7,0.1,3.3-0.4,4.5-1.5c1.1-1.1,1.7-2.6,1.6-4.2c0.1-1.4-0.5-2.8-1.5-3.8c-1.3-1-2.8-1.4-4.4-1.3H49.4z M59.6,53.7 c1.3-1.2,1.9-2.9,1.8-4.6c0.1-1.7-0.6-3.3-1.9-4.4c-1.2-1-3.1-1.6-5.7-1.6h-4.4v12.3h4.4C56.5,55.3,58.4,54.8,59.6,53.7z"/> <path class="st0" d="M83.3,63.8c-2.1-0.4-4.2-1-6.2-1.9V51.5c2,1,4,1.9,6.2,2.5c2.2,0.7,4.4,1,6.7,1c2,0.1,3.9-0.3,5.7-1.2 c1.2-0.7,1.9-2,1.9-3.4s-0.8-2.8-2-3.5c-2.2-1.5-4.6-2.7-7.1-3.7c-4.1-1.8-7.1-3.8-8.9-6c-1.9-2.3-2.9-5.1-2.8-8.1 c0-2.6,0.8-5.2,2.3-7.3c1.6-2.2,3.8-3.8,6.3-4.8c2.9-1.1,6-1.7,9.1-1.7c2.2,0,4.4,0.1,6.6,0.5c1.7,0.3,3.4,0.7,5.1,1.3v9.7 c-3.3-1.3-6.8-1.9-10.3-1.9c-1.8-0.1-3.7,0.3-5.3,1c-1.2,0.6-2,1.8-2,3.2c0,0.9,0.4,1.7,1,2.3c0.8,0.7,1.6,1.2,2.5,1.7 c1.1,0.5,3.1,1.4,6,2.7c4,1.8,6.8,3.8,8.5,6.1s2.6,5.1,2.5,7.9c0.2,5.6-3.1,10.8-8.3,12.9c-3.2,1.3-6.6,2-10,1.9 C88.1,64.5,85.7,64.3,83.3,63.8z"/> </g> <g> <circle class="st1" cx="164.9" cy="40" r="40"/> <path class="st2" d="M164.8,4.5c-19.8,0-35.8,15.9-35.9,35.7c0,19.6,15.9,35.6,35.5,35.7c19.7,0.1,35.8-15.8,35.9-35.5 C200.4,20.7,184.5,4.6,164.8,4.5z M134.5,40.3L134.5,40.3l23.3,6.8l6.9,23.2C148.1,70.2,134.7,56.9,134.5,40.3z M157.8,33.2 L134.5,40c0.1-16.6,13.6-29.9,30.2-30L157.8,33.2z M164.9,70.3L164.9,70.3l6.9-23.2l23.3-6.8C195,56.9,181.5,70.3,164.9,70.3z M171.8,33.2L165,10c16.6,0,30,13.4,30.1,30l0,0L171.8,33.2z"/> <polygon class="st2" points="151.3,49.2 146,58.9 155.7,53.6 154.7,50.2"/> <polygon class="st2" points="174.9,30.1 178.3,31.1 183.6,21.5 173.9,26.7"/> <polygon class="st2" points="178.3,49.2 174.9,50.2 173.9,53.6 183.6,58.9"/> <polygon class="st2" points="154.7,30.1 155.7,26.7 146,21.5 151.3,31.1"/> </g> </g> </svg>';
		else :
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		endif;
		if ( $item->url == '#' ) :
			$item_output .= '</div>';
		else :
			$item_output .= '</a>';
		endif;
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/*
 * Modify page title for NPR API stories to reflect the title of the post
 */
function hpmv2_npr_article_title( $title ) {
	if ( is_page_template( 'page-npr-articles.php' ) ) :
		global $nprdata;
		return $nprdata['title']." | NPR &amp; Houston Public Media";
	endif;
	return $title;
}
add_filter( 'pre_get_document_title', 'hpmv2_npr_article_title' );


/*
 * Modify the canonical URL metadata in the head of NPR API-based posts
 */
function rel_canonical_w_npr() {
	if ( ! is_singular() ) :
		return;
	endif;

	if ( ! $id = get_queried_object_id() ) :
		return;
	endif;
	if ( is_page_template( 'page-npr-articles.php' ) ) :
		global $nprdata;
		$url = $nprdata['permalink'];
	else :
		$url = get_permalink( $id );
		$page = get_query_var( 'page' );
		if ( $page >= 2 ) :
			if ( '' == get_option( 'permalink_structure' ) ) :
				$url = add_query_arg( 'page', $page, $url );
			else :
				$url = trailingslashit( $url ) . user_trailingslashit( $page, 'single_paged' );
			endif;
		endif;

		$cpage = get_query_var( 'cpage' );
		if ( $cpage ) :
			$url = get_comments_pagenum_link( $cpage );
		endif;
	endif;
	echo '<link rel="canonical" href="' . esc_url( $url ) . "\" />\n";
}

if ( function_exists( 'rel_canonical' ) ) :
	remove_action( 'wp_head', 'rel_canonical' );
endif;
add_action( 'wp_head', 'rel_canonical_w_npr' );

/*
 * Set up Category Tag metadata for posts
 */
add_action( 'load-post.php', 'hpm_cat_tag_setup' );
add_action( 'load-post-new.php', 'hpm_cat_tag_setup' );
function hpm_cat_tag_setup() {
	add_action( 'add_meta_boxes', 'hpm_cat_tag_add_meta' );
	add_action( 'save_post', 'hpm_cat_tag_save_meta', 10, 2 );
}

function hpm_cat_tag_add_meta() {
	add_meta_box(
		'hpm-cat-tag-meta-class',
		esc_html__( 'Category Tag', 'example' ),
		'hpm_cat_tag_meta_box',
		'post',
		'side',
		'core'
	);
}

/*
 * Add Category Tag metadata boxes to the editor
 */
function hpm_cat_tag_meta_box( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'hpm_cat_tag_class_nonce' );

    $hpm_cat_tag = get_post_meta( $object->ID, 'hpm_cat_tag', true );
    if ( empty( $hpm_cat_tag ) ) :
		$hpm_cat_tag = '';
	endif;
	?>
	<p><?PHP _e( "Enter the category tag for this post", 'example' ); ?></p>
	<ul>
		<li><label for="hpm-cat-tag"><?php _e( "Category Tag:", 'example' ); ?></label> <input type="text" id="hpm-cat-tag" name="hpm-cat-tag" value="<?PHP echo $hpm_cat_tag; ?>" placeholder="News, Classical Classroom, etc." style="width: 60%;" /></li>
	</ul>
<?php }

/*
 * Saving the Category Tag metadata to the database
 */
function hpm_cat_tag_save_meta( $post_id, $post ) {
	if ( !isset( $_POST['hpm_cat_tag_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_cat_tag_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	$hpm_cat_tag = ( isset( $_POST['hpm-cat-tag'] ) ? $_POST['hpm-cat-tag'] : '' );

	if ( empty( $hpm_cat_tag ) ) :
        return $post_id;
	else :
		update_post_meta( $post_id, 'hpm_cat_tag', $hpm_cat_tag );
    endif;
}

/*
 * Pull custom category tag.  If one doesn't exist, return either the most deeply nested category, or a series or show category
 */
function hpm_top_cat( $post_id ) {
	$hpm_primary_cat = get_post_meta( $post_id, 'epc_primary_category', true );
	$hpm_cat_tag = get_post_meta( $post_id, 'hpm_cat_tag', true );
	if ( !empty( $hpm_cat_tag ) ) :
		return $hpm_cat_tag;
	elseif ( !empty( $hpm_primary_cat ) ) :
		return get_the_category_by_ID( $hpm_primary_cat );
	else :
		$categories = get_the_category( $post_id );
		$top_cat = array(
			'depth' => 0,
			'name' => ''
		);
		foreach ($categories as $cats) :
			$anc = get_ancestors( $cats->term_id, 'category' );
			if ( in_array( 9, $anc ) || in_array( 5, $anc ) ) :
				return $cats->name;
			elseif ( count( $anc ) >= $top_cat['depth'] ) :
				$top_cat = array(
					'depth' => count( $anc ),
					'name' => $cats->name
				);
			endif;
		endforeach;
		return $top_cat['name'];
	endif;
}

/*
 * Generate excerpt outside of the WP Loop
 */
function get_excerpt_by_id( $post_id ){
	$the_post = get_post( $post_id );
	if ( !empty( $the_post ) ) :
		$the_excerpt = $the_post->post_excerpt;
		if ( empty( $the_excerpt ) ) :
			$the_excerpt = $the_post->post_content;
			$excerpt_length = 55;
			$the_excerpt = wp_strip_all_tags( strip_shortcodes( $the_excerpt ), true );
			$words = explode(' ', $the_excerpt, $excerpt_length + 1);

			if ( count( $words ) > $excerpt_length ) :
				array_pop( $words );
				array_push( $words, '...' );
				$the_excerpt = implode( ' ', $words );
			endif;
		endif;
		return $the_excerpt;
	else :
		return '';
	endif;
}

/*
 * Display Top Posts
 */
function hpm_top_posts() {
	echo '<section class="highlights"><h4>Most Viewed</h4>'.analyticsPull().'</section>';
}

/*
 * Remove Generator tag from RSS feeds
 */
function remove_wp_version_rss() {
	return '';
}
add_filter( 'the_generator', 'remove_wp_version_rss' );

/*
 * Display word count for post
 */
function word_count( $post_id ) {
    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    return $word_count;
}

/*
 * Insert bug into posts of a selected category
 */
function prefix_insert_post_bug( $content ) {
	global $post;
	if ( is_single() && $post->post_type == 'post' ) :
		if ( in_category( 'election-2016' ) ) :
			$bug_code = '<div class="in-post-bug"><a href="/news/politics/election-2016/"><img src="https://cdn.hpm.io/wp-content/uploads/2016/03/21120957/ELECTION_crop.jpg" alt="Houston Public Media\'s Coverage of Election 2016"></a><h3><a href="/news/politics/election-2016/">Houston Public Media\'s Coverage of Election 2016</a></h3></div>';
			return prefix_insert_after_paragraph( $bug_code, 2, $content );
		elseif ( in_category( 'texas-legislature' ) ) :
			$bug_code = '<div class="in-post-bug"><a href="/news/politics/texas-legislature/"><img src="https://cdn.hpm.io/assets/images/TX_Lege_Article_Bug.jpg" alt="Special Coverage Of The 85th Texas Legislative Session"></a><h3><a href="/news/politics/texas-legislature/">Special Coverage Of The 85th Texas Legislative Session</a></h3></div>';
			return prefix_insert_after_paragraph( $bug_code, 2, $content );
		elseif ( in_category( 'in-depth' ) ) :
			if ( !preg_match( '/\[hpm_indepth\/\]/', $content ) ) :
				$bug_code = '<div class="in-post-bug in-depth"><a href="/topics/in-depth/">Click here for more inDepth features.</a></div>';
				return prefix_insert_after_paragraph( $bug_code, 5, $content );
			endif;
		endif;
	endif;
	return $content;
}
add_filter( 'the_content', 'prefix_insert_post_bug' );

function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) :
		if ( trim( $paragraph ) ) :
			$paragraphs[$index] .= $closing_p;
		endif;
		if ( $paragraph_id == $index + 1 ) :
			$paragraphs[$index] .= $insertion;
		endif;
	endforeach;
	return implode( '', $paragraphs );
}

if ( !array_key_exists( 'hpm_filter_text' , $GLOBALS['wp_filter'] ) ) :
	add_filter( 'hpm_filter_text', 'wptexturize' );
	add_filter( 'hpm_filter_text', 'convert_smilies' );
	add_filter( 'hpm_filter_text', 'convert_chars' );
	add_filter( 'hpm_filter_text', 'wpautop' );
	add_filter( 'hpm_filter_text', 'shortcode_unautop' );
	add_filter( 'hpm_filter_text', 'do_shortcode' );
endif;

function hpm_login_logo() { ?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(https://cdn.hpm.io/assets/images/HPM-PBS-NPR-Color.png);
			height:85px;
			width:320px;
			background-size: 320px 85px;
			background-repeat: no-repeat;
			padding-bottom: 0;
		}
		.login form .forgetmenot {
			padding-top: 5px !important;
		}
		#login {
			width: 340px !important;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'hpm_login_logo' );

add_action('init', 'remove_plugin_image_sizes');

function remove_plugin_image_sizes() {
	remove_image_size( 'guest-author-32' );
    remove_image_size( 'guest-author-50' );
    remove_image_size( 'guest-author-64' );
    remove_image_size( 'guest-author-96' );
    remove_image_size( 'guest-author-128' );
}

function wpf_dev_char_limit() {
	?>
	<script type="text/javascript">
		var wpfInputs = document.querySelectorAll('.wpf-char-limit input, .wpf-char-limit textarea');
		Array.from(wpfInputs).forEach((inp) => {
			inp.setAttribute('maxlength', 1000);
		});
	</script>
	<?php
}
add_action( 'wpforms_wp_footer', 'wpf_dev_char_limit' );

function login_checked_remember_me() {
	add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );

function rememberme_checked() {
	echo "<script>var rem = document.getElementById('rememberme');rem.checked = true;rem.labels[0].textContent = 'Stay Logged in for 2 Weeks';</script>";
}

function hpm_yt_embed_mod( $content ) {
	global $post;
	preg_match_all( '/<iframe.+>/', $content, $iframes );
	foreach ( $iframes[0] as $i ) :
		$new = $i;
		if ( strpos( $new, 'loading="lazy"' ) === false ) :
			$new = str_replace( '<iframe', '<iframe loading="lazy"', $new );
		endif;
		if ( strpos( $new, 'youtube.com' ) !== false ) :
			preg_match( '/src="(https:\/\/w?w?w?\.?youtube.com\/embed\/[a-zA-Z0-9\.\/:\-_#;\?&=]+)"/', $new, $src );
			if ( !empty( $src ) ) :
				$parse = parse_url( html_entity_decode( $src[1] ) );
				if ( !empty( $parse['query'] ) ) :
					$exp = explode( '&', $parse['query'] );
					if ( !in_array( 'enablejsapi=1', $exp ) ) :
						$exp[] = 'enablejsapi=1';
						$parse['query'] = implode( '&', $exp );
					endif;
				else :
					$parse['query'] = 'enablejsapi=1';
				endif;
				$url = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . $parse['query'];
				$new = str_replace( $src[1], $url, $new );
				$ytid = str_replace( '/embed/', '', $parse['path'] );
				if ( strpos( $new, 'id="' ) === false ) :
					$new = str_replace( '<iframe', '<iframe id="'.$ytid.'"', $new );
				endif;
			endif;
		endif;
		if ( strpos( $new, 'title="' ) === false ) :
			preg_match( '/src="https:\/\/([a-zA-Z0-9_\-\.]+)\//', $new, $domain );
			if ( !empty( $domain ) ) :
				$new = str_replace( '<iframe', '<iframe title="'.$domain[1].' embed"', $new );
			endif;
		endif;
		$content = str_replace( $i, $new, $content );
	endforeach;
	return $content;
}
add_filter( 'the_content', 'hpm_yt_embed_mod', 999 );

function hpm_charset_clean( $content ) {
	$find = [ ' ', '…', '’', '“', '”' ];
	$replace = [ ' ', '...', "'", '"', '"' ];
	return str_replace( $find, $replace, $content );
}
add_filter( 'the_content', 'hpm_charset_clean', 10 );

function hpm_revue_signup( $content ) {
	global $post;
	if ( is_single() && $post->post_type == 'post' ) :
		if ( in_category( 'news' ) ) :
			$content .= "<div id=\"revue-embed\">
<h2>Subscribe to <em>Today in Houston</em></h2>
<p>Fill out the form below to subscribe our new daily editorial newsletter from the HPM Newsroom.</p>
<form action=\"https://www.getrevue.co/profile/TodayInHouston/add_subscriber\" method=\"post\" id=\"revue-form\" name=\"revue-form\" target=\"_blank\">
<div class=\"revue-form-group\"><label for=\"member_email\">Email*</label><input class=\"revue-form-field\" placeholder=\"Email (Required)\" type=\"email\" name=\"member[email]\" id=\"member_email\"></div>
<div class=\"revue-form-group\"><label for=\"member_first_name\">First Name</label><input class=\"revue-form-field\" placeholder=\"First Name\" type=\"text\" name=\"member[first_name]\" id=\"member_first_name\"></div>
<div class=\"revue-form-group\"><label for=\"member_last_name\">Last Name</label><input class=\"revue-form-field\" placeholder=\"Last Name\" type=\"text\" name=\"member[last_name]\" id=\"member_last_name\"></div>
<div class=\"revue-form-actions\"><p class=\"revue-small\">* required</p><input type=\"submit\" value=\"Subscribe\" name=\"member[subscribe]\" id=\"member_submit\"></div></form></div>";
		endif;
	endif;
	return $content;
}
add_filter( 'the_content', 'hpm_revue_signup', 8 );


function hpm_nprone_check( $post_id, $post ) {
	if ( !empty( $_POST ) && !empty( $_POST['post_type'] ) && $_POST['post_type'] === 'post' ) :
		$coauthors = get_coauthors( $post_id );
		$local = false;
		foreach ( $coauthors as $coa ) :
			if ( is_a( $coa, 'wp_user' ) ) :
				$local = true;
			elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) :
				if ( !empty( $coa->linked_account ) ) :
					$local = true;
				endif;
			endif;
		endforeach;
		if ( $local ) :
			if ( !preg_match( '/\[audio.+\]\[\/audio\]/', $post->post_content ) ) :
				unset( $_POST['send_to_one'] );
				unset( $_POST['nprone_featured'] );
			endif;
		else :
			unset( $_POST['send_to_api'] );
			unset( $_POST['send_to_one'] );
			unset( $_POST['nprone_featured'] );
		endif;
	endif;
}
add_action( 'save_post', 'hpm_nprone_check', 2, 2 );
add_action( 'publish_post', 'hpm_nprone_check', 2, 2 );

add_filter( 'apple_news_skip_push', 'hpm_skip_apple_news', 10, 2);
function hpm_skip_apple_news( $skip = false ) {
	if ( WP_ENV !== 'production' ) :
		$skip = true;
	endif;
	return $skip;
}

function election_homepage() {
	$election_args = [
		'p' => 248126,
		'post_type'  => 'page',
		'post_status' => 'publish'
	];
	$election = new WP_Query( $election_args );
	if ( $election->have_posts() ) :
		while ( $election->have_posts() ) :
			$election->the_post();
			the_content();
		endwhile;
		wp_reset_postdata();
	endif;
}

function hpm_homepage_articles() {
	$articles = [];
	$hpm_priority = get_option( 'hpm_priority' );
	if ( !empty( $hpm_priority['homepage'] ) ) :
		$sticknum = count( $hpm_priority['homepage'] );
		if ( empty( $hpm_priority['homepage'][1] ) ) :
			$indepth = new WP_Query([
				'posts_per_page' => 2,
				'cat' => 29328,
				'ignore_sticky_posts' => 1,
				'post_status' => 'publish'
			]);
			if ( $indepth->have_posts() ) :
				if ( $hpm_priority['homepage'][0] == $indepth->posts[0]->ID ) :
					$hpm_priority['homepage'][1] = $indepth->posts[1]->ID;
				else :
					$hpm_priority['homepage'][1] = $indepth->posts[0]->ID;
				endif;
			endif;
		endif;
		$sticky_args = [
			'posts_per_page' => $sticknum,
			'post__in'  => $hpm_priority['homepage'],
			'orderby' => 'post__in',
			'ignore_sticky_posts' => 1
		];
		$sticky_query = new WP_Query( $sticky_args );
		if ( $sticky_query->have_posts() ) :
			foreach ( $sticky_query->posts as $stp ) :
				$articles[] = $stp;
			endforeach;
		endif;
	endif;
	global $wp_query;
	if ( $wp_query->have_posts() ) :
		foreach ( $wp_query->posts as $wpp ) :
			$articles[] = $wpp;
		endforeach;
	endif;
	return $articles;
}

function hpm_priority_indepth() {
	$hpm_priority = get_option( 'hpm_priority' );
	if ( !empty( $hpm_priority['indepth'] ) ) :
		$indepth = [
			'posts_per_page' => 1,
			'p' => $hpm_priority['indepth'],
			'post_status' => 'publish'
		];
	else :
		$indepth = [
			'posts_per_page' => 1,
			'cat' => 29328,
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish'
		];
	endif;
	$indepth_query = new WP_Query( $indepth );
	if ( $indepth_query->have_posts() ) :
		while ( $indepth_query->have_posts() ) : $indepth_query->the_post();
			get_template_part( 'content', get_post_format() );
		endwhile;
	endif;
	wp_reset_query();
}

function hpm_article_share($nprdata = null) {
	global $post;
	if ( empty( $nprdata ) ) :
		$uri_title = rawurlencode( get_the_title() );
		$facebook_link = rawurlencode( get_the_permalink().'?utm_source=facebook-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$twitter_link = rawurlencode( get_the_permalink().'?utm_source=twitter-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$linkedin_link = rawurlencode( get_the_permalink().'?utm_source=linked-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$uri_excerpt = rawurlencode( get_the_excerpt() );
	else :
		$uri_title = rawurlencode( $nprdata['title'] );
		$facebook_link = rawurlencode( $nprdata['permalink'].'?utm_source=facebook-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$twitter_link = rawurlencode( $nprdata['permalink'].'?utm_source=twitter-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$linkedin_link = rawurlencode( $nprdata['permalink'].'?utm_source=linked-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$uri_excerpt = rawurlencode( $nprdata['excerpt'] );
	endif; ?>
	<div id="article-share">
		<div class="social-wrap">
			<h4>Share</h4>
			<div class="social-icon facebook">
				<button aria-label="Share to Facebook" data-href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" data-dialog="400:368">
					<span class="fab fa-facebook-f" aria-hidden="true"></span>
				</button>
			</div>
			<div class="social-icon twitter">
				<button aria-label="Share to Twitter" data-href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" data-dialog="364:250">
					<span class="fab fa-twitter" aria-hidden="true"></span>
				</button>
			</div>
			<div class="social-icon linkedin">
				<button aria-label="Share to LinkedIn" data-href="https://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
					<span class="fab fa-linkedin-in" aria-hidden="true"></span>
				</button>
			</div>
			<div class="social-icon">
				<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
					<span class="fas fa-envelope" aria-hidden="true"></span>
				</a>
			</div>
		</div>
	</div><?php
}

function hpm_site_logo() {
	$output = '<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston"><svg id="head-logo" data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true"><path class="cls-1" d="M.35,4.35H11.46V30.53H42.82V4.35H53.93V67.26H42.82V40H11.46V67.26H.35Z"/><path class="cls-1" d="M75.1,65.37A20.64,20.64,0,0,1,66.78,57a26.65,26.65,0,0,1-3-13,26.63,26.63,0,0,1,3-13.09,20.74,20.74,0,0,1,8.38-8.38,25.22,25.22,0,0,1,12.16-2.87A24.3,24.3,0,0,1,99.3,22.53a20.81,20.81,0,0,1,8.28,8.33,25.42,25.42,0,0,1,3,12.53,27.28,27.28,0,0,1-3.06,13.28,21.35,21.35,0,0,1-8.42,8.61,25.86,25.86,0,0,1-24,.09ZM96,55.1c2.31-2.69,3.46-6.52,3.46-11.52q0-7-3.33-10.92a11.1,11.1,0,0,0-8.88-3.88,11.52,11.52,0,0,0-8.93,3.83c-2.31,2.57-3.47,6.31-3.47,11.25S76,52.41,78.34,55.1A11.73,11.73,0,0,0,96,55.1Z"/><path class="cls-1" d="M159.66,20.63V67.26h-9L150,63.19a28.89,28.89,0,0,1-6.53,3.66,23,23,0,0,1-8.55,1.43q-7.5,0-11.57-4.25t-4.07-11.94V20.63h10.55V49.78q0,9.16,8.51,9.16a12.3,12.3,0,0,0,6.24-1.62,16.34,16.34,0,0,0,4.58-3.75V20.63Z"/><path class="cls-1" d="M169.21,65.51V55.79a25.11,25.11,0,0,0,6.38,2.59,28,28,0,0,0,7.5,1.11,13,13,0,0,0,6.48-1.38,4.17,4.17,0,0,0,2.4-3.7,4.76,4.76,0,0,0-1.9-4q-1.89-1.49-7.44-3.43-7.32-2.58-10.41-5.78a11.5,11.5,0,0,1-3.1-8.37,11.18,11.18,0,0,1,2.17-6.71,14.66,14.66,0,0,1,6.34-4.77,25.68,25.68,0,0,1,10-1.76,39.4,39.4,0,0,1,7.08.61A27.84,27.84,0,0,1,200,21.65v9.07a28.53,28.53,0,0,0-5.36-1.62,29.82,29.82,0,0,0-6.21-.69,15.12,15.12,0,0,0-6.52,1.15c-1.57.77-2.36,1.77-2.36,3a3.56,3.56,0,0,0,1.76,3.1,39,39,0,0,0,6.66,3l2.13.83q6.75,2.6,9.53,5.83t2.78,9a12.21,12.21,0,0,1-4.91,10.18q-4.91,3.8-14,3.79A35.21,35.21,0,0,1,169.21,65.51Z"/><path class="cls-1" d="M223.36,28.87V52.28q0,6.84,6.48,6.84a18.48,18.48,0,0,0,5.83-.92V67a17.49,17.49,0,0,1-3.75.92,30.87,30.87,0,0,1-4.86.37q-7.31,0-10.77-3.79t-3.47-10.27V28.87h-6.76V20.63h6.76V10.55L223.36,7.4V20.63H236v8.24Z"/><path class="cls-1" d="M251,65.37A20.66,20.66,0,0,1,242.69,57a26.76,26.76,0,0,1-3-13,26.53,26.53,0,0,1,3.05-13.09,20.72,20.72,0,0,1,8.37-8.38,25.26,25.26,0,0,1,12.17-2.87,24.33,24.33,0,0,1,11.94,2.92,20.94,20.94,0,0,1,8.28,8.33,25.41,25.41,0,0,1,3,12.53,27.28,27.28,0,0,1-3.05,13.28A21.42,21.42,0,0,1,275,65.28a25.86,25.86,0,0,1-24,.09ZM271.93,55.1c2.31-2.69,3.47-6.52,3.47-11.52q0-7-3.33-10.92a11.1,11.1,0,0,0-8.88-3.88,11.51,11.51,0,0,0-8.93,3.83c-2.32,2.57-3.47,6.31-3.47,11.25s1.15,8.55,3.47,11.24a11.72,11.72,0,0,0,17.67,0Z"/><path class="cls-1" d="M332,23.87q4.4,4.26,4.4,12V67.26H325.8V37.94a9.73,9.73,0,0,0-2.13-6.67q-2.13-2.4-6.48-2.4a12.61,12.61,0,0,0-6.2,1.48,23.47,23.47,0,0,0-5,3.7V67.26H295.45V20.63h9.07l.92,4.26A26.8,26.8,0,0,1,312,21a21.46,21.46,0,0,1,8.05-1.39Q327.56,19.61,332,23.87Z"/><path class="cls-1" d="M408.1,9.76q6.15,5.41,6.15,14.3a21.1,21.1,0,0,1-2.92,11.19,19.72,19.72,0,0,1-8.28,7.5,27.76,27.76,0,0,1-12.58,2.68h-6.2V67.26h-11.1V4.35h17.49Q401.94,4.35,408.1,9.76ZM399.4,33a11.42,11.42,0,0,0,3.47-8.55,10,10,0,0,0-3.47-8.1c-2.31-1.94-5.45-2.91-9.39-2.91h-5.74V36.36h5.18Q395.93,36.36,399.4,33Z"/><path class="cls-1" d="M461.72,20.63V67.26h-9L452,63.19a28.89,28.89,0,0,1-6.53,3.66,23,23,0,0,1-8.56,1.43q-7.49,0-11.56-4.25t-4.07-11.94V20.63h10.55V49.78q0,9.16,8.51,9.16a12.3,12.3,0,0,0,6.24-1.62,16.49,16.49,0,0,0,4.58-3.75V20.63Z"/><path class="cls-1" d="M506,22.48a19.57,19.57,0,0,1,7.54,8.15,26.65,26.65,0,0,1,2.68,12.21A26.9,26.9,0,0,1,513,56.16a22.5,22.5,0,0,1-9,8.93,27.37,27.37,0,0,1-13.46,3.19,48.76,48.76,0,0,1-8.7-.83,48.15,48.15,0,0,1-8.42-2.31V0H484V21.93a24.67,24.67,0,0,1,10.64-2.32A21.92,21.92,0,0,1,506,22.48Zm-4.77,32.34q3.88-4.3,3.89-11.33,0-6.57-3.19-10.55a10.43,10.43,0,0,0-8.56-4,19.09,19.09,0,0,0-9.35,2.5V57.92a18.43,18.43,0,0,0,6.94,1.2A13.16,13.16,0,0,0,501.22,54.82Z"/><path class="cls-1" d="M525.38,0h10.55V67.26H525.38Z"/><path class="cls-1" d="M548.52,12a5.77,5.77,0,0,1-1.9-4.44,5.87,5.87,0,0,1,1.85-4.44,6.47,6.47,0,0,1,4.63-1.76,6.65,6.65,0,0,1,4.62,1.76,5.71,5.71,0,0,1,1.95,4.44A5.71,5.71,0,0,1,557.72,12a6.65,6.65,0,0,1-4.62,1.76A6.5,6.5,0,0,1,548.52,12Zm-.7,8.6h10.55V67.26H547.82Z"/><path class="cls-1" d="M578.6,65.23a20.73,20.73,0,0,1-8.42-8.51A26.46,26.46,0,0,1,567.26,44a26.29,26.29,0,0,1,3-12.63,21.32,21.32,0,0,1,8.65-8.65,27.82,27.82,0,0,1,13.65-3.15,29,29,0,0,1,11.38,2v9.72a23.35,23.35,0,0,0-10.55-2.22q-7.12,0-11.05,4.11t-3.94,10.87q0,6.85,3.89,10.74a13.89,13.89,0,0,0,10.27,3.88,29.93,29.93,0,0,0,6.2-.64,20.24,20.24,0,0,0,5.18-1.76v9.71a31.51,31.51,0,0,1-12.3,2.22A26.44,26.44,0,0,1,578.6,65.23Z"/><path class="cls-1" d="M699.48,4.35V67.26H689V24.43L669.68,55.05h-2.77l-19.52-30.9V67.26H637V4.35h10l21.65,35.16L690.32,4.35Z"/><path class="cls-1" d="M752.44,46.82H720.61q1.47,12.49,14.62,12.49a29.53,29.53,0,0,0,14.89-3.7v9.53a29.47,29.47,0,0,1-7,2.27,42.66,42.66,0,0,1-8.65.87,28.27,28.27,0,0,1-13.37-3,21,21,0,0,1-8.75-8.52,26.23,26.23,0,0,1-3-12.9,27.24,27.24,0,0,1,2.87-12.73,20.8,20.8,0,0,1,8-8.51,23,23,0,0,1,11.8-3,21,21,0,0,1,11.15,2.92,19.06,19.06,0,0,1,7.26,8,25.12,25.12,0,0,1,2.5,11.33A20,20,0,0,1,752.44,46.82ZM724.82,31.27a13,13,0,0,0-3.93,7.59h21.83a11.79,11.79,0,0,0-3.14-7.31,9.69,9.69,0,0,0-7.41-3A10.62,10.62,0,0,0,724.82,31.27Z"/><path class="cls-1" d="M802.16,0V64.77a53.38,53.38,0,0,1-18.51,3.51q-11,0-17.62-6.29t-6.62-18a26.51,26.51,0,0,1,2.92-12.63,21.1,21.1,0,0,1,8.28-8.6A24.29,24.29,0,0,1,783,19.61a19.89,19.89,0,0,1,8.6,1.76V0ZM791.61,58V30.72a15.8,15.8,0,0,0-7.68-1.85,12.28,12.28,0,0,0-9.71,4.21q-3.71,4.2-3.7,11.15,0,7.13,3.74,11.05a12.74,12.74,0,0,0,9.67,3.94A22.11,22.11,0,0,0,791.61,58Z"/><path class="cls-1" d="M814.64,12a5.77,5.77,0,0,1-1.9-4.44,5.87,5.87,0,0,1,1.85-4.44,6.48,6.48,0,0,1,4.63-1.76,6.69,6.69,0,0,1,4.63,1.76,5.74,5.74,0,0,1,1.94,4.44A5.74,5.74,0,0,1,823.85,12a6.69,6.69,0,0,1-4.63,1.76A6.5,6.5,0,0,1,814.64,12Zm-.69,8.6h10.54V67.26H814Z"/><path class="cls-1" d="M868.1,24.38Q873,29.15,873,38V67.26h-8.42L863.24,63a21.94,21.94,0,0,1-14.43,5.27q-6.94,0-11.06-3.88t-4.11-10.55q0-8,5.82-11.57t15-3.6a35.94,35.94,0,0,1,8,.83v-.46q0-4.9-2-7.64t-8.15-2.73a31.65,31.65,0,0,0-7.45.93,35.16,35.16,0,0,0-7.26,2.59V22.58a44.73,44.73,0,0,1,16.1-3Q863.25,19.61,868.1,24.38ZM846.36,57.64a7.15,7.15,0,0,0,5,1.67,18.87,18.87,0,0,0,11-3.79V46.91a33.14,33.14,0,0,0-6.29-.56,19.27,19.27,0,0,0-8.19,1.58,5.63,5.63,0,0,0-3.38,5.55A5.4,5.4,0,0,0,846.36,57.64Z"/><path d="M15.4,124.21H6.34L4.48,129.5H0l9.2-24h3.38l9.24,24H17.31Zm-1.23-3.46-3.35-9.34-3.28,9.34Z"/><path d="M43.51,129.44a12.57,12.57,0,0,1-3-1v-4.19a13.19,13.19,0,0,0,3,1.34,11.55,11.55,0,0,0,3.44.56,5.64,5.64,0,0,0,3.4-.86A2.73,2.73,0,0,0,51.5,123a2.82,2.82,0,0,0-1.17-2.32,18.33,18.33,0,0,0-4.09-2.12,12.12,12.12,0,0,1-4.47-2.82,5.64,5.64,0,0,1-1.45-4,5.77,5.77,0,0,1,1.08-3.49,6.83,6.83,0,0,1,3-2.27,10.84,10.84,0,0,1,4.25-.79,18.33,18.33,0,0,1,3.24.24,20.64,20.64,0,0,1,2.57.67V110a16.69,16.69,0,0,0-2.52-.77,12.66,12.66,0,0,0-2.8-.32,6.07,6.07,0,0,0-3.15.71,2.28,2.28,0,0,0-1.19,2.08,2.19,2.19,0,0,0,.55,1.49,4.86,4.86,0,0,0,1.5,1.1c.63.31,1.69.79,3.17,1.42a11.59,11.59,0,0,1,4.44,3,6.15,6.15,0,0,1,1.38,4,6.87,6.87,0,0,1-1,3.68,6.66,6.66,0,0,1-2.94,2.52,11.08,11.08,0,0,1-4.71.92A16.47,16.47,0,0,1,43.51,129.44Z"/><path d="M78.2,126.08v3.42H64v-24H78.09V109h-9.9v6.76h9.23v3.42H68.19v7Z"/><path d="M99.48,129.5l-4.93-9.06c-.24,0-.59,0-1.06,0H91.13v9H86.9v-24h6.66a10.09,10.09,0,0,1,6.64,2,6.43,6.43,0,0,1,2.35,5.2,7.5,7.5,0,0,1-1.11,4.09,7.19,7.19,0,0,1-3.12,2.71l5.88,10Zm-2.55-13.63a4,4,0,0,0,1.28-3.05A3.45,3.45,0,0,0,96.93,110a5.71,5.71,0,0,0-3.62-1H91.13v8.1h2A5.49,5.49,0,0,0,96.93,115.87Z"/><path d="M130.28,105.53l-9.34,24h-3.45l-9.24-24h4.62l6.45,17.9,6.45-17.9Z"/><path d="M137.21,105.53h4.23v24h-4.23Z"/><path d="M155.7,128.32a10.81,10.81,0,0,1-4.3-4.31,12.83,12.83,0,0,1-1.54-6.32,13.56,13.56,0,0,1,1.54-6.54,10.8,10.8,0,0,1,4.4-4.41,13.9,13.9,0,0,1,6.72-1.56,18.1,18.1,0,0,1,5.78.88V110a14.41,14.41,0,0,0-5.43-1.09A9,9,0,0,0,158.3,110a7.39,7.39,0,0,0-2.94,3.1,10,10,0,0,0-1,4.6,9.34,9.34,0,0,0,1,4.39,7.33,7.33,0,0,0,2.82,3,8,8,0,0,0,4.18,1.08,14.49,14.49,0,0,0,5.92-1.27v3.91a13.74,13.74,0,0,1-2.78.79,19.52,19.52,0,0,1-3.46.27A12.83,12.83,0,0,1,155.7,128.32Z"/><path d="M190.91,126.08v3.42H176.67v-24H190.8V109h-9.9v6.76h9.23v3.42H180.9v7Z"/><path d="M217.18,128.3a10.86,10.86,0,0,1-4.19-4.36,13.31,13.31,0,0,1-1.5-6.39A13.67,13.67,0,0,1,213,111a10.5,10.5,0,0,1,4.21-4.32,12.66,12.66,0,0,1,6.28-1.51,12.51,12.51,0,0,1,6.16,1.49,10.65,10.65,0,0,1,4.25,4.27,13.14,13.14,0,0,1,1.53,6.47,13.69,13.69,0,0,1-1.49,6.52,10.73,10.73,0,0,1-4.23,4.37,12.8,12.8,0,0,1-6.4,1.55A12.1,12.1,0,0,1,217.18,128.3Zm11.74-4.43a9.26,9.26,0,0,0,2-6.39,10.58,10.58,0,0,0-.95-4.64,6.84,6.84,0,0,0-2.65-3,7.41,7.41,0,0,0-3.87-1,7.3,7.3,0,0,0-3.9,1.05,7.13,7.13,0,0,0-2.66,3,10.18,10.18,0,0,0-.95,4.51,10.78,10.78,0,0,0,.93,4.65,7,7,0,0,0,2.61,3,7.19,7.19,0,0,0,3.86,1A7,7,0,0,0,228.92,123.87Z"/><path d="M248,109v7.29h9v3.46h-9v9.73H243.8v-24h13.88V109Z"/><path d="M283.56,109H277v-3.45h17.34V109h-6.56V129.5h-4.22Z"/><path d="M301.67,105.53h4.23v10h12v-10h4.23v24h-4.23V119.1h-12v10.4h-4.23Z"/><path d="M346,126.08v3.42H331.78v-24h14.13V109H336v6.76h9.23v3.42H336v7Z"/><path d="M370.3,127.21q-2.54-2.65-2.54-7.9V105.53H372V119.2a7.94,7.94,0,0,0,1.48,5.15,5.17,5.17,0,0,0,4.23,1.8,5.08,5.08,0,0,0,4.2-1.84,8,8,0,0,0,1.48-5.14V105.53h4.3V119.1c0,3.55-.85,6.22-2.56,8s-4.17,2.72-7.42,2.72S372,129,370.3,127.21Z"/><path d="M418,105.53v24h-3.42L401,111.91V129.5h-3.8v-24h3.7l13.25,17.37V105.53Z"/><path d="M427.63,105.53h4.23v24h-4.23Z"/><path d="M460.8,105.53l-9.34,24H448l-9.24-24h4.62l6.45,17.9,6.45-17.9Z"/><path d="M482,126.08v3.42H467.73v-24h14.14V109H472v6.76h9.24v3.42H472v7Z"/><path d="M503.26,129.5l-4.94-9.06c-.23,0-.59,0-1.06,0H494.9v9h-4.23v-24h6.66a10.12,10.12,0,0,1,6.65,2,6.45,6.45,0,0,1,2.34,5.2,7.5,7.5,0,0,1-1.11,4.09,7.19,7.19,0,0,1-3.12,2.71l5.89,10Zm-2.56-13.63a3.93,3.93,0,0,0,1.29-3.05A3.43,3.43,0,0,0,500.7,110a5.68,5.68,0,0,0-3.61-1H494.9v8.1h2A5.46,5.46,0,0,0,500.7,115.87Z"/><path d="M517.53,129.44a12.5,12.5,0,0,1-3-1v-4.19a13.09,13.09,0,0,0,3,1.34,11.49,11.49,0,0,0,3.43.56,5.64,5.64,0,0,0,3.4-.86,2.7,2.7,0,0,0,1.15-2.28,2.82,2.82,0,0,0-1.17-2.32,18.2,18.2,0,0,0-4.08-2.12,12.17,12.17,0,0,1-4.48-2.82,5.68,5.68,0,0,1-1.44-4,5.76,5.76,0,0,1,1.07-3.49,6.89,6.89,0,0,1,3-2.27,10.84,10.84,0,0,1,4.25-.79,18.33,18.33,0,0,1,3.24.24,20.64,20.64,0,0,1,2.57.67V110a16.69,16.69,0,0,0-2.52-.77,12.59,12.59,0,0,0-2.8-.32,6.07,6.07,0,0,0-3.15.71,2.27,2.27,0,0,0-1.18,2.08,2.19,2.19,0,0,0,.54,1.49,5.12,5.12,0,0,0,1.5,1.1c.63.31,1.69.79,3.17,1.42a11.43,11.43,0,0,1,4.44,3,6.15,6.15,0,0,1,1.38,4,6.87,6.87,0,0,1-1,3.68,6.66,6.66,0,0,1-2.94,2.52,11.05,11.05,0,0,1-4.71.92A16.31,16.31,0,0,1,517.53,129.44Z"/><path d="M538,105.53h4.23v24H538Z"/><path d="M556.07,109h-6.55v-3.45h17.34V109H560.3V129.5h-4.23Z"/><path d="M593.67,105.53l-8.39,14.34v9.63H581v-9.56l-8.28-14.41h4.65l5.88,10.82,5.86-10.82Z"/><path d="M617.83,128.3a10.86,10.86,0,0,1-4.19-4.36,13.31,13.31,0,0,1-1.5-6.39,13.67,13.67,0,0,1,1.5-6.54,10.5,10.5,0,0,1,4.21-4.32,12.65,12.65,0,0,1,6.27-1.51,12.52,12.52,0,0,1,6.17,1.49,10.65,10.65,0,0,1,4.25,4.27,13.14,13.14,0,0,1,1.53,6.47,13.81,13.81,0,0,1-1.49,6.52,10.73,10.73,0,0,1-4.23,4.37,12.8,12.8,0,0,1-6.4,1.55A12.1,12.1,0,0,1,617.83,128.3Zm11.74-4.43a9.26,9.26,0,0,0,2-6.39,10.58,10.58,0,0,0-1-4.64,6.84,6.84,0,0,0-2.65-3,7.45,7.45,0,0,0-3.88-1,7.29,7.29,0,0,0-3.89,1.05,7.13,7.13,0,0,0-2.66,3,10.18,10.18,0,0,0-1,4.51,10.78,10.78,0,0,0,.93,4.65,6.92,6.92,0,0,0,2.61,3,7.19,7.19,0,0,0,3.86,1A7,7,0,0,0,629.57,123.87Z"/><path d="M648.67,109v7.29h9v3.46h-9v9.73h-4.22v-24h13.88V109Z"/><path d="M679.76,105.53H684v10h12v-10h4.23v24h-4.23V119.1H684v10.4h-4.23Z"/><path d="M714.26,128.3a10.82,10.82,0,0,1-4.2-4.36,13.31,13.31,0,0,1-1.5-6.39,13.67,13.67,0,0,1,1.5-6.54,10.5,10.5,0,0,1,4.21-4.32,13.67,13.67,0,0,1,12.45,0,10.69,10.69,0,0,1,4.24,4.27,13,13,0,0,1,1.54,6.47,13.7,13.7,0,0,1-1.5,6.52,10.73,10.73,0,0,1-4.23,4.37,12.77,12.77,0,0,1-6.4,1.55A12,12,0,0,1,714.26,128.3ZM726,123.87a9.26,9.26,0,0,0,2-6.39,10.58,10.58,0,0,0-.95-4.64,6.84,6.84,0,0,0-2.65-3,7.41,7.41,0,0,0-3.87-1,7.3,7.3,0,0,0-3.9,1.05,7.2,7.2,0,0,0-2.66,3,10.18,10.18,0,0,0-1,4.51,10.78,10.78,0,0,0,.93,4.65,7,7,0,0,0,2.61,3,7.19,7.19,0,0,0,3.86,1A7,7,0,0,0,726,123.87Z"/><path d="M743.18,127.21c-1.7-1.77-2.54-4.4-2.54-7.9V105.53h4.23V119.2a7.88,7.88,0,0,0,1.48,5.15,5.15,5.15,0,0,0,4.23,1.8,5.06,5.06,0,0,0,4.19-1.84,8,8,0,0,0,1.48-5.14V105.53h4.3V119.1c0,3.55-.85,6.22-2.55,8s-4.18,2.72-7.42,2.72S744.87,129,743.18,127.21Z"/><path d="M771.87,129.44a12.57,12.57,0,0,1-3-1v-4.19a13.19,13.19,0,0,0,3,1.34,11.6,11.6,0,0,0,3.44.56,5.64,5.64,0,0,0,3.4-.86,2.7,2.7,0,0,0,1.15-2.28,2.82,2.82,0,0,0-1.17-2.32,18.2,18.2,0,0,0-4.08-2.12,12.08,12.08,0,0,1-4.48-2.82,5.64,5.64,0,0,1-1.45-4,5.77,5.77,0,0,1,1.08-3.49,6.89,6.89,0,0,1,3-2.27,10.84,10.84,0,0,1,4.25-.79,18.33,18.33,0,0,1,3.24.24,20.64,20.64,0,0,1,2.57.67V110a16.69,16.69,0,0,0-2.52-.77,12.66,12.66,0,0,0-2.8-.32,6.07,6.07,0,0,0-3.15.71,2.28,2.28,0,0,0-1.19,2.08,2.19,2.19,0,0,0,.55,1.49,5,5,0,0,0,1.5,1.1c.63.31,1.69.79,3.17,1.42a11.43,11.43,0,0,1,4.44,3,6.15,6.15,0,0,1,1.38,4,6.87,6.87,0,0,1-1,3.68,6.66,6.66,0,0,1-2.94,2.52,11.08,11.08,0,0,1-4.71.92A16.47,16.47,0,0,1,771.87,129.44Z"/><path d="M796.55,109H790v-3.45h17.34V109h-6.55V129.5h-4.23Z"/><path d="M818.42,128.3a10.73,10.73,0,0,1-4.19-4.36,13.31,13.31,0,0,1-1.5-6.39,13.67,13.67,0,0,1,1.5-6.54,10.44,10.44,0,0,1,4.21-4.32,12.65,12.65,0,0,1,6.27-1.51,12.52,12.52,0,0,1,6.17,1.49,10.65,10.65,0,0,1,4.25,4.27,13.14,13.14,0,0,1,1.53,6.47,13.7,13.7,0,0,1-1.5,6.52,10.73,10.73,0,0,1-4.23,4.37,12.76,12.76,0,0,1-6.39,1.55A12.1,12.1,0,0,1,818.42,128.3Zm11.74-4.43a9.26,9.26,0,0,0,2-6.39,10.44,10.44,0,0,0-.95-4.64,6.76,6.76,0,0,0-2.64-3,7.45,7.45,0,0,0-3.88-1,7.27,7.27,0,0,0-3.89,1.05,7.13,7.13,0,0,0-2.66,3,10,10,0,0,0-1,4.51,10.77,10.77,0,0,0,.94,4.65,6.92,6.92,0,0,0,2.61,3,7.17,7.17,0,0,0,3.86,1A7,7,0,0,0,830.16,123.87Z"/><path d="M865.79,105.53v24h-3.42l-13.53-17.59V129.5H845v-24h3.7L862,122.9V105.53Z"/><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"/><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"/><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"/><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"/><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"/><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"/><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"/><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"/><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"/><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"/><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"/></svg></a>';
	return $output;
}

add_filter( 'the_excerpt', 'hpm_remove_autop', 0 );
add_filter( 'the_content', 'hpm_remove_autop', 0 );

function hpm_remove_autop( $content ) {
	if ( get_post_type() !== 'post' ) :
		remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
	endif;
	return $content;
}

function hpm_link_extract( $links ) {
	$output = '';
	if ( !empty( $links ) ) :
		if ( is_string( $links ) ) :
			$output = $links;
		elseif ( is_array( $links ) ) :
			foreach ( $links as $link ) :
				if ( empty( $link->type ) ) {
					continue;
				}
				if ( 'html' === $link->type ) :
					$output = $link->value;
				endif;
			endforeach;
		elseif ( $links instanceof NPRMLElement && !empty( $links->value ) ) :
			$output = $links->value;
		endif;
	endif;
	return $output;
}

function hpm_npr_byline( $author ) {
	$output = [];
	if ( !$author instanceof NPRMLElement && !empty( $author ) ) :
		return $output;
	endif;
	$output = [
		'name' => ( !empty( $author->name->value ) ? $author->name->value : '' ),
		'link' => ( !empty( $author->link ) ? hpm_link_extract( $author->link ) : '' )
	];
	return $output;
}

function hpm_pull_npr_story( $npr_id ) {
	$nprdata = [
		'title' => '',
		'excerpt' => '',
		'keywords' => [],
		'keywords_html' => [],
		'date' => '',
		'bylines' => [],
		'body' => '',
		'related' => [],
		'permalink' => '',
		'slug' => '',
		'image' => [
			'src' => 'https://cdn.hpm.io/assets/images/NPR-NEWS.gif',
			'width' => 600,
			'height' => 293,
			'mime-type' => 'image/gif'
		]
	];
	$npr = new NPRAPIWordpress();
	$npr->request([
		'id' => $npr_id,
		'fields' => 'all',
		'profileTypeId' => '1,15'
	]);
	$npr->parse();
	if ( !empty( $npr->stories[0] ) ) :
		$story = $npr->stories[0];
	endif;

	$use_npr_layout = ( !empty( get_option( 'dp_npr_query_use_layout' ) ) ? TRUE : FALSE );

	$npr_layout = $npr->get_body_with_layout( $story, $use_npr_layout );
	if ( !empty( $npr_layout['body'] ) ) :
		$nprdata['body'] = $npr_layout['body'];
	endif;

	// add the transcript
	$nprdata['body'] .= $npr->get_transcript_body( $story );

	// Use oEmbed to flesh out external embeds
	preg_match_all( '/<div class\="wp\-block\-embed__[ \-a-z0-9]+">\s+(.+)\s+<\/div>/', $nprdata['body'], $match );
	if ( !empty( $match[1] ) ) :
		foreach ( $match[1] as $k => $v ) :
			$embed = wp_oembed_get( $v );
			if ( strpos( $embed, '<iframe ' ) !== false ) :
				$embed = '<p>' . $embed . '</p>';
			endif;
			$nprdata['body'] = str_replace( $v, $embed, $nprdata['body'] );
		endforeach;
	endif;

	$story_date = new DateTime( $story->storyDate->value );
	$nprdata['date'] = $story_date->format( 'F j, Y, g:i A' );
	$nprdata['permalink'] = WP_HOME . '/npr/' . $story_date->format( 'Y/m/d/' ) . $npr_id . '/' . sanitize_title( $story->title->value ) . '/';

	if ( is_array( $story->byline ) ) :
		foreach( $story->byline as $single ) :
			$nprdata['bylines'][] = hpm_npr_byline( $single );
		endforeach;
	else :
		$nprdata['bylines'][] = hpm_npr_byline( $story->byline );
	endif;

	$nprdata['title'] = $story->title->value;
	if ( !empty( $story->teaser->value ) ) :
		$nprdata['excerpt'] = $story->teaser->value;
	elseif ( !empty( $story->miniTeaser->value ) ) :
		$nprdata['excerpt'] = $story->miniTeaser->value;
	endif;

	$slug = [];
	if ( !empty( $story->slug->value ) ) :
		$slug[] = $story->slug->value;
	endif;
	if ( !empty( $story->organization ) ) :
		if ( is_array( $story->organization ) ) :
			foreach ( $story->organization as $org ) :
				$slug[] = $org->name->value;
			endforeach;
		else :
			$slug[] = $story->organization->name->value;
		endif;
	endif;
	$nprdata['slug'] = implode( " | ", $slug );

	if ( !empty( $story->relatedLink ) ) :
		if ( is_array( $story->relatedLink ) ) :
			foreach( $story->relatedLink as $link ) :
				$nprdata['related'][] = [
					'text' => $link->caption->value,
					'link' => hpm_link_extract( $link->link )
				];
			endforeach;
		else :
			$nprdata['related'][] = [
				'text' => $story->relatedLink->caption->value,
				'link' => hpm_link_extract( $story->relatedLink->link )
			];
		endif;
	endif;

	if ( isset( $story->parent ) ) :
		foreach ( (array)$story->parent as $parent ) :
			if ( $parent->type == 'topic' || $parent->type == 'program' ) :
				$nprdata['keywords'][] = $parent->title->value;
				$nprdata['keywords_html'][] = '<a href="' . hpm_link_extract( $parent->link ) . '">' . $parent->title->value . '</a>';
			endif;
		endforeach;
	endif;

	if ( !empty( $story->image ) ) :
		foreach ( (array)$story->image as $image ) :
			if ( $image->type == 'primary' ) :
				if ( !empty( $image->crop ) ) :
					foreach ( $image->crop as $crop ) :
						if ( !empty( $crop->primary ) && $crop->primary == true ) :
							$nprdata['image']['src'] = $crop->src;
							$nprdata['image']['width'] = $crop->width;
							$nprdata['image']['height'] = $crop->height;
							$parse_url = parse_url( $crop->src );
							$ext = wp_check_filetype( $parse_url['path'] );
							$nprdata['image']['mime-type'] = $ext['type'];
						endif;
					endforeach;
				endif;
			endif;
		endforeach;
	endif;

	return $nprdata;
}