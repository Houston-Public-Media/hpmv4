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
function hpm_setup(): void {

	// Make theme available for translation.
	load_theme_textdomain( 'hpmv4', get_template_directory() . '/languages' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	register_nav_menus();

	// Enable support for Post Thumbnails on posts and pages and set specific image sizes
	add_theme_support( 'post-thumbnails', [ 'post', 'page', 'shows', 'staff', 'podcasts', 'event' ] );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption' ] );
}
add_action( 'after_setup_theme', 'hpm_setup' );

// Add excerpts to pages
function wpcodex_add_excerpt_support_for_pages(): void {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'wpcodex_add_excerpt_support_for_pages' );

// Enqueue Typekit, stylesheets, etc.
function hpm_scripts(): void {
	$versions = hpm_versions();

	wp_register_script( 'hpm-plyr', 'https://cdn.houstonpublicmedia.org/assets/js/plyr/plyr.js', [], $versions['js'], true );
	wp_register_script( 'hpm-splide', 'https://cdn.houstonpublicmedia.org/assets/js/splide-settings.js', [ 'hpm-splide-js' ], $versions['js'], true );
	wp_register_script( 'hpm-splide-js', 'https://cdn.houstonpublicmedia.org/assets/js/splide.min.js', [], $versions['js'], true );
	wp_enqueue_script( 'bootstrap-js', 'https://cdn.houstonpublicmedia.org/assets/bootstrap/js/bootstrap.min.js', [ 'jquery' ], '5.3.2', true);
	wp_register_style( 'hpm-splide-css', 'https://cdn.houstonpublicmedia.org/assets/css/splide.min.css', [], $versions['css'] );
	wp_enqueue_style( 'bootstrap-css', 'https://cdn.houstonpublicmedia.org/assets/bootstrap/css/bootstrap.min.css', false, '5.3.2' );

	wp_deregister_script( 'wp-embed' );
	wp_deregister_style( 'gutenberg-pdfjs' );
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'wp-block-style' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_deregister_style( 'classic-theme-styles' );
	wp_deregister_style( 'wpforms-gutenberg-form-selector' );
	wp_deregister_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'hpm_scripts' );

function hpm_inline_script(): void {
	if ( WP_ENV == 'production' ) {
		$js = file_get_contents( get_template_directory() . '/js/main.js' );
		echo '<script>' . $js . '</script>';
	} else {
		echo '<script src="' . get_template_directory_uri() . '/js/main.js"></script>';
	}
}
function hpm_inline_style(): void {
	if ( WP_ENV == 'production' ) {
		$styles = str_replace( [ "\n", "\t" ], [ '', '' ], file_get_contents( get_template_directory() . '/style.css' ) );
		$styles = preg_replace( '/\/\*([\n\t\sA-Za-z0-9:\/\-\.!@\(\){}#,;]+)\*\//', '', $styles );
		echo '<style>' . $styles . '</style>';
	} else {
		echo '<link rel="stylesheet" id="hpm-css" href="' . get_template_directory_uri() . '/style.css" type="text/css" media="all">';
	}

}

add_action( 'wp_footer', 'hpm_inline_script', 100 );
add_action( 'wp_head', 'hpm_inline_style', 100 );

/*
 * Modifies homepage query
 */
function homepage_meta_query( $query ): void {
	if ( $query->is_home() && $query->is_main_query() ) {
		$priority = get_option('hpm_priority');
		if ( !empty( $priority['homepage'] ) ) {
			$query->set( 'post__not_in', $priority['homepage'] );
		}
		$query->set( 'post_status', 'publish' );
		$query->set( 'category__not_in', [ 0, 1, 7636, 28, 37840, 54338, 60 ] );
		$query->set( 'ignore_sticky_posts', 1 );
		$query->set( 'posts_per_page', 25 );
	}
}
add_action( 'pre_get_posts', 'homepage_meta_query' );

function hpm_exclude_category( $query ) {
	if ( $query->is_feed ) {
		$query->set('cat', '-37840');
	}
	return $query;
}
add_filter( 'pre_get_posts', 'hpm_exclude_category' );

// Load extra includes
require( get_template_directory() . '/includes/amp.php' );
require( get_template_directory() . '/includes/google.php' );
require( get_template_directory() . '/includes/head.php' );
require( get_template_directory() . '/includes/foot.php' );
require( get_template_directory() . '/includes/shortcodes.php' );


// Get Time Difference in post datetime and current time
function hpm_calculate_datetime_difference( $pID ): bool|string {
	if ( $pID ) {
		$postTimeDifference = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
		return $postTimeDifference;
	}
	return false;
}

function hpm_custom_pagination_shortcode( $atts ): string {
	extract( shortcode_atts( [
		'pages' => 50,
		'range' => 4,
		'pageLink' => "/topics/coronavirus/page/"
	], $atts, 'multilink' ) );
	return hpm_custom_pagination( $pages, $range, $pageLink );
}
add_shortcode( 'hpm_custom_pagination_shortcode', 'hpm_custom_pagination_shortcode' );


// Custom Pagination
function hpm_custom_pagination( $pages = '', $range = 4, $pageLink = "" ): string {
	global $paged;
	$showitems = ( $range * 2 ) + 1; // links to show
	if ( empty( $paged ) ) {
		$paged = 1;
	}
	if ( empty( $pages ) ) {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if ( !$pages ) {
			$pages = 1;
		}
	}
	$output = '';
	if ( 1 !== $pages ) {
		$output .= '<div class="wp-pagenavi"><span class="pages">Page ' . $paged . ' of ' . $pages . '</span>';
		if ( $paged > 1 && $showitems < $pages ) {
			$output .= '<a href="' . ( !empty( $pageLink ) ? $pageLink . ( $paged - 1 ) : get_pagenum_link( $paged - 1 ) ) . '">< Previous</a>';
		}
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 !== $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				$numLink = !empty( $pageLink ) ? $pageLink . $i : get_pagenum_link( $i );
				$output .= ( $paged == $i ) ? '<span aria-current="page" class="current">' . $i . '</span>' : '<a class="page larger" href="' . $numLink . '">' . $i . '</a>';
			}
		}
		if ( $paged < $pages && $showitems < $pages ) {
			$nextLink = !empty( $pageLink ) ? $pageLink . ( $paged + 1 ) : get_pagenum_link( $paged + 1 );
			$output .= '<span><a href="' . $nextLink . '">»</a></span>';
		}
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			$lastLink = !empty( $pageLink) ? $pageLink . $pages : get_pagenum_link( $pages );
			$output .= '<a class="last" aria-label="Last Page" href="' . $lastLink . '">Last »</a>';
		}
		$output .= '</div>';
	}
	return $output;
}


