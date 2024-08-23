<?php

/**
 * Template Name: Profile New Page
 * 
 * Profile page for the users 
 * 
 * 
 * github version->488cf11
 * 
 * @package spitout
 */



get_header(); ?>



<!-- Profile section css=================================================================== -->
<section class="so-profile-new-profile">
    <div class="container so-profile-new-container so-feed-new-container">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-cover-image">
                    <figure>
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/cover-proifle.png"
                            alt="cover" height="100%" width="100%" title="Cover Picture">
                    </figure>
                </div>
                <div class="profile-main-option">
                    <div class="profile-picture-image">
                        <figure>
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/seller6.jpeg"
                                alt="profile picture" title="Profile Picture">
                        </figure>
                    </div>
                    <div class="profile-text-follow-option d-flex">

                        <i class="bi bi-chat-square-dots-fill"></i>
                        <div class="follow-button">
                            <p>Follow</p>
                        </div>
                    </div>
                </div>
                <div class="profile-user-content-details">
                    <div class="profile-user-detail d-flex">
                        <h5>Jessica Fernandez</h5>
                        <div class="profile-user-location d-flex">
                            <i class="bi bi-geo-alt-fill"></i>
                            <p>USA, Chicago</p>
                        </div>
                    </div>
                    <div class="profile-user-bio">
                        <h6 class="pb-2">Glamour sophisticated Spanish woman Green eyes You can purchase all my
                            content
                            in main wall
                            ! I´m in the chat every night...
                        </h6>
                        <a href="#">
                            <p>show more</p>
                        </a>
                    </div>
                    <div class="profile-user-price-details">
                        <div class="profile-user-saliva-type">
                            <i class="bi bi-droplet-fill"></i>
                            <h5>Special Saliva</h5>
                        </div>
                        <div class="profile-user-saliva-price">
                            <label for="saliva1">
                                <h5>$50.00</h5>
                            </label><br>
                            <input type="checkbox" id="saliva1" name="saliva1" value="50">

                        </div>
                    </div>
                    <div class="profile-user-price-details">
                        <div class="profile-user-saliva-type">
                            <i class="bi bi-droplet-fill"></i>
                            <h5>Standard Saliva</h5>
                        </div>
                        <div class="profile-user-saliva-price">
                            <label for="saliva1">
                                <h5>$50.00</h5>
                            </label><br>
                            <input type="checkbox" id="saliva1" name="saliva1" value="50">

                        </div>
                    </div>
                    <div class="profile-user-price-details">
                        <div class="profile-user-saliva-type">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/bottle 1.svg" alt="jar"
                                height="18px">
                            <h5>Custome Jar</h5>
                        </div>
                        <div class="profile-user-saliva-price">
                            <label for="saliva1">
                                <h5>$2.00</h5>
                            </label><br>
                            <input type="checkbox" id="saliva1" name="saliva1" value="50">
                        </div>
                    </div>
                    <div class="profile-saliva-order-button">
                        <button class="d-flex">
                            <h5>Order</h5>
                            <i class="bi bi-arrow-right-circle-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- profile section end-========================================================= -->

<div class="feed-new-container so-profile-feed-options">
    <div class="profile-feed-show-option">
        <a href="#">
            <h5>Post</h5>
        </a>
        <a href="#">
            <h5>Media</h5>
        </a>
        <a href="#">
            <h5>Review</h5>
        </a>
    </div>
</div>


<!-- News feed section======================================================= -->
<section class="'so-news-feed">
    <div class="container so-feed-new-container">
        <div class="container mt-4 p-0">
            <div class="card so-feed-card-wImage">
                <!-- First column of card -->
                <div class="card-body so-feed-profile-summary">
                    <div class="d-flex align-items-center feed-profile-card-title">
                        <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
                            <a href="#"><img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers11.webp"
                                    alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;"> </a>
                        </div>
                        <a href="#">
                            <h5 class="card-title mb-0">John Doe</h5>
                        </a>
                        <span class="ml-auto text-muted">5 mins ago</span>
                        <a href="#"><span class="ml-2">&#8230;</span></a>
                    </div>
                </div>

                <!-- Second  column of card -->
                <div class="card-body so-feed-card-body">
                    <h6 class="card-text">The last image/video.</h6>
                </div>

                <!-- Third  column of card-->
                <div class="card-body so-feed-uploaded-img">
                    <!-- Replace 'image_or_video_url' with the actual URL of the image or video -->
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/feed1.cms" class="img-fluid"
                        alt="Image or Video">
                </div>
            </div>
        </div>
        <div class="container mt-4 p-0">
            <div class="card so-feed-card-wImage">
                <!-- First  column of card -->
                <div class="card-body so-feed-profile-summary">
                    <div class="d-flex align-items-center feed-profile-card-title">
                        <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
                            <a href="#"> <img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers11.webp"
                                    alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;"></a>
                        </div>
                        <a href="#">
                            <h5 class="card-title mb-0">John Doe</h5>
                        </a>
                        <span class="ml-auto text-muted">5 mins ago</span>
                        <a href="#"><span class="ml-2">&#8230;</span></a>
                    </div>
                </div>
                <div class="card-body so-feed-card-body">
                    <h6 class="card-text">The last image/video.</h6>
                </div>
            </div>
        </div>
        <div class="container mt-4 p-0">
            <div class="card so-feed-card-wImage">
                <!-- First  column of card -->
                <div class="card-body so-feed-profile-summary">
                    <div class="d-flex align-items-center feed-profile-card-title">
                        <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
                            <!-- Replace 'image_url' with the actual URL of the image -->
                            <a href="#"> <img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers11.webp"
                                    alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;"></a>
                        </div>
                        <a href="#">
                            <h5 class="card-title mb-0">John Doe</h5>
                        </a>
                        <span class="ml-auto text-muted">5 mins ago</span>
                        <a href="#"><span class="ml-2">&#8230;</span></a>
                    </div>
                </div>

                <!-- Second line -->
                <div class="card-body so-feed-card-body">
                    <h6 class="card-text">The last image/video.</h6>
                </div>

                <!-- Third line -->
                <div class="card-body so-feed-uploaded-img">
                    <!-- Replace 'image_or_video_url' with the actual URL of the image or video -->
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/feed2.jpg" class="img-fluid"
                        alt="Image or Video">
                </div>
            </div>
        </div>

        <div class="container mt-4 p-0">
            <div class="card so-feed-card-wImage">
                <!-- First line -->
                <div class="card-body so-feed-profile-summary">
                    <div class="d-flex align-items-center feed-profile-card-title">
                        <div class="so-feed-profile-image" style="width: 50px; height: 50px;">
                            <!-- Replace 'image_url' with the actual URL of the image -->
                            <a href="#"> <img
                                    src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers11.webp"
                                    alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;"></a>
                        </div>
                        <a href="#">
                            <h5 class="card-title mb-0">John Doe</h5>
                        </a>
                        <span class="ml-auto text-muted">5 mins ago</span>
                        <a href="#"><span class="ml-2">&#8230;</span></a>
                    </div>
                </div>

                <!-- Second line -->
                <div class="card-body so-feed-card-body">
                    <h6 class="card-text">The last image/video.</h6>
                </div>

                <!-- Third line -->
                <div class="card-body so-feed-uploaded-img">
                    <!-- Replace 'image_or_video_url' with the actual URL of the image or video -->
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/feed3.jpg" class="img-fluid"
                        alt="Image or Video">
                </div>
            </div>
        </div>

    </div>
</section>

<!-- end of the newsfeed section==================================================================
    ========================================================================================= -->





<?php get_footer(); ?>