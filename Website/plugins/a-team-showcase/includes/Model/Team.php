<?php

class A_Team_App_Model_Team extends Awesome_Post {
    public static $model_name = 'team';
    public static $post_type = 'ats-team';

    public static $validation_rules = array(
        'name' => array(
            'rule' => 'required'
        ),
    );

    public static $fill_rules = array(
        'employers',
        'layout',
        'template',
        'layout_html',

        'slider',
        'filter',

        'button_color',
        'button_hover_color',
        'button_text_color',
        'button_text_hover_color',

        'filter_bold',
        'filter_italic',

        'reveal',
        'hover',

        'body_visible',
        'photo_visible',
        'name_visible',
        'divider_visible',
        'position_visible',
        'short_bio_visible',
        'phone_visible',
        'email_visible',
        'skype_visible',
        'link_visible',
        'social_visible',

        'base_color',
        'card_base_color',
        'card_border_color',
        'card_divider_color',
        'card_shadow_color',
        'card_even_row_color',

        'name_bold',
        'name_italic',

        'position_bold',
        'position_italic',

        'short_bio_bold',
        'short_bio_italic',

        'contacts_bold',
        'contacts_italic',
    );

    public static $labels = array(
        'name'               => 'Teams',
        'singular_name'      => 'Team',
        'menu_name'          => 'Teams',
        'name_admin_bar'     => 'Team',
        'add_new'            => 'Add Team',
        'add_new_item'       => '+ Add new team',
        'new_item'           => 'New Team',
        'edit_item'          => 'Edit Team',
        'view_item'          => 'View Team',
        'delete_item'        => 'Delete Team',
        'all_items'          => 'My Teams',
        'search_items'       => 'Search Teams',
        'parent_item_colon'  => 'Parent Teams:',
        'not_found'          => 'No teams found.',
        'not_found_in_trash' => 'No teams found in Trash.'
    );

    public static $fields = array(
        'title', 'editor', 'excerpt'
    );

    protected static $defaults = array(
        'name' => '',
        'description' => '',
        'custom_fields' => array(
            'employers' => array(),
            'layout' => 'grid',
            'title' => '',
            'custom_css' => '',
            'styles' => array(
                'grid' => array(),
                'table' => array(),
                'widget' => array()
            )
        )
    );


    public function prepare_input($input){
        $custom_fields = $this->getDefault('custom_fields');
        $data = array(
            'ID' => $this->id,
            'post_type' => static::$post_type,
            'post_status' => 'publish',
            'post_title' => $input['name'],
            'post_excerpt' => $input['description'],
            'custom_fields' => $this->assign_custom_fields($custom_fields, $input['custom_fields'])
        );

        return $data;
    }

    public function save($input){
        $input = $this->set_defaults($input, static::getDefaults());
        $data = $this->prepare_input($input);

        $this->before_save($input);

        if($this->id){
            /* Edit Post */
            A_Team_App_Model_Employer::update_teams($input['custom_fields']['employers'], $this->id);
            $wp_post = wp_update_post($data);
            $this->save_custom_fields($wp_post, $data['custom_fields'], true);
        }else{
            /* Insert New Post */
            $wp_post = wp_insert_post($data);
            $this->save_custom_fields($wp_post, $data['custom_fields']);
            A_Team_App_Model_Employer::update_teams($input['custom_fields']['employers'], $wp_post);
        }

        $this->after_save($input);
        return $wp_post;
    }

    /**
     * @return array
     */
    public static function getDefaults()
    {
        $grid = A_Team_App_Template::get_template('grid');
        $table = A_Team_App_Template::get_template('table');
        $widget = A_Team_App_Template::get_template('widget');
        static::$defaults['custom_fields']['styles']['grid'] = $grid['aerial']['styles'];
        static::$defaults['custom_fields']['styles']['table'] = $table['anna']['styles'];
        static::$defaults['custom_fields']['styles']['widget'] = $widget['richard']['styles'];
        return static::$defaults;
    }

    public static function unsetDefault($styles, $root, $defaults){
        foreach($styles[$root] as $key => $value) {
            if (isset($styles[$root][$key]) && !array_key_exists($key, $defaults)) {
                unset($styles[$root][$key]);
            }
        }
        return $styles;
    }

    public static function setDefault($styles, $root, $defaults){
        foreach($defaults as $key => $value) {
            $def_instance = static::getDefaults();
            if (array_key_exists($key, $def_instance['custom_fields']['styles'][$root]) && !isset($styles[$root][$key])) {
                $styles[$root][$key] = $value;
            }
        }
        return $styles;
    }

    /**
     * Set default value if field doesn't exist
     *
     * @param $post
     */
    public static function setDefaults($post){
        $defaults = static::getDefaults();
        if(isset($post->styles)){
            $styles = static::unsetDefault($post->styles, 'grid', $defaults['custom_fields']['styles']['grid']);
            $styles = static::unsetDefault($styles, 'table', $defaults['custom_fields']['styles']['table']);
            $styles = static::unsetDefault($styles, 'widget', $defaults['custom_fields']['styles']['widget']);

            $styles = static::setDefault($styles, 'grid', $defaults['custom_fields']['styles']['grid']);
            $styles = static::setDefault($styles, 'table', $defaults['custom_fields']['styles']['table']);
            $styles = static::setDefault($styles, 'widget', $defaults['custom_fields']['styles']['widget']);
            $post->styles = $styles;
        }
        return $post;
    }

    /**
     * Cascade update for related employers
     * @param bool $force_delete
     */
    public function delete($force_delete = true){
        if(isset($this->post->employers)){
            foreach($employers = $this->post->employers as $id){
                $employer = A_Team_App_Model_Employer::find($id);
                $teams = $employer->teams ?: array();
                if(($key = array_search($this->id, $teams)) !== false){
                    unset($teams[$key]);
                    update_post_meta($employer->ID, 'teams', $teams);
                }
            }
        }
        wp_delete_post($this->id, $force_delete);
    }
} 