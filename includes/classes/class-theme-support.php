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
        $theme_dir = get_template_directory();
        $theme_uri = get_template_directory_uri();
        
        // Check for arsol-wp-snippets directory in theme
        $snippets_dir = $theme_dir . '/arsol-wp-snippets/';
        
        if (!is_dir($snippets_dir)) {
            return;
        }
        
        // Define addon directories within arsol-wp-snippets
        $addon_paths = array(
            'css' => array(
                'dir' => $snippets_dir . 'css/',
                'uri' => $theme_uri . '/arsol-wp-snippets/css/',
                'context' => 'frontend'
            ),
            'js' => array(
                'dir' => $snippets_dir . 'js/',
                'uri' => $theme_uri . '/arsol-wp-snippets/js/',
                'context' => 'frontend'
            ),
            'php' => array(
                'dir' => $snippets_dir . 'php/',
                'uri' => null, // PHP files don't need URIs
                'context' => null
            )
        );
        
        foreach ($addon_paths as $type => $config) {
            if (is_dir($config['dir'])) {
                $this->register_files_from_directory($type, $config);
            }
        }
    }

    /**
     * Register files from a theme directory
     */
    private function register_files_from_directory($type, $config) {
        $files = glob($config['dir'] . '*.' . $type);
        
        if (empty($files)) {
            return;
        }
        
        $filter_name = "arsol_wp_snippets_{$type}_addon_files";
        
        add_filter($filter_name, function($options) use ($files, $config, $type) {
            foreach ($files as $file_path) {
                $filename = basename($file_path, '.' . $type);
                $file_key = 'theme-' . sanitize_key($filename);
                
                // Build addon data
                $addon_data = array(
                    'name' => ucwords(str_replace(array('-', '_'), ' ', $filename)) . ' (Theme)',
                );
                
                if ($type === 'php') {
                    $addon_data['file'] = $file_path;
                } else {
                    $addon_data['file'] = $config['uri'] . basename($file_path);
                    $addon_data['context'] = $config['context'];
                    $addon_data['position'] = ($type === 'js') ? 'footer' : 'header';
                }
                
                $options[$file_key] = $addon_data;
            }
            
            return $options;
        });
    }
}