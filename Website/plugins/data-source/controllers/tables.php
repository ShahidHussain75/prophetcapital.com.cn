<?php

namespace DataSource;

require_once(DTS_PLUGIN_DIR.'/classes/models/data-source.php');
require_once(DTS_PLUGIN_DIR.'/classes/models/data-table.php');
require_once(DTS_PLUGIN_DIR.'/classes/validate-form.php');
require_once(DTS_PLUGIN_DIR.'/classes/posts-grid.php');

/**
 * Data tables controller
 */

class Tables {
    /**
     * Access to bootstrap object
     */
    private $bootstrap = null;

    public function __construct($bootstrap=null)
    {
        $this->bootstrap = $bootstrap;

        if ( is_admin() ) {
            $screen = get_current_screen();
            $screen->add_help_tab(array(
                'id' => 'dts-table-help',
                'title' => __('Getting started', 'data-source'),
                'content' =>
                    '<p>' . __('You can see list of existing tables on index page. In order to put any of them on front end just copy short code and paste in page or post editor.', 'data-source') . '</p>' .
                    '<p>' . __('This section allows you to create tables using your data sources. You can select columns you wish to display in Table Columns section, select or configure it\'s look in Table Design box and choose other options in table options.', 'data-source') . '</p>' .
                    '<p>' . __('For more details check <a href="' . dts_plugin_url('DataSource - User Manual.pdf') . '">User Manual</a>', 'data-source') . '</p>'
            ));
        }
    }

    /**
     * List of data tables
     * @return array
     */
    public function index()
    {
        $grid = new \DataSource\PostsGrid('dts-data-table');

        return array(
            'grid' => $grid
        );
    }

    /**
     * Create new data table
     * @return array
     */
    function add()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-table' );

