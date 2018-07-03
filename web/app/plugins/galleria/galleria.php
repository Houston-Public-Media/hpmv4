<?php
/*
Plugin Name: HPM Galleria
Plugin URI: http://www.houstonpublicmedia.org
Description: Displays a beautiful Galleria slideshow in place of the built-in WordPress image grid. Overrides the default functionality of the [gallery] shortcode. Bugfixes and upgrades to Galleria 1.5.7 made by Jared Counts.
Version: 1.0.4
Author: Andy Whalen & Jared Counts
Author URI: http://www.houstonpublicmedia.org/
License: The MIT License
*/

require_once dirname(__FILE__) . '/amw-galleria/AMWGalleria.php';
$amw_galleria = new AMWGalleria(plugins_url(basename(dirname(__FILE__))));
