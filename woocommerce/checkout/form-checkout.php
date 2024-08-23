<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$cart = WC()->cart;

$cart_items = $cart->get_cart();

// Check if the cart is not empty

// Get the first cart item
$first_cart_item = reset($cart_items);

// Get the product ID of the first cart item
$first_product_id = $first_cart_item['product_id'];

$post_id = $first_product_id;
$author_id = get_post_field('post_author', $post_id);
$get_author_meta_data = spitout_get_seller_information($author_id);

echo "<div class='so-woocommerce-container'>"; ?>
<div class="woo-title">
	<h3>
		<a href="javascript:void(0);" onclick="window.history.back();">
			<span class="so-custom-icon icon-lightgray">
				<svg id="Layer_1" enable-background="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
					<path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
				</svg>
			</span>
			Order </a>
	</h3>
</div>
<div class="woo-note">
	<span class="so-custom-icon icon-yellow">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM11.25 8C11.25 7.59 11.59 7.25 12 7.25C12.41 7.25 12.75 7.59 12.75 8V13C12.75 13.41 12.41 13.75 12 13.75C11.59 13.75 11.25 13.41 11.25 13V8ZM12.92 16.38C12.87 16.51 12.8 16.61 12.71 16.71C12.61 16.8 12.5 16.87 12.38 16.92C12.26 16.97 12.13 17 12 17C11.87 17 11.74 16.97 11.62 16.92C11.5 16.87 11.39 16.8 11.29 16.71C11.2 16.61 11.13 16.51 11.08 16.38C11.03 16.26 11 16.13 11 16C11 15.87 11.03 15.74 11.08 15.62C11.13 15.5 11.2 15.39 11.29 15.29C11.39 15.2 11.5 15.13 11.62 15.08C11.86 14.98 12.14 14.98 12.38 15.08C12.5 15.13 12.61 15.2 12.71 15.29C12.8 15.39 12.87 15.5 12.92 15.62C12.97 15.74 13 15.87 13 16C13 16.13 12.97 16.26 12.92 16.38Z" fill="#292D32" />
		</svg>

	</span>
	<p>The website is not responsible for the purchase product.</p>
</div>
<?php

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

