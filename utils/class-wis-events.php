<?php

class WIS_Events {

    private $loader;

    public function __construct()
    {
        $this->loader = new WIS_Events_Loader();
        $this->define_util_hooks();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    public function define_admin_hooks() {
        // $plugin_admin = new WIS_Events_Admin();
    }

    public function define_util_hooks() {
        $this->loader->add_action('init', $this, 'register_event_post_type');
        $this->loader->add_action('add_meta_boxes', $this, 'add_event_meta_boxes');
        $this->loader->add_action('save_post', $this, 'save_event_meta');
        $this->loader->add_action('admin_notices', $this, '_wis_admin_notices');
        $this->loader->add_action('the_content', $this, 'display_event_meta_on_single_page');
    }

    public function define_public_hooks() {
        $plugin_public = new WIS_Events_Public($this->get_plugin_name(), $this->get_plugin_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_shortcode('display_wis_events', $plugin_public, 'render_events_shortcode');
    }

    public function register_event_post_type() {
        $labels = [
            'name'               => __('Events', 'wis-events'),
            'singular_name'      => __('Event', 'wis-events'),
            'menu_name'             => __('Events', 'wis-events'),
            'add_new_item'       => __('Add New Event', 'wis-events'),
            'edit_item'          => __('Edit Event', 'wis-events'),
            'new_item'           => __('New Event', 'wis-events'),
            'view_item'          => __('View Event', 'wis-events'),
            'all_items'          => __('All Events', 'wis-events'),
            'update_item'           => __('Update Event', 'wis-events'),
            'view_Event'             => __('View Event', 'wis-events'),
            'view_Events'            => __('View Events', 'wis-events'),
            'search_Events'          => __('Search Event', 'wis-events'),
            'not_found'             => __('No events found', 'wis-events'),
            'not_found_in_trash'    => __('No events found in Trash', 'wis-events'),
            'featured_image'        => __('Event Featured Image', 'wis-events'),
            'set_featured_image'    => __('Set event featured image', 'wis-events'),
            'remove_featured_image' => __('Remove event featured image', 'wis-events'),
            'use_featured_image'    => __('Use as event featured image', 'wis-events'),
            'insert_into_Event'      => __('Insert into Event', 'wis-events'),
        ];

        $args = [
            'labels'                => $labels,
            'public'                => true,
            'has_archive'           => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 25,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'show_in_rest'          => true,
            'capability_type'       => 'post',
            'supports'              => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'rewrite'               => ['slug' => 'events'],
            'menu_icon'          => 'dashicons-calendar',
        ];

        register_post_type('wis_event', $args);
    }

    public function display_event_meta_on_single_page($content) {
        if (is_singular('wis_event')) {
            $post_id = get_the_ID();

            $start_date   = $this->get_meta_value($post_id, '_wis_event_start_date');
            $end_date     = $this->get_meta_value($post_id, '_wis_event_end_date');
            $start_time   = $this->get_meta_value($post_id, '_wis_event_start_time');
            $end_time     = $this->get_meta_value($post_id, '_wis_event_end_time');
            $location     = $this->get_meta_value($post_id, '_wis_event_location');
            
            $event_details = '<div class="wis-event">';
            $event_details .= '<h3>Event Details</h3>';
            $event_details .= '<p><strong>Start Date:</strong> ' . esc_html(date('l jS, Y', strtotime($start_date))) . '</p>';
            $event_details .= '<p><strong>End Date:</strong> ' . esc_html(date('l jS, Y', strtotime($end_date))) . '</p>';
            $event_details .= '<p><strong>Start Time:</strong> ' . esc_html(date('H:i A (e)', strtotime($start_time))) . '</p>';
            $event_details .= '<p><strong>End Time:</strong> ' . esc_html(date('h:i A (e)', strtotime($end_time))) . '</p>';
            $event_details .= '<p><strong>Location:</strong> ' . esc_html($location) . '</p>';
            $event_details .= '</div>';
            
            $content .= $event_details;
        }

        return $content;
    }

    public function add_event_meta_boxes() {
        add_meta_box(
            'wis_event_details',
            __('Event Details', 'wis-events'),
            [$this, 'render_event_meta_boxes'],
            'event',
            'normal',
            'default'
        );
    }

    /**
     * Render the Event's meta fields.
     * 
     * @param WP_Post $post
     * @return void
     */
    public function render_event_meta_boxes($post) {
        // Get current meta data values
        $start_date = get_post_meta($post->ID, '_wis_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_wis_event_end_date', true);
        $start_time = get_post_meta($post->ID, '_wis_event_start_time', true);
        $end_time = get_post_meta($post->ID, '_wis_event_end_time', true);
        $location = get_post_meta($post->ID, '_wis_event_location', true);

        wp_nonce_field('wis_event_meta_box', 'wis_event_meta_box_nonce');

        // Render meta box fields
        ?>
        <div class="wis_event_meta_boxes_container">
            <div class="wis_event_meta_dates">
                <div>
                    <label for="wis_event_start_date"><?php _e('Start Date', 'wis-events'); ?></label> <br>
                    <input type="date" min="<?php echo date('Y-m-d'); ?>" id="wis_event_start_date" name="_wis_event_start_date" value="<?php echo esc_attr($start_date); ?>">
                </div>
                <div>
                    <label for="wis_event_end_date"><?php _e('End Date', 'wis-events'); ?></label> <br>
                    <input type="date" min="<?php echo date('Y-m-d'); ?>" id="wis_event_end_date" name="_wis_event_end_date" value="<?php echo esc_attr($end_date); ?>">
                </div>
            </div>
            <div class="wis_event_meta_timing">
                <div>
                    <label for="wis_event_start_time"><?php _e('Start Time (12-Hour)', 'wis-events'); ?></label> <br>
                    <input type="time" id="wis_event_start_time" name="_wis_event_start_time" value="<?php echo esc_attr($start_time); ?>">
                </div>
                <div>
                    <label for="wis_event_end_time"><?php _e('End Time (12-Hour)', 'wis-events'); ?></label> <br>
                    <input type="time" id="wis_event_end_time" name="_wis_event_end_time" value="<?php echo esc_attr($end_time); ?>">
                </div>
            </div>
            <div class="wis_event_meta_location">
                <div>
                    <label for="wis_event_location"><?php _e('Location', 'wis-events'); ?></label> <br>
                    <input type="text" id="wis_event_location" name="_wis_event_location" value="<?php echo esc_attr($location); ?>">
                </div>
            </div>
        </div>
        <?php
    }

    public function save_event_meta($post_id) {

        // Skip if POST data is empty
        if (empty($_POST)) {
            return;
        }

        // Skip autosave or invalid requests
        if (! $this->isValidSaveRequest($post_id, $_POST)) {
            return;
        }

        if (! $this->validated($post_id, $_POST)) {
            return;
        }

        // Save meta fields
        $fields = [
            '_wis_event_start_date',
            '_wis_event_end_date',
            '_wis_event_location',
            '_wis_event_start_time',
            '_wis_event_end_time',
        ];
        
        foreach ($fields as $meta_key) {
            if (isset($_POST[$meta_key])) {
                $value = sanitize_text_field($_POST[$meta_key]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }

    }

    /**
     * Retrieve and display errors
     * @since 1.0.0
     * @return void
     */ 
    public function _wis_admin_notices() {
        $screen = get_current_screen();

        // Show errors only on the 'event' post type edit screen
        if ($screen && $screen->post_type === 'event') {
            $errors = get_transient('wis_event_errors_');

            // No errors? Get out
            if (!$errors) {
                return;  
            }

            // TODO: handle validation errors in Block editor supported way.
            // Errors? Display them
            echo '<div class="">';
            echo '<ul>';
            foreach ($errors as $error) {
                echo '<li>' . esc_html($error) . '</li>';
            }
            echo '</ul>';
            echo '</div>';

            // Ensure we don't display them multiple times
            delete_transient('wis_event_errors_');
        }

    }

    private function isValidSaveRequest(int $post_id, array $post_request) {

        return 
            isset($post_request['wis_event_meta_box_nonce']) &&
            wp_verify_nonce($post_request['wis_event_meta_box_nonce'], 'wis_event_meta_box') &&
            ! (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) &&
            current_user_can('edit_post', $post_id);
    }

    private function validated(int $post_id, array $post_request) {
        $errors = [];
        $start_date = isset($post_request['_wis_event_start_date']) ? sanitize_text_field($post_request['_wis_event_start_date']) : '';
        $end_date = isset($post_request['_wis_event_end_date']) ? sanitize_text_field($post_request['_wis_event_end_date']) : '';
        
        $start_time = isset($post_request['_wis_event_start_time']) ? sanitize_text_field($post_request['_wis_event_start_time']) : '';
        $end_time = isset($post_request['_wis_event_end_time']) ? sanitize_text_field($post_request['_wis_event_end_time']) : '';
        
        // Validate dates
        if ($start_date && strtotime($start_date) < strtotime('today')) {
            $errors[] = 'Start date cannot be in the past.';
        }

        if ($end_date && strtotime($end_date) < strtotime('today')) {
            $errors[] = 'End date cannot be in the past.';
        }

        if ($start_date && $end_date && strtotime($start_date) > strtotime($end_date)) {
            $errors[] = 'Start date must be before the end date.';
        }

        // Validate times
        if ($start_time && $end_time && strtotime($start_time) >= strtotime($end_time)) {
            $errors[] = 'Start time must be earlier than end time.';
        }
        
        // Log and set errors
        if (!empty($errors)) {
            error_log("Validation errors: " . implode(', ', $errors)); // Debug log
            set_transient('wis_event_errors_', $errors, 30); // Store errors for display
            return false;
        }

        return true;
    }

    public function run() {
        $this->loader->run();
    }

    private function get_plugin_name() {
        return 'wis-events';
    }

    private function get_plugin_version() {
        return WIS_EVENTS_VERSION;
    }

    private function get_meta_value(int $post_id, string $meta_key, bool $single = true) {
        return get_post_meta($post_id, $meta_key, $single);
    }
}