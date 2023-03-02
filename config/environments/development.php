<?php
/** Development */
const SAVEQUERIES = true;
ini_set( 'display_errors', 1 );
const WP_DEBUG = true;
const WP_DEBUG_DISPLAY = true;
const SCRIPT_DEBUG = true;
const WP_DEBUG_LOG = true;
$_SERVER['SERVER_NAME'] = 'www.houstonpublicmedia.org';
define( 'AS3CF_ASSETS_PULL_SETTINGS', serialize( [
	'rewrite-urls' => false,
	'domain' => 'assets.houstonpublicmedia.org',
	'force-https' => false,
] ) );