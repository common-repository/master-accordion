<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themecentury.com/plugins/master-accordion/
 * @since      1.0.0
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Master_Accordion
 * @subpackage Master_Accordion/includes
 * @author     Theme Century Team <themecentury@gmail.com>
 */
class Master_Accordion_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'master-accordion',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
