
<div class="wrap">
    <h1><?php echo esc_html( $page_title ); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'arsol_css_addons_settings' );
        do_settings_sections( $page_slug );
        submit_button();
        ?>
    </form>
</div>