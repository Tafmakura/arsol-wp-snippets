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
?>

<div class="arsol-script-filter">
    <select id="arsol-packet-filter" class="arsol-select2">
        <option value=""><?php esc_html_e('All Packets', 'arsol-wp-snippets'); ?></option>
    </select>
</div> 