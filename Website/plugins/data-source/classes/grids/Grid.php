<?php

namespace DataSource;

/**
 * Class Grid
 * @package DataSource
 */
class Grid {

    private $dataSource = null;
    private $values = array();
    private $list = array();
    private $columns = array();

    /**
     * GoogleCharts constructor
     * @param null $dataSource
     */
    public function __construct($dataSource=null)
    {
        if ($dataSource) {
            $this->dataSource = $dataSource;

            $this->list = $dataSource->getList(null);
            $this->columns = $dataSource->getColumns();
        }
    }

    /**
     * Get values for Data Grid
     * @param $columns
     * @param string $sortBy
     * @param string $sortOrder
     * @return array
     */
    public function getGrid($template, $sortBy='', $sortOrder='')
    {
        $this->values = array();
        if ($sortBy) {
            usort($this->list, $this->sort($sortBy, $sortOrder));
        }

        $placeholders = array();
        foreach ($this->columns as $column) {
            $placeholders[] = '%'.$column['name'].'%';
        }

        foreach ($this->list as $item) {
            $itemValues = $this->getValues($item);
            $row = str_replace($placeholders, $itemValues, $template);
            $this->values[] = $row;
        }

        return $this->values;
    }

    /**
     * Build array with item values list
     * @param $columns
     * @param $item
     * @return array
     */
    private function getValues($item)
    {
        $values = array();
        foreach ($this->columns as $column) {
            $values[] = isset($item[$column['name']])?$item[$column['name']]:'';
        }

        return $values;
    }

    /**
     * Sort dataset
     * @param $sortBy
     * @param $sortOrder
     * @return callable
     */
    private function sort($sortBy, $sortOrder)
    {
        if ($sortOrder == 'asc') {
            return function ($a, $b) use ($sortBy) {
                return strnatcmp($a[$sortBy], $b[$sortBy]);
            };
        } else {
            return function ($a, $b) use ($sortBy) {
                return strnatcmp($b[$sortBy], $a[$sortBy]);
            };
        }
    }
}