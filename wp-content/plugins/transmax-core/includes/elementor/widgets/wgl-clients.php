<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-clients.php`.
 */
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Control_Media,
    Group_Control_Border,
    Group_Control_Box_Shadow,
    Group_Control_Background,
    Repeater,
    Utils
};
use WGL_Extensions\Includes\{
    WGL_Carousel_Settings,
    WGL_Elementor_Helper
};

class WGL_Clients extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-clients';
    }

    public function get_title()
    {
        return esc_html__('WGL Clients', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-clients';
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
            'section_content_general',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'item_grid',
            [
                'label' => esc_html__('Grid Columns Amount', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    1 => esc_html__('1 (one)', 'transmax-core'),
                    2 => esc_html__('2 (two)', 'transmax-core'),
                    3 => esc_html__('3 (three)', 'transmax-core'),
                    4 => esc_html__('4 (four)', 'transmax-core'),
                    5 => esc_html__('5 (five)', 'transmax-core'),
                    6 => esc_html__('6 (six)', 'transmax-core'),
                    7 => esc_html__('7 (seven)', 'transmax-core'),
                ],
                'default' => 1,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            [
                'label' => esc_html__('Thumbnail', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'hover_thumbnail',
            [
                'label' => esc_html__('Hover Thumbnail', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'description' => esc_html__('For \'Toggle Image\' animations only.', 'transmax-core' ),
                'default' => ['url' => ''],
            ]
        );

	    $repeater->add_responsive_control(
		    'thumbnail_width',
		    [
			    'label' => esc_html__('Image/Images Width', 'transmax-core'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => ['min' => 10, 'max' => 500 ],
				    '%' => ['min' => 10, 'max' => 100 ],
			    ],
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}.clients_image img:not(.lazyload),
		             {{WRAPPER}} {{CURRENT_ITEM}}.clients_image img.lazyloaded' => 'width: {{SIZE}}{{UNIT}};',
			    ],
			    'label_block' => true,
		    ]
	    );

        $repeater->add_control(
            'client_link',
            [
                'label' => esc_html__('Add Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Items', 'transmax-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->add_control(
            'item_anim',
            [
                'label' => esc_html__('Thumbnail Animation', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'options' => [
                    'none' => esc_html__('None', 'transmax-core'),
                    'ex_images' => esc_html__('Toggle Image - Fade', 'transmax-core'),
                    'ex_images_ver' => esc_html__('Toggle Image - Vertical', 'transmax-core'),
                    'grayscale' => esc_html__('Grayscale', 'transmax-core'),
                    'opacity' => esc_html__('Opacity', 'transmax-core'),
                    'zoom' => esc_html__('Zoom', 'transmax-core'),
                    'contrast' => esc_html__('Contrast', 'transmax-core'),
                    'blur-1' => esc_html__('Blur 1', 'transmax-core'),
                    'blur-2' => esc_html__('Blur 2', 'transmax-core'),
                    'invert' => esc_html__('Invert', 'transmax-core'),
                ],
                'default' => 'ex_images',
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => esc_html__('Custom Items Height', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['item_anim' => 'ex_images_bg'],
                'range' => [
                    'px' => ['min' => 50, 'max' => 300 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'alignment_h',
            [
                'label' => esc_html__('Horizontal Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => true,
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
                    '{{WRAPPER}} .clients_image' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'alignment_v',
            [
                'label' => esc_html__('Vertical Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'toggle' => true,
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
                    '{{WRAPPER}} .wgl-clients' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}} .swiper-wrapper' => 'align-items: {{VALUE}}; display: flex;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CAROUSEL OPTIONS
         */

        WGL_Carousel_Settings::add_controls($this);

        /**
         * STYLES -> ITEMS
         */

        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__('Items', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs(
            'tabs_items',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_item_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_idle',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .clients_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_idle',
                'selector' => '{{WRAPPER}} .clients_image',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_item_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .clients_image:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_hover',
                'selector' => '{{WRAPPER}} .clients_image:hover',
            ]
        );

        $this->add_control(
            'item_transition',
            [
                'label' => esc_html__('Transition Duration', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['s'],
                'range' => [
                    's' => ['min' => 0, 'max' => 2, 'step' => 0.1 ],
                ],
                'default' => ['size' => 0.4, 'unit' => 's'],
                'selectors' => [
                    '{{WRAPPER}} .clients_image' => 'transition: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * STYLES -> IMAGES
         */

        $this->start_controls_section(
            'section_style_images',
            [
                'label' => esc_html__('Images', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('images');

        $this->start_controls_tab(
            'image_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_idle',
                'selector' => '{{WRAPPER}} .image_wrapper > img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_idle',
                'selector' => '{{WRAPPER}} .image_wrapper > img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'image_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow_hover',
                'selector' => '{{WRAPPER}} .image_wrapper:hover > img',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border_hover',
                'selector' => '{{WRAPPER}} .image_wrapper:hover > img',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render()
    {
        extract($this->get_settings_for_display());

        $this->add_render_attribute(
            'wrapper',
            [
                'class' => [
                    'wgl-clients',
                    'clearfix',
                    'anim-' . $item_anim,
                    'items-' . $item_grid,
                ],
                'data-carousel' => $use_carousel
            ]
        );

        // Render
        echo '<div ', $this->get_render_attribute_string('wrapper'), '>',
            $this->get_clients_html(),
        '</div>';
    }

    protected function get_clients_html()
    {
        extract($this->get_settings_for_display());

        $content = '';
        foreach ($list as $index => $item) {

            $has_link = !empty($item['client_link']['url']);

            if ($has_link) {
                $client_link = $this->get_repeater_setting_key('client_link', 'list', $index);
                $this->add_render_attribute($client_link, 'class', 'image_link image_wrapper');
                $this->add_link_attributes($client_link, $item['client_link']);
            }

            $client_image_idle = $this->get_repeater_setting_key('thumbnail', 'list', $index);
            $this->add_render_attribute($client_image_idle, [
                'class' => 'main_image',
                'alt' => Control_Media::get_image_alt($item['thumbnail']),
            ]);
            $url_idle = $item['thumbnail']['url'] ?? false;
            if ($url_idle) {
                $this->add_render_attribute($client_image_idle, 'src', esc_url($url_idle));
            }

            $client_image_hover = $this->get_repeater_setting_key('hover_thumbnail', 'list', $index);
            $this->add_render_attribute($client_image_hover, [
                'class' => 'hover_image',
                'alt' => Control_Media::get_image_alt($item['hover_thumbnail']),
            ]);
            $url_hover = $item['hover_thumbnail']['url'] ?? false;
            if ($url_hover) {
                $this->add_render_attribute($client_image_hover, 'src', esc_url($url_hover));
            }

            ob_start();

            echo '<div class="clients_image elementor-repeater-item-'. $item['_id'] . (($use_carousel) ? ' swiper-slide' : '') .'">';

                echo $has_link
                    ? '<a ' . $this->get_render_attribute_string($client_link) . '>'
                    : '<div class="image_wrapper">';

                    if (
                        $url_hover
                        && ($item_anim == 'ex_images' || $item_anim == 'ex_images_bg' || $item_anim == 'ex_images_ver')
                    ) {
                        echo '<img ', $this->get_render_attribute_string($client_image_hover), ' />';
                    }

                    echo '<img ', $this->get_render_attribute_string($client_image_idle), ' />';

                echo $has_link
                    ? '</a>'
                    : '</div>';

            echo '</div>';

            $content .= ob_get_clean();
        }

        return !$use_carousel ? $content : $this->apply_carousel_settings($content);
    }

    protected function apply_carousel_settings($content)
    {
        extract($this->get_settings_for_display());

        $options = [
            'slides_per_row' => $item_grid,
            'autoplay' => $autoplay,
            'autoplay_speed' => $autoplay_speed,
            'fade_animation' => $fade_animation,
            'slider_infinite' => $slider_infinite,
            'slide_per_single'  => $slide_per_single,
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

        return WGL_Carousel_Settings::init($options, $content);
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
