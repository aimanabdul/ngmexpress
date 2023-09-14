<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-carousel.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Repeater
};
use WGL_Extensions\{
    Includes\WGL_Carousel_Settings,
    Includes\WGL_Elementor_Helper
};

class WGL_Carousel extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-carousel';
    }

    public function get_title()
    {
        return esc_html__('WGL Carousel', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-carousel';
    }

    public function get_script_depends()
    {
        return ['swiper'];
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'content_general',
            ['label' => esc_html__('General' , 'transmax-core')]
        );

        $repeater = new REPEATER();

        $repeater->add_control(
            'content',
            [
                'label' => esc_html__('Content', 'transmax-core'),
                'type' => Controls_Manager::SELECT2,
                'options' => WGL_Elementor_Helper::get_instance()->get_elementor_templates(),
            ]
        );

        $this->add_control(
            'content_repeater',
            [
                'label' => esc_html__('Templates', 'transmax-core'),
                'type' => Controls_Manager::REPEATER,
                'description' => esc_html__('Slider content is a template which you can choose from Elementor library. Each template will be a slider content', 'transmax-core'),
                'fields' => $repeater->get_controls(),
                'title_field' => esc_html__('Template:', 'transmax-core') . ' {{{ content }}}'
            ]
        );

        $this->add_control(
            'slides_per_row',
            [
                'label' => esc_html__('Columns Amount', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => esc_html__('1 (one)', 'transmax-core'),
                    2 => esc_html__('2 (two)', 'transmax-core'),
                    3 => esc_html__('3 (three)', 'transmax-core'),
                    4 => esc_html__('4 (four)', 'transmax-core'),
                    5 => esc_html__('5 (five)', 'transmax-core'),
                    6 => esc_html__('6 (six)', 'transmax-core'),
                ],
                'default' => 1,
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Animation Speed', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 3000,
            ]
        );

        $this->add_control(
            'adaptive_height',
            [
                'label' => esc_html__('Adaptive Height', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this);

        $this->end_controls_section();

        $this->start_controls_section(
            'navigation_section',
            ['label' => esc_html__('Pagination | Navigation', 'transmax-core')]
        );

        WGL_Carousel_Settings::add_pagination_controls($this);

        $this->add_control(
            'pagination_navigation_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_pagination',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_navigation_controls($this);

        $this->end_controls_section();

        $this->start_controls_section(
            'section_responsive',
            ['label' => esc_html__('Responsive', 'transmax-core')]
        );

        WGL_Carousel_Settings::add_responsive_controls($this);

        $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        $content = [];
        foreach ($_s['content_repeater'] as $template) {
            array_push($content, $template['content']);
        }

        echo WGL_Carousel_Settings::init($_s, $content, true);
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
