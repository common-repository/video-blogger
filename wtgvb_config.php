<?php
/**
* Admin Triggered Automation
* This calls various functions to peform cleanup functions, extra security checks and validation
*/
function wtgvb_admin_triggered_automation(){
    // clear out log table (48 hour log)
    VideoBlogger_WP::log_cleanup();
}     

/**
* Used by Wordpress email filter 
* 
* Example: add_filter( 'wp_mail_content_type', 'wtgvb_set_html_content_type' )
*/
function wtgvb_set_html_content_type() {
    return 'text/html';
}

/**
 * Installs the main elements for all packages
 */
function wtgvb_install_core(){         
    global $wtgvb_extension_loaded,$wtgvb_pub_set,$wtgvb_mpt_arr,$wtgvb_currentversion,$wtgvb_is_free;
            
    $minor_fails = 0;// count minor number of failures, if 3 or more then we'll call it a failed install
    $overall_install_result = true;// used to indicate overall result
   
    wtgvb_INSTALL_table_log();

    // schedule settings
    require(WTG_VB_PATH . 'arrays/wtgvb_schedule_array.php');
    if(!VideoBlogger_WP::option('wtgvb_schedule','add',serialize($wtgvb_schedule_array)) ){
         
        // should never happen - _uninstall() used at the beginning of _install_core()
        VideoBlogger_Notice::notice_depreciated(__('Schedule settings are already installed, no changes were made to those settings.','video-blogger'),'warning','Tiny',false,'','echo');
        $overall_install_result = false;          
   
    }else{
        VideoBlogger_Notice::notice_depreciated(__('Installed the schedule settings','video-blogger'),'success','Tiny',false,'','echo');
    }

    #################################################
    #                                               #
    #         INSTALL PERSISTENT NOTICE ARRAY       #
    #                                               #
    #################################################
    $wtgvb_persistent_array = array();
    if( !VideoBlogger_WP::option('wtgvb_notifications','add',serialize($wtgvb_persistent_array)) ){
        // should never happen - _uninstall() used at the beginning of _install_core()
        VideoBlogger_Notice::notice_depreciated(__('Notification settings are already installed, no changes were made to those settings.','video-blogger'),'warning','Tiny',false,'','echo');
        $overall_install_result = false;          
    }else{
        VideoBlogger_Notice::notice_depreciated(__('Installed the notification settings','video-blogger'),'success','Tiny',false,'','echo');
    }    
         
    // installation state values
    update_option('wtgvb_is_installed',true); 
    update_option('wtgvb_installedversion',$wtgvb_currentversion);# will only be updated when user prompted to upgrade rather than activation
    update_option('wtgvb_installeddate',time());# update the installed date, this includes the installed date of new versions

    return $overall_install_result;
}

