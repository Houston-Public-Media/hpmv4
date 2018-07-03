<?php
/* This class handles the settings page for the plugin
*/
if ( !defined( 'ABSPATH' ) ) exit;

class PBS_Media_Manager_Settings { 
	private $dir;
	private $file;
	private $assets_url;
	private $token;
	private $plugin;
	private $version;

	public function __construct( $file ) {
		$this->dir = dirname( $file );
		$this->file = $file;

		// Initialize the main plugin file
		if ( class_exists('PBS_Media_Manager' ) ) :
			$this->plugin = new PBS_Media_Manager( $file );
		else :
			error_log( 'could not find PBS_Media_Manager class' );
		endif;

		$this->assets_url = $this->plugin->assets_url;
		$this->token = $this->plugin->token;
		$this->version = $this->plugin->version;

		// Register plugin settings
		add_action( 'admin_init' , [ $this , 'register_settings' ] );

		// Add settings page to menu
		add_action( 'admin_menu' , [ $this , 'add_menu_item' ] );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , [ $this , 'add_settings_link' ] );

		// Run this after options are saved
		add_action( 'update_option_' . $this->token, [ $this, 'run_after_settings_saved' ], 10, 2);

		// Enable the ajax media manager lookup
		add_action( 'wp_ajax_pbs_media_manager_lookup', [ $this, 'ajax_media_manager_api_lookup' ], 10, 3);

