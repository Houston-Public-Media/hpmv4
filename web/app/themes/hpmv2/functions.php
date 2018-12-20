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
	add_theme_support( 'post-thumbnails', array( 'post','page','shows','staff','podcasts' ) );
//	set_post_thumbnail_size( 450, 450, false );
//	update_option( 'thumbnail_size_w', 450 );
//	update_option( 'thumbnail_size_h', 450 );
//	update_option( 'thumbnail_crop', 0 );
//	update_option( 'medium_size_w', 1000 );
//	update_option( 'medium_size_h', 1000 );
//	update_option( 'medium_crop', 0 );
//	update_option( 'large_size_w', 1200 );
//	update_option( 'large_size_h', 800 );
//	update_option( 'large_crop', 0 );


	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'head-main' => __( 'Main Header Menu', 'hpmv2' ),
		'footer' => __( 'Footer Menu', 'hpmv2' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'gallery', 'caption'
	) );
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
function hpmv2_scripts() {
	$versions = hpm_versions();
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri().'/css/font-awesome.min.css', array(), '4.3.6' );

	// Load our main stylesheet.
	if ( WP_ENV == 'development' ) :
		wp_enqueue_style( 'hpmv2-style', 'https://local.hpm.io/hpm-style.css', array(), date('Ymd') );
	else :
		wp_enqueue_style( 'hpmv2-style', 'https://cdn.hpm.io/assets/css/style.css', array(), $versions['css'] );
	endif;

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_script( 'html5-shiv', '//html5shiv.googlecode.com/svn/trunk/html5.js', array() );
	wp_script_add_data( 'html5-shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond-js', get_stylesheet_directory_uri().'/js/respond.min.js', array() );
	wp_script_add_data( 'respond-js', 'conditional', 'lt IE 9' );

	wp_enqueue_style( 'ie9-css', get_stylesheet_directory_uri().'/css/ie9.css', array() );
	wp_style_add_data( 'ie9-css', 'conditional', 'lt IE 10' );

	if ( WP_ENV == 'development' ) :
		wp_enqueue_script( 'hpmv2-js', 'https://local.hpm.io/hpm-main.js', array('jquery'), date('Ymd'), true );
	else :
		wp_enqueue_script( 'hpmv2-js', 'https://cdn.hpm.io/assets/js/main.js', array('jquery'), $versions['js'], true );
	endif;

	wp_register_script( 'jplayer', get_stylesheet_directory_uri().'/js/jplayer/jquery.jplayer.min.js', array( 'jquery' ),	'20170928' );

	if ( is_page( 142253 ) ) :
		wp_enqueue_script( 'hpmv2-tablesorter', 'https://cdn.hpm.io/static/tablesorter/js/jquery.tablesorter.min.js', array(), '20160321', true );
		wp_enqueue_script( 'hpmv2-tablesorter-widgets', 'https://cdn.hpm.io/static/tablesorter/js/jquery.tablesorter.widgets.min.js', array(), '20160321', true );
		wp_enqueue_script( 'hpmv2-spellers', 'https://app.hpm.io/spellers/spellers.js', array(), '20160321', true );
		wp_enqueue_style( 'hpmv2-tablesorter-styles', 'https://cdn.hpm.io/static/tablesorter/css/theme.bootstrap.min.css', array(), '20160321', 'all' );
    elseif ( is_page( 145596 ) ) :
		wp_enqueue_script( 'hpmv2-tablesorter', 'https://cdn.hpm.io/static/tablesorter/js/jquery.tablesorter.min.js', array(), '20160321', true );
		wp_enqueue_script( 'hpmv2-tablesorter-widgets', 'https://cdn.hpm.io/static/tablesorter/js/jquery.tablesorter.widgets.min.js', array(), '20160321', true );
		wp_enqueue_style( 'hpmv2-tablesorter-styles', 'https://cdn.hpm.io/static/tablesorter/css/theme.bootstrap.min.css', array(), '20160321', 'all' );
	endif;
}
add_action( 'wp_enqueue_scripts', 'hpmv2_scripts' );

/*
 * Modifies homepage query
 */
function homepage_meta_query( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) :
		$query->set( 'post_status', 'publish' );
		$query->set( 'category__not_in', array(0,1,7636,28) );
		//$query->set( 'category__in', array(26881,26989,27123) );
		$query->set( 'ignore_sticky_posts', 1 );
		$query->set( 'posts_per_page', 18 );
	endif;
}
add_action( 'pre_get_posts', 'homepage_meta_query' );



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
	elseif ( is_page_template( 'page-diversecity-single.php' ) ) :
		global $dc;
		return the_title_attribute( array( 'before' => '', 'after' => ' | Houston Public Media', 'echo' => false, 'post' => $dc['id'] ) );
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

