<?php

/**
 * Template Name: Edit Profile
 * 
 * Edit profile page for the buyers and sellers
 * where they can edit their personal info
 * 
 * github version->488cf11
 * 
 * @package spitout
 */

// Check if the user is not logged in
if (!is_user_logged_in()) {
    // Redirect them to the wp-admin login page
    wp_redirect(wp_login_url());
    exit;
}

get_header();

$curr_userID = get_current_user_id();
$sucess = false;
if (isset($_POST['update-profile'])) {

    if (isset($_FILES['so-banner-img']['name']) && !empty($_FILES['so-banner-img']['name'])) {
        // WordPress environmet
        require (dirname(__FILE__) . '../../../../../wp-load.php');

        // it allows us to use wp_handle_upload() function
        require_once (ABSPATH . 'wp-admin/includes/file.php');

        // validation
        if (empty($_FILES['so-banner-img'])) {
            wp_die('No files selected.');
        }

        $upload_banner = wp_handle_upload(
            $_FILES['so-banner-img'],
            ['test_form' => false]
        );

        if (!empty($upload_banner['error'])) {
            wp_die($upload_banner['error']);
        }

        // it is time to add our uploaded image into WordPress media library
        $banner_attachment_id = wp_insert_attachment(
            [
                'guid' => $upload_banner['url'],
                'post_mime_type' => $upload_banner['type'],
                'post_title' => basename($upload_banner['file']),
                'post_content' => '',
                'post_status' => 'inherit',
            ],
            $upload_banner['file']
        );

        if (is_wp_error($banner_attachment_id) || !$banner_attachment_id) {
            wp_die('Upload error.');
        }

        // update medatata, regenerate image sizes
        require_once (ABSPATH . 'wp-admin/includes/image.php');

        wp_update_attachment_metadata(
            $banner_attachment_id,
            wp_generate_attachment_metadata($banner_attachment_id, $upload_banner['file'])
        );

        update_user_meta($curr_userID, 'so_banner_img', $banner_attachment_id);
    }

    if (isset($_FILES['so-profile-img']['name']) && !empty($_FILES['so-profile-img']['name'])) {

        require (dirname(__FILE__) . '../../../../../wp-load.php');

        require_once (ABSPATH . 'wp-admin/includes/file.php');

        if (empty($_FILES['so-profile-img'])) {
            wp_die('No files selected.');
        }

        $upload_profile = wp_handle_upload(
            $_FILES['so-profile-img'],
            ['test_form' => false]
        );

        if (!empty($upload_profile['error'])) {
            wp_die($upload_profile['error']);
        }

        $profile_attachment_id = wp_insert_attachment(
            [
                'guid' => $upload_profile['url'],
                'post_mime_type' => $upload_profile['type'],
                'post_title' => basename($upload_profile['file']),
                'post_content' => '',
                'post_status' => 'inherit',
            ],
            $upload_profile['file']
        );

        if (is_wp_error($profile_attachment_id) || !$profile_attachment_id) {
            wp_die('Upload error.');
        }

        // update medatata, regenerate image sizes
        require_once (ABSPATH . 'wp-admin/includes/image.php');

        wp_update_attachment_metadata(
            $profile_attachment_id,
            wp_generate_attachment_metadata($profile_attachment_id, $upload_profile['file'])
        );

        update_user_meta($curr_userID, 'so_profile_img', $profile_attachment_id);
    }


    if (isset($_POST['so-dname']) && !empty($_POST['so-dname'])) {
        $display_name = sanitize_text_field($_POST['so-dname']);
       // wp_update_user(['ID' => $curr_userID, 'display_name' => $display_name]);
    }

    if (isset($_POST['so-location']) && !empty($_POST['so-location'])) {
        $user_location = sanitize_text_field($_POST['so-location']);
        update_user_meta((int) $curr_userID, 'so_location', $user_location);
    }

    // if (isset($_POST['so-age']) && !empty($_POST['so-age'])) {
    //     $user_age = intval($_POST['so-age']);
    //     update_user_meta((int) $curr_userID, 'so_age', $user_age);
    // }

    if (isset($_POST['so-bio']) && !empty($_POST['so-bio'])) {
        $user_bio = sanitize_textarea_field($_POST['so-bio']);
        update_user_meta((int) $curr_userID, 'so_bio', $user_bio);
    }

    if (isset($_POST['so-category']) && !empty($_POST['so-category'])) {
        update_user_meta((int) $curr_userID, 'so_category', $_POST['so-category']);
    }

    if (
        isset($_POST['so-password-current']) && !empty($_POST['so-password-current'])
        && isset($_POST['so-password-new']) && !empty($_POST['so-password-new'])
        && isset($_POST['so-password-new-confirm']) && !empty($_POST['so-password-new-confirm'])
    ) {
        $curr_password = $_POST['so-password-current'];
        $new_password = $_POST['so-password-new'];
        $confirm_new_password = $_POST['so-password-new-confirm'];



        if (wp_check_password($curr_password, get_userdata($curr_userID)->user_pass, $curr_userID)) {
            if ($new_password === $confirm_new_password) {

                wp_set_password($confirm_new_password, $curr_userID);
                echo '<h5 class="so-feed-new-container edit-profile-pwd-change-success" style="color:green">Password changed successfully!!</h5>';
                ?>
                <script>
                    window.location.href = '<?php echo home_url('login'); ?>';
                </script>
                <?php
            } else {

                echo '<h5 class="so-feed-new-container edit-profile-pwd-change-error" style="color:red">New Password and Confrim New Password do not match!</h5>';
            }

        } else {
            echo '<h5 class="so-feed-new-container edit-profile-pwd-change-error" style="color:red">Current password is wrong</h5>';
        }
    }
    $sucess = true;
}

