<?php

	/* Add shortcodes */
	add_shortcode('upme', 'upme_shortcode');
	add_shortcode('upme_search', 'upme_search');
	add_shortcode('upme_registration', 'upme_registration');
	add_shortcode('upme_login', 'upme_login');
	add_shortcode('upme_private', 'upme_private');
	add_shortcode('upme_logout', 'upme_logout');
	add_shortcode('upme_reset_password', 'upme_reset_password');
    add_shortcode('upme_member', 'upme_member_content');
    add_shortcode('upme_non_member', 'upme_non_member_content');
    
    //add_shortcode('upme', 'upme_shortcode');
	
	function upme_shortcode($atts) {
		global $upme;
		return apply_filters('upme_profile_shortcode_display' , $upme->display( $atts ) , array('atts' => $atts) );
	}
	
	function upme_search($atts) {
		global $upme;
		return apply_filters('upme_search_shortcode_display' ,$upme->search($atts) , array('atts' => $atts) );
	}
	
	function upme_registration($atts) {
		global $upme;
		if (is_user_logged_in()) {
			return $upme->display( $atts );
		}else{
			return apply_filters('upme_registration_shortcode_display' ,$upme->show_registration($atts) , array('atts' => $atts) ); 
		}
		
	}
	
	function upme_login($atts) {
		global $upme;
		
		if (!is_user_logged_in()) {
		return apply_filters('upme_login_shortcode_display' ,$upme->login($atts) , array('atts' => $atts) ); 
		} else {
		return apply_filters('upme_mini_profile_shortcode_display' ,$upme->display_mini_profile($atts) , array('atts' => $atts) ); 
		}
	}
	
	function upme_private($atts, $content = null) {

		global $upme_private_content;
		$private_content_result = $upme_private_content->validate_private_content($atts, $content);
		return $upme_private_content->get_restriction_message($atts,$content,$private_content_result);
		// TODO - Call result message generation function and output the message

	}
	
	function upme_logout($atts) {
		global $upme;
		return $upme->logout($atts);
	}

	function upme_reset_password($atts) {
		global $upme;
		if (!is_user_logged_in()) {
			return apply_filters('upme_reset_password_shortcode_display' ,$upme->upme_reset_password($atts) , array('atts' => $atts) ); 
		} else {
			return apply_filters('upme_mini_profile_shortcode_display' ,$upme->display_mini_profile($atts) , array('atts' => $atts) ); 
		}

	}

    function upme_member_content($atts,$content){
        if (is_user_logged_in()) {
			return do_shortcode($content);
		} else {
			return '';
		}
    }

    function upme_non_member_content($atts,$content){
        if (!is_user_logged_in()) {
			return do_shortcode($content);
		} else {
			return '';
		}
    }
    
	
?>