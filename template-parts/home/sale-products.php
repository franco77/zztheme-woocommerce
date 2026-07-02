<?php
/**
 * Sección de productos en oferta en la homepage.
 */

if (!class_exists('WooCommerce')) {
    return;
}

$title    = (string) get_theme_mod('zz_sale_title', __('Ofertas Especiales', 'zztheme'));
$count    = max(6, (int) get_theme_mod('zz_sale_count', 6));
$sale_ids = wc_get_product_ids_on_sale();

if (empty($sale_ids)) {
    return;
}

$loop = new WP_Query([
    'post_type'           => 'product',
    'post__in'            => $sale_ids,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'posts_per_page'      => $count,
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
]);

if (!$loop->have_posts()) {
    return;
}
?>
<section class="home-section" style="background-color: var(--zz-surface);">
    <div class="container">
        <div class="section-header section-header--center">
            <h2><?php echo esc_html($title); ?></h2>
        </div>

        <?php
        wc_set_loop_prop('columns', 3);
        echo '<div class="woocommerce">';
        woocommerce_product_loop_start();
        while ($loop->have_posts()) :
            $loop->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        woocommerce_product_loop_end();
        echo '</div>';
        wp_reset_postdata();
        ?>
    </div>
</section>
