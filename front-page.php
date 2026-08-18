<?php
/**
 * Página de inicio (Homepage).
 * Secciones: Hero → Features → Categorías → Destacados → Banners → Recién Llegados → Ofertas → Newsletter
 */

get_header();
?>
<main id="primary" class="site-main home">

    <?php get_template_part('template-parts/home/hero'); ?>
    <?php get_template_part('template-parts/home/features'); ?>
    <?php get_template_part('template-parts/home/categories'); ?>
    <?php get_template_part('template-parts/home/featured-products'); ?>
    <?php get_template_part('template-parts/home/banners'); ?>
    <?php get_template_part('template-parts/home/new-products'); ?>
    <?php get_template_part('template-parts/home/mid-banner'); ?>
    <?php get_template_part('template-parts/home/sale-products'); ?>

    <?php
    /* ── Secciones de Categorías ──────────────────────────────────────── */
    if (class_exists('WooCommerce')) :
        $section_title = (string) get_theme_mod('zz_cats_title', __('Navega por Categoría', 'zztheme'));
        $cat_items = [];
        for ($i = 1; $i <= 3; $i++) {
            $cat_id    = (int) get_theme_mod("zz_cat_{$i}_id", 0);
            $cat_label = (string) get_theme_mod("zz_cat_{$i}_label", '');
            if ($cat_id > 0) {
                $cat_items[] = ['id' => $cat_id, 'label' => $cat_label];
            }
        }

        if (!empty($cat_items)) : ?>
            <section class="home-section">
                <div class="container">
                    <?php if ($section_title) : ?>
                        <div class="section-header">
                            <h2><?php echo esc_html($section_title); ?></h2>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($cat_items as $cat_item) :
                        $term = get_term($cat_item['id'], 'product_cat');
                        if (!$term || is_wp_error($term)) continue;

                        $label   = $cat_item['label'] ?: $term->name;
                        $term_url = get_term_link($term);
                        $count   = (int) get_theme_mod('zz_featured_count', 8);

                        $products = new WC_Shortcode_Products([
                            'category' => $term->slug,
                            'limit'    => $count,
                            'columns'  => 4,
                            'orderby'  => 'menu_order',
                        ]);
                        $content = $products->get_content();
                        if (!$content) continue;
                        ?>
                        <div class="category-section" style="margin-bottom: 48px;">
                            <div class="category-section__header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                                <h3 style="margin:0;"><?php echo esc_html($label); ?></h3>
                                <a href="<?php echo esc_url($term_url); ?>" class="button button--outline" style="font-size:0.8rem;padding:7px 16px;">
                                    <?php esc_html_e('Ver todos', 'zztheme'); ?>
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </a>
                            </div>
                            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif;
    endif; ?>

    <?php
    /* ── Newsletter ────────────────────────────────────────────────────── */
    $newsletter_enabled  = (bool) get_theme_mod('zz_newsletter_enabled', true);
    $newsletter_title    = (string) get_theme_mod('zz_newsletter_title', __('¿Quieres recibir las mejores ofertas?', 'zztheme'));
    $newsletter_subtitle = (string) get_theme_mod('zz_newsletter_subtitle', __('Suscríbete y recibe descuentos exclusivos', 'zztheme'));

    if ($newsletter_enabled) : ?>
        <section class="newsletter-strip">
            <div class="container">
                <?php if ($newsletter_title) : ?><h2><?php echo esc_html($newsletter_title); ?></h2><?php endif; ?>
                <?php if ($newsletter_subtitle) : ?><p><?php echo esc_html($newsletter_subtitle); ?></p><?php endif; ?>
                <form class="newsletter-form" action="#" method="post" onsubmit="return false;">
                    <input type="email" name="newsletter_email"
                           placeholder="<?php esc_attr_e('tu@correo.com', 'zztheme'); ?>"
                           aria-label="<?php esc_attr_e('Tu email', 'zztheme'); ?>"
                           required>
                    <button type="submit"><?php esc_html_e('Suscribirme', 'zztheme'); ?></button>
                </form>
            </div>
        </section>
    <?php endif; ?>

</main>
<?php
get_footer();
