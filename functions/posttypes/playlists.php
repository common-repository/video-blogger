<?php
// playlist
add_action( 'init', 'wtgvb_register_customposttype_playlists', 0 );
add_action( 'add_meta_boxes', 'wtgvb_add_meta_boxes_playlists' );
add_action( 'save_post', 'wtgvb_save_meta_boxes_playlist',10,2 ); 

// Register Custom Post Type - playlists
function wtgvb_register_customposttype_playlists() {

    $labels = array(
        'name'                => 'Playlists',
        'singular_name'       => 'Playlist',
        'menu_name'           => 'V.B. Playlists',
        'parent_item_colon'   => 'Parent Playlist:',
        'all_items'           => 'All Playlists',
        'view_item'           => 'View Playlist',
        'add_new_item'        => 'Add New Playlists',
        'add_new'             => 'New Playlist',
        'edit_item'           => 'Edit Playlist',
        'update_item'         => 'Update Playlist',
        'search_items'        => 'Search playlists',
        'not_found'           => 'No playlists found',
        'not_found_in_trash'  => 'No playlists found in trash',
    );
    $args = array(
        'label'               => 'videobloggerplaylists',
        'description'         => 'Video blogger playlists',
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', ),
        'taxonomies'          => array(),
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
        'capability_type'     => 'post',
    );
    register_post_type( 'videobloggerplaylist', $args );

}

function wtgvb_add_meta_boxes_playlists(){
    add_meta_box('vb-id-playlists-selectedvideos',__('Videos Assigned To Playlist','video-blogger'),'wtgvb_meta_box_playlists_selectedvideos','videobloggerplaylist','normal','high');   
}   

function wtgvb_meta_box_playlists_selectedvideos($object){
    wp_nonce_field( basename( __FILE__ ), 'wtgvb_playlistnonce_selectedvideos' ); ?>
    <p>Separate post ID's with a comma and the ID's must be from V.B. Videos</p>
    <p>
        <input class="widefat" type="text" name="wtgvb_playlists_selectedvideos" id="wtgvbselectedvideos" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videoblogger_selectedvideos', true ) ); ?>" size="30" />
    </p><?php 
}

/**
* Save playlist meta box's
* 
* @param mixed $post_id
* @param mixed $post
*/
function wtgvb_save_meta_boxes_playlist( $post_id, $post ) {
                  
    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['wtgvb_playlistnonce_selectedvideos'] ) || !wp_verify_nonce( $_POST['wtgvb_playlistnonce_selectedvideos'], basename( __FILE__ ) ) )    
        return $post_id;
               
    // avoid processing during autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
                                          
    // check permissions to edit pages
    if ( (key_exists('post_type', $post)) && ('videobloggerplaylist' == $post->post_type) ) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }        
           
    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );
        
    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ){
        return $post_id;
    }
      
    $meta_array = array('selectedvideos');
 
    // loop through our terms and meta functions
    foreach($meta_array as $key => $partkey){  
        $new_meta_value = '';
                   
        /* Get the meta key. */
        $meta_key = '_videoblogger_' . $partkey;

        if(isset($_POST['wtgvb_playlists_'.$partkey])){
            $new_meta_value = $_POST['wtgvb_playlists_'.$partkey];    
        }
        
        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta( $post_id, $meta_key, true );

        if ( $new_meta_value && '' == $meta_value ){
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );# new meta value was added and there was no previous value
        }elseif ( $new_meta_value && $new_meta_value != $meta_value ){
            update_post_meta( $post_id, $meta_key, $new_meta_value );# new meta value does not match the old value, update it
        }elseif ( '' == $new_meta_value && $meta_value ){
            delete_post_meta( $post_id, $meta_key, $meta_value );# no new meta value but an old value exists, delete it
        }
    }
}

function wtgvb_playlists_template( $template_path ) {  

    if ( get_post_type() == 'videobloggerplaylist' ) {                
        if ( is_single() ) {  
                              
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'videoblogger-playlist.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = WTG_VB_DIR . 'templates/videoblogger-playlist.php';
            }
        }
    }
    
    return $template_path;
} 
?>
