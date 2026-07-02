<?php
/**
 * Pie de página global.
 */
?>
<footer id="colophon" class="site-footer">
    <div class="container">

        <!-- Widgets del footer -->
        <?php
        $has_widgets = false;
        for ($i = 1; $i <= 4; $i++) {
            if (is_active_sidebar('footer-' . $i)) { $has_widgets = true; break; }
        }
        if ($has_widgets) : ?>
            <div class="site-footer__widgets">
                <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <?php if (is_active_sidebar('footer-' . $i)) : ?>
                        <div class="footer-col footer-col-<?php echo (int) $i; ?>">
                            <?php dynamic_sidebar('footer-' . $i); ?>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <!-- Bottom bar -->
        <div class="site-footer__bottom">
            <p class="site-info">
                &copy; <?php echo esc_html(date_i18n('Y')); ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
                &mdash;
                <?php esc_html_e('Todos los derechos reservados.', 'zztheme'); ?>
            </p>

            <?php if (has_nav_menu('footer')) : ?>
                <?php wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]); ?>
            <?php endif; ?>

            <?php
            $social_links = zztheme_get_social_links();
            if ($social_links) : ?>
                <div class="footer-social">
                    <?php foreach ($social_links as $social) : ?>
                        <a href="<?php echo esc_url($social['url']); ?>"
                           target="_blank" rel="noopener noreferrer"
                           aria-label="<?php echo esc_attr($social['label']); ?>">
                            <?php echo $social['svg']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
