<?php


namespace DataSource;

/**
 * Class GoogleChart
 * @package DataSource
 */
class GoogleChart {

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
     * Get values for Pie chart
     * @param $columns
     * @param string $sortBy
     * @param string $sortOrder
     * @param string $group
     * @param array $groupBy
     * @return array
     */
    public function getPieChart($columns, $sortBy='', $sortOrder='', $group='', $groupBy=array())
    {
        $this->values = array();
        if ($sortBy) {
            usort($this->list, $this->sort($sortBy, $sortOrder));
        }

        if ($group == 'all') {
            $this->list = $this->group($columns, array());
        } elseif ($group == 'fields' && count($groupBy)) {
            $this->list = $this->group($columns, $groupBy);
        }

        if ($group == 'all') {
            $item = $this->list[0];
            $column = $columns[0];
            $this->values[] = array('Title', 'Value');
            foreach ($columns as $column) {
                if ($column['function']) {
                    if ($column['function'] == 'Average') {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']]['sum'] / $item['__functions'][$column['column'].'__'.$column['function']]['count'];
                    } else {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']];
                    }
                    $row = array(
                        $column['label'],
                        round((double)$val*100)/100
                    );
                } else {
                    $row = array(
                        $column['label'],
                        round((double)$item[$column['column']]*100)/100,
                    );
                }

                $this->values[] = $row;
            }
        } else {
            $column = $columns[0];
            $this->values[] = array($column['label'], $column['column']);

            foreach ($this->list as $item) {
                if (($column['function']) && ($group == 'fields')) {
                    if ($column['function'] == 'Average') {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']]['sum'] / $item['__functions'][$column['column'].'__'.$column['function']]['count'];
                    } else {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']];
                    }
                    $row = array(
                        $item[$column['label']],
                        round((double)$val*100)/100
                    );
                } else {
                    $row = array(
                        $item[$column['label']],
                        round((double)$item[$column['column']]*100)/100,
                    );
                }

                $this->values[] = $row;
            }
        }

        return $this->values;
    }

    /**
     * Get values for Bar chart
     * @param $columns
     * @param string $sortBy
     * @param string $sortOrder
     * @param string $group
     * @param array $groupBy
     * @return array
     */
    public function getBarChart($columns, $sortBy='', $sortOrder='', $group='', $groupBy=array())
    {
        $this->values = array();
        if ($sortBy) {
            usort($this->list, $this->sort($sortBy, $sortOrder));
        }

        if (isset($columns['main'])) {
            $mainColumn = $columns['main'];
            unset($columns['main']);
            $this->values[] = array($mainColumn);
        } else {
            $mainColumn = '';
        }

        if ($group == 'all') {
            $this->list = $this->group($columns, array());
        } elseif ($group == 'fields' && count($groupBy)) {
            $this->list = $this->group($columns, $groupBy);
        }

        if ($group == 'all') {
            $item = $this->list[0];

            $this->values[0][] = '';
            $row = array(0);
            foreach ($columns as $column) {
                $this->values[0][] = $column['label'];
            }

            foreach ($columns as $column) {
                if ($column['function']) {
                    if ($column['function'] == 'Average') {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']]['sum'] / $item['__functions'][$column['column'].'__'.$column['function']]['count'];
                    } else {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']];
                    }
                    $row[] = round((double)$val*100)/100;
                } else {
                    $row[] = round((double)$val*100)/100;
                }
            }
            $this->values[] = $row;
        } else {
            foreach ($columns as $column) {
                $this->values[0][] = $column['label'];
            }

            foreach ($this->list as $item) {
                $row = array();
                if ($mainColumn) {
                    $row[] = $item[$mainColumn];
                }
                foreach ($columns as $column) {
                    if (($column['function']) && ($group == 'fields')) {
                        if ($column['function'] == 'Average') {
                            $val = $item['__functions'][$column['column'].'__'.$column['function']]['sum'] / $item['__functions'][$column['column'].'__'.$column['function']]['count'];
                        } else {
                            $val = $item['__functions'][$column['column'].'__'.$column['function']];
                        }
                        $row[] = round((double)$val*100)/100;
                    } else {
                        $val = $item[$column['column']];
                        $row[] = round((double)$val*100)/100;
                    }
                }
                $this->values[] = $row;
            }
        }

        return $this->values;
    }

