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
 * @package     Tab_For_WP
 * @subpackage  Tab_For_WP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package     Tab_For_WP
 * @subpackage  Tab_For_WP/admin
 * @author     Theme Century Team <themecentury@gmail.com>
 */
class MRAN_TAB_For_WP_Post_Type {

	public function __construct() {
		
		add_action( 'init', array( $this, 'register_custom_post_types' ) );
		add_filter( 'manage_edit-tab-category_columns', array( $this, 'mran_tab_shortcode_column' ), 10, 1 );
		add_action( 'manage_tab-category_custom_column', array( $this, 'action_custom_columns_content' ), 10, 3 );
		add_action( 'tab-category_add_form_fields', array( $this, 'mran_tab_group_add_new_meta_field' ), 10, 2 );
		add_action( 'tab-category_edit_form_fields', array( $this, 'mran_tab_group_edit_new_meta_field' ), 10, 2 );
		add_action( 'edited_tab-category', array( $this, 'save_mran_tab_group_custom_meta' ), 10, 2 );
		add_action( 'create_tab-category', array( $this, 'save_mran_tab_group_custom_meta' ), 10, 2 );
		add_action('all_admin_notices' , array($this, 'mran_admin_notices') );

	}

	public function mran_admin_notices(){

		$tab_page = false;

		if(isset($_GET['post_type']) && $_GET['post_type']=='master-tabs'){
			$tab_page = true;
		}

		global $typenow;
		if($typenow=='master-tabs'){
			$tab_page = true;
		}

		if(!$tab_page){
			return;
		}

		$current_screen = get_current_screen();
		$current_id = isset($current_screen->id) ? $current_screen->id : false;

		if(!$current_id){
			return;
		}

		$all_menu_items = array(
			array(
				'id'	=> 'edit-tab-category',
				'label'	=> esc_html__('Tab Category', 'master-accordion'),
				'url' 	=> 'edit-tags.php?taxonomy=tab-category&post_type=master-tabs',
			),
			array(
				'id'	=> 'edit-master-tabs',
				'label'	=> esc_html__('All Tabs', 'master-accordion'),
				'url' 	=> 'edit.php?post_type=master-tabs',
			),
			array(
				'id'	=> 'master-tabs',
				'label'	=> esc_html__('Add New Tab', 'master-accordion'),
				'url' 	=> 'post-new.php?post_type=master-tabs',
			),
		);
		?>
		<div class="acfwp-admin-tab">
		<h5 class="nav-tab-wrapper">
			<?php 
			foreach($all_menu_items as $single_menu){ 
				$menu_url = isset($single_menu['url']) ? $single_menu['url'] : false;
				$menu_label = isset($single_menu['label']) ? $single_menu['label'] : '';
				$menu_active_classs = 'nav-tab-active '; 
				$menu_active_classs = (isset($single_menu['id']) && $current_id==$single_menu['id']) ? ' nav-tab-active ' : '';
				?>
				<a href="<?php echo $menu_url; ?>" class="nav-tab <?php echo esc_attr($menu_active_classs); ?>"><?php echo $menu_label; ?></a>
			<?php } ?>
		</h5>
	</div>
		<?php
	}

