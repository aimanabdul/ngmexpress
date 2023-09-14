<?php
namespace WGL_Extensions\Modules;

defined('ABSPATH') || exit;

use WGL_Extensions\{
    Includes\WGL_Elementor_Helper,
    WGL_Framework_Global_Variables
};

/**
 * WGL Elementor Custom Icon Control
 *
 *
 * @package wgl-extensions\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGL_Icons_Library
{
    public function __construct()
    {

        add_filter('elementor/icons_manager/additional_tabs', [$this, 'extended_icons_library']);
    }

    public function extended_icons_library()
    {
        return [
            'wgl_icons' => [
                'name' => 'wgl_icons',
                'label' => esc_html__('WGL Icons Library', 'wgl-extensions'),
                //'url' => get_template_directory_uri() . '/fonts/flaticon/flaticon.css',
                //'enqueue' => [get_template_directory_uri() . '/fonts/flaticon/flaticon.css'],
                'prefix' => 'flaticon-',
                'displayPrefix' => 'flaticon',
                'labelIcon' => 'flaticon',
                //'ver' => wp_get_theme()->get('Version'),
                'icons' => WGL_Elementor_Helper::get_instance()->get_wgl_icons(),
                'native' => true,
            ]
        ];
    }
}
