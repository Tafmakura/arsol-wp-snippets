<?php

namespace Arsol_CSS_Addons;

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
        $plugin_url = plugin_dir_url(ARSOL_CSS_ADDONS_PLUGIN_FILE);
        
        // Register CSS with prefixed filename
        wp_register_style(
            'arsol-css-addons-frontend',
            $plugin_url . 'assets/css/arsol-css-addons-frontend.css',
            array(),
            ARSOL_CSS_ADDONS_ASSETS_VERSION
        );
        
        // Register JS with prefixed filename
        wp_register_script(
            'arsol-css-addons-frontend',
            $plugin_url . 'assets/js/arsol-css-addons-frontend.js',
            array('jquery'),
            ARSOL_CSS_ADDONS_ASSETS_VERSION,
            true
        );
    }

    /**
     * Enqueue frontend assets on appropriate pages
     */
    public function enqueue_frontend_assets() {
        // Load CSS addons on all frontend pages
        wp_enqueue_style('arsol-css-addons-frontend');
        
        // Only load JS if needed for interactive CSS features
        if (apply_filters('arsol_css_addons_load_js', true)) {
            wp_enqueue_script('arsol-css-addons-frontend');
            
            // Add localized data if needed
            wp_localize_script('arsol-css-addons-frontend', 'arsolCssAddons', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arsol-css-addons-frontend'),
                'i18n' => array(
                    'loadingMessage' => __('Loading styles...', 'arsol-css-addons'),
                )
            ));
        }
    }

    /**
     * Register admin CSS and JS
     */
    public function register_admin_assets() {
        $plugin_url = plugin_dir_url(ARSOL_CSS_ADDONS_PLUGIN_FILE);
        
        // Register CSS with prefixed filename
        wp_register_style(
            'arsol-css-addons-admin',
            $plugin_url . 'assets/css/arsol-css-addons-admin.css',
            array(),
            ARSOL_CSS_ADDONS_ASSETS_VERSION
        );
        
        // Register JS with prefixed filename
        wp_register_script(
            'arsol-css-addons-admin',
            $plugin_url . 'assets/js/arsol-css-addons-admin.js',
            array('jquery'),
            ARSOL_CSS_ADDONS_ASSETS_VERSION,
            true
        );

        // For code editor if used in the admin settings
        wp_register_script(
            'arsol-css-addons-editor',
            $plugin_url . 'assets/js/arsol-css-addons-editor.js',
            array('jquery', 'wp-codemirror'),
            ARSOL_CSS_ADDONS_ASSETS_VERSION,
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
            $hook === 'settings_page_arsol-css-addons' || 
            $hook === 'appearance_page_arsol-css-addons' ||
            ($hook === 'customize.php') ||
            ($hook === 'post.php' && function_exists('get_current_screen') && 
             in_array($screen->post_type, apply_filters('arsol_css_addons_post_types', array('post', 'page'))))
        ) {
            $load_assets = true;
        }
        
        if ($load_assets) {
            wp_enqueue_style('arsol-css-addons-admin');
            wp_enqueue_script('arsol-css-addons-admin');
            
            // Load code editor assets if on settings page
            if ($hook === 'settings_page_arsol-css-addons' || $hook === 'appearance_page_arsol-css-addons') {
                wp_enqueue_code_editor(array('type' => 'text/css'));
                wp_enqueue_script('arsol-css-addons-editor');
            }
            
            // Add localized data if needed
            wp_localize_script('arsol-css-addons-admin', 'arsolCssAddons', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('arsol-css-addons-admin'),
                'i18n' => array(
                    'confirmReset' => __('Are you sure you want to reset all custom CSS? This cannot be undone.', 'arsol-css-addons'),
                    'saved' => __('CSS saved successfully.', 'arsol-css-addons'),
                )
            ));
        }
    }
}