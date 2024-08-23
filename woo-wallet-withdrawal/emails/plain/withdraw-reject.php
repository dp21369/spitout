<?php
/**
 * Rejected Withdraw request Email. ( plain text )
 *
 * An email sent to the user when a withdraw request rejected by admin.
 *
 * @version     1.0.1
 * 
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
echo "= " . $email_heading . " =\n\n";
?>

<?php _e( 'Hi '. $data['username'], 'woo-wallet-withdrawal' ); echo " \n";?>

<?php _e( 'Your withdraw request was cancelled', 'woo-wallet-withdrawal' ); echo " \n";?>

<?php _e( 'You sent a withdraw request of:', 'woo-wallet-withdrawal' );  echo " \n";?>

<?php _e( 'Amount : '.$data['amount'], 'woo-wallet-withdrawal' ); echo " \n";?>
<?php _e( 'Method : '.$data['method'], 'woo-wallet-withdrawal' ); echo " \n";?>
<?php
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );