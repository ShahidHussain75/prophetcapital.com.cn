<?php

class UPME_Featured_Members{
    
    public $upme_settings;

	function __construct() {
        
        
        add_filter('upme_module_settings_array_fields', array($this, 'settings_list'));
        add_filter('upme_init_options', array($this,'general_settings') );
        add_filter('upme_default_module_settings', array($this,'default_module_settings'),12 );    
                
        add_action('init',array($this,'intialize_featured_member_settings'));        
        add_action( 'plugins_loaded', array($this,'upme_featured_members_plugin_init') );
        
	}
     
    public function settings_list($settings){
        $settings['upme-featured-members-settings'] = array('featured_members_enabled_status','featured_member_level_1_color','featured_member_level_2_color',
            'featured_member_level_3_color','featured_member_level_4_color','featured_member_level_5_color');
        return $settings;
    }
        
    public function general_settings($settings){
        $settings['featured_members_enabled_status'] = '0';
        $settings['featured_member_level_1_color'] = '';
        $settings['featured_member_level_2_color'] = '';
        $settings['featured_member_level_3_color'] = '';
        $settings['featured_member_level_4_color'] = '';
        $settings['featured_member_level_5_color'] = '';
        return $settings;
    }

    public function default_module_settings($settings){
    
        $settings['upme-featured-members-settings'] = array(
                                            'featured_members_enabled_status' => '0',
                                            'featured_member_level_1_color' => '',
                                            'featured_member_level_2_color' => '',
                                            'featured_member_level_3_color' => '',
                                            'featured_member_level_4_color' => '',
                                            'featured_member_level_5_color' => '',
                                        );
        return $settings;
    }

    
    public function module_tabs(){
        echo '<li class="upme-tab " id="upme-featured-members-settings-tab">'. __('Featured Members','upme').'</li>';
    }

    public function module_settings(){
        global $upme_template_loader,$wpdb;        
        
        ob_start();
        $upme_template_loader->get_template_part('featured-members');
        $display = ob_get_clean();        
        echo $display;
    }
    
    public function intialize_featured_member_settings(){
        global $upme_options;
        $this->featured_members_enabled_status = isset($upme_options->upme_settings['featured_members_enabled_status']) ? $upme_options->upme_settings['featured_members_enabled_status'] : '0' ;
        $this->current_user = get_current_user_id();

        $upme_featured_member_status = isset($upme_options->upme_settings['featured_members_enabled_status']) ? $upme_options->upme_settings['featured_members_enabled_status'] : '0';
        if($upme_featured_member_status == '1'){
            add_filter('upme_search_custom_orderby_conditions',array($this,'upme_search_custom_orderby_conditions'),10,2);
            add_filter('upme_search_custom_orderby_meta_joins',array($this,'upme_search_custom_orderby_meta_joins'),10,2);
            add_filter('upme_search_custom_orderby_meta_where',array($this,'upme_search_custom_orderby_meta_where'),10,2);
    
        }
        
    }
    

    public function upme_featured_members_plugin_init(){

        add_action('upme_addon_module_tabs',array($this, 'module_tabs') );
        add_action('upme_addon_module_settings',array($this, 'module_settings') );   
    }


     
    public function upme_search_custom_orderby_conditions($orderby,$search_custom_orderby_meta_joins_params){
        global $upme;
        $orderby = " mtf.meta_value desc, " . $orderby ;
        return $orderby;
    }

     public function upme_search_custom_orderby_meta_joins($orderby_meta_custom_joins,$search_custom_orderby_meta_joins_params){
        //print_r($orderby_meta_custom_joins);exit;
        global $wpdb;
        $orderby_meta_custom_joins = ' inner JOIN ' . $wpdb->usermeta . ' as mtf ON (users.ID = mtf.user_id) ';
        return $orderby_meta_custom_joins;
    }

    public function upme_search_custom_orderby_meta_where($upme_search_custom_orderby_meta_where,$search_custom_orderby_meta_joins_params){
        array_push($upme_search_custom_orderby_meta_where, " (mtf.meta_key = 'upme_featured_member_level') ");       
        return $upme_search_custom_orderby_meta_where;
    }
    
}




// add_action( 'plugins_loaded', 'upme_featured_members_plugin_init' );

// function upme_featured_members_plugin_init(){
//     global $upme_featured_members;
    $upme_featured_members = new UPME_Featured_Members();
// }