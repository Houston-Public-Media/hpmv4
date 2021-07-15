<?php
	/*
Plugin Name: KERA Passport Tools
Plugin URI: http://www.kera.org
Description: Plugin for interacting with PBS MVault in regards to Passport
Author: Justin Bowers
Version: 1.0
Author URI: http://www.kera.org
 */	
 
 /*
 The following variables below will need to be filled in with the info from PBS:
 
 client_id
 client_secret
 station_id
 station_call_letters
 laas_client_id
 lass_client_secret
 
 The following link is a good starting point for getting the credentials you need:
 https://docs.pbs.org/display/MV/Custom+Implementation
  */

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

require_once('classes/class-KERA-PBS-MVault-client.php');

function kera_passport_activate() {
	$defaults = [
		'client_id' => HPM_MVAULT_ID,
		'client_secret' => HPM_MVAULT_SECRET,
		'station_id' => '',
		'station_call_letters' => 'KUHT',
		'mvault_url' => 'https://mvault.services.pbs.org/api/',
		'oauth2_endpoint' => 'https://account.pbs.org/oauth2/',
		'laas_client_id' => '',
		'laas_client_secret' => ''
	];
	$station_id = ( !empty( $defaults['station_id'] ) ? $defaults['station_id'] : $defaults['station_call_letters'] );
	$defaults['mvault_endpoint'] = 'https://mvault.services.pbs.org/api/';
	
	$activation_token = ( !empty( $_REQUEST['activation_token'] ) ? str_replace( ' ', '-', trim( $_REQUEST['activation_token'] ) ) : '' );

	if ( $activation_token ) :
		
		$mvault_client = new KERA_PBS_MVault_Client($defaults['client_id'], $defaults['client_secret'],$defaults['mvault_endpoint'], $station_id);
		
		
		$mvaultinfo = $mvault_client->lookup_activation_token($activation_token);
		
		if ( empty( $mvaultinfo['membership_id'] ) ) :
			$return['errors'] = [ 'message' => 'This activation code is invalid', 'class' => 'error' ];
		else :
			if ( $mvaultinfo['status'] !== 'On' ) :
				$return['errors'] = [ 'message' => 'This account has been disabled', 'class' => 'error' ];
			endif;
			if ( !empty( $mvaultinfo['activation_date'] ) ) :
				$obscured = $mvault_client->obscured_login_account($mvaultinfo);
				$obs_msg = '';
				if ( $obscured ) :
					$obs_msg = "</h3><p>This is the email that was used to activate your account:<br /><b>$obscured</b><br />We've obscured all but the first characters and changed the lengths of each part of the email address to protect your privacy.</p><h3>";
				endif;
				$return['errors'] = array('message' => 'Your account has already been activated..' . $obs_msg . '  You only need to activate the first time you use KUHT TV 8 Passport.<br /><br />', 'class' => 'info');
			endif;

			if ( empty( $return['errors'] ) ) :
				echo "<script>window.location.href = 'https://www.pbs.org/passport/activate/".$mvaultinfo['token']."';</script>";
				exit();
			endif;
		endif;
	endif;
	$sStr = '';
	$sStr .= '<form action="" method="POST" class="cf" id="passport-activate">';
		$sStr .= '<input name="activation_token" type="text" value="'.$activation_token.'" />';
		$sStr .= '<button class="passport-button">Enter Code</button>';
	$sStr .= '</form>';

	if ( !empty( $return['errors'] ) ) :
		$sStr .= "<h3 class='" . $return['errors']['class'] . "'>" . $return['errors']['message'] . "</h3>";
	endif;
	return $sStr;
}
add_shortcode( 'kera_passport_activate', 'kera_passport_activate' );

