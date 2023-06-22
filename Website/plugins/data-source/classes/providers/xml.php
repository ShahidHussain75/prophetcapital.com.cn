<?php

namespace DataSource;

/**
 * Class XML
 * @package DataSource
 */
class XML {

    // XML file url
    private $url = '';

    // Items parent path
    private $parentPath = 0;

    // XML file contents
    private $contentXML = '';

    // XML file as array
    private $contentArray = array();

    // columns
    private $columns = array();

    // file data
    private $data = array();

    /**
     * Init XML provider with URL
     * @param $data
     */
    public function __construct($data)
    {
        if (isset($data['xml_file'])) {
            $this->url = $data['xml_file'];
            if (!$this->url) return;
            $this->contentXML = $this->downloadData();
            $content = @simplexml_load_string($this->contentXML, 'SimpleXMLElement', LIBXML_NOCDATA);
            if (!$content) return;
            $this->convertXmlObjToArr($content, $result);
            $this->contentArray = $result;

            if (isset($data['xml_parent_path'])) {
                $this->parentPath = $data['xml_parent_path'];
            }

            $this->parseFile();
        }
    }

    /**
     * Convert XML object to PHP array
     * @param $obj
     * @param $arr
     */
    private function convertXmlObjToArr($obj, &$arr)
    {
        if (!is_object($obj)) return;
        $children = $obj->children();
        foreach ($children as $elementName => $node)
        {
            $nextIdx = count($arr);
            $arr[$nextIdx] = array();
            $arr[$nextIdx]['@name'] = strtolower((string)$elementName);
            $arr[$nextIdx]['@attributes'] = array();
            $attributes = $node->attributes();
            foreach ($attributes as $attributeName => $attributeValue)
            {
                $attribName = strtolower(trim((string)$attributeName));
                $attribVal = trim((string)$attributeValue);
                $arr[$nextIdx]['@attributes'][$attribName] = $attribVal;
            }

            $text = (string)$node;
            $text = trim($text);
            if (strlen($text) > 0)
            {
                $arr[$nextIdx]['@text'] = $text;
            }
            $arr[$nextIdx]['@children'] = array();
            $this->convertXmlObjToArr($node, $arr[$nextIdx]['@children']);
        }

        return;
    }

    /**
     * Build XML structure
     * @param $xmlArray
     * @param $original
     * @return mixed
     */
    private function buildStructure($xmlArray, $original)
    {
        $structure = $original;
        foreach ($xmlArray as $item) {
            if (!isset($structure[$item['@name']])) {
                $structure[$item['@name']] = array();
            }
            $structure[$item['@name']] = $this->buildStructure($item['@children'], $structure[$item['@name']]);
        }
        return $structure;
    }

    /**
     * Build XML structure as nested list
     * @param $structure
     * @param int $depth
     * @return array
     */
    private function buildStructureList($structure, $depth = 0, $kprefix='')
    {
        $list = array();
        foreach ($structure as $tag => $children)
        {
            $list[$kprefix.$tag] = $tag;
            $items = $this->buildStructureList($children, $depth+1, $tag.'/');
            $prefix = str_repeat('-', $depth+1);
            foreach ($items as $k=>$item)
            {
                $list[$kprefix.$k] = $prefix.$item;
            }
        }
        return $list;
    }

    /**
     * Get XML file structure as list
     * @return array
     */
    public function getFileStructure()
    {
        $structure = array();
        $structure = $this->buildStructure($this->contentArray, $structure);

        $list = $this->buildStructureList($structure);

        return $list;
    }

    private function filterNodes($name, &$array)
    {
        $list = array();
        foreach ($array as $item) {
            if ($item['@name'] == $name) {
                $list[] = $item;
            }
        }

        return $list;
    }

    /**
     * Get item nodes
     * @param $path
     * @param $array
     * @return mixed
     */
    private function getItemNodes($path, &$array)
    {
        if (!$path) return $array;

        $parts = explode('/', $path);
        foreach ($array as $item) {
            if ($item['@name'] == $parts[0]) {
                if (count($parts) > 1) {
                    unset($parts[0]);
                    return $this->getItemNodes(implode('/', $parts), $item['@children']);
                } else {
                    return $this->filterNodes($item['@name'], $array);
                    //return $item['@children'];
                }
            }
        }
    }

    /**
     * Parse columns and rows
     * @param $item
     * @param string $prefix
     * @param $row
     */
    private function parseColumns(&$item, $prefix = '', &$row)
    {
        $prefix .= $item['@name'];

        foreach ($item['@attributes'] as $attr => $val) {
            $cellName = $prefix. '.'.$attr;
            if (!isset($this->columns[$cellName])) {
                $this->columns[$cellName] = $cellName;
            }
            $row[$cellName] = $val;
        }

        if (isset($item['@text'])) {
            $cellTextName = $prefix. '.text';
            if (!isset($this->columns[$cellTextName])) {
                $this->columns[$cellTextName] = $cellTextName;
            }
            $row[$cellTextName] = $item['@text'];
        }

        foreach ($item['@children'] as $child) {
            $this->parseColumns($child, $prefix.'.', $row);
        }
    }

    /**
     * Parse data
     */
    private function parseFile()
    {
        $this->data = array();
        $this->columns = array();

        $list = $this->getItemNodes($this->parentPath, $this->contentArray);

        if (is_array($list)) {
            foreach ($list as $item) {
                $row = array();
                $this->parseColumns($item, '', $row);
                if (count($row)) {
                    $this->data[] = $row;
                }
            }
        }
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