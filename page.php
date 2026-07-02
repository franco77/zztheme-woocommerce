<?php
/**
 * Plantilla para páginas genéricas de WordPress.
 */

get_header();
?>
<main id="primary" class="site-main">
    <?php get_template_part('template-parts/content/page-header'); ?>

    <div class="container">
        <div class="content-area">
            <?php get_template_part('template-parts/content/content-page'); ?>
        </div>
    </div>
</main>
<?php
get_footer();
