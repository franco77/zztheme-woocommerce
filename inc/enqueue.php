<?php
/**
 * Encolado de estilos y scripts.
 * Cadena: milligram → base → layout → components → woocommerce → dark-mode → main.js
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    $dir = ZZTHEME_DIR . '/assets/css/';
    $uri = ZZTHEME_URI . '/assets/css/';
    $ver = ZZTHEME_VERSION;

    /* Milligram: copia local con fallback CDN. */
    $milligram_local = $dir . 'milligram.min.css';
    $milligram_src   = file_exists($milligram_local)
        ? $uri . 'milligram.min.css'
        : 'https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css';

    wp_enqueue_style(
        'zztheme-milligram',
        $milligram_src,
        [],
        '1.4.1'
    );

    wp_enqueue_style(
        'zztheme-base',
        $uri . 'base.css',
        ['zztheme-milligram'],
        file_exists($dir . 'base.css') ? filemtime($dir . 'base.css') : $ver
    );

    wp_enqueue_style(
        'zztheme-layout',
        $uri . 'layout.css',
        ['zztheme-base'],
        file_exists($dir . 'layout.css') ? filemtime($dir . 'layout.css') : $ver
    );

    wp_enqueue_style(
        'zztheme-components',
        $uri . 'components.css',
        ['zztheme-layout'],
        file_exists($dir . 'components.css') ? filemtime($dir . 'components.css') : $ver
    );

    if (class_exists('WooCommerce')) {
        wp_enqueue_style(
            'zztheme-woocommerce',
            $uri . 'woocommerce.css',
            ['zztheme-components'],
            file_exists($dir . 'woocommerce.css') ? filemtime($dir . 'woocommerce.css') : $ver
        );
    }

    wp_enqueue_style(
        'zztheme-darkmode',
        $uri . 'dark-mode.css',
        ['zztheme-components'],
        file_exists($dir . 'dark-mode.css') ? filemtime($dir . 'dark-mode.css') : $ver
    );

    /* Script principal — en el footer, sin dependencias. */
    $js_path = ZZTHEME_DIR . '/assets/js/main.js';
    wp_enqueue_script(
        'zztheme-main',
        ZZTHEME_URI . '/assets/js/main.js',
        [],
        file_exists($js_path) ? filemtime($js_path) : $ver,
        true
    );

    wp_localize_script('zztheme-main', 'ZZTHEME', [
        'ajaxUrl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('zztheme'),
        'searchNonce'   => wp_create_nonce('zz_live_search'),
        'wishlistNonce' => wp_create_nonce('zz_wishlist'),
        'shopUrl'       => class_exists('WooCommerce') ? get_permalink(wc_get_page_id('shop')) : home_url('/shop/'),
        'searchLabel'   => __('Ver todos los resultados', 'zztheme'),
        'emptyLabel'    => __('No se encontraron productos', 'zztheme'),
        'loadingLabel'  => __('Buscando...', 'zztheme'),
        'wishlistEmpty' => __('Tu lista de deseos está vacía.', 'zztheme'),
        'wishlistShop'  => __('Explorar productos', 'zztheme'),
        'wishlistAdd'   => __('Agregar a lista de deseos', 'zztheme'),
        'wishlistRemove'=> __('Quitar de lista de deseos', 'zztheme'),
    ]);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 20);

/* Colores del Customizer como variables CSS inline. */
add_action('wp_enqueue_scripts', function () {
    $primary = sanitize_hex_color((string) get_theme_mod('zz_color_primary', '#3f82ef'));
    $dark    = sanitize_hex_color((string) get_theme_mod('zz_color_dark', '#1a2c4a'));

    if ($primary || $dark) {
        $css = ':root{';
        if ($primary) $css .= '--zz-primary:' . $primary . ';';
        if ($dark)    $css .= '--zz-dark:' . $dark . ';';
        $css .= '}';
        wp_add_inline_style('zztheme-base', $css);
    }
}, 30);

/* Eliminar ruido del <head> que ralentiza el frontend. */
add_action('init', function () {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
});

/* Quitar CSS de bloques no usados — ahorra ~30KB por request. */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
}, 100);
