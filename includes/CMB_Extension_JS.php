<?php
/**
 * Class CMB_Extension_JS
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    polupraneeth
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_JS extends CMB2_JS
{

    /**
     * The CMB2 Extension JS handle
     *
     * @var   string
     * @since 1.0.0
     */
    protected static $handle = 'cmb2-ext-scripts';

    /**
     * The CMB2 Extension JS variable name
     *
     * @var   string
     * @since 1.0.0
     */
    protected static $js_variable = 'cmb2_ext_l10';

    /**
     * Enqueue the CMB Extension JS
     *
     * @since  1.0.0
     */
    public static function enqueue()
    {

        // Filter required script dependencies.
        $dependencies = self::$dependencies;
        // Only use minified files if SCRIPT_DEBUG is off.
        $debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

        $min = $debug ? '' : '.min';

        // if jquery autocomplete.
        if (isset($dependencies['jquery-autocomplete-ajax-search'])) {
            self::ajax_search();
        }

        //font icon selector
        if (isset($dependencies['jqueryiconselector'])) {
            self::icon_selector();
        }

        // Enqueue cmb JS.
        wp_enqueue_script(self::$handle, CMB_Extension::url("assets/js/script{$min}.js"), $dependencies, CMB2_EXTENSION_VERSION, true);

        self::localize($debug);

        do_action('cmb2_ext_footer_enqueue');

    }

    /**
     * Register or enqueue the jquery-ui-autocomplete script.
     *
     * @param boolean $enqueue Whether or not to enqueue.
     *
     * @return void
     * @since  1.0.0
     *
     */
    public static function ajax_search($enqueue = false)
    {
        $func = $enqueue ? 'wp_enqueue_script' : 'wp_register_script';
        $func('jquery-autocomplete-ajax-search', CMB_Extension::url('assets/js/vendore/jquery.autocomplete.min.js'), array('jquery'), CMB2_EXTENSION_VERSION);
    }

     /**
     * https://github.com/micc83/fontIconPicker
     * @param boolean $enqueue Whether or not to enqueue.
     *
     * @return void
     * @since  1.0.0
     *
     */
    public static function icon_selector($enqueue = false)
    {
        $func = $enqueue ? 'wp_enqueue_script' : 'wp_register_script';
        $func('jqueryiconselector', 'https://unpkg.com/@fonticonpicker/fonticonpicker/dist/js/jquery.fonticonpicker.min.js',  array('jquery'), CMB2_EXTENSION_VERSION, true);
    }

    /**
     * https://select2.org/
     * @param boolean $enqueue Whether or not to enqueue.
     *
     * @return void
     * @since  1.0.0
     *
     */
    public static function select2($enqueue = false)
    {
        $func = $enqueue ? 'wp_enqueue_script' : 'wp_register_script';
        $func('select2', CMB_Extension::url('assets/js/vendore/select2.full.min.js'),  array('jquery'), CMB2_EXTENSION_VERSION, true);
    }

    /**
     * Localize the php variables for CMB Extension JS
     *
     * @param $debug
     * @since  1.0.0
     */
    protected static function localize($debug)
    {
        static $localized = false;
		if ( $localized ) {
			return;
		}

		$localized = true;
        $l10n = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cmb_ajax_search_get_results'),
            'options' => apply_filters('cmb_field_ajax_search_autocomplete_options', array())
        );
        wp_localize_script( self::$handle, self::$js_variable , apply_filters( 'cmb2_ext_localized_data', $l10n ) );
    }

}
