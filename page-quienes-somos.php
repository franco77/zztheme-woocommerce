<?php
/**
 * Template Name: Quiénes Somos
 * Página institucional con imagen + texto + sección de valores.
 */

get_header();
?>
<main id="primary" class="site-main">

    <?php get_template_part('template-parts/content/page-header'); ?>

    <?php while (have_posts()) : the_post(); ?>

        <!-- Contenido principal: imagen + texto -->
        <section class="container">
            <div class="about-layout">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="about-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="about-text entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>

    <?php endwhile; ?>

    <!-- Sección Misión / Visión / Valores -->
    <section class="about-values">
        <div class="container">
            <div class="section-header">
                <h2><?php esc_html_e('Nuestros Valores', 'zztheme'); ?></h2>
                <p><?php esc_html_e('Los principios que guían nuestro trabajo cada día.', 'zztheme'); ?></p>
            </div>

            <div class="values-grid">

                <div class="value-card">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title"><?php esc_html_e('Misión', 'zztheme'); ?></h3>
                    <p class="value-card__desc">
                        <?php esc_html_e('Proveer repuestos automotrices de calidad al mejor precio, con un servicio excepcional.', 'zztheme'); ?>
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title"><?php esc_html_e('Visión', 'zztheme'); ?></h3>
                    <p class="value-card__desc">
                        <?php esc_html_e('Ser la tienda de repuestos de referencia en la región, reconocida por excelencia y confianza.', 'zztheme'); ?>
                    </p>
                </div>

                <div class="value-card">
                    <div class="value-card__icon">
                        <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3 class="value-card__title"><?php esc_html_e('Valores', 'zztheme'); ?></h3>
                    <p class="value-card__desc">
                        <?php esc_html_e('Honestidad, calidad, compromiso y atención personalizada en cada venta.', 'zztheme'); ?>
                    </p>
                </div>

            </div>
        </div>
    </section>

</main>
<?php
get_footer();
