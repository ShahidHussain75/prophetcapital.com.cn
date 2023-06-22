<?php

namespace DataSource;

require_once(DTS_PLUGIN_DIR.'/classes/models/data-source.php');
require_once(DTS_PLUGIN_DIR.'/classes/validate-form.php');
require_once(DTS_PLUGIN_DIR.'/classes/posts-grid.php');

/**
 * Data sources controller
 */
class Sources {
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
                'id' => 'dts-sources-help',
                'title' => __('Getting started', 'data-source'),
                'content' =>
                    '<p>' . __('This is a page you should start with. You can see a list of existing data sources on index page. Create data source of any kind for further use with Tables, Charts, Grids and Maps.', 'data-source') . '</p>' .
                    '<p>' . __('For more details check <a href="' . dts_plugin_url('DataSource - User Manual.pdf') . '">User Manual</a>', 'data-source') . '</p>'
            ));
        }
    }

    /**
     * List of data sources
     * @return array
     */
    public function index()
    {
        $grid = new \DataSource\PostsGrid('dts-data-source');

        return array(
            'grid' => $grid
        );
    }

    /**
     * Create new data source
     * @return array
     */
    function add()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        if (!empty($data) && $request->isAjaxRequest()) {
            check_admin_referer( 'dts-save-data-source' );

            $form = $this->getSourceFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataSource = new \DataSource\DataSourceModel($data);
                $dataSource->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-sources');
                $this->sendAjaxRespone(true, array(), array(), $redirectUrl);
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
            'data-sources',
            dts_plugin_url('admin/js/sources.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        global $wpdb;
        $tables = $wpdb->get_results( "SHOW TABLES", ARRAY_N );

        return array(
            'mysqlTables' => $tables,
            'postTypes' => get_post_types( '', 'names' )
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
            check_admin_referer( 'dts-save-data-source' );

            $form = $this->getSourceFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $dataSource = new \DataSource\DataSourceModel($post);
                $dataSource->setValues($data);
                $dataSource->save();

                $redirectUrl = $this->bootstrap->menuUrl('dts-sources');
                $this->sendAjaxRespone(true, array(), array(), $redirectUrl);
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
            'data-sources',
            dts_plugin_url('admin/js/sources.js'),
            array( 'jquery' ),
            DTS_VERSION,
            'all'
        );

        $dataSource = new \DataSource\DataSourceModel($post);

        global $wpdb;
        $tables = $wpdb->get_results( "SHOW TABLES", ARRAY_N );

        return array(
            'mysqlTables' => $tables,
            'dataSource' => $dataSource,
            'postTypes' => get_post_types( '', 'names' )
        );
    }

    /**
     * Delete data source action
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
                $dataSource = new \DataSource\DataSourceModel(array('id'=>$postId));
                $dataSource->delete();
            }

            $redirectUrl = $this->bootstrap->menuUrl('dts-sources');
            wp_safe_redirect($redirectUrl);
            exit();
        } else {
            wp_die( __( 'Error deleting', 'data-source' ) );
        }
    }

    /**
     * Load data source preview
     */
    public function preview()
    {
        $request = $this->bootstrap->getRequest();
        $type = $request->getPost('type');
        $data = $request->getPost();

        if (!$type) {
            exit(__('Type not set', 'data-source'));
        }

        if ($type == 'mysql') {
            if ((!$data['mysql_table']) && ( !($data['mysql_table']==-1 && $data['mysql_query']) ) ) {
                exit(__('Please select table or input query', 'data-source'));
            }
        } elseif ($type == 'google-spreadsheet') {
            $field = 'google_spreadsheet';
            if (!isset($data[$field]) || !$data[$field]) {
                exit(__('URL not set', 'data-source'));
            }
        } elseif ($type == 'posts') {
            $field = 'wordpress_post_type';
            if (!isset($data[$field])) {
                exit(__('Post type not set', 'data-source'));
            }
        } else {
            $field = $type.'_file';
            if (!isset($data[$field]) || !$data[$field]) {
                exit(__('File not set', 'data-source'));
            }
        }

        $dataSource = new \DataSource\DataSourceModel($data);
        $list = $dataSource->getList();
        $columns = $dataSource->getColumns();

        if (!$columns) {
            exit(__('Unable to render table', 'data-source'));
        }

        $vars = array(
            'list' => $list,
            'columns' => $columns,
            'type' => $type
        );

        if ($type == 'xml') {
            $provider = $dataSource->getProvider();
            $vars['xml_structure'] = $provider->getFileStructure();
            $vars['xml_parent_path'] = $data['xml_parent_path'];
        }

        $this->bootstrap->loadTemplate($vars);
        exit;
    }

    /**
     * Check database connection and retrieve DB tables
     */
    public function checkDatabase()
    {
        $request = $this->bootstrap->getRequest();
        $data = $request->getPost();

        if (!empty($data) && $request->isAjaxRequest()) {
            $form = $this->getDBFormValidator($data);

            if (!$form->isValid()) {
                $this->sendAjaxRespone(false, $form->validate());
            } else {
                $connection = new \wpdb( $data['mysql_user'], $data['mysql_password'], $data['mysql_db'], $data['mysql_host'] );
                $tables = $connection->get_results( "SHOW TABLES", ARRAY_N );
                if ($connection && $tables) {
                    $this->sendAjaxRespone(true, array(), $tables);
                } else {
                    $this->sendAjaxRespone(false, array('mysql_host'=>__('Error accessing database tables', 'data-source')));
                }
            }
        }

        $this->sendAjaxRespone(false);
    }

    private function getDBFormValidator($data)
    {
        $form = new \DataSource\ValidateForm();
        $form->setData($data);

        $form->addField(
            'mysql_host',
            __('MySQL host', 'data-source'),
            array('required')
        );

        $form->addField(
            'mysql_user',
            __('Username', 'data-source'),
            array('required')
        );

        $form->addField(
            'mysql_db',
            __('Database', 'data-source'),
            array('required')
        );

        return $form;
    }

    /**
     * Returns source form validator
     * @param $data
     * @return ValidateForm
     */
    private function getSourceFormValidator($data)
    {
        $form = new \DataSource\ValidateForm();
        $form->setData($data);
        $type = isset($data['type'])?$data['type']:'';

        $form->addField(
            'title',
            __('Title', 'data-source'),
            array('required')
        );

        $form->addField(
            'type',
            __('Data source type', 'data-source'),
            array('required')
        );

        if ($type=='mysql') {
            $form->addField(
                'mysql_table',
                __('MySQL table', 'data-source'),
                array('required')
            );

            if ($data['mysql_table'] == -1) {
                $form->addField(
                    'mysql_query',
                    __('MySQL query', 'data-source'),
                    array('required')
                );
            }
        } elseif ($type == 'csv') {
            $form->addField(
                'csv_file',
                __('CSV File', 'data-source'),
                array('required')
            );
        } elseif ($type == 'xls') {
            $form->addField(
                'xls_file',
                __('XLS File', 'data-source'),
                array('required')
            );
        } elseif ($type == 'google-spreadsheet') {
            $form->addField(
                'google_spreadsheet',
                __('Google Spreadsheet URL', 'data-source'),
                array('required')
            );
        } elseif ($type == 'posts') {
            $form->addField(
                'wordpress_post_type',
                __('Wordpress Post Type', 'data-source'),
                array('required')
            );
        }

        return $form;
    }

    /**
     * Send AJAX response of a specified format
     * @param $status
     * @param array $errors
     * @param string $redirectUrl
     */
    function sendAjaxRespone($status, $errors=array(), $data=array(), $redirectUrl = '')
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