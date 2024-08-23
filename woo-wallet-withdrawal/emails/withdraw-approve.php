<?php

/**
 * Withdraw request approved.
 *
 * An email sent to the user when a new withdraw request is approved.
 *
 * @class       WOO_Wallet_Withdrawal_Approved
 * @version     1.0.1
 * 
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_email_header', $email_heading, $email); ?>

<div class="dots">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<div class="email-intro">
    <p>
        <?php _e('Hi ' . $data['username'], 'woo-wallet-withdrawal'); ?>
    </p>
    <p>
        <?php _e('Congrats! Your withdraw request has been approved, congrats!', 'woo-wallet-withdrawal'); ?>
    </p>
    <p>
        <?php _e('You sent a withdraw request of:', 'woo-wallet-withdrawal'); ?>
        <br>
        <?php _e('Amount : ' . $data['amount'], 'woo-wallet-withdrawal'); ?>
        <br>
        <?php _e('Method : ' . $data['method'], 'woo-wallet-withdrawal'); ?>
    </p>
    <p>
        <?php _e('We\'ll transfer this amount to your preferred payment method shortly.', 'woo-wallet-withdrawal'); ?>

        <?php _e('Thanks for being with us.', 'woo-wallet-withdrawal'); ?>
    </p>
</div>
<div class="body-footer">
    <p>SpitOut: Natural Inimacy Uniquely Yours!</p>
</div>
<?php
do_action('woocommerce_email_footer', $email);
