<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package spitout
 */

?>

<footer class="so-footer mt-5">
	<div class="container">
		<!-- Footer for larger screens -->
		<div class="row d-sm-flex so-footer-lg">
			<div class="col-sm-3 column so-footer-1">
				<ul>
					<?php
					$footer_menu_name = 'footer-menu-1';
					spitout_footer_menu_list($footer_menu_name);
					?>
			</div>
			</ul>
		</div>
		<div class="col-sm-3 column so-footer-2">
			<ul>
				<?php
				$footer_menu_name2 = 'footer-menu-2';
				spitout_footer_menu_list($footer_menu_name2);
				?>
		</div>
		</ul>
	</div>
	<div class="col-sm-3 column so-footer-3">
		<ul>
			<?php
			$footer_menu_name3 = 'footer-menu-3';
			spitout_footer_menu_list($footer_menu_name3);
			?>
	</div>
	</ul>
	</div>
	<div class="col-sm-3 column so-footer-4">
		<ul>
			<?php
			$footer_menu_name4 = 'footer-menu-4';
			spitout_footer_menu_list($footer_menu_name4);
			?>
		</ul>
	</div>
	</div>

	<!-- footer for logo and copyrights============================================ -->

	<div class="row align-items-center mb-5 mt-4  so-footer-bottom">
		<div class="col-md-3 so-footer-logo">
			<a class="navbar-brand" href="#"><img src="<?php echo get_option('footer_logo_field'); ?>" width="100px" alt="spitout footer logo">
			</a>
		</div>
		<div class="col-md-6 so-footer-copyrights">
			<p> &copy; SpitOut All rights reserved
				<?php echo date('Y'); ?> -
				<?php echo get_option('text_field'); ?>
			</p>
		</div>
		<div class="col-md-3 so-footer-icons">
			<ul class="list-inline social-icons">
				<li class="list-inline-item">
					<a href="#!" target="_blank" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
				</li>
				<li class="list-inline-item">
					<a href="#!" target="_blank" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
				</li>
				<li class="list-inline-item">
					<a href="#!" target="_blank" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
				</li>
				<li class="list-inline-item">
					<a href="#!" target="_blank" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
				</li>
			</ul>
		</div>
	</div>
	</div>
</footer>
<?php wp_footer(); ?>

</body>
</html>