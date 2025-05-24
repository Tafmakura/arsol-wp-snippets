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
        
        // Enqueue addon files based on settings
        add_action('admin_enqueue_scripts', array($this, 'load_admin_addon_files'));
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_addon_files'));
        add_action('init', array($this, 'load_php_addon_files'));
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
        
        // Add PHP Addon Section
        add_settings_section(
            'arsol_css_addons_php',
            __('PHP Addons', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select PHP addon files to include.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add PHP addon options
        add_settings_field(
            'php_addon_options',
            __('PHP addon files', 'arsol-css-addons'),
            array($this, 'render_php_addon_options'),
            $this->css_addons_slug,
            'arsol_css_addons_php'
        );
        
        // Add CSS Addon Section
        add_settings_section(
            'arsol_css_addons_css',
            __('CSS Addons', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS addon files to include.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add CSS addon options
        add_settings_field(
            'css_addon_options',
            __('CSS addon files', 'arsol-css-addons'),
            array($this, 'render_css_addon_options'),
            $this->css_addons_slug,
            'arsol_css_addons_css'
        );
        
        // Add JS Addon Section
        add_settings_section(
            'arsol_css_addons_js',
            __('JS Addons', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select JavaScript addon files to include.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add JS addon options
        add_settings_field(
            'js_addon_options',
            __('JS addon files', 'arsol-css-addons'),
            array($this, 'render_js_addon_options'),
            $this->css_addons_slug,
            'arsol_css_addons_js'
        );
    }
    
    /**
     * Get available PHP addon options
     */
    public function get_php_addon_options() {
        // Default empty array
        $php_addon_options = array();
        
        /**
         * Filter the PHP addon options
         * 
         * @param array $php_addon_options Array of PHP addon options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_php_addon_files', $php_addon_options);
        
        // Validate that all files are PHP files
        foreach ($filtered_options as $addon_id => $addon_data) {
            // Check if file path exists and ends with .php
            if (!isset($addon_data['file']) || substr($addon_data['file'], -4) !== '.php') {
                // Remove invalid entries
                unset($filtered_options[$addon_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available CSS addon options
     */
    public function get_css_addon_options() {
        // Default empty array
        $css_addon_options = array();
        
        /**
         * Filter the CSS addon options
         * 
         * @param array $css_addon_options Array of CSS addon options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_css_addon_files', $css_addon_options);
        
        // Validate that all files are CSS files
        foreach ($filtered_options as $addon_id => $addon_data) {
            // Check if file path exists and ends with .css
            if (!isset($addon_data['file']) || substr($addon_data['file'], -4) !== '.css') {
                // Remove invalid entries
                unset($filtered_options[$addon_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available JS addon options
     */
    public function get_js_addon_options() {
        // Default empty array
        $js_addon_options = array();
        
        /**
         * Filter the JS addon options
         * 
         * @param array $js_addon_options Array of JS addon options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_js_addon_files', $js_addon_options);
        
        // Validate that all files are JS files
        foreach ($filtered_options as $addon_id => $addon_data) {
            // Check if file path exists and ends with .js
            if (!isset($addon_data['file']) || substr($addon_data['file'], -3) !== '.js') {
                // Remove invalid entries
                unset($filtered_options[$addon_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Render PHP addon options checkboxes
     */
    public function render_php_addon_options() {
        $options = get_option('arsol_css_addons_options', array());
        $php_addon_options = isset($options['php_addon_options']) ? $options['php_addon_options'] : array();
        
        // Get PHP addon options from the filter
        $available_php_addons = $this->get_php_addon_options();
        
        if (empty($available_php_addons)) {
            echo '<p>' . esc_html__('No PHP addon files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_php_addons as $addon_id => $addon_data) {
            // Set variables that will be available to the template
            $enabled_options = $php_addon_options;
            $option_type = 'php';
            $addon_id = $addon_id;
            $addon_data = $addon_data;
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/addon-file-checkbox.php';
        }
    }
    
    /**
     * Render CSS addon options checkboxes
     */
    public function render_css_addon_options() {
        $options = get_option('arsol_css_addons_options', array());
        $css_addon_options = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        
        // Get CSS addon options from the filter
        $available_css_addons = $this->get_css_addon_options();
        
        if (empty($available_css_addons)) {
            echo '<p>' . esc_html__('No CSS addon files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_css_addons as $addon_id => $addon_data) {
            // Set variables that will be available to the template
            $enabled_options = $css_addon_options;
            $option_type = 'css';
            $addon_id = $addon_id;
            $addon_data = $addon_data;
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/addon-file-checkbox.php';
        }
    }
    
    /**
     * Render JS addon options checkboxes
     */
    public function render_js_addon_options() {
        $options = get_option('arsol_css_addons_options', array());
        $js_addon_options = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
        
        // Get JS addon options from the filter
        $available_js_addons = $this->get_js_addon_options();
        
        if (empty($available_js_addons)) {
            echo '<p>' . esc_html__('No JS addon files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_js_addons as $addon_id => $addon_data) {
            // Set variables that will be available to the template
            $enabled_options = $js_addon_options;
            $option_type = 'js';
            $addon_id = $addon_id;
            $addon_data = $addon_data;
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/addon-file-checkbox.php';
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
        
        // Sanitize PHP addon file options
        $sanitized_input['php_addon_options'] = array();
        if (isset($input['php_addon_options']) && is_array($input['php_addon_options'])) {
            foreach ($input['php_addon_options'] as $addon_id => $value) {
                $sanitized_input['php_addon_options'][$addon_id] = 1;
            }
        }
        
        // Sanitize CSS addon file options
        $sanitized_input['css_addon_options'] = array();
        if (isset($input['css_addon_options']) && is_array($input['css_addon_options'])) {
            foreach ($input['css_addon_options'] as $addon_id => $value) {
                $sanitized_input['css_addon_options'][$addon_id] = 1;
            }
        }
        
        // Sanitize JS addon file options
        $sanitized_input['js_addon_options'] = array();
        if (isset($input['js_addon_options']) && is_array($input['js_addon_options'])) {
            foreach ($input['js_addon_options'] as $addon_id => $value) {
                $sanitized_input['js_addon_options'][$addon_id] = 1;
            }
        }
        
        return $sanitized_input;
    }
    
    /**
     * Load PHP addon files based on settings
     */
    public function load_php_addon_files() {
        $options = get_option('arsol_css_addons_options', array());
        
        // If no PHP addon options are set, return
        if (!isset($options['php_addon_options']) || empty($options['php_addon_options'])) {
            return;
        }
        
        // Get PHP addon definitions
        $php_addon_options = $this->get_php_addon_options();
        
        // Get enabled PHP addon files
        $enabled_options = $options['php_addon_options'];
        
        // Loop through enabled files and include them
        foreach ($enabled_options as $addon_id => $enabled) {
            if ($enabled && isset($php_addon_options[$addon_id])) {
                $file_path = $php_addon_options[$addon_id]['file'];
                if (file_exists($file_path)) {
                    include_once $file_path;
                    do_action('arsol_css_addons_loaded_php_addon', $addon_id, $file_path);
                }
            }
        }
    }
    
    /**
     * Load admin addon files based on settings
     */
    public function load_admin_addon_files() {
        // Only run in admin
        if (!is_admin()) {
            return;
        }
        
        $options = get_option('arsol_css_addons_options', array());
        
        // Load CSS addon files
        if (isset($options['css_addon_options']) && !empty($options['css_addon_options'])) {
            $css_addon_options = $this->get_css_addon_options();
            $enabled_css_options = $options['css_addon_options'];
            
            // Loop through enabled CSS files and enqueue them
            foreach ($enabled_css_options as $addon_id => $enabled) {
                if ($enabled && isset($css_addon_options[$addon_id])) {
                    $addon_data = $css_addon_options[$addon_id];
                    
                    // Check context - load if global or admin
                    $context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                    if ($context === 'global' || $context === 'admin') {
                        wp_enqueue_style(
                            'arsol-css-addon-' . $addon_id,
                            $addon_data['file'],
                            array(),
                            ARSOL_CSS_ADDONS_VERSION
                        );
                        // Note: CSS position doesn't matter much - always loads in header
                        
                        do_action('arsol_css_addons_loaded_css_addon', $addon_id, $addon_data['file']);
                    }
                }
            }
        }
        
        // Load JS addon files
        if (isset($options['js_addon_options']) && !empty($options['js_addon_options'])) {
            $js_addon_options = $this->get_js_addon_options();
            $enabled_js_options = $options['js_addon_options'];
            
            // Loop through enabled JS files and enqueue them
            foreach ($enabled_js_options as $addon_id => $enabled) {
                if ($enabled && isset($js_addon_options[$addon_id])) {
                    $addon_data = $js_addon_options[$addon_id];
                    
                    // Check context - load if global or admin
                    $context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                    if ($context === 'global' || $context === 'admin') {
                        // Get position - DEFAULT TO FOOTER for JS
                        $position = isset($addon_data['position']) ? $addon_data['position'] : 'footer';
                        $in_footer = ($position === 'footer');
                        
                        wp_enqueue_script(
                            'arsol-js-addon-' . $addon_id,
                            $addon_data['file'],
                            array('jquery'),
                            ARSOL_CSS_ADDONS_VERSION,
                            $in_footer  // true for footer (default), false for header
                        );
                        
                        do_action('arsol_css_addons_loaded_js_addon', $addon_id, $js_addon_options[$addon_id]['file']);
                    }
                }
            }
        }
    }
    
    /**
     * Load frontend addon files based on settings
     */
    public function load_frontend_addon_files() {
        // Don't run in admin
        if (is_admin()) {
            return;
        }
        
        $options = get_option('arsol_css_addons_options', array());
        
        // Load CSS addon files
        if (isset($options['css_addon_options']) && !empty($options['css_addon_options'])) {
            $css_addon_options = $this->get_css_addon_options();
            $enabled_css_options = $options['css_addon_options'];
            
            // Loop through enabled CSS files and enqueue them
            foreach ($enabled_css_options as $addon_id => $enabled) {
                if ($enabled && isset($css_addon_options[$addon_id])) {
                    $addon_data = $css_addon_options[$addon_id];
                    
                    // Check context - load if global or frontend
                    $context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                    if ($context === 'global' || $context === 'frontend') {
                        wp_enqueue_style(
                            'arsol-css-addon-' . $addon_id,
                            $addon_data['file'],
                            array(),
                            ARSOL_CSS_ADDONS_VERSION
                        );
                        // Note: CSS always loads in header regardless of position setting
                        
                        do_action('arsol_css_addons_loaded_css_addon', $addon_id, $css_addon_options[$addon_id]['file']);
                    }
                }
            }
        }
        
        // Load JS addon files
        if (isset($options['js_addon_options']) && !empty($options['js_addon_options'])) {
            $js_addon_options = $this->get_js_addon_options();
            $enabled_js_options = $options['js_addon_options'];
            
            // Loop through enabled JS files and enqueue them
            foreach ($enabled_js_options as $addon_id => $enabled) {
                if ($enabled && isset($js_addon_options[$addon_id])) {
                    $addon_data = $js_addon_options[$addon_id];
                    
                    // Check context - load if global or frontend
                    $context = isset($addon_data['context']) ? $addon_data['context'] : 'global';
                    if ($context === 'global' || $context === 'frontend') {
                        // Get position - DEFAULT TO FOOTER for JS
                        $position = isset($addon_data['position']) ? $addon_data['position'] : 'footer';
                        $in_footer = ($position === 'footer');
                        
                        wp_enqueue_script(
                            'arsol-js-addon-' . $addon_id,
                            $addon_data['file'],
                            array('jquery'),
                            ARSOL_CSS_ADDONS_VERSION,
                            $in_footer  // true for footer (default), false for header
                        );
                        
                        do_action('arsol_css_addons_loaded_js_addon', $addon_id, $js_addon_options[$addon_id]['file']);
                    }
                }
            }
        }
    }
}

