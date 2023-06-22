<?php
/**
 * Plugin bootstrap class
 */

namespace DataSource;

require_once DTS_PLUGIN_DIR . '/classes/request.php';

class Bootstrap {

    private $prefix = 'dts-';
    private $namespace = '\DataSource';
    private $routes = array();
    private $templateVariables = array();
    private $usedShortcodes = array(
        'table' => false,
        'chart' => false,
        'map' => false,
        'grid' => false,
    );

    /**
     * @var string
     */
    public $currentPage = '';

    /**
     * @var string
     */
    public $currentAction = '';

    private $request = null;

    /**
     *
     */
    public function __construct()
    {
        $this->request = \DataSource\Request::getInstance();
        add_action( 'init', array($this, 'init') );
        $this->registerShortcodes();
    }

    /**
     * Register admin menu
     * @return void
     */
    public function registerMenu()
    {
        // Main menu block
        add_object_page( __( 'Data Source', 'data-source' ),
            __( 'Data Source', 'data-source' ),
            'activate_plugins', 'dts-sources',
            array($this, 'loadTemplate'), 'dashicons-data-source');

        $manageSources = $this->addSubMenu(
            'dts-sources',
            __( 'Data Sources', 'data-source' ),
            'activate_plugins',
            'sources'
        );

        // Manage tables submenu item
        $manageTables = $this->addSubMenu(
            'dts-sources',
            __( 'Data Tables', 'data-source' ),
            'activate_plugins',
            'tables'
        );

        // Manage charts submenu item
        $manageCharts = $this->addSubMenu(
            'dts-sources',
            __( 'Data Charts', 'data-source' ),
            'activate_plugins',
            'charts'
        );

        // Manage maps submenu item
        $manageMaps = $this->addSubMenu(
            'dts-sources',
            __( 'Data Maps', 'data-source' ),
            'activate_plugins',
            'maps'
        );

        // Manage grids submenu item
        $manageGrids = $this->addSubMenu(
            'dts-sources',
            __( 'Data Grids', 'data-source' ),
            'activate_plugins',
            'grids'
        );
    }

    /**
     * Registers Wordpress shortcodes
     */
    private function registerShortcodes()
    {
        add_shortcode( 'datatable', array($this, 'dataTableShortCode') );
        add_shortcode( 'datachart', array($this, 'dataChartShortCode') );
        add_shortcode( 'datamap', array($this, 'dataMapShortCode') );
        add_shortcode( 'datagrid', array($this, 'dataGridShortCode') );

        // Register actions for map's iframe
        add_action( 'wp_ajax_nopriv_dts_map', array($this, 'renderDataMap' ));
        add_action( 'wp_ajax_dts_map', array($this, 'renderDataMap' ));
    }

