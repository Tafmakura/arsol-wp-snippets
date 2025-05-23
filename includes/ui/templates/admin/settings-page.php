<?php
/**
 * Admin settings page template
 *
 * @package Arsol_CSS_Addons
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html($page_title); ?></h1>
    
    <?php if ($page_type === 'main') : ?>
    
        <!-- Main Hello World Page -->
        <div class="card">
            <h2><?php esc_html_e('Hello World', 'arsol-css-addons'); ?></h2>
            <p><?php esc_html_e('Welcome to the Arsol plugin suite!', 'arsol-css-addons'); ?></p>
        </div>
        
    <?php elseif ($page_type === 'css-addons') : ?>
    
        <!-- CSS Addons Settings Page -->
        <form method="post" action="options.php">
            <?php 
            // Output nonce, action, and option_page fields
            settings_fields('arsol_css_addons_settings');
            
            // Output settings sections and fields
            do_settings_sections($page_slug);
            
            // Output save settings button
            submit_button();
            ?>
        </form>
        
    <?php endif; ?>
</div>