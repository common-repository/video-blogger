<?php
/** 
 * Free edition file (applies to paid also) for Video Blogger plugin by WebTechGlobal.co.uk
 * 
 * This file is used in all WebTechGlobal plugins. Changes should apply to all. Use a different file/screen for plugin specific requirements.
 *
 * @package Video Blogger
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 * 
 * 1. URL search ability
 * 2. Stores last search
 */
 
global $wpdb,$wtgvb_settings;

// default log screen array
$search_headers_array = array();
//rowid (treated different from others)
$search_headers_array['row_id']['title'] = 'Row ID';
$search_headers_array['row_id']['select'] = true;// add to SELECT part of query
$search_headers_array['row_id']['display'] = true;// display in table
// outcome
$search_headers_array['outcome']['title'] = 'Outcome';
$search_headers_array['outcome']['select'] = false;
$search_headers_array['outcome']['display'] = false;
$search_headers_array['outcome']['searchoptions'] = false;// 0|1
// type
$search_headers_array['type']['title'] = 'Type';
$search_headers_array['type']['select'] = false;
$search_headers_array['type']['display'] = false;
$search_headers_array['type']['searchoptions'] = false;// general|error|trace
// priority
$search_headers_array['priority']['title'] = 'Priority';
$search_headers_array['priority']['select'] = false;
$search_headers_array['priority']['display'] = false;
$search_headers_array['priority']['searchoptions'] = false;// low|high|medium
// timestamp
$search_headers_array['timestamp']['title'] = 'Date + Time';
$search_headers_array['timestamp']['select'] = false;
$search_headers_array['timestamp']['display'] = false;
$search_headers_array['timestamp']['searchoptions'] = false;// onehour|oneday|oneweek 
// line
$search_headers_array['line']['title'] = 'Line';
$search_headers_array['line']['select'] = false;
$search_headers_array['line']['display'] = false;
$search_headers_array['line']['searchoptions'] = false;
// file
$search_headers_array['file']['title'] = 'File';
$search_headers_array['file']['select'] = false;
$search_headers_array['file']['display'] = false;
$search_headers_array['file']['searchoptions'] = false;    
// function
$search_headers_array['function']['title'] = 'Function';
$search_headers_array['function']['select'] = false;
$search_headers_array['function']['display'] = false;
$search_headers_array['function']['searchoptions'] = false;
// sqlresult
$search_headers_array['sqlresult']['title'] = 'SQL Result';
$search_headers_array['sqlresult']['select'] = false;
$search_headers_array['sqlresult']['display'] = false;
$search_headers_array['sqlresult']['searchoptions'] = false;
// sqlquery
$search_headers_array['sqlquery']['title'] = 'SQL Query';
$search_headers_array['sqlquery']['select'] = false;
$search_headers_array['sqlquery']['display'] = false;
$search_headers_array['sqlquery']['searchoptions'] = false;
// sqlerror
$search_headers_array['sqlerror']['title'] = 'SQL Error';
$search_headers_array['sqlerror']['select'] = false;
$search_headers_array['sqlerror']['display'] = false;
$search_headers_array['sqlerror']['searchoptions'] = false;
// wordpresserror
$search_headers_array['wordpresserror']['title'] = 'WP Error';
$search_headers_array['wordpresserror']['select'] = false;
$search_headers_array['wordpresserror']['display'] = false;
$search_headers_array['wordpresserror']['searchoptions'] = false;
// screenshoturl
$search_headers_array['screenshoturl']['title'] = 'Screenshot URL';
$search_headers_array['screenshoturl']['select'] = false;
$search_headers_array['screenshoturl']['display'] = false;
$search_headers_array['screenshoturl']['searchoptions'] = false;
// userscomment
$search_headers_array['userscomment']['title'] = 'Users Comment';
$search_headers_array['userscomment']['select'] = false;
$search_headers_array['userscomment']['display'] = false;
$search_headers_array['userscomment']['searchoptions'] = false;
// page
$search_headers_array['page']['title'] = 'Page';
$search_headers_array['page']['select'] = false;
$search_headers_array['page']['display'] = false;
$search_headers_array['page']['searchoptions'] = false;
// version
$search_headers_array['version']['title'] = 'Plugin Version';
$search_headers_array['version']['select'] = false;
$search_headers_array['version']['display'] = false;
$search_headers_array['version']['searchoptions'] = false;
// panelname
$search_headers_array['panelname']['title'] = 'Panel Name';
$search_headers_array['panelname']['select'] = false;
$search_headers_array['panelname']['display'] = false;
$search_headers_array['panelname']['searchoptions'] = false;
// tabscreenid
$search_headers_array['tabscreenid']['title'] = 'Screen ID';
$search_headers_array['tabscreenid']['select'] = false;
$search_headers_array['tabscreenid']['display'] = false;
$search_headers_array['tabscreenid']['searchoptions'] = false;
// tabscreenname
$search_headers_array['tabscreenname']['title'] = 'Screen Name';
$search_headers_array['tabscreenname']['select'] = false;
$search_headers_array['tabscreenname']['display'] = false;
$search_headers_array['tabscreenname']['searchoptions'] = false;
// dump
$search_headers_array['dump']['title'] = 'Dump';
$search_headers_array['dump']['select'] = false;
$search_headers_array['dump']['display'] = false;
$search_headers_array['dump']['searchoptions'] = false;
// ipaddress
$search_headers_array['ipaddress']['title'] = 'IP Address';
$search_headers_array['ipaddress']['select'] = false;
$search_headers_array['ipaddress']['display'] = false;
$search_headers_array['ipaddress']['searchoptions'] = false;
// userid
$search_headers_array['userid']['title'] = 'User ID';
$search_headers_array['userid']['select'] = false;
$search_headers_array['userid']['display'] = false;
$search_headers_array['userid']['searchoptions'] = false;
// comment
$search_headers_array['comment']['title'] = 'Comment';
$search_headers_array['comment']['select'] = false;
$search_headers_array['comment']['display'] = false;
$search_headers_array['comment']['searchoptions'] = false;       
// category
$search_headers_array['category']['title'] = 'Category';
$search_headers_array['category']['select'] = false;
$search_headers_array['category']['display'] = false;
$search_headers_array['category']['searchoptions'] = false;
// action
$search_headers_array['action']['title'] = 'Action';
$search_headers_array['action']['select'] = false;
$search_headers_array['action']['display'] = false;
$search_headers_array['action']['searchoptions'] = false;         
// thetrigger
$search_headers_array['thetrigger']['title'] = 'Trigger';
$search_headers_array['thetrigger']['select'] = false;
$search_headers_array['thetrigger']['display'] = false; 
$search_headers_array['thetrigger']['searchoptions'] = false;   
    