$user_info = get_userdata($curr_userID);

//update the join date with the user creation date
update_user_meta($curr_userID, 'so_join_date', date('d.m.Y', strtotime($user_info->user_registered)));

//get current values
$curr_display_name = $user_info->display_name;
$curr_user_email = $user_info->user_email;
$curr_user_location = get_user_meta($curr_userID, 'so_location', true);
//$curr_user_dob = get_user_meta($curr_userID, 'so_dob', true);
//$dob = date_create($curr_user_dob);
//$currentDate = date_create(date("Y-m-d"));
//$ageDifference = date_diff($dob, $currentDate);
//$ageToDisplay = $ageDifference->format('%y');
// Assuming $curr_userID is defined and holds the current user ID
$curr_user_dob = get_user_meta($curr_userID, 'so_dob', true);

// Check if $curr_user_dob is a valid date string
if (is_string($curr_user_dob)) {
    $dob = date_create($curr_user_dob);
    if ($dob!== false) {
        // Create a DateTime object for the current date
        $currentDate = date_create(date("Y-m-d"));
        
        // Calculate the age difference
        $ageDifference = date_diff($dob, $currentDate);
        
        // Format the age difference
        $ageToDisplay = $ageDifference->format('%y');
        
        // Use $ageToDisplay as needed
    } else {
        // Handle the case where $dob could not be created
        // For example, log an error or set a default value
        error_log('Failed to create DateTime object from user DOB.');
        $ageToDisplay = 'Unknown';
    }
} else {
    // Handle the case where $curr_user_dob is not a valid date string
    // For example, log an error or set a default value
    error_log('User DOB is not a valid date string.');
    $ageToDisplay = 'Unknown';
}

$curr_user_bio = get_user_meta($curr_userID, 'so_bio', true);
$curr_join_date = get_user_meta($curr_userID, 'so_join_date', true);
$curr_profile_img_url = wp_get_attachment_url((int) get_user_meta($curr_userID, 'so_profile_img', true));
$curr_banner_img_url = wp_get_attachment_url((int) get_user_meta($curr_userID, 'so_banner_img', true));

$curr_user_role = '';
$user_roles = $user_info->roles;
if (in_array('seller', $user_roles)) {
    $curr_user_role = 'seller';
} elseif (in_array('buyer', $user_roles)) {
    $curr_user_role = 'buyer';
}

function spitout_fetch_all_categories_from_custom_post_type()
{
    $categories = '';

    $curr_user_categories = get_user_meta(get_current_user_id(), 'so_category', true);

    if (empty($curr_user_categories)) {
        $curr_user_categories = array(); // Assign an empty array
    }

    $args = array(
        'post_type' => 'spit-category',
        'posts_per_page' => -1,
    );
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();

            $is_selected = in_array((string) get_the_ID(), $curr_user_categories) ? 'selected' : '';
            $categories .= '<option value="' . get_the_ID() . '" ' . $is_selected . '>' . get_the_title() . '</option>';
        }
        wp_reset_postdata();
    }

    return $categories;
}

function delete_user($user_id)
{
    //Include the user file with the user administration API
    require_once (ABSPATH . 'wp-admin/includes/user.php');

    //Delete a WordPress user by specifying its user ID. Here the user with an ID equal to $user_id is deleted.
    return wp_delete_user($user_id);
}

