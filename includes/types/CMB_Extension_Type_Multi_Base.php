<?php
/**
 * CMB Extension Multi base field type Class
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

abstract class CMB_Extension_Type_Multi_Base extends CMB2_Type_Multi_Base
{

    /**
     * Generates html for list item with input
     *
     * @param array $args Override arguments
     * @param int $i Iterator value
     * @return string       Gnerated list item html
     * @since  1.1.0
     */
    public function list_input_hidden($args = array(), $i)
    {
        $a = $this->parse_args('list_input_hidden', array(
            'type' => 'hidden',
            'name' => $this->_name() . '[]',
            'id' => $this->_id($i),
            'value' => $this->field->escaped_value(),
            'label' => '',
        ), $args);

        return sprintf("\t" . '<li><input%s/><span >%s</span></li>' . "\n", $this->concat_attrs($a, array('label')), $a['label']);
    }

    public function unit_option($default_unit_options)
    {

        if (is_array($this->field->args('units'))) {
            $unit_options = $this->field->args('units');
        } else {
            $unit_options = !empty($this->field->options()) ? $this->field->options() : $default_unit_options;
        }

        // If there is just 1 unit option, set it on a hidden field
        if (count($unit_options) === 1) {
            $first_index = array_keys($unit_options)[0];
            return $this->types->input(array(
                'name' => $this->_name() . '[unit]',
                'desc' => '',
                'id' => $this->_id() . '_unit',
                'type' => 'hidden',
                'value' => $unit_options[$first_index],
            ));

        } else {
            $this->field->args['options'] = $unit_options;
            $this->field->set_options();
            return $this->field_unit();
        }
    }

}
