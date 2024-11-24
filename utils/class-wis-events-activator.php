<?php

/**
 * Activates the plugin
 */
class WIS_Events_Activator {

    /**
     * The plugin's activate function
     * 
     * Registers a custom post type and flushes rewrite rules
     *
     * @return void
     * @since 1.0.0
     * @author Cornelius <cornelius@udeh.ng>
     * 
     */
    public static function activate() : void {
        (new WIS_Events)->register_event_post_type();
        flush_rewrite_rules();
    }
}