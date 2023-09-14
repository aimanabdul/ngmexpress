<?php

if (!class_exists('WGL_Extensions_Core')) {
    return;
}

if (!function_exists('wgl_get_redux_icons')) {
    function wgl_get_redux_icons()
    {
        return WGLAdminIcon()->get_icons_name(true);
    }

    add_filter('redux/font-icons', 'wgl_get_redux_icons',99);
}

//* This is theme option name where all the Redux data is stored.
$theme_slug = 'transmax_set';

/**
 * Set all the possible arguments for Redux
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */
$theme = wp_get_theme();

Redux::set_args($theme_slug, [
    'opt_name' => $theme_slug, //* This is where your data is stored in the database and also becomes your global variable name.
    'display_name' => $theme->get('Name'), //* Name that appears at the top of your panel
    'display_version' => $theme->get('Version'), //* Version that appears at the top of your panel
    'menu_type' => 'menu', //* Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
    'allow_sub_menu' => true, //* Show the sections below the admin menu item or not
    'menu_title' => esc_html__('Theme Options', 'transmax'),
    'page_title' => esc_html__('Theme Options', 'transmax'),
    'google_api_key' => '', //* You will need to generate a Google API key to use this feature. Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
    'google_update_weekly' => false, //* Set it you want google fonts to update weekly. A google_api_key value is required.
    'async_typography' => true, //* Must be defined to add google fonts to the typography module
    'admin_bar' => true, //* Show the panel pages on the admin bar
    'admin_bar_icon' => 'dashicons-admin-generic', //* Choose an icon for the admin bar menu
    'admin_bar_priority' => 50, //* Choose an priority for the admin bar menu
    'global_variable' => '', //* Set a different name for your global variable other than the opt_name
    'dev_mode' => false,
    'update_notice' => true, //* If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
    'customizer' => true,
    'page_priority' => 3, //* Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
    'page_parent' => 'wgl-dashboard-panel', //* For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
    'page_permissions' => 'manage_options', //* Permissions needed to access the options panel.
    'menu_icon' => 'dashicons-admin-generic', //* Specify a custom URL to an icon
    'last_tab' => '', //* Force your panel to always open to a specific tab (by id)
    'page_icon' => 'icon-themes', //* Icon displayed in the admin panel next to your menu_title
    'page_slug' => 'wgl-theme-options-panel', //* Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
    'save_defaults' => true, //* On load save the defaults to DB before user clicks save or not
    'default_show' => false, //* If true, shows the default value next to each field that is not the default value.
    'default_mark' => '', //* What to print by the field's title if the value shown is default. Suggested: *
    'show_import_export' => true, //* Shows the Import/Export panel when not used as a field.
    'transient_time' => 60 * MINUTE_IN_SECONDS, //* Show the time the page took to load, etc
    'output' => true, //* Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
    'output_tag' => true, //* FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
    'database' => '', //* possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
    'use_cdn' => true,
]);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'general',
        'title' => esc_html__('General', 'transmax'),
        'icon' => 'el el-screen',
        'fields' => [
            [
                'id' => 'use_minified',
                'title' => esc_html__('Use minified css/js files', 'transmax'),
                'type' => 'switch',
                'desc' => esc_html__('Speed up your site load.', 'transmax'),
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
            ],
            [
                'id' => 'preloader-start',
                'title' => esc_html__('Preloader', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'preloader',
                'title' => esc_html__('Preloader', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'preloader_background',
                'title' => esc_html__('Preloader Background', 'transmax'),
                'type' => 'color',
                'required' => ['preloader', '=', '1'],
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'preloader_color',
                'title' => esc_html__('Preloader Color', 'transmax'),
                'type' => 'color',
                'required' => ['preloader', '=', '1'],
                'transparent' => false,
                'default' => '#ff7029',
            ],
            [
                'id' => 'preloader-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'search_settings',
                'type' => 'section',
                'title' => esc_html__('Search', 'transmax'),
                'indent' => true,
            ],
            [
                'id' => 'search_style',
                'title' => esc_html__('Choose search style', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'standard' => esc_html__('Standard', 'transmax'),
                    'alt' => esc_html__('Full Page Width', 'transmax'),
                ],
                'default' => 'standard',
            ],
            [
                'id' => 'search_post_type',
                'title' => esc_html__('Search Post Types', 'transmax'),
                'type' => 'multi_text',
                'validate' => 'no_html',
                'add_text' => esc_html__('Add Post Type', 'transmax'),
                'default' => [],
            ],
            [
                'id' => 'search_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'scroll_up_settings',
                'title' => esc_html__('Back to Top', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'scroll_up',
                'title' => esc_html__('Button', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Disable', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'scroll_up_appearance',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'switch',
                'required' => ['scroll_up', '=', true],
                'on' => esc_html__('Text', 'transmax'),
                'off' => esc_html__('Icon', 'transmax'),
            ],
            [
                'id' => 'scroll_up_text',
                'title' => esc_html__('Button Text', 'transmax'),
                'type' => 'text',
                'required' => ['scroll_up_appearance', '=', true],
                'default' => esc_html__('BACK TO TOP', 'transmax'),
            ],
            [
                'id' => 'scroll_up_arrow_color',
                'title' => esc_html__('Text/Icon Color', 'transmax'),
                'type' => 'color',
                'required' => ['scroll_up', '=', true],
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'scroll_up_bg_color',
                'title' => esc_html__('Background Color', 'transmax'),
                'type' => 'color',
                'required' => ['scroll_up', '=', true],
                'transparent' => false,
	            'default' => '#ff7d44',
            ],
            [
                'id' => 'scroll_up_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
        ],
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'editors-option',
        'title' => esc_html__('Custom JS', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'custom_js',
                'title' => esc_html__('Custom JS', 'transmax'),
                'type' => 'ace_editor',
                'subtitle' => esc_html__('Paste your JS code here.', 'transmax'),
                'mode' => 'javascript',
                'theme' => 'chrome',
                'default' => ''
            ],
            [
                'id' => 'header_custom_js',
                'title' => esc_html__('Custom JS', 'transmax'),
                'type' => 'ace_editor',
                'subtitle' => esc_html__('Code to be added inside HEAD tag', 'transmax'),
                'mode' => 'html',
                'theme' => 'chrome',
                'default' => ''
            ],
        ],
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'header_section',
        'title' => esc_html__('Header', 'transmax'),
        'icon' => 'fas fa-window-maximize',
    ]
);

$header_builder_items = [
    'default' => [
        'html1' => ['title' => esc_html__('HTML 1', 'transmax'), 'settings' => true],
        'html2' => ['title' => esc_html__('HTML 2', 'transmax'), 'settings' => true],
        'html3' => ['title' => esc_html__('HTML 3', 'transmax'), 'settings' => true],
        'html4' => ['title' => esc_html__('HTML 4', 'transmax'), 'settings' => true],
        'html5' => ['title' => esc_html__('HTML 5', 'transmax'), 'settings' => true],
        'html6' => ['title' => esc_html__('HTML 6', 'transmax'), 'settings' => true],
        'html7' => ['title' => esc_html__('HTML 7', 'transmax'), 'settings' => true],
        'html8' => ['title' => esc_html__('HTML 8', 'transmax'), 'settings' => true],
        'delimiter1' => ['title' => esc_html__('|', 'transmax'), 'settings' => true],
        'delimiter2' => ['title' => esc_html__('|', 'transmax'), 'settings' => true],
        'delimiter3' => ['title' => esc_html__('|', 'transmax'), 'settings' => true],
        'delimiter4' => ['title' => esc_html__('|', 'transmax'), 'settings' => true],
        'delimiter5' => ['title' => esc_html__('|', 'transmax'), 'settings' => true],
        'delimiter6' => ['title' => esc_html__('|', 'transmax'), 'settings' => true],
        'spacer3' => ['title' => esc_html__('Spacer 3', 'transmax'), 'settings' => true],
        'spacer4' => ['title' => esc_html__('Spacer 4', 'transmax'), 'settings' => true],
        'spacer5' => ['title' => esc_html__('Spacer 5', 'transmax'), 'settings' => true],
        'spacer6' => ['title' => esc_html__('Spacer 6', 'transmax'), 'settings' => true],
        'spacer7' => ['title' => esc_html__('Spacer 7', 'transmax'), 'settings' => true],
        'spacer8' => ['title' => esc_html__('Spacer 8', 'transmax'), 'settings' => true],
        'button1' => ['title' => esc_html__('Button', 'transmax'), 'settings' => true],
        'button2' => ['title' => esc_html__('Button', 'transmax'), 'settings' => true],
        'wpml' => ['title' => esc_html__('WPML/Polylang', 'transmax'), 'settings' => false],
        'cart' => ['title' => esc_html__('Cart', 'transmax'), 'settings' => true],
        'login' => ['title' => esc_html__('WC Login', 'transmax'), 'settings' => false],
        'side_panel' => ['title' => esc_html__('Side Panel', 'transmax'), 'settings' => true],
    ],
    'mobile' => [
        'html1' => esc_html__('HTML 1', 'transmax'),
        'html2' => esc_html__('HTML 2', 'transmax'),
        'html3' => esc_html__('HTML 3', 'transmax'),
        'html4' => esc_html__('HTML 4', 'transmax'),
        'html5' => esc_html__('HTML 5', 'transmax'),
        'html6' => esc_html__('HTML 6', 'transmax'),
        'spacer1' => esc_html__('Spacer 1', 'transmax'),
        'spacer2' => esc_html__('Spacer 2', 'transmax'),
        'spacer3' => esc_html__('Spacer 3', 'transmax'),
        'spacer4' => esc_html__('Spacer 4', 'transmax'),
        'spacer5' => esc_html__('Spacer 5', 'transmax'),
        'spacer6' => esc_html__('Spacer 6', 'transmax'),
        'side_panel' => esc_html__('Side Panel', 'transmax'),
        'wpml' => esc_html__('WPML/Polylang', 'transmax'),
        'cart' => esc_html__('Cart', 'transmax'),
        'login' => esc_html__('WC Login', 'transmax'),
    ],
    'mobile_drawer' => [
        'html1' => esc_html__('HTML 1', 'transmax'),
        'html2' => esc_html__('HTML 2', 'transmax'),
        'html3' => esc_html__('HTML 3', 'transmax'),
        'html4' => esc_html__('HTML 4', 'transmax'),
        'html5' => esc_html__('HTML 5', 'transmax'),
        'html6' => esc_html__('HTML 6', 'transmax'),
        'wpml' => esc_html__('WPML/Polylang', 'transmax'),
        'spacer1' => esc_html__('Spacer 1', 'transmax'),
        'spacer2' => esc_html__('Spacer 2', 'transmax'),
        'spacer3' => esc_html__('Spacer 3', 'transmax'),
        'spacer4' => esc_html__('Spacer 4', 'transmax'),
        'spacer5' => esc_html__('Spacer 5', 'transmax'),
        'spacer6' => esc_html__('Spacer 6', 'transmax'),
    ],
];

Redux::set_section(
    $theme_slug,
    [
        'title' => esc_html__('Header Builder', 'transmax'),
        'id' => 'header-customize',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'header_switch',
                'title' => esc_html__('Header', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Disable', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'header_building_tool',
                'title' => esc_html__('Layout Building Tool', 'transmax'),
                'type' => 'select',
                'required' => ['header_switch', '=', '1'],
                'options' => [
                    'default' => esc_html__('Default Builder', 'transmax'),
                    'elementor' => esc_html__('Elementor (recommended)', 'transmax')
                ],
                'default' => 'default',
            ],
            [
                'id' => 'header_page_select',
                'type' => 'select',
                'title' => esc_html__('Header Template', 'transmax'),
                'required' => ['header_building_tool', '=', 'elementor'],
                'desc' => wp_kses(
                    sprintf(
                        '%s <a href="%s" target="_blank">%s</a> %s<br> %s',
                        __('Selected Template will be used for all pages by default. You can edit/create Header Template in the', 'transmax'),
                        admin_url('edit.php?post_type=header'),
                        __('Header Templates', 'transmax'),
                        __('dashboard tab.', 'transmax'),
                        transmax_quick_tip(
                            sprintf(
                                __('Note: fine tuning is available through the Elementor\'s <code>Post Settings</code> tab, which is located <a href="%s" target="_blank">here</a>', 'transmax'),
                                get_template_directory_uri() . '/core/admin/img/dashboard/quick_tip__header_extra_options.png'
                            )
                        )
                    ),
                    ['a' => ['href' => true, 'target' => true], 'br' => [], 'span' => ['class' => true], 'i' => ['class' => true], 'code' => []]
                ),
                'data' => 'posts',
                'args' => [
                    'post_type' => 'header',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => 'bottom_header_layout',
                'type' => 'custom_header_builder',
                'title' => esc_html__('Header Builder', 'transmax'),
                'required' => ['header_building_tool', '=', 'default'],
                'compiler' => 'true',
                'full_width' => true,
                'default' => [
                    'items' => $header_builder_items['default'],
                    'Top Left area' => [],
                    'Top Center area' => [],
                    'Top Right area' => [],
                    'Middle Left area' => [
                        'spacer1' => ['title' => esc_html__('Spacer 1', 'transmax'), 'settings' => false],
                        'logo' => ['title' => esc_html__('Logo', 'transmax'), 'settings' => false],
                    ],
                    'Middle Center area' => [
                        'menu' => ['title' => esc_html__('Menu', 'transmax'), 'settings' => false],
                    ],
                    'Middle Right area' => [
                        'item_search' => ['title' => esc_html__('Search', 'transmax'), 'settings' => true],
                        'spacer2' => ['title' => esc_html__('Spacer 2', 'transmax'), 'settings' => false],
                    ],
                    'Bottom Left area' => [],
                    'Bottom Center area' => [],
                    'Bottom Right area' => [],
                ],
            ],
            [
                'id' => 'bottom_header_spacer1',
                'title' => esc_html__('Header Spacer 1 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 40],
            ],
            [
                'id' => 'bottom_header_spacer2',
                'title' => esc_html__('Header Spacer 2 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 40],
            ],
            [
                'id' => 'bottom_header_spacer3',
                'title' => esc_html__('Header Spacer 3 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer4',
                'title' => esc_html__('Header Spacer 4 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer5',
                'title' => esc_html__('Header Spacer 5 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => false,
                'width' => true,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer6',
                'title' => esc_html__('Header Spacer 6 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => false,
                'width' => true,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer7',
                'title' => esc_html__('Header Spacer 7 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_spacer8',
                'title' => esc_html__('Header Spacer 8 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'bottom_header_item_search_custom',
                'title' => esc_html__('Customize Search', 'transmax'),
                'type' => 'switch',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => false,
            ],
            [
                'id' => 'bottom_header_item_search_color_txt',
                'title' => esc_html__('Icon Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_item_search_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_item_search_hover_color_txt',
                'title' => esc_html__('Hover Icon Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_item_search_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_cart_custom',
                'title' => esc_html__('Customize cart', 'transmax'),
                'type' => 'switch',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => false,
            ],
            [
                'id' => 'bottom_header_cart_color_txt',
                'title' => esc_html__('Icon Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_cart_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_cart_hover_color_txt',
                'title' => esc_html__('Hover Icon Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_cart_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter1_height',
                'title' => esc_html__('Delimiter Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 50],
            ],
            [
                'id' => 'bottom_header_delimiter1_width',
                'title' => esc_html__('Delimiter Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter1_bg',
                'title' => esc_html__('Delimiter Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'color' => '#000000',
                    'alpha' => '0.1',
                    'rgba' => 'rgba(0, 0, 0, 0.1)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter1_margin',
                'title' => esc_html__('Delimiter Spacing', 'transmax'),
                'type' => 'spacing',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '20',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter2_height',
                'title' => esc_html__('Delimiter Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter2_width',
                'title' => esc_html__('Delimiter Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => false,
                'width' => true,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter2_bg',
                'title' => esc_html__('Delimiter Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter2_margin',
                'title' => esc_html__('Delimiter Spacing', 'transmax'),
                'type' => 'spacing',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter3_height',
                'title' => esc_html__('Delimiter Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter3_width',
                'title' => esc_html__('Delimiter Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter3_bg',
                'title' => esc_html__('Delimiter Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter3_margin',
                'title' => esc_html__('Delimiter Spacing', 'transmax'),
                'type' => 'spacing',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter4_height',
                'title' => esc_html__('Delimiter Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter4_width',
                'title' => esc_html__('Delimiter Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => false,
                'width' => true,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter4_bg',
                'title' => esc_html__('Delimiter Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter4_margin',
                'title' => esc_html__('Delimiter Spacing', 'transmax'),
                'type' => 'spacing',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter5_height',
                'title' => esc_html__('Delimiter Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter5_width',
                'title' => esc_html__('Delimiter Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => false,
                'width' => true,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter5_bg',
                'title' => esc_html__('Delimiter Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter5_margin',
                'title' => esc_html__('Delimiter Spacing', 'transmax'),
                'type' => 'spacing',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_delimiter6_height',
                'title' => esc_html__('Delimiter Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'bottom_header_delimiter6_width',
                'title' => esc_html__('Delimiter Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_building_tool', '=', 'default'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 1],
            ],
            [
                'id' => 'bottom_header_delimiter6_bg',
                'title' => esc_html__('Delimiter Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'bottom_header_delimiter6_margin',
                'title' => esc_html__('Delimiter Spacing', 'transmax'),
                'type' => 'spacing',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'margin',
                'all' => false,
                'bottom' => false,
                'top' => false,
                'left' => true,
                'right' => true,
                'default' => [
                    'margin-left' => '30',
                    'margin-right' => '30',
                ],
            ],
            [
                'id' => 'bottom_header_button1_title',
                'title' => esc_html__('Button Text', 'transmax'),
                'type' => 'text',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => esc_html__('Contact Us', 'transmax'),
            ],
            [
                'id' => 'bottom_header_button1_link',
                'title' => esc_html__('Link', 'transmax'),
                'type' => 'text',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => '#',
            ],
            [
                'id' => 'bottom_header_button1_target',
                'title' => esc_html__('Open link in a new tab', 'transmax'),
                'type' => 'switch',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => true,
            ],
            [
                'id' => 'bottom_header_button1_size',
                'title' => esc_html__('Button Size', 'transmax'),
                'type' => 'select',
                'required' => ['header_building_tool', '=', 'default'],
                'options' => [
                    'sm' => esc_html__('Small', 'transmax'),
                    'md' => esc_html__('Medium', 'transmax'),
                    'lg' => esc_html__('Large', 'transmax'),
                    'xl' => esc_html__('Extra Large', 'transmax'),
                ],
                'default' => 'md',
            ],
            [
                'id' => 'bottom_header_button1_radius',
                'title' => esc_html__('Button Border Radius', 'transmax'),
                'type' => 'text',
                'required' => ['header_building_tool', '=', 'default'],
                'desc' => esc_html__('Value in pixels.', 'transmax'),
            ],
            [
                'id' => 'bottom_header_button1_custom',
                'title' => esc_html__('Customize Button', 'transmax'),
                'type' => 'switch',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => false,
            ],
            [
                'id' => 'bottom_header_button1_color_txt',
                'title' => esc_html__('Text Color Idle', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_color_txt',
                'title' => esc_html__('Text Color Hover', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#232323',
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_bg',
                'title' => esc_html__('Background Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#232323',
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_bg',
                'title' => esc_html__('Hover Background Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_border',
                'title' => esc_html__('Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#232323',
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button1_hover_border',
                'title' => esc_html__('Hover Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button1_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#232323',
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)'
                ],
            ],
            [
                'id' => 'bottom_header_button2_title',
                'title' => esc_html__('Button Text', 'transmax'),
                'type' => 'text',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => esc_html__('Contact Us', 'transmax'),
            ],
            [
                'id' => 'bottom_header_button2_link',
                'title' => esc_html__('Link', 'transmax'),
                'type' => 'text',
                'required' => ['header_building_tool', '=', 'default'],
            ],
            [
                'id' => 'bottom_header_button2_target',
                'title' => esc_html__('Open link in a new tab', 'transmax'),
                'type' => 'switch',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => true,
            ],
            [
                'id' => 'bottom_header_button2_size',
                'title' => esc_html__('Button Size', 'transmax'),
                'type' => 'select',
                'required' => ['header_building_tool', '=', 'default'],
                'options' => [
                    'sm' => esc_html__('Small', 'transmax'),
                    'md' => esc_html__('Medium', 'transmax'),
                    'lg' => esc_html__('Large', 'transmax'),
                    'xl' => esc_html__('Extra Large', 'transmax'),
                ],
                'default' => 'md',
            ],
            [
                'id' => 'bottom_header_button2_radius',
                'title' => esc_html__('Button Border Radius', 'transmax'),
                'type' => 'text',
                'required' => ['header_building_tool', '=', 'default'],
                'desc' => esc_html__('Value in pixels.', 'transmax'),
            ],
            [
                'id' => 'bottom_header_button2_custom',
                'title' => esc_html__('Customize Button', 'transmax'),
                'type' => 'switch',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => false,
            ],
            [
                'id' => 'bottom_header_button2_color_txt',
                'title' => esc_html__('Text Color Idle', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_color_txt',
                'title' => esc_html__('Text Color Hover', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_button2_bg',
                'title' => esc_html__('Background Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_bg',
                'title' => esc_html__('Hover Background Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'bottom_header_button2_border',
                'title' => esc_html__('Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_button2_hover_border',
                'title' => esc_html__('Hover Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['bottom_header_button2_custom', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(35,35,35,1)',
                    'color' => '#232323',
                ],
            ],
            [
                'id' => 'bottom_header_bar_html1_editor',
                'title' => esc_html__('HTML Element 1 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html2_editor',
                'title' => esc_html__('HTML Element 2 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html3_editor',
                'title' => esc_html__('HTML Element 3 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html4_editor',
                'title' => esc_html__('HTML Element 4 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html5_editor',
                'title' => esc_html__('HTML Element 5 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html6_editor',
                'title' => esc_html__('HTML Element 6 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html7_editor',
                'title' => esc_html__('HTML Element 7 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_bar_html8_editor',
                'title' => esc_html__('HTML Element 8 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'html',
            ],
            [
                'id' => 'bottom_header_side_panel_color',
                'title' => esc_html__('Icon Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_building_tool', '=', 'default'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'bottom_header_side_panel_background',
                'title' => esc_html__('Background Icon', 'transmax'),
                'type' => 'color',
                'required' => ['header_building_tool', '=', 'default'],
                'default' => '#232323',
            ],
            [
                'id' => 'header_top-start',
                'title' => esc_html__('Header Top Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_top_full_width',
                'title' => esc_html__('Full Width Header', 'transmax'),
                'type' => 'switch',
                'subtitle' => esc_html__('Set header content in full width', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'header_top_max_width_custom',
                'title' => esc_html__('Limit the Max Width of Container', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_top_max_width',
                'title' => esc_html__('Max Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_top_max_width_custom', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 1290],
            ],
            [
                'id' => 'header_top_height',
                'title' => esc_html__('Header Top Height', 'transmax'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 49],
            ],
            [
                'id' => 'header_top_background_image',
                'title' => esc_html__('Header Top Background Image', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'header_top_background',
                'title' => esc_html__('Header Top Background', 'transmax'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'header_top_color',
                'title' => esc_html__('Header Top Text Color', 'transmax'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#a2a2a2',
            ],
            [
                'id' => 'header_top_bottom_border',
                'type' => 'switch',
                'title' => esc_html__('Set Header Top Bottom Border', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'header_top_border_height',
                'title' => esc_html__('Header Top Border Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_top_bottom_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_top_bottom_border_color',
                'title' => esc_html__('Header Top Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_top_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '.2',
                    'rgba' => 'rgba(162,162,162,0.2)',
                    'color' => '#a2a2a2',
                ],
            ],
            [
                'id' => 'header_top-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_middle-start',
                'title' => esc_html__('Header Middle Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_middle_full_width',
                'type' => 'switch',
                'title' => esc_html__('Full Width Middle Header', 'transmax'),
                'subtitle' => esc_html__('Set header content in full width', 'transmax'),
	            'default' => true,
            ],
            [
                'id' => 'header_middle_max_width_custom',
                'title' => esc_html__('Limit the Max Width of Container', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_middle_max_width',
                'title' => esc_html__('Max Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_middle_max_width_custom', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 1290],
            ],
            [
                'id' => 'header_middle_height',
                'title' => esc_html__('Header Middle Height', 'transmax'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 98],
            ],
            [
                'id' => 'header_middle_background_image',
                'title' => esc_html__('Header Middle Background Image', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'header_middle_background',
                'title' => esc_html__('Header Middle Background', 'transmax'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(34,35,40,1)',
                    'color' => '#222328',
                ],
            ],
            [
                'id' => 'header_middle_color',
                'title' => esc_html__('Header Middle Text Color', 'transmax'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'header_middle_bottom_border',
                'title' => esc_html__('Set Header Middle Bottom Border', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_middle_border_height',
                'title' => esc_html__('Header Middle Border Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_middle_bottom_border', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_middle_bottom_border_color',
                'title' => esc_html__('Header Middle Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_middle_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(245,245,245,1)',
                    'color' => '#f5f5f5',
                ],
            ],
            [
                'id' => 'header_middle-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_bottom-start',
                'title' => esc_html__('Header Bottom Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_bottom_full_width',
                'title' => esc_html__('Full Width Bottom Header', 'transmax'),
                'type' => 'switch',
                'subtitle' => esc_html__('Set header content in full width', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'header_bottom_max_width_custom',
                'title' => esc_html__('Limit the Max Width of Container', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_bottom_max_width',
                'title' => esc_html__('Max Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_bottom_max_width_custom', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 1290],
            ],
            [
                'id' => 'header_bottom_height',
                'title' => esc_html__('Header Bottom Height', 'transmax'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => 100],
            ],
            [
                'id' => 'header_bottom_background_image',
                'title' => esc_html__('Header Bottom Background Image', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'header_bottom_background',
                'title' => esc_html__('Header Bottom Background', 'transmax'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '.9',
                    'rgba' => 'rgba(255,255,255,0.9)'
                ],
            ],
            [
                'id' => 'header_bottom_color',
                'title' => esc_html__('Header Bottom Text Color', 'transmax'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#fefefe',
            ],
            [
                'id' => 'header_bottom_bottom_border',
                'title' => esc_html__('Set Header Bottom Border', 'transmax'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'header_bottom_border_height',
                'title' => esc_html__('Header Bottom Border Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_bottom_bottom_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_bottom_bottom_border_color',
                'title' => esc_html__('Header Bottom Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_bottom_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'color' => '#ffffff',
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,0.2)'
                ],
            ],
            [
                'id' => 'header_bottom-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-left-start',
                'title' => esc_html__('Top Left Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_left_horz',
                'type' => 'button_set',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_top_left_vert',
                'type' => 'button_set',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_left_display',
                'type' => 'button_set',
                'title' => esc_html__('Display', 'transmax'),
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-left-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-center-start',
                'title' => esc_html__('Top Center Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_center_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_top_center_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_center_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-center-start',
                'title' => esc_html__('Top Center Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_center_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_top_center_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_center_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-top-right-start',
                'title' => esc_html__('Top Right Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_top_right_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'right'
            ],
            [
                'id' => 'header_column_top_right_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_top_right_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-top-right-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-left-start',
                'title' => esc_html__('Middle Left Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_left_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_middle_left_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_middle_left_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-middle-left-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-center-start',
                'title' => esc_html__('Middle Center Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_center_horz',
                'type' => 'button_set',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'header_column_middle_center_vert',
                'type' => 'button_set',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_middle_center_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-middle-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-middle-right-start',
                'title' => esc_html__('Middle Right Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_middle_right_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'right',
            ],
            [
                'id' => 'header_column_middle_right_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle',
            ],
            [
                'id' => 'header_column_middle_right_display',
                'type' => 'button_set',
                'title' => esc_html__('Display', 'transmax'),
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal',
            ],
            [
                'id' => 'header_column-middle-right-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-left-start',
                'title' => esc_html__('Bottom Left Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_bottom_left_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_bottom_left_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_left_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-left-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-center-start',
                'type' => 'section',
                'title' => esc_html__('Bottom Center Column Options', 'transmax'),
                'indent' => true,
                'required' => ['header_building_tool', '=', 'default'],
            ],
            [
                'id' => 'header_column_bottom_center_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left'
            ],
            [
                'id' => 'header_column_bottom_center_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_center_display',
                'type' => 'button_set',
                'title' => esc_html__('Display', 'transmax'),
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-center-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_column-bottom-right-start',
                'title' => esc_html__('Bottom Right Column Options', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_column_bottom_right_horz',
                'title' => esc_html__('Horizontal Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'right'
            ],
            [
                'id' => 'header_column_bottom_right_vert',
                'title' => esc_html__('Vertical Align', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'top' => esc_html__('Top', 'transmax'),
                    'middle' => esc_html__('Middle', 'transmax'),
                    'bottom' => esc_html__('Bottom', 'transmax'),
                ],
                'default' => 'middle'
            ],
            [
                'id' => 'header_column_bottom_right_display',
                'title' => esc_html__('Display', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'normal' => esc_html__('Normal', 'transmax'),
                    'grow' => esc_html__('Grow', 'transmax'),
                ],
                'default' => 'normal'
            ],
            [
                'id' => 'header_column-bottom-right-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_row_settings-start',
                'title' => esc_html__('Header Settings', 'transmax'),
                'type' => 'section',
                'required' => ['header_building_tool', '=', 'default'],
                'indent' => true,
            ],
            [
                'id' => 'header_shadow',
                'title' => esc_html__('Header Bottom Shadow', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_on_bg',
                'title' => esc_html__('Over content', 'transmax'),
                'type' => 'switch',
                'subtitle' => esc_html__('Display header template over the content.', 'transmax'),
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'lavalamp_active',
                'type' => 'switch',
                'title' => esc_html__('Lavalamp Marker', 'transmax'),
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'sub_menu_background',
                'type' => 'color_rgba',
                'title' => esc_html__('Sub Menu Background', 'transmax'),
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(255,255,255,1)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'sub_menu_color',
                'title' => esc_html__('Sub Menu Text Color', 'transmax'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#003b49',
            ],
            [
                'id' => 'header_sub_menu_bottom_border',
                'title' => esc_html__('Sub Menu Bottom Border', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'header_sub_menu_border_height',
                'title' => esc_html__('Sub Menu Border Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['header_sub_menu_bottom_border', '=', '1'],
                'width' => false,
                'height' => true,
                'default' => ['height' => '1'],
            ],
            [
                'id' => 'header_sub_menu_bottom_border_color',
                'title' => esc_html__('Sub Menu Border Color', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['header_sub_menu_bottom_border', '=', '1'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(0, 0, 0, 0.08)',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'header_mobile_queris',
                'title' => esc_html__('Mobile Header Switch Breakpoint', 'transmax'),
                'type' => 'slider',
                'display_value' => 'text',
                'min' => 400,
                'max' => 1920,
                'default' => 1200,
            ],
            [
                'id' => 'header_row_settings-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'title' => esc_html__('Header Sticky', 'transmax'),
        'id' => 'header_builder_sticky',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'header_sticky',
                'title' => esc_html__('Header Sticky', 'transmax'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'header_sticky-start',
                'title' => esc_html__('Sticky Settings', 'transmax'),
                'type' => 'section',
                'required' => ['header_sticky', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'header_sticky_page_select',
                'title' => esc_html__('Header Sticky Template', 'transmax'),
                'type' => 'select',
                'required' => ['header_sticky', '=', '1'],
                'desc' => sprintf(
                    '%s <a href="%s" target="_blank">%s</a> %s',
                    esc_html__('Selected Template will be used for all pages by default. You can edit/create Header Template in the', 'transmax'),
                    admin_url('edit.php?post_type=header'),
                    esc_html__('Header Templates', 'transmax'),
                    esc_html__('dashboard tab.', 'transmax')
                ),
                'data' => 'posts',
                'args' => [
                    'post_type' => 'header',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => 'header_sticky_style',
                'type' => 'select',
                'title' => esc_html__('Appearance', 'transmax'),
                'options' => [
                    'standard' => esc_html__('Always Visible', 'transmax'),
                    'scroll_up' => esc_html__('Visible while scrolling upwards', 'transmax'),
                ],
                'default' => 'scroll_up'
            ],
            [
                'id' => 'header_sticky-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'title' => esc_html__('Header Mobile', 'transmax'),
        'id' => 'header_builder_mobile',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'mobile_header',
                'title' => esc_html__('Mobile Header', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Custom', 'transmax'),
                'off' => esc_html__('Default', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'header_mobile_appearance-start',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'section',
                'required' => ['mobile_header', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'header_mobile_height',
                'title' => esc_html__('Header Height', 'transmax'),
                'type' => 'dimensions',
                'width' => false,
                'height' => true,
                'default' => ['height' => '50'],
            ],
            [
                'id' => 'header_mobile_full_width',
                'title' => esc_html__('Full Width Header', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'mobile_sticky',
                'title' => esc_html__('Mobile Sticky Header', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'mobile_over_content',
                'title' => esc_html__('Header Over Content', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'mobile_background',
                'title' => esc_html__('Header Background', 'transmax'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(34,35,40, 1)',
                    'color' => '#222328',
                ],
            ],
            [
                'id' => 'mobile_color',
                'title' => esc_html__('Header Text Color', 'transmax'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'header_mobile_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'header_mobile_menu-start',
                'title' => esc_html__('Menu', 'transmax'),
                'type' => 'section',
                'required' => ['mobile_header', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'mobile_position',
                'title' => esc_html__('Menu Occurrence', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'custom_mobile_menu',
                'title' => esc_html__('Custom Mobile Menu', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'mobile_menu',
                'type' => 'select',
                'title' => esc_html__('Mobile Menu', 'transmax'),
                'required' => ['custom_mobile_menu', '=', '1'],
                'select2' => ['allowClear' => false],
                'options' => $menus = wgl_get_custom_menu(),
                'default' => reset($menus),
            ],
            [
                'id' => 'mobile_sub_menu_color',
                'title' => esc_html__('Menu Text Color', 'transmax'),
                'type' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'mobile_sub_menu_background',
                'title' => esc_html__('Menu Background', 'transmax'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(17,48,57,1)',
                    'color' => '#113039',
                ],
            ],
            [
                'id' => 'mobile_sub_menu_overlay',
                'title' => esc_html__('Menu Overlay', 'transmax'),
                'type' => 'color_rgba',
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(34,35,40,0.8)',
                    'color' => '#222328',
                ],
            ],
            [
                'id' => 'header_mobile_menu-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'mobile_header_layout',
                'title' => esc_html__('Mobile Header Order', 'transmax'),
                'type' => 'sorter',
                'required' => ['mobile_header', '=', '1'],
                'desc' => esc_html__('Organize the layout of the mobile header', 'transmax'),
                'compiler' => 'true',
                'full_width' => true,
                'options' => [
                    'items' => $header_builder_items['mobile'],
                    'Left align side' => [
                        'menu' => esc_html__('Hamburger Menu', 'transmax'),
                    ],
                    'Center align side' => [
                        'logo' => esc_html__('Logo', 'transmax'),
                    ],
                    'Right align side' => [
                        'item_search' => esc_html__('Search', 'transmax'),
                    ],
                ],
            ],
            [
                'id' => 'mobile_content_header_layout',
                'title' => esc_html__('Mobile Drawer Content', 'transmax'),
                'type' => 'sorter',
                'required' => ['mobile_header', '=', '1'],
                'desc' => esc_html__('Organize the layout of the mobile header', 'transmax'),
                'compiler' => 'true',
                'full_width' => true,
                'options' => [
                    'items' => $header_builder_items['mobile_drawer'],
                    'Left align side' => [
                        'logo' => esc_html__('Logo', 'transmax'),
                        'menu' => esc_html__('Menu', 'transmax'),
                        'item_search' => esc_html__('Search', 'transmax'),
                    ],
                ],
                'default' => [
                    'items' => $header_builder_items['mobile_drawer'],
                    'Left align side' => [
                        'logo' => esc_html__('Logo', 'transmax'),
                        'menu' => esc_html__('Menu', 'transmax'),
                        'item_search' => esc_html__('Search', 'transmax'),
                    ],
                ],
            ],
            [
                'id' => 'mobile_header_bar_html1_editor',
                'title' => esc_html__('HTML Element 1 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html2_editor',
                'title' => esc_html__('HTML Element 2 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html3_editor',
                'title' => esc_html__('HTML Element 3 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html4_editor',
                'title' => esc_html__('HTML Element 4 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html5_editor',
                'title' => esc_html__('HTML Element 5 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_bar_html6_editor',
                'title' => esc_html__('HTML Element 6 Editor', 'transmax'),
                'type' => 'ace_editor',
                'required' => ['mobile_header', '=', '1'],
                'mode' => 'html',
                'default' => '',
            ],
            [
                'id' => 'mobile_header_spacer1',
                'title' => esc_html__('Spacer 1 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer2',
                'title' => esc_html__('Spacer 2 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer3',
                'title' => esc_html__('Spacer 3 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer4',
                'title' => esc_html__('Spacer 4 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer5',
                'title' => esc_html__('Spacer 5 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
            [
                'id' => 'mobile_header_spacer6',
                'title' => esc_html__('Spacer 6 Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_header', '=', '1'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 25],
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'logo',
        'title' => esc_html__('Logo', 'transmax'),
        'subsection' => true,
        'required' => ['header_building_tool', '=', 'elementor'],
        'fields' => [
            [
                'id' => 'header_logo',
                'title' => esc_html__('Default Header Logo', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'logo_height_custom',
                'title' => esc_html__('Limit Default Logo Height', 'transmax'),
                'type' => 'switch',
                'required' => ['header_logo', '!=', ''],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'logo_height',
                'title' => esc_html__('Default Logo Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['logo_height_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 90],
            ],
            [
                'id' => 'sticky_header_logo',
                'title' => esc_html__('Sticky Header Logo', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'sticky_logo_height_custom',
                'title' => esc_html__('Limit Sticky Logo Height', 'transmax'),
                'type' => 'switch',
                'required' => ['sticky_header_logo', '!=', ''],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'sticky_logo_height',
                'title' => esc_html__('Sticky Header Logo Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['sticky_logo_height_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 90],
            ],
            [
                'id' => 'logo_mobile',
                'title' => esc_html__('Mobile Header Logo', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'mobile_logo_height_custom',
                'title' => esc_html__('Limit Mobile Logo Height', 'transmax'),
                'type' => 'switch',
                'required' => ['logo_mobile', '!=', ''],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'mobile_logo_height',
                'title' => esc_html__('Mobile Logo Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_logo_height_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 60],
            ],
            [
                'id' => 'logo_mobile_menu',
                'title' => esc_html__('Mobile Menu Logo', 'transmax'),
                'type' => 'media',
            ],
            [
                'id' => 'mobile_logo_menu_height_custom',
                'title' => esc_html__('Limit Mobile Menu Logo Height', 'transmax'),
                'type' => 'switch',
                'required' => ['logo_mobile_menu', '!=', ''],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'mobile_logo_menu_height',
                'title' => esc_html__('Mobile Menu Logo Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['mobile_logo_menu_height_custom', '=', '1'],
                'height' => true,
                'width' => false,
                'default' => ['height' => 60],
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'page_title',
        'title' => esc_html__('Page Title', 'transmax'),
        'icon' => 'el el-home-alt',
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'page_title_settings',
        'title' => esc_html__('General', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'page_title_switch',
                'title' => esc_html__('Use Page Titles?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'page_title-start',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'page_title_bg_switch',
                'title' => esc_html__('Use Background Image/Color?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'page_title_bg_image',
                'title' => esc_html__('Background Image/Color', 'transmax'),
                'type' => 'background',
                'required' => ['page_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-image' => '',
                    'background-repeat' => 'no-repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center bottom',
                    'background-color' => '#003b49',
                ],
            ],
            [
                'id' => 'page_title_height',
                'title' => esc_html__('Min Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['page_title_bg_switch', '=', true],
                'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'transmax'),
                'width' => false,
                'height' => true,
                'default' => ['height' => 420],
            ],
            [
                'id' => 'page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '85',
                    'padding-bottom' => '40',
                ],
            ],
            [
                'id' => 'page_title_margin',
                'title' => esc_html__('Margin Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => true,
                'top' => false,
                'left' => false,
                'right' => false,
                'default' => ['margin-bottom' => '40'],
            ],
            [
                'id' => 'page_title_align',
                'title' => esc_html__('Title Alignment', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'page_title_breadcrumbs_switch',
                'title' => esc_html__('Breadcrumbs', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'page_title_breadcrumbs_block_switch',
                'title' => esc_html__('Breadcrumbs Full Width', 'transmax'),
                'type' => 'switch',
                'required' => ['page_title_breadcrumbs_switch', '=', true],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'page_title_breadcrumbs_align',
                'title' => esc_html__('Breadcrumbs Alignment', 'transmax'),
                'type' => 'button_set',
                'required' => ['page_title_breadcrumbs_block_switch', '=', true],
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'right',
            ],
            [
                'id' => 'page_title_parallax',
                'title' => esc_html__('Parallax Effect', 'transmax'),
                'type' => 'switch',
                'default' => false,
            ],
            [
                'id' => 'page_title_parallax_speed',
                'title' => esc_html__('Parallax Speed', 'transmax'),
                'type' => 'spinner',
                'required' => ['page_title_parallax', '=', '1'],
                'min' => '-5',
                'max' => '5',
                'step' => '0.1',
                'default' => '0.3',
            ],
            [
                'id' => 'page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'page_title_typography',
        'title' => esc_html__('Typography', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'page_title_font',
                'title' => esc_html__('Page Title Font', 'transmax'),
                'type' => 'custom_typography',
                'font-size' => true,
                'google' => false,
                'font-weight' => false,
                'font-family' => false,
                'font-style' => false,
                'color' => true,
                'line-height' => true,
                'font-backup' => false,
                'text-align' => false,
                'all_styles' => false,
                'default' => [
                    'font-size' => '48px',
                    'line-height' => '60px',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'page_title_breadcrumbs_font',
                'title' => esc_html__('Breadcrumbs Font', 'transmax'),
                'type' => 'custom_typography',
                'font-size' => true,
                'google' => false,
                'font-weight' => false,
                'font-family' => false,
                'font-style' => false,
                'color' => true,
                'line-height' => true,
                'font-backup' => false,
                'text-align' => false,
                'all_styles' => false,
                'default' => [
                    'font-size' => '14px',
                    'color' => '#ffffff',
                    'line-height' => '24px',
                ],
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'title' => esc_html__('Responsive', 'transmax'),
        'id' => 'page_title_responsive',
        'subsection' => true,
        'fields' => [
            [
                'id' => 'page_title_resp_switch',
                'title' => esc_html__('Responsive Settings', 'transmax'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'page_title_resp_resolution',
                'title' => esc_html__('Screen breakpoint', 'transmax'),
                'type' => 'slider',
                'required' => ['page_title_resp_switch', '=', '1'],
                'desc' => esc_html__('Use responsive settings on screens smaller then choosed breakpoint.', 'transmax'),
                'display_value' => 'text',
                'min' => 1,
                'max' => 1700,
                'step' => 1,
                'default' => 1200,
            ],
            [
                'id' => 'page_title_resp_padding',
                'title' => esc_html__('Page Title Paddings', 'transmax'),
                'type' => 'spacing',
                'required' => ['page_title_resp_switch', '=', '1'],
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '90',
                    'padding-bottom' => '90',
                ],
            ],
            [
                'id' => 'page_title_resp_font',
                'title' => esc_html__('Page Title Font', 'transmax'),
                'type' => 'custom_typography',
                'required' => ['page_title_resp_switch', '=', '1'],
                'google' => false,
                'all_styles' => false,
                'font-family' => false,
                'font-style' => false,
                'font-size' => true,
                'font-weight' => false,
                'font-backup' => false,
                'line-height' => true,
                'text-align' => false,
                'color' => true,
                'default' => [
                    'font-size' => '30px',
                    'line-height' => '42px',
                    'color' => '#ffffff',
                ],
            ],
            [
                'id' => 'page_title_resp_breadcrumbs_switch',
                'title' => esc_html__('Breadcrumbs', 'transmax'),
                'type' => 'switch',
                'required' => ['page_title_resp_switch', '=', '1'],
                'default' => true,
            ],
            [
                'id' => 'page_title_resp_breadcrumbs_font',
                'title' => esc_html__('Breadcrumbs Font', 'transmax'),
                'type' => 'custom_typography',
                'required' => ['page_title_resp_breadcrumbs_switch', '=', '1'],
                'google' => false,
                'all_styles' => false,
                'font-family' => false,
                'font-style' => false,
                'font-size' => true,
                'font-weight' => false,
                'font-backup' => false,
                'line-height' => true,
                'text-align' => false,
                'color' => true,
                'default' => [
                    'font-size' => '14px',
                    'color' => '#ffffff',
                    'line-height' => '24px',
                ],
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'footer',
        'title' => esc_html__('Footer', 'transmax'),
        'icon' => 'fas fa-window-maximize el-rotate-180',
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'footer-general',
        'title' => esc_html__('General', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'footer_switch',
                'title' => esc_html__('Footer', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Disable', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'footer-start',
                'title' => esc_html__('Layout', 'transmax'),
                'type' => 'section',
                'required' => ['footer_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'footer_building_tool',
                'title' => esc_html__('Layout Building Tool', 'transmax'),
                'type' => 'select',
                'options' => [
                    'widgets' => esc_html__('Wordpress Widgets', 'transmax'),
                    'elementor' => esc_html__('Elementor', 'transmax'),
                ],
                'default' => 'widgets',
            ],
            [
                'id' => 'footer_page_select',
                'title' => esc_html__('Footer Template', 'transmax'),
                'type' => 'select',
                'required' => ['footer_building_tool', '=', 'elementor'],
                'data' => 'posts',
                'args' => [
                    'post_type' => 'footer',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => 'widget_columns',
                'title' => esc_html__('Columns', 'transmax'),
                'type' => 'button_set',
                'required' => ['footer_building_tool', '=', 'widgets'],
                'options' => [
                    '1' => esc_html('1'),
                    '2' => esc_html('2'),
                    '3' => esc_html('3'),
                    '4' => esc_html('4'),
                ],
                'default' => '4',
            ],
            [
                'id' => 'widget_columns_2',
                'title' => esc_html__('Columns Layout', 'transmax'),
                'type' => 'image_select',
                'required' => ['widget_columns', '=', '2'],
                'options' => [
                    '6-6' => [
                        'alt' => '50-50',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/50-50.png'
                    ],
                    '3-9' => [
                        'alt' => '25-75',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/25-75.png'
                    ],
                    '9-3' => [
                        'alt' => '75-25',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/75-25.png'
                    ],
                    '4-8' => [
                        'alt' => '33-66',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/33-66.png'
                    ],
                    '8-4' => [
                        'alt' => '66-33',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/66-33.png'
                    ]
                ],
                'default' => '6-6',
            ],
            [
                'id' => 'widget_columns_3',
                'title' => esc_html__('Columns Layout', 'transmax'),
                'type' => 'image_select',
                'required' => ['widget_columns', '=', '3'],
                'options' => [
                    '4-4-4' => [
                        'alt' => '33-33-33',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/33-33-33.png'
                    ],
                    '3-3-6' => [
                        'alt' => '25-25-50',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/25-25-50.png'
                    ],
                    '3-6-3' => [
                        'alt' => '25-50-25',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/25-50-25.png'
                    ],
                    '6-3-3' => [
                        'alt' => '50-25-25',
                        'img' => get_template_directory_uri() . '/core/admin/img/options/50-25-25.png'
                    ],
                ],
                'default' => '4-4-4',
            ],
            [
                'id' => 'footer_spacing',
                'title' => esc_html__('Paddings', 'transmax'),
                'type' => 'spacing',
                'required' => ['footer_building_tool', '=', 'widgets'],
                'output' => ['.wgl-footer'],
                'all' => false,
                'mode' => 'padding',
                'units' => 'px',
                'default' => [
                    'padding-top' => '50px',
                    'padding-right' => '0px',
                    'padding-bottom' => '0px',
                    'padding-left' => '0px'
                ],
            ],
            [
                'id' => 'footer_full_width',
                'title' => esc_html__('Full Width On/Off', 'transmax'),
                'type' => 'switch',
                'required' => ['footer_building_tool', '=', 'widgets'],
                'default' => false,
            ],
            [
                'id' => 'footer-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'footer-start-styles',
                'title' => esc_html__('Footer Styling', 'transmax'),
                'type' => 'section',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'indent' => true,
            ],
            [
                'id' => 'footer_bg_image',
                'title' => esc_html__('Background Image', 'transmax'),
                'type' => 'background',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                ],
            ],
            [
                'id' => 'footer_align',
                'title' => esc_html__('Content Align', 'transmax'),
                'type' => 'button_set',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'footer_bg_color',
                'title' => esc_html__('Background Color', 'transmax'),
                'type' => 'color',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'transparent' => false,
                'default' => '#f5f5f5',
            ],
            [
                'id' => 'footer_heading_color',
                'title' => esc_html__('Headings color', 'transmax'),
                'type' => 'color',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'transparent' => false,
                'default' => '#202020',
            ],
            [
                'id' => 'footer_text_color',
                'title' => esc_html__('Content color', 'transmax'),
                'type' => 'color',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'transparent' => false,
                'default' => '#464646',
            ],
            [
                'id' => 'footer_add_border',
                'title' => esc_html__('Add Border Top', 'transmax'),
                'type' => 'switch',
                'required' => [
                    ['footer_switch', '=', '1'],
                    ['footer_building_tool', '=', 'widgets'],
                ],
                'default' => false,
            ],
            [
                'id' => 'footer_border_color',
                'title' => esc_html__('Border color', 'transmax'),
                'type' => 'color',
                'required' => ['footer_add_border', '=', '1'],
                'transparent' => false,
                'default' => '#dcdcdc',
            ],
            [
                'id' => 'footer-end-styles',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'footer-copyright',
        'title' => esc_html__('Copyright', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'copyright_switch',
                'type' => 'switch',
                'title' => esc_html__('Copyright', 'transmax'),
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Disable', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'copyright-start',
                'type' => 'section',
                'title' => esc_html__('Copyright Settings', 'transmax'),
                'indent' => true,
                'required' => ['copyright_switch', '=', '1'],
            ],
            [
                'id' => 'copyright_editor',
                'title' => esc_html__('Editor', 'transmax'),
                'type' => 'editor',
                'required' => ['copyright_switch', '=', '1'],
                'args' => [
                    'wpautop' => false,
                    'media_buttons' => false,
                    'textarea_rows' => 2,
                    'teeny' => false,
                    'quicktags' => true,
                ],
                'default' => '<p>Copyright © 2021 Transmax by WebGeniusLab. All Rights Reserved</p>',
            ],
            [
                'id' => 'copyright_text_color',
                'title' => esc_html__('Text Color', 'transmax'),
                'type' => 'color',
                'required' => ['copyright_switch', '=', '1'],
                'transparent' => false,
                'default' => '#6e6e6e',
            ],
            [
                'id' => 'copyright_bg_color',
                'title' => esc_html__('Background Color', 'transmax'),
                'type' => 'color',
                'required' => ['copyright_switch', '=', '1'],
                'transparent' => false,
                'default' => '#f5f5f5',
            ],
            [
                'id' => 'copyright_spacing',
                'type' => 'spacing',
                'title' => esc_html__('Paddings', 'transmax'),
                'required' => ['copyright_switch', '=', '1'],
                'mode' => 'padding',
                'all' => false,
                'left' => false,
                'right' => false,
                'default' => [
                    'padding-top' => '20',
                    'padding-bottom' => '20',
                ],
            ],
            [
                'id' => 'copyright-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'blog-option',
        'title' => esc_html__('Blog', 'transmax'),
        'icon' => 'el el-bullhorn',
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'blog-list-option',
        'title' => esc_html__('Archive', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'blog_list_page_title-start',
                'title' => esc_html__('Page Title', 'transmax'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'post_archive__page_title_bg_image',
                'title' => esc_html__('Background Image', 'transmax'),
                'type' => 'background',
                'background-color' => false,
                'preview_media' => true,
                'preview' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                ],
            ],
            [
                'id' => 'blog_list_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'blog_list_sidebar-start',
                'title' => esc_html__('Sidebar', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'blog_list_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'transmax'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'blog_list_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'transmax'),
                'type' => 'select',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'blog_list_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'transmax'),
                'type' => 'button_set',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => esc_html__('25%', 'transmax'),
                    '8' => esc_html__('33%', 'transmax'),
                ],
                'default' => '9',
            ],
            [
                'id' => 'blog_list_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'transmax'),
                'type' => 'switch',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'default' => false,
            ],
            [
                'id' => 'blog_list_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'transmax'),
                'type' => 'select',
                'required' => ['blog_list_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'transmax'),
                    '0' => esc_html('0'),
                    '15' => esc_html('15'),
                    '20' => esc_html('20'),
                    '25' => esc_html('25'),
                    '30' => esc_html('30'),
                    '35' => esc_html('35'),
                    '40' => esc_html('40'),
                    '45' => esc_html('45'),
                    '50' => esc_html('50'),
                ],
                'default' => 'def',
            ],
            [
                'id' => 'blog_list_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'blog_list_appearance-start',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'blog_list_columns',
                'title' => esc_html__('Columns in Archive', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    '12' => esc_html__('One', 'transmax'),
                    '6' => esc_html__('Two', 'transmax'),
                    '4' => esc_html__('Three', 'transmax'),
                    '3' => esc_html__('Four', 'transmax'),
                ],
                'default' => '12',
            ],
            [
                'id' => 'blog_list_likes',
                'title' => esc_html__('Likes', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_views',
                'title' => esc_html__('Views', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_share',
                'title' => esc_html__('Shares', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_hide_media',
                'title' => esc_html__('Hide Media?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_hide_title',
                'title' => esc_html__('Hide Title?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_hide_content',
                'title' => esc_html__('Hide Content?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_post_listing_content',
                'title' => esc_html__('Limit the characters amount in Content?', 'transmax'),
                'type' => 'switch',
                'required' => ['blog_list_hide_content', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_letter_count',
                'title' => esc_html__('Characters amount to be displayed in Content', 'transmax'),
                'type' => 'text',
                'required' => ['blog_post_listing_content', '=', true],
                'default' => '85',
            ],
            [
                'id' => 'blog_list_read_more',
                'title' => esc_html__('Hide Read More Button?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta',
                'title' => esc_html__('Hide all post-meta?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_author',
                'title' => esc_html__('Hide post-meta author?', 'transmax'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_comments',
                'title' => esc_html__('Hide post-meta comments?', 'transmax'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'blog_list_meta_categories',
                'title' => esc_html__('Hide post-meta categories?', 'transmax'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_meta_date',
                'title' => esc_html__('Hide post-meta date?', 'transmax'),
                'type' => 'switch',
                'required' => ['blog_list_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_list_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'blog-single-option',
        'title' => esc_html__('Single', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'single_type_layout',
                'title' => esc_html__('Default Post Layout', 'transmax'),
                'type' => 'button_set',
                'desc' => esc_html__('Note: each Post can be separately customized within its Metaboxes section.', 'transmax'),
                'options' => [
                    '1' => esc_html__('Title First', 'transmax'),
                    '2' => esc_html__('Image First', 'transmax'),
                    '3' => esc_html__('Overlay Image', 'transmax')
                ],
                'default' => '3',
            ],
            [
                'id' => 'blog_single_page_title-start',
                'title' => esc_html__('Page Title', 'transmax'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'blog_title_conditional',
                'title' => esc_html__('Page Title Text', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Post Type Name', 'transmax'),
                'off' => esc_html__('Post Title', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'blog_single__page_title_breadcrumbs_switch',
                'title' => esc_html__('Breadcrumbs', 'transmax'),
                'type' => 'switch',
                'required' => ['single_type_layout', '!=', '3'],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'post_single__page_title_bg_switch',
                'title' => esc_html__('Use Background Image/Color?', 'transmax'),
                'type' => 'switch',
                'required' => ['single_type_layout', '!=', '3'],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'post_single__page_title_bg_image',
                'title' => esc_html__('Background Image/Color', 'transmax'),
                'type' => 'background',
                'required' => ['single_type_layout', '!=', '3'],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                ],
            ],
	        [
		        'id' => 'post_single_layout_3_bg_image',
		        'type' => 'background',
                'title' => esc_html__('Default Background', 'transmax'),
                'required' => ['single_type_layout', '=', '3'],
                'desc' => esc_html__('Note: If Featured Image doesn\'t exist.', 'transmax'),
		        'preview' => false,
		        'preview_media' => true,
		        'background-color' => true,
		        'transparent' => false,
		        'background-repeat' => false,
		        'background-size' => false,
		        'background-attachment' => false,
		        'background-position' => false,
		        'default' => [
			        'background-color' => '#113039',
		        ],
	        ],
            [
                'id' => 'single_padding_layout_3',
                'type' => 'spacing',
                'title' => esc_html__('Padding Top/Bottom', 'transmax'),
                'required' => ['single_type_layout', '=', '3'],
                'mode' => 'padding',
                'all' => false,
                'top' => true,
                'right' => false,
                'bottom' => true,
                'left' => false,
                'default' => [
                    'padding-top' => '340',
                    'padding-bottom' => '0',
                ],
            ],
            [
                'id' => 'blog_single_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'blog_single_sidebar-start',
                'type' => 'section',
                'title' => esc_html__('Sidebar', 'transmax'),
                'indent' => true,
            ],
            [
                'id' => 'single_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'transmax'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'right'
            ],
            [
                'id' => 'single_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'transmax'),
                'type' => 'select',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
                'default' => 'sidebar_main-sidebar',
            ],
            [
                'id' => 'single_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'transmax'),
                'type' => 'button_set',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => esc_html__('25%', 'transmax'),
                    '8' => esc_html__('33%', 'transmax'),
                ],
                'default' => '9',
            ],
            [
                'id' => 'single_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'transmax'),
                'type' => 'switch',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'default' => true,
            ],
            [
                'id' => 'single_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'transmax'),
                'type' => 'select',
                'required' => ['single_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'transmax'),
                    '0' => esc_html('0'),
                    '15' => esc_html('15'),
                    '20' => esc_html('20'),
                    '25' => esc_html('25'),
                    '30' => esc_html('30'),
                    '35' => esc_html('35'),
                    '40' => esc_html('40'),
                    '45' => esc_html('45'),
                    '50' => esc_html('50'),
                ],
                'default' => 'def',
            ],
            [
                'id' => 'blog_single_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'blog_single_appearance-start',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'featured_image_type',
                'title' => esc_html__('Featured Image', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'default' => esc_html__('Default', 'transmax'),
                    'off' => esc_html__('Off', 'transmax'),
                    'replace' => esc_html__('Replace', 'transmax')
                ],
                'default' => 'default',
            ],
            [
                'id' => 'featured_image_replace',
                'title' => esc_html__('Image To Replace On', 'transmax'),
                'type' => 'media',
                'required' => ['featured_image_type', '=', 'replace'],
            ],
            [
                'id' => 'single_apply_animation',
                'title' => esc_html__('Apply Animation?', 'transmax'),
                'type' => 'switch',
                'required' => ['single_type_layout', '=', '3'],
                'desc' => transmax_quick_tip(
                    wp_kses(
                        __('Fade out the Post Title during page scrolling. <br>Note: affects only <code>Overlay Image</code> post layouts', 'transmax'),
                        ['br' => [], 'code' => []]
                    )
                ),
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'single_likes',
                'title' => esc_html__('Likes', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'single_views',
                'title' => esc_html__('Views', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'single_share',
                'title' => esc_html__('Shares', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_tags',
                'title' => esc_html__('Tags', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'single_author_info',
                'title' => esc_html__('Author Info', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'single_meta',
                'title' => esc_html__('Hide all post-meta?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_author',
                'title' => esc_html__('Hide post-meta author?', 'transmax'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'single_meta_comments',
                'title' => esc_html__('Hide post-meta comments?', 'transmax'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'single_meta_categories',
                'title' => esc_html__('Hide post-meta categories?', 'transmax'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'single_meta_date',
                'title' => esc_html__('Hide post-meta date?', 'transmax'),
                'type' => 'switch',
                'required' => ['single_meta', '=', false],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'blog_single_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'blog-single-related-option',
        'title' => esc_html__('Related', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'single_related_posts',
                'title' => esc_html__('Related Posts', 'transmax'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'blog_title_r',
                'title' => esc_html__('Related Section Title', 'transmax'),
                'type' => 'text',
                'required' => ['single_related_posts', '=', '1'],
                'default' => esc_html__('Related Posts', 'transmax'),
            ],
            [
                'id' => 'blog_cat_r',
                'title' => esc_html__('Select Categories', 'transmax'),
                'type' => 'select',
                'required' => ['single_related_posts', '=', '1'],
                'multi' => true,
                'data' => 'categories',
                'width' => '20%',
            ],
            [
                'id' => 'blog_column_r',
                'title' => esc_html__('Columns', 'transmax'),
                'type' => 'button_set',
                'required' => ['single_related_posts', '=', '1'],
                'options' => [
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4'
                ],
                'default' => '6',
            ],
            [
                'id' => 'blog_number_r',
                'title' => esc_html__('Number of Related Items', 'transmax'),
                'type' => 'text',
                'required' => ['single_related_posts', '=', '1'],
                'default' => '2',
            ],
            [
                'id' => 'blog_carousel_r',
                'title' => esc_html__('Display items in the carousel', 'transmax'),
                'type' => 'switch',
                'required' => ['single_related_posts', '=', '1'],
                'default' => true,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'portfolio-option',
        'title' => esc_html__('Portfolio', 'transmax'),
        'icon' => 'el el-picture',
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'portfolio-list-option',
        'title' => esc_html__('Archive', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_slug',
                'title' => esc_html__('Portfolio Slug', 'transmax'),
                'type' => 'text',
                'default' => 'portfolio',
            ],
            [
                'id' => 'portfolio_archive_page_title-start',
                'title' => esc_html__('Page Title', 'transmax'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', '1'],
                'indent' => true,
            ],
            [
                'id' => 'portfolio_archive__page_title_bg_image',
                'title' => esc_html__('Page Title Background Image', 'transmax'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '',
                ],
            ],
            [
                'id' => 'portfolio_archive_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_archive_sidebar-start',
                'title' => esc_html__('Sidebar', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_list_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'transmax'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'portfolio_list_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'transmax'),
                'type' => 'select',
                'required' => ['portfolio_list_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'portfolio_list_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'transmax'),
                'type' => 'button_set',
                'required' => ['portfolio_list_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => esc_html__('25%', 'transmax'),
                    '8' => esc_html__('33%', 'transmax'),
                ],
                'default' => '9',
            ],
            [
                'id' => 'portfolio_archive_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_list_appearance-start',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_list_columns',
                'title' => esc_html__('Columns in Archive', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    '1' => esc_html__('One', 'transmax'),
                    '2' => esc_html__('Two', 'transmax'),
                    '3' => esc_html__('Three', 'transmax'),
                    '4' => esc_html__('Four', 'transmax'),
                ],
                'default' => '3',
            ],
            [
                'id' => 'portfolio_list_show_title',
                'title' => esc_html__('Title', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_list_show_content',
                'title' => esc_html__('Content', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_list_show_cat',
                'title' => esc_html__('Categories', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_list_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'portfolio-single-option',
        'title' => esc_html__('Single', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_single_layout-start',
                'title' => esc_html__('Layout', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_single_type_layout',
                'title' => esc_html__('Portfolio Single Layout', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    '1' => esc_html__('Title First', 'transmax'),
                    '2' => esc_html__('Image First', 'transmax'),
                ],
                'default' => '2',
            ],
            [
                'id' => 'portfolio_single_layout-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_single_page_title-start',
                'title' => esc_html__('Page Title', 'transmax'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', true],
                'indent' => true,
            ],
            [
                'id' => 'portfolio_title_conditional',
                'title' => esc_html__('Page Title Text', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Post Type Name', 'transmax'),
                'off' => esc_html__('Post Title', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_title_align',
                'title' => esc_html__('Title Alignment', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'portfolio_single_breadcrumbs_align',
                'title' => esc_html__('Breadcrumbs Alignment', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'center',
            ],
            [
                'id' => 'portfolio_single_breadcrumbs_block_switch',
                'title' => esc_html__('Breadcrumbs Full Width', 'transmax'),
                'type' => 'switch',
                'default' => true,
            ],
            [
                'id' => 'portfolio_single__page_title_bg_switch',
                'title' => esc_html__('Use Background Image/Color?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single__page_title_bg_image',
                'title' => esc_html__('Background Image/Color', 'transmax'),
                'type' => 'background',
                'required' => ['portfolio_single__page_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '',
                ],
            ],
            [
                'id' => 'portfolio_single__page_title_height',
                'title' => esc_html__('Min Height', 'transmax'),
                'type' => 'dimensions',
                'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'transmax'),
                'height' => true,
                'width' => false,
            ],
            [
                'id' => 'portfolio_single__page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
            ],
            [
                'id' => 'portfolio_single__page_title_margin',
                'title' => esc_html__('Margin Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => true,
                'top' => false,
                'left' => false,
                'right' => false,
            ],
            [
                'id' => 'portfolio_single_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_single_sidebar-start',
                'title' => esc_html__('Sidebar', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_single_sidebar_layout',
                'title' => esc_html__('Sidebar Layout', 'transmax'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'portfolio_single_sidebar_def',
                'title' => esc_html__('Sidebar Template', 'transmax'),
                'type' => 'select',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'portfolio_single_sidebar_def_width',
                'title' => esc_html__('Sidebar Width', 'transmax'),
                'type' => 'button_set',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => esc_html__('25%', 'transmax'),
                    '8' => esc_html__('33%', 'transmax'),
                ],
                'default' => '8',
            ],
            [
                'id' => 'portfolio_single_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'transmax'),
                'type' => 'switch',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'transmax'),
                'type' => 'select',
                'required' => ['portfolio_single_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'transmax'),
                    '0' => esc_html('0'),
                    '15' => esc_html('15'),
                    '20' => esc_html('20'),
                    '25' => esc_html('25'),
                    '30' => esc_html('30'),
                    '35' => esc_html('35'),
                    '40' => esc_html('40'),
                    '45' => esc_html('45'),
                    '50' => esc_html('50'),
                ],
                'default' => 'def',
            ],
            [
                'id' => 'portfolio_single_sidebar-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => 'portfolio_single_appearance-start',
                'title' => esc_html__('Appearance', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'portfolio_above_content_cats',
                'title' => esc_html__('Tags', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_above_content_share',
                'title' => esc_html__('Shares', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'portfolio_single_meta_likes',
                'title' => esc_html__('Likes', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta',
                'title' => esc_html__('Hide all post-meta?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_author',
                'title' => esc_html__('Post-meta author', 'transmax'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_comments',
                'title' => esc_html__('Post-meta comments', 'transmax'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_categories',
                'title' => esc_html__('Post-meta categories', 'transmax'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_meta_date',
                'title' => esc_html__('Post-meta date', 'transmax'),
                'type' => 'switch',
                'required' => ['portfolio_single_meta', '=', false],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'portfolio_single_appearance-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'portfolio-related-option',
        'title' => esc_html__('Related Posts', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_related_switch',
                'title' => esc_html__('Related Posts', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'pf_title_r',
                'title' => esc_html__('Title', 'transmax'),
                'type' => 'text',
                'required' => ['portfolio_related_switch', '=', '1'],
                'default' => esc_html__('Related Projects', 'transmax'),
            ],
            [
                'id' => 'pf_carousel_r',
                'title' => esc_html__('Display items within carousel for this post', 'transmax'),
                'type' => 'switch',
                'required' => ['portfolio_related_switch', '=', '1'],
                'default' => true,
            ],
            [
                'id' => 'pf_column_r',
                'title' => esc_html__('Related Columns', 'transmax'),
                'type' => 'button_set',
                'required' => ['portfolio_related_switch', '=', '1'],
                'options' => [
                    '2' => esc_html__('Two', 'transmax'),
                    '3' => esc_html__('Three', 'transmax'),
                    '4' => esc_html__('Four', 'transmax'),
                ],
                'default' => '3',
            ],
            [
                'id' => 'pf_number_r',
                'title' => esc_html__('Number of Related Items', 'transmax'),
                'type' => 'text',
                'required' => ['portfolio_related_switch', '=', '1'],
                'default' => '3',
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'portfolio-advanced',
        'title' => esc_html__('Advanced', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'portfolio_archives',
                'title' => esc_html__('Portfolio Archives', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Enabled', 'transmax'),
                'off' => esc_html__('Disabled', 'transmax'),
                'default' => true,
                'desc' => transmax_quick_tip(sprintf(
                    wp_kses(
                        __('Archive Page lists all the portfolio posts you have created. <br>This option will disable only the Archive Page, while the post\'s Single Pages will still be displayed. <br>Note: you need to refresh your <a href="%s">permalinks</a> after switching this option.', 'transmax'),
                        ['a' => ['href' => true], 'br' => []]
                    ),
                    esc_url(admin_url('options-permalink.php'))
                )),
            ],
            [
                'id' => 'portfolio_singular',
                'title' => esc_html__('Portfolio Single', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Enabled', 'transmax'),
                'off' => esc_html__('Disabled', 'transmax'),
                'default' => true,
                'desc' => transmax_quick_tip(
                    wp_kses(
                        __('By default, all Portfolio posts have their Single Pages. <br>This creates a specific URL on your website for every post. <br>Selecting "Disabled" will prevent the single view post being publicly displayed.', 'transmax'),
                        ['br' => []]
                    )
                ),
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'team-option',
        'title' => esc_html__('Team', 'transmax'),
        'icon' => 'el el-user',
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'team-single-option',
        'title' => esc_html__('Single', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'team_single_page_title-start',
                'title' => esc_html__('Page Title', 'transmax'),
                'type' => 'section',
                'required' => ['page_title_switch', '=', true],
                'indent' => true,
            ],
            [
                'id' => 'team_title_conditional',
                'title' => esc_html__('Page Title Text', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Post Type Name', 'transmax'),
                'off' => esc_html__('Post Title', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'team_single__page_title_bg_switch',
                'title' => esc_html__('Use Background Image/Color?', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => 'team_single__page_title_bg_image',
                'title' => esc_html__('Background Image/Color', 'transmax'),
                'type' => 'background',
                'required' => ['team_single__page_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                    'background-color' => '',
                ],
            ],
            [
                'id' => 'team_single__page_title_height',
                'title' => esc_html__('Min Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['page_title_bg_switch', '=', true],
                'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'transmax'),
                'height' => true,
                'width' => false,
            ],
            [
                'id' => 'team_single__page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'bottom' => true,
                'top' => true,
                'left' => false,
                'right' => false,
            ],
            [
                'id' => 'team_single__page_title_margin',
                'title' => esc_html__('Margin Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'bottom' => true,
                'top' => false,
                'left' => false,
                'right' => false,
            ],
            [
                'id' => 'team_single_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'team-advanced',
        'title' => esc_html__('Advanced', 'transmax'),
        'subsection' => true,
        'fields' => [
            [
                'id' => 'team_slug',
                'title' => esc_html__('Team Slug', 'transmax'),
                'type' => 'text',
                'default' => 'team',
            ],
            [
                'id' => 'team_singular',
                'title' => esc_html__('Team Singles', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Enabled', 'transmax'),
                'off' => esc_html__('Disabled', 'transmax'),
                'default' => true,
                'desc' => esc_html__('By default, all team posts have single views enabled. This creates a specific URL on your website for that post. Selecting "Disabled" will prevent the single view post being publicly displayed.', 'transmax'),
            ],
            [
                'id' => 'team_archives',
                'title' => esc_html__('Team Archive', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Enabled', 'transmax'),
                'off' => esc_html__('Disabled', 'transmax'),
                'default' => true,
                'desc' => sprintf(
                    wp_kses(
                        __('Archive Page lists all the Team Members you have created. This option will disable only the member\'s Archive Page. The member\'s Single Pages will still be displayed. Note: you will need to refresh your <a href="%s">permalinks</a> after switching this option.', 'transmax'),
                        ['a' => ['href' => true, 'target' => true]]
                    ),
                    esc_url(admin_url('options-permalink.php'))
                ),
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'title' => esc_html__('Page 404', 'transmax'),
        'id' => '404-option',
        'icon' => 'el el-error',
        'fields' => [
            [
                'id' => '404_building_tool',
                'title' => esc_html__('Layout Building Tool', 'transmax'),
                'type' => 'select',
                'options' => [
                    'default' => esc_html__('Default', 'transmax'),
                    'elementor' => esc_html__('Elementor', 'transmax'),
                ],
                'default' => 'default',
            ],
            [
                'id' => '404_template_select',
                'type' => 'select',
                'title' => esc_html__('Select Template', 'transmax'),
                'required' => ['404_building_tool', '=', 'elementor'],
                'data' => 'posts',
                'desc' => sprintf(
                    '%s <br>%s <a href="%s" target="_blank">%s</a> %s',
                    esc_html__('Selected Template will be used for 404 page by default.', 'transmax'),
                    esc_html__('You can edit/create Template in the', 'transmax'),
                    admin_url('edit.php?post_type=elementor_library&tabs_group=library'),
                    esc_html__('Saved Templates', 'transmax'),
                    esc_html__('dashboard tab.', 'transmax')
                ),
                'args' => [
                    'post_type' => 'elementor_library',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => '404_show_header',
                'type' => 'switch',
                'title' => esc_html__('Header Section', 'transmax'),
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => '404_page_title_switcher',
                'title' => esc_html__('Page Title Section', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => '404_page_title-start',
                'type' => 'section',
                'required' => ['404_page_title_switcher', '=', true],
                'indent' => true,
            ],
            [
                'id' => '404_custom_title_switch',
                'title' => esc_html__('Page Title Text', 'transmax'),
                'type' => 'switch',
                'required' => ['404_page_title_switcher', '=', true],
                'on' => esc_html__('Custom', 'transmax'),
                'off' => esc_html__('Default', 'transmax'),
                'default' => false,
            ],
            [
                'id' => '404_page_title_text',
                'title' => esc_html__('Custom Page Title Text', 'transmax'),
                'type' => 'text',
                'required' => ['404_custom_title_switch', '=', true],
            ],
            [
                'id' => '404_page__page_title_bg_switch',
                'title' => esc_html__('Use Background Image/Color?', 'transmax'),
                'type' => 'switch',
                'required' => ['404_page_title_switcher', '=', true],
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
            [
                'id' => '404_page__page_title_bg_image',
                'title' => esc_html__('Background Image/Color', 'transmax'),
                'type' => 'background',
                'required' => ['404_page__page_title_bg_switch', '=', true],
                'preview' => false,
                'preview_media' => true,
                'background-color' => true,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                ],
            ],
            [
                'id' => '404_page__page_title_height',
                'title' => esc_html__('Min Height', 'transmax'),
                'type' => 'dimensions',
                'required' => ['page_title_bg_switch', '=', true],
                'desc' => esc_html__('Choose `0px` in order to use `min-height: auto;`', 'transmax'),
                'height' => true,
                'width' => false,
            ],
            [
                'id' => '404_page__page_title_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'top' => true,
                'bottom' => true,
                'left' => false,
                'right' => false,
            ],
            [
                'id' => '404_page__page_title_margin',
                'title' => esc_html__('Margin Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'margin',
                'all' => false,
                'top' => false,
                'bottom' => true,
                'left' => false,
                'right' => false,
                'default' => [
                    'margin-bottom' => '0',
                ],
            ],
            [
                'id' => '404_page_title-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => '404_page_main-start',
                'type' => 'section',
                'title' => esc_html__('Section Settings', 'transmax'),
                'required' => ['404_page_title_switcher', '=', true],
                'indent' => true,
            ],
            [
                'id' => '404_page_main_bg_image',
                'title' => esc_html__('Background Image/Color', 'transmax'),
                'type' => 'background',
                'preview' => false,
                'preview_media' => true,
                'background-color' => false,
                'transparent' => false,
                'default' => [
                    'background-repeat' => 'repeat',
                    'background-size' => 'cover',
                    'background-attachment' => 'scroll',
                    'background-position' => 'center center',
                ],
            ],
            [
                'id' => '404_page_main_padding',
                'title' => esc_html__('Paddings Top/Bottom', 'transmax'),
                'type' => 'spacing',
                'mode' => 'padding',
                'all' => false,
                'top' => true,
                'bottom' => true,
                'left' => false,
                'right' => false,
            ],
            [
                'id' => '404_page_main-end',
                'type' => 'section',
                'indent' => false,
            ],
            [
                'id' => '404_show_footer',
                'title' => esc_html__('Footer Section', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => true,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'side_panel',
        'title' => esc_html__('Side Panel', 'transmax'),
        'icon' => 'el el-indent-left',
        'fields' => [
            [
                'id' => 'side_panel_enabled',
                'title' => esc_html__('Side Panel', 'transmax'),
                'type' => 'switch',
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Disable', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'side_panel-start',
                'title' => esc_html__('Layout', 'transmax'),
                'type' => 'section',
                'required' => ['side_panel_enabled', '=', true],
                'indent' => true,
            ],
            [
                'id' => 'side_panel_building_tool',
                'title' => esc_html__('Layout Building Tool', 'transmax'),
                'type' => 'select',
                'options' => [
                    'widgets' => esc_html__('Wordpress Widgets', 'transmax'),
                    'elementor' => esc_html__('Elementor (recommended)', 'transmax'),
                ],
                'default' => 'elementor',
            ],
            [
                'id' => 'side_panel_page_select',
                'title' => esc_html__('Select Template', 'transmax'),
                'type' => 'select',
                'required' => ['side_panel_building_tool', '=', 'elementor'],
                'desc' => wp_kses(
                    sprintf(
                        '%s <a href="%s" target="_blank">%s</a> %s<br> %s',
                        __('You can edit/create Side Panel Template in the', 'transmax'),
                        admin_url('edit.php?post_type=side_panel'),
                        __('Side Panel', 'transmax'),
                        __('dashboard tab.', 'transmax'),
                        transmax_quick_tip(
                            sprintf(
                                __('Note: fine tuning is available through the Elementor\'s <code>Post Settings</code> tab, which is located <a href="%s" target="_blank">here</a>', 'transmax'),
                                get_template_directory_uri() . '/core/admin/img/dashboard/quick_tip__side_panel_extra_options.png'
                            )
                        )
                    ),
                    ['a' => ['href' => true, 'target' => true], 'br' => [], 'span' => ['class' => true], 'i' => ['class' => true], 'code' => []]
                ),
                'data' => 'posts',
                'args' => [
                    'post_type' => 'side_panel',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ],
            ],
            [
                'id' => 'side_panel_spacing',
                'title' => esc_html__('Margin', 'transmax'),
                'type' => 'spacing',
                'mode' => 'margin',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'units' => 'px',
                'all' => false,
                'default' => [
                    'margin-top' => '50',
                    'margin-right' => '50',
                    'margin-bottom' => '50',
                    'margin-left' => '50',
                ],
            ],
            [
                'id' => 'side_panel_title_color',
                'title' => esc_html__('Title Color', 'transmax'),
                'type' => 'color',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'side_panel_text_color',
                'title' => esc_html__('Text Color', 'transmax'),
                'type' => 'color',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'side_panel_bg',
                'title' => esc_html__('Background', 'transmax'),
                'type' => 'color_rgba',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'mode' => 'background',
                'default' => [
                    'alpha' => '1',
                    'rgba' => 'rgba(44,44,44,1)',
                    'color' => '#034b5b',
                ],
            ],
            [
                'id' => 'side_panel_text_alignment',
                'title' => esc_html__('Text Align', 'transmax'),
                'type' => 'button_set',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'center' => esc_html__('Center', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'left',
            ],
            [
                'id' => 'side_panel_width',
                'title' => esc_html__('Width', 'transmax'),
                'type' => 'dimensions',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'width' => true,
                'height' => false,
                'default' => ['width' => 370],
            ],
            [
                'id' => 'side_panel_position',
                'title' => esc_html__('Position', 'transmax'),
                'type' => 'button_set',
                'required' => ['side_panel_building_tool', '=', 'widgets'],
                'options' => [
                    'left' => esc_html__('Left', 'transmax'),
                    'right' => esc_html__('Right', 'transmax'),
                ],
                'default' => 'right'
            ],
            [
                'id' => 'side_panel-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'layout_options',
        'title' => esc_html__('Sidebars', 'transmax'),
        'icon' => 'el el-braille',
        'fields' => [
            [
                'id' => 'sidebars',
                'title' => esc_html__('Register Sidebars', 'transmax'),
                'type' => 'multi_text',
                'validate' => 'no_html',
                'add_text' => esc_html__('Add Sidebar', 'transmax'),
                'default' => ['Main Sidebar'],
            ],
            [
                'id' => 'sidebars-start',
                'title' => esc_html__('Sidebar Settings', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'page_sidebar_layout',
                'title' => esc_html__('Page Sidebar Layout', 'transmax'),
                'type' => 'image_select',
                'options' => [
                    'none' => [
                        'alt' => esc_html__('None', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                    ],
                    'left' => [
                        'alt' => esc_html__('Left', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                    ],
                    'right' => [
                        'alt' => esc_html__('Right', 'transmax'),
                        'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                    ]
                ],
                'default' => 'none'
            ],
            [
                'id' => 'page_sidebar_def',
                'title' => esc_html__('Page Sidebar', 'transmax'),
                'type' => 'select',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'data' => 'sidebars',
            ],
            [
                'id' => 'page_sidebar_def_width',
                'title' => esc_html__('Page Sidebar Width', 'transmax'),
                'type' => 'button_set',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'options' => [
                    '9' => esc_html__('25%', 'transmax'),
                    '8' => esc_html__('33%', 'transmax'),
                ],
                'default' => '9',
            ],
            [
                'id' => 'page_sidebar_sticky',
                'title' => esc_html__('Sticky Sidebar', 'transmax'),
                'type' => 'switch',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'default' => false,
            ],
            [
                'id' => 'page_sidebar_gap',
                'title' => esc_html__('Sidebar Side Gap', 'transmax'),
                'type' => 'select',
                'required' => ['page_sidebar_layout', '!=', 'none'],
                'options' => [
                    'def' => esc_html__('Default', 'transmax'),
                    '0' => esc_html('0'),
                    '15' => esc_html('15'),
                    '20' => esc_html('20'),
                    '25' => esc_html('25'),
                    '30' => esc_html('30'),
                    '35' => esc_html('35'),
                    '40' => esc_html('40'),
                    '45' => esc_html('45'),
                    '50' => esc_html('50'),
                ],
                'default' => 'def',
            ],
            [
                'id' => 'sidebars-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'soc_shares',
        'title' => esc_html__('Social Shares', 'transmax'),
        'icon' => 'el el-share-alt',
        'fields' => [
            [
                'id' => 'post_shares',
                'title' => esc_html__('Share List', 'transmax'),
                'type' => 'checkbox',
                'desc' => esc_html__('Note: used only on Blog Single, Blog List and Portfolio Single pages', 'transmax'),
                'options' => [
                    'telegram' => esc_html__('Telegram', 'transmax'),
                    'reddit' => esc_html__('Reddit', 'transmax'),
                    'twitter' => esc_html__('Twitter', 'transmax'),
                    'whatsapp' => esc_html__('WhatsApp', 'transmax'),
                    'facebook' => esc_html__('Facebook', 'transmax'),
                    'pinterest' => esc_html__('Pinterest', 'transmax'),
                    'linkedin' => esc_html__('Linkedin', 'transmax'),
                ],
                'default' => [
                    'telegram' => '0',
                    'reddit' => '0',
                    'twitter' => '1',
                    'whatsapp' => '0',
                    'facebook' => '1',
                    'pinterest' => '1',
                    'linkedin' => '1',
                ]
            ],
            [
                'id' => 'page_socials-start',
                'title' => esc_html__('Page Socials', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'show_soc_icon_page',
                'title' => esc_html__('Page Social Shares', 'transmax'),
                'type' => 'switch',
                'desc' => esc_html__('Social buttons are to be rendered on a left side of each page.', 'transmax'),
                'on' => esc_html__('Use', 'transmax'),
                'off' => esc_html__('Hide', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'soc_icon_style',
                'title' => esc_html__('Socials visibility', 'transmax'),
                'type' => 'button_set',
                'options' => [
                    'standard' => esc_html__('Always', 'transmax'),
                    'hovered' => esc_html__('On Hover', 'transmax'),
                ],
                'default' => 'standard',
                'required' => ['show_soc_icon_page', '=', '1'],
            ],
            [
                'id' => 'soc_icon_offset',
                'title' => esc_html__('Offset Top', 'transmax'),
                'type' => 'spacing',
                'required' => ['show_soc_icon_page', '=', '1'],
                'desc' => esc_html__('If units defined as "%" then socials will be fixed to viewport.', 'transmax'),
                'mode' => 'margin',
                'units' => ['px', '%'],
                'all' => false,
                'top' => true,
                'bottom' => false,
                'left' => false,
                'right' => false,
                'default' => [
                    'margin-top' => '250',
                    'units' => 'px'
                ],
            ],
            [
                'id' => 'soc_icon_facebook',
                'title' => esc_html__('Facebook Button', 'transmax'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_twitter',
                'title' => esc_html__('Twitter Button', 'transmax'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_linkedin',
                'title' => esc_html__('Linkedin Button', 'transmax'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_pinterest',
                'title' => esc_html__('Pinterest Button', 'transmax'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'soc_icon_tumblr',
                'title' => esc_html__('Tumblr Button', 'transmax'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'default' => false,
            ],
            [
                'id' => 'add_custom_share',
                'title' => esc_html__('Need Additional Socials?', 'transmax'),
                'type' => 'switch',
                'required' => ['show_soc_icon_page', '=', '1'],
                'on' => esc_html__('Yes', 'transmax'),
                'off' => esc_html__('No', 'transmax'),
                'default' => false,
            ],
            [
                'id' => 'share_name-1',
                'title' => esc_html__('Social 1 - Name', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_link-1',
                'title' => esc_html__('Social 1 - Link', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_icons-1',
                'title' => esc_html__('Social 1 - Icon', 'transmax'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'share_name-2',
                'title' => esc_html__('Social 2 - Name', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_link-2',
                'title' => esc_html__('Social 2 - Link', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_icons-2',
                'title' => esc_html__('Social 2 - Icon', 'transmax'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'share_name-3',
                'title' => esc_html__('Social 3 - Name', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_link-3',
                'title' => esc_html__('Social 3 - Link', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_icons-3',
                'title' => esc_html__('Social 3 - Icon', 'transmax'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'share_name-4',
                'type' => 'text',
                'title' => esc_html__('Social 4 - Name', 'transmax'),
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_link-4',
                'title' => esc_html__('Social 4 - Link', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_icons-4',
                'type' => 'select',
                'title' => esc_html__('Social 4 - Icon', 'transmax'),
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'share_name-5',
                'title' => esc_html__('Social 5 - Name', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_link-5',
                'title' => esc_html__('Social 5 - Link', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_icons-5',
                'title' => esc_html__('Social 5 - Icon', 'transmax'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'share_name-6',
                'title' => esc_html__('Social 6 - Name', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_link-6',
                'title' => esc_html__('Social 6 - Link', 'transmax'),
                'type' => 'text',
                'required' => ['add_custom_share', '=', '1'],
            ],
            [
                'id' => 'share_icons-6',
                'title' => esc_html__('Social 6 - Icon', 'transmax'),
                'type' => 'select',
                'required' => ['add_custom_share', '=', '1'],
                'data' => 'elusive-icons',
            ],
            [
                'id' => 'page_socials-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

Redux::set_section(
    $theme_slug,
    [
        'id' => 'color_options_color',
        'title' => esc_html__('Color Settings', 'transmax'),
        'icon' => 'el-icon-tint',
        'fields' => [
            [
                'id' => 'theme_colors-start',
                'title' => esc_html__('Theme Colors', 'transmax'),
                'type' => 'section',
                'indent' => true,
            ],
            [
                'id' => 'theme-primary-color',
                'title' => esc_html__('Primary Theme Color', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ff7d44',
            ],
            [
                'id' => 'theme-secondary-color',
                'title' => esc_html__('Secondary Theme Color', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#034b5b',
            ],
            [
                'id' => 'theme-content-color',
                'title' => esc_html__('Content Color', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#464646',
            ],
            [
                'id' => 'theme-headings-color',
                'title' => esc_html__('Headings Color', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#202020',
            ],
            [
                'id' => 'body-background-color',
                'title' => esc_html__('Body Background Color', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'button-color-idle',
                'title' => esc_html__('Button Color Idle', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'button-color-hover',
                'title' => esc_html__('Button Color Hover', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ffffff',
            ],
            [
                'id' => 'button-bg-color-idle',
                'title' => esc_html__('Button Background Color Idle', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#ff7d44',
            ],
            [
                'id' => 'button-bg-color-hover',
                'title' => esc_html__('Button Background Color Hover', 'transmax'),
                'type' => 'color',
                'validate' => 'color',
                'transparent' => false,
                'default' => '#034b5b',
            ],
            [
                'id' => 'theme_colors-end',
                'type' => 'section',
                'indent' => false,
            ],
        ]
    ]
);

//* ↓ Typography Config
Redux::set_section(
    $theme_slug,
    [
        'id' => 'Typography',
        'title' => esc_html__('Typography', 'transmax'),
        'icon' => 'el-icon-font',
    ]
);

$main_typography = [
    [
        'id' => 'main-font',
        'title' => esc_html__('Content Font', 'transmax'),
        'line-height' => true,
        'font-size' => true,
        'subsets' => false,
        'all_styles' => true,
        'font-weight-multi' => true,
        'defs' => [
            'font-size' => '16px',
            'line-height' => '30px',
            'font-family' => 'Nunito Sans',
            'font-weight' => '400',
            'font-weight-multi' => '400,500,700',
        ],
    ],
    [
        'id' => 'header-font',
        'title' => esc_html__('Headings Font', 'transmax'),
        'font-size' => false,
        'line-height' => false,
        'subsets' => false,
        'all_styles' => true,
        'font-weight-multi' => true,
        'defs' => [
            'google' => true,
            'font-family' => 'DM Sans',
            'font-weight' => '400',
            'font-weight-multi' => '400,500,600,700',
        ],
    ],
];
$typography = [];
foreach ($main_typography as $key => $value) {
    array_push($typography, [
        'id' => $value['id'],
        'type' => 'custom_typography',
        'title' => $value['title'],
        'color' => $value['color'] ?? '',
        'line-height' => $value['line-height'],
        'font-size' => $value['font-size'],
        'subsets' => $value['subsets'],
        'all_styles' => $value['all_styles'],
        'font-weight-multi' => $value['font-weight-multi'] ?? '',
        'subtitle' => $value['subtitle'] ?? '',
        'letter-spacing' => $value['letter-spacing'] ?? '',
        'google' => true,
        'font-style' => true,
        'font-backup' => false,
        'text-align' => false,
        'default' => $value['defs'],
    ]);
}

Redux::set_section(
    $theme_slug,
    [
        'id' => 'main_typography',
        'title' => esc_html__('Main Content', 'transmax'),
        'subsection' => true,
        'fields' => $typography,
    ]
);

//* ↓ Menu Typography
$menu_typography = [
    [
        'id' => 'menu-font',
        'title' => esc_html__('Menu Font', 'transmax'),
        'color' => false,
        'line-height' => true,
        'font-size' => true,
        'subsets' => true,
        'defs' => [
            'google' => true,
            'font-family' => 'DM Sans',
            'font-size' => '16px',
            'font-weight' => '500',
            'line-height' => '30px'
        ],
    ],
    [
        'id' => 'sub-menu-font',
        'title' => esc_html__('Submenu Font', 'transmax'),
        'color' => false,
        'line-height' => true,
        'font-size' => true,
        'subsets' => true,
        'defs' => [
            'google' => true,
            'font-family' => 'DM Sans',
            'font-size' => '16px',
            'font-weight' => '500',
            'line-height' => '30px'
        ],
    ],
];
$menu_typography_array = [];
foreach ($menu_typography as $key => $value) {
    array_push($menu_typography_array, [
        'id' => $value['id'],
        'type' => 'custom_typography',
        'title' => $value['title'],
        'color' => $value['color'],
        'line-height' => $value['line-height'],
        'font-size' => $value['font-size'],
        'subsets' => $value['subsets'],
        'google' => true,
        'font-style' => true,
        'font-backup' => false,
        'text-align' => false,
        'all_styles' => false,
        'default' => $value['defs'],
    ]);
}

Redux::set_section(
    $theme_slug,
    [
        'id' => 'main_menu_typography',
        'title' => esc_html__('Menu', 'transmax'),
        'subsection' => true,
        'fields' => $menu_typography_array
    ]
);
//* ↑ menu typography

//* ↓ Headings Typography
$headings = [
    [
        'id' => 'header-h1',
        'title' => esc_html__('‹h1›', 'transmax'),
        'defs' => [
            'font-family' => 'DM Sans',
            'font-size' => '48px',
            'line-height' => '56px',
            'font-weight' => '700',
            'text-transform' => 'none',
        ],
    ],
    [
        'id' => 'header-h2',
        'title' => esc_html__('‹h2›', 'transmax'),
        'defs' => [
            'font-family' => 'DM Sans',
            'font-size' => '42px',
            'line-height' => '48px',
            'font-weight' => '700',
            'text-transform' => 'none',
        ],
    ],
    [
        'id' => 'header-h3',
        'title' => esc_html__('‹h3›', 'transmax'),
        'defs' => [
            'font-family' => 'DM Sans',
            'font-size' => '36px',
            'line-height' => '44px',
            'font-weight' => '700',
            'text-transform' => 'none',
        ],
    ],
    [
        'id' => 'header-h4',
        'title' => esc_html__('‹h4›', 'transmax'),
        'defs' => [
            'font-family' => 'DM Sans',
            'font-size' => '30px',
            'line-height' => '38px',
            'font-weight' => '700',
            'text-transform' => 'none',
        ],
    ],
    [
        'id' => 'header-h5',
        'title' => esc_html__('‹h5›', 'transmax'),
        'defs' => [
            'font-family' => 'DM Sans',
            'font-size' => '24px',
            'line-height' => '30px',
            'font-weight' => '700',
            'text-transform' => 'none',
        ],
    ],
    [
        'id' => 'header-h6',
        'title' => esc_html__('‹h6›', 'transmax'),
        'defs' => [
            'font-family' => 'DM Sans',
            'font-size' => '20px',
            'line-height' => '28px',
            'font-weight' => '700',
            'text-transform' => 'none',
        ],
    ],
];
$headings_array = [];
foreach ($headings as $key => $heading) {
    array_push($headings_array, [
        'id' => $heading['id'],
        'type' => 'custom_typography',
        'title' => $heading['title'],
        'google' => true,
        'font-backup' => false,
        'font-size' => true,
        'line-height' => true,
        'color' => false,
        'word-spacing' => false,
        'letter-spacing' => false,
        'text-align' => false,
        'text-transform' => true,
        'default' => $heading['defs'],
    ]);
}

Redux::set_section(
    $theme_slug,
    [
        'id' => 'main_headings_typography',
        'title' => esc_html__('Headings', 'transmax'),
        'subsection' => true,
        'fields' => $headings_array
    ]
);

if (class_exists('WooCommerce')) {
    Redux::set_section(
        $theme_slug,
        [
            'id' => 'shop-option',
            'title' => esc_html__('Shop', 'transmax'),
            'icon' => 'el-icon-shopping-cart',
            'fields' => []
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'shop-catalog-option',
            'title' => esc_html__('Catalog', 'transmax'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_catalog__page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'transmax'),
                    'type' => 'background',
                    'required' => ['page_title_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ]
                ],
                [
                    'id' => 'shop_catalog_sidebar-start',
                    'title' => esc_html__('Sidebar Settings', 'transmax'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'shop_catalog_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'transmax'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'transmax'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'transmax'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'transmax'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ],
                    ],
                    'default' => 'left',
                ],
                [
                    'id' => 'shop_catalog_sidebar_def',
                    'title' => esc_html__('Shop Catalog Sidebar', 'transmax'),
                    'type' => 'select',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'shop_catalog_sidebar_def_width',
                    'title' => esc_html__('Shop Sidebar Width', 'transmax'),
                    'type' => 'button_set',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html__('25%', 'transmax'),
                        '8' => esc_html__('33%', 'transmax'),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'shop_catalog_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'transmax'),
                    'type' => 'switch',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'shop_catalog_sidebar_gap',
                    'title' => esc_html__('Sidebar Side Gap', 'transmax'),
                    'type' => 'select',
                    'required' => ['shop_catalog_sidebar_layout', '!=', 'none'],
                    'options' => [
                        'def' => esc_html__('Default', 'transmax'),
                        '0' => esc_html('0'),
                        '15' => esc_html('15'),
                        '20' => esc_html('20'),
                        '25' => esc_html('25'),
                        '30' => esc_html('30'),
                        '35' => esc_html('35'),
                        '40' => esc_html('40'),
                        '45' => esc_html('45'),
                        '50' => esc_html('50'),
                    ],
                    'default' => 'def',
                ],
                [
                    'id' => 'shop_catalog_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'shop_column',
                    'title' => esc_html__('Shop Column', 'transmax'),
                    'type' => 'button_set',
                    'options' => [
                        '1' => esc_html('1'),
                        '2' => esc_html('2'),
                        '3' => esc_html('3'),
                        '4' => esc_html('4'),
                    ],
                    'default' => '3',
                ],
                [
                    'id' => 'shop_products_per_page',
                    'title' => esc_html__('Products per page', 'transmax'),
                    'type' => 'spinner',
                    'min' => '1',
                    'max' => '100',
                    'default' => '12',
                ],
                [
                    'id' => 'use_animation_shop',
                    'title' => esc_html__('Use Animation Shop?', 'transmax'),
                    'type' => 'switch',
                    'default' => true,
                ],
                [
                    'id' => 'shop_catalog_animation_style',
                    'title' => esc_html__('Animation Style', 'transmax'),
                    'type' => 'select',
                    'required' => ['use_animation_shop', '=', true],
                    'select2' => ['allowClear' => false],
                    'options' => [
                        'fade-in' => esc_html__('Fade In', 'transmax'),
                        'slide-top' => esc_html__('Slide Top', 'transmax'),
                        'slide-bottom' => esc_html__('Slide Bottom', 'transmax'),
                        'slide-left' => esc_html__('Slide Left', 'transmax'),
                        'slide-right' => esc_html__('Slide Right', 'transmax'),
                        'zoom' => esc_html__('Zoom', 'transmax'),
                    ],
                    'default' => 'slide-left',
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'shop-single-option',
            'title' => esc_html__('Single', 'transmax'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_single_page_title-start',
                    'title' => esc_html__('Page Title Settings', 'transmax'),
                    'type' => 'section',
                    'required' => ['page_title_switch', '=', true],
                    'indent' => true,
                ],
                [
                    'id' => 'shop_title_conditional',
                    'title' => esc_html__('Page Title Text', 'transmax'),
                    'type' => 'switch',
                    'on' => esc_html__('Post Type Name', 'transmax'),
                    'off' => esc_html__('Post Title', 'transmax'),
                    'default' => true,
                ],
                [
                    'id' => 'shop_single_title_align',
                    'title' => esc_html__('Title Alignment', 'transmax'),
                    'type' => 'button_set',
                    'options' => [
                        'left' => esc_html__('Left', 'transmax'),
                        'center' => esc_html__('Center', 'transmax'),
                        'right' => esc_html__('Right', 'transmax'),
                    ],
                    'default' => 'center',
                ],
                [
                    'id' => 'shop_single_breadcrumbs_block_switch',
                    'title' => esc_html__('Breadcrumbs Display', 'transmax'),
                    'type' => 'switch',
                    'required' => ['page_title_breadcrumbs_switch', '=', true],
                    'on' => esc_html__('Block', 'transmax'),
                    'off' => esc_html__('Inline', 'transmax'),
                    'default' => true,
                ],
                [
                    'id' => 'shop_single_breadcrumbs_align',
                    'title' => esc_html__('Title Breadcrumbs Alignment', 'transmax'),
                    'type' => 'button_set',
                    'required' => [
                        ['page_title_breadcrumbs_switch', '=', true],
                        ['shop_single_breadcrumbs_block_switch', '=', true]
                    ],
                    'options' => [
                        'left' => esc_html__('Left', 'transmax'),
                        'center' => esc_html__('Center', 'transmax'),
                        'right' => esc_html__('Right', 'transmax'),
                    ],
                    'default' => 'center',
                ],
                [
                    'id' => 'shop_single__page_title_bg_switch',
                    'title' => esc_html__('Use Background Image/Color?', 'transmax'),
                    'type' => 'switch',
                    'on' => esc_html__('Use', 'transmax'),
                    'off' => esc_html__('Hide', 'transmax'),
                    'default' => true,
                ],
                [
                    'id' => 'shop_single__page_title_bg_image',
                    'title' => esc_html__('Background Image/Color', 'transmax'),
                    'type' => 'background',
                    'required' => ['shop_single__page_title_bg_switch', '=', true],
                    'preview' => false,
                    'preview_media' => true,
                    'background-color' => true,
                    'transparent' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
                [
                    'id' => 'shop_single__page_title_padding',
                    'title' => esc_html__('Paddings Top/Bottom', 'transmax'),
                    'type' => 'spacing',
                    'mode' => 'padding',
                    'all' => false,
                    'bottom' => true,
                    'top' => true,
                    'left' => false,
                    'right' => false,
                ],
                [
                    'id' => 'shop_single__page_title_margin',
                    'title' => esc_html__('Margin Bottom', 'transmax'),
                    'type' => 'spacing',
                    'mode' => 'margin',
                    'all' => false,
                    'bottom' => true,
                    'top' => false,
                    'left' => false,
                    'right' => false,
                    'default' => ['margin-bottom' => '47'],
                ],
                [
                    'id' => 'shop_single_page_title-end',
                    'type' => 'section',
                    'indent' => false,
                ],
                [
                    'id' => 'shop_single_sidebar-start',
                    'title' => esc_html__('Sidebar Settings', 'transmax'),
                    'type' => 'section',
                    'indent' => true,
                ],
                [
                    'id' => 'shop_single_sidebar_layout',
                    'title' => esc_html__('Sidebar Layout', 'transmax'),
                    'type' => 'image_select',
                    'options' => [
                        'none' => [
                            'alt' => esc_html__('None', 'transmax'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/1col.png'
                        ],
                        'left' => [
                            'alt' => esc_html__('Left', 'transmax'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cl.png'
                        ],
                        'right' => [
                            'alt' => esc_html__('Right', 'transmax'),
                            'img' => get_template_directory_uri() . '/core/admin/img/options/2cr.png'
                        ],
                    ],
                    'default' => 'none',
                ],
                [
                    'id' => 'shop_single_sidebar_def',
                    'title' => esc_html__('Sidebar Template', 'transmax'),
                    'type' => 'select',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'data' => 'sidebars',
                ],
                [
                    'id' => 'shop_single_sidebar_def_width',
                    'title' => esc_html__('Sidebar Width', 'transmax'),
                    'type' => 'button_set',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'options' => [
                        '9' => esc_html__('25%', 'transmax'),
                        '8' => esc_html__('33%', 'transmax'),
                    ],
                    'default' => '9',
                ],
                [
                    'id' => 'shop_single_sidebar_sticky',
                    'title' => esc_html__('Sticky Sidebar', 'transmax'),
                    'type' => 'switch',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'default' => false,
                ],
                [
                    'id' => 'shop_single_sidebar_gap',
                    'title' => esc_html__('Sidebar Side Gap', 'transmax'),
                    'type' => 'select',
                    'required' => ['shop_single_sidebar_layout', '!=', 'none'],
                    'options' => [
                        'def' => esc_html__('Default', 'transmax'),
                        '0' => esc_html('0'),
                        '15' => esc_html('15'),
                        '20' => esc_html('20'),
                        '25' => esc_html('25'),
                        '30' => esc_html('30'),
                        '35' => esc_html('35'),
                        '40' => esc_html('40'),
                        '45' => esc_html('45'),
                        '50' => esc_html('50'),
                    ],
                    'default' => 'def',
                ],
                [
                    'id' => 'shop_single_sidebar-end',
                    'type' => 'section',
                    'indent' => false,
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Related', 'transmax'),
            'id' => 'shop-related-option',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_related_columns',
                    'title' => esc_html__('Related products column', 'transmax'),
                    'type' => 'button_set',
                    'options' => [
                        '1' => esc_html('1'),
                        '2' => esc_html('2'),
                        '3' => esc_html('3'),
                        '4' => esc_html('4'),
                    ],
                    'default' => '4',
                ],
                [
                    'id' => 'shop_r_products_per_page',
                    'title' => esc_html__('Related products per page', 'transmax'),
                    'type' => 'spinner',
                    'min' => '1',
                    'max' => '100',
                    'default' => '4',
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'title' => esc_html__('Cart', 'transmax'),
            'id' => 'shop-cart-option',
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_cart__page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'transmax'),
                    'type' => 'background',
                    'required' => ['page_title_switch', '=', true],
                    'background-color' => false,
                    'preview_media' => true,
                    'preview' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
            ]
        ]
    );

    Redux::set_section(
        $theme_slug,
        [
            'id' => 'shop-checkout-option',
            'title' => esc_html__('Checkout', 'transmax'),
            'subsection' => true,
            'fields' => [
                [
                    'id' => 'shop_checkout__page_title_bg_image',
                    'title' => esc_html__('Page Title Background Image', 'transmax'),
                    'type' => 'background',
                    'required' => ['page_title_switch', '=', true],
                    'background-color' => false,
                    'preview_media' => true,
                    'preview' => false,
                    'default' => [
                        'background-repeat' => 'repeat',
                        'background-size' => 'cover',
                        'background-attachment' => 'scroll',
                        'background-position' => 'center center',
                        'background-color' => '',
                    ],
                ],
            ]
        ]
    );
}

$advanced_fields = [
    [
        'id' => 'advanced_warning',
        'title' => esc_html__('Attention! This tab stores functionality that can harm site reliability.', 'transmax'),
        'type' => 'info',
        'desc' => esc_html__('Site troublefree operation is not ensured, if any of the following options is changed.', 'transmax'),
        'style' => 'critical',
        'icon' => 'el el-warning-sign',
    ],
    [
        'id' => 'advanced_divider',
        'type' => 'divide'
    ],
    [
        'id' => 'advanced-wp-start',
        'title' => esc_html__('WordPress', 'transmax'),
        'type' => 'section',
        'indent' => true,
    ],
    [
        'id' => 'disable_wp_gutenberg',
        'title' => esc_html__('Gutenberg Stylesheet', 'transmax'),
        'type' => 'switch',
        'desc' => esc_html__('Dequeue CSS files.', 'transmax') . transmax_quick_tip(
            wp_kses(
                __('Eliminates <code>wp-block-library-css</code> stylesheet. <br>Before disabling ensure that Gutenberg editor is not used anywhere throughout the site.', 'transmax'),
                ['br' => [], 'code' => []]
            )
        ),
        'on' => esc_html__('Dequeue', 'transmax'),
        'off' => esc_html__('Default', 'transmax'),
    ],
    [
        'id' => 'wordpress_widgets',
        'title' => esc_html__('WordPress Widgets', 'transmax'),
        'type' => 'switch',
        'on' => esc_html__('Classic', 'transmax'),
        'off' => esc_html__('Gutenberg', 'transmax'),
        'default' => true,
    ],
    [
        'id' => 'advanced-wp-end',
        'type' => 'section',
        'indent' => false,
    ],
];

if (class_exists('Elementor\Plugin')) {
    $advanced_elementor = [
        [
            'id' => 'advanced-elementor-start',
            'title' => esc_html__('Elementor', 'transmax'),
            'type' => 'section',
            'indent' => true,
        ],
        [
            'id' => 'disable_elementor_googlefonts',
            'title' => esc_html__('Google Fonts', 'transmax'),
            'type' => 'switch',
            'desc' => esc_html__('Dequeue font pack.', 'transmax') . transmax_quick_tip(sprintf(
                '%s <a href="%s" target="_blank">%s</a>%s',
                esc_html__('See: ', 'transmax'),
                esc_url('https://docs.elementor.com/article/286-speed-up-a-slow-site'),
                esc_html__('Optimizing a Slow Site w/ Elementor', 'transmax'),
                wp_kses(
                    __('<br>Note: breaks all fonts selected within <code>Group_Control_Typography</code> (if any). Has no affect on <code>Theme Options->Typography</code> fonts.', 'transmax'),
                    ['br' => [], 'code' => []]
                )
            )),
            'on' => esc_html__('Disable', 'transmax'),
            'off' => esc_html__('Default', 'transmax'),
        ],
        [
            'id' => 'disable_elementor_fontawesome',
            'title' => esc_html__('Font Awesome Pack', 'transmax'),
            'type' => 'switch',
            'desc' => esc_html__('Dequeue icon pack.', 'transmax')
                . transmax_quick_tip(esc_html__('Note: Font Awesome is essential for Transmax theme. Disable only if it already enqueued by some other plugin.', 'transmax')),
            'on' => esc_html__('Disable', 'transmax'),
            'off' => esc_html__('Default', 'transmax'),
        ],
        [
            'id' => 'advanced-elelemntor-end',
            'type' => 'section',
            'indent' => false,
        ],
    ];
    array_push($advanced_fields, ...$advanced_elementor);
}

Redux::set_section(
    $theme_slug,
    [
        'id' => 'advanced',
        'title' => esc_html__('Advanced', 'transmax'),
        'icon' => 'el el-warning-sign',
        'fields' => $advanced_fields
    ]
);
