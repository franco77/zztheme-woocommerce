<?php
/**
 * Banner interior de páginas: título + breadcrumb.
 */

/* Determinar título de la página */
if (is_shop()) {
    $title = get_the_title(wc_get_page_id('shop'));
} elseif (is_product_category()) {
    $title = single_cat_title('', false);
} elseif (is_product_tag()) {
    $title = single_tag_title('', false);
} elseif (is_tax()) {
    $title = single_term_title('', false);
} elseif (is_singular()) {
    $title = get_the_title();
} elseif (is_search()) {
    $title = sprintf(esc_html__('Resultados para: "%s"', 'zztheme'), get_search_query());
} elseif (is_404()) {
    $title = esc_html__('Página no encontrada', 'zztheme');
} else {
    $title = get_the_archive_title();
}
?>
<div class="page-hero">
    <div class="container page-hero__content">
        <h1 class="page-hero__title"><?php echo esc_html($title); ?></h1>

        <nav class="breadcrumb" aria-label="<?php esc_attr_e('Ruta de navegación', 'zztheme'); ?>">
            <?php
            if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout())) {
                woocommerce_breadcrumb([
                    'delimiter'   => '<span class="bc-sep" aria-hidden="true">/</span>',
                    'wrap_before' => '',
                    'wrap_after'  => '',
                ]);
            } else {
                echo wp_kses_post(zztheme_get_breadcrumb());
            }
            ?>
        </nav>
    </div>
</div>
