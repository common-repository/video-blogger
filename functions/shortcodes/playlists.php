<?php
/**
* Display a playlist
*/
function wtgvb_shortcode_playlist_basic($atts){
    global $wtgvb_is_free;
    
    extract( shortcode_atts( array(
        'listid' => false,// ID for another V.B. Playlist post
        'search' => false,// search content, custom fields and tags for a phrase or keyword
        'customfields' => false,// match specific meta keys and values
        'maximum' => 50,// put a limit on the number of posts to show
    ), $atts ) );

    // the playlist array is used to build the playlist
    $playlist_array = array();
    
    if($listid != false){
        
        if(!is_numeric($listid)){
            return '<p>'. __('Sorry but an invalid video ID was giving here.', 'video-blogger') .'</p>';
        }                    
                                                                   
        $listmeta = get_post_meta($listid,'_videoblogger_selectedvideos');
        
        if(!$listmeta){
            return '<p>'. __('Oops, could not retrieve the playlist.', 'video-blogger') .'</p>';     
        }
        
        $playlist_array = wtgvb_videoblogger_selectedvideos_to_playlistarray($listmeta,$playlist_array);
          
    }elseif($search != false){
        
        global $wpdb;
        
        $get_results_posts = wtgvb_search_post_content($search, 'videobloggervideos');

        foreach($get_results_posts as $a_result){
    
            $terms = wp_get_post_terms( $a_result->ID, 'videobloggersources' );

            if(!$terms){
                $videopost_array['fail'][$a_result->ID] = __('This post does not have any "videobloggersources" meta value.','video-blogger');        
            }else{
            
                // add term data to playlist_array, going to make it more readable by beginners so it is easier to customize
                // this is where we can perform checks on each video URL
                foreach($terms as $term_ID => $term_array){
                    $playlist_array[$a_result->ID][$term_array->term_id]['url'] = $term_array->name;
                    $playlist_array[$a_result->ID][$term_array->term_id]['description'] = $term_array->description;
                    $playlist_array[$a_result->ID][$term_array->term_id]['used'] = $term_array->count;   
                }
                
            }
          
        }
        
    }elseif($customfields != false){
        
        global $wpdb;
        
        $querystr = "
            SELECT wposts.ID
            FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
            WHERE wposts.ID = wpostmeta.post_id
            AND wpostmeta.meta_key = 'mytopic'
            AND wpostmeta.meta_value = 'Wordpress Plugins'
            AND wposts.post_type = 'videobloggervideos'
            ORDER BY wpostmeta.meta_value DESC
            LIMIT $maximum
        ";

        $get_results_posts = $wpdb->get_results($querystr, OBJECT);        
            
        foreach($get_results_posts as $a_result){
    
            $terms = wp_get_post_terms( $a_result->ID, 'videobloggersources' );

            if(!$terms){
                $videopost_array['fail'][$a_result->ID] = __('This post does not have any "videobloggersources" meta value.','video-blogger');        
            }else{
            
                // add term data to playlist_array, going to make it more readable by beginners so it is easier to customize
                // this is where we can perform checks on each video URL
                foreach($terms as $term_ID => $term_array){
                    $playlist_array[$a_result->ID][$term_array->term_id]['url'] = $term_array->name;
                    $playlist_array[$a_result->ID][$term_array->term_id]['description'] = $term_array->description;
                    $playlist_array[$a_result->ID][$term_array->term_id]['used'] = $term_array->count;   
                }
                
            }
          
        }
            
    }else{
        
        // get the video ID string for the current post (not alot different from $listids but we will keep it as two seperate processes right now)
        $videopostid = get_the_ID();
        $listmeta = get_post_meta($videopostid,'_videoblogger_selectedvideos');
        if(!$listmeta){
            return '<p>'. __('Something went wrong with this playlist, sorry.', 'video-blogger') .'</p>';     
        }        
        
        $playlist_array = wtgvb_videoblogger_selectedvideos_to_playlistarray($listmeta,$playlist_array);        
    }
    
    // we should now have an array for building a playlist
    if($playlist_array && count($playlist_array) != 0){ 

        $playlist_array = array_slice($playlist_array, 0, $maximum);
        
        if(!$wtgvb_is_free){
            return wtgvb_videoplaylist_advanced($playlist_array,$maximum);
        }else{
            return wtgvb_videoplaylist_basic($playlist_array,$maximum);
        }
    }

    return '<p>'. __('No Playlist','video-blogger') .'</p>';   
}  
?>
