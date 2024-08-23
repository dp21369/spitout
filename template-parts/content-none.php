<?php

/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package spitout
 */

?>
<div class="so-news-feed">
	<div class="container so-feed-new-container">
		<div class="container mt-4 p-0">
			<div class="card so-feed-card-wImage">
				<div class="card-body">
					<section class="no-results not-found">
						<header class="page-header text-center">
							<h1 class="page-title"><?php esc_html_e('Nothing Found', 'spitout'); ?></h1>
						</header><!-- .page-header -->

						<div class="page-content">
							<?php
							if (is_home() && current_user_can('publish_posts')) :

								printf(
									'<p>' . wp_kses(
										/* translators: 1: link to WP admin new post page. */
										__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'spitout'),
										array(
											'a' => array(
												'href' => array(),
											),
										)
									) . '</p>',
									esc_url(admin_url('post-new.php'))
								);

							elseif (is_search()) :
							?>

								<p class="so-feed-card-body"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'spitout'); ?></p>
							<?php
								//get_search_form();

							else :
							?>

								<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'spitout'); ?></p>
							<?php
							//	get_search_form();
							endif;
							?>
						</div><!-- .page-content -->
					</section><!-- .no-results -->
				</div>
			</div>

		</div>
	</div>
</div>