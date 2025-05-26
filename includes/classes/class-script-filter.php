<?php

namespace Arsol_WP_Snippets;

/**
 * Script Filter Class
 *
 * @package Arsol_WP_Snippets
 * @subpackage Arsol_WP_Snippets/includes/classes
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class Script_Filter
 */
class Script_Filter {
    /**
     * Initialize the class
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_arsol_get_script_packets', array($this, 'get_script_packets'));
        add_action('wp_ajax_arsol_filter_scripts', array($this, 'filter_scripts'));
    }

    /**
     * Enqueue required scripts and styles
     *
     * @param string $hook Current admin page
     */
    public function enqueue_scripts($hook) {
        // Only load on our plugin page
        if ('settings_page_arsol-wp-snippets' !== $hook) {
            return;
        }

        // Use WordPress's built-in Select2
        wp_enqueue_style('select2');
        wp_enqueue_script('select2');

        // Enqueue our admin script
        wp_enqueue_script(
            'arsol-wp-snippets-admin',
            plugins_url('assets/js/arsol-wp-snippets-admin.js', dirname(dirname(__FILE__))),
            array('jquery', 'select2'),
            ARSOL_WP_SNIPPETS_VERSION,
            true
        );

        // Localize script with WordPress data
        wp_localize_script('arsol-wp-snippets-admin', 'arsolScriptFilter', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('arsol_script_filter_nonce'),
            'i18n' => array(
                'selectPacket' => __('Select a packet', 'arsol-wp-snippets'),
                'noResults' => __('No packets found', 'arsol-wp-snippets'),
                'loading' => __('Loading...', 'arsol-wp-snippets')
            )
        ));
    }

    /**
     * AJAX handler to get available packets
     */
    public function get_script_packets() {
        check_ajax_referer('arsol_script_filter_nonce', 'nonce');
        
        $packets = array();
        $scripts = get_option('arsol_wp_snippets_options', array());
        
        // Get unique packets from scripts
        foreach ($scripts as $script) {
            if (!empty($script['packet'])) {
                $packets[$script['packet']] = $script['packet'];
            }
        }
        
        wp_send_json(array_values($packets));
    }

    /**
     * AJAX handler to filter scripts by packet
     */
    public function filter_scripts() {
        check_ajax_referer('arsol_script_filter_nonce', 'nonce');
        
        $packet = sanitize_text_field($_POST['packet']);
        $scripts = get_option('arsol_wp_snippets_options', array());
        
        $filtered_scripts = array();
        foreach ($scripts as $id => $script) {
            if (empty($packet) || $script['packet'] === $packet) {
                $filtered_scripts[$id] = $script;
            }
        }
        
        wp_send_json_success($filtered_scripts);
    }
} 