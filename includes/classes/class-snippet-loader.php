<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Snippet Loader class to handle loading and executing snippets
 */
class Snippet_Loader {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_snippets'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_snippets'));
        add_action('init', array($this, 'load_php_snippets'));
    }

    /**
     * Check if safe mode is enabled
     */
    private function is_safe_mode() {
        return defined('ARSOL_WP_SNIPPETS_SAFE_MODE') && ARSOL_WP_SNIPPETS_SAFE_MODE;
    }

    /**
     * Load CSS and JS snippets for frontend
     */
    public function load_frontend_snippets() {
        if ($this->is_safe_mode()) {
            return;
        }
        $this->load_css_snippets('frontend');
        $this->load_js_snippets('frontend');
    }

    /**
     * Load CSS and JS snippets for admin
     */
    public function load_admin_snippets() {
        if ($this->is_safe_mode()) {
            return;
        }
        $this->load_css_snippets('admin');
        $this->load_js_snippets('admin');
    }

    /**
     * Load PHP snippets
     */
    public function load_php_snippets() {
        // Get options
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_php_files = isset($options['php_addon_options']) ? $options['php_addon_options'] : array();
        
        if (!empty($enabled_php_files)) {
            foreach ($enabled_php_files as $file_key) {
                if (!$this->is_safe_mode()) {
                    $this->include_php_snippet($file_key);
                }
            }
        }
    }

    /**
     * Load CSS snippets for specific context
     * 
     * @param string $context The context to load snippets for ('frontend' or 'admin')
     */
    private function load_css_snippets($context) {
        // Get options
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_css_files = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        
        if (empty($enabled_css_files)) {
            return;
        }

        // Get all available CSS files through filter
        $css_files = apply_filters('arsol_wp_snippets_css_addon_files', array());

        foreach ($enabled_css_files as $file_key) {
            if (!isset($css_files[$file_key])) {
                continue;
            }

            $file_data = $css_files[$file_key];

            // Skip if context doesn't match
            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                continue;
            }

            // Register and enqueue the CSS file
            $handle = 'arsol-wp-snippets-css-' . $file_key;
            $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
            $version = isset($file_data['version']) ? $file_data['version'] : arsol_wp_snippets_get_version();

            wp_register_style(
                $handle,
                $file_data['file'],
                $dependencies,
                $version
            );

            wp_enqueue_style($handle);

            // Debug log
            error_log('Arsol WP Snippets: Loading CSS file - ' . $file_data['file']);

            // Trigger action when CSS file is loaded
            do_action('arsol_wp_snippets_loaded_css_addon', $file_key, $file_data);
        }
    }

    /**
     * Load JS snippets for specific context
     * 
     * @param string $context The context to load snippets for ('frontend' or 'admin')
     */
    private function load_js_snippets($context) {
        // Get options
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_js_files = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
        
        if (empty($enabled_js_files)) {
            return;
        }

        // Get all available JS files through filter
        $js_files = apply_filters('arsol_wp_snippets_js_addon_files', array());

        foreach ($enabled_js_files as $file_key) {
            if (!isset($js_files[$file_key])) {
                continue;
            }

            $file_data = $js_files[$file_key];

            // Skip if context doesn't match
            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                continue;
            }

            // Register and enqueue the JS file
            $handle = 'arsol-wp-snippets-js-' . $file_key;
            $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
            $version = isset($file_data['version']) ? $file_data['version'] : arsol_wp_snippets_get_version();
            $in_footer = isset($file_data['position']) && $file_data['position'] === 'footer';

            wp_register_script(
                $handle,
                $file_data['file'],
                $dependencies,
                $version,
                $in_footer
            );

            wp_enqueue_script($handle);

            // Debug log
            error_log('Arsol WP Snippets: Loading JS file - ' . $file_data['file']);

            // Trigger action when JS file is loaded
            do_action('arsol_wp_snippets_loaded_js_addon', $file_key, $file_data);
        }
    }

    /**
     * Include PHP snippet file
     * 
     * @param string $file_key The key of the PHP file to include
     */
    private function include_php_snippet($file_key) {
        // Get all available PHP files through filter
        $php_files = apply_filters('arsol_wp_snippets_php_addon_files', array());

        if (!isset($php_files[$file_key])) {
            return;
        }

        $file_data = $php_files[$file_key];
        
        if (file_exists($file_data['file'])) {
            include_once $file_data['file'];
            
            // Debug log
            error_log('Arsol WP Snippets: Loading PHP file - ' . $file_data['file']);
            
            // Trigger action when PHP file is loaded
            do_action('arsol_wp_snippets_loaded_php_addon', $file_key, $file_data);
        }
    }
}