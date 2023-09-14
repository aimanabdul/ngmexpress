<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/templates/wgl-team.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\{
    WGL_Loop_Settings,
    WGL_Carousel_Settings,
    WGL_Elementor_Helper
};
use WGL_Framework;
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

/**
 * WGL Elementor Team Template
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Team
{
    private static $instance;
    private $attributes;

    public function render($attributes)
    {
        $this->attributes = $attributes;

        $wrapper_classes = 'grid-col--' . $attributes['posts_per_row'];
        $wrapper_classes .= !empty($attributes['info_align']) ? ' a' . $attributes['info_align'] : '';

        $query = $this->formalize_query();
        ob_start();
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_member_grid();
            }
            wp_reset_postdata();
        $posts_html = ob_get_clean();

        if ($attributes['use_carousel']) {
            $wrapper_classes .= ' carousel';
            $posts_html = $this->apply_carousel_settings($posts_html);
        }

        echo '<section class="wgl_module_team">',
            '<div class="team__members ', esc_attr($wrapper_classes), '">',
                $posts_html,
            '</div>',
        '</section>';
    }

    public function formalize_query()
    {
        list($query_args) = WGL_Loop_Settings::buildQuery($this->attributes);
        $query_args['post_type'] = 'team';

        return new \WP_Query($query_args);
    }

    public function render_member_single($attributes)
    {
        $this->attributes = $attributes;

        echo '<div class="team__member"', $this->get_wrapper_style(), '>';

            echo '<div class="member__thumbnail-wrap">', $this->get_featured_image(true), '</div>';

            echo '<div class="member__info">';

                $this->member_name();
                $this->member_highlighted_info();
                $this->member_excerpt();
                $this->member_info();
                $this->member_socials(true);

            echo '</div>';

        echo '</div>';
    }

    public function render_member_grid()
    {
        echo '<article class="team__member' . ( $this->attributes['use_carousel'] ? ' swiper-slide' : '' ) . '">';
        echo '<div class="member__wrapper">';

            echo '<div class="member__media">',
                $this->get_featured_image();
                $this->member_socials();
            echo '</div>';

            echo '<div class="member__info">';
                $this->member_name();
                echo '<div class="info__wrapper">';
                    $this->member_highlighted_info();
                echo '</div>';
                $this->member_excerpt();
            echo '</div>';

        echo '</div>';
        echo '</article>';
    }

    protected function get_featured_image($single = false)
    {
        $id = get_the_ID();
        $wp_get_attachment_url = wp_get_attachment_url(get_post_thumbnail_id($id));

        if (!$wp_get_attachment_url) {
            // Bailout.
            return;
        }

        extract($this->attributes);

        $dimensions = $thumbnail_dimensions ?? WGL_Elementor_Helper::get_image_dimensions(
            $img_size_array ?: $img_size_string,
            $img_aspect_ratio
        );
        if (!$dimensions) {
            $img_ratio = 1; // = width / height
            switch ($posts_per_row) {
                default:
                case '1':
                case '2':
                    $dimensions['width'] = 800;
                    $dimensions['height'] = round($dimensions['width'] / $img_ratio);
                    break;
                case '3':
                case '4':
                case '5':
                case '6':
                    $dimensions['width'] = 530;
                    $dimensions['height'] = round($dimensions['width'] / $img_ratio);
                    break;
            }
        }

        $img_url = aq_resize($wp_get_attachment_url, $dimensions['width'], $dimensions['height'], true, true, true) ?: $wp_get_attachment_url;
        $img_alt = get_post_meta(get_post_thumbnail_id($id), '_wp_attachment_image_alt', true);

        $is_single_page = $single_page ?? '';
        $tag_open = $tag_close = 'div';
        if (
            !$is_single_page
            && $thumbnail_linked
        ) {
            $permalink = esc_url(get_permalink($id));

            $tag_open = 'a href="' . $permalink . '"';
            $tag_close = 'a';
        }

        $featured_html = sprintf(
            '<img src="%s" class="thumbnail__featured" alt="%s">',
            esc_url($img_url),
            esc_attr($img_alt ?: '')
        );
        $image_mark = !$single ? '<span class="member__thumbnail-mark"></span>' : '';
        return sprintf(
            '<%s class="member__thumbnail">%s%s</%s>',
            $tag_open,
            $image_mark,
            $featured_html,
            $tag_close
        );
    }

    protected function member_name()
    {
        if ($this->attributes['hide_title']) {
            // Bailout.
            return;
        }

        $is_single_page = $this->attributes['single_page'] ?? '';

        $tag_open = '<span>';
        $tag_close = '</span>';

        $has_link = !$is_single_page && $this->attributes['heading_linked'];
        if ($has_link) {
            $permalink = esc_url(get_permalink(get_the_ID()));

            $tag_open = '<a href="' . $permalink . '">';
            $tag_close = '</a>';
        }

        $member_name = $tag_open . get_the_title() . $tag_close;

        printf(
            '<%1$s class="member__name">%2$s</%1$s>',
            $is_single_page ? 'h1' : 'h2',
            $member_name
        );
    }

    protected function member_highlighted_info()
    {
        if ($this->attributes['hide_highlited_info']) {
            // Bailout.
            return;
        }

        $highlighted_info = get_post_meta(get_the_ID(), 'highlighted_info', true);

        if ($highlighted_info) {
            echo '<div class="info__highlighted">',
                esc_html($highlighted_info),
            '</div>';
        }
    }

    protected function member_excerpt()
    {
        if ($this->attributes['hide_content']) {
            // Bailout.
            return;
        }

        $post = get_post(get_the_ID());

        $is_single_page = $this->attributes['single_page'] ?? '';

        $excerpt = $post->post_excerpt ?: $post->post_content;
        $excerpt = $is_single_page ? $post->post_excerpt : $excerpt;
        $excerpt = preg_replace('~\[[^\]]+\]~', '', $excerpt);
        $excerpt = strip_tags($excerpt);

        if (!empty($this->attributes['content_limit'])) {
            $excerpt = WGL_Framework::modifier_character($excerpt, $this->attributes['content_limit'], '');
        }

        $excerpt && print '<div class="member__excerpt">' . $excerpt . '</div>';
    }

    protected function member_socials($single = false)
    {
        if ($this->attributes['hide_socials']) {
            // Bailout.
            return;
        }

        $extra_class = !empty($this->attributes['socials_official_colors']['idle']) ? ' socials-official-idle' : '';
        $extra_class .= !empty($this->attributes['socials_official_colors']['hover']) ? ' socials-official-hover' : '';

        $socials = '';

        $mb_socials = get_post_meta(get_the_ID(), 'soc_icon', true);
        if ($mb_socials) {
            for ($i = 0, $count = count($mb_socials); $i < $count; $i++) {
                $icon = $mb_socials[$i];
                $name = $icon['select'] ?: '';
                $href = $icon['link'] ?: '#';
                if ($icon['select']) {
                    $socials .= '<a href="' . $href . '" class="social__icon ' . $name . '"></a>';
                }
            }
        }
        $share_icon = !$single ? '<span class="social__icon flaticon-sharing"></span>' : '';
        $socials && print '<div class="member__socials' . $extra_class .'">' . $share_icon . $socials . '</div>';
    }

    protected function member_info()
    {
        $info_array = get_post_meta(get_the_ID(), 'info_items', true);

        if (!$info_array) {
            // Bailout.
            return;
        }

        for ($i = 0, $count = count($info_array); $i < $count; $i++) {
            $info = $info_array[$i];
            $info_name = !empty($info['name']) ? $info['name'] : '';
            $info_description = !empty($info['description']) ? $info['description'] : '';
            $info_link = !empty($info['link']) ? $info['link'] : '';

            if (
                !$info_name
                || !$info_description
            ) {
                continue;
            }

            echo '<div class="info__item">',
                $info_name ? '<h5>' . esc_html($info_name) . '</h5>' : '',
                $info_link ? '<a href="' . esc_url($info_link) . '">' : '',
                    '<span>',
                        esc_html($info_description),
                    '</span>',
                $info_link ? '</a>' : '',
            '</div>';
        }
    }

    protected function get_wrapper_style()
    {
        $bg_id = get_post_meta(get_the_ID(), 'mb_info_bg', true);
        $bg_url = wp_get_attachment_url($bg_id);
        $bg_image_style = $bg_url ? 'background-image: url(' . esc_url($bg_url) . '); ' : '';

        $bg_color = get_post_meta(get_the_ID(), 'info_bg_color', true);
        $bg_color_style = $bg_color ? 'background-color: ' . $bg_color .';' : '';

        $bg_styles = ($bg_image_style || $bg_color_style) ? $bg_image_style . $bg_color_style : '';

        return $bg_styles ? ' style="'.$bg_styles.'"' : '';
    }

    protected function apply_carousel_settings($posts_html)
    {
        $options = [
            'slides_per_row' => $this->attributes['posts_per_row'],
            'slide_per_single'  => $this->attributes['slide_per_single'],
            'slider_infinite' => $this->attributes['slider_infinite'],
            'center_mode' => $this->attributes['center_mode'],
            'autoplay' => $this->attributes['autoplay'],
            'autoplay_speed' => $this->attributes['autoplay_speed'],
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

        return WGL_Carousel_Settings::init($options, $posts_html);
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
