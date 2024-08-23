<?php

/**
 * Template Name: Sales-confirm page
 * @package spitout
 */

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

get_header();

function so_empty_orders_placeholder($content)
{
    return '
    <div class="row justify-content-center mt-0">
        <div class="col-lg-12">
            <div class=" px-0 pb-0  mb-3">

                <div class="row">
                    <div class="col-md-12 mx-0">
                        <div id="msform">
                            <fieldset id="so-verify-failed">
                                <div class="form-card register-step-completed so-registration-failed">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                                viewBox="0 0 48 48">
                                                <g fill="#ef4444">
                                                    <path
                                                        d="M31.424 38.177A15.93 15.93 0 0 1 24 40c-8.837 0-16-7.163-16-16S15.163 8 24 8s16 7.163 16 16c0 .167-.003.334-.008.5h2.001c.005-.166.007-.333.007-.5c0-9.941-8.059-18-18-18S6 14.059 6 24s8.059 18 18 18a17.92 17.92 0 0 0 8.379-2.065l-.954-1.758Z" />
                                                    <path
                                                        d="M13.743 23.35c-.12.738.381 1.445 1.064 1.883c.714.457 1.732.707 2.93.53a3.794 3.794 0 0 0 2.654-1.665c.504-.764.711-1.693.48-2.382a.5.5 0 0 0-.818-.203c-1.796 1.704-3.824 2.123-5.643 1.448a.5.5 0 0 0-.667.39Zm20.076 0c.119.738-.382 1.445-1.065 1.883c-.714.457-1.731.707-2.93.53a3.794 3.794 0 0 1-2.653-1.665c-.504-.764-.712-1.693-.48-2.382a.5.5 0 0 1 .818-.203c1.796 1.704 3.824 2.123 5.642 1.448a.5.5 0 0 1 .668.39ZM40 32a4 4 0 0 1-8 0c0-3.5 4-7 4-7s4 3.5 4 7Zm-19.2 1.6c1.6-2.133 4.8-2.133 6.4 0a1 1 0 0 0 1.6-1.2c-2.4-3.2-7.2-3.2-9.6 0a1 1 0 0 0 1.6 1.2Z" />
                                                </g>
                                            </svg>
                                        </div>
                                        <div class="col-lg-12 reg-failed-title">
                                            <i class="bi bi-x-circle"></i>
                                            <h4>No orders</h4>
                                        </div>
                                        <div class="col-lg-12">
                                            <h5></h5>
                                        </div
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
}