		// setup custom script
		add_action( 'admin_enqueue_scripts', [ $this, 'setup_custom_scripts' ] );
	}

	public function setup_custom_scripts() {
		wp_enqueue_script( 'pbs-media-manager-admin', $this->assets_url . 'js/pbs-media-manager-admin.js', [ 'jquery' ]
		, $this->version, true );
	}
	
	public function add_menu_item() {
		$hook_suffix = add_options_page( 'PBS Media Manager Settings' , 'PBS Media Manager Settings' , 'manage_options' , $this->token . '_settings' ,  [ $this , 'settings_page' ] );
	}

	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page='.  $this->token . '_settings">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	public function register_settings() {
        register_setting( $this->token . '_group', $this->token );

	    add_settings_section('general_settings', 'General settings', [ $this, 'settings_section_callback' ], $this->token);

	    add_settings_field( 'mm_api_base', 'Media Manager API Base URL', [ $this, 'settings_field' ], $this->token, 'general_settings', [ 'setting' => $this->token, 'field' => 'mm_api_base', 'class' => 'regular-text', 'label' => 'Base URL for the Media Manager data endpoint', 'default' => 'https://media.services.pbs.org/api/v1' ] );

	    add_settings_field( 'mm_api_id', 'Media Manager API ID', [ $this, 'settings_field' ], $this->token, 'general_settings', [ 'setting' => $this->token, 'field' => 'mm_api_id', 'class' => 'regular-text', 'label' => 'Unique to this station, get from PBS' ] );

	    add_settings_field( 'mm_api_secret', 'Media Manager API Secret', [ $this, 'settings_field' ], $this->token, 'general_settings', [ 'setting' => $this->token, 'field' => 'mm_api_secret', 'class' => 'regular-text', 'label' => '' ] );

	    add_settings_field( 'station_uid', 'Station UID', [ $this, 'settings_field' ], $this->token,
		'general_settings', [ 'setting' => $this->token, 'field' => 'station_uid', 'class' => 'regular-text', 'label' => 'Optional: If making queries from a station context, hexadecimal ID as used by PBS to identify the station. Visit https://station.services.pbs.org/api/public/v1/stations/?call_sign={station_callsign} to get your Station ID' ] );

        add_settings_field( 'enable_passport', 'Enable Passport', [ $this, 'settings_field' ], $this->token, 'general_settings', [ 'setting' => $this->token, 'field' => 'enable_passport', 'type'=> 'checkbox', 'options' => [ 'true' ], 'class' => '', 'label' => 'Check to enable displaying Passport videos on program pages' ] );
	}

	public function settings_section_callback() { echo ' '; }

	public function settings_field( $args ) {
	    // This is the default processor that will handle most input fields.  Because it accepts a class, it can be styled or even have jQuery things (like a calendar picker) integrated in it.  Pass in a 'default' argument only if you want a non-empty default value.
	    $settingname = esc_attr( $args['setting'] );
	    $setting = get_option( $settingname );
	    $field = esc_attr( $args['field'] );
	    $label = esc_attr( $args['label'] );
	    $class = esc_attr( $args['class'] );
	    $type = ( !empty( $args['type'] ) ? esc_attr( $args['type'] ) : 'text' );
	    $options = ( !empty( $args['options'] ) && is_array( $args['options'] ) ? $args['options'] : [ 'true', 'false' ] );
	    $default = ( !empty( $args['default'] ) ? esc_attr( $args['default'] ) : '' );
		switch ( $type ) :
			case "checkbox":
				// dont set a default for checkboxes
				$value = $setting[$field];
				$values = ( is_array( $value ) ? $values = $value : [ $value ] );
				foreach( $options as $option ) :
					// each option can be an array but doesn't have to be
					if ( !is_array( $option ) ) :
						$option_label = $option;
						$option_value = $option;
					else :
						$option_label = ( isset( $option['label'] ) ? esc_attr( $option['label'] ) : $option[0] );
						$option_value = ( isset( $option['value'] ) ? esc_attr( $option['value'] ) : $option[0] );
					endif;
					$checked = in_array($option_value, $values) ? 'checked="checked"' : '';
					echo '<span class="' . $class . '"><input type="checkbox" name="' . $settingname . '[' . $field . ']" id="' . $settingname . '[' . $field . ']" value="' . $option_value . '" ' . $checked . ' />&nbsp;' . $option_label . ' </span> &nbsp; ';
				endforeach;
				echo '<label for="' . $field . '"><p class="description">' . $label . '</p></label>';
				break;
			default:
				// any case other than selects, radios, checkboxes, or textareas formats like a text input
				$value = ( ( $setting[$field] && strlen( trim( $setting[$field] ) ) ) ? $setting[$field] : $default );
				echo '<input type="' . $type . '" name="' . $settingname . '[' . $field . ']" id="' . $settingname . '[' . $field . ']" class="' . $class . '" value="' . $value . '" /><p class="description">' . $label . '</p>';
		endswitch;
	}

	public function settings_page() {
	    if ( !current_user_can( 'manage_options' ) ) :
	      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	    endif;
    ?>
		<div class="wrap">
			<h2>PBS Media Manager Settings</h2>
			<form action="options.php" method="POST">
				<?php settings_fields( $this->token . '_group' ); ?>
				<?php do_settings_sections( $this->token ); ?>
				<?php submit_button(); ?>
			</form>

			<form id="pbs_media_manager_lookup_form">
				<h3>Media Manager API Lookup</h3>
				<p>Enter either the slug or "content id" for a show or asset, or use a TP Media ID.  Select the correct endpoint</p>
				<select name="endpoint">
					<option>tp_media_id</option>
					<option>asset</option>
					<option>show</option>
					<option>franchise</option>
				</select>
				<input type="text" name="id_or_slug" />
				<button>Lookup this slug or id against the chosen endpoint</button>
				<pre class="mm_response"></pre>
			</form>
			<script>
				var pbs_media_manager_lookup_nonce = "<?php echo wp_create_nonce( "pbs_media_manager_lookup" ) ?>";
			</script>
		</div>
	<?php
	}

	public function run_after_settings_saved( $old_settings, $new_settings ) {
		// could use this for anything after the plugin settings have been saved.
	}

	public function ajax_media_manager_api_lookup() {
		if ( !wp_verify_nonce( $_POST["nonce"], "pbs_media_manager_lookup" ) ) exit( "Request not permitted." );
		$defaultargs = [ "platform-slug" => "partnerplayer" ];
		$options = get_option( $this->token );
		if ( !empty( $options['station_uid'] ) ) :
			$defaultargs['localized_station'] = $options['station_uid'];
		endif;
		$endpoint = $_REQUEST["endpoint"] ? $_REQUEST["endpoint"] : "asset";
		$id_or_slug = $_REQUEST["id"];
		$return = "called id";
		$client = $this->plugin->get_media_manager_client();
		if ( empty( $id_or_slug ) ) :
			$return = "id or slug required";
		endif;
		if ( $endpoint == "tp_media_id" ) :
			$return = $client->get_asset_by_tp_media_id( $id_or_slug );
		else :
			$return = $client->get_item_of_type( $id_or_slug, $endpoint, $defaultargs );
		endif;
		echo json_encode( $return );
		die();
	}
}