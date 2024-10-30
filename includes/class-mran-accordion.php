<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themecentury.com/plugins/master-accordion/
 * @since      1.0.0
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Master_Accordion
 * @subpackage Master_Accordion/includes
 * @author     Theme Century Team <themecentury@gmail.com>
 */
class Master_Accordion {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Master_Accordion_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 */
	public function __construct() {

		$this->plugin_name = MASTER_ACCORDION_NAME;
		$this->version = MASTER_ACCORDION_VERSION;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_public_hooks();
		if(is_admin()){
			$this->define_admin_hooks();
		}
		$this->add_shortcodes();
		$this->add_admin_options();
		add_action( 'widgets_init', [$this, 'add_widgets'] );

	}


	public function add_admin_options() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mran-accordion-admin-options.php';

	}
	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Master_Accordion_Loader. Orchestrates the hooks of the plugin.
	 * - Master_Accordion_i18n. Defines internationalization functionality.
	 * - Master_Accordion_Admin. Defines all hooks for the admin area.
	 * - Master_Accordion_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mran-accordion-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mran-accordion-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */

		if(is_admin()){

 		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mran-accordion-admin.php';

		}
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mran-accordion-public.php';

		$this->loader = new Master_Accordion_Loader();

	}

	/**
	 * Add all shortcodes
	 *
	 * @access   private
	 */
	private function add_shortcodes(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/class-mran-accordion-default.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/class-mran-accordion-category.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/class-mran-tab-default.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/class-mran-tab-category.php';
	}

	/**
	 * Add all Widgets
	 * @access   public
	 */
	public function add_widgets(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/class-mran-accordion-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/class-mran-term-accordion-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/class-mran-nav-menu-accordion-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/class-mran-tab-widget.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/class-mran-term-tab-widget.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Master_Accordion_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Master_Accordion_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}



	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Master_Accordion_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the admin-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Master_Accordion_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Master_Accordion_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
