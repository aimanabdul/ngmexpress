<?php
/**
 * Cross-sells
 *
 * This template is overridden by WebGeniusLab team.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

use WGL_Extensions\Includes\WGL_Carousel_Settings;
wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper/js/swiper-bundle.min.js', array(), false, false);
wp_enqueue_style('swiper', get_template_directory_uri() . '/js/swiper/css/swiper-bundle.min.css');

if ( $cross_sells ) : ?>

	<div class="cross-sells">
		<?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'transmax' ) );

		if ( $heading ) : ?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php woocommerce_product_loop_start();

            ob_start();

                foreach ( $cross_sells as $cross_sell ) :

                    global $product;

                    $post_object = get_post( $cross_sell->get_id() );

                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                    wc_get_template_part( 'content', 'product' );

                endforeach;

            $products_items = ob_get_clean();

            $options = [
                // General
                'slides_per_row' => 2,
                'autoplay' => false,
	            'slide_per_single' => count((array)$cross_sells) > 3 ? false : true,
	            'slider_infinite' => count((array)$cross_sells) > 1 ? true : false,
                // Pagination
	            'use_pagination' => count((array)$cross_sells) > 1 ? true : false,
                'pagination_type' => 'circle',
                // Responsive
                'customize_responsive' => true,
	            'desktop_breakpoint' => 1200,
                'desktop_slides' => 2,
	            'tablet_breakpoint' => 768,
                'tablet_slides' => 1,
	            'mobile_breakpoint' => 280,
                'mobile_slides' => 1,
                'responsive_gap' => [
                    'desktop_gap' => ['size' => 30],
                    'tablet_gap'  => ['size' => 20],
                    'mobile_gap'  => ['size' => 20],
                ],
	            'extra_class' => 'number_of_slides-'.count((array)$cross_sells),
            ];

            if (class_exists('Transmax_Core') && class_exists('WGL_Extensions_Core')) {
		        echo WGL_Carousel_Settings::init( $options, $products_items );
	        }else{
		        echo WGL_Framework::render_html($products_items);
            }

        woocommerce_product_loop_end(); ?>

	</div>
	<?php
endif;

wp_reset_postdata();
