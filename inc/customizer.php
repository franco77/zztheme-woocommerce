<?php
/**
 * Opciones del Customizer: Hero, Features, Productos, Colores, Contacto, Social.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('customize_register', function (WP_Customize_Manager $wp_customize) {

    /* ── Panel Home ─────────────────────────────────────────────────── */
    $wp_customize->add_panel('zz_home_panel', [
        'title'    => __('Página de Inicio', 'zztheme'),
        'priority' => 30,
    ]);

    /* ── Sección Hero ───────────────────────────────────────────────── */
    $wp_customize->add_section('zz_hero', [
        'title' => __('Banner Principal (Hero)', 'zztheme'),
        'panel' => 'zz_home_panel',
    ]);

    $hero_fields = [
        'zz_hero_title'    => ['label' => __('Título', 'zztheme'),    'default' => __('Repuestos de Calidad', 'zztheme')],
        'zz_hero_subtitle' => ['label' => __('Subtítulo', 'zztheme'), 'default' => __('Encuentra todo lo que necesitas para tu vehículo', 'zztheme')],
        'zz_hero_cta_text' => ['label' => __('Texto del botón', 'zztheme'), 'default' => __('Ver catálogo', 'zztheme')],
        'zz_hero_cta_url'  => ['label' => __('URL del botón', 'zztheme'), 'default' => ''],
    ];

    $priority = 10;
    foreach ($hero_fields as $id => $args) {
        $wp_customize->add_setting($id, [
            'default'           => $args['default'],
            'sanitize_callback' => $id === 'zz_hero_cta_url' ? 'esc_url_raw' : 'sanitize_text_field',
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control($id, [
            'label'    => $args['label'],
            'section'  => 'zz_hero',
            'priority' => $priority,
        ]);
        $priority += 10;
    }

    $wp_customize->add_setting('zz_hero_image', [
        'default'           => '',
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'zz_hero_image', [
        'label'     => __('Imagen de fondo del hero', 'zztheme'),
        'section'   => 'zz_hero',
        'mime_type' => 'image',
        'priority'  => 50,
    ]));

    /* ── Sección Features ───────────────────────────────────────────── */
    $wp_customize->add_section('zz_features', [
        'title' => __('Bloques Informativos', 'zztheme'),
        'panel' => 'zz_home_panel',
    ]);

    $features_defaults = [
        1 => ['title' => __('Productos Originales', 'zztheme'),  'desc' => __('Solo trabajamos con marcas certificadas', 'zztheme')],
        2 => ['title' => __('Precios Accesibles', 'zztheme'),    'desc' => __('Los mejores precios del mercado garantizados', 'zztheme')],
        3 => ['title' => __('Gran Variedad', 'zztheme'),          'desc' => __('Más de 10.000 referencias disponibles', 'zztheme')],
    ];

    $priority = 10;
    foreach ($features_defaults as $i => $defaults) {
        $wp_customize->add_setting("zz_feature_{$i}_title", [
            'default'           => $defaults['title'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control("zz_feature_{$i}_title", [
            'label'    => sprintf(__('Feature %d — Título', 'zztheme'), $i),
            'section'  => 'zz_features',
            'priority' => $priority,
        ]);

        $wp_customize->add_setting("zz_feature_{$i}_desc", [
            'default'           => $defaults['desc'],
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        ]);
        $wp_customize->add_control("zz_feature_{$i}_desc", [
            'label'    => sprintf(__('Feature %d — Descripción', 'zztheme'), $i),
            'section'  => 'zz_features',
            'priority' => $priority + 5,
        ]);

        $priority += 20;
    }

    /* ── Sección Productos Destacados ───────────────────────────────── */
    /* ── Banners publicitarios ─────────────────────────────────────────────── */
    $wp_customize->add_section('zz_banners', [
        'title'    => __('Banners Publicitarios', 'zztheme'),
        'panel'    => 'zz_home_panel',
        'priority' => 22,
    ]);

    $wp_customize->add_setting('zz_banners_title', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('zz_banners_title', [
        'label'       => __('Título de la sección (opcional)', 'zztheme'),
        'section'     => 'zz_banners',
        'priority'    => 5,
    ]);

    $wp_customize->add_setting('zz_banners_columns', [
        'default'           => '2',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('zz_banners_columns', [
        'label'   => __('Banners visibles a la vez', 'zztheme'),
        'section' => 'zz_banners',
        'type'    => 'select',
        'choices' => [
            '2' => __('2 banners', 'zztheme'),
            '3' => __('3 banners', 'zztheme'),
        ],
        'priority' => 6,
    ]);

    for ($i = 1; $i <= 6; $i++) {
        $priority = 10 + ($i - 1) * 20;

        $wp_customize->add_setting("zz_banner_{$i}_title", [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("zz_banner_{$i}_title", [
            'label'    => sprintf(__('Banner %d — Título', 'zztheme'), $i),
            'description' => __('Dejar vacío para ocultar este banner.', 'zztheme'),
            'section'  => 'zz_banners',
            'priority' => $priority,
        ]);

        $wp_customize->add_setting("zz_banner_{$i}_label", [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("zz_banner_{$i}_label", [
            'label'    => sprintf(__('Banner %d — Etiqueta (texto pequeño)', 'zztheme'), $i),
            'section'  => 'zz_banners',
            'priority' => $priority + 2,
        ]);

        $wp_customize->add_setting("zz_banner_{$i}_subtitle", [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("zz_banner_{$i}_subtitle", [
            'label'    => sprintf(__('Banner %d — Subtítulo', 'zztheme'), $i),
            'section'  => 'zz_banners',
            'priority' => $priority + 4,
        ]);

        $wp_customize->add_setting("zz_banner_{$i}_cta_text", [
            'default'           => __('Ver más', 'zztheme'),
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("zz_banner_{$i}_cta_text", [
            'label'    => sprintf(__('Banner %d — Texto del botón', 'zztheme'), $i),
            'section'  => 'zz_banners',
            'priority' => $priority + 6,
        ]);

        $wp_customize->add_setting("zz_banner_{$i}_cta_url", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control("zz_banner_{$i}_cta_url", [
            'label'    => sprintf(__('Banner %d — URL del botón', 'zztheme'), $i),
            'section'  => 'zz_banners',
            'type'     => 'url',
            'priority' => $priority + 8,
        ]);

        $wp_customize->add_setting("zz_banner_{$i}_image", [
            'default'           => '',
            'sanitize_callback' => 'absint',
        ]);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "zz_banner_{$i}_image", [
            'label'     => sprintf(__('Banner %d — Imagen de producto', 'zztheme'), $i),
            'section'   => 'zz_banners',
            'mime_type' => 'image',
            'priority'  => $priority + 10,
        ]));

        $wp_customize->add_setting("zz_banner_{$i}_light_bg", [
            'default'           => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        $wp_customize->add_control("zz_banner_{$i}_light_bg", [
            'label'    => sprintf(__('Banner %d — Fondo claro (texto oscuro)', 'zztheme'), $i),
            'section'  => 'zz_banners',
            'type'     => 'checkbox',
            'priority' => $priority + 12,
        ]);
    }

    /* ── Sección Recién Llegados ────────────────────────────────────────── */
    $wp_customize->add_section('zz_new', [
        'title'    => __('Recién Llegados', 'zztheme'),
        'panel'    => 'zz_home_panel',
        'priority' => 23,
    ]);

    $wp_customize->add_setting('zz_new_title', [
        'default'           => __('Recién Llegados', 'zztheme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('zz_new_title', [
        'label'   => __('Título de la sección', 'zztheme'),
        'section' => 'zz_new',
        'priority' => 10,
    ]);

    $wp_customize->add_setting('zz_new_count', [
        'default'           => 8,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('zz_new_count', [
        'label'       => __('Cantidad de productos a mostrar', 'zztheme'),
        'description' => __('Múltiplos de 4 recomendados (4, 8, 12…).', 'zztheme'),
        'section'     => 'zz_new',
        'type'        => 'number',
        'input_attrs' => ['min' => 4, 'max' => 24, 'step' => 4],
        'priority'    => 20,
    ]);

    $wp_customize->add_setting('zz_new_days', [
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('zz_new_days', [
        'label'       => __('Filtrar por días recientes', 'zztheme'),
        'description' => __('Mostrar solo productos publicados en los últimos N días. 0 = sin límite de fecha (los más nuevos de siempre).', 'zztheme'),
        'section'     => 'zz_new',
        'type'        => 'number',
        'input_attrs' => ['min' => 0, 'max' => 365],
        'priority'    => 30,
    ]);

    /* ── Carrusel de Categorías ─────────────────────────────────────────── */
    $wp_customize->add_section('zz_catgrid', [
        'title'    => __('Carrusel de Categorías', 'zztheme'),
        'panel'    => 'zz_home_panel',
        'priority' => 25,
    ]);

    $wp_customize->add_setting('zz_catgrid_title', [
        'default'           => __('Categorías', 'zztheme'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('zz_catgrid_title', [
        'label'   => __('Título de la sección', 'zztheme'),
        'section' => 'zz_catgrid',
    ]);

    $wp_customize->add_setting('zz_catgrid_mode', [
        'default'           => 'auto',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('zz_catgrid_mode', [
        'label'   => __('Modo de selección', 'zztheme'),
        'section' => 'zz_catgrid',
        'type'    => 'select',
        'choices' => [
            'auto'   => __('Automático (todas las categorías)', 'zztheme'),
            'manual' => __('Manual (IDs personalizados)', 'zztheme'),
        ],
    ]);

    $wp_customize->add_setting('zz_catgrid_count', [
        'default'           => 8,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('zz_catgrid_count', [
        'label'       => __('Cantidad máxima (modo automático)', 'zztheme'),
        'section'     => 'zz_catgrid',
        'type'        => 'number',
        'input_attrs' => ['min' => 1, 'max' => 20],
    ]);

    $wp_customize->add_setting('zz_catgrid_ids', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('zz_catgrid_ids', [
        'label'       => __('IDs de categorías (modo manual)', 'zztheme'),
        'description' => __('IDs separados por coma, ej: 12,34,56. Ver IDs en Productos → Categorías.', 'zztheme'),
        'section'     => 'zz_catgrid',
        'type'        => 'text',
    ]);

    /* ── Productos Destacados ───────────────────────────────────────────── */
    $wp_customize->add_section('zz_featured', [
        'title' => __('Productos Destacados', 'zztheme'),
        'panel' => 'zz_home_panel',
    ]);

    $wp_customize->add_setting('zz_featured_title', [
        'default'           => __('Productos Destacados', 'zztheme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('zz_featured_title', [
        'label'   => __('Título de la sección', 'zztheme'),
        'section' => 'zz_featured',
    ]);

    $wp_customize->add_setting('zz_featured_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('zz_featured_count', [
        'label'       => __('Cantidad de productos a mostrar', 'zztheme'),
        'description' => __('Múltiplos de 3 recomendados (3, 6, 9, 12…) para filas completas.', 'zztheme'),
        'section'     => 'zz_featured',
        'type'        => 'number',
        'input_attrs' => ['min' => 3, 'max' => 24, 'step' => 3],
    ]);

    /* ── Sección Productos en Oferta ────────────────────────────────── */
    $wp_customize->add_section('zz_sale', [
        'title' => __('Productos en Oferta', 'zztheme'),
        'panel' => 'zz_home_panel',
    ]);

    $wp_customize->add_setting('zz_sale_title', [
        'default'           => __('Ofertas Especiales', 'zztheme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('zz_sale_title', [
        'label'   => __('Título de la sección', 'zztheme'),
        'section' => 'zz_sale',
    ]);

    $wp_customize->add_setting('zz_sale_count', [
        'default'           => 6,
        'sanitize_callback' => 'absint',
    ]);
    $wp_customize->add_control('zz_sale_count', [
        'label'       => __('Cantidad de productos a mostrar', 'zztheme'),
        'description' => __('Múltiplos de 3 recomendados (3, 6, 9, 12…) para filas completas.', 'zztheme'),
        'section'     => 'zz_sale',
        'type'        => 'number',
        'input_attrs' => ['min' => 3, 'max' => 24, 'step' => 3],
    ]);

    /* ── Sección Newsletter ─────────────────────────────────────────── */
    $wp_customize->add_section('zz_newsletter', [
        'title' => __('Newsletter', 'zztheme'),
        'panel' => 'zz_home_panel',
    ]);

    $wp_customize->add_setting('zz_newsletter_enabled', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('zz_newsletter_enabled', [
        'label'   => __('Mostrar sección newsletter', 'zztheme'),
        'section' => 'zz_newsletter',
        'type'    => 'checkbox',
    ]);

    $wp_customize->add_setting('zz_newsletter_title', [
        'default'           => __('¿Quieres recibir las mejores ofertas?', 'zztheme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('zz_newsletter_title', [
        'label'   => __('Título', 'zztheme'),
        'section' => 'zz_newsletter',
    ]);

    $wp_customize->add_setting('zz_newsletter_subtitle', [
        'default'           => __('Suscríbete y recibe descuentos exclusivos', 'zztheme'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control('zz_newsletter_subtitle', [
        'label'   => __('Subtítulo', 'zztheme'),
        'section' => 'zz_newsletter',
    ]);

    /* ── Datos de Contacto ──────────────────────────────────────────── */
    $wp_customize->add_section('zz_contact_section', [
        'title'    => __('Datos de Contacto', 'zztheme'),
        'priority' => 35,
    ]);

    $contact_fields = [
        'zz_contact_phone'   => __('Teléfono', 'zztheme'),
        'zz_contact_email'   => __('Email', 'zztheme'),
        'zz_contact_address' => __('Dirección', 'zztheme'),
        'zz_contact_whatsapp' => __('WhatsApp (número con código de país)', 'zztheme'),
    ];

    $priority = 10;
    foreach ($contact_fields as $id => $label) {
        $wp_customize->add_setting($id, [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control($id, [
            'label'    => $label,
            'section'  => 'zz_contact_section',
            'priority' => $priority,
        ]);
        $priority += 10;
    }

    $wp_customize->add_setting('zz_contact_intro', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    $wp_customize->add_control('zz_contact_intro', [
        'label'       => __('Texto introductorio (columna izquierda)', 'zztheme'),
        'description' => __('Aparece encima de los datos de contacto. Admite HTML básico.', 'zztheme'),
        'section'     => 'zz_contact_section',
        'type'        => 'textarea',
        'priority'    => 45,
    ]);

    $wp_customize->add_setting('zz_contact_maps_embed', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('zz_contact_maps_embed', [
        'label'       => __('URL embed Google Maps', 'zztheme'),
        'description' => __('URL del iframe de Google Maps (sólo la URL src del iframe)', 'zztheme'),
        'section'     => 'zz_contact_section',
        'priority'    => 50,
    ]);

    /* ── Redes Sociales ─────────────────────────────────────────────── */
    $wp_customize->add_section('zz_social_section', [
        'title'    => __('Redes Sociales', 'zztheme'),
        'priority' => 36,
    ]);

    $social_networks = [
        'zz_social_facebook'  => __('Facebook URL', 'zztheme'),
        'zz_social_instagram' => __('Instagram URL', 'zztheme'),
        'zz_social_whatsapp'  => __('WhatsApp URL', 'zztheme'),
        'zz_social_twitter'   => __('Twitter / X URL', 'zztheme'),
    ];

    $priority = 10;
    foreach ($social_networks as $id => $label) {
        $wp_customize->add_setting($id, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        $wp_customize->add_control($id, [
            'label'    => $label,
            'section'  => 'zz_social_section',
            'type'     => 'url',
            'priority' => $priority,
        ]);
        $priority += 10;
    }

    /* ── Colores ────────────────────────────────────────────────────── */
    $wp_customize->add_section('zz_colors_section', [
        'title'    => __('Colores del Tema', 'zztheme'),
        'priority' => 37,
    ]);

    $wp_customize->add_setting('zz_color_primary', [
        'default'           => '#3f82ef',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'zz_color_primary', [
        'label'   => __('Color primario', 'zztheme'),
        'section' => 'zz_colors_section',
    ]));

    $wp_customize->add_setting('zz_color_dark', [
        'default'           => '#1a2c4a',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'zz_color_dark', [
        'label'   => __('Color oscuro (header, footer)', 'zztheme'),
        'section' => 'zz_colors_section',
    ]));

    /* ── Header — estilo visual y badges ───────────────────────────── */
    $wp_customize->add_section('zz_header_section', [
        'title'       => __('Cabecera — Estilo y badges', 'zztheme'),
        'description' => __('Personaliza el aspecto visual de la barra de navegación principal.', 'zztheme'),
        'priority'    => 38,
    ]);

    /* Esquema de color del nav */
    $wp_customize->add_setting('zz_nav_style', [
        'default'           => 'dark',
        'sanitize_callback' => function (string $value): string {
            return in_array($value, ['dark', 'light'], true) ? $value : 'dark';
        },
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('zz_nav_style', [
        'label'       => __('Esquema de color — barra de navegación', 'zztheme'),
        'description' => __('Oscuro: fondo navy con textos blancos. Claro: fondo blanco con textos y botones en el color primario del sitio.', 'zztheme'),
        'section'     => 'zz_header_section',
        'type'        => 'radio',
        'priority'    => 5,
        'choices'     => [
            'dark'  => __('Oscuro (navy)', 'zztheme'),
            'light' => __('Claro (blanco)', 'zztheme'),
        ],
    ]);

    /* Badges */
    for ($i = 1; $i <= 2; $i++) {
        $wp_customize->add_setting("zz_header_badge{$i}", [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("zz_header_badge{$i}", [
            'label'    => sprintf(__('Badge %d (texto corto)', 'zztheme'), $i),
            'section'  => 'zz_header_section',
            'priority' => 10 + $i,
        ]);
    }
});
