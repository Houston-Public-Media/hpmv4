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
    wp_enqueue_script('bootstrap-js', get_template_directory_uri().'/bootstrap/js/bootstrap.min.js', array('jquery'), NULL, true);
	wp_register_style( 'hpm-splide-css', 'https://cdn.houstonpublicmedia.org/assets/css/splide.min.css', [], $versions['css'] );
    wp_enqueue_style('bootstrap-css', get_template_directory_uri().'/bootstrap/css/bootstrap.min.css', false, NULL, 'all');

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
		$styles = str_replace( [ "\n", "\t" ], [ '', '' ], file_get_contents( get_template_directory() . '/stylesheet.css' ) );
		$styles .= str_replace( [ "\n", "\t" ], [ '', '' ], file_get_contents( get_template_directory() . '/main.css' ) );
		$styles = preg_replace( '/\/\*([\n\t\sA-Za-z0-9:\/\-\.!@\(\){}#,;]+)\*\//', '', $styles );
		echo '<style>' . $styles . '</style>';
	} else {
		echo '<link rel="stylesheet" id="hpm-css" href="' . get_template_directory_uri() . '/stylesheet.css" type="text/css" media="all">';
		echo '<link rel="stylesheet" id="hpm-css-new" href="' . get_template_directory_uri() . '/main.css" type="text/css" media="all">';
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
function hpm_calculate_datetime_difference( $pID ) {
    if ( $pID ) {
        $postTimeDifference = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
        return $postTimeDifference;
    }
	return false;
}

// Modification to the normal Menu Walker to add <div> elements in certain locations and remove links with '#' hrefs
class HPM_Menu_Walker extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		foreach ( $classes as $k => $v ) {
			if ( !str_contains( $v, 'nav-' ) && !str_contains( $v, 'has-children' )  ) {
				//unset( $classes[$k] );
			}
		}

        if( in_array( 'current-menu-item', $classes ) ||
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
		$atts['target'] = $item->target     ?? '';
		$atts['rel']    = $item->xfn        ?? '';
		$atts['href']   = $item->url        ?? '';
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
	if ( is_page_template( 'page-npr-articles.php' ) ) {
		global $nprdata;
		$url = $nprdata['permalink'];
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
	//echo '<section id="top-posts" class="highlights"><h4>Most Viewed</h4>' . analyticsPull() . '</section>';
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
//add_filter( 'the_content', 'hpm_revue_signup', 15 );

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

function hpm_homepage_modules($catId): array{
    $articles = [];
    if(!empty($catId)) {
        $catposts_args = [
            'posts_per_page' => 4,
            'category' => $catId,
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish'
        ];
        $catposts_query = new WP_Query($catposts_args);
        if ($catposts_query->have_posts()) {
            foreach ($catposts_query->posts as $stp) {
                $articles[] = $stp;
            }
        }
    }
    return $articles;
    wp_reset_query();
}

function hpm_showLatestArticlesbyShowID($catID): array{
    $articles = [];
    if(!empty($catID))
    {
        $showposts_args = [
            'posts_per_page' => 3,
            'cat' => $catID,
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish'
        ];
        $catposts_query = new WP_Query($showposts_args);
        //print_r( $catposts_query);
        if ($catposts_query->have_posts()) {
            foreach ($catposts_query->posts as $stp) {
                $articles[] = $stp;
            }
        }
    }
    return $articles;
    wp_reset_query();
}

function altered_post_time_ago_function() {
    return ( get_the_time('U') >= strtotime('-1 week') ) ? sprintf( esc_html__( '%s ago', 'textdomain' ), human_time_diff( get_the_time ( 'U' ), current_time( 'timestamp' ) ) ) : get_the_date();
}
add_filter( 'the_time', 'altered_post_time_ago_function' );

function hpm_showTopthreeArticles(): string{
    $result ="";
    $articles = hpm_homepage_articles();
     $kk=0;
    if(count($articles)>0)
    {
        foreach ( $articles as $ka => $va ) {
            $post = $va;
            $post_title = get_the_title();
                if ( is_front_page() ) {
                    $alt_headline = get_post_meta( get_the_ID(), 'hpm_alt_headline', true );
                    if ( !empty( $alt_headline ) ) {
                        echo $alt_headline;
                    } else {
                        $post_title = get_the_title($post);
                    }
                    } else {
                        $post_title = get_the_title();
                    }
                $summary = strip_tags( get_the_excerpt($post) );
                if($ka == 0) {
                    /*if (strtotime(get_the_time('U', $post->ID)) <= time()+3600)
                    {
                        $timeago =  human_time_diff(get_the_time('U', $post->ID), current_time('timestamp'));
                        echo $interval->format( 'Published %a days ago.' );
                    }*/
                    if(in_array('tag-breaking-news-button', get_post_class('', $post->ID))){
                        $breakingNewsButton = '<div class="blue-label"><strong>Breaking News | </strong><span>'.hpm_top_cat( $post->ID ).'</span></div>';
                    }
                    else{
                        $breakingNewsButton = ''; //<span>11:12 AM </span>
                    }
                    $result .= '<div class="col-lg-8 col-md-12"><div class="row news-main"> <div class="col-5">'.$breakingNewsButton.'<div class="time-category"><strong class="text-light-gray text-uppercase">&nbsp;</strong></div><h1><a href="'.get_the_permalink($post).'" rel="bookmark">' . $post_title . '</a></h1><p>' . $summary . '</p></div><div class="col-7"><div class="box-img breaking-news-img">'.get_the_post_thumbnail($post, $post->ID).' </div> </div></div></div><div class="col-4"><ul class="news-listing">';
                }
                if($ka == 1  || $ka == 2)
                {
                    $result .= '<li><div class="d-flex flex-row-reverse"><div class="col-5"> <div class="box-img">'.get_the_post_thumbnail($post, get_the_ID()).'</div></div>
                                    <div class="col-7"><h4 class="text-light-gray">'.hpm_top_cat($post->ID).'</h4><h3><a href="'.get_the_permalink($post).'">' . get_the_title($post) . '</a></h3></div></div> </li>';
                }
                if($ka >3) {
                    $result .= '</ul>';
                }
            $kk++;
        }
    }
    return $result;
    wp_reset_query();
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
	global $wp_query;
	if ( $wp_query->have_posts() ) {
		foreach ( $wp_query->posts as $wpp ) {
			//$articles[] = $wpp;
		}
	}
	return $articles;
}

function hpm_priority_indepth() {
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
					<?php echo hpm_svg_output( 'facebook' ); ?>
				</button>
			</div>
			<div class="service-icon twitter">
				<button aria-label="Share to Twitter" data-href="https://twitter.com/share?text=<?PHP echo $uri_title; ?>&amp;url=<?PHP echo $twitter_link; ?>" data-dialog="364:250">
					<?php echo hpm_svg_output( 'twitter' ); ?>
				</button>
			</div>
			<div class="service-icon linkedin">
				<button aria-label="Share to LinkedIn" data-href="https://www.linkedin.com/shareArticle?mini=true&source=Houston+Public+Media&summary=<?PHP echo $uri_excerpt; ?>&title=<?PHP echo $uri_title; ?>&url=<?PHP echo $linkedin_link; ?>" target="_blank" data-dialog="600:471">
					<?php echo hpm_svg_output( 'linkedin' ); ?>
				</button>
			</div>
			<div class="service-icon envelope">
				<a href="mailto:?subject=Someone%20Shared%20an%20Article%20From%20Houston%20Public%20Media%21&body=I%20would%20like%20to%20share%20an%20article%20I%20found%20on%20Houston%20Public%20Media!%0A%0A<?php the_title(); ?>%0A%0A<?php the_permalink(); ?>">
					<?php echo hpm_svg_output( 'envelope' ); ?>
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

		$story_date = new DateTime( $story->publishDateTime );
		$nprdata['date'] = $story_date->format( 'F j, Y, g:i A' );
		$nprdata['permalink'] = WP_HOME . '/npr/' . $story_date->format( 'Y/m/d/' ) . $npr_id . '/' . sanitize_title( $story->title ) . '/';

		if ( !empty( $story->bylines ) ) {
			foreach ( $story->bylines as $byline ) {
				$byl_id = $npr->extract_asset_id( $byline->href );
				$nprdata['bylines'][] = [
					'name' => $story->assets->{$byl_id}->name,
					'link' => ''
				];
			}
		} else {
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
	/*if ( $icon == 'hpm' ) {
		return '<a href="/" rel="home" title="Houston Public Media, a service of the University of Houston"><svg data-name="Houston Public Media, a service of the University of Houston" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 872.96 231.64" aria-hidden="true" class="hpm-logo"><text class="hpm-logo-text" x="0" y="68">Houston Public Media</text><text class="hpm-logo-service" x="5" y="130">A SERVICE OF THE UNIVERSITY OF HOUSTON</text><polygon class="cls-2" points="505.03 224.43 505.03 175.7 455.22 175.7 455.22 224.43 505.03 224.43 505.03 224.43"/><polygon points="555.09 224.43 555.09 175.7 505.03 175.7 505.03 224.43 555.09 224.43 555.09 224.43"/><polygon class="cls-3" points="604.31 224.43 604.31 175.7 555.09 175.7 555.09 224.43 604.31 224.43 604.31 224.43"/><path class="cls-4" d="M485.35,213.27V198.5a7.38,7.38,0,0,0-1.26-4.77,5.09,5.09,0,0,0-4.11-1.5,7.2,7.2,0,0,0-5.15,2.58v18.46h-6V187.61h4.31l1.1,2.4c1.63-1.88,4-2.83,7.21-2.83a9.62,9.62,0,0,1,7.22,2.74c1.76,1.83,2.64,4.37,2.64,7.64v15.71Z"/><path class="cls-4" d="M529.59,213.78q5.86,0,9.25-3.4c2.27-2.27,3.39-5.5,3.39-9.7q0-13.5-12.26-13.5a7.72,7.72,0,0,0-5.54,2.16v-1.73h-6v32.48h6v-7.44a11.69,11.69,0,0,0,5.16,1.13Zm-1.34-21.48c2.76,0,4.73.62,5.93,1.85s1.78,3.36,1.78,6.39q0,4.26-1.8,6.22c-1.2,1.32-3.18,2-5.93,2a5.85,5.85,0,0,1-3.8-1.31V194a5.29,5.29,0,0,1,3.82-1.67Z"/><path class="cls-4" d="M586.73,193.24a6.32,6.32,0,0,0-3.49-1,4.73,4.73,0,0,0-3.68,1.88,6.82,6.82,0,0,0-1.61,4.61v14.55h-6V187.61h6v2.46a8.32,8.32,0,0,1,6.64-2.89,9.37,9.37,0,0,1,4.67.94l-2.53,5.12Z"/><path class="cls-5" d="M332.08,200.07a31.54,31.54,0,1,1-31.54-31.58,31.55,31.55,0,0,1,31.54,31.58"/><path class="cls-5" d="M411.22,196.55c-3.45-1.79-6.24-3.25-6.24-6,0-2,1.67-3.17,4.49-3.17a17,17,0,0,1,8.6,2.43v-7.13a23.23,23.23,0,0,0-8.6-1.89c-8.32,0-12.05,5-12.05,10.33,0,6.3,4.24,9.33,8.91,11.8s6.36,3.5,6.36,6.13c0,2.23-1.93,3.51-5.17,3.51a15.24,15.24,0,0,1-9.75-3.75v7.58a19.35,19.35,0,0,0,9.69,3c8.08,0,13.18-4.22,13.18-11,0-7-6-10-9.43-11.8"/><path class="cls-5" d="M387.49,198.61a8.85,8.85,0,0,0,3.75-7.79c0-6-4.4-9.7-11.46-9.7H368.22V219h12.07c9.25,0,13.46-5.95,13.46-11.47C393.75,203.17,391.37,199.79,387.49,198.61Zm-8.24-11.11a4.42,4.42,0,0,1,4.79,4.63c0,2.85-2,4.69-5.19,4.69h-3.17V187.5Zm-3.57,25.19v-9.9h4.71c3.76,0,6,1.84,6,4.92,0,3.3-2.25,5-6.69,5Z"/><path class="cls-5" d="M349.63,181.12h-10V219h7.45V207h1.5c9.32,0,15.11-5,15.11-13S358.45,181.12,349.63,181.12Zm-2.53,6.32h2.19c4.37,0,7.19,2.53,7.19,6.45,0,4.24-2.6,6.68-7.14,6.68H347.1Z"/><path class="cls-6" d="M323.51,200.37l-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219h-5.76v-7.53h1.79a4,4,0,0,0,4.1-3.91v-6.48l3.5-.72a1.16,1.16,0,0,0,.8-1.68l-9.18-17.57h5.76l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-12.6,0-3.5.72v6.48a4,4,0,0,1-4.1,3.91h-1.79V219H287.35v-9a13.89,13.89,0,0,1-10.09-13.11c-.21-8.65,7.13-15.73,15.77-15.73h9.5l9.18,17.57a1.16,1.16,0,0,1-.8,1.68m-7.54-6.29a3.61,3.61,0,1,0-3.61,3.61,3.61,3.61,0,0,0,3.61-3.61"/></svg></a>';
	}*/

    if ( $icon == 'hpm' ) {
        return '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="1113" height="175"><image width="1113" height="175" href="data:image/png;base64,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 MzM6MjYrMDA6MDADHtfPAAAAAElFTkSuQmCC"/></svg>';
    }

    elseif ( $icon == 'instagram' ) {
		$output = '<path d="M256,141.1c-63.6,0-114.9,51.3-114.9,114.9S192.4,370.9,256,370.9S370.9,319.6,370.9,256S319.6,141.1,256,141.1z  M256,330.7c-41.1,0-74.7-33.5-74.7-74.7s33.5-74.7,74.7-74.7s74.7,33.5,74.7,74.7S297.1,330.7,256,330.7L256,330.7z M402.4,136.4 c0,14.9-12,26.8-26.8,26.8c-14.9,0-26.8-12-26.8-26.8s12-26.8,26.8-26.8S402.4,121.6,402.4,136.4z M478.5,163.6 c-1.7-35.9-9.9-67.7-36.2-93.9c-26.2-26.2-58-34.4-93.9-36.2c-37-2.1-147.9-2.1-184.9,0c-35.8,1.7-67.6,9.9-93.9,36.1 s-34.4,58-36.2,93.9c-2.1,37-2.1,147.9,0,184.9c1.7,35.9,9.9,67.7,36.2,93.9s58,34.4,93.9,36.2c37,2.1,147.9,2.1,184.9,0 c35.9-1.7,67.7-9.9,93.9-36.2c26.2-26.2,34.4-58,36.2-93.9C480.6,311.4,480.6,200.6,478.5,163.6L478.5,163.6z M430.7,388.1 c-7.8,19.6-22.9,34.7-42.6,42.6c-29.5,11.7-99.5,9-132.1,9s-102.7,2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6 c-11.7-29.5-9-99.5-9-132.1s-2.6-102.7,9-132.1c7.8-19.6,22.9-34.7,42.6-42.6c29.5-11.7,99.5-9,132.1-9s102.7-2.6,132.1,9 c19.6,7.8,34.7,22.9,42.6,42.6c11.7,29.5,9,99.5,9,132.1S442.4,358.7,430.7,388.1z" />';
	} elseif ( $icon == 'facebook' ) {
		$output = '<path d="M441.4,283.8l12.6-82h-78.7v-53.2c0-22.4,11-44.3,46.2-44.3h35.8V34.5c0,0-32.5-5.5-63.5-5.5 C329,29,286.7,68.3,286.7,139.3v62.5h-72v82h72V482h88.6V283.8H441.4z" />';
	} elseif ( $icon == 'twitter' ) {
		$output = '<path d="M435.5,163.9c0.3,4,0.3,8,0.3,12c0,122.5-93.2,263.6-263.6,263.6C119.8,439.6,71,424.4,30,398 c7.5,0.9,14.6,1.1,22.4,1.1c43.3,0,83.2-14.6,115-39.6c-40.7-0.9-74.9-27.5-86.6-64.2c5.7,0.9,11.5,1.4,17.5,1.4 c8.3,0,16.6-1.1,24.4-3.2c-42.4-8.6-74.3-45.9-74.3-90.9v-1.1c12.3,6.9,26.7,11.2,41.9,11.8c-25-16.6-41.3-45-41.3-77.2 c0-17.2,4.6-33,12.6-46.7c45.6,56.2,114.1,92.9,191,96.9c-1.4-6.9-2.3-14.1-2.3-21.2c0-51.1,41.3-92.6,92.6-92.6 c26.7,0,50.8,11.2,67.7,29.3c20.9-4,41-11.8,58.8-22.4c-6.9,21.5-21.5,39.6-40.7,51.1c18.6-2,36.7-7.2,53.3-14.3 C469.4,134.4,453.6,150.7,435.5,163.9L435.5,163.9z" />';
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
		$output = '<path d="M434.9,219L124.2,35.3C99,20.4,60.3,34.9,60.3,71.8v367.3c0,33.1,35.9,53.1,63.9,36.5l310.7-183.6 C462.6,275.6,462.7,235.3,434.9,219L434.9,219z" />';
	} elseif ( $icon == 'phone' ) {
		$output = '<path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />';
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

add_filter( 'the_content', 'hpm_image_credits' , 1000000 );
function hpm_image_credits( $content ) {
	preg_match_all( '/<div class="credits-overlay" data-target="\.(wp-image-[0-9]{1,6})">(.+)<\/div>/', $content, $matches );
	if ( !empty( $matches[0] ) ) {
		foreach( $matches[1] as $k => $v ) {
			preg_match( '/(<p>)?(<a.+>)?(<img[ a-z\=\'\"0-9\-,\.\/\:A-Z\(\)]+'. $v .'[ A-Za-z\=\'\"0-9\-,\.\/\:\(\)_]+ \/>)(<\/a>)?(<\/p>)?/', $content, $match );
			if ( !empty( $match[3] ) ) {
				preg_match( '/class="([a-zA-Z\-0-9 ]+)"/', $match[3], $class );
				$credit = $matches[0][$k];
				if ( str_contains( '<a href="" title="">', $credit ) ) {
					$credit = str_replace( [ '<a href="" title="">', '</a>' ], [ '', '' ], $credit );
				}
				$replace = '<div class="credits-container ' . ( !empty( $class ) ? $class[1] : $v ) . '">' .
					( $match[2] ?? '' ) . $match[3] . ( $match[4] ?? '' ) . $credit . '</div>';
				$content = str_replace( $matches[0][$k], '', $content );
				$content = str_replace( $match[0], $replace, $content );
			}
		}
	}
	return $content;
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