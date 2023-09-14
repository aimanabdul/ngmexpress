<?php
/**
 * This template can be overridden by copying it to
 * `transmax[-child]/transmax-core/elementor/widgets/wgl-double-headings.php`.
 */

namespace WGL_Extensions\Widgets;

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

use Elementor\{Group_Control_Background,
	Group_Control_Border,
	Widget_Base,
	Controls_Manager,
	Control_Media,
	Group_Control_Typography,
	Repeater};
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // If this file is called directly, abort.

class Wgl_Combo_Menu extends Widget_Base {

	public function get_name() {
		return 'wgl-combo-menu';
	}

	public function get_title() {
		return esc_html__( 'Wgl Combo Menu', 'transmax-core' );
	}

	public function get_icon() {
		return 'wgl-combo-menu';
	}

	public function get_categories() {
		return [ 'wgl-extensions' ];
	}

	public function get_script_depends() {
		return [
			'appear',
		];
	}

	protected function register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*  Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section( 'wgl_working_section',
			[
				'label' => esc_html__( 'Menu Content', 'transmax-core' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'thumbnail',
			[
				'label' => esc_html__( 'Image', 'transmax-core' ),
				'type' => Controls_Manager::MEDIA,
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'menu_title',
			[
				'label' => esc_html__( 'Title', 'transmax-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Classic Latte', 'transmax-core' ),
			]
		);

		$repeater->add_control(
			'menu_desc',
			[
				'label' => esc_html__( 'Description', 'transmax-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( '2/3 espresso, 1/3 streamed milk', 'transmax-core' ),
			]
		);

		$repeater->add_control(
			'menu_price',
			[
				'label' => esc_html__( 'Price', 'transmax-core' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '$3', 'transmax-core' ),
				'separator' => 'after'
			]
		);

		$repeater->add_control( 'link_item',
			[
				'label' => esc_html__( 'Link', 'transmax-core' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
			]
		);

		$this->add_control(
			'items',
			[
				'label' => esc_html__( 'Menu', 'transmax-core' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'menu_title' => esc_html__( 'Classic Latte', 'transmax-core' ),
						'menu_desc' => esc_html__( '2/3 espresso, 1/3 streamed milk', 'transmax-core' ),
					],
					[
						'menu_title' => esc_html__( 'Americano', 'transmax-core' ),
						'menu_desc' => esc_html__( '2/3 water, 1/3 espresso', 'transmax-core' ),
					],
					[
						'menu_title' => esc_html__( 'Flat White', 'transmax-core' ),
						'menu_desc' => esc_html__( '2/3 streamed milk, 1/3 espresso', 'transmax-core' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{menu_title}}',
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  Style Section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Styles', 'transmax-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Item Styles', 'transmax-core' ),
			]
		);

		$this->add_responsive_control(
			'item_margin',
			[
				'label' => esc_html__( 'Margin', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .menu-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '23',
					'left' => '0',
					'unit'  => 'px',
					'isLinked' => false
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'image_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Image Styles', 'transmax-core' ),
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__('Image Width', 'transmax-core'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => ['min' => 20, 'max' => 300 ],
					'%' => ['min' => 5, 'max' => 80 ],
				],
				'default' => ['size' => 75, 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .main_image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label' => esc_html__( 'Margin', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .menu-item_image-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default' => [
					'top' => '0',
					'right' => '20',
					'bottom' => '0',
					'left' => '0',
					'unit'  => 'px',
					'isLinked' => false
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'title_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Title Styles', 'transmax-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'selector' => '{{WRAPPER}} .menu-item_title',
			]
		);

		$this->start_controls_tabs( 'title_color_tab' );

		$this->start_controls_tab(
			'custom_title_color_normal',
			[
				'label' => esc_html__( 'Normal', 'transmax-core' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'transmax-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-item_title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'custom_title_color_hover',
			[
				'label' => esc_html__( 'Hover', 'transmax-core' ),
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Color', 'transmax-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-item:hover .menu-item_title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .menu-item_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'desc_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Description Styles', 'transmax-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typo',
				'selector' => '{{WRAPPER}} .menu-item_desc',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Color', 'transmax-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#848788',
				'selectors' => [
					'{{WRAPPER}} .menu-item_desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Margin', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '2',
					'right' => '0',
					'bottom' => '0',
					'left' => '2',
					'unit'  => 'px',
					'isLinked' => false
				],
				'selectors' => [
					'{{WRAPPER}} .menu-item_desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'price_styles',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Price Styles', 'transmax-core' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typo',
				'selector' => '{{WRAPPER}} .menu-item_price',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'transmax-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-item_price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'price_margin',
			[
				'label' => esc_html__( 'Margin', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .menu-item_price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'sep_color',
			[
				'label' => esc_html__( 'Separator Between Color', 'transmax-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-item_content:after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  Container Style Section
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'container_style_section',
			[
				'label' => esc_html__( 'Container Styles', 'transmax-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'container_bg',
				'label' => esc_html__('Background', 'transmax-core'),
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}} .wgl-combo-menu',
			]
		);

		$this->add_responsive_control(
			'container_margin',
			[
				'label' => esc_html__( 'Margin', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-combo-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'transmax-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wgl-combo-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'render_type' => 'template',
				'dynamic' => ['active' => true],
				'selector' => '{{WRAPPER}} .wgl-combo-menu',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'combo-menu', [
			'class' => [
				'wgl-combo-menu',
			],
		] );

		?>
        <div <?php echo $this->get_render_attribute_string( 'combo-menu' ); ?>><?php

		foreach ( $settings['items'] as $index => $item ) {

			if ( ! empty( $item['link_item']['url'] ) ) {
				$link_item = $this->get_repeater_setting_key( 'link_item', 'list', $index );
				$this->add_render_attribute( $link_item, 'class', 'menu-item menu-item_link' );
				$this->add_link_attributes( $link_item, $item['link_item'] );
			}

			$menu_image = $this->get_repeater_setting_key( 'thumbnail', 'list', $index );
			$this->add_render_attribute( $menu_image, [
				'class' => 'main_image',
				'src' => esc_url( $item['thumbnail']['url'] ),
				'alt' => Control_Media::get_image_alt( $item['thumbnail'] ),
			] );

			$menu_title = $this->get_repeater_setting_key( 'menu_title', 'items', $index );
			$this->add_render_attribute( $menu_title, [
				'class' => [
					'menu-item_title',
				],
			] );

			$menu_price = $this->get_repeater_setting_key( 'menu_price', 'items', $index );
			$this->add_render_attribute( $menu_price, [
				'class' => [
					'menu-item_price',
				],
			] );

			if ( ! empty( $item['link_item']['url'] ) ) {
				?><a <?php echo $this->get_render_attribute_string( $link_item ); ?>><?php
			} else { ?>
                <div class="menu-item"><?php
			}
			if ( ! empty( $item['thumbnail']['url'] ) ) { ?>
                <div class="menu-item_image-wrap">
                <img <?php echo $this->get_render_attribute_string( $menu_image ); ?> /></div><?php
			} ?>
            <div class="menu-item_content-wrap">
            <div class="menu-item_content"><?php
			if ( ! empty( $item['menu_title'] ) ) {
				?>
                <div <?php echo $this->get_render_attribute_string( $menu_title ); ?>><?php echo esc_html( $item['menu_title'] ); ?></div><?php
			}
			if ( ! empty( $item['menu_price'] ) ) {
				?>
                <div <?php echo $this->get_render_attribute_string( $menu_price ); ?>><?php echo esc_html( $item['menu_price'] ); ?></div><?php
			} ?>
            </div><?php
			if ( ! empty( $item['menu_desc'] ) ) {
				?>
                <div class="menu-item_desc"><?php echo esc_html( $item['menu_desc'] ); ?></div><?php
			} ?>
            </div><?php
			if ( ! empty( $item['link_item']['url'] ) ) { ?>
                </a><?php
			} else { ?>
                </div><?php
			}
		}

		?></div><?php

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