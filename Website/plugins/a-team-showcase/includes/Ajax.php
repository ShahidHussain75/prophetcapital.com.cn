<?php

class A_Team_App_Ajax {

    /**
     * Get Model data by ID
     */
    public function ajax_handle_get_employer(){
        $input = $_REQUEST['data'];
        $post = A_Team_App_Model_Employer::find($input['id']);

        $thumb_id = get_post_thumbnail_id($post->ID);
        $thumb_url = wp_get_attachment_image_src($thumb_id, 'thumbnail');

        $terms = wp_get_post_terms($post->ID, A_Team_App_Model_Employer::getTaxonomy(), array('fields' => 'names'));


        $result = array(
            'model' => 'employer',
            'id' => $post->ID,
            'name' => $post->post_title,
            'department' => $terms,
            'short_bio' => $post->post_excerpt,
            'foto' => $thumb_url[0] ?: '', // $thumb_url returns array (url, width, height, (bool) resized)
            'thumbnail_id' => $thumb_id, // $thumb id
            'fill_rules' => A_Team_App_Model_Employer::$fill_rules
        );

        // mass assignment for custom fields
        $custom_fields = get_post_custom($post->ID);
        foreach($custom_fields as $key => $value){
            $result[$key] = $value[0];
        }

        $response = new WP_Ajax_Response;
        $response = Awesome_Response::get($response, json_encode($result), 'get_model');
        $response->send();

        exit();
    }

    protected function make_team($post){
        // get team employers list
        $employers = $post->employers;
        $shortcode = new A_Team_App_Shortcode();
        $post = A_Team_App_Model_Team::setDefaults($post);

        if(gettype($employers) == 'array' && count($employers) > 0){
            $employers = A_Team_App_Model_Employer::where(
                array(
                    'post_type' => A_Team_App_Model_Employer::$post_type,
                    'post__in' => $employers,
                    'orderby' => 'post__in'
                )
            );
            foreach($employers as $employer){
                $employer->post_id = $employer->ID;
                $foto = wp_get_attachment_image_src(
                    get_post_thumbnail_id($employer->ID),
                    'a-thumbnail'
                );
                $employer->foto = $foto[0];
                $employer->position = get_post_meta($employer->ID, 'position', true);
                $teams = get_post_meta($employer->ID, 'teams', true);
                $employer->teams_count = count($teams);
            }
        }

        // mass assignment for custom fields
        $custom_fields = $post->ID == 0 ? $post->custom_fields : get_post_custom($post->ID);
        foreach($custom_fields as $key => $value){
            $result[$key] = isset($post->$key) ? $post->$key : $post->custom_fields[$key];
        }

        $result['model'] = 'team';
        $result['id'] = $post->ID;
        $result['name'] = $post->post_title;
        $result['description'] = $post->post_excerpt;
        $result['employers'] = $employers;
        $result['fill_rules'] = A_Team_App_Model_Team::$fill_rules;
        $result['grid_html'] = $shortcode->preview($post, 'grid');
        $result['table_html'] = $shortcode->preview($post, 'table');
        $result['widget_html'] = $shortcode->preview($post, 'widget');

        return $result;
    }

    /**
     * Get Model data by ID
     */
    public function ajax_handle_get_team(){
        $input = $_REQUEST['data'];
        $post = A_Team_App_Model_Team::find($input['id']);

        $result = $this->make_team($post);

        $response = new WP_Ajax_Response;
        $response = Awesome_Response::get($response, json_encode($result), 'get_model');
        $response->send();

        wp_die();
    }

    /**
     * Get Model defaults
     */
    public function ajax_handle_get_team_defaults(){
        $defaults = A_Team_App_Model_Team::getDefaults();

        $post = new stdClass;
        $post->ID = 0;
        $post->post_name = '';
        $post->post_type = 'team';
        $post->post_excerpt = '';
        $post->employers = array();
        $post->custom_fields = $defaults['custom_fields'];

        $post = new WP_Post($post);
        $result = $this->make_team($post);

        $response = new WP_Ajax_Response();
        $response = Awesome_Response::get($response, json_encode($result), 'get_team_defaults');
        $response->send();

        wp_die();
    }


    /**
     * Route for add Employer Ajax request
     *
     * @return xml WP_Response
     */
    public function ajax_handle_update_or_new_employer(){
        if(!empty($_POST['data'])){
            $prepare = $_REQUEST['data']['data'];
        }

        $input['id'] = isset($prepare['id']) ? $prepare['id'] : '';
        $input['name'] = isset($prepare['name']) ? $prepare['name'] : '';
        $input['department'] = isset($prepare['department']) ? $prepare['department'] : '';
        $input['short_bio'] = isset($prepare['short_bio']) ? $prepare['short_bio'] : '';
        $input['custom_fields'] = array(
            'position' => isset($prepare['position']) ? $prepare['position'] : '',
            'thumbnail_id' => isset($prepare['thumbnail_id']) ? $prepare['thumbnail_id'] : '',
            'email' => isset($prepare['email']) ? $prepare['email'] : '',
            'phone' => isset($prepare['phone']) ? $prepare['phone'] : '',
            'skype' => isset($prepare['skype']) ? $prepare['skype'] : '',
            'link' => isset($prepare['link']) ? $prepare['link'] : '',
            'profile' => isset($prepare['profile']) ? $prepare['profile'] : '',
            'facebook' => isset($prepare['facebook']) ? $prepare['facebook'] : '',
            'twitter' => isset($prepare['twitter']) ? $prepare['twitter'] : '',
            'linkedin' => isset($prepare['linkedin']) ? $prepare['linkedin'] : ''
        );

        $response = new WP_Ajax_Response;

        $validator = new Awesome_Validator('employer');
        $validation = $validator->validate($input);

        if(count($validation->errors) > 0){
            Awesome_Response::get($response, $validation, 'errors');
            $response->send();
            wp_die();
        }
        $id = !empty($input['id']) ? (int) $input['id'] : null;

        $employer = new A_Team_App_Model_Employer(
            array(
                'id' => $id
            )
        );

        $id = $employer->save($input);

        $response = Awesome_Response::get($response, $id, 'update_or_new_employer');
        $response->send();
        wp_die();
    }

