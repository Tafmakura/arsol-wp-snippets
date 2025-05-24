<?php
/**
 * JS addon functions for Arsol CSS Addons
 * 
 * Hooks into filters to add JS addon files to the plugin.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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