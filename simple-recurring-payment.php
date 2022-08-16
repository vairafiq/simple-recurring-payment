<?php
/**
 * Plugin Name:       Simple Recurring Payment
 * Description:       Example static block scaffolded with Create Block tool.
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hackathon
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

defined('ABSPATH') || die('No direct script access allowed!');

if (!class_exists('SRP_From')) {
    final class SRP_From
    {

        /** Singleton *************************************************************/

        /**
         * @var SRP_From The one true SRP_From
         * @since 1.0
         */
        private static $instance;

        /**
         * @since 1.0.0
         */
        public $controller;

        /**
         * Main SRP_From Instance.
         *
         * Insures that only one instance of SRP_From exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @return object|SRP_From The one true SRP_From
         * @uses SRP_From::setup_constants() Setup the constants needed.
         * @uses SRP_From::includes() Include the required files.
         * @uses SRP_From::load_textdomain() load the language files.
         * @see  SRP_From()
         * @since 1.0
         * @static
         * @static_var array $instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof SRP_From)) {
                self::$instance = new SRP_From;
                self::$instance->setup_constants();
                self::$instance->includes();
                add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
                self::$instance->controller = new SRP_Controller;
            }
            return self::$instance;
        }

        private function __construct()
        {
            /*making it private prevents constructing the object*/
        }

        /**
         * Throw error on object clone.
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         *
         * @return void
         * @since 1.0
         * @access protected
         */
        public function __clone()
        {
            // Cloning instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'simple-recurring-payment'), '1.0');
        }

        /**
         * Disable unserializing of the class.
         *
         * @return void
         * @since 1.0
         * @access protected
         */
        public function __wakeup()
        {
            // Unserializing instances of the class is forbidden.
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'simple-recurring-payment'), '1.0');
        }

        /**
         * It register the text domain to the WordPress
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('simple-recurring-payment', false, SRP_LANG_DIR);
        }

        /**
         * It Includes and requires necessary files.
         *
         * @access private
         * @return void
         * @since 1.0
         */
        private function includes()
        {
            require_once SRP_INC_DIR . 'class-controller.php';
            require_once SRP_INC_DIR . 'helper-functions.php';
            require_once SRP_INC_DIR . 'config-helper.php';
        }

        /**
         * Setup plugin constants.
         *
         * @access private
         * @return void
         * @since 1.0
         */
        private function setup_constants()
        {
            if ( ! defined( 'SRP_FILE' ) ) { define( 'SRP_FILE', __FILE__ ); }
            if ( ! defined( 'SRP_BASE_DIR' ) ) { define( 'SRP_BASE_DIR', __DIR__ ); }

            // require_once plugin_dir_path(__FILE__) . '/inc/config-helper.php'; // loads constant from a file so that it can be available on all files.
            require_once plugin_dir_path(__FILE__) . '/config.php'; // loads constant from a file so that it can be available on all files.
        }
    }

    if ( ! function_exists( 'srp_is_plugin_active' ) ) {
        function srp_is_plugin_active( $plugin ) {
            return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || srp_is_plugin_active_for_network( $plugin );
        }
    }
    
    if ( ! function_exists( 'srp_is_plugin_active_for_network' ) ) {
        function srp_is_plugin_active_for_network( $plugin ) {
            if ( ! is_multisite() ) {
                return false;
            }
                    
            $plugins = get_site_option( 'active_sitewide_plugins' );
            if ( isset( $plugins[ $plugin ] ) ) {
                    return true;
            }
    
            return false;
        }
    }

    /**
     * The main function for that returns SRP_From
     *
     * The main function responsible for returning the one true SRP_From
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     *
     * @return object|SRP_From The one true SRP_From Instance.
     * @since 1.0
     */
    function SRP_From()
    {
        return SRP_From::instance();
    }

    // Instantiate Directorist Stripe gateway only if our directorist plugin is active
    if ( srp_is_plugin_active( 'simple-recurring-payment/simple-recurring-payment.php' ) ) {
        SRP_From(); // get the plugin running
    }
}

