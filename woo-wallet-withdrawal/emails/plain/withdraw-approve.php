<?php
/**
 * Approved Withdraw request Email.
 *
 * An email sent to the user when a withdraw request is approved by admin.
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

<?php _e( 'Your withdraw request has been approved, congrats!', 'woo-wallet-withdrawal' ); echo " \n";?>

<?php _e( 'You sent a withdraw request of:', 'woo-wallet-withdrawal' );  echo " \n";?>

<?php _e( 'Amount : '.$data['amount'], 'woo-wallet-withdrawal' ); echo " \n";?>
<?php _e( 'Method : '.$data['method'], 'woo-wallet-withdrawal' ); echo " \n";?>

<?php _e( 'We\'ll transfer this amount to your preferred destination shortly.', 'woo-wallet-withdrawal' ); echo " \n";?>

<?php _e( 'Thanks for being with us.', 'woo-wallet-withdrawal' );  echo " \n";?>

<?php
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
