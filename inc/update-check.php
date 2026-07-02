<?php
/**
 * Verifica si los templates de WooCommerce que el tema sobreescribe
 * están desactualizados y muestra un aviso en el panel de WordPress.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lee la versión (@version x.x.x) del comentario de cabecera de un archivo.
 */
function zztheme_read_template_version( string $file ): string {
	if ( ! file_exists( $file ) ) {
		return '';
	}
	$contents = file_get_contents( $file, false, null, 0, 1500 );
	if ( preg_match( '/@version\s+([\d.]+)/i', $contents, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Compara los templates del tema con los de WooCommerce y devuelve
 * un array con los que están desactualizados.
 *
 * @return array  [ ['file' => '...', 'theme_v' => '...', 'wc_v' => '...'], ... ]
 */
function zztheme_get_outdated_templates(): array {
	if ( ! function_exists( 'WC' ) ) {
		return [];
	}

	$wc_templates_dir    = WC()->plugin_path() . '/templates/';
	$theme_templates_dir = get_template_directory() . '/woocommerce/';

	// Lista de templates que el tema sobreescribe.
	$overrides = [
		'myaccount/navigation.php',
	];

	$outdated = [];

	foreach ( $overrides as $relative_path ) {
		$theme_file = $theme_templates_dir . $relative_path;
		$wc_file    = $wc_templates_dir . $relative_path;

		$theme_v = zztheme_read_template_version( $theme_file );
		$wc_v    = zztheme_read_template_version( $wc_file );

		if ( $theme_v && $wc_v && version_compare( $theme_v, $wc_v, '<' ) ) {
			$outdated[] = [
				'file'    => 'woocommerce/' . $relative_path,
				'theme_v' => $theme_v,
				'wc_v'    => $wc_v,
			];
		}
	}

	return $outdated;
}

/**
 * Muestra el aviso en el panel de WordPress si hay templates desactualizados.
 */
add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$outdated = zztheme_get_outdated_templates();

	if ( empty( $outdated ) ) {
		return;
	}

	$status_url = admin_url( 'admin.php?page=wc-status' );
	?>
	<div class="notice notice-warning" style="border-left-color:#f59e0b; padding: 14px 16px;">

		<p style="font-size:1rem; font-weight:700; margin:0 0 6px;">
			⚠️ <?php esc_html_e( 'El tema necesita una pequeña actualización manual', 'zztheme' ); ?>
		</p>

		<p style="margin:0 0 10px; color:#444;">
			<?php esc_html_e( 'WooCommerce fue actualizado y algunos archivos del tema quedaron desactualizados. Esto puede causar que algunas páginas no se vean bien.', 'zztheme' ); ?>
		</p>

		<p style="margin:0 0 6px;"><strong><?php esc_html_e( 'Archivos afectados:', 'zztheme' ); ?></strong></p>
		<ul style="margin:0 0 12px; padding-left:20px;">
			<?php foreach ( $outdated as $t ) : ?>
				<li style="font-family:monospace; font-size:0.9rem;">
					<?php echo esc_html( $t['file'] ); ?>
					&nbsp;<span style="color:#888;">(tema: v<?php echo esc_html( $t['theme_v'] ); ?> → WooCommerce: v<?php echo esc_html( $t['wc_v'] ); ?>)</span>
				</li>
			<?php endforeach; ?>
		</ul>

		<p style="margin:0 0 10px; color:#444;">
			<?php esc_html_e( '¿Qué hacer? Avísale a tu desarrollador o sigue estos pasos:', 'zztheme' ); ?>
		</p>
		<ol style="margin:0 0 12px; padding-left:20px; color:#444;">
			<li><?php esc_html_e( 'Ve a WooCommerce → Estado del sistema → Templates del tema.', 'zztheme' ); ?></li>
			<li><?php esc_html_e( 'Verás el archivo marcado en rojo. Haz clic en "Ver diferencia".', 'zztheme' ); ?></li>
			<li><?php esc_html_e( 'Copia los cambios nuevos al archivo del tema conservando el bloque del panel de usuario (account-nav-user).', 'zztheme' ); ?></li>
		</ol>

		<a href="<?php echo esc_url( $status_url ); ?>" class="button button-primary">
			<?php esc_html_e( 'Ver Estado de WooCommerce', 'zztheme' ); ?>
		</a>

	</div>
	<?php
} );
