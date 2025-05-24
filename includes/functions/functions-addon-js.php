<?php
/**
 * JS addon functions for Arsol CSS Addons
 * 
 * Hooks into filters to add JS addon files to the plugin.
 * Each addon is registered independently.
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Admin Enhancement Scripts JS addon
 */
function arsol_add_admin_enhancements_js_addon($options) {
    if (is_admin()) {
        $options['admin-enhancements'] = array(
            'name' => __('Admin Enhancement Scripts', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/admin-enhancements.js'
        );
    }
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_admin_enhancements_js_addon');

/**
 * Add Admin Form Scripts JS addon
 */
function arsol_add_admin_forms_js_addon($options) {
    if (is_admin()) {
        $options['admin-forms'] = array(
            'name' => __('Admin Form Scripts', 'arsol-css-addons'),
            'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/admin-forms.js'
        );
    }
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_admin_forms_js_addon');

/**
 * Add Frontend Interactions JS addon
 */
function arsol_add_frontend_interactions_js_addon($options) {
    $options['frontend-interactions'] = array(
        'name' => __('Frontend Interactions', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/frontend-interactions.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_frontend_interactions_js_addon');

/**
 * Add Form Validation JS addon
 */
function arsol_add_form_validation_js_addon($options) {
    $options['form-validation'] = array(
        'name' => __('Form Validation', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/form-validation.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_form_validation_js_addon');

/**
 * Add Smooth Scroll JS addon
 */
function arsol_add_smooth_scroll_js_addon($options) {
    $options['smooth-scroll'] = array(
        'name' => __('Smooth Scroll', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/smooth-scroll.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_smooth_scroll_js_addon');

/**
 * Add Mobile Menu JS addon
 */
function arsol_add_mobile_menu_js_addon($options) {
    $options['mobile-menu'] = array(
        'name' => __('Mobile Menu', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/mobile-menu.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_mobile_menu_js_addon');

/**
 * Add Lazy Loading JS addon
 */
function arsol_add_lazy_loading_js_addon($options) {
    $options['lazy-loading'] = array(
        'name' => __('Lazy Loading', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/lazy-loading.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_lazy_loading_js_addon');

/**
 * Add Image Gallery JS addon
 */
function arsol_add_image_gallery_js_addon($options) {
    $options['image-gallery'] = array(
        'name' => __('Image Gallery', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/image-gallery.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_image_gallery_js_addon');

/**
 * Add Search Enhancement JS addon
 */
function arsol_add_search_enhancement_js_addon($options) {
    $options['search-enhancement'] = array(
        'name' => __('Search Enhancement', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/search-enhancement.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_search_enhancement_js_addon');

/**
 * Add Cookie Consent JS addon
 */
function arsol_add_cookie_consent_js_addon($options) {
    $options['cookie-consent'] = array(
        'name' => __('Cookie Consent', 'arsol-css-addons'),
        'file' => ARSOL_CSS_ADDONS_PLUGIN_URL . 'assets/js/addon-js/cookie-consent.js'
    );
    return $options;
}
add_filter('arsol_css_addons_js_addon_options', 'arsol_add_cookie_consent_js_addon');