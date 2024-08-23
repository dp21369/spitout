<?php

/* This function is responsible for follow action  */
if (!function_exists('follow_author_callback')) {
  add_action('wp_ajax_follow_author', 'follow_author_callback');
  function follow_author_callback()
  {
    if (isset($_POST['author_id'])) {
      $authorId = intval($_POST['author_id']);
      $currentUserId = get_current_user_id();

      // Prevent a user from following themselves
      if ($authorId == $currentUserId) {
        die(); // Exit the function if the user tries to follow themselves
      }

      // Get the existing followed authors for the current user
      $followedSellers = get_user_meta($currentUserId, 'so_followed_sellers', true);
      $sellerTotalFollowers = get_user_meta($authorId, 'so_total_followers', true);


      // If the user doesn't have any followed Sellers yet, initialize an array
      if (!$followedSellers) {
        $followedSellers = array();
      }

      // If the user doesn't have any followers yet, initialize an array
      if (!$sellerTotalFollowers) {
        $sellerTotalFollowers = array();
      }

      // Add the current user ID to the author's total followers
      if (!in_array($currentUserId, $sellerTotalFollowers)) {
        $sellerTotalFollowers[] = $currentUserId;
        // Update the user meta with the new array of followed Sellers
        update_user_meta($authorId, 'so_total_followers', $sellerTotalFollowers);
      }

      // Add the new author ID to the array if it doesn't already exist
      if (!in_array($authorId, $followedSellers)) {
        $followedSellers[] = $authorId;
        // Update the user meta with the new array of followed Sellers
        update_user_meta($currentUserId, 'so_followed_sellers', $followedSellers);
      }
    }
    die();
  }
}

/* This function is responsible for unfollow action  */
if (!function_exists('unfollow_author_callback')) {
  add_action('wp_ajax_unfollow_author', 'unfollow_author_callback');
  function unfollow_author_callback()
  {
    if (isset($_POST['author_id'])) {
      $authorId = intval($_POST['author_id']);
      $currentUserId = get_current_user_id();

      // Get the existing followed sellers for the current user
      $followedSellers = get_user_meta($currentUserId, 'so_followed_sellers', true);
      $sellerTotalFollowers = get_user_meta($authorId, 'so_total_followers', true);

      // If the user doesn't have any followed sellers yet, initialize an array
      if (!$followedSellers) {
        $followedSellers = array();
      }

      // If the user doesn't have any followers yet, initialize an array
      if (!$sellerTotalFollowers) {
        $sellerTotalFollowers = array();
      }

      // Remove the author ID from the array if it exists
      if (in_array($authorId, $followedSellers)) {
        $key = array_search($authorId, $followedSellers);
        unset($followedSellers[$key]);

        // Update the user meta with the new array of followed sellers
        update_user_meta($currentUserId, 'so_followed_sellers', $followedSellers);
      }

      // Remove the author ID from the array if it exists
      if (in_array($currentUserId, $sellerTotalFollowers)) {
        $key = array_search($currentUserId, $sellerTotalFollowers);
        unset($sellerTotalFollowers[$key]);

        // Update the user meta with the new array of followed sellers
        update_user_meta($authorId, 'so_total_followers', $sellerTotalFollowers);
      }
    }
    die();
  }
}


/* this function is used to change the post status of the post  */
if (!function_exists('so_change_post_status')) {
  function so_change_post_status()
  {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ($post_id) {
      // Get the current post status
      $current_status = get_post_status($post_id);

      // Decide the new status based on the current one
      $new_status = ($current_status == 'publish') ? 'draft' : 'publish';

      $post_data = array(
        'ID' => $post_id,
        'post_status' => $new_status,
      );

      // Update the post
      wp_update_post($post_data);

      // Return the new post status
      echo json_encode(array('status' => 'success', 'new_status' => $new_status));
    } else {
      echo json_encode(array('status' => 'error'));
    }
    die(); // Always exit after handling AJAX
  }
  add_action('wp_ajax_so_change_post_status', 'so_change_post_status');
}



/**
 * This PHP function handles the AJAX request for uploading a file and adding it to the WordPress media
 * library.
 */
if (!function_exists('so_feed_cover_img_update')) {
  add_action('wp_ajax_so_feed_cover_img_update', 'so_feed_cover_img_update');
  function so_feed_cover_img_update()
  {
    $upload_overrides = array('test_form' => false);
    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {

      // WordPress environmet
      require(ABSPATH . '/wp-load.php');
      // it allows us to use wp_handle_upload() function
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      // validation
      if (empty($_FILES['file'])) {
        wp_die('No files selected.');
      }
      $upload_landing_video = wp_handle_upload($_FILES['file'], $upload_overrides);
      // var_dump($upload_landing_video);
      if (!empty($upload_landing_video['error'])) {
        echo 'error vo';
        wp_die($upload_landing_video['error']);
      }
      // it is time to add our uploaded image into WordPress media library
      $banner_attachment_id = wp_insert_attachment(
        [
          'guid' => $upload_landing_video['url'],
          'post_mime_type' => $upload_landing_video['type'],
          'post_title' => basename($upload_landing_video['file']),
          'post_content' => '',
          'post_status' => 'inherit',
        ],
        $upload_landing_video['file']
      );
      if (is_wp_error($banner_attachment_id) || !$banner_attachment_id) {
        wp_die('Upload error.');
      }
      update_user_meta(get_current_user_id(), 'so_banner_img', $banner_attachment_id);

      wp_send_json_success(wp_get_attachment_url($banner_attachment_id));
    }
    die();
  }
}



/**
 * The above PHP function handles the AJAX request for updating a user's profile image by uploading a
 * file and saving it as a WordPress attachment.
 */
