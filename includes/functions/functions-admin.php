<?php
/**
 * Admin functions for Arsol CSS Addons
 * 
 * Hooks into filters defined in the Admin_Settings class
 * to add CSS files to the plugin.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add admin CSS options to the filter
 * 
 * @param array $options Existing admin CSS options
 * @return array Modified options array
 */
function arsol_add_admin_css_options($options) {
    $admin_css_files = array(
        'admin-menu' => array(
            'name' => __('Enhanced Admin Menu', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-menu.css'
        ),
        'admin-buttons' => array(
            'name' => __('Enhanced Admin Buttons', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-buttons.css'
        ),
        'admin-forms' => array(
            'name' => __('Enhanced Admin Forms', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-forms.css'
        ),
        'admin-tables' => array(
            'name' => __('Enhanced Admin Tables', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-tables.css'
        ),
    );
    
    return array_merge($options, $admin_css_files);
}
add_filter('arsol_css_addons_admin_css_options', 'arsol_add_admin_css_options');

/**
 * Add frontend CSS options to the filter
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_css_options($options) {
    $frontend_css_files = array(
        'buttons' => array(
            'name' => __('Enhanced Buttons', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-buttons.css'
        ),
        'forms' => array(
            'name' => __('Enhanced Forms', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-forms.css'
        ),
        'typography' => array(
            'name' => __('Enhanced Typography', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-typography.css'
        ),
        'layouts' => array(
            'name' => __('Enhanced Layouts', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-layouts.css'
        ),
        'tables' => array(
            'name' => __('Enhanced Tables', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-tables.css'
        ),
    );
    
    return array_merge($options, $frontend_css_files);
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_css_options');