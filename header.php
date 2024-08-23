<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package spitout
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<meta name="description" content="Discover the finest selection of premium spit products at [Your Website Name]. From flavorful spices to top-quality cooking accessories, our store offers everything you need to elevate your grilling and culinary experience. Explore our wide range of savory and mouthwatering options today!">
</head>
<?php
$spit_page_selected_color = get_post_meta(get_the_ID(), 'spit_page_selected_color', true);
if (($spit_page_selected_color)) {
	echo '<style>
	body {
		background: ' . $spit_page_selected_color . ' !important;
	}
</style>';
} else {
	echo '<style>
	body {
		background: #F4F4F4 !important;
	}
</style>';
}


//set current exchange rate in cookie
if (!isset($_COOKIE['curr-exchange-rate'])) { 
    
    ?>
    <script>
		jQuery(document).ready(function () {
			function createCookies(name, value, minutes) {
				var expires;
				if (minutes) {
					var date = new Date();
					date.setTime(date.getTime() + (minutes * 60 * 1000));
					expires = "; expires=" + date.toGMTString();
				} else {
					expires = "";
				}
				document.cookie = name + "=" + value + expires + "; path=/";
			}
			createCookies("curr-exchange-rate", jQuery('.curr-exchange-rate').val(), 10);

		});
    </script>

    <?php 
    $_COOKIE['curr-exchange-rate'] = spitout_get_currency_exchange_btc_usd_rate();
}
?>



