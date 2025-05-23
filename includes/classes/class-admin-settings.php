<?php
namespace Arsol_CSS_Addons;

/**
 * Admin Settings Class
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Settings {

    /**
     * Settings page slug
     *
     * @var string
     */
    private $page_slug = 'arsol-css-addons';

    /**
     * Plugin base path
     *
     * @var string
     */
    private $base_path;

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        
        // Set base path to plugin directory
        $this->base_path = plugin_dir_path( dirname( dirname( __FILE__ ) ) );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add main Arsol menu if it doesn't exist
        if ( ! menu_page_url( 'arsol', false ) ) {
            add_menu_page(
                __( 'Arsol', 'arsol-css-addons' ),
                __( 'Arsol', 'arsol-css-addons' ),
                'manage_options',
                'arsol',
                array( $this, 'display_settings_page' ),
                'dashicons-admin-customizer',
                30
            );
        }

        // Add CSS Addons submenu
        add_submenu_page(
            'arsol',
            __( 'CSS Addons', 'arsol-css-addons' ),
            __( 'CSS Addons', 'arsol-css-addons' ),
            'manage_options',
            $this->page_slug,
            array( $this, 'display_settings_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'arsol_css_addons_settings',
            'arsol_css_addons_options',
            array( $this, 'sanitize_settings' )
        );

        // General settings section
        add_settings_section(
            'arsol_css_addons_general',
            __( 'General Settings', 'arsol-css-addons' ),
            array( $this, 'render_general_section' ),
            $this->page_slug
        );

        // Enable CSS Addons field
        add_settings_field(
            'enable_css_addons',
            __( 'Enable CSS Addons', 'arsol-css-addons' ),
            array( $this, 'render_enable_field' ),
            $this->page_slug,
            'arsol_css_addons_general'
        );

        // Custom CSS field
        add_settings_field(
            'custom_css',
            __( 'Custom CSS', 'arsol-css-addons' ),
            array( $this, 'render_custom_css_field' ),
            $this->page_slug,
            'arsol_css_addons_general'
        );
    }

    /**
     * Load a template file
     *
     * @param string $template_name Template name without extension.
     * @param string $type Either 'template' for full templates or 'partial' for partial templates.
     * @param array  $args Arguments to pass to the template.
     */
    private function load_template( $template_name, $type = 'template', $args = array() ) {
        $dir = ($type === 'template') ? 'ui/templates/admin' : 'ui/partials/admin';
        $template_path = $this->base_path . $dir . '/' . $template_name . '.php';
        
        if ( file_exists( $template_path ) ) {
            extract( $args );
            include $template_path;
        } else {
            // Fallback message for development
            echo '<!-- Template not found: ' . esc_html( $template_path ) . ' -->';
        }
    }

    /**
     * Display settings page
     */
    public function display_settings_page() {
        $args = array(
            'page_title' => get_admin_page_title(),
            'page_slug' => $this->page_slug,
        );
        
        $this->load_template( 'settings-page', 'template', $args );
    }

    /**
     * Render general section
     */
    public function render_general_section() {
        $args = array();
        $this->load_template( 'general-settings', 'partial', $args );
    }

    /**
     * Render enable field
     */
    public function render_enable_field() {
        $options = get_option( 'arsol_css_addons_options', array() );
        $enabled = isset( $options['enable_css_addons'] ) ? $options['enable_css_addons'] : 1;
        
        $args = array(
            'field_type' => 'enable',
            'enabled' => $enabled,
        );
        
        $this->load_template( 'general-settings', 'partial', $args );
    }

    /**
     * Render custom CSS field
     */
    public function render_custom_css_field() {
        $options = get_option( 'arsol_css_addons_options', array() );
        $custom_css = isset( $options['custom_css'] ) ? $options['custom_css'] : '';
        
        $args = array(
            'field_type' => 'custom_css',
            'custom_css' => $custom_css,
        );
        
        $this->load_template( 'general-settings', 'partial', $args );
    }

    /**
     * Sanitize settings
     *
     * @param array $input The input array.
     * @return array
     */
    public function sanitize_settings( $input ) {
        $sanitized_input = array();

        // Sanitize enable option
        $sanitized_input['enable_css_addons'] = isset( $input['enable_css_addons'] ) ? 1 : 0;

        // Sanitize custom CSS
        $sanitized_input['custom_css'] = isset( $input['custom_css'] ) ? wp_strip_all_tags( $input['custom_css'] ) : '';

        return $sanitized_input;
    }
}

// Initialize the admin settings
new \Arsol_CSS_Addons\Admin_Settings();