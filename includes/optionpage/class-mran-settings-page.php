<?php
class MRAN_Settings_Page{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct(){

        add_action( 'admin_menu', array( $this, 'mran_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'mran_admin_init' ) );

    }


    /**
     * Add Submenu page
     */
    public function mran_admin_menu(){
        add_submenu_page(
            'edit.php?post_type=master-accordion',
            esc_html__('All Settings', 'master-accordion'),
            esc_html__('All Settings', 'master-accordion'),
            'manage_options',
            'master-settings',
            array( $this, 'mran_add_submenu_page' )
        );
    }

    /**
     * Options page callback
     */
    public function mran_add_submenu_page(){

        // Set class property
        $this->options = get_option( 'mran_all_settings' );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Master Accordion Settings  ', 'master-accordion'); ?></h1>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content" style="position: relative;">
                        <form class="mran-tab-wraper" method="post" action="options.php">
                            <?php
                                $active_tab_type = isset($this->options['active_tab_type']) ? esc_attr($this->options['active_tab_type']) : 'accordion';
                                $list_all_tabs = array(
                                    'accordion'   =>  array(
                                        'id'    => 'mran_settings_accordion',
                                        'label' => esc_html__('Accordion Settings', 'master-accordion'),
                                    ),
                                    'tabs'    =>  array(
                                        'id'    => 'mran_settings_tabs',
                                        'label' => esc_html__('Tabs Settings', 'master-accordion'),
                                    ),
                                );
                            ?>
                            <h5 class="mran-tab-list nav-tab-wrapper">
                                <?php foreach($list_all_tabs as $tab_key=>$tab_details){ ?>
                                    <label for="tab_<?php echo esc_attr($tab_details['id']); ?>" data-id="#<?php echo esc_attr($tab_details['id']); ?>" class="nav-tab <?php echo ($tab_key == $active_tab_type) ? 'nav-tab-active' : ''; ?>"><?php echo sanitize_text_field($tab_details['label']); ?><input id="tab_<?php echo esc_attr($tab_details['id']); ?>" type="radio" name="mran_all_settings[active_tab_type]" value="<?php echo esc_attr($tab_key); ?>" <?php checked($active_tab_type, $tab_key); ?> class="mran-hidden"/></label>
                                <?php } ?>
                                <label for="submit" class="button-primary" style="padding: 5px 10px; height:auto; margin-left: .5em; border:none;">Save Changes</label>
                            </h5>
                            <div class="mran-tab-content-wraper" >
                                <?php settings_fields( 'mran_all_setting_group' ); ?>
                                <?php foreach($list_all_tabs as $tab_key=>$tab_details){ ?>
                                    <div id="<?php echo esc_attr($list_all_tabs[$tab_key]['id']); ?>" class="mran-tab-content <?php echo ($active_tab_type==$tab_key) ? 'mran-content-active' : ''; ?>">
                                        <?php do_settings_sections( 'mran_settings_'.$tab_key.'_tab' ); ?>
                                    </div>
                                <?php } ?>
                                <?php submit_button(); ?>
                            </div>
                        </form>
                    </div><!-- /post-body-content -->
                    <div id="postbox-container-1" class="postbox-container">
                        <h2 class="mran-top-title"><?php esc_html_e('Our Free Plugins', 'master-accordion'); ?></h2>
                        <?php 
                            $themecentury_themes = array(
                                array(
                                    'name'=> esc_html__('Master Accordion', 'master-accordion'),
                                    'theme_url'=> 'https://themecentury.com/downloads/master-accordion-free-wordpress-plugin/',
                                    'demo_url'=> 'https://themecentury.com/downloads/master-accordion-free-wordpress-plugin/',
                                    'docs_url'=> 'https://themecentury.com/downloads/master-accordion-free-wordpress-plugin/',
                                    'forum_url'=> 'https://themecentury.com/forums/forum/master-accordion-free-wordpress-plugin/',
                                    'thumbnail_url'=>'https://ps.w.org/master-accordion/assets/banner-772x250.png?rev=1717682',
                                    'rate_url'=> 'https://wordpress.org/support/plugin/master-accordion/reviews/?filter=5',
                                    'download_url'=> 'https://wordpress.org/plugins/master-accordion/',
                                ),
                                array(
                                    'name'=> esc_html__('Sharing Plus', 'master-accordion'),
                                    'theme_url'=> 'https://themecentury.com/downloads/sharing-plus-free-wordpress-plugin/',
                                    'demo_url'=> 'https://themecentury.com/downloads/sharing-plus-free-wordpress-plugin/',
                                    'docs_url'=> 'https://themecentury.com/downloads/sharing-plus-free-wordpress-plugin/',
                                    'forum_url'=> 'https://themecentury.com/forums/forum/sharing-plus-free-wordpress-plugin/',
                                    'thumbnail_url'=>'https://ps.w.org/sharing-plus/assets/banner-772x250.png?rev=1717682',
                                    'rate_url'=> 'https://wordpress.org/support/plugin/sharing-plus/reviews/?filter=5',
                                    'download_url'=> 'https://wordpress.org/plugins/sharing-plus/',
                                ),
                            );
                            foreach ($themecentury_themes as $single_theme){
                                ?>
                                <div id="submitdiv" class="postbox mran-postbox">
                                    <h2 class="hndle ui-sortable-handle"><span><?php echo esc_attr($single_theme['name']); ?></span></h2>
                                    <div class="inside">
                                        <div class="submitbox">
                                            <div class="mran-minor-publishing">
                                                <a href="<?php echo esc_attr($single_theme['theme_url']); ?>" title="<?php echo esc_attr($single_theme['name']); ?>" target="_blank">
                                                    <img src="<?php echo esc_attr($single_theme['thumbnail_url']); ?>" alt="<?php echo  esc_attr($single_theme['name']); ?>"/>
                                                </a>
                                            </div>
                                            <div class="mran-bottom-actions">
                                                <a href="<?php echo esc_attr($single_theme['demo_url']); ?>" target="_blank" class="btn button-primary"><?php echo esc_html_e('Demo', 'master-accordion'); ?></a>
                                                <a href="<?php echo  esc_attr($single_theme['docs_url']); ?>" target="_blank" class="btn button-primary"><?php echo esc_html_e('Docs', 'master-accordion'); ?></a>
                                                <a href="<?php echo  esc_attr($single_theme['forum_url']); ?>" target="_blank" class="btn button-primary"><?php echo esc_html_e('Support', 'master-accordion'); ?></a>
                                                <a href="<?php echo  esc_attr($single_theme['rate_url']); ?>" target="_blank" class="btn button-primary"><?php echo esc_html_e('Rating', 'master-accordion'); ?></a>
                                                <a href="<?php echo  esc_attr($single_theme['download_url']); ?>" target="_blank" class="btn button-primary"><?php echo esc_html_e('Download', 'master-accordion'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div><!-- /post-body -->
                <br class="clear">
            </div>
        </div>
        <?php

    }

    /**
     * Register and add settings
     */
    public function mran_admin_init()
    {
        register_setting(
            'mran_all_setting_group', // Option group
            'mran_all_settings', // Option name
            array( $this, 'global_settings_sanitize' ) // Sanitize
        );

        add_settings_section(
            'mran_settings_accordion_id', // ID
            esc_html__('Accordion Settings', 'master-accordion'), // Title
            array( $this, 'accordion_section_info' ), // Callback
            'mran_settings_accordion_tab' // Page
        );

        add_settings_field(
            'accordion_publicly_queryable', // ID
            esc_html__('Enable accordion single page?', 'master-accordion'), // Title
            array( $this, 'mran_checkbox_callback' ), // Callback
            'mran_settings_accordion_tab', // Page
            'mran_settings_accordion_id', // Section
            array(
                'name'=>'accordion_publicly_queryable' // Send field name as arguments
            ) //Arguments
        );

        add_settings_section(
            'mran_settings_tab_id', // ID
            esc_html__('Tab Settings', 'master-accordion'), // Title
            array( $this, 'tab_section_info' ), // Callback
            'mran_settings_tabs_tab' // Page
        );

        add_settings_field(
            'tabs_publicly_queryable', // ID
            esc_html__('Enable tabs single page?', 'master-accordion'), // Title
            array( $this, 'mran_checkbox_callback' ), // Callback
            'mran_settings_tabs_tab', // Page
            'mran_settings_tab_id', // Section
            array(
                'name'=>'mran_settings_tab_id' // Send field name as arguments
            ) //Arguments
        );

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function global_settings_sanitize( $settings_input )
    {
        $sanitize_data = array();

        if( isset( $settings_input['active_tab_type'] ) )
            $sanitize_data['active_tab_type'] = sanitize_text_field( $settings_input['active_tab_type'] );

        if( isset( $settings_input['accordion_publicly_queryable'] ) )
            $sanitize_data['accordion_publicly_queryable'] = absint( $settings_input['accordion_publicly_queryable'] );

        if( isset( $settings_input['title'] ) )
            $sanitize_data['title'] = sanitize_text_field( $settings_input['title'] );

        return $sanitize_data;

    }

    /**
     * Print the Section text
     */
    public function tab_section_info(){

        ?>
        <p><?php esc_html_e('You can change tabs settings from here.', 'master-accordion'); ?></p>
        <?php

    }

    /**
     * Print the Section text
     */
    public function accordion_section_info(){
        ?>
        <p><?php esc_html_e('You can change accordion settings from here.', 'master-accordion'); ?></p>
        <?php
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function mran_checkbox_callback($args){
        $setting_name = isset($args['name']) ? esc_attr($args['name']) : '';
        if(!$setting_name){
            ?><p><?php esc_html_e( 'Settings name is not defined', 'master-accordion' ); ?></p><?php
            return;
        }
        $checkbox_value = isset( $this->options[$setting_name] ) ? absint( $this->options[$setting_name]) : 0;
        ?><input type="checkbox" id="<?php echo esc_attr($setting_name); ?>" name="mran_all_settings[<?php echo esc_attr($setting_name); ?>]" value="1" <?php checked($checkbox_value); ?> /><?php
    }

}

if( is_admin() )
    $mran_settings_page = new MRAN_Settings_Page();