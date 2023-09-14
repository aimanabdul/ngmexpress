<?php

defined('ABSPATH') || exit;

use WGL_Extensions\WGL_Framework_Global_Variables;

/**
 * Dynamic Styles
 *
 *
 * @package transmax\core\class
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Framework_Dynamic_Styles
{
    protected static $instance;

    private $template_directory_uri;
    private $use_minified;
    private $enqueued_stylesheets = [];
    private $header_page_id;
    private $header_building_tool;
    private $gradient_enabled;

    public function __construct()
    {
        // do nothing.
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function construct()
    {
        $this->template_directory_uri = get_template_directory_uri();
        $this->use_minified = WGL_Framework::get_option('use_minified') ? '.min' : '';
        $this->header_building_tool = WGL_Framework::get_option('header_building_tool');
        $this->gradient_enabled = WGL_Framework::get_mb_option('use-gradient', 'mb_page_colors_switch', 'custom');

        $this->enqueue_styles_and_scripts();
        $this->add_body_classes();
    }

    public function enqueue_styles_and_scripts()
    {
        add_action('wp_enqueue_scripts', [$this, 'frontend_stylesheets']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_scripts']);

        //* Elementor Compatibility
        add_action('wp_enqueue_scripts', [$this, 'get_elementor_css_theme_builder']);
        add_action('wp_enqueue_scripts', [$this, 'elementor_column_fix']);

        add_action('admin_enqueue_scripts', [$this, 'admin_stylesheets']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    public function get_elementor_css_theme_builder()
    {
        $current_post_id = get_the_ID();
        $css_files = [];

        $locations[] = $this->get_elementor_css_cache_header();
        $locations[] = $this->get_elementor_css_cache_header_sticky();
        $locations[] = $this->get_elementor_css_cache_footer();
        $locations[] = $this->get_elementor_css_cache_side_panel();

        foreach ($locations as $location) {
            //* Don't enqueue current post here (let the preview/frontend components to handle it)
            if ($location && $current_post_id !== $location) {
                $css_file = new \Elementor\Core\Files\CSS\Post($location);
                $css_files[] = $css_file;
            }
        }

        if (!empty($css_files)) {
            \Elementor\Plugin::$instance->frontend->enqueue_styles();
            foreach ($css_files as $css_file) {
                $css_file->enqueue();
            }
        }
    }

    public function get_elementor_css_cache_header()
    {
        if (
            !apply_filters('wgl_theme/header/enable', true)
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailtout.
            return;
        }

        if (
            $this->RWMB_is_active()
            && 'custom' === rwmb_meta('mb_customize_header_layout')
            && 'default' !== rwmb_meta('mb_header_content_type')
        ) {
            $this->header_building_tool = 'elementor';
            $this->header_page_id = rwmb_meta('mb_customize_header');
        } else {
            $this->header_page_id = WGL_Framework::get_option('header_page_select');
        }

        if ('elementor' === $this->header_building_tool) {
            return $this->multi_language_support($this->header_page_id, 'header');
        }
    }

    public function get_elementor_css_cache_header_sticky()
    {
        if (
            ! apply_filters( 'wgl_theme/header/enable', true )
            || 'elementor' !== $this->header_building_tool
            || !class_exists( '\Elementor\Core\Files\CSS\Post' )
        ) {
            // Bailtout.
            return;
        }

        $header_sticky_page_id = '';

        if (
            $this->RWMB_is_active()
            && 'custom' === rwmb_meta( 'mb_customize_header_layout' )
            && 'default' !== rwmb_meta( 'mb_sticky_header_content_type' )
        ) {
            $header_sticky_page_id = rwmb_meta( 'mb_customize_sticky_header' );
        } elseif ( WGL_Framework::get_option( 'header_sticky' ) ) {
            $header_sticky_page_id = WGL_Framework::get_option( 'header_sticky_page_select' );
        }

        return $this->multi_language_support( $header_sticky_page_id, 'header' );
    }

    public function get_elementor_css_cache_footer()
    {
        $footer = apply_filters('wgl_theme/footer/enable', true);
        $footer_switch = $footer['footer_switch'] ?? '';

        if (
            !$footer_switch
            || 'elementor' !== WGL_Framework::get_mb_option('footer_building_tool', 'mb_footer_switch', 'on')
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailout.
            return;
        }

        $footer_page_id = WGL_Framework::get_mb_option('footer_page_select', 'mb_footer_switch', 'on');

        return $this->multi_language_support($footer_page_id, 'footer');
    }

    public function get_elementor_css_cache_side_panel()
    {
        if (
            !WGL_Framework::get_option('side_panel_enabled')
            || 'elementor' !== WGL_Framework::get_mb_option('side_panel_building_tool', 'mb_customize_side_panel', 'custom')
            || !class_exists('\Elementor\Core\Files\CSS\Post')
        ) {
            // Bailout.
            return;
        }

        $sp_page_id = WGL_Framework::get_mb_option('side_panel_page_select', 'mb_customize_side_panel', 'custom');

        return $this->multi_language_support($sp_page_id, 'side_panel');
    }

    public function multi_language_support($page_id, $page_type)
    {
        if (!$page_id) {
            // Bailout.
            return;
        }

        $page_id = intval($page_id);

        if (class_exists('Polylang') && function_exists('pll_current_language')) {
            $currentLanguage = pll_current_language();
            $translations = PLL()->model->post->get_translations($page_id);

            $polylang_id = $translations[$currentLanguage] ?? '';
            $page_id = $polylang_id ?: $page_id;
        }

        if (class_exists('SitePress')) {
            $wpml_id = wpml_object_id_filter($page_id, $page_type, false, ICL_LANGUAGE_CODE);
            if (
                $wpml_id
                && 'trash' !== get_post_status($wpml_id)
            ) {
                $page_id = $wpml_id;
            }
        }

        return $page_id;
    }

    public function elementor_column_fix()
    {
        $css = '.elementor-container > .elementor-row > .elementor-column > .elementor-element-populated,'
            . '.elementor-container > .elementor-column > .elementor-element-populated {'
                . 'padding-top: 0;'
                . 'padding-bottom: 0;'
            . '}';

        $css .= '.elementor-column-gap-default > .elementor-row > .elementor-column > .elementor-element-populated,'
            . '.elementor-column-gap-default > .elementor-column > .theiaStickySidebar > .elementor-element-populated,'
            . '.elementor-column-gap-default > .elementor-column > .elementor-element-populated {'
                . 'padding-left: 15px;'
                . 'padding-right: 15px;'
            . '}';

        wp_add_inline_style('elementor-frontend', $css);
    }

    public function frontend_stylesheets()
    {
        wp_enqueue_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme-info',
            get_bloginfo('stylesheet_url'),
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        $this->enqueue_css_variables();
        $this->enqueue_additional_styles();
		if (is_rtl()) {
			wp_enqueue_style('transmax-rtl', get_template_directory_uri() . '/css/rtl.css');
		} else {
            $this->enqueue_style('main', '/css/');
            $this->enqueue_pluggable_styles();
            $this->enqueue_style('responsive', '/css/', $this->enqueued_stylesheets);
        }

        $this->enqueue_style('dynamic', '/css/', $this->enqueued_stylesheets);
    }

    public function enqueue_css_variables()
    {
        return wp_add_inline_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme-info',
            $this->retrieve_css_variables_and_extra_styles()
        );
    }

    public function enqueue_additional_styles()
    {
        wp_enqueue_style('font-awesome-5-all', $this->template_directory_uri . '/css/font-awesome-5.min.css');

        wp_enqueue_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-flaticon',
            $this->template_directory_uri . '/fonts/flaticon/flaticon.css',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );
    }

    public function retrieve_css_variables_and_extra_styles()
    {
        $root_vars = $extra_css = '';

        /**
         * Color Variables
         */
        if (
            class_exists('RWMB_Loader')
            && 'custom' === WGL_Framework::get_mb_option('page_colors_switch')
        ) {
            $theme_primary_color = WGL_Framework::get_mb_option('theme-primary-color');
            $theme_secondary_color = WGL_Framework::get_mb_option('theme-secondary-color');

            $bg_body = WGL_Framework::get_mb_option('body_background_color');

            $main_font_color = WGL_Framework::get_mb_option('theme-content-color');
            $h_font_color = WGL_Framework::get_mb_option('theme-headings-color');

            $button_color_idle = WGL_Framework::get_mb_option('button-color-idle');
            $button_color_hover = WGL_Framework::get_mb_option('button-color-hover');
            $button_bg_color_idle = WGL_Framework::get_mb_option('button-bg-color-idle');
            $button_bg_color_hover = WGL_Framework::get_mb_option('button-bg-color-hover');

            $scroll_up_arrow_color = WGL_Framework::get_mb_option('scroll_up_arrow_color');
            $scroll_up_bg_color = WGL_Framework::get_mb_option('scroll_up_bg_color');

            $this->gradient_enabled && $theme_gradient_from = WGL_Framework::get_mb_option('theme-gradient-from');
            $this->gradient_enabled && $theme_gradient_to = WGL_Framework::get_mb_option('theme-gradient-to');
        } else {
            $theme_primary_color = WGL_Framework_Global_Variables::get_primary_color();
            $theme_secondary_color = WGL_Framework_Global_Variables::get_secondary_color();

            $bg_body = WGL_Framework_Global_Variables::get_bg_body_color();

            $main_font_color = WGL_Framework_Global_Variables::get_main_font_color();
            $h_font_color = WGL_Framework_Global_Variables::get_h_font_color();

            $button_color_idle = WGL_Framework_Global_Variables::get_btn_color_idle();
            $button_color_hover = WGL_Framework_Global_Variables::get_btn_color_hover();
            $button_bg_color_idle = WGL_Framework_Global_Variables::get_btn_bg_color_idle();
            $button_bg_color_hover = WGL_Framework_Global_Variables::get_btn_bg_color_hover();

            $scroll_up_arrow_color = WGL_Framework::get_option('scroll_up_arrow_color');
            $scroll_up_bg_color = WGL_Framework::get_option('scroll_up_bg_color');

            $this->gradient_enabled && $theme_gradient = WGL_Framework::get_option('theme-gradient');
        }

	    $root_vars .= '--transmax-primary-color: ' . ( $theme_primary_color ? esc_attr($theme_primary_color) : 'unset') . ';';
	    $root_vars .= '--transmax-secondary-color: ' . ( $theme_secondary_color ? esc_attr($theme_secondary_color) : 'unset') . ';';

	    $root_vars .= '--transmax-button-color-idle: ' . ( $button_color_idle ? esc_attr($button_color_idle) : 'unset' ) . ';';
	    $root_vars .= '--transmax-button-color-hover: ' . ( $button_color_hover ? esc_attr($button_color_hover) : 'unset' ) . ';';
	    $root_vars .= '--transmax-button-bg-color-idle: ' . ( $button_bg_color_idle ? esc_attr($button_bg_color_idle) : 'unset' ) . ';';
	    $root_vars .= '--transmax-button-bg-color-hover: ' . ( $button_bg_color_hover ? esc_attr($button_bg_color_hover) : 'unset' ) . ';';

	    $root_vars .= '--transmax-back-to-top-color: ' . ( $scroll_up_arrow_color ? esc_attr($scroll_up_arrow_color) : 'unset' ) . ';';
	    $root_vars .= '--transmax-back-to-top-background: ' . ( $scroll_up_bg_color ? esc_attr($scroll_up_bg_color) : 'unset' ) . ';';

	    $root_vars .= '--transmax-body-background: ' . ( $bg_body ? esc_attr($bg_body) : 'unset' ) . ';';
        $root_vars .= '--transmax-body-rgb-background: ' . esc_attr(WGL_Framework::HexToRGB($bg_body)) . ';';

        $root_vars .= '--transmax-primary-rgb: ' . ( $theme_primary_color ? esc_attr(WGL_Framework::HexToRGB($theme_primary_color)) : 'unset' ) . ';';
        $root_vars .= '--transmax-secondary-rgb: ' . ( $theme_secondary_color ? esc_attr(WGL_Framework::HexToRGB($theme_secondary_color)) : 'unset' ) . ';';
        $root_vars .= '--transmax-content-rgb: ' . ( $main_font_color ? esc_attr(WGL_Framework::HexToRGB($main_font_color)) : 'unset' ) . ';';
        $root_vars .= '--transmax-header-rgb: ' . ( $h_font_color ? esc_attr(WGL_Framework::HexToRGB($h_font_color)) : 'unset' ) . ';';

	    //* ↑ color variables

        /**
         * Headings Variables
         */
        $header_font = WGL_Framework::get_option('header-font');
        $root_vars .= '--transmax-header-font-family: ' . ( $header_font['font-family'] ? esc_attr($header_font['font-family']) : 'unset' ) . ';';
        $root_vars .= '--transmax-header-font-weight: ' . ( $header_font['font-weight'] ? esc_attr($header_font['font-weight']) : 'unset' ) . ';';
        $root_vars .= '--transmax-header-font-color: ' . ( $h_font_color ? esc_attr($h_font_color) : 'unset' ) . ';';

        for ($i = 1; $i <= 6; $i++) {
            ${'header-h' . $i} = WGL_Framework::get_option('header-h' . $i);

            $root_vars .= '--transmax-h' . $i . '-font-family: ' . (${'header-h' . $i}['font-family'] ? esc_attr(${'header-h' . $i}['font-family']) : 'unset') . ';';
            $root_vars .= '--transmax-h' . $i . '-font-size: ' . (${'header-h' . $i}['font-size'] ? esc_attr(${'header-h' . $i}['font-size']) : 'unset') . ';';
            $root_vars .= '--transmax-h' . $i . '-line-height: ' . (${'header-h' . $i}['line-height'] ? esc_attr(${'header-h' . $i}['line-height']) : 'unset') . ';';
            $root_vars .= '--transmax-h' . $i . '-font-weight: ' . (${'header-h' . $i}['font-weight'] ? esc_attr(${'header-h' . $i}['font-weight']) : 'unset') . ';';
            $root_vars .= '--transmax-h' . $i . '-text-transform: ' . (${'header-h' . $i}['text-transform'] ? esc_attr(${'header-h' . $i}['text-transform']) : 'unset') . ';';
        }
        //* ↑ headings variables

        /**
         * Content Variables
         */
        $main_font = WGL_Framework::get_option('main-font');
        $content_font_size = $main_font['font-size'] ?? '';
        $content_line_height = $main_font['line-height'] ?? '';
        $content_line_height = $content_line_height ? round(((int) $content_line_height / (int) $content_font_size), 3) : '';

	    $root_vars .= '--transmax-content-font-family: ' . ( $main_font['font-family'] ? esc_attr($main_font['font-family']) : 'unset') . ';';
	    $root_vars .= '--transmax-content-font-size: ' . ( $content_font_size ? esc_attr($content_font_size) : 'unset') . ';';
	    $root_vars .= '--transmax-content-line-height: ' . ( $content_line_height ? esc_attr($content_line_height) : 'unset') . ';';
	    $root_vars .= '--transmax-content-font-weight: ' . ( $main_font['font-weight'] ? esc_attr($main_font['font-weight']) : 'unset') . ';';
	    $root_vars .= '--transmax-content-color: ' . ( $main_font_color ? esc_attr($main_font_color) : 'unset') . ';';
        //* ↑ content variables

        /**
         * Menu Variables
         */
        $menu_font = WGL_Framework::get_option('menu-font');
	    $root_vars .= '--transmax-menu-font-family: ' . ( $menu_font['font-family'] ? esc_attr($menu_font['font-family']) : 'unset') . ';';
	    $root_vars .= '--transmax-menu-font-size: ' . ( $menu_font['font-size'] ? esc_attr($menu_font['font-size']) : 'unset') . ';';
	    $root_vars .= '--transmax-menu-line-height: ' . ( $menu_font['line-height'] ? esc_attr($menu_font['line-height']) : 'unset') . ';';
	    $root_vars .= '--transmax-menu-font-weight: ' . ( $menu_font['font-weight'] ? esc_attr($menu_font['font-weight']) : 'unset') . ';';
        //* ↑ menu variables

        /**
         * Submenu Variables
         */
        $sub_menu_color = WGL_Framework::get_option('sub_menu_color') ?? 'unset';
        $sub_menu_bg = WGL_Framework::get_option('sub_menu_background')['rgba'] ?? 'unset';
        $sub_menu_font = WGL_Framework::get_option('sub-menu-font');
	    $root_vars .= '--transmax-submenu-font-family: ' . ( $sub_menu_font['font-family'] ? esc_attr($sub_menu_font['font-family']) : 'unset') . ';';
	    $root_vars .= '--transmax-submenu-font-size: ' . ( $sub_menu_font['font-size'] ? esc_attr($sub_menu_font['font-size']) : 'unset') . ';';
	    $root_vars .= '--transmax-submenu-line-height: ' . ( $sub_menu_font['line-height'] ? esc_attr($sub_menu_font['line-height']) : 'unset') . ';';
	    $root_vars .= '--transmax-submenu-font-weight: ' . ( $sub_menu_font['font-weight'] ? esc_attr($sub_menu_font['font-weight']) : 'unset') . ';';
        $root_vars .= '--transmax-submenu-color: ' . ( $sub_menu_color ? esc_attr($sub_menu_color) : 'unset' ) . ';';
        $root_vars .= '--transmax-submenu-background: ' . ( $sub_menu_bg ? esc_attr($sub_menu_bg) : 'unset' ) . ';';

        $mob_sub_menu_color = WGL_Framework::get_option('mobile_sub_menu_color') ?? 'unset';
        $mob_sub_menu_bg = WGL_Framework::get_option('mobile_sub_menu_background')['rgba'] ?? 'unset';
        $mob_sub_menu_overlay = WGL_Framework::get_option('mobile_sub_menu_overlay')['rgba'] ?? 'unset';
        $root_vars .= '--transmax-submenu-mobile-color: ' . esc_attr($mob_sub_menu_color) . ';';
        $root_vars .= '--transmax-submenu-mobile-background: ' . esc_attr($mob_sub_menu_bg) . ';';
        $root_vars .= '--transmax-submenu-mobile-overlay: ' . esc_attr($mob_sub_menu_overlay) . ';';

        $sub_menu_border = WGL_Framework::get_option('header_sub_menu_bottom_border');
        if ($sub_menu_border) {
            $sub_menu_border_height = WGL_Framework::get_option('header_sub_menu_border_height')['height'] ?? '0';
            $sub_menu_border_color = WGL_Framework::get_option('header_sub_menu_bottom_border_color')['rgba'] ?? 'unset';

            $extra_css .= '.primary-nav ul li ul li:not(:last-child),'
                . '.sitepress_container > .wpml-ls ul ul li:not(:last-child) {'
                    . ($sub_menu_border_height ? 'border-bottom-width: ' . esc_attr($sub_menu_border_height) . 'px;' : '')
                    . ($sub_menu_border_color ? 'border-bottom-color: ' . esc_attr($sub_menu_border_color) . ';' : '')
                    . 'border-bottom-style: solid;'
                . '}';
        }
        //* ↑ submenu variables

	    /**
	     * Encoded SVG variables
	     */
	    $root_vars .= '--transmax-bg-caret: url(\'data:image/svg+xml; utf8, <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="451.847px" height="451.847px" viewBox="0 0 451.847 451.847" preserveAspectRatio="none" fill="%23707477"><path xmlns="http://www.w3.org/2000/svg" d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z"/></svg>\');';
        $root_vars .= '--transmax-svg-arrow: url(\'data:image/svg+xml; utf8, <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 490.787 490.787" xml:space="preserve"><path d="M362.671,490.787c-2.831,0.005-5.548-1.115-7.552-3.115L120.452,253.006 c-4.164-4.165-4.164-10.917,0-15.083L355.119,3.256c4.093-4.237,10.845-4.354,15.083-0.262c4.237,4.093,4.354,10.845,0.262,15.083 c-0.086,0.089-0.173,0.176-0.262,0.262L143.087,245.454l227.136,227.115c4.171,4.16,4.179,10.914,0.019,15.085 C368.236,489.664,365.511,490.792,362.671,490.787z"/><path d="M362.671,490.787c-2.831,0.005-5.548-1.115-7.552-3.115L120.452,253.006c-4.164-4.165-4.164-10.917,0-15.083L355.119,3.256 c4.093-4.237,10.845-4.354,15.083-0.262c4.237,4.093,4.354,10.845,0.262,15.083c-0.086,0.089-0.173,0.176-0.262,0.262 L143.087,245.454l227.136,227.115c4.171,4.16,4.179,10.914,0.019,15.085C368.236,489.664,365.511,490.792,362.671,490.787z"/></svg>\');';

        //* ↑ encoded SVG variables

        /**
         * Footer Variables
         */
        if (
            WGL_Framework::get_option('footer_switch')
            && 'widgets' === WGL_Framework::get_option('footer_building_tool')
        ) {
	        $footer_text_color = WGL_Framework::get_option('footer_text_color') ?? 'unset';
	        $footer_heading_color = WGL_Framework::get_option('footer_heading_color') ?? 'unset';
	        $copyright_text_color = WGL_Framework::get_mb_option('copyright_text_color', 'mb_copyright_switch', 'on') ?? 'unset';
            $root_vars .= '--transmax-footer-content-color: ' . esc_attr($footer_text_color) . ';';
            $root_vars .= '--transmax-footer-heading-color: ' . esc_attr($footer_heading_color) . ';';
            $root_vars .= '--transmax-copyright-content-color: ' . esc_attr($copyright_text_color) . ';';
        }
        //* ↑ footer variables

        /**
         * Side Panel Variables
         */
        $sidepanel_title_color = WGL_Framework::get_mb_option('side_panel_title_color', 'mb_customize_side_panel', 'custom') ?? 'unset';
        $root_vars .= '--transmax-sidepanel-title-color: ' . esc_attr($sidepanel_title_color) . ';';
        //* ↑ side panel variables

	    /**
         * Elementor Container
         */
        $root_vars .= '--transmax-elementor-container-width: ' . $this->get_elementor_container_width() . 'px;';
        //* ↑ elementor container

        $css_variables = ':root {' . $root_vars . '}';

        $extra_css .= $this->get_mobile_header_extra_css();
        $extra_css .= $this->get_page_title_responsive_extra_css();

        return $css_variables . $extra_css;
    }

    public function get_elementor_container_width()
    {
        if (
            did_action('elementor/loaded')
            && defined('ELEMENTOR_VERSION')
        ) {
            if (version_compare(ELEMENTOR_VERSION, '3.0', '<')) {
                $container_width = get_option('elementor_container_width') ?: 1140;
            } else {
                $kit_id = (new \Elementor\Core\Kits\Manager())->get_active_id();
                $meta_key = \Elementor\Core\Settings\Page\Manager::META_KEY;
                $kit_settings = get_post_meta($kit_id, $meta_key, true);
                $container_width = $kit_settings['container_width']['size'] ?? 1140;
            }
        }

        return $container_width ?? 1170;
    }

    protected function get_mobile_header_extra_css()
    {
        $extra_css = '';

        $this->get_elementor_css_cache_header();

        if (WGL_Framework::get_option('mobile_header')) {
            $mobile_background = WGL_Framework::get_option('mobile_background')['rgba'] ?? '';
            $mobile_color = WGL_Framework::get_option('mobile_color');

            $extra_css .= '.wgl-theme-header {'
                    . 'background-color: ' . esc_attr($mobile_background) . ' !important;'
                    . 'color: ' . esc_attr($mobile_color) . ' !important;'
                . '}';
        }

        $extra_css .= 'header.wgl-theme-header .wgl-mobile-header {'
                . 'display: block;'
            . '}'
            . '.wgl-site-header,'
            . '.wgl-theme-header .primary-nav {'
                . 'display: none;'
            . '}'
            . '.wgl-theme-header .hamburger-box {'
                . 'display: inline-flex;'
            . '}'
            . 'header.wgl-theme-header .mobile_nav_wrapper .primary-nav {'
                . 'display: block;'
            . '}'
            . '.wgl-theme-header .wgl-sticky-header {'
                . 'display: none;'
            . '}'
            . '.wgl-page-socials {'
                . 'display: none;'
            . '}';

        $mobile_sticky = WGL_Framework::get_option('mobile_sticky');

        if (WGL_Framework::get_option('mobile_over_content')) {
            $extra_css .= 'body .wgl-theme-header {'
                    . 'position: absolute;'
                    . 'z-index: 99;'
                    . 'width: 100%;'
                    . 'left: 0;'
                    . 'top: 0;'
                . '}';

            if ($mobile_sticky) {
                $extra_css .= 'body .wgl-theme-header .wgl-mobile-header {'
                        . 'position: absolute;'
                        . 'left: 0;'
                        . 'width: 100%;'
                    . '}';
            }

        } else {
            $extra_css .= 'body .wgl-theme-header.header_overlap {'
                    . 'position: relative;'
                    . 'z-index: 2;'
                . '}';
        }

        if ($mobile_sticky) {
            $extra_css .= 'body .wgl-theme-header,'
                . 'body .wgl-theme-header.header_overlap {'
                .   'position: sticky;'
                .   'top: 0;'
                . '}'
                . '.admin-bar .wgl-theme-header{'
                .   'top: 32px;'
                . '}'
                . 'body.mobile_switch_on{'
                .   'position: static !important;'
                . '}'
                . 'body.admin-bar .sticky_mobile .wgl-menu_outer{'
                .   'top: 0px;'
                .   'height: 100vh;'
                . '}';
        }

        return '@media only screen and (max-width: ' . $this->get_header_mobile_breakpoint() . 'px) {' . $extra_css . '}';
    }

    protected function get_header_mobile_breakpoint()
    {
        $elementor_breakpoint = '';

        if (
            'elementor' === $this->header_building_tool
            && $this->header_page_id
            && did_action('elementor/loaded')
        ) {
            $settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers('page');
            $settings_model = $settings_manager->get_model($this->header_page_id);

            $elementor_breakpoint = $settings_model->get_settings('mobile_breakpoint');
        }

        return $elementor_breakpoint ?: (int) WGL_Framework::get_option('header_mobile_queris');
    }

    protected function get_page_title_responsive_extra_css()
    {
        $page_title_resp = WGL_Framework::get_option('page_title_resp_switch');

        if (
            $this->RWMB_is_active()
            && 'on' === rwmb_meta('mb_page_title_switch')
            && rwmb_meta('mb_page_title_resp_switch')
        ) {
            $page_title_resp = true;
        }

        if (!$page_title_resp) {
            // Bailout, if no any responsive logic
            return;
        }

        $pt_padding = WGL_Framework::get_mb_option('page_title_resp_padding', 'mb_page_title_resp_switch', true);

        $extra_css = '.page-header {'
            . (!empty($pt_padding['padding-top']) ? 'padding-top: ' . esc_attr((int) $pt_padding['padding-top']) . 'px !important;' : '')
            . (!empty($pt_padding['padding-bottom']) ? 'padding-bottom: ' . esc_attr((int) $pt_padding['padding-bottom']) . 'px !important;' : '')
            . 'min-height: auto !important;'
        . '}';

        $breadcrumbs_switch = WGL_Framework::get_mb_option('page_title_resp_breadcrumbs_switch', 'mb_page_title_resp_switch', true);

        //* Title
        $pt_font = WGL_Framework::get_mb_option('page_title_resp_font', 'mb_page_title_resp_switch', true);
        $pt_color = !empty($pt_font['color']) ? 'color: ' . esc_attr($pt_font['color']) . ' !important;' : '';
        $pt_f_size = !empty($pt_font['font-size']) ? ' font-size: ' . esc_attr((int) $pt_font['font-size']) . 'px !important;' : '';
        $pt_line_height = !empty($pt_font['line-height']) ? ' line-height: ' . esc_attr((int) $pt_font['line-height']) . 'px !important;' : '';
        $pt_additional_style = !(bool) $breadcrumbs_switch ? ' margin-bottom: 0 !important;' : '';
        $title_style = $pt_color . $pt_f_size . $pt_line_height . $pt_additional_style;

        $extra_css .= '.page-header_content .page-header_title {' . $title_style . '}';

        //* Breadcrumbs
        $breadcrumbs_font = WGL_Framework::get_mb_option('page_title_resp_breadcrumbs_font', 'mb_page_title_resp_switch', true);
        $breadcrumbs_color = !empty($breadcrumbs_font['color']) ? 'color: ' . esc_attr($breadcrumbs_font['color']) . ' !important;' : '';
        $breadcrumbs_f_size = !empty($breadcrumbs_font['font-size']) ? 'font-size: ' . esc_attr((int) $breadcrumbs_font['font-size']) . 'px !important;' : '';
        $breadcrumbs_line_height = !empty($breadcrumbs_font['line-height']) ? 'line-height: ' . esc_attr((int) $breadcrumbs_font['line-height']) . 'px !important;' : '';
        $breadcrumbs_display = !(bool) $breadcrumbs_switch ? 'display: none !important;' : '';
        $breadcrumbs_style = $breadcrumbs_color . $breadcrumbs_f_size . $breadcrumbs_line_height . $breadcrumbs_display;

        $extra_css .= '.page-header_content .page-header_breadcrumbs {' . $breadcrumbs_style . '}'
            . '.page-header_breadcrumbs .divider:not(:last-child):before {width: 10px;}';

        //* Blog Single Type 3
        if (
            is_single()
            && 'post' === get_post_type()
            && '3' === WGL_Framework::get_mb_option('single_type_layout', 'mb_post_layout_conditional', 'custom')
        ) {
            $blog_t3_padding = WGL_Framework::get_option('single_padding_layout_3');
	        $blog_t3_p_top = esc_attr($blog_t3_padding['padding-top']) ?? '';
	        $blog_t3_p_bottom = esc_attr($blog_t3_padding['padding-bottom']) ?? '';
            $blog_t3_p_top_responsive = $blog_t3_p_top > $blog_t3_p_bottom ? 80 + (int) $blog_t3_p_bottom : (int) $blog_t3_p_top;
            $blog_t3_p_top_responsive = $blog_t3_p_top_responsive > 100 ? 100 : $blog_t3_p_top_responsive;

            $extra_css .= '.single-post .post_featured_bg > .blog-post {'
                    . 'padding-top: ' . $blog_t3_p_top_responsive . 'px !important;'
                . '}';
        }

        $pt_breakpoint = (int) WGL_Framework::get_mb_option('page_title_resp_resolution', 'mb_page_title_resp_switch', true);

        return '@media (max-width: ' . $pt_breakpoint . 'px) {' . $extra_css . '}';
    }

    /**
     * Enqueue theme stylesheets
     *
     * Function keeps track of already enqueued stylesheets and stores them in `enqueued_stylesheets[]`
     *
     * @param string   $tag      Unprefixed handle.
     * @param string   $file_dir Optional. Path to stylesheet folder, relative to Transmax root folder.
     * @param string[] $deps     Optional. An array of registered stylesheet handles this stylesheet depends on.
     */
    public function enqueue_style($tag, $file_dir = '/css/pluggable/', $deps = [])
    {
        $prefixed_tag = WGL_Framework_Global_Variables::get_theme_slug() . '-' . $tag;
        $this->enqueued_stylesheets[] = $prefixed_tag;

        wp_enqueue_style(
            $prefixed_tag,
            $this->template_directory_uri . $file_dir . $tag . $this->use_minified . '.css',
            $deps,
            WGL_Framework_Global_Variables::get_theme_version()
        );
    }

    public function enqueue_pluggable_styles()
    {
        //* Preloader
        WGL_Framework::get_option('preloader') && $this->enqueue_style('preloader');

        //* Page 404|Search
        (is_404() || is_search()) && $this->enqueue_style('page-404');

        //* Gutenberg
        WGL_Framework::get_option('disable_wp_gutenberg')
            ? wp_dequeue_style('wp-block-library')
            : $this->enqueue_style('gutenberg');

        //* Post Single
        if (is_single()) {
            $post_type = get_post()->post_type;
            if (
                'post' === $post_type
                || 'portfolio' === $post_type
            ) {
                $this->enqueue_style('blog-post-single');
            } elseif ('team' === $post_type) {
                $this->enqueue_style('team-post-single');
            }
        }

        //* WooCommerce Plugin
        class_exists('WooCommerce') && $this->enqueue_style('woocommerce');

        //* Side Panel
        WGL_Framework::get_option('side_panel_enabled') && $this->enqueue_style('side-panel');

        //* WPML plugin
        class_exists('SitePress') && $this->enqueue_style('wpml');

        //* Polylang plugin
        if (function_exists('pll_the_languages')) {
            $this->enqueue_style('polylang');
        }
    }

    public function frontend_scripts()
    {
        wp_enqueue_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme-addons',
            $this->template_directory_uri . '/js/theme-addons' . $this->use_minified . '.js',
            ['jquery'],
            WGL_Framework_Global_Variables::get_theme_version(),
            true
        );

        wp_enqueue_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme',
            $this->template_directory_uri . '/js/theme.js',
            ['jquery'],
            WGL_Framework_Global_Variables::get_theme_version(),
            true
        );

        wp_localize_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-theme',
            'wgl_core',
            ['ajaxurl' => esc_url(admin_url('admin-ajax.php'))]
        );

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    public function admin_stylesheets()
    {
        wp_enqueue_style(
            WGL_Framework_Global_Variables::get_theme_slug() . '-admin',
            $this->template_directory_uri . '/core/admin/css/admin.css',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        wp_enqueue_style('font-awesome-5-all', $this->template_directory_uri . '/css/font-awesome-5.min.css');

        wp_enqueue_style('wp-color-picker');
    }

    public function admin_scripts()
    {
        wp_enqueue_media();

        wp_enqueue_script('wp-color-picker');
	    wp_localize_script('wp-color-picker', 'wpColorPickerL10n', [
		    'clear' => esc_html__('Clear', 'transmax'),
		    'clearAriaLabel' => esc_html__('Clear color', 'transmax'),
		    'defaultString' => esc_html__('Default', 'transmax'),
		    'defaultAriaLabel' => esc_html__('Select default color', 'transmax'),
		    'pick' => esc_html__('Select', 'transmax'),
		    'defaultLabel' => esc_html__('Color value', 'transmax'),
        ]);

        wp_enqueue_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-admin',
            $this->template_directory_uri . '/core/admin/js/admin.js',
            [],
            WGL_Framework_Global_Variables::get_theme_version()
        );

        $currentTheme = wp_get_theme();
        $theme_name = false == $currentTheme->parent()
            ? wp_get_theme()->get('Name')
            : wp_get_theme()->parent()->get('Name');
        $theme_name = trim($theme_name);

        $purchase_code = $email = '';
        if (WGL_Framework::wgl_theme_activated()) {
            $theme_details = get_option('wgl_licence_validated');
            $purchase_code = $theme_details['purchase'] ?? '';
            $email = $theme_details['email'] ?? '';
        }

        wp_localize_script(
            WGL_Framework_Global_Variables::get_theme_slug() . '-admin',
            'wgl_verify',
            [
                'ajaxurl' => esc_js(admin_url('admin-ajax.php')),
                'wglUrlActivate' => esc_js(WGL_Theme_Verify::get_instance()->api . 'verification'),
                'wglUrlDeactivate' => esc_js(WGL_Theme_Verify::get_instance()->api . 'deactivate'),
                'domainUrl' => esc_js(site_url('/')),
                'themeName' => esc_js($theme_name),
                'purchaseCode' => esc_js($purchase_code),
                'email' => esc_js($email),
                'message' => esc_js(esc_html__('Thank you, your license has been validated', 'transmax')),
                'ajax_nonce' => esc_js(wp_create_nonce('_notice_nonce'))
            ]
        );
    }

    protected function add_body_classes()
    {
        add_filter('body_class', function (Array $classes) {
            if ($this->gradient_enabled) {
                $classes[] = 'theme-gradient';
            }

            if (
                is_single()
                && 'post' === get_post_type(get_queried_object_id())
                && '3' === WGL_Framework::get_mb_option('single_type_layout', 'mb_post_layout_conditional', 'custom')
            ) {
                $classes[] = WGL_Framework_Global_Variables::get_theme_slug() . '-blog-type-overlay';
            }

            return $classes;
        });
    }

    public function RWMB_is_active()
    {
        $id = !is_archive() ? get_queried_object_id() : 0;

        return class_exists('RWMB_Loader') && 0 !== $id;
    }
}

function wgl_dynamic_styles()
{
    return WGL_Framework_Dynamic_Styles::instance();
}

wgl_dynamic_styles()->construct();
