<?php
/** 
 * Default administration settings for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */

// install main admin settings option record
$wtgvb_settings = array();
// interface
$wtgvb_settings['interface']['forms']['dialog']['status'] = 'hide';
// encoding
$wtgvb_settings['encoding']['type'] = 'utf8';
// admin user interface settings start
$wtgvb_settings['ui_advancedinfo'] = false;// hide advanced user interface information by default
$wtgvb_settings['ui_helpdialog_width'] = 800;
$wtgvb_settings['ui_helpdialog_height'] = 500;
// log
$wtgvb_settings['reporting']['uselog'] = 1;
$wtgvb_settings['reporting']['loglimit'] = 1000;
// other
$wtgvb_settings['ecq'] = array();
$wtgvb_settings['chmod'] = '0750';
// ad options
$wtgvb_settings['adoptions']['maximumads'] = 3;
// ads - 250 x 250 example
$wtgvb_settings['adsnippets'][0]['time'] = time();
$wtgvb_settings['adsnippets'][0]['source'] = 'google';
$wtgvb_settings['adsnippets'][0]['snippet'] = '<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* VideoBlogger3 */
google_ad_slot = "7739188671";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
// ads - 200 x 200 example
$wtgvb_settings['adsnippets'][1]['time'] = time();
$wtgvb_settings['adsnippets'][1]['source'] = 'google';
$wtgvb_settings['adsnippets'][1]['snippet'] = '<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* VideoBlogger2 */
google_ad_slot = "6262455473";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
// ads - 125 x 125 example
$wtgvb_settings['adsnippets'][2]['time'] = time();
$wtgvb_settings['adsnippets'][2]['source'] = 'google';
$wtgvb_settings['adsnippets'][2]['snippet'] = '<script type="text/javascript"><!--
google_ad_client = "ca-pub-4923567693678329";
/* VideoBlogger1 */
google_ad_slot = "4785722279";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';

###############################################################
#                         LOG SEARCH                          #
###############################################################
$wtgvb_settings['log']['logscreen']['displayedcolumns']['outcome'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['timestamp'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['line'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['function'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['page'] = true; 
$wtgvb_settings['log']['logscreen']['displayedcolumns']['panelname'] = true;   
$wtgvb_settings['log']['logscreen']['displayedcolumns']['userid'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['type'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['category'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['action'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['priority'] = true;
$wtgvb_settings['log']['logscreen']['displayedcolumns']['comment'] = true; 
?>