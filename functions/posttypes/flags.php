<?php
/** 
* WebTechGlobal Flag system for Wordpress
* 
* Use to flag anything that matters. Creates a task list but can also be configured or developed into a global alert system for many 
* administrators or all staff in a business. The flag itself holding all information to be communicated. It is temporary until manually
* deleted or expiry ends if expiry set. 
* 
* @package Video Blogger
* 
* @since 0.0.3
* 
* @copyright (c) 2009-2013 webtechglobal.co.uk
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* 
* @author Ryan Bayne | ryan@webtechglobal.co.uk
*/

add_action( 'init', 'wtgvb_register_customposttype_flags');   
add_action( 'save_post', 'wtgvb_save_meta_boxes_flags', 10, 2);
add_action( 'add_meta_boxes', 'wtgvb_add_meta_boxes_flags');   

function wtgvb_register_customposttype_flags(){
    $labels = array(
        'name' => _x('Video Blogger Flags', 'post type general name'),
        'singular_name' => _x('Video Blogger Flag', 'post type singular name'),
        'add_new' => _x('Flag Item', 'videobloggerflags'),
        'add_new_item' => __('Create Flag'),
        'edit_item' => __('Edit Flag'),
        'new_item' => __('Create Flag'),
        'all_items' => __('All Flags'),
        'view_item' => __('View Flag'),
        'search_items' => __('Search Flags'),
        'not_found' =>  __('No flags found'),
        'not_found_in_trash' => __('No flags found in Trash'), 
        'parent_item_colon' => '',
        'menu_name' => 'Flags'
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => false,
        'menu_position' => 25,
        'supports' => array( 'title', 'editor' )
    );   

    register_post_type( 'videobloggerflags', $args );    
} 

