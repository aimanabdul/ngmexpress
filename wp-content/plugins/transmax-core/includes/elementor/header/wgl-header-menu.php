<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager
};
use Elementor\{
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background
};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * Menu widget for Header CPT
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Menu extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-menu';
    }

    public function get_title()
    {
        return esc_html__('WGL Menu', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-header-menu';
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends()
    {
        return [
            'wgl-widgets',
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
            'template',
            [
                'label' => esc_html__('Template', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Theme Default', 'transmax-core'),
                    'custom' => esc_html__('Custom', 'transmax-core'),
                ],
                'default' => 'default',
            ]
        );

        $this->add_control(
            'menu',
            [
                'label' => esc_html__('Menu', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['template' => 'custom'],
                'options' => wgl_get_custom_menu(),
            ]
        );

        $this->add_control(
            'submenu_disable',
            [
                'label' => esc_html__('Disable Submenu', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
            ]
        );

        $this->add_control(
            'marker_disable',
            [
                'label' => esc_html__('Disable Marker', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'lavalamp_active',
            [
                'label' => esc_html__('Lavalamp Marker', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'transmax-core'),
                'label_off' => esc_html__('Off', 'transmax-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'heading_width',
            [
                'label' => esc_html__('Width', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'display',
            [
                'label' => esc_html__('Display', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inline-flex; width: auto' => esc_html__('Inline-Flex', 'transmax-core'),
                    'block' => esc_html__('Block', 'transmax-core'),
                ],
                'default' => 'inline-flex; width: auto',
                'selectors' => [
                    '{{WRAPPER}}' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'flex_grow',
            [
                'label' => esc_html__('Flex Grow', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['display' => 'inline-flex; width: auto'],
                'min' => -1,
                'max' => 20,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}}' => 'flex-grow: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_height',
            [
                'label' => esc_html__('Height', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'menu_height',
            [
                'label' => esc_html__('Module Height (px)', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'separator' => 'after',
                'min' => 30,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .primary-nav' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'alignmentt_flex',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['display' => 'inline-flex; width: auto'],
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}}' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'alignmentt_block',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['display' => 'block'],
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
                'selectors' => [
                    '{{WRAPPER}} .primary-nav' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> MENU
         */

        $this->start_controls_section(
            'section_style_menu',
            [
                'label' => esc_html__('Menu', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'items',
                'selector' => '{{WRAPPER}} .primary-nav > div > ul, {{WRAPPER}} .primary-nav > ul',
            ]
        );

        $this->add_responsive_control(
            'menu_items_padding',
            [
                'label' => esc_html__('Items Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .primary-nav > ul' => 'margin-left: -{{LEFT}}{{UNIT}}; margin-right: -{{RIGHT}}{{UNIT}}; margin-bottom: -{{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_menu');

        $this->start_controls_tab(
            'tab_menu_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'menu_text_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_icon_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a > .menu-item__plus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_marker_idle',
            [
                'label' => esc_html__('Marker Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li > a:after' => 'color: {{VALUE}}',
                ],
                'condition' => ['marker_disable' => ''],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'menu_text_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_icon_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a > .menu-item__plus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_marker_hover',
            [
                'label' => esc_html__('Marker Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li:hover > a:after' => 'color: {{VALUE}}',
                ],
                'condition' => ['marker_disable' => ''],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_menu_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            'menu_text_active',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_icon_active',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"]:not(:hover) > a > .menu-item__plus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_marker_active',
            [
                'label' => esc_html__('Marker Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav > ul > li[class*="current"] > a:after' => 'color: {{VALUE}}',
                ],
                'condition' => ['marker_disable' => ''],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> SUBMENU
         */

        $this->start_controls_section(
            'section_style_submenu',
            [
                'label' => esc_html__('Submenu', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submenu_typo',
                'selector' => '{{WRAPPER}} .primary-nav > div > ul ul, {{WRAPPER}} .primary-nav > ul ul',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submenu_bg',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .primary-nav ul li ul',
            ]
        );

        $this->start_controls_tabs(
            'tabs_submenu'
        );

        $this->start_controls_tab(
            'tab_submenu_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'submenu_text_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_icon_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul li:not(:hover) > a > .menu-item__plus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submenu_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'submenu_text_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_icon_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul li:hover > a > .menu-item__plus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_submenu_active',
            ['label' => esc_html__('Active' , 'transmax-core')]
        );

        $this->add_control(
            'submenu_text_active',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul li[class*="current"]:not(:hover) > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submenu_icon_active',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul li[class*="current"]:not(:hover) > a > .menu-item__plus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submenu_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .primary-nav ul li ul',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'submenu_shadow',
                'selector' => '{{WRAPPER}} .primary-nav ul li ul',
            ]
        );

        $this->add_responsive_control(
            'submenu_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .primary-nav ul li ul' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}} .primary-nav ul li ul a' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> LAVALAMP
         */

        $this->start_controls_section(
            'section_style_lavalamp',
            [
                'label' => esc_html__('Lavalamp', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['lavalamp_active!' => ''],
            ]
        );

        $this->add_control(
            'lavalamp_color',
            [
                'label' => esc_html__('Lavalamp Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .lavalamp-object' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        extract($settings);

        if (
            $template === 'default'
            && has_nav_menu('main_menu')
        ) {
            $menu = 'main_menu';
        }

        $nav_classes = $lavalamp_active ? ' menu_line_enable' : '';
        $nav_classes .= $submenu_disable ? ' submenu-disable' : '';
        $nav_classes .= $marker_disable ? ' marker-disable' : '';
	
	    ?><nav class="primary-nav<?php echo $nav_classes; ?>"><?php
	    if (isset($menu) && $menu) {
		    wgl_theme_main_menu( $menu, false, $submenu_disable ?: null );
	    }
	    ?></nav>
        <div class="mobile-hamburger-toggle">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div><?php
    }
}