if( function_exists( 'rel_canonical' ) ) :
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
            $the_excerpt = strip_tags(strip_shortcodes($the_excerpt));
            $words = explode(' ', $the_excerpt, $excerpt_length + 1);

            if(count($words) > $excerpt_length) :
                array_pop($words);
                array_push($words, '...');
                $the_excerpt = implode(' ', $words);
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
add_filter('the_generator','remove_wp_version_rss');

/*
 * Display word count for post
 */
function word_count( $post_id ) {
    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    return $word_count;
}

/*
 * Set site icon URL on Google AMP
 */
add_filter( 'amp_post_template_data', 'hpm_amp_set_site_icon_url' );
function hpm_amp_set_site_icon_url( $data ) {
    // Ideally a 32x32 image
    $data[ 'site_icon_url' ] = 'https://cdn.hpm.io/assets/images/apple-touch-icon-180x180.png';
    return $data;
}

/*
 * Modify the JSON metadata present on Google AMP
 */
add_filter( 'amp_post_template_metadata', 'hpm_amp_modify_json_metadata', 10, 2 );
function hpm_amp_modify_json_metadata( $metadata, $post ) {
	$metadata['@type'] = 'NewsArticle';

	$metadata['publisher']['logo'] = array(
		'@type' => 'ImageObject',
		'url' => 'https://cdn.hpm.io/wp-content/uploads/2016/01/14100554/HPM_Vert.png'
	);
	if ( empty( $metadata['image'] ) ) :
		$metadata['image'] = array(
			'@type' => 'ImageObject',
			'url' => 'https://cdn.hpm.io/wp-content/uploads/2016/01/14100554/HPM_Vert.png',
			'height' => 1400,
			'width' => 1400
		);
	endif;
	return $metadata;
}

/*
 * Add Google Analytics to AMP
 */
add_filter( 'amp_post_template_analytics', 'hpm_amp_add_custom_analytics' );
function hpm_amp_add_custom_analytics( $analytics ) {
	if ( ! is_array( $analytics ) ) :
		$analytics = array();
	endif;
	$analytics['hpm-googleanalytics'] = array(
		'type' => 'googleanalytics',
		'attributes' => array(
			// 'data-credentials' => 'include',
		),
		'config_data' => array(
			'vars' => array(
				'account' => "UA-3106036-13"
			),
			'triggers' => array(
				'trackPageview' => array(
					'on' => 'visible',
					'request' => 'pageview',
				),
			),
		),
	);
	return $analytics;
}

/*
 * Add footer code to Google AMP
 */
add_action( 'amp_post_template_footer', 'hpm_amp_add_footer' );
function hpm_amp_add_footer( $amp_template ) {
	$post_id = $amp_template->get( 'post_id' );
?>
<footer id="colophon" class="site-footer amp-wp-footer" role="contentinfo">
	<div class="site-info">
		<div class="foot-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?>">
				<amp-img src="https://cdn.hpm.io/assets/images/HPM_OneLine.png" width="300" height="63" class="amp-wp-footer-logo" id="AMP_foot"></amp-img>
			</a>
		</div>
		<p>Houston Public Media is supported with your gifts to the Houston Public Media Foundation and is licensed to the University of Houston</p>
		<p>Copyright &copy; <?php echo date('Y'); ?> | <a href="/about/privacy-policy">Privacy Policy</a></p>
	</div><!-- .site-info -->
</footer><!-- .site-footer -->
<?php
}

add_action( 'amp_post_template_css', 'hpm_amp_additional_css' );