// Modification to the normal Menu Walker to add <div> elements in certain locations and remove links with '#' hrefs
class HPM_Menu_Walker extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		if ( in_array( 'current-menu-item', $classes ) ||
			in_array( 'current-menu-ancestor', $classes ) ||
			in_array( 'current-menu-parent', $classes ) ||
			in_array( 'current_page_parent', $classes ) ||
			in_array( 'current_page_ancestor', $classes )
		) {
			$classes[] = "active";
		}
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$id = '';
		$output .= $indent . '<li' . $id . $class_names .'>';
		$atts = [];
		$atts['title']  = $item->attr_title ?? '';
		$atts['target'] = $item->target	 ?? '';
		$atts['rel']	= $item->xfn		?? '';
		$atts['href']   = $item->url		?? '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$attributes = '';

		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		if ( $item->url == '#' ) {
			if ( $depth > 0 && !in_array( 'nav-back', $classes ) ) {
				$item_output .= '<div class="nav-top-head">';
			} else {
				$item_output .= '<div tab-index="0" aria-expanded="false" aria-controls="'.$item->post_name.'-dropdown">';
			}
		} else {
			if ( !str_contains( $item->url, WP_HOME ) && !str_contains( $attributes, 'noopener' ) ) {
				$attributes .= ' rel="noopener"';
			}
			$item_output .= '<a'. $attributes .' tab-index="0">';
		}
		if ( $item->url !== '#' && in_array( 'nav-passport', $classes ) ) {
			$item_output .= '<span style="text-indent:-9999px;">' . apply_filters( 'the_title', $item->title, $item->ID ) . '</span><?xml version="1.0" encoding="utf-8"?><svg id="pbs-passport-logo" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 488.8 80" style="enable-background:new 0 0 488.8 80;" xml:space="preserve" aria-hidden="true"> <style type="text/css"> .st0{fill:#0A145A;} .st1{fill:#5680FF;} .st2{fill:#FFFFFF;} </style> <g> <g> <path class="st0" d="M246.2,18c2.6,1.2,4.8,3.1,6.3,5.5s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2H232v15.6h-11.1V16h15.2C239.5,15.9,243,16.6,246.2,18z M241.1,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.9-2.2-5.2c-1.6-1.3-3.6-1.9-5.7-1.8H232v14.5h3C237.2,39.5,239.4,38.7,241.1,37.2L241.1,37.2z"/> <path class="st0" d="M284.5,31.4c2.6,2.6,3.9,6.1,3.9,10.7v21.8H280l-1.2-3c-1.3,1.1-2.9,2-4.5,2.6c-1.9,0.7-4,1.1-6.1,1.1 c-3.1,0.1-6.2-0.9-8.5-2.9c-2.2-2.1-3.4-5-3.2-8.1c0-4.2,1.6-7.2,4.7-9c3.6-2,7.6-2.9,11.7-2.8c1.7,0,3.4,0.1,5.1,0.4 c0.1-1.7-0.4-3.4-1.4-4.8c-0.9-1.1-2.8-1.7-5.6-1.7c-1.9,0-3.8,0.2-5.6,0.7c-1.9,0.4-3.8,1.1-5.6,1.9v-8.6c4.2-1.5,8.6-2.3,13-2.3 C278,27.5,281.9,28.8,284.5,31.4z M268.4,55.5c0.9,0.7,2,1.1,3.2,1c2.3-0.1,4.5-0.8,6.3-2.1v-5.7c-1.1-0.1-2.2-0.2-3.3-0.2 c-1.8-0.1-3.6,0.3-5.3,1c-1.3,0.6-2.1,1.9-2,3.4C267.2,53.9,267.6,54.8,268.4,55.5z"/> <path class="st0" d="M294.5,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.7-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.8,0.5,10,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.2,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C302,64.7,298.1,64,294.5,62.6z"/> <path class="st0" d="M325.1,62.6V54c1.6,0.8,3.3,1.5,5,1.9c1.8,0.5,3.6,0.7,5.5,0.7c1.4,0.1,2.9-0.2,4.2-0.8 c0.9-0.3,1.5-1.2,1.5-2.2c0-0.6-0.2-1.2-0.5-1.7c-0.5-0.6-1.2-1-1.9-1.3c-1.4-0.6-2.7-1.2-4.1-1.6c-3.6-1.3-6.1-2.8-7.6-4.4 c-1.6-1.8-2.4-4.1-2.3-6.5c0-2,0.6-3.9,1.8-5.5c1.3-1.7,3.1-3,5.1-3.8c2.5-1,5.2-1.4,7.9-1.4c3.4-0.1,6.7,0.5,9.9,1.6v8.1 c-1.4-0.5-2.8-0.9-4.2-1.2c-1.6-0.3-3.2-0.5-4.8-0.5c-1.4-0.1-2.9,0.2-4.2,0.7c-1,0.5-1.5,1-1.5,1.7c0,0.6,0.3,1.2,0.8,1.6 c0.8,0.6,1.6,1,2.5,1.4c1.1,0.5,2.4,0.9,3.8,1.4c3.4,1.3,5.8,2.7,7.2,4.4c1.5,1.9,2.3,4.4,2.2,6.8c0.1,3.2-1.4,6.2-3.9,8.1 c-2.6,2-6.3,3-11.1,3C332.5,64.7,328.7,64,325.1,62.6z"/> <path class="st0" d="M386.9,32.3c3.2,3.2,4.9,7.7,4.9,13.7c0.1,3.4-0.6,6.7-2.1,9.8c-1.3,2.7-3.3,5-5.9,6.6 c-2.7,1.6-5.8,2.4-9,2.3c-2.4,0.1-4.8-0.4-7.1-1.3v15.1h-10.5V30.4c5.2-1.8,10.7-2.8,16.2-2.9C379.1,27.5,383.6,29.1,386.9,32.3z M378.6,52.8c1.5-2.1,2.3-4.6,2.2-7.2c0-3-0.7-5.2-2.1-6.8s-3.5-2.5-5.7-2.4c-1.8,0-3.6,0.3-5.4,0.8v17.1c1.6,0.8,3.3,1.1,5,1.1 C374.9,55.6,377.1,54.6,378.6,52.8z"/> <path class="st0" d="M404.6,62.4c-2.8-1.5-5.1-3.7-6.6-6.4c-1.7-3.1-2.5-6.5-2.4-10c-0.1-3.5,0.7-6.9,2.4-9.9 c1.5-2.7,3.9-4.9,6.6-6.4c3-1.5,6.3-2.3,9.6-2.2c3.3,0,6.5,0.7,9.4,2.2c2.8,1.4,5.1,3.6,6.7,6.3c1.6,2.9,2.5,6.2,2.4,9.5 c0.1,3.6-0.7,7.1-2.4,10.2c-1.5,2.8-3.8,5.1-6.6,6.6c-3,1.6-6.3,2.3-9.6,2.3C410.8,64.7,407.5,63.9,404.6,62.4z M419.6,53.1 c1.4-1.7,2.1-4.2,2.1-7.4c0.2-2.4-0.6-4.9-2-6.8c-1.3-1.6-3.4-2.6-5.5-2.5c-2.1-0.1-4.2,0.8-5.5,2.4c-1.4,1.6-2.1,4-2.1,7.1 s0.7,5.5,2.1,7.2c2.5,3,6.9,3.4,10,1C419.1,53.8,419.4,53.5,419.6,53.1L419.6,53.1z"/> <path class="st0" d="M461,28.2v10.1c-0.7-0.2-1.4-0.4-2.1-0.5c-0.8-0.1-1.5-0.2-2.3-0.2c-1.5,0-3.1,0.4-4.4,1.1 c-1.3,0.7-2.3,1.6-3.2,2.8v22.4h-10.6V28.4h9.1l1.3,4.4c0.9-1.5,2.1-2.8,3.6-3.6c1.7-0.9,3.5-1.3,5.4-1.3 C458.9,27.8,460,27.9,461,28.2z"/> <path class="st0" d="M479.6,36.2v14.5c-0.1,1.4,0.3,2.8,1.1,4c1,1,2.4,1.5,3.8,1.4c1.4,0,2.7-0.2,4-0.6v8c-1,0.4-2.1,0.6-3.1,0.8 c-1.3,0.2-2.7,0.3-4,0.3c-4.1,0-7.2-1-9.3-3.1c-2-2.1-3.1-5.1-3.1-9V36.2h-5.5v-7.8h5.5v-7.7l10.6-2.9v10.6h9.2v7.8H479.6z"/> </g> <g> <path class="st0" d="M25.3,17.9c2.6,1.2,4.8,3,6.3,5.4s2.2,5.2,2.1,8.1c0.1,3.1-0.7,6.2-2.4,8.9c-1.6,2.5-3.8,4.6-6.5,5.8 c-3,1.4-6.3,2.1-9.6,2h-4.1v15.7H0V16h15.2C18.7,15.9,22.1,16.6,25.3,17.9z M20.2,37.2c1.4-1.4,2.2-3.4,2.1-5.4 c0.1-2-0.7-3.8-2.1-5.1c-1.6-1.3-3.6-1.9-5.7-1.8h-3.3v14.5h3C16.4,39.5,18.6,38.7,20.2,37.2z"/> <path class="st0" d="M70.1,41.8c2,2.1,3,5,2.9,7.9c0.1,4-1.6,7.8-4.7,10.3s-7.5,3.8-13.2,3.8H38.3V16h15.6c5.2,0,9.1,1,11.9,3 c2.7,2,4.1,5,4.1,9c0.1,2.2-0.5,4.5-1.8,6.3c-1.1,1.7-2.6,3-4.4,3.7C66.1,38.6,68.4,39.9,70.1,41.8z M49.4,24.3v10.8h3.2 c1.7,0.1,3.3-0.4,4.5-1.5c1.1-1.1,1.7-2.6,1.6-4.2c0.1-1.4-0.5-2.8-1.5-3.8c-1.3-1-2.8-1.4-4.4-1.3H49.4z M59.6,53.7 c1.3-1.2,1.9-2.9,1.8-4.6c0.1-1.7-0.6-3.3-1.9-4.4c-1.2-1-3.1-1.6-5.7-1.6h-4.4v12.3h4.4C56.5,55.3,58.4,54.8,59.6,53.7z"/> <path class="st0" d="M83.3,63.8c-2.1-0.4-4.2-1-6.2-1.9V51.5c2,1,4,1.9,6.2,2.5c2.2,0.7,4.4,1,6.7,1c2,0.1,3.9-0.3,5.7-1.2 c1.2-0.7,1.9-2,1.9-3.4s-0.8-2.8-2-3.5c-2.2-1.5-4.6-2.7-7.1-3.7c-4.1-1.8-7.1-3.8-8.9-6c-1.9-2.3-2.9-5.1-2.8-8.1 c0-2.6,0.8-5.2,2.3-7.3c1.6-2.2,3.8-3.8,6.3-4.8c2.9-1.1,6-1.7,9.1-1.7c2.2,0,4.4,0.1,6.6,0.5c1.7,0.3,3.4,0.7,5.1,1.3v9.7 c-3.3-1.3-6.8-1.9-10.3-1.9c-1.8-0.1-3.7,0.3-5.3,1c-1.2,0.6-2,1.8-2,3.2c0,0.9,0.4,1.7,1,2.3c0.8,0.7,1.6,1.2,2.5,1.7 c1.1,0.5,3.1,1.4,6,2.7c4,1.8,6.8,3.8,8.5,6.1s2.6,5.1,2.5,7.9c0.2,5.6-3.1,10.8-8.3,12.9c-3.2,1.3-6.6,2-10,1.9 C88.1,64.5,85.7,64.3,83.3,63.8z"/> </g> <g> <circle class="st1" cx="164.9" cy="40" r="40"/> <path class="st2" d="M164.8,4.5c-19.8,0-35.8,15.9-35.9,35.7c0,19.6,15.9,35.6,35.5,35.7c19.7,0.1,35.8-15.8,35.9-35.5 C200.4,20.7,184.5,4.6,164.8,4.5z M134.5,40.3L134.5,40.3l23.3,6.8l6.9,23.2C148.1,70.2,134.7,56.9,134.5,40.3z M157.8,33.2 L134.5,40c0.1-16.6,13.6-29.9,30.2-30L157.8,33.2z M164.9,70.3L164.9,70.3l6.9-23.2l23.3-6.8C195,56.9,181.5,70.3,164.9,70.3z M171.8,33.2L165,10c16.6,0,30,13.4,30.1,30l0,0L171.8,33.2z"/> <polygon class="st2" points="151.3,49.2 146,58.9 155.7,53.6 154.7,50.2"/> <polygon class="st2" points="174.9,30.1 178.3,31.1 183.6,21.5 173.9,26.7"/> <polygon class="st2" points="178.3,49.2 174.9,50.2 173.9,53.6 183.6,58.9"/> <polygon class="st2" points="154.7,30.1 155.7,26.7 146,21.5 151.3,31.1"/> </g> </g> </svg>';
		} else {
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		}
		if ( $item->url == '#' ) {
			$item_output .= '</div>';
		} else {
			$item_output .= '</a>';
		}
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// Modify page title for NPR API stories to reflect the title of the post
function hpm_npr_article_title( $title ) {
	if ( is_page_template( 'page-npr-articles.php' ) ) {
		global $nprdata;
		return $nprdata['title']." | NPR &amp; Houston Public Media";
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'hpm_npr_article_title' );

/*Create Events Custom Post type starts here*/
add_action( 'init', 'create_hpmevent_post' );

function create_hpmevent_post(): void {
	register_post_type( 'event', [
		'labels' => [
			'name' => __( 'Events' ),
			'singular_name' => __( 'Event' ),
			'menu_name' => __( 'Events' ),
			'add_new_item' => __( 'Add New Event' ),
			'edit_item' => __( 'Edit Event' ),
			'new_item' => __( 'New Event' ),
			'view_item' => __( 'View Event' ),
			'search_items' => __( 'Search Event' ),
			'not_found' => __( 'Event Not Found' ),
			'not_found_in_trash' => __( 'Event not found in trash' )
		],
		'description' => 'Houston Public Media Event',
		'public' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-groups',
		'has_archive' => true,
		'rewrite' => [
			'slug' => __( 'event' ),
			'with_front' => false,
			'feeds' => false,
			'pages' => true
		],
		'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'author' ],
		'map_meta_cap' => true,
		'show_in_graphql' => true,
		'graphql_single_name' => 'Staff',
		'graphql_plural_name' => 'Staff'
	]);
}
add_action( 'admin_init', 'hpm_events_add_role_caps', 999 );
function hpm_events_add_role_caps(): void {
	// Add the roles you'd like to administer the custom post types
	$roles = [ 'editor', 'administrator', 'author' ];

	// Loop through each role and assign capabilities
	foreach( $roles as $the_role ) {
		$role = get_role( $the_role );
		$role->add_cap( 'read' );
		$role->add_cap( 'read_hpm_event');
		if ( $the_role !== 'author' ) {
			$role->add_cap( 'add_hpm_event' );
			$role->add_cap( 'add_hpm_events' );
			$role->add_cap( 'read_private_hpm_events' );
			$role->add_cap( 'edit_hpm_event' );
			$role->add_cap( 'edit_hpm_events' );
			$role->add_cap( 'edit_others_hpm_eventrs' );
			$role->add_cap( 'edit_published_hpm_events' );
			$role->add_cap( 'publish_hpm_events' );
			$role->add_cap( 'delete_others_hpm_events' );
			$role->add_cap( 'delete_private_hpm_events' );
			$role->add_cap( 'delete_published_hpm_events' );
		} else {
			$role->remove_cap( 'add_hpm_event' );
			$role->remove_cap( 'add_hpm_events' );
			$role->remove_cap( 'read_private_hpm_events' );
			$role->add_cap( 'edit_hpm_event' );
			$role->add_cap( 'edit_hpm_events' );
			$role->remove_cap( 'edit_others_hpm_events' );
			$role->remove_cap( 'edit_published_hpm_events' );
			$role->remove_cap( 'publish_hpm_events' );
			$role->remove_cap( 'delete_others_hpm_events' );
			$role->remove_cap( 'delete_private_hpm_events' );
			$role->remove_cap( 'delete_published_hpm_events' );
		}
	}
}

/*Create Events Custom Post type ends here*/

// Modify the canonical URL metadata in the head of NPR API-based posts
function rel_canonical_w_npr(): void {
	if ( !is_singular() ) {
		return;
	}

	if ( !$id = get_queried_object_id() ) {
		return;
	}

	if ( !empty( get_post_meta( $id, 'npr_html_link', 1 ) ) ) {
		return;
	}
	if ( is_page_template( 'page-npr-articles.php' ) ) {
		global $nprdata;
		$url = $nprdata['canonical'];
	} else {
		$url = get_permalink( $id );
		$page = get_query_var( 'page' );
		if ( $page >= 2 ) {
			if ( '' == get_option( 'permalink_structure' ) ) {
				$url = add_query_arg( 'page', $page, $url );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $page, 'single_paged' );
			}
		}

		$cpage = get_query_var( 'cpage' );
		if ( $cpage ) {
			$url = get_comments_pagenum_link( $cpage );
		}
	}
	echo '<link rel="canonical" href="' . esc_url( $url ) . "\" />\n";
}

