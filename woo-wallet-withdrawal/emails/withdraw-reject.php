<?php

/**
 * Withdraw request cancelled Email.
 *
 * An email sent to the vendor when a new withdraw request is cancelled by admin.
 *
 * @version    1.0.1
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
        <?php _e('Your withdraw request has been cancelled!', 'woo-wallet-withdrawal'); ?>
    </p>
    <p>
        <?php _e('You sent a withdraw request of:', 'woo-wallet-withdrawal'); ?>
        <br>
        <?php _e('Amount : ' . $data['amount'], 'woo-wallet-withdrawal'); ?>
        <br>
        <?php _e('Method : ' . $data['method'], 'woo-wallet-withdrawal'); ?>
    </p>
</div>
<div class="body-footer">
    <p>SpitOut: Natural Inimacy Uniquely Yours!</p>
</div>
<?php
do_action('woocommerce_email_footer', $email);
