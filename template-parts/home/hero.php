<?php
/**
 * Slider del hero de la homepage.
 * Con slides del CPT zz_slide → muestra slider.
 * Sin slides → hero estático del Customizer (fallback).
 */

$slides = function_exists('zztheme_get_slides') ? zztheme_get_slides() : [];

/* ══════════════════════════════════════════════════════════════════════════
   HERO ESTÁTICO — fallback cuando no hay slides configurados
   ══════════════════════════════════════════════════════════════════════════ */
if (empty($slides)) :
    $title    = (string) get_theme_mod('zz_hero_title', __('Repuestos de Calidad', 'zztheme'));
    $subtitle = (string) get_theme_mod('zz_hero_subtitle', __('Encuentra todo lo que necesitas para tu vehículo', 'zztheme'));
    $cta_text = (string) get_theme_mod('zz_hero_cta_text', __('Ver catálogo', 'zztheme'));
    $cta_url  = (string) get_theme_mod('zz_hero_cta_url', '');
    $image_id = (int)    get_theme_mod('zz_hero_image', 0);

    if (!$cta_url && class_exists('WooCommerce')) {
        $cta_url = (string) get_permalink(wc_get_page_id('shop'));
    }

    $bg_style = '';
    if ($image_id) {
        $bg_url   = wp_get_attachment_image_url($image_id, 'zztheme-hero');
        $bg_style = $bg_url ? ' style="background-image:url(\'' . esc_url($bg_url) . '\')"' : '';
    }
    ?>
    <section class="home-hero"<?php echo $bg_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
        <div class="home-hero__overlay"></div>
        <div class="container">
            <div class="home-hero__content">
                <?php if ($title) : ?>
                    <h1><?php echo esc_html($title); ?></h1>
                <?php endif; ?>
                <?php if ($subtitle) : ?>
                    <p><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
                <?php if ($cta_text && $cta_url) : ?>
                    <a href="<?php echo esc_url($cta_url); ?>" class="button button--outline home-hero__cta">
                        <?php echo esc_html($cta_text); ?>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php
/* ══════════════════════════════════════════════════════════════════════════
   HERO SLIDER — cuando hay slides del CPT
   ══════════════════════════════════════════════════════════════════════════ */
else :
    $slide_count = count($slides);

    // Preload de la imagen del primer slide para evitar parpadeo inicial
    $first_img = get_the_post_thumbnail_url($slides[0], 'zztheme-hero');
    if ($first_img) :
        ?>
        <link rel="preload" as="image" href="<?php echo esc_url($first_img); ?>">
        <?php
    endif;
    ?>

    <section class="hero-slider"
             aria-label="Slider principal"
             aria-roledescription="carousel"
             data-autoplay="5000">

        <!-- Slides -->
        <div class="hero-slider__track" aria-live="polite">
            <?php foreach ($slides as $i => $slide) :
                $img_url  = get_the_post_thumbnail_url($slide, 'zztheme-hero');
                $title    = get_the_title($slide);
                $subtitle = (string) get_post_meta($slide->ID, '_zz_slide_subtitle', true);
                $btn_text = (string) get_post_meta($slide->ID, '_zz_slide_btn_text', true);
                $btn_url  = (string) get_post_meta($slide->ID, '_zz_slide_btn_url',  true);
                $is_first = $i === 0;
            ?>
            <div class="hero-slide<?php echo $is_first ? ' is-active' : ''; ?>"
                 role="group"
                 aria-roledescription="slide"
                 aria-label="Slide <?php echo $i + 1; ?> de <?php echo $slide_count; ?>"
                 aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
                 <?php if ($img_url) : ?>
                 style="background-image:url('<?php echo esc_url($img_url); ?>')"
                 <?php endif; ?>>

                <div class="hero-slide__overlay"></div>

                <div class="container">
                    <div class="hero-slide__content">
                        <?php if ($title) : ?>
                            <h2 class="hero-slide__title"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ($subtitle) : ?>
                            <p class="hero-slide__subtitle"><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>
                        <?php if ($btn_text && $btn_url) : ?>
                            <a href="<?php echo esc_url($btn_url); ?>"
                               class="button button--outline hero-slide__cta">
                                <?php echo esc_html($btn_text); ?>
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($slide_count > 1) : ?>
        <!-- Flechas de navegación -->
        <button class="hero-slider__arrow hero-slider__arrow--prev" aria-label="Slide anterior">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>
        <button class="hero-slider__arrow hero-slider__arrow--next" aria-label="Siguiente slide">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </button>

        <!-- Indicadores de puntos -->
        <nav class="hero-slider__dots" aria-label="Navegación de slides">
            <?php foreach ($slides as $i => $slide) : ?>
            <button class="hero-slider__dot<?php echo $i === 0 ? ' is-active' : ''; ?>"
                    aria-label="Ir al slide <?php echo $i + 1; ?>"
                    aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                    data-index="<?php echo $i; ?>">
            </button>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

    </section>

<?php endif; ?>
