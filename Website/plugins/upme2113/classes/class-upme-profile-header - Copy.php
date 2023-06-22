<?php

class UPME_Profile_Header {
    
    public function profile_header_block($profile_shortcode_params){ 
        global $upme;
        
        $this->profile_shortcode_params = $profile_shortcode_params;  
        
        $this->init_header_data();
        
        $display = '';
        
        $profile_header_design = isset($upme->upme_options['profile_header_design']) ? $upme->upme_options['profile_header_design'] : '0';
        
        if(is_null($this->profile_shortcode_params['view'])){
            switch($profile_header_design){
                case '0':
                    $display = $this->default_profile_header($profile_shortcode_params);
                    break;

                case '1':
                    $display = $this->profile_header_one($profile_shortcode_params);
                    break;

                case '2':
                    $display = $this->profile_header_two($profile_shortcode_params);
                    break;
            }
        }else{
            $display = $this->default_profile_header($profile_shortcode_params);
        }

        /* END HEADER BLOCK */
        return $display;
    }
    
    public function init_header_data(){
        global $upme;
        extract($this->profile_shortcode_params);
        
        $this->profile_title_field = $upme->upme_options['profile_title_field'];
        $this->profile_title_display = $upme->upme_profile_title_value($this->profile_title_field, $id);
        
        // Enable profile loading on new window
        $this->new_window_display = ('yes' == $new_window || 'true' == $new_window) ? ' target="_blank" ' : '';
        $this->new_window_display_pic = $this->new_window_display;
        
        $this->profile_url = $this->get_profile_url();
        
        if('yes' == $modal || 'true' == $modal){
            $this->new_window_display = ' class="profile-fancybox upme_inner_modal" upme-data-modal-active="INACTIVE" data-url="'. $this->profile_url .'"';
        }
        
        $this->cover_image = $this->get_cover_image($id,$view);
        $this->profile_pic_display = $this->get_profile_pic_display();
        $this->profile_title_display = $this->get_profile_title_display();
        
        if ($use_in_sidebar == 'yes' || $use_in_sidebar) {
            $this->link = get_permalink($upme->get_option('profile_page_id'));
            $this->class = "upme-button-alt";
            $this->link_text = __('View Profile', 'upme');
        } else {
            $this->link = '#edit';
            $this->class = "upme-button-alt upme-fire-editor upme-fire-editor-view";
            $this->link_text = __('Edit Profile', 'upme');
        }

        //Enable customlogout url
        $this->logout_url = '';
        if($logout_redirect){
            $this->logout_url = ' redirect_to='.$logout_redirect;
        }
        
        $this->profile_edit_bar = $this->profile_edit_bar();
        $this->profile_header_bar = $this->profile_header_bar();
    }
    
    public function get_cover_image($id,$view){
        global $upme;
        
        $cover_image_default = upme_url . 'img/cover-default.png';
        
        if(isset($upme->upme_options['profile_cover_image_status']) && $upme->upme_options['profile_cover_image_status'] && is_null($view) ){
            $cover_image = get_user_meta($id,'user_cover_pic',true);
            $cover_image_display = '';
            if($cover_image != ''){
                $cover_image_display = '<img src="'.$cover_image.'" />';
            }else{                
                $cover_image_display = '<img src="'.$cover_image_default.'" />';
            }
            return $cover_image_display;
        }else if(is_null($view)){
            $cover_image_display = '<img src="'.$cover_image_default.'" />';
        }       
        
        return $cover_image_display;        
    }
    
