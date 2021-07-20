<?php
/** Development */
define( 'SAVEQUERIES', true );
ini_set( 'display_errors', 1 );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'SCRIPT_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
$_SERVER['SERVER_NAME'] = 'www.houstonpublicmedia.org';
define( 'AS3CF_ASSETS_PULL_SETTINGS', serialize( [
	'rewrite-urls' => false,
	'domain' => 'assets.hpm.io',
	'force-https' => false,
] ) );