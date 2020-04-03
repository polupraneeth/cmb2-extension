<?php
/**
 * Class CMB_Extension_Type_Content_Wrap
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: Content Wrap (https://github.com/rubengc/cmb2-field-content-wrap)
// Special thanks to Rubengc for his awesome work

class CMB_Extension_Type_Content_Wrap extends CMB_Extension_Type_Multi_Base
{

    /**
     * The optional value for the Content Wrap field
     *
     * @var string
     */
    public $value = '';

    /**
     * The type of field
     *
     * @var string
     */
    public $type = 'content_wrap_style_editor';

    /**
     * Constructor
     *
     * @param CMB2_Types $types
     * @param array $args
     * @param string $type
     * @since 1.0.0
     */
    public function __construct(CMB2_Types $types, $args = array(), $type = '')
    {
        parent::__construct($types, $args);
        $this->value = is_array($args) && !is_null($args[1]) ? $args[1] : $this->value;
        $this->type = $type ? $type : $this->type;
    }

    /**
     * Render field
     */
    public function render($args = array())
    {
        $initial_content_wrap = 'multiple';
        if ((isset($this->value['all']) && !empty($this->value['all']))) {
            $initial_content_wrap = 'single';
        }

        $icon_class = ($initial_content_wrap == 'single') ? "expand" : "contract";

        $default_unit_options = array(
            'px' => 'px',
            'em' => 'em',
            '%' => '%',
        );

        $unit_field = $this->unit_option($default_unit_options);

        $button = sprintf('<div class="cmb-ext-content-wrap-field cmb-ext-content-wrap-field-switch"><button type="button" class="button button-secondary"><i class="dashicons dashicons-editor-%s"></i></button></div>', $icon_class);
        return $this->rendered(
            sprintf('<div class="cmb-ext-content-wrap cmb-ext-content-wrap-%s">%s %s %s %s %s %s %s</div>%s', $initial_content_wrap, $button, $this->field_all(), $this->field_top(), $this->field_right(), $this->field_bottom(), $this->field_left(), $unit_field, $this->_desc(true))
        );

    }

    public function field_all()
    {
        $args = wp_parse_args('content_wrap_style_editor_all', array(
            'name' => $this->_name() . '[all]',
            'desc' => '',
            'id' => $this->_id() . '_all',
            'class' => 'cmb2-text-small cmb-ext-content-wrap-input',
            'type' => 'number',
            'pattern' => '\d*',
            'value' => ((isset($this->value['all'])) ? $this->value['all'] : ''),
        ));

        return $this->filed_html($args, "cmb-ext-content-wrap-field-all", __('All:', 'cmb-ext'));

    }

    public function field_top()
    {
        $args = wp_parse_args('content_wrap_style_editor_top', array(
            'name' => $this->_name() . '[top]',
            'desc' => '',
            'id' => $this->_id() . '_top',
            'class' => 'cmb2-text-small cmb-ext-content-wrap-input',
            'type' => 'number',
            'pattern' => '\d*',
            'value' => ((isset($this->value['top'])) ? $this->value['top'] : ''),
        ));

        return $this->filed_html($args, "cmb-ext-content-wrap-field-top", __('Top:', 'cmb-ext'));
    }

    public function field_right()
    {
        $args = wp_parse_args('content_wrap_style_editor_right', array(
            'name' => $this->_name() . '[right]',
            'desc' => '',
            'id' => $this->_id() . '_right',
            'class' => 'cmb2-text-small cmb-ext-content-wrap-input',
            'type' => 'number',
            'pattern' => '\d*',
            'value' => ((isset($this->value['right'])) ? $this->value['right'] : ''),
        ));

        return $this->filed_html($args, "cmb-ext-content-wrap-field-right", __('Right:', 'cmb-ext'));
    }

    public function field_bottom()
    {
        $args = wp_parse_args('content_wrap_style_editor_bottom', array(
            'name' => $this->_name() . '[bottom]',
            'desc' => '',
            'id' => $this->_id() . '_bottom',
            'class' => 'cmb2-text-small cmb-ext-content-wrap-input',
            'type' => 'number',
            'pattern' => '\d*',
            'value' => ((isset($this->value['bottom'])) ? $this->value['bottom'] : ''),
        ));

        return $this->filed_html($args, "cmb-ext-content-wrap-field-bottom", __('Bottom:', 'cmb-ext'));
    }

    public function field_left()
    {
        $args = wp_parse_args('content_wrap_style_editor_left', array(
            'name' => $this->_name() . '[left]',
            'desc' => '',
            'id' => $this->_id() . '_left',
            'class' => 'cmb2-text-small cmb-ext-content-wrap-input',
            'type' => 'number',
            'pattern' => '\d*',
            'value' => ((isset($this->value['left'])) ? $this->value['left'] : ''),
        ));

        return $this->filed_html($args, "cmb-ext-content-wrap-field-left", __('Left:', 'cmb-ext'));
    }

    public function field_unit()
    {
        $this->field->escaped_value = isset($this->value['unit']) ? $this->value['unit'] : $this->value;
        $args = wp_parse_args('content_wrap_style_editor_unit', array(
            'name' => $this->_name() . '[unit]',
            'desc' => '',
            'id' => $this->_id() . '_unit',
            'class' => 'cmb-ext-content-wrap-select',
            'options' => $this->concat_items(),
        ));

        //reset
        $this->field->escaped_value = $this->value;

        $attr = $this->concat_attrs(array('for' => $args['id']));
        $lable = __('Unit:', 'cmb-ext');
        return sprintf('<div class="cmb-ext-content-wrap-field cmb-ext-content-wrap-field-unit"><label %s>%s</label>%s</div>', $attr, $lable, $this->types->select($args));
    }

    private function filed_html($arg, $class, $label)
    {
        $attr = $this->concat_attrs(array('for' => $arg['id']));
        return sprintf('<div class="cmb-ext-content-wrap-field %s"><label %s>%s</label>%s</div>', $class, $attr, $label, $this->types->input($arg));

    }


}
