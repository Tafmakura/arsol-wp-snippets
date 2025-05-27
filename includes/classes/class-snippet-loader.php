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
     * Process files
     *
     * @param array $files Array of files to process
     * @param string $type Type of files (php, css, js)
     * @return array Processed files
     */
    public function process_files($files, $type) {
        $final = array();
        
        foreach ($files as $id => $data) {
            if (!isset($data['file'])) continue;
            
            // Validate file type
            if ($type === 'css' && substr($data['file'], -4) !== '.css') continue;
            if ($type === 'js' && substr($data['file'], -3) !== '.js') continue;
            if ($type === 'php' && substr($data['file'], -4) !== '.php') continue;
            
            // Ensure required keys exist and get source name
            $path_info = \Arsol_WP_Snippets\Helper::normalize_path($data['file']);
            $data = array_merge(array(
                'context' => \Arsol_WP_Snippets\Helper::get_default_options('context'),
                'loading_order' => \Arsol_WP_Snippets\Helper::get_default_options('loading_order'),
                'source_name' => $path_info['source_name']
            ), $data);
            
            $final[$id] = $data;
        }
        
        return array('files' => $final);
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
            error_log('Arsol WP Snippets: Safe mode is enabled, skipping frontend snippets');
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
            error_log('Arsol WP Snippets: Safe mode is enabled, skipping admin snippets');
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
            foreach ($enabled_php_files as $file_key => $enabled) {
                if ($enabled && !$this->is_safe_mode()) {
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
    public function load_css_snippets($context = 'frontend') {
        $css_files = apply_filters('arsol_wp_snippets_css_addon_files', array());
        $enabled_files = get_option('arsol_wp_snippets_enabled_css_files', array());

        foreach ($css_files as $handle => $file_data) {
            if (!isset($enabled_files[$handle]) || !$enabled_files[$handle]) {
                continue;
            }

            if (!isset($file_data['file']) || !file_exists($file_data['file'])) {
                continue;
            }

            // Get version - use specified version, fallback to filemtime, or null
            $version = isset($file_data['version']) ? $file_data['version'] : 
                      (file_exists($file_data['file']) ? filemtime($file_data['file']) : null);

            wp_register_style(
                $handle,
                $file_data['file'],
                isset($file_data['dependencies']) ? $file_data['dependencies'] : array(),
                $version
            );

            if ($context === 'frontend' && (!isset($file_data['context']) || $file_data['context'] === 'frontend')) {
                wp_enqueue_style($handle);
            } elseif ($context === 'admin' && isset($file_data['context']) && $file_data['context'] === 'admin') {
                wp_enqueue_style($handle);
            }
        }
    }

    /**
     * Load JS snippets for specific context
     * 
     * @param string $context The context to load snippets for ('frontend' or 'admin')
     */
    public function load_js_snippets($context = 'frontend') {
        $js_files = apply_filters('arsol_wp_snippets_js_addon_files', array());
        $enabled_files = get_option('arsol_wp_snippets_enabled_js_files', array());

        foreach ($js_files as $handle => $file_data) {
            if (!isset($enabled_files[$handle]) || !$enabled_files[$handle]) {
                continue;
            }

            if (!isset($file_data['file']) || !file_exists($file_data['file'])) {
                continue;
            }

            // Get version - use specified version, fallback to filemtime, or null
            $version = isset($file_data['version']) ? $file_data['version'] : 
                      (file_exists($file_data['file']) ? filemtime($file_data['file']) : null);

            wp_register_script(
                $handle,
                $file_data['file'],
                isset($file_data['dependencies']) ? $file_data['dependencies'] : array(),
                $version,
                isset($file_data['in_footer']) ? $file_data['in_footer'] : true
            );

            if ($context === 'frontend' && (!isset($file_data['context']) || $file_data['context'] === 'frontend')) {
                wp_enqueue_script($handle);
            } elseif ($context === 'admin' && isset($file_data['context']) && $file_data['context'] === 'admin') {
                wp_enqueue_script($handle);
            }
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

        // Process files
        $result = $this->process_files($php_files, 'php');
        $php_files = $result['files'];

        if (!isset($php_files[$file_key])) {
            error_log('Arsol WP Snippets: PHP file not found - ' . $file_key);
            return;
        }

        $file_data = $php_files[$file_key];
        
        if (file_exists($file_data['file'])) {
            include_once $file_data['file'];
            error_log('Arsol WP Snippets: Loaded PHP file - ' . $file_data['file']);
            
            // Trigger action when PHP file is loaded
            do_action('arsol_wp_snippets_loaded_php_addon', $file_key, $file_data);
        } else {
            error_log('Arsol WP Snippets: PHP file does not exist - ' . $file_data['file']);
        }
    }
}