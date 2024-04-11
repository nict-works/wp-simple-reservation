<?php
/**
 * Plugin name: WP Simple Reservation
 * Description: Simple reservation system.
 * Version: 1.0.0
 * Author: nict.works
 * Author URI: https://www.nict.works
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text domain: wp-simple-reservation
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WP_SIMPLE_RESERVATION_VERSION', '1.0.0');
define('WP_SIMPLE_RESERVATION_DIRECTORY', plugin_dir_path(__FILE__));
define('WP_SIMPLE_RESERVATION_FILE', __FILE__);

require_once WP_SIMPLE_RESERVATION_DIRECTORY . 'src/autoloader.php';

new WpSimpleReservation\WpSimpleReservation();
