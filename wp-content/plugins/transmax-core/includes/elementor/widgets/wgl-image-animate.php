<?php
namespace WGL_Extensions\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Repeater;


defined( 'ABSPATH' ) || exit; // Abort, if called directly.

class WGL_Image_Animate extends Widget_Base {

    public function get_name() {
        return 'wgl-image-animate';
    }

    public function get_title() {
        return esc_html__('WGL Image Animate', 'transmax-core');
    }

    public function get_icon() {
        return 'wgl-image-animate';
    }

    public function get_categories() {
        return ['wgl-modules'];
    }

    public function get_script_depends() {
        return [ 'jquery-appear' ];
    }


    protected function register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            array(
                'label' => esc_html__('General', 'transmax-core'),
            )
        );

        $this->add_control(
            'image_link',
            array(
                'label' => esc_html__('Add Image Link', 'transmax-core'),
                'type' => Controls_Manager::URL,
                'label_block' => true,
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'thumbnail',
            array(
                'label' => esc_html__('Thumbnail', 'transmax-core'),
                'type' => Controls_Manager::MEDIA,
                'label_block' => true,
                'default' => [
                    'url' => '',
                ],
            )
        );

        $repeater->add_control(
            'top_offset',
            array(
                'label' => esc_html__('Top Offset', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 1000,
				'step' => 1,
				'default' => '0',
                'description' => esc_html__('Enter offset in %, for example -100% or 100%', 'transmax-core'),
            )
        );

        $repeater->add_control(
            'left_offset',
            array(
                'label' => esc_html__('Left Offset', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 1000,
				'step' => 1,
				'default' => '0',
                'description' => esc_html__('Enter offset in %, for example -100% or 100%', 'transmax-core'),
            )
        );

        $repeater->add_control(
            'image_animation',
            array(
                'label' => esc_html__('Layer Animation', 'transmax-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'transmax-core'),
                    'up_down1' => esc_html__('Up Down 1', 'transmax-core'),
                    'up_down2' => esc_html__('Up Down 2', 'transmax-core'),
                    'up_down3' => esc_html__('Up Down 3', 'transmax-core'),
                    'left_right1' => esc_html__('Left Right 1', 'transmax-core'),
                    'left_right2' => esc_html__('Left Right 2', 'transmax-core'),
                    'left_right3' => esc_html__('Left Right 3', 'transmax-core'),
                    'move1' => esc_html__('Move 1', 'transmax-core'),
                    'move2' => esc_html__('Move 2', 'transmax-core'),
                    'move3' => esc_html__('Move 3', 'transmax-core'),
                    'move4' => esc_html__('Move 4', 'transmax-core'),
                    'move-rotate1' => esc_html__('Move with Rotate 1', 'transmax-core'),
                    'move-rotate2' => esc_html__('Move with Rotate 2', 'transmax-core'),
                    'move-rotate3' => esc_html__('Move with Rotate 3', 'transmax-core'),
                    'move-rotate4' => esc_html__('Move with Rotate 4', 'transmax-core'),
                    'scale1' => esc_html__('Scale 1', 'transmax-core'),
                    'scale2' => esc_html__('Scale 2', 'transmax-core'),
                    'scale3' => esc_html__('Scale 3', 'transmax-core'),
                ],
                'default' => 'up_down',
            )
        );

        $repeater->add_control(
            'anim_duration',
            array(
                'label' => esc_html__('Animation Duration (in sec)', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
				'step' => 0.1,
                'default' => '5',
            )
        );

        $repeater->add_control(
            'image_order',
            array(
                'label' => esc_html__('Image z-index', 'transmax-core'),
                'type' => Controls_Manager::NUMBER,
				'step' => 1,
                'default' => '1',
            )
        );

        $this->add_control(
            'items',
            array(
                'label' => esc_html__('Layers', 'transmax-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            )
        );

        $this->end_controls_section();

    }

    protected function render() {

        wp_enqueue_script('jquery-appear', get_template_directory_uri() . '/js/jquery.appear.js', array(), false, false);

        $content = '';
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('image-animate', 'class', 'wgl-image-animate');

        if (!empty($settings['image_link']['url'])) {
            $this->add_render_attribute('image_link', 'class', 'image_link');
            $this->add_link_attributes('image_link', $settings['image_link']);
        }

        foreach ( $settings[ 'items' ] as $index => $item ) {

            $image_layer = $this->get_repeater_setting_key( 'image_layer', 'items' , $index );
            $this->add_render_attribute( $image_layer, [
                'src' => esc_url($item[ 'thumbnail' ][ 'url' ]),
                'alt' => Control_Media::get_image_alt( $item[ 'thumbnail' ] ),
            ] );

            $image_wrapper = $this->get_repeater_setting_key( 'image_wrapper', 'items' , $index );
            $this->add_render_attribute( $image_wrapper, [
                'class' => [
                    'img-layer_image-wrapper',
                    esc_attr($item[ 'image_animation' ])
                ],
                'style' => 'z-index: '.esc_attr((int)$item[ 'image_order' ]),
            ] );

            $layer_item = $this->get_repeater_setting_key( 'layer_item', 'items' , $index );
            $this->add_render_attribute( $layer_item, [
                'class' => [ 'img-layer_item' ],
                'style' => 'transform: translate('.esc_attr($item[ 'left_offset' ]).'%, '.esc_attr($item[ 'top_offset' ]).'%);'
            ] );

            $layer_image = $this->get_repeater_setting_key( 'layer_image', 'items' , $index );
            $this->add_render_attribute( $layer_image, [
                'class' => [ 'img-layer_image' ],
                'style' => 'animation-duration: '.esc_attr($item[ 'anim_duration' ]).'s;'
            ] );

            ob_start();

            ?><div <?php echo $this->get_render_attribute_string( $image_wrapper ); ?>>
                <div <?php echo $this->get_render_attribute_string( $layer_item ); ?>>
                    <div <?php echo $this->get_render_attribute_string( $layer_image ); ?>>
                        <img <?php echo $this->get_render_attribute_string( $image_layer ); ?> />
                    </div>
                </div>
            </div><?php

            $content .= ob_get_clean();
        }

        ?><div <?php echo $this->get_render_attribute_string( 'image-animate' ); ?>><?php
            if ( !empty($settings[ 'image_link' ][ 'url' ]) ) : ?><a <?php echo $this->get_render_attribute_string( 'image_link' ); ?>><?php endif;
                echo $content;
            if ( !empty($settings[ 'image_link' ][ 'url' ]) ) : ?></a><?php endif;
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