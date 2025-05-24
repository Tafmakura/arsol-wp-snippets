<?php
/**
 * PHP addon functions for Arsol WP Snippets
 * 
 * Hooks into filters to add PHP addon files to the plugin.
 * Each addon is registered independently.
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Admin Enhancements PHP addon
 */
function arsol_add_admin_enhancements_php_addon($options) {
    $options['admin-enhancements'] = array(
        'name' => __('Admin Enhancements', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/admin-enhancements.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_admin_enhancements_php_addon');

/**
 * Add Custom Post Types PHP addon
 */
function arsol_add_custom_post_types_php_addon($options) {
    $options['custom-post-types'] = array(
        'name' => __('Custom Post Types', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/custom-post-types.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_custom_post_types_php_addon');

/**
 * Add Custom Fields PHP addon
 */
function arsol_add_custom_fields_php_addon($options) {
    $options['custom-fields'] = array(
        'name' => __('Custom Fields', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/custom-fields.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_custom_fields_php_addon');

/**
 * Add Security Enhancements PHP addon
 */
function arsol_add_security_enhancements_php_addon($options) {
    $options['security-enhancements'] = array(
        'name' => __('Security Enhancements', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/security-enhancements.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_security_enhancements_php_addon');

/**
 * Add SEO Enhancements PHP addon
 */
function arsol_add_seo_enhancements_php_addon($options) {
    $options['seo-enhancements'] = array(
        'name' => __('SEO Enhancements', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/seo-enhancements.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_seo_enhancements_php_addon');

/**
 * Add Performance Optimizations PHP addon
 */
function arsol_add_performance_optimizations_php_addon($options) {
    $options['performance-optimizations'] = array(
        'name' => __('Performance Optimizations', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/performance-optimizations.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_performance_optimizations_php_addon');

/**
 * Add Custom Shortcodes PHP addon
 */
function arsol_add_custom_shortcodes_php_addon($options) {
    $options['custom-shortcodes'] = array(
        'name' => __('Custom Shortcodes', 'arsol-wp-snippets'),
        'file' => ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'assets/php/addon-php/custom-shortcodes.php'
    );
    return $options;
}
add_filter('arsol_wp_snippets_php_addon_files', 'arsol_add_custom_shortcodes_php_addon');