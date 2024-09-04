<?php

/* SpitOut registration email template */


add_action('woocommerce_email_footer', 'email_custom_footer');
function email_custom_footer()
{

?>
    <div class="email-footer">
        <table class="quick-links">
            <tr>
                <td><a href="#">HELP CENTER</a></td>
                <td><a href="#">SUPPORT 24/7</a></td>
                <td><a href="#">ACCOUNT</a></td>
            </tr>
        </table>

        <div class="footer-copyright">
            <p>Copyright © 2024 Spitout. All Rights Reserved. We appreciate you!</p>
        </div>
        <div class="footer-contact">
            <a href="mailto:contact@spitout.com">contact@spitout.com</a>
            <a href="tel:1(800)232-90-26">1(800)232-90-26</a>
        </div>

        <div class="footer-social-icons">
            <ul>
                <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/instagram.png"></a></li>
                <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/twitter.png"></a></li>
                <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/facebook.png"></a></li>
                <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/google-plus-1.png"></a></li>
            </ul>
        </div>
    </div>
    <?php
    spitout_email_template_styles(); ?>
<?php
}


function spitout_set_mail_content($content)
{
    return 'text/html';
}

/* The above code is a PHP function definition named `spitout_email_templates` that takes
three parameters: ``, ``, and ``. The purpose of this function is not clear from
the provided code snippet, as the function body is missing. Typically, based on the function name,
it seems like this function might be intended to generate email templates related to user
registration. The actual implementation of the function, including the email template generation
logic, would need to be written within the function body. */
function spitout_email_templates($fullName, $to, $subject, $message, $type)
{


    ob_start(); // Start output buffering
    add_filter('wp_mail_content_type', 'spitout_set_mail_content');


    // Output your email template

    $email_message = '
<html>
<head>
  <title>' . $subject . '</title>
 <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");

        p {
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        ul {
            padding: 0;
            margin: 0;
        }

        .email-wrapper {
            max-width: 550px;
            margin: 0 auto;
            font-family: "Inter", sans-serif;
            line-height: 1.3;
            color: #4c4c4c;
        }

        .email-wrapper a {
            text-decoration: none;
        }

        .email-wrapper strong {
            color: #3a485e;
        }

        .email-content {
            margin: 30px;
            background-color: rgb(247, 232, 236);
            /*   background-color: #ea1e7955; */
            border-radius: 8px;
            margin: 30px;
        }

        .email-header,
        .email-body {
            padding: 30px 30px 0;
        }

        .email-header figure {
            text-align: center;
            margin: 0 auto;
            width: 150px;
        }

        .email-header figure .logo {
            width: 100%;
        }

        .mail-title {
            font-size: 22px;
            font-weight: 300;
            line-height: 1.2;
        }

        .mail-title strong {
            color: #101010;
            font-size: 26px;
            font-weight: 600;
        }

        .dots {
            margin-bottom: 30px;
        }

        .dots span {
            display: inline-block;
            background-color: #ea1e79;
            width: 4px;
            height: 4px;
            border-radius: 20px;
            margin-right: 10px;
        }

        .body-footer {
            text-align: center;
            border-top: 1px solid #ccc;
            padding: 30px;
            margin: 0 10px;
        }

        .body-footer p {
            margin: 0;
        }

        .email-footer {
            margin: 30px;
        }

        .quick-links {
            /* display: inline-block; */
            font-size: 14px;
            font-weight: 600;
            width: 100%;
        }

        .quick-links td {
            text-align: center;
        }

        .quick-links td:first-child {
            text-align: left;
        }

        .quick-links td:last-child {
            text-align: right;
        }

        .quick-links a {
            color: #121212;
        }

        .footer-copyright {
            text-align: center;
            margin-top: 20px;
        }

        .footer-copyright p {
            margin-bottom: 5px;
        }

        .footer-contact {
            text-align: center;
        }

        .footer-contact a {
            color: #121212;
            font-weight: 300;
            display: inline-block;
            padding: 0 20px;
            border-right: 1px solid;
        }

        .footer-contact a:last-child {
            border: 0;
        }

        .footer-social-icons ul {
            text-align: center;
            margin-top: 30px;
        }

        .footer-social-icons ul li {
            display: inline-block;
            margin: 0 15px;
        }

        .footer-social-icons img {
            height: 20px;
            width: auto;
        }

        @media screen and (max-width: 480px) {
            .quick-links td {
                text-align: center;
                width: 100%;
                display: block;
                margin-bottom: 30px;
            }

            .quick-links td:first-child,
            .quick-links td:last-child {
                text-align: center;
            }

            .quick-links td:last-child {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
  <div class="email-wrapper">
        <div class="email-content">

            <div class="email-header">
                <figure>
                    <img class="logo" src="https://spitout.com/wp-content/uploads/2023/08/cropped-logo-white-background-e1690870726332.png" alt="logo" />
                </figure>

            </div>

            <div class="email-body"><p>' . $subject . '</p>
                <div class="dots">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <p>Hi ' . $fullName . '</p>
                <p>' . $message . '</p>
                
' . ($type == 'registration' ? '
    <div class="user-info">
      <p>User Name: <strong>' . $fullName . '</strong></p>
      <p>Email: <strong>' . $to . '</strong></p>
    </div>' : '') . '

                
                <div class="body-signature">
                    <p>Best,</br>SpitOut Team
                    </p>
                </div>
            </div>
            <div class="body-footer">
                <p>SpitOut: Natural Inimacy Uniquely Yours!</p>
            </div>
        </div>

        <div class="email-footer">
            <table class="quick-links">
                <tr>
                    <td><a href="#">HELP CENTER</a></td>
                    <td><a href="#">SUPPORT 24/7</a></td>
                    <td><a href="#">ACCOUNT</a></td>
                </tr>
            </table>

            <div class="footer-copyright">
                <p>Copyright © 2024 Spitout. All Rights Reserved. We appreciate you!</p>
            </div>
            <div class="footer-contact">
                <a href="mailto:contact@spitout.com">contact@spitout.com</a>
                <a href="tel:1(800)232-90-26">1(800)232-90-26</a>
            </div>

            <div class="footer-social-icons">
                <ul>
                    <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/instagram.png"></a></li>
                    <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/twitter.png"></a></li>
                    <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/facebook.png"></a></li>
                    <li><a href="#"><img src="https://spitout.com/wp-content/uploads/2024/05/google-plus-1.png"></a></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    wp_mail($to, $subject, $email_message, $headers);
}


/* ---------------seller order received customt email----------------- */

// add_action('woocommerce_thankyou', 'notify_seller_of_new_order', 10, 1);

function notify_seller_of_new_order($order_id)
{
    // Get the order object
    $order = wc_get_order($order_id);
    $seller_obj = get_user_by('ID', $order->get_meta('seller_id'));
    $sellerEmail = $seller_obj->user_email;

    // Initialize variables to hold the product name, order ID, and seller's email
    $product_name = '';
    $order_id = '';
    $billing_name = '';

    // Iterate through the order items
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        $product = wc_get_product($product_id);
        if ($product) {
            // Extract the product name and order ID
            $product_name = $product->get_name();
            $order_id = $order->get_id();
            $billing_first_name = $order->get_billing_first_name();
            $billing_last_name = $order->get_billing_last_name();
            $billing_name = $billing_first_name . ' ' . $billing_last_name;

            // Break the loop once the product is found
            break;
        }
    }

    // Prepare the email content
    $type = 'order';
    $subject = 'New order placed for your product';
    $message = "A new order has been placed for your product '{$product_name}'.\nOrder ID: {$order_id}";
    $to = $sellerEmail; // Use the seller's email address fetched dynamically
    spitout_email_templates($billing_name, $to, $subject, $message, $type);
}





/* --------------_Email Styles--------------------- */


function spitout_email_template_styles()
{ ?>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");

        p {
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        ul {
            padding: 0;
            margin: 0;
        }


        .dots {
            margin-bottom: 30px;
            text-align: center;
        }

        .dots span {
            display: inline-block;
            background-color: #ea1e79;
            width: 4px;
            height: 4px;
            border-radius: 20px;
            margin-right: 10px;
        }

        #wrapper {
            /* background-color: rgb(247, 232, 236); */
            border-radius: 8px;
            /* margin: 30px; */
            background-color: #f7e8ec;
            padding: 0px;
            font-family: "Inter", sans-serif;
            line-height: 1.3;
            color: #4c4c4c;
        }

        #template_header_image p {
            margin-bottom: 0;
            padding: 40px 0 30px;
        }

        #header_wrapper {
            padding: 0 48px 36px;
        }

        #wrapper #template_container,
        #body_content,
        #template_header {
            background-color: unset;
            box-shadow: unset;
            border: 0;
            border-radius: 0;
        }

        #template_header_image,
        #template_container {
            /* background-color: #f7e8ec !important; */
        }

        table#template_container {
            box-shadow: unset !important;
        }

        #body_content table tr>td {
            padding: 0;
        }

        #template_header h1 {
            color: #000;
            text-shadow: unset;
            text-align: center;
            font-weight: 600;
        }

        #body_content_inner {
            text-align: center !important;
        }

        #body_content_inner h2 {
            text-align: center;
            color: #101010;
        }

        #body_content_inner table.td {
            background-color: #FFF;
            border: 0;
            margin-top: 30px;
            padding: 15px;
            border: 20px solid #f7e8ec;
        }

        #body_content_inner table.td td,
        #body_content_inner table.td th {
            border: 0;
            text-align: right !important;
            padding: 6px;
        }

        #body_content_inner table.td thead th {
            color: #3a485e;
        }

        #body_content_inner table.td tfoot tr:last-child td {
            color: #ea1e79;
        }

        #body_content_inner tfoot th {
            font-weight: 400;
        }

        .email-intro p {
            margin-bottom: 5px !important;
        }


        .body-footer {
            text-align: center;
            border-top: 1px solid #ccc;
            padding: 30px;
            margin: 0 10px;
        }

        .body-footer p {
            margin: 0;
        }

        .footer-copyright p {
            margin-bottom: 0;
        }


        /* ---footer styles---- */

        .email-footer {
            font-family: "Inter", sans-serif;
            line-height: 1.3;
            color: #4c4c4c;
            padding: 30px 10px;
            background: #FFF;
        }

        a {
            text-decoration: none;
        }

        .quick-links {
            /* display: inline-block; */
            font-size: 14px;
            font-weight: 600;
            width: 100%;
        }

        .quick-links td {
            text-align: center;
        }

        .quick-links td:first-child {
            text-align: left;
        }

        .quick-links td:last-child {
            text-align: right;
        }

        .quick-links a {
            color: #121212;
            font-size: 14px;
            font-weight: 600;
        }

        .footer-copyright {
            text-align: center;
            margin-top: 20px;
        }

        .footer-copyright p {
            margin-bottom: 5px;
        }

        .footer-contact {
            text-align: center;
        }

        .footer-contact a {
            color: #121212;
            font-weight: 300;
            display: inline-block;
            padding: 0 20px;
            border-right: 1px solid;
        }

        .footer-contact a:last-child {
            border: 0;
        }

        .footer-social-icons ul {
            text-align: center;
            margin-top: 30px;
        }

        .footer-social-icons ul li {
            display: inline-block;
            margin: 0 15px;
        }

        .footer-social-icons img {
            height: 20px;
            width: auto;
        }

        @media screen and (max-width: 480px) {
            .quick-links td {
                text-align: center;
                width: 100%;
                display: block;
            }

            .quick-links td:first-child,
            .quick-links td:last-child {
                text-align: center !important;
            }

            .quick-links td:last-child {
                margin-bottom: 0;
            }
        }
    </style>


<?php
}
