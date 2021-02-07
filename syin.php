<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              None
 * @since             1.0.0
 * @package           Syin
 *
 * @wordpress-plugin
 * Plugin Name:       Sybos integration
 * Plugin URI:        None
 * Description:       Integration for Sybos CMS used by security organizations in Austria.
 * Version:           1.0.0
 * Author:            Sebastian
 * Author URI:        None
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       syin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SYIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-syin-activator.php
 */
function activate_syin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-syin-activator.php';
	Syin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-syin-deactivator.php
 */
function deactivate_syin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-syin-deactivator.php';
	Syin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_syin' );
register_deactivation_hook( __FILE__, 'deactivate_syin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-syin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_syin() {
    $plugin = new Syin();
	$plugin->run();

}
run_syin();