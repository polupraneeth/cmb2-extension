<?php
/**
 * Class CMB_Extension_Type_Image_Select
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: Image Select(https://www.proy.info/how-to-create-cmb2-image-select-field-type/)

class CMB_Extension_Type_Image_Select extends CMB2_Type_Multi_Base
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

        $args = $this->parse_args('image_select', array(
            'id' => 'cmb-image-select' . $this->_id(),
            'class' => 'cmb-image-select-list',
            'options' => $this->concat_items(array(
                'method' => 'list_input_img',
            )),
            'desc' => $this->_desc(true),
        ));
        return $this->rendered($this->ul($args));

    }

    protected function ul($a)
    {
        return sprintf('<ul class="%s">%s</ul>%s', $a['class'], $a['options'], $a['desc']);
    }

    /**
     * Generates html for list item with input
     *
     * @param array $args Override arguments
     * @param int $i Iterator value
     * @return string       Gnerated list item html
     * @since  1.1.0
     */
    public function list_input_img($args = array(), $i)
    {
        $a = $this->parse_args('list_input_img', array(
            'type' => 'radio',
            'class' => 'cmb2-option',
            'name' => $this->_name(),
            'id' => $this->_id($i),
            'value' => $this->field->escaped_value(),
            'label' => '',
        ), $args);

        $li_class = 'cmb-image-select ';
        $li_class .= isset($a['checked']) ? 'cmb-image-select-selected' : '';

        $img = sprintf('<img style=" width: auto; " %s><br>', $this->concat_attrs($a['label']));
        $title = is_array($a['label']) & isset($a['label']['title']) ? $a['label']['title'] : "";
        return $this->rendered(
            sprintf("\t" . '<li class="%s"><label for="%s"><input%s/>%s<span></span></label></li>' . "\n", $li_class, $a['id'], $this->concat_attrs($a, array('label')), $img, esc_html($title))
        );
    }
}
