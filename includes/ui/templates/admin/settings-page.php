<?php
/**
<<<<<<< HEAD
 * Admin settings page template
 *
<<<<<<< HEAD
 * @package Arsol_WP_Snippets
=======
 * @package Arsol_CSS_Addons
>>>>>>> parent of c855b08 (Merge branch 'production' into staging)
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
=======
 * Settings Page Template
 *
 * @package Arsol_WP_Snippets
 * @subpackage Arsol_WP_Snippets/includes/ui/templates/admin
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
>>>>>>> parent of caed6d7 (Revert "Packet Filter")
}
?>
<div class="wrap">
<<<<<<< HEAD
    <h1><?php echo esc_html($page_title); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('arsol-css-addons');
        do_settings_sections('arsol-css-addons');
=======
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php 
    // Include the packet filter partial
    require_once ARSOL_WP_SNIPPETS_PLUGIN_DIR . 'includes/ui/partials/admin/packet-filter.php';
    ?>

    <form method="post" action="options.php">
        <?php
        settings_fields('arsol_wp_snippets_options');
        do_settings_sections('arsol_wp_snippets_options');
>>>>>>> parent of caed6d7 (Revert "Packet Filter")
        submit_button();
        ?>
    </form>
</div>