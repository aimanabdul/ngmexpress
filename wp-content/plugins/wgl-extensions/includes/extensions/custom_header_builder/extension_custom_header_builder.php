<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys (dovy)
 * @version     3.0.0
 */

// Exit if accessed directly

if( !defined( 'ABSPATH' ) ) exit;
// Don't duplicate me!

if( !class_exists( 'ReduxFramework_extension_custom_header_builder' ) ) {
    /**
     * Main ReduxFramework custom_header_builder extension class
     *
     * @since       3.1.6
     */

    class ReduxFramework_extension_custom_header_builder {

        // Set the version number of your extension here
        public static $version       = '1.0.0';
        // Set the name of your extension here
        public $ext_name             = 'Custom Header Builder';

        // Set the minumum required version of Redux here (optional).
        // Leave blank to require no minimum version.
        // This allows you to specify a minimum required version of Redux in the event
        // you do not want to support older versions.
        public $min_redux_version    = '3.0.0';
        // Protected vars
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;

        /**
        * Class Constructor. Defines the args for the extions class
        *
        * @since       1.0.0
        * @access      public
        * @param       array $sections Panel sections.
        * @param       array $args Class constructor arguments.
        * @param       array $extra_tabs Extra panel tabs.
        * @return      void
        */

        public function __construct( $parent ) {

            $this->parent = $parent;

            if (is_admin() && !$this->is_minimum_version()) {
                return;
            }

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $dir = Redux_Helpers::cleanFilePath( dirname( __FILE__ ) );
                $_dir = trailingslashit( $dir );

                $wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) ) );

                $wp_content_dir = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
                $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
                $relative_url   = str_replace( $wp_content_dir, '', $_dir );
                /* Fix bitnami url */
                $relative_url   = str_replace("/bitnami/wordpress/wp-content", "", $relative_url);
                $this->extension_url     = trailingslashit( $wp_content_url . $relative_url );
            }

            $this->field_name = 'custom_header_builder';
            self::$theInstance = $this;
            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'.$this->field_name, array( &$this, 'overload_field_path' ) ); // Adds the local field
        }
        public function getInstance() {
            return self::$theInstance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path($field) {
            return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }

        private function is_minimum_version () {
            $redux_ver = ReduxFramework::$_version;
            if ($this->min_redux_version != '') {
                if (version_compare($redux_ver, $this->min_redux_version) < 0) {
                    $msg = '<strong>' . esc_html__( 'The', 'wgl-extensions') . ' ' .  $this->ext_name . ' ' .  esc_html__('extension requires', 'wgl-extensions') . ' Redux Framework ' . esc_html__('version', 'wgl-extensions') . ' ' . $this->min_redux_version . ' ' .  esc_html__('or higher.','wgl-extensions' ) . '</strong>&nbsp;&nbsp;' . esc_html__( 'You are currently running', 'wgl-extensions') . ' Redux Framework ' . esc_html__('version','wgl-extensions' ) . ' ' . $redux_ver . '.<br/><br/>' . esc_html__('This field will not render in your option panel, and featuress of this extension will not be available until the latest version of','wgl-extensions' ) . ' Redux Framework ' . esc_html__('has been installed.','wgl-extensions' );

                    $data = array(
                        'parent'    => $this->parent,
                        'type'      => 'error',
                        'msg'       => $msg,
                        'id'        => $this->ext_name . '_notice_' . self::$version,
                        'dismiss'   => false
                    );

                    if (method_exists('Redux_Admin_Notices', 'set_notice')) {
                        Redux_Admin_Notices::set_notice($data);
                    } else {
                        echo '<div class="error">';
                        echo     '<p>';
                        echo         $msg;
                        echo     '</p>';
                        echo '</div>';
                    }
                    return false;
                }
            }

            return true;
        }
    } // class
} // if