<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-testimonials.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Utils,
    Repeater,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography,
    Group_Control_Background
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Carousel_Settings,
    Templates\WGL_Testimonials as Testimonials_Template
};

class WGL_Testimonials extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-testimonials';
    }

    public function get_title()
    {
        return esc_html__('WGL Testimonials', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-testimonials';
    }

    public function get_script_depends()
    {
        return ['swiper'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'posts_per_row',
            [
                'label' => esc_html__('Grid Columns Amount', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1 (one)', 'transmax-core'),
                    '2' => esc_html__('2 (two)', 'transmax-core'),
                    '3' => esc_html__('3 (three)', 'transmax-core'),
                    '4' => esc_html__('4 (four)', 'transmax-core'),
                    '5' => esc_html__('5 (five)', 'transmax-core'),
                ],
                'default' => '1',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Image', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'author_name',
            [
                'label' => esc_html__('Author Name', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'link_author',
            [
                'label' => esc_html__('Link Author', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'author_position',
            [
                'label' => esc_html__('Author Position', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('“Great Work!”​', 'transmax-core'),
            ]
        );

        $repeater->add_control(
            'quote',
            [
                'label' => esc_html__('Quote', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Items', 'transmax-core'),
                'type' => Controls_Manager::REPEATER,
                'default' => [
                    [
                        'author_name' => esc_html__('Dominicana Rodrigez', 'transmax-core'),
                        'author_position' => esc_html__('Restaurant Client', 'transmax-core'),
                        'quote' => esc_html__('“Because a restaurant’s story is never complete, there is always something new and wonderful to discover. An evening spent at Transmax is like boarding a golden ship sailing through a Parisian night.”', 'transmax-core'),
                        'thumbnail' => Utils::get_placeholder_image_src(),
                    ],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ author_name }}}',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'transmax-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'top_block' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_1.png',
                    ],
                    'bottom_block' => [
                        'title' => esc_html__('Bottom', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_4.png',
                    ],
                    'top_inline' => [
                        'title' => esc_html__('Top Inline', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_2.png',
                    ],
                    'bottom_inline' => [
                        'title' => esc_html__('Bottom Inline', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/testimonials_3.png',
                    ],
                ],
                'default' => 'bottom_inline',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'prefix_class' => 'a',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Enable Hover Animation', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Lift up the item on hover.', 'transmax-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        WGL_Carousel_Settings::add_controls($this, [
            '3d_animation_options' => 'enabled',
            'animation_style' => [
                'default' => 'default',
            ],
            'slide_per_single' => [
                'default' => 1,
            ],
            'pagination_margin' => [
                'default' => [
                    'size' => 0
                ],
            ],
        ]);

        /**
         * STYLE -> ITEM CONTAINER
         */

        $this->start_controls_section(
            'style_item_container',
            [
                'label' => esc_html__('Item Container', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'allowed_dimensions' => 'vertical',
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-carousel_wrapper .swiper-container' => 'margin: calc(-1 * {{TOP}}{{UNIT}}) -15px calc(-1 * {{BOTTOM}}{{UNIT}}) -15px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
				    'top' => '0',
				    'right' => '0',
				    'bottom' => '36',
				    'left' => '0',
				    'unit' => 'px',
				    'isLinked' => true
			    ],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .testimonial__item, {{WRAPPER}} .testimonial__item:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_bg',
                'selector' => '{{WRAPPER}} .testimonial__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .testimonial__item:before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .testimonial__item',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes'
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 5,
                            'vertical' => 6,
                            'blur' => 30,
                            'spread' => 0,
                            'color' => 'rgba(0,0,0,0.1)',
                        ]
                    ]
                ]
            ]
        );

	    $this->add_control(
		    'item_border_crop',
		    [
			    'label' => esc_html__('Crop Border for Quote Icon', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'condition' => [
				    'item_border_border!' => '',
			    ],
		    ]
	    );

	    $this->add_control(
		    'item_border_crop_size',
		    [
			    'label' => esc_html__('Crop Width', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'condition' => [
			    	'item_border_border!' => '',
				    'item_border_crop!' => ''
			    ],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 150],
			    ],
			    'render_type' => 'ui',
		    ]
	    );

	    $this->add_control(
		    'item_border_crop_position',
		    [
			    'label' => esc_html__('Crop Position', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'condition' => [
				    'item_border_border!' => '',
				    'item_border_crop!' => ''
			    ],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 500],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .testimonial__item:before' => 'clip-path: polygon( 0% 0%, 0% 101%, {{SIZE}}px 101%, {{SIZE}}px 0%, calc( {{SIZE}}px + {{item_border_crop_size.SIZE}}px) 0%, calc( {{SIZE}}px + {{item_border_crop_size.SIZE}}px) {{item_border_crop_size.SIZE}}px, {{SIZE}}px {{item_border_crop_size.SIZE}}px, {{SIZE}}px 101%, 101% 101%, 101% 0%);',
			    ],
		    ]
	    );

	    $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'style_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .item__title',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
				    'top' => '31',
				    'right' => '20',
				    'bottom' => '10',
				    'left' => '40',
				    'unit' => 'px',
				    'isLinked' => true
			    ],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * STYLE -> QUOTE
         */

        $this->start_controls_section(
            'style_quote',
            [
                'label' => esc_html__('Quote', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_quote',
                'selector' => '{{WRAPPER}} .item__quote',
            ]
        );

        $this->add_control(
            'quote_tag',
            [
                'label' => esc_html__('Quote tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'quote_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
				    'top' => '0',
				    'right' => '20',
				    'bottom' => '0',
				    'left' => '0',
				    'unit' => 'px',
				    'isLinked' => true
			    ],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
				    'top' => '3',
				    'right' => '20',
				    'bottom' => '28',
				    'left' => '40',
				    'unit' => 'px',
				    'isLinked' => true
			    ],
                'tablet_default' => [
		            'top' => '3',
		            'right' => '20',
		            'bottom' => '28',
		            'left' => '20',
		            'unit' => 'px',
		            'isLinked' => true
	            ],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'quote_border',
                'selector' => '{{WRAPPER}} .item__quote',
            ]
        );


        $this->add_responsive_control(
            'quote_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'quote_bg',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .item__quote' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'quote_overlay',
            [
                'label' => esc_html__('Overlay Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .item__quote:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
		    'quote_bg_shift',
		    [
			    'label' => esc_html__('Shift the Overlay', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px' ],
			    'range' => [ 'px' => [ 'min' => -20, 'max' => 120 ] ],
			    'default' => [ 'size' => '0', 'unit' => 'px' ],
			    'selectors' => [
				    '{{WRAPPER}} .item__quote:after' => 'transform: translate({{SIZE}}{{UNIT}},{{SIZE}}{{UNIT}})',
			    ],
		    ]
	    );

        $this->end_controls_section();

        /**
         * STYLE -> QUOTE ICON
         */

        $this->start_controls_section(
            'style_quote_icon',
            [
                'label' => esc_html__('Quote Icon', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'quote_icon_enabled',
            [
                'label' => esc_html__('Use Icon', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'quote_icon-',
                'return_value' => 'view_1',
            ]
        );

	    $this->add_control(
		    'quote_icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'condition' => ['quote_icon_enabled!' => ''],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 150],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon:before' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
		    'icon_padding',
		    [
			    'label' => esc_html__('Padding', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'condition' => ['quote_icon_enabled!' => ''],
			    'size_units' => ['px', 'em', '%'],
			    'default' => [
				    'top' => '20',
				    'right' => '20',
				    'bottom' => '20',
				    'left' => '20',
				    'unit' => 'px',
				    'isLinked' => true
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'icon_margin',
		    [
			    'label' => esc_html__('Margin', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'condition' => ['quote_icon_enabled!' => ''],
			    'size_units' => ['px', 'em', '%'],
			    'default' => [
				    'top' => '-58',
				    'right' => '0',
				    'bottom' => '0',
				    'left' => '18',
				    'unit' => 'px',
				    'isLinked' => true
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'quote_icon_color',
		    [
			    'label' => esc_html__('Icon Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['quote_icon_enabled!' => ''],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .item__icon:before' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'quote_icon_bg',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['quote_icon_enabled!' => ''],
			    'dynamic' => ['active' => true],
                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .item__icon:before' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR THUMBNAIL
         */

        $this->start_controls_section(
            'style_thumnail',
            [
                'label' => esc_html__('Author Thumbnail', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => esc_html__('Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 20, 'max' => 400],
                ],
                'default' => ['size' => 80],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .author__thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .author__thumbnail img',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'testimonials_image_shadow',
                'selector' => '{{WRAPPER}} .author__thumbnail img',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR NAME
         */

        $this->start_controls_section(
            'style_name',
            [
                'label' => esc_html__('Author Name', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_tag',
            [
                'label' => esc_html__('HTML tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'name_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .author__name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('name_colors');

        $this->start_controls_tab(
            'tab_name_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'name_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_name_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'name_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_name',
                'selector' => '{{WRAPPER}} .author__name',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> AUTHOR POSITION
         */

        $this->start_controls_section(
            'style_position',
            [
                'label' => esc_html__('Author Position', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'position_tag',
            [
                'label' => esc_html__('HTML tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h6›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
            ]
        );

        $this->add_responsive_control(
            'position_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'position_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('position_colors');

        $this->start_controls_tab(
            'position_color_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'custom_position_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__position' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_position_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'position_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .author__position:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_position',
                'selector' => '{{WRAPPER}} .author__position',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        (new Testimonials_Template())->render(
            $this,
            $this->get_settings_for_display()
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
