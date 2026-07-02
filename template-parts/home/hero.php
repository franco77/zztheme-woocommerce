<?php
/**
 * Banner principal (hero) de la homepage.
 */

$title    = (string) get_theme_mod('zz_hero_title', __('Repuestos de Calidad', 'zztheme'));
$subtitle = (string) get_theme_mod('zz_hero_subtitle', __('Encuentra todo lo que necesitas para tu vehículo', 'zztheme'));
$cta_text = (string) get_theme_mod('zz_hero_cta_text', __('Ver catálogo', 'zztheme'));
$cta_url  = (string) get_theme_mod('zz_hero_cta_url', '');
$image_id = (int)    get_theme_mod('zz_hero_image', 0);

if (!$cta_url && class_exists('WooCommerce')) {
    $cta_url = get_permalink(wc_get_page_id('shop'));
}

$bg_style = '';
if ($image_id) {
    $bg_url   = wp_get_attachment_image_url($image_id, 'zztheme-hero');
    $bg_style = $bg_url ? ' style="background-image:url(\'' . esc_url($bg_url) . '\')"' : '';
}
?>
<section class="home-hero"<?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
    <div class="home-hero__overlay"></div>
    <div class="container">
        <div class="home-hero__content">
            <?php if ($title) : ?>
                <h1><?php echo esc_html($title); ?></h1>
            <?php endif; ?>

            <?php if ($subtitle) : ?>
                <p><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($cta_text && $cta_url) : ?>
                <a href="<?php echo esc_url($cta_url); ?>" class="button button--outline home-hero__cta">
                    <?php echo esc_html($cta_text); ?>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
