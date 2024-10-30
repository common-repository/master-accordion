<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!function_exists('mran_accordion_templates')){
	function mran_accordion_templates() {

		return apply_filters(

			'mran_accordion_templates', array(

				'default'       => esc_html__( 'Default', 'master-accordion' ),
				'template-1'    => esc_html__( 'Template 1', 'master-accordion' ),
				'theme-default' => esc_html__( 'Theme default', 'master-accordion' ),
			)
		);
	}
}

if(!function_exists('mran_sanitize_accordion_templates')){

	function mran_sanitize_accordion_templates($template='') {

		$default = 'template-1';
		$accordion_templates = mran_accordion_templates();
		if(isset($accordion_templates[$template])){
			return $template;
		}else{
			return $default;
		}

	}

}

if(!function_exists('mran_accordion_styles')){
	function mran_accordion_styles() {

		return apply_filters(

			'mran_accordion_styles', array(
				'vertical'   => esc_html__( 'Vertical', 'master-accordion' ),
				'horizontal' => esc_html__( 'Horizontal', 'master-accordion' ),
			)
		);
	}
}

if(!function_exists('mran_sanitize_accordion_styles')){

	function mran_sanitize_accordion_styles($style='') {

		$default = 'vertical';
		$accordion_style = mran_accordion_styles();
		if(isset($accordion_style[$style])){
			return $style;
		}else{
			return $default;
		}

	}

}

if(!function_exists('mran_accordion_content_type')){
	function mran_accordion_content_type() {
		return apply_filters(
			'mran_accordion_content_type', array(
				'excerpt'   => esc_html__( 'Excerpt', 'master-accordion' ),
				'content' => esc_html__( 'Full Content', 'master-accordion' ),
			)
		);
	}
}

if(!function_exists('mran_sanitize_accordion_content_type')){
	function mran_sanitize_accordion_content_type($content_type='') {
		$default = 'excerpt';
		$accordion_style = mran_accordion_styles();
		if(isset($accordion_style[$content_type])){
			return $content_type;
		}else{
			return $default;
		}
	}
}

if(!function_exists('mran_tab_templates')){

	function mran_tab_templates() {

		return apply_filters(

			'mran_tab_templates', array(
				'default'       => esc_html__( 'Default', 'master-accordion' ),
				'template-1'    => esc_html__( 'Template 1', 'master-accordion' ),
				'theme-default' => esc_html__( 'Theme default', 'master-accordion' ),
			)

		);

	}
	
}

if(!function_exists('mran_sanitize_tab_templates')){

	function mran_sanitize_tab_templates($template='') {

		$default = 'template-1';
		$tab_templates = mran_tab_templates();
		if(isset($tab_templates[$template])){
			return $template;
		}else{
			return $default;
		}

	}

}


if(!function_exists('mran_tab_styles')){
	function mran_tab_styles() {

		return apply_filters(

			'mran_tab_styles', array(
				'vertical'   => esc_html__( 'Vertical', 'master-accordion' ),
				'horizontal' => esc_html__( 'Horizontal', 'master-accordion' ),
			)
		);
	}
}

if(!function_exists('mran_sanitize_tab_styles')){

	function mran_sanitize_tab_styles($style='') {

		$default = 'vertical';
		$tab_style = mran_tab_styles();
		if(isset($tab_style[$style])){
			return $style;
		}else{
			return $default;
		}

	}

}


if(!function_exists('get_mran_excerpt')){

	function get_mran_excerpt($readmore='default') {
		switch ($readmore) {
			case 'default':
				return get_the_excerpt();
				break;
			case 'button':
				return get_the_excerpt();
				break;
			default:
				return get_the_excerpt();
				break;
		}
		return get_the_excerpt();
	}

}


if(!function_exists('the_mran_excerpt')){

	function the_mran_excerpt($readmore='default') {

		switch ($readmore) {
			case 'default':
				echo get_mran_excerpt();
				break;
			case 'button':
				echo get_mran_excerpt();
				break;
			default:
				echo get_mran_excerpt();
				break;
		}

	}

}