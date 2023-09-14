<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-time-line-vertical.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Repeater,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Time_Line_Vertical extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-time-line-vertical';
    }

    public function get_title()
    {
        return esc_html__('WGL Time Line Vertical', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-time-line-vertical';
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
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> CONTENT
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_content',
            [ 'label' => esc_html__('Content', 'transmax-core') ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail_idle',
            [
                'label' => esc_html__('Thumbnail', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'thumbnail_switch',
            [
                'label' => esc_html__('Change on hover?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'thumbnail_hover',
            [
                'label' => esc_html__('Hover Thumbnail', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'thumbnail_switch!' => '' ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 1,
                'separator' => 'before',
                'placeholder' => esc_attr__('Your title', 'transmax-core'),
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit.', 'transmax-core'),
            ]
        );

        $repeater->add_control(
            'date',
            [
                'label' => esc_html__('Date', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Layers', 'transmax-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
	                    'title' => esc_html__('First heading​', 'transmax-core'),
                        'date' => esc_html__('2019', 'transmax-core'),
                    ],
	                [
		                'title' => esc_html__('Second heading​', 'transmax-core'),
		                'date' => esc_html__('2020', 'transmax-core'),
	                ],
                ],
                'title_field' => '{{title}}',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> APPEARANCE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_animation',
            [ 'label' => esc_html__('Appearance', 'transmax-core') ]
        );

        $this->add_control(
            'add_appear',
            [
                'label' => esc_html__('Use Appear Animation?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CURVE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_curve',
            [
                'label' => esc_html__('Main Curve', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'curve_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
                'default' => [
                    'top' => '0',
                    'right' => '70',
                    'bottom' => '0',
                    'left' => '70',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '25',
                    'bottom' => '0',
                    'left' => '25',
	                'unit' => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:nth-child(odd) .tlv__curve-wrapper,
                     body[data-elementor-device-mode="tablet"] {{WRAPPER}} .tlv__item:nth-child(even) .tlv__curve-wrapper,
                     body[data-elementor-device-mode="mobile"] {{WRAPPER}} .tlv__item:nth-child(even) .tlv__curve-wrapper,
                     body.elementor-device-tablet {{WRAPPER}} .tlv__item:nth-child(even) .tlv__curve-wrapper,
                     body.elementor-device-mobile {{WRAPPER}} .tlv__item:nth-child(even) .tlv__curve-wrapper' => 'margin: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .tlv__item:nth-child(even) .tlv__curve-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->add_control(
            'curve_bg',
            [
                'label' => esc_html__('Curve Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#d0d0d0',
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-wrapper:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'start_end_curve_color',
            [
                'label' => esc_html__('Start/End Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__items-start,
                     {{WRAPPER}} .tlv__items-end' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_pointer' );

        $this->start_controls_tab(
            'tab_pointer_idle',
            [ 'label' => esc_html__('Idle', 'transmax-core') ]
        );

        $this->add_control(
            'in_pointer_color_idle',
            [
                'label' => esc_html__('Pointer Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__curve-wrapper span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'line_bg',
            [
                'label' => esc_html__('Line Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__curve-wrapper:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_pointer_hover',
            [ 'label' => esc_html__('Hover', 'transmax-core') ]
        );

        $this->add_control(
            'in_pointer_bg_hover',
            [
                'label' => esc_html__('Pointer Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__curve-wrapper span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'line_bg_hover',
            [
                'label' => esc_html__('Line Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__curve-wrapper:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CONTENT & MEDIA WRAPPER
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => esc_html__('Content & Media Wrapper', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wrapper_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Left/right values are inversed for odd items.', 'transmax-core' ),
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item .tlv__volume-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .tlv__item:nth-child(odd) .tlv__volume-wrapper' => 'padding-left: {{RIGHT}}{{UNIT}}; padding-right: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .tlv__item:nth-child(odd) .tlv__content-wrapper' => 'border-radius: {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_control(
		    'wrapper_border_animation_color',
		    [
			    'label' => esc_html__('Border Color for Animation', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .tlv__content-wrapper:before, {{WRAPPER}} .tlv__content-wrapper:after' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs(
            'tabs_wrapper',
            [ 'separator' => 'before' ]
        );

        $this->start_controls_tab(
            'tab_wrapper_idle',
            [ 'label' => esc_html__('Idle', 'transmax-core') ]
        );

        $this->add_control(
            'wrapper_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_idle',
                'selector' => '{{WRAPPER}} .tlv__content-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_idle',
                'selector' => '{{WRAPPER}} .tlv__content-wrapper',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_wrapper_hover',
            [ 'label' => esc_html__('Hover', 'transmax-core') ]
        );

        $this->add_control(
            'wrapper_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__content-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wrapper_hover',
                'selector' => '{{WRAPPER}} .tlv__item:hover .tlv__content-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wrapper_hover',
                'selector' => '{{WRAPPER}} .tlv__item:hover .tlv__content-wrapper',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
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

        $this->add_control(
            'media_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Left/right values are inversed for odd items.', 'transmax-core' ),
            ]
        );

        $this->add_responsive_control(
            'media_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'mobile_default' => [
                    'top' => '0',
                    'right' => '30',
                    'bottom' => '0',
                    'left' => '30',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__media' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .tlv__item:nth-child(odd) .tlv__media' => 'margin-left: {{RIGHT}}{{UNIT}}; margin-right: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .tlv__media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .tlv__item:nth-child(odd) .tlv__media' => 'padding-left: {{RIGHT}}{{UNIT}}; padding-right: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'media_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .tlv__item:nth-child(odd) .tlv__media' => 'border-radius: {{RIGHT}}{{UNIT}} {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'media',
                'selector' => '{{WRAPPER}} .tlv__media',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Content Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'description' => esc_html__('Note. Left/right values are inversed for odd items.', 'transmax-core'),
	            'default' => [
		            'top' => '25',
		            'right' => '30',
		            'bottom' => '20',
		            'left' => '30',
		            'unit' => 'px',
		            'isLinked' => false
	            ],
                'tablet_default' => [
                    'top' => '10',
                    'right' => '30',
                    'bottom' => '46',
                    'left' => '30',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'mobile_default' => [
                    'top' => '10',
                    'right' => '30',
                    'bottom' => '46',
                    'left' => '30',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    'body[data-elementor-device-mode="desktop"] {{WRAPPER}} .tlv__item:nth-child(odd) .tlv__content' => 'padding-left: {{RIGHT}}{{UNIT}}; padding-right: {{LEFT}}{{UNIT}};',

                ],
            ]
        );

	    $this->start_controls_tabs( 'tabs_content_bg' );

	    $this->start_controls_tab(
		    'tab_content_bg_idle',
		    [ 'label' => esc_html__('Idle', 'transmax-core') ]
	    );

	    $this->add_control(
		    'content_bg_idle',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .tlv__content' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_content_bg_hover',
		    [ 'label' => esc_html__('Hover', 'transmax-core') ]
	    );

	    $this->add_control(
		    'content_bg_hover',
		    [
			    'label' => esc_html__('Background Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .tlv__item:hover .tlv__content' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__('Title Styles', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_title',
                'selector' => '{{WRAPPER}} .tlv__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'tablet_default' => [
                    'top' => '24',
                    'right' => '0',
                    'bottom' => '9',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_title' );

        $this->start_controls_tab(
            'tab_title_idle',
            [ 'label' => esc_html__('Idle', 'transmax-core') ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            [ 'label' => esc_html__('Hover', 'transmax-core') ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'heading_text',
            [
                'label' => esc_html__('Text Styles', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_text',
                'selector' => '{{WRAPPER}} .tlv__text',
            ]
        );

        $this->start_controls_tabs( 'tabs_content' );

        $this->start_controls_tab(
            'tab_content_idle',
            [ 'label' => esc_html__('Idle', 'transmax-core') ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_content_hover',
            [ 'label' => esc_html__('Hover', 'transmax-core') ]
        );

        $this->add_control(
            'content_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> DATE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_date',
            [
                'label' => esc_html__('Date', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'date',
                'selector' => '{{WRAPPER}} .tlv__date',
            ]
        );

        $this->add_responsive_control(
            'date_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'desktop_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '28',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'mobile_default' => [
                    'top' => '10',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'tablet_default' => [
                    'top' => '19',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'mobile_default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .tlv__date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
		    'date_divider',
		    [
			    'label' => esc_html__('Use Divider', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'separator' => 'before',
			    'default' => 'yes',
                'selectors' => [
				    '{{WRAPPER}} .tlv__date:after' => 'display:block;',
			    ],
		    ]
	    );

	    $this->add_control(
		    'date_divider_color',
		    [
			    'label' => esc_html__('Divider Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'condition' => ['date_divider' => 'yes'],
			    'selectors' => [
				    '{{WRAPPER}} .tlv__date:after' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

        $this->start_controls_tabs( 'tabs_date' );

        $this->start_controls_tab(
            'date_colors_idle',
            [ 'label' => esc_html__('Idle', 'transmax-core') ]
        );

        $this->add_control(
            'date_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__date' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_date_hover',
            [ 'label' => esc_html__('Hover', 'transmax-core') ]
        );

        $this->add_control(
            'date_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tlv__item:hover .tlv__date' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        // Allowed HTML tags
        $allowed_html = [
            'a' => [
                'href' => true, 'title' => true,
                'class' => true, 'style' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['class' => true, 'style' => true],
            'em' => ['class' => true, 'style' => true],
            'strong' => ['class' => true, 'style' => true],
            'span' => ['class' => true, 'style' => true],
            'p' => ['class' => true, 'style' => true],
            'ul' => ['class' => true, 'style' => true],
            'ol' => ['class' => true, 'style' => true],
        ];

        $this->add_render_attribute(
            'timeline-vertical',
            [
                'class' => [
                    'wgl-timeline-vertical',
                    $_s[ 'add_appear' ] ? 'appear_animation' : '',
                ],
            ]
        );

        // Render
        echo '<div ', $this->get_render_attribute_string('timeline-vertical'), '>';?>
        <div class="tlv__items-start"></div>
        <div class="tlv__items-wrapper"><?php

        foreach ($_s['items'] as $index => $item) {
            if ($index !== 0 && $index % 2 === 0){ ?>
                </div><div class="tlv__items-wrapper"><?php
            }

            $thumbnail_idle = $this->get_repeater_setting_key('thumbnail', 'list', $index);
            $url_idle = $item['thumbnail_idle']['url'] ?? false;
            $this->add_render_attribute($thumbnail_idle, [
                'class' => 'tlv__thumbnail--idle',
                'src' => $url_idle ? esc_url($url_idle) : '',
                'alt' => Control_Media::get_image_alt($item['thumbnail_idle']),
            ]);

            $thumbnail_hover = $this->get_repeater_setting_key('thumbnail_hover', 'list', $index);
            $url_hover = $item['thumbnail_hover']['url'] ?? false;
            $this->add_render_attribute($thumbnail_hover, [
                'class' => 'tlv__thumbnail--hover',
                'src' => $url_hover ? esc_url($url_hover) : '',
                'alt' => Control_Media::get_image_alt($item['thumbnail_hover']),
            ]); ?>
            <div class="tlv__item<?php echo $url_idle ? ' has_media' : '';?>">
                <div class="tlv__curve-wrapper"><span></span></div>
                <div class="tlv__volume-wrapper">
                    <?php
                    if ($url_idle) { ?>
                        <div class="tlv__media"><?php
                            if ( $url_hover ) {
                                echo '<img ', $this->get_render_attribute_string( $thumbnail_hover ), '/>';
                            }
                            echo '<img ', $this->get_render_attribute_string( $thumbnail_idle ), '/>';?>
                        </div><?php
                    } ?>
                    <div class="tlv__content-wrapper">
	                    <div class="tlv__content">
                            <?php
		                    if (!empty($item['date'])) { ?>
                                <div class="tlv__date-wrapper">
                                    <span class="tlv__date">
                                        <?php echo $item['date']; ?>
                                    </span>
                                </div>
                                <?php
		                    }
		                    if (!empty($item['title'])) { ?>
		                        <h3 class="tlv__title"><?php
		                            echo $item['title']; ?>
		                        </h3><?php
		                    }
		                    if (!empty($item['content'])) { ?>
		                        <div class="tlv__text"><?php
		                            echo wp_kses( $item['content'], $allowed_html ); ?>
		                        </div><?php
		                    } ?>
	                    </div>
                    </div>
                </div>

            </div><?php
	        if ($index !== 0 && $index % 2 === 0){
		        end($_s['items']);
		        if ($index === key($_s['items']))
			        echo '<div class="tlv__item empty"></div>';
	        }
        } ?>
        </div>
        <div class="tlv__items-end"></div><?php
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