    /**
     * Route for add Team Ajax request
     *
     * @return xml WP_Response
     */
    public function ajax_handle_update_or_new_team(){
        if(!empty($_POST['data'])){
//            parse_str($_REQUEST['data']['data'], $input);
//            $model = $_REQUEST['data']['model'];
        }
        $prepare = $_REQUEST['data']['data'];
        $input['id'] = isset($prepare['id']) ? $prepare['id'] : '';
        $input['name'] = isset($prepare['name']) ? $prepare['name'] : '';
        $input['description'] = isset($prepare['description']) ? $prepare['description'] : '';
        $input['custom_fields'] = array(
            'employers' => isset($prepare['employers_ids']) ? array_map('intval', $prepare['employers_ids']) : array(),
            'layout' => isset($prepare['layout']) ? $prepare['layout'] : '',
            'title' => isset($prepare['title']) ? $prepare['title'] : '',
            'custom_css' => isset($prepare['custom_css']) ? $prepare['custom_css'] : '',
            'styles' => isset($prepare['styles']) ? $prepare['styles'] : array()
        );


        $response = new WP_Ajax_Response;

        $validator = new Awesome_Validator('team');
        $validation = $validator->validate($input);

        if(count($validation->errors) > 0){
            $response = Awesome_Response::get($response, $validation, 'errors');
            $response->send();
            wp_die();
        }
        $id = !empty($input['id']) ? (int) $input['id'] : null;

        $team = new A_Team_App_Model_Team(
            array(
                'id' => $id
            )
        );
        $id = $team->save($input);

        $response = Awesome_Response::get($response, $id, 'update_or_new_team');
        $response->send();
        wp_die();
    }

    /**
     * Universal AJAX delete route
     */
    public function ajax_handle_delete_model(){
        $input = $_REQUEST['data'];
        $id = (int) $input['id'];
        $model_name = $input['model_name'];
        $response = new WP_Ajax_Response;

        if(isset($id) && isset($model_name)){
            $model_name = 'A_Team_App_Model_'.ucfirst($model_name);
            $post = new $model_name(array('id' => $id));
            $post->delete();
            $response = Awesome_Response::get($response, '', 'delete_model');
        }else{
            $errors = new WP_Error();
            $errors->add('delete-error', 'Id or model not defined');
            $response = Awesome_Response::get($response, $errors, 'errors');
        }
        $response->send();
        exit();
    }

    public function ajax_handle_get_team_preview_photo(){
        $input = $_REQUEST['data'];
        $id = (int) $input['id'];
        $size = $input['size'];
        $response = new WP_Ajax_Response;

        $photo = get_the_post_thumbnail(
            $id,
            $size
        );

        $response = Awesome_Response::get($response, $photo, 'team_preview_photo');
        $response->send();
        wp_die();
    }

    public function ajax_handle_get_team_employers_photos(){
        $input = $_REQUEST['data'];
        $ids = $input['ids'];
        $size = $input['size'];

        $default_avatar_path = plugins_url('admin/img/default-avatar.png', dirname(__FILE__));
        $default_avatar = '<img src="' . $default_avatar_path . '" class="attachment-thumbnail" alt="" ?>';

        $response = new WP_Ajax_Response;
        $result = array();

        foreach($ids as $id){
            $photo = get_the_post_thumbnail(
                $id,
                $size
            );
            $result[] = $photo ?: $default_avatar;
        }
        $response = Awesome_Response::get($response, json_encode($result), 'team_employers_photos');
        $response->send();
        wp_die();
    }

    public function ajax_handle_get_templates(){
        $templates = A_Team_App_Template::get_templates();

        $response = new WP_Ajax_Response;

        $response = Awesome_Response::get($response, json_encode($templates), 'get_templates');
        $response->send();
        wp_die();
    }

    public function ajax_handle_get_taxonomy_terms(){
        $input = $_REQUEST['data'];
        $tax = $input['taxonomy'];
        $query = isset($input['query']) ? $input['query'] : '';

        $response = new WP_Ajax_Response;
        $result = array();

        if($tax){
            $result = get_terms($tax, array(
                'hide_empty' => false,
//                'fields' => 'names',
                'name__like' => esc_attr($query)
            ));
        }

        $response = Awesome_Response::get($response, json_encode($result), 'get_taxonomy_terms');
        $response->send();
        wp_die();

    }
}