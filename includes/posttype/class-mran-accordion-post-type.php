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
class Master_Accordion_Post_Type {

	public function __construct() {

		add_action( 'init', array( $this, 'register_custom_post_types' ) );
		add_filter( 'manage_edit-accordion-category_columns', array( $this, 'accordion_shortcode_column' ), 10, 1 );
		add_action( 'manage_accordion-category_custom_column', array( $this, 'action_custom_columns_content' ), 10, 3 );
		add_action( 'accordion-category_add_form_fields', array( $this, 'accordion_group_add_new_meta_field' ), 10, 2 );
		add_action( 'accordion-category_edit_form_fields', array( $this, 'accordion_group_edit_new_meta_field' ), 10, 2 );
		add_action( 'edited_accordion-category', array( $this, 'save_accordion_group_custom_meta' ), 10, 2 );
		add_action( 'create_accordion-category', array( $this, 'save_accordion_group_custom_meta' ), 10, 2 );

	}

	public function save_accordion_group_custom_meta( $term_id ) {


		if ( isset( $_POST['term_meta'] ) ) {

			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][ $key ] ) ) {

					$term_meta_value = sanitize_text_field( $_POST['term_meta'][ $key ] );
					update_term_meta( $term_id, $key, $term_meta_value );

				}
			}

			// Save the option array.

		}
	}

	public function accordion_group_add_new_meta_field() {
		?>

		<div class="form-field">
			<label for="term_meta[mran_term_template]"><?php esc_html_e( 'Accordion template', 'master-accordion' ); ?></label>
			<select style="width:94%" name="term_meta[mran_term_template]" id="term_meta[mran_term_template]">
				<?php
				foreach ( mran_accordion_templates() as $template_index => $template_value ) {

					?>
					<option value="<?php echo $template_index ?>"><?php echo $template_value; ?></option>
					<?php

				}

				?>
			</select>
			<p class="description"><?php esc_html_e( 'Select template for accordion', 'master-accordion' ); ?></p>
		</div>
		<div class="form-field">
			<label for="term_meta[acwp_term_style]"><?php esc_html_e( 'Accordion style', 'master-accordion' ); ?></label>
			<select style="width:94%" name="term_meta[acwp_term_style]" id="term_meta[acwp_term_style]">
				<?php
				foreach ( mran_accordion_styles() as $template_index => $template_value ) {
					?>
					<option value="<?php echo $template_index ?>"><?php echo $template_value; ?></option>
					<?php
				}

				?>
			</select>
			<p class="description"><?php esc_html_e( 'Select style for accordion', 'master-accordion' ); ?></p>
		</div>
		<?php


	}

	public function accordion_group_edit_new_meta_field( $term ) {
		// put the term ID into a variable
		$t_id = $term->term_id;
		// retrieve the existing value(s) for this meta field. This returns an array
		$mran_term_template1 = get_term_meta( $t_id );

		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label
					for="term_meta[mran_term_template]"><?php esc_html_e( 'Accordion template', 'master-accordion' ); ?></label>
			</th>
			<td>
				<select style="width:94%" name="term_meta[mran_term_template]" id="term_meta[mran_term_template]">
					<?php
					$mran_term_template = get_term_meta( $t_id, 'mran_term_template', true );

					foreach ( mran_accordion_templates() as $template_index => $template_value ) {
						?>
						<option value="<?php echo $template_index ?>"

							<?php echo $mran_term_template === $template_index ? 'selected= "selected"' : '' ?>
						><?php echo $template_value; ?></option>
						<?php

					}

					?>
				</select>
				<p class="description"><?php esc_html_e( 'Select template for accordion', 'master-accordion' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label
					for="term_meta[acwp_term_style]"><?php esc_html_e( 'Accordion style', 'master-accordion' ); ?></label>
			</th>
			<td>
				<select style="width:94%" name="term_meta[acwp_term_style]" id="term_meta[acwp_term_style]">
					<?php
					$acwp_term_style = get_term_meta( $t_id, 'acwp_term_style', true );

					foreach ( mran_accordion_styles() as $template_index => $template_value ) {
						?>
						<option value="<?php echo $template_index ?>"
							<?php echo $acwp_term_style === $template_index ? 'selected= "selected"' : '' ?>
						><?php echo $template_value; ?></option>
						<?php

					}

					?>
				</select>
				<p class="description"><?php esc_html_e( 'Select style for accordion', 'master-accordion' ); ?></p>
			</td>
		</tr>

		<?php


	}

	/**
	 * @param $column_id
	 * @param $post_id
	 *
	 * @return string
	 */
	function action_custom_columns_content( $content, $column_id, $taxonomy_id ) {

		//run a switch statement for all of the custom columns created
		switch ( $column_id ) {
			case 'accordion_shortcode':
				return '<span onclick="">[mran_category_accordion id="' . $taxonomy_id . '"]</span>';
				break;

		}
	}

	/**
	 * @param $columns
	 *
	 * @return array
	 */
	function accordion_shortcode_column( $columns ) {

		$key    = 'description';
		$offset = array_search( $key, array_keys( $columns ) );

		$result = array_merge(
			array_slice( $columns, 0, $offset ),
			array( 'accordion_shortcode' => esc_html__( 'Shortcode', 'master-accordion' ) ),
			array_slice( $columns, $offset, null )
		);

		return $result;
	}

	public function register_custom_post_types() {

		$mran_all_settings = get_option( 'mran_all_settings', array() );

		$accordion_publicly_queryable = isset($mran_all_settings['accordion_publicly_queryable']) ? absint($mran_all_settings['accordion_publicly_queryable']) : false;

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Accordion Category', 'taxonomy general name', 'master-accordion' ),
			'singular_name'     => _x( 'Accordion Category', 'taxonomy singular name', 'master-accordion' ),
			'search_items'      => esc_html__( 'Search Accordion Category', 'master-accordion' ),
			'all_items'         => esc_html__( 'All Accordion Categories', 'master-accordion' ),
			'parent_item'       => esc_html__( 'Parent Accordion Category', 'master-accordion' ),
			'parent_item_colon' => esc_html__( 'Parent Accordion Category:', 'master-accordion' ),
			'edit_item'         => esc_html__( 'Edit Accordion Category', 'master-accordion' ),
			'update_item'       => esc_html__( 'Update Accordion Category', 'master-accordion' ),
			'add_new_item'      => esc_html__( 'Add New Accordion Category', 'master-accordion' ),
			'new_item_name'     => esc_html__( 'New Accordion Category Name', 'master-accordion' ),
			'menu_name'         => esc_html__( 'Accordion Category', 'master-accordion' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'accordion-category' ),
		);

		register_taxonomy( 'accordion-category', array( 'master-accordion' ), $args );

		$labels = array(
			'name'               => _x( 'Accordion', 'post type general name', 'master-accordion' ),
			'singular_name'      => _x( 'Accordion', 'post type singular name', 'master-accordion' ),
			'menu_name'          => _x( 'Accordions', 'admin menu', 'master-accordion' ),
			'name_admin_bar'     => _x( 'Accordion', 'add new on admin bar', 'master-accordion' ),
			'add_new'            => _x( 'Add New', 'book', 'master-accordion' ),
			'add_new_item'       => esc_html__( 'Add New Accordion', 'master-accordion' ),
			'new_item'           => esc_html__( 'New Accordion', 'master-accordion' ),
			'edit_item'          => esc_html__( 'Edit Accordion', 'master-accordion' ),
			'view_item'          => esc_html__( 'View Accordion', 'master-accordion' ),
			'all_items'          => esc_html__( 'All Accordions', 'master-accordion' ),
			'search_items'       => esc_html__( 'Search Accordions', 'master-accordion' ),
			'parent_item_colon'  => esc_html__( 'Parent Accordions:', 'master-accordion' ),
			'not_found'          => esc_html__( 'No accordions found.', 'master-accordion' ),
			'not_found_in_trash' => esc_html__( 'No accordions found in Trash.', 'master-accordion' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'master-accordion' ),
			'public'             => true,
			'publicly_queryable' => $accordion_publicly_queryable,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'master-accordion' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'           => 'dashicons-index-card',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'master-accordion', $args );
	}

}

new Master_Accordion_Post_Type();
