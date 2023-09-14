<?php

defined('ABSPATH') || exit;

/**
 * Template for Page 404
 *
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package transmax
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

get_header();

$layout_building_tool = WGL_Framework::get_option('404_building_tool');
if (
    'elementor' === $layout_building_tool
    && did_action('elementor/loaded')
) {

    $selected_page_id = WGL_Framework::get_option('404_template_select');
    $selected_page_id = wgl_dynamic_styles()->multi_language_support($selected_page_id, 'elementor_library');

    if (class_exists('\Elementor\Core\Files\CSS\Post')) {
        (new \Elementor\Core\Files\CSS\Post($selected_page_id))->enqueue();
    }

    echo \Elementor\Plugin::$instance->frontend->get_builder_content($selected_page_id);

} else {
    $styles = $section_padding_html = '';
    $bg_image = WGL_Framework::bg_render('404_page_main');
    $section_padding = WGL_Framework::get_option('404_page_main_padding');

    $section_padding_html .= !empty($section_padding['padding-top']) ? ' padding-top:' . (int) $section_padding['padding-top'] . 'px;' : '';
    $section_padding_html .= !empty($section_padding['padding-bottom']) ? ' padding-bottom:' . (int) $section_padding['padding-bottom'] . 'px;' : '';

    $styles .= $bg_image ?: '';
    $styles .= $section_padding_html ?: '';
    $styles_html = $styles ? ' style="' . esc_attr($styles) . '"' : "";

    echo '<div class="wgl-container full-width"', $styles_html, '>';
    ?>
    <div class="row">
    <div class="wgl_col-12">
    <?php
    echo '<section class="page_404_wrapper">';
        echo '<div class="page_404_wrapper-container">';
            echo '<div class="error_page">';

                echo '<div class="error_page__banner">',
                    '<img',
                        ' src="', esc_url(get_template_directory_uri() . '/img/404.png'), '"',
                        ' alt="', esc_attr__('404 decoration', 'transmax'), '"',
                        '>',
                '</div>';

                echo '<h2 class="error_page__title">',
                    esc_html__('Sorry We Can`t Find That Page!', 'transmax'),
                '</h2>';

                echo '<p class="error_page__description">',
                    esc_html__('The page you are looking for was moved, removed, renamed or never existed.', 'transmax'),
                '</p>';

                echo '<div class="transmax_404_search">',
                    get_search_form(),
                '</div>';

                echo '<div class="transmax_404__button">',
                    '<a',
                        ' class="wgl-button btn-size-md"',
                        ' href="', esc_url(home_url('/')), '"',
                        ' role="button"',
                        '>',
                        '<div class="button-content-wrapper">',
                            esc_html__('TAKE ME HOME', 'transmax'),
                        '</div>',
                    '</a>',
                '</div>';

            echo '</div>';
        echo '</div>';
    echo '</section>';
    ?>
    </div>
    </div>
    </div>
    <?php
}

get_footer();