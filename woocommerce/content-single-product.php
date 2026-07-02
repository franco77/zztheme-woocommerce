<?php
/**
 * Producto individual — layout personalizado.
 * Galería izquierda + resumen derecho.
 *
 * @see woocommerce/templates/content-single-product.php
 */

defined('ABSPATH') || exit;

global $product;

if (post_password_required()) {
    echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    return;
}

/* Calcular porcentaje de descuento */
$regular = (float) $product->get_regular_price();
$sale    = (float) $product->get_sale_price();
$percent = ($product->is_on_sale() && $regular > 0 && $sale > 0)
    ? round((($regular - $sale) / $regular) * 100)
    : 0;

if ($product->is_type('variable') && $product->is_on_sale()) {
    $percentages = [];
    foreach ($product->get_children() as $child_id) {
        $v = wc_get_product($child_id);
        if ($v && $v->is_on_sale()) {
            $r = (float) $v->get_regular_price();
            $s = (float) $v->get_sale_price();
            if ($r > 0) { $percentages[] = round((($r - $s) / $r) * 100); }
        }
    }
    $percent = !empty($percentages) ? max($percentages) : 0;
}
?>

<?php get_template_part('template-parts/content/page-header'); ?>

<div class="container">
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('sp', $product); ?>>

    <div class="sp__gallery">
        <?php
        /**
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action('woocommerce_before_single_product_summary');
        ?>
    </div>

    <div class="sp__summary">
        <?php if ($product->is_on_sale() && $percent > 0) : ?>
            <span class="sp__badge"><?php printf(esc_html__('Oferta %d%%', 'zztheme'), $percent); ?></span>
        <?php elseif ($product->is_on_sale()) : ?>
            <span class="sp__badge"><?php esc_html_e('Oferta', 'zztheme'); ?></span>
        <?php endif; ?>

        <h1 class="sp__title"><?php the_title(); ?></h1>

        <div class="sp__meta">
            <?php if ($product->get_average_rating() > 0) : ?>
                <div class="sp__rating">
                    <?php woocommerce_template_single_rating(); ?>
                </div>
            <?php endif; ?>

            <?php if ($sku = $product->get_sku()) : ?>
                <span class="sp__sku"><?php esc_html_e('SKU:', 'zztheme'); ?> <strong><?php echo esc_html($sku); ?></strong></span>
            <?php endif; ?>

            <?php if ($product->is_in_stock()) : ?>
                <span class="sp__stock sp__stock--in">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <?php esc_html_e('En stock', 'zztheme'); ?>
                </span>
            <?php else : ?>
                <span class="sp__stock sp__stock--out"><?php esc_html_e('Agotado', 'zztheme'); ?></span>
            <?php endif; ?>
        </div>

        <div class="sp__price">
            <?php woocommerce_template_single_price(); ?>
        </div>

        <?php
        /**
         * Dispara hooks de plugins registrados en woocommerce_single_product_summary.
         * Los hooks de WC por defecto (title, price, excerpt, add_to_cart, sharing, meta)
         * ya fueron removidos en inc/woocommerce.php — solo disparan plugins externos.
         * Ej: WC Rewards muestra el chip de puntos a ganar (prioridad 15).
         */
        do_action('woocommerce_single_product_summary');
        ?>

        <?php if ($product->get_short_description()) : ?>
            <div class="sp__excerpt">
                <?php woocommerce_template_single_excerpt(); ?>
            </div>
        <?php endif; ?>

        <div class="sp__add-to-cart">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>

        <button class="wishlist-btn wishlist-btn--sp"
                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                aria-label="<?php esc_attr_e('Agregar a lista de deseos', 'zztheme'); ?>">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <span class="wishlist-btn__text"><?php esc_html_e('Agregar a lista de deseos', 'zztheme'); ?></span>
        </button>

        <ul class="sp__benefits">
            <?php
            $benefits = [
                get_theme_mod('zz_sp_benefit1', __('Garantía en todos los productos', 'zztheme')),
                get_theme_mod('zz_sp_benefit2', __('Tiempo de entrega: 1-3 días hábiles', 'zztheme')),
                get_theme_mod('zz_sp_benefit3', __('Devolución gratuita en 30 días', 'zztheme')),
            ];
            foreach ($benefits as $b) :
                if ($b) : ?>
                    <li><?php echo esc_html($b); ?></li>
                <?php endif;
            endforeach; ?>
        </ul>

        <?php
        $sp_phone = zztheme_contact('phone');
        $sp_wa    = zztheme_contact('whatsapp');
        $sp_tel   = $sp_phone ?: $sp_wa;
        if ($sp_tel) : ?>
        <div class="sp__support">
            <div class="sp__support-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.07 10.4 19.79 19.79 0 0 1 1 1.82 2 2 0 0 1 2.96 0h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 7.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 15l.92 1.92z"/>
                </svg>
            </div>
            <div class="sp__support-body">
                <p class="sp__support-label"><?php esc_html_e('Nuestro representante está esperando por ti.', 'zztheme'); ?></p>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $sp_tel)); ?>" class="sp__support-phone">
                    <?php echo esc_html($sp_tel); ?>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <?php
        $share_url   = urlencode(get_permalink());
        $share_title = urlencode(get_the_title());
        ?>
        <div class="sp__share">
            <span class="sp__share-label"><?php esc_html_e('Compartir:', 'zztheme'); ?></span>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="sp__share-btn" aria-label="Facebook">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" class="sp__share-btn" aria-label="Twitter/X">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M4 4l16 16M4 20 20 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&description=<?php echo $share_title; ?>" target="_blank" rel="noopener noreferrer" class="sp__share-btn" aria-label="Pinterest">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.24 2.65 7.86 6.39 9.29-.09-.78-.17-1.98.03-2.83.19-.78 1.27-5.35 1.27-5.35s-.32-.65-.32-1.61c0-1.51.88-2.64 1.97-2.64.93 0 1.38.7 1.38 1.54 0 .94-.6 2.34-.91 3.64-.26 1.09.54 1.97 1.61 1.97 1.93 0 3.42-2.04 3.42-4.97 0-2.6-1.87-4.42-4.53-4.42-3.09 0-4.9 2.31-4.9 4.7 0 .93.36 1.93.81 2.48.09.11.1.2.07.31-.08.34-.26 1.09-.3 1.24-.05.2-.17.24-.38.14-1.39-.65-2.26-2.68-2.26-4.32 0-3.51 2.55-6.74 7.36-6.74 3.86 0 6.86 2.75 6.86 6.42 0 3.83-2.41 6.9-5.76 6.9-1.13 0-2.19-.59-2.55-1.28l-.69 2.59c-.25.97-.93 2.18-1.39 2.92.05.02.1.03.15.04C7.85 21.91 9.88 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>
            </a>
            <?php if ($sp_wa) : ?>
            <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^+\d]/', '', $sp_wa)); ?>?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" target="_blank" rel="noopener noreferrer" class="sp__share-btn" aria-label="WhatsApp">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.046 22l4.956-1.379A9.952 9.952 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18a7.95 7.95 0 0 1-4.054-1.112l-.29-.173-3.02.841.854-2.951-.19-.303A7.954 7.954 0 0 1 4 12c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z"/></svg>
            </a>
            <?php endif; ?>
        </div>

        <div class="sp__taxonomies">
            <?php
            $cat_list = wc_get_product_category_list($product->get_id(), ', ');
            $tag_list = wc_get_product_tag_list($product->get_id(), ', ');
            if ($cat_list) : ?>
                <span class="sp__tax"><span class="sp__tax-label"><?php esc_html_e('Categoría:', 'zztheme'); ?></span> <?php echo wp_kses_post($cat_list); ?></span>
            <?php endif;
            if ($tag_list) : ?>
                <span class="sp__tax"><span class="sp__tax-label"><?php esc_html_e('Etiquetas:', 'zztheme'); ?></span> <?php echo wp_kses_post($tag_list); ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="sp__tabs">
        <?php
        /**
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */
        do_action('woocommerce_after_single_product_summary');
        ?>
    </div>

</div>
</div><!-- /.container -->
