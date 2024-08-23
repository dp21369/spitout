<?php

/**
 * Template part for displaying results in search pages
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
				<div class="card-body" id="post-<?php the_ID(); ?>">
					<div class="so-feed-profile-summary">
						<div class="d-flex align-items-center feed-profile-card-title">

							<?php the_title(sprintf('<h5 class="card-title mb-0"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h5>'); ?>
							<span class="ml-auto text-muted">
						Posted on: 	<?php echo get_the_date('j F Y', get_the_ID()) ?>
						 </span>

						</div>
					</div>

					<div class="card-text so-feed-card-body">
						<p> <?php the_excerpt(); ?> </p>
					</div>
					<div class="so-feed-card-body">
						<div class="so-feed-uploaded-img search-img">
							<?php spitout_post_thumbnail(); ?>
						</div>
						<a href="<?php the_permalink( );?>">
							<button class="d-flex so-feed-card-body">

								<h5 class="color-white">Read More</h5>
								<span class="so-custom-icon">
									<svg id="Layer_1" enable-background="new 0 0 100 100" height="512" viewBox="0 0 100 100" width="512" xmlns="http://www.w3.org/2000/svg">
										<path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z"></path>
									</svg>
								</span>

							</button>
						</a>
					</div>

				</div>
			</div>

		</div>

	</div>
</div>