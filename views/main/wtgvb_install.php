<?php
/** 
 * Installation tab view for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */
?>

<div class="wtgvb_unlimitedcolumns_container">

    <!-- Box Start -->
    <div class="wtgvb_unlimitedcolumns_left">
        <!-- Panel Start -->
        <h4><?php _e('Partial Un-Install','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Remove individual option records, tables and files...','video-blogger');?></p>
                
                <form method="post" name="partialuninstall" action="<?php VideoBlogger_ui::form_action(); ?>">
                    
                    <?php VideoBlogger_WP::hidden_form_values('partialuninstall',__('Partial Uninstallation','video-blogger'));?>
                    
                    <h4>Core Plugin Tables</h4>
                    <?php VideoBlogger_WP::list_plugintables();?>
                           
                    <h4>Folders</h4>
                    <?php VideoBlogger_WP::list_folders();?>
                                                            
                    <h4>Option Records</h4>
                    <?php VideoBlogger_WP::list_optionrecordtrace(true,'Tiny'); ?>                    
                    
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
        <h4><?php _e('Re-Install Database Tables','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Use with care, will delete all existing tables for this plugin...','video-blogger');?></p>
                
                <form method="post" name="reinstalldatabasetables" action="<?php VideoBlogger_ui::form_action(); ?>">
                    
                    <?php VideoBlogger_WP::hidden_form_values('reinstalldatabasetables',__('Re-Install Database Tables','video-blogger'));?>
                    
                    <?php 
                    global $wtgvb_tables_array;
                    if(is_array($wtgvb_tables_array)){
                        
                        echo '<h2>'. __('Tables Already Installed','video-blogger') .'</h2>';  
                                
                        VideoBlogger_WP::tablestart();
                        
                        echo '
                        <thead>
                            <tr>
                                <th>'. __('Table Names','video-blogger') .'</th>
                                <th>'. __('Rows','video-blogger') .'</th>
                            </tr>
                        </thead>';

                        echo '
                        <tfoot>
                            <tr>
                                <th>'. __('Table Names','video-blogger') .'</th>
                                <th>'. __('Rows','video-blogger') .'</th>
                            </tr>
                        </tfoot>';
                        
                        echo '<tbody>';
                               
                        foreach($wtgvb_tables_array['tables'] as $key => $table){
                            if(VideoBlogger_WPDB::does_table_exist($table['name'])){         
                                echo '<tr><td>'.$table['name'].'</td><td>'.VideoBlogger_WPDB::countrecords($table['name']).'</td></tr>';
                            }                                                             
                        }
                                               
                        echo '</tbody></table>';
                        
                        echo '<br /><br />';
                        
                        echo '<h2>'. __('Tables Not Installed','video-blogger') .'</h2>';

                        VideoBlogger_WP::tablestart();
                        
                        echo '
                        <thead>
                            <tr>
                                <th>'. __('Table Names','video-blogger') .'</th>
                                <th>'. __('Required','video-blogger') .'</th>
                            </tr>
                        </thead>';
                  
                        echo '
                        <tfoot>
                            <tr>
                                <th>'. __('Table Names','video-blogger') .'</th>
                                <th>'. __('Required','video-blogger') .'</th>
                            </tr>
                        </tfoot>';
                          
                        foreach($wtgvb_tables_array['tables'] as $key => $table){
                            if(!VideoBlogger_WPDB::does_table_exist($table['name'])){         
                                echo '<tr><td>'.$table['name'].'</td><td>'.$table['required'].'</td></tr>';
                            }                                                             
                        }
                                               
                        echo '</tbody></table>';               
                    }?>  
                    
                    <input class="button" type="submit" value="Submit" />   
                </form>
                                                                                              
            </div>
        </div>
        <!-- Panel End -->
        
        <!-- Panel Start -->
        <h4><?php _e('Folders Status','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Status of folders on your server created by Video Blogger but outside of the plugin folder...','video-blogger');?></p>
                
                <?php VideoBlogger_WP::contentfolder_display_status(); ?>
                                                                                              
            </div>
        </div> 
        <!-- Panel End -->
                 
    </div>
    <!-- Box End -->
    
    <!-- Box Start -->
    <div class="wtgvb_unlimitedcolumns_left">
        <!-- Panel Start -->
        <h4><?php _e('Install Test Data','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Use with care i.e. test blog or a live blog still being developed. Click to begin
                the installation of data for all sections of Video Blogger active or not. This will also install test data
                for integrated plugins and themes.','video-blogger');?></p>
                
                <form method="post" name="partialuninstall" action="<?php VideoBlogger_ui::form_action(); ?>">
                    
                    <?php VideoBlogger_WP::hidden_form_values('installtestdata', __('Install Test Data','video-blogger') );?>                  
                    
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
        <h4><?php _e('Plugin Configuration','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Key variables that tell us the overall status of the plugin...','video-blogger');?></p>
                
                <h2>Package Values</h2>
                <ul>
                    <li><strong>Plugin Version:</strong> <?php echo $wtgvb_currentversion;?></li>               
                    <li><strong>WTG_VB_NAME:</strong> <?php echo WTG_VB_NAME;?></li>
                    <li><strong>WTG_VB_PHPVERSIONMINIMUM:</strong> <?php echo WTG_VB_PHPVERSIONMINIMUM;?></li>
                    <li><strong>WTG_VB_EXTENSIONS:</strong> <?php echo WTG_VB_EXTENSIONS;?></li>
                </ul> 
                
                <?php global $wtgvb_debug_mode,$wtgvb_is_dev,$wtgvb_isbeingactivated,$wtgvb_is_event,$wtgvb_installation_required;?>
                    
                <h2>Variables</h2>   
                <ul>    
                    <li><strong>Debug Mode:</strong> <?php echo $wtgvb_debug_mode;?></li>                                                             
                    <li><strong>Developer Mode:</strong> <?php echo $wtgvb_is_dev;?></li>                                                                                                                                                                                      
                    <li><strong>Being Activated:</strong> <?php echo $wtgvb_isbeingactivated;?></li>
                    <li><strong>Is Event:</strong> <?php echo $wtgvb_is_event;?></li>
                    <li><strong>Installation Drive:</strong> <?php echo $wtgvb_installation_required;?></li>
                    <li><strong>Is Installed:</strong> <?php echo $wtgvb_is_installed;?></li>                                                      
                </ul>    
                  
                <?php global $wtgvb_extension_loaded;?>
                
                <h2>Users Configuration</h2>
                <ul>
                    <li><strong>Current Project:</strong> <?php echo $wtgvb_currentproject;?></li>
                    <li><strong>Extension Loaded:</strong> <?php echo $wtgvb_extension_loaded;?></li>
                    <li><strong>Current Job Code:</strong> <?php echo $wtgvb_currentjob_code;?></li>
                    <li><strong>Current Project Code:</strong> <?php echo $wtgvb_currentproject_code;?></li>
                </ul>  
                
                <h2>File Paths</h2>
                <ul> 
                    <li><strong>WTG_VB_PATH</strong> <?php echo WTG_VB_PATH;?></li>
                    <li><strong>WTG_VB_FOLDERNAME</strong> <?php echo WTG_VB_FOLDERNAME;?></li>                   
                    <li><strong>WTG_VB_CONTENTFOLDER_DIR</strong> <?php echo WTG_VB_CONTENTFOLDER_DIR;?></li>
                <ul> 
                                                                                              
            </div>
        </div>
        <!-- Panel End --> 
    </div>
    <!-- Box End -->
        
    <div class="wtgvb_unlimitedcolumns_left">
        <h4>Environment Settings</h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Server variables that may be important for this plugin to operate...','video-blogger');?></p>
                
                <ul>
                    <li><strong>PHP Version:</strong> <?php echo phpversion();?></li>
                    <li><strong>MySQL Version:</strong> <?php echo VideoBlogger_PHP::mysqlversion();?></li>                          
                    <li><strong>HTTP_HOST:</strong> <?php echo $_SERVER['HTTP_HOST'];?></li>
                    <li><strong>SERVER_NAME:</strong> <?php echo $_SERVER['SERVER_NAME'];?></li>           
                </ul>
                         
                <hr>
                
                <h2>Common Functions (returned value)</h2>
                <ul>
                    <li><strong>time():</strong> <?php echo time();?></li>
                    <li><strong>date('Y-m-d H:i:s'):</strong> <?php echo date('Y-m-d H:i:s');?></li>
                    <li><strong>date('e'):</strong> <?php echo date('e');?> (timezone identifier)</li>
                    <li><strong>date('G'):</strong> <?php echo date('G');?> (24-hour format)</li>
                    <li><strong>get_admin_url():</strong> <?php echo get_admin_url();?></li>                 
                </ul>                
                                                                                              
            </div>
        </div> 
    </div>
     
    <div class="wtgvb_unlimitedcolumns_left">
    
        <h4>Wordpress Configuration</h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Wordpress settings which effect how this plugin operates...','video-blogger');?></p>
                
                <ul>
                    <li><strong>Wordpress Database Charset:</strong> <?php echo DB_CHARSET; ?></li>
                    <li><strong>Wordpress Blog Charset:</strong> <?php echo get_option('blog_charset'); ?></li>
                    <li><strong>WP_MEMORY_LIMIT:</strong> <?php echo WP_MEMORY_LIMIT;?></li>            
                    <li><strong>WP_POST_REVISIONS:</strong> <?php echo WP_POST_REVISIONS;?></li>                                    
                </ul>                 
                                                                                              
            </div>
        </div> 
    </div>
                
</div>