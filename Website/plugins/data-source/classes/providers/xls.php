<?php

namespace DataSource;

require_once(DTS_PLUGIN_DIR.'/classes/libs/PHPExcel/Classes/PHPExcel.php');
require_once(DTS_PLUGIN_DIR.'/../../../wp-admin/includes/file.php');

/**
 * Class XLS
 * @package DataSource
 */
class XLS {

    // Columns
    private $columns = array();

    // Data
    private $data = array();

    /**
     * Init Excel provider with file name
     * @param $data
     */
    public function __construct($data)
    {
        global $wpdb;
        if (isset($data['xls_file'])) {
            @ini_set('memory_limit','2048M');
            if ($reader = $this->getReader($data['xls_file'])) {
                $reader->setReadDataOnly(true);

                $attachmentId = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid=%s", $data['xls_file'] ) );
                if ($attachmentId) {
                    $filename = get_attached_file($attachmentId);
                } else {
                    $filename = download_url($data['xls_file']);
                }

                $phpExcel = $reader->load($filename);

                $objWorksheet = $phpExcel->setActiveSheetIndex(0);

                $this->data = array();
                $this->header = array();
                $i=0;
                $header = false;
                foreach ($objWorksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(true);
                    foreach ($cellIterator as $cell) {
                        if ($header) {
                            $this->data[$i][] = $cell->getValue();
                        } else {
                            $this->columns[] = $cell->getValue();
                        }
                    }
                    $header = true;
                    $i++;
                }

                // Remove temp files only!
                if (!$attachmentId) {
                    @unlink($filename);
                }
            }
        }
    }

    /**
     * Get XLS file reader
     * @param $file
     * @return bool|\PHPExcel_Reader_Excel2007|\PHPExcel_Reader_Excel5|\PHPExcel_Reader_OOCalc
     */
    private function getReader($file)
    {
        $parts = explode('.', strtolower($file));
        $ext = $parts[count($parts)-1];

        if ($ext == 'xls') {
            return new \PHPExcel_Reader_Excel5();
        } elseif ($ext == 'xlsx') {
            return new \PHPExcel_Reader_Excel2007();
        } elseif ($ext == 'ods') {
            return new \PHPExcel_Reader_OOCalc();
        }

        return false;
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
     * Get list
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