<?php
/**
 * Producto individual — delega a WooCommerce.
 * El layout personalizado está en woocommerce/content-single-product.php
 */

get_header();

/**
 * woocommerce_before_main_content / woocommerce_after_main_content no se
 * disparan cuando llamamos wc_get_template_part() directamente, así que
 * añadimos el mismo wrapper de container que usan las demás páginas WC.
 */
echo '<main id="primary" class="site-main wc-main">';

while (have_posts()) :
    the_post();
    wc_get_template_part('content', 'single-product');
endwhile;

echo '</main>';

get_footer();
