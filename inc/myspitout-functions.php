<?php
add_action('wp_ajax_so_upload_product_image', 'so_upload_product_image');
function so_upload_product_image()
{
    $upload_overrides = array('test_form' => false);
    if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {

        // WordPress environmet
        require(ABSPATH . '/wp-load.php');
        // it allows us to use wp_handle_upload() function
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        // validation
        if (empty($_FILES['file'])) {
            wp_die('No files selected.');
        }
        $upload_landing_video = wp_handle_upload($_FILES['file'], $upload_overrides);
        // var_dump($upload_landing_video);
        if (!empty($upload_landing_video['error'])) {
            echo 'error vo';
            wp_die($upload_landing_video['error']);
        }
        // it is time to add our uploaded image into WordPress media library
        $msg_attachment_id = wp_insert_attachment(
            [
                'guid' => $upload_landing_video['url'],
                'post_mime_type' => $upload_landing_video['type'],
                'post_title' => basename($upload_landing_video['file']),
                'post_content' => '',
                'post_status' => 'inherit',
            ],
            $upload_landing_video['file']
        );
        if (is_wp_error($msg_attachment_id) || !$msg_attachment_id) {
            wp_die('Upload error.');
        }

        wp_send_json_success($msg_attachment_id);
    }
    die();
}


add_action('wp_ajax_so_add_new_product', 'so_add_new_product');
function so_add_new_product()
{
    $attachment_id = isset($_POST['product_image']) ? (int)$_POST['product_image'] : 0;

    //create new product
    $product_name = sanitize_text_field($_POST['product_name']);
    $get_currency_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();

    $usd_price = floatval($_POST['price']);
    $price = truncate_decimal_places(($usd_price / $get_currency_exchange_rate), 6);
    $additional_info = sanitize_textarea_field($_POST['additional_info']);
    $additional_info = sanitize_textarea_field($_POST['additional_info']);
    $product_icon = ($_POST['product_icon']);
    $product = new WC_Product_Simple();

    $product->set_name($product_name);
    $product->set_regular_price($price);
    $product->set_short_description($additional_info);
    if ($attachment_id != 0) {
        $product->set_image_id($attachment_id);
    }
    $product->save();

    $product_id = $product->get_id();
    update_post_meta($product_id, '_product_spitout_icon', $product_icon);
    update_post_meta($product_id, '_regular_price_wmcp', json_encode(['USD' => (string)$usd_price]));
    update_post_meta($product_id, 'current_exchange_rate', $get_currency_exchange_rate);
    $assign_product_seller = array(
        'ID' => $product_id,
        'post_author' => get_current_user_id(),
    );

    // Update the seller of the product
    wp_update_post($assign_product_seller);

    wp_die();
}


