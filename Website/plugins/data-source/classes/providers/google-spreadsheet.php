<?php

namespace DataSource;

/**
 * Class GoogleSpreadsheet
 * @package DataSource
 */
class GoogleSpreadsheet {

    // Spreadsheet url
    private $url = '';

    // file contents
    private $content = '';

    // columns
    private $columns = array();

    // file data
    private $data = array();

    /**
     * Init GoogleSpreadsheet provider with URL
     * @param $data
     */
    public function __construct($data)
    {
        if (isset($data['google_spreadsheet'])) {
            $this->url = $this->convertURL($data['google_spreadsheet']);
            if (!$this->url) return;
            $this->content = json_decode($this->downloadData());
            $this->parseFile();
        }
    }

    /**
     * Convert public URL to one available with JSON format
     * @param $url
     * @return string
     */
    private function convertURL($url)
    {
        $parts = parse_url($url);
        $path = explode('/', $parts['path']);
        if (count($path) > 4) {
            $url = 'https://spreadsheets.google.com/feeds/list/'.$path[3].'/default/public/values?alt=json';
            if (isset($parts['query']) && $parts['query']) {
                $url .= '&'.$parts['query'];
            }
            return $url;
        } else {
            return false;
        }
    }

    /**
     * Parse data
     */
    private function parseFile()
    {
        $this->data = array();
        $this->columns = array();
        foreach ($this->content->feed->entry as $entry) {
            $row = array();
            foreach ($entry as $cell=>$val) {
                if ($cellName = $this->filterCell($cell)) {
                    if (!isset($this->columns[$cellName])) {
                        $this->columns[$cellName] = $cellName;
                    }
                    $row[$cellName] = $val->{'$t'};
                }
            }
            if (count($row)) {
                $this->data[] = $row;
            }
        }
    }

    /**
     * Filter GoogleSpreadsheet cell names
     * @param $cell
     * @return bool|mixed
     */
    private function filterCell($cell)
    {
        if (strpos($cell, 'gsx$') !== false) {
            return str_replace('gsx$', '', $cell);
        }
        return false;
    }

    /**
     * Download file
     * @return bool|mixed
     */
    private function downloadData()
    {
        if (!$this->url) {
            return false;
        }

        $http = \DataSource\Downloader::getInstance();
        return $http->download($this->url);
    }

    /**
     * Get column names
     * @return array|bool
     */
    public function getColumns()
    {
        if (count($this->columns)) {
            $columns = array();

            foreach ($this->columns as $column) {
                $columns[] = array(
                    'name' => $column,
                    'label' => $column,
                    'type' => 'text',
                );
            }

            return $columns;
        }
        return false;
    }

    /**
     * Get rows
     * @param int $limit
     * @return array
     */
    public function getList($limit=5)
    {
        $results = array();
        $i = 0;
        foreach ($this->data as $row) {
            $newRow = array();
            foreach ($this->columns as $column) {
                $newRow[$column] = isset($row[$column])?$row[$column]:'';
            }
            $results[] = $newRow;
            $i++;
            if ($limit && ($i>=$limit)) break;
        }

        return $results;
    }
}