<?php
/**
 * Cabecera del sitio ZZTheme.
 * Fila 1 (topbar): datos de contacto
 * Fila 2 (main): Logo + Buscador + Acciones
 * Fila 3 (nav): Menú + Teléfono + Dark mode toggle
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <script>
    (function(){var m=localStorage.getItem('zztheme-dark-mode');if(m==='dark')document.documentElement.classList.add('dark-mode');else if(m==='light')document.documentElement.classList.add('light-mode');})();
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="header-sentinel"></div>
<div class="site-overlay" aria-hidden="true"></div>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Saltar al contenido', 'zztheme'); ?></a>

<header id="masthead" class="site-header">

    <!-- Topbar -->
    <?php get_template_part('template-parts/header/topbar'); ?>

    <!-- Fila principal: Logo + Buscador + Acciones -->
    <div class="site-header__main">
        <div class="container site-header__row">

            <!-- Branding -->
            <div class="site-branding">
                <?php zztheme_site_branding(); ?>
            </div>

            <?php if (class_exists('WooCommerce')) : ?>
                <!-- Botón categorías con dropdown -->
                <div class="header-cats">
                    <button class="header-categories-btn" aria-expanded="false" aria-haspopup="true">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                        <?php esc_html_e('Categorías', 'zztheme'); ?>
                        <svg class="header-cats__chevron" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>

                    <nav class="header-cats__panel" aria-label="<?php esc_attr_e('Categorías de productos', 'zztheme'); ?>">
                        <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="header-cats__all">
                            <?php esc_html_e('Ver todos los productos', 'zztheme'); ?>
                            <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                        <?php
                        $zz_top_cats = get_terms([
                            'taxonomy'   => 'product_cat',
                            'parent'     => 0,
                            'hide_empty' => true,
                            'orderby'    => 'name',
                            'order'      => 'ASC',
                            'number'     => 40,
                        ]);
                        if (!empty($zz_top_cats) && !is_wp_error($zz_top_cats)) : ?>
                        <ul class="header-cats__list">
                            <?php foreach ($zz_top_cats as $zz_cat) :
                                $zz_children = get_terms([
                                    'taxonomy'   => 'product_cat',
                                    'parent'     => $zz_cat->term_id,
                                    'hide_empty' => true,
                                    'orderby'    => 'name',
                                ]);
                                $zz_has_sub = !empty($zz_children) && !is_wp_error($zz_children);
                            ?>
                            <li class="header-cats__item<?php echo $zz_has_sub ? ' has-children' : ''; ?>">
                                <a href="<?php echo esc_url(get_term_link($zz_cat)); ?>" class="header-cats__link">
                                    <?php echo esc_html($zz_cat->name); ?>
                                    <?php if ($zz_has_sub) : ?>
                                    <svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                    <?php endif; ?>
                                </a>
                                <?php if ($zz_has_sub) : ?>
                                <ul class="header-cats__sub">
                                    <?php foreach ($zz_children as $zz_child) : ?>
                                    <li><a href="<?php echo esc_url(get_term_link($zz_child)); ?>"><?php echo esc_html($zz_child->name); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </nav>
                </div>

                <!-- Búsqueda -->
                <div class="header-search-wrapper">
                    <form class="header-search" method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
                        <input type="search" name="s" class="header-search__input"
                               placeholder="<?php esc_attr_e('Buscar productos...', 'zztheme'); ?>"
                               value="<?php echo esc_attr(get_search_query()); ?>" aria-label="<?php esc_attr_e('Buscar', 'zztheme'); ?>">
                        <input type="hidden" name="post_type" value="product">
                        <button type="submit" class="header-search__submit" aria-label="<?php esc_attr_e('Buscar', 'zztheme'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <!-- Acciones: cuenta, carrito -->
            <div class="site-header__actions">
                <?php if (class_exists('WooCommerce')) : ?>
                    <a class="header-action" href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                       aria-label="<?php esc_attr_e('Mi cuenta', 'zztheme'); ?>">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span class="header-action__label">
                            <?php echo is_user_logged_in() ? esc_html(wp_get_current_user()->display_name) : esc_html__('Cuenta', 'zztheme'); ?>
                        </span>
                    </a>

                    <?php
                    $wl_pages = get_pages(['meta_key' => '_wp_page_template', 'meta_value' => 'page-wishlist.php', 'number' => 1]);
                    $wl_url   = !empty($wl_pages) ? get_permalink($wl_pages[0]->ID) : '#';
                    ?>
                    <a class="header-action header-action--wishlist" href="<?php echo esc_url($wl_url); ?>"
                       aria-label="<?php esc_attr_e('Lista de deseos', 'zztheme'); ?>">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <span class="header-action__count header-action__count--wishlist">0</span>
                        <span class="header-action__label"><?php esc_html_e('Deseos', 'zztheme'); ?></span>
                    </a>

                    <a class="header-action header-action--cart" href="<?php echo esc_url(wc_get_cart_url()); ?>"
                       aria-label="<?php esc_attr_e('Carrito', 'zztheme'); ?>">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                        <span class="header-action__count header-cart-count"><?php echo WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0; ?></span>
                        <span class="header-action__label"><?php esc_html_e('Carrito', 'zztheme'); ?></span>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Fila nav -->
    <div class="site-header__nav">
        <div class="container site-header__nav-row">

            <!-- Hamburger -->
            <button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="<?php esc_attr_e('Abrir menú', 'zztheme'); ?>">
                <span class="screen-reader-text"><?php esc_html_e('Menú', 'zztheme'); ?></span>
                <span class="menu-toggle__bar"></span>
            </button>

            <!-- Nav -->
            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Menú principal', 'zztheme'); ?>">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'walker'         => new ZZTheme_Walker_Nav_Menu(),
                        'fallback_cb'    => false,
                    ]);
                } else {
                    echo '<ul id="primary-menu" class="menu">';
                    wp_list_pages(['title_li' => '', 'depth' => 2, 'sort_column' => 'menu_order, post_title']);
                    echo '</ul>';
                }
                ?>
            </nav>

            <!-- Teléfono + badges + dark mode -->
            <div class="header-right-info">
                <?php $phone = zztheme_contact('phone'); ?>
                <?php if ($phone) : ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="header-phone">
                        <?php printf(esc_html__('Llama: %s', 'zztheme'), '<strong>' . esc_html($phone) . '</strong>'); ?>
                    </a>
                <?php endif; ?>

                <?php
                $badge1 = get_theme_mod('zz_header_badge1', '');
                $badge2 = get_theme_mod('zz_header_badge2', '');
                if ($badge1 || $badge2) : ?>
                    <div class="header-badges">
                        <?php if ($badge1) : ?><span class="header-badge"><?php echo esc_html($badge1); ?></span><?php endif; ?>
                        <?php if ($badge2) : ?><span class="header-badge"><?php echo esc_html($badge2); ?></span><?php endif; ?>
                    </div>
                <?php endif; ?>

                <button id="dark-mode-toggle" aria-label="<?php esc_attr_e('Cambiar modo oscuro', 'zztheme'); ?>" aria-pressed="false">
                    <span class="dm-icon">◑</span>
                </button>
            </div>

        </div>
    </div>

</header>
