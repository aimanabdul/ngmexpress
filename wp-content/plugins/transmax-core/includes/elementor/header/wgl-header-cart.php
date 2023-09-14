<?php
namespace WGL_Extensions\Widgets;

defined('ABSPATH') || exit; // Abort, If called directly.

use Elementor\{Plugin, Widget_Base, Controls_Manager};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * Cart widget for Header CPT
 *
 *
 * @category Class
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Header_Cart extends Widget_Base
{
    public function get_name() {
        return 'wgl-header-cart';
    }

    public function get_title() {
        return esc_html__('WooCart', 'transmax-core');
    }

    public function get_icon() {
        return 'wgl-header-cart';
    }

    public function get_categories() {
        return [ 'wgl-header-modules' ];
    }

    public function get_script_depends() {
        return [
            'wgl-widgets',
        ];
    }

    protected function register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_search_settings',
            [ 'label' => esc_html__('General', 'transmax-core') ]
        );

        $this->add_control(
            'cart_height',
            [
                'label' => esc_html__('Cart Icon Height', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .mini-cart' => 'height: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'cart_align',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'toggle' => true,
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
                'selectors' => [
                    '{{WRAPPER}} .wgl-mini-cart_wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  STYLE -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'transmax-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('icon_style_tabs');

        $this->start_controls_tab(
            'tab_idle',
            [ 'label' => esc_html__('Idle' , 'transmax-core') ]
        );

        $this->add_control(
            'icon_color_idle',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart .wgl-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_bg_idle',
            [
                'label' => esc_html__('Items Counter Background', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .woo_mini-count > span' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hover',
            [ 'label' => esc_html__('Hover' , 'transmax-core') ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart:hover .wgl-cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'counter_bg_hover',
            [
                'label' => esc_html__('Items Counter Background', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .mini-cart:hover .woo_mini-count > span' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    public function render()
    {
        if (!class_exists('\WooCommerce')) {
            return;
        }
	    global $wgl_woo_cart;
	    $wgl_woo_cart = true;?>
        <div class="wgl-mini-cart_wrapper">
            <div class="mini-cart woocommerce"><?php
            echo $this->icon_cart();?>
            </div>
        </div><?php
    }

    public function icon_cart()
    {
        ob_start();
        $this->add_render_attribute('cart', 'class', 'wgl-cart woo_icon elementor-cart');
        $this->add_render_attribute('cart', 'role', 'button' );
        $this->add_render_attribute('cart', 'title', esc_attr__('Click to open Shopping Cart', 'transmax-core')); ?>

        <a <?php echo \WGL_Framework::render_html($this->get_render_attribute_string('cart')); ?>>
            <span class="woo_mini-count flaticon flaticon-shopping-bags"><?php
                if ((!(bool) Plugin::$instance->editor->is_edit_mode())) {
                    echo \WooCommerce::instance()->cart->cart_contents_count > 0
                        ? '<span>' . esc_html( \WooCommerce::instance()->cart->cart_contents_count ) .'</span>'
                        : '';
                } ?>
            </span>
        </a><?php

        return ob_get_clean();
    }
}