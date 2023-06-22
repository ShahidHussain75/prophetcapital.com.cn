<?php

namespace DataSource;

require_once(DTS_PLUGIN_DIR.'/classes/models/data-source.php');
require_once(DTS_PLUGIN_DIR.'/classes/models/data-map.php');
require_once(DTS_PLUGIN_DIR.'/classes/maps/Map.php');
require_once(DTS_PLUGIN_DIR.'/classes/validate-form.php');
require_once(DTS_PLUGIN_DIR.'/classes/posts-grid.php');

/**
 * Data sources controller
 */

class Maps {
    /**
     * Access to bootstrap object
     */
    private $bootstrap = null;

    public function __construct($bootstrap=null)
    {
        $this->bootstrap = $bootstrap;

        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            if ( is_admin() && method_exists($screen, 'add_help_tab')) {
                $screen->add_help_tab(array(
                    'id' => 'dts-maps-help',
                    'title' => __('Getting started', 'data-source'),
                    'content' =>
                        '<p>' . __('You can see list of existing maps on index page. In order to put any of them to your website just copy short code and paste it in page or post editor.', 'data-source') . '</p>' .
                        '<p>' . __('This section allows you to create and edit maps using your data sources. You can configure your map and it\'s look in Map configuration box', 'data-source') . '</p>' .
                        '<p>' . __('For more details check <a href="' . dts_plugin_url('DataSource - User Manual.pdf') . '">User Manual</a>', 'data-source') . '</p>'
                ));
            }
        }
    }

    /**
     * List of maps
     * @return array
     */
    public function index()
    {
        $grid = new \DataSource\PostsGrid('dts-data-map');

        return array(
            'grid' => $grid
        );
    }

    /**
     * Create new map
     * @return array
     */
    function add()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-map' );

            $form = $this->getMapFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataMap = new \DataSource\DataMapModel($data);
                $dataMap->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-maps');
                $this->sendAjaxRespone(true, null, null, $redirectUrl);
            }
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'data-sources-spectrum',
            dts_plugin_url('admin/js/spectrum/spectrum.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'data-sources-maps',
            dts_plugin_url('admin/js/maps.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'google-maps-js-api',
            'https://www.google.com/jsapi',
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'worldRegions' => $this->getWorldRegions(),
            'dataSources' => $dataSources['list']
        );
    }

    /**
     * Edit map
     * @return array
     */
    function edit()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        $post = $request->getRequest('post');

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-map' );

            $form = $this->getMapFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataMap = new \DataSource\DataMapModel($post);
                $dataMap->setValues($data);
                $dataMap->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-maps');
                $this->sendAjaxRespone(true, null, null, $redirectUrl);
            }
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'data-sources-spectrum',
            dts_plugin_url('admin/js/spectrum/spectrum.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'data-sources-maps',
            dts_plugin_url('admin/js/maps.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'google-maps-js-api',
            'https://www.google.com/jsapi',
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $dataMap = new \DataSource\DataMapModel($post);

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'worldRegions' => $this->getWorldRegions(),
            'dataSources' => $dataSources['list'],
            'dataMap' => $dataMap
        );
    }

    /**
     * Delete map
     */
    public function delete()
    {
        $request = $this->bootstrap->getRequest();
        $post = $request->getRequest('post');

        if ($post) {
            $posts = array();
            if (is_array($post)) {
                $posts = $post;
            } else {
                $posts = array($post);
            }

            foreach ($posts as $postId) {
                $dataMap = new \DataSource\DataMapModel(array('id'=>$postId));
                $dataMap->delete();
            }

            $redirectUrl = $this->bootstrap->menuUrl('dts-maps');
            wp_safe_redirect($redirectUrl);
            exit();
        } else {
            wp_die( __( 'Error deleting', 'data-source' ) );
        }
    }

    /**
     * Load available columns from selected data source
     */
    public function getColumns()
    {
        $request = $this->bootstrap->getRequest();
        $dataSourceId = $request->getRequest('datasource');

        if (!$dataSourceId) {
            exit(__('Columns not found', 'data-source'));
        }

        $dataSource = new \DataSource\DataSourceModel($dataSourceId);
        $columns = $dataSource->getColumns();

        $this->sendAjaxRespone(true, array(), $columns);
        exit;
    }

    /**
     * Load map preview
     */
    public function preview()
    {
        $request = $this->bootstrap->getRequest();
        $dataSourceId = $request->getPost('datasource');
        $data = $request->getPost();

        if (!$dataSourceId) {
            exit(__('Unable to render table', 'data-source'));
        }

        $dataSource = new \DataSource\DataSourceModel($dataSourceId);

        $map = new \DataSource\Map($dataSource);
        $options = array();

        // Set columns
        $columns = array();
        if ($data['address_type'] == 'latlng') {
            $columns['latitude'] = $data['latitude'];
            $columns['longitude'] = $data['longitude'];
        } else {
            $columns['country'] = $data['country'];
            $columns['state'] = $data['state'];
            $columns['city'] = $data['city'];
            $columns['address'] = $data['address'];
        }

        if (!isset($data['groupby'])) {
            $data['groupby'] = array();
        }

        $mapType = $data['map_name'];
        if ($data['map_name'] == 'geochart') {

            $options['colorAxis'] = array();
            // Colors
            if ($data['color']['min'] && $data['color']['max']) {
                $options['colorAxis'] = array('colors'=>array($data['color']['min'], $data['color']['max']));
            }

            // Color min and max values
            if ($data['color']['min_value']) {
                $options['colorAxis']['minValue'] = $data['color']['min_value'];
            }

            if ($data['color']['max_value']) {
                $options['colorAxis']['maxValue'] = $data['color']['max_value'];
            }

            // Size min and max values
            if ($data['size']['min_value']) {
                $options['sizeAxis']['minValue'] = $data['size']['min_value'];
            }

            if ($data['size']['max_value']) {
                $options['sizeAxis']['maxValue'] = $data['size']['max_value'];
            }

            // Markers or regions?
            if ($data['geochart_type']) {
                $options['displayMode'] = $data['geochart_type'];
            }

            // Display specific region
            if ($data['geochart_region']) {
                $options['region'] = $data['geochart_region'];
            }

            $options['title'] = $data['title'];
            $values = $map->getGeoChart($data['address_type'], $columns, $data['color'], $data['size'], $data['group'], $data['groupby']);
        } elseif ($data['map_name'] == 'googlemap') {
            $options['showTip'] = true;

            // enableScrollWheel
            if (isset($data['scroll_wheel']) && $data['scroll_wheel']) {
                $options['enableScrollWheel'] = true;
            } else {
                $options['enableScrollWheel'] = false;
            }

            // mapType
            if ($data['map_type']) {
                $options['mapType'] = $data['map_type'];
            }

            // useMapTypeControl
            if (isset($data['map_selector']) && $data['map_selector']) {
                $options['useMapTypeControl'] = true;
            } else {
                $options['useMapTypeControl'] = false;
            }

            // zoomLevel
            if ($data['zoom_level']) {
                $options['zoomLevel'] = $data['zoom_level'];
            }

            $values = $map->getGoogleMap($data['address_type'], $columns, $data['description'], $data['group'], $data['groupby']);
        }

        $vars = array(
            'values' => $values,
            'map' => $mapType,
            'options' => $options
        );

        $this->bootstrap->loadTemplate($vars);
        exit;
    }

    /**
     * Render map for public side
     * @param $id
     */
    public function renderMap($id)
    {
        $dataMap = new \DataSource\DataMapModel($id);

        $dataSource = new \DataSource\DataSourceModel($dataMap->datasource);
        $map = new \DataSource\Map($dataSource);
        $options = array();

        // Set columns
        $columns = array();
        if ($dataMap->address_type == 'latlng') {
            $columns['latitude'] = $dataMap->latitude;
            $columns['longitude'] = $dataMap->longitude;
        } else {
            $columns['country'] = $dataMap->country;
            $columns['state'] = $dataMap->state;
            $columns['city'] = $dataMap->city;
            $columns['address'] = $dataMap->address;
        }

        $mapType = $dataMap->map_name;
        if ($dataMap->map_name == 'geochart') {

            $options['colorAxis'] = array();
            // Colors
            if ($dataMap->color['min'] && $dataMap->color['max']) {
                $options['colorAxis'] = array('colors'=>array($dataMap->color['min'], $dataMap->color['max']));
            }

            // Color min and max values
            if ($dataMap->color['min_value']) {
                $options['colorAxis']['minValue'] = $dataMap->color['min_value'];
            }

            if ($dataMap->color['max_value']) {
                $options['colorAxis']['maxValue'] = $dataMap->color['max_value'];
            }

            // Size min and max values
            if ($dataMap->size['min_value']) {
                $options['sizeAxis']['minValue'] = $dataMap->size['min_value'];
            }

            if ($dataMap->size['max_value']) {
                $options['sizeAxis']['maxValue'] = $dataMap->size['max_value'];
            }

            // Markers or regions?
            if ($dataMap->geochart_type) {
                $options['displayMode'] = $dataMap->geochart_type;
            }

            // Display specific region
            if ($dataMap->geochart_region) {
                $options['region'] = $dataMap->geochart_region;
            }

            $options['title'] = $dataMap->title;
            $values = $map->getGeoChart($dataMap->address_type, $columns, $dataMap->color, $dataMap->size, $dataMap->group, $dataMap->groupby);
        } elseif ($dataMap->map_name == 'googlemap') {
            $options['showTip'] = true;

            // enableScrollWheel
            if ($dataMap->scroll_wheel) {
                $options['enableScrollWheel'] = true;
            } else {
                $options['enableScrollWheel'] = false;
            }

            // mapType
            if ($dataMap->map_type) {
                $options['mapType'] = $dataMap->map_type;
            }

            // useMapTypeControl
            if ($dataMap->map_selector) {
                $options['useMapTypeControl'] = true;
            } else {
                $options['useMapTypeControl'] = false;
            }

            // zoomLevel
            if ($dataMap->zoom_level) {
                $options['zoomLevel'] = $dataMap->zoom_level;
            }

            $values = $map->getGoogleMap($dataMap->address_type, $columns, $dataMap->description, $dataMap->group, $dataMap->groupby);
        }

        $vars = array(
            'values' => $values,
            'map' => $mapType,
            'options' => $options,
            'mapData' => $dataMap
        );

        wp_enqueue_script("jquery");

        $this->bootstrap->loadTemplate($vars, 'dts-maps', 'renderMap');
        return $dataMap;
    }

    /**
     * Validate data map form
     * @param $data
     * @return ValidateForm
     */
    private function getMapFormValidator($data)
    {
        $form = new \DataSource\ValidateForm();
        $form->setData($data);

        $form->addField(
            'title',
            __('Title', 'data-source'),
            array('required')
        );

        $form->addField(
            'datasource',
            __('Data source', 'data-source'),
            array('required')
        );

        return $form;
    }

    /**
     * Sends ajax response
     * @param $status
     * @param array $errors
     * @param array $data
     * @param string $redirectUrl
     */
    private function sendAjaxRespone($status, $errors=array(), $data=array(), $redirectUrl = '')
    {
        $response = array (
            'status' => $status
        );

        if ($errors && count($errors)) {
            $response['errors'] = $errors;
        }

        if ($data && count($data)) {
            $response['data'] = $data;
        }

        if ($redirectUrl) {
            $response['redirect_url'] = $redirectUrl;
        }

        echo dts_json_encode($response);

        exit;
    }

    /**
     * Get world map regions for GeoCharts
     * @return array
     */
    private function getWorldRegions()
    {
        $countries = array(
            'DZ'=>'Algeria', 'EG'=>'Egypt', 'EH'=>'Western Sahara', 'LY'=>'Libya', 'MA'=>'Morocco', 'SD'=>'Sudan', 'TN'=>'Tunisia',

            'BF'=>'Burkina Faso', 'BJ'=>'Benin', 'CI'=>'Côte d\'Ivoire', 'CV'=>'Cabo Verde', 'GH'=>'Ghana', 'GM'=>'Gambia', 'GN'=>'Guinea',
            'GW'=>'Guinea-Bissau', 'LR'=>'Liberia', 'ML'=>'Mali', 'MR'=>'Mauritania', 'NE'=>'Niger', 'NG'=>'Nigeria', 'SH'=>'Saint Helena',
            'SL'=>'Sierra Leone', 'SN'=>'Senegal', 'TG'=>'Togo',

            'AO'=>'Angola', 'CD'=>'Congo', 'ZR'=>'Zaire', 'CF'=>'Central African Republic', 'CG'=>'Congo', 'CM'=>'Cameroon', 'GA'=>'Gabon',
            'GQ'=>'Equatorial Guinea', 'ST'=>'Sao Tome and Principe', 'TD'=>'Chad',

            'BI'=>'Burundi', 'DJ'=>'Djibouti', 'ER'=>'Eritrea', 'ET'=>'Ethiopia', 'KE'=>'Kenya', 'KM'=>'Comoros', 'MG'=>'Madagascar',
            'MU'=>'Mauritius', 'MW'=>'Malawi', 'MZ'=>'Mozambique', 'RE'=>'Réunion', 'RW'=>'Rwanda', 'SC'=>'Seychelles', 'SO'=>'Somalia',
            'TZ'=>'Tanzania', 'UG'=>'Uganda', 'YT'=>'Mayotte', 'ZM'=>'Zambia', 'ZW'=>'Zimbabwe',

            'BW' => 'Botswana', 'LS' => 'Lesotho', 'NA' => 'Namibia', 'SZ' => 'Swaziland', 'ZA' => 'South Africa',

            'GG' => 'Guernsey', 'GG' => 'Guernsey', 'JE' => 'Jersey', 'JE' => 'Jersey', 'AX' => 'Aland Islands', 'AX' => 'Åland', 'DK' => 'Denmark', 'EE' => 'Estonia', 'FI' => 'Finland', 'FO' => 'Faroe Islands',
            'GB' => 'United Kingdom', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IM' => 'Isle of Man', 'IS' => 'Iceland', 'LT' => 'Lithuania', 'LT' => 'Libya Tripoli', 'LV' => 'Latvia', 'NO' => 'Norway', 'SE' => 'Sweden',
            'SJ' => 'Svalbard and Jan Mayen',

            'AT' => 'Austria', 'BE' => 'Belgium', 'CH' => 'Switzerland', 'DE' => 'Germany', 'DD' => 'DE', 'DD' => 'German Democratic Republic', 'FR' => 'France', 'FX' => 'France, Metropolitan', 'LI' => 'Liechtenstein', 'LU' => 'Luxembourg', 'MC' => 'Monaco', 'NL' => 'Netherlands',

            'BG' => 'Bulgaria', 'BY' => 'Belarus', 'CZ' => 'Czech Republic', 'HU' => 'Hungary', 'MD' => 'Moldova', 'PL' => 'Poland', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RU' => 'Burundi', 'SU' => 'USSR',
            'SK' => 'Slovakia', 'SK' => 'Sikkim', 'UA' => 'Ukraine',

            'AD' => 'Andorra', 'AL' => 'Albania', 'BA' => 'Bosnia and Herzegovina', 'ES' => 'Spain', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'HR' => 'Croatia', 'IT' => 'Italy', 'ME' => 'Montenegro', 'ME' => 'Western Sahara', 'MK' => 'Macedonia',
            'MT' => 'Malta', 'CS' => 'Serbia and Montenegro', 'CS' => 'Czechoslovakia', 'RS' => 'Serbia', 'PT' => 'Portugal', 'SI' => 'Slovenia', 'SM' => 'San Marino', 'VA' => 'Holy See', 'YU' => 'Yugoslavia',

            'BM' => 'Bermuda', 'CA' => 'Canada', 'GL' => 'Greenland', 'PM' => 'Saint Pierre and Miquelon', 'US' => 'United States of America',

            'AG' => 'Antigua and Barbuda', 'AI' => 'Anguilla', 'AI' => 'French Afar and Issas', 'AN' => 'Netherlands Antilles', 'AW' => 'Aruba', 'BB' => 'Barbados', 'BL' => 'Saint Barthélemy', 'BS' => 'Bahamas', 'CU' => 'Cuba', 'DM' => 'Dominica',
            'DO' => 'Dominican Republic', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'HT' => 'Haiti', 'JM' => 'Jamaica', 'KN' => 'Saint Kitts and Nevis', 'KY' => 'Cayman Islands', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin (French part)',
            'MQ' => 'Martinique', 'MS' => 'Montserrat', 'PR' => 'Puerto Rico', 'TC' => 'Turks and Caicos Islands', 'TT' => 'Trinidad and Tobago', 'VC' => 'Saint Vincent and the Grenadines', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.',

            'BZ' => 'Belize', 'CR' => 'Costa Rica', 'GT' => 'Guatemala', 'HN' => 'Honduras', 'MX' => 'Mexico', 'NI' => 'Nicaragua', 'PA' => 'Panama', 'SV' => 'El Salvador',

            'AR' => 'Argentina', 'BO' => 'Bolivia', 'BR' => 'Brazil', 'CL' => 'Chile', 'CO' => 'Colombia', 'EC' => 'Ecuador', 'FK' => 'Falkland Islands (Malvinas)', 'GF' => 'French Guiana', 'GY' => 'Guyana', 'PE' => 'Peru', 'PY' => 'Paraguay', 'SR' => 'Suriname', 'UY' => 'Uruguay', 'VE' => 'Venezuela',

            'TM' => 'Turkmenistan', 'TJ' => 'Tajikistan', 'KG' => 'Kyrgyzstan', 'KZ' => 'Kazakhstan', 'UZ' => 'Uzbekistan',

            'CN' => 'China', 'HK' => 'Hong Kong', 'JP' => 'Japan', 'KP' => 'Democratic reople\'s republic of Korea', 'KR' => 'Korea', 'MN' => 'Mongolia', 'MO' => 'Macao', 'TW' => 'Taiwan, Province of China',

            'AF' => 'Afghanistan', 'BD' => 'Bangladesh', 'BT' => 'Bhutan', 'IN' => 'India', 'IR' => 'Iran', 'LK' => 'Sri Lanka', 'MV' => 'Maldives', 'NP' => 'Nepal', 'PK' => 'Pakistan',

            'BN' => 'Brunei Darussalam', 'ID' => 'Indonesia', 'KH' => 'Cambodia', 'LA' => 'Lao People\'s Democratic Republic', 'MM' => 'Myanmar', 'BU' => 'Burma', 'MY' => 'Malaysia', 'PH' => 'Philippines', 'SG' => 'Singapore', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TP' => 'East Timor', 'VN' => 'Viet Nam',

            'AE' => 'United Arab Emirates', 'AM' => 'Armenia', 'AZ' => 'Azerbaijan', 'BH' => 'Bahrain', 'CY' => 'Cyprus', 'GE' => 'Georgia', 'GE' => 'Gilbert and Ellice Islands', 'IL' => 'Israel', 'IQ' => 'Iraq', 'JO' => 'Jordan', 'KW' => 'Kuwait', 'LB' => 'Lebanon', 'OM' => 'Oman', 'PS' => 'Palestine', 'QA' => 'Qatar', 'SA' => 'Saudi Arabia', 'NT' => 'Neutral Zone', 'SY' => 'Syrian Arab Republic', 'TR' => 'Turkey', 'YE' => 'Yemen', 'YD' => 'YE', 'YD' => 'Yemen',

            'AU' => 'Australia', 'NF' => 'Norfolk Island', 'NZ' => 'New Zealand',
            'FJ' => 'Fiji', 'NC' => 'New Caledonia', 'PG' => 'Papua New Guinea', 'SB' => 'Solomon Islands', 'VU' => 'Vanuatu',
            'FM' => 'Micronesia', 'GU' => 'Guam', 'KI' => 'Kiribati', 'MH' => 'Marshall Islands', 'MP' => 'Northern Mariana Islands', 'NR' => 'Nauru', 'PW' => 'Palau',
            'AS' => 'American Samoa', 'CK' => 'Cook Islands', 'NU' => 'Niue', 'PF' => 'French Polynesia', 'PN' => 'Pitcairn', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TV' => 'Tuvalu', 'WF' => 'Wallis and Futuna', 'WS' => 'Samoa'
        );

        asort($countries);

        $regions = array(
            '002' => 'Africa',
            '015' => 'Northern Africa',
            '011' => 'Western Africa',
            '017' => 'Middle Africa',
            '014' => 'Easter Africa',
            '018' => 'Southern Africa',

            '150' => 'Europe',
            '154' => 'Northern Europe',
            '155' => 'Western Europe',
            '151' => 'Eastern Europe',
            '039' => 'Southern Europe',

            '019' => 'Americas',
            '021' => 'Northern America',
            '029' => 'Caribbean',
            '013' => 'Central America',
            '005' => 'South America',

            '142' => 'Asia',
            '143' => 'Central Asia',
            '030' => 'Eastern Asia',
            '034' => 'Southern Asia',
            '035' => 'South-Eastern Asia',
            '145' => 'Western Asia',
            '009' => 'Oceania',
            '053' => 'Australia and New Zealand',
            '054' => 'Melanesia',
            '057' => 'Micronesia',
            '061' => 'Polynesia',
        );

        foreach ($regions as $key=>$region){
            $countries[$key] = $region;
        }

        return $countries;
    }
}

?>