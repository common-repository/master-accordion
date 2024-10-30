<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The class for accordion shortcode
 *
 * @link       https://themecentury.com/
 * @since      1.0.0
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/public
 */
class MRAN_Accordion_Shortcode_Group {

	protected $atts;

	protected $content_type;

	protected $style;
	protected $template;
	protected $active_item;

	protected $dropdown_icon;
	protected $active_dp_icon;
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

		add_shortcode( 'mran_category_accordion', array( $this, 'mran_category_accordion' ) );
	}


	/**
	 * @param $atts is shortcode attribute
	 *
	 * @since      1.0.0
	 */
	public function filter_args( $atts ) {

		/*WP Query Args*/
		$default_args = array(

			'mran_content_type'		=> 'excerpt',

			'mran_style'			=> 'vertical',
			'mran_active_item'		=> 1,
			'mran_template'			=> 'default',

			'mran_dropdown_icon'	=> 'fa-toggle-off',
			'mran_active_dp_icon'	=> 'fa-toggle-on',
			'mran_title_color'		=> '',
			'mran_title_background'	=> '',
			'mran_content_color'	=> '',
			'mran_content_background' => '',

			//Remaining arguments supports WP_Query Arguments

		);

		$this->atts = wp_parse_args( $atts, $default_args );

		return $this->atts;

	}

	public function mran_category_accordion( $atts, $content = "" ) {

		$args = $this->filter_args( $atts );

		if ( ! isset( $args['id'] ) ) {
			return;
		}


		$taxonomy_id = absint( $args['id'] );
		$post_limit = isset( $args['limit'] ) ? $args['limit'] : - 1;

		$this->content_type = isset( $args['mran_content_type'] ) ? $args['mran_content_type'] : 'excerpt';

		$this->template = get_term_meta( $taxonomy_id, 'mran_term_template', true );
		$this->style = get_term_meta( $taxonomy_id, 'acwp_term_style', true );
		$this->active_item = isset($args['mran_active_item']) ? absint($args['mran_active_item']) : 1;

		$this->dropdown_icon = isset( $args['mran_dropdown_icon'] ) ? $args['mran_dropdown_icon'] : 'fa-toggle-off';
		$this->active_dp_icon = isset( $args['mran_active_dp_icon'] ) ? $args['mran_active_dp_icon'] : 'fa-toggle-on';
		$this->title_color = isset( $args['mran_title_color'] ) ? $args['mran_title_color'] : '';
		$this->title_background = isset( $args['mran_title_background'] ) ? $args['mran_title_background'] : '';
		$this->content_color = isset( $args['mran_content_color'] ) ? $args['mran_content_color'] : '';
		$this->content_background = isset( $args['mran_content_background'] ) ? $args['mran_content_background'] : '';

		$query_args = array(
			'posts_per_page' => $post_limit,
			'post_type'      => 'master-accordion',
			'tax_query'      => array(
				array(
					'taxonomy' => 'accordion-category',
					'field'    => 'term_id',
					'terms'    => $taxonomy_id,
				)
			)
		);
		$this->atts = wp_parse_args($query_args, $args);

		ob_start();

		$this->template();

		$output = ob_get_contents();

		ob_get_clean();

		return $output;

	}

	public function mran_accordion_args() { return $this->atts; }
	public function mran_content_type(){ return $this->content_type; }

	public function mran_accordion_styles(){  return $this->style; }
	public function mran_accordion_templates(){ return $this->template; }
	public function mran_active_item(){ return $this->active_item; }

	public function mran_dropdown_icon(){  return $this->dropdown_icon; }
	public function mran_active_dp_icon(){ return $this->active_dp_icon; }
	public function mran_title_color(){  return $this->title_color; }
	public function mran_title_background(){ return $this->title_background; }
	public function mran_content_color(){  return $this->content_color; }
	public function mran_content_background(){ return $this->content_background; }

	
	public function template() {

		add_filter( 'mran_accordion_args', array( $this, 'mran_accordion_args' ) );

		add_filter( 'mran_accordion_content_type', array( $this, 'mran_content_type' ) );

		add_filter( 'mran_accordion_styles', array( $this, 'mran_accordion_styles' ) );
		add_filter( 'mran_accordion_templates', array( $this, 'mran_accordion_templates' ) );
		add_filter( 'mran_accordion_activeitem', array( $this, 'mran_active_item' ));

		add_filter( 'mran_dropdown_icon', array( $this, 'mran_dropdown_icon' ) );
		add_filter( 'mran_active_dp_icon', array( $this, 'mran_active_dp_icon' ) );
		add_filter( 'mran_title_color', array( $this, 'mran_title_color' ));
		add_filter( 'mran_title_background', array( $this, 'mran_title_background' ));
		add_filter( 'mran_content_color', array( $this, 'mran_content_color' ));
		add_filter( 'mran_content_background', array( $this, 'mran_content_background' ) );
		

		$mran_loader = new Master_Accordion_Loader();
		$mran_loader->mran_template_part( 'public/partials/mran-accordion-public-display.php' );

	}

}

new MRAN_Accordion_Shortcode_Group();