function hpm_amp_additional_css( $amp_template ) {
	?>
	@font-face {
		font-family: 'MiloOT-Light';
		src: url('https://cdn.hpm.io/assets/fonts/MiloOT-Light.otf') format('opentype'), url('https://cdn.hpm.io/assets/fonts/MiloWeb-Light.woff') format('woff'), url('https://cdn.hpm.io/assets/fonts/MiloWeb-Light.eot') format('eot');
		font-weight: normal;
		font-style: normal;
	}
	.amp-wp-title {
		font: normal 2em/1.125em 'MiloOT-Light',helvetica,arial;
	}
	.amp-audio-wrap {
		text-align: center;
	}
	<?php
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

function overwrite_audio_shortcode() {
	function hpm_audio_shortcode( $attr, $content = '' ) {
		global $wpdb;
		$post_id = get_post() ? get_the_ID() : 0;
		static $instance = 0;
		$instance++;

		$override = apply_filters( 'wp_audio_shortcode_override', '', $attr, $content, $instance );
		if ( '' !== $override ) :
			return $override;
		endif;

		$audio = null;

		$default_types = wp_get_audio_extensions();
		$defaults_atts = array(
			'src'      => '',
			'loop'     => '',
			'autoplay' => '',
			'preload'  => 'none',
			'class'    => 'wp-audio-shortcode',
			'style'    => 'width: 100%; visibility: hidden;'
		);
		foreach ( $default_types as $type ) :
			$defaults_atts[$type] = '';
		endforeach;

		$atts = shortcode_atts( $defaults_atts, $attr, 'audio' );

		$attach_id = $attr['id'];
		$primary = false;
		if ( ! empty( $atts['src'] ) ) :
			$type = wp_check_filetype( $atts['src'], wp_get_mime_types() );
			if ( ! in_array( strtolower( $type['ext'] ), $default_types ) ) :
				return sprintf( '<a class="wp-embedded-audio" href="%s">%s</a>', esc_url( $atts['src'] ), esc_html( $atts['src'] ) );
			endif;
			$primary = true;
			array_unshift( $default_types, 'src' );
		else :
			foreach ( $default_types as $ext ) :
				if ( ! empty( $atts[ $ext ] ) ) :
					$type = wp_check_filetype( $atts[ $ext ], wp_get_mime_types() );
					if ( strtolower( $type['ext'] ) === $ext ) :
						$primary = true;
					endif;
				endif;
			endforeach;
		endif;

		if ( ! $primary ) :
			$atts['src'] = wp_get_attachment_url( $attach_id );
			if ( empty( $atts['src'] ) ) :
				return;
			endif;
			$audio_id = $attach_id;
			$audio_title = 'Listen';
			$audio_url = $atts['src'];
			array_unshift( $default_types, 'src' );
		elseif ( $primary ) :
			foreach ( $default_types as $fallback ) :
				if ( ! empty( $atts[ $fallback ] ) ) :
					if ( empty( $fileurl ) ) :
						$fileurl = $atts[ $fallback ];
					endif;
				endif;
			endforeach;
			$audio_id = $instance;
			$audio_title = 'Listen';
			$audio_url = $fileurl;
		endif;
		$sg_file = get_post_meta( $post_id, 'hpm_podcast_enclosure', true );
		if ( !empty( $sg_file ) ) :
			$s3_parse = parse_url( $audio_url );
			$s3_path = pathinfo( $s3_parse['path'] );
			$sg_parse = parse_url( $sg_file['url'] );
			$sg_path = pathinfo( $sg_parse['path'] );
			if ( $s3_path['basename'] == $sg_path['basename'] ) :
				$audio_url = $sg_file['url'];
			else :
				$audio_url = str_replace( 'http:', 'https:', $audio_url );
			endif;
		else :
			$audio_url = str_replace( 'http:', 'https:', $audio_url );
		endif;
		$html = '';
		if ( is_amp_endpoint() ) :
			$html .= '<div class="amp-audio-wrap"><amp-audio width="360" height="33" src="'.$audio_url.'?source=amp-article"><div fallback><p>Your browser doesn’t support HTML5 audio</p></div><source type="audio/mpeg" src="'.$audio_url.'?source=amp-article"></amp-audio></div>';
		else :
			if ( is_admin() ) :
				$html .= '<link rel="stylesheet" id="fontawesome-css" href="https://cdn.hpm.io/assets/css/font-awesome.min.css" type="text/css" media="all"><link rel="stylesheet" id="hpmv2-css" href="https://cdn.hpm.io/assets/css/style.css" type="text/css" media="all"><script type="text/javascript" src="/wp/wp-includes/js/jquery/jquery.js"></script><script type="text/javascript" src="https://cdn.hpm.io/assets/js/jplayer/jquery.jplayer.min.js"></script>';
			else :
				wp_enqueue_script( 'jplayer' );
			endif;
			if ( in_array( 'small', $attr ) ) :
				$player_class = 'jp-audio jp-float';
			else :
				$player_class = 'jp-audio';
			endif;
			$html .= "
<div id=\"jquery_jplayer_{$audio_id}\" class=\"jp-jplayer\"></div>
<div id=\"jp_container_{$audio_id}\" class=\"{$player_class}\" role=\"application\" aria-label=\"media player\">
	<div class=\"jp-type-single\">
		<div class=\"jp-gui jp-interface\">
			<div class=\"jp-controls\">
				<button class=\"jp-play\" role=\"button\" tabindex=\"0\">
					<span class=\"fa fa-play\" aria-hidden=\"true\"></span>
				</button>
				<button class=\"jp-pause\" role=\"button\" tabindex=\"0\">
					<span class=\"fa fa-pause\" aria-hidden=\"true\"></span>
				</button>
			</div>
			<div class=\"jp-progress-wrapper\">
				<div class=\"jp-progress\">
					<div class=\"jp-seek-bar\">
						<div class=\"jp-play-bar\"></div>
					</div>
				</div>
				<div class=\"jp-details\">
					<div class=\"jp-title\" aria-label=\"title\">&nbsp;</div>
				</div>
				<div class=\"jp-time-holder\">
					<span class=\"jp-current-time\" role=\"timer\" aria-label=\"time\"></span> /<span class=\"jp-duration\" role=\"timer\" aria-label=\"duration\"></span>
				</div>
			</div>
		</div>
	</div>";
	if ( !is_admin() ) :
		$html .= "
	<a href=\"#\" class=\"jp-audio-embed\"><span class=\"fa fa-code\"></span></a>
	<div class=\"jp-audio-embed-popup\" id=\"jp_container_{$audio_id}-popup\">
		<div class=\"jp-audio-embed-wrap\">
			<p>To embed this piece of audio in your site, please use this code:</p>
			<div class=\"jp-audio-embed-code\">
				&lt;iframe src=\"https://embed.hpm.io/{$attach_id}/{$post_id}\" style=\"height: 115px; width: 100%;\"&gt;&lt;/iframe&gt;
			</div>
			<div class=\"jp-audio-embed-close\">X</div>
		</div>
	</div>";
	endif;
	$html .= "
</div>
<div class=\"screen-reader-text\">
	<script type=\"text/javascript\">
		jQuery(document).ready(function($){
			$(\"#jquery_jplayer_{$audio_id}\").jPlayer({
				ready: function () {
					$(this).jPlayer(\"setMedia\", {
						title: \"".htmlentities( wp_trim_words( $audio_title, 10, '...' ), ENT_COMPAT | ENT_HTML5, 'UTF-8', false )."\",
						mp3: \"{$audio_url}?source=jplayer-article\"
					});
				},
				swfPath: \"https://cdn.hpm.io/assets/js/jplayer\",
				supplied: \"mp3\",
				preload: \"metadata\",
				cssSelectorAncestor: \"#jp_container_{$audio_id}\",
				wmode: \"window\",
				useStateClassSkin: true,
				autoBlur: false,
				smoothPlayBar: true,
				keyEnabled: true,
				remainingDuration: false,
				toggleDuration: true
			});";
		if ( !is_admin() ) :
			$html .= "
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.play, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					ga('send', 'event', 'jPlayer', 'Play', mediaName, playerTime);
					ga('hpmRollup.send', 'event', 'jPlayer', 'Play', mediaName, playerTime);
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.pause, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					if (playerTime<100) {
						ga('send', 'event', 'jPlayer', 'Pause', mediaName, playerTime);
						ga('hpmRollup.send', 'event', 'jPlayer', 'Pause', mediaName, playerTime);
					}
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.seeking, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					ga('send', 'event', 'jPlayer', 'Seeking', mediaName, playerTime);
					ga('hpmRollup.send', 'event', 'jPlayer', 'Seeking', mediaName, playerTime);
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.seeked, function(event) {
					var playerTime = Math.round(event.jPlayer.status.currentPercentAbsolute);
					var mediaName = event.jPlayer.status.src;
					if (playerTime>0) {
						ga('send', 'event', 'jPlayer', 'Seeked', mediaName, playerTime);
						ga('hpmRollup.send', 'event', 'jPlayer', 'Seeked', mediaName, playerTime);
					} else {
						ga('send', 'event', 'jPlayer', 'Stopped', mediaName, playerTime);
						ga('hpmRollup.send', 'event', 'jPlayer', 'Stopped', mediaName, playerTime);
					}
				}
			);
			$(\"#jquery_jplayer_{$audio_id}\").bind(
				$.jPlayer.event.ended, function(event) {
					var playerTime = 100;
					var mediaName = event.jPlayer.status.src;
					ga('send', 'event', 'jPlayer', 'Ended', mediaName, playerTime);
					ga('hpmRollup.send', 'event', 'jPlayer', 'Ended', mediaName, playerTime);
				}
			);";
		endif;
		$html .= "
		});
	</script>
