<?php
/** 
* Wordpress plugin notice, messages and prompts
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

class VideoBlogger_Notice {
    public function get_notice_array(){
        $a = get_option('wtgvb_notifications');
       
        $wtgvb_notice_array = maybe_unserialize($a);
        if(!is_array($wtgvb_notice_array)){
            return array();    
        }
        
        // delete some expired admin notices
        if(isset($wtgvb_notice_array['admin']))
        {
            foreach($wtgvb_notice_array['admin'] as $key => $notice){
                
                if(isset($notice['created']))
                {
                    $projected = $notice['created'] + 60;
                    
                    if($projected < time())
                    {
                        unset($wtgvb_notice_array['admin'][$key]);    
                    }                                               
                }
                else
                {       
                    unset($wtgvb_notice_array['admin'][$key]);
                }
            }
        }
      
        // delete some expired user notices
        if(isset($wtgvb_notice_array['users'])){
 
            foreach($wtgvb_notice_array['users'] as $owner_id => $owners_notices){
                
                foreach($owners_notices as $key => $notice)
                {
                    if(isset($notice['created']))
                    {
                        $projected = $notice['created'] + 60;
                        
                        if($projected < time())
                        {
                            unset($wtgvb_notice_array['users'][$key]);    
                        }                                               
                    }
                    else
                    {
                        unset($wtgvb_notice_array['users'][$key]);
                    }
                }
            }   
        }     
           
        // any notices unset due to expiry will be reflected in update during display procedure        
        return $wtgvb_notice_array;    
    }
    public function update_notice_array(){
        global $wtgvb_notice_array;      
        return update_option('wtgvb_notifications',maybe_serialize($wtgvb_notice_array));    
    }     
    /**
    * use to create an in-content notice i.e. the notice is built and echoed straight away it is not
    * stored within an array for output at a later point in the plugin or Wordpress loading 
    */
    public function basic_html($type,$size,$title = false,$message = 'no message has been set'){
        $output = '<div class="'.$type.$size.'">';
        if($size != 'Tiny' && $title !== false){$output .= '<h4>'.$title.'</h4>';}
        $output .= '<p>' . ucfirst($message) . '</p>';    
        $output .= '</div>';
        echo $output;        
    }
    /**
    * A notice is meant to be instant and for the current user only
    * a) use create_prompt() to get an action from the current user based on conditions i.e. configuration issue
    * b) use create_message() to create a notice for another user for when they login 
    * 
    * @param mixed $message
    * @param mixed $type
    * @param mixed $size
    * @param mixed $title
    * @param mixed $sensitive
    */
    public function create_notice($message,$type = 'info',$size = 'Small',$title = false,$sensitive = false)
    {
        global $wtgvb_notice_array,$current_user;
        
        // return if no current user, this allows us to use create_notice() in functions which are used in both manual and automated procedures                             
        if(!isset($current_user->ID) || !is_numeric($current_user->ID)){return false;}
                                                 
        $key = time() . rand(10000,99999);

        if($sensitive === false)
        {   
            $wtgvb_notice_array['notices'][$current_user->ID][$key]['sensitive'] = false;
            $wtgvb_notice_array['notices'][$current_user->ID][$key]['message'] = $message;
            $wtgvb_notice_array['notices'][$current_user->ID][$key]['type'] = $type;
            $wtgvb_notice_array['notices'][$current_user->ID][$key]['size'] = $size;
            $wtgvb_notice_array['notices'][$current_user->ID][$key]['title'] = $title; 
            $wtgvb_notice_array['notices'][$current_user->ID][$key]['created'] = time();
        }                   
        elseif($sensitive === true)
        {
            $wtgvb_notice_array['users'][$current_user->ID][$key]['sensitive'] = false;
            
            # to be complete, this procedure will store the notice in user meta with the $key being used in meta_key
            # even when we start using a database table to store all notices, we will do this to avoid that table
            # holding sensitive data. Instead we will try to group subscriber/customer data where some already exists. 
                                    
        }

        return VideoBlogger_Notice::update_notice_array($wtgvb_notice_array);
    }
    /**
    * prompt are sticky by default, requiring a users action 
    */
    public function create_prompt()
    {
        ## $wtgvb_notice_array['prompts'][$owner][$key]['message'] = $message;    
    }
    /**
    * use to create a notice for a specific user and not usually the current user (in effect sending a message to them that instantly displays on their screen)
    * a) admin actions can cause a message to be created so subscribers are notified the moment they login
    * b) client actions within their account i.e. invoice request, can create a message for the lead developer of clients project 
    */
    public function create_message()
    {
        ## $wtgvb_notice_array['messages'][$owner][$key]['message'] = $message;    
    }
    public function display_all()
    {
        VideoBlogger_Notice::display_users_notices();    
    }
    public function display_users_notices()
    {
        global $wtgvb_notice_array,$current_user;
        
        $wtgvb_notice_array = VideoBlogger_Notice::get_notice_array();
        
        if(!isset($wtgvb_notice_array['notices'][$current_user->ID])){return;}
 
        foreach($wtgvb_notice_array['notices'][$current_user->ID] as $key => $owners_notices){

            if(isset($owners_notices['sensitive']) && $owners_notices['sensitive'] === true)
            {
                # to be complete - this will be used for sensitive information 
                # this method will retrieve data from user meta, storage procedure to be complete    
            }   
            else
            {
                VideoBlogger_Notice::basic_html($owners_notices['type'],$owners_notices['size'],$owners_notices['title'],$owners_notices['message']);    
            }   

            // users notices have been displayed, unset to prevent second display
            unset($wtgvb_notice_array['notices'][$current_user->ID]);
        }
                                       
        VideoBlogger_Notice::update_notice_array($wtgvb_notice_array);            
    }
    
    ################################################
    #            OLD FUNCTIONS BELOW HERE          #
    ################################################
    
    /**
    * Returns notification HTML.
    * This function has the html and css to make all notifications standard.
    * 
    * @deprecated please use alternative functions in the notice class
    * 
    * @param mixed $type
    * @param string $helpurl
    * @param string $size
    * @param string $title
    * @param string $message
    * @param bool $clickable
    * @param mixed $persistent
    * @param mixed $id, used for persistent messages which use jQuery UI button, the ID should be the notice ID
    */
    public function notice_display_depreciated($type,$helpurl,$size,$title,$message,$clickable,$persistent = false,$id = false)
    {
        // begin building output
        $output = '';
                        
        // if clickable (only allowed when no other links being used) - $helpurl will actually be a local url to another plugin or Wordpress page
        if($clickable){
            $output .= '<div class="stepLargeTest"><a href="'.$helpurl.'">';
        }

        // start div
        $output .= '<div class="'.$type.$size.'">';     
       
        // set h4 when required
        if($size != 'Tiny'){$output .= '<h4>'.$title.'</h4>';}

        $output .= '<p>' . $message . '</p>';

        // if is not clickable (entire div) and help url is not null then display a clickable ico
        $thelink = '';
        if($helpurl != '' && $helpurl != false){
            //$output .= '<a class="jquerybutton" href="'.$helpurl.'" target="_blank">Get Help</a>';
        }   
            
        // complete notice with closing div
        $output .= '</div>';
        
        // end wrapping with link and styled div for making div clickable when required
        if($clickable){$output .= '</a></div>';}

        return $output;    
    }
    /**
    * Notice html is printed where this function is used 
    */
    public function n_incontent_depreciated($message,$type = 'info',$size = 'Small',$title = '',$helpurl = '')
    {
        echo VideoBlogger_Notice::notice_depreciated($message,$type,$size,$title, $helpurl, 'return');    
    }
    /**
    * Standard notice format to be displayed on submission of a form (Large).
    * Change the size if a plugin using core is to have a different format.
    * 
    * @deprecated use wtgvb_n_postresult as of 13th February 2013
    */
    public function notice_postresult_depreciated($type,$title,$message,$helpurl = false,$user = 'admin')
    {
        VideoBlogger_Notice::notice_depreciated($message,$type,'Large',$title, $helpurl, 'echo');    
    } 
    /**
    * New Notice Function (replacing VideoBlogger_Notice::notice_depreciated())
    * 1. None clickable (using a different function for that)
    * 2. Not a Step Notice (also using a different function for that)
    * 3. Does add link for help url though
    * 
    * @deprecated please use other functions in the Notice class
    */
    public function n_depreciated($title,$mes,$style,$size,$atts = array())
    {
        global $wtgvb_notice_array;
                    
        extract( shortcode_atts( array( 
            'url' => false,
            'output' => 'norm',// default normal echos html, return will return html, public also returns it but bypasses is_admin() etc
            'audience' => 'administrator',// admin or user (use to display a different message to visitors than to staff)
            'user_mes' => 'No user message giving',// only used when audience is set to user, user_mes replaces $mes
            'side' => 'private',// private,public (use to apply themes styles if customised, do not use for security)      
            'clickable' => false,// boolean
        ), $atts ) );
                  
        // do not allow a notice box if $output not stated as public and current visitor is not logged in
        // this forces backend messages which may be more private i.e. account info or key admin details
        if(!is_admin() && $output != 'public'){
            // visitor is on front-end, but the $output set is not for public
            return false; 
        }
                       
        // if return wanted or $side == public (used to bypass is_admin() check)
        // this allows the notice to be printed within content where the function is called rather than within the $wtgvb_notice_array loop
        if($output == 'return' || $output == 'public'){
            return VideoBlogger_Notice::notice_display_depreciated($style,$url,$size,$title,$mes,$clickable,$persistent = false);
        }
                       
        // if user is not an administrator and notice is for administrators only do not display
        if ( $audience == 'administrator' && !current_user_can( 'manage_options' ) ) {
            return false;
        }
                
        // arriving here means normal, most common output to the backend of Wordpress
        $wtgvb_notice_array = VideoBlogger_WP::persistentnotifications_array();
        
        // set next array key value
        $next_key = 0;

        // determine next array key
        if(isset($wtgvb_notice_array['notifications'])){    
            $next_key = VideoBlogger_WP::get_array_nextkey($wtgvb_notice_array['notifications']);
        }    
                       
        // add new message to the notifications array
        // this will be output during the current page loading. The notification will show once unless persistent is set to true
        $wtgvb_notice_array['notifications'][$next_key]['message'] = $mes;
        $wtgvb_notice_array['notifications'][$next_key]['type'] = $style;
        $wtgvb_notice_array['notifications'][$next_key]['size'] = $size;
        $wtgvb_notice_array['notifications'][$next_key]['title'] = $title;
        $wtgvb_notice_array['notifications'][$next_key]['helpurl'] = $url; 
        $wtgvb_notice_array['notifications'][$next_key]['output'] = $output;
        $wtgvb_notice_array['notifications'][$next_key]['audience'] = $audience;
        $wtgvb_notice_array['notifications'][$next_key]['user_mes'] = $user_mes;
        $wtgvb_notice_array['notifications'][$next_key]['side'] = $side;
        $wtgvb_notice_array['notifications'][$next_key]['clickable'] = $clickable;
                          
        VideoBlogger_Notice::update_persistentnotifications_array($wtgvb_notice_array);
    } 
    /**
    * Display a standard notification after form submission.
    * This function simply helps to quickly apply a notice to the outcome while using the same
    * configuration for all form submissions.
    * 
    * @param mixed $type
    * @param mixed $title
    * @param mixed $message
    * @param mixed $helpurl
    * @param mixed $user
    */
    public function n_postresult_depreciated($type,$title,$message,$helpurl = false,$user = 'admin')
    {  
        VideoBlogger_Notice::n_depreciated($title,$message,$type,'Large');  
    }
    /**
    * Updates notifications array in Wordpress options table
    * 
    * @param array $notifications_array
    * @return bool
    */
    public function update_persistentnotifications_array($notifications_array)
    {
        return update_option('wtgvb_notifications',maybe_serialize($notifications_array));    
    }
    /**
    * Creates a new notification with a long list of style options available.
    * 
    * @deprecated do not use this function
    */
    public function notice_depreciated($message,$type = 'success',$size = 'Extra',$title = false, $helpurl = 'www.videoblogger.com/support', $output_type = 'echo',$persistent = false,$clickable = false,$user_type = false){
        
        if(is_admin() || $output_type == 'public'){
            
            // change unexpected values into expected values (for flexability and to help avoid fault)
            if($type == 'accepted'){$type == 'success';}
            if($type == 'fault'){$type == 'error';}
            if($type == 'next'){$type == 'step';}
            
            // prevent div being clickable if help url giving (we need to more than one link in the message)
            if($helpurl != false && $helpurl != '' && $helpurl != 'http://www.videoblogger.com/support'){$clickable = false;}
                                     
            if($output_type == 'return' || $output_type == 'public'){   
                return VideoBlogger_Notice::notice_display_depreciated($type,$helpurl,$size,$title,$message,$clickable,$persistent);
            }else{
                global $wtgvb_notice_array;

                // establish next array key
                $next_key = 0;
                if(isset($wtgvb_notice_array['notifications'])){
                    $next_key = VideoBlogger_WP::get_array_nextkey($wtgvb_notice_array['notifications']);
                }
                
                // add new message to the notifications array
                $wtgvb_notice_array['notifications'][$next_key]['message'] = $message;
                $wtgvb_notice_array['notifications'][$next_key]['type'] = $type;
                $wtgvb_notice_array['notifications'][$next_key]['size'] = $size;
                $wtgvb_notice_array['notifications'][$next_key]['title'] = $title;
                $wtgvb_notice_array['notifications'][$next_key]['helpurl'] = $helpurl; 
                $wtgvb_notice_array['notifications'][$next_key]['clickable'] = $clickable; 
                          
                VideoBlogger_Notice::update_persistentnotifications_array($wtgvb_notice_array);       
            }
        } 
    }    
    /**                                              
    * Outputs the contents of $wtgvb_notice_array, used in VideoBlogger_WP::pageheader().
    * Will hold new and none persistent notifications. May also hold persistent. 
    */
    function output_depreciated()
    {
        $wtgvb_notice_array = VideoBlogger_WP::persistentnotifications_array();    
        if(isset($wtgvb_notice_array['notifications'])){
                                                        
            foreach($wtgvb_notice_array['notifications'] as $key => $notice){
                                
                // persistent notices are handled by another function as the output is different
                if(!isset($notice['persistent']) || $notice['persistent'] != false){
            
                    // set default values where any requires are null
                    if(!isset($notice['type'])){$notice['type'] = 'info';}
                    if(!isset($notice['helpurl'])){$notice['helpurl'] = false;} 
                    if(!isset($notice['size'])){$notice['size'] = 'Large';} 
                    if(!isset($notice['title'])){$notice['title'] = 'Sorry No Title Was Provided';}
                    if(!isset($notice['message'])){$notice['message'] = 'Sorry No Message Was Provided';}
                    if(!isset($notice['clickable'])){$notice['clickable'] = false;} 
                    if(!isset($notice['persistent'])){$notice['persistent'] = false;}
                    if(!isset($notice['id'])){$notice['id'] = false;} 
                      
                    echo VideoBlogger_Notice::notice_display_depreciated($notice['type'],$notice['helpurl'],$notice['size'],$notice['title'],$notice['message'],$notice['clickable'],$notice['persistent'],$notice['id']);                                               
                
                    // notice has been displayed so we now removed it
                    unset($wtgvb_notice_array['notifications'][$key]);
                }
            }
                               
            VideoBlogger_Notice::update_persistentnotifications_array($wtgvb_notice_array);
        }  
    }                       
}// end class VideoBlogger_Notice
?>