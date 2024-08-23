<?php
/* Template Name: login Template */

// Check if the user is not logged in
if (is_user_logged_in()) {
  // Redirect them to the wp-admin login page
  wp_redirect(site_url());
  exit;
}

get_header();
?>
<section class='so-ms-form so-feed-new-container' id="so-ms-form-login">
  <div class=" container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
      <div class="col-lg-12">
        <div class=" px-0 pt-0">
          <div id="msform">
            <div class="row">
              <div class="col-md-12 mx-0">
                <div class="form-card">
                  <div class="row so-login-form">
                    <div class="login-title col-lg-12">

                      <?php
                      //handle login errors
                      // if ($_GET['reason']) {
                      if (isset($_GET['reason'])) {
                        $login_err_msg = '';
                        switch ($_GET['reason']) {
                          case 'invalid_username':
                            $login_err_msg = 'Invalid username';
                            break;

                          case 'empty_password':
                            $login_err_msg = 'Password is empty';
                            break;

                          case 'empty_username':
                            $login_err_msg = 'Username is Empty';
                            break;

                          case 'incorrect_password':
                            $login_err_msg = 'Incorrect Password';
                            break;
                        }

                        echo '<p class="text-danger font-weight-bold">' . $login_err_msg . '</p>';
                      }
                      ?>

                      <h4 class="fs-title">Welcome Back</h4>
                      <p class="pt-2">Please enter your details to sign in.</p>
                    </div>
                    <?php

                    echo wp_login_form(
                      array(
                        'redirect' => esc_url($_SERVER['REQUEST_URI']),
                        'form_id' => 'loginform',
                        'label_username' => __('Username', 'spitout'),
                        'label_password' => __('Password', 'spitout'),
                        'label_remember' => __('Remember Me', 'spitout'),
                        'label_log_in' => __('Sign In', 'spitout'),
                        'id_username' => 'so-username',
                        'id_password' => 'so-password',
                        'id_remember' => 'so-rememberme',
                        'id_submit' => 'so-submit',
                        'remember' => true,
                        'value_username' => '',
                        'value_remember' => false,
                      )
                    );
                    ?>
                  </div>
                  <a href="<?php echo esc_url(wp_lostpassword_url()) ?>" class="login-forgot-password">
                    <p>Forgot password?</p>
                  </a>
                  <i id="show-password" class=" toggle-password bi bi-eye-slash-fill"></i>
                  <!-- <div class="next-step-register-btn"> -->
                  <div class="col-lg-12 login-redirect-to-register pt-2">
                    <h6>Don't have an account yet. <span><a href="<?php echo home_url('/register') ?>">Sign
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
<?php
get_footer();