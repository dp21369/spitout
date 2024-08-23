<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package spitout
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function spitout_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'spitout_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function spitout_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'spitout_pingback_header');


/**
 * The above code is a PHP function that is used to redirect users to different profile templates based
 * on their user role.
 *
 * @param $template description
 * @return $template
 */
if (!function_exists('so_seller_profile_template')) {
	function so_seller_profile_template($template)
	{
		// Get the current author's ID if available
		if (is_author()) {
			$author_id = get_queried_object_id();
			$author_roles = get_userdata($author_id)->roles;

			if (in_array('seller', $author_roles)) {
				// redirects to seller profile templates
				return get_template_directory() . '/seller-profile.php';
			} elseif (in_array('buyer', $author_roles)) {
				// redirects to buyer profile templates
				return get_template_directory() . '/buyer-profile.php';
			} elseif (in_array('administrator', $author_roles)) {
				// redirects to admin dashboard
				wp_redirect(admin_url());
				exit;
			}
		}

		return $template;
	}
	add_filter('template_include', 'so_seller_profile_template');
}


/**
 * A function to store the author's page views if the user is logged in and is the author.
 */
if (!function_exists('so_store_author_page_views')) {
	function so_store_author_page_views()
	{
		if (is_user_logged_in() && is_author()) {
			$current_user_id = get_current_user_id();
			$author_id = get_queried_object_id();

			if ($current_user_id !== $author_id) {
				$page_views = get_user_meta($author_id, 'author_page_views', true);
				if (empty($page_views)) {
					// If the 'author_page_views' meta key doesn't exist, create it
					update_user_meta($author_id, 'author_page_views', [$current_user_id]);
				} else {
					// If the 'author_page_views' meta key exists, update it
					if (!in_array($current_user_id, $page_views)) {
						$page_views[] = $current_user_id;
						update_user_meta($author_id, 'author_page_views', $page_views);
					}
				}
			}
		}
	}
	add_action('template_redirect', 'so_store_author_page_views');
}

/**
 * A function to handle the login redirect based on user roles and errors.
 *
 * @param mixed $redirect_to The redirect destination.
 * @param mixed $request The request object.
 * @param mixed $user The user object.
 * @return mixed The redirect destination based on user roles and errors.
 */
if (!function_exists('cpm_spitout_login_redirect')) {
	function cpm_spitout_login_redirect($redirect_to, $request, $user)
	{
		if (is_wp_error($user)) {
			//Login failed, find out why...
			$error_types = array_keys($user->errors);
			//Error type seems to be empty if none of the fields are filled out
			$error_type = 'both_empty';
			//Otherwise just get the first error (as far as I know there
			//will only ever be one)
			if (is_array($error_types) && !empty($error_types)) {
				$error_type = $error_types[0];
			}
			wp_redirect(home_url('/login') . "?login=failed&reason=" . $error_type);
			exit;
		} else {
			if (isset($user->roles) && is_array($user->roles)) {
				if (in_array('administrator', $user->roles)) {
					$redirect_to = admin_url();
				} else if (in_array('seller', $user->roles)) {
					$custom_my_spitout_url = home_url('/my-spitout');
					$redirect_to = $custom_my_spitout_url;
				} else if (in_array('buyer', $user->roles)) {
					$custom_buyers_url = home_url('/feeds');
					$redirect_to = $custom_buyers_url;
				} else {
					$redirect_to = admin_url();
				}
			}
			return $redirect_to;
		}
	}
	add_filter('login_redirect', 'cpm_spitout_login_redirect', 10, 3);
}



/**
 * Redirects the WordPress login URL to a custom login URL with optional query parameters.
 *
 * @param string $login_url The original WordPress login URL.
 * @param string $redirect The redirection URL, if any.
 * @param bool $force_reauth Whether to force reauthentication.
 * @return string The custom login URL with optional query parameters.
 */
if (!function_exists('redirect_wp_login_to_custom_login')) {
	function redirect_wp_login_to_custom_login($login_url, $redirect, $force_reauth)
	{
		$custom_login_url = home_url('/login');
		if (!empty($redirect)) {
			$custom_login_url = add_query_arg('redirect_to', urlencode($redirect), $custom_login_url);
		}
		if ($force_reauth) {
			$custom_login_url = add_query_arg('reauth', '1', $custom_login_url);
		}
		return $custom_login_url;
	}
	add_filter('login_url', 'redirect_wp_login_to_custom_login', 10, 3);
}

/**
 * Disables the admin bar for users who do not have the 'activate_plugins' capability.
 *
 * @throws Some_Exception_Class description of exception
 */
if (!function_exists('spitout_disable_admin_bar')) {
	function spitout_disable_admin_bar()
	{
		if (!current_user_can('activate_plugins')) {
			add_filter('show_admin_bar', '__return_false');
			add_action('admin_print_scripts-profile.php', 'spitout_hide_admin_bar_settings');
		}
	}
	add_action('init', 'spitout_disable_admin_bar', 9);
}

/**
 * Function to hide the admin bar settings by echoing the necessary CSS styles.
 */
if (!function_exists('spitout_hide_admin_bar_settings')) {
	function spitout_hide_admin_bar_settings()
	{
		echo '<style type="text/css">
	  .show-admin-bar {
		  display: none;
	  }
	</style>';
	}
}


/**
 * The function adds specific body classes based on the page slug and user login status.
 * 
 * @param classes The "classes" parameter is an array that contains the current body classes of the
 * page.
 * 
 * @return the modified array of body classes.
 */
