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
 * Add admin menu CSS file
 * 
 * @param array $options Existing admin CSS options
 * @return array Modified options array
 */
function arsol_add_admin_menu_css($options) {
    // Add condition here - for example, only on certain admin pages
    if (is_admin() && !wp_doing_ajax()) {
        $options['admin-menu'] = array(
            'name' => __('Admin Menu Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-menu.css'
        );
    }
    
    return $options;
}
add_filter('arsol_css_addons_admin_css_options', 'arsol_add_admin_menu_css');

/**
 * Add admin buttons CSS file
 * 
 * @param array $options Existing admin CSS options
 * @return array Modified options array
 */
function arsol_add_admin_buttons_css($options) {
    // For example, only add this for specific admin users or roles
    if (current_user_can('manage_options')) {
        $options['admin-buttons'] = array(
            'name' => __('Admin Button Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-buttons.css'
        );
    }
    
    return $options;
}
add_filter('arsol_css_addons_admin_css_options', 'arsol_add_admin_buttons_css');

/**
 * Add admin forms CSS file
 * 
 * @param array $options Existing admin CSS options
 * @return array Modified options array
 */
function arsol_add_admin_forms_css($options) {
    // Example: Only add on specific admin screens
    $screen = get_current_screen();
    if (is_admin() && $screen && in_array($screen->id, array('post', 'edit-post'))) {
        $options['admin-forms'] = array(
            'name' => __('Admin Form Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-forms.css'
        );
    }
    
    return $options;
}
add_filter('arsol_css_addons_admin_css_options', 'arsol_add_admin_forms_css');

/**
 * Add admin tables CSS file
 * 
 * @param array $options Existing admin CSS options
 * @return array Modified options array
 */
function arsol_add_admin_tables_css($options) {
    // Always available
    $options['admin-tables'] = array(
        'name' => __('Admin Table Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-tables.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_admin_css_options', 'arsol_add_admin_tables_css');

/**
 * Add frontend buttons CSS file
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_buttons_css($options) {
    // You could conditionally add based on theme, page type, etc.
    $options['buttons'] = array(
        'name' => __('Button Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-buttons.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_buttons_css');

/**
 * Add frontend forms CSS file
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_forms_css($options) {
    // Condition: Only if the current theme supports forms
    $options['forms'] = array(
        'name' => __('Form Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-forms.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_forms_css');

/**
 * Add frontend typography CSS file
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_typography_css($options) {
    // Always add typography styling
    $options['typography'] = array(
        'name' => __('Typography Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-typography.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_typography_css');

/**
 * Add frontend layouts CSS file
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_layouts_css($options) {
    // You could check for specific page templates
    $options['layouts'] = array(
        'name' => __('Layout Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-layouts.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_layouts_css');

/**
 * Add frontend tables CSS file
 * 
 * @param array $options Existing frontend CSS options
 * @return array Modified options array
 */
function arsol_add_frontend_tables_css($options) {
    // Add tables CSS file
    $options['tables'] = array(
        'name' => __('Table Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-tables.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_frontend_css_options', 'arsol_add_frontend_tables_css');

/**
 * Add global CSS files
 * 
 * @param array $options Existing global CSS options
 * @return array Modified options array
 */
function arsol_add_global_css_files($options) {
    // Add global CSS files
    $options['normalize'] = array(
        'name' => __('Normalize CSS', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/global-normalize.css'
    );
    
    $options['responsive'] = array(
        'name' => __('Responsive Utilities', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/global-responsive.css'
    );
    
    return $options;
}
add_filter('arsol_css_addons_global_css_options', 'arsol_add_global_css_files');

// Continue with similar individual functions for typography, layouts, tables, etc.