<?php

defined('ABSPATH') || exit;

if (!class_exists('Transmax_Single_Post')) {
    /**
     * @package transmax\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class Transmax_Single_Post
    {
	    /**
	     * @var Transmax_Single_Post
	     */
	    private static $instance;

	    /**
	     * @var WP_Post
	     */
        private $post_id;
        private $post_format;
        private $show_meta_date;
        private $show_meta_author;
        private $show_meta_comments;
        private $show_meta_cats;
        private $show_meta_likes;
        private $show_meta_views;
        private $show_meta_shares;
        private $render_attributes;


        /**
         * Get featured image
         *
         * Retrieve the post format featured image.
         *
         * @access private
         *
         * @var null|array
         */
        private $media;

        /**
         * Get class name
         *
         * Retrieve the post format classes names.
         *
         * @access private
         *
         * @var null|array
         */
        private $media_part_class;

        /**
         * Provider match masks.
         *
         * Holds a list of supported providers with their URL structure in a regex format.
         *
         * @var array Provider URL structure regex.
         */
        private static $provider_match_masks = [
            'youtube' => '/^.*(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:(?:watch)?\?(?:.*&)?vi?=|(?:embed|v|vi|user)\/))([^\?&\"\'>]+)/',
            'vimeo' => '/^.*vimeo\.com\/(?:[a-z]*\/)*([‌​0-9]{6,11})[?]?.*/',
            'dailymotion' => '/^.*dailymotion.com\/(?:video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/',
        ];

        /**
         * Embed patterns.
         *
         * Holds a list of supported providers with their embed patters.
         *
         * @var array Embed patters.
         */
        private static $embed_patterns = [
            'youtube' => 'https://www.youtube{NO_COOKIE}.com/embed/{VIDEO_ID}?feature=oembed',
            'vimeo' => 'https://player.vimeo.com/video/{VIDEO_ID}#t={TIME}',
            'dailymotion' => 'https://dailymotion.com/embed/video/{VIDEO_ID}',
        ];

	    /**
	     * @var bool
	     */
	    public $meta_info_render;
	    public $render_bg_image;

        public static function getInstance()
        {
            if (is_null(static::$instance)) {
                static::$instance = new static();
            }

            return static::$instance;
        }

        private function __construct()
        {
            $this->post_id = get_the_ID();
        }

        public function set_post_meta($args = false)
        {
            if (!$args || empty($args)) {
                $this->show_meta_date = true;
                $this->show_meta_author = true;
                $this->show_meta_comments = true;
                $this->show_meta_cats = true;
                $this->show_meta_likes = true;
                $this->show_meta_shares = true;
                $this->show_meta_views = false;
            } else {
                $this->show_meta_date = $args['date'] ?? '';
                $this->show_meta_author = $args['author'] ?? '';
                $this->show_meta_comments = $args['comments'] ?? '';
                $this->show_meta_cats = $args['category'] ?? '';
                $this->show_meta_likes = $args['likes'] ?? '';
                $this->show_meta_shares = $args['share'] ?? '';
                $this->show_meta_views = !empty($args['views'][0]) ? $args['views'][1] : '';
            }
        }

        public function set_image_data(
            $media_link = false,
            $image_size = 'full'
        ) {
            $this->meta_info_render = false;
            $this->media_part_class = '';

            $this->media = false;
            if (class_exists('RWMB_Loader')) {
                switch ($this->post_format) {
                    case 'gallery':
                        $this->meta_info_render = true;
                        $this->media = $this->featured_gallery($image_size);
                        break;
                    case 'video':
                        global $wgl_related_posts;
                        if (is_singular('post') && empty($wgl_related_posts)) {
                            $this->meta_info_render = false;
                        } else {
                            $this->meta_info_render = true;
                        }
                        $this->media = $this->featured_video($image_size);

                        $video_style = rwmb_meta('post_format_video_style');

                        if ($video_style == 'bg_video') {
                            $this->media_part_class .= ' video_parallax';
                        }
                        if (has_post_thumbnail()) {
                            if (!is_single() || !empty($wgl_related_posts)) {
                                $this->media_part_class .= ' video_image';
                            }
                        } elseif ($video_style != 'bg_video') {
                            $this->media_part_class .= ' no-thumbnail';
                        }

                        if((bool) strpos($this->media, 'rwmb-oembed-not-available', 11)){
                            $this->media = false;
                        }
                        break;
                    case 'quote':
                        $this->media = $this->featured_quote();
                        $this->meta_info_render = false;
	                    break;
                    case 'link':
                        $this->media = $this->featured_link();
                        $this->meta_info_render = false;
                        break;
                    case 'audio':
                        $this->media = $this->featured_audio();
                        $this->meta_info_render = false;
                        break;
                    default:
                        $this->meta_info_render = true;
                        $this->media = $this->featured_image($media_link, $image_size);
                        break;
                }

            } else {
                $this->meta_info_render = true;
                $this->media = $this->featured_image($media_link, $image_size);
            }

            if (empty($this->media)) {
                $this->meta_info_render = false;
            }
        }

        public function set_post_data()
        {
            $this->post_id = get_the_ID();
            $this->post_format = get_post_format();
        }

        public function get_pf()
        {
            if (!$this->post_format) {
                if (
                    has_post_thumbnail()
                    && wp_get_attachment_url(get_post_thumbnail_id($this->post_id))
                ) {
                    return 'standard-image';
                } else {
                    return 'standard';
                }
            }

            return $this->post_format;
        }

        /**
         * Render the featured media of a post.
         *
         * @since 1.0.0
         *
         * @param array $args [
         *     Optional. Array of key => value arguments.
         *
         *     @type bool|int      $hide_all_meta  Default is true|1.
         *     @type array|bool    $meta_cats      Whether render categories inside `blog-post_media` or not.
         *                                         To have an effect pass array with key `category` and value true assigned to it.
         * ]
         *
         * @return string HTML content.
         */
        public function render_featured(...$args)
        {
            $args = isset($args[0]) && is_array($args[0]) ? $args[0] : [];

            $default_args = [
                'hide_all_meta' => true,
                'meta_cats' => false
            ];
            $args = array_merge($default_args, $args);
            extract($args);

            if (!$this->media) {
                return;
            }

            echo '<div class="blog-post_media">';
                echo '<div class="blog-post_media_part', esc_attr($this->media_part_class), '">',
                    $this->media,
                '</div>';

                // Categories
                if (!$args['hide_all_meta'] && $this->meta_info_render) {
                    $this->render_post_meta($args['meta_cats']);
                }
            echo '</div>';
        }

        public function render_featured_image_as_background(
            $media_link = false,
            $image_size = 'full',
	        $data_animation = null,
	        $show_media = true
        ) {
	        $media = '';
            $rwmb_is_active = class_exists('RWMB_Loader');

            $featured_image = WGL_Framework::get_mb_option('featured_image_type', 'mb_featured_image_conditional', 'custom');
            if ('replace' === $featured_image) {
                $featured_image_replace = WGL_Framework::get_mb_option('featured_image_replace', 'mb_featured_image_conditional', 'custom');
            }

            if (has_post_thumbnail()) {
                $image_id = 0;
                if (!empty($featured_image_replace) && is_single()) {
                    if (
                        $rwmb_is_active
                        && 'custom' === rwmb_meta('mb_featured_image_conditional')
                    ) {
                        $image_id = array_values( (array) $featured_image_replace )[0]['ID'] ?? 0;
                    } else {
                        $image_id = $featured_image_replace['id'];
                    }
                }
                $image_id = $image_id ?: get_post_thumbnail_id();

                $media = $this->get_image_url($image_size, $image_id);
            }

            if (
                'gallery' === $this->post_format
                && !is_single()
            ) {
                $media = $this->featured_gallery($image_size);
            }

            $video_style = $rwmb_is_active ? rwmb_meta('post_format_video_style') : null;
            if (
                'bg_video' === $video_style
                && 'video' === $this->post_format
            ) {
                $media = $this->featured_video($image_size);
            }

            // Render
            if ($media_link) {
                echo '<a href="', esc_url(get_permalink()), '" class="media-link image-overlay">';
            }

	        if ($media && $show_media) {
		        if (
			        'video' === $this->post_format
			        && 'bg_video' === $video_style
		        ) {
			        echo WGL_Framework::render_html( $media );
		        } else if ( 'off' !== $featured_image ) {
			        echo '<div',
			        ' class="blog-post_bg_media"',
			        ' style="background-image: url(', esc_url( $media ), ')"',
				        $data_animation ?? '',
			        '></div>';
		        }
	        }else{
		        $default_bg = WGL_Framework::get_option('post_single_layout_3_bg_image');
		        $media = $default_bg['background-image'] ?? '';

		        if(!empty($media)){
			        if ( 'off' !== $featured_image ) {
				        echo '<div class="blog-post_bg_media" style="background-image:url('.esc_url($media).')" '.(! empty($data_animation) ? $data_animation : "").'></div>';
			        }
		        }
            }

            if ($media_link) {
                echo '</a>';
            }
        }

        public function featured_image(
            $media_link,
            $image_size
        ) {
            $featured_image = WGL_Framework::get_mb_option('featured_image_type', 'mb_featured_image_conditional', 'custom');
            if ('replace' === $featured_image) {
                $featured_image_replace = WGL_Framework::get_mb_option('featured_image_replace', 'mb_featured_image_conditional', 'custom');
            }

            if (
                ('off' !== $featured_image || !is_single())
                && (has_post_thumbnail() || !empty($featured_image_replace))
            ) {
                $image_id = 0;
                if (!empty($featured_image_replace) && is_single()) {
                    if ('custom' === rwmb_meta('mb_featured_image_conditional')) {
                        $image_id = array_values($featured_image_replace)[0]['ID'] ?? 0;
                    } else {
                        $image_id = $featured_image_replace['id'];
                    }
                }
                $image_id = $image_id ?: get_post_thumbnail_id();

                $image_url = $this->get_image_url($image_size, $image_id);

                if (!$image_url) {
                    // Bailout.
                    return;
                }

                $output = '';
                if ($media_link) {
                    $output .= '<a href="' . esc_url(get_permalink()) . '" class="media-link image-overlay">';
                }

                $image_meta_title = trim( strip_tags(get_post_meta( $image_id, '_wp_attachment_image_alt', true )));
                if (!$image_meta_title){
                    if (!!get_the_title($image_id)){
                        $image_meta_title = get_the_title($image_id);
                    }else{
                        $image_meta_title = get_the_title();
                    }
                }

                $output .= '<img'
                    . ' src="' . esc_url($image_url) . '"'
                    . ' alt="' . esc_attr($image_meta_title) . '"'
                    . '>';

                if ($media_link) {
                    $output .= '</a>';
                }

                $this->post_format = 'standard-image';
            }

            return $output ?? '';
        }

        public function featured_video($image_size)
        {
            $output = '';
            global $wgl_related_posts;

            $video_style = rwmb_meta('post_format_video_style');

            if (
                is_single()
                && 'bg_video' !== $video_style
                && empty($wgl_related_posts)
            ) {
                $output .= rwmb_meta('post_format_video_url', 'type=oembed');
            } else {
                $video_link = get_post_meta($this->post_id, 'post_format_video_url')[0] ?? '';
                $video_start = get_post_meta($this->post_id, 'start_video')[0] ?? '';
                $video_end = get_post_meta($this->post_id, 'end_video')[0] ?? '';

                if ('bg_video' == $video_style) {
                    wp_enqueue_script('jarallax', get_template_directory_uri() . '/js/jarallax.min.js');
                    wp_enqueue_script('jarallax-video', get_template_directory_uri() . '/js/jarallax-video.min.js');

                    $class = 'parallax-video';
                    $attr = '';
                    if ($video_link) $attr = ' data-video="'. esc_url($video_link) .'"';
                    if ($video_start) $attr .= ' data-start="'. esc_attr((int) $video_start) .'"';
                    if ($video_end) $attr .= ' data-end="'. esc_attr((int) $video_end) .'"';

                    $output .= '<div class="'.esc_attr($class).'"'.$attr.'>';
                    $output .= '</div>';
                } else {
                    if (has_post_thumbnail()) {
                        $image_url = $this->get_image_url($image_size);

                        if (!$image_url) {
                            // Bailout, if url is empty.
                            return;
                        }

                        $image_meta_title = wp_get_attachment_metadata(get_post_thumbnail_id())['image_meta']['title'] ?? '';

                        $output .= '<div class="wgl-video_popup with_image">';
                        $output .= '<div class="videobox_content">';
                            $output .= '<div class="videobox_background">';
                                $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_meta_title) . '">';
                            $output .= '</div>';

                            if ($video_link) {

                                $lightbox_url = self::get_embed_url($video_link, [], []);

                                $lightbox_options = [
                                    'type' => 'video',
                                    'url' => $lightbox_url,
                                    'modalOptions' => [
                                        'id' => 'elementor-lightbox-' . uniqid(),
                                    ],
                                ];

                                unset($this->render_attributes);

                                $this->add_render_attribute( 'video-lightbox', [
                                    'class' => 'videobox_link videobox',
                                    'data-elementor-open-lightbox' => 'yes',
                                    'data-elementor-lightbox' =>  wp_json_encode( $lightbox_options ),
                                ] );

                                $output .= '<div class="videobox_link_wrapper">';
                                $output .= '<div ' . $this->get_render_attribute_string( 'video-lightbox' ) . '>';
	                                $output .= '<svg class="videobox_icon" viewBox="0 0 10 10"><polygon points="1,0 1,10 10,5"></polygon></svg>';
                                $output .= '</div>';
                                $output .= '</div>';
                            }

                        $output .= '</div>';
                        $output .= '</div>';
                    } else {
                        $output .= rwmb_meta('post_format_video_url', 'type=oembed');
                    }
                }
            }

            return $output;
        }

        public function load_script_dependancy(){
            wp_enqueue_script('swiper', get_template_directory_uri() . '/js/swiper/js/swiper-bundle.min.js', array(), false, false);
            wp_enqueue_style('swiper', get_template_directory_uri() . '/js/swiper/css/swiper-bundle.min.css');
        }

        public function featured_gallery($image_size)
        {
            $gallery_data = rwmb_meta('post_format_gallery');

            if (empty($gallery_data)) {
                // Bailout, if no any data
                return;
            }

            if(class_exists('\Elementor\Plugin')){
                $frontend = \Elementor\Plugin::$instance->frontend;
                if (
                    !\Elementor\Plugin::$instance::$instance->documents->get(get_the_ID())->is_built_with_elementor()
                    || has_excerpt()
                    && !$frontend->has_elementor_in_page()
                ) {
                    $this->load_script_dependancy();
                }
            }else{
                $this->load_script_dependancy();
            }

            $image_size = $this->validate_image_sizes($image_size);

            $carousel_item =  uniqid('wgl_carousel_');
            // Render
            ob_start();
            echo '<div class="wgl-carousel_wrapper">';
                echo '<div class="slider-wrapper wgl-carousel wgl-carousel_swiper swiper-container fade_swiper navigation-posiiton-right" data-item-carousel="' . esc_attr($carousel_item) . '">';
                    echo '<div class="blog-post_media-slider_swiper swiper-wrapper">';

                    $loading = '';
                    foreach ($gallery_data as $image) {
                        $url = aq_resize(
                            $image['full_url'],
                            $image_size[0],
                            $image_size[1],
                            true,
                            true,
                            true
                        ) ?: $image['full_url'];

                        $alt = $image['alt'] ?? '';

                        echo '<div class="item__swiper swiper-slide">',
                            '<span>',
                            '<img src="', esc_url($url), '" alt="', esc_attr($alt), '"', $loading, '>',
                            '</span>',
                        '</div>';

                        $loading = $loading ?: ' loading="lazy"';
                    }
                    echo '</div>';
                echo '</div>';
                echo '<div class="wgl-navigation_wrapper">';
                    echo '<button class="elementor-swiper-button elementor-swiper-button-prev" data-carousel="' . esc_attr($carousel_item) . '"></button>';
                    echo '<button class="elementor-swiper-button elementor-swiper-button-next" data-carousel="' . esc_attr($carousel_item) . '"></button>';
                echo '</div>';
            echo '</div>';

            return ob_get_clean();
        }

        public function featured_quote()
        {
            $quote_author = rwmb_meta('post_format_qoute_name');
            $quote_author_pos = rwmb_meta('post_format_qoute_position') ?: '';
            $quote_author_image = rwmb_meta('post_format_qoute_avatar');
            $quote_author_image = array_shift($quote_author_image)['url'] ?? '';
            $quote_text = rwmb_meta('post_format_qoute_text') ?: '';

            // Quote text
            ob_start();
            if ($quote_text) {
                echo '<h4 class="blog-post_quote-text">',
                    esc_html($quote_text),
                    '</h4>';
            }
            $output = ob_get_clean();

            // Author Image
            ob_start();
            if ($quote_author_image) {
                echo '<img',
                    ' src="', esc_url($quote_author_image), '"',
                    ' class="blog-post_quote-image"',
                    ' alt="', esc_attr($quote_author), '"',
                    '>';
            }
            $autor_avatar = ob_get_clean();

            // Basic quote container
            ob_start();
            if (strlen($quote_author)) :
                echo '<div class="blog-post_quote-author">',
                    $autor_avatar,
                    '<span class="blog-post_quote-author-name">' ,esc_html($quote_author),'</span>',
                    ($quote_author_pos ? '<span class="blog-post_quote-author-pos">, ' . esc_html($quote_author_pos) . '</span>' : ''),
                '</div>';
            endif;

            $output .= ob_get_clean();

            return $output;
        }

        public function featured_link()
        {
            $link = rwmb_meta('post_format_link_url');
            $link_text = rwmb_meta('post_format_link_text');

            if (!$link && !$link_text) {
                // Bailout, if no any data
                return;
            }

            ob_start();
            echo '<h4 class="blog-post_link">';
                if ($link) {
                    echo '<a class="link_post" href="', esc_url($link), '">';
                } else {
                    echo '<span class="link_post">';
                }

                if ($link_text) {
                    echo esc_attr($link_text);
                } else {
                    echo esc_attr($link);
                }

                if ($link) {
                    echo '</a>';
                } else {
                    echo '</span>';
                }
            echo '</h4>';

            return ob_get_clean();
        }

        public function get_image_url($img_size, $img_id = 0)
        {
            $sizes = $this->validate_image_sizes($img_size);
            $img_id = $img_id ?: get_post_thumbnail_id();
            $elementor_editor_active = false;

            $url = wp_get_attachment_image_url($img_id, 'full');
            global $wgl_related_posts;
            if (class_exists('\Elementor\Plugin')) {
                $elementor_editor_active = \Elementor\Plugin::$instance->editor->is_edit_mode();
            }
            if ((is_single() && !$elementor_editor_active) && !$wgl_related_posts){
                return $url;
            }
            if (function_exists('aq_resize')) {
                $url = aq_resize($url, $sizes[0], $sizes[1], true, true, true) ?: $url;
            }

            return $url;
        }

        public function validate_image_sizes($image_size)
        {
            return [
                $image_size['width'] ?? 1170,
                $image_size['height'] ?? 725
            ];
        }

        public function featured_audio()
        {
            $audio_meta = get_post_meta($this->post_id, 'post_format_audio_url');
            if (!empty($audio_meta)) {
                $audio_embed = rwmb_meta('post_format_audio_url', 'type=oembed');
                $output = $audio_embed;
            }

            return $output ?? '';
        }

        public function render_post_meta($args = false, $wrapper = true)
        {
            $this->set_post_meta($args);

            if ($this->show_meta_shares && function_exists('wgl_extensions_social')) {
                wgl_extensions_social()->render_post_list_share();
            }

            if ($this->show_meta_author
                || $this->show_meta_comments
                || $this->show_meta_cats
                || $this->show_meta_views
                || $this->show_meta_date
            ) {
                echo (true == $wrapper) ? '<div class="meta-data">' : '';

                    if ($this->show_meta_date) {
                        ?><span class="post_date">
                        <?php echo get_the_time( get_option( 'date_format' ) ); ?>
                        </span><?php
                    }

                    if ($this->show_meta_cats) {
                        $this->render_post_cats();
                    }

                    if ($this->show_meta_author) {
                        echo '<span class="post_author"><span>',
                            esc_html__('By ', 'transmax'),
                            '<a href="', esc_url(get_author_posts_url(get_the_author_meta('ID'))), '">',
                                esc_html(get_the_author_meta('display_name')),
                            '</a>',
                        '</span></span>';
                    }

                    if ($this->show_meta_comments) {
                        $comments_num = get_comments_number($this->post_id);
                        echo '<span class="comments_post">',
                            '<a href="', esc_url(get_comments_link()), '" title="', esc_attr__('Leave a reply', 'transmax'), '">',
                                '<i class="flaticon-chat"></i>',
                                esc_html($comments_num),
                                ' ',
                                esc_html(_n('Comment', 'Comments', $comments_num, 'transmax')),
                            '</a>',
                        '</span>';
                    }

                    echo !empty($this->show_meta_views) ? $this->show_meta_views : '';

                echo (true == $wrapper) ? '</div>' : '';
            }
        }

        public function render_post_cats()
        {
            $categories = get_the_category();

            if (!$categories) {
                return;
            }
	        ?><span class="post_categories"><?php

            foreach ($categories as $category) :
                $color = get_term_meta($category->term_id, '_category_color', true);
	            ?><span><a <?php
                    echo (!empty($color) ? ' style="color: #' . esc_attr($color) . ';"' : ''),
                    ' href="', esc_url(get_category_link($category->term_id)), '"'; ?> ><?php
                    echo esc_attr($category->cat_name);
                ?></a></span><?php
            endforeach;

	        ?></span><?php
        }

        public function get_excerpt()
        {
            ob_start();
            if (has_excerpt()) {
                the_excerpt();
            }

            return ob_get_clean();
        }

        public function render_excerpt(
            $symbol_count = 85,
            $trim = false
        ) {
            ob_start();
            if (has_excerpt()) {
                the_excerpt();
            } else {
                the_content();
            }
            $post_content = ob_get_clean();

            if ($trim || WGL_Framework::get_option('blog_post_listing_content')) {
                $post_content = preg_replace('#<style>(.*?)</style>#is', '', $post_content);
                if('active' === get_option( 'elementor_experiment-e_optimized_css_loading' )){
                    $post_content = preg_replace('#<style>/\W\W\W(elementor)(.*?)</style>#is', '', $post_content);
                }
                $content_strip_tags = strip_tags($post_content);
                $content_strip_tags = trim($content_strip_tags);
                $post_content = WGL_Framework::modifier_character($content_strip_tags, $symbol_count, '...');
            }

            echo '<div class="blog-post_text">', trim($post_content), '</div>';
        }

        public function get_post_views($postID, $grid = false)
        {
            $count_key = 'post_views_count';
            $counter = get_post_meta($postID, $count_key, true);
            if (empty($counter)) {
                $counter = '0';
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, $counter);
            }

            return '<div class="post_views wgl-views" title="' . esc_attr__('Total Views', 'transmax') . '">'
                . '<span class="post_views_inner"><span class="sl-icon far fa-eye"></span>'
                    . '<span class="sl-count">'
                        . esc_html($counter)
                    . '</span>'
                . '</span>'
            . '</div>';
        }

        public function set_post_views($postID)
        {
            if (current_user_can('administrator')) {
                return;
            }

            $user_ip = function_exists('wgl_get_ip') ? wgl_get_ip() : '0.0.0.0';
            $key = $user_ip . 'x' . $postID;
            $value = [$user_ip, $postID];
            $visited = get_transient($key);

            // check to see if the Post ID/IP ($key) address is currently stored as a transient
            if (false === $visited) {
                // store the unique key, Post ID & IP address for 12 hours if it does not exist
                set_transient($key, $value, 60*60*12);

                $count_key = 'post_views_count';
                $count = get_post_meta($postID, $count_key, true);
                if ('' == $count) {
                    $count = 0;
                    delete_post_meta($postID, $count_key);
                    add_post_meta($postID, $count_key, '0');
                } else {
                    $count++;
                    update_post_meta($postID, $count_key, $count);
                }
            }
        }

        public function render_author_info()
        {
            $name_html = '';

            $user_email = get_the_author_meta('user_email');
            $user_avatar = get_avatar($user_email, 280);
            $user_first = get_the_author_meta('first_name');
            $user_last = get_the_author_meta('last_name');
            $user_description = get_the_author_meta('description');

            $avatar_html = !empty($user_avatar) ? '<div class="author-info_avatar">' . $user_avatar . '</div>' : '';
            if ($user_first || $user_last) {
                $name_html = '<h5 class="author-info_name">'
                    . '<span class="author-excerpt_name">'
                    . esc_html__('About', 'transmax')
                    . '</span>'
                    . esc_html($user_first)
                    . ' '
                    . esc_html($user_last)
                    . '</h5>';
            }

	        $description = $user_description ? '<div class="author-info_description">' . esc_html($user_description) . '</div>' : '';

            $social_medias = '';
            if (function_exists('wgl_user_social_medias_arr')) {
                foreach (wgl_user_social_medias_arr() as $social => $value) if (get_the_author_meta($social)) {
                    $social_medias .= '<a'
                        . ' href="' . esc_url( get_the_author_meta($social) ) . '"'
                        . ' class="author-info_social-link fab fa-' . esc_attr($social) . '"'
                        . '></a>';
                }
            }
            if ($social_medias) {
                $social_medias = '<div class="author-info_social-wrapper">'
                    . '<span class="title_soc_share">'
                    . $social_medias
                    . '</span>'
                    . '</div>';
            }

            if ($name_html && $description) :
                echo '<div class="author-info_wrapper clearfix">',
                    $avatar_html,
                    '<div class="author-info_content">',
                        $name_html,
                        $description,
                        $social_medias,
                    '</div>',
                '</div>';
            endif;
        }

        /**
         * Get embed URL.
         *
         * Retrieve the embed URL for a given video.
         *
         * @param string $video_url        Video URL.
         * @param array  $embed_url_params Optional. Embed parameters. Default is an
         *                                 empty array.
         * @param array  $options          Optional. Embed options. Default is an
         *                                 empty array.
         */
        public static function get_embed_url($video_url, array $embed_url_params = [], array $options = []) {
            $video_properties = self::get_video_properties( $video_url );

            if ( ! $video_properties ) {
                return null;
            }

            $embed_pattern = self::$embed_patterns[ $video_properties['provider'] ];

            $replacements = [
                '{VIDEO_ID}' => $video_properties['video_id'],
            ];

            if ( 'youtube' === $video_properties['provider'] ) {
                $replacements['{NO_COOKIE}'] = ! empty( $options['privacy'] ) ? '-nocookie' : '';
            } elseif ( 'vimeo' === $video_properties['provider'] ) {
                $time_text = '';

                if ( ! empty( $options['start'] ) ) {
                    $time_text = date( 'H\hi\ms\s', $options['start'] ); // PHPCS:Ignore WordPress.DateTime.RestrictedFunctions.date_date
                }

                $replacements['{TIME}'] = $time_text;
            }

            $embed_pattern = str_replace( array_keys( $replacements ), $replacements, $embed_pattern );
            return add_query_arg( $embed_url_params, $embed_pattern );
        }

        /**
         * Get video properties.
         *
         * Retrieve the video properties for a given video URL.
         *
         * @param string $video_url Video URL.
         *
         * @return null|array The video properties, or null.
         */
        public static function get_video_properties( $video_url ) {
            foreach ( self::$provider_match_masks as $provider => $match_mask ) {
                preg_match( $match_mask, $video_url, $matches );

                if ( $matches ) {
                    return [
                        'provider' => $provider,
                        'video_id' => $matches[1],
                    ];
                }
            }

            return null;
        }

        public function add_render_attribute($element, $key = null, $value = null, $overwrite = false)
        {
            if (is_array($element)) {
                foreach ($element as $element_key => $attributes) {
                    $this->add_render_attribute($element_key, $attributes, null, $overwrite);
                }

                return $this;
            }

            if (is_array($key)) {
                foreach ($key as $attribute_key => $attributes) {
                    $this->add_render_attribute($element, $attribute_key, $attributes, $overwrite);
                }

                return $this;
            }

            if (empty( $this->render_attributes[ $element ][ $key ] )) {
                $this->render_attributes[ $element ][ $key ] = [];
            }

            settype($value, 'array');

            if ($overwrite) {
                $this->render_attributes[ $element ][ $key ] = $value;
            } else {
                $this->render_attributes[ $element ][ $key ] = array_merge( $this->render_attributes[ $element ][ $key ], $value );
            }

            return $this;
        }

        public function get_render_attribute_string($element)
        {
            if (empty($this->render_attributes[$element])) {
                return '';
            }

            return ' ' . \WGL_Framework::render_html_attributes( $this->render_attributes[ $element ] );
        }
    }
}
