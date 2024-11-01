<?php
// video post type
add_action( 'init', 'wtgvb_register_customposttype_videos', 0 );
add_action( 'init', 'wtgvb_register_taxonomy_videosource', 0 );
add_filter( 'template_include', 'wtgvb_videos_template', 0 );

// Register Custom Post Type - individual videos
function wtgvb_register_customposttype_videos() {

    $labels = array(
        'name'                => 'Videos',
        'singular_name'       => 'Video',
        'menu_name'           => 'V.B. Videos',
        'parent_item_colon'   => 'Parent Video:',
        'all_items'           => 'All Videos',
        'view_item'           => 'View Video',
        'add_new_item'        => 'Add New Video',
        'add_new'             => 'New Video',
        'edit_item'           => 'Edit Video',
        'update_item'         => 'Update Video',
        'search_items'        => 'Search videos',
        'not_found'           => 'No videos found',
        'not_found_in_trash'  => 'No videos found in trash',
    );
    $args = array(
        'label'               => 'videobloggervideos',
        'description'         => 'Video blogging individual videos',
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
        'taxonomies'          => array( 'videobloggervideos' ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'menu_icon'           => '',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'query_var'           => 'videobloggervideos',
        'capability_type'     => 'post',
    );
    register_post_type( 'videobloggervideos', $args );

}

// Register Custom Taxonomy - individual video source
function wtgvb_register_taxonomy_videosource() {

    $labels = array(
        'name'                       => 'Video Sources',
        'singular_name'              => 'Source',
        'menu_name'                  => 'Source',
        'all_items'                  => 'All Sources',
        'parent_item'                => 'Parent Source',
        'parent_item_colon'          => 'Parent Source:',
        'new_item_name'              => 'New Source',
        'add_new_item'               => 'Add New source',
        'edit_item'                  => 'Edit Source',
        'update_item'                => 'Update Source',
        'separate_items_with_commas' => 'Separate sources with commas',
        'search_items'               => 'Search sources',
        'add_or_remove_items'        => 'Add or remove sources',
        'choose_from_most_used'      => false,
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy( 'videobloggersources', 'videobloggervideos', $args );

}
 
function wtgvb_videos_template( $template_path ) {  

    if ( get_post_type() == 'videobloggervideos' ) {                
        if ( is_single() ) {  
                              
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'videoblogger-video.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = WTG_VB_DIR . 'templates/videoblogger-video.php';
            }
        }
    }
    
    return $template_path;
}
?>
