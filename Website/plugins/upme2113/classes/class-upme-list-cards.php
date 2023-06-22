<?php
class UPME_List_Cards {

    public $upme_options;


    public function __construct() {
        global $upme;
        add_shortcode('upme_latest_members_list', array($this,'latest_members_list'));
    }
    
    public function latest_members_list($atts){
        global $upme_template_loader,$upme_list_card_params;
        extract( shortcode_atts( array(
            'limit'   => 10 ,
            'user_role' => '',
            'template' => 'members_icon_mini',
          ), $atts ) );
        
        $admin_users = get_users('role=administrator&orderby=registered&order=DESC');
        $admin_users_list = array();
        foreach ($admin_users as $admin_user) {
            array_push($admin_users_list,$admin_user->ID);
        }
        
        $display = '';
        
        $args = array(
                    'exclude'=> $admin_users_list,
                    'number' => $limit,
                    'orderby' => 'registered',
                    'order'   => 'desc',
                    'meta_query' => array(
                        'relation' => 'AND',
                        0 => array(
                            'key'     => 'upme_user_profile_status',
                            'value'   => 'ACTIVE',
                            'compare' => '='
                            ),
                        1 => array(
                            'key'     => 'upme_approval_status',
                            'value'   => 'ACTIVE',
                            'compare' => '='
                            ),
                        2 => array(
                            'key'     => 'upme_activation_status',
                            'value'   => 'ACTIVE',
                            'compare' => '='
                            )
                    )
                );
        
        if($user_role != ''){
            $args['role'] = $user_role;
        }
        
        $users_query = new WP_User_Query( $args );
        $results = $users_query->get_results();
        $upme_list_card_params['results'] = $results;
        
        ob_start();
        
        switch($template){
            case 'members_icon_mini':                
                $upme_list_card_params['css_class'] = 'upme-list-card-default-mini';
                $upme_template_loader->get_template_part('members-icon-mini');        
                break;
                
            case 'members_icon_mini_rounded':                
                $upme_list_card_params['css_class'] = 'upme-list-card-default-mini-rounded';
                $upme_template_loader->get_template_part('members-icon-mini');        
                break;
        }
        
        $display = ob_get_clean();
            
        return $display;       
    }
    
}

$upme_list_cards = new UPME_List_Cards();