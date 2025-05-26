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
<<<<<<< HEAD
        // load_plugin_textdomain('arsol-wp-snippets', false, dirname(ARSOL_WP_SNIPPETS_PLUGIN_BASENAME) . '/languages');
=======
        // load_plugin_textdomain('arsol-css-addons', false, dirname(ARSOL_CSS_ADDONS_PLUGIN_BASENAME) . '/languages');
>>>>>>> parent of c855b08 (Merge branch 'production' into staging)
    }

    /**
     * Include necessary files.
     */
    private function require_files() {
        // Core Classes
<<<<<<< HEAD
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-assets.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-admin-settings.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
<<<<<<< HEAD
=======
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-theme-support.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-snippet-loader.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/classes/class-script-filter.php';
>>>>>>> parent of caed6d7 (Revert "Packet Filter")

        // Core Functions 
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-admin.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-addon-css.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-addon-js.php';
        require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/functions/functions-addon-php.php';
=======
        require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-assets.php';
        require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-admin-settings.php';
        require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';

        // Core Functions 
        require_once ARSOL_CSS_ADDONS_PLUGIN_DIR . 'includes/functions/functions-admin.php';
>>>>>>> parent of c855b08 (Merge branch 'production' into staging)
    }

    /**
     * Instantiate plugin classes.
     */
    private function instantiate_classes() {
<<<<<<< HEAD
        new \Arsol_WP_Snippets\Assets();
        new \Arsol_WP_Snippets\Admin_Settings();
        new \Arsol_WP_Snippets\Shortcodes();
<<<<<<< HEAD
=======
        new \Arsol_CSS_Addons\Assets();
        new \Arsol_CSS_Addons\Admin_Settings();
        new \Arsol_CSS_Addons\Shortcodes();
>>>>>>> parent of c855b08 (Merge branch 'production' into staging)
=======
        new \Arsol_WP_Snippets\Theme_Support();
        new \Arsol_WP_Snippets\Snippet_Loader();
        new \Arsol_WP_Snippets\Script_Filter();
>>>>>>> parent of caed6d7 (Revert "Packet Filter")
    }
}

// Initialize the setup class
new Setup();