<?php
namespace Arsol_CSS_Addons;

/**
 * Admin Settings Controller Class
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Settings {
    /**
     * CSS Addons page slug
     *
     * @var string
     */
    private $css_addons_slug = 'arsol-css-addons';

    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue CSS files based on settings
        add_action('admin_enqueue_scripts', array($this, 'load_admin_css_files'));
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_css_files'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add main menu page
        add_menu_page(
            __('Arsol CSS Addons', 'arsol-css-addons'), // Page title
            __('Arsol CSS Addons', 'arsol-css-addons'), // Menu title
            'manage_options',
            $this->css_addons_slug,
            array($this, 'display_css_addons_page'),
            'dashicons-admin-customizer',
            30
        );
        
        // Remove the automatically created submenu item
        remove_submenu_page($this->css_addons_slug, $this->css_addons_slug);
    }
    
    /**
     * Display CSS Addons settings page
     */
    public function display_css_addons_page() {
        // Set variables that will be available to the template
        $page_title = get_admin_page_title();
        $settings_slug = $this->css_addons_slug;
        
        // Include the template file
        include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/templates/admin/settings-page.php';
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            'arsol_css_addons_settings',
            'arsol_css_addons_options',
            array($this, 'sanitize_settings')
        );
        
        // Add Admin CSS Section
        add_settings_section(
            'arsol_css_addons_admin',
            __('Admin', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS enhancements for the WordPress admin area.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add admin CSS options
        add_settings_field(
            'admin_css_options',
            __('Admin addon CSS files', 'arsol-css-addons'),
            array($this, 'render_admin_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_admin'
        );
        
        // Add Frontend CSS Section
        add_settings_section(
            'arsol_css_addons_frontend',
            __('Frontend', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS enhancements for your website frontend.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add frontend CSS options
        add_settings_field(
            'frontend_css_options',
            __('Frontend addon CSS files', 'arsol-css-addons'),
            array($this, 'render_frontend_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_frontend'
        );
        
        // Add Global CSS Section - MOVED TO BOTTOM
        add_settings_section(
            'arsol_css_addons_global',
            __('Global', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS enhancements that apply globally.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add global CSS options
        add_settings_field(
            'global_css_options',
            __('Global addon CSS files', 'arsol-css-addons'),
            array($this, 'render_global_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_global'
        );
    }
    
    /**
     * Get available global CSS options
     *
     * @return array Array of global CSS options
     */
    public function get_global_css_options() {
        // Default empty array
        $global_css_options = array();
        
        /**
         * Filter the global CSS options
         * 
         * @param array $global_css_options Array of global CSS options with file paths
         */
        return apply_filters('arsol_css_addons_global_css_options', $global_css_options);
    }
    
    /**
     * Get available admin CSS options
     *
     * @return array Array of admin CSS options
     */
    public function get_admin_css_options() {
        // Default empty array
        $admin_css_options = array();
        
        /**
         * Filter the admin CSS options
         * 
         * @param array $admin_css_options Array of admin CSS options with file paths
         */
        return apply_filters('arsol_css_addons_admin_css_options', $admin_css_options);
    }
    
    /**
     * Get available frontend CSS options
     *
     * @return array Array of frontend CSS options
     */
    public function get_frontend_css_options() {
        // Default empty array
        $frontend_css_options = array();
        
        /**
         * Filter the frontend CSS options
         * 
         * @param array $frontend_css_options Array of frontend CSS options with file paths
         */
        return apply_filters('arsol_css_addons_frontend_css_options', $frontend_css_options);
    }
    
    /**
     * Render global CSS options checkboxes
     */
    public function render_global_css_options() {
        $options = get_option('arsol_css_addons_options', array());
        $global_css_options = isset($options['global_css_options']) ? $options['global_css_options'] : array();
        
        // Get CSS options from the filter
        $available_global_css = $this->get_global_css_options();
        
        if (empty($available_global_css)) {
            echo '<p>' . esc_html__('No global CSS files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_global_css as $css_id => $css_data) {
            // Set variables that will be available to the template
            $enabled_options = $global_css_options;
            $option_type = 'global';
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/css-file-checkbox.php';
        }
    }
    
    /**
     * Render admin CSS options checkboxes
     */
    public function render_admin_css_options() {
        $options = get_option('arsol_css_addons_options', array());
        $admin_css_options = isset($options['admin_css_options']) ? $options['admin_css_options'] : array();
        
        // Get CSS options from the filter
        $available_admin_css = $this->get_admin_css_options();
        
        if (empty($available_admin_css)) {
            echo '<p>' . esc_html__('No admin CSS files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_admin_css as $css_id => $css_data) {
            // Set variables that will be available to the template
            $enabled_options = $admin_css_options;
            $option_type = 'admin';
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/css-file-checkbox.php';
        }
    }
    
    /**
     * Render frontend CSS options checkboxes
     */
    public function render_frontend_css_options() {
        $options = get_option('arsol_css_addons_options', array());
        $frontend_css_options = isset($options['frontend_css_options']) ? $options['frontend_css_options'] : array();
        
        // Get CSS options from the filter
        $available_frontend_css = $this->get_frontend_css_options();
        
        if (empty($available_frontend_css)) {
            echo '<p>' . esc_html__('No frontend CSS files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_frontend_css as $css_id => $css_data) {
            // Set variables that will be available to the template
            $enabled_options = $frontend_css_options;
            $option_type = 'frontend';
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/css-file-checkbox.php';
        }
    }
    
    /**
     * Sanitize settings
     *
     * @param array $input The input array.
     * @return array
     */
    public function sanitize_settings($input) {
        $sanitized_input = array();
        
        // Sanitize global CSS file options
        $sanitized_input['global_css_options'] = array();
        if (isset($input['global_css_options']) && is_array($input['global_css_options'])) {
            foreach ($input['global_css_options'] as $css_id => $value) {
                $sanitized_input['global_css_options'][$css_id] = 1;
            }
        }
        
        // Sanitize admin CSS file options
        $sanitized_input['admin_css_options'] = array();
        if (isset($input['admin_css_options']) && is_array($input['admin_css_options'])) {
            foreach ($input['admin_css_options'] as $css_id => $value) {
                $sanitized_input['admin_css_options'][$css_id] = 1;
            }
        }
        
        // Sanitize frontend CSS file options
        $sanitized_input['frontend_css_options'] = array();
        if (isset($input['frontend_css_options']) && is_array($input['frontend_css_options'])) {
            foreach ($input['frontend_css_options'] as $css_id => $value) {
                $sanitized_input['frontend_css_options'][$css_id] = 1;
            }
        }
        
        return $sanitized_input;
    }
    
    /**
     * Load admin CSS files based on settings
     */
    public function load_admin_css_files() {
        // Only run in admin
        if (!is_admin()) {
            return;
        }
        
        $options = get_option('arsol_css_addons_options', array());
        
        // Load global CSS files
        if (isset($options['global_css_options']) && !empty($options['global_css_options'])) {
            $global_css_options = $this->get_global_css_options();
            $enabled_global_options = $options['global_css_options'];
            
            // Loop through enabled global files and enqueue them
            foreach ($enabled_global_options as $css_id => $enabled) {
                if ($enabled && isset($global_css_options[$css_id])) {
                    wp_enqueue_style(
                        'arsol-css-addon-global-' . $css_id,
                        $global_css_options[$css_id]['file'],
                        array(),
                        ARSOL_CSS_ADDONS_VERSION
                    );
                    
                    do_action('arsol_css_addons_loaded_global_css', $css_id, $global_css_options[$css_id]['file']);
                }
            }
        }
        
        // If no admin CSS options are set, return
        if (!isset($options['admin_css_options']) || empty($options['admin_css_options'])) {
            return;
        }
        
        // Get admin CSS definitions
        $admin_css_options = $this->get_admin_css_options();
        
        // Get enabled admin CSS files
        $enabled_options = $options['admin_css_options'];
        
        // Loop through enabled files and enqueue them
        foreach ($enabled_options as $css_id => $enabled) {
            if ($enabled && isset($admin_css_options[$css_id])) {
                wp_enqueue_style(
                    'arsol-css-addon-' . $css_id,
                    $admin_css_options[$css_id]['file'],
                    array(),
                    ARSOL_CSS_ADDONS_VERSION
                );
                
                do_action('arsol_css_addons_loaded_admin_css', $css_id, $admin_css_options[$css_id]['file']);
            }
        }
    }
    
    /**
     * Load frontend CSS files based on settings
     */
    public function load_frontend_css_files() {
        // Don't run in admin
        if (is_admin()) {
            return;
        }
        
        $options = get_option('arsol_css_addons_options', array());
        
        // Load global CSS files
        if (isset($options['global_css_options']) && !empty($options['global_css_options'])) {
            $global_css_options = $this->get_global_css_options();
            $enabled_global_options = $options['global_css_options'];
            
            // Loop through enabled global files and enqueue them
            foreach ($enabled_global_options as $css_id => $enabled) {
                if ($enabled && isset($global_css_options[$css_id])) {
                    wp_enqueue_style(
                        'arsol-css-addon-global-' . $css_id,
                        $global_css_options[$css_id]['file'],
                        array(),
                        ARSOL_CSS_ADDONS_VERSION
                    );
                    
                    do_action('arsol_css_addons_loaded_global_css', $css_id, $global_css_options[$css_id]['file']);
                }
            }
        }
        
        // If no frontend CSS options are set, return
        if (!isset($options['frontend_css_options']) || empty($options['frontend_css_options'])) {
            return;
        }
        
        // Get frontend CSS definitions
        $frontend_css_options = $this->get_frontend_css_options();
        
        // Get enabled frontend CSS files
        $enabled_options = $options['frontend_css_options'];
        
        // Loop through enabled files and enqueue them
        foreach ($enabled_options as $css_id => $enabled) {
            if ($enabled && isset($frontend_css_options[$css_id])) {
                wp_enqueue_style(
                    'arsol-css-addon-' . $css_id,
                    $frontend_css_options[$css_id]['file'],
                    array(),
                    ARSOL_CSS_ADDONS_VERSION
                );
                
                do_action('arsol_css_addons_loaded_frontend_css', $css_id, $frontend_css_options[$css_id]['file']);
            }
        }
    }
}

