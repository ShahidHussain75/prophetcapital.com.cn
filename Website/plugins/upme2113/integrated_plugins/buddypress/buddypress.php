<?php

function display_avatar($avatar, $params) {
    global $upme;
    extract($params);
    //echo "<pre>";print_r($params);exit;
    $user_id = $params['item_id'];
    $user_pic = get_user_meta( $user_id, 'user_pic', true);
    /* Resizing feature for profile picture - Version 2.1.09 */    
    if($upme->upme_options['profile_image_resize_status']){
        if(get_user_meta($user_id, 'upme_user_pic_thumb', true) != ''){
            $user_pic = esc_url(get_user_meta($user_id,'upme_user_pic_thumb', true));
        }       
    }
    /* END Resizing feature */

    $html_css_id = ' id="' . esc_attr( $params['css_id'] ) . '"';
    
    
    $avatar_classes = $params['class'];
	if ( ! is_array( $avatar_classes ) ) {
		$avatar_classes = explode( ' ', $avatar_classes );
	}

	// merge classes
	$avatar_classes = array_merge( $avatar_classes, array(
		$params['object'] . '-' . $params['item_id'] . '-avatar',
		'avatar-' . $params['width'],
	) );

	// Sanitize each class
	$avatar_classes = array_map( 'sanitize_html_class', $avatar_classes );

	// populate the class attribute
	$html_class = ' class="' . join( ' ', $avatar_classes ) . ' photo"';
    
    $html_width = ' width="' . $params['width'] . '"';
    if ( false !== $params['height'] ) {
		// Height has been specified. No modification necessary.
	} elseif ( 'thumb' == $params['type'] ) {
		$params['height'] = bp_core_avatar_thumb_height();
	} else {
		$params['height'] = bp_core_avatar_full_height();
	}
	$html_height = ' height="' . $params['height'] . '"';
    
    $html_alt = ' alt="' . esc_attr( $params['alt'] ) . '"';
    $html_title = '';
    if ( ! empty( $params['title'] ) ) {
		$html_title = ' title="' . esc_attr( $params['title'] ) . '"';
	}
    
    $extra_attr = ! empty( $args['extra_attr'] ) ? ' ' . $args['extra_attr'] : '';
    
    if ($user_pic != '') {
        return '<img id="upme-avatar-user_pic" src="' . $user_pic . '" ' . $html_css_id . $html_class . $html_width . $html_height . $html_alt . $html_title . $extra_attr . ' />';
    } else {
        return $avatar;
    }
}
add_filter('bp_core_fetch_avatar', 'display_avatar', 15, 8);