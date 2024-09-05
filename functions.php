<?php

/**
 * spitout functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package spitout
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function spitout_setup()
{
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on spitout, use a find and replace
     * to change 'spitout' to the name of your theme in all the template files.
     */
    load_theme_textdomain('spitout', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        array(
            'main-menu' => esc_html__('Primary', 'spitout'),
            'loggedin-menu-buyer' => esc_html__('Logged In Menu Buyer', 'spitout'),
            'loggedin-menu-seller' => esc_html__('Logged In Menu Seller', 'spitout'),
            'footer-menu-1' => esc_html__('Footer Menu 1', 'spitout'),
            'footer-menu-2' => esc_html__('Footer Menu 2', 'spitout'),
            'footer-menu-3' => esc_html__('Footer Menu 3', 'spitout'),
            'footer-menu-4' => esc_html__('Footer Menu 4', 'spitout'),
            'footer-social-menu' => esc_html__('Social Menu', 'spitout'),

        )
    );

    function spitout_add_menu_item_class($classes, $item, $args, $depth)
    {
        // Add custom class to <li> element
        $classes[] = 'nav-item';

        return $classes;
    }
    add_filter('nav_menu_css_class', 'spitout_add_menu_item_class', 10, 4);

    function spitout_add_menu_link_class($atts, $item, $args)
    {
        // Add custom class to <a> element
        $atts['class'] = 'nav-link';

        return $atts;
    }
    add_filter('nav_menu_link_attributes', 'spitout_add_menu_link_class', 10, 3);

    add_filter('nav_menu_css_class', 'spitout_special_nav_class', 10, 2);

    function spitout_special_nav_class($classes, $item)
    {
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'active ';
        }
        return $classes;
    }

    add_theme_support('disable-layout-styles');
    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'spitout_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}
