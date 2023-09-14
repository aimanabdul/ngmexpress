<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-gallery.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Background,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Typography
};
use WGL_Extensions\{
    Includes\WGL_Carousel_Settings,
    Includes\WGL_Elementor_Helper
};

class WGL_Gallery extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-gallery';
    }

    public function get_title()
    {
        return esc_html__('WGL Gallery', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-gallery';
    }

    public function get_categories()
    {
        return ['wgl-modules'];
    }

    public function get_script_depends()
    {
        return [
            'swiper',
            'imagesloaded',
            'isotope',
            'jquery-justifiedGallery',
            'wgl-widgets',
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
            'gallery',
            [
                'type' => Controls_Manager::GALLERY,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'gallery_layout',
            [
                'label' => esc_html__('Gallery Layout', 'transmax-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'justified' => [
                        'title' => esc_html__('Justified', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_justified.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['gallery_layout!' => 'justified'],
                'render_type' => 'template',
                'options' => [
                    '1' => esc_html__('1 (one)', 'transmax-core'),
                    '2' => esc_html__('2 (two)', 'transmax-core'),
                    '3' => esc_html__('3 (three)', 'transmax-core'),
                    '4' => esc_html__('4 (four)', 'transmax-core'),
                    '5' => esc_html__('5 (five)', 'transmax-core'),
                ],
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'prefix_class' => 'col%s-',
            ]
        );

        $this->add_responsive_control(
            'justified_height',
            [
                'label' => esc_html__('Row Height', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['gallery_layout' => 'justified'],
                'render_type' => 'template',
                'range' => [
                    'px' => ['min' => 20, 'max' => 600],
                ],
                'default' => ['size' => 200],
                'tablet_default' => ['size' => 150],
                'mobile_default' => ['size' => 100],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => esc_html__('Gap', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['size' => 10],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_items:not(.gallery-justified) .wgl-gallery_item-wrapper' => 'padding: calc({{SIZE}}px / 2);',
                    '{{WRAPPER}} .wgl-gallery_items:not(.gallery-justified)' => 'margin: calc(-{{SIZE}}px / 2);',
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'img_size_string',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Image Size', 'transmax-core'),
                'condition' => [
                    'gallery_layout' => ['grid', 'carousel']
                ],
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'transmax-core'),
                    '300' => esc_html__('300x300 - Medium', 'transmax-core'),
                    '768' => esc_html__('768x768 - Medium Large', 'transmax-core'),
                    '1024' => esc_html__('1024x1024 - Large', 'transmax-core'),
                    'full' => esc_html__('Full', 'transmax-core'),
                    'custom' => esc_html__('Custom', 'transmax-core'),
                ],
                'default' => 'full',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'transmax-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [
                    'img_size_string' => 'custom',
                    'gallery_layout' => ['grid', 'carousel']
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'gallery_layout' => ['grid', 'carousel']
                ],
                'options' => [
                    '' => esc_html__('No Crop', 'transmax-core'),
                    '1:1' => esc_html__('1:1', 'transmax-core'),
                    '3:2' => esc_html__('3:2', 'transmax-core'),
                    '4:3' => esc_html__('4:3', 'transmax-core'),
                    '6:5' => esc_html__('6:5', 'transmax-core'),
                    '9:16' => esc_html__('9:16', 'transmax-core'),
                    '16:9' => esc_html__('16:9', 'transmax-core'),
                    '21:9' => esc_html__('21:9', 'transmax-core'),
                ],
                'default' => '1:1',
            ]
        );

        $this->add_control(
            'link_destination',
            [
                'label' => esc_html__('Link Target', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'transmax-core'),
                    'file' => esc_html__('Media File', 'transmax-core'),
                    'custom' => esc_html__('Custom URL', 'transmax-core'),
                ],
                'default' => 'file',
            ]
        );

        $this->add_control(
            'link_custom__notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['link_destination' => 'custom'],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Specify the link in the attachment details of each corresponding image.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'link_target_blank',
            [
                'label' => esc_html__('Open in New Tab', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['link_destination' => 'custom'],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'file_popup',
            [
                'label' => esc_html__('Open in Popup', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['link_destination' => 'file'],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'popup_hide_title_description',
            [
                'label' => esc_html__('Hide Title and Description on Popup', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['file_popup' => 'yes'],
                'selectors' => [
                    '#elementor-lightbox-slideshow-all-{{ID}} .elementor-slideshow__title,
                     #elementor-lightbox-slideshow-all-{{ID}} .elementor-slideshow__description' => 'display: none;',
                ],
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__('Order By', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'separator' => 'before',
                'options' => [
                    '' => esc_html__('Default', 'transmax-core'),
                    'random' => esc_html__('Random', 'transmax-core'),
                    'asc' => esc_html__('ASC', 'transmax-core'),
                    'desc' => esc_html__('DESC', 'transmax-core'),
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'add_animation',
            [
                'label' => esc_html__('Add Appear Animation', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => ['gallery_layout!' => 'carousel'],
            ]
        );

        $this->add_control(
            'appear_animation',
            [
                'label' => esc_html__('Animation Style', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'add_animation' => 'yes',
                    'gallery_layout!' => 'carousel'
                ],
                'options' => [
                    'fade-in' => esc_html__('Fade In', 'transmax-core'),
                    'slide-top' => esc_html__('Slide Top', 'transmax-core'),
                    'slide-bottom' => esc_html__('Slide Bottom', 'transmax-core'),
                    'slide-left' => esc_html__('Slide Left', 'transmax-core'),
                    'slide-right' => esc_html__('Slide Right', 'transmax-core'),
                    'zoom' => esc_html__('Zoom', 'transmax-core'),
                ],
                'default' => 'fade-in',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> IMAGE ATTACHMENT
         */

        $this->start_controls_section(
            'content_image_attachment',
            ['label' => esc_html__('Image Attachment', 'transmax-core')]
        );

        $this->add_control(
            'info_animation',
            [
                'label' => esc_html__('Animation', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'transmax-core'),
                    'until_hover' => esc_html__('Visible Until Hover', 'transmax-core'),
                    'always' => esc_html__('Always Visible', 'transmax-core'),
                ],
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'image_title',
            [
                'label' => esc_html__('Title', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'transmax-core'),
                    'alt' => esc_html__('Alt', 'transmax-core'),
                    'title' => esc_html__('Title', 'transmax-core'),
                    'caption' => esc_html__('Caption', 'transmax-core'),
                    'description' => esc_html__('Description', 'transmax-core'),
                ],
            ]
        );

        $this->add_control(
            'image_descr',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'transmax-core'),
                    'alt' => esc_html__('Alt', 'transmax-core'),
                    'title' => esc_html__('Title', 'transmax-core'),
                    'caption' => esc_html__('Caption', 'transmax-core'),
                    'description' => esc_html__('Description', 'transmax-core'),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL SETTINGS
         */

        $this->start_controls_section(
            'content_carousel',
            [
                'label' => esc_html__('Carousel Settings', 'transmax-core'),
                'condition' => ['gallery_layout' => 'carousel'],
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this);

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['use_pagination' => 'yes'],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'pagination_margin' => [
                'range' => [
                    'px' => ['min' => -50, 'max' => 100]
                ],
                'default' => [
                    'size' => 20
                ],
            ]
        ]);

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

        $this->add_control(
            'navigation_responsive_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'use_navigation',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'customize_responsive',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_responsive_controls($this);

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
            'image_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('image');

        $this->start_controls_tab(
            'image_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'image_radius_idle',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_idle',
                'condition' => ['gallery_layout!' => 'justified'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .wgl-gallery_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item:before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'image_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'condition' => ['gallery_layout!' => 'justified'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .wgl-gallery_item:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'image_bg_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wgl-gallery_item:after',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> INFO
         */

        $this->start_controls_section(
            'style_info',
            [
                'label' => esc_html__('Info', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'info_alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'transmax-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'transmax-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'info_vertical',
            [
                'label' => esc_html__('Vertical Position', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'transmax-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'transmax-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors_dictionary' => [
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ],
                'default' => 'middle',
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'info_padding',
            [
                'label' => esc_html__('Info Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Title Styles
        $this->add_control(
            'divider_1_1',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_control(
            'divider_1',
            [
                'label' => esc_html__('Title Styles', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'divider_1_2',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'selector' => '{{WRAPPER}} .wgl-gallery_image-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('title');

        $this->start_controls_tab(
            'title_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-gallery_image-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'divider_2_1',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_control(
            'divider_2',
            [
                'label' => esc_html__('Description Styles', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'divider_2_2',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'descr_typo',
                'selector' => '{{WRAPPER}} .wgl-gallery_image-descr',
            ]
        );

        $this->add_responsive_control(
            'descr_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-descr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('description');

        $this->start_controls_tab(
            'description_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'description_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'description_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'description_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-gallery_item:hover .wgl-gallery_image-descr' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        extract($this->get_settings_for_display());

        // Variables validation
        $gallery = $gallery ?? [];
        $img_size_string = $img_size_string ?? '';
        $img_size_array = $img_size_array ?? [];
        $img_aspect_ratio = $img_aspect_ratio ?? '';
        $open_in_popup = $file_popup ? 'yes' : 'no';
        $item_tag = 'none' === $link_destination ? 'div' : 'a';

        switch ($gallery_layout) {
            case 'masonry':
                $layout_class = 'gallery-masonry';
                break;
            case 'justified':
                $layout_class = 'gallery-justified';
                $this->add_render_attribute('gallery_items', [
                    'data-height' => $justified_height['size'],
                    'data-tablet-height' => $justified_height_tablet['size'],
                    'data-mobile-height' => $justified_height_mobile['size'],
                    'data-gap' => $gap['size'],
                    'data-tablet-gap' => $gap_tablet['size'],
                    'data-mobile-gap' => $gap_mobile['size'],
                ]);
                break;
            case 'carousel':
                $layout_class = 'gallery-carousel';
                break;
            default:
                $layout_class = '';
                break;
        }

        //* Gallery order
        if ('random' === $order_by) {
            shuffle($gallery);
        } elseif ('desc' === $order_by) {
            krsort($gallery);
        }

        $this->add_render_attribute('gallery', 'class', 'wgl-gallery');

        $this->add_render_attribute('gallery_items', [
            'class' => [
                'wgl-gallery_items',
                $layout_class,
            ],
        ]);

        $this->add_render_attribute('gallery_item_wrap', 'class', 'wgl-gallery_item-wrapper' . ( 'carousel' === $gallery_layout ? ' swiper-slide' : '' ));

        $this->add_render_attribute('gallery_image_info', [
            'class' => [
                'wgl-gallery_image-info',
                !empty($info_animation) ? 'show_' . $info_animation : '',
            ],
        ]);

        //* Appear Animation
        if (
            'carousel' !== $gallery_layout
            && $add_animation
        ) {
            $this->add_render_attribute('gallery_items', [
                'class' => [
                    'appear-animation',
                    $appear_animation,
                ],
            ]);
        }

        ob_start();
        foreach ($gallery as $index => $item) {
            $id = $item[ 'id' ];
            $attachment = get_post( $id );
            $image_data = wp_get_attachment_image_src( $id, 'full' );

            if ( empty( $image_data[ 0 ] ) ) {
				continue;
			}

            $dimensions = WGL_Elementor_Helper::get_image_dimensions(
                $img_size_array ?: $img_size_string,
                $img_aspect_ratio,
                $image_data
            );
            $dimensions[ 'width' ] = $dimensions[ 'width' ] ?? $image_data[ 1 ] ?? null;
			$dimensions[ 'height' ] = $dimensions[ 'height' ] ?? $image_data[ 2 ] ?? null;

            $image_full_url = $image_data[ 0 ];
            $image_resized_url = aq_resize( $image_full_url, $dimensions[ 'width' ], $dimensions[ 'height' ], true, true, true ) ?: $image_full_url;

            // Image Attachment
            $image_arr = [
	            'src_full' => $image_full_url,
                'src_resized' => $image_resized_url,
                'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
                'title' => $attachment->post_title,
                'caption' => $attachment->post_excerpt,
                'description' => $attachment->post_content
            ];

            $this->add_render_attribute( 'gallery_item_' . $index, 'class', 'wgl-gallery_item' );

            // Link
            switch ($link_destination) {
                case 'file':
                    $this->add_lightbox_data_attributes('gallery_item_' . $index, $id, $open_in_popup, 'all-' . $this->get_id());
                    $this->add_render_attribute( 'gallery_item_' . $index, [
                        'href' => $image_arr[ 'src_full' ],
                    ] );
                    break;

                case 'custom':
                    $custom_link = get_post_meta( $id, 'custom_image_link', true );
                    if ( ! empty( $custom_link ) ) {
                        $this->add_render_attribute( 'gallery_item_' . $index, [
                            'href' => $custom_link,
                            'target' => $link_target_blank ? '_blank' : '_self',
                        ] );
                        $item_tag = 'a';
                    } else {
                        $item_tag = 'div';
                    }
                    break;
            }

            $this->add_render_attribute( 'gallery_image' . $index, [
                'class' => 'wgl-gallery_image',
                'src' => $image_arr[ 'src_resized' ],
                'alt' => $image_arr[ 'alt' ],
                'loading' => 'lazy'
            ] );

            echo '<div ', $this->get_render_attribute_string('gallery_item_wrap'), '>';
                echo '<', $item_tag, ' ', $this->get_render_attribute_string('gallery_item_' . $index), '>';
                echo '<img ', $this->get_render_attribute_string('gallery_image' . $index), '>'; // gallery image
                echo !empty($this->attachment_info($image_arr))
                    ? '<div ' . $this->get_render_attribute_string('gallery_image_info') . '>' . $this->attachment_info($image_arr) . '</div>'
                    : ''; //* attachment info
                echo '</', $item_tag, '>'; //* gallery item
            echo '</div>';
        }
        $gallery_items = ob_get_clean();

        echo '<div ', $this->get_render_attribute_string('gallery'), '>',
            '<div ', $this->get_render_attribute_string('gallery_items'), '>',
                'carousel' === $gallery_layout ? $this->apply_carousel_options($gallery_items) : $gallery_items,
            '</div>',
        '</div>';
    }

    protected function attachment_info($image_arr)
    {
        $image_title = $this->get_settings_for_display('image_title');
        $image_descr = $this->get_settings_for_display('image_descr');

        ob_start();
        if ($image_title && !empty($image_arr[$image_title])) {
            echo '<div class="wgl-gallery_image-title">',
                $image_arr[$image_title],
            '</div>';
        }

        if ($image_descr && !empty($image_arr[$image_descr])) {
            echo '<div class="wgl-gallery_image-descr">',
                $image_arr[$image_descr],
            '</div>';
        }

        return ob_get_clean();
    }

    protected function apply_carousel_options($items_html)
    {
        extract($this->get_settings_for_display());

        $options = [
            // General
            'slides_per_row' => $columns,
            'autoplay' => $autoplay,
            'autoplay_speed' => $autoplay_speed,
            'slider_infinite' => $slider_infinite,
            'slide_per_single'  => $slide_per_single,
            'fade_animation' => $fade_animation,
            'center_mode' => $center_mode,
            // Pagination
            'use_pagination' => $use_pagination,
            'pagination_type' => $pagination_type,
            // Navigation
            'use_navigation' => $use_navigation,
            'navigation_position' => $navigation_position,
            'navigation_view' => $navigation_view,
            // Responsive
            'customize_responsive' => $customize_responsive,
            'desktop_breakpoint' => $desktop_breakpoint,
            'desktop_slides' => $desktop_slides,
            'tablet_breakpoint' => $tablet_breakpoint,
            'tablet_slides' => $tablet_slides,
            'mobile_breakpoint' => $mobile_breakpoint,
            'mobile_slides' => $mobile_slides,
        ];

        return WGL_Carousel_Settings::init($options, $items_html);
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
