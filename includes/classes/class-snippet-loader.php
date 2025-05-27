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
        // Register all snippets during initialization
        $this->register_snippets();
        
        // Main hooks for loading
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_snippets'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_snippets'));
    }

    /**
     * Sort files by priority, loading order, and maintain original order
     * 
     * @param array $files Array of files to sort
     * @return array Sorted files
     */
    private function sort_files($files) {
        // Convert to array for sorting while preserving original order
        $files_array = array();
        $index = 0;
        foreach ($files as $key => $file) {
            $files_array[] = array(
                'key' => $key,
                'file' => $file,
                'priority' => isset($file['priority']) ? intval($file['priority']) : 10,
                'loading_order' => isset($file['loading_order']) ? intval($file['loading_order']) : 10,
                'original_index' => $index++
            );
        }

        // Log pre-sort state
        error_log('Arsol WP Snippets: Pre-sort files:');
        foreach ($files_array as $item) {
            error_log(sprintf(
                'File: %s, Priority: %d, Loading Order: %d, Original Index: %d',
                $item['key'],
                $item['priority'],
                $item['loading_order'],
                $item['original_index']
            ));
        }

        // Sort by priority, then loading_order, then original order
        usort($files_array, function($a, $b) {
            if ($a['priority'] !== $b['priority']) {
                return $a['priority'] - $b['priority'];
            }
            if ($a['loading_order'] !== $b['loading_order']) {
                return $a['loading_order'] - $b['loading_order'];
            }
            return $a['original_index'] - $b['original_index'];
        });

        // Log post-sort state
        error_log('Arsol WP Snippets: Post-sort files:');
        foreach ($files_array as $item) {
            error_log(sprintf(
                'File: %s, Priority: %d, Loading Order: %d, Original Index: %d',
                $item['key'],
                $item['priority'],
                $item['loading_order'],
                $item['original_index']
            ));
        }

        // Convert back to associative array
        $sorted_files = array();
        foreach ($files_array as $item) {
            $sorted_files[$item['key']] = $item['file'];
        }

        return $sorted_files;
    }

    /**
     * Register all snippets with their priorities
     */
    private function register_snippets() {
        $this->register_php_snippets();
        $this->register_css_snippets('frontend');
        $this->register_css_snippets('admin');
        $this->register_js_snippets('frontend');
        $this->register_js_snippets('admin');
    }

    /**
     * Register PHP snippets
     */
    private function register_php_snippets() {
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_files = isset($options['php_addon_options']) ? $options['php_addon_options'] : array();
        
        if (!empty($enabled_files)) {
            error_log('Arsol WP Snippets: Registering PHP snippets');
            $files = apply_filters('arsol_wp_snippets_php_addon_files', array());
            $result = $this->process_files($files, 'php');
            $files = $result['files'];
            
            // Sort files by priority and loading order
            $sorted_files = $this->sort_files($files);
            
            foreach ($sorted_files as $file_key => $file_data) {
                if (!isset($enabled_files[$file_key]) || !$enabled_files[$file_key]) {
                    continue;
                }
                
                $priority = isset($file_data['priority']) ? intval($file_data['priority']) : 10;
                error_log(sprintf(
                    'Arsol WP Snippets: Registering PHP file %s with priority %d',
                    $file_key,
                    $priority
                ));
                
                add_action('init', function() use ($file_key) {
                    $this->include_php_snippet($file_key);
                }, $priority);
            }
        }
    }

    /**
     * Register CSS snippets for a specific context
     * 
     * @param string $context The context ('frontend' or 'admin')
     */
    private function register_css_snippets($context) {
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_files = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        
        if (empty($enabled_files)) {
            return;
        }

        $hook = $context === 'frontend' ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';
        error_log(sprintf('Arsol WP Snippets: Registering CSS snippets for %s context', $context));
        
        $files = apply_filters('arsol_wp_snippets_css_addon_files', array());
        $result = $this->process_files($files, 'css');
        $files = $result['files'];

        // Sort files by priority and loading order
        $sorted_files = $this->sort_files($files);

        foreach ($sorted_files as $file_key => $file_data) {
            if (!isset($enabled_files[$file_key]) || !$enabled_files[$file_key]) {
                continue;
            }

            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                continue;
            }

            $priority = isset($file_data['priority']) ? intval($file_data['priority']) : 10;
            error_log(sprintf(
                'Arsol WP Snippets: Registering CSS file %s with priority %d',
                $file_key,
                $priority
            ));
            
            add_action($hook, function() use ($file_data, $file_key) {
                $handle = "arsol-wp-snippets-css-{$file_key}";
                $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
                $version = $this->get_file_version($file_data['file'], isset($file_data['version']) ? $file_data['version'] : null, $file_data);
                
                wp_register_style($handle, $file_data['file'], $dependencies, $version);
                wp_enqueue_style($handle);
            }, $priority);
        }
    }

    /**
     * Register JS snippets for a specific context
     * 
     * @param string $context The context ('frontend' or 'admin')
     */
    private function register_js_snippets($context) {
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_files = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
        
        if (empty($enabled_files)) {
            return;
        }

        $hook = $context === 'frontend' ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';
        error_log(sprintf('Arsol WP Snippets: Registering JS snippets for %s context', $context));
        
        $files = apply_filters('arsol_wp_snippets_js_addon_files', array());
        $result = $this->process_files($files, 'js');
        $files = $result['files'];

        // Sort files by priority and loading order
        $sorted_files = $this->sort_files($files);

        foreach ($sorted_files as $file_key => $file_data) {
            if (!isset($enabled_files[$file_key]) || !$enabled_files[$file_key]) {
                continue;
            }

            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                continue;
            }

            $priority = isset($file_data['priority']) ? intval($file_data['priority']) : 10;
            error_log(sprintf(
                'Arsol WP Snippets: Registering JS file %s with priority %d',
                $file_key,
                $priority
            ));
            
            add_action($hook, function() use ($file_data, $file_key) {
                $handle = "arsol-wp-snippets-js-{$file_key}";
                $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
                $version = $this->get_file_version($file_data['file'], isset($file_data['version']) ? $file_data['version'] : null, $file_data);
                $in_footer = isset($file_data['position']) && $file_data['position'] === 'footer';
                
                wp_register_script($handle, $file_data['file'], $dependencies, $version, $in_footer);
                wp_enqueue_script($handle);
            }, $priority);
        }
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
     * Load CSS snippets for specific context
     * 
     * @param string $context The context to load snippets for ('frontend' or 'admin')
     */
    private function load_css_snippets($context) {
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_css_files = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        
        if (empty($enabled_css_files)) {
            return;
        }

        $css_files = apply_filters('arsol_wp_snippets_css_addon_files', array());
        $result = $this->process_files($css_files, 'css');
        $css_files = $result['files'];

        foreach ($enabled_css_files as $file_key => $enabled) {
            if (!$enabled || !isset($css_files[$file_key])) {
                continue;
            }

            $file_data = $css_files[$file_key];
            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                continue;
            }

            $priority = isset($file_data['priority']) ? intval($file_data['priority']) : 10;
            
            // Add the file to be loaded at its specified priority
            $hook = $context === 'frontend' ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';
            add_action($hook, function() use ($file_data, $file_key) {
                $handle = 'arsol-wp-snippets-css-' . $file_key;
                $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
                $version = $this->get_file_version($file_data['file'], isset($file_data['version']) ? $file_data['version'] : null, $file_data);
                
                wp_register_style($handle, $file_data['file'], $dependencies, $version);
                wp_enqueue_style($handle);
            }, $priority);
        }
    }

    /**
     * Load JS snippets for specific context
     * 
     * @param string $context The context to load snippets for ('frontend' or 'admin')
     */
    private function load_js_snippets($context) {
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_js_files = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
        
        if (empty($enabled_js_files)) {
            return;
        }

        $js_files = apply_filters('arsol_wp_snippets_js_addon_files', array());
        $result = $this->process_files($js_files, 'js');
        $js_files = $result['files'];

        foreach ($enabled_js_files as $file_key => $enabled) {
            if (!$enabled || !isset($js_files[$file_key])) {
                continue;
            }

            $file_data = $js_files[$file_key];
            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                continue;
            }

            $priority = isset($file_data['priority']) ? intval($file_data['priority']) : 10;
            
            // Add the file to be loaded at its specified priority
            $hook = $context === 'frontend' ? 'wp_enqueue_scripts' : 'admin_enqueue_scripts';
            add_action($hook, function() use ($file_data, $file_key) {
                $handle = 'arsol-wp-snippets-js-' . $file_key;
                $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
                $version = $this->get_file_version($file_data['file'], isset($file_data['version']) ? $file_data['version'] : null, $file_data);
                $in_footer = isset($file_data['position']) && $file_data['position'] === 'footer';
                
                wp_register_script($handle, $file_data['file'], $dependencies, $version, $in_footer);
                wp_enqueue_script($handle);
            }, $priority);
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

    /**
     * Convert URL to local file path
     * 
     * @param string $url The URL to convert
     * @return string|false The local file path or false if conversion fails
     */
    private function url_to_local_path($url) {
        // Remove protocol and domain
        $path = str_replace(array('http://', 'https://'), '', $url);
        $path = preg_replace('/^[^\/]+\//', '', $path);
        
        // Convert to absolute server path
        $local_path = ABSPATH . $path;
        
        // Check if file exists
        if (file_exists($local_path)) {
            return $local_path;
        }
        
        return false;
    }

    /**
     * Get file version
     * 
     * @param string $file_path The file path or URL
     * @param string|null $explicit_version Explicit version if set
     * @param array $file_data Additional file data
     * @return string The version number
     */
    private function get_file_version($file_path, $explicit_version = null, $file_data = array()) {
        // If explicit version is set, use it
        if ($explicit_version !== null) {
            return $explicit_version;
        }
        
        // Try to get local path from file_data first
        $local_path = isset($file_data['local_path']) ? $file_data['local_path'] : $this->url_to_local_path($file_path);
        
        // If we have a local path and can get filemtime, use it
        if ($local_path && ($mtime = filemtime($local_path)) !== false) {
            return $mtime;
        }
        
        // Fallback to current timestamp if we can't get filemtime
        return time();
    }
}