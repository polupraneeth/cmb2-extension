<?php
/**
 * Class CMB_Extension_Int
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Int
{
    /**
     * Store all output fields
     * This is used to put fields in correct wrapper <div> for tabs
     *
     * @var array
     * @since 1.0.0
     */
    protected static $fields_output = array();

    /**
     * check if CMB2 fields need to  output buffer
     *
     * @var boolean
     * @since 1.0.0
     */
    protected $capture_fields = false;

    /**
     * Hook functions
     *
     * @since 1.0.0
     */
    public function hookup()
    {
        add_action('cmb2_before_form', array($this, 'opening_div'), 10, 4);
        add_action('cmb2_after_form', array($this, 'closing_div'), 20, 4);

        add_filter('cmb2_field_arguments_raw', array($this, 'update_field_arguments'), 40, 2);
        add_action('cmb2_ext_before_closing_div', array($this, 'show_form'), 10, 4);

        $this->extend();
    }

    /**
     * Extend CMB2 fields to support Tabs and other global functionality
     *
     * @since 1.0.0
     */
    protected function extend()
    {
        new CMB_Extension_Tabs();
    }

    /**
     * Modified CMB2 render row function to capture rows in a output string
     *
     * @param $field_args
     * @param $field
     * @since 1.0.0
     */
    public static function tabs_render_row_cb($field_args, $field)
    {

        // Ok, callback is good, let's run it and store the result.
        ob_start();

        if ('group' === $field_args['type']) {
            self::tabs_render_group_row_cb($field_args, $field);
        } else {
            $field->render_field_callback();
        }

        // Grab the result from the output buffer and store it.
        $echoed = ob_get_clean();
        self::captured_render_row($echoed, $field_args, $field);
    }

    /**
     * Modified CMB2 render row function to capture Group rows in a output string
     *
     * @param $field_args
     * @param $field_group
     * @since 1.0.0
     */
    public static function tabs_render_group_row_cb($field_args, $field_group)
    {
        // Ok, callback is good, let's run it and store the result.
        ob_start();

        CMB_Extension::$cmb2->render_group_callback($field_args, $field_group);

        // Grab the result from the output buffer and store it.
        $echoed = ob_get_clean();
        self::captured_render_row($echoed, $field_args, $field_group);
    }

    /**
     * field captured to print in CMB2 Ext container.
     *
     * @param $echoed
     * @param $field_args
     * @param $field
     * @since 1.0.0
     */
    protected static function captured_render_row($echoed, $field_args, $field)
    {
        $outer_html = $echoed ? $echoed : null;
        $outer_html = apply_filters('cmb2_ext_field_output_buffer', $outer_html, $field_args, $field);

        self::$fields_output[] = $outer_html;
    }

    /**
     * Display tab navigation for meta box
     * Note that: this public function is hooked to 'cmb2_after_form', when all fields are outputted
     * (and captured by 'capture_fields' public function)
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function show_form($cmb_id, $object_id, $object_type, $cmb)
    {
        if (!$this->capture_fields) {
            return;
        }

        echo '<div class="', esc_attr($cmb->box_classes()), '"><div id="cmb2-metabox-', sanitize_html_class($cmb_id), '" class="cmb2-metabox cmb-field-list">';

        $fields_output = marge_same_key_value(self::$fields_output);
        foreach ($fields_output as $id => $fields) {

            if (apply_filters('cmb2_ext_before_opening_buffer_condition', $id, $cmb, $this)) {

                // Add classes to div
                $classes = array('cmb-ext-cap-panel');
                $classes = apply_filters('cmb2_ext_wrap_buffer_classes', $classes, $id, $cmb, $this);

                //
                echo '<div class="' . cmb_ext_clean_classes($classes) . '">';
                echo implode('', $fields);
                echo '</div>';
            }
        }

        echo '</div></div>';

        // Reset to initial state to be ready for other meta boxes
        self::$fields_output = array();
    }

    /**
     * Display opening div wrapper before form
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function opening_div($cmb_id, $object_id, $object_type, $cmb)
    {
        //Hook before opening div
        do_action('cmb2_ext_before_opening_div', $cmb_id, $object_id, $object_type, $cmb);

        // Do we need to capture CMB2 form for extension
        $this->capture_fields = apply_filters('cmb2_ext_capture_support', $cmb, $this);

        // Add classes to wrapper div
        $classes = array('cmb2-ext', 'clearfix');
        $classes = apply_filters('cmb2_ext_wrap_classes', $classes, $cmb, $this);
        echo '<div class="' . cmb_ext_clean_classes($classes) . '">';

        //Hook after opening div
        do_action('cmb2_ext_after_opening_div', $cmb_id, $object_id, $object_type, $cmb);

    }

    /**
     * Display closing div wrapper after form
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function closing_div($cmb_id, $object_id, $object_type, $cmb)
    {
        do_action('cmb2_ext_before_closing_div', $cmb_id, $object_id, $object_type, $cmb);
        echo '</div>';
        do_action('cmb2_ext_after_closing_div', $cmb_id, $object_id, $object_type, $cmb);
    }

    /**
     * Update modified field arguments into CMB2 fields
     *
     * @param $args
     * @param $field
     * @return array
     * @since 1.0.0
     */
    public function update_field_arguments($args, $field)
    {
        if (!$this->capture_fields) {
            return $args;
        }

        $defaults = $this->defaults_field_arguments($args, $field);
        $defaults = apply_filters('cmb2_ext_field_arguments_raw', $defaults, $field);
        $defaults = array_unique($defaults);

        return wp_parse_args($defaults, $args);
    }

    /**
     * Set CMB2 extension default fields
     *
     * @param $args
     * @param $field
     * @return array
     * @since 1.0.0
     */
    protected function defaults_field_arguments($args, $field)
    {

        $defaults = array();

        if ('group' === $field->type() && $this->capture_fields) {
            $defaults = array('render_row_cb' => array(__CLASS__, 'tabs_render_group_row_cb'));
        } elseif ($this->capture_fields) {
            $defaults = array('render_row_cb' => array(__CLASS__, 'tabs_render_row_cb'));
        }

        return $defaults;
    }

}
