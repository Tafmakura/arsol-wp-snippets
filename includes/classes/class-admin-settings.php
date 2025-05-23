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
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('arsol_css_addons_settings');
                do_settings_sections($this->css_addons_slug);
                submit_button();
                ?>
            </form>
        </div>
        <?php
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
            array($this, 'render_enable_field'),
            $this->css_addons_slug,
            'arsol_css_addons_general'
        );
        
        // Add custom CSS field
        add_settings_field(
            'custom_css',
            __('Custom CSS', 'arsol-css-addons'),
            array($this, 'render_css_field'),
            $this->css_addons_slug,
            'arsol_css_addons_general'
        );
    }
    
    /**
     * Render enable checkbox field
     */
    public function render_enable_field() {
        $options = get_option('arsol_css_addons_options', array());
        $enabled = isset($options['enable_css_addons']) ? $options['enable_css_addons'] : 1;
        ?>
        <input type="checkbox" name="arsol_css_addons_options[enable_css_addons]" value="1" <?php checked(1, $enabled); ?>/>
        <span class="description"><?php esc_html_e('Enable CSS Addons functionality', 'arsol-css-addons'); ?></span>
        <?php
    }
    
    /**
     * Render custom CSS field
     */
    public function render_css_field() {
        $options = get_option('arsol_css_addons_options', array());
        $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';
        ?>
        <textarea name="arsol_css_addons_options[custom_css]" rows="10" class="large-text code"><?php echo esc_textarea($custom_css); ?></textarea>
        <p class="description"><?php esc_html_e('Add custom CSS rules to be applied to your site.', 'arsol-css-addons'); ?></p>
        <?php
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
        
        // Sanitize custom CSS - allow CSS but strip dangerous tags
        $sanitized_input['custom_css'] = isset($input['custom_css']) ? sanitize_textarea_field($input['custom_css']) : '';
        
        return $sanitized_input;
    }
}

