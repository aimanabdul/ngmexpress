<?php
/**
 * Single Page Template for Team CPT
 *
 * @package wgl-extensions\includes\post-types\team
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

use WGL_Extensions\Templates\WGL_Team;

$sb = WGL_Framework::get_sidebar_data();
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

$attributes = [
    'single_page' => true,
    'thumbnail_dimensions' => ['width' => '1000', 'height' => '1000'],
    'socials_official_colors' => ['idle' => false, 'hover' => false],
    // Defaults
    'thumbnail_linked' => '',
    'hide_title' => '',
    'hide_content' => '',
    'hide_highlited_info' => '',
    'hide_socials' => '',
];

// Render
get_header();

echo '<div class="wgl-container', apply_filters('wgl/container/class', $container_class), '">';
echo '<div class="row', apply_filters('wgl/row/class', $row_class), '">';

    echo '<div id="main-content" class="wgl_col-', apply_filters('wgl/column/class', $column), '">';

        while (have_posts()) :
            the_post();

            echo '<div class="row single_team_page">',
                '<div class="wgl_col-12">',
                    (new WGL_Team())->render_member_single($attributes),
                '</div>',
                '<div class="wgl_col-12">',
                    the_content( esc_html__('Read more!', 'transmax-core') ),
                '</div>',
            '</div>';
        endwhile;
        wp_reset_postdata();

    echo '</div>';

    $sb && WGL_Framework::render_sidebar($sb);

echo '</div>';
echo '</div>';

get_footer();
