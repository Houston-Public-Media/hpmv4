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
 * Enqueue Typekit, FontAwesome, Masonry, jPlayer scripts, stylesheets and some conditional scripts and stylesheets for older versions of IE
 */
$hpm_test = ( !empty( $_GET['version'] ) ? $_GET['version'] : '' );
if ( $hpm_test !== '-mod' ) :
	$hpm_test = '';
endif;
define('HPM_TEST', $hpm_test);
function hpmv2_scripts() {
	$versions = hpm_versions();
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'fontawesome', 'https://cdn.hpm.io/assets/fonts/fontawesome/css/all.css', [], '5.14.0' );

	// Load our main stylesheet.
	if ( WP_ENV == 'development' ) :
		wp_enqueue_style( 'hpmv2-style', get_template_directory_uri().'/style'.HPM_TEST.'.css', [], time() );
		// wp_enqueue_style( 'hpmv2-style', 'https://cdn.hpm.io/assets/css/style.css', [], $versions['css'] );
		wp_enqueue_script( 'hpmv2-js', get_template_directory_uri().'/js/main'.HPM_TEST.'.js', [ 'jquery' ], time(), false );
	else :
		wp_enqueue_style( 'hpmv2-style', 'https://cdn.hpm.io/assets/css/style.css', [], $versions['css'] );
		wp_enqueue_script( 'hpmv2-js', 'https://cdn.hpm.io/assets/js/main.js', [ 'jquery' ], $versions['js'], false );
	endif;
	wp_enqueue_script( 'hpm-analytics', 'https://cdn.hpm.io/assets/js/analytics/index.js', [], $versions['analytics'], false );

	wp_register_script( 'jplayer', 'https://cdn.hpm.io/assets/js/jplayer/jquery.jplayer.min.js', [ 'jquery' ],	'20170928' );
}
add_action( 'wp_enqueue_scripts', 'hpmv2_scripts' );

/*
 * Modifies homepage query
 */
function homepage_meta_query( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) :
		$query->set( 'post_status', 'publish' );
		$query->set( 'category__not_in', array(0,1,7636,28,37840) );
		//$query->set( 'category__in', array(26881,26989,27123) );
		$query->set( 'ignore_sticky_posts', 1 );
		$query->set( 'posts_per_page', 18 );
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
		if ( in_array( 'flex-break-before', $classes ) ) :
			$output .= $indent . '<li class="flex-break"></li>';
		endif;
		$output .= $indent . '<li' . $id . $class_names .'>';
		$atts = [];
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
		$attributes = '';
		if ( $item->url !== '#' ) :
			if ( empty( $atts['class'] ) ) :
				$atts['class'] = 'nav-item-'.$args->theme_location;
			else :
				$atts['class'] .= ' nav-item-'.$args->theme_location;
			endif;
		endif;

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
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
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

/*
 * Removes defunct SoundClound embeds from articles imported from Tendenci
 */
function tendenci_soundcloud_removal( $content ) {
	global $post;
	if ( !empty( $post->ID ) ) :
        $old_id = get_post_meta( $post->ID, 'old_id', true );
        if ( is_single() && !empty( $old_id ) ) :
            $content = preg_replace( '/(<p><iframe.+w\.soundcloud.com\/player.+><\/iframe><\/p>)/U', '', $content );
        endif;
    endif;
	return $content;
}
add_filter( 'the_content', 'tendenci_soundcloud_removal' );

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
		jQuery(function($){
			$('.wpf-char-limit input').attr('maxlength',100);
			$('.wpf-char-limit textarea').attr('maxlength',1000);
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
	if ( preg_match( '/<iframe.+youtube(-nocookie)?\.com.+><\/iframe>/', $content ) ) :
		$doc = new DOMDocument();
		$doc->loadHTML( $content );
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
	$c = time();
	$offset = get_option( 'gmt_offset' ) * 3600;
	$c = $c + $offset;
	if ( is_single() && $post->post_type == 'post' && $c > mktime( 5, 0, 0, 8, 3, 2020 ) ) :
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