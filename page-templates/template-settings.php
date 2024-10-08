<?php

/**
 * Template Name: Settings Page
 * @package spitout
 */

// Check if the user is not logged in
if (!is_user_logged_in()) {
    // Redirect to the site's URL
    wp_redirect(home_url());
    exit; // Ensure WordPress stops further execution
}

get_header();

?>
<section>
    <div class="container so-feed-new-container so-setting-page-heading">
        <div class="row">
            <div class="col-md-12">
                <h4>Settings</h4>
            </div>
        </div>
    </div>

    <div class="container so-feed-new-container">
        <div class="so-settings-panel-titles">
            <ul>
                <li>
                    <a href="/spitout/edit-profile/">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M19.5119 5.85L13.5719 2.42C12.6019 1.86 11.4019 1.86 10.4219 2.42L4.49187 5.85C3.52187 6.41 2.92188 7.45 2.92188 8.58V15.42C2.92188 16.54 3.52187 17.58 4.49187 18.15L10.4319 21.58C11.4019 22.14 12.6019 22.14 13.5819 21.58L19.5219 18.15C20.4919 17.59 21.0919 16.55 21.0919 15.42V8.58C21.0819 7.45 20.4819 6.42 19.5119 5.85ZM12.0019 7.34C13.2919 7.34 14.3319 8.38 14.3319 9.67C14.3319 10.96 13.2919 12 12.0019 12C10.7119 12 9.67188 10.96 9.67188 9.67C9.67188 8.39 10.7119 7.34 12.0019 7.34ZM14.6819 16.66H9.32187C8.51187 16.66 8.04187 15.76 8.49187 15.09C9.17187 14.08 10.4919 13.4 12.0019 13.4C13.5119 13.4 14.8319 14.08 15.5119 15.09C15.9619 15.75 15.4819 16.66 14.6819 16.66Z"
                                    fill="#292D32" />
                            </svg>
                            <h5>Account</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="<?php echo home_url('/my-account/woo-wallet/'); ?>">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M22 7.54844C22 8.20844 21.46 8.74844 20.8 8.74844H3.2C2.54 8.74844 2 8.20844 2 7.54844V7.53844C2 5.24844 3.85 3.39844 6.14 3.39844H17.85C20.14 3.39844 22 5.25844 22 7.54844Z"
                                    fill="#292D32" />
                                <path
                                    d="M2 11.45V16.46C2 18.75 3.85 20.6 6.14 20.6H17.85C20.14 20.6 22 18.74 22 16.45V11.45C22 10.79 21.46 10.25 20.8 10.25H3.2C2.54 10.25 2 10.79 2 11.45ZM8 17.25H6C5.59 17.25 5.25 16.91 5.25 16.5C5.25 16.09 5.59 15.75 6 15.75H8C8.41 15.75 8.75 16.09 8.75 16.5C8.75 16.91 8.41 17.25 8 17.25ZM14.5 17.25H10.5C10.09 17.25 9.75 16.91 9.75 16.5C9.75 16.09 10.09 15.75 10.5 15.75H14.5C14.91 15.75 15.25 16.09 15.25 16.5C15.25 16.91 14.91 17.25 14.5 17.25Z"
                                    fill="#292D32" />
                            </svg>
                            <h5>Wallet</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/spitout/notification">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M19.3399 14.49L18.3399 12.83C18.1299 12.46 17.9399 11.76 17.9399 11.35V8.82C17.9399 6.47 16.5599 4.44 14.5699 3.49C14.0499 2.57 13.0899 2 11.9899 2C10.8999 2 9.91994 2.59 9.39994 3.52C7.44994 4.49 6.09994 6.5 6.09994 8.82V11.35C6.09994 11.76 5.90994 12.46 5.69994 12.82L4.68994 14.49C4.28994 15.16 4.19994 15.9 4.44994 16.58C4.68994 17.25 5.25994 17.77 5.99994 18.02C7.93994 18.68 9.97994 19 12.0199 19C14.0599 19 16.0999 18.68 18.0399 18.03C18.7399 17.8 19.2799 17.27 19.5399 16.58C19.7999 15.89 19.7299 15.13 19.3399 14.49Z"
                                    fill="#292D32" />
                                <path
                                    d="M14.8297 20.01C14.4097 21.17 13.2997 22 11.9997 22C11.2097 22 10.4297 21.68 9.87969 21.11C9.55969 20.81 9.31969 20.41 9.17969 20C9.30969 20.02 9.43969 20.03 9.57969 20.05C9.80969 20.08 10.0497 20.11 10.2897 20.13C10.8597 20.18 11.4397 20.21 12.0197 20.21C12.5897 20.21 13.1597 20.18 13.7197 20.13C13.9297 20.11 14.1397 20.1 14.3397 20.07C14.4997 20.05 14.6597 20.03 14.8297 20.01Z"
                                    fill="#292D32" />
                            </svg>
                            <h5>Notifications</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/spitout/hidden-users/">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                version="1.1" x="0px" y="0px" viewBox="0 0 256 256" enable-background="new 0 0 256 256"
                                xml:space="preserve" height="24px" width="24px">
                                <g>
                                    <g>
                                        <g>
                                            <path fill="#000000"
                                                d="M147.5,18.8c-15,1.9-26.5,8.2-32.7,18c-1.9,2.9-4.2,9.1-4.6,12.3c-0.2,1.3-0.4,2.4-0.5,2.4c-0.1,0-0.8-0.3-1.6-0.7c-6-3.1-14.7-4.6-24.6-4.3C69.5,47,60.2,50.7,52.5,58.9c-3.6,3.8-5.9,7.4-7.9,12.3c-2.3,5.8-2.9,9.6-2.9,20c0,9.1,0,9.3-1.2,10.8c-2.4,3.3-2.6,4.6-2.5,15.4c0.1,9.5,0.2,10.2,1.2,12.1c0.6,1.2,1.7,2.7,2.4,3.5c0.7,0.7,1.8,2.8,2.4,4.4c2.3,6.6,7,14.6,11.4,19.4l2.1,2.4v6.4c0,7.2-0.4,8.6-2.9,10.8c-0.9,0.8-8.6,5.7-17,11c-18.3,11.4-20.7,13.4-24.3,20.7c-2.9,6-3.5,9.1-3.5,20.1v9.3h78.2h78.1l-3.6-1.5c-10.3-4.5-20.7-12.2-28.5-21.1c-4.9-5.6-6.4-7.6-11.5-15.4l-4.3-6.5l5.2-7.8c9.3-13.8,17.5-22.4,27.9-29.4c9.6-6.4,21.6-10.7,32.6-11.6l3.9-0.3l2.1-3.2c3.1-4.8,5.7-9.8,7.9-15.2c1.8-4.5,2.2-5,4.3-6.4c2.8-1.8,4.6-4.2,5.9-7.4c0.9-2.1,1-3.6,1.2-12.2c0.2-11.2-0.3-14.3-2.7-17.8l-1.4-2V65.2c0-12.8-0.1-14.8-0.9-18.3c-3.5-14.5-15-24.1-32.6-27.3C166.9,18.6,152.4,18.2,147.5,18.8z M98.1,55.7c2.1,0.5,5.5,1.8,7.6,2.8l3.8,1.9V70c0,9.5,0,9.6-1.2,11.4c-2.2,3.4-2.5,5.4-2.5,16.9c0,12.3,0.4,14.1,3.9,18c1.5,1.7,2.3,3.2,3.3,6.4c1.7,5.6,6,14.5,9.6,20l2.9,4.4v7.9c0,8.7-0.4,10.4-3.1,12.9c-0.7,0.7-9.3,5.6-19.1,10.9c-9.8,5.3-19,10.5-20.5,11.4c-5.6,3.4-10.1,8.5-13.4,15c-2.3,4.7-3.3,9.2-3.6,17.2l-0.3,7.1H41.7H17.9v-4.8c0-5.7,0.9-9.8,2.9-13.6c2.6-5,5-6.8,22.1-17.5c8.8-5.5,16.9-10.8,17.9-11.8c1-1,2.4-3,3.1-4.5c1.3-2.6,1.3-2.9,1.4-11.9l0.1-9.2l-3-3.3c-5.4-5.8-9.9-13.7-11.8-20.5c-0.6-2-1.2-3.1-2-3.6c-0.6-0.4-1.5-1.4-2-2.2c-0.8-1.4-0.9-2.4-0.9-10v-8.5l1.4-1.9c0.7-1,1.6-2,1.9-2.2c0.3-0.2,0.5-3.9,0.5-10.8c0-12.3,0.5-15.7,3.3-21.6c4.2-8.6,11.6-14.1,22.4-16.5C81,54,92.7,54.2,98.1,55.7z" />
                                            <path fill="#000000"
                                                d="M180.8,156.6c-5.3,1-11.7,3.1-16.1,5.4c-10.6,5.3-21.1,15-29.2,26.9l-2.7,4.1l2.3,3.5c8.2,12.1,19.3,22.2,30.1,27.4c13.9,6.6,28.4,7.3,42.8,2.1c8-2.9,15.3-7.7,22.8-14.9c4.8-4.7,11.5-12.8,14-17.1l1-1.7l-2.2-3.2c-10-14.8-22.7-25.3-36.3-30C198.9,156,188.7,155.1,180.8,156.6z M195.3,165.1c6.4,2.4,11.3,8.3,12.4,15.1c2.1,12.8-9.4,24.4-22.2,22.2c-10.7-1.7-18.1-11.9-16.3-22.2C171.3,168,183.7,160.8,195.3,165.1z M219.1,174.2c1.5,1.1,4.3,3.4,6.2,5.1c3.6,3.4,10.8,12,10.8,13c0,2.3-13.3,16.3-19.1,20.1c-21.1,14-44.3,11.6-63.5-6.5c-4-3.8-9.6-10.3-10.5-12.2c-0.4-0.9-0.1-1.5,2.2-4.6c3.9-5.1,8.7-10.1,13.5-13.8c2.3-1.8,4.2-3.1,4.3-3.1c0,0.1-0.3,1.4-0.8,2.9c-1.3,3.9-1.6,9.4-0.7,13.8c3.7,17.3,22.8,26.9,38.7,19.2c13.1-6.3,19.2-21.8,13.7-35.1l-1.2-2.9l1.7,1C215.5,171.7,217.5,173,219.1,174.2z" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <h5>Hidden Users</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                <li>
                    <a href="/privacy-policy/">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M18.5408 4.11984L13.0408 2.05984C12.4708 1.84984 11.5408 1.84984 10.9708 2.05984L5.47078 4.11984C4.41078 4.51984 3.55078 5.75984 3.55078 6.88984V14.9898C3.55078 15.7998 4.08078 16.8698 4.73078 17.3498L10.2308 21.4598C11.2008 22.1898 12.7908 22.1898 13.7608 21.4598L19.2608 17.3498C19.9108 16.8598 20.4408 15.7998 20.4408 14.9898V6.88984C20.4508 5.75984 19.5908 4.51984 18.5408 4.11984ZM15.4808 9.71984L11.1808 14.0198C11.0308 14.1698 10.8408 14.2398 10.6508 14.2398C10.4608 14.2398 10.2708 14.1698 10.1208 14.0198L8.52078 12.3998C8.23078 12.1098 8.23078 11.6298 8.52078 11.3398C8.81078 11.0498 9.29078 11.0498 9.58078 11.3398L10.6608 12.4198L14.4308 8.64984C14.7208 8.35984 15.2008 8.35984 15.4908 8.64984C15.7808 8.93984 15.7808 9.42984 15.4808 9.71984Z"
                                    fill="#292D32" />
                            </svg>
                            <h5>Privacy Policy</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/terms-of-use/">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="#000000" fill-rule="evenodd"
                                    d="M5 2a1 1 0 0 0-1 1v16a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V8a1 1 0 0 0-.293-.707l-5-5A1 1 0 0 0 14 2H5Zm9 2.414L17.586 8H14V4.414Zm1.707 9.293a1 1 0 0 0-1.414-1.414L11 15.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h5>Terms and Conditions</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/refund-policy/">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="#000000"
                                    d="M22.005 7h-20V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v3Zm0 2v11a1 1 0 0 1-1 1h-18a1 1 0 0 1-1-1V9h20Zm-11 5v-2.5l-4.5 4.5h10.5v-2h-6Z" />
                            </svg>
                            <h5>Refund Policy</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/shipping-policy/">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="#000000"
                                    d="M6 20q-1.25 0-2.125-.875T3 17H1V6q0-.825.588-1.413T3 4h14v4h3l3 4v5h-2q0 1.25-.875 2.125T18 20q-1.25 0-2.125-.875T15 17H9q0 1.25-.875 2.125T6 20Zm0-2q.425 0 .713-.288T7 17q0-.425-.288-.713T6 16q-.425 0-.713.288T5 17q0 .425.288.713T6 18Zm12 0q.425 0 .713-.288T19 17q0-.425-.288-.713T18 16q-.425 0-.713.288T17 17q0 .425.288.713T18 18Zm-1-5h4.25L19 10h-2v3Z" />
                            </svg>
                            <h5>Shipping Policy</h5>
                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </a>
                </li>
                <li>
                    <div id="live-chat-support">
                        <div class=so-settings-panels>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M17 2.42969H7C4 2.42969 2 4.42969 2 7.42969V13.4297C2 16.4297 4 18.4297 7 18.4297V20.5597C7 21.3597 7.89 21.8397 8.55 21.3897L13 18.4297H17C20 18.4297 22 16.4297 22 13.4297V7.42969C22 4.42969 20 2.42969 17 2.42969ZM12 14.5997C11.58 14.5997 11.25 14.2597 11.25 13.8497C11.25 13.4397 11.58 13.0997 12 13.0997C12.42 13.0997 12.75 13.4397 12.75 13.8497C12.75 14.2597 12.42 14.5997 12 14.5997ZM13.26 10.4497C12.87 10.7097 12.75 10.8797 12.75 11.1597V11.3697C12.75 11.7797 12.41 12.1197 12 12.1197C11.59 12.1197 11.25 11.7797 11.25 11.3697V11.1597C11.25 9.99969 12.1 9.42969 12.42 9.20969C12.79 8.95969 12.91 8.78969 12.91 8.52969C12.91 8.02969 12.5 7.61969 12 7.61969C11.5 7.61969 11.09 8.02969 11.09 8.52969C11.09 8.93969 10.75 9.27969 10.34 9.27969C9.93 9.27969 9.59 8.93969 9.59 8.52969C9.59 7.19969 10.67 6.11969 12 6.11969C13.33 6.11969 14.41 7.19969 14.41 8.52969C14.41 9.66969 13.57 10.2397 13.26 10.4497Z"
                                    fill="#292D32" />
                            </svg>

                            <div data-id="TPER_auCMAI" class="livechat_button"><a
                                    href="https://www.livechat.com/?utm_source=chat_button&utm_medium=referral&utm_campaign=lc_17284176">live
                                    chat customer service</a></div>

                        </div>
                        <div class="so-goto-settings-single">
                            <i class="bi bi-chevron-right"></i>
                        </div>
                    </div>

                </li>
            </ul>
        </div>
    </div>
</section>

<?php get_footer(); ?>