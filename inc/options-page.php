<?php

/* new code for theme options */

function custom_theme_settings()
{
    add_menu_page('Theme Settings', 'Theme Settings', 'manage_options', 'theme-settings', 'theme_settings_page');
}
add_action('admin_menu', 'custom_theme_settings');


function theme_settings_page()
{
?>
    <div class="wrap">
        <h2>Theme Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('theme-settings-group');
            do_settings_sections('theme-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

function custom_settings_fields()
{
    add_settings_section('general-section', 'General Settings', 'spit_out_theme_setting_section_callback', 'theme-settings');


    add_settings_field('desktop-logo-field', 'Desktop Logo', 'spit_out_header_logo', 'theme-settings', 'general-section');
    add_settings_field('mobile-logo-field', 'Mobile Logo', 'spit_out_header_mobile_logo', 'theme-settings', 'general-section');
    add_settings_field('footer-logo-field', 'Footer Logo', 'spit_out_footer_logo', 'theme-settings', 'general-section');
    add_settings_field('text-field', 'Footer Copyright Text', 'spit_out_footer_copyright_text_field_callback', 'theme-settings', 'general-section');


    register_setting('theme-settings-group', 'desktop_logo_field');
    register_setting('theme-settings-group', 'mobile_logo_field');
    register_setting('theme-settings-group', 'footer_logo_field');
    register_setting('theme-settings-group', 'text_field');
}
add_action('admin_init', 'custom_settings_fields');

function spit_out_theme_setting_section_callback()
{
    echo 'General settings For SpitOut';
}

function spit_out_header_logo()
{
    $desktop_logo_url = get_option('desktop_logo_field');
    echo "<input type='hidden' id='desktop_logo_field' name='desktop_logo_field' value='$desktop_logo_url' />";
    echo "<input type='button' class='button' value='Upload Image' id='upload_image_button' />";
    echo "<img src='$desktop_logo_url' id='image_preview' style='max-width: 300px; display: block; margin-top: 10px;' />";
}

function spit_out_header_mobile_logo()
{
    $mobile_logo_url = get_option('mobile_logo_field');
    echo "<input type='hidden' id='mobile_logo_field' name='mobile_logo_field' value='$mobile_logo_url' />";
    echo "<input type='button' class='button' value='Upload Image' id='mobile_upload_image_button' />";
    echo "<img src='$mobile_logo_url' id='mobile_image_preview' style='max-width: 300px; display: block; margin-top: 10px;' />";
}

function spit_out_footer_logo()
{
    $footer_logo_url = get_option('footer_logo_field');
    echo "<input type='hidden' id='footer_logo_field' name='footer_logo_field' value='$footer_logo_url' />";
    echo "<input type='button' class='button' value='Upload Image' id='footer_upload_image_button' />";
    echo "<img src='$footer_logo_url' id='footer_image_preview' style='max-width: 300px; display: block; margin-top: 10px;' />";
}
function spit_out_footer_copyright_text_field_callback()
{
    $text_value = get_option('text_field');
    echo "<input type='text' name='text_field' class='widefat' value='$text_value' />";
}
