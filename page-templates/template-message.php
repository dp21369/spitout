<?php

/**
 * Template Name: Message
 * @package spitout
 */
get_header();
?>
<div class="so-message-page container">
    <div class="row">
        <div class="col-md-12 message-page-header">
            <h4>Messages</h4>
        </div>
    </div>

    <div class="so-message-page-contents container">
        <div class="row message-container-all-message">
            <div class="col-md-4 so-see-all-messages">
                <div class="so-search-message">
                    <form class="search-message">
                        <input class="form-control mr-2" type="search" placeholder="Search" aria-label="Search" id="search-users-input">
                    </form><i class="bi bi-search" onclick="toggleSearch()"></i>
                </div>

                <ul class="nav nav-tabs all-message-options all-message-options-lists pt-2" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">
                            <h5>Messages</h5>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                            role="tab" aria-controls="profile" aria-selected="false">
                            <h5>Favorites</h5>
                        </button>
                    </li>
                    <li class="nav-item " role="presentation">
                        <button class="nav-link message-requests" id="contact-tab" data-toggle="tab"
                            data-target="#contact" type="button" role="tab" aria-controls="contact"
                            aria-selected="false">
                            <h5>Requests</h5>
                            <p>3</p>
                        </button>
                    </li>
                </ul>
                <div class="tab-content all-messages-lists" id="myTabContent">
                    <div class="tab-pane fade show active all-messages-lists" id="home" role="tabpanel"
                        aria-labelledby="home-tab">
                        <ul class="nav nav-tabs all-message-options pt-2" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-toggle="tab"
                                    data-target="#MadamByNature" type="button" role="tab" aria-controls="MadamByNature"
                                    aria-selected="true">
                                    <div class="all-messages-lists-messages">
                                        <div class="all-message-lists-messages-person-details">
                                            <figure>
                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers11.webp"
                                                    alt="Profile Image" style=" object-fit: cover;">
                                            </figure>
                                            <div class="all-message-lists-name">
                                                <h5>MadamByNature</h5>
                                                <p class="pt-1">HI! Thanks for following</p>
                                            </div>
                                        </div>
                                        <div class="so-message-active-now">
                                            <p>Now</p>
                                            <div class="all-messages-lists-messages-new-message-ntf">
                                                <p>3</p>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab" data-toggle="tab" data-target="#SaraGray"
                                    type="button" role="tab" aria-controls="SaraGray" aria-selected="true">
                                    <div class="all-messages-lists-messages new-message">
                                        <div class="all-message-lists-messages-person-details">
                                            <figure>
                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers10.jpg"
                                                    alt="Profile Image" style=" object-fit: cover;">
                                            </figure>
                                            <div class="all-message-lists-name">
                                                <h5>Sara Gray</h5>
                                                <p class="pt-1">HI! Thanks for following</p>
                                            </div>
                                        </div>
                                        <div class="so-message-active-now">
                                            <p>Now</p>
                                        </div>
                                    </div>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab" data-toggle="tab" data-target="#Jessiehot"
                                    type="button" role="tab" aria-controls="Jessiehot" aria-selected="true">
                                    <div class="all-messages-lists-messages new-message">
                                        <div class="all-message-lists-messages-person-details">
                                            <figure>
                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/seller6.jpeg"
                                                    alt="Profile Image" style=" object-fit: cover;">
                                            </figure>
                                            <div class="all-message-lists-name">
                                                <h5>Jessiehot</h5>
                                                <p class="pt-1">HI! Thanks for following</p>
                                            </div>
                                        </div>
                                        <div class="so-message-active-now">
                                            <p>Now</p>
                                        </div>
                                    </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade all-messages-lists-messages" id="profile" role="tabpanel"
                        aria-labelledby="profile-tab"></div>
                    <div class="tab-pane fade all-messages-lists-messages" id="contact" role="tabpanel"
                        aria-labelledby="contact-tab">...</div>
                </div>
                <!-- <div class="all-message-options">
                        <a href="#" class="active">
                            <h6>Messages</h6>
                        </a>
                        <a href="#">
                            <h6>Favorites</h6>
                        </a>
                        <a href="#">
                            <h6>Requests</h6>
                        </a>
                    </div>
                    <div class="all-messages-lists">
                        <div class="all-messages-lists-messages">
                            <img src="assets/img/sellers12.cms" alt="fetish">
                            <div class="all-message-lists-name">
                                <h5>MadamByNature</h5>
                                <p>HI! Thanks for following</p>
                            </div>
                            <div class="so-message-active-now">
                                <p>Now</p>
                            </div>
                        </div>
                        <div class="all-messages-lists-messages">
                            <img src="assets/img/sellers12.cms" alt="fetish">
                            <div class="all-message-lists-name">
                                <h5>MadamByNature</h5>
                                <p>HI! Thanks for following</p>
                            </div>
                            <div class="so-message-active-now">
                                <p>Now</p>
                            </div>
                        </div>
                        <div class="all-messages-lists-messages">
                            <img src="assets/img/sellers12.cms" alt="fetish">
                            <div class="all-message-lists-name">
                                <h5>MadamByNature</h5>
                                <p>HI! Thanks for following</p>
                            </div>
                            <div class="so-message-active-now">
                                <p>Now</p>
                            </div>
                        </div>
                        <div class="all-messages-lists-messages">
                            <img src="assets/img/sellers12.cms" alt="fetish">
                            <div class="all-message-lists-name">
                                <h5>MadamByNature</h5>
                                <p>HI! Thanks for following</p>
                            </div>
                            <div class="so-message-active-now">
                                <p>Now</p>
                            </div>
                        </div>
                        s
                    </div> -->
            </div>
            <div class="col-md-7 so-see-single-msg">
                <div class="tab-pane fade show active all-messages-chats" id="MadamByNature" role="tabpanel"
                    aria-labelledby="home-tab">
                    <div class="message-user-chats">
                        <div class="message-user-name d-flex">
                            <figure>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers11.webp"
                                    alt="Profile Image" style=" object-fit: cover;">
                            </figure>
                            <h5>Madam By Nature</h5>
                        </div>
                        <div class="message-user-chat-ratings">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-three-dots-vertical"></i>
                        </div>
                    </div>
                    <div class="message-user-chats-messages">
                        <div class="message-recieved">
                            <p>Hellooooo!!!</p>
                        </div>
                        <div class="message-recieved-time">
                            <p>12:16 am, Tuesday, May 2nd 2023</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent-time">
                            <p>12:16 am, Tuesday, May 2nd 2023</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved-time">
                            <p>12:16 am, Tuesday, May 2nd 2023</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved-time">
                            <p>12:16 am, Tuesday, May 2nd 2023</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>


                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved-time">
                            <p>12:16 am, Tuesday, May 2nd 2023</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent-time">
                            <p>12:16 am, Tuesday, May 2nd 2023</p>
                        </div>
                    </div>
                    <div class="so-send-messages-field pt-3">
                        <input type="text" name="send-message" id="send-message" placeholder="Type a message.....">
                        <label for="sendmessage"></label>
                        <!-- <input type="file" name="file-choose" id="">
                        <i class="bi bi-image"></i></a> -->

                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <label for="file-choose">
                                <span class="icon">
                                    <i class="bi bi-image"></i></i> <!-- Replace with your desired icon class -->
                                </span>
                            </label>
                            <input type="file" name="file-choose" id="file-choose" style="display: none;">
                        </form>

                        <button>Send</button>
                    </div>
                </div>
                <div class="tab-pane fade active all-messages-chats" id="SaraGray" role="tabpanel"
                    aria-labelledby="home-tab">
                    <div class="message-user-chats">
                        <div class="message-user-name d-flex">
                            <figure>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sellers10.jpg"
                                    alt="Profile Image" style=" object-fit: cover;">
                            </figure>
                            <h5>Sara</h5>
                        </div>
                        <div class="message-user-chat-ratings">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-three-dots-vertical"></i>
                        </div>
                    </div>
                    <div class="message-user-chats-messages">
                        <div class="message-recieved">
                            <p>Hellooooo!!!</p>
                        </div>

                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent media">
                            <figure>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/seller6.jpeg"
                                    alt="Profile Image" style=" object-fit: cover;">
                            </figure>
                        </div>
                    </div>
                    <div class="so-send-messages-field pt-3">
                        <input type="text" name="send-message" id="send-message" placeholder="Type a message.....">
                        <label for="sendmessage"></label>
                        <!-- <input type="file" name="file-choose" id="">
                        <i class="bi bi-image"></i></a> -->

                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <label for="file-choose">
                                <span class="icon">
                                    <i class="bi bi-image"></i></i> <!-- Replace with your desired icon class -->
                                </span>

                            </label>
                            <input type="file" name="file-choose" id="file-choose" style="display: none;">

                        </form>

                        <button>Send</button>
                    </div>
                </div>
                <div class="tab-pane fade active all-messages-chats" id="Jessiehot" role="tabpanel"
                    aria-labelledby="home-tab">
                    <div class="message-user-chats">
                        <div class="message-user-name d-flex">
                            <figure>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/seller6.jpeg"
                                    alt="Profile Image" style=" object-fit: cover;">
                            </figure>
                            <h5>Jessiehot</h5>
                        </div>
                        <div class="message-user-chat-ratings">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-three-dots-vertical"></i>
                        </div>
                    </div>
                    <div class="message-user-chats-messages">
                        <div class="message-recieved">
                            <p>Hellooooo!!!</p>
                        </div>

                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>

                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-recieved">
                            <p>Let me tell you, they will smell like wild flowers</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent">
                            <p>Some guy asked me to take picture of me smelling my own feet...Why not?</p>
                        </div>
                        <div class="message-sent media">
                            <figure>
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/seller6.jpeg"
                                    alt="Profile Image" style=" object-fit: cover;">
                            </figure>
                        </div>
                    </div>
                    <div class="so-send-messages-field pt-3">
                        <input type="text" name="send-message" id="send-message" placeholder="Type a message.....">
                        <label for="sendmessage"></label>
                        <!-- <input type="file" name="file-choose" id="">
                        <i class="bi bi-image"></i></a> -->

                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <label for="file-choose">
                                <span class="icon">
                                    <i class="bi bi-image"></i></i> <!-- Replace with your desired icon class -->
                                </span>

                            </label>
                            <input type="file" name="file-choose" id="file-choose" style="display: none;">

                        </form>

                        <button>Send</button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</div>

<?php get_footer(); ?>