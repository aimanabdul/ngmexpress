<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-products-grid.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
	Group_Control_Border,
	Widget_Base,
	Controls_Manager,
	Group_Control_Typography,
	Group_Control_Box_Shadow
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\WGLProductsGrid
};

class WGL_Products_Grid extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-products-grid';
    }

    public function get_keywords() {
        return ['products', 'shop', 'woocommerce'];
    }

    public function get_title()
    {
        return esc_html__('WGL Products Grid', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-products-grid';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'products_layout',
            [
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => esc_html__('Grid Columns Amount', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    '1' => esc_html__('1 (one)', 'transmax-core'),
                    '2' => esc_html__('2 (two)', 'transmax-core'),
                    '3' => esc_html__('3 (three)', 'transmax-core'),
                    '4' => esc_html__('4 (four)', 'transmax-core'),
                    '5' => esc_html__('5 (five)', 'transmax-core'),
                    '6' => esc_html__('6 (six)', 'transmax-core'),
                ],
                'desktop_default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'transmax-core'),
                'options' => [
                    '150' => 'Thumbnail - 150x150',
                    '300' => 'Medium - 300x300',
                    '768' => 'Medium Large - 768x768',
                    '1024' => 'Large - 1024x1024',
                    '540x520' => '540x520',
                    'full' => 'Full',
                    'custom' => 'Custom',
	                '' => 'Default Woo Size',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'transmax-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'description' => esc_html__('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'transmax-core'),
                'condition' => [
                    'img_size_string' => 'custom',
                ],
                'default' => [
                    'width' => '540',
                    'height' => '520',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Aspect Ratio', 'transmax-core'),
                'options' => [
                    '1:1' => esc_html__('1:1', 'transmax-core'),
                    '3:2' => esc_html__('3:2', 'transmax-core'),
                    '4:3' => esc_html__('4:3', 'transmax-core'),
                    '6:5' => esc_html__('6:5', 'transmax-core'),
                    '9:16' => esc_html__('9:16', 'transmax-core'),
                    '16:9' => esc_html__('16:9', 'transmax-core'),
                    '21:9' => esc_html__('21:9', 'transmax-core'),
                    '' => esc_html__('No Crop', 'transmax-core'),
                ],
	            'condition' => [
		            'img_size_string!' => '',
	            ],
                'default' => '',
            ]
        );

        $this->add_control(
            'show_header_products',
            array(
                'label' => esc_html__('Show Header Shop', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_res_count',
            array(
                'label' => esc_html__('Show Result Count', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
	            'condition' => [ 'show_header_products' => 'yes' ],
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_sorting',
            array(
                'label' => esc_html__('Show Default Sorting', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
	            'condition' => [ 'show_header_products' => 'yes' ],
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'isotope_filter',
            [
                'label' => esc_html__('Use Filter?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['products_layout!' => 'carousel'],
            ]
        );

	    $this->add_control(
		    'filter_counter_enabled',
		    [
			    'label' => esc_html__('Show Number of Categories', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['isotope_filter' => 'yes'],
		    ]
	    );

	    $this->add_control(
		    'filter_max_width_enabled',
		    [
			    'label' => esc_html__('Limit the Filter Container Width', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => ['isotope_filter' => 'yes'],
		    ]
	    );

	    $this->add_control(
		    'max_width_filter',
		    [
			    'label' => esc_html__('Filter Container Max Width (px)', 'transmax-core'),
			    'type' => Controls_Manager::NUMBER,
			    'condition' => [
				    'isotope_filter' => 'yes',
				    'filter_max_width_enabled' => 'yes',
			    ],
			    'default' => '1170',
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter' => 'max-width: {{VALUE}}px;',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'filter_alignment',
		    [
			    'label' => esc_html__('Filter Align', 'transmax-core'),
			    'type' => Controls_Manager::SELECT,
			    'condition' => ['isotope_filter' => 'yes'],
			    'options' => [
				    'left' => esc_html__('Left', 'transmax-core'),
				    'center' => esc_html__('Сenter', 'transmax-core'),
				    'right' => esc_html__('Right', 'transmax-core'),
			    ],
			    'default' => 'center',
		    ]
	    );

        $this->add_control(
            'products_navigation',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Navigation', 'transmax-core'),
	            'condition' => ['products_layout!' => 'carousel'],
                'options' => [
                    '' => 'None',
                    'pagination' => 'Pagination',
                    'load_more' => 'Load More',
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'remainings_loading_btn_items_amount',
            [
                'label' => esc_html__('Items to be loaded', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'products_navigation' => 'load_more',
                    'products_layout!' => 'carousel'
                ],
                'default' => esc_html__('4', 'transmax-core'),
            ]
        );

        $this->add_control(
            'name_load_more',
            array(
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
	            'default' => esc_html__('More Products', 'transmax-core'),
                'condition' => [
                    'products_navigation' => 'load_more',
                    'products_layout!' => 'carousel'
                ],
            )
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        $this->start_controls_section(
            'section_content_carousel',
            [
                'label' => esc_html__('Carousel Options', 'transmax-core'),
                'condition' => ['products_layout' => 'carousel']
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this);

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination!' => ''],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'use_pagination' => [
                'default' => 'yes',
            ],
	        'pagination_type' => [
		        'default' => 'line',
	        ],
            'pagination_margin' => [
                'range' => [
                    'px' => ['min' => -50, 'max' => 150],
                ],
	            'default' => [
		            'size' => 79
	            ],
            ],
        ]);

        $this->add_control(
            'pagination_navigation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_pagination',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_navigation_controls($this);

        $this->add_control(
            'navigation_responsive_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'customize_responsive',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_responsive_controls($this);

        $this->end_controls_section();

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls($this, [
            'post_type' => 'product',
            'hide_tags' => true,
            'hide_cats' => true,
        ]);

	    /**
	     * STYLE -> FILTER
	     */

	    $this->start_controls_section(
		    'style_filter',
		    [
			    'label' => esc_html__('Filter', 'transmax-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['isotope_filter' => 'yes'],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'filter',
			    'selector' => '{{WRAPPER}} .isotope-filter a',
		    ]
	    );

	    $this->add_responsive_control(
		    'filter_cats_gap',
		    [
			    'label' => esc_html__('Categories Gap', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0, 'max' => 100, 'step' => 1],
				    '%' => ['min' => 0, 'max' => 10, 'step' => 0.5],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .swiper-wrapper' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				    '{{WRAPPER}} .wgl-products' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'filter_cats_margin',
		    [
			    'label' => esc_html__('Margin', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'filter_cats_padding',
		    [
			    'label' => esc_html__('Padding', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_cats_radius',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('filter');

	    $this->start_controls_tab(
		    'filter_idle',
		    ['label' => esc_html__('Idle', 'transmax-core')]
	    );

	    $this->add_control(
		    'filter_color_idle',
		    [
			    'label' => esc_html__('Text Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a:not(.active)' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_bg_idle',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a:not(.active):after' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'filter_hover',
		    ['label' => esc_html__('Hover', 'transmax-core')]
	    );

	    $this->add_control(
		    'filter_color_hover',
		    [
			    'label' => esc_html__('Text Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_bg_hover',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a:hover:after' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'filter_active',
		    ['label' => esc_html__('Active', 'transmax-core')]
	    );

	    $this->add_control(
		    'filter_color_active',
		    [
			    'label' => esc_html__('Text Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a.active' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'filter_bg_active',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a.active:after' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();

	    $this->add_control(
		    'filter_shadow_divider',
		    ['type' => Controls_Manager::DIVIDER]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'filter_shadow',
			    'selector' => '{{WRAPPER}} .isotope-filter a',
		    ]
	    );

	    $this->end_controls_section();

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'products_gap',
            [
                'label' => esc_html__('Products Gap', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 60, 'step' => 2],
                ],
	            'render_type' => 'template',
                'desktop_default' => ['size' => 30, 'unit' => 'px'],
                'tablet_default' => ['size' => 30, 'unit' => 'px'],
                'mobile_default' => ['size' => 20, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products' => '--products-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Product Inner Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Product Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product, {{WRAPPER}} .woo_product_inner_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_item');

        $this->start_controls_tab(
            'tab_item_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'item_bg_color_idle',
            [
                'label' => esc_html__('Item Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_inner_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'item_bg_color_hover',
            [
                'label' => esc_html__('Item Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product:hover .woo_product_inner_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

	    $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Content Padding', 'transmax-core'),
                'separator' => 'before',
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'content_radius',
            [
                'label' => esc_html__('Content Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woo_product_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'image_width',
		    [
			    'label' => esc_html__('Image Max Width', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => ['min' => 50, 'max' => 500 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .product .picture .woo_post-link' => 'max-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'image_padding',
		    [
			    'label' => esc_html__( 'Margin', 'transmax-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .product .picture' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
            'image_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product .picture img,
				     {{WRAPPER}} .product .picture .woo_post-link,
				     {{WRAPPER}} .product .picture:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_image',
            [
                'label' => esc_html__('Show Secondary Image on Hover', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'selectors' => [
                    '{{WRAPPER}} .product .picture img.attachment-shop_catalog' => 'display: block;',
                ],
            ]
        );

        $this->add_control(
            'image_bg_color',
            [
                'label' => esc_html__('image Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product .picture' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity_hover',
            [
                'label' => esc_html__('Image Transparency on Hover', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => .02],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product:hover .picture .woo_post-link' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_image');

        $this->start_controls_tab(
            'tab_image_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .product .picture:before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_image_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .product .picture:after',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_title');

        $this->start_controls_tab(
            'tab_title_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'htitle_color_hover',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE -> PRICE
         */

        $this->start_controls_section(
            'section_style_price',
            [
                'label' => esc_html__('Price', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price',
                'selector' => '{{WRAPPER}} .wgl-products .woocommerce-Price-amount, {{WRAPPER}} .wgl-products .price',
            ]
        );

        $this->add_responsive_control(
            'price_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_price');

        $this->start_controls_tab(
            'tab_price_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'price_color_idle',
            [
                'label' => esc_html__('Price Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'old_price_color_idle',
            [
                'label' => esc_html__('Old Price Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .price del' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_price_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'price_color_hover',
            [
                'label' => esc_html__('Price Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .product:hover .price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'old_price_color_hover',
            [
                'label' => esc_html__('Old Price Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-products .product:hover .price del' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * STYLE -> BUTTON
         */

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button',
                'selector' => '{{WRAPPER}} .product a.button',
            ]
        );

        $this->add_control(
            'button_width',
            [
                'label' => esc_html__('Min Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 400],
                    '%' => ['min' => 10, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_button');

        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Button Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_idle',
            [
                'label' => esc_html__('Button Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button,
				     {{WRAPPER}} .product a.wc-forward' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Button Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button:hover,
				     {{WRAPPER}} .product a.wc-forward:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => esc_html__('Button Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .product a.button:hover,
				     {{WRAPPER}} .product a.wc-forward:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> LOAD MORE BUTTON
         */

        $this->start_controls_section(
            'style_load_more',
            [
                'label' => esc_html__('Load More Button', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['products_navigation' => 'load_more'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'button_inner_margin',
		    [
			    'label' => esc_html__('Margin Inner', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'separator' => 'before',
			    'size_units' => ['px'],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 20 ],
			    ],
			    'default' => ['size' => 5, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-button.with-border' => '--transmax-button-margin: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .wgl-button' => 'margin: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_control(
            'load_more_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'load_more_btn',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'load_more_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'load_more_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_animated_element_color_idle',
            [
                'label' => esc_html__('Animated Element Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'load_more_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_animated_element_color_hover',
            [
                'label' => esc_html__('Animated Element Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .load_more_item:before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_media_heading',
            [
                'label' => esc_html__('Media', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'load_more_media_type',
            [
                'label' => esc_html__('Media Type', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'transmax-core'),
                        'icon' => 'fa fa-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'transmax-core'),
                        'icon' => 'far fa-smile',
                    ],
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'load_more_media_icon',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'load_more_icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '10',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'load_more_icon',
            ['condition' => ['load_more_media_type' => 'icon']]
        );

        $this->start_controls_tab(
            'load_more_icon_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'load_more_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_icon_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'load_more_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover .load_more__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> LABEL SALE
         */

        $this->start_controls_section(
            'section_style_label_sale',
            [
                'label' => esc_html__('Label Sale', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_sale',
                'selector' => '{{WRAPPER}} span.onsale',
            ]
        );

        $this->add_control(
            'label_sale_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'label_sale_bg_color',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_sale_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_sale_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} span.onsale' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        (new WGLProductsGrid())->render(
            $this->get_settings_for_display(),
            $this
        );
    }

    public function wpml_support_module() {
        add_filter( 'wpml_elementor_widgets_to_translate',  [$this, 'wpml_widgets_to_translate_filter']);
    }

    public function wpml_widgets_to_translate_filter( $widgets ){
        return \WGL_Extensions\Includes\WGL_WPML_Settings::get_translate(
            $this, $widgets
        );
    }
}