if ( function_exists( 'rel_canonical' ) ) {
	remove_action( 'wp_head', 'rel_canonical' );
}
add_action( 'wp_head', 'rel_canonical_w_npr' );

// Set up Category Tag metadata for posts
add_action( 'load-post.php', 'hpm_cat_tag_setup' );
add_action( 'load-post-new.php', 'hpm_cat_tag_setup' );
function hpm_cat_tag_setup(): void {
	add_action( 'add_meta_boxes', 'hpm_cat_tag_add_meta' );
	add_action( 'save_post', 'hpm_cat_tag_save_meta', 10, 2 );
}

function hpm_cat_tag_add_meta(): void {
	add_meta_box(
		'hpm-cat-tag-meta-class',
		esc_html__( 'Category Tag', 'example' ),
		'hpm_cat_tag_meta_box',
		'post',
		'side',
		'core'
	);
}

// Add Category Tag metadata boxes to the editor
function hpm_cat_tag_meta_box( $object, $box ): void {
	wp_nonce_field( basename( __FILE__ ), 'hpm_cat_tag_class_nonce' );

	$hpm_cat_tag = get_post_meta( $object->ID, 'hpm_cat_tag', true );
	if ( empty( $hpm_cat_tag ) ) {
		$hpm_cat_tag = '';
	}
	?>
	<p><?PHP _e( "Enter the category tag for this post", 'example' ); ?></p>
	<ul>
		<li><label for="hpm-cat-tag"><?php _e( "Category Tag:", 'example' ); ?></label> <input type="text" id="hpm-cat-tag" name="hpm-cat-tag" value="<?PHP echo $hpm_cat_tag; ?>" placeholder="News, Classical Classroom, etc." style="width: 60%;" /></li>
	</ul>
<?php
}

// Saving the Category Tag metadata to the database
function hpm_cat_tag_save_meta( $post_id, $post ) {
	if ( !isset( $_POST['hpm_cat_tag_class_nonce'] ) || !wp_verify_nonce( $_POST['hpm_cat_tag_class_nonce'], basename( __FILE__ ) ) ) {
		return $post_id;
	}
	$post_type = get_post_type_object( $post->post_type );

	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}
	$hpm_cat_tag = ( $_POST['hpm-cat-tag'] ?? '' );

	if ( empty( $hpm_cat_tag ) ) {
		return $post_id;
	} else {
		update_post_meta( $post_id, 'hpm_cat_tag', $hpm_cat_tag );
	}
	return $post_id;
}

// Pull custom category tag.  If one doesn't exist, return either the most deeply nested category, or a series or show category
function hpm_top_cat( $post_id ) {
	$hpm_primary_cat = get_post_meta( $post_id, 'epc_primary_category', true );
	$hpm_cat_tag = get_post_meta( $post_id, 'hpm_cat_tag', true );
	if ( !empty( $hpm_cat_tag ) ) {
		return $hpm_cat_tag;
	} elseif ( !empty( $hpm_primary_cat ) ) {
		return get_the_category_by_ID( $hpm_primary_cat );
	}
	$categories = get_the_category( $post_id );
	$top_cat = [
		'depth' => 0,
		'name' => ''
	];
	foreach ( $categories as $cats ) {
		$anc = get_ancestors( $cats->term_id, 'category' );
		if ( in_array( 9, $anc ) || in_array( 5, $anc ) ) {
			return $cats->name;
		} elseif ( count( $anc ) >= $top_cat['depth'] ) {
			$top_cat = [
				'depth' => count( $anc ),
				'name' => $cats->name
			];
		}
	}

	return $top_cat['name'];
}

// Generate excerpt outside of the WP Loop
function get_excerpt_by_id( $post_id ): string {
	$the_post = get_post( $post_id );
	if ( !empty( $the_post ) ) {
		$the_excerpt = $the_post->post_excerpt;
		$excerpt_length = 55;
		if ( empty( $the_excerpt ) ) {
			$the_excerpt = $the_post->post_content;
			$the_excerpt = wp_strip_all_tags( strip_shortcodes( $the_excerpt ), true );
			$words = explode(' ', $the_excerpt, $excerpt_length + 1);
			if ( count( $words ) > $excerpt_length ) {
				array_pop( $words );
				$words[] = '...';
				$the_excerpt = implode( ' ', $words );
			}
		}
		return $the_excerpt;
	}
	return '';
}

function get_excerpt_by_id_ShowPages( $post_id ): string {
	$the_post = get_post( $post_id );
	if ( !empty( $the_post ) ) {
		$the_excerpt = $the_post->post_excerpt;
		$excerpt_length = 28;
		if ( empty( $the_excerpt ) ) {
			$the_excerpt = $the_post->post_content;
		}
		$the_excerpt = wp_strip_all_tags( strip_shortcodes( $the_excerpt ), true );
		$words = explode(' ', $the_excerpt, $excerpt_length + 1);

		if ( count( $words ) > $excerpt_length ) {
			array_pop( $words );
			$words[] = '...';
			$the_excerpt = implode( ' ', $words );
		}
		return $the_excerpt;
	}
	return '';
}

// Display Top Posts
function hpm_top_posts(): void {
	echo analyticsPull();
}

// Remove Generator tag from RSS feeds
function remove_wp_version_rss(): string {
	return '';
}
add_filter( 'the_generator', 'remove_wp_version_rss' );

// Insert bug into posts of a selected category
function prefix_insert_post_bug( $content ) {
	global $post;
	if ( is_single() && $post->post_type == 'post' ) {
		if ( in_category( 'election-2016' ) ) {
			$bug_code = '<div class="in-post-bug"><a href="/news/politics/election-2016/"><img src="https://cdn.houstonpublicmedia.org/wp-content/uploads/2016/03/21120957/ELECTION_crop.jpg" alt="Houston Public Media\'s Coverage of Election 2016"></a><h3><a href="/news/politics/election-2016/">Houston Public Media\'s Coverage of Election 2016</a></h3></div>';
			return prefix_insert_after_paragraph( $bug_code, 2, $content );
		} elseif ( in_category( 'texas-legislature' ) ) {
			$bug_code = '<div class="in-post-bug"><a href="/news/politics/texas-legislature/"><img src="https://cdn.houstonpublicmedia.org/assets/images/TX_Lege_Article_Bug.jpg" alt="Special Coverage Of The 85th Texas Legislative Session"></a><h3><a href="/news/politics/texas-legislature/">Special Coverage Of The 85th Texas Legislative Session</a></h3></div>';
			return prefix_insert_after_paragraph( $bug_code, 2, $content );
		} elseif ( in_category( 'in-depth' ) ) {
			if ( !preg_match( '/\[hpm_indepth ?\/?\]/', $content ) ) {
				$bug_code = '<div class="in-post-bug in-depth"><a href="/topics/in-depth/">Click here for more inDepth features.</a></div>';
				return prefix_insert_after_paragraph( $bug_code, 5, $content );
			}
		}
	}
	return $content;
}
add_filter( 'the_content', 'prefix_insert_post_bug', 9 );

function prefix_insert_after_paragraph( $insertion, $paragraph_id, $content ): string {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	foreach ($paragraphs as $index => $paragraph) {
		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}
		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $insertion;
		}
	}
	return implode( '', $paragraphs );
}