</div>";

			$library = 'jplayer';
		endif;
		return $html;
	}
	remove_shortcode('audio');
	add_shortcode( 'audio', 'hpm_audio_shortcode' );
}
add_action( 'wp_loaded', 'overwrite_audio_shortcode' );

function hpm_nprapi_audio_shortcode( $text ) {
	preg_match_all( '/' . get_shortcode_regex() . '/', $text, $matches );
	
	$tags = $matches[2];
	$args = $matches[3];
	foreach( $tags as $i => $tag ) :
		if ( $tag == "audio" ) :
			$atts = shortcode_parse_atts( $args[$i] );
			if ( !empty( $atts['mp3'] ) ) :
				$a_tag = '<figure><figcaption>Listen to the story audio:</figcaption><audio controls src="' . $atts['mp3'] . '">Your browser does not support the <code>audio</code> element.</audio></figure>';
				$text = str_replace( '<p>'.$matches[0][$i].'</p>', $a_tag, $text );
				$text = str_replace( $matches[0][$i], $a_tag, $text );
			endif;
		endif;
	endforeach;
	return $text;
}
add_filter( 'npr_ds_shortcode_filter', 'hpm_nprapi_audio_shortcode', 10, 1 );

function hpm_emergency() {
	// Emergency Notifications
	$t = getdate();
	$emergency_query = new WP_Query(
		array(
			'post_type' => 'emergency',
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key'     => 'hpm_break_end_time',
					'value'   => $t[0],
					'compare' => '>=',
				),
			)
		)
	);
	if ( $emergency_query->have_posts() ) :
		$html = "<div id=\"emergency\">";
		while ( $emergency_query->have_posts() ) :
			$emergency_query->the_post();
			$emergency_meta = get_post_meta( get_the_ID(), 'hpm_break_meta', 1 );
			$html .= "<a href=\"".$emergency_meta."\"><span class=\"fa fa-exclamation-circle\" aria-hidden=\"true\"></span> ".get_the_title()."</a>";
		endwhile;
		$html .= "</div>";
		wp_reset_query();
		global $hpm_emergency;
		$hpm_emergency = $html;
		return $html;
	endif;
}

