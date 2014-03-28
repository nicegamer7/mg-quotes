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

require_once MG_QT_INCLUDES . 'functions.php';
require_once MG_QT_INCLUDES . 'admin.php';
require_once MG_QT_INCLUDES . 'template-tags.php';
require_once MG_QT_INCLUDES . 'shortcodes.php';
require_once MG_QT_INCLUDES . 'widgets/init.php';
