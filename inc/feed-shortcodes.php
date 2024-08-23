<?php
function get_excerpt_with_html($content, $word_limit = 50) {
  // Replace <br> and <br/> tags with a special marker to count them as words
  $content = preg_replace('/<br\s*\/?>/i', ' <br> ', $content);

  // Split the content by spaces to count words
  $words = preg_split('/\s+/', $content);

  // If the number of words is less than or equal to the limit, return the original content
  if (count($words) <= $word_limit) {
      return $content;
  }

  // Otherwise, truncate the content to the word limit
  $excerpt = array_slice($words, 0, $word_limit);
  $excerpt = implode(' ', $excerpt);

  // Add an ellipsis if the content was truncated
  if (count($words) > $word_limit) {
      $excerpt .= '...';
  }

  return $excerpt;
}

/* The above code is a PHP function that handles the creation of a post form shortcode in WordPress. */
if (!function_exists('handle_create_post_form_submission')) {
  // Add shortcode to display frontend post creation form
  add_shortcode('create_post_form', 'handle_create_post_form_submission');
  function handle_create_post_form_submission()
  {
    ob_start();

    // Get the author ID of the current user (assuming the shortcode is used on the author page)
    $current_user_id = get_current_user_id();

    $attachment_id = (int) get_user_meta($current_user_id, 'so_profile_img', true);
    $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
    if ($attachment_array) {
      $author_avatar = $attachment_array[0]; // URL of the thumbnail image 
    }


    /* if the author avatar is empty it assign a placeholder image */
    if (empty($author_avatar)) {
      $author_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
    }
    ?>

    <div class="spitout-create-post-form-wrapper">
      <div class="so-feed-profile-image">
        <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
          <img src="<?php echo $author_avatar; ?>" alt="Profile Image"
            style="width: 100%; height: 100%; object-fit: cover;">
        </div>
      </div>

      <form id="so_create_new_post" method="post" enctype="multipart/form-data">
        <textarea name="post-content" id="post-content" placeholder="Type something..."></textarea>
        <label id="so_create_post_icon">
          <!-- for="post-image" -->
          <span class="icon">
            <i class="bi bi-image"></i>
          </span>
        </label>
        <input type="file" name="post-images[]" id="so_create_post_media" multiple="multiple" accept="image/*, video/*"
          style="display: none;">

        <?php wp_nonce_field('create_post_nonce', 'create_post_nonce_field'); ?>

        <input class="so-create-posts" data-sender="<?php echo $current_user_id; ?>" data-receiver="0"
          data-notify="new-post" data-author-id="<?php echo $current_user_id; ?>" type="submit" id="so-feed-submit-form"
          name="submit" value="Post">
      </form>
    </div>
    <!-- here this below div is used to display "You can only upload a maximum of 10 images" or "You can only upload a maximum of 1 video" -->
    <div class="so-fallback-create-post-message"></div>
    <!-- image preview start -->
    <div class="image-preview-container" id="so-create-post-img-preview-wrapper">
      <!-- <img class="spitout-feed-img-prev" src="">
      <span class="clear-image-icon"><i class="bi bi-x-circle"></i></span> -->
    </div>
    <!-- image prev ends -->

    <!-- The below code is HTML code to creates a progress bar element in the frontend. -->
    <div class="spitout-progress-bar-wrapper" style="display:none;">
      <div class="spitout-progress-bar">
      </div>
    </div>

    <?php
    return ob_get_clean();
  }
}


/**
 * Generate seller feed based on type for a specific user.
 *
 * @param string $type Type of feed to generate ('for_you', 'following', 'author')
 * @param string $user_id User ID for whom to generate the feed
 */
