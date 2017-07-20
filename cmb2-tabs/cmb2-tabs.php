<?php
/**
 * CMB2 Tabs.
 *
 * @package     WordPress\Plugins\CMB2 Tabs
 * @author      Praneeth Polu <contact@polupraneeth.me>
 * @link        https://github.com/polupraneeth/cmb2-extensions
 * @version     1.0.0
 *
 * @copyright   2017 Praneeth Polu
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name:       CMB2 Tabs
 * Plugin URI:        https://github.com/polupraneeth/cmb2-extensions
 * Description:       CMB2 Tabs is an extenstion for CMB2 which allow you to oragnize fields into tabs.
 * Author:            Praneeth Polu <contact@polupraneeth.me>
 * Author URI:        https://github.com/polupraneeth
 * Github Plugin URI: https://github.com/polupraneeth/cmb2-extensions
 * Github Branch:     master
 * Version:           1.0.0
 * License:           GPL v3
 *
 * Copyright (C) 2017, Praneeth Polu - contact@polupraneeth@gmail.com
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


// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('CMB2_Tabs')) {
    /**
     * Class CMB2_Tabs
     */
    class CMB2_Tabs {

        /**
         * Priority on which our actions are hooked in.
         *
         * @const int
         */
        const PRIORITY = 99999;

        /**
         * Current version number
         */
        const VERSION = '1.0.0';

        /**
         * The url which is used to load local resources
         */
        protected static $url = '';
        
        /**
         * Current CMB2 instance
         */
        protected $cmb = '';

        /**
         * Indicate that the instance of the class is working on a meta box that has tabs or not
         * It will be set 'true' BEFORE meta box is display and 'false' AFTER
         */
        public $active = false;

        /**
         * Active Panel
         */
        public $active_panel = '';

        /**
         * Deactive Conditional tabs "show_on_cb"
         */
        public $conditional = array();

        /**
         * Store all output of fields
         * This is used to put fields in correct <div> for tabs
         */
        public $fields_output = array();

        /**
         * Initialize the hooking into CMB2
         */
        public function __construct() {

            add_action('cmb2_before_form', array($this, 'opening_div'), 10, 4);
            add_action('cmb2_after_form', array($this, 'closing_div'), 20, 4);

            add_action('cmb2_before_form', array($this, 'render_nav'), 20, 4);
            add_action('cmb2_after_form', array($this, 'show_panels'), 10, 4);

            add_filter('cmb2_wrap_classes', array($this, 'panel_wraper_class'), 10, 2);
            add_filter('cmb_output_html_row', array($this, 'capture_fields'), 10, 3);
        }

        /**
         * Display opening div for tabs for meta box
         *
         */
        public function opening_div($cmb_id, $object_id, $object_type, $cmb)
        {
            if (!$cmb->prop("tabs")) {
                return;
            }

            $tab_style = $cmb->prop("tab_style");
            $class = 'cmb-tabs clearfix';

            if (isset($tab_style) && 'default' != $tab_style) {
                $class .= ' cmb-tabs-'.$tab_style;
            }

            echo '<div class="'.$class.'">';

            // Current cmb2 instance
            $this->cmb = $cmb;
            // Set 'true' to let us know that we're working on a meta box that has tabs
            $this->active = true;
            //setup style and script for tabs
            $this->setup_admin_scripts();
        }

        /**
         * Display closing div for tabs for meta box
         */
        public function closing_div()
        {
            if (!$this->active) {
                return;
            }

            echo '</div>';

            // Reset to initial state to be ready for other meta boxes
            $this->active        = false;
            $this->fields_output = array();
        }

        /**
         * Render Nav
         */
        public function render_nav($cmb_id, $object_id, $object_type, $cmb) 
        {
           
            $tabs = $cmb->prop("tabs");

            if ($tabs) {

                echo '<ul class="cmb-tab-nav">';
                $active_nav = true;

                foreach ($tabs as $key => $tab_data)
                {

                        if (is_string($tab_data))
                        {
                            $tab_data = array('label' => $tab_data);
                        }

                        $tab_data = wp_parse_args($tab_data, array(
                            'icon'  => '',
                            'label' => '',
                            'show_on_cb' => null,
                        ));

                        if ($tab_data['show_on_cb'] && $this->do_callback($tab_data['show_on_cb'])) {
                            $this->conditional[] = $key;
                            continue;
                        }  

                        //set icon defult it it's emty 
                        $tab_data['icon'] = $tab_data['icon'] ? $tab_data['icon'] : "dashicons-admin-post";
                        
                        // If icon is URL to image
                        if (filter_var($tab_data['icon'], FILTER_VALIDATE_URL))
                        {
                            $icon = '<img src="'.$tab_data['icon'].'">';
                        }
                        // If icon is icon font
                        else
                        {
                            // If icon is dashicon, auto add class 'dashicons' for users
                            if (false !== strpos($tab_data['icon'], 'dashicons'))
                            {
                                $tab_data['icon'] .= ' dashicons';
                            }
                            // Remove duplicate classes
                            $tab_data['icon'] = array_filter(array_map('trim', explode(' ', $tab_data['icon'])));
                            $tab_data['icon'] = implode(' ', array_unique($tab_data['icon']));

                            $icon = $tab_data['icon'] ? '<i class="'.$tab_data['icon'].'"></i>' : '';
                        }

                        $class = "cmb-tab-$key";
                        if ($active_nav) {
                            $class .= ' cmb-tab-active';
                            $this->active_panel = $key;
                            $active_nav = false;
                        }

                        printf(
                            '<li class="%s" data-panel="%s"><a href="#">%s<span>%s</span></a></li>',
                            $class,
                            $key,
                            $icon,
                            $tab_data['label']
                        );
                }

                    echo '</ul>';
            
            }
        }

        public function panel_wraper_class($classes, $box) 
        {

            if ($this->active) {
                $classes[] = 'cmb-tabs-panel';
            }
            if ($this->active && $this->fields_output) {
                $classes[] = 'cmb2-wrap-tabs';
            }

            return array_unique($classes);
        }
        
        public static function tabs_render_row_cb($field_args, $field) 
        {

            // Ok, callback is good, let's run it and store the result.
            ob_start();
                
                // If field is requesting to not be shown on the front-end
                if (!is_admin() && !$this->args('on_front')) {
                    return;
                }

                // If field is requesting to be conditionally shown
                if (!$field->should_show()) {
                    return;
                }

                $field->peform_param_callback('before_row');

                printf("<div class=\"cmb-row %s\" data-fieldtype=\"%s\">\n", $field->row_classes(), $field->type());

                if (!$field->args('show_names')) {
                    echo "\n\t<div class=\"cmb-td\">\n";

                    $field->peform_param_callback('label_cb');

                } else {

                    if ($field->get_param_callback_result('label_cb')) {
                        echo '<div class="cmb-th">', $field->peform_param_callback('label_cb'), '</div>';
                    }

                    echo "\n\t<div class=\"cmb-td\">\n";
                }

                $field->peform_param_callback('before');

                $types = new CMB2_Types($field);
                $types->render();

                $field->peform_param_callback('after');

                echo "\n\t</div>\n</div>";

                $field->peform_param_callback('after_row');


            // Grab the result from the output buffer and store it.
            $echoed = ob_get_clean();

            $outer_html = $echoed ? $echoed : $returned;

            $outer_html = apply_filters('cmb_output_html_row', $outer_html, $field_args, $field);            

            echo $outer_html;
        }

        /**
         * Display tab navigation for meta box
         * Note that: this public function is hooked to 'cmb2_after_form', when all fields are outputted
         * (and captured by 'capture_fields' public function)
         */
        public function show_panels($cmb_id, $object_id, $object_type, $cmb)
        {
            if (!$this->active) {return; }

            echo '<div class="', esc_attr($cmb->box_classes()), '"><div id="cmb2-metabox-', sanitize_html_class($cmb_id), '" class="cmb2-metabox cmb-field-list">';

                foreach ($this->fields_output as $tab => $fields)
                {   
                    if (!in_array($tab, $this->conditional, TRUE)) {
                            $active_panel = $this->active_panel == $tab ? "show" : "";
                            echo '<div class="'.$active_panel.' cmb-tab-panel cmb-tab-panel-'.$tab.'">';
                            echo implode('', $fields);
                            echo '</div>';
                    }
                }

            echo '</div></div>';
        }


        /**
         * Save field output into class variable to output later
         */
        public function capture_fields($output, $field_args, $field)
        {
            // If meta box doesn't have tabs, do nothing
            if (!$this->active || !isset($field_args['tab'])) { return $output; }

            $tab = $field_args['tab'];

            if (!isset($this->fields_output[$tab])) {
                $this->fields_output[$tab] = array();
            }
            $this->fields_output[$tab][] = $output;

            // Return empty string to let Meta Box plugin echoes nothing
            return '';
        }

    
        /**
         * Enqueue scripts and styles
         */
        public function setup_admin_scripts() {
            
            wp_register_script('cmb-tabs-js', self::url('js/tabs.js'), array('jquery'), self::VERSION);
            wp_enqueue_script('cmb-tabs-js');

            wp_enqueue_style('cmb2-tabs-style', self::url('css/tabs.css'), array(), self::VERSION);
            wp_enqueue_style('cmb2-tabs-style');

        }

        /**
         * Defines the url which is used to load local resources. Based on, and uses, 
         * the CMB2_Utils class from the CMB2 library.
         */
        public static function url($path = '') {
            if (self::$url) { return self::$url.$path; }

            /**
             * Set the variable cmb2_tabs_dir
             */
            $cmb2_tabs_dir = trailingslashit(dirname(__FILE__));

            /**
             * Use CMB2_Utils to gather the url from cmb2_tabs_dir
             */ 
            $cmb2_tabs_url = CMB2_Utils::get_url_from_dir($cmb2_tabs_dir);

            /**
             * Filter the CMB2 FPSA location url
             */
            self::$url = trailingslashit(apply_filters('cmb2_tabs_url', $cmb2_tabs_url, self::VERSION));

            return self::$url.$path;
        }

        /**
         * Handles metabox property callbacks, and passes this $cmb object as property.
         */
        protected function do_callback($cb) {
            return call_user_func($cb, $this->cmb, $this);
        }


    }

    $cmb2_tabs = new CMB2_Tabs();
}
