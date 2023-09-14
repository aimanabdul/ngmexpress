<?php

define('WGL_ELEMENTOR_MODULE_URL', plugins_url('/', __FILE__));
define('WGL_ELEMENTOR_MODULE_PATH', plugin_dir_path(__FILE__));
define('WGL_ELEMENTOR_MODULE_FILE', __FILE__);

use Elementor\Plugin;
use WGL_Extensions\WGL_Framework_Global_Variables as WGL_Globals;

if (!class_exists('WGL_Elementor_Module')) {
    /**
     * WGL Elementor Module
     *
     *
     * @package transmax-core\includes\elementor
     * @author WebGeniusLab <webgeniuslab@gmail.com>
     * @since 1.0.0
     */
    class WGL_Elementor_Module
    {
        /**
         * @var string The defualt path to elementor dir on this plugin.
         */
        private $dir_path;

        private static $instance;

        public function __construct()
        {
            $this->dir_path = plugin_dir_path(__FILE__);

            add_action('plugins_loaded', [$this, 'elementor_setup']);

            add_action('elementor/init', [$this, 'optimize_elementor'], 5);
            add_action('elementor/init', [$this, 'inject_wgl_categories']);

            add_filter('elementor/widgets/wordpress/widget_args', [$this, 'wgl_widget_args'], 10, 1); // WPCS: spelling ok.

            add_filter('wgl_flaticon_icons', [$this, 'theme_icons']);
        }

        /**
         * Eliminate redundant functionality
         * and speed up website load
         */
        public function optimize_elementor()
        {
            if (!class_exists('\WGL_Framework')) {
                return;
            }

            if (WGL_Framework::get_option('disable_elementor_googlefonts')) {
                /**
                 * Disable Google Fonts
                 * Note: breaks all fonts selected within `Group_Control_Typography` (if any).
                 */
                add_filter('elementor/frontend/print_google_fonts', '__return_false');
            }

            if (WGL_Framework::get_option('disable_elementor_fontawesome')) {
                /** Disable Font Awesome pack */
                add_action('elementor/frontend/after_register_styles', function () {
                    foreach (['solid', 'regular', 'brands'] as $style) {
                        wp_deregister_style('elementor-icons-fa-' . $style);
                    }
                }, 20);
            }
        }

        public function elementor_setup()
        {
            /**
             * Check if Elementor installed and activated
             * @see https://developers.elementor.com/creating-an-extension-for-elementor/
             */
            if (!did_action('elementor/loaded')) {
                return;
            }

            $this->requiere_all_files('includes');
            $this->requiere_all_files('templates');
            $this->init_addons();
        }

        /**
         * Load required file for addons integration
         */
        public function init_addons()
        {
            add_action('elementor/widgets/register', [$this, 'widgets_area']);

            add_action('elementor/frontend/after_register_scripts', [$this, 'frontend_scripts_registration']);
            add_action('elementor/editor/before_enqueue_scripts', [$this, 'admin_styles_enqueuing']);

            add_action( 'init', [ $this, 'add_wpml_support' ] );

            $this->init_all_modules();
        }

        public function init_all_modules()
        {
            foreach (glob($this->dir_path . 'modules/' . '*.php') as $file_name) {
                $base = basename(str_replace('.php', '', $file_name));
                $class = ucwords(str_replace('-', ' ', $base));
                $class = str_replace(' ', '_', $class);
                $class = sprintf('WGL_Extensions\Modules\%s', $class);

                // Class File
                require_once $file_name;

                if (class_exists($class)) {
                    new $class();
                }
            }
        }

        /**
         * Register addon by file name.
         */
        public function register_controls_addon($file_name)
        {
            $controls_manager = Plugin::$instance->controls_manager;

            $base = basename(str_replace('.php', '', $file_name));
            $class = ucwords(str_replace('-', ' ', $base));
            $class = str_replace(' ', '_', $class);
            $class = sprintf('WGL_Extensions\Controls\%s', $class);

            // Class Constructor File
            require_once $file_name;

            if (class_exists($class)) {
                $controls_manager->register(new $class);
            }
        }

        /**
         * Load widgets require function
         */
        public function widgets_area()
        {
            $this->requiere_all_files('widgets');
            $this->requiere_all_files('header');
        }

        private function requiere_all_files($require_file = 'widgets', $wpml_translate = false)
        {
            $template_names = [];
            $template_path = '/wgl-extensions/elementor/'.$require_file.'/';
            $plugin_template_path = $this->dir_path . $require_file.'/';
            $ext_template_path = WGL_EXTENSIONS_ELEMENTOR_PATH . $require_file.'/';

            foreach (glob($ext_template_path . '*.php') as $file) {
                $template_name = basename($file);
                array_push($template_names, $template_name);
            }

            foreach (glob($plugin_template_path . '*.php') as $file) {
                $template_name = basename($file);
                array_push($template_names, $template_name);
            }

            $files = wgl_extensions_global()->get_locate_template(
                $template_names,
                '/elementor/' . $require_file . '/',
                $template_path,
                realpath(__DIR__ . '/..')
            );

            switch ($require_file) {
                case 'templates':
                case 'includes':
                    foreach ((array) $files as $file) {
                        require_once $file;
                    }
                    break;
                case 'header':
                case 'widgets':
                    foreach ((array) $files as $file_name) {
                        $this->register_wgl_widget($file_name, $wpml_translate);
                    }
                    break;
            }
        }

        public function register_wgl_widget($file_name = '', $wpml_translate = false)
        {
            $widget_manager = Plugin::instance()->widgets_manager;

            $base = basename(str_replace('.php', '', $file_name));
            $class = ucwords(str_replace('-', ' ', $base));
            $class = str_replace('Wgl', 'WGL', $class);
            $class = str_replace(' ', '_', $class);
            $class = sprintf('WGL_Extensions\Widgets\%s', $class);

            if ($this->unvalid_widget_registration($class)) {
                // Bailout.
                return;
            }

            require_once $file_name;

            if (class_exists($class)) {
                if(!$wpml_translate){
                    $widget_manager->register( new $class );
                }else{
                    $widget = new $class();
                    if(method_exists(new $class(), 'wpml_support_module')){
                        $widget->wpml_support_module();
                    }
                }
            }
        }

        private function unvalid_widget_registration($class)
        {
            if (
                'WGL_Extensions\Widgets\WGL_Header_Wpml' === $class
                && !class_exists('\SitePress')
            ) {
                return true;
            }

            if (!class_exists('\WooCommerce')) {
                if (
                    'WGL_Extensions\Widgets\WGL_Header_Cart' === $class
                    || 'WGL_Extensions\Widgets\WGL_Header_login' === $class
                    || 'WGL_Extensions\Widgets\WGL_Products_Grid' === $class
                ) {
                    return true;
                }
            }

            if (!class_exists('WPCF7_ContactForm')) {
                if (
                    'WGL_Extensions\Widgets\WGL_Contact_Form_7' === $class
                ) {
                    return true;
                }
            }

            return false; // registration can be continued
        }

        public function frontend_scripts_registration()
        {
            wp_register_script(
                'wgl-widgets',
                WGL_ELEMENTOR_MODULE_URL . '/assets/js/wgl_elementor_widgets.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_register_script(
                'isotope',
                WGL_EXTENSIONS_ELEMENTOR_URL . 'assets/js/isotope.pkgd.min.js',
                ['jquery'],
                '1.0.0',
                true
            );

	        wp_register_script(
		        'jquery-easypiechart',
		        get_template_directory_uri() . '/js/jquery.easypiechart.min.js',
		        ['jquery'],
		        '2.1.7',
		        true
	        );

            wp_register_script(
                'jquery-appear',
                get_template_directory_uri() . '/js/jquery.appear.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_register_script(
                'jarallax',
                get_template_directory_uri() . '/js/jarallax.min.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_register_script(
                'jarallax-video',
                get_template_directory_uri() . '/js/jarallax-video.min.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_register_script(
                'jquery-countdown',
                get_template_directory_uri() . '/js/jquery.countdown.min.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_register_script(
                'cocoen',
                get_template_directory_uri() . '/js/cocoen.min.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_register_script(
                'jquery-justifiedGallery',
                get_template_directory_uri() . '/js/jquery.justifiedGallery.min.js',
                ['jquery'],
                '1.0.0',
                true
            );
        }

        public function inject_wgl_categories()
        {
            $elements_manager = Plugin::instance()->elements_manager;

            $elements_manager->add_category(
                'wgl-modules',
                ['title' => esc_html__('WGL Modules', 'transmax-core')]
            );

            $elements_manager->add_category(
                'wgl-header-modules',
                ['title' => esc_html__('WGL Header Modules', 'transmax-core')]
            );
        }

        public function admin_styles_enqueuing()
        {
            wp_enqueue_style(
                WGL_Globals::get_theme_slug() . '-flaticon',
                get_template_directory_uri() . '/fonts/flaticon/flaticon.css',
                [],
                WGL_Globals::get_theme_version()
            );
        }

        public function wgl_widget_args($params)
        {
            // Default wrapper for widget and title
            $id = str_replace('wp-', '', $params['widget_id']);
            $id = str_replace('-', '_', $id);

            $wrapper_before = '<div class="wgl-elementor-widget widget '. WGL_Globals::get_theme_slug() . '_widget ' . esc_attr($id) . '">';
            $wrapper_after = '</div>';
            $title_before = '<div class="title-wrapper"><span class="title">';
            $title_after = '</span></div>';

            $default_widget_args = [
                'id' => 'sidebar_' . esc_attr(strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $params['widget_id'])))),
                'before_widget' => $wrapper_before,
                'after_widget' => $wrapper_after,
                'before_title' => $title_before,
                'after_title' => $title_after,
            ];

            return $default_widget_args;
        }

        public function theme_icons()
        {
            return [
                'search',
                'search-1',
                'delivery',
                'box',
                'transportation',
                'plane',
                'goods',
                'settings',
                'global',
                'placeholder',
                'trolley',
                'call',
                'levels',
                'dollar',
                'clock',
                'tick',
                'done',
                'play-button',
                'right-arrow',
                'left-arrow-1',
                'arrow-down-sign-to-navigate',
                'next',
                'left-arrow-2',
                'straight-quotes',
                'heart-simple-shape-silhouette',
                'heart',
                'heart-1',
                'loupe',
                'search-interface-symbol',
                'magnifiying-glass',
                'shopping-bags',
                'delivered',
                'package',
                'cart',
                'paper-plane',
                'shipping',
                'sharing',
                'placeholder-1',
                'add',
                'plus',
                'plus-1',
                'plus-2',
                'add-1',
                'bar-chart',
                'balance',
                'pie-chart',
                'delivery-1',
                'light-bulb',
                'location',
                'archive',
                'truck',
                'plus-3',
                'close',
                'close-1',
                'check',
                'remove',
                'info',
                'warning',
                'star',
                'home',
                'clipboard',
                'filter',
                'copy',
                'goal',
                'pencil',
                'check-1',
                'close-2',
                'link',
            ];
        }

        public function add_wpml_support() {
            if(class_exists('\SitePress')){
                $this->requiere_all_files('widgets', true);
                $this->requiere_all_files('header', true);
            }
        }

        /**
         * Creates and returns an instance of the class
         *
         * @return object
         */
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }
    }
}

if (!function_exists('wgl_elementor_module')) {
    function wgl_elementor_module()
    {
        return WGL_Elementor_Module::get_instance();
    }

    wgl_elementor_module();
}