function hpm_site_header() {
    global $hpm_emergency;
    if ( empty( $hpm_emergency ) ) :
        $ll_height = '400';
    else :
        $ll_height = '455';
	endif; ?>
			<a href="/ProdStage" rel="nofollow" style="display: none" aria-hidden="true">Production Staging</a>
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<div class="site-logo">
						<a href="/" rel="home" title="<?php bloginfo( 'name' ); ?>">&nbsp;</a>
					</div>
					<div id="top-schedule">
						<div class="top-schedule-label">Schedules</div>
						<div class="top-schedule-links"><a href="/tv8">TV 8 Guide</a></div>
						<div class="top-schedule-links"><a href="/news887">News 88.7</a></div>
						<div class="top-schedule-links"><a href="/classical">Classical</a></div>
						<div class="top-schedule-links"><a href="/mixtape">Mixtape</a></div>
					</div>
					<div id="top-listen"><a href="/listen-live" target="_blank" data-dialog="860:<?php echo $ll_height; ?>">Listen Live</a></div>
					<div id="top-donate"><a href="/donate" target="_blank">Donate</a></div>
					<div id="header-social">
						<div class="header-social-icon header-facebook">
							<a href="https://www.facebook.com/houstonpublicmedia" target="_blank"><span class="fa fa-facebook" aria-hidden="true"></span></a>
						</div>
						<div class="header-social-icon header-twitter">
							<a href="https://twitter.com/houstonpubmedia" target="_blank"><span class="fa fa-twitter" aria-hidden="true"></span></a>
						</div>
						<div class="header-social-icon header-instagram">
							<a href="https://instagram.com/houstonpubmedia" target="_blank"><span class="fa fa-instagram" aria-hidden="true"></span></a>
						</div>
						<div class="header-social-icon header-youtube">
							<a href="https://www.youtube.com/user/houstonpublicmedia" target="_blank"><span class="fa fa-youtube-play" aria-hidden="true"></span></a>
						</div>
					</div>
					<div id="top-mobile-menu"><span class="fa fa-bars" aria-hidden="true"></span></div>
					<nav id="site-navigation" class="main-navigation" role="navigation">
						<div id="top-search"><span class="fa fa-search" aria-hidden="true"></span><?php get_search_form(); ?></div>
					<?php
						// Primary navigation menu.
						wp_nav_menu( array(
							'menu_class' => 'nav-menu',
							'theme_location' => 'head-main',
							'walker' => new HPMv2_Menu_Walker
						) );
					?>
						<div class="clear"></div>
					</nav><!-- .main-navigation -->
				</div><!-- .site-branding -->
			</header><!-- .site-header --><?php
}

add_filter( 'hpm_filter_text', 'wptexturize'       );
add_filter( 'hpm_filter_text', 'convert_smilies'   );
add_filter( 'hpm_filter_text', 'convert_chars'     );
add_filter( 'hpm_filter_text', 'wpautop'           );
add_filter( 'hpm_filter_text', 'shortcode_unautop' );
add_filter( 'hpm_filter_text', 'do_shortcode'      );

