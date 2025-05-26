<?php
/**
 * Settings Page Template
 *
 * @package Arsol_WP_Snippets
 * @subpackage Arsol_WP_Snippets/includes/ui/templates/admin
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get current options
$options = get_option('arsol_wp_snippets_options', array());
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php 
    // Include the packet filter partial
    require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/packet-filter.php';
    ?>

    <form method="post" action="options.php">
        <?php
        settings_fields('arsol_wp_snippets_settings');
        do_settings_sections('arsol-wp-snippets');
        submit_button();
        ?>
    </form>
</div>