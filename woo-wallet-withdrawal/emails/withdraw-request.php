<?php
/**
 * New Withdraw request Email.
 *
 * An email sent to the admin when a new withdraw request is created by user.
 *
 * @class       WOO_Wallet_Withdrawal_Request
 * @version     1.0.0
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p>
    <?php _e( 'Hi,', 'woo-wallet-withdrawal' ); ?>
</p>
<p>
    <?php _e( 'A new withdraw request has been made by', 'woo-wallet-withdrawal' ); ?> <?php echo $data ['username']; ?>.
</p>
<hr>
<ul>
    <li>
        <strong>
            <?php _e( 'Username : ', 'woo-wallet-withdrawal' ); ?>
        </strong>
        <?php 
        printf( '<a href="%s">%s</a>', $data['profile_url'], $data['username']  ); ?>
    </li>
    <li>
        <strong>
            <?php _e( 'Request Amount:', 'woo-wallet-withdrawal' ); ?>
        </strong>
        <?php echo $data['amount']; ?>
    </li>
    <li>
        <strong>
            <?php _e( 'Payment Method: ', 'woo-wallet-withdrawal' ); ?>
        </strong>
        <?php echo $data['method'] ?>
    </li>
</ul>

<?php echo sprintf( __( 'You can approve or deny it by going <a href="%s"> here </a>', 'woo-wallet-withdrawal' ), $data['withdraw_page'] ); ?>

<?php

do_action( 'woocommerce_email_footer', $email );
