<?php  
/** 
 * The core classes for Video Blogger Wordpress plugin 
 * 
 * @package Video Blogger
 * 
 * @since 1.0.2
 * 
 * @copyright (c) 2009-2013 webtechglobal.co.uk
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 */

class VideoBlogger_Core {
    public function __construct() {
        
    }    
    /**
    * $_POST and $_GET request processing procedure.
    * 
    * Checks nonce from any form or URL, then includes functions that processing submission, then includes the
    * file that determines which function to use to process the submission.
    *
    * @package Video Blogger
    * @since 0.0.1  
    */
    public function process_POST_GET(){   
        if(!isset($_POST['wtgvb_post_requested']) && !isset($_GET['wtgvbprocess']))
        {
            return;
        }          
               
        // $_POST request
        if(isset($_POST['wtgvb_post_requested']) && $_POST['wtgvb_post_requested'] == true){        
            if(isset($_POST['wtgvb_admin_referer'])){        
                // a few forms have the wtgvb_admin_referer where the default hidden values are not in use
                check_admin_referer( $_POST['wtgvb_admin_referer'] ); 
                $function_name = $_POST['wtgvbprocess'];        
            }else{                                       
                // 99% of forms will use this method
                check_admin_referer( $_POST['wtgvb_form_name'] );
                $function_name = $_POST['wtgvb_form_name'];
            }        
        }
                          
        // $_GET request
        if(isset($_GET['wtgvbprocess'])){      
            check_admin_referer( $_GET['wtgvbprocess'] );        
            $function_name = $_GET['wtgvbprocess'];
        }     
                   
        // arriving here means check_admin_referer() security is positive       
        global $wtgvb_debug_mode,$cont,$wtgvb_is_free;

        VideoBlogger_PHP::var_dump($_POST,'<h1>$_POST</h1>');           
        VideoBlogger_PHP::var_dump($_GET,'<h1>$_GET</h1>');    
 
        // ensure class and method exist
        if(!method_exists('VideoBlogger_requests', $function_name))
        {
            $a = 'ryan@webtechglobal.co.uk';
            $b = '<br><br>';
            wp_die(sprintf(__("Sorry but it appears the function for processing your request
            has not been added to Video Blogger, it was not loaded or the request itself is invalid.
            I would appreciate your feedback regarding this matter by emailing %s %s
            Please report method %s does not exist.",'video-blogger'),$a,$b,$function_name));    
        }
        
        if(isset($function_name) && is_string($function_name))
        {
            eval('VideoBlogger_requests::' . $function_name .'();');
        }
    }                           
                    
    // Wordpress admin page callbacks
    static function page_toppage(){require_once( WTG_VB_PATH . 'views/main/wtgvb_main.php' );}

    public function admin_menu(){ 
        global $wtgvb_currentversion,$wtgvb_mpt_arr,$wtgvb_is_installed,$wtgvb_is_free;
                                   
        $n = 'videoblogger';

        // if file version is newer than install we display the main page only but re-label it as an update screen
        // the main page itself will also change to offer plugin update details. This approach prevent the problem with 
        // visiting a page without permission between installation
        $installed_version = VideoBlogger_WP::get_installed_version();                
            
        // installation is not done on activation, we display an installation screen if not fully installed
        if(!$wtgvb_is_installed && !isset($_POST['wtgvb_plugin_install_now'])){   
           
            // if URL user is attempting to visit any screen other than page=videoblogger then redirect to it
            if(isset($_GET['page']) && strstr($_GET['page'],'wtgvb_')){
                wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=videoblogger' );           
                exit;    
            }
            
            // if plugin not installed
            add_menu_page(__('Install',$n.'install'), __('Video Blogger Install','home'), 'administrator', 'videoblogger', array($this,'page_toppage') );
            
        }elseif(isset($wtgvb_currentversion) 
        && isset($installed_version) 
        && $installed_version != false
        && $wtgvb_currentversion > $installed_version){
            
            // if URL user is attempting to visit any screen other than page=videoblogger then redirect to it
            if(isset($_GET['page']) && strstr($_GET['page'],'wtgvb_')){
                wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=videoblogger' );
                exit;    
            }
                    
            // if $installed_version = false it indicates no installation so we should not be displaying an update screen
            // update screen will be displayed after installation submission if this is not in place
            
            // main is always set in menu, even in extensions main must exist
            add_menu_page(__('Update',$n.'update'), __('Video Blogger Update','home'), 'administrator', 'videoblogger',  array($this,'page_toppage') );
            
        }else{

            // main is always set in menu, even in extensions main must exist
            add_menu_page(__($wtgvb_mpt_arr['menu']['main']['title'],$n.$wtgvb_mpt_arr['menu']['main']['slug']), __($wtgvb_mpt_arr['menu']['main']['menu'],'home'), VideoBlogger_WP::get_page_capability('main'), $n,  array($this,'page_toppage') ); 
                                
            // loop through sub-pages
            foreach($wtgvb_mpt_arr['menu'] as $k => $a){

                // skip none page values such as ['arrayinfo']
                if($k != 'arrayinfo'){
                    // skip main page (even extensions use the same main page file but the tab screens may be customised
                    if($wtgvb_is_free && $a == 'beta' || $k == 'main'){
                        // page is either for paid edition only or is added to the menu elsewhere    
                    }else{
                        // if ['active'] is set and not equal to false, if not set we assume true   
                        if(!isset($wtgvb_mpt_arr['menu'][$k]['active']) || isset($wtgvb_mpt_arr['menu'][$k]['active']) && $wtgvb_mpt_arr['menu'][$k]['active'] != false){
                            
                            $display = false;
                            
                            if(!isset($wtgvb_mpt_arr['menu'][$k]['package'])){
                                $display = true;// to hide a page we must set the package value
                            }
                            
                            if(isset($wtgvb_mpt_arr['menu'][$k]['package']) && $wtgvb_mpt_arr['menu'][$k]['package'] == 'paid' && !$wtgvb_is_free){
                                $display = true;
                            }
                            
                            // if is free package, only show page if package value set (added 29th April 2013) and the value == free
                            if($display){
                                $required_capability = VideoBlogger_WP::get_page_capability($k);    
                                add_submenu_page($n, __($wtgvb_mpt_arr['menu'][$k]['title'],$n.$wtgvb_mpt_arr['menu'][$k]['slug']), __($wtgvb_mpt_arr['menu'][$k]['menu'],$n.$wtgvb_mpt_arr['menu'][$k]['slug']), $required_capability, $wtgvb_mpt_arr['menu'][$k]['slug'],  array($this,'page_' . $k) );
                            }
                        }
                    }
                }             

            }// end page loop 
        }
    } 
    /**
    * Determines if an event is due and processes what we refer to as an action (event action) it if true.
    * 1. Early in the function we do every possible check to find a reason not to process
    * 2. This function checks all required values exist, else it sets them then returns as this is considered an event action
    * 3. This function itself is considered part of the event, we cycle through event types
    * 
    * Trace
    * $wtgvb_schedule_array['history']['trace'] is used to indicate how far the this script went before a return.
    * This is a simple way to quickly determine where we are arriving.
    * 
    * @return boolean false if no due events else returns true to indicate event was due and full function ran
    */
    public function event_check()
    {
        // do not continue if Wordpress is DOING_AJAX
        if(VideoBlogger_WP::request_made()){return;}
                      
        // do not continue if certain forms are being submitted i.e. uninstallation or re-installation
        if(isset($_POST['wtgvb_form_name']) && $_POST['wtgvb_form_name'] == 'partialuninstall'){   
            return;
        } 
                      
        VideoBlogger_WP::log_schedule(__('The schedule is being checked. There should be further log entries explaining the outcome.','video-blogger'),__('schedule being checked','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
        
        // now do checks that we will store the return reason for to allow us to quickly determine why it goes no further                     
        //  get and ensure we have the schedule array
        //  we do not initialize the schedule array as the user may be in the processing of deleting it
        //  do not use wtgvb_event_refused as we do not want to set the array
        global $wtgvb_schedule_array;
        if(!isset($wtgvb_schedule_array) || !is_array($wtgvb_schedule_array)){       
            VideoBlogger_WP::log_schedule(__('Scheduled events cannot be peformed due to the schedule array of stored settings not existing.','video-blogger'),__('schedule settings missing','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            return false;
        }
                      
        // check when last event was run - avoid running two events within 1 minute of each other
        // I've set it here because this function could grow over time and we dont want to go through all the checks PER VISIT or even within a few seconds of each other.
        if(isset($wtgvb_schedule_array['history']['lasteventtime'])){
            
            // increase lasteventtime by 60 seconds
            $soonest = $wtgvb_schedule_array['history']['lasteventtime'] + 60;//hack info page http://www.webtechglobal.co.uk/hacking/increase-automatic-events-delay-time
            
            if($soonest > time()){
                VideoBlogger_WP::log_schedule(__('No changed made as it has not been 60 seconds since the last event.','video-blogger'),__('enforcing schedule event delay','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
                VideoBlogger_WP::event_return(__('has not been 60 seconds since list event','video-blogger')); 
                return;
            } 
                    
        }else{
                       
            // set lasteventtime value for the first time
            $wtgvb_schedule_array['history']['lasteventtime'] = time();
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('The last even time event was set for the first time, no further processing was done.');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
            VideoBlogger_WP::log_schedule(__('The plugin initialized the timer for enforcing a delay between events. This action is treated as an event itself and no further
            changes are made during this schedule check.','video-blogger'),__('initialized schedule delay timer','videoblogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);

            VideoBlogger_WP::event_return('initialised the last event time value');
            return;        
        }                             
                                           
        // is last event type value set? if not set default as dataupdate, this means postcreation is the next event
        if(!isset($wtgvb_schedule_array['history']['lasteventtype'])){
            
            $wtgvb_schedule_array['history']['lasteventtype'] = 'dataupdate';
            $wtgvb_schedule_array['history']['lastreturnreason'] = 'The last event type value was set for the first time';
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);

            VideoBlogger_WP::log_schedule(__('The plugin initialized last event type value, this tells the plugin what event was last performed and it is used to
            determine what event comes next.','video-blogger'),__('initialized schedule last event value','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            
            VideoBlogger_WP::event_return(__('initialised last event type value','video-blogger'));
            return;
        }
                 
        // does the "day_lastreset"" time value exist, if not we set it now then return
        if(!isset($wtgvb_schedule_array['history']['day_lastreset'])){
            
            $wtgvb_schedule_array['history']['day_lastreset'] = time();
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('The last daily reset time was set for the first time','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
            
            VideoBlogger_WP::log_schedule(__('Day timer was set in schedule system. This is the 24 hour timer used to track daily events. It was set, no further action was taking 
            and should only happen once.','video-blogger'),__('24 hour timer set','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            
            VideoBlogger_WP::event_return('initialised last daily reset time');        
            return;
        } 
                                                         
        // does the "hour_lastreset"" time value exist, if not we set it now then return
        if(!isset($wtgvb_schedule_array['history']['hour_lastreset'])){
            
            $wtgvb_schedule_array['history']['hour_lastreset'] = time();
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('The hourly reset time was set for the first time','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
            
            VideoBlogger_WP::log_schedule(__('Hourly timer was set in schedule system. The time has been set for hourly countdown. No further action was 
            taking. This should only happen once.','video-blogger'),__('one hour timer set','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);        
            
            VideoBlogger_WP::event_return(__('initialised hourly reset time','video-blogger'));
            return;
        }    
               
        // does the hourcounter value exist, if not we set it now then return (this is to initialize the variable)
        if(!isset($wtgvb_schedule_array['history']['hourcounter'])){
            
            $wtgvb_schedule_array['history']['hourcounter'] = 0;
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('The hourly events counter was set for the first time','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
        
            VideoBlogger_WP::log_schedule(__('Number of events per hour has been set for the first time, this change is treated as an event.','video-blogger'),__('hourly events counter set','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);     
            
            VideoBlogger_WP::event_return(__('initialised hourly events counter','video-blogger'));   
            return;
        }     
                                     
        // does the daycounter value exist, if not we set it now then return (this is to initialize the variable)
        if(!isset($wtgvb_schedule_array['history']['daycounter'])){
            
            $wtgvb_schedule_array['history']['daycounter'] = 0;
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('The daily events counter was set for the first time','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);

            VideoBlogger_WP::log_schedule(__('The daily events counter was not set. No further action was taking. 
            This measure should only happen once.','video-blogger'),__('daily events counter set','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);     
            
            VideoBlogger_WP::event_return(__('initialised daily events counter','video-blogger'));           
            return;
        } 

        // has hourly target counter been reset for this hour - if not, reset now then return (this is an event)
        // does not actually start at the beginning of an hour, it is a 60 min allowance not hour to hour
        $hour_reset_time = $wtgvb_schedule_array['history']['hour_lastreset'] + 3600;
        if(time() > $hour_reset_time ){
            
            // reset hour_lastreset value and the hourlycounter
            $wtgvb_schedule_array['history']['hour_lastreset'] = time();
            $wtgvb_schedule_array['history']['hourcounter'] = 0;
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('Hourly counter was reset for another 60 minute period','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
          
            VideoBlogger_WP::log_schedule(__('Hourly counter has been reset, no further action is taking during this event. This should only happen once every hour.','video-blogger'),__('hourly counter reset','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            
            VideoBlogger_WP::event_return(__('hourly counter was reset','video-blogger'));        
            return;
        }  

        // have all target counters been reset for today - if not we will reset now and end event check (in otherwords this was the event)
        $day_reset_time = $wtgvb_schedule_array['history']['day_lastreset'] + 86400;
        if(time() > $day_reset_time ){
            
            $wtgvb_schedule_array['history']['hour_lastreset'] = time();
            $wtgvb_schedule_array['history']['day_lastreset'] = time();
            $wtgvb_schedule_array['history']['hourcounter'] = 0;
            $wtgvb_schedule_array['history']['daycounter'] = 0;
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('Daily and hourly events counter reset for a new 24 hours period','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array); 
            VideoBlogger_WP::log_schedule(__('24 hours had passed and the daily counter had to be reset. No further action is taking during these events and this should only happen once a day.','video-blogger'),__('daily counter reset','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);   
            VideoBlogger_WP::event_return(__('24 hour counter was reset','video-blogger'));            
            return;
        }

        // ensure event processing allowed today
        $day = strtolower(date('l'));
        if(!isset($wtgvb_schedule_array['days'][$day])){
            VideoBlogger_WP::event_return(__('Event processing is has not been permitted for today','video-blogger'));
            VideoBlogger_WP::log_schedule(__('Event processing is not permitted for today. Please check schedule settings to change this.','video-blogger'),'schedule not permitted today',1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__); 
            VideoBlogger_WP::event_return(__('schedule not permitting day','video-blogger'));        
            return;    
        } 

        // ensure event processing allow this hour   
        $hour = strtolower( date('G') );
        if(!isset($wtgvb_schedule_array['hours'][$hour])){
            VideoBlogger_WP::event_return(__('Event processing is has not been permitted for the hour','video-blogger'));
            VideoBlogger_WP::log_schedule(__('Processsing is not permitted for the current hour. Please check schedule settings to change this.','video-blogger'),__('hour not permitted','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            VideoBlogger_WP::event_return(__('schedule not permitting hour','video-blogger'));        
            return;    
        }

        // ensure hourly limit value has been set
        if(!isset($wtgvb_schedule_array['limits']['hour'])){
            
            $wtgvb_schedule_array['limits']['hour'] = 1;
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('Hourly limit was set for the first time','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
            VideoBlogger_WP::log_schedule(__('The hourly limit value had not been set yet. You can change the limit but the default has been set to one. No further action is taking during this event and this should only happen once.','video-blogger'),__('no hourly limit set','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            VideoBlogger_WP::event_return(__('initialised hourly limit','video-blogger'));        
            return;
        }     
                    
        // ensure daily limit value has been set
        if(!isset($wtgvb_schedule_array['limits']['day'])){
            
            $wtgvb_schedule_array['limits']['day'] = 1;
            $wtgvb_schedule_array['history']['lastreturnreason'] = __('Daily limit was set for the first time','video-blogger');
            VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
            VideoBlogger_WP::log_schedule(__('The daily limit value had not been set yet. It has now been set as one which allows only one post to be created or updated etc. This action should only happen once.','video-blogger'),__('no daily limit set','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            VideoBlogger_WP::event_return(__('initialised daily limit','video-blogger'));           
            return;
        }

        // if this hours target has been met return
        if($wtgvb_schedule_array['history']['hourcounter'] >= $wtgvb_schedule_array['limits']['hour']){
            VideoBlogger_WP::event_return(__('The hours event limit/target has been met','video-blogger'));
            VideoBlogger_WP::log_schedule(__('The events target for the current hour has been met so no further processing is permitted.','video-blogger'),__('hourly target met','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            VideoBlogger_WP::event_return(__('hours limit reached','video-blogger'));            
            return;        
        }
         
        // if this days target has been met return
        if($wtgvb_schedule_array['history']['daycounter'] >= $wtgvb_schedule_array['limits']['day']){
            VideoBlogger_WP::event_return(__('The days event limit/target has been met','video-blogger'));
            VideoBlogger_WP::log_schedule(__('The daily events target has been met for the current 24 hour period (see daily timer counter). No events will be processed until the daily timer reaches 24 hours and is reset.','video-blogger'),__('daily target met','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            VideoBlogger_WP::event_return(__('days limit reached'));        
            return;       
        }
               
        // decide which event should be run (based on previous event, all events history and settings)
        $run_event_type = $this->event_decide();
                  
        VideoBlogger_WP::log_schedule(sprintf(__('The schedule system decided that the next event type is %s.','video-blogger'),$run_event_type),__('next event type determined','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);
            
        // update $wtgvb_schedule_array with decided event type to advance the cycle and increase hourly plus daily counter
        $wtgvb_schedule_array['history']['lasteventtype'] = $run_event_type;
        $wtgvb_schedule_array['history']['lasteventtime'] = time(); 
        $wtgvb_schedule_array['history']['hourcounter'] = $wtgvb_schedule_array['history']['hourcounter'] + 1; 
        $wtgvb_schedule_array['history']['daycounter'] = $wtgvb_schedule_array['history']['daycounter'] + 1;
        VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
        
        // run procedure for decided event
        $event_action_outcome = $this->event_action($run_event_type); 
        
        return $event_action_outcome;   
    }
    /**
    * Establishes which event should be run then return it.
    * Must call ths function within a function that checks that uses wtgvb_DOING_AJAX() first 
    * 
    * 1. If you add a new event type, you must also update wtgvb_tab1_pagecreation.php (Schedule), specifically Events Status panel
    * 2. Update event_action when adding a new event type
    * 3. Update the Event Types panel and add option for new event types
    * 4. Update wtgvb_form_save_eventtypes
    * 
    * @link http://www.webtechglobal.co.uk/hacking/event-types
    */
    public function event_decide()
    {
        global $wtgvb_schedule_array,$wtgvb_settings;
        
        // return focused event if active
        $override_event = $this->event_decide_focus();// returns false if no override settings in place    
        if($override_event && is_string($override_event))
        {
            VideoBlogger_WP::log_schedule(sprintf(__('The plugins ability to override the next due event type has been applied and then next event forced is %s.','video-blogger'),$override_event),__('next event type override','video-blogger'),1,'scheduledeventcheck',__LINE__,__FILE__,__FUNCTION__);         
            return $override_event;
        }    

        // set default
        $run_event_type = 'deleteuserswaiting';
        
        // if we have no last event to establish the next event return the default
        if(!isset($wtgvb_schedule_array['history']['lasteventtype'])){
            return $run_event_type;
        }
        $bypass = false;// change to true when the next event after the last is not active, then the first available in the list will be the event 
        
        // deleteuserswaiting - delete users who have not yet activated their account  
        if($wtgvb_schedule_array['history']['lasteventtype'] == 'sendemails'){
            if( isset($wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch']) && $wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'] == true){
                 return 'deleteuserswaiting';    
            }else{
                $bypass = true; 
            }            
        }
                                              
        // sendemails (sends emails stored in wp_wtgvbmailing
        if($wtgvb_schedule_array['history']['lasteventtype'] == 'deleteuserswaiting' || $bypass == true){        
            if( isset($wtgvb_schedule_array['eventtypes']['sendemails']['switch']) && $wtgvb_schedule_array['eventtypes']['sendemails']['switch'] == true){ 
                // ensure email system active (this setting is for the entire system, where the switch is for within schedule system only)
                if(isset($wtgvb_settings['community']['emailsystem']['status']) && $wtgvb_settings['community']['emailsystem']['status'] == 1)
                {                            
                    return 'sendemails';    
                }                 
            }else{           
                $bypass = true; 
            }            
        }    
                   
        return $run_event_type;        
    }
    /**
    * Determines if user wants the schedule to focus on one specific event type
    */
    public function event_decide_focus()
    {
        global $wtgvb_schedule_array;
        if(isset($wtgvb_schedule_array['focus']) && $wtgvb_schedule_array['focus'] != false){
            return $wtgvb_schedule_array['focus'];    
        }
    }
    /**
    * Runs the required event
    * 1. The event type determines what function is to be called. 
    * 2. We can add arguments here to call different (custom) functions and more than one action.
    * 3. Global settings effect the event type selected, it is always cycled to ensure good admin
    * 
    * @param mixed $run_event_type, see event_decide() for list of event types 
    */
    public function event_action($run_event_type)
    {    
        global $wtgvb_schedule_array,$wtgvb_settings;       
        $wtgvb_schedule_array['history']['lasteventaction'] = $run_event_type . ' Requested';     
        switch ($run_event_type) {
            case "deleteuserswaiting":# delete WP users who have not activated through Video Blogger  
                
                VideoBlogger_WP::log_schedule(__('Procedure for deleting of newely registered users who have not activated their account is being run.','video-blogger'),__('start deletion of waiting users','video-blogger'),1,$run_event_type,__LINE__,__FILE__,__FUNCTION__);
                
                $result_array = VideoBlogger_users_core::delete_users_waiting(); 
                if(!$result_array){
                    $users_deleted = 0;        
                }elseif(is_array($result_array)){
                    $users_deleted = count($result_array);
                }
                
                VideoBlogger_WP::log_schedule(sprintf(__('Procedure for deleting accounts that have never been activated has ended and a total of %s Wordpress users were deleted.','video-blogger'),$users_deleted),__('finished deleting waiting users','video-blogger'),1,$run_event_type,__LINE__,__FILE__,__FUNCTION__);
                
                VideoBlogger_WP::event_return(__('completed deletion of waiting users','video-blogger'));           
                break;  
            case "sendemails":
                                
                // ensure email system active
                if(isset($wtgvb_settings['community']['emailsystem']['status']) && $wtgvb_settings['community']['emailsystem']['status'] == 0)
                {
                    break;    
                }
                           
                VideoBlogger_WP::log_schedule(__('Email system is about to determine if emails need to be sent.','video-blogger'),__('begin sending emails','video-blogger'),1,$run_event_type,__LINE__,__FILE__,__FUNCTION__);
                               
                $emails_sent = VideoBlogger_community_core::send_emails_bulk($wtgvb_settings['community']['emailsystem']['batchlimit']);
                             
                if($emails_sent == 0)
                {                  
                    VideoBlogger_WP::log_schedule(__('No emails were due to be sent.','video-blogger'),__('no emails due','video-blogger'),1,$run_event_type,__LINE__,__FILE__,__FUNCTION__);   
                }
                else
                {             
                    VideoBlogger_WP::log_schedule(sprintf(__("A total of %s emails were sent.",'video-blogger'),$emails_sent),sprintf(__("%s emails sent",'video-blogger'),$emails_sent),1,$run_event_type,__LINE__,__FILE__,__FUNCTION__);   
                }
                              
                VideoBlogger_WP::event_return(__('completed email sending procedure','video-blogger'));           
                break;
        }
        VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
    } 
    /**
    * Used in admin page headers to constantly check the plugins status while administrator logged in 
    */
    public function diagnostics_constant()
    {
        if(is_admin() && current_user_can( 'manage_options' )){
                                                      
            if(VideoBlogger_WP::request_made()){
                return;
            }
                                    
            ###########################################################################################
            #                              PEFORM EXTENSION DIAGNOSTICS                               #
            ###########################################################################################
            if(WTG_VB_EXTENSIONS != 'disable' && file_exists(WP_CONTENT_DIR . '/wtgvbextensions')){
                $current_extension_name = 'df1'; 
                if(VideoBlogger_Extensions::activation_status($current_extension_name) == 3){
                    if(file_exists(WP_CONTENT_DIR . '/eciextensions/'.$current_extension_name.'/functions/diagnostics.php')){
                        
                        require_once(WP_CONTENT_DIR . '/eciextensions/'.$current_extension_name.'/functions/diagnostics.php');            
                    }
                }
            }
                                 
            ###########################################################################################
            #                                PEFORM CORE DIAGNOSTICS                                  #
            ###########################################################################################                    
            // core database tables
            global $wtgvb_tables_array;# core tables
            // temp OFF while plugin is in constant development $this->diagnostics_databasecomparison_alertonly($wtgvb_tables_array); (begin using again when all tables created using dbDelta)  
        }
    }
    /**
    * Uses database table arrays to check if any installed tables have not been updated yet 
    */
    public function diagnostics_databasecomparison_alertonly($expected_tables_array)
    {
        if(is_array($expected_tables_array)){  
            
            // loop through plugins tables
            foreach($expected_tables_array['tables'] as $key => $table){
                           
                $table_installed = VideoBlogger_WPDB::does_table_exist($table['name']);
                
                // is table required and exist?
                if($table['required'] == true && $table_installed == false){  
                    
                    VideoBlogger_Notice::n_depreciated(__('Database Table Missing','video-blogger'),sprintf(__('Video Blogger diagnostic
                    indicates a required database table is missing. This must be dealt with before the
                    plugin is used. The table name is %s and you can install it manually
                    on the plugins installation screens. WebTechGlobal is happy to help you.','video-blogger'),'<strong>' . $table['name'] .'</strong>'),
                    'error','Small',array());
                    return false;   
                     
                }elseif($table_installed == true){
                           
                    // whatever the requirements, the table is installed so we need to ensure all columns are as required            
                    $installed_table_array = VideoBlogger_WPDB::get_tablecolumns($table['name'],true,true);
                               
                    foreach($table['columns'] as $something => $somethingelse){
                        $column_found = false;
         
                        foreach($installed_table_array as $installedCol){
                            if($installedCol == $something){
                                $column_found = true;
                            }
                        }
                        
                        if(!$column_found){
                            VideoBlogger_Notice::n_depreciated(__('Database Update Required','video-blogger'),sprintf(__('The database table named %s needs to be updated. A column
                            named %s must be added to it. This is a requirement in the latest version of the plugin. You can
                            perform this update on the installation screens.','video-blogger'),'<strong>'.$table['name'].'</strong>','<strong>'.$something.'</strong>'),
                            'error','Small');
                            return false;
                        }
                    }
                        
                    ###############################################
                    #               PRECISE COMPARISON            #
                    ###############################################   
                    $installed_table_array = VideoBlogger_WPDB::get_tablecolumns($table['name'],true);
                             
                    // loop through the current tables array of columns
                    foreach($installed_table_array as $key => $column){
                        
                        $columnName = $column[0];

                        // Type
                        // remove "unsigned" first,we are not checking for that right now
                        $installed_column = str_replace(' unsigned','',$column[1]);# example: int(10) unsigned
                        if($installed_column != $table['columns'][$columnName]['type']){
                            VideoBlogger_Notice::n_depreciated(__('Database Table Update Required'),sprintf(__('The plugins database table named %s
                            needs to be updated. The column type for column named %s does not
                            match the current version of files installed. Please use the installation tools provided
                            to update the table manually. Seek support from WebTechGlobal if unsure.','video-blogger'),'<strong>'.$table['name'].'</strong>','<strong>' . $columnName .'</strong>'),
                            'error','Small');
                            return false;      
                        }
                       
                        // Null (mysql returns NO or YES but the CREATE query requires NOT NULL or no value at all)
                        if($column[2] == 'NO' && $table['columns'][$columnName]['null'] != 'NOT NULL'
                        || $column[2] == 'YES' && $table['columns'][$columnName]['null'] != ''){ 
                            VideoBlogger_Notice::n_depreciated(__('Database Table Update Required'),sprintf(__('The plugins database table named %s
                            needs to be updated. The "null" setting for column named %s does not
                            match the current version of files installed. Please use the installation tools provided
                            to update the table manually. Seek support from WebTechGlobal if unsure.','video-blogger'),'<strong>'.$table['name'].'</strong>','<strong>' . $columnName .'</strong>'),
                            'error','Small');
                            return false;  
                        }

                        // Key - $column[3] - this wont change after database design so no check required
         
                        // Default
                        if(!$column[4] && $table['columns'][$columnName]['default'] != 'NULL' && $table['columns'][$columnName]['default'] != '' && $column[3] != 'PRI'){ 
                            VideoBlogger_Notice::n_depreciated(__('Database Table Update Required'),sprintf(__('The plugins database table named %s
                            needs to be updated. The "default" value setting for column named %s does not
                            match the current version of files installed. Please use the installation tools provided
                            to update the table manually. Seek support from WebTechGlobal if unsure.','video-blogger'),'<strong>'.$table['name'].'</strong>','<strong>' . $columnName .'</strong>'),
                            'error','Small'); 
                            return false; 
                        }
                        
                        // Extra - installed returns lowercase while CREATE table query has uppercase
                        $installed = strtolower($column[5]);
                        $expected = strtolower($table['columns'][$columnName]['extra']);
                        if($installed != $expected){ 
                            VideoBlogger_Notice::n_depreciated(__('Database Table Update Required','video-blogger'),'video-blogger',sprintf(__('The plugins database table named %s
                            needs to be updated. Configuration for the column named %s does not
                            match the current version of files installed. Please use the installation tools provided
                            to update the table manually. Seek support from WebTechGlobal if unsure.','video-blogger'),'<strong>'.$table['name'].'</strong>','<strong>' . $columnName .'</strong>'),
                            'error','Small');
                            return false;  
                        }             
                    }
                }
            }
        }  
    }                           
}                                           
class VideoBlogger extends VideoBlogger_Core {
    protected
        $filters = array(),
        $actions = array(),    

        // Format: array( event | function in this class(in an array if optional arguments are needed) | loading circumstances)
        $plugin_actions = array(
            array('admin_menu',                     'admin_menu',                                      'all'),
            array('admin_init',                     'process_POST_GET',                                'all'),
        ),
                               
        $plugin_filters = array(
            /*
                Examples - last value are the sections the filter apply to
                    array('plugin_row_meta',                     array('examplefunction1', 10, 2),         'all'),
                    array('page_link',                             array('examplefunction2', 10, 2),             'downloads'),
                    array('admin_footer_text',                     'examplefunction3',                         'monetization'),
                    
            */
        );     
                
    private
        $doneInit = false;
        
    /**
    * This class is being introduced gradually, we will move various lines and config functions from the main file to load here eventually
    */
    public function __construct() 
    {
        
        // straight away we need to indicate if phpBB is loaded or not
        $this->_loaded = false;
        if(defined('IN_PHPBB')) { 
            $this->_loaded = true;
        }

        parent::__construct();
        $this->VideoBlogger_Core;
    }
    /**
     * if Video Blogger already initialised returns true
     * @return bool true if already inited
     */
    public function has_inited() 
    {
        return $this->doneInit;
    }   
    /**
     * Initialises the plugin from WordPress
     * @return void
     */
    public function wp_init() {  
        if($this->has_inited()) {
            return false;
        }
        $this->doneInit = true;
                
        $this->add_actions();
        $this->add_filters();
        unset($this->plugin_actions, $this->plugin_filters);
    }
    protected function add_actions() {          
        foreach($this->plugin_actions as $actionArray) {        
            list($action, $details, $whenToLoad) = $actionArray;
                                   
            if(!$this->filteraction_should_beloaded($whenToLoad)) {      
                continue;
            }
                 
            switch(count($details)) {         
                case 3:
                    add_action($action, array($this, $details[0]), $details[1], $details[2]);     
                break;
                case 2:
                    add_action($action, array($this, $details[0]), $details[1]);   
                break;
                case 1:
                default:
                    add_action($action, array($this, $details));
            }
        }    
    }
    protected function add_filters() {
        foreach($this->plugin_filters as $filterArray) {
            list($filter, $details, $whenToLoad) = $filterArray;
                           
            if(!$this->filteraction_should_beloaded($whenToLoad)) {
                continue;
            }
            
            switch(count($details)) {
                case 3:
                    add_filter($filter, array($this, $details[0]), $details[1], $details[2]);
                break;
                case 2:
                    add_filter($filter, array($this, $details[0]), $details[1]);
                break;
                case 1:
                default:
                    add_filter($filter, array($this, $details));
            }
        }    
    }    
    /**
    * Should the giving action or filter be loaded?
    * 1. we can add security and check settings per case
    * 2. each case is a section and we use this approach to load action or filter for specific section
    * 3. In early development all sections are loaded, this function is prep for a modular plugin
    * 4. addons will require core functions like this to be updated rather than me writing dynamic functions for any possible addons
    *  
    * @param mixed $whenToLoad
    */
    private function filteraction_should_beloaded($whenToLoad) {
        global $wtgvb_sections_array;
                                
        if($whenToLoad != 'all' && !in_array($whenToLoad,$wtgvb_sections_array) ) {
            return false;
        }
                      
        switch($whenToLoad) {
            case 'all':    
                return true;
            break;
        }

        return true;
    }  
    public function activation(){
        // not installing anything during activation, installation requires another submission
        global $wtgvb_isbeingactivated;
        $wtgvb_isbeingactivated = true;// used to avoid loading files not required during activation (minimise errors during activation related to none activation related)        
    }
    /**
    * When request will display maximum php errors including Wordpress errors 
    */
    public function debugmode(){
        global $wtgvb_debug_mode;
        if($wtgvb_debug_mode){
            global $wpdb;
            ini_set('display_errors',1);
            error_reporting(E_ALL);      
            if(!defined("WP_DEBUG_DISPLAY")){define("WP_DEBUG_DISPLAY",true);}
            if(!defined("WP_DEBUG_LOG")){define("WP_DEBUG_LOG",true);}
            //add_action( 'all', create_function( '', 'var_dump( current_filter() );' ) );
            //define( 'SAVEQUERIES', true );
            //define( 'SCRIPT_DEBUG', true );
            $wpdb->show_errors();
            $wpdb->print_error();
        }
    }
    /**
    * Gets option value for videoblogger _adminset or defaults to the file version of the array if option returns invalid.
    * 1. Called in the main videoblogger.php file.
    * 2. Installs the admin settings option record if it is currently missing due to the settings being required by all screens, this is to begin applying and configuring settings straighta away for a per user experience 
    */
    public function adminsettings(){
        $result = VideoBlogger_WP::option('wtgvb_settings','get');
        $result = maybe_unserialize($result); 
        if(is_array($result)){
            return $result; 
        }else{     
            return wtgvb_INSTALL_admin_settings();
        }  
    }              
    /**
     * Add a widget to the dashboard.
     *
     * This function is hooked into the 'wp_dashboard_setup' action below.
     */
    public function add_dashboard_widgets() {   /*
        global $wtgvb_settings;
            
        // affiliates section
        if(isset($wtgvb_settings['affiliates']['affiliatessectionsettings']['dashboardwidgetswitch']) && $wtgvb_settings['affiliates']['affiliatessectionsettings']['dashboardwidgetswitch'] == 1)
        {
            wp_add_dashboard_widget(
                         'affiliatessectiondashboard',// Widget slug.
                         'Affiliates Section',// Title.
                         array($this,'affiliates_dashboard_widget')// Display function.
                ); 
        }      
         */               
    }     
    public function domain_only ( $url ) { 
        $url = trim($url);
        $url = preg_replace("/^(http:\/\/)*(www.)*/is", "", $url); 
        $url = preg_replace("/\/.*$/is" , "" ,$url); 
        return $url; 
    }        
    public function is_installed(){
        global $wtgvb_settings;
           
        if(!isset($wtgvb_settings) || !is_array($wtgvb_settings)){
            return false;
        }
                 
        // currently this value is returned, if changed to false
        $returnresult = true;
        $failcause = 'Not Known';// only used for debugging to determine what causes indication of not fully installed
        
        // function only returns boolean but if required we will add results array to the log
        $is_installed_result = array();
        $is_installed_result['finalresult'] = false;
        $is_installed_result['options'] = null;
                    
        foreach($wtgvb_settings as $id => $optionrecord){
                
            if(isset($optionrecord['required']) && $optionrecord['required'] == true){
                        
                $currentresult = get_option($id);    
                
                $is_installed_result['options'][$id]['result'] = $currentresult;
                            
                // change return switch to false if option not found
                if($currentresult == false || $currentresult == null){ 
                  
                    $returnresult = false;
                    $failcause = 'Option RecordMissing:'.$id;    
                }
            } 
        }                       
          
        return $returnresult;        
    }      
    public function update_option_adminsettings($wtgvb_adm_set){
        $admin_settings_array_serialized = maybe_serialize($wtgvb_adm_set);
        return update_option('wtgvb_settings',$admin_settings_array_serialized);    
    }     
}                                    
class VideoBlogger_ui {
    public function __construct() {
        $this->load_phpBB();
    }  
    public function form_action($values = '')
    {
        global $wtgvb_tab_number;
        echo get_admin_url() . 'admin.php?page=' . $_GET['page'] . '&wtgvbtab='.$wtgvb_tab_number.$values;
    }  
} 
class VideoBlogger_requests {   
    public function user_can($capability = 'activate_plugins')
    {
        if(!current_user_can($capability))
        {
            VideoBlogger_Notice::n_depreciated(sprintf(__('You Are Restricted','You do not have permission to complete that submission. Your Wordpress account requires the %s capability to perform the action you attempted.','video-blogger'),$capability),'warning','Small');
            return false;
        }   
        return true;
    }  
    public function request_success($form_title,$more_info = '')
    {   
        VideoBlogger_Notice::create_notice(sprintf(__("Your configuration for %s was updated successfully. %s",'video-blogger'),$form_title,$more_info),'success','Small',sprintf(__("%s Updated",'video-blogger'),$form_title) );          
    } 
    public function request_failed($form_title,$reason = '')
    {
        VideoBlogger_Notice::n_depreciated($form_title . ' Unchanged',sprintf(__("Your settings for %s were not changed. %s",'video-blogger'),$form_title,$reason),'error','Small');    
    }
    public function operationsettings()
    {
        global $wtgvb_settings;
        $wtgvb_settings['reporting']['uselog'] = $_POST['wtgvb_radiogroup_logstatus'];
        $wtgvb_settings['reporting']['loglimit'] = $_POST['wtgvb_loglimit'];
        VideoBlogger_WP::update_settings($wtgvb_settings);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Operation Settings Saved','video-blogger'),__('We recommend that you monitor the plugin for a short time and ensure your new settings are as expected.','video-blogger'));  
    }  
    /**
    * Create a data rule for replacing specific values after import 
    */
    public function eventtypes()
    {
        global $wtgvb_schedule_array;   
        $wtgvb_schedule_array['eventtypes']["deleteuserswaiting"]['switch'] = $_POST["wtgvb_eventtype_deleteuserswaiting"];
        VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
        VideoBlogger_Notice::notice_depreciated(__('Schedule event types have been saved, the changes will have an effect on the types of events run, straight away.','video-blogger'),'success','Large',__('Schedule Event Types Saved','video-blogger'),'','echo');
    } 
    /**
    * Data panel one settings
    */
    public function panelone()
    {
        global $wtgvb_settings;
        $wtgvb_settings['encoding']['type'] = $_POST['wtgvb_radiogroup_encoding'];
        $wtgvb_settings['admintriggers']['newcsvfiles']['status'] = $_POST['wtgvb_radiogroup_detectnewcsvfiles'];
        $wtgvb_settings['admintriggers']['newcsvfiles']['display'] = $_POST['wtgvb_radiogroup_detectnewcsvfiles_display'];
        $wtgvb_settings['postfilter']['status'] = $_POST['wtgvb_radiogroup_postfilter'];          
        $wtgvb_settings['postfilter']['tokenrespin']['status'] = $_POST['wtgvb_radiogroup_spinnertokenrespin'];        
        VideoBlogger_WP::update_settings($wtgvb_settings);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Data Related Settings Saved','video-blogger'),__('We recommend that you monitor the plugin for a short time and ensure your new settings are as expected.','video-blogger'));
    }     
    public function persistentnotice()
    {
        global $wtgvb_persistent_array;

        foreach($wtgvb_persistent_array['notifications'] as $key => $notice){
            if($notice['id'] == $_POST['wtgvb_post_deletenotice_id']){
                unset($wtgvb_persistent_array['notifications'][$key]);
            }            
        }
        
        VideoBlogger_WP::option('wtgvb_notifications','update',$wtgvb_persistent_array);            
    }                                                                            
    /**
    * Creates the csv file folder in the wp-content path
    */
    public function deletecontentfolder()
    {
        // this function does the output when set to true for 2nd parameter
        wtgvb_delete_contentfolder(WTG_VB_CONTENTFOLDER_DIR,true);      
    } 
    /**
    * Creates the csv file folder in the wp-content path
    */
    public function createcontentfolder()
    {
            // this function does the output when set to true for 2nd parameter
            wtgvb_install_contentfolder_videobloggercontent(WTG_VB_CONTENTFOLDER_DIR,true);    
    }      
    /**
    * Saves easy configuration questions
    */
    public function easyconfigurationquestions()
    {
        global $wtgvb_settings,$wtgvb_ecq_array;

        // save answers
        foreach($wtgvb_ecq_array as $key => $q){
            // if $_POST value for this question
            if(isset($_POST['wtgvb_'.$key])){
                $wtgvb_settings['ecq'][$key] = $_POST['wtgvb_'.$key];  
            }  
        }

        /**
        * Where there are existing settings we will update those settings to help make this
        * function central to the ECQ system. However if there is no existing system, consider
        * makign one. If one is not required required, then use the ['ecq'] value itself in the appropriate
        * script. 
        */

        // 102 - Data Tables (all or videoblogger)
        if($wtgvb_settings['ecq'][102] == 'yes'){$wtgvb_settings['dbtablesscope'] = 'all';}else{$wtgvb_settings['dbtablesscope'] = 'videoblogger';}
        // 103 - Schedule/Automation Trigger (admin also or public only)
        if($wtgvb_settings['ecq'][103] == 'yes'){}
        // 104 - New version tweets
        if($wtgvb_settings['ecq'][104] == 'yes'){/* return answer using User Experience Program when it is complete */}                                   
        // 105 - Use log system
        if($wtgvb_settings['ecq'][105] == 'yes'){}
        // 106 - RSS feed tutorials and videos
        if($wtgvb_settings['ecq'][106] == 'yes'){/* return answer using User Experience Program when it is complete */}
        // 107 - Encoding
        if($wtgvb_settings['ecq'][107] == 'yes'){/* process once encoding functions re-vised and complete */}
        // 108 - Hide jQuery theme and use Wordpress CSS
        if($wtgvb_settings['ecq'][108] == 'yes'){update_option('wtgvb_theme','wordpresscss'); }  
        // 109 - Only one user
        if($wtgvb_settings['ecq'][109] == 'yes'){/* a) screen permissions panel hidden b) page permissions panel hidden */}
        // 110 - Do you need to merge two or more CSV files into one set of data?
        if($wtgvb_settings['ecq'][110] == 'no'){/* no setting - answer will be used to hide multiple file related features */}
        // 111 - Table to table
        if($wtgvb_settings['ecq'][111] == 'yes'){}
        // 112 - Two or more database tables
        if($wtgvb_settings['ecq'][112] == 'yes'){/* radios instead of checkboxes are set on table selection when creating new project */}
        // 113 - Content template rules
        if($wtgvb_settings['ecq'][113] == 'yes'){/* hides panels related to contact template rules */}
        // 114 - Post dates
        if($wtgvb_settings['ecq'][114] == 'yes'){}
        // 115 - Post updating 
        if($wtgvb_settings['ecq'][115] == 'yes'){}  
        // 116 - Social networking
        if($wtgvb_settings['ecq'][116] == 'yes'){}
        // 117 - URL Cloaking
        if($wtgvb_settings['ecq'][117] == 'yes'){}
        // 118 - Text Spinning
        if($wtgvb_settings['ecq'][118] == 'yes'){}
        // 119 - Create authors
        if($wtgvb_settings['ecq'][119] == 'yes'){}
        // 120 - Display info or video buttons in panels?
        if($wtgvb_settings['ecq'][120] == 'yes'){$wtgvb_settings['interface']['panels']['supportbuttons']['status'] = 'display';}
        // 121 - One or more projects
        if($wtgvb_settings['ecq'][121] == 'yes'){}
        // 122 - User Experience Program
        if($wtgvb_settings['ecq'][122] == 'yes'){}
        // 123 - Register domain as user
        if($wtgvb_settings['ecq'][123] == 'yes'){}
        // 124 - Support account using wordpressplugin@videoblogger.com
        if($wtgvb_settings['ecq'][124] == 'yes'){}

        VideoBlogger_WP::update_settings($wtgvb_settings);

        VideoBlogger_Notice::notice_depreciated(__('Your answers for the Easy Configuration Questions have been saved. Please remember that this may hide features, display new features or change the way a feature operates.','video-blogger'),'success','Large',__('Answers Saved','video-blogger'),'','echo');      
    }                                                                                                        
    public function reinstalldatabasetables(){
        $tables = 0;
        $result = true;
        $result = wtgvb_INSTALL_reinstall_databasetables();++$tables;
        if($result)
        {
            VideoBlogger_Notice::n_postresult_depreciated('success',__('Tables Re-Installed Successfully','video-blogger'),sprintf(__('A total of %s tables were deleted then created. All data in the original tables is lost.','video-blogger'),$tables));
        }
        else
        {   
            VideoBlogger_Notice::n_postresult_depreciated('error',__('Tables Re-Installation Failed','video-blogger'),sprintf(__('A total of %s tables were meant to be deleted then installed again but a problem was detected. Please try again then investigate the issue. It may be a single table causing this issue. Please report it and we will be happy to help.','video-blogger'),$tables));
        }  
    } 
    public function extensionsettings(){
        // save extension status
        update_option('wtgvb_extensions',$_POST['wtgvb_radiogroup_extensionstatus']);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Extension Settings Saved'),__('Please ensure your blog operates as you expect with or without certain extensions.','video-blogger'));        
        wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=videoblogger&wtgvbtab=3' );
        exit;    
    }  
    public function screenpermissions(){
        global $wtgvb_mpt_arr;

        // loop through tabs, this is the same loop build as on the capabilities interface itself
        $menu_id = 0;
        foreach($wtgvb_mpt_arr['menu'] as $page_name => $page_array){

            foreach($page_array['tabs'] as $key => $tab_array){
                
                if(isset($tab_array['display']) && $tab_array['display'] != false){
                                        
                    // is post value set for current tab
                    if(isset($_POST['wtgvb_capabilitiesmenu_'.$page_name.'_'.$key.''])){
         
                        // update capability setting for screen
                        $wtgvb_mpt_arr['menu'][$page_name]['tabs'][$key]['permissions']['customcapability'] = $_POST['wtgvb_capabilitiesmenu_'.$page_name.'_'.$key.''];
                    }

                    ++$menu_id; 
                }        
            }
        }        

        VideoBlogger_WP::option('wtgvb_tabmenu','update',$wtgvb_mpt_arr);

        VideoBlogger_Notice::notice_postresult_depreciated('success',__('Screen Permissions Saved'),__('Your saved screen permissions may hide or display screens for users. We recommend that you access your blog using applicable user accounts to test your new permissions.') );    
    } 
    public function pagepermissions()
    {
        global $wtgvb_mpt_arr;

        foreach($wtgvb_mpt_arr as $page_name => $page_array){
            
            if(isset($_POST['wtgvb_capabilitiesmenu_'.$page_name.'_99'])){
                $wtgvb_mpt_arr['menu'][$page_name]['permissions']['customcapability'] = $_POST['wtgvb_capabilitiesmenu_'.$page_name.'_99'];    
            }

        }        

        VideoBlogger_WP::option('wtgvb_tabmenu','update',$wtgvb_mpt_arr); 
        
        VideoBlogger_Notice::notice_postresult_depreciated('success',__('Page Permissions Saved'),__('Your saved page permissions
        may hide or display the plugins pages for users. We recommend that you access your blog using applicable 
        user accounts to test your new permissions.') );     
    }  
    /**
    * Install Plugin - initial post submission validation  
    */
    public function installform()
    {             
        if(!current_user_can('activate_plugins')){
            VideoBlogger_Notice::notice_depreciated(sprintf(__('You do not have the required permissions to activate Video Blogger.
            The Wordpress role required is activate_plugins, usually granted to Administrators. Please
            contact your Web Master or contact %s if you feel this is a fault.','video-blogger'),'info@videoblogger.com'), 
            'warning', 'Large', false);
        }else{            
            wtgvb_install_core();
            wtgvb_install_plugin();    
            wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=videoblogger' ); 
            exit;        
        }      
    }
    /**
    * Partial Un-install Plugin Options 
    */
    public function partialuninstall()
    {
        if(current_user_can('delete_plugins')){
                     
            // if delete data import job tables
            if(isset($_POST['wtgvb_deletejobtables_array'])){
                               
                foreach($_POST['wtgvb_deletejobtables_array'] as $k => $table_name){
                    $code = str_replace('wtgvb_','',$table_name);
                    wtgvb_SQL_drop_dataimportjob_table($table_name);
                    VideoBlogger_Notice::notice_depreciated('Table ' . $table_name . ' was deleted.','success','Tiny','Table Deleted','','echo'); 
                }
            }
            
            // if delete core plugin tables
            if(isset($_POST['wtgvb_deletecoretables_array'])){
                foreach($_POST['wtgvb_deletecoretables_array'] as $key => $table_name){
                    VideoBlogger_WPDB::drop_table($table_name);
                }
            }
       
            // if delete csv files
            if(isset($_POST['wtgvb_deletecsvfiles_array'])){
                foreach($_POST['wtgvb_deletecsvfiles_array'] as $k => $csv_file_name){
                        
                    $file_is_in_use = false;
                    $file_is_in_use = wtgvb_is_csvfile_in_use($csv_file_name);
                       
                    // if file is in use
                    if($file_is_in_use){        
                        VideoBlogger_Notice::notice_depreciated(sprintf(__('The file named %s is in use, cannot delete.','video-blogger'),$csv_file_name),'error','Tiny',__('File In Use','video-blogger'),'','echo');
                    }else{                         
                        unlink(WTG_VB_CONTENTFOLDER_DIR . $csv_file_name); 
                        VideoBlogger_Notice::notice_depreciated( $csv_file_name .' Deleted','success','Tiny','','','echo');
                    }
                                            
                }      
            }
                      
            // if delete folders
            if(isset($_POST['wtgvb_deletefolders_array'])){    
                foreach($_POST['wtgvb_deletefolders_array'] as $k => $o){       
                    // currently only have one folder so we will use a specific function   
                    wtgvb_delete_contentfolder(WTG_VB_CONTENTFOLDER_DIR,false);
                }      
            }            

            // if delete options
            if(isset($_POST['wtgvb_deleteoptions_array'])){          
                foreach($_POST['wtgvb_deleteoptions_array'] as $k => $o){      
                    delete_option($o);
                    VideoBlogger_Notice::notice_depreciated(sprintf(__('Option record %s has been deleted.','video-blogger'),$o),'success','Tiny',__('Option Record Deleted','video-blogger'),'','echo'); 
                }      
            }
            
            wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=videoblogger' );
            exit;
                                                
        }else{           
            VideoBlogger_Notice::notice_depreciated(__('You do not have the required permissions to un-install Video Blogger. The Wordpress role required is delete_plugins, usually granted to Administrators.','video-blogger'), 'warning', 'Large',__('No Permission To Uninstall Video Blogger','video-blogger'),'','echo');
            return false;
        }
    }
    public function updatevideoblogger()
    {
        // we need requested update version, usually the version up from the installed version however we wont assume that
        if(!isset($_POST['wtgvb_plugin_update_now']) || !is_numeric($_POST['wtgvb_plugin_update_now']))
        {
             VideoBlogger_Notice::create_notice(__('The plugin update procedure was requested but a security parameter has not validated. No changes have been made and you will need to contact WebTechGlobal for support.','video-blogger'),'error','Large',__('Update Cannot Continue','video-blogger'));
            return false;
        }
        
        // perform update by calling the request version update procedure
        eval('$update_result_array = VideoBlogger_UpdatePlugin::patch_' . $_POST['wtgvb_plugin_update_now'] .'("update");');
                     
        if($update_result_array['failed'] == true)
        {           
            VideoBlogger_Notice::create_notice(__('The update procedure failed, the reason should be displayed below. Please try again unless
            the notice below indicates not to. If a second attempt fails, please seek support.','video-blogger'),'error','Small',__('Update Failed','video-blogger'));
                    
            VideoBlogger_Notice::create_notice($update_result_array['failedreason'],'info','Small',__('Update Failed Reason','video-blogger'));
        }
        else
        {  
            // storing the current file version will prevent user coming back to the update screen
            global $wtgvb_currentversion;        
            update_option('wtgvb_installedversion',$wtgvb_currentversion);

            VideoBlogger_Notice::create_notice(__('Good news, the update procedure was complete. If you do not see any errors or any notices indicating
            a problem was detected it means the procedure worked. Please ensure any new changes suit your needs.','video-blogger'),'success','Small',__('Update Complete','video-blogger'));
            
            // do a redirect so that the plugins menu is reloaded
            wp_redirect( get_bloginfo('url') . '/wp-admin/admin.php?page=videoblogger' );
            exit;                
        }
    }
    /**
    * Save drip feed limits  
    */
    public function schedulesettings()
    {
        global $wtgvb_schedule_array;

        // if any required values are not in $_POST set them to zero
        if(!isset($_POST['day'])){
            $wtgvb_schedule_array['limits']['day'] = 0;        
        }else{
            $wtgvb_schedule_array['limits']['day'] = $_POST['day'];            
        }
        
        if(!isset($_POST['hour'])){
            $wtgvb_schedule_array['limits']['hour'] = 0;
        }else{
            $wtgvb_schedule_array['limits']['hour'] = $_POST['hour'];            
        }
        
        if(!isset($_POST['session'])){
            $wtgvb_schedule_array['limits']['session'] = 0;
        }else{
            $wtgvb_schedule_array['limits']['session'] = $_POST['session'];            
        }
                                 
        // ensure $wtgvb_schedule_array is an array, it may be boolean false if schedule has never been set
        if(isset($wtgvb_schedule_array) && is_array($wtgvb_schedule_array)){
            
            // if times array exists, unset the [times] array
            if(isset($wtgvb_schedule_array['days'])){
                unset($wtgvb_schedule_array['days']);    
            }
            
            // if hours array exists, unset the [hours] array
            if(isset($wtgvb_schedule_array['hours'])){
                unset($wtgvb_schedule_array['hours']);    
            }
            
        }else{
            // $schedule_array value is not array, this is first time it is being set
            $wtgvb_schedule_array = array();
        }
        
        // loop through all days and set each one to true or false
        if(isset($_POST['wtgvb_scheduleday_list'])){
            foreach($_POST['wtgvb_scheduleday_list'] as $key => $submitted_day){
                $wtgvb_schedule_array['days'][$submitted_day] = true;        
            }  
        } 
        
        // loop through all hours and add each one to the array, any not in array will not be permitted                              
        if(isset($_POST['wtgvb_schedulehour_list'])){
            foreach($_POST['wtgvb_schedulehour_list'] as $key => $submitted_hour){
                $wtgvb_schedule_array['hours'][$submitted_hour] = true;        
            }           
        }    

        if(isset($_POST['wtgvb_eventtype_deleteuserswaiting']))
        {
            $wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 1;                
        }
        
        if(isset($_POST['event_sendemails']))
        {
            $wtgvb_schedule_array['eventtypes']['sendemails']['switch'] = 1;    
        }        
  
        VideoBlogger_WP::update_option_schedule_array($wtgvb_schedule_array);
        VideoBlogger_Notice::notice_depreciated(__('Schedule settings have been saved.','video-blogger'),'success','Large',__('Schedule Times Saved','video-blogger'));   
    }
    /**
    * Updates existing installation
    */
    public function pluginupdate()
    {
        if(!current_user_can('update_plugins')){wp_die(__('You are not permitted to perform plugin updates and so cannot upate Video Blogger.','video-blogger'));}
        
        global $wtgvb_settings,$wtgvb_currentversion;
        
        // re-install tab menu if installation has the array stored in option record and not using array in file
        if(isset($wtgvb_settings['tabmenu']['loadmethod']) && $wtgvb_settings['tabmenu']['loadmethod'] != 'file'){
            wtgvb_INSTALL_tabmenu_settings();
        }
            
        // re-install admin settings
        wtgvb_INSTALL_admin_settings();

        // update locally stored version number
        update_option('wtgvb_installedversion',$wtgvb_currentversion);        
        update_option('wtgvb_installeddate',time()); 

        VideoBlogger_Notice::notice_postresult_depreciated('success',__('Plugin Update Complete','video-blogger'),__('Please have a browse over all of the
        plugins screens, ensure key settings are as you need them and where applicable check the front-end of 
        your blog to ensure nothing has gone wrong.','video-blogger'));
    }   
    public function interfacesettings()
    {
        // form submit dialog
        $wtgvb_settings['interface']['forms']['dialog']['status'] = $_POST['wtgvb_radiogroup_dialog'];
        // panel support buttons                                                                                         
        $wtgvb_settings['interface']['panels']['supportbuttons']['status'] = $_POST['wtgvb_radiogroup_supportbuttons']; 
        
        VideoBlogger_WP::update_settings($wtgvb_settings); 
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Interface Settings Saved','video-blogger'),__('This plugins
        interface settings may make changes you do not expect. Please
        keep this in mind and ask us if you are unsure about something.','video-blogger'));            
    }   
    public function logsearchoptions()
    {
        global $wtgvb_settings;
        
        // first unset all criteria
        if(isset($wtgvb_settings['log']['logscreen'])){
            unset($wtgvb_settings['log']['logscreen']);
        }
        
        ##################################################
        #         COLUMN DISPlAY SETTINGS FIRST          #
        ##################################################
        // if a column is set in the array, it indicates that it is to be displayed, we unset those not to be set, we dont set them to false
        if(isset($_POST['wtgvb_logfields'])){
            foreach($_POST['wtgvb_logfields'] as $column){
                $wtgvb_settings['log']['logscreen']['displayedcolumns'][$column] = true;                   
            }
        }
            
        ############################################################
        #          SAVE CUSTOM SEARCH CRITERIA CHECK BOXES         #
        ############################################################              
        // outcome criteria
        if(isset($_POST['wtgvb_log_outcome'])){    
            foreach($_POST['wtgvb_log_outcome'] as $outcomecriteria){
                $wtgvb_settings['log']['logscreen']['outcomecriteria'][$outcomecriteria] = true;                   
            }            
        } 
        
        // type criteria
        if(isset($_POST['wtgvb_log_type'])){
            foreach($_POST['wtgvb_log_type'] as $typecriteria){
                $wtgvb_settings['log']['logscreen']['typecriteria'][$typecriteria] = true;                   
            }            
        }         

        // category criteria
        if(isset($_POST['wtgvb_log_category'])){
            foreach($_POST['wtgvb_log_category'] as $categorycriteria){
                $wtgvb_settings['log']['logscreen']['categorycriteria'][$categorycriteria] = true;                   
            }            
        }         

        // priority criteria
        if(isset($_POST['wtgvb_log_priority'])){
            foreach($_POST['wtgvb_log_priority'] as $prioritycriteria){
                $wtgvb_settings['log']['logscreen']['prioritycriteria'][$prioritycriteria] = true;                   
            }            
        }         

        ############################################################
        #         SAVE CUSTOM SEARCH CRITERIA SINGLE VALUES        #
        ############################################################
        // page
        if(isset($_POST['wtgvb_pluginpages_logsearch']) && $_POST['wtgvb_pluginpages_logsearch'] != 'notselected'){
            $wtgvb_settings['log']['logscreen']['page'] = $_POST['wtgvb_pluginpages_logsearch'];
        }   
        // action
        if(isset($_POST['csv2pos_logactions_logsearch']) && $_POST['csv2pos_logactions_logsearch'] != 'notselected'){
            $wtgvb_settings['log']['logscreen']['action'] = $_POST['csv2pos_logactions_logsearch'];
        }   
        // screen
        if(isset($_POST['wtgvb_pluginscreens_logsearch']) && $_POST['wtgvb_pluginscreens_logsearch'] != 'notselected'){
            $wtgvb_settings['log']['logscreen']['screen'] = $_POST['wtgvb_pluginscreens_logsearch'];
        }  
        // line
        if(isset($_POST['wtgvb_logcriteria_phpline'])){
            $wtgvb_settings['log']['logscreen']['line'] = $_POST['wtgvb_logcriteria_phpline'];
        }  
        // file
        if(isset($_POST['wtgvb_logcriteria_phpfile'])){
            $wtgvb_settings['log']['logscreen']['file'] = $_POST['wtgvb_logcriteria_phpfile'];
        }          
        // function
        if(isset($_POST['wtgvb_logcriteria_phpfunction'])){
            $wtgvb_settings['log']['logscreen']['function'] = $_POST['wtgvb_logcriteria_phpfunction'];
        }
        // panel name
        if(isset($_POST['wtgvb_logcriteria_panelname'])){
            $wtgvb_settings['log']['logscreen']['panelname'] = $_POST['wtgvb_logcriteria_panelname'];
        }
        // IP address
        if(isset($_POST['wtgvb_logcriteria_ipaddress'])){
            $wtgvb_settings['log']['logscreen']['ipaddress'] = $_POST['wtgvb_logcriteria_ipaddress'];
        }
        // user id
        if(isset($_POST['wtgvb_logcriteria_userid'])){
            $wtgvb_settings['log']['logscreen']['userid'] = $_POST['wtgvb_logcriteria_userid'];
        }

        VideoBlogger_WP::update_settings($wtgvb_settings);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Log Search Settings Saved','video-blogger'),__('Your selections have an instant effect. Please browse the Log screen for the results of your new search.','video-blogger'));                   
    }      
    public function newadsnippet(){
        global $wtgvb_settings;
                  
        $key = 0;
        if(isset($wtgvb_settings['adsnippets']))
        {
            $key = VideoBlogger_WP::get_array_nextkey($wtgvb_settings['adsnippets']);
        }
        
        $wtgvb_settings['adsnippets'][$key]['time'] = time();
        $wtgvb_settings['adsnippets'][$key]['snippet'] = stripslashes_deep($_POST['wtgvb_submitad_snippet']);
    
        $wtgvb_settings['adsnippets'][$key]['source'] = 'other';
        if(strstr($_POST['wtgvb_submitad_snippet'],'google_ad_client')){$wtgvb_settings['adsnippets'][$key]['source'] = 'google';}
            
        VideoBlogger::update_option_adminsettings($wtgvb_settings);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Ad Snippet Saved','video-blogger'),__('Your new Ad will be diplayed on applicable posts immediately. Some ads may not show for many minutes, including Google AdSense.','video-blogger'));     
    }
    public function editads(){
        global $wtgvb_settings;
        
        // first perform deletions before we make further changes to the array
        // however at some point we will control ads by an AD ID not array key
        foreach($_POST as $name => $value){
            
            if(strstr($name,'wtgvb_ad_delete_')){
                
                $arrayID = str_replace('wtgvb_ad_delete_','',$name);    
                unset($wtgvb_settings['adsnippets'][$arrayID]);
            }
        } 
        
        VideoBlogger::update_option_adminsettings($wtgvb_settings);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Ads Saved','video-blogger'),__('Changes to your Ads have been saved and take effect immediately.','video-blogger'));
    }
    public function adconfiguration(){
        global $wtgvb_settings;
        $wtgvb_settings['adoptions']['status'] = $_POST['wtgvb_adsenseoptions_status'];
        $wtgvb_settings['adoptions']['maximumads'] = $_POST['wtgvb_adsenseoptions_maximum'];
        VideoBlogger::update_option_adminsettings($wtgvb_settings);
        VideoBlogger_Notice::n_postresult_depreciated('success',__('Ad Options Saved','video-blogger'),__('Your ad options have been saved and take effect immediately.','video-blogger'));      
    }
}       
?>