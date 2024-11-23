<?php

class WIS_Events_Activator {
    public static function activate() : void {
        (new WIS_Events)->register_event_post_type();
        flush_rewrite_rules();
    }
}