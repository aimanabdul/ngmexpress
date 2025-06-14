<?php
/**
 * Redux_Framework_Plugin main class
 *
 * @package     Redux Framework
 * @since       3.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Framework_Plugin', false ) ) {

	/**
	 * Main Redux_Framework_Plugin class
	 *
	 * @since       3.0.0
	 */
	class Redux_Framework_Plugin {

		/**
		 * Option array for demo mode.
		 *
		 * @access      protected
		 * @var         array $options Array of config options, used to check for demo mode
		 * @since       3.0.0
		 */
		protected $options = array();

		/**
		 * Use this value as the text domain when translating strings from this plugin. It should match
		 * the Text Domain field set in the plugin header, as well as the directory name of the plugin.
		 * Additionally, text domains should only contain letters, number and hypens, not underscores
		 * or spaces.
		 *
		 * @access      protected
		 * @var         string $plugin_slug The unique ID (slug) of this plugin
		 * @since       3.0.0
		 */
		protected $plugin_slug = 'redux-framework';

		/**
		 * Set on network activate.
		 *
		 * @access      protected
		 * @var         string $plugin_network_activated Check for plugin network activation
		 * @since       3.0.0
		 */
		protected $plugin_network_activated = null;

		/**
		 * Class instance.
		 *
		 * @access      private
		 * @var         \Redux_Framework_Plugin $instance The one true Redux_Framework_Plugin
		 * @since       3.0.0
		 */
		private static $instance;

		/**
		 * Crash flag.
		 *
		 * @access      private
		 * @var         \Redux_Framework_Plugin $crash Crash flag if inside a crash.
		 * @since       4.1.15
		 */
		public static $crash = false;

		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       3.1.3
		 * @return      self::$instance The one true Redux_Framework_Plugin
		 */
		public static function instance() {
			$path = REDUX_PLUGIN_FILE;

			if ( function_exists( 'get_plugin_data' ) && file_exists( $path ) ) {
				$data = get_plugin_data( $path );

				if ( isset( $data ) && isset( $data['Version'] ) && '' !== $data['Version'] ) {
					$res = version_compare( $data['Version'], '4', '<' );
				}

				if ( is_plugin_active( 'redux-framework/redux-framework.php' ) && true === $res ) {
					echo '<div class="error"><p>' . esc_html__( 'Redux Framework version 4 is activated but not loaded. Redux Framework version 3 is still installed and activated.  Please deactivate Redux Framework version 3.', 'wgl-extensions' ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
					return;
				}
			}

			if ( ! self::$instance ) {
				self::$instance = new self();
				if ( class_exists( 'ReduxFramework' ) ) {
					self::$instance->load_first();
				} else {
					self::$instance->get_redux_options();
					self::$instance->includes();
					self::$instance->hooks();
				}
			}

			return self::$instance;
		}

		/**
		 * Shim for geting instance
		 *
		 * @access      public
		 * @since       4.0.1
		 * @return      self::$instance The one true Redux_Framework_Plugin
		 */
		public static function get_instance() {
			return self::instance();
		}

		/**
		 * Get Redux options
		 *
		 * @access      public
		 * @since       3.1.3
		 * @return      void
		 */
		public function get_redux_options() {

			// Setup defaults.
			$defaults = array(
				'demo' => false,
			);

			// If multisite is enabled.
			if ( is_multisite() ) {

				// Get network activated plugins.
				$plugins = get_site_option( 'active_sitewide_plugins' );

				foreach ( $plugins as $file => $plugin ) {
					if ( strpos( $file, 'redux-framework.php' ) !== false ) {
						$this->plugin_network_activated = true;
						$this->options                  = get_site_option( 'ReduxFrameworkPlugin', $defaults );
					}
				}
			}

			// If options aren't set, grab them now!
			if ( empty( $this->options ) ) {
				$this->options = get_option( 'ReduxFrameworkPlugin', $defaults );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      public
		 * @since       3.1.3
		 * @return      void
		 */
		public function includes() {

			// Include Redux_Core.
			if ( file_exists( dirname( __FILE__ ) . '/redux-core/framework.php' ) ) {
				require_once dirname( __FILE__ ) . '/redux-core/framework.php';
			}

			if ( file_exists( dirname( __FILE__ ) . '/redux-templates/redux-templates.php' ) ) {
				require_once dirname( __FILE__ ) . '/redux-templates/redux-templates.php';
			}

			if ( isset( Redux_Core::$as_plugin ) ) {
				Redux_Core::$as_plugin = true;
			}

			add_action( 'setup_theme', array( $this, 'load_sample_config' ) );

		}

		/**
		 * Loads the sample config after everything is loaded.
		 *
		 * @access      public
		 * @since       4.0.2
		 * @return      void
		 */
		public function load_sample_config() {
			// Include demo config, if demo mode is active.
			if ( $this->options['demo'] && file_exists( dirname( __FILE__ ) . '/sample/sample-config.php' ) ) {
				require_once dirname( __FILE__ ) . '/sample/sample-config.php';
			}
		}

		/**
		 * Run action and filter hooks
		 *
		 * @access      private
		 * @since       3.1.3
		 * @return      void
		 */
		private function hooks() {
			add_action( 'activated_plugin', array( $this, 'load_first' ) );
			add_action( 'wp_loaded', array( $this, 'options_toggle_check' ) );

			// Activate plugin when new blog is added.
			add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

			// Display admin notices.
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			// Edit plugin metalinks.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );
			add_filter( 'network_admin_plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
			add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/plugin/hooks', $this );
		}

		/**
		 * Pushes Redux to top of plugin load list, so it initializes before any plugin that may use it.
		 */
		public function load_first() {
			if ( ! class_exists( 'Redux_Functions_Ex' ) ) {
				require_once dirname( __FILE__ ) . '/redux-core/inc/classes/class-redux-functions-ex.php';
			}

			$plugin_dir = Redux_Functions_Ex::wp_normalize_path( WP_PLUGIN_DIR ) . '/';
			$self_file  = Redux_Functions_Ex::wp_normalize_path( __FILE__ );

			$path = str_replace( $plugin_dir, '', $self_file );
			$path = str_replace( 'class.redux-plugin.php', 'redux-framework.php', $path );

			$plugins = get_option( 'active_plugins' );

			if ( $plugins ) {
				$key = array_search( $path, $plugins, true );

				if ( false !== $key ) {
					array_splice( $plugins, $key, 1 );
					array_unshift( $plugins, $path );
					update_option( 'active_plugins', $plugins );
				}

				if ( class_exists( 'Redux_Pro' ) ) {
					$self_file = Redux_Functions_Ex::wp_normalize_path( Redux_Pro::$dir );
					$path      = str_replace( $plugin_dir, '', $self_file );

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$basename = apply_filters( 'redux/pro/basename', 'redux-pro.php' );

					$key = array_search( $path . '/' . $basename, $plugins, true );
					if ( false !== $key ) {
						array_splice( $plugins, $key, 1 );
						array_unshift( $plugins, $path . '/' . $basename );
						update_option( 'active_plugins', $plugins );
					}
				}
			}
		}

		/**
		 * Fired on plugin activation
		 *
		 * @access      public
		 * @since       3.0.0
		 *
		 * @param       boolean $network_wide True if plugin is network activated, false otherwise.
		 *
		 * @return      void
		 */
		public static function activate( $network_wide ) {
			// phpcs:disable
			//if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			//	if ( $network_wide ) {
			//		// Get all blog IDs.
			//		$blog_ids = self::get_blog_ids();
			//
			//		foreach ( $blog_ids as $blog_id ) {
			//			switch_to_blog( $blog_id );
			//			self::single_activate();
			//		}
			//		restore_current_blog();
			//	} else {
			//		self::single_activate();
			//	}
			//} else {
			//	self::single_activate();
			//}
			// phpcs:enable

			delete_site_transient( 'update_plugins' );
		}

		/**
		 * Fired when plugin is deactivated
		 *
		 * @access      public
		 * @since       3.0.0
		 *
		 * @param       boolean $network_wide True if plugin is network activated, false otherwise.
		 *
		 * @return      void
		 */
		public static function deactivate( $network_wide ) {
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $network_wide ) {
					// Get all blog IDs.
					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {
						switch_to_blog( $blog_id );
						self::single_deactivate();
					}
					restore_current_blog();
				} else {
					self::single_deactivate();
				}
			} else {
				self::single_deactivate();
			}

			delete_option( 'ReduxFrameworkPlugin' );
			Redux_Enable_Gutenberg::cleanup_options( 'wgl-extensions' ); // Auto disable Gutenberg and all that.
		}

		/**
		 * Fired when a new WPMU site is activated
		 *
		 * @access      public
		 * @since       3.0.0
		 *
		 * @param       int $blog_id The ID of the new blog.
		 *
		 * @return      void
		 */
		public function activate_new_site( $blog_id ) {
			if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
				return;
			}

			switch_to_blog( $blog_id );
			self::single_activate();
			restore_current_blog();
		}

		/**
		 * Get all IDs of blogs that are not activated, not spam, and not deleted
		 *
		 * @access      private
		 * @since       3.0.0
		 * @global      object $wpdb
		 * @return      array|false Array of IDs or false if none are found
		 */
		private static function get_blog_ids() {
			global $wpdb;

			$var = '0';

			// Get an array of IDs (We have to do it this way because WordPress ays so, however reduntant.
			$result = wp_cache_get( 'redux-blog-ids' );
			if ( false === $result ) {

				// WordPress asys get_col is discouraged?  I found no alternative.  So...ignore! - kp.
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$result = $wpdb->get_col( $wpdb->prepare( "SELECT blog_id FROM $wpdb->blogs WHERE archived = %s AND spam = %s AND deleted = %s", $var, $var, $var ) );

				wp_cache_set( 'redux-blog-ids', $result );
			}

			return $result;
		}

		/**
		 * Fired for each WPMS blog on plugin activation
		 *
		 * @access      private
		 * @since       3.0.0
		 * @return      void
		 */
		private static function single_activate() {
			$notices = array();

			$nonce = wp_create_nonce( 'redux_framework_demo' );

			$notices   = get_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', array() );
			$notices[] = esc_html__( 'Redux Framework has an embedded demo.', 'wgl-extensions' ) . ' <a href="./plugins.php?redux-framework-plugin=demo&nonce=' . $nonce . '">' . esc_html__( 'Click here to activate the sample config file.', 'wgl-extensions' ) . '</a>';

			update_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', $notices );
		}

		/**
		 * Display admin notices
		 *
		 * @access      public
		 * @since       3.0.0
		 * @return      void
		 */
		public function admin_notices() {
			do_action( 'redux_framework_plugin_admin_notice' );
			$notices = get_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', '' );
			if ( ! empty( $notices ) ) {
				foreach ( $notices as $notice ) {
					echo '<div class="updated notice is-dismissible"><p>' . $notice . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				}

				delete_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES' );
			}
		}

		/**
		 * Fired for each blog when the plugin is deactivated
		 *
		 * @access      private
		 * @since       3.0.0
		 * @return      void
		 */
		private static function single_deactivate() {
			delete_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES' );
		}

		/**
		 * Turn on or off
		 *
		 * @access      public
		 * @since       3.0.0
		 * @global      string $pagenow The current page being displayed
		 * @return      void
		 */
		public function options_toggle_check() {
			global $pagenow;

			if ( isset( $_GET['nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'redux_framework_demo' ) ) {
				if ( isset( $_GET['redux-framework-plugin'] ) && 'demo' === $_GET['redux-framework-plugin'] ) {
					$url = admin_url( add_query_arg( array( 'page' => 'wgl-extensions' ), 'tools.php' ) );

					if ( 'demo' === $_GET['redux-framework-plugin'] ) {
						if ( false === $this->options['demo'] ) {
							$this->options['demo'] = true;
							$url                   = admin_url( add_query_arg( array( 'page' => 'redux_demo' ), 'admin.php' ) );
						} else {
							$this->options['demo'] = false;

						}
					}
					if ( is_multisite() && $this->plugin_network_activated ) {
						update_site_option( 'ReduxFrameworkPlugin', $this->options );
					} else {
						update_option( 'ReduxFrameworkPlugin', $this->options );
					}

					wp_safe_redirect( esc_url( $url ) );

					exit();
				}
			}
		}


		/**
		 * Add a settings link to the Redux entry in the plugin overview screen
		 *
		 * @param array  $links Links array.
		 * @param string $file Plugin filename/slug.
		 *
		 * @return array
		 * @see   filter:plugin_action_links
		 * @since 1.0
		 */
		public function add_settings_link( $links, $file ) {

			if ( strpos( REDUX_PLUGIN_FILE, $file ) === false ) {
				return $links;
			}

			if ( ! class_exists( 'Redux_Pro' ) ) {
				$links[] = sprintf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url( $this->get_site_utm_url( '', 'upgrade' ) ),
					sprintf(
						'<span style="font-weight: bold;">%s</span>',
						__( 'Go Pro', 'wgl-extensions' )
					)
				);
			}

			return $links;
		}

		/**
		 * Get the url where the Admin Columns website is hosted
		 *
		 * @param string $path Path to add to url.
		 *
		 * @return string
		 */
		private function get_site_url( $path = '' ) {
			$url = 'https://redux.io';

			if ( ! empty( $path ) ) {
				$url .= '/' . trim( $path, '/' ) . '/';
			}

			return $url;
		}

		/**
		 * Url with utm tags
		 *
		 * @param string $path Path on site.
		 * @param string $utm_medium Medium var.
		 * @param string $utm_content Content var.
		 * @param bool   $utm_campaign Campaign var.
		 *
		 * @return string
		 */
		public function get_site_utm_url( $path, $utm_medium, $utm_content = null, $utm_campaign = false ) {
			$url = self::get_site_url( $path );

			if ( ! $utm_campaign ) {
				$utm_campaign = 'plugin-installation';
			}

			$args = array(
				// Referrer: plugin.
				'utm_source'   => 'plugin-installation',

				// Specific promotions or sales.
				'utm_campaign' => $utm_campaign,

				// Marketing medium: banner, documentation or email.
				'utm_medium'   => $utm_medium,

				// Used for differentiation of medium.
				'utm_content'  => $utm_content,
			);

			$args = array_map( 'sanitize_key', array_filter( $args ) );

			return add_query_arg( $args, $url );
		}

		/**
		 * Edit plugin metalinks
		 *
		 * @access      public
		 * @since       3.0.0
		 *
		 * @param       array  $links The current array of links.
		 * @param       string $file  A specific plugin row.
		 *
		 * @return      array The modified array of links
		 */
		public function plugin_metalinks( $links, $file ) {
			if ( strpos( $file, 'redux-framework.php' ) !== false && is_plugin_active( $file ) ) {
				$links[] = '<a href="' . esc_url( admin_url( add_query_arg( array( 'page' => 'wgl-extensions' ), 'tools.php' ) ) ) . '">' . esc_html__( 'What is this?', 'wgl-extensions' ) . '</a>';
				$links[] = '<a href="' . esc_url( admin_url( add_query_arg( array( 'post_type' => 'page' ), 'post-new.php' ) ) ) . '#redux_templates=1">' . esc_html__( 'Template Library', 'wgl-extensions' ) . '</a>';
			}

			return $links;
		}
	}
	if ( ! class_exists( 'ReduxFrameworkPlugin' ) ) {
		class_alias( 'Redux_Framework_Plugin', 'ReduxFrameworkPlugin' );
	}
}
