CMB2 Code Snippet Library
========================

This is a CMB2 Extension repository which help modify the default behavior of [CMB2](https://github.com/WebDevStudios/CMB2/) and extends it's functionality.

Extension are organized into categories (folders) and each Extension is placed in its own file with a name that describes what it does.

First attempt at a library. Lots more changes and fixes to do. Contributions are welcome.
## Usage
You can view wiki page to usage and setup guide:
[Documentation](https://github.com/stackadroit/cmb2-extensions/wiki)

## CMB2 Tabs
[CMB2 Tabs](https://wordpress.org/plugins/cmb2-tabs/) is an extenstion for [CMB2](https://github.com/WebDevStudios/CMB2/) which allow you to oragnize fields into tabs.

![cmb2 tabs](https://ps.w.org/cmb2-tabs/assets/banner-772x250.png?rev=1702623)

### Example
```php
// Classic CMB2 declaration
$cmb = new_cmb2_box( array(
	'id'           => 'prefix-metabox-id',
	'title'        => __( 'Post Info' ),
	'object_types' => array( 'post', ), // Post type
	'tabs'      => array(
		'contact' => array(
			'label' => __( 'Contact', 'cmb2' ),
			//'show_on_cb' => 'yourprefix_show_if_front_page',
		),
		'social'  => array(
			'label' => __( 'Social Media', 'cmb2' ),
			'icon'  => 'dashicons-share', // Dashicon
		),
	),
	'tab_style'   => 'default',
) );

// Add new field
$cmb_demo->add_field( array(
	'name' => esc_html__( 'Test Text Medium', 'cmb2' ),
	'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
	'id'   => $prefix . 'textmedium',
	'type' => 'text_medium',
	'tab'  => 'contact',
	'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
) );

// Add new field
$cmb_demo->add_field( array(
	'name' => esc_html__( 'Test Text Medium', 'cmb2' ),
	'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
	'id'   => $prefix . 'textmedium2',
	'type' => 'text_medium',
	'tab'  => 'social',
	'render_row_cb' => array( 'CMB2_Tabs', 'tabs_render_row_cb' ),
) );
```

### Screenshot

![cmb2 tabs screen short 1](https://ps.w.org/cmb2-tabs/assets/screenshot-1.PNG)
![cmb2 tabs screen short 2](https://ps.w.org/cmb2-tabs/assets/screenshot-2.PNG)
![cmb2 tabs screen short 3](https://ps.w.org/cmb2-tabs/assets/screenshot-3.PNG)