function spitout_seller_feed($type = 'for_you', $user_id = "0")
{

  ob_start();
  $post_per_page = 10;
  $current_user_id = $user_id;

  if ($type === 'for_you') {
    $hidden_posts = get_user_meta($current_user_id, 'so_hidden_post', true); // this contains post id in array
    $hidden_sellers = get_user_meta($current_user_id, 'so_hidden_seller', true);
    $args = array(
      'post_type' => 'spit-feed',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => $post_per_page,
      'post__not_in' => $hidden_posts,
      'author__not_in' => $hidden_sellers,
    );
  } elseif ($type === 'following') {
    $current_user_id = get_current_user_id();
    $hidden_posts = get_user_meta($current_user_id, 'so_hidden_post', true); // this contains post id in array
    $followed_sellers = get_user_meta($current_user_id, 'so_followed_sellers', true);
    $hidden_sellers = get_user_meta($current_user_id, 'so_hidden_seller', true);
    if (empty($hidden_sellers)) {
      $hidden_sellers = [];
    }
    if (empty($followed_sellers)) {
      $followed_sellers = [];
    } else {
      $followed_sellers_filtered = array_diff($followed_sellers, $hidden_sellers);
    }

    $args = array(
      // 'post_type' => 'spit-feed',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => $post_per_page,
      'author__in' => $followed_sellers_filtered, // Only display posts from followed sellers
      // 'author__not_in' => $hidden_sellers, // Exclude posts from hidden sellers
      'post__not_in' => $hidden_posts,
    );

    if (empty($followed_sellers_filtered)) {
      $args['post_type'] = 'something_that_does_not_exist';
    } else {
      $args['post_type'] = 'spit-feed';
    }
    // echo 'args are ' . print_r($args, true);

  } elseif ($type === 'author') {
    $current_uid = get_current_user_id();
    $hidden_posts = get_user_meta($current_uid, 'so_hidden_post', true); // this contains post id in array
    $args = array(
      'post_type' => 'spit-feed',
      'post_status' => array('publish', 'draft'),
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => $post_per_page,
      'author__in' => $current_user_id,
      'post__not_in' => $hidden_posts, // exclude the post IDs
    );
  }
  $posts_query = new WP_Query($args);
  //   $loader_img = get_stylesheet_directory_uri() . "/assets/img/loader.gif";
//   echo '
//   <div class="so-feed-options-loader-wrapper" style="display:none;">
//  <img src="' . $loader_img . '" alt="Loading" class="so-feed-options-loader" style="width: 25px;">
// </div>
//  ';
  echo '<input type="hidden" id="spitout-feed-type" value="' . $type . '">';
  ?>
<div id="new-posts-notice" style="display: none; text-align: center; position: fixed; top: 0; left: 0; width: 100%; z-index: 9999;">
    <i class="fas fa-bell"></i> New posts are available! <a href="#" id="close-notice"><i class="fas fa-times"></i></a>
</div>

<?php 
  if ($posts_query->have_posts()) {
    while ($posts_query->have_posts()) {
      global $post;
      $posts_query->the_post();
      cpm_fetch_feed_posts($post, $type);
    }
    // if there is more than 1 page of posts – display the button
    if ($posts_query->max_num_pages > 1) {
      echo '<div class="cpm_load_more_feed" data-args="' . esc_attr(json_encode($args)) . '" data-max-page="' . $posts_query->max_num_pages . '" data-feed-type="' . $type . '" data-current-page="1">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" class="feed-loader">
      <circle fill="#EA1E79" stroke="#EA1E79" stroke-width="2" r="15" cx="40" cy="65">
          <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;"
              keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate>
      </circle>
      <circle fill="#EA1E79" stroke="#EA1E79" stroke-width="2" r="15" cx="100" cy="65">
          <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;"
              keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate>
      </circle>
      <circle fill="#EA1E79" stroke="#EA1E79" stroke-width="2" r="15" cx="160" cy="65">
          <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;"
              keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate>
      </circle>
  </svg>
      </div>';
    }

    echo '<div class="so-all-caught-up" style="display: none;">All caught up</div>';

    wp_reset_postdata();
  } else {

    if ($type == 'following') {
      so_error_template_display('The followed seller has not posted anything.');
    }
    if ($type == 'for_you') {
      so_error_template_display('No posts found.');
    }
    if ($type == 'author') {
      so_error_template_display('The seller has not posted any post.');
    }

  }
}

/**
 * Fetches and displays feed posts.
 *
 * @param object $post The post object.
 * @param string $type The type of feed.
 * @return void
 */

