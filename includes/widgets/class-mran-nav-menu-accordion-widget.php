<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget API: WP_Nav_Menu_Widget class
 *
 * @package WordPress
 * @subpackage Widgets
 * @since 1.0.0
 */
if(!class_exists('MRAN_Nav_Menu_Accordion_Widget')):
class MRAN_Nav_Menu_Accordion_Widget extends WP_Widget {

	/**
	 * Sets up a new Custom Menu widget instance.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'mran_accordion_nav_menu',
			'description'                 => esc_html__( 'Add a custom accordion menu to your sidebar.', 'master-accordion' ),
			'customize_selective_refresh' => true,
		);
		$control_ops = array( 'width' => 350, 'height' => 350 );
		parent::__construct( 'mran_accordion_nav_menu', esc_html__( 'Accordion Menu', 'master-accordion' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the content for the current Custom Menu widget instance.
	 *
	 * @access public
	 *
	 * @param array $args Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Custom Menu widget instance.
	 */
	public function widget( $args, $instance ) {

		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		$templates      	= empty( $instance['templates'] ) ? 'default' : esc_attr($instance['templates']);
		$style          	= empty( $instance['style'] ) ? 'vertical' : esc_attr($instance['style']);

		$dropdown_icon		= isset($instance['dropdown_icon']) ? esc_attr( $instance['dropdown_icon'] ) : 'fa-toggle-off';
		$active_dp_icon		= isset($instance['active_dp_icon']) ? esc_attr( $instance['active_dp_icon'] ) : 'fa-toggle-on';
		$title_color		= isset($instance['title_color']) ? sanitize_hex_color( $instance['title_color'] ) : '';
		$title_background	= isset($instance['title_background']) ? sanitize_hex_color( $instance['title_background'] ) : '';
		$content_color		= isset($instance['content_color']) ? sanitize_hex_color( $instance['content_color'] ) : '';
		$content_background	= isset($instance['content_background']) ? sanitize_hex_color( $instance['content_background'] ) : '';

		if ( ! $nav_menu ) {
			return;
		}

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$nav_menu_args = array(
			'fallback_cb' => '',
			'menu'        => $nav_menu
		);

		/**
		 * Filters the arguments for the Custom Menu widget.
		 *
		 * @param array $nav_menu_args {
		 *     An array of arguments passed to wp_nav_menu() to retrieve a custom menu.
		 *
		 * @type callable|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
		 * @type mixed $menu Menu ID, slug, or name.
		 * }
		 *
		 * @param WP_Term $nav_menu Nav menu object for the current menu.
		 * @param array $args Display arguments for the current widget.
		 * @param array $instance Array of settings for the current widget.
		 */
		$menu_args = apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance );
			$mran_navigation_id = 'mran_accordion_nav_menu'.$this->number;
			echo '<div class="mran-menu-toggle-wraper" style="display:none;">';
			echo '<i class="mran-toggle-icon fa '.$dropdown_icon.'" data-dropdown-icon="'.$dropdown_icon.'" data-active-dp-icon="'.$active_dp_icon.'" ></i>';
			echo '</div>';
			echo '<div id="'.$mran_navigation_id.'" class="mran-accordion-template mran-widget mran-' . $templates . '">';
			$menu_args['container_class'] = 'mran-accordion ' . $style;
			$menu_args['menu_class']      = 'mran-accordion-list';
			wp_nav_menu( $menu_args );
			echo '</div>';