if (is_user_logged_in()) {
    $allowed_roles = array('seller'); // Replace 'custom_role' with your role slug
    $user = wp_get_current_user();
    $dynamic_price_symbol = so_get_dynamic_currency_symbol();
    $curr_exchange_rate = (float) $_COOKIE['curr-exchange-rate'];

    if (array_intersect($allowed_roles, $user->roles)) {


        $current_user = get_current_user_id();

        $get_current_seller_info = spitout_get_seller_information($current_user);
        $get_seller_name = $get_current_seller_info['seller_display_name'];
        $get_seller_location = $get_current_seller_info['seller_location'];
        // $get_seller_image = $get_current_seller_info['seller_profile_img'];
        $attachment_id = (int) get_user_meta($current_user, 'so_profile_img', true);
        $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
        if ($attachment_array) {
            $get_seller_image = $attachment_array[0]; // URL of the thumbnail image 
        }


        /* if the author avatar is empty it assign a placeholder image */
        if (empty($get_seller_image)) {
            $get_seller_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
        }


        // $total_earnings = isset($total_earnings) ? $total_earnings : 0;
        // $total_sales = isset($total_sales) ? $total_sales : 0;
        // $average_sales = isset($average_sales) ? $average_sales : 0;


        // $seller_sales_info['total_earnings'] = $total_earnings;
        // $seller_sales_info['total_sales'] = $total_sales;
        // $seller_sales_info['average_sales'] = $average_sales;
        // Replace 'user_id_here' with the actual user ID of the seller
        $seller_id = $current_user;
        $seller_totals = get_seller_totals($seller_id);

        $get_total_earning = 0;
        $get_seller_total_sales = 0;
        $get_seller_average = 0;
        if (is_array($seller_totals)) {
            $get_total_earning = $seller_totals['total_earnings'];
            $get_seller_total_sales = $seller_totals['total_sales'];
            $get_seller_average = $seller_totals['average_sales'];
        }
        $wc_pice_symbol = get_woocommerce_currency_symbol();

        // function get_single_seller_id($str)
        // {
        //     $unserialized = @unserialize($str);
        //     return $unserialized !== false && is_array($unserialized);
        // }
        /* needs delivery orders */

        // var_dump($seller_id);
        $needs_delivery_args = array(
            'limit' => -1,
            'meta_key' => 'seller_id',
            'meta_value' => $seller_id,
            'meta_compare' => 'LIKE',
            'return' => 'ids',
            'status' => 'on-hold'
        );

        $needs_delivery_orders = wc_get_orders($needs_delivery_args);
        // var_dump($needs_delivery_orders);

        /* awaiting confirmation  orders */

        $await_args = array(
            'limit' => -1,
            'meta_key' => 'seller_id',
            'meta_value' => $seller_id,
            'meta_compare' => 'LIKE',
            'return' => 'ids',
            'status' => 'processing'
        );

        $awaiting_confirmation_orders = wc_get_orders($await_args);


        $all_sales_args = array(
            'limit' => -1,
            'meta_key' => 'seller_id',
            'meta_value' => $seller_id,
            'meta_compare' => 'LIKE',
            'return' => 'ids',
        );

        $all_sales_orders = wc_get_orders($all_sales_args);


        /* completion orders */

        $complete_args = array(
            'limit' => -1,
            'meta_key' => 'seller_id',
            'meta_value' => $seller_id,
            'meta_compare' => 'LIKE',
            'return' => 'ids',
            'status' => 'completed'
        );

        $completion_orders = wc_get_orders($complete_args);


        // $my_products = wc_get_products(
        //     array(
        //         'status' => 'publish',
        //         'limit' => -1,
        //         'author' => $seller_id

        //     )
        // );


        /* set order tracking id and set order to processing to on-hold status */
        $sumbit_message = "";

        //XXXXX
        // if (isset($_POST['send-order-to-hold'])) {

        //     $needs_delivery_o_id = $_POST['order-id'];
        //     $needs_delivery_tracking_id = $_POST['tracking-num'];

        //     $order = new WC_Order($needs_delivery_o_id);
        //     $order->update_status('processing');
        //     update_post_meta($needs_delivery_o_id, 'order_tracking_id', $needs_delivery_tracking_id);

        //     $sumbit_message = 'Order ID:' . $needs_delivery_o_id . ' moved to Shipped Tab';
        // }


        /* set complete order from processing to completed */
        $sumbit_completed_message = "";
        if (isset($_POST['complete_order'])) {
            //var_dump($_POST);

            $awaiting_confirmation_o_id = $_POST['order_id'];


            $order = new WC_Order($awaiting_confirmation_o_id);
            $order->update_status('completed');

            $sumbit_completed_message = 'Order ID:' . $awaiting_confirmation_o_id . ' Moved to Awating Completion Tab';
            // wc_add_order_item_meta($needs_delivery_o_id, 'order_tracking_id', $needs_delivery_tracking_id);
        }


        ?>
        <section class="so-sales-view-confirm-page so-feed-new-container">

            <div class="so-sales-view-confirm-page-heading row" id="sales-page-tabs">
                <div class="col-lg-12 m-3">
                    <h4>Sales</h4>
                </div>
                <div class="col-lg-12 so-sales-view-confirm-page-tabs">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex" id="pills-all-sales" data-toggle="pill" data-target="#pills-all-sales" type="button" role="tab" aria-controls="pills-all-sales" aria-selected="true">
                                <h6>All</h6>
                                <div class="sales-confirm-nd-count">
                                    <p>
                                        <?php //echo count($needs_delivery_orders); 
                                                ?>
                                    </p>
                                </div>
                            </button>
                        </li> -->
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex" id="pills-need-delivery-tab" data-toggle="pill"
                                data-target="#pills-need-delivery" type="button" role="tab" aria-controls="pills-need-delivery"
                                aria-selected="true">
                                <h6>Awaiting Shipment</h6>
                                <div class="sales-confirm-nd-count">
                                    <p>
                                        <?php echo count($needs_delivery_orders); ?>
                                    </p>
                                </div>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-awaiting-confirmation-tab" data-toggle="pill"
                                data-target="#pills-awaiting-confirmation" type="button" role="tab"
                                aria-controls="pills-awaiting-confirmation" aria-selected="false">
                                <h6>Shipped</h6>
                                <div class="sales-confirm-ac-count">
                                    <p>
                                        <?php echo count($awaiting_confirmation_orders); ?>
                                    </p>
                                </div>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-completion-tab" data-toggle="pill"
                                data-target="#pills-completion" type="button" role="tab" aria-controls="pills-completion"
                                aria-selected="false">
                                <h6>Completed</h6>
                                <div class="sales-confirm-compl-count">
                                    <p>
                                        <?php echo count($completion_orders); ?>
                                    </p>
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="show-message-section">
                    <?php echo $sumbit_message; ?>
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">





                <div class="tab-pane fade show active so-sc-need-delivery" id="pills-need-delivery" role="tabpanel"
                    aria-labelledby="pills-need-delivery-tab">
                    <!-- seller sales page lists============================== -->
                    <section class="sales-list-page">
                        <?php

                        //empty placeholder
                        if (empty($needs_delivery_orders)) {
                            echo so_empty_orders_placeholder('There are currently no orders that need shipment.');
                        }

                        $awaiting_shipment_count = 0;

                        foreach ($needs_delivery_orders as $key => $order) {
                            # code...
                            $order_info = wc_get_order($order);
                            // Check if the order object exists
                            if ($order) {

                                $awaiting_shipment_count++;
                                $customer_id = $order_info->get_customer_id();
                                $customer_name = $order_info->get_billing_first_name() . ' ' . $order_info->get_billing_last_name();
                                $purchase_date = $order_info->get_date_created()->format('Y-m-d H:i:s');

                                // Get order amount
                                // $order_amount = $order_info->get_total();
                                if ($customer_id) {
                                    $get_current_seller_info = spitout_get_seller_information($customer_id);
                                    $get_customer_name = $get_current_seller_info['seller_display_name'];
                                    $get_customer_location = $get_current_seller_info['seller_location'];
                                    $seller_url = $get_current_seller_info['seller_url'];
                                    // $get_customer_image = $get_current_seller_info['seller_profile_img'];
                                    $attachment_id = (int) get_user_meta($customer_id, 'so_profile_img', true);
                                    $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                    if ($attachment_array) {
                                        $get_customer_image = $attachment_array[0]; // URL of the thumbnail image 
                                    }

                                    /* if the author avatar is empty it assign a placeholder image */
                                    if (empty($get_customer_image)) {
                                        $get_customer_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                    }

                                    echo '
                            
                               <div class="container so-feed-new-container so-sales-lists mt-5">
                            <div class="row">
                                <div class="col-lg-12 d-flex align-items-center nd-status-needs-delivery so-sc-nd-sales-single-order-status">
                                    <a href="#">
                                        <p>Awaiting Shipment</p>
                                    </a>
                                    <a href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                                            <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                                            <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                                            <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                                        </svg>
                                    </a>
                                    <h6 class="ml-auto font-weight-bold">Tracking Id: <span class="text-muted font-weight-normal">' . get_post_meta((int) $order, 'order_tracking_id', true) . '</span>
                                                </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered-single so-sc-nd-products-ordered-lists">
                                <div class="sales-product">
                                ';

                                    foreach ($order_info->get_items() as $item_id => $item_data) {
                                        $product_name = $item_data->get_name(); // Get product name
                                        $quantity = $item_data->get_quantity(); // Get product quantity\\
                                        $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';

                                        echo '
                                     <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                        </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                    </h5>';
                                    }

                                    echo '</div>
                                                    <h6 class="ml-auto font-weight-bold">Order ID: <span class="text-muted font-weight-normal">' . $order . '</span>
                                                    </h6>
                                                </div>
                                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered-lists-price">
                                                    <h5 class="static-price-currency">' . dynamic_currency_for_buyer_seller_order_pages($order) . '</h5>
                                                    <h6 class="ml-auto font-weight-bold">Date: <span class="text-muted font-weight-normal">' . $purchase_date . '</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 so-sc-nd-single-buyer-details d-flex align-items-center">
                                                    <figure>
                                                        <img src="' . $get_customer_image . '" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </figure>
                                                    <a href="' . $seller_url . '" target="_blank">
                                                        <h5>' . $get_customer_name . '</h5>
                                                    </a>
                                                    <a href="/spitout/chat/?uid=' . $customer_id . '"> 
                                                    <i class="bi bi-chat-left-dots-fill"> </i> </a>
                                                    <a href="#sales-page-tabs" class="ml-auto so-nd-single-confirm-order" id="single-confirm-order-' . $order . '">
                                                        <p class="font-weight-bold">View Order</p>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                            }
                            
                        }

                        if($awaiting_shipment_count == 0){
                            echo '<div class="so-error-msg"> 
                                No any Needs Delivery order found.
                            </div>';
                        }
                        ?>
                        <!-- first sales detail -->
                    </section>



                    <?php


                    foreach ($needs_delivery_orders as $key => $order) {
                        # code...
                        $order_info = wc_get_order($order);

                        // var_dump($order_info->meta_data('order_tracking_id') );
                        // var_dump(get_post_meta(1352, 'order_tracking_id', true) );
                        // Check if the order object exists
                        if ($order) {
                            // Get customer information
            
                            $customer_id = $order_info->get_customer_id();


                            $customer_name = $order_info->get_billing_first_name() . ' ' . $order_info->get_billing_last_name();

                            // Get purchase date
                            $purchase_date = $order_info->get_date_created()->format('Y-m-d H:i:s');

                            // Get order amount
                            $order_amount = $order_info->get_total();
                            $order_data = $order_info->get_data();
                            if ($customer_id) {
                                $get_current_seller_info = spitout_get_seller_information($customer_id);
                                $get_customer_name = $get_current_seller_info['seller_display_name'];
                                $order_billing_email = $order_data['billing']['email'];
                                $get_customer_location = $get_current_seller_info['seller_location'];
                                $seller_url = $get_current_seller_info['seller_url'];
                                // $get_customer_image = $get_current_seller_info['seller_profile_img'];
                                $attachment_id = (int) get_user_meta($customer_id, 'so_profile_img', true);
                                $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                if ($attachment_array) {
                                    $get_customer_image = $attachment_array[0]; // URL of the thumbnail image 
                                }


                                /* if the author avatar is empty it assign a placeholder image */
                                if (empty($get_customer_image)) {
                                    $get_customer_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                }
                                $order_billing_address_1 = $order_data['billing']['address_1'];
                                $order_billing_address_2 = $order_data['billing']['address_2'];
                                $order_billing_city = $order_data['billing']['city'];
                                $order_billing_state = $order_data['billing']['state'];
                                $order_billing_postcode = $order_data['billing']['postcode'];
                                $order_billing_country = $order_data['billing']['country'];
                                // $final_delivery_address = $order_billing_country . ',' . $order_billing_city . ',' . $order_billing_address_1 . ',' . $order_billing_postcode;
                                $final_delivery_address =  $order_billing_address_1 . ', ' .$order_billing_city . ', '.$order_billing_country . ' ' . $order_billing_postcode;




                                echo '

                            <section class="single-sale-bill" id="single-bill-section-' . $order . '">
                        <div class="so-sc-nd-heading d-flex align-items-center mt-5">
                            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 100 100" height="20" viewBox="0 0 100 100" width="20">
                                <path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                            </svg>
                            <h5>Order ' . $order . '</h5>
                            <div class="so-sc-nd-heading-options" style="display:none">
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                                        <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                                        <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="so-sc-nd-order-details container">
                            <div class="row">
                                <div class="col-lg-12 d-flex align-items-center so-sc-nd-delivery-status nd-status-needs-delivery">
                                    <a href="#">
                                        <p>Awaiting Shipment</p>
                                    </a>
                                    <h6 class="ml-auto font-weight-bold">Date: <span class="text-muted">' . $purchase_date . '</span></h6>
                                    <h6 class="ml-auto font-weight-bold col-lg-12">
                                        Tracking Id: <span class="text-muted font-weight-normal">' . get_post_meta((int) $order, 'order_tracking_id', true) . '</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="font-weight-bold">Order</h6>
                                </div>

                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered">
                                  <div class="sales-product">
                                ';



                                // foreach ($order_info->get_items() as $item_id => $item_data) {
                                //     $product_name = $item_data->get_name(); // Get product name
                                //     $quantity = $item_data->get_quantity(); // Get product quantity\\
                                //     $get_price = $item_data->get_total();

                                //     $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';


                                //     echo '<div class="single-bill-product-wrapper"> <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                //             </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                //         </h5>
                                //         <h5 class="ml-auto"> <span class="text-muted">' . $wc_pice_symbol . $get_price . '</span> </h5> </div>';
                                // }



                                foreach ($order_info->get_items() as $item_id => $item_data) {
                                    $product_id = $item_data->get_product_id();
                                    $product = wc_get_product($product_id);
                                    $product_name = $item_data->get_name(); // Get product name
                                    $quantity = $item_data->get_quantity(); // Get product quantity\\
                                    // $get_price = $item_data->get_total();
                                    // $product_price = truncate_decimal_places(($product->get_price() * $quantity),6);
                                    
                                    if(so_get_dynamic_currency() == "BTC"){
                                        $final_price = $item_data->get_meta('_btc_price') * $quantity;
                                    }else{
                                        $final_price = $item_data->get_meta('_usd_price') * $quantity;
                                    }

                                    $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';

                                    echo '<div class="single-bill-product-wrapper"> <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                            </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                        </h5>
                                        <h5 class="ml-auto"> <span class="text-muted">' . so_get_dynamic_currency_symbol() . number_format($final_price,6) . '</span> </h5> </div>';
                                }



                                echo ' </div></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 d-flex so-cancellation-date-notice px-3">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                 
                                    <p>Please ship within the next <span>72 </span> hours</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="font-weight-bold">Address Delivery</h6>
                                </div>
                                <div class="so-sc-nd-address-details" style="width:100%">
                                    <div class="col-lg-12 d-flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                        </svg>
                                        <h6>' . $final_delivery_address . '</h6>
                                    </div>
                                    <div class="col-lg-12 d-flex">
                                        <i class="bi bi-envelope-fill"></i>
                                        <h6>' . $order_billing_email . '</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="font-weight-bold">Buyer</h6>
                                </div>
                                <div class="col-lg-12 so-sc-nd-buyer-details d-flex align-items-center">
                                    <figure>
                                        <img src="' . $get_customer_image . '" alt="' . $get_customer_name . '" style="width: 100%; height: 100%; object-fit: cover;">
                                    </figure>
                                    <a href="' . $seller_url . '" target="_blank">
                                        <h5>' . $get_customer_name . '</h5>
                                    </a>
                                    <a href="#" class="ml-auto d-none">
                                        <p class="font-weight-bold">Send Message</p>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <p class="font-weight-bold">Add Tracking Number </p>
                                    <button id="generate-tracking-num" class="d-none">Generate</button>
                                </div>
                                <div class="col-lg-12 so-sc-nd-tracking-num">
                                    <form class="update-tracking-num" method="post">
                                        <input type="text" class="update-order-tracking_num" name="tracking-num" id="tracking-num" value="' . $order_info->get_meta('order_tracking_id') . '" required>
                                        <input type="hidden" class="update-order-order-id" name="order-id" value="' . $order . '">
                                        <p class="text-muted">Specify the service and tracking number to confirm the sale</p>
                                        <button name="send-order-to-hold" type="submit" class="mt-3 confirm-tracking-num" data-sender-id="' . $seller_id . '" data-receiver-id="' . $customer_id . '">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </section>
                            ';
                            }
                        }
                    }


                    foreach ($needs_delivery_orders as $key => $order) {
                        # code...
                        $order_info = wc_get_order($order);
                        // Check if the order object exists
                        if ($order) { ?>

                            <script>
                                jQuery("#single-confirm-order-<?php echo $order; ?>").on('click', function (event) {
                                    event.preventDefault(); // Prevent the default behavior of the link

                                    jQuery("#pills-need-delivery .sales-list-page").hide(); // Hide the sales-lists-page
                                    jQuery("#single-bill-section-<?php echo $order; ?>").show();
                                    jQuery("html, body").animate({
                                        scrollTop: 0
                                    }, "fast");
                                });
                                jQuery(".so-sc-nd-heading svg").on('click', function (event) {
                                    event.preventDefault(); // Prevent the default behavior of the link
                                    jQuery(".sales-list-page").show(); // Hide the sales-lists-page
                                    jQuery("#single-bill-section-<?php echo $order; ?>").hide(); // Show the single-sale-bill
                                    jQuery("html, body").animate({
                                        scrollTop: 0
                                    }, "fast");
                                });
                            </script>

                            <?php
                        }
                    }

                    ?>

                </div>





                <div class="tab-pane fade" id="pills-awaiting-confirmation" role="tabpanel"
                    aria-labelledby="pills-awaiting-confirmation-tab">

                    <section class="sales-list-page">

                        <?php
                        //empty placeholder
                        if (empty($awaiting_confirmation_orders)) {
                            echo so_empty_orders_placeholder('All orders have been received.');
                        }

                        if ($awaiting_confirmation_orders) {
                            foreach ($awaiting_confirmation_orders as $key => $order) {
                                # code...
                                $order_info = wc_get_order($order);
                                // Check if the order object exists
                                if ($order) {
                                    // Get customer information
                
                                    $customer_id = $order_info->get_customer_id();


                                    $customer_name = $order_info->get_billing_first_name() . ' ' . $order_info->get_billing_last_name();

                                    // Get purchase date
                                    $purchase_date = $order_info->get_date_created()->format('Y-m-d H:i:s');

                                    // Get order amount
                                    $order_amount = $order_info->get_total();
                                    if ($customer_id) {
                                        $get_current_seller_info = spitout_get_seller_information($customer_id);
                                        $get_customer_name = $get_current_seller_info['seller_display_name'];
                                        $get_customer_location = $get_current_seller_info['seller_location'];
                                        $seller_url = $get_current_seller_info['seller_url'];
                                        // $get_customer_image = $get_current_seller_info['seller_profile_img'];
                                        $attachment_id = (int) get_user_meta($customer_id, 'so_profile_img', true);
                                        $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                        if ($attachment_array) {
                                            $get_customer_image = $attachment_array[0]; // URL of the thumbnail image 
                                        }


                                        /* if the author avatar is empty it assign a placeholder image */
                                        if (empty($get_customer_image)) {
                                            $get_customer_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                        }

                                        echo '
                            
                               <div class="container so-feed-new-container so-sales-lists mt-5">
                            <div class="row">
                                <div class="col-lg-12 d-flex align-items-center nd-status-needs-processing so-sc-nd-sales-single-order-status">
                                    <a>
                                        <p>Shipped</p>
                                    </a>
                                    <a href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                                            <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                                            <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                                            <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                                        </svg>
                                    </a>
                                    <h6 class="ml-auto font-weight-bold">Tracking Id: <span class="text-muted font-weight-normal">' . get_post_meta((int) $order, 'order_tracking_id', true) . '</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered-single so-sc-nd-products-ordered-lists">
                                <div class="sales-product">
                                ';

                                        foreach ($order_info->get_items() as $item_id => $item_data) {
                                            $product_name = $item_data->get_name(); // Get product name
                                            $quantity = $item_data->get_quantity(); // Get product quantity\\
                                            $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';

                                            echo ' <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                        </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                    </h5>';
                                        }

                                        echo '</div>
                                    <h6 class="ml-auto font-weight-bold">Order ID: <span class="text-muted font-weight-normal">' . $order . '</span>
                                    </h6>
                                    
                                </div>
                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered-lists-price">
                                    <h5>' . dynamic_currency_for_buyer_seller_order_pages($order) . '</h5>
                                    <h6 class="ml-auto font-weight-bold">Date: <span class="text-muted font-weight-normal">' . $purchase_date . '</span>
                                    </h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 so-sc-nd-single-buyer-details d-flex align-items-center">
                                    <figure>
                                        <img src="' . $get_customer_image . '" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                                    </figure>
                                    <a href="' . $seller_url . '" target="_blank">
                                        <h5>' . $get_customer_name . '</h5>
                                    </a>

                                    <form method="post">
                                    <input type="hidden" name="order_id" value="' . $order . '">
                                    <input class="ml-auto so-nd-single-confirm-order d-none" type="submit" name="complete_order" value="Complete Order">
                                    </form>

                                    <a href="#sales-page-tabs" class="ml-auto so-nd-single-confirm-order" id="single-shipped-order-' . $order . '">
                                        <p class="font-weight-bold">View Order</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                            
                            ';
                                    }
                                } else {
                                    echo "No any Needs Delivery order found.";
                                }
                            }
                        } else {
                            ?>
                            <!-- <div class="so-error-msg"> -->
                                <?php
                                //echo "There is No Awating Confirmation Order"; ?>
                            <!-- </div> -->
                            <?php
                        }
                        ?>
                        <!-- first sales detail -->
                    </section>



                    <?php

                    foreach ($awaiting_confirmation_orders as $key => $order) {
                        # code...
                        $order_info = wc_get_order($order);
                        // Check if the order object exists
                        if ($order) {
                            // Get customer information
                            $customer_id = $order_info->get_customer_id();

                            $customer_name = $order_info->get_billing_first_name() . ' ' . $order_info->get_billing_last_name();

                            // Get purchase date
                            $purchase_date = $order_info->get_date_created()->format('Y-m-d H:i:s');

                            // Get order amount
                            $order_amount = $order_info->get_total();
                            $order_data = $order_info->get_data();
                            if ($customer_id) {
                                $get_current_seller_info = spitout_get_seller_information($customer_id);
                                $get_customer_name = $get_current_seller_info['seller_display_name'];
                                $order_billing_email = $order_data['billing']['email'];
                                $get_customer_location = $get_current_seller_info['seller_location'];
                                $seller_url = $get_current_seller_info['seller_url'];
                                $attachment_id = (int) get_user_meta($customer_id, 'so_profile_img', true);
                                $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                if ($attachment_array) {
                                    $get_customer_image = $attachment_array[0]; // URL of the thumbnail image 
                                }

                                /* if the author avatar is empty it assign a placeholder image */
                                if (empty($get_customer_image)) {
                                    $get_customer_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                }
                                $order_billing_address_1 = $order_data['billing']['address_1'];
                                $order_billing_address_2 = $order_data['billing']['address_2'];
                                $order_billing_city = $order_data['billing']['city'];
                                $order_billing_state = $order_data['billing']['state'];
                                $order_billing_postcode = $order_data['billing']['postcode'];
                                $order_billing_country = $order_data['billing']['country'];
                                $final_delivery_address = $order_billing_country . ',' . $order_billing_city . ',' . $order_billing_address_1 . ',' . $order_billing_postcode;
                                echo '

                            <section class="single-sale-bill single-shipping-bill" id="single-bill-section-' . $order . '">
                        <div class="so-sc-nd-heading d-flex align-items-center mt-5">
                            <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 100 100" height="20" viewBox="0 0 100 100" width="20">
                                <path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                            </svg>
                            <h5>Order ' . $order . '</h5>
                            <div class="so-sc-nd-heading-options" style="display:none">
                                <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                                        <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                                        <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                                        <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="so-sc-nd-order-details container">
                            <div class="row">
                                <div class="col-lg-12 d-flex align-items-center so-sc-nd-delivery-status nd-status-needs-delivery">
                                    <a href="#">
                                        <p>Shipped</p>
                                    </a>
                                    <h6 class="ml-auto font-weight-bold">Date: <span class="text-muted">' . $purchase_date . '</span></h6>
                                </div>
                                <h6 class="ml-auto font-weight-bold col-lg-12">
                                    Tracking Id: <span class="text-muted font-weight-normal">' . get_post_meta((int) $order, 'order_tracking_id', true) . '</span>
                                </h6>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="font-weight-bold">Order</h6>
                                </div>

                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered">
                                  <div class="sales-product">
                                ';

                                // foreach ($order_info->get_items() as $item_id => $item_data) {
                                //     $product_name = $item_data->get_name(); // Get product name
                                //     $quantity = $item_data->get_quantity(); // Get product quantity\\
                                //     $get_price = $item_data->get_total();
                                //     $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';
            
                                //     echo '<div class="single-bill-product-wrapper"> <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                //             </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                //         </h5>
                                //         <h5 class="ml-auto"> <span class="text-muted">' . $wc_pice_symbol . $get_price . '</span> </h5> </div>';
                                // }



                                // foreach ($order_info->get_items() as $item_id => $item_data) {
                                //     $product_id = $item_data->get_product_id();
                                //     $product = wc_get_product($product_id);
                                //     if(!$product) continue;
                                //     $product_name = $item_data->get_name(); // Get product name
                                //     $quantity = $item_data->get_quantity(); // Get product quantity\\
                                //     $get_price = $item_data->get_total();
                                //     $product_price = truncate_decimal_places(($product->get_price() * $quantity),6);

                                //     $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';


                                //     echo '<div class="single-bill-product-wrapper"> <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                //             </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                //         </h5>
                                //         <h5 class="ml-auto"> <span class="text-muted">' . $wc_pice_symbol . number_format($product_price,6) . '</span> </h5> </div>';
                                // }


                                foreach ($order_info->get_items() as $item_id => $item_data) {
                                    $product_id = $item_data->get_product_id();
                                    $product = wc_get_product($product_id);
                                    $product_name = $item_data->get_name(); // Get product name
                                    $quantity = $item_data->get_quantity(); // Get product quantity

                                    if(so_get_dynamic_currency() == "BTC"){
                                        $final_price = $item_data->get_meta('_btc_price') * $quantity;
                                    }else{
                                        $final_price = $item_data->get_meta('_usd_price') * $quantity;
                                    }

                                    $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';

                                    echo '<div class="single-bill-product-wrapper"> <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                            </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                        </h5>
                                        <h5 class="ml-auto"> <span class="text-muted">' . so_get_dynamic_currency_symbol() . number_format($final_price,6) . '</span> </h5> </div>';
                                }

                                echo ' </div></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 d-flex so-cancellation-date-notice px-3">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                 
                                    <p>Please ship within the next <span>72 </span> hours</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="font-weight-bold">Address Delivery</h6>
                                </div>
                                <div class="so-sc-nd-address-details" style="width:100%">
                                    <div class="col-lg-12 d-flex">
                                    <i class="bi bi-person-fill" style="font-size: 20px"></i>
                                        <h6>' . $customer_name . '</h6>
                                    </div>
                                    <div class="col-lg-12 d-flex gap-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                            <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                        </svg>
                                        <h6>' . $final_delivery_address . '</h6>
                                    </div>
                                    <div class="col-lg-12 d-flex">
                                        <i class="bi bi-envelope-fill" style="font-size: 20px"></i>
                                        <h6>' . $order_billing_email . '</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h6 class="font-weight-bold">Buyer</h6>
                                </div>
                                <div class="col-lg-12 so-sc-nd-buyer-details d-flex align-items-center">
                                    <figure>
                                        <img src="' . $get_customer_image . '" alt="' . $get_customer_name . '" style="width: 100%; height: 100%; object-fit: cover;">
                                    </figure>
                                    <a href="' . $seller_url . '" target="_blank">
                                        <h5>' . $get_customer_name . '</h5>
                                    </a>
                                    <a href="#" class="ml-auto d-none">
                                        <p class="font-weight-bold">Send Message</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
                            ';
                            }
                        }
                    }

                    foreach ($awaiting_confirmation_orders as $key => $order) {
                        # code...
                        $order_info = wc_get_order($order);
                        // Check if the order object exists
                        if ($order) { ?>

                            <script>
                                jQuery("#single-shipped-order-<?php echo $order; ?>").on('click', function (event) {
                                    event.preventDefault(); // Prevent the default behavior of the link

                                    jQuery("#pills-awaiting-confirmation .sales-list-page").hide(); // Hide the sales-lists-page
                                    jQuery("#single-bill-section-<?php echo $order; ?>").show();
                                    jQuery("html, body").animate({
                                        scrollTop: 0
                                    }, "fast");
                                });
                                jQuery(".single-shipping-bill .so-sc-nd-heading svg").on('click', function (event) {
                                    event.preventDefault(); // Prevent the default behavior of the link
                                    jQuery("#pills-awaiting-confirmation .sales-list-page").show(); // show the sales-lists-page
                                    jQuery("#single-bill-section-<?php echo $order; ?>").hide(); // Show the single-sale-bill
                                    jQuery("html, body").animate({
                                        scrollTop: 0
                                    }, "fast");
                                });
                            </script>

                            <?php
                        }
                    }

                    ?>
                </div>






                <div class="tab-pane fade" id="pills-completion" role="tabpanel" aria-labelledby="pills-completion-tab">
                    <section class="sales-list-page">
                        <?php
                        //empty placeholder
                        if (empty($completion_orders)) {
                            echo so_empty_orders_placeholder('No completed orders till now.');
                        }

                        $currency = $_COOKIE['wmc_current_currency'] ?? 'BTC';
                        $get_currency_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();

                        foreach ($completion_orders as $key => $order) {
                            # code...
                            $order_info = wc_get_order($order);
                            // Check if the order object exists
                            if ($order) {
                                // Get customer information
                
                                $customer_id = $order_info->get_customer_id();


                                $customer_name = $order_info->get_billing_first_name() . ' ' . $order_info->get_billing_last_name();

                                // Get purchase date
                                $purchase_date = $order_info->get_date_created()->format('Y-m-d H:i:s');

                                $order_status = $order_info->get_status();

                                $order_data = $order_info->get_data();

                                // Get order amount
                                $order_amount = $order_info->get_total();

                                if ($currency == 'BTC') {
                                    $order_total = $order_info->get_formatted_order_total();
                                } else {
                                    $convert_btc_usd = $order_info->get_total() * $get_currency_exchange_rate;
                                    $order_total = '$' . number_format($convert_btc_usd, 2, '.', ',');
                                }


                                if ($customer_id) {
                                    $get_current_seller_info = spitout_get_seller_information($customer_id);
                                    $get_customer_name = $get_current_seller_info['seller_display_name'];
                                    $get_customer_location = $get_current_seller_info['seller_location'];
                                    $seller_url = $get_current_seller_info['seller_url'];
                                    // $get_customer_image = $get_current_seller_info['seller_profile_img'];
                                    $attachment_id = (int) get_user_meta($customer_id, 'so_profile_img', true);
                                    $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                    if ($attachment_array) {
                                        $get_customer_image = $attachment_array[0]; // URL of the thumbnail image 
                                    }


                                    /* if the author avatar is empty it assign a placeholder image */
                                    if (empty($get_customer_image)) {
                                        $get_customer_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                    }
                                    $order_billing_address_1 = $order_data['billing']['address_1'];
                                    $order_billing_address_2 = $order_data['billing']['address_2'];
                                    $order_billing_city = $order_data['billing']['city'];
                                    $order_billing_state = $order_data['billing']['state'];
                                    $order_billing_postcode = $order_data['billing']['postcode'];
                                    $order_billing_country = $order_data['billing']['country'];
                                    // $final_delivery_address = $order_billing_country . ', ' . $order_billing_city . ', ' . $order_billing_address_1 . ', ' . $order_billing_postcode;
                                    $final_delivery_address =  $order_billing_address_1 . ', ' .$order_billing_city . ', '.$order_billing_country . ' ' . $order_billing_postcode;
                                    


                                    $order_items = [];
                                    $order_total = 0;
                                    foreach ($order_info->get_items() as $item_id => $item_data) {
                                        $product_name = $item_data->get_name();
                                        $quantity = $item_data->get_quantity();
                                        // $get_price = $item_data->get_total();
                                        // if ($currency == 'BTC') {
                                        //     $order_total = $order_info->get_formatted_order_total();
                                        //     $get_price = $item_data->get_total() / $quantity;
                                        // } else {

                                        //     $convert_item_btc_usd = $item_data->get_total() * $get_currency_exchange_rate;
                                        //     $get_single_item_price = $convert_item_btc_usd / $quantity;
                                        //     $convert_btc_usd = $order_info->get_total() * $get_currency_exchange_rate;
                                        //     $order_total = '$' . number_format($convert_btc_usd, 2, '.', ',');
                                        //     $get_price = number_format($get_single_item_price, 2, '.', ',');
                                        // }
                                        
                                        $get_price = 0;

                                        if(so_get_dynamic_currency() == "BTC"){
                                            $get_price = $item_data->get_meta('_btc_price');
                                            $order_total += (float)$get_price * (float)$quantity;
                                        }else{
                                            $get_price = $item_data->get_meta('_usd_price');
                                            $order_total += (float)$get_price * (float)$quantity;
                                        }

                                        $order_items[] = [$product_name, $quantity, so_get_dynamic_currency_symbol().$get_price];
                                    }

                                    $sale_details = [
                                        'id' => $order,
                                        'tracking_id' => get_post_meta($order, 'order_tracking_id', true),
                                        'total' => so_get_dynamic_currency_symbol().$order_total,
                                        'shipping_address' => $final_delivery_address,
                                        'customer_name' => $customer_name,
                                        'date' => $purchase_date,
                                        'status' => $order_status,
                                        'items' => $order_items
                                    ];

                                    $json_data = htmlspecialchars(json_encode($sale_details, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');


                                    echo '
                                    
                                    <div class="container so-feed-new-container so-sales-lists mt-5">
                                            <div class="row">
                                                <div class="col-lg-12 d-flex align-items-center nd-status-needs-completed so-sc-nd-sales-single-order-status">
                                                    <a>
                                                        <p>Completed</p>
                                                    </a>
                                                    <a href="#">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                                                            <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                                                            <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                                                            <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                                                        </svg>
                                                    </a>
                                                    <h6 class="ml-auto font-weight-bold">
                                                        Tracking Id: <span class="text-muted font-weight-normal">' . get_post_meta((int) $order, 'order_tracking_id', true) . '</span>
                                                    </h6>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered-single so-sc-nd-products-ordered-lists">
                                                <div class="sales-product">';
                                                    foreach ($order_info->get_items() as $item_id => $item_data) {
                                                        $product_name = $item_data->get_name(); // Get product name
                                                        $quantity = $item_data->get_quantity(); // Get product quantity\\
                                                        $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';

                                                        echo ' <h5 class="font-weight-normal text-muted"><span class="seller_order_product">' . $product_name . ' </span><span class ="seller_order_qty">' . $quantity . 'X
                                                                    </span><span class="seller_order_icon"><img src="' . $qtyicon . '" /></span>
                                                                </h5>';
                                                    }

                                                    echo '</div>
                                                    <h6 class="ml-auto font-weight-bold">Order ID: <span class="text-muted font-weight-normal">' . $order . '</span>
                                                    </h6>
                                                </div>
                                                <div class="col-lg-12 d-flex so-sc-nd-products-ordered-lists-price">
                                                    <h5>' . dynamic_currency_for_buyer_seller_order_pages($order) . '</h5>
                                                    <h6 class="ml-auto font-weight-bold">Date: <span class="text-muted font-weight-normal">' . $purchase_date . '</span>
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 so-sc-nd-single-buyer-details d-flex align-items-center">
                                                    <figure>
                                                        <img src="' . $get_customer_image . '" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </figure>
                                                    <a href="' . $seller_url . '" target="_blank">
                                                        <h5>' . $get_customer_name . '</h5>
                                                    </a>
                                                
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 sales-complete-orderDetailsbtn">
                                                    <button class="view-sale-details" type="button" data-toggle="modal" data-target="#sale-details" data-order-details="' . $json_data . '" >Order Details</button>
                                                </div>
                                            </div>
                                        </div> 
                                    ';
                                }
                            } else {
                                echo "No any Needs Delivery order found.";
                            }
                        }
                        ?>
                        <!-- order details modal -->
                        <div class="modal fade so-my-spitout-modal" id="sale-details" tabindex="-1" aria-labelledby="add-new"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-white-background.png"
                                            alt="Profile Image" style="object-fit: cover;">
                                        <h5 class="modal-title" id="add-new"></h5>
                                        <button type="button" id="close-add-product-modal" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body my-spitout-sale-details-modal">
                                        <div class="sale-details-container-column-1">
                                            <span class="d-flex justify-content-between">
                                                <p class="order_id">Order Id: </p>
                                                <p class="purchase_date">Date: </p>
                                            </span>
                                            <p class="tracking_id">Tracking Number: </p>
                                            <span>
                                                <table class='sales-view-modal-table m-0'>
                                                    <thead>
                                                        <th></th>
                                                        <!-- <th>Qty:</th>
                                                        <th>PPU:</th> -->
                                                    </thead>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>PPU</th>

                                                </table>
                                                <!-- <div class="sale-details-container-column-2">
                                                    <img src="<?php //echo get_stylesheet_directory_uri(); ?>/assets/img/bottle.png"
                                                        alt="Profile Image" style="object-fit: cover;">
                                                </div> -->
                                            </span>
                                            <p class="total">Total: </p>
                                            <p class="shipping">Shipping: </p>
                                            <p class="customer_name">Customer_name: </p>
                                            <p class="status"></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        <?php


    } else {

        echo ' <section class="error-404 not-found">
            <div class="card so-feed-card-wImage text-center pt-5 py-5">
                <div class="card-body so-feed-profile-summary">
                    <header class="page-header">
                        <h1 class="page-title">Sorry Permission Denied !</h1>
                    </header><!-- .page-header -->
                </div>
                <div class="card-body so-feed-card-body">
                    <p>You do not have permission to access this page</p>
                </div>
            </div>
        </section>';
    }
}
get_footer();
