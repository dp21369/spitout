<?php
/**
 * This file contains all the CRUD functions related to the 'Catergories'
 * post type
 */



// Register a custom post type called "spit-categories".
add_action('init', 'spitout_register_spit_categories');
function spitout_register_spit_categories()
{
    $labels = array(
        'name' => _x('Seller Types', 'Post type general name', 'spitout'),
        'singular_name' => _x('Seller Type', 'Post type singular name', 'spitout'),
        'menu_name' => _x('Seller Types', 'Admin Menu text', 'spitout'),
        'name_admin_bar' => _x('Seller Types', 'Add New on Toolbar', 'spitout'),
        'add_new' => __('Add New', 'spitout'),
        'add_new_item' => __('Add New Seller Type', 'spitout'),
        'new_item' => __('New Seller Type', 'spitout'),
        'edit_item' => __('Edit Seller Type', 'spitout'),
        'view_item' => __('View Seller Type', 'spitout'),
        'all_items' => __('All Seller Types', 'spitout'),
        'search_items' => __('Search Seller Types', 'spitout'),
        'parent_item_colon' => __('Parent Seller Types:', 'spitout'),
        'not_found' => __('No type found.', 'spitout'),
        'not_found_in_trash' => __('No type found in Trash.', 'spitout'),
        'featured_image' => _x('Seller Type Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'spitout'),
        'set_featured_image' => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'spitout'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'spitout'),
        'use_featured_image' => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'spitout'),
        'archives' => _x('Seller Type archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'spitout'),
        'insert_into_item' => _x('Insert into type', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'spitout'),
        'uploaded_to_this_item' => _x('Uploaded to this type', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'spitout'),
        'filter_items_list' => _x('Filter typelist', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'spitout'),
        'items_list_navigation' => _x('Types list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'spitout'),
        'items_list' => _x('Types list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'spitout'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'spit-category'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
    );

    register_post_type('spit-category', $args);
}


add_action("admin_init", "so_custom_field_for_featured_category");
function so_custom_field_for_featured_category()
{
    add_meta_box("featured_category", __('Featured Category', 'spitout'), "so_featured_category_checkbox", "spit-category", "side", "low");
}


function so_featured_category_checkbox()
{
    global $post;
    ?>

    <label for="featured_cat">
        <?php echo __('Set as Featured Category', 'spitout') ?>
    </label>&nbsp;
    <?php $featured_cat_value = get_post_meta($post->ID, 'featured_cat', true);

    $featured_cat_checked = '';

    if ($featured_cat_value == "yes") {
        $featured_cat_checked = 'checked="checked"';
    }
    ?>
    <input type="checkbox" name="featured_cat" value="yes" <?php echo $featured_cat_checked; ?> />
    <?php
}

// save some category as featured category
add_action('save_post_spit-category', 'so_featured_category_save');
function so_featured_category_save()
{
    global $post;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post->ID;
    }

    if (isset($_POST['featured_cat'])) {
        update_post_meta($post->ID, "featured_cat", 'yes');
    } else {
        update_post_meta($post->ID, "featured_cat", 'no');
    }
}