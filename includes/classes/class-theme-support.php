<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Support {
    
    public function __construct() {
        add_action('after_setup_theme', array($this, 'register_theme_addons'));
    }

    /**
     * Register theme addons automatically
     */
    public function register_theme_addons() {
        $this->auto_register_theme_files();
        do_action('arsol_wp_snippets_register_theme_addons');
    }

    /**
     * Automatically register theme addon files from arsol-wp-snippets directory
     */
    private function auto_register_theme_files() {
        // Check both child theme and parent theme directories
        $theme_directories = array();
        
        // Child theme first (if exists)
        if (is_child_theme()) {
            $theme_directories['child'] = array(
                'dir' => get_stylesheet_directory(),
                'uri' => get_stylesheet_directory_uri(),
                'label' => 'Child Theme'
            );
        }
        
        // Parent theme
        $theme_directories['parent'] = array(
            'dir' => get_template_directory(),
            'uri' => get_template_directory_uri(),
            'label' => 'Theme'
        );
        
        foreach ($theme_directories as $theme_type => $theme_info) {
            $this->scan_theme_directory($theme_info, $theme_type);
        }
    }

    /**
     * Scan a specific theme directory for addon files
     */
    private function scan_theme_directory($theme_info, $theme_type) {
        $theme_dir = $theme_info['dir'];
        $theme_uri = $theme_info['uri'];
        $theme_label = $theme_info['label'];
        
        // Check for arsol-wp-snippets directory in theme
        $snippets_dir = $theme_dir . '/arsol-wp-snippets/';
        
        // Debug: Add error log to see if directory exists
        error_log("Checking {$theme_label} directory: " . $snippets_dir);
        error_log("Directory exists: " . (is_dir($snippets_dir) ? 'YES' : 'NO'));
        
        if (!is_dir($snippets_dir)) {
            return;
        }
        
        // Define addon directories within arsol-wp-snippets
        $addon_paths = array(
            'css' => array(
                'dir' => $snippets_dir . 'css/',
                'uri' => $theme_uri . '/arsol-wp-snippets/css/',
                'context' => 'frontend',
                'position' => 'header'
            ),
            'js' => array(
                'dir' => $snippets_dir . 'js/',
                'uri' => $theme_uri . '/arsol-wp-snippets/js/',
                'context' => 'frontend',
                'position' => 'footer'
            ),
            'php' => array(
                'dir' => $snippets_dir . 'php/',
                'uri' => null, // PHP files don't need URIs
                'context' => 'global',
                'position' => null
            )
        );
        
        foreach ($addon_paths as $type => $config) {
            if (is_dir($config['dir'])) {
                $this->register_files_from_directory($type, $config, $theme_type, $theme_label);
            }
        }
    }

    /**
     * Register files from a theme directory
     */
    private function register_files_from_directory($type, $config, $theme_type, $theme_label) {
        $files = glob($config['dir'] . '*.' . $type);
        
        if (empty($files)) {
            return;
        }
        
        $filter_name = "arsol_wp_snippets_{$type}_addon_files";
        
        add_filter($filter_name, function($options) use ($files, $config, $type, $theme_type, $theme_label) {
            foreach ($files as $file_path) {
                $filename = basename($file_path, '.' . $type);
                $file_key = $theme_type . '-theme-' . sanitize_key($filename);
                
                // Build addon data
                $addon_data = array(
                    'name' => ucwords(str_replace(array('-', '_'), ' ', $filename)) . " ({$theme_label})",
                );
                
                if ($type === 'php') {
                    $addon_data['file'] = $file_path;
                    $addon_data['context'] = 'global';
                } else {
                    $addon_data['file'] = $config['uri'] . basename($file_path);
                    $addon_data['context'] = 'frontend'; // Explicitly set to frontend only
                    $addon_data['position'] = $config['position']; // header for CSS, footer for JS
                }
                
                $options[$file_key] = $addon_data;
            }
            
            return $options;
        });
    }
}