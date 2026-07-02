<?php
/**
 * Fallback cuando no hay resultados.
 */
?>
<div class="no-results">
    <h2><?php esc_html_e('No se encontraron resultados', 'zztheme'); ?></h2>
    <p><?php esc_html_e('Lo sentimos, no encontramos contenido que coincida con tu búsqueda. Intenta con otras palabras.', 'zztheme'); ?></p>
    <?php get_search_form(); ?>
</div>