            $form = $this->getTableFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataTable = new \DataSource\DataTableModel($data);
                $dataTable->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-tables');
                $this->sendAjaxRespone(true, null, null, $redirectUrl);
            }
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'data-sources-codemirror',
            dts_plugin_url('js/codemirror/codemirror-compressed.js'),
            array(),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_style(
            'data-sources-codemirror-css',
            dts_plugin_url( 'js/codemirror/lib/codemirror.css' ),
            array(),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'data-sources-spectrum',
            dts_plugin_url('admin/js/spectrum/spectrum.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'data-sources-tables',
            dts_plugin_url('admin/js/tables.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'dataSources' => $dataSources['list'],
            'fonts' => $this->getFontsList(),
            'languages' => $this->getLanguages()
        );
    }

    /**
     * Edit data source
     * @return array
     */
    function edit()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        $post = $request->getRequest('post');

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-table' );

            $form = $this->getTableFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataTable = new \DataSource\DataTableModel($post);
                $dataTable->setValues($data);
                $dataTable->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-tables');
                $this->sendAjaxRespone(true, null, null, $redirectUrl);
            }
        }

        wp_enqueue_media();

        wp_enqueue_script(
            'data-sources-codemirror',
            dts_plugin_url('js/codemirror/codemirror-compressed.js'),
            array(),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_style(
            'data-sources-codemirror-css',
            dts_plugin_url( 'js/codemirror/lib/codemirror.css' ),
            array(),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'data-sources-spectrum',
            dts_plugin_url('admin/js/spectrum/spectrum.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        wp_enqueue_script(
            'data-sources-tables',
            dts_plugin_url('admin/js/tables.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $dataTable = new \DataSource\DataTableModel($post);

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'dataSources' => $dataSources['list'],
            'dataTable' => $dataTable,
            'fonts' => $this->getFontsList(),
            'languages' => $this->getLanguages()
        );
    }

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
                $dataTable = new \DataSource\DataTableModel(array('id'=>$postId));
                $dataTable->delete();
            }

            $redirectUrl = $this->bootstrap->menuUrl('dts-tables');
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
        $post = $request->getRequest('post');

        if (!$dataSourceId) {
            exit(__('Columns not found', 'data-source'));
        }

        $dataSource = new \DataSource\DataSourceModel($dataSourceId);
        $columns = $dataSource->getColumns();

        if (!$columns) {
            exit(__('Columns not found', 'data-source'));
        }

        if ($post) {
            $dataTable = new \DataSource\DataTableModel($post);
        }

        if ($post && ($dataTable->datasource == $dataSourceId)) {
            $tempColumns = array();
            foreach ($dataTable->column as $k => $col) {
                $tempColumns[$k] = $columns[$k];
            }

            foreach ($tempColumns as $i => $col) {
                $tempColumns[$i]['show'] = isset($dataTable->column[$i]['show'])?$dataTable->column[$i]['show']:'';
                $tempColumns[$i]['label'] = isset($dataTable->column[$i]['label'])?$dataTable->column[$i]['label']:'';
                $tempColumns[$i]['type'] = isset($dataTable->column[$i]['type'])?$dataTable->column[$i]['type']:'';
                $tempColumns[$i]['sortable'] = isset($dataTable->column[$i]['sortable'])?$dataTable->column[$i]['sortable']:'';
            }

            foreach ($columns as $i => $col) {
                if (!isset($dataTable->column[$i])) {
                    $tempColumns[$i] = $col;
                }
            }
            $columns = $tempColumns;
        } else {
            foreach ($columns as $n=>$col) {
                $columns[$n]['show']=1;
            }
        }

        $vars = array(
            'columns' => $columns,
            'types' => array(
                'text' => 'Text',
                'number' => 'Number',
                'date' => 'Date',
                'image' => 'Image',
                'url' => 'URL',
            )
        );

        $this->bootstrap->loadTemplate($vars);
        exit;
    }

    /**
     * Load table preview
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
        $list = $dataSource->getList();
        $columns = $dataSource->getColumns();
        $selectedColumns = array();

        if (!$list) {
            exit(__('Unable to render table', 'data-source'));
        }

        foreach ($data['column'] as $k=>$column) {
            if ($column['show']) {
                $col = $columns[$k];
                $col['label'] = $column['label'];
                $col['type'] = $column['type'];
                $selectedColumns[] = $col;
            }
        }

        if ($data['table_design_type'] == 'dts_existing') {
            $styles = $this->getStyles($data['table_design_type'], $data['dts_existing_table_style']);
        } elseif ($data['table_design_type'] == 'dts_class') {
            $styles = $this->getStyles($data['table_design_type'], $data['table_class']);
        } else {
            $styles = $this->getStyles($data['table_design_type'], $data['design']);
        }

        $vars = array(
            'list' => $list,
            'columns' => $selectedColumns,
            'styles' => $styles,
            'customCSS' => $data['table_css']
        );

        $this->bootstrap->loadTemplate($vars);
        exit;
    }

    /**
     * Render table for public side
     * @param $id
     */
    public function renderTable($id)
    {
        $dataTable = new \DataSource\DataTableModel($id);

        if (!$dataTable->id) {
            echo(__('Unable to render table', 'data-source'));
            return;
        }

        $dataSource = new \DataSource\DataSourceModel($dataTable->datasource);

        if (!$dataSource->id) {
            echo(__('Unable to render table', 'data-source'));
            return;
        }

        $list = $dataSource->getList(null);
        $columns = $dataSource->getColumns();
        $selectedColumns = array();

        if (!$list) {
            echo(__('Unable to render table', 'data-source'));
            return;
        }

        $sortby = false;
        $i = 0;
        foreach ($dataTable->column as $k=>$column) {
            if ($column['show']) {
                $col = $columns[$k];
                $col['label'] = isset($column['label'])?$column['label']:'';
                $col['type'] = isset($column['type'])?$column['type']:'';
                $col['sortable'] = isset($column['sortable'])?$column['sortable']:'';
                $selectedColumns[] = $col;

                if (($dataTable->sortby) && ($dataTable->sortby == $columns[$k]['name'])) {
                    $sortby = $i;
                }
                $i++;
            }
        }

        if ($dataTable->table_design_type == 'dts_existing') {
            $styles = $this->getStyles($dataTable->table_design_type, $dataTable->dts_existing_table_style);
        } elseif ($dataTable->table_design_type == 'dts_class') {
            $styles = $this->getStyles($dataTable->table_design_type, $dataTable->table_class);
        } else {
            $styles = $this->getStyles($dataTable->table_design_type, $dataTable->design);
        }

        $vars = array(
            'list' => $list,
            'columns' => $selectedColumns,
            'styles' => $styles,
            'customCSS' => $dataTable->table_css,
            'dataTable' => $dataTable,
            'sortby' => $sortby
        );

        $this->bootstrap->loadTemplate($vars, 'dts-tables', 'renderTable');
        return;
    }

    /**
     * Save table styles
     */
    public function saveStyles()
    {
        $request = $this->bootstrap->getRequest();
        $designData = $request->getPost('design');

        if (!$designData || !count($designData)) {
            $this->sendAjaxRespone(false, array(__('Some error occured', 'data-source')));
            exit;
        }

        if (empty($designData['name'])) {
            $this->sendAjaxRespone(false, array(__('Name is required', 'data-source')));
            exit;
        }

        $designs = array();

        if ( get_option( '_dts_table_styles' ) !== false ) {
            $designs = json_decode( get_option( '_dts_table_styles' ), true );
        }

        $designs[$designData['name']] = $designData;
        update_option( '_dts_table_styles', dts_json_encode($designs) );

        $this->sendAjaxRespone(true, null, $designs);
    }

    /**
     * Load table styles
     */
    public function loadStyles()
    {
        $designs = array();

        if ( get_option( '_dts_table_styles' ) !== false ) {
            $designs = json_decode( get_option( '_dts_table_styles' ), true );
        }

        $this->sendAjaxRespone(true, null, $designs);
    }

    /**
     * Get table styles configuration for template
     * @param $type
     * @param $design
     * @return array
     */
    private function getStyles($type, $design)
    {
        $styles = array(
            'id' => uniqid('preview'),
            'css' => '',
            'class' => '',
            'font' => ''
        );

        if ($type == 'dts_existing') {
            $designs = array();
            if ( get_option( '_dts_table_styles' ) !== false ) {
                $designs = json_decode( get_option( '_dts_table_styles' ), true );
            }
            if (isset($designs['table_font']) && $designs['table_font']) {
                $styles['font'] = $designs['table_font'];
            }
            $styles['css'] = $this->generateCSS($styles['id'], isset($designs[$design])?$designs[$design]:array());
        } elseif ($type == 'dts_custom') {
            if ($design['table_font']) {
                $styles['font'] = $design['table_font'];
            }
            $styles['css'] = $this->generateCSS($styles['id'], $design);
        } elseif ($type == 'dts_class') {
            $styles['class'] = $design;
        }

        return $styles;
    }

    /**
     * Generates CSS based on specified table styles
     * @param $id
     * @param $styles
     * @return string
     */
    private function generateCSS($id, $styles)
    {
        $css = array();
        $fonts = $this->getFontsList();

        $rules = array(
            'table_border' => array(
                'rule' => '[id] table',
                'style' => 'border: 1px solid %s'
            ),

            'header_background' => array(
                'rule' => '[id] table th',
                'style' => 'background-color: %s'
            ),

            'header_text' => array(
                'rule' => '[id] table th',
                'style' => 'color: %s'
            ),

            'body_text' => array(
                'rule' => '[id] table td',
                'style' => 'color: %s'
            ),

            'body_even_background' => array(
                'rule' => '[id] table tr td',
                'style' => 'background: %s'
            ),

            'body_odd_background' => array(
                'rule' => '[id] table tr:nth-of-type(2n+1) td',
                'style' => 'background: %s'
            ),

            'body_hover_background' => array(
                'rule' => '[id] table tr:hover td',
                'style' => 'background: %s'
            ),

            'pagination_color' => array(
                'rule' => '[id] div.dts-pagination, [id] a.paginate_button',
                'style' => 'color: %s'
            ),

            'pagination_background' => array(
                'rule' => '[id] .dts-pagination-selected, [id] a.paginate_button.current',
                'style' => 'background: %s'
            ),

            'pagination_selected_color' => array(
                'rule' => '[id] .dts-pagination-selected, [id] a.paginate_button.current',
                'style' => 'color: %s !important'
            ),

            'table_borders_type_horizontal' => array(
                'rule' => '[id] table td, [id] table th',
                'style' => 'border-top: 1px solid %s'
            ),

            'table_borders_type_all' => array(
                'rule' => '[id] table td, [id] table th',
                'style' => 'border: 1px solid %s'
            ),

            'table_font' => array(
                'rule' => '[id] table td, [id] table th',
                'style' => 'font-family: %s'
            )
        );

        foreach ($styles as $attr=>$val) {
            if (($val) && ($attr != 'name')) {
                $sAttr = $attr;
                $sVal = $val;
                if ($attr == 'table_borders_type') {
                    $sAttr = $attr . '_' . $val;
                    $sVal = $styles['table_border'];
                } elseif ($attr == 'table_font') {
                    $sVal = $fonts[$val];
                }
                $item = str_replace('[id]', '#'.$id, $rules[$sAttr]['rule']).' {';
                $item .= sprintf($rules[$sAttr]['style'], $sVal);
                $item .= '}';

                $css[] = $item;
            }
        }

        return implode("\n", $css);
    }

    /**
     * Validate data table form
     * @param $data
     * @return ValidateForm
     */
    private function getTableFormValidator($data)
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

        $form->addField(
            'table_design_type',
            __('Table design', 'data-source'),
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

    private function getFontsList()
    {
        $fonts = array(
            'Open Sans' => "'Open Sans', sans-serif;",
            'Roboto' => "'Roboto', sans-serif;",
            'Lato' => "'Lato', sans-serif;",
            'Droid Sans' => "'Droid Sans', sans-serif;",
            'Raleway' => "'Raleway', sans-serif;",
            'Noto Sans' => "'Noto Sans', sans-serif;",
            'Titillium Web' => "'Titillium Web', sans-serif;",
            'Hind' => "'Hind', sans-serif;",
            'Josefin Sans' => "'Josefin Sans', sans-serif;"
        );

        return $fonts;
    }

    private function getLanguages()
    {
        $languages = array(
            'English',
            'Afrikaans',
            'Albanian',
            'Arabic',
            'Armenian',
            'Azerbaijan',
            'Bangla',
            'Basque',
            'Belarusian',
            'Bulgarian',
            'Catalan',
            'Chinese-traditional',
            'Chinese',
            'Croatian',
            'Czech',
            'Danish',
            'Dutch',
            'Estonian',
            'Filipino',
            'Finnish',
            'French',
            'Galician',
            'Georgian',
            'German',
            'Greek',
            'Gujarati',
            'Hebrew',
            'Hindi',
            'Hungarian',
            'Icelandic',
            'Indonesian-Alternative',
            'Indonesian',
            'Irish',
            'Italian',
            'Japanese',
            'Korean',
            'Kyrgyz',
            'Latvian',
            'Lithuanian',
            'Macedonian',
            'Malay',
            'Mongolian',
            'Nepali',
            'Norwegian',
            'Persian',
            'Polish',
            'Portuguese-Brasil',
            'Portuguese',
            'Romanian',
            'Russian',
            'Serbian',
            'Sinhala',
            'Slovak',
            'Slovenian',
            'Spanish',
            'Swahili',
            'Swedish',
            'Tamil',
            'Thai',
            'Turkish',
            'Ukranian',
            'Urdu',
            'Uzbek',
            'Vietnamese'
        );

        return $languages;
    }
}

?>