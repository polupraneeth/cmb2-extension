<?php
/**
 * Class CMB_Extension_Type_Location
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

// This plugin is based on CMB2 Field Type: Google Maps (https://github.com/mustardBees/cmb_field_map)
// Special thanks to Phil Wylie (http://www.philwylie.co.uk/) for his awesome work

class CMB_Extension_Type_Location extends CMB2_Type_Text
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

    public function render($args = array())
    {

        // Get the Google API key from the field's parameters.
        $api_key = $this->field->args('api_key');
        // Allow a custom hook to specify the key.
        $api_key = $this->google_api_key_constant($api_key);

        $this->setup_admin_scripts($api_key);
        //$this->field->add_js_dependencies('google-maps-api');

        $input = parent::render(array(
            'type' => 'text',
            'id' => $this->_id(),
            'class' => 'large-text pw-map-search',
            'desc' => '',
        ));

        $input_latitude = parent::render(array(
            'type' => 'hidden',
            'name' => $this->_name() . '[latitude]',
            'value' => isset($this->value['latitude']) ? $this->value['latitude'] : '',
            'class' => 'pw-map-latitude',
            'desc' => '',
        ));

        $input_longitude = parent::render(array(
            'type' => 'hidden',
            'name' => $this->field->args('_name') . '[longitude]',
            'value' => isset($this->value['longitude']) ? $this->value['longitude'] : '',
            'class' => 'pw-map-longitude',
            'desc' => '',
        ));

        return $this->rendered(
            sprintf('%s<div class="pw-map"></div>%s %s %s', $input, $input_latitude, $input_longitude, $this->_desc(true))
        );
    }

    /**
     * Enqueue scripts and styles.
     */
    public function setup_admin_scripts($api_key)
    {
        wp_register_script('google-maps-api', "https://maps.googleapis.com/maps/api/js?key={$api_key}&libraries=places", null, null);
    }

    /**
     * Default filter to return a Google API key constant if defined.
     */
    public function google_api_key_constant($google_api_key = null)
    {
        // Allow the field's 'api_key' parameter or a custom hook to take precedence.
        if (!empty($google_api_key)) {
            return $google_api_key;
        }
        if (defined('PW_GOOGLE_API_KEY')) {
            $google_api_key = PW_GOOGLE_API_KEY;
        }
        return $google_api_key;
    }

}
