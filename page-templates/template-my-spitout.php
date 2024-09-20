<?php

/**
 * Template Name: myspitout
 * @package spitout
 */
get_header();
if (is_user_logged_in()) {
    $allowed_roles = array('seller'); // Replace 'custom_role' with your role slug
    $user = wp_get_current_user();
    $current_user = get_current_user_id();

    if (array_intersect($allowed_roles, $user->roles)) {


        $get_current_seller_info = spitout_get_seller_information($current_user);
        $get_seller_name = $get_current_seller_info['seller_display_name'];
        $get_seller_location = $get_current_seller_info['seller_location'];
        // $get_seller_image = $get_current_seller_info['seller_profile_img'];
        $get_seller_url = $get_current_seller_info['seller_url'];

        $attachment_id = (int) get_user_meta($current_user, 'so_profile_img', true);
        $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
        if ($attachment_array) {
            $get_seller_image = $attachment_array[0]; // URL of the thumbnail image 
        }

        /* if the author avatar is empty it assign a placeholder image */
        if (empty($get_seller_image)) {
            $get_seller_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
        }

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

        $args = array(
            'limit' => -1,
            'meta_key' => 'seller_id',
            'meta_value' => $seller_id,
            'meta_compare' => 'LIKE',
            'return' => 'ids',
        );

        $orders = wc_get_orders($args);

        $my_products = wc_get_products(
            array(
                'status' => 'publish',
                'limit' => -1,
                'author' => $seller_id

            )
        );

        /* get total profile view */
        $total_views = get_user_meta($seller_id, 'author_page_views', true);

        if (!$total_views) {
            $total_views = [];
        }

        /* get seller fan data */
        $get_followers = get_user_meta($seller_id, 'so_total_followers', true);

        ?>

        <!-- ================ MY SPITOUT ==================== -->
        <input type="hidden" name="curr_seller_id" id="curr-seller-id" value="<?php echo $seller_id; ?>">
        <section class="so-my-spitout">
            <div class="container so-my-spitout-container">
                <div class="so-my-spitout-title">
                    <h4>My Spitout</h4>
                    <div class="so-my-spitout-wrapper">
                        <div class="so-my-spitout-wrap">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="so-my-spitout-card">
                                        <div class="row">
                                            <div class="col-md-1 col-2">
                                                <div class="so-my-spitout-card-image">
                                                    <img alt="<?php echo $get_seller_name; ?>"
                                                        style="width: 100%; height: 100%; object-fit: cover;"
                                                        src="<?php echo esc_url($get_seller_image); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-9">
                                                <div class="so-my-spitout-card-body">
                                                    <h5 class="so-my-spitout-card-name">

                                                        <a href="<?php echo $get_seller_url; ?>">
                                                            <?php echo $get_seller_name; ?>

                                                        </a>

                                                        <?php if ((int) get_user_meta($seller_id, 'is_verified', true) == 1) { ?>
                                                            <div class="profile-verify" title="verified">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                    viewBox="0 0 24 24" fill="none">
                                                                    <path
                                                                        d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                                                                        fill="#292D32" />
                                                                </svg>
                                                            </div>
                                                        <?php } ?>

                                                    </h5>
                                                    <p class="so-my-spitout-card-location">
                                                        <span class="so-custom-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z"
                                                                    fill="#292D32" />
                                                            </svg>
                                                        </span>
                                                        <?php echo $get_seller_location; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a href="<?php echo home_url('/my-account/woo-wallet/'); ?>" class="so-my-spitout-wallet">
                                        <p><span class="so-custom-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M20.97 16.08C20.73 18.75 18.8 20.5 16 20.5H7C4.24 20.5 2 18.26 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.51 16.75 3.55C19.14 3.83 20.76 5.5 20.97 7.92C21 8.21 20.76 8.45 20.47 8.45H18.92C17.96 8.45 17.07 8.82 16.43 9.48C15.67 10.22 15.29 11.26 15.38 12.3C15.54 14.12 17.14 15.55 19.04 15.55H20.47C20.76 15.55 21 15.79 20.97 16.08Z"
                                                        fill="#292D32" />
                                                    <path
                                                        d="M22.0002 10.9692V13.0292C22.0002 13.5792 21.5602 14.0292 21.0002 14.0492H19.0402C17.9602 14.0492 16.9702 13.2592 16.8802 12.1792C16.8202 11.5492 17.0602 10.9592 17.4802 10.5492C17.8502 10.1692 18.3602 9.94922 18.9202 9.94922H21.0002C21.5602 9.96922 22.0002 10.4192 22.0002 10.9692Z"
                                                        fill="#292D32" />
                                                </svg>
                                            </span>Wallet</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="so-my-spitout-tabs-summary">
                            <div class="so-my-spitout-tabs">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="so-my-spitout-overview-tab" data-toggle="tab"
                                            data-target="#so-my-spitout-overview" type="button" role="tab"
                                            aria-controls="so-my-spitout-overview" aria-selected="false">Overview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="so-my-spitout-sales-tab" data-toggle="tab"
                                            data-target="#so-my-spitout-sales" type="button" role="tab"
                                            aria-controls="so-my-spitout-sales" aria-selected="false">Sales</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="so-my-spitout-myProduct-tab" data-toggle="tab"
                                            data-target="#so-my-spitout-myProduct" type="button" role="tab"
                                            aria-controls="so-my-spitout-myProduct" aria-selected="true">My Products</button>
                                    </li>
                                </ul>
                                <div class="so-my-spitout-summary d-none">
                                    <p>Here's a quick summary of the account</p>
                                </div>
                                <div class="tab-content" id="myTabContent">
                                    <!-- -------- OVERVIEW ---------- -->

                                    <div class="tab-pane fade show active" id="so-my-spitout-overview" role="tabpanel"
                                        aria-labelledby="so-my-spitout-overview-tab">
                                        <h4>Overview</h4>
                                        <div class="so-my-spitout-overview-card">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="card-title">
                                                                <?php if ($get_total_earning > 0) {
                                                                    echo so_get_dynamic_currency_symbol(). $get_total_earning;
                                                                } else {

                                                                    echo 'N/A';
                                                                } ?>
                                                            </h4>
                                                            <p class="card-text">Total Earnings</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="card-title">$16.756k</h4>
                                                            <p class="card-text">Purchase Count</p>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="card-title">
                                                                <?php if ($get_seller_average != 0) {
                                                                    // echo spitout_get_formatted_price(round($get_seller_average));
                                                                    echo so_get_dynamic_currency_symbol(). $get_seller_average;
                                                                } else {
                                                                    echo 'N/A';
                                                                } ?>
                                                            </h4>
                                                            <p class="card-text">Average Purchase</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="card-title">
                                                                <?php echo count($total_views); ?>
                                                            </h4>
                                                            <p class="card-text">Total Views</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                        $year = date('Y');
                                        $monthlySales = [];
                                        $all_months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                                        $monthly_orders = wc_get_orders([
                                            'limit' => -1,
                                            'meta_key' => 'seller_id',
                                            'meta_value' => $seller_id,
                                            'meta_compare' => 'LIKE',
                                            'status' => array('completed'),
                                            'date_query' => array(
                                                array(
                                                    'after' => date('Y-m-d', strtotime($year . '-01-01')),
                                                    'before' => date('Y-m-d', strtotime($year . '-12-31')),
                                                    'inclusive' => true,
                                                ),
                                            ),
                                            'return' => 'ids',
                                        ]);

                                        foreach ($monthly_orders as $key => $order) {
                                            $order_info = wc_get_order($order);

                                            if ($order) {

                                                $month = date("F", strtotime($order_info->get_date_created()));
                                                // var_dump($month);
                                
                                                // $total = $order_info->get_total();
                                                $total = dynamic_currency_for_buyer_seller_order_pages($order, false);

                                                if (isset($monthlySales[$month])) {
                                                    $monthlySales[$month] += $total;
                                                } else {
                                                    $monthlySales[$month] = $total;
                                                }
                                            }
                                        }



                                        // if (isset($_COOKIE['wmc_current_currency']) && $_COOKIE['wmc_current_currency'] != 'BTC') {
                                        //     $curr_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();
                                        //     foreach ($monthlySales as $key => $value) {
                                        //         $monthlySales[$key] = $value * $curr_exchange_rate;
                                        //     }
                                        // }
                                        // echo '<pre>';
                                        // var_dump($monthlySales);
                                        // echo '</pre>';
                                

                                        if (is_array($monthlySales) && count($monthlySales) > 0) {
                                            echo '<input type="hidden" id="monthly_sales" value=' . json_encode($monthlySales) . '>';
                                            echo '<canvas id="sales-chart"></canvas>';
                                        }
                                        ?>

                                        <h4>Top Buyers</h4>
                                        <div class="so-my-spitout-overview-table" id="myspitout-top-buyer-table">
                                            <div class="table-responsive-sm">
                                                <table class="table table-borderless top-buyer-table-main" id="overview-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Fan</th>
                                                            <th>Purchases</th>
                                                            <th class="spitout-overview-total">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php

                                                        //get all customers of current seller
                                                        $get_customer_ids = [];
                                                        $total = 0;
                                                        foreach ($orders as $key => $order_id) {

                                                            $order = new WC_Order($order_id);

                                                            // $total += $order->get_total();
                                                            $total += dynamic_currency_for_buyer_seller_order_pages($order_id,false);
                                                            $user_id = $order->get_user_id();
                                                            $get_customer_ids[] = $user_id;
                                                        }

                                                        // var_dump($seller_id);
                                                
                                                        foreach (array_unique($get_customer_ids) as $key => $get_customer_id) {
                                                            if ($get_customer_id != 0) {
                                                                $buyer_order_info = so_get_buyer_total_purchase($get_customer_id, $seller_id);

                                                                $get_buyer_total_purchase = $buyer_order_info[0];
                                                                // $get_fan_total_count = wc_get_customer_order_count($get_customer_id);
                                                                $get_fan_total_count = $buyer_order_info[1];
                                                                $get_buyer_infomation = spitout_get_buyer_information($get_customer_id);
                                                                $get_buyer_name = $get_buyer_infomation['buyer_display_name'];
                                                                $get_buyer_image = $get_buyer_infomation['buyer_profile_img'];

                                                                // $attachment_id = (int) get_user_meta($get_customer_id, 'so_profile_img', true);
                                                                // $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
                                                                // if ($attachment_array) {
                                                                //     $get_buyer_image = $attachment_array[0]; // URL of the thumbnail image 
                                                                // }
                                                

                                                                /* if the author avatar is empty it assign a placeholder image */
                                                                if (empty($get_buyer_image)) {
                                                                    $get_buyer_image = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                                                }

                                                                if ($get_fan_total_count == 0) {
                                                                    continue;
                                                                }

                                                                if ($get_buyer_total_purchase) {
                                                                    $total_purchase = $get_buyer_total_purchase;
                                                                } else {
                                                                    $total_purchase = '0';
                                                                }
                                                                echo '
                                                                <tr>
                                                                    <td id="fan" class="so-my-spitout-overview-fan">
                                                                        <img src="' . $get_buyer_image . '" alt="' . $get_buyer_name . '">
                                                                    ' . $get_buyer_name;



                                                                if ((int) get_user_meta($get_customer_id, 'is_verified', true) == 1) {
                                                                    echo '<div class="profile-verify" title="verified">
                                                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                                            <path
                                                                              d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                                                                              fill="#292D32" />
                                                                          </svg>
                                                                        </div>';
                                                                }


                                                                echo ' </td>
                                                                    <td id="purchase" class="so-my-spitout-overview-purchase">' . $get_fan_total_count . '</td>
                                                                    <td id="toal" class="so-my-spitout-overview-total">' . so_get_dynamic_currency_symbol(). $total_purchase . '</td>
                                                                </tr>                                                            
                                                            ';
                                                            }
                                                        }

                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <?php
                                        $start_and_end_of_each_week = [];
                                        $firstDayOfMonth = date('Y-m-01');
                                        $lastDayOfMonth = date('Y-m-t');
                                        // $firstDayOfMonth = date('2023-02-01');
                                        // $lastDayOfMonth = date('2023-02-26');
                                
                                        $startDate = strtotime($firstDayOfMonth);
                                        $endDate = strtotime($lastDayOfMonth);

                                        $currentDate = $startDate;
                                        $week_count = 1;
                                        while ($currentDate <= $endDate) {
                                            $startOfWeek = date('Y-m-d', $currentDate);
                                            $endOfWeek = date('Y-m-d', strtotime('next saturday', $currentDate));
                                            if (date('w', $currentDate) != 0 && date('m', $currentDate) == date('m', $startDate)) {
                                                $startOfWeek = date('Y-m-d', strtotime('last sunday', $currentDate));
                                            }
                                            if (date('w', strtotime('next saturday', $currentDate)) != 6) {
                                                $endOfWeek = date('Y-m-d', strtotime('next saturday', $currentDate));
                                            }

                                            if (date('Y-m-d', strtotime($startOfWeek)) < date('Y-m-d', strtotime($firstDayOfMonth))) {
                                                // array_push($start_and_end_of_each_week, [$week_count => ['start' => date('Y-m-d', strtotime($firstDayOfMonth)), 'end' => date('Y-m-d', strtotime($endOfWeek))]]);
                                                $start_and_end_of_each_week[] = [$week_count => ['start' => date('Y-m-d', strtotime($firstDayOfMonth)), 'end' => date('Y-m-d', strtotime($endOfWeek))]];
                                            } else {
                                                if (date('Y-m-d', strtotime($endOfWeek)) > date('Y-m-d', strtotime($lastDayOfMonth))) {
                                                    // array_push($start_and_end_of_each_week, ['start' => date('Y-m-d', strtotime($startOfWeek)), 'end' => date('Y-m-d', strtotime($lastDayOfMonth))]);
                                                    $start_and_end_of_each_week[] = [$week_count => ['start' => date('Y-m-d', strtotime($startOfWeek)), 'end' => date('Y-m-d', strtotime($lastDayOfMonth))]];
                                                } else {
                                                    // array_push($start_and_end_of_each_week, [$week_count => ['start' => date('Y-m-d', strtotime($startOfWeek)), 'end' => date('Y-m-d', strtotime($endOfWeek))]]);
                                                    $start_and_end_of_each_week[] = [$week_count => ['start' => date('Y-m-d', strtotime($startOfWeek)), 'end' => date('Y-m-d', strtotime($endOfWeek))]];
                                                }
                                            }
                                            $currentDate = strtotime('+1 week', $currentDate);
                                            $week_count++;
                                        }

                                        // echo '<pre>';
                                        // print_r($start_and_end_of_each_week);
                                        // echo '</pre>';
                                
                                        //weekly orders
                                        $week_order = [];
                                        $week_countt = 1;
                                        foreach ($start_and_end_of_each_week as $week) {

                                            // echo '<pre>';
                                            // var_dump(date( 'Y-m-d', strtotime($week[$week_countt]['start'])));
                                            // var_dump(date( 'Y-m-d', strtotime($week[$week_countt]['end'])));
                                            // echo '</pre>';
                                
                                            $weekly_orders = wc_get_orders([
                                                'limit' => -1,
                                                'meta_key' => 'seller_id',
                                                'meta_value' => $seller_id,
                                                'meta_compare' => 'LIKE',
                                                'status' => array('completed'),
                                                'date_query' => array(
                                                    array(
                                                        'after' => date('Y-m-d', strtotime($week[$week_countt]['start'])),
                                                        'before' => date('Y-m-d', strtotime($week[$week_countt]['end'])),
                                                        'inclusive' => true,
                                                    ),
                                                ),
                                                'return' => 'ids',
                                            ]);

                                            // echo '<pre>';
                                            // var_dump($weekly_orders);
                                            // echo '</pre>';
                                
                                            $curr_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();
                                            foreach ($weekly_orders as $key => $order) {

                                                $order_info = wc_get_order($order);

                                                if ($order) {
                                                    // echo $order_info->get_id().'->'.$order_info->get_total().'||';
                                
                                                    // $total = $order_info->get_total();


                                                    $total = dynamic_currency_for_buyer_seller_order_pages($order, false);
                                                    if (isset($week_order[$week_countt])) {
                                                        $week_order[$week_countt] += $total;
                                                    } else {
                                                        $week_order[$week_countt] = $total;
                                                    }



                                                    // if (isset($_COOKIE['wmc_current_currency']) && $_COOKIE['wmc_current_currency'] != 'BTC') {
                                                    //     if (isset($week_order[$week_countt])) {
                                                    //         $week_order[$week_countt] += $total * $curr_exchange_rate;
                                                    //     } else {
                                                    //         $week_order[$week_countt] = $total * $curr_exchange_rate;
                                                    //     }
                                                    // } else {
                                                    //     if (isset($week_order[$week_countt])) {
                                                    //         $week_order[$week_countt] += $total;
                                                    //     } else {
                                                    //         $week_order[$week_countt] = $total;
                                                    //     }
                                                    // }
                                                    // $week_order[$week_countt] += $total;
                                                }
                                            }

                                            // echo '<br>';
                                
                                            $week_countt++;
                                        }

                                        // echo '<pre>';
                                        // var_dump($week_order);
                                        // echo '</pre>';
                                

                                        //daily orders
                                        $day_and_order = [];

                                        $daily_orders = wc_get_orders([
                                            'limit' => -1,
                                            'meta_key' => 'seller_id',
                                            'meta_value' => $seller_id,
                                            'meta_compare' => 'LIKE',
                                            'status' => array('completed'),
                                            'date_query' => array(
                                                array(
                                                    'after' => date('Y-m-d', strtotime($firstDayOfMonth)),
                                                    'before' => date('Y-m-d', strtotime($lastDayOfMonth)),
                                                    'inclusive' => true,
                                                ),
                                            ),
                                            'return' => 'ids',
                                        ]);

                                        foreach ($daily_orders as $key => $order) {

                                            $order_info = wc_get_order($order);

                                            if ($order) {

                                                $total = $order_info->get_total();
                                                $order_date = $order_info->get_date_created()->format('m/d');

                                                $total = dynamic_currency_for_buyer_seller_order_pages($order, false);

                                                if (isset($day_and_order[$order_date])) {
                                                    $day_and_order[$order_date] += (float) $total;
                                                } else {
                                                    $day_and_order[$order_date] = (float) $total;
                                                }


                                                // if (isset($_COOKIE['wmc_current_currency']) && $_COOKIE['wmc_current_currency'] != 'BTC') {
                                                //     if (isset($day_and_order[$order_date])) {
                                                //         $day_and_order[$order_date] += (float) $total * $curr_exchange_rate;
                                                //     } else {
                                                //         $day_and_order[$order_date] = (float) $total * $curr_exchange_rate;
                                                //     }
                                                // } else {
                                                //     if (isset($day_and_order[$order_date])) {
                                                //         $day_and_order[$order_date] += (float) $total;
                                                //     } else {
                                                //         $day_and_order[$order_date] = (float) $total;
                                                //     }
                                                // }


                                                // if (isset($day_and_order[$order_date])) {
                                                //     $day_and_order[$order_date] += (float) $total;
                                                // } else {
                                                //     $day_and_order[$order_date] = (float) $total;
                                                // }
                                            }
                                        }

                                        // echo '<pre>';
                                        // var_dump($day_and_order);
                                        // echo '</pre>';
                                
                                        ?>

                                        <div class="weekly-daily-charts mt-5">

                                            <?php if (is_array($week_order) && count($week_order) > 0) { ?>

                                                <!-- weekly chart -->
                                                <input type="hidden" id="weekly_sales"
                                                    value='<?php echo json_encode($week_order); ?>'>
                                                <div class="weekly-chart-container">
                                                    <h5>Best Week</h3>
                                                        <canvas id="best-week-chart"></canvas>
                                                </div>

                                            <?php } ?>


                                            <?php if (is_array($day_and_order) && count($day_and_order) > 0) { ?>

                                                <!-- daily chart -->
                                                <input type="hidden" id="daily_sales"
                                                    value='<?php echo json_encode($day_and_order, JSON_FORCE_OBJECT); ?>'>
                                                <div class="daily-chart-container">
                                                    <h5>Best Day</h3>
                                                        <canvas id="best-days-chart"></canvas>
                                                </div>

                                            <?php } ?>
                                        </div>

                                    </div>
                                    <!-- --------  SALES  ---------- -->
                                    <div class="tab-pane fade" id="so-my-spitout-sales" role="tabpanel"
                                        aria-labelledby="so-my-spitout-sales-tab">
                                        <div class="row align-items-center">
                                            <div class='col-lg-12'>
                                                <h4>History</h4>
                                            </div>
                                            <div class="search-sales d-flex col-lg-6 col-md-6">
                                                <form action="" method="GET" id="my-spitout-sales-table-search">
                                                    <input type="number" name="invoice-num" id="invoice-num"
                                                        value="<?php echo isset($_GET['invoice-num']) && !empty($_GET['invoice-num']) ? $_GET['invoice-num'] : ''; ?>">
                                                    <button type="submit" id="invoice-search"
                                                        name="invoice-search">Search</button>
                                                </form>
                                            </div>

                                            <!-- sort the tables -->
                                            <div class="col-lg-6 col-md-6">
                                                <label for="sort-sales"></label>
                                                <select name="sort-sales" id="sort-sales" class="so-my-spitout-sorts">
                                                    <option id="" value="">Sort by</option>
                                                    <option id="" value="amount_lh">Amount: Low to High</option>
                                                    <option id="" value="amount_hl">Amount: High to Low</option>
                                                    <option id="" value="status_complete">Status: Completed</option>
                                                    <option id="" value="status_not_complete">Status: Needs Delivery</option>
                                                </select>
                                                <select name="so-my-spitout-sort-sales" id="so-my-spitout-sort-sales">
                                                    <option id="so-my-spitout_salesOption"></option>
                                                </select>
                                                <div class="so-my-spitout-calendar-datepicker">
                                                    <!-- <label for="date"></label>
                                            <input type="date" id="so-my-spitout-purchaseOn" name="purchaseOn"> -->
                                                    <p> <span class="so-custom-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none">
                                                                <path
                                                                    d="M16.7502 3.56V2C16.7502 1.59 16.4102 1.25 16.0002 1.25C15.5902 1.25 15.2502 1.59 15.2502 2V3.5H8.75023V2C8.75023 1.59 8.41023 1.25 8.00023 1.25C7.59023 1.25 7.25023 1.59 7.25023 2V3.56C4.55023 3.81 3.24023 5.42 3.04023 7.81C3.02023 8.1 3.26023 8.34 3.54023 8.34H20.4602C20.7502 8.34 20.9902 8.09 20.9602 7.81C20.7602 5.42 19.4502 3.81 16.7502 3.56Z"
                                                                    fill="#292D32" />
                                                                <path
                                                                    d="M20 9.83984H4C3.45 9.83984 3 10.2898 3 10.8398V16.9998C3 19.9998 4.5 21.9998 8 21.9998H16C19.5 21.9998 21 19.9998 21 16.9998V10.8398C21 10.2898 20.55 9.83984 20 9.83984ZM9.21 18.2098C9.16 18.2498 9.11 18.2998 9.06 18.3298C9 18.3698 8.94 18.3998 8.88 18.4198C8.82 18.4498 8.76 18.4698 8.7 18.4798C8.63 18.4898 8.57 18.4998 8.5 18.4998C8.37 18.4998 8.24 18.4698 8.12 18.4198C7.99 18.3698 7.89 18.2998 7.79 18.2098C7.61 18.0198 7.5 17.7598 7.5 17.4998C7.5 17.2398 7.61 16.9798 7.79 16.7898C7.89 16.6998 7.99 16.6298 8.12 16.5798C8.3 16.4998 8.5 16.4798 8.7 16.5198C8.76 16.5298 8.82 16.5498 8.88 16.5798C8.94 16.5998 9 16.6298 9.06 16.6698C9.11 16.7098 9.16 16.7498 9.21 16.7898C9.39 16.9798 9.5 17.2398 9.5 17.4998C9.5 17.7598 9.39 18.0198 9.21 18.2098ZM9.21 14.7098C9.02 14.8898 8.76 14.9998 8.5 14.9998C8.24 14.9998 7.98 14.8898 7.79 14.7098C7.61 14.5198 7.5 14.2598 7.5 13.9998C7.5 13.7398 7.61 13.4798 7.79 13.2898C8.07 13.0098 8.51 12.9198 8.88 13.0798C9.01 13.1298 9.12 13.1998 9.21 13.2898C9.39 13.4798 9.5 13.7398 9.5 13.9998C9.5 14.2598 9.39 14.5198 9.21 14.7098ZM12.71 18.2098C12.52 18.3898 12.26 18.4998 12 18.4998C11.74 18.4998 11.48 18.3898 11.29 18.2098C11.11 18.0198 11 17.7598 11 17.4998C11 17.2398 11.11 16.9798 11.29 16.7898C11.66 16.4198 12.34 16.4198 12.71 16.7898C12.89 16.9798 13 17.2398 13 17.4998C13 17.7598 12.89 18.0198 12.71 18.2098ZM12.71 14.7098C12.66 14.7498 12.61 14.7898 12.56 14.8298C12.5 14.8698 12.44 14.8998 12.38 14.9198C12.32 14.9498 12.26 14.9698 12.2 14.9798C12.13 14.9898 12.07 14.9998 12 14.9998C11.74 14.9998 11.48 14.8898 11.29 14.7098C11.11 14.5198 11 14.2598 11 13.9998C11 13.7398 11.11 13.4798 11.29 13.2898C11.38 13.1998 11.49 13.1298 11.62 13.0798C11.99 12.9198 12.43 13.0098 12.71 13.2898C12.89 13.4798 13 13.7398 13 13.9998C13 14.2598 12.89 14.5198 12.71 14.7098ZM16.21 18.2098C16.02 18.3898 15.76 18.4998 15.5 18.4998C15.24 18.4998 14.98 18.3898 14.79 18.2098C14.61 18.0198 14.5 17.7598 14.5 17.4998C14.5 17.2398 14.61 16.9798 14.79 16.7898C15.16 16.4198 15.84 16.4198 16.21 16.7898C16.39 16.9798 16.5 17.2398 16.5 17.4998C16.5 17.7598 16.39 18.0198 16.21 18.2098ZM16.21 14.7098C16.16 14.7498 16.11 14.7898 16.06 14.8298C16 14.8698 15.94 14.8998 15.88 14.9198C15.82 14.9498 15.76 14.9698 15.7 14.9798C15.63 14.9898 15.56 14.9998 15.5 14.9998C15.24 14.9998 14.98 14.8898 14.79 14.7098C14.61 14.5198 14.5 14.2598 14.5 13.9998C14.5 13.7398 14.61 13.4798 14.79 13.2898C14.89 13.1998 14.99 13.1298 15.12 13.0798C15.3 12.9998 15.5 12.9798 15.7 13.0198C15.76 13.0298 15.82 13.0498 15.88 13.0798C15.94 13.0998 16 13.1298 16.06 13.1698C16.11 13.2098 16.16 13.2498 16.21 13.2898C16.39 13.4798 16.5 13.7398 16.5 13.9998C16.5 14.2598 16.39 14.5198 16.21 14.7098Z"
                                                                    fill="#292D32" />
                                                            </svg>
                                                        </span><input type="text" id="datepicker"></p>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="so-my-spitout-sales-table">
                                            <div class="table-responsive-lg">

                                                <?php
                                                $total_orders = count(wc_get_orders([
                                                    'limit' => -1,
                                                    'meta_key' => 'seller_id',
                                                    'meta_value' => $seller_id,
                                                    'meta_compare' => 'LIKE',
                                                    'return' => 'ids'
                                                ]));
                                                $orders_per_page = 10;
                                                $total_pages = ceil($total_orders / $orders_per_page);

                                                $current_page = max(1, get_query_var('paged'));
                                                $offset = ($current_page - 1) * $orders_per_page;

                                                $args = [
                                                    'limit' => $orders_per_page,
                                                    'offset' => $offset,
                                                    'meta_key' => 'seller_id',
                                                    'meta_value' => $seller_id,
                                                    'meta_compare' => 'LIKE',
                                                    'return' => 'ids',
                                                ];

                                                $sales_orders = wc_get_orders($args);

                                                if (isset($_GET['invoice-search'])) {

                                                    if (isset($_GET['invoice-num']) && !empty($_GET['invoice-num'])) {
                                                        $sales_orders = [(int) $_GET['invoice-num']];
                                                    }
                                                }

                                                ?>


                                                <?php if (count($sales_orders) > 0) { ?>
                                                    <table id="seller-sales" class="table table-borderless table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Invoice</th>
                                                                <th>Customers</th>
                                                                <th>Purchase On</th>
                                                                <th>Amount</th>
                                                                <th>Status</th>
                                                                <th>Detail</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php
                                                            foreach ($sales_orders as $key => $order) {
                                                                # code...
                                                                $order_info = wc_get_order($order);
                                                                // Check if the order object exists
                                                                if ($order_info) {

                                                                    $customer_id = $order_info->get_customer_id();
                                                                    $order_data = $order_info->get_data();

                                                                    $get_customer_name = $get_current_seller_info['seller_display_name'];
                                                                    $order_billing_address_1 = $order_data['billing']['address_1'];
                                                                    $order_billing_address_2 = $order_data['billing']['address_2'];
                                                                    $order_billing_city = $order_data['billing']['city'];
                                                                    $order_billing_state = $order_data['billing']['state'];
                                                                    $order_billing_postcode = $order_data['billing']['postcode'];
                                                                    $order_billing_country = $order_data['billing']['country'];
                                                                    $final_delivery_address = $order_billing_country . ',' . $order_billing_city . ',' . $order_billing_address_1 . ',' . $order_billing_postcode;

                                                                    // Get customer information
                                                                    $customer_name = $order_info->get_billing_first_name() . ' ' . $order_info->get_billing_last_name();

                                                                    // Get purchase date
                                                                    $purchase_date = $order_info->get_date_created()->format('m/d/Y');

                                                                    // Get order amount
                                                                    $order_amount = $order_info->get_total();
                                                                    $formatted_order_amount = dynamic_currency_for_buyer_seller_order_pages($order);

                                                                    // Get order status
                                                                    $order_status = $order_info->get_status();
                                                                    if ($order_status == 'completed') {
                                                                        $order_status_check = ' <td class="so-my-spitout-sales-status so-complete">Completed</td>';
                                                                    } elseif ($order_status == 'processing') {
                                                                        $order_status_check = ' <td class="so-my-spitout-sales-status so-needsDelivery">Shipped</td>';
                                                                    } elseif ($order_status == 'on-hold') {
                                                                        $order_status_check = ' <td class="so-my-spitout-sales-status so-needsDelivery">Awaiting Shipment</td>';
                                                                    } elseif ($order_status == 'cancelled') {
                                                                        $order_status_check = '<td class="so-my-spitout-sales-status so-inDelivery">Cancelled</td>';
                                                                    }

                                                                    $order_items = [];
                                                                    foreach ($order_info->get_items() as $item_id => $item_data) {
                                                                        $curr_product_id = $item_data->get_product_id();
                                                                        $product_name = $item_data->get_name();
                                                                        $quantity = $item_data->get_quantity();
                                                                        if(so_get_dynamic_currency() == "BTC"){
                                                                            $final_price = $item_data->get_meta('_btc_price');
                                                                        }else{
                                                                            $final_price = $item_data->get_meta('_usd_price');
                                                                        }
                                                                        // $get_price = $item_data->get_total();
                                                                        // $order_items[] = [$product_name, $quantity, dynamic_currency_generator_for_products($curr_product_id)];
                                                                        $order_items[] = [$product_name, $quantity, so_get_dynamic_currency_symbol() . $final_price];
                                                                    }

                                                                    $sale_details = [
                                                                        'id' => $order,
                                                                        'tracking_id' => get_post_meta($order, 'order_tracking_id', true),
                                                                        'total' => $formatted_order_amount,
                                                                        'shipping_address' => $final_delivery_address,
                                                                        'customer_name' => $customer_name,
                                                                        'date' => $purchase_date,
                                                                        'status' => $order_status,
                                                                        'items' => $order_items
                                                                    ];

                                                                    $json_data = htmlspecialchars(json_encode($sale_details, JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');

                                                                    echo '
                                                                <tr>
                                                                    <td class="so-my-spitout-sales-invoice">#' . $order . '</td>
                                                                    <td class="so-my-spitout-sales-customer">' . $customer_name . '</td>
                                                                    <td class="so-my-spitout-sales-purchase">' . $purchase_date . '</td>
                                                                    <td class="so-my-spitout-sales-amount">' . $formatted_order_amount . '</td>
                                                                    ' . $order_status_check . '
                                                                    <td class="so-my-spitout-sales-viewModalbtn">
                                                                        <button class="view-sale-details" type="button" data-toggle="modal" data-target="#sale-details" data-order-details="' . $json_data . '" >
                                                                            <span class="so-custom-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512">
                                                                                    <path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                                                                                </svg>
                                                                            </span>
                                                                            View
                                                                        </button>
                                                                        
                                                                    </td>
                                                                </tr>
                                                                ';
                                                                } else {
                                                                    echo "<p class='so-error-msg mt-2'>Order ID $order not found.</p>";
                                                                }
                                                            }


                                                            ?>
                                                        </tbody>
                                                    </table>

                                                    <?php
                                                } else {
                                                    echo '<p>No orders found</p>';
                                                }
                                                ?>

                                                <?php 
                                                    if (count($sales_orders) > 10) {
                                                        echo '<div class="sales-pagination">';
                                                            $big = 999999999; // need an unlikely integer
                                                            echo paginate_links([
                                                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                                                'format' => '?paged=%#%',
                                                                'current' => $current_page,
                                                                'total' => $total_pages,
                                                                'prev_text' => __('« Prev'),
                                                                'next_text' => __('Next »'),
                                                            ]);
                                                        echo '</div>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -------- MY PRODUCT ---------- -->
                                    <div class="tab-pane fade" id="so-my-spitout-myProduct" role="tabpanel"
                                        aria-labelledby="so-my-spitout-myProduct-tab">
                                        <h4>My Products</h4>
                                        <?php

                                        //get drafted products
                                        $draft_products = wc_get_products(
                                            array(
                                                'status' => 'draft',
                                                'limit' => -1,
                                                'author' => $seller_id

                                            )
                                        );

                                        //combine published and drafted products
                                        $products = array_merge($my_products, $draft_products);
                                        // $products=[];
                                
                                        ?>

                                        <!-- sort the tables -->
                                        <label for="sort-products"></label>
                                        <select name="sort-products" id="sort-products" class="so-my-spitout-sorts">
                                            <option id="" value="">Sort by</option>
                                            <option id="" value="price_lh">Price: Low to High</option>
                                            <option id="" value="price_hl">Price: High to Low</option>
                                            <option id="" value="sales_lh">Sales: Low to High</option>
                                            <option id="" value="sales_hl">Sales: High to Low</option>
                                        </select>
                                        <select name="so-my-spitout-sort-products" id="so-my-spitout-sort-products">

                                            <option id="so-my-spitout_productsOption"></option>

                                        </select>

                                        <!-- Add new product popup button -->
                                        <!--   first check if user is verified  -->
                                        <?php if ((int) get_user_meta($seller_id, 'is_verified', true) == 1) { ?>
                                            <button type="button" class="btn btn-primary so-my-spitout-addNewProduct-btn"
                                                data-toggle="modal" data-target="#addNewProductModal">
                                                Add new product
                                            </button>
                                        <?php } else {

                                            echo '  <button type="button" class="btn btn-primary so-my-spitout-addNewProduct-btn">
                                                <a href="' . $get_seller_url . '">Please verify your account </a>
                                            </button>';
                                        } ?>

                                        <!-- Add new product popup -->
                                        <div class="modal fade so-my-spitout-modal" id="addNewProductModal" tabindex="-1"
                                            aria-labelledby="add-new" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="add-new">Add new product</h4>
                                                        <button type="button" id="close-add-product-modal" class="close"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="update-product-loader add-product-loader">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif"
                                                            alt="Loading" style="width:3rem;">
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="add_new_product_form" action="" method="post"
                                                            enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <label for="product_name">Product Name:</label>
                                                                <input type="text" name="product_name" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="price">Price (USD):</label>
                                                                <input type="number" step="any" name="price" required>
                                                            </div>
                                                            <div class="form-group so-my-spitout-form-full">
                                                                <label for="additional_info">Additional Information:</label>
                                                                <textarea name="additional_info"></textarea>
                                                            </div>
                                                            <div class="form-group so-my-spitout-form-full">
                                                                <label for="additional_info">Category</label>

                                                                <input type="hidden" name="product_icon" value="">

                                                                <select class="so-producticonpicker">
                                                                    <option value=""
                                                                        data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/product-icon-noimage.png">
                                                                        Select Category</option>
                                                                    <!-- <option value="saliva"
                                                                        data-thumbnail="<?php // get_stylesheet_directory_uri(); ?>/assets/img/saliva.png">
                                                                        Saliva</option>
                                                                    <option value="jar"
                                                                        data-thumbnail="<?php //echo get_stylesheet_directory_uri(); ?>/assets/img/jar.png">
                                                                        Jar</option> -->
                                                                        <option value="text" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/text.svg">Text</option>
                                                                        <option value="photo" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/photo.svg">Photo</option>
                                                                        <option value="video" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/video.svg">Video</option>
                                                                        <option value="vr_video" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/vr_video.svg">VR Video</option>
                                                                        <option value="pov_video" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/pov_video.svg">POV Video</option>
                                                                        <option value="livestream" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/livestream.svg">Livestream</option>
                                                                        <option value="couples" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/couples.svg">Couples</option>
                                                                        <option value="cosplay_and_fantasy" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/cosplay_and_fantasy.png">Cosplay & Fantasy</option>
                                                                        <option value="role_play_services" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/role_play_services.png">Role-play Services</option>
                                                                        <option value="virtual_girlfriend" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/virtual_girlfriend.png">Virtual Girlfriend</option>
                                                                        <option value="custom_fetish_requests" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/custom_fetish_requests.png">Custom Fetish Requests</option>
                                                                        <option value="other" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/other.png">Other</option>
                                                                </select>

                                                                <div class="product-icon-select lang-select">
                                                                    <a class="btn-select" value=""></a>
                                                                    <div class="so-addp-b">
                                                                        <ul id="so-addp-a"></ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group so-my-spitout-form-full">
                                                                <label for="product_picture">Product Image:</label>
                                                                <input type="file" name="product_picture" accept="image/*"
                                                                    class="so-my-spitout-upload" />
                                                            </div>
                                                            <div class="form-group so-my-spitout-form-submitBtn">
                                                                <input type="submit" id="" name="add_new_product"
                                                                    value="Add Product">
                                                            </div>
                                                        </form>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <table class="table table-striped so-my-spitout-table" id="seller-products">
                                            <thead>
                                                <th>Name</th>
                                                <th>Created</th>
                                                <th>Sales</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                                <?php

                                                if (count($products) != 0) {

                                                    foreach ($products as $product) {

                                                        $selectedIcon = get_post_meta($product->get_id(), '_product_spitout_icon', true);
                                                        if ($selectedIcon) {
                                                            $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/' . $selectedIcon . '.png';
                                                        } else {
                                                            $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';
                                                        }

                                                        ?>
                                                        <tr class="so-my-spitout-border product-row-<?php echo $product->get_id(); ?>">
                                                            <td class="so-my-spitout-productName">
                                                                <img src="<?php echo $get_product_icon; ?> ">
                                                                <?php echo $product->get_name(); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo (new DateTime($product->get_date_created()))->format('m.d.Y'); ?>
                                                            </td>
                                                            <td>
                                                                <?php echo get_post_meta((int) $product->get_id(), 'total_sales', true); ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                echo static_currency_generator_for_products((int) $product->get_id(), true);
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <!-- Delete product button -->
                                                                <button type="button" class="btn btn-danger so-my-spitout-deteteBtn"
                                                                    data-product-id="<?php echo $product->get_id(); ?>"
                                                                    data-toggle="modal" data-target="#so-my-spitout-deteteModal">
                                                                    Delete
                                                                </button>

                                                                <!-- Edit product button -->
                                                                <button type="button" class="btn btn-warning so-my-spitout-editBtn"
                                                                    data-toggle="modal" data-target="#so-my-spitout-editModal"
                                                                    data-product-id="<?php echo $product->get_id(); ?>"
                                                                    data-product-name="<?php echo $product->get_name(); ?>"
                                                                    data-product-price="<?php echo (json_decode(get_post_meta((int) $product->get_id(), '_regular_price_wmcp', true), true))['USD']; ?>"
                                                                    data-product-info="<?php echo $product->get_short_description(); ?>"
                                                                    data-product-icon="<?php echo $selectedIcon; ?>"
                                                                    data-product-img="<?php echo wp_get_attachment_url(get_post_thumbnail_id((int) $product->get_id())); ?>">
                                                                    Edit
                                                                </button>

                                                                <!-- Hide product button -->
                                                                <button type="submit" class="btn btn-primary so-my-spitout-hideBtn"
                                                                    name="hide_product"
                                                                    data-product-id="<?php echo $product->get_id(); ?>">
                                                                    <?php echo get_post_status($product->get_id()) == 'publish' ? 'Hide' : 'Unhide'; ?>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>


                                        <!-- delete modal -->
                                        <div class="modal fade so-my-spitout-modal" id="so-my-spitout-deteteModal" tabindex="-1"
                                            aria-labelledby="delete product" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <i class="bi bi-trash-fill"></i>
                                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure
                                                            you want to delete this product? This action cannot be
                                                            undone.</h5>
                                                        <button type="button" id="close-delete-product-modal" class="close"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body myspitout-modal-delete-btn">
                                                        <button type="submit" class="delete-product" name="delete_product"
                                                            data-product-id="">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- edit modal -->
                                        <div class="modal fade" id="so-my-spitout-editModal" tabindex="-1"
                                            aria-labelledby="edit product" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="edit product">Edit product</h5>
                                                        <button type="button" class="close" id="close-edit-product-modal"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="update-product-loader">
                                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif"
                                                            alt="Loading" style="width:3rem;">
                                                    </div>
                                                    <div class="modal-body myspitout-edit-product-modal">
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <input type="hidden" name="product_id" value="">
                                                            <div class="form-group">
                                                                <label for="product_name">Product Name:</label>
                                                                <input type="text" name="product_name" value="" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="price">Price (USD):</label>
                                                                <input type="number" step="1" name="price" value="" required>
                                                            </div>
                                                            <div class="form-group so-my-spitout-form-full">
                                                                <label for="additional_info">Additional
                                                                    Information:</label>
                                                                <textarea name="additional_info"></textarea>
                                                            </div>
                                                            <div class="form-group so-my-spitout-form-full">
                                                                <label for="product_icon">Category</label>
                                                                <input type="hidden" name="product_icon" value="">
                                                                <select class="so-producticonpicker-edit"
                                                                    name="product_icon_choose">
                                                                    <option value=""
                                                                        data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/product-icon-noimage.png">
                                                                        Select Category</option>
                                                                    <!-- <option value="saliva"
                                                                        data-thumbnail="<?php //echo get_stylesheet_directory_uri(); ?>/assets/img/saliva.png">
                                                                        Saliva</option>
                                                                    <option value="jar"
                                                                        data-thumbnail="<?php //echo get_stylesheet_directory_uri(); ?>/assets/img/jar.png">
                                                                        Jar</option> -->
                                                                        <option value="text" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/text.svg">Text</option>
                                                                        <option value="photo" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/photo.svg">Photo</option>
                                                                        <option value="video" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/video.svg">Video</option>
                                                                        <option value="vr_video" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/vr_video.svg">VR Video</option>
                                                                        <option value="pov_video" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/pov_video.svg">POV Video</option>
                                                                        <option value="livestream" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/livestream.svg">Livestream</option>
                                                                        <option value="couples" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/couples.svg">Couples</option>
                                                                        <option value="cosplay_and_fantasy" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/cosplay_and_fantasy.png">Cosplay & Fantasy</option>
                                                                        <option value="role_play_services" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/role_play_services.png">Role-play Services</option>
                                                                        <option value="virtual_girlfriend" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/virtual_girlfriend.png">Virtual Girlfriend</option>
                                                                        <option value="custom_fetish_requests" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/custom_fetish_requests.svg">Custom Fetish Requests</option>
                                                                        <option value="other" data-thumbnail="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/other.png">Other</option>
                                                                </select>

                                                                <div class="product-icon-selecte">
                                                                    <a class="btn-selecte" value=""></a>
                                                                    <div class="so-editp-b">
                                                                        <ul id="so-editp-a"></ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group so-my-spitout-form-full">
                                                                <label for="product_picture">Product Image:</label>
                                                                <input type="file" name="product_picture" accept="image/*"
                                                                    class="so-my-spitout-upload" />
                                                            </div>

                                                            <!-- product image -->
                                                            <img class="product_img" src="" alt="product image" width="150px">

                                                        </form>

                                                        <button class="update_product" value="Update Product">Update
                                                            Product</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -------- EARNINGS ---------- -->
                                    <div class="tab-pane fade" id="so-my-spitout-earnings" role="tabpanel"
                                        aria-labelledby="so-my-spitout-earnings-tab">
                                        <h4>Your Earning</h4>
                                        <div class="so-my-spitout-wallet">
                                            <p>
                                                <span class="so-custom-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M20.97 16.08C20.73 18.75 18.8 20.5 16 20.5H7C4.24 20.5 2 18.26 2 15.5V8.5C2 5.78 3.64 3.88 6.19 3.56C6.45 3.52 6.72 3.5 7 3.5H16C16.26 3.5 16.51 3.51 16.75 3.55C19.14 3.83 20.76 5.5 20.97 7.92C21 8.21 20.76 8.45 20.47 8.45H18.92C17.96 8.45 17.07 8.82 16.43 9.48C15.67 10.22 15.29 11.26 15.38 12.3C15.54 14.12 17.14 15.55 19.04 15.55H20.47C20.76 15.55 21 15.79 20.97 16.08Z"
                                                            fill="#292D32"></path>
                                                        <path
                                                            d="M22.0002 10.9692V13.0292C22.0002 13.5792 21.5602 14.0292 21.0002 14.0492H19.0402C17.9602 14.0492 16.9702 13.2592 16.8802 12.1792C16.8202 11.5492 17.0602 10.9592 17.4802 10.5492C17.8502 10.1692 18.3602 9.94922 18.9202 9.94922H21.0002C21.5602 9.96922 22.0002 10.4192 22.0002 10.9692Z"
                                                            fill="#292D32"></path>
                                                    </svg>
                                                </span>Wallet: <span>$2800.65</span>
                                            </p>
                                        </div>
                                        <p class="so-my-spitout-wallet-text">Your earnings balance is below $25.00 which is
                                            the
                                            minimum to request a payout.</p>
                                        <form class="form-inline so-my-spitout-wallet-form">
                                            <div class="form-group">
                                                <label for="soMyspitoutWalletPrice"></label>
                                                <input type="text" class="form-control" id="soMyspitoutWalletPrice"
                                                    placeholder="$100.00">
                                            </div>
                                            <button type="submit" class="btn">Request payout</button>
                                        </form>
                                        <h5>Earning history</h5>
                                        <div class="so-my-spitout-earning-table">
                                            <div class="table-responsive-lg">
                                                <table class="table table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th>Purchase On</th>
                                                            <th>Date</th>
                                                            <th>Amount</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Earnings payout
                                                                request <a class="" href="#">#1583746</a></td>
                                                            <td class="so-my-spitout-earning-purchase">Spitout</td>
                                                            <td class="so-my-spitout-earning-date">08.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">-$100</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Payment <a
                                                                    class="so-my-spitout-desc-color" href="#">#1583746</a>
                                                            </td>
                                                            <td class="so-my-spitout-earning-purchase">Enrice7865</td>
                                                            <td class="so-my-spitout-earning-date">07.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">+$100</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Earnings payout
                                                                request <a class="" href="#">#1583746</a></td>
                                                            <td class="so-my-spitout-earning-purchase">Spitout</td>
                                                            <td class="so-my-spitout-earning-date">06.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">-$1250</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Payment <a
                                                                    class="so-my-spitout-desc-color" href="#">#886422 </a>
                                                            </td>
                                                            <td class="so-my-spitout-earning-purchase">Janson Martezi</td>
                                                            <td class="so-my-spitout-earning-date">05.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">+$1250</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Earnings payout
                                                                request <a class="" href="#">#1583746</a></td>
                                                            <td class="so-my-spitout-earning-purchase">Spitout</td>
                                                            <td class="so-my-spitout-earning-date">08.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">-$2</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Payment <a
                                                                    class="so-my-spitout-desc-color" href="#">#1583746</a>
                                                            </td>
                                                            <td class="so-my-spitout-earning-purchase">Enrice7865</td>
                                                            <td class="so-my-spitout-earning-date">07.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">+$100</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Earnings payout
                                                                request <a class="" href="#">#1583746</a></td>
                                                            <td class="so-my-spitout-earning-purchase">Spitout</td>
                                                            <td class="so-my-spitout-earning-date">06.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">-$1250</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="so-my-spitout-earning-description">Payment <a
                                                                    class="so-my-spitout-desc-color" href="#">#886422 </a>
                                                            </td>
                                                            <td class="so-my-spitout-earning-purchase">Janson Martezi</td>
                                                            <td class="so-my-spitout-earning-date">05.07.2023</td>
                                                            <td class="so-my-spitout-earning-amount">+$1250</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- -------- REPORTS ---------- -->
                                    <!-- <div class="tab-pane fade" id="so-my-spitout-reports" role="tabpanel" aria-labelledby="so-my-spitout-reports-tab">
                                           

                                        <div class="so-my-spitout-select">
                                            <div class="so-my-spitout-select__dropdown">
                                                <button href="#" role="button" data-value="" class="so-my-spitout-select-dropdown__button">
                                                    <span>Sort by</span> <i class=""></i>
                                                </button>
                                                <ul class="so-my-spitout-select__list">
                                                    <li data-value="1" class="so-my-spitout-select-dropdown__list-item selected">Price: Low to High</li>
                                                    <li data-value="2" class="so-my-spitout-select-dropdown__list-item">Price: High to Low</li>
                                                    <li data-value="3" class="so-my-spitout-select-dropdown__list-item">Sales: Low to High</li>
                                                    <li data-value="4" class="so-my-spitout-select-dropdown__list-item">Sales: High to Low</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- my spiout sales view modal -->
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
                                <p class="customer_name">Customer name: </p>

                                <p class="status"></p>
                            </div>

                            <!-- <table class="sales-view-modal-table">





                                <tbody>
                                </tbody>
                            </table> -->
                        </div>
                    </div>
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
