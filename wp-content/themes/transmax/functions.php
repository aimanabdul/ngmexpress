<?php

/**
 * Load Theme Dependencies
 */
require_once get_theme_file_path('/core/class/theme-dependencies.php');

/**
 * Sequence of theme specific actions
 */

add_action('after_setup_theme', function() {
    $content_width = $content_width ?? 940;
}, 0);

add_action('after_setup_theme', function() {
    add_theme_support('title-tag');
	add_theme_support( 'html5', [
		'gallery',
		'caption',
	]);
});

add_action('init', function() {
    add_post_type_support('page', 'excerpt');
});

/** Add a pingback url auto-discovery for single posts, pages or attachments. */
add_action('wp_head', function() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
});


/**
 * Sequence of theme specific filters
 */

add_filter('wgl_theme/header/enable', 'transmax_header_enable');

add_filter('wgl_theme/page_title/enable', 'transmax_page_title_enable');

add_filter('wgl_theme/footer/enable', 'transmax_footer_enable');

add_action('wgl_theme/preloader', 'WGL_Framework::preloader');

add_action('wgl_theme/after_main_content', 'transmax_after_main_content');

add_filter('comment_form_fields', 'transmax_comment_form_fields');

add_filter('mce_buttons_2', function($buttons) {
	array_unshift($buttons, 'styleselect');
    return $buttons;
});

add_filter('tiny_mce_before_init', 'transmax_tiny_mce_before_init');

add_action('current_screen', function() {
    add_editor_style('css/font-awesome-5.min.css');
});

add_filter('wp_list_categories', 'transmax_categories_postcount_filter');
add_filter('woocommerce_layered_nav_term_html', 'transmax_categories_postcount_filter');

add_filter('get_archives_link', 'transmax_render_archive_widgets', 10, 6);

add_filter('wgl_theme/enqueue_shortcode_css', function($styles) {
    global $transmax_dynamic_css;
    if (!isset($transmax_dynamic_css['style'])) {
        $transmax_dynamic_css = [];
        $transmax_dynamic_css['style'] = $styles;
    } else {
        $transmax_dynamic_css['style'] .= $styles;
    }
});

/* Add Custom Image Link field to media uploader for WGL Gallery module */
add_filter('attachment_fields_to_edit', function($form_fields, $post) {
    $form_fields['custom_image_link'] = array(
        'label' => esc_html__('Custom Image Link','transmax'),
        'input' => 'text',
        'value' => get_post_meta($post->ID, 'custom_image_link', true),
        'helps' => esc_html__('This option works only for the WGL Gallery module.','transmax'),
    );

    return $form_fields;
}, 10, 2);

/* Save values of Custom Image Link in media uploader */
add_filter('attachment_fields_to_save', function ($post, $attachment) {
    if (isset($attachment['custom_image_link']))
    update_post_meta($post['ID'], 'custom_image_link', $attachment['custom_image_link']);

    return $post;
}, 10, 2);

add_filter('widget_types_to_hide_from_legacy_widget_block', function () {
    return [];
}, 10);

add_filter( 'wpcf7_autop_or_not', '__return_false');