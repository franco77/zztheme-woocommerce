<?php
/**
 * Template Name: Contacto
 * Mapa full-width + info de contacto (izq) + CF7 card (der).
 */

get_header();

$phone    = zztheme_contact('phone');
$email    = zztheme_contact('email');
$address  = zztheme_contact('address');
$whatsapp = zztheme_contact('whatsapp');
$maps_url = (string) get_theme_mod('zz_contact_maps_embed', '');
?>
<main id="primary" class="site-main">

    <?php get_template_part('template-parts/content/page-header'); ?>

    <?php if ($maps_url) : ?>
    <div class="contact-map-full">
        <iframe
            src="<?php echo esc_url($maps_url); ?>"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="<?php esc_attr_e('Nuestra ubicación en Google Maps', 'zztheme'); ?>">
        </iframe>
    </div>
    <?php endif; ?>

    <div class="container contact-page-wrap">
		<h2 class="contact-locations__title">
                    <?php echo esc_html(get_the_title()); ?>
                </h2>
		<br >
        <div class="contact-page-grid">
 
            <!-- Izquierda: info de contacto -->
            <div class="contact-locations">

                <?php
                $contact_intro = get_theme_mod('zz_contact_intro', '');
                if ($contact_intro) : ?>
                <div class="contact-locations__intro">
                    <?php echo wp_kses_post($contact_intro); ?>
                </div>
                <?php endif; ?>

                <div class="contact-locations__grid">

                    <?php if ($phone || $address) : ?>
                    <div class="contact-location">
                        <?php if ($address) : ?>
                        <p class="contact-location__label"><?php esc_html_e('DIRECCIÓN', 'zztheme'); ?></p>
                        <p class="contact-location__city"><?php echo esc_html(get_bloginfo('name')); ?></p>
                        <p class="contact-location__address"><?php echo nl2br(esc_html($address)); ?></p>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>"
                           class="contact-location__phone">
                            <?php echo esc_html($phone); ?>
                        </a>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>"
                           class="contact-location__email">
                            <?php echo esc_html($email); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($whatsapp) : ?>
                    <div class="contact-location">
                        <p class="contact-location__label">WHATSAPP</p>
                        <p class="contact-location__city"><?php esc_html_e('Soporte en línea', 'zztheme'); ?></p>
                        <a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D/', '', $whatsapp)); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="contact-location__phone">
                            <?php echo esc_html($whatsapp); ?>
                        </a>
                        <span class="contact-location__email"><?php esc_html_e('Lunes a Sábado, 8am–6pm', 'zztheme'); ?></span>
                    </div>
                    <?php endif; ?>

                </div>

                <?php
                $social = zztheme_get_social_links();
                if ($social) : ?>
                <div class="contact-page-social">
                    <?php foreach ($social as $s) : ?>
                    <a href="<?php echo esc_url($s['url']); ?>"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="<?php echo esc_attr($s['label']); ?>"
                       class="contact-social-link">
                        <?php echo $s['svg']; // phpcs:ignore ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Derecha: card con CF7 -->
            <div class="contact-form-card">
                <h3 class="contact-form-card__title">
                    <?php esc_html_e('Escríbenos...', 'zztheme'); ?>
                </h3>
                <p class="contact-form-card__desc">
                    <?php esc_html_e('Completa el formulario y te responderemos a la brevedad.', 'zztheme'); ?>
                </p>
                <?php
                /* Coloca el shortcode de Contact Form 7 en el editor de esta página */
                the_content();
                ?>
            </div>

        </div>
    </div>

</main>
<?php
get_footer();
