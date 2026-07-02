<?php
/**
 * Template Name: Lista de Deseos
 *
 * Crea una página en WordPress y asígnale esta plantilla.
 */

get_header();
?>
<main id="primary" class="site-main">

    <?php get_template_part('template-parts/content/page-header'); ?>

    <div class="container wishlist-page">

        <div id="wishlist-container"
             data-nonce="<?php echo esc_attr(wp_create_nonce('zz_wishlist')); ?>"
             data-ajaxurl="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
             data-shop="<?php echo esc_url(class_exists('WooCommerce') ? get_permalink(wc_get_page_id('shop')) : home_url('/')); ?>">

            <div class="wishlist-loading" aria-label="<?php esc_attr_e('Cargando lista de deseos', 'zztheme'); ?>">
                <span class="live-search-spinner"></span>
            </div>

        </div>

    </div>

</main>
<?php
get_footer();
