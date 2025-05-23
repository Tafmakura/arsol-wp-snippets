<?php
/**
 * Admin functions for Arsol CSS Addons
 * 
 * Hooks into filters defined in the Admin_Settings class
 * to add various file types to the plugin.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add admin CSS files
 * 
 * @param array $options Existing admin CSS options
 * @return array Modified options array
 */
function arsol_add_admin_css_files($options) {
    // Add admin CSS files
    $options['admin-menu'] = array(
        'name' => __('Admin Menu Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-menu.css'
    );
    
    $options['admin-buttons'] = array(
        'name' => __('Admin Button Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-buttons.css'
    );
    
    $options['admin-forms'] = array(
        'name' => __('Admin Form Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-forms.css'
    );
    
    $options['admin-tables'] = array(
        'name' => __('Admin Table Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-tables.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_admin_css_options', 'arsol_add_admin_css_files');

/**
 * Add admin JavaScript files
 * 
 * @param array $options Existing admin JS options
 * @return array Modified options array
 */
function arsol_add_admin_js_files($options) {
    // Add admin JS files
    $options['admin-helpers'] = array(
        'name' => __('Admin UI Helpers', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/admin-helpers.js'
    );
    
    $options['admin-enhancements'] = array(
        'name' => __('Admin UI Enhancements', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/admin-enhancements.js'
    );
    
    return $options;
}
add_filter('arsol_css_addons_admin_js_options', 'arsol_add_admin_js_files');

/**
 * Add admin PHP files
 * 
 * @param array $options Existing admin PHP options
 * @return array Modified options array
 */
function arsol_add_admin_php_files($options) {
    // Add admin PHP files
    $options['admin-utilities'] = array(
        'name' => __('Admin Utility Functions', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/php/addon-php/admin-utilities.php'
    );
    
    return $options;
}
add_filter('arsol_css_addons_admin_php_options', 'arsol_add_admin_php_files');

/**
 * Add frontend CSS files
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_css_files($options) {
    // Add frontend CSS files
    $options['buttons'] = array(
        'name' => __('Button Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-buttons.css'
    );
    
    $options['forms'] = array(
        'name' => __('Form Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-forms.css'
    );
    
    $options['typography'] = array(
        'name' => __('Typography Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-typography.css'
    );
    
    $options['layouts'] = array(
        'name' => __('Layout Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-layouts.css'
    );
    
    $options['tables'] = array(
        'name' => __('Table Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-tables.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_css_files');

/**
 * Add frontend JavaScript files
 * 
 * @param array $options Existing frontend JS options
 * @return array Modified options array
 */
function arsol_add_frontend_js_files($options) {
    // Add frontend JS files
    $options['frontend-utilities'] = array(
        'name' => __('Frontend Utilities', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/frontend-utilities.js'
    );
    
    $options['frontend-effects'] = array(
        'name' => __('Frontend Effects', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/frontend-effects.js'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_js_options', 'arsol_add_frontend_js_files');

/**
 * Add frontend PHP files
 * 
 * @param array $options Existing frontend PHP options
 * @return array Modified options array
 */
function arsol_add_frontend_php_files($options) {
    // Add frontend PHP files
    $options['frontend-functions'] = array(
        'name' => __('Frontend Helper Functions', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/php/addon-php/frontend-functions.php'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_php_options', 'arsol_add_frontend_php_files');

/**
 * Add global CSS files
 * 
 * @param array $options Existing global CSS options
 * @return array Modified options array
 */
function arsol_add_global_css_files($options) {
    // Add global CSS files
    $options['reset'] = array(
        'name' => __('CSS Reset', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/global-reset.css'
    );
    
    $options['variables'] = array(
        'name' => __('CSS Variables', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/global-variables.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_global_css_options', 'arsol_add_global_css_files');

/**
 * Add global JavaScript files
 * 
 * @param array $options Existing global JS options
 * @return array Modified options array
 */
function arsol_add_global_js_files($options) {
    // Add global JS files
    $options['common-utils'] = array(
        'name' => __('Common Utilities', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/global-common-utils.js'
    );
    
    return $options;
}
add_filter('arsol_css_addons_global_js_options', 'arsol_add_global_js_files');

/**
 * Add global PHP files
 * 
 * @param array $options Existing global PHP options
 * @return array Modified options array
 */
function arsol_add_global_php_files($options) {
    // Add global PHP files
    $options['helper-functions'] = array(
        'name' => __('Helper Functions', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/php/addon-php/global-helpers.php'
    );
    
    return $options;
}
add_filter('arsol_css_addons_global_php_options', 'arsol_add_global_php_files');