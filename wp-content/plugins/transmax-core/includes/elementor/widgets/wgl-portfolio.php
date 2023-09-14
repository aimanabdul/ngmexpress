<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-portfolio.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Background,
    Group_Control_Box_Shadow,
    Utils
};
use WGL_Extensions\{
    Includes\WGL_Loop_Settings,
    Includes\WGL_Carousel_Settings,
    Templates\WGL_Portfolio as Portfolio_Template
};

class WGL_Portfolio extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-portfolio';
    }

    public function get_title()
    {
        return esc_html__('WGL Portfolio', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-portfolio';
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
            'layout',
            [
                'label' => esc_html__('Layout', 'transmax-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                    'masonry-1' => [
                        'title' => esc_html__('Masonry 1', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'masonry-2' => [
                        'title' => esc_html__('Masonry 2', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_2.png',
                    ],
                    'masonry-3' => [
                        'title' => esc_html__('Masonry 3', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_3.png',
                    ],
                    'masonry-4' => [
                        'title' => esc_html__('Masonry 4', 'transmax-core'),
                        'image' => WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry_4.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'posts_per_row',
            [
                'label' => esc_html__('Columns Amount', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'layout' => ['grid', 'masonry-1', 'carousel']
                ],
                'options' => [
                    1 => esc_html__('1 (one)', 'transmax-core'),
                    2 => esc_html__('2 (two)', 'transmax-core'),
                    3 => esc_html__('3 (three)', 'transmax-core'),
                    4 => esc_html__('4 (four)', 'transmax-core'),
                    5 => esc_html__('5 (five)', 'transmax-core'),
                ],
                'default' => 3,
            ]
        );

	    $this->add_control(
		    'grid_gap',
		    [
			    'label' => esc_html__('Grid Gap', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0, 'max' => 50, 'step' => 2],
			    ],
			    'default' => ['size' => 30],
		    ]
	    );

	    $this->add_control(
            'show_filter',
            [
                'label' => esc_html__('Show Filter', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['layout!' => 'carousel'],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'filter_counter_enabled',
            [
                'label' => esc_html__('Use Filter Counter?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['show_filter' => 'yes'],
                'default' => 'yes'
            ]
        );

	    $this->add_control(
            'filter_max_width_enabled',
            [
                'label' => esc_html__('Limit the Filter Container Width', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['show_filter' => 'yes'],
            ]
        );

        $this->add_control(
            'filter_max_width',
            [
                'label' => esc_html__('Filter Container Max Width (px)', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'show_filter' => 'yes',
                    'filter_max_width_enabled' => 'yes',
                ],
                'default' => '1170',
                'selectors' => [
                    '{{WRAPPER}} .portfolio__filter' => 'max-width: {{VALUE}}px;',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'filter_alignment',
		    [
			    'label' => esc_html__('Filter Align', 'transmax-core'),
			    'type' => Controls_Manager::SELECT,
			    'condition' => [
				    'show_filter' => 'yes',
			    ],
			    'options' => [
				    'left' => esc_html__('Left', 'transmax-core'),
				    'center' => esc_html__('Сenter', 'transmax-core'),
				    'right' => esc_html__('Right', 'transmax-core'),
			    ],
			    'default' => 'center',
		    ]
	    );

        $this->add_control(
            'img_size_string',
            [
                'label' => esc_html__('Image Size', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'layout' => ['grid', 'carousel', 'masonry-1']
                ],
                'separator' => 'before',
                'options' => [
                    '150' => esc_html__('150x150 - Thumbnail', 'transmax-core'),
                    '300' => esc_html__('300x300 - Medium', 'transmax-core'),
                    '1024' => esc_html__('1024x1024 - Large', 'transmax-core'),
                    '1140x840' => esc_html__('1140x840 - 2 Columns', 'transmax-core'),
                    '740x740' => esc_html__('740x740 - 3 Columns', 'transmax-core'),
                    '886' => esc_html__('886x886 - 4 Columns Wide', 'transmax-core'),
                    'full' => esc_html__('Full', 'transmax-core'),
                    'custom' => esc_html__('Custom', 'transmax-core'),
                ],
                'default' => '740x740',
            ]
        );

        $this->add_control(
            'img_size_array',
            [
                'label' => esc_html__('Image Dimension', 'transmax-core'),
                'type' => Controls_Manager::IMAGE_DIMENSIONS,
                'condition' => [
                    'img_size_string' => 'custom',
                    'layout' => ['grid', 'carousel', 'masonry-1'],
                ],
                'description' => esc_html__('Crop the original image to any custom size. You can also set a single value for width to keep the initial ratio.', 'transmax-core'),
                'default' => [
                    'width' => '740',
                    'height' => '740',
                ],
            ]
        );

        $this->add_control(
            'img_aspect_ratio',
            [
                'label' => esc_html__('Image Aspect Ratio', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'layout' => ['grid', 'carousel', 'masonry-1'],
                    'img_size_string!' => 'custom',
                ],
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
            'remainings_loading_type',
            [
                'label' => esc_html__('Remaining Posts Loading Type', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['layout!' => 'carousel'],
                'separator' => 'before',
                'options' => [
                    'none' => esc_html__('None', 'transmax-core'),
                    'pagination' => esc_html__('Pagination', 'transmax-core'),
                    'infinite' => esc_html__('Infinite Scroll', 'transmax-core'),
                    'load_more' => esc_html__('Load More', 'transmax-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'remainings_loading_alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['remainings_loading_type' => 'pagination'],
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
                    '{{WRAPPER}} .wgl-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'remainings_loading_pagination_offset',
            [
                'label' => esc_html__('Margin Top', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['remainings_loading_type' => 'pagination'],
                'range' => [
                    'px' => ['min' => -150, 'max' => 500],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'remainings_loading_btn_items_amount',
            [
                'label' => esc_html__('Items to be loaded', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'layout!' => 'carousel',
                    'remainings_loading_type' => ['load_more', 'infinite'],
                ],
                'min' => 1,
                'default' => 4,
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'layout!' => 'carousel',
                    'remainings_loading_type' => 'load_more',
                ],
                'dynamic' => ['active' => true],
                'default' => esc_html__('Load More', 'transmax-core'),
            ]
        );

        $this->add_control(
            'appear_animation_enabled',
            [
                'label' => esc_html__('Appear Animation', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'appear_animation_style',
            [
                'label' => esc_html__('Animation Style', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['appear_animation_enabled!' => ''],
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
         * CONTENT -> APPEARANCE
         */

        $this->start_controls_section(
            'content_appearance',
            ['label' => esc_html__('Appearance', 'transmax-core')]
        );

        $this->add_control(
            'gallery_mode_enabled',
            [
                'label' => esc_html__('Gallery Mode', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'use_additional_post',
            [
                'label' => esc_html__('Add Additional Post', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'additional_post_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['use_additional_post!' => ''],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Additional Post item is fully cusomizible via `Style` tab.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'show_portfolio_title',
            [
                'label' => esc_html__('Show Heading?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta_categories',
            [
                'label' => esc_html__('Show Categories?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_content',
            [
                'label' => esc_html__('Show Excerpt/Content?', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_control(
            'content_letter_count',
            [
                'label' => esc_html__('Content Characters Amount', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'show_content' => 'yes',
                    'gallery_mode_enabled' => '',
                ],
                'min' => 1,
                'default' => 85,
            ]
        );

        $this->add_control(
            'description_heading',
            [
                'label' => esc_html__('Description', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['gallery_mode_enabled' => ''],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_position',
            [
                'label' => esc_html__('Position', 'transmax-core'),
                'condition' => ['gallery_mode_enabled' => ''],
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'inside_image' => esc_html__('within image', 'transmax-core'),
                    'under_image' => esc_html__('beneath image', 'transmax-core'),
                ],
                'default' => 'inside_image',
            ]
        );

        $this->add_control(
            'description_animation',
            [
                'label' => esc_html__('Animation', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'description_position' => 'inside_image',
                    'gallery_mode_enabled' => ''
                ],
                'options' => [
                    'simple' => esc_html__('Simple', 'transmax-core'),
                    'sub_layer' => esc_html__('Sub-Layer', 'transmax-core'),
                    'offset' => esc_html__('Side Offset', 'transmax-core'),
                    'zoom_in' => esc_html__('Zoom In', 'transmax-core'),
                    'outline' => esc_html__('Outline', 'transmax-core'),
                    'until_hover' => esc_html__('Visible Until Hover', 'transmax-core'),
                ],
                'default' => 'simple',
            ]
        );

        $this->add_control(
            'description_alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => ['gallery_mode_enabled' => ''],
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
                        'title' => esc_html__('Justified', 'transmax-core'),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .description__wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_media_type',
            [
                'label' => esc_html__('Media', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'description_position' => 'inside_image',
                    'gallery_mode_enabled' => ''
                ],
                'label_block' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'transmax-core'),
                        'icon' => 'fa fa-ban',
                    ],
                    'font' => [
                        'title' => esc_html__('Icon', 'transmax-core'),
                        'icon' => 'far fa-smile',
                    ],
                ],
                'default' => 'font',
            ]
        );

        $this->add_control(
            'description_icon',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'description_media_type' => 'font',
                    'description_position' => 'inside_image',
                    'gallery_mode_enabled' => ''
                ],
                'label_block' => true,
                'default' => [
                    'library' => 'flaticon',
                    'value' => 'flaticon-right-arrow',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> LINKS
         */

        $this->start_controls_section(
            'content_links',
            [
                'label' => esc_html__('Links', 'transmax-core'),
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_control(
            'image_has_link',
            [
                'label' => esc_html__('Add link on Image', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_has_link',
            [
                'label' => esc_html__('Add link on Heading', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['show_portfolio_title!' => ''],
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'linked_icon',
            [
                'label' => esc_html__('Add link on Icon', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'description_media_type' => 'font',
                    'description_icon!' => '',
                    'description_position' => 'inside_image',
                    'gallery_mode_enabled' => '',
                ],
            ]
        );

        $this->add_control(
            'link_destination',
            [
                'label' => esc_html__('Click Action', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'title_has_link',
                            'operator' => '!==',
                            'value' => '',
                        ], [
                            'name' => 'image_has_link',
                            'operator' => '!==',
                            'value' => '',
                        ],
                    ],
                ],
                'options' => [
                    'single' => esc_html__('Open Single Page', 'transmax-core'),
                    'custom' => esc_html__('Open Custom Link', 'transmax-core'),
                    'popup' => esc_html__('Popup the Image', 'transmax-core'),
                ],
                'default' => 'single',
            ]
        );

        $this->add_control(
            'link_custom_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['link_destination' => 'custom'],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: Specify the link in metabox section of each corresponding post.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'link_target',
            [
                'label' => esc_html__('Open link in a new tab', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'conditions' => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'title_has_link',
                                    'operator' => '!==',
                                    'value' => '',
                                ], [
                                    'name' => 'image_has_link',
                                    'operator' => '!==',
                                    'value' => '',
                                ],
                            ],
                        ],
                        [
                            'name' => 'link_destination',
                            'operator' => '!==',
                            'value' => 'popup',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        $this->start_controls_section(
            'content_carousel',
            [
                'label' => esc_html__('Carousel Options', 'transmax-core'),
                'condition' => ['layout' => 'carousel'],
            ]
        );

        WGL_Carousel_Settings::add_general_controls($this, [
            'slider_infinite'  => [
                'default' => 'yes'
            ],
            'slide_per_single' => [
                'default' => 1
            ],
        ]);

        $this->add_control(
            'variable_width',
            [
                'label' => esc_html__('Variable Width', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'chess_divider_before',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['chess_layout!' => ''],
            ]
        );

        $this->add_control(
            'chess_layout',
            [
                'label' => esc_html__('Chess Layout', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'chess',
                'prefix_class' => 'layout-',
            ]
        );

        $this->add_control(
            'chess_offset',
            [
                'label' => esc_html__('Chess Offset', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['chess_layout!' => ''],
                'size_units' => ['px', 'rem'],
                'range' => [
                    'px' => ['min' => 1, 'max' => 300],
                    'rem' => ['min' => 0.1, 'max' => 20, 'step' => 0.1],
                ],
                'default' => ['size' => '30'],
                'selectors' => [
                    '{{WRAPPER}} .swiper-wrapper' => 'padding-top: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .portfolio__item:nth-child(even)' => 'margin-top: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'chess_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'condition' => [
                    'autoplay!' => '',
                    'chess_layout!' => '',
                ],
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'raw' => esc_html__('Note: even number of portfolio items is preffered.', 'transmax-core'),
            ]
        );

        $this->add_control(
            'pagination_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [[
                        'terms' => [[
                            'name' => 'chess_layout',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ], [
                        'terms' => [[
                            'name' => 'use_pagination',
                            'operator' => '!=',
                            'value' => '',
                        ]]
                    ],],
                ],
            ]
        );

        WGL_Carousel_Settings::add_pagination_controls($this, [
            'pagination_type'  => [
                'default' => 'circle_border',
            ],
            'pagination_margin' => [
                'range' => [
                    'px' => ['min' => -60, 'max' => 1000]
                ],
            ],
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

        WGL_Carousel_Settings::add_navigation_controls($this, [
            'pagination_margin' => [
                'range' => [
                    'px' => ['min' => -60, 'max' => 1000]
                ],
            ],
        ]);

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

        WGL_Carousel_Settings::add_responsive_controls($this, [
            'desktop_slides' => [
                'label' => esc_html__('Columns amount', 'transmax-core'),
                'max' => 5,
            ],
            'tablet_slides' => [
                'label' => esc_html__('Columns amount', 'transmax-core'),
                'max' => 5,
            ],
            'mobile_slides' => [
                'label' => esc_html__('Columns amount', 'transmax-core'),
                'max' => 5,
            ],
        ]);

        $this->end_controls_section();

        /**
         * SETTINGS -> QUERY
         */

        WGL_Loop_Settings::add_controls($this, [
            'post_type' => 'portfolio',
            'hide_cats' => true,
            'hide_tags' => true
        ]);

        /**
         * STYLE -> FILTER
         */

        $this->start_controls_section(
            'style_filter',
            [
                'label' => esc_html__('Filter', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_filter' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'filter',
                'selector' => '{{WRAPPER}} .isotope-filter a',
            ]
        );
	
	    $this->add_responsive_control(
		    'filter_cats_gap',
		    [
			    'label' => esc_html__('Categories Gap', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 0, 'max' => 100, 'step' => 1],
				    '%' => ['min' => 0, 'max' => 10, 'step' => 0.5],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter a' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
				    '{{WRAPPER}} .isotope-filter .wgl-filter_swiper_wrapper' => 'margin-right: calc({{SIZE}}{{UNIT}} / -2); margin-left: calc({{SIZE}}{{UNIT}} / -2)',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'filter_cats_margin',
		    [
			    'label' => esc_html__( 'Margin', 'transmax-core' ),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => [ 'px', 'em', '%' ],
			    'selectors' => [
				    '{{WRAPPER}} .isotope-filter' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_responsive_control(
            'filter_cats_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'filter_cats_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('filter');

        $this->start_controls_tab(
            'filter_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'filter_color_idle',
            [
                'label' => esc_html__('Category Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:not(.active)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:not(.active)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_border_idle',
                'selector' => '{{WRAPPER}} .isotope-filter a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'filter_color_hover',
            [
                'label' => esc_html__('Category Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_counter_color_hover',
            [
                'label' => esc_html__('Counter Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['filter_counter_enabled' => 'yes'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:hover .filter_counter' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_border_hover',
                'selector' => '{{WRAPPER}} .isotope-filter a:hover',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_active',
            ['label' => esc_html__('Active', 'transmax-core')]
        );

        $this->add_control(
            'filter_color_active',
            [
                'label' => esc_html__('Category Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_counter_color_active',
            [
                'label' => esc_html__('Counter Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['filter_counter_enabled' => 'yes'],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a.active .filter_counter' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_extra_element_color_active',
            [
                'label' => esc_html__('Animated Element Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_bg_active',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .isotope-filter a.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'filter_border_active',
                'selector' => '{{WRAPPER}} .isotope-filter a.active',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'filter_shadow_divider',
            ['type' => Controls_Manager::DIVIDER]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'filter_shadow',
                'selector' => '{{WRAPPER}} .isotope-filter a',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> OVERLAYS
         */

        $this->start_controls_section(
            'style_overlays',
            [
                'label' => esc_html__('Overlays', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $item_overlay_conditions = [
            'relation' => 'or',
            'terms' => [
                [
                    'terms' => [
                        [
                            'name' => 'description_position',
                            'operator' => '===',
                            'value' => 'inside_image',
                        ],
                        [
                            'name' => 'description_animation',
                            'operator' => '!==',
                            'value' => 'sub_layer',
                        ],
                    ],
                ],
                [
                    'name' => 'gallery_mode_enabled',
                    'operator' => '!==',
                    'value' => '',
                ],
            ],
        ];

        $this->add_control(
            'overlay_heading',
            [
                'label' => esc_html__('Items Overlay', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $item_overlay_conditions,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_overlay',
                'conditions' => $item_overlay_conditions,
                'selector' => '{{WRAPPER}} .overlay',
            ]
        );

        $this->add_control(
            'outline_color',
            [
                'label' => esc_html__('Outline Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'description_position' => 'inside_image',
                    'description_animation' => 'outline',
                ],
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .animation_outline:hover .overlay:before' => 'box-shadow: inset 0px 0px 0px 10px {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'overlay_divider',
            [
                'type' => Controls_Manager::DIVIDER,
                'conditions' => $item_overlay_conditions,
            ]
        );

        $this->add_control(
            'images_overlay_heading',
            [
                'label' => esc_html__('Images Overlay', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->start_controls_tabs('images_overlay');

        $this->start_controls_tab(
            'img_overlay_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'images_grayscale_idle',
            [
                'label' => esc_html__('Grayscale Filter', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item__image img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'thubmnail_overlay_idle',
                'selector' => '{{WRAPPER}} .item__image:before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'img_overlay_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'images_grayscale_hover',
            [
                'label' => esc_html__('Grayscale Filter', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio__item:hover .item__image img' => 'filter: grayscale({{SIZE}});',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'thubmnail_overlay_hover',
                'selector' => '{{WRAPPER}} .item__image:after',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> DESCRIPTIONS
         */

        $this->start_controls_section(
            'style_descriptions',
            [
                'label' => esc_html__('Descriptions', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['gallery_mode_enabled' => ''],
            ]
        );

        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => [
                    'description_position' => 'inside_image',
                    'description_animation' => 'sub_layer',
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                                                      . 'width: calc(100% - {{RIGHT}}{{UNIT}} - {{LEFT}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .item__description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'description_under_img',
                'condition' => ['description_position' => 'under_image'],
                'selector' => '{{WRAPPER}} .item__description',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'description_inside_img',
                'condition' => [
                    'description_position' => 'inside_image',
                    'description_animation' => 'sub_layer',
                ],
                'selector' => '{{WRAPPER}} .item__description',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'description_shdw',
                'condition' => [
                    'description_position' => 'inside_image',
                    'description_animation' => 'sub_layer',
                ],
                'selector' => '{{WRAPPER}} .item__description',
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> DESCRIPTION ICONS
         */

        $this->start_controls_section(
            'style_description_icons',
            [
                'label' => esc_html__('Description Icons', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'description_media_type' => 'font',
                    'description_icon!' => '',
                    'description_position' => 'inside_image',
                    'gallery_mode_enabled' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_icon_size',
            [
                'label' => esc_html__('Icon Size', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .description__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_icon_animation',
            [
                'label' => esc_html__('Rotate on Hover', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'rotate-icon',
                'default' => 'rotate-icon',
                'prefix_class' => 'animation_',
                'default' => false
            ]
        );

        $this->add_responsive_control(
            'description_icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .description__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_icon_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .description__icon > a,
                     {{WRAPPER}} .description__icon > i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .description__icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_icon_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .description__icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'description_icon_styles',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'description_icon_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'description_icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .description__icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .description__icon a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .description__icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'description_icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .description__icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'description_icon_border_idle',
                'selector' => '{{WRAPPER}} .description__icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'description_icon_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'description_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .description__icon:hover,
                     {{WRAPPER}} .description__icon:hover a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .description__icon:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'description_icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .description__icon:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'description_icon_border_hover',
                'selector' => '{{WRAPPER}} .description__icon:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> HEADINGS
         */

        $this->start_controls_section(
            'style_headings',
            [
                'label' => esc_html__('Headings', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_portfolio_title!' => '',
                    'gallery_mode_enabled' => ''
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'headings',
                'selector' => '{{WRAPPER}} .title',
            ]
        );

        $this->add_responsive_control(
            'headings_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .item__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'headings',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'headings_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'headings_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'headings_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'headings_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .title:hover,
                     {{WRAPPER}} .title:hover a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> CATEGORIES
         */

        $this->start_controls_section(
            'style_categories',
            [
                'label' => esc_html__('Categories', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_meta_categories!' => ''],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'categories',
                'selector' => '{{WRAPPER}} .portfolio-category',
            ]
        );

        $this->add_responsive_control(
            'cat_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'cat_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'categories',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'categories_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_responsive_control(
            'cat_padding_idle',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'cat_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category,
                     {{WRAPPER}} .portfolio-category:after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cat_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'categories_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_responsive_control(
            'cat_padding_hover',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'cat_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cat_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-category:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> EXCERPT/CONTENT
         */

        $this->start_controls_section(
            'style_excerpt',
            [
                'label' => esc_html__('Excerpt|Content', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_content!' => ''],
            ]
        );

        $this->add_control(
            'custom_content_color',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .description_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> LOAD MORE BUTTON
         */

        $this->start_controls_section(
            'style_load_more',
            [
                'label' => esc_html__('Load More Button', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['remainings_loading_type' => 'load_more'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_alignment',
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
                    '{{WRAPPER}} .load_more_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'after',
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'load_more_btn',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'load_more_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'load_more_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_animated_element_color_idle',
            [
                'label' => esc_html__('Animated Element Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'load_more_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_animated_element_color_hover',
            [
                'label' => esc_html__('Animated Element Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_shadow',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .load_more_item',
            ]
        );

        $this->add_control(
            'load_more_media_heading',
            [
                'label' => esc_html__('Media', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'load_more_media_type',
            [
                'label' => esc_html__('Media Type', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    '' => [
                        'title' => esc_html__('None', 'transmax-core'),
                        'icon' => 'fa fa-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'transmax-core'),
                        'icon' => 'far fa-smile',
                    ],
                ],
                'default' => '',
            ]
        );

        $this->add_control(
            'load_more_media_icon',
            [
                'label' => esc_html__('Icon', 'transmax-core'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'condition' => ['load_more_media_type' => 'icon'],
                'default' => [
                    'library' => 'flaticon',
                    'value' => 'flaticon-plus-1',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_icon_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more__icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_icon_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'allowed_dimensions' => 'horizontal',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more__icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_icon_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'condition' => ['load_more_media_type' => 'icon'],
                'separator' => 'after',
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper .load_more_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'load_more_icon',
            ['condition' => ['load_more_media_type' => 'icon']]
        );

        $this->start_controls_tab(
            'load_more_icon_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'load_more_icon_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more__icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_icon_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'load_more_icon_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover .load_more__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'load_more_icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .load_more_item:hover .load_more__icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLE -> ADDITIONAL POST
         */

        $this->start_controls_section(
            'style_additional_item',
            [
                'label' => esc_html__('Additional Post', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['use_additional_post!' => ''],
            ]
        );

        $this->add_control(
            'additional_post_position',
            [
                'label' => esc_html__('Position', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'first' => esc_html__('First Item', 'transmax-core'),
                    'last' => esc_html__('Last Item', 'transmax-core'),
                ],
                'default' => 'last',
            ]
        );

        $this->add_control(
            'additional_post_link',
            [
                'label' => esc_html__('Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'label_block' => false,
                'placeholder' => esc_attr__('https://your-link.com', 'transmax-core'),
            ]
        );

        $this->add_control(
            'additional_post_img_heading',
            [
                'label' => esc_html__('Image', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'additional_post_img_media',
            [
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $this->add_control(
            'additional_post_btn_heading',
            [
                'label' => esc_html__('Button', 'transmax-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'additional_post_btn_text',
            [
                'label' => esc_html__('Button Text', 'transmax-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Coming Soon', 'transmax-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'additional_post_btn',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->add_control(
            'additional_post_btn_align_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
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
                    '{{WRAPPER}} .additional-post .item__wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'additional_post_btn_align_v',
            [
                'label' => esc_html__('Vertical Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => false,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'transmax-core'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'transmax-core'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'transmax-core'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__wrapper' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'additional_post_btn_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'additional_post_btn_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'additional_post_btn_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .additional-post .item__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'additional_post_button',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->start_controls_tabs('additional_post_btn');

        $this->start_controls_tab(
            'addtnl_btn_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_control(
            'addtnl_btn_color_idle',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'addtnl_btn_bg_idle',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'addtnl_btn_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_control(
            'addtnl_btn_color_hover',
            [
                'label' => esc_html__('Text Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'addtnl_btn_bg_hover',
                'selector' => '{{WRAPPER}} .additional-post .item__button',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new Portfolio_Template($atts, $this))->render();
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
