<?php

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

/**
 * Footer CPT
 *
 * @package transmax-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Footer
{
    /**
     * @var string
     *
     * Set post type params
     */
    private $type = 'footer';
    private $slug;
    private $singular_name;
    private $plural_name;

    public function __construct()
    {
        $this->slug = 'footer';
        $this->singular_name = esc_html__( 'Footer', 'transmax-core' );
        $this->plural_name = esc_html__( 'Footers', 'transmax-core' );

        add_action( 'init', [ $this, 'register_cpt' ] );
        add_action( 'template_redirect', [ $this, 'restrict_ui' ] );

        add_filter( 'single_template', [ $this, 'get_custom_pt_single_template' ] );
    }

    public function register_cpt()
    {
        $labels = [
            'name' => $this->singular_name,
            'singular_name' => $this->singular_name,
            'add_new' => sprintf( esc_html__( 'Add New %s', 'transmax-core' ), $this->singular_name ),
            'add_new_item' => sprintf( esc_html__( 'Add New %s', 'transmax-core' ), $this->singular_name ),
            'edit_item' => sprintf( esc_html__( 'Edit %s', 'transmax-core' ), $this->singular_name ),
            'new_item' => sprintf( esc_html__( 'New %s', 'transmax-core' ), $this->singular_name ),
            'all_items' => sprintf( esc_html__( 'All %s', 'transmax-core' ), $this->plural_name ),
            'view_item' => sprintf( esc_html__( 'View %s', 'transmax-core' ), $this->singular_name ),
            'search_items' => sprintf( esc_html__( 'Search %s', 'transmax-core' ), $this->plural_name ),
            'not_found' => sprintf( esc_html__( 'No %s found' , 'transmax-core' ), strtolower( $this->plural_name ) ),
            'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash', 'transmax-core' ), strtolower( $this->plural_name ) ),
            'parent_item_colon' => '',
            'menu_name' => $this->singular_name
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'rewrite' => false,
            'menu_position' => 13,
            'supports' => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
            'menu_icon' => 'dashicons-admin-page',
        ];

        register_post_type( $this->type, $args );
    }

    function restrict_ui()
    {
        if (
            is_singular( $this->type )
            && ! current_user_can( 'edit_posts' )
        ) {
            wp_safe_redirect( site_url(), 301 );
            die;
        }
    }

    public function wrapper_footer_open()
    {
        global $post;

        if ( $post->post_type == $this->type ) {
            echo '<footer class="footer clearfix" id="footer">';
                echo '<div class="footer_top-area">';
                    echo '<div class="wgl-container">';
                        echo '<div class="row-footer">';
        }
    }

    public function wrapper_footer_close()
    {
        global $post;

        if ( $post->post_type == $this->type ) {
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</footer>';
        }
    }

    /** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template */
    function get_custom_pt_single_template( $single_template )
    {
        global $post;

        if ( $post->post_type == $this->type ) {

            if ( defined( 'ELEMENTOR_PATH' ) ) {
                $elementor_template = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

                if ( file_exists( $elementor_template ) ) {
                    add_action( 'elementor/page_templates/canvas/before_content', [ $this, 'wrapper_footer_open' ] );
                    add_action( 'elementor/page_templates/canvas/after_content', [ $this, 'wrapper_footer_close' ] );
                    return $elementor_template;
                }
            }

            if ( file_exists( get_template_directory() . '/single-footer.php' ) ) {
                return $single_template;
            }

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'footer/templates/single-footer.php';
        }

        return $single_template;
    }
}
