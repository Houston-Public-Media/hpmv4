<?php

/**
 * The AMWGalleria WordPress Plugin class
 */
class AMWGalleria {

	protected $url;
	protected $theme;
	protected $version = '1.5';
	protected $galleriaVersion = '1.5.7';
	protected $optionsName = 'amw_galleria_theme';
	protected $defaultTheme = 'classic'; // TODO: add option to switch theme

	/**
	 * Constructor
	 *
	 * @param string $pluginUrl The full URL to this plugin's directory.
	 */
	public function __construct($pluginUrl) {
		$this->url   = $pluginUrl;
		//$this->theme = get_option($this->optionsName, $this->defaultTheme);
        $this->theme = 'classic';
		//$this->initialize();
		add_action( 'wp_enqueue_scripts', array( $this, 'initialize' ) );
	}

	/**
	 * Initializes this plugin
	 */
	public function initialize() {

		// replace the default [gallery] shortcode functionality
		add_shortcode('gallery', array(&$this, 'galleryShortcode'));

		// determine the theme and version for the files to load

		$theme_css   = sprintf("%s/galleria/themes/%s/galleria.%s.min.css", $this->url, $this->theme, $this->theme);
		$galleria_js = sprintf("%s/galleria/galleria-%s.min.js",        $this->url, $this->galleriaVersion);

		// add required scripts and styles to head
		wp_register_script('amw-galleria',       $galleria_js, array( 'jquery' ),       $this->galleriaVersion);
		wp_register_style( 'amw-galleria-style', $theme_css,   array(),        $this->version);

		// admin options page
		add_action('admin_menu', array(&$this, 'addOptionsPage'));

	}

