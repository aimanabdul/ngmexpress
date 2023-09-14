<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-double-headings.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Box_Shadow
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Double_Heading extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-double-heading';
    }

    public function get_title()
    {
        return esc_html__('WGL Double Heading', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-double-heading';
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
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('ex: About Us', 'transmax-core'),
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_part-1',
            [
                'label' => esc_html__('1st Part', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('1st part', 'transmax-core'),
                'default' => esc_html__('Explore Our', 'transmax-core'),
            ]
        );

        $this->add_control(
            'title_part-2',
            [
                'label' => esc_html__('2nd Part', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('2nd part', 'transmax-core'),
                'default' => esc_html__(' Services', 'transmax-core'),
            ]
        );

        $this->add_control(
            'title_part-3',
            [
                'label' => esc_html__('3rd Part', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('3rd part', 'transmax-core'),
            ]
        );

        $this->add_control(
            'divider',
            [
                'label' => esc_html__('Divider', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'render_type' => 'template',
                'prefix_class' => 'divider_',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                'prefix_class' => 'a%s',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Title Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_attr__('https://your-link.com', 'transmax-core'),
            ]
        );

        $this->end_controls_section();

        /**
         * STYLES -> SUBTITLE
         */

        $this->start_controls_section(
            'style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['subtitle!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typo',
                'selector' => '{{WRAPPER}} .dblh__subtitle',
            ]
        );

        $this->add_control(
            'sub_title_tag',
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
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'div',
            ]
        );

        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'subtitle_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'subtitle_bg_color',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__subtitle span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'subtitle_shadow',
                'selector' => '{{WRAPPER}} .dblh__subtitle span',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLES -> TITLE
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
                'name' => 'title_all',
                'selector' => '{{WRAPPER}} .dblh__title-wrapper',
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
                    'span' => esc_html('‹span›'),
                    'div' => esc_html('‹div›'),
                ],
                'default' => 'h3',
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_1st_heading',
            [
                'label' => esc_html__('1st Part', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['title_part-1!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_first',
                'condition' => ['title_part-1!' => ''],
                'selector' => '{{WRAPPER}} .dblh__title-1',
            ]
        );

        $this->add_control(
            'title_1st_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['title_part-1!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-1' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_2nd_heading',
            [
                'label' => esc_html__('2nd Part', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['title_part-2!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_second',
                'condition' => ['title_part-2!' => ''],
                'selector' => '{{WRAPPER}} .dblh__title-2',
            ]
        );

        $this->add_control(
            'title_2nd_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['title_part-2!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_3rd_heading',
            [
                'label' => esc_html__('3rd Part', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['title_part-3!' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_third',
                'condition' => ['title_part-3!' => ''],
                'selector' => '{{WRAPPER}} .dblh__title-3',
            ]
        );

        $this->add_control(
            'title_3rd_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['title_part-3!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .dblh__title-3' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->end_controls_section();

	    /**
	     * STYLES -> DIVIDER
	     */

	    $this->start_controls_section(
		    'style_divider',
		    [
			    'label' => esc_html__('Divider', 'transmax-core'),
			    'tab' => Controls_Manager::TAB_STYLE,
			    'condition' => ['divider' => 'yes'],
		    ]
	    );

	    $this->add_control(
		    'divider_color',
		    [
			    'label' => esc_html__('Divider Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .dblh__divider' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

        echo '<div class="wgl-double-heading">';

        if ($_s['subtitle']) {
            echo '<', $_s['sub_title_tag'], ' class="dblh__subtitle">',
                '<span>',
                    $_s['subtitle'],
                '</span>',
            '</', $_s['sub_title_tag'], '>';
        }

        if (
            $_s['title_part-1']
            || $_s['title_part-2']
            || $_s['title_part-3']
        ) {

            if (!empty($_s['link']['url'])) {
                $this->add_render_attribute('link', 'class', 'dbl__link');
                $this->add_link_attributes('link', $_s['link']);

                echo '<a ', $this->get_render_attribute_string('link'), '>';
            }

            echo '<', $_s['title_tag'], ' class="dblh__title-wrapper">',
                ($_s['divider'] && $_s['alignment'] != 'right') ? '<span class="dblh__divider"></span>' : '',
                $_s['title_part-1'] ? '<span class="dblh__title dblh__title-1">' . $_s['title_part-1'] . '</span>' : '',
                $_s['title_part-2'] ? '<span class="dblh__title dblh__title-2">' . $_s['title_part-2'] . '</span>' : '',
                $_s['title_part-3'] ? '<span class="dblh__title dblh__title-3">' . $_s['title_part-3'] . '</span>' : '',
                ($_s['divider'] && $_s['alignment'] == 'right') ? '<span class="dblh__divider"></span>' : '',
            '</', $_s['title_tag'], '>';

            if (!empty($_s['link']['url'])) {
                echo '</a>';
            }
        }
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
