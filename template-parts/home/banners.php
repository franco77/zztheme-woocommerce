<?php
/**
 * Sección de banners publicitarios — carrusel configurable (2 o 3 por fila).
 */

$section_title = (string) get_theme_mod('zz_banners_title', '');
$columns       = max(2, min(3, (int) get_theme_mod('zz_banners_columns', 2)));

$banners = [];
for ($i = 1; $i <= 6; $i++) {
    $title = (string) get_theme_mod("zz_banner_{$i}_title", '');
    if (!$title) {
        continue;
    }
    $img_id    = (int) get_theme_mod("zz_banner_{$i}_image", 0);
    $banners[] = [
        'label'    => (string) get_theme_mod("zz_banner_{$i}_label", ''),
        'title'    => $title,
        'subtitle' => (string) get_theme_mod("zz_banner_{$i}_subtitle", ''),
        'cta_text' => (string) get_theme_mod("zz_banner_{$i}_cta_text", __('Ver más', 'zztheme')),
        'cta_url'  => (string) get_theme_mod("zz_banner_{$i}_cta_url", ''),
        'img_url'  => $img_id ? wp_get_attachment_image_url($img_id, 'full') : '',
        'light'    => (bool) get_theme_mod("zz_banner_{$i}_light_bg", false),
    ];
}

if (empty($banners)) {
    return;
}

$show_nav = count($banners) > $columns;
?>
<section class="home-section home-section--carousel home-section--banners">
    <div class="container">

        <?php if ($section_title || $show_nav) : ?>
        <div class="section-header<?php echo !$section_title ? ' section-header--end' : ''; ?>">
            <?php if ($section_title) : ?>
                <h2><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            <?php if ($show_nav) : ?>
            <div class="carousel-nav" aria-label="<?php esc_attr_e('Navegación banners', 'zztheme'); ?>">
                <button class="carousel-btn carousel-btn--prev" aria-label="<?php esc_attr_e('Anterior', 'zztheme'); ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </button>
                <button class="carousel-btn carousel-btn--next" aria-label="<?php esc_attr_e('Siguiente', 'zztheme'); ?>">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="carousel-outer">
            <div class="carousel-track carousel-track--banners-<?php echo $columns; ?>">
                <?php foreach ($banners as $banner) : ?>
                <div class="carousel-slide carousel-slide--banner-<?php echo $columns; ?>">
                    <div class="banner-card<?php echo $banner['light'] ? ' banner-card--light-bg' : ''; ?>"
                         <?php if ($banner['img_url']) : ?>style="background-image: url('<?php echo esc_url($banner['img_url']); ?>')"<?php endif; ?>>
                        <div class="banner-card__content">
                            <?php if ($banner['label']) : ?>
                            <span class="banner-card__label"><?php echo esc_html($banner['label']); ?></span>
                            <?php endif; ?>
                            <h3 class="banner-card__title"><?php echo esc_html($banner['title']); ?></h3>
                            <?php if ($banner['subtitle']) : ?>
                            <p class="banner-card__subtitle"><?php echo esc_html($banner['subtitle']); ?></p>
                            <?php endif; ?>
                            <?php if ($banner['cta_text'] && $banner['cta_url']) : ?>
                            <a href="<?php echo esc_url($banner['cta_url']); ?>" class="banner-card__btn">
                                <?php echo esc_html($banner['cta_text']); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>
