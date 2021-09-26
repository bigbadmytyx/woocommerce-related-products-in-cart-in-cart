<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://example.com/
 * @since             1.0.0
 * @package           Woocrp
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce related products in cart
 * Plugin URI:        https://example.com/
 * Description:       WordPress (WooCommerce) plugin that shows 5 related products on cart's page, based on previous purchase history.
 * Version:           1.0.0
 * Author:            Dmitry Lebedko
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocrp
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
define( 'WOOCRP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocrp-activator.php
 */
function activate_woocrp() {
	require_once plugin_dir_path( __FILE__ ) . 'partials/class-woocrp-activator.php';
	Woocrp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocrp-deactivator.php
 */
function deactivate_woocrp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocrp-deactivator.php';
	Woocrp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocrp' );
register_deactivation_hook( __FILE__, 'deactivate_woocrp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocrp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocrp() {

	$plugin = new Woocrp();
	$plugin->run();

}
run_woocrp();
