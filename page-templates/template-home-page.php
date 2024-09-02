<?php

/**
 * Template Name: Home Page
 * @package spitout
 */

get_header();

$TopBannerTitle = get_post_meta(get_the_ID(), 'spitout_meta_optionstop-banner-title', true);
$JoinFree = get_post_meta(get_the_ID(), 'spitout_meta_optionsjoin-free', true);
$SellFetish = get_post_meta(get_the_ID(), 'spitout_meta_optionssell-fetish', true);
$DesktopBannerImage = get_post_meta(get_the_ID(), 'spitout_meta_optionsdesktop-banner-image', true);
$IpadBannerImage = get_post_meta(get_the_ID(), 'spitout_meta_optionsipad-banner-image', true);
$MobileBannerImage = get_post_meta(get_the_ID(), 'spitout_meta_optionsmobile-banner-image', true);
$TitleJoinUsCTA = get_post_meta(get_the_ID(), 'spitout_meta_optionstitle-join-us-cta', true);
$SubTitleJoinUs = get_post_meta(get_the_ID(), 'spitout_meta_optionssub-title-join-us', true);
$JoinUsFreeLink = get_post_meta(get_the_ID(), 'spitout_meta_optionsjoin-us-free-link', true);
$TitleShop = get_post_meta(get_the_ID(), 'spitout_meta_optionstitle-shop', true);
$SubTitleShopCTA = get_post_meta(get_the_ID(), 'spitout_meta_optionssub-title-shop-cta', true);
$ShopLink = get_post_meta(get_the_ID(), 'spitout_meta_optionsshop-link', true);

?>
<!-- Banner section start=============================================================
    ==================================================================================== -->
<section class="so-banner">
    <div class="container home-container">
        <div class="row so-banner-texts">
            <div class="col-12">
                <h1 class="text-center">
                    <?php echo $TopBannerTitle; ?>
                </h1>
                <div class="so-banner-btn text-center mt-1 mb-4 justify-content-center align-items-center">
                    <a href="<?php echo $JoinFree; ?> ">
                        <h5>
                            <button class="ml-3 m-2 px-4 py-3">
                                Join Now
                                <span class="so-custom-icon">
                                    <svg id="Layer_1" enable-background="new 0 0 100 100" height="512"
                                        viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                                    </svg>
                                </span>
                            </button>
                        </h5>
                    </a>
                    <a href="<?php echo $SellFetish; ?>">
                        <h5><button class=" bg-dark px-4 py-3">Spit Now
                                <span class="so-custom-icon">
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 248.151 248.151" style="enable-background:new 0 0 248.151 248.151;"
                                        xml:space="preserve">
                                        <path
                                            d="M134.475,8.551c-6.8-11.6-14-11.2-20.8,0c-31.2,46.4-78.4,116-78.4,150.8c0,24.4,10,46.8,26,62.8s38.4,26,62.8,26
            c24.4,0,46.8-10,62.8-26s26-38.4,26-62.8C212.875,124.151,165.675,54.951,134.475,8.551z M188.075,198.951
            c-6.4,10.4-15.6,19.6-26.8,26c-5.2,2.8-11.6,1.2-14.4-4c-3.2-5.6-1.2-12,4-14.8c8-4.4,14.4-10.8,19.2-18.4
            c4.8-7.6,7.6-16.4,8-25.6c0.4-6,5.2-10.4,11.2-10c6,0.4,10.4,5.2,10,11.2C198.475,176.151,194.475,188.151,188.075,198.951z" />
                                    </svg>

                                </span>
                            </button>
                        </h5>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="container mt-5 mb-5">
            <figure class="so-banner-image">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/banner.png" alt="spit">
            </figure>
        </div> -->
    <div class="container banner-container so-banner-main-image mt-5 mb-5">
        <picture class="so-banner-image">
            <!-- Image for screen size less than 600px -->
            <source media="(max-width: 767px)" srcset="<?php echo $MobileBannerImage; ?>">
            <!-- Image for screen size less than 767px but greater than 600px -->
            <source media=" (max-width: 991px)" srcset="<?php echo $IpadBannerImage; ?>">
            <!-- Default image for larger screen sizes -->
            <img src=" <?php echo $DesktopBannerImage; ?>" alt="spit">
        </picture>
    </div>
    <div class="container home-container pt-5">
        <?php the_content(); ?>
    </div>
