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
     * Get addon data from duplicate file information
     *
     * @param array $dup_data The duplicate file data containing file, name, and loading_order
     * @return array Array containing addon data for display
     */
    public static function get_duplicate_addon_data($dup_data) {
        return array(
            'name' => $dup_data['name'],
            'loading_order' => $dup_data['loading_order'],
            'type' => pathinfo($dup_data['file'], PATHINFO_EXTENSION)
        );
    }

    /**
     * Get duplicate file ID for HTML elements
     *
     * @param array $dup_data The duplicate file data containing file, name, and loading_order
     * @return string Sanitized ID for the duplicate file
     */
    public static function get_duplicate_file_id($dup_data) {
        return 'duplicate-' . sanitize_title($dup_data['file']);
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
            'enabled' => false
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
     * Process duplicate file data for storage
     *
     * @param array $addon_data The addon data containing file, name, and optional loading_order
     * @return array Processed duplicate file data
     */
    public static function process_duplicate_data($addon_data) {
        // Get the source name for the current file
        $path_info = self::normalize_path($addon_data['file']);
        
        // Get all available files through filters
        $php_files = apply_filters('arsol_wp_snippets_php_addon_files', array());
        $css_files = apply_filters('arsol_wp_snippets_css_addon_files', array());
        $js_files = apply_filters('arsol_wp_snippets_js_addon_files', array());
        
        // Combine all files
        $all_files = array_merge($php_files, $css_files, $js_files);
        
        // Find all files that use this path, excluding the current file
        $files_with_same_path = array();
        foreach ($all_files as $file_data) {
            if (isset($file_data['file']) && 
                $file_data['file'] === $addon_data['file'] && 
                $file_data['name'] !== $addon_data['name']) {
                $files_with_same_path[] = $file_data;
            }
        }
        
        // Add the current file to the collection for sorting
        $files_with_same_path[] = $addon_data;
        
        // Sort files by loading order
        usort($files_with_same_path, function($a, $b) {
            $loading_order_a = self::get_loading_order($a);
            $loading_order_b = self::get_loading_order($b);
            return $loading_order_a - $loading_order_b;
        });
        
        // The first file in the sorted array is the one with lowest loading order
        $first_file = $files_with_same_path[0];
        $first_path_info = self::normalize_path($first_file['file']);
        
        // Get all duplicate names for display, excluding the first file
        $duplicate_names = array();
        foreach ($files_with_same_path as $file) {
            if ($file['name'] !== $first_file['name']) {
                $duplicate_names[] = $file['name'];
            }
        }
        
        return array(
            'file' => $addon_data['file'],
            'name' => $addon_data['name'],
            'loading_order' => self::get_loading_order($addon_data),
            'first_source' => $first_path_info['source_name'],
            'first_name' => $first_file['name'],
            'first_loading_order' => self::get_loading_order($first_file),
            'total_duplicates' => count($files_with_same_path),
            'duplicate_names' => $duplicate_names
        );
    }
} 