add_action('after_setup_theme', 'spitout_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function spitout_content_width()
{
    $GLOBALS['content_width'] = apply_filters('spitout_content_width', 640);
}
add_action('after_setup_theme', 'spitout_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function spitout_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'spitout'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'spitout'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}
add_action('widgets_init', 'spitout_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function spitout_scripts()
{
    global $wp_query;
    wp_enqueue_style('spitout-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('spitout-style', 'rtl', 'replace');

    /*  start Enquee splitout css  */

    wp_enqueue_style('spitout-bootstrap.min', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), _S_VERSION);
    wp_enqueue_style('spitout-bootstrap-icons.min', get_template_directory_uri() . '/assets/css/bootstrap-icons.min.css', array(), _S_VERSION);
    wp_enqueue_style('spitout-select2.min', get_template_directory_uri() . '/assets/css/select2.min.css', array(), _S_VERSION);
    wp_enqueue_style('new-spitout-style-css', get_template_directory_uri() . '/assets/css/spit-style.css', array(), _S_VERSION);
    wp_enqueue_style('new-spitout-style-mobile', get_template_directory_uri() . '/assets/css/spit-responsive.css', array(), _S_VERSION);
    // wp_enqueue_style('new-spitout-style-sb', get_template_directory_uri() . '/assets/css/ns.css', array(), _S_VERSION);
    // wp_enqueue_style('new-spitout-style-ns', get_template_directory_uri() . '/assets/css/sb.css', array(), _S_VERSION);
    wp_enqueue_style('new-spitout-style-aa', get_template_directory_uri() . '/assets/css/wallet-style.css', array(), _S_VERSION);
    wp_enqueue_style('spitout-datepicker-jquery-css', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', array(), _S_VERSION);
    // wp_enqueue_style('spitout-datepicker-jquery-css', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', array(), _S_VERSION);
    // wp_enqueue_style('spitout-chart-css', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css', array(), _S_VERSION);

    wp_enqueue_style('spitout-cropper-css', 'https://unpkg.com/cropperjs/dist/cropper.min.css', array(), _S_VERSION);

    /*  End Enquee splitout css  */

    /*  start Enquee splitout js  */

    // wp_enqueue_script( 'jquery-ui-core', false, array('jquery'));
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/custom.js', array(), time(), true);
    wp_enqueue_script('jquery-ui-js', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js', array(), _S_VERSION, true);
    wp_enqueue_script('multistep-form-js', get_template_directory_uri() . '/assets/js/multistep-form.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('spit-bootstrap.min', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('spit-select2.min', get_template_directory_uri() . '/assets/js/select2.min.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.js', array('jquery', 'jquery-ui-js'), _S_VERSION, true);
    // wp_enqueue_script('spit-cropper', 'https://unpkg.com/cropperjs/dist/cropper.min.js', array(), _S_VERSION, true);
    wp_enqueue_script('spit-script', get_template_directory_uri() . '/assets/js/spit-script.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('spit-chart', 'https://cdn.jsdelivr.net/npm/chart.js', array(), _S_VERSION, true);
    // wp_enqueue_script('spit-chart', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js', array(), _S_VERSION, true);

    wp_enqueue_script('spit-cropper', 'https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('spit-script-ajax', get_template_directory_uri() . '/assets/js/spit-ajax.js', array('jquery'), time(), true);
    if (is_page('seller') || is_post_type_archive('seller')) {
        wp_enqueue_script('seller-page-script-ajax', get_template_directory_uri() . '/assets/js/seller-spit-ajax.js', array('jquery'), time(), true);
        wp_localize_script('seller-page-script-ajax', 'spit_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /*  End Enquee splitout js  */
    /*  localize ajax so than we can use "admin_url.ajax_url" on ajax-url  */
    wp_localize_script('spit-script-ajax', 'spit_ajax', array('ajax_url' => admin_url('admin-ajax.php')));

    /* feed load more js */

    wp_register_script('spitout_loadmore', get_stylesheet_directory_uri() . '/assets/js/spit-load-more.js', array('jquery'));

    $loadmore_params = array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    );
    wp_localize_script('spitout_loadmore', 'spitout_loadmore_params', $loadmore_params);
    if (is_page('Feeds') || is_author()) // also tried slug, page id and wp_reset_query(); bot not worked
    {
        wp_enqueue_script('spitout_loadmore');
        wp_enqueue_script('tinymce');
        wp_enqueue_script('wp-tinymce');
    }
}
add_action('wp_enqueue_scripts', 'spitout_scripts');

// jquery ui enqueue
function load_jquery_ui()
{
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-slider');
}

add_action('wp_enqueue_scripts', 'load_jquery_ui');

// enqueue for slider in jquery ui for mobile touch
function enqueue_jquery_ui_touch_punch()
{
    // Register the script
    wp_register_script('jquery-ui-touch-punch', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', array('jquery', 'jquery-ui-slider'), '0.2.3', true);

    // Enqueue the script
    wp_enqueue_script('jquery-ui-touch-punch');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery_ui_touch_punch');


/**
 * Enqueue admin scripts and styles.
 */
function spitout_admin_scripts()
{

    /*  start Enquee splitout css  */
    wp_enqueue_style('spitout-select2.min', get_template_directory_uri() . '/assets/css/select2.min.css', array(), _S_VERSION);
    wp_enqueue_style('spitout-admin-style', get_template_directory_uri() . '/assets/css/spitout-admin.css', array(), _S_VERSION);
    /*  End Enquee splitout css  */

    /*  start Enquee splitout js  */
    wp_enqueue_media();
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/js/custom.js', array(), _S_VERSION, true);
    wp_enqueue_script('icon_uploader_js', get_template_directory_uri() . '/assets/js/spit-menu-icon-uploader.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('spit-select2.min', get_template_directory_uri() . '/assets/js/select2.min.js', array('jquery'), _S_VERSION, true);
    /*  End Enquee splitout js  */
}
add_action('admin_enqueue_scripts', 'spitout_admin_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Home page custom-fields
 */

require get_template_directory() . '/inc/homepage-custom-fields.php';
/**
 * Theme options include file
 */

require get_template_directory() . '/inc/options-page.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
}


//=========by vileroze starts=========

require_once get_template_directory() . '/inc/categories-functions.php';

require get_template_directory() . '/inc/order-product-functions.php';

require_once get_template_directory() . '/inc/myspitout-functions.php';


//=========by vileroze ends=========




//======tanush starts========

require get_template_directory() . '/inc/notifications.php';

require get_template_directory() . '/inc/spitout-idenfy-functions.php';
require get_template_directory() . '/inc/spitout-email-templates-functions.php';

/**
 * Function to list users according to popular sellers
 * @param int $total_shown
 * @return array
 **/
function spitout_get_popular_sellers($total_shown = -1)
{
    global $wpdb;

    // Query to get all sellers
    $sellers = $wpdb->get_results("SELECT ID, display_name FROM {$wpdb->prefix}users");

    $sales_by_sellers = array();

    if ($sellers) {
        foreach ($sellers as $seller) {
            $seller_id = $seller->ID;

            // Query to get all posts (products) by the current seller
            $args = array(
                'author' => $seller_id,
                'post_type' => 'product',
                'posts_per_page' => -1,
            );

            $posts_by_seller = get_posts($args);

            if ($posts_by_seller) {
                $total_sales = 0; // Initialize total sales for the seller

                foreach ($posts_by_seller as $post) {
                    //Calculate total sales for the product
                    $total_sales += intval(get_post_meta($post->ID, 'total_sales', true));
                }

                $sales_by_sellers[$seller_id] = $total_sales;
            }
        }

        // Sort the array in descending order of total sales
        arsort($sales_by_sellers);

        // Limit the number of top sellers shown
        if ($total_shown > 0) {
            $sales_by_sellers = array_slice($sales_by_sellers, 0, $total_shown, true);
        }
    }

    return $sales_by_sellers;
}

/**
 * Get total sales of a specified seller
 * @param int $seller_id
 * @return array<int>|string
 **/
function get_seller_totals($seller_id)
{
    $total_sales = 0;
    $total_earnings = 0;

    $seller_sales_info = [];

    $seller_orders = wc_get_orders([
        'limit' => -1,
        'meta_key' => 'seller_id',
        'meta_value' => $seller_id,
        'meta_compare' => 'LIKE',
        'status' => array('completed'),
    ]);

    foreach ($seller_orders as $order) {
        if ($order) {
            $total_sales++;
            // echo "1--";
            $total_earnings +=  dynamic_currency_for_buyer_seller_order_pages($order->get_id(), false);
        }
    }

    // echo '|||'.$total_sales.'|||';

    if ($total_earnings > 0) {
        $seller_sales_info['total_earnings'] = $total_earnings;
        $seller_sales_info['total_sales'] = $total_sales;
        $seller_sales_info['average_sales'] = $total_earnings / $total_sales;
    } else {
        $seller_sales_info['total_earnings'] = 0;
        $seller_sales_info['total_sales'] = 0;
        $seller_sales_info['average_sales'] = 0;
    }

    return $seller_sales_info;
}

/**
 * Summary of spitout_get_newest_users
 * @param int $total_shown
 * @return array
 **/
function spitout_get_newest_users($total_shown = -1)
{
    $users = get_users(
        array(
            'role' => 'seller',
        )
    );
    $users_with_date = array();

    foreach ($users as $user) {

        $user_id = $user->ID;
        $user_joined = get_user_meta($user_id, 'so_join_date', true);

        $timestamp = strtotime($user_joined);
        $formatted_date = date("Y-m-d", $timestamp);
        $users_with_date[$user_id] = $formatted_date;
    }
    // Sort the array based on join dates in descending order
    arsort($users_with_date);

    // Limit the number of top sellers shown
    if ($total_shown > 0) {
        $users_with_date = array_slice($users_with_date, 0, $total_shown, true);
    }

    return $users_with_date;
}


/* the create post shortcode, and the feeds shortcodes are here */
require get_template_directory() . '/inc/feed-shortcodes.php';

/* all the ajax actions are listed here  */
require get_template_directory() . '/inc/ajax-actions.php';

//======Sam dai starts========

function spitout_update_user_status_on_login($user_login, $user)
{
    update_user_meta($user->ID, 'user_status', 'logged_in');
}
add_action('wp_login', 'spitout_update_user_status_on_login', 10, 2);


function spit_out_update_user_status_on_logout()
{
    global $current_user;

    update_user_meta($current_user->ID, 'user_status', 'logged_out');
    update_user_meta($current_user->ID, 'user_logged_out_time', current_time('mysql'));
}
add_action('clear_auth_cookie', 'spit_out_update_user_status_on_logout', 20);



if (function_exists('add_theme_support')) {
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(50, 50, true); // 50 pixels wide by 50 pixels tall, crop mode
}

function split_out_get_logged_in_user_ids()
{
    $args = array(
        'meta_query' => array(
            array(
                'key' => 'user_status',
                'value' => 'logged_in',
                'compare' => '='
            )
        )
    );

    $user_query = new WP_User_Query($args);
    $logged_out_users = $user_query->get_results();

    $logged_out_user_ids = array();
    foreach ($logged_out_users as $user) {
        $logged_out_user_ids[] = $user->ID;
    }

    return $logged_out_user_ids;
}


/* retrive seller data  */

/**
 * Get Seller Information
 *
 * Retrieves information about a seller based on the provided user ID.
 *
 * @param int $seller_id The ID of the seller.
 * @return array An array containing seller information.
 */

/* to call this function pass seller id 
 eg
    $get_author_meta_data = spitout_get_seller_information($seller_id);
    $image = $get_author_meta_data['seller_profile_img']; */
function spitout_get_seller_information($seller_id)
{
    if ($seller_id) {
        $seller_img = get_user_meta($seller_id, "so_profile_img", true);
        $seller_data = get_userdata($seller_id);
        $seller_user_name = $seller_data->user_login;

        // $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';F
        $seller_url = get_author_posts_url($seller_id);
        $seller_img_url = resize_and_compress_image($seller_img, 150, 150, 70);
        if (!$seller_img_url) {
            $seller_img_url = get_template_directory_uri() . '/assets/img/user.png';
        }
        // $seller_img_url = wp_get_attachment_url($seller_img, 'thumbnail');
        $seller_location = get_user_meta($seller_id, "so_location", true);
        $seller_online_status = get_user_meta($seller_id, "user_status", true);
        $seller_final_location = $seller_location ? $seller_location : 'N/A';
        // $seller_final_profile_img = $seller_img_url ? $seller_img_url : $profile_avatar;

        $seller_display_name = $seller_data->display_name;

        if ($seller_online_status == 'logged_in') {
            $active_status = 'online';
        } else {
            $active_status = 'offline';
        }

        $seller_information = array(
            'seller_display_name' => $seller_display_name,
            'seller_user_name' => $seller_user_name,
            'seller_location' => $seller_final_location,
            'seller_profile_img' => esc_url($seller_img_url),
            'seller_url' => $seller_url,
            'seller_online' => $active_status
        );

        return $seller_information;
    }
}

/**
 * A function that filters existing users based on their user IDs.
 *
 * @param array $userIds The array of user IDs to filter.
 * @return array The array of filtered user IDs.
 */
if (!function_exists('spitoutFilterExistingUsers')) {
    function spitoutFilterExistingUsers($userIds)
    {
        return array_filter($userIds, function ($userId) {
            return get_userdata($userId);
        });
    }
}

/**
 * Format the time difference between the input date and the current date in a human-readable way.
 *
 * @param string $comment_date The input date to calculate the time difference from
 * @return string The formatted time difference with units (e.g., 1m for 1 minute)
 */
if (!function_exists('formatTimeDifference')) {
    function formatTimeDifference($comment_date)
    {
        // Convert the input date and current date to Unix timestamps
        $comment_timestamp = strtotime($comment_date);
        $current_timestamp = current_time('timestamp');

        // Calculate the time difference in seconds
        $time_diff_seconds = $current_timestamp - $comment_timestamp;

        // Define variables to store the formatted time and units
        $formatted_time = '';
        $unit = '';

        if ($time_diff_seconds < 60) {
            // If less than 60 seconds, use seconds
            $formatted_time = $time_diff_seconds;
            $unit = 's';
        } elseif ($time_diff_seconds < 3600) {
            // If less than 1 hour, use minutes
            $formatted_time = floor($time_diff_seconds / 60);
            $unit = 'm';
        } elseif ($time_diff_seconds < 86400) {
            // If less than 1 day, use hours
            $formatted_time = floor($time_diff_seconds / 3600);
            $unit = 'h';
        } elseif ($time_diff_seconds < 2592000) {
            // If less than 30 days, use days
            $formatted_time = floor($time_diff_seconds / 86400);
            $unit = 'd';
        } elseif ($time_diff_seconds < 31536000) {
            // If less than 1 year, use months
            $formatted_time = floor($time_diff_seconds / 2592000);
            $unit = 'mo';
        } else {
            // Use years
            $formatted_time = floor($time_diff_seconds / 31536000);
            $unit = 'y';
        }

        // Combine the formatted time and unit
        $output = $formatted_time . $unit;

        return $output;
    }
}

/* retrive buyer data  */

/**
 * Get Buyer Information
 *
 * Retrieves information about a seller based on the provided user ID.
 *
 * @param int $buyer_id The ID of the seller.
 * @return array An array containing seller information.
 */

/* to call this function pass buyer id 
 eg
    $get_author_meta_data = spitout_get_buyer_information($buyer_id);
    $image = $get_author_meta_data['buyer_profile_img']; */
function spitout_get_buyer_information($buyer_id)
{
    if ($buyer_id) {
        $buyer_img = get_user_meta($buyer_id, "so_profile_img", true);
        $buyer_data = get_userdata($buyer_id);
        $profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
        $buyer_url = get_author_posts_url($buyer_id);
        // $buyer_img_url = wp_get_attachment_url($buyer_img, 'thumbnail');
        $buyer_location = get_user_meta($buyer_id, "so_location", true);
        $buyer_online_status = get_user_meta($buyer_id, "user_status", true);
        $buyer_final_location = $buyer_location ? $buyer_location : 'N/A';
        // $buyer_final_profile_img = $buyer_img_url ? $buyer_img_url : $profile_avatar;
        $buyer_final_profile_img = $buyer_img == false ? $profile_avatar : (wp_get_attachment_image_src((int) $buyer_img, 'post-thumbnail'))[0];
        $buyer_display_name = $buyer_data->display_name;
        $buyer_user_name = $buyer_data->user_login;

        if ($buyer_online_status == 'logged_in') {
            $active_status = 'online';
        } else {
            $active_status = 'offline';
        }

        $buyer_information = array(
            'buyer_display_name' => $buyer_display_name,
            'buyer_user_name' => $buyer_user_name,
            'buyer_location' => $buyer_final_location,
            'buyer_profile_img' => $buyer_final_profile_img,
            'buyer_url' => $buyer_url,
            'buyer_online' => $active_status
        );

        return $buyer_information;
    }
}

add_filter('woocommerce_billing_fields', 'remove_company_name_from_checkout', 10, 1);

function remove_company_name_from_checkout($fields)
{

    unset($fields['billing_company']);

    return $fields;
}


function spitout_footer_menus($menu_name)
{

    if (($locations = get_nav_menu_locations()) && isset($locations[$menu_name])) {
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        $menu_items = wp_get_nav_menu_items($menu->term_id);

        return $menu_items;
    }
}

function spitout_footer_menu_list($footer_menu_name)
{
    $menu_location_slug = $footer_menu_name;

    $menu_locations = get_nav_menu_locations();
    if (isset($menu_locations[$menu_location_slug])) {
        $menu_id = $menu_locations[$menu_location_slug];
        $menu_object = wp_get_nav_menu_object($menu_id);
        if ($menu_object) {
            $menu_name = $menu_object->name;
            echo '<h5 class="fw-bold footer-title">' . $menu_name . '</h5>';
            echo '<div class="dropdown-list">';

            $menu_items_footer2 = spitout_footer_menus($footer_menu_name);
            foreach ($menu_items_footer2 as $menu_item) {
                $id = $menu_item->ID;
                $title = $menu_item->title;
                $url = $menu_item->url;
                echo '<li> <a class="' . $title . '" href="' . $url . '">' . $title . '</a></li>';
            }
        }
    }
}
/* spit out upload menu icon function */

function spit_out_page_custom_color_meta_box()
{
    add_meta_box(
        'custom_color_meta_box',
        'Choose Background Color',
        'spit_out_render_page_color_meta_box',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'spit_out_page_custom_color_meta_box');

function spit_out_render_page_color_meta_box($post)
{
    $spit_page_selected_color = get_post_meta($post->ID, 'spit_page_selected_color', true);
?>
    <label>
        <input type="radio" name="spit_page_selected_color" value="#FFFFFF" <?php checked($spit_page_selected_color, '#FFFFFF'); ?>>
        White
    </label><br>
    <label>
        <input type="radio" name="spit_page_selected_color" value="#F4F4F4" <?php checked($spit_page_selected_color, '#F4F4F4'); ?>>
        Grey
    </label>
<?php
}


function spit_out_save_custom_page_color_meta_box($post_id)
{
    if (array_key_exists('spit_page_selected_color', $_POST)) {
        update_post_meta(
            $post_id,
            'spit_page_selected_color',
            sanitize_text_field($_POST['spit_page_selected_color'])
        );
    }
}
add_action('save_post', 'spit_out_save_custom_page_color_meta_box');

function spit_out_redirect_shop_product_page()
{
    // Get the current URL
    $current_url = home_url($_SERVER['REQUEST_URI']);
    $to_redirect_page = home_url('/seller/');

    // Check if the current URL matches the WooCommerce shop or single product URLs
    //if (strpos($current_url, '/shop/') !== false || strpos($current_url, '/product/') !== false) { 
    if (strpos($current_url, '/shop/') !== false) {
        // Redirect to another page
        wp_redirect($to_redirect_page);
        exit;
    }
    if (!is_user_logged_in() && is_page('my-spitout')) {
        wp_redirect(site_url('/login/'));
        exit();
    }
}
add_action('template_redirect', 'spit_out_redirect_shop_product_page');

/* set woocommerce order to on-hold */
add_action('woocommerce_thankyou', 'spitout_set_wc_product_to_on_hold');
function spitout_set_wc_product_to_on_hold($order_id)
{
    if (!$order_id)
        return;

    $order = wc_get_order($order_id);

    // If order is "on-hold" update status to "processing"
    if ($order->has_status('processing')) {
        $order->update_status('on-hold');
    }
}

function so_get_buyer_total_purchase($buyer_id, $seller_id)
{

    $customer_orders = wc_get_orders(
        array(
            'limit' => -1,
            'customer_id' => (int) $buyer_id,
            'meta_key' => 'seller_id',
            'meta_value' => $seller_id,
            'meta_compare' => 'LIKE',
            'status' => array('completed'),
        )
    );

    $total = 0;
    $count = 0;
    foreach ($customer_orders as $order) {
        if ($order) {
            $total += dynamic_currency_for_buyer_seller_order_pages($order->get_id(), false);
            $count++;
        }
    }

    return [$total, $count];
}

// shortcode for reset psw=================
add_shortcode('so_reset_password', 'so_reset_password');
function so_reset_password()
{
    ob_start();
?>
    <form>
        <div class="form-card so-feed-new-container so-reset-pw">
            <div class="row so-reset-password">
                <div class="col-lg-12">
                    <div class="login-title ">
                        <h4 class="fs-title">Reset password</h4>
                        <p class="mt-2">Enter the email address associated with your account and will send a link to reset
                            your
                            password.</p>
                    </div>
                    <label for="email">Email</label> <br>
                    <input type="email" name="email" placeholder="Email" /> <br>
                    <div class="col-lg-12">
                        <input type="button" name="next" class="next-step-register-btn mt-4 so-signin-btn" id="so-login-btn" value="Continue" />
                    </div>
                    <div class="col-lg-12 login-redirect-to-register pt-2">
                        <h6>Don't have an account yet. <span><a href="#">Sign up </a></span>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
// end of reset psw shortcode

// shortcode for age verification-======================================================================


add_shortcode('so_age_verification', 'so_age_verification');
function so_age_verification()
{
    ob_start();
?>

    <div class="container so-age-verification-popup mt-5">
        <h5 class="so-age-verification-popup-agelimit">+21</h5>
        <h4 class="mb-2 mt-0">Age Verification</h4>
        <p>This Website requires you to be <span>21</span> years or older to enter. Please enter your Date of Birth in
            the fields
            below in order to continue.</p>
        <div class="so-datepicker-container mx-auto">
            <div class="so-datepicker-dropdown">
                <label for="month"></label>
                <select id="month">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="03">April</option>
                    <option value="03">May</option>
                    <option value="03">June</option>
                    <option value="03">July</option>
                    <option value="03">August</option>
                    <option value="03">September</option>
                    <option value="03">October</option>
                    <option value="03">November</option>
                    <option value="03">December</option>
                </select>
            </div>
            <div class="so-datepicker-dropdown">
                <label for="day"></label>
                <select id="day">
                    <!-- JavaScript to update the days dynamically based on the selected month -->
                </select>
            </div>
            <div class="so-datepicker-dropdown">
                <label for="year"></label>
                <select id="year">

                    <!-- JavaScript to generate a range of years dynamically -->
                </select>
            </div>
        </div>
        <div class="so-age-verify-popup-submit mt-3">
            <button type="submit">
                Submit
                <i class="bi bi-arrow-right-circle-fill"></i></button>

        </div>
    </div>
    <?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_action('wp_ajax_cpmm_send_msg_request_from_seller_profile', 'cpmm_send_msg_request_from_seller_profile');
function cpmm_send_msg_request_from_seller_profile()
{
    $user_id = (int) $_POST['user_id'];
    $curr_user_id = get_current_user_id();

    $curr_req_ids = get_user_meta($user_id, 'cpmm_message_requests', true);

    if (is_array($curr_req_ids)) {
        if (!in_array($curr_user_id, $curr_req_ids)) {
            array_push($curr_req_ids, $curr_user_id);
            update_user_meta($user_id, 'cpmm_message_requests', $curr_req_ids);
        }
    } else {
        update_user_meta($user_id, 'cpmm_message_requests', [$curr_user_id]);
    }
}

function so_loginPage_styling()
{
    if (isset($_GET['action']) && ($_GET['action'] == 'rp' || $_GET['action'] == 'resetpass' || $_GET['action'] == 'lostpassword' || $_GET['checkemail'] == 'confirm')) { ?>
        <style type="text/css">
            #login h1 a,
            .login h1 a {
                background-image: url(<?php echo get_stylesheet_directory_uri() . '/assets/img/logo-mob.png'; ?>);
                height: 65px;
                width: 320px;
                background-repeat: no-repeat;
                padding-bottom: 30px;
            }

            #login .message.reset-pass,
            #login .message {
                border-left: 4px solid #ea1e79;
                font-weight: 500;
            }

            #login #resetpassform .reset-pass-submit .button,
            #login #resetpassform .reset-pass-submit .button-secondary {
                color: #ea1e79;
                border-color: #ea1e79;
                background: #fff;
            }

            #login #resetpassform {
                border: transparent;
            }

            #login #resetpassform .reset-pass-submit .button-primary,
            #lostpasswordform .submit input {
                background: #ea1e79;
                border-color: #ea1e79;
                color: #fff;
                text-decoration: none;
                text-shadow: none;
            }

            #login .privacy-policy-page-link a {
                color: #ea1e79;
            }

            #login #resetpassform .description {
                font-size: 14px;
                font-weight: 500;
            }

            #login p#nav {
                padding-left: 0;
            }

            #login p#nav a {
                font-size: 16px;
                font-weight: 600;
                background: #ea1e79;
                color: #fff;
                padding: 10px 24px;
                border-radius: 20px;
            }

            #login p#backtoblog {
                background: #fff;
                width: fit-content;
                padding: 9px 12px;
                border-radius: 20px;
                font-weight: 500;
            }

            #login p#nav a:hover,
            #login p#backtoblog a:hover {
                color: #ea1e79;
            }
        </style>
    <?php } else { ?>
        <style type="text/css">
            #login h1 a,
            .login h1 a {
                background-image: url(<?php echo get_stylesheet_directory_uri() . '/assets/img/logo-mob.png'; ?>);
                height: 65px;
                width: 320px;
                background-repeat: no-repeat;
                padding-bottom: 30px;
            }

            #login .message.reset-pass,
            #login .message {
                border-left: 4px solid #ea1e79;
                font-weight: 500;
            }

            #login #resetpassform .reset-pass-submit .button,
            #login #resetpassform .reset-pass-submit .button-secondary {
                color: #ea1e79;
                border-color: #ea1e79;
                background: #fff;
            }

            #login #resetpassform {
                border: transparent;
            }

            #login #resetpassform .reset-pass-submit .button-primary,
            #lostpasswordform .submit input {
                background: #ea1e79;
                border-color: #ea1e79;
                color: #fff;
                text-decoration: none;
                text-shadow: none;
            }

            #login .privacy-policy-page-link a {
                color: #ea1e79;
            }

            #login #resetpassform .description {
                font-size: 14px;
                font-weight: 500;
            }

            #login p#nav {
                padding-left: 0;
            }

            #login p#nav a {
                font-size: 16px;
                font-weight: 600;
                background: #ea1e79;
                color: #fff;
                padding: 10px 24px;
                border-radius: 20px;
            }

            #login p#backtoblog {
                background: #fff;
                width: fit-content;
                padding: 9px 12px;
                border-radius: 20px;
                font-weight: 500;
            }

            #login p#nav a:hover,
            #login p#backtoblog a:hover {
                color: #ea1e79;
            }
        </style>

    <?php
    }
}
add_action('login_enqueue_scripts', 'so_loginPage_styling');

