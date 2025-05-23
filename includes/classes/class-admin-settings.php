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
            'arsol_css_addons_options',  // Change this to match your template
            'arsol_css_addons_options',
            array($this, 'sanitize_settings')
        );

        // Also update all your add_settings_section calls
        add_settings_section(
            'arsol_css_addons_global_section',
            __('Global Options', 'arsol-css-addons'),
            null,
            'arsol_css_addons_options'  // Change this too
        );

        add_settings_section(
            'arsol_css_addons_admin_section',
            __('Admin Options', 'arsol-css-addons'),
            null,
            'arsol_css_addons_options'  // Change this too
        );

        add_settings_section(
            'arsol_css_addons_frontend_section',
            __('Frontend Options', 'arsol-css-addons'),
            null,
            'arsol_css_addons_options'  // Change this too
        );

        // Update all your add_settings_field calls too
        add_settings_field(
            'global_css_options',
            __('Global CSS Files', 'arsol-css-addons'),
            array($this, 'render_global_css_options'),
            'arsol_css_addons_options',  // Change this
            'arsol_css_addons_global_section'
        );
        
        // Admin CSS
        add_settings_field(
            'admin_css_options',
            __('Admin addon CSS files', 'arsol-css-addons'),
            array($this, 'render_admin_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_admin'
        );
        
        // Admin JavaScript
        add_settings_field(
            'admin_js_options',
            __('Admin addon JS files', 'arsol-css-addons'),
            array($this, 'render_admin_js_options'),
            $this->css_addons_slug,
            'arsol_css_addons_admin'
        );
        
        // Admin PHP
        add_settings_field(
            'admin_php_options',
            __('Admin addon PHP files', 'arsol-css-addons'),
            array($this, 'render_admin_php_options'),
            $this->css_addons_slug,
            'arsol_css_addons_admin'
        );
        
        // FRONTEND SECTION
        add_settings_section(
            'arsol_css_addons_frontend',
            __('Frontend', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Extend your website frontend with additional files.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Frontend CSS
        add_settings_field(
            'frontend_css_options',
            __('Frontend addon CSS files', 'arsol-css-addons'),
            array($this, 'render_frontend_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_frontend'
        );
        
        // Frontend JavaScript
        add_settings_field(
            'frontend_js_options',
            __('Frontend addon JS files', 'arsol-css-addons'),
            array($this, 'render_frontend_js_options'),
            $this->css_addons_slug,
            'arsol_css_addons_frontend'
        );
        
        // Frontend PHP
        add_settings_field(
            'frontend_php_options',
            __('Frontend addon PHP files', 'arsol-css-addons'),
            array($this, 'render_frontend_php_options'),
            $this->css_addons_slug,
            'arsol_css_addons_frontend'
        );
        
        // GLOBAL SECTION
        add_settings_section(
            'arsol_css_addons_global',
            __('Global', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Files that apply both to admin and frontend.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Global CSS
        add_settings_field(
            'global_css_options',
            __('Global addon CSS files', 'arsol-css-addons'),
            array($this, 'render_global_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_global'
        );
        
        // Global JavaScript
        add_settings_field(
            'global_js_options',
            __('Global addon JS files', 'arsol-css-addons'),
            array($this, 'render_global_js_options'),
            $this->css_addons_slug,
            'arsol_css_addons_global'
        );
        
        // Global PHP
        add_settings_field(
            'global_php_options',
            __('Global addon PHP files', 'arsol-css-addons'),
            array($this, 'render_global_php_options'),
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
        $filtered_options = apply_filters('arsol_css_addons_global_css_options', $global_css_options);
        
        // Validate that all files are CSS files
        foreach ($filtered_options as $css_id => $css_data) {
            // Check if file path exists and ends with .css
            if (!isset($css_data['file']) || substr($css_data['file'], -4) !== '.css') {
                // Remove invalid entries
                unset($filtered_options[$css_id]);
            }
        }
        
        return $filtered_options;
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
        $filtered_options = apply_filters('arsol_css_addons_admin_css_options', $admin_css_options);
        
        // Validate that all files are CSS files
        foreach ($filtered_options as $css_id => $css_data) {
            // Check if file path exists and ends with .css
            if (!isset($css_data['file']) || substr($css_data['file'], -4) !== '.css') {
                // Remove invalid entries
                unset($filtered_options[$css_id]);
            }
        }
        
        return $filtered_options;
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
        $filtered_options = apply_filters('arsol_css_addons_frontend_css_options', $frontend_css_options);
        
        // Validate that all files are CSS files
        foreach ($filtered_options as $css_id => $css_data) {
            // Check if file path exists and ends with .css
            if (!isset($css_data['file']) || substr($css_data['file'], -4) !== '.css') {
                // Remove invalid entries
                unset($filtered_options[$css_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available admin JS options
     *
     * @return array Array of admin JS options
     */
    public function get_admin_js_options() {
        // Default empty array
        $admin_js_options = array();
        
        /**
         * Filter the admin JS options
         * 
         * @param array $admin_js_options Array of admin JS options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_admin_js_options', $admin_js_options);
        
        // Validate that all files are JS files
        foreach ($filtered_options as $js_id => $js_data) {
            // Check if file path exists and ends with .js
            if (!isset($js_data['file']) || substr($js_data['file'], -3) !== '.js') {
                // Remove invalid entries
                unset($filtered_options[$js_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available global JS options
     *
     * @return array Array of global JS options
     */
    public function get_global_js_options() {
        // Default empty array
        $global_js_options = array();
        
        /**
         * Filter the global JS options
         * 
         * @param array $global_js_options Array of global JS options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_global_js_options', $global_js_options);
        
        // Validate that all files are JS files
        foreach ($filtered_options as $js_id => $js_data) {
            // Check if file path exists and ends with .js
            if (!isset($js_data['file']) || substr($js_data['file'], -3) !== '.js') {
                // Remove invalid entries
                unset($filtered_options[$js_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available admin PHP options
     *
     * @return array Array of admin PHP options
     */
    public function get_admin_php_options() {
        // Default empty array
        $admin_php_options = array();
        
        /**
         * Filter the admin PHP options
         * 
         * @param array $admin_php_options Array of admin PHP options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_admin_php_options', $admin_php_options);
        
        // Validate that all files are PHP files
        foreach ($filtered_options as $php_id => $php_data) {
            // Check if file path exists and ends with .php
            if (!isset($php_data['file']) || substr($php_data['file'], -4) !== '.php') {
                // Remove invalid entries
                unset($filtered_options[$php_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available frontend JS options
     *
     * @return array Array of frontend JS options
     */
    public function get_frontend_js_options() {
        // Default empty array
        $frontend_js_options = array();
        
        /**
         * Filter the frontend JS options
         * 
         * @param array $frontend_js_options Array of frontend JS options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_frontend_js_options', $frontend_js_options);
        
        // Validate that all files are JS files
        foreach ($filtered_options as $js_id => $js_data) {
            // Check if file path exists and ends with .js
            if (!isset($js_data['file']) || substr($js_data['file'], -3) !== '.js') {
                // Remove invalid entries
                unset($filtered_options[$js_id]);
            }
        }
        
        return $filtered_options;
    }
    
    /**
     * Get available frontend PHP options
     *
     * @return array Array of frontend PHP options
     */
    public function get_frontend_php_options() {
        // Default empty array
        $frontend_php_options = array();
        
        /**
         * Filter the frontend PHP options
         * 
         * @param array $frontend_php_options Array of frontend PHP options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_frontend_php_options', $frontend_php_options);
        
        // Validate that all files are PHP files
        foreach ($filtered_options as $php_id => $php_data) {
            // Check if file path exists and ends with .php
            if (!isset($php_data['file']) || substr($php_data['file'], -4) !== '.php') {
                // Remove invalid entries
                unset($filtered_options[$php_id]);
            }
        }
        
        return $filtered_options;
    }

     /**
     * Get available global PHP options
     *
     * @return array Array of global PHP options
     */
    public function get_global_php_options() {
        // Default empty array
        $global_php_options = array();
        
        /**
         * Filter the global PHP options
         * 
         * @param array $global_php_options Array of global PHP options with file paths
         */
        $filtered_options = apply_filters('arsol_css_addons_global_php_options', $global_php_options);
        
        // Validate that all files are PHP files
        foreach ($filtered_options as $php_id => $php_data) {
            // Check if file path exists and ends with .php
            if (!isset($php_data['file']) || substr($php_data['file'], -4) !== '.php') {
                // Remove invalid entries
                unset($filtered_options[$php_id]);
            }
        }
        
        return $filtered_options;
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
     * Render global JS options checkboxes
     */
    public function render_global_js_options() {
        $options = get_option('arsol_css_addons_options', array());
        $global_js_options = isset($options['global_js_options']) ? $options['global_js_options'] : array();
        
        // Get JS options from the filter
        $available_global_js = $this->get_global_js_options();
        
        if (empty($available_global_js)) {
            echo '<p>' . esc_html__('No global JavaScript files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_global_js as $js_id => $js_data) {
            // Set variables that will be available to the template
            $enabled_options = $global_js_options;
            $option_type = 'global_js';
            $css_id = $js_id; // Keeping variable name for compatibility with template
            $css_data = $js_data; // Keeping variable name for compatibility with template
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/css-file-checkbox.php';
        }
    }
    
    /**
     * Render global PHP options checkboxes
     */
    public function render_global_php_options() {
        $options = get_option('arsol_css_addons_options', array());
        $global_php_options = isset($options['global_php_options']) ? $options['global_php_options'] : array();
        
        // Get PHP options from the filter
        $available_global_php = $this->get_global_php_options();
        
        if (empty($available_global_php)) {
            echo '<p>' . esc_html__('No global PHP files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_global_php as $php_id => $php_data) {
            // Set variables that will be available to the template
            $enabled_options = $global_php_options;
            $option_type = 'global_php';
            $css_id = $php_id; // Keeping variable name for compatibility with template
            $css_data = $php_data; // Keeping variable name for compatibility with template
            
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
     * Render admin JS options checkboxes
     */
    public function render_admin_js_options() {
        $options = get_option('arsol_css_addons_options', array());
        $admin_js_options = isset($options['admin_js_options']) ? $options['admin_js_options'] : array();
        
        // Get JS options from the filter
        $available_admin_js = $this->get_admin_js_options();
        
        if (empty($available_admin_js)) {
            echo '<p>' . esc_html__('No admin JavaScript files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_admin_js as $js_id => $js_data) {
            // Set variables that will be available to the template
            $enabled_options = $admin_js_options;
            $option_type = 'admin_js';
            $css_id = $js_id; // Keeping variable name for compatibility with template
            $css_data = $js_data; // Keeping variable name for compatibility with template
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/css-file-checkbox.php';
        }
    }
    
    /**
     * Render admin PHP options checkboxes
     */
    public function render_admin_php_options() {
        $options = get_option('arsol_css_addons_options', array());
        $admin_php_options = isset($options['admin_php_options']) ? $options['admin_php_options'] : array();
        
        // Get PHP options from the filter
        $available_admin_php = $this->get_admin_php_options();
        
        if (empty($available_admin_php)) {
            echo '<p>' . esc_html__('No admin PHP files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_admin_php as $php_id => $php_data) {
            // Set variables that will be available to the template
            $enabled_options = $admin_php_options;
            $option_type = 'admin_php';
            $css_id = $php_id; // Keeping variable name for compatibility with template
            $css_data = $php_data; // Keeping variable name for compatibility with template
            
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
     * Render frontend JS options checkboxes
     */
    public function render_frontend_js_options() {
        $options = get_option('arsol_css_addons_options', array());
        $frontend_js_options = isset($options['frontend_js_options']) ? $options['frontend_js_options'] : array();
        
        // Get JS options from the filter
        $available_frontend_js = $this->get_frontend_js_options();
        
        if (empty($available_frontend_js)) {
            echo '<p>' . esc_html__('No frontend JavaScript files available.', 'arsol_css_addons') . '</p>';
            return;
        }
        
        foreach ($available_frontend_js as $js_id => $js_data) {
            // Set variables that will be available to the template
            $enabled_options = $frontend_js_options;
            $option_type = 'frontend_js';
            $css_id = $js_id; // Keeping variable name for compatibility with template
            $css_data = $js_data; // Keeping variable name for compatibility with template
            
            // Include the template file with the correct path
            include ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/ui/partials/admin/css-file-checkbox.php';
        }
    }
    
    /**
     * Render frontend PHP options checkboxes
     */
    public function render_frontend_php_options() {
        $options = get_option('arsol_css_addons_options', array());
        $frontend_php_options = isset($options['frontend_php_options']) ? $options['frontend_php_options'] : array();
        
        // Get PHP options from the filter
        $available_frontend_php = $this->get_frontend_php_options();
        
        if (empty($available_frontend_php)) {
            echo '<p>' . esc_html__('No frontend PHP files available.', 'arsol-css-addons') . '</p>';
            return;
        }
        
        foreach ($available_frontend_php as $php_id => $php_data) {
            // Set variables that will be available to the template
            $enabled_options = $frontend_php_options;
            $option_type = 'frontend_php';
            $css_id = $php_id; // Keeping variable name for compatibility with template
            $css_data = $php_data; // Keeping variable name for compatibility with template
            
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
        
        // Sanitize global CSS options
        $sanitized_input['global_css_options'] = array();
        if (isset($input['global_css_options']) && is_array($input['global_css_options'])) {
            foreach ($input['global_css_options'] as $css_id => $value) {
                $sanitized_input['global_css_options'][$css_id] = 1;
            }
        }
        
        // Sanitize global JS options
        $sanitized_input['global_js_options'] = array();
        if (isset($input['global_js_options']) && is_array($input['global_js_options'])) {
            foreach ($input['global_js_options'] as $js_id => $value) {
                $sanitized_input['global_js_options'][$js_id] = 1;
            }
        }
        
        // Sanitize global PHP options
        $sanitized_input['global_php_options'] = array();
        if (isset($input['global_php_options']) && is_array($input['global_php_options'])) {
            foreach ($input['global_php_options'] as $php_id => $value) {
                $sanitized_input['global_php_options'][$php_id] = 1;
            }
        }
        
        // Sanitize admin CSS options
        $sanitized_input['admin_css_options'] = array();
        if (isset($input['admin_css_options']) && is_array($input['admin_css_options'])) {
            foreach ($input['admin_css_options'] as $css_id => $value) {
                $sanitized_input['admin_css_options'][$css_id] = 1;
            }
        }
        
        // Sanitize admin JS options
        $sanitized_input['admin_js_options'] = array();
        if (isset($input['admin_js_options']) && is_array($input['admin_js_options'])) {
            foreach ($input['admin_js_options'] as $js_id => $value) {
                $sanitized_input['admin_js_options'][$js_id] = 1;
            }
        }
        
        // Sanitize admin PHP options
        $sanitized_input['admin_php_options'] = array();
        if (isset($input['admin_php_options']) && is_array($input['admin_php_options'])) {
            foreach ($input['admin_php_options'] as $php_id => $value) {
                $sanitized_input['admin_php_options'][$php_id] = 1;
            }
        }
        
        // Sanitize frontend CSS options
        $sanitized_input['frontend_css_options'] = array();
        if (isset($input['frontend_css_options']) && is_array($input['frontend_css_options'])) {
            foreach ($input['frontend_css_options'] as $css_id => $value) {
                $sanitized_input['frontend_css_options'][$css_id] = 1;
            }
        }
        
        // Sanitize frontend JS options
        $sanitized_input['frontend_js_options'] = array();
        if (isset($input['frontend_js_options']) && is_array($input['frontend_js_options'])) {
            foreach ($input['frontend_js_options'] as $js_id => $value) {
                $sanitized_input['frontend_js_options'][$js_id] = 1;
            }
        }
        
        // Sanitize frontend PHP options
        $sanitized_input['frontend_php_options'] = array();
        if (isset($input['frontend_php_options']) && is_array($input['frontend_php_options'])) {
            foreach ($input['frontend_php_options'] as $php_id => $value) {
                $sanitized_input['frontend_php_options'][$php_id] = 1;
            }
        }
        
        return $sanitized_input;
    }
    
    /**
     * Load admin files based on settings
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
        
        // Load global JS files
        if (isset($options['global_js_options']) && !empty($options['global_js_options'])) {
            $global_js_options = $this->get_global_js_options();
            $enabled_global_options = $options['global_js_options'];
            
            // Loop through enabled global files and enqueue them
            foreach ($enabled_global_options as $js_id => $enabled) {
                if ($enabled && isset($global_js_options[$js_id])) {
                    wp_enqueue_script(
                        'arsol-js-addon-global-' . $js_id,
                        $global_js_options[$js_id]['file'],
                        array('jquery'),
                        ARSOL_CSS_ADDONS_VERSION,
                        true
                    );
                    
                    do_action('arsol_css_addons_loaded_global_js', $js_id, $global_js_options[$js_id]['file']);
                }
            }
        }
        
        // Load global PHP files
        if (isset($options['global_php_options']) && !empty($options['global_php_options'])) {
            $global_php_options = $this->get_global_php_options();
            $enabled_global_options = $options['global_php_options'];
            
            // Loop through enabled global files and include them
            foreach ($enabled_global_options as $php_id => $enabled) {
                if ($enabled && isset($global_php_options[$php_id])) {
                    // Convert URL to file path for PHP files
                    $file_url = $global_php_options[$php_id]['file'];
                    $file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
                    
                    if (file_exists($file_path)) {
                        include_once $file_path;
                    }
                    
                    do_action('arsol_css_addons_loaded_global_php', $php_id, $file_path);
                }
            }
        }
        
        // Load admin CSS files
        if (isset($options['admin_css_options']) && !empty($options['admin_css_options'])) {
            $admin_css_options = $this->get_admin_css_options();
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
        
        // Load admin JS files
        if (isset($options['admin_js_options']) && !empty($options['admin_js_options'])) {
            $admin_js_options = $this->get_admin_js_options();
            $enabled_options = $options['admin_js_options'];
            
            // Loop through enabled files and enqueue them
            foreach ($enabled_options as $js_id => $enabled) {
                if ($enabled && isset($admin_js_options[$js_id])) {
                    wp_enqueue_script(
                        'arsol-js-addon-' . $js_id,
                        $admin_js_options[$js_id]['file'],
                        array('jquery'),
                        ARSOL_CSS_ADDONS_VERSION,
                        true
                    );
                    
                    do_action('arsol_css_addons_loaded_admin_js', $js_id, $admin_js_options[$js_id]['file']);
                }
            }
        }
        
        // Load admin PHP files
        if (isset($options['admin_php_options']) && !empty($options['admin_php_options'])) {
            $admin_php_options = $this->get_admin_php_options();
            $enabled_options = $options['admin_php_options'];
            
            // Loop through enabled files and include them
            foreach ($enabled_options as $php_id => $enabled) {
                if ($enabled && isset($admin_php_options[$php_id])) {
                    // Convert URL to file path for PHP files
                    $file_url = $admin_php_options[$php_id]['file'];
                    $file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
                    
                    if (file_exists($file_path)) {
                        include_once $file_path;
                    }
                    
                    do_action('arsol_css_addons_loaded_admin_php', $php_id, $file_path);
                }
            }
        }
    }
    
    /**
     * Load frontend files based on settings
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
        
        // Load global JS files
        if (isset($options['global_js_options']) && !empty($options['global_js_options'])) {
            $global_js_options = $this->get_global_js_options();
            $enabled_global_options = $options['global_js_options'];
            
            // Loop through enabled global files and enqueue them
            foreach ($enabled_global_options as $js_id => $enabled) {
                if ($enabled && isset($global_js_options[$js_id])) {
                    wp_enqueue_script(
                        'arsol-js-addon-global-' . $js_id,
                        $global_js_options[$js_id]['file'],
                        array('jquery'),
                        ARSOL_CSS_ADDONS_VERSION,
                        true
                    );
                    
                    do_action('arsol_css_addons_loaded_global_js', $js_id, $global_js_options[$js_id]['file']);
                }
            }
        }
        
        // Load global PHP files
        if (isset($options['global_php_options']) && !empty($options['global_php_options'])) {
            $global_php_options = $this->get_global_php_options();
            $enabled_global_options = $options['global_php_options'];
            
            // Loop through enabled global files and include them
            foreach ($enabled_global_options as $php_id => $enabled) {
                if ($enabled && isset($global_php_options[$php_id])) {
                    // Convert URL to file path for PHP files
                    $file_url = $global_php_options[$php_id]['file'];
                    $file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
                    
                    if (file_exists($file_path)) {
                        include_once $file_path;
                    }
                    
                    do_action('arsol_css_addons_loaded_global_php', $php_id, $file_path);
                }
            }
        }
        
        // Load frontend CSS files
        if (isset($options['frontend_css_options']) && !empty($options['frontend_css_options'])) {
            $frontend_css_options = $this->get_frontend_css_options();
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
        
        // Load frontend JS files
        if (isset($options['frontend_js_options']) && !empty($options['frontend_js_options'])) {
            $frontend_js_options = $this->get_frontend_js_options();
            $enabled_options = $options['frontend_js_options'];
            
            // Loop through enabled files and enqueue them
            foreach ($enabled_options as $js_id => $enabled) {
                if ($enabled && isset($frontend_js_options[$js_id])) {
                    wp_enqueue_script(
                        'arsol-js-addon-' . $js_id,
                        $frontend_js_options[$js_id]['file'],
                        array('jquery'),
                        ARSOL_CSS_ADDONS_VERSION,
                        true
                    );
                    
                    do_action('arsol_css_addons_loaded_frontend_js', $js_id, $frontend_js_options[$js_id]['file']);
                }
            }
        }
        
        // Load frontend PHP files
        if (isset($options['frontend_php_options']) && !empty($options['frontend_php_options'])) {
            $frontend_php_options = $this->get_frontend_php_options();
            $enabled_options = $options['frontend_php_options'];
            
            // Loop through enabled files and include them
            foreach ($enabled_options as $php_id => $enabled) {
                if ($enabled && isset($frontend_php_options[$php_id])) {
                    // Convert URL to file path for PHP files
                    $file_url = $frontend_php_options[$php_id]['file'];
                    $file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
                    
                    if (file_exists($file_path)) {
                        include_once $file_path;
                    }
                    
                    do_action('arsol_css_addons_loaded_frontend_php', $php_id, $file_path);
                }
            }
        }
    }
}

