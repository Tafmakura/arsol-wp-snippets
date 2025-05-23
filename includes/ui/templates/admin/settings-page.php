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
    <form method="post" action="options.php">
        <?php
        settings_fields('arsol-css-addons');
        do_settings_sections('arsol-css-addons');
        submit_button();
        ?>
    </form>
</div>