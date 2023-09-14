<?php
namespace WGL_Extensions\Modules;

defined('ABSPATH') || exit;

use Elementor\{Controls_Manager, Group_Control_Background, Group_Control_Typography, Repeater, Plugin, Utils};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * WGL Elementor Section
 *
 *
 * @package wgl-extensions\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Section
{
    public $sections = [];

    public function __construct()
    {
        add_action('elementor/init', [$this, 'add_hooks']);
    }

    public function add_hooks()
    {
        if(!class_exists('WGL_Framework')){
            return;
        }

        // Add WGL extension control section to Section panel
        add_action('elementor/element/section/section_typo/after_section_end', [$this, 'extended_blur_options'], 9, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_blur_options'], 9, 2);
        
        add_action('elementor/element/column/section_typo/after_section_end', [$this, 'extended_blur_options'], 9, 2);
        
        add_action('elementor/element/section/section_typo/after_section_end', [$this, 'extended_animation_options'], 10, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_animation_options'], 10, 2);
        add_action('elementor/element/container/_section_transform/after_section_end', [$this, 'extended_container_sticky_options'], 10, 2);

        add_action('elementor/element/column/layout/after_section_end', [$this, 'extends_column_params'], 10, 2);

        add_action('elementor/frontend/section/before_render', [$this, 'extended_row_render'], 10, 1);
        add_action('elementor/frontend/container/before_render', [$this, 'extended_row_render'], 10, 1);

        add_action('elementor/frontend/column/before_render', [$this, 'extended_column_render'], 10, 1);
        add_action('elementor/frontend/container/before_render', [$this, 'extended_column_render'], 10, 1);

        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_action('elementor/element/wp-page/document_settings/after_section_end', [$this, 'inject_options_page'], 10, 1);
        add_action('elementor/element/wp-post/document_settings/after_section_end', [$this, 'inject_options_post'], 10, 1);

        // Add WGL Editor Style
        if(!class_exists('\ElementorPro\Plugin')){
            add_action('elementor/element/after_section_end', [$this, 'add_controls_section'], 10, 3);
            add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
            add_action('elementor/css-file/post/parse', [ $this, 'add_page_settings_css']);
        }
    }

	public static function add_controls_section($element, $section_id, $args) {
		if ($section_id == 'section_custom_css_pro') {

			$element->remove_control('section_custom_css_pro');
			$element->start_controls_section(
				'section_custom_css',
				[
					'label' => esc_html__( 'WGL Custom CSS', 'wgl-extensions' ),
					'tab' => Controls_Manager::TAB_ADVANCED,
				]
			);

			$element->add_control(
				'custom_css_title',
				[
					'raw' => esc_html__( 'Add your own custom CSS here', 'wgl-extensions' ),
					'type' => Controls_Manager::RAW_HTML,
				]
			);

			$element->add_control(
				'custom_css',
				[
					'type' => Controls_Manager::CODE,
					'label' => esc_html__( 'Custom CSS', 'wgl-extensions' ),
					'language' => 'css',
					'render_type' => 'ui',
					'show_label' => false,
					'separator' => 'none',
				]
			);

			$element->add_control(
				'custom_css_description',
				[
					'raw' => 'Use "selector" to target wrapper element. Examples:<br>selector {color: red;} // For main element<br>selector .child-element {margin: 10px;} // For child element<br>.my-class {text-align: center;} // Or use any custom selector',
					'type' => Controls_Manager::RAW_HTML,
					'content_classes' => 'wgl-elementor-descriptor',
				]
			);

			$element->end_controls_section();
		}
	}

	public function add_post_css($post_css, $element) {
		if ($post_css instanceof Dynamic_CSS) {
			return;
		}

		$element_settings = $element->get_settings();

		if (empty($element_settings['custom_css'])) {
			return;
		}

		$css = trim($element_settings['custom_css']);

		if (empty($css)) {
			return;
		}
		$css = str_replace('selector', $post_css->get_element_unique_selector($element), $css);

		// Add a css comment
		$css = sprintf('/* Start custom CSS for %s, class: %s */', $element->get_name(), $element->get_unique_selector()) . $css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css($css);
	}

	public function add_page_settings_css( $post_css ) {
		$document = \Elementor\Plugin::$instance->documents->get( $post_css->get_post_id() );
		$custom_css = $document->get_settings( 'custom_css' );
		$custom_css = !empty($custom_css) ? trim( $custom_css ) : '';

		if ( empty( $custom_css ) ) {
			return;
		}

		$custom_css = str_replace( 'selector', $document->get_css_wrapper_selector(), $custom_css );

		// Add a css comment
		$custom_css = '/* Start custom CSS for page-settings */' . $custom_css . '/* End custom CSS */';

		$post_css->get_stylesheet()->add_raw_css( $custom_css );
	}

    public function inject_options_post($document)
    {
        if ('header' === get_post_type()) {
            $this->get_header_controls($document);
        } elseif ('side_panel' === get_post_type()){
            $this->get_side_panel_controls($document);
        }
    }

    public function inject_options_page( $document )
    {
        $document->start_controls_section(
            'body_options',
            [
                'label' => esc_html__( 'WGL Main Content Options', 'wgl-extensions' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );

        $document->add_responsive_control(
            'main_content_margin',
            [
                'label' => esc_html__( 'Margin', 'wgl-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #main.site-main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_responsive_control(
            'main_content_padding',
            [
                'label' => esc_html__( 'Padding', 'wgl-extensions' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #main.site-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_responsive_control(
            'z_index',
            [
                'label' => esc_html__( 'Z-Index', 'wgl-extensions' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
                'selectors' => [
                    '{{WRAPPER}} #main.site-main' => 'z-index: {{VALUE}};',
                ],
            ]
        );

        $document->end_controls_section();
    }

    public function get_header_controls($document)
    {
        $document->start_controls_section(
            'header_options',
            [
                'label' => esc_html__('WGL Header Options', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );

        $document->add_control(
            'use_custom_logo',
            [
                'label' => esc_html__('Use Custom Mobile Logo?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $document->add_control(
            'custom_logo',
            [
                'label' => esc_html__('Custom Logo', 'wgl-extensions'),
                'type' => Controls_Manager::MEDIA,
                'condition' => ['use_custom_logo' => 'yes'],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $document->add_control(
            'enable_logo_height',
            [
                'label' => esc_html__('Enable Logo Height?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['use_custom_logo' => 'yes'],
            ]
        );

        $document->add_control(
            'logo_height',
            [
                'label' => esc_html__('Logo Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'use_custom_logo' => 'yes',
                    'enable_logo_height' => 'yes',
                ],
                'min' => 1,
            ]
        );

        $document->add_control(
            'hr_mobile_logo',
            ['type' => Controls_Manager::DIVIDER ]
        );

        $document->add_control(
            'use_custom_menu_logo',
            [
                'label' => esc_html__('Use Custom Mobile Menu Logo?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $document->add_control(
            'custom_menu_logo',
            [
                'label' => esc_html__('Custom Logo', 'wgl-extensions'),
                'type' => Controls_Manager::MEDIA,
                'condition' => ['use_custom_menu_logo' => 'yes'],
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $document->add_control(
            'enable_menu_logo_height',
            [
                'label' => esc_html__('Enable Logo Height?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['use_custom_menu_logo' => 'yes'],
            ]
        );

        $document->add_control(
            'logo_menu_height',
            [
                'label' => esc_html__('Logo Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'use_custom_menu_logo' => 'yes',
                    'enable_menu_logo_height' => 'yes',
                ],
                'min' => 1,
            ]
        );

        $document->add_control(
            'hr_mobile_menu_logo',
            ['type' => Controls_Manager::DIVIDER]
        );

        $document->add_control(
            'mobile_breakpoint',
            [
                'label' => esc_html__('Mobile Header resolution breakpoint', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 1920,
                'default' => 1200,
            ]
        );

        $document->add_control(
            'header_on_bg',
            [
                'label' => esc_html__('Over content', 'wgl-extensions'),
                'description' => esc_html__('Set Header to display over content.', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $document->end_controls_section();
    }

    public function get_side_panel_controls($document)
    {
        $document->start_controls_section(
            'settings_side_panel_options',
            [
                'label' => esc_html__('WGL Side Panel Options', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $document->add_control(
			'sp_container_heading',
			[
				'label' => esc_html__('Side Panel Container', 'wgl-extensions'),
				'type' => Controls_Manager::HEADING,
			]
        );

        $document->add_control(
            'sp_position',
            [
                'label' => esc_html__('Position', 'wgl-extensions'),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wgl-extensions'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'left: 0; right: auto;',
                    'right' => 'left: auto; right: 0;',
                ],
                'default' => 'right',
                'selectors' => [
                    '#side-panel.side-panel' => '{{VALUE}}',
                ],
            ]
        );

        $document->add_control(
            'sp_container_width',
            [
                'label' => esc_html__('Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 500,
                'selectors' => [
                    '#side-panel.side-panel' => 'width: {{VALUE}}px;',
                ],
            ]
        );

        $document->add_responsive_control(
            'sp_container_padding',
            [
                'label' => esc_html__('Padding', 'wgl-extensions'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 50,
                    'left' => 50,
                    'right' => 50,
                    'bottom' => 50,
                ],
                'selectors' => [
                    '#side-panel.side-panel .side-panel_sidebar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_responsive_control(
            'sp_container_margin',
            [
                'label' => esc_html__('Margin', 'wgl-extensions'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '#side-panel.side-panel .side-panel_sidebar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $document->add_control(
            'sp_container_bg',
            [
                'label' => esc_html__('Background Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '#side-panel.side-panel .side-panel_sidebar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $document->end_controls_section();
    }

    function clear_settings_helper($array, $args, $type = false)
    {
        if ($type) {
            switch ($type) {
                case 'background':
                    if (isset($array[$args . '_background'])) {
                        unset($array[$args . '_background']);
                    }
                    if (isset($array[$args . '_transition'])) {
                        unset($array[$args . '_transition']);
                    }
                    if (isset($array[$args . '_image'])) {
                        unset($array[$args . '_image']);
                    }
                    if (isset($array[$args . '_xpos_mobile'])) {
                        unset($array[$args . '_xpos_mobile']);
                    }
                    if (isset($array[$args . '_ypos_mobile'])) {
                        unset($array[$args . '_ypos_mobile']);
                    }
                    if (isset($array[$args . '_size_tablet'])) {
                        unset($array[$args . '_size_tablet']);
                    }
                    if (isset($array[$args . '_bg_width_tablet'])) {
                        unset($array[$args . '_bg_width_tablet']);
                    }
                    if (isset($array[$args . '_position'])) {
                        unset($array[$args . '_position']);
                    }
                    if (isset($array[$args . '_size'])) {
                        unset($array[$args . '_size']);
                    }
                    if (isset($array[$args . '_opacity'])) {
                        unset($array[$args . '_opacity']);
                    }
                    if (isset($array[$args . '_color'])) {
                        unset($array[$args . '_color']);
                    }
                    if (isset($array[$args . '_color_stop'])) {
                        unset($array[$args . '_color_stop']);
                    }
                    if (isset($array[$args . '_color_b'])) {
                        unset($array[$args . '_color_b']);
                    }
                    if (isset($array[$args . '_color_b_stop'])) {
                        unset($array[$args . '_color_b_stop']);
                    }
                    if (isset($array[$args . '_gradient_type'])) {
                        unset($array[$args . '_gradient_type']);
                    }
                    if (isset($array[$args . '_gradient_angle'])) {
                        unset($array[$args . '_gradient_angle']);
                    }
                    if (isset($array[$args . '_gradient_position'])) {
                        unset($array[$args . '_gradient_position']);
                    }
                    if (isset($array[$args . '_xpos'])) {
                        unset($array[$args . '_xpos']);
                    }
                    if (isset($array[$args . '_ypos'])) {
                        unset($array[$args . '_ypos']);
                    }
                    if (isset($array[$args . '_attachment'])) {
                        unset($array[$args . '_attachment']);
                    }
                    if (isset($array[$args . '_repeat'])) {
                        unset($array[$args . '_repeat']);
                    }
                    if (isset($array[$args . '_bg_width'])) {
                        unset($array[$args . '_bg_width']);
                    }
                    if (isset($array[$args . '_video_link'])) {
                        unset($array[$args . '_video_link']);
                    }
                    if (isset($array[$args . '_video_start'])) {
                        unset($array[$args . '_video_start']);
                    }
                    if (isset($array[$args . '_video_end'])) {
                        unset($array[$args . '_video_end']);
                    }
                    if (isset($array[$args . '_play_once'])) {
                        unset($array[$args . '_play_once']);
                    }
                    if (isset($array[$args . '_play_on_mobile'])) {
                        unset($array[$args . '_play_on_mobile']);
                    }
                    if (isset($array[$args . '_privacy_mode'])) {
                        unset($array[$args . '_privacy_mode']);
                    }
                    if (isset($array[$args . '_video_fallback'])) {
                        unset($array[$args . '_video_fallback']);
                    }
                    if (isset($array[$args . '_slideshow_gallery'])) {
                        unset($array[$args . '_slideshow_gallery']);
                    }
                    if (isset($array[$args . '_slideshow_loop'])) {
                        unset($array[$args . '_slideshow_loop']);
                    }
                    if (isset($array[$args . '_slideshow_slide_duration'])) {
                        unset($array[$args . '_slideshow_slide_duration']);
                    }
                    if (isset($array[$args . '_slideshow_slide_transition'])) {
                        unset($array[$args . '_slideshow_slide_transition']);
                    }
                    if (isset($array[$args . '_slideshow_transition_duration'])) {
                        unset($array[$args . '_slideshow_transition_duration']);
                    }
                    if (isset($array[$args . '_slideshow_background_size'])) {
                        unset($array[$args . '_slideshow_background_size']);
                    }
                    if (isset($array[$args . '_slideshow_background_position'])) {
                        unset($array[$args . '_slideshow_background_position']);
                    }
                    if (isset($array[$args . '_slideshow_lazyload'])) {
                        unset($array[$args . '_slideshow_lazyload']);
                    }
                    if (isset($array[$args . '_slideshow_ken_burns'])) {
                        unset($array[$args . '_slideshow_ken_burns']);
                    }
                    if (isset($array[$args . '_slideshow_ken_burns_zoom_direction'])) {
                        unset($array[$args . '_slideshow_ken_burns_zoom_direction']);
                    }
                    break;
                case 'css_filters':
                    if (isset($array[$args . '_css_filter'])) {
                        unset($array[$args . '_css_filter']);
                    }
                    if (isset($array[$args . '_blur'])) {
                        unset($array[$args . '_blur']);
                    }
                    if (isset($array[$args . '_brightness'])) {
                        unset($array[$args . '_brightness']);
                    }
                    if (isset($array[$args . '_contrast'])) {
                        unset($array[$args . '_contrast']);
                    }
                    if (isset($array[$args . '_saturate'])) {
                        unset($array[$args . '_saturate']);
                    }
                    if (isset($array[$args . '_hue'])) {
                        unset($array[$args . '_hue']);
                    }
                    break;
                case 'border':
                    if (isset($array[$args . '_border'])) {
                        unset($array[$args . '_border']);
                    }
                    if (isset($array[$args . '_width'])) {
                        unset($array[$args . '_width']);
                    }
                    if (isset($array[$args . '_color'])) {
                        unset($array[$args . '_color']);
                    }
                    if (isset($array[$args . '_radius'])) {
                        unset($array[$args . '_radius']);
                    }
                    if (isset($array[$args . '_radius_hover'])) {
                        unset($array[$args . '_radius_hover']);
                    }
                    if (isset($array[$args . '_transition'])) {
                        unset($array[$args . '_transition']);
                    }
                    break;
                case 'shadow':
                    if (isset($array[$args . '_box_shadow_type'])) {
                        unset($array[$args . '_box_shadow_type']);
                    }
                    if (isset($array[$args . '_box_shadow'])) {
                        unset($array[$args . '_box_shadow']);
                    }
                    if (isset($array[$args . '_box_shadow_position'])) {
                        unset($array[$args . '_box_shadow_position']);
                    }
                    break;
            }
        } else {
            if (isset($array[$args])) {
                unset($array[$args]);
            }
        }

        return $array;
    }

    function clear_settings_elementor($settings) {

        $settings = $this->clear_settings_helper($settings, 'content_width');
        $settings = $this->clear_settings_helper($settings, 'margin');
        $settings = $this->clear_settings_helper($settings, '_title');
        $settings = $this->clear_settings_helper($settings, 'layout');
        $settings = $this->clear_settings_helper($settings, 'gap');
        $settings = $this->clear_settings_helper($settings, 'gap_columns_custom');
        $settings = $this->clear_settings_helper($settings, 'height');
        $settings = $this->clear_settings_helper($settings, 'custom_height');
        $settings = $this->clear_settings_helper($settings, 'height_inner');
        $settings = $this->clear_settings_helper($settings, 'custom_height_inner');
        $settings = $this->clear_settings_helper($settings, 'column_position');
        $settings = $this->clear_settings_helper($settings, 'content_position');
        $settings = $this->clear_settings_helper($settings, 'overflow');
        $settings = $this->clear_settings_helper($settings, 'stretch_section');
        $settings = $this->clear_settings_helper($settings, 'html_tag');
        $settings = $this->clear_settings_helper($settings, 'structure');
        $settings = $this->clear_settings_helper($settings, 'overlay_blend_mode');
        $settings = $this->clear_settings_helper($settings, 'heading_color');
        $settings = $this->clear_settings_helper($settings, 'color_text');
        $settings = $this->clear_settings_helper($settings, 'color_link');
        $settings = $this->clear_settings_helper($settings, 'color_link_hover');
        $settings = $this->clear_settings_helper($settings, 'text_align');
        $settings = $this->clear_settings_helper($settings, 'padding');
        $settings = $this->clear_settings_helper($settings, 'z_index');
        $settings = $this->clear_settings_helper($settings, '_element_id');
        $settings = $this->clear_settings_helper($settings, 'css_classes');
        $settings = $this->clear_settings_helper($settings, 'animation');
        $settings = $this->clear_settings_helper($settings, 'animation_tablet');
        $settings = $this->clear_settings_helper($settings, 'animation_mobile');
        $settings = $this->clear_settings_helper($settings, 'animation_duration');
        $settings = $this->clear_settings_helper($settings, 'animation_delay');
        $settings = $this->clear_settings_helper($settings, 'reverse_order_tablet');
        $settings = $this->clear_settings_helper($settings, 'reverse_order_mobile');
        $settings = $this->clear_settings_helper($settings, 'hide_desktop');
        $settings = $this->clear_settings_helper($settings, 'hide_tablet');
        $settings = $this->clear_settings_helper($settings, 'hide_mobile');
        $settings = $this->clear_settings_helper($settings, 'custom_css');

        $settings = $this->clear_settings_helper($settings, 'border', 'border');
        $settings = $this->clear_settings_helper($settings, 'border_hover', 'border');

        $settings = $this->clear_settings_helper($settings, 'box_shadow', 'shadow');
        $settings = $this->clear_settings_helper($settings, 'box_shadow_hover', 'shadow');
        
        $settings = $this->clear_settings_helper($settings, 'css_filters', 'css_filters');
        $settings = $this->clear_settings_helper($settings, 'css_filters_hover', 'css_filters');
        
        $settings = $this->clear_settings_helper($settings, 'background', 'background');
        $settings = $this->clear_settings_helper($settings, 'background_hover', 'background');
        $settings = $this->clear_settings_helper($settings, 'background_overlay', 'background');
        $settings = $this->clear_settings_helper($settings, 'background_overlay_hover', 'background');
        return $settings;
    }

    public function extended_row_render(\Elementor\Element_Base $element)
    {
        if (
            'section' !== $element->get_name() 
            && 'container' !== $element->get_name()
        ) {
            return;
        }

        $settings = $element->get_settings();
        $data = $element->get_data();

        $settings = $this->clear_settings_elementor($settings);

        // Background Text Extensions
        if (!empty($settings['add_background_text'])) {
            wp_enqueue_script('jquery-appear', esc_url(get_template_directory_uri() . '/js/jquery.appear.js'));
            wp_enqueue_script('anime', esc_url(get_template_directory_uri() . '/js/anime.min.js'));
        }

        // 3D Wave Extensions
        if (isset($settings['add_wave'])
            && !empty($settings['add_wave'])) {
            wp_enqueue_script('three', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/three.min.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('projector', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/projector.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('canvas-renderer', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/canvas.renderer.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('stats', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/stats.min.js'), [],wp_get_theme()->get('Version') ?? false);
        }

        // Parallax Extensions
        if (
            isset($settings['add_background_animation'])
            && !empty($settings['add_background_animation'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            $scroll_animation = $mouse_animation = $css_animation = false;

            foreach ($settings['items_parallax'] as $k => $v) {
                if('css_animation' === $v['image_effect']){
                    $css_animation = true;
                }elseif('mouse' === $v['image_effect']){
                    $mouse_animation = true;
                }elseif('scroll' === $v['image_effect']){
                    $scroll_animation = true;
                }
            }

            if($css_animation){
	            wp_enqueue_style('animate', esc_url(get_template_directory_uri() . '/css/animate.css'), array('e-animations'));
            }

            if($mouse_animation){
                wp_enqueue_script('parallax', esc_url(get_template_directory_uri() . '/js/parallax.min.js'));
            }

            if($scroll_animation){
                wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));
            }
        }

        // Particles Extensions
        if (
            !empty($settings['add_particles_animation'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', array('jquery'), false, true);
        }

        // Particles Img Extensions
        if (
            !empty($settings['add_particles_img_animation'])
            && !(bool) Plugin::$instance->editor->is_edit_mode()
        ) {
            wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', array('jquery'), false, true);
        }

        $this->sections[$data['id']] = $settings;
    }

    public function extended_column_render(\Elementor\Element_Base $element)
    {
        if (
            'column' !== $element->get_name() 
            && 'container' !== $element->get_name()) 
        {
            return;
        }

        $settings = $element->get_settings();
        $data     = $element->get_data();

        if (isset($settings['apply_sticky_column']) && !empty($settings['apply_sticky_column'])) {

            wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js');
        }
    }

    public function enqueue_scripts()
    {
        if (Plugin::$instance->preview->is_preview_mode()) {
            wp_enqueue_style('animate', esc_url(get_template_directory_uri() . '/css/animate.css'), array('e-animations'));

            wp_enqueue_script('parallax', esc_url(get_template_directory_uri() . '/js/parallax.min.js'));
            wp_enqueue_script('jquery-paroller', esc_url(get_template_directory_uri() . '/js/jquery.paroller.min.js'));

            wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() . '/js/theia-sticky-sidebar.min.js');

            wp_enqueue_script('tsparticles', get_template_directory_uri() . '/js/tsparticles.min.js', ['jquery'], false, true);

            // 3D Wave Extensions
            wp_enqueue_script('three', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/three.min.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('projector', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/projector.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('canvas-renderer', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/canvas.renderer.js'), [],wp_get_theme()->get('Version') ?? false);
            wp_enqueue_script('stats', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/three-js/stats.min.js'), [],wp_get_theme()->get('Version') ?? false);
        }

        // Add options in the section
        wp_enqueue_script('wgl-parallax', esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/wgl_elementor_sections.js'), ['jquery'], false, true);

        wp_localize_script('wgl-parallax', 'wgl_parallax_settings', [
            $this->sections,
            'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
            'svgURL' => esc_url(WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/shapes/'),
            'elementorPro' => class_exists('\ElementorPro\Plugin'),
        ]);
    }

    public function extended_blur_options($widget, $args)
    {
        /**
         * WGL Blur
         */

        $widget->start_controls_section(
            'extended_filter',
            [
                'label' => esc_html__('WGL Blur', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_backdrop_filter',
            [
                'label' => esc_html__('Add Backdrop Blur?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'add-backdrop-filter',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->add_control(
            'section_backdrop_filter',
            [
                'label' => esc_html__('Backdrop Blur', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 30, 'step' => 0.1],
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-backdrop-filter:after' => 'backdrop-filter: blur({{SIZE}}px);-webkit-backdrop-filter: blur({{SIZE}}px);',
                ],
                'condition' => ['add_backdrop_filter!' => ''],
            ]
        );

        $widget->end_controls_section();
    }

    public function extended_animation_options($widget, $args)
    {

        /**
         * BACKGROUND TEXT
         */

        $widget->start_controls_section(
            'extended_animation',
            [
                'label' => esc_html__('WGL Background Text', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_background_text',
            [
                'label' => esc_html__('Add Background Text?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'add-background-text',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->add_control(
            'background_text',
            [
                'label' => esc_html__('Background Text', 'wgl-extensions'),
                'type' => Controls_Manager::TEXTAREA,
                'condition' => ['add_background_text!' => ''],
                'label_block' => true,
                'default' => esc_html__('Text', 'wgl-extensions'),
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'content: "{{VALUE}}"',
                    '{{WRAPPER}} .wgl-background-text' => 'content: "{{VALUE}}"',
                ],
            ]
        );

        $widget->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'background_text_typo',
                'condition' => ['add_background_text' => 'add-background-text'],
                'selector' => '{{WRAPPER}}.wgl-add-background-text:before, {{WRAPPER}} .wgl-background-text',
            ]
        );
	
        $widget->add_responsive_control(
            'background_text_indent',
            [
                'label' => esc_html__('Text Indent', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['add_background_text!' => ''],
                'size_units' => ['px', 'vw'],
                'range' => [
                    'px' => ['min' => -1000, 'max' => 1000],
                    'vw' => ['min' => -100, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .wgl-background-text' => 'transform: translateX(calc({{SIZE}}{{UNIT}} / 2));',
                ],
            ]
        );

        $widget->add_responsive_control(
            'background_text_spacing',
            [
                'label' => esc_html__('Top Spacing', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['add_background_text!' => ''],
	            'range' => [
		            'px' => ['min' => -100, 'max' => 400],
		            'em' => ['min' => -10, 'max' => 20],
		            '%' => ['min' => -100, 'max' => 200],
		            'vw' => ['min' => -100, 'max' => 200],
	            ],
	            'size_units' => ['px', 'em', '%', 'vw'],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wgl-background-text' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
	    $widget->add_responsive_control(
		    'background_text_stroke_size',
		    [
			    'label' => esc_html__('Stroke Width', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['px'],
			    'range' => ['px' => ['min' => 0, 'max' => 10, 'step' => 0.1]],
			    'condition' => ['add_background_text!' => ''],
			    'selectors' => [
				    '{{WRAPPER}}.wgl-add-background-text:before,
				     {{WRAPPER}} .wgl-background-text' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $widget->add_control(
		    'background_text_color',
		    [
			    'label' => esc_html__('Color', 'wgl-extensions'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => ['add_background_text!' => ''],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}}.wgl-add-background-text:before' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .wgl-background-text' => 'color: {{VALUE}};',
			    ],
		    ]
	    );
	
	    $widget->add_control(
		    'background_text_stroke_color',
		    [
			    'label' => esc_html__('Stroke Color', 'wgl-extensions'),
			    'type' => Controls_Manager::COLOR,
			    'condition' => [
				    'add_background_text!' => '',
				    'background_text_stroke_size[size]!' => '',
			    ],
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}}.wgl-add-background-text:before,
				     {{WRAPPER}} .wgl-background-text' => '-webkit-text-stroke-color: {{VALUE}};',
			    ],
		    ]
	    );

        $widget->add_control(
            'apply_animation_background_text',
            [
                'label' => esc_html__('Apply Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => ['add_background_text!' => ''],
                'return_value' => 'animation-background-text',
                'default' => 'animation-background-text',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->add_control(
            'background_text_gradient',
            [
                'label' => esc_html__('Gradient Text', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                ],
                'label_on' => esc_html__('Yes', 'wgl-extensions'),
                'label_off' => esc_html__('No', 'wgl-extensions'),
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before,
				     {{WRAPPER}} .wgl-background-text' => '-webkit-background-clip: text; -webkit-text-fill-color: transparent;',
                ],
            ]
        );

        $widget->add_control(
            'bg_text_color_1',
            [
                'label' => esc_html__('Primary Gradient Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'default' => WGL_Globals::get_primary_color(1),
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-color-1: {{VALUE}}',
                ],
            ]
        );

        $widget->add_control(
            'bg_text_color_2',
            [
                'label' => esc_html__('Secondary Gradient Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'default' => WGL_Globals::get_primary_color(0),
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-color-2: {{VALUE}}',
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_location_1',
            [
                'label' => esc_html__('First Color Location', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [ 'unit' => '%', 'size' => 0 ],
                'render_type' => 'ui',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-location-1: {{SIZE}}%',
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_location_2',
            [
                'label' => esc_html__('Second Color Location', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'default' => [ 'unit' => '%', 'size' => 100 ],
                'render_type' => 'ui',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => '--bg-text-location-2: {{SIZE}}%',
                ],
            ]
        );

        $widget->add_control(
            'bg_text_gradient_type',
            [
                'label' => esc_html__('Type', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'linear' => _x( 'Linear', 'Background Control', 'elementor' ),
                    'radial' => _x( 'Radial', 'Background Control', 'elementor' ),
                ],
                'default' => 'linear',
                'render_type' => 'ui',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => ''
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_gradient_angle',
            [
                'label' => esc_html__('Angle', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'default' => [ 'unit' => 'deg', 'size' => 180 ],
                'range' => [
                    'deg' => [ 'step' => 10 ],
                ],
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => '',
                    'bg_text_gradient_type' => 'linear',
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, var(--bg-text-color-1) var(--bg-text-location-1), var(--bg-text-color-2) var(--bg-text-location-2));',
                ],
            ]
        );

        $widget->add_responsive_control(
            'bg_text_gradient_position',
            [
                'label' => esc_html__('Position', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => _x( 'Center Center', 'wgl-extensions' ),
                    'center left' => _x( 'Center Left', 'wgl-extensions' ),
                    'center right' => _x( 'Center Right', 'wgl-extensions' ),
                    'top center' => _x( 'Top Center', 'wgl-extensions' ),
                    'top left' => _x( 'Top Left', 'wgl-extensions' ),
                    'top right' => _x( 'Top Right', 'wgl-extensions' ),
                    'bottom center' => _x( 'Bottom Center', 'wgl-extensions' ),
                    'bottom left' => _x( 'Bottom Left', 'wgl-extensions' ),
                    'bottom right' => _x( 'Bottom Right', 'wgl-extensions' ),
                ],
                'default' => 'center center',
                'condition' => [
                    'add_background_text!' => '',
                    'apply_animation_background_text' => '',
                    'background_text_gradient!' => '',
                    'bg_text_gradient_type' => 'radial',
                ],
                'selectors' => [
                    '{{WRAPPER}}.wgl-add-background-text:before' => 'background-color: transparent; background-image: radial-gradient(circle at {{VALUE}}, var(--bg-text-color-1) var(--bg-text-location-1), var(--bg-text-color-2) var(--bg-text-location-2));',
                ],
            ]
        );

        $widget->add_control(
            'background_text_index',
            [
                'label' => esc_html__('Z-Index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'separator' => 'before',
                'condition' => ['add_background_text!' => ''],
                'selectors' => [
                    '{{WRAPPER}} .wgl-background-text' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $widget->end_controls_section();

        /**
         * PARALLAX
         */

        $widget->start_controls_section(
            'extended_parallax',
            [
                'label' => esc_html__('WGL Parallax', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_background_animation',
            [
                'label' => esc_html__('Add Extended Background Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image_effect',
            [
                'label' => esc_html__('Parallax Effect', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'scroll' => esc_html__('Scroll', 'wgl-extensions'),
                    'mouse' => esc_html__('Mouse', 'wgl-extensions'),
                    'css_animation' => esc_html__('CSS Animation', 'wgl-extensions'),
                ],
                'default' => 'scroll',
            ]
        );

        $repeater->add_responsive_control(
            'animation_name',
            [
                'label' => esc_html__('Animation', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT2,
                'condition' => ['image_effect' => 'css_animation'],
                'options' => [
                    'bounce' => 'bounce',
                    'flash' => 'flash',
                    'pulse' => 'pulse',
                    'rubberBand' => 'rubberBand',
                    'shake' => 'shake',
                    'swing' => 'swing',
                    'tada' => 'tada',
                    'wobble' => 'wobble',
                    'jello' => 'jello',
                    'bounceIn' => 'bounceIn',
                    'bounceInDown' => 'bounceInDown',
                    'bounceInUp' => 'bounceInUp',
                    'bounceOut' => 'bounceOut',
                    'bounceOutDown' => 'bounceOutDown',
                    'bounceOutLeft' => 'bounceOutLeft',
                    'bounceOutRight' => 'bounceOutRight',
                    'bounceOutUp' => 'bounceOutUp',
                    'fadeIn' => 'fadeIn',
                    'fadeInDown' => 'fadeInDown',
                    'fadeInDownBig' => 'fadeInDownBig',
                    'fadeInLeft' => 'fadeInLeft',
                    'fadeInLeftBig' => 'fadeInLeftBig',
                    'fadeInRightBig' => 'fadeInRightBig',
                    'fadeInUp' => 'fadeInUp',
                    'fadeInUpBig' => 'fadeInUpBig',
                    'fadeOut' => 'fadeOut',
                    'fadeOutDown' => 'fadeOutDown',
                    'fadeOutDownBig' => 'fadeOutDownBig',
                    'fadeOutLeft' => 'fadeOutLeft',
                    'fadeOutLeftBig' => 'fadeOutLeftBig',
                    'fadeOutRightBig' => 'fadeOutRightBig',
                    'fadeOutUp' => 'fadeOutUp',
                    'fadeOutUpBig' => 'fadeOutUpBig',
                    'flip' => 'flip',
                    'flipInX' => 'flipInX',
                    'flipInY' => 'flipInY',
                    'flipOutX' => 'flipOutX',
                    'flipOutY' => 'flipOutY',
                    'lightSpeedIn' => 'lightSpeedIn',
                    'lightSpeedOut' => 'lightSpeedOut',
                    'rotateIn' => 'rotateIn',
                    'rotateInDownLeft' => 'rotateInDownLeft',
                    'rotateInDownRight' => 'rotateInDownRight',
                    'rotateInUpLeft' => 'rotateInUpLeft',
                    'rotateInUpRight' => 'rotateInUpRight',
                    'rotateOut' => 'rotateOut',
                    'rotateOutDownLeft' => 'rotateOutDownLeft',
                    'rotateOutDownRight' => 'rotateOutDownRight',
                    'rotateOutUpLeft' => 'rotateOutUpLeft',
                    'rotateOutUpRight' => 'rotateOutUpRight',
                    'slideInUp' => 'slideInUp',
                    'slideInDown' => 'slideInDown',
                    'slideInLeft' => 'slideInLeft',
                    'slideInRight' => 'slideInRight',
                    'slideOutUp' => 'slideOutUp',
                    'slideOutDown' => 'slideOutDown',
                    'slideOutLeft' => 'slideOutLeft',
                    'slideOutRight' => 'slideOutRight',
                    'zoomIn' => 'zoomIn',
                    'zoomInDown' => 'zoomInDown',
                    'zoomInLeft' => 'zoomInLeft',
                    'zoomInRight' => 'zoomInRight',
                    'zoomInUp' => 'zoomInUp',
                    'zoomOut' => 'zoomOut',
                    'zoomOutDown' => 'zoomOutDown',
                    'zoomOutLeft' => 'zoomOutLeft',
                    'zoomOutUp' => 'zoomOutUp',
                    'hinge' => 'hinge',
                    'rollIn' => 'rollIn',
                    'rollOut' => 'rollOut'
                ],
                'default' => 'fadeIn',
            ]
        );

        $repeater->add_control(
            'animation_name_iteration_count',
            [
                'label' => esc_html__('Animation Iteration Count', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['image_effect' => 'css_animation'],
                'options' => [
                    'infinite' => esc_html__('Infinite', 'wgl-extensions'),
                    '1' => esc_html__('1', 'wgl-extensions'),
                ],
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'animation-iteration-count: {{VALUE}};'
                ],
            ]
        );

        $repeater->add_control(
            'animation_name_speed',
            [
                'label' => esc_html__('Animation speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['image_effect' => 'css_animation'],
                'min' => 1,
                'step' => 100,
                'default' => '1',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'animation-duration: {{VALUE}}s;'
                ],
            ]
        );

        $repeater->add_control(
            'animation_name_direction',
            [
                'label' => esc_html__('Animation Direction', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['image_effect' => 'css_animation'],
                'options' => [
                    'normal' => esc_html__('Normal', 'wgl-extensions'),
                    'reverse' => esc_html__('Reverse', 'wgl-extensions'),
                    'alternate' => esc_html__('Alternate', 'wgl-extensions'),
                ],
                'default' => 'normal',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'animation-direction: {{VALUE}};'
                ],
            ]
        );

        $repeater->add_control(
            'image_bg',
            [
                'label' => esc_html__('Parallax Image', 'wgl-extensions'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => ['url' => ''],
            ]
        );

        $repeater->add_control(
            'parallax_dir',
            [
                'label' => esc_html__('Parallax Direction', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['image_effect' => 'scroll'],
                'options' => [
                    'vertical' => esc_html__('Vertical', 'wgl-extensions'),
                    'horizontal' => esc_html__('Horizontal', 'wgl-extensions'),
                ],
                'default' => 'vertical',
            ]
        );

        $repeater->add_control(
            'parallax_factor',
            [
                'label' => esc_html__('Parallax Factor', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set elements offset and speed. It can be positive (0.3) or negative (-0.3). Less means slower.', 'wgl-extensions'),
                'min' => -3,
                'max' => 3,
                'step' => 0.01,
                'default' => 0.03,
            ]
        );

        $repeater->add_responsive_control(
            'position_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_html__('Set figure vertical offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_html__('Set figure horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',

                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_rotate',
            [
                'label' => esc_html__('Rotate Image', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['deg', 'turn'],
                'range' => [
                    'deg' => ['max' => 360],
                    'turn' => ['min' => 0, 'max' => 1, 'step' => 0.1],
                ],
                'default' => ['unit' => 'deg'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

	    $repeater->add_control(
		    'parallax_opacity',
		    [
			    'label' => esc_html__( 'Opacity', 'wgl-extensions' ),
			    'description' => esc_html__('Set figure opacity from 0 to 1.', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [ 'min' => 0.10, 'max' => 1, 'step' => 0.01 ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'filter: opacity({{SIZE}})',
			    ],
		    ]
	    );

	    $repeater->add_responsive_control(
		    'parallax_image_width',
		    [
			    'label' => esc_html__( 'Image Width', 'wgl-extensions' ),
			    'description' => esc_html__('Set figure width.', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => ['%', 'px'],
			    'range' => [
				    '%' => ['min' => 1, 'max' => 100],
				    'px' => ['min' => 1, 'max' => 1920, 'step' => 1],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'width: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

        $repeater->add_control(
            'parallax_image_maxwidth',
            [
                'label' => esc_html__('Disable Max-Width 100% on Image?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'max-width: none',
                ],
            ]
        );

	    $repeater->add_control(
		    'parallax_image_mask_color',
		    [
			    'label' => esc_html__('Change Color for Image', 'wgl-extensions'),
			    'type' => Controls_Manager::COLOR,
			    'render_type' => 'template',
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}} .wgl_mask_image' => 'background-color: {{VALUE}}',
				    '{{WRAPPER}} {{CURRENT_ITEM}} img' => 'visibility: hidden !important',
			    ],
		    ]
	    );

	    $repeater->add_control(
            'image_index',
            [
                'label' => esc_html__('Image z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'default' => -1,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $repeater->add_control(
            'hide_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $repeater->add_control(
            'hide_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $widget->add_control(
            'items_parallax',
            [
                'label' => esc_html__('Layers', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_background_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();

        $widget->start_controls_section(
            'extended_shape',
            [
                'label' => esc_html__('WGL Shape Divider', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->start_controls_tabs('tabs_wgl_shape_dividers');

        $shapes_options = [
            '' => esc_html__('None', 'wgl-extensions'),
            'torn_line' => esc_html__('Torn Line', 'wgl-extensions'),
        ];

        foreach ([
            'top' => esc_html__('Top', 'wgl-extensions'),
            'bottom' => esc_html__('Bottom', 'wgl-extensions'),
        ] as $side => $side_label) {
            $base_control_key = "wgl_shape_divider_$side";

            $widget->start_controls_tab(
                "tab_$base_control_key",
                [
                    'label' => $side_label,
                ]
            );

            $widget->add_control(
                $base_control_key,
                [
                    'label' => esc_html__('Type', 'wgl-extensions'),
                    'type' => Controls_Manager::SELECT,
                    'options' => $shapes_options,
                ]
            );

            $widget->add_control(
                $base_control_key . '_color',
                [
                    'label' => esc_html__('Color', 'wgl-extensions'),
                    'type' => Controls_Manager::COLOR,
                    'condition' => ["wgl_shape_divider_$side!" => ''],
                    'dynamic' => ['active' => true],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side path" => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $widget->add_responsive_control(
                $base_control_key . '_height',
                [
                    'label' => esc_html__('Height', 'wgl-extensions'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'range' => [
                        'px' => ['max' => 500],
                    ],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side svg" => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $widget->add_control(
                $base_control_key . '_flip',
                [
                    'label' => __('Flip', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side svg" => 'transform: translateX(-50%) rotateY(180deg)',
                    ],
                ]
            );

            $widget->add_control(
                $base_control_key . '_invert',
                [
                    'label' => __('Invert', 'wgl-extensions'),
                    'type' => Controls_Manager::SWITCHER,
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side" => 'transform: rotate(180deg);',
                    ],
                ]
            );

            $widget->add_control(
                $base_control_key . '_above_content',
                [
                    'label' => esc_html__('Z-index', 'wgl-extensions'),
                    'type' => Controls_Manager::NUMBER,
                    'condition' => [ "wgl_shape_divider_$side!" => ''],
                    'default' => 0,
                    'selectors' => [
                        "{{WRAPPER}} > .wgl-elementor-shape-$side" => 'z-index: {{UNIT}}',
                    ],
                ]
            );

            $widget->end_controls_tab();
        }

        $widget->end_controls_tabs();
        $widget->end_controls_section();

        $widget->start_controls_section(
            'extended_particles',
            [
                'label' => esc_html__('WGL Particles', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_particles_animation',
            [
                'label' => esc_html__('Add Particles Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'particles_effect',
            [
                'label' => esc_html__('Style: ', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'one_color' => esc_html__('One Color', 'wgl-extensions'),
                    'random_colors' => esc_html__('Random Colors', 'wgl-extensions'),
                ],
                'default' => 'one_color',
            ]
        );

        $repeater->add_control(
            'particles_color_one',
            [
                'label' => esc_html__('Color 1', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'default' => WGL_Globals::get_primary_color(),
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'particles_color_second',
            [
                'label' => esc_html__('Color 2', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['particles_effect' => 'random_colors'],
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'particles_color_third',
            [
                'label' => esc_html__('Color 3', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'condition' => ['particles_effect' => 'random_colors'],
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'particles_count',
            [
                'label' => esc_html__('Count Of Particles', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
            ]
        );

        $repeater->add_control(
            'particles_max_size',
            [
                'label' => esc_html__('Particles Max Size', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
            ]
        );

        $repeater->add_control(
            'particles_speed',
            [
                'label' => esc_html__('Particles Speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'step' => .1,
                'default' => 2,
            ]
        );

        $repeater->add_control(
            'particles_line',
            [
                'label' => esc_html__('Add Linked Line?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater->add_control(
            'particles_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grab' => esc_html__('Grab', 'wgl-extensions'),
                    'bubble' => esc_html__('Bubble', 'wgl-extensions'),
                    'repulse' => esc_html__('Repulse', 'wgl-extensions'),
                    'none' => esc_html__('None', 'wgl-extensions'),
                ],
                'default' => 'grab',
            ]
        );

        $repeater->add_responsive_control(
            'position_particles_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_html__('Set particles vertical offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'position_particles_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_html__('Set particles horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $repeater->add_control(
            'particles_width',
            [
                'label' => esc_html__('Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set particles container width in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'particles_height',
            [
                'label' => esc_html__('Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set particles container height in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'hide_particles_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $repeater->add_control(
            'hide_particles_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_particles_on_mobile' => 'yes'],
                'default' => 768,
            ]
        );

        $widget->add_control(
            'items_particles',
            [
                'label' => esc_html__('Particles', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_particles_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->end_controls_section();


        $widget->start_controls_section(
            'extended_particles_img',
            [
                'label' => esc_html__('WGL Particles Image', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_particles_img_animation',
            [
                'label' => esc_html__('Add Particles Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'particles_image',
            [
                'label' => esc_html__('Image', 'wgl-extensions'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => ['url' => Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_responsive_control(
            'particles_img_width',
            [
                'label' => esc_html__('Image Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set particles img width in px.', 'wgl-extensions'),
                'min' => 0,
                'max' => 1000,
                'default' => 100,
            ]
        );

        $repeater->add_control(
            'particles_img_height',
            [
                'label' => esc_html__('Image Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set particles img height in px.', 'wgl-extensions'),
                'min' => 0,
                'max' => 1000,
                'default' => 100,
            ]
        );

        $widget->add_control(
            'items_particles_img',
            [
                'label' => esc_html__('Particles Image', 'wgl-extensions'),
                'type' => Controls_Manager::REPEATER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'fields' => $repeater->get_controls(),
            ]
        );

        $widget->add_control(
            'particles_img_color',
            [
                'label' => esc_html__('Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_max_size',
            [
                'label' => esc_html__('Particles Max Size', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'default' => 60,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_count',
            [
                'label' => esc_html__('Count Of Particles', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'default' => 50,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_speed',
            [
                'label' => esc_html__('Particles Speed', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'step' => .1,
                'default' => 2,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_line',
            [
                'label' => esc_html__('Add Linked Line?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_rotate',
            [
                'label' => esc_html__('Add Rotate Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_control(
            'particles_img_rotate_speed',
            [
                'label' => esc_html__('Rotate Speed Animation', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'step' => .1,
                'default' => 5,
                'condition' => [
                    'particles_img_rotate' => 'yes',
                    'add_particles_img_animation' => 'yes',
                ],
            ]
        );

        $widget->add_control(
            'particles_img_hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'wgl-extensions'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grab' => esc_html__('Grab', 'wgl-extensions'),
                    'bubble' => esc_html__('Bubble', 'wgl-extensions'),
                    'repulse' => esc_html__('Repulse', 'wgl-extensions'),
                    'none' => esc_html__('None', 'wgl-extensions'),
                ],
                'default' => 'grab',
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_responsive_control(
            'position_particles_img_top',
            [
                'label' => esc_html__('Top Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_html__('Set particles vertical offset from top border.', 'wgl-extensions'),
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => ['min' => -100, 'max' => 100],
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-particles-img-js' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => ['add_particles_img_animation' => 'yes'],
            ]
        );

        $widget->add_responsive_control(
            'position_particles_img_left',
            [
                'label' => esc_html__('Left Offset', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'description' => esc_html__('Set particles horizontal offset from left border.', 'wgl-extensions'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
                    '%' => ['min' => -100, 'max' => 100],
                ],
                'default' => ['size' => 0, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-particles-img-js' => 'left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $widget->add_control(
            'particles_img_container_width',
            [
                'label' => esc_html__('Width', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'description' => esc_html__('Set particles container width in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $widget->add_control(
            'particles_img_container_height',
            [
                'label' => esc_html__('Height', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'description' => esc_html__('Set particles container height in percent.', 'wgl-extensions'),
                'min' => 0,
                'max' => 100,
                'default' => 100,
            ]
        );

        $widget->add_control(
            'hide_particles_img_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_particles_img_animation' => 'yes'],
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $widget->add_control(
            'hide_particles_img_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_particles_img_on_mobile' => 'yes' , 'add_particles_img_animation' => 'yes'],
                'default' => 768,
            ]
        );

        $widget->end_controls_section();

	    /**
	     * WGL Dynamic Highlights
	     */
	    $widget->start_controls_section(
		    'extended_dynamic_highlights',
		    [
			    'label' => esc_html__('WGL Dynamic Highlights', 'wgl-extensions'),
			    'tab' => Controls_Manager::TAB_STYLE
		    ]
	    );

	    $widget->add_control(
		    'add_dynamic_highlights_animation',
		    [
			    'label' => esc_html__('Add Highlights Animation?', 'wgl-extensions'),
			    'type' => Controls_Manager::SWITCHER,
		    ]
	    );

	    $repeater = new Repeater();

	    $repeater->add_control(
		    'dynamic_highlights_color_first',
		    [
			    'label' => esc_html__('First Color', 'wgl-extensions'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#8d07d526',
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $repeater->add_control(
		    'dynamic_highlights_color_second',
		    [
			    'label' => esc_html__('Secondary Color', 'wgl-extensions'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#ff6c5233',
			    'dynamic' => ['active' => true],
		    ]
	    );

	    $repeater->add_responsive_control(
		    'position_dynamic_highlights_top',
		    [
			    'label' => esc_html__('Top Offset', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'description' => esc_html__('Set Dynamic Highlights offset from top border.', 'wgl-extensions'),
			    'size_units' => ['%', 'px'],
			    'range' => [
				    '%' => ['min' => -100, 'max' => 100],
				    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
			    ],
			    'default' => ['size' => 0, 'unit' => '%'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $repeater->add_responsive_control(
		    'position_dynamic_highlights_left',
		    [
			    'label' => esc_html__('Left Offset', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'description' => esc_html__('Set Dynamic Highlights horizontal offset from left border.', 'wgl-extensions'),
			    'size_units' => ['%', 'px'],
			    'range' => [
				    '%' => ['min' => -100, 'max' => 100],
				    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
			    ],
			    'default' => ['size' => 0, 'unit' => '%'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $repeater->add_responsive_control(
		    'dynamic_highlights_width',
		    [
			    'label' => esc_html__('Size', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'description' => esc_html__('Set Dynamic Highlights container Size', 'wgl-extensions'),
			    'size_units' => ['px'],
			    'range' => [
				    'px' => ['min' => 0, 'max' => 2000, 'step' => 5],
			    ],
			    'default' => ['size' => 500, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'font-size: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $repeater->add_control(
		    'hide_dynamic_highlights_on_mobile',
		    [
			    'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('On', 'wgl-extensions'),
			    'label_off' => esc_html__('Off', 'wgl-extensions'),
			    'default' => 'yes',
		    ]
	    );

	    $repeater->add_control(
		    'hide_dynamic_highlights_mobile_resolution',
		    [
			    'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
			    'type' => Controls_Manager::NUMBER,
			    'condition' => ['hide_dynamic_highlights_on_mobile' => 'yes'],
			    'default' => 768,
		    ]
	    );

	    $repeater->add_control(
		    'dynamic_highlights_index',
		    [
			    'label' => esc_html__('Highlights z-index', 'wgl-extensions'),
			    'type' => Controls_Manager::NUMBER,
			    'default' => -2,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
			    ],
		    ]
	    );

	    $widget->add_control(
		    'items_dynamic_highlights',
		    [
			    'label' => esc_html__('Dynamic Highlights', 'wgl-extensions'),
			    'type' => Controls_Manager::REPEATER,
			    'condition' => ['add_dynamic_highlights_animation' => 'yes'],
			    'fields' => $repeater->get_controls(),
		    ]
	    );

	    $widget->end_controls_section();


	    /**
	     * WGL Morph
	     */
	    $widget->start_controls_section(
		    'extended_morph',
		    [
			    'label' => esc_html__('WGL Morph', 'wgl-extensions'),
			    'tab' => Controls_Manager::TAB_STYLE
		    ]
	    );

	    $widget->add_control(
		    'add_morph_animation',
		    [
			    'label' => esc_html__('Add Morph Animation?', 'wgl-extensions'),
			    'type' => Controls_Manager::SWITCHER,
		    ]
	    );

	    $repeater = new Repeater();

	    $repeater->add_control(
		    'morph_style',
		    [
			    'label' => esc_html__( 'Morph Style', 'wgl-extensions' ),
			    'type' => Controls_Manager::SELECT,
			    'options' => [
				    'style_1' => esc_html__( 'Style 1', 'wgl-extensions' ),
				    'style_2' => esc_html__( 'Style 2', 'wgl-extensions' ),
				    'style_3' => esc_html__( 'Style 3', 'wgl-extensions' ),
			    ],
			    'default' => 'style_1',
		    ]
	    );

	    $repeater->add_control(
		    'morph_transform',
		    [
			    'label' => esc_html__( 'Morph Rotation', 'wgl-extensions' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px' ],
			    'range' => [
				    'px' => [ 'min' => -360, 'max' => 360 ],
			    ],
			    'default' => [ 'size' => 0 ],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'transform: rotate({{SIZE}}deg);',
			    ],
		    ]
	    );

	    $repeater->add_control(
		    'morph_color',
		    [
			    'label' => esc_html__('Morph Color', 'wgl-extensions'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#8d07d526',
			    'dynamic' => ['active' => true],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'fill: {{VALUE}};',
			    ],
		    ]
	    );

	    $repeater->add_control(
		    'morph_animation_speed',
		    [
			    'label' => esc_html__('Animation Speed', 'wgl-extensions'),
			    'type' => Controls_Manager::NUMBER,
			    'min' => 0,
			    'step' => 1,
			    'default' => 10,
		    ]
	    );

	    $repeater->add_responsive_control(
		    'position_morph_top',
		    [
			    'label' => esc_html__('Top Offset', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'description' => esc_html__('Set Morph offset from top border.', 'wgl-extensions'),
			    'size_units' => ['%', 'px'],
			    'range' => [
				    '%' => ['min' => -100, 'max' => 100],
				    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
			    ],
			    'default' => ['size' => 0, 'unit' => '%'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $repeater->add_responsive_control(
		    'position_morph_left',
		    [
			    'label' => esc_html__('Left Offset', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'description' => esc_html__('Set Morph horizontal offset from left border.', 'wgl-extensions'),
			    'size_units' => ['%', 'px'],
			    'range' => [
				    '%' => ['min' => -100, 'max' => 100],
				    'px' => ['min' => -200, 'max' => 1000, 'step' => 5],
			    ],
			    'default' => ['size' => 0, 'unit' => '%'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $repeater->add_responsive_control(
		    'morph_size',
		    [
			    'label' => esc_html__('Size', 'wgl-extensions'),
			    'type' => Controls_Manager::SLIDER,
			    'description' => esc_html__('Set Morph Size', 'wgl-extensions'),
			    'size_units' => ['px', '%'],
			    'range' => [
				    '%' => ['min' => 0, 'max' => 100, 'step' => 1],
				    'px' => ['min' => 0, 'max' => 2000, 'step' => 1],
			    ],
			    'default' => ['size' => 450, 'unit' => 'px'],
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $repeater->add_control(
		    'hide_morph_on_mobile',
		    [
			    'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__('On', 'wgl-extensions'),
			    'label_off' => esc_html__('Off', 'wgl-extensions'),
		    ]
	    );

	    $repeater->add_control(
		    'hide_morph_mobile_resolution',
		    [
			    'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
			    'type' => Controls_Manager::NUMBER,
			    'condition' => ['hide_morph_on_mobile' => 'yes'],
			    'default' => 768,
		    ]
	    );

	    $repeater->add_control(
		    'morph_index',
		    [
			    'label' => esc_html__('Morph z-index', 'wgl-extensions'),
			    'type' => Controls_Manager::NUMBER,
			    'default' => -2,
			    'selectors' => [
				    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'z-index: {{UNIT}};',
			    ],
		    ]
	    );

	    $widget->add_control(
		    'items_morph',
		    [
			    'label' => esc_html__('Morph', 'wgl-extensions'),
			    'type' => Controls_Manager::REPEATER,
			    'condition' => ['add_morph_animation' => 'yes'],
			    'fields' => $repeater->get_controls(),
		    ]
	    );

	    $widget->end_controls_section();

        /**
         * WGL 3D Wave
         */
        $widget->start_controls_section(
            'extended_wave',
            [
                'label' => esc_html__('WGL 3D Wave', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $widget->add_control(
            'add_wave',
            [
                'label' => esc_html__('Add 3D Wave Animation?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $widget->add_control(
            'wave_dots_color',
            [
                'label' => esc_html__('Dots Color', 'wgl-extensions'),
                'type' => Controls_Manager::COLOR,
                'alpha' => false,
                'condition' => ['add_wave!' => ''],
                'default' => '#8d07d5',
                'dynamic' => ['active' => true],
            ]
        );

        $widget->add_control(
            'wave_opacity',
            [
                'label' => esc_html__('Dots Opacity', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'condition' => ['add_wave!' => ''],
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wgl-wave' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $widget->add_control(
            'wave_dots_max_x',
            [
                'label' => esc_html__('Scene Position by X', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => -500, 'max' => 500],
                ],
                'condition' => ['add_wave!' => ''],
                'default' => ['size' => 0],
            ]
        );

        $widget->add_control(
            'wave_dots_max_y',
            [
                'label' => esc_html__('Scene Position by Y', 'wgl-extensions'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => -200, 'max' => 500],
                ],
                'condition' => ['add_wave!' => ''],
                'default' => ['size' => 150],
            ]
        );

        $widget->add_control(
            'wave_mouse_manipulation',
            [
                'label' => esc_html__('Mouse Manipulation', 'wgl-extensions'),
                'description' => esc_html__('For best performance, we recommend disabling animation', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_wave!' => ''],
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $widget->add_control(
            'wave_hide_on_mobile',
            [
                'label' => esc_html__('Hide On Mobile?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['add_wave!' => ''],
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
            ]
        );

        $widget->add_control(
            'wave_hide_mobile_resolution',
            [
                'label' => esc_html__('Screen Resolution', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'add_wave!' => '',
                    'wave_hide_on_mobile!' => ''
                ],
                'default' => 768,
            ]
        );

        $widget->add_control(
            'wave_index',
            [
                'label' => esc_html__('Wave z-index', 'wgl-extensions'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['add_wave!' => ''],
                'default' => -2,
                'selectors' => [
                    '{{WRAPPER}} .wgl-wave' => 'z-index: {{UNIT}};',
                ],
            ]
        );

        $widget->end_controls_section();

        /**
         * WGL Sibling Columns Fade
         */
        $widget->start_controls_section(
            'extended_sibling_columns',
            [
                'label' => esc_html__('WGL Sibling Columns Fade', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $widget->add_control(
            'wgl_sibling_fade_elements',
            [
                'label' => esc_html__('Elements', 'bili-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Disable', 'bili-core'),
                    'animate-columns' => esc_html__('Columns', 'bili-core'),
                    'animate-widgets' => esc_html__('Widgets of Columns', 'bili-core'),
                ],
                'description' => esc_html__('Which Elements will be animated?', 'bili-core'),
                'default' => '',
                'prefix_class' => '',
            ]
        );

        $widget->add_responsive_control(
            'wgl_sibling_elements_fade',
            [
                'label' => esc_html__( 'Sibling Elements Fade', 'wgl-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [ 'px' => ['min' => 0, 'max' => 1, 'step' => 0.01] ],
                'condition' => ['wgl_sibling_fade_elements!' => ''],
                'selectors' => [
                    '{{WRAPPER}}.animate-columns:hover > .elementor-container > .elementor-column:not(:hover),
                     {{WRAPPER}}.animate-widgets:hover > .elementor-container > .elementor-column > .elementor-widget-wrap > .elementor-widget:not(:hover),
                     {{WRAPPER}}.animate-widgets:hover > .elementor-container > .elementor-column > .elementor-widget-wrap > .elementor-inner-section:not(:hover),
                     {{WRAPPER}}.animate-columns:hover > .e-con-inner > .elementor-element:not(:hover),
                     {{WRAPPER}}.animate-widgets:hover > .e-con-inner > .elementor-element .elementor-widget:not(:hover)' => 'opacity: {{SIZE}};'
                ],
            ]
        );
        $widget->end_controls_section();

        /**
         * WGL Opacity by Gradient
         */
        $widget->start_controls_section(
            'extended_opacity_by_gradient',
            [
                'label' => esc_html__('WGL Opacity by Gradient', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );
        $widget->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'extended_opacity_by_gradient_init',
                'types' => ['gradient'],
                'fields_options' => [
                    'background' => [ 'default' => '' ],
                    'color' => [
                        'label' => esc_html__( 'Gradient Color 1', 'wgl-extensions' ),
                        'default' => '#ffffff'
                    ],
                    'color_stop' => [  'default' => [ 'unit' => '%', 'size' => 0 ] ],
                    'color_b' => [
                        'label' => esc_html__( 'Gradient Color 2', 'wgl-extensions' ),
                        'default' => 'transparent'
                    ],
                    'color_b_stop' => [ 'default' => [ 'unit' => '%', 'size' => 75 ] ],
                    'gradient_type' => [ 'default' => 'radial' ],
                    'gradient_angle' => [
                        'default' => [ 'unit' => 'deg', 'size' => 90 ],
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: transparent; -webkit-mask-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '{{SELECTOR}} .elementor-editor-section-settings' => 'transform: translateX(-50%) translateY(0) scaleY(-1);',
                        ],
                    ],
                    'gradient_position' => [
                        'selectors' => [
                            '{{SELECTOR}}' => 'background-color: transparent; -webkit-mask-image: radial-gradient( circle at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
                            '{{SELECTOR}} .elementor-editor-section-settings' => 'transform: translateX(-50%) translateY(0) scaleY(-1);',
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}}',
            ]
        );
        $widget->end_controls_section();
    }

    public function extended_container_sticky_options($widget, $args)
    {
        /**
         * Sticky column for container
         */

        $widget->start_controls_section(
            'extended_sticky',
            [
                'label' => esc_html__('WGL Sticky Column', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $widget->add_control(
            'apply_sticky_column',
            [
                'label' => esc_html__('Enable Sticky?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'return_value' => 'sidebar',
                'prefix_class' => 'sticky-',
                'selectors' => [
                    '{{WRAPPER}}.sticky-sidebar' => 'display: block;',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    public function extends_header_params($widget, $args)
    {
        $widget->start_controls_section(
            'extended_header',
            [
                'label' => esc_html__('WGL Header Layout', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $widget->add_control(
            'apply_sticky_row',
            [
                'label' => esc_html__('Apply Sticky?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'sticky-on',
                'prefix_class' => 'wgl-',
            ]
        );

        $widget->end_controls_section();
    }

    public function extends_column_params( $widget, $args )
    {
        $widget->start_controls_section(
            'extended_header',
            [
                'label' => esc_html__('WGL Column Options', 'wgl-extensions'),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );

        $widget->add_responsive_control(
            'column_overflow',
            [
                'label' => esc_html__( 'Column Overflow', 'wgl-extensions' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'visible' => esc_html__( 'Visible', 'wgl-extensions' ),
                    'hidden' => esc_html__( 'Hidden', 'wgl-extensions' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-element-populated' => 'overflow: {{VALUE}}',
                ],
            ]
        );

        $widget->add_control(
            'apply_sticky_column',
            [
                'label' => esc_html__('Enable Sticky?', 'wgl-extensions'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'wgl-extensions'),
                'label_off' => esc_html__('Off', 'wgl-extensions'),
                'return_value' => 'sidebar',
                'prefix_class' => 'sticky-',
                'selectors' => [
                    '{{WRAPPER}}.sticky-sidebar' => 'display: block;',
                ],
            ]
        );
	
	    $widget->add_responsive_control(
		    'extend_column_order',
		    [
			    'label' => esc_html__( 'Column Order', 'wgl-extensions' ),
			    'type' => Controls_Manager::NUMBER,
			    'min' => -5,
			    'max' => 5,
			    'selectors' => [
				    '{{WRAPPER}}' => 'order: {{VALUE}}',
			    ],
		    ]
	    );

        $widget->add_responsive_control(
            'wgl_sibling_widgets_fade',
            [
                'label' => esc_html__( 'Sibling Widgets Fade', 'wgl-extensions' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}}:hover > .elementor-widget-wrap > .elementor-widget:not(:hover),
                     {{WRAPPER}}:hover > .elementor-widget-wrap > .elementor-inner-section:not(:hover)' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $widget->end_controls_section();
    }
}
