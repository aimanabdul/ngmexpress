<?php

defined('ABSPATH') || exit;

use WGL_Extensions\Templates\WGL_Portfolio;

/**
 * Archive Page Template for Portfolio CPT
 *
 * @package wgl-extensions\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */

// Taxonomies
$tax_obj = get_queried_object();
$term_id = $tax_obj->term_id ?? '';
if ($term_id) {
    $taxonomies[] = $tax_obj->taxonomy . ': ' . $tax_obj->slug;
    $tax_description = $tax_obj->description;
}

$archive_attrs = [
    'posts_per_row' => WGL_Framework::get_option('portfolio_list_columns'),
    'layout' => 'grid',
    'image_has_link' => true,
    'title_has_link' => true,
    'link_destination' => 'single',
    'appear_animation_enabled' => null,
    'remainings_loading_type' => 'pagination',
    'remainings_loading_alignment' => 'center',
    'remainings_loading_btn_items_amount' => '4',
    'show_portfolio_title' => WGL_Framework::get_option('portfolio_list_show_title'),
    'show_meta_categories' => WGL_Framework::get_option('portfolio_list_show_cat'),
    'show_content' => WGL_Framework::get_option('portfolio_list_show_content'),
	'show_filter' => false,
    'filter_alignment' => 'center',
    'filter_counter_enabled' => '',
    'grid_gap' => '30',
    'description_position' => 'under_image',
    'description_animation' => 'simple',
    'description_media_type' => '',
    'gallery_mode_enabled' => false,
	'img_size_string' => '740x840',
	'img_size_array' => '',
    'img_aspect_ratio' => '',
    // Query
    'number_of_posts' => '12',
    'order_by' => 'menu_order',
    'order' => 'DSC',
    'taxonomies' => $taxonomies ?? [],
];

// Sidebar parameters
$sb = WGL_Framework::get_sidebar_data('portfolio_list');
$container_class = $sb['container_class'] ?? '';
$row_class = $sb['row_class'] ?? '';
$column = $sb['column'] ?? '12';

// Render
get_header();

echo '<div class="wgl-container', apply_filters('wgl/container/class', $container_class), '">';
echo '<div class="row', apply_filters('wgl/row/class', $row_class), '">';

    echo '<div id="main-content" class="wgl_col-', apply_filters('wgl/column/class', $column), '">';

    if ($term_id) {
        echo '<div class="archive__heading">',
            '<h4 class="archive__tax_title">',
                get_the_archive_title(),
            '</h4>',
            (!empty($tax_description) ? '<div class="archive__tax_description">' . esc_html($tax_description) . '</div>' : ''),
        '</div>';
    }

    (new WGL_Portfolio($archive_attrs))->render();

    echo '</div>';

    $sb && WGL_Framework::render_sidebar($sb);

echo '</div>';
echo '</div>';

get_footer();
