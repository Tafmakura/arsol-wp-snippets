<?php
/**
 * Template for displaying duplicate file errors
 *
 * @package Arsol_WP_Snippets
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * @var string $dup_path The duplicate file path to display
 */
?>
<div class="arsol-addon-container arsol-error">
    <div class="arsol-first-column">
        <span class="dashicons dashicons-warning"></span>
    </div>
    <div class="arsol-label-container">
        <div class="arsol-addon-info">
            <small class="arsol-addon-error">
                <strong>Duplicate file path detected:</strong> <?php echo esc_html($dup_path); ?>
            </small>
        </div>
    </div>
</div> 