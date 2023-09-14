<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/templates/wgl-testimonials.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit;

use WGL_Extensions\Includes\WGL_Carousel_Settings;

if (!class_exists('WGL_Testimonials')) {
    /**
     * WGL Elementor Testimonials Template
     *
     *
     * @package transmax-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Testimonials
    {
        private static $instance;
        private $attributes;

        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function render($self, $attributes)
        {
            $this->attributes = $attributes;
            extract($this->attributes);

            switch ($posts_per_row) {
                case '1':
                    $col = 12;
                    break;
                case '2':
                    $col = 6;
                    break;
                case '3':
                    $col = 4;
                    break;
                case '4':
                    $col = 3;
                    break;
                case '5':
                    $col = '1/5';
                    break;
            }

            // Wrapper attributes
            $self->add_render_attribute('wrapper', 'class', [
                'wgl-testimonials',
                'type-' . $layout,
            ]);
            if ($hover_animation) {
                $self->add_render_attribute('wrapper', 'class', 'hover_animation');
            }

            // Image styles
            $image_size = $image_size['size'] ?? '';
            $image_height = $image_height['size'] ?? '';
            $image_width = $image_size ? 'width: ' . $image_size . 'px;' : '';
            $thumbnail_style = $image_width ? ' style="' . $image_width . '"' : '';

            // Build structure
            $items_html =  '';

	        if(!$use_carousel){
		        $items_html .= '<div class="row">';
	        }

            foreach ($items as $item) {

                // Fields validation
                $thumbnail = $item['thumbnail'] ?? '';
                $quote = $item['quote'] ?? '';
                $title = $item['title'] ?? '';
                $author_name = $item['author_name'] ?? '';
                $author_position = $item['author_position'] ?? '';
                $link_author = $item['link_author'] ?? '';

                $has_link = !empty($link_author['url']);

                if ($has_link) {
                    $self->add_link_attributes('link-author', $link_author);
                }

                $name_html = '<' . esc_attr($name_tag) . ' class="author__name">'
                    . ($has_link ? '<a ' . $self->get_render_attribute_string('link-author') . '>' : '')
                    . esc_html($author_name)
                    . ($has_link ? '</a>' : '')
                    . '</' . esc_attr($name_tag) . '>';

                $quote_icon = $quote_icon_enabled ? '<div class="item__icon"></div>' : '';
                $title_html = '<' . esc_attr($title_tag) . ' class="item__title">' . wp_kses($title, self::get_kses_allowed_html()) . '</' . esc_attr($title_tag) . '>';
                $quote_html = '<' . esc_attr($quote_tag) . ' class="item__quote">' . wp_kses($quote, self::get_kses_allowed_html()) . '</' . esc_attr($quote_tag) . '>';

                $position_html = $author_position ? '<' . esc_attr($position_tag) . ' class="author__position">' . esc_html($author_position) . '</' . esc_attr($position_tag) . '>' : '';

                $image_html = '';
                $testimonials_image_src = aq_resize($thumbnail['url'], $image_size, $image_height, true, true, true);
                if (!empty($testimonials_image_src)) {
                    $image_html = '<div class="author__thumbnail">'
                        . ($has_link ? '<a ' . $self->get_render_attribute_string('link-author') . '>' : '')
                        . '<img src="' . esc_url($testimonials_image_src) . '" alt="' . esc_attr($author_name) . ' photo" ' . $thumbnail_style . '>'
                        . ($has_link ? '</a>' : '')
                        . '</div>';
                }

                $items_html .= '<div class="testimonials__wrapper' . (!$use_carousel ? ' wgl_col-' . $col : ' swiper-slide') . '">';

                switch ($layout) {
                    case 'top_block':
                        $items_html .= '<div class="testimonial__item">'
                            . $quote_icon
                            . $image_html
                            . '<div class="item__content">'
                            . $title_html
                            . $quote_html
                            . '</div>'
                            . '<div class="item__author">'
                            . '<div class="author__meta">'
                            . $name_html
                            . $position_html
                            . '</div>'
                            . '</div>'
                            . '</div>';
                        break;

                    case 'bottom_block':
                        $items_html .= '<div class="testimonial__item">'
                            . $quote_icon
                            . '<div class="item__content">'
                            . $title_html
                            . $quote_html
                            . '</div>'
                            . '<div class="item__author">'
                            . $image_html
                            . '<div class="author__meta">'
                            . $name_html
                            . $position_html
                            . '</div>'
                            . '</div>'
                            . '</div>';
                        break;

                    case 'top_inline':
                        $items_html .= '<div class="testimonial__item">'
                            . $quote_icon
                            . '<div class="item__author">'
                            . $image_html
                            . '<div class="author__meta">'
                            . $name_html
                            . $position_html
                            . '</div>'
                            . '</div>'
                            . '<div class="item__content">'
                            . $title_html
                            . $quote_html
                            . '</div>'
                            . '</div>';
                        break;

                    case 'bottom_inline':
                        $items_html .= '<div class="testimonial__item">'
                            . $quote_icon
                            . '<div class="item__content">'
                            . $title_html
                            . $quote_html
                            . '</div>'
                            . '<div class="item__author">'
                            . $image_html
                            . '<div class="author__meta">'
                            . $name_html
                            . $position_html
                            . '</div>'
                            . '</div>'
                            . '</div>';
                        break;
                }
                $items_html .= '</div>';
            }

	        if(!$use_carousel){
		        $items_html .= '</div>';
	        }

	        echo '<div  ', $self->get_render_attribute_string('wrapper'), '>',
                (!$use_carousel ? $items_html : $this->apply_carousel_settings($items_html)),
            '</div>';
        }

        protected function apply_carousel_settings($testimonials_html)
        {
            $options = [
                // General
                'slides_per_row' => $this->attributes['posts_per_row'],
                'animation_style' => $this->attributes['animation_style'],
                'animation_triggered_by_mouse' => $this->attributes['animation_triggered_by_mouse'],
                'autoplay' => $this->attributes['autoplay'],
                'autoplay_speed' => $this->attributes['autoplay_speed'],
	            'slide_per_single' => $this->attributes['slide_per_single'],
                'slider_infinite' => $this->attributes['slider_infinite'],
                'fade_animation' => $this->attributes['fade_animation'],
	            'center_mode' => $this->attributes['center_mode'],
                // Pagination
                'use_pagination' => $this->attributes['use_pagination'],
                'pagination_type' => $this->attributes['pagination_type'],
                // Navigation
                'use_navigation' => $this->attributes['use_navigation'],
                'navigation_position' => $this->attributes['navigation_position'],
                'navigation_view' => $this->attributes['navigation_view'],
                // Responsive
                'customize_responsive' => $this->attributes['customize_responsive'],
	            'desktop_breakpoint' => $this->attributes['desktop_breakpoint'],
                'desktop_slides' => $this->attributes['desktop_slides'],
                'tablet_breakpoint' => $this->attributes['tablet_breakpoint'],
                'tablet_slides' => $this->attributes['tablet_slides'],
                'mobile_breakpoint' => $this->attributes['mobile_breakpoint'],
                'mobile_slides' => $this->attributes['mobile_slides'],
            ];

            return WGL_Carousel_Settings::init($options, $testimonials_html);
        }

        protected static function get_kses_allowed_html()
        {
            return [
                'a' => [
                    'id' => true, 'class' => true, 'style' => true,
                    'href' => true, 'title' => true,
                    'rel' => true, 'target' => true
                ],
                'br' => ['id' => true, 'class' => true, 'style' => true],
                'em' => ['id' => true, 'class' => true, 'style' => true],
                'strong' => ['id' => true, 'class' => true, 'style' => true],
                'span' => ['id' => true, 'class' => true, 'style' => true],
                'p' => ['id' => true, 'class' => true, 'style' => true],
                'ul' => ['id' => true, 'class' => true, 'style' => true],
                'ol' => ['id' => true, 'class' => true, 'style' => true],
            ];
        }
    }
}