if (!function_exists('so_add_body_class_by_slug')) {
	function so_add_body_class_by_slug($classes)
	{
		if (is_page('seller') && !is_user_logged_in()) {
			$classes[] = 'seller-not-logged-in';
		} elseif (is_page('contact') && !is_user_logged_in()) {
			$classes[] = 'contact-not-logged-in';
		}
		return $classes;
	}
	add_filter('body_class', 'so_add_body_class_by_slug');
}


/**
 * Redirects to the product author page if the current page is a single product.
 *
 * @throws Some_Exception_Class description of exception
 */
if (!function_exists('spitout_redirect_to_product_author_page')) {
	function spitout_redirect_to_product_author_page()
	{
		if (is_singular('product')) {
			$product_id = get_the_ID();
			$product_author = get_post_field('post_author', $product_id);
			$author_url = get_author_posts_url($product_author);
			wp_safe_redirect($author_url);
			exit;
		}
	}
	add_action('template_redirect', 'spitout_redirect_to_product_author_page');
}



/**
 * Load Plyr library in the header of the page if it is a feed or single author page.
 *
 * Plyr is a lightweight JavaScript library for embedding<br>
 * video players. It supports HTML5 video, YouTube, Vimeo, SoundCloud,<br>
 * Wistia, DailyMotion, Mixcloud, Kickstarter, and File API.
 *
 * @see https://plyr.io/
 */
if (!function_exists('spitout_plyr_load_library_head')) {
	function spitout_plyr_load_library_head()
	{
		if (is_page('feeds') || is_author()) {
			?>
			<!-- Add link to Plyr CSS stylesheet in the header -->
			<link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css">
			<!-- Add script tag to load Plyr JavaScript library in the header -->
			<script src="https://cdn.plyr.io/3.6.2/plyr.js"></script>
			<?php
		}
	}
	add_action('wp_head', 'spitout_plyr_load_library_head');
}
/**
 * This code checks if the current page is a feed or a single author page. 
 * If it is, it adds a script tag to the footer of the page that initializes the Plyr library on all videos with the class "plyr".
 */
if (!function_exists('spitout_plyr_load_library_footer')) {
	function spitout_plyr_load_library_footer()
	{
		if (is_page('feeds') || is_author()) {
			?>
			<script>
				jQuery(document).ready(function() {
					let plyrArr = [];
					// Select all video elements and map them to Plyr instances
					const players = Array.from(document.querySelectorAll('.plyr')).map((p,index) => {
						let currPlyr = new Plyr(p,{controls: ['play-large', 'mute']});
						plyrArr.push(currPlyr);

						const observer = createIntersectionObserver(currPlyr);
						observer.observe(currPlyr.elements.container);

						if(index == 0){
							currPlyr.muted = true;
							currPlyr.play();
							currPlyr.volume = 100;
						}

						
					});

					plyrArr.forEach(player => {
						player.on('play', event => {
							const instance = event.detail.plyr;
							plyrArr.forEach(otherPlayer => {
							if (otherPlayer!== instance) {
								otherPlayer.pause();
							}
							});
						});
					});

					// Function to create an Intersection Observer for a single player
					function createIntersectionObserver(player) {
						return new IntersectionObserver((entries) => {
							entries.forEach(entry => {
								if (entry.isIntersecting) {
									player.play();
								} else {
									player.pause();
								}
							});
						}, {
							threshold: 1 // Adjust as needed
						});
					}
				});
			</script>

			<?php
		}
	}
	add_action('wp_footer', 'spitout_plyr_load_library_footer');
}


/**
 * Display the error template.
 *
 * @param string $text The text to be displayed in the error template.
 * @return void
 */
if (!function_exists('so_error_template_display')) {

	function so_error_template_display($text)
	{
		echo '<div id="msform">
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
					<h4>Nothing to show</h4>
				</div>
				<div class="col-lg-12">
					<h5>' . $text . '</h5>
				</div>
			</div>
		</div>
	</fieldset>
  </div>';
	}
}


/**
 * Redirects non-logged-in users to the "my-spitout" page on the account page.
 *
 * This function checks if the current page is the account page using the 
 * `is_account_page()` function. If it is, it redirects the user to the 
 * "my-spitout" page using the `wp_redirect()` function and exits the script 
 * using the `exit` keyword.
 *
 * @throws None
 * @return void
 */
// if (!function_exists('spitout_redirect_non_logged_in_users_my_account')) {
// 	function spitout_redirect_non_logged_in_users_my_account()
// 	{
// 		if (is_account_page() && is_user_logged_in()) {
// 			wp_redirect(home_url('/my-spitout'));
// 			exit;
// 		}
// 	}
// 	add_action('template_redirect', 'spitout_redirect_non_logged_in_users_my_account');
// }

function custom_redirect_my_account_page() {
    // Check if the current page is the WooCommerce my-account page
    if (is_account_page() && !strpos($_SERVER['REQUEST_URI'], 'woo-wallet')) {
        // Redirect to the desired URL
        wp_redirect(home_url('/my-spitout/'));
        exit; // Make sure to exit after the redirect
    }
}
add_action('template_redirect', 'custom_redirect_my_account_page');


// forgot password
function so_forgotPsw($lostpassword_url, $redirect)
{
	return home_url("/forgot-password");
}
add_filter('lostpassword_url', 'so_forgotPsw', 10, 2);