<?php

namespace DataSource;

/**
 * Class MySQL
 * @package DataSource
 */
class MySQL {

    // MySQL query
    private $query = '';

    // MySQL table name
    private $table = '';

    // MySQL connection object
    private $wpdb = null;

    /**
     * Init MySQL provider
     * @param $data
     */
    public function __construct($data)
    {
        if (isset($data['mysql_custom']) && $data['mysql_custom']) {
            $this->wpdb = new \wpdb( $data['mysql_user'], isset($data['mysql_password'])?$data['mysql_password']:'', $data['mysql_db'], $data['mysql_host'] );
        } else {
            global $wpdb;
            $this->wpdb = $wpdb;
        }

        if (isset($data['mysql_table']) && $data['mysql_table']!=-1) {
            $this->table = $data['mysql_table'];
        } elseif (isset($data['mysql_query']) && $data['mysql_query']) {
            $this->query = stripslashes($data['mysql_query']);
        }
    }

    /**
     * Get columns
     * @return array|bool
     */
    public function getColumns()
    {
        if ($this->table) {
            $list = $this->wpdb->get_results( 'SHOW COLUMNS FROM `'.$this->table.'`', ARRAY_A );
            $columns = array();

            foreach ($list as $column) {
                $columns[] = array(
                    'name' => $column['Field'],
                    'label' => $column['Field'],
                    'type' => $this->convertType($column['Type']),
                );
            }

            return $columns;
        } elseif ($this->query) {
            $this->wpdb->check_safe_collation = false;

            // Check if there are more than one query
            $subQueries = array_filter(explode(';', $this->query));
            $mainQuery = $subQueries[count($subQueries)-1];
            unset($subQueries[count($subQueries)-1]);

            if (count($subQueries)) {
                foreach ($subQueries as $subQuery) {
                    $res = $this->wpdb->get_results( $subQuery, ARRAY_A );
                }
            }

            $query = 'SELECT * FROM ('.$mainQuery.') as q LIMIT 0, 1';

            if (strpos(strtolower($mainQuery), 'execute') !== false) {
                $query = $mainQuery;
            }

            $results = $this->wpdb->get_results( $query, ARRAY_A );

            if (!$results && !count($results)) {
                return false;
            }

            $columns = array();
            foreach ($results[0] as $column=>$val) {
                $columns[] = array(
                    'name' => $column,
                    'label' => $column,
                    'type' => 'text'
                );
            }

            return $columns;
        }

        return false;
    }

    /**
     * Convert column types
     * @param $type
     * @return string
     */
    private function convertType($type)
    {
        // Available types: Number, Image, URL, Text, Date
        $newType = 'text';

        $numbers = array('int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'float', 'decimal', 'double', 'real');
        $dates = array('date', 'datetime', 'timestamp');

        $firstBracket = strpos($type, '(');
        $firstSpace = strpos($type, ' ');
        $lastChar = strlen($type);

        if ($firstBracket === false) $firstBracket = 1000;
        if ($firstSpace === false) $firstSpace = 1000;

        $type = strtolower(substr($type, 0, min($firstBracket, $firstSpace, $lastChar)));

        if (in_array($type, $numbers)) {
            $newType = 'number';
        } elseif (in_array($type, $dates)) {
            $newType = 'date';
        }

        return $newType;
    }

    /**
     * Get list of rows
     * @param int $limit
     * @return bool
     */
    public function getList($limit=5)
    {
        $query = '';
        if ($this->table) {
            $query = 'SELECT * FROM `'.$this->table.'`';
            if ($limit) {
                $query .= ' LIMIT 0, '.$limit;
            }
        } elseif ($this->query) {
            // Check if there are more than one query
            $subQueries = array_filter(explode(';', $this->query));
            $mainQuery = $subQueries[count($subQueries)-1];
            unset($subQueries[count($subQueries)-1]);

            if (count($subQueries)) {

                foreach ($subQueries as $subQuery) {
                    $res = $this->wpdb->get_results( $subQuery, ARRAY_A );
                }
            }

            $query = 'SELECT * FROM ('.$mainQuery.') as q';
            if ($limit) {
                $query .=  ' LIMIT 0, '.$limit;
            }

            if (strpos(strtolower($mainQuery), 'execute') !== false) {
                $query = $mainQuery;
            }
        }

        if ($query) {
            $list = $this->wpdb->get_results( $query, ARRAY_A );
            return $list;
        }

        return false;
    }
}