if (!function_exists('so_feed_profile_img_update')) {
  add_action('wp_ajax_so_feed_profile_img_update', 'so_feed_profile_img_update');
  function so_feed_profile_img_update()
  {
    $upload_overrides = array('test_form' => false);
    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {

      // WordPress environmet
      require(ABSPATH . '/wp-load.php');
      // it allows us to use wp_handle_upload() function
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      // validation
      if (empty($_FILES['file'])) {
        wp_die('No files selected.');
      }
      $upload_landing_video = wp_handle_upload($_FILES['file'], $upload_overrides);
      // var_dump($upload_landing_video);
      if (!empty($upload_landing_video['error'])) {
        echo 'error vo';
        wp_die($upload_landing_video['error']);
      }
      // it is time to add our uploaded image into WordPress media library
      $banner_attachment_id = wp_insert_attachment(
        [
          'guid' => $upload_landing_video['url'],
          'post_mime_type' => $upload_landing_video['type'],
          'post_title' => basename($upload_landing_video['file']),
          'post_content' => '',
          'post_status' => 'inherit',
        ],
        $upload_landing_video['file']
      );
      if (is_wp_error($banner_attachment_id) || !$banner_attachment_id) {
        wp_die('Upload error.');
      }
      update_user_meta(get_current_user_id(), 'so_profile_img', $banner_attachment_id);

      wp_send_json_success(wp_get_attachment_url($banner_attachment_id));
    }
    die();
  }
}


/* The above code is a PHP function that creates new posts in feed post type */
if (!function_exists('so_create_new_posts')) {
  function so_create_new_posts()
  {
    // it allows us to use wp_handle_upload() function
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    // $post_content = wp_kses_post(urldecode($_POST['postContent']));
    $post_content = wp_kses_post(($_POST['postContent']));
    // Determine the post title based on the post content and uploaded files
    $post_title = date('Y-m-d H:i:s'); // Default title
    if (!empty($post_content)) {
      // If post content is not empty, trim it and use it as the title
      $post_title = wp_trim_words($post_content, $num_words = 8, '...');
    } elseif (isset($_FILES['file']['name'][0]) && !empty($_FILES['file']['name'][0])) {
      // If post content is empty, check if there's an uploaded file to use its name as the title
      $post_title = basename($_FILES['file']['name'][0]); // Use the first uploaded file's name
    }
    // Insert the new post and get the post ID
    $new_post = array(
      'post_title' => $post_title,
      'post_content' => $post_content,
      'post_status' => 'publish',
      'post_type' => 'spit-feed'
    );

    $post_id = wp_insert_post($new_post);

    // Check if the file field is set and the file has been uploaded successfully
    $upload_overrides = array('test_form' => false);
    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
      require(dirname(__FILE__) . '../../../../../wp-load.php');
      require_once(ABSPATH . 'wp-admin/includes/file.php');

      $file_count = count($_FILES['file']['name']);
      for ($i = 0; $i < $file_count; $i++) {
        if (empty($_FILES['file']['name'][$i])) {
          continue;
        }

        $file_array = array(
          'name' => $_FILES['file']['name'][$i],
          'type' => $_FILES['file']['type'][$i],
          'tmp_name' => $_FILES['file']['tmp_name'][$i],
          'error' => $_FILES['file']['error'][$i],
          'size' => $_FILES['file']['size'][$i],
        );

        // Handle media file upload
        $upload = wp_handle_upload($file_array, ['test_form' => false]);

        if (!empty($upload['error'])) {
          wp_die($upload['error']);
        }

        // Insert the uploaded media file into the media library with the correct post ID as post_parent
        $attachment_id = wp_insert_attachment(
          [
            'guid' => $upload['url'],
            'post_mime_type' => $upload['type'],
            'post_title' => basename($upload['file']),
            'post_content' => '',
            'post_status' => 'inherit'
          ],
          $upload['file']
        );

        if (is_wp_error($attachment_id) || !$attachment_id) {
          wp_die('Failed to add media to the library. Please try again.');
        }
        update_post_meta($post_id, 'so_profile_feed_img' . $i, $attachment_id);
      }
    }
    $post = get_post($post_id);

    // Generate the HTML using cpm_fetch_feed_posts
    ob_start();
    cpm_fetch_feed_posts($post, 'author');
    $html = ob_get_clean();

    // wp_send_json_success($post_id);
    wp_send_json_success(array('post_id' => $post_id, 'html' => $html));
    die();
  }
  add_action('wp_ajax_so_create_new_posts', 'so_create_new_posts');
}

/**
 * The function checks if the current user has the capability to delete posts, and if so, it deletes
 * the post with the specified ID.
 */
if (!function_exists('spitout_delete_post_ajax')) {
  add_action('wp_ajax_spitout_delete_post_ajax', 'spitout_delete_post_ajax');
  function spitout_delete_post_ajax()
  {
    $response = array(); // Initialize response array

    if (current_user_can('delete_posts')) {
      if (isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);
        // Delete the post
        wp_trash_post($post_id);
        $response = array('status' => 'success', 'message' => 'Post deleted successfully');
      } else {
        $response = array('status' => 'error', 'message' => 'No post id set');
      }
    } else {
      $response = array('status' => 'error', 'message' => 'Current user cannot delete posts');
    }
    echo json_encode($response);
    die();
  }
}

/**
 * Reloads the shortcode "[spitout_seller_feed]" in the author profile page.
 *
 * @throws None
 * @return void
 */
if (!function_exists('so_reload_shortcode')) {
  function so_reload_shortcode()
  {
    $author_id = get_current_user_id();
    spitout_seller_feed('author', $author_id);
    die();
  }
  add_action('wp_ajax_so_reload_shortcode', 'so_reload_shortcode');
}

/**
 * Generates the HTML markup for a modal box that displays options for a feed.
 *
 * @throws None
 * @return None
 */
