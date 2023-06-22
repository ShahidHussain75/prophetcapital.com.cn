<?php

namespace DataSource;

require_once(DTS_PLUGIN_DIR.'/classes/models/data-source.php');
require_once(DTS_PLUGIN_DIR.'/classes/models/data-chart.php');
require_once(DTS_PLUGIN_DIR.'/classes/charts/GoogleChart.php');
require_once(DTS_PLUGIN_DIR.'/classes/validate-form.php');
require_once(DTS_PLUGIN_DIR.'/classes/posts-grid.php');

/**
 * Data sources controller
 */

class Charts {
    /**
     * Access to bootstrap object
     */
    private $bootstrap = null;

    public function __construct($bootstrap=null)
    {
        $this->bootstrap = $bootstrap;

        if ( is_admin() ) {
            $screen = get_current_screen();
            $screen->add_help_tab( array(
                'id'      => 'dts-charts-help',
                'title'   => __('Getting started', 'data-source'),
                'content' =>
                    '<p>'.__('You can see list of existing charts on index page. In order to put any of them to your website just copy short code and paste it in page or post editor.', 'data-source').'</p>'.
                    '<p>'.__('This section allows you to create and edit charts using your data sources. You can select columns you wish to display in Columns section, select or configure it\'s look in Chart Styles box and choose other options in chart options.', 'data-source').'</p>'.
                    '<p>'.__('For more details check <a href="'.dts_plugin_url('DataSource - User Manual.pdf').'">User Manual</a>', 'data-source').'</p>'
            ) );
        }
    }

    /**
     * List of charts
     * @return array
     */
    public function index()
    {
        $grid = new \DataSource\PostsGrid('dts-data-chart');

        return array(
            'grid' => $grid
        );
    }

    /**
     * Create new chart
     * @return array
     */
    function add()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-chart' );

