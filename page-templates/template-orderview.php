<?php

/**
 * Template Name: Order view page
 * @package spitout
 */
get_header();

$get_order_id = $_GET['order_id'];


$order = wc_get_order($get_order_id);
// Get the Customer ID (User ID)
$customer_id = $order->get_customer_id(); // Or $order->get_user_id();
$order_status = $order->get_status();
$billing_first_name = $order->get_billing_first_name();
$billing_last_name = $order->get_billing_last_name();
$shipping_first_name = $order->get_shipping_first_name();
$shipping_last_name = $order->get_shipping_last_name();
$shipping_company = $order->get_shipping_company();
$shipping_address_1 = $order->get_billing_address_1();
$shipping_address_2 = $order->get_billing_address_2();
$shipping_city = $order->get_billing_city();
$shipping_state = $order->get_billing_state();
$shipping_postcode = $order->get_billing_postcode();
$shipping_country = $order->get_billing_country();
$shipping_email = $order->get_billing_email();
$order_date = $order->get_date_created();
$formatted_order_date = $order_date->date('Y-m-d H:i:s');
$completed_date = $order->get_date_completed();


$tracking_number = get_post_meta((int) $get_order_id, 'order_tracking_id', true);




if ($completed_date !== null) {
    $formatted_completed_date = $completed_date->format('Y-m-d H:i:s');
    // Use the formatted date as needed
} else {
    // Handle the case where the completed date is null
    // For example, you can assign a default value or display an error message
    $formatted_completed_date = 'Order Not Completed';
}

?>


<section class="order-single-bill so-feed-new-container m-auto">
    <div class="so-sc-nd-heading d-flex align-items-center mt-5">
        <a onclick="history.back()">
            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 100 100" height="20" viewBox="0 0 100 100" width="20">
                <path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
            </svg></a>
        <h5>
            Order
            <?php echo $get_order_id; ?>
        </h5>
        <?php if (($order_status == 'processing' || $order_status == 'on-hold') && !empty($tracking_number)) {
            echo '<a class="order-marked-as-recieved-view-order" title="mark as recieved" data-order-id="' . $get_order_id . '">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#e91e79" d="M19 19H5V5h10V3H5c-1.11 0-2 .89-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8h-2m-11.09-.92L6.5 11.5L11 16L21 6l-1.41-1.42L11 13.17l-3.09-3.09Z" />
                </svg></a>';
        } ?>


        <!-- <div class="so-sc-nd-heading-options" style="display:none">
            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                    <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                    <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                    <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                </svg>
            </a>
        </div> -->
    </div>
    <div class="so-sc-nd-order-details container">
        <div class="row">
            <!-- <div class="col-lg-12 d-flex align-items-center so-sc-nd-delivery-status nd-status-needs-delivery">
                <a href="#">
                    <p>Awaiting Shipment</p>
                </a>
                <h6 class="ml-auto font-weight-bold">Date: <span class="text-muted">08.06.2023</span></h6>
            </div> -->

        </div>
        <div class="row">
            <div class="col-lg-12">
                <h6 class="font-weight-bold">Order</h6>

            </div>
            <div class="col-lg-12">

                <div class="sales-product">
                    <?php
                    $wc_pice_symbol = get_woocommerce_currency_symbol();

                    foreach ($order->get_items() as $item_id => $item_data) {
                        $product_id = $item_data->get_product_id();
                        $product_name = $item_data->get_name(); // Get product name
                        $quantity = $item_data->get_quantity(); // Get product quantity
                        $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';
                        if(so_get_dynamic_currency() == "BTC"){
                            $final_price = $item_data->get_meta('_btc_price') * $quantity;
                        }else{
                            $final_price = $item_data->get_meta('_usd_price') * $quantity;
                        }

                        //     echo '<div class="single-bill-product-wrapper">';

                        //     echo ' <h5><span class="buyer_order_product">' . $product_name . ' </span><span class ="buyer_order_qty">' . $quantity . 'X
                        //     </span><span class="buyer_order_icon"><img src="' . $qtyicon . '" /></span>
                        // </h5>';
                        //     echo '</div>';

                        echo '
                        <div class="single-bill-product-wrapper">
                            <h5 class="font-weight-normal text-muted d-flex justify-content-start">
                                <span class="seller_order_product">
                                    ' . $product_name . '
                                </span>
                                <span class="seller_order_qty">' . $quantity . 'X </span>
                                <span class="seller_order_icon"><img src="' . $qtyicon . '"></span>
                                <h5> <span class="text-mßuted">' . so_get_dynamic_currency_symbol() . number_format($final_price,6) . '</span> </h5>
                            </h5>
                            
                        </div>';
                    }

                    echo '
                    <h6 class="ml-auto font-weight-bold">
                        Tracking Id: <span class="text-muted">' . $tracking_number . '</span><br>
                        Order Date: <span class="text-muted">' . $formatted_order_date . '</span><br>
                        Received Date: <span class="text-muted">' . $formatted_completed_date . '</span>
                    </h6>';
                    ?>
                </div>

            </div>
        </div>

        <div class="row d-none">
            <div class="col-lg-12 d-flex so-cancellation-date-notice">
                <i class="bi bi-exclamation-circle-fill"></i>
                <!-- <p>You need to send it by <span>10.06.2023</span> for it to be cancelled automatically</p> -->
                <p>Please ship within the next <span>72 hours</span> for it to be cancelled automatically</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h6 class="font-weight-bold">Address Delivery</h6>
            </div>
            <div class="so-sc-nd-address-details" style="width:100%">
                <div class="col-lg-12 d-flex">
                    <i class="bi bi-person-fill" style="font-size: 20px"></i>

                    <h6>
                        <?php echo $billing_first_name . ' ' . $billing_last_name; ?>
                    </h6>
                </div>
                <div class="col-lg-12 d-flex">

                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                    </svg>
                    <h6>
                        <?php echo $shipping_address_1 . ' ' . $shipping_address_2 . ' ' . $shipping_city; ?>
                    </h6>

                </div>
                <div class="col-lg-12 d-flex">
                    <i class="bi bi-envelope-fill"></i>
                    <h6>
                        <?php echo $shipping_email; ?>
                    </h6>
                </div>
            </div>
        </div>
    </div>
</section>




<?php
get_footer();
