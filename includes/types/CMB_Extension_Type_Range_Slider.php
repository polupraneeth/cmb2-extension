<?php
/**
 * Class CMB_Extension_Type_Range_Slider
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Field Slider (https://github.com/mattkrupnik/cmb2-field-slider)
// Special thanks to Matt Krupnik (http://mattkrupnik.com) for his awesome work

class CMB_Extension_Type_Range_Slider extends CMB2_Type_Text
{

    /**
     * The optional value for the field
     *
     * @var string
     */
    public $value = '';

    /**
     * Constructor
     *
     * @param CMB2_Types $types
     * @param array $args
     * @since 1.0.0
     *
     */
    public function __construct(CMB2_Types $types, $args = array())
    {
        parent::__construct($types, $args);
        $this->value = is_array($args) && !is_null($args[1]) ? $args[1] : $this->value;
    }

    public function render($args = array())
    {

        $input = parent::render(array(
            'type' => 'hidden',
            'class' => 'cmb-ext-range-slider-field-value',
            'readonly' => 'readonly',
            'data-start' => absint($this->value),
            'data-min' => $this->field->min(),
            'data-max' => $this->field->max(),
            'data-step' => $this->field->step(),
            'desc' => '',
            'js_dependencies' => array('jquery', 'jquery-ui-slider'),
        ));

        return $this->rendered(sprintf('<div class="cmb-ext-range-slider cmb-ext-range-slider-field"></div>%s<span class="cmb-ext-range-slider-field-value-display">%s<span class="cmb-ext-range-slider-field-value-text"></span></span>%s', $input, $this->field->value_label(), $this->_desc(true)));

    }

}