?>
<script>
    jQuery("textarea[name='so-bio']").after("<h5 style='color:red'>Bio must be 180 characters or less</h5>");
</script>
<!-- ACCOUNT Page Contents -->
<section class="so-account-content-wrapper">
    <div class="container inner-small-container">
        <div class="row">
            <div class="col-md-12">
                <div class="so-account-settings">
                    <div class="so-account-settings-heading">
                        <h4>Settings</h4>
                    </div>
                    <div class="so-account-settings-content">
                        <div class="so-account-settings-content-title d-flex">
                            <!-- <a href="#">
                                <i class="bi bi-arrow-left-circle-fill"></i>
                            </a> -->
                            <a onclick="history.back()">
                                <span class="so-custom-icon icon-lightgray">
                                    <svg id="Layer_1" enable-background="new 0 0 100 100" height="512"
                                        viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z">
                                        </path>
                                    </svg>
                                </span>
                            </a>
                            <h5>Account</h5>
                        </div>
                        <div class="settings-pannel">
                            <h5>Account Settings</h5>
                            <p class="fw-light pt-3">See information about your account, change password, or learn about
                                your
                                account
                                deactivation options.</p>
                            <form action="" method="post"  enctype="multipart/form-data" id="profile-settings"
                                class="so-profile-settings-form">

                                <div class="form-group">
                                    <label>Display Name</label>
                                    <input type="text" disabled name="so-dname" value="<?php echo $curr_display_name; ?>"
                                        placeholder="Enter you new email">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" disabled name="so-email" value="<?php echo $curr_user_email; ?>"
                                        placeholder="Enter you new email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" name="so-location"
                                        value="<?php echo get_user_meta($curr_userID, 'so_location', true); ?>"
                                        placeholder="Enter you location">
                                </div>
                                <div class="form-group">
                                    <label>Age</label>
                                    <input min="18" step="1" pattern="\d+" type="number" name="so-age"
                                        value="<?php echo $ageToDisplay; ?>" placeholder="Enter you age" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Bio</label>
                                    <textarea type="textarea" cols="80" rows="3" name="so-bio" maxlength="180"
                                        placeholder="Bio here"><?php echo $curr_user_bio; ?></textarea>
                                    <small>Bio must be 180 characters or less.</small>
                                </div>
                                <?php if ($curr_user_role == 'seller') { ?>
                                    <div class="form-group so-settings-profile-form-category">
                                        <label>Category</label>
                                        <select id="so-category" name="so-category[]" multiple style="width:100%">
                                            <?php echo spitout_fetch_all_categories_from_custom_post_type(); ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label>Joined</label>
                                    <input type="text" name="so-join-date" value="<?php echo $curr_join_date; ?>"
                                        readonly>
                                </div>
                                <div class="form-group so-settings-profile-form-password">
                                    <label>Change my password</label>
                                    <input type="password" id="password-current" name="so-password-current" value=""
                                        placeholder="Current Password">
                                    <input type="password" id="password-new" name="so-password-new" value=""
                                        placeholder="New Password">

                                    <input type="password" id="password-new-confirm" name="so-password-new-confirm"
                                        value="" placeholder="Confirm New Password">
                                    <small>Min 9 max 30 characters, at least a an upper & lower case letter.</small>

                                </div>

                                <input name="update-profile" type="submit" value="UPDATE">
                            </form>
                            <div class="delete-account">
                                <h5>Delete my account</h5>
                                <p>PERMANENTLY DELETE your Spitout account with all your data? All data associated
                                    with your spitout account will be permanently deleted.</p>
                                <button type="button" class="so-my-spitout-detete-user-Btn" data-toggle="modal"
                                    data-target="#so-my-spitout-detete-user-Modal">
                                    <h5>Delete Account</h5>
                                </button>
                            </div>
                            <?php
                            if ($sucess) {
                                echo '<div class="alert alert-success" role="alert">Profile updated</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- delete user modal -->
<div class="modal fade so-my-spitout-modal" id="so-my-spitout-detete-user-Modal" tabindex="-1"
    aria-labelledby="delete product" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header gap-10">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/warning.png"
                    alt="Warning image to delete account" title="Warning to delete account"
                    style="margin: auto; width: 50px; height: 50px;">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to permanently delete your account?
                    This action cannot be undone.</h5>
                <button type="button" id="close-delete-user-modal" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body myspitout-modal-delete-btn">
                <button type="submit" class="spitout-delete-user-btn" name="delete_user"
                    data-user-id="<?php echo get_current_user_id(); ?>">Delete</button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>