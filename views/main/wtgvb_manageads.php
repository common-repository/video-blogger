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
        <h4><?php _e('Ad Configuration','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Some options that apply to all ads...','video-blogger');?></p>
                
                <form method="post" name="adconfiguration" action="<?php VideoBlogger_ui::form_action(); ?>">
                    
                    <?php VideoBlogger_WP::hidden_form_values('adconfiguration',__('Ad Configuration','video-blogger'));?>
                    
                    <table class="form-table">    
                    
                        <!-- Option Start -->          
                        <tr valign="top">
                            <th scope="row"> <label for="wtgvb_adsenseoptions_status"><?php _e('Ad Status','video-blogger');?></label> </th>
                            <td>  
                                <?php 
                                if(!isset($wtgvb_settings['adoptions']['status'])){
                                    $wtgvb_settings['adoptions']['status'] = 'posts';
                                }
                                ?>
                                <select name="wtgvb_adsenseoptions_status" id="wtgvb_adsenseoptions_status" size="1">
                                    <option value="posts" <?php VideoBlogger_PHP::echoselected( $wtgvb_settings['adoptions']['status'] ,'posts' ); ?>><?php _e('Posts Only','video-blogger');?></option>
                                    <option value="postssidebar" <?php VideoBlogger_PHP::echoselected( $wtgvb_settings['adoptions']['status'] ,'postssidebar' ); ?>><?php _e('Posts and Sidebar','video-blogger');?></option>
                                    <option value="noads" <?php VideoBlogger_PHP::echoselected( $wtgvb_settings['adoptions']['status'] ,'noads' ); ?>><?php _e('No Ads','video-blogger');?></option>
                                </select> 
                                               
                            </td>
                        </tr>        
                        <!-- Option End -->              
                            
                        <!-- Option Start -->          
                        <tr valign="top">
                            <th scope="row"> <label for="wtgvb_adsenseoptions_maximum"><?php _e('Maximum Ads','video-blogger');?></label> </th>
                            <td>  
                                <?php 
                                if(!isset($wtgvb_settings['adoptions']['maximumads'])){
                                    $wtgvb_settings['adoptions']['maximumads'] = '3';
                                }
                                ?>
                                <select name="wtgvb_adsenseoptions_maximum" id="wtgvb_adsenseoptions_maximum" size="1">
                                    <option value="3" <?php VideoBlogger_PHP::echoselected( $wtgvb_settings['adoptions']['maximumads'] ,'3' ); ?>><?php _e('3','video-blogger');?></option>
                                    <option value="2" <?php VideoBlogger_PHP::echoselected( $wtgvb_settings['adoptions']['maximumads'] ,'2' ); ?>><?php _e('2','video-blogger');?></option>
                                    <option value="1" <?php VideoBlogger_PHP::echoselected( $wtgvb_settings['adoptions']['maximumads'] ,'1' ); ?>><?php _e('1','video-blogger');?></option>
                                </select> 
                                               
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
        <h4><?php _e('New Ad Snippet','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Submit the script/HTML required to display your ad...','video-blogger');?></p>
                
                <form method="post" name="submitadsnippet" action="<?php VideoBlogger_ui::form_action(); ?>">
                    
                    <?php VideoBlogger_WP::hidden_form_values('newadsnippet', __('New Ad Snippet','video-blogger') );?>
                    
                    <p><?php _e('Copy and paste ad snippets here then submit, they will become available for using in your widget when a post does not have a video to display.','video-blogger');?></p>
                    <textarea name="wtgvb_submitad_snippet" id="wtgvb_submitad_snippet" cols="100" rows="10"></textarea>
       
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
        <h4><?php _e('Edit Ads','video-blogger');?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Change your existing ads...','video-blogger');?></p>
                
                <form method="post" name="editads" action="<?php VideoBlogger_ui::form_action(); ?>">
                    
                    <?php VideoBlogger_WP::hidden_form_values('editads',__('Edit Ads','video-blogger'));?>

                    <?php  
                    if(!isset($wtgvb_settings['adsnippets'])){
                        echo '<p>'. __('You do not have any ads stored.','video-blogger') .'</p>';    
                    }else{

                        VideoBlogger_WP::tablestart();
                        
                        // head
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th width="75">'. __('Delete','video-blogger') .'</th>';
                        echo '<th width="75">'. __('Array ID','video-blogger') .'</th>'; 
                        echo '<th width="200">'. __('Ad Provider','video-blogger') .'</th>';
                        echo '<th>'. __('Snippet','video-blogger') .'</th>';
                        echo '<th>'. __('Preview','video-blogger') .'</th>';
                        echo '<th>'. __('Actions','video-blogger') .'</th>';
                        echo '</tr>';
                        echo '</thead>';
                        
                        // foot
                        echo '<tfoot>';          
                        echo '<tr>';
                        echo '<th>'. __('Delete','video-blogger') .'</th>';
                        echo '<th>'. __('Array ID','video-blogger') .'</th>'; 
                        echo '<th>'. __('Ad Provider','video-blogger') .'</th>';
                        echo '<th>'. __('Snippet','video-blogger') .'</th>';
                        echo '<th>'. __('Preview','video-blogger') .'</th>';
                        echo '<th>'. __('Actions','video-blogger') .'</th>';
                        echo '</tr>';
                        echo '</tfoot>';
                    
                            // body start
                        echo '<tbody>';

                        foreach($wtgvb_settings['adsnippets'] as $arrayID => $snippet_array){
                            
                            echo '<tr>';
                            echo '<th><input type="checkbox" name="wtgvb_ad_delete_'.$arrayID.'"></th>';
                            echo '<th>'.$arrayID.'</th>'; 
                            echo '<th>'.$snippet_array['source'].'</th>';
                            echo '<th><textarea name="wtgvb_adsnippet_' . $arrayID . '">'.$snippet_array['snippet'].'</textarea></th>';
                            echo '<th>'.$snippet_array['snippet'].'</th>';
                            echo '<th></th>';# delete, view stats
                            echo '</tr>';
                    
                        }
                                  
                        // body finish
                        echo '</tbody></table>';
                        
                        echo '<p>'. __('PPC services have limits to the number of ads displayed per page. Some ads may not show a preview.','video-blogger') .'</p>';
                    }
                    ?>                                    
                    
                    <input class="button" type="submit" value="Submit" />
                </form>                 
                                                                              
            </div>
        </div> 
        <!-- Panel End -->
    </div>
    <!-- Box End -->
               
</div>