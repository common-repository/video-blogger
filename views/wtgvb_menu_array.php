<?php
/** 
 * Tab navigation array for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */
 
/**
* Instructions
* 1. remember to set an ['userdefault'] for each page, this is to revert to if there are issues loading requested screen, the default for none admin should be suitable for anyone who is expected to visit the page
* 2. note, there are other values that are not included in the array by default and in most cases the value not being present is treated as boolean true
*/

global $wtgvb_is_dev;

$dc = 'update_core';// default capability required by page or tab
$freepath = WTG_VB_PATH . 'views/';
$wtgvb_mpt_arr = array();

function videoblogger_active_status($default_active_status = false) {
    global $wtgvb_is_dev;
    if($wtgvb_is_dev){return true;}// developers see all
    return $default_active_status;
}

// main page
$wtgvb_mpt_arr['menu']['main']['active'] = videoblogger_active_status(true);// boolean -is this page in use
$wtgvb_mpt_arr['menu']['main']['slug'] = 'videoblogger';// home page slug set in main file
$wtgvb_mpt_arr['menu']['main']['menu'] = 'Video Blogger';// main menu title
$wtgvb_mpt_arr['menu']['main']['name'] = "main";// name of page (slug) and unique
$wtgvb_mpt_arr['menu']['main']['title'] = 'Video Blogger';// page title seen once page is opened
$wtgvb_mpt_arr['menu']['main']['permissions']['capability'] = $dc;// our best guess on a suitable capability
$wtgvb_mpt_arr['menu']['main']['permissions']['customcapability'] = $dc;// users requested capability which is giving priority over default 
$sub = 0;  
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['active'] = videoblogger_active_status(true);
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['slug'] = 'about';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['label'] = 'About';  
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['display'] = true;// user can change to false to hide screens
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['path'] = $freepath.'main/wtgvb_about.php';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['capability'] = $dc;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['customcapability'] = $dc; 
++$sub; 
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['active'] = videoblogger_active_status(true);
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['slug'] = 'generalsettings';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['label'] = 'General Settings';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['display'] = true;// user can change to false to hide screens
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['path'] = $freepath.'main/wtgvb_generalsettings.php';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['capability'] = $dc;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['customcapability'] = $dc;
++$sub; 
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['active'] = videoblogger_active_status(true);
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['slug'] = 'adsettings';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['label'] = 'Ads';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['display'] = true;// user can change to false to hide screens
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['path'] = $freepath.'main/wtgvb_manageads.php';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['capability'] = $dc;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['customcapability'] = $dc;  
++$sub;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['active'] = videoblogger_active_status(true);
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['slug'] = 'install';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['label'] = 'Install';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['display'] = true;// user can change to false to hide screens
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['path'] = $freepath.'main/wtgvb_install.php';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['capability'] = $dc;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['customcapability'] = $dc;
++$sub;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['active'] = videoblogger_active_status(false);
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['slug'] = 'extensions';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['label'] = 'Extensions';# developer information
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['display'] = true;// user can change to false to hide screens
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['path'] = $freepath.'main/wtgvb_extensions.php';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['capability'] = $dc;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['customcapability'] = $dc;
++$sub;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['active'] = videoblogger_active_status(true);
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['slug'] = 'log';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['label'] = 'Log';# developer information
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['display'] = true;// user can change to false to hide screens
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['path'] = $freepath.'main/wtgvb_log.php';
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['capability'] = $dc;
$wtgvb_mpt_arr['menu']['main']['tabs'][$sub]['permissions']['customcapability'] = $dc;
?>