<?php

defined( 'ABSPATH' ) || exit; // Abort, if called directly.

/**
 * Team CPT
 *
 *
 * @package transmax-core\includes\post-types
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class Team
{
    private $type = 'team';
    private $slug;
    private $name;
    private $singular_name;
    private $plural_name;

    public function __construct()
    {
        $this->name = esc_html__( 'Team', 'transmax-core' );
        $this->singular_name = esc_html__( 'Team Member', 'transmax-core' );
        $this->plural_name = esc_html__( 'Team Members', 'transmax-core' );

        $this->slug = WGL_Framework::get_option( 'team_slug' ) ?: 'team';

        add_action( 'init', [ $this, 'register_taxonomy_category' ] );
        add_action( 'init', [ $this, 'register_cpt' ] );

        add_filter( 'single_template', [ $this, 'get_cpt_single_template' ] );
        add_filter( 'archive_template', [ $this, 'get_cpt_archive_template' ] );
    }

    public function register_cpt()
    {
        $labels = [
            'name' => $this->name,
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
            'menu_name' => $this->name
        ];

        $team_singular = (bool) WGL_Framework::get_option( 'team_singular' );
        $team_archive = (bool) WGL_Framework::get_option( 'team_archives' );

        $args = [
            'labels' => $labels,
            'public' => $team_singular,
            'query_var' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'rewrite' => [ 'slug' => $this->slug ],
            'capability_type' => 'post',
            'menu_position' => 14,
            'menu_icon' => 'dashicons-groups',
            'supports' => [
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'page-attributes',
            ],
            'taxonomies' => [
                $this->type . '_category',
            ],
            'has_archive' => $team_archive,
        ];

        register_post_type( $this->type, $args );
    }

    public function register_taxonomy_category()
    {
        $labels = [
            'name' => sprintf( esc_html__( '%s Categories', 'transmax-core' ), $this->name ),
            'menu_name' => sprintf( esc_html__( '%s Categories', 'transmax-core' ), $this->name ),
            'singular_name' => sprintf( esc_html__( '%s Category', 'transmax-core' ), $this->name ),
            'search_items' =>  sprintf( esc_html__( 'Search %s Categories', 'transmax-core' ), $this->name ),
            'all_items' => sprintf( esc_html__( 'All %s Categories', 'transmax-core' ), $this->name ),
            'parent_item' => sprintf( esc_html__( 'Parent %s Category', 'transmax-core' ), $this->name ),
            'parent_item_colon' => sprintf( esc_html__( 'Parent %s Category:', 'transmax-core' ), $this->name ),
            'new_item_name' => sprintf( esc_html__( 'New %s Category Name', 'transmax-core' ), $this->name ),
            'add_new_item' => sprintf( esc_html__( 'Add New %s Category', 'transmax-core' ), $this->name ),
            'edit_item' => sprintf( esc_html__( 'Edit %s Category', 'transmax-core' ), $this->name ),
            'update_item' => sprintf( esc_html__( 'Update %s Category', 'transmax-core' ), $this->name ),
        ];

        $args = [
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => [ 'slug' => $this->slug . '-category' ],
        ];

        register_taxonomy( $this->type . '_category', [ $this->type ], $args );
    }

    /** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template */
    function get_cpt_single_template( $single_template )
    {
        global $post;

        if ( $post->post_type == $this->type ) {
            if ( file_exists( get_template_directory() . '/single-team.php' ) ) {
                return $single_template;
            }

            $single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'team/templates/single-team.php';
        }

        return $single_template;
    }

    /** @see https://codex.wordpress.org/Plugin_API/Filter_Reference/archive_template */
    function get_cpt_archive_template( $archive_template )
    {
        if ( is_post_type_archive( $this->type ) ) {
            if ( file_exists( get_template_directory() . '/archive-team.php' ) ) {
                return $archive_template;
            }

            $archive_template = plugin_dir_path( dirname( __FILE__ ) ) . 'team/templates/archive-team.php';
        }

        return $archive_template;
    }
}
