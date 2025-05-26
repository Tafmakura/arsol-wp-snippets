<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Assets class to manage CSS and JS files
 */
class Assets {
    /**
     * Constructor
     */
    public function __construct() {
        // Register hooks for frontend assets
        add_action('wp_enqueue_scripts', array($this, 'register_frontend_assets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Register hooks for admin assets
        add_action('admin_enqueue_scripts', array($this, 'register_admin_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Register frontend CSS and JS
     */
    public function register_frontend_assets() {
        $plugin_url = plugin_dir_url(ARSOL_WP_SNIPPETS_PLUGIN_FILE);
        
        // Register CSS (loads in header by default)
        wp_register_style(
            'arsol-wp-snippets-frontend',
            $plugin_url . 'assets/css/arsol-wp-snippets-frontend.css',
            array(),
            ARSOL_WP_SNIPPETS_VERSION
        );
        
        // Register JS (loads in footer with true parameter)
        wp_register_script(
            'arsol-wp-snippets-frontend',
            $plugin_url . 'assets/js/arsol-wp-snippets-frontend.js',
            array('jquery'),
            ARSOL_WP_SNIPPETS_VERSION,
            true // Load in footer
        );
    }

    /**
     * Enqueue frontend assets on appropriate pages
     */
    public function enqueue_frontend_assets() {
        // Only load on frontend (not admin)
        if (!is_admin()) {
            // Load CSS (header)
            wp_enqueue_style('arsol-wp-snippets-frontend');
            
            // Load JS (footer)
            wp_enqueue_script('arsol-wp-snippets-frontend');
        }
    }

    /**
     * Register admin CSS and JS
     */
    public function register_admin_assets() {
        $plugin_url = plugin_dir_url(ARSOL_WP_SNIPPETS_PLUGIN_FILE);
        
        // Register CSS (loads in header by default)
        wp_register_style(
            'arsol-wp-snippets-admin',
            $plugin_url . 'assets/css/arsol-wp-snippets-admin.css',
            array(),
            ARSOL_WP_SNIPPETS_VERSION
        );
        
        // Register JS (loads in footer with true parameter)
        wp_register_script(
            'arsol-wp-snippets-admin',
            $plugin_url . 'assets/js/arsol-wp-snippets-admin.js',
            array('jquery'),
            ARSOL_WP_SNIPPETS_VERSION,
            true // Load in footer
        );

        // For code editor if used in the admin settings
        wp_register_script(
            'arsol-wp-snippets-editor',
            $plugin_url . 'assets/js/arsol-wp-snippets-editor.js',
            array('jquery', 'wp-codemirror'),
            ARSOL_WP_SNIPPETS_VERSION,
            true // Load in footer
        );
    }

    /**
     * Enqueue admin assets on appropriate pages
     * 
     * @param string $hook Current admin page hook
     */
    public function enqueue_admin_assets($hook) {
        // Load on all admin pages for now - you can make this more specific later
        wp_enqueue_style('arsol-wp-snippets-admin');
        wp_enqueue_script('arsol-wp-snippets-admin');
        
        // Load code editor assets only on specific pages
        if (
            $hook === 'toplevel_page_arsol-wp-snippets' || 
            strpos($hook, 'arsol-wp-snippets') !== false
        ) {
            if (function_exists('wp_enqueue_code_editor')) {
                wp_enqueue_code_editor(array('type' => 'text/css'));
            }
            wp_enqueue_script('arsol-wp-snippets-editor');
            
            // Add localized data
            wp_localize_script('arsol-wp-snippets-admin', 'arsolWpSnippets', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arsol-wp-snippets-admin'),
                'i18n' => array(
                    'confirmReset' => __('Are you sure you want to reset all settings? This cannot be undone.', 'arsol-wp-snippets'),
                    'saved' => __('Settings saved successfully.', 'arsol-wp-snippets'),
                )
            ));
        }
    }
}