add_action('wp_ajax_so_retrieve_seller_products', 'so_retrieve_seller_products');
function so_retrieve_seller_products()
{
    $seller_id = (int)$_POST['seller_id'];

    $published_products = wc_get_products(
        [
            'status' => 'publish',
            'limit' => -1,
            'author' => $seller_id
        ]
    );

    $drafted_products = wc_get_products(
        [
            'status' => 'draft',
            'limit' => -1,
            'author' => $seller_id
        ]
    );

    $all_productss = array_merge($published_products, $drafted_products);

    foreach ($all_productss as $product) {

        $selectedIcon = get_post_meta($product->get_id(), '_product_spitout_icon', true);
        if ($selectedIcon) {
            $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/' . $selectedIcon . '.png';
        } else {
            $get_product_icon = get_stylesheet_directory_uri() . '/assets/img/saliva.png';
        }

?>
        <tr class="so-my-spitout-border product-row-<?php echo $product->get_id(); ?>">
            <td class="so-my-spitout-productName">
                <img src="<?php echo $get_product_icon; ?> ">
                <?php echo $product->get_name(); ?>
            </td>
            <td>
                <?php echo (new DateTime($product->get_date_created()))->format('m.d.Y'); ?>
            </td>
            <td>
                <?php echo get_post_meta((int) $product->get_id(), 'total_sales', true); ?>
            </td>
            <td>

                <?php
                echo static_currency_generator_for_products((int) $product->get_id(), true);
                ?>
            </td>
            <td>
                <!-- Delete product button -->
                <button type="button" class="btn btn-danger so-my-spitout-deteteBtn" data-product-id="<?php echo $product->get_id(); ?>" data-toggle="modal" data-target="#so-my-spitout-deteteModal">
                    Delete
                </button>

                <!-- Edit product button -->
                <button type="button" class="btn btn-warning so-my-spitout-editBtn jjj" data-toggle="modal" data-target="#so-my-spitout-editModal" data-product-id="<?php echo $product->get_id(); ?>" data-product-name="<?php echo $product->get_name(); ?>" data-product-price="<?php echo (json_decode(get_post_meta((int) $product->get_id(), '_regular_price_wmcp', true), true))['USD']; ?>" data-product-info="<?php echo $product->get_short_description(); ?>" data-product-icon="<?php echo $selectedIcon; ?>" data-product-img="<?php echo wp_get_attachment_url(get_post_thumbnail_id((int)$product->get_id())); ?>">
                    Edit
                </button>

                <!-- Hide product button -->
                <button type="submit" class="btn btn-primary so-my-spitout-hideBtn" name="hide_product" data-product-id="<?php echo $product->get_id(); ?>">
                    <?php echo get_post_status($product->get_id()) == 'publish' ? 'Hide' : 'Unhide'; ?>
                </button>
            </td>
        </tr>
<?php
    }
}


add_action('wp_ajax_so_hide_unhide_products', 'so_hide_unhide_products');
function so_hide_unhide_products()
{
    $product_id = (int)$_POST['product_id'];
    $post_status = get_post_status($product_id) == 'publish' ? 'draft' : 'publish';

    $prodcut_status_update = wp_update_post([
        'ID'    =>  $product_id,
        'post_status'   =>  $post_status
    ]);

    if ($prodcut_status_update == 0 || is_wp_error($prodcut_status_update)) {
        wp_send_json_error('Some error occured, try refreshing the page');
    } else {
        wp_send_json_success('status_changed');
    }

    wp_die();
}


add_action('wp_ajax_so_delete_product', 'so_delete_product');
function so_delete_product()
{
    $delete_product = wp_delete_post((int)$_POST['product_id']);

    if ($delete_product == false || $delete_product == null) {
        wp_send_json_error('Failed to delete product');
    } else {
        wp_send_json_success('product_deleted');
    }
}


add_action('wp_ajax_so_edit_product', 'so_edit_product');
function so_edit_product()
{
    $attachment_id = isset($_POST['product_image']) ? (int)$_POST['product_image'] : 0;
    $additional_info = sanitize_textarea_field($_POST['product_info']);
    $objProduct = wc_get_product($_POST['product_id']);
    $get_currency_exchange_rate = spitout_get_currency_exchange_btc_usd_rate();

    $current_product_id = (int)$_POST['product_id'];

    if ($objProduct instanceof WC_Product) {
        $objProduct->set_name($_POST['product_name']);
        // $objProduct->set_regular_price(((int)$_POST['product_price']) / $get_currency_exchange_rate);
        $objProduct->set_regular_price(truncate_decimal_places(number_format(((int)$_POST['product_price'] / $get_currency_exchange_rate), 6), 6));
        update_post_meta($current_product_id, '_regular_price_wmcp', json_encode(["USD" => (string)$_POST['product_price']]));
        $objProduct->set_short_description($additional_info);
        if ($attachment_id != 0) {
            $objProduct->set_image_id($attachment_id);
        }
        $objProduct->save();

        update_post_meta($current_product_id, '_product_spitout_icon', $_POST['product_icon']);
        update_post_meta($current_product_id, 'current_exchange_rate', $get_currency_exchange_rate);
    }

    wp_die();
}
