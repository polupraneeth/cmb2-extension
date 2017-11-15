<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'cmb2_tabs_' with your project's prefix.
 *
 * @category WordPress_Plugin
 * @package  Demo_CMB2_Tabs
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     https://github.com/stackadroit/cmb2-extensions
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if (file_exists(dirname(__FILE__).'/cmb2/init.php')) {
    require_once dirname(__FILE__).'/cmb2/init.php';
} elseif (file_exists(dirname(__FILE__).'/CMB2/init.php')) {
    require_once dirname(__FILE__).'/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object.
 *
 * @return bool             True if metabox should show
 */
function cmb2_tabs_show_if_front_page($cmb) {
    // Don't show this metabox if it's not the front page template.
    if (get_option('page_on_front') !== $cmb->object_id) {
        return false;
    }
    return true;
}


add_action('cmb2_admin_init', 'cmb2_tabs_register_demo_metabox');

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function cmb2_tabs_register_demo_metabox() {
    $prefix = 'cmb2_tabs_';

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_tabs_demo = new_cmb2_box(array(
        'id'            => $prefix.'metabox',
        'title'         => esc_html__('Test Metabox', 'cmb2_tabs'),
        'object_types'  => array('page'), // Post type
        'tabs'      => array(
            'contact' => array(
                'label' => __('Contact', 'cmb2_tabs'),
                'show_on_cb' => 'cmb2_tabs_show_if_front_page',
            ),
            'social'  => array(
                'label' => __('Social Media', 'cmb2_tabs'),
                'icon'  => 'dashicons-share', // Dashicon
            ),
            'note'    => array(
                'label' => __('Note', 'cmb2_tabs'),
                'icon'  => 'dashicons-sos', // Custom icon, using image
            ),
        ),
        // 'show_on_cb' => 'cmb2_tabs_show_if_front_page', // function should return a bool value
        // 'context'    => 'normal',
        // 'priority'   => 'high',
        // 'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        // 'classes'    => 'extra-class', // Extra cmb2-wrap classes
    ));

    $cmb_tabs_demo->add_field(array(
        'name'       => esc_html__('Test Text', 'cmb2_tabs'),
        'desc'       => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'         => $prefix.'text',
        'type'       => 'text',
        'tab'  => 'contact',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        'show_on_cb' => 'cmb2_tabs_hide_if_no_cats', // function should return a bool value
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
        // 'column'          => true, // Display field value in the admin post-listing columns
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Text Small', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'textsmall',
        'type' => 'text_small',
        'tab'  => 'contact',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        // 'repeatable' => true,
        // 'column' => array(
        //  'name'     => esc_html__( 'Column Title', 'cmb2_tabs' ), // Set the admin column title
        //  'position' => 2, // Set as the second column.
        // );
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Text Medium', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'textmedium',
        'type' => 'text_medium',
        'tab'  => 'contact',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
    ));

    $cmb_tabs_demo->add_field(array(
        'name'       => esc_html__('Read-only Disabled Field', 'cmb2_tabs'),
        'desc'       => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'         => $prefix.'readonly',
        'type'       => 'text_medium',
        'tab'  => 'social',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        'default'    => esc_attr__('Hey there, I\'m a read-only field', 'cmb2_tabs'),
        'save_field' => false, // Disables the saving of this field.
        'attributes' => array(
            'disabled' => 'disabled',
            'readonly' => 'readonly',
        ),
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Custom Rendered Field', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'render_row_cb',
        'type' => 'text',
        'tab'  => 'social',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Website URL', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'url',
        'type' => 'text_url',
        'tab'  => 'social',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        // 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
        // 'repeatable' => true,
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Text Email', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'email',
        'type' => 'text_email',
        'tab'  => 'social',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        // 'repeatable' => true,
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Time', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'time',
        'type' => 'text_time',
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        // 'time_format' => 'H:i', // Set to 24hr format
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Time zone', 'cmb2_tabs'),
        'desc' => esc_html__('Time zone', 'cmb2_tabs'),
        'id'   => $prefix.'timezone',
        'type' => 'select_timezone',
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Date Picker', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'textdate',
        'type' => 'text_date',
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        // 'date_format' => 'Y-m-d',
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Date Picker (UNIX timestamp)', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'textdate_timestamp',
        'type' => 'text_date_timestamp',
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
        // 'timezone_meta_key' => $prefix . 'timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
    ));

    $cmb_tabs_demo->add_field(array(
        'name' => esc_html__('Test Date/Time Picker Combo (UNIX timestamp)', 'cmb2_tabs'),
        'desc' => esc_html__('field description (optional)', 'cmb2_tabs'),
        'id'   => $prefix.'datetime_timestamp',
        'type' => 'text_datetime_timestamp',
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_row_cb'),
    ));
    
    /*******************GROUPS**************************/
    $group_field_id = $cmb_tabs_demo->add_field( array(
        'id'          => 'wiki_test_repeat_group',
        'type'        => 'group',
        'description' => __( 'Generates reusable form entries', 'cmb2_tabs' ),
        'tab'  => 'note',
        'render_row_cb' => array('CMB2_Tabs', 'tabs_render_group_row_cb'),
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'   => __( 'Entry {#}', 'cmb2_tabs' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'    => __( 'Add Another Entry', 'cmb2_tabs' ),
            'remove_button' => __( 'Remove Entry', 'cmb2_tabs' ),
            'sortable'      => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ) );

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $cmb_tabs_demo->add_group_field( $group_field_id, array(
        'name' => __( 'Entry Title', 'cmb2_tabs' ),
        'id'   => 'title',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ) );
    
}

