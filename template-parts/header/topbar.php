<?php
/**
 * Barra superior (topbar) con datos de contacto del Customizer.
 */

$email   = zztheme_contact('email');
$address = zztheme_contact('address');
$phone   = zztheme_contact('phone');
$whatsapp = zztheme_contact('whatsapp');
$social  = zztheme_get_social_links();

if (!$email && !$address && !$phone && !$whatsapp && empty($social)) {
    return;
}
?>
<div class="site-topbar">
    <div class="container site-topbar__row">

        <div class="topbar-left">
            <?php if ($phone) : ?>
                <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>">
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <?php echo esc_html($phone); ?>
                </a>
            <?php endif; ?>

            <?php if ($address) : ?>
                <span>
                    <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?php echo esc_html($address); ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="topbar-right">
            <?php if ($email) : ?>
                <a href="mailto:<?php echo esc_attr($email); ?>">
                    <?php echo esc_html($email); ?>
                </a>
            <?php endif; ?>

            <?php if ($whatsapp) : ?>
                <a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D/', '', $whatsapp)); ?>" target="_blank" rel="noopener noreferrer">
                    WhatsApp
                </a>
            <?php endif; ?>

            <?php foreach ($social as $s) : ?>
                <a href="<?php echo esc_url($s['url']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($s['label']); ?>">
                    <?php echo $s['svg']; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>
