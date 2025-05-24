<?php
/**
 * Admin functions for Arsol CSS Addons
 * 
 * Hooks into filters defined in the Admin_Settings class
 * to add addon files to the plugin.
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

/**
 * Add CSS addon files
 * 
 * @param array $options Existing CSS addon options
 * @return array Modified options array
 */
function arsol_add_css_addon_files($options) {
    // Admin CSS addons
    if (is_admin() && !wp_doing_ajax()) {
        $options['admin-menu'] = array(
            'name' => __('Admin Menu Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-menu.css'
        );
    }
    
    if (current_user_can('manage_options')) {
        $options['admin-buttons'] = array(
            'name' => __('Admin Button Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-buttons.css'
        );
    }
    
    // Frontend CSS addons
    $options['frontend-buttons'] = array(
        'name' => __('Frontend Button Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-buttons.css'
    );
    
    $options['frontend-forms'] = array(
        'name' => __('Frontend Form Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-forms.css'
    );
    
    $options['typography'] = array(
        'name' => __('Typography Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/typography.css'
    );
    
    $options['layouts'] = array(
        'name' => __('Layout Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/layouts.css'
    );
    
    $options['tables'] = array(
        'name' => __('Table Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/tables.css'
    );
    
    // Global CSS addons
    $options['normalize'] = array(
        'name' => __('Normalize CSS', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/normalize.css'
    );
    
    $options['responsive'] = array(
        'name' => __('Responsive Utilities', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/responsive.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_css_addon_files');

/**
 * Add JS addon files
 * 
 * @param array $options Existing JS addon options
 * @return array Modified options array
 */
function arsol_add_js_addon_files($options) {
    // Admin JS addons
    if (is_admin()) {
        $options['admin-enhancements'] = array(
            'name' => __('Admin Enhancement Scripts', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/admin-enhancements.js'
        );
        
        $options['admin-forms'] = array(
            'name' => __('Admin Form Scripts', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/admin-forms.js'
        );
    }
    
    // Frontend JS addons
    $options['frontend-interactions'] = array(
        'name' => __('Frontend Interactions', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/frontend-interactions.js'
    );
    
    $options['form-validation'] = array(
        'name' => __('Form Validation', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/form-validation.js'
    );
    
    $options['smooth-scroll'] = array(
        'name' => __('Smooth Scroll', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/smooth-scroll.js'
    );
    
    $options['mobile-menu'] = array(
        'name' => __('Mobile Menu', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/mobile-menu.js'
    );
    
    $options['lazy-loading'] = array(
        'name' => __('Lazy Loading', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/lazy-loading.js'
    );
    
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_js_addon_files');