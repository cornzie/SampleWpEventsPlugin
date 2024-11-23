<?php
/**
 * Class WIS_Events_Loader
 *
 * This class handles the loading of actions and filters for the plugin.
 */
class WIS_Events_Loader {

    /**
     * An array of actions and filters to be registered with WordPress.
     *
     * @var array
     */
    private $actions;

    /**
     * An array of shortcodes to be registered with WordPress.
     *
     * @var array
     */
    private $filters;

    /**
     * An array of shortcodes to be registered with WordPress.
     *
     * @var array
     */
    private $shortcodes;

    /**
     * Initialize the collections used to maintain the actions, filters, and shortcodes added.
     */
    public function __construct() {
        $this->actions = array();
        $this->filters = array();
        $this->shortcodes = array();
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param string $hook The name of the WordPress action that is being registered.
     * @param object $component A reference to the instance of the object on which the action is defined.
     * @param string $callback The name of the function definition on the $component.
     * @param int $priority The priority at which the function should be fired.
     * @param int $accepted_args The number of arguments that should be passed to the $callback.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param string $hook The name of the WordPress filter that is being registered.
     * @param object $component A reference to the instance of the object on which the filter is defined.
     * @param string $callback The name of the function definition on the $component.
     * @param int $priority The priority at which the function should be fired.
     * @param int $accepted_args The number of arguments that should be passed to the $callback.
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new shortcode to the collection to be registered with WordPress.
     *
     * @param string $tag The name of the new shortcode.
     * @param object $component A reference to the instance of the object on which the shortcode is defined.
     * @param string $callback The name of the function definition on the $component.
     */
    public function add_shortcode($tag, $component, $callback) {
        $this->shortcodes = $this->add($this->shortcodes, $tag, $component, $callback);
    }

    /**
     * A utility function that is used to register the actions and hooks into WordPress.
     *
     * @param array $hooks The collection of hooks that is being registered (filtered, actions, or shortcodes).
     * @param string $hook The name of the WordPress filter that is being registered.
     * @param object $component A reference to the instance of the object on which the filter is defined.
     * @param string $callback The name of the function definition on the $component.
     * @param int $priority The priority at which the function should be fired.
     * @param int $accepted_args The number of arguments that should be passed to the $callback.
     * @return array The collection of actions and filters registered with WordPress.
     */
    private function add($hooks, $hook, $component, $callback, $priority = 10, $accepted_args = 1) {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    /**
     * Register the filters and actions with WordPress.
     */
    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->shortcodes as $hook) {
            add_shortcode($hook['hook'], array($hook['component'], $hook['callback']));
        }
    }
}