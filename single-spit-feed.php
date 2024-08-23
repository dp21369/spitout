<?php

// Check if the user is not logged in
if (!is_user_logged_in()) {
  // Redirect them to the wp-admin login page
  wp_redirect(wp_login_url());
  exit;
}

get_header();
?>
<!-- this is for loading js library while playing videos  -->
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css">
<script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
<?php
$post_id = get_the_id();
$post_author_id = get_post_field('post_author', $post_id);

$user_data = get_userdata($post_author_id);
// $post_content = get_the_content();
$post_content = get_post_field('post_content', $post_id);
// $total_content_word_count = 100;
// $trimmed_content = wp_trim_words($post_content, $total_content_word_count, '...'); // Shorten to 150 words
// $wordCount = str_word_count(strip_tags($post_content)); // Count words in the full biography
// $showMoreLinkVisible = ($wordCount > $total_content_word_count); // Show the "Show more" link if more than 150 words

$author_name = $user_data->display_name;

$post_time_ago = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
$post_attachment = wp_get_attachment_url((int) get_post_meta($post_id, 'so_profile_feed_img', true));
$file_info = pathinfo($post_attachment);

if (isset($file_info['extension'])) {
  $file_extension = strtolower($file_info['extension']);
}

// $post_author_id = get_the_author_meta('ID');
$author_url = get_author_posts_url($post_author_id);

$current_user_id = get_current_user_id();

$followedAuthors = get_user_meta($current_user_id, 'so_followed_sellers', true);
// If the user doesn't have any followed authors yet, initialize an array
if (!$followedAuthors) {
  $followedAuthors = array();
}

$totalfollowers = get_user_meta($post_author_id, 'so_total_followers', true);
// If the author doesn't have any followers yet, initialize the total to 0
if (!$totalfollowers) {
  $totalfollowers = array();
}
$followersCount = count($totalfollowers);


// Get the author's avatar (profile picture)
$author_avatar = wp_get_attachment_url((int) get_user_meta($post_author_id, 'so_profile_img', true));

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

<!-- main content starts -->
<div class="container so-feed-new-container">
  <div class="container mt-4 p-0 spitout-feed-card-wrapper spitout-post-<?php echo $post_id; ?>">
    <div class="card so-feed-card-wImage">
      <div class="card-body">
        <div class="so-feed-profile-summary">
          <div class="d-flex align-items-center feed-profile-card-title">
            <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
              <img src="<?php echo $author_avatar; ?>" alt="Profile Image"
                style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <h5 class="card-title mb-0 d-flex">
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
            // if ($post_status == 'draft') {
            //   echo "<span id='hide-button' class='spitout-post-status-draft spitout-post-status-draft-" . $post_id . "' data-post-id='" . $post_id . "'><i class='bi bi-eye-slash-fill'></i></span>";
            // }
            echo "<span id='hide-button' class='spitout-post-status-draft spitout-post-status-draft-" . $post_id . "' data-post-id='" . $post_id . "' style='display: " . ($post_status == 'draft' ? 'block' : 'none') . ";'><i class='bi bi-eye-slash-fill'></i></span>";

            ?>
            <span id="spitoutFeedModalBox" class="ml-2 feed-card-edit-option"
              data-post-id="<?php echo $post_id; ?>">&#8230;</span>
          </div>
        </div>

        <div class="so-feed-card-body">
          
			 <div class="entry-content">
                    <?php
                    echo $post_content;
                    ?>
                </div>
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
                  data-post-id="<?php echo $post_id; ?>">
              </div>
              <?php
            } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) {
              ?>
              <div class="card-body so-feed-uploaded-img">
                <video class="plyr img-fluid" width="320" height="240" controls>
                  <source src="<?php echo $post_attachment; ?>" type="video/mp4">
                </video>
              </div>
              <?php
            }
          }
        } else if ($image_count == 0) {
          // Display single image
          $post_attachment_id = get_post_meta($post_id, 'so_profile_feed_img', true);
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
                    data-post-id="<?php echo $post_id; ?>">
                </div>
              <?php
            } elseif (in_array($file_extension, ['mp4', 'avi', 'mov', 'wmv'])) {
              ?>
                <div class="card-body so-feed-uploaded-img">
                  <video class="plyr img-fluid" width="320" height="240" controls>
                    <source src="<?php echo $post_attachment; ?>" type="video/mp4">
                  </video>
                </div>
              <?php
            }
          }
        } else {
          // Display slider
          ?>
            <div id="carouselExampleIndicators" class="carousel slide so-feed-carousel feed-carousel-card"
              data-ride="carousel">
              <ol class="carousel-indicators">
              <?php for ($j = 0; $j < $image_count; $j++) { ?>
                  <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $j; ?>" <?php if ($j == 0) {
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
                        <video class="plyr" width="320" height="240" controls>
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
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
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
        $current_user_avatar = wp_get_attachment_url((int) get_user_meta($current_user_id, 'so_profile_img', true));

        if (empty($current_user_avatar)) {
          $current_user_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
        }
        echo '<div id="spitoutComments" class="spitout-comment-display-wrap spitout-comment-postID-' . $post_id . '">';
        echo spitout_display_comments($post_id, 'single');
        echo '</div>';
        echo spitout_create_comment_form($current_user_avatar, $post_id);
        ?>
      </div>
    </div>
  </div>
</div>

<script>
  jQuery(document).ready(function() {
    const playerr = new Plyr('.plyr', {
      controls: ['play-large', 'mute']
    });
    playerr.muted = true;
    playerr.play();
    playerr.volume = 100;

  });
</script>
<?php
echo spitout_placeholder_and_modal_div_content();


get_footer();