<?php
/** 
* Wordpress plugin extension procedures
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
 
class VideoBlogger_Extensions{ 
    /**
    * Checks the extensions version with the version stored in Wordpress options table
    */
    public function updaterequired($name,$version)
    {
        $current_version = get_option($name . '_version');
        if(!$current_version || $current_version < $version){
            return true;
        }else{
            return false;
        }
    }
    public function activation_status($name)
    {
        if(!file_exists(WP_CONTENT_DIR . '/videobloggerextensions/'.$name)){
            return false;    
        }
              
        $ext_opt = get_option('ext_activated_'.$name);
        if(!$ext_opt || $ext_opt == 'no'){
            return 2;# extension not activated
        }elseif($ext_opt == 'yes'){
            return 3;# extension activated
        }
    }
    /**
    * Determines the installation status of an extension
    * 
    * @param mixed $name the slug name of the extension and should always be the name of the extension folder
    * @return false if no files else returnsa code number, 2|3 
    */
    function install_status($name)
    {
        // check if extensions files exist
        if(!file_exists(WP_CONTENT_DIR . '/videobloggerextensions/'.$name)){
            return false;    
        }
        
        // get the extensions option records (every extension has its own option record)
        $ext_opt = get_option('ext_installed_'.$name);
        if(!$ext_opt || $ext_opt == 'no'){
            return 2;# extension not installed
        }elseif($ext_opt == 'yes'){
            return 3;# extension installed
        }
    }
    public function readable_extension_status($name){
        $installation_status = VideoBlogger_Extensions::install_status($name);# false (no files), 2 (not installed), 3 (is installed)
        $activation_status = VideoBlogger_Extensions::activation_status($name);# false (no files), 2 (not activated), 3 (activated)
                              
        if(!$installation_status){# no files
            return __('Files Not Uploaded','video-blogger');
        }
        
        if($installation_status == 2){# installed (tables, option records etc)
            return __('Files Uploaded but Not Installed','video-blogger');
        }
        
        if($activation_status == 2){
            return __('Installed but not Activated','video-blogger');
        }
        
        if($activation_status == 3){
            return __('Active','video-blogger');
        }
        
        return __('error please report','video-blogger');
    }           
}// end class VideoBlogger_Extensions

?>
