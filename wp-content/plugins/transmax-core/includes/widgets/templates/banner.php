<?php
defined('ABSPATH') || exit;

if (!class_exists( 'WGL_Widgets_Helper')) {
	return;
}

if (!class_exists( 'WGL_Banner_Widget')) {

	class WGL_Banner_Widget extends WGL_Widgets_Helper {

		function create_widget() {
			$args = [
				'label' => esc_html__( 'WGL Banner', 'transmax-core' ),
				'description' => esc_html__( 'WGL Widget', 'transmax-core' ),
			];
			$args['fields'] = $this->form_fields();

			$this->create( $args );
		}
		/**
		 * Front-end display of widget.
		 *
		 * @see WP_Widget::widget()
		 *
		 * @param array $args     Widget arguments.
		 * @param array $instance Saved values from database.
		 */
		public function widget($args, $instance) {
			// KSES Allowed HTML
			$allowed_html = [
				'a' => [
					'href' => true, 'title' => true,
					'class' => true, 'style' => true,
					'rel' => true, 'target' => true
				],
				'br' => ['class' => true, 'style' => true],
				'em' => ['class' => true, 'style' => true],
				'strong' => ['class' => true, 'style' => true],
				'span' => ['class' => true, 'style' => true]
			];

			$logo = $instance['logo'] ?? '';
			$bg_image = $instance['bg_image'] ?? '';
			$padding_top_value = $instance['padding_top'] ?? '';
			$padding_bottom_value = $instance['padding_bottom'] ?? '';
			$subtitle = $instance['subtitle'] ?? false;
			$title = $instance['title'] ?? false;
			$button_text = $instance['button_text'] ?? '';
			$banner_url = $instance['banner_url'] ?? '';

			$widgetClass = $logo_alt = '';

			if ( $logo ) {
				$attachment_id = attachment_url_to_postid( $logo );
				// if no alt attribute is filled out then echo "Featured Image of article: Article Name"
				if ( '' === get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) {
					$logo_alt = the_title_attribute( [
						'before' => esc_html__( 'Featured image: ', 'transmax-core' ),
						'echo' => false
					] );
				} else {
					$logo_alt = trim( strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
				}
			} else {
				$widgetClass = ' without_logotype';
            }

			$wrapper_style = $bg_image ? 'background-image: url('.esc_url($bg_image).');' : '';
			$wrapper_style .= $padding_top_value ? ' padding-top: ' . (int)$padding_top_value. 'px;' : '';
			$wrapper_style .= $padding_bottom_value ? ' padding-bottom: ' . (int)$padding_bottom_value. 'px;' : '';

			// Render ?>
            <div class="transmax_banner-widget transmax_widget widget<?php echo esc_attr($widgetClass); ?>">
            <div class="banner-widget_wrapper" style="<?php echo esc_attr($wrapper_style); ?>"><?php

                if ( $banner_url ) { ?>
                    <a href="<?php echo esc_url( $banner_url ); ?>" class="banner-widget__link"></a><?php
                }

                if ( $logo ) { ?>
                    <div class="banner-widget_img-wrapper"><?php
                        echo '<img class="banner-widget_img" src="' .esc_url($logo). '" alt="' .esc_attr($logo_alt). '">'; ?>
                    </div><?php
                }

                if ( $title ) { ?>
                    <h2 class="banner-widget_text">
	                    <?php echo wp_kses($title, $allowed_html); ?>
                    </h2><?php
				}

                if ( $subtitle ) { ?>
                    <p class="banner-widget_text_sub">
	                    <?php echo wp_kses($subtitle, $allowed_html); ?>
                    </p><?php
                }

                if ( $button_text ) { ?>
                    <div class="banner-widget_button">
                        <span><?php echo wp_kses($button_text, $allowed_html); ?></span>
                    </div><?php
                };
			?></div>
            </div><?php
		}

		/**
		 * Back-end widget form.
		 *
		 * @return array
		 * @see WP_Widget::form()
		 */
		public function form_fields(){
			$args = [
				[
					'name' => esc_html__('Logo image', 'transmax-core'),
					'id' => 'logo',
					'type' => 'media_image',
					'class' => 'widefat wgl_extensions_media_url',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags'
				],
				[
					'name' => esc_html__('Background Image', 'transmax-core'),
					'id' => 'bg_image',
					'type' => 'media_image',
					'class' => 'widefat wgl_extensions_media_url',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags'
				],
				[
					'name' => esc_html__('Padding Top', 'transmax-core'),
					'id' => 'padding_top',
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr'
				],
				[
					'name' => esc_html__('Padding Bottom', 'transmax-core'),
					'id' => 'padding_bottom',
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr'
				],
				[
					'name' => esc_html__('Sub Title', 'transmax-core'),
					'id' => 'subtitle',
					'type' => 'textarea',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_textarea'
				],
				[
					'name' => esc_html__('Title', 'transmax-core'),
					'id' => 'title',
					'type' => 'textarea',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_textarea'
				],
				[
					'name' => esc_html__('Button Text', 'transmax-core'),
					'id' => 'button_text',
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_attr'
				],
				[
					'name' => esc_html__('Banner URL', 'transmax-core'),
					'id' => 'banner_url',
					'type' => 'text',
					'class' => 'widefat',
					'validate' => 'alpha_dash',
					'filter' => 'strip_tags|esc_url'
				],
			];

			return $args;
		}
	}

	function wgl_banner_widget_register() {
		register_widget('WGL_Banner_Widget');
	}

	add_action('widgets_init', 'wgl_banner_widget_register');
}
