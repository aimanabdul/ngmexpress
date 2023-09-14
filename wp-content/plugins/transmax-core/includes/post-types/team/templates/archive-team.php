<?php
/**
 * Archive Page Template for Team CPT
 *
 * @package transmax-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

use WGL_Extensions\Templates\WGL_Team;

$attributes = [
    'posts_per_row' => '3',
    'thumbnail_linked' => true,
    'heading_linked' => true,
    'hide_content' => true,
    'content_limit' => '100',
    'info_align' => 'center',
    'img_size_string' => '',
    'img_size_array' => '',
    'img_aspect_ratio' => '',
    'hide_title' => '',
    'hide_socials' => '',
    'socials_official_colors' => ['idle' => false, 'hover' => false],
    'hide_highlited_info' => '',
    'use_carousel' => '',
    // Query
    'post_type' => 'team',
    'number_of_posts' => 'all',
    'order_by' => 'date',
];

// Render
get_header();

echo '<div class="wgl-container">',
    '<div id="main-content">',
        (new WGL_Team())->render($attributes),
    '</div>',
'</div>';

get_footer();