function cpm_fetch_feed_posts($post, $type)
{
  $post_id = $post->ID;

  $post_single_page = get_permalink($post_id);
  $post_content = get_post_field('post_content', $post_id); // Get the post content
  $total_content_word_count = 100;
  $trimmed_content = wp_trim_words($post_content, $total_content_word_count, '...'); // Shorten to 150 words
  $wordCount = str_word_count(strip_tags($post_content)); // Count words in the full biography
  $showMoreLinkVisible = ($wordCount > $total_content_word_count); // Show the "Show more" link if more than 150 words

  $post_author_id = $post->post_author;
  $author_name = get_the_author_meta('display_name', $post->post_author);

  // $post_time_ago = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';

  $post_time = get_the_time('U', $post_id); // Get the post's timestamp
  $current_time = current_time('timestamp');
  $post_time_ago = human_time_diff($post_time, $current_time) . ' ago'; // Calculate time difference


  $post_attachment = wp_get_attachment_url((int) get_post_meta($post_id, 'so_profile_feed_img', true));
  $file_info = pathinfo($post_attachment);


  // $Parsedown = new Parsedown();
  // $html_content = $Parsedown->text($post_content);
  // $html_content = nl2br($post_content);  // Convert new lines to <br> tags
  // $post_content = wpautop($post_content);

  if (isset($file_info['extension'])) {
    $file_extension = strtolower($file_info['extension']);
  }

  // $post_author_id = get_the_author_meta('ID');
  $current_user_id = get_current_user_id();

  $author_url = get_author_posts_url($post_author_id);
  // Get the author's avatar (profile picture)
  $attachment_id = (int) get_user_meta($post_author_id, 'so_profile_img', true);
  $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
  if ($attachment_array) {
    $author_avatar = $attachment_array[0]; // URL of the thumbnail image 
  }

  /* if the author avatar is empty it assign a placeholder image */
  if (empty($author_avatar)) {
    $author_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
  }

  // Check for post status
  $post_status = get_post_status($post_id);
  // Get the total count of likes and the user IDs who have liked the post
  $total_user_liked = get_post_meta($post_id, 'likes', true);

  if (!$total_user_liked) {
    $total_user_liked = [];
  } else {
    /* this function checks if the userid are currently present or not in the database */
    $total_user_liked = spitoutFilterExistingUsers($total_user_liked);
  }
  $total_likes_count = count($total_user_liked);

  // Check if the current user has liked the post
  $hasLiked = in_array($current_user_id, $total_user_liked);
  ?>

  <div
    class="container mt-4 p-0 spitout-feed-card-wrapper spitout-uid-<?php echo $post_author_id ?> spitout-post-<?php echo $post_id; ?>">
    <div class="card so-feed-card-wImage">
      <div class="card-body so-feed-card-body">
        <div class="so-feed-profile-summary">
          <div class="d-flex align-items-center feed-profile-card-title">
            <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
              <img src="<?php echo $author_avatar; ?>" alt="Profile Image"
                style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h5 class="card-title mb-0 d-flex align-items-center">
              <a href="<?php echo $author_url; ?>">
                <?php echo $author_name; ?>
              </a>
              <?php if ((int) get_user_meta($post_author_id, 'is_verified', true) == 1) { ?>
                <div class="profile-verify" title="verified">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path
                      d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                      fill="#292D32" />
                  </svg>
                </div>
              <?php } ?>
            </h5>
            <span class="ml-auto text-muted">
              <?php echo $post_time_ago; ?>
            </span>
            <?php
            echo "<span id='hide-button' class='spitout-post-status-draft spitout-post-status-draft-" . $post_id . "' data-post-id='" . $post_id . "' style='display: " . ($post_status == 'draft' ? 'block' : 'none') . ";'><i class='bi bi-eye-slash-fill'></i></span>";
            ?>
            <span id="spitoutFeedModalBox" class="ml-2 feed-card-edit-option"
              data-post-id="<?php echo $post_id; ?>">&#8230;</span>
          </div>
        </div>

        <div class="so-feed-card-body">
          <p class="card-text">
            <?php echo get_excerpt_with_html($post_content);

            if ($showMoreLinkVisible): ?>
              <a href="<?php echo $post_single_page; ?>">
                <div class="spitout-content-show-more">
                  <p>show more</p>
                </div>
              </a>
            <?php endif; ?>
          </p>
        </div>
        <?php

        $i = 0;
        $image_count = 0;
        while ($post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img' . $i, true)) {
          $image_count++;
          $i++;
        }

        if ($image_count == 1) {
          // Display single image
          $post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img0', true);
          $thumbnail_attributes = wp_get_attachment_image_src($post_attachment_id, 'large');
          $attachment_url = wp_get_attachment_url((int) $post_attachment_id);

          if (!$thumbnail_attributes) {
            $file_info = pathinfo($attachment_url);
          } else {
            if ($thumbnail_attributes) {
              $thumbnail_url = $thumbnail_attributes[0];
              // Parse the URL and extract the path
              $parsed_url = parse_url($thumbnail_url);

              $path = $parsed_url['path'];

              // Get the directory, basename, extension, and filename
              $file_info = pathinfo($path);

              // Add the scheme and host to the directory
              $file_info['dirname'] = $parsed_url['scheme'] . '://' . $parsed_url['host'] . dirname($path);
            }
          }

          if (isset($file_info['extension'])) {
            $file_extension = strtolower($file_info['extension']);
          }

          if (!empty($post_attachment_id)) {
            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
              ?>

              <div class="so-feed-uploaded-img">
                <i class="fa fa-heart" style="display: none;"></i>
                <img src="<?php echo $thumbnail_url; ?>" class="img-fluid" alt="Image or Video"
                  data-post-id="<?php echo $post_id; ?>" data-user-id="<?php echo $current_user_id; ?>">
              </div>
              <?php
            } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) {
              ?>
              <div class="card-body so-feed-uploaded-img">
                <video preload="none" class="plyr img-fluid" width="320" height="240" controls>
                  <source src="<?php echo $attachment_url; ?>" type="video/mp4">
                </video>
              </div>
              <?php
            }
          }
        } else {
          ?>
          <div id="carouselExampleIndicators<?php echo $post_id . $type; ?>"
            class="carousel slide so-feed-carousel feed-carousel-card" data-ride="carousel" data-interval="1000000">
            <ol class="carousel-indicators">
              <?php for ($j = 0; $j < $image_count; $j++) { ?>
                <li data-target="#carouselExampleIndicators<?php echo $post_id . $type; ?>" data-slide-to="<?php echo $j; ?>"
                  <?php if ($j == 0) {
                    echo ' class="active"';
                  } ?>></li>
              <?php } ?>
            </ol>
            <div class="carousel-inner">
              <?php
              $i = 0;
              while ($post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img' . $i, true)) {
                $thumbnail_attributes = wp_get_attachment_image_src($post_attachment_id, 'large');

                if ($thumbnail_attributes) {
                  $thumbnail_url = $thumbnail_attributes[0];

                  // Parse the URL and extract the path
                  $parsed_url = parse_url($thumbnail_url);
                  $path = $parsed_url['path'];

                  // Get the directory, basename, extension, and filename
                  $file_info = pathinfo($path);

                  // Add the scheme and host to the directory
                  $file_info['dirname'] = $parsed_url['scheme'] . '://' . $parsed_url['host'] . dirname($path);
                }

                if (isset($file_info['extension'])) {
                  $file_extension = strtolower($file_info['extension']);
                }
                if (!empty($thumbnail_attributes)) {
                  ?>
                  <div class="carousel-item<?php if ($i == 0) {
                    echo ' active';
                  } ?>">
                    <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) { ?>
                      <img src="<?php echo $thumbnail_url; ?>" alt="Profile Image">
                    <?php } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) { ?>
                      <video preload="none" class="plyr" width="320" height="240" controls>
                        <source src="<?php echo $thumbnail_url; ?>" type="video/mp4">
                      </video>
                    <?php } ?>
                  </div>
                  <?php
                }
                $i++;
              }
              ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators<?php echo $post_id . $type; ?>" role="button"
              data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators<?php echo $post_id . $type; ?>" role="button"
              data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          <?php
        }

        // Display the like button with the appropriate heart icon
        echo '<div class="spitout-like-btn-wrapper">';
        echo '<div class="spitoutlikeBtn so-notify" data-sender="' . $current_user_id . '" data-receiver="' . $post_author_id . '"  data-notify="like"  data-post-id="' . $post_id . '" data-user-id="' . $current_user_id . '">';
        echo $hasLiked ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>';
        echo '</div>';

        // Display the total likes count if it's not empty
        if (!empty($total_likes_count)) {
          $total_likes_count .= $total_likes_count == 1 ? ' Like' : ' Likes';
          echo '<span class="spitout-likes-count" data-post-id="' . $post_id . '">' . $total_likes_count . '</span>';
        } else {
          echo '<span class="spitout-likes-count" data-post-id="' . $post_id . '"></span>';
        }
        echo "</div>";
        echo '<div class="spitout-likes-feedback-' . $post_id . '"></div>';

        // Get the author ID of the current user (assuming the shortcode is used on the author page)
        $current_user_id = get_current_user_id();
        $attachment_id = (int) get_user_meta($current_user_id, 'so_profile_img', true);
        $attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
        if ($attachment_array) {
          $author_avatar = $attachment_array[0]; // URL of the thumbnail image 
        }

        /* if the author avatar is empty it assign a placeholder image */
        if (empty($author_avatar)) {
          $author_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
        }

        echo '<div class="spitout-comment-display-wrap spitout-comment-postID-' . $post_id . '">';
        echo spitout_display_comments($post_id, 'feed');
        echo '</div>';
        echo spitout_create_comment_form($author_avatar, $post_id);
        ?>
      </div>
    </div>
  </div>
  <?php
}

