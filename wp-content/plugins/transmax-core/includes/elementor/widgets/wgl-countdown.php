<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-countdown.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
	Widget_Base,
	Controls_Manager,
	Group_Control_Typography
};
use WGL_Extensions\{
	WGL_Framework_Global_Variables as WGL_Globals,
	Templates\WGLCountDown
};

class WGL_CountDown extends Widget_Base
{
	public function get_name()
	{
		return 'wgl-countdown';
	}

	public function get_title()
	{
		return esc_html__('WGL Countdown Timer', 'transmax-core');
	}

	public function get_icon()
	{
		return 'wgl-countdown';
	}

	public function get_categories()
	{
		return ['wgl-modules'];
	}

	public function get_script_depends()
	{
		return [
			'jquery-countdown',
			'wgl-widgets',
		];
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
			'h_tip',
			[
				'label' => esc_html__('Choose the specific date:', 'transmax-core'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'countdown_year',
			[
				'label' => esc_html__('Year', 'transmax-core'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Example: 2021', 'transmax-core'),
				'default' => esc_html__('2021', 'transmax-core'),
			]
		);

		$this->add_control(
			'countdown_month',
			[
				'label' => esc_html__('Month', 'transmax-core'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Example: 12', 'transmax-core'),
				'default' => esc_html__('12', 'transmax-core'),
			]
		);

		$this->add_control(
			'countdown_day',
			[
				'label' => esc_html__('Day', 'transmax-core'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Example: 31', 'transmax-core'),
				'default' => esc_html__('31', 'transmax-core'),
			]
		);

		$this->add_control(
			'countdown_hours',
			[
				'label' => esc_html__('Hours', 'transmax-core'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Example: 24', 'transmax-core'),
				'default' => esc_html__('24', 'transmax-core'),
			]
		);

		$this->add_control(
			'countdown_min',
			[
				'label' => esc_html__('Minutes', 'transmax-core'),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__('Example: 59', 'transmax-core'),
				'default' => esc_html__('59', 'transmax-core'),
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
					],
					'justify' => [
						'title' => esc_html__('Full Width', 'transmax-core'),
						'icon' => 'fa fa-align-justify',
					],
				],
				'desktop_default' => 'left',
				'tablet_default'  => 'center',
				'mobile_default'  => 'center',
				'prefix_class' => 'a%s',
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  CONTENT -> CONTENT
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_content_content',
			[ 'label' => esc_html__('Content', 'transmax-core') ]
		);

		$this->add_control(
			'show_value_names',
			[
				'label' => esc_html__('Show Title?', 'transmax-core'),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'show_title_',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_separating',
			[
				'label' => esc_html__('Show Separating Dots?', 'transmax-core'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
                	 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'visibility: visible;'
				]
			]
		);

		$this->add_control(
			'hide_day',
			[
				'label' => esc_html__('Hide Days?', 'transmax-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_hours',
			[
				'label' => esc_html__('Hide Hours?', 'transmax-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_minutes',
			[
				'label' => esc_html__('Hide Minutes?', 'transmax-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'hide_seconds',
			[
				'label' => esc_html__('Hide Seconds?', 'transmax-core'),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> NUMBERS
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'countdown_style_numbers',
			[
				'label' => esc_html__('Numbers', 'transmax-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'transmax-core'),
				'name' => 'custom_fonts_number',
				'selector' => '{{WRAPPER}} .wgl-countdown .countdown-amount',
			]
		);

		$this->add_control(
			'number_color_idle',
			[
				'label' => esc_html__('Text Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'number_bg_idle',
			[
				'label' => esc_html__('Background Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_width',
			[
				'label' => esc_html__('Min Width', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 400,
					],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 180,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 140,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 72,
					'unit' => 'px',
				],
				'condition' => ['alignment!' => 'justify'],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'min-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'number_padding',
			[
				'label' => esc_html__('Padding', 'transmax-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => ['px'],
				'desktop_default' => [
					'top' => '0',
					'right' => '55',
					'bottom' => '14',
					'left' => '55',
					'unit' => 'px',
					'isLinked' => false
				],
				'tablet_default'  => [
					'top' => '0',
					'right' => '19',
					'bottom' => '14',
					'left' => '19',
					'unit' => 'px',
					'isLinked' => false
				],
				'mobile_default'  => [
					'top' => '0',
					'right' => '10',
					'bottom' => '12',
					'left' => '10',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> TITLES
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_titles',
			[
				'label' => esc_html__('Titles', 'transmax-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography', 'transmax-core'),
				'name' => 'custom_fonts_titles',
				'selector' => '{{WRAPPER}} .wgl-countdown .countdown-period',
			]
		);

		$this->add_responsive_control(
			'titles_padding',
			[
				'label' => esc_html__('Padding', 'transmax-core'),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => ['px', '%'],
				'desktop_default' => [
					'top' => '2',
					'right' => '0',
					'bottom' => '0',
					'left' => '55',
					'unit' => 'px',
					'isLinked' => false
				],
				'tablet_default'  => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false
				],
				'mobile_default'  => [
					'top' => '0',
					'right' => '0',
					'bottom' => '0',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-period' => 'padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'titles_color_idle',
			[
				'label' => esc_html__('Text Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wgl-countdown .countdown-period' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  STYLE -> DOTS
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_dots',
			[
				'label' => esc_html__('Dots', 'transmax-core'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['show_separating!' => ''],
			]
		);

		$this->add_control(
			'dots_color_idle',
			[
				'label' => esc_html__('Dots Color', 'transmax-core'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
					 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_shape',
			[
				'label' => esc_html__('Dots Shape', 'transmax-core'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'font' => esc_html__('Current Font', 'transmax-core'),
					'circle' => esc_html__('Circle', 'transmax-core'),
					'square' => esc_html__('Rhombus', 'transmax-core'),
					'rectangle' => esc_html__('Rectangle', 'transmax-core'),
				],
				'default' => 'circle',
				'prefix_class' => 'dots_style-',
			]
		);


		$this->add_responsive_control(
			'dots_size',
			[
				'label' => esc_html__('Dots Size', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 30,
					],
				],
				'condition' => ['dots_shape!' => ['font', 'rectangle']],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 12,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
					 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_width',
			[
				'label' => esc_html__('Dots Width', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => ['min' => 1, 'max' => 30 ]
				],
				'condition' => ['dots_shape' => 'rectangle'],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 4,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
					 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_height',
			[
				'label' => esc_html__('Dots Height', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => ['min' => 1, 'max' => 30 ]
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 5,
					'unit' => 'px',
				],
				'condition' => ['dots_shape' => 'rectangle'],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
					 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_interval',
			[
				'label' => esc_html__('Dots Interval', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => ['dots_shape!' => 'font'],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_v_position',
			[
				'label' => esc_html__('Dots Vertical Position', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [ 'min' => 0, 'max' => 100 ],
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'condition' => ['dots_shape!' => 'font'],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 32,
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => 32,
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => 25,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
					 {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$atts = $this->get_settings_for_display();

		$countdown = new WGLCountDown();
		$countdown->render($this, $atts);
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
