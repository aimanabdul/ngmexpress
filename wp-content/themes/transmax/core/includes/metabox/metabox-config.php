<?php

if (!class_exists('RWMB_Loader')) return;

use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

class Transmax_Metaboxes
{
    public function __construct()
    {
        // Team
        add_filter('rwmb_meta_boxes', [$this, 'team_meta_boxes']);

        // Portfolio
        add_filter('rwmb_meta_boxes', [$this, 'portfolio_meta_boxes']);
        add_filter('rwmb_meta_boxes', [$this, 'portfolio_post_settings_meta_boxes']);
        add_filter('rwmb_meta_boxes', [$this, 'portfolio_related_meta_boxes']);

        // Blog
        add_filter('rwmb_meta_boxes', [$this, 'blog_settings_meta_boxes']);
        add_filter('rwmb_meta_boxes', [$this, 'blog_meta_boxes']);
        add_filter('rwmb_meta_boxes', [$this, 'blog_related_meta_boxes']);

        // Page
        add_filter('rwmb_meta_boxes', [$this, 'page_layout_meta_boxes']);

        // Colors
        add_filter('rwmb_meta_boxes', [$this, 'page_color_meta_boxes']);

        // Header Builder
        add_filter('rwmb_meta_boxes', [$this, 'page_header_meta_boxes']);

        // Title
        add_filter('rwmb_meta_boxes', [$this, 'page_title_meta_boxes']);

        // Side Panel
        add_filter('rwmb_meta_boxes', [$this, 'page_side_panel_meta_boxes']);

        // Social Shares
        add_filter('rwmb_meta_boxes', [$this, 'page_soc_icons_meta_boxes']);

        // Footer
        add_filter('rwmb_meta_boxes', [$this, 'page_footer_meta_boxes']);

        // Copyright
        add_filter('rwmb_meta_boxes', [$this, 'page_copyright_meta_boxes']);
    }

