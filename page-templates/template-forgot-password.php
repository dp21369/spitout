<?php

/**
 * Template Name: Forgot Password
 * @package spitout
 */
get_header();
$message = '';
$success_message = '';
if (isset($_POST['reset_email'])) {
    $user_data = get_user_by('email', trim($_POST['reset_email']));
    if (empty($user_data)) {

        echo ' <p class="forget-password-verify-acc-text"><i class="bi bi-exclamation-circle"></i>User not found.
        </p>';
    } else {
        $user_login = $user_data->user_login;
        $user_email = $user_data->user_email;
        $key = get_password_reset_key($user_data);
        $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
        // Send the reset URL via email (you'll need to replace this with your own email-sending code)
        //wp_mail($user_email, 'Password Reset', 'Click here to reset your password: ' . $reset_url);
        $type = 'forget-password';
        $subject = 'Someone has requested a password reset for the following account:';
        $message .= 'To reset your password, visit the following address:  ';
        $message .= '<a href="' . $reset_url . '"> Reset Password </a>';
        //spitout_email_templates($user_login, $user_email, $subject, $message, $type);

        // Assuming spitout_email_templates() sends the email and returns a success status
        spitout_email_templates($user_login, $user_email, $subject, $message, $type);
        $success_message = 'Password reset link sent successfully. Please check your inbox or spam';
    }
}
?>


<?php
if (!empty($success_message)) {
    echo ' <p class="forget-password-verify-acc-text"><i class="bi bi-exclamation-circle"></i>' . $success_message . '
        </p>';
}

?>



<section class='so-ms-form so-feed-new-container' id="so-ms-form-login">
    <div class=" container-fluid" id="grad1">
        <div class="row justify-content-center mt-0">
            <div class="col-lg-12">
                <div class=" px-0 pt-0">
                    <div id="msform">
                        <div class="row so-forgotPsw">
                            <div class="col-md-12 mx-0">
                                <div class="form-card">
                                    <div class="row so-login-form">
                                        <div class="login-title col-lg-12">

                                            <h4 class="fs-title mb-1">Reset password</h4>
                                            <p class="pt-1">Enter the email address associated with your account and we
                                                will send a link to reset your password.</p>
                                        </div>
                                    </div>

                                    <form method="post">
                                        <label for="reset_email">Email</label>
                                        <input type="email" name="reset_email" placeholder="Enter your email address">
                                        <button type="submit" class="mt-4">
                                            <h5>Continue</h5>
                                        </button>
                                    </form>

                                    <div class="col-lg-12 login-redirect-to-register so-forgotPsw-redirect pt-2">
                                        <h6>Don't have an account yet. <span><a href="<?php echo home_url('/register'); ?>">Sign
                                                    up </a></span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<?php get_footer(); ?>