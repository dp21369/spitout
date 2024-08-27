<?php

/**
 * Create notifications table on theme activation
 * @return void
 **/
function so_create_notifications_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'so_notifications';

    //drop old table
    $wpdb->query("DROP TABLE IF EXISTS wp_so__notifications");
    // $wpdb->query("DROP TABLE IF EXISTS wp_so_notifications");

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
            receiver_id bigint(10) unsigned NOT NULL,
            sender_id bigint(10) unsigned NOT NULL,
            notif_type varchar(50) NOT NULL,
            target_id varchar(25) NOT NULL,
            seen tinyint(1) NOT NULL DEFAULT 0,
            sent_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_notif_receiver_id (receiver_id),
            INDEX idx_notif_sender_id (sender_id),
            INDEX idx_notif_sent_at (sent_at)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('after_setup_theme', 'so_create_notifications_table');

/**
 * Function to add notifications to database
 * @param int $sender
 * @param int $receiver
 * @param string $notification_type
 * @return string
 **/
function so_notification_handler($sender, $receiver, $notification_type, $post_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'so_notifications';
    $current_datetime = new DateTime();

    $ntfy_data_arr = [
        'receiver_id' => $receiver,
        'sender_id' => $sender,
        'notif_type' => $notification_type,
        // 'sent_at' => current_time('mysql'),
        'sent_at' => $current_datetime->format('Y/m/d H:i:s'),
    ];

    //if notification is about a post than set the the post_id as the target_id else set the sender_id as the target_id
    if ($post_id == null) {
        $ntfy_data_arr += ['target_id' => $sender];
    } else {
        $ntfy_data_arr += ['target_id' => $post_id];
    }

    $wpdb->insert(
        $table_name,
        $ntfy_data_arr,
        array(
            '%d',
            // receiver
            '%d',
            // sender
            '%s',
            // message_type
            '%s',
            '%s' // created_timestamp
        )
    );

    // Return success or error message
    if ($wpdb->insert_id) {
        return "success";
    } else {
        return "error";
    }
}

/**
 * Function to handle notification requests
 * @return void
 **/
function so_notification()
{
    //Pull data from ajax
    $so_sender = isset($_POST['so_sender']) ? intval($_POST['so_sender']) : 0;
    $so_receiver = isset($_POST['so_receiver']) ? intval($_POST['so_receiver']) : 0;
    $so_receiver_array = array();
    $so_notification_type = $_POST['so_notification_type'];
    $post_id = isset($_POST['postID']) ? $_POST['postID'] : null;
    $response = array();


    if ($so_receiver == 0) {
        $so_receiver_array = get_user_meta($so_sender, "so_total_followers", true);

        foreach ($so_receiver_array as $individual_receiver) {
            $result = so_notification_handler($so_sender, $individual_receiver, $so_notification_type, $post_id);
        }

        if ($result === "success") {
            $response = [
                'status' => 'success',
                'message' => 'The data has been received.',
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error entering data into the database',
            ];
        }
    } else {

        $result = so_notification_handler($so_sender, $so_receiver, $so_notification_type, $post_id);

        if ($result === "success") {
            $response = [
                'status' => 'success',
                'message' => 'The data has been recieved.',
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'error entering the data into the database',
            ];
        }
    }
    wp_send_json($response);
}
add_action('wp_ajax_so_notification', 'so_notification');


/**
 * Function to pull notifications
 * @param int $limit
 * @param string $unseen_notifications
 * @return array<array>
 **/
