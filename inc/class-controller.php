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
            add_action('init', array($this, 'create_block_hackathon_block_init'));
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


         /**
        * It hooks into the Table Generator plugin's edit page and output theme section in the tab.
        * @param array $table It contains all the data of the table where we are hooking into
        */
        public function attc_tab_content( $table )
        {
            ?>
                <div class="tab-pane fade" id="table_setting">
                    <div class="container-fluid attc_import_view">
                        <div class="row">
                            <div class="col-md-5 col-md-offset-3">
                                <form action="" method="post" id="tablegen_imort_from_google">
                                    <div class="upload_content">
                                        <div class="upload_wrapper">
                                            <label for="sheet_key"><?php esc_html_e( 'Sheet Key', 'tablegen-google-sheet-integration' ); ?>*</label>
                                            <input type="text" required name="sheet_key" class="attc_input_field" placeholder="<?php esc_html_e( 'Enter your google sheet key', 'tablegen-google-sheet-integration' ); ?>" />

                                            <label for="sheet_id"><?php esc_html_e( 'Sheet ID', 'tablegen-google-sheet-integration' ); ?></label>
                                            <input type="text" name="sheet_id" class="attc_input_field" placeholder="<?php esc_html_e( 'Enter your google sheet id if you have more than one, Defaule: first sheet', 'tablegen-google-sheet-integration' ); ?>" />
                                            
                                            <label for="table_name"><?php esc_html_e( 'Table Name', 'tablegen-google-sheet-integration' ); ?>*</label>
                                            <input type="text" required name="table_name" class="attc_input_field" placeholder="<?php esc_html_e( 'Enter your table name', 'tablegen-google-sheet-integration' ); ?>" />
                                            
                                            <label for="table_description"><?php esc_html_e( 'Table Description', 'tablegen-google-sheet-integration' ); ?></label>
                                            <input type="text" name="table_description" class="attc_input_field" placeholder="<?php esc_html_e( 'Enter your description', 'tablegen-google-sheet-integration' ); ?>" />
                                        </div>
                                        <button class="attc_btn attc_google_import_btn" type="submit" name="button"><?php esc_html_e( 'Import Sheet', 'tablegen-google-sheet-integration' ); ?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
        }

         /**
         * It output a tab menu item in the tab of Table Generator plugin's edit screen by hooking there
         */
        public function create_block_hackathon_block_init()
        {
            register_block_type( SRP_BASE_DIR . '/build/payment-form' );
        }

    }
endif;