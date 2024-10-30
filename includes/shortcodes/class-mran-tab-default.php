<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The class for tab shortcode
 *
 * @link       https://themecentury.com/
 * @since      1.0.0
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/public
 */
class MRAN_Tab_Shortcode_Default {

	protected $atts;

	protected $content_type;

	protected $style;
	protected $template;
	protected $active_item;
	
	protected $tab_icon;
	protected $title_color;
	protected $title_background;
	protected $content_color;
	protected $content_background;

	/**
	 * @param no param
	 *
	 * @since      1.0.0
	 */
	public function __construct() {

		defined( 'WPINC' ) or exit;

		add_shortcode( 'mran_tab', array( $this, 'mran_tab' ) );
	}

	/**
	 * @param $atts is shortcode attribute
	 *
	 * @since      1.0.0
	 */
	public function filter_args( $atts ) {

		/*WP Query Args with attrs*/
		$default_args = array(

			'mran_content_type'		=> 'excerpt',

			'mran_style'			=> 'vertical',
			'mran_active_item'		=> 1,
			'mran_template'			=> 'default',

			'mran_tab_icon'			=> '',
			'mran_title_color'		=> '',
			'mran_title_background'	=> '',
			'mran_content_color'	=> '',
			'mran_content_background' => '',

			//Remaining arguments supports WP_Query Arguments

		);

		$this->atts = wp_parse_args( $atts, $default_args );

		return $this->atts;


	}

	public function mran_tab( $atts, $content = "" ) {

		$args = $this->filter_args( $atts );

		$this->content_type = isset($args['mran_content_type']) ? esc_attr($args['mran_content_type']) : 'excerpt';

		$this->style = isset($args['mran_style']) ? esc_attr($args['mran_style']) : 'vertical';
		$this->template = isset($args['mran_template']) ? $args['mran_template'] : 'default';
		$this->active_item = isset($args['mran_active_item']) ? absint($args['mran_active_item']) : 1;

		$this->tab_icon = isset($args['mran_tab_icon']) ? esc_attr($args['mran_tab_icon']) : '';
		$this->title_color = isset($args['mran_title_color']) ? esc_attr($args['mran_title_color']) : '';
		$this->title_background = isset($args['mran_title_background']) ? esc_attr($args['mran_title_background']) : '';
		$this->content_color = isset($args['mran_content_color']) ? esc_attr($args['mran_content_color']) : '';
		$this->content_background = isset($args['mran_content_background']) ? esc_attr($args['mran_content_background']) : '';

		ob_start();

		$this->template();

		$output = ob_get_contents();

		ob_get_clean();

		return $output;

	}

	public function mran_tab_args() { return $this->atts; }
	public function mran_content_type(){ return $this->content_type; }

	public function mran_tab_styles(){  return $this->style; }
	public function mran_tab_templates(){ return $this->template; }
	public function mran_active_item(){ return $this->active_item; }

	public function mran_tab_icon(){  return $this->tab_icon; }
	public function mran_title_color(){  return $this->title_color; }
	public function mran_title_background(){ return $this->title_background; }
	public function mran_content_color(){  return $this->content_color; }
	public function mran_content_background(){ return $this->content_background; }

	public function template() {

		add_filter( 'mran_tab_args', array( $this, 'mran_tab_args' ));
		add_filter( 'mran_tab_content_type', array( $this, 'mran_content_type' ));

		add_filter( 'mran_tab_templates', array( $this, 'mran_tab_templates' ));
		add_filter( 'mran_tab_styles', array( $this, 'mran_tab_styles' ));
		add_filter( 'mran_tab_activeitem', array( $this, 'mran_active_item' ));

		add_filter( 'mran_tab_icon', array( $this, 'mran_tab_icon' ));
		add_filter( 'mran_title_color', array( $this, 'mran_title_color' ));
		add_filter( 'mran_title_background', array( $this, 'mran_title_background' ));
		add_filter( 'mran_content_color', array( $this, 'mran_content_color' ));
		add_filter( 'mran_content_background', array( $this, 'mran_content_background' ) );

		$mran_loader = new Master_Accordion_Loader();

		$mran_loader->mran_template_part( 'public/partials/mran-tab-public-display.php' );

	}

}

new MRAN_Tab_Shortcode_Default();
