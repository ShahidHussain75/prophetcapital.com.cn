<?php


namespace DataSource;

/**
 * Class Map
 * @package DataSource
 */
class Map {

    private $dataSource = null;
    private $values = array();
    private $list = array();
    private $columns = array();

    /**
     * Map constructor
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
     * Return GeoChartMap data
     * @param $columns
     * @param $color
     * @param $size
     * @param string $group
     * @param array $groupBy
     * @return array
     */
    public function getGeoChart($addressType, $columns, $color, $size, $group='', $groupBy=array())
    {
        $this->values = array();

        if ($group == 'fields' && count($groupBy)) {
            $newColumns = array();

            if ($color['field']) {
                $newColumns[] = array(
                    'column' => $color['field'],
                    'function' => $color['function'],
                );
            }

            if ($size['field']) {
                $newColumns[] = array(
                    'column' => $size['field'],
                    'function' => $size['function'],
                );
            }
            $this->list = $this->group($newColumns, $groupBy);
        }

        if ($addressType == 'latlng') {
            $this->values[] = array('Latitude', 'Longitude');
        } else {
            $this->values[] = array('Location');
        }

        if ($color['field']) {
            if ($color['label']) {
                $this->values[0][] = $color['label'];
            } else {
                $this->values[0][] = $color['field'];
            }
        }

        if ($size['field']) {
            if ($size['label']) {
                $this->values[0][] = $size['label'];
            } else {
                $this->values[0][] = $size['field'];
            }
        }

        foreach ($this->list as $item) {
            if ($group == 'fields') {
                $row = array();
                if ($addressType == 'latlng') {
                    $row[] = (double)$item[$columns['latitude']];
                    $row[] = (double)$item[$columns['longitude']];
                } else {
                    $address = array();
                    foreach ($columns as $column) {
                        if ($column) {
                            $address[] = $item[$column];
                        }
                    }
                    $row[] = implode(',', $address);
                }

                if ($color['field']) {
                    if ($color['function'] == 'Average') {
                        $val = $item['__functions'][$color['field'].'__'.$color['function']]['sum'] / $item['__functions'][$color['field'].'__'.$color['function']]['count'];
                    } else {
                        $val = $item['__functions'][$color['field'].'__'.$color['function']];
                    }
                    $row[] = round((double)$val*100)/100;
                }

                if ($size['field']) {
                    if ($size['function'] == 'Average') {
                        $sizeVal = $item['__functions'][$size['field'].'__'.$size['function']]['sum'] / $item['__functions'][$size['field'].'__'.$size['function']]['count'];
                    } else {
                        $sizeVal = $item['__functions'][$size['field'].'__'.$size['function']];
                    }
                    $row[] = round((double)$sizeVal*100)/100;
                }
            } else {
                $row = array();

                if ($addressType == 'latlng') {
                    $row[] = (double)$item[$columns['latitude']];
                    $row[] = (double)$item[$columns['longitude']];
                } else {
                    $address = array();
                    foreach ($columns as $column) {
                        if ($column) {
                            $address[] = $item[$column];
                        }
                    }
                    $row[] = implode(',', $address);
                }


                if ($color['field']) {
                    $row[] = round((double)$item[$color['field']]*100)/100;
                }

                if ($size['field']) {
                    $row[] = round((double)$item[$size['field']]*100)/100;
                }
            }

            $this->values[] = $row;
        }

        return $this->values;
    }

    /**
     * Return GoogleMap data
     * @param $columns
     * @param string $group
     * @param array $groupBy
     * @return array
     */
    public function getGoogleMap($addressType, $columns, $description, $group='', $groupBy=array())
    {
        $this->values = array();

        if ($group == 'fields' && count($groupBy)) {
            $newColumns = array();

            if ($description['field']) {
                $newColumns[] = array(
                    'column' => $description['field'],
                    'function' => $description['function'],
                );
            }
            $this->list = $this->group($newColumns, $groupBy);
        }

        if ($addressType == 'latlng') {
            $this->values[] = array('Latitude', 'Longitude');
        } else {
            $this->values[] = array('Location');
        }

        if ($description['field']) {
            if ($description['label']) {
                $this->values[0][] = $description['label'];
            } else {
                $this->values[0][] = $description['field'];
            }
        }

        foreach ($this->list as $item) {
            if ($group == 'fields') {
                $row = array();
                if ($addressType == 'latlng') {
                    $row[] = (double)$item[$columns['latitude']];
                    $row[] = (double)$item[$columns['longitude']];
                } else {
                    $address = array();
                    foreach ($columns as $column) {
                        if ($column) {
                            $address[] = $item[$column];
                        }
                    }
                    $row[] = implode(',', $address);
                }

                if ($description['field']) {
                    if ($description['function'] == 'Average') {
                        $val = $item['__functions'][$description['field'].'__'.$description['function']]['sum'] / $item['__functions'][$description['field'].'__'.$description['function']]['count'];
                    } else {
                        $val = $item['__functions'][$description['field'].'__'.$description['function']];
                    }

                    if ($description['label']) {
                        $row[] = $description['label'] .': '. $val;
                    } else {
                        $row[] = $val;
                    }
                }
            } else {
                $row = array();

                if ($addressType == 'latlng') {
                    $row[] = (double)$item[$columns['latitude']];
                    $row[] = (double)$item[$columns['longitude']];
                } else {
                    $address = array();
                    foreach ($columns as $column) {
                        if ($column) {
                            $address[] = $item[$column];
                        }
                    }
                    $row[] = implode(',', $address);
                }

                if ($description['field']) {
                    if ($description['label']) {
                        $row[] = $description['label'] .': '. $item[$description['field']];
                    } else {
                        $row[] = $item[$description['field']];
                    }
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
                            $functions[$functionKey] = array();
                        }
                        $functions[$functionKey]['sum'] += $item[$column['column']];
                        $functions[$functionKey]['count']++;
                    } elseif ($column['function'] == 'Count') {
                        $functions[$functionKey]++;
                    } elseif ($column['function'] == 'Minimum') {
                        $functions[$functionKey] = min($functions[$functionKey], $item[$column['column']]);
                    } elseif ($column['function'] == 'Maximum') {
                        $functions[$functionKey] = max($functions[$functionKey], $item[$column['column']]);
                    } else {
                        $functions[$functionKey] = $item[$column['column']];
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
}