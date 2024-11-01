<?php
/** 
* Plugin update functions. This class needs to be adapted for every plugin.
* 
* Every version gets its own function. Within that function is a standard of procedures
* applicable to upgrading from one version to another, arrays of information for users
* and options to use the functions in different ways.
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

class VideoBlogger_UpdatePlugin{
    public function patch_103($action_requested = 'update'){
        $update_array = array();
        $update_array['info']['modificationrequired'] = true;
        $update_array['info']['intro'] = __('A short note from the author about the update and what it delivers.','video-blogger');
        $update_array['info']['urgency'] = 'low';
        #############################
        #          CHANGES          #
        #############################
        // new features
        $update_array['changes']['added'][0] = array('title' => 'None','about' => __('No new features.','video-blogger'));
        // bug fixes        
        $update_array['changes']['fixes'][0] = array('title' => 'None','about' => __('No fixes.','video-blogger'));
        // general changes        
        $update_array['changes']['general'][0] = array('title' => 'Core','about' => __('Entire new core in use which uses PHP classes and my new plugin interface.','video-blogger'));
        $update_array['changes']['general'][1] = array('title' => 'Translation','about' => __('Plugin has been prepared for translation.','video-blogger'));
        // configuration changes        
        $update_array['changes']['config'][0] = array('title' => 'None','about' => __('No installation changes required.','video-blogger'));
        ############################
        #     REQUEST RESULT       #
        ############################   
        $update_array['failed'] = false;
        $update_array['failedreason'] = 'No Applicable';
                    
        if($action_requested == 'update')
        {             
            /* no installation changes required for version 0.0.3 */
            return $update_array;
        }   
        elseif($action_requested == 'info')
        {
            return $update_array['info'];    
        }
        elseif($action_requested == 'changes')
        {
            return $update_array['changes'];    
        }
    }    
    public function patch_2()
    {
        $update_array = array();
        $update_array['info']['modificationrequired'] = false; 
        $update_array['info']['intro'] = __('No changes are required for this version.','video-blogger');
        $update_array['info']['urgency'] = 'low';
        #############################
        #          CHANGES          #
        #############################
        $update_array['changes']['added'][0] = array('title' => 'What Was Added','about' => __('Explain what new feature has been added.','video-blogger'));
        $update_array['changes']['fixes'][0] = array('title' => 'What Was Fixed','about' => __('Explain what has been fixed.','video-blogger'));
        $update_array['changes']['general'][0] = array('title' => 'What Was Changed','about' => __('Explain what was changed.','video-blogger'));
        $update_array['changes']['config'][0] = array('title' => 'What Needs Configured','about' => __('Explain what will be re-configured or what the user may need to configure.','video-blogger'));
        ############################
        #     REQUEST RESULT       #
        ############################   
        $update_array['failed'] = false;
        $update_array['failedreason'] = __('Not Applicable','video-blogger');
                        
        if($action_requested == 'update')
        {
            /* no installation changes required for version 0.0.3 */
            return $update_array;
        }   
        elseif($action_requested == 'info')
        {
            return $update_array['info'];    
        }
        elseif($action_requested == 'changes')
        {
            return $update_array['changes'];    
        }  
    }    
    /**
    * Example only
    * @param mixed $action_requested update|info|changes
    */
    public function patch_1($action_requested = 'update')
    {
        $update_array = array();
        
        // if not required no update process will occur in the eyes of the user 
        $update_array['info']['modificationrequired'] = true;// boolean 
        // authors own comment with recommendation, link to tutorial or video
        $update_array['info']['intro'] = __('A short note from the author about the update and what it delivers.','video-blogger');
        // urgency should be used to indicate if users really need to update or not
        $update_array['info']['urgency'] = 'low';// low (no bug fixes at all),normal (usual mix of minor fixes and new features),high (high would be for bug fixes especially security related)
        
        #############################
        #          CHANGES          #
        #############################
        $update_array['changes']['added'][0] = array('title' => 'What Was Added','about' => __('Explain what new feature has been added.','video-blogger'));
        $update_array['changes']['fixes'][0] = array('title' => 'What Was Fixed','about' => __('Explain what has been fixed.','video-blogger'));
        $update_array['changes']['general'][0] = array('title' => 'What Was Changed','about' => __('Explain what was changed.','video-blogger'));
        $update_array['changes']['config'][0] = array('title' => 'What Needs Configured','about' => __('Explain what will be re-configured or what the user may need to configure.','video-blogger'));
        ############################
        #     REQUEST RESULT       #
        ############################   
        $update_array['failed'] = false;
        $update_array['failedreason'] = __('Not Applicable','video-blogger');
                        
        if($action_requested == 'update')
        {
            /* no installation changes required for version 0.0.3 */
            return $update_array;
        }   
        elseif($action_requested == 'info')
        {
            return $update_array['info'];    
        }
        elseif($action_requested == 'changes')
        {
            return $update_array['changes'];    
        }
    }
    public function nextversion_clean()
    {
        global $wtgvb_currentversion;
        
        $installed_version = VideoBlogger_WP::get_installed_version();
        
        $installed_version_cleaned = str_replace('.','',$installed_version);
        
        return $installed_version_cleaned + 1;       
    }
    public function changelist($scope = 'next')
    {
        global $wtgvb_currentversion;
        
        // standard messages for change types
        $added = __('New features added to the plugin, be sure to configure them to suit your needs.','video-blogger');
        $fixes = __('Bug fixes, remember a fix may instantly change how the plugin operates on your site.','video-blogger');
        $general = __('General improvements to how features operate, the interface and procedures.','video-blogger');
        $config = __('These changes involve changing the plugins installation, database updates involved.','video-blogger');
                                                                            
        $next_version = $this->nextversion_clean();

        eval('$changes_array = $this->patch_' . $next_version .'("changes");');
        
        foreach($changes_array as $key => $group)
        {
            $test = '<ol>';
            foreach($group as $item)
            {
                $test .= '<li><strong>'.$item['title'].': </strong>'.$item['about'].'</li>';
            }
            $test .= '</ol>';
            
            VideoBlogger_Notice::basic_html('info','Small',ucfirst($key),$$key . $test);                
        }    
    } 
}
?>
