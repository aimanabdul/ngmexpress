<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-info-box.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
	Group_Control_Border,
	Widget_Base,
	Controls_Manager,
	Group_Control_Typography,
	Group_Control_Box_Shadow,
	Group_Control_Background,
	Group_Control_Css_Filter};
use WGL_Extensions\{
    Includes\WGL_Icons,
    Templates\WGLInfoBoxes
};

class WGL_Info_Box extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-info-box';
    }

    public function get_title()
    {
        return esc_html__('WGL Info Box', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-info-box';
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
            'layout',
            [
                'label' => esc_html__('Layout', 'transmax-core'),
                'type' => 'wgl-radio-image',
                'condition' => ['icon_type!' => ''],
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_def.png',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_left.png',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/style_right.png',
                    ],
                ],
                'default' => 'top',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
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

        $this->end_controls_section();

        /**
         * CONTENT -> ICON/IMAGE
         */

        $output = [];

        $output['view'] = [
            'label' => esc_html__('View', 'transmax-core'),
            'type' => Controls_Manager::SELECT,
            'condition' => ['icon_type' => ['font', 'number']],
            'options' => [
                'default' => esc_html__('Default', 'transmax-core'),
                'stacked' => esc_html__('Stacked', 'transmax-core'),
                'framed'  => esc_html__('Framed', 'transmax-core'),
                'bubble'   => esc_html__('Bubble', 'transmax-core'),
            ],
            'default' => 'bubble',
            'prefix_class' => 'elementor-view-',
        ];

        $output['shape'] = [
            'label' => esc_html__('Shape', 'transmax-core'),
            'type' => Controls_Manager::SELECT,
            'condition' => [
                'icon_type' => ['font', 'number'],
                'view!' => ['default', 'bubble'],
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
	            'default' => [
		            'media_type' => 'font',
		            'icon' => [
			            'library' => 'solid',
			            'value' => 'fas fa-icons'
		            ],
	            ],
                'media_types_options' => [
                    '' => [
                        'title' => esc_html__('None', 'transmax-core'),
                        'icon' => 'fa fa-ban',
                    ],
                    'number' => [
                        'title' => esc_html__('Number', 'transmax-core'),
                        'icon' => 'fa fa-list-ol',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'transmax-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'transmax-core'),
                        'icon' => 'far fa-image',
                    ],
                ],
            ]
        );

        /**
         * CONTENT -> CONTENT
         */

        $this->start_controls_section(
            'content_content',
            ['label' => esc_html__('Content', 'transmax-core')]
        );

        $this->add_control(
            'ib_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('This is the heading​', 'transmax-core'),
            ]
        );

        $this->add_control(
            'ib_subtitle',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
	            'placeholder' => esc_attr__('ex: 01', 'transmax-core'),
            ]
        );

	    $this->add_control(
		    'ib_bg_text',
		    [
			    'label' => esc_html__('Background Text', 'transmax-core'),
			    'type' => Controls_Manager::TEXT,
			    'label_block' => true,
		    ]
	    );

        $this->add_control(
            'ib_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'placeholder' => esc_attr__('Description Text', 'transmax-core'),
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'transmax-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> LINK
         */

        $this->start_controls_section(
            'content_link',
            ['label' => esc_html__('Link', 'transmax-core')]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'module_link',
                            'operator' => '!=',
                            'value' => '',
                        ],
                        [
                            'name' => 'add_read_more',
                            'operator' => '!=',
                            'value' => '',
                        ],
                    ],
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'module_link',
            [
                'label' => esc_html__('Whole Module Link', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

	    $this->add_control(
            'add_read_more',
            [
                'label' => esc_html__('\'Read More\' Button', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Use', 'transmax-core'),
                'label_off' => esc_html__('Hide', 'transmax-core'),
                'default' => 'yes'
            ]
        );

	    $this->add_control(
            'read_more_inline',
            [
                'label' => esc_html__('Inline Button', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'layout' => ['left', 'right'],
                    'add_read_more!' => ''
                ],
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'terms' => [
                                [
                                    'name' => 'layout',
                                    'operator' => '=',
                                    'value' => 'top',
                                ], [
                                    'name' => 'add_read_more',
                                    'operator' => '!=',
                                    'value' => '',
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'layout',
                                    'operator' => 'in',
                                    'value' => ['left', 'right'],
                                ], [
                                    'name' => 'add_read_more',
                                    'operator' => '!=',
                                    'value' => '',
                                ], [
                                    'name' => 'read_more_inline',
                                    'operator' => '=',
                                    'value' => '',
                                ]
                            ]
                        ],
                    ],
                ],
                'default' => esc_html__('READ MORE​', 'transmax-core'),
                'label_block' => true,
            ]
        );

	    $this->add_control(
		    'button_alignment',
		    [
			    'label' => esc_html__('Alignment', 'transmax-core'),
			    'type' => Controls_Manager::CHOOSE,
			    'toggle' => true,
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
				    'justify' => [
					    'title' => esc_html__('Justify', 'transmax-core'),
					    'icon' => 'fa fa-align-justify',
				    ],
			    ],
			    'condition' => [ 'add_read_more!' => '' ],
			    'prefix_class' => 'button_',
		    ]
	    );

	    $this->add_control(
		    'read_more_icon_fontawesome',
		    [
			    'label' => esc_html__('Button Icon', 'transmax-core'),
			    'type' => Controls_Manager::ICONS,
			    'condition' => [ 'add_read_more' => 'yes' ],
			    'description' => esc_html__('Select icon from available libraries.', 'transmax-core'),
			    'label_block' => true,
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
			    'label' => esc_html__('Background Animation', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
                'condition' => ['hover_toggling_icon' => ''],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox:before, {{WRAPPER}} .wgl-infobox:after' => 'display: block;',
			    ],
		    ]
	    );

	    $this->add_control(
            'hover_lifting',
            [
                'label' => esc_html__('Lift Up the Item', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'hover_toggling' => '',
                    'hover_toggling_icon' => ''
                ],
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'lifting',
                'prefix_class' => 'animation_'
            ]
        );

        $this->add_control(
            'hover_toggling_icon',
            [
                'label' => esc_html__('Toggle Icon Visibility', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'background_animation' => '',
                    'hover_lifting' => '',
                    'hover_toggling' => '',
                    'layout' => ['left', 'right'],
                    'icon_type!' => '',
                ],
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'toggling_icon',
                'prefix_class' => 'animation_'
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_icon_offset',
            [
                'label' => esc_html__('Animation Distance', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'background_animation' => '',
                    'hover_lifting' => '',
                    'hover_toggling' => '',
                    'hover_toggling_icon!' => '',
                    'layout' => ['left', 'right'],
                    'icon_type!' => '',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'default' => ['size' => 40],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling_icon .content_wrapper' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.animation_toggling_icon .elementor-widget-container:hover .content_wrapper' => 'padding-left: {{SIZE}}{{UNIT}}; margin-right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.animation_toggling_icon .media-wrapper' => 'transform: translateX(-{{SIZE}}{{UNIT}}) scale(0.5);',
                ],
            ]
        );

        $this->add_control(
            'hover_toggling',
            [
                'label' => esc_html__('Toggle Icon/Content Visibility', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'hover_lifting' => '',
                    'hover_toggling_icon' => '',
                    'layout!' => ['left', 'right'],
                    'icon_type!' => ''
                ],
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'toggling',
                'prefix_class' => 'animation_',
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_offset',
            [
                'label' => esc_html__('Animation Distance', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'hover_toggling!' => '',
                    'layout!' => ['left', 'right'],
                    'icon_type!' => '',
                ],
                'range' => [
                    'px' => ['min' => 30, 'max' => 100],
                ],
                'default' => ['size' => 40],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling .wgl-infobox_wrapper' => 'transform: translateY({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}}.animation_toggling .elementor-widget-container:hover .wgl-infobox_wrapper' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_toggling_transition',
            [
                'label' => esc_html__('Transition Duration', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'hover_toggling!' => '',
                    'layout!' => ['left', 'right'],
                    'icon_type!' => '',
                ],
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 2, 'step' => 0.1],
                ],
                'default' => ['size' => 0.6],
                'selectors' => [
                    '{{WRAPPER}}.animation_toggling .wgl-infobox_wrapper,
                     {{WRAPPER}}.animation_toggling .media-wrapper,
                     {{WRAPPER}}.animation_toggling .wgl-infobox-button_wrapper' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> ICON
         */

        $this->start_controls_section(
            'style_icon',
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
                'default' => ['size' => 42],
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
		            'top' => '38',
		            'right' => '0',
		            'bottom' => '14',
		            'left' => '38',
		            'unit'  => 'px',
		            'isLinked' => false
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
            'icon_border_radius',
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
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon,
                     {{WRAPPER}}.elementor-view-default .elementor-icon,
                     {{WRAPPER}}.elementor-view-bubble .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-default .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-bubble .elementor-icon svg' => 'fill: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_secondary_color_idle',
            [
                'label' => esc_html__('Additional Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['view!' => ['default','bubble']],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon svg' => 'fill: {{VALUE}};',
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
                     {{WRAPPER}}.elementor-view-default:hover .elementor-icon,
                     {{WRAPPER}}.elementor-view-bubble:hover .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed:hover .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-default:hover .elementor-icon svg,
                     {{WRAPPER}}.elementor-view-bubble:hover .elementor-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_secondary_color_hover',
            [
                'label' => esc_html__('Additional Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['view!' => ['default','bubble']],
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

        /**
         * STYLE -> Number
         */

		$this->start_controls_section(
			'section_style_number',
			[
				'label' => esc_html__('Number', 'transmax-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [ 'icon_type'  => 'number' ],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typo',
                'selector' => '{{WRAPPER}} .elementor-icon',
            ]
        );

		$this->start_controls_tabs( 'number_colors' );

		$this->start_controls_tab(
			'number_colors_idle',
			[ 'label' => esc_html__('Idle', 'transmax-core') ]
		);

		$this->add_control(
			'primary_number_color',
			[
				'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'secondary_number_color',
			[
				'label' => esc_html__('Additional Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'condition' => [ 'view!' => 'default' ],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'number_shadow',
				'selector' =>  '{{WRAPPER}} .elementor-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'number_colors_hover',
			[ 'label' => esc_html__('Hover', 'transmax-core') ]
		);

		$this->add_control(
			'number_primary_color_hover',
			[
				'label' => esc_html__('Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-framed:hover .elementor-icon, {{WRAPPER}}.elementor-view-default:hover .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_secondary_color_hover',
			[
				'label' => esc_html__('Additional Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'condition' => [ 'view!' => 'default' ],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.elementor-view-framed:hover .elementor-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-view-stacked:hover .elementor-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'number_hover_shadow',
				'selector' =>  '{{WRAPPER}}:hover .elementor-icon',
			]
		);

		$this->add_control(
			'hover_animation_number',
			[
				'label' => esc_html__('Hover Animation', 'transmax-core'),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'hr_number_style',
			[ 'type' => Controls_Manager::DIVIDER ]
		);

		$this->add_responsive_control(
			'number_space',
			[
				'label' => esc_html__('Margin', 'transmax-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
                'default' => [
		            'top' => '38',
		            'right' => '0',
		            'bottom' => '14',
		            'left' => '38',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'number_padding',
			[
				'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
				'condition' => [ 'view!' => 'default' ],
                'size_units' => [ 'px', 'em', '%' ],
				'default' => [ 'size' => 15, 'unit' => 'px' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_border_width',
			[
				'label' => esc_html__('Border Width', 'transmax-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'condition' => [ 'view' => 'framed' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'number_border_radius',
			[
				'label' => esc_html__('Border Radius', 'transmax-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'condition' => [ 'view!' => 'default' ],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'style_image',
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
                'selectors' => [
                    '{{WRAPPER}} figure.wgl-image-box_img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} figure.wgl-image-box_img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'px' => ['min' => 0, 'max' => 800],
                    '%' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_control(
		    'image_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .media-wrapper .wgl-image-box_img,
				     {{WRAPPER}} .media-wrapper .wgl-image-box_img img,
				     {{WRAPPER}} .media-wrapper .wgl-image-box_img:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

	    $this->add_control(
		    'image_bg_color_idle',
		    [
			    'label' => esc_html__('Image BG Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img' => 'background-color: {{VALUE}};',
			    ],
		    ]
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

	    $this->add_control(
		    'image_bg_color_hover',
		    [
			    'label' => esc_html__('Image BG Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-image-box_img' => 'color: {{VALUE}};',
			    ],
		    ]
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

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> Bubble
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_bubble',
            [
                'label' => esc_html__('Bubble', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['view' => 'bubble'],
            ]
        );

        $this->add_responsive_control(
            'bubble_top_offset',
            [
                'label' => esc_html__('Top Offset', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 1],
                ],
                'default' => ['size' => 24, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-icon .elementor-icon:after' => 'top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-number.elementor-icon .number:after' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bubble_left_offset',
            [
                'label' => esc_html__('Left Offset', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 1],
                ],
                'default' => ['size' => 50, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-icon .elementor-icon:after' => 'left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-number.elementor-icon .number:after' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'bubble_size',
            [
                'label' => esc_html__('Bubble Diameter', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => ['max' => 200],
                ],
                'default' => ['size' => 40, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-icon .elementor-icon:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-number.elementor-icon .number:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
		    'icon_animation',
		    [
			    'label' => esc_html__('Icon Bubble Animation', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => [
                    'icon_type' => ['font', 'number'],
                    'view' => 'bubble',
                ],
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'return_value' => 'bubble',
                'prefix_class' => 'animation_',
                'default' => 'bubble'
		    ]
	    );


        $this->start_controls_tabs(
            'tabs_bubble_styles',
            [ 'separator' => 'before' ]
     );

        $this->start_controls_tab(
            'tab_bubble_idle',
            [ 'label' => esc_html__('Idle', 'transmax-core') ]
        );

        $this->add_control(
            'bubble_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-icon .elementor-icon:after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble .wgl-number.elementor-icon .number:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_bubble_hover',
            [ 'label' => esc_html__('Hover', 'transmax-core') ]
        );

        $this->add_control(
            'bubble_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble:hover .wgl-icon .elementor-icon:after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-widget-wgl-info-box.elementor-view-bubble:hover .wgl-number.elementor-icon .number:after' => 'background-color: {{VALUE}};'
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
                'selector' => '{{WRAPPER}} .wgl-infobox_title',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('HTML Tag', 'transmax-core'),
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
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '8',
                    'left' => '40',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'title_border',
			    'render_type' => 'template',
			    'dynamic' => ['active' => true],
			    'fields_options' => [
				    'color' => ['type' => Controls_Manager::HIDDEN],
			    ],
			    'selector' => '{{WRAPPER}} .wgl-infobox_title',
		    ]
	    );


        $this->add_control(
            'title_separator',
            [
                'label' => esc_html__('Show Separator?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title:after' => 'display: block;',
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
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_title:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_title:after' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_title:after' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->start_controls_tabs(
            'title',
            [ 'separator' => 'before' ]
        );

        $this->start_controls_tab(
            'title_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-infobox_title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'title_shadow_idle',
			    'selector' => '{{WRAPPER}} .wgl-infobox_title',
		    ]
	    );

        $this->add_control(
            'separator_color_idle',
            [
                'label' => esc_html__('Separator Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['title_separator!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'condition' => [
	            	'title_border_border!' => ''
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_title' => 'border-color: {{VALUE}};',
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
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
		    'title_hover_shift',
		    [
			    'label' => esc_html__('Lift up the title', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px' ],
			    'default' => [ 'size' => '0', 'unit' => 'px' ],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'transform: translateY({{SIZE}}{{UNIT}})',
			    ],
		    ]
	    );

        $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'title_shadow_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title',
		    ]
	    );

	    $this->add_control(
		    'separator_color_hover',
		    [
			    'label' => esc_html__('Separator Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['title_separator!' => ''],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title:after' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

        $this->add_control(
            'title_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'condition' => [
	            	'title_border_border!' => ''
	            ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_title' => 'border-color: {{VALUE}};',
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
            'style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['ib_subtitle!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .wgl-infobox_subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
	                'top' => '35',
	                'right' => '0',
	                'bottom' => '26',
	                'left' => '38',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_subtitle_styles');

        $this->start_controls_tab(
            'tab_subtitle_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'subtitle_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_subtitle_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'subtitle_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_subtitle' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

	    /**
	     * STYLE -> BG Text
	     */

	    $this->start_controls_section(
		    'background_text',
		    [
			    'label' => esc_html__('Background Text', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['ib_bg_text!' => ''],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'custom_fonts_bg_text',
			    'selector' => '{{WRAPPER}} .wgl-infobox_bg_text',
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
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_margin',
		    [
			    'label' => esc_html__('Margin', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				    '{{WRAPPER}} .wgl-infobox_bg_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'bg_text_z_index',
		    [
			    'label' => __( 'Z-Index', 'transmax-core' ),
			    'type' => Controls_Manager::NUMBER,
			    'min' => -5,
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_bg_text_wrapper' => 'z-index: {{VALUE}};',
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
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox .wgl-infobox_bg_text' => 'color: {{VALUE}};',
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
				    '{{WRAPPER}} .wgl-infobox .wgl-infobox_bg_text' => 'background-color: {{VALUE}};',
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
			    'selectors' => [
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox .wgl-infobox_bg_text' => 'color: {{VALUE}};',
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
				    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox .wgl-infobox_bg_text' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();
	    $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'style_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['ib_content!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-infobox_content',
            ]
        );

        $this->add_control(
            'content_tag',
            [
                'label' => esc_html__('HTML Tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html('‹h1›'),
                    'h2' => esc_html('‹h2›'),
                    'h3' => esc_html('‹h3›'),
                    'h4' => esc_html('‹h4›'),
                    'h5' => esc_html('‹h5›'),
                    'h6' => esc_html('‹h5›'),
                    'div' => esc_html('‹div›'),
                    'span' => esc_html('‹span›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'content_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
		            'top' => '18',
		            'right' => '50',
		            'bottom' => '15',
		            'left' => '39',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_content_mask_color',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'condition' => ['custom_bg' => 'custom'],
                'selector' => '{{WRAPPER}} .wgl-infobox_content',
            ]
        );

        $this->start_controls_tabs('content_color_tab');

        $this->start_controls_tab(
            'custom_content_color_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_content_color_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container:hover .wgl-infobox_content' => 'color: {{VALUE}};'
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
            'style_button',
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
                'selector' => '{{WRAPPER}} .wgl-infobox_button span',
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
		            'bottom' => '0',
		            'left' => '0',
		            'unit' => 'px',
		            'isLinked' => false,
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-button_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		            'top' => '0',
		            'right' => '0',
		            'bottom' => '30',
		            'left' => '37',
		            'unit' => 'px',
		            'isLinked' => false,
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox-button_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_inner_padding',
            [
                'label' => esc_html__('Inner Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
		            'top' => '0',
		            'right' => '0',
		            'bottom' => '0',
		            'left' => '0',
		            'unit' => 'px',
		            'isLinked' => false,
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_control(
		    'button_radius',
		    [
			    'label' => esc_html__('Border Radius', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'default' => [
				    'top' => '0',
				    'right' => '0',
				    'bottom' => '0',
				    'left' => '0',
				    'unit'  => '%',
				    'isLinked' => false
			    ],
			    'condition' => [ 'read_more_icon_fontawesome!' => [
				    'value' => '',
				    'library' => ''
			    ]],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'button_border',
			    'render_type' => 'template',
			    'dynamic' => ['active' => true],
			    'fields_options' => [
				    'color' => ['type' => Controls_Manager::HIDDEN],
			    ],
			    'condition' => [ 'read_more_icon_fontawesome!' => [
				    'value' => '',
				    'library' => ''
			    ]],
			    'selector' => '{{WRAPPER}} .wgl-infobox_button',
		    ]
	    );

	    $this->add_responsive_control(
		    'button_icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em', 'rem'],
			    'range' => [
				    'px' => ['min' => 6, 'max' => 300],
			    ],
			    'default' => ['size' => 20],
			    'condition' => [ 'read_more_icon_fontawesome!' => [
				    'value' => '',
				    'library' => ''
			    ]],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_button:before' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'button_icon_rotate',
		    [
			    'label' => esc_html__('Rotate', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['deg', 'turn'],
			    'range' => [
				    'deg' => ['max' => 360],
				    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
			    ],
			    'default' => ['unit' => 'deg'],
			    'condition' => [ 'read_more_icon_fontawesome!' => [
				    'value' => '',
				    'library' => ''
			    ]],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox_button:before' => 'transform: rotate({{SIZE}}{{UNIT}}); display: inline-block;',
			    ],
		    ]
	    );

        $this->start_controls_tabs(
            'tabs_button',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_button_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'button_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_bg_idle',
            [
                'label' => esc_html__('Additional Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'button_icon_color_idle',
		    [
			    'label' => esc_html__('Icon Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => [ 'read_more_icon_fontawesome!' => [
				    'value' => '',
				    'library' => ''
			    ]],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button:before' => 'color: {{VALUE}}'
			    ],
		    ]
	    );

	    $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
	            'condition' => [
	            	'button_border_border!' => '',
		            'read_more_icon_fontawesome!' => [
			            'value' => '',
			            'library' => ''
		            ]
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button' => 'border-color: {{VALUE}};',
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
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-infobox .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-infobox_button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_icon_bg_hover',
            [
                'label' => esc_html__('Additional Hover Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-infobox_button:hover, {{WRAPPER}} .wgl-infobox_button:hover,
                     {{WRAPPER}} .wgl-infobox .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-infobox_button,
                     {{WRAPPER}} .wgl-infobox .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-infobox_button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

	    $this->add_control(
		    'button_icon_color_hover',
		    [
			    'label' => esc_html__('Icon Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => [ 'read_more_icon_fontawesome!' => [
				    'value' => '',
				    'library' => ''
			    ]],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button:hover:before,
				     {{WRAPPER}} .wgl-infobox .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-infobox_button:before' => 'color: {{VALUE}}'
			    ],
		    ]
	    );

	    $this->add_control(
		    'button_border_color_hover',
		    [
			    'label' => esc_html__('Border Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'default' => 'rgba(255,255,255,0)',
			    'condition' => [
				    'button_border_border!' => '',
				    'read_more_icon_fontawesome!' => [
					    'value' => '',
					    'library' => ''
				    ]
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox .wgl-infobox_button:hover,
				     {{WRAPPER}} .wgl-infobox .wgl-infobox__link:hover ~ .wgl-infobox_wrapper .wgl-infobox_button' => 'border-color: {{VALUE}}'
			    ],
		    ]
	    );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> BACKGROUND
         */

        $this->start_controls_section(
            'style_background',
            [
                'label' => esc_html__('Background', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_overflow',
            [
                'label' => esc_html__('Module Overflow', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Theme Default', 'transmax-core'),
                    'overflow: visible;' => esc_html__('Visible', 'transmax-core'),
                    'overflow: hidden;' => esc_html__('Hidden', 'transmax-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => '{{VALUE}}',
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
				    '{{WRAPPER}} .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'container_animation_color',
		    [
			    'label' => esc_html__('Border Color for Animation', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['background_animation!' => ''],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-infobox:before, {{WRAPPER}} .wgl-infobox:after' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('tabs_background');

        $this->start_controls_tab(
            'tab_bg_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
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
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_idle',
                'selector' => '{{WRAPPER}} .elementor-widget-container',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'container_shadow_idle',
			    'selector' => '{{WRAPPER}} .elementor-widget-container',
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

	    $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_bg_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
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
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border_hover',
                'selector' => '{{WRAPPER}} .elementor-widget-container:hover',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'container_shadow_hover',
			    'selector' => '{{WRAPPER}} .elementor-widget-container:hover',
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
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new WGLInfoBoxes())->render($this, $atts);
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
