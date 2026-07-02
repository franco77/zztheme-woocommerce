<?php
/**
 * My Account navigation — zztheme override
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">

	<?php if ( is_user_logged_in() ) :
		$user = wp_get_current_user();
		$name = $user->display_name ?: $user->user_login;
	?>
	<div class="account-nav-user">
		<div class="account-nav-user__avatar">
			<?php $avatar = get_avatar( $user->ID, 48, '', '', [ 'class' => 'account-nav-user__img' ] );
			if ( $avatar ) {
				echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else { ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="28" height="28" aria-hidden="true">
					<circle cx="12" cy="8" r="4"/>
					<path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
				</svg>
			<?php } ?>
		</div>
		<div class="account-nav-user__info">
			<span class="account-nav-user__label"><?php esc_html_e( 'Bienvenido,', 'zztheme' ); ?></span>
			<strong class="account-nav-user__name"><?php echo esc_html( $name ); ?></strong>
		</div>
	</div>
	<?php endif; ?>

	<ul>
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
