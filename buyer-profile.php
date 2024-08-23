<?php

/**
 * Template Name: Buyer profile page
 * @package spitout
 */
// Check if the user is not logged in
if (!is_user_logged_in()) {
    // Redirect them to the wp-admin login page
    wp_redirect(wp_login_url());
    exit;
}
get_header(); // Include header


// Get the ID of the current user
$author_id = get_queried_object_id();
$current_user_id = get_current_user_id();
$isCurrentUserAuthor = ($author_id == $current_user_id);

if ($isCurrentUserAuthor) {
    $user_id = $current_user_id;
} else {
    $user_id = $author_id;
}
$profile_avatar = wp_get_attachment_url((int) get_user_meta($user_id, 'so_profile_img', true));
$banner_avatar = wp_get_attachment_url((int) get_user_meta($user_id, 'so_banner_img', true));
$author_name = get_the_author_meta('display_name', $user_id, true);
$author_location = get_the_author_meta('so_location', $user_id, true);

// $author_bio = get_the_author_meta('so_bio', $user_id, true);
$author_bio = get_user_meta($user_id, 'so_bio', true);

$short_bio = wp_trim_words($author_bio, 20, '...'); // Shorten to 25 words
$wordCount = str_word_count(strip_tags($author_bio)); // Count words in the full biography
$showMoreLinkVisible = ($wordCount > 25); // Show the "Show more" link if more than 25 words
$followedAuthors = get_user_meta($current_user_id, 'so_followed_sellers', true);
// If the user doesn't have any followed authors yet, initialize an array
if (!$followedAuthors) {
    $followedAuthors = array();
}
// test
$totalFollowing = get_user_meta($author_id, 'so_followed_sellers', true);
// If the user doesn't have any followed authors yet, initialize an array
if (!$totalFollowing) {
    $totalFollowing = array();
}
$totalFollowers = get_user_meta($user_id, 'so_total_followers', true);
if (!$totalFollowers) {
    $totalFollowers = array();
}
// Count the number of followers
$followersCount = count($totalFollowers);
$followingCount = count($totalFollowing);


$high_quality_banner_avatar = wp_get_attachment_url((int) get_user_meta($user_id, 'so_banner_img', true));
if (empty($high_quality_banner_avatar[0])) {
    $high_quality_banner_avatar = get_stylesheet_directory_uri() . '/assets/img/cover-proifle.png';
}

$high_quality_profile_avatar = wp_get_attachment_url((int) get_user_meta($user_id, 'so_profile_img', true));
if (empty($high_quality_profile_avatar[0])) {
    $high_quality_profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
}


if (empty($profile_avatar)) {
    $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
}

if (empty($banner_avatar)) {
    $banner_avatar = get_stylesheet_directory_uri() . '/assets/img/cover-proifle.png';
}

// check if user is verified from Idenfy 
$get_verified_status = get_user_meta($author_id, 'is_verified', true);

?>