add_action('woocommerce_thankyou', 'tm_add_order_metadata');
function tm_add_order_metadata($order_id)
{
    $seller_ids = [];
    $order_obj = new WC_Order($order_id);
    $order_items = $order_obj->get_items();
    $customer_id = $order_obj->get_customer_id();
    foreach ($order_items as $item) {
        $curr_product_id = $item->get_product_id();
        if (!in_array($curr_product_id, $seller_ids)) {
            array_push($seller_ids, get_post_field('post_author', $curr_product_id));
        }
    }

    remove_action('woocommerce_new_order', 'tm_add_order_metadata', 10);

    if (count($seller_ids) == 1) {
        $order_obj->update_meta_data('seller_id', $seller_ids[0]);
    } else if (count($seller_ids) > 1) {
        $order_obj->update_meta_data('seller_id', serialize($seller_ids));
    }
    $order_obj->save();

    ?>
    <script>
        // Define the button attributes
        var buttonAttributes = {
            'class': 'so-notify',
            'data-sender': <?php echo $customer_id; ?>,
            'data-receiver': <?php echo $seller_ids[0]; ?>,
            'data-notify': 'new-orders',
            'data-post-id': <?php echo $order_id; ?>
        };
        var $buttonn = jQuery('<button>').attr(buttonAttributes);
        $buttonn.hide().appendTo('body');
        setTimeout(function() {
            if ($buttonn.length) {
                $buttonn.click();
                $buttonn.remove();
            }
        }, 1000);
    </script>
    <?php

    add_action('woocommerce_new_order', 'tm_add_order_metadata', 10, 2);
}

add_filter('woocommerce_add_error', 'custom_cart_error_message', 10, 2);

function custom_cart_error_message($error)
{
    // Check if the error message matches the default message
    if ($error == 'Sorry, this product cannot be purchased.') {
        // Replace the default error message with your custom message
        $error = 'Attention: You are not allowed to add product from different sellers in same cart';
    }

    return $error;
}

/**
 * The function `spitout_get_currency_exchange_rate` retrieves and formats the current currency
 * exchange rate using the (WOOMULTI_CURRENCY_F_Data-> class for free plugin) (WOOMULTI_CURRENCY_Data-> class for pro plugin) class.
 * 
 * @return The function `spitout_get_currency_exchange_rate` returns the current exchange rate of the
 * selected currency in a readable format with 10 decimal places.
 */
function spitout_get_currency_exchange_rate()
{

    if (class_exists('WOOMULTI_CURRENCY_Data')) {
        $multiCurrencySettings = WOOMULTI_CURRENCY_Data::get_ins();
        $wmcCurrencies = $multiCurrencySettings->get_list_currencies();
        $currentCurrency = $multiCurrencySettings->get_current_currency();
        $currentCurrencyRate = floatval($wmcCurrencies[$currentCurrency]['rate']);
        return $currentCurrencyRate;
    }
}

/**
 * The function `spitout_get_formatted_price` calculates and returns a formatted price based on a given
 * exchange rate.
 * 
 * @param price_to_be_formatted The `price_to_be_formatted` parameter is the price value that you want
 * to format. This function appears to be calculating the final price after applying a currency
 * exchange rate and then formatting it using the `wc_price` function.
 * 
 * @return The function `spitout_get_formatted_price` returns a formatted price after converting the
 * input price to the currency exchange rate obtained from `spitout_get_currency_exchange_rate`
 * function.
 */
function spitout_get_formatted_price($price_to_be_formatted)
{
    $exchange_rate = spitout_get_currency_exchange_rate();
    $final_exchange_price = $price_to_be_formatted * $exchange_rate;
    $formatted_price = wc_price($final_exchange_price);
    return $formatted_price;
}


function spitout_get_formatted_currency_and_price($price)
{
    $get_exchange_rate = spitout_get_currency_exchange_rate();
    $final_exchange_price = $price * $get_exchange_rate;
    return get_woocommerce_currency_symbol() . $final_exchange_price;
}


