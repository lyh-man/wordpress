<?php

/**
 * Plugin Name: ChatGPT Integration (CI)
 * Description: Integrate ChatGPT into your WordPress site.
 * Version: 1.0.0
 * Author: yhliu
 * License: GPL2
 * Text Domain: wp-ci
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('CI_PLUGIN_VERSION', '1.0.0');
define('CI_PLUGIN_NAME', 'chatgpt-integration');
define('CI_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('CI_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));

function activate_plugin_name() {
    require_once CI_PLUGIN_DIR_PATH . 'includes/class.activator.php';
    CI_Activator::activate();
}

function deactivate_plugin_name() {
    require_once CI_PLUGIN_DIR_PATH . 'includes/class.deactivator.php';
    CI_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_plugin_name');
register_deactivation_hook(__FILE__, 'deactivate_plugin_name');

require CI_PLUGIN_DIR_PATH . 'includes/class.base.php';
$GLOBALS['ci_base'] = new CI_Base();
$GLOBALS['ci_base']->run();

//function run() {
//    $plugin = new CI_Base();
//
//    $plugin->run();
//}
//run();