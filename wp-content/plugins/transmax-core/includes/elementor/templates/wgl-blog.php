<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/templates/wgl-blog.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WGL_Extensions\Includes\{
    WGL_Loop_Settings,
    WGL_Carousel_Settings
};
use WGL_Framework;

/**
 * WGL Elementor Blog Template
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 */
class WGL_Blog
{
    private static $instance;
    private $attributes;
    private $query;

    public function render($attributes)
    {
        $this->attributes = $attributes;
        $this->query = $this->formalize_query();

        if (!$this->query->have_posts()) {
            // Bailout, if nothing to render
            return;
        }

        wp_enqueue_script('imagesloaded');
        if ('masonry' === $attributes['blog_layout']){
            wp_enqueue_script('isotope', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
        }

        if ('carousel' === $attributes['blog_layout']){
            wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper/js/swiper-bundle.min.js', array(), false, false);
            wp_enqueue_style('swiper', get_template_directory_uri() . '/js/swiper/css/swiper-bundle.min.css');
        }

        echo '<section class="wgl_cpt_section">';

        echo '<div class="blog-posts">';

        $this->render_header_section();

        echo '<div class="container-grid row', $this->get_row_classes(), '">',
            $this->get_posts_html(),
        '</div>';

        echo '</div>';

        $this->render_navigation_section();

        echo '</section>';

        unset($wgl_blog_atts); //* clear global var
    }

    protected function formalize_query()
    {
        list($query_args) = WGL_Loop_Settings::buildQuery($this->attributes);

        // Add Page to Query
        global $paged;
        if (empty($paged)) {
            $paged = get_query_var('page') ?: 1;
        }
        $query_args['paged'] = $paged;

        if ('none' == $this->attributes['navigation_type']) {
            $query_args['no_found_rows'] = true; // SQL optimization
        }

        $query_args['update_post_term_cache'] = false; // don't retrieve post terms
        $query_args['update_post_meta_cache'] = false; // don't retrieve post meta

        return WGL_Loop_Settings::cache_query($query_args);
    }

    protected function get_posts_html()
    {
        $defaults = [
            'query' => $this->query,
            'blog_layout' => '',
            'blog_columns' => '',
            'hide_media' => '',
            'media_link' => '',
            'hide_share' => $this->attributes['hide_share'],
            'hide_content' => '',
            'hide_blog_title' => '',
            'hide_all_meta' => '',
            'meta_author' => '',
            'meta_comments' => '',
            'meta_categories' => '',
            'meta_date' => '',
            'hide_views' => '',
            'hide_likes' => $this->attributes['hide_likes'],
            'read_more_hide' => $this->attributes['read_more_hide'],
            'read_more_text' => '',
            'content_letter_count' => '',
            'img_size' => $this->attributes['img_size_array'] ?: $this->attributes['img_size_string'],
            'img_aspect_ratio' => $this->attributes['img_aspect_ratio'],
            'heading_tag' => '',
            'remainings_loading_btn_items_amount' => $this->attributes['remainings_loading_btn_items_amount'],
            'load_more_text' => $this->attributes['load_more_text'],
        ];

        global $wgl_blog_atts;
        $wgl_blog_atts = array_merge($defaults, array_intersect_key($this->attributes, $defaults));

        ob_start();
            get_template_part('templates/post/post', 'standard');
        $posts_html = ob_get_clean();

        if ('carousel' === $this->attributes['blog_layout']) {
            $posts_html = $this->apply_carousel_settings($posts_html);
        }

        return $posts_html;
    }

    protected function apply_carousel_settings($posts_html)
    {
        switch ($this->attributes['blog_columns']) {
            case '6':
                $grid_columns = 2;
                break;
            case '3':
                $grid_columns = 4;
                break;
            case '4':
                $grid_columns = 3;
                break;
            case '12':
                $grid_columns = 1;
                break;
            default:
                $grid_columns = 6;
                break;
        }

        $options = [
            // General
            'slides_per_row' => $grid_columns,
            'autoplay' => $this->attributes['autoplay'],
            'autoplay_speed' => $this->attributes['autoplay_speed'],
            'slider_infinite' => $this->attributes['slider_infinite'],
            'slide_per_single'  => $this->attributes['slide_per_single'],
            'adaptive_height' => true,
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

    protected function render_header_section()
    {
        $module_title = $this->attributes['blog_title'] ?? '';
        $module_subtitle = $this->attributes['blog_subtitle'] ?? '';

        if (!$module_title && !$module_subtitle) {
            // Bailout.
            return;
        }

        echo '<div class="wgl_module_title item_title">';

        if ($module_title) {
            echo '<h3 class="transmax_module_title blog_title">',
                wp_kses($module_title, self::_get_kses_allowed_html()),
            '</h3>';
        }

        if ($module_subtitle) {
            echo '<p class="blog_subtitle">',
                wp_kses($module_subtitle, self::_get_kses_allowed_html()),
            '</p>';
        }

        echo '</div>';
    }

    public function get_row_classes()
    {
        $row_class = '';

        $layout = $this->attributes['blog_layout'];

        if ('carousel' === $layout) {
            $row_class .= ' blog_carousel';

            empty($this->attributes['blog_title']) || $row_class .= ' blog_carousel_title-arrow';
        }

        if (in_array($layout, ['grid', 'masonry'])) {
            switch ($this->attributes['blog_columns']) {
                case '12':
                    $row_class .= ' blog_columns-1';
                    break;
                case '6':
                    $row_class .= ' blog_columns-2';
                    break;
                case '4':
                    $row_class .= ' blog_columns-3';
                    break;
                case '3':
                    $row_class .= ' blog_columns-4';
                    break;
            }
            $row_class .= ' ' . $layout;
        }

        $row_class .= ' blog-style-standard';

        return esc_attr($row_class);
    }

    protected function render_navigation_section()
    {
        switch ($this->attributes['navigation_type']) {
            case 'pagination':
                echo WGL_Framework::pagination($this->query);
                break;
            case 'load_more':
                $this->render_load_more();
                break;
        }
    }

    protected function render_load_more()
    {
        global $wgl_blog_atts;

        $wgl_blog_atts['post_count'] = $this->query->post_count;
        $wgl_blog_atts['query_args'] = $this->query->query_vars;
        $wgl_blog_atts['atts'] = $this->attributes;
        $wgl_blog_atts['load_more_text'] = $this->attributes['load_more_text'];

        return WGL_Framework::render_load_more_button($wgl_blog_atts);
    }

    private static function _get_kses_allowed_html()
    {
        return [
            'a' => [
                'id' => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id' => true, 'class' => true, 'style' => true],
            'em' => ['id' => true, 'class' => true, 'style' => true],
            'b' => ['id' => true, 'class' => true, 'style' => true],
            'strong' => ['id' => true, 'class' => true, 'style' => true],
            'span' => ['id' => true, 'class' => true, 'style' => true],
        ];
    }

    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
