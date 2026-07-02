<?php
/**
 * Tarjeta de producto en loops de WooCommerce.
 *
 * Controla todos los enlaces manualmente para evitar los <a></a> sueltos
 * que WC inyecta vía woocommerce_before/after_shop_loop_item.
 *
 * @see woocommerce/templates/content-product.php
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}

$cats = wc_get_product_category_list($product->get_id(), ', ');
?>
<li <?php wc_product_class('product-card', $product); ?>>

    <div class="product-card__thumb">
        <a class="product-card__image-link" href="<?php the_permalink(); ?>"
           aria-label="<?php echo esc_attr(get_the_title()); ?>">
            <?php
            /**
             * Sale flash + thumbnail.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </a>
        <button class="wishlist-btn"
                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                aria-label="<?php esc_attr_e('Agregar a lista de deseos', 'zztheme'); ?>">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </button>
    </div>

    <div class="product-card__body">
        <?php if ($cats) : ?>
            <span class="loop-product__cat"><?php echo wp_kses_post($cats); ?></span>
        <?php endif; ?>

        <a class="product-card__title-link" href="<?php the_permalink(); ?>">
            <?php
            /**
             * @hooked woocommerce_template_loop_product_title - 10
             */
            do_action('woocommerce_shop_loop_item_title');
            ?>
        </a>

        <?php
        /**
         * Rating + precio.
         *
         * @hooked woocommerce_template_loop_rating - 5
         * @hooked woocommerce_template_loop_price  - 10
         */
        do_action('woocommerce_after_shop_loop_item_title');
        ?>
    </div>

    <div class="product-card__footer">
        <?php woocommerce_template_loop_add_to_cart(); ?>
    </div>

</li>
