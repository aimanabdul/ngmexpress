<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/templates/wgl-products-grid.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\{
    WGL_Loop_Settings,
    WGL_Carousel_Settings
};
use WGL_Framework;

/**
 * WGL Elementor Products Grid Template
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGLProductsGrid
{
    private $attributes;
    private $query;

    public function render($attributes = [], $self = false)
    {
        $this->attributes = $attributes;
        $this->item = !empty($self) ? $self : $this;
        $this->query = $this->formalize_query();

        $_ = $attributes; // assign shorthand for attributes array

        $wgl_def_atts = array(
            'query' => $this->query,
            // General
            'products_layout' => '',
            'products_title' => '',
            'products_subtitle' => '',
            // Content
            'products_columns' => '',
            'remainings_loading_btn_items_amount'  => '4',
            'products_style' => 'grid',
        );

        global $wgl_products_atts;
        $wgl_products_atts = array_merge($wgl_def_atts ,array_intersect_key($this->attributes, $wgl_def_atts));
        $wgl_products_atts['post_count'] = $this->query->post_count;
        $wgl_products_atts['query_args'] = $this->query->query_vars;
        $wgl_products_atts['atts'] = $this->attributes;

        echo '<section class="wgl_cpt_section wgl-products-grid woocommerce">';

        if ($_['isotope_filter'] && 'carousel' !== $_['products_layout']) {
            echo WGL_Framework::render_html($this->_render_filter());
        }
        // Load the template orderby

        if((bool) $_['show_header_products']){
            echo '<div class="wgl-woocommerce-sorting">';

                if((bool) $_['show_res_count']){
                    // Load the template result count.
                    wc_get_template('addons/addons-result-count.php', [
                        'query' => $this->query,
                    ]);
                }

                if((bool) $_['show_sorting']){
                    // Load the template orderby
                    wc_get_template('addons/addons-orderby.php', [
                        'query' => $this->query,
                    ]);
                }

            echo '</div>';
        }

        echo '<div class="wgl-products-catalog wgl-products-wrapper', $this->_get_wrapper_classes(), '">';

        echo '<div class="wgl-products container-grid', $this->_get_isotope_classes(), '">';

		    if ('carousel' === $_['products_layout']) {
			    ob_start();
			    get_template_part('templates/shop/products', 'grid');
			    $products_items = ob_get_clean();
			    echo $this->apply_carousel_settings($products_items);
		    }else{
			    get_template_part('templates/shop/products', 'grid');
		    }

        echo '</div>';

        echo '</div>';

        $this->render_navigation_section();

        echo '</section>';

        unset($wgl_products_atts); // clear global var
    }

    protected function formalize_query()
    {
        list($query_args) = WGL_Loop_Settings::buildQuery($this->attributes);

        $query_args['post_type'] = 'product';

        //* Add Page to Query
        global $paged;
        if (empty($paged)) {
            $paged = get_query_var('page') ?: 1;
        }
        $query_args['paged'] = $paged;

        $tax = array();
        $product_catalog_terms  = wc_get_product_visibility_term_ids();
        $product_not_in = array($product_catalog_terms['exclude-from-catalog']);
        if ( ! empty( $product_not_in ) ) {
            $tax[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_not_in,
                'operator' => 'NOT IN',
            );
        }

        if(isset($_GET['orderby']) && !empty($_GET['orderby'])){
            $orderby_value = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

            // Get order + orderby args from string
            $orderby_value = explode('-', $orderby_value);
            $orderby = esc_attr($orderby_value[0]);
            $order = ! empty( $orderby_value[1] ) ? $orderby_value[1] : '';

            $orderby = strtolower( $orderby );
            $order   = strtoupper( $order );

            $ordering_args = WC()->query->get_catalog_ordering_args( $orderby, $order );
            $meta_query    = WC()->query->get_meta_query();

            $query_args['orderby'] = $ordering_args['orderby'];
            $query_args['order'] = $ordering_args['order'];

            if ( $ordering_args['meta_key'] ) {
                $query_args['meta_key']       = $ordering_args['meta_key'];
            }

            if ('price' === $_GET['orderby']) {
                $query_args['order'] = 'ASC';
            }
        }

        $query_args['tax_query'][] = $tax;

        return WGL_Loop_Settings::cache_query($query_args);
    }

    protected function _get_wrapper_classes()
    {
        $_ = $this->attributes;

        $class = !empty($_['grid_columns']) ? ' columns-' . $_['grid_columns'] : '';
        $class .= !empty($_['grid_columns_tablet']) ? ' columns-tablet-' . $_['grid_columns_tablet'] : '';
        $class .= !empty($_['grid_columns_mobile']) ? ' columns-mobile-' . $_['grid_columns_mobile'] : '';
        $class .= 'carousel' === $_['products_layout'] ? ' carousel' : '';

        return esc_attr($class);
    }

    protected function _get_isotope_classes()
    {
        $_ = $this->attributes;
        $class = '';
        if ('masonry' === $_['products_layout'] || $_['isotope_filter'] || $this->attributes['products_navigation'] == 'load_more') {
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('isotope', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
            $class = ' isotope';
        }
        $class .= 'grid' === $_['products_layout'] ? ' fit_rows' : '';

        return esc_attr($class);
    }

    protected function _render_filter()
    {
        list($query_args) = WGL_Loop_Settings::buildQuery($this->attributes);
        $data_category = $query_args['tax_query'] ?? [];
        $include = $exclude = [];
	    $class = $this->attributes['filter_alignment'] ? ' filter-' . $this->attributes['filter_alignment'] : '';
	    $class .= $this->attributes['filter_alignment_tablet'] ? ' filter-tablet-' . $this->attributes['filter_alignment_tablet'] : '';
	    $class .= $this->attributes['filter_alignment_mobile'] ? ' filter-mobile-' . $this->attributes['filter_alignment_mobile'] : '';
	    $class .= $this->attributes['filter_counter_enabled'] ? ' has_filter_counter' : '';
	    $class .= $this->attributes['filter_max_width_enabled'] ? ' max_width_enabled' : '';

	    if ( isset($data_category[0]) ) {
            foreach ($data_category[0]['terms'] as $value) {
                $idObj = get_term_by( 'slug', $value, 'product_cat' );
                $id_list[] = $idObj ? $idObj->term_id : '';
            }
            switch ($data_category[0]['operator']) {
                case 'NOT IN':
                    $exclude = implode(',', $id_list);
                    break;
                case 'IN':
                    $include = implode(',', $id_list);
                    break;
            }
        }
        $cats = get_terms( [
            'taxonomy' => 'product_cat',
            'include' => $include,
            'exclude' => $exclude,
            'hide_empty' => true
        ] );
        $filter = '<div class="wgl-filter_wrapper product__filter isotope-filter'. esc_attr($class) . '">';
	    $filter .= '<div class="wgl-filter_swiper_wrapper">';
	    $filter .= '<div class="swiper-wrapper">';
        $filter .= '<a href="#" data-filter=".product" class="swiper-slide active">' . esc_html__('All', 'transmax-core') . '<span class="filter_counter"></span></a>';
        foreach ( $cats as $cat ) {
            if ( $cat->count > 0 ) {
                $filter .= '<a class="swiper-slide" href="'.get_term_link($cat->term_id, 'product_cat').'" data-filter=".product_cat-'.$cat->slug.'">';
                $filter .= $cat->name;
                $filter .= '<span class="filter_counter"></span>';
                $filter .= '</a>';
            }
        }
        $filter .= '</div>';
        $filter .= '</div>';
        $filter .= '</div>';

        return $filter;
    }

    protected function apply_carousel_settings($product_items)
    {
        $this->attributes['products_gap'] = !empty($this->attributes['products_gap']['size']) ? $this->attributes['products_gap'] : ['size' => '30'];
        $options = [
            // General
            'slides_per_row' => $this->attributes['grid_columns'],
            'autoplay' => $this->attributes['autoplay'],
            'autoplay_speed' => $this->attributes['autoplay_speed'],
            'slider_infinite' => $this->attributes['slider_infinite'],
            'slide_per_single' => $this->attributes['slide_per_single'],
            'fade_animation' => $this->attributes['fade_animation'],
            'center_mode' => $this->attributes['center_mode'],
            'adaptive_height' => true,
            // Pagination
            'use_pagination' => $this->attributes['use_pagination'],
            'pagination_type' => $this->attributes['pagination_type'],
            // Navigation
            'use_navigation' => $this->attributes['use_navigation'],
            'navigation_position' => $this->attributes['navigation_position'],
            'navigation_view' => $this->attributes['navigation_view'],
            // Responsive
            'customize_responsive' => $this->attributes['customize_responsive'],
            'desktop_breakpoint' => $this->attributes['desktop_breakpoint'],
            'desktop_slides' => $this->attributes['desktop_slides'],
            'tablet_breakpoint' => $this->attributes['tablet_breakpoint'],
            'tablet_slides' => $this->attributes['tablet_slides'],
            'mobile_breakpoint' => $this->attributes['mobile_breakpoint'],
            'mobile_slides' => $this->attributes['mobile_slides'],
            'responsive_gap' => [
                'desktop_gap' => $this->attributes['products_gap'],
                'tablet_gap' => !empty($this->attributes['products_gap_tablet']['size']) ? $this->attributes['products_gap_tablet'] : $this->attributes['products_gap'],
                'mobile_gap' => !empty($this->attributes['products_gap_mobile']['size']) ? $this->attributes['products_gap_mobile'] : $this->attributes['products_gap'],
            ],
        ];

        return WGL_Carousel_Settings::init($options, $product_items);
    }

    protected function render_navigation_section()
    {
        if ('pagination' === $this->attributes['products_navigation']) {
            echo WGL_Framework::pagination($this->query, 'center');
        }

        if ('load_more' === $this->attributes['products_navigation']) {
            global $wgl_products_atts;
            $wgl_products_atts['load_more_text'] = $this->attributes['name_load_more'];
            $wgl_products_atts['load_more_media_type'] = $this->attributes['load_more_media_type'];
            $wgl_products_atts['load_more_media_icon'] = $this->attributes['load_more_media_icon'];

            WGL_Framework::render_load_more_button($wgl_products_atts);
        }
    }
}