function article_display_shortcode( $atts ) {
	global $hpm_constants;
	if ( empty( $hpm_constants ) ) :
		$hpm_constants = [];
	endif;
	$article = [];
	extract( shortcode_atts( [
		'num' => 1,
		'tag' => '',
		'category' => '',
		'type' => 'd',
		'overline' => '',
		'post_id' => ''
	], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	];
	if ( !empty( $hpm_constants ) ) :
		$args['post__not_in'] = $hpm_constants;
	endif;
	if ( !empty( $category ) ) :
		$args['category_name'] = $category;
	endif;
	if ( !empty( $tag ) ) :
		$args['tag_slug__in'][] = $tag;
	endif;
	if ( !empty( $post_id ) ) :
		$i_exp = explode( ',', $post_id );
		foreach ( $i_exp as $ik => $iv ) :
			$i_exp[$ik] = trim( $iv );
		endforeach;
		$args['post__in'] = $i_exp;
		$args['orderby'] = 'post__in';
		$c = count( $i_exp );
		if ( $c != $args['posts_per_page'] ) :
			$diff = $args['posts_per_page'] - $c;
			$args['posts_per_page'] = $c;
			unset( $args['category_name'] );
			$article[] = new WP_Query( $args );
			unset( $args['post__in'] );
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
			$args['post__not_in'] = array_merge( $hpm_constants, $i_exp );
			$args['posts_per_page'] = $diff;
			if ( !empty( $category ) ) :
				$args['category_name'] = $category;
			endif;
		endif;
	endif;
	$article[] = new WP_query( $args );
	$output = '<div class="grid-sizer"></div>';
	foreach ( $article as $art ) :
		if ( $art->have_posts() ) :
			while ( $art->have_posts() ) : $art->the_post();
				$postClass = get_post_class();
				$fl_array = preg_grep("/felix-type-/", $postClass);
				$fl_arr = array_keys( $fl_array );
				$postClass[$fl_arr[0]] = 'felix-type-'.$type;
				$postClass[] = 'grid-item';
				if ( $type == 'a' ) :
					$thumbnail_type = 'large';
					$postClass[] = 'grid-item--width2';
				elseif ( $type == 'b' ) :
					$thumbnail_type = 'thumbnail';
					$postClass[] = 'grid-item--width2';
				else :
					$thumbnail_type = 'thumbnail';
				endif;
				if ( empty( $overline ) ) :
					$overline = hpm_top_cat( get_the_ID() );
				endif;
				$output .= '<article id="post-'.get_the_ID().'" class="'.implode( ' ', $postClass ).'"><div class="thumbnail-wrap" style="background-image: url('.get_the_post_thumbnail_url(get_the_ID(), $thumbnail_type ).')"><a class="post-thumbnail" href="'.get_permalink().'" aria-hidden="true"></a></div><header class="entry-header"><h3>'.$overline.'</h3><h2 class="entry-title"><a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a></h2></header></article>';
			endwhile;
		endif;
	endforeach;
	return $output;
}
add_shortcode( 'hpm_articles', 'article_display_shortcode' );

function article_display_shortcode_temp( $atts ) {
	global $hpm_constants;
	if ( empty( $hpm_constants ) ) :
		$hpm_constants = [];
	endif;
	$article = [];
	extract( shortcode_atts( [
		'num' => 1,
		'tag' => '',
		'category' => '',
		'type' => 'd',
		'overline' => '',
		'post_id' => ''
	], $atts, 'multilink' ) );
	$args = [
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	];
	if ( !empty( $hpm_constants ) ) :
		$args['post__not_in'] = $hpm_constants;
	endif;
	if ( !empty( $category ) ) :
		$args['category_name'] = $category;
	endif;
	if ( !empty( $tag ) ) :
		$args['tag_slug__in'][] = $tag;
	endif;
	if ( !empty( $post_id ) ) :
		$i_exp = explode( ',', $post_id );
		foreach ( $i_exp as $ik => $iv ) :
			$i_exp[$ik] = trim( $iv );
		endforeach;
		$args['post__in'] = $i_exp;
		$args['orderby'] = 'post__in';
		$c = count( $i_exp );
		if ( $c != $args['posts_per_page'] ) :
			$diff = $args['posts_per_page'] - $c;
			$args['posts_per_page'] = $c;
			unset( $args['category_name'] );
			$article[] = new WP_Query( $args );
			unset( $args['post__in'] );
			$args['orderby'] = 'date';
			$args['order'] = 'DESC';
			$args['post__not_in'] = array_merge( $hpm_constants, $i_exp );
			$args['posts_per_page'] = $diff;
			if ( !empty( $category ) ) :
				$args['category_name'] = $category;
			endif;
		endif;
	endif;
	$article[] = new WP_query( $args );
	$output = '<div class="grid-sizer"></div>';
	foreach ( $article as $art ) :
		if ( $art->have_posts() ) :
			while ( $art->have_posts() ) : $art->the_post();
				ob_start();
				get_template_part( 'content', get_post_format() );
				$var = ob_get_contents();
				ob_end_clean();
				$output .= $var;
				$hpm_constants[] = get_the_ID();
			endwhile;
		endif;
	endforeach;
	return $output;
}
add_shortcode( 'hpm_articles_temp', 'article_display_shortcode_temp' );

function article_list_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'num' => 1,
		'category' => '',
		'post_id' => ''
	), $atts, 'multilink' ) );
	$args = array(
		'posts_per_page' => $num,
		'ignore_sticky_posts' => 1
	);
	$extra = '';
	if ( !empty( $category ) ) :
		$args['category_name'] = $category;
		$extra = '<li><a href="/topics/'.$category.'/">Read More...</a></li>';
	endif;
	if ( !empty( $post_id ) ) :
		$args['p'] = $post_id;
		$args['posts_per_page'] = 1;
	endif;
	$article = new WP_query( $args );
	$output = '<ul>';
	if ( $article->have_posts() ) :
		while ( $article->have_posts() ) : $article->the_post();
			$output .= '<li><a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a></li>';
		endwhile;
		$output .= $extra;
	else :
		$output .= "<li>Coming Soon</li>";
	endif;
	$output .= '</ul>';
	return $output;
}
add_shortcode( 'hpm_article_list', 'article_list_shortcode' );

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
			background-image: url(https://cdn.hpm.io/assets/images/HPM_Full_2Line.png);
			height:175px;
			width:320px;
			background-size: 320px 175px;
			background-repeat: no-repeat;
			padding-bottom: 0;
		}
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'hpm_login_logo' );