// set criteria
if(isset($_GET['wtgvblogsearch'])){
    foreach($search_headers_array as $key => $header){
        if(isset($_GET[$key . 'criteria'])){

            $search_headers_array[$key]['searchoptions'] = $_GET[$key . 'criteria'];// changes from false to indicate we want to use the preset criteria value
            $search_headers_array[$key]['select'] = true;
            $search_headers_array[$key]['display'] = true;

        }            
    } 
}elseif(!isset($_GET['wtgvblogsearch'])){
    foreach($search_headers_array as $key => $header){
        if(isset($wtgvb_settings['log']['logscreen'][$key . 'criteria'])){

            $search_headers_array[$key]['searchoptions'] = $wtgvb_settings['log']['logscreen'][$key . 'criteria'];// changes from false to indicate we want to use the preset criteria value
            $search_headers_array[$key]['select'] = true;
            $search_headers_array[$key]['display'] = true;

        }               
    }
}

// establish columns    
foreach($search_headers_array as $key => $header){
    if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns'][$key]) && $wtgvb_settings['log']['logscreen']['displayedcolumns'][$key] == true){
        $search_headers_array[$key]['select'] = true;
        $search_headers_array[$key]['display'] = true;
    }            
} 

// build and query
if(!VideoBlogger_WPDB::database_table_exist($wpdb->prefix . 'wtgvblog')){return false;}
      
