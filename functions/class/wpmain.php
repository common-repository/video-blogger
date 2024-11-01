<?php
/** 
* Wordpress main functions library for plugin and theme development
* 
* @package Video Blogger
* 
* @version 0.0.1
* 
* @since 0.0.2
* 
* @copyright (c) 2009-2013 webtechglobal.co.uk
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
* 
* @author Ryan Bayne | ryan@webtechglobal.co.uk
*/

class VideoBlogger_WP{
    /**
     * Checks existing plugins and displays notices with advice or informaton
     * This is not only for code conflicts but operational conflicts also especially automated processes
     *
     * $return $critical_conflict_result true or false (true indicatesd a critical conflict found, prevents installation, this should be very rare)
     */
    function conflict_prevention($outputnoneactive = false)
    {
        // track critical conflicts, return the result and use to prevent installation
        // only change $conflict_found to true if the conflict is critical, if it only effects partial use
        // then allow installation but warn user
        $conflict_found = false;

        // Wordpress HTTPS  EXAMPLE
        /*
        $plugin_profiles[1]['switch'] = 1;//used to use or not use this profile, 0 is no and 1 is use
        $plugin_profiles[1]['title'] = 'Wordpress HTTPS';
        $plugin_profiles[1]['slug'] = 'wordpress-https/wordpress-https.php';
        $plugin_profiles[1]['author'] = 'Mvied';
        $plugin_profiles[1]['title_active'] = 'Wordpress HTTPS Conflict';
        $plugin_profiles[1]['message_active'] = __('On 15th August 2012 a critical and persistent conflict was found 
        between Wordpress HTTPS and Video Blogger. It breaks the jQuery UI tabs by making all panels/features show on
        one screen rather than on individual tabbed screens. A search on Google found many posts regarding the
        plugin causing JavaScript related conflicts, responded to my the plugins author. So please ensure you
        have the latest version. One of the posts suggested the fault, was exactly as we found to be causing our
        broken interface in Video Blogger. URL were being re-written, specifically those passed through jQuery UI functions
        were having a slash removed. It would not just break the interface but submitting some forms would fail
        because the action location URL would be wrong. This sounds like the fault described by a Wordpress HTTPS user,
        before the author responded to it. So not sure what is going on but right now we do not feel we can provide the
        fix. Please still let us know if you are seeing this message, we need to know how popular the conflicting plugin
        is while using Video Blogger for auto-blogging.');
        $plugin_profiles[1]['title_inactive'] = 'title inactive';
        $plugin_profiles[1]['message_inactive'] = __('message inactive');
        $plugin_profiles[1]['type'] = 'info';//passed to the message function to apply styling and set type of notice displayed
        $plugin_profiles[1]['criticalconflict'] = true;// true indicates that the conflict will happen if plugin active i.e. not specific settings only, simply being active has an effect
        */
            
        // we create an array of profiles for plugins we want to check
        $plugin_profiles = array();

        // Tweet My Post (javascript conflict and a critical one that breaks entire interface)
        $plugin_profiles[0]['switch'] = 1;//used to use or not use this profile, 0 is no and 1 is use
        $plugin_profiles[0]['title'] = 'Tweet My Post';
        $plugin_profiles[0]['slug'] = 'tweet-my-post/tweet-my-post.php';
        $plugin_profiles[0]['author'] = 'ksg91';
        $plugin_profiles[0]['title_active'] = 'Tweet My Post Conflict';
        $plugin_profiles[0]['message_active'] = __('Please deactivate Twitter plugins before performing mass post creation. This will avoid spamming Twitter and causing more processing while creating posts.','video-blogger');
        $plugin_profiles[0]['message_inactive'] = __('If you activate this or any Twitter plugin please ensure the pluginsoptions are not setup to perform mass tweets during post creation.','video-blogger');
        $plugin_profiles[0]['type'] = 'info';//passed to the message function to apply styling and set type of notice displayed
        $plugin_profiles[0]['criticalconflict'] = true;// true indicates that the conflict will happen if plugin active i.e. not specific settings only, simply being active has an effect
                             
        // loop through the profiles now
        if(isset($plugin_profiles) && $plugin_profiles != false){
            foreach($plugin_profiles as $key=>$plugin){   
                if( is_plugin_active( $plugin['slug']) ){ 
                   
                    // recommend that the user does not use the plugin
                    VideoBlogger_Notice::notice_depreciated($plugin['message_active'],'warning','Small',$plugin['title_active'],'','echo');

                    // if the conflict is critical, we will prevent installation
                    if($plugin['criticalconflict'] == true){
                        $conflict_found = true;// indicates critical conflict found
                    }
                    
                }elseif(is_plugin_inactive($plugin['slug'])){
                    
                    if($outputnoneactive)
                    {    
                        VideoBlogger_Notice::n_incontent_depreciated($plugin['message_inactive'],'warning','Small',$plugin['title'] . ' Plugin Found');
                    }
        
                }
            }
        }

        return $conflict_found;
    }     
    /**
    * Determines if process request of any sort has been requested
    * 1. used to avoid triggering automatic processing during proccess requests
    * 
    * @returns true if processing already requested else false
    */
    public function request_made()
    {
        // ajax
        if(defined('DOING_AJAX') && DOING_AJAX){
            return true;    
        } 
        
        // form submissions - if $_POST is set that is fine, providing it is an empty array
        if(isset($_POST) && !empty($_POST)){
            return true;
        }
        
        // Video Blogger own special processing triggers
        if(isset($_GET['wtgvbprocsub']) || isset($_GET['wtgvbprocess']) || isset($_GET['nonceaction'])){
            return true;
        }
        
        return false;
    }    
    /**
    * Gets notifications array if it exists in Wordpress options table else returns empty array
    */
    public function persistentnotifications_array()
    {
        $a = get_option('wtgvb_notifications');
        $v = maybe_unserialize($a);
        if(!is_array($v)){
            return array();    
        }
        return $v;    
    }    
    /**
    * Used to build history, flag items and schedule actions to be performed.
    * 1. it all falls under log as we would probably need to log flags and scheduled actions anyway
    *
    * @global $wpdb
    * @uses extract, shortcode_atts
    * 
    * @link http://www.videoblogger.com/hacking/log-table
    */
    public function newlog($atts)
    {     
        global $wtgvb_settings,$wpdb,$wtgvb_currentversion;

        $table_name = $wpdb->prefix . 'wtgvblog';
        
        // if ALL logging is off - if ['uselog'] not set then logging for all files is on by default
        if(isset($wtgvb_settings['reporting']['uselog']) && $wtgvb_settings['reporting']['uselog'] == 0){return false;}
        
        // if log table does not exist return false
        if(!VideoBlogger_WPDB::does_table_exist($table_name)){return false;}
        
        // if a value is false, it will not be added to the insert query, we want the database default to kick in, NULL mainly
        extract( shortcode_atts( array(  
            'outcome' => 1,# 0|1 (overall outcome in boolean) 
            'line' => false,# __LINE__ 
            'function' => false,# __FUNCTION__
            'file' => false,# __FILE__ 
            'sqlresult' => false,# dump of sql query result 
            'sqlquery' => false,# dump of sql query 
            'sqlerror' => false,# dump of sql error if any 
            'wordpresserror' => false,# dump of a wp error 
            'screenshoturl' => false,# screenshot URL to aid debugging 
            'userscomment' => false,# beta testers comment to aid debugging (may double as other types of comments if log for other purposes) 
            'page' => false,# related page 
            'version' => $wtgvb_currentversion, 
            'panelid' => false,# id of submitted panel
            'panelname' => false,# name of submitted panel 
            'tabscreenid' => false,# id of the menu tab  
            'tabscreenname' => false,# name of the menu tab 
            'dump' => false,# dump anything here 
            'ipaddress' => false,# users ip 
            'userid' => false,# user id if any    
            'noticemessage' => false,# when using log to create a notice OR if logging a notice already displayed      
            'comment' => false,# dev comment to help with troubleshooting
            'type' => false,# general|error|trace 
            'category' => false,# createposts|importdata|uploadfile|deleteuser|edituser 
            'action' => false,# 3 posts created|22 posts updated (the actuall action performed)
            'priority' => false,# low|normal|high (use high for errors or things that should be investigated, use low for logs created mid procedure for tracing progress)                        
            'triga' => false# autoschedule|cronschedule|wpload|manualrequest
        ), $atts ) );
        
        // start query
        $query = "INSERT INTO $table_name";
        
        // add columns and values
        $query_columns = '(outcome';
        $query_values = '(1';
        
        if($line){$query_columns .= ',line';$query_values .= ',"'.$line.'"';}
        if($file){$query_columns .= ',file';$query_values .= ',"'.$file.'"';}                                                                           
        if($function){$query_columns .= ',function';$query_values .= ',"'.$function.'"';}  
        if($sqlresult){$query_columns .= ',sqlresult';$query_values .= ',"'.$sqlresult.'"';}     
        if($sqlquery){$query_columns .= ',sqlquery';$query_values .= ',"'.$sqlquery.'"';}     
        if($sqlerror){$query_columns .= ',sqlerror';$query_values .= ',"'.$sqlerror.'"';}    
        if($wordpresserror){$query_columns .= ',wordpresserror';$query_values .= ',"'.$wordpresserror.'"';}     
        if($screenshoturl){$query_columns .= ',screenshoturl';$query_values .= ',"'.$screenshoturl.'"' ;}     
        if($userscomment){$query_columns .= ',userscomment';$query_values .= ',"'.$userscomment.'"';}     
        if($page){$query_columns .= ',page';$query_values .= ',"'.$page.'"';}     
        if($version){$query_columns .= ',version';$query_values .= ',"'.$version.'"';}     
        if($panelid){$query_columns .= ',panelid';$query_values .= ',"'.$panelid.'"';}     
        if($panelname){$query_columns .= ',panelname';$query_values .= ',"'.$panelname.'"';}     
        if($tabscreenid){$query_columns .= ',tabscreenid';$query_values .= ',"'.$tabscreenid.'"';}     
        if($tabscreenname){$query_columns .= ',tabscreenname';$query_values .= ',"'.$tabscreenname.'"';}     
        if($dump){$query_columns .= ',dump';$query_values .= ',"'.$dump.'"';}     
        if($ipaddress){$query_columns .= ',ipaddress';$query_values .= ',"'.$ipaddress.'"';}     
        if($userid){$query_columns .= ',userid';$query_values .= ',"'.$userid.'"';}     
        if($noticemessage){$query_columns .= ',noticemessage';$query_values .= ',"'.$noticemessage.'"';}     
        if($comment){$query_columns .= ',comment';$query_values .= ',"'.$comment.'"';}     
        if($type){$query_columns .= ',type';$query_values .= ',"'.$type.'"';}     
        if($category){$query_columns .= ',category';$query_values .= ',"'.$category.'"';}     
        if($action){$query_columns .= ',action';$query_values .= ',"'.$action.'"';}     
        if($priority){$query_columns .= ',priority';$query_values .= ',"'.$priority.'"';}     
        if($triga){$query_columns .= ',triga';$query_values .= ',"'.$triga.'"';}
        
        $query_columns .= ')';
        $query_values .= ')';
        $query .= $query_columns .' VALUES '. $query_values;  
        $wpdb->query( $query );     
    } 
    /**
    * Use this to log automated events and track progress in automated scripts.
    * Mainly used in schedule function but can be used in any functions called by add_action() or
    * other processing that is triggered by user events but not specifically related to what the user is doing.
    * 
    * @param mixed $outcome
    * @param mixed $trigger schedule, hook (action hooks such as text spinning could be considered automation), cron, url, user (i.e. user does something that triggers background processing)
    * @param mixed $line
    * @param mixed $file
    * @param mixed $function
    */
    public function log_schedule($comment,$action,$outcome,$category = 'scheduledeventaction',$trigger = 'autoschedule',$line = 'NA',$file = 'NA',$function = 'NA')
    {
        $atts = array();   
        $atts['logged'] = VideoBlogger_WP::datewp();
        $atts['comment'] = $comment;
        $atts['action'] = $action;
        $atts['outcome'] = $outcome;
        $atts['category'] = $category;
        $atts['line'] = $line;
        $atts['file'] = $file;
        $atts['function'] = $function;
        $atts['trigger'] = $function;
        // set log type so the log entry is made to the required log file
        $atts['type'] = 'automation';
        VideoBlogger_WP::newlog($atts);    
    }       
    /**
    * Cleanup log table - currently keeps 2 days of logs
    */
    public function log_cleanup()
    {     
        if(VideoBlogger_WPDB::database_table_exist('wtgvblog')){
            global $wpdb;
            $twodays_time = strtotime('2 days ago midnight');
            $twodays = date("Y-m-d H:i:s",$twodays_time);
            $wpdb->query( 
                "
                    DELETE FROM wtgvblog
                    WHERE timestamp < '".$twodays."'
                "
            );
        }
    }
    public function send_email($recipients,$subject,$content,$content_type = 'html')
    {                          
        if($content_type == 'html')
        {
            add_filter( 'wp_mail_content_type', 'wtgvb_set_html_content_type' );
        }
        
        $result = wp_mail( $recipients, $subject, $content );

        if($content_type == 'html')
        {    
            remove_filter( 'wp_mail_content_type', 'wtgvb_set_html_content_type' );  
        }   
        
        return $result;
    }    
    /**
    * Creates url to an admin page
    *  
    * @param mixed $page, registered page slug i.e. wtgvb_install which results in wp-admin/admin.php?page=wtgvb_install   
    * @param mixed $values, pass a string beginning with & followed by url values
    */
    public function url_toadmin($page,$values = '')
    {                                  
        return get_admin_url() . 'admin.php?page=' . $page . $values;
    }
    /**
    * Adds <button> with jquerybutton class and </form>, for using after a function that outputs a form
    * Add all parameteres or add none for defaults
    * @param string $buttontitle
    * @param string $buttonid
    */
    public function formend_standard($buttontitle = 'Submit',$buttonid = 'notrequired')
    {
            if($buttonid == 'notrequired'){
                $buttonid = 'wtgvb_notrequired'.rand(1000,1000000);# added during debug
            }else{
                $buttonid = $buttonid.'_formbutton';
            }?>

            <p class="submit">
                <input type="submit" name="wtgvb_wpsubmit" id="<?php echo $buttonid;?>" class="button button-primary" value="<?php echo $buttontitle;?>">
            </p>

        </form><?php
    }
    /**
     * Echos the html beginning of a form and beginning of widefat post fixed table
     * 
     * @param string $name (a unique value to identify the form)
     * @param string $method (optional, default is post, post or get)
     * @param string $action (optional, default is null for self submission - can give url)
     * @param string $enctype (pass enctype="multipart/form-data" to create a file upload form)
     */
    public function formstart_standard($name,$id = 'none', $method = 'post',$class,$action = '',$enctype = '')
    {
        if($class){
            $class = 'class="'.$class.'"';
        }else{
            $class = '';         
        }
        echo '<form '.$class.' '.$enctype.' id="'.$id.'" method="'.$method.'" name="wtgvb_request_'.$name.'" action="'.$action.'">
        <input type="hidden" id="wtgvb_post_requested" name="wtgvb_post_requested" value="true">';
    }    
    /**
    * Displays the status of the content folder with buttons to delete or create the folder
    * 
    * @param mixed $logtype
    */
    public function contentfolder_display_status()
    {
        $contentfolder_exists = file_exists(WTG_VB_CONTENTFOLDER_DIR);
        
        if($contentfolder_exists){

            echo VideoBlogger_Notice::notice_depreciated('Content folder exists'.
            VideoBlogger_WP::formstart_standard('wtgvb_deletecontentfolder_form','none','post','').'
                <button class="button" name="wtgvb_contentfolder_delete">Delete</button>                        
            </form>', 'success', 'Small', false,'','return');

        }elseif(!$contentfolder_exists){
            echo VideoBlogger_Notice::notice_depreciated('Content folder does not exist please create it'.
            VideoBlogger_WP::formstart_standard('wtgvb_createcontentfolder_form','none','post','').'
                <button class="button" name="wtgvb_contentfolder_create">Create</button>        
            </form>', 'error', 'Small', false,'','return');
        }
    }
    /**
    * Adds Script Start and Stylesheets to the beginning of pages
    */
    public function pageheader($pagetitle,$layout)
    {
        global $current_user,$wtgvb_mpt_arr,$wtgvb_settings,$wtgvb_pub_set,$wtgvb_is_free;

        $wtgvb_settings = VideoBlogger::adminsettings();?>
                    
        <div class="wrap">
            <?php        
            // run diagnostics
            VideoBlogger_Core::diagnostics_constant();
            ?>
        
            <div id="icon-options-general" class="icon32"><br /></div>
            
            <h2><?php echo $pagetitle;?></h2>

            <?php       
            // run specific admin triggered automation tasks, this way an output can be created for admin to see
            wtgvb_admin_triggered_automation();  

            // check existing plugins and give advice or warnings
            VideoBlogger_WP::conflict_prevention();
                     
            // display form submission result notices
            VideoBlogger_Notice::output_depreciated();// now using display_all();
            VideoBlogger_Notice::display_all();   
          
            // process global security and any other types of checks here such such check systems requirements, also checks installation status
            $wtgvb_requirements_missing = VideoBlogger_WP::check_requirements(true);?>

            <div class="postbox-container" style="width:99%">
                <div class="metabox-holder">
                    <div class="meta-box-sortables"><?php
    }    
    /**
    * Displays author and adds some required scripts
    */
    public function footer()
    {?>
                    </div><!-- end of post boxes -->
                </div><!-- end of post boxes -->
            </div><!-- end of post boxes -->
        </div><!-- end of wrap - started in header -->

        <script type="text/javascript">
            // <![CDATA[
            jQuery('.postbox div.handlediv').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
            jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
            jQuery('.postbox.close-me').each(function(){
            jQuery(this).addClass("closed");
            });
            //-->
         </script><?php
    }    
    /**
    * Gets tab menu array
    */
    public function tabmenu()
    {
        global $wtgvb_settings,$wtgvb_menu_source;# this is coming from the loaded array file
        // if load method not set and global is an array return the global
        if(isset($wtgvb_settings['tabmenu']['loadmethod']) && $wtgvb_settings['tabmenu']['loadmethod'] == 'file'
        || $wtgvb_menu_source == 'file'){     
            require_once(WTG_VB_PATH . 'views/wtgvb_menu_array.php');
            return $wtgvb_mpt_arr;
                          
        }else{

            // load from option array by default ($wtgvb_menu_source over-rides this to load from file)
            // but only return value if its a valid array else we install the admin settings array now        
            $result = VideoBlogger_WP::option('wtgvb_tabmenu','get');
            if(is_array($result) && isset($result['menu'])){# if the new ['menu'] is not in array we re-install
                return $result;
            }else{
                // users wants menu to load from stored option value but it returned an invald value
                return wtgvb_INSTALL_tabmenu_settings();# returns the tabmenu array
            }
        }        
    }    
    /**
    * Returns standard formatted form name
    * 
    * @param string $panel_name
    * @param string $specificpurpose, used to append a value, important when multiple forms used in a single panel
    */
    public function create_formname($panel_name,$specificpurpose = '')
    {
        return 'wtgvb_form_name_' . $panel_name . $specificpurpose;    
    } 
    /**
    * Returns a standard formatted form ID
    * 
    * @param string $panel_name
    * @param string $specificpurpose, used to append a value, important when multiple forms used in a single panel 
    */
    public function create_formid($panel_name,$specificpurpose = '')
    {
        return 'wtgvb_form_id_' . $panel_name . $specificpurpose;
    }
    /**
    * <table class="widefat">
    * Allows control over all table
    * 
    */
    public function tablestart($class = false)
    {
        if(!$class){$class = 'widefat';}
        echo '<table class="'.$class.'">';    
    }   
    /**
    * List of notification boxes displaying folders created by Video Blogger.
    */
    public function list_folders()
    {
        if(file_exists(WTG_VB_CONTENTFOLDER_DIR)){?>
            <script language="JavaScript">
            function wtgvb_deletefolders_checkboxtoggle(source) {
              checkboxes = document.getElementsByName('wtgvb_deletefolders_array[]');
              for(var i in checkboxes)
                checkboxes[i].checked = source.checked;
            }
            </script>
            <input type="checkbox" onClick="wtgvb_deletefolders_checkboxtoggle(this)" /> Select All Folders<br/>
            <?php echo VideoBlogger_Notice::notice_depreciated('<input type="checkbox" name="wtgvb_deletefolders_array[]" value="videobloggercontent" /> 1. videobloggercontent','success','Tiny','','','return');                    
        }
    }
    /**
    * List of notification boxes displaying core plugin tables
    */
    public function list_plugintables()
    {
        global $wtgvb_tables_array;?>

        <script language="JavaScript">
        function wtgvb_deletecoretables_checkboxtoggle(source) {
          checkboxes = document.getElementsByName('wtgvb_deletecoretables_array[]');
          for(var i in checkboxes)
            checkboxes[i].checked = source.checked;
        }
        </script>

        <input type="checkbox" onClick="wtgvb_deletecoretables_checkboxtoggle(this)" /> Select All Tables<br/>

        <?php 
        $count = 0;
        foreach($wtgvb_tables_array['tables'] as $key => $table){
            if(VideoBlogger_WPDB::does_table_exist($table['name'])){
                ++$count;
                echo VideoBlogger_Notice::notice_depreciated('<input type="checkbox" name="wtgvb_deletecoretables_array[]" value="'.$table['name'].'" /> ' . $count . '. ' . $table['name'],
                'success','Tiny','','','return');               
            }
        }
        
        if($count == 0){echo '<p>'. __('There are no core tables installed right now.', 'video-blogger') .'</p>';}
    }     
    /**
    * Displays a table of wtgvb_option records with ability to view their value or delete them
    * @param boolean $form true adds checkbox object to each option record (currently used on uninstall panel) 
    */
    public function list_optionrecordtrace($form = false,$size = 'Small',$optiontype = 'all')
    {    
        // first get all records that begin with wtgvb_
        $videobloggerrecords_result = VideoBlogger_WPDB::options_beginning_with('wtgvb_');
        $counter = 1;?>
            
            <script language="JavaScript">
            function wtgvb_deleteoptions_checkboxtoggle(source) {
              checkboxes = document.getElementsByName('wtgvb_deleteoptions_array[]');
              for(var i in checkboxes)
                checkboxes[i].checked = source.checked;
            }
            </script>

        <?php   

        if($form){
            echo '<input type="checkbox" onClick="wtgvb_deleteoptions_checkboxtoggle(this)" /> Select All Options<br/>';
        }
        
        $html = '';
                        
        foreach($videobloggerrecords_result as $key => $option ){
            
            if($form){
                $html = '<input type="checkbox" name="wtgvb_deleteoptions_array[]" value="'.$option.'" />';
            }
            
            echo VideoBlogger_Notice::notice_depreciated($html . ' ' . $counter . '. ' . $option,'success',$size,'','','return');
            
            ++$counter;
        }
    } 
    public function screens_menuoptions($current)
    {
        global $wtgvb_mpt_arr;
        foreach($wtgvb_mpt_arr['menu'] as $page_slug => $page_array){ 
            foreach($wtgvb_mpt_arr['menu'][$page_slug]['tabs'] as $whichvalue => $screen_array){
                $selected = '';
                if($screen_array['slug'] == $current){
                    $selected = 'selected="selected"';    
                }             
                echo '<option value="'.$screen_array['slug'].'" '.$selected.'>'.$screen_array['label'].'</option>'; 
            }
        }    
    }
    public function page_menuoptions($current)
    {
        global $wtgvb_mpt_arr;
        foreach($wtgvb_mpt_arr['menu'] as $page_slug => $page_array){ 
            $selected = '';
            if($page_slug == $current){
                $selected = 'selected="selected"';    
            } 
            echo '<option value="'.$page_slug.'" '.$selected.'>'.$wtgvb_mpt_arr['menu'][$page_slug]['title'].'</option>';
        }    
    }
    public function screenintro($wtgvb_page_name,$text)
    {
        global $wtgvb_is_beta;
        if(current_user_can('activate_plugins') && !$wtgvb_is_beta){
            echo '
            <div class="wtgvb_screenintro_container">
                <div class="welcome-panel">
                    <h3>'. ucfirst($wtgvb_page_name) .' Introduction</h3>
                    <div class="welcome-panel-content">
                        <p class="about-description">'. ucfirst($text) .'...</p>                                                             
                    </div>
                </div> 
            </div>';  
        }
    }
    /**
    * Add hidden form fields, to help with processing and debugging
    * Adds the _form_processing_required value, required to call the form validation file
    *
    * @param integer $pageid (the id used in page menu array)
    * @param slug $panel_name (panel name form is in)
    * @param string $panel_title (panel title form is in)
    * @param integer $panel_number (the panel number form is in),(tab number passed instead when this function called for support button row)
    * @param integer $step (1 = confirm form, 2 = process request, 3+ alternative processing)
    */
    public function hidden_form_values($form_name,$form_title)
    {
        global $wtgvb_page_name,$wtgvb_tab_number;
        
        $form_name = lcfirst($form_name);
        wp_nonce_field($form_name); 
        echo '<input type="hidden" name="wtgvb_post_requested" value="true">';
        echo '<input type="hidden" name="wtgvb_hidden_tabnumber" value="'.$wtgvb_tab_number.'">';
        echo '<input type="hidden" name="wtgvb_hidden_pagename" value="'.$wtgvb_page_name.'">';
        echo '<input type="hidden" name="wtgvb_form_name" value="'.$form_name.'">';
        echo '<input type="hidden" name="wtgvb_form_title" value="'.$form_title.'">';
    }
    /**
    * Checks if the cores minimum requirements are met and displays notices if not
    * Checks: Internet Connection (required for jQuery), PHP version, Soap Extension
    */
    public function check_requirements($display){
        // variable indicates message being displayed, we will only show 1 message at a time
        $requirement_missing = false;

        // php version
        if(defined(WTG_VB_PHPVERSIONMINIMUM)){
            if(WTG_VB_PHPVERSIONMINIMUM > phpversion()){
                $requirement_missing = true;
                if($display == true){
                    VideoBlogger_Notice::notice_depreciated(sprintf(__('The plugin detected an older PHP version than the minimum requirement which 
                    is %s. You can requests an upgrade for free from your hosting, use .htaccess to switch
                    between PHP versions per WP installation or sometimes hosting allows customers to switch using their control panel.','video-blogger'),WTG_VB_PHPVERSIONMINIMUM),
                    'warning','Large',sprintf(__('Video Blogger Requires PHP %s','video-blogger'),WTG_VB_PHPVERSIONMINIMUM));                
                }
            }
        }
        
        return $requirement_missing;
    }
    /**
    * Returns a value for Tab Number, if $_GET[WTG_##_ABB . 'tabnumber'] not set returns 0 
    */
    public function tabnumber(){                 
        if(isset($_POST['wtgvb_hidden_tabnumber']))
        {                    
            return $_POST['wtgvb_hidden_tabnumber'];    
        }                   
        elseif(isset($_GET['wtgvbtab']) && is_numeric($_GET['wtgvbtab']))
        {                  
            return $_GET['wtgvbtab'];
        }
        else
        {              
            return '0';                   
        }                                                      
    } 
    /**
    * Loads CSS for plugin not core
    * 
    * @param string $side, admin, public
    * @param mixed $wtgvb_css_side_override, makes use of admin lines in front-end of blog
    */
    public function css_core($side = 'admin',$wtgvb_css_side_override = false){        
        include_once(WTG_VB_PATH . '/css/wtgcore_css_parent.php');
    }     
    /**
    * Builds string of comma delimited table colum names
    */
    public function columns_string($table_name){
        $table_columns = wtgvb_WP_SQL_get_tablecolumns($table_name);
        $column_string = '';
        $first_column = true;
        
        while ($row_column = mysql_fetch_row($table_columns)) { 

            if(!$first_column){
                $column_string .= ',';
            } 
            
            $column_string .= $row_column[0];
            $first_column = false;
        }
        
        return $column_string;       
    }    
    /**
    * Removes special characters and converts to lowercase
    */
    public function clean_string($string){ 
        $string = preg_replace("/[^a-zA-Z0-9s]/", "", $string);
        $string = strtolower ( $string );
        return $string;
    }        
    /**       
     * Generates a username using a single value by incrementing an appended number until a none used value is found
     * @param string $username_base
     * @return string username, should only fail if the value passed to the function causes so
     * 
     * @todo log entry functions need to be added, store the string, resulting username
     */
    public function create_username($username_base){
        $attempt = 0;
        $limit = 500;// maximum trys - would we ever get so many of the same username with appended number incremented?
        $exists = true;// we need to change this to false before we can return a value

        // clean the string
        $username_base = preg_replace('/([^@]*).*/', '$1', $username_base );

        // ensure giving string does not already exist as a username else we can just use it
        $exists = username_exists( $username_base );
        if( $exists == false ){
            return $username_base;
        }else{
            // if $suitable is true then the username already exists, increment it until we find a suitable one
            while( $exists != false ){
                ++$attempt;
                $username = $username_base.$attempt;

                // username_exists returns id of existing user so we want a false return before continuing
                $exists = username_exists( $username );

                // break look when hit limit or found suitable username
                if($attempt > $limit || $exists == false ){
                    break;
                }
            }

            // we should have our login/username by now
            if ( $exists == false ) {
                return $username;
            }
        }
    }
    /**
    * Wrapper, uses wtgvb_url_toadmin to create local admin url
    * 
    * @param mixed $page
    * @param mixed $values 
    */
    public function create_adminurl($page,$values = ''){
        return VideoBlogger_WP::url_toadmin($page,$values);    
    }
    /**
    * Returns the plugins standard date (MySQL Date Time Formatted) with common format used in Wordpress.
    * Optional $time parameter, if false will return the current time().
    * 
    * @param integer $timeaddition, number of seconds to add to the current time to create a future date and time
    * @param integer $time optional parameter, by default causes current time() to be used
    */
    public function datewp($timeaddition = 0,$time = false){
        $thetime = time();
        if($time != false){$thetime = $time;}
        return date('Y-m-d H:i:s',$thetime + $timeaddition);    
    }    
    /**
    * Decides a tab screens required capability in order for dashboard visitor to view it
    * 
    * @param mixed $wtgvb_page_name the array key for pages
    * @param mixed $tab_key the array key for tabs within a page
    */
    public function get_tab_capability($wtgvb_page_name,$tab_key,$default = false){
        global $wtgvb_mpt_arr;
        $codedefault = 'activate_plugins';
        if(isset($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'][$tab_key]['permissions']['capability'])){
            return $wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'][$tab_key]['permissions']['capability'];    
        }else{
            return $codedefault;    
        }    
    }
    public function get_page_capability($wtgvb_page_name,$default = false){
        global $wtgvb_mpt_arr;
        $thisdefault = 'update_core';// script default for all outcomes

        // there is no capability (setup by users settings), so we check for the defaultcapability we have already hard coded as most suitable
        if(!isset($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['permissions']['capability'])){
            return $thisdefault;    
        }else{
            return $wtgvb_mpt_arr['menu'][$wtgvb_page_name]['permissions']['capability'];// our decided default    
        }

        return $thisdefault;   
    }
    public function get_installed_version(){
        return get_option('wtgvb_installedversion');    
    }  
    /**
    * Adds the opening divs for panels and help button
    * 
    * @param array $form_array
    */
    public function panel_header( $form_array, $boxintro_div = true ){
        global $wtgvb_settings;
                             
        // establish global panel state for all panels in plugin, done prior 
        $panel_state = ''; 
        if($panel_state){
            $panel_state = 'closed';    
        }    
              
        // override panel state if $form_array includes specific state
        if(isset($form_array['panel_state']) && ($form_array['panel_state'] == 1 || $form_array['panel_state'] == 'open'))
        {
            $panel_state = 'open';    
        }
        elseif(isset($form_array['panel_state']) && ($form_array['panel_state'] == 0 || $form_array['panel_state'] == 'closed'))
        {
            $panel_state = 'closed';
        }?>

        <div id="titles" class="postbox <?php echo $panel_state;?>">
            <div class="handlediv" title="Click to toggle"><br /></div>
            <h3 class="hndle"><span><?php echo $form_array['panel_title'];?></span></h3>
            <div class="inside"><?php
    }      
    /**
    * Adds closing divs for panels 
    */
    public function panel_footer(){
        echo '</div></div>';
    }
    /**
    * Returns array with common values required for forms that need jQuery dialog etc.
    * The default values can be overridden by populating the $jsform_set_override array. 
    * 
    * @param mixed $pageid
    * @param mixed $wtgvb_tab_number
    * @param mixed $panel_number
    * @param mixed $panel_name
    * @param mixed $panel_title
    * @param array $jsform_set_override, (not yet in use) use to customise the return value, not required in most uses
    */
    function jqueryform_commonarrayvalues($pageid,$wtgvb_tab_number,$panel_number,$panel_name,$panel_title,$jsform_set_override = '')
    {
        // $jsform_set_override
        // this is so we can pass the override array for custom settings rather than the default
        $jsform_set = array();
        // http://www.webtechglobal.co.uk/blog/wordpress/wtg-plugin-template/wtg-pt-jquery-dialogue-form 
        $jsform_set['pageid'] = $pageid;
        $jsform_set['tab_number'] = $wtgvb_tab_number; 
        $jsform_set['panel_number'] = $panel_number;
        $jsform_set['form_title'] = $panel_title;                
        // form related
        $jsform_set['form_id'] = VideoBlogger_WP::create_formid($panel_name);
        $jsform_set['form_name'] = VideoBlogger_WP::create_formname($panel_name);                                   
        return $jsform_set;
    }       
    /**
    * Initiates panel array values, some of which are also values changed by settings within this function and later in panel script
    */
    public function panel_array($pageid,$panel_number,$wtgvb_tab_number){
        $form_array = array();                             
        $form_array['panel_name'] = 'defaultpanelnamepleasechange';// slug to act as a name and part of the panel ID 
        $form_array['panel_number'] = $panel_number;// number of panels counted on page, used to create object ID
        $form_array['panel_title'] = __('Default Panel Title Please Change','video-blogger');// user seen panel header text 
        $form_array['pageid'] = $pageid;// must be set on panels lines
        $form_array['tabnumber'] = $wtgvb_tab_number; 
        $form_array['panel_id'] = $form_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
        $form_array['form_button'] = 'Submit';
        $form_array['dialogdisplay'] = false; // yes or no - this value when used over-rides the global setting for hiding all dialogue
        return $form_array;   
    }
    /**
    * Use to start a new result array which is returned at the end of a function. It gives us a common set of values to work with.

    * @uses VideoBlogger_PHP::arrayinfo_set()
    * @param mixed $description use to explain what array is used for
    * @param mixed $line __LINE__
    * @param mixed $function __FUNCTION__
    * @param mixed $file __FILE__
    * @param mixed $reason use to explain why the array was updated (rather than what the array is used for)
    * @return string
    */                                   
    public function result_array($description,$line,$function,$file){
        $array = VideoBlogger_PHP::arrayinfo_set(array(),$line,$function,$file);
        $array['description'] = $description;
        $array['outcome'] = true;// boolean
        $array['failreason'] = false;// string - our own typed reason for the failure
        $array['error'] = false;// string - add php mysql wordpress error 
        $array['parameters'] = array();// an array of the parameters passed to the function using result_array, really only required if there is a fault
        $array['result'] = array();// the result values, if result is too large not needed do not use
        return $array;
    }         
    /**
    * Get arrays next key (only works with numeric key)
    */
    public function get_array_nextkey($array){
        if(!is_array($array)){
            return 1;   
        }
        
        ksort($array);
        end($array);
        return key($array) + 1;
    }
    /**
    * Gets the schedule array from wordpress option table.
    * Array [times] holds permitted days and hours.
    * Array [limits] holds the maximum post creation numbers 
    */
    public function get_option_schedule_array(){
        $wtgvb_schedule_array = get_option( 'wtgvb_schedule');
        return maybe_unserialize($wtgvb_schedule_array);    
    }
    /**
    * Builds text link, also validates it to ensure it still exists else reports it as broken
    * 
    * The idea of this function is to ensure links used throughout the plugins interface
    * are not broken. Over time links may no longer point to a page that exists, we want to 
    * know about this quickly then replace the url.
    * 
    * @return $link, return or echo using $response parameter
    * 
    * @param mixed $text
    * @param mixed $url
    * @param mixed $htmlentities, optional (string of url passed variables)
    * @param string $target, _blank _self etc
    * @param string $class, css class name (common: button)
    * @param strong $response [echo][return]
    */
    public function link($text,$url,$htmlentities = '',$target = '_blank',$class = '',$response = 'echo',$title = ''){
        // add ? to $middle if there is no proper join after the domain
        $middle = '';
                                 
        // decide class
        if($class != ''){$class = 'class="'.$class.'"';}
        
        // build final url
        $finalurl = $url.$middle.htmlentities($htmlentities);
        
        // check the final result is valid else use a default fault page
        $valid_result = VideoBlogger_PHP::validate_url($finalurl);
        
        if($valid_result)
        {
            $link = '<a href="'.$finalurl.'" '.$class.' target="'.$target.'" title="'.$title.'">'.$text.'</a>';
        }
        else
        {
            $link = '<a href="http://www.webtechglobal.co.uk/blog/help/invalid-application-link" target="_blank">'. __('Invalid Link Please Click Here To Report This','video-blogger') .'</a>';        
        }
        
        if($response == 'echo'){
            echo $link;
        }else{
            return $link;
        }     
    }     
    /**
    * Updates the schedule array from wordpress option table.
    * Array [times] holds permitted days and hours.
    * Array [limits] holds the maximum post creation numbers 
    */
    public function update_option_schedule_array($schedule_array){
        $schedule_array_serialized = maybe_serialize($schedule_array);
        return update_option('wtgvb_schedule',$schedule_array_serialized);    
    }
    public function update_settings($wtgvb_settings){
        $admin_settings_array_serialized = maybe_serialize($wtgvb_settings);
        return update_option('wtgvb_settings',$admin_settings_array_serialized);    
    }
    /**
    * Returns Wordpress version in short
    * 1. Default returned example by get_bloginfo('version') is 3.6-beta1-24041
    * 2. We remove everything after the first hyphen
    */
    public function get_wp_version(){
        $longversion = get_bloginfo('version');
        return strstr( $longversion , '-', true );
    }
    /**
    * Determines if the giving value is a Video Blogger page or not
    */
    public function is_plugin_page($page){
        return strstr($page,'videoblogger');  
    }
    /**
     * Tabs menu loader - calls function for css only menu or jquery tabs menu
     * 
     * @param string $thepagekey this is the screen being visited
     */
    public function createmenu($wtgvb_page_name){           
        global $wtgvb_mpt_arr;
        
        echo '<h2 class="nav-tab-wrapper">';
            
        foreach($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'] as $tab=>$values){

            $tabslug = $wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'][$tab]['slug'];
            $tablabel = $wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'][$tab]['label'];  

            if(VideoBlogger_WP::should_tab_be_displayed($wtgvb_page_name,$tab)){
            
                if(!isset($_GET['wtgvbtab']) && $tab == 0){
                    $activeclass = 'class="nav-tab nav-tab-active"';
                }else{
                    $activeclass = 'class="nav-tab"';
                }                            
                
                if(isset($_GET['wtgvbtab']) && $_GET['wtgvbtab'] == $tab){$activeclass = 'class="nav-tab nav-tab-active"';}
                echo '<a href="'.VideoBlogger_WP::create_adminurl($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['slug']).'&wtgvbtab='.$tab.'" '.$activeclass.'>' . $tablabel . '</a>';       

            }
        }      
        
        echo '</h2>';
    }    
    /**
    * Determines if giving tab for the giving page should be displayed or not based on current user.
    * 
    * Checks for reasons not to display and returns false. If no reason found to hide the tab then true is default.
    * 
    * @param mixed $page
    * @param mixed $tab
    * 
    * @return boolean
    */
    public function should_tab_be_displayed($page,$tab){
        global $wtgvb_mpt_arr,$wtgvb_is_free,$wtgvb_is_beta;

        if(isset($wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['permissions']['capability'])){
            $boolean = current_user_can( $wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['permissions']['capability'] );
            if($boolean ==  false){
                return false;
            }
        }

        // if screen not active
        if(isset($wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['active']) && $wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['active'] == false){
            return false;
        }    
        
        // if screen is not active at all (used to disable a screen in all packages and configurations)
        if(isset($wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['active']) && $wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['active'] == false){
            return false;
        }
                   
        // display screen if the package not set, just to be safe as the package value should also be set if menu array installed properly
        if(isset($wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['package'])){      
            
            if($wtgvb_is_free && $wtgvb_mpt_arr['menu'][$page]['tabs'][$tab]['package'] == 'paid'){   
                return false;
            } 
        } 
                     
        return true;      
    } 
    /**
    * includes the default screen file for the current user, either admin or subscriber/user
    */
    public function include_default_screen($wtgvb_page_name){
        global $wtgvb_mpt_arr;
        if(current_user_can( 'activate_plugins' )){ 
            
            foreach($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'] as $tab_number => $tab_array){       
                if(isset($tab_array['admindefault']) && $tab_array['admindefault'] == true){   
                    include($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'][$tab_number]['path']);    
                }    
            }
                
        }else{
            
            foreach($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'] as $tab_number => $tab_array){
                if(isset($tab_array['userdefault']) && $tab_array['userdefault'] == true){
                    include($wtgvb_mpt_arr['menu'][$wtgvb_page_name]['tabs'][$tab_number]['path']);    
                }    
            }
                    
        }
    }
    /**
    * Builds a nonced admin link styled as button by Wordpress
    *
    * @package Video Blogger
    * @since 0.0.2
    *
    * @return string html a href link nonced by Wordpress  
    */
    public function linkaction($tab,$page,$action,$title = 'Video Blogger admin link',$text = 'Click Here',$values = ''){
        return '<a href="'. wp_nonce_url( admin_url() . 'admin.php?page=' . $page . '&wtgvbprocess='.$action.'&wtgvbtab=' . $tab . $values, $action ) . '" title="'.$title.'" class="button">'.$text.'</a>';
    }
    /**
    * Get POST ID using post_name (slug)
    * 
    * @param string $name
    * @return string|null
    */
    public function get_post_ID_by_postname($name){
        global $wpdb;
        // get page id using custom query
        return $wpdb->get_var("SELECT ID 
        FROM $wpdb->posts 
        WHERE post_name = '".$name."' 
        AND post_type='page' ");
    }       
    /**
    * Returns all the columns in giving database table that hold data of the giving data type.
    * The type will be determined with PHP not based on MySQL column data types. 
    * 1. Table must have one or more records
    * 2. 1 record will be queried 
    * 3. Each columns values will be tested by PHP to determine data type
    * 4. Array returned with column names that match the giving type
    * 5. If $dt is false, all columns will be returned with their type however that is not the main purpose of this function
    * 6. Types can be custom, using regex etc. The idea is to establish if a value is of the pattern suitable for intended use.
    * 
    * @param string $tableName table name
    * @param string $dataType data type URL|IMG|NUMERIC|STRING|ARRAY
    * 
    * @returns false if no record could be found
    */
    public function cols_by_datatype($tableName,$dataType = false)
    {
        global $wpdb;
        
        $ra = array();// returned array - our array of columns matching data type
        $matchCount = 0;// matches
        $ra['arrayinfo']['matchcount'] = $matchCount;

        $rec = $wpdb->get_results( 'SELECT * FROM '. $tableName .'  LIMIT 1',ARRAY_A);
        if(!$rec){return false;}
        
        $knownTypes = array();
        foreach($rec as $id => $value_array){
            foreach($value_array as $column => $value){     
                             
                $isURL = VideoBlogger_PHP::is_url($value);
                if($isURL){++$matchCount;$ra['matches'][] = $column;}
           
            }       
        }
        
        $ra['arrayinfo']['matchcount'] = $matchCount;
        return $ra;
    }  
    public function querylog_bytype($type = 'all',$limit = 100)
    {
        global $wpdb;

        // where
        $where = '';
        if($type != 'all'){
          $where = 'WHERE type = "'.$type.'"';
        }

        // limit
        $limit = 'LIMIT ' . $limit;
        
        // get_results
        $rows = $wpdb->get_results( 
        "
        SELECT * 
        FROM wtgvb_log
        ".$where."
        ".$limit."

        ",ARRAY_A);

        if(!$rows){
            return false;
        }else{
            return $rows;
        }
    }  
    /**
    * Determines if all tables in a giving array exist or not
    * @returns boolean true if all table exist else false if even one does not
    */
    public function tables_exist($tables_array)
    {
        if($tables_array && is_array($tables_array)){         
            // foreach table in array, if one does not exist return false
            foreach($tables_array as $key => $table_name){
                $table_exists = VideoBlogger_WPDB::does_table_exist($table_name);  
                if(!$table_exists){          
                    return false;
                }
            }        
        }
        return true;    
    } 
    /**
    * Control Wordpress option functions using this single function.
    * This function will give us the opportunity to easily log changes and some others ideas we have.
    * 
    * @param mixed $option
    * @param mixed $action add, get, wtgget (own query function) update, delete
    * @param mixed $value
    * @param mixed $autoload used by add_option only
    */
    public function option($option,$action,$value = 'No Value',$autoload = 'yes')
    {
        if($action == 'add'){  
            return add_option($option,$value,'',$autoload);            
        }elseif($action == 'get'){
            return get_option($option);    
        }elseif($action == 'update'){        
            return update_option($option,$value);
        }elseif($action == 'delete'){
            return delete_option($option);        
        }
    }
    /**
    * Stores the last known reason why auto event was refused during checks in event_check()
    */
    public function event_return($return_reason)
    {
        global $wtgvb_schedule_array;
        $wtgvb_schedule_array['history']['lastreturnreason'] = $return_reason;
        VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);   
    }  
    /**
    * Uses wp-admin/includes/image.php to store an image in Wordpress files and database.
    * 
    * @uses wp_insert_attachment()
    * @param mixed $imageurl
    * @param mixed $postid
    * @return boolean false on fail else $thumbid which is stored in post meta _thumbnail_id
    */
    function create_localmedia( $url, $postid )
    {
        $photo = new WP_Http();
        $photo = $photo->request( $url );
        
        if(is_wp_error($photo)){  
            VideoBlogger_Flags::flagpost($postid,1,'thumbnail',sprintf(__('The URL giving to create a thumbnail for post with ID %s caused a Wordpress error. Please setup this posts thumbnail manually. Seek help from WebTechGlobal if this happens with too many posts.','video-blogger'),$postid));
            return false;
        }
              
        $attachment = wp_upload_bits( basename( $url ), null, $photo['body'], date("Y-m", strtotime( $photo['headers']['last-modified'] ) ) );
               
        $file = $attachment['file'];
                
        // get filetype
        $type = wp_check_filetype( $file, null );
                
        // build attachment object
        $att = array(
            'post_mime_type' => $type['type'],
            'post_content' => '',
            'guid' => $url,
            'post_parent' => null,
            'post_title' => preg_replace('/\.[^.]+$/', '', basename( $attachment['file'] )),
        );
        
        // action insert attachment now
        $attach_id = wp_insert_attachment($att, $file, $postid);

        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        
        wp_update_attachment_metadata( $attach_id,  $attach_data );

        return $attach_id;
    }
    /**
    * First function to adding a post thumbnail 

    * @param mixed $overwrite_existing, if post already has a thumbnail do we want to overwrite it or leave it
    */
    public function create_post_thumbnail($post_id,$image_url,$overwrite_existing = false)
    {
        global $wpdb;

        if(!file_is_valid_image($image_url)){  
            VideoBlogger_Flags::flagpost($post_id,1,'thumbnail',__('The URL giving to create this posts thumbnail caused a Wordpress error. Please setup this posts thumbnail manually. If this happens to many posts please troubleshoot on the WebTechGlobal website.','video-blogger'));    
            return false;
        }
             
        // if post has existing thumbnail
        if($overwrite_existing == false){
            if ( get_post_meta( $post_id, '_thumbnail_id', true) || get_post_meta( $post_id, 'skip_post_thumb', true ) ) {
                return false;
            }
        }
        
        // call action function to create the thumbnail in wordpress gallery 
        $thumbid = VideoBlogger_WP::create_localmedia( $image_url, $post_id );

        // update post meta with new thumbnail
        if ( is_numeric($thumbid) ) {
            update_post_meta( $post_id, '_thumbnail_id', $thumbid );
        }else{
            return false;
        }
    }
    public function form_action()
    {
        echo VideoBlogger_WP::url_toadmin($_GET['page'],'&wtgvbtab='.$_GET['wtgvbtab']);    
    }                                     
}// end class VideoBlogger_WP

?>
