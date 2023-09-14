<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/widgets/wgl-contact-form-7.php`.
 */
namespace WGL_Extensions\Widgets;

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Border,
    Group_Control_Typography,
    Group_Control_Box_Shadow,
    Group_Control_Background
};


defined( 'ABSPATH' ) || exit; // Abort, if called directly.

class WGL_Contact_Form_7 extends Widget_Base {

    public function get_name() {
        return 'wgl-contact-form-7';
    }

    public function get_title() {
        return esc_html__('WGL Contact Form 7', 'transmax-core');
    }

    public function get_icon() {
        return 'wgl-contact-form-7';
    }

    public function get_categories() {
        return ['wgl-modules'];
    }

    protected function get_availbale_forms() {

		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return [];
		}

		$forms = \WPCF7_ContactForm::find( [
			'orderby' => 'title',
			'order'   => 'ASC',
        ] );

		if ( empty( $forms ) ) {
			return [];
		}

		$result = [];

		foreach ( $forms as $item ) {
			$key            = sprintf( '%1$s::%2$s', $item->id(), $item->title() );
			$result[ $key ] = $item->title();
		}

		return $result;
	}

    protected function register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  CONTENT -> GENERAL
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'section_content_general',
            [
                'label' => esc_html__('General', 'transmax-core'),
            ]
        );

        $avaliable_forms = $this->get_availbale_forms();

		$active_form = '';

		if ( !empty( $avaliable_forms ) ) {
			$active_form = array_keys( $avaliable_forms )[0];
		}

		$this->add_control(
            'form_shortcode',
            [
                'label'   => esc_html__('Select Form', 'transmax-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => $active_form,
                'options' => $avaliable_forms,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'general_typo',
                'selector' => '{{WRAPPER}} .wpcf7-form'
            ]
        );

        $this->add_control(
            'general_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'additional_color',
            [
                'label' => esc_html__('Additional Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} input[type=\'radio\'] + label:before,
                    {{WRAPPER}} input[type=\'radio\'] + span:before,
                    {{WRAPPER}} input[type=\'checkbox\'] + label:before,
                    {{WRAPPER}} input[type=\'checkbox\'] + span:before' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                    ]
                ],
                'default' => 'left',
                'prefix_class' => 'a%s',
            ]
        );

        $this->add_control(
            'form_inline',
            [
                'label' => esc_html__('Form Inline', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'transmax-core'),
                'label_off' => esc_html__('no', 'transmax-core'),
                'separator' => 'before',
                'selectors'    => [
					'{{WRAPPER}} .wpcf7-form-control-wrap, {{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'display: inline-block; vertical-align: top;',
                ]
            ]
        );

        $this->add_responsive_control(
            'form_inline_width',
            [
                'label' => esc_html__('Inputs Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'condition' => [
                    'form_inline!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'inputs_wrap_margin',
            [
                'label' => esc_html__('Inputs Wrap Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'condition' => [
                    'form_inline!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
		/*  STYLE -> INPUTS
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_inputs',
			[
				'label' => esc_html__('Inputs', 'transmax-core'),
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'inputs_typo',
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)'
            ]
        );

        $this->add_responsive_control(
            'inputs_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'inputs_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'inputs_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
			'inputs_height',
			[
				'label'       => esc_html__('Inputs Height', 'transmax-core'),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [
					'{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file):not(.wpcf7-textarea)' => 'height: {{VALUE}}px; min-height: {{VALUE}}px;',
                ],
                'min' => 30,
				'separator' => 'before'
            ]
		);

        $this->add_responsive_control(
			'textarea_height',
			[
				'label'       => esc_html__('Textarea Height', 'transmax-core'),
				'type'        => Controls_Manager::NUMBER,
				'selectors'   => [
					'{{WRAPPER}} .wpcf7-form-control.wpcf7-textarea' => 'height: {{VALUE}}px; min-height: {{VALUE}}px;',
                ],
                'min' => 60
            ]
		);

        $this->start_controls_tabs(
            'inputs_colors_tabs',
            ['separator' => 'before']
        );
        // Idle
        $this->start_controls_tab(
            'inputs_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'inputs_idle_bg',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)'
            ]
        );

        $this->add_control(
            'inputs_idle_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'inputs_idle_border',
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)'
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'inputs_idle_shadow',
			    'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file)'
		    ]
	    );

        $this->end_controls_tab();
        //focus
        $this->start_controls_tab(
            'inputs_focus',
            ['label' => esc_html__('Focus', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'inputs_focus_bg',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file):focus'
            ]
        );

        $this->add_control(
            'inputs_focus_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file):focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'inputs_focus_border',
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file):focus'
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'inputs_focus_shadow',
			    'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file):focus'
		    ]
	    );

        $this->end_controls_tab();
        //not valid
        $this->start_controls_tab(
            'inputs_not_valid',
            ['label' => esc_html__('Not Valid', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'inputs_not_valid_bg',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file).wpcf7-not-valid'
            ]
        );

        $this->add_control(
            'inputs_not_valid_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file).wpcf7-not-valid' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'inputs_not_valid_border',
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file).wpcf7-not-valid'
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'inputs_not_valid_shadow',
			    'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-acceptance):not(.wpcf7-file).wpcf7-not-valid'
		    ]
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
		/*  STYLE -> SUBMIT
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_submit',
			[
				'label' => esc_html__('Submit Button', 'transmax-core'),
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'submit_typo',
                'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit'
            ]
        );

        $this->add_responsive_control(
            'submit_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'height: auto; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_width',
            [
                'label'       => esc_html__('Button min Width', 'transmax-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'min-width: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'submit_fullwidth',
            [
                'label' => esc_html__('Full Width', 'transmax-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'transmax-core'),
                'label_off' => esc_html__('no', 'transmax-core'),
                'selectors'    => [
					'{{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'width: 100%;',
                ]
            ]
        );

        $this->start_controls_tabs(
            'submit_colors_tabs',
            ['separator' => 'before']
        );
        // Idle
        $this->start_controls_tab(
            'submit_idle',
            ['label' => esc_html__('Idle', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submit_idle_bg',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit'
            ]
        );

        $this->add_control(
            'submit_idle_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_idle_border',
                'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit'
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'submit_idle_shadow',
			    'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit'
		    ]
	    );

        $this->end_controls_tab();
        //hover
        $this->start_controls_tab(
            'submit_hover',
            ['label' => esc_html__('Hover', 'transmax-core')]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'submit_hover_bg',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit:hover'
            ]
        );

        $this->add_control(
            'submit_hover_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit:hover' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'submit_hover_border',
                'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit:hover'
            ]
        );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name' => 'submit_hover_shadow',
			    'selector' => '{{WRAPPER}} .wpcf7-form-control.wpcf7-submit:hover'
		    ]
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
		/*  STYLE -> NOT VALID TIP
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_tip',
			[
				'label' => esc_html__('Not Valid Tip', 'transmax-core'),
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tip_typo',
                'selector' => '{{WRAPPER}} .wpcf7-not-valid-tip'
            ]
        );

        $this->add_control(
            'tip_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-not-valid-tip' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'tip_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-not-valid-tip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'tip_alignment',
            [
                'label' => esc_html__('Alignment', 'transmax-core'),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
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
                    ]
                ],
                'selectors'  => [
					'{{WRAPPER}} .wpcf7-not-valid-tip' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
		/*  STYLE -> RESPONSE
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_style_response',
			[
				'label' => esc_html__('Alert', 'transmax-core'),
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'response_typo',
                'selector' => '{{WRAPPER}} .wpcf7-response-output'
            ]
        );

        $this->add_responsive_control(
            'response_margin',
            [
                'label' => esc_html__('Margin', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'response_padding',
            [
                'label' => esc_html__('Padding', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_responsive_control(
            'response_border_radius',
            [
                'label' => esc_html__('Border Radius', 'transmax-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'response_bg',
                'label' => esc_html__('Background', 'transmax-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wpcf7-response-output'
            ]
        );

        $this->add_control(
            'response_color',
            [
                'label' => esc_html__('Color', 'transmax-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-response-output' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $avaliable_forms = $this->get_availbale_forms();

		$shortcode = $this->get_settings( 'form_shortcode' );

		if ( !array_key_exists( $shortcode, $avaliable_forms ) ) {
			$shortcode = array_keys( $avaliable_forms )[0];
		}

		$data = explode( '::', $shortcode );

		if ( !empty( $data ) && 2 === count( $data ) ) {
            echo '<div class="wgl-contact-form-7">';
			    echo do_shortcode( sprintf( '[contact-form-7 id="%1$d" title="%2$s"]', $data[0], $data[1] ) );
            echo '</div>';
		}

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