<?php
/** 
 * About view for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 */
?>

<div class="wtgvb_unlimitedcolumns_container">

    <div class="wtgvb_unlimitedcolumns_left">
    
        <h4><?php _e('Introduction', 'video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Authors vision of Video Blogger revealed...', 'video-blogger'); ?></p>
                
                <p><?php _e('Hello my name is Ryan Bayne, the Author of Video Blogger. One day I had an idea for a video manager plugin that monitors all videos
                added to the blog. The first goal of that monitoring is to provide improved searches and that would happen by linking videos, with their
                Author (Wordpress user) and with a specific post. The ability to list videos as search result instantly came to mind. Next I considered how we could discourage 
                duplicate videos being added i.e. the same video or very similar from multiple sources. I stopped for a bit and I realized there was no
                real need to discourage the submission of duplicate or similar videos. What would be even better is taking advantage of it and managing it. 
                Consider a community website where all videos can submit videos. Rather than telling a user they cannot submit the snippet or ID for a video on
                YouTube because it has already been added. We instead allow the site to behave just as if they were the first to submit the video, but we make 
                use of the existing video in the post and display the existing. This would be user friendly and it also gives us the opportunity to record 
                statistics that tell us how keen people are to have certain videos added to the site.', 'video-blogger'); ?></p>
                
                <p><?php _e("So by now you can tell my approach is different from any other plugin but there is a lot of work to be done. As I prepared the plugin
                I realized that there is a whole lot more I could do. I'm going to prevent duplicate snippets being stored in the database but not prevent 
                a video being published by more than one user. So why not allow playlists to be created and many users can use the same video. Now a huge advantage
                in the video using the same source is that if a problem arises (video no longer hosted) it will be spotted by many and many will be keen to resolve it
                i.e. submit a replacement snippet or request its removal.", 'video-blogger'); ?></p>
                
                <p><?php _e("My last thoughts were to monetize playlists by displaying Google AdSense between videos. The ideas that started going through my head
                made me realize that we can duplicate YouTube's own approach to monetizing with ads i.e. users create playlists, opt to allow ads and they
                earn from our Video Blogger website as do we. Could this work? Well on a successfull website, yes. But we're not there yet and the plugin
                needs plenty work. Video Blogger can be used for basic video display but it is being targetted at video communities.", 'video-blogger'); ?></p>                    
                                                                              
            </div>
        </div> 
        
    </div>
    
    <div class="wtgvb_unlimitedcolumns_left">
    
        <h4><?php _e('Support', 'video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">

                <?php 
                $link_a = '<a class="button" href="http://forum.webtechglobal.co.uk/viewforum.php?f=8" title="CSV 2 POST Forum" target="_blank">Forum</a>';
                $link_b = '<a class="button" href="http://www.webtechglobal.co.uk/wp-login.php?action=register" title="Forum" target="_blank">register</a>';
                $link_c = '<a class="button" href="http://www.webtechglobal.co.uk/wp-login.php" title="WebTechGlobal Log-In" target="_blank">log into</a>';
                $link_d = '<a class="button" href="http://forum.webtechglobal.co.uk/viewtopic.php?f=18&t=8" title="How To Register" target="_blank">detailed instructions</a>'; 
                ?>
                
                <p><?php echo sprintf(__('All users (free and premium) are welcome to use the WebTechGlobal %1$s. Simply %2$s on our
                Wordpress blog, %3$s your WTG WP account, active your account by answering the anti-spam question
                and then your phpBB forum account will be automatically created. You can read more %4$s here
                with details about our WP to phpBB bridge.','video-blogger'), $link_a, $link_b, $link_c, $link_d);?></p>          
                                                        
            </div>
        </div>
            
        <h4><?php _e('Links', 'video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Information...', 'video-blogger'); ?></p>
                
                <ul>
                    <li><a class="button" href="http://www.webtechglobal.co.uk" title="Plugin authors own website, WebTechGlobal" target="_blank">Plugin Website</a></li>
                    <li><a class="button" href="http://forum.webtechglobal.co.uk/viewforum.php?f=31" title="Plugin forum on WebTechGlobal website" target="_blank">Plugin Forum</a></li>
                    <li><a class="button" href="http://www.twitter.com/WebTechGlobal/" title="Video Blogger Twitter Profile" target="_blank">Plugins Tweets</a></li>
                    <li><a class="button" href="http://www.linkedin.com/company/webtechglobal-ltd" title="Visit WebTechGlobal LinkedIn Profile" target="_blank">LinkedIn</a></li>
                </ul>                    
                                                                              
            </div>
        </div> 
        
    </div>

    <div class="wtgvb_unlimitedcolumns_left">
    
        <h4><?php _e('Support Video Blogger', 'video-blogger'); ?></h4>
        <div class="welcome-panel">
         
            <div class="welcome-panel-content">
          
                <p class="about-description"><?php _e('Please consider these Wordpress products...', 'video-blogger'); ?></p>

                <table>
                    <tbody>                                  
                        <tr>
                            <td><a href="http://www.tipsandtricks-hq.com/wordpress-affiliate-platform-plugin-simple-affiliate-program-for-wordpress-blogsite-1474?ap_id=WebTechGlobal" target="_blank"><img alt="" src="http://images.tipsandtricks-hq.com/my-product-banner/wp_aff_125_arrow.gif" width="125" height="125" /></a></td>
                            <td><a href="http://www.tipsandtricks-hq.com/wordpress-emember-easy-to-use-wordpress-membership-plugin-1706?ap_id=WebTechGlobal"><img alt="" src="http://images.tipsandtricks-hq.com/my-product-banner/eMember-banner-125.gif" width="125" height="125" /></a></td>
                            <td><a href="http://www.oiopublisher.com/ref.php?u=4059"><img alt="" src="http://www.oiopublisher.com/images/banners/125x125_1.gif" width="125" height="125" /></a></td>
                            <td><a href="http://www.appthemes.com/themes/classipress/?aid=438"><img alt="" src="http://www.webtechglobal.co.uk/images/affiliate-banners/classipress/classipress_125x125_02.jpg" width="125" height="125" /></a></td>
                            <td><a href="http://www.tipsandtricks-hq.com/wordpress-estore-plugin-complete-solution-to-sell-digital-products-from-your-wordpress-blog-securely-1059?ap_id=WebTechGlobal"><img alt="" src="http://images.tipsandtricks-hq.com/my-product-banner/eStore_banner_125_125.gif" width="125" height="125" /></a></td>
                            <td><a href="http://themeforest.net?ref=WebTechGlobal"><img alt="" src="http://www.webtechglobal.co.uk/images/affiliate-banners/theme_forest_125x125.gif" width="125" height="125" /></a></td>
                        </tr>
                    </tbody>
                </table>
                                                                                              
            </div>
        </div> 
    </div>
        
</div>