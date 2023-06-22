<?php

namespace DataSource;

/**
 * Class DataTableModel
 * @package DataSource
 */
class DataTableModel {

    // Custom post type
    const post_type = 'dts-data-table';

    // Table ID/PostID
    public $id = null;

    // Title
    public $title;

    // Custom properties stored as post meta
    private $properties = array();

    /**
     * Init table with table ID or data as array
     * @param array|int $data
     */
    public function __construct($data=array())
    {
        if (is_numeric($data)) {
            $this->id = $data;
            $this->load();
        } else {
            if (is_array($data) && count($data)) {
                $this->setValues($data);
            }
        }
    }

    /**
     * Load meta data
     */
    private function load()
    {
        if ($this->id) {
            $post = get_post( $this->id );
            if (!$post) {
                $this->id = null;
                return;
            }
            $this->title = $post->post_title;
            $this->id = $post->ID;
            $meta = get_post_meta($post->ID);
            if ($meta) {
                foreach ($meta as $key=>$item) {
                    $newKey = substr($key, 1);
                    $this->properties[$newKey] = $this->isJSON($item[0])?json_decode($item[0], true):$item[0];
                }
            }
        }
    }

    /**
     * Check if value is JSON
     * @param $string
     * @return bool
     */
    private function isJSON($string){
        return is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /**
     * Magic get method for meta data
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        return null;
    }

    /**
     * Save table
     * @return int|\WP_Error
     */
    public function save()
    {
        if ( !$this->id ) {
            $postId = wp_insert_post(
                array(
                    'post_type' => self::post_type,
                    'post_status' => 'publish',
                    'post_title' => $this->title,
                    'post_parent' => $this->properties['datasource'],
                )
            );
        } else {

            // Delete post meta
            $meta = get_post_meta($this->id);
            foreach ($meta as $key=>$item) {
                delete_post_meta($this->id, $key);
            }

            $postId = wp_update_post(
                array(
                    'ID' => (int) $this->id,
                    'post_status' => 'publish',
                    'post_title' => $this->title,
                    'post_parent' => $this->properties['datasource']
                )
            );
        }

        if ( $postId ) {
            foreach ( $this->properties as $key => $value ) {
                update_post_meta( $postId, '_' . $key, $value );
            }
        }

        return $postId;
    }

    /**
     * Delete table
     * @return bool
     */
    public function delete()
    {
        if ($this->id) {
            if ( wp_delete_post( $this->id, true ) ) {
                $this->id = 0;
                return true;
            }
        }
        return false;
    }

    /**
     * Set model properties
     * @param array $data
     */
    public function setValues($data)
    {
        $this->properties = array();

        foreach ($data as $field=>$value) {
            if ($field == 'id') {
                $this->id = $data['id'];
            } elseif ($field == 'title') {
                $this->title = $data['title'];
            } elseif (is_array($value)) {
                $this->properties[$field] = dts_json_encode($data[$field]);
            } else {
                $this->properties[$field] = $data[$field];
            }
        }
    }
}

?>