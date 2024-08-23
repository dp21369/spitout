<?php

/**
 * Template Name: Hidden Users 
 * @package spitout
 */

// Check if the user is not logged in
if (!is_user_logged_in()) {
    // Redirect to the site's URL
    wp_redirect(home_url());
    exit; // Ensure WordPress stops further execution
}

get_header();

$get_current_seller_id = get_current_user_id();
$get_list_of_hidden_users = get_user_meta($get_current_seller_id, 'so_hidden_seller', true);

?>


<div class="container so-feed-new-container so-hidden-user-page">
    <h4>Hidden Users</h4>

    <?php
    if ($get_list_of_hidden_users) {

        foreach ($get_list_of_hidden_users as $key => $get_list_of_hidden_user) {
            # code...
            $get_users_id = $get_list_of_hidden_user;
            $user_info = get_userdata($get_users_id);
            $username = $user_info->user_login;
            $seller_img = get_user_meta($get_users_id, "so_profile_img", true);
            $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
            $seller_url = get_author_posts_url($get_users_id);
            $seller_img_url = wp_get_attachment_url($seller_img, 'thumbnail');
            $seller_final_profile_img = $seller_img_url ? $seller_img_url : $profile_avatar;

            echo ' 
                <div class="so-likes-list-modal-content mt-4 hidden_user-' . $get_users_id . '" id="so-hidden-user-wrapper">
                <div class="so-likes-list-wrapper">
                <img src="' . $seller_final_profile_img . '" alt="' . $username . '" height="100px" width="100px">
                <div class="so-likes-list-name">
                    <a href="' . $seller_url . '">
                        ' . $username . ' </a>
                    <h6>
                        ' . $username . ' </h6>
                </div>

                <div class="spitout-modal-likes-actions">
                    
                        <div class="so-view-profile so-unhide-user-btn"  data-id="' . $get_users_id . '">
                            <h6>Unhide</h6>
                        </div>
                   
                </div>
                </div>
            </div>';
        }

    ?>


    <?php } else {


        echo '
         <div class="so-likes-list-modal-content mt-4" id="so-hidden-user-wrapper">
        
        No hidden Users
        
        </div>
        
        ';
    }


    ?>


</div>


<?php get_footer(); ?>