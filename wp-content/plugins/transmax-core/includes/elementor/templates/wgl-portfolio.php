<?php
/**
 * This template can be overridden by copying it to `transmax[-child]/transmax-core/elementor/templates/wgl-portfolio.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Icons_Manager,
    Control_Media
};
use WGL_Extensions\Includes\{
    WGL_Loop_Settings,
    WGL_Elementor_Helper,
    WGL_Carousel_Settings
};
use WGL_Framework;

/**
 * WGL Elementor Portfolio Template
 *
 *
 * @package transmax-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Portfolio
{
    private $attributes;

    public function __construct(
        $attributes = null,
        $self = null,
        $ajax_qeury = null
    ) {
        $this->attributes = $attributes;
        $this->item = $self;
        $this->query = $ajax_qeury;
	    if (isset($this->attributes['grid_gap']) && isset($this->attributes['grid_gap']['size'])){
		    $this->attributes['grid_gap'] = $this->attributes['grid_gap']['size'];
	    }
    }

    public function render()
    {
        $this->attributes['module_id'] = uniqid('portfolio_module_');

        $this->query = $this->formalize_query();

        $this->layout_determination();
        $this->enqueue_scripts();

        echo '<section class="wgl_cpt_section">';
        echo '<div class="wgl-portfolio" id="' . esc_attr($this->attributes['module_id']) . '">';

        $this->render_filter();

        echo '<div class="wgl-portfolio_wrapper">',
           '<div',
                ' class="wgl-portfolio_container' . $this->get_row_classes() . '" ',
                $this->get_row_styles(),
                '>',
           $this->get_posts_html(),
           '</div>',
        '</div>';

        $this->render_remaining_posts_section();

        echo '</div>';
        echo '</section>';
    }

    public function formalize_query()
    {
        if (!empty($this->query)) {
            // Bailout, if ajax query is already set.
            return;
        }

        list($query_args) = WGL_Loop_Settings::buildQuery($this->attributes);

        $query_args['paged'] = get_query_var('paged') ?: 1;
        $query_args['post_type'] = 'portfolio';

        $this->attributes['query_args'] = $query_args;

        $wp_query_instance = new \WP_Query($query_args);

        $this->attributes['post_count'] = $wp_query_instance->post_count;
        $this->attributes['found_posts'] = $wp_query_instance->found_posts;

        return $wp_query_instance;
    }

    public function layout_determination()
    {
        if (!empty($this->attributes['mb_pf_carousel_r'])) {
            $this->attributes['layout'] = 'carousel';
        }
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('imagesloaded');

        if ($this->attributes['appear_animation_enabled']) {
            wp_enqueue_script('jquery-appear', get_template_directory_uri() . '/js/jquery.appear.js');
        }

        if (0 === strpos($this->attributes['layout'], 'masonry')) {
            wp_enqueue_script('isotope', WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/isotope.pkgd.min.js', ['imagesloaded']);
        }

        if (0 === strpos($this->attributes['layout'], 'carousel')){
            wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper/js/swiper-bundle.min.js', array(), false, false);
            wp_enqueue_style('swiper', get_template_directory_uri() . '/js/swiper/css/swiper-bundle.min.css');
        }
    }

    public function render_filter()
    {
        if (!$this->attributes['show_filter']) {
            // Bailout;
            return;
        }

	    $class = 'carousel' !== $this->attributes['layout'] ? ' isotope-filter' : '';
	    $class .= $this->attributes[ 'filter_alignment' ] ? ' filter-' . $this->attributes[ 'filter_alignment' ] : '';
	    $class .= ! empty( $this->attributes[ 'filter_alignment_tablet' ] ) ? ' filter-tablet-' . $this->attributes[ 'filter_alignment_tablet' ] : '';
	    $class .= ! empty( $this->attributes[ 'filter_alignment_mobile' ] ) ? ' filter-mobile-' . $this->attributes[ 'filter_alignment_mobile' ] : '';
	    $class .= $this->attributes['filter_counter_enabled'] ? ' has_filter_counter' : '';
	    $class .= $this->attributes['filter_max_width_enabled'] ? ' max_width_enabled' : '';

	    echo '<div class="wgl-filter_wrapper portfolio__filter', esc_attr($class), '">',
	        '<div class="wgl-filter_swiper_wrapper">',
                '<div class="swiper-wrapper">',
                    $this->get_filter_categories(),
                '</div>',
	        '</div>',
	    '</div>';
    }

    protected function get_filter_categories()
    {
        $data_category = $this->query->query['tax_query'] ?? [];

        $include = $exclude = [];
        if (!is_tax() && !empty($data_category[0])) {
            if ('IN' === $data_category[0]['operator']) {
                foreach ($data_category[0]['terms'] as $value) {
                    $idObj = get_term_by('slug', $value, 'portfolio-category');
                    $id_list[] = $idObj->term_id;
                }

                $include = implode(',', $id_list);
            } elseif ('NOT IN' === $data_category[0]['operator']) {
                foreach ($data_category[0]['terms'] as $value) {
                    $idObj = get_term_by('slug', $value, 'portfolio-category');
                    $id_list[] = $idObj->term_id;
                }

                $exclude = implode(',', $id_list);
            }
        }

        $cats = get_terms([
            'taxonomy' => 'portfolio-category',
            'include' => $include,
            'exclude' => $exclude,
            'hide_empty' => true
        ]);

        $out = '<a href="#" data-filter=".portfolio__item" class="swiper-slide active">'
            . esc_html__('All', 'transmax-core')
            . '<span class="filter_counter"></span>'
            . '</a>';

        foreach ($cats as $cat) if ($cat->count > 0) {
            $out .= '<a class="swiper-slide" href="' . get_term_link($cat->term_id, 'portfolio-category') . '" data-filter=".' . $cat->slug . '">';
            $out .= $cat->name;
            $out .= '<span class="filter_counter"></span>';
            $out .= '</a>';
        }

        return $out;
    }

    protected function get_row_classes()
    {
        extract($this->attributes);

        $classes = ' container-grid row';
        $classes .= $appear_animation_enabled ? ' appear-animation' : '';
        $classes .= $appear_animation_enabled && !empty($appear_animation_style) ? ' anim-' . $appear_animation_style : '';

        if (0 === strpos($layout, 'masonry')) {
            $classes .= ' isotope';
            $classes .= ' ' . $layout;
        } else {
            switch ($layout) {
                case 'carousel':
                    $classes .= ' carousel';
                    break;
                case 'related':
                    $classes .= !empty($mb_pf_carousel_r) ? ' carousel' : ' isotope';
                    break;
                default:
                    $classes .= !empty($show_filter) ? ' masonry isotope' : ' grid';
                    break;
            }
        }

        $classes .= $posts_per_row ? ' col-' . $posts_per_row : '';

        return esc_attr($classes);
    }

    protected function get_row_styles()
    {
        $grid_gap = $this->attributes['grid_gap'];

        if (empty($grid_gap)) {
            // Bailout.
            return;
        }

        $gap_half = $grid_gap / 2;

        $margins = 'margin-right: -' . $gap_half . 'px;';
        $margins .= 'margin-left: -' . $gap_half . 'px;';
        $margins .= 'margin-bottom: -' . $grid_gap . 'px;';

        return ' style="' . $margins . '"';
    }

    public function get_posts_html($ajax_offset = false)
    {
        extract($this->attributes);

        switch ($layout) {
            default:
            case 'masonry-4':
                $max_masonry_row_items = 6;
                break;

            case 'masonry-2':
            case 'masonry-3':
	            $max_masonry_row_items = 8;
                break;
        }

        if ($this->query->have_posts()) {
            ob_start();
            if (
                'masonry-2' === $layout
                || 'masonry-3' === $layout
                || 'masonry-4' === $layout
            ) {
                echo '<div class="pf_item_size" style="width: 25%;"></div>';
            }

            $this->attributes['additional_post_is_rendered'] = $this->attributes['additional_post_is_rendered'] ?? false;
            $this->attributes['masonry_post_index'] = $this->attributes['masonry_post_index'] ?? 0;

            if($ajax_offset){
                $temp_value = $ajax_offset;
                while($temp_value > $max_masonry_row_items){
                    $temp_value = $temp_value - $max_masonry_row_items;
                }
                $this->attributes['masonry_post_index'] = $temp_value;
            }

            $use_additional_post = $use_additional_post ?? '';

            while ($this->query->have_posts()) {
                $this->query->the_post();

                $this->attributes['masonry_post_index'] = $this->attributes['masonry_post_index'] < $max_masonry_row_items
                    ? 1 + $this->attributes['masonry_post_index']
                    : 1;

                if (
                    $use_additional_post
                    && !$this->attributes['additional_post_is_rendered']
                    && 'first' === $additional_post_position
                    && 0 == $this->query->current_post
                ) {
                    $this->render_additional_grid_post();
                }

                $this->render_grid_post();

                if (
                    $use_additional_post
                    && !$this->attributes['additional_post_is_rendered']
                    && 'last' === $additional_post_position
                    && $this->query->current_post == $this->query->post_count - 1
                ) {
                    $this->render_additional_grid_post();
                }
            }
            $posts_html = ob_get_clean();
            wp_reset_postdata();

            if ('carousel' === $layout) {
                $posts_html = $this->apply_carousel_settings($posts_html);
            }
        }

        return $posts_html ?? '';
    }

    public function render_grid_post()
    {
        extract($this->attributes);

        $description_position = $description_position ?? '';
        $description_animation = $description_animation ?? '';
        $gallery_mode_enabled = $gallery_mode_enabled ?? '';

        $style_gap = !empty($grid_gap) ? ' style="padding: 0 ' . $grid_gap / 2 . 'px ' . $grid_gap . 'px;"' : '';

        echo '<article class="portfolio__item', $this->get_grid_item_classes(), '" ', $style_gap, '>';

        $wrapper_class = $description_position ? ' description_' . $description_position : '';
        $wrapper_class .= 'inside_image' === $description_position ? ' animation_' . $description_animation : '';
        $wrapper_class .= $gallery_mode_enabled ? ' gallery_mode' : '';

        echo '<div class="item__wrapper', esc_attr($wrapper_class), '">';

        $link_params['link_destination'] = $link_destination ?? '';
        $link_params['link_target'] = $link_target ?? '';
        $link_params['additional_class'] = ' portfolio_link';
        $link = $this->get_link($link_params);

        echo '<div class="item__image">';

            echo $this->get_grid_post_image();

            if ('under_image' === $description_position) {
                echo '<div class="overlay"></div>';

                echo $image_has_link ? $link : '';
            }

        echo '</div>';

        if ($gallery_mode_enabled) {
            $img_id = get_post_thumbnail_id(get_the_ID());
            $img_url = wp_get_attachment_image_url($img_id, 'full');

            echo '<a',
                ' href="', esc_url($img_url), '"',
                ' class="overlay"',
                ' data-elementor-open-lightbox="yes"',
                ' data-elementor-lightbox-slideshow="' . esc_attr($module_id) . '"',
                '>',
                '<i aria-hidden="true" class="flaticon flaticon-loupe"></i>',
            '</a>';

        } else {
            $this->standard_mode_post($link);
        }

        if (
            'under_image' !== $description_position
            && 'sub_layer' !== $description_animation
            && !$gallery_mode_enabled
        ) {
            echo '<div class="overlay"></div>';
        }

        if ('sub_layer' === $description_animation && $image_has_link) {
            echo $link;
        }

        echo '</div>';

        echo '</article>';
    }

    public function get_grid_item_classes()
    {
        $class = ' item'; // ajax requiered class

        $class .= $this->get_categories_slugs();

        $class .= 'carousel' === $this->attributes['layout'] ? ' swiper-slide' : '';

        return esc_attr($class);
    }

    protected function get_categories_slugs()
    {
        $terms = wp_get_post_terms(get_the_id(), 'portfolio-category');

        $categories = '';
        for ($i = 0, $count = count($terms); $i < $count; $i++) {
            $term = $terms[$i];
            $categories .= ' ' . $term->slug;
        }

        return esc_attr($categories);
    }

    public function get_grid_post_image()
    {
	    $img_id = get_post_thumbnail_id(get_the_ID());
	    $url = wp_get_attachment_image_url($img_id, 'full');
	
	    if (!$url) {
		    // Bailout.
		    return;
	    }
	
	    $layout = $this->attributes['layout'];
	    $masonry_grid = '';
	
	    if (0 === strpos($layout, 'masonry')) {
		    $grid_gap = $this->attributes['grid_gap'];
		
		    $elementor_container_width = (int) wgl_dynamic_styles()->get_elementor_container_width();
		    $full_viewport_width = $elementor_container_width - ($grid_gap * 2);
		    $half_viewport_width = ($elementor_container_width / 2 - $grid_gap * 2);
		
		    $post_index = $this->attributes['masonry_post_index'];
		
		    if ('masonry-2' === $layout) {
			    switch ($post_index) {
				    case 2:
				    case 6:
					    $dimensions = [
						    'width' => $full_viewport_width / 2,
						    'height' => $full_viewport_width
					    ];
					    $masonry_grid = ' style="max-width: unset; width: calc(100% + '.((int)$grid_gap/2).'px);"';
					    break;
				    case 3:
				    case 4:
				    case 5:
				    case 8:
					    $dimensions = [
						    'width' => $half_viewport_width,
						    'height' => $half_viewport_width
					    ];
					    break;
				
				    default:
					    $dimensions = [
						    'width' => $full_viewport_width,
						    'height' => $full_viewport_width
					    ];
			    }
		    } elseif ('masonry-3' === $layout) {
			    switch ($post_index) {
				    case 2:
				    case 5:
					    $dimensions = [
						    'width' => $full_viewport_width,
						    'height' => $full_viewport_width / 2
					    ];
					    $masonry_grid = ' style="margin-top: -'.((int)$grid_gap/2).'px;"';
					    break;
				    case 3:
				    case 4:
				    case 7:
				    case 8:
					    $dimensions = [
						    'width' => $half_viewport_width,
						    'height' => $half_viewport_width
					    ];
					    break;
				
				    default:
					    $dimensions = [
						    'width' => $full_viewport_width,
						    'height' => $full_viewport_width
					    ];
			    }
		    } elseif ('masonry-4' === $layout) {
			    switch ($post_index) {
				    case 1:
				    case 6:
					    $dimensions = [
						    'width' => $full_viewport_width,
						    'height' => $full_viewport_width / 2
					    ];
					    $masonry_grid = ' style="margin-top: -'.((int)$grid_gap/2).'px;"';
					    break;
				    case 2:
				    case 3:
				    case 4:
				    case 5:
					    $dimensions = [
						    'width' => $half_viewport_width,
						    'height' => $half_viewport_width
					    ];
					    break;
				
				    default:
					    $dimensions = [
						    'width' => $full_viewport_width,
						    'height' => $full_viewport_width
					    ];
			    }
		    }
	    } else {
		    $img_size_array = $this->attributes['img_size_array'] ?? '';
		    $img_size_string = $this->attributes['img_size_string'] ?? '';
		    $img_aspect_ratio = $this->attributes['img_aspect_ratio'] ?? '';
		
		    $dimensions = WGL_Elementor_Helper::get_image_dimensions($img_size_array ?: $img_size_string, $img_aspect_ratio);
	    }
	
	    empty($dimensions['width']) || $url = aq_resize($url, $dimensions['width'], $dimensions['height'], true, true, true) ?: $url;
	
	    $alt = trim(strip_tags(get_post_meta($img_id, '_wp_attachment_image_alt', true)));
	
	    return '<img src="' . esc_url($url) . '" alt="' . esc_attr($alt) . '"'.$masonry_grid.'>';
    }

    public function standard_mode_post($link)
    {
        extract($this->attributes);

        echo '<div class="item__description">';

        if (
            $image_has_link
            && 'under_image' !== $description_position
            && 'sub_layer' !== $description_animation
        ) {
            echo $link;
        }

        $link_params['link_destination'] = $link['link_destination'] ?? '';
        $link_params['link_target'] = $link['link_target'] ?? '';

        echo '<div class="description__wrapper">';

        $this->render_grid_post_categories();

        if ($show_portfolio_title) {
            $link_params['link_content'] = get_the_title();
            $title_has_link = $title_has_link ?? '';

            echo '<div class="item__title">',
                '<h4 class="title">',
                ($title_has_link ? $this->get_link($link_params) : '<span>' . get_the_title() . '</span>'),
                '</h4>',
            '</div>';
        }

        $this->render_post_content();

        if (
            isset($description_media_type)
            && 'font' === $description_media_type
        ) {
            if (Icons_Manager::is_migration_allowed()) {
                ob_start();
                Icons_Manager::render_icon($description_icon);
                $icon_output = ob_get_clean();
            } else {
                $icon_output = '<i class="icon ' . esc_attr($description_icon) . '"></i>';
            }
            $link_params['link_content'] = $icon_output;

            echo '<div class="description__icon">',
                ($linked_icon ? $this->get_link($link_params) : $icon_output),
                '</div>';
        }

        echo '</div>';

        echo '</div>'; // item__description
    }

    public function apply_carousel_settings($posts_html)
    {
        $options = [
            'slides_per_row' => $this->attributes['posts_per_row'],
            'autoplay' => $this->attributes['autoplay'],
            'autoplay_speed' => $this->attributes['autoplay_speed'],
            'slider_infinite' => $this->attributes['slider_infinite'],
            'slide_per_single' => $this->attributes['slide_per_single'],
            'center_mode' => $this->attributes['center_mode'],
            'variable_width' => $this->attributes['variable_width'],
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

    protected function render_grid_post_categories()
    {
        if (!$this->attributes['show_meta_categories']) {
            // Bailout.
            return;
        }

        $cats_html = '';

        $p_cats = wp_get_post_terms(get_the_id(), 'portfolio-category');
        if (!empty($p_cats)) {
            $cats_html = '<div class="post_cats">';
            for ($i = 0, $count = count($p_cats); $i < $count; $i++) {
                $term = $p_cats[$i];
                $name = $term->name;
                $link = get_category_link($term->term_id);

                $cats_html .= '<a href=' . esc_url($link) . ' class="portfolio-category">' . esc_html($name) . '</a>';
            }
            $cats_html .= '</div>';
        }

        echo $cats_html;
    }

    protected function render_post_content()
    {
        if (!$this->attributes['show_content']) {
            // Bailout.
            return;
        }

        $letter_count = !empty($this->attributes['content_letter_count']) ? $this->attributes['content_letter_count'] : '';

        $post = get_post(get_the_id());

        $chars_count = $letter_count ?: $this->characters_limit();
        $content = !empty($post->post_excerpt) ? $post->post_excerpt : $post->post_content;
        $content = preg_replace('~\[[^\]]+\]~', '', $content);
        $content = strip_tags($content);
        $content = WGL_Framework::modifier_character($content, $chars_count, '');

        if ($content) {
            echo '<div class="description_content">',
                '<div class="content">',
                    $content,
                '</div>',
            '</div>';
        }
    }

    protected function characters_limit()
    {
        switch ($this->attributes['posts_per_row']) {
            case '1':
                $limit = 300;
                break;
            default:
            case '2':
                $limit = 145;
                break;
            case '3':
                $limit = 70;
                break;
            case '4':
                $limit = 55;
                break;
        }

        return $limit;
    }

    public function render_additional_grid_post()
    {
        $style_gap = !empty($this->attributes['grid_gap']) ? ' style="padding: 0 ' . $this->attributes['grid_gap'] / 2 . 'px ' . $this->attributes['grid_gap'] . 'px;"' : '';

        $img_url = $this->attributes['additional_post_img_media']['url'] ?? '';
        if (
            $img_url
            && isset($this->attributes['img_size_string'])
        ) {
            $dimensions = WGL_Elementor_Helper::get_image_dimensions(
                $this->attributes['img_size_array'] ?: $this->attributes['img_size_string'],
                $this->attributes['img_aspect_ratio'] ?: ''
            );

            empty($dimensions['width']) || $img_url = aq_resize($img_url, $dimensions['width'], $dimensions['height'], true, true, true) ?: $img_url;
        }
        $this->item->add_render_attribute('additional__img', 'src', esc_url($img_url));
        $this->item->add_render_attribute('additional__img', 'alt', Control_Media::get_image_alt($this->attributes['additional_post_img_media']));

        $link = $this->attributes['additional_post_link'];
        $this->item->add_render_attribute('link', 'class', 'item__button');
        empty($link['url']) || $this->item->add_link_attributes('link', $link);

        echo '<article class="portfolio__item additional-post ' , ( 'carousel' === $this->attributes['layout'] ? ' swiper-slide' : '' ) , '" ', $style_gap, '>',
            '<div class="item__wrapper">',
                '<div class="item__image">',
                    '<img ', $this->item->get_render_attribute_string('additional__img'), '>',
                '</div>',
                '<a ', $this->item->get_render_attribute_string('link'), '>',
                    esc_html($this->attributes['additional_post_btn_text']),
                '</a>',
            '</div>',
        '</article>';

        $this->attributes['additional_post_is_rendered'] = true;
        $this->attributes['masonry_post_index'] = 1 + $this->attributes['masonry_post_index'];
    }

    public function get_link($link_settings)
    {
        extract($link_settings);

        $href = $href ?? get_permalink();
        $target = !empty($link_target) ? ' target="_blank"' : '';
        $additional_class = $additional_class ?? '';
        $link_content = $link_content ?? '';

        switch ($link_destination) {
            case 'popup':
                $attachment_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
                $link = '<a'
                    . ' href="' . $attachment_url . '"'
                    . ($additional_class ? ' class="' . $additional_class . '"' : '')
                    . ' data-elementor-open-lightbox="yes"'
                    . ' data-elementor-lightbox-slideshow="' . esc_attr($this->attributes['module_id']) . '"'
                    . '>'
                    . $link_content
                    . '</a>';
                break;

            case 'custom':
                if (
                    class_exists('RWMB_Loader')
                    && rwmb_meta('mb_portfolio_link')
                    && !empty(rwmb_meta('portfolio_custom_url'))
                ) {
                    $href = rwmb_meta('portfolio_custom_url');
                }
                $link = '<a href="' . esc_url($href) . '"' . $target . ' class="custom_link' . $additional_class . '">'
                    . $link_content
                    . '</a>';
                break;

            default:
            case 'single':
                $link = '<a href="' . esc_url($href) . '"' . $target . ' class="single_link' . $additional_class . '">'
                    . $link_content
                    . '</a>';
                break;
        }

        return $link;
    }

    protected function render_remaining_posts_section()
    {
        $remainings_loading_type = $this->attributes['remainings_loading_type'] ?? '';

        switch ($remainings_loading_type) {
            case 'pagination':
                echo WGL_Framework::pagination($this->query, $this->attributes['remainings_loading_alignment']);
                break;

            case 'load_more':
                $this->render_load_more_btn();
                break;

            case 'infinite':
                $this->render_infinite_scroll();
                break;
        }
    }

    public function render_load_more_btn()
    {
        if ($this->query->post_count > $this->query->found_posts) {
            // Bailout.
            return;
        }

        WGL_Framework::render_load_more_button($this->attributes);
    }

    public function render_infinite_scroll()
    {
        if ($this->query->post_count > $this->query->found_posts) {
            // Bailout.
            return;
        }

        wp_enqueue_script('waypoints');

        $uniq = uniqid();

        $non_empty_attributes = array_filter($this->attributes, function($value) {
            return !empty($value) ? $value : false;
        });

        $ajax_data_str = htmlspecialchars(json_encode($non_empty_attributes), ENT_QUOTES, 'UTF-8');

        echo '<div class="clear"></div>',
            '<div class="text-center load_more_wrapper">',
            '<div class="infinity_item">',
            '<span class="wgl-ellipsis">',
                '<span></span><span></span>',
                '<span></span><span></span>',
            '</span>',
            '</div>',
            '<form class="posts_grid_ajax">',
                "<input type='hidden' class='ajax_data' name='{$uniq}_ajax_data' value='${ajax_data_str}' />",
            '</form>',
        '</div>';
    }

    public function render_item_single()
    {
        echo '<article class="wgl-portfolio-single_item">';
        echo '<div class="portfolio__item">';

        echo '<div class="portfolio-item__meta-wrap single_meta">';
            switch (WGL_Framework::get_mb_option('portfolio_single_type_layout', 'mb_portfolio_post_conditional', 'custom')) {
                case '1':
                    echo $this->render_single_post_categories();
                    $this->render_post_title();
                    $this->render_post_meta();
                    $this->render_single_post_image();
                    break;
                default:
                case '2':
                    $this->render_single_post_image();
                    echo $this->render_single_post_categories();
                    $this->render_post_title();
                    $this->render_post_meta();
                    break;
            }
        echo '</div>';

        $content = apply_filters('the_content', get_post_field('post_content', get_the_id()));
        if ($content) {
            echo '<div class="description_content">',
                '<div class="content">',
                    $content,
                '</div>',
            '</div>';
        }

        // ↓ Post Meta Information
        $tags_enabled = $shares_enabled = '';
        if (class_exists('RWMB_Loader')) {
            if ('default' !== rwmb_meta('mb_portfolio_above_content_cats')) {
                $tags_enabled = rwmb_meta('mb_portfolio_above_content_cats');
            }

            if ('default' !== rwmb_meta('mb_portfolio_above_content_share')) {
                $shares_enabled = rwmb_meta('mb_portfolio_above_content_share');
            }
        }
        $tags_enabled = $tags_enabled ?: WGL_Framework::get_option('portfolio_above_content_cats');
        $shares_enabled = $shares_enabled ?: WGL_Framework::get_option('portfolio_above_content_share');

        $tags_html = $tags_enabled ? $this->get_tags() : '';
        $socials_html = $shares_enabled ? $this->get_post_socials() : '';
        if ($tags_html || $socials_html) {
            echo '<div class="single_post_info">',
                $socials_html,
                $tags_html,
            '</div>';

            echo '<div class="clear"></div>';
        } else {
            echo '<div class="post_info-divider"></div>';
        }
        // ↑ post meta information

        echo '</div>';
        echo '</article>';
    }

    public function get_tags()
    {
        $tags = $this->get_tags_list('<div class="tagcloud-wrapper"><div class="tagcloud">', '</div></div>');

        return !is_wp_error($tags) ? $tags : '';
    }

    /**
     * Filters the tags list for a given post.
     */
    protected function get_tags_list(
        $before = '',
        $after = '',
        $sep = ' '
    ) {
        global $post;

        return apply_filters(
            'the_tags',
            get_the_term_list(
                $post->ID,
                'portfolio_tag',
                $before,
                $sep,
                $after
            ),
            $before,
            $sep,
            $after,
            $post->ID
        );
    }

    protected function get_post_socials()
    {
        if (function_exists('wgl_extensions_social')) {
            ob_start();

			    echo '<div class="share_post-container">';
                    echo '<div class="share_post-title">'.esc_html__('SHARE ARTICLE','transmax-core').'</div>';
                    wgl_extensions_social()->render_post_share();
                echo '</div>';
            return ob_get_clean();
        }
    }

    protected function get_post_likes()
    {
        if (
            !WGL_Framework::get_option('portfolio_single_meta_likes')
            || !function_exists('wgl_simple_likes')
        ) {
            // Bailout.
            return;
        }

        return wgl_simple_likes()->get_likes_button();
    }

    protected function get_post_date()
    {
        if (
            class_exists('RWMB_Loader')
            && 'default' != rwmb_meta('mb_portfolio_single_meta_date')
        ) {
            $date_enable = rwmb_meta('mb_portfolio_single_meta_date');
        }

        $date_enable = $date_enable ?? WGL_Framework::get_option('portfolio_single_meta_date');

        if ($date_enable) {
            return '<span class="post_date">'
                . esc_html(get_the_time(get_option('date_format')))
                . '</span>';
        }
    }

    protected function get_post_author()
    {
        if (!WGL_Framework::get_option('portfolio_single_meta_author')) {
            // Bailout.
            return;
        }

        return '<span class="post_author"><span>'.
        esc_html__('By ', 'transmax-core').
        '<a href="'. esc_url(get_author_posts_url(get_the_author_meta('ID'))). '">'.
            esc_html(get_the_author_meta('display_name')).
        '</a>'.
    '</span></span>';
    }

    protected function get_post_comments()
    {
        if (!WGL_Framework::get_option('portfolio_single_meta_comments')) {
            // Bailout.
            return;
        }

        $amount = get_comments_number(get_the_ID());

        return '<span class="comments_post">'
            . '<a href="' . esc_url(get_comments_link()) . '">'
            . esc_html($amount)
            . ' '
            . esc_html(_n('Comment', 'Comments', $amount, 'transmax-core'))
            . '</a>'
            . '</span>';
    }

    protected function render_single_post_categories()
    {
        if (
            class_exists('RWMB_Loader')
            && 'default' != rwmb_meta('mb_portfolio_single_meta_categories')
        ) {
            $cats_enabled = rwmb_meta('mb_portfolio_single_meta_categories');
        }
        $cats_enabled = !empty($cats_enabled) ? $cats_enabled : WGL_Framework::get_option('portfolio_single_meta_categories');

        if (
            $cats_enabled
            && $cats = wp_get_post_terms(get_the_id(), 'portfolio-category')
        ) {
            $cats_html = '<span class="post_categories">';
            for ($i = 0, $count = count($cats); $i < $count; $i++) {
                $term = $cats[$i];
                $link = get_category_link($term->term_id);

                $cats_html .= '<span>'
                    . '<a href=' . esc_html($link) . ' class="portfolio-category">'
                    . esc_html($term->name)
                    . '</a>'
                    . '</span>';
            }
            $cats_html .= '</span>';
        }

        return $cats_html ?? '';
    }

    protected function render_post_meta()
    {
        $hide_all_meta = WGL_Framework::get_option('portfolio_single_meta');
        if ($hide_all_meta) {
            // Bailout.
            return;
        }

        $meta_data = $this->get_post_date()
            . $this->get_post_author()
            . $this->get_post_comments();
        $meta_likes = $this->get_post_likes();

        if ($meta_data || $meta_likes) {
            echo '<div class="meta_wrapper">';
                if ($meta_data) {
                    echo '<div class="meta-data">',
                        $meta_data,
                    '</div>';
                }
                if ($meta_likes) {
                    echo '<div class="meta-data">',
                        $meta_likes,
                    '</div>';
                }
            echo '</div>';
        }
    }

    protected function render_post_title()
    {
        if (class_exists('RWMB_Loader')) {
            $mb_title_enabled = rwmb_meta('mb_portfolio_title');
        }
        $title_enabled = isset($mb_title_enabled) ? $mb_title_enabled : true;

        echo $title_enabled ? '<h1 class="item__title">' . get_the_title() . '</h1>' : '';
    }

    protected function render_single_post_image()
    {
        $featured_replaced = [];
        $featured_type = WGL_Framework::get_mb_option('portfolio_featured_image_type', 'mb_portfolio_featured_image_conditional', 'custom');
        if ('off' === $featured_type) {
            // Bailout.
            return;
        }
        if ('replace' === $featured_type) {
            $featured_replaced = WGL_Framework::get_mb_option('portfolio_featured_image_replace', 'mb_portfolio_featured_image_conditional', 'custom');
        }

        $img_id = 0;
        if ($featured_replaced) {
            $img_id = array_values($featured_replaced)[0]['ID'] ?? 0;
        }
        $img_id = $img_id ?: get_post_thumbnail_id(get_the_ID());

        $url = wp_get_attachment_image_url($img_id, 'full');

        if (!$url) {
            // Bailout.
            return;
        }

        $alt = trim(strip_tags(get_post_meta($img_id, '_wp_attachment_image_alt', true)));

        echo '<div class="item__image">',
            '<img src="', esc_url($url), '" alt="', esc_attr($alt), '">',
        '</div>';
    }
}