<!-- Profile section css=================================================================== -->
<section class="so-profile-new-profile so-buyer-profile-page">
    <input type="hidden" value="<?php echo $author_id; ?>" id="get_author_id">

    <?php if ((int) get_user_meta($current_user_id, 'is_verified', true) == 0 && (int) $author_id == (int) $current_user_id) { ?>
        <p class="verify-acc-text"><i class="bi bi-exclamation-circle"></i>Please Verify Your Account Below.
        </p>
    <?php } ?>

    <div class="container so-profile-new-container so-feed-new-container">
        <div class="row">
            <div class="col-md-12 buyer-profile-about">
                <?php if ($isCurrentUserAuthor) { ?>
                    <div class="so-change-cover-button">
                        <button data-toggle="modal" data-target="#editCoverImageModal">
                            <p>Change Cover</p>
                        </button>
                    </div>
                <?php } ?>
                <div class="profile-cover-image">
                    <figure>
                        <img class="so-seller-open-modal" id="feed_banner_image" src="<?php echo $banner_avatar; ?>"
                            data-high-img="<?php echo $high_quality_banner_avatar; ?>"
                            alt="<?php echo $author_name; ?> banner image" height="100%" width="100%">
                    </figure>
                </div>
                <div class="profile-main-option">
                    <div class="profile-picture-image">
                        <!-- <i class="bi bi-dot"></i> -->
                        <figure>
                            <img class="so-seller-open-modal" id="feed_profile_picture"
                                src="<?php echo $profile_avatar; ?>"
                                data-high-img="<?php echo $high_quality_profile_avatar; ?>"
                                alt="<?php echo $author_name; ?> profile picture">
                        </figure>
                    </div>
                    <?php
                    // If the user is viewing their own profile, show the "Edit profile" button
                    if ($isCurrentUserAuthor) {
                        ?>
                        <div class="so-feed-profile-image-edit" data-toggle="modal" data-target="#editFrofileImageModal">
                            <a href="#!" aria-label="Camera"><i class="bi bi-camera"></i></a>
                        </div>

                        <div class="profile-text-follow-option d-flex idenfy_verify">


                            <?php
                            if ((int) get_user_meta($author_id, 'is_verified', true) == 0) {
                                echo do_shortcode('[IDENFY]');
                            } else {
                                echo '<p class="idenfy-verified">Verified</p>';
                            }

                            ?>
                        </div>
                        <div class="profile-text-follow-option d-flex">
                            <div class="edit-profile-button">
                                <a href="<?php echo home_url('/edit-profile') ?>">
                                    <button type="button" class="btn btn-light">
                                        Edit Profile
                                    </button>
                                </a>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="profile-text-follow-option d-flex">
                            <?php
                            $msg_btn = '';
                            //info about the seller whose page the user is on
                            $seller_approved = get_user_meta((int) $author_id, 'cpmm_approved_messages', true);
                            $seller_approved = is_array($seller_approved) ? $seller_approved : [];
                            $seller_request = get_user_meta((int) $author_id, 'cpmm_message_requests', true);
                            $seller_request = is_array($seller_request) ? $seller_request : [];
                            if (in_array((int) $current_user_id, $seller_approved)) {
                                $msg_btn = '<i class="bi bi-chat-left-dots-fill search-user-action-btn" data-action="msg" data-uid="' . $author_id . '" data-page="profile"></i>';
                            } else if (in_array((int) $current_user_id, $seller_request)) {
                                $msg_btn = '<i class="bi bi-send-check-fill search-user-action-btn" data-action="requested" data-uid="' . $author_id . '" data-page="profile"></i>';
                            } else if (!in_array($current_user_id, $seller_approved) && !in_array($current_user_id, $seller_request)) {
                                $msg_btn = '<i class="bi bi-send-plus-fill search-user-action-btn" data-action="request" data-uid="' . $author_id . '" data-page="profile"></i>';
                            }
                            ?>

                            <!-- send message button -->
                            <?php echo $msg_btn; ?>

                            <div class="follow-button">
                                <?php
                                if (in_array($author_id, $followedAuthors)) {
                                    ?>
                                    <div class="so-follow-following-wrapper">
                                        <p class="so-following-seller" data-author-id="<?php echo $user_id; ?>"
                                            data-followers-count="<?php echo $followersCount; ?>"
                                            data-sender="<?php echo $current_user_id; ?>"
                                            data-receiver="<?php echo $author_id; ?>" data-notify="follow">Following</p>
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif"
                                            alt="Loading" class="so-feed-following-loader" style="display: none; width: 25px;">
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="so-follow-following-wrapper">
                                        <p class="so-follow-seller so-notify" data-author-id="<?php echo $user_id; ?>"
                                            data-followers-count="<?php echo $followersCount; ?>"
                                            data-sender="<?php echo $current_user_id; ?>"
                                            data-receiver="<?php echo $author_id; ?>" data-notify="follow">Follow</p>

                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif"
                                            alt="Loading" class="so-feed-following-loader" style="display: none; width: 25px;">
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>

                <div class=" profile-user-content-details">
                    <div class="profile-user-detail d-flex">
                        <h5>
                            <?php echo $author_name; ?>
                        </h5>
                        <?php if ((int) get_user_meta($user_id, 'is_verified', true) == 1) { ?>
                            <div class="profile-verify" title="verified">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none">
                                    <path
                                        d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                                        fill="#292D32" />
                                </svg>
                            </div>
                        <?php } ?>
                        <div class="profile-user-location d-flex">
                            <i class="bi bi-geo-alt-fill"></i>
                            <p>
                                <?php echo $author_location ? $author_location : "N/A"; ?>
                            </p>
                        </div>
                    </div>
                    <div class="so-user-follow-wrapper buyer-page-user-follow-wrapper">
                        <i class="bi bi-person-fill"></i>
                        <div class="so-user-following-wrapper" data-toggle="modal" data-target="#spitoutfollowinglist">
                            <div class="so-user-following-count">
                                <?php echo $followingCount; ?>
                            </div>
                            <div class="so-user-followingText">
                                Following</div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <div  class="so-feed-new-container m-auto buyer-profile-details so-profile-new-container container pb-5 buyer-profile-bio">
        <?php
        $user_bio = get_user_meta($user_id, 'so_bio', true);
        if (!empty($user_bio)) {
            echo '<h6 id="buyer-bio">' . esc_html($user_bio) . '</h6>';
        }
        ?>
    </div>
</section>
<!-- profile section end-========================================================= -->


<!-- The Modal-->
<!-- Modal box to update cover picture of user -->
<div class="modal fade" id="editCoverImageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change Cover Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <img src="<?php echo $banner_avatar; ?>" id="selected-cover-preview"
                    alt="<?php echo $author_name; ?> cover image" class="img-fluid">

            </div>
            <div class="modal-footer">

                <form id="soBannerImg" action="" method="post" enctype="multipart/form-data">
                    <div id="profile-cover-uploader" class="upload-new-cover-photo">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                        <!-- <h5>Upload Photo</h5> -->
                        <p>Add Photo</p>
                    </div>
                    <input type="file" name="so-cover-imgs" id="so-cover-img" aria-label="Select Image" accept="image/*"
                        style="display:none;">

                    <div id="btnBannerCrop">
                        <i class="bi bi-crop"></i>
                        <input type="button" id="btnBannerCrop" value="Crop Image" />
                    </div>
                    <div id="btnBannerRestore">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <input type="button" id="btnBannerRestore" value="Restore" />
                    </div>

                    <input class="update-image-btn" name="update-cover" type="submit" value="UPDATE">
                </form>

                <div class="m-0">
                    <canvas id="cropper_canvas_banner">
                        Your browser does not support the HTML5 canvas element.
                    </canvas>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal box to change profile picture image  -->
<div class="modal fade so-close-on-action" id="editFrofileImageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change Profile Picture</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="<?php echo $profile_avatar; ?>" id="selected-profile-image-preview"
                    alt="<?php echo $author_name; ?> profile picture" class="img-fluid">
            </div>
            <div class="modal-footer">

                <form id="soProfileImage" action="" method="post" enctype="multipart/form-data">
                    <div id="profile-picture-uploader" class="upload-new-profile-photo">
                        <i class="bi bi-cloud-arrow-up-fill"></i>
                        <h5>Upload Photo</h5>
                        <p>Add Photo</p>
                    </div>
                    <input type="file" name="so-profile-imgs" id="so-profile-img" aria-label="Select Image"
                        accept="image/*" style="display: none;">
                    <div id="btnCrop">
                        <i class="bi bi-crop"></i>
                        <input type="button" id="btnCrop" value="Crop Image" />
                    </div>
                    <div id="btnRestore">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <input type="button" id="btnRestore" value="Restore" />
                    </div>
                    <input class="update-image-btn" name="update-profile" type="submit" value="UPDATE">
                </form>

                <div class="m-0">
                    <canvas id="cropper_canvas">
                        Your browser does not support the HTML5 canvas element.
                    </canvas>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- modal for following list -->
<div class="modal fade" id="spitoutfollowinglist" tabindex="-1" role="dialog"
    aria-labelledby="spitoutfollowinglistTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content so-following-list">
            <div class="modal-header">
                <h5 class="modal-title" id="spitoutfollowinglistTitle">Following</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body so-following-list-modal-content">
                <?php
                foreach ($totalFollowing as $following_id) {
                    $get_seller_information = spitout_get_seller_information($following_id);
                    $seller_display_name = $get_seller_information['seller_display_name'];
                    $seller_final_profile_img = $get_seller_information['seller_profile_img'];
                    $seller_url = $get_seller_information['seller_url'];
                    $user = get_userdata($following_id);
                    ?>

                    <div class="so-following-list-wrapper">
                        <div>
                            <img src="<?php echo $seller_final_profile_img; ?>" alt="<?php echo $seller_display_name; ?>"
                                height="100px" width="100px">
                        </div>
                        <div class="so-following-name">
                            <a href="<?php echo $seller_url; ?>">
                                <?php echo $user->user_login; ?>
                            </a>
                            <?php echo $seller_display_name; ?>
                        </div>
                        <div>
                            <a href="<?php echo $seller_url; ?>">View Profile</a>
                        </div>
                    </div>
                    <?php
                }
                if (empty($totalFollowing)) {
                    echo '<p class="text-center">No following yet.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
echo spitout_placeholder_and_modal_div_content();
get_footer(); ?>