<?php
/**
 * Funciones principales del tema ZZTheme.
 * Solo carga los archivos de inc/.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('ZZTHEME_VERSION', '1.0.0');
define('ZZTHEME_DIR', get_template_directory());
define('ZZTHEME_URI', get_template_directory_uri());

require_once ZZTHEME_DIR . '/inc/setup.php';
require_once ZZTHEME_DIR . '/inc/template-tags.php';
require_once ZZTHEME_DIR . '/inc/menu-walker.php';
require_once ZZTHEME_DIR . '/inc/enqueue.php';
require_once ZZTHEME_DIR . '/inc/customizer.php';
require_once ZZTHEME_DIR . '/inc/ajax.php';

if (class_exists('WooCommerce')) {
    require_once ZZTHEME_DIR . '/inc/woocommerce.php';
    require_once ZZTHEME_DIR . '/inc/update-check.php';
}
