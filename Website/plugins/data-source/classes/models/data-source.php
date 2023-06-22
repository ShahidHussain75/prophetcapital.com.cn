<?php

namespace DataSource;

// Include data source providers
require_once(DTS_PLUGIN_DIR.'/classes/downloader.php');
require_once(DTS_PLUGIN_DIR.'/classes/providers/mysql.php');
require_once(DTS_PLUGIN_DIR.'/classes/providers/csv.php');
require_once(DTS_PLUGIN_DIR.'/classes/providers/xls.php');
require_once(DTS_PLUGIN_DIR.'/classes/providers/xml.php');
require_once(DTS_PLUGIN_DIR.'/classes/providers/google-spreadsheet.php');
require_once(DTS_PLUGIN_DIR.'/classes/providers/post-type.php');

/**
 * Class DataSourceModel
 * @package DataSource
 */
class DataSourceModel {

    // Custom post type
    const post_type = 'dts-data-source';

    // post ID
    public $id = null;

    // post title
    public $title;

    // other post properties stored as post_meta
    private $properties = array();

    // available data providers
    private $providers = array(
        'mysql' => 'MySQL',
        'csv' => 'CSV',
        'xls' => 'XLS',
        'xml' => 'XML',
        'google-spreadsheet' => 'GoogleSpreadsheet',
        'posts' => 'PostType'
    );

    /**
     * Init new data source
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
     * Magic get method for data source properties
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
     * Init data source with information from DB
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
            foreach ($meta as $key=>$item) {
                $newKey = substr($key, 1);
                $this->properties[$newKey] = $item[0];
            }
        }
    }

    /**
     * Save data source information
     * @return int|\WP_Error
     */
    public function save()
    {
        if ( !$this->id ) {
            $postId = wp_insert_post(
                array(
                    'post_type' => self::post_type,
                    'post_status' => 'publish',
                    'post_title' => $this->title
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
                    'post_title' => $this->title
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
     * Delete data source
     * @return bool
     */
    public function delete()
    {
        if ($this->id) {
            $this->deleteChildPosts($this->id, 'dts-data-table');
            $this->deleteChildPosts($this->id, 'dts-data-chart');
            $this->deleteChildPosts($this->id, 'dts-data-map');
            $this->deleteChildPosts($this->id, 'dts-data-grid');

            if ( wp_delete_post( $this->id, true ) ) {
                $this->id = 0;

                return true;
            }
        }
        return false;
    }

    /**
     * Delete child posts
     * @param $parentId
     * @param $type
     */
    private function deleteChildPosts($parentId, $type)
    {
        $args = array(
            'post_parent' => $parentId,
            'post_type' => $type
        );

        $posts = get_posts( $args );
        if (is_array($posts) && count($posts) > 0) {
            // Delete all the Children of the Parent Page
            foreach($posts as $post){
                wp_delete_post($post->ID, true);
            }
        }
    }

    /**
     * Set model properties
     * @param array $data
     */
    public function setValues($data)
    {
        if (isset($data['id']) && $data['id']) {
            $this->id = $data['id'];
        }

        if (isset($data['title']) && $data['title']) {
            $this->title = $data['title'];
        }

        $type = null;
        if (isset($data['type']) && $data['type']) {
            $type = $this->properties['type'] = $data['type'];
        }

        $fields = array();

        if ($type == 'mysql') {
            $fields = array('mysql_table', 'mysql_query', 'mysql_custom');
            if (isset($data['mysql_custom']) && $data['mysql_custom']) {
                $fields = array_merge($fields, array('mysql_host', 'mysql_password', 'mysql_user', 'mysql_db'));
            }
        } elseif ($type == 'csv') {
            $fields = array('csv_file');
        } elseif ($type == 'xls') {
            $fields = array('xls_file');
        } elseif ($type == 'xml') {
            $fields = array('xml_file', 'xml_parent_path');
        } elseif ($type == 'google-spreadsheet') {
            $fields = array('google_spreadsheet');
        } elseif ($type == 'posts') {
            $fields = array('wordpress_post_type');
        }

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $this->properties[$field] = $data[$field];
            }
        }
    }

    /**
     * Detect and get data source provider
     * @return bool|object
     */
    public function getProvider()
    {
        if ($this->type && isset($this->providers[$this->type])) {
            $className = '\DataSource\\'. $this->providers[$this->type];
            return new $className($this->properties);
        } else {
            return false;
        }
    }

    /**
     * Get data source columns
     * @return bool
     */
    public function getColumns()
    {
        if ($provider = $this->getProvider()) {
            return $provider->getColumns();
        }
        return false;
    }

    /**
     * Get data source rows
     * @param int $limit
     * @return bool
     */
    public function getList($limit=5)
    {
        if ($provider = $this->getProvider()) {
            return $provider->getList($limit);
        }
        return false;
    }
}

?>