$type = 'all';

$select = 'row_id,timestamp';
foreach($search_headers_array as $column => $column_array){
    if($search_headers_array[$column]['select'] == true){
        $select .= ',' . $column;    
    }
}    

// where
$where = 'row_id IS NOT NULL ';
foreach($search_headers_array as $column => $column_array){
    
    if(isset($search_headers_array[$column]['searchoptions']) && !empty($search_headers_array[$column]['searchoptions'])){
       
        $where .= '
        AND (';
        
        if(is_array($search_headers_array[$column]['searchoptions']))
        {
            $array = $search_headers_array[$column]['searchoptions'];    
        }
        else
        {
            $array = explode(',',$search_headers_array[$column]['searchoptions']);
        }
        
        $total_strings = count($array);
        
        $added = 0;
        foreach($array as $key => $string){
            
            ++$added;
            
            $where .= $column . ' = "' . $string .'"';
            
            if($total_strings > 0 && $added != $total_strings){
                $where .= ' OR ';
            } 
        }  
        
        $where .= ')';
    }
    
} 
                      
$query = "SELECT ".$select." 
FROM wtgvblog 
WHERE ".$where."
ORDER BY timestamp DESC
LIMIT 200";
                     
// get_results
$query_results = $wpdb->get_results($query,ARRAY_A);
    
/*
echo '<br>';
echo wtgvb_link_ nonced('wpecsustomers','logsearchpostcreation','Search log for post creation related log entries','Scheduled Post Creation','&wtgvbtab=12&wtgvblogsearch=normal&categorycriteria=postcreation');
echo wtgvb_link_ nonced('wpecsustomers','logsearchpostcreationstrict','Search log for entries where posts were created','Scheduled Post Creation (strict)','&wtgvbtab=12&wtgvblogsearch=normal&categorycriteria=postcreation&prioritycriteria=medium');
echo wtgvb_link_ nonced('wpecsustomers','logsearchpostcreationstrict','Search log for entries where posts were updated','Scheduled Post Update','&wtgvbtab=12&wtgvblogsearch=normal&categorycriteria=postupdate&prioritycriteria=medium');
echo '<br><br>';
*/

++$panel_number;// increase panel counter so this panel has unique ID
$panel_array = VideoBlogger_WP::panel_array($pageid,$panel_number,$wtgvb_tab_number);
$panel_array['panel_name'] = 'logsearchoptions';// slug to act as a name and part of the panel ID 
$panel_array['panel_title'] = __('Stored Search Criteria');// user seen panel header text  
$panel_array['panel_id'] = $panel_array['panel_name'].$panel_number;// creates a unique id, may change from version to version but within a version it should be unique
$panel_array['panel_state'] = 'closed';
// Form Settings - create the array that is passed to jQuery form functions
$jsform_set_override = array();
$jsform_set = VideoBlogger_WP::jqueryform_commonarrayvalues($pageid,$panel_array['tabnumber'],$panel_array['panel_number'],$panel_array['panel_name'],$panel_array['panel_title'],$jsform_set_override);               
?>
<?php VideoBlogger_WP::panel_header( $panel_array );?> 

    <?php 
    // begin form and add hidden values
    VideoBlogger_WP::formstart_standard($jsform_set['form_name'],$jsform_set['form_id'],'post','wtgvb_form',$wtgvb_form_action);
    VideoBlogger_WP::hidden_form_values($panel_array['panel_name'],$panel_array['panel_title'],$panel_array['panel_number']);?>

    <p>This panel and these options are not affected by URL searches. These options belong to your custom search criteria which this screen defaults to
    when loading.</p>
    
    <h4>Outcomes</h4>
    <label for="wtgvb_log_outcomes_success"><input type="checkbox" name="wtgvb_log_outcome[]" id="wtgvb_log_outcomes_success" value="1" <?php if(isset($wtgvb_settings['log']['logscreen']['outcomecriteria']['1'])){echo 'checked';} ?>> Success</label>
    <br> 
    <label for="wtgvb_log_outcomes_fail"><input type="checkbox" name="wtgvb_log_outcome[]" id="wtgvb_log_outcomes_fail" value="0" <?php if(isset($wtgvb_settings['log']['logscreen']['outcomecriteria']['0'])){echo 'checked';} ?>> Fail/Rejected</label>

    <h4>Type</h4>
    <label for="wtgvb_log_type_general"><input type="checkbox" name="wtgvb_log_type[]" id="wtgvb_log_type_general" value="general" <?php if(isset($wtgvb_settings['log']['logscreen']['typecriteria']['general'])){echo 'checked';} ?>> General</label>
    <br>
    <label for="wtgvb_log_type_error"><input type="checkbox" name="wtgvb_log_type[]" id="wtgvb_log_type_error" value="error" <?php if(isset($wtgvb_settings['log']['logscreen']['typecriteria']['error'])){echo 'checked';} ?>> Errors</label>
    <br>
    <label for="wtgvb_log_type_trace"><input type="checkbox" name="wtgvb_log_type[]" id="wtgvb_log_type_trace" value="flag" <?php if(isset($wtgvb_settings['log']['logscreen']['typecriteria']['flag'])){echo 'checked';} ?>> Trace</label>

    <h4>Priority</h4>
    <label for="wtgvb_log_priority_low"><input type="checkbox" name="wtgvb_log_priority[]" id="wtgvb_log_priority_low" value="low" <?php if(isset($wtgvb_settings['log']['logscreen']['prioritycriteria']['low'])){echo 'checked';} ?>> Low</label>
    <br>
    <label for="wtgvb_log_priority_normal"><input type="checkbox" name="wtgvb_log_priority[]" id="wtgvb_log_priority_normal" value="normal" <?php if(isset($wtgvb_settings['log']['logscreen']['prioritycriteria']['normal'])){echo 'checked';} ?>> Normal</label>
    <br>
    <label for="wtgvb_log_priority_high"><input type="checkbox" name="wtgvb_log_priority[]" id="wtgvb_log_priority_high" value="high" <?php if(isset($wtgvb_settings['log']['logscreen']['prioritycriteria']['high'])){echo 'checked';} ?>> High</label>
    
    <h1>Custom Search</h1>
    <p>This search criteria is not currently stored, it will be used on the submission of this form only.</p>
 
    <h4>Page</h4>
    <select name="wtgvb_pluginpages_logsearch" id="wtgvb_pluginpages_logsearch" >
        <option value="notselected">Do Not Apply</option>
        <?php
        $current = '';
        if(isset($wtgvb_settings['log']['logscreen']['page']) && $wtgvb_settings['log']['logscreen']['page'] != 'notselected'){
            $current = $wtgvb_settings['log']['logscreen']['page'];
        } 
        VideoBlogger_WP::page_menuoptions($current);?> 
    </select>
    
    <h4>Action</h4> 
    <select name="csv2pos_logactions_logsearch" id="csv2pos_logactions_logsearch" >
        <option value="notselected">Do Not Apply</option>
        <?php 
        $current = '';
        if(isset($wtgvb_settings['log']['logscreen']['action']) && $wtgvb_settings['log']['logscreen']['action'] != 'notselected'){
            $current = $wtgvb_settings['log']['logscreen']['action'];
        }
        $action_results = VideoBlogger_WPDB::log_queryactions($current);
        if($action_results){
            foreach($action_results as $key => $action){
                $selected = '';
                if($action['action'] == $current){
                    $selected = 'selected="selected"';
                }
                echo '<option value="'.$action['action'].'" '.$selected.'>'.$action['action'].'</option>'; 
            }   
        }?> 
    </select>
    
    <h4>Screen Name</h4>
    <select name="wtgvb_pluginscreens_logsearch" id="wtgvb_pluginscreens_logsearch" >
        <option value="notselected">Do Not Apply</option>
        <?php 
        $current = '';
        if(isset($wtgvb_settings['log']['logscreen']['screen']) && $wtgvb_settings['log']['logscreen']['screen'] != 'notselected'){
            $current = $wtgvb_settings['log']['logscreen']['screen'];
        }
        VideoBlogger_WP::screens_menuoptions($current);?> 
    </select>
          
    <h4>PHP Line</h4>
    <input type="text" name="wtgvb_logcriteria_phpline" value="<?php if(isset($wtgvb_settings['log']['logscreen']['line'])){echo $wtgvb_settings['log']['logscreen']['line'];} ?>">
    
    <h4>PHP File</h4>
    <input type="text" name="wtgvb_logcriteria_phpfile" value="<?php if(isset($wtgvb_settings['log']['logscreen']['file'])){echo $wtgvb_settings['log']['logscreen']['file'];} ?>">
    
    <h4>PHP Function</h4>
    <input type="text" name="wtgvb_logcriteria_phpfunction" value="<?php if(isset($wtgvb_settings['log']['logscreen']['function'])){echo $wtgvb_settings['log']['logscreen']['function'];} ?>">
    
    <h4>Panel Name</h4>
    <input type="text" name="wtgvb_logcriteria_panelname" value="<?php if(isset($wtgvb_settings['log']['logscreen']['panelname'])){echo $wtgvb_settings['log']['logscreen']['panelname'];} ?>">

    <h4>IP Address</h4>
    <input type="text" name="wtgvb_logcriteria_ipaddress" value="<?php if(isset($wtgvb_settings['log']['logscreen']['ipaddress'])){echo $wtgvb_settings['log']['logscreen']['ipaddress'];} ?>">
   
    <h4>User ID</h4>
    <input type="text" name="wtgvb_logcriteria_userid" value="<?php if(isset($wtgvb_settings['log']['logscreen']['userid'])){echo $wtgvb_settings['log']['logscreen']['userid'];} ?>">    
  
    <h4>Display Fields</h4>                                                                                                                                        
    <label for="wtgvb_logfields_outcome"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_outcome" value="outcome" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['outcome'])){echo 'checked';} ?>> Outcome</label>
    <br>
    <label for="wtgvb_logfields_line"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_line" value="line" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['line'])){echo 'checked';} ?>> Line</label>
    <br>
    <label for="wtgvb_logfields_file"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_file" value="file" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['file'])){echo 'checked';} ?>> File</label> 
    <br>
    <label for="wtgvb_logfields_function"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_function" value="function" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['function'])){echo 'checked';} ?>> Function</label>
    <br>
    <label for="wtgvb_logfields_sqlresult"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_sqlresult" value="sqlresult" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlresult'])){echo 'checked';} ?>> SQL Result</label>
    <br>
    <label for="wtgvb_logfields_sqlquery"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_sqlquery" value="sqlquery" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlquery'])){echo 'checked';} ?>> SQL Query</label>
    <br>
    <label for="wtgvb_logfields_sqlerror"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_sqlerror" value="sqlerror" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlerror'])){echo 'checked';} ?>> SQL Error</label>
    <br>
    <label for="wtgvb_logfields_wordpresserror"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_wordpresserror" value="wordpresserror" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['wordpresserror'])){echo 'checked';} ?>> Wordpress Error</label>
    <br>
    <label for="wtgvb_logfields_screenshoturl"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_screenshoturl" value="screenshoturl" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['screenshoturl'])){echo 'checked';} ?>> Screenshot URL</label>
    <br>
    <label for="wtgvb_logfields_userscomment"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_userscomment" value="userscomment" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userscomment'])){echo 'checked';} ?>> Users Comment</label>
    <br>
    <label for="wtgvb_logfields_page"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_page" value="page" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['page'])){echo 'checked';} ?>> Page</label>
    <br>
    <label for="wtgvb_logfields_version"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_version" value="version" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['version'])){echo 'checked';} ?>> Plugin Version</label>
    <br>
    <label for="wtgvb_logfields_panelname"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_panelname" value="panelname" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['panelname'])){echo 'checked';} ?>> Panel Name</label>
    <br>
    <label for="wtgvb_logfields_tabscreenname"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_tabscreenname" value="tabscreenname" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['outcome'])){echo 'checked';} ?>> Screen Name *</label>
    <br>
    <label for="wtgvb_logfields_dump"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_dump" value="dump" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['dump'])){echo 'checked';} ?>> Dump</label>
    <br>
    <label for="wtgvb_logfields_ipaddress"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_ipaddress" value="ipaddress" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['ipaddress'])){echo 'checked';} ?>> IP Address</label>
    <br>
    <label for="wtgvb_logfields_userid"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_userid" value="userid" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userid'])){echo 'checked';} ?>> User ID</label>
    <br>
    <label for="wtgvb_logfields_comment"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_comment" value="comment" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['comment'])){echo 'checked';} ?>> Developers Comment</label>
    <br>
    <label for="wtgvb_logfields_type"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_type" value="type" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['type'])){echo 'checked';} ?>> Type</label>
    <br>
    <label for="wtgvb_logfields_category"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_category" value="category" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['category'])){echo 'checked';} ?>> Category</label>
    <br>
    <label for="wtgvb_logfields_action"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_action" value="action" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['action'])){echo 'checked';} ?>> Action</label>
    <br>
    <label for="wtgvb_logfields_priority"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_priority" value="priority" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['priority'])){echo 'checked';} ?>> Priority</label> 
    <br>
    <label for="wtgvb_logfields_thetrigger"><input type="checkbox" name="wtgvb_logfields[]" id="wtgvb_logfields_thetrigger" value="thetrigger" <?php if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['thetrigger'])){echo 'checked';} ?>> Trigger</label> 
                
    <?php VideoBlogger_WP::formend_standard($panel_array['form_button'],$jsform_set['form_id']);?>

