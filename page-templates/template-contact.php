<?php

/**
 * Template Name: Contact page
 * @package spitout
 */
get_header();
?>
<section class="so-contact-form-page">
    <div class="so-contact-form">
        <div class="so-contact-form-header">

            <h2>Contact Us</h2>
            <h6>If you have any questions, please fill out the form below to contact us</h6>
        </div>
        <?php echo apply_shortcodes('[contact-form-7 id="c466ca7" title="Contact form 1"]'); ?>
    </div>
</section>
<?php get_footer(); ?>