<?php

class WIS_Events_Deactivator {
    public static function deactivate() : void {
        unregister_post_type('event');
        flush_rewrite_rules();
    }
}