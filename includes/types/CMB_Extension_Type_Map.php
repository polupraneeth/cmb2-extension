<?php
/**
 * Class CMB_Extension_Type_Map
 *
 * @since  1.0.3
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: CMB2 MAP (https://github.com/mustardBees/cmb_field_map)
// Special thanks to MustardBees for his awesome work

class CMB_Extension_Type_Map extends CMB2_Type_Text
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
     * @since 1.0.3
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

        $this->field->add_js_dependencies('google-maps-api');

        $latitude = parent::render(array(
            'type'       => 'hidden',
            'id'         => $this->_name() . '_latitude',
            'name'       => $this->_name() . '[latitude]',
            'value'      => isset( $this->value['latitude'] ) ? $this->value['latitude'] : '',
            'class'      => 'cmb-map-latitude',
            'desc'       => '',
        ));

        $longitude = parent::render(array(
            'type'       => 'hidden',
            'id'         => $this->_name() . '_longitude',
            'name'       => $this->_name() . '[longitude]',
            'value'      => isset( $this->value['longitude'] ) ? $this->value['longitude'] : '',
            'class'      => 'cmb-map-longitude',
            'desc'       => '',
        ) );

        return $this->rendered(
            sprintf('<input type="text" class="large-text cmb-map-search" id="%s" /><div class="cmb-map"></div>%s %s %s',$this->_id(), $this->_desc(true),$latitude,$longitude)
        );

    }

}
