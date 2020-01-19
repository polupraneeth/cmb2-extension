<?php
/**
 * Class CMB_Extension_Type_Unit
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Font (https://github.com/rubengc/cmb2-field-unit)
// Special thanks to Rubengc for his awesome work

class CMB_Extension_Type_Unit extends CMB_Extension_Type_Multi_Base
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
     * @since 1.0.0
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
        $input = $this->types->input(array(
            'name' => $this->_name() . '[value]',
            'id' => $this->_id() . '_value',
            'class' => 'cmb2-text-small cmb-ext-unit-input',
            'type' => 'number',
            'pattern' => '\d*',
            'value' => ((isset($this->value['value'])) ? $this->value['value'] : ''),
            'desc' => '',
        ));

        $default_unit_options = array(
            'px' => 'px',
            'em' => 'em',
            'rem' => 'rem',
        );

        $unit_field = $this->unit_option($default_unit_options);

        return $this->rendered(
            sprintf('<div class="cmb-ext-unit"><div class="cmb-ext-unit-field cmb-ext-unit-field-value">%s</div>%s</div>%s',$input, $unit_field, $this->_desc(true))
        );

    }

    public function field_unit()
    {
        $this->field->escaped_value = isset($this->value['unit']) ? $this->value['unit'] : $this->value;
        $args = $this->parse_args('unit', array(
            'name' => $this->_name() . '[unit]',
            'desc' => '',
            'id' => $this->_id() . '_unit',
            'class' => 'cmb-ext-unit-select',
            'options' => $this->concat_items(),
        ));
        //reset
        $this->field->escaped_value = $this->value;

        return sprintf('<div class="cmb-ext-unit-field cmb-ext-unit-field-unit">%s</div>', $this->types->select($args));
    }

}
