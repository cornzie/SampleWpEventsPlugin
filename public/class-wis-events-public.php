<?php

/**
 * The class that manages user facing content
 */
class WIS_Events_Public
{
    /**
     * The plugin name 
     *
     * @var string
     */
    private string $plugin_name;

    /**
     * The version of the plugin in use
     *
     * @var string
     */
    private string $version;

    /**
     * A new public manager instance
     *
     * @param string $plugin_name
     * @param string $version
     * @since 1.0.0
     * @author Cornelius <cornelius@udeh.ng>
     */
    public function __construct(string $plugin_name, string $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Enqueue styles
     *
     * @return void
     * @since 1.0.0
     * @author Cornelius <cornelius@udeh.ng>
     */
    public function enqueue_styles(): void
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'assets/css/wis-events-public.css', array(), $this->version, 'all');
    }

    /**
     * Render events based on short code usage
     *
     * @param mixed $atts The attributes passed through to the short code
     * @return string The HTML output
     * @since 1.0.0
     * @author Cornelius <cornelius@udeh.ng>
     */
    public function render_events_shortcode($atts): string
    {
        // Parse any attributes passed to the shortcode
        $atts = shortcode_atts([
            'number' => 5, // Default: show 5 events
        ], $atts);

        // Query filters options
        $query_args = [
            'post_type' => 'wis_event',
            'posts_per_page' => $atts['number'],
            'meta_key' => '_wis_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        ];

        // The actual query
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
            $output .= '<h3> <a href="'. get_the_permalink(get_the_ID()) .'">' . esc_html(get_the_title()) . '</a></h3>';
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
