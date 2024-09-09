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
if ($all_sellers) { ?>
    <div class="container sellers-page-heading mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="seller-search-header">
                    <div class="seller-header-top">
                        <h4>Spitout <span>Sellers</span></h4>
                        <div class="custom-pill-box pink-pill-box"><span><?php echo esc_html($seller_cat_count) ?> Sellers</span></div>
                    </div>
                    <div class="seller-search-form">
                        <form>
                            <input type="input" name="seller_search" value="" placeholder="Search Seller" id="seller_search">
                            <i class="bi bi-search"></i>
                        </form>
                        <div class="seller-search-categories">
                            <div class="seller-cat-wrapper">
                                <?php // Check if there are any posts
                                if ($seller_type_query->have_posts()) :
                                    while ($seller_type_query->have_posts()) :
                                        $seller_type_query->the_post();
                                        $post_id = get_the_ID();
                                        $title = get_the_title();
                                        $featured_image_id = get_post_meta($post_id, '_thumbnail_id', true);
                                        // $featured_image_url = resize_and_compress_image($featured_image_id, 150, 150, 70);

                                        $featured_image_url = wp_get_attachment_image_src($featured_image_id,'thumbnail');
                                        if (!$featured_image_url) {
                                            $featured_image_url = get_template_directory_uri() . '/assets/img/user.png';
                                        }else{
                                            $featured_image_url = $featured_image_url[0];
                                        } ?>
                                        <div class="seller-cat-checkbox">
                                            <input type="checkbox" name="category-name[]" value="true" class="category-class" data-id="<?php echo $post_id; ?>">
                                            <figure>
                                                <img src="<?php echo esc_url($featured_image_url); ?>" alt="seller-image">
                                            </figure>
                                            <h5 class="text-center"><?php echo esc_html($title); ?></h5>
                                        </div>
                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                    echo '<p>No posts found.</p>';
                                endif; ?>
                            </div>
                            <figure class="seller-more">
                                <i class="bi bi-three-dots"></i>
                                <h5 class="text-center">More</h5>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- seller page body========================================= -->
    <div class="so-seller-contents">
        <section class="so-new-seller seller-tab-section mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="seller-tab-col">
                            <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-all-tab" data-toggle="pill" data-target="#pills-all" type="button" role="tab" aria-controls="pills-all" aria-selected="true" data-button-id="pills-all-tab" data-tab="all">All</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-active-tab" data-toggle="pill" data-target="#pills-active" type="button" role="tab" aria-controls="pills-active" aria-selected="false" data-button-id="pills-active-tab" data-tab="active">Active</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-popular-tab" data-toggle="pill" data-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular" aria-selected="false" data-button-id="pills-popular-tab" data-tab="popular">Popular</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-new-sellers-tab" data-toggle="pill" data-target="#pills-new-sellers" type="button" role="tab" aria-controls="pills-new-sellers" aria-selected="false" data-button-id="pills-new-sellers-tab" data-tab="new-sellers">New Sellers</button>
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
                                <a href="#" id="seller-filter-dropdown">
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
                            <div class="filter-pannel">
                                <form class="seller-filter-dropdown-form filter" action="" method="POST">
                                    <i class="bi bi-caret-up-fill"></i>
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
                                                echo '<option value="' . $location . '">' . $location . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class=" seller-filter-dropdowns-lists seller-dropdown-age">
                                        <label for="age-start">Age</label> <br>
                                        <select id="age-start" name="age-start">
                                            <option value="" selected> -- </option>
                                            <?php
                                            if (!empty($all_sellers)) {
                                                $ages = array();
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
                                                    echo '<option value="' . $age . '">' . $age . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <label for="age-end"></label>
                                        <select id="age-end" name="age-end">
                                            <option value="" selected> -- </option>
                                            <?php
                                            if (!empty($all_sellers)) {
                                                for ($age = $largest_age; $age >= $smallest_age; $age--) {
                                                    echo '<option value="' . $age . '">' . $age . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="slider-range-age" class="sellers-filter-slider" data-min-age="<?php echo $smallest_age; ?>" data-max-age="<?php echo $largest_age; ?>"></div>
                                    <div class="seller-filter-dropdowns-lists seller-dropdown-price">
                                        <label for="price-start">Price Saliva</label> <br>
                                        <input type="number" min="0" id="price-start" name="price-start" />
                                        <label for="price-end"></label>
                                        <input type="number" min="0" id="price-end" name="price-end"/>
                                    </div>
                                    <div id="slider-range" class="sellers-filter-slider"></div>
                                    <div class="filter-dropdown-delete-icon">
                                        <a href="#" class="reset-button" id="reset-button"> <i class="bi bi-trash-fill"></i> </a>
                                        <a href="#" class="apply-button" id="apply-button"> Apply Filter </a>
                                    </div>
                                </form>
                            </div>
                            <div class="new-seller-tab-content">
                                <div class="tab-content" id="seller-pills-tabContent">
                                </div>
                                <div id="new-seller-tab-loader" class="so-feed-options-loader-wrapper">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/spit-loader.gif'); ?>" alt="Loading" class="so-feed-options-loader" style="width: 25px;">
                                </div>
                            </div>
                        </div>
                    </div>
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