function hpm_google_tracker() { ?>
	<script>
		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
		ga('create', 'UA-3106036-9', 'auto');
		ga('create', 'UA-3106036-11', 'auto', 'hpmRollup' );
		var custom_vars = {
			nid: {name: "nid", slot: 8, scope_id: 3},
			pop: {name: "pop", slot: 9, scope_id: 3},
			author: {name: "author", slot: 11, scope_id: 3},
			keywords: {name: "tags", slot: 12, scope_id: 3},
			org_id: {name: "org_id", slot: 13, scope_id: 3},
			brand: {name: "CP_Station", slot: 14, scope_id: 2},
			has_audio: {name: "Has_Inline_Audio", slot: 15, scope_id: 3},
			programs: {name: "Program", slot: 16, scope_id: 3},
			category: {name: "Category", slot: 10, scope_id: 3},
			datePublished: {name: "PublishedDate", slot: 17, scope_id: 3},
			wordCount: {name: "WordCount", slot: 18, scope_id: 3},
			story_id: {name: "API_Story_Id", slot: 19, scope_id: 3},
			pmp_guid: {name: "pmp_guid", slot: 20, scope_id: 3}
		};
		metadata = document.getElementsByTagName("meta");
		// no metadata then no custom variables
		if (metadata.length > 0) {
			for (var k = 0; k < metadata.length; k++) {
				if (metadata[k].content !== "") {
					if (custom_vars[metadata[k].name]) {
						if (metadata[k].name === 'keywords' && metadata[k].content.length > 150) {
							var tagString = escape(metadata[k].content);
							var comma = tagString.lastIndexOf('%2C', 150);
							var tag = tagString.substring( comma-5, comma );
							var short = metadata[k].content.substring( 0, metadata[k].content.lastIndexOf( tag, 150 ) + 5 );
							ga('set', "dimension" + custom_vars[metadata[k].name]["slot"], short );
							ga('hpmRollup.set', "dimension" + custom_vars[metadata[k].name]["slot"], short );
						} else {
							ga('set', "dimension" + custom_vars[metadata[k].name]["slot"], metadata[k].content );
							ga('hpmRollup.set', "dimension" + custom_vars[metadata[k].name]["slot"], metadata[k].content );
						}

					}
				}
			}
		}
		ga('send', 'pageview');
		ga('hpmRollup.send', 'pageview');
		function hpmKimbiaComplete(kimbiaData) {
			var charge = kimbiaData['initialCharge'];
			var amount = Number(charge.replace(/[^0-9\.]+/g,""));
			fbq( 'track', 'Purchase', { value: amount, currency: 'USD' } );
			ga('send', 'event', { eventCategory: 'Button', eventAction: 'Submit', eventLabel: 'Donation', eventValue: amount });
		}
	</script>
	<script async src='https://www.google-analytics.com/analytics.js'></script>
<?php
}

