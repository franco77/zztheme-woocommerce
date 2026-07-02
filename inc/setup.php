<?php
/**
 * Registro de capacidades del tema, menús, sidebars, image sizes.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    load_theme_textdomain('zztheme', ZZTHEME_DIR . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('wp-block-styles');

    register_nav_menus([
        'primary' => __('Menú principal', 'zztheme'),
        'footer'  => __('Menú del pie', 'zztheme'),
        'mobile'  => __('Menú mobile', 'zztheme'),
    ]);

    add_image_size('zztheme-card', 600, 600, true);
    add_image_size('zztheme-hero', 1920, 800, true);
});

add_action('widgets_init', function () {
    $defaults = [
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar(array_merge($defaults, [
        'name' => __('Barra lateral principal', 'zztheme'),
        'id'   => 'sidebar-1',
    ]));

    register_sidebar(array_merge($defaults, [
        'name'        => __('Sidebar de la Tienda', 'zztheme'),
        'id'          => 'shop-sidebar',
        'description' => __('Aparece en shop, categorías y búsqueda de productos. Añade filtros WooCommerce aquí.', 'zztheme'),
    ]));

    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array_merge($defaults, [
            'name' => sprintf(__('Pie de página %d', 'zztheme'), $i),
            'id'   => 'footer-' . $i,
        ]));
    }
});

/* Declarar compatibilidad con HPOS y bloques de Cart/Checkout de WooCommerce. */
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
    }
});
