<?php
/** Development */
define( 'SAVEQUERIES', true );
ini_set( 'display_errors', 1 );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );
define( 'SCRIPT_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_REDIS_SCHEME', 'unix' );
define( 'WP_REDIS_PATH', '/Applications/MAMP/tmp/redis.sock' );
$_SERVER['SERVER_NAME'] = 'www.houstonpublicmedia.org';