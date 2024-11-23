<?php

class WIS_Events_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/css/wis-events-public.css', array(), $this->version, 'all');
    }

    public function render_events_shortcode($atts) {
        // Parse any attributes passed to the shortcode
        $atts = shortcode_atts([
            'number' => 5, // Default: show 5 events
        ], $atts, 'wis_events');
    
        // Query the events
        $query_args = [
            'post_type'      => 'event',
            'posts_per_page' => $atts['number'],
            'meta_key'       => '_wis_event_start_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
        ];
    
        $events = new WP_Query($query_args);
    
        if (!$events->have_posts()) {
            return '<p>No events found.</p>';
        }
    
        // Build the HTML output
        $output = '<div class="wis-events-list">';
        while ($events->have_posts()) {
            $events->the_post();
            $start_date = get_post_meta(get_the_ID(), '_wis_event_start_date', true);
            $end_date = get_post_meta(get_the_ID(), '_wis_event_end_date', true);
            $start_time = get_post_meta(get_the_ID(), '_wis_event_start_time', true);
            $end_time = get_post_meta(get_the_ID(), '_wis_event_end_time', true);
            $location = get_post_meta(get_the_ID(), '_wis_event_location', true);
    
            $output .= '<div class="wis-event">';
            $output .= '<h3>' . esc_html(get_the_title()) . '</h3>';
            $output .= '<p><strong>Start Date:</strong> ' . esc_html($start_date) . '</p>';
            $output .= '<p><strong>End Date:</strong> ' . esc_html($end_date) . '</p>';
            $output .= '<p><strong>Time:</strong> ' . esc_html($start_time) . ' - ' . esc_html($end_time) . '</p>';
            $output .= '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
            $output .= '<div><strong>Details:</strong> ' . esc_html(get_the_excerpt()) . '</div>';
            $output .= '</div>';
        }
        wp_reset_postdata();
        $output .= '</div>';
    
        return $output;
    }
}