	/**
	 * Displays a Galleria slideshow using images attached to the specified post/page.
	 * Overrides the default functionality of the [gallery] Shortcode.
	 *
	 * @param array $attr Attributes of the shortcode.
	 * @return string HTML content to display gallery.
	 */
	public function galleryShortcode($attr) {

		global $post, $content_width;

		// global content width set for this theme? (see theme functions.php)
		if (!isset($content_width)) $content_width = 'auto';

		// make sure each slideshow that is rendered for the current request has a unique ID
		static $instance = 0;
		$instance++;

		// yield to other plugins/themes attempting to override the default gallery shortcode
		$output = apply_filters('post_gallery', '', $attr);
		if ($output != '') {
			return $output;
		}

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if (isset($attr['orderby'])) {
			$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
			if (!$attr['orderby']) {
				unset($attr['orderby']);
			}
		}

		// 3:2 display ratio of the stage, account for 60px thumbnail strip at the bottom
		$width  = 'auto';
		$height = '0.76'; // a fraction of the width

		// defaults if not set
		$autoplay = false;

		// extract the shortcode attributes into the current variable space
		extract(shortcode_atts(array(
			// standard WP [gallery] shortcode options
			'order'        => 'ASC',
			'orderby'      => 'menu_order ID',
			'id'           => $post->ID,
			'itemtag'      => 'dl',
			'icontag'      => 'dt',
			'captiontag'   => 'dd',
			'columns'      => 3,
			'size'         => 'thumbnail',
			'include'      => '',
			'exclude'      => '',
			'ids'          => '',
			// galleria options
			'width'        => $width,
			'height'       => $height,
			'autoplay'     => $autoplay
		), $attr));

		// the id of the current post, or a different post if specified in the shortcode
		$id = intval($id);

		// random MySQL ordering doesn't need two attributes
		if ($order == 'RAND') {
			$orderby = 'none';
		}

		// use the given IDs of images
		if (!empty($ids)) {
			$include = $ids;
		}

		// fetch the images
		if (!empty($include)) {
			// include only the given image IDs
			$include = preg_replace('/[^0-9,]+/', '', $include);
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
			$attachments = array();
			foreach ($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			}
			if (!empty($ids)) {
				$sortedAttachments = array();
				$ids = preg_replace('/[^0-9,]+/', '', $ids);
				$idsArray = explode(',', $ids);
				foreach ($idsArray as $aid) {
					if (array_key_exists($aid, $attachments)) {
						$sortedAttachments[$aid] = $attachments[$aid];
					}
				}
				$attachments = $sortedAttachments;
			}
		} elseif (!empty($exclude)) {
			// exclude certain image IDs
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			// default: all images attached to this post/page
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		// output nothing if we didn't find any images
		if (empty($attachments)) {
			return '';
		}

		// output the individual images when displaying as a news feed
		if (is_feed()) {
			$output = "\n";
			foreach ($attachments as $attachmentId => $attachment) {
				list($src, $w, $h) = wp_get_attachment_image_src($attachmentId, 'medium');
				$output .= '<img src="'.$src.'" width="'.$w.'" height="'.$h.'">' . "\n";
			}
			return $output;
		}

		/***************/
		// amw-galleria
		/***************/

		// make an array of images with the proper data for Galleria
		$images_full = '';
		foreach ($attachments as $attachmentId => $attachment) {
			$thumb = wp_get_attachment_image_src($attachmentId, 'thumbnail');
			$big   = wp_get_attachment_image_src($attachmentId, 'large');
			$credit = get_post_meta( $attachmentId, '_wp_attachment_source_name', true );
			if ( !empty( $credit ) ) :
				$mcredit = " (Photo Credit: ".$credit.")";
			else :
				$mcredit = '';
			endif;
			if ( !empty( $attachment->post_excerpt ) ) :
				$description = $attachment->post_excerpt . $mcredit;
			elseif ( !empty( $attachment->post_title ) ) :
				$description = $attachment->post_title . $mcredit;
			else :
				$description = $mcredit;
			endif;
			$description = str_replace( '"', '&quot;', $description );
			$images_full .= '<a href="'.$big[0].'"><img src="'.$thumb[0].'" data-big="'.$big[0].'" data-title="'.$description.'" data-description=""></a>';
		}

		// encode the Galleria options as JSON
		$options = json_encode(array(
            'theme'             => $this->theme,
			'width'             => (is_numeric($width)) ? (int) $width  : (string) $width,
			'height'            => (is_int($height))    ? (int) $height : (float)  $height,
			'autoplay'          => (boolean) $autoplay,
			'transition'        => 'fade',
			'lightbox'          => (boolean) true,
			'showInfo'          => (boolean) true
		));

		// unique ID for this slideshow
		$domId = "amw_galleria_slideshow_" . $instance;

		// the DOM is built in JavaScript so we just need a placeholder div
		$output .= "<div id=\"" . $domId . "\" class=\"galleria\">".$images_full."</div>\n";

		$theme_js    = sprintf("%s/galleria/themes/%s/galleria.%s.min.js",  $this->url, $this->theme, $this->theme);
		// galleria JavaScript output
		// NOTE: WordPress disables the use of the dollar-sign function ($) for compatibility
		$output .= '<div class="screen-reader-text"><script type="text/javascript">jQuery(document).ready(function(){Galleria.loadTheme(\''.$theme_js.'\');Galleria.run(\'#'. $domId .'\',' . $options . '); });</script></div>';
		wp_enqueue_script( 'amw-galleria' );
		wp_enqueue_style( 'amw-galleria-style' );
		return $output;

	}

	/**
	 * Adds the callback for the admin options page.
	 * @return void
	 */
	public function addOptionsPage() {
		add_options_page('Galleria', 'Galleria', 'manage_options', 'galleria2', array(&$this, 'showOptionsPage'));
	}

	/**
	 * Displays the admin settings page.
	 * If a POST request, saves the submitted plugin options.
	 * @return void
	 */
	public function showOptionsPage() {

		if (!current_user_can('manage_options'))  {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		if (isset($_POST[$this->optionsName])) {
			update_option($this->optionsName, $_POST[$this->optionsName]);
			echo '<div id="message" class="updated fade"><p><strong>Options saved.</strong></p></div>';
		}

		$theme = get_option($this->optionsName, $this->defaultTheme);

		// get the current option value

		$availableThemes = array(
			/*'amw-classic-light' => 'Classic Light (with fullscreen button)',
			'amw-classic' => 'Classic Dark (with fullscreen button)',*/
			'classic' => 'Classic (with Lightbox)'
		);

		?>
		<div class="wrap">
			<h2>Galleria Settings</h2>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="amw-theme-select">Theme</label>
							</th>
							<td>
								<select id="amw-theme-select" name="<?php echo $this->optionsName; ?>">
									<?php foreach ($availableThemes as $k=>$v): ?>
									<option value="<?php echo $k; ?>"<?php if($theme==$k){ echo ' selected="selected"'; } ?>><?php echo $v; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
					</tbody>
				</table>
				<p class="submit"><input type="submit" value="Save Changes" class="button button-primary"></p>
			</form>
		</div>
		<?php

	}

}