            $form = $this->getChartFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataChart = new \DataSource\DataChartModel($data);
                $dataChart->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-charts');
                $this->sendAjaxRespone(true, null, null, $redirectUrl);
            }
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'data-sources-charts',
            dts_plugin_url('admin/js/charts.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'google-charts-js-api',
            'https://www.google.com/jsapi',
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'palettes' => $this->getPalettes(),
            'dataSources' => $dataSources['list']
        );
    }

    /**
     * Edit chart
     * @return array
     */
    function edit()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        $post = $request->getRequest('post');

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-chart' );

            $form = $this->getChartFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataChart = new \DataSource\DataChartModel($post);
                $dataChart->setValues($data);
                $dataChart->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-charts');
                $this->sendAjaxRespone(true, null, null, $redirectUrl);
            }
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'data-sources-tables',
            dts_plugin_url('admin/js/charts.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'google-charts-js-api',
            'https://www.google.com/jsapi',
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $dataChart = new \DataSource\DataChartModel($post);

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'palettes' => $this->getPalettes(),
            'dataSources' => $dataSources['list'],
            'dataChart' => $dataChart
        );
    }

    /**
     * Delete chart
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
                $dataChart = new \DataSource\DataChartModel(array('id'=>$postId));
                $dataChart->delete();
            }

            $redirectUrl = $this->bootstrap->menuUrl('dts-charts');
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
     * Load chart preview
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

        $chart = new \DataSource\GoogleChart($dataSource);
        $options = array();

        if (!isset($data['legend']) || !$data['legend']) {
            $options['legend'] = array('position'=>'none');
        }

        if (!isset($data['groupby'])) {
            $data['groupby'] = array();
        }

        $chartType = '';
        if ($data['chart_name'] == 'pie') {
            if ($data['pie_type'] == '3d') {
                $options['is3D'] = true;
            } elseif ($data['pie_type'] == 'donut') {
                $options['pieHole'] = '0.4';
            }
            $chartType = 'PieChart';
            $values = $chart->getPieChart($data['columns'], $data['sortby'], $data['sortorder'], $data['group'], $data['groupby']);
        } elseif ($data['chart_name'] == 'bar') {
            if (isset($data['stacked']) && ($data['stacked'] == 1)) {
                $options['isStacked'] = true;
            }
            $chartType = 'ColumnChart';
            if (isset($data['horizontal']) && $data['horizontal']) {
                $chartType = 'BarChart';
            }
            $values = $chart->getBarChart($data['columns'], $data['sortby'], $data['sortorder'], $data['group'], $data['groupby']);
        } elseif ($data['chart_name'] == '2d') {
            $chartType = 'AreaChart';
            $values = $chart->get2DChart($data['columns'], $data['sortby'], $data['sortorder'], $data['group'], $data['groupby']);
        }

        if ($data['palette'] == 'custom') {
            $options['colors'] = $data['colors'];
        }

        if ($data['show_title']) {
            $options['title'] = $data['title'];
        }

        $vars = array(
            'values' => $values,
            'chart' => $chartType,
            'options' => $options
        );

        $this->bootstrap->loadTemplate($vars);
        exit;
    }

    /**
     * Render chart for public side
     * @param $id
     */
    public function renderChart($id)
    {
        $dataChart = new \DataSource\DataChartModel($id);

        $dataSource = new \DataSource\DataSourceModel($dataChart->datasource);
        $chart = new \DataSource\GoogleChart($dataSource);
        $options = array();

        if (!$dataChart->legend) {
            $options['legend'] = array('position'=>'none');
        }

        $chartType = '';
        if ($dataChart->chart_name == 'pie') {
            if ($dataChart->pie_type == '3d') {
                $options['is3D'] = true;
            } elseif ($dataChart->pie_type == 'donut') {
                $options['pieHole'] = '0.4';
            }
            $chartType = 'PieChart';
            $values = $chart->getPieChart($dataChart->columns, $dataChart->sortby, $dataChart->sortorder, $dataChart->group, $dataChart->groupby);
        } elseif ($dataChart->chart_name == 'bar') {
            if ($dataChart->stacked == 1) {
                $options['isStacked'] = true;
            }
            $chartType = 'ColumnChart';
            if ($dataChart->horizontal) {
                $chartType = 'BarChart';
            }
            $values = $chart->getBarChart($dataChart->columns, $dataChart->sortby, $dataChart->sortorder, $dataChart->group, $dataChart->groupby);
        } elseif ($dataChart->chart_name == '2d') {
            $chartType = 'AreaChart';
            $values = $chart->get2DChart($dataChart->columns, $dataChart->sortby, $dataChart->sortorder, $dataChart->group, $dataChart->groupby);
        }

        if ($dataChart->palette == 'custom') {
            $options['colors'] = $dataChart->colors;
        }

        if ($dataChart->show_title) {
            $options['title'] = $dataChart->title;
        }

        $vars = array(
            'values' => $values,
            'chart' => $chartType,
            'options' => $options,
            'chartData' => $dataChart
        );

        wp_enqueue_script("jquery");

        $this->bootstrap->loadTemplate($vars, 'dts-charts', 'renderChart');
        return;
    }

    /**
     * Validate data chart form
     * @param $data
     * @return ValidateForm
     */
    private function getChartFormValidator($data)
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
     * Returns palettes list
     * @return array|mixed
     */
    private function getPalettes()
    {
        $palettes = '[{"name":"vision in taupe","colors":["#735353","#293040","#262018","#736D68","#59544F"]},{"name":"Theme 2","colors":["#2E608C","#1C3D59","#F2F2F2","#245C73","#5D98A6"]},{"name":"rose ","colors":["#AD3D5B","#1C3015","#AC3335","#0B100B","#948271"]},{"name":"Theme 25","colors":["#353440","#497CBF","#4E8BBF","#F2E4C9","#A67C72"]},{"name":"what","colors":["#F2B28B","#C2BABA","#FFFFFF","#534F40","#FFFFFF"]},{"name":"Pinkish","colors":["#D95970","#F2F2F2","#F2D6A2","#F28E85","#591A15"]},{"name":"Theme 24","colors":["#D0E2F2","#F2F2F2","#5F7336","#8C8672","#A67165"]},{"name":"Theme 4","colors":["#E4E9F2","#3E5942","#4D734F","#1D2619","#D9CD91"]},{"name":"cake","colors":["#D9304F","#A6335D","#D9674E","#8C2F1B","#F2786D"]},{"name":"SeaWeed","colors":["#401238","#392973","#053959","#063A40","#035951"]},{"name":"olive","colors":["#732634","#D9B777","#735826","#D9BCA3","#732E1F"]},{"name":"Theme 32","colors":["#151B26","#99AABF","#F2DFA7","#F2DCC9","#F2AD85"]},{"name":"Bumble Bee","colors":["#1B1A26","#F2F2F2","#F2BB16","#F2A20C","#D9A79C"]},{"name":"stella","colors":["#0F122D","#49858C","#E7F8ED","#E2C7C6","#C9A1B2"]},{"name":"room ","colors":["#392F40","#766BBF","#BFB79F","#7E7178","#8F6154"]},{"name":"Landscaping Company","colors":["#E66C26","#7A6540","#F5FA68","#1E3B11","#74E141"]},{"name":"leanne","colors":["#D94A8C","#492559","#4162A6","#4D6F8C","#F2D8C2"]},{"name":"Iceland by WithHearts","colors":["#787C86","#B5C8D7","#A5A24F","#8D8243","#564E2E"]},{"name":"restouranr","colors":["#B6BEC0","#F2E1AC","#F2C8A2","#BC998E","#CB8D38"]},{"name":"hamster","colors":["#D9BFBF","#591535","#468C58","#DAF2B6","#BF9663"]},{"name":"swag","colors":["#F257D8","#448FF2","#50B4F2","#38D0F2","#F2C84B"]},{"name":"cloth 1","colors":["#BF1B39","#73122C","#F2CC85","#A68256","#D96055"]},{"name":"backbend","colors":["#60A4BF","#5AB5BF","#66D9D9","#ADD9B0","#D9C8B4"]},{"name":"Theme 6","colors":["#F25E95","#F26BB5","#D3D932","#A69E86","#D9896C"]},{"name":"Theme 2","colors":["#73404A","#D8EBF2","#D9AFA0","#8C5F4F","#59362E"]},{"name":"Theme 43","colors":["#8C1F3C","#6DB1F2","#72C1F2","#D8F2F0","#BF0404"]},{"name":"Theme 1","colors":["#CBC7D9","#F2EDE4","#D9A273","#A66E4E","#260401"]},{"name":"giselle","colors":["#F24BA7","#F24BC6","#B6C5F2","#D9C58B","#D99791"]},{"name":"Theme 2","colors":["#252E40","#C4D4F2","#4D5D73","#D0ECF2","#344028"]},{"name":"Theme 2","colors":["#252E40","#C4D4F2","#4D5D73","#D0ECF2","#344028"]},{"name":"Theme 1","colors":["#F2133C","#6C808C","#6ED93D","#F2D6BD","#F26430"]},{"name":"table top","colors":["#D92344","#BF3056","#8C703B","#D9AE79","#F28E85"]},{"name":"ponton","colors":["#0D0D0D","#8C5B3E","#F2594B","#8C1818","#F25C5C"]},{"name":"Theme 2","colors":["#F20519","#F2055C","#F2059F","#07F2F2","#0ABF04"]},{"name":"Theme 3","colors":["#201822","#F27E9D","#F2C094","#F2A999","#40211A"]},{"name":"Theme 8","colors":["#000000","#EDE9F6","#F2F2F2","#29F270","#73655D"]},
                    {"name":"Theme 6","colors":["#773045","#702C3E","#482329","#371321","#F6EAD0"]},{"name":"Theme 1","colors":["#F280CA","#D9A0C5","#F279D2","#403F40","#F2F2F2"]},{"name":"Theme 14","colors":["#3B6FD9","#47A62D","#BFA34E","#BF834E","#8C5E35"]},{"name":"Theme 7","colors":["#0D0A0D","#3C3A40","#D9D0BF","#BFA89B","#BF3F34"]},{"name":"Theme 7","colors":["#212126","#E9EFF2","#A2A69C","#D9BBA0","#F2B8A2"]},{"name":"yvr","colors":["#4E6BA6","#747A87","#A9B9D9","#384D59","#F2EBDC"]},{"name":"Theme 5","colors":["#1C1C26","#F2F2F2","#EEF279","#733A26","#8C8584"]},{"name":"Theme 4","colors":["#3B3B40","#0C0C0D","#F2F2F2","#D9B391","#BF9D7E"]},{"name":"Theme 3h","colors":["#370C34","#0F1114","#8F8E93","#827A6E","#655B52"]},{"name":"Theme 3","colors":["#D0D2D9","#D9D8D2","#A6A09C","#8C4130","#261414"]},{"name":"Theme 2","colors":["#BB8491","#41240D","#786E52","#807557","#7E6F4E"]},{"name":"Theme 3","colors":["#E7B6B3","#E0A2A2","#E4A39E","#EE8A85","#4B5B59"]},{"name":"Theme 2","colors":["#F263B2","#F25EBF","#A8BF56","#A69B92","#D97E6A"]},{"name":"Modafucka","colors":["#232ADB","#EC008C","#140120","#6FF2CE","#FFFFFF"]},{"name":"Theme 12","colors":["#BFA065","#D9AE5F","#D9C1B4","#A69286","#D99F9A"]},{"name":"kimono","colors":["#F2778D","#BF3B5E","#6A8C65","#D99255","#BF4136"]},{"name":"Theme 4","colors":["#0B0A0D","#C8CBD1","#574F55","#5B575E","#D8E0EE"]},{"name":"flowerz ","colors":["#C1DCEE","#E4C591","#E17069","#F0D87D","#571838"]},{"name":"Theme 2","colors":["#48594D","#5C735F","#D9C196","#D9C7B8","#A6978A"]},{"name":"qttttt ","colors":["#DCBAA2","#553F6D","#5C6783","#D3CECA","#E19B7C"]},{"name":"Theme 1","colors":["#F25CAF","#567360","#7D8C45","#F2E7DC","#D9A38F"]},{"name":"Theme 3m","colors":["#736451","#BFA58E","#594A3C","#A68A7B","#D9C2BA"]},{"name":"Theme 11","colors":["#592D41","#F2E4BB","#D99982","#8C5E4D","#D97F77"]},{"name":"Theme 29","colors":["#525943","#262624","#A69665","#D9CAAD","#0D0B07"]},{"name":"Theme 3","colors":["#4F6B73","#BD7870","#F4EA8E","#B397A9","#64001F"]},{"name":"Theme 10","colors":["#A66D82","#F2B988","#D9A384","#A6695B","#D9736A"]},{"name":"adobe color","colors":["#370235","#A1619C","#FE62FA","#FDB8FB","#F26A1B"]},{"name":"Sunset 2","colors":["#0C0B0D","#495B73","#885B63","#F2EFBD","#F27D72"]},{"name":"Theme 20","colors":["#CDC1D9","#75BFAA","#D9BE3B","#D9A441","#8C6318"]},{"name":"Theme 7","colors":["#0C0B0D","#495B73","#F2F2F2","#F2EFBD","#F27D72"]},{"name":"Theme 19","colors":["#E0D6EB","#76BCAB","#BE8A26","#695B1B","#8E7B74"]},{"name":"Theme 30","colors":["#BF3983","#F2EB8C","#F28B30","#F25922","#D92929"]},{"name":"Theme 8","colors":["#F2EDA7","#F2B279","#401F0C","#F2A172","#0D0000"]},{"name":"Theme 16","colors":["#A6751B","#401910","#BFA9A4","#A61B1B","#A63C3C"]},{"name":"kitty","colors":["#BEB3A5","#B0766F","#C0A38F","#E7C8BD","#BF988F"]},{"name":"Theme 1","colors":["#567D8A","#AF4834","#231F1E","#D96B62","#BFA07F"]},
                    {"name":"Theme 4","colors":["#5A708C","#BF9E3B","#D99982","#593325","#F2A099"]},{"name":"Theme 3","colors":["#1B618C","#3285A6","#D9946C","#BF6C3B","#F2C1B6"]},{"name":"Theme 2","colors":["#FDD17B","#F8DFC4","#948685","#F28E6C","#B1B2B3"]},{"name":"Painted Bike","colors":["#D6D1C9","#F9F2F2","#FFFFFF","#3C3121","#382E2A"]},{"name":"Theme 10","colors":["#4FF9FF","#C0FFC3","#F64C97","#181D3A","#E0AF6F"]},{"name":"Theme 1","colors":["#F28C9F","#F2D5F2","#154EBF","#155FBF","#79E6F2"]},{"name":"Theme 2","colors":["#FFF9FB","#7F3139","#897D67","#AB8F78","#281720"]},{"name":"Theme 1","colors":["#F2C185","#BF8136","#F2D8C2","#A68A7B","#F28C8C"]},{"name":"Theme 1","colors":["#736B6D","#0C0B08","#A62E3F","#586C41","#3E4834"]},{"name":"kenevens colors","colors":["#5A1914","#AC1F22","#C0B0B3","#696A73","#FEFEFE"]},{"name":"whatis","colors":["#CC1571","#94031E","#73012A","#F58D2E","#F5D59C"]},{"name":"dinner","colors":["#E74F74","#B72745","#E69753","#7D4723","#8B401F"]},{"name":"Theme 4 bb","colors":["#B8AB98","#E0CCA4","#9D9896","#DCCDA6","#8A858E"]},{"name":"me alternately","colors":["#5A3673","#67BDB8","#F2F2F2","#DB6F85","#4799B1"]},{"name":"city lake spring","colors":["#8FA8D9","#ACCAF2","#BFA626","#8C6B2E","#735229"]},{"name":"Theme 4","colors":["#D98BC4","#EDC4F2","#8DA68F","#B0D9B2","#C4F2BD"]},{"name":"Theme 2","colors":["#465CA6","#6D7887","#787E5A","#ABAC67","#90967E"]},{"name":"city lake 1","colors":["#A0C4F2","#AED3F2","#736D1A","#D9BC2B","#735826"]},{"name":"Theme 1","colors":["#8095BF","#BCBF6B","#A6A15D","#D9CAB8","#A6826D"]},{"name":"Theme 5","colors":["#F2E63D","#F2CB57","#F28241","#D9B1A3","#F26D3D"]},{"name":"Grey Cords","colors":["#6A7484","#A2A9BB","#6E7B88","#CDD3E1","#9399A4"]},{"name":"Theme 4","colors":["#66908A","#F5C476","#A3A39B","#C7B9B0","#5E6963"]},{"name":"Theme 3","colors":["#F21D44","#F28D35","#F27141","#8C1616","#F23535"]},{"name":"Theme 1","colors":["#312829","#CEBFA8","#F7F8F3","#F2A29B","#BF5A5A"]},{"name":"Theme 2","colors":["#D93B65","#355925","#5A8C35","#ABD94A","#D4D94A"]},{"name":"Theme 1","colors":["#F96F16","#DB4414","#F23622","#DB1435","#F916AD"]},{"name":"Theme 2","colors":["#C1817B","#E0BD9E","#EAB2B5","#554D50","#8F7D88"]},{"name":"me today","colors":["#BF4158","#2669BF","#5FCDD9","#F2CEAE","#A66F5B"]},{"name":"Theme 1","colors":["#38401E","#B5BF63","#848C49","#F29E38","#A64724"]},{"name":"summer dress","colors":["#0A3EA6","#0A4DA6","#849EBF","#34A6BF","#08A6A6"]},{"name":"Trees","colors":["#414440","#88904E","#495C2B","#7D6B5E","#899A83"]},{"name":"Theme 6","colors":["#B3CFF2","#3B4B59","#6C7D8C","#CEE1F2","#F2D0C4"]},{"name":"pretty live","colors":["#D9666F","#273240","#58788C","#F2E2CE","#8C7B72"]},{"name":"live car ride","colors":["#083359","#142426","#207364","#E0F2D8","#A69E97"]},{"name":"BookStore","colors":["#AE8A5C","#FFE1BA","#FAD29D","#4A8AAE","#9DD8FA"]},{"name":"Siding","colors":["#ACA7A1","#BFBBB4","#888070","#B2ABA1","#B9B6B6"]},
                    {"name":"Cold","colors":["#011526","#F2F2F2","#4A7A8C","#D9BB96","#A68263"]},{"name":"Theme 1","colors":["#9E6C5C","#C3947C","#BE8C6A","#C09084","#D49784"]},{"name":"Front porch","colors":["#B2B9BD","#9D9694","#3B312C","#645359","#FCFBF6"]},{"name":"Victorian Furniture Store","colors":["#ABD7E3","#362A24","#E37B6D","#ECF5C0","#ADDB9B"]},{"name":"thunder","colors":["#B3ABA7","#B3AAB1","#E6BBB4","#FDDED2","#9A97A1"]},{"name":"Theme 3","colors":["#F2EDE4","#D9CCC1","#A69485","#A67051","#8C543F"]},{"name":"Theme 2","colors":["#BACBD9","#A69076","#D9C9BA","#26211C","#0D0A08"]},{"name":"Brick","colors":["#AA988F","#7D787D","#C0B8AC","#6A5C57","#73706D"]},{"name":"Theme 5","colors":["#1D1822","#FBFCFC","#8EC3BF","#20827D","#55A603"]},{"name":"my pants","colors":["#F0D4D5","#D1A6AF","#9B7580","#B49573","#483828"]},{"name":"darkness","colors":["#3F3936","#AA9B8B","#A29B89","#A59A8C","#26221E"]},{"name":"Theme 3","colors":["#2F3659","#C1D4D9","#5F6D70","#BF3030","#F26666"]},{"name":"Theme 4","colors":["#2FEAD1","#D813F2","#52BE33","#EAF205","#A60303"]},{"name":"Theme 4","colors":["#8C2E36","#171826","#BFAEA4","#A68B7C","#593932"]},{"name":"Sushi Wall","colors":["#232240","#A69972","#D9A577","#402C21","#8C6354"]},{"name":"Theme 3","colors":["#F9E3C4","#3A3A57","#120C0B","#55352B","#877155"]},{"name":"Theme 3","colors":["#A64E55","#5695E4","#3D3D41","#CACACC","#B8CCD9"]},{"name":"table top display","colors":["#0B6161","#7D8289","#DBC7B3","#6F5B46","#45251D"]},{"name":"Victorian Furniture","colors":["#A1CBD6","#42342D","#E37B6D","#ECF5C0","#76966A"]},{"name":"Theme 2","colors":["#F2D852","#594916","#D9B341","#402E0C","#8C7F77"]},{"name":"Theme 1","colors":["#A60321","#A660A1","#688C82","#527349","#F23A29"]},{"name":"Theme 1","colors":["#59222C","#1A0926","#594636","#D9644A","#BF4F45"]},{"name":"Theme 1","colors":["#400F21","#26121D","#A68F72","#735E45","#733D29"]},{"name":"Metreon Chic","colors":["#591B38","#A0DBF2","#F2D680","#F2CA7E","#F2622E"]},{"name":"Theme 1","colors":["#2D2B4C","#AB6F6F","#AE535A","#B0D8A6","#9AB7A4"]},{"name":"Theme 1","colors":["#F2A0A7","#F285A2","#F26699","#2D4473","#F2C4B3"]},{"name":"Kenyon Plaid","colors":["#A62E38","#8C2B45","#A66F8D","#593027","#BF6F6F"]},{"name":"Theme 1","colors":["#63AEBF","#A6A163","#BF8654","#A66963","#401A1A"]},{"name":"sec pic outsidw","colors":["#264013","#517338","#85A653","#BF9D7E","#A67D65"]},{"name":"Theme 1","colors":["#F2AEE0","#F2EA79","#3D4C23","#B61216","#CC9880"]},{"name":"Theme 4","colors":["#3B3C59","#94ABF2","#B3C3F2","#5E8C65","#D9B9A7"]},{"name":"SJNN-V10","colors":["#F6E5D4","#867977","#520063","#A85873","#FFFFFF"]},{"name":"SJNN-V9","colors":["#EBD4DE","#520063","#2B2B2B","#CAA953","#FFFFFF"]},{"name":"pic frm outside","colors":["#F2F2F2","#284021","#3A592C","#A6A39F","#8C6E64"]},{"name":"SJNN-V8","colors":["#C1DCD1","#520063","#513444","#0A0A24","#FFFFFF"]},{"name":"SJNN-V7","colors":["#E5E5DD","#BE909B","#606060","#649A90","#FFFFFF"]},
                    {"name":"SJNN-V6","colors":["#E1EFE6","#76979A","#282828","#BE68A5","#FFFFFF"]},{"name":"SJNN-V5","colors":["#FED4C6","#C48DA0","#D03C30","#DDC05E","#FFFFFF"]},{"name":"SJNN","colors":["#F17D80","#833555","#457842","#F7784F","#FFFFFF"]},{"name":"SJNN-V4","colors":["#CBEDDD","#410727","#316B26","#EEAA69","#FFFFFF"]},{"name":"Neon Parrot","colors":["#FF0019","#FD0D14","#0071A3","#060C34","#FFFF21"]},{"name":"SJNN-V3","colors":["#DDDED9","#520063","#3E3E3E","#617192","#FFFFFF"]},{"name":"SJNN-V1","colors":["#D8E2EE","#434343","#873873","#DCED9F","#FFFFFF"]},{"name":"Theme 1","colors":["#8C141E","#F2C572","#D9B68B","#A67A60","#A63333"]},{"name":"Theme 14","colors":["#0E121A","#15171C","#27253C","#EEE1EF","#887489"]},{"name":"My foot colors","colors":["#DCC4C2","#2C1D2F","#0B0B0D","#DAE0EC","#8E8EA1"]},{"name":"Chases shirt","colors":["#662735","#6A2633","#FEFEFE","#9F715A","#8C664B"]},{"name":"Locker upscale gradient","colors":["#260D11","#908DA6","#F2F2F2","#A69586","#593939"]},{"name":"Theme 2","colors":["#BF34A8","#A6639B","#384773","#4E7DA6","#ECF244"]},{"name":"lissa swimming","colors":["#112959","#A3B4BF","#56ACBF","#88DFF2","#733832"]},{"name":"Theme 1","colors":["#C8C9C3","#747D7A","#433C2D","#756F54","#727171"]},{"name":"Fendi Reds","colors":["#3E0506","#8C1D22","#F0F1F1","#5B5453","#750E0F"]},{"name":"galaxy","colors":["#D777F2","#7D6BF2","#6650F2","#302E59","#0D0E26"]},{"name":"more blue","colors":["#294655","#4D7794","#6898CA","#1F282C","#A1C5EE"]},{"name":"Blue","colors":["#8FA4BF","#11538C","#9BAEBF","#165F8C","#30698C"]},{"name":"Theme 4","colors":["#0F594C","#107361","#A68568","#8C6954","#592614"]},{"name":"Theme 20","colors":["#6D8C4D","#9BBF65","#48592E","#A68160","#BF9780"]},{"name":"Theme 19","colors":["#26401B","#7FA658","#758C51","#BFBDB8","#D9B79A"]},{"name":"Theme 2","colors":["#A17E63","#6C4A2E","#694D37","#866E63","#623D2D"]},{"name":"Theme 18","colors":["#F2F2F2","#070D09","#5C7346","#8CA665","#BF9780"]},{"name":"Theme 1","colors":["#A6415C","#D9B29C","#BF877A","#8C564A","#592A25"]},{"name":"Theme 17","colors":["#5F734C","#708C46","#8AA64E","#F2E5D5","#8C7369"]},{"name":"house b","colors":["#5F734C","#909B5A","#F1E0CC","#C1A192","#8C7369"]},{"name":"coffee","colors":["#F2D1B3","#BF9673","#261911","#A67458","#593A28"]},{"name":"Theme 3","colors":["#FFF6C9","#433C36","#6F4F36","#2D221B","#121013"]},{"name":"Theme 2","colors":["#004E94","#048ABF","#7CE3BB","#32AA8A","#D1C74B"]},{"name":"Prada Pallette","colors":["#043F8D","#004973","#0089A6","#F15F05","#98C3DF"]},{"name":"house","colors":["#5F734C","#7FA646","#8BA651","#F2E5D5","#8C7369"]},{"name":"Theme 4","colors":["#BF1A35","#D58C91","#F32B59","#BA966D","#603C2F"]},{"name":"Theme 3","colors":["#735741","#4B4540","#AD9073","#A28777","#8A583B"]},{"name":"clouds","colors":["#525146","#7CA68E","#A7D9B8","#59564D","#8C7B74"]},{"name":"Theme 3","colors":["#F2CB05","#F2B705","#D98E04","#F28379","#BF0A0A"]},
                    {"name":"Theme 1","colors":["#D300B7","#BF9775","#261B14","#BF9278","#734930"]},{"name":"Grapefruit Cloud","colors":["#F23D5E","#F2F2F2","#F2A9A2","#BF4C41","#F25D50"]},{"name":"dundas game day","colors":["#174FBF","#B8C6D9","#93A651","#545928","#848C45"]},{"name":"Theme 3","colors":["#6E7B3E","#9A9D54","#3E4221","#A09E4A","#C5CF6E"]},{"name":"Vitamin C","colors":["#BF4B8B","#F2D49B","#F28B0C","#D9C1B8","#F26363"]},{"name":"Palm","colors":["#A9B9D9","#698EBF","#736D19","#D9CBBA","#403527"]},{"name":"Theme 1","colors":["#A3B5D9","#314027","#D5D973","#A3A651","#8C8045"]},{"name":"moon","colors":["#F0D9AD","#E5CF9E","#C7B585","#F2DCB2","#020202"]},{"name":"Theme 3","colors":["#F2D027","#D9A918","#594B39","#D9B89C","#A6896F"]},{"name":"Theme 11","colors":["#6DA2A9","#759493","#6699A2","#D6B498","#516C6E"]},{"name":"Snow Kaiser","colors":["#3A3140","#B4A792","#D0D9D1","#BABFBA","#8C6C61"]},{"name":"sunclouds","colors":["#5E728C","#F2EC99","#FECE63","#F27B50","#4A373A"]},{"name":"Oatmeal Petals","colors":["#F0F0F2","#D9965B","#A66844","#F7937A","#BF3B37"]},{"name":"My Color Theme","colors":["#0DFF0D","#E8AD0C","#FF0000","#B4FFDF","#7F7AE6"]},{"name":"blue","colors":["#032973","#0455BF","#2E97F2","#3DADF2","#4BC3F2"]},{"name":"Theme 1","colors":["#997EBF","#97A626","#F28F6B","#D9653B","#0B3C44"]},{"name":"beach","colors":["#96B9D9","#B6D6F2","#343A40","#70818C","#D0E5F2"]},{"name":"Theme 23","colors":["#A67B7B","#2E4159","#BBDDF2","#593D2D","#8C665E"]},{"name":"salty blonde","colors":["#A78B81","#26466C","#7D4344","#E0EBE3","#8E5449"]},{"name":"Theme 7","colors":["#16288C","#D9933D","#BF612A","#A6431F","#F24C3D"]},{"name":"Elephant Juice","colors":["#201926","#F1F0F2","#D9B596","#BF6E50","#F29999"]},{"name":"Citrus Crush","colors":["#F2F1F0","#F2994B","#A68572","#D9501E","#D93030"]},{"name":"alexa","colors":["#732634","#123159","#100F16","#F2CDAC","#A66F3F"]},{"name":"Theme 1","colors":["#866FA6","#21038C","#040DBF","#0511F2","#F29966"]},{"name":"Ocean Dock","colors":["#F2F2F2","#BFBFBF","#8C8887","#734D3F","#401F18"]},{"name":"tan and tea","colors":["#7D4438","#7C9C99","#D1CAC8","#907561","#491F2B"]},{"name":"Nature Run","colors":["#D95578","#260126","#90A686","#808C23","#F2EAD0"]},{"name":"Evening sun","colors":["#4E4C54","#363940","#E6C4A8","#FFC797","#D9996A"]},{"name":"Thinking With Portals","colors":["#FFC079","#D5DBFF","#E8E8E8","#91CAEB","#44B3FA"]},{"name":"Tree Blossoms","colors":["#F2B6B6","#A63740","#8AA66F","#BFB999","#F29999"]},{"name":"Theme 9","colors":["#70927C","#B2D7C8","#92B495","#739491","#8CB1A1"]},{"name":"Theme 8","colors":["#8C7488","#9AA3D9","#CFD9C7","#593F27","#40271E"]},{"name":"Theme 7","colors":["#748C81","#F2AFA0","#A6695B","#401A17","#F28080"]},{"name":"Theme 7","colors":["#B0D9C6","#708C7F","#C9F2D3","#40331B","#261D11"]},{"name":"fruits","colors":["#730E16","#575907","#D9BB62","#D9CBBA","#8C8279"]},{"name":"little girls party ","colors":["#D94A7E","#F272AE","#8C873B","#736B45","#D9B79A"]},
                    {"name":"pastels","colors":["#BF8FAF","#8C5D80","#D2F2BB","#BFBD9B","#8C7D61"]},{"name":"Theme 6","colors":["#F25E6B","#D92949","#F2A172","#F2785C","#F25757"]},{"name":"Theme 11","colors":["#537FA6","#91B7D9","#F2EA7E","#7E82A0","#DD979A"]},{"name":"Theme 4","colors":["#261010","#F27289","#F2BFAC","#593027","#F2A099"]},{"name":"Desert","colors":["#F2F2F2","#F2D06B","#F2C063","#735A3C","#F29849"]},{"name":"Theme 3","colors":["#F2DCDC","#BF5A75","#F2A0B6","#3F3C40","#A6867B"]},{"name":"Dark Red","colors":["#26030A","#0D0409","#0D0804","#260B09","#400404"]},{"name":"Berry","colors":["#F2636F","#F2C6A0","#F28F79","#401410","#F2786D"]},{"name":"Theme 1","colors":["#F2DCDC","#A64B63","#F2A0B6","#3F3C40","#A6867B"]},{"name":"Theme 3","colors":["#958A84","#FFD4BD","#FF6C60","#FDD4D6","#86BBC4"]},{"name":"Theme 1","colors":["#BF0436","#AEA19D","#090301","#000000","#00009C"]},{"name":"Theme 1","colors":["#583D73","#73A9D9","#A0D3F2","#D9B384","#F2B8A2"]},{"name":"Theme 22","colors":["#367DD9","#C4A0C2","#1B594E","#6E7346","#BF6969"]},{"name":"AX006","colors":["#D79A8A","#D8CCC5","#52A5D9","#AA777B","#654849"]},{"name":"Theme 1","colors":["#BF4B54","#DFE5F2","#BFBBA4","#736849","#594936"]},{"name":"Renato Theme","colors":["#A63F48","#566D8C","#7A9FBF","#BF7449","#BF5E49"]},{"name":"Prometheus","colors":["#141F26","#659AA6","#496B73","#B6ECF2","#F2EEAC"]},{"name":"Theme match","colors":["#806577","#332822","#B1CA76","#8D7E5F","#5D4E39"]},{"name":"Theme 4","colors":["#73121A","#F27B13","#D98D62","#734226","#F2600C"]},{"name":"Karlstad Couch Colors","colors":["#594D4F","#707366","#73726C","#595752","#8C8881"]},{"name":"Traditional Colors","colors":["#BC3636","#1C796D","#61C2CC","#F29D52","#AD343A"]},{"name":"Theme 1","colors":["#D9D4D0","#BF9288","#A66963","#B4A698","#402222"]},{"name":"Theme 2","colors":["#505957","#D1D4D7","#535B57","#3E4531","#5B6843"]},{"name":"Theme 2","colors":["#A59093","#4F89A4","#C29E57","#CEA152","#B4ADA5"]},{"name":"Theme 2","colors":["#1B2E40","#AABFBB","#7D7D7F","#BF9C34","#F2B680"]},{"name":"Theme 8","colors":["#DEC4B3","#077368","#F2CC0C","#D9B607","#E7E1DF"]},{"name":"Simple Rom Coloe","colors":["#D91A2A","#9E8D34","#0D0D0D","#FFFFFF","#00A8FC"]},{"name":"duma","colors":["#DF9BF2","#658C5A","#71A64B","#8ABF39","#F2A413"]},{"name":"Theme 1","colors":["#73061A","#BF0628","#D90B42","#402719","#A67563"]},{"name":"Theme 6","colors":["#C1D9D2","#A6800D","#733A09","#8C260F","#590B0B"]},{"name":"Scythei Haikyn","colors":["#63102A","#360D46","#8AACA7","#715A43","#A6523C"]},{"name":"claire","colors":["#CF9787","#F6F5F6","#7F2F56","#CD8CB5","#8C4F49"]},{"name":"Wythern Haikyn","colors":["#31AC73","#E2B231","#204963","#511A8D","#DAD0FF"]},{"name":"PR - Boho4","colors":["#52A7B4","#98E5F1","#6DA668","#658F61","#AC4D4C"]},{"name":"sundancw","colors":["#6D8BA6","#D9D0C5","#D9B88F","#8C2323","#591E1E"]},{"name":"We Are What We Are 2","colors":["#BA9570","#0D0A04","#261D0C","#8C623E","#732C1D"]}]';
        return json_decode($palettes, true);
    }
}

?>