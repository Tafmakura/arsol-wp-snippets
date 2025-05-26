<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Snippet Loader class to handle loading and executing snippets
 */
class Snippet_Loader {
    
    /**
     * Track loaded files to prevent duplicates
     *
     * @var array
     */
    private static $loaded_files = array();
    
    /**
     * Track loaded file keys to prevent duplicates
     *
     * @var array
     */
    private static $loaded_keys = array();
    
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
     * Check if a file has already been loaded
     *
     * @param string $file_path The file path to check
     * @param string $file_key The file key to check
     * @return bool True if file is already loaded
     */
    private function is_file_already_loaded($file_path, $file_key) {
        // Check if we've already loaded this exact file
        if (in_array($file_path, self::$loaded_files)) {
            error_log('Arsol WP Snippets: Duplicate file path detected: ' . $file_path);
            return true;
        }

        // Check if we've already loaded a file with this key
        if (in_array($file_key, self::$loaded_keys)) {
            error_log('Arsol WP Snippets: Duplicate file key detected: ' . $file_key);
            return true;
        }

        return false;
    }

    /**
     * Add a file to the loaded files list
     *
     * @param string $file_path The file path to add
     * @param string $file_key The file key to add
     */
    private function add_loaded_file($file_path, $file_key) {
        self::$loaded_files[] = $file_path;
        self::$loaded_keys[] = $file_key;
        error_log('Arsol WP Snippets: Added to loaded files - Path: ' . $file_path . ', Key: ' . $file_key);
    }

    /**
     * Display an admin notice for duplicate files
     *
     * @param string $file_path The duplicate file path
     * @param string $file_key The duplicate file key
     */
    private function display_duplicate_file_notice($file_path, $file_key) {
        add_action('admin_notices', function() use ($file_path, $file_key) {
            ?>
            <div class="notice notice-error">
                <p>
                    <strong>Arsol WP Snippets:</strong> 
                    Attached snippet already loaded → <?php echo esc_html($file_path); ?>
                    <br>
                    <small>File key: <?php echo esc_html($file_key); ?></small>
                </p>
            </div>
            <?php
        });
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
    private function load_css_snippets($context) {
        // Get options
        $options = get_option('arsol_wp_snippets_options', array());
        $enabled_css_files = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        
        if (empty($enabled_css_files)) {
            error_log('Arsol WP Snippets: No CSS files enabled for ' . $context);
            return;
        }

        // Get all available CSS files through filter
        $css_files = apply_filters('arsol_wp_snippets_css_addon_files', array());
        error_log('Arsol WP Snippets: Available CSS files: ' . print_r($css_files, true));

        foreach ($enabled_css_files as $file_key => $enabled) {
            if (!$enabled || !isset($css_files[$file_key])) {
                continue;
            }

            $file_data = $css_files[$file_key];

            // Skip if context doesn't match
            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                error_log('Arsol WP Snippets: Skipping CSS file ' . $file_key . ' - context mismatch');
                continue;
            }

            // Check for duplicate files
            if ($this->is_file_already_loaded($file_data['file'], $file_key)) {
                $this->display_duplicate_file_notice($file_data['file'], $file_key);
                error_log('Arsol WP Snippets: Duplicate CSS file detected - ' . $file_data['file']);
                continue;
            }

            // Register and enqueue the CSS file
            $handle = 'arsol-wp-snippets-css-' . $file_key;
            $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
            $version = isset($file_data['version']) ? $file_data['version'] : arsol_wp_snippets_get_version();

            error_log('Arsol WP Snippets: Registering CSS file - ' . $file_data['file'] . ' with handle ' . $handle);

            wp_register_style(
                $handle,
                $file_data['file'],
                $dependencies,
                $version
            );

            wp_enqueue_style($handle);

            // Add to loaded files
            $this->add_loaded_file($file_data['file'], $file_key);

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
            error_log('Arsol WP Snippets: No JS files enabled for ' . $context);
            return;
        }

        // Get all available JS files through filter
        $js_files = apply_filters('arsol_wp_snippets_js_addon_files', array());
        error_log('Arsol WP Snippets: Available JS files: ' . print_r($js_files, true));

        foreach ($enabled_js_files as $file_key => $enabled) {
            if (!$enabled || !isset($js_files[$file_key])) {
                continue;
            }

            $file_data = $js_files[$file_key];

            // Skip if context doesn't match
            if (isset($file_data['context']) && $file_data['context'] !== $context) {
                error_log('Arsol WP Snippets: Skipping JS file ' . $file_key . ' - context mismatch');
                continue;
            }

            // Check for duplicate files
            if ($this->is_file_already_loaded($file_data['file'], $file_key)) {
                $this->display_duplicate_file_notice($file_data['file'], $file_key);
                error_log('Arsol WP Snippets: Duplicate JS file detected - ' . $file_data['file']);
                continue;
            }

            // Register and enqueue the JS file
            $handle = 'arsol-wp-snippets-js-' . $file_key;
            $dependencies = isset($file_data['dependencies']) ? $file_data['dependencies'] : array();
            $version = isset($file_data['version']) ? $file_data['version'] : arsol_wp_snippets_get_version();
            $in_footer = isset($file_data['position']) && $file_data['position'] === 'footer';

            error_log('Arsol WP Snippets: Registering JS file - ' . $file_data['file'] . ' with handle ' . $handle);

            wp_register_script(
                $handle,
                $file_data['file'],
                $dependencies,
                $version,
                $in_footer
            );

            wp_enqueue_script($handle);

            // Add to loaded files
            $this->add_loaded_file($file_data['file'], $file_key);

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
            error_log('Arsol WP Snippets: PHP file not found - ' . $file_key);
            return;
        }

        $file_data = $php_files[$file_key];
        
        // Check for duplicate files
        if ($this->is_file_already_loaded($file_data['file'], $file_key)) {
            $this->display_duplicate_file_notice($file_data['file'], $file_key);
            error_log('Arsol WP Snippets: Duplicate PHP file detected - ' . $file_data['file']);
            return;
        }
        
        if (file_exists($file_data['file'])) {
            include_once $file_data['file'];
            error_log('Arsol WP Snippets: Loaded PHP file - ' . $file_data['file']);
            
            // Add to loaded files
            $this->add_loaded_file($file_data['file'], $file_key);
            
            // Trigger action when PHP file is loaded
            do_action('arsol_wp_snippets_loaded_php_addon', $file_key, $file_data);
        } else {
            error_log('Arsol WP Snippets: PHP file does not exist - ' . $file_data['file']);
        }
    }
}