/**
 * Generates a comment form with the current user's avatar and a textarea for adding comments.
 *
 * @param datatype $current_user_avatar The avatar of the current user.
 * @param datatype $post_id The ID of the post.
 * @return string The HTML for the comment form.
 */
function spitout_create_comment_form($current_user_avatar, $post_id)
{
  if (is_single()) {
    $pageType = 'single';
  } else {
    // Set the $type variable to a feed value for other page types
    $pageType = 'feed';
  }
  $html = '<div class="spitout-create-comment-form-wrapper">';
  $html .= '<div class="so-feed-profile-image">';
  $html .= '<div class="so-feed-profile-image" style="width: 40px; height: 40px;">';
  $html .= '<img src="' . esc_url($current_user_avatar) . '" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">';
  $html .= '</div>';
  $html .= '</div>';
  $html .= '<textarea class="so-comment-content" name="comment-content" aria-label="Add a comment" placeholder="Add a comment..." data-post-id="' . esc_attr($post_id) . '"></textarea>';
  $html .= '<div class="so-create-comment so-create-comment-' . esc_attr($post_id) . ' spitout-comment-disabled" data-post-id="' . esc_attr($post_id) . '" style="display: none;"><i class="bi bi-send-fill"></i></div>';

  $html .= '<input type="hidden" class="so-commentbox-type" name="action" value="' . $pageType . '" />';

  $html .= '</div>';

  return $html;
}

