<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

class Setup {
    public function __construct() {
        $this->require_files();
        $this->instantiate_classes();
        add_action('plugins_loaded', array($this, 'init'));
        
        // Add activation and deactivation hooks
        register_activation_hook(ARSOL_WP_SNIPPETS_PLUGIN_FILE, array($this, 'activate'));
        register_deactivation_hook(ARSOL_WP_SNIPPETS_PLUGIN_FILE, array($this, 'deactivate'));
    }

    public function init() {
        // Load plugin text domain
        // load_plugin_textdomain('arsol-wp-snippets', false, dirname(ARSOL_WP_SNIPPETS_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Plugin activation hook callback
     */
    public function activate() {
        $this->add_config_constants();
    }

    /**
     * Plugin deactivation hook callback
     */
    public function deactivate() {
        $this->remove_config_constants();
    }

    /**
     * Add constants to wp-config.php
     */
    private function add_config_constants() {
        $config_file = ABSPATH . 'wp-config.php';
        
        // Check if wp-config.php exists and is writable
        if (!file_exists($config_file) || !is_writable($config_file)) {
            return;
        }
        
        // Read the config file
        $config_content = file_get_contents($config_file);
        
        // Check if our constant is already defined
        if (strpos($config_content, 'ARSOL_WP_SNIPPETS_SAFE_MODE') !== false) {
            return;
        }
        
        // Prepare the constant definition
        $constant_definition = "\n// Arsol WP Snippets Safe Mode\nif (!defined('ARSOL_WP_SNIPPETS_SAFE_MODE')) {\n    define('ARSOL_WP_SNIPPETS_SAFE_MODE', false);\n}\n";
        
        // Find the position to insert our constant (before the first require_once)
        $insert_position = strpos($config_content, "require_once");
        if ($insert_position !== false) {
            $new_content = substr_replace($config_content, $constant_definition, $insert_position, 0);
            
            // Write the modified content back to wp-config.php
            file_put_contents($config_file, $new_content);
        }
    }

    /**
     * Remove constants from wp-config.php
     */
    private function remove_config_constants() {
        $config_file = ABSPATH . 'wp-config.php';
        
        // Check if wp-config.php exists and is writable
        if (!file_exists($config_file) || !is_writable($config_file)) {
            return;
        }
        
        // Read the config file
        $config_content = file_get_contents($config_file);
        
        // Pattern to match our constant block
        $pattern = "/\n\/\/ Arsol WP Snippets Safe Mode\nif \(!defined\('ARSOL_WP_SNIPPETS_SAFE_MODE'\)\) {\n    define\('ARSOL_WP_SNIPPETS_SAFE_MODE', false\);\n}\n/";
        
        // Remove the constant block if it exists
        $new_content = preg_replace($pattern, '', $config_content);
        
        // Write the modified content back to wp-config.php
        if ($new_content !== $config_content) {
            file_put_contents($config_file, $new_content);
        }
    }

    /**
     * Include necessary files.
     */
    private function require_files() {
        // Core Classes
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-assets.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-admin-settings.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-theme-support.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-snippet-loader.php';

        // Core Functions 
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-admin.php';
    }

    /**
     * Instantiate plugin classes.
     */
    private function instantiate_classes() {
        new \Arsol_WP_Snippets\Assets();
        new \Arsol_WP_Snippets\Admin_Settings();
        new \Arsol_WP_Snippets\Shortcodes();
        new \Arsol_WP_Snippets\Theme_Support();
        new \Arsol_WP_Snippets\Snippet_Loader();
    }
}