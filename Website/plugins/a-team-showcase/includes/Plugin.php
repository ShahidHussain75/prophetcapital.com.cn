<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://looks-awesome.com
 * @since      1.0.0
 *
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/includes
 * @author     Looks Awesome <hello@looks-awesome.com>
 */



class A_Team_App_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      A_Team_Showcase_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	public static $plugin_name = 'a-team-showcase';

    /**
     * Plugin Label
     *
     * @var string
     */
    public static $plugin_label = 'Team Builder';

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	public static $version = '1.1';

    /**
     * Required PHP version
     *
     * @var
     */
    public static $require_php = '5.3.4';

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        // check PHP version
        add_action( 'admin_init', array( $this, 'check_version' ) );
        if(!A_Team_App_Activator::compatible_version()){
            return;
        }

        add_action( 'init', array( $this, 'ie_add_header' ) );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
        $this->define_image_sizes();
        $this->loader->run();

	}

    // Better IE rendering
    public function ie_add_header()
    {
        //header('X-UA-Compatible: IE=edge,chrome=1');
    }

    public function define_image_sizes(){
        add_image_size('a-full', 1000, 1000, true);
        add_image_size('a-large', 600, 600, true);
        add_image_size('a-medium', 300, 300, true);
        add_image_size('a-thumbnail', 150, 150, true);
        add_image_size('a-small', 50, 50, true);
    }
    
    /**
     * If plugin already enabled in some way
     * Check PHP version to prevent WP admin crash
     */
    public static function check_version(){
        if(!A_Team_App_Activator::compatible_version()){
            $plugin = self::$plugin_name . '/' . self::$plugin_name . '.php';
            if(is_plugin_active($plugin)){
                deactivate_plugins($plugin);
            }
            add_action('admin_notices', array('A_Team_App_Activator', 'admin_message'));
            if(isset($_GET['activate'])){
                unset($_GET['activate']);
            }
        }
    }

    /**
     * Register custom post types on plugin activation
     */
    public function register_custom_post_types(){
        // labels for custom admin UI
        A_Team_App_Model_Employer::register_post_type();
        A_Team_App_Model_Employer::register_taxonomy();
        A_Team_App_Model_Team::register_post_type();
    }

    /**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - A_Team_Showcase_Loader. Orchestrates the hooks of the plugin.
	 * - A_Team_Showcase_i18n. Defines internationalization functionality.
	 * - A_Team_Showcase_Admin. Defines all hooks for the admin area.
	 * - A_Team_Showcase_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new A_Team_App_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the A_Team_Showcase_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new A_Team_App_Translate();
		$plugin_i18n->set_domain( self::$plugin_name );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

    private function define_ajax_hooks(){
        // Employer REST
        $ajax_class = new A_Team_App_Ajax();
        $this->loader->add_action( 'wp_ajax_update_or_new_employer', $ajax_class, 'ajax_handle_update_or_new_employer' );
        $this->loader->add_action( 'wp_ajax_get_employer', $ajax_class, 'ajax_handle_get_employer' );

        // Team REST
        $this->loader->add_action( 'wp_ajax_update_or_new_team', $ajax_class, 'ajax_handle_update_or_new_team' );
        $this->loader->add_action( 'wp_ajax_get_team', $ajax_class, 'ajax_handle_get_team' );

        // Universal action to delete any model
        $this->loader->add_action( 'wp_ajax_delete_model', $ajax_class, 'ajax_handle_delete_model' );

        // Get team preview photo
        $this->loader->add_action( 'wp_ajax_get_team_preview_photo', $ajax_class, 'ajax_handle_get_team_preview_photo' );

        // Get team employers photos
        $this->loader->add_action( 'wp_ajax_get_team_employers_photos', $ajax_class, 'ajax_handle_get_team_employers_photos' );

        // Get team templates
        $this->loader->add_action( 'wp_ajax_get_templates', $ajax_class, 'ajax_handle_get_templates' );

        // Get model defaults
        $this->loader->add_action( 'wp_ajax_get_team_defaults', $ajax_class, 'ajax_handle_get_team_defaults' );

        // Get model taxonomy
        $this->loader->add_action( 'wp_ajax_get_taxonomy_terms', $ajax_class, 'ajax_handle_get_taxonomy_terms' );
    }


    /**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new A_Team_Back_Admin( self::$plugin_name, self::$plugin_label, self::$version );

        // unqueue all scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // register custom post type
        $this->loader->add_action( 'init', $this, 'register_custom_post_types' );

        // add action links
        $plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . self::$plugin_name . '.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

        //register admin AJAX hooks
        $this->define_ajax_hooks();

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new A_Team_Front_Frontend( self::$plugin_name, self::$version );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_scripts' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'register_shortcode' );
        add_filter('widget_text', 'do_shortcode');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    A_Team_Showcase_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

    public function add_action_links( $links ) {

        return array_merge(
            array(
                'settings' => '<a href="' . admin_url( 'options-general.php?page=' . self::$plugin_name ) . '">' . 'Settings' . '</a>',
                'docs' => '<a target="_blank" href="http://team.looks-awesome.com/docs/Getting_Started">' . 'Documentation' . '</a>'
            ),
            $links
        );
    }

}
