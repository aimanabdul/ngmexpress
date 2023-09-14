<?php
namespace WGL_Extensions;

defined('ABSPATH') || exit;

use WGL_Framework;

if (!class_exists('WGL_Framework_Global_Variables')) {
    /**
     * Transmax Global Variables
     *
     *
     * @package transmax\core\class
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Framework_Global_Variables
    {
        protected static $theme_slug;
        protected static $theme_version;
        protected static $primary_color;
        protected static $secondary_color;
        protected static $tertiary_color;
        protected static $main_font_color;
        protected static $h_font_color;
        protected static $bg_body;
        protected static $btn_color_idle;
        protected static $btn_color_hover;
        protected static $btn_bg_color_idle;
        protected static $btn_bg_color_hover;

        function __construct()
        {
            if (class_exists('\WGL_Framework')) {
                $this->set_variables();
            }
        }

        protected function set_variables()
        {
            // General
            self::$theme_slug = str_replace('-child', '', wp_get_theme()->get('TextDomain'));
            self::$theme_version = wp_get_theme()->get('Version') ?? false;
            // Colors
            self::$primary_color = esc_attr(WGL_Framework::get_option('theme-primary-color'));
            self::$secondary_color = esc_attr(WGL_Framework::get_option('theme-secondary-color'));
            self::$main_font_color = esc_attr(WGL_Framework::get_option('theme-content-color'));
            self::$h_font_color = esc_attr(WGL_Framework::get_option('theme-headings-color'));
            self::$bg_body = esc_attr(WGL_Framework::get_option('body-background-color'));
            self::$btn_color_idle = esc_attr(WGL_Framework::get_option('button-color-idle'));
            self::$btn_color_hover = esc_attr(WGL_Framework::get_option('button-color-hover'));
            self::$btn_bg_color_idle = esc_attr(WGL_Framework::get_option('button-bg-color-idle'));
            self::$btn_bg_color_hover = esc_attr(WGL_Framework::get_option('button-bg-color-hover'));
        }

        public static function get_theme_slug()
        {
            return self::$theme_slug;
        }

        public static function get_theme_version()
        {
            return self::$theme_version;
        }

        public static function get_primary_color()
        {
            return self::$primary_color;
        }

        public static function get_secondary_color()
        {
            return self::$secondary_color;
        }

        public static function get_main_font_color()
        {
            return self::$main_font_color;
        }

        public static function get_h_font_color()
        {
            return self::$h_font_color;
        }

        public static function get_bg_body_color()
        {
            return self::$bg_body;
        }

        public static function get_btn_color_idle()
        {
            return self::$btn_color_idle;
        }

        public static function get_btn_color_hover()
        {
            return self::$btn_color_hover;
        }

        public static function get_btn_bg_color_idle()
        {
            return self::$btn_bg_color_idle;
        }

        public static function get_btn_bg_color_hover()
        {
            return self::$btn_bg_color_hover;
        }

    }

    new WGL_Framework_Global_Variables();
}
