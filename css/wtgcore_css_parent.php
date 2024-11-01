<?php
// $side is already set as required parameter
if($side == 'admin' || $wtgvb_css_side_override == true){

    function wtgvb_register_admin_styles() {
        
        wp_register_style('wtgvb_css_notification',plugins_url(WTG_VB_FOLDERNAME . '/css/notifications.css'), array(), '1.0.0', 'screen');
        wp_register_style('wtgvb_css_admin',plugins_url(WTG_VB_FOLDERNAME . '/css/admin.css'), __FILE__);          
    }

    function wtgvb_admin_styles_callback() {
        wp_enqueue_style('wtgvb_css_notification');
        wp_enqueue_style('wtgvb_css_admin');               
    }

    // print admin only styles (must be preregistered)
    add_action('admin_print_styles','wtgvb_admin_styles_callback');
    add_action('init','wtgvb_register_admin_styles');
}

// do not make this an else, this is to allow the admin override to be used AND apply public specific lines
if($side == 'public'){
    
}
?>
