<?php

add_action( 'widgets_init', 'upme_latest_widget' );
function upme_latest_widget() { register_widget( 'upme_latest_widget_func' ); }

class upme_latest_widget_func extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'upme_latest_widget_func', 'description' => __('Displays a list of latest registered users.', 'upme') );
		
		$control_ops = array( 'id_base' => 'upme_latest_widget_func' );
		
		parent::__construct( 'upme_latest_widget_func', __('UPME - Latest Members', 'upme'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		global $upme;
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$users_num = $instance['users_num'];
		$pic_size = $instance['pic_size'];
		$link_profile = $instance['link_profile'];
        $field_user_title = $instance['field_user_title'];
		
		// Display widget
		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;
		        
        $admin_users = get_users('role=administrator&orderby=registered&order=DESC');
        $admin_users_list = array();
        foreach ($admin_users as $admin_user) {
            array_push($admin_users_list,$admin_user->ID);
        }
        
        $args = array('exclude'=> $admin_users_list,
                     'number' => $users_num,
                     'orderby' =>'registered',
                     'meta_query'   => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'upme_activation_status',
                            'value' => 'ACTIVE',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'upme_approval_status',
                            'value' =>  'ACTIVE',
                            'compare' => '='
                        ),
                        array(
                            'key' => 'upme_user_profile_status',
                            'value' =>  'ACTIVE',
                            'compare' => '='
                        ),
                     ),
                     'order' => 'DESC');
		$users = get_users($args);
		echo '<div class="upme-latest-mebers-widget upme-clearfix">';
		echo '<ul class="'.$widget_id.'">';
		foreach ($users as $user) {
			echo '<li>';
			echo '<div class="upme-widget-image">';

				$user_pic = get_user_meta($user->ID,'user_pic', true);
				/* Resizing feature for profile picture - Version 2.1.09 */    
                if($upme->upme_options['profile_image_resize_status']){
                    if(get_user_meta($user->ID,'upme_user_pic_thumb', true) != ''){
                        $user_pic = esc_url(get_user_meta($user->ID,'upme_user_pic_thumb', true));
                    }       
                }
                /* END Resizing feature */

				if ($user_pic != '') {
					echo '<img src="'.$user_pic.'" class="avatar avatar-'.$pic_size.'" />';
				} else {
					echo get_avatar($user->user_email, $pic_size);
				}
			echo '</div>';
			echo '<div class="upme-widget-info">';
						if ($link_profile == 1) {
							echo '<a href="'.$upme->profile_link($user->ID).'" title="'.sprintf(__('View %s Profile','upme'), $user->display_name).'">';
                            echo $this->user_title($field_user_title,$user);
							echo '</a>';
						} else {
                            echo $this->user_title($field_user_title,$user);
						}
			echo '</div>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
		
		echo $after_widget;
		
		print "<style type=\"text/css\">
				.$widget_id li {
					list-style-type: none;
					float: left;
					width: 100%;
				}
				
				.$widget_id .upme-widget-image {
					float: left;
					margin: 0 20px 0 0;
				}

				.$widget_id li div.upme-widget-image img.avatar {
					box-shadow: none;
					border-radius: ".$pic_size."px;
					margin: 0;
					padding: 0;
					width: ".$pic_size."px;
					height: ".$pic_size."px;
				}

				.$widget_id .upme-widget-info {
					float: left;
				}
			</style>";

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['users_num'] = strip_tags( $new_instance['users_num'] );
		$instance['pic_size'] = strip_tags( $new_instance['pic_size'] );
		$instance['link_profile'] = strip_tags( $new_instance['link_profile'] );
        $instance['field_user_title'] = strip_tags( $new_instance['field_user_title'] );
		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Latest Members','upme'), 'users_num' => 5, 'pic_size' => 40, 'link_profile' => 1 , 'field_user_title' => 0 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'upme'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
	
		<p>
			<label for="<?php echo $this->get_field_id( 'users_num' ); ?>"><?php _e('How many users to show:', 'upme'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'users_num' ); ?>" name="<?php echo $this->get_field_name( 'users_num' ); ?>" value="<?php echo $instance['users_num']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'pic_size' ); ?>"><?php _e('Profile picture size:', 'upme'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'pic_size' ); ?>" name="<?php echo $this->get_field_name( 'pic_size' ); ?>" value="<?php echo $instance['pic_size']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'link_profile' ); ?>"><?php _e('Link to user profile:', 'upme'); ?></label>
			<select id="<?php echo $this->get_field_id( 'link_profile' ); ?>" name="<?php echo $this->get_field_name( 'link_profile' ); ?>" class="">
				<option value="1"<?php selected('1', $instance['link_profile']); ?>><?php _e('Yes','upme'); ?></option>
				<option value="0"<?php selected('0', $instance['link_profile']); ?>><?php _e('No','upme'); ?></option>
			</select>
		</p>

        <p>
			<label for="<?php echo $this->get_field_id( 'field_user_title' ); ?>"><?php _e('Field for User Title:', 'upme'); ?></label>
			<select id="<?php echo $this->get_field_id( 'field_user_title' ); ?>" name="<?php echo $this->get_field_name( 'field_user_title' ); ?>" class="widefat">
                <option value="0"<?php selected('0', $instance['field_user_title']); ?>><?php _e('Display Name','upme'); ?></option>
				<option value="1"<?php selected('1', $instance['field_user_title']); ?>><?php _e('First Name','upme'); ?></option>
				<option value="2"<?php selected('2', $instance['field_user_title']); ?>><?php _e('Last Name','upme'); ?></option>
                <option value="3"<?php selected('3', $instance['field_user_title']); ?>><?php _e('First Name + Last Name','upme'); ?></option>
			</select>
		</p>

	<?php
	}
    
    function user_title($field_user_title,$user){
        $title = '';
        switch($field_user_title){
            case '0':
                $title = $user->display_name;
                break;
            case '1':
                $name = get_user_meta($user->ID, 'first_name', true);
                $title = ($name != '') ? $name : $user->display_name;
                break;
            case '2':
                $name = get_user_meta($user->ID, 'last_name', true);
                $title = ($name != '') ? $name : $user->display_name;
                break;
            case '3':
                $name = trim(get_user_meta($user->ID, 'first_name', true) . ' ' . get_user_meta($user->ID, 'last_name', true));
                $title = ($name != '') ? $name : $user->display_name;
                break;
        }
        return $title;
    }
}