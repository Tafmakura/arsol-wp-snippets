<?php
/**
 * PHP addon functions for Arsol CSS Addons
 * 
 * Hooks into filters to add PHP addon files to the plugin.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add PHP addon files
 * 
 * @param array $options Existing PHP addon options
 * @return array Modified options array
 */
function arsol_add_php_addon_files($options) {
    // Example PHP addon for custom admin functionality
    $options['admin-enhancements'] = array(
        'name' => __('Admin Enhancements', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_DIR . 'assets/php/addon-php/admin-enhancements.php'
    );
    
    // Example PHP addon for custom post types
    $options['custom-post-types'] = array(
        'name' => __('Custom Post Types', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_DIR . 'assets/php/addon-php/custom-post-types.php'
    );
    
    // Example PHP addon for custom fields
    $options['custom-fields'] = array(
        'name' => __('Custom Fields', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_DIR . 'assets/php/addon-php/custom-fields.php'
    );
    
    return $options;
}
add_filter('arsol_css_addons_php_addon_options', 'arsol_add_php_addon_files');