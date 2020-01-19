<?php
/**
 * Class CMB_Extension_Type_Icon_Select
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Icon Font (https://github.com/serkanalgur/cmb2-field-faiconselect)
// Special thanks to Serkan Algur for his awesome work

class CMB_Extension_Type_Icon_Select extends CMB2_Type_Select
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
        parent::__construct($types, array());
        $this->value = is_array($args) && !is_null($args[1]) ? $args[1] : $this->value;
    }

    /**
     * Render field
     */
    public function render($args = array())
    {
        $this->load_css($this->field);

        $this->field->add_js_dependencies('jqueryiconselector');

        return $this->types->select(array(
            'class' => 'cmb-ext-iconselect',
            'options' => '<option></option>' . $this->types->concat_items(),
            'desc' => $this->_desc(true),
        ));

    }

    public function load_css($field)
    {

        $font_args = $field->args('attributes', 'fatype');
        $font_awesome_ver = $field->args('attributes', 'faver');

        if ($font_awesome_ver && $font_awesome_ver === 5) {
            wp_enqueue_style('fontawesome5');
            wp_enqueue_style('fontawesome5solid');
            wp_enqueue_style('fontawesome5brands');
        } else {
            wp_enqueue_style('fontawesome');
        }

    }

}