function hpm_fb_sdk() { ?>
	<div id="fb-root"></div>
	<script>window.fbAsyncInit = function() { FB.init({ appId: '523938487799321', xfbml: true, version: 'v3.1' });}; (function(d, s, id){ var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/sdk.js"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
<?php
}

function hpm_pledge_counter() {
	$t = time();
	$offset = get_option('gmt_offset')*3600;
	$t = $t + $offset;
	$now = getdate($t);
	$start = mktime(0,0,0,10,5,2017 ) + $offset;
	$end = mktime( 0,0,0,10,19,2017 ) + $offset;
	if ( $now[0] > $start && $now[0] < $end ) :
		$json = get_transient( 'hpm_pledge_total' );
		if ( empty( $json ) ) :
			$json = file_get_contents('https://s3-us-west-2.amazonaws.com/hpmwebv2/assets/pledge.json');
			set_transient( 'hpm_pledge_total', $json, 120 );
		endif;

		$total = json_decode( $json, true );

		$days = round( ( ( $end - $now[0] ) / 86400 ), 0,PHP_ROUND_HALF_DOWN );
		if ( $days == 0 ) :
			$countdown = '<h4>Final day to give</h4>';
		else :
			$countdown = '<h4>'.$days.' days left to give</h4>';
		endif;

		$percent = round( ( ( $total['number']/100000 ) * 100 ) );
		if ( $percent < 20 ) :
			$low_percent = ' class="low"';
		else :
			$low_percent = '';
		endif; ?>
		<div id="campaign-splash">
			<div id="splash">
				<div class="campaign-push"><h3>Help us reach $100,000!</h3><?php echo $countdown; ?></div>
				<div class="campaign-total-bar-wrap">
					<div class="campaign-total-bar">
						<div id="campaign-percentage-bar" style="width: <?php echo $percent; ?>%;">
							<div id="campaign-percentage-display"<?php echo $low_percent; ?>><?php echo $percent; ?>%</div>
						</div>
						<div class="campaign-total-ticks"></div>
						<div class="campaign-total-ticks"></div>
						<div class="campaign-total-ticks"></div>
						<div class="campaign-total-ticks"></div>
					</div>
				</div>
			</div>
		</div>
		<style>
			#campaign-splash {
				border: 1px solid black;
				width: 100%;
				margin: 1em 0;
			}
			#campaign-splash #splash {
				overflow: hidden;
			}
			#campaign-splash .campaign-total-bar-wrap {
				padding: 1em;
			}
			@media screen and (min-width: 30.0625em) {
				#campaign-splash #splash {
					align-items: center;
					justify-content: center;
					align-content: center;
					-ms-align-items: center;
					-ms-justify-content: center;
					-ms-align-content: center;
					display: -webkit-box;
					display: -moz-box;
					display: -ms-flexbox;
					display: -webkit-flex;
					display: flex;
				}
				#campaign-splash .campaign-push {
					float: left;
					width: 40%;
				}
				#campaign-splash .campaign-total-bar-wrap {
					float: left;
					width: 60%;
				}
			}
		</style><?php
	endif;
}
add_shortcode( 'hpm_pledge', 'hpm_pledge_counter' );

add_action('init', 'remove_plugin_image_sizes');

function remove_plugin_image_sizes() {
	remove_image_size( 'guest-author-32' );
    remove_image_size( 'guest-author-50' );
    remove_image_size( 'guest-author-64' );
    remove_image_size( 'guest-author-96' );
    remove_image_size( 'guest-author-128' );
}

add_filter( 'gtm_post_category', 'gtm_populate_category_items', 10, 3 );

function gtm_populate_category_items( $total_match, $match, $post_id ) {
	$terms = wp_get_object_terms( $post_id, 'category', [ 'fields' => 'slugs' ] );
	if ( is_wp_error( $terms ) || empty( $terms ) ) :
		return '';
	endif;
	return $terms;
}

add_filter( 'media_send_to_editor', 'hpm_audio_shortcode_insert', 10, 8 );
function hpm_audio_shortcode_insert ( $html, $id, $attachment ) {
	if ( strpos( $html, '[audio' ) !== FALSE ) :
		$html = str_replace( '][/audio]', ' id="'.$id.'"][/audio]', $html );
	endif;
	return $html;
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