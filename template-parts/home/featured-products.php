<?php
/**
 * Sección de productos destacados en la homepage — carrusel.
 */

if (!class_exists('WooCommerce')) {
    return;
}

$title = (string) get_theme_mod('zz_featured_title', __('Productos Destacados', 'zztheme'));
$count = max(6, (int) get_theme_mod('zz_featured_count', 6));
$ids   = wc_get_featured_product_ids();

if (empty($ids)) {
    return;
}

$loop = new WP_Query([
    'post_type'           => 'product',
    'post__in'            => array_slice($ids, 0, $count * 2),
    'orderby'             => 'post__in',
    'posts_per_page'      => $count,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
]);

if (!$loop->have_posts()) {
    return;
}
?>
<section class="home-section home-section--carousel">
    <div class="container">
        <div class="section-header">
            <h2><?php echo esc_html($title); ?></h2>
            <div class="carousel-nav" aria-label="<?php esc_attr_e('Navegación del carrusel', 'zztheme'); ?>">
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
            <div class="carousel-track" data-carousel="featured">
                <?php
                wc_set_loop_prop('columns', 4);
                while ($loop->have_posts()) :
                    $loop->the_post();
                    echo '<div class="carousel-slide">';
                    wc_get_template_part('content', 'product');
                    echo '</div>';
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</section>
