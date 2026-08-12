<?php
/**
 * Funciones auxiliares reutilizables en templates.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Muestra el logo del sitio o el nombre como texto.
 */
function zztheme_site_branding(): void {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a class="site-title-link" href="' . esc_url(home_url('/')) . '" rel="home">'
            . esc_html(get_bloginfo('name'))
            . '</a>';
    }
}

/**
 * Devuelve un valor del Customizer de contacto.
 */
function zztheme_contact(string $key): string {
    return (string) get_theme_mod('zz_contact_' . $key, '');
}

/**
 * Devuelve array de redes sociales configuradas.
 *
 * @return array<int, array{network: string, url: string, label: string, svg: string}>
 */
function zztheme_get_social_links(): array {
    $networks = [
        'facebook'  => [
            'label' => 'Facebook',
            'svg'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
        ],
        'instagram' => [
            'label' => 'Instagram',
            'svg'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
        ],
        'whatsapp'  => [
            'label' => 'WhatsApp',
            'svg'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>',
        ],
        'twitter'   => [
            'label' => 'Twitter / X',
            'svg'   => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        ],
    ];

    $links = [];
    foreach ($networks as $key => $data) {
        $url = (string) get_theme_mod('zz_social_' . $key, '');
        if ($url) {
            $links[] = array_merge(['network' => $key, 'url' => $url], $data);
        }
    }
    return $links;
}

/**
 * Walker para categorías del sidebar: añade conteo como badge y clase is-active.
 */
class ZZTheme_Category_Walker extends Walker_Category {
    public function start_el( &$output, $data_object, $depth = 0, $args = [], $current_object_id = 0 ) {
        $cat         = $data_object;
        $url         = get_term_link( $cat );
        $count       = ! empty( $args['show_count'] ) ? (int) $cat->count : 0;
        $active      = ( (int) $cat->term_id === (int) get_queried_object_id() ) ? ' is-active' : '';
        $has_children = ! empty( get_term_children( $cat->term_id, 'product_cat' ) );

        $output .= '<li class="cat-item cat-item-' . $cat->term_id . $active . ( $has_children ? ' has-children' : '' ) . '">';
        $output .= '<a href="' . esc_url( is_wp_error( $url ) ? '#' : $url ) . '" class="cat-item__link">';
        $output .= '<span class="cat-item__name">' . esc_html( $cat->name ) . '</span>';
        if ( $count ) {
            $output .= '<span class="cat-item__count">' . $count . '</span>';
        }
        $output .= '</a>';
        if ( $has_children ) {
            $output .= '<button class="cat-expand" aria-expanded="false" aria-label="' . esc_attr__( 'Expandir', 'zztheme' ) . '">+</button>';
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = [] ) {
        $output .= "</li>\n";
    }
}

/**
 * Renderiza el sidebar fallback de la tienda cuando shop-sidebar no tiene widgets.
 */
function zztheme_render_default_shop_sidebar(): void {
    $shop_url   = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/' );
    $has_filter = ! empty( $_GET['min_price'] ) || ! empty( $_GET['max_price'] ) || is_product_category(); // phpcs:ignore WordPress.Security.NonceVerification

    echo '<div class="shop-sidebar">';

    /* ── Limpiar filtros ── */
    if ( $has_filter ) {
        echo '<a href="' . esc_url( $shop_url ) . '" class="sidebar-reset">'
            . '<svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> '
            . esc_html__( 'Limpiar filtros', 'zztheme' )
            . '</a>';
    }

    /* ── Categorías ── */
    echo '<div class="sidebar-section">';
    echo '<h3 class="sidebar-section__title">'
        . '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>'
        . esc_html__( 'Categorías', 'zztheme' )
        . '</h3>';
    echo '<ul class="sidebar-categories">';
    wp_list_categories( [
        'taxonomy'   => 'product_cat',
        'title_li'   => '',
        'hide_empty' => true,
        'depth'      => 2,
        'show_count' => true,
        'walker'     => new ZZTheme_Category_Walker(),
    ] );
    echo '</ul>';
    echo '</div>';

    /* ── Precio ── */
    if ( class_exists( 'WooCommerce' ) ) {
        echo '<div class="sidebar-section">';
        echo '<h3 class="sidebar-section__title">'
            . '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'
            . esc_html__( 'Precio', 'zztheme' )
            . '</h3>';
        the_widget( 'WC_Widget_Price_Filter', [ 'button_text' => __( 'Aplicar', 'zztheme' ) ] );
        echo '</div>';
    }

    /* ── Disponibilidad ── */
    echo '<div class="sidebar-section">';
    echo '<h3 class="sidebar-section__title">'
        . '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>'
        . esc_html__( 'Disponibilidad', 'zztheme' )
        . '</h3>';
    $on_sale_url = add_query_arg( 'orderby', 'price', $shop_url );
    $sale_ids    = class_exists( 'WooCommerce' ) ? wc_get_product_ids_on_sale() : [];
    echo '<ul class="sidebar-availability">';
    echo '<li><a href="' . esc_url( $on_sale_url ) . '" class="availability-link">'
        . '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>'
        . esc_html__( 'En oferta', 'zztheme' )
        . ( ! empty( $sale_ids ) ? ' <span class="cat-item__count">' . count( $sale_ids ) . '</span>' : '' )
        . '</a></li>';
    echo '</ul>';
    echo '</div>';

    echo '</div><!-- .shop-sidebar -->';
}

/**
 * Devuelve HTML del breadcrumb manual para páginas no-WC.
 */
function zztheme_get_breadcrumb(): string {
    $crumbs   = [];
    $crumbs[] = '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Inicio', 'zztheme') . '</a>';

