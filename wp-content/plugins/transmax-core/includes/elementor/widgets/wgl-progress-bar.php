<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-progress-bar.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Progress_Bar extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-progress-bar';
    }

    public function get_title()
    {
        return esc_html__('WGL Progress Bar', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-progress-bar';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['jquery-appear', 'wgl-widgets'];
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
            'progress_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('ex: QUALITY', 'transmax-core'),
                'default' => esc_html__('EXPERIENCE', 'transmax-core'),
            ]
        );

        $this->add_control(
            'value',
            [
                'label' => esc_html__('Value', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['size' => 50, 'unit' => '%'],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'units',
            [
                'label' => esc_html__('Units', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_attr__('ex: %, px, points, etc.', 'transmax-core'),
                'default' => esc_html__('%', 'transmax-core'),
            ]
        );

        $this->add_control(
            'value_position',
            [
                'label' => esc_html__('Value Position', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    'fixed' => esc_html__('Fixed', 'transmax-core'),
                    'dynamic' => esc_html__('Dynamic', 'transmax-core'),
                ],
                'default' => 'fixed',
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
                'name' => 'progress_label_typography',
                'selector' => '{{WRAPPER}} .progress__content',
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
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .content__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .content__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> VALUE
         */

        $this->start_controls_section(
            'style_value',
            [
                'label' => esc_html__('Value', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'progress_value_typo',
                'selector' => '{{WRAPPER}} .content__value',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'value_bg',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .content__value:after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'value_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'value_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '4',
                    'right' => '5',
                    'bottom' => '4',
                    'left' => '6',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'value_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .content__value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> PROGRESS BAR
         */

        $this->start_controls_section(
            'style_bar',
            [
                'label' => esc_html__('Bar', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bar_height_filled',
            [
                'label' => esc_html__('Filled Bar Height', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => ['min' => 1, 'max' => 30],
                ],
                'default' => ['size' => 5],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar .bar__filled' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_gap',
            [
                'label' => esc_html__('Bar gap', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => [ 'min' => -20, 'max' => 20 ],
                ],
                'default' => [ 'size' => 11 ],
                'selectors' => [
                    '{{WRAPPER}} .bar__filled' => 'transform: translateY(calc( -1 * {{SIZE}}{{UNIT}}));',
                ],
            ]
        );

        $this->add_control(
            'bar_height_empty',
            [
                'label' => esc_html__('Empty Bar Height', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => ['min' => 1, 'max' => 30],
                ],
                'default' => ['size' => 1],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_bg_empty',
            [
                'label' => esc_html__('Empty Bar Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#d9d9d9',
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bar_color_filled',
            [
                'label' => esc_html__('Filled Bar Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .bar__filled' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '16',
                    'right' => '0',
                    'bottom' => '10',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bar_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .progress__bar,
                     {{WRAPPER}} .bar__filled' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bar_box_shadow',
                'selector' => '{{WRAPPER}} .progress__bar',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .progress__bar',
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $_s = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', [
            'wgl-progress-bar',
            $_s['value_position'] == 'dynamic' ? 'dynamic-value' : '',
        ]);

        $this->add_render_attribute('bar-filled', [
            'class' => 'bar__filled',
            'data-width' => esc_attr((int) $_s['value']['size']),
        ]);

        echo '<div ', $this->get_render_attribute_string('wrapper'), '>';
        echo '<div class="progress__wrapper">';

            echo '<div class="progress__content">';
                if (!empty($_s['progress_title'])) {
                    echo '<', esc_attr($_s['title_tag']), ' class="content__label">',
                        esc_html($_s['progress_title']),
                    '</', esc_attr($_s['title_tag']), '>';
                }
                echo '<div class="content__value">';
                    echo '<span class="value__digit">0</span>';
                    if (!empty($_s['units'])) {
                        echo '<span class="value__unit">',
                            esc_html($_s['units']),
                        '</span>';
                    }
                echo '</div>';
            echo '</div>';

            echo '<div class="progress__bar">',
                '<div ', $this->get_render_attribute_string('bar-filled'), '></div>',
            '</div>';

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