function notifications_display($limit = -1, $unseen_notifications = 'all')
{

    global $wpdb;

    $notifications_array = array();

    // User ID of the receiver
    $receiverUserID = get_current_user_id();


    // Table name
    $tableName = $wpdb->prefix . 'so_notifications';

    // SQL query to retrieve rows
    $query = $wpdb->prepare(
        "SELECT * FROM $tableName WHERE receiver_id = %d ORDER BY sent_at DESC",
        $receiverUserID
    );

    // Execute the query
    $rows = $wpdb->get_results($query);
    // var_dump($rows);

    if ($rows) {
        foreach ($rows as $row) {
            $sender = $row->sender_id;

            // Check if the sender_id exists
            $user_data = get_userdata($sender);
            if ($user_data === false) {
                // User does not exist. Continue to next iteration.
                continue;
            }

            $message_type = $row->notif_type;
            $timestamp = $row->sent_at;
            $notification_id = $row->id;
            $notification_seen = $row->seen;
            $target_id = $row->target_id;

            $sender_data = spitout_get_seller_information($sender);
            $sender_display_name = $sender_data['seller_display_name'];
            $sender_url = $sender_data['seller_url'];
            $sender_profile_img_url = $sender_data['seller_profile_img'];
            $sender_username = $sender_data['seller_user_name'];
            // $user_info = get_userdata($sender);
            // $sender_username = $user_info->user_login;
            $error = 0;
            $notification_url = $sender_url;

            $timestamp_datetime = new DateTime($timestamp);
            $current_datetime = new DateTime();
            $time_difference = $current_datetime->diff($timestamp_datetime);
            // echo '<pre>';
            // echo $current_datetime->format('Y/m/d H:i:s');
            // echo $time_difference->y;
            // echo $time_difference->m;
            // echo $time_difference->d;
            // echo $time_difference->h;
            // echo $time_difference->i;
            // echo '</pre>';

            $elapsed = "";

            //Add values to elapsed accordinlg to the time difference
            if ($time_difference->y > 0) {
                $elapsed = $time_difference->y . " years ago";
            } elseif ($time_difference->m > 0) {
                $elapsed = $time_difference->m . " months ago";
            } elseif ($time_difference->d > 0) {
                $elapsed = $time_difference->d . " days ago";
            } elseif ($time_difference->h > 0) {
                $elapsed = $time_difference->h . " hours ago";
            } elseif ($time_difference->i > 0) {
                $elapsed = $time_difference->i . " mins ago";
            } else {
                $elapsed = "just now";
            }

            //Add messages according to message type
            switch ($message_type) {
                case "follow":
                    $message = "Started following you";
                    $notification_url = home_url('/chat/');
                    break;

                case "new-post":
                    $message = "Uploaded a new post";
                    // $notification_url = home_url('/chat/');
                    $notification_url = get_permalink((int) $target_id);
                    break;

                case "new-message":
                    $message = "Sent a message";
                    // $notification_url = home_url('/chat/');
                    $notification_url = add_query_arg('uid', $sender, home_url('/chat/'));
                    break;

                case "new-orders":
                    $message = "Sent an order";
                    $notification_url = add_query_arg('order_id', $target_id, home_url('/sales-confirm/'));
                    break;

                case "profile-view":
                    $message = "Viewed your profile";
                    $notification_url = home_url($sender_username);
                    break;

                case "like":
                    $message = "Liked your post";
                    $notification_url = get_permalink((int) $target_id);
                    break;

                case "comment-post":
                    $message = "Commented on your post";
                    $notification_url = get_permalink((int) $target_id);
                    break;

                case "msg-request":
                    $message = "Requested to chat";
                    $notification_url = add_query_arg('request', $sender, home_url('/chat/'));
                    break;

                case "order-shipped":
                    $message = "Your order has been shipped, Tracking Id: ".$target_id;
                    $notification_url = home_url('/orders/');
                    break;

                case "seller-review":
                    $message = $sender_username." left a review";
                    $notification_url = get_author_posts_url(get_current_user_id()).'?view=review' ;
                    break;

                default:
                    $error = 1;
            }

            if ($error === 0) {
                // Populate the array with notification data
                $notifications_array[] = array(
                    'id' => $notification_id,
                    'sender_display_name' => $sender_display_name,
                    'sender_profile_img_url' => $sender_profile_img_url,
                    'elapsed_time' => $elapsed,
                    'notification_url' => $notification_url,
                    'message' => $message,
                    'is_seen' => $notification_seen
                );
            }
        }
    }

    //Seperate the unseen notifications
    $unseen_notifications_array = array_filter($notifications_array, function ($notification) {
        return $notification['is_seen'] == 0;
    });

    // Apply the limit and slice the array accordingly
    if ($limit > 0) {
        if ($unseen_notifications == 'unseen') {
            $unseen_notifications_array = array_slice($unseen_notifications_array, 0, $limit);
        } else {
            $notifications_array = array_slice($notifications_array, 0, $limit);
        }
    }

    //Return arrays accordingly
    if ($unseen_notifications == 'unseen') {
        return $unseen_notifications_array;
    } else {
        return $notifications_array;
    }
}