    if (is_singular()) {
        $crumbs[] = '<span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_archive()) {
        $crumbs[] = '<span>' . esc_html(get_the_archive_title()) . '</span>';
    } elseif (is_search()) {
        $crumbs[] = '<span>' . sprintf(esc_html__('Búsqueda: %s', 'zztheme'), get_search_query()) . '</span>';
    } elseif (is_404()) {
        $crumbs[] = '<span>' . esc_html__('Página no encontrada', 'zztheme') . '</span>';
    }

    return implode('<span class="bc-sep" aria-hidden="true">/</span>', $crumbs);
}

/**
 * Devuelve el HTML del formulario de contacto nativo.
 */
function zztheme_contact_form_html(): string {
    ob_start();
    ?>
    <form id="zztheme-contact-form" class="contact-form" novalidate>
        <?php wp_nonce_field('zztheme_contact', 'zztheme_nonce'); ?>
        <input type="hidden" name="action" value="zztheme_contact">

        <div class="row">
            <div class="column">
                <label for="cf-name"><?php esc_html_e('Nombre *', 'zztheme'); ?></label>
                <input type="text" id="cf-name" name="cf_name" required placeholder="<?php esc_attr_e('Tu nombre completo', 'zztheme'); ?>">
            </div>
            <div class="column">
                <label for="cf-email"><?php esc_html_e('Email *', 'zztheme'); ?></label>
                <input type="email" id="cf-email" name="cf_email" required placeholder="<?php esc_attr_e('tu@email.com', 'zztheme'); ?>">
            </div>
        </div>

        <label for="cf-phone"><?php esc_html_e('Teléfono', 'zztheme'); ?></label>
        <input type="tel" id="cf-phone" name="cf_phone" placeholder="<?php esc_attr_e('+58 414 000 0000', 'zztheme'); ?>">

        <label for="cf-message"><?php esc_html_e('Mensaje *', 'zztheme'); ?></label>
        <textarea id="cf-message" name="cf_message" rows="6" required placeholder="<?php esc_attr_e('¿En qué podemos ayudarte?', 'zztheme'); ?>"></textarea>

        <button type="submit" class="button button-primary contact-form__submit">
            <?php esc_html_e('Enviar mensaje', 'zztheme'); ?>
        </button>

        <div class="contact-success" hidden>
            <?php esc_html_e('¡Mensaje enviado! Nos pondremos en contacto a la brevedad.', 'zztheme'); ?>
        </div>
        <div class="contact-error" hidden>
            <?php esc_html_e('Error al enviar. Por favor intenta de nuevo o escríbenos directamente.', 'zztheme'); ?>
        </div>
    </form>
    <?php
    return ob_get_clean();
}

/**
 * Rate limiter basado en transients. Retorna false si el IP superó el límite.
 */
function zztheme_rate_limit(string $action, int $max, int $window_seconds): bool {
    $ip  = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? 'cli'));
    $key = 'zzrl_' . substr(md5($action . $ip), 0, 24);
    $hit = (int) get_transient($key);
    if ($hit >= $max) {
        return false;
    }
    set_transient($key, $hit + 1, $window_seconds);
    return true;
}

/* Handler AJAX del formulario de contacto. */
function zztheme_handle_contact_form(): void {
    if (!isset($_POST['zztheme_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['zztheme_nonce'])), 'zztheme_contact')) {
        wp_send_json_error(['message' => __('Token de seguridad inválido.', 'zztheme')]);
    }

    // Fix 1: Rate limiting — máx. 5 envíos por IP cada 5 minutos.
    if (!zztheme_rate_limit('contact', 5, 300)) {
        wp_send_json_error(['message' => __('Demasiados intentos. Espera unos minutos antes de volver a enviar.', 'zztheme')]);
    }

    $name    = sanitize_text_field(wp_unslash($_POST['cf_name'] ?? ''));
    $email   = sanitize_email(wp_unslash($_POST['cf_email'] ?? ''));
    $phone   = sanitize_text_field(wp_unslash($_POST['cf_phone'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['cf_message'] ?? ''));

    if (!$name || !$email || !$message || !is_email($email)) {
        wp_send_json_error(['message' => __('Por favor completa todos los campos obligatorios.', 'zztheme')]);
    }

    $to      = get_option('admin_email');
    $subject = sprintf(__('[%s] Nuevo mensaje de contacto de %s', 'zztheme'), get_bloginfo('name'), $name);
    $body    = sprintf(
        "Nombre: %s\nEmail: %s\nTeléfono: %s\n\nMensaje:\n%s",
        $name, $email, $phone ?: '—', $message
    );
    // Fix 5: Reply-To usa solo el email para evitar inyección de cabeceras.
    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $email,
    ];

    if (wp_mail($to, $subject, $body, $headers)) {
        wp_send_json_success(['message' => __('Mensaje enviado correctamente.', 'zztheme')]);
    } else {
        wp_send_json_error(['message' => __('Error al enviar el mensaje. Inténtalo nuevamente.', 'zztheme')]);
    }
}

add_action('wp_ajax_zztheme_contact', 'zztheme_handle_contact_form');
add_action('wp_ajax_nopriv_zztheme_contact', 'zztheme_handle_contact_form');
