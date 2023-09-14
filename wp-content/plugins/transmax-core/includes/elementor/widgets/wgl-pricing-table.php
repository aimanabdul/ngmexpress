<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-pricing-table.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Templates\WGL_Button
};

class WGL_Pricing_Table extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-pricing-table';
    }

    public function get_title()
    {
        return esc_html__('WGL Pricing Table', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-pricing-table';
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

        $this->add_responsive_control(
            'p_alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
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
                'default' => 'left',
                'prefix_class' => 'a',
            ]
        );

        $this->add_control(
            'p_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Title...', 'transmax-core'),
                'default' => esc_html__('Royal Silver', 'transmax-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
		    'p_bg_text',
		    [
			    'label' => esc_html__('Background Text', 'transmax-core'),
			    'type' => Controls_Manager::TEXT,
			    'label_block' => true,
		    ]
	    );

        $this->add_control(
            'p_currency',
            [
                'label' => esc_html__('Currency', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Currency...', 'transmax-core'),
                'default' => esc_html__('$', 'transmax-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_price',
            [
                'label' => esc_html__('Price', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Price...', 'transmax-core'),
                'default' => esc_html__('600', 'transmax-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_period',
            [
                'label' => esc_html__('Period', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('Period...', 'transmax-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'p_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Your content...', 'transmax-core'),
            ]
        );

        $this->add_control(
            'p_description',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => esc_attr__('Description...', 'transmax-core'),
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Enable hover animation', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Lift up the item on hover.', 'transmax-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> BUTTON
         */

        $this->start_controls_section(
            'content_button',
            ['label' => esc_html__('Button', 'transmax-core')]
        );

        $this->add_control(
            'button_enabled',
            [
                'label' => esc_html__('Use button?','transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['button_enabled' => 'yes'],
                'label_block' => true,
                'default' => esc_html__('MAKE A RESERVATION', 'transmax-core'),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => esc_html__('Button Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'condition' => ['button_enabled' => 'yes'],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> CONTAINER
         */

        $this->start_controls_section(
            'style_background',
            [
                'label' => esc_html__('Container', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__('Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 200, 'max' => 800],
	                '%' => ['min' => 10, 'max' => 100],
                ],
	            'size_units' => [ 'px', '%' ],
	            'default' => ['size' => 370, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pricing_plan' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'module_bg',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color' => ['default' => '#ffffff'],
                ],
                'selector' => '{{WRAPPER}} .pricing__wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
	            'selector' => '{{WRAPPER}} .pricing__wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .pricing__wrapper',
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
                            'color' => 'rgba(0,0,0,0.12)',
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'inner_border',
            [
                'label' => esc_html__('Inner Border', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
		    'inner_border_width',
		    [
			    'label' => esc_html__('Inner Border Width', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em'],
			    'range' => [
				    'px' => ['min' => 1, 'max' => 300],
				    'em' => ['min' => 1, 'max' => 20],
			    ],
			    'default' => [
			    	'unit' => 'px',
			    	'size' => 1,
			    ],
                'condition' => ['inner_border' => 'yes'],
			    'selectors' => [
				    '{{WRAPPER}} .pricing__wrapper .tb-border:before,{{WRAPPER}} .pricing__wrapper .tb-border:after' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pricing__wrapper .lr-border:before,{{WRAPPER}} .pricing__wrapper .lr-border:after' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'inner_border_offset',
            [
                'label' => esc_html__('Inner Border Offset', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['inner_border' => 'yes'],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 300],
                ],
                'default' => ['size' => '10'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__wrapper .tb-border:before' => 'top: {{SIZE}}{{UNIT}};left: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pricing__wrapper .tb-border:after' => 'left: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pricing__wrapper .lr-border:before' => 'left: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pricing__wrapper .lr-border:after' => 'top: {{SIZE}}{{UNIT}};right: {{SIZE}}{{UNIT}};bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'inner_border_color',
            [
                'label' => esc_html__('Inner Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['inner_border' => 'yes'],
                'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .pricing__wrapper .tb-border:before,{{WRAPPER}} .pricing__wrapper .tb-border:after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .pricing__wrapper .lr-border:before,{{WRAPPER}} .pricing__wrapper .lr-border:after' => 'background-color: {{VALUE}};',
			    ],
            ]
        );

        $this->add_control(
            'sections_styling_enabled',
            [
                'label' => esc_html__('Separate sections cusomization', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'price_section',
            [
                'label' => esc_html__('Price Section', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['sections_styling_enabled' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'price_section_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '24',
                    'right' => '50',
                    'bottom' => '20',
                    'left' => '50',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_section_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'price_section_bg',
                'condition' => ['sections_styling_enabled' => 'yes'],
                'selector' => '{{WRAPPER}} .pricing__header',
            ]
        );

        $this->add_control(
            'сontent_section',
            [
                'label' => esc_html__('Content Section', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['sections_styling_enabled' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'сontent_section_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '34',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '50',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'сontent_section_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '34',
                    'right' => '0',
                    'bottom' => '39',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'сontent_section_bg',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_section',
            [
                'label' => esc_html__('Button Section', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['sections_styling_enabled' => 'yes'],
            ]
        );

        $this->add_responsive_control(
            'button_section_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '50',
                    'bottom' => '39',
                    'left' => '50',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_section_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_section_bg',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['sections_styling_enabled' => 'yes'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__footer' => 'background-color: {{VALUE}};',
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
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .pricing__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
	            'default' => [
		            'top' => '47',
		            'right' => '0',
		            'bottom'=> '0',
		            'left'  => '0',
		            'unit' => 'px',
		            'isLinked' => true
	            ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'title',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'title_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__wrapper:hover .pricing__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__wrapper:hover .pricing__title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .pricing__title',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .pricing__title',
            ]
        );

        $this->end_controls_section();

        /**
	     * STYLE -> BG Text
	     */

	    $this->start_controls_section(
		    'background_text',
		    [
			    'label' => esc_html__('Background Text', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['p_bg_text!' => ''],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'custom_fonts_bg_text',
			    'selector' => '{{WRAPPER}} .bg_text__inner',
		    ]
	    );

	    $this->add_responsive_control(
		    'size_bg_text',
		    [
			    'label' => esc_html__('Wrapper Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em'],
			    'range' => [
				    'px' => ['min' => 6, 'max' => 300],
				    'em' => ['min' => 1, 'max' => 20],
			    ],
			    'default' => [
			    	'unit' => 'em',
			    	'size' => 3,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .bg_text__inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_margin',
		    [
			    'label' => esc_html__('Margin', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '18',
                    'right' => '0',
                    'bottom'=> '0',
                    'left'  => '-32',
	                'unit' => 'px',
	                'isLinked' => true
                ],
			    'selectors' => [
				    '{{WRAPPER}} .bg_text__inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_padding',
		    [
			    'label' => esc_html__('Padding', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .bg_text__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'bg_text_radius',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .bg_text__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_z_index',
		    [
			    'label' => __( 'Z-Index', 'elementor' ),
			    'type' => Controls_Manager::NUMBER,
			    'min' => -5,
			    'selectors' => [
				    '{{WRAPPER}} .pricing__bg_text' => 'z-index: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('tabs_bg_text_styles');

	    $this->start_controls_tab(
		    'tab_bg_text',
		    ['label' => esc_html__('Idle', 'transmax-core')]
	    );

	    $this->add_control(
		    'bg_text_color_idle',
		    [
			    'label' => esc_html__('Text Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .bg_text__inner' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'bg_text_bg_color_idle',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .bg_text__inner' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_bg_text_hover',
		    ['label' => esc_html__('Hover', 'transmax-core')]
	    );

	    $this->add_control(
		    'bg_text_color_hover',
		    [
			    'label' => esc_html__('Text Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .bg_text__inner' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'bg_text_bg_color_hover',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .bg_text__inner' => 'background-color: {{VALUE}};',
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
            'style_price',
            [
                'label' => esc_html__('Price', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pricing_price',
                'selector' => '{{WRAPPER}} .pricing__price',
            ]
        );

        $this->add_control(
            'custom_price_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'price_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '17',
                    'right' => '0',
                    'bottom'=> '2',
                    'left'  => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .pricing__price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> PERIOD
         */

        $this->start_controls_section(
            'style_period',
            [
                'label' => esc_html__('Period', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_period',
                'selector' => '{{WRAPPER}} .price__period',
            ]
        );

        $this->add_responsive_control(
            'period_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '9',
                    'right' => '0',
                    'bottom'=> '0',
                    'left'  => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .price__period' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'period_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__price .price__period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         *  STYLE -> CONTENT
         */

        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pricing_content_typography',
                'selector' => '{{WRAPPER}} .pricing__content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing__content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['sections_styling_enabled' => ''],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pricing__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .pricing__content',
                'fields_options' => [
		            'border' => ['default' => 'solid'],
		            'width'  => ['default' => [
			            'top'      => '1',
			            'right'    => '0',
			            'bottom'   => '0',
			            'left'     => '0',
		            ]],
		            'color' => ['default' => '#e6e6e6'],
	            ],
            ]
        );

	    $this->add_control(
		    'content_figure',
		    [
			    'label' => esc_html__('Use Divider', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'content_figure_color_1',
		    [
			    'label' => esc_html__('Figure Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['content_figure' => 'yes'],
			    'selectors' => [
				    '{{WRAPPER}} .pricing__content .pricing__divider' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_section();

        /**
         * STYLE -> DESCRIPTION
         */

        $this->start_controls_section(
            'style_description',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['p_description!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pricing_desc_typo',
                'selector' => '{{WRAPPER}} .pricing_desc',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .pricing_desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pricing_desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> BUTTON
         */

        $this->start_controls_section(
            'style_button',
            [
                'label' => esc_html__('Button', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['button_enabled!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .wgl-button',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '21',
                    'right' => '41',
                    'bottom' => '20',
                    'left' => '41',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('button');

        $this->start_controls_tab(
            'button_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover,
                     {{WRAPPER}} .wgl-button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover,
                     {{WRAPPER}} .wgl-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-button:hover',
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['button_border_border!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover,
                     {{WRAPPER}} .wgl-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'separator' => 'before',
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'width'  => ['default' => [
                        'top'      => '1',
                        'right'    => '1',
                        'bottom'   => '1',
                        'left'     => '1',
                    ]],
                ],
                'selector' => '{{WRAPPER}} .wgl-button',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        // Wrapper classes
        $wrap_classes = $_s['hover_animation'] ? ' hover-animation' : '';

        $title = '';
        if (!empty($_s['p_title'])) {
            $title .= '<h4 class="pricing__title">' . esc_html($_s['p_title']) . '</h4>';
        }

        $bg_text = '';
        if (!empty($_s['p_bg_text'])) {
            $bg_text .= '<div class="pricing__bg_text"><span class="bg_text__inner">' . wp_kses($_s['p_bg_text'], self::get_kses_allowed_html()) . '</span></div>';
        }

        $inner_border = '';
        if (!empty($_s['inner_border'])) {
            $inner_border .= '<div class="tb-border"></div>';
            $inner_border .= '<div class="lr-border"></div>';
        }

        $pricing__divider = '';
        if (!empty($_s['content_figure'])) {
            $pricing__divider .= '<div class="pricing__divider"></div>';
        }

        $currency = '';
        if (!empty($_s['p_currency'])) {
            $currency = '<span class="price__currency">' . esc_html($_s['p_currency']) . '</span>';
        }

        $price = '';

        // Price
        if (!empty($_s['p_price'])) {
            preg_match("/(\d+)(\.| |,)(\d+)$/", $_s['p_price'], $matches, PREG_OFFSET_CAPTURE);
            switch (isset($matches[0])) {
                case false:
                    $price = '<span class="price__value">' . esc_html($_s['p_price']) . '</span>';
                    break;
                case true:
                    $price = '<span class="price__value">';
                    $price .= esc_html($matches[1][0]);
                    $price .= '<span class="price__decimal">' . esc_html($matches[3][0]) . '</span>';
                    $price .= '</span>';
                    break;
            }
        }

        $period = '';
        if (!empty($_s['p_period'])) {
            $period = '<div class="price__period">' . esc_html($_s['p_period']) . '</div>';
        }

        $description = '';
        if (!empty($_s['p_description'])) {
            $description = '<div class="pricing_desc">'
                . wp_kses($_s['p_description'], self::get_kses_allowed_html())
                . '</div>';
        }

        // Button
        if ($_s['button_enabled']) {
            $button_options = [
                'icon_type' => '',
                'text' => $_s['button_text'],
                'link' => $_s['button_link'],
                'size' => 'xl',
            ];
            ob_start();
                (new WGL_Button())->render($this, $button_options);
            $button = ob_get_clean();
        }

        // Render
        echo '<div class="wgl-pricing_plan', $wrap_classes, '">',
            '<div class="pricing__wrapper">',
                $inner_border,
                '<div class="pricing__header">',
                    $title,
                    $bg_text,
                    '<div class="pricing__price">',
                        $currency,
                        $price,
                        $period,
                    '</div>',
                '</div>',
                '<div class="pricing__content">',
                    $pricing__divider,
                    $_s['p_content'],
                '</div>',
                '<div class="pricing__footer">',
                    $description,
                    $button,
                '</div>',
            '</div>',
        '</div>';
    }

    protected static function get_kses_allowed_html()
    {
        return [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'ul' => ['id' => true, 'class' => true, 'style' => true],
            'ol' => ['id' => true, 'class' => true, 'style' => true],
        ];
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
