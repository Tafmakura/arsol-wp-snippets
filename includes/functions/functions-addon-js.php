<?php
/**
 * JS addon functions for Arsol WP Snippets
 * 
 * Hooks into filters to add JS addon files to the plugin.
 * Each addon is registered independently.
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Admin Enhancement Scripts JS addon
 */
function arsol_add_admin_enhancements_js_addon($options) {
    $options['admin-enhancements'] = array(
        'name' => __('Admin Enhancement Scripts', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/admin-enhancements.js',
        'context' => 'admin',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_admin_enhancements_js_addon');

/**
 * Add Admin Form Scripts JS addon
 */
function arsol_add_admin_forms_js_addon($options) {
    $options['admin-forms'] = array(
        'name' => __('Admin Form Scripts', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/admin-forms.js',
        'context' => 'admin',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_admin_forms_js_addon');

/**
 * Add Frontend Interactions JS addon
 */
function arsol_add_frontend_interactions_js_addon($options) {
    $options['frontend-interactions'] = array(
        'name' => __('Frontend Interactions', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/frontend-interactions.js',
        'context' => 'frontend',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_frontend_interactions_js_addon');

/**
 * Add Form Validation JS addon
 */
function arsol_add_form_validation_js_addon($options) {
    $options['form-validation'] = array(
        'name' => __('Form Validation', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/form-validation.js',
        'context' => 'frontend',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_form_validation_js_addon');

/**
 * Add Smooth Scroll JS addon
 */
function arsol_add_smooth_scroll_js_addon($options) {
    $options['smooth-scroll'] = array(
        'name' => __('Smooth Scroll', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/smooth-scroll.js',
        'context' => 'frontend',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_smooth_scroll_js_addon');

/**
 * Add Mobile Menu JS addon
 */
function arsol_add_mobile_menu_js_addon($options) {
    $options['mobile-menu'] = array(
        'name' => __('Mobile Menu', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/mobile-menu.js',
        'context' => 'frontend',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_mobile_menu_js_addon');

/**
 * Add Lazy Loading JS addon
 */
function arsol_add_lazy_loading_js_addon($options) {
    $options['lazy-loading'] = array(
        'name' => __('Lazy Loading', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/lazy-loading.js',
        'context' => 'global',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_lazy_loading_js_addon');

/**
 * Add Image Gallery JS addon
 */
function arsol_add_image_gallery_js_addon($options) {
    $options['image-gallery'] = array(
        'name' => __('Image Gallery', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/image-gallery.js',
        'context' => 'frontend',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_image_gallery_js_addon');

/**
 * Add Search Enhancement JS addon
 */
function arsol_add_search_enhancement_js_addon($options) {
    $options['search-enhancement'] = array(
        'name' => __('Search Enhancement', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/search-enhancement.js',
        'context' => 'global',
        'position' => 'footer'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_search_enhancement_js_addon');

/**
 * Add Cookie Consent JS addon
 */
function arsol_add_cookie_consent_js_addon($options) {
    $options['cookie-consent'] = array(
        'name' => __('Cookie Consent', 'arsol-css_addons'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_URL . 'assets/js/addon-js/cookie-consent.js',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $options;
}
add_filter('arsol_wp_snippets_js_addon_files', 'arsol_add_cookie_consent_js_addon');