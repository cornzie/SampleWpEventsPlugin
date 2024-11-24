<?php

/**
 * Handles deactivation of the plugin
 */
class WIS_Events_Deactivator {

    /**
     * Deactivates the plugin and flushes rewrite rules
     * 
     * @since 1.0.0
     * @author Cornelius <cornelius@udeh.ng>
     *
     * @return void
     */
    public static function deactivate() : void {
        unregister_post_type('event');
        flush_rewrite_rules();
    }
}