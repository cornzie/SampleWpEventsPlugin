<?php
/**
 * Triggered when the plugin is uninstalled.
 *
 * Deletes all custom post type 'event' posts and their metadata.
 */

// Exit if accessed directly.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all posts of type 'event'
$args = [
    'post_type' => 'wis_event',
    'post_status' => 'any',
    'numberposts' => -1,
];

$events = get_posts($args);

if (!empty($events)) {
    foreach ($events as $event) {
        // Delete the post and its metadata
        wp_delete_post($event->ID, true);
    }
}
