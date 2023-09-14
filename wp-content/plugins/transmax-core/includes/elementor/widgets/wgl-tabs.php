<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-tabs.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Frontend,
    Widget_Base,
    Utils,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Control_Media,
    Group_Control_Image_Size,
    Icons_Manager,
    Repeater
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Elementor_Helper
};

class WGL_Tabs extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-tabs';
    }

    public function get_title()
    {
        return esc_html__('WGL Tabs', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-tabs';
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

        $this->add_responsive_control(
            'tabs_tab_align',
            [
                'label' => esc_html__('Title Alignment', 'transmax-core'),
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'transmax-core'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'left',
                'render_type' => 'template',
            ]
        );

	    $this->add_control(
		    'tabs_tab_oversize',
		    [
			    'label' => esc_html__('Oversize Tabs', 'transmax-core'),
			    'description' => esc_html__('Tabs will no longer migrate', 'transmax-core'),
//			    'condition' => [
//				    'tabs_tab_align!' => 'justify',
//			    ],
			    'type' => Controls_Manager::SWITCHER,
			    'default' => 'yes',
			    'return_value' => 'yes',
			    'prefix_class' => 'tabs_oversize-',
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

        $repeater = new Repeater();
        $repeater->add_control(
			'tabs_tab_title',
			[
                'label' => esc_html__('Tab Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'transmax-core'),
                'dynamic' => ['active' => true],
			]
        );

        $repeater->add_control(
			'tabs_tab_icon_type',
			[
                'label'             => esc_html__('Add Icon/Image', 'transmax-core'),
                'type'              => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options'           => [
                    ''              => [
                        'title' => esc_html__('None', 'transmax-core'),
                        'icon' => 'fa fa-ban',
                    ],
                    'font'          => [
                        'title' => esc_html__('Icon', 'transmax-core'),
                        'icon' => 'far fa-smile',
                    ],
                    'image'         => [
                        'title' => esc_html__('Image', 'transmax-core'),
                        'icon' => 'far fa-image',
                    ]
                ],
                'default'           => '',
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_fontawesome',
			[
                'label'       => esc_html__( 'Icon', 'transmax-core' ),
                'type'        => Controls_Manager::ICONS,
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_type'  => 'font',
                ],
                'description' => esc_html__( 'Select icon from Fontawesome library.', 'transmax-core' ),
			]
        );
        $repeater->add_control(
			'tabs_tab_icon_thumbnail',
			[
                'label'       => esc_html__( 'Image', 'transmax-core' ),
                'type'        => Controls_Manager::MEDIA,
                'label_block' => true,
                'condition'     => [
                    'tabs_tab_icon_type'   => 'image',
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
        );
        $repeater->add_control(
			'tabs_content_type',
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
			'tabs_content_templates',
			[
                'label' => esc_html__('Choose Template', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
                'condition' => [
                    'tabs_content_type' => 'template',
                ],
			]
        );
        $repeater->add_control(
			'tabs_content',
			[
                'label' => esc_html__('Tab Content', 'transmax-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'transmax-core'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'tabs_content_type' => 'content',
                ],
			]
        );

        $this->add_control(
            'tabs_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['tabs_tab_title' => esc_html__('Tab Title 1', 'transmax-core')],
                    ['tabs_tab_title' => esc_html__('Tab Title 2', 'transmax-core')],
                    ['tabs_tab_title' => esc_html__('Tab Title 3', 'transmax-core')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{tabs_tab_title}}',
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
                'name' => 'tabs_title_typo',
                'selector' => '{{WRAPPER}} .wgl-tabs_title',
            ]
        );

        $this->add_control(
            'tabs_title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'transmax-core'),
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
                    'top' => '16',
                    'right' => '40',
                    'bottom' => '17',
                    'left' => '40',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '5',
                    'bottom' => '0',
                    'left' => '5',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_title_fill_bg_animate',
            [
                'label' => esc_html__('Fill Background Animation', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->start_controls_tabs('tabs_header_tabs');

        $this->start_controls_tab(
            'tabs_header_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_bg_color_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            't_title_border_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_title_border',
                'selector' => '{{WRAPPER}} .wgl-tabs_header',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_header_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            't_title_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_bg_color_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
	            'condition' => ['tabs_title_fill_bg_animate!' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_line_color_hover',
            [
                'label' => esc_html__('Fill Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['tabs_title_fill_bg_animate' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            't_title_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 't_title_border_hover',
                'selector' => '{{WRAPPER}} .wgl-tabs_header:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            't_header_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            't_title_color_active',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_bg_color_active',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
	            'condition' => ['tabs_title_fill_bg_animate!' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            't_title_line_color_active',
            [
                'label' => esc_html__('Fill Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['tabs_title_fill_bg_animate' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            't_title_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 't_title_border_active',
                'selector' => '{{WRAPPER}} .wgl-tabs_header.active',
	            'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> ICON
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'tabs_icon_size',
            [
                'label' => esc_html__('Icon Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 26,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon:not(.wgl-tabs_icon-image)' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_icon_position',
            [
                'label' => esc_html__('Icon/Image Position', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'transmax-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'left' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'eicon-h-align-left',
                    ]
                ],
                'default' => 'top',
                'render_type' => 'template',
            ]
        );

        $this->add_responsive_control(
            'tabs_icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_icon_tabs');

        $this->start_controls_tab(
            'tabs_icon_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'tabs_icon_color',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_icon_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'tabs_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header:hover .wgl-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-tabs_header:hover .wgl-tabs_icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tabs_icon_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            'tabs_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_header.active .wgl-tabs_icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wgl-tabs_header.active .wgl-tabs_icon svg' => 'fill: {{VALUE}};',
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
            'section_style_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tabs_content_typo',
                'selector' => '{{WRAPPER}} .wgl-tabs_content',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '27',
                    'right' => '0',
                    'bottom' => '15',
                    'left' => '0',
                    'unit'  => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'tabs_content_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_content_color',
            [
                'label' => esc_html__('Content Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_content_bg_color',
            [
                'label' => esc_html__('Content Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tabs_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-tabs_content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tabs_content_border',
                'selector' => '{{WRAPPER}} .wgl-tabs_content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $_s = $this->get_settings_for_display();
        $id_int = substr( $this->get_id_int(), 0, 3 );

        $this->add_render_attribute(
            'tabs',
            [
                'class' => [
                    'wgl-tabs',
                    ( $_s['tabs_tab_oversize'] === 'yes' ? 'wgl-tabs_swiper-wrapper' : '' ),
	                (!empty($_s[ 'tabs_title_fill_bg_animate' ]) ? 'fill-bg-animation' : ''),
                    (!empty($_s['tabs_icon_position']) ? 'icon_position-'.$_s['tabs_icon_position'] : ''),
                    (!empty($_s['tabs_tab_align']) ? 'tabs_align-'.$_s['tabs_tab_align'] : ''),
                    (!empty($_s['tabs_tab_align_tablet']) ? 'tabs_align-tablet-'.$_s['tabs_tab_align_tablet'] : ''),
                    (!empty($_s['tabs_tab_align_mobile']) ? 'tabs_align-mobile-'.$_s['tabs_tab_align_mobile'] : ''),
                    (!empty($_s['tabs_icon_position']) ? 'icon_position-'.$_s['tabs_icon_position'] : ''),
                    (!empty($_s['tabs_icon_position_tablet']) ? 'icon_position-tablet-'.$_s['tabs_icon_position_tablet'] : ''),
                    (!empty($_s['tabs_icon_position_mobile']) ? 'icon_position-mobile-'.$_s['tabs_icon_position_mobile'] : ''),
                ],
            ]
        );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'tabs' ); ?>>

            <div class="wgl-tabs_headings<?php echo($_s['tabs_tab_oversize'] === 'yes' ? ' swiper-wrapper' : '') ?>"><?php

                foreach ( $_s[ 'tabs_tab' ] as $index => $item ) :

                    $tab_count = $index + 1;
                    $tab_title_key = $this->get_repeater_setting_key( 'tabs_tab_title', 'tabs_tab', $index );
                    $this->add_render_attribute(
                        $tab_title_key,
                        [
                            'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
                            'class' => [
                                'wgl-tabs_header',
                                $_s['tabs_tab_oversize'] === 'yes' ? 'swiper-slide' : '',
                            ],
                        ]
                    );

                    ?>
                    <<?php echo $_s[ 'tabs_title_tag' ]; ?> <?php echo $this->get_render_attribute_string( $tab_title_key ); ?>>
                        <span class="wgl-tabs_title"><?php echo $item[ 'tabs_tab_title' ] ?></span>

                        <?php
                        // Tab Icon/image
                        if ( $item[ 'tabs_tab_icon_type' ] != '' ) {
                            if ( $item[ 'tabs_tab_icon_type' ] == 'font' && (!empty( $item[ 'tabs_tab_icon_fontawesome' ] )) ) {

                                $icon_font = $item[ 'tabs_tab_icon_fontawesome' ];
                                $icon_out = '';
                                // add icon migration
                                $migrated = isset( $item['__fa4_migrated'][$item[ 'tabs_tab_icon_fontawesome' ]] );
                                $is_new = Icons_Manager::is_migration_allowed();
                                if ( $is_new || $migrated ) {
                                    ob_start();
                                    Icons_Manager::render_icon( $item[ 'tabs_tab_icon_fontawesome' ], [ 'aria-hidden' => 'true' ] );
                                    $icon_out .= ob_get_clean();
                                } else {
                                    $icon_out .= '<i class="icon '.esc_attr($icon_font).'"></i>';
                                }

                                ?>
                                <span class="wgl-tabs_icon">
                                    <?php
                                        echo $icon_out;
                                    ?>
                                </span>
                                <?php
                             }
                            if ($item['tabs_tab_icon_type'] == 'image' && !empty($item['tabs_tab_icon_thumbnail'])) {
                                if (!empty($item['tabs_tab_icon_thumbnail']['url'])) {
                                    $this->add_render_attribute('thumbnail', 'src', $item['tabs_tab_icon_thumbnail']['url']);
                                    $this->add_render_attribute('thumbnail', 'alt', Control_Media::get_image_alt($item['tabs_tab_icon_thumbnail']));
                                    $this->add_render_attribute('thumbnail', 'title', Control_Media::get_image_title($item['tabs_tab_icon_thumbnail']));
                                    ?>
                                    <span class="wgl-tabs_icon wgl-tabs_icon-image">
                                    <?php
                                        echo Group_Control_Image_Size::get_attachment_image_html($item, 'thumbnail', 'tabs_tab_icon_thumbnail');
                                    ?>
                                    </span>
                                    <?php
                                }
                            }
                        }
                        // End Tab Icon/image
                        ?>

                    </<?php echo $_s[ 'tabs_title_tag' ]; ?>>

                <?php endforeach;?>
            </div>

            <div class="wgl-tabs_content-wrap"><?php
                foreach ( $_s[ 'tabs_tab' ] as $index => $item ) :

                    $tab_count = $index + 1;
                    $tab_content_key = $this->get_repeater_setting_key( 'tab_content', 'tabs_tab', $index );
                    $this->add_render_attribute(
                        $tab_content_key,
                        [
                            'data-tab-id' => 'wgl-tab_' . $id_int . $tab_count,
                            'class' => [ 'wgl-tabs_content' ],
                        ]
                    );

                    ?>
                    <div <?php echo $this->get_render_attribute_string( $tab_content_key ); ?>>
                    <?php
                        if ( $item[ 'tabs_content_type' ] == 'content' ) {
                            echo do_shortcode( $item[ 'tabs_content' ] );
                        } else if ( $item[ 'tabs_content_type' ] == 'template' ) {
                            $id = $item[ 'tabs_content_templates' ];
                            $wgl_frontend = new Frontend;
                            echo $wgl_frontend->get_builder_content_for_display( $id, false );
                        }
                    ?>
                    </div>

                <?php endforeach; ?>
            </div>

        </div>
        <?php

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