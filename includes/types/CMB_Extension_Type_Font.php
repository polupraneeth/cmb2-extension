<?php
/**
 * Class CMB_Extension_Type_Font
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Font (https://github.com/rubengc/cmb2-field-font)
// Special thanks to Rubengc for his awesome work

class CMB_Extension_Type_Font extends CMB2_Type_Text
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
        parent::__construct($types, $args);
        $this->value = is_array($args) && !is_null($args[1]) ? $args[1] : $this->value;
    }

    /**
     * Render field
     */
    public function render($args = array())
    {
        // Parse args
        $attrs = $this->parse_args('select', array(
            'class' => 'cmb2_select',
            'name' => $this->_name(),
            'id' => $this->_id(),
            'data-selected' => $this->value,
            'data-placeholder' => ($this->field->args('placeholder') ? $this->field->args('placeholder') : '')
        ));

        $this->field->add_js_dependencies('higooglefonts');
        $preview = $this->field->args('preview') ? '<p class="font-preview">Preview</p>' : '';

        echo $this->rendered(
            sprintf('<div class="cmb-ext-font"><select%s></select>%s %s</div>',
                $this->field->concat_attrs($attrs),
                $preview,
                $this->_desc(true))
        );

    }

}
