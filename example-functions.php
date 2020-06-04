<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 Extension directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2_Extension
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     https://github.com/polupraneeth/cmb2-extension
 */


function cmb2_ext_demo_metabox()
{
    $prefix = 'cmb2_ext_demo_';

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_ext_demo = new_cmb2_box(array(
        'id' => $prefix . 'metabox',
        'title' => esc_html__('Test Metabox', 'cmb-ext'),
        'object_types' => array('page'), // Post type
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        'tabs' => array(
            'general' => array(
                'label' => __('General', 'cmb-ext'),
                'icon' => 'dashicons-list-view', // Dashicon
            ),
            'search' => array(
                'label' => __('Search', 'cmb-ext'),
                'icon' => 'dashicons-search', // Custom icon, using image
            ),
            'group' => array(
                'label' => __('Group', 'cmb-ext'),
                'icon' => 'dashicons-buddicons-groups', // Dashicon
            ),
        ),
    ));

    // Content Wrap
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Content Wrap', 'cmb-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb-ext'),
        'id' => $prefix . 'content_wrap',
        'type' => 'content_wrap_style_editor',
        'tab' => 'general',
        // Custom units (units by default are 'px', '%' and 'em'
        'units' => array(
            'px' => 'px',
            '%' => '%',
        )
    ));

    // Switch Button
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Switch Button', 'cmb-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb-ext'),
        'id' => $prefix . 'switch_button',
        'type' => 'switch_button',
        'tab' => 'general',
        'default' => 'off' //If it's checked by default
    ));

    //Image Select
    $image_path = 'https://github.com/polupraneeth/cmb2-extension/blob/master/assets/images/';
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Image Select', 'cmb-ext'),
        'desc' => esc_html__('page layout using image select', 'cmb-ext'),
        'id' => $prefix . 'image_select',
        'type' => 'image_select',
        'tab' => 'general',
        //'default' => 'sidebar-left',
        'options' => array(
            'disabled' => array('title' => 'Full Width', 'alt' => 'Full Width', 'src' => ($image_path ."image-select/layout-fluid.png?raw=true")),
            'sidebar-left' => array('title' => 'Sidebar Left', 'alt' => 'Sidebar Left', 'src' => ($image_path ."image-select/layout-sidebar-left.png?raw=true")),
            'sidebar-right' => array('title' => 'Sidebar Right', 'alt' => 'Sidebar Right', 'src' => ($image_path . "image-select/layout-sidebar-right.png?raw=true")),
        )
    ));

    // Icon Select
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Icon Select', 'cmb-ext'),
        'desc' => esc_html__('Select Font Awesome icon', 'cmb-ext'),
        'id' => $prefix . 'iconselect',
        'type' => 'icon_select',
        'tab' => 'general',
        'options' => array(
            'fab fa-facebook' => 'fa fa-facebook',
            'fab fa-500px' => 'fa fa-500px',
            'fab fa-twitter' => 'fa fa-twitter',
            'fas fa-address-book' => 'fas fa-address-book'
        ),
        'attributes' => array(
            'faver' => 5
        )
    ));

    // Animation
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Test Animation', 'cmb-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb-ext'),
        'id' => $prefix . '_animation',
        'type' => 'animation',
        'tab' => 'general',
        // 'groups'        => array( 'entrances', 'exits' ), // By default all groups are enabled
        'preview' => true, // "Preview" text where animations are applied on click the play button
        'custom_groups' => array(
            // Format: '{group}' => '{group_label}'
            'custom_group' => __('Custom Group', 'cmb-ext'),
        ),
        'custom_animations' => array(
            // Format: '{group}' => array( '{animation}' => '{animation_label}' )
            'entrances' => array(
                'custom_entrance' => __('Custom Entrance', 'cmb-ext'),
            ),
            'custom_group' => array(
                'custom_animation' => __('Custom Animation on a custom group', 'cmb-ext'),
            )
        ),
    ));

    //Button Set
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('ButtonSet', 'cmb-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb-ext'),
        'id' => $prefix . 'button_set',
        'type' => 'button_set',
        'tab' => 'general',
        'default' => 'left',
        'options' => array(
            "" => __("Default", 'cmb-ext'),
            "left" => __("Left", 'cmb-ext'),
            "center" => __("Center", 'cmb-ext'),
            "right" => __("Right", 'cmb-ext')
        ),
    ));

    //Order
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Order', 'cmb-ext'),
        'desc' => esc_html__('field description (optional)', 'cmb-ext'),
        'id' => $prefix . '_order',
        'type' => 'order',
        'tab' => 'general',
        // 'inline'        => true,
        'options' => array(
            'option-1' => esc_html__('Option 1', 'cmb-ext'),
            'option-2' => esc_html__('Option 2', 'cmb-ext'),
            'option-3' => esc_html__('Option 3', 'cmb-ext'),
            'option-4' => esc_html__('Option 4', 'cmb-ext'),
        ),
    ));

    //Unit
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Unit', 'cmb-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb-ext'),
        'id' => $prefix . 'unit',
        'type' => 'unit',
        'tab' => 'general',
        // Custom units (units by default are 'px', 'em' and 'rem'
        'units' => array(
            'px' => 'px',
            'em' => 'em',
        )
    ));

    //Font
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Font', 'cmb2-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb2-ext'),
        'id' => $prefix . 'font',
        'type' => 'font',
        'tab' => 'general',
        'preview' => true,
        'attributes' => array(
            'data-placeholder' => esc_html__('Choose a font', 'cmb2-ext'),
        )
    ));

    //Slide
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Slide', 'cmb2-ext'),
        'desc' => esc_html__('Field description (optional)', 'cmb2-ext'),
        'id' => $prefix . 'slide',
        'tab' => 'general',
        'type'        => 'slider',
		'min'         => '0',
		'max'         => '200',
		'step'        => '5',
		'default'     => '0', // start value
		'value_label' => 'Value:',
    ));

    //Map
    $cmb_ext_demo->add_field( array(
		'name' => esc_html__('Location', 'cmb2-ext'),
		'desc' => esc_html__('Drag the marker to set the exact location', 'cmb2-ext'),
		'id' => $prefix . 'location',
		'tab' => 'general',
		'type' => 'map',
		// 'split_values' => true, // Save latitude and longitude as two separate fields
	) );

    //Ajax Search single
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Example Single', 'cmb-ext'),
        'desc' => esc_html__('(Start typing post title)', 'cmb-ext'),
        'id' => $prefix . 'post',
        'type' => 'ajax_search',
        'tab' => 'search',
        'search' => 'post',
        'query_args' => array(
            'post_type' => array('post'),
            'posts_per_page' => -1
        )
    ));

    //Ajax Search Multiple
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Example Multiple', 'cmb-ext'),
        'desc' => esc_html__('(Start typing post title)', 'cmb-ext'),
        'id' => $prefix . 'posts',
        'type' => 'ajax_search',
        'tab' => 'search',
        'multiple' => true,
        'limit' => 10,
        'sortable' => true,    // Allow selected items to be sortable (default false)
        //'search'      => 'post',
        'query_args' => array(
            'post_type' => array('post', 'page'),
            'post_status' => array('publish', 'pending')
        )
    ));

    //Ajax Search user single
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Example User Single', 'cmb-ext'),
        'desc' => esc_html__('(Start typing user)', 'cmb-ext'),
        'id' => $prefix . 'user',
        'type' => 'ajax_search',
        'tab' => 'search',
        'search' => 'user',
        'query_args' => array(
            'role' => array('Administrator'),
            'search_columns' => array('user_login', 'user_email')
        )
    ));

    //Ajax Search user multiple
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Example user multiple', 'cmb-ext'),
        'desc' => esc_html__('(Start typing user)', 'cmb-ext'),
        'id' => $prefix . 'users',
        'type' => 'ajax_search',
        'tab' => 'search',
        'multiple' => true,
        'limit' => 5,
        'search' => 'user',
        'query_args' => array(
            'role__not_in' => array('Editor'),
        )
    ));

    //Ajax Search single term
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Example term', 'cmb-ext'),
        'desc' => esc_html__('(Start typing term)', 'cmb-ext'),
        'id' => $prefix . 'term',
        'type' => 'ajax_search',
        'tab' => 'search',
        'search' => 'term',
        'query_args' => array(
            'taxonomy' => 'category',
            'childless' => true
        )
    ));

    //Ajax Search Multiple terms
    $cmb_ext_demo->add_field(array(
        'name' => esc_html__('Example Multiple term', 'cmb-ext'),
        'desc' => esc_html__('(Start typing term)', 'cmb-ext'),
        'id' => $prefix . 'terms',
        'type' => 'ajax_search',
        'tab' => 'search',
        'multiple' => true,
        'limit' => -1,
        'search' => 'term',
        'query_args' => array(
            'taxonomy' => 'post_tag',
            'hide_empty' => false
        )
    ));

    /*******************GROUPS**************************/
    $group_field_id = $cmb_ext_demo->add_field(array(
        'description' => esc_html__('Generates reusable form entries', 'cmb-ext'),
        'id' => $prefix . '_group',
        'type' => 'group',
        'tab' => 'group',
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options' => array(
            'group_title' => esc_html__('Entry {#}', 'cmb-ext'), // since version 1.1.4, {#} gets replaced by row number
            'add_button' => esc_html__('Add Another Entry', 'cmb-ext'),
            'remove_button' => esc_html__('Remove Entry', 'cmb-ext'),
            'sortable' => true,
        ),
    ));

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $cmb_ext_demo->add_group_field($group_field_id, array(
        'name' => esc_html__('Title', 'cmb-ext'),
        'desc' => esc_html__('This is a title description', 'cmb-ext'),
        'id' => $prefix . '_title',
        'type' => 'text',
        //'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ));

}

add_action('cmb2_admin_init', 'cmb2_ext_demo_metabox');