<?php

function truncate_decimal_places($number, $decimal_places)
{
    // Convert number to string
    $number_str = strval($number);

    // Find the position of the decimal point
    $decimal_pos = strpos($number_str, '.');

    if ($decimal_pos !== false) {
        // Truncate the string to the desired decimal places
        $truncated_str = substr($number_str, 0, $decimal_pos + $decimal_places + 1);
        return $truncated_str;
    } else {
        return $number_str; // No decimal places found, return original number
    }
}
function so_has_more_than_six_decimal_places($number)
{
    $numberString = strval($number);
    if (strpos($numberString, '.') !== false) {
        $decimalPart = substr($numberString, strpos($numberString, '.') + 1);
        if (strlen($decimalPart) > 6) {
            return true;
        }
    }
    return false;
}

function so_get_dynamic_currency_symbol()
{
    $curr_currency = !isset($_COOKIE['wmc_current_currency']) ? 'BTC' : $_COOKIE['wmc_current_currency'];
    $currency_symbol = $curr_currency == "BTC" ? '฿' : '$';
    return $currency_symbol;
}

function so_get_dynamic_currency()
{
    $curr_currency = !isset($_COOKIE['wmc_current_currency']) ? 'BTC' : $_COOKIE['wmc_current_currency'];
    return $curr_currency;
}


function so_get_product_usd_price($product_id)
{
    return (float)((json_decode(get_post_meta((int) $product_id, '_regular_price_wmcp', true), true))['USD']);
}


function static_currency_generator_for_products($product_id, $use_current_exchange_rate = false)
{
    $btc_price = number_format((get_post_meta((int) $product_id, '_regular_price', true)), 6);
    $usd_price = so_get_product_usd_price($product_id);

    if ($use_current_exchange_rate) {
        $btc_price = truncate_decimal_places(number_format(($usd_price / spitout_get_currency_exchange_btc_usd_rate()), 6), 6);
        $curr_product = wc_get_product($product_id);
        $curr_product->set_regular_price($btc_price);
        $curr_product->save();
    }

    // echo "||||".$btc_price."||||";
    // echo "||||".$usd_price."||||".$btc_price."||||".($usd_price / spitout_get_currency_exchange_btc_usd_rate())."||||";

    return '
        <span class="custom-bdi">$' . $usd_price . '</span>' .
        ' <strong>|</strong> ' .
        '<span class="custom-bdi">฿' . $btc_price . '</span>';
}


function dynamic_currency_generator_for_products($product_id)
{
    $btc_price = number_format((get_post_meta((int) $product_id, '_regular_price', true)), 6);
    $usd_price = so_get_product_usd_price($product_id);

    if (so_get_dynamic_currency() == 'BTC') {
        return so_get_dynamic_currency_symbol() . truncate_decimal_places(number_format($btc_price, 6), 6);
    } else {
        return so_get_dynamic_currency_symbol() . $usd_price;
    }
}

function dynamic_currency_for_buyer_seller_order_pages($order_id, $include_currency = true)
{
    $order = wc_get_order($order_id);
    $curr_price = 0;

    foreach ($order->get_items() as $item) {
        $quantity = $item->get_quantity();
        $btc_price = $item->get_meta('_btc_price');
        $usd_price = $item->get_meta('_usd_price');

        if (so_get_dynamic_currency() == 'BTC') {
            $curr_price += (float)$btc_price * $quantity;
        } else {
            $curr_price += (float)$usd_price * $quantity;
        }
    }

    if (!$include_currency) {
        // echo '|||'.$curr_price.'|||';
        // echo '|||'.number_format($curr_price, 6).'|||';
        // echo '|||'.truncate_decimal_places(number_format($curr_price, 6), 6).'|||';
        return truncate_decimal_places(number_format($curr_price, 6), 6);
    }
    return so_get_dynamic_currency_symbol() . truncate_decimal_places(number_format($curr_price, 6), 6);
}

function so_is_topup_product()
{
    $curr_product_id = 0;
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        if (!empty($product)) {
            $curr_product_id = $product->get_id();
        }
    }

    $recharble_product_wallet_product_id = get_wallet_rechargeable_product()->get_id();
    if ((int) $curr_product_id == (int) $recharble_product_wallet_product_id) {
        return true;
    }

    return false;
}


function so_is_order_topup_product($order_id)
{
    $order = wc_get_order($order_id);
    $curr_product_id = 0;
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        $curr_product_id = $product_id;
    }


    $recharble_product_wallet_product_id = get_wallet_rechargeable_product()->get_id();
    if ((int) $curr_product_id == (int) $recharble_product_wallet_product_id) {
        return true;
    }

    return false;
}



// add_action('woocommerce_thankyou', 'so_update_order_data', 99, 1);
// function so_update_order_data($order_id)
// {

//     if (so_is_order_topup_product($order_id)) return;

//     //calculate order total
//     $order = wc_get_order($order_id);
//     $product_arr = [];

//     foreach ($order->get_items() as $item) {

//         $product_id = $item->get_product_id();
//         $product = wc_get_product($product_id);

//         if (!$product) continue;

//         $btc_price = number_format((get_post_meta((int) $product_id, '_regular_price', true)), 6);
//         $usd_price = so_get_product_usd_price($product_id);
//         $quantity = $item->get_quantity();
//         $product_arr += [
//             $product_id => [
//                 'btc_unit_price' => $btc_price, 
//                 'usd_unit_price' => $usd_price,
//                 'quantity' => $quantity
//             ]
//         ];
//     }

//     $order->update_meta_data('so_order_metadata', $product_arr);
//     $order->save();
// }




add_action('woocommerce_add_order_item_meta', 'add_custom_order_item_meta', 10, 2);
function add_custom_order_item_meta($item_id, $values)
{
    if ($values['product_id'] == get_wallet_rechargeable_product()->get_id()) return;

    if (!isset($_COOKIE['curr-exchange-rate'])) {

    ?>
        <script>
            function createCookiee(name, value, minutes) {
                var expires;
                if (minutes) {
                    var date = new Date();
                    date.setTime(date.getTime() + (minutes * 60 * 1000));
                    expires = "; expires=" + date.toGMTString();
                } else {
                    expires = "";
                }
                document.cookie = name + "=" + value + expires + "; path=/";
            }
            createCookiee("curr-exchange-rate", jQuery('.curr-exchange-rate').val(), 10);
        </script>

        <?php
        $_COOKIE['curr-exchange-rate'] = spitout_get_currency_exchange_btc_usd_rate();
    }

    $curr_exchange_rate = (float) $_COOKIE['curr-exchange-rate'];

    $product_id = $values['product_id'];

    // $btc_price = number_format((get_post_meta((int) $product_id, '_regular_price', true)), 6);
    $usd_price = so_get_product_usd_price($product_id);
    $btc_price = truncate_decimal_places((number_format(($usd_price/$curr_exchange_rate), 6)),6);

    wc_add_order_item_meta($item_id, '_btc_price', $btc_price);
    wc_add_order_item_meta($item_id, '_usd_price', $usd_price);
}


// function add_custom_order_item_meta($item_id, $values) {

    
//     if ($values['product_id'] == get_wallet_rechargeable_product()->get_id()) return;
    
//     $product_id = $values['product_id'];
//     $curr_rate = $_COOKIE['wmc_current_currency'];

//     $usd_price = so_get_product_usd_price($product_id);
//     $btc_price = ((float)$usd_price/(float)$curr_rate);

//     wc_add_order_item_meta($item_id, 'btc_price', $btc_price);
//     wc_add_order_item_meta($item_id, 'usd_price', $usd_price);

// }