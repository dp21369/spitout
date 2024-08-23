<?php
/**
 * Customer wallet transaction email
 *
 * This template can be overridden by copying it to yourtheme/woo-wallet/emails/plain/low-wallet-balance.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  Subrata Mal
 * @version     1.0.0
 * @package WooWallet
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$currency  = get_woocommerce_currency_symbol();
$remaining = woo_wallet()->wallet->get_wallet_balance( $user->ID, 'edit' );
echo '= ' . esc_html( $email_heading ) . " =\n\n";
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
/* translators: 1: wallet amount */
echo sprintf( esc_html__( 'Your %s wallet balance is low.', 'woo-wallet' ), wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
/* translators: 1: wallet amount */
echo sprintf( esc_html__( 'Current Balance: %s', 'woo-wallet' ), $currency . ' ' . woo_wallet()->wallet->get_wallet_balance( $user->ID, 'edit' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
echo sprintf( esc_html__( 'Please recharge you wallet now to avoid any disruption.', 'woo-wallet' ) );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";
echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
