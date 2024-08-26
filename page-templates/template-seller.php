<?php

/**
 * Template Name: Seller Page
 * @package spitout
 */
get_header();
$filtered_users = array();
$errors = '';
$all_sellers = get_users(
    array(
        'role' => 'seller',
    )
);

// Define the query arguments
$seller_args = array(
    'post_type' => 'spit-category', // Your custom post type
    'posts_per_page' => -1, // Retrieve all posts
);

// Execute the query
$seller_type_query = new WP_Query($seller_args);
// Get the count of posts
$seller_cat_count = $seller_type_query->post_count;
if ($all_sellers) {
    // Check if the form is submitted
    if (isset($_POST['apply-button'])) {
        $users_filtered_by_category = [];
        $users_filtered_by_popularity = [];
        $users_filtered_by_age = [];
        $users_filtered_by_active_status = [];
        $users_filtered_by_saliva_price = [];
        $users_filtered_by_location = [];
        //filter sellers based on the category
        if (!empty($_POST["category"])) {
            $selected_category = sanitize_text_field($_POST['category']);
            foreach ($all_sellers as $user) {
                $categories = get_user_meta($user->ID, 'so_category', true);
                $user_id = $user->ID;
                if (is_array($categories) && in_array($selected_category, $categories)) {
                    $users_filtered_by_category[] = $user_id;
                    $users_filtered_by_category = spitoutFilterExistingUsers($users_filtered_by_category);
                }
            }
            wp_reset_postdata();
        }
        //Filter sellers based on top-sellers
        if (!empty($_POST["top-sellers"])) {
            $selected_top_seller = sanitize_text_field($_POST['top-sellers']);
            if ($selected_top_seller == 'most-popular') {
                // var_dump('most-popular');
                $users_filtered_by_popularity = array_keys(spitout_get_popular_sellers());
            } else if ($selected_top_seller == 'new-sellers') {
                $users_filtered_by_popularity = array_keys(spitout_get_newest_users());
            }
        }
        //Filter sellers based on age
        if (!empty($_POST["age-start"]) || !empty($_POST["age-end"])) {
            // die($_POST["age-end"]);
            // $selected_age_start = $_POST['age-start'] != '' ? intval($_POST['age-start']) : 0;
            // $selected_age_end = $_POST['age-end'] !== '' ? intval($_POST['age-end']) : PHP_INT_MAX;
            $selected_age_start = $_POST['age-start'];
            $selected_age_end = $_POST['age-end'];
            foreach ($all_sellers as $user) {
                $post_meta_date = get_user_meta($user->ID, 'so_dob', true);
                $date_from_post = DateTime::createFromFormat('m/d/Y', $post_meta_date);
                // Check if $date_from_post is a valid DateTime object
                if ($date_from_post !== false) {
                    $today_date = new DateTime();
                    $interval = $date_from_post->diff($today_date);
                    $user_age = $interval->y; // Get the difference in years
                    //Check seller age against the filter values
                    if ($user_age >= $selected_age_start && $user_age <= $selected_age_end) {
                        $users_filtered_by_age[] = $user->ID;
                        $users_filtered_by_age = spitoutFilterExistingUsers($users_filtered_by_age);
                    }
                }
            }
        }
        //Filter sellers based on whether they are active or not
        if (!empty($_POST["online"])) {
            $selected_online = sanitize_text_field($_POST['online']);
            if ($selected_online == 'active-now') {
                foreach ($all_sellers as $user) {
                    $user_id = $user->ID;
                    // $status = get_user_meta($user_id, 'so_online_status', true);
                    $status = get_user_meta($user_id, "cpmm_user_status", true);
                    if ($status == 'logged_in') {
                        $users_filtered_by_active_status[] = $user_id;
                    }
                }
            } else if ($selected_online == 'offline') {
                foreach ($all_sellers as $user) {
                    $user_id = $user->ID;
                    $status = get_user_meta($user_id, 'cpmm_user_status', true);
                    if ($status != 'logged_in') {
                        $users_filtered_by_active_status[] = $user_id;
                        $users_filtered_by_active_status = spitoutFilterExistingUsers($users_filtered_by_active_status);
                    }
                }
            }
        }
        //Filter sellers based on their product price
        if (!empty($_POST["price-start"]) || !empty($_POST["price-end"])) {
            // $selected_price_start = isset($_POST['price-start']) && $_POST['price-start'] !== '' ? (intval($_POST['price-start']) <= intval($_POST['price-end']) ? intval($_POST['price-start']) : intval($_POST['price-end'])) : 0;
            // $selected_price_end = isset($_POST['price-start']) && $_POST['price-start'] !== '' ? (intval($_POST['price-end']) >= intval($_POST['price-start']) ? intval($_POST['price-end']) : intval($_POST['price-start'])) : PHP_INT_MAX;
            $selected_price_start = $_POST['price-start'] !== '' ? floatval($_POST['price-start']) : 0;
            $selected_price_end = !empty($_POST['price-end']) ? floatval($_POST['price-end']) : PHP_INT_MAX;
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
            //Pull all products
            $products = new WP_Query($args);
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    $price = floatval(get_post_meta(get_the_ID(), '_price', true));
                    //Check price of product against the filtered values
                    if ($price >= $selected_price_start && $price <= $selected_price_end) {
                        $seller_id = get_post_field('post_author', get_the_ID());
                        // Check if the user has products before adding to the filtered array
                        $user_has_products = count_user_posts($seller_id, 'product') > 0;
                        if ($user_has_products && !in_array($seller_id, $users_filtered_by_saliva_price)) {
                            $users_filtered_by_saliva_price[] = (int) $seller_id;
                            $users_filtered_by_saliva_price = spitoutFilterExistingUsers($users_filtered_by_saliva_price);
                        }
                        //                         if (!in_array($seller_id, $users_filtered_by_saliva_price)) {
                        //                             $users_filtered_by_saliva_price[] = (int) $seller_id;
                        //                             $users_filtered_by_saliva_price = spitoutFilterExistingUsers($users_filtered_by_saliva_price);
                        //                         }
                    }
                }
            }
            wp_reset_postdata();
        }
        //Filter sellers based on location
        if (!empty($_POST["location"])) {
            $selected_location = sanitize_text_field($_POST["location"]);
            foreach ($all_sellers as $user) {
                $user_location = get_user_meta($user->ID, "so_location", true);
                if ($user_location == $selected_location) {
                    $users_filtered_by_location[] = $user->ID;
                }
            }
        }
        $filter_list = [];
        $temp_filter_list = [
            $users_filtered_by_category,
            $users_filtered_by_popularity,
            $users_filtered_by_age,
            $users_filtered_by_active_status,
            $users_filtered_by_saliva_price,
            $users_filtered_by_location
        ];
        foreach ($temp_filter_list as $arr) {
            if (!empty($arr)) {
                $filter_list[] = $arr;
            }
        }
        if (!empty($filter_list)) { //&& count($filter_list) > 1
            $filtered_users = call_user_func_array('array_intersect', $filter_list);
        } else if (empty($_POST["category"]) && empty($_POST["top-sellers"]) && empty($_POST["age-start"]) && empty($_POST["age-end"]) && empty($_POST["online"]) && empty($_POST["price-start"]) && empty($_POST["price-end"]) && empty($_POST["location"])) {
            foreach ($all_sellers as $seller) {
                $filtered_users[] = $seller->ID;
            }
        }
        if (empty($filtered_users)) {
            $errors =
                '
        <section class="so-ms-form so-feed-new-container">
            <div class="container-fluid" id="grad1">
                <div class="row justify-content-center mt-0">
                    <div class="col-lg-12">
                        <div class=" px-0 pt-4 pb-0 mt-3 mb-3">
                            <div class="row">
                                <div class="col-md-12 mx-0">
                                    <div id="msform">
                                        <fieldset id="so-verify-failed">
                                            <div class="form-card register-step-completed so-registration-failed">
                                                <div class="row no-sellers-notice">
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
                                                        <h4>Unfortunately</h4>
                                                    </div>
                                                    <div class="col-lg-12 d-flex align-items-center">
                                                        <h5>No sellers found. Try with different keywords. </h5>
                                                    </div>
                                                    <div class="col-lg-12 go-back-reg-failed">
                                                        <a href="/spitout/seller">Go back</a>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
        }
    }

