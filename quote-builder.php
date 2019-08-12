<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/mbezuidenhout/
 * @since             1.0.0
 * @package           Quote_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       Quote Builder
 * Plugin URI:        https://github.com/mbezuidenhout/wordpress-quote-builder
 * Description:       Build quotes for customers with multiple revisions.
 * Version:           1.0.0
 * Author:            Marius Bezuidenhout
 * Author URI:        https://profiles.wordpress.org/mbezuidenhout/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       quote-builder
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
define( 'QUOTE_BUILDER_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-quote-builder-activator.php
 */
function activate_quote_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quote-builder-activator.php';
	Quote_Builder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-quote-builder-deactivator.php
 */
function deactivate_quote_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-quote-builder-deactivator.php';
	Quote_Builder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_quote_builder' );
register_deactivation_hook( __FILE__, 'deactivate_quote_builder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-quote-builder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_quote_builder() {

	$plugin = new Quote_Builder();
	$plugin->run();

}
run_quote_builder();
