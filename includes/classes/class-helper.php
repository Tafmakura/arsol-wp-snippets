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
            'priority' => 10
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
} 