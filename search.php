<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package spitout
 */

get_header();
?>
<div class="so-search-page-wrapper so-feed-new-container m-auto">
	<div class="so-news-feed">
		<div class="container">
			<h4 class="page-title">
				<?php
				/* translators: %s: search query. */
				printf(esc_html__('Search Results for: %s', 'spitout'), '<span>' . get_search_query() . '</span>');
				?>
			</h4>
		</div>
	</div>

	<?php

	$search_string = esc_attr(trim(get_query_var('s')));
	$users = new WP_User_Query(
		array(
			'search' => "*{$search_string}*",
			'search_columns' => array(
				'user_login',
				'user_nicename',
				'user_email',
				'user_url',
				'display_name',
			),
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'first_name',
					'value' => $search_string,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'last_name',
					'value' => $search_string,
					'compare' => 'LIKE'
				),
				array(
					'key' => 'nickname',
					'value' => $search_string,
					'compare' => 'LIKE',
				),
			)
		)
	);
	$users_found = $users->get_results();
	//var_dump($users_found);

	// if (have_posts() || !empty($users_found)):
	if (!empty($users_found)) :

		//$get_author_meta_data = spitout_get_seller_information($seller_id);
		echo '<div class="so-seller-contents">
        <section class="so-new-seller mt-3">
            <div class="container">
                <div class="row pt-4 pb-5">';
		foreach ($users_found as $key => $users) {
			# code...

			$seller_id = $users->data->ID;
			$attachment_id = (int) get_user_meta($seller_id, 'so_profile_img', true);
			$attachment_array = wp_get_attachment_image_src($attachment_id, 'medium'); // if not available than retrieves the original image
			if ($attachment_array) {
				$seller_img_url = $attachment_array[0]; // URL of the thumbnail image 
			}

			$seller_img = get_user_meta($seller_id, "so_profile_img", true);
			/* if the author avatar is empty it assign a placeholder image */
			if (empty($seller_img_url)) {
				$seller_img_url = get_stylesheet_directory_uri() . '/assets/img/user.png';
			} else {
				$seller_img_url = wp_get_attachment_url($seller_img);
			}
			$seller_data = get_userdata((int) $seller_id);
			$profile_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
			$seller_url = get_author_posts_url($seller_id);
			// $seller_img_url = wp_get_attachment_url($seller_img);
			$seller_location = get_user_meta($seller_id, "so_location", true);
			// $seller_active_status = get_user_meta($seller_id, 'user_status', true);
			$seller_active_status = get_user_meta(
				$seller_id,
				"cpmm_user_status",
				true
			);
			if ($seller_active_status == 'logged_in') {
				$active_status = 'online';
			} else {
				$active_status = 'offline';
			}
	?>

			<div class="col-md-6 col-sm-6 col-6 col-lg-4">
				<a href="<?php echo $seller_url; ?>">
					<div class="so-new-seller-desc">
						<figure>
							<i class="bi bi-circle-fill <?php echo $active_status; ?>"></i><!--This is to mark the seller as online -->
							<img src="<?php echo $seller_img_url ? $seller_img_url : $profile_avatar; ?>" alt="<?php echo $seller_data->display_name; ?>">
						</figure>
						<div class=" so-new-sellers-name">
							<h5 class="text-center m-0 p-2 d-flex">
								<?php echo $seller_data->display_name; ?>
								<?php if ((int) get_user_meta($seller_id, 'is_verified', true) == 1) { ?>
									<div class="profile-verify" title="verified">
										<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
											<path d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z" fill="#292D32" />
										</svg>
									</div>
								<?php } ?>
							</h5>
							<div class="d-flex justify-content-center">
								<p class="text-center"><span class="so-custom-icon icon-lightgray">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32" />
										</svg>
									</span>
								<div class="">
									<?php echo $seller_location ? $seller_location : "N/A"; ?>
								</div>
							</div>
							</p>
						</div>
					</div>
				</a>
			</div>

	<?php
		}
		echo '
    </div>
    </div>
        </section>
    </div>';
	else :
		echo '
	<div id="msform">
                <fieldset id="so-verify-failed">
                    <div class="form-card register-step-completed so-registration-failed">
                        <div class="row">
                            <div class="col-lg-12">

                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 48 48">
                                    <g fill="#ef4444">
                                        <path
                                            d="M31.424 38.177A15.93 15.93 0 0 1 24 40c-8.837 0-16-7.163-16-16S15.163 8 24 8s16 7.163 16 16c0 .167-.003.334-.008.5h2.001c.005-.166.007-.333.007-.5c0-9.941-8.059-18-18-18S6 14.059 6 24s8.059 18 18 18a17.92 17.92 0 0 0 8.379-2.065l-.954-1.758Z" />
                                        <path
                                            d="M13.743 23.35c-.12.738.381 1.445 1.064 1.883c.714.457 1.732.707 2.93.53a3.794 3.794 0 0 0 2.654-1.665c.504-.764.711-1.693.48-2.382a.5.5 0 0 0-.818-.203c-1.796 1.704-3.824 2.123-5.643 1.448a.5.5 0 0 0-.667.39Zm20.076 0c.119.738-.382 1.445-1.065 1.883c-.714.457-1.731.707-2.93.53a3.794 3.794 0 0 1-2.653-1.665c-.504-.764-.712-1.693-.48-2.382a.5.5 0 0 1 .818-.203c1.796 1.704 3.824 2.123 5.642 1.448a.5.5 0 0 1 .668.39ZM40 32a4 4 0 0 1-8 0c0-3.5 4-7 4-7s4 3.5 4 7Zm-19.2 1.6c1.6-2.133 4.8-2.133 6.4 0a1 1 0 0 0 1.6-1.2c-2.4-3.2-7.2-3.2-9.6 0a1 1 0 0 0 1.6 1.2Z" />
                                    </g>
                                </svg>
                            </div>
                            <div class="col-lg-12 reg-failed-title">
                                <i class="bi bi-x-circle"></i>
                                <h4>No results found</h4>
                            </div>
               
                        </div>
                    </div>
                </fieldset>
            </div> ';
	endif;
	echo '</div>';
	get_footer();
