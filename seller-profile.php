<?php
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

$isCurrentUserSeller = in_array('seller', (array) wp_get_current_user()->roles);

$attachment_id = (int) get_user_meta($user_id, 'so_profile_img', true);
$attachment_array = wp_get_attachment_image_src($attachment_id, 'medium'); // if not available than retrieves the original image
if ($attachment_array) {
  $profile_avatar = $attachment_array[0]; // URL of the thumbnail image 
}

/* if the author avatar is empty it assign a placeholder image */
if (empty($profile_avatar)) {
  $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
}

$attachment_id = (int) get_user_meta($user_id, 'so_banner_img', true);
$attachment_array = wp_get_attachment_image_src($attachment_id, 'medium'); // if not available than retrieves the original image
if ($attachment_array) {
  $banner_avatar = $attachment_array[0]; // URL of the thumbnail image 
}

/* if the author avatar is empty it assign a placeholder image */
if (empty($banner_avatar)) {
  $banner_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
}

$high_quality_profile_avatar = wp_get_attachment_url((int) get_user_meta($user_id, 'so_profile_img', true));
if (empty($high_quality_profile_avatar[0])) {
  $high_quality_profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
}


$high_quality_banner_avatar = wp_get_attachment_url((int) get_user_meta($user_id, 'so_banner_img', true));
if (empty($high_quality_banner_avatar[0])) {
  $high_quality_banner_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
}


$author_name = get_the_author_meta('display_name', $user_id, true);
$author_location = get_the_author_meta('so_location', $user_id, true);

$author_bio = get_the_author_meta('so_bio', $user_id, true); // Show the "Show more" link if more than 25 words
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
$totalFollowing = spitoutFilterExistingUsers($totalFollowing);

$totalFollowers = get_user_meta($user_id, 'so_total_followers', true);
if (!$totalFollowers) {
  $totalFollowers = array();
}

$totalFollowers = spitoutFilterExistingUsers($totalFollowers);

// Count the number of followers
$followersCount = count($totalFollowers);
$followingCount = count($totalFollowing);

if (empty($banner_avatar)) {
  $banner_avatar = get_stylesheet_directory_uri() . '/assets/img/cover-proifle.png';
}

$is_verified = (int) get_user_meta($current_user_id, 'is_verified', true) == 0 ? false: true;
?>


