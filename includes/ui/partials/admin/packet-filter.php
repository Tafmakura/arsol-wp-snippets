<?php
/**
 * Packet Filter Partial
 *
 * @package Arsol_WP_Snippets
 * @subpackage Arsol_WP_Snippets/includes/ui/partials/admin
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get all addon options
$admin_settings = new \Arsol_WP_Snippets\Admin_Settings();
$php_addons = $admin_settings->get_php_addon_options();
$css_addons = $admin_settings->get_css_addon_options();
$js_addons = $admin_settings->get_js_addon_options();

// Collect all unique packets
$packets = array();
foreach (array($php_addons, $css_addons, $js_addons) as $addons) {
    foreach ($addons as $addon) {
        if (!empty($addon['packet'])) {
            $packets[$addon['packet']] = $addon['packet'];
        }
    }
}
ksort($packets); // Sort packets alphabetically
?>

<div class="arsol-script-filter">
    <select id="arsol-packet-filter" class="arsol-select2">
        <option value=""><?php esc_html_e('All Packets', 'arsol-wp-snippets'); ?></option>
        <?php foreach ($packets as $packet) : ?>
            <option value="<?php echo esc_attr($packet); ?>"><?php echo esc_html($packet); ?></option>
        <?php endforeach; ?>
    </select>
</div> 