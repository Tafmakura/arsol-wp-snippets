<?php
namespace Arsol_WP_Snippets;

/**
 * Admin Settings Controller Class
 *
 * @package Arsol_WP_Snippets
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
    private $css_addons_slug = 'arsol-wp-snippets';

    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add main menu page
        add_menu_page(
            __('Arsol WP Snippets', 'arsol-wp-snippets'), // Page title
            __('Arsol WP Snippets', 'arsol-wp-snippets'), // Menu title
            'manage_options',
            $this->css_addons_slug,
            array($this, 'display_css_addons_page'),
            'dashicons-editor-code',
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
        
        // Display welcome message
        ?>
        <div class="notice notice-info">
            <p>
                Welcome to Arsol WP Snippets! This plugin helps you manage and load custom PHP, CSS, and JavaScript snippets in your WordPress site. 
                For detailed documentation and examples, visit our <a href="https://github.com/Tafmakura/arsol-wp-snippets" target="_blank">GitHub repository</a> 
                or check out our <a href="https://github.com/Tafmakura/arsol-wps-packet-example" target="_blank">packet template</a>.
            </p>
        </div>
        <?php
        
        // Display safe mode notice if enabled
        if (defined('ARSOL_WP_SNIPPETS_SAFE_MODE') && ARSOL_WP_SNIPPETS_SAFE_MODE) {
            $this->display_safe_mode_notice();
        }
        
        // Include the template file
        include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/templates/admin/settings-page.php';
    }
    
    /**
     * Display safe mode notice
     */
    private function display_safe_mode_notice() {
        ?>
        <div class="notice notice-warning">
            <h3 style="margin: 0.5em 0;">⚠️ Safe Mode Active</h3>
            <p>
                <strong>Safe Mode is currently enabled.</strong> This means that while you can still manage which files are selected, 
                none of the selected files will be loaded. This is useful when troubleshooting fatal errors.
            </p>
            <p>
                To disable Safe Mode, you'll need to set <code>ARSOL_WP_SNIPPETS_SAFE_MODE</code> to <code>false</code> in your wp-config.php file.
            </p>
        </div>
        <?php
    }
    
    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            'arsol_wp_snippets_settings',
            'arsol_wp_snippets_options',
            array($this, 'sanitize_settings')
        );
        
        // Add PHP Addon Section
        add_settings_section(
            'arsol_wp_snippets_php',
            __('PHP Addons', 'arsol-wp-snippets'),
            function() {
                echo '<h4>' . esc_html__('Select PHP snippets to include.', 'arsol-wp-snippets') . '</h4>';
                echo '<div class="arsol-addon-list">';
                // Render PHP addons directly here instead of using add_settings_field
                $this->render_php_addon_options();
                echo '</div>';
            },
            $this->css_addons_slug
        );
        
        // Add CSS Addon Section
        add_settings_section(
            'arsol_wp_snippets_css',
            __('CSS Addons', 'arsol-wp-snippets'),
            function() {
                echo '<h4>' . esc_html__('Select CSS snippets to include.', 'arsol-wp-snippets') . '</h4>';
                echo '<div class="arsol-addon-list">';
                // Render CSS addons directly here instead of using add_settings_field
                $this->render_css_addon_options();
                echo '</div>';
            },
            $this->css_addons_slug
        );
        
        // Add JS Addon Section
        add_settings_section(
            'arsol_wp_snippets_js',
            __('JS Addons', 'arsol-wp-snippets'),
            function() {
                echo '<h4>' . esc_html__('Select JavaScript snippets to include.', 'arsol-wp-snippets') . '</h4>';
                echo '<div class="arsol-addon-list">';
                // Render JS addons directly here instead of using add_settings_field
                $this->render_js_addon_options();
                echo '</div>';
            },
            $this->css_addons_slug
        );
        
        // Don't add any add_settings_field() calls - that's what creates the table structure
    }
    
    /**
     * Sort addons by loading order
     *
     * @param array $addons Array of addon data
     * @return array Sorted addons
     */
    private function sort_addons_by_loading_order($addons) {
        uasort($addons, function($a, $b) {
            $loading_order_a = \Arsol_WP_Snippets\Helper::get_loading_order($a);
            $loading_order_b = \Arsol_WP_Snippets\Helper::get_loading_order($b);
            return $loading_order_a - $loading_order_b;
        });
        return $addons;
    }
    
    /**
     * Get available PHP addon options
     */
    public function get_php_addon_options() {
        $php_addon_options = array();
        $duplicates = array();
        $seen_paths = array();
        $path_to_first_file = array(); // Track first file for each path
        
        // Get PHP files from theme directory
        $theme_dir = get_stylesheet_directory();
        $theme_files = glob($theme_dir . '/includes/functions/*.php');
        if ($theme_files) {
            foreach ($theme_files as $file) {
                $file_name = basename($file);
                $addon_id = 'theme-' . sanitize_title($file_name);
                $loading_order = \Arsol_WP_Snippets\Helper::get_default_options('loading_order');
                
                if (in_array($file, $seen_paths)) {
                    // Check if this file should be considered the first one based on loading order
                    if (!isset($path_to_first_file[$file]) || $loading_order < \Arsol_WP_Snippets\Helper::get_loading_order($path_to_first_file[$file])) {
                        // This file should be the first one
                        if (isset($path_to_first_file[$file])) {
                            // Move the previous first file to duplicates
                            $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($path_to_first_file[$file]);
                        }
                        $path_to_first_file[$file] = array(
                            'file' => $file,
                            'name' => ucwords(str_replace('-', ' ', sanitize_title($file_name))),
                            'loading_order' => $loading_order
                        );
                    } else {
                        // This file should be a duplicate
                        $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data(array(
                            'file' => $file,
                            'name' => ucwords(str_replace('-', ' ', sanitize_title($file_name))),
                            'loading_order' => $loading_order
                        ));
                    }
                    continue;
                }
                
                $seen_paths[] = $file;
                $php_addon_options[$addon_id] = array(
                    'name' => ucwords(str_replace('-', ' ', sanitize_title($file_name))),
                    'file' => $file,
                    'context' => \Arsol_WP_Snippets\Helper::get_default_options('context'),
                    'loading_order' => $loading_order
                );
                $path_to_first_file[$file] = array(
                    'file' => $file,
                    'name' => ucwords(str_replace('-', ' ', sanitize_title($file_name))),
                    'loading_order' => $loading_order
                );
            }
        }
        
        // Allow filtering of PHP addon options
        $php_addon_options = apply_filters('arsol_wp_snippets_php_addon_files', $php_addon_options);
        
        // Remove duplicates from filtered options
        $final = array();
        $seen_paths = array();
        $path_to_first_file = array(); // Reset for filtered options
        
        foreach ($php_addon_options as $id => $data) {
            if (!isset($data['file'])) continue;
            
            if (in_array($data['file'], $seen_paths)) {
                $loading_order = \Arsol_WP_Snippets\Helper::get_loading_order($data);
                
                // Check if this file should be considered the first one based on loading order
                if (!isset($path_to_first_file[$data['file']]) || $loading_order < \Arsol_WP_Snippets\Helper::get_loading_order($path_to_first_file[$data['file']])) {
                    // This file should be the first one
                    if (isset($path_to_first_file[$data['file']])) {
                        // Move the previous first file to duplicates
                        $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($path_to_first_file[$data['file']]);
                    }
                    $path_to_first_file[$data['file']] = $data;
                    $final[$id] = $data;
                } else {
                    // This file should be a duplicate
                    $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($data);
                }
                continue;
            }
            
            $seen_paths[] = $data['file'];
            $final[$id] = $data;
            $path_to_first_file[$data['file']] = $data;
        }
        
        $this->php_duplicates = $duplicates;
        return $final;
    }
    
    /**
     * Get available CSS addon options
     */
    public function get_css_addon_options() {
        $css_addon_options = array();
        $duplicates = array();
        $seen_paths = array();
        $path_to_first_file = array(); // Track first file for each path
        
        $filtered_options = apply_filters('arsol_wp_snippets_css_addon_files', $css_addon_options);
        foreach ($filtered_options as $addon_id => $addon_data) {
            if (!isset($addon_data['file']) || substr($addon_data['file'], -4) !== '.css') {
                unset($filtered_options[$addon_id]);
                continue;
            }
            
            if (in_array($addon_data['file'], $seen_paths)) {
                $loading_order = \Arsol_WP_Snippets\Helper::get_loading_order($addon_data);
                
                // Check if this file should be considered the first one based on loading order
                if (!isset($path_to_first_file[$addon_data['file']]) || $loading_order < \Arsol_WP_Snippets\Helper::get_loading_order($path_to_first_file[$addon_data['file']])) {
                    // This file should be the first one
                    if (isset($path_to_first_file[$addon_data['file']])) {
                        // Move the previous first file to duplicates
                        $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($path_to_first_file[$addon_data['file']]);
                    }
                    $path_to_first_file[$addon_data['file']] = $addon_data;
                    $filtered_options[$addon_id] = $addon_data;
                } else {
                    // This file should be a duplicate
                    $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($addon_data);
                    unset($filtered_options[$addon_id]);
                }
                continue;
            }
            
            $seen_paths[] = $addon_data['file'];
            $path_to_first_file[$addon_data['file']] = $addon_data;
        }
        
        $this->css_duplicates = $duplicates;
        return $filtered_options;
    }
    
    /**
     * Get available JS addon options
     */
    public function get_js_addon_options() {
        $js_addon_options = array();
        $duplicates = array();
        $seen_paths = array();
        $path_to_first_file = array(); // Track first file for each path
        
        $filtered_options = apply_filters('arsol_wp_snippets_js_addon_files', $js_addon_options);
        foreach ($filtered_options as $addon_id => $addon_data) {
            if (!isset($addon_data['file']) || substr($addon_data['file'], -3) !== '.js') {
                unset($filtered_options[$addon_id]);
                continue;
            }
            
            if (in_array($addon_data['file'], $seen_paths)) {
                $loading_order = \Arsol_WP_Snippets\Helper::get_loading_order($addon_data);
                
                // Check if this file should be considered the first one based on loading order
                if (!isset($path_to_first_file[$addon_data['file']]) || $loading_order < \Arsol_WP_Snippets\Helper::get_loading_order($path_to_first_file[$addon_data['file']])) {
                    // This file should be the first one
                    if (isset($path_to_first_file[$addon_data['file']])) {
                        // Move the previous first file to duplicates
                        $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($path_to_first_file[$addon_data['file']]);
                    }
                    $path_to_first_file[$addon_data['file']] = $addon_data;
                    $filtered_options[$addon_id] = $addon_data;
                } else {
                    // This file should be a duplicate
                    $duplicates[] = \Arsol_WP_Snippets\Helper::process_duplicate_data($addon_data);
                    unset($filtered_options[$addon_id]);
                }
                continue;
            }
            
            $seen_paths[] = $addon_data['file'];
            $path_to_first_file[$addon_data['file']] = $addon_data;
        }
        
        $this->js_duplicates = $duplicates;
        return $filtered_options;
    }
    
    /**
     * Render PHP addon options checkboxes
     */
    public function render_php_addon_options() {
        $options = get_option('arsol_wp_snippets_options', array());
        $php_addon_options = isset($options['php_addon_options']) ? $options['php_addon_options'] : array();
        $available_php_addons = $this->get_php_addon_options();
        $duplicates = isset($this->php_duplicates) ? $this->php_duplicates : array();
        if (empty($available_php_addons) && empty($duplicates)) {
            echo '<p>' . esc_html__('No PHP snippets available.', 'arsol-wp-snippets') . '</p>';
            return;
        }
        $available_php_addons = $this->sort_addons_by_loading_order($available_php_addons);
        foreach ($available_php_addons as $addon_id => $addon_data) {
            $enabled_options = $php_addon_options;
            $option_type = 'php';
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/addon-file-checkbox.php';
        }
        foreach ($duplicates as $dup_data) {
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/duplicate-file-error.php';
        }
    }
    
    /**
     * Render CSS addon options checkboxes
     */
    public function render_css_addon_options() {
        $options = get_option('arsol_wp_snippets_options', array());
        $css_addon_options = isset($options['css_addon_options']) ? $options['css_addon_options'] : array();
        $available_css_addons = $this->get_css_addon_options();
        $duplicates = isset($this->css_duplicates) ? $this->css_duplicates : array();
        if (empty($available_css_addons) && empty($duplicates)) {
            echo '<p>' . esc_html__('No CSS snippets available.', 'arsol-wp-snippets') . '</p>';
            return;
        }
        $available_css_addons = $this->sort_addons_by_loading_order($available_css_addons);
        foreach ($available_css_addons as $addon_id => $addon_data) {
            $enabled_options = $css_addon_options;
            $option_type = 'css';
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/addon-file-checkbox.php';
        }
        foreach ($duplicates as $dup_data) {
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/duplicate-file-error.php';
        }
    }
    
    /**
     * Render JS addon options checkboxes
     */
    public function render_js_addon_options() {
        $options = get_option('arsol_wp_snippets_options', array());
        $js_addon_options = isset($options['js_addon_options']) ? $options['js_addon_options'] : array();
        $available_js_addons = $this->get_js_addon_options();
        $duplicates = isset($this->js_duplicates) ? $this->js_duplicates : array();
        if (empty($available_js_addons) && empty($duplicates)) {
            echo '<p>' . esc_html__('No JS snippets available.', 'arsol-wp-snippets') . '</p>';
            return;
        }
        $available_js_addons = $this->sort_addons_by_loading_order($available_js_addons);
        foreach ($available_js_addons as $addon_id => $addon_data) {
            $enabled_options = $js_addon_options;
            $option_type = 'js';
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/addon-file-checkbox.php';
        }
        foreach ($duplicates as $dup_data) {
            include ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/duplicate-file-error.php';
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
     * Load PHP snippets
     */
    public function load_php_addon_files() {
        // This method is now handled by Snippet_Loader
    }
    
    /**
     * Load admin snippets based on settings
     */
    public function load_admin_addon_files() {
        // This method is now handled by Snippet_Loader
    }
    
    /**
     * Load frontend snippets based on settings
     */
    public function load_frontend_addon_files() {
        // This method is now handled by Snippet_Loader
    }
}