function hpm_login_logo(): void { ?>
	<style>
		#login h1 a, .login h1 a {
			background-image: url(https://cdn.houstonpublicmedia.org/assets/images/HPM-PBS-NPR-Color.png);
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

add_action( 'init', 'remove_plugin_image_sizes' );

function remove_plugin_image_sizes(): void {
	remove_image_size( 'guest-author-32' );
	remove_image_size( 'guest-author-50' );
	remove_image_size( 'guest-author-64' );
	remove_image_size( 'guest-author-96' );
	remove_image_size( 'guest-author-128' );
}

function wpf_dev_char_limit(): void {
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

function login_checked_remember_me(): void {
	add_filter( 'login_footer', 'rememberme_checked' );
}
add_action( 'init', 'login_checked_remember_me' );

function rememberme_checked(): void {
	echo "<script>let rem = document.getElementById('rememberme');rem.checked = true;rem.labels[0].textContent = 'Stay Logged in for 2 Weeks';</script>";
}

function hpm_yt_embed_mod( $content ) {
	global $post;
	preg_match_all( '/<iframe.+>/', $content, $iframes );
	foreach ( $iframes[0] as $i ) {
		$new = $i;
		if ( !str_contains( $new, 'loading="lazy"' ) ) {
			$new = str_replace( '<iframe', '<iframe loading="lazy"', $new );
		}
		if ( str_contains( $new, 'youtube.com' ) ) {
			preg_match( '/src="(https:\/\/w?w?w?\.?youtube.com\/embed\/[a-zA-Z0-9\.\/:\-_#;\?&=]+)"/', $new, $src );
			if ( !empty( $src ) ) {
				$parse = parse_url( html_entity_decode( $src[1] ) );
				if ( !empty( $parse['query'] ) ) {
					$exp = explode( '&', $parse['query'] );
					if ( !in_array( 'enablejsapi=1', $exp ) ) {
						$exp[] = 'enablejsapi=1';
						$parse['query'] = implode( '&', $exp );
					}
				} else {
					$parse['query'] = 'enablejsapi=1';
				}
				$url = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . $parse['query'];
				$new = str_replace( $src[1], $url, $new );
				$ytid = str_replace( '/embed/', '', $parse['path'] );
				if ( !str_contains( $new, 'id="' ) ) {
					$new = str_replace( '<iframe', '<iframe id="'.$ytid.'"', $new );
				}
			}
		}
		if ( !str_contains( $new, 'title="' ) ) {
			preg_match( '/src="https:\/\/([a-zA-Z0-9_\-\.]+)\//', $new, $domain );
			if ( !empty( $domain ) ) {
				$new = str_replace( '<iframe', '<iframe title="'.$domain[1].' embed"', $new );
			}
		}
		$content = str_replace( $i, $new, $content );
	}
	return $content;
}
add_filter( 'the_content', 'hpm_yt_embed_mod', 999 );

function hpm_charset_clean( $content ): array|string {
	$find = [ ' ', '…', '’', '“', '”' ];
	$replace = [ ' ', '...', "'", '"', '"' ];
	return str_replace( $find, $replace, $content );
}
add_filter( 'the_content', 'hpm_charset_clean', 10 );

function hpm_revue_signup( $content ) {
	global $post;
	if ( is_single() && $post->post_type == 'post' ) {
		if ( in_category( 'news' ) ) {
			$form_id = '441232';
			$content .= '<div id="revue-embed">' . do_shortcode( '[wpforms id="' . $form_id . '" title="true" description="true"]' ) . '</div>';
		}
	}
	return $content;
}
// add_filter( 'the_content', 'hpm_revue_signup', 15 );

function hpm_nprone_check( $post_id, $post ): void {
	if ( !empty( $_POST ) && !empty( $_POST['post_type'] ) && $_POST['post_type'] === 'post' ) {
		$coauthors = get_coauthors( $post_id );
		$local = false;
		foreach ( $coauthors as $coa ) {
			if ( is_a( $coa, 'wp_user' ) ) {
				$local = true;
			} elseif ( !empty( $coa->type ) && $coa->type == 'guest-author' ) {
				if ( !empty( $coa->linked_account ) ) {
					$local = true;
				}
			}
		}
		if ( $local ) {
			if ( !preg_match( '/\[audio.+\]\[\/audio\]/', $post->post_content ) ) {
				unset( $_POST['send_to_one'] );
				unset( $_POST['nprone_featured'] );
			} else {
				$_POST['send_to_one'] = 1;
			}
		} else {
			unset( $_POST['send_to_api'] );
			unset( $_POST['send_to_cds'] );
			unset( $_POST['send_to_one'] );
			unset( $_POST['nprone_featured'] );
		}
		if ( in_array( 60, $_POST['post_category'] ) ) {
			unset( $_POST['send_to_api'] );
			unset( $_POST['send_to_cds'] );
			unset( $_POST['send_to_one'] );
			unset( $_POST['nprone_featured'] );
		}
	}
}
add_action( 'save_post', 'hpm_nprone_check', 2, 2 );
add_action( 'publish_post', 'hpm_nprone_check', 2, 2 );

add_filter( 'apple_news_skip_push', 'hpm_skip_apple_news', 10, 2);
function hpm_skip_apple_news( $skip = false ) {
	if ( WP_ENV !== 'production' ) {
		$skip = true;
	}
	return $skip;
}

function election_homepage(): void {
	$election_args = [
		'p' => 248126,
		'post_type'  => 'page',
		'post_status' => 'publish'
	];
	$election = new WP_Query( $election_args );
	if ( $election->have_posts() ) {
		while ( $election->have_posts() ) {
			$election->the_post();
			the_content();
		}
		wp_reset_postdata();
	}
}

function hpm_homepage_modules( $catId ): array {
	$articles = [];
	if ( !empty( $catId ) ) {
		$catposts_args = [
			'posts_per_page' => 4,
			'category' => $catId,
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish'
		];
		$catposts_query = new WP_Query( $catposts_args );
		if ( $catposts_query->have_posts() ) {
			foreach ( $catposts_query->posts as $stp ) {
				$articles[] = $stp;
			}
		}
	}
	return $articles;
	wp_reset_query();
}

function hpm_showLatestArticlesbyShowID( $catID ): array {
	$articles = [];
	if ( !empty( $catID ) ) {
		$showposts_args = [
			'posts_per_page' => 3,
			'cat' => $catID,
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish'
		];
		$catposts_query = new WP_Query( $showposts_args );
		//print_r( $catposts_query);
		if ( $catposts_query->have_posts() ) {
			foreach ( $catposts_query->posts as $stp ) {
				$articles[] = $stp;
			}
		}
	}
	wp_reset_query();
	return $articles;
}

function altered_post_time_ago_function() {
	return ( get_the_time('U') >= strtotime('-1 week') ) ? sprintf( esc_html__( '%s ago', 'textdomain' ), human_time_diff( get_the_time ( 'U' ), current_time( 'timestamp' ) ) ) : get_the_date();
}
add_filter( 'the_time', 'altered_post_time_ago_function' );

function hpm_showTopthreeArticles( $articles ): string{
	$result = "";
	$kk = 0;
	if ( count( $articles ) > 0 ) {
		foreach ( $articles as $ka => $va ) {
			$post = $va;
			$post_title = get_the_title();
			if ( is_front_page() ) {
				$alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
				if ( !empty( $alt_headline ) ) {
					echo $alt_headline;
				} else {
					$post_title = get_the_title( $post );
				}
			} else {
				$post_title = get_the_title();
			}
			$summary = strip_tags( get_the_excerpt( $post ) );
			if ( $ka == 0 ) {
				if ( in_array( 'tag-breaking-news-button', get_post_class( '', $post->ID ) ) ) {
					$breakingNewsButton = '<div class="blue-label"><strong>Breaking News | </strong><span>'.hpm_top_cat( $post->ID ).'</span></div>';
				} else {
					$breakingNewsButton = '';
				}
				$result .= '<div class="col-lg-8 col-sm-12 breaking-news-first"><div class="row news-main"> <div class="col-sm-5">' . $breakingNewsButton . '<h1><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . $post_title . '</a></h1><p style="font-size: 0.875rem;">' . $summary . '</p></div><div class="col-sm-7"><div class="box-img breaking-news-img"><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . get_the_post_thumbnail( $post, $post->ID ) . ' </a></div> </div></div></div><div class="col-lg-4 col-sm-12"><ul class="news-listing row">';
			} elseif ( $ka == 1 || $ka == 2 ) {
				$result .= '<li class="col-lg-12 col-sm-6"><div class="d-flex flex-row-reverse"><div class="col-5"><div class="box-img"><a href="' . get_the_permalink( $post ) . '" rel="bookmark">' . get_the_post_thumbnail( $post, get_the_ID() ) . '</a></div></div>
									<div class="col-7"><h4 class="text-light-gray" style="color:#237bbd;"><a href="' . get_the_permalink( $post ) . '">' . hpm_top_cat( $post->ID ) . '</a></h4><p><a href="' . get_the_permalink( $post ) . '">' . get_the_title( $post ) . '</a></p></div></div> </li>';
			} elseif ( $ka > 3 ) {
					$result .= '</ul>';
			}
			$kk++;
		}
	}
	wp_reset_query();
	return $result;
}

function hpm_homepage_articles(): array {
	$articles = [];
	$hpm_priority = get_option( 'hpm_priority' );
	if ( !empty( $hpm_priority['homepage'] ) ) {
		if ( empty( $hpm_priority['homepage'][1] ) ) {
			$indepth = new WP_Query([
				'posts_per_page' => 2,
				'cat' => 29328,
				'ignore_sticky_posts' => 1,
				'post_status' => 'publish'
			]);
			if ( $indepth->have_posts() ) {
				if ( $hpm_priority['homepage'][0] == $indepth->posts[0]->ID ) {
					$hpm_priority['homepage'][1] = $indepth->posts[1]->ID;
				} else {
					$hpm_priority['homepage'][1] = $indepth->posts[0]->ID;
				}
			}
		}
		$sticknum = count( $hpm_priority['homepage'] );
		$sticky_args = [
			'posts_per_page' => $sticknum,
			'post__in'  => $hpm_priority['homepage'],
			'orderby' => 'post__in',
			'ignore_sticky_posts' => 1
		];
		$sticky_query = new WP_Query( $sticky_args );
		if ( $sticky_query->have_posts() ) {
			foreach ( $sticky_query->posts as $stp ) {
				$articles[] = $stp;
			}
		}
	}
	return $articles;
}

function hpm_priority_indepth(): void {
	$hpm_priority = get_option( 'hpm_priority' );
	if ( !empty( $hpm_priority['indepth'] ) ) {
		$indepth = [
			'posts_per_page' => 1,
			'p' => $hpm_priority['indepth'],
			'post_status' => 'publish'
		];
	} else {
		$indepth = [
			'posts_per_page' => 1,
			'cat' => 29328,
			'ignore_sticky_posts' => 1,
			'post_status' => 'publish'
		];
	}
	$indepth_query = new WP_Query( $indepth );
	if ( $indepth_query->have_posts() ) {
		while ( $indepth_query->have_posts() ) {
			$indepth_query->the_post();
			get_template_part( 'content', get_post_type() );
		}
	}
	wp_reset_query();
}

function hpm_article_share( $nprdata = null ): void {
	global $post;
	if ( empty( $nprdata ) ) {
		$uri_title = rawurlencode( get_the_title() );
		$facebook_link = rawurlencode( get_the_permalink().'?utm_source=facebook-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$twitter_link = rawurlencode( get_the_permalink().'?utm_source=twitter-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$linkedin_link = rawurlencode( get_the_permalink().'?utm_source=linked-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$uri_excerpt = rawurlencode( get_the_excerpt() );
	} else {
		$uri_title = rawurlencode( $nprdata['title'] );
		$facebook_link = rawurlencode( $nprdata['permalink'].'?utm_source=facebook-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$twitter_link = rawurlencode( $nprdata['permalink'].'?utm_source=twitter-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$linkedin_link = rawurlencode( $nprdata['permalink'].'?utm_source=linked-share-attachment&utm_medium=button&utm_campaign=hpm-share-link' );
		$uri_excerpt = rawurlencode( $nprdata['excerpt'] );
	} ?>
	<div id="article-share">
		<div class="icon-wrap">
			<h4>Share</h4>
			<div class="service-icon facebook">
				<button aria-label="Share to Facebook" data-href="https://www.facebook.com/sharer.php?u=<?php echo $facebook_link; ?>" data-dialog="400:368">
					<?php echo hpm_svg_output( 'facebook' ); ?><span class="screen-reader-text">Facebook</span>
				</button>
			</div>
			<div class="service-icon twitter">
				<button aria-label="Share to Twitter" data-href="https://twitter.com/intent/tweet?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" data-dialog="364:250">
					<?php echo hpm_svg_output( 'twitter' ); ?><span class="screen-reader-text">Twitter/X</span>
				</button>
			</div>
			<div class="service-icon linkedin">
				<button aria-label="Share to LinkedIn" data-href="https://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
					<?php echo hpm_svg_output( 'linkedin' ); ?><span class="screen-reader-text">LinkedIn</span>
				</button>
			</div>
			<div class="service-icon envelope">
				<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
					<?php echo hpm_svg_output( 'envelope' ); ?><span class="screen-reader-text">Email</span>
				</a>
			</div>
		</div>
	</div><?php
}

if ( !array_key_exists( 'hpm_filter_text' , $GLOBALS['wp_filter'] ) ) {
	add_filter( 'hpm_filter_text', 'wptexturize', 10 );
	add_filter( 'hpm_filter_text', 'convert_smilies', 20 );
	add_filter( 'hpm_filter_text', 'convert_chars', 10 );
	add_filter( 'hpm_filter_text', 'shortcode_unautop', 10 );
	add_filter( 'hpm_filter_text', 'wp_filter_content_tags', 11 );
	add_filter( 'hpm_filter_text', 'do_shortcode', 12 );
	add_filter( 'hpm_filter_text', 'wpautop', 13 );
}

remove_filter( 'the_content', 'do_shortcode', 11 );
remove_filter( 'the_content', 'wpautop', 10 );

add_filter( 'the_content', 'shortcode_unautop', 12 );
add_filter( 'the_content', 'do_shortcode', 13 );

add_filter( 'the_excerpt', 'hpm_add_autop', 2 );
add_filter( 'the_content', 'hpm_add_autop', 2 );

function hpm_add_autop( $content ) {
	if ( get_post_type() == 'post' ) {
		add_filter( 'the_content', 'wpautop', 11 );
		add_filter( 'the_excerpt', 'wpautop', 11 );
	}
	return $content;
}

function hpm_link_extract( $links ) {
	$output = '';
	if ( !empty( $links ) ) {
		if ( is_string( $links ) ) {
			$output = $links;
		} elseif ( is_array( $links ) ) {
			foreach ( $links as $link ) {
				if ( empty( $link->type ) ) {
					continue;
				}
				if ( 'html' === $link->type ) {
					$output = $link->value;
				}
			}
		} elseif ( $links instanceof NPRMLElement && !empty( $links->value ) && $links->type === 'html' ) {
			$output = $links->value;
		}
	}
	return $output;
}

function hpm_npr_byline( $author ): array {
	$output = [];
	if ( !$author instanceof NPRMLElement && !empty( $author ) ) {
		return $output;
	}
	$output = [
		'name' => ( $author->name->value ?? '' ),
		'link' => ( !empty( $author->link ) ? hpm_link_extract( $author->link ) : '' )
	];
	return $output;
}

/**
 * @throws Exception
 */
function hpm_pull_npr_story( $npr_id ) {
	$trans = get_transient( 'hpm_nprdata_' . $npr_id );
	if ( !empty( $trans ) ) {
		return $trans;
	}
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
		'canonical' => '',
		'slug' => '',
		'image' => [
			'src' => 'https://cdn.houstonpublicmedia.org/assets/images/NPR-NEWS.gif',
			'width' => 600,
			'height' => 293,
			'mime-type' => 'image/gif'
		]
	];
	if ( function_exists( 'npr_cds_activate' ) ) {
		$npr = new NPR_CDS_WP();
		$npr->request([
			'id' => $npr_id
		]);
		$npr->parse();
		if ( !empty( $npr->stories[0] ) ) {
			$story = $npr->stories[0];
		}

		$npr_body = $npr->get_body_with_layout( $story );

		$nprdata['body'] = $npr_body['body'];

		// add the transcript
		$nprdata['body'] .= $npr->get_transcript_body( $story );

		// Use oEmbed to flesh out external embeds
		preg_match_all( '/<div class\="wp\-block\-embed__[ \-a-z0-9]+">\s+(.+)\s+<\/div>/', $nprdata['body'], $match );
		if ( !empty( $match[1] ) ) {
			foreach ( $match[1] as $v ) {
				$embed = wp_oembed_get( $v );
				if ( str_contains( $embed, '<iframe ' ) ) {
					$embed = '<p>' . $embed . '</p>';
				}
				$nprdata['body'] = str_replace( $v, $embed, $nprdata['body'] );
			}
		}
		$webPage = '';
		if ( !empty( $story->webPages ) ) {
			foreach ( $story->webPages as $web ) {
				if ( in_array( 'canonical', $web->rels ) ) {
					$webPage = $web->href;
				}
			}
		}
		$story_date = new DateTime( $story->publishDateTime );
		$nprdata['date'] = $story_date->format( 'F j, Y, g:i A' );
		$nprdata['permalink'] = WP_HOME . '/npr/' . $story_date->format( 'Y/m/d/' ) . $npr_id . '/' . sanitize_title( $story->title ) . '/';
		$nprdata['canonical'] = $webPage;

		if ( !empty( $story->bylines ) ) {
			foreach ( $story->bylines as $byline ) {
				$byl_id = $npr->extract_asset_id( $byline->href );
				$byl_current = $story->assets->{$byl_id};
				$byl_profile = $npr->extract_asset_profile( $byl_current );
				if ( $byl_profile === 'reference-byline' ) {
					foreach ( $byl_current->bylineDocuments as $byl_doc ) {
						$byl_data = $npr->get_document( $byl_doc->href );
						if ( !empty( $byl_data ) ) {
							$byl_link = '';
							if ( !empty( $byl_data->webPages ) ) {
								foreach ( $byl_data->webPages as $byl_web ) {
									if ( !empty( $byl_web->rels ) && in_array( 'canonical', $byl_web->rels ) ) {
										$byl_link = $byl_web->href;
									}
								}
							}
							$nprdata['bylines'][] = [
								'name' => $byl_data->title,
								'link' => $byl_link
							];
						}
					}
				}
			}
		}
		if ( empty( $nprdata['bylines'] ) ) {
			$nprdata['bylines'][] = [
				'name' => 'NPR Staff',
				'link' => ''
			];
		}

		$nprdata['title'] = $story->title;
		if ( !empty( $story->teaser ) ) {
			$nprdata['excerpt'] = $story->teaser;
		}

		$slug = [];
		if ( !empty( $story->collections ) ) {
			foreach ( $story->collections as $collect ) {
				if ( in_array( 'topic', $collect->rels ) || in_array( 'program', $collect->rels ) ) {
					$coll_temp = $npr->get_document( $collect->href );
					if ( !empty( $coll_temp ) ) {
						$nprdata['keywords'][] = $coll_temp->title;
						if ( !empty( $coll_temp->webPages ) ) {
							foreach ( $coll_temp->webPages as $coll_web ) {
								if ( in_array( 'canonical', $coll_web->rels ) ) {
									$nprdata['keywords_html'][] = '<a href="' . $coll_web->href . '">' . $coll_temp->title . '</a>';
								}
							}
						}
						if ( in_array( 'slug', $collect->rels ) ) {
							$slug[] = $coll_temp->title;
						}
					}
				}
			}
		}
		if ( !empty( $story->brandings ) ) {
			foreach ( $story->brandings as $brand ) {
				$brand_get = wp_remote_get( $brand->href );
				if ( !is_wp_error( $brand_get ) && $brand_get['response']['code'] == 200 ) {
					$brand_json = json_decode( $brand_get['body'] );
					$slug[] = $brand_json->brand->displayName;
				}
			}
		}
		$nprdata['slug'] = implode( " | ", $slug );

		if ( !empty( $story->relatedItems ) ) {
			foreach ( $story->relatedItems as $related ) {
				$relate_get = wp_remote_get( $related->href );
				if ( !is_wp_error( $relate_get ) && $relate_get['response']['code'] == 200 ) {
					$relate_json = json_decode( $relate_get['body'] );
					$relate_link = '';
					if ( !empty( $relate_json->webPages ) ) {
						foreach ( $relate_json->webPages as $rel_web ) {
							if ( in_array( 'canonical', $rel_web->rels ) ) {
								$relate_link = $rel_web->href;
							}
						}
					}
					if ( !empty( $relate_link ) ) {
						$nprdata['related'][] = [
							'text' => $relate_json->title,
							'link' => $relate_link
						];
					}
				}
			}
		}

		if ( !empty( $story->images ) ) {
			foreach ( $story->images as $image ) {
				if ( !empty( $image->rels ) && in_array( 'primary', $image->rels ) ) {
					$image_id = $npr->extract_asset_id( $image->href );
					$image_asset = $story->assets->{$image_id};
					if ( !empty( $image_asset->enclosures ) ) {
						foreach ( $image_asset->enclosures as $enclose ) {
							if ( in_array( 'primary', $enclose->rels ) ) {
								$nprdata['image']['src'] = $enclose->href;
								$nprdata['image']['width'] = $enclose->width;
								$nprdata['image']['height'] = $enclose->height;
								$nprdata['image']['mime-type'] = $enclose->type;
							}
						}
					}
				}
			}
		}
	} elseif ( function_exists( 'nprstory_activate' ) ) {
		$npr = new NPRAPIWordpress();
		$npr->request([
			'id' => $npr_id,
			'fields' => 'all',
			'profileTypeId' => '1,15'
		]);
		$npr->parse();
		if ( !empty( $npr->stories[0] ) ) {
			$story = $npr->stories[0];
		}

		$use_npr_layout = !empty( get_option( 'dp_npr_query_use_layout' ) );

		$npr_layout = $npr->get_body_with_layout( $story, $use_npr_layout );
		if ( !empty( $npr_layout['body'] ) ) {
			$nprdata['body'] = $npr_layout['body'];
		}

		// add the transcript
		$nprdata['body'] .= $npr->get_transcript_body( $story );

		// Use oEmbed to flesh out external embeds
		preg_match_all( '/<div class\="wp\-block\-embed__[ \-a-z0-9]+">\s+(.+)\s+<\/div>/', $nprdata['body'], $match );
		if ( !empty( $match[1] ) ) {
			foreach ( $match[1] as $k => $v ) {
				$embed = wp_oembed_get( $v );
				if ( str_contains( $embed, '<iframe ' ) ) {
					$embed = '<p>' . $embed . '</p>';
				}
				$nprdata['body'] = str_replace( $v, $embed, $nprdata['body'] );
			}
		}

		$story_date = new DateTime( $story->storyDate->value );
		$nprdata['date'] = $story_date->format( 'F j, Y, g:i A' );
		$nprdata['permalink'] = WP_HOME . '/npr/' . $story_date->format( 'Y/m/d/' ) . $npr_id . '/' . sanitize_title( $story->title->value ) . '/';
		$nprdata['canonical'] = $story->link['html']->value;

		if ( !empty( $story->byline ) ) {
			if ( is_array( $story->byline ) ) {
				foreach( $story->byline as $single ) {
					$nprdata['bylines'][] = hpm_npr_byline( $single );
				}
			} else {
				$nprdata['bylines'][] = hpm_npr_byline( $story->byline );
			}
		} else {
			$nprdata['bylines'][] = [
				'name' => 'NPR Staff',
				'link' => ''
			];
		}

		$nprdata['title'] = $story->title->value;
		if ( !empty( $story->teaser->value ) ) {
			$nprdata['excerpt'] = $story->teaser->value;
		} elseif ( !empty( $story->miniTeaser->value ) ) {
			$nprdata['excerpt'] = $story->miniTeaser->value;
		}

		$slug = [];
		if ( !empty( $story->slug->value ) ) {
			$slug[] = $story->slug->value;
		}
		if ( !empty( $story->organization ) ) {
			if ( is_array( $story->organization ) ) {
				foreach ( $story->organization as $org ) {
					$slug[] = $org->name->value;
				}
			} else {
				$slug[] = $story->organization->name->value;
			}
		}
		$nprdata['slug'] = implode( " | ", $slug );

		if ( !empty( $story->relatedLink ) ) {
			if ( is_array( $story->relatedLink ) ) {
				foreach( $story->relatedLink as $link ) {
					$nprdata['related'][] = [
						'text' => $link->caption->value,
						'link' => hpm_link_extract( $link->link )
					];
				}
			} else {
				$nprdata['related'][] = [
					'text' => $story->relatedLink->caption->value,
					'link' => hpm_link_extract( $story->relatedLink->link )
				];
			}
		}

		if ( isset( $story->parent ) ) {
			foreach ( (array)$story->parent as $parent ) {
				if ( $parent->type == 'topic' || $parent->type == 'program' ) {
					$nprdata['keywords'][] = $parent->title->value;
					$nprdata['keywords_html'][] = '<a href="' . hpm_link_extract( $parent->link ) . '">' . $parent->title->value . '</a>';
				}
			}
		}

		if ( !empty( $story->image ) ) {
			foreach ( (array)$story->image as $image ) {
				if ( $image->type == 'primary' ) {
					if ( !empty( $image->crop ) ) {
						foreach ( $image->crop as $crop ) {
							if ( !empty( $crop->primary ) ) {
								$nprdata['image']['src'] = $crop->src;
								$nprdata['image']['width'] = $crop->width;
								$nprdata['image']['height'] = $crop->height;
								$parse_url = parse_url( $crop->src );
								$ext = wp_check_filetype( $parse_url['path'] );
								$nprdata['image']['mime-type'] = $ext['type'];
							}
						}
					}
				}
			}
		}
	}

	set_transient( 'hpm_nprdata_' . $npr_id, $nprdata, 3600 );
	return $nprdata;
}

function hpm_svg_output( $icon ): string {
	$output = '';
	if ( $icon == 'hpm' ) {
		return '<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston"><svg data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true" class="hpm-logo"><text class="hpm-logo-text" x="0" y="68">Houston Public Media</text><text class="hpm-logo-service" x="0" y="130">A SERVICE OF THE UNIVERSITY OF HOUSTON</text><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"/><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"/><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"/><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"/><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"/><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"/><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"/><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"/><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"/><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"/><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"/></svg></a>';
	} elseif ( $icon == 'instagram' ) {
		$output = '<path d="M256,141.1c-63.6,0-114.9,51.3-114.9,114.9S192.4,370.9,256,370.9S370.9,319.6,370.9,256S319.6,141.1,256,141.1z  M256,330.7c-41.1,0-74.7-33.5-74.7-74.7s33.5-74.7,74.7-74.7s74.7,33.5,74.7,74.7S297.1,330.7,256,330.7L256,330.7z M402.4,136.4 c0,14.9-12,26.8-26.8,26.8c-14.9,0-26.8-12-26.8-26.8s12-26.8,26.8-26.8S402.4,121.6,402.4,136.4z M478.5,163.6 c-1.7-35.9-9.9-67.7-36.2-93.9c-26.2-26.2-58-34.4-93.9-36.2c-37-2.1-147.9-2.1-184.9,0c-35.8,1.7-67.6,9.9-93.9,36.1 s-34.4,58-36.2,93.9c-2.1,37-2.1,147.9,0,184.9c1.7,35.9,9.9,67.7,36.2,93.9s58,34.4,93.9,36.2c37,2.1,147.9,2.1,184.9,0 c35.9-1.7,67.7-9.9,93.9-36.2c26.2-26.2,34.4-58,36.2-93.9C480.6,311.4,480.6,200.6,478.5,163.6L478.5,163.6z M430.7,388.1 c-7.8,19.6-22.9,34.7-42.6,42.6c-29.5,11.7-99.5,9-132.1,9s-102.7,2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6 c-11.7-29.5-9-99.5-9-132.1s-2.6-102.7,9-132.1c7.8-19.6,22.9-34.7,42.6-42.6c29.5-11.7,99.5-9,132.1-9s102.7-2.6,132.1,9 c19.6,7.8,34.7,22.9,42.6,42.6c11.7,29.5,9,99.5,9,132.1S442.4,358.7,430.7,388.1z" />';
	} elseif ( $icon == 'facebook' ) {
		$output = '<path d="M441.4,283.8l12.6-82h-78.7v-53.2c0-22.4,11-44.3,46.2-44.3h35.8V34.5c0,0-32.5-5.5-63.5-5.5 C329,29,286.7,68.3,286.7,139.3v62.5h-72v82h72V482h88.6V283.8H441.4z" />';
	} elseif ( $icon == 'twitter' ) {
		$output = '<path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>';
	} elseif ( $icon == 'linkedin' ) {
		$output = '<path d="M130.4,482H36.5V179.6h93.9V482z M83.4,138.3c-30,0-54.4-24.9-54.4-54.9C29,53.4,53.4,29,83.4,29 c30,0,54.4,24.3,54.4,54.4C137.8,113.4,113.4,138.3,83.4,138.3z M481.9,482h-93.7V334.8c0-35.1-0.7-80.1-48.8-80.1 c-48.8,0-56.3,38.1-56.3,77.6V482h-93.8V179.6h90.1v41.3h1.3c12.5-23.8,43.2-48.8,88.9-48.8c95,0,112.5,62.6,112.5,143.9V482H481.9 z" />';
	} elseif ( $icon == 'youtube' ) {
		$output = '<path d="M472.5,146.9c-5.2-19.6-20.6-35.1-40-40.3c-35.3-9.5-177-9.5-177-9.5s-141.7,0-177,9.5 c-19.5,5.2-34.8,20.7-40,40.3C29,182.4,29,256.6,29,256.6s0,74.2,9.5,109.7c5.2,19.6,20.6,34.4,40,39.7c35.3,9.5,177,9.5,177,9.5 s141.7,0,177-9.5c19.5-5.2,34.8-20,40.1-39.7c9.5-35.6,9.5-109.7,9.5-109.7S482,182.4,472.5,146.9z M209.2,324V189.3l118.4,67.4 L209.2,324L209.2,324z" />';
	} elseif ( $icon == 'chevron-left' ) {
		$output = '<path d="M125.9,238L326.6,37.3c9.7-9.7,25.4-9.7,35.1,0l23.4,23.4c9.7,9.7,9.7,25.3,0,35L226.1,255.5l159.1,159.8 c9.6,9.7,9.6,25.3,0,35l-23.4,23.4c-9.7,9.7-25.4,9.7-35.1,0L125.9,273C116.2,263.3,116.2,247.6,125.9,238z" />';
	} elseif ( $icon == 'chevron-right' ) {
		$output = '<path d="M385.7,273.1L184.1,474.7c-9.7,9.7-25.5,9.7-35.2,0l-23.5-23.5c-9.7-9.7-9.7-25.4,0-35.2l159.8-160.5L125.3,95 c-9.7-9.7-9.7-25.5,0-35.2l23.5-23.5c9.7-9.7,25.5-9.7,35.2,0l201.6,201.6C395.4,247.6,395.4,263.4,385.7,273.1z" />';
	} elseif ( $icon == 'chevron-down' ) {
		$output = '<path d="M237.9,385.7L36.3,184.1c-9.7-9.7-9.7-25.5,0-35.2l23.5-23.5c9.7-9.7,25.4-9.7,35.2,0l160.5,159.8L416,125.3 c9.7-9.7,25.5-9.7,35.2,0l23.5,23.5c9.7,9.7,9.7,25.5,0,35.2L273.1,385.7C263.4,395.4,247.6,395.4,237.9,385.7L237.9,385.7z" />';
	} elseif ( $icon == 'chevron-up' ) {
		$output = '<path d="M416 352c-8.188 0-16.38-3.125-22.62-9.375L224 173.3l-169.4 169.4c-12.5 12.5-32.75 12.5-45.25 0s-12.5-32.75 0-45.25l192-192c12.5-12.5 32.75-12.5 45.25 0l192 192c12.5 12.5 12.5 32.75 0 45.25C432.4 348.9 424.2 352 416 352z"/>';
	} elseif ( $icon == 'angle-double-down' ) {
		$output = '<path d="M236.3,255.8L82.4,101.9c-10.6-10.6-10.6-27.8,0-38.4L108,38c10.6-10.6,27.8-10.6,38.4,0l109.1,109.1L364.5,38 c10.6-10.6,27.8-10.6,38.4,0l25.8,25.5c10.6,10.6,10.6,27.8,0,38.4L274.7,255.7C264.1,266.4,246.9,266.4,236.3,255.8L236.3,255.8z  M274.7,473l153.9-153.9c10.6-10.6,10.6-27.8,0-38.4L403,255.2c-10.6-10.6-27.8-10.6-38.4,0l-109.2,109L146.4,255.1 c-10.6-10.6-27.8-10.6-38.4,0l-25.7,25.6c-10.6,10.6-10.6,27.8,0,38.4l153.9,153.9C246.9,483.7,264.1,483.7,274.7,473z" />';
	} elseif ( $icon == 'tv' ) {
		$output = '<path d="M448.1,74.7H63.9C45.2,74.7,30,89.9,30,108.6v226c0,18.7,15.2,33.9,33.9,33.9h169.5v22.6H109.1 c-6.2,0-11.3,5.1-11.3,11.3V425c0,6.2,5.1,11.3,11.3,11.3h293.8c6.2,0,11.3-5.1,11.3-11.3v-22.6c0-6.2-5.1-11.3-11.3-11.3H278.6 v-22.6h169.5c18.7,0,33.9-15.2,33.9-33.9v-226C482,89.9,466.8,74.7,448.1,74.7z M436.8,323.3H75.2V119.9h361.6V323.3z" />';
	} elseif ( $icon == 'microphone' ) {
		$output = '<path d="M256,340.5c46.7,0,84.5-37.9,84.5-84.5V115.1c0-46.7-37.9-84.5-84.5-84.5s-84.5,37.9-84.5,84.5V256 C171.5,302.7,209.3,340.5,256,340.5z M396.9,199.6h-14.1c-7.8,0-14.1,6.3-14.1,14.1V256c0,65.9-56.8,118.7-124,112.2 c-58.6-5.7-101.5-58.4-101.5-117.2v-37.3c0-7.8-6.3-14.1-14.1-14.1h-14.1c-7.8,0-14.1,6.3-14.1,14.1v35.4 c0,78.9,56.3,149.3,133.9,160v30.1h-49.3c-7.8,0-14.1,6.3-14.1,14.1v14.1c0,7.8,6.3,14.1,14.1,14.1h140.9c7.8,0,14.1-6.3,14.1-14.1 v-14.1c0-7.8-6.3-14.1-14.1-14.1h-49.3v-29.7C352.6,399.1,411,334.3,411,256v-42.3C411,205.9,404.7,199.6,396.9,199.6z" />';
	} elseif ( $icon == 'envelope' ) {
		$output = '<path d="M472.9,197.9c3.4-2.7,8.6-0.2,8.6,4.1v180.5c0,23.4-19,42.4-42.4,42.4H71.9c-23.4,0-42.4-19-42.4-42.4V202.2 c0-4.4,5-6.9,8.6-4.1c19.8,15.4,46,34.9,136,100.3c18.6,13.6,50.1,42.2,81.4,42c31.5,0.3,63.6-29,81.5-42 C427,232.9,453.2,213.3,472.9,197.9z M255.5,312c20.5,0.4,50-25.8,64.8-36.5c117.1-85,126.1-92.4,153.1-113.6 c5.1-4,8.1-10.2,8.1-16.7v-16.8c0-23.4-19-42.4-42.4-42.4H71.9c-23.4,0-42.4,19-42.4,42.4v16.8c0,6.5,3,12.6,8.1,16.7 c27,21.1,35.9,28.6,153.1,113.6C205.5,286.2,235,312.4,255.5,312L255.5,312z" />';
	} elseif ( $icon == 'home' ) {
		$output = '<path d="M250,171.4L105.3,290.6v128.6c0,6.9,5.6,12.6,12.6,12.6l87.9-0.2c6.9,0,12.5-5.6,12.5-12.6v-75.1 c0-6.9,5.6-12.6,12.6-12.6h50.2c6.9,0,12.6,5.6,12.6,12.6v75c0,6.9,5.6,12.6,12.5,12.6c0,0,0,0,0,0l87.9,0.2 c6.9,0,12.6-5.6,12.6-12.6V290.5L262,171.4C258.5,168.6,253.5,168.6,250,171.4L250,171.4z M478.5,252.4l-65.6-54.1V89.7 c0-5.2-4.2-9.4-9.4-9.4h-43.9c-5.2,0-9.4,4.2-9.4,9.4v57l-70.3-57.8c-13.9-11.4-34-11.4-47.9,0L33.4,252.4c-4,3.3-4.6,9.2-1.3,13.3 c0,0,0,0,0,0l20,24.3c3.3,4,9.2,4.6,13.3,1.3c0,0,0,0,0,0l184.6-152c3.5-2.8,8.5-2.8,12,0l184.6,152c4,3.3,9.9,2.8,13.3-1.3 c0,0,0,0,0,0l20-24.3C483.2,261.7,482.6,255.8,478.5,252.4C478.5,252.4,478.5,252.4,478.5,252.4L478.5,252.4z" />';
	} elseif ( $icon == 'times' ) {
		$output = '<path d="M341.4,256l128.8-128.8c15.8-15.8,15.8-41.4,0-57.2l-28.6-28.6c-15.8-15.8-41.4-15.8-57.2,0L255.5,170.1 L126.7,41.4c-15.8-15.8-41.4-15.8-57.2,0L40.9,70c-15.8,15.8-15.8,41.4,0,57.2L169.6,256L40.9,384.8C25,400.6,25,426.2,40.9,442 l28.6,28.6c15.8,15.8,41.4,15.8,57.2,0l128.8-128.8l128.8,128.8c15.8,15.8,41.4,15.8,57.2,0l28.6-28.6c15.8-15.8,15.8-41.4,0-57.2 L341.4,256z" />';
	} elseif ( $icon == 'bars' ) {
		$output = '<path d="M46.1,130.9h419.7c8.9,0,16.1-7.2,16.1-16.1V74.4c0-8.9-7.2-16.1-16.1-16.1H46.1c-8.9,0-16.1,7.2-16.1,16.1 v40.4C30,123.7,37.2,130.9,46.1,130.9z M46.1,292.3h419.7c8.9,0,16.1-7.2,16.1-16.1v-40.4c0-8.9-7.2-16.1-16.1-16.1H46.1 c-8.9,0-16.1,7.2-16.1,16.1v40.4C30,285.1,37.2,292.3,46.1,292.3z M46.1,453.8h419.7c8.9,0,16.1-7.2,16.1-16.1v-40.4 c0-8.9-7.2-16.1-16.1-16.1H46.1c-8.9,0-16.1,7.2-16.1,16.1v40.4C30,446.5,37.2,453.8,46.1,453.8z" />';
	} elseif ( $icon == 'calendar' ) {
		$output = '<path d="M68.6,199.1h373.8c5.8,0,10.6,4.8,10.6,10.6v229.2c0,23.4-19,42.3-42.3,42.3H100.3c-23.4,0-42.3-19-42.3-42.3 V209.7C58,203.8,62.8,199.1,68.6,199.1z M453,160.3v-31.7c0-23.4-19-42.3-42.3-42.3h-42.3V40.4c0-5.8-4.8-10.6-10.6-10.6h-35.3 c-5.8,0-10.6,4.8-10.6,10.6v45.8H199.1V40.4c0-5.8-4.8-10.6-10.6-10.6h-35.3c-5.8,0-10.6,4.8-10.6,10.6v45.8h-42.3 c-23.4,0-42.3,19-42.3,42.3v31.7c0,5.8,4.8,10.6,10.6,10.6h373.8C448.2,170.9,453,166.1,453,160.3z" />';
	} elseif ( $icon == 'code' ) {
		$output = '<path d="M227,435.7l-43-12.5c-4.5-1.3-7.1-6-5.8-10.5L274.5,81c1.3-4.5,6-7.1,10.5-5.8l43,12.5c4.5,1.3,7.1,6,5.8,10.5 l-96.3,331.7C236.2,434.5,231.5,437.1,227,435.7z M146.6,356.6l30.7-32.7c3.2-3.5,3-9-0.6-12.1l-63.9-56.2l63.9-56.2 c3.6-3.2,3.9-8.7,0.6-12.1l-30.7-32.7c-3.2-3.4-8.5-3.6-12-0.4L32.9,249.3c-3.6,3.3-3.6,9,0,12.3l101.7,95.3 C138.1,360.2,143.4,360,146.6,356.6L146.6,356.6z M377.4,357l101.7-95.3c3.6-3.3,3.6-9,0-12.3L377.4,154c-3.4-3.2-8.7-3-12,0.4 l-30.7,32.7c-3.2,3.5-3,9,0.6,12.1l63.9,56.3l-63.9,56.2c-3.6,3.2-3.9,8.7-0.6,12.1l30.7,32.7C368.6,360,373.9,360.2,377.4,357 L377.4,357z" />';
	} elseif ( $icon == 'search' ) {
		$output = '<path d="M475.8,420.8l-88-88c-4-4-9.4-6.2-15-6.2h-14.4c24.4-31.2,38.8-70.3,38.8-113c0-101.4-82.2-183.6-183.6-183.6 S30,112.2,30,213.6s82.2,183.6,183.6,183.6c42.6,0,81.8-14.5,113-38.8v14.4c0,5.6,2.2,11,6.2,15l88,88c8.3,8.3,21.7,8.3,29.9,0 l25-25C484,442.5,484,429.1,475.8,420.8z M213.6,326.6c-62.4,0-113-50.5-113-113c0-62.4,50.5-113,113-113c62.4,0,113,50.5,113,113 C326.6,276,276.1,326.6,213.6,326.6z" />';
	} elseif ( $icon == 'exclamation-circle' ) {
		$output = '<path d="M482,256c0,124.8-101.2,226-226,226S30,380.8,30,256C30,131.2,131.2,30,256,30S482,131.2,482,256z M256,301.6 c-23.2,0-41.9,18.8-41.9,41.9c0,23.2,18.8,41.9,41.9,41.9s41.9-18.8,41.9-41.9C297.9,320.3,279.2,301.6,256,301.6z M216.2,150.9 l6.8,123.9c0.3,5.8,5.1,10.3,10.9,10.3h44.2c5.8,0,10.6-4.5,10.9-10.3l6.8-123.9c0.3-6.3-4.6-11.5-10.9-11.5h-57.8 C220.8,139.4,215.9,144.6,216.2,150.9L216.2,150.9z" />';
	} elseif ( $icon == 'heart' ) {
		$output = '<path d="M438.1,85.3c-48.4-41.2-120.3-33.8-164.7,12L256,115.2l-17.4-17.9c-44.3-45.8-116.4-53.2-164.7-12 c-55.4,47.3-58.4,132.2-8.7,183.5L236,445.2c11,11.4,29,11.4,40,0l170.8-176.4C496.5,217.5,493.6,132.6,438.1,85.3L438.1,85.3z" />';
	} elseif ( $icon == 'play' ) {
		$output = '<use href="#hpm-play-button"></use><symbol id="hpm-play-button"><path d="M434.9,219L124.2,35.3C99,20.4,60.3,34.9,60.3,71.8v367.3c0,33.1,35.9,53.1,63.9,36.5l310.7-183.6 C462.6,275.6,462.7,235.3,434.9,219L434.9,219z" /></symbol>';
	} elseif ( $icon == 'phone' ) {
		$output = '<path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />';
	} elseif ( $icon == 'stop' ) {
		$output = '<path d="M0 128C0 92.7 28.7 64 64 64H320c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/>';
	} elseif ( $icon == 'pause' ) {
		$output = '<path d="M48 64C21.5 64 0 85.5 0 112V400c0 26.5 21.5 48 48 48H80c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H48zm192 0c-26.5 0-48 21.5-48 48V400c0 26.5 21.5 48 48 48h32c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48H240z"/></svg>';
	} elseif ( $icon == 'volume-up' ) {
		$output = '<path d="M533.6 32.5C598.5 85.3 640 165.8 640 256s-41.5 170.8-106.4 223.5c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C557.5 398.2 592 331.2 592 256s-34.5-142.2-88.7-186.3c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zM473.1 107c43.2 35.2 70.9 88.9 70.9 149s-27.7 113.8-70.9 149c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C475.3 341.3 496 301.1 496 256s-20.7-85.3-53.2-111.8c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zm-60.5 74.5C434.1 199.1 448 225.9 448 256s-13.9 56.9-35.4 74.5c-10.3 8.4-25.4 6.8-33.8-3.5s-6.8-25.4 3.5-33.8C393.1 284.4 400 271 400 256s-6.9-28.4-17.7-37.3c-10.3-8.4-11.8-23.5-3.5-33.8s23.5-11.8 33.8-3.5zM301.1 34.8C312.6 40 320 51.4 320 64V448c0 12.6-7.4 24-18.9 29.2s-25 3.1-34.4-5.3L131.8 352H64c-35.3 0-64-28.7-64-64V224c0-35.3 28.7-64 64-64h67.8L266.7 40.1c9.4-8.4 22.9-10.4 34.4-5.3z"/>';
	} elseif ( $icon == 'mute' ) {
		$output = '<path d="M320 64c0-12.6-7.4-24-18.9-29.2s-25-3.1-34.4 5.3L131.8 160H64c-35.3 0-64 28.7-64 64v64c0 35.3 28.7 64 64 64h67.8L266.7 471.9c9.4 8.4 22.9 10.4 34.4 5.3S320 460.6 320 448V64z"/>';
	} elseif ( $icon == 'mastodon' ) {
		$output = '<path d="M433 179.11c0-97.2-63.71-125.7-63.71-125.7-62.52-28.7-228.56-28.4-290.48 0 0 0-63.72 28.5-63.72 125.7 0 115.7-6.6 259.4 105.63 289.1 40.51 10.7 75.32 13 103.33 11.4 50.81-2.8 79.32-18.1 79.32-18.1l-1.7-36.9s-36.31 11.4-77.12 10.1c-40.41-1.4-83-4.4-89.63-54a102.54 102.54 0 0 1-.9-13.9c85.63 20.9 158.65 9.1 178.75 6.7 56.12-6.7 105-41.3 111.23-72.9 9.8-49.8 9-121.5 9-121.5zm-75.12 125.2h-46.63v-114.2c0-49.7-64-51.6-64 6.9v62.5h-46.33V197c0-58.5-64-56.6-64-6.9v114.2H90.19c0-122.1-5.2-147.9 18.41-175 25.9-28.9 79.82-30.8 103.83 6.1l11.6 19.5 11.6-19.5c24.11-37.1 78.12-34.8 103.83-6.1 23.71 27.3 18.4 53 18.4 175z"/>';
	}
	if ( !empty( $output ) ) {
		$output = '<svg role="img" ' . ( $icon == 'play' ? 'id="play-button"' : '' ) . ' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">' . $output . '</svg>';
	}
	return $output;
}

/**
 * Accepts Unix formatted time. If the time is more than 3 years behind current, output a banner
 */
function hpm_pub_time_banner( $time_string ): string {
	$output = '';
	$t = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$t = $t + $offset;
	if ( empty( $time_string ) || $time_string > $t ) {
		return $output;
	}
	$diff = $t - $time_string;
	if ( $diff > 94608000 ) {
		$output = '<div class="old-article-banner"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256C512 397.4 397.4 512 256 512zM232 256C232 264 236 271.5 242.7 275.1L338.7 339.1C349.7 347.3 364.6 344.3 371.1 333.3C379.3 322.3 376.3 307.4 365.3 300L280 243.2V120C280 106.7 269.3 96 255.1 96C242.7 96 231.1 106.7 231.1 120L232 256z"/></svg> This article is over ' . floor( $diff / 31536000 ) . ' years old</div>';
	}
	return $output;
}

function hpm_new_user_guest_author( $user_id ): void {
	$coauthor_guest = new CoAuthors_Guest_Authors();
	$coauthor_guest->create_guest_author_from_user_id( $user_id );
}
add_action( 'user_register', 'hpm_new_user_guest_author', 20, 1 );

function hpm_save_bylines_before_delete( $user_id ): void {
	global $coauthors_plus;

	$user_obj = get_userdata( $user_id );
	$search_author = $coauthors_plus->search_authors( $user_obj->data->user_login, [] );
	foreach ( $search_author as $a ) {
		if ( $a->linked_account == $user_obj->data->user_login ) {
			$author = $a;
		}
	}

	$author_posts = new WP_Query([
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'author' => $user_id
	]);
	if ( !$author_posts->have_posts() ) {
		$author_posts = new WP_Query([
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'author_name' => $user_obj->data->user_nicename
		]);
	}
	if ( !$author_posts->have_posts() ) {
		$author_posts = new WP_Query([
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'author_name' => $author->user_login
		]);
	}

	$temp = $output = [];
	while ( $author_posts->have_posts() ) {
		$author_posts->the_post();
		$id = get_the_ID();
		$coauthors = get_coauthors( $id );
		$temp[ $id ] = $coauthors;
	}

	foreach ( $temp as $k => $v ) {
		$coauth = [];
		foreach ( $v as $co ) {
			if ( $co->type == 'guest-author' ) {
				if ( $co->linked_account == $user_obj->data->user_login ) {
					$coauth[] = $author->user_login;
				} else {
					$coauth[] = $co->user_login;
				}
			} elseif ( $co->type == 'wpuser' ) {
				if ( $co->data->user_login == $user_obj->data->user_login ) {
					$coauth[] = $author->user_login;
				} else {
					$coauth[] = $co->user_login;
				}
			}
		}
		$output[ $k ] = $coauth;
	}
	update_option( 'hpm_user_backup_'.$user_id, $output, false );
	update_post_meta( $author->ID, 'cap-linked_account', '' );
	Red_Item::create([
		"url" => "/articles/author/" . $user_obj->data->user_login,
		"match_data" => [
			"source" => [
				"flag_query" => "exact",
				"flag_case" => true,
				"flag_trailing" => true,
				"flag_regex" => false
			],
			"options" => [
				"log_exclude" => false
			]
		],
		"action_code" => "301",
		"action_type" => "url",
		"action_data" => [
			"url" => "/articles/author/" . $author->user_login
		],
		"match_type" => "url",
		"group_id" => 1,
		"status" => "enabled",
		"regex" => false
	]);
}
add_action( 'delete_user', 'hpm_save_bylines_before_delete', 1, 1 );

function hpm_reassign_bylines_after_delete( $user_id ): void {
	global $coauthors_plus;
	$temp = get_option( 'hpm_user_backup_' . $user_id );
	foreach ( $temp as $k => $v ) {
		wp_set_post_terms( $k, $v, $coauthors_plus->coauthor_taxonomy );
	}
	delete_option( 'hpm_user_backup_' . $user_id );
}
add_action( 'deleted_user', 'hpm_reassign_bylines_after_delete', 999, 1 );

function hpm_uh_moment_blurb( $content ) {
	global $post;
	if ( is_single() && $post->post_type == 'post' ) {
		if ( in_category( 'uh-moment' ) ) {
			$content .= '<div id="revue-embed">This content is in service of our education mission and is sponsored by the University of Houston. It is not a product of our news team.</div>';
		}
	}
	return $content;
}
add_filter( 'the_content', 'hpm_uh_moment_blurb', 15 );

function hpm_thumbnail_alt_text_check( $html ) {
	if ( !str_contains( $html, 'alt=""' ) ) {
		return $html;
	}
	$id = get_post_thumbnail_id();
	$thumb = get_post( $id );
	$alt_text = get_post_meta( $id, '_wp_attachment_image_alt', true );
	if ( empty( $alt_text ) ) {
		if ( !empty( $thumb->post_excerpt ) ) {
			$alt_text = $thumb->post_excerpt;
		} elseif ( !empty( $thumb->post_content ) ) {
			$alt_text = $thumb->post_content;
		} elseif ( !empty( $thumb->post_title ) ) {
			$alt_text = $thumb->post_title;
		}
	}
	if ( !empty( $alt_text ) ) {
		$html = str_replace( 'alt=""', 'alt="' . $alt_text . '"', $html );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'hpm_thumbnail_alt_text_check', 15 );