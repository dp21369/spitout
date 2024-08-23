<?php

/**
 * Template Name: Category Page
 * @package spitout
 */
get_header();
?>



<!-- -----------------------------
Popular Category  
----------------------------------->
<section class="so-popular-category">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="so-popular-category-block">
                    <h4>Popular Categories</h4>
                    <ul class="category-list">
                        <?php

                        $get_spit_featured_category = get_posts(array(
                            'numberposts' => -1,   // -1 returns all posts
                            'post_type' => 'spit-category',
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'post_status' => 'publish',
                            'meta_key'         => 'featured_cat',
                            'meta_value'       => 'yes'
                        ));
                        foreach ($get_spit_featured_category as $key => $get_featured_spit_cat) {
                            # code...
                            $category_image = wp_get_attachment_url(get_post_thumbnail_id($get_featured_spit_cat->ID));
                            $default_category_image = get_stylesheet_directory_uri() . '/assets/img/logo-mob.png';
                            if ($category_image) {
                                $category_image_figure = '<figure>
                                    <img src="' . $category_image . '" alt="' . $get_featured_spit_cat->post_title . '">
                                </figure>';
                            } else {
                                $category_image_figure = '<figure>
                                    <img src="' . $default_category_image . '" alt="' . $get_featured_spit_cat->post_title . '">
                                </figure>';
                            }
                            echo '  <li>
                            <a href="' . get_permalink($get_featured_spit_cat->ID) . '">
                              ' . $category_image_figure . '
                                <h5>' . $get_featured_spit_cat->post_title . '</h5>
                            </a>
                        </li>';
                        }
                        ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- -----------------------------
All Category  
----------------------------------->
<section class="so-all-category">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h5>All Category</h3>
                    <div class="so-browse-categories mt-4">
                        <ul class="so-browse-categories-lists">

                            <?php

                            $get_spit_category = get_posts(array(
                                'numberposts' => -1,   // -1 returns all posts
                                'post_type' => 'spit-category',
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'post_status' => 'publish'
                            ));

                            foreach ($get_spit_category as $key => $get_spit_cat) {
                                # code...
                                echo '  <li>
                            <a href="' . get_permalink($get_spit_cat->ID) . '">
                                <h5>' . $get_spit_cat->post_title . '</h5>
                            </a>
                        </li>';
                            }

                            ?>
                        </ul>
                    </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>