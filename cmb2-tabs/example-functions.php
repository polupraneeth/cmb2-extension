<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'cmb2_tabs_' with your project's prefix.
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2_Tabs
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     https://github.com/stackadroit/cmb2-extensions
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object.
 *
 * @return bool             True if metabox should show
 */
function cmb2_tabs_show_if_front_page( $cmb ) {
    // Don't show this metabox if it's not the front page template.
    if ( get_option( 'page_on_front' ) !== $cmb->object_id ) {
        return false;
    }
    return true;
}


add_action( 'cmb2_admin_init', 'cmb2_tabs_register_demo_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function cmb2_tabs_register_demo_metabox() {
    $prefix = 'cmb2_tabs_demo_';

    /**
     * Sample metabox to demonstrate each field type included
     */
    $cmb_tabs_demo = new_cmb2_box( array(
        'id'            => $prefix . 'metabox',
        'title'         => esc_html__( 'Test Metabox', 'cmb2' ),
        'object_types'  => array( 'page' ), // Post type
        'tabs'      => array(
            'contact' => array(
                'label' => __( 'Contact', 'cmb2' ),
                'show_on_cb' => 'cmb2_tabs_show_if_front_page',
            ),
            'social'  => array(
                'label' => __( 'Social Media', 'cmb2' ),
                'icon'  => 'dashicons-share', // Dashicon
            ),
            'note'    => array(
                'label' => __( 'Note', 'cmb2' ),
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
        // 'classes_cb' => 'cmb2_tabs_add_some_classes', // Add classes through a callback.
    ) );

    $cmb_tabs_demo->add_field( array(
        'name'       => esc_html__( 'Test Text', 'cmb2' ),
        'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'         => $prefix . 'text',
        'type'       => 'text',
        'tab'  => 'contact',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        'show_on_cb' => 'cmb2_tabs_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => false, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
        // 'column'          => true, // Display field value in the admin post-listing columns
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Text Small', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'textsmall',
        'type' => 'text_small',
        'tab'  => 'contact',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        // 'repeatable' => true,
        // 'column' => array(
        //  'name'     => esc_html__( 'Column Title', 'cmb2' ), // Set the admin column title
        //  'position' => 2, // Set as the second column.
        // );
        // 'display_cb' => 'cmb2_tabs_display_text_small_column', // Output the display of the column values through a callback.
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Text Medium', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'textmedium',
        'type' => 'text_medium',
        'tab'  => 'contact',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
    ) );

    $cmb_tabs_demo->add_field( array(
        'name'       => esc_html__( 'Read-only Disabled Field', 'cmb2' ),
        'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'         => $prefix . 'readonly',
        'type'       => 'text_medium',
        'tab'  => 'social',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        'default'    => esc_attr__( 'Hey there, I\'m a read-only field', 'cmb2' ),
        'save_field' => false, // Disables the saving of this field.
        'attributes' => array(
            'disabled' => 'disabled',
            'readonly' => 'readonly',
        ),
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Custom Rendered Field', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'render_row_cb',
        'type' => 'text',
        'tab'  => 'social',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Website URL', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'url',
        'type' => 'text_url',
        'tab'  => 'social',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        // 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
        // 'repeatable' => true,
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Text Email', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'email',
        'type' => 'text_email',
        'tab'  => 'social',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        // 'repeatable' => true,
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Time', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'time',
        'type' => 'text_time',
        'tab'  => 'note',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        // 'time_format' => 'H:i', // Set to 24hr format
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Time zone', 'cmb2' ),
        'desc' => esc_html__( 'Time zone', 'cmb2' ),
        'id'   => $prefix . 'timezone',
        'type' => 'select_timezone',
        'tab'  => 'note',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Date Picker', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'textdate',
        'type' => 'text_date',
        'tab'  => 'note',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        // 'date_format' => 'Y-m-d',
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Date Picker (UNIX timestamp)', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'textdate_timestamp',
        'type' => 'text_date_timestamp',
        'tab'  => 'note',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
        // 'timezone_meta_key' => $prefix . 'timezone', // Optionally make this field honor the timezone selected in the select_timezone specified above
    ) );

    $cmb_tabs_demo->add_field( array(
        'name' => esc_html__( 'Test Date/Time Picker Combo (UNIX timestamp)', 'cmb2' ),
        'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
        'id'   => $prefix . 'datetime_timestamp',
        'type' => 'text_datetime_timestamp',
        'tab'  => 'note',
        'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
    ) );

   

}

