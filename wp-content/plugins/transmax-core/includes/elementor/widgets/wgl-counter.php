<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-counter.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Icons
};

class WGL_Counter extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-counter';
    }

    public function get_title()
    {
        return esc_html__('WGL Counter', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-counter';
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
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wgl_counter_content',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        WGL_Icons::init(
            $this,
            [
                'label' => esc_html__('Counter ', 'transmax-core'),
                'output' => '',
                'section' => false,
                'default' => [
		            'media_type' => 'font',
		            'icon' => [
			            'library' => 'flaticon',
			            'value' => 'flaticon flaticon-trolley'
		            ],
	            ],
            ]
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
            'counter_title',
            [
                'label' => esc_html__('Title Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('experts​', 'transmax-core'),
            ]
        );

        $this->add_control(
            'title_block',
            [
                'label' => esc_html__('Title Full Width', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'counter_content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => esc_html__('We help you mitigate supply chain disruptions.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'start_value',
            [
                'label' => esc_html__('Start Value', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'before',
                'min' => 0,
                'step' => 10,
                'default' => 0,
            ]
        );

        $this->add_control(
            'end_value',
            [
                'label' => esc_html__('End Value', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 10,
                'default' => 200,
            ]
        );

        $this->add_control(
            'prefix',
            [
                'label' => esc_html__('Counter Prefix', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'suffix',
            [
                'label' => esc_html__('Counter Suffix', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('ex: +', 'transmax-core'),
                'default' => esc_html__('+', 'transmax-core'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Animation Speed', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'step' => 100,
                'default' => 2000,
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                    ]
                ],
                'default' => 'center',
                'prefix_class' => 'a%s',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'counter_style_section',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'counter_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'counter_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'counter_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('counter_color_tab');

        $this->start_controls_tab(
            'custom_counter_color_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'bg_counter_color',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter' => 'background-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_border',
                'label' => esc_html__('Border Type', 'transmax-core'),
                'selector' => '{{WRAPPER}} .wgl-counter',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_shadow',
                'selector' => '{{WRAPPER}} .wgl-counter',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_counter_color_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'bg_counter_color_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .wgl-counter' => 'background-color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_border_hover',
                'label' => esc_html__('Border Type', 'transmax-core'),
                'selector' => '{{WRAPPER}}:hover .wgl-counter',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_shadow_hover',
                'selector' => '{{WRAPPER}}:hover .wgl-counter',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> MEDIA
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Media', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['icon_type!' => ''],
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['icon_type' => 'font'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['icon_type' => 'font'],
                'range' => [
                    'px' => ['min' => 13, 'max' => 100],
                ],
                'default' => ['size' => 40],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '8',
                    'left' => '0',
	                'unit' => 'px',
	                'isLinked' => true
                ],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'counter_icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'media_background',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .media-wrapper',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'counter_icon_border',
                'selector' => '{{WRAPPER}} .media-wrapper'
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'counter_icon_shadow',
                'selector' => '{{WRAPPER}} .media-wrapper',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> VALUE
         */

        $this->start_controls_section(
            'value_style_section',
            [
                'label' => esc_html__('Value', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'value_offset',
            [
                'label' => esc_html__('Value Offset', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_value-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_value',
                'selector' => '{{WRAPPER}} .wgl-counter_value-wrap',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_value-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

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
            'title_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
	            'default' => [
		            'top' => '3',
		            'right' => '0',
		            'bottom' => '0',
		            'left' => '0',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_title',
                'selector' => '{{WRAPPER}} .wgl-counter_title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> CONTENT
         */

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_offset',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
	            'default' => [
		            'top' => '2',
		            'right' => '20',
		            'bottom' => '0',
		            'left' => '20',
		            'unit'  => 'px',
		            'isLinked' => false
	            ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_content',
                'selector' => '{{WRAPPER}} .wgl-counter_content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-counter_content' => 'color: {{VALUE}};',
                ],
            ]
        );

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

        $this->add_render_attribute(
            [
                'counter' => [
                    'class' => [
                        'wgl-counter',
                        $_s['title_block'] ? 'title-block' : 'title-inline',
                    ],
                ],
                'counter-wrap' => [
                    'class' => [
                        'wgl-counter_wrap',
                        $_s['layout'] ? 'wgl-layout-' . $_s['layout'] : '',
                    ],
                ],
                'counter_value' => [
                    'class' => 'wgl-counter__value',
                    'data-start-value' => $_s['start_value'],
                    'data-end-value' => $_s['end_value'],
                    'data-speed' => $_s['speed'],
                ],
            ]
        );

        // Icon/Image
        ob_start();
        if (!empty($_s['icon_type'])) {
            $icons = new WGL_Icons;
            echo $icons->build($this, $_s, []);
        }
        $counter_media = ob_get_clean();

        $_s['prefix'] = !empty($_s['prefix']) ? $_s['prefix'] : '';

        // Render
        echo '<div ', $this->get_render_attribute_string('counter'), '>';
        echo '<div ', $this->get_render_attribute_string('counter-wrap'), '>';
        if ($_s['icon_type'] != '' && $counter_media) {
            echo '<div class="media-wrap">',
                $counter_media,
                '</div>';
        }

        echo '<div class="content-wrap">';
        echo '<div class="wgl-counter_value-wrap">';

        if ($_s['prefix']) {
            echo '<span class="wgl-counter__prefix">', $_s['prefix'], '</span>';
        }
        if (!empty($_s['end_value'])) {
            echo '<div class="wgl-counter__placeholder-wrap">';
            echo '<span class="wgl-counter__placeholder">',
                $_s['end_value'],
                '</span>';

            echo '<span ', $this->get_render_attribute_string('counter_value'), '>',
                $_s['start_value'],
                '</span>';
            echo '</div>';
        }
        if (!empty($_s['suffix'])) {
            echo '<span class="wgl-counter__suffix">',
                $_s['suffix'],
                '</span>';
        }
        echo '</div>'; // wgl-counter_value-wrap

        if (!empty($_s['counter_title'])) {
            echo '<', $_s['title_tag'], ' class="wgl-counter_title">',
                $_s['counter_title'],
                '</',
                $_s['title_tag'],
                '>';
        }

        if (!empty($_s['counter_content'])) {
            echo '<div class="wgl-counter_content">',wp_kses($_s['counter_content'], $kses_allowed_html),'</div>';
        }

        echo '</div>'; // content-wrap
        echo '</div>';
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