			?>
			<style type="text/css">
				.mran_accordion_nav_menu #<?php echo $mran_navigation_id; ?> .menu-item a{
					color:<?php echo $title_color; ?>;
					background:<?php echo $title_background; ?>;
				}
				.mran_accordion_nav_menu #<?php echo $mran_navigation_id; ?> .mran-toggle-icon{
					color:<?php echo $title_color; ?>;
				}
			</style>
			<?php

		echo $args['after_widget'];

	}

	/**
	 * Handles updating settings for the current Custom Menu widget instance.
	 *
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 *
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['title']     = isset($new_instance['title']) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['nav_menu']     = isset($new_instance['nav_menu']) ? (int) $new_instance['nav_menu'] : '';

		$instance['templates']     = isset($new_instance['templates']) ? esc_attr( $new_instance['templates'] ) : '';
		$instance['style']     = isset($new_instance['style']) ? esc_attr( $new_instance['style'] ) : '';

		$instance['dropdown_icon']     = isset($new_instance['dropdown_icon']) ? esc_attr( $new_instance['dropdown_icon'] ) : '';
		$instance['active_dp_icon']     = isset($new_instance['active_dp_icon']) ? esc_attr( $new_instance['active_dp_icon'] ) : '';
		$instance['title_color']     = isset($new_instance['title_color']) ? esc_attr( $new_instance['title_color'] ) : '';
		$instance['title_background']     = isset($new_instance['title_background']) ? esc_attr( $new_instance['title_background'] ) : '';

		$instance['active_tab_type']     = isset($new_instance['active_tab_type']) ? esc_attr( $new_instance['active_tab_type'] ) : '';

		return $instance;

	}

	/**
	 * Outputs the settings form for the Custom Menu widget.
	 *
	 * @access public
	 *
	 * @param array $instance Current settings.
	 *
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function form( $instance ) {

		global $wp_customize;

		$title    = isset( $instance['title'] ) ? sanitize_text_field($instance['title']) : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? (int) $instance['nav_menu'] : '';

		$templates      = isset( $instance['templates'] ) ? esc_attr($instance['templates']) : 'default';
		$style          = isset( $instance['style'] ) ? esc_attr($instance['style']) : 'vertical';

		$dropdown_icon	= empty( $instance['dropdown_icon'] ) ? 'fa-toggle-off' : esc_attr( $instance['dropdown_icon'] );
		$active_dp_icon	= empty( $instance['active_dp_icon'] ) ? 'fa-toggle-on' : esc_attr( $instance['active_dp_icon'] );
		$title_color	= isset($instance['title_color']) ? esc_attr( $instance['title_color'] ) : '';
		$title_background = isset($instance['title_background']) ? esc_attr( $instance['title_background'] ) : '';

		$active_tab_type = isset($instance['active_tab_type']) ? esc_attr( $instance['active_tab_type'] ) : 'general';

		// Get menus
		$menus = wp_get_nav_menus();	
		$list_all_tabs = array(
			'general'	=>	array(
				'id'	=> 'mran_accordion_nav_menu_general'.esc_attr($this->number),
				'label'	=> esc_html__('General', 'master-accordion'),
			),
			'layout'	=>	array(
				'id'	=> 'mran_accordion_nav_menu_layout'.esc_attr($this->number),
				'label'	=> esc_html__('Layout', 'master-accordion'),
			),
			'design'	=>	array(
				'id'	=> 'mran_accordion_nav_menu_design'.esc_attr($this->number),
				'label'	=> esc_html__('Design', 'master-accordion'),
			),
		);

		// If no menus exists, direct the user to go and create some.
		?>
		<div class="mran-tab-wraper">
			<h5 class="mran-tab-list nav-tab-wrapper">
				<?php foreach($list_all_tabs as $tab_key=>$tab_details){ ?>
					<label for="tab_<?php echo esc_attr($tab_details['id']); ?>" data-id="#<?php echo esc_attr($tab_details['id']); ?>" class="nav-tab <?php echo ($tab_key == $active_tab_type) ? 'nav-tab-active' : ''; ?>"><?php echo sanitize_text_field($tab_details['label']); ?><input id="tab_<?php echo esc_attr($tab_details['id']); ?>" type="radio" name="<?php echo $this->get_field_name("active_tab_type"); ?>" value="<?php echo esc_attr($tab_key); ?>" <?php checked($active_tab_type, $tab_key); ?> class="mran-hidden"/></label>
				<?php } ?>
			</h5>
			<div class="mran-tab-content-wraper">
				<div id="<?php echo esc_attr($list_all_tabs['general']['id']); ?>" class="mran-tab-content <?php echo ($active_tab_type=='general') ? 'mran-content-active' : ''; ?>">
					<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) {
						echo ' style="display:none" ';
					} ?>>
						<?php
						if ( $wp_customize instanceof WP_Customize_Manager ) {
							$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
						} else {
							$url = admin_url( 'nav-menus.php' );
						}
						?>
						<?php echo sprintf( __( 'No menus have been created yet. <a href="%s">Create some</a>.', 'master-accordion' ), esc_attr( $url ) ); ?>
					</p>
					<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) {
						echo ' style="display:none" ';
					} ?>>
						<p>
							<label
								for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'master-accordion' ) ?></label>
							<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
							       name="<?php echo $this->get_field_name( 'title' ); ?>"
							       value="<?php echo esc_attr( $title ); ?>"/>
						</p>
						<p>
							<label
								for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php esc_html_e( 'Select Menu:', 'master-accordion' ); ?></label>
							<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>"
							        name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
								<option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'master-accordion' ); ?></option>
								<?php foreach ( $menus as $menu ) : ?>
									<option
										value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $nav_menu, $menu->term_id ); ?>>
										<?php echo esc_html( $menu->name ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</p>

						
					</div>
				</div>
				<div id="<?php echo esc_attr($list_all_tabs['layout']['id']); ?>" class="mran-tab-content <?php echo ($active_tab_type=='layout') ? 'mran-content-active' : ''; ?>">
					<?php
						$all_templates = mran_accordion_templates();
						?>
						<p>
							<label
								for="<?php echo $this->get_field_id( 'templates' ); ?>"><?php esc_html_e( 'Template:', 'master-accordion' ); ?></label>
							<select class="widefat" id="<?php echo $this->get_field_id( 'templates' ); ?>"
							        name="<?php echo $this->get_field_name( 'templates' ); ?>">
								<?php foreach ( $all_templates as $template_key => $template_value ): ?>
									<option <?php selected( $templates, $template_key, true ); ?>
										value="<?php echo $template_key; ?>"><?php echo $template_value; ?></option>
								<?php endforeach; ?>
							</select>
						</p>
						<?php
						//$all_style = mran_accordion_styles();
						$all_style=array('vertical'   => esc_html__( 'Vertical', 'master-accordion' ));
						?>
						<p>
							<label
								for="<?php echo $this->get_field_id( 'style' ); ?>"><?php esc_html_e( 'Style:', 'master-accordion' ); ?></label>
							<select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>"
							        name="<?php echo $this->get_field_name( 'style' ); ?>">
								<?php foreach ( $all_style as $style_key => $style_value ): ?>
									<option <?php selected( $style, $style_key, true ); ?>
										value="<?php echo $style_key; ?>"><?php echo $style_value; ?></option>
								<?php endforeach; ?>
							</select>
						</p>
						<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
							<p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) {
								echo 'display: none;';
							} ?>">
								<button type="button" class="button"><?php esc_html_e( 'Edit Menu', 'master-accordion' ) ?></button>
							</p>
						<?php endif; ?>
				</div>
				<div id="<?php echo esc_attr($list_all_tabs['design']['id']); ?>" class="mran-tab-content <?php echo ($active_tab_type=='design') ? 'mran-content-active' : ''; ?> " >
					<div class="mran_widget_field">
						<label for="<?php echo $this->get_field_id( 'dropdown_icon' ); ?>"><?php esc_html_e( 'Dropdown Icon:', 'master-accordion' ); ?></label>
						<div class="mran_widget_icon_wrap">
							<input class="widefat mran_icon_picker" id="<?php echo $this->get_field_id( 'dropdown_icon' ); ?>"
							   name="<?php echo $this->get_field_name( 'dropdown_icon' ); ?>" type="text"
							   value="<?php echo esc_attr( $dropdown_icon ); ?>"/>
							<label class="mran_icon fa <?php echo esc_attr( $dropdown_icon ); ?>" for="<?php echo $this->get_field_id( 'dropdown_icon' ); ?>"></label>
						</div>
					</div>
					<div class="mran_widget_field">
						<label for="<?php echo $this->get_field_id( 'active_dp_icon' ); ?>"><?php esc_html_e( 'Active Dropdown Icon:', 'master-accordion' ); ?></label>
						<div class="mran_widget_icon_wrap">
							<input class="widefat mran_icon_picker" id="<?php echo $this->get_field_id( 'active_dp_icon' ); ?>"
							   name="<?php echo $this->get_field_name( 'active_dp_icon' ); ?>" type="text"
							   value="<?php echo esc_attr( $active_dp_icon ); ?>"/>
							<label class="mran_icon fa <?php echo esc_attr( $active_dp_icon ); ?>" for="<?php echo $this->get_field_id( 'active_dp_icon' ); ?>"></label>
						</div>
					</div>
					<div class="mran_widget_field">
						<label for="<?php echo $this->get_field_id( 'title_color' ); ?>"><?php esc_html_e( 'Title Color:', 'master-accordion' ); ?></label>
						<input class="mran_color_picker" id="<?php echo $this->get_field_id( 'title_color' ); ?>" name="<?php echo $this->get_field_name( 'title_color' ); ?>" type="text"
							   value="<?php echo esc_attr( $title_color ); ?>"/>
					</div>
					<div class="mran_widget_field">
						<label for="<?php echo $this->get_field_id( 'title_background' ); ?>"><?php esc_html_e( 'Title Background:', 'master-accordion' ); ?></label>
						<input class="mran_color_picker" id="<?php echo $this->get_field_id( 'title_background' ); ?>"
							   name="<?php echo $this->get_field_name( 'title_background' ); ?>" type="text"
							   value="<?php echo esc_attr( $title_background ); ?>"/>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
endif;
register_widget( 'MRAN_Nav_Menu_Accordion_Widget' );