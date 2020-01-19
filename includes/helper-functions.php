<?php
/**
 * Helper functions
 *
 * @since  1.0.0
 *
 * @category  WordPress_Plugin
 * @package   CMB2 Extension
 * @author    Praneeth Polu
 * @license   GPL-3.0+
 * @link      https://polupraneeth.me
 */

/**
 *
 * @param string $class_name Name of the class being requested.
 * @since  1.0.0
 */
function cmb2_extension_autoload_classes($class_name)
{
    if (0 !== strpos($class_name, 'CMB_Extension')) {
        return;
    }

    $path = 'includes';
    if ( 0 === strpos($class_name, 'CMB_Extension_Type_')) {
        $path .= '/types';
    }

    if ( 0 === strpos($class_name, 'CMB_Extension_Walkers_')) {
        $path .= '/walkers';
    }

    include_once(cmb_ext()->cmb2_ext_dir("$path/{$class_name}.php"));
}

/**
 *
 * @param string $name
 * @return string
 * @since  1.0.0
 */
function type2className($name)
{
    return str_replace(' ', '_', ucwords(str_replace('_', ' ', $name)));
}

/**
 *
 * @param array $classes
 * @return string
 * @since  1.0.0
 */
function cmb_ext_clean_classes($classes = array())
{
    // Clean up.
    $classes = array_map('strip_tags', array_filter($classes));

    // Remove any duplicates.
    $classes = array_unique($classes);

    // Make a string.
    return implode(' ', $classes);
}

/**
 *
 * @param array $arg
 * @return array
 * @since  1.0.0
 */
function marge_same_key_value($arg)
{

    $clean = array();
    foreach ($arg as $index => $fields) {

        $key = key($fields);
        if (!isset($clean[$key])) {
            $clean[$key] = array();
        }
        $clean[$key][] = $fields[$key];
    }
    return $clean;
}

/**
 * Get instance of the CMB2_Ajax class
 *
 * @return CMB_Extension_Ajax object
 * @since  1.0.0
 */
function cmb_ext_ajax()
{
    return CMB_Extension_Ajax::get_instance();
}

/**
 * FontAwesome predefined array
 *
 * @since  1.0.0
 */
function returnRayFaPre() {
    include CMB2_EXTENSION_DIR .'/data/predefined-array-fontawesome.php';
    return $fontAwesome;
}

/**
 * FontAwesome version 5 predefined array
 *
 * @since  1.0.0
 */
function returnRayFapsa() {
    include CMB2_EXTENSION_DIR .'/data/predefined-array-fontawesome.php';
    return array_combine( $fa5all, $fa5all );
}