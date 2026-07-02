<?php
/**
 * Mini carrito — sólo enlace con contador.
 * WooCommerce actualiza el conteo via fragments AJAX automáticamente.
 */

if (!class_exists('WooCommerce') || !WC()->cart) {
    return;
}
?>
<a class="header-action header-action--cart" href="<?php echo esc_url(wc_get_cart_url()); ?>"
   aria-label="<?php esc_attr_e('Ver carrito', 'zztheme'); ?>">
    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
        <line x1="3" y1="6" x2="21" y2="6"/>
        <path d="M16 10a4 4 0 0 1-8 0"/>
    </svg>
    <span class="header-action__count"><?php echo (int) WC()->cart->get_cart_contents_count(); ?></span>
    <span class="header-action__label"><?php esc_html_e('Carrito', 'zztheme'); ?></span>
</a>
