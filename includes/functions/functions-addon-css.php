<?php
/**
 * CSS addon functions for Arsol CSS Addons
 * 
 * Hooks into filters to add CSS addon files to the plugin.
 * Each addon is registered independently.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Admin Menu CSS addon
 */
function arsol_add_admin_menu_css_addon($options) {
    if (is_admin() && !wp_doing_ajax()) {
        $options['admin-menu'] = array(
            'name' => __('Admin Menu Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-menu.css'
        );
    }
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_admin_menu_css_addon');

/**
 * Add Admin Buttons CSS addon
 */
function arsol_add_admin_buttons_css_addon($options) {
    if (current_user_can('manage_options')) {
        $options['admin-buttons'] = array(
            'name' => __('Admin Button Styling', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/admin-buttons.css'
        );
    }
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_admin_buttons_css_addon');

/**
 * Add Frontend Buttons CSS addon
 */
function arsol_add_frontend_buttons_css_addon($options) {
    $options['frontend-buttons'] = array(
        'name' => __('Frontend Button Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-buttons.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_frontend_buttons_css_addon');

/**
 * Add Frontend Forms CSS addon
 */
function arsol_add_frontend_forms_css_addon($options) {
    $options['frontend-forms'] = array(
        'name' => __('Frontend Form Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/frontend-forms.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_frontend_forms_css_addon');

/**
 * Add Typography CSS addon
 */
function arsol_add_typography_css_addon($options) {
    $options['typography'] = array(
        'name' => __('Typography Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/typography.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_typography_css_addon');

/**
 * Add Layouts CSS addon
 */
function arsol_add_layouts_css_addon($options) {
    $options['layouts'] = array(
        'name' => __('Layout Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/layouts.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_layouts_css_addon');

/**
 * Add Tables CSS addon
 */
function arsol_add_tables_css_addon($options) {
    $options['tables'] = array(
        'name' => __('Table Styling', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/tables.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_tables_css_addon');

/**
 * Add Normalize CSS addon
 */
function arsol_add_normalize_css_addon($options) {
    $options['normalize'] = array(
        'name' => __('Normalize CSS', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/normalize.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_normalize_css_addon');

/**
 * Add Responsive CSS addon
 */
function arsol_add_responsive_css_addon($options) {
    $options['responsive'] = array(
        'name' => __('Responsive Utilities', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/css/addon-css/responsive.css'
    );
    return $options;
}
add_filter('arsol_css_addons_css_addon_options', 'arsol_add_responsive_css_addon');