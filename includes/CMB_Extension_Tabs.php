<?php
/**
 * Class CMB_Extension_Tabs
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth Polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Tabs
{

    /**
     * Current active Panel
     *
     * @var string
     * @since 1.0.0
     */
    protected static $active_panel = '';

    /**
     * Indicate that the instance of the class is working on a meta box that has tabs or not
     * It will be set 'true' BEFORE meta box is display and 'false' AFTER
     *
     * @var boolean
     * @since 1.0.0
     */
    public $active = false;

    /**
     * Indicate to take buffer approach or not
     *
     * @var boolean
     * @since 1.0.0
     */
    protected $tab_buffer = false;

    /**
     * Deactivate Conditional tabs "show_on_cb"
     *
     * @var array
     * @since 1.0.0
     */
    protected $conditional = array();

    /**
     * Initialize the hooking
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('cmb2_ext_before_opening_div', array($this, 'check_for_tabs_support'), 10, 4);
        add_action('cmb2_ext_after_opening_div', array($this, 'render_nav'), 20, 4);
        add_action('cmb2_ext_after_closing_div', array($this, 'reset_tabs'), 20, 4);

        add_filter('cmb2_ext_wrap_classes', array($this, 'tab_classes'), 10, 3);
        add_filter('cmb2_wrap_classes', array($this, 'panel_wrapper_class'), 10, 2);
        add_filter('cmb2_row_classes', array($this, 'fields_row_class'), 10, 2);
        add_filter('cmb2_ext_wrap_buffer_classes', array($this, 'tab_panel_classes'), 10, 4);

        add_filter('cmb2_ext_capture_support', array($this, 'enable_capture_fields'), 10, 3);
        add_filter('cmb2_ext_before_opening_buffer_condition', array($this, 'tabs_echo_condition'), 10, 3);
        add_filter('cmb2_ext_field_output_buffer', array($this, 'buffer_fields'), 10, 3);

    }

    /**
     * Initialize variable if the form has tabs
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function check_for_tabs_support($cmb_id, $object_id, $object_type, $cmb)
    {
        if (!$cmb->prop("tabs")) {
            return;
        }

        // Set 'true' to let us know that we're working on a meta box that has tabs
        $this->active = true;

        //Check if the tabs fields must have buffer approach
        $this->tab_buffer = (is_null($cmb->prop("tab_buffer")) && !is_bool($cmb->prop("tab_buffer"))) ? $this->tab_buffer : $cmb->prop("tab_buffer");
    }

    /**
     * Return 'True' if we need to capture the cmb2 form
     *
     * @since 1.0.0
     */
    public function enable_capture_fields($cmb, $cmb_ext_buffer)
    {
        return $this->tab_buffer;
    }

    /**
     * Add tab style classes to wrapper <div>
     *
     * @param $class
     * @param $cmb
     * @param $cmb_ext_buffer
     * @return array
     * @since 1.0.0
     */
    public function tab_classes($class, $cmb, $cmb_ext_buffer)
    {
        if (!$cmb->prop("tabs")) {
            return $class;
        }

        $classes = array('cmb-tabs');
        $tab_style = $cmb->prop("tab_style");

        if (isset($tab_style) && 'default' != $tab_style) {
            $classes[] = 'cmb-tabs-' . $tab_style;
        }

        // Include an ID based class wrapper as well.
        $classes[] = 'cmb-tabs-' . $cmb->prop('id');

        // And merge all the classes back into the array.
        return array_merge($classes, $class);

    }

    /**
     * Add class to wrapper div of CMB2 fields panel in buffer approach
     *
     * @param $class
     * @param $id
     * @param $cmb
     * @param $cmb_ext_buffer
     * @return array
     * @since 1.0.0
     */
    public function tab_panel_classes($class, $id, $cmb, $cmb_ext_buffer)
    {
        if (!$cmb->prop("tabs")) {
            return $class;
        }

        $classes = array('cmb-tab-panel');
        $active_panel = CMB_Extension_Tabs::$active_panel == $id ? "show" : "";
        $classes[] = $active_panel;
        $classes[] = 'cmb-tab-panel-' . $id;

        // And merge all the classes back into the array.
        return array_merge($classes, $class);

    }

    /**
     * Reset Variables once we complete rendering the form
     *
     * @since 1.0.0
     */
    public function reset_tabs()
    {
        if (!$this->active) {
            return;
        }

        // Reset to initial state to be ready for other meta boxes
        $this->active = false;
    }

    /**
     * Render Navigation
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function render_nav($cmb_id, $object_id, $object_type, $cmb)
    {
        if (!$this->active) {
            return;
        }

        $tabs = $cmb->prop("tabs");

        if ($tabs) {
            echo '<ul class="cmb-tab-nav cmb-ext-tab-nav">';
            $active_nav = true;
            foreach ($tabs as $key => $tab_data) {

                if (is_string($tab_data)) {
                    $tab_data = array('label' => $tab_data);
                }

                $tab_data = wp_parse_args($tab_data, array(
                    'icon' => '',
                    'label' => '',
                    'show_on_cb' => null,
                ));
                $tab_data = array_unique($tab_data);

                if ($tab_data['show_on_cb'] && $cmb->do_callback($tab_data['show_on_cb'], $cmb, $this)) {
                    $this->conditional[] = $key;
                    continue;
                }

                //set icon default it's empty
                $tab_data['icon'] = $tab_data['icon'] ? $tab_data['icon'] : "dashicons-admin-post";

                // If icon is URL to image
                if (filter_var($tab_data['icon'], FILTER_VALIDATE_URL)) {
                    $icon = '<img src="' . $tab_data['icon'] . '">';
                } // If icon is icon font
                else {
                    // If icon is dashicon, auto add class 'dashicons' for users
                    if (false !== strpos($tab_data['icon'], 'dashicons')) {
                        $tab_data['icon'] .= ' dashicons';
                    }
                    // Remove duplicate classes
                    $tab_data['icon'] = array_filter(array_map('trim', explode(' ', $tab_data['icon'])));
                    $tab_data['icon'] = implode(' ', array_unique($tab_data['icon']));

                    $icon = $tab_data['icon'] ? '<i class="' . $tab_data['icon'] . '"></i>' : '';
                }

                $class = "cmb-tab-$key";
                if ($active_nav) {
                    $class .= ' cmb-tab-active';
                    self::$active_panel = $key;
                    $active_nav = false;
                }

                printf(
                    '<li class="%s" data-panel="%s"><a href="#" class="cmb-ext-tab-nav-menu">%s<span>%s</span></a></li>',
                    $class,
                    $key,
                    $icon,
                    $tab_data['label']
                );
            }
            echo '</ul>';

        }
    }

    /**
     * Add classes to after tab navigation <div>
     *
     * @param $classes
     * @param $box
     * @return array
     * @since 1.0.0
     */
    public function panel_wrapper_class($classes, $box)
    {

        if (!$this->active) {
            return $classes;
        }

        $classes[] = 'cmb-tabs-panel';

        if ($this->active && $this->tab_buffer) {
            $classes[] = 'cmb2-wrap-tabs';
        } else {
            $classes[] = 'cmb2-nowrap-tabs';
        }

        return array_unique($classes);
    }

    /**
     * update classes on cmb2 fields wrapper <div> non wrapping approach
     *
     * @param $classes
     * @param $field
     * @return string
     * @since 1.0.0
     */
    public function fields_row_class($classes, $field)
    {
        if (!$this->active || $this->tab_buffer) {
            return $classes;
        }

        $current_tab = $field->group instanceof CMB2_Field ? $field->group->args['tab'] : $field->args('tab');
        $active_panel = self::$active_panel == $current_tab ? "show" : "";

        return $classes . ' ' . $active_panel . ' cmb-tab-panel cmb-tab-panel-' . $current_tab;
    }

    /**
     *  Check for conditional argument on field
     *
     * @param $id
     * @param $cmb
     * @param $cmb_ext_buffer
     * @return bool
     * @since 1.0.0
     */
    public function tabs_echo_condition($id, $cmb, $cmb_ext_buffer)
    {
        return !in_array($id, $this->conditional, TRUE);
    }

    /**
     * return array format $output for tab sorting buffer approach
     *
     * @param $output
     * @param $field_args
     * @param $field
     * @return array
     * @since 1.0.0
     */
    public function buffer_fields($output, $field_args, $field)
    {
        // If meta box doesn't have tabs, do nothing
        if ($field->group) {
            echo $output;
            return array($field->group->args['tab'] => '');
        }
        return array($field_args['tab'] => $output);
    }

}

