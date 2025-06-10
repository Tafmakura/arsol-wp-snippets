<?php
/**
 * Admin Settings Page Template
 * 
 * Available variables:
 * @var string $page_title - The page title
 * @var string $settings_slug - The settings slug
 * @var Admin_Settings $admin_settings - The admin settings instance
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get current options
$options = get_option('arsol_wp_snippets_options', array());
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