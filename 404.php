<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package spitout
 */

get_header();
?>

<main id="primary" class="site-main">
	<div class="container mt-4 p-0">
		<section class="error-404 not-found">
			<div class="card so-feed-card-wImage text-center pt-5 py-5">
				<div class="card-body so-feed-profile-summary">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e('Oops! 404 error.', 'spitout'); ?></h1>
					</header><!-- .page-header -->
				</div>
				<div class="card-body so-feed-card-body">
					<p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'spitout'); ?></p>
				</div>
			</div>
		</section><!-- .error-404 -->
	</div>


</main><!-- #main -->

<?php
get_footer();
