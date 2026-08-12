<?php
/**
 * CPT zz_slide — Slides del hero con límite máximo de 6.
 */

if (!defined('ABSPATH')) exit;

/* ── Registro del Custom Post Type ────────────────────────────────────────── */
add_action('init', function () {
    register_post_type('zz_slide', [
        'labels' => [
            'name'               => 'Slides del Hero',
            'singular_name'      => 'Slide',
            'add_new'            => 'Añadir slide',
            'add_new_item'       => 'Añadir nuevo slide',
            'edit_item'          => 'Editar slide',
            'new_item'           => 'Nuevo slide',
            'view_item'          => 'Ver slide',
            'search_items'       => 'Buscar slides',
            'not_found'          => 'No se encontraron slides.',
            'not_found_in_trash' => 'No hay slides en la papelera.',
            'menu_name'          => 'Slides Hero',
        ],
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => ['title', 'thumbnail', 'page-attributes'],
        'rewrite'            => false,
        'has_archive'        => false,
        'hierarchical'       => false,
        'show_in_rest'       => false,
    ]);
});

/* ── Meta box: Subtítulo + Botón ───────────────────────────────────────────── */
add_action('add_meta_boxes', function () {
    add_meta_box(
        'zz_slide_meta',
        'Contenido del slide',
        'zztheme_slide_meta_box_html',
        'zz_slide',
        'normal',
        'high'
    );
});

function zztheme_slide_meta_box_html(WP_Post $post): void {
    wp_nonce_field('zz_slide_save', 'zz_slide_nonce');
    $subtitle = (string) get_post_meta($post->ID, '_zz_slide_subtitle', true);
    $btn_text = (string) get_post_meta($post->ID, '_zz_slide_btn_text', true);
    $btn_url  = (string) get_post_meta($post->ID, '_zz_slide_btn_url',  true);
    ?>
    <table class="form-table" style="width:100%">
        <tr>
            <th style="width:140px"><label for="zz_slide_subtitle">Subtítulo</label></th>
            <td>
                <input type="text"
                       id="zz_slide_subtitle"
                       name="zz_slide_subtitle"
                       value="<?php echo esc_attr($subtitle); ?>"
                       class="large-text"
                       placeholder="Texto secundario que aparece debajo del título">
            </td>
        </tr>
        <tr>
            <th><label for="zz_slide_btn_text">Texto del botón</label></th>
            <td>
                <input type="text"
                       id="zz_slide_btn_text"
                       name="zz_slide_btn_text"
                       value="<?php echo esc_attr($btn_text); ?>"
                       class="regular-text"
                       placeholder="Ej: Ver catálogo">
            </td>
        </tr>
        <tr>
            <th><label for="zz_slide_btn_url">URL del botón</label></th>
            <td>
                <input type="url"
                       id="zz_slide_btn_url"
                       name="zz_slide_btn_url"
                       value="<?php echo esc_attr($btn_url); ?>"
                       class="large-text"
                       placeholder="https://">
            </td>
        </tr>
    </table>
    <p style="margin-top:14px;padding:12px 14px;background:#f0f6fc;border-left:4px solid #3f82ef;border-radius:2px;font-size:13px;color:#3d4554;line-height:1.6">
        📌 <strong>Imagen de fondo:</strong> asígnala como <em>Imagen destacada</em> en el panel de la derecha.<br>
        🔤 <strong>Título:</strong> usa el campo "Título" estándar de arriba.<br>
        🔢 <strong>Orden:</strong> el campo "Orden" (panel Atributos, derecha) controla qué slide va primero — 0 = primero, 1 = segundo, etc.<br>
        ⚠️ <strong>Máximo 6 slides</strong> permitidos. Si intentas crear más, el sistema lo rechazará automáticamente.
    </p>
    <?php
}

add_action('save_post_zz_slide', function (int $post_id): void {
    if (!isset($_POST['zz_slide_nonce'])) return;
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['zz_slide_nonce'])), 'zz_slide_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        '_zz_slide_subtitle' => 'zz_slide_subtitle',
        '_zz_slide_btn_text' => 'zz_slide_btn_text',
    ];
    foreach ($fields as $meta_key => $field_name) {
        if (isset($_POST[$field_name])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field(wp_unslash($_POST[$field_name])));
        }
    }
    // Fix 3: La URL del botón requiere esc_url_raw, no sanitize_text_field.
    if (isset($_POST['zz_slide_btn_url'])) {
        update_post_meta($post_id, '_zz_slide_btn_url', esc_url_raw(wp_unslash($_POST['zz_slide_btn_url'])));
    }
});

/* ── Límite de 6 slides ────────────────────────────────────────────────────── */
add_action('wp_insert_post', function (int $post_id, WP_Post $post, bool $update): void {
    if ($post->post_type !== 'zz_slide' || $update) return;
    if (in_array($post->post_status, ['auto-draft', 'trash', 'inherit'], true)) return;

    $counts = wp_count_posts('zz_slide');
    $total  = (int) ($counts->publish ?? 0)
            + (int) ($counts->draft   ?? 0)
            + (int) ($counts->pending ?? 0);

    if ($total > 6) {
        wp_trash_post($post_id);
        set_transient('zz_slide_limit_' . get_current_user_id(), true, 30);
    }
}, 10, 3);

add_action('admin_notices', function (): void {
    if (!get_transient('zz_slide_limit_' . get_current_user_id())) return;
    delete_transient('zz_slide_limit_' . get_current_user_id());
    ?>
    <div class="notice notice-warning is-dismissible">
        <p>
            <strong>ZZTheme — Slides del Hero:</strong>
            Se alcanzó el límite de <strong>6 slides</strong>.
            Elimina o desactiva uno existente antes de agregar otro.
        </p>
    </div>
    <?php
});

/* ── Columna "Orden" en la lista del admin ─────────────────────────────────── */
add_filter('manage_zz_slide_posts_columns', function (array $cols): array {
    $new = [];
    foreach ($cols as $key => $label) {
        $new[$key] = $label;
        if ($key === 'title') {
            $new['slide_order'] = 'Orden';
        }
    }
    return $new;
});

add_action('manage_zz_slide_posts_custom_column', function (string $col, int $post_id): void {
    if ($col === 'slide_order') {
        echo (int) get_post_field('menu_order', $post_id);
    }
}, 10, 2);

/* ── Aviso de conteo en el formulario "Añadir nuevo" ──────────────────────── */
add_action('admin_head-post-new.php', function (): void {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'zz_slide') return;

    $counts  = wp_count_posts('zz_slide');
    $total   = (int) ($counts->publish ?? 0) + (int) ($counts->draft ?? 0);
    $restant = max(0, 6 - $total);
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var h1 = document.querySelector('.wp-heading-inline');
        if (!h1) return;
        var badge = document.createElement('span');
        badge.style.cssText = 'margin-left:10px;font-size:13px;font-weight:400;color:<?php echo $restant === 0 ? "#b91c1c" : "#6b7280"; ?>';
        badge.textContent = '(<?php echo $total; ?>/6 slides<?php echo $restant === 0 ? " — límite alcanzado" : ""; ?>)';
        h1.parentNode.insertBefore(badge, h1.nextSibling);
    });
    </script>
    <?php
});

/* ── Helper público: obtener slides activos ordenados ──────────────────────── */
function zztheme_get_slides(): array {
    $query = new WP_Query([
        'post_type'      => 'zz_slide',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);
    return $query->posts ?: [];
}
