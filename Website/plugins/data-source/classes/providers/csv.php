<?php

namespace DataSource;

/**
 * Class CSV
 * @package DataSource
 */
class CSV {

    // File name
    private $file = '';

    // Columns
    private $columns = array();

    // Data
    private $data = array();

    // File contents
    private $content = '';

    // CSV control
    private $control = array(
        'delimiter' => ',',
        'enclosure' => '"'
    );

    /**
     * Init CSV provider with file URL
     * @param $data
     */
    public function __construct($data)
    {
        if (isset($data['csv_file'])) {
            @ini_set('memory_limit','2048M');
            $this->file = $data['csv_file'];
            $this->content = $this->downloadData();
            $this->detectControl();
            $this->parseFile();
        }
    }

    /**
     * Detect CSV control type
     */
    private function detectControl()
    {
        ini_set("auto_detect_line_endings", true);
        $controls = array(
            array(',', '"'),
            array(';', '"'),
            array(',', '\''),
            array(';', '\''),
        );

        foreach ($controls as $control) {
            // Because of Mac OS issue with str_getcsv() function
            $handle = fopen('data://text/plain;base64,' . base64_encode($this->content), 'r');

            while (($res = fgetcsv($handle, 1000, $control[0], $control[1])) !== FALSE) {
                $num = 0;
                if (count($res) > 1) {
                    if (!$num) {
                        $num = count($res);
                    } else {
                        if ($num != count($res)) {
                            $num = 0;
                            break;
                        }
                    }
                } else {
                    $num = 0;
                    break;
                }
            }

            if ($num) {
                $this->control['delimiter'] = $control[0];
                $this->control['enclosure'] = $control[1];
                fclose($handle);
                return;
            }

            fclose($handle);
        }

        ini_set('auto_detect_line_endings', false);

        return;
    }

    /**
     * Parse CSV file
     */
    private function parseFile()
    {
        $this->data = array();
        $this->columns = array();

        @ini_set("auto_detect_line_endings", true);
        $header = false;
        $handle = fopen('data://text/plain;base64,' . base64_encode($this->content), 'r');

        while (($row = fgetcsv($handle, 1000, $this->control['delimiter'], $this->control['enclosure'])) !== FALSE) {
            if ($header) {
                $this->data[] = $row;
            } else {
                $this->columns = $row;
                $header = true;
            }
        }

        fclose($handle);
        @ini_set('auto_detect_line_endings', false);
    }

    /**
     * Download file
     * @return bool|mixed
     */
    private function downloadData()
    {
        if (!$this->file) {
            return false;
        }

        $http = \DataSource\Downloader::getInstance();
        return $http->download($this->file);
    }

    /**
     * Get columns
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
            $j = 0;
            foreach ($this->columns as $column) {
                $newRow[$column] = $row[$j];
                $j++;
            }
            $results[] = $newRow;
            $i++;
            if ($limit && ($i>=$limit)) break;
        }

        return $results;
    }
}