<body <?php body_class(); ?>>

	<!-- Navigation bar -->

	<nav class="navbar navbar-expand-lg so-navigation container-fluid">
		<input type="hidden" class="curr-home-url" value="<?php echo home_url('/'); ?>">
		<input type="hidden" class="curr-exchange-rate" value="<?php echo spitout_get_currency_exchange_btc_usd_rate(); ?>">
		<div class="container">

			<a class="navbar-brand so-logo-desktop" href="<?php echo esc_url(home_url('/')); ?>">
				<img src="<?php echo get_option('desktop_logo_field'); ?>" width="100px" alt="spitout-header-logo">
			</a>

			<div class="so-iconlogo so-logo-mobile">
				<a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand" rel="home"><img src="<?php echo is_user_logged_in() ? get_option('mobile_logo_field') : get_option('desktop_logo_field'); ?> " alt="spitout header logo" /></a>
			</div>
			<button class="navbar-toggler so-nav-toggle" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon d-flex"><i class="bi bi-list"></i>
					<h5>Menu</h5>
				</span>
			</button>




			<div class="collapse navbar-collapse" id="navbarNav">
				<?php

				$author_id = get_current_user_id();
				if ($author_id) {
					$user_data = get_userdata($author_id);
					if (is_object($user_data)) {
						$author_roles = $user_data->roles;
					}
				}

				if (!is_user_logged_in() || (in_array('administrator', $author_roles))) {
					wp_nav_menu(
						array(
							'theme_location' => 'main-menu',
							'container' => 'ul',
							'container_id' => 'navbar',
							'container_class' => 'navbar-collapse collapse',
							'menu_class' => 'navbar-nav mx-auto so-navigation-menu',
							'depth' => 0,
							'fallback_cb' => false,
						)
					);
				}


				/* seller logged in menu */

				if (is_user_logged_in() && (in_array('seller', $author_roles))) {
					wp_nav_menu(
						array(
							'theme_location' => 'loggedin-menu-seller',
							'container' => 'ul',
							'container_id' => 'navbar',
							'container_class' => 'navbar-collapse collapse',
							'menu_class' => 'navbar-nav mx-auto so-navigation-menu',
							'depth' => 0,
							'fallback_cb' => false,
						)
					);
				}
				/* Buyer logged in menu */
				if (is_user_logged_in() && (in_array('buyer', $author_roles))) {
					wp_nav_menu(
						array(
							'theme_location' => 'loggedin-menu-buyer',
							'container' => 'ul',
							'container_id' => 'navbar',
							'container_class' => 'navbar-collapse collapse',
							'menu_class' => 'navbar-nav mx-auto so-navigation-menu',
							'depth' => 0,
							'fallback_cb' => false,
						)
					);
				}

				?>

			</div>
			<div class="d-flex nav-rightside-icons spitout-currency-icons">
				<div class="spitout-currency-switcher">
					<?php
					if (is_user_logged_in()) {
						echo do_shortcode('[woo_multi_currency_plain_horizontal]');
					}

					?>
				</div>

				<div class="so-navbar-search-button">
					<?php echo get_search_form(); ?>
				</div>

				<?php
				if (is_user_logged_in() && (in_array('seller', $author_roles)) || is_user_logged_in() && (in_array('buyer', $author_roles))) {
					$notifications = notifications_display(5, 'unseen');
					$notifications_count = count(notifications_display(-1, 'unseen'));
				?>
					<div class="header-sideicons so-notification dropdown">
						<div class="dropdown-toggle" id="nfDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php
							if ($notifications) { ?>
								<span class="notification-count">
									<?php echo $notifications_count; ?>
								</span>
							<?php } ?>
							<i class="bi bi-bell"></i>
						</div>
						<div class="dropdown-menu nf-dropdown" aria-labelledby="nfDropdown">
							<div class="nf-arrow-up"></div>
							<div class="notification-dropdown-title">
								<h4>Notification</h4>
								<?php if ($notifications) { ?>
									<span id="ntfy-count">
										<?php echo $notifications_count; ?>
									</span>
								<?php } ?>

								<!-- clear all notificaitons -->
								<div class="delete-ntfy">
									<!-- delete all notifications -->
									<i class="bi bi-x-circle notification-action delete-ntfy-icon" data-action="delete" data-toggle="tooltip" data-placement="top" title="Delete all notifications"></i>
								</div>
							</div>


							<ul class="header-notifications">
								<?php if ($notifications) {
									foreach ($notifications as $notification) {
										$sender_display_name = $notification['sender_display_name'];
										$sender_profile_img_url = $notification['sender_profile_img_url'];
										$message = $notification['message'];
										$elapsed = $notification['elapsed_time'];
										$notification_url = $notification['notification_url'];
										$notification_id = $notification['id'];

										echo '<li>
											<a href="' . $notification_url . '" class="so-notification-link" data-notification-id= "' . $notification_id . '">
												<div class="nf-thumbnail"><img src="' . $sender_profile_img_url . '" />
												</div>
												<div class=" nf-content">
													<h3>
														' . $sender_display_name . '
													</h3>
													<p>
														' . $message . '
													</p>
												</div>
												<div class="nf-time">
													<p>
														' . $elapsed . '
													</p>
												</div>
											</a>
											<div class="delete-ntfy">
												<!-- delete individual notification -->
												<i class="bi bi-x-circle notification-action delete-ntfy-icon" data-notification-id= "' . $notification_id . '" data-action="ind-delete" data-toggle="tooltip" data-placement="top" title="Dismiss"></i>
											</div>
										</li>';
									}
								} else {
									echo "No new notifications found";
								} ?>
							</ul>
							<?php if ($notifications_count > 5) { ?>
								<div class="nf-all">
									<h5><a href="<?php echo home_url('/notification'); ?>"> View all notifications</a></h5>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
				<?php
				if ((is_user_logged_in() && (in_array('seller', $author_roles))) || (is_user_logged_in() && (in_array('buyer', $author_roles)))) {
				?>
					<a href="<?php echo esc_url(home_url('/chat')); ?>" aria-label="Chat">
						<div class="header-sideicons so-settings nav-msg-btn">
							<!-- <i class="bi bi-gear"></i> -->
							<i class="bi bi-dot new-msg-icon"></i>
							<!-- <p>1+</p> -->
							<i class="bi bi-chat-dots"></i>
						</div>
					</a>
				<?php } ?>
				<?php if (!is_user_logged_in()) { ?>
					<a href="<?php echo home_url('/login'); ?>" aria-label="Login">
						<button class="btn text-light so-login-btn"><span class="so-login-btn-span">Log in</span>
							<i class="bi bi-person-fill"></i>
						</button>
					</a>
				<?php } else if (is_user_logged_in()) {
					$new_seller_id = get_current_user_id();

					$get_seller_information = spitout_get_seller_information($new_seller_id);
					$seller_display_name = $get_seller_information['seller_display_name'];
					// $seller_final_profile_img = $get_seller_information['seller_profile_img'];


					$attachment_id = (int) get_user_meta($new_seller_id, 'so_profile_img', true);
					$attachment_array = wp_get_attachment_image_src($attachment_id, 'post-thumbnail'); // if not available than retrieves the original image
					if ($attachment_array) {
						$author_avatar = $attachment_array[0]; // URL of the thumbnail image 
					}


					/* if the author avatar is empty it assign a placeholder image */
					if (empty($author_avatar)) {
						$author_avatar = get_stylesheet_directory_uri() . '/assets/img/user.png';
					}


					$seller_final_location = $get_seller_information['seller_location'];
					$seller_url = $get_seller_information['seller_url']; ?>
					<div class="dropdown">
						<div class="header-sideicons dp-icons dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img src="<?php echo $author_avatar; ?>" title="<?php echo $seller_display_name; ?>" />
						</div>
						<div class="dropdown-menu nf-dropdown dp-dropdown" aria-labelledby="dropdownMenuLink">
							<div class="nf-arrow-up"></div>
							<ul>
								<li>
									<h3><a href="<?php echo $seller_url; ?>">Profile </a></h3>
								</li>
								<li>
									<h3><a href="<?php echo esc_url(home_url('/settings')); ?>">Settings </a></h3>
								</li>
								<li>
									<h3><a class="so-header-logout" href="<?php echo wp_logout_url(); ?>">Logout
										</a></h3>
								</li>
							</ul>
						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</nav>


	<!-- navigation end -->