/**
* Save flag meta box's
* 
* @param mixed $post_id
* @param mixed $post
*/
function wtgvb_save_meta_boxes_flags( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['wtgvb_flagsnonce'] ) || !wp_verify_nonce( $_POST['wtgvb_flagsnonce'], basename( __FILE__ ) ) )    
        return $post_id;
        
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
                                        
    // check permissions
    if ( (key_exists('post_type', $post)) && ('page' == $post->post_type) ) {
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

    $flagmeta_array = array('phpline','phpfunction','priority','type','fileurl','dataid','userid','errortext',
    'projectid','reason','action','instructions','dump','creator','version');

    // loop through our terms and meta functions
    foreach($flagmeta_array as $key => $term){  
        $new_meta_value = '';
         
        /* Get the meta key. */
        $meta_key = '_videobloggerflags_' . $term;

        if(isset($_POST['wtgvb_flags_'.$term])){
            $new_meta_value = $_POST['wtgvb_flags_'.$term];    
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

function wtgvb_add_meta_boxes_flags() {
    global $wtgvb_is_free;

    // phpline
    add_meta_box(
        'flags-meta-phpline',            
        esc_html__( 'PHP Line', 'example' ),       
        'wtgvb_meta_box_flags_phpline',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    );
     
    // phpfunction 
    add_meta_box(
        'flags-meta-phpfunction',            
        esc_html__( 'PHP Function', 'example' ),       
        'wtgvb_meta_box_flags_phpfunction',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    );       
     
    // priority 
    add_meta_box(
        'flags-meta-priority',            
        esc_html__( 'Priority', 'example' ),       
        'wtgvb_meta_box_flags_priority',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 

    // type 
    add_meta_box(
        'flags-meta-type',            
        esc_html__( 'Type', 'example' ),       
        'wtgvb_meta_box_flags_type',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    );    

    // fileurl 
    add_meta_box(
        'flags-meta-fileurl',            
        esc_html__( 'File URL', 'example' ),       
        'wtgvb_meta_box_flags_fileurl',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 

    // dataid 
    add_meta_box(
        'flags-meta-dataid',            
        esc_html__( 'Data ID', 'example' ),       
        'wtgvb_meta_box_flags_dataid',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 

    // userid 
    add_meta_box(
        'flags-meta-userid',            
        esc_html__( 'User ID', 'example' ),       
        'wtgvb_meta_box_flags_userid',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 

    // errortext 
    add_meta_box(
        'flags-meta-errortext',            
        esc_html__( 'Error Text', 'example' ),       
        'wtgvb_meta_box_flags_errortext',        
        'videobloggerflags',                  
        'normal',                 
        'default'                  
    ); 

    // projectid 
    add_meta_box(
        'flags-meta-projectid',            
        esc_html__( 'Project ID', 'example' ),       
        'wtgvb_meta_box_flags_projectid',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 

    // reason 
    add_meta_box(
        'flags-meta-reason',            
        esc_html__( 'Reason', 'example' ),       
        'wtgvb_meta_box_flags_reason',        
        'videobloggerflags',                  
        'normal',                 
        'default'                  
    ); 

    // action 
    add_meta_box(
        'flags-meta-action',            
        esc_html__( 'Action', 'example' ),       
        'wtgvb_meta_box_flags_action',        
        'videobloggerflags',                  
        'normal',                 
        'default'                  
    ); 

    // instructions 
    add_meta_box(
        'flags-meta-instructions',            
        esc_html__( 'Instructions', 'example' ),       
        'wtgvb_meta_box_flags_instructions',        
        'videobloggerflags',                  
        'normal',                 
        'default'                  
    ); 

    // dump 
    add_meta_box(
        'flags-meta-dump',            
        esc_html__( 'Dump', 'example' ),       
        'wtgvb_meta_box_flags_dump',        
        'videobloggerflags',                  
        'normal',                 
        'default'                  
    ); 

    // creator 
    add_meta_box(
        'flags-meta-creator',            
        esc_html__( 'Creator', 'example' ),       
        'wtgvb_meta_box_flags_creator',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 

    // version 
    add_meta_box(
        'flags-meta-version',            
        esc_html__( 'Version', 'example' ),       
        'wtgvb_meta_box_flags_version',        
        'videobloggerflags',                  
        'side',                 
        'default'                  
    ); 
}

// phpline meta box
function wtgvb_meta_box_flags_phpline( $object, $box ) { ?>
    <?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_phplinenonce' ); ?>
    <p>
        <input class="widefat" type="text" name="wtgvb_flags_phpline" id="phpline-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_phpline', true ) ); ?>" size="30" />
    </p><?php 
}

// phpfunction meta box
function wtgvb_meta_box_flags_phpfunction( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_phpfunctionnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_phpfunction" id="phpfunction-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_phpfunction', true ) ); ?>" size="30" />
</p><?php 
}    

// priority meta box
function wtgvb_meta_box_flags_priority( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_prioritynonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_priority" id="priority-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_priority', true ) ); ?>" size="30" />
</p><?php 
}        

// type meta box
function wtgvb_meta_box_flags_type( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_typenonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_type" id="type-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_type', true ) ); ?>" size="30" />
</p><?php 
}      

// fileurl meta box
function wtgvb_meta_box_flags_fileurl( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_fileurlnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_fileurl" id="fileurl-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_fileurl', true ) ); ?>" size="30" />
</p><?php 
}      

// dataid meta box
function wtgvb_meta_box_flags_dataid( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_dataidnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_dataid" id="dataid-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_dataid', true ) ); ?>" size="30" />
</p><?php 
}      

// userid meta box
function wtgvb_meta_box_flags_userid( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_useridnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_userid" id="userid-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_userid', true ) ); ?>" size="30" />
</p><?php 
}      

// errortext meta box
function wtgvb_meta_box_flags_errortext( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_errortextnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_errortext" id="errortext-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_errortext', true ) ); ?>" size="30" />
</p><?php 
}      

// projectid meta box
function wtgvb_meta_box_flags_projectid( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_projectidnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_projectid" id="projectid-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_projectid', true ) ); ?>" size="30" />
</p><?php 
}        

// reason 
function wtgvb_meta_box_flags_reason( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_reasonnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_reason" id="reason-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_reason', true ) ); ?>" size="30" />
</p><?php 
}        
    
// action 
function wtgvb_meta_box_flags_action( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_actionnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_action" id="action-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_action', true ) ); ?>" size="30" />
</p><?php 
}        

// instructions 
function wtgvb_meta_box_flags_instructions( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_instructionsnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_instructions" id="action-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_instructions', true ) ); ?>" size="30" />
</p><?php 
}      

// dump 
function wtgvb_meta_box_flags_dump( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flags_dumpnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_dump" id="action-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_dump', true ) ); ?>" size="30" />
</p><?php 
}      

// creator 
function wtgvb_meta_box_flags_creator( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_creator_creatornonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_creator" id="action-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_creator', true ) ); ?>" size="30" />
</p><?php 
}      

// version 
function wtgvb_meta_box_flags_version( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'wtgvb_flagsnonce' ); ?>
<p>
    <input class="widefat" type="text" name="wtgvb_flags_version" id="version-id" value="<?php echo esc_attr( get_post_meta( $object->ID, '_videobloggerflags_version', true ) ); ?>" size="30" />
</p><?php 
}      
?>
