<?php
/**
 * The template for displaying product widget entries.
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}

?>
<li class="wgl_mini-cart_flex">
    <?php do_action( 'woocommerce_widget_product_item_start', $args ); ?>
    <div class="wgl_mini-cart_image">
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
            <?php
            if(function_exists('aq_resize')){
                $image_data = wp_get_attachment_metadata($product->get_image_id());
                $image_meta = isset($image_data['image_meta']) ? $image_data['image_meta'] : array();
	            $width = $height = apply_filters('wgl_woo_mini_thumbnail_size', '80');
                $image_url = wp_get_attachment_image_src( $product->get_image_id(), 'full', false );
                $image_url[0] = aq_resize($image_url[0], $width, $height, true, true, true);

                $image_meta['title'] = isset($image_meta['title']) ? $image_meta['title'] : "";

                echo "<img src='" . esc_url( $image_url[0] ) . "' alt='" . esc_attr($image_meta['title']) . "' />";
            }else{
                echo WGL_Framework::render_html($product->get_image()); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
             // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </a>
    </div>
    <div class="wgl_mini-cart_contents">
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo wp_kses_post( $product->get_name() ); ?></a>
        <?php if ( ! empty( $show_rating ) ) : ?>
            <?php echo wc_get_rating_html( $product->get_average_rating() ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php endif; ?>
        <p class="price">
            <?php echo WGL_Framework::render_html( $product->get_price_html() ); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </p>
    </div>

    <?php do_action( 'woocommerce_widget_product_item_end', $args ); ?>
</li>
