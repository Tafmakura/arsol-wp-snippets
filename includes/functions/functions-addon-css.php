<?php
/**
 * CSS addon functions for Arsol CSS Addons
 * 
 * Hooks into filters to add CSS addon files to the plugin.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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