if (!function_exists('spitout_feed_modal_box')) {
  function spitout_feed_modal_box()
  {
    $post_id = absint($_POST['postId']); // Sanitize the post ID to ensure it's a positive integer
    $post_status = get_post_status($post_id); // Retrieve the status of the post 
    $post_author_id = get_post_field('post_author', $post_id); // Retrieve the author ID of the post
    $post_single_page = get_permalink($post_id); // Retrieve the permalink of the post
    $current_user_id = get_current_user_id(); // Retrieve the current user ID
    $followedAuthors = get_user_meta($current_user_id, 'so_followed_sellers', true) ?: array(); // Retrieve the list of authors followed by the current user, defaulting to an empty array if not set
    $isSingularPage = strpos($_SERVER['HTTP_REFERER'], 'spit-feed') !== false; // Check if the current page URL contains 'spit-feed', indicating if it's a singular page
    $totalfollowers = get_user_meta($post_author_id, 'so_total_followers', true) ?: array(); // Retrieve the total number of followers for the author
    $followersCount = count($totalfollowers); // Count the total number of followers
?>
    <div class="modal fade so-feed-options-modal-box" id="soModalBoxDisplay" tabindex="-1" aria-labelledby="soFeedModalBoxLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="so-edit-card-modal-popup modal-body d-flex flex-column align-items-center">
            <?php
            if ($post_author_id == $current_user_id) {
            ?>
              <div class="so-delete-list-item-modalbox delete-list-item so-edit-card-option" id="spitoutDeleteModalBox" data-post-id="<?php echo $post_id; ?>">
                <span class="text-danger">
                  <h6>Delete</h6>
                </span>
              </div>
              <div id="hide-button" class="so-edit-card-option" data-post-id="<?php echo $post_id; ?>">
                <?php
                if ($post_status == 'draft') {
                  echo '<h6>Unhide</h6>';
                } else {
                  echo '<h6>Hide</h6>';
                }
                ?>
              </div>
              <?php
            } else {
              if (in_array($post_author_id, $followedAuthors)) {
              ?>
                <h6 class="so-following-seller so-edit-card-option" data-author-id="<?php echo $post_author_id; ?>" data-followers-count="<?php echo $followersCount; ?>">Unfollow</h6>
              <?php
              } else {
              ?>
                <h6 class="so-follow-seller so-edit-card-option" data-author-id="<?php echo $post_author_id; ?>" data-followers-count="<?php echo $followersCount; ?>">Follow</h6>
                </p>
              <?php
              }
              ?>
              <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif" alt="Loading" class="so-feed-following-loader" style="display: none; width: 25px;">
              <?php if (!$isSingularPage) { ?>
                <div class="so-edit-card-option so-hide-this-post" data-post-id="<?php echo $post_id; ?>">
                  <h6>Hide this post</h6>
                </div>
                <div class="so-edit-card-option so-hide-this-seller" data-seller-id="<?php echo $post_author_id; ?>">
                  <h6>Hide this seller</h6>
                </div>
            <?php
              }
            }
            ?>
            <?php if (!$isSingularPage) { ?>
              <div class="so-edit-card-option">
                <a href="<?php echo $post_single_page; ?>">
                  <h6>Go to post</h6>
                </a>
              </div>
            <?php } ?>
            <div data-dismiss="modal" class="so-edit-card-option">
              <h6>Cancel</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php
    die();
  }
  add_action('wp_ajax_spitout_feed_modal_box', 'spitout_feed_modal_box');
}

/**
 * Handles the like action for a post.
 *
 * @param int $post_id The ID of the post being liked.
 * @param int $user_id The ID of the user liking the post.
 * @throws None
 * @return int The new like count for the post.
 */
if (!function_exists('handle_like_action')) {
  function handle_like_action()
  {
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    // Check if the user has already liked this post
    $total_post_likes = get_post_meta($post_id, 'likes', true);
    // $post_likes = spitoutFilterExistingUsers($total_post_likes);
    if (!$total_post_likes) {
      $post_likes = array();
    } else {
      $post_likes = spitoutFilterExistingUsers($total_post_likes);
    }

    if (in_array($user_id, $post_likes)) {
      // User has liked the post, so remove the like
      $post_likes = array_diff($post_likes, array($user_id));
      $like_count = count($post_likes);
    } else {
      // User has not liked the post, so add the like
      $post_likes[] = $user_id;
      $like_count = count($post_likes);
    }

    // Update the post metadata and like count
    update_post_meta($post_id, 'likes', $post_likes);
    // Return the new like count
    echo json_encode(array('result' => $like_count));
    die();
  }
  add_action('wp_ajax_like_action', 'handle_like_action');
}

/**
 * Handles the comment action for liking/unliking a comment.
 *
 * @throws WP_Error If the user is not logged in.
 * @return void
 */
if (!function_exists('spitout_comment_action')) {
  function spitout_comment_action()
  {
    $comment_id = intval($_POST['comment_id']);
    $user_id = $_POST['user_id'];

    // Ensure the user is logged in
    if (!is_user_logged_in()) {
      wp_send_json_error(array('message' => 'User not logged in.'));
    }

    // Check if the user has already liked this post
    $total_comment_likes = get_comment_meta($comment_id, 'comment_likes', true);
    // $post_likes = spitoutFilterExistingUsers($total_post_likes);
    if (!$total_comment_likes) {
      $comment_likes = array();
    } else {
      $comment_likes = spitoutFilterExistingUsers($total_comment_likes);
    }

    if (in_array($user_id, $comment_likes)) {
      // User has liked the post, so remove the like
      $comment_likes = array_diff($comment_likes, array($user_id));
      $like_count = count($comment_likes);
    } else {
      // User has not liked the post, so add the like
      $comment_likes[] = $user_id;
      $like_count = count($comment_likes);
    }

    // Update the post metadata and like count
    update_comment_meta($comment_id, 'comment_likes', $comment_likes);

    // Return the new like count
    echo json_encode(array('result' => $like_count));
    die();
  }

  add_action('wp_ajax_spitout_comment_action', 'spitout_comment_action');
}

/**
 * Displays a modal window that shows a list of users who have liked a particular post or comment.
 *
 * @throws None
 * @return void
 */
