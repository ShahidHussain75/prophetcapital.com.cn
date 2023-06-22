<?php

class UPME_Profile{

    public $upme_settings;
    function __construct() {
        add_filter( 'upme_profile_tabbed_sections', array($this, 'profile_tabbed_sections') ,10,2);
        
        
        $this->upme_settings = get_option('upme_options');
        
        add_action('wp_ajax_upme_profile_delete_request', array($this, 'profile_delete_request'));
        
        add_action( 'init', array($this,'process_profile_delete_confirmation'));
    }
    
    public function  profile_tabbed_sections($display,$params){
        global $upme_template_loader,$upme_profile_tabs_params,$upme;
        
        extract($params);
        
        $profile_tab_status = $this->upme_settings['profile_tabs_display_status'];
        if('disabled' == $profile_tab_status){
            return '';
        }else if('enabled_members' == $profile_tab_status && !is_user_logged_in()){
            return '';
        }else if('enabled_owner' == $profile_tab_status && !$upme->can_edit_profile($upme->logged_in_user, $id) ){
            return '';
        }
        
        $upme_profile_tabs_params = $params;
        $upme_profile_tabs_params['initial_display'] = $this->upme_settings['profile_tabs_initial_display_status'];
        
        $tab_display_status = false;
	    if($group == '' && $view != 'compact'){
            $tab_display_status = true;
        }
        
        $tab_display_status = apply_filters('upme_profile_tabs_display_status',$tab_display_status,$params);

        if($tab_display_status && $hide_profile_tabs != 'yes'){

            ob_start();
            $upme_template_loader->get_template_part('profile-tabs');
            $display = ob_get_clean();
            return $display;
        }

        return $display;
    }
    
    
    
    public function profile_delete_request(){
        global $upme_email_templates;
        
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
        $profile_delete_hash = wp_generate_password(12,false);
        if( ($user_id != 0  && get_current_user_id() == $user_id ) || current_user_can('manage_options') ){
            
            $user = new WP_User( $user_id );
            $username = $user->user_login;  
            
            // Create profile delete confirmation link
            $link = get_permalink($this->upme_settings['login_page_id']);
            $query_str = "upme_action=upme_delete_profile&upme_id=" . $user_id . "&upme_profile_delete_code=" . $profile_delete_hash;
            $profile_delete_confirm_link = upme_add_query_string($link, $query_str);
            
            
            // Send email with profile remove confirmation link
            $send_params = array('profile_delete_confirm_link' => $profile_delete_confirm_link, 'username' => $username , 'email' => $user->user_email);
                    
            $admin_email_status = $upme_email_templates->upme_send_emails( 'delete_profile_confirm' , $user->user_email , '' , '' ,$send_params,$user_id);
            
            // Add random key to verify the delete profile confirmation link
            update_user_meta($user_id,'_upme_delete_profile_hash',$profile_delete_hash);
            echo json_encode(array('status' => 'success', 'msg' => __('Profile removal request success. We sent you an email with confirmation link. Please click the link to confirm the request.','upme')));
                             
                             
        }else{
            echo json_encode(array('status' => 'error', 'msg' => __('Profile removal request failed.','upme')));
        }
                             
        exit;
    }
    
    public function process_profile_delete_confirmation(){
        global $wpdb,$upme_login;
        
        if( isset( $_GET['upme_action'] ) && $_GET['upme_action'] == 'upme_delete_profile'){
            $user_id = isset($_GET['upme_id']) ? $_GET['upme_id'] : '';
            $upme_profile_delete_code = isset($_GET['upme_profile_delete_code']) ? $_GET['upme_profile_delete_code'] : '';
            
            $delete_code = get_user_meta($user_id,'_upme_delete_profile_hash',true);
            if($delete_code == $upme_profile_delete_code){
            
                $sql = $wpdb->prepare("DELETE FROM $wpdb->users WHERE ID = %d",$user_id);
                $wpdb->query($sql);
                $sql = $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id = %d",$user_id);
                $wpdb->query($sql);
                
                $upme_login->delete_profile_message = __('Profile deleted successfully.','upme');
                $upme_login->delete_profile_message_status  = 'upme-success';
            }else{
                $upme_login->delete_profile_message = __('Invalid profile delete request.','upme');
                $upme_login->delete_profile_message_status  = 'upme-errors';
            }
        }
    }

}

$upme_profile = new UPME_Profile();