<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{
    Group_Control_Border,
    Widget_Base,
    Controls_Manager
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * Side Panel widget for Header CPT
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Side_panel extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-side_panel';
    }

    public function get_title()
    {
        return esc_html__('WGL Side Panel Button', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-header-side_panel';
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends() {
        return [ 'wgl-widgets' ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_side_panel_settings',
            ['label' => esc_html__('Side Panel', 'transmax-core')]
        );

        $this->add_responsive_control(
            'sp_width',
            [
                'label' => esc_html__('Button Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 30, 'max' => 200],
                    '%' => ['min' => 5, 'max' => 100],
                ],
                'default' => ['size' => 100],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'sp_height',
            [
                'label' => esc_html__('Button Height', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 30, 'max' => 250],
                    '%' => ['min' => 5, 'max' => 100],
                ],
                'default' => ['size' => 100],
                'selectors' => [
                    '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['sp_width!' => 0],
                'options' => [
                    'margin-right' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'margin' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'margin-left' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}}' => '{{VALUE}}: auto;',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .side_panel',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .side_panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'sp_color_tabs',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_color_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .side_panel' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'item_bg_idle',
            [
                'label' => esc_html__('Item Background', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .side_panel' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_color_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .side_panel' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'item_bg_hover',
            [
                'label' => esc_html__('Item Background', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}:hover .side_panel' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function render()
    {
        echo '<div class="side_panel">',
            '<div class="side_panel_inner">',
                '<a href="#" class="side_panel-toggle">',
                    '<span class="side_panel-toggle-inner">',
                        '<span></span>',
                        '<span></span>',
                        '<span></span>',
                    '</span>',
                '</a>',
            '</div>',
        '</div>';
    }
}