?>
    <div class="container sellers-page-heading mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="seller-search-header">
                    <div class="seller-header-top">
                        <h4>Spitout <span>Sellers</span></h4>
                        <div class="seller-number"><span><?php echo esc_html($seller_cat_count) ?> Sellers</span></div>
                    </div>
                    <div class="seller-search-form">
                        <form>
                            <input type="input" name="seller_search" value="" placeholder="Search Seller">
                            <i class="bi bi-search"></i>
                        </form>

                        <div class="seller-search-categories">
                            <?php // Check if there are any posts
                            if ($seller_type_query->have_posts()) :
                                while ($seller_type_query->have_posts()) :
                                    $seller_type_query->the_post();
                                    // Get the post ID
                                    $post_id = get_the_ID();
                                    // Get the post title
                                    $title = get_the_title();
                                    $featured_image_id = get_post_meta($post_id,'_thumbnail_id',true);
                                    // Get the featured image URL
                                    $featured_image_url = wp_get_attachment_url($featured_image_id, 'full') ? wp_get_attachment_url($featured_image_id, 'full') : get_template_directory_uri().'/assets/img/user.png' ; 
                                    ?>
                                    <figure>
                                        <img src="<?php echo esc_url($featured_image_url); ?>" alt="seller-image">
                                        <h5 class="text-center"><?php echo esc_html($title); ?></h5>
                                    </figure>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<p>No posts found.</p>';
                            endif; ?>
                            <figure class="seller-more">
                                <i class="bi bi-three-dots"></i>
                                <h5 class="text-center">More</h5>
                            </figure>

                        </div>
                    </div>
                </div>
                <div class="filter-pannel">
                    <form class="seller-filter-dropdown-form filter" action="" method="POST">
                        <i class="bi bi-caret-up-fill"></i>
                        <div class="seller-filter-dropdowns-lists seller-dropdown-category">
                            <label for="category">Category</label> <br>
                            <select id="category" name="category">
                                <option value="">All</option>
                                <?php
                                $args = array(
                                    'post_type' => 'spit-category',
                                    'posts_per_page' => -1,
                                );
                                $posts = get_posts($args);
                                foreach ($posts as $post) {
                                    $is_selected = isset($_POST['category']) && intval($_POST['category']) == intval(esc_attr($post->ID)) ? 'selected' : '';
                                    echo '<option value="' . esc_attr($post->ID) . '" ' . $is_selected . '>' . esc_html($post->post_title) . '</option>';
                                }
                                wp_reset_postdata();
                                ?>
                            </select>
                        </div>
                        <div class="seller-filter-dropdowns-lists seller-dropdown-topseller">
                            <label for="top-sellers">Top Sellers</label> <br>
                            <select id="top-sellers" name="top-sellers">
                                <?php
                                function isTopSellerSelected($valueToCheck)
                                {
                                    if (isset($_POST['top-sellers'])) {
                                        $selected_value = $_POST['top-sellers'];
                                        return $selected_value == $valueToCheck ? 'selected' : '';
                                    }
                                }
                                ?>
                                <option value="" <?php echo isTopSellerSelected(''); ?>>All</option>
                                <option value="most-popular" <?php echo isTopSellerSelected('most-popular'); ?>>Most Popular
                                </option>
                                <option value="new-sellers" <?php echo isTopSellerSelected('new-sellers'); ?>>New Sellers
                                </option>
                            </select>
                        </div>
                        <div class=" seller-filter-dropdowns-lists seller-dropdown-age">
                            <label for="age-start">Age</label> <br>
                            <select id="age-start" name="age-start">
                                <option value=""> -- </option>
                                <?php
                                if (!empty($all_sellers)) {
                                    $ages = array();
                                    echo 'xxxxxxx';
                                    foreach ($all_sellers as $user) {
                                        $post_meta_date = get_user_meta($user->ID, 'so_dob', true); // Replace with your post meta date
                                        if (gettype($post_meta_date) == 'boolean') {
                                            continue;
                                        }
                                        $date_from_post = DateTime::createFromFormat('m/d/Y', $post_meta_date);
                                        // Check if $date_from_post is a valid DateTime object
                                        if (!$date_from_post instanceof DateTime) {
                                            // Handle the error (e.g., log it, provide a default date, etc.)
                                            continue;
                                        }
                                        $today_date = new DateTime();
                                        $interval = $date_from_post->diff($today_date);
                                        $age = $interval->y; // Get the difference in years
                                        // $age = get_user_meta(54, 'so_age', true);
                                        if ($age !== '') {
                                            $ages[] = $age; // Add age to the array if it's not empty
                                        }
                                    }
                                    // Calculate the maximum and minimum ages
                                    $largest_age = max($ages);
                                    $smallest_age = min($ages);
                                    for ($age = $smallest_age; $age <= $largest_age; $age++) {
                                        $is_selected = isset($_POST['age-start']) && intval($_POST['age-start']) == intval($age) ? 'selected' : '';
                                        echo '<option value="' . $age . '" ' . $is_selected . '>' . $age . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <label for="age-end"></label>
                            <select id="age-end" name="age-end">
                                <option value=""> -- </option>
                                <?php
                                if (!empty($all_sellers)) {
                                    for ($age = $largest_age; $age >= $smallest_age; $age--) {
                                        $is_selected = isset($_POST['age-end']) && intval($_POST['age-end']) == intval($age) ? 'selected' : '';
                                        echo '<option value="' . $age . '" ' . $is_selected . '>' . $age . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div id="slider-range-age" class="sellers-filter-slider"></div>
                        <script>
                            jQuery(function() {
                                var minAge = <?php echo $smallest_age; ?>;
                                var maxAge = <?php echo $largest_age; ?>;
                                jQuery("#slider-range-age").slider({
                                    range: true,
                                    min: minAge,
                                    max: maxAge,
                                    values: [minAge, maxAge],
                                    slide: function(event, ui) {
                                        jQuery("#age-start").val(ui.values[0]);
                                        jQuery("#age-end").val(ui.values[1]);
                                    }
                                });
                                jQuery("#age-start").val(jQuery("#slider-range-age").slider("values", 0));
                                jQuery("#age-end").val(jQuery("#slider-range-age").slider("values", 1));
                            });
                        </script>
                        <div class="seller-filter-dropdowns-lists seller-dropdown-activestatus">
                            <label for="online">Online</label> <br>
                            <select id="online" name="online">
                                <?php
                                function isOnlineSelected($valueToCheck)
                                {
                                    if (isset($_POST['online'])) {
                                        $selected_value = $_POST['online'];
                                        // var_dump($selected_value);
                                        return $selected_value == $valueToCheck ? 'selected' : '';
                                    }
                                }
                                ?>
                                <option value="" <?php echo isOnlineSelected(''); ?>>All</option>
                                <option value="active-now" <?php echo isOnlineSelected('active-now'); ?>> Active Now </option>
                                <option value="offline" <?php echo isOnlineSelected('offline'); ?>> Offline </option>
                            </select>
                        </div>
                        <div class="seller-filter-dropdowns-lists seller-dropdown-price">
                            <label for="price-start">Price Saliva</label> <br>
                            <input type="number" step="any" min="0" id="price-start" name="price-start" value="<?php echo isset($_POST['price-start']) ? $_POST['price-start'] : ''; ?>" />
                            <label for="price-end"></label>
                            <input type="number" step="any" min="0" id="price-end" name="price-end" value="<?php echo isset($_POST['price-end']) ? $_POST['price-end'] : ''; ?>" />
                        </div>
                        <div id="slider-range" class="sellers-filter-slider"></div>
                        <script>
                            jQuery(function() {
                                var minPrice = 0;
                                var maxPrice = 1000; // You can set the maximum value according to your requirements
                                jQuery("#slider-range").slider({
                                    range: true,
                                    min: minPrice,
                                    max: maxPrice,
                                    values: [minPrice, maxPrice],
                                    slide: function(event, ui) {
                                        jQuery("#price-start").val(ui.values[0]);
                                        jQuery("#price-end").val(ui.values[1]);
                                    }
                                });
                                jQuery("#price-start").val(jQuery("#slider-range").slider("values", 0));
                                jQuery("#price-end").val(jQuery("#slider-range").slider("values", 1));
                            });
                            jQuery(document).on("pagecreate", function() {
                                jQuery("#slider-range").on('slidestop', function(event) {
                                    console.log("slidestop event fired");
                                });
                            });
                        </script>
                        <div class="seller-filter-dropdowns-lists seller-dropdown-location">
                            <label for="location">Location</label> <br>
                            <select id="location" name="location">
                                <option value="">All</option>
                                <?php
                                $sellers_location = array();
                                foreach ($all_sellers as $seller) {
                                    $seller_location = get_user_meta($seller->ID, "so_location", true);
                                    if (!in_array($seller_location, $sellers_location) && !empty($seller_location)) {
                                        $sellers_location[] = $seller_location;
                                    }
                                }
                                foreach ($sellers_location as $location) {
                                    $is_selected = isset($_POST['location']) && $_POST['location'] == $location ? 'selected' : '';
                                    echo '<option value="' . $location . '" ' . $is_selected . '>' . $location . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="filter-dropdown-delete-icon">
                            <button type="reset" id="reset-button">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <button type="submit" class="apply-button" name="apply-button">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- seller page body========================================= -->
    <div class="so-seller-contents">
        <section class="so-new-seller seller-tab-section mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="seller-tab-col">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-all-tab" data-toggle="pill" data-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true">All</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-active-tab" data-toggle="pill" data-target="#pills-active" type="button" role="tab" aria-controls="pills-active" aria-selected="false">Active</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-popular-tab" data-toggle="pill" data-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular" aria-selected="false">Popular</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-recommended-tab" data-toggle="pill" data-target="#pills-recommended" type="button" role="tab" aria-controls="pills-recommended" aria-selected="false">Recommended</button>
                                </li>
                            </ul>

                            <div class="so-filters-dropdowns">
                                <div class="so-seller-filter-dropdown1 d-none">
                                    <!-- <i class="bi bi-filter-left"></i> -->
                                    <select id="seller-option">
                                        <option value=""></i></option>
                                        <option value="newsellers">New Sellers</option>
                                        <option value="bestsellers">Best Sellers</option>
                                        <option value="mostexp">Most expensive</option>
                                        <option value="leastexp">Least expensive</option>
                                        <option value="sortbyprice">Sort by price</option>
                                    </select>
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/chevron-down.png" alt="" id="seller-dropdown-after-img" class="d-none" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <a href="#" id="seller-filter-dropdown filter">
                                    <div class="so-seller-filter-dropdown2 d-flex">
                                        <h5>Filter</h5><span class="so-custom-icon icon-lightgray">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5401 8.81063C19.1748 8.81063 20.5001 7.48539 20.5001 5.85062C20.5001 4.21586 19.1748 2.89062 17.5401 2.89062C15.9053 2.89062 14.5801 4.21586 14.5801 5.85062C14.5801 7.48539 15.9053 8.81063 17.5401 8.81063Z" fill="#292D32" />
                                                <path d="M6.46 8.81063C8.09476 8.81063 9.42 7.48539 9.42 5.85062C9.42 4.21586 8.09476 2.89062 6.46 2.89062C4.82524 2.89062 3.5 4.21586 3.5 5.85062C3.5 7.48539 4.82524 8.81063 6.46 8.81063Z" fill="#292D32" />
                                                <path d="M17.5401 21.1114C19.1748 21.1114 20.5001 19.7862 20.5001 18.1514C20.5001 16.5166 19.1748 15.1914 17.5401 15.1914C15.9053 15.1914 14.5801 16.5166 14.5801 18.1514C14.5801 19.7862 15.9053 21.1114 17.5401 21.1114Z" fill="#292D32" />
                                                <path d="M6.46 21.1114C8.09476 21.1114 9.42 19.7862 9.42 18.1514C9.42 16.5166 8.09476 15.1914 6.46 15.1914C4.82524 15.1914 3.5 16.5166 3.5 18.1514C3.5 19.7862 4.82524 21.1114 6.46 21.1114Z" fill="#292D32" />
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                                    <div class="row">
                                        <?php
                                        $sellers_ids = [];
                                        if (empty($filtered_users) && empty($errors)) {
                                            foreach ($all_sellers as $seller) {
                                                $sellers_ids[] = $seller->ID;
                                            }
                                            // var_dump('aaa');
                                        } else if (empty($filtered_users) && !empty($errors)) {
                                            echo ($errors);
                                            // var_dump('bbb');
                                        } else if (!empty($filtered_users)) {
                                            $sellers_ids = $filtered_users;
                                            // var_dump('ccc');
                                        }
                                        // echo '<pre>';
                                        // var_dump($sellers_ids);
                                        // echo '</pre>';
                                        foreach ($sellers_ids as $seller_id) {
                                            $attachment_id = (int) get_user_meta($seller_id, 'so_profile_img', true);
                                            $attachment_array = wp_get_attachment_image_src($attachment_id, 'medium'); // if not available than retrieves the original image
                                            if ($attachment_array) {
                                                $seller_img_url = $attachment_array[0]; // URL of the thumbnail image 
                                            }
                                            $seller_img = get_user_meta($seller_id, "so_profile_img", true);
                                            /* if the author avatar is empty it assign a placeholder image */
                                            if (empty($seller_img_url)) {
                                                $seller_img_url = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                            } else {
                                                $seller_img_url = wp_get_attachment_url($seller_img);
                                            }
                                            $seller_data = get_userdata((int) $seller_id);
                                            $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
                                            $seller_url = get_author_posts_url($seller_id);
                                            // $seller_img_url = wp_get_attachment_url($seller_img);
                                            $seller_location = get_user_meta($seller_id, "so_location", true);
                                            // $seller_active_status = get_user_meta($seller_id, 'user_status', true);
                                            $seller_active_status = get_user_meta($seller_id, "cpmm_user_status", true);
                                            if ($seller_active_status == 'logged_in') {
                                                $active_status = 'online';
                                            } else {
                                                $active_status = 'offline';
                                            }
                                            if ($seller_data == false) {
                                                // echo '<p class="text-warning">Sellerid:'.$seller_id.' does not exist</p> <br>';
                                                continue;
                                            }
                                        ?>

                                            <div class="col-md-6 col-sm-6 col-6 col-lg-3">
                                                <a href="<?php echo $seller_url; ?>">
                                                    <div class="so-new-seller-desc">
                                                        <div class="so-seller-header">
                                                            <figure>
                                                                <i class="bi bi-circle-fill <?php echo $active_status; ?>"></i><!--This is to mark the seller as online -->
                                                                <img src="<?php echo $seller_img_url ? $seller_img_url : $profile_avatar; ?>" alt="<?php echo $seller_data->display_name; ?>">
                                                            </figure>
                                                            <div class="so-new-sellers-name">
                                                                <p class="seller-tag">Top Seller</p>
                                                                <h5 class="text-center m-0 p-2 d-flex">
                                                                    <?php echo $seller_data->display_name; ?>
                                                                    <?php if ((int) get_user_meta($seller_id, 'is_verified', true) == 1) { ?>
                                                                        <div class="profile-verify" title="verified">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                                                <path d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z" fill="#292D32" />
                                                                            </svg>
                                                                        </div>
                                                                    <?php } ?>
                                                                </h5>
                                                                <div class="d-flex seller-page-location-details seller-page-location">
                                                                    <p class="text-center d-flex">
                                                                        <span class="so-custom-icon icon-lightgray">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                                                            </svg>
                                                                        </span>
                                                                        <span>
                                                                            <?php echo $seller_location ? $seller_location : "N/A"; ?></span>
                                                                        <!-- <div class="seller-page-location">
                                                            </div> -->
                                                                    </p>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="so-seller-footer mt-4 pt-4">
                                                            <div class="seller-detailed-info mb-2">
                                                                <div class="seller-followers">
                                                                    <h6><strong>870k</strong></h6>
                                                                    <span>Followers</span>
                                                                </div>
                                                                <div class="seller-sold">
                                                                    <h6><strong>11256k</strong></h6>
                                                                    <span>Spits Sold</span>
                                                                </div>
                                                                <div class="seller-category">
                                                                    <p><span>Asian</span></p>
                                                                </div>
                                                            </div>

                                                            <div class="seller-new-rating">
                                                                <p>
                                                                    <span>
                                                                        <i class="bi bi-star-fill"></i>
                                                                        <i class="bi bi-star-fill"></i>
                                                                        <i class="bi bi-star-fill"></i>
                                                                        <i class="bi bi-star-fill"></i>
                                                                        <i class="bi bi-star-half"></i>
                                                                    </span> 4.9 Rating
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-active" role="tabpanel" aria-labelledby="pills-active-tab">..prodwsd.</div>
                                <div class="tab-pane fade" id="pills-popular" role="tabpanel" aria-labelledby="pills-popular-tab">..sdasdsa.</div>
                                <div class="tab-pane fade" id="pills-recommended" role="tabpanel" aria-labelledby="pills-recommended-tab">..recommended.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pt-4 pb-5">

                </div>
            </div>
        </section>
    </div>
<?php } else {
    echo '
    <div class="so-seller-contents">
    <section class="so-new-seller mt-3">
        <div class="container">
            <div class="row pt-4 pb-5">
        <section class="so-ms-form so-feed-new-container">
            <div class="container-fluid" id="grad1">
                <div class="row justify-content-center mt-0">
                    <div class="col-lg-12">
                        <div class=" px-0 pt-4 pb-0 mt-3 mb-3">
                            <div class="row">
                                <div class="col-md-12 mx-0">
                                    <div id="msform">
                                        <fieldset id="so-verify-failed">
                                            <div class="form-card register-step-completed so-registration-failed">
                                                <div class="row no-sellers-notice">
                                                    <div class="col-lg-12">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 48 48">
                                                            <g fill="#ef4444">
                                                                <path d="M31.424 38.177A15.93 15.93 0 0 1 24 40c-8.837 0-16-7.163-16-16S15.163 8 24 8s16 7.163 16 16c0 .167-.003.334-.008.5h2.001c.005-.166.007-.333.007-.5c0-9.941-8.059-18-18-18S6 14.059 6 24s8.059 18 18 18a17.92 17.92 0 0 0 8.379-2.065l-.954-1.758Z"></path>
                                                                <path d="M13.743 23.35c-.12.738.381 1.445 1.064 1.883c.714.457 1.732.707 2.93.53a3.794 3.794 0 0 0 2.654-1.665c.504-.764.711-1.693.48-2.382a.5.5 0 0 0-.818-.203c-1.796 1.704-3.824 2.123-5.643 1.448a.5.5 0 0 0-.667.39Zm20.076 0c.119.738-.382 1.445-1.065 1.883c-.714.457-1.731.707-2.93.53a3.794 3.794 0 0 1-2.653-1.665c-.504-.764-.712-1.693-.48-2.382a.5.5 0 0 1 .818-.203c1.796 1.704 3.824 2.123 5.642 1.448a.5.5 0 0 1 .668.39ZM40 32a4 4 0 0 1-8 0c0-3.5 4-7 4-7s4 3.5 4 7Zm-19.2 1.6c1.6-2.133 4.8-2.133 6.4 0a1 1 0 0 0 1.6-1.2c-2.4-3.2-7.2-3.2-9.6 0a1 1 0 0 0 1.6 1.2Z"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class="col-lg-12 reg-failed-title">
                                                        <i class="bi bi-x-circle"></i>
                                                        <h4>Unfortunately</h4>
                                                    </div>
                                                    <div class="col-lg-12 d-flex align-items-center">
                                                        <h5>No sellers found.</h5>
                                                    </div>
                                                    <div class="col-lg-12 go-back-reg-failed">
                                                        <a href="/spitout/home">Go back</a>
                                                    </div>
                                                </div>
                                            </div>
                                    </fieldset></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
            </div>
        </div>
    </section>
</div>
    ';
} ?>
<?php get_footer(); ?>