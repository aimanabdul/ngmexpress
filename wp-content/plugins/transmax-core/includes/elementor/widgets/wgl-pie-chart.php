<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-pie-chart.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Border
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Pie_Chart extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-pie-chart';
    }

    public function get_title()
    {
        return esc_html__('WGL Pie Chart', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-pie-chart';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [
            'jquery-easypiechart',
            'jquery-appear'
        ];
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
            'value',
            [
                'label' => esc_html__('Value', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    '%' => ['min' => 0, 'max' => 100],
                ],
                'default' => ['size' => 75, 'unit' => '%'],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'transmax-core'),
                'type' => 'wgl-radio-image',
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
                'default' => 'left',
            ]
        );

        $this->add_responsive_control(
            'align',
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
                'default' => 'left',
                'tablet_default' => 'center',
				'mobile_default' => 'center',
                'prefix_class' => 'a%s',
            ]
        );

        $this->add_control(
            'sub_title',
            [
                'label' => esc_html__('Sub Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('HAPPY', 'transmax-core'),
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('MODELING', 'transmax-core'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default' => esc_html__('Solutions minimize the cost and increase the velocity', 'transmax-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> GENERAL
         */

        $this->start_controls_section(
            'style_chart',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'chart_diameter',
            [
                'label' => esc_html__('Chart Diameter', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'label_block' => true,
                'range' => [
                    'px' => ['min' => 50, 'max' => 450],
                ],
                'default' => ['size' => 148],
            ]
        );

        $this->add_control(
            'track_color',
            [
                'label' => esc_html__('Track Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'bar_color',
            [
                'label' => esc_html__('Bar Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => WGL_Globals::get_primary_color(),
            ]
        );

        $this->add_control(
            'line_width',
            [
                'label' => esc_html__('Line Width', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 1,
                'default' => 6,
            ]
        );

        $this->add_responsive_control(
            'bar_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .chart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'bar_border',
	            'fields_options' => [
		            'border' => ['default' => 'solid'],
		            'width'  => ['default' => [
			            'top'      => '1',
			            'right'    => '1',
			            'bottom'   => '1',
			            'left'     => '1',
		            ]],
		            'color' => ['default' => '#d9d9d9'],
	            ],
	            'selector' => '{{WRAPPER}} .chart',
            ]
        );

        $this->add_control(
            'bar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .chart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .chart__percent',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__percent' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'value_bg',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => array(
                    '{{WRAPPER}} .chart__percent' => 'background-color: {{VALUE}};',
                ),
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> SUB TITLE
         */

        $this->start_controls_section(
            'style_sub_title',
            [
                'label' => esc_html__('Sub Title', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'sub_title_typography',
                'selector' => '{{WRAPPER}} .chart__sub_title',
            ]
        );

        $this->add_responsive_control(
            'sub_title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '17',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '25',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '17',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
				'mobile_default' => [
                    'top' => '17',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .chart__sub_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'sub_title_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__sub_title' => 'color: {{VALUE}};',
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
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .chart__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '-1',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '24',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '-1',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
				'mobile_default' => [
                    'top' => '-1',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .chart__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .chart__title' => 'color: {{VALUE}};',
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
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .chart__description',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .chart__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'desc_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '24',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'tablet_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
				'mobile_default' => [
                    'top' => '5',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
	                'unit'  => 'px',
	                'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .chart__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        extract($this->get_settings_for_display());

        $diameter = (int) esc_attr($chart_diameter['size']);
        $wrapper_classes = $layout ? ' wgl-layout-' . $layout : '';

        $this->add_render_attribute('chart', [
            'class' => 'chart',
            'style' => 'height: ' . $diameter . 'px;',
            'data-percent' => (int) esc_attr($value['size']),
            'data-track-color' => esc_attr($track_color),
            'data-bar-color' => esc_attr($bar_color),
            'data-line-width' => (int) esc_attr($line_width),
            'data-size' => $diameter,
        ]);

        echo '<div class="wgl-pie_chart">';
        echo '<div class="chart__wrapper', esc_attr($wrapper_classes), '">';

            echo '<div ', $this->get_render_attribute_string('chart'), '>',
                '<span class="chart__percent">0</span>',
            '</div>';

            echo '<div class="chart__content content_wrapper">';

            if ($sub_title) {
                echo '<span class="chart__sub_title">',
                    $sub_title,
                '</span>';
            }

            if ($title) {
                echo '<span class="chart__title">',
                    $title,
                '</span>';
            }

            if ($description) {
                echo '<span class="chart__description">',
                    $description,
                '</span>';
            }

            echo '</div>';

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
