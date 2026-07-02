<?php
/**
 * Barra de 3 bloques informativos de la homepage.
 */

$features = [];
for ($i = 1; $i <= 3; $i++) {
    $features[] = [
        'title' => (string) get_theme_mod("zz_feature_{$i}_title", ''),
        'desc'  => (string) get_theme_mod("zz_feature_{$i}_desc", ''),
    ];
}

/* Valores por defecto si el Customizer está vacío */
$defaults = [
    [
        'title' => __('Productos Originales', 'zztheme'),
        'desc'  => __('Solo trabajamos con marcas certificadas', 'zztheme'),
    ],
    [
        'title' => __('Precios Accesibles', 'zztheme'),
        'desc'  => __('Los mejores precios del mercado garantizados', 'zztheme'),
    ],
    [
        'title' => __('Gran Variedad', 'zztheme'),
        'desc'  => __('Más de 10.000 referencias disponibles', 'zztheme'),
    ],
];

/* SVG icons */
$icons = [
    '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>',
    '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
    '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
];
?>
<div class="features-bar">
    <div class="container">
        <div class="features-bar__grid">
            <?php foreach ($features as $i => $feat) :
                $title = $feat['title'] ?: $defaults[$i]['title'];
                $desc  = $feat['desc']  ?: $defaults[$i]['desc'];
                ?>
                <div class="feature-item">
                    <div class="feature-item__icon">
                        <?php echo $icons[$i]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <div class="feature-item__text">
                        <div class="feature-item__title"><?php echo esc_html($title); ?></div>
                        <p class="feature-item__desc"><?php echo esc_html($desc); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
