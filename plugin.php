<?php
/*
Plugin Name: mg WP Quotes
Plugin URI: http://wp-labs.dev/admin
Description: An easy to use plugin for quotes.
Version: 1.0
Author: Giulio 'mgiulio' Mainardi
Author URI: http://m-giulio.me
License: GPL2
*/

if (!defined('ABSPATH')) exit;

define('MG_QT_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('MG_QT_INCLUDES', MG_QT_PLUGIN_DIR_PATH . 'includes/');
define('MG_QT_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define('MG_QT_ASSETS', MG_QT_PLUGIN_DIR_URL . 'assets/');

function mg_qt_log($x) {	
	$out = '';
	ob_start();
	var_dump($x);
	$out .= ob_get_clean();
	
	error_log($out);
}

require_once MG_QT_INCLUDES . 'admin/init.php';
require_once MG_QT_INCLUDES . 'query.php';
require_once MG_QT_INCLUDES . 'template-tags.php';
require_once MG_QT_INCLUDES . 'shortcodes.php';
require_once MG_QT_INCLUDES . 'widgets/init.php';
