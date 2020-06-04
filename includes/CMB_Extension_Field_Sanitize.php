<?php
/**
 * Class CMB_Extension_Field_Sanitize
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth Polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Field_Sanitize
{

    /**
     * A CMB Sanitize object
     *
     * @var CMB2_Sanitize object
     */
    public $sanitizer;

    /**
     * A CMB  field type
     */
    public $field_type;

    public function __construct($field_type)
    {
        $this->field_type = $field_type;
        add_filter('cmb2_sanitize_{$field_type}', array($this, 'default_sanitization'), 10, 3);
    }

    public function default_sanitization($override_value, $value, $object_id, $field_args, $sanitizer)
    {

        $this->sanitizer = &$sanitizer;

        switch ($this->field_type) {
            case 'ajax_search':
                $sanitized_value = $this->ajax_search($override_value, $value, $object_id, $field_args, $sanitizer);
                break;
            case 'order':
            case 'visual_style_editor':
                $sanitized_value = $this->order($override_value, $value, $object_id, $field_args, $sanitizer);
                break;
            case 'map':
                $sanitized_value = $this->map($override_value, $value, $object_id, $field_args, $sanitizer);
                break;
            default:
                // We'll fallback to 'value'
                $sanitized_value = $value;
                break;
        }
        return $this->_is_empty_array($sanitized_value) ? '' : $sanitized_value;
    }

    protected function ajax_search($override_value, $value, $object_id, $field_args, $sanitizer)
    {

        if ($field_args['render_row_cb'][0]->data_to_save[$field_args['id']]) {
            $value = $field_args['render_row_cb'][0]->data_to_save[$field_args['id']];
        } else {
            $value = false;
        }

        $value = apply_filters("cmb_ext_return_ajax_search_values_{$object_id}", $value, $object_id, $this->field_type);
        return $value;
    }

    protected function order($override_value, $value, $object_id, $field_args, $sanitizer)
    {

        $fid = $field_args['id'];
        if ($field_args['render_row_cb'][0]->data_to_save[$fid]) {
            $value = $field_args['render_row_cb'][0]->data_to_save[$fid];
        } else {
            $value = false;
        }
        return $value;
    }

    protected function map($override_value, $value, $object_id, $field_args, $sanitizer)
    {
        if (isset($field_args['split_values']) && $field_args['split_values']) {
            if (!empty($value['latitude'])) {
                update_post_meta($object_id, $field_args['id'] . '_latitude', $value['latitude']);
            }
            if (!empty($value['longitude'])) {
                update_post_meta($object_id, $field_args['id'] . '_longitude', $value['longitude']);
            }
        }
        return $value;
    }

}