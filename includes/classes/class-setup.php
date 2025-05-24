<?php

namespace Arsol_WP_Snippets;

if (!defined('ABSPATH')) {
    exit;
}

class Setup {
    public function __construct() {
        $this->require_files();
        $this->instantiate_classes();
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        // Load plugin text domain
        // load_plugin_textdomain('arsol-wp-snippets', false, dirname(ARSOL_WP_SNIPPETS_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Include necessary files.
     */
    private function require_files() {
        // Core Classes
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-assets.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-admin-settings.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-theme-support.php';

        // Core Functions 
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-admin.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-addon-css.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-addon-js.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-addon-php.php';
    }

    /**
     * Instantiate plugin classes.
     */
    private function instantiate_classes() {
        new \Arsol_WP_Snippets\Assets();
        new \Arsol_WP_Snippets\Admin_Settings();
        new \Arsol_WP_Snippets\Shortcodes();
        new \Arsol_WP_Snippets\Theme_Support();
    }
}