    public function get_profile_pic_display(){
        global $upme;
        extract($this->profile_shortcode_params);
        
        $profile_url = $this->profile_url;
        
        $profile_pic_display = '';
        if ($upme->get_option('clickable_profile')) {
            if ($upme->get_option('clickable_profile') == 1) {
                if ('compact' == $view) {
                    $profile_pic_display .= '<a href="'.$profile_url.'" upme-data-user-id="'.$id.'" '.$this->new_window_display.'>' . $upme->pic($id, 50) . '</a>';
                } else {
                    $profile_pic_display .= '<a href="'.$profile_url.'">' . $upme->pic($id, 50) . '</a>';
                }
            }else{
                $profile_pic_display .= '<a href="' . get_author_posts_url($id) . '" '.$this->new_window_display_pic.'>' . $upme->pic($id, 50) . '</a>';

            }
        }else{
            $profile_pic_display .= $upme->pic($id, 50);                           
        }
        
        return $profile_pic_display;
    }
    
    public function get_profile_url(){
        global $upme;
        extract($this->profile_shortcode_params);
        
        $params = array('id' => $id, 'view' => $view, 'modal' => $modal, 'group'=>$group , 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
        /* UPME Filter for customizing profile URL */
        $profile_url = apply_filters('upme_custom_profile_url',$upme->profile_link($id),$params);
        
        if('yes' == $modal || 'true' == $modal){
            $new_window_display = ' class="profile-fancybox upme_inner_modal" upme-data-modal-active="INACTIVE" data-url="'.$profile_url.'"';
            $profile_url = '#upme_inner_modal';
        }
        
        return $profile_url;
    }
    
    public function get_profile_title_display(){
        global $upme;
        extract($this->profile_shortcode_params);
        
        $display = '';
        
        if ($upme->get_option('clickable_profile')) {
            if ($upme->get_option('clickable_profile') == 1) { 
                if('compact' == $view){
                    $display .= '<a href="'.$this->profile_url.'" upme-data-user-id="'.$id.'" ' .$this->new_window_display. ' >';
                }else if('yes' != $modal_view && 'true' != $modal_view){
                    $display .= '<a href="' . $this->profile_url . '" >';
                }

            } else if('yes' != $modal_view && 'true' != $modal_view){ 
                $display .= '<a href="' . get_author_posts_url($id) . '" ' .$this->new_window_display_pic. '>';
            }


            $display .= $this->profile_title_display;
            $display .= '</a>';
        } else {
            $display .= $this->profile_title_display;
        }
        
        return $display;
    }
    
    public function profile_edit_bar(){
        global $upme;
        extract($this->profile_shortcode_params);
        
        $params = array('id' => $id, 'view' => $view, 'modal' => $modal, 'group'=>$group , 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
        
        $display = '';
        $target_window = '';
        if(isset($_POST['upme_modal_profile']) && 'yes' == $_POST['upme_modal_profile']){

            $this->link = $upme->profile_link($id);
            $this->link = upme_add_query_string($this->profile_url, 'upme_modal_target_link=yes');
            $target_window = ' target="_blank" ';

            $edit_buttons = '<a '.$target_window.' href="' . $this->link . '" class="' . $this->class . '">' . $this->link_text . '</a>&nbsp;' . do_shortcode('[upme_logout wrap_div="false" user_id="' . $id . '"  '.$this->logout_url.']');
            $params['type'] = 'modal';

            $display .= '<div class="upme-field-edit-modal">';
            /* UPME Filters for profile edit buttons panel */
            $display .= apply_filters( 'upme_profile_edit_bar', $edit_buttons , $id, $params);
            // End Filter
            $display .= '</div>';

        }else{   

            $edit_buttons = '<a  href="' . $this->link . '" class="' . $this->class . '">' . $this->link_text . '</a>&nbsp;' . do_shortcode('[upme_logout wrap_div="false" user_id="' . $id . '"  '.$this->logout_url.']');
            $params['type'] = $view;

            $display .= '<div class="upme-field-edit">';
            /* UPME Filters for profile edit buttons panel */
            $display .= apply_filters( 'upme_profile_edit_bar', $edit_buttons , $id, $params);
            // End Filter
            $display .='</div>';
        }
        
        return $display;
    }
    
    public function profile_header_bar(){
        global $upme;
        extract($this->profile_shortcode_params);
        
        $user_profile_form_name = get_user_meta($id,'upme-register-form-name',true);
        if( $user_profile_form_name == '' ){
            $user_profile_form_name = $upme->profile_form_name;
        }

        /* UPME Filters for customizing profile form name */
        $profile_form_name_params = array('user_id' => $id ,'view'=>$view , 'page_form_name' => $upme->profile_form_name, 'profile_form_name' => $user_profile_form_name, 'width'=>$width);
        $user_profile_form_name = apply_filters( 'upme_profile_form_name', $user_profile_form_name, $profile_form_name_params);
        // End Filter

        /* UPME Filters for profile header buttons panel */
        $header_bar_params = array('view'=>$view , 'form_name' => $user_profile_form_name, 'width'=>$width);
        $display = apply_filters( 'upme_profile_header_bar', '' ,$id, $header_bar_params);
        // End Filter
        
        return $display;
    }
    
    public function profile_header_one($params){
        global $upme;
        extract($params);
        
        $display .= '<div class="upme-profile-header-one">';
        
        $display .= '<div class="upme-cover-image">' . $this->cover_image ;

        /* UPME Filter for customizing profile picture */
        $params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
        $profile_pic_display = apply_filters('upme_custom_profile_pic',$this->profile_pic_display,$params);
        $display .= '<div class="upme-profile-picture" >' . $profile_pic_display . '</div>';
        // End Filter

        $display .= '<div class="upme-profile-title" >' . $this->profile_title_display . '</div>';
        
        if ($upme->can_edit_profile($upme->logged_in_user, $id)) {
            $display .= '<div class="upme-profile-edit-bar" >' . $this->profile_edit_bar . '</div>';
        }
        
        

        if (($width == '2' || $width == '3') && ($view != 'compact')) {
            $display .= '<div class="upme-clear"></div>';
        }

        
        if ($show_stats != 'no' && $show_stats != 'false') {
            $display .= $upme->show_user_stats($id);
        }
        
        if ($show_social_bar != 'no' && $show_social_bar != 'false') {
            $display .= '<div class="upme-profile-social-bar" >' . $upme->show_user_social_profiles($id) . '</div>';
        }
        
        $display .= $this->profile_header_bar;

        $display .= '</div>';
        $display .= '</div>';
        return $display;
    }
    
    public function profile_header_two($params){
        global $upme;
        extract($params);
        
        $display .= '<div class="upme-profile-header-two">';
        
        $display .= '<div class="upme-cover-image">' . $this->cover_image ;

        /* UPME Filter for customizing profile picture */
        $params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
        $profile_pic_display = apply_filters('upme_custom_profile_pic',$this->profile_pic_display,$params);
        $display .= '<div class="upme-profile-picture" >' . $profile_pic_display . '</div>';
        // End Filter

        $display .= '<div class="upme-profile-title" >' . $this->profile_title_display . '</div>';
        
        if ($upme->can_edit_profile($upme->logged_in_user, $id)) {
            $display .= '<div class="upme-profile-edit-bar" >' . $this->profile_edit_bar . '</div>';
        }
        
        

        if (($width == '2' || $width == '3') && ($view != 'compact')) {
            $display .= '<div class="upme-clear"></div>';
        }

        
//        if ($show_stats != 'no' && $show_stats != 'false') {
//            $display .= $upme->show_user_stats($id);
//        }
        
        if ($show_social_bar != 'no' && $show_social_bar != 'false') {
            $display .= '<div class="upme-profile-social-bar" >' . $upme->show_user_social_profiles($id) . '</div>';
        }
        
        $display .= '</div>';
        
        $display .= $this->profile_header_bar;

        
        $display .= '</div>';
        return $display;
    }
    
    public function default_profile_header($params){
        global $upme;
        extract($params);
        
        $display .= '<div class="upme-cover-image">' . $this->cover_image .'</div>';

        /* UPME Filters for after profile head section - DEPRECATED */
        $display .= apply_filters( 'upme_profile_before_head', '' , $id);

        if('compact' == $view){
            $display .= apply_filters( 'upme_compact_profile_before_head', '', $id);
        }else{
            $display .= apply_filters( 'upme_full_profile_before_head', '', $id);
        }
        // End Filters - DEPRECATED
        
        
        $display .= '<div class="upme-head">
                        <div class="upme-left">';

        
        $display .= '<div class="' . $pic_class . '">';
        /* UPME Filter for customizing profile picture */
        $params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
        $profile_pic_display = apply_filters('upme_custom_profile_pic',$this->profile_pic_display,$params);
        $display .= $profile_pic_display;
        // End Filter
        $display .= '</div>';

        if ($upme->can_edit_profile($upme->logged_in_user, $id)) {

            $display .= '<div class="upme-name">';
            
            $display .= '<div class="upme-field-name">';
            $display .= $this->profile_title_display;
            $display .= '</div>';

            /*  UPME filter for adding contents into header section between profile title and buttons */
            $profile_header_fields_params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
            $display .= apply_filters('upme_profile_header_fields','',$profile_header_fields_params );

            $display .= $this->profile_edit_bar;
            $display .='</div>';


        } else {

            $display .= '<div class="upme-name">';
            $display .= '<div class="upme-field-name">';
            $display .= $this->profile_title_display;
            $display .= '</div>';

            $profile_header_fields_params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
            $display .= apply_filters('upme_profile_header_fields','',$profile_header_fields_params );

            $display .= '</div>';
        }

        $display .= '</div>';



        if (($width == '2' || $width == '3') && ($view != 'compact')) {
            $display .= '<div class="upme-clear"></div>';
        }

        $display .= '<div class="upme-right">';

        if ($show_social_bar != 'no' && $show_social_bar != 'false') {
            $display .= $upme->show_user_social_profiles($id);
        }

        if ($show_stats != 'no' && $show_stats != 'false') {
            $display .= $upme->show_user_stats($id);
        }

        $display .= '</div><div class="upme-clear"></div>';


        $display .= $this->profile_header_bar;

        $display .= '</div>';
        
        return $display;
    }
    
//    public function default_profile_header($params){
//        global $upme;
//        extract($params);
//        
//        $profile_title_field = $upme->upme_options['profile_title_field'];
//        $profile_title_display = $upme->upme_profile_title_value($profile_title_field, $id); 
//        
//        if(isset($upme->upme_options['profile_cover_image_status']) && $upme->upme_options['profile_cover_image_status'] && is_null($view) ){
//            $cover_image = get_user_meta($id,'user_cover_pic',true);
//            $cover_image_display = '';
//            if($cover_image != ''){
//                $cover_image_display = '<img src="'.$cover_image.'" />';
//            }
//            $display .= '<div class="upme-cover-image">'.$cover_image_display.'</div>';
//
//        }
//
//        /* UPME Filters for after profile head section - DEPRECATED */
//        $display .= apply_filters( 'upme_profile_before_head', '' , $id);
//
//        if('compact' == $view){
//            $display .= apply_filters( 'upme_compact_profile_before_head', '', $id);
//        }else{
//            $display .= apply_filters( 'upme_full_profile_before_head', '', $id);
//        }
//        // End Filters - DEPRECATED
//        
//        
//        $display .= '<div class="upme-head">
//
//        <div class="upme-left">';
//
//        // Enable profile loading on new window
//        $new_window_display = ('yes' == $new_window || 'true' == $new_window) ? ' target="_blank" ' : '';
//        $new_window_display_pic = $new_window_display;
//
//        $params = array('id' => $id, 'view' => $view, 'modal' => $modal, 'group'=>$group , 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
//        /* UPME Filter for customizing profile URL */
//        $profile_url = apply_filters('upme_custom_profile_url',$upme->profile_link($id),$params);
//        // End Filter
//
//        // Override new window setting when modal is set
//        if('yes' == $modal || 'true' == $modal){
//            $new_window_display = ' class="profile-fancybox upme_inner_modal" upme-data-modal-active="INACTIVE" data-url="'.$profile_url.'"';
//            $profile_url = '#upme_inner_modal';
//        }
//
//        $profile_pic_display = '';
//        if ($upme->get_option('clickable_profile')) {
//            if ($upme->get_option('clickable_profile') == 1) {
//                if ('compact' == $view) {
//                    $profile_pic_display .= '<a href="'.$profile_url.'" upme-data-user-id="'.$id.'" '.$new_window_display.'>' . $upme->pic($id, 50) . '</a>';
//                } else {
//                    $profile_pic_display .= '<a href="'.$profile_url.'">' . $upme->pic($id, 50) . '</a>';
//                }
//            }else{
//                $profile_pic_display .= '<a href="' . get_author_posts_url($id) . '" '.$new_window_display_pic.'>' . $upme->pic($id, 50) . '</a>';
//
//            }
//        }else{
//            $profile_pic_display .= $upme->pic($id, 50);                           
//        }
//
//        $display .= '<div class="' . $pic_class . '">';
//        /* UPME Filter for customizing profile picture */
//        $params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
//        $profile_pic_display = apply_filters('upme_custom_profile_pic',$profile_pic_display,$params);
//        $display .= $profile_pic_display;
//        // End Filter
//        $display .= '</div>';
//
//        if ($upme->can_edit_profile($upme->logged_in_user, $id)) {
//
//            $display .= '<div class="upme-name">
//            <div class="upme-field-name">';                        
//
//            if ($upme->get_option('clickable_profile')) {
//                if ($upme->get_option('clickable_profile') == 1) { 
//                    if('compact' == $view){
//                        $display .= '<a href="'.$profile_url.'" upme-data-user-id="'.$id.'" ' .$new_window_display. ' >';
//                    }else if('yes' != $modal_view && 'true' != $modal_view){
//                        $display .= '<a href="' . $profile_url . '" >';
//                    }
//
//                } else if('yes' != $modal_view && 'true' != $modal_view){ 
//                    $display .= '<a href="' . get_author_posts_url($id) . '" ' .$new_window_display_pic. '>';
//                }
//
//
//                $display .= $profile_title_display;
//                $display .= '</a>';
//            } else {
//                $display .= $profile_title_display;
//            }
//
//            $display .= '</div>';
//
//            /*  UPME filter for adding contents into header section between profile title and buttons */
//            $profile_header_fields_params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
//            $display .= apply_filters('upme_profile_header_fields','',$profile_header_fields_params );
//
//            if ($use_in_sidebar == 'yes' || $use_in_sidebar) {
//                $link = get_permalink($upme->get_option('profile_page_id'));
//                $class = "upme-button-alt";
//                $link_text = __('View Profile', 'upme');
//            } else {
//                $link = '#edit';
//                $class = "upme-button-alt upme-fire-editor upme-fire-editor-view";
//                $link_text = __('Edit Profile', 'upme');
//            }
//
//            //Enable customlogout url
//            $logout_url = '';
//            if($logout_redirect){
//                $logout_url = ' redirect_to='.$logout_redirect;
//            }
//
//            //Change link for modal edit button
//            $target_window = '';
//
//            // Enable  profile view/edit modes based on loading window
//            $params   = array('logout_url'=>$logout_url,'group'=>$group,'use_in_sidebar' => $use_in_sidebar, 'view' => $view);
//
//
//            if(isset($_POST['upme_modal_profile']) && 'yes' == $_POST['upme_modal_profile']){
//
//                $link = $upme->profile_link($id);
//                $link = upme_add_query_string($profile_url, 'upme_modal_target_link=yes');
//                $target_window = ' target="_blank" ';
//
//                $edit_buttons = '<a '.$target_window.' href="' . $link . '" class="' . $class . '">' . $link_text . '</a>&nbsp;' . do_shortcode('[upme_logout wrap_div="false" user_id="' . $id . '"  '.$logout_url.']');
//                $params['type'] = 'modal';
//
//                $display .= '<div class="upme-field-edit-modal">';
//                /* UPME Filters for profile edit buttons panel */
//                $display .= apply_filters( 'upme_profile_edit_bar', $edit_buttons , $id, $params);
//                // End Filter
//                $display .= '</div>
//            </div>';
//
//            }else{   
//
//                $edit_buttons = '<a  href="' . $link . '" class="' . $class . '">' . $link_text . '</a>&nbsp;' . do_shortcode('[upme_logout wrap_div="false" user_id="' . $id . '"  '.$logout_url.']');
//                $params['type'] = $view;
//
//                $display .= '<div class="upme-field-edit">';
//                /* UPME Filters for profile edit buttons panel */
//                $display .= apply_filters( 'upme_profile_edit_bar', $edit_buttons , $id, $params);
//                // End Filter
//                $display .='</div>
//                                </div>';
//            }
//
//
//        } else {
//
//            $display .= '<div class="upme-name">
//            <div class="upme-field-name ">';
//
//            if ($upme->get_option('clickable_profile')) {
//                if ($upme->get_option('clickable_profile') == 1) {
//
//                    if('compact' == $view){
//                        $display .= '<a href="' . $profile_url . '" upme-data-user-id="'.$id.'" ' .$new_window_display. '>';
//                    }else if('yes' != $modal_view && 'true' != $modal_view){
//                        $display .= '<a href="' . $profile_url . '" >';
//                    }
//
//                } else if('yes' != $modal_view && 'true' != $modal_view){  
//                    $display .= '<a href="' . get_author_posts_url($id) . '" ' .$new_window_display_pic. '>';
//                }
//
//                $display .= $profile_title_display;
//                $display .= '</a>';
//            } else {
//                $display .= $profile_title_display;
//            }
//
//            $display .= '</div>';
//
//            $profile_header_fields_params = array('id'=> $id, 'view' => $view, 'modal' => $modal, 'use_in_sidebar'=>$use_in_sidebar, 'context' => 'normal');
//            $display .= apply_filters('upme_profile_header_fields','',$profile_header_fields_params );
//
//            $display .= '</div>';
//        }
//
//        $display .= '</div>';
//
//
//
//        if (($width == '2' || $width == '3') && ($view != 'compact')) {
//            $display .= '<div class="upme-clear"></div>';
//        }
//
//        $display .= '<div class="upme-right">';
//
//        if ($show_social_bar != 'no' && $show_social_bar != 'false') {
//            $display .= $upme->show_user_social_profiles($id);
//        }
//
//        if ($show_stats != 'no' && $show_stats != 'false') {
//            $display .= $upme->show_user_stats($id);
//        }
//
//        $display .= '</div><div class="upme-clear"></div>';
//
//
//        $user_profile_form_name = get_user_meta($id,'upme-register-form-name',true);
//        if( $user_profile_form_name == '' ){
//            $user_profile_form_name = $upme->profile_form_name;
//        }
//
//        /* UPME Filters for customizing profile form name */
//        $profile_form_name_params = array('user_id' => $id ,'view'=>$view , 'page_form_name' => $upme->profile_form_name, 'profile_form_name' => $user_profile_form_name, 'width'=>$width);
//        $user_profile_form_name = apply_filters( 'upme_profile_form_name', $user_profile_form_name, $profile_form_name_params);
//        // End Filter
//
//        /* UPME Filters for profile header buttons panel */
//        $header_bar_params = array('view'=>$view , 'form_name' => $user_profile_form_name, 'width'=>$width);
//        $display .= apply_filters( 'upme_profile_header_bar', '' ,$id, $header_bar_params);
//        // End Filter
//
//        $display .= '</div>';
//        
//        return $display;
//    }
}

$upme_profile_header = new UPME_Profile_Header();