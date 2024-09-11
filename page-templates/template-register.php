<?php

/**
 * Template Name: Register page
 * @package spitout
 */

get_header();

$verified = 'verification_not_started';
//if user already verfied by idenfy then redirect to my spitout page
$current_user_id = get_current_user_id();

if (is_user_logged_in()) {

?>
    <script>
        window.location.href = '<?php echo get_author_posts_url($current_user_id); ?>';
    </script>
<?php
}

// if ((int)get_user_meta($current_user_id, 'is_verified', true) == 1) {
//     $verified = 'is_verified';
// } else {
//         //got to the verification tab directly and hide the create account tab

//         if (get_user_meta($current_user_id, 'is_verified', true) == false || get_user_meta($current_user_id, 'is_verified', true) == '' || empty(get_user_meta($current_user_id, 'is_verified', true))) {

//             $verified = 'verification_not_started';
//         } else {

//             $verified = 'not_verified';
//         }
//     }
// }

$policy_page_id = (int) get_option('wp_page_for_privacy_policy');

$months = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
];

// with post object by id
$post = get_post($policy_page_id); // specific post
$the_content = apply_filters('the_content', $post->post_content);
?>
<section class='so-ms-form so-feed-new-container'>
    <!-- MultiStep Form -->
    <div class="container-fluid" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-lg-12">
                <div class=" px-0 pt-4 pb-0 mt-3 mb-3">

                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="so-register-steps-bar">
                                    <li class="active" id="account">
                                        <h6><i class="bi bi-arrow-right-circle-fill"></i> Create Account</h6>
                                    </li>
                                </ul>
                                <!-- fieldsets -->
                                <fieldset id="so-create-acc">
                                    <div class="form-card">
                                        <h4 class="fs-title">Welcome to Spitout</h4>
                                        <form id="registration-form" action="submit_form.php" method="POST">
                                            <label for="fullName">Full Name</label> <br>
                                            <input type="text" name="Fname" placeholder="Full Name" required />
                                            <label for="username">User Name</label> <br>
                                            <input id="username" type="text" name="username" placeholder="UserName" required />
                                            <div class="so-register-notice">Username cannot be changed.</div>
                                            <label for="email">Email</label> <br>
                                            <input id="email" type="email" name="email" placeholder="Email Id" required />
                                            <div class="so-register-notice">Email cannot be changed.</div>
                                            <label for="password">Password</label> <br>
                                            <input type="password" id="registrationPassword" name="pwd" placeholder="Password" required />
                                            <label for="confirm_password">Confirm Password</label> <br>
                                            <input type="password" id="registrationConfirmPassword" name="confirm_password" placeholder="Confirm Password" required />
                                            <label for="country">Country:</label> <br>
                                            <select id="country" name="country" class="form-control">
                                                <?php
                                                $countries = WC()->countries->get_countries();
                                                foreach ($countries as $key => $value) {
                                                    // Check if the country is "US" and set it as the default selected option.
                                                    $selected = ($key === 'US') ? 'selected' : '';
                                                    echo "<option value='$key' $selected>$value</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for="state">State:</label><br>
                                            <select id="state" name="state" class="form-control">
                                                <?php
                                                $states = WC()->countries->get_states('US');
                                                foreach ($states as $key => $value) {
                                                    echo "<option value='$key'>$value</option>";
                                                }
                                                ?>
                                            </select>

                                            <label for="role">Role:</label><br>
                                            <select id="role" name="role" class="form-control">
                                                <option value='' selected>Choose a role</option>
                                                <option value='seller'>Seller</option>
                                                <option value='buyer'>Buyer</option>
                                            </select>

                                            <!-- label select for birthday -->
                                            <label for="birthday">Birth Day</label> <br>
                                            <label for="month"></label>
                                            <select id="month">
                                                <?php
                                                foreach ($months as $key => $value) {
                                                    echo "<option value='$key'>$value</option>";
                                                }
                                                ?>
                                            </select>
                                            <label for="day"></label>
                                            <select id="day"></select>
                                            <label for="year"></label>
                                            <select id="year">
                                                <!-- JavaScript to generate a range of years dynamically -->
                                            </select>
                                            <div class="so-register-notice">Date of birth cannot be changed.</div>
                                            <input type="hidden" name="year">

                                            <input type="checkbox" id="agree" name="agree" value="agree" data-toggle="modal" data-target="#exampleModalLong" onclick="return false;" required>
                                            <label for="agree"> I agree to the terms of use & privacy
                                                policy.</label>


                                            <!-- modal start -->
                                            <div class="modal fade privacy-policy-modal" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content non-template-page">
                                                        <button type="button" class="btn btn-primary" id="scrollToBottom" title="Go to bottom"><i class="bi bi-chevron-down"></i></button>
                                                        <div class="modal-body">
                                                            <?php
                                                            if (!empty($the_content)) {
                                                                echo $the_content;
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary agree-terms" data-dismiss="modal">Agree</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- modal ends -->

                                            <input type="button" name="next" class="next multistep-next action-button next-step-register-btn so-action-button" value="Register" />
                                            <!-- <svg id="Layer_1" enable-background="new 0 0 100 100" height="20" viewBox="0 0 100 100" width="20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="m50 10.75c-18.266 0-34.562 13.129-38.383 31.007-1.909 8.933-.623 18.432 3.636 26.515 4.099 7.779 10.819 14.066 18.859 17.629 8.363 3.707 17.964 4.353 26.754 1.825 8.48-2.438 15.999-7.789 21.118-14.972 10.703-15.017 9.272-36.111-3.32-49.567-7.38-7.886-17.862-12.437-28.664-12.437zm18.829 41.347-10.7 10.958c-2.709 2.775-6.991-1.429-4.293-4.191l5.399-5.529h-25.586c-1.817 0-3.333-1.517-3.333-3.333s1.517-3.333 3.333-3.333h25.458l-5.506-5.505c-2.736-2.736 1.506-6.979 4.242-4.243l10.961 10.96c1.162 1.161 1.173 3.041.025 4.216z">
                                                </path>
                                            </svg> -->
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<?php get_footer(); ?>