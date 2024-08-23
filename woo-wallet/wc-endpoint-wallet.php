<?php

/**
 * The Template for displaying wallet recharge form
 *
 * This template can be overridden by copying it to yourtheme/woo-wallet/wc-endpoint-wallet.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author  Subrata Mal
 * @version     1.1.8
 * @package WooWallet
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

global $wp;
do_action('woo_wallet_before_my_wallet_content');
$is_rendred_from_myaccount = wc_post_content_has_shortcode('woo-wallet') ? false : is_account_page();
$menu_items = apply_filters(
    'woo_wallet_nav_menu_items',
    array(
        'top_up' => array(
            'title' => apply_filters('woo_wallet_account_topup_menu_title', __('Wallet topup', 'woo-wallet')),
            'url' => $is_rendred_from_myaccount ? esc_url(wc_get_endpoint_url(get_option('woocommerce_woo_wallet_endpoint', 'woo-wallet'), 'add', wc_get_page_permalink('myaccount'))) : add_query_arg('wallet_action', 'add'),
            'icon' => 'dashicons dashicons-plus-alt',
        ),
        'transfer' => array(
            'title' => apply_filters('woo_wallet_account_transfer_amount_menu_title', __('Wallet transfer', 'woo-wallet')),
            'url' => $is_rendred_from_myaccount ? esc_url(wc_get_endpoint_url(get_option('woocommerce_woo_wallet_endpoint', 'woo-wallet'), 'transfer', wc_get_page_permalink('myaccount'))) : add_query_arg('wallet_action', 'transfer'),
            'icon' => 'dashicons dashicons-randomize',
        ),
        'transaction_details' => array(
            'title' => apply_filters('woo_wallet_account_transaction_menu_title', __('Transactions', 'woo-wallet')),
            'url' => $is_rendred_from_myaccount ? esc_url(wc_get_account_endpoint_url(get_option('woocommerce_woo_wallet_transactions_endpoint', 'woo-wallet-transactions'))) : add_query_arg('wallet_action', 'view_transactions'),
            'icon' => 'dashicons dashicons-list-view',
        ),
    ),
    $is_rendred_from_myaccount
);
?>

<div class="woo-wallet-my-wallet-container">

    <div class="woo-wallet-sidebar">
        <div class="Blinking-container">
            <?php
            $get_bitcoin_rate = spitout_get_currency_exchange_btc_usd_rate();
            $final_formated_bit_rate = number_format($get_bitcoin_rate, 2, '.', ',');
            echo ' <h5 class="text-red blink-soft">1 Bitcoin (BTC) = $' . $final_formated_bit_rate . '</h5>';
            ?>

        </div>
        <h3 class="woo-wallet-sidebar-heading"><a href="<?php echo $is_rendred_from_myaccount ? esc_url(wc_get_account_endpoint_url(get_option('woocommerce_woo_wallet_endpoint', 'woo-wallet'))) : esc_url(get_permalink()); ?>">
                <?php echo apply_filters('woo_wallet_account_menu_title', __('My Wallet', 'woo-wallet')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
            </a></h3>
        <ul>
            <?php foreach ($menu_items as $item => $menu_item) : ?>
                <?php if (apply_filters('woo_wallet_is_enable_' . $item, true)) : ?>
                    <li class="card"><a href="<?php echo esc_url($menu_item['url']); ?>"><span class="<?php echo esc_attr($menu_item['icon']); ?>"></span>
                            <p>
                                <?php echo esc_html($menu_item['title']); ?>
                            </p>
                        </a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php do_action('woo_wallet_menu_items'); ?>
        </ul>
    </div>

    <div class="woo-wallet-content">
        <div class="woo-wallet-content-heading">
            <h3 class="woo-wallet-content-h3">
                <?php esc_html_e('Balance', 'woo-wallet'); ?>
            </h3>
            <!-- <h6 class="woo-wallet--original-price so-mobile-hide ">Original Balance</h6> -->
            <p class="woo-wallet-price">
                <?php echo woo_wallet()->wallet->get_wallet_balance(get_current_user_id()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?>
            </p>
        </div>
        <div style="clear: both"></div>
        <hr />
        <?php if ((isset($wp->query_vars['woo-wallet']) && !empty($wp->query_vars['woo-wallet'])) || isset($_GET['wallet_action'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended 
        ?>
            <?php if (apply_filters('woo_wallet_is_enable_top_up', true) && ((isset($wp->query_vars['woo-wallet']) && 'add' === $wp->query_vars['woo-wallet']) || (isset($_GET['wallet_action']) && 'add' === $_GET['wallet_action']))) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended 
            ?>

                <?php
                //$currency = $_COOKIE['wmc_current_currency'];
                $currency = $_COOKIE['wmc_current_currency'] ?? 'BTC';
                $get_currency_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();

                ?>
                <form method="post" action="">
                    <div class="woo-wallet-add-amount">
                        <label for="woo_wallet_balance_to_add">
                            <?php
                            $curr_currency = !isset($_COOKIE['wmc_current_currency']) ? 'BTC' : $_COOKIE['wmc_current_currency'];
                            esc_html_e('Enter amount in ' . $curr_currency, 'woo-wallet');
                            ?>
                        </label>
                        <?php
                        $min_amount = woo_wallet()->settings_api->get_option('min_topup_amount', '_wallet_settings_general', 0);
                        $max_amount = woo_wallet()->settings_api->get_option('max_topup_amount', '_wallet_settings_general', '');
                        ?>
                        <input type="number" data-current-currency="<?php echo $currency; ?>" data-currencyRate="<?php echo $get_currency_exchange_rate; ?>" step="0.01" min="<?php echo esc_attr($min_amount); ?>" max="<?php echo esc_attr($max_amount); ?>" name="woo_wallet_balance_to_add" id="woo_wallet_balance_to_add" class="woo-wallet-balance-to-add" required="" />
                        <?php wp_nonce_field('woo_wallet_topup', 'woo_wallet_topup'); ?>

                        <input type="submit" name="woo_add_to_wallet" class="woo-add-to-wallet" value="<?php esc_html_e('Add', 'woo-wallet'); ?>" />
                    </div>
                </form>
                <div id="woo-wallet-conversion" class="woo-wallet-conversion">

                </div>

            <?php } elseif (apply_filters('woo_wallet_is_enable_transfer', 'on' === woo_wallet()->settings_api->get_option('is_enable_wallet_transfer', '_wallet_settings_general', 'on')) && ((isset($wp->query_vars['woo-wallet']) && 'transfer' === $wp->query_vars['woo-wallet']) || (isset($_GET['wallet_action']) && 'transfer' === $_GET['wallet_action']))) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended 
            ?>
                <form method="post" action="" id="woo_wallet_transfer_form">
                    <p class="woo-wallet-field-container form-row form-row-wide">
                        <label for="woo_wallet_transfer_user_id">
                            <?php esc_html_e('Select whom to transfer', 'woo-wallet'); ?>
                            <?php
                            if (apply_filters('woo_wallet_user_search_exact_match', true)) {
                                esc_html_e('(Email)', 'woo-wallet');
                            }
                            ?>
                        </label>
                        <select name="woo_wallet_transfer_user_id" class="woo-wallet-select2" required=""></select>
                    </p>
                    <p class="woo-wallet-field-container form-row form-row-wide">
                        <label for="woo_wallet_transfer_amount">
                            <?php esc_html_e('Amount', 'woo-wallet'); ?>
                        </label>
                        <input type="number" step="0.01" min="<?php echo woo_wallet()->settings_api->get_option('min_transfer_amount', '_wallet_settings_general', 0); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                                ?>" name="woo_wallet_transfer_amount" required="" />
                    </p>
                    <p class="woo-wallet-field-container form-row form-row-wide">
                        <label for="woo_wallet_transfer_note">
                            <?php esc_html_e('What\'s this for', 'woo-wallet'); ?>
                        </label>
                        <textarea name="woo_wallet_transfer_note"></textarea>
                    </p>
                    <p class="woo-wallet-field-container form-row">
                        <?php wp_nonce_field('woo_wallet_transfer', 'woo_wallet_transfer'); ?>
                        <input type="submit" class="button" name="woo_wallet_transfer_fund" value="<?php esc_html_e('Proceed to transfer', 'woo-wallet'); ?>" />
                    </p>
                </form>
            <?php } ?>
            <?php do_action('woo_wallet_menu_content'); ?>
        <?php } elseif (apply_filters('woo_wallet_is_enable_transaction_details', true)) { ?>
            <?php $transactions = get_wallet_transactions(array('limit' => apply_filters('woo_wallet_transactions_count', 10)));
            //var_dump($transactions);

            ?>
            <?php if (!empty($transactions)) { ?>
                <ul class="woo-wallet-transactions-items">
                    <?php foreach ($transactions as $transaction) :
                        //echo $transaction->transaction_id;
                    ?>
                        <li>
                            <div>
                                <p>
                                    <?php echo $transaction->details; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                    ?>
                                </p>
                                <small>
                                    <?php echo wc_string_to_datetime($transaction->date)->date_i18n(wc_date_format()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                    ?>
                                </small>
                            </div>
                            <?php
                            $get_current_wallet_balance = get_user_meta(get_current_user_id(), '_current_woo_wallet_balance', true);
                            ?>
                            <div class="woo-wallet-transaction-type-<?php echo esc_attr($transaction->type); ?>">
                                <?php
                                echo '<div class="">';
                                echo 'credit' === $transaction->type ? '+' : '-';
                                $get_orgnal_amount = $transaction->amount;
                                $get_charge_percentage = get_option('amount_charge_percentage');
                                $final_charge_percentage =  $get_charge_percentage / 100;
                                $transaction_amount_after_charge = $get_orgnal_amount * $final_charge_percentage;
                                $final_total_tranc_amount = $get_orgnal_amount - $transaction_amount_after_charge;

                                $get_wallet_transation_charge = get_wallet_transaction_meta($transaction->transaction_id, '_wc_wallet_purchase_gateway_charge', true);
                                $get_wallet_transation_type = get_wallet_transaction_meta($transaction->transaction_id, '_type', true);
                                echo wc_price(apply_filters('woo_wallet_amount', $final_total_tranc_amount, $transaction->currency, $transaction->user_id), woo_wallet_wc_price_args($transaction->user_id));
                                // if ($transaction->type == 'credit') {
                                //     echo wc_price(apply_filters('woo_wallet_amount', $final_total_tranc_amount, $transaction->currency, $transaction->user_id), woo_wallet_wc_price_args($transaction->user_id));
                                // } else {
                                //     echo wc_price(apply_filters('woo_wallet_amount', $get_orgnal_amount, $transaction->currency, $transaction->user_id), woo_wallet_wc_price_args($transaction->user_id));
                                // }
                                echo '</div>';
                                echo '<div class="wallet-transction-balance">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                echo 'Balance:';
                                echo wc_price(apply_filters('woo_wallet_amount', $transaction->balance, $transaction->currency, $transaction->user_id), woo_wallet_wc_price_args($transaction->user_id));
                                echo '</div>';
                                ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
        <?php
            } else {
                esc_html_e('No transactions found', 'woo-wallet');
            }
        }
        ?>
    </div>
</div>
<?php
do_action('woo_wallet_after_my_wallet_content');
