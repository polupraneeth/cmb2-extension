<?php
/**
 * Class CMB_Extension_Type_Order
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: cmb2 order (https://github.com/rubengc/cmb2-field-order)
// Special thanks to Rubengc for his awesome work

class CMB_Extension_Type_Order extends CMB_Extension_Type_Multi_Base
{

    /**
     * The optional value for the Order field
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
    public function render()
    {

        $inline = $this->field->args('inline') ? 'cmb-ext-order-inline' : '';

        $options = (array)$this->field->options();

        $re_ordered = array();
        if (is_array($this->value)) {
            foreach ((array)$this->value as $key) {
                $re_ordered[$key] = $options[$key];
            }
        }
        
        $this->field->args['options'] = wp_parse_args($options, $re_ordered);
        $this->field->set_options();

        $args = $this->parse_args('order', array(
            'class' => 'cmb-ext-order cmb-ext-order-items sortable-list-ext' . $inline,
            'id' => $this->_name() . '_items',
            'options' => $this->concat_items(array(
                'method' => 'list_input_hidden',
            )),
            'desc' => $this->_desc(true),
        ));

        return $this->rendered($this->ul($args));
    }

    protected function ul($a)
    {
        return sprintf('<ul class="%s">%s</ul>%s', $a['class'], $a['options'], $a['desc']);
    }

}
