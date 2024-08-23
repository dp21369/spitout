<?php

/**
 * Template Name: Seller Order Process
 * @package spitout
 */

if (isset($_POST['proceed_to_checkout'])) {
    global $woocommerce;

    $get_product_ids = $_POST['selected_products'];
    foreach ($get_product_ids as $get_product_id) {
        $product_id = $get_product_id;

        // $product_cart_id = WC()->cart->generate_cart_id($product_id);
        // if (!WC()->cart->find_product_in_cart($product_cart_id)) {
        // Yep, the product with ID is NOT in the cart, let's add it then!
        WC()->cart->add_to_cart($product_id);
        //}
        // Redirect to the cart page
        wp_redirect(wc_get_cart_url());
    }
    //}
}
