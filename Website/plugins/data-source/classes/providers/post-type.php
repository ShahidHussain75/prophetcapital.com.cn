<?php

namespace DataSource;

/**
 * Class PostType
 * @package DataSource
 */
class PostType {
    // Wordpress post type
    private $post_type = '';

    /**
     * Init MySQL provider
     * @param $data
     */
    public function __construct($data)
    {
        if ($data['wordpress_post_type']) {
            $this->post_type = $data['wordpress_post_type'];
        }
    }

    /**
     * Get columns
     * @return array|bool
     */
    public function getColumns()
    {
        $args = array( 'post_type' => $this->post_type, 'posts_per_page' => 20, 'post_status' => 'any' );
        $posts = get_posts( $args );

        $ids = array();
        if ( $posts ) {
            foreach ( $posts as $post ) {
                $ids[] = $post->ID;
            }
        }

        $columnsTemp = array('ID', 'post_title', 'post_date', 'post_content', 'post_excerpt', 'comment_status', 'post_name', 'post_password', 'guid', 'menu_order', 'comment_count');

        foreach ($ids as $id) {
            $fields = get_post_custom($id);
            foreach ($fields as $field=>$val) {
                $newField = 'meta_'.$field;
                if (!in_array($newField, $columnsTemp)) {
                    $columnsTemp[] = $newField;
                }
            }
        }

        $columns = array();

        foreach ($columnsTemp as $column) {
            $columns[] = array(
                'name' => $column,
                'label' => $column,
                'type' => 'text',
            );
        }

        return $columns;
    }

    /**
     * Get list of rows
     * @param int $limit
     * @return bool
     */
    public function getList($limit=5)
    {
        $args = array( 'post_type' => $this->post_type, 'posts_per_page' => $limit?$limit:-1, 'post_status' => 'any' );
        $posts = get_posts( $args );

        $results = array();

        if ($posts) {
            foreach ($posts as $post) {
                $meta = get_post_meta( $post->ID );
                $row = (array)$post;
                foreach ($meta as $key=>$value) {
                    $row['meta_'.$key] = $value[0];
                }
                $results[] = $row;
            }
        }

        return $results;
    }
}