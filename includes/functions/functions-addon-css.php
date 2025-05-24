<?php
/**
 * CSS addon functions for Arsol CSS Addons
 * 
 * Hooks into filters to add CSS addon files to the plugin.
 * Each addon is registered independently.
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Admin Menu CSS addon
 */
function arsol_add_admin_menu_css_addon($options) {
    $options['admin-menu'] = array(
        'name' => __('Admin Menu Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/admin-menu.css',
        'context' => 'admin',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_admin_menu_css_addon');

/**
 * Add Admin Buttons CSS addon
 */
function arsol_add_admin_buttons_css_addon($options) {
    $options['admin-buttons'] = array(
        'name' => __('Admin Button Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/admin-buttons.css',
        'context' => 'admin',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_admin_buttons_css_addon');

/**
 * Add Frontend Buttons CSS addon
 */
function arsol_add_frontend_buttons_css_addon($options) {
    $options['frontend-buttons'] = array(
        'name' => __('Frontend Button Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/frontend-buttons.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_frontend_buttons_css_addon');

/**
 * Add Frontend Forms CSS addon
 */
function arsol_add_frontend_forms_css_addon($options) {
    $options['frontend-forms'] = array(
        'name' => __('Frontend Form Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/frontend-forms.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_frontend_forms_css_addon');

/**
 * Add Typography CSS addon
 */
function arsol_add_typography_css_addon($options) {
    $options['typography'] = array(
        'name' => __('Typography Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/typography.css',
        'context' => 'global',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_typography_css_addon');

/**
 * Add Layouts CSS addon
 */
function arsol_add_layouts_css_addon($options) {
    $options['layouts'] = array(
        'name' => __('Layout Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/layouts.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_layouts_css_addon');

/**
 * Add Tables CSS addon
 */
function arsol_add_tables_css_addon($options) {
    $options['tables'] = array(
        'name' => __('Table Styling', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/tables.css',
        'context' => 'global',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_tables_css_addon');

/**
 * Add Normalize CSS addon
 */
function arsol_add_normalize_css_addon($options) {
    $options['normalize'] = array(
        'name' => __('Normalize CSS', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/normalize.css',
        'context' => 'global',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_normalize_css_addon');

/**
 * Add Responsive CSS addon
 */
function arsol_add_responsive_css_addon($options) {
    $options['responsive'] = array(
        'name' => __('Responsive Utilities', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/css/addon-css/responsive.css',
        'context' => 'global',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_add_responsive_css_addon');