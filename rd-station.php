<?php
/**
 * Plugin Name: RD Station Integration
 * Plugin URI:  https://github.com/larissa4p/rd-station-wp
 * Description: Sincroniza leads de formulários WordPress com o CRM da RD Station.
 * Version:     1.0.0
 * Author:      Larissa Bessa
 * Author URI:  https://github.com/larissa4p
 * License:     GPL-2.0+
 * Text Domain: rd-station
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'RDS_VERSION', '1.0.0' );
define( 'RDS_PATH', plugin_dir_path( __FILE__ ) );
define( 'RDS_URL', plugin_dir_url( __FILE__ ) );

require_once RDS_PATH . 'includes/class-rd-api.php';
require_once RDS_PATH . 'includes/class-log.php';
require_once RDS_PATH . 'includes/class-settings.php';
require_once RDS_PATH . 'includes/class-cf7.php';
require_once RDS_PATH . 'includes/class-wpforms.php';

register_activation_hook( __FILE__, [ 'RDS_Log', 'create_table' ] );

new RDS_Settings();
new RDS_CF7();
new RDS_WPForms();
