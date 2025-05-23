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
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add main menu page
        add_menu_page(
            __('Arsol CSS Addons', 'arsol-css-addons'), // Page title
            __('CSS Addons', 'arsol-css-addons'),      // Menu title
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
        $options = get_option('arsol_css_addons_options', array());
        
        $args = array(
            'page_title' => get_admin_page_title(),
            'page_type' => 'css-addons',
            'page_slug' => $this->css_addons_slug,
            'options' => $options
        );
        
        $this->load_template('settings-page', $args);
    }
    
    /**
     * Load a template file
     *
     * @param string $template Template name
     * @param array $args Arguments to pass to the template
     */
    private function load_template($template, $args = array()) {
        $template_path = plugin_dir_path(dirname(dirname(__FILE__))) . 'ui/templates/admin/' . $template . '.php';
        
        if (file_exists($template_path)) {
            extract($args);
            include $template_path;
        }
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
        
        // Add settings section
        add_settings_section(
            'arsol_css_addons_general',
            __('General Settings', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Configure the CSS Addons settings below.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add enable CSS Addons field
        add_settings_field(
            'enable_css_addons',
            __('Enable CSS Addons', 'arsol-css-addons'),
            function() {
                $options = get_option('arsol_css_addons_options', array());
                $enabled = isset($options['enable_css_addons']) ? $options['enable_css_addons'] : 1;
                echo '<input type="checkbox" name="arsol_css_addons_options[enable_css_addons]" value="1" ' . checked(1, $enabled, false) . '/>';
                echo '<span class="description">' . esc_html__('Enable CSS Addons functionality', 'arsol-css-addons') . '</span>';
            },
            $this->css_addons_slug,
            'arsol_css_addons_general'
        );
        
        // Add custom CSS field
        add_settings_field(
            'custom_css',
            __('Custom CSS', 'arsol-css-addons'),
            function() {
                $options = get_option('arsol_css_addons_options', array());
                $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';
                echo '<textarea name="arsol_css_addons_options[custom_css]" rows="10" class="large-text code">' . esc_textarea($custom_css) . '</textarea>';
                echo '<p class="description">' . esc_html__('Add custom CSS rules to be applied to your site.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug,
            'arsol_css_addons_general'
        );
    }
    
    /**
     * Sanitize settings
     *
     * @param array $input The input array.
     * @return array
     */
    public function sanitize_settings($input) {
        $sanitized_input = array();
        
        // Sanitize enable option
        $sanitized_input['enable_css_addons'] = isset($input['enable_css_addons']) ? 1 : 0;
        
        // Sanitize custom CSS
        $sanitized_input['custom_css'] = isset($input['custom_css']) ? wp_strip_all_tags($input['custom_css']) : '';
        
        return $sanitized_input;
    }
}

// Initialize the admin settings
new Admin_Settings();