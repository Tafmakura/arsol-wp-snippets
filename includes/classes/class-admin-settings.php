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
            __('Admin CSS', 'arsol-css-addons'),
            function() {
                echo '<p>' . esc_html__('Select CSS enhancements for the WordPress admin area.', 'arsol-css-addons') . '</p>';
            },
            $this->css_addons_slug
        );
        
        // Add admin CSS options
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
        
        // Add frontend CSS options
        add_settings_field(
            'frontend_css_options',
            __('Frontend CSS Files', 'arsol-css-addons'),
            array($this, 'render_frontend_css_options'),
            $this->css_addons_slug,
            'arsol_css_addons_frontend'
        );
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
            // Check if file exists by converting URL to file path
            $file_url = $css_data['file'];
            $file_path = str_replace(ARSOL_CSS_ADDONS_PLUGIN_URL, ARSOL_CSS_ADDONS_PLUGIN_DIR, $file_url);
            $file_exists = file_exists($file_path);
            
            if ($file_exists) {
                // File exists - show checkbox
                $checked = isset($admin_css_options[$css_id]) ? $admin_css_options[$css_id] : 0;
                ?>
                <p>
                    <input type="checkbox" id="arsol-admin-css-<?php echo esc_attr($css_id); ?>" 
                           name="arsol_css_addons_options[admin_css_options][<?php echo esc_attr($css_id); ?>]" 
                           value="1" <?php checked(1, $checked); ?>/>
                    <label for="arsol-admin-css-<?php echo esc_attr($css_id); ?>"><?php echo esc_html($css_data['name']); ?></label>
                </p>
                <?php
            } else {
                // File doesn't exist - show error message
                ?>
                <p class="arsol-css-error">
                    <span class="dashicons dashicons-warning" style="color: #d63638; vertical-align: middle;"></span>
                    <span style="color: #d63638;">
                        <?php echo esc_html(sprintf(__('CSS file "%s" could not be found.', 'arsol-css-addons'), $css_data['name'])); ?>
                    </span>
                </p>
                <?php
            }
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
            $checked = isset($frontend_css_options[$css_id]) ? $frontend_css_options[$css_id] : 0;
            ?>
            <p>
                <input type="checkbox" id="arsol-frontend-css-<?php echo esc_attr($css_id); ?>" 
                       name="arsol_css_addons_options[frontend_css_options][<?php echo esc_attr($css_id); ?>]" 
                       value="1" <?php checked(1, $checked); ?>/>
                <label for="arsol-frontend-css-<?php echo esc_attr($css_id); ?>"><?php echo esc_html($css_data['name']); ?></label>
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
                
                /**
                 * Action that fires when an admin CSS file is loaded
                 * 
                 * @param string $css_id The ID of the CSS file
                 * @param string $file_url The URL of the CSS file
                 */
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
                
                /**
                 * Action that fires when a frontend CSS file is loaded
                 * 
                 * @param string $css_id The ID of the CSS file
                 * @param string $file_url The URL of the CSS file
                 */
                do_action('arsol_css_addons_loaded_frontend_css', $css_id, $frontend_css_options[$css_id]['file']);
            }
        }
    }
}

