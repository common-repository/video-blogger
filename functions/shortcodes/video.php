<?php
/**
* Display a video
* 1. use in a video blogger post to position the video manually
* 2. use in any other post or page by providing ID of the video blogger post
*/
function wtgvb_shortcode_video_basic($atts){
    extract( shortcode_atts( array(
        'id' => 0,// video blogger post ID, only required if shortcode not inside a video blogger post
        'tag' => false,// true indicates the URL is to be retrieved straight from taxonomy terms, ignoring current or linked posts
        'show' => 1,// all or specific number - show more than one video, can only be used if returning embed, not the main purpose of this shortcode but someone may want multiple sources on one page
        'selection' => '0',// any or a number for selecting specific source
        'return' => 'snippet'// snippet|url - return full snippet which displays a video or return the source url only so that the shortcode can be used within a snippet 
    ), $atts ) );
    
    if($tag === false){
                           
        // establish post id
        $videopostid = false;
        if($id === 0){          
            $videopostid = get_the_ID();
        }else{
            $videopostid = (int) $id;      
        }
                             
        if(!$videopostid || !is_numeric($videopostid)){
            return '<p>'. __('Oops! A video was meant to show here but a post ID could not be established.','video-blogger') . '</p>';
        }
        
        // get all source (taxonomy terms)
        $terms = wp_get_post_terms( $videopostid, 'videobloggersources' );
        
        // ensure there are sources
        $sourcecount = count($terms);
        if($sourcecount == 0){return '<p>'. __('No videos have been added to this post yet.','video-blogger') . '</p>';}
                                                                     
        if($show == 'all'){
            
            // this is all videos, no randomizing, just make sure no duplicates            
            $pickedvideos = array();
            
            for ($i = 0; $i <= $sourcecount; $i++) {
                              
                if(isset($terms[$i]->name) && !in_array($terms[$i]->name,$pickedvideos)){
                    $pickedvideos[] = $terms[$i]->name;
                }
                
                if(count($pickedvideos) >= $show){
                    break;
                }
            }                   
            
        }elseif($show > 1){
            
            // this is displaying a specific number of videos and no specific videos (we won't use $selection that would get a little messy)
            $pickedvideos = array();
            
            for ($i = 0; $i <= $show; $i++) {
                
                if(isset($terms[$i]->name)){
                    $pickedvideos[] = $terms[$i]->name;
                }
                
                if($i >= $sourcecount || count($pickedvideos) >= $show){
                    break;
                }
            }           
            
        }elseif($selection == 'any' && $show == 1){ 
            
            // this will show one video and select any (also randomize)
            // we will do a maximum of 10 attempts to find a good video
            for ($i = 0; $i <= 10; $i++){
         
                $randompick = rand(0,$sourcecount);
                
                if(isset($terms[$randompick]->name)){
                    $counter = $randompick;
                    $videopicked = true;
                    break;
                }
            }
            
            if(!$videopicked){return '<p>'. __('No suitable videos were found.') .'</p>';}
            
        }elseif($show == 1 && $selection != 'any'){
            if(!isset($terms[$selection]->name)){return '<p>'. __('The video usually displayed here has to have been removed.','video-blogger') .'</p>';}
            $counter = 0;
        }
                                                          
        // return single url or single snippet or a stack of snippets (possibly styled to tile or accordian etc)
        if($return == 'url'){
            return $terms[$counter]->name;
        }elseif($show == 1){
            return wtgvb_videoobject($terms[$counter]->name);
        }elseif($show > 1 || $show == 'all'){
            // do not mistake this for our main playlist system, this here is not the main use of this plugin
            // however we can create a playlist using this approach but it wouldn't have the control our main metho has
            return wtgvb_videoplaylist_basic($pickedvideos);    
        }
        
    }else{
             
        if(!is_numeric($id)){
            return '<p>'. __('Oops! A video was meant to show here but the source ID provided is not valid.','video-blogger') .'</p>';
        }  
        
        $term = get_term( $id, 'videobloggersources' );
        
        if(!$term){
            return '<p>'. __('Well something went wrong because a video was meant to show right here, sorry!','video-blogger') .'</p>';
        }elseif(!isset($term->name)){
            return '<p>'. __('Sorry if you are expecting a video right here. Something went a little wrong!','video-blogger') .'</p>';
        }else{
            // return snippet or url
            if($return == 'url'){
                return $term->name;
            }else{
                return wtgvb_videoobject($term->name);
            }            
        }
              
    }

    return '<p>'. __('No Video','video-blogger') .'</p>';
}  
?>
