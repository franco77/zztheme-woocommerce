<?php
/**
 * Template fallback para blog y búsquedas sin template específico.
 */

get_header();
?>
<main id="primary" class="site-main">
    <div class="container">
        <?php get_template_part('template-parts/content/page-header'); ?>

        <div class="content-area">
            <?php if (have_posts()) : ?>
                <div class="posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('template-parts/content/content'); ?>
                    <?php endwhile; ?>
                </div>
                <?php the_posts_pagination([
                    'prev_text' => '&laquo; ' . __('Anterior', 'zztheme'),
                    'next_text' => __('Siguiente', 'zztheme') . ' &raquo;',
                ]); ?>
            <?php else : ?>
                <?php get_template_part('template-parts/content/content-none'); ?>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php
get_footer();