if (!function_exists('spitout_view_likes_list')) {
  function spitout_view_likes_list()
  {
    // $post_id = $_POST['postId'];
    // $total_user_liked = get_post_meta($post_id, 'likes', true);
    if (isset($_POST['postId'])) {
      // This is for post likes
      $post_id = $_POST['postId'];
      $total_user_liked = get_post_meta($post_id, 'likes', true);
    } elseif (isset($_POST['commentId'])) {
      // This is for comment likes
      $comment_id = $_POST['commentId'];
      $total_user_liked = get_comment_meta($comment_id, 'comment_likes', true);
    } else {
      // Handle the case where neither postId nor commentId is provided
      return;
    }
    $total_user_liked = spitoutFilterExistingUsers($total_user_liked);

  ?>
    <div class="modal fade so-close-on-action" id="spitoutlikeslist" tabindex="-1" role="dialog" aria-labelledby="spitoutlikeslistTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="spitoutlikeslistTitle">Likes</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body so-likes-list-modal-content">
            <?php
            foreach ($total_user_liked as $user_id) {
              $get_seller_information = spitout_get_seller_information($user_id);
              $seller_display_name = $get_seller_information['seller_display_name'];
              // $seller_final_profile_img = $get_seller_information['seller_profile_img'];

              $attachment_id = (int) get_user_meta($user_id, 'so_profile_img', true);
              $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
              if ($attachment_array) {
                $author_avatar = $attachment_array[0]; // URL of the thumbnail image 
              }


              /* if the author avatar is empty it assign a placeholder image */
              if (empty($author_avatar)) {
                $author_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
              }


              $seller_url = $get_seller_information['seller_url'];
              $user = get_userdata($user_id);
              $current_user_id = get_current_user_id();
              $followedAuthors = get_user_meta($current_user_id, 'so_followed_sellers', true);
              // If the user doesn't have any followed authors yet, initialize an array
              if (!$followedAuthors) {
                $followedAuthors = array();
              }



              $totalfollowers = get_user_meta($user_id, 'so_total_followers', true);
              // If the author doesn't have any followers yet, initialize the total to 0
              if (!$totalfollowers) {
                $totalfollowers = array();
              }
              $followersCount = count($totalfollowers);


            ?>
              <div class='so-likes-list-wrapper'>
                <img src="<?php echo $author_avatar; ?>" alt="<?php echo $seller_display_name; ?>" height="100px" width="100px">
                <div class="so-likes-list-name">
                  <a href="<?php echo $seller_url; ?>">
                    <?php echo $user->user_login; ?>
                  </a>
                  <h6>
                    <?php echo $seller_display_name; ?>
                  </h6>
                </div>

                <?php if ($user_id != $current_user_id) { ?>
                  <div class="spitout-modal-likes-actions">
                    <?php
                    if (user_can($user_id, 'seller')) {


                      if (in_array($user_id, $followedAuthors)) {
                    ?>
                        <h6 class="so-following-seller so-edit-card-option" data-author-id="<?php echo $user_id; ?>" data-followers-count="<?php echo $followersCount; ?>">Unfollow</h6>
                      <?php
                      } else {
                      ?>
                        <h6 class="so-follow-seller so-edit-card-option" data-author-id="<?php echo $user_id; ?>" data-followers-count="<?php echo $followersCount; ?>">Follow</h6>
                        </p>
                  <?php

                      }
                    } elseif (user_can($user_id, 'buyer')) {
                      echo '<a href="' . $seller_url . '"><div class="so-view-profile"><h6>View Profile</h6></div></a>';
                    }
                    echo '</div>';
                  }
                  ?>

                  </div>
                <?php
              }
                ?>
              </div>
          </div>
        </div>
      </div>
      <?php
      die();
    }
    add_action('wp_ajax_spitout_view_likes_list', 'spitout_view_likes_list');
  }

  /**
   * Update a post content and handle file upload if needed.
   *
   * @throws Some_Exception_Class description of exception
   * @return Some_Return_Value
   */
  if (!function_exists('spitout_update_post')) {
    function spitout_update_post()
    {
      // Get the post ID from the request
      $post_id = intval($_POST['post_id']);
      // Update the post content
      $updated_post_content = wp_kses_post($_POST['post_content']);
      wp_update_post(
        array(
          'ID' => $post_id,
          'post_content' => $updated_post_content
        )
      );

      // Check if the file field is set and the file has been uploaded successfully
      if (isset($_FILES['edited-post-image']) && !empty($_FILES['edited-post-image']['name'])) {
        // Handle media file upload
        $upload = wp_handle_upload($_FILES['edited-post-image'], ['test_form' => false]);

        if (!empty($upload['error'])) {
          wp_send_json_error('Failed to upload the file. Please try again.');
        }

        // Insert the uploaded media file into the media library with the correct post ID as post_parent
        $attachment_id = wp_insert_attachment(
          array(
            'guid' => $upload['url'],
            'post_mime_type' => $upload['type'],
            'post_title' => basename($upload['file']),
            'post_content' => '',
            'post_status' => 'inherit'
          ),
          $upload['file']
        );
        if (is_wp_error($attachment_id) || !$attachment_id) {
          wp_send_json_error('Failed to add media to the library. Please try again.');
        }
        // Update the post meta with the attachment ID
        update_post_meta($post_id, 'so_profile_feed_img', $attachment_id);
      }
      // Return a success response
      wp_send_json_success('Post updated successfully.');
    }
    add_action('wp_ajax_spitout_update_post', 'spitout_update_post');
  }


  add_action('wp_ajax_get_states', 'get_states_callback');
  add_action('wp_ajax_nopriv_get_states', 'get_states_callback');

  function get_states_callback()
  {
    $country = sanitize_text_field($_POST['country']);
    $states = WC()->countries->get_states($country);

    // Generate the options for the state dropdown.
    $options = '';

    if (empty($states)) {
      // If no states were found, add a placeholder option.
      $options .= '<option value="" disabled selected>No states found</option>';
    } else {
      foreach ($states as $key => $value) {
        $options .= "<option value='$key'>$value</option>";
      }
    }
    echo $options;
    wp_die(); // Always include this to terminate the AJAX request.
  }

  /**
   * Registers a new user based on the provided registration form data.
   *
   * @throws None
   * @return None
   */
  function spitout_user_registration()
  {
    /*Pulling data from registration form*/
    $fullName = sanitize_text_field($_POST['fullName']);
    $username = sanitize_text_field($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $dateOfBirth = sanitize_text_field($_POST['dob']);
    $accountpassword = $_POST['accountpassword'];
    $country = sanitize_text_field($_POST['country']);
    $state = sanitize_text_field($_POST['state']);
    $role = sanitize_text_field($_POST['role']);
    $confirmPassword = $_POST['confirmpassword'];

    // if ($accountpassword !== $confirmPassword) {
    //   $response = [
    //     'type' => 'pwd',
    //     'status' => 'error',
    //     'message' => 'The password do not match'
    //   ];
    //   wp_send_json($response);
    // }

    if (!empty($state)) {
      // Concatenate country and state with a comma if state is not empty
      $location = $country . ',' . $state;
    } else {
      // Use only the country if state is empty
      $location = $country;
    }

    /* Check if the username and email address are unique */
    if (username_exists($username)) {
      $response = [
        'type' => 'username',
        'status' => 'error',
        'message' => 'The username ' . $username . ' already exists.'
      ];
      wp_send_json($response);
    }

    if (email_exists($email)) {
      $response = [
        'type' => 'email',
        'status' => 'error',
        'message' => 'The email address ' . $email . ' already exists.'
      ];
      wp_send_json($response);
    }

    /* Check if the username and email address meet formatting requirements */
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
      $response = [
        'type' => 'username',
        'status' => 'error',
        'message' => 'The username ' . $username . ' is not valid.'
      ];
      wp_send_json($response);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response = [
        'type' => 'email',
        'status' => 'error',
        'message' => 'The email address ' . $email . ' is not valid.'
      ];
      wp_send_json($response);
    }

    /*Creating new user*/
    // $user_id = register_new_user($username, $email);
    // wp_set_password($accountpassword, $user_id);

    $userdata = array(
      'user_login'           => $username,
      'user_nicename'        => $fullName,
      'nickname'             => $fullName,
      'user_email'           => $email,
      'user_pass'            => $accountpassword,
      'display_name'         => $fullName,
      'role'                 => $role,
    );

    $user_id = wp_insert_user($userdata);
	   // Split full name into parts
    $name_parts = explode(' ', $fullName);

    // Assign first name as the first word
    $first_name = $name_parts[0];

    // Combine the remaining parts into the last name
    $last_name = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : '';

    // Update user meta for first name and last name
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
	  
    /*Check for errors when creating new user*/
    if (empty($user_id)) {
      $response = [
        'status' => 'error',
        'message' => 'Sorry, user cannot be created.',
      ];
      wp_send_json($response);
    } else {
      /*Pulling user data of new user*/
      $user = new WP_User($user_id);

      /* Log the user in automatically */
      wp_clear_auth_cookie();
      wp_set_current_user($user_id, $user->user_login);
      wp_set_auth_cookie($user_id);
      do_action('wp_login', $user->user_login, $user);

      /* Send a custom account created email instead of a password reset link */
      $type = "registration";
      $to = $email;
      $subject = '[SpitOut: Natural Intimacy, Uniquely Yours!] Your account has been created';
      $message = 'Welcome ' . $fullName . '! Your account has been successfully created.';

      spitout_email_templates($fullName, $to, $subject, $message, $type);

      $response = [
        'type' => $role,
        'status' => 'success',
        'message' => 'New User Created',
      ];
      wp_send_json($response);
    }
  }
  add_action('wp_ajax_spitout_user_registration', 'spitout_user_registration');
  add_action('wp_ajax_nopriv_spitout_user_registration', 'spitout_user_registration');

  /**
   * Check if an email exists in the WordPress database.
   *
   * @throws None
   * @return None
   */
  add_action('wp_ajax_spitout_check_email_exists', 'spitout_check_email_exists');
  add_action('wp_ajax_nopriv_spitout_check_email_exists', 'spitout_check_email_exists');
  function spitout_check_email_exists()
  {
    // Get the email sent via AJAX
    $entered_email = sanitize_email($_POST['input']);

    // Check if the username exists
    if (username_exists($entered_email)) {
      echo 'exists'; // Username already exists
    } else {
      echo 'unique'; // Username is unique
    }


    wp_die(); // Always include this to end AJAX requests in WordPress
  }


  /**
   * Check if a username exists in the WordPress database via AJAX.
   *
   * @throws Exception If the AJAX request fails.
   */
  add_action('wp_ajax_spitout_check_username_exists', 'spitout_check_username_exists');
  add_action('wp_ajax_nopriv_spitout_check_username_exists', 'spitout_check_username_exists');

  function spitout_check_username_exists()
  {
    // Get the email sent via AJAX
    $entered_username = sanitize_text_field($_POST['input']);

    // Query the WordPress database to check if the email exists
    $user = get_user_by('login', $entered_username);

    if ($user) {
      echo 'exists'; // Email already exists
    } else {
      echo 'unique'; // Email is unique
    }

    wp_die(); // Always include this to end AJAX requests in WordPress
  }

  /**
   * Deletes a WordPress user by specifying its user ID.
   *
   * @param int $user_id The ID of the user to be deleted.
   * @return void
   */
  if (!function_exists('spitout_delete_user')) {
    add_action('wp_ajax_spitout_delete_user', 'spitout_delete_user');
    function spitout_delete_user()
    {
      // Get the email sent via AJAX
      $user_id = $_POST['user_id'];
      $site_url = home_url();

      //Include the user file with the user administration API
      require_once(ABSPATH . 'wp-admin/includes/user.php');

      //Delete a WordPress user by specifying its user ID. Here the user with an ID equal to $user_id is deleted.
      wp_delete_user($user_id);

      wp_send_json($site_url);
      wp_die(); // Always include this to end AJAX requests in WordPress
    }
  }

  /**
   * Updates the list of hidden posts for a specific user.
   *
   * @param None
   * @throws None
   * @return None
   */
  if (!function_exists('spitout_hide_post_ajax')) {
    function spitout_hide_post_ajax()
    {
      $current_post_id = $_POST['post_id'];
      $current_user_id = get_current_user_id();

      // Retrieve the current array of hidden posts
      $hidden_posts = get_user_meta($current_user_id, 'so_hidden_post', true);

      if (!is_array($hidden_posts)) {
        $hidden_posts = array();
      }

      // Check if the $hidden_posts array exists and is not empty
      if (is_array($hidden_posts) && !empty($hidden_posts)) {
        // Create a temporary array to store existing post IDs
        $existing_post_ids = array();

        // Loop through each post ID in the $hidden_posts array
        foreach ($hidden_posts as $post_id) {
          // Check if the post exists using the lightweight get_post_status() function
          $post_status = get_post_status($post_id);

          // If the post exists (status is not false), add it to the $existing_post_ids array
          if ($post_status !== false) {
            $existing_post_ids[] = $post_id;
          }
        }

        // Update the $hidden_posts array with only existing post IDs
        $hidden_posts = $existing_post_ids;
      }
      // Add the new post id to the array
      array_push($hidden_posts, $current_post_id);

      // Update the user meta with the new array
      $result = update_user_meta($current_user_id, 'so_hidden_post', $hidden_posts);

      // If the update was successful, send a success response, else send an error response
      if ($result) {
        wp_send_json_success(array('message' => 'Success'));
      } else {
        wp_send_json_error(array('message' => 'Data not saved'));
      }

      die();
    }
    add_action('wp_ajax_spitout_hide_post_ajax', 'spitout_hide_post_ajax');
  }

  /**
   * Unhides a post for the current user.
   *
   * @param int $post_id The ID of the post to unhide.
   * @throws Exception If the post ID is not found in the hidden posts.
   * @return void
   */
  if (!function_exists('spitout_unhide_post_ajax')) {
    function spitout_unhide_post_ajax()
    {
      $post_id = $_POST['post_id'];
      $current_user_id = get_current_user_id();

      // Retrieve the current array of hidden posts
      $hidden_posts = get_user_meta($current_user_id, 'so_hidden_post', true);

      // Check if $hidden_posts is an array, and $post_id exists in it
      if (is_array($hidden_posts) && in_array($post_id, $hidden_posts)) {
        // Remove the post_id from the array
        $updated_hidden_posts = array_diff($hidden_posts, array($post_id));

        // Update the user meta with the updated array
        $result = update_user_meta($current_user_id, 'so_hidden_post', $updated_hidden_posts);

        // If the update was successful, send a success response, else send an error response
        if ($result) {
          wp_send_json_success(array('message' => 'Success'));
        } else {
          wp_send_json_error(array('message' => 'Data not saved'));
        }
      } else {
        wp_send_json_error(array('message' => 'Post ID not found in hidden posts'));
      }

      die();
    }
    add_action('wp_ajax_spitout_unhide_post_ajax', 'spitout_unhide_post_ajax');
  }

  /**
   * Hides a seller by adding the seller ID to the user's hidden seller array.
   *
   * @throws WP_Error If the data is not saved.
   * @return void
   */
  if (!function_exists('spitout_hide_seller_ajax')) {
    function spitout_hide_seller_ajax()
    {
      $seller_id = $_POST['seller_id'];
      $current_user_id = get_current_user_id();

      // Retrieve the current array of hidden posts
      $hidden_sellers = get_user_meta($current_user_id, 'so_hidden_seller', true);
      if (!is_array($hidden_sellers)) {
        $hidden_sellers = array();
      }

      // Check if the seller ID is already in the array
      if (!in_array($seller_id, $hidden_sellers)) {
        // If not, add the new post id to the array
        array_push($hidden_sellers, $seller_id);

        // Update the user meta with the new array
        $result = update_user_meta($current_user_id, 'so_hidden_seller', $hidden_sellers);

        // If the update was successful, send a success response, else send an error response
        if ($result) {
          wp_send_json_success(array('message' => 'Success'));
        } else {
          wp_send_json_error(array('message' => 'Data not saved'));
        }
      } else {
        // If the seller ID is already in the array, send a response indicating it's already hidden
        wp_send_json_error(array('message' => 'Seller already hidden'));
      }

      die();
    }
    add_action('wp_ajax_spitout_hide_seller_ajax', 'spitout_hide_seller_ajax');
  }

  if (!function_exists('spitout_save_comment')) {
    // Add AJAX action to handle the comment saving request
    add_action('wp_ajax_spitout_save_comment', 'spitout_save_comment');
    function spitout_save_comment()
    {
      $postId = $_POST['post_id'];
      // Validate and sanitize the comment content
      $commentContent = sanitize_textarea_field($_POST['commentContent']);

      // Check if comment content is empty
      if (empty($commentContent)) {
        wp_send_json_error(array('message' => 'Comment content cannot be empty.'));
      }

      // Get the current user ID
      $userId = get_current_user_id();

      // Create the comment data array
      $commentData = array(
        'comment_post_ID' => $postId,
        // Replace with the actual post ID
        'comment_author' => $userId,
        'user_id' => $userId,
        // Replace with the actual author ID
        'comment_content' => $commentContent,
        'comment_type' => 'spitout_feed_comment',
        // Set the comment type to 'feed_comment'
        'comment_approved' => 1 // Set to 1 to automatically approve the comment
      );

      // Save the comment using wp_insert_comment()
      $commentId = wp_insert_comment($commentData);
      // march

      // Get the comment object
      $comment = get_comment($commentId);

      $comment_id = $commentId;
      $comment_author_id = get_comment_author($comment_id);
      $comment_date = $comment->comment_date;
      // Convert the input date and current date to Unix timestamps
      $formatted_time = formatTimeDifference($comment_date);
      // Check if the user exists
      $user = get_userdata($comment_author_id);
      $current_user_id = get_current_user_id();

      // Get the total count of likes and the user IDs who have liked the post
      $total_comment_likes = get_comment_meta($comment_id, 'comment_likes', true);

      if (!$total_comment_likes) {
        $total_comment_likes = [];
      } else {
        /* this function checks if the userid are currently present or not in the database */
        $total_comment_likes = spitoutFilterExistingUsers($total_comment_likes);
      }

      $total_comment_likes_count = count($total_comment_likes);

      // Check if the current user has liked the post
      $hasLikedComment = in_array($current_user_id, $total_comment_likes);

      if ($user) {
        $comment_content = $comment->comment_content;
        $get_seller_information = spitout_get_seller_information($comment_author_id);

        $seller_display_name = $get_seller_information['seller_display_name'];
        $attachment_id = (int) get_user_meta($comment_author_id, 'so_profile_img', true);
        $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
        if ($attachment_array) {
          $author_avatar = $attachment_array[0]; // URL of the thumbnail image 
        }

        /* if the author avatar is empty it assign a placeholder image */
        if (empty($author_avatar)) {
          $author_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
        }
        $seller_url = $get_seller_information['seller_url'];

      ?>
        <div class="spitout-comment-container spitout-comment-id-<?php echo $comment_id; ?>">
          <div class="so-commentbox-wrapper">
            <div class="so-feed-profile-image">
              <img src="<?php echo esc_url($author_avatar); ?>" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="so-comment-user-wrap">
              <a href="<?php echo $seller_url; ?>">
                <div class="so-comment-author-name  d-flex align-items-center">
                  <?php echo esc_html($seller_display_name); ?>
                  <?php if ((int) get_user_meta($comment_author_id, 'is_verified', true) == 1) { ?>
                    <div class="profile-verify" title="verified">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z" fill="#292D32" />
                      </svg>
                    </div>
                  <?php } ?>
                </div>
              </a>
              <div class="so-comment-main-content">
                <?php echo esc_html($comment_content); ?>
              </div>
              <div class="so-comment-likes-action-container">
                <div class="spitout-like-btn-wrapper">
                  <div class="spitoutCommentlikeBtn" data-comment-id="<?php echo $comment_id; ?>" data-user-id="<?php echo $current_user_id; ?>">
                    <?php
                    echo $hasLikedComment ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>';
                    ?>
                  </div>
                </div>
                <?php

                if (!empty($total_comment_likes_count)) {
                  $total_comment_likes_count .= $total_comment_likes_count == 1 ? ' Like' : ' Likes';
                  echo '<span class="spitout-likes-count so-comment-id-' . $comment_id . '" data-comment-id="' . $comment_id . '">' . $total_comment_likes_count . '</span>';
                } else {
                  echo '<span class="spitout-likes-count so-comment-id-' . $comment_id . '" data-comment-id="' . $comment_id . '" style="display: none;"></span>';
                }
                ?>

                <div class="so-comment-posted-time">
                  <?php echo $formatted_time; ?>
                </div>
                <?php
                if ($current_user_id == $comment_author_id) {
                ?>
                  <div class="so-delete-comment" data-comment-id="<?php echo $comment_id; ?>" data-post-id="<?php echo $postId; ?>">Delete</div>
                <?php } ?>
              </div>
            </div>

          </div>

        </div>
        <?php }
      // march end
      // Return a response to the AJAX request
      // if ($commentId) {
      //   // Comment saved successfully
      //   wp_send_json_success(array('message' => 'Comment saved successfully.'));
      // } else {
      //   // Error saving comment
      //   wp_send_json_error(array('message' => 'Error saving comment.'));
      // }

      // Make sure to exit after sending the response
      wp_die();
    }
  }

  /**
   * Deletes a comment via AJAX callback.
   *
   * @throws Some_Exception_Class You must be logged in to delete comments.
   * @throws Some_Exception_Class The comment does not exist. For comment id {comment_id}.
   * @throws Some_Exception_Class You do not have permission to delete this comment.
   */
  if (!function_exists('spitout_delete_comment_callback')) {
    // Add AJAX actions for both logged in and non-logged in users
    add_action('wp_ajax_spitout_delete_comment_action', 'spitout_delete_comment_callback');
    function spitout_delete_comment_callback()
    {
      // Check if the user is logged in
      if (!is_user_logged_in()) {
        echo json_encode(array('error' => 'You must be logged in to delete comments.'));
        wp_die();
      }

      // Get the comment ID from the AJAX request
      $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;

      // Check if the comment exists
      $comment = get_comment($comment_id);
      if (!$comment) {
        echo json_encode(array('error' => 'The comment does not exist. For comment id ' . $comment_id));
        wp_die();
      }

      // Check if the current user is the comment author or has sufficient permissions
      $current_user_id = get_current_user_id();

      if ($comment->user_id == $current_user_id || current_user_can('delete_comment', $comment_id)) {
        // If the user is the comment author or has permission, delete the comment
        wp_delete_comment($comment_id);
        echo json_encode(array('success' => 'Comment deleted successfully.'));
      } else {
        // Send an error response if the user does not have permission
        echo json_encode(array('error' => 'You do not have permission to delete this comment.'));
      }

      // Always exit to avoid further processing
      wp_die();
    }
  }

  /**
   * Loads tab content for the current user.
   *
   * @throws Some_Exception_Class Always exits to avoid further processing.
   */
  if (!function_exists('so_load_tab_content')) {
    add_action('wp_ajax_so_load_tab_content', 'so_load_tab_content');
    function so_load_tab_content()
    {
      $current_user_id = get_current_user_id();
      spitout_seller_feed('following', $current_user_id);
      // Always exit to avoid further processing
      wp_die();
    }
  }

  /**
   * Updates the order status to completed.
   *
   * @throws Exception If the order ID is not provided or the order cannot be found.
   */
  if (!function_exists('so_mark_order_as_complete')) {
    add_action('wp_ajax_so_mark_order_as_complete', 'so_mark_order_as_complete');
    function so_mark_order_as_complete()
    {
      $order_id = (int) $_POST['order_id'];
      $order = new WC_Order($order_id);
      $order->update_status('completed');
    }
  }

  /**
   * Updates the order status to processing and adds tracking information.
   *
   * @param int $needs_delivery_o_id The ID of the order needing delivery
   * @param string $needs_delivery_tracking_id The tracking ID for the delivery
   */
  if (!function_exists('so_mark_order_as_shipped')) {
    add_action('wp_ajax_so_mark_order_as_shipped', 'so_mark_order_as_shipped');
    function so_mark_order_as_shipped()
    {
      $needs_delivery_o_id = $_POST['order_id'];
      $needs_delivery_tracking_id = $_POST['tracking_num'];

      if (empty($needs_delivery_o_id) || empty($needs_delivery_tracking_id)) {
        return;
      }

      $order = new WC_Order($needs_delivery_o_id);
      $order->update_status('processing');
      update_post_meta($needs_delivery_o_id, 'order_tracking_id', $needs_delivery_tracking_id);
    }
  }

  if (!function_exists('spitout_feed_load_more_ajax')) {
    add_action('wp_ajax_spitout_feed_load_more_ajax', 'spitout_feed_load_more_ajax'); // wp_ajax_{action}
    add_action('wp_ajax_nopriv_spitout_feed_load_more_ajax', 'spitout_feed_load_more_ajax'); // wp_ajax_nopriv_{action}
    function spitout_feed_load_more_ajax()
    {
      $args = isset($_POST['query']) ? $_POST['query'] : [];
      $args['paged'] = isset($_POST['page']) ? intval($_POST['page']) + 1 : 1;
      $type = sanitize_text_field($_POST['type']);

      $posts_query = new WP_Query($args);
      if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
          global $post;
          $posts_query->the_post();
          cpm_fetch_feed_posts($post, $type);
        }
        wp_reset_postdata();
      } else {
        echo '<div class="so-no-post-found-wrapper"><div class="so-no-feed-post so-error-msg">No posts found.</div></div>';
      }
      die;
    }
  }

  function so_check_new_posts()
  {
    // $interval = 4 * 60; // 4 minutes in seconds
    $interval = 10; // 10 sec

    // Get the current time and the time of the last check
    $now = current_time('timestamp');
    $last_check = get_option('last_check_time', $now - $interval);

    // for_you page 
    $current_user_id = get_current_user_id();

    $hidden_posts = get_user_meta($current_user_id, 'so_hidden_post', true); // this contains post id in array
    $hidden_sellers = get_user_meta($current_user_id, 'so_hidden_seller', true);
    $for_you_args = array(
      'post_type' => 'spit-feed',
      'post_status' => 'publish',
      'post__not_in' => $hidden_posts,
      'author__not_in' => $hidden_sellers,
      'date_query' => array(
        array(
          'after' => date('Y-m-d H:i:s', $last_check),
          'before' => date('Y-m-d H:i:s', $now),
        ),
      )
    );

    // following agrs 
    $followed_sellers = get_user_meta($current_user_id, 'so_followed_sellers', true);
    if (empty($hidden_sellers)) {
      $hidden_sellers = [];
    }
    if (empty($followed_sellers)) {
      $followed_sellers = [];
    } else {
      $followed_sellers_filtered = array_diff($followed_sellers, $hidden_sellers);
    }

    $following_args = array(
      'post_status' => 'publish',
      'author__in' => $followed_sellers_filtered, // Only display posts from followed sellers
      'post__not_in' => $hidden_posts,
      'date_query' => array(
        array(
          'after' => date('Y-m-d H:i:s', $last_check),
          'before' => date('Y-m-d H:i:s', $now),
        ),
      ),
    );
    if (empty($followed_sellers_filtered)) {
      $following_args['post_type'] = 'something_that_does_not_exist';
    } else {
      $following_args['post_type'] = 'spit-feed';
    }



    // Query for new posts
    $args = array(
      'post_status' => 'publish',
      'post_type' => 'spit-feed',
      'date_query' => array(
        array(
          'after' => date('Y-m-d H:i:s', $last_check),
          'before' => date('Y-m-d H:i:s', $now),
        ),
      ),
    );
    $query = new WP_Query($following_args);

    // Check if new posts are found
    $new_posts = $query->have_posts();

    // Update the last check time
    update_option('last_check_time', $now);

    // Send the response
    wp_send_json(array('new_posts' => $new_posts));
  }
  add_action('wp_ajax_so_check_new_posts', 'so_check_new_posts');
  add_action('wp_ajax_nopriv_so_check_new_posts', 'so_check_new_posts');



  function spitout_load_more_reviews()
  {
    global $wpdb;

    $author_id = isset($_POST['author_id']) ? intval($_POST['author_id']) : 0;
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $per_page = 2; // Number of reviews to load per request

    if ($author_id > 0) {
      $args_reviews = array(
        'comment_approved' => 1,
        'post_id' => $author_id,
        'comment_type' => 'so_seller_review',
        'number' => $per_page,
        'offset' => $offset,
      );

      $reviews = get_comments($args_reviews);

      if (!empty($reviews)) {
        ob_start();
        foreach ($reviews as $review) {
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
              <p><strong><?php echo esc_html($display_name); ?></strong></p>
              <p><?php echo esc_html($review->comment_content); ?></p>
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
        }
        $output = ob_get_clean();
        echo $output;
      } else {
        echo 'No more reviews found.';
      }
    } else {
      echo 'Invalid author ID.';
    }

    wp_die();
  }
  add_action('wp_ajax_nopriv_spitout_load_more_reviews', 'spitout_load_more_reviews');
  add_action('wp_ajax_spitout_load_more_reviews', 'spitout_load_more_reviews');
