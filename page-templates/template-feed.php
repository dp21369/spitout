<?php

/**
 * Template Name: Feed Page
 * @package spitout
 */

// Check if the user is not logged in
if (!is_user_logged_in()) {
    // Redirect them to the wp-admin login page
    wp_redirect(wp_login_url());
    exit;
}

$current_user_id = get_current_user_id();

get_header();
?>

<!--  feed section======================================================= -->
<section class="so-news-feed">
    <input type="hidden" value="following" id="feed_type">
    <div class="container so-feed-new-container">
        <?php
        $followed_sellers = get_user_meta($current_user_id, 'so_followed_sellers', true);
        // Get all users with the role 'seller'
        $seller_users = get_users(
            array(
                'role' => 'seller',
                'fields' => 'ids', // Only retrieve user IDs
            )
        );

        // Check if any seller users were found
        if (empty ($seller_users)) {
            // Display a notice if no seller users were found
            so_error_template_display('No sellers found.');
        }

        if (!empty ($followed_sellers)) {
            $followed_sellers = spitoutFilterExistingUsers($followed_sellers);
        } else {
            $followed_sellers = array();
        }


        if (!empty ($followed_sellers)) {
            ?>
            <ul class="nav nav-pills mb-2 so-feed-postOptions" id="pills-tab" role="tablist">

                <li class="nav-item feed-following">
                    <a class="nav-link" id="pills-nf-following-tab" data-toggle="pill" href="#pills-nf-following" role="tab"
                        aria-controls="pills-nf-following" aria-selected="false">
                        <h6 class='font-weight-bold'>Following</h6>
                    </a>
                </li>

                <li class="nav-item feed-forYou">
                    <a class="nav-link active" id="pills-nf-for-you-tab" data-toggle="pill" href="#pills-nf-for-you"
                        role="tab" aria-controls="pills-nf-for-you" aria-selected="true">
                        <h6 class='font-weight-bold'>View All</h6>
                    </a>
                </li>
            </ul>

            <?php
        }
        ?>

        <div class="tab-content so-feed-display" id="pills-tabContent">
            <div class="tab-pane fade" id="pills-nf-following" role="tabpanel" aria-labelledby="pills-nf-following-tab">
                <div class="spitout-feed-new-container-shortcode-wrapper spitout-following-feed">
                    <div class="initital_response_following">
                        <?php spitout_seller_feed('following', $current_user_id); ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show active" id="pills-nf-for-you" role="tabpanel"
                aria-labelledby="pills-nf-for-you-tab">
                <div class="spitout-feed-new-container-shortcode-wrapper spitout-for-you-feed">
                    <div class="initital_response_for_you">
                        <?php spitout_seller_feed('for_you', $current_user_id); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- end of the newsfeed section==================================================================
    ========================================================================================= -->
<?php
echo spitout_placeholder_and_modal_div_content();
get_footer(); ?>