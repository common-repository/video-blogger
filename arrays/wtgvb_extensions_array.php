<?php
/** 
 * Default extensions array for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */

 
$wtgvb_extensions_array = array();
$wtgvb_extensions_array['arrayinfo']['description'] = __('list of known extensions to facilate promotion and installation','video-blogger');

/*
$wtgvb_extensions_array['name'] = '';
$wtgvb_extensions_array['title'] = '';
$wtgvb_extensions_array['authorname'] = '';
$wtgvb_extensions_array['authorwp'] = '';
$wtgvb_extensions_array['authorwplink'] = ''; 
$wtgvb_extensions_array['website'] = '';
$wtgvb_extensions_array['description'] = '';
$wtgvb_extensions_array['rating'] = '';
*/

# Ryanair 
$wtgvb_extensions_array['extensions']['df1']['title'] = 'Ryanair DF1';// an actual project title
$wtgvb_extensions_array['extensions']['df1']['authorname'] = 'Ryan Bayne';// a real name of extension developer 
$wtgvb_extensions_array['extensions']['df1']['authorwp'] = 'WebTechGlobal';// username on Wordpress if any
$wtgvb_extensions_array['extensions']['df1']['authorwplink'] = 'http://wordpress.org/support/profile/webtechglobal';// link to Wordpress profile if any 
$wtgvb_extensions_array['extensions']['df1']['website'] = 'http://www.videoblogger.com';// page providing the extension download
$wtgvb_extensions_array['extensions']['df1']['description'] = __('Information about this extension is confidential','video-blogger');// small sentence explaining what extension does
# Twitter Schedule
$wtgvb_extensions_array['extensions']['twitterschedule']['title'] = 'Twitter Schedule';
$wtgvb_extensions_array['extensions']['twitterschedule']['authorname'] = 'Zara Walsh';
$wtgvb_extensions_array['extensions']['twitterschedule']['authorwp'] = '';
$wtgvb_extensions_array['extensions']['twitterschedule']['authorwplink'] = ''; 
$wtgvb_extensions_array['extensions']['twitterschedule']['website'] = '';
$wtgvb_extensions_array['extensions']['twitterschedule']['description'] = __('Schedules all posts to be tweeted at a natural pace','video-blogger');
# Amazon API
$wtgvb_extensions_array['extensions']['amazonapi']['title'] = 'Amazon API';
$wtgvb_extensions_array['extensions']['amazonapi']['authorname'] = 'Ryan Bayne';
$wtgvb_extensions_array['extensions']['amazonapi']['authorwp'] = '';
$wtgvb_extensions_array['extensions']['amazonapi']['authorwplink'] = ''; 
$wtgvb_extensions_array['extensions']['amazonapi']['website'] = '';
$wtgvb_extensions_array['extensions']['amazonapi']['description'] = __('Auto-blog using Amazon data feed','video-blogger');
?>
