=== Plugin Name ===
Contributors: Zara Walsh
Donate link: http://www.webtechglobal.co.uk
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: video,blog,blogger,videos,youtube,vimeo,bliptv,metacafe,break,videoplayer,facebook videos,google videos,video content,episodes,episode,episodic
Requires at least: 3.5.0
Tested up to: 3.6.0
Stable tag: trunk

Video Blogger Plugin

== Description ==

Add YouTube, Blip TV, Vimeo and other streaming website to your own blog in the form of individual video posts or well constructed playlists. Video Blogger
by WebTechGlobal takes a different approach to other video plugins such as YouTube Sidebar. Custom post type is registered for individual video posts
and another register for playlists. Playlists are built using video posts and with it comes many great features other plugins do not offer. There is
functionality many developers find great to work with such as being able to add multiple video sources to one video post. This is great for reducing
the appearance of those horrible empty dark boxes on your website years later when an author removes their video. There is more innovation to come and 
we are waiting for your requests. 

= Intentions =
An intended direction of this plugin is episode management. Features to communicate new episodes and
invite others to publish their videos on your website is something we have in mind. We want to create highly dynamic playlists and public
features for anyone wanting to build a video based community.

== Installation ==

Initial upload and activation as guided by Wordpress.org standard plugin installation.

1. Upload the `video-blogger` folder to the `/wp-content/plugins/` directory
1. Activate the Video Blogger plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the Settings screen
1. Replace our example Google AdSense with your own
1. Create posts in V.B. Videos, add one or more sources per post and add the [v] shortcode to content
1. Create a post in V.B. Playlists if you wish and add multiple videos posts, add the [p] shortcode
1. Considering creating theme templates by copying the ones provided into your themes root folder (do not customize plugins files)

== Frequently Asked Questions ==

= What was the plugin developed? =
Development begun 1st August 2013 and the plugin was released 12th August 2013. 
 
 = Can Video Blogger help me make money? =
Yes it can but you must remember to make use of it regularly. The plugin is designed for Wordpress bloggers who like to embed many videos. You can then create 
a video spot on your blogs which your visitors become familiar with. When a post does not have a video, rather than show nothing, we can show Google
AdSense. Your visitors will often focus on the AdSense ad expecting a video to be there. 

= What URL should I submitted as a Source to display my video? =
Each video streaming website has URL for different purposes i.e. link, using in embed, playlists, episodes. As I type this
the plugin is still very new and expects the embed URL for most sites. YouTube is a little easier, you can use the YouTube URL displayed in browser when visiting
a YouTube video on YouTube.com. This area of functionality is priority and the hope is that most URL will work to help make things like confusing. 

= Is YouTube.com supported? =
Yes

= Is Blip.tv supported? =
Yes

= Is Vimeo.com supported? =
Yes

= Is Vevo.com supported? =
Yes

= Is Dailymotion.com supported? =
Yes

= Is Metacafe.com supported? =
Yes

= Is Break.com supported? =
Yes

== Screenshots ==

1. Video Blogger places Google AdSense between videos in a playlist

== Changelog ==

= 1.0.3 =
* From The Developers
    * Released 4th November 2013
* Fixes     
    * None
* Feature Changes
    * New core added: classes now in use
    * Log screen now has default columns set to display (previously one column was being displayed on installation)
    * Log screen now accepts search by URL (stored criteria is ignored and not affected)
* Technical Notes
    * Plugin strings prepared for translation
    
= 1.0.2 =
* From The Developers
    * Released 12th August 2013
* Fixes     
    * None
* Feature Changes
    * Beta notice removed
    * Ads page added, it allows ads to be placed between videos, more options are needed and coming for this
* Technical Notes
    * Beta mode is no longer active by default
    * Removed theme option installation from wtgcore_wp_install.php
    
= 1.0.1 =
* From The Developers
    * Released 12th August 2013
* Fixes     
    * None
* Feature Changes
    * Maximum parameter added to playlist shortcode
    * Playlist will not use the same URL twice when the URL exists in two different video posts
* Technical Notes
    * None
    
= 1.0.0 =
* From The Developers
    * Released Beta
* Fixes     
    * None
* Feature Changes
    * None
* Technical Notes
    * None