	public function save_mran_tab_group_custom_meta( $term_id ) {


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

	public function mran_tab_group_add_new_meta_field() {
		?>

		<div class="form-field">
			<label for="term_meta[mran_term_template]"><?php esc_html_e( ' Tab template', 'master-accordion' ); ?></label>
			<select style="width:94%" name="term_meta[mran_term_template]" id="term_meta[mran_term_template]">
				<?php
				foreach ( mran_tab_templates() as $template_index => $template_value ) {

					?>
					<option value="<?php echo $template_index ?>"><?php echo $template_value; ?></option>
					<?php
				}
				?>
			</select>
			<p class="description"><?php esc_html_e( 'Select template for accordion', 'master-accordion' ); ?></p>
		</div>
		<div class="form-field">
			<label for="term_meta[acwp_term_style]"><?php esc_html_e( ' Tab style', 'master-accordion' ); ?></label>
			<select style="width:94%" name="term_meta[acwp_term_style]" id="term_meta[acwp_term_style]">
				<?php
				foreach ( mran_tab_styles() as $template_index => $template_value ){
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

	public function mran_tab_group_edit_new_meta_field( $term ) {
		// put the term ID into a variable
		$t_id = $term->term_id;
		// retrieve the existing value(s) for this meta field. This returns an array
		$mran_term_template1 = get_term_meta( $t_id );

		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label
					for="term_meta[mran_term_template]"><?php esc_html_e( ' Tab template', 'master-accordion' ); ?></label>
			</th>
			<td>
				<select style="width:94%" name="term_meta[mran_term_template]" id="term_meta[mran_term_template]">
					<?php
					$mran_term_template = get_term_meta( $t_id, 'mran_term_template', true );

					foreach ( mran_tab_templates() as $template_index => $template_value ) {
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
					for="term_meta[acwp_term_style]"><?php esc_html_e( ' Tab style', 'master-accordion' ); ?></label>
			</th>
			<td>
				<select style="width:94%" name="term_meta[acwp_term_style]" id="term_meta[acwp_term_style]">
					<?php
					$acwp_term_style = get_term_meta( $t_id, 'acwp_term_style', true );

					foreach ( mran_tab_styles() as $template_index => $template_value ) {
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
				return '<span onclick="">[mran_category_tab id="' . $taxonomy_id . '"]</span>';
				break;

		}
	}

	/**
	 * @param $columns
	 *
	 * @return array
	 */
	function mran_tab_shortcode_column( $columns ) {

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

		$tabs_publicly_queryable = isset($mran_all_settings['tabs_publicly_queryable']) ? absint($mran_all_settings['tabs_publicly_queryable']) : false;

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( ' Tab Category', 'taxonomy general name', 'master-accordion' ),
			'singular_name'     => _x( ' Tab Category', 'taxonomy singular name', 'master-accordion' ),
			'search_items'      => esc_html__( 'Search Tab category', 'master-accordion' ),
			'all_items'         => esc_html__( 'All  Tab Categories', 'master-accordion' ),
			'parent_item'       => esc_html__( 'Parent  Tab Category', 'master-accordion' ),
			'parent_item_colon' => esc_html__( 'Parent  Tab Category:', 'master-accordion' ),
			'edit_item'         => esc_html__( 'Edit  Tab Category', 'master-accordion' ),
			'update_item'       => esc_html__( 'Update  Tab Category', 'master-accordion' ),
			'add_new_item'      => esc_html__( 'Add New  Tab Category', 'master-accordion' ),
			'new_item_name'     => esc_html__( 'New  Tab Category Name', 'master-accordion' ),
			'menu_name'         => esc_html__( ' Tab Category', 'master-accordion' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'accordion-category' ),
		);

		register_taxonomy( 'tab-category', array( 'master-tabs' ), $args );

		$labels = array(
			'name'               => _x( ' Tab', 'post type general name', 'master-accordion' ),
			'singular_name'      => _x( ' Tab', 'post type singular name', 'master-accordion' ),
			'menu_name'          => _x( ' Tabs', 'admin menu', 'master-accordion' ),
			'name_admin_bar'     => _x( ' Tab', 'add new on admin bar', 'master-accordion' ),
			'add_new'            => _x( 'Add New', 'book', 'master-accordion' ),
			'add_new_item'       => esc_html__( 'Add New  Tab', 'master-accordion' ),
			'new_item'           => esc_html__( 'New  Tab', 'master-accordion' ),
			'edit_item'          => esc_html__( 'Edit  Tab', 'master-accordion' ),
			'view_item'          => esc_html__( 'View  Tab', 'master-accordion' ),
			'all_items'          => esc_html__( 'All  Tabs', 'master-accordion' ),
			'search_items'       => esc_html__( 'Search  Tabs', 'master-accordion' ),
			'parent_item_colon'  => esc_html__( 'Parent  Tabs:', 'master-accordion' ),
			'not_found'          => esc_html__( 'No tabs found.', 'master-accordion' ),
			'not_found_in_trash' => esc_html__( 'No tabs found in Trash.', 'master-accordion' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Description.', 'master-accordion' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'master-tabs' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'           => 'dashicons-index-card',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'show_in_menu' => 'edit.php?post_type=master-accordion'
		);

		register_post_type( 'master-tabs', $args );
	}

}

new MRAN_TAB_For_WP_Post_Type();
