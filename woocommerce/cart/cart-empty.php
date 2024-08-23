<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');

if (wc_get_page_id('shop') > 0): ?>
	<div class="so-woocommerce-container">
		<div class="woo-title">
			<h3>
				<a href="javascript:void(0);" onclick="window.history.back();">
					<span class="so-custom-icon icon-lightgray">
						<svg id="Layer_1" enable-background="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512"
							xmlns="http://www.w3.org/2000/svg">
							<path
								d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z" />
						</svg>
					</span>
					Empty Cart </a>
			</h3>
		</div>
		<div class="woo-note">
			<span class="so-custom-icon icon-yellow">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path
						d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM11.25 8C11.25 7.59 11.59 7.25 12 7.25C12.41 7.25 12.75 7.59 12.75 8V13C12.75 13.41 12.41 13.75 12 13.75C11.59 13.75 11.25 13.41 11.25 13V8ZM12.92 16.38C12.87 16.51 12.8 16.61 12.71 16.71C12.61 16.8 12.5 16.87 12.38 16.92C12.26 16.97 12.13 17 12 17C11.87 17 11.74 16.97 11.62 16.92C11.5 16.87 11.39 16.8 11.29 16.71C11.2 16.61 11.13 16.51 11.08 16.38C11.03 16.26 11 16.13 11 16C11 15.87 11.03 15.74 11.08 15.62C11.13 15.5 11.2 15.39 11.29 15.29C11.39 15.2 11.5 15.13 11.62 15.08C11.86 14.98 12.14 14.98 12.38 15.08C12.5 15.13 12.61 15.2 12.71 15.29C12.8 15.39 12.87 15.5 12.92 15.62C12.97 15.74 13 15.87 13 16C13 16.13 12.97 16.26 12.92 16.38Z"
						fill="#292D32" />
				</svg>

			</span>
			<p>Your cart is currently empty.</p>
		</div>
		<div class="seller-wrapper text-center">
			<button class="d-flex" onclick="location.href = '<?php echo home_url(); ?>/seller'">
				<h5 class="color-white">Sellers</h5><span class="so-custom-icon">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path
							d="M19.9597 8.95844C19.2897 8.21844 18.2797 7.78844 16.8797 7.63844V6.87844C16.8797 5.50844 16.2997 4.18844 15.2797 3.26844C14.2497 2.32844 12.9097 1.88844 11.5197 2.01844C9.12975 2.24844 7.11975 4.55844 7.11975 7.05844V7.63844C5.71975 7.78844 4.70975 8.21844 4.03975 8.95844C3.06975 10.0384 3.09975 11.4784 3.20975 12.4784L3.90975 18.0484C4.11975 19.9984 4.90975 21.9984 9.20975 21.9984H14.7897C19.0897 21.9984 19.8797 19.9984 20.0897 18.0584L20.7897 12.4684C20.8997 11.4784 20.9197 10.0384 19.9597 8.95844ZM11.6597 3.40844C12.6597 3.31844 13.6097 3.62844 14.3497 4.29844C15.0797 4.95844 15.4897 5.89844 15.4897 6.87844V7.57844H8.50975V7.05844C8.50975 5.27844 9.97975 3.56844 11.6597 3.40844ZM8.41975 13.1484H8.40975C7.85975 13.1484 7.40975 12.6984 7.40975 12.1484C7.40975 11.5984 7.85975 11.1484 8.40975 11.1484C8.96975 11.1484 9.41975 11.5984 9.41975 12.1484C9.41975 12.6984 8.96975 13.1484 8.41975 13.1484ZM15.4197 13.1484H15.4097C14.8597 13.1484 14.4097 12.6984 14.4097 12.1484C14.4097 11.5984 14.8597 11.1484 15.4097 11.1484C15.9697 11.1484 16.4197 11.5984 16.4197 12.1484C16.4197 12.6984 15.9697 13.1484 15.4197 13.1484Z"
							fill="#292D32"></path>
					</svg>

				</span>
			</button>
		</div>

	</div>
<?php endif; ?>