if ($product_found == false) { ?>
<div class="seller-wrapper">
	<div class="seller-title">
		<h3>Seller</h3>
	</div>
	<div class="seller-content">
		<div class="seller-dp">
			<figure>
				<i class="bi bi-circle-fill <?php echo $get_author_meta_data['seller_online']; ?>"></i><!--This is to mark the seller as online -->
				<img src="<?php echo $get_author_meta_data['seller_profile_img']; ?>" alt="<?php echo $get_author_meta_data['seller_display_name']; ?>">
			</figure>
		</div>
		<div class="seller-desc">
			<h2><a href="<?php echo $get_author_meta_data['seller_url']; ?>">
					<?php echo $get_author_meta_data['seller_display_name']; ?>
				</a> <span class="so-custom-icon icon-blue">

					<!-- <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M21.5609 10.7386L20.2009 9.15859C19.9409 8.85859 19.7309 8.29859 19.7309 7.89859V6.19859C19.7309 5.13859 18.8609 4.26859 17.8009 4.26859H16.1009C15.7109 4.26859 15.1409 4.05859 14.8409 3.79859L13.2609 2.43859C12.5709 1.84859 11.4409 1.84859 10.7409 2.43859L9.17086 3.80859C8.87086 4.05859 8.30086 4.26859 7.91086 4.26859H6.18086C5.12086 4.26859 4.25086 5.13859 4.25086 6.19859V7.90859C4.25086 8.29859 4.04086 8.85859 3.79086 9.15859L2.44086 10.7486C1.86086 11.4386 1.86086 12.5586 2.44086 13.2486L3.79086 14.8386C4.04086 15.1386 4.25086 15.6986 4.25086 16.0886V17.7986C4.25086 18.8586 5.12086 19.7286 6.18086 19.7286H7.91086C8.30086 19.7286 8.87086 19.9386 9.17086 20.1986L10.7509 21.5586C11.4409 22.1486 12.5709 22.1486 13.2709 21.5586L14.8509 20.1986C15.1509 19.9386 15.7109 19.7286 16.1109 19.7286H17.8109C18.8709 19.7286 19.7409 18.8586 19.7409 17.7986V16.0986C19.7409 15.7086 19.9509 15.1386 20.2109 14.8386L21.5709 13.2586C22.1509 12.5686 22.1509 11.4286 21.5609 10.7386ZM16.1609 10.1086L11.3309 14.9386C11.1909 15.0786 11.0009 15.1586 10.8009 15.1586C10.6009 15.1586 10.4109 15.0786 10.2709 14.9386L7.85086 12.5186C7.56086 12.2286 7.56086 11.7486 7.85086 11.4586C8.14086 11.1686 8.62086 11.1686 8.91086 11.4586L10.8009 13.3486L15.1009 9.04859C15.3909 8.75859 15.8709 8.75859 16.1609 9.04859C16.4509 9.33859 16.4509 9.81859 16.1609 10.1086Z" fill="#292D32" />
					</svg> -->

				</span></h2>
			<div class="seller-location">
				<span class="so-custom-icon icon-lightgray">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M20.6211 8.45C19.5711 3.83 15.5411 1.75 12.0011 1.75C12.0011 1.75 12.0011 1.75 11.9911 1.75C8.46107 1.75 4.42107 3.82 3.37107 8.44C2.20107 13.6 5.36107 17.97 8.22107 20.72C9.28107 21.74 10.6411 22.25 12.0011 22.25C13.3611 22.25 14.7211 21.74 15.7711 20.72C18.6311 17.97 21.7911 13.61 20.6211 8.45ZM12.0011 13.46C10.2611 13.46 8.85107 12.05 8.85107 10.31C8.85107 8.57 10.2611 7.16 12.0011 7.16C13.7411 7.16 15.1511 8.57 15.1511 10.31C15.1511 12.05 13.7411 13.46 12.0011 13.46Z" fill="#292D32"></path>
					</svg>
				</span>
				<p>
					<?php echo $get_author_meta_data['seller_location']; ?>
				</p>
			</div>
		</div>
	</div>

</div>
<?php } ?>
<div class="order-wrapper seller-wrapper">
	<div class="seller-title">
		<h3>Order</h3>
	</div>

	<table class="responsive table so-order-table">

		<?php


		// var_dump($cart);

		// Get the cart total
		$cart_total = $cart->get_cart_total();




		$product_price_symbol = get_woocommerce_currency_symbol();

		foreach (WC()->cart->get_cart() as $cart_item) {
			$product = $cart_item['data'];
			if (!empty($product)) {
				// $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );
				$product_name = $product->get_name();
				$product_id = $product->get_id();
				$product_price = $product->get_price();
				// $product_quantity = $product->get_price();

				$product_icon_image = get_stylesheet_directory_uri() . "/assets/img/saliva.png";

				if ($cart_item['product_id'] === $product_id) {
					$product_quantity = $cart_item['quantity'];
					$final_price = $product_quantity * $product_price;
				}

				echo '
				<tr>
			<td>
				<img src="' . $product_icon_image . '" />
				<h3 class="' . $product_id . '">' . $product_name . '</h3> <em>x' . $product_quantity . '</em>
			</td>
			<td>
				<h3>' . $product_price_symbol . number_format($final_price,6) . '</h3>
			</td>
		</tr>
			
			';
			}
		}
		?>
		<tr>
			<td>
				<h3>Total</h3>
			</td>
			<td>
				<h3>
					<?php echo $cart_total; ?>
				</h3>
			</td>
		</tr>
	</table>
</div>
<?php
do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

?>
<form name="checkout" method="post" class="checkout woocommerce-checkout so-checkout-form-details" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action('woocommerce_checkout_billing'); ?>
			</div>

			<div class="col-2">
				<?php do_action('woocommerce_checkout_shipping'); ?>
			</div>
		</div>

		<?php do_action('woocommerce_checkout_after_customer_details'); ?>

	<?php endif; ?>

	<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

	<h3 id="order_review_heading">
		<?php esc_html_e('Your order', 'woocommerce'); ?>
	</h3>

	<?php do_action('woocommerce_checkout_before_order_review'); ?>

	<div id="order_review" class="woocommerce-checkout-review-order spitout-checkout-order-review">
		<?php do_action('woocommerce_checkout_order_review'); ?>
	</div>

	<?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
</div>