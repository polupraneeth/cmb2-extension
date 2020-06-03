<?php
/**
 * Class CMB_Extension_Hookup
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

class CMB_Extension_Hookup extends CMB2_Hookup_Base
{

    /**
     * Only allow JS registration once
     *
     * @var   bool
     * @since 1.0.0
     */
    protected static $js_registration_done = false;

    /**
     * Only allow CSS registration once
     *
     * @var   bool
     * @since 1.0.0
     */
    protected static $css_registration_done = false;

    public function __construct(CMB2 $cmb)
    {
        parent::__construct($cmb);
    }

    /**
     * Initial hook up
     *
     * @param CMB2 $cmb
     * @return bool|CMB_Extension_Hookup
     * @since 1.0.0
     */
    public static function maybe_init_and_hookup(CMB2 $cmb)
    {
        CMB_Extension::$cmb2 = &$cmb;

        if ($cmb->prop('hookup')) {
            $hookup = new self($cmb);
            return $hookup->universal_hooks();
        }

        return false;
    }

    /**
     * Hooks to load scripts and extend cmb2 hooks functionality
     *
     * @since 1.0.0
     */
    public function universal_hooks()
    {
        if (is_admin()) {
            $this->once('admin_enqueue_scripts', array(__CLASS__, 'register_scripts'), 8);
            $this->once('admin_enqueue_scripts', array($this, 'do_scripts'));
        }

        $this->extend_fields();

        $this->object_type = CMB_Extension::ext_current_object_type($this->object_type);
        switch ($this->object_type) {
            case 'widgets':
                return $this->widget_hooks();
        }

        return $this;
    }

    /**
     * Add CMB2 Extension fields
     *
     * @since 1.0.0
     */
    protected function extend_fields()
    {
        new CMB_Extension_Types(CMB_Extension::$cmb2);
    }

    /**
     * Hook up fields to widgets
     *
     * @since 1.0.0
     */
    public function widget_hooks()
    {
        return $this;
    }

    /**
     * Registers scripts and styles for CMB Extension
     *
     * @since  1.0.0
     */
    public static function register_scripts()
    {
        self::register_styles();
        self::register_js();
    }

    /**
     * Registers styles for CMB Extension
     *
     * @since 1.0.0
     */
    protected static function register_styles()
    {
        if (self::$css_registration_done) {
            return;
        }

        // Only use minified files if SCRIPT_DEBUG is off.
        $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        $front = is_admin() ? 'admin/admin-' : 'site/front-';
        $rtl = is_rtl() ? '-rtl' : '';

        self::vendor_css();

        /**
         * Filters the registered style dependencies for the cmb2 stylesheet.
         *
         * @param array $dependencies The registered style dependencies for the cmb2 stylesheet.
         */
        $dependencies = apply_filters('cmb2_ext_style_dependencies', array('cmb2-styles'));
        wp_register_style('cmb2-ext-styles', CMB_Extension::url("assets/css/{$front}style{$rtl}{$min}.css"), $dependencies);
        wp_register_style('cmb2-ext-global', CMB_Extension::url("assets/css/global/style{$rtl}{$min}.css"), $dependencies);
        //wp_register_style('cmb2-ext-display-styles', CMB_Extension::url("assets/css/cmb2-ext-display{$rtl}{$min}.css"), $dependencies);

        self::$css_registration_done = true;
    }

    /**
     * Registers vendor css for CMB Extension
     *
     * @since  1.0.0
     */
    protected static function vendor_css()
    {
        //select2
        wp_register_style('select2', CMB_Extension::url('assets/css/vendor/select2.min.css'), array(), CMB2_EXTENSION_VERSION);

        //animate.css
        wp_register_style('animate', CMB_Extension::url('assets/css/vendor/animate.min.css'), array(), CMB2_EXTENSION_VERSION);

        //Icon Picker
        wp_register_style('jqueryfontselectormain', 'https://unpkg.com/@fonticonpicker/fonticonpicker/dist/css/base/jquery.fonticonpicker.min.css', array(), CMB2_EXTENSION_VERSION);
        wp_register_style('jqueryfontselector', 'https://unpkg.com/@fonticonpicker/fonticonpicker/dist/css/themes/grey-theme/jquery.fonticonpicker.grey.min.css', array(), CMB2_EXTENSION_VERSION);

        //FontAwesome 5
        wp_register_style('fontawesome5', 'https://use.fontawesome.com/releases/v5.7.2/css/fontawesome.css', array('jqueryfontselector'), CMB2_EXTENSION_VERSION, 'all');
        wp_add_inline_style('fontawesome5', '.fip-icons-container i.fas{font-family: "Font Awesome 5 Free" !important;} .selected-icon i.fas{font-family: "Font Awesome 5 Free" !important;}');
        wp_register_style('fontawesome5solid', 'https://use.fontawesome.com/releases/v5.7.2/css/solid.css', array('jqueryfontselector'), CMB2_EXTENSION_VERSION, 'all');
        wp_register_style('fontawesome5brands', 'https://use.fontawesome.com/releases/v5.7.2/css/brands.css', array('jqueryfontselector'), CMB2_EXTENSION_VERSION, 'all');
        wp_add_inline_style('fontawesome5brands', '.fip-icons-container i.fab{font-family: "Font Awesome 5 Brands" !important;} .selected-icon i.fab{font-family: "Font Awesome 5 Brands" !important;}');

        //FontAwesome 4
        $asset_path = apply_filters('cmb2_ext_font_icon_asset_path', CMB_Extension::url());
        wp_register_style('fontawesome', $asset_path . 'assets/css/vendor/font-awesome.min.css', array('jqueryfontselector'), CMB2_EXTENSION_VERSION);

        //Jquery Ui
        wp_register_style( 'jquery-ui', '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css', array(), '1.0' );

    }

    /**
     * Registers scripts for CMB Extension
     *
     * @since  1.0.0
     */
    protected static function register_js()
    {
        if (self::$js_registration_done) {
            return;
        }

        add_action('cmb2_footer_enqueue', array('CMB_Extension_JS', 'enqueue'));

        self::$js_registration_done = true;
    }

    /**
     * Enqueues scripts and styles for CMB2 Extension in admin_head.
     *
     * @param string $hook Current hook for the admin page.
     * @since 1.0.0
     *
     */
    public function do_scripts($hook)
    {
        $hooks = array(
            'post.php',
            'post-new.php',
            'page-new.php',
            'page.php',
            'comment.php',
            'edit-tags.php',
            'term.php',
            'user-new.php',
            'profile.php',
            'user-edit.php',
        );

        // only pre-enqueue our scripts/styles on the proper pages
        // show_form_for_type will have us covered if we miss something here.
        if (in_array($hook, $hooks, true)) {

            self::enqueue_cmb_css();
            self::enqueue_cmb_js();

        }
    }

    /**
     * Includes CMB2 Extension styles.
     *
     * @param string $handle CSS handle.
     * @return bool | void
     * @since 1.0.0
     */
    public static function enqueue_cmb_css($handle = 'cmb2-ext-styles')
    {

        /**
         * Filter to determine if CMB2'S css should be enqueued.
         *
         * @param bool $enqueue_css Default is true.
         */
        if (!apply_filters('cmb2_enqueue_css', true)) {
            return false;
        }

        self::register_styles();

        /*
         * White list the options as this method can be used as a hook callback
         * and have a different argument passed.
         */
        switch ($handle) {
            case "cmb2-ext-display-styles":
                wp_enqueue_style('cmb2-ext-display-styles');
                break;
            default:
                wp_enqueue_style('cmb2-ext-styles');
                wp_enqueue_style('cmb2-ext-global');
        }

    }

    /**
     * Includes CMB2 Extension JS.
     *
     * @since  1.0.0
     */
    public static function enqueue_cmb_js()
    {

        /**
         * Filter to determine if CMB2'S JS should be enqueued.
         *
         * @param bool $enqueue_js Default is true.
         */
        if (!apply_filters('cmb2_enqueue_js', true)) {
            return false;
        }

        self::register_js();
        return true;
    }

}