</section>
<!-- banner section end===================================================
    ==================================================================== -->

<!-- popular sellers section start================================================================
    =================================================================== -->
<?php
$get_popular_sellers = spitout_get_popular_sellers();
if (!empty($get_popular_sellers)) {

    ?>
    <section class="so-popular-seller">
        <div class="home-container container ">
            <h3>Popular Sellers</h3>
            <div class="row mt-5">
                <?php
                foreach ($get_popular_sellers as $key => $get_popular_seller) {

                    $get_seller_id = $key;
                    $get_author_url = get_author_posts_url($get_seller_id);
                    $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
                    $seller_img = get_user_meta($get_seller_id, "so_profile_img", true);
                    $seller_url = get_author_posts_url($get_seller_id);

                    if ($seller_img) {
                        $seller_img_src = wp_get_attachment_image_src($seller_img, 'medium');
                        $seller_img_url = $seller_img_src[0];
                    } else {
                        $seller_img_url = false;
                    }

                    $seller_final_profile_img = $seller_img_url ? $seller_img_url : $profile_avatar;
                    $seller_data = get_userdata($get_seller_id);
                    $seller_display_name = $seller_data->display_name;
                    $user_meta = get_userdata($get_seller_id);
                    $user_roles = $user_meta->roles;
                    $seller_active_status = get_user_meta($get_seller_id, 'user_status', true);
                    $seller_inactive_status_time = get_user_meta($get_seller_id, 'user_logged_out_time', true);

                    $provided_datetime = new DateTime($seller_inactive_status_time);
                    $current_datetime = new DateTime();

                    // Calculate the time difference
                    /*  $time_difference = $current_datetime->diff($provided_datetime);
                    $office_time = 'Active' . $time_difference->i . 'ago'; */
                    // Active 5 min ago
                    // Calculate the time difference
                    $time_difference = $current_datetime->diff($provided_datetime);

                    // Format the time difference for display
                    if ($time_difference->days > 0) {
                        $online_status = $time_difference->days . " days ago";
                    } elseif ($time_difference->h > 0) {
                        $online_status = $time_difference->h . " hours ago";
                    } elseif ($time_difference->i > 0) {
                        $online_status = $time_difference->i . " minutes ago";
                    } else {
                        $online_status = "Just now";
                    }

                    if ($seller_active_status == 'logged_in') {
                        $active_status = '<p class="text-center text-bold online">Active now</p>';
                    } else {

                        $active_status = '<p class="text-center">Active ' . $online_status . '</p>';
                    }

                    if (in_array('seller', $user_roles, true)) {

                        echo ' <div class="col-lg-2 col-md-4 col-6">
                    <a href="' . $get_author_url . '">
                        <div class="so-popular-sellers-desc">
                            <div class="so-popular-sellers-image">
                                <figure>
                                    <img src="' . $seller_final_profile_img . '" alt="' . $seller_display_name . '">
                                </figure>
                            </div>
                            <div class="so-popular-sellers-name">
                                <h5 class="text-center mb-0">' . $seller_display_name . '</h5>
                               ' . $active_status . '
                            </div>
                        </div>
                    </a>
                </div>';
                    }
                }
}
?>

        </div>
    </div>
</section>

<!-- browse category -->
<section class="so-browse-category">
    <div class="home-container container">
        <div class="row">
            <div class="col-md-12">
                <h3>Browse Category</h3>
                <div class="so-browse-categories mt-4">
                    <ul class="so-browse-categories-lists">
                        <?php
                        $get_spit_category = get_posts(
                            array(
                                'numberposts' => -1,
                                // -1 returns all posts
                                'post_type' => 'spit-category',
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'post_status' => 'publish'
                            )
                        );

                        foreach ($get_spit_category as $key => $get_spit_cat) {
                            # code...
                            echo '  <li>
                            <a href="' . get_permalink($get_spit_cat->ID) . '">
                                <h5>' . $get_spit_cat->post_title . '</h5>
                            </a>
                        </li>';
                        }
                        ?>

                        <li id="browse-all-categories-btn">
                            <button>
                                <a href="<?php echo home_url('/categories') ?>" class="so-browse-cat-browse-all d-flex">
                                    <h5>Browse All</h5>

                                    <span class="so-custom-icon">
                                        <svg id="Layer_1" enable-background="new 0 0 100 100" height="512"
                                            viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                                        </svg>
                                    </span>
                                </a>
                        </li>
                        </button>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- How it works=========================================== -->
