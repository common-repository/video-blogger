<?php         
/*
Plugin Name: Video Blogger
Version: 1.0.3
Plugin URI: http://www.webtechglobal.co.uk
Description: Display videos individually or in playlists. Works with Youtube, Blip TV, Vimeo and other streaming websites.
Author: WebTechGlobal
Author URI: http://www.webtechglobal.co.uk
Last Updated: 4th November 2013
Text Domain: video-blogger
Domain Path: /languages

Video Blogger License (free edition license does not apply to premium directory)

GPL v3 

This program is free software downloaded from Wordpress.org: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. This means
it can be provided for the sole purpose of being developed further
and we do not promise it is ready for any one persons specific needs.
See the GNU General Public License for more details.

See <http://www.gnu.org/licenses/>.

This license does not apply to the paid edition which comes with premium
services not just software. License and agreement is seperate.
*/           

if ( ! defined( 'ABSPATH' ) ) {exit;}
            
// package variables
$wtgvb_currentversion = '1.0.3'; 
$wtgvb_debug_mode = false;// www.csvtopost.com will override this but only if demo mode not active
$wtgvb_disable_extensions = false;// boolean - can quickly disable extensions using this
$wtgvb_is_beta = true;// expect beta mode until 2014 - hides partialy completed features or information not true until features complete
$wtgvb_is_dev = false;// true will display more information i.e. array dumps using var_dump() 
    
// go into dev mode if on test installation                  
if(strstr(ABSPATH,'testing/wordpress/videoblogger'))
{
    $wtgvb_debug_mode = true; 
    $wtgvb_is_beta = false;// we do not want to hide partially completed features or information for those features
    $wtgvb_is_dev = true;
    $wtgvb_is_free_override = true;// toggle free/paid modes even when paid folder present      
}                

// error output should never be on during AJAX requests               
if(defined('DOING_AJAX') && DOING_AJAX){
    $wtgvb_debug_mode = false;    
}                   
         
// other variables required on installation or loading
$wtgvb_isbeingactivated = false;// changed to true during activation, used to avoid certain processing especially the schedule and automation system
$wtgvb_is_event = false;// when true, an event is running or has ran, used to avoid over processing         
$wtgvb_installation_required = true;                                                     
$wtgvb_is_installed = false;        
$wtgvb_notice_array = array();// set notice array for storing new notices in (not persistent notices)
$wtgvb_extension_loaded = false;
$wtgvb_menu_source = 'file';// file (inline array), data (stored in option record for custom settings to be applied)
$wtgvb_is_free = true;                           
if(file_exists(plugin_dir_path(__FILE__) . 'paidedition') && isset($wtgvb_is_free_override) && $wtgvb_is_free_override == false){$wtgvb_is_free = false;}

// define constants               
if(!defined("WTG_VB_FOLDERNAME")){define("WTG_VB_FOLDERNAME",str_replace('/video-blogger.php','',plugin_basename(__FILE__)));}               
if(!defined("WTG_VB_NAME")){define("WTG_VB_NAME",'Video Blogger');} 
if(!defined("WTG_VB_PATH")){define("WTG_VB_PATH", plugin_dir_path(__FILE__) );}//C:\AppServ\www\wordpress-testing\wtgplugintemplate\wp-content\plugins\wtgplugintemplate/  
if(!defined("WTG_VB_PHPVERSIONMINIMUM")){define("WTG_VB_PHPVERSIONMINIMUM",'5.3.0');}// The minimum php version that will allow the plugin to work                                
if(!defined("WTG_VB_CONTENTFOLDER_DIR")){define("WTG_VB_CONTENTFOLDER_DIR",WP_CONTENT_DIR.'/videobloggercontent/');}// directory path to storage folder inside the wp_content folder  
                       
// load config file - the functions that are called before all othersS
require_once(WTG_VB_PATH.'/wtgvb_config.php');            
               
// set extension switch constant
if(!defined("WTG_VB_EXTENSIONS")){if(!$wtgvb_disable_extensions){if(get_option('wtgvb_extensions')){define("WTG_VB_EXTENSIONS",get_option('wtgvb_extensions'));}else{define("WTG_VB_EXTENSIONS",'disable');}}}
 
// initiate plugin (this is a new class approach being slowly introduced to a function only plugin, expect gradual reduction of this file per version)
if( !class_exists( 'VideoBlogger' ) ) 
{                    
    // include core functions and arrays (many which apply to all WTG plugins)               
    require_once(WTG_VB_PATH.'arrays/wtgvb_options_array.php');
    require_once(WTG_VB_PATH.'arrays/wtgvb_sections_array.php');
    require_once(WTG_VB_PATH.'arrays/wtgvb_tableschema_array.php'); 
    
    // include post types (each one has its own file) 
    foreach (glob(WTG_VB_PATH . 'functions/posttypes/*.php') as $filename){include $filename;}
        
    // include section functions
    global $wtgvb_sections_array;
    foreach ($wtgvb_sections_array as $section => $section_array){if($section_array['active'] === true){foreach(glob(WTG_VB_PATH.'functions/sections/'.$section."/*.php") as $filename){include $filename;}}} 
        
    require_once(WTG_VB_PATH.'functions/wtgvb_main_classes.php');
    
    // extending classes
    foreach (glob(WTG_VB_PATH . 'functions/class/*.php') as $filename){include $filename;}

    // shortcodes
    foreach (glob(WTG_VB_PATH . 'functions/shortcodes/*.php') as $filename){include $filename;}
    
    $VideoBlogger = new VideoBlogger();  
}
$VideoBlogger->wp_init();                                                                             
$wtgvb_settings = $VideoBlogger->adminsettings();
$VideoBlogger->debugmode();    
$wtgvb_is_installed = $VideoBlogger->is_installed();// boolean - if false either plugin has never been installed or installation has been tampered with 
$wtgvb_schedule_array = VideoBlogger_WP::get_option_schedule_array();

// admin only values (these are arrays that contain data that should never be displayed on public side, load them admin side only reduces a fault causing display of the information)
if(is_admin())
{
    $wtgvb_persistent_array = VideoBlogger_WP::persistentnotifications_array();// holds interface notices/messages, some temporary, some are persistent 
    $wtgvb_mpt_arr = VideoBlogger_WP::tabmenu();
}   
                                                                        
register_activation_hook( __FILE__ ,array($VideoBlogger,'activation'));

// load admin that comes last and applys to Video Blogger plugin pages only
if(is_admin() && isset($_GET['page']) && VideoBlogger_WP::is_plugin_page($_GET['page'])){

    VideoBlogger_WP::css_core('admin');// script and css admin side   
    
}elseif(!is_admin()){// default to public side script and css      

    VideoBlogger_WP::css_core('public');    
}

add_shortcode( 'v', 'wtgvb_shortcode_video_basic' );  
add_shortcode( 'p', 'wtgvb_shortcode_playlist_basic' ); 

function wtgvb_textdomain() {
    load_plugin_textdomain( 'video-blogger', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'wtgvb_textdomain');                                                                                                                                    
?>