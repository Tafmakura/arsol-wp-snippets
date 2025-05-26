<?php
/**
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