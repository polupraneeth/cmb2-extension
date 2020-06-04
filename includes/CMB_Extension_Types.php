<?php
/**
 * Class CMB_Extension_Types
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth Polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Types
{

    /**
     * CMB2 extension fields
     *
     * @var   array
     * @since 1.0.0
     */
    protected $ext_fields = array(
        'content_wrap_style_editor',
        'switch_button',
        'image_select',
        'icon_select',
        'ajax_search',
        'animation',
        'button_set',
        'slider',
        'order',
        'unit',
        'font',
        'map'
    );

    /**
     * CMB2 extension fields css dependency
     *
     * @var   array
     * @since 1.0.0
     */
    protected $fields_css_dependency = array(
        'icon_select' => array('jqueryfontselectormain', 'jqueryfontselector'),
        'animation' => array('animate'),
        'font' => array('select2'),
        'slider' => array('jquery-ui'),
    );

    public function ajax_search($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Ajax_Search', $args)->render();
    }

    public function animation($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Animation', $args)->render();
    }

    public function button_set($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Buttonset', $args)->render();
    }

    public function slider($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Slider', $args)->render();
    }

    public function order($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Order', $args)->render();
    }

    public function unit($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Unit', $args)->render();
    }

    public function font($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Font', $args)->render();
    }

    public function map($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Map', $args)->render();
    }

    /**
     * Initialize the plugin by hooking into CMB2
     * @param CMB2 $cmb
     */
    public function __construct(CMB2 $cmb)
    {
        cmb_ext_ajax();
        // Load CMB2 Extension fields
        $this->int_fields($cmb);
    }

    /**
     * Initialize extended fields
     *
     * @param $cmb2
     * @since  1.0.0
     */
    public function int_fields($cmb2)
    {
        $css_dependency = array();
        foreach ($cmb2->prop('fields') as $field_args) {
            $hook = 'cmb2_render_' . $field_args['type'];
            $type = $field_args['type'];

            //css dependency
            if (array_key_exists($type, $this->fields_css_dependency)) {
                $css_dependency = wp_parse_args($this->fields_css_dependency[$type], $css_dependency);
            }

            //hook up render fields
            if (in_array($type, $this->ext_fields, true)) {

                add_action($hook, array($this, '_render'), 10, 5);
                new CMB_Extension_Field_Display($type);
                new CMB_Extension_Field_Sanitize($type);

            }
        }

        add_filter('cmb2_ext_style_dependencies', function ($dependencies) use ($css_dependency) {
            return wp_parse_args($css_dependency, $dependencies);
        });

    }

    public function content_wrap_style_editor($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Content_Wrap', $args)->render();
    }

    public function switch_button($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Switch_Button', $args)->render();
    }

    public function image_select($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Image_Select', $args)->render();
    }

    public function icon_select($args)
    {
        return $args[4]->get_new_render_type(__FUNCTION__, 'CMB_Extension_Type_Icon_Select', $args)->render();
    }

    /**
     * Render a field type
     *
     * @param $field
     * @param $field_escaped_value
     * @param $field_object_id
     * @param $field_object_type
     * @param $field_type_object
     * @since  1.0.0
     */
    public function _render($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object)
    {
        echo $this->{$field->type()}(array($field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object));
    }


    /**
     * Non-existent methods fallback to checking for field arguments of the same name
     *
     * @param $fieldtype
     * @param $arguments
     * @since  1.0.0
     */
    public function __call($fieldtype, $arguments)
    {
        if (!@class_exists("CMB_Extension_Types_" . type2className($fieldtype))) {
            return;
        }
        $this->default_render($arguments);
    }

    /**
     * Fields to render by classes
     *
     * @param $arg
     * @return mixed
     * @since  1.0.0
     */
    protected function default_render($arg)
    {

        $arg = $arg[0];
        $class_type_name = type2className($arg[0]->type());

        return $arg[4]->get_new_render_type(__FUNCTION__, "CMB_Extension_Types_{$class_type_name}", array(
            'field' => $arg[0],
            'field_escaped_value' => $arg[1],
            'field_object_id' => $arg[2],
            'field_object_type' => $arg[3],
            'field_type_object' => $arg[4],
        ))->render();

    }

}
