<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific Accordion Ajax functionality of the plugin.
 *
 * Accordion Specific Ajax Data
 * @package    Master_Accordion
 * @subpackage Master_Accordion/admin
 * @author     Theme Century Team <themecentury@gmail.com>
 */
class MRAN_Accordion_Ajax{

    /**
     * Accordion Data of this plugin.
     *
     * @since    1.1.0
     * @access   private
     * @var      string    $accordion_data    Accordion Data of this plugin.
     */
    private $accordion_data;

    /**
     * Constructor for Ajax for the admin area.
     *
     * @since    1.1.0
     */
    function __construct(){

        add_action( 'wp_ajax_mran_accordion_widget', [$this, 'accordion_widget'] );

    }

    /**
     * Set data to $accordion_data variable
     *
     * @since    1.1.0
     */
    function set_accordion_data(){

    }

    /**
     * Get data to $accordion_data variable
     *
     * @since    1.1.0
     */
    function get_accordion_data(){

    }

    /**
     * This function return Accordion Data to Widget area
     *
     * @since    1.1.0
     */
    public function accordion_widget(){

        if( ! (isset( $_POST['action']) && $_POST['action']=='mran_accordion_widget' )  ){
            die();
        }

        $data_type = sanitize_text_field($_POST['data']['data_type']);
        $data_value = sanitize_text_field($_POST['data']['data_value']);
        $data = array();
        switch($data_type){
            case 'post_type':
                $all_taxonomy = get_object_taxonomies($data_value, 'objects');
                if(count($all_taxonomy)){
                    foreach($all_taxonomy as $taxonomy_key=>$taxonomy_value){
                        $data[]=array(
                            'slug'=>$taxonomy_key,
                            'name'=>$taxonomy_value->label
                        );
                    }
                }
                break;
            case 'taxonomy':

                $args = array(
                    'taxonomy' => $data_value,
                    'hide_empty' => false,
                );

                $all_terms = get_terms($args);

                if( is_array($all_terms) ):
                    $data = $all_terms;
                endif;

                break;
            default:
                $data = array();
                break;
        }
        echo json_encode($data);
        die();

    }

    /**
     * @since    1.1.0
     */
    function __destruct(){

    }

}

new MRAN_Accordion_Ajax();
