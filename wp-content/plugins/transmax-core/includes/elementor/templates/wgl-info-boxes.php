<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/templates/wgl-info-boxes.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\Icons_Manager;
use WGL_Extensions\Includes\WGL_Icons;

/**
 * WGL Elementor Info Boxes Template
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGLInfoBoxes
{
    private static $instance;

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function render($self, $atts)
    {
        extract($atts);

        $ib_media = $infobox_content = $ib_button = $module_link_html = '';

        $wrapper_classes = $layout ? ' wgl-layout-' . $layout : '';

        $kses_allowed_html = [
            'a' => [
                'href' => true, 'title' => true,
                'class' => true, 'style' => true,
                'rel' => true, 'target' => true
            ],
            'br' => ['class' => true, 'style' => true],
            'em' => ['class' => true, 'style' => true],
            'strong' => ['class' => true, 'style' => true],
            'span' => ['class' => true, 'style' => true],
            'small' => ['class' => true, 'style' => true],
            'p' => ['class' => true, 'style' => true],
        ];

        // Title
        $infobox_title = '<div class="wgl-infobox-title_wrapper">';
        $infobox_title .= !empty($ib_subtitle) ? '<div class="wgl-infobox_subtitle">' . wp_kses($ib_subtitle, $kses_allowed_html) . '</div>' : '';
        $infobox_title .= !empty($ib_title) ? '<' . esc_attr($title_tag) . ' class="wgl-infobox_title">' : '';
        $infobox_title .= !empty($ib_title) ? '<span class="wgl-infobox_title-idle">' . wp_kses($ib_title, $kses_allowed_html) . '</span>' : '';
        $infobox_title .= !empty($ib_title) ? '</' . esc_attr($title_tag) . '>' : '';
        $infobox_title .= '</div>';

        // Content
        if (!empty($ib_content)) {
            $infobox_content = '<' . esc_attr($content_tag) . ' class="wgl-infobox_content">';
            $infobox_content .= $ib_content;
            $infobox_content .= '</' . esc_attr($content_tag) . '>';
        }

	    // BG Text
	    $infobox_bg_text = !empty($ib_bg_text) ? '<div class="wgl-infobox_bg_text_wrapper"><span class="wgl-infobox_bg_text">' . wp_kses($ib_bg_text, $kses_allowed_html) . '</span></div>' : '';

        // Media
        if (!empty($icon_type)) {
            $media = new WGL_Icons;
            $ib_media .= $media->build($self, $atts, []);
        }

        // Link
        if (!empty($link['url'])) {
            $self->add_link_attributes('link', $link);
        }

        // Read more button
        if ($add_read_more) {
            $self->add_render_attribute('btn', 'class', 'wgl-infobox_button');

            if($read_more_icon_fontawesome['value']) {
	            $migrated = isset( $atts['__fa4_migrated']['read_more_icon_fontawesome'] );
	            $is_new = Icons_Manager::is_migration_allowed();
	            if ( $is_new || $migrated ) {
		            $self->add_render_attribute( 'btn', 'class', esc_attr( $read_more_icon_fontawesome['value'] ) );
	            }
            }else{
	            $self->add_render_attribute('btn', 'class', 'button-read-more');
            }

            $ib_button = '<div class="wgl-infobox-button_wrapper">';
            $ib_button .= sprintf(
                '<%s %s %s>',
                $module_link ? 'div' : 'a',
                $module_link ? '' : $self->get_render_attribute_string('link'),
                $self->get_render_attribute_string('btn')
            );
            $ib_button .= $read_more_text ? '<span>' . esc_html($read_more_text) . '</span>' : '';
            $ib_button .= $module_link ? '</div>' : '</a>';
            $ib_button .= '</div>';
        }

        if ($module_link && !empty($link['url'])) {
            $module_link_html = '<a class="wgl-infobox__link" ' . $self->get_render_attribute_string('link') . '></a>';
        }

        // Render
        echo '<div class="wgl-infobox">',
            $module_link_html,
            '<div class="wgl-infobox_wrapper', esc_attr($wrapper_classes), '">',
                $ib_media,
                '<div class="content_wrapper">',
                    $infobox_title,
                    $infobox_content,
                    $infobox_bg_text,
                    $read_more_inline ? '' : $ib_button,
                '</div>',
                $read_more_inline ? $ib_button : '',
            '</div>',
        '</div>';
    }
}
