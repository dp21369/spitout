<?php

/**
 * Template Name: Orders Page
 * @package spitout
 */
get_header();


function get_single_seller_id($str)
{
    $unserialized = @unserialize($str);
    return $unserialized !== false && is_array($unserialized);
}

if (is_user_logged_in()) {
    $allowed_roles = array('seller'); // Replace 'custom_role' with your role slug
    $user = wp_get_current_user();

    if (array_intersect($allowed_roles, $user->roles)) {

        get_footer();
        return;
    }
}

?>

<section class="so-order-page-details">
    <div class="container so-feed-new-container">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="buyer-order-filters d-flex justify-content-center">
                    <a href="" class="buyer-filter-all active">
                        <p>All</p>
                    </a>
                    <a href="" class="buyer-filter-arriving-soon">
                        <p>Arriving soon</p>
                    </a>
                    <a href="" class="buyer-filter-completed">
                        <p>Completed</p>
                    </a>
                </div>

            </div>
        </div>
        <?php

        if (is_user_logged_in()) {
            // Get the current user's ID
            $user_id = get_current_user_id();
            $wc_pice_symbol = get_woocommerce_currency_symbol();

            // Load WooCommerce functions
            if (class_exists('WooCommerce')) {
                if ($user_id) {

                    $orderArg = array(
                        'customer_id' => $user_id,
                        'limit' => -1,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );
                    $orders = wc_get_orders($orderArg);
                    // echo '<pre>';
                    // var_dump($orders);
                    // echo '</pre>';




                    $order_ids = array(); // Initialize an array to store order IDs

                    foreach ($orders as $order) {
                        $order_ids[] = $order->get_id(); // Get and store order ID
                    }
                    $modal_counter = 0; // Initialize modal counter
                    if (($order_ids)) {

                        if (!empty(get_wallet_rechargeable_product())) {



                            foreach ($order_ids as $key => $order_id) {
                                $order = wc_get_order($order_id);
                                $order_total = $order->get_total();
                                $order_status = $order->get_status();
                                $order_data = $order->get_data();
                                $order_created_date = $order->get_date_created();
                                $get_seller_id = $order->get_meta('seller_id');
                                $tracking_id = get_post_meta((int) $order_id, 'order_tracking_id', true);

                                if (get_single_seller_id($get_seller_id)) {
                                    $unserializedArray = unserialize($get_seller_id);
                                    $seller_id = ($unserializedArray['0']);
                                } else {
                                    $seller_id = $get_seller_id;
                                }

                                $get_author_meta_data = spitout_get_seller_information($seller_id);
                                // Check if seller exists
                                if (!$get_author_meta_data) {
                                    continue; // Skip to the next iteration if the seller doesn't exist
                                }
                                $seller_display_name = $get_author_meta_data['seller_display_name'];
                                $seller_url = $get_author_meta_data['seller_url'];
                                $attachment_id = (int) get_user_meta($seller_id, 'so_profile_img', true);
                                $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                if ($attachment_array) {
                                    $seller_final_profile_img = $attachment_array[0]; // URL of the thumbnail image 
                                }

                                /* if the author avatar is empty it assign a placeholder image */
                                if (empty($seller_final_profile_img)) {
                                    $seller_final_profile_img = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                }

                                $order_billing_address_1 = $order_data['billing']['address_1'];
                                $order_billing_address_2 = $order_data['billing']['address_2'];
                                $order_billing_city = $order_data['billing']['city'];
                                $order_billing_state = $order_data['billing']['state'];
                                $order_billing_postcode = $order_data['billing']['postcode'];
                                $order_billing_country = $order_data['billing']['country'];
                                $customer_name = $order_data['billing']['first_name'] . ' ' . $order_data['billing']['last_name'];
                                $final_delivery_address =  $order_billing_address_1 . ', ' .$order_billing_city . ', '.$order_billing_country . ' ' . $order_billing_postcode;
                                
                                // $review_page = home_url('/product-review/') . '?order_id=' . $order_id;

                                $review_page = home_url('/product-review/') . '?user_id=' . $seller_id;
                                $product_names = array();


                                // Loop through order items
                                foreach ($order->get_items() as $item_id => $item) {
                                    $product_name = $item->get_name(); // Get the product name
                                    $product_names[] = $product_name;
                                }


                                $product_name_to_check = 'Wallet Topup'; // The product name you want to check
                                if (!in_array($product_name_to_check, $product_names)) {

                                    if ($order_status == 'completed') {
                                        $order_stat = '
                                            <div class="col-md-12 so-order-pay-status">
                                                <a class="so-ordercard-status-paid completed">
                                                    <p>Completed</p>
                                                </a>

                                                <a href="' . $review_page . '" target="_blank" class="so-ordercard-status-paid review_completed">
                                                    <p>Leave a review</p>
                                                </a>
                                            </div>';
                                    } else if (($order_status == 'processing' || $order_status == 'on-hold')) {
                                        $order_stat = '
                                                <div class="col-md-12 so-order-pay-status">
                                                    <a class="so-ordercard-status-paid">
                                                        <p>Paid</p>
                                                    </a>
                                                    <a class="so-ordercard-status-arrivesoon">
                                                        <p>'.(empty($tracking_id) ? 'Awaiting Shipment' : 'Shipped').'</p>
                                                    </a>
                                                </div>';

                                    } else {

                                        $order_stat = '
                                                <div class="col-md-12 so-order-pay-status">
                                
                                                    <a class="so-ordercard-status-paid completed">
                                                        <p>Failed Order</p>
                                                    </a>
                                                </div>';
                                    }
                                    //$payment_status = $order->get_payment_status();

                                    echo '<div class="row so-ordercard-row-content">
                                            <div class="col-md-12 ordered-detailed-col">
                                                <div class="so-ordered-saliva">';

                                                    foreach ($order->get_items() as $item_id => $item_data) {
                                                        $product_name = $item_data->get_name(); // Get product name
                                                        $quantity = $item_data->get_quantity(); // Get product quantity\\
                                                        $qtyicon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';

                                                        echo ' <h5><span class="buyer_order_product">' . $product_name . ' </span><span class ="buyer_order_qty">' . $quantity . 'X
                                                                    </span><span class="buyer_order_icon"><img src="' . $qtyicon . '" /></span>
                                                                </h5>';
                                                    }

                                                echo '
                                                <span class="so-ordercard-saliva-price">
                                                        <h5 class="static-price-currency">' . dynamic_currency_for_buyer_seller_order_pages($order_id) . '</h5>
                                                    </span>
                                            </div>
                                            <div class="so-orderd-saliva-details">
                                                <h6>Order ID:<span> ' . $order_id . ' </span></h6>
                                                <h6>Date: <span> ' . $order_created_date->format('d-m-Y') . ' </span></h6>
                                                <h6>
                                                    Tracking Id: <span class="text-muted font-weight-normal">' . $tracking_id . '</span>
                                                </h6>
                                            </div>
                                            </div>

                                        ' . $order_stat . '
                                            
                                            <div class="row m-0">
                                                            <div class="col-lg-12">
                                                                <h6 class="font-weight-bold">Address Delivery</h6>
                                                            </div>
                                                            <div class="so-sc-nd-address-details mx-2 my-2" style="width:100%">
                                                                <div class="col-lg-12 d-flex">
                                                                <i class="bi bi-person-fill" style="font-size: 20px"></i>
                                                                    <h6>' . $customer_name . '</h6>
                                                                </div>
                                                                <div class="col-lg-12 d-flex">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                                <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                                            </svg>
                                                                    <h6>' . $final_delivery_address . '</h6>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                        <div class="col-md-12 so-ordercard-buyer-details">
                                            <figure>
                                                <img src="' . $seller_final_profile_img . '" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                                            </figure>
                                            <a href="' . $seller_url . '" target="_blank" class="ordercard-buyer-name">
                                                <h5>' . $seller_display_name . '</h5>
                                            </a>';

                                                                if (($order_status == 'processing' || $order_status == 'on-hold') &&!empty($tracking_id)) {
                                                                    echo '<a class="order-marked-as-recieved" title ="mark as recieved" data-order-id="' . $order_id . '">
                                                                            <h6> Finalize Order </h6>
                                                                        </a>';
                                                                }

                                                                echo '<a href="/spitout/order-view/?order_id=' . $order_id . '">
                                            <h6> View Order </h6>
                                            </a>
                                        </div>
                                        </div>
                                        
                                            ';
                                                            }


                                    ?>

                                    <?php
                                // Now $order_ids contains an array of order IDs
                                //print_r($order_ids);
                            }
                        } else {
                            echo '<p class="sp_no_orders">No Orders Found</p>';
                        }
                    }
                }
            }
        } else {
            echo '<p class="spitout_user_not_loggedin_msg">Sorry you cannot access this page. Please Login </p>';
        }

        ?>

    </div>
    </div>

</section>






<?php get_footer();
