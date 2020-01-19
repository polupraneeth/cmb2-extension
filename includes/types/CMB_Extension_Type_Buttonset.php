<?php
/**
 * Class CMB_Extension_Type_Buttonset
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Buttonset (https://www.proy.info/how-to-create-cmb2-buttonset-field-type/)

class CMB_Extension_Type_Buttonset extends CMB2_Type_Multi_Base
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
    }

    /**
     * Render field
     */
    public function render($args = array())
    {

        $args = $this->parse_args('button_set', array(
            'id' => 'cmb2-ext-buttonset' . $this->_id(),
            'class' => 'cmb2-ext-buttonset',
            'options' => $this->concat_items(array(
                'method' => 'list_input_button_set',
            )),
            'desc' => $this->_desc(true),
        ));

        return $this->rendered($this->html($args));

    }

    protected function html( $a ) {
		return sprintf( '<div class="%s">%s</div>%s', $a['class'], $a['options'], $a['desc'] );
	}

    /**
     * Generates html for list item with input
     *
     * @param array $args Override arguments
     * @param int $i Iterator value
     * @return string       Generated list item html
     * @since  1.1.0
     */
    public function list_input_button_set($args = array(), $i)
    {
        $a = $this->parse_args('list_input', array(
            'type' => 'radio',
            'class' => 'cmb2-ext-buttonset-item',
            'name' => $this->_name(),
            'id' => $this->_id($i),
            'value' => $this->field->escaped_value(),
            'label' => '',
        ), $args);

        $label_class = 'cmb2-ext-buttonset-label state-default ';
        $label_class .= isset($a['checked']) ? 'selected' : '';

        return sprintf("\t" . '<input%s/> <label class="%s" for="%s"><span class="buttonset-text">%s</span></label>' . "\n", $this->concat_attrs($a, array('label')), $label_class, $a['id'], $a['label']);
    }

}