/**
* Creates $wpdb->prefix.wtgvblog
*/
function wtgvb_INSTALL_table_log(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'wtgvblog';

    if(VideoBlogger_WPDB::does_table_exist($table_name)){
        
        VideoBlogger_Notice::notice_depreciated(sprintf(__('Database table named %s already exists.','video-blogger'),$table_name),'warning','Tiny','','','echo');    
    }else{ 
        global $wpdb;
        
        // table name 
        $create = 'CREATE TABLE `'.$table_name.'` (';

        // columns - please update http://www.videoblogger.com/hacking/log-table   
        $create .= '`row_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `outcome` tinyint(1) unsigned NOT NULL DEFAULT 1,
        `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `line` int(11) unsigned DEFAULT NULL,
        `file` varchar(250) DEFAULT NULL,
        `function` varchar(250) DEFAULT NULL,
        `sqlresult` blob,
        `sqlquery` varchar(45) DEFAULT NULL,
        `sqlerror` mediumtext,
        `wordpresserror` mediumtext,
        `screenshoturl` varchar(500) DEFAULT NULL,
        `userscomment` mediumtext,
        `page` varchar(45) DEFAULT NULL,
        `version` varchar(45) DEFAULT NULL,
        `panelid` varchar(45) DEFAULT NULL,
        `panelname` varchar(45) DEFAULT NULL,
        `tabscreenid` varchar(45) DEFAULT NULL,
        `tabscreenname` varchar(45) DEFAULT NULL,
        `dump` longblob,
        `ipaddress` varchar(45) DEFAULT NULL,
        `userid` int(11) unsigned DEFAULT NULL,
        `comment` mediumtext,
        `type` varchar(45) DEFAULT NULL,
        `category` varchar(45) DEFAULT NULL,
        `action` varchar(45) DEFAULT NULL,
        `priority` varchar(45) DEFAULT NULL,
        `triga` varchar(45) DEFAULT NULL,        
        PRIMARY KEY (`row_id`),
        UNIQUE KEY `row_id` (`row_id`)';

        // table config  
        $create .= ') ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8';
        
        $result = $wpdb->query( $create ); 
         
        if( $result ){
            VideoBlogger_Notice::notice_depreciated(sprintf(__('Database table named %s has been created.','video-blogger'),$table_name),'success','Tiny','','','echo');
            return true;
        }else{
            VideoBlogger_Notice::notice_depreciated(sprintf(__('Database table named %s could not be created. This must be investigated before using the plugin','video-blogger'),$table_name),'error','Tiny','','','echo');
            return false;
        }                                           
    }  
}

/**
* Quick way to delete then install all core database tables                             
*/
function wtgvb_INSTALL_reinstall_databasetables(){
    global $wtgvb_tables_array,$wpdb;
    $result_array = VideoBlogger_WP::result_array(__('result array for database table re-installation','video-blogger'),__LINE__,__FUNCTION__,__FILE__);
    $result_array['droppedtables'] = array();
    
    if(is_array($wtgvb_tables_array)){
        
        // delete all tables
        foreach($wtgvb_tables_array['tables'] as $key => $table){
           
            if(VideoBlogger_WPDB::does_table_exist($table['name'])){         
                $wpdb->query( 'DROP TABLE '. $table['name'] );
                $result_array['droppedtables'][] = $table['name'];
            }                                                             
        }
        
        // now install tables
        wtgvb_INSTALL_table_log();

    }else{
        $result_array['outcome'] = false;
        $result_array['failreason'] = 'tables array is not an array';
    }   
    
    return $result_array;
}

/**
* DO NOT CALL DURING FULL PLUGIN INSTALL
* This function uses update. Do not call it during full install because user may be re-installing but
* wishing to keep some existing option records.
* 
* Use this function when installing admin settings during use of the plugin. 
*/
function wtgvb_INSTALL_admin_settings(){
    require_once(WTG_VB_PATH . 'arrays/wtgvb_variables_adminset_array.php');
    return VideoBlogger_WP::option('wtgvb_settings','update',$wtgvb_settings);# update creates record if it does not exist   
}

/**
* DO NOT CALL DURING FULL PLUGIN INSTALL
* This function uses update. Users may want their installation to retain old values, we cannot assume the
* installation is 100% fresh.
* 
* Use this function when the tab menu option array is missing or invalid or when user actions a re-install of everything 
*/
function wtgvb_INSTALL_tabmenu_settings(){
    require_once(WTG_VB_PATH . 'views/wtgvb_menu_array.php');    
    $result = VideoBlogger_WP::option('wtgvb_tabmenu','update',$wtgvb_mpt_arr);   
} 

/**
 * Removes the plugins main wp-content folder, used in wtgvb_install_core() for failed install
 */
function wtgvb_remove_contentfolder(){

}

/**
* Deletes the plugins main content folder
* 
* @param mixed $pathdir (the path to be deleted)
* @param mixed $output (boolean true means that the file was found and deleted)
*/
function wtgvb_delete_contentfolder($pathdir,$output = false){
    if(!is_dir($pathdir)){
        VideoBlogger_Notice::notice_depreciated(__('Video Blogger could not find the main content folder, it may have already been deleted or moved.','video-blogger'), 'warning', 'Tiny',__('Content Folder Not Found','video-blogger') );
        return false;
    }else{
        if (VideoBlogger_PHP::dir_is_empty($pathdir)) {
            rmdir($pathdir);
            VideoBlogger_Notice::notice_depreciated(__('Content folder has been deleted after confirming it did not contain any files.','video-blogger'), 'success', 'Tiny',__('Content Folder Removed','video-blogger'));                
            return true; 
        }else{
            VideoBlogger_Notice::notice_depreciated(__('Content folder cannot be deleted as it contains files.','video-blogger'), 'warning', 'Tiny',__('Content Folder Not Removed','video-blogger') );                      
        }
    }
}

/**
 * Install main content folder in wp-content directory for holding uploaded items
 * Called from install function in install.php if constant is not equal to false "WTG_VB_CONTENTFOLDER_DIR
 *
 * @return boolean true if folder installed or already exists false if failed
 */
function wtgvb_install_contentfolder_videobloggercontent($pathdir,$output = false){
    $contentfolder_outcome = true;

    // does folder already exist
    if(!is_dir($pathdir))
    {
        $contentfolder_outcome = VideoBlogger_PHP::createfolder($pathdir);
    }
    else
    {
        return true;
    }

    if(!$contentfolder_outcome){
        $contentfolder_outcome = false;
        if($output){
            VideoBlogger_Notice::notice_depreciated(sprintf(__('Plugins content folder could be not created: %s','video-blogger'),$pathdir), 'error', 'Tiny');
        }
    }elseif($contentfolder_outcome){
         if($output){
            VideoBlogger_Notice::notice_depreciated(sprintf(__('Plugin content folder has been created here: %s','video-blogger'),$pathdir), 'success', 'Tiny');
         }
    }

    return $contentfolder_outcome;
}

/**
* Not to be confused with wtgvb_install_core()
* 1. use this to install elements related to the build, not the core
* 2. this function does not belong in wtgcore_wp_install.php which is where wtgvb_install_core() is  
*/
function wtgvb_install_plugin(){
    
    $overall_install_result = true;

    // create or confirm content folder for storing main uploads - false means no folder wanted, otherwise a valid path is expected
    if( defined(WTG_VB_CONTENTFOLDER_DIR)){$overall_install_result = wtgvb_install_contentfolder_videobloggercontent(WTG_VB_CONTENTFOLDER_DIR);}        

    return $overall_install_result;
}
       
/**
* takes a string of video post IDs, explodes them, gets wp_get_post_terms() (videobloggersources) for each then builds playlist_array 
*/
function wtgvb_videoblogger_selectedvideos_to_playlistarray($listmeta,$playlist_array){
    $listmeta_array = explode(',',$listmeta[0]);
    
    foreach($listmeta_array as $videopost_ID){
        $terms = wp_get_post_terms( $videopost_ID, 'videobloggersources' );

        if(!$terms){
            $videopost_array['fail'][$videopost_ID] = __('This post does not have any "videobloggersources" meta value.','video-blogger');        
        }else{
        
            // add term data to playlist_array, going to make it more readable by beginners so it is easier to customize
            // this is where we can perform checks on each video URL
            foreach($terms as $term_ID => $term_array){
                $playlist_array[$videopost_ID][$term_array->term_id]['url'] = $term_array->name;
                $playlist_array[$videopost_ID][$term_array->term_id]['description'] = $term_array->description;
                $playlist_array[$videopost_ID][$term_array->term_id]['used'] = $term_array->count;   
            }
            
        }   
    }   
    
    return $playlist_array; 
}     

/**
* advanced playlist, our own pre-set layout and design
*
* 1. use the basic as a starting point to creating a new one of these functions
* 2. wtgvb_videoplaylist_custom makes more use of options and users html design
* 3. requires passing of a totally different array than in basic which only passes url
*
* @package Video Blogger
* @since 1.0.0
* 
* @param mixed $alreadypicked
* @returns html 
*/
function wtgvb_videoplaylist_advanced($playlist_array,$maximum){
    global $wtgvb_adm_set;
    
    $usedsources_array = array();
    
    $finaloutput = '';
        
    $video_counter = 1;
    
    $adsense_counter = 0;
    
    // loop through posts (the $post_ID is the same ID as stored in the V.B. Playlists meta)
    foreach($playlist_array as $post_ID => $postsource_array){

        // we only want to use one Source URL per video post, if we wanted we could validate the URL or cycle it etc
        foreach($postsource_array as $term_ID => $video_array){
        
            $video_source_picked = false;
            
            if(!in_array($video_array['url'],$usedsources_array)){
                
                $finaloutput .= '<div class="videoblogger-video">';
                
                $finaloutput .= wtgvb_videoobject($video_array['url']);
                
                if(isset($video_array['description']) && !empty($video_array['description'])){
                    $finaloutput .= '<p>' . $video_counter .'. ' . $video_array['description'] . '</p>';
                }else{
                    $finaloutput .= '<br><br>';
                }
                
                if(isset($wtgvb_adm_set['adoptions']['status']) && isset($wtgvb_adm_set['adsnippets']) && $adsense_counter != 3){
                    if($wtgvb_adm_set['adoptions']['status'] == 'posts' || $wtgvb_adm_set['adoptions']['status'] == 'postssidebar'){
                        
                        $finaloutput .= '<div class="videoblogger-adsense" style="text-align: center">';
                        
                        $finaloutput .= $wtgvb_adm_set['adsnippets'][2]['snippet'];        
                        
                        $finaloutput .= '<br><br>';  
                        
                        $finaloutput .= '</div>';  
                        
                        ++$adsense_counter;
                    }
                }
                                
                $finaloutput .= '</div>'; 
                
                // we are not doing anything fancy here yet but we could validate the URL, ensure video exists
                $video_source_picked = true;
                
                if($video_source_picked){
                    ++$video_counter;
                    break;
                }
            }
        } 
    }  
         
    return $finaloutput;
}

/**
* custom playlist, uses more user html and css based on options
* 
* 1. styling
* 2. layout
* 3. options
* 
* @param mixed $alreadypicked
*/
function wtgvb_videoplaylist_custom($playlist_array){
    $finaloutput = '';
    foreach($alreadypicked as $url){
        
        $finaloutput .= '<div class="videoblogger-video">';
        
        $finaloutput .= wtgvb_videoobject($url);

        $finaloutput .= '</div>'; 
           
    }    
    return $finaloutput;
} 

/**
* A basic playlist, we will not be adding too many variables and styling to this one.
* 1. used with the single video post option to display more than one
* 
* @param mixed $alreadypicked
*/
function wtgvb_videoplaylist_basic($playlist_array){
    global $wtgvb_adm_set;
                   
    $usedsources_array = array();
    
    $finaloutput = '';
        
    $video_counter = 1;
    
    $adsense_counter = 0;
    
    $finaloutput .= '<div class="videoblogger-playlist">';
    
    // loop through posts (the $post_ID is the same ID as stored in the V.B. Playlists meta)
    foreach($playlist_array as $post_ID => $postsource_array)
    {
        // we only want to use one Source URL per video post, if we wanted we could validate the URL or cycle it etc
        foreach($postsource_array as $term_ID => $video_array)
        {
            $video_source_picked = false;
            
            if(!in_array($video_array['url'],$usedsources_array))
            {

                $finaloutput .= '<div class="videoblogger-video">';
                
                $finaloutput .= wtgvb_videoobject($video_array['url']);

                $finaloutput .= '<br><br>';

                if(isset($wtgvb_adm_set['adoptions']['status']) && isset($wtgvb_adm_set['adsnippets']) && $adsense_counter != 3)
                { 
                    if($wtgvb_adm_set['adoptions']['status'] == 'posts' || $wtgvb_adm_set['adoptions']['status'] == 'postssidebar')
                    {  
                        $finaloutput .= '<div class="videoblogger-adsense" style="text-align: center">';
                        
                        $finaloutput .= $wtgvb_adm_set['adsnippets'][2]['snippet'];        
                        
                        $finaloutput .= '<br><br>';  
                        
                        $finaloutput .= '</div>';  
                        
                        ++$adsense_counter;
                    }
                }

                $finaloutput .= '</div>'; 
                
                // we are not doing anything fancy here yet but we could (eventually will) validate the URL, ensure video exists
                $video_source_picked = true;
            }
            
            if($video_source_picked)
            {
                ++$video_counter;
                break;
            }            
        } 
    }  
    
    $finaloutput .= '</div>';
         
    return $finaloutput;
}    

/**
* Builds appropriate snippet for giving url
* 
* @param mixed $url
*/
function wtgvb_videoobject($url){
    if(strstr($url,'youtube.com'))
    {
        // extract video ID
        $video_ID = wtgvb_YouTube_ID($url);
        if(!$video_ID){return __('Invalid YouTube URL found, no video ID could be established.','video-blogger');}
        return wtgvb_buildembedcode_youtube($video_ID);
    }
    elseif(strstr($url,'blip.tv/play'))
    {  
        $video_ID = wtgvb_BlipTV_ID($url);
        return '<iframe src="http://blip.tv/play/'.$video_ID.'.x?p=1" width="720" height="433" frameborder="0" allowfullscreen></iframe>
        <embed type="application/x-shockwave-flash" src="http://a.blip.tv/api.swf#'.$video_ID.'" style="display:none">
        </embed>'; 
    }
    elseif(strstr($url,'player.vimeo.com'))
    { 
        return '<iframe src="'.$url.'" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    }
    elseif(strstr($url,'videoplayer.vevo.com/embed'))
    { 
        $video_ID = wtgvb_vevo_ID($url);
        return '<object width="575" height="324"><param name="movie" value="http://videoplayer.vevo.com/embed/Embedded?videoId='.$video_ID.'&playlist=false&autoplay=0&playerId=62FF0A5C-0D9E-4AC1-AF04-1D9E97EE3961&playerType=embedded&env=0&cultureName=en-US&cultureIsRTL=False"></param>
        <param name="wmode" value="transparent"></param><param name="bgcolor" value="#000000"></param>
        <param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param>
        <embed src="http://videoplayer.vevo.com/embed/Embedded?videoId='.$video_ID.'&playlist=false&autoplay=0&playerId=62FF0A5C-0D9E-4AC1-AF04-1D9E97EE3961 &playerType=embedded&env=0&cultureName=en-US&cultureIsRTL=False" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="575" height="324" bgcolor="#000000" wmode="transparent">
        </embed></object>';
    }
    elseif(strstr($url,'dailymotion.com/embed'))
    {
        $video_ID = wtgvb_dailymotion_ID($url);
        return '<iframe frameborder="0" width="480" height="270" src="http://www.dailymotion.com/embed/video/'.$video_ID.'"></iframe>';
    }
    elseif(strstr($url,'metacafe.com/embed'))
    {
        return '<iframe src="http://www.metacafe.com/embed/10919565/" width="440" height="248" allowFullScreen frameborder=0></iframe>';
    }
    elseif(strstr($url,'break.com/embed'))
    {
        return '<iframe scrolling="no" marginheight="0" marginwidth="0" width="464" height="290" frameborder="0" src="'.$url.'" allowfullscreen ></iframe>';
    }

    return '<p>'. __('Video URL provided was not recognized.', 'video-blogger') .'</p>';
}     

function wtgvb_buildembedcode_youtube($videoid){    
    return '<object width="560" height="315"><param name="movie" value="//www.youtube.com/v/'.$videoid.'?hl=en_US&amp;version=3"></param>
    <param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param>
    <embed src="//www.youtube.com/v/'.$videoid.'?hl=en_US&amp;version=3" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true">
    </embed></object>';
}    

/**
* extract video ID from URL, recommend validating URL contains "youtube" prior to calling
* 
* @param mixed $url
*/
function wtgvb_YouTube_ID($url){    
    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $id)) {
        return $id[1];
    } else {   
        return false;
    }
} 
?>