    /**
     * Data Table shortcode
     * @param $atts
     * @return string|void
     */
    public function dataTableShortCode($atts)
    {
        $a = shortcode_atts( array(
            'id' => 0,
        ), $atts );

        if (!$a['id']) {
            return __('Table not set', 'data-source');
        }

        $this->usedShortcodes['table'] = true;

        require_once(DTS_PLUGIN_DIR.'/controllers/tables.php');
        $tablesController = new \DataSource\Tables($this);

        wp_register_script(
            'data-tables',
            dts_plugin_url('js/DataTables/media/js/jquery.dataTables.js'),
            array( 'jquery' ),
            DTS_VERSION,
            false
        );

        wp_register_script(
            'data-tables-responsive',
            dts_plugin_url('js/DataTables/extensions/Responsive/js/dataTables.responsive.min.js'),
            array( 'data-tables' ),
            DTS_VERSION,
            false
        );

        wp_register_style(
            'data-tables-css',
            dts_plugin_url( 'js/DataTables/media/css/jquery.dataTables.min.css' ),
            array(),
            DTS_VERSION,
            'all'
        );

        wp_register_style(
            'data-tables-responsive-css',
            dts_plugin_url( 'js/DataTables/extensions/Responsive/css/dataTables.responsive.css' ),
            array(),
            DTS_VERSION,
            'all'
        );

        ob_start();
        $tablesController->renderTable($a['id']);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * DataChart shortcode
     * @param $atts
     * @return string|void
     */
    public function dataChartShortCode($atts)
    {
        $a = shortcode_atts( array(
            'id' => 0,
        ), $atts );

        if (!$a['id']) {
            return __('Chart not set', 'data-source');
        }

        $this->usedShortcodes['chart'] = true;

        require_once(DTS_PLUGIN_DIR.'/controllers/charts.php');
        $chartsController = new \DataSource\Charts($this);

        wp_register_script(
            'google-charts-js-api',
            'https://www.google.com/jsapi',
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        ob_start();
        $chartsController->renderChart($a['id']);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * DataMap shortcode
     * @param $atts
     * @return string|void
     */
    public function dataMapShortCode($atts)
    {
        $a = shortcode_atts( array(
            'id' => 0,
        ), $atts );

        if (!$a['id']) {
            return __('Map ID not set', 'data-source');
        }

        require_once(DTS_PLUGIN_DIR.'/classes/models/data-map.php');
        $dataMap = new \DataSource\DataMapModel($a['id']);

        return '<iframe width="'.$dataMap->width.'" height="'.$dataMap->height.'" src="'. add_query_arg('map_id', $a['id'], add_query_arg( 'action', 'dts_map', admin_url( 'admin-ajax.php' ) ) ) .'"></iframe>';
    }

    /**
     * Render map within iframe
     * @return string|void
     */
    public function renderDataMap()
    {
        header('X-Frame-Options: ALLOWALL');
        if (!isset($_GET['map_id'])) {
            return __('Map ID not set', 'data-source');
        }

        $this->usedShortcodes['map'] = true;

        require_once(DTS_PLUGIN_DIR.'/controllers/maps.php');
        $mapsController = new \DataSource\Maps($this);

        wp_register_script(
            'google-charts-js-api',
            'https://www.google.com/jsapi',
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $mapId = (int)$_GET['map_id'];

        $dataMap = $mapsController->renderMap($mapId);
        exit;
    }

    /**
     * Data Grid shortcode
     * @param $atts
     * @return string|void
     */
    public function dataGridShortCode($atts)
    {
        $a = shortcode_atts( array(
            'id' => 0,
        ), $atts );

        if (!$a['id']) {
            return __('Grid ID not set', 'data-source');
        }

        $this->usedShortcodes['grid'] = true;

        require_once(DTS_PLUGIN_DIR.'/controllers/grids.php');
        $gridsController = new \DataSource\Grids($this);

        wp_register_style(
            'data-grid-css',
            dts_plugin_url( 'css/data-grid.css' ),
            array(),
            DTS_VERSION,
            'all'
        );

        ob_start();
        $gridsController->renderGrid($a['id']);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    }

    /**
     * Print script for used shortcodes
     */
    public function printScripts()
    {
        if ($this->usedShortcodes['table']) {
            wp_print_scripts('data-tables');
            wp_print_scripts('data-tables-responsive');
            wp_print_styles('data-tables-css');
            wp_print_styles('data-tables-responsive-css');
        }

        if ($this->usedShortcodes['chart']) {
            wp_print_scripts('google-charts-js-api');
        }

        if ($this->usedShortcodes['map']) {
            wp_print_scripts('google-charts-js-api');
        }

        if ($this->usedShortcodes['grid']) {
            wp_print_styles('data-grid-css');
        }
    }

    /**
     * Add admin submenu
     * @param $parent
     * @param $title
     * @param $permission
     * @param $controller
     * @param string $action
     * @return false|string
     */
    private function addSubMenu($parent, $title, $permission, $controller, $action='')
    {
        $path = $this->prefix.$controller.($action?'&action='.$action:'');

        $name = add_submenu_page(
            $parent,
            $title, $title,
            $permission, $path,
            array($this, 'loadTemplate')
        );

        $this->routes[$name] = array(
            'controller' => $controller,
            'action' => $action?$action:'index'
        );

        add_action( 'load-' . $name, array($this, 'route') );

        return $name;
    }

    /**
     * Load admin panel specific hooks
     */
    public function loadAdmin()
    {
        // Register menu
        add_action( 'admin_menu', array($this, 'registerMenu'));
    }

    /**
     * Router
     */
    public function route()
    {
        $page = isset($_GET['page'])?$_GET['page']:null;
        $action = (isset($_GET['action']) && (!empty($_GET['action'])))?$_GET['action']:'index';
        if (!$page) return;

        $page = substr($page, strlen($this->prefix));

        $this->currentPage = $page;
        $this->currentAction = $action;

        $className = $this->namespace.'\\'.ucfirst($page);
        $path = DTS_PLUGIN_DIR.'/controllers/'.$page.'.php';

        if (file_exists($path)) {
            require_once($path);
            if (class_exists($className)) {
                $controller = new $className($this);
                $this->templateVariables = $controller->$action();
            }
        }
        return;
    }

    /**
     * Render template
     * @param array $vars
     * @param null $page
     * @param null $template
     */
    public function loadTemplate($vars = array(), $page=null, $template=null)
    {
        if (!$page) {
            $page = isset($_GET['page'])?$_GET['page']:null;
        }

        if (!$template) {
            $template = (isset($_GET['action']) && !empty($_GET['action']))?$_GET['action']:'index';
        }

        if (!$page) return;

        $controller = substr($page, strlen($this->prefix));

        if (!$vars || !count($vars)) {
            $vars = $this->templateVariables;
        }
        if (isset($vars)) {
            extract($vars);
        }
        include(DTS_PLUGIN_DIR.'/templates/'.$controller.'/'.$template.'.phtml');
    }

    /**
     * Get menu URL
     * @param $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public function menuUrl($controller, $action='', $params=array())
    {
        $url = menu_page_url( $controller, false );

        if ($action) {
            $url = add_query_arg(array( 'action' => $action ), $url);
        }

        if (count($params)) {
            $url = add_query_arg($params, $url);
        }

        return esc_url( $url );
    }

    /**
     * Init data source, register post types
     */
    public function init()
    {
        register_post_type( 'dts-data-source', array(
            'labels' => array(
                'name' => __( 'Data Source', 'data-source' ),
                'singular_name' => __( 'Data Source', 'data-source' ) ),
            'rewrite' => false,
            'query_var' => false )
        );
        register_post_type( 'dts-data-table', array(
                'labels' => array(
                    'name' => __( 'Data Table', 'data-source' ),
                    'singular_name' => __( 'Data Table', 'data-source' ) ),
                'rewrite' => false,
                'query_var' => false )
        );
        register_post_type( 'dts-data-grid', array(
                'labels' => array(
                    'name' => __( 'Data Grid', 'data-source' ),
                    'singular_name' => __( 'Data Grid', 'data-source' ) ),
                'rewrite' => false,
                'query_var' => false )
        );
        register_post_type( 'dts-data-chart', array(
                'labels' => array(
                    'name' => __( 'Data Chart', 'data-source' ),
                    'singular_name' => __( 'Data Chart', 'data-source' ) ),
                'rewrite' => false,
                'query_var' => false )
        );
        register_post_type( 'dts-data-map', array(
                'labels' => array(
                    'name' => __( 'Data Map', 'data-source' ),
                    'singular_name' => __( 'Data Map', 'data-source' ) ),
                'rewrite' => false,
                'query_var' => false )
        );
    }

    /**
     * Returns request object
     * @return Request|null
     */
    public function getRequest()
    {
        return $this->request;
    }
}

?>