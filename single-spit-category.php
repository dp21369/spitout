<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package spitout
 */

get_header();


function CheckSelleridExistsInMeta($id, $objects)
{
    foreach ($objects as $object) {
        $meta_array = unserialize($object->meta_value);
        if (in_array($id, $meta_array)) {
            return true;
        }
    }
    return false;
}

?>

<div class="so-seller-contents">
    <section class="so-new-seller mt-3">
        <div class="container">
            <div class="sellers-page-heading mt-5">
                <h4>
                    <?php the_title(); ?>
                </h4>
            </div>
            <div class="row pt-4 pb-5">
                <?php
                $get_catergory_id = get_the_ID();

                if ($get_catergory_id) {

                    global $wpdb;

                    $meta_key = 'so_category'; // Replace with your actual meta key
                    $target_id = $get_catergory_id; // ID you want to check within the serialized array
                
                    $query = $wpdb->prepare(
                        "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = %s",
                        $meta_key
                    );

                    $results = $wpdb->get_results($query);
                    if (CheckSelleridExistsInMeta($get_catergory_id, $results)) {
                        if ($results) {

                            foreach ($results as $result) {
                                $serialized_array = unserialize($result->meta_value);
                                $final_array = array_unique($serialized_array);


                                if (is_array($serialized_array) && in_array($target_id, $serialized_array)) {

                                    $get_new_seller_id = $result->user_id;

                                    if (!empty($get_new_seller_id)) {
                                        $get_seller_information = spitout_get_seller_information($get_new_seller_id);
                                        if (!empty($get_seller_information)) {

                                            $seller_display_name = $get_seller_information['seller_display_name'];
                                            $seller_final_profile_img = $get_seller_information['seller_profile_img'];
                                            $seller_final_location = $get_seller_information['seller_location'];
                                            $seller_url = $get_seller_information['seller_url'];
                                            $user_meta = get_userdata($get_new_seller_id);
                                            $user_roles = $user_meta->roles;
                                            if (in_array('seller', $user_roles, true)) {
                                                echo ' <div class="col-md-4 col-sm-4 col-6  col-lg-2">
                    <a href="' . $seller_url . '">
                        <div class="so-new-seller-desc">
                            <figure>
                                <img src="' . $seller_final_profile_img . '" alt="' . $seller_display_name . '">
                            </figure>
                            <div class="so-new-sellers-name">
                                <h5 class="text-center m-0 p-2">' . $seller_display_name . '</h5>';
                                                if ((int) get_user_meta($get_new_seller_id, 'is_verified', true) == 1) { ?>
                                                    <div class="profile-verify" title="verified">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z"
                                                                fill="#292D32" />
                                                        </svg>
                                                    </div>
                                                <?php }


                                                echo '<p class="text-center"> <i class="bi bi-geo-alt-fill"></i>' . $seller_final_location . '</p>
                            </div>
                        </div>
                    </a>
                </div>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        ?>
                        <div class="m-auto spitout_no_sellers_msg">
                            <?php
                            //echo "ID $get_catergory_id does not exist in meta_value arrays.";
                            so_error_template_display('No seller found on this category');
                            ?>
                        </div>
                        <?php
                    }
                }


                ?>


            </div>
        </div>
    </section>
</div>

<?php


get_footer();