    public function team_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Team Options', 'transmax'),
            'post_types' => ['team'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'highlighted_info',
                    'name' => esc_html__('Highlighted Info', 'transmax'),
                    'type' => 'text',
                    'class' => 'field-inputs'
                ],
                [
                    'id' => 'info_items',
                    'name' => esc_html__('Member Info', 'transmax'),
                    'type' => 'social',
                    'clone' => true,
                    'sort_clone' => true,
                    'options' => [
                        'name' => [
                            'name' => esc_html__('Name', 'transmax'),
                            'type_input' => 'text'
                        ],
                        'description' => [
                            'name' => esc_html__('Description', 'transmax'),
                            'type_input' => 'text'
                        ],
                        'link' => [
                            'name' => esc_html__('Link', 'transmax'),
                            'type_input' => 'text'
                        ],
                    ],
                ],
                [
                    'id' => 'soc_icon',
                    'name' => esc_html__('Member Socials', 'transmax'),
                    'type' => 'select_icon',
                    'placeholder' => esc_attr__('Select an icon', 'transmax'),
                    'clone' => true,
                    'sort_clone' => true,
                    'multiple' => false,
                    'options' => WGLAdminIcon()->get_icons_name(),
                    'std' => 'default',
                ],
                [
                    'id' => 'info_bg_color',
                    'name' => esc_html__('Info Background Color', 'transmax'),
                    'type' => 'color',
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => '#003b49'],
                    'std' => '#003b49',
                ],
                [
                    'id' => 'mb_info_bg',
                    'name' => esc_html__('Info Background Image', 'transmax'),
                    'type' => 'file_advanced',
                    'mime_type' => 'image',
                    'max_file_uploads' => 1,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function portfolio_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Portfolio Options', 'transmax'),
            'post_types' => ['portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_portfolio_featured_image_conditional',
                    'name' => esc_html__('Featured Image', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_featured_image_type',
                    'name' => esc_html__('Featured Image Settings', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                                ['mb_portfolio_featured_image_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'off' => esc_html__('Off', 'transmax'),
                        'replace' => esc_html__('Replace', 'transmax'),
                    ],
                    'std' => 'off',
                ],
                [
                    'id' => 'mb_portfolio_featured_image_replace',
                    'name' => esc_html__('Featured Image Replace', 'transmax'),
                    'type' => 'image_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_featured_image_conditional', '=', 'custom'],
                            ['mb_portfolio_featured_image_type', '=', 'replace'],
                        ]],
                    ],
                    'max_file_uploads' => 1,
                ],
                [
                    'id' => 'mb_portfolio_title',
                    'name' => esc_html__('Show Title on single', 'transmax'),
                    'type' => 'switch',
                    'std' => 'true',
                ],
                [
                    'id' => 'mb_portfolio_link',
                    'name' => esc_html__('Add Custom Link for Portfolio Grid', 'transmax'),
                    'type' => 'switch',
                ],
                [
                    'id' => 'portfolio_custom_url',
                    'name' => esc_html__('Custom Url for Portfolio Grid', 'transmax'),
                    'type' => 'text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_link', '=', '1']
                        ]],
                    ],
                    'class' => 'field-inputs',
                ],
                [
                    'id' => 'mb_portfolio_single_meta_categories',
                    'name' => esc_html__('Categories', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'yes' => esc_html__('Use', 'transmax'),
                        'no' => esc_html__('Hide', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_single_meta_date',
                    'name' => esc_html__('Date', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'yes' => esc_html__('Use', 'transmax'),
                        'no' => esc_html__('Hide', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_above_content_cats',
                    'name' => esc_html__('Tags', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'yes' => esc_html__('Use', 'transmax'),
                        'no' => esc_html__('Hide', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_portfolio_above_content_share',
                    'name' => esc_html__('Share Links', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'yes' => esc_html__('Use', 'transmax'),
                        'no' => esc_html__('Hide', 'transmax'),
                    ],
                    'std' => 'default',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function portfolio_post_settings_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Portfolio Post Settings', 'transmax'),
            'post_types' => ['portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_portfolio_post_conditional',
                    'name' => esc_html__('Post Layout', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Post Layout Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_post_conditional', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_portfolio_single_type_layout',
                    'name' => esc_html__('Layout', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_post_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '1' => esc_html__('Title First', 'transmax'),
                        '2' => esc_html__('Image First', 'transmax'),
                    ],
                    'std' => '2',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function portfolio_related_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Related Portfolio', 'transmax'),
            'post_types' => ['portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_portfolio_related_switch',
                    'name' => esc_html__('Portfolio Related', 'transmax'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'on' => esc_html__('On', 'transmax'),
                        'off' => esc_html__('Off', 'transmax'),
                    ],
                    'std' => 'default'
                ],
                [
                    'name' => esc_html__('Portfolio Related Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_related_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_pf_carousel_r',
                    'name' => esc_html__('Display items withiin carousel for this post', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_related_switch', '=', 'on']
                        ]],
                    ],
                    'std' => 1,
                ],
                [
                    'id' => 'mb_pf_title_r',
                    'name' => esc_html__('Title', 'transmax'),
                    'type' => 'text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_related_switch', '=', 'on']
                        ]],
                    ],
                    'std' => esc_html__('Related Portfolio', 'transmax'),
                ],
                [
                    'id' => 'mb_pf_cat_r',
                    'name' => esc_html__('Categories', 'transmax'),
                    'type' => 'taxonomy_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_related_switch', '=', 'on']
                        ]],
                    ],
                    'multiple' => true,
                    'taxonomy' => 'portfolio-category',
                ],
                [
                    'id' => 'mb_pf_column_r',
                    'name' => esc_html__('Columns', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_related_switch', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '2' => esc_html__('2', 'transmax'),
                        '3' => esc_html__('3', 'transmax'),
                        '4' => esc_html__('4', 'transmax'),
                    ],
                    'std' => '3',
                ],
                [
                    'id' => 'mb_pf_number_r',
                    'name' => esc_html__('Number of Related Items', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_portfolio_related_switch', '=', 'on']
                        ]],
                    ],
                    'min' => 0,
                    'step' => 1,
                    'std' => 3,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function blog_settings_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Post Settings', 'transmax'),
            'post_types' => ['post'],
            'context' => 'advanced',
            'fields' => [
                [
                    'name' => esc_html__('Post Layout Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'mb_post_layout_conditional',
                    'name' => esc_html__('Post Layout', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_single_type_layout',
                    'name' => esc_html__('Post Layout Type', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_post_layout_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '1' => esc_html__('Title First', 'transmax'),
                        '2' => esc_html__('Image First', 'transmax'),
                        '3' => esc_html__('Overlay Image', 'transmax'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('single_type_layout')),
                ],
                [
                    'id' => 'mb_single_padding_layout_3',
                    'name' => esc_html__('Padding Top/Bottom', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_post_layout_conditional', '=', 'custom'],
                            ['mb_single_type_layout', '=', '3'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr(WGL_Framework::get_option('single_padding_layout_3')['padding-top']),
                        'padding-bottom' => esc_attr(WGL_Framework::get_option('single_padding_layout_3')['padding-bottom']),
                    ],
                ],
                [
                    'id' => 'mb_single_apply_animation',
                    'name' => esc_html__('Apply Animation', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_post_layout_conditional', '=', 'custom'],
                            ['mb_single_type_layout', '=', '3'],
                        ]],
                    ],
                    'std' => 1,
                ],
                [
                    'name' => esc_html__('Featured Image Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'mb_featured_image_conditional',
                    'name' => esc_html__('Featured Image', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_featured_image_type',
                    'name' => esc_html__('Featured Image Settings', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_featured_image_conditional', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'off' => esc_html__('Off', 'transmax'),
                        'replace' => esc_html__('Replace', 'transmax'),
                    ],
                    'std' => 'off',
                ],
                [
                    'id' => 'mb_featured_image_replace',
                    'name' => esc_html__('Featured Image Replace', 'transmax'),
                    'type' => 'image_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_featured_image_conditional', '=', 'custom'],
                            ['mb_featured_image_type', '=', 'replace'],
                        ]],
                    ],
                    'max_file_uploads' => 1,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function blog_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Post Format Layout', 'transmax'),
            'post_types' => ['post'],
            'context' => 'advanced',
            'fields' => [
                // Standard Post Format
                [
                    'id' => 'post_format_standard',
                    'name' => esc_html__('Standard Post( Enabled only Featured Image for this post format)', 'transmax'),
                    'type' => 'static-text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['formatdiv', '=', '0']
                        ]],
                    ],
                ],
                // Gallery Post Format
                [
                    'name' => esc_html__('Gallery Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_gallery',
                    'name' => esc_html__('Add Images', 'transmax'),
                    'type' => 'image_advanced',
                    'max_file_uploads' => '',
                ],
                // Video Post Format
                [
                    'name' => esc_html__('Video Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_video_style',
                    'name' => esc_html__('Video Style', 'transmax'),
                    'type' => 'select',
                    'multiple' => false,
                    'options' => [
                        'bg_video' => esc_html__('Background Video', 'transmax'),
                        'popup' => esc_html__('Popup', 'transmax'),
                    ],
                    'std' => 'bg_video',
                ],
                [
                    'id' => 'start_video',
                    'name' => esc_html__('Start Video', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['post_format_video_style', '=', 'bg_video'],
                        ]],
                    ],
                    'std' => '0',
                ],
                [
                    'id' => 'end_video',
                    'name' => esc_html__('End Video', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['post_format_video_style', '=', 'bg_video'],
                        ]],
                    ],
                ],
                [
                    'id' => 'post_format_video_url',
                    'name' => esc_html__('oEmbed URL', 'transmax'),
                    'type' => 'oembed',
                ],
                // Quote Post Format
                [
                    'name' => esc_html__('Quote Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_qoute_text',
                    'name' => esc_html__('Quote Text', 'transmax'),
                    'type' => 'textarea',
                ],
                [
                    'id' => 'post_format_qoute_name',
                    'name' => esc_html__('Author Name', 'transmax'),
                    'type' => 'text',
                ],
                [
                    'id' => 'post_format_qoute_position',
                    'name' => esc_html__('Author Position', 'transmax'),
                    'type' => 'text',
                ],
                [
                    'id' => 'post_format_qoute_avatar',
                    'name' => esc_html__('Author Avatar', 'transmax'),
                    'type' => 'image_advanced',
                    'max_file_uploads' => 1,
                ],
                // Audio Post Format
                [
                    'name' => esc_html__('Audio Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_audio_url',
                    'name' => esc_html__('oEmbed URL', 'transmax'),
                    'type' => 'oembed',
                ],
                // Link Post Format
                [
                    'name' => esc_html__('Link Settings', 'transmax'),
                    'type' => 'wgl_heading',
                ],
                [
                    'id' => 'post_format_link_url',
                    'name' => esc_html__('URL', 'transmax'),
                    'type' => 'url',
                ],
                [
                    'id' => 'post_format_link_text',
                    'name' => esc_html__('Text', 'transmax'),
                    'type' => 'text',
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function blog_related_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Related Blog Post', 'transmax'),
            'post_types' => ['post'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_blog_show_r',
                    'name' => esc_html__('Related Options', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                        'off' => esc_html__('Off', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Related Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_blog_title_r',
                    'name' => esc_html__('Title', 'transmax'),
                    'type' => 'text',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'std' => esc_html__('Related Posts', 'transmax'),
                ],
                [
                    'id' => 'mb_blog_cat_r',
                    'name' => esc_html__('Categories', 'transmax'),
                    'type' => 'taxonomy_advanced',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'multiple' => true,
                    'taxonomy' => 'category',
                ],
                [
                    'id' => 'mb_blog_column_r',
                    'name' => esc_html__('Columns', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '12' => esc_html__('1', 'transmax'),
                        '6' => esc_html__('2', 'transmax'),
                        '4' => esc_html__('3', 'transmax'),
                        '3' => esc_html__('4', 'transmax'),
                    ],
                    'std' => '6',
                ],
                [
                    'name' => esc_html__('Number of Related Items', 'transmax'),
                    'id' => 'mb_blog_number_r',
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'min' => 0,
                    'std' => 2,
                ],
                [
                    'id' => 'mb_blog_carousel_r',
                    'name' => esc_html__('Display items carousel for this blog post', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_blog_show_r', '=', 'custom']
                        ]],
                    ],
                    'std' => 1,
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function page_layout_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Page Sidebar Layout', 'transmax'),
            'post_types' => ['page', 'post', 'team', 'portfolio', 'product'],
            'context' => 'advanced',
            'fields' => [
                [
                    'name' => esc_html__('Page Sidebar Layout', 'transmax'),
                    'id' => 'mb_page_sidebar_layout',
                    'type' => 'wgl_image_select',
                    'options' => [
                        'default' => get_template_directory_uri() . '/core/admin/img/options/1c.png',
                        'none' => get_template_directory_uri() . '/core/admin/img/options/none.png',
                        'left' => get_template_directory_uri() . '/core/admin/img/options/2cl.png',
                        'right' => get_template_directory_uri() . '/core/admin/img/options/2cr.png',
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Sidebar Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_sidebar_def',
                    'name' => esc_html__('Page Sidebar', 'transmax'),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                    'placeholder' => esc_html__('Select a Sidebar', 'transmax'),
                    'multiple' => false,
                    'options' => transmax_get_all_sidebars(),
                ],
                [
                    'id' => 'mb_page_sidebar_def_width',
                    'name' => esc_html__('Page Sidebar Width', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        '9' => esc_html__('25%', 'transmax'),
                        '8' => esc_html__('33%', 'transmax'),
                    ],
                    'std' => '9',
                ],
                [
                    'id' => 'mb_sticky_sidebar',
                    'name' => esc_html__('Sticky Sidebar On?', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_sidebar_gap',
                    'name' => esc_html__('Sidebar Side Gap', 'transmax'),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_sidebar_layout', '!=', 'default'],
                            ['mb_page_sidebar_layout', '!=', 'none'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'def' => esc_html__('Default', 'transmax'),
                        '0' => esc_html__('0', 'transmax'),
                        '15' => esc_html__('15', 'transmax'),
                        '20' => esc_html__('20', 'transmax'),
                        '25' => esc_html__('25', 'transmax'),
                        '30' => esc_html__('30', 'transmax'),
                        '35' => esc_html__('35', 'transmax'),
                        '40' => esc_html__('40', 'transmax'),
                        '45' => esc_html__('45', 'transmax'),
                        '50' => esc_html__('50', 'transmax'),
                    ],
                    'std' => 'def',
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_color_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Page Colors', 'transmax'),
            'post_types' => ['page' , 'post', 'team', 'portfolio'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_page_colors_switch',
                    'name' => esc_html__('Page Colors', 'transmax'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Main', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_theme-primary-color',
                    'name' => esc_html__('Primary Theme Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => WGL_Globals::get_primary_color()],
                    'std' => WGL_Globals::get_primary_color(),
                ],
                [
                    'id' => 'mb_theme-secondary-color',
                    'name' => esc_html__('Secondary Theme Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => WGL_Globals::get_secondary_color()],
                    'std' => WGL_Globals::get_secondary_color(),
                ],
                [
                    'id' => 'mb_theme-content-color',
                    'name' => esc_html__('Content Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => WGL_Globals::get_main_font_color()],
                    'std' => WGL_Globals::get_main_font_color(),
                ],
                [
                    'id' => 'mb_theme-headings-color',
                    'name' => esc_html__('Headings Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => WGL_Globals::get_h_font_color()],
                    'std' => WGL_Globals::get_h_font_color(),
                ],
                [
                    'id' => 'mb_body_background_color',
                    'name' => esc_html__('Body Background Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => '#ffffff'],
                    'std' => '#ffffff',
                ],
                [
                    'id' => 'mb_button-color-idle',
                    'name' => esc_html__('Button Idle Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [
                        'defaultColor' => WGL_Globals::get_btn_color_idle(),
                    ],
                    'std' => WGL_Globals::get_btn_color_idle(),
                ],
                [
                    'id' => 'mb_button-color-hover',
                    'name' => esc_html__('Button Hover Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [
                        'defaultColor' => WGL_Globals::get_btn_color_hover()
                    ],
                    'std' => WGL_Globals::get_btn_color_hover(),
                ],
                [
                    'id' => 'mb_button-bg-color-idle',
                    'name' => esc_html__('Button Background Idle Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [
                        'defaultColor' => WGL_Globals::get_btn_bg_color_idle(),
                    ],
                    'std' => WGL_Globals::get_btn_bg_color_idle(),
                ],
                [
                    'id' => 'mb_button-bg-color-hover',
                    'name' => esc_html__('Button Background Hover Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => [
                        'defaultColor' => WGL_Globals::get_btn_bg_color_hover()
                    ],
                    'std' => WGL_Globals::get_btn_bg_color_hover(),
                ],
                [
                    'name' => esc_html__('Back to Top', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_scroll_up_arrow_color',
                    'name' => esc_html__('Button Arrow Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color'))],
                    'std' => esc_attr(WGL_Framework::get_option('scroll_up_arrow_color')),
                ],
                [
                    'id' => 'mb_scroll_up_bg_color',
                    'name' => esc_html__('Button Background Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_colors_switch', '=', 'custom'],
                        ]],
                    ],
                    'validate' => 'color',
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('scroll_up_bg_color'))],
                    'std' => esc_attr(WGL_Framework::get_option('scroll_up_bg_color')),
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_header_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Header', 'transmax'),
            'post_types' => ['page', 'post', 'portfolio', 'product'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_customize_header_layout',
                    'name' => esc_html__('Header Settings', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('default', 'transmax'),
                        'custom' => esc_html__('custom', 'transmax'),
                        'hide' => esc_html__('hide', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_header_content_type',
                    'name' => esc_html__('Header Template', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax')
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_customize_header',
                    'name' => esc_html__('Template', 'transmax'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_header_content_type', '=', 'custom'],
                        ]],
                    ],
                    'post_type' => 'header',
                    'multiple' => false,
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_header_sticky',
                    'name' => esc_html__('Sticky Header', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom']
                        ]],
                    ],
                    'std' => 1,
                ],
                [
                    'id' => 'mb_sticky_header_content_type',
                    'name' => esc_html__('Sticky Header Template', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_header_sticky', '=', '1'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax')
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_customize_sticky_header',
                    'name' => esc_html__('Template', 'transmax'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_sticky_header_content_type', '=', 'custom'],
                            ['mb_header_sticky', '=', '1'],
                        ]],
                    ],
                    'multiple' => false,
                    'post_type' => 'header',
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_mobile_menu_custom',
                    'name' => esc_html__('Mobile Menu Template', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_header_layout', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax')
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_mobile_menu_header',
                    'name' => esc_html__('Mobile Menu ', 'transmax'),
                    'type' => 'select',
                    'attributes' => [
                        'data-conditional-logic'  =>  [[
                            ['mb_customize_header_layout', '=', 'custom'],
                            ['mb_mobile_menu_custom', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => $menus = wgl_get_custom_menu(),
                    'default' => reset($menus),
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_title_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Page Title', 'transmax'),
            'post_types' => ['page', 'post', 'team', 'portfolio', 'product'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_page_title_switch',
                    'name' => esc_html__('Page Title', 'transmax'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'on' => esc_html__('On', 'transmax'),
                        'off' => esc_html__('Off', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Page Title Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_title_bg_switch',
                    'name' => esc_html__('Use Background Image/Color?', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'std' => true,
                ],
                [
                    'id' => 'mb_page_title_bg',
                    'name' => esc_html__('Background', 'transmax'),
                    'type' => 'wgl_background',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_bg_switch', '=', true ],
                        ]],
                    ],
                    'image' => '',
                    'repeat' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-repeat'] ?? ''),
                    'size' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-size'] ?? ''),
                    'attachment' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-attachment'] ?? ''),
                    'position' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-position'] ?? ''),
                    'color' => esc_attr(WGL_Framework::get_option('page_title_bg_image')['background-color'] ?? ''),
                ],
                [
                    'id' => 'mb_page_title_height',
                    'name' => esc_html__('Min Height', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_bg_switch', '=', true],
                        ]],
                    ],
                    'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'transmax'),
                    'min' => 0,
                    'std' => esc_attr((int) WGL_Framework::get_option('page_title_height')['height']),
                ],
                [
                    'id' => 'mb_page_title_align',
                    'name' => esc_html__('Title Alignment', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('left', 'transmax'),
                        'center' => esc_html__('center', 'transmax'),
                        'right' => esc_html__('right', 'transmax'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('page_title_align')),
                ],
                [
                    'id' => 'mb_page_title_padding',
                    'name' => esc_html__('Paddings Top/Bottom', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr((int) WGL_Framework::get_option('page_title_padding')['padding-top'] ?? ''),
                        'padding-bottom' => esc_attr((int) WGL_Framework::get_option('page_title_padding')['padding-bottom'] ?? ''),
                    ],
                ],
                [
                    'id' => 'mb_page_title_margin',
                    'name' => esc_html__('Margin Bottom', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'margin',
                        'top' => false,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => ['margin-bottom' => esc_attr((int) WGL_Framework::get_option('page_title_margin')['margin-bottom'] ?? '')],
                ],
                [
                    'id' => 'mb_page_title_parallax',
                    'name' => esc_html__('Parallax Switch', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_title_parallax_speed',
                    'name' => esc_html__('Prallax Speed', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_parallax', '=', true],
                            ['mb_page_title_switch', '=', 'on'],
                        ]],
                    ],
                    'step' => 0.1,
                    'std' => 0.3,
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_switch',
                    'name' => esc_html__('Show Breadcrumbs', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'std' => 1,
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_align',
                    'name' => esc_html__('Breadcrumbs Alignment', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_breadcrumbs_switch', '=', '1']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('left', 'transmax'),
                        'center' => esc_html__('center', 'transmax'),
                        'right' => esc_html__('right', 'transmax'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_align')),
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_block_switch',
                    'name' => esc_html__('Breadcrumbs Full Width', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_breadcrumbs_switch', '=', '1']
                        ]],
                    ],
                    'std' => true,
                ],
                [
                    'name' => esc_html__('Page Title Typography', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_title_font',
                    'name' => esc_html__('Page Title Font', 'transmax'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_font')['font-size'] ?? ''),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_font')['line-height'] ?? ''),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_font')['color'] ?? ''),
                    ],
                ],
                [
                    'id' => 'mb_page_title_breadcrumbs_font',
                    'name' => esc_html__('Page Title Breadcrumbs Font', 'transmax'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['font-size']),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['line-height']),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_font')['color']),
                    ],
                ],
                [
                    'name' => esc_html__('Responsive Layout', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_title_resp_switch',
                    'name' => esc_html__('Responsive Layout On/Off', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_page_title_resp_resolution',
                    'name' => esc_html__('Screen breakpoint', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'min' => 1,
                    'std' => esc_attr(WGL_Framework::get_option('page_title_resp_resolution')),
                ],
                [
                    'id' => 'mb_page_title_resp_padding',
                    'name' => esc_html__('Padding Top/Bottom', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr(WGL_Framework::get_option('page_title_resp_padding')['padding-top']),
                        'padding-bottom' => esc_attr(WGL_Framework::get_option('page_title_resp_padding')['padding-bottom']),
                    ],
                ],
                [
                    'id' => 'mb_page_title_resp_font',
                    'name' => esc_html__('Page Title Font', 'transmax'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_resp_font')['font-size']),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_resp_font')['line-height']),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_resp_font')['color']),
                    ],
                ],
                [
                    'id' => 'mb_page_title_resp_breadcrumbs_switch',
                    'name' => esc_html__('Show Breadcrumbs', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                        ]],
                    ],
                    'std' => 1,
                ],
                [
                    'id' => 'mb_page_title_resp_breadcrumbs_font',
                    'name' => esc_html__('Page Title Breadcrumbs Font', 'transmax'),
                    'type' => 'wgl_font',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_page_title_switch', '=', 'on'],
                            ['mb_page_title_resp_switch', '=', '1'],
                            ['mb_page_title_resp_breadcrumbs_switch', '=', '1'],
                        ]],
                    ],
                    'options' => [
                        'font-size' => true,
                        'line-height' => true,
                        'font-weight' => false,
                        'color' => true,
                    ],
                    'std' => [
                        'font-size' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['font-size']),
                        'line-height' => esc_attr((int) WGL_Framework::get_option('page_title_breadcrumbs_font')['line-height']),
                        'color' => esc_attr(WGL_Framework::get_option('page_title_breadcrumbs_font')['color']),
                    ],
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function page_side_panel_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Side Panel', 'transmax'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_customize_side_panel',
                    'name' => esc_html__('Side Panel', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'inline' => true,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'custom' => esc_html__('Custom', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Side Panel Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_side_panel_building_tool',
                    'name' => esc_html__('Content Type', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'widgets' => esc_html__('Wordpress Widgets', 'transmax'),
                        'elementor' => esc_html__('Elementor', 'transmax')
                    ],
                    'std' => 'widgets',
                ],
                [
                    'id' => 'mb_side_panel_page_select',
                    'name' => esc_html__('Select a page', 'transmax'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'elementor'],
                        ]],
                    ],
                    'post_type' => 'side_panel',
                    'field_type' => 'select_advanced',
                    'placeholder' => esc_html__('Select a page', 'transmax'),
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                ],
                [
                    'id' => 'mb_side_panel_spacing',
                    'name' => esc_html__('Margin', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'margin',
                        'top' => true,
                        'right' => true,
                        'bottom' => true,
                        'left' => true,
                    ],
                    'std' => [
                        'margin-top' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-top'] ?? ''),
                        'margin-right' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-right'] ?? ''),
                        'margin-bottom' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-bottom'] ?? ''),
                        'margin-left' => esc_attr(WGL_Framework::get_option('side_panel_spacing')['margin-left'] ?? ''),
                    ],
                ],
                [
                    'id' => 'mb_side_panel_title_color',
                    'name' => esc_html__('Title Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('side_panel_title_color'))],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_title_color')),
                ],
                [
                    'id' => 'mb_side_panel_text_color',
                    'name' => esc_html__('Text Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'js_options' => ['defaultColor' => WGL_Globals::get_h_font_color()],
                    'std' => WGL_Globals::get_h_font_color(),
                ],
                [
                    'id' => 'mb_side_panel_bg',
                    'name' => esc_html__('Background Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'alpha_channel' => true,
                    'js_options' => ['defaultColor' => esc_attr(WGL_Framework::get_option('side_panel_bg')['rgba'] ?? '')],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_bg')['rgba'] ?? ''),
                ],
                [
                    'id' => 'mb_side_panel_text_alignment',
                    'name' => esc_html__('Text Align', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('Left', 'transmax'),
                        'center' => esc_html__('Center', 'transmax'),
                        'right' => esc_html__('Right', 'transmax'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_text_alignment')),
                ],
                [
                    'id' => 'mb_side_panel_width',
                    'name' => esc_html__('Width', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_side_panel', '=', 'custom'],
                            ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'min' => 50,
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_width')['width'] ?? ''),
                ],
                [
                    'id' => 'mb_side_panel_position',
                    'name' => esc_html__('Position', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                                ['mb_customize_side_panel', '=', 'custom'],
                                ['mb_side_panel_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'left' => esc_html__('Left', 'transmax'),
                        'right' => esc_html__('Right', 'transmax'),
                    ],
                    'std' => esc_attr(WGL_Framework::get_option('side_panel_position')),
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_soc_icons_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Social Shares', 'transmax'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_customize_soc_shares',
                    'name' => esc_html__('Social Shares', 'transmax'),
                    'type' => 'button_group',
                    'inline' => true,
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'on' => esc_html__('On', 'transmax'),
                        'off' => esc_html__('Off', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'id' => 'mb_soc_icon_style',
                    'name' => esc_html__('Socials visibility', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'standard' => esc_html__('Always', 'transmax'),
                        'hovered' => esc_html__('On Hover', 'transmax'),
                    ],
                    'std' => 'standard',
                ],
                [
                    'id' => 'mb_soc_icon_offset',
                    'name' => esc_html__('Offset Top', 'transmax'),
                    'type' => 'number',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                    'min' => 0,
                    'std' => 250,
                ],
                [
                    'id' => 'mb_soc_icon_offset_units',
                    'name' => esc_html__('Offset Top Units', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                    'desc' => esc_html__('If measurement units defined as "%" then social buttons will be fixed relative to viewport.', 'transmax'),
                    'multiple' => false,
                    'options' => [
                        'pixel' => esc_html__('pixels (px)', 'transmax'),
                        'percent' => esc_html__('percents (%)', 'transmax'),
                    ],
                    'std' => 'pixel',
                ],
                [
                    'id' => 'mb_soc_icon_facebook',
                    'name' => esc_html__('Facebook Button', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_twitter',
                    'name' => esc_html__('Twitter Button', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_linkedin',
                    'name' => esc_html__('Linkedin Button', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_pinterest',
                    'name' => esc_html__('Pinterest Button', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_soc_icon_tumblr',
                    'name' => esc_html__('Tumblr Button', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_customize_soc_shares', '=', 'on']
                        ]],
                    ],
                ],
            ]
        ];

        return $meta_boxes;
    }

    public function page_footer_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Footer', 'transmax'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_footer_switch',
                    'name' => esc_html__('Footer', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'on' => esc_html__('On', 'transmax'),
                        'off' => esc_html__('Off', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Footer Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_footer_building_tool',
                    'name' => esc_html__('Layout Building Tool', 'transmax'),
                    'type' => 'button_group',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on']
                        ]],
                    ],
                    'multiple' => false,
                    'options' => [
                        'widgets' => esc_html__('Wordpress Widgets', 'transmax'),
                        'elementor' => esc_html__('Elementor', 'transmax')
                    ],
                    'std' => 'elementor',
                ],
                [
                    'id' => 'mb_footer_page_select',
                    'name' => esc_html__('Select a page', 'transmax'),
                    'type' => 'post',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'elementor']
                        ]],
                    ],
                    'post_type' => 'footer',
                    'field_type' => 'select_advanced',
                    'placeholder' => esc_html__('Select a page', 'transmax'),
                    'query_args' => [
                        'post_status' => 'publish',
                        'posts_per_page' => - 1,
                    ],
                ],
                [
                    'id' => 'mb_footer_spacing',
                    'name' => esc_html__('Paddings', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => true,
                        'bottom' => true,
                        'left' => true,
                    ],
                    'std' => [
                        'padding-top' => '0',
                        'padding-right' => '0',
                        'padding-bottom' => '0',
                        'padding-left' => '0'
                    ],
                ],
                [
                    'id' => 'mb_footer_bg',
                    'name' => esc_html__('Background', 'transmax'),
                    'type' => 'wgl_background',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'widgets'],
                        ]],
                    ],
                    'image' => '',
                    'position' => 'center center',
                    'attachment' => 'scroll',
                    'size' => 'cover',
                    'repeat' => 'no-repeat',
                    'color' => '#ffffff',
                ],
                [
                    'id' => 'mb_footer_add_border',
                    'name' => esc_html__('Add Border Top', 'transmax'),
                    'type' => 'switch',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_building_tool', '=', 'widgets'],
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_footer_border_color',
                    'name' => esc_html__('Border Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_footer_switch', '=', 'on'],
                            ['mb_footer_add_border', '=', '1'],
                        ]],
                    ],
                    'alpha_channel' => true,
                    'js_options' => ['defaultColor' => '#e5e5e5'],
                    'std' => '#e5e5e5',
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function page_copyright_meta_boxes($meta_boxes)
    {
        $meta_boxes[] = [
            'title' => esc_html__('Copyright', 'transmax'),
            'post_types' => ['page'],
            'context' => 'advanced',
            'fields' => [
                [
                    'id' => 'mb_copyright_switch',
                    'name' => esc_html__('Copyright', 'transmax'),
                    'type' => 'button_group',
                    'multiple' => false,
                    'options' => [
                        'default' => esc_html__('Default', 'transmax'),
                        'on' => esc_html__('On', 'transmax'),
                        'off' => esc_html__('Off', 'transmax'),
                    ],
                    'std' => 'default',
                ],
                [
                    'name' => esc_html__('Copyright Settings', 'transmax'),
                    'type' => 'wgl_heading',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                ],
                [
                    'id' => 'mb_copyright_editor',
                    'name' => esc_html__('Editor', 'transmax'),
                    'type' => 'textarea',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                    'cols' => 20,
                    'rows' => 3,
                    'std' => esc_html__('Copyright © 2021 Transmax by WebGeniusLab. All Rights Reserved', 'transmax'),
                ],
                [
                    'id' => 'mb_copyright_text_color',
                    'name' => esc_html__('Text Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                    'js_options' => ['defaultColor' => '#838383'],
                    'std' => '#838383',
                ],
                [
                    'id' => 'mb_copyright_bg_color',
                    'name' => esc_html__('Background Color', 'transmax'),
                    'type' => 'color',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                    'js_options' => ['defaultColor' => '#171a1e'],
                    'std' => '#171a1e',
                ],
                [
                    'id' => 'mb_copyright_spacing',
                    'name' => esc_html__('Paddings', 'transmax'),
                    'type' => 'wgl_offset',
                    'attributes' => [
                        'data-conditional-logic' => [[
                            ['mb_copyright_switch', '=', 'on']
                        ]],
                    ],
                    'options' => [
                        'mode' => 'padding',
                        'top' => true,
                        'right' => false,
                        'bottom' => true,
                        'left' => false,
                    ],
                    'std' => [
                        'padding-top' => esc_attr(WGL_Framework::get_option('copyright_spacing')['padding-top'] ?? ''),
                        'padding-bottom' => esc_attr(WGL_Framework::get_option('copyright_spacing')['padding-bottom'] ?? ''),
                    ],
                ],
            ],
        ];

        return $meta_boxes;
    }
}

new Transmax_Metaboxes();