<!-- Profile section css=================================================================== -->
<section class="so-profile-new-profile">
  <input type="hidden" value="<?php echo $author_id; ?>" id="get_author_id">
  <?php if (!$is_verified && (int) $author_id == (int) $current_user_id) { ?>
    <p class="verify-acc-text"><i class="bi bi-exclamation-circle"></i>Please Verify Your Account Below.</p>
  <?php } ?>
  <div class="container so-profile-new-container so-feed-new-container">
    <div class="row">
      <div class="col-md-12">
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
              data-high-img="<?php echo $high_quality_banner_avatar; ?>" alt="<?php echo $author_name; ?> banner image"
              height="100%" width="100%">
          </figure>
        </div>
        <div class="profile-main-option">
          <div class="profile-picture-image">
            <figure>
              <img class="so-seller-open-modal" id="feed_profile_picture" src="<?php echo $profile_avatar; ?>"
                data-high-img="<?php echo $high_quality_profile_avatar; ?>"
                alt="<?php echo $author_name; ?> profile picture">
            </figure>
          </div>
          <?php
          // If the user is viewing their own profile, show the "Edit profile" button
          if ($isCurrentUserAuthor) {
            ?>
            <div class="so-feed-profile-image-edit" data-toggle="modal" data-target="#editFrofileImageModal">
              <a href="#"><i class="bi bi-camera"></i></a>
            </div>
            <div class="profile-text-follow-option d-flex">
              <div class="edit-profile-button">
                <?php
                if (!$is_verified) {
                  echo do_shortcode('[IDENFY]');
                } else {
                  echo '<p class="idenfy-verified">Verified</p>';
                }

                ?>
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
                $msg_btn = '<i class="bi bi-chat-left-dots-fill search-user-action-btn" data-action="msg" data-curr-uid="' . $current_user_id . '" data-uid="' . $author_id . '" data-page="profile"></i>';
              } else if (in_array((int) $current_user_id, $seller_request)) {
                $msg_btn = '<i class="bi bi-send-check-fill search-user-action-btn" data-action="requested" data-curr-uid="' . $current_user_id . '" data-uid="' . $author_id . '" data-page="profile"></i>';
              } else if (!in_array($current_user_id, $seller_approved) && !in_array($current_user_id, $seller_request)) {
                $msg_btn = '<i class="bi bi-send-plus-fill search-user-action-btn" data-action="request" data-curr-uid="' . $current_user_id . '" data-uid="' . $author_id . '" data-page="profile"></i>';
              }
              ?>
              <div id="req-sent-notice">Pending Request.</div>
              <input type="hidden" class="spitout-site-url" value="<?php echo home_url(); ?>" />
              <!-- send message button -->
              <?php echo $msg_btn; ?>

              <div class="follow-button">
                <?php
                if (in_array($author_id, $followedAuthors)) {
                  ?>
                  <div class="so-follow-following-wrapper">
                    <p class="so-following-seller" data-author-id="<?php echo $user_id; ?>"
                      data-followers-count="<?php echo $followersCount; ?>" data-sender="<?php echo $current_user_id; ?>"
                      data-receiver="<?php echo $author_id; ?>" data-notify="follow">Following</p>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/loader.gif" alt="Loading"
                      class="so-feed-following-loader" style="display: none; width: 25px;">
                  </div>
                  <?php
                } else {
                  ?>
                  <div class="so-follow-following-wrapper">
                    <p class="so-follow-seller so-notify" data-author-id="<?php echo $user_id; ?>"
                      data-followers-count="<?php echo $followersCount; ?>" data-sender="<?php echo $current_user_id; ?>"
                      data-receiver="<?php echo $author_id; ?>" data-notify="follow">Follow</p>

                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/loader.gif" alt="Loading"
                      class="so-feed-following-loader" style="display: none; width: 25px;">
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
            <?php if ($is_verified) { ?>
              <div class="profile-verify" title="verified">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
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

          <?php
          if (!empty($author_bio)) {
            // Display the author's biography if it's not empty
            ?>
            <div class="profile-user-bio">
              <h6 id="full-bio" class="pb-2">
                <?php echo $author_bio; ?>
              </h6>
            </div>
            <?php
          }
          ?>
          <div class="so-user-follow-wrapper">

            <div class="so-user-followers-wrapper" data-toggle="modal" data-target="#spitoutfollowerslist">
              <div class="so-user-followers-count">
                <?php echo $followersCount; ?>
              </div>
              <div class="so-user-followersText">
                Followers</div>
            </div>

            <div class="so-user-following-wrapper" data-toggle="modal" data-target="#spitoutfollowinglist">
              <div class="so-user-following-count">
                <?php echo $followingCount; ?>
              </div>
              <div class="so-user-followingText">
                Following</div>
            </div>

          </div>
          <?php

          $product_args = array(
            'status' => 'publish',
            'author' => $author_id,
          );

          $published_products = wc_get_products(
            array(
              'status' => 'publish',
              'limit' => -1,
              'author' => $author_id
            )
          );


          if ($isCurrentUserAuthor) {

            foreach ($published_products as $product) {
              $selectedIcon = get_post_meta($product->get_id(), '_product_spitout_icon', true);
              if ($selectedIcon) {
                $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/' . $selectedIcon . '.png';
              } else {
                $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';
              }
              ?>

              <div class="profile-user-product-details">
                <div class="profile-user-price-details">
                  <div class="profile-user-saliva-type">
                    <i class="bi bi-droplet-fill"></i>
                    <h5>
                      <?php echo $product->get_name(); ?>
                    </h5>
                  </div>
                  <div class="profile-user-saliva-price">
                    <label for="saliva1">
                      <h5>
                        <?php
                        echo static_currency_generator_for_products((int) $product->get_id(), true);
                        ?>
                      </h5>
                    </label>
                  </div>
                </div>


              </div>
            <?php }
            if (!empty($published_products)) {
              ?>
              <a href="<?php echo home_url('/my-spitout') ?>">
                <div class="profile-saliva-order-button">
                  <button class="d-flex so-manage-products">
                    <h5>Manage Products</h5>
                  </button>
                </div>
              </a>

              <?php
            }
          } else {

            $process_action = home_url('/process-seller-order/')

              ?>
            <form action="<?php echo esc_url($process_action);
            ?>" method="post">
              <?php
              foreach ($published_products as $product) {
                $selectedIcon = get_post_meta($product->get_id(), '_product_spitout_icon', true);
                if ($selectedIcon) {
                  $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/' . $selectedIcon . '.png';
                } else {
                  $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';
                }
                ?>
                <div class="profile-user-price-details">
                  <div class="profile-user-saliva-type">
                    <i class="bi bi-droplet-fill"></i>
                    <h5>
                      <?php echo $product->get_name(); ?>
                    </h5>
                  </div>
                  <div class="profile-user-saliva-price">
                    <label>
                      <h5>
                        <?php
                        echo static_currency_generator_for_products((int) $product->get_id(), true);
                        ?>
                      </h5>
                    </label><br>
                    <?php if (!$isCurrentUserSeller) { ?>
                      <input type="checkbox" id="selected_product" name="selected_products[]"
                        value="<?php echo esc_attr($product->get_id()) ?>" aria-label="Select">
                    <?php } ?>
                  </div>
                </div>
                <?php
              }
              if (!empty($published_products)) {

                ?>
                <?php
                $get_current_seller_info = spitout_get_seller_information(get_current_user_id());
                $get_seller_url = $get_current_seller_info['seller_url'];
				if (!in_array('administrator', (array) wp_get_current_user()->roles)) {
                ?>
                <div class="profile-saliva-order-button">
                <button class="d-flex so-order-products" id="spitout_order" type="submit" name="proceed_to_checkout"
                      title="<?php echo $isCurrentUserSeller ? 'Sellers can not buy products from another seller' : 'Order'; ?>">
                      <h5>Order</h5>
                      <i class="bi bi-arrow-right-circle-fill"></i>
                    </button>
                </div>
              </form>
            <?php }
			  }
          } ?>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
<!-- profile section end-========================================================= -->

<!-- News feed section======================================================= -->
<ul class="nav nav-pills author-feed-page-post" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pills-post-tab" data-toggle="pill" data-target="#pills-post" type="button"
      role="tab" aria-controls="pills-post-tab" aria-selected="true">
      <p>Posts</p>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-media-tab" data-toggle="pill" data-target="#pills-media" type="button" role="tab"
      aria-controls="pills-media" aria-selected="false">
      <p>Media</p>
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="pills-review-tab" data-toggle="pill" data-target="#pills-review" type="button"
      role="tab" aria-controls="pills-review" aria-selected="false">
      <p>Reviews</p>
    </button>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <!-- Post feed start here -->
  <div class="tab-pane fade show active" id="pills-post" role="tabpanel" aria-labelledby="pills-post-tab">
    <section class="so-news-feed">
      <div class="container so-feed-new-container">

        <!-- <div id="spitoutmodalboxDisplay"></div>
        <div id="spitoutdeletemodalboxdisplay"></div>
        <div id="spitouteditmodalboxdisplay"></div>
        <div id="spitoutlikesmodalboxdisplay"></div> -->
        <?php
        if (is_author()) {
          $author_id = get_queried_object_id();
        }

        $current_user_id = get_current_user_id();

        if ($isCurrentUserAuthor) {
          echo do_shortcode('[create_post_form]');
        }
        echo '<div class="spitout-feed-post-message"></div>';

        echo '<div class="spitout-feed-new-container-shortcode-wrapper  so-feed-display">';
        echo '<div class="initital_response">';
        spitout_seller_feed('author', $author_id);
        echo '</div></div>';
        ?>
      </div>
    </section>
  </div>
  <!-- Post feed ends here -->

  <!-- Media feed start here -->
  <div class="tab-pane fade" id="pills-media" role="tabpanel" aria-labelledby="pills-media-tab">
    <section>
      <div class="container so-author-page-review so-feed-new-container">

        <div id="sellerProfileMediaTab">
          <?php
          $args = array(
            'post_type' => 'spit-feed',
            'post_status' => array('publish', 'draft'),
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1
          );

          // Get the current author's ID if available
          if (is_author()) {
            $author_id = get_queried_object_id();
            $args['author'] = $author_id;
          }

          $posts_query = new WP_Query($args);
          $hasMedia = false;
          if ($posts_query->have_posts()) {
            while ($posts_query->have_posts()) {
              global $post;
              $posts_query->the_post();
              $post_id = $post->ID;
              $i = 0;
              $image_count = 0;
              while ($post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img' . $i, true)) {
                $hasMedia = true;
                $image_count++;
                $i++;
              }

              if ($image_count == 1) {
                // Display single image
                $post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img0', true);
                $post_attachment = wp_get_attachment_url((int) $post_attachment_id);
                $file_info = pathinfo($post_attachment);

                if (isset($file_info['extension'])) {
                  $file_extension = strtolower($file_info['extension']);
                }

                if (!empty($post_attachment)) {
                  if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    ?>
                    <div class="so-feed-uploaded-img">
                      <img src="<?php echo $post_attachment; ?>" class="img-fluid" alt="Image or Video"
                        data-post-id="<?php echo $post_id; ?>" data-user-id="<?php echo $current_user_id; ?>">
                    </div>
                    <?php
                  } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) {
                    ?>
                    <div class="card-body so-feed-uploaded-img">
                      <video preload="metadata" class="plyr img-fluid" width="320" height="240" controls>
                        <source src="<?php echo $post_attachment; ?>" type="video/mp4">
                      </video>
                    </div>
                    <?php
                  }
                }
              } else if (!empty($image_count)) {
                // Display slider
                ?>
                  <div class="card-body so-feed-uploaded-img">
                    <div id="carouselIndicators<?php echo $post_id; ?>"
                      class="carousel slide so-feed-carousel feed-carousel-card" data-ride="carousel" data-interval="1000000">
                      <ol class="carousel-indicators">
                      <?php for ($j = 0; $j < $image_count; $j++) { ?>
                          <li data-target="#carouselIndicators<?php echo $post_id; ?>" data-slide-to="<?php echo $j; ?>" <?php if ($j == 0) {
                                  echo ' class="active"';
                                } ?>></li>
                      <?php } ?>
                      </ol>
                      <div class="carousel-inner">
                        <?php
                        $i = 0;
                        while ($post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img' . $i, true)) {
                          $post_attachment = wp_get_attachment_url((int) $post_attachment_id);
                          $file_info = pathinfo($post_attachment);

                          if (isset($file_info['extension'])) {
                            $file_extension = strtolower($file_info['extension']);
                          }

                          if (!empty($post_attachment)) {
                            ?>
                            <div class="carousel-item<?php if ($i == 0) {
                              echo ' active';
                            } ?>">
                            <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                                <img src="<?php echo $post_attachment; ?>" alt="Profile Image">
                            <?php } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) { ?>
                                <video preload="metadata" class="plyr" width="320" height="240" controls>
                                  <source src="<?php echo $post_attachment; ?>" type="video/mp4">
                                </video>
                            <?php } ?>
                            </div>
                          <?php
                          }
                          $i++;
                        }
                        ?>
                      </div>
                      <a class="carousel-control-prev" href="#carouselIndicators<?php echo $post_id; ?>" role="button"
                        data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#carouselIndicators<?php echo $post_id; ?>" role="button"
                        data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    </div>
                  </div>
                <?php
              }
            }
          }

          if (!$hasMedia) {
            ?>
            <style>
              .card-body.so-feed-uploaded-img {
                display: none;
              }
            </style>
            <?php
            so_error_template_display('No media yet.');
          }
          ?>
        </div>
      </div>
    </section>
  </div>
  <!-- Media feed ends here -->

  <!-- Review section-->
  <div class="tab-pane fade" id="pills-review" role="tabpanel" aria-labelledby="pills-review-tab">
    <section>
      <div class="container so-author-page-review so-feed-new-container">
        <div class="profile-user-product-details">

          <div class="so-rating-detail-wrapper">
            <?php
            // Flag to track if any reviews are found
            $reviewsFound = false;
            ?>
            <!-- Display product reviews -->
            <div class="profile-user-reviews">
              <?php
              $args_reviews = array(
                'comment_approved' => 1,
                'post_id' => $author_id,
                'comment_type' => 'so_seller_review',
                'number' => 2, // Initial number of reviews to display
              );

              $reviews = get_comments($args_reviews);

              if ($reviews):
                $reviewsFound = true; // Set flag to true if reviews are found
                foreach ($reviews as $review):
                  $rating = get_comment_meta($review->comment_ID, 'rating', true);
                  $comment = get_comment($review->comment_ID);
                  $comment_author_id = $comment->user_id;
                  $reviewer_profile_avatar = wp_get_attachment_url((int) get_user_meta($comment_author_id, 'so_profile_img', true));
                  if (empty($reviewer_profile_avatar)) {
                    $reviewer_profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
                  }

                  // Retrieve user data
                  $user_info = get_userdata($comment_author_id);

                  // Get the full name from the user data
                  $full_name = get_the_author_meta('display_name', $comment_author_id, true);
                  $display_name = $full_name;

                  // Check if the full name is available and format it as "J D"
                  if (!empty($full_name)) {
                    $name_parts = explode(' ', $full_name);
                    if (count($name_parts) > 1) {
                      // Check if the first and second parts of the name exist before accessing them
                      $first_initial = isset($name_parts[0][0]) ? ucfirst($name_parts[0][0]) : '';
                      $second_initial = isset($name_parts[1][0]) ? ucfirst($name_parts[1][0]) : '';
                      $display_name = $first_initial . ' ' . $second_initial;
                    } elseif (isset($name_parts[0][0])) {
                      // If only one part of the name exists, use the first character
                      $display_name = ucfirst($name_parts[0][0]);
                    }
                  } else {
                    // If full name is not available, use the username from the user data
                    $display_name = $user_info->user_login;
                  }

                  ?>
                  <div class="review">
                    <div class="profile-picture-image">
                      <figure>
                        <img src="<?php echo $reviewer_profile_avatar; ?>" alt="<?php echo esc_html($display_name); ?>">
                      </figure>
                    </div>
                    <div class="so-user-review-details">
                      <p><strong>
                          <?php echo esc_html($display_name); ?>
                        </strong></p>
                      <p>
                        <?php echo esc_html($review->comment_content); ?>
                      </p>
                      <div class="star-rating">
                        <?php
                        // Output star icons based on the rating
                        for ($i = 1; $i <= 5; $i++) {
                          $star_class = ($i <= $rating) ? 'bi bi-star-fill so-full-start' : 'bi bi-star-empty so-empty-star';
                          echo '<i class="' . $star_class . '"></i>';
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                  <?php
                endforeach;
                echo ' <div class="load-more-reviews">
                <button id="load-more-reviews-btn" data-author-id="' . $author_id . '" data-offset="2">Load
                  More</button>
              </div>';
              endif;
              ?>

            </div>
            <?php
            // Display "No reviews yet." message if no reviews were found
            if (!$reviewsFound):
              so_error_template_display('No reviews yet');
            endif;
            ?>
          </div>
        </div>

      </div>
    </section>
  </div>
  <!--Review section ends here -->
</div>


<!-- The Modal-->
<!-- Modal box to update cover picture of user -->
<div class="modal fade" id="editCoverImageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cover Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="<?php echo $banner_avatar; ?>" id="selected-cover-preview"
          alt="<?php echo $author_name; ?>cover image" class="img-fluid">
      </div>
      <div class="modal-footer">

        <form id="soBannerImg" action="" method="post" enctype="multipart/form-data">
          <div id="profile-cover-uploader" class="upload-new-cover-photo">
            <i class="bi bi-camera-fill"></i>
            <!-- <h5>Upload Photo</h5> -->
            <p>Add Photo</p>
          </div>
          <input type="file" name="so-cover-imgs" id="so-cover-img" aria-label="Select Image" accept="image/*"
            style="display:none;">

          <!-- Below are a series of inputs which allow file selection and interaction with the cropper api -->
          <div id="btnBannerCrop">
            <i class="bi bi-crop"></i>
            <input type="button" id="" value="Crop Image" />
          </div>
          <div id="btnBannerRestore">
            <i class="bi bi-arrow-counterclockwise"></i>
            <input type="button" id="" value="Restore" />
          </div>

          <input class="update-image-btn update-banner-btn" name="update-cover" type="submit" value="UPDATE">
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
        <h5 class="modal-title" id="exampleModalLongTitle">Profile Picture</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="<?php echo $profile_avatar; ?>" id="selected-profile-image-preview"
          alt="<?php echo $author_name; ?>cover image" class="img-fluid">
      </div>
      <div class="modal-footer">

        <form id="soProfileImage" action="" method="post" enctype="multipart/form-data">
          <div id="profile-picture-uploader" class="upload-new-profile-photo">
            <i class="bi bi-camera-fill"></i>
            <h5>Upload Photo</h5>
            <p>Add Photo</p>
          </div>

          <input type="file" name="so-profile-imgs" id="so-profile-img" aria-label="Select Image" accept="image/*"
            style="display: none;">

          <!-- Below are a series of inputs which allow file selection and interaction with the cropper api -->
          <div id="btnCrop">
            <i class="bi bi-crop"></i>
            <input type="button" id="" value="Crop Image" />
          </div>
          <div id="btnRestore">
            <i class="bi bi-arrow-counterclockwise"></i>
            <input type="button" id="" value="Restore" />
          </div>


          <input class="update-image-btn update-profile-btn" name="update-profile" type="submit" value="UPDATE">
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


<!-- Modal for followers list -->
<div class="modal fade so-close-on-action" id="spitoutfollowerslist" tabindex="-1" role="dialog"
  aria-labelledby="spitoutfollowerslistTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="spitoutfollowerslistTitle">Followers</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body so-followers-list-modal-content">
        <?php
        foreach ($totalFollowers as $follower_id) {

          $get_seller_information = spitout_get_seller_information($follower_id);
          $seller_display_name = $get_seller_information['seller_display_name'];
          $seller_final_profile_img = $get_seller_information['seller_profile_img'];
          $seller_url = $get_seller_information['seller_url'];
          $user = get_userdata($follower_id);
          ?>

          <div class="so-followers-wrapper">
            <img src="<?php echo $seller_final_profile_img; ?>" alt="<?php echo $seller_display_name; ?>" height="100px"
              width="100px">
            <div class="so-followers-name">
              <a href="<?php echo $seller_url; ?>">
                <?php echo $user->user_login; ?>
              </a>
              <p>
                <?php echo $seller_display_name; ?>
              </p>
            </div>
            <div>
              <a href="<?php echo $seller_url; ?>">View Profile</a>
            </div>
          </div>
          <?php
        }

        if (empty($totalFollowers)) {
          echo '<p class="text-center">No followers yet.</p>';
        }
        ?>
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
              <img src="<?php echo $seller_final_profile_img; ?>" alt="<?php echo $seller_display_name; ?>" height="100px"
                width="100px">
            </div>
            <div class="so-following-name">
              <a href="<?php echo $seller_url; ?>">
                <?php echo $user->user_login; ?>
              </a>
              <p>
                <?php echo $seller_display_name; ?>
              </p>
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
get_footer(); // Include footer
