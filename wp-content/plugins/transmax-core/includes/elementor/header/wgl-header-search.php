<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;
use Elementor\{Widget_Base, Controls_Manager, Group_Control_Typography};

/**
 * Search widget for Header CPT
 *
 *
 * @category Class
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Search extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-header-search';
    }

    public function get_title()
    {
        return esc_html__('WGL Search', 'transmax-core');
    }

    public function get_icon()
    {
        return 'wgl-header-search';
    }

    public function get_categories()
    {
        return ['wgl-header-modules'];
    }

    public function get_script_depends()
    {
        return [ 'wgl-widgets' ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'transmax-core')]
        );

        $this->add_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}} .wgl-search' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'height_full',
            [
                'label' => esc_html__('Full Height', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
                'default' => 'yes',
                'prefix_class' => 'full-height-',

            ]
        );

        $this->add_control(
            'height_custom',
            [
                'label' => esc_html__('Height', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['height_full' => ''],
                'min' => 0,
                'default' => 55,
                'selectors' => [
                    '{{WRAPPER}} .header_search' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'search_style',
            [
                'label' => esc_html__('Choose Search Style', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'standard' => esc_html__( 'Standard', 'transmax-core' ),
                    'alt'    => esc_html__( 'Full Page Width', 'transmax-core' ),
                ],
                'default'              => 'standard',
            ]
        );

        $this->add_control(
            'search_post_type',
            [
                'label' => esc_html__('Search Post Types', 'transmax-core'),
                'type' => Controls_Manager::SELECT2,
                'options' => self::post_type_options(),
                'multiple' => true,
                'default' => '',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_general_style',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'input_typo',
                'selector' => '{{WRAPPER}} .header_search.search_standard .header_search-field .search-field',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> SEARCH ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_search',
            [
                'label' => esc_html__('Search Icon', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'search',
                'selector' => '{{WRAPPER}} .header_search-button, {{WRAPPER}} .header_search-close',
                'exclude' => ['font_family', 'text_transform', 'font_style', 'text_decoration', 'letter_spacing'],
            ]
        );

        $this->start_controls_tabs(
            'icon',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_icon_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .header_search .wgl-search' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search:hover .header_search-button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'search_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wgl-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'search_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .header_search,
                     {{WRAPPER}} .wgl-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> CLOSE ICON
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_close',
            [
                'label' => esc_html__('Close Icon', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'close_typo',
                'selector' => '{{WRAPPER}} .search_standard .header_search-close',
                'exclude' => ['font_family', 'text_transform', 'font_style', 'text_decoration', 'letter_spacing'],
            ]
        );

        $this->start_controls_tabs(
            'icon_close',
            ['separator' => 'before']
        );

        $this->start_controls_tab(
            'tab_icon_close_idle',
            ['label' => esc_html__('Idle' , 'transmax-core')]
        );

        $this->add_control(
            'icon_close_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'icon_close_bg_idle',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_close_hover',
            ['label' => esc_html__('Hover' , 'transmax-core')]
        );

        $this->add_control(
            'icon_close_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_close_bg_hover',
            [
                'label' => esc_html__('Background Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'icon_close_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .search_standard .header_search-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public static function post_type_options()
    {
        $args = array(
            'public'   => true,
            '_builtin' => false,
        );
        $output = 'names';
        $operator = 'and';
        $content = [
            '' => esc_html__('Default', 'transmax-core'),
            'post' => 'post',
            'page' => 'page',
        ];
        $post_types = get_post_types($args, $output, $operator);
        foreach ($post_types  as $post_type) {
            $content[$post_type] = $post_type;
        }

        return $content ?? [];
    }

    public function render()
    {
        $_s = $this->get_settings_for_display();
        $description = esc_html__('Type To Search', 'transmax-core');
        $search_style = $_s['search_style'] ?? 'standard';
        $search_counter = null;
        $unique_id = uniqid('search-form-');

        if (class_exists('\WGL_Extensions_Get_Header')) {
            $search_counter = \WGL_Extensions_Get_Header::$search_form_counter ?? null;
        }

        $search_class = ' search_' . $_s['search_style'];

        $render_search = true;
        if ($search_style === 'alt') {
            // the only search form in Default and Sticky headers is allowed
            $render_search = $search_counter > 0 ? false : true;

            if (isset($search_counter)) \WGL_Extensions_Get_Header::$search_form_counter++;
        }

        $inputs = '';
        if (!empty($_s['search_post_type'])) {
            if (count($_s['search_post_type']) === 1) {
                $inputs .= '<input type="hidden" name="post_type" value="'.$_s['search_post_type'][0].'" />';
            } else{
                foreach ($_s['search_post_type'] as $key => $value) {
                    $inputs .= '<input type="hidden" name="post_type[]" value="'.$value.'" />';
                }
            }
        }

        $this->add_render_attribute('search', 'class', 'wgl-search elementor-search header_search-button-wrapper');
        $this->add_render_attribute('search', 'role', 'button'); ?>

        <div class="header_search<?php echo esc_attr($search_class); ?>">
	        <div <?php echo $this->get_render_attribute_string('search'); ?>>
	            <div class="header_search-button flaticon-search-interface-symbol"></div>
	            <div class="header_search-close flaticon-close-1"></div>
	        </div><?php

	        if ($render_search) { ?>
	            <div class="header_search-field"><?php
	            if ($search_style === 'alt') { ?>
	                <div class="header_search-wrap">
	                    <div class="wgl_theme_module_double_headings aleft">
	                    <h3 class="header_search-heading_description heading_title"><?php
	                        echo apply_filters('wgl_theme/search/description', $description); ?>
	                    </h3>
	                    </div>
	                    <div class="header_search-close flaticon-close-1"></div>
	                </div><?php
	            } else {
                    echo '<div class="header_search-close flaticon-close-1"></div>';
                }
	            // search form
                echo '<form role="search" method="get" action="', esc_url(home_url('/')), '" class="search-form">',
                    '<input',
                        ' required',
                        ' type="text"',
                        ' id="', esc_attr($unique_id), '"',
                        ' class="search-field"',
                        ' placeholder="', esc_attr_x('Search &hellip;', 'placeholder', 'transmax-core'), '"',
                        ' value="', get_search_query(), '"',
                        ' name="s"',
                        '>',
                    '<input class="search-button" type="submit" value="', esc_attr__('Search', 'transmax-core'), '">',
                    $inputs;
                    echo '<i class="search__icon flaticon-search-interface-symbol"></i>',
                '</form>'; ?>
	            </div><?php
	        }?>
        </div><?php
    }
}
