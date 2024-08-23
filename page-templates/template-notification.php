<?php

/**
 * Template Name: Notification page
 * @package spitout
 */
// Check if the user is not logged in
if (!is_user_logged_in()) {
    // Redirect to the site's URL
    wp_redirect(home_url());
    exit; // Ensure WordPress stops further execution
}
get_header();
?>
<section class="so-notification-page so-feed-new-container m-auto">
    <div class="container">
        <div class="row align-items-center mb-1 ntf-page-heading">
            <div class="col-lg-6">
                <h4>Notifications</h4>
            </div>
            <div class="col-lg-6 so-read-all-ntf so-marked-read-ntf dropdown d-flex">

                <button id="request-permission" class="browser-permissions"
                    title="Enable push notification">ENABLE</button>

                <div class="delete-ntfy">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif" alt="Loading"
                        class="delete-ntfy-loader" style="display: none; width: 25px;">
                    <!-- delete all notifications -->
                    <i class="bi bi-x-circle notification-action delete-ntfy-icon" data-action="delete"
                        data-toggle="tooltip" data-placement="top" title="Delete all notifications"></i>
                </div>

                <div class="seen-ntfy">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/spit-loader.gif" alt="Loading"
                        class="seen-ntfy-loader" style="display: none; width: 25px;">
                    <!-- mark all notifications as 'seen' -->
                    <i class="bi bi-check-all notification-action seen-ntfy-icon" data-action="mark-as-seen"
                        data-toggle="tooltip" data-placement="top" title="Mark all notifications as seen"></i>
                </div>
            </div>
        </div>
        <?php
        $notifications = notifications_display();

        foreach ($notifications as $notification) {
            $sender_display_name = $notification['sender_display_name'];
            $sender_profile_img_url = $notification['sender_profile_img_url'];
            $message = $notification['message'];
            $elapsed = $notification['elapsed_time'];
            $notification_url = $notification['notification_url'];
            $notification_id = $notification['id'];
            $notifications_seen = $notification['is_seen'];

            echo '<a href="' . $notification_url . '" class="so-notification-link" data-notification-id="' . $notification_id . '">
                <div class="row ntf-single-detail-row mb-1 ' . (($notifications_seen == 0) ? 'so-unseen' : 'so-seen') . '">
                    <div class="col-lg-8 so-ntf-single-details">
                        <figure>
                            <img src="' . $sender_profile_img_url . '" alt="Profile Image"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </figure>
                        <div class="ntf-details">
                            <h5>
                                ' . $sender_display_name . '
                            </h5>
                            <p>
                                ' . $message . '
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 so-ntf-detail-time">
                        <p>
                            ' . $elapsed . '
                        </p>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="4" viewBox="0 0 18 4" fill="none">
                                <circle cx="2" cy="2" r="2" fill="#D2D2D2" />
                                <circle cx="9" cy="2" r="2" fill="#D2D2D2" />
                                <circle cx="16" cy="2" r="2" fill="#D2D2D2" />
                            </svg>
                        </span>
                    </div>
                </div>
            </a>';
        } ?>
    </div>
</section>

<?php get_footer(); ?>