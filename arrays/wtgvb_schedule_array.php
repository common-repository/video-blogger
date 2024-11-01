<?php
/** 
 * Default schedule array for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */

$wtgvb_schedule_array = array();
// history
$wtgvb_schedule_array['history']['lastreturnreason'] = 'None';
$wtgvb_schedule_array['history']['lasteventtime'] = time();
$wtgvb_schedule_array['history']['lasteventtype'] = 'None';
$wtgvb_schedule_array['history']['day_lastreset'] = time();
$wtgvb_schedule_array['history']['hour_lastreset'] = time();
$wtgvb_schedule_array['history']['hourcounter'] = 1;
$wtgvb_schedule_array['history']['daycounter'] = 1;
$wtgvb_schedule_array['history']['lasteventaction'] = 'None';
// times/days
$wtgvb_schedule_array['days']['monday'] = true;
$wtgvb_schedule_array['days']['tuesday'] = true;
$wtgvb_schedule_array['days']['wednesday'] = true;
$wtgvb_schedule_array['days']['thursday'] = true;
$wtgvb_schedule_array['days']['friday'] = true;
$wtgvb_schedule_array['days']['saturday'] = true;
$wtgvb_schedule_array['days']['sunday'] = true;
// times/hours
$wtgvb_schedule_array['hours'][0] = true;
$wtgvb_schedule_array['hours'][1] = true;
$wtgvb_schedule_array['hours'][2] = true;
$wtgvb_schedule_array['hours'][3] = true;
$wtgvb_schedule_array['hours'][4] = true;
$wtgvb_schedule_array['hours'][5] = true;
$wtgvb_schedule_array['hours'][6] = true;
$wtgvb_schedule_array['hours'][7] = true;
$wtgvb_schedule_array['hours'][8] = true;
$wtgvb_schedule_array['hours'][9] = true;
$wtgvb_schedule_array['hours'][10] = true;
$wtgvb_schedule_array['hours'][11] = true;
$wtgvb_schedule_array['hours'][12] = true;
$wtgvb_schedule_array['hours'][13] = true;
$wtgvb_schedule_array['hours'][14] = true;
$wtgvb_schedule_array['hours'][15] = true;
$wtgvb_schedule_array['hours'][16] = true;
$wtgvb_schedule_array['hours'][17] = true;
$wtgvb_schedule_array['hours'][18] = true;
$wtgvb_schedule_array['hours'][19] = true;
$wtgvb_schedule_array['hours'][20] = true;
$wtgvb_schedule_array['hours'][21] = true;
$wtgvb_schedule_array['hours'][22] = true;
$wtgvb_schedule_array['hours'][23] = true;
// limits
$wtgvb_schedule_array['limits']['hour'] = '1000';
$wtgvb_schedule_array['limits']['day'] = '5000';
$wtgvb_schedule_array['limits']['session'] = '300';
// event types (update event_action() if adding more eventtypes)
// deleteuserswaiting - this is the auto deletion of new users who have not yet activated their account 
$wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['name'] = 'Delete Users Waiting'; 
$wtgvb_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 0;
// send emails - rows are stored in wp_wtgvbmailing table for mass email campaigns 
$wtgvb_schedule_array['eventtypes']['sendemails']['name'] = 'Send Emails'; 
$wtgvb_schedule_array['eventtypes']['sendemails']['switch'] = 0;    
?>