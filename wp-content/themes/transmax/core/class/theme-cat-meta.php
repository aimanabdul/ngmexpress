<?php
defined('ABSPATH') || exit;

if (!class_exists('WGL_Extensions_Core')) {
    return;
}

if (!class_exists('WGL_Cat_Images')) {
/**
 * WGL Categories Images
 *
 *
 * @package transmax\core\class
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Cat_Images
{
    /*
     * https://catapultthemes.com/adding-an-image-upload-field-to-categories/
     * Initialize the class and start calling our hooks and filters
     * @since 1.0.0
     */
    public function init()
    {
        add_action( 'category_edit_form_fields', array ( $this, 'category_fields' ));
        add_action( 'category_add_form_fields', array ( $this, 'add_category_image' ), 10, 2 );
        add_action( 'category_edit_form_fields', array ( $this, 'update_category_image' ), 10, 2 );
        add_action( 'created_category', array ( $this, 'updated_category_image' ), 10, 2 );
        add_action( 'edited_category', array ( $this, 'updated_category_image' ), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );

        // Add form
        add_action( 'product_cat_add_form_fields', array( $this, 'add_category_icons' ) );
        add_action( 'product_cat_edit_form_fields', array( $this, 'update_category_product_icons' ), 10 );
        add_action( 'created_term', array( $this, 'save_category_fields_icon' ), 10, 3 );
        add_action( 'edit_term', array( $this, 'save_category_fields_icon' ), 10, 3 );
    }

    /**
     * Add new colopicker field to "Add New Category" screen
     * - https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
     *
     * @param WP_Term_Object $term
     *
     * @return void
     */
    function category_fields( $term ) { //check for existing featured ID

        $color = get_term_meta( $term->term_id, '_category_color', true );

        $theme_color = WGL_Framework::get_option('theme-headings-color');
        $color = ( ! empty( $color ) ) ? "#{$color}" : $theme_color;

        ?>
        <tr class="form-field term-colorpicker-wrap">
            <th scope="row"><label for="term-colorpicker"><?php echo esc_html__( 'Color', 'transmax' );?></label></th>
            <td>
                <input name="_category_color" value="<?php echo esc_attr($color); ?>" class="colorpicker" id="term-colorpicker" />
            </td>
        </tr>
        <?php
    }

    public function load_media()
    {
        wp_enqueue_script('transmax-cat-meta', get_template_directory_uri() . '/core/admin/js/cat_img_upload.js', array(), false, false);
        wp_enqueue_media();
        if ( null !== ( $screen = get_current_screen() ) && 'edit-category' !== $screen->id ) {
            return;
        }

        // Colorpicker Scripts
        wp_enqueue_script( 'wp-color-picker' );

        // Colorpicker Styles
        wp_enqueue_style( 'wp-color-picker' );
    }

    /*
    * Add a form field in the new category page
    * @since 1.0.0
    */
    public function add_category_image ( $taxonomy )
    {
        $theme_color = WGL_Framework::get_option('theme-headings-color');
        ?>

        <div class="form-field term-colorpicker-wrap">
                <label for="term-colorpicker"><?php echo esc_html__( 'Color', 'transmax' );?></label>
                <input name="_category_color" value="<?php echo esc_attr($theme_color);?>" class="colorpicker" id="term-colorpicker" />
        </div>
        <div class="form-field term-group wgl-image-form">
            <label for="category-image-id"><?php esc_html_e('Background Image', 'transmax'); ?></label>
            <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
            <div class="category-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'transmax' ); ?>" />
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'transmax' ); ?>" />
            </p>
        </div>
        <div class="form-field term-group wgl-image-form">
            <label for="category-icon-image-id"><?php esc_html_e('Icon Image', 'transmax'); ?></label>
            <input type="hidden" id="category-icon-image-id" name="category-icon-image-id" class="custom_media_url" value="">
            <div class="category-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button_icon" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'transmax' ); ?>" />
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove_icon" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'transmax' ); ?>" />
            </p>
        </div>
        <?php
    }

    /*
    * Add a form field in the new category page
    * @since 1.0.0
    */
    public function add_category_icons()
    {
        ?>
        <div class="form-field term-group wgl-image-form">
            <label for="category-icon-image-id"><?php esc_html_e('Icon Image', 'transmax'); ?></label>
            <input type="hidden" id="category-icon-image-id" name="category-icon-image-id" class="custom_media_url" value="">
            <div class="category-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button_icon" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'transmax' ); ?>" />
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove_icon" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'transmax' ); ?>" />
            </p>
        </div>
        <?php
    }

    /*
    * Edit the form field
    * @since 1.0.0
    */
    public function update_category_image( $term, $taxonomy )
    {
        ?>
        <tr class="form-field term-group-wrap wgl-image-form">
            <th scope="row">
                <label for="category-image-id"><?php esc_html_e( 'Background Image', 'transmax' ); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta ( $term -> term_id, 'category-image-id', true ); ?>
                <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="<?php echo esc_attr($image_id); ?>">
                <div class="category-image-wrapper">
                    <?php if ( $image_id ) { ?>
                        <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'transmax' ); ?>" />
                    <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'transmax' ); ?>" />
                </p>
            </td>
        </tr>
        <tr class="form-field term-group-wrap wgl-image-form">
            <th scope="row">
                <label for="category-icon-image-id"><?php esc_html_e( 'Icon Image', 'transmax' ); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta ( $term -> term_id, 'category-icon-image-id', true ); ?>
                <input type="hidden" id="category-icon-image-id" name="category-icon-image-id" class="custom_media_url" value="<?php echo esc_attr($image_id); ?>">
                <div class="category-image-wrapper">
                    <?php if ( $image_id ) { ?>
                        <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button_icon" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'transmax' ); ?>" />
                    <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove_icon" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'transmax' ); ?>" />
                </p>
            </td>
        </tr>
        <?php
    }

    /*
    * Edit the form field
    * @since 1.0.0
    */
    public function update_category_product_icons($term)
    {
        ?>
        <tr class="form-field term-group-wrap wgl-image-form">
            <th scope="row">
                <label for="category-icon-image-id"><?php esc_html_e( 'Icon Image', 'transmax' ); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta ( $term -> term_id, 'category-icon-image-id', true ); ?>
                <input type="hidden" id="category-icon-image-id" name="category-icon-image-id" class="custom_media_url" value="<?php echo esc_attr($image_id); ?>">
                <div class="category-image-wrapper">
                    <?php if ( $image_id ) { ?>
                        <?php echo wp_get_attachment_image ( $image_id, 'thumbnail' ); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button_icon" name="ct_tax_media_button" value="<?php esc_attr_e( 'Add Image', 'transmax' ); ?>" />
                    <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove_icon" name="ct_tax_media_remove" value="<?php esc_attr_e( 'Remove Image', 'transmax' ); ?>" />
                </p>
            </td>
        </tr>
        <?php
    }

    /*
    * Update the form field value
    * @since 1.0.0
    */
    public function updated_category_image($term_id, $tt_id)
    {
        if ( isset( $_POST['category-image-id'] ) && '' !== $_POST['category-image-id'] ){
            $image = sanitize_text_field($_POST['category-image-id']);
            update_term_meta ( $term_id, 'category-image-id', $image );
        } else {
            delete_term_meta ( $term_id, 'category-image-id' );
        }
        if ( isset( $_POST['category-icon-image-id'] ) && '' !== $_POST['category-icon-image-id'] ){
            $image = sanitize_text_field($_POST['category-icon-image-id']);
            update_term_meta ( $term_id, 'category-icon-image-id', $image );
        } else {
            delete_term_meta ( $term_id, 'category-icon-image-id' );
        }

        if ( isset( $_POST['_category_color'] ) && ! empty( $_POST['_category_color'] ) ) {
            update_term_meta( $term_id, '_category_color', sanitize_hex_color_no_hash( $_POST['_category_color'] ) );
        } else {
            delete_term_meta( $term_id, '_category_color' );
        }
    }

    /*
    * Update the form field value
    * @since 1.0.0
    */
    public function save_category_fields_icon($term_id, $tt_id)
    {
        if ( isset( $_POST['category-icon-image-id'] ) && '' !== $_POST['category-icon-image-id'] ){
            $image = sanitize_text_field($_POST['category-icon-image-id']);
            update_term_meta ( $term_id, 'category-icon-image-id', $image );
        } else {
            update_term_meta ( $term_id, 'category-icon-image-id', '' );
        }
    }
}

$WGL_Cat_Images = new WGL_Cat_Images();
$WGL_Cat_Images -> init();

}