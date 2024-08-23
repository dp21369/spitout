<?php

/**
 * The Template for displaying wallet recharge form
 *
 * This template can be overridden by copying it to yourtheme/woo-wallet-withdrawal/woo-wallet-withdrawal.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 	Subrata Mal
 * @version     1.0.0
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

?>
<div class="woocommerce-info"><?php _e('Current wallet balance: ', 'woo-wallet-withdrawal');
                                echo woo_wallet()->wallet->get_wallet_balance();
                                ?> <a style="float: right;" href="<?php echo is_account_page() ? esc_url(wc_get_account_endpoint_url(get_option('woocommerce_woo_wallet_endpoint', 'woo-wallet'))) : get_permalink(); ?>"><span class="dashicons dashicons-editor-break"></span></a>
    <div class="spitout-withdrawal-after-chargeable-amt">
        <?php
        $get_seller_wallet_balance = get_user_meta(get_current_user_id(), '_current_woo_wallet_balance', true);
        if (!empty($get_seller_wallet_balance)) {
            // get charge amount 
            $charge_amount_bitcoin = woo_wallet()->settings_api->get_option('_charge_' . 'crypto_bitcoin', '_wallet_settings_withdrawal', 0);


            /* Amount after charge deduction */

            $final_charge_bitcoin_percentage =  $charge_amount_bitcoin / 100;
            $withdraw_amount =  $final_charge_bitcoin_percentage * $get_seller_wallet_balance;
            $final_usd_withdrawal_amount = $get_seller_wallet_balance - $withdraw_amount;

            // get rate of current currency //get list currency
            $get_exchange_rate = spitout_get_currency_exchange_rate();

            $final_exchange_price = ($final_usd_withdrawal_amount * ($get_exchange_rate));
            $formatted_price = wc_price($final_exchange_price);
            echo ' <p>You will receive (' . $charge_amount_bitcoin . '% transaction fee): ' . $formatted_price . '</p>';
        }

        ?>
    </div>
</div>

<div id="woo-wallet-withdrawal-tabs">
    <ul>
        <li><a href="#ww-pending"><?php _e('Withdraw Request', 'woo-wallet-withdrawal'); ?></a></li>
        <li><a href="#ww-approved"><?php _e('Approved Requests', 'woo-wallet-withdrawal'); ?></a></li>
        <li><a href="#ww-cancelled"><?php _e('Cancelled Requests', 'woo-wallet-withdrawal'); ?></a></li>
        <li><a href="#ww-payment-settings"><?php _e('Payment Settings', 'woo-wallet-withdrawal'); ?></a></li>
    </ul>
    <div id="ww-pending">
        <?php woo_wallet_withdrawal()->get_template('tabs/ww-pending.php'); ?>
    </div>
    <div id="ww-approved">
        <?php woo_wallet_withdrawal()->get_template('tabs/ww-approved.php'); ?>
    </div>
    <div id="ww-cancelled">
        <?php woo_wallet_withdrawal()->get_template('tabs/ww-cancelled.php'); ?>
    </div>
    <div id="ww-payment-settings">
        <?php woo_wallet_withdrawal()->get_template('tabs/ww-payment-settings.php'); ?>
    </div>
</div>