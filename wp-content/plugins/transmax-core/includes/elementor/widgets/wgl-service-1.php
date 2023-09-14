<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-service-1.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
	Group_Control_Border,
	Widget_Base,
	Controls_Manager,
	Icons_Manager,
	Group_Control_Typography,
	Group_Control_Box_Shadow,
	Group_Control_Background,
	Group_Control_Css_Filter};

use WGL_Extensions\{
    Includes\WGL_Icons
};

class WGL_Service_1 extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-service-1';
    }

    public function get_title()
    {
        return esc_html__('WGL Service', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-services';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
		/**
         * CONTENT -> SERVICE CONTENT
         */

        $this->start_controls_section(
            'wgl_service_content',
            ['label' => esc_html__('Service Content', 'transmax-core')]
        );

        $this->add_control(
            's_title_heading',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            's_title_1',
            [
                'label' => esc_html__('Title 1st Part', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'default' => esc_html__('The Heading​', 'transmax-core'),
            ]
        );

        $this->add_control(
            's_title_2',
            [
                'label' => esc_html__('2nd Part(Count etc.)', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
				'default' => esc_html__('01​', 'transmax-core'),
            ]
        );

		$this->add_control(
            's_subtitle',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
				'separator' => 'before',
                'label_block' => true,
            ]
        );

        $this->add_control(
            's_description',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
				'separator' => 'before',
				'default' => esc_html__('With our worldwide inclusion, strong transportation organization and industry driving coordinations experience...', 'transmax-core'),
            ]
        );

        $this->add_control(
            'alignment',
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
                'toggle' => true,
            ]
        );

        $this->add_control(
            'hover_toggling',
            [
                'label' => esc_html__('Toggle Content Visibility', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'toggling',
                'prefix_class' => 'animation_',
                'default' => 'toggling',
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_offset',
            [
                'label' => esc_html__('Animation Distance in %', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['hover_toggling!' => ''],
                'range' => [
                    '%' => ['min' => 30, 'max' => 100],
                ],
                'default' => ['size' => 68, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling .wgl-service_content' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_transition',
            [
                'label' => esc_html__('Transition Duration', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['hover_toggling!' => ''],
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 2, 'step' => 0.1],
                ],
                'default' => ['size' => 0.6 ],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling .wgl-service_content,
                    {{WRAPPER}}.animation_toggling .wgl-service_count' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_section();

		/**
		 * CONTENT -> ICON/IMAGE
		 */

        $output = [];

        $output['view'] = [
            'label' => esc_html__('View', 'transmax-core'),
            'type' => Controls_Manager::SELECT,
            'condition' => ['icon_type' => 'font'],
            'options' => [
                'default' => esc_html__('Default', 'transmax-core'),
                'stacked' => esc_html__('Stacked', 'transmax-core'),
                'framed'  => esc_html__('Framed', 'transmax-core'),
            ],
            'prefix_class' => 'elementor-view-',
        ];

        $output['shape'] = [
            'label' => esc_html__('Shape', 'transmax-core'),
            'type' => Controls_Manager::SELECT,
            'condition' => [
                'icon_type' => 'font',
                'view!' => 'default',
            ],
            'options' => [
                'circle' => esc_html__('Circle', 'transmax-core'),
                'square' => esc_html__('Square', 'transmax-core'),
            ],
            'default' => 'circle',
            'prefix_class' => 'elementor-shape-',
        ];

        WGL_Icons::init(
            $this,
            [
                'output' => $output,
                'section' => true,
            ]
        );

        /**
         * CONTENT -> BUTTON
         */

        $this->start_controls_section(
            'section_style_link',
            ['label' => esc_html__('Link', 'transmax-core') ]
        );

	    $this->add_control(
		    'item_link',
		    [
			    'label' => esc_html__('Link', 'transmax-core'),
			    'type' => Controls_Manager::URL,
			    'dynamic' => ['active' => true],
		    ]
	    );

        $this->add_control(
            'add_item_link',
            [
                'label' => esc_html__('Whole Item Link', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),

            ]
        );

        $this->add_control(
            'add_read_more',
            [
                'label' => esc_html__('Button', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'default' => 'yes',
            ]
        );

	    $this->add_control(
		    'read_more_alignment',
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
			    'condition' => ['add_read_more' => 'yes'],
			    'toggle' => true,
			    'prefix_class' => 'read_more_alignment-',
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_button-wrapper' => 'text-align: {{VALUE}};',
			    ],
		    ]
	    );

		$this->add_control(
            'read_more_type',
            [
                'label' => esc_html__('Type', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'transmax-core'),
                    'btn' => esc_html__('Button', 'transmax-core'),
                ],
                'condition' => ['add_read_more' => 'yes'],
                'default' => 'icon',
            ]
        );

		$this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('READ MORE​', 'transmax-core'),
				'condition' => [
					'add_read_more' => 'yes',
					'read_more_type' => 'btn'
				],
                'label_block' => true,
            ]
        );

	    $this->add_control(
            'read_more_icon_fontawesome',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
					'add_read_more' => 'yes',
					'read_more_type' => 'icon'
				],
                'label_block' => true,
                'description' => esc_html__('Select icon from available libraries.', 'transmax-core'),
				'default' => [
                    'library' => 'flaticon',
                    'value' => 'flaticon-right-arrow',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'read_more_icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'condition' => [
				    'add_read_more' => 'yes',
					'read_more_type' => 'icon',
				    'read_more_icon_fontawesome!' => [
				    	'value' => '',
				    	'library' => '',
				    ]
			    ],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 100 ],
			    ],
			    'default' => ['size' => 24 ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_button i' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'read_more_icon_spacing',
		    [
			    'label' => esc_html__('Icon Wrapper Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'condition' => [
				    'add_read_more' => 'yes',
					'read_more_type' => 'icon',
				    'read_more_icon_fontawesome!' => [
				    	'value' => '',
				    	'library' => '',
				    ]
			    ],
			    'range' => [
				    'px' => ['min' => 10, 'max' => 100 ],
			    ],
			    'default' => ['size' => 55 ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_button i,{{WRAPPER}} .wgl-service_button span' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();

		/**
         * CONTENT -> HOVER ANIMATION
         */

        $this->start_controls_section(
            'content_animation',
            ['label' => esc_html__('Hover Animation', 'transmax-core')]
        );

	    $this->add_control(
		    'background_animation',
		    [
			    'label' => esc_html__('Triangle Animation', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:after' => 'display: block;',
			    ],
		    ]
	    );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general_style_section',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'general_padding',
		    [
			    'label' => esc_html__('Padding', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'default' => [
				    'top' => '160',
				    'right' => '0',
				    'bottom' => '114',
				    'left' => '0',
				    'unit' => 'px',
				    'isLinked' => false,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('tabs_background');

	    $this->start_controls_tab(
		    'tab_bg_idle',
		    ['label' => esc_html__('Idle', 'transmax-core')]
	    );

	    $this->add_control(
		    'general_border_radius_idle',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'item_idle',
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .elementor-widget-container',
		    ]
	    );

		$this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'item_css_filters',
			    'selector' => '{{WRAPPER}} .elementor-widget-container',
		    ]
	    );

		$this->add_control(
		    'backdrop_filter',
		    [
			    'label' => esc_html__('Backdrop Filter', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['max' => 30, 'step' => 0.1],
			    ],
			    'selectors' => [
				    '{{WRAPPER}}:before' => 'content: \'\';backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_bg_hover',
		    ['label' => esc_html__('Hover', 'transmax-core')]
	    );

	    $this->add_control(
		    'general_border_radius_hover',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', '%' ],
			    'selectors' => [
				    '{{WRAPPER}}:hover .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name' => 'item_hover',
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .elementor-widget-container:before',
		    ]
	    );

		$this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'item_css_filters_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:before',
		    ]
	    );

		$this->add_control(
		    'backdrop_filter_hover',
		    [
			    'label' => esc_html__('Backdrop Filter', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['max' => 30, 'step' => 0.1],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:before' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
			    ],
		    ]
	    );

	    $this->add_control(
		    'item_bg_transition',
		    [
			    'label' => esc_html__('Transition Delay', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'separator' => 'before',
			    'range' => [
				    'px' => ['max' => 3, 'step' => 0.1],
			    ],
			    'default' => ['size' => 0.4],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container' => 'transition: {{SIZE}}s',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  STYLE -> ICON
		/*-----------------------------------------------------------------------------------*/

	    $this->start_controls_section(
		    'section_style_icon',
		    [
			    'label' => esc_html__('Icon', 'transmax-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['icon_type' => 'font'],
		    ]
	    );

	    $this->add_responsive_control(
		    'icon_size',
		    [
			    'label' => esc_html__('Font Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em', 'rem'],
			    'range' => [
				    'px' => ['min' => 6, 'max' => 300],
			    ],
			    'default' => ['size' => 60],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_rotate',
		    [
			    'label' => esc_html__('Rotate', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['deg', 'turn'],
			    'range' => [
				    'deg' => ['max' => 360],
				    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
			    ],
			    'default' => ['unit' => 'deg'],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .elementor-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'icon_margin',
		    [
			    'label' => esc_html__('Margin', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'separator' => 'before',
			    'size_units' => ['px', 'em', '%'],
			    'default' => [
				    'top' => '0',
				    'right' => '38',
				    'bottom' => '10',
				    'left' => '38',
				    'unit' => 'px',
				    'isLinked' => false,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .elementor-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_padding',
		    [
			    'label' => esc_html__('Padding', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .elementor-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'border_width',
		    [
			    'label' => esc_html__('Border Width', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'condition' => ['view' => 'framed'],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'condition' => ['view!' => 'default'],
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs(
		    'tabs_icons',
		    ['separator' => 'before']
	    );

	    $this->start_controls_tab(
		    'tab_icon_idle',
		    ['label' => esc_html__('Idle', 'transmax-core')]
	    );

	    $this->add_control(
		    'icon_primary_color_idle',
		    [
			    'label' => esc_html__('Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-framed .elementor-icon,
                     {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-framed .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-default .elementor-icon svg' => 'fill: {{VALUE}}; border-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_secondary_color_idle',
		    [
			    'label' => esc_html__('Additional Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['view!' => 'default'],
			    'dynamic' => ['active' => true],
			    'default' => 'rgba(255,255,255,.3)',
			    'selectors' => [
				    '{{WRAPPER}}.elementor-view-framed .elementor-icon,
				    {{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'icon_idle',
			    'selector' => '{{WRAPPER}} .elementor-icon',
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_icon_hover',
		    ['label' => esc_html__('Hover', 'transmax-core')]
	    );

	    $this->add_control(
		    'icon_primary_color_hover',
		    [
			    'label' => esc_html__('Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'background-color: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon,
                     {{WRAPPER}}.elementor-view-default:hover .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-default:hover .elementor-icon svg' => 'fill: {{VALUE}}; border-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'icon_secondary_color_hover',
		    [
			    'label' => esc_html__('Additional Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['view!' => 'default'],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon' => 'background-color: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'color: {{VALUE}};',
				    '{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon svg' => 'fill: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'icon_hover',
			    'selector' => '{{WRAPPER}}:hover .elementor-icon',
		    ]
	    );

	    $this->add_control(
		    'hover_animation_icon',
		    [
			    'label' => esc_html__('Hover Animation', 'transmax-core'),
			    'type' => Controls_Manager::HOVER_ANIMATION,
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->end_controls_section();

	    /*-----------------------------------------------------------------------------------*/
	    /*  STYLE -> IMAGE
		/*-----------------------------------------------------------------------------------*/

	    $this->start_controls_section(
		    'section_style_image',
		    [
			    'label' => esc_html__('Image', 'transmax-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['icon_type' => 'image'],
		    ]
	    );

	    $this->add_responsive_control(
		    'image_space',
		    [
			    'label' => esc_html__('Margin', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'default' => [
				    'top' => '0',
				    'right' => '38',
				    'bottom' => '22',
				    'left' => '38',
				    'unit' => 'px',
				    'isLinked' => false,
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'image_size',
		    [
			    'label' => esc_html__('Width', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range' => [
				    'px' => ['min' => 50, 'max' => 800],
				    '%' => ['min' => 5, 'max' => 100],
			    ],
			    'default' => ['size' => 95, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'hover_animation_image',
		    [
			    'label' => esc_html__('Hover Animation', 'transmax-core'),
			    'type' => Controls_Manager::HOVER_ANIMATION,
		    ]
	    );

	    $this->start_controls_tabs('image_effects');

	    $this->start_controls_tab(
		    'Idle',
		    ['label' => esc_html__('Idle', 'transmax-core')]
	    );

	    $this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'css_filters',
			    'selector' => '{{WRAPPER}} .wgl-image-box_img img',
		    ]
	    );

	    $this->add_control(
		    'image_opacity',
		    [
			    'label' => esc_html__('Opacity', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img img' => 'opacity: {{SIZE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'background_hover_transition',
		    [
			    'label' => esc_html__('Transition Duration', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'default' => ['size' => 0.3],
			    'range' => [
				    'px' => ['max' => 3, 'step' => 0.1],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img img' => 'transition-duration: {{SIZE}}s',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'hover',
		    ['label' => esc_html__('Hover', 'transmax-core')]
	    );

	    $this->add_group_control(
		    Group_Control_Css_Filter::get_type(),
		    [
			    'name' => 'css_filters_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img',
		    ]
	    );

	    $this->add_control(
		    'image_opacity_hover',
		    [
			    'label' => esc_html__('Opacity', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0.10, 'max' => 1, 'step' => 0.01],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-image-box_img img' => 'opacity: {{SIZE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->end_controls_section();

        /**
         * STYLE -> TITLE
         */

        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('‹h1›', 'transmax-core'),
                    'h2' => esc_html__('‹h2›', 'transmax-core'),
                    'h3' => esc_html__('‹h3›', 'transmax-core'),
                    'h4' => esc_html__('‹h4›', 'transmax-core'),
                    'h5' => esc_html__('‹h5›', 'transmax-core'),
                    'h6' => esc_html__('‹h6›', 'transmax-core'),
                    'div' => esc_html__('‹div›', 'transmax-core'),
                    'span' => esc_html__('‹span›', 'transmax-core'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '0',
                    'right' => '38',
                    'bottom' => '11',
                    'left' => '38',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Title Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
            'title_separator',
            [
                'label' => esc_html__('Show Separator?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_content-wrap:after' => 'display: block;',
                ],
                'separator' => 'before',
                'default' => 'yes'
            ]
        );

        $this->add_responsive_control(
            'title_separator_margin',
            [
                'label' => esc_html__('Margin Separator', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%', 'rem' ],
                'default' => [
                    'top' => '25',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'condition' => ['title_separator!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_content-wrap:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_separator_width',
            [
                'label' => esc_html__('Width Separator', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 800],
                    '%' => ['min' => .1, 'max' => 100],
                ],
                'default' => ['size' => 100, 'unit' => '%'],
                'condition' => ['title_separator!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_content-wrap:after' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_separator_height',
            [
                'label' => esc_html__('Height Separator', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 800],
                    '%' => ['min' => .1, 'max' => 100],
                ],
                'default' => ['size' => 1, 'unit' => 'px'],
                'condition' => ['title_separator!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_content-wrap:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->start_controls_tabs(
			'service_color_tab_separator',
			['condition' => ['title_separator!' => '']]
		);

        $this->start_controls_tab(
            'custom_service_separator_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'service_separator_idle',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_content-wrap:after' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_service_separator_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'service_separator_hover',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-service_content-wrap:after' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

		$this->add_control(
            'title_1st',
            [
                'label' => esc_html__('1st Part', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['s_title_1!' => ''],
                'separator' => 'before',
            ]
        );

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title_1',
                'selector' => '{{WRAPPER}} .wgl-service_title .service_title-1',
            ]
        );

        $this->start_controls_tabs( 'service_color_tab_title_1' );

        $this->start_controls_tab(
            'custom_service_color_1_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'service_color_1',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_title .service_title-1' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_service_color_1_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'service_color_1_hover',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-service_title .service_title-1' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'title_2st',
            [
                'label' => esc_html__('2nd Part', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['s_title_2!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title_2',
                'selector' => '{{WRAPPER}} .wgl-service_title .service_title-2',
            ]
        );

		$this->add_control(
            'title_2st_top_offset',
            [
                'label' => esc_html__('Top Offset', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'default' => ['size' => -19, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_title .service_title-2' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'title_2st_left_offset',
            [
                'label' => esc_html__('Left Offset', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -100, 'max' => 100, 'step' => 1],
                ],
                'default' => ['size' => -6, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_title .service_title-2' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'service_color_tab_title_2' );

        $this->start_controls_tab(
            'custom_service_color_2_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'service_color_2',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_title .service_title-2' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_service_color_2_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'service_color_2_hover',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-service_title .service_title-2' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

		/**
		 * STYLE -> SUBTITLE
		 */

        $this->start_controls_section(
            'subtitle_style_section',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_custom_fonts',
                'selector' => '{{WRAPPER}} .wgl-service_subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Subtitle Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
	                'top' => '0',
	                'right' => '38',
	                'bottom' => '6',
	                'left' => '38',
	                'unit' => 'px',
	                'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'service_color_tab_subtitle' );

        $this->start_controls_tab(
            'custom_service_color_normal_subtitle',
            [
                'label' => esc_html__('Idle' , 'transmax-core'),
            ]
        );

        $this->add_control(
            'service_color_subtitle',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_subtitle' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_service_color_hover_subtitle',
            [
                'label' => esc_html__('Hover' , 'transmax-core'),
            ]
        );

        $this->add_control(
            'service_color_hover_subtitle',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-service_subtitle' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

		/**
		 * STYLE -> DESCRIPTION
		 */

        $this->start_controls_section(
            'descr_style_section',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'descr_custom_fonts',
                'selector' => '{{WRAPPER}} .wgl-service_description',
            ]
        );

        $this->add_responsive_control(
            'descr_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
				'default' => [
				    'top' => '0',
				    'right' => '38',
				    'bottom' => '0',
				    'left' => '38',
				    'unit' => 'px',
				    'isLinked' => false,
			    ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('service_color_tab_descr');

        $this->start_controls_tab(
            'custom_service_color_normal_descr',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'service_color_descr',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_description' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_service_color_hover_descr',
            [
                'label' => esc_html__('Hover' , 'transmax-core'),
            ]
        );

        $this->add_control(
            'service_color_hover_descr',
            [
                'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-service_description' => 'color: {{VALUE}};'
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
            'button_style_section',
            [
                'label' => esc_html__('Button', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['add_read_more!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_button',
				'condition' => [
					'read_more_type' => 'btn'
				],
                'selector' => '{{WRAPPER}} .wgl-service_button span',
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
				'default' => [
				    'top' => '0',
				    'right' => '0',
				    'bottom' => '40',
				    'left' => '40',
				    'unit' => 'px',
				    'isLinked' => false,
			    ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_button-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_button-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'button_inner_padding',
            [
                'label' => esc_html__('Inner Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
				'condition' => [
					'read_more_type' => 'btn'
				],
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'button_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0, 'max' => 50, 'step' => 1 ],
			    ],
				'condition' => [
					'read_more_type' => 'icon'
				],
			    'default' => ['size' => 28, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_button, {{WRAPPER}} .wgl-service_button i' => 'border-radius: {{SIZE}}px;',
			    ],
		    ]
	    );

	    $this->start_controls_tabs(
            'button_color_tab',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle' , 'transmax-core') ]
        );

        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-service_button.icon-read-more i, {{WRAPPER}} .wgl-service_button.icon-read-more span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_button.button-read-more' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-service_button.icon-read-more i, {{WRAPPER}} .wgl-service_button.icon-read-more span' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_button.button-read-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'button_icon_rotate_idle',
		    [
			    'label' => esc_html__('Rotate', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['deg', 'turn'],
			    'range' => [
				    'deg' => ['max' => 360],
				    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
			    ],
				'condition' => [
					'read_more_type' => 'icon'
				],
			    'default' => ['unit' => 'deg'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_button i:before,
				     {{WRAPPER}} .wgl-service_button span:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
			    ],
		    ]
	    );

        $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_button_hover_item',
		    [
                'label' => esc_html__('Hover' , 'transmax-core'),
            ]
	    );

	    $this->add_control(
		    'button_color_hover_item',
		    [
			    'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button.icon-read-more i,
				    {{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button.icon-read-more span' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button.button-read-more' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_button.icon-read-more:hover i,
                    {{WRAPPER}} .wgl-service_button.icon-read-more:hover span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_button.button-read-more:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'button_bg_hover_item',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button.icon-read-more i,
				    {{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button.icon-read-more span' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button.button-read-more' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_button.icon-read-more:hover i,
                    {{WRAPPER}} .wgl-service_button.icon-read-more:hover span' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wgl-service_button.button-read-more:hover' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'button_icon_rotate_hover_item',
		    [
			    'label' => esc_html__('Rotate', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['deg', 'turn'],
			    'range' => [
				    'deg' => ['max' => 360],
				    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
			    ],
				'condition' => [
					'read_more_type' => 'icon'
				],
			    'default' => ['unit' => 'deg'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button i:before,
				    {{WRAPPER}} .wgl-service_link:hover ~ .wgl-service_button-wrapper .wgl-service_button span:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .wgl-service_button.icon-read-more:hover i:before,
                    {{WRAPPER}} .wgl-service_button.icon-read-more:hover span:before,
                    {{WRAPPER}} .wgl-service_link ~ .wgl-service_button-wrapper .wgl-service_button:hover i:before,
                    {{WRAPPER}} .wgl-service_link ~ .wgl-service_button-wrapper .wgl-service_button:hover span:before' => 'transform: rotate({{SIZE}}{{UNIT}});',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

    }

    public function render()
    {
        $_s = $this->get_settings_for_display();

        $kses_allowed_html = [
            'a' => [
				'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
            'p' => ['id' => true, 'class' => true, 'style' => true],
            'small' => ['id' => true, 'class' => true, 'style' => true],
        ];

        $this->add_render_attribute('service', 'class', 'wgl-service-1');

        // Link
        if (!empty($_s['item_link']['url'])) {
            $this->add_link_attributes('link', $_s['item_link']);
        }

	    // Media
	    if (!empty($_s['icon_type'])) {
		    $media = new WGL_Icons;
		    $ib_media = $media->build($this, $_s, []);
	    }

        // Read more button
        if ($_s['add_read_more']) {
            $this->add_render_attribute('btn', 'class', ['wgl-service_button', 'icon' === $_s['read_more_type'] ? 'icon-read-more' : 'button-read-more']);

            $icon_font = $_s['read_more_icon_fontawesome'];

            $migrated = isset($_s['__fa4_migrated']['read_more_icon_fontawesome']);
            $is_new = Icons_Manager::is_migration_allowed();
		    $icon_output = '';

		    if ( $is_new || $migrated ) {
			    ob_start();
			    Icons_Manager::render_icon( $_s['read_more_icon_fontawesome'], ['aria-hidden' => 'true'] );
			    $icon_output .= ob_get_clean();
		    } else {
			    $icon_output .= '<i class="icon '.esc_attr($icon_font).'"></i>';
		    }

		    if (!empty($icon_output) || $_s['read_more_text']){
                $s_button = '<div class="wgl-service_button-wrapper">';
                    $s_button .= sprintf('<%s %s %s>',
                        $_s['add_item_link'] ? 'div' : 'a',
                        $_s['add_item_link'] ? '' : $this->get_render_attribute_string('link'),
                        $this->get_render_attribute_string('btn')
                    );

					if('icon' === $_s['read_more_type']){
						$s_button .= $icon_output;
					}else{
						$s_button .= $_s['read_more_text'] ? '<span>' . esc_html($_s['read_more_text']) . '</span>' : '';
					}

                    $s_button .= $_s['add_item_link'] ? '</div>' : '</a>';
                $s_button .= '</div>';
		    }
        }

        // Render
        if ($_s['add_item_link'] && !empty($_s['item_link']['url'])) { ?>
            <a class="wgl-service_link" <?php echo $this->get_render_attribute_string('link'); ?>></a><?php
        }?>
        <div <?php echo $this->get_render_attribute_string('service'); ?>>
            <div class="wgl-service_content-wrap">
                <div class="wgl-service_content">
                    <?php
                    if (!empty($ib_media)) {
	                    echo $ib_media;
                    }
					if (!empty($_s['s_subtitle'])) { ?>
                        <div class="wgl-service_subtitle"><?php echo wp_kses($_s['s_subtitle'], $kses_allowed_html); ?></div><?php
                    }
                    if (!empty($_s['s_title_1'])) {
                        echo '<'. $_s['title_tag']. ' class="wgl-service_title">';
							echo '<span class="service__title service_title-1">'. wp_kses($_s['s_title_1'], $kses_allowed_html).'</span>';
							if (!empty($_s['s_title_2'])) { ?>
								<span class="service__title service_title-2"><?php echo wp_kses($_s['s_title_2'], $kses_allowed_html); ?></span><?php
							}
						echo '</'. $_s['title_tag']. '>';
                    }
                    if (!empty($_s['s_description'])) { ?>
                        <div class="wgl-service_description"><?php echo wp_kses($_s['s_description'], $kses_allowed_html); ?></div><?php
                    }?>
                </div>
            </div>
        </div><?php
	    if ( ! empty( $s_button ) ) {
		    echo $s_button;
	    }
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
