<?php
/**
 * @link 			https://github.com/jwcounts
 * @since  			20170906
 * @package  		HPM-Listings
 *
 * @wordpress-plugin
 * Plugin Name: 	HPM Listings
 * Plugin URI: 		https://github.com/jwcounts
 * Description: 	Episode information listing for locally-produced shows
 * Version: 		20171108
 * Author: 			Jared Counts
 * Author URI: 		http://www.houstonpublicmedia.org/staff/jared-counts/
 * License: 		GPL-2.0+
 * License URI: 	http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 	hpmv2
 *
 * Works best with Wordpress 4.6.0+
 */

class HPM_Listings {

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Init
	 */
	public function init() {
		add_action( 'init', array( $this, 'create_type' ) );
		add_action( 'init', array( $this, 'create_taxonomies' ) );
		add_action( 'admin_init', array( $this, 'add_role_caps' ), 999 );
		add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );
		add_action( 'post_submitbox_misc_actions', array( $this, 'unpub_date' ) );
		add_action( 'hpm_listing_cleanup', array( $this, 'cleanup' ) );
		add_filter( 'manage_edit-hpm_listings_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_hpm_listings_posts_custom_column', array( $this, 'manage_columns' ), 10, 2 );

		// Make sure that the proper cron job is scheduled
		if ( ! wp_next_scheduled( 'hpm_listing_cleanup' ) ) :
			wp_schedule_event( time(), 'daily', 'hpm_listing_cleanup' );
		endif;
	}

	public function create_type() {
		register_post_type( 'hpm_listings',
			array(
				'labels'               => array(
					'name'               => __( 'Schedule Listings' ),
					'singular_name'      => __( 'Schedule Listing' ),
					'menu_name'          => __( 'Schedule Listings' ),
					'add_new_item'       => __( 'Add New Schedule Listing' ),
					'edit_item'          => __( 'Edit Schedule Listing' ),
					'new_item'           => __( 'New Schedule Listing' ),
					'view_item'          => __( 'View Schedule Listing' ),
					'search_items'       => __( 'Search Schedule Listings' ),
					'not_found'          => __( 'Schedule Listing Not Found' ),
					'not_found_in_trash' => __( 'Schedule Listing not found in trash' )
				),
				'description'          => 'Episode information listing for locally-produced shows',
				'public'               => false,
				'show_ui'              => true,
				'show_in_admin_bar'    => true,
				'menu_position'        => 15,
				'menu_icon'            => 'dashicons-calendar-alt',
				'has_archive'          => false,
				'rewrite'              => false,
				'supports'             => array( 'title', 'editor' ),
				'taxonomies'           => array( 'listing_category' ),
				'capability_type'      => array( 'hpm_listing', 'hpm_listings' ),
				'map_meta_cap'         => true
			)
		);
	}

	public function create_taxonomies() {
		register_taxonomy('listing_category', 'hpm_listings', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Listing Category', 'taxonomy general name' ),
				'singular_name' => _x( 'listing-category', 'taxonomy singular name' ),
				'search_items' =>  __( 'Search Listing Categories' ),
				'all_items' => __( 'All Listing Categories' ),
				'parent_item' => __( 'Parent Listing Category' ),
				'parent_item_colon' => __( 'Parent Listing Category:' ),
				'edit_item' => __( 'Edit Listing Category' ),
				'update_item' => __( 'Update Listing Category' ),
				'add_new_item' => __( 'Add New Listing Category' ),
				'new_item_name' => __( 'New Listing Category Name' ),
				'menu_name' => __( 'Listing Categories' )
			),
			'public' => false,
			'rewrite' => false,
			'show_ui' => true,
			'show_in_admin_bar' => true
		));
	}

	public function add_role_caps() {
		// Add the roles you'd like to administer the custom post types
		$roles = array( 'editor','administrator' );

		// Loop through each role and assign capabilities
		foreach ( $roles as $the_role ) :
			$role = get_role( $the_role );
			$role->add_cap( 'read' );
			$role->add_cap( 'read_hpm_listing' );
			$role->add_cap( 'read_private_hpm_listings' );
			$role->add_cap( 'edit_hpm_listing' );
			$role->add_cap( 'edit_hpm_listings' );
			$role->add_cap( 'edit_others_hpm_listings' );
			$role->add_cap( 'edit_published_hpm_listings' );
			$role->add_cap( 'publish_hpm_listings' );
			$role->add_cap( 'delete_others_hpm_listings' );
			$role->add_cap( 'delete_private_hpm_listings' );
			$role->add_cap( 'delete_published_hpm_listings' );
		endforeach;
	}

	function save_meta( $post_id, $post ) {
		if ( $post->post_type == 'hpm_listings' ) :
			/* Verify the nonce before proceeding. */
			if ( ! isset( $_POST['hpm_listings_class_nonce'] ) || ! wp_verify_nonce( $_POST['hpm_listings_class_nonce'],
					basename( __FILE__ ) ) ) :
				return $post_id;
			endif;

			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );

			/* Check if the current user has permission to edit the post. */
			if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) :
				return $post_id;
			endif;

			$hpend = $_POST['hpm_listings']['end'];

			foreach ( $hpend as $hpe ) :
				if ( !is_numeric( $hpe ) || $hpe == '' ) :
					return $post_id;
				endif;
			endforeach;

			$offset = get_option('gmt_offset')*3600;
			$endtime = mktime( $hpend['hour'], $hpend['min'], 0, $hpend['mon'], $hpend['day'], $hpend['year'] ) - $offset;
			update_post_meta( $post_id, 'hpm_listings_end_time', $endtime );
		endif;
	}


	function unpub_date() {
		global $post;
		if ( ! current_user_can( 'edit_others_posts', $post->ID ) ) return false;
		if ( $post->post_type == 'hpm_listings' ) :
			wp_nonce_field( basename( __FILE__ ), 'hpm_listings_class_nonce' );
			$endtime = get_post_meta( $post->ID, 'hpm_listings_end_time', true );
			$offset = get_option('gmt_offset')*3600;
			if ( empty( $endtime ) ) :
				$t = time() + $offset + ( 72 * HOUR_IN_SECONDS );
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
				<select id="hpm_listings_end_mon" name="hpm_listings[end][mon]">
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
				<input type="text" id="hpm_listings_end_day" name="hpm_listings[end][day]" value="<?php echo $timeend['day']; ?>" size="2" maxlength="2" autocomplete="off">
			</label>,
			<label>
				<span class="screen-reader-text">Year</span>
				<input type="text" id="hpm_listings_end_year" name="hpm_listings[end][year]" value="<?php echo $timeend['year']; ?>" size="4" maxlength="4" autocomplete="off">
			</label> @
			<label>
				<span class="screen-reader-text">Hour</span>
				<input type="text" id="hpm_listings_end_hour" name="hpm_listings[end][hour]" value="<?php echo $timeend['hour']; ?>" size="2" maxlength="2" autocomplete="off">
			</label>:
			<label>
				<span class="screen-reader-text">Minute</span>
				<input type="text" id="hpm_listings_end_min" name="hpm_listings[end][min]" value="<?php echo $timeend['min']; ?>" size="2" maxlength="2" autocomplete="off">
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
	#hpm_listings_end_day,
	#hpm_listings_end_hour,
	#hpm_listings_end_min {
		width: 2em;
	}
	#hpm_listings_end_year,
	#hpm_listings_end_day,
	#hpm_listings_end_hour,
	#hpm_listings_end_min {
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
		$t = $t + $offset - 432000;
		$now = getdate($t);
		$args = array(
			'post_type' => 'hpm_listings',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key'     => 'hpm_listings_end_time',
					'value'   => $now[0],
					'compare' => '<=',
				)
			)
		);
		$promos = new WP_Query( $args );
		if ( $promos->have_posts() ) :
			while ( $promos->have_posts() ) :
				$promos->the_post();
				wp_delete_post( get_the_ID(), false );
			endwhile;
		endif;
	}

	public static function generate( $category ) {
		if ( empty( $category ) ) :
			return '';
		endif;
		$t = time();
		$offset = get_option('gmt_offset')*3600;
		$t = $t + $offset;
		$now = getdate($t);
		$args = array(
			'post_type' => 'hpm_listings',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'tax_query' => array(
				array(
					'taxonomy' => 'listing_category',
					'field'    => 'slug',
					'terms'    => $category,
				),
			),
			'meta_query' => array(
				array(
					'key'     => 'hpm_listings_end_time',
					'value'   => $now[0],
					'compare' => '>=',
				)
			),
			'orderby' => 'meta_value_num',
			'meta_key' => 'hpm_listings_end_time',
			'order' => 'ASC'
		);
		$output = '';
		$events = new WP_Query( $args );
		if ( $events->have_posts() ) :
			$output .= '<div class="show-content"><h3>Upcoming Episodes</h3><div class="upcoming-eps">';
			while ( $events->have_posts() ) : $events->the_post();
				$output .= '<div><h2>'.get_the_title().'</h2>'.get_the_content().'</div>';
			endwhile;
			$output .= '</div></div>';
		endif;
		wp_reset_query();
		return $output;
	}

	public function edit_columns( $columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title' ),
			'listing_category' => __( 'Category' ),
			'start_date' => __( 'Start Date' ),
			'end_date' => __( 'End Date' )
		);
		return $columns;
	}

	public function manage_columns( $column, $post_id ) {
		global $post;
		$endtime = date( 'F j, Y, g:i A', get_post_meta( $post_id, 'hpm_listings_end_time', true ) );
		$date = get_the_date( 'F j, Y, g:i A', $post_id );
		switch( $column ) {
			case 'end_date' :
				echo $endtime;
				break;
			case 'start_date' :
				echo $date;
				break;
			case 'listing_category' :
				$terms = get_the_terms( $post_id, 'listing_category' );
				if ( !empty( $terms ) ) {
					$out = array();
					foreach ( $terms as $term ) {
						$out[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'listing_category' => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'listing_category', 'display' ) )
						);
					}
					echo join( ', ', $out );
				}
				else {
					_e( '--' );
				}
				break;
			default :
				break;
		}
	}
}

new HPM_Listings();