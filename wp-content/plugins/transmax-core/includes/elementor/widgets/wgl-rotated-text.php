<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/widgets/wgl-rotated-text.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Controls_Manager,
    Group_Control_Typography,
    Widget_Base
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class WGL_Rotated_Text extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-rotated-text';
    }

    public function get_title()
    {
        return esc_html__('WGL Rotated Text', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-rotated-text';
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
            'wgl_rotated_text_section',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default' => esc_html__('Transmax`s Founder', 'transmax-core'),
            ]
        );

        $this->add_control(
            'rt_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'rows' => 1,
                'placeholder' => esc_attr__('Arnold Black', 'transmax-core'),
                'default' => esc_html_x('Arnold Black', 'WGL Rotated Text', 'transmax-core'),
            ]
        );

	    $this->add_responsive_control(
		    'max_height',
		    [
			    'label' => esc_html__('Max Height', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 20, 'max' => 1000],
			    ],
			    'default' => ['size' => 240],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text' => 'height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'alignment',
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
                'default' => 'center',
                'prefix_class' => 'a%s',
            ]
        );

	    $this->add_responsive_control(
		    'disable_rotation',
		    [
			    'label' => esc_html__('Disable Rotation', 'transmax-core'),
			    'type' => Controls_Manager::SWITCHER,
			    'mobile_default' => 'yes',
			    'return_value' => 'yes',
			    'prefix_class' => 'disable-rotation%s-',
		    ]
	    );

	    $this->add_control(
		    'link',
		    [
			    'label' => esc_html__('Module Link', 'transmax-core'),
			    'type' => Controls_Manager::URL,
			    'placeholder' => esc_attr__('https://your-link.com', 'transmax-core'),
		    ]
	    );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> ITEM
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('Item', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

	    $this->add_responsive_control(
		    'item_margin',
		    [
			    'label' => esc_html__('Padding', 'transmax-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'default' => [
				    'top' => '25',
				    'right' => '25',
				    'bottom' => '25',
				    'left' => '25',
				    'unit'  => 'px',
				    'isLinked' => false
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> TITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['rt_title!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_all',
                'selector' => '{{WRAPPER}} .rt__title',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title',
                'condition' => ['rt_title!' => ''],
                'selector' => '{{WRAPPER}} .rt__title',
            ]
        );

	    $this->start_controls_tabs(
		    'tabs_title'
	    );

	    $this->start_controls_tab(
		    'tab_title_idle',
		    ['label' => esc_html__('Idle' , 'transmax-core')]
	    );

	    $this->add_control(
		    'title_color',
		    [
			    'label' => esc_html__('Title Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['rt_title!' => ''],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .rt__title' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'title_hover',
		    ['label' => esc_html__('Hover' , 'transmax-core')]
	    );

	    $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Title Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['rt_title!' => ''],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-rotated_text:hover .rt__title' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLES -> SUBTITLE
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_subtitle',
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
                'selector' => '{{WRAPPER}} .rt__subtitle',
            ]
        );

	    $this->add_responsive_control(
		    'subtitle_gap',
		    [
			    'label' => esc_html__('Gap', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'condition' => ['rt_title!' => ''],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 100],
			    ],
			    'default' => ['size' => 11],
			    'render_type' => 'template',
			    'selectors' => [
				    '{{WRAPPER}} .rt__subtitle' => 'margin-right: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs(
		    'tabs_subtitle'
	    );

	    $this->start_controls_tab(
		    'tab_subtitle_idle',
		    ['label' => esc_html__('Idle' , 'transmax-core')]
	    );

	    $this->add_control(
		    'subtitle_color',
		    [
			    'label' => esc_html__('Subtitle Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .rt__subtitle' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'subtitle_hover',
		    ['label' => esc_html__('Hover' , 'transmax-core')]
	    );

	    $this->add_control(
		    'subtitle_color_hover',
		    [
			    'label' => esc_html__('Subtitle Color', 'transmax-core'),
			    'type' => Controls_Manager::COLOR,
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} .wgl-rotated_text:hover .rt__subtitle' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();

	    $this->end_controls_section();
    }

    protected function render()
    {
        $_s = $this->get_settings_for_display();

	    if (isset($_s['link']['url'])) {
		    $this->add_render_attribute('link', 'class', 'rt__link');
		    $this->add_link_attributes('link', $_s['link']);
	    }

        $this->add_render_attribute('heading_wrapper', 'class', 'wgl-rotated_text'); ?>
        <div <?php echo $this->get_render_attribute_string('heading_wrapper'); ?>><?php
	        if (isset($_s['link']['url'])) echo '<a ', $this->get_render_attribute_string('link'), '></a>';

            if ($_s['rt_title']) {
                echo '<', $_s['title_tag'], ' class="rt__title-wrapper">';
                    if ($_s['rt_title']) ?><span class="rt__title"><?php echo $_s['rt_title']; ?></span><?php
                echo '</', $_s['title_tag'], '>';
            }

            if ($_s['subtitle']) { ?>
                <div class="rt__subtitle"><?php
                if ($_s['subtitle']) echo $_s['subtitle']; ?>
                </div><?php
            } ?>
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
