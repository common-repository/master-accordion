<?php
/**
 * Plugin Name:       Master Accordion - Accordion, FAQ, Tabs, Shortcode & Widgets
 * Plugin URI:        https://themecentury.com/plugins/master-accordion/
 * Description:       Master Accordion is designed for tabs and accordion. This plugin have multiple accordion, tab, widget and shortcode features with a lot of Flexibility with multiple templates.
 * Version:           1.0.3
 * Author:            ThemeCentury Team
 * Author URI:        https://themecentury.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       master-accordion
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!defined('MASTER_ACCORDION_NAME')){
	define('MASTER_ACCORDION_NAME', 'master-accordion');
}
if(!defined('MASTER_ACCORDION_VERSION')){
	define('MASTER_ACCORDION_VERSION', '1.0.3');
}

require plugin_dir_path( __FILE__ ) . 'includes/function-mran-core.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mran-accordion-activator.php
 */
function activate_mran_accordion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mran-accordion-activator.php';
	Master_Accordion_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mran-accordion-deactivator.php
 */
function deactivate_mran_accordion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mran-accordion-deactivator.php';
	Master_Accordion_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mran_accordion' );
register_deactivation_hook( __FILE__, 'deactivate_mran_accordion' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mran-accordion.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mran_accordion() {

	$plugin = new Master_Accordion();
	$plugin->run();

}

run_mran_accordion();
