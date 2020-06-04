<?php
/**
 * Class CMB_Extension_Type_Slider
 *
 * @since  1.0.2
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Slider (https://github.com/mattkrupnik/cmb2-field-slider)
// Special thanks to Mattkrupnik for his awesome work

class CMB_Extension_Type_Slider extends CMB2_Type_Text
{

    /**
     * The optional value for the Ajax Search field
     *
     * @var string
     */
    public $value = '';

    /**
     * Constructor
     *
     * @param CMB2_Types $types
     * @param array $args
     * @since 1.0.2
     *
     */
    public function __construct(CMB2_Types $types, $args = array())
    {
        parent::__construct($types, array());
        $this->value = is_array($args) && !is_null($args[1]) ? $args[1] : $this->value;
    }

    /**
     * Render field
     */
    public function render($args = array())
    {
        $this->field->add_js_dependencies('jquery-ui-slider');

        $input = $this->types->input(array(
            'type'       => 'hidden',
            'class'      => 'cmb-slider-field-value',
            'readonly'   => 'readonly',
            'data-start' => absint($this->value),
            'data-min'   => $this->field->min(),
            'data-max'   => $this->field->max(),
            'data-step'  => $this->field->step(),
            'desc'       => '',
        ) );

        return $this->rendered(
            sprintf('<div class="cmb-slider-field"></div>%s<span class="cmb-slider-field-value-display">%s<span class="cmb-slider-field-value-text"></span></span>%s',$input, $this->field->value_label(), $this->_desc(true))
        );

    }

}
