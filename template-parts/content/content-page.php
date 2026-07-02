<?php
/**
 * Contenido para páginas genéricas.
 */

while (have_posts()) :
    the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
        <?php if (has_post_thumbnail()) : ?>
            <div class="page-content__thumb">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="page-content__body entry-content">
            <?php
            the_content();
            wp_link_pages([
                'before' => '<nav class="page-links" aria-label="' . esc_attr__('Páginas del artículo', 'zztheme') . '">',
                'after'  => '</nav>',
            ]);
            ?>
        </div>
    </article>
    <?php
endwhile;
