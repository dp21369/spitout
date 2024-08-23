<?php
/**
 * New Withdraw request Email.
 *
 * An email sent to the admin when a new withdraw request is created by vendor.
 *
 * @version     1.0.1
 * 
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
echo "= " . $email_heading . " =\n\n";
?>
<?php _e( 'Hi,', 'woo-wallet-withdrawal' );  echo " \n";?>

<?php _e( 'A new withdraw request has been made by - '.$data['username'], 'woo-wallet-withdrawal' );  echo " \n";?>

<?php _e( 'Request Amount : '.$data['amount'], 'woo-wallet-withdrawal' );  echo " \n";?>
<?php _e( 'Payment Method : '.$data['method'], 'woo-wallet-withdrawal' );  echo " \n";?>

<?php _e( 'Username : '.$data['username'], 'woo-wallet-withdrawal' );  echo " \n";?>
<?php _e( 'Profile : '.$data['profile_url'], 'woo-wallet-withdrawal' );  echo " \n";?>

<?php _e( 'You can approve or deny it by going here : '.$data['withdraw_page'], 'woo-wallet-withdrawal' );  echo " \n";?>

<?php
echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
