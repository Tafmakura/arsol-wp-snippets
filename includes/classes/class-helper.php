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
     * Process duplicate file data
     *
     * @param array $file_data File data to process
     * @return array Processed duplicate data
     */
    public static function process_duplicate_data($file_data) {
        if (!isset($file_data['file'])) {
            return array();
        }

        // Normalize file path
        $file_path = self::normalize_path($file_data['file']);
        
        // Get all files with same path
        $duplicate_files = self::get_duplicate_file_data($file_path);
        
        if (empty($duplicate_files)) {
            return array();
        }

        // Sort by loading order
        usort($duplicate_files, function($a, $b) {
            return $a['loading_order'] - $b['loading_order'];
        });

        // Get first file (lowest loading order)
        $first_file = $duplicate_files[0];
        
        // Get all other files
        $other_files = array_filter($duplicate_files, function($f) use ($first_file) {
            return $f['name'] !== $first_file['name'];
        });

        return array(
            'file' => $file_path,
            'name' => $file_data['name'],
            'loading_order' => $file_data['loading_order'],
            'first_source' => $first_file['source_name'],
            'first_name' => $first_file['name'],
            'first_loading_order' => $first_file['loading_order'],
            'total_duplicates' => count($duplicate_files),
            'duplicate_names' => array_map(function($f) {
                return $f['name'];
            }, $other_files)
        );
    }
} 