    /**
     * Get values for 2D chart
     * @param $columns
     * @param string $sortBy
     * @param string $sortOrder
     * @param string $group
     * @param array $groupBy
     * @return array
     */
    public function get2DChart($columns, $sortBy='', $sortOrder='', $group='', $groupBy=array())
    {
        $this->values = array();
        unset($columns['main']);
        if ($sortBy) {
            usort($this->list, $this->sort($sortBy, $sortOrder));
        }

        if ($group == 'fields' && count($groupBy)) {
            $this->list = $this->group($columns, $groupBy);
        }

        foreach ($columns as $column) {
            $this->values[0][] = $column['column'];
        }

        foreach ($this->list as $item) {
            $row = array();
            foreach ($columns as $column) {
                if (($column['function']) && ($group == 'fields')) {
                    if ($column['function'] == 'Average') {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']]['sum'] / $item['__functions'][$column['column'].'__'.$column['function']]['count'];
                    } else {
                        $val = $item['__functions'][$column['column'].'__'.$column['function']];
                    }
                    $row[] = round((double)$val*100)/100;
                } else {
                    $val = $item[$column['column']];
                    $row[] = round((double)$val*100)/100;
                }
            }
            $this->values[] = $row;
        }
        return $this->values;
    }

    /**
     * Group dataset
     * @param $columns
     * @param $groupBy
     * @return array
     */
    private function group($columns, $groupBy)
    {
        $list = array();

        $column = $columns[0];

        $newList = array();

        $byFields = false;
        if (count($groupBy)) {
            $byFields = true;
        }

        foreach ($this->list as $item) {
            if ($byFields) {
                $key = $this->getKey($groupBy, $item);
            } else {
                $key = 0;
            }
            $newList[$key][] = $item;
        }

        foreach ($newList as $key=>$items) {
            // Aggregate
            $aggregatedItem = array();
            foreach ($items as $item) {
                if (!count($aggregatedItem)) {
                    $aggregatedItem = $item;
                    $aggregatedItem['__functions'] = array();
                }
                $functions = $aggregatedItem['__functions'];

                foreach ($columns as $column) {
                    $functionKey = $column['column'].'__'.$column['function'];
                    if (!isset($functions[$functionKey])) {
                        $functions[$functionKey] = 0;
                        if ($column['function'] == 'Minimum') {
                            $functions[$functionKey] = PHP_INT_MAX;
                        }
                    }

                    if ($column['function'] == 'Sum') {
                        $functions[$functionKey] += $item[$column['column']];
                    } elseif ($column['function'] == 'Average') {
                        if (!isset($functions[$functionKey]['sum'])) {
                            $functions[$functionKey] = array(
                                'sum'=>0,
                                'count'=>0,
                            );
                        }
                        $functions[$functionKey]['sum'] += $item[$column['column']];
                        $functions[$functionKey]['count']++;
                    } elseif ($column['function'] == 'Count') {
                        $functions[$functionKey]++;
                    } elseif ($column['function'] == 'Minimum') {
                        $functions[$functionKey] = min($functions[$functionKey], $item[$column['column']]);
                    } elseif ($column['function'] == 'Maximum') {
                        $functions[$functionKey] = max($functions[$functionKey], $item[$column['column']]);
                    }
                }
                $aggregatedItem['__functions'] = $functions;
            }
            $list[] = $aggregatedItem;
        }

        return $list;
    }

    /**
     * Generate key for dataset
     * @param $groupBy
     * @param $item
     * @return string
     */
    private function getKey($groupBy, $item) {
        $key = '';
        foreach ($groupBy as $group) {
            $key .= $item[$group].'-';
        }
        return md5($key);
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