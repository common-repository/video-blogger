<?php
/** 
 * Sections array for Video Blogger plugin 
 * 
 * @link by http://www.webtechglobal.co.uk
 * 
 * @author Ryan Bayne | ryan@webtechglobal.co.uk
 *
 * @package Video Blogger
 * 
 * @since 0.0.1
 * 
 * For now all sections are active by default. I'm preparing for a modular system but until it is complete I want all sections active at all times
 */

global $wtgvb_sections_array; 
$wtgvb_sections_array = array();
// affiliates
$wtgvb_sections_array['affiliates']['title'] = __('Affiliates','video-blogger');
$wtgvb_sections_array['affiliates']['active'] = true;
// clients
$wtgvb_sections_array['clients']['title'] = __('Clients','video-blogger');
$wtgvb_sections_array['clients']['active'] = true;
// community
$wtgvb_sections_array['community']['title'] = __('Community','video-blogger');
$wtgvb_sections_array['community']['active'] = true;
// downloads
$wtgvb_sections_array['downloads']['title'] = __('Downloads','video-blogger');
$wtgvb_sections_array['downloads']['active'] = true;
// finance
$wtgvb_sections_array['finance']['title'] = __('Finance','video-blogger');
$wtgvb_sections_array['finance']['active'] = true;
// forum
$wtgvb_sections_array['forum']['title'] = __('Forum','video-blogger');
$wtgvb_sections_array['forum']['active'] = true;
// lab
$wtgvb_sections_array['lab']['title'] = __('Lab','video-blogger');
$wtgvb_sections_array['lab']['active'] = true;
// monetize
$wtgvb_sections_array['monetize']['title'] = __('Monetize','video-blogger');
$wtgvb_sections_array['monetize']['active'] = true;
// questions
$wtgvb_sections_array['questions']['title'] = __('Questions','video-blogger');
$wtgvb_sections_array['questions']['active'] = true;
// rep
$wtgvb_sections_array['rewards']['title'] = __('Rewards','video-blogger');
$wtgvb_sections_array['rewards']['active'] = true;
// tickets
$wtgvb_sections_array['tickets']['title'] = __('Tickets','video-blogger');
$wtgvb_sections_array['tickets']['active'] = true;
// users
$wtgvb_sections_array['users']['title'] = __('Users','video-blogger');
$wtgvb_sections_array['users']['active'] = true;
// web
$wtgvb_sections_array['web']['title'] = __('Web','video-blogger');
$wtgvb_sections_array['web']['active'] = true;
?>
