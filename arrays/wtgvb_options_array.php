<?php 
/** 
 * Array of core option record values for installation related procedures in Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */


$total_option_records = 0;// used to count total options and assign count to label
$wtgvb_options_array = array();
++$total_option_records;
// indicates if the options, tables and other installation changes altogether equal an installed state
$wtgvb_options_array['wtgvb_is_installed']['datatype'] = 'boolean';// array,boolean,string etc
$wtgvb_options_array['wtgvb_is_installed']['purpose'] = __('Indicates result of last installation status check, should hold value true for normal operation else an element of installation is missing.','video-blogger');
$wtgvb_options_array['wtgvb_is_installed']['name'] = __('Installation Switch','video-blogger');
$wtgvb_options_array['wtgvb_is_installed']['inputtype'] = 'hidden';
$wtgvb_options_array['wtgvb_is_installed']['defaultvalue'] = 'false';// NA if not applicable i.e. the default value is generated in the script
$wtgvb_options_array['wtgvb_is_installed']['public'] = 'false';// boolean, false indicates users are not to be made aware of this option because it is more for development use
$wtgvb_options_array['wtgvb_is_installed']['required'] = 'false';// some option arrays may be optional, if that is the case true will avoid installation status checks returning false
// main settings array now for public side also 
++$total_option_records;
$wtgvb_options_array['wtgvb_settings']['datatype'] = 'array';// array,boolean,string etc
$wtgvb_options_array['wtgvb_settings']['purpose'] = __('Settings for the administrator/backend only. These settings effect things that are to do with the backend only. They configure manual actions, tools and operations triggered by backend use.','video-blogger');
$wtgvb_options_array['wtgvb_settings']['name'] = __('Administration Settings','video-blogger');
$wtgvb_options_array['wtgvb_settings']['inputtype'] = 'hidden';
$wtgvb_options_array['wtgvb_settings']['defaultvalue'] = 'NA';// NA if not applicable i.e. the default value is generated in the script
$wtgvb_options_array['wtgvb_settings']['public'] = 'true';// boolean, false indicates users are not to be made aware of this option because it is more for development use
$wtgvb_options_array['wtgvb_settings']['required'] = 'true';// some option arrays may be optional, if that is the case true will avoid installation status checks returning false
// current installed version (in terms of options, database tables etc, not files)
$wtgvb_options_array['wtgvb_installedversion']['datatype'] = 'string';
$wtgvb_options_array['wtgvb_installedversion']['purpose'] = __('Used to determine gap between old and new version.','video-blogger');
$wtgvb_options_array['wtgvb_installedversion']['name'] = __('Latest Version','video-blogger');
$wtgvb_options_array['wtgvb_installedversion']['inputtype'] = 'hidden';
$wtgvb_options_array['wtgvb_installedversion']['defaultvalue'] = '0.0.0';
$wtgvb_options_array['wtgvb_installedversion']['public'] = 'false';
$wtgvb_options_array['wtgvb_installedversion']['required'] = 'true';
// installation date
$wtgvb_options_array['wtgvb_installeddate']['datatype'] = 'integer';
$wtgvb_options_array['wtgvb_installeddate']['purpose'] = __('Date last full installation was run','video-blogger');
$wtgvb_options_array['wtgvb_installeddate']['name'] = __('Install Date','video-blogger');
$wtgvb_options_array['wtgvb_installeddate']['inputtype'] = 'hidden';
$wtgvb_options_array['wtgvb_installeddate']['defaultvalue'] = time();
$wtgvb_options_array['wtgvb_installeddate']['public'] = 'false';
$wtgvb_options_array['wtgvb_installeddate']['required'] = 'true'; 
// schedule settings and statistics
$wtgvb_options_array['wtgvb_installeddate']['datatype'] = 'integer';
$wtgvb_options_array['wtgvb_installeddate']['purpose'] = __('Schedule settings and statistics','video-blogger');
$wtgvb_options_array['wtgvb_installeddate']['name'] = 'Schedule Settings';
$wtgvb_options_array['wtgvb_installeddate']['inputtype'] = 'hidden';
$wtgvb_options_array['wtgvb_installeddate']['defaultvalue'] = time();
$wtgvb_options_array['wtgvb_installeddate']['public'] = 'false';
$wtgvb_options_array['wtgvb_installeddate']['required'] = 'true'; 
?>
