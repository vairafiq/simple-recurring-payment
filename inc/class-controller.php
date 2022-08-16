<?php
defined('ABSPATH') || die('Direct access is not allowed.');
/**
 * @since 1.0.0
 * @package Directorist
 */
if (!class_exists('SRP_Controller')) :

    class SRP_Controller
    {
        public function __construct()
        {
            // add_action( 'admin_enqueue_scripts', array( $this, 'add_assets' ) );
            add_action('init', array($this, 'init'));
            add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );

            // add_action('attc_import_tab_content', array($this, 'attc_tab_content'), 10);

            // add_action('wp_ajax_tablegen_imort_from_google', array($this, 'import_from_google'));

        }

        public function import_from_google() {
       
            if( ! tablegen_verify_nonce() ) {
                wp_send_json([
                    'error' => true,
                    'msg' => __( 'Invalid nonce!', 'tablegen-google-sheet-integration' ),
                ]);
            }
    
            if( ! current_user_can( 'manage_options' ) ) {
                wp_send_json([
                    'error' => true,
                    'msg' => __( 'You are not allowed to import', 'tablegen-google-sheet-integration' ),
                ]);
            }
            
    
            $sheet_key          = ! empty( $_POST['sheet_key'] ) ? sanitize_text_field( wp_unslash( $_POST['sheet_key'] ) ) : '';
            $sheet_id           = ! empty( $_POST['sheet_id'] ) ? sanitize_text_field( wp_unslash( $_POST['sheet_id'] ) ) : '';
            $name               = ! empty( $_POST['table_name'] ) ? sanitize_text_field( wp_unslash( $_POST['table_name'] ) ) : '';
            $description        = ! empty( $_POST['table_description'] ) ? sanitize_text_field( wp_unslash( $_POST['table_description'] ) ) : '';
            
            if( empty( $sheet_key ) || empty( $name ) ) {
                wp_send_json([
                    'error' => true,
                    'msg' => __( 'Please fill up the required field', 'tablegen-google-sheet-integration' ),
                ]);
            }
    
            $url = "https://docs.google.com/spreadsheets/d/$sheet_key/export?format=csv";
            if( ! empty( $sheet_id ) ) {
                $url = "https://docs.google.com/spreadsheets/d/$sheet_key/export?gid=$sheet_id&format=csv";
            }
    
            $data = file_get_contents( $url );
            $controller = new ATTC_controller();
    
            $import = $controller->_import_insert_or_replace_table( 'csv', $data, $name, $description, '', 'add');
    
    
            if( is_wp_error( $import ) ) {
                wp_send_json([
                    'error' => true,
                    'msg' => __( 'Error importing data', 'tablegen-google-sheet-integration' ),
                ]);
            }
    
            wp_send_json([
                'msg' => __( 'Successfully imported to a new table', 'tablegen-google-sheet-integration' ),
            ]);
    
        }
     
        public function add_assets( $screen ){
            
            wp_register_script( 'attc-import-js', SRP_ADMIN_ASSETS . 'js/main.js', array(
                'jquery',
                'attc-bootstrap-js',
            ), SRP_VERSION, true );

            $attc_obj = array(
                'tablegen_nonce' => wp_create_nonce( tablegen_get_nonce_key() ),
                'ajax_url'       => admin_url( 'admin-ajax.php' ),
            );

            if( 'table-generator_page_attc-import' === $screen ) {
                wp_enqueue_script( 'attc-import-js' );
                wp_localize_script( 'attc-import-js', 'attc_import_data', $attc_obj );
            }
          
        }


        public function add_menu_pages()
        {
            add_submenu_page(
                'edit.php?post_type=srp_orders',
                'Settings',
                'Settings',
                'manage_options',
                'srp-settings',
                [ $this, 'menu_page_output' ],
                12
            );
        }
        
        public function menu_page_output() {
            echo "Hi there";
        }

         /**
         * It output a tab menu item in the tab of Table Generator plugin's edit screen by hooking there
         */
        public function init()
        {
            register_block_type( SRP_BASE_DIR . '/build/payment-form' );

            $this->register_post_type();
        }

        protected function register_post_type()
        {
            $labels = array(
                'name'                  => _x( 'Order Histories', 'Post Type General Name', 'text_domain' ),
                'singular_name'         => _x( 'Order History', 'Post Type Singular Name', 'text_domain' ),
                'menu_name'             => __( 'Payments', 'text_domain' ),
                'name_admin_bar'        => __( 'Payment', 'text_domain' ),
                'archives'              => __( 'Item Archives', 'text_domain' ),
                'attributes'            => __( 'Item Attributes', 'text_domain' ),
                'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
                'all_items'             => __( 'All Orders', 'text_domain' ),
                'edit_item'             => __( 'Edit Item', 'text_domain' ),
                'update_item'           => __( 'Update Item', 'text_domain' ),
                'view_item'             => __( 'View Item', 'text_domain' ),
                'view_items'            => __( 'View Items', 'text_domain' ),
                'search_items'          => __( 'Search Item', 'text_domain' ),
                'not_found'             => __( 'Not found', 'text_domain' ),
                'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
                'featured_image'        => __( 'Featured Image', 'text_domain' ),
                'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
                'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
                'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
                'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
                'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
                'items_list'            => __( 'Items list', 'text_domain' ),
                'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
                'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
            );
            $args = array(
                'label'                 => __( 'Order History', 'text_domain' ),
                'description'           => __( 'Order History of All Payments', 'text_domain' ),
                'labels'                => $labels,
                'supports'              => array( 'title' ),
                'taxonomies'            => array(),
                'hierarchical'          => false,
                'public'                => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_icon'             => 'dashicons-cart',
                'menu_position'         => 5,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'can_export'            => true,
                'has_archive'           => true,
                'exclude_from_search'   => false,
                'publicly_queryable'    => true,
                'capability_type'       => 'page',
                'capabilities' => array(
                    'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                  ),
                'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts
            );
            register_post_type( 'srp_orders', $args );
        }

    }
endif;