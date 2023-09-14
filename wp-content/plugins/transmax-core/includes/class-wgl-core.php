<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 *
 * @link https://themeforest.net/user/webgeniuslab
 *
 * @package transmax-core\includes
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Transmax_Core
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since 1.0.0
     * @access protected
     * @var Transmax_Core_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since 1.0.0
     * @access protected
     * @var string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since 1.0.0
     * @access protected
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Custom Fonts
     *
     * @since 1.0.0
     * @var string string of CSS rules
     */
    public $font_css;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (defined('WGL_CORE_VERSION')) {
            $this->version = WGL_CORE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'transmax-core';

        $this->load_dependencies();
        $this->define_cpt_hooks();

	    add_action('wgl/widgets_require', [$this, 'get_widgets_locate_template']);
        $this->set_locale();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Transmax_Core_Loader. Orchestrates the hooks of the plugin.
     * - Transmax_Core_i18n. Defines internationalization functionality.
     * - Transmax_Core_Admin. Defines all hooks for the admin area.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-core-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wgl-core-i18n.php';

        /**
         * Redux Framework Loader
         * @see https://github.com/reduxframework/redux-extensions-loader
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wbc_importer/redux-importer-config.php';

        /**
         * WGL Likes
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl-likes/wgl-extensions-likes.php';

        /**
         * WGL Social Shares
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/wgl-social/wgl-extensions-social.php';

        /**
         * WGL Post types register
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/post-types/post-types-register.php';

        /**
         * Include Elementor Extensions.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/elementor/init.php';

        $this->loader = new Transmax_Core_Loader();
    }

    public function get_widgets_locate_template()
    {
        $template_names = [];
        $template_path = '/wgl-extensions/widgets/templates/';
        $plugin_template_path = plugin_dir_path(dirname(__FILE__))  . 'includes/widgets/templates/';
        $ext_template_path = wgl_extensions_global()->get_ext_dir_path() . 'includes/widgets/templates/';

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
            '/widgets/templates/',
            $template_path,
            realpath(__DIR__ . '/..') . '/includes'
        );

        foreach ((array) $files as $file) {
            require_once $file;
        }
    }

    /**
     * Register 'custom' post type.
     */
    private function define_cpt_hooks()
    {
        $plugin_cpt = WGLPostTypesRegister::getInstance();
        // Add post type.
        $this->loader->add_action('after_setup_theme', $plugin_cpt, 'init');
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Transmax_Core_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function set_locale()
    {
        $plugin_i18n = new Transmax_Core_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since 1.0.0
     * @return string The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since 1.0.0
     * @return Transmax_Core_Loader Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since 1.0.0
     * @return string The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
