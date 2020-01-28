# CMB2 Extension

![CMB2 Extension](https://raw.githubusercontent.com/wiki/polupraneeth/cmb2-extension/images/cmb2-extension-banner.gif)

**Contributors:**      [polupraneeth](https://github.com/polupraneeth), [stackadroit](https://github.com/stackadroit)  
**Homepage:**          [github](https://github.com/polupraneeth/cmb2-extension)  
**Tags:**              metaboxes, forms, fields, options, settings  
**Requires at least:** 3.8.0  
**Tested up to:**      5.3.2  
**Stable tag:**        1.0.0  
**License:**           GPLv3 or later  
**License URI:**       [http://www.gnu.org/licenses/gpl-3.0.html](http://www.gnu.org/licenses/gpl-3.0.html)  


Complete contributors list found here: [github.com/CMB2/CMB2/graphs/contributors](https://github.com/polupraneeth/cmb2-extension/graphs/contributors)

## Description

This is a CMB2 Extension repository which help modify the default behavior of [CMB2](https://github.com/WebDevStudios/CMB2/) and extends it's functionality.

Extension are organized into categories (folders) and each Filed Type is placed in its own file with a name that describes what it does.

**[Download plugin on wordpress.org](https://wordpress.org/plugins/cmb2-extension/)**

To get started, please follow the examples in the included `example-functions.php` file and have a look at the [basic usage instructions](https://github.com/polupraneeth/cmb2-extension/wiki/Basic-Usage).

You can see a list of available field types [here](https://github.com/polupraneeth/cmb2-extension/wiki/Field-Types#types).

### Contribution

First attempt at a library. Lots more changes and fixes to do. Contributions are welcome.

## Installation

If installing the plugin from wordpress.org:

1. Upload the entire `/cmb2-extension` directory to the `/wp-content/plugins/` directory.
2. Activate CMB2 Extension through the 'Plugins' menu in WordPress.
2. Copy (and rename if desired) `example-functions.php` into to your theme or plugin's directory.
2. Edit to only include the fields you need and rename the functions.
4. Profit.

If including the library in your plugin or theme:

1. Place the CMB2 Extension directory inside of your theme or plugin.
2. Copy (and rename if desired) `example-functions.php` into a folder *above* the CMB2 directory OR copy the entirety of its contents to your theme's `functions.php` file.
2. Edit to only include the fields you need and rename the functions (CMB2 directory should be left unedited in order to easily update the library).
4. Profit.

## Usage
You can view wiki page to usage and setup guide:
[Documentation](https://github.com/polupraneeth/cmb2-extensions/wiki)

## Known Issues

* Not all fields work well in a repeatable group.

