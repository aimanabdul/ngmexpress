<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-flipbox.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Icons_Manager,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Icons
};

class WGL_Flipbox extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-flipbox';
    }

    public function get_title()
    {
        return esc_html__('WGL Flipbox', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-flipbox';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'dev_view',
            [
                'label' => esc_html__('Show Back Side', 'transmax-core'),
                'description' => esc_html__('This option does not affect the result in any way', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '-active',
                'prefix_class' => 'dev_view'
            ]
        );

        $this->add_control(
            'flip_direction',
            [
                'label' => esc_html__('Flip Direction', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'flip_right' => esc_html__('Right', 'transmax-core'),
                    'flip_left' => esc_html__('Left', 'transmax-core'),
                    'flip_top' => esc_html__('Top', 'transmax-core'),
                    'flip_bottom' => esc_html__('Bottom', 'transmax-core'),
                ],
                'default' => 'flip_right',
            ]
        );

        $this->add_control(
            'flipbox_height',
            [
                'label' => esc_html__('Module Height', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 150,
                'step' => 10,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_item',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_item_front',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_control(
            'h_alignment_front',
            [
                'label' => esc_html__('Horizontal Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'v_alignment_front',
            [
                'label' => esc_html__('Vertical Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'transmax-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_back',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_control(
            'h_alignment_back',
            [
                'label' => esc_html__('Horizontal Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content:after' => '{{VALUE}}: 0;',
                ],
            ]
        );

        $this->add_control(
            'v_alignment_back',
            [
                'label' => esc_html__('Vertical Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'transmax-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> MEDIA
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_media',
            ['label' => esc_html__('Media', 'transmax-core')]
        );

        $this->start_controls_tabs( 'flipbox_icon' );

        $this->start_controls_tab(
            'flipbox_front_icon',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        WGL_Icons::init(
            $this,
            [
                'label' => esc_html__('Flipbox ', 'transmax-core'),
                'output' => '',
                'section' => false,
                'prefix' => 'front_'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'flipbox_back_icon',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        WGL_Icons::init(
            $this,
            [
                'label' => esc_html__('Flipbox ', 'transmax-core'),
                'output' => '',
                'section' => false,
                'prefix' => 'back_'
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'transmax-core')]
        );

        $this->start_controls_tabs('tabs_content');

        $this->start_controls_tab(
            'tab_content_front',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_control(
            'title_front',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Front Heading​', 'transmax-core'),
                'default' => esc_html__('This is the heading​', 'transmax-core'),
            ]
        );

        $this->add_control(
            'content_front',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'separator' => 'before',
                'label_block' => true,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Front Content', 'transmax-core'),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_content_back',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_control(
            'back_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'back_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Back Content', 'transmax-core'),
                'default' => esc_attr__('We all work toward a common goal: We don’t just move our customers’ goods, we take them further.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'back_content_trail',
            [
                'label' => esc_html__('Add Content Trailing Line', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['back_content!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content' => 'margin-bottom: 1em;',
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content:after' => 'content: \'\'',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> LINK
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_link',
            ['label' => esc_html__('Link', 'transmax-core')]
        );

        $this->add_control(
            'add_item_link',
            [
                'label' => esc_html__('Add Link To Whole Item', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_read_more' => ''],
            ]
        );

        $this->add_control(
            'item_link',
            [
                'label' => esc_html__('Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'condition' => ['add_item_link!' => ''],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'add_read_more',
            [
                'label' => esc_html__('Add \'Read More\' Button', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_item_link' => ''],
                'default' => 'yes'
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Button Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'condition' => ['add_read_more!' => ''],
                'label_block' => true,
            ]
        );

        $this->start_controls_tabs(
            'tabs_button_link', [
                'condition' => [
                    'add_item_link' => '',
                    'add_read_more!' => ''
                ],
            ]
        );

        $this->start_controls_tab(
            'tab_button_front',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_control(
            'read_more_type_front',
            [
                'label' => esc_html__('Type', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'transmax-core'),
                    'btn' => esc_html__('Button', 'transmax-core'),
                ],
                'default' => 'icon',
            ]
        );

		$this->add_control(
            'read_more_text_front',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('READ MORE​', 'transmax-core'),
				'condition' => [
					'read_more_type_front' => 'btn'
				],
                'label_block' => true,
            ]
        );

	    $this->add_control(
            'read_more_icon_fontawesome_front',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
					'read_more_type_front' => 'icon'
				],
                'label_block' => true,
                'description' => esc_html__('Select icon from available libraries.', 'transmax-core'),
				'default' => [
                    'library' => 'flaticon',
                    'value' => 'flaticon-right-arrow',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_back',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_control(
            'read_more_type_back',
            [
                'label' => esc_html__('Type', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'icon' => esc_html__('Icon', 'transmax-core'),
                    'btn' => esc_html__('Button', 'transmax-core'),
                ],
                'default' => 'btn',
            ]
        );

		$this->add_control(
            'read_more_text_back',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('READ MORE​', 'transmax-core'),
				'condition' => [
					'read_more_type_back' => 'btn'
				],
                'label_block' => true,
            ]
        );

	    $this->add_control(
            'read_more_icon_fontawesome_back',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
					'read_more_type_back' => 'icon'
				],
                'label_block' => true,
                'description' => esc_html__('Select icon from available libraries.', 'transmax-core'),
				'default' => [
                    'library' => 'flaticon',
                    'value' => 'flaticon-right-arrow',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('flipbox_style');

        $this->start_controls_tab(
            'flipbox_front_style',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'front_background',
                'label' => esc_html__('Front Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-flipbox_front',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'flipbox_back_style',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'back_background',
                'label' => esc_html__('Back Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-flipbox_back',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'flipbox_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'separator' => 'before',
                'default' => [
                    'top' => '25',
                    'right' => '40',
                    'bottom' => '15',
                    'left' => '40',
                    'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'flipbox_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'flipbox_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'flipbox_border',
                'selector' => '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'flipbox_shadow',
                'selector' => '{{WRAPPER}} .wgl-flipbox_front, {{WRAPPER}} .wgl-flipbox_back',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> MEDIA
         */

        $this->start_controls_section(
            'section_style_media',
            [
                'label' => esc_html__('Media', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_media' );

        $this->start_controls_tab(
            'tab_media_front',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_responsive_control(
            'media_margin_front',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 14,
                    'left' => 0,
                    'right' => 0,
                    'bottom' => 11,
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_media-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_front',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['front_icon_type' => 'font'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .elementor-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-flipbox_front .elementor-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size_front',
            [
                'label' => esc_html__('Icon Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['front_icon_type' => 'font'],
                'range' => [
                    'px' => ['min' => 16, 'max' => 100 ],
                ],
                'default' => ['size' => 34, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width_front',
            [
                'label' => esc_html__('Image Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'front_icon_type' => 'image',
                    'front_thumbnail[url]!' => '',
                ],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 800 ],
                    '%' => ['min' => 5, 'max' => 100 ],
                ],
                'default' => ['size' => 75, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_media_back',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_responsive_control(
            'media_margin_back',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_media-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_back',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['back_icon_type' => 'font'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .elementor-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-flipbox_back .elementor-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size_back',
            [
                'label' => esc_html__('Icon Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['back_icon_type' => 'font'],
                'range' => [
                    'px' => ['min' => 16, 'max' => 100 ],
                ],
                'default' => ['size' => 34, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width_back',
            [
                'label' => esc_html__('Image Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => [
                    'back_icon_type' => 'image',
                    'back_thumbnail[url]!' => '',
                ],
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 800 ],
                    '%' => ['min' => 5, 'max' => 100 ],
                ],
                'default' => ['size' => 50, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-image-box_img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_title');

        $this->start_controls_tab(
            'front_title_style',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_front',
                'selector' => '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_title span',
            ]
        );

        $this->add_control(
            'title_tag_front',
            [
                'label' => esc_html__('HTML Tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                    'div' => '‹div›',
                    'span' => '‹span›',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'title_color_front',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_title span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin_front',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'back_title_style',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_back',
                'selector' => '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_title span',
            ]
        );

        $this->add_control(
            'title_tag_back',
            [
                'label' => esc_html__('HTML Tag', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => '‹h1›',
                    'h2' => '‹h2›',
                    'h3' => '‹h3›',
                    'h4' => '‹h4›',
                    'h5' => '‹h5›',
                    'h6' => '‹h6›',
                    'div' => '‹div›',
                    'span' => '‹span›',
                ],
                'default' => 'h3',
            ]
        );

        $this->add_control(
            'title_color_back',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_title span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin_back',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '22',
                    'left' => '0',
                    'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_content_styles' );

        $this->start_controls_tab(
            'front_content_style',
            ['label' => esc_html__('Front', 'transmax-core')]
        );

        $this->add_responsive_control(
            'front_content_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_front_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_content',
            ]
        );

        $this->add_control(
            'front_content_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_front .wgl-flipbox_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'back_content_style',
            ['label' => esc_html__('Back', 'transmax-core')]
        );

        $this->add_responsive_control(
            'back_content_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [
                    'top' => '14',
                    'right' => '0',
                    'bottom' => '14',
                    'left' => '0',
                    'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_back_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content',
            ]
        );

        $this->add_control(
            'back_content_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_back .wgl-flipbox_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> BUTTON ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_button_icon',
            [
                'label' => esc_html__('Button Icon', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['add_read_more!' => ''],
            ]
        );

        $this->add_responsive_control(
		    'read_more_icon_size',
		    [
			    'label' => esc_html__('Icon Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 10, 'max' => 100 ],
			    ],
			    'default' => ['size' => 24 ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more i' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'read_more_icon_spacing',
		    [
			    'label' => esc_html__('Icon Wrapper Size', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 10, 'max' => 100 ],
			    ],
			    'default' => ['size' => 55 ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more i,{{WRAPPER}} .wgl-flipbox_button.icon-read-more span' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'custom_button_icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '4',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_icon_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->start_controls_tabs(
            'tabs_button_icon',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_button_icon_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'button_icon_color_idle',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more i, {{WRAPPER}} .wgl-flipbox_button.icon-read-more span' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_control(
		    'button_icon_bg_idle',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more' => 'background: {{VALUE}};',
			    ],
		    ]
	    );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_idle',
                'label' => esc_html__('Border Type', 'transmax-core'),
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.icon-read-more',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_icon_idle',
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.icon-read-more',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_icon_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'button_icon_color_hover',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more:hover' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'button_icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.icon-read-more:hover' => 'background: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_icon_hover',
                'label' => esc_html__('Border Type', 'transmax-core'),
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.icon-read-more:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_icon_hover',
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.icon-read-more:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> BUTTON TEXT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['add_read_more!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_button_font',
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.button-read-more',
            ]
        );

        $this->add_responsive_control(
            'custom_button_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '4',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.button-read-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'custom_button_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.button-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.button-read-more' => 'color: {{VALUE}};',
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
				    '{{WRAPPER}} .wgl-flipbox_button.button-read-more' => 'background: {{VALUE}};',
			    ],
		    ]
	    );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_idle',
                'label' => esc_html__('Border Type', 'transmax-core'),
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.button-read-more',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_idle',
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.button-read-more',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.button-read-more:hover' => 'background: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-flipbox_button.button-read-more:hover' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_hover',
                'label' => esc_html__('Border Type', 'transmax-core'),
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.button-read-more:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover',
                'selector' => '{{WRAPPER}} .wgl-flipbox_button.button-read-more:hover',
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
                'href' => true, 'title' => true,
                'class' => true, 'style' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['class' => true, 'style' => true],
            'em' => ['class' => true, 'style' => true],
            'strong' => ['class' => true, 'style' => true],
            'span' => ['class' => true, 'style' => true],
            'p' => ['class' => true, 'style' => true]
        ];

        $this->add_render_attribute('flipbox', 'class', ['wgl-flipbox', 'type_'.$_s['flip_direction'] ]);

        if (isset($_s['link']['url'])) $this->add_link_attributes('flipbox_link', $_s['link']);

        $this->add_render_attribute('item_link', 'class', 'wgl-flipbox_item-link');
        if (isset($_s['item_link']['url'])) $this->add_link_attributes('item_link', $_s['item_link']);

        // Icon/Image
        ob_start();
        if (!empty($_s['front_icon_type'])) {
            $icons = new WGL_Icons;
            echo $icons->build($this, $_s, 'front_');
        }
        $front_media = ob_get_clean();

        ob_start();
        if (!empty($_s['back_icon_type'])) {
            $icons = new WGL_Icons;
            echo $icons->build($this, $_s, 'back_');
        }
        $back_media = ob_get_clean();

        $front_btn = $back_btn = '';
        // Read more button
        if ($_s['add_read_more']) {
            $btn = ['front', 'back'];
            foreach ($btn as $v) {
                $this->add_render_attribute('btn_'.$v, 'class', ['wgl-flipbox_button','btn_'.$v, 'icon' === $_s['read_more_type_'.$v] ? 'icon-read-more' : 'button-read-more']);

                ${'icon_'.$v} =  $_s['read_more_icon_fontawesome_'.$v];

                $migrated = isset($_s['__fa4_migrated']['read_more_icon_fontawesome_'.$v]);
                $is_new = Icons_Manager::is_migration_allowed();
                $icon_output = '';

                if ( $is_new || $migrated ) {
                    ob_start();
                    Icons_Manager::render_icon( $_s['read_more_icon_fontawesome_'.$v], ['aria-hidden' => 'true'] );
                    $icon_output .= ob_get_clean();
                } else {
                    $icon_output .= '<i class="icon '.esc_attr(${'icon_'.$v}).'"></i>';
                }

                if (!empty($icon_output) || $_s['read_more_text_'.$v]){
                    ${$v.'_btn'} = '<div class="wgl-flipbox_button-wrap">';
                        ${$v.'_btn'} .= sprintf('<%s %s %s>',
                            'a',
                            $this->get_render_attribute_string('flipbox_link'),
                            $this->get_render_attribute_string('btn_'.$v)
                        );
                        if('icon' === $_s['read_more_type_' .$v]){
                            ${$v.'_btn'} .= $icon_output;
                        }else{
                            ${$v.'_btn'} .= $_s['read_more_text_'.$v] ? '<span>' . esc_html($_s['read_more_text_'.$v]) . '</span>' : '';
                        }
                        ${$v.'_btn'} .= '</a>';
                    ${$v.'_btn'} .= '</div>';
                }
            }
        }

        // Render
        echo '<div ', $this->get_render_attribute_string('flipbox'), '>';
            echo '<div class="wgl-flipbox_wrap">';

                echo '<div class="wgl-flipbox_front">';
                    if ($_s['front_icon_type'] && $front_media) {
                        echo '<div class="wgl-flipbox_media-wrap">',
                            $front_media,
                        '</div>';
                    }
                    if (!empty($_s['title_front'])) {
                        echo '<', $_s['title_tag_front'], ' class="wgl-flipbox_title">',
                            '<span>',
                                wp_kses($_s['title_front'], $kses_allowed_html),
                            '</span>',
                        '</', $_s['title_tag_front'], '>';
                    }
                    if (!empty($_s['content_front'])) {
                        echo '<div class="wgl-flipbox_content">',
                            wp_kses($_s['content_front'], $kses_allowed_html),
                        '</div>';
                    }

                    echo $front_btn;

                echo '</div>'; // wgl-flipbox_front

                echo '<div class="wgl-flipbox_back">';
                    if ($_s['back_icon_type'] && $back_media) {
                        echo '<div class="wgl-flipbox_media-wrap">',
                            $back_media,
                        '</div>';
                    }
                    if (!empty($_s['back_title'])) {
                        echo '<', $_s['title_tag_back'], ' class="wgl-flipbox_title">',
                            '<span>',
                                wp_kses($_s['back_title'], $kses_allowed_html),
                            '</span>',
                        '</', $_s['title_tag_back'], '>';
                    }
                    if (!empty($_s['back_content'])) {
                        echo '<div class="wgl-flipbox_content">',
                            wp_kses($_s['back_content'], $kses_allowed_html),
                        '</div>';
                    }
                    echo $back_btn;

                echo '</div>'; // _back

            echo '</div>';

            if ($_s['add_item_link']) {
                echo '<a ', $this->get_render_attribute_string('item_link'), '></a>';
            }

        echo '</div>';

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