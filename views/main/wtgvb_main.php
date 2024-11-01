<?php
/** 
 * Main view file for main tabs in Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */

$wtgvb_page_name = 'main';
 
global $wtgvb_tab_number,$wtgvb_settings,$wtgvb_currentversion,$wtgvb_mpt_arr,$wtgvb_schedule_array,$wtgvb_is_beta,$wtgvb_is_installed,$wtgvb_extension_loaded,$wtgvb_is_free;
  
$pageid = 'main';// used to access variable.php configuration
$pagefolder = 'pagemain';             
        
$installing_software_name = WTG_VB_NAME;
$installing_software_name_plus = '';
$installing_message = __('Thank you for choosing Video Blogger, we look forward to working with you and reading your feedback.','video-blogger');

// set the installation software name, Video Blogger or extension name
$installed_version = VideoBlogger_WP::get_installed_version();
            
// this switch is set to false when we detect first time install or update is required
$display_main_screens = true;
     
// main page header
VideoBlogger_WP::pageheader($wtgvb_mpt_arr['menu'][$pageid]['title'],0);
                    
########################################################
#     REQUEST USER TO INITIATE FIRST TIME INSTALL      #
########################################################
if(!$wtgvb_is_installed && !isset($_POST['wtgvb_plugin_install_now']))
{
    // hide the main screens until update complete
    $display_main_screens = false;
    
    VideoBlogger_Notice::n_incontent_depreciated($installing_message,'info','Large',__('Video Blogger Installation','video-blogger'));?>

    <form class="wtgvb_form" method="post" name="wtgvb_plugin_install" action="">
        <?php wp_nonce_field('installform');?> 
        <input type="hidden" name="wtgvb_post_requested" value="true">
        <input type="hidden" name="wtgvb_plugin_install_now" value="true">
        <input type="hidden" name="wtgvb_hidden_pagename" value="main">
        <input type="hidden" name="wtgvb_form_name" value="installform">
        <input type="hidden" name="wtgvb_form_title" value="Welcome To Video Blogger">
        <input type="hidden" name="wtgvb_hidden_tabnumber" value="0">
        <button id="wtgvb_install_plugin_button"><?php _e('Install Video Blogger','video-blogger');?></button>
    </form>
    
<?php  
}elseif($wtgvb_currentversion > $installed_version){      
   
    ########################################################
    #        REQUEST USER TO INITIATE PLUGIN UPDATE        #
    ######################################################## 
        
    // hide the main screens until update complete
    $display_main_screens = false;
    
    VideoBlogger_Notice::n_incontent_depreciated(__('The plugins files have been replaced with a new version. You now need to complete the update by clicking below.','video-blogger'),'warning','Large',"Video Blogger Update $wtgvb_currentversion");?>

    <?php 
    $videoblogger_update = new VideoBlogger_UpdatePlugin();
    $videoblogger_update->changelist('next');
    ?>
    
    <form class="wtgvb_form" method="post" name="updatevideoblogger" action="">
        <?php wp_nonce_field('updatevideoblogger');?> 
        <input type="hidden" id="wtgvb_post_requested" name="wtgvb_post_requested" value="true">
        <input type="hidden" name="wtgvb_hidden_pagename" value="<?php echo $pageid;?>">
        <input type="hidden" name="wtgvb_form_name" value="updatevideoblogger">
        <input type="hidden" name="wtgvb_form_title" value="Update Video Blogger">
        <input type="hidden" name="wtgvb_hidden_tabnumber" value="0">  
        
        <!-- existing of this value is used for security but we also pass the next version (cleaned) to confirm in submission what version should be updated to -->
        <input type="hidden" id="wtgvb_plugin_update_now" name="wtgvb_plugin_update_now" value="<?php echo VideoBlogger_UpdatePlugin::nextversion_clean();?>">
             
        <input class="button" type="submit" value="<?php _e('Update Video Blogger Installation','video-blogger');?>" />
    </form>
    
<?php   
}

########################################################
#                                                      #
#               DISPLAY MAIN SCREENS                   #
#                                                      #
########################################################
// the plugin update process is complete above and that decides if we should show the main screens
if($display_main_screens){
          
    // set tab number variable, a common use is in form hidden values
    $wtgvb_tab_number = VideoBlogger_WP::tabnumber();
                    
    // create tab menu for the giving page
    VideoBlogger_WP::createmenu($pageid);
              
    // create screen content 
    include($wtgvb_mpt_arr['menu'][$pageid]['tabs'][$wtgvb_tab_number]['path']);?>

<?php 
}?>


            </div><!-- end of post boxes -->
        </div><!-- end of post boxes -->
    </div><!-- end of post boxes -->
</div><!-- end of wrap - started in header -->

<script type="text/javascript">
    // <![CDATA[
    jQuery('.postbox div.handlediv').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
    jQuery('.postbox h3').click( function() { jQuery(jQuery(this).parent().get(0)).toggleClass('closed'); } );
    jQuery('.postbox.close-me').each(function(){
    jQuery(this).addClass("closed");
    });
    //-->
</script>