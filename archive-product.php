<?php
/**
 * Página de tienda y archivos de productos WooCommerce.
 * Layout: sidebar izquierdo + grid de productos.
 *
 * No usa woocommerce_content() para evitar que los hooks
 * woocommerce_before/after_main_content inyecten un <main><div class="container">
 * anidado dentro del wc-layout, lo que rompía el grid.
 */

get_header();
?>
<main id="primary" class="site-main wc-archive">

    <?php get_template_part('template-parts/content/page-header'); ?>

    <div class="container">
        <div class="wc-layout">

            <!-- Sidebar de filtros -->
            <aside class="wc-sidebar">
                <?php if (is_active_sidebar('shop-sidebar')) : ?>
                    <?php dynamic_sidebar('shop-sidebar'); ?>
                <?php else : ?>
                    <?php zztheme_render_default_shop_sidebar(); ?>
                <?php endif; ?>
            </aside>

            <!-- Contenido: productos -->
            <div class="wc-content">

                <!-- Toggle sidebar mobile -->
                <button class="wc-sidebar-toggle button button--outline">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                    <?php esc_html_e('Filtros', 'zztheme'); ?>
                </button>

                <?php
                if (woocommerce_product_loop()) :
                    /**
                     * @hooked woocommerce_output_all_notices - 10
                     * @hooked woocommerce_result_count       - 20
                     * @hooked woocommerce_catalog_ordering   - 30
                     */
                    do_action('woocommerce_before_shop_loop');

                    woocommerce_product_loop_start();

                    while (have_posts()) :
                        the_post();
                        do_action('woocommerce_shop_loop');
                        wc_get_template_part('content', 'product');
                    endwhile;

                    woocommerce_product_loop_end();

                    /**
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');

                else :
                    do_action('woocommerce_no_products_found');
                endif;
                ?>

            </div><!-- .wc-content -->

        </div><!-- .wc-layout -->
    </div><!-- .container -->

</main>
<?php
get_footer();
