<?php
/**
 * CMB2 Extension.
 *
 * @package     WordPress\Plugins\CMB2 Extension
 * @author      Praneeth Polu <contact@polupraneeth.me>
 * @link        https://polupraneeth.me
 * @version     1.0.3
 *
 * @copyright   2019 StackAdroit
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name:       CMB2 Extension
 * Plugin URI:        https://github.com/polupraneeth/cmb2-extensions
 * Description:       CMB2 Extension is to extend cmb2 plugin functionality and organize it's Add-ons at one place.
 * Author:            Praneeth Polu <contact@polupraneeth.me>
 * Author URI:        https://polupraneeth.me
 * Github Plugin URI: https://github.com/polupraneeth/cmb2-extensions
 * Github Branch:     master
 * Version:           1.0.3
 * License:           GPL v3
 *
 * Copyright (C) 2019, StackAdroit - contact@polupraneeth.me
 *
 * GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/3.0/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!class_exists('CMB2_Extension_Bootstrap', false)) {

    class CMB2_Extension_Bootstrap
    {
        /**
         * Current version number
         *
         * @const string
         * @since 1.0.0
         */
        const VERSION = '1.0.3';

        /**
         * Current version hook priority.
         * Will decrement with each release
         *
         * @var   int
         * @since 1.0.0
         */
        const PRIORITY = 9998;

        /**
         * Single instance of the CMB2_Extension_Bootstrap object
         *
         * @var CMB2_Extension_Bootstrap
         * @since 1.0.0
         */
        public static $single_instance = null;

        /**
         * Single instance of the CMB_Extension object
         *
         * @var CMB_Extension
         * @since 1.0.0
         */
        public $obj = null;

        /**
         * Plugin slug
         *
         * @var string
         * @since 1.0.0
         */
        public $slug = 'cmb_ext';

        /**
         * Initialize the hooking
         *
         * @since 1.0.0
         */
        public function __construct()
        {

            // define constants
            if (!defined('CMB2_EXTENSION')) {
                /**
                 * A constant you can use to check if CMB2_EXTENSION is loaded
                 * for your plugins/themes with CMB2_EXTENSION dependency.
                 *
                 * Can also be used to determine the priority of the hook
                 * in use for the currently loaded version.
                 */
                define('CMB2_EXTENSION', self::PRIORITY);
            }

            /**
             * Check if CMB2 Extension is Compatible
             */
            add_action('admin_notices', array($this, 'admin_notice'));


            // Use the hook system to ensure only the newest version is loaded.
            add_action('cmb2_extension_load', array($this, 'init_extension'), self::PRIORITY);

            add_action('cmb2_init', array($this, 'do_hook'));

            add_action('cmb2_init_before_hookup', array($this, 'cmb2_ext_init_hookup'));
        }

        /**
         * Creates/returns the single instance CMB2_Extension_Bootstrap object
         *
         * @return CMB2_Extension_Bootstrap Single instance object
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
         * Fires the CMB2_Extension_Bootstrap action hook
         * (from the cmb2_init hook).
         *
         * @since 1.0.0
         */
        public function do_hook()
        {
            // Then fire our hook.
            do_action('cmb2_extension_load');
        }

        /**
         * Extension hookups into cmb2
         *
         * @since  1.0.0
         */
        public function cmb2_ext_init_hookup()
        {
            foreach (CMB2_Boxes::get_all() as $cmb) {
                // Hook in the hookup... how meta.
                add_action("cmb2_init_hookup_{$cmb->cmb_id}", array('CMB_Extension_Hookup', 'maybe_init_and_hookup'));

                // Hook in the rest api functionality.
                //add_action( "cmb2_init_hookup_{$this->cmb_id}", array( 'CMB_Extension_REST', 'maybe_init_and_hookup' ) );
            }
        }

        /**
         * Bootload the extension
         *
         * @throws Exception
         * @since  1.0.0
         */
        public function init_extension()
        {
            //A final check if CMB2_Extension_Bootstrap exists before kicking off our CMB2_Extension_Bootstrap loading.
            if (class_exists('CMB2_Extension_Bootstrap', false)) {
                //return;
            }
            if (!defined('CMB2_EXTENSION_VERSION')) {
                //Defines the currently loaded version of CMB2_Extension_Bootstrap.
                define('CMB2_EXTENSION_VERSION', self::VERSION);
            }
            if (!defined('CMB2_EXTENSION_DIR')) {
                // Defines the Directory which is used to load local resources.
                define('CMB2_EXTENSION_DIR', trailingslashit(dirname(__FILE__)));
            }

            // bail early if too early
            // ensures all plugins have a chance to add fields, etc
            //if( !did_action('plugins_loaded') ) return;

            //multilingual support
            $this->l10ni18n();

            //Load dependencies
            $this->load_dependency();

            //Now kick off the class autoloader.
            spl_autoload_register('cmb2_extension_autoload_classes');

            //Initialize CMB_Extension class
            $this->obj = CMB_Extension::get_instance();
        }

        /**
         * Get CMB2 Extension directory path
         *
         * @since 1.0.0
         */
        public function cmb2_ext_dir($path = '')
        {
            if (CMB2_EXTENSION_DIR) {
                return CMB2_EXTENSION_DIR . $path;
            }
            return $path;
        }

        /**
         * Load dependencies
         *
         * @since 1.0.0
         */
        protected function load_dependency()
        {
            // Include helper functions.
            require_once(CMB2_EXTENSION_DIR . 'includes/helper-functions.php');
        }

        /**
         * Registers CMB2_Extension_Bootstrap text domain path
         *
         * @since  1.0.0
         */
        protected function l10ni18n()
        {

            $loaded = load_plugin_textdomain('cmb-ext', false, '/languages/');

            if (!$loaded) {
                $loaded = load_muplugin_textdomain('cmb-ext', '/languages/');
            }

            if (!$loaded) {
                $loaded = load_theme_textdomain('cmb-ext', get_stylesheet_directory() . '/languages/');
            }

            if (!$loaded) {
                $locale = apply_filters('plugin_locale', get_locale(), 'cmb-ext');
                $mofile = dirname(__FILE__) . '/languages/cmb-ext-' . $locale . '.mo';
                load_textdomain('cmb-ext', $mofile);
            }

        }

        function admin_notice()
        {
            $class = 'notice notice-error';

            if (version_compare(PHP_VERSION, '5.3', '<') && is_admin() && current_user_can('activate_plugins')) {

                $message = __(' Activation failed: The CMB2 Extension plugin requires PHP 5.3+. Please contact your webhost and ask them to upgrade the PHP version for your webhosting account. ', 'cmb-ext');

                $file = plugin_basename(__FILE__);
                if (is_plugin_active(plugin_basename($file))) {

                    deactivate_plugins($file, false, is_network_admin());

                    // Add to recently active plugins list.
                    if (!is_network_admin()) {
                        update_option('recently_activated', array($file => time()) + (array)get_option('recently_activated'));
                    } else {
                        update_site_option('recently_activated', array($file => time()) + (array)get_site_option('recently_activated'));
                    }

                    // Prevent trying again on page reload.
                    if (isset($_GET['activate'])) {
                        unset($_GET['activate']);
                    }

                }

            }

            if (!defined('CMB2_LOADED')) {

                $message = __(' CMB2 Extensions is a dependent plugin of CMB2, in order to work please install and activate CMB2 Plugin', 'cmb-ext');
            }

            if (!empty($message)) {
                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
            }


        }

        /**
         * Non-existent fallback to checking for CMB_Extension object methods
         *
         * @since  1.0.0
         */
        public function __call($name, $arguments)
        {
            return $this->obj->{$name()}($arguments);
        }

    }

    /*
    *  The main function responsible for returning the one true cmb2_ext Instance to functions everywhere.
    *  Use this function like you would a global variable, except without needing to declare the global.
    *
    *  Example: <?php $ext = cmb_ext(); ?>
    *
    */
    function cmb_ext()
    {

        // globals
        global $cmb_ext;

        // initialize
        if (!isset($cmb_ext)) {
            $cmb_ext = CMB2_Extension_Bootstrap::get_instance();;
        }
        // return
        return $cmb_ext;

    }

    // initialize
    cmb_ext();
}

// class_exists check