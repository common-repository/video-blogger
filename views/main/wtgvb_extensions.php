<?php
/** 
 * Extensions tab view for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */                        
include_once(WTG_VB_PATH . 'arrays/wtgvb_extensions_array.php');?> 

<div class="wtgvb_unlimitedcolumns_container">

    <!-- Box Start -->
    <div class="wtgvb_unlimitedcolumns_left">
    
        <!-- Panel Start -->
        <h4><?php _e('Wordpress Configuration','video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Wordpress settings which effect how this plugin operates...','video-blogger'); ?></p>
                
                <form method="post" name="extensionsettings" action="<?php VideoBlogger_ui::form_action(); ?>">
    
                    <?php VideoBlogger_WP::hidden_form_values('extensionsettings',__('Wordpress Configuration Settings','video-blogger') );?>
    
                    <table class="form-table">   

                        <!-- Option Start -->
                        <tr valign="top">
                            <th scope="row"><?php _e('Extension Engine','video-blogger'); ?></th>
                            <td>
                            
                                <script>
                                    $(function() {
                                        $( "#wtgvb_div_<?php echo $form_array['panel_name'];?>_extensionstatus" ).buttonset();
                                    });
                                </script>
                 
                                <?php 
                                if(VideoBlogger_WP::option('wtgvb_extensions','get') == 'enable'){
                                    $radio1_checked_enabled = 'checked';
                                    $radio2_checked_disabled = '';    
                                }elseif(VideoBlogger_WP::option('wtgvb_extensions','get') == 'disable'){
                                    $radio1_checked_enabled = '';
                                    $radio2_checked_disabled = 'checked';    
                                }?>
                                <div id="wtgvb_div_<?php echo $form_array['panel_name'];?>_extensionstatus">
                                    <input type="radio" id="wtgvb_<?php echo $form_array['panel_name'];?>_enable" name="wtgvb_radiogroup_extensionstatus" value="enable" <?php echo $radio1_checked_enabled;?> />
                                    <label for="wtgvb_<?php echo $form_array['panel_name'];?>_enable"> Enable</label>
                                    <br />
                                    <input type="radio" id="wtgvb_<?php echo $form_array['panel_name'];?>_disable" name="wtgvb_radiogroup_extensionstatus" value="disable" <?php echo $radio2_checked_disabled;?> />
                                    <label for="wtgvb_<?php echo $form_array['panel_name'];?>_disable"> Disable</label>
                                </div>    

                            </td>
                        </tr>
                        <!-- Option End -->

                    </table>                
                    <input class="button" type="submit" value="Submit" />
                </form>
                                                                       
            </div>
        </div>
        <!-- Panel End -->
         
    </div>
    <!-- Box End -->

    <!-- Box Start -->
    <div class="wtgvb_unlimitedcolumns_left">
    
        <!-- Panel Start -->
        <h4><?php _e('WebTechGlobal Extension List','video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('A list of extensions created by WebTechGlobal...','video-blogger'); ?></p>
  
                    <?php VideoBlogger_WP::tablestart();?>   
                        
                            <thead>
                                <tr>
                                    <th width="50"><?php _e('Name','video-blogger'); ?></th>
                                    <th><?php _e('Status','video-blogger'); ?></th>
                                    <th><?php _e('Latest Version','video-blogger'); ?></th>
                                    <th><?php _e('Installed Version','video-blogger'); ?></th>
                                    <th><?php _e('Description','video-blogger'); ?></th> 
                                    <th><?php _e('Install/Uninstall/Update','video-blogger'); ?></th>
                                    <th><?php _e('Activate/Disable','video-blogger'); ?></th>
                                </tr>
                            </thead>
                            
                            <tfoot>
                                <tr>
                                    <th><?php _e('Name','video-blogger'); ?></th>
                                    <th><?php _e('Status','video-blogger'); ?></th>
                                    <th><?php _e('File Version','video-blogger'); ?></th>
                                    <th><?php _e('Installed Version','video-blogger'); ?></th>
                                    <th><?php _e('Description','video-blogger'); ?></th>
                                    <th><?php _e('Install/Uninstall/Update','video-blogger'); ?></th>
                                    <th><?php _e('Activate/Disable','video-blogger'); ?></th> 
                                </tr>
                            </tfoot>
                        
                            <tbody>

                                <?php foreach($wtgvb_extensions_array['extensions'] as $name => $ext){
                 
                                    $installstat = VideoBlogger_Extensions::install_status($name);
                                    $activatestat = VideoBlogger_Extensions::activation_status($name);
                                    $readstat = VideoBlogger_Extensions::readable_extension_status($name);
                                                                                                      
                                    // is updated required?  
                                    $extension_version = '';   
                                    if($installstat != false){# false equals no files                  
                                        // including the extension.php file gives us more information to play with
                                        global $wtgvb_extension_df1_version;
                  
                                        if(!$wtgvb_extension_df1_version){
                                            require_once(WP_CONTENT_DIR . '/videobloggerextensions/'.$name.'/extension.php');
                                        }
                                        
                                        $extension_version = ${"wtgvb_extension_" . $name ."_version"};
                                        $update_required = VideoBlogger_Extensions::updaterequired($name,$extension_version);
                                    }
                                    ?>
                    
                                    <tr valign="top">
                               
                                        <td><?php echo $ext['title']; ?></td>
                                        <th scope="row"><?php echo $readstat; ?></th>
                                        <td><?php echo $extension_version; ?></td>
                                        <td><?php echo get_option($name.'_version'); ?></td>
                                        <th><?php echo $ext['description']; ?></th>                       
                            
                                        <?php if($installstat == false){# no files ?>
                                           
                                                <th></th>
                                                <th></th>
                                                
                                       <?php }elseif($update_required && $installstat != 2 ){?>                        
                    
                                                <th> <a class="button" href="<?php echo wp_nonce_url(admin_url().'admin.php?page=videoblogger&eciprocess=true&action=extensionupdate&extension='.$name, 'extensionupdate' );?>"><?php _e('Update','video-blogger'); ?></a> </th>
                                                <th></th>   
                                                                             
                                       <?php }elseif($installstat == 2 ){?>                        
                    
                                                <th> <a class="button" href="<?php echo wp_nonce_url(admin_url().'admin.php?page=videoblogger&eciprocess=true&action=extensioninstall&extension='.$name, 'extensioninstall' );?>"><?php _e('Install','video-blogger'); ?></a> </th>
                                                <th></th>
                                                
                                       <?php }elseif($installstat == 3){?>
                                            
                                                <th> <a class="button" href="<?php echo wp_nonce_url( admin_url().'admin.php?page=videoblogger&eciprocess=true&action=extensionuninstall&extension='.$name, 'extensionuninstall' ); ?>"><?php _e('Uninstall','video-blogger'); ?></a> </th>
                                                
                                                <?php if( $activatestat == 2 ){?>
                                                
                                                    <th> <a class="button" href="<?php echo wp_nonce_url( admin_url().'admin.php?page=videoblogger&eciprocess=true&action=extensionactivate&extension='.$name, 'extensionactivate' ); ?>"><?php _e('Activate','video-blogger'); ?></a> </th>
                                                    
                                                <?php }elseif( $activatestat == 3 ) {?>
                                                    
                                                    <th> <a class="button" href="<?php echo wp_nonce_url( admin_url().'admin.php?page=videoblogger&eciprocess=true&action=extensiondisable&extension='.$name, 'extensiondisable' ); ?>"><?php _e('Disable','video-blogger'); ?></a> </th>  
                                                    
                                                <?php }?>
                           
                                        <?php }?>

                                    </tr>

                                <?php }?>
                                    
                        </tbody>
                    </table>                  
                                                                       
            </div>
        </div>
        <!-- Panel End -->
         
    </div>
    <!-- Box End -->
                   
</div>

<?php     
if(isset($wtgvb_extensions_array)){ 
    foreach($wtgvb_extensions_array['extensions'] as $name => $ext){
        
        // if $installstat is not false (which means no files, then display the extensions panel)
        $installstat = VideoBlogger_Extensions::install_status($name);
        if($installstat != false){
            
            ++$panel_number;// increase panel counter so this panel has unique ID
            $form_array = VideoBlogger_WP::panel_array($pageid,$panel_number,$wtgvb_tab_number);
            $form_array['panel_name'] = 'extensionstatus'.$name;// slug to act as a name and part of the panel ID 
            $form_array['panel_title'] = __($ext['title']);// user seen panel header text  
            $form_array['panel_id'] = $form_array['panel_name'].$name;// creates a unique id, may change from version to version but within a version it should be unique
            // Form Settings - create the array that is passed to jQuery form functions
            $jsform_set_override = array();
            $jsform_set = VideoBlogger_WP::jqueryform_commonarrayvalues($pageid,$form_array['tabnumber'],$form_array['panel_number'],$form_array['panel_name'],$form_array['panel_title'],$jsform_set_override);     
            ?>
            <?php VideoBlogger_WP::panel_header( $form_array );?>
            
            <?php 
            $extension_version = '';   
            if($installstat != false){# false equals no files 
            
                $extension_version = ${"wtgvb_extension_" . $name ."_version"};

                echo '<h4>Files Version: ' . $extension_version . '</h4>';
                
                $installedversion = get_option($name.'_version');
                if(!$installedversion){$m = __('no version has been installed, installation must be complete','video-blogger');}else{$m = $installedversion;}
                echo '<h4>'. __('Installed Version:','video-blogger') . $m . '</h4>';
                
                if($installedversion == $extension_version){$m = 'No';}else{$m = 'Yes';}
                echo '<h4>'. __('Update Required:','video-blogger') . $m . '</h4>';

            } 
            
            // list all database tables and their columns
            global $wtgvb_df1_tables_array;
            if(is_array($wtgvb_df1_tables_array)){
 
            VideoBlogger_WP::tablestart();   
        
            echo '    
                <thead>
                    <tr>
                        <th>'. __('Table Name','video-blogger') .'</th>
                        <th>'. __('Rows','video-blogger') .'</th>
                        <th>'. __('Install/Delete','video-blogger') .'</th>
                        <th>'. __('Update Required','video-blogger') .'</th>
                    </tr>
                </thead>
                
                <tfoot>
                    <tr>
                        <th>'. __('Table Name','video-blogger') .'</th>
                        <th>'. __('Rows','video-blogger') .'</th>
                        <th>'. __('Install/Delete','video-blogger') .'</th>
                        <th>'. __('Update Required','video-blogger') .'</th>
                    </tr>
                </tfoot>
            
                <tbody>';

                foreach($wtgvb_df1_tables_array['tables'] as $key => $table){
                             
                    echo '
                    <tr>
                        <td>'.$table['name'].'</td>';
                        
                        // display number of rows in table
                        if(VideoBlogger_WPDB::does_table_exist($table['name'])){
                            echo '<td>'.VideoBlogger_WPDB::countrecords($table['name']).'</td>';
                        }else{
                            echo '<td></td>';
                        }
                        
                        // delete or install
                        if(VideoBlogger_WPDB::does_table_exist($table['name'])){
                            echo '<td><a class="button" href="'. wp_nonce_url( admin_url() . "admin.php?page=videoblogger&eciprocess=true&action=deletedbtable&tablename=".$table['name'], "updatedbtable" ) .'">'. __('Delete','video-blogger') .'</a></td>';
                        }else{
                            echo '<td><a class="button" href="'. wp_nonce_url( admin_url() . "admin.php?page=videoblogger&eciprocess=true&action=installdbtable&tablename=".$table['name'], "installdbtable" ) .'">'. __('Install','video-blogger') .'</a></td>';                                
                        }
           
                        if($table['name'] == 'wtgvb_ryanair_session' || $table['name'] == 'wtgvb_ryanair_flight'){
                            echo '<td><a class="button" href="'. wp_nonce_url( admin_url() . "admin.php?page=videoblogger&eciprocess=true&action=updatedbtable&tablename=".$table['name'], "updatedbtable" ) .'">'. __('Update','video-blogger') .'</a></td>';
                        }else{
                            echo '<td></td>';
                        }

                    echo '</tr>';                                                       
                }
                                       
                echo '</tbody></table>';        
            }           
            ?>
   
            <?php VideoBlogger_WP::panel_footer();
        }
    }
}
?>