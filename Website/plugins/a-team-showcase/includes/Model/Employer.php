<?php

class A_Team_App_Model_Employer extends Awesome_Post {
    public static $model_name = 'employer';
    public static $post_type = 'ats-employer';

    public static $validation_rules = array(
        'name' => array(
            'rule' => 'required'
        )
    );

    public static $fill_rules = array(
        'foto'
    );

    public static $labels = array(
        'name'               => 'Employees',
        'singular_name'      => 'Employee',
        'menu_name'          => 'Employees',
        'name_admin_bar'     => 'Employee',
        'add_new'            => 'Add Employee',
        'add_new_item'       => '+ Add new member',
        'new_item'           => 'New Employee',
        'edit_item'          => 'Edit Employee',
        'view_item'          => 'View Employee',
        'delete_item'        => 'Fire Employee',
        'all_items'          => 'All Employees',
        'search_items'       => 'Search Employees',
        'parent_item_colon'  => 'Parent Employees:',
        'not_found'          => 'No employees found.',
        'not_found_in_trash' => 'No employees found in Trash.',
        'add_to_team'        => 'Add employee to team'
    );

    public static $fields = array(
        'title', 'editor', 'thumbnail', 'excerpt'
    );

    protected static $defaults = array(
        'name' => '',
        'department' => '',
        'short_bio' => '',
        'custom_fields' => array(
            'position' => '',
            'thumbnail_id' => 0,
            'email' => '',
            'phone' => '',
            'skype' => '',
            'link' => '',
            'profile' => '',
            'facebook' => '',
            'twitter' => '',
            'linkedin' => ''
        )
    );

    public function after_save($input){
        $thumbnail_id = $input['custom_fields']['thumbnail_id'];
        if(!empty($thumbnail_id)){
            set_post_thumbnail($this->id, $thumbnail_id);
        }else{
            delete_post_thumbnail($this->id);
        }
        wp_set_post_terms($this->id,  $input['department'], static::$taxonomy);
    }

    public function prepare_input($input){
        $custom_fields = $this->getDefault('custom_fields');
        $tmp = '';
        $data = array(
            'ID' => $this->id,
            'post_type' => static::$post_type,
            'post_status' => 'publish',
            'post_title' => $input['name'],
            'post_excerpt' => $input['short_bio'],
            'custom_fields' => $this->assign_custom_fields($custom_fields, $input['custom_fields'])
        );

        return $data;
    }

    public static function unlink_teams($team_id){
        $team_employers = get_post_meta($team_id, 'employers', true);
        foreach($team_employers as $team_employer_id){
            $post = A_Team_App_Model_Employer::find($team_employer_id);
            $employer_teams_array = get_post_meta($post->ID, 'teams', true) ?: array();
            if(($key = array_search($team_id, $employer_teams_array)) !== false){
                unset($employer_teams_array[$key]);
            }
            update_post_meta($post->ID, 'teams', $employer_teams_array);
        }
    }

    public static function link_teams($team_employers_request, $team_id){
        foreach($team_employers_request as $team_employer_id){
            $post = A_Team_App_Model_Employer::find($team_employer_id);
            $employer_teams_array = get_post_meta($post->ID, 'teams', true);
            if($employer_teams_array == null){
                if(get_post_meta($post->ID, 'teams', true) !== ''){
                    update_post_meta($post->ID, 'teams', array($team_id)); // TODO CREATE OR UPDATE POST META FIELD
                }else{
                    add_post_meta($post->ID, 'teams', array($team_id));
                }
                continue;
            }
            if(!in_array($team_id, $employer_teams_array)){
                array_push($employer_teams_array, $team_id);
                update_post_meta($post->ID, 'teams', $employer_teams_array);
            }
        }
    }

    public static function update_teams($team_employers_request, $team_id){
        self::unlink_teams($team_id);
        self::link_teams($team_employers_request, $team_id);

    }

    public function delete($force_delete = true){
        if(isset($this->post->teams)){
            foreach($teams = $this->post->teams as $id){
                $team = A_Team_App_Model_Team::find($id);
                $employers = $team->employers;
                if(($key = array_search($this->id, $employers)) !== false){
                    unset($employers[$key]);
                    update_post_meta($team->ID, 'employers', $employers);
                }
            }
        }
        wp_delete_post($this->id, $force_delete);
    }
} 