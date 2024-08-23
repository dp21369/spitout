<?php

/**
 * Template Name: Register Failed page
 * @package spitout
 */
// Check if the user is not logged in
// if (is_user_logged_in()) {
//     // Redirect them to the wp-admin login page
//     wp_redirect(site_url());
//     exit;
// }

get_header(); ?>

<section class='so-ms-form so-feed-new-container'>
    <div class="container-fluid" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-lg-12">
                <div class=" px-0 pt-4 pb-0 mt-3 mb-3">

                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <div id="msform">
                                <fieldset id="so-verify-failed">
                                    <div class="form-card register-step-completed so-registration-failed">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50"
                                                    viewBox="0 0 48 48">
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
                                                <h4>Unfortunately</h4>
                                            </div>
                                            <div class="col-lg-12">
                                                <h5>The verification process has failed.</h5>
                                            </div>
                                            <div class="col-lg-12 go-back-reg-failed">
                                                <a href="<?php echo get_author_posts_url(get_current_user_id()); ?>">Go back</a>
                                            </div>

                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>