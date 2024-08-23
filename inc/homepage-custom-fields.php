<?php

/**
 * Retrieving the values:
 * Top Banner Title = get_post_meta( get_the_ID(), 'spitout_meta_optionstop-banner-title', true )
 * Join Free = get_post_meta( get_the_ID(), 'spitout_meta_optionsjoin-free', true )
 * Sell Fetish = get_post_meta( get_the_ID(), 'spitout_meta_optionssell-fetish', true )
 * Desktop Banner Image = get_post_meta( get_the_ID(), 'spitout_meta_optionsdesktop-banner-image', true )
 * Mobile Banner Image = get_post_meta( get_the_ID(), 'spitout_meta_optionsmobile-banner-image', true )
 * Ipad Banner Image = get_post_meta( get_the_ID(), 'spitout_meta_optionsipad-banner-image', true )
 * Title Join Us CTA = get_post_meta( get_the_ID(), 'spitout_meta_optionstitle-join-us-cta', true )
 * Sub-Title Join Us  = get_post_meta( get_the_ID(), 'spitout_meta_optionssub-title-join-us', true )
 * Join Us Free Link = get_post_meta( get_the_ID(), 'spitout_meta_optionsjoin-us-free-link', true )
 * Title Shop = get_post_meta( get_the_ID(), 'spitout_meta_optionstitle-shop', true )
 * Sub-Title Shop CTA = get_post_meta( get_the_ID(), 'spitout_meta_optionssub-title-shop-cta', true )
 * Shop Link = get_post_meta( get_the_ID(), 'spitout_meta_optionsshop-link', true )
 */

class spitout_create_custom_field
{
    private $config = '{"title":"Spitout Custom Fields","prefix":"spitout_meta_options","domain":"spitout","class_name":"spitout_create_custom_field","post-type":["post","page"],"context":"normal","priority":"default","fields":[{"type":"text","label":"Top Banner Title","id":"spitout_meta_optionstop-banner-title"},{"type":"url","label":"Join Free","id":"spitout_meta_optionsjoin-free"},{"type":"url","label":"Sell Fetish","id":"spitout_meta_optionssell-fetish"},{"type":"media","label":"Desktop Banner Image","button-text":"Upload","return":"url","id":"spitout_meta_optionsdesktop-banner-image"},{"type":"media","label":"Ipad Banner Image","return":"url","id":"spitout_meta_optionsipad-banner-image"},{"type":"media","label":"Mobile Banner Image","return":"url","id":"spitout_meta_optionsmobile-banner-image"},{"type":"text","label":"Title Join Us CTA","id":"spitout_meta_optionstitle-join-us-cta"},{"type":"text","label":"Sub-Title Join Us ","id":"spitout_meta_optionssub-title-join-us"},{"type":"url","label":"Join Us Free Link","id":"spitout_meta_optionsjoin-us-free-link"},{"type":"text","label":"Title Shop","id":"spitout_meta_optionstitle-shop"},{"type":"text","label":"Sub-Title Shop CTA","id":"spitout_meta_optionssub-title-shop-cta"},{"type":"url","label":"Shop Link","id":"spitout_meta_optionsshop-link"}]}';

    public function __construct()
    {

        $this->config = json_decode($this->config, true);


        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('admin_head', [$this, 'admin_head']);
        add_action('save_post', [$this, 'save_post']);
    }

    public function add_meta_boxes()
    {


        global $post;

        // Check if the post has the specific template assigned
        $template = get_post_meta($post->ID, '_wp_page_template', true);

        // Compare the template with the target template name
        if ($template === 'page-templates/template-home-page.php') {
            add_meta_box(
                sanitize_title($this->config['title']),
                $this->config['title'],
                [$this, 'add_meta_box_callback'],
                'page',
                $this->config['context'],
                $this->config['priority']
            );
        }
    }

    public function admin_enqueue_scripts()
    {
        global $typenow;
        if (in_array($typenow, $this->config['post-type'])) {
            wp_enqueue_media();
        }
    }

    public function admin_head()
    {
        global $typenow;
        if (in_array($typenow, $this->config['post-type'])) {
            ?>
            <script>
                jQuery.noConflict();
                (function ($) {
                    $(function () {
                        $('body').on('click', '.rwp-media-toggle', function (e) {
                            e.preventDefault();
                            let button = $(this);
                            let rwpMediaUploader = null;
                            rwpMediaUploader = wp.media({
                                title: button.data('modal-title'),
                                button: {
                                    text: button.data('modal-button')
                                },
                                multiple: true
                            }).on('select', function () {
                                let attachment = rwpMediaUploader.state().get('selection').first().toJSON();
                                button.prev().val(attachment[button.data('return')]);
                            }).open();
                        });
                    });
                })(jQuery);
            </script>
            <?php
        }
    }

    public function save_post($post_id)
    {
        foreach ($this->config['fields'] as $field) {
            switch ($field['type']) {
                case 'url':
                    if (isset($_POST[$field['id']])) {
                        $sanitized = esc_url_raw($_POST[$field['id']]);
                        update_post_meta($post_id, $field['id'], $sanitized);
                    }
                    break;
                default:
                    if (isset($_POST[$field['id']])) {
                        // $sanitized = sanitize_text_field($_POST[$field['id']]);
                        $sanitized = ($_POST[$field['id']]);
                        update_post_meta($post_id, $field['id'], $sanitized);
                    }
            }
        }
    }

    public function add_meta_box_callback()
    {
        $this->fields_table();
    }

    private function fields_table()
    {
        ?>
        <table class="form-table" role="presentation">
            <tbody class="so-spit-dashboard-table">
                <?php
                foreach ($this->config['fields'] as $field) {
                    ?>
                    <tr>
                        <th scope="row">
                            <?php $this->label($field); ?>
                        </th>
                        <td>
                            <?php $this->field($field); ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }

    private function label($field)
    {
        switch ($field['type']) {
            case 'media':
                printf(
                    '<label class="" for="%s_button">%s</label>',
                    $field['id'],
                    $field['label']
                );
                break;
            default:
                printf(
                    '<label class="" for="%s">%s</label>',
                    $field['id'],
                    $field['label']
                );
        }
    }

    private function field($field)
    {
        switch ($field['type']) {
            case 'media':
                $this->input($field);
                $this->media_button($field);
                break;
            default:
                $this->input($field);
        }
    }

    private function input($field)
    {
        if ($field['type'] === 'media') {
            $field['type'] = 'text';
        }
        printf(
            '<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
            isset($field['class']) ? $field['class'] : '',
            $field['id'],
            $field['id'],
            isset($field['pattern']) ? "pattern='{$field['pattern']}'" : '',
            $field['type'],
            $this->value($field)
        );
    }

    private function media_button($field)
    {
        printf(
            ' <button class="button rwp-media-toggle" data-modal-button="%s" data-modal-title="%s" data-return="%s" id="%s_button" name="%s_button" type="button">%s</button>',
            isset($field['modal-button']) ? $field['modal-button'] : __('Select this file', 'spitout'),
            isset($field['modal-title']) ? $field['modal-title'] : __('Choose a file', 'spitout'),
            $field['return'],
            $field['id'],
            $field['id'],
            isset($field['button-text']) ? $field['button-text'] : __('Upload', 'spitout')
        );
    }

    private function value($field)
    {
        global $post;
        if (metadata_exists('post', $post->ID, $field['id'])) {
            $value = get_post_meta($post->ID, $field['id'], true);
        } else if (isset($field['default'])) {
            $value = $field['default'];
        } else {
            return '';
        }
        return str_replace('\u0027', "'", $value);
    }
}
new spitout_create_custom_field;