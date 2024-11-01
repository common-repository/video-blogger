<?php
/** 
 * General settings tab view for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */
?>
  
<div class="wtgvb_unlimitedcolumns_container">

    <div class="wtgvb_unlimitedcolumns_left">
    
        <h4><?php _e('Operational Settings','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Options that apply to all all sections and procedures...','video-blogger');?></p>
                
                <form method="post" name="operationsettings" action="<?php VideoBlogger_ui::form_action(); ?>">
                
                    <?php VideoBlogger_WP::hidden_form_values('operationsettings',__('Operational Settings','video-blogger') );?>     

                    <table class="form-table">
                    
                        <!-- Option Start -->

                        <tr valign="top">
                            <th scope="row"><?php _e('Log','video-blogger'); ?></th>
                            <td>
                                <?php 
                                // if is not set ['admintriggers']['newcsvfiles']['status'] then it is enabled by default
                                if(!isset($wtgvb_settings['reporting']['uselog']))
                                {
                                    $radio1_uselog_enabled = 'checked'; 
                                    $radio2_uselog_disabled = '';                    
                                }
                                else
                                {
                                    if($wtgvb_settings['reporting']['uselog'] == 1)
                                    {
                                        $radio1_uselog_enabled = 'checked'; 
                                        $radio2_uselog_disabled = '';    
                                    }
                                    elseif($wtgvb_settings['reporting']['uselog'] == 0)
                                    {
                                        $radio1_uselog_enabled = ''; 
                                        $radio2_uselog_disabled = 'checked';    
                                    }
                                }?>

                                <input type="radio" id="wtgvb_<?php echo $form_array['panel_name'];?>_logstatus_enable" name="wtgvb_radiogroup_logstatus" value="1" <?php echo $radio1_uselog_enabled;?> />
                                <label for="wtgvb_<?php echo $form_array['panel_name'];?>_logstatus_enable"> Enable</label>
                                <br />
                                <input type="radio" id="wtgvb_<?php echo $form_array['panel_name'];?>_logstatus_disable" name="wtgvb_radiogroup_logstatus" value="0" <?php echo $radio2_uselog_disabled;?> />
                                <label for="wtgvb_<?php echo $form_array['panel_name'];?>_logstatus_disable"> Disable</label>

                            </td>
                        </tr>
                        <!-- Option End -->
                        
                        <!-- Option Start -->
                        <?php
                        $log_file_limit = 1000;
                        if(isset($wtgvb_settings['reporting']['loglimit']) && is_numeric($wtgvb_settings['reporting']['loglimit'])){
                            $log_file_limit = $wtgvb_settings['reporting']['loglimit'];
                        } ?>         
                        <tr valign="top">
                            <th scope="row"><?php __('Log Entries Limit','video-blogger'); ?></th>
                            <td>
                                <label for="wtgvb_<?php echo $form_array['panel_name'];?>_loglimit">
                                <input type="text" name="wtgvb_loglimit" id="wtgvb_<?php echo $form_array['panel_name'];?>_loglimit" value="<?php echo $log_file_limit;?>"> rows</label>
                            </td>
                        </tr>
                        <!-- Option End -->     

                        <!-- Option Start -->
                        <tr valign="top">
                            <th scope="row"><?php __('Extension System','video-blogger'); ?></th>
                            <td>

                                <?php 
                                if(VideoBlogger_WP::option('wtgvb_extensions','get') == 'enable'){
                                    $radio1_checked_enabled = 'checked';
                                    $radio2_checked_disabled = '';    
                                }elseif(VideoBlogger_WP::option('wtgvb_extensions','get') == 'disable'){
                                    $radio1_checked_enabled = '';
                                    $radio2_checked_disabled = 'checked';    
                                }?>

                                <input type="radio" id="wtgvb_<?php echo $form_array['panel_name'];?>_enable" name="wtgvb_radiogroup_extensionstatus" value="enable" <?php echo $radio1_checked_enabled;?> />
                                <label for="wtgvb_<?php echo $form_array['panel_name'];?>_enable"> Enable</label>
                                <br />
                                <input type="radio" id="wtgvb_<?php echo $form_array['panel_name'];?>_disable" name="wtgvb_radiogroup_extensionstatus" value="disable" <?php echo $radio2_checked_disabled;?> />
                                <label for="wtgvb_<?php echo $form_array['panel_name'];?>_disable"> Disable</label>

                            </td>
                        </tr>
                        <!-- Option End -->

                    </table>
                 
                    <input class="button" type="submit" value="Submit" />
     
                </form>                 
                                                                              
            </div>
        </div> 
        
    </div>

    <div class="wtgvb_unlimitedcolumns_left">
    
        <h4><?php _e('Schedule Settings','video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Use the schedule to control repeat automation...','video-blogger');?></p>
                
                <form method="post" name="schedulesettings" action="<?php VideoBlogger_ui::form_action(); ?>">
                
                    <?php VideoBlogger_WP::hidden_form_values('schedulesettings',__('phpBB Core Settings','video-blogger'));?>
                        
                    <?php 
                    $days_array = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
                    $days_counter = 1;
                    foreach($days_array as $key => $day){
                        
                        // set checked status
                        if(isset($wtgvb_schedule_array['days'][$day])){
                            $day_checked = 'checked';
                        }else{
                            $day_checked = '';            
                        }
                             
                        echo '<input type="checkbox" name="wtgvb_scheduleday_list[]" id="daycheck'.$days_counter.'" value="'.$day.'" '.$day_checked.' />
                        <label for="daycheck'.$days_counter.'">'.ucfirst($day).'</label><br />';    
                        ++$days_counter;
                    }?>
             
                    <h4>Allowed Hours</h4>    
                   
                    <?php
                    // loop 24 times and create a checkbox for each hour
                    for($i=0;$i<24;$i++){
                        
                        // check if the current hour exists in array, if it exists then it is permitted, if it does not exist it is not permitted
                        if(isset($wtgvb_schedule_array['hours'][$i])){
                            $hour_checked = ' checked'; 
                        }else{
                            $hour_checked = '';
                        }
                        
                        echo '<input type="checkbox" name="wtgvb_schedulehour_list[]" id="hourcheck'.$i.'"  value="'.$i.'" '.$hour_checked.' />
                        <label for="hourcheck'.$i.'">'.$i.'</label>&nbsp;&nbsp;';    
                    }
                    ?>
                 
                    <h4><?php _e('Maximum Per Day','video-blogger'); ?></h4>

                    <input type="radio" id="wtgvb_radio1_dripfeedrate_maximumperday" name="day" value="1" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 1){echo 'checked';} ?> /><label for="wtgvb_radio1_dripfeedrate_maximumperday"> <?php _e('1','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio2_dripfeedrate_maximumperday" name="day" value="5" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 5){echo 'checked';} ?> /><label for="wtgvb_radio2_dripfeedrate_maximumperday"> <?php _e('5','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio3_dripfeedrate_maximumperday" name="day" value="10" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 10){echo 'checked';} ?> /><label for="wtgvb_radio3_dripfeedrate_maximumperday"> <?php _e('10','video-blogger'); ?> </label>&nbsp;  
                    <input type="radio" id="wtgvb_radio9_dripfeedrate_maximumperday" name="day" value="24" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 24){echo 'checked';} ?> /><label for="wtgvb_radio9_dripfeedrate_maximumperday"> <?php _e('24','video-blogger'); ?> </label>&nbsp;                    
                    <input type="radio" id="wtgvb_radio4_dripfeedrate_maximumperday" name="day" value="50" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 50){echo 'checked';} ?> /><label for="wtgvb_radio4_dripfeedrate_maximumperday"> <?php _e('50','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio5_dripfeedrate_maximumperday" name="day" value="250" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 250){echo 'checked';} ?> /><label for="wtgvb_radio5_dripfeedrate_maximumperday"> <?php _e('250','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio6_dripfeedrate_maximumperday" name="day" value="1000" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 1000){echo 'checked';} ?> /><label for="wtgvb_radio6_dripfeedrate_maximumperday"> <?php _e('1000','video-blogger'); ?> </label>&nbsp;                                                                                                                       
                    <input type="radio" id="wtgvb_radio7_dripfeedrate_maximumperday" name="day" value="2000" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 2000){echo 'checked';} ?> /><label for="wtgvb_radio7_dripfeedrate_maximumperday"> <?php _e('2000','video-blogger'); ?> </label>&nbsp; 
                    <input type="radio" id="wtgvb_radio8_dripfeedrate_maximumperday" name="day" value="5000" <?php if(isset($wtgvb_schedule_array['limits']['day']) && $wtgvb_schedule_array['limits']['day'] == 5000){echo 'checked';} ?> /><label for="wtgvb_radio8_dripfeedrate_maximumperday"> <?php _e('5000','video-blogger'); ?> </label>&nbsp;   
                 
                    <h4><?php _e('Maximum Per Hour','video-blogger'); ?></h4>

                    <input type="radio" id="wtgvb_radio1_dripfeedrate_maximumperhour" name="hour" value="1" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 1){echo 'checked';} ?> /><label for="wtgvb_radio1_dripfeedrate_maximumperhour"> <?php _e('1','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio2_dripfeedrate_maximumperhour" name="hour" value="5" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 5){echo 'checked';} ?> /><label for="wtgvb_radio2_dripfeedrate_maximumperhour"> <?php _e('5','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio3_dripfeedrate_maximumperhour" name="hour" value="10" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 10){echo 'checked';} ?> /><label for="wtgvb_radio3_dripfeedrate_maximumperhour"> <?php _e('10','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio9_dripfeedrate_maximumperhour" name="hour" value="24" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 24){echo 'checked';} ?> /><label for="wtgvb_radio9_dripfeedrate_maximumperhour"> <?php _e('24','video-blogger'); ?> </label>&nbsp;                    
                    <input type="radio" id="wtgvb_radio4_dripfeedrate_maximumperhour" name="hour" value="50" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 50){echo 'checked';} ?> /><label for="wtgvb_radio4_dripfeedrate_maximumperhour"> <?php _e('50','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio5_dripfeedrate_maximumperhour" name="hour" value="100" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 100){echo 'checked';} ?> /><label for="wtgvb_radio5_dripfeedrate_maximumperhour"> <?php _e('100','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio6_dripfeedrate_maximumperhour" name="hour" value="250" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 250){echo 'checked';} ?> /><label for="wtgvb_radio6_dripfeedrate_maximumperhour"> <?php _e('250','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio7_dripfeedrate_maximumperhour" name="hour" value="500" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 500){echo 'checked';} ?> /><label for="wtgvb_radio7_dripfeedrate_maximumperhour"> <?php _e('500','video-blogger'); ?> </label>&nbsp;       
                    <input type="radio" id="wtgvb_radio8_dripfeedrate_maximumperhour" name="hour" value="1000" <?php if(isset($wtgvb_schedule_array['limits']['hour']) && $wtgvb_schedule_array['limits']['hour'] == 1000){echo 'checked';} ?> /><label for="wtgvb_radio8_dripfeedrate_maximumperhour"> <?php _e('1000','video-blogger'); ?> </label>&nbsp;                                                                                                                        
                  
                    <h4><?php _e('Maximum Per Session/Event','video-blogger'); ?></h4>

                    <input type="radio" id="wtgvb_radio1_dripfeedrate_maximumpersession" name="session" value="1" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 1){echo 'checked';} ?> /><label for="wtgvb_radio1_dripfeedrate_maximumpersession"> <?php _e('1','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio2_dripfeedrate_maximumpersession" name="session" value="5" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 5){echo 'checked';} ?> /><label for="wtgvb_radio2_dripfeedrate_maximumpersession"> <?php _e('5','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio3_dripfeedrate_maximumpersession" name="session" value="10" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 10){echo 'checked';} ?> /><label for="wtgvb_radio3_dripfeedrate_maximumpersession"> <?php _e('10','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio9_dripfeedrate_maximumpersession" name="session" value="25" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 25){echo 'checked';} ?> /><label for="wtgvb_radio9_dripfeedrate_maximumpersession"> <?php _e('25','video-blogger'); ?> </label>&nbsp;                    
                    <input type="radio" id="wtgvb_radio4_dripfeedrate_maximumpersession" name="session" value="50" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 50){echo 'checked';} ?> /><label for="wtgvb_radio4_dripfeedrate_maximumpersession"> <?php _e('50','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio5_dripfeedrate_maximumpersession" name="session" value="100" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 100){echo 'checked';} ?> /><label for="wtgvb_radio5_dripfeedrate_maximumpersession"> <?php _e('100','video-blogger'); ?> </label>&nbsp;
                    <input type="radio" id="wtgvb_radio6_dripfeedrate_maximumpersession" name="session" value="200" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 200){echo 'checked';} ?> /><label for="wtgvb_radio6_dripfeedrate_maximumpersession"> <?php _e('200','video-blogger'); ?> </label>&nbsp;                                                                                                                        
                    <input type="radio" id="wtgvb_radio7_dripfeedrate_maximumpersession" name="session" value="300" <?php if(isset($wtgvb_schedule_array['limits']['session']) && $wtgvb_schedule_array['limits']['session'] == 300){echo 'checked';} ?> /><label for="wtgvb_radio7_dripfeedrate_maximumpersession"> <?php _e('300','video-blogger'); ?> </label>&nbsp;    
                                              
                    <h4><?php _e('Focus','video-blogger'); ?></h4>                                                                                                                           

                    <?php         
                    $default = true;
                    foreach($wtgvb_schedule_array['eventtypes'] as $eventtype => $eventtype_array){
                                  
                        $checked = '';
                        if(isset(${'focus'.$eventtype}) && ${'focus'.$eventtype} == 1){
                            $checked = 'checked="checked"';
                            $default = false;
                        }?>
                        
                        <input type="radio" id="wtgvb_focus_event_<?php echo $eventtype;?>" name="wtgvb_focus_event" value="1" <?php echo $checked;?>  />
                        <label for="wtgvb_focus_event_<?php echo $eventtype;?>"><?php echo $eventtype_array['name'];?></label><br /><?php           
                    }
                    ?>
                    
                    <input type="radio" id="wtgvb_focus_event_none" name="wtgvb_focus_event" value="1" <?php if($default){echo 'checked="checked"';}?>  />
                    <label for="wtgvb_focus_event_none">No Focus</label>        
                    
                    <!-- Automated Event Activation Option Start -->                                                                                                  
                    <h4><?php _e('Delete Waiting Users','video-blogger'); ?></h4>                

                    <input type="radio" id="wtgvb_radio1_eventtypeactivation_deleteuserswaiting" name="wtgvb_eventtype_deleteuserswaiting" value="1" <?php if(isset($wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch']) && $wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'] == 1){echo 'checked';} ?> />
                    <label for="wtgvb_radio1_eventtypeactivation_deleteuserswaiting"> Enabled</label>
                    <br />
                    <input type="radio" name="wtgvb_eventtype_deleteuserswaiting" value="0" <?php if(isset($wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch']) && $wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'] == 0 || !isset($wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'])){echo 'checked';} ?> />
                    <label for="wtgvb_radio2_eventtypeactivation_deleteuserswaiting"> Disabled</label> 
                    <!-- Automated Event Activation Option End -->
                    
                    <!-- Automated Event Activation Option Start -->                                                                                                  
                    <h4><?php _e('Send Emails','video-blogger'); ?></h4>                

                    <input type="radio" id="event_sendemails_enable" name="event_sendemails" value="1" <?php if(isset($wtgvb_schedule_array['eventtypes']['sendemails']['switch']) && $wtgvb_schedule_array['eventtypes']['sendemails']['switch'] == 1){echo 'checked';} ?> />
                    <label for="event_sendemails_enable"> Enabled</label>
                    <br />
                    <input type="radio" id="event_sendemails_disable" name="event_sendemails" value="0" <?php if(isset($wtgvb_schedule_array['eventtypes']['sendemails']['switch']) && $wtgvb_schedule_array['eventtypes']['sendemails']['switch'] == 0 || !isset($wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'])){echo 'checked';} ?> />
                    <label for="event_sendemails_disable"> Disabled</label> 
                    <!-- Automated Event Activation Option End -->                       

                    <h4><?php _e('Last Schedule Finish Reason','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['lastreturnreason'])){
                        echo $wtgvb_schedule_array['history']['lastreturnreason']; 
                    }else{
                        _e('No event refusal reason has been set yet','video-blogger');    
                    }?>
                    </p>
                    
                    <h4><?php _e('Events Counter - 60 Minute Period','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['hourcounter'])){
                        echo $wtgvb_schedule_array['history']['hourcounter']; 
                    }else{
                        _e('No events have been done during the current 60 minute period','video-blogger');    
                    }?>
                    </p> 

                    <h4><?php _e('Events Counter - 24 Hour Period','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['daycounter'])){
                        echo $wtgvb_schedule_array['history']['daycounter']; 
                    }else{
                        _e('No events have been done during the current 24 hour period','video-blogger');
                    }?>
                    </p>

                    <h4><?php _e('Last Event Type','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['lasteventtype'])){
                        
                        if($wtgvb_schedule_array['history']['lasteventtype'] == 'dataimport'){
                            echo 'Data Import';            
                        }elseif($wtgvb_schedule_array['history']['lasteventtype'] == 'dataupdate'){
                            echo 'Data Update';
                        }elseif($wtgvb_schedule_array['history']['lasteventtype'] == 'postcreation'){
                            echo 'Post Creation';
                        }elseif($wtgvb_schedule_array['history']['lasteventtype'] == 'postupdate'){
                            echo 'Post Update';
                        }elseif($wtgvb_schedule_array['history']['lasteventtype'] == 'twittersend'){
                            echo 'Twitter: New Tweet';
                        }elseif($wtgvb_schedule_array['history']['lasteventtype'] == 'twitterupdate'){
                            echo 'Twitter: Send Update';
                        }elseif($wtgvb_schedule_array['history']['lasteventtype'] == 'twitterget'){
                            echo 'Twitter: Get Reply';
                        }
                         
                    }else{
                        _e('No events have been carried out yet','video-blogger');    
                    }?>
                    </p>

                    <h4><?php _e('Last Event Action','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['lasteventaction'])){
                        echo $wtgvb_schedule_array['history']['lasteventaction']; 
                    }else{
                        _e('No event actions have been carried out yet','video-blogger');    
                    }?>
                    </p>
                        
                    <h4><?php _e('Last Event Time','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['lasteventtime'])){
                        echo date("F j, Y, g:i a",$wtgvb_schedule_array['history']['lasteventtime']); 
                    }else{
                        _e('No schedule events have ran on this server yet','video-blogger');    
                    }?>
                    </p>
                    
                    <h4><?php _e('Last Hourly Reset','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['hour_lastreset'])){
                        echo date("F j, Y, g:i a",$wtgvb_schedule_array['history']['hour_lastreset']); 
                    }else{
                        _e('No hourly reset has been done yet','video-blogger');    
                    }?>
                    </p>   
                        
                    <h4><?php _e('Last 24 Hour Period Reset','video-blogger'); ?></h4>
                    <p>
                    <?php 
                    if(isset($wtgvb_schedule_array['history']['day_lastreset'])){
                        echo date("F j, Y, g:i a",$wtgvb_schedule_array['history']['day_lastreset']); 
                    }else{
                        _e('No 24 hour reset has been done yet','video-blogger');    
                    }?>
                    </p> 
                       
                    <h4><?php _e('Your Servers Current Data and Time','video-blogger'); ?></h4>
                    <p><?php echo date("F j, Y, g:i a",time());?></p>                              
                                                 
                    <input class="button" type="submit" value="Submit" />
     
                </form>
                                                                                              
            </div>
        </div> 
    </div>
    
</div>