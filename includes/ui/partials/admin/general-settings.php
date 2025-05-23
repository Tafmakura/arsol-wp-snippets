<?php
/**
 * ARSol CSS Addons Admin Settings
 *
 * @package ARSol_CSS_Addons
 */

defined( 'ABSPATH' ) || exit;

$options = get_option( 'arsol_css_addons_options' );
$enabled = isset( $options['enable_css_addons'] ) ? $options['enable_css_addons'] : 0;
$custom_css = isset( $options['custom_css'] ) ? $options['custom_css'] : '';
$field_type = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';

?>

<div class="wrap">
    <h1><?php esc_html_e( 'CSS Addons Settings', 'arsol-css-addons' ); ?></h1>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'arsol_css_addons' );
        do_settings_sections( 'arsol_css_addons' );
        ?>

        <p><?php esc_html_e( 'Configure the CSS addons settings below.', 'arsol-css-addons' ); ?></p>

        <?php if ( isset( $field_type ) && $field_type === 'enable' ): ?>
            <label>
                <input type="checkbox" name="arsol_css_addons_options[enable_css_addons]" value="1" <?php checked( 1, $enabled ); ?>>
                <?php esc_html_e( 'Enable CSS addons functionality', 'arsol-css-addons' ); ?>
            </label>
        <?php endif; ?>

        <?php if ( isset( $field_type ) && $field_type === 'custom_css' ): ?>
            <textarea name="arsol_css_addons_options[custom_css]" rows="10" cols="50" class="large-text code"><?php echo esc_textarea( $custom_css ); ?></textarea>
            <p class="description"><?php esc_html_e( 'Add custom CSS rules here. They will be added to your site.', 'arsol-css-addons' ); ?></p>
        <?php endif; ?>

        <?php submit_button(); ?>
    </form>
</div>