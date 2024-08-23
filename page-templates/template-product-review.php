<?php
/**
 * Template Name: Product Review Page
 * @package spitout
 */

// Check if the 'user_id' parameter is present in the URL
if (!isset($_GET['user_id'])) {
    // If 'user_id' is not present, redirect to the /orders page
    wp_redirect(home_url('/orders'));
    exit;
}

get_header();
$get_user_id = sanitize_text_field($_GET['user_id']);

// Check if the user exists in the database
$user_data = get_userdata($get_user_id);

if ($user_data) {
    $author_name = get_the_author_meta('display_name', $get_user_id, true);
    $user_id = get_current_user_id();

    $existing_review = get_comments(
        array(
            'user_id' => $user_id,
            'post_id' => $get_user_id, // Use the seller's user ID as the post ID for seller reviews
            'type' => 'so_seller_review',
            'number' => 1,
        )
    );

    if (isset($_POST['submit_review'])) {
        $rating = intval($_POST['rating']);
        $review_text = sanitize_textarea_field($_POST['review-text']);
        // $current_user = wp_get_current_user();

        $review_data = array(
            'comment_post_ID' => $get_user_id, // here the review is done for seller so the product id is being saved as 0. 
            'comment_author' => $user_id,
            'comment_author_email' => $user_email,
            'comment_content' => $review_text,
            'comment_date' => current_time('mysql'),
            'comment_approved' => 1,
            'comment_type' => 'so_seller_review',
            'user_id' => $user_id,
        );

        if ($existing_review) {
            $comment_id = $existing_review[0]->comment_ID;
            $update_success = wp_update_comment($review_data + array('comment_ID' => $comment_id));
            if ($update_success) {
                update_comment_meta($comment_id, 'rating', $rating);
                $success_message = "<p class='so-success-msg'>Your review has been updated successfully</p>";
            }
        } else {
            $comment_id = wp_insert_comment($review_data);
            if ($comment_id) {
                add_comment_meta($comment_id, 'rating', $rating);
                $success_message = "<p class='so-success-msg'>Your review has been submitted successfully</p>";
            }
        }
    }
    ?>

    <div class="container so-author-page-review so-feed-new-container pt-3 so-product-review">
        <div class="profile-user-product-details">
            <div class="so-rating-detail-wrapper">
                <div class="profile-user-price-details">
                    <div class="profile-user-saliva-type">
                        <i class="bi bi-droplet-fill"></i>
                        <h5><?php echo $author_name; ?></h5>
                    </div>
                </div>
                <form id="review-form" method="post">
                    <div class="ratings-wrapper">
                        <label for="rating">Ratings:</label>
                        <span class="star-ratings">
                            <?php
                            $existing_rating = 0;
                            if ($existing_review) {
                                $existing_rating = get_comment_meta($existing_review[0]->comment_ID, 'rating', true);
                            }
                            for ($i = 5; $i >= 1; $i--) {
                                $checked = ($i == $existing_rating) ? 'checked' : '';
                                echo "<input type='radio' name='rating' id='rate-$i' value='$i' $checked><label for='rate-$i' class='star'>&#9733;</label>";
                            }
                            ?>
                        </span>
                    </div>
                    <div class="review-text">
                        <label for="review-text">Review:</label>
                        <textarea name="review-text" id="review-text" rows="4" required><?php
                        if ($existing_review) {
                            echo $existing_review[0]->comment_content;
                        }
                        ?></textarea>
                    </div>
                    <?php wp_nonce_field('custom_review_nonce', 'custom_review_nonce'); ?>
                    <input type="submit" class="send-new-review-ntff" name="submit_review"
                        value="<?php echo $existing_review ? 'Update Review' : 'Submit Review'; ?>">
                </form>
                <?php
                if (isset($success_message)) {
                    echo $success_message;
                    echo "<a href='/orders' class='btn btn-primary'>View Your Orders</a>"; // Directly add the button here
                }
                ?>

            </div>
        </div>
    </div>

    <?php
} else {

    echo ' <div class="container so-author-page-review so-feed-new-container pt-3 so-product-review">
    <div class="profile-user-product-details">
    <div class="so-error-msg">The user does not exist. The user may have been deleted their account.</div></div></div>';

}

get_footer();