<?php
namespace Arsol_WP_Snippets;

/**
 * Helper Class
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Helper {
    /**
     * Normalize a file path and get its display information
     *
     * @param string $file_path The file path to normalize
     * @return array Array containing normalized path and source information
     */
    public static function normalize_path($file_path) {
        $result = array(
            'normalized_path' => $file_path,
            'source_name' => '',
            'display_path' => $file_path
        );

        // Clean up the file path
        $file_reference = $file_path;

        // Remove 'functions/' from plugin paths regardless of format
        if (strpos($file_reference, 'wp-content/plugins/') !== false || strpos($file_reference, WP_PLUGIN_DIR) !== false) {
            $file_reference = preg_replace('#/functions/snippets/#', '/snippets/', $file_reference);
            $file_reference = preg_replace('#/functions/\.\./snippets/#', '/snippets/', $file_reference);
            $file_reference = str_replace('/functions/', '/', $file_reference);
        }

        // Get source name and normalize path based on file location
        if (strpos($file_reference, get_stylesheet_directory_uri()) === 0) {
            $result['source_name'] = wp_get_theme()->get('Name') . ' → ';
            $result['normalized_path'] = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $file_reference);
        } elseif (strpos($file_reference, get_template_directory_uri()) === 0) {
            $result['source_name'] = wp_get_theme()->get('Name') . ' → ';
            $result['normalized_path'] = str_replace(get_template_directory_uri(), get_template_directory(), $file_reference);
        } elseif (strpos($file_reference, plugins_url()) === 0 || strpos($file_reference, WP_PLUGIN_DIR) === 0) {
            // Get plugin name from the file path
            $plugin_path = str_replace(plugins_url(), WP_PLUGIN_DIR, $file_reference);
            $plugin_dir = dirname($plugin_path);
            
            // Keep going up until we find the plugin's main PHP file
            $found_plugin = false;
            while (!$found_plugin && strpos($plugin_dir, WP_PLUGIN_DIR) === 0) {
                $plugin_file = $plugin_dir . '/' . basename($plugin_dir) . '.php';
                if (file_exists($plugin_file)) {
                    $found_plugin = true;
                } else {
                    $plugin_dir = dirname($plugin_dir);
                }
            }
            
            if ($found_plugin) {
                $plugin_data = get_plugin_data($plugin_file);
                $result['source_name'] = $plugin_data['Name'] . ' → ';
            } else {
                // Try to get plugin name from directory
                $plugin_name = basename($plugin_dir);
                $result['source_name'] = ucwords(str_replace('-', ' ', $plugin_name)) . ' → ';
            }
            
            $result['normalized_path'] = $plugin_path;
        }

        // Set the display path
        $result['display_path'] = $result['normalized_path'];

        return $result;
    }

    /**
     * Get default options for file types
     *
     * @param string $option_key The specific option key to get, or null for all defaults
     * @return mixed The default value(s)
     */
    public static function get_default_options($option_key = null) {
        $defaults = array(
            'loading_order' => 10,
            'context' => 'global',
            'enabled' => false,
            'priority' => 10,
            'hook' => 'init'  // Default hook for PHP snippets
        );
        
        if ($option_key !== null) {
            return isset($defaults[$option_key]) ? $defaults[$option_key] : null;
        }
        
        return $defaults;
    }

    /**
     * Get loading order for a file
     *
     * @param array $file_data The file data array
     * @return int The loading order value
     */
    public static function get_loading_order($file_data) {
        return isset($file_data['loading_order']) ? intval($file_data['loading_order']) : self::get_default_options('loading_order');
    }

    /**
     * Get priority for a file
     *
     * @param array $file_data The file data array
     * @return int The priority value
     */
    public static function get_priority($file_data) {
        return isset($file_data['priority']) ? intval($file_data['priority']) : self::get_default_options('priority');
    }

    /**
     * Get hook for a PHP file
     *
     * @param array $file_data The file data array
     * @return string The hook name
     */
    public static function get_hook($file_data) {
        return isset($file_data['hook']) ? sanitize_text_field($file_data['hook']) : self::get_default_options('hook');
    }

    /**
     * Get available WordPress hooks for PHP snippets
     *
     * @return array Array of hook => description pairs
     */
    public static function get_available_hooks() {
        return array(
            'plugins_loaded' => 'Plugins Loaded (Early - before init)',
            'init' => 'Init (Default - recommended for most snippets)',
            'wp_loaded' => 'WordPress Loaded (After init)',
            'template_redirect' => 'Template Redirect (Frontend only, before content)',
            'wp_head' => 'Head Section (Frontend only, in HTML head)',
            'wp_footer' => 'Footer Section (Frontend only, before closing body)',
            'admin_init' => 'Admin Init (Admin only)',
            'admin_menu' => 'Admin Menu (Admin only, when building admin menu)',
            'wp_enqueue_scripts' => 'Enqueue Scripts (Frontend only, for script/style registration)',
            'admin_enqueue_scripts' => 'Admin Enqueue Scripts (Admin only, for script/style registration)',
            'wp_ajax_' => 'AJAX Requests (For AJAX handlers - add action name after underscore)',
            'rest_api_init' => 'REST API Init (When REST API is initialized)',
            'widgets_init' => 'Widgets Init (When widgets are initialized)',
            'after_setup_theme' => 'After Setup Theme (Early theme setup)',
            'wp_print_styles' => 'Print Styles (When styles are printed)',
            'wp_print_scripts' => 'Print Scripts (When scripts are printed)'
        );
    }

    /**
     * Get hook description for display
     *
     * @param string $hook_name The hook name
     * @return string The hook description
     */
    public static function get_hook_description($hook_name) {
        $hooks = self::get_available_hooks();
        return isset($hooks[$hook_name]) ? $hooks[$hook_name] : $hook_name;
    }

    /**
     * Sort files by position, priority, loading order, and maintain original order
     *
     * @param array $files Array of file data
     * @return array Sorted files
     */
    public static function sort_files_by_loading_order($files) {
        // Convert to array for sorting while preserving original order
        $files_array = array();
        $index = 0;
        foreach ($files as $key => $file) {
            $files_array[] = array(
                'key' => $key,
                'file' => $file,
                'position' => isset($file['position']) ? $file['position'] : 'footer',
                'priority' => self::get_priority($file),
                'loading_order' => self::get_loading_order($file),
                'original_index' => $index++
            );
        }

        // Sort by position (header first), then priority, then loading_order, then original order
        usort($files_array, function($a, $b) {
            // First sort by position (header before footer)
            if ($a['position'] !== $b['position']) {
                return $a['position'] === 'header' ? -1 : 1;
            }
            // Then by priority
            if ($a['priority'] !== $b['priority']) {
                return $a['priority'] - $b['priority'];
            }
            // Then by loading order
            if ($a['loading_order'] !== $b['loading_order']) {
                return $a['loading_order'] - $b['loading_order'];
            }
            // Finally by original order
            return $a['original_index'] - $b['original_index'];
        });

        // Convert back to original format
        $sorted_files = array();
        foreach ($files_array as $item) {
            $sorted_files[$item['key']] = $item['file'];
        }

        return $sorted_files;
    }

    /**
     * Get category for a numeric value (priority or loading order)
     *
     * @param int $value The numeric value to categorize
     * @return string The category name ('Early', 'Default', 'Late', or 'Very Late')
     */
    public static function get_category_for_value($value) {
        if ($value <= 5) {
            return 'Early';
        } elseif ($value <= 10) {
            return 'Default';
        } elseif ($value <= 20) {
            return 'Late';
        } else {
            return 'Very Late';
        }
    }
} 