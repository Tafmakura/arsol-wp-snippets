<?php
/**
 * Admin settings page template
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html($page_title); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('arsol_wp_snippets_settings');
        do_settings_sections($settings_slug);
        submit_button();
        ?>
    </form>
</div>