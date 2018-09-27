<?php
/**
 * @link 			https://github.com/jwcounts
 * @since  			20180919
 * @package  		HPM-Promos
 *
 * @wordpress-plugin
 * Plugin Name: 	HPM Promo Banners
 * Plugin URI: 		https://github.com/jwcounts
 * Description: 	Promotional banners for use in the HPMv2 Theme
 * Version: 		20180919
 * Author: 			Jared Counts
 * Author URI: 		https://www.houstonpublicmedia.org/staff/jared-counts/
 * License: 		GPL-2.0+
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	hpmv2
 *
 * Works best with Wordpress 4.6.0+
 */

class HPM_Promos {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'create_type' ) );
	}

	/**
	 * Init
	 */
	public function init() {
		add_action( 'admin_init', array( $this, 'add_role_caps' ), 999 );
		add_filter( 'user_can_richedit', array( $this, 'disable_wysiwyg' ) );
		add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
		add_action( 'post_submitbox_misc_actions', array( $this, 'unpub_date' ) );
		add_action( 'hpm_promo_cleanup', array( $this, 'cleanup' ) );
		add_filter( 'manage_edit-promos_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_promos_posts_custom_column', array( $this, 'manage_columns' ), 10, 2 );
		add_action( 'wp_footer', function() {
			echo $this->generate();
		}, 100 );

		// Make sure that the proper cron job is scheduled
		if ( ! wp_next_scheduled( 'hpm_promo_cleanup' ) ) :
			wp_schedule_event( time(), 'daily', 'hpm_promo_cleanup' );
		endif;
	}

	public function create_type() {
		register_post_type( 'promos',
			array(
				'labels'               => array(
					'name'               => __( 'Promo Banners' ),
					'singular_name'      => __( 'Promo Banner' ),
					'menu_name'          => __( 'Promo Banners' ),
					'add_new_item'       => __( 'Add New Promo Banner' ),
					'edit_item'          => __( 'Edit Promo Banner' ),
					'new_item'           => __( 'New Promo Banner' ),
					'view_item'          => __( 'View Promo Banner' ),
					'search_items'       => __( 'Search Promo Banners' ),
					'not_found'          => __( 'Promo Banner Not Found' ),
					'not_found_in_trash' => __( 'Promo Banner not found in trash' )
				),
				'description'          => 'Internal promotional banners for the homepage and internal page sidebars',
				'public'               => false,
				'show_ui'              => true,
				'show_in_admin_bar'    => true,
				'menu_position'        => 20,
				'menu_icon'            => 'dashicons-warning',
				'has_archive'          => false,
				'rewrite'              => false,
				'supports'             => array( 'title', 'editor' ),
				'can_export'           => false,
				'capability_type'      => array( 'hpm_promo', 'hpm_promos' ),
				'map_meta_cap'         => true,
				'register_meta_box_cb' => array( $this, 'add_meta' )
			)
		);
	}

	public function add_role_caps() {
		// Add the roles you'd like to administer the custom post types
		$roles = array( 'administrator' );

		// Loop through each role and assign capabilities
		foreach ( $roles as $the_role ) :
			$role = get_role( $the_role );
			$role->add_cap( 'read' );
			$role->add_cap( 'read_hpm_promo' );
			$role->add_cap( 'read_private_hpm_promos' );
			$role->add_cap( 'edit_hpm_promo' );
			$role->add_cap( 'edit_hpm_promos' );
			$role->add_cap( 'edit_others_hpm_promos' );
			$role->add_cap( 'edit_published_hpm_promos' );
			$role->add_cap( 'publish_hpm_promos' );
			$role->add_cap( 'delete_others_hpm_promos' );
			$role->add_cap( 'delete_private_hpm_promos' );
			$role->add_cap( 'delete_published_hpm_promos' );
		endforeach;
	}


	public function add_meta() {
		add_meta_box(
			'hpm-promos-meta-class',
			esc_html__( 'Banner Metadata', 'example' ),
			array( $this, 'meta_box' ),
			'promos',
			'normal',
			'core'
		);
	}

	public function meta_box( $object, $box ) {
		wp_nonce_field( basename( __FILE__ ), 'hpm_promos_class_nonce' );
		$hpm_promo = get_post_meta( $object->ID, 'hpm_promos_meta', true );
		if ( empty( $hpm_promo ) ) :
			$hpm_promo = array(
				'location' => 'homepage',
				'type' => 'sidebar',
				'options' => array(
					'sidebar' => array(
						'mobile' => '',
						'tablet' => '',
						'desktop' => ''
					),
					'fullwidth' => array(
						'mobile' => '',
						'tablet' => '',
						'desktop' => ''
					),
					'lightbox' => array(
						'a' => array(
							'link' => '',
							'image' => '',
							'text' => ''
						),
						'b' => array(
							'link' => '',
							'image' => '',
							'text' => ''
						),
						'total' => ''
					)
				)
			);
		endif;
		$editor_opts = [
			'editor_height' => 150,
			'media_buttons' => false,
			'teeny' => true
		]; ?>
		<h3><?PHP _e( "Where do you want your element to show up?", 'hpmv2' ); ?></h3>
		<p><label for="hpm_promo[location]"><?php _e( "Location:", 'hpmv2' ); ?></label>
			<select id="hpm_promo[location]" name="hpm_promo[location]">
				<option value="homepage" <?PHP selected( $hpm_promo['location'], 'homepage', TRUE ); ?>>Homepage Only</option>
				<option value="any" <?PHP selected( $hpm_promo['location'], 'any', TRUE ); ?>>Any Page</option>
			</select>
		</p>
		<h3><?PHP _e( "What type of banner are you creating?", 'hpmv2' ); ?></h3>
		<p><label for="hpm_promo[type]"><?php _e( "Type:", 'hpmv2' ); ?></label>
			<select id="hpm_promo_type" name="hpm_promo[type]">
				<option value="sidebar" <?PHP selected( $hpm_promo['type'], 'sidebar', TRUE ); ?>>Sidebar Banner/Poll</option>
				<option value="fullwidth" <?PHP selected( $hpm_promo['type'], 'fullwidth', TRUE ); ?>>Full-Width Banner</option>
				<option value="lightbox" <?PHP selected( $hpm_promo['type'], 'lightbox', TRUE ); ?>>Lightbox</option>
			</select>
		</p>
		<div id="hpm-sidebar" class="hpm-promo-types"<?php echo ( $hpm_promo['type'] == 'sidebar' ? '' : ' style="display: none;"' ); ?>>
			<h3><?php _e( "Sidebar Banner Options", 'hpmv2' ); ?></h3>
			<p><?php _e( "The Sidebar banner allows for alternate image versions for mobile, tablet, and desktop, if
				desired. If you only wish to use a single image size, you can just include it in the HTML. If you
				want to use multiple versions, paste the image URLs in the boxes below, and place [[image]] in the
				image source in your HTML.", 'hpmv2' ); ?></p>
			<ul>
				<li><label for="hpm_promo[options][sidebar][mobile]"><?php _e('Mobile: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][sidebar][mobile]" value="<?php echo $hpm_promo['options']['sidebar']['mobile']; ?>" style="max-width: 100%; width: 800px;" /></li>
				<li><label for="hpm_promo[options][sidebar][tablet]"><?php _e('Tablet: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][sidebar][tablet]" value="<?php echo $hpm_promo['options']['sidebar']['tablet']; ?>" style="max-width: 100%; width: 800px;" /></li>
				<li><label for="hpm_promo[options][sidebar][desktop]"><?php _e('Desktop: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][sidebar][desktop]" value="<?php echo $hpm_promo['options']['sidebar']['desktop']; ?>" style="max-width: 100%; width: 800px;" /></li>
			</ul>
		</div>
		<div id="hpm-fullwidth" class="hpm-promo-types"<?php echo ( $hpm_promo['type'] == 'fullwidth' ? '' : ' style="display: none;"' ); ?>>
			<h3><?php _e( "Full-Width Banner Options", 'hpmv2' ); ?></h3>
			<p><?php _e( "The Full-Width banner allows for alternate image versions for mobile, tablet, and desktop, if
				desired. If you only wish to use a single image size, you can just include it in the HTML. If you
				want to use multiple versions, paste the image URLs in the boxes below, and place [[image]] in the
				image source in your HTML.", 'hpmv2' ); ?></p>
			<ul>
				<li><label for="hpm_promo[options][fullwidth][mobile]"><?php _e('Mobile: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][fullwidth][mobile]" value="<?php echo $hpm_promo['options']['fullwidth']['mobile']; ?>" style="max-width: 100%; width: 800px;" /></li>
				<li><label for="hpm_promo[options][fullwidth][tablet]"><?php _e('Tablet: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][fullwidth][tablet]" value="<?php echo $hpm_promo['options']['fullwidth']['tablet']; ?>" style="max-width: 100%; width: 800px;" /></li>
				<li><label for="hpm_promo[options][fullwidth][desktop]"><?php _e('Desktop: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][fullwidth][desktop]" value="<?php echo $hpm_promo['options']['fullwidth']['desktop']; ?>" style="max-width: 100%; width: 800px;" /></li>
			</ul>
		</div>
		<div id="hpm-lightbox" class="hpm-promo-types"<?php echo ( $hpm_promo['type'] == 'lightbox' ? '' : ' style="display: none;"'); ?>>
			<h3><?php _e( "Lightbox Options", 'hpmv2' ); ?></h3>
			<p><?php _e( "The Lightbox allows for A/B testing of images, text, and links, and has an option for showing a 
				pledge total.", 'hpmv2' ); ?></p>
			<p><?php _e( "To use the total, or the A/B testing option, simply put these placeholders into your HTML: [[link]], [[image]], [[text]], [[total]]", 'hpmv2' ); ?></p>
			<p><strong><?php _e( "Version A", 'hpmv2' ); ?></strong></p>
			<ul style="margin-bottom: 2em;">
				<li><label for="hpm_promo[options][lightbox][a][link]"><?php _e('Link: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][lightbox][a][link]" value="<?php echo $hpm_promo['options']['lightbox']['a']['link']; ?>" style="max-width: 100%; width: 800px;" /></li>
				<li><label for="hpm_promo[options][lightbox][a][text]"><?php _e('Text: ', 'hpmv2' ); ?></label>
					<?php wp_editor( $hpm_promo['options']['lightbox']['a']['text'], 'hpm_promo[options][lightbox][a][text]', $editor_opts ); ?>
				</li>
				<li><label for="hpm_promo[options][lightbox][a][image]"><?php _e('Image: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][lightbox][a][image]" value="<?php echo $hpm_promo['options']['lightbox']['a']['image']; ?>" style="max-width: 100%; width: 800px;" /></li>
			</ul>
			<p><strong><?php _e( "Version B", 'hpmv2' ); ?></strong></p>
			<ul style="margin-bottom: 2em;">
				<li><label for="hpm_promo[options][lightbox][b][link]"><?php _e('Link: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][lightbox][b][link]" value="<?php echo $hpm_promo['options']['lightbox']['b']['link']; ?>" style="max-width: 100%; width: 800px;" /></li>
				<li><label for="hpm_promo[options][lightbox][b][text]"><?php _e('Text: ', 'hpmv2' ); ?></label>
				<?php wp_editor( $hpm_promo['options']['lightbox']['b']['text'], 'hpm_promo[options][lightbox][b][text]', $editor_opts ); ?>
				</li>
				<li><label for="hpm_promo[options][lightbox][b][image]"><?php _e('Image: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][lightbox][b][image]" value="<?php echo $hpm_promo['options']['lightbox']['b']['image']; ?>" style="max-width: 100%; width: 800px;" /></li>
			</ul>
			<p><strong><?php _e( "Pledge Total", 'hpmv2' ); ?></strong></p>
			<ul style="margin-bottom: 2em;">
				<li><label for="hpm_promo[options][lightbox][total]"><?php _e('Link to JSON File: ', 'hpmv2' ); ?></label><input type="text" name="hpm_promo[options][lightbox][total]" value="<?php echo $hpm_promo['options']['lightbox']['total']; ?>" style="max-width: 100%; width: 800px;" /></li>
			</ul>
		</div>
		<script>
			jQuery(document).ready(function($){
				$( "#hpm_promo_type" ).change(function () {
					var typeVal = $(this).val();
					$('.hpm-promo-types').hide();
					$('#hpm-'+typeVal).show();
				});
			});
		</script>
		<?php
	}

	public function save_meta( $post_id, $post ) {
		if ( $post->post_type == 'promos' ) :
			/* Verify the nonce before proceeding. */
			if ( ! isset( $_POST['hpm_promos_class_nonce'] ) || ! wp_verify_nonce( $_POST['hpm_promos_class_nonce'], basename( __FILE__ ) ) ) :
				return $post_id;
			endif;

			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );

			/* Check if the current user has permission to edit the post. */
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) :
				return $post_id;
			endif;

			$hpend = $_POST['hpm_promo']['end'];

			foreach ( $hpend as $hpe ) :
				if ( !is_numeric( $hpe ) || $hpe == '' ) :
					return $post_id;
				endif;
			endforeach;

			$offset = get_option('gmt_offset')*3600;
			$endtime = mktime( $hpend['hour'], $hpend['min'], 0, $hpend['mon'], $hpend['day'], $hpend['year'] ) - $offset;
			update_post_meta( $post_id, 'hpm_promos_end_time', $endtime );

			$options = $_POST['hpm_promo']['options'];
			foreach ( $options as $k => $v ) :
				if ( is_array( $v ) ) :
					foreach ( $v as $vk => $vv ) :
						if ( is_array( $vv ) ) :
							foreach ( $vv as $vvk => $vvv ) :
								if ( $vvk !== 'text' ) :
									$options[$k][$vk][$vvk] = sanitize_text_field( $vvv );
								else :
									$options[$k][$vk][$vvk] = wp_kses_post( $vvv );
								endif;
							endforeach;
						else :
							$options[$k][$vk] = sanitize_text_field( $vv );
						endif;
					endforeach;
				else :
					$options[$k] = sanitize_text_field( $v );
				endif;
			endforeach;

			$hpm_promo_meta = array(
				'location' => $_POST['hpm_promo']['location'],
				'type' => $_POST['hpm_promo']['type'],
				'options' => $options
			);

			update_post_meta( $post_id, 'hpm_promos_meta', $hpm_promo_meta );
		endif;
	}

	public function disable_wysiwyg( $default ) {
		if ( get_post_type() === 'promos' ) :
			return false;
		endif;
		return $default;
	}

	public function unpub_date() {
		global $post;
		if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) return false;
		if ( $post->post_type == 'promos' ) :
			$endtime = get_post_meta( $post->ID, 'hpm_promos_end_time', true );
			$offset = get_option('gmt_offset')*3600;
			if ( empty( $endtime ) ) :
				$t = time() + $offset + ( 24 * HOUR_IN_SECONDS );
			else :
				$t = $endtime + $offset;
			endif;
			$timeend = array(
				'mon' => date( 'm', $t),
				'day' => date( 'd', $t),
				'year' => date( 'Y', $t),
				'hour' => date( 'H', $t),
				'min' => date( 'i', $t)
			);

		?>
<div class="misc-pub-section curtime misc-pub-curtime">
	<span id="endtimestamp">End Date:</span>
	<fieldset id="endtimestampdiv">
		<legend class="screen-reader-text">End date and time</legend>
		<div class="timestamp-wrap">
			<label>
				<span class="screen-reader-text">Month</span>
				<select id="hpm_promo_end_mon" name="hpm_promo[end][mon]">
					<option value="01" data-text="Jan" <?PHP selected( $timeend['mon'], '01', TRUE ); ?>>01-Jan</option>
					<option value="02" data-text="Feb" <?PHP selected( $timeend['mon'], '02', TRUE ); ?>>02-Feb</option>
					<option value="03" data-text="Mar" <?PHP selected( $timeend['mon'], '03', TRUE ); ?>>03-Mar</option>
					<option value="04" data-text="Apr" <?PHP selected( $timeend['mon'], '04', TRUE ); ?>>04-Apr</option>
					<option value="05" data-text="May" <?PHP selected( $timeend['mon'], '05', TRUE ); ?>>05-May</option>
					<option value="06" data-text="Jun" <?PHP selected( $timeend['mon'], '06', TRUE ); ?>>06-Jun</option>
					<option value="07" data-text="Jul" <?PHP selected( $timeend['mon'], '07', TRUE ); ?>>07-Jul</option>
					<option value="08" data-text="Aug" <?PHP selected( $timeend['mon'], '08', TRUE ); ?>>08-Aug</option>
					<option value="09" data-text="Sep" <?PHP selected( $timeend['mon'], '09', TRUE ); ?>>09-Sep</option>
					<option value="10" data-text="Oct" <?PHP selected( $timeend['mon'], '10', TRUE ); ?>>10-Oct</option>
					<option value="11" data-text="Nov" <?PHP selected( $timeend['mon'], '11', TRUE ); ?>>11-Nov</option>
					<option value="12" data-text="Dec" <?PHP selected( $timeend['mon'], '12', TRUE ); ?>>12-Dec</option>
				</select>
			</label>
			<label>
				<span class="screen-reader-text">Day</span>
				<input type="text" id="hpm_promo_end_day" name="hpm_promo[end][day]" value="<?php echo $timeend['day']; ?>" size="2" maxlength="2" autocomplete="off">
			</label>,
			<label>
				<span class="screen-reader-text">Year</span>
				<input type="text" id="hpm_promo_end_year" name="hpm_promo[end][year]" value="<?php echo $timeend['year']; ?>" size="4" maxlength="4" autocomplete="off">
			</label> @
			<label>
				<span class="screen-reader-text">Hour</span>
				<input type="text" id="hpm_promo_end_hour" name="hpm_promo[end][hour]" value="<?php echo $timeend['hour']; ?>" size="2" maxlength="2" autocomplete="off">
			</label>:
			<label>
				<span class="screen-reader-text">Minute</span>
				<input type="text" id="hpm_promo_end_min" name="hpm_promo[end][min]" value="<?php echo $timeend['min']; ?>" size="2" maxlength="2" autocomplete="off">
			</label>
		</div>
	</fieldset>
</div>
<style>
	.curtime #endtimestamp {
		padding: 2px 0 1px 0;
		display: inline !important;
		height: auto !important;
	}
	.curtime #endtimestamp:before {
		content: "\f145";
		position: relative;
		top: -1px;
		font: normal 20px/1 dashicons;
		speak: none;
		display: inline-block;
		margin-left: -1px;
		padding-right: 3px;
		vertical-align: top;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		color: #82878c;
	}
	#endtimestampdiv {
		padding-top: 5px;
		line-height: 23px;
	}
	#endtimestampdiv select {
		height: 21px;
		line-height: 14px;
		padding: 0;
		vertical-align: top;
		font-size: 12px;
	}
	#endtimestampdiv input {
		border-width: 1px;
		border-style: solid;
	}
	#hpm_promo_end_day,
	#hpm_promo_end_hour,
	#hpm_promo_end_min {
		width: 2em;
	}
	#hpm_promo_end_year,
	#hpm_promo_end_day,
	#hpm_promo_end_hour,
	#hpm_promo_end_min {
		padding: 1px;
		font-size: 12px;
	}
</style>
<?php
		endif;
	}

	public function cleanup() {
		$t = time();
		$offset = get_option('gmt_offset')*3600;
		$t = $t + $offset;
		$now = getdate($t);
		$args = array(
			'post_type' => 'promos',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => 'hpm_promos_end_time',
					'value' => $now[0],
					'compare' => '<=',
				)
			)
		);
		$promos = new WP_Query( $args );
		if ( $promos->have_posts() ) :
			while ( $promos->have_posts() ) :
				$promos->the_post();
				wp_trash_post( get_the_ID() );
			endwhile;
		endif;
	}

	public function generate() {
		global $wp_query;
		$wp_global = $wp_query;
		$output = '';
		$lightbox = $fullwidth = 0;

		if ( $wp_global->is_page || $wp_global->is_single ) :
			$page_id = $wp_global->get_queried_object_id();
			$anc = get_post_ancestors( $page_id );
			$bans = [ 61263, 135762, 135920, 290722 ];
			$pt_slug = [ 'page-blank.php', 'page-ghr.php', 'page-elevator.php' ];
			if ( in_array( 61383, $anc ) || in_array( $page_id, $bans ) ) :
				return $output;
			elseif ( in_array( get_page_template_slug( $page_id ), $pt_slug ) ) :
				return $output;
			endif;
		endif;
		$args = [
			'post_type' => 'promos',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'order' => 'ASC'
		];
		$t = time();
		$now = getdate($t);
		if ( !empty( $_GET['testtime'] ) ) :
			$tt = explode( '-', $_GET['testtime'] );
			$offset = get_option( 'gmt_offset' ) * 3600;
			$now = getdate( mktime( $tt[0], $tt[1], 0, $tt[2], $tt[3], $tt[4] ) + $offset );
			$args['post_status'] = [ 'publish', 'future' ];
			$args['date_query'] = [
				[
					'before' => [
						'year' => $now['year'],
						'month' => $now['mon'],
						'day' => $now['mday']
					],
					'inclusive' => true
				]
			];
		endif;
		$args['meta_query'] = [
			[
				'key'     => 'hpm_promos_end_time',
				'value'   => $now[0],
				'compare' => '>=',
			]
		];
		$promos = new WP_Query( $args );
		if ( $promos->have_posts() ) :
			while ( $promos->have_posts() ) :
				$promos->the_post();
				$meta = get_post_meta( get_the_ID(), 'hpm_promos_meta', true );
				if ( empty( $meta ) ) :
					continue;
				endif;
				if ( $meta['location'] == 'homepage' && ! $wp_global->is_home ) :
					continue;
				endif;
				$content = get_the_content();
				$content_esc = str_replace( "'", "\'", $content );
				$content_esc = preg_replace( "/\r|\n|\t/", "", $content_esc );
				if ( $meta['type'] == 'sidebar' ) :
					preg_match( '/<script.+>(.+)?<\/script>/', $content, $match );
					if ( !empty( $match[0] ) ) :
						echo $content;
						continue;
					endif;
					$sizing = array();
					if ( !empty( $meta['options']['sidebar']['mobile'] ) ) :
						$sizing[] = "if ( wide <= 480 ) { var image = '".$meta['options']['sidebar']['mobile']."'; }";
					endif;
					if ( !empty( $meta['options']['sidebar']['tablet'] ) ) :
						$sizing[] = "if ( wide > 480 && wide <= 800 ) { var image = '".$meta['options']['sidebar']['tablet']."'; }";
					endif;
					if ( !empty( $meta['options']['sidebar']['desktop'] ) ) :
						$sizing[] = "if ( wide > 800 ) { var image = '".$meta['options']['sidebar']['desktop']."'; }";
					endif;
					if ( !empty( $sizing ) ) :
						$output .= implode( ' else ', $sizing );
					endif;
					$content_esc = str_replace( "[[image]]", "'+image+'", $content_esc  );
					if ( $wp_global->is_home || ( !empty( $page_id ) && get_page_template_slug( $page_id ) == 'page-main-categories.php' ) ) :
						$output .= "if ( document.getElementById('top-schedule-wrap') !== null ) { document.getElementById('top-schedule-wrap').insertAdjacentHTML('afterbegin', '".$content_esc."'); masonLoad(); }";
					else :
						$output .= "if ( document.querySelector( 'aside.column-right' ) !== null ) {document.querySelector('aside.column-right').insertAdjacentHTML('afterbegin', '".$content_esc."'); }";
					endif;
				elseif ( $meta['type'] == 'fullwidth' ) :
					if ( $fullwidth == 0 ) :
						$sizing = array();
						if ( !empty( $meta['options']['fullwidth']['mobile'] ) ) :
							$sizing[] = "if ( wide <= 480 ) { var image = '".$meta['options']['fullwidth']['mobile']."'; }";
						endif;
						if ( !empty( $meta['options']['fullwidth']['tablet'] ) ) :
							$sizing[] = "if ( wide > 480 && wide <= 800 ) { var image = '".$meta['options']['fullwidth']['tablet']."'; }";
						endif;
						if ( !empty( $meta['options']['fullwidth']['desktop'] ) ) :
							$sizing[] = "if ( wide > 800 ) { var image = '".$meta['options']['fullwidth']['desktop']."'; }";
						endif;
						if ( !empty( $sizing ) ) :
							$output .= implode( ' else ', $sizing );
						endif;
						$content_esc = str_replace( "[[image]]", "'+image+'", $content_esc  );
						$output .= "document.getElementById('primary').insertAdjacentHTML('afterbegin', '".$content_esc ."');";
						$fullwidth++;
					else :
						continue;
					endif;
				elseif ( $meta['type'] == 'lightbox' ) :
					if ( $lightbox == 0 ) :
						$output .= "
		var visited = getCookie('visited');";
						if ( preg_match( '/\[\[(link|image|text)\]\]/', $content_esc ) ) :
							$content_esc = str_replace(
								array( "[[link]]", "[[image]]", "[[text]]" ),
								array( "'+lblink+'", "'+lbimage+'", "'+lbtext+'" ),
								$content_esc );
							$output .= "
		var rand = Math.floor(Math.random() * 20);
		var lbtext, lblink, lbimage, lbox, primary;
		if ( rand > 9 ) {
			lbtext = '".$meta['options']['lightbox']['a']['text']."';
			lblink = '".$meta['options']['lightbox']['a']['link']."';
			lbimage = '".$meta['options']['lightbox']['a']['image']."';
		} else {
			lbtext = '".$meta['options']['lightbox']['b']['text']."';
			lblink = '".$meta['options']['lightbox']['b']['link']."';
			lbimage = '".$meta['options']['lightbox']['b']['image']."';
		}";
						endif;
						if ( !empty( $meta['options']['lightbox']['total'] ) ) :
							$remote = file_get_contents( $meta['options']['lightbox']['total'] );
							$total = json_decode( $remote, true );
//							if ( is_wp_error( $remote ) ) :
//								return false;
//							else :
//								$json = wp_remote_retrieve_body( $remote );
//							endif;
							$content_esc = str_replace( "[[total]]", $total['total'], $content_esc );
						endif;
						$output .= "
		var lightBox = '".$content_esc."';";
						$output .= "
		if (visited === null) {
			setCookie('visited','true',4);";
						if ( $wp_global->is_home || ( !empty( $page_id ) && get_page_template_slug( $page_id ) == 'page-main-categories.php' ) ) :
							$output .= "document.getElementById('top-schedule-wrap').insertAdjacentHTML('afterbegin', lightBox);
		masonLoad();";
						else :
							$output .= "document.getElementById('aside.column-right').insertAdjacentHTML('afterbegin', lightBox);";
						endif;
						$output .= "
			var campaign = document.querySelectorAll('#campaign-splash, #campaign-close');
			for (i = 0; i < campaign.length; ++i) {
				campaign[i].addEventListener('click', function() {
					document.getElementById('campaign-splash').style.display = 'none';
				});
			}
		}
";
						$lightbox++;
					else :
						continue;
					endif;
				endif;
			endwhile;
		endif;
		if ( !empty( $output ) ) :
			$output = "
<script>
	(function(){
		var wide = window.innerWidth;
		".$output."
		var topBanner = document.querySelectorAll('.top-banner');
		for (i = 0; i < topBanner.length; ++i) {
			topBanner[i].addEventListener('click', function() {
				var attr = this.id;
				if ( typeof attr !== typeof undefined && attr !== false) {
					ga('send', 'event', 'Top Banner', 'click', attr);
					ga('hpmRollup.send', 'event', 'Top Banner', 'click', attr);
				}
			});
		}
	}());
</script>";
		endif;
		return $output;
	}

	
	public function edit_columns( $columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Name' ),
			'promo_type' => __( 'Type' ),
			'promo_location' => __( 'Location' ),
			'date' => __( 'Date' ),
			'promo_expiration' => __( 'Expiration' )
		);
		return $columns;
	}
	
	public function manage_columns( $column, $post_id ) {
		global $post;
		$endtime = get_post_meta( $post->ID, 'hpm_promos_end_time', true );
		$offset = get_option('gmt_offset')*3600;
		$t = $endtime + $offset;
		$meta = get_post_meta( $post->ID, 'hpm_promos_meta', true );
		switch( $column ) {
			case 'promo_type' :
				if ( empty( $meta['type'] ) ) :
					echo __( 'None' );
				else :
					echo __( ucwords( $meta['type'] ) );
				endif;
				break;
			case 'promo_location' :
				if ( empty( $meta['location'] ) ) :
					echo __( 'None' );
				else :
					echo __( ucwords( $meta['location'] ) );
				endif;
				break;
			case 'promo_expiration' :
				echo date( 'F j, Y, g:i A', $t );
				break;
			default :
				break;
		}
	}
}
new HPM_Promos();