function spitout_get_currency_exchange_btc_usd_rate()
{

    if (class_exists('WOOMULTI_CURRENCY_Data')) {
        $multiCurrencySettings = WOOMULTI_CURRENCY_Data::get_ins();
        $wmcCurrencies = $multiCurrencySettings->get_list_currencies();
        $currentCurrency = $multiCurrencySettings->get_current_currency();
        $currentCurrencyRate = floatval($wmcCurrencies[$currentCurrency]['rate']);
        $readable_format = number_format($currentCurrencyRate, 10);
        $get_exchange_rate = $wmcCurrencies['USD']['rate'];
        return $get_exchange_rate;
    }
}



function get_order_id_by_transaction_id($transaction_id)
{
    // Get all order id with the specified meta key
    global $wpdb;
    $get_order_id = $wpdb->get_results("
    SELECT post_id 
    FROM $wpdb->postmeta 
    WHERE meta_key = '_wallet_payment_transaction_id' 
    AND meta_value = $transaction_id
", ARRAY_A);
    if (!empty($get_order_id)) {
        // Return the first order ID found with the specified meta value
        return $get_order_id[0]['post_id'];
    } else {
        // Return false if no order is found with the specified meta value
        return false;
    }
}




// Define a function to modify the data
function spitout_wallet_transaction_data($data, $transaction)
{
    $get_transacion_id = $transaction->transaction_id;
    $get_transaction_details = $transaction->details;
    $get_transaction_type = $transaction->type;
    preg_match(
        '/\d+/',
        $get_transaction_details,
        $matches
    );
    if (!empty($matches)) {
        $order_number = $matches[0];
    } else {
        $order_number = 0;
    }

    $currency = $_COOKIE['wmc_current_currency'] ?? 'BTC';
    $get_currency_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();

    $get_order_id_of_transaction = get_order_id_by_transaction_id($get_transacion_id);
    $get_wallet_transation_charge = get_wallet_transaction_meta($get_transacion_id, '_wc_wallet_purchase_gateway_charge', true);
    $get_wallet_withdrawal_transation = get_wallet_transaction_meta($get_transacion_id, '_withdrawal_request_id', true);
    $get_wallet_withdrawal_amount_transation = get_post_meta($get_wallet_withdrawal_transation, '_wallet_withdrawal_amount', true);
    $get_wallet_withdrawal_charge_amount_transation = get_post_meta($get_wallet_withdrawal_transation, '_wallet_withdrawal_transaction_charge', true);

    /* $order = wc_get_order($order_number);

    if ($order) {
        if ($currency == 'BTC') {
            $order_total = $order->get_formatted_order_total();
        } else {
            $convert_btc_usd = $order->get_total() * $get_currency_exchange_rate;
            $order_total = '$' . number_format($convert_btc_usd, 2, '.', ',');
        }
        // etc.
    } else {
        if ($currency == 'BTC') {
            $charge_able_amount = $get_wallet_withdrawal_charge_amount_transation / $get_currency_exchange_rate;
            $order_total = '<p class="withdraw_order_amt m-0">฿' . number_format($charge_able_amount, 5, '.', ',') . '</p><span> (Withdraw Charge)</span>';
        } else {

            $convert_btc_usd = $get_wallet_withdrawal_charge_amount_transation;
            $order_total = '<p class="withdraw_order_amt m-0">$' . number_format($convert_btc_usd, 2, '.', ',') . ' </p><span>(Withdraw Charge)</span>';
        }
    } */

    $get_user_balance =  wc_price(apply_filters('woo_wallet_amount', $transaction->balance, $transaction->currency, $transaction->user_id), woo_wallet_wc_price_args($transaction->user_id));
    // Additional modifications can be made here
    $data['total_amount'] = $get_user_balance;
    return $data;
}

// Hook the function to the filter
add_filter('woo_wallet_transactons_datatable_row_data', 'spitout_wallet_transaction_data', 10, 2);

// Define a function to modify the columns
function spitout_wallet_transaction_columns($columns)
{
    // Add your new array
    $new_column = array(
        'data' => 'total_amount',
        'title' => __('Balance', 'woo-wallet'),
        'orderable' => false, // Example value, modify as needed
    );

    // Add the new column array to the existing columns array
    array_splice($columns, 3, 0, array($new_column));
    return $columns;
}

// Hook the function to the filter
add_filter('woo_wallet_transactons_datatable_columns', 'spitout_wallet_transaction_columns', 10, 2);

add_action('wp_ajax_spitout_hidden_users', 'spitout_hidden_users');
function spitout_hidden_users()
{
    $user_id = (int) $_POST['user_id'];
    $get_current_user_id = get_current_user_id();
    $get_seller_hidden_user = get_user_meta($get_current_user_id, 'so_hidden_seller', true);
    $user_info = get_userdata($user_id);
    $username = $user_info->user_login;
    $final_array = array_diff($get_seller_hidden_user, array($user_id));

    // Output the updated array
    $result = update_user_meta($get_current_user_id, 'so_hidden_seller', $final_array);

    // Check if update was successful
    if ($result) {
        echo 'Success! ' . $username . '  has been removed from hidden users';
    } else {
        echo "Failed! Unable to removed form hidden users";
    }
    die();
}

function my_spitout_tab_setting_js()
{
    if (is_page('my-spitout')) { ?>

        <script>
            jQuery(document).ready(function() {

                jQuery(function() {
                    jQuery('.sales-pagination').on('click', function(e) {
                        //window.localStorage.removeItem("activeTab");
                        window.localStorage.setItem('activeTab', '#so-my-spitout-sales');
                    });
                    var activeTab = window.localStorage.getItem('activeTab');
                    if (activeTab) {
                        jQuery('#myTab button[data-target="' + activeTab + '"]').tab('show');
                        window.localStorage.removeItem("activeTab");
                    }

                });
            });
        </script>
    <?php
    }
}
add_action('wp_footer', 'my_spitout_tab_setting_js');

/**
 * The above PHP function creates a shortcode in WordPress that generates an age verification popup
 * dialog with a form for users to enter their date of birth.
 * 
 * @param mixed $atts The only attribute being used is `age`, which represents the age of consent 
 * required to view the content.
 * 
 * @return void code for an age verification popup dialog.
 */
//vileroze
add_shortcode('age_verification', 'spitout_age_verification');
function spitout_age_verification($atts)
{
    //dont show to logged in users
    if (is_user_logged_in()) {
        return;
    }
    $age_of_consent = $atts['age'];
    ob_start();
    ?>

    <dialog id="age-verfication-popup" class="overlay so-age-verification-popup" tabindex="-1">
        <div class="popup so-age-verify-popup">
            <div class="age-verify-popup-limit">
                <h5>+21</h5>
            </div>
            <h4 class="mt-3 text-center">Age Verification</h4>
            <h3 id="age-verification-err">You are not old enough to view this content</h3>
            <p class="mt-3 mb-3">
                This Website requires you to be <span>
                    <?php echo $age_of_consent; ?>
                </span> years or older to enter.
                Please enter your Date of Birth in the fields below in order
                to continue:
            </p>
            <div class="content">
                <form action="" id="verify-age">

                    <select name="month" class="form-control" id="verify-month" required>
                        <option value="none">Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>

                    <select name="day" class="form-control" id="verify-day" required>
                        <option value="none">Day</option>
                    </select>

                    <select name="Year" class="form-control" id="verify-year" required>
                        <option value="none">Year</option>
                    </select>
                    <!-- <input type="submit" class="btn btn-default" id="age-submit" value="VERIFY"> -->
                </form>
                <a href="#" class="btn btn-default age-verify-submit-btn" id="age-submit">Submit
                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 100 100" height="20" viewBox="0 0 100 100" width="20">
                        <path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                    </svg>
                </a>
            </div>
        </div>
    </dialog>
<?php
    return ob_get_clean();
}

//add new product form
add_shortcode('add_new_product_form', 'spitout_add_new_product_form_shortcode');
function spitout_add_new_product_form_shortcode()
{
    ob_start();

    if (isset($_POST['add_product'])) {
        // WordPress environmet
        require(dirname(__FILE__) . '/../../../wp-load.php');
        // it allows us to use wp_handle_upload() function
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        // validation
        if (empty($_FILES['product_picture'])) {
            wp_die('No files selected.');
        }

        $upload = wp_handle_upload(
            $_FILES['product_picture'],
            ['test_form' => false]
        );

        if (!empty($upload['error'])) {
            wp_die($upload['error']);
        }

        // it is time to add our uploaded image into WordPress media library
        $attachment_id = wp_insert_attachment(
            [
                'guid' => $upload['url'],
                'post_mime_type' => $upload['type'],
                'post_title' => basename($upload['file']),
                'post_content' => '',
                'post_status' => 'inherit',
            ],
            $upload['file']
        );

        if (is_wp_error($attachment_id) || !$attachment_id) {
            wp_die('Upload error.');
        }

        // update medatata, regenerate image sizes
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        wp_update_attachment_metadata(
            $attachment_id,
            wp_generate_attachment_metadata($attachment_id, $upload['file'])
        );

        //create new product
        $product_name = sanitize_text_field($_POST['product_name']);
        $price = floatval($_POST['price']);
        $final_price = spitout_get_formatted_price($price);

        $additional_info = sanitize_textarea_field($_POST['additional_info']);
        $product = new WC_Product_Simple();
        $product->set_name($product_name);
        $product->set_regular_price($final_price);
        $product->set_short_description($additional_info);
        $product->set_image_id($attachment_id);
        $product->save();

        // $new_product_id = $product->get_id();
    }
?>
    <!-- ACCOUNT Page Contents -->
    <section class="so-account-content-wrapper">
        <div class="container inner-small-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="so-account-settings">
                        <h3>Add new product</h3>
                        <div class="settings-pannel">
                            <h4>Product Details</h4>
                            <p>Add product, images, price and etc</p>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="product_name">Product Name:</label>
                                    <input type="text" name="product_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price:</label>
                                    <input type="number" step="1" name="price" required>
                                </div>
                                <div class="form-group">
                                    <label for="additional_info">Additional Information:</label>
                                    <textarea name="additional_info"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="product_picture">Product Image:</label>
                                    <input type="file" name="product_picture" />
                                    <!-- accept="image/*" -->
                                </div>
                                <div class="form-group">
                                    <input type="submit" name="add_product" value="Add Product">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}


//to store the current exchange rate, to be used in graphs in 'my-spitout' page
add_action('wp_footer', 'spitout_hidden_echange_rate');
function spitout_hidden_echange_rate()
{
    if (is_page('my-spitout')) {
        echo '<input type="hidden" class="curr-exchange-rate" value="' . spitout_get_currency_exchange_btc_usd_rate() . '">';
    }
}


add_action('woocommerce_add_to_cart', 'so_add_product_meta_for_wallet_topup_on_submit', 10, 6);
function so_add_product_meta_for_wallet_topup_on_submit($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
{
    if (($product_id == (int)(get_wallet_rechargeable_product()->get_id())) && isset($_POST['woo_add_to_wallet'])) {
        if (so_get_dynamic_currency() == "BTC") {
            //update the product meta
            update_post_meta($product_id, '_regular_price', $_POST['woo_wallet_balance_to_add']);
            $usd_price = (float)$_POST['woo_wallet_balance_to_add'] * spitout_get_currency_exchange_btc_usd_rate();
            update_post_meta($product_id, '_regular_price_wmcp', json_encode(['USD' => (string)$usd_price]));
        } else {
            $btc_price = truncate_decimal_places(($_POST['woo_wallet_balance_to_add'] / spitout_get_currency_exchange_btc_usd_rate()), 6);
            // $btc_price = number_format(($_POST['woo_wallet_balance_to_add'] / spitout_get_currency_exchange_btc_usd_rate()), 6, '.', '');
            update_post_meta($product_id, '_regular_price', $btc_price);
            update_post_meta($product_id, '_regular_price_wmcp', json_encode(['USD' => $_POST['woo_wallet_balance_to_add']]));
        }
    }
}


add_filter('custom_modify_cart_item_data', 'so_chage_wallet_product_data', 10, 6);
function so_chage_wallet_product_data($cart_item_data, $product_id, $variation_id)
{
    // $cart_item_data['fffffff'] = 'ffffff';
    if (($product_id == (int)(get_wallet_rechargeable_product()->get_id())) && isset($_POST['woo_add_to_wallet'])) {
        if (so_get_dynamic_currency() == "BTC") {
            $usd_price = (float)$_POST['woo_wallet_balance_to_add'] * spitout_get_currency_exchange_btc_usd_rate();

            //update the woocommerce cart item details
            $cart_item_data['regular_price'] = $_POST['woo_wallet_balance_to_add'];
            $cart_item_data['regular_price_wmcp'] = (string)$usd_price;
        } else {
            $btc_price = (($_POST['woo_wallet_balance_to_add'] / spitout_get_currency_exchange_btc_usd_rate()));
            // $btc_price = number_format(($_POST['woo_wallet_balance_to_add'] / spitout_get_currency_exchange_btc_usd_rate()), 6, '.', '');

            //update the woocommerce cart item details
            $cart_item_data['regular_price'] = (string)$btc_price;
            $cart_item_data['regular_price_wmcp'] = $_POST['woo_wallet_balance_to_add'];
        }
    }
    return $cart_item_data;
}


// add_action('woocommerce_before_checkout_form', 'so_add_product_meta_for_wallet_topup');
// function so_add_product_meta_for_wallet_topup()
// {
//     if (!class_exists('WooCommerce')) {
//         return;
//     }

//     $cart = WC()->cart;
//     $wallet_topup_product_id = get_wallet_rechargeable_product()->get_id();

//     if ($cart->get_cart_contents_count() == 1) {

//         $cart_items = $cart->get_cart();

//         foreach ($cart_items as $cart_item_key => $cart_item) {

//             if ($cart_item['product_id'] == $wallet_topup_product_id) {
//                 update_post_meta($cart_item['product_id'], '_regular_price', 'your_meta_value');
//                 break;
//             }
//         }
//     }
// }





/* The above PHP code defines a function named `spitout_enable_plisio_only_in_wallettopup` that takes
an array of available gateways as a parameter. The function is intended to modify the list of
available gateways and enable only the Plisio gateway when the user is in the "wallettopup" context.
 */
function spitout_enable_plisio_only_in_wallettopup($available_gateways)
{


    if (is_admin()) return $available_gateways;
    if (!is_checkout()) return $available_gateways;
    // Get cart object
    $cart = WC()->cart;

    // Get cart items
    $cart_items = $cart->get_cart();

    // Flag to indicate if the product is found
    $product_found = false;

    // Loop through cart items
    foreach ($cart_items as $cart_item_key => $cart_item) {
        // Get product ID
        $product_id = $cart_item['product_id'];

        // Check if the product ID matches the one we're looking for (1964)
        if ($product_id == get_wallet_rechargeable_product()->get_id()) {
            $product_found = true;
            break; // Stop the loop since we found the product
        }
    }

    if ($product_found == false) unset($available_gateways['plisio']);
    return $available_gateways;
}
add_filter('woocommerce_available_payment_gateways', 'spitout_enable_plisio_only_in_wallettopup');




/* -------------------------user restrict to wp-admin-------------- */


function spitout_restrict_admin_access()
{
    if ((current_user_can('seller') || current_user_can('buyer')) && is_admin() && !defined('DOING_AJAX')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'spitout_restrict_admin_access');



// use to load seller (load filter user)

function load_filtered_sellers()
{
    $users_ids = array();
    $button_id = sanitize_text_field($_POST['button_id']);
    $button_target = sanitize_text_field($_POST['button_target']);
    $button_tab = sanitize_text_field($_POST['tab']);
    $selected_cat_ids = isset($_POST['cat_id']) ? array_map('intval', $_POST['cat_id']) : array();
    $searchValue = isset($_POST['searchValue']) ? sanitize_text_field($_POST['searchValue']) : '';
    $location = isset($_POST['location']) ? sanitize_text_field($_POST['location']) : '';
    $selectedMinAge = isset($_POST['selectedMinAge']) ? (int)sanitize_text_field($_POST['selectedMinAge']) : '';
    $selectedMaxAge = isset($_POST['selectedMaxAge']) ? (int)sanitize_text_field($_POST['selectedMaxAge']) : '';
    $selectedMinPrice = isset($_POST['selectedMinPrice']) ? (int)sanitize_text_field($_POST['selectedMinPrice']) : '';
    $selectedMaxPrice = isset($_POST['selectedMaxPrice']) ? (int)sanitize_text_field($_POST['selectedMaxPrice']) : '';

    $all_sellers = get_users(
        array(
            'role' => 'seller',
        )
    );

    switch ($button_tab) {
        case 'active':

            if (!empty($selected_cat_ids) || !empty($searchValue) || !empty($location) || !empty($selectedMinAge) || !empty($selectedMaxAge) || !empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                // Add the filter before running the query
                add_action('pre_user_query', 'modify_user_query_for_partial_search');
                // Prepare the meta query
                $meta_query = array(
                    'relation' => 'AND', // Use AND relation for matching any of the selected categories
                );

                foreach ($selected_cat_ids as $cat_id) {
                    // Add a LIKE condition for each category ID
                    $meta_query[] = array(
                        'key'     => 'so_category', // Meta key to match
                        'value'   => '"' . $cat_id . '"', // Serialized value format
                        'compare' => 'LIKE', // Use LIKE to match serialized arrays
                    );
                }

                // Query users with the role 'seller'
                $args = array(
                    'role'    => 'seller', // Role to match
                    'meta_query' => $meta_query, // Meta query to filter by so_category
                    'search'     => esc_attr($searchValue), // Search by username
                );

                $user_query = new WP_User_Query($args);

                // Get the results
                $users = $user_query->get_results();

                // Remove the filter after running the query
                remove_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Check if users are found
                if (!empty($users)) {
                    $users_ids = []; // Initialize the array to store user IDs


                    foreach ($users as $user) {
                        $post_meta_date = get_user_meta($user->ID, 'so_dob', true);
                        $status = get_user_meta($user->ID, "cpmm_user_status", true);
                        $seller_location = get_user_meta($user->ID, 'so_location', true);
                        if ($status == 'logged_in') {
                            // Calculate the user's age based on their DOB
                            $age = 0;

                            if (!empty($post_meta_date)) {
                                try {
                                    // Create a DateTime object from the given format
                                    $dob = DateTime::createFromFormat('d/m/Y', $post_meta_date);

                                    // Check if the conversion was successful
                                    if ($dob === false) {
                                        throw new Exception("Invalid date format: " . $post_meta_date);
                                    }

                                    $now = new DateTime();
                                    $age = (int)$now->diff($dob)->y;
                                    error_log("User Age: " . $age);
                                } catch (Exception $e) {
                                    error_log("Error: " . $e->getMessage());
                                }
                            }
                            // var_dump($age);
                            // Check if the user meets the location and age criteria
                            $location_match = true;
                            $age_match = true;
                            $price_match = true;

                            if (!empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                                $product_user_ids = get_woocommerce_user_ids_by_user_id_and_price_range($user->ID, $selectedMinPrice, $selectedMaxPrice);

                                if (is_null($product_user_ids)) {
                                    $price_match = false;
                                }
                            }

                            if (!empty($location)) {
                                $location_match = ($seller_location == $location);
                            }

                            if ((!empty($selectedMinAge) || !empty($selectedMaxAge)) && $age > 0) {
                                if (!empty($selectedMinAge) && !empty($selectedMaxAge)) {
                                    $age_match = ($age >= $selectedMinAge && $age <= $selectedMaxAge);
                                } elseif (!empty($selectedMinAge)) {
                                    $age_match = ($age >= $selectedMinAge);
                                } elseif (!empty($selectedMaxAge)) {
                                    $age_match = ($age <= $selectedMaxAge);
                                }
                            }

                            if ($location_match && $age_match && $price_match) {
                                $users_ids[] = $user->ID;
                            }
                        }
                    }
                }
            } else {
                $users_ids = [];
                // Code to execute if expression equals active
                foreach ($all_sellers as $user) {
                    $user_id = $user->ID;
                    // $status = get_user_meta($user_id, 'so_online_status', true);
                    $status = get_user_meta($user_id, "cpmm_user_status", true);
                    if ($status == 'logged_in') {
                        $users_ids[] = $user_id;
                    }
                }
            }
            break;

        case 'popular':
            if (!empty($selected_cat_ids) || !empty($searchValue) || !empty($location) || !empty($selectedMinAge) || !empty($selectedMaxAge) || !empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                // Add the filter before running the query
                add_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Prepare the meta query
                $meta_query = array(
                    'relation' => 'AND', // Use OR relation for matching any of the selected categories
                );

                foreach ($selected_cat_ids as $cat_id) {
                    // Add a LIKE condition for each category ID
                    $meta_query[] = array(
                        'key'     => 'so_category', // Meta key to match
                        'value'   => '"' . $cat_id . '"', // Serialized value format
                        'compare' => 'LIKE', // Use LIKE to match serialized arrays
                    );
                }

                // Assuming $users_ids is already populated with the user IDs to include
                // Prepare the user query arguments
                $args = array(
                    'role'       => 'seller', // Role to match
                    // 'include'    => array_keys(spitout_get_popular_sellers()), // Filter to only these user IDs
                    'include'    => get_popular_seller(), // Filter to only these user IDs
                    'meta_query' => $meta_query, // Meta query to filter by so_category
                    'search'     => esc_attr($searchValue), // Search by username
                );

                // Perform the user query
                $user_query = new WP_User_Query($args);

                // Get the results
                $users = $user_query->get_results();

                // Remove the filter after running the query
                remove_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Check if users are found
                if (!empty($users)) {
                    $users_ids = [];
                    foreach ($users as $user) {
                        $seller_location = get_user_meta($user->ID, 'so_location', true);
                        $post_meta_date = get_user_meta($user->ID, 'so_dob', true);

                        $age = 0;
                        if (!empty($post_meta_date)) {
                            try {
                                // Create a DateTime object from the given format
                                $dob = DateTime::createFromFormat('d/m/Y', $post_meta_date);

                                // Check if the conversion was successful
                                if ($dob === false) {
                                    throw new Exception("Invalid date format: " . $post_meta_date);
                                }

                                $now = new DateTime();
                                $age = (int)$now->diff($dob)->y;
                                error_log("User Age: " . $age);
                            } catch (Exception $e) {
                                error_log("Error: " . $e->getMessage());
                            }
                        }
                        $location_match = true;
                        $age_match = true;
                        $price_match = true;
                        if (!empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                            $product_user_ids = get_woocommerce_user_ids_by_user_id_and_price_range($user->ID, $selectedMinPrice, $selectedMaxPrice);

                            if (is_null($product_user_ids)) {
                                $price_match = false;
                            }
                        }

                        if (!empty($location)) {
                            $location_match = ($seller_location == $location);
                        }

                        if ((!empty($selectedMinAge) || !empty($selectedMaxAge)) && $age > 0) {
                            if (!empty($selectedMinAge) && !empty($selectedMaxAge)) {
                                $age_match = ($age >= $selectedMinAge && $age <= $selectedMaxAge);
                            } elseif (!empty($selectedMinAge)) {
                                $age_match = ($age >= $selectedMinAge);
                            } elseif (!empty($selectedMaxAge)) {
                                $age_match = ($age <= $selectedMaxAge);
                            }
                        }

                        if ($location_match && $age_match && $price_match) {
                            $users_ids[] = $user->ID;
                        }
                    }
                }
            } else {
                // Code to execute if expression equals popular
                // $users_ids = array_keys(spitout_get_popular_sellers());
                $users_ids = get_popular_seller();
            }
            break;

        case 'recommended':
            // Code to execute if expression equals recommended
            break;
        case 'new-sellers':
            if (!empty($selected_cat_ids) || !empty($searchValue) || !empty($location) || !empty($selectedMinAge) || !empty($selectedMaxAge) || !empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                // Add the filter before running the query
                add_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Prepare the meta query
                $meta_query = array(
                    'relation' => 'AND', // Use OR relation for matching any of the selected categories
                );

                foreach ($selected_cat_ids as $cat_id) {
                    // Add a LIKE condition for each category ID
                    $meta_query[] = array(
                        'key'     => 'so_category', // Meta key to match
                        'value'   => '"' . $cat_id . '"', // Serialized value format
                        'compare' => 'LIKE', // Use LIKE to match serialized arrays
                    );
                }

                // Assuming $users_ids is already populated with the user IDs to include
                // Prepare the user query arguments
                $args = array(
                    'role'       => 'seller', // Role to match
                    // 'include'    => array_keys(spitout_get_popular_sellers()), // Filter to only these user IDs
                    'include'    => get_newer_seller(), // Filter to only these user IDs
                    'meta_query' => $meta_query, // Meta query to filter by so_category
                    'search'     => esc_attr($searchValue), // Search by username
                );

                // Perform the user query
                $user_query = new WP_User_Query($args);

                // Get the results
                $users = $user_query->get_results();

                // Remove the filter after running the query
                remove_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Check if users are found
                if (!empty($users)) {
                    $users_ids = [];
                    foreach ($users as $user) {
                        $seller_location = get_user_meta($user->ID, 'so_location', true);
                        $post_meta_date = get_user_meta($user->ID, 'so_dob', true);

                        $age = 0;
                        if (!empty($post_meta_date)) {
                            try {
                                // Create a DateTime object from the given format
                                $dob = DateTime::createFromFormat('d/m/Y', $post_meta_date);

                                // Check if the conversion was successful
                                if ($dob === false) {
                                    throw new Exception("Invalid date format: " . $post_meta_date);
                                }

                                $now = new DateTime();
                                $age = (int)$now->diff($dob)->y;
                                error_log("User Age: " . $age);
                            } catch (Exception $e) {
                                error_log("Error: " . $e->getMessage());
                            }
                        }
                        $location_match = true;
                        $age_match = true;
                        $price_match = true;
                        if (!empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                            $product_user_ids = get_woocommerce_user_ids_by_user_id_and_price_range($user->ID, $selectedMinPrice, $selectedMaxPrice);

                            if (is_null($product_user_ids)) {
                                $price_match = false;
                            }
                        }

                        if (!empty($location)) {
                            $location_match = ($seller_location == $location);
                        }

                        if ((!empty($selectedMinAge) || !empty($selectedMaxAge)) && $age > 0) {
                            if (!empty($selectedMinAge) && !empty($selectedMaxAge)) {
                                $age_match = ($age >= $selectedMinAge && $age <= $selectedMaxAge);
                            } elseif (!empty($selectedMinAge)) {
                                $age_match = ($age >= $selectedMinAge);
                            } elseif (!empty($selectedMaxAge)) {
                                $age_match = ($age <= $selectedMaxAge);
                            }
                        }

                        if ($location_match && $age_match && $price_match) {
                            $users_ids[] = $user->ID;
                        }
                    }
                }
            } else {
                $users_ids = get_newer_seller(15);
            }
            break;

        default:
            if (!empty($selected_cat_ids) || !empty($searchValue) || !empty($location) || !empty($selectedMinAge) || !empty($selectedMaxAge) || !empty($selectedMinPrice) || !empty($selectedMaxPrice)) {
                // Add the filter before running the query
                add_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Prepare the meta query
                $meta_query = array(
                    'relation' => 'AND', // Use OR relation for matching any of the selected categories
                );

                foreach ($selected_cat_ids as $cat_id) {
                    // Add a LIKE condition for each category ID
                    $meta_query[] = array(
                        'key'     => 'so_category', // Meta key to match
                        'value'   => '"' . $cat_id . '"', // Serialized value format
                        'compare' => 'LIKE', // Use LIKE to match serialized arrays
                    );
                }

                // Query users with the role 'seller'
                $args = array(
                    'role'    => 'seller', // Role to match
                    'meta_query' => $meta_query, // Meta query to filter by so_category
                    'search'     => esc_attr($searchValue), // Search by username
                );

                $user_query = new WP_User_Query($args);

                // Get the results
                $users = $user_query->get_results();

                // Remove the filter after running the query
                remove_action('pre_user_query', 'modify_user_query_for_partial_search');

                // Check if users are found
                if (!empty($users)) {
                    $users_ids = [];
                    foreach ($users as $user) {
                        $seller_location = get_user_meta($user->ID, 'so_location', true);
                        $post_meta_date = get_user_meta($user->ID, 'so_dob', true);
                        $age = 0;
                        if (!empty($post_meta_date)) {
                            try {
                                // Create a DateTime object from the given format
                                $dob = DateTime::createFromFormat('d/m/Y', $post_meta_date);

                                // Check if the conversion was successful
                                if ($dob === false) {
                                    throw new Exception("Invalid date format: " . $post_meta_date);
                                }

                                $now = new DateTime();
                                $age = (int)$now->diff($dob)->y;
                                error_log("User Age: " . $age);
                            } catch (Exception $e) {
                                error_log("Error: " . $e->getMessage());
                            }
                        }
                        $location_match = true;
                        $age_match = true;
                        $price_match = true;
                        if (!empty($selectedMinPrice) || !empty($selectedMaxPrice)) {

                            $product_user_ids = get_woocommerce_user_ids_by_user_id_and_price_range($user->ID, $selectedMinPrice, $selectedMaxPrice);
                            if (is_null($product_user_ids)) {
                                $price_match = false;
                            }
                        }

                        if (!empty($location)) {
                            $location_match = ($seller_location == $location);
                        }

                        if ((!empty($selectedMinAge) || !empty($selectedMaxAge)) && $age > 0) {
                            if (!empty($selectedMinAge) && !empty($selectedMaxAge)) {
                                $age_match = ($age >= $selectedMinAge && $age <= $selectedMaxAge);
                            } elseif (!empty($selectedMinAge)) {
                                $age_match = ($age >= $selectedMinAge);
                            } elseif (!empty($selectedMaxAge)) {
                                $age_match = ($age <= $selectedMaxAge);
                            }
                        }

                        if ($location_match && $age_match && $price_match) {
                            $users_ids[] = $user->ID;
                        }
                    }
                }
            } else {
                $users_ids = [];
                // Code to execute if expression doesn't match any value
                foreach ($all_sellers as $user) {
                    $user_id = $user->ID;
                    $users_ids[] = $user_id;
                }
            }
    }
    if (!empty($users_ids)) { ?>

        <div class="tab-pane fade show active" id="<?php echo esc_html($button_target); ?>" role="tabpanel" aria-labelledby="<?php echo esc_html($button_id); ?>">
            <div class="row">
                <?php
                foreach ($users_ids as $seller) {
                    $attachment_id = (int) get_user_meta($seller, 'so_profile_img', true);
                    $seller_img_url = resize_and_compress_image($attachment_id, 150, 150, 70);
                    if (!$seller_img_url) {
                        $seller_img_url = get_template_directory_uri() . '/assets/img/user.png';
                    }
                    $seller_data = get_userdata((int) $seller);
                    $seller_url = get_author_posts_url($seller);
                    $seller_location = get_user_meta($seller, "so_location", true);
                    $seller_category_id = get_user_meta($seller, "so_category", true) ? get_user_meta($seller, "so_category", true)[0] : '';
                    $seller_category = $seller_category_id ? get_the_title($seller_category_id) : 'N/A';
                    $get_Followers = get_user_meta($seller, 'so_total_followers', true) ? get_user_meta($seller, 'so_total_followers', true) : [];
                    $totalFollowers = is_array($get_Followers) || $get_Followers instanceof Countable
                        ? (count($get_Followers) > 0
                            ? (count($get_Followers) >= 1000
                                ? round(count($get_Followers) / 1000, 1) . 'k'
                                : count($get_Followers))
                            : 'N/A')
                        : 'N/A';
                    // $seller_active_status = get_user_meta($seller, 'user_status', true);
                    $seller_active_status = get_user_meta($seller, "cpmm_user_status", true);
                    $total_sold = get_number_of_products_sold_by_user($seller);
                    $seller_rating = (float)get_rating_of_seller($seller);
                    // var_dump($seller_rating);
                    if ($seller_active_status == 'logged_in') {
                        $active_status = 'online';
                    } else {
                        $active_status = 'offline';
                    }
                    if ($seller_data == false) {
                        // echo '<p class="text-warning">Sellerid:'.$seller.' does not exist</p> <br>';
                        continue;
                    } ?>

                    <div class="col-md-6 col-sm-6 col-6 col-lg-3">
                        <a href="<?php echo $seller_url; ?>" class="seller-card-block">
                            <div class="so-new-seller-desc">
                                <div class="so-seller-header">
                                    <figure>
                                        <i class="bi bi-circle-fill <?php echo $active_status; ?>"></i><!--This is to mark the seller as online -->
                                        <img src="<?php echo esc_url($seller_img_url); ?>" alt="<?php echo $seller_data->display_name; ?>">
                                    </figure>
                                    <div class="so-new-sellers-name">
                                        <?php
                                        $top_seller = get_popular_seller();
                                        $is_top_seller = in_array($seller, $top_seller);
                                        if ($is_top_seller) { ?>
                                            <span class="seller-tag-popular custom-pill-box yellow-pill-box">Top Seller</span>
                                        <?php } else { ?>
                                            <span class="seller-tag custom-pill-box"></span>
                                        <?php } ?>
                                        <h5 class="text-center m-0 p-2 d-flex">
                                            <?php echo $seller_data->display_name; ?>
                                            <?php if ((int) get_user_meta($seller, 'is_verified', true) == 1) { ?>
                                                <div class="profile-verify" title="verified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                        <path d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z" fill="#292D32" />
                                                    </svg>
                                                </div>
                                            <?php } ?>
                                        </h5>
                                        <div class="d-flex seller-page-location-details seller-page-location">
                                            <p class="text-center d-flex">
                                                <span class="so-custom-icon icon-lightgray">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                                    </svg>
                                                </span>
                                                <span>
                                                    <?php echo $seller_location ? $seller_location : "N/A"; ?></span>
                                                <!-- <div class="seller-page-location">
                                                            </div> -->
                                            </p>
                                        </div>
                                        </p>
                                    </div>
                                </div>
                                <div class="so-seller-footer mt-4 pt-3">
                                    <div class="seller-detailed-info mb-2">
                                        <div class="seller-followers">
                                            <h6><strong><?php echo esc_html($totalFollowers); ?></strong></h6>
                                            <span>Followers</span>
                                        </div>
                                        <div class="seller-sold">
                                            <h6><strong><?php echo esc_html($total_sold); ?></strong></h6>
                                            <span>Spits Sold</span>
                                        </div>
                                        <div class="seller-category">
                                            <p class="custom-pill-box pink-pill-box"><span><?php echo esc_html($seller_category); ?></span></p>
                                        </div>
                                    </div>
                                    <?php
                                    // Full stars
                                    $fullStars = floor($seller_rating);

                                    // Half star (if the seller_ra$seller_rating has a decimal part greater than 0)
                                    $halfStar = ($seller_rating - $fullStars >= 0.5) ? 1 : 0;

                                    // Empty stars
                                    $emptyStars = 5 - ($fullStars + $halfStar);


                                    ?>
                                    <div class="seller-new-rating">
                                        <p>
                                            <span>
                                                <?php
                                                // Generate the full stars
                                                for ($i = 0; $i < $fullStars; $i++) { ?>
                                                    <i class="bi bi-star-fill"></i>
                                                <?php }

                                                // Generate the half star (if any)
                                                if ($halfStar) { ?>
                                                    <i class="bi bi-star-half"></i>
                                                <?php }

                                                // Generate the empty stars
                                                for ($i = 0; $i < $emptyStars; $i++) { ?>
                                                    <i class="bi bi-star"></i>
                                                <?php } ?>
                                            </span> <?php echo esc_html($seller_rating); ?> Rating
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php } ?>
            </div>
        </div>

    <?php } else {
        echo '<span class="no-seller-found">No user found!</span>';
    }
    wp_die();
}
add_action('wp_ajax_load_filtered_sellers', 'load_filtered_sellers');
add_action('wp_ajax_nopriv_load_filtered_sellers', 'load_filtered_sellers');


//function to change the size and copress the image
function resize_and_compress_image($attachment_id, $max_width = 800, $max_height = 800, $quality = 70)
{
    $file_path = get_attached_file($attachment_id);

    if (!file_exists($file_path)) {
        return false; // Return false if the image file doesn't exist
    }

    // Create a new instance of the image editor
    $image_editor = wp_get_image_editor($file_path);

    if (is_wp_error($image_editor)) {
        return false; // Return false if there's an error with the image editor
    }

    // Resize the image
    $image_editor->resize($max_width, $max_height, true);

    // Set the compression quality
    $image_editor->set_quality($quality);

    // Generate a new filename for the resized image
    $upload_dir = wp_upload_dir();
    $file_info = pathinfo($file_path);
    $new_file_name = $file_info['filename'] . '-resized.' . $file_info['extension'];
    $new_file_path = $upload_dir['path'] . '/' . $new_file_name;

    // Save the resized image with the new filename
    $result = $image_editor->save($new_file_path);

    if (is_wp_error($result)) {
        return false; // Return false if there's an error saving the image
    }

    // Return the URL of the resized image
    return $upload_dir['url'] . '/' . $new_file_name;
}

// function for partial user search
function modify_user_query_for_partial_search($query)
{
    global $wpdb;

    // Only modify the query if the search term is set
    if (isset($query->query_vars['search'])) {
        // Modify the query to allow partial matches
        $search = esc_attr($query->query_vars['search']);
        $search = like_escape($search);
        $query->query_where = str_replace(
            "user_login LIKE",
            "user_login LIKE '%{$search}%' OR {$wpdb->users}.user_nicename LIKE '%{$search}%' OR {$wpdb->users}.display_name LIKE '%{$search}%'",
            $query->query_where
        );
    }
}

// ShortCode For Homepage Banner content =================
add_shortcode('so_banner_content', 'so_banner_content');
function so_banner_content()
{
    ob_start();
    $seller_args = array(
        'post_type' => 'spit-category', // Your custom post type
        'posts_per_page' => -1, // Retrieve all posts
    );
    // Execute the query
    $seller_type_query = new WP_Query($seller_args);
    // Get the count of posts
    $seller_cat_count = $seller_type_query->post_count;

    // rating testing here starts

    global $wpdb;

    // Get all user IDs with the 'seller' role
    $seller_ids = get_users(array(
        'role'    => 'seller',
        'fields'  => 'ID',
    ));

    // Check if there are any seller IDs
    global $wpdb;

    // Get all user IDs with the 'seller' role
    $seller_ids = get_users(array(
        'role'    => 'seller',
        'fields'  => 'ID',
    ));

    // If there are sellers
    if (!empty($seller_ids)) {
        // Prepare the placeholders for the seller IDs
        $placeholders = implode(',', array_fill(0, count($seller_ids), '%d'));

        // 1. Count the number of unique users with a 5-star rating
        $query_unique_users = $wpdb->prepare("
        SELECT COUNT(DISTINCT c.comment_post_ID) 
        FROM {$wpdb->commentmeta} cm
        INNER JOIN {$wpdb->comments} c ON cm.comment_id = c.comment_ID
        WHERE cm.meta_key = 'rating'
        AND cm.meta_value = 5
        AND c.comment_post_ID IN ($placeholders)
    ", ...$seller_ids);

        $unique_user_count = $wpdb->get_var($query_unique_users);

        // 2. Get the IDs of users who received a 5-star rating
        $query_user_ids = $wpdb->prepare("
        SELECT DISTINCT c.comment_post_ID
        FROM {$wpdb->commentmeta} cm
        INNER JOIN {$wpdb->comments} c ON cm.comment_id = c.comment_ID
        WHERE cm.meta_key = 'rating'
        AND cm.meta_value = 5
        AND c.comment_post_ID IN ($placeholders)
    ", ...$seller_ids);

        $user_ids_with_5_star = $wpdb->get_col($query_user_ids);

        // 3. Get the total number of ratings
        $query_total_ratings = $wpdb->prepare("
        SELECT COUNT(*)
        FROM {$wpdb->commentmeta} cm
        INNER JOIN {$wpdb->comments} c ON cm.comment_id = c.comment_ID
        WHERE cm.meta_key = 'rating'
        AND c.comment_post_ID IN ($placeholders)
    ", ...$seller_ids);

        $total_ratings = $wpdb->get_var($query_total_ratings);

        // Output the results
        // echo 'Number of unique users with 5-star ratings: ' . $unique_user_count . '<br>';
        // echo 'User IDs with 5-star ratings: ';
        // print_r($user_ids_with_5_star);
        // echo '<br>';
        // echo 'Total number of ratings: ' . $total_ratings;
    }

    // Output or use the $ratings array as needed


    // rating testing here ends

    ?>
    <div class="seller-dropdown d-flex">
        <div class="custom-pill-box pink-pill-box"><?php echo esc_html($seller_cat_count); ?> Sellers</div>
        <div class="banner-cat-select">
            <div class="selected-cat">
                <span>Selected Category</span>
            </div>
            <div class="cat-option-dropdown">
                <?php // Check if there are any posts
                if ($seller_type_query->have_posts()) :
                    while ($seller_type_query->have_posts()) :
                        $seller_type_query->the_post();
                        $post_id = get_the_ID();
                        $cat_url = get_permalink($post_id);
                        $title = get_the_title();
                        $featured_image_id = get_post_meta($post_id, '_thumbnail_id', true);
                        $featured_image_url = resize_and_compress_image($featured_image_id, 150, 150, 70); 
                        if (!$featured_image_url) {
                            $featured_image_url = get_template_directory_uri() . '/assets/img/user.png';
                        }?>
                        <div class="cat-item">
                            <a href="<?php echo esc_url($cat_url); ?>">
                                <figure>
                                    <img src="<?php echo esc_url($featured_image_url); ?>" alt="cat-image">
                                </figure>
                                <span><?php echo esc_html($title); ?></span>
                            </a>
                        </div>
                <?php endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
    </div>
    <div class="wp-block-buttons">
        <div class="new-button sell-btn"><a class="wp-block-button__link wp-element-button" href="/register">Sell</a></div>
        <div class="new-button join-btn"><a class="wp-block-button__link wp-element-button" href="/my-spitout">Join Free</a></div>
    </div>

    <div class="banner-rewiews">
        <div class="reviewers-img">
            <?php if (!empty($user_ids_with_5_star))
                foreach ($user_ids_with_5_star as $user_id) {
                    $attachment_id = (int) get_user_meta($user_id, 'so_profile_img', true);
                    $seller_img_url = resize_and_compress_image($attachment_id, 150, 150, 70); ?>
                <figure>
                    <img src="<?php echo esc_url($seller_img_url); ?>" alt="reviewer-image">
                </figure>
            <?php } ?>
        </div>
        <div class="review-details">
            <div class="review-icons">
                <span><i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                </span>
                <span>5.0</span>
            </div>
            <div class="review-text">
                <p>From <?php echo $total_ratings; ?>+ Reviews</p>
            </div>
        </div>
    </div>
<?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
// END of ShortCode For Homepage Banner content

// ShortCode For HomepageCATEGORY =================
add_shortcode('so_category_list', 'so_category_list');
function so_category_list()
{
    ob_start(); ?>
    <div class="so-browse-categories mt-4">
        <ul class="so-browse-categories-lists">
            <?php
            $get_spit_category = get_posts(
                array(
                    'numberposts' => -1,
                    // -1 returns all posts
                    'post_type' => 'spit-category',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'post_status' => 'publish'
                )
            );

            foreach ($get_spit_category as $key => $get_spit_cat) { ?>
                <li>
                    <a href="<?php echo esc_url(get_permalink($get_spit_cat->ID)); ?>">
                        <h5><?php echo esc_html($get_spit_cat->post_title); ?></h5>
                    </a>
                </li>
            <?php } ?>

            <li id="browse-all-categories-btn">
                <button>
                    <a href="<?php echo home_url('/categories') ?>" class="so-browse-cat-browse-all d-flex">
                        <h5>Browse All</h5>

                        <span class="so-custom-icon">
                            <svg id="Layer_1" enable-background="new 0 0 100 100" height="512"
                                viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
                            </svg>
                        </span>
                    </a>
            </li>
            </button>
        </ul>
    </div>
<?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
// END of ShortCode For HomepageCATEGORY 


function get_woocommerce_user_ids_by_user_id_and_price_range($user_id, $min_price = 0, $max_price = PHP_INT_MAX)
{
    global $wpdb;

    // Prepare SQL query
    $sql = $wpdb->prepare(
        "SELECT p.ID
        FROM {$wpdb->posts} p
        JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'product'
        AND p.post_author = %d
        AND pm.meta_key = '_regular_price_wmcp'
        AND JSON_EXTRACT(pm.meta_value, '$.USD') BETWEEN %f AND %f
        LIMIT 1",
        $user_id,
        $min_price,
        $max_price
    );

    // Execute SQL query
    $result = $wpdb->get_var($sql);

    // Check if any product is found
    if ($result) {
        return $user_id; // Return the author's ID if a product is found
    }

    return null; // Return null if no matching product is found
}


// ShortCode For HomepageSeller =================
add_shortcode('so_seller_list', 'so_seller_list');
function so_seller_list($atts)
{
    $status = '';
    // Set default attributes
    $atts = shortcode_atts(
        array(
            'status' => 'active', // Default status
            'count' => -1 // Default count, -1 means no limit
        ),
        $atts,
        'so_seller_list'
    );

    // Get the status and count from attributes
    $status = sanitize_text_field($atts['status']);
    $count = intval($atts['count']);

    ob_start();
    switch ($status) {
        case 'new':
            $seller_ids = get_newer_seller($count);
            break;
        case 'popular':
            $seller_ids = get_popular_seller($count);
            break;
        default:
            break;
    } ?>
    <?php if (!empty($seller_ids)) { ?>
        <div class="shortcode-seller-wrapper">
            <div class="row">
                <?php
                foreach ($seller_ids as $seller) {
                    $attachment_id = (int) get_user_meta($seller, 'so_profile_img', true);
                    $seller_img_url = resize_and_compress_image($attachment_id, 150, 150, 70);
                    if (!$seller_img_url) {
                        $seller_img_url = get_template_directory_uri() . '/assets/img/user.png';
                    }
                    $seller_data = get_userdata((int) $seller);
                    $seller_url = get_author_posts_url($seller);
                    $seller_location = get_user_meta($seller, "so_location", true);
                    $seller_category_id = get_user_meta($seller, "so_category", true) ? get_user_meta($seller, "so_category", true)[0] : '';
                    $seller_category = $seller_category_id ? get_the_title($seller_category_id) : 'N/A';
                    $get_Followers = get_user_meta($seller, 'so_total_followers', true) ? get_user_meta($seller, 'so_total_followers', true) : [];
                    $totalFollowers = is_array($get_Followers) || $get_Followers instanceof Countable
                        ? (count($get_Followers) > 0
                            ? (count($get_Followers) >= 1000
                                ? round(count($get_Followers) / 1000, 1) . 'k'
                                : count($get_Followers))
                            : 'N/A')
                        : 'N/A';
                    // $seller_active_status = get_user_meta($seller, 'user_status', true);
                    $seller_active_status = get_user_meta($seller, "cpmm_user_status", true);
                    $total_sold = get_number_of_products_sold_by_user($seller);
                    $seller_rating = (float)get_rating_of_seller($seller);
                    if ($seller_active_status == 'logged_in') {
                        $active_status = 'online';
                    } else {
                        $active_status = 'offline';
                    }
                    if ($seller_data == false) {
                        // echo '<p class="text-warning">Sellerid:'.$seller.' does not exist</p> <br>';
                        continue;
                    } ?>

                    <div class="col-md-6 col-sm-6 col-6 col-lg-3">
                        <a href="<?php echo $seller_url; ?>" class="seller-card-block">
                            <div class="so-new-seller-desc">
                                <div class="so-seller-header">
                                    <figure>
                                        <i class="bi bi-circle-fill <?php echo $active_status; ?>"></i><!--This is to mark the seller as online -->
                                        <img src="<?php echo esc_url($seller_img_url); ?>" alt="<?php echo $seller_data->display_name; ?>">
                                    </figure>
                                    <div class="so-new-sellers-name">
                                        <h5 class="text-center m-0 p-2 d-flex">
                                            <?php echo $seller_data->display_name; ?>
                                            <?php if ((int) get_user_meta($seller, 'is_verified', true) == 1) { ?>
                                                <div class="profile-verify" title="verified">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                        <path d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z" fill="#292D32" />
                                                    </svg>
                                                </div>
                                            <?php } ?>
                                        </h5>
                                        <div class="d-flex seller-page-location-details seller-page-location">
                                            <p class="text-center d-flex">
                                                <span class="so-custom-icon icon-lightgray">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
                                                    </svg>
                                                </span>
                                                <span>
                                                    <?php echo $seller_location ? $seller_location : "N/A"; ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="so-seller-footer mt-4 pt-3">
                                    <div class="seller-detailed-info mb-2">
                                        <div class="seller-followers">
                                            <h6><strong><?php echo esc_html($totalFollowers); ?></strong></h6>
                                            <span>Followers</span>
                                        </div>
                                        <div class="seller-sold">
                                            <h6><strong><?php echo esc_html($total_sold); ?></strong></h6>
                                            <span>Spits Sold</span>
                                        </div>
                                        <div class="seller-category">
                                            <p class="custom-pill-box pink-pill-box"><span><?php echo esc_html($seller_category); ?></span></p>
                                        </div>
                                    </div>
                                    <?php
                                    // Full stars
                                    $fullStars = floor($seller_rating);
                                    // var_dump($fullStars);
                                    // Half star (if the seller_ra$seller_rating has a decimal part greater than 0)
                                    $halfStar = ($seller_rating - $fullStars >= 0.5) ? 1 : 0;

                                    // Empty stars
                                    $emptyStars = 5 - ($fullStars + $halfStar); ?>
                                    <div class="seller-new-rating">
                                        <p>
                                            <span>
                                                <?php
                                                // Generate the full stars
                                                for ($i = 0; $i < $fullStars; $i++) { ?>
                                                    <i class="bi bi-star-fill"></i>
                                                <?php }

                                                // Generate the half star (if any)
                                                if ($halfStar) { ?>
                                                    <i class="bi bi-star-half"></i>
                                                <?php }

                                                // Generate the empty stars
                                                for ($i = 0; $i < $emptyStars; $i++) { ?>
                                                    <i class="bi bi-star"></i>
                                                <?php } ?>
                                            </span> <?php echo esc_html($seller_rating); ?> Rating
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}
// END of ShortCode For HomepageSeller 



function get_popular_seller($count = -1)
{
    // Query to get all users with the role 'seller'
    $user_args = array(
        'role' => 'seller' // Get users with the role 'seller'
    );

    $user_query = new WP_User_Query($user_args);
    $sellers = [];
    $seller_ids = array();
    // Check if users are found
    if (!empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            $user_id = $user->ID;

            // Get WooCommerce products for this user
            $product_args = array(
                'post_type' => 'product',
                'posts_per_page' => $count,
                'author' => $user_id,
                'post_status' => 'publish'
            );

            $products = get_posts($product_args);
            $total_sales = 0;

            // Calculate total sales for all products of this user
            foreach ($products as $product) {
                $product_sales = intval(get_post_meta($product->ID, 'total_sales', true));
                $total_sales += $product_sales;
            }
            if ($total_sales > 0) {
                // Add the seller and their total sales to the array
                $sellers[] = array(
                    'user' => $user,
                    'total_sales' => $total_sales

                );
            }
        }

        // Sort sellers by total sales in descending order
        usort($sellers, function ($a, $b) {
            return $b['total_sales'] - $a['total_sales'];
        });

        // Display the top sellers based on the total sales
        $sellers = array_slice($sellers, 0);

        foreach ($sellers as $seller) {
            $seller_ids[] = $seller['user']->ID;
        }
    }
    return $seller_ids;
}


function get_newer_seller($count = -1)
{
    $seller_ids = array();
    // Query to get the latest registered users with the role 'seller'
    $args = array(
        'role'    => 'seller', // Modify if needed
        'number'  => $count,   // Get the specified number of users
        'orderby' => 'registered',
        'order'   => 'DESC',
    );

    $user_query = new WP_User_Query($args);
    // Check if users are found
    if (!empty($user_query->results)) {
        foreach ($user_query->results as $user) {
            $seller_ids[] = $user->ID;
        }
    }
    return $seller_ids;
}


function get_number_of_products_sold_by_user($user_id)
{
    // Get all orders with 'completed' status
    $orders = wc_get_orders(array(
        'limit'    => -1,  // Get all orders
        'status'   => 'completed',
        'return'   => 'ids', // Return only order IDs for better performance
    ));

    $total_products_sold = 0;

    // Loop through each order
    foreach ($orders as $order_id) {
        $order = wc_get_order($order_id);

        // Loop through each item in the order
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);

            // Check if the product author matches the given user ID
            if ($product && $product->get_post_data()->post_author == $user_id) {
                // Add the quantity sold for this product to the total
                $total_products_sold += $item->get_quantity();
            }
        }
    }
    // Format the number of products sold based on the value
    if ($total_products_sold >= 1000) {
        $number_of_products_sold = number_format($total_products_sold / 1000, 1) . 'k';
    } elseif ($total_products_sold > 0 && $total_products_sold < 1000) {
        $number_of_products_sold = $total_products_sold;
    } else {
        $number_of_products_sold = 'N/A';
    }


    return $number_of_products_sold;
}


function get_rating_of_seller($author_id)
{
    $args_reviews = array(
        'comment_approved' => 1,
        'post_id' => $author_id,
        'comment_type' => 'so_seller_review',
        'number' => 2, // Initial number of reviews to display
    );

    $reviews = get_comments($args_reviews);
    $rating = array();
    if ($reviews):
        foreach ($reviews as $review):
            $rating[] = (int)get_comment_meta($review->comment_ID, 'rating', true);
        endforeach;
        // Calculate the average rating
        $totalRatings = count($rating);
        $sumRatings = array_sum($rating);

        // Avoid division by zero if there are no ratings
        $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;

        // Optionally round the average rating to one decimal place
        $averageRating = round($averageRating, 1);
        return $averageRating;
    endif;
    return null;
}



//===========change woocomerce email template recipient======================

// Send "Processing Order" email to admin
add_filter('woocommerce_email_recipient_customer_processing_order', 'send_processing_order_email_to_admin', 10, 2);
function send_processing_order_email_to_admin($recipient, $order)
{
    $admin_email = get_option('admin_email');
    $recipient .= ', ' . $admin_email;
    return $recipient;
}

// Send "Order On-Hold" email to admin
add_filter('woocommerce_email_recipient_customer_on_hold_order', 'send_on_hold_order_email_to_admin', 10, 2);
function send_on_hold_order_email_to_admin($recipient, $order)
{
    $admin_email = get_option('admin_email');
    $recipient .= ', ' . $admin_email;
    return $recipient;
}

// Send "Completed Order" email to admin
add_filter('woocommerce_email_recipient_customer_completed_order', 'send_completed_order_email_to_admin', 10, 2);
function send_completed_order_email_to_admin($recipient, $order)
{
    $admin_email = get_option('admin_email');
    $recipient .= ', ' . $admin_email;
    return $recipient;
}
//===========change woocomerce email template recipient ends======================