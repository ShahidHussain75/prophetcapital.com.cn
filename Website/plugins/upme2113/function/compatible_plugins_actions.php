<?php

add_action('bbp_template_before_replies_loop','upme_bbp_template_before_replies_loop');
function upme_bbp_template_before_replies_loop(){
    global $upme_bbpress_topic_content;
    if($upme_bbpress_topic_content != ''){
        echo $upme_bbpress_topic_content;
    }    
}

add_action( 'woocommerce_single_product_summary', 'upme_woocommerce_template_single_post_buttons', 6 );
function upme_woocommerce_template_single_post_buttons(){
    global $upme_woocommerce_topic_content;
    if($upme_woocommerce_topic_content != ''){
        echo $upme_woocommerce_topic_content;
    }    
}

add_action('upme_profile_field_before_update', 'upme_woocommerce_country_before_update' ,10,4);
add_action('upme_before_save_backend_field',   'upme_woocommerce_country_before_update',10,4);
add_action('upme_before_registration_field_update', 'upme_woocommerce_registration_country_before_update');
function upme_woocommerce_country_before_update($userid, $key, $value, $params){
	global $upme_frontend_field_update_status,$upme_backend_field_update_status;
	$country_list = upme_country_key_list();
	if(class_exists('WooCommerce')){

		if($key == 'shipping_country' || $key == 'billing_country'){
			$upme_frontend_field_update_status = FALSE;
            $upme_backend_field_update_status = FALSE;
			$value = $country_list[$value];
			update_user_meta($userid,$key,$value);
		}
	}
}

function upme_woocommerce_registration_country_before_update($params){
	global $upme_registration_field_update_status;
	extract($params);
	$country_list = upme_country_key_list();
	if(class_exists('WooCommerce')){

		if($meta == 'shipping_country' || $meta == 'billing_country'){
			$upme_registration_field_update_status = FALSE;
			$value = $country_list[$value];
			update_user_meta($user_id,$meta,$value);
		}
	}
}




?>