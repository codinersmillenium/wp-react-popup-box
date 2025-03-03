<?php
/**
 * Plugin Name:     React Popup Box
 * Plugin URI:      https://github.com/codinersmillenium/wp-react-popup-box.git
 * Description:     The Popup Box plugin is a WordPress plugin built using React.js to create and manage customizable popup boxes with a modern and interactive UI. It leverages Custom Post Type (CPT) for popup management, allowing users to create, edit, and display popups easily from the WordPress dashboard.
 * Author:          codinersmillenium
 * Author URI:      https://github.com/codinersmillenium
 * Text Domain:     react-popup-box
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         React_Popup_Box
 */

// Your code starts here.

if ( ! defined('ABSPATH') ) exit; // Exit if accessed directly

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugin_dir_url(__FILE__));
require_once __DIR__ . '/vendor/autoload.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Http/Routes/Api.php';

use PopupBox\Controllers\PopupAdmin;
use PopupBox\Controllers\PopupBox;
add_action('plugins_loaded', function() {
    $db = Database::get_instance()->install();
    if (!$db) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(' Plugin ini akan dinonaktifkan sekarang, karena terdapat kesalahan database.');
    }
    PopupAdmin::get_instance();
    PopupBox::get_instance();
});
