<?php

namespace DataSource;

require_once(DTS_PLUGIN_DIR.'/classes/models/data-source.php');
require_once(DTS_PLUGIN_DIR.'/classes/models/data-grid.php');
require_once(DTS_PLUGIN_DIR.'/classes/grids/Grid.php');
require_once(DTS_PLUGIN_DIR.'/classes/validate-form.php');
require_once(DTS_PLUGIN_DIR.'/classes/posts-grid.php');

/**
 * Data grids controller
 */

class Grids {
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
                'id' => 'dts-grids-help',
                'title' => __('Getting started', 'data-source'),
                'content' =>
                    '<p>' . __('You can see list of existing grids on index page. In order to put any of them to your website just copy short code and paste it in page or post editor.', 'data-source') . '</p>' .
                    '<p>' . __('This section allows you to create and edit grids using your data sources. You can add placeholders you wish to display in Placeholders section, select or configure it\'s look in Grid Styles box and choose other options in grid options.', 'data-source') . '</p>' .
                    '<p>' . __('For more details check <a href="' . dts_plugin_url('DataSource - User Manual.pdf') . '">User Manual</a>', 'data-source') . '</p>'
            ));
        }
    }

    /**
     * List of grids
     * @return array
     */
    public function index()
    {
        $grid = new \DataSource\PostsGrid('dts-data-grid');

        return array(
            'grid' => $grid
        );
    }

    /**
     * Create new grid
     * @return array
     */
    function add()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-grid' );

            $form = $this->getGridFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataGrid = new \DataSource\DataGridModel($data);
                $dataGrid->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-grids');
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
            'data-sources-grids',
            dts_plugin_url('admin/js/grids.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'dataSources' => $dataSources['list']
        );
    }

    /**
     * Edit grid
     * @return array
     */
    function edit()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        $post = $request->getRequest('post');

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-grid' );

            $form = $this->getGridFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataGrid = new \DataSource\DataGridModel($post);
                $dataGrid->setValues($data);
                $dataGrid->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-grids');
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
            'data-sources-grids',
            dts_plugin_url('admin/js/grids.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $dataGrid = new \DataSource\DataGridModel($post);

        $grid = new \DataSource\PostsGrid('dts-data-source');
        $dataSources = $grid->getList(1000);

        return array(
            'dataSources' => $dataSources['list'],
            'dataGrid' => $dataGrid
        );
    }

    /**
     * Delete grid
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
                $dataGrid = new \DataSource\DataGridModel(array('id'=>$postId));
                $dataGrid->delete();
            }

            $redirectUrl = $this->bootstrap->menuUrl('dts-grids');
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
     * Load grid preview
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

        $grid = new \DataSource\Grid($dataSource);

        $template = stripslashes($data['grid_template']);
        $values = $grid->getGrid($template, $data['sortby'], $data['sortorder']);

        $options = array(
            'columns_num' => $data['columns_num']
        );

        $vars = array(
            'values' => $values,
            'options' => $options,
            'backgrounds' => isset($data['background'])?$data['background']:array(),
            'border' => $data['border'],
            'padding' => $data['padding'],
            'margin' => $data['margin']
        );

        $this->bootstrap->loadTemplate($vars);
        exit;
    }

    /**
     * Render grid for public side
     * @param $id
     */
    public function renderGrid($id)
    {
        $dataGrid = new \DataSource\DataGridModel($id);

        $dataSource = new \DataSource\DataSourceModel($dataGrid->datasource);
        $grid = new \DataSource\Grid($dataSource);

        $template = stripslashes($dataGrid->grid_template);
        $values = $grid->getGrid($template, $dataGrid->sortby, $dataGrid->sortorder);

        $options = array(
            'columns_num' => $dataGrid->columns_num
        );

        $vars = array(
            'values' => $values,
            'options' => $options,
            'backgrounds' => $dataGrid->background,
            'border' => $dataGrid->border,
            'padding' => $dataGrid->padding,
            'margin' => $dataGrid->margin
        );

        $this->bootstrap->loadTemplate($vars, 'dts-grids', 'renderGrid');
        return;
    }

    /**
     * Validate data grid form
     * @param $data
     * @return ValidateForm
     */
    private function getGridFormValidator($data)
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
}

?>