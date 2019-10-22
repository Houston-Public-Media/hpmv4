<?php
/** Production */
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
/** Disable all file modifications including updates and update notifications */
define( 'DISALLOW_FILE_MODS', false );
define( 'DB_HOST_2', env( 'DB_HOST_2' ) );

$_SERVER['HTTPS']='on';
$protocol = 'https';