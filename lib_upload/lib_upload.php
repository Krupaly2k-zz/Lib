<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/krupaly2k
 * @since             1.0.0
 * @package           Lib_upload
 *
 * @wordpress-plugin
 * Plugin Name:       Library
 * Plugin URI:        lib_upload
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Krupal Lakhia
 * Author URI:        https://github.com/krupaly2k
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lib_upload
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lib_upload-activator.php
 */
function activate_lib_upload() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lib_upload-activator.php';
	Lib_upload_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lib_upload-deactivator.php
 */
function deactivate_lib_upload() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-lib_upload-deactivator.php';
	Lib_upload_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_lib_upload' );
register_deactivation_hook( __FILE__, 'deactivate_lib_upload' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-lib_upload.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lib_upload() {

	$plugin = new Lib_upload();
	$plugin->run();

}
run_lib_upload();
