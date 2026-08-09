<?php
/**
 * Sección de categorías en la homepage — carrusel de tarjetas.
 */

if (!class_exists('WooCommerce')) {
    return;
}

$title  = (string) get_theme_mod('zz_catgrid_title', __('Categorías', 'zztheme'));
$mode   = (string) get_theme_mod('zz_catgrid_mode', 'auto');
$count  = max(1, (int) get_theme_mod('zz_catgrid_count', 8));
$custom = (string) get_theme_mod('zz_catgrid_ids', '');

if ($mode === 'manual' && $custom) {
    $ids   = array_filter(array_map('intval', explode(',', $custom)));
    $terms = array_filter(array_map(function ($id) {
        $t = get_term($id, 'product_cat');
        return (!$t || is_wp_error($t)) ? null : $t;
    }, $ids));
} else {
    $terms = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'exclude'    => [(int) get_option('default_product_cat')],
        'number'     => $count,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ]);
}

if (empty($terms)) {
    return;
}
?>
<section class="home-section home-section--carousel home-section--cats">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-eyebrow"><?php esc_html_e('Explora', 'zztheme'); ?></span>
                <h2><?php echo esc_html($title); ?></h2>
            </div>
            <div class="carousel-nav" aria-label="<?php esc_attr_e('Navegación categorías', 'zztheme'); ?>">
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
        </div>

        <div class="carousel-outer">
            <div class="carousel-track carousel-track--cats">
                <?php foreach ($terms as $term) :
                    if (!$term || is_wp_error($term)) continue;

                    $url        = get_term_link($term);
                    $thumb_id   = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
                    $thumb_url  = $thumb_id
                        ? wp_get_attachment_image_url($thumb_id, 'zztheme-card')
                        : wc_placeholder_img_src('zztheme-card');
                    $count_text = (int) $term->count;
                ?>
                <div class="carousel-slide carousel-slide--cat">
                    <a href="<?php echo esc_url(is_wp_error($url) ? '#' : $url); ?>" class="cat-card">
                        <div class="cat-card__img">
                            <img src="<?php echo esc_url($thumb_url); ?>"
                                 alt="<?php echo esc_attr($term->name); ?>"
                                 loading="lazy">
                        </div>
                        <div class="cat-card__body">
                            <span class="cat-card__name"><?php echo esc_html($term->name); ?></span>
                            <span class="cat-card__count"><?php echo esc_html($count_text); ?></span>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
