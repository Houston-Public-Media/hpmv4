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
		wp_enqueue_style( 'hpm-style', get_template_directory_uri().'/style'.HPM_TEST.'.css', [], date('Y-m-d-H') );
		wp_enqueue_script( 'hpm-js', get_template_directory_uri().'/js/main'.HPM_TEST.'.js', [], date('Y-m-d-H'), true );
	else :
		wp_enqueue_style( 'hpm-style', 'https://cdn.hpm.io/assets/css/style.css', [], $versions['css'] );
		wp_enqueue_script( 'hpm-js', 'https://cdn.hpm.io/assets/js/main.js', [], $versions['js'], true );
	endif;
	wp_enqueue_script( 'hpm-analytics', 'https://cdn.hpm.io/assets/js/analytics/index.js', [], $versions['analytics'], false );
	wp_register_script( 'hpm-plyr', 'https://cdn.hpm.io/assets/js/plyr/plyr.js', [], date('Y-m-d-H'), true );

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
		$classes[] = 'menu-item-' . $item->ID;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
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
			elseif ( $depth > 0 && in_array('nav-back', $classes ) ) :
				$item_output .= '<div>';
			else :
				$item_output .= '<div class="nav-top">';
			endif;
		else :
			if ( $item->description != '' ) :
				$item_output .= '<p>'.$item->description.'</p>';
			endif;
			$item_output .= '<a'. $attributes .'>';
		endif;
		/** This filter is documented in wp-includes/post-template.php */
		if ( $item->url !== '#' && in_array( 'nav-passport', $classes ) ) :
			$item_output .= '<span class="hidden">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span><?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 488.8 80" style="enable-background:new 0 0 488.8 80;" xml:space="preserve" aria-hidden="true"> <style type="text/css"> .st0{fill:#0A145A;} .st1{fill:#5680FF;} .st2{fill:#FFFFFF;} </style> <g> <g> <path class="st0" d="M246.2,18c2.6,1.2,4.8,3.1,6.3,5.5s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2H232v15.6h-11.1V16h15.2C239.5,15.9,243,16.6,246.2,18z M241.1,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.9-2.2-5.2c-1.6-1.3-3.6-1.9-5.7-1.8H232v14.5h3C237.2,39.5,239.4,38.7,241.1,37.2L241.1,37.2z"/> <path class="st0" d="M284.5,31.4c2.6,2.6,3.9,6.1,3.9,10.7v21.8H280l-1.2-3c-1.3,1.1-2.9,2-4.5,2.6c-1.9,0.7-4,1.1-6.1,1.1 c-3.1,0.1-6.2-0.9-8.5-2.9c-2.2-2.1-3.4-5-3.2-8.1c0-4.2,1.6-7.2,4.7-9c3.6-2,7.6-2.9,11.7-2.8c1.7,0,3.4,0.1,5.1,0.4 c0.1-1.7-0.4-3.4-1.4-4.8c-0.9-1.1-2.8-1.7-5.6-1.7c-1.9,0-3.8,0.2-5.6,0.7c-1.9,0.4-3.8,1.1-5.6,1.9v-8.6c4.2-1.5,8.6-2.3,13-2.3 C278,27.5,281.9,28.8,284.5,31.4z M268.4,55.5c0.9,0.7,2,1.1,3.2,1c2.3-0.1,4.5-0.8,6.3-2.1v-5.7c-1.1-0.1-2.2-0.2-3.3-0.2 c-1.8-0.1-3.6,0.3-5.3,1c-1.3,0.6-2.1,1.9-2,3.4C267.2,53.9,267.6,54.8,268.4,55.5z"/> <path class="st0" d="M294.5,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.7-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.8,0.5,10,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.2,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C302,64.7,298.1,64,294.5,62.6z"/> <path class="st0" d="M325.1,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.8-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.7,0.5,9.9,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.1,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C332.5,64.7,328.7,64,325.1,62.6z"/> <path class="st0" d="M386.9,32.3c3.2,3.2,4.9,7.7,4.9,13.7c0.1,3.4-0.6,6.7-2.1,9.8c-1.3,2.7-3.3,5-5.9,6.6 c-2.7,1.6-5.8,2.4-9,2.3c-2.4,0.1-4.8-0.4-7.1-1.3v15.1h-10.5V30.4c5.2-1.8,10.7-2.8,16.2-2.9C379.1,27.5,383.6,29.1,386.9,32.3z M378.6,52.8c1.5-2.1,2.3-4.6,2.2-7.2c0-3-0.7-5.2-2.1-6.8s-3.5-2.5-5.7-2.4c-1.8,0-3.6,0.3-5.4,0.8v17.1c1.6,0.8,3.3,1.1,5,1.1 C374.9,55.6,377.1,54.6,378.6,52.8z"/> <path class="st0" d="M404.6,62.4c-2.8-1.5-5.1-3.7-6.6-6.4c-1.7-3.1-2.5-6.5-2.4-10c-0.1-3.5,0.7-6.9,2.4-9.9 c1.5-2.7,3.9-4.9,6.6-6.4c3-1.5,6.3-2.3,9.6-2.2c3.3,0,6.5,0.7,9.4,2.2c2.8,1.4,5.1,3.6,6.7,6.3c1.6,2.9,2.5,6.2,2.4,9.5 c0.1,3.6-0.7,7.1-2.4,10.2c-1.5,2.8-3.8,5.1-6.6,6.6c-3,1.6-6.3,2.3-9.6,2.3C410.8,64.7,407.5,63.9,404.6,62.4z M419.6,53.1 c1.4-1.7,2.1-4.2,2.1-7.4c0.2-2.4-0.6-4.9-2-6.8c-1.3-1.6-3.4-2.6-5.5-2.5c-2.1-0.1-4.2,0.8-5.5,2.4c-1.4,1.6-2.1,4-2.1,7.1 s0.7,5.5,2.1,7.2c2.5,3,6.9,3.4,10,1C419.1,53.8,419.4,53.5,419.6,53.1L419.6,53.1z"/> <path class="st0" d="M461,28.2v10.1c-0.7-0.2-1.4-0.4-2.1-0.5c-0.8-0.1-1.5-0.2-2.3-0.2c-1.5,0-3.1,0.4-4.4,1.1 c-1.3,0.7-2.3,1.6-3.2,2.8v22.4h-10.6V28.4h9.1l1.3,4.4c0.9-1.5,2.1-2.8,3.6-3.6c1.7-0.9,3.5-1.3,5.4-1.3 C458.9,27.8,460,27.9,461,28.2z"/> <path class="st0" d="M479.6,36.2v14.5c-0.1,1.4,0.3,2.8,1.1,4c1,1,2.4,1.5,3.8,1.4c1.4,0,2.7-0.2,4-0.6v8c-1,0.4-2.1,0.6-3.1,0.8 c-1.3,0.2-2.7,0.3-4,0.3c-4.1,0-7.2-1-9.3-3.1c-2-2.1-3.1-5.1-3.1-9V36.2h-5.5v-7.8h5.5v-7.7l10.6-2.9v10.6h9.2v7.8H479.6z"/> </g> <g> <path class="st0" d="M25.3,17.9c2.6,1.2,4.8,3,6.3,5.4s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2h-4.1v15.7H0V16h15.2C18.7,15.9,22.1,16.6,25.3,17.9z M20.2,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.8-2.1-5.1c-1.6-1.3-3.6-1.9-5.7-1.8h-3.3v14.5h3C16.4,39.5,18.6,38.7,20.2,37.2z"/> <path class="st0" d="M70.1,41.8c2,2.1,3,5,2.9,7.9c0.1,4-1.6,7.8-4.7,10.3s-7.5,3.8-13.2,3.8H38.3V16h15.6c5.2,0,9.1,1,11.9,3 c2.7,2,4.1,5,4.1,9c0.1,2.2-0.5,4.5-1.8,6.3c-1.1,1.7-2.6,3-4.4,3.7C66.1,38.6,68.4,39.9,70.1,41.8z M49.4,24.3v10.8h3.2 c1.7,0.1,3.3-0.4,4.5-1.5c1.1-1.1,1.7-2.6,1.6-4.2c0.1-1.4-0.5-2.8-1.5-3.8c-1.3-1-2.8-1.4-4.4-1.3H49.4z M59.6,53.7 c1.3-1.2,1.9-2.9,1.8-4.6c0.1-1.7-0.6-3.3-1.9-4.4c-1.2-1-3.1-1.6-5.7-1.6h-4.4v12.3h4.4C56.5,55.3,58.4,54.8,59.6,53.7z"/> <path class="st0" d="M83.3,63.8c-2.1-0.4-4.2-1-6.2-1.9V51.5c2,1,4,1.9,6.2,2.5c2.2,0.7,4.4,1,6.7,1c2,0.1,3.9-0.3,5.7-1.2 c1.2-0.7,1.9-2,1.9-3.4s-0.8-2.8-2-3.5c-2.2-1.5-4.6-2.7-7.1-3.7c-4.1-1.8-7.1-3.8-8.9-6c-1.9-2.3-2.9-5.1-2.8-8.1 c0-2.6,0.8-5.2,2.3-7.3c1.6-2.2,3.8-3.8,6.3-4.8c2.9-1.1,6-1.7,9.1-1.7c2.2,0,4.4,0.1,6.6,0.5c1.7,0.3,3.4,0.7,5.1,1.3v9.7 c-3.3-1.3-6.8-1.9-10.3-1.9c-1.8-0.1-3.7,0.3-5.3,1c-1.2,0.6-2,1.8-2,3.2c0,0.9,0.4,1.7,1,2.3c0.8,0.7,1.6,1.2,2.5,1.7 c1.1,0.5,3.1,1.4,6,2.7c4,1.8,6.8,3.8,8.5,6.1s2.6,5.1,2.5,7.9c0.2,5.6-3.1,10.8-8.3,12.9c-3.2,1.3-6.6,2-10,1.9 C88.1,64.5,85.7,64.3,83.3,63.8z"/> </g> <g> <circle class="st1" cx="164.9" cy="40" r="40"/> <path class="st2" d="M164.8,4.5c-19.8,0-35.8,15.9-35.9,35.7c0,19.6,15.9,35.6,35.5,35.7c19.7,0.1,35.8-15.8,35.9-35.5 C200.4,20.7,184.5,4.6,164.8,4.5z M134.5,40.3L134.5,40.3l23.3,6.8l6.9,23.2C148.1,70.2,134.7,56.9,134.5,40.3z M157.8,33.2 L134.5,40c0.1-16.6,13.6-29.9,30.2-30L157.8,33.2z M164.9,70.3L164.9,70.3l6.9-23.2l23.3-6.8C195,56.9,181.5,70.3,164.9,70.3z M171.8,33.2L165,10c16.6,0,30,13.4,30.1,30l0,0L171.8,33.2z"/> <polygon class="st2" points="151.3,49.2 146,58.9 155.7,53.6 154.7,50.2"/> <polygon class="st2" points="174.9,30.1 178.3,31.1 183.6,21.5 173.9,26.7"/> <polygon class="st2" points="178.3,49.2 174.9,50.2 173.9,53.6 183.6,58.9"/> <polygon class="st2" points="154.7,30.1 155.7,26.7 146,21.5 151.3,31.1"/> </g> </g> </svg>';
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
function rel_canonical_w_npr()
{
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
	echo '<section id="top-posts"><h4>Most Viewed</h4>'.analyticsPull().'</section>';
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

/*
 * Adding extra classes to posts based on Post Type
 * Edited version for newer post priority system
*/
function felix_type_class( $classes ) {
	$post_id = get_the_ID();
	if ( !empty( $post_id ) ) :
		$classes[] = 'felix-type-d';
	endif;

	return $classes;
}
add_filter( 'post_class', 'felix_type_class' );

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

function hpm_tvguide_url() {
	$tvguide = get_transient( 'hpm_tvguide_url' );
	if ( !empty( $tvguide ) ) :
		return $tvguide;
	endif;
	$remote = wp_remote_get( esc_url_raw( "https://cdn.hpm.io/assets/tvguide.json" ) );
	if ( is_wp_error( $remote ) ) :
		return "";
	else :
		$api = wp_remote_retrieve_body( $remote );
		$json = json_decode( $api, TRUE );
		$tvguide = $json['url'];
	endif;
	set_transient( 'hpm_tvguide_url', $tvguide, 900 );
	return $tvguide;
}

function login_checked_remember_me() {
	add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );

function rememberme_checked() {
	echo "<script>var rem = document.getElementById('rememberme');rem.checked = true;rem.labels[0].textContent = 'Stay Logged in for 2 Weeks';</script>";
}

function hpm_yt_embed_mod( $content ) {
	global $post;
	if ( preg_match( '/<iframe.+><\/iframe>/', $content ) ) :
		$doc = new DOMDocument();
		$doc->loadHTML( $content, LIBXML_NOWARNING | LIBXML_NOERROR );
		$doc->removeChild( $doc->doctype );
		$frame = $doc->getElementsByTagName( 'iframe' );
		foreach ( $frame as $f ) :
			$src = $f->getAttribute('src');
			if ( strpos( $src, 'youtube' ) !== false ) :
				$url = parse_url( $src );
				$ytid = str_replace( '/embed/', '', $url['path'] );
				$f->setAttribute( 'id', $ytid );
				if ( empty( $url['query'] ) ) :
					$f->setAttribute( 'src', $src . '?enablejsapi=1' );
				else :
					if ( strpos( $url['query'], 'enablejsapi' ) === false ) :
						$f->setAttribute( 'src', $src . '&enablejsapi=1' );
					endif;
				endif;
			endif;
			$load = $f->getAttribute('loading');
			if ( empty( $load ) ) :
				$f->setAttribute('loading','lazy');
			endif;
		endforeach;
		$content = $doc->saveHTML();
	endif;
	$content = str_replace( [ '<html><body>', '</body></html>' ], [ '', '' ], $content );
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
	if ( !empty( $_POST ) && $_POST['post_type'] === 'post' ) :
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

function hpm_footer_ads() {
	global $wp_query;
	$id = $wp_query->post->ID;
	$type = $wp_query->post->post_type;
	if ( $type === 'page' ) :
		if ( $id === 362776 || $id === 366638 ) :
			echo "<script>document.getElementById('main').insertAdjacentHTML('beforeend', '<h2 id=\"foot-banner\">These services are brought to you by our community of donors, foundations, and partners.</h2>');</script>";
		endif;
	elseif ( $type === 'post' ) :
		echo "<script>document.getElementById('main').insertAdjacentHTML('beforeend', '<h2 id=\"foot-banner\"><a href=\"/donate\">Stories like this are made possible by the generosity of our community of donors, foundations and corporate partners. If you value our reporting, join others and make a gift to Houston Public Media.<br /><br /><span class=\"donate\"><span class=\"fas fa-heart\"></span> DONATE</span></h2>');</script>";
	endif;
}
add_action( 'wp_footer', 'hpm_footer_ads', 100 );

function skip_apple_news( $post_id, $post ) {
	if ( WP_ENV !== 'production' ) :
		apply_filters( 'apple_news_skip_push', true, $post_id );
	endif;
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
		$sticknum = count( $hpm_priority['homepage'] );
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
			$postClass = get_post_class();
			$search = 'felix-type-';
			$felix_type = array_filter($postClass, function($el) use ($search) {
				return ( strpos($el, $search) !== false );
			});
			if ( !empty( $felix_type ) ) :
				$key = array_keys( $felix_type );
				unset( $postClass[$key[0]] );
			endif; ?>
			<article id="post-<?php the_ID(); ?>" <?php echo "class=\"".implode( ' ', $postClass )."\""; ?>>
				<?php
				if ( has_post_thumbnail() ) : ?>
					<div class="thumbnail-wrap" style="background-image: url(<?php the_post_thumbnail_url('thumbnail'); ?>)">
						<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true"></a>
					</div>
				<?php
				endif; ?>
				<header class="entry-header">
				<?php
					the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
					the_excerpt(); ?>
					<div class="screen-reader-text">
					<?php
						coauthors_posts_links( ' / ', ' / ', '<address class="vcard author">', '</address>', true );
						$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

						$time_string = sprintf( $time_string,
							esc_attr( get_the_date( 'c' ) ),
							get_the_date( 'F j, Y' )
						);

						printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span>%2$s</span>',
							_x( 'Posted on', 'Used before publish date.', 'hpmv2' ),
							$time_string
						); ?>
					</div>
				</header>
			</article>
<?php
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
		<h4>Share</h4>
		<div class="article-share-icon">
			<button data-href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" data-dialog="400:368">
				<span class="fab fa-facebook-f" aria-hidden="true"></span>
			</button>
		</div>
		<div class="article-share-icon">
			<button data-href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" data-dialog="364:250">
				<span class="fab fa-twitter" aria-hidden="true"></span>
			</button>
		</div>
		<div class="article-share-icon">
			<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
				<span class="fas fa-envelope" aria-hidden="true"></span>
			</a>
		</div>
		<div class="article-share-icon">
			<button data-href="https://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
				<span class="fab fa-linkedin-in" aria-hidden="true"></span>
			</button>
		</div>
	</div><?php
}