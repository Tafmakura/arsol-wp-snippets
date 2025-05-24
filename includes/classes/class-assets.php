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
        
        // Register CSS with prefixed filename
        wp_register_style(
            'arsol-wp-snippets-frontend',
            $plugin_url . 'assets/css/arsol-wp-snippets-frontend.css',
            array(),
            ARSOL_WP_SNIPPETS_ASSETS_VERSION
        );
        
        // Register JS with prefixed filename
        wp_register_script(
            'arsol-wp-snippets-frontend',
            $plugin_url . 'assets/js/arsol-wp-snippets-frontend.js',
            array('jquery'),
            ARSOL_WP_SNIPPETS_ASSETS_VERSION,
            true
        );
    }

    /**
     * Enqueue frontend assets on appropriate pages
     */
    public function enqueue_frontend_assets() {
        // Load CSS addons on all frontend pages
        wp_enqueue_style('arsol-wp-snippets-frontend');
        
        // Only load JS if needed for interactive CSS features
        if (apply_filters('arsol_wp_snippets_load_js', true)) {
            wp_enqueue_script('arsol-wp-snippets-frontend');
            
            // Add localized data if needed
            wp_localize_script('arsol-wp-snippets-frontend', 'arsolCssAddons', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arsol-wp-snippets-frontend'),
                'i18n' => array(
                    'loadingMessage' => __('Loading styles...', 'arsol-wp-snippets'),
                )
            ));
        }
    }

    /**
     * Register admin CSS and JS
     */
    public function register_admin_assets() {
        $plugin_url = plugin_dir_url(ARSOL_WP_SNIPPETS_PLUGIN_FILE);
        
        // Register CSS with prefixed filename
        wp_register_style(
            'arsol-wp-snippets-admin',
            $plugin_url . 'assets/css/arsol-wp-snippets-admin.css',
            array(),
            ARSOL_WP_SNIPPETS_ASSETS_VERSION
        );
        
        // Register JS with prefixed filename
        wp_register_script(
            'arsol-wp-snippets-admin',
            $plugin_url . 'assets/js/arsol-wp-snippets-admin.js',
            array('jquery'),
            ARSOL_WP_SNIPPETS_ASSETS_VERSION,
            true
        );

        // For code editor if used in the admin settings
        wp_register_script(
            'arsol-wp-snippets-editor',
            $plugin_url . 'assets/js/arsol-wp-snippets-editor.js',
            array('jquery', 'wp-codemirror'),
            ARSOL_WP_SNIPPETS_ASSETS_VERSION,
            true
        );
    }

    /**
     * Enqueue admin assets on appropriate pages
     * 
     * @param string $hook Current admin page hook
     */
    public function enqueue_admin_assets($hook) {
        // Get current screen to check page
        $screen = get_current_screen();
        
        // Only load on specific admin pages (CSS settings, customizer, etc.)
        $load_assets = false;
        
        // Check if we're on our plugin's settings page
        if (
            $hook === 'settings_page_arsol-wp-snippets' || 
            $hook === 'appearance_page_arsol-wp-snippets' ||
            ($hook === 'customize.php') ||
            ($hook === 'post.php' && function_exists('get_current_screen') && 
             in_array($screen->post_type, apply_filters('arsol_wp_snippets_post_types', array('post', 'page'))))
        ) {
            $load_assets = true;
        }
        
        if ($load_assets) {
            wp_enqueue_style('arsol-wp-snippets-admin');
            wp_enqueue_script('arsol-wp-snippets-admin');
            
            // Load code editor assets if on settings page
            if ($hook === 'settings_page_arsol-wp-snippets' || $hook === 'appearance_page_arsol-wp-snippets') {
                wp_enqueue_code_editor(array('type' => 'text/css'));
                wp_enqueue_script('arsol-wp-snippets-editor');
            }
            
            // Add localized data if needed
            wp_localize_script('arsol-wp-snippets-admin', 'arsolCssAddons', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arsol-wp-snippets-admin'),
                'i18n' => array(
                    'confirmReset' => __('Are you sure you want to reset all custom CSS? This cannot be undone.', 'arsol-wp-snippets'),
                    'saved' => __('CSS saved successfully.', 'arsol-wp-snippets'),
                )
            ));
        }
    }
}