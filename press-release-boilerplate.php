<?php
/**
 *  Plugin Name: Press Release Boilerplate
 *  Author: Nic Scott
 *  Version: 0.1
 *  Description: Easily create boilerplate text content to add to the end of posts.
 *
 */



class PressReleaseBoilerplate {
    
    /**
     *  Instance
     */
    
    public static $instance;
    
    public $post_type = 'pr_boilerplate';
    
    
    public function __construct() {
        
        $this->init();
        
        //Insert into end of post content
        add_filter( 'the_content', [ $this, 'insert_boilerplate' ] );
        
    }
    
    
    
    public function init() {
        
        add_action( 'init', [ $this, 'register_post_type' ] );
        
        
        
    }
    
    
    
    
    
    public function get_boilerplates( $cats ) {
        
        $bps = get_posts( [
            'post_type' => $this->post_type,
            'posts_per_page' => -1,
            'category__in' => $cats,
        ]);
        
        
        return $bps;
        
        
    }
    
    
    
    
    
    public function insert_boilerplate( $the_content ) {
        
        global $post;
        
        //Get the boilerplates that correspond to the current post
        $current_cats = get_the_category();
        
       // var_dump( $current_cats );
        
        if ( !empty( $current_cats ) && is_array( $current_cats ) ) {
            
            $cats = [];


            foreach( $current_cats as $cat ) {

                $cats[] = $cat->term_id;

            }
            
        }
        
        
        
        $boilerplates = $this->get_boilerplates( $cats );
        
        
        if ( !empty( $boilerplates ) ) {
            
            ob_start();
            
            foreach( $boilerplates as $post ) {

                setup_postdata( $post );
                
                include __DIR__ . '/template-parts/boilerplate.php';

            }
            
            wp_reset_postdata();
            
            $the_content .= ob_get_clean();

        }
        
        
        return $the_content;
    }
    
    
    
    
    
    
    
    
    
    public function register_post_type() {
        
        $args = [
            'label'  => esc_html__( 'PR Boilerplates', 'text-domain' ),
            'labels' => [
                'menu_name'          => esc_html__( 'PR Boilerplates', '' ),
                'name_admin_bar'     => esc_html__( 'PR Boilerplate', '' ),
                'add_new'            => esc_html__( 'Add PR Boilerplate', '' ),
                'add_new_item'       => esc_html__( 'Add new PR Boilerplate', '' ),
                'new_item'           => esc_html__( 'New PR Boilerplate', '' ),
                'edit_item'          => esc_html__( 'Edit PR Boilerplate', '' ),
                'view_item'          => esc_html__( 'View PR Boilerplate', '' ),
                'update_item'        => esc_html__( 'View PR Boilerplate', '' ),
                'all_items'          => esc_html__( 'PR Boilerplate', '' ),
                'search_items'       => esc_html__( 'Search PR Boilerplates', '' ),
                'parent_item_colon'  => esc_html__( 'Parent PR Boilerplate', '' ),
                'not_found'          => esc_html__( 'No PR Boilerplates found', '' ),
                'not_found_in_trash' => esc_html__( 'No PR Boilerplates found in Trash', '' ),
                'name'               => esc_html__( 'PR Boilerplates', '' ),
                'singular_name'      => esc_html__( 'PR Boilerplate', '' ),
            ],
            'public'              => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => false,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'has_archive'         => true,
            'query_var'           => false,
            'can_export'          => true,
            'rewrite_no_front'    => false,
            'show_in_menu'        => 'edit.php',
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-media-document',
            'supports' => [
                'title',
                'editor',
                'revisions',
            ],

            'rewrite' => false,
            'taxonomies' => [
                'category',
            ],
        ];

        register_post_type( $this->post_type, $args );
    }



    
    
    
    
    /**
     *  Get Instance
     */
    
    public static function get_instance() {
        
        if ( self::$instance == null ) {
            
            self::$instance = new self;
            
        }
        
        return self::$instance;
    }
    
    
    
}



function PressReleaseBoilerplate() {
    
    return PressReleaseBoilerplate::get_instance();

}


PressReleaseBoilerplate();