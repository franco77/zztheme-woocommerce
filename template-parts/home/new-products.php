<?php
/**
 * Sección de productos recién llegados en la homepage — carrusel.
 */

if (!class_exists('WooCommerce')) {
    return;
}

$title = (string) get_theme_mod('zz_new_title', __('Recién Llegados', 'zztheme'));
$count = max(4, (int) get_theme_mod('zz_new_count', 8));
$days  = max(0, (int) get_theme_mod('zz_new_days', 0));

$query_args = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $count,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => true,
    'tax_query'           => [
        [
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => ['exclude-from-catalog'],
            'operator' => 'NOT IN',
        ],
    ],
];

if ($days > 0) {
    $query_args['date_query'] = [
        ['after' => $days . ' days ago', 'inclusive' => true],
    ];
}

$loop = new WP_Query($query_args);

if (!$loop->have_posts()) {
    return;
}
?>
<section class="home-section home-section--carousel">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-eyebrow"><?php esc_html_e('Novedades', 'zztheme'); ?></span>
                <h2><?php echo esc_html($title); ?></h2>
            </div>
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
            <div class="carousel-track" data-carousel="new-products">
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
