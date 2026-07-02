<?php
/**
 * Integración nativa con WooCommerce. Sin parches invasivos.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce', [
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ],
    ]);
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
});

/* Single product: quitamos hooks renderizados manualmente en content-single-product.php.
 * Dejamos el action disparable para que plugins (ej. WC Rewards p.15) sigan funcionando. */
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

/* Wrappers del contenido WooCommerce. */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', function () {
    echo '<main id="primary" class="site-main wc-main"><div class="container">';
}, 10);

add_action('woocommerce_after_main_content', function () {
    echo '</div></main>';
}, 10);

/* Quitar el sidebar por defecto de WC: lo gestiona el tema. */
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

/* Productos por página y columnas de grid. */
add_filter('loop_shop_per_page', fn() => 12, 20);
add_filter('loop_shop_columns', fn() => 3, 20);

/* Badge de oferta con porcentaje de descuento. */
add_filter('woocommerce_sale_flash', function (string $html, WP_Post $post, WC_Product $product): string {
    if ($product->is_type('variable')) {
        $percentages = [];
        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if ($variation && $variation->is_on_sale()) {
                $regular = (float) $variation->get_regular_price();
                $sale    = (float) $variation->get_sale_price();
                if ($regular > 0) {
                    $percentages[] = round((($regular - $sale) / $regular) * 100);
                }
            }
        }
        $percent = !empty($percentages) ? max($percentages) : 0;
    } else {
        $regular = (float) $product->get_regular_price();
        $sale    = (float) $product->get_sale_price();
        $percent = ($regular > 0 && $sale > 0) ? round((($regular - $sale) / $regular) * 100) : 0;
    }

    if ($percent > 0) {
        return '<span class="onsale">' . esc_html__('Oferta', 'zztheme') . ' ' . $percent . '%</span>';
    }
    return '<span class="onsale">' . esc_html__('Oferta', 'zztheme') . '</span>';
}, 10, 3);

/* Cupón en el order review, antes del botón de pago. */
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
add_action('woocommerce_review_order_before_payment', function () {
    ?>
    <div class="checkout-coupon-inline">
        <button type="button" class="checkout-coupon-toggle" id="coupon-toggle">
            <?php esc_html_e('¿Tienes un cupón de descuento?', 'zztheme'); ?>
        </button>
        <div class="checkout-coupon-field" id="coupon-field" hidden>
            <div class="checkout-coupon-row">
                <input type="text" name="coupon_code" class="input-text" id="checkout_coupon_code"
                       placeholder="<?php esc_attr_e('Código del cupón', 'zztheme'); ?>" value="">
                <button type="button" class="button checkout-coupon-apply" id="checkout_coupon_apply">
                    <?php esc_html_e('Aplicar', 'zztheme'); ?>
                </button>
            </div>
        </div>
    </div>
    <?php
});

/* Ocultar estrellas en productos sin valoraciones. */
add_filter('woocommerce_product_get_rating_html', function (string $html, float $rating): string {
    if ($rating <= 0) {
        return '';
    }
    return $html;
}, 10, 2);

/* Productos relacionados: 4 productos en 1 columna. */
add_filter('woocommerce_output_related_products_args', function (array $args): array {
    $args['posts_per_page'] = 4;
    $args['columns']        = 4;
    return $args;
});

/* Actualizar contador del carrito en el header vía WC fragments. */
add_filter('woocommerce_add_to_cart_fragments', function (array $fragments): array {
    $count = WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
    $fragments['.header-cart-count'] = '<span class="header-action__count header-cart-count">' . $count . '</span>';
    return $fragments;
});

/* Eliminar el <a> abierto/cerrado que WC inyecta en loops. */
add_action('wp_loaded', function () {
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
});

