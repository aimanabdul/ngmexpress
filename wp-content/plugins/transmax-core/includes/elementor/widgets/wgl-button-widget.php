<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-button-widget.php`.
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
use WGL_Extensions\{WGL_Framework_Global_Variables as WGL_Globals,
	Includes\WGL_Icons,
	Templates\WGL_Button};

class WGL_Button_widget extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-button';
    }

    public function get_title()
    {
        return esc_html__('WGL Button', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-button';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public static function get_button_sizes()
    {
        return [
            'sm' => esc_html__('Small', 'transmax-core'),
            'md' => esc_html__('Medium', 'transmax-core'),
            'lg' => esc_html__('Large', 'transmax-core'),
            'xl' => esc_html__('Extra Large', 'transmax-core'),
        ];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
	            'label_block' => true,
                'placeholder' => esc_attr__('Button Text', 'transmax-core'),
                'default' => esc_html__('LEARN MORE', 'transmax-core'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_attr__('https://your-link.com', 'transmax-core'),
                'default' => ['url' => '#'],
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
                    'justify' => [
                        'title' => esc_html__('Full Width', 'transmax-core'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'prefix_class' => 'a%s',
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => esc_html__('Size', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => self::get_button_sizes(),
                'default' => 'lg',
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();

        $output['icon_align'] = [
            'label' => esc_html__('Position', 'transmax-core'),
            'type' => Controls_Manager::SELECT,
            'condition' => ['icon_type!' => ''],
            'options' => [
                'left' => esc_html__('Before', 'transmax-core'),
                'right' => esc_html__('After', 'transmax-core'),
            ],
            'default' => 'left',
        ];

        $output['icon_indent'] = [
            'label' => esc_html__('Offset', 'transmax-core'),
            'type' => Controls_Manager::SLIDER,
            'condition' => ['icon_type!' => ''],
            'range' => [
                'px' => ['max' => 50],
            ],
            'selectors' => [
                '{{WRAPPER}} .align-icon-right .media-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .align-icon-left .media-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
            ],
        ];

        WGL_Icons::init(
            $this,
            [
                'output' => $output,
                'section' => true,
            ]
        );

        /**
         * STYLE -> BUTTON
         */

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .wgl-button',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

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
                    '{{WRAPPER}} .wgl-button' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .wgl-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_idle',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['border_border!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:before' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .wgl-button',
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
                    '{{WRAPPER}} .wgl-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['border_border!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover:before' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .wgl-button:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .wgl-button',
                'render_type' => 'template',
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'button_inner_margin',
		    [
			    'label' => esc_html__('Margin Inner', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'separator' => 'before',
			    'size_units' => ['px'],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 20 ],
			    ],
			    'default' => ['size' => 0, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-button.with-border' => '--transmax-button-margin: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .wgl-button' => 'margin: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> ICON/IMAGE
         */

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon/Image', 'transmax-core'),
                'condition' => ['icon_type!' => ''],
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'media_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'allowed_dimensions' => 'vertical',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .media-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_icon',
            ['condition' => ['icon_type' => 'font']]
        );

        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-button:hover .elementor-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'icon_size',
            [
                'label' => esc_html__('Font Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['icon_type' => 'font'],
                'separator' => 'before',
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => ['max' => 80],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> ANIMATION
         */

        $this->start_controls_section(
            'section_style_animation',
            [
                'label' => esc_html__('Animation', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Button Hover', 'transmax-core'),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new WGL_Button())->render($this, $atts);
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
