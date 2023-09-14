<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-team.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Css_Filter,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow
};
use WGL_Extensions\{
    WGL_Framework_Global_Variables as WGL_Globals,
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\WGL_Team as Team_Template
};

class WGL_Team extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-team';
    }

    public function get_title()
    {
        return esc_html__('WGL Team', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-team';
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
            'posts_per_row',
            [
                'label' => esc_html__('Columns Amount', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1 (one)', 'transmax-core'),
                    '2' => esc_html__('2 (two)', 'transmax-core'),
                    '3' => esc_html__('3 (three)', 'transmax-core'),
                    '4' => esc_html__('4 (four)', 'transmax-core'),
                    '5' => esc_html__('5 (five)', 'transmax-core'),
                    '6' => esc_html__('6 (six)', 'transmax-core'),
                ],
                'default' => '3',
            ]
        );

        $this->add_control(
            'posts_gap',
            [
                'label' => esc_html__('Columns Gap', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['posts_per_row!' => '1'],
                'min' => 0,
                'default' => 30,
                'selectors' => [
                    '{{WRAPPER}} .wgl_module_team' => '--transmax-team-grid-gap: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
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
                'prefix_class' => 'a',
                'default' => 'left',
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Images Size', 'transmax-core'),
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'transmax-core'),
                    '300' => esc_html__('300x300 - Medium', 'transmax-core'),
                    '768' => esc_html__('768x768 - Medium Large', 'transmax-core'),
                    '1024' => esc_html__('1024x1024 - 1 Column', 'transmax-core'),
                    '800' => esc_html__('800x800 - 2 Columns', 'transmax-core'),
                    '700x790' => esc_html__('700x790 - 3 Columns', 'transmax-core'),  // ratio = 1
                    'full' => esc_html__('Full', 'transmax-core'),
                    'custom' => esc_html__('Custom', 'transmax-core'),
                ],
                'default' => '700x790',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'transmax-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => ['img_size_string' => 'custom'],
                'description' => esc_html__('You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'transmax-core'),
                'default' => [
                    'width' => '700',
                    'height' => '790',
                ]
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Aspect Ratio', 'transmax-core'),
                'options' => [
                    '' => esc_html__('No Crop', 'transmax-core'),
                    '1:1' => esc_html('1:1'),
                    '3:2' => esc_html('3:2'),
                    '4:3' => esc_html('4:3'),
                    '6:5' => esc_html('6:5'),
                    '9:16' => esc_html('9:16'),
                    '16:9' => esc_html('16:9'),
                    '21:9' => esc_html('21:9'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'thumbnail_linked',
            [
                'label' => esc_html__('Add Link on Image', 'transmax-core'),
                'separator' => 'before',
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'heading_linked',
            [
                'label' => esc_html__('Add Link on Heading', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> APPEARANCE
         */

        $this->start_controls_section(
            'content_appearance',
            ['label' => esc_html__('Appearance', 'transmax-core')]
        );

        $this->add_control(
            'hide_title',
            [
                'label' => esc_html__('Hide Title', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_highlited_info',
            [
                'label' => esc_html__('Hide Highlighted Info', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_socials',
            [
                'label' => esc_html__('Hide Social Icons', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_content',
            [
                'label' => esc_html__('Hide Excerpt|Content', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'content_limit',
            [
                'label' => esc_html__('Excerpt|Content Characters Amount', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_content!' => 'yes'],
                'label_block' => true,
                'min' => 5,
                'default' => '100',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

	    WGL_Carousel_Settings::add_controls(
            $this,
            [
                'pagination_margin' => [
                    'default' => [
                        'size' => -25
                    ],
                ],
            ]
        );

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls(
            $this,
            [
                'post_type' => 'team',
                'hide_cats' => true,
                'hide_tags' => true
            ]
        );

        /**
         * STYLE -> ITEM CONTAINERS
         */

        $this->start_controls_section(
            'style_item_containers',
            [
                'label' => esc_html__('Item Containers', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_box_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}} .wgl_module_team .team__member' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'item_box_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_box_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'item_box',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'item_box_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__wrapper, {{WRAPPER}} .member__thumbnail-mark:before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_idle',
                'selector' => '{{WRAPPER}} .member__wrapper',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'item_box_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_box_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__wrapper:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_hover',
                'selector' => '{{WRAPPER}} .member__wrapper:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> IMAGE
         */

        $this->start_controls_section(
            'style_image',
            [
                'label' => esc_html__('Image', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__media' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail,
                     {{WRAPPER}} .member__thumbnail img,
                     {{WRAPPER}} .member__thumbnail:before,
                     {{WRAPPER}} .member__thumbnail:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_heading',
            [
                'label' => esc_html__('Overlays', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('overlay');

        $this->start_controls_tab(
            'overlay_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__thumbnail:before',
            ]
        );

        $this->add_control(
            'overlay_blend_idle',
            [
                'label' => esc_html__('Blend Mode', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Disabled', 'transmax-core'),
                    'multiply' => esc_html__('Multiply', 'transmax-core'),
                    'screen' => esc_html__('Screen', 'transmax-core'),
                    'overlay' => esc_html__('Overlay', 'transmax-core'),
                    'darken' => esc_html__('Darken', 'transmax-core'),
                    'lighten' => esc_html__('Lighten', 'transmax-core'),
                    'color-dodge' => esc_html__('Color Dodge', 'transmax-core'),
                    'saturation' => esc_html__('Saturation', 'transmax-core'),
                    'color' => esc_html__('Color', 'transmax-core'),
                    'difference' => esc_html__('Difference', 'transmax-core'),
                    'exclusion' => esc_html__('Exclusion', 'transmax-core'),
                    'hue' => esc_html__('Hue', 'transmax-core'),
                    'luminosity' => esc_html__('Luminosity', 'transmax-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail:before' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_notice_idle',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'overlay_blend_idle!' => '',
                    'overlay_idle_color' => ''
                ],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Blend Mode affects only overlay color|image. Please choose one.', 'transmax-core' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'overlay_idle',
                'selector' => '{{WRAPPER}} .member__thumbnail img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'overlay_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'overlay_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .member__thumbnail:after',
            ]
        );

        $this->add_control(
            'overlay_blend_hover',
            [
                'label' => esc_html__('Blend Mode', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Disabled', 'transmax-core'),
                    'multiply' => esc_html__('Multiply', 'transmax-core'),
                    'screen' => esc_html__('Screen', 'transmax-core'),
                    'overlay' => esc_html__('Overlay', 'transmax-core'),
                    'darken' => esc_html__('Darken', 'transmax-core'),
                    'lighten' => esc_html__('Lighten', 'transmax-core'),
                    'color-dodge' => esc_html__('Color Dodge', 'transmax-core'),
                    'saturation' => esc_html__('Saturation', 'transmax-core'),
                    'color' => esc_html__('Color', 'transmax-core'),
                    'difference' => esc_html__('Difference', 'transmax-core'),
                    'exclusion' => esc_html__('Exclusion', 'transmax-core'),
                    'hue' => esc_html__('Hue', 'transmax-core'),
                    'luminosity' => esc_html__('Luminosity', 'transmax-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail:after' => 'mix-blend-mode: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_notice_hover',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'overlay_blend_hover!' => '',
                    'overlay_hover_color' => ''
                ],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Blend Mode affects only overlay color|image. Please choose one.', 'transmax-core' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'overlay_hover',
                'selector' => '{{WRAPPER}} .member__wrapper:hover .member__thumbnail img',
            ]
        );

        $this->add_control(
            'overlay_hover_transition',
            [
                'label' => esc_html__('Transition Duration', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 3, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .member__thumbnail,
                     {{WRAPPER}} .member__thumbnail img,
                     {{WRAPPER}} .member__thumbnail:before,
                     {{WRAPPER}} .member__thumbnail:after' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
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
                'name' => 'title',
                'selector' => '{{WRAPPER}} .member__name',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .member__name',
            ]
        );

        $this->start_controls_tabs(
            'tabs_title',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_title_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'title_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__name a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> HIGHLIGHTED INFO
         */

        $this->start_controls_section(
            'style_highlighted_info',
            [
                'label' => esc_html__('Highlighted Info', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta',
                'selector' => '{{WRAPPER}} .info__highlighted',
            ]
        );

        $this->add_responsive_control(
            'highlighted_meta_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .info__highlighted' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'highlighted_meta_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .info__highlighted' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'highlighted_meta_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .info__highlighted' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'highlighted_meta_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .info__highlighted',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> SOCIALS
         */

        $this->start_controls_section(
            'style_socials',
            [
                'label' => esc_html__('Socials', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'socials_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'socials_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'socials_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'socials_container_bg_idle',
            [
                'label' => esc_html__('Container Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__socials' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'socials',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'socials_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'socials_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'socials_official_colors_idle',
            [
                'type' => Controls_Manager::HIDDEN,
                'condition' => ['socials_color_idle' => ''],
                'prefix_class' => 'socials-official-',
                'default' => 'idle',
            ]
        );

        $this->add_control(
            'socials_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'socials_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'socials_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'socials_official_colors_hover',
            [
                'type' => Controls_Manager::HIDDEN,
                'condition' => ['socials_color_hover' => ''],
                'prefix_class' => 'socials-official-',
                'default' => 'hover',
            ]
        );

        $this->add_control(
            'socials_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .social__icon:hover' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> EXCERPT | CONTENT
         */

        $this->start_controls_section(
            'style_excerpt',
            [
                'label' => esc_html__('Excerpt | Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['hide_content' => ''],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

	    $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .member__excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt',
                'selector' => '{{WRAPPER}} .member__excerpt',
            ]
        );

	    $this->start_controls_tabs(
            'tabs_excerpt',
            ['separator' => 'before']
        );

	    $this->start_controls_tab(
            'tab_excerpt_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'excerpt_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_excerpt_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'excerpt_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .member__wrapper:hover .member__excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        (new Team_Template())->render($this->get_settings_for_display());
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