<section class="so-how-it-works so-mobile-hide">
    <div class="container home-container">
        <h3>How it Works</h3>
        <div class="row pt-4 so-hiw-row">
            <div class="col-lg-4 col-12">
                <div class="so-hiw-cards">
                    <div class="so-hiw-first-card">
                        <div class="so-hiw-card-age-limit">
                            <h5>+21</h5>
                        </div>
                        <h4 class="text-center">Register and verify your ID</h4>
                        <h6 class="text-center p-4">You can create a free account follow your favorites influencers,
                            artists and content
                            creators
                        </h6>
                        <figure class="text-center sellers-image pt-4">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/icons/face-id.svg" alt="spit"
                                class="m-auto" height="100px" width="100px">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="so-hiw-cards">
                    <div class="so-hiw-second-card">
                        <h4 class="text-center">Find a seller you like</h4>
                        <h6 class="text-center p-4">You can create a free account follow your favorites influencers,
                            artists and content
                            creators
                        </h6>
                        <figure class="text-center sellers-image pt-4">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/hiw-card-img.png"
                                alt="spit">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="so-hiw-cards">
                    <div class="so-hiw-third-card">
                        <h4 class="text-center">Delivery</h4>
                        <h6 class="text-center p-4">You can create a free account follow your favorites influencers,
                            artists and content
                            creators
                        </h6>
                        <figure class="text-center pt-4">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/bottle.png" alt="spit"
                                class="m-auto" width="110px" height="190px">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- New seller====================================================================== -->
