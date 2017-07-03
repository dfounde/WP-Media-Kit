<?php 
/*
Plugin Name: WP Media Kit
Description: A simple way to create and manage Media Kits in WordPress
Version:     1.0
Author:      ImpressionWeb
Author URI:  http://www.impressionwebdesign.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

define('IW_WMK_ROOT_FILE', __FILE__);
define('IW_WMK_ROOT_PATH', dirname(__FILE__));
define('IW_WMK_PLUGIN_SLUG', basename(dirname(__FILE__)));
define('IW_WMK_PLUGIN_BASE', plugin_basename(__FILE__));
define('IW_WMK_PLUGIN_VERSION', '1.0.0');

require_once(IW_WMK_ROOT_PATH . '/class.wmk.php');
require_once(IW_WMK_ROOT_PATH . '/class.wmk.post.meta.php');
require_once(IW_WMK_ROOT_PATH . '/class.wmk.magazine.covers.php');
/*
require_once(IW_WMK_ROOT_PATH . '/class.wmk.admin.php');

// Backend include
if ( is_admin() ) {
	add_action('init', array('WMK_Admin', 'init'));
}

*/

WP_Media_Kit::get_instance();
 ?>