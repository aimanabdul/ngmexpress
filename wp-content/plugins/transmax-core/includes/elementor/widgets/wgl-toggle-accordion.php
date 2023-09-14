<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-toggle-accordion.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Frontend,
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Repeater
};
use WGL_Framework;
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Elementor_Helper
};


class WGL_Toggle_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-toggle-accordion';
    }

    public function get_title()
    {
        return esc_html__('WGL Toggle/Accordion', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-toggle-accordion';
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
            'acc_type',
            [
                'label' => esc_html__('Type', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'accordion' => esc_html__('Accordion', 'transmax-core'),
                    'toggle' => esc_html__('Toggle', 'transmax-core'),
                ],
                'default' => 'toggle',
            ]
        );

        $this->add_control(
            'heading_desktop',
            [
                'label' => esc_html__('Icon Settings', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_acc_icon',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'transmax-core'),
                    'plus' => esc_html__('Plus/Minus', 'transmax-core'),
                    'custom' => esc_html__('Custom', 'transmax-core'),
                ],
                'default' => 'plus',
            ]
        );

        $this->add_control(
            'icon_style',
            [
                'label' => esc_html__('Style', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['enable_acc_icon!' => 'none'],
                'options' => [
                    'default' => esc_html__('Default', 'transmax-core'),
                    'stacked' => esc_html__('Stacked', 'transmax-core'),
                    'framed' => esc_html__('Framed', 'transmax-core'),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-view-'
            ]
        );

        $this->add_control(
            'acc_icon',
            [
                'label' => esc_html__('Choose Icon', 'transmax-core'),
                'type' => Controls_Manager::ICON,
                'condition' => ['enable_acc_icon' => 'custom'],
                'label_block' => true,
                'include' => [
                    'flaticon flaticon-play',
                    'fa fa-chevron-right',
                    'fa fa-plus',
                    'fa fa-long-arrow-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-arrow-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-angle-right',
                    'fa fa-angle-double-right',
                ],
                'default' => 'fa fa-chevron-right',
            ]
        );

        $this->add_control(
            'icon_alignment',
            [
                'label' => esc_html__('Icon Position', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['enable_acc_icon!' => 'none'],
                'options' => [
                    'order: 1' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'order: 0; flex-grow: 1' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'order: 0; flex-grow: 1',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_title' => '{{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'transmax-core')]
        );

        $this->add_responsive_control(
            'tab_panel_margin',
            [
                'label' => esc_html__('Tab Panel Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_tab_panel_border',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel',
	            'dynamic' => ['active' => true],
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'width'  => ['default' => [
                        'top'      => '0',
                        'right'    => '0',
                        'bottom'   => '1',
                        'left'     => '0',
                    ]],
                    'color' => ['default' => '#d9dadf'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label' => 'Tab Panel Shadow',
                'name' => 'acc_tab_panel_shadow',
                'selector' => '{{WRAPPER}} .wgl-accordion_panel',
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
			'acc_tab_title',
			[
                'label' => esc_html__('Tab Title', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Tab Title', 'transmax-core'),
                'dynamic' => ['active' => true],
			]
        );
        $repeater->add_control(
			'acc_tab_title_pref',
			[
                'label' => esc_html__('Title Prefix', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
			]
        );
        $repeater->add_control(
			'acc_tab_def_active',
			[
                'label' => esc_html__('Active as Default', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
			]
        );
        $repeater->add_control(
			'acc_content_type',
			[
                'label' => esc_html__('Content Type', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => esc_html__('Content', 'transmax-core'),
                    'template' => esc_html__('Saved Templates', 'transmax-core'),
                ],
                'default' => 'content',
			]
        );
        $repeater->add_control(
			'acc_content_templates',
			[
                'label' => esc_html__('Choose Template', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'acc_content_type' => 'template',
                ],
			]
        );
        $repeater->add_control(
			'acc_content',
			[
                'label' => esc_html__('Tab Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'transmax-core'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'acc_content_type' => 'content',
                ],
			]
        );

        $this->add_control(
            'acc_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['acc_tab_title' => esc_html__('Tab Title 1', 'transmax-core')],
	                [
		                'acc_tab_title' => esc_html__('Tab Title 2', 'transmax-core'),
		                'acc_tab_def_active' => 'yes'
	                ],
                    ['acc_tab_title' => esc_html__('Tab Title 3', 'transmax-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{acc_tab_title}}',
            ]
        );

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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'acc_title_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_title',
            ]
        );

        $this->add_control(
            'acc_title_tag',
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
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '15',
                    'right' => '0',
                    'bottom' => '22',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('acc_header_tabs');

        $this->start_controls_tab(
            'acc_header_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'acc_title_color',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_title_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_title_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_shadow_radius',
                'selector' => '{{WRAPPER}} .wgl-accordion_header',
	            'dynamic' => ['active' => true],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .wgl-accordion_header',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_header_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_title_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover',
	            'dynamic' => ['active' => true],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_title_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_header_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            'acc_title_color_active',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_title_bg_color_active',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_title_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_title_border_active',
                'selector' => '{{WRAPPER}} .wgl-accordion_header.active',
	            'dynamic' => ['active' => true],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'acc_title_shadow_active',
                'selector' => '{{WRAPPER}} .wgl-accordion_header.active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> TITLE PREFIX
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title_pref',
            [
                'label' => esc_html__('Title Prefix', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'acc_title_pref_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_title .wgl-accordion_title-prefix',
            ]
        );


        $this->start_controls_tabs('acc_header_pref_tabs');

        $this->start_controls_tab(
            'acc_header_pref_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'acc_title_pref_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_header_pref_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'title_pref_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'acc_header_pref_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            'acc_title_pref_color_active',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active .wgl-accordion_title-prefix' => 'color: {{VALUE}};',
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
            ]
        );

        $this->add_responsive_control(
            'acc_icon_size',
            [
                'label' => esc_html__('Icon Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['enable_acc_icon' => 'custom'],
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 100],
                ],
                'default' => ['size' => 14, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_icon_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_width',
            [
                'label' => esc_html__('Border Width', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('acc_icon_tabs');

        $this->start_controls_tab(
            'acc_icon_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-plus .wgl-accordion_icon:before,{{WRAPPER}} .icon-plus .wgl-accordion_icon:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_idle',
                'selector' => '{{WRAPPER}} .wgl-accordion_icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_icon_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'acc_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-plus .wgl-accordion_header:hover .wgl-accordion_icon:before, {{WRAPPER}} .icon-plus .wgl-accordion_header:hover .wgl-accordion_icon:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_hover',
                'selector' => '{{WRAPPER}} .wgl-accordion_header:hover .wgl-accordion_icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'acc_icon_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            'icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active .wgl-accordion_icon:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .icon-plus .wgl-accordion_header.active .wgl-accordion_icon:before,
                     {{WRAPPER}} .icon-plus .wgl-accordion_header.active .wgl-accordion_icon:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_active',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active .wgl-accordion_icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color_active',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_header.active .wgl-accordion_icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_active',
                'selector' => '{{WRAPPER}} .wgl-accordion_header.active .wgl-accordion_icon',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'acc_content_typo',
                'selector' => '{{WRAPPER}} .wgl-accordion_content',
            ]
        );

        $this->add_responsive_control(
            'acc_content_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '25',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'acc_content_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'acc_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-accordion_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'acc_content_border',
                'selector' => '{{WRAPPER}} .wgl-accordion_content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute(
            'accordion',
            [
                'class' => [
                    'wgl-accordion',
                    'icon-' . $_s['enable_acc_icon'],
                ],
                'id' => 'wgl-accordion-' . esc_attr($this->get_id()),
                'data-type' => $_s['acc_type'],
            ]
        );

        echo '<div ', $this->get_render_attribute_string('accordion'), '>';

        foreach ($_s['acc_tab'] as $index => $item) :

            $tab_count = $index + 1;

            $tab_title_key = $this->get_repeater_setting_key('acc_tab_title', 'acc_tab', $index);

            $this->add_render_attribute(
                $tab_title_key,
                [
                    'id' => 'wgl-accordion_header-' . $id_int . $tab_count,
                    'class' => ['wgl-accordion_header'],
                    'data-default' => $item['acc_tab_def_active'],
                ]
            );

            echo '<div class="wgl-accordion_panel">';
	            echo '<', $_s['acc_title_tag'], ' ', $this->get_render_attribute_string($tab_title_key), '>';

		            echo '<span class="wgl-accordion_title">';
		            if (!empty($item['acc_tab_title_pref'])) {
		                echo '<span class="wgl-accordion_title-prefix">',
		                    $item['acc_tab_title_pref'],
		                    '</span>';
		            }
		            echo $item['acc_tab_title'];
		            echo '</span>'; // _title

		            if ($_s['enable_acc_icon'] != 'none') {
		                echo '<i class="wgl-accordion_icon elementor-icon ', $_s['acc_icon'], '"></i>';
		            }

	            echo '</', $_s['acc_title_tag'], '>';

	            echo '<div class="wgl-accordion_content">';

		            if ($item['acc_content_type'] == 'content') {
		                echo do_shortcode($item['acc_content']);
		            } elseif ($item['acc_content_type'] == 'template') {
		                $id = $item['acc_content_templates'];
		                $wgl_frontend = new Frontend;
		                echo $wgl_frontend->get_builder_content_for_display($id, false);
		            }

	            echo '</div>'; // _content

            echo '</div>'; // _panel

        endforeach;

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
