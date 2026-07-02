<?php
/**
 * Búsqueda en tiempo real (live search) vía AJAX.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_zz_live_search',        'zztheme_live_search_handler');
add_action('wp_ajax_nopriv_zz_live_search', 'zztheme_live_search_handler');

add_action('wp_ajax_zz_get_wishlist',        'zztheme_get_wishlist_handler');
add_action('wp_ajax_nopriv_zz_get_wishlist', 'zztheme_get_wishlist_handler');

function zztheme_live_search_handler(): void {
    check_ajax_referer('zz_live_search', 'nonce');

    $q = sanitize_text_field(wp_unslash($_GET['q'] ?? ''));

    if (mb_strlen($q) < 2) {
        wp_send_json_success(['results' => []]);
    }

    $query = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        's'              => $q,
        'posts_per_page' => 8,
        'no_found_rows'  => true,
        'fields'         => 'ids',
    ]);

    $results = [];

    foreach ($query->posts as $id) {
        $product = wc_get_product($id);
        if (!$product || !$product->is_visible()) {
            continue;
        }

        $thumb_id  = (int) $product->get_image_id();
        $thumb_url = $thumb_id
            ? wp_get_attachment_image_url($thumb_id, 'thumbnail')
            : wc_placeholder_img_src('thumbnail');

        $cats  = wp_get_post_terms($id, 'product_cat', ['fields' => 'names']);
        $cat   = (!is_wp_error($cats) && !empty($cats)) ? $cats[0] : '';

        $results[] = [
            'id'    => $id,
            'title' => $product->get_name(),
            'url'   => get_permalink($id),
            'price' => $product->get_price_html(),
            'thumb' => esc_url($thumb_url),
            'cat'   => esc_html($cat),
        ];
    }

    wp_send_json_success(['results' => $results]);
}

function zztheme_get_wishlist_handler(): void {
    check_ajax_referer('zz_wishlist', 'nonce');

    $ids_raw = sanitize_text_field(wp_unslash($_POST['ids'] ?? ''));
    $ids     = array_values(array_filter(array_map('absint', explode(',', $ids_raw))));

    if (empty($ids)) {
        wp_send_json_success(['products' => []]);
    }

    $query = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'post__in'       => $ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count($ids),
        'no_found_rows'  => true,
    ]);

    $products = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            if (!$product || !$product->is_visible()) {
                continue;
            }

            $thumb_id  = (int) $product->get_image_id();
            $thumb_url = $thumb_id
                ? wp_get_attachment_image_url($thumb_id, 'woocommerce_thumbnail')
                : wc_placeholder_img_src('woocommerce_thumbnail');

            $is_variable = $product->is_type('variable');

            $products[] = [
                'id'          => $product->get_id(),
                'title'       => $product->get_name(),
                'url'         => get_permalink($product->get_id()),
                'thumb'       => esc_url((string) $thumb_url),
                'price_html'  => $product->get_price_html(),
                'in_stock'    => $product->is_in_stock(),
                'stock_text'  => $product->is_in_stock()
                    ? __('En Stock', 'zztheme')
                    : __('Agotado', 'zztheme'),
                'is_variable' => $is_variable,
                'cart_url'    => $is_variable
                    ? get_permalink($product->get_id())
                    : esc_url($product->add_to_cart_url()),
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success(['products' => $products]);
}
