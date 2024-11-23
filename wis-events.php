<?php 
/**
 * Plugin Name: WIS Events
 * Description: A sample plugin to manage and display events in WordPress via Custom Post Types.
 * Author: Cornelius Udeh
 * Author URI: http://udeh.ng
 * Plugin URI: http://udeh.ng
 * 
 * Version: 1.0.0
 * Requires PHP: 8.2
 */

 if (! defined('ABSPATH')) {
    exit;
 }

 define('WIS_EVENTS_VERSION', '1.0.0');
 define('WIS_EVENTS_PATH', plugin_dir_path(__FILE__));
 define('WIS_EVENTS_URL', plugin_dir_url(__FILE__));

 require_once WIS_EVENTS_PATH . 'utils/class-wis-events-activator.php';
 require_once WIS_EVENTS_PATH . 'utils/class-wis-events-deactivator.php';
 require_once WIS_EVENTS_PATH . 'utils/class-wis-events-loader.php';
 require_once WIS_EVENTS_PATH . 'public/class-wis-events-public.php';
 require_once WIS_EVENTS_PATH . 'utils/class-wis-events.php';

 register_activation_hook(__FILE__, ['WIS_Events_Activator', 'activate']);
 register_deactivation_hook(__FILE__, ['WIS_Events_Deactivator', 'deactivate']);

 function run_wis_events() {
    (new WIS_Events())->run();
 }

 run_wis_events();