/**
 * Function to update the notification table's is_seen column
 * @return void
 **/
add_action('wp_ajax_so_notification_update', 'so_notification_update');
function so_notification_update()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'so_notifications';

    $curr_user_id = get_current_user_id();

    if ($_POST['ntfy_action'] == 'mark_all_as_seen') { //mark all notification as seen

        $wpdb->query($wpdb->prepare("UPDATE $table_name SET seen='1' WHERE receiver_id = '" . $curr_user_id . "'"));

        $response = ['status' => 'all_success'];

    } else if ($_POST['ntfy_action'] == 'delete') { //delete all notifications

        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE receiver_id = '" . $curr_user_id . "'"));

        $response = ['status' => 'all_deleted'];

    } else if ($_POST['ntfy_action'] == 'mark_as_seen') { //mark specific id as seen

        $notification_id = $_POST['notification_id'];

        $wpdb->update(
            $table_name,
            array('seen' => 1),
            array('id' => $notification_id)
        );

        $response = ['status' => 'ind_success'];

    } else if ($_POST['ntfy_action'] == 'ind-delete') { //delete specific id

        $notification_id = $_POST['notification_id'];

        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = '" . $notification_id . "'"));

        $response = ['status' => 'ind_deleted'];
    }

    wp_send_json($response);
}






// add_action('wp_ajax_so_retrieve_new_messages', 'so_retrieve_new_messages');
function so_retrieve_new_messages(){

    $limit = (int)$_POST['limit'];
    $last_ntfy_id = (int)$_POST['last_ntfy_id'];

    global $wpdb;
    $table_name = $wpdb->prefix . 'so_notifications';

    $curr_user_id = get_current_user_id();

    // SELECT * FROM $table_name WHERE id > $last_ntfy_id AND receiver_id = $curr_user_id AND notif_type = 'new-message' AND seen = 0 ORDER BY sent_at DESC LIMIT $limit
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE receiver_id = $curr_user_id AND notif_type = 'new-message' AND seen = 0 ORDER BY sent_at DESC LIMIT $limit"
    );

    $rows = $wpdb->get_results($query);

    if ($rows) {
        foreach ($rows as $row) {
            $sender = $row->sender_id;

            $user_data = get_userdata($sender);

            if ($user_data === false) {
                continue;
            }

            $notification_id = $row->id;
            $sender_data = spitout_get_seller_information($sender);
            $sender_display_name = $sender_data['seller_display_name'];
            $sender_profile_img_url = $sender_data['seller_profile_img'];
            $error = 0;

            if ($error === 0) {
                // Populate the array with notification data
                $notifications_array[] = array(
                    'id' => $notification_id,
                    'sender_id' => $sender,
                    'sender_display_name' => $sender_display_name,
                    'sender_profile_img_url' => $sender_profile_img_url
                );
            }
        }
    }else{
        wp_send_json_success('no-new-msg');
    }

    wp_send_json_success($notifications_array);

}


add_action('wp_ajax_so_mark_ntfy_as_seen', 'so_mark_ntfy_as_seen');
function so_mark_ntfy_as_seen(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'so_notifications';

    $wpdb->query($wpdb->prepare("UPDATE $table_name SET seen='1' WHERE id = '" . $_POST['ntfy_id'] . "'"));
}