<?php
// Plugin version.
// if ( ! defined( 'SRP_VERSION' ) ) {define( 'SRP_VERSION', srp_get_version_from_file_content( SRP_FILE ) );}
if ( ! defined( 'SRP_VERSION' ) ) {define( 'SRP_VERSION', '1.0' );}
// Plugin Folder Path.
if ( ! defined( 'SRP_DIR' ) ) { define( 'SRP_DIR', plugin_dir_path( SRP_FILE ) ); }
// Plugin Folder URL.
if ( ! defined( 'SRP_URL' ) ) { define( 'SRP_URL', plugin_dir_url( SRP_FILE ) ); }
// Plugin Root File.
if ( ! defined( 'SRP_BASE' ) ) { define( 'SRP_BASE', plugin_basename( SRP_FILE ) ); }
// Plugin Includes Path
if ( !defined('SRP_INC_DIR') ) { define('SRP_INC_DIR', SRP_DIR.'inc/'); }
// Plugin Assets Path
if ( !defined('SRP_ASSETS') ) { define('SRP_ASSETS', SRP_URL.'assets/'); }
// Public asset
if ( !defined('SRP_PUBLIC_ASSETS') ) { define('SRP_PUBLIC_ASSETS', SRP_URL.'assets/public/'); }
// Admin asset
if ( !defined('SRP_ADMIN_ASSETS') ) { define('SRP_ADMIN_ASSETS', SRP_URL.'assets/admin/'); }
// Plugin Language File Path
if ( !defined('SRP_LANG_DIR') ) { define('SRP_LANG_DIR', dirname(plugin_basename( SRP_FILE ) ) . '/languages'); }
// Plugin Name
if ( !defined('SRP_NAME') ) { define( 'SRP_NAME', 'TableGen - Google Sheet Integration' ); }
// Plugin Alert Message
if ( !defined('SRP_ALERT_MSG') ) { define('SRP_ALERT_MSG', __('You do not have the right to access this file directly', 'tablegen-google-sheet-integration')); }
