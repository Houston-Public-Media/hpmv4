<?php
/** Production */
ini_set( 'display_errors', 0 );
const WP_DEBUG = false;
const WP_DEBUG_DISPLAY = false;
const SCRIPT_DEBUG = false;
const WP_DEBUG_LOG = false;
/** Disable all file modifications including updates and update notifications */
const DISALLOW_FILE_MODS = false;
define( 'DB_HOST_2', env( 'DB_HOST_2' ) );

$_SERVER['HTTPS'] = 'on';
$protocol = 'https';