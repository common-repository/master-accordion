<?php
class MRAN_Shortcode_Generator{

    /**
     * Start up
     */
    public function __construct(){

        add_action( 'admin_menu', array( $this, 'mran_admin_menu' ) );

        /*if ((current_user_can('edit_posts') || current_user_can('edit_pages')) && get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array($this, 'add_tinymce_plugin'));
            add_filter('mce_buttons', array($this, 'register_buttons'));
        }*/

    }

    public function register_buttons( $buttons ) {
       array_push( $buttons, 'separator', 'myplugins' );
       return $buttons;
    }

    public function add_tinymce_plugin($context){
        global $pagenow;

        if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) ) {
            $context .= '<button id="insert-accordion-button" type="button" data-editor="content" class="button insert-accordion add_accordion">';
            $context .= '<span class="wp-media-buttons-icon dashicons-before dashicons-index-card">&nbsp;</span>';
            $context .= esc_html__('Add Accordion', 'master-accordion');
            $context .= '</button>';
        }

        return $context;
    }

    /**
     * Add Submenu page
     */
    public function mran_admin_menu(){
        add_submenu_page(
            'edit.php?post_type=master-accordion',
            esc_html__('Accordion WordPress Shortcode Generator', 'master-accordion'),
            esc_html__('Shortcode Generator', 'master-accordion'),
            'manage_options',
            'mran-shortcode-generator',
            array( $this, 'mran_add_submenu_page' )
        );
    }

    /**
     * Options page callback
     */
    public function mran_add_submenu_page(){

        // Set class property
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Generate accordion shortcode from here.', 'master-accordion'); ?></h1>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="mran_generater_wraper">
                            <p><?php esc_html_e('Change your settings from here and generate shortcode than just copy this shortcode and paste it to text editor.', 'master-accordion'); ?></p>
                            <code class="mran_generated_shortcode">[mran_accordion post_type="post" posts_per_page="5" order="date" perm="readable" offset="0" mran_active_item="1" mran_template="default" mran_style="vertical" mran_dropdown_icon="fa-desktop" mran_active_dp_icon="fa-desktop"]</code>
                            <form method="post" id="mran_shortcode_generator_form" class="mran-tab-wraper">
                                <?php
                                
                                $active_tab_type = 'general';
                                
                                $post_types = get_post_types( array('public' => true ) );
                                $mran_tab_or_accordion = array(
                                    'mran_tab'=>esc_html__('Tabs', 'master-accordion'),
                                    'mran_accordion'=>esc_html__('Accordion', 'master-accordion'),
                                );
                                $mran_order = array(
                                    'DESC' => esc_html__('Descending', 'master-accordion'),
                                    'ASC' => esc_html__('Ascending', 'master-accordion'),
                                );
                                $mran_order_by = array(
                                    'date' => esc_html__('Date', 'master-accordion'),
                                    'title' => esc_html__('Title', 'master-accordion'),
                                    'ID' => esc_html__('ID', 'master-accordion'),
                                    'author' => esc_html__('Author', 'master-accordion'),
                                    'modified' => esc_html__('Modified', 'master-accordion'),
                                    'rand' => esc_html__('Random', 'master-accordion'),
                                    'comment_count' => esc_html__('Comment Count', 'master-accordion'),
                                );
                                $mran_permission = array(
                                    'readable' => esc_html__('Readable', 'master-accordion'),
                                    'editable' => esc_html__('Editable', 'master-accordion'),
                                );
                                $mran_content_type = array(
                                    'excerpt'   => esc_html__('Short Description', 'master-accordion'),
                                    'content'   => esc_html__('Full Content', 'master-accordion'),
                                );

                                $mran_template = mran_accordion_templates();
                                $mran_style = mran_accordion_styles();

                                $list_all_tabs = array(
                                    'general'   =>  array(
                                        'id'    => 'mran_settings_general',
                                        'label' => esc_html__('General', 'master-accordion'),
                                        'fields'=> array(
                                            array(
                                                'type'      => 'select',
                                                'default'   => 'mran_accordion',
                                                'label'     => esc_html__('Type', 'master-accordion'),
                                                'id'        => 'mran_tab_or_accordion',
                                                'default'   => 'mran_accordion',
                                                'choices'   => $mran_tab_or_accordion,
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'default'   => 'post',
                                                'label'     => esc_html__('Post Type', 'master-accordion'),
                                                'id'        => 'post_type',
                                                'choices'   => $post_types,
                                            ),
                                            array(
                                                'type'      => 'number',
                                                'default'   => '5',
                                                'label'     => esc_html__('Posts per page', 'master-accordion'),
                                                'id'        => 'posts_per_page',
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'default'   => 'DESC',
                                                'label'     => esc_html__('Order', 'master-accordion'),
                                                'id'        => 'order',
                                                'choices'   => $mran_order,
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'default'   => 'date',
                                                'label'     => esc_html__('Order By', 'master-accordion'),
                                                'id'        => 'order',
                                                'choices'   => $mran_order_by,
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'default'   => 'readable',
                                                'label'     => esc_html__('Permission', 'master-accordion'),
                                                'id'        => 'perm',
                                                'choices'   => $mran_permission,
                                            ),
                                            array(
                                                'type'      => 'number',
                                                'default'   => '0',
                                                'label'     => esc_html__('Offset', 'master-accordion'),
                                                'id'        => 'offset',
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'default'   => '0',
                                                'label'     => esc_html__('Content Type:', 'master-accordion'),
                                                'id'        => 'mran_content_type',
                                                'choices'   => $mran_content_type,
                                            ),
                                        ),
                                    ),
                                    'layout'    =>  array(
                                        'id'    => 'mran_settings_layout',
                                        'label' => esc_html__('Layout', 'master-accordion'),
                                        'fields'=> array(
                                            array(
                                                'type'      => 'number',
                                                'label'     => esc_html__('Active Item', 'master-accordion'),
                                                'id'        => 'mran_active_item',
                                                'default'   => '1',
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'label'     => esc_html__('Template', 'master-accordion'),
                                                'id'        => 'mran_template',
                                                'default'   => '',
                                                'choices'   => $mran_template,
                                            ),
                                            array(
                                                'type'      => 'select',
                                                'label'     => esc_html__('Style', 'master-accordion'),
                                                'id'        => 'mran_style',
                                                'default'   => '',
                                                'choices'   => $mran_style,
                                            ),
                                        ),
                                    ),
                                    'design'    =>  array(
                                        'id'    => 'mran_settings_design',
                                        'label' => esc_html__('Design', 'master-accordion'),
                                        'fields'=> array(
                                            array(
                                                'type'      => 'icon',
                                                'label'     => esc_html__('Title Icon', 'master-accordion'),
                                                'id'        => 'mran_dropdown_icon',
                                                'default'   => 'fa-desktop',
                                            ),
                                            array(
                                                'type'      => 'icon',
                                                'label'     => esc_html__('Active Title Icon', 'master-accordion'),
                                                'id'        => 'mran_active_dp_icon',
                                                'default'   => 'fa-desktop',
                                            ),
                                            array(
                                                'type'      => 'color',
                                                'label'     => esc_html__('Title Color', 'master-accordion'),
                                                'id'        => 'mran_title_color',
                                                'default'   => '',
                                            ),
                                            array(
                                                'type'      => 'color',
                                                'label'     => esc_html__('Title Background', 'master-accordion'),
                                                'id'        => 'mran_title_background',
                                                'default'   => '',
                                            ),
                                            array(
                                                'type'      => 'color',
                                                'label'     => esc_html__('Title Content Color', 'master-accordion'),
                                                'id'        => 'mran_content_color',
                                                'default'   => '',
                                            ),
                                            array(
                                                'type'      => 'color',
                                                'label'     => esc_html__('Content Background', 'master-accordion'),
                                                'id'        => 'mran_content_background',
                                                'default'   => '',
                                            ),
                                        ),
                                    ),
                                );
                            ?>
                            <h5 class="mran-tab-list nav-tab-wrapper">
                                <?php foreach($list_all_tabs as $tab_key=>$tab_details){ ?>
                                    <label for="tab_<?php echo esc_attr($tab_details['id']); ?>" data-id="#<?php echo esc_attr($tab_details['id']); ?>" class="nav-tab <?php echo ($tab_key == $active_tab_type) ? 'nav-tab-active' : ''; ?>"><?php echo sanitize_text_field($tab_details['label']); ?></label>
                                <?php } ?>
                                <label for="mran_generate_button" class="button-primary" style="padding: 5px 10px; height:auto; margin-left: .5em; border:none;"><?php esc_html_e('Generate Shortcode', 'master-accordion'); ?></label>
                            </h5>
                            <div class="mran-tab-content-wraper">
                                <?php foreach($list_all_tabs as $tab_key=>$tab_details){ ?>
                                    <div id="<?php echo esc_attr($list_all_tabs[$tab_key]['id']); ?>" class="mran-tab-content <?php echo ($active_tab_type==$tab_key) ? 'mran-content-active' : ''; ?>">
                                        <table class="form-table">
                                            <?php 
                                            $tabs_fields = $tab_details['fields']; 
                                            foreach($tabs_fields as $fields_details){
                                                $field_type = $fields_details['type'];
                                                $field_id = $fields_details['id'];
                                                $field_label = $fields_details['label'];
                                                $field_default = $fields_details['default'];
                                                ?>
                                                    <tr class="mran-field-container">
                                                        <th>
                                                            <label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_attr($field_label); ?></label>
                                                        </th>
                                                        <td>
                                                            <?php 
                                                                switch ($field_type){
                                                                    case 'select':
                                                                        $field_choices = $fields_details['choices'];
                                                                        ?>
                                                                            <select name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" class="widefat">
                                                                                <?php 
                                                                                     foreach($field_choices as $field_value=>$field_label){
                                                                                        ?>
                                                                                            <option <?php selected($field_default, $field_value); ?> value="<?php echo $field_value; ?>"><?php echo $field_label; ?></option>
                                                                                        <?php
                                                                                     }

                                                                                ?>
                                                                            </select>
                                                                        <?php
                                                                        break;
                                                                    
                                                                    case 'number':
                                                                        ?>
                                                                        <input name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" class="widefat" type="number" value="<?php echo $field_default; ?>"/>
                                                                        <?php
                                                                        break;
                                                                    case 'icon':
                                                                        ?>
                                                                        <input name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" class="widefat mran_icon_picker" type="text" value="<?php echo $field_default; ?>"/>
                                                                        <?php
                                                                        break;
                                                                     case 'color':
                                                                        ?>
                                                                        <input name="<?php echo esc_attr($field_id); ?>" id="<?php echo esc_attr($field_id); ?>" class="widefat mran_color_picker" type="text" value="<?php echo $field_default; ?>"/>
                                                                        <?php
                                                                        break;

                                                                    default:
                                                                        ?>
                                                                        <p><?php esc_html_e("There is no ".$field_type." field type exist", 'master-accordion'); ?></p>
                                                                        <?php
                                                                        break;

                                                                }
                                                            ?>
                                                            
                                                        </td>
                                                    </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                                <?php } ?>
                                <hr/>
                                <input type="button" id="mran_generate_button" class="button button-primary" value="<?php esc_html_e('Generate Shortcode', 'master-accordion'); ?>" />
                                <hr/>
                            </div>
                                
                            </form>
                            <code class="mran_generated_shortcode">[mran_accordion post_type="post" posts_per_page="5" order="date" perm="readable" offset="0" mran_active_item="1" mran_template="default" mran_style="vertical" mran_dropdown_icon="fa-desktop" mran_active_dp_icon="fa-desktop"]</code>
                        </div>
                    </div><!-- /post-body-content -->
                    <div id="postbox-container-1" class="postbox-container ">
                        <h2 class="mran-top-title"><?php esc_html_e('Our Free Themes', 'master-accordion'); ?></h2>
                        <?php 
                            $themes_advertisement = array(
                                array(
                                    'name'=> esc_html__('Twenty Nineteen', 'master-accordion'),
                                    'theme_url'=> 'https://wordpress.org/themes/twentynineteen/',
                                    'demo_url'=> 'https://wp-themes.com/twentynineteen/',
                                    'docs_url'=> 'https://codex.wordpress.org/Twenty_Nineteen',
                                    'forum_url'=> 'https://wordpress.org/support/theme/twentynineteen',
                                    'thumbnail_url'=>'https://i0.wp.com/themes.svn.wordpress.org/twentynineteen/1.2/screenshot.png',
                                    'rate_url'=> 'https://wordpress.org/support/theme/twentynineteen/reviews/?filter=5',
                                    'download_url'=> 'https://downloads.wordpress.org/theme/twentynineteen.zip',
                                ),
                                array(
                                    'name'=> esc_html__('Twenty Seventeen', 'master-accordion'),
                                    'theme_url'=> 'https://wordpress.org/themes/twentyseventeen/',
                                    'demo_url'=> 'https://wp-themes.com/twentyseventeen/',
                                    'docs_url'=> 'https://codex.wordpress.org/Twenty_Seventeen',
                                    'forum_url'=> 'https://wordpress.org/support/theme/twentyseventeen',
                                    'thumbnail_url'=>'https://i0.wp.com/themes.svn.wordpress.org/twentyseventeen/1.2/screenshot.png',
                                    'rate_url'=> 'https://wordpress.org/support/theme/twentyseventeen/reviews/?filter=5',
                                    'download_url'=> 'https://downloads.wordpress.org/theme/twentyseventeen.zip',
                                ),
                                
                            );
                            foreach ($themes_advertisement as $single_theme) {
                                ?>
                                <div id="submitdiv" class="postbox mran-postbox">
                                    <h2 class="hndle ui-sortable-handle"><span><?php echo esc_attr($single_theme['name']); ?></span></h2>
                                    <div class="inside">
                                        <div class="submitbox">
                                            <div class="mran-minor-publishing">
                                                <a href="<?php echo  esc_attr($single_theme['theme_url']); ?>" title="<?php echo  esc_attr($single_theme['name']); ?>" target="_blank">
                                                    <img src="<?php echo  esc_attr($single_theme['thumbnail_url']); ?>" alt="<?php echo  esc_attr($single_theme['name']); ?>"/>
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

}

if( is_admin() )
    $mran_settings_page = new MRAN_Shortcode_Generator();