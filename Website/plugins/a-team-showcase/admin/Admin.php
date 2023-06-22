<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://looks-awesome.com
 * @since      1.0.0
 *
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/admin
 * @author     Looks Awesome <hello@looks-awesome.com>
 */



class A_Team_Back_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


    /**
     * Plugin running mode. By default is dev
     *
     * @var
     */
    private $mode;


    private $dir;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_label, $version ) {
        $env = null;
        if(file_exists(plugin_dir_path(__DIR__) . 'env.json')){
            $env = json_decode(file_get_contents(plugin_dir_path(__DIR__) . 'env.json'), true);
        }

		$this->plugin_name = $plugin_name;
		$this->plugin_label = $plugin_label;
		$this->version = $version;
		$this->mode = $env['mode'] ?: 'dev';
        $this->dir  = plugin_dir_url(__FILE__);
        // add menu item to admin sidebar
        add_action('admin_menu', array($this, 'add_admin_menu'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in A_Team_Showcase_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The A_Team_Showcase_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
            if($this->mode == 'dev') {
                /* Bootstrap styles */
                wp_enqueue_style('bootstrap', $this->dir . 'css/vendor/bootstrap/bootstrap.css', array(), '3.3.5', 'all');
                wp_enqueue_style('a-bootflat', $this->dir . 'css/vendor/bootstrap/bootflat.css', array(), '2.0.4', 'all');
                wp_enqueue_style('color-picker', $this->dir . 'css/vendor/bootstrap/bootstrap-colorpicker.css', array(), '2.0.4', 'all');
                wp_enqueue_style('selectize', $this->dir . 'css/vendor/selectize/selectize.css', array(), '2.0.4', 'all');
                /* Formstone */
                wp_enqueue_style('formstone-dropdown', $this->dir . 'css/vendor/formstone/dropdown.css', array(), '0.7.0', 'all');
                wp_enqueue_style('formstone-lightbox', $this->dir . 'css/vendor/formstone/lightbox.css', array(), '0.7.0', 'all');
                /* Plugin styles */
                wp_enqueue_style($this->plugin_name, $this->dir . 'css/a-team-showcase-admin.css', array(), $this->version, 'all');
                /* Shortcode styles */
                wp_enqueue_style(A_Team_App_Plugin::$plugin_name . '-shortcode', plugin_dir_url(__DIR__) . 'public/css/a-team-showcase-public.css', array(), $this->version, 'all');
                wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), $this->version, 'all');
                wp_enqueue_style('font-montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700', array(), $this->version, 'all');
                wp_enqueue_style('font-noto-sans', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,400italic,700,700italic', array(), $this->version, 'all');
            }else{
                wp_enqueue_style('all', $this->dir . 'css/all.min.css', array(), $this->version, 'all');
                wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), $this->version, 'all');
                wp_enqueue_style('font-montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700', array(), $this->version, 'all');
                wp_enqueue_style('font-noto-sans', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,400italic,700,700italic', array(), $this->version, 'all');
            }
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in A_Team_Showcase_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The A_Team_Showcase_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
            if($this->mode == 'dev') {
                /* Register plugin scripts */
                wp_register_script($this->plugin_name . '-system', $this->dir . 'js/app/system.js', array('jquery'), $this->version, false);
                wp_register_script($this->plugin_name . '-main', $this->dir . 'js/app/main.js', array('jquery', 'underscore', 'backbone'), $this->version, false);

                /* Vendor libs */
                wp_register_script('backbone-stickit', $this->dir . 'js/vendor/backbone/backbone.stickit.js', null, $this->version, false);
                wp_register_script('backbone-nested', $this->dir . 'js/vendor/backbone/backbone.nested.js', null, $this->version, false);
                wp_register_script('formstone', $this->dir . 'js/vendor/formstone/core.js', null, $this->version, false);
                wp_register_script('formstone-touch', $this->dir . 'js/vendor/formstone/touch.js', array('formstone'), $this->version, false);
                wp_register_script('formstone-transition', $this->dir . 'js/vendor/formstone/transition.js', array('formstone'), $this->version, false);
                wp_register_script('formstone-dropdown', $this->dir . 'js/vendor/formstone/dropdown.js', array('formstone'), $this->version, false);
                wp_register_script('formstone-lightbox', $this->dir . 'js/vendor/formstone/lightbox.js', array('formstone'), $this->version, false);
                wp_register_script('bootstrap-colorpicker', $this->dir . 'js/vendor/bootstrap/bootstrap-colorpicker.min.js', null, $this->version, false);
                wp_register_script('selectize', $this->dir . 'js/vendor/selectize/selectize.js', null, $this->version, false);
                wp_register_script('awesome-panel', $this->dir . 'js/vendor/awesome/panel.js', array('jquery'), $this->version, false);

                /* App modules */
                wp_register_script($this->plugin_name . '-drag-n-drop', $this->dir . 'js/app/drag-n-drop.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-ajax', $this->dir . 'js/app/ajax.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-form', $this->dir . 'js/app/form.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-tooltip', $this->dir . 'js/app/tooltip.js', null, $this->version, false);

                /* App actions */
                wp_register_script($this->plugin_name . '-team-actions', $this->dir . 'js/app/team-actions.js', array('awesome-panel'), $this->version, false);
                wp_register_script($this->plugin_name . '-employer-actions', $this->dir . 'js/app/employer-actions.js', array('awesome-panel'), $this->version, false);

                /* App models */
                wp_register_script($this->plugin_name . '-model-team', $this->dir . 'js/models/team.js', null, $this->version, false);

                /* App views */
                wp_register_script($this->plugin_name . '-team', $this->dir . 'js/views/team.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-team-form', $this->dir . 'js/views/team/form.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-team-preview', $this->dir . 'js/views/team/preview.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-team-tooltip-photo', $this->dir . 'js/views/team/tooltip-photo.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-team-tooltip-font', $this->dir . 'js/views/team/tooltip-font.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-team-tooltip-divider', $this->dir . 'js/views/team/tooltip-divider.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-team-tooltip-social', $this->dir . 'js/views/team/tooltip-social.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-employer', $this->dir . 'js/views/employer.js', null, $this->version, false);
                wp_register_script($this->plugin_name . '-employer-form', $this->dir . 'js/views/employer/form.js', null, $this->version, false);


                /* Enqueue all this shit */

                wp_enqueue_script($this->plugin_name . '-system');
                wp_enqueue_script($this->plugin_name . '-main');

                wp_enqueue_script('backbone-stickit');
                wp_enqueue_script('backbone-nested');
                wp_enqueue_script('formstone');
                wp_enqueue_script('formstone-touch');
                wp_enqueue_script('formstone-transition');
                wp_enqueue_script('formstone-dropdown');
                wp_enqueue_script('formstone-lightbox');
                wp_enqueue_script('bootstrap-colorpicker');
                wp_enqueue_script('selectize');
                wp_enqueue_script('awesome-panel');

                wp_enqueue_script($this->plugin_name . '-drag-n-drop');
                wp_enqueue_script($this->plugin_name . '-ajax');
                wp_enqueue_script($this->plugin_name . '-form');
                wp_enqueue_script($this->plugin_name . '-tooltip');

                wp_enqueue_script($this->plugin_name . '-team-actions');
                wp_enqueue_script($this->plugin_name . '-employer-actions');

                wp_enqueue_script($this->plugin_name . '-model-team');

                wp_enqueue_script($this->plugin_name . '-team');
                wp_enqueue_script($this->plugin_name . '-team-form');
                wp_enqueue_script($this->plugin_name . '-team-preview');
                wp_enqueue_script($this->plugin_name . '-team-tooltip-photo');
                wp_enqueue_script($this->plugin_name . '-team-tooltip-font');
                wp_enqueue_script($this->plugin_name . '-team-tooltip-divider');
                wp_enqueue_script($this->plugin_name . '-team-tooltip-social');
                wp_enqueue_script($this->plugin_name . '-employer');
                wp_enqueue_script($this->plugin_name . '-employer-form');
            }else{
                wp_register_script('all', $this->dir . 'js/all.min.js', array('jquery', 'underscore', 'backbone'), $this->version, false);
                wp_enqueue_script('all');
            }

            // Include WP libraries
            wp_enqueue_media();
            wp_enqueue_script( 'wp-ajax-response' );
		}
	}

    /**
     * Register menu in admin area
     */
    public function add_admin_menu(){
        $plugin_data = get_plugin_data(__FILE__);
        $wp_version = (float) get_bloginfo('version');
        if($wp_version > 3.8){ // From 3.8 WP supports SVG icons
            $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sbnM6c2tldGNoPSJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnMiPiAgICAgICAgPHRpdGxlPnNpZGViYXJfd3BfaWNvbjwvdGl0bGU+ICAgIDxkZXNjPkNyZWF0ZWQgd2l0aCBTa2V0Y2guPC9kZXNjPiAgICA8ZGVmcz48L2RlZnM+ICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiIHNrZXRjaDp0eXBlPSJNU1BhZ2UiPiAgICAgICAgPGcgaWQ9InNpZGViYXJfd3BfaWNvbiIgc2tldGNoOnR5cGU9Ik1TQXJ0Ym9hcmRHcm91cCIgZmlsbD0iIzlFQTNBOCI+ICAgICAgICAgICAgPHBhdGggZD0iTTQuMzczNDk3MTksMTAuNjAyNTQzOSBMNC4wMDUzMzcxNiwxMi42NTczOTA2IEMzLjk4NTQ0Nzg4LDEyLjc3MDY2NDggNC4wMjE3NDEwOCwxMi44ODY1NTMgNC4xMDQyOTEwNiwxMi45NjcyMDg3IEM0LjE4NjY4OTUsMTMuMDQ3OTQwMiA0LjMwMzE4Mzg1LDEzLjA4MjQxNSA0LjQxNjUzMzgsMTMuMDYwMTM5IEw2LjQ2MzU3NjI4LDEyLjY0OTM5NyBDNi41MzI2Mzk0NCwxMi42MzU1NjkyIDYuNTk1MTEwNzIsMTIuNjAxNDM1NCA2LjY0NDI4NDU5LDEyLjU1MTgwNjkgTDcuNDcyNzc3MjQsMTEuNzA2MzA0MiBMOC4xNzEzNjQ0OSw2LjYzOTcyODIgTDQuNDY4MjQ1OTMsMTAuNDIwNjk5MSBDNC40MTg0MjgwMiwxMC40NzAyODk3IDQuMzg1NjU4MDYsMTAuNTM0MDExMSA0LjM3MzQ5NzE5LDEwLjYwMjU0MzkgWiBNMTQuNTA2ODM4Niw0LjQ4MTgzNjEzIEw5LjcyMjc2NjE0LDQuNDgxODM2MTMgTDguMjM0NTE3NjksMTUuMjc1MDA5MSBDOC4yMTI1ODI2NiwxNS40MzMyMTQxIDguMjYxNzE4NjUsMTUuNTkzMzEzNCA4LjM2OTEyMDc1LDE1LjcxMjE1NjUgTDExLjcxMDg2MDYsMTkuNDMwNTA0NiBDMTEuODE0MDU3NSwxOS41NDQ1MzY1IDExLjk2MDc4MzYsMTkuNjEwMDAwNiAxMi4xMTQ4MjEzLDE5LjYxMDAwMDYgQzEyLjI2ODg1OSwxOS42MTAwMDA2IDEyLjQxNTU4NTEsMTkuNTQ0NDk4NiAxMi41MTg3ODIsMTkuNDMwNTA0NiBMMTUuODYxMDUyMywxNS43MTIxNTY1IEMxNS45Njg0MTY1LDE1LjU5MzMxMzQgMTYuMDE2OTA4NCwxNS40MzMyMTQxIDE1Ljk5NTEyNDksMTUuMjc1MDA5MSBMMTQuNTA2ODM4Niw0LjQ4MTgzNjEzIFogTTkuNjM3ODI5NDUsMy4yMzk3OTgwMSBMOS42OTg2MzM4MiwzLjQ5MTQ2MzY5IEwxNC41MzEwMDg4LDMuNDkxNDYzNjkgTDE0LjU5MTY2MTcsMy4yMzk3OTgwMSBMMTQuNzk2NjkxNywyLjM5NzMyNjAzIEMxNC44NzgwNjczLDIuMDYwMTU1NCAxNC44MDA0MDQ0LDEuNzA0MTU2MjUgMTQuNTg2MjgyMSwxLjQzMTcyOTk1IEMxNC4zNzIxNTk4LDEuMTU4ODQ5MDQgMTQuMDQ0MDQzNSwxIDEzLjY5NzE3NDUsMSBMMTAuNTMyNTQzOSwxIEMxMC4xODU1MjM0LDEgOS44NTc0MDcwOSwxLjE1ODg0OTA0IDkuNjQzNDM2MzMsMS40MzE3Mjk5NSBDOS40MjkzMTQwNCwxLjcwNDE1NjI1IDkuMzUxNjUxMTQsMi4wNjAxNTU0IDkuNDMyODc1MTYsMi4zOTczMjYwMyBMOS42Mzc4Mjk0NSwzLjIzOTc5ODAxIFoiIGlkPSJ0aWUiIHNrZXRjaDp0eXBlPSJNU1NoYXBlR3JvdXAiPjwvcGF0aD4gICAgICAgIDwvZz4gICAgPC9nPjwvc3ZnPg==';
//            $icon = plugin_dir_url(__DIR__) . 'admin/img/menu_icon.png';
        }else{
            $icon = 'dashicons-universal-access';
        }

        $capabilities = 'manage_options'; // by default minimum user role is Admin
        if($this->mode == 'demo'){
            $capabilities = 'read'; // open plugin admin for subscribers role (Demo sandbox)
        }

        $this->plugin_screen_hook_suffix = add_menu_page(
            $this->plugin_name, // Plugin Display Name
            $this->plugin_label, // Menu Item
            $capabilities, // Capabilities
            $this->plugin_name, // Slug
            array($this, 'display_admin_index'), // method to display admin index page
            $icon, // menu icon
            21
        );
    }

    /**
     * Display main admin page
     */
    public function display_admin_index(){
        $template = plugin_dir_path(__FILE__) . 'partials/a-team-showcase-admin-display.php';

        $employers = A_Team_App_Model_Employer::all();
        $teams = A_Team_App_Model_Team::all();

        if (sizeof($teams) !== 0) {
            foreach ($teams as $team) {
                if (!empty($team->employers)) {
                    $team_employers = A_Team_App_Model_Employer::where(
                        array(
                            'post_type' => A_Team_App_Model_Employer::$post_type,
                            'post__in' => $team->employers,
                            'orderby' => 'post__in',
                            'post_count' => -1,
                            'posts_per_page' => -1
                        )
                    );
                    $team->employers = $team_employers;
                } else {
                    $team->employers = array();
                }
            }
        } else {

        }

        $data = array(
            'teams' => $teams,
            'employers' => $employers
        );

        $view = new Awesome_Render($template, $data);
        echo $view->render();
    }
}