function kera_passport_lookup() {
	global $wpdb;
	$defaults = [
		'client_id' => HPM_MVAULT_ID,
		'client_secret' => HPM_MVAULT_SECRET,
		'station_id' => '',
		'station_call_letters' => 'KUHT',
		'mvault_url' => 'https://mvault.services.pbs.org/api/',
		'oauth2_endpoint' => 'https://account.pbs.org/oauth2/',
		'laas_client_id' => '',
		'laas_client_secret' => ''
	];
	$station_id = ( !empty( $defaults['station_id'] ) ? $defaults['station_id'] : $defaults['station_call_letters'] );
	$defaults['mvault_endpoint'] = 'https://mvault.services.pbs.org/api/';
	
	$passport_email = ( !empty( $_REQUEST['passport_email'] ) ? $_REQUEST['passport_email'] : '' );

	if ( $passport_email ) :
		$wpdb->query( $wpdb->prepare( 'INSERT INTO hpm_passport_activations (email) VALUES (%s)', $passport_email ) );
		$mvault_client = new KERA_PBS_MVault_Client( $defaults['client_id'], $defaults['client_secret'], $defaults['mvault_endpoint'], $station_id );
		
		$mvaultinfo = $mvault_client->get_membership_by_email( $passport_email );

		if ( empty( $mvaultinfo[0]['membership_id'] ) ) :
			$return['errors'] = [ 'message' => 'We do not have that email address on file. If you do not have an email address associated with your membership, <a href="https://www.houstonpublicmedia.org/contact-us/">message us</a> or call 713.743.8483.', 'class' => 'error' ];
		else :
			if ( $mvaultinfo[0]['status'] !== 'On' ) :
				$return['errors'] = [ 'message' => 'This account has been disabled', 'class' => 'error' ];
			endif;
			if ( !empty( $mvaultinfo[0]['activation_date'] ) ) :
				$obscured = $mvault_client->obscured_login_account($mvaultinfo[0]);
				$obs_msg = '';
				if ( $obscured ) :
					$obs_msg = "</h3><p>This is the email that was used to activate your account:<br /><b>".$obscured."</b><br />We've obscured all but the first characters and changed the lengths of each part of the email address to protect your privacy.</p><h3>";
				endif;
				$return['errors'] = [ 'message' => 'Your Passport has already been activated. <a href="http://www.pbs.org/?showSignIn=true&returnURL=http://video.houstonpbs.org/" target="_blank">Sign in or create an account</a>.<br /><br />', 'class' => 'info' ];
			endif;

			if ( empty( $return['errors'] ) ) : 
				$message = "<html><head><title>KUHT TV 8 Passport Activation</title></head><body><h3>Welcome to KUHT TV 8 Passport, and thank you for your support of KUHT TV 8!</h3><p>Your KUHT TV 8 Passport activation code is <b>".$mvaultinfo[0]['token']."</b>. Please enter this four-word code at <a href='https://www.houstonpublicmedia.org/support/passport/'>houstonpublicmedia.org/support/passport/</a> or click <a href='https://www.houstonpublicmedia.org/support/passport/?activation_token=".$mvaultinfo[0]['token']."'>this link</a> to activate.</p><br><br><p>If you experience issues activating your Passport account, e-mail <a href='mailto:help@pbs.org'>help@pbs.org</a> or call 844-859-5372.</body></html>";

				$to      = $mvaultinfo[0]['email'];
				$subject = 'Your KUHT TV 8 Passport Activation Code';
				$headers[] = 'MIME-Version: 1.0';
				$headers[] = 'Content-type: text/html; charset=iso-8859-1';

				// Additional headers
				$headers[] = 'From: KUHT TV 8 Passport <webmaster@houstonpublicmedia.org>';
				mail($to, $subject, $message, implode("\r\n", $headers));
				$return['errors'] = array('message' => 'An email has been sent to you with your activation code.<br /><br />', 'class' => 'info');

			endif;
		endif;

	endif;
	
	$sStr = '';
	$sStr .= '<form action="" method="POST" class="cf" id="passport-lookup">';
		$sStr .= '<input name="passport_email" type="text" value="'.$passport_email.'" />';
		$sStr .= '<button class="passport-button">Submit Email</button>';
	$sStr .= '</form>';

	if ( !empty( $return['errors'] ) ) :
		$sStr .= "<h3 class='" . $return['errors']['class'] . "'>" . $return['errors']['message'] . "</h3>";
	endif;
	return $sStr;
}
add_shortcode( 'kera_passport_lookup', 'kera_passport_lookup' );


//------------Enqueue the Styles------------------------
// function kera_passport_styles() {
// 	wp_enqueue_style( 'kera-passport-styles', plugin_dir_url( __FILE__ ) . 'styles.css', array(), '0.1', 'screen' );
// }
// add_action( 'wp_enqueue_scripts', 'kera_passport_styles' );

?>