/**
 * Retrieves comments based on the post ID and type.
 *
 * @param int $post_id The ID of the post.
 * @param string $type The type of comments to retrieve ('feed' or 'single').
 */
function spitout_display_comments($post_id, $type)
{
  if ($type === 'feed') {
    $comments = get_comments(
      array(
        'post_id' => $post_id, // Replace with the actual post ID
        'type' => 'spitout_feed_comment', // Specify the comment type you used
        'number' => 2, // Limit the number of comments to 2
        'approved' => 1,
      )
    );
  } else if ($type === 'single') {
    $comments = get_comments(
      array(
        'post_id' => $post_id, // Replace with the actual post ID
        'type' => 'spitout_feed_comment', // Specify the comment type you used
        'approved' => 1,
      )
    );
  }

  $comment_count = wp_count_comments($post_id);
  $total_comments = $comment_count->approved;
  echo '<div class="so-dummy-commentbox-' . $post_id . '"></div>';
  if ($comments) {
    foreach ($comments as $comment) {
      $comment_id = $comment->comment_ID;
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
        <div
          class="spitout-comment-container spitout-uid-<?php echo $comment_author_id; ?> spitout-comment-id-<?php echo $comment_id; ?>">
          <div class="so-commentbox-wrapper">
            <div class="so-feed-profile-image">
              <img src="<?php echo esc_url($author_avatar); ?>" alt="Profile Image"
                style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="so-comment-user-wrap">
              <a href="<?php echo $seller_url; ?>">
                <div class="so-comment-author-name  d-flex align-items-center">
                  <?php echo esc_html($seller_display_name); ?>
                  <?php if ((int) get_user_meta($comment_author_id, 'is_verified', true) == 1) { ?>
                    <div class="profile-verify" title="verified">
                      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                        <path
                          d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                          fill="#292D32" />
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
                  <div class="spitoutCommentlikeBtn" data-comment-id="<?php echo $comment_id; ?>"
                    data-user-id="<?php echo $current_user_id; ?>">
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
                  <div class="so-delete-comment" data-comment-id="<?php echo $comment_id; ?>">Delete</div>
                <?php } ?>
              </div>
            </div>

          </div>

        </div>
        <?php
      }
    }
    // Check if there are more comments than the ones displayed
    if ($total_comments > 2 && $type == 'feed') {
      echo '<a href="' . get_permalink($post_id) . '#spitoutComments">View all ' . $total_comments . ' comments</a>';
    }
  }
}

/**
 * Function to display placeholder and modal div content.
 */
function spitout_placeholder_and_modal_div_content()
{
  $loader_img = get_stylesheet_directory_uri() . "/assets/img/3dotloader.gif";

  if (is_single()) {
    $type = 'single';
  } else {
    // Set the $type variable to a feed value for other page types
    $type = 'feed';
  }


  $loader_img = get_stylesheet_directory_uri() . "/assets/img/loader.gif";
  echo '
  <div class="so-feed-options-loader-wrapper" style="display:none;">
 <img src="' . $loader_img . '" alt="Loading" class="so-feed-options-loader" style="width: 25px;">
</div>
 ';
  ?>
  <!-- these are used to display different modal boxes -->
  <div id="spitoutmodalboxDisplay"></div>
  <div id="spitoutdeletemodalboxdisplay"></div>
  <div id="spitouteditmodalboxdisplay"></div>
  <div id="spitoutlikesmodalboxdisplay"></div>

  <!--  displays modal for confirmation box for hide this seller -->
  <div class="modal fade so-feed-hide-seller-modal so-close-on-action" id="soHideSellerModalBoxDisplay" tabindex="-1"
    aria-labelledby="sohideConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <h5>Hide this user? </h5>
          Are you sure you want to hide this user?
        </div>
        <div class="modal-footer ">
          <button class="btn btn-danger hide-seller-btn" data-user-id="">Hide</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>


  <!--  displays modal for confirmation box for comment delete -->
  <div class="modal fade so-feed-delete-comment-modal so-close-on-action" id="soDeleteCommentModalBoxDisplay"
    tabindex="-1" aria-labelledby="sohideConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <i class="bi bi-trash-fill"></i>
          <h5>Delete this comment? </h5>
          Are you sure you want to delete this comment?
        </div>
        <div class="modal-footer ">
          <input type="hidden" class="so-comment-type" name="action" value="<?php echo $type; ?>" />
          <button class="btn btn-danger spitout-delete-comment-btn">Delete</button>
          <!-- <button class="btn btn-danger spitout-delete-comment-btn-loader" style="display: none;">Deleting
          <img src="<?php // echo $loader_img; ?>" alt="loading"> -->
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>


  <!-- delete post modal box  -->
  <div class="modal fade so-feed-delete-modal-box so-close-on-action" id="soDeleteModalBoxDisplay" tabindex="-1"
    aria-labelledby="soDeleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-body">
          <i class="bi bi-trash-fill"></i>
          <h5> Delete post? </h5>
          Are you sure you want to delete this post?
        </div>
        <div class="modal-footer ">
          <button class="btn btn-danger delete-post-buttonn">Yes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>


  <div class="so_placeholder_comment_template d-none">
    <?php
    $comment_author_id = get_current_user_id();
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
    <div class="spitout-comment-container">
      <div class="so-commentbox-wrapper">
        <div class="so-feed-profile-image">
          <img src="<?php echo esc_url($author_avatar); ?>" alt="Profile Image"
            style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="so-comment-user-wrap">
          <a href="<?php echo $seller_url; ?>">
            <div class="so-comment-author-name  d-flex align-items-center">
              <?php echo esc_html($seller_display_name); ?>
              <?php if ((int) get_user_meta($comment_author_id, 'is_verified', true) == 1) { ?>
                <div class="profile-verify" title="verified">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <path
                      d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                      fill="#292D32" />
                  </svg>
                </div>
              <?php } ?>
            </div>
          </a>
          <div class="so-placeholder-comment-content">

          </div>
          <div class="so-comment-likes-action-container">
            <div class="so-delete-comment-status">Posting...</div>
          </div>
        </div>
      </div>
    </div>
  </div>




<!--  displays profile/cover image in modal -->
<div class="modal so-display-image-fullscreen" id="SpitoutSellerImageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <img id="spitout_modal_image_src" src="" alt="Image" style="width:100%;">
            </div>
        </div>
    </div>
</div>
  <?php
}
