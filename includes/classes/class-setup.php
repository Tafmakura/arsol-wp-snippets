<?php

namespace Arsol_CSS_Addons;

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
        // load_plugin_textdomain('arsol-css-addons', false, dirname(ARSOL_CSS_ADDONS_PLUGIN_BASENAME) . '/languages');
    }

    /**
     * Include necessary files.
     */
    private function require_files() {
        // Core Classes
       require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-assets.php';
       // require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-admin-settings.php';
       // require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
    }

    /**
     * Instantiate plugin classes.
     */
    private function instantiate_classes() {
     //   new \Arsol_CSS_Addons\Assets();
     //   new \Arsol_CSS_Addons\Admin_Settings();
     //   new \Arsol_CSS_Addons\Shortcodes();
    }
}

// Initialize the setup class
new Setup();