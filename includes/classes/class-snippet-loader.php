<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Snippet Loader class to handle loading and executing snippets yay
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
        // Load enabled PHP snippet files
        $enabled_php_files = get_option('arsol_wp_snippets_enabled_php_files', array());
        
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
     */
    private function load_css_snippets($context) {
        // This would call your existing CSS loading functions
        // from functions-addon-css.php
    }

    /**
     * Load JS snippets for specific context
     */
    private function load_js_snippets($context) {
        // This would call your existing JS loading functions
        // from functions-addon-js.php
    }

    /**
     * Include PHP snippet file
     */
    private function include_php_snippet($file_key) {
        // This would call your existing PHP loading functions
        // from functions-addon-php.php
    }
}