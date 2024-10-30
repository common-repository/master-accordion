<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themecentury.com/plugins/master-accordion/
 * @since      1.0.0
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/admin
 * @author     Theme Century Team <themecentury@gmail.com>
 */
class Master_Accordion_Admin_Options {

	public function __construct() {

		$this->register_custom_post_type();
		$this->register_option_page();

	}

	public function register_custom_post_type() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/posttype/class-mran-accordion-post-type.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/posttype/class-mran-tab-post-type.php';

	}

	public function register_option_page(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/optionpage/class-mran-settings-page.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/optionpage/class-mran-shortcode-generator.php';
	}

}

new Master_Accordion_Admin_Options();