<?php VideoBlogger_WP::panel_footer();?>

<?php    
global $wpdb;
if(!VideoBlogger_WPDB::database_table_exist($wpdb->prefix . 'wtgvblog'))
{
    echo '<p>'. __('The database table for storing log entries has not been installed. You can install it on the Install screen.','video-blogger') .'</p>';
}
elseif(!$query_results || empty($query_results))
{
    echo '<strong>'. __('There are no log entries matches your current search criteria.','video-blogger') .'</strong>';
}
else
{   
    VideoBlogger_WP::tablestart();
    echo ' 
    <thead>
        <tr>';
        
            echo '<th>row_id</th>'; 
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['outcome']) || isset($_GET['outcomecriteria'])){ echo '<th>outcome</th>'; }
            echo '<th>timestamp</th>';
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['category']) || isset($_GET['categorycriteria'])){ echo '<th>category</th>';}
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['action'])){ echo '<th>action</th>';}        
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['line'])){ echo '<th>line</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['file'])){ echo '<th>file</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['function'])){ echo '<th>function</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlresult'])){ echo '<th>sqlresult</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlquery'])){ echo '<th>sqlquery</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlerror'])){ echo '<th>sqlerror</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['wordpresserror'])){ echo '<th>wordpresserror</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['screenshoturl'])){ echo '<th>screenshoturl</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userscomment'])){ echo '<th>userscomment</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['page'])){ echo '<th>page</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['version'])){ echo '<th>version</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['panelname'])){ echo '<th>panelname</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['tabscreenid'])){ echo '<th>tabscreenid</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['tabscreenname'])){ echo '<th>tabscreenname</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['dump'])){ echo '<th>dump</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['ipaddress'])){ echo '<th>ipaddress</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userid'])){ echo '<th>userid</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['comment'])){ echo '<th>comment</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['type']) || isset($_GET['typecriteria'])){ echo '<th>type</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['priority'])){ echo '<th>priority</th>';}                                    
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['thetrigger'])){ echo '<th>thetrigger</th>';}
            
        echo '</tr>
    </thead>'; 

    echo '
    <tfoot>
        <tr>';
        
            echo '<th>row_id</th>';
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['outcome'])){ echo '<th>outcome</th>'; }
            echo '<th>timestamp</th>';
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['category']) || isset($_GET['categorycriteria'])){ echo '<th>category</th>';}
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['action'])){ echo '<th>action</th>';}        
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['line'])){ echo '<th>line</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['file'])){ echo '<th>file</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['function'])){ echo '<th>function</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlresult'])){ echo '<th>sqlresult</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlquery'])){ echo '<th>sqlquery</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlerror'])){ echo '<th>sqlerror</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['wordpresserror'])){ echo '<th>wordpresserror</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['screenshoturl'])){ echo '<th>screenshoturl</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userscomment'])){ echo '<th>userscomment</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['page'])){ echo '<th>page</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['version'])){ echo '<th>version</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['panelname'])){ echo '<th>panelname</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['tabscreenid'])){ echo '<th>tabscreenid</th>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['tabscreenname'])){ echo '<th>tabscreenname</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['dump'])){ echo '<th>dump</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['ipaddress'])){ echo '<th>ipaddress</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userid'])){ echo '<th>userid</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['comment'])){ echo '<th>comment</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['type'])){ echo '<th>type</th>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['priority'])){ echo '<th>priority</th>';}                                                      
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['thetrigger'])){ echo '<th>thetrigger</th>';}
            
        echo '</tr>
    </tfoot>
    <tbody>';

    foreach($query_results as $key => $row){

        echo '<tr>';
        
            echo '<td>'.$row['row_id'].'</td>';
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['outcome'])){ echo '<td>'.$row['outcome'].'</td>'; }
            echo '<td>'.$row['timestamp'].'</td>';
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['category']) || isset($_GET['categorycriteria'])){ echo '<td>'.$row['category'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['action'])){ echo '<td>'.$row['action'].'</td>'; }        
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['line'])){ echo '<td>'.$row['line'].'</td>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['file'])){ echo '<td>'.$row['file'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['function'])){ echo '<td>'.$row['function'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlresult'])){ echo '<td>'.$row['sqlresult'].'</td>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlquery'])){ echo '<td>'.$row['sqlquery'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['sqlerror'])){ echo '<td>'.$row['sqlerror'].'</td>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['wordpresserror'])){ echo '<td>'.$row['wordpresserror'].'</td>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['screenshoturl'])){ echo '<td>'.$row['screenshoturl'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userscomment'])){ echo '<td>'.$row['userscomment'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['page'])){ echo '<td>'.$row['page'].'</td>'; }  
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['version'])){ echo '<td>'.$row['version'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['panelname'])){ echo '<td>'.$row['panelname'].'</td>'; }    
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['tabscreenname'])){ echo '<td>'.$row['tabscreenname'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['dump'])){ echo '<td>'.$row['dump'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['ipaddress'])){ echo '<td>'.$row['ipaddress'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['userid'])){ echo '<td>'.$row['userid'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['comment'])){ echo '<td>'.$row['comment'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['type'])  || isset($_GET['typecriteria'])){ echo '<td>'.$row['type'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['priority'])){ echo '<td>'.$row['priority'].'</td>'; }
            if(isset($wtgvb_settings['log']['logscreen']['displayedcolumns']['thetrigger'])){ echo '<td>'.$row['thetrigger'].'</td>'; }
            
        echo '</tr>';
          
    }

    echo '</tbody></table>';
}
?>