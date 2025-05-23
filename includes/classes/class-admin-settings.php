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
            __('Admin CSS', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS enhancements for the WordPress admin area.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add admin CSS options (removed the enable toggle)
        add_settings_field(
            'admin_css_options',
            __('Admin CSS Files', 'arsol-css-addons'),
            array($this, 'render_admin_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_admin'
        );
        
        // Add Frontend CSS Section
        add_settings_section(
            'arsol_css_addons_frontend',
            __('Frontend CSS', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS enhancements for your website frontend.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add frontend CSS options (removed the enable toggle)
        add_settings_field(
            'frontend_css_options',
            __('Frontend CSS Files', 'arsol-css-addons'),
            array($this, 'render_frontend_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_frontend'
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
     * Render enable admin CSS field
     */
    public function render_enable_admin_field() {
        $options = get_option('arsol_css_addons_options', array());
        $enabled = isset($options['enable_admin_css']) ? $options['enable_admin_css'] : 1;
        ?>
        <input type="checkbox" name="arsol_css_addons_options[enable_admin_css]" value="1" <?php checked(1, $enabled); ?>/>
        <span class="description"><?php esc_html_e('Enable CSS enhancements for the admin area', 'arsol-css-addons'); ?></span>
        <?php
    }
    
    /**
     * Render admin CSS options checkboxes
     */
    public function render_admin_css_options() {
        $options = get_option('arsol_css_addons_options', array());
        $admin_css_options = isset($options['admin_css_options']) ? $options['admin_css_options'] : array();
        
        // Define available admin CSS files
        $available_admin_css = array(
            'admin-menu' => __('Enhanced Admin Menu', 'arsol-css-addons'),
            'admin-buttons' => __('Enhanced Admin Buttons', 'arsol-css-addons'),
            'admin-forms' => __('Enhanced Admin Forms', 'arsol-css-addons'),
            'admin-tables' => __('Enhanced Admin Tables', 'arsol-css-addons'),
        );
        
        foreach ($available_admin_css as $css_id => $css_name) {
            $checked = isset($admin_css_options[$css_id]) ? $admin_css_options[$css_id] : 0;
            ?>
            <p>
                <input type="checkbox" id="arsol-admin-css-<?php echo esc_attr($css_id); ?>" 
                       name="arsol_css_addons_options[admin_css_options][<?php echo esc_attr($css_id); ?>]" 
                       value="1" <?php checked(1, $checked); ?>/>
                <label for="arsol-admin-css-<?php echo esc_attr($css_id); ?>"><?php echo esc_html($css_name); ?></label>
            </p>
            <?php
        }
    }
    
    /**
     * Render enable frontend CSS field
     */
    public function render_enable_frontend_field() {
        $options = get_option('arsol_css_addons_options', array());
        $enabled = isset($options['enable_frontend_css']) ? $options['enable_frontend_css'] : 1;
        ?>
        <input type="checkbox" name="arsol_css_addons_options[enable_frontend_css]" value="1" <?php checked(1, $enabled); ?>/>
        <span class="description"><?php esc_html_e('Enable CSS enhancements for the frontend', 'arsol-css-addons'); ?></span>
        <?php
    }
    
    /**
     * Render frontend CSS options checkboxes
     */
    public function render_frontend_css_options() {
        $options = get_option('arsol_css_addons_options', array());
        $frontend_css_options = isset($options['frontend_css_options']) ? $options['frontend_css_options'] : array();
        
        // Define available frontend CSS files
        $available_frontend_css = array(
            'buttons' => __('Enhanced Buttons', 'arsol-css-addons'),
            'forms' => __('Enhanced Forms', 'arsol-css-addons'),
            'typography' => __('Enhanced Typography', 'arsol-css-addons'),
            'layouts' => __('Enhanced Layouts', 'arsol-css-addons'),
        );
        
        foreach ($available_frontend_css as $css_id => $css_name) {
            $checked = isset($frontend_css_options[$css_id]) ? $frontend_css_options[$css_id] : 0;
            ?>
            <p>
                <input type="checkbox" id="arsol-frontend-css-<?php echo esc_attr($css_id); ?>" 
                       name="arsol_css_addons_options[frontend_css_options][<?php echo esc_attr($css_id); ?>]" 
                       value="1" <?php checked(1, $checked); ?>/>
                <label for="arsol-frontend-css-<?php echo esc_attr($css_id); ?>"><?php echo esc_html($css_name); ?></label>
            </p>
            <?php
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
        
        return $sanitized_input;
    }
}

