<?php
/**
 * Class CMB_Extension
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth Polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension
{

    /**
     * Single instance of the CMB_Extension object
     *
     * @var CMB_Extension
     * @since 1.0.0
     */
    public static $single_instance = null;

    /**
     * CMB2 object
     *
     * @var   CMB2
     * @since 1.0.0
     */
    public static $cmb2 = null;

    /**
     * The url which is used to load local resources
     *
     * @var string
     * @since 1.0.0
     */
    public static $url = '';

    /**
     * Initialize the plugin by hooking into CMB2
     */
    protected function __construct()
    {
        $init = new CMB_Extension_Int();
        $init->hookup();
    }

    /**
     * Creates or returns an instance of this class.
     *
     * @return CMB_Extension A single instance of this class.
     * @since  1.0.0
     */
    public static function get_instance()
    {
        if (null === self::$single_instance) {
            self::$single_instance = new self();
        }

        return self::$single_instance;
    }

    /**
     * Defines the url which is used to load local resources. Based on, and uses,
     * the CMB2_Utils class from the CMB2 library.
     *
     * @param string $path
     * @return string
     * @since 1.0.0
     */
    public static function url($path = '')
    {
        if (self::$url) {
            return self::$url . $path;
        }

        //Set the variable to cmb2_ext_url
        $cmb2_ext_url = trailingslashit(dirname(dirname(__FILE__)));

        // Use CMB2_Utils to gather the url from cmb2_ext_url
        $cmb2_ext_url = CMB2_Utils::get_url_from_dir($cmb2_ext_url);

        //Filter the CMB2 FPSA location url
        self::$url = trailingslashit(apply_filters('cmb2_ext_url', $cmb2_ext_url, CMB2_EXTENSION_VERSION));

        return self::$url . $path;
    }

    /**
     * Display opening div for wrap meta box
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function opening_div($cmb_id, $object_id, $object_type, $cmb)
    {
        do_action('cmb2_ext_opening_div', $cmb_id, $object_id, $object_type, $cmb);
    }

    /**
     * Display closing div for wrap meta box
     *
     * @param $cmb_id
     * @param $object_id
     * @param $object_type
     * @param $cmb
     * @since 1.0.0
     */
    public function closing_div($cmb_id, $object_id, $object_type, $cmb)
    {
        do_action('cmb2_ext_closing_div', $cmb_id, $object_id, $object_type, $cmb);
    }

    /**
     * Get the object type for the current page, based on the $pagenow global.
     *
     * @param string $fallback
     * @return string  Page object type name.
     * @since  1.0.0
     */
    public static function ext_current_object_type($fallback = '')
    {
        global $pagenow;
		if ( in_array( $pagenow, array( 'widgets.php'), true ) ) {
			$type = 'widgets';
		}else{
		    $type = $fallback;
        }
        return $type;
    }

}