<section class="so-new-seller">
    <div class="container home-container">
        <h3>New Seller</h3>
        <div class="row pt-4 pb-5">
            <?php
            $get_new_sellers = spitout_get_newest_users(6);

            foreach ($get_new_sellers as $key => $get_new_seller) {
                $get_new_seller_id = $key;

                $get_author_url = get_author_posts_url($get_new_seller_id);
                $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
                $seller_img = get_user_meta($get_new_seller_id, "so_profile_img", true);
                $seller_url = get_author_posts_url($get_new_seller_id);

                if ($seller_img) {
                    $seller_img_src = wp_get_attachment_image_src($seller_img, 'medium');
                    $seller_img_url = $seller_img_src[0];
                } else {
                    $seller_img_url = false;
                }

                $seller_final_profile_img = $seller_img_url ? $seller_img_url : $profile_avatar;
                $seller_data = get_userdata($get_new_seller_id);
                $seller_location = get_user_meta($get_new_seller_id, "so_location", true);
                $seller_final_location = $seller_location ? $seller_location : 'N/A';
                $seller_display_name = $seller_data->display_name;

                # code...
                $user_meta = get_userdata($get_new_seller_id);
                $user_roles = $user_meta->roles;
                if (in_array('seller', $user_roles, true)) {
                    echo ' <div class="col-md-4 col-sm-4 col-6  col-lg-2">
                <a href="' . $seller_url . '">
                    <div class="so-new-seller-desc">
                        <figure>
                            <img src="' . $seller_final_profile_img . '" alt="seller">
                        </figure>
                        <div class=" so-new-sellers-name">
                            <h5 class="text-center m-0 p-2">' . $seller_display_name . '</h5>
                            <p class="text-center"><span class="so-custom-icon inverted">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                    </svg>
                                </span>' . $seller_final_location . '</p>
                        </div>
                    </div>
                </a>
            </div>';
                }
            }

            ?>
        </div>

        <div class="row pt-3 pb-5">
            <div class="col-md-12 col-lg-6">
                <div class="card so-join-card1">
                    <div class="card-body">
                        <h2 class="card-title text-light">Be Yourself And <br> Join Us Today</h2>
                        <h6 class="card-text text-light pb-3">SplitOut is the safest, easiest and most secure <br>
                            website for verified user, buy and sell custom fetish.
                        </h6>
                        <a href="<?php echo $JoinUsFreeLink; ?>">
                            <button class="d-flex">
                                <h5>Join Now</h5>
                                <span class="so-custom-icon inverted">
                                    <svg id="Layer_1" enable-background="new 0 0 100 100" height="512"
                                        viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                                    </svg>
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="card so-join-card2">
                    <div class="card-body">
                        <h2 class="card-title text-light">Be Yourself And <br> Join Us Today</h2>
                        <h6 class="card-text text-light pb-3">SplitOut is the safest, easiest and most secure <br>
                            website
                            for
                            verified
                            user,
                            buy and sell
                            custom fetish.
                        </h6>
                        <a href="<?php echo $ShopLink; ?>">
                            <button class="d-flex">
                                <h5 class="home-shop-link">Shop</h5><span class="so-custom-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19.9597 8.95844C19.2897 8.21844 18.2797 7.78844 16.8797 7.63844V6.87844C16.8797 5.50844 16.2997 4.18844 15.2797 3.26844C14.2497 2.32844 12.9097 1.88844 11.5197 2.01844C9.12975 2.24844 7.11975 4.55844 7.11975 7.05844V7.63844C5.71975 7.78844 4.70975 8.21844 4.03975 8.95844C3.06975 10.0384 3.09975 11.4784 3.20975 12.4784L3.90975 18.0484C4.11975 19.9984 4.90975 21.9984 9.20975 21.9984H14.7897C19.0897 21.9984 19.8797 19.9984 20.0897 18.0584L20.7897 12.4684C20.8997 11.4784 20.9197 10.0384 19.9597 8.95844ZM11.6597 3.40844C12.6597 3.31844 13.6097 3.62844 14.3497 4.29844C15.0797 4.95844 15.4897 5.89844 15.4897 6.87844V7.57844H8.50975V7.05844C8.50975 5.27844 9.97975 3.56844 11.6597 3.40844ZM8.41975 13.1484H8.40975C7.85975 13.1484 7.40975 12.6984 7.40975 12.1484C7.40975 11.5984 7.85975 11.1484 8.40975 11.1484C8.96975 11.1484 9.41975 11.5984 9.41975 12.1484C9.41975 12.6984 8.96975 13.1484 8.41975 13.1484ZM15.4197 13.1484H15.4097C14.8597 13.1484 14.4097 12.6984 14.4097 12.1484C14.4097 11.5984 14.8597 11.1484 15.4097 11.1484C15.9697 11.1484 16.4197 11.5984 16.4197 12.1484C16.4197 12.6984 15.9697 13.1484 15.4197 13.1484Z"
                                            fill="#292D32" />
                                    </svg>
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5 so-joinus-email-section">
            <div class="col-12">
                <div class="so-subscribe">
                    <h2 class="text-center">Subscribe to our weekly <br> digest of new names.</h2>
                    <div class="so-subscribe-email">
                        <form action="#" class="text-center pt-3">
                            <input type="email" id="email" name="email" placeholder="Email" aria-label="Email"><span
                                class="so-custom-icon">
                                <svg width="512" height="512" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 3.5H7C4 3.5 2 5 2 8.5V15.5C2 19 4 20.5 7 20.5H17C20 20.5 22 19 22 15.5V8.5C22 5 20 3.5 17 3.5ZM17.47 9.59L14.34 12.09C13.68 12.62 12.84 12.88 12 12.88C11.16 12.88 10.31 12.62 9.66 12.09L6.53 9.59C6.21 9.33 6.16 8.85 6.41 8.53C6.67 8.21 7.14 8.15 7.46 8.41L10.59 10.91C11.35 11.52 12.64 11.52 13.4 10.91L16.53 8.41C16.85 8.15 17.33 8.2 17.58 8.53C17.84 8.85 17.79 9.33 17.47 9.59Z"
                                        fill="#292D32" />
                                </svg>
                                </svg>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<?php get_footer();