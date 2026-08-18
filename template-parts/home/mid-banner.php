<?php
/**
 * Banner intermedio entre "Recién Llegados" y "Productos en Oferta".
 * Solo se renderiza si hay una imagen configurada en el Customizer.
 */

$image_id = (int) get_theme_mod('zz_mid_banner_image', 0);
if ( ! $image_id ) {
    return;
}

$image = wp_get_attachment_image( $image_id, 'full', false, [
    'class'   => 'mid-banner__img',
    'loading' => 'lazy',
    'alt'     => (string) get_theme_mod( 'zz_mid_banner_alt', get_bloginfo( 'name' ) ),
] );

if ( ! $image ) {
    return;
}

$url = (string) get_theme_mod( 'zz_mid_banner_url', '' );
?>
<section class="mid-banner">
    <div class="container">
        <?php if ( $url ) : ?>
            <a href="<?php echo esc_url( $url ); ?>" class="mid-banner__link">
                <?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </a>
        <?php else : ?>
            <?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php endif; ?>
    </div>
</section>
