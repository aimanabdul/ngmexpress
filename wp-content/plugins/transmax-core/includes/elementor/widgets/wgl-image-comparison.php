<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-image-comparison.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Utils
};

class WGL_Image_Comparison extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-image-comparison';
    }

    public function get_title()
    {
        return esc_html__('WGL Image Comparison', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-image-comparison';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return ['cocoen'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'wgl_image_comparison_section',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'before_image',
            [
                'label' => esc_html__('Before Image', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => esc_html__('After Image', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> SLIDER BAR STYLES
         */

        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__('Slider Bar Styles', 'transmax-core'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
        );

        $this->add_control(
			'slider',
			[
				'label' => esc_html__('Slider Bar', 'transmax-core'),
				'type' => Controls_Manager::HEADING,
			]
        );

        $this->add_control(
			'slider_color',
			[
				'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .cocoen-drag:before, {{WRAPPER}} .cocoen-drag:after' => 'color: {{VALUE}};',
				],
			]
        );

        $this->add_control(
			'slider_bg',
			[
				'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
				'selectors' => [
					'{{WRAPPER}} .cocoen-drag, {{WRAPPER}} .cocoen-drag:before, {{WRAPPER}} .cocoen-drag:after' => 'background: {{VALUE}};',
				],
			]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('image_comp_wrapper', 'class', [
            'wgl-image_comparison',
            'cocoen'
        ]);

        $this->add_render_attribute('before_image', [
            'class' => [
                'comp-image_before',
                'comp-image'
            ],
            'src' => isset($settings['before_image']['url']) ? esc_url($settings['before_image']['url']) : '',
            'alt' => Control_Media::get_image_alt($settings['before_image']),
        ]);

        $this->add_render_attribute('after_image', [
            'class' => [
                'comp-image_after',
                'comp-image'
            ],
            'src' => isset($settings['after_image']['url']) ? esc_url($settings['after_image']['url']) : '',
            'alt' => Control_Media::get_image_alt($settings['after_image']),
        ]);

        ?><div <?php echo $this->get_render_attribute_string('image_comp_wrapper'); ?>>
            <img <?php echo $this->get_render_attribute_string('before_image'); ?> />
            <img <?php echo $this->get_render_attribute_string('after_image'); ?> />
        </div><?php
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