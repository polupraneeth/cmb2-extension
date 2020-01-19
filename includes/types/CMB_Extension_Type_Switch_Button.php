<?php
/**
 * Class CMB_Extension_Type_Switch_Button
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 Switch Button (https://github.com/themevan/CMB2-Switch-Button/)
// Special thanks to ThemeVan for his awesome work

class CMB_Extension_Type_Switch_Button extends CMB2_Type_Text
{

    /**
     * The optional value for the Switch field
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
        $args = array(
            'type' => 'checkbox',
            'name' => $this->_name(),
            'value' => 'on',
            'desc' => ''
        );
        if ($this->value == 'on') {
            $args['checked'] = 'checked';
        }

        $print_args = $this->parse_args( $this->type, array(
			'input_field'   => parent::render($args),
			'desc' => $this->_desc( true ),
		) );

        return $this->rendered( $this->html( $print_args ) );
    }

    protected function html( $args ) {
		return sprintf( '<label class="cmb-ext-switch">%s<span class="cmb-ext-slider round"></span></label>%s', $args['input_field'], $args['desc'] );
	}

}
