<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://looks-awesome.com
 * @since      1.0.0
 *
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/public
 * @author     Looks Awesome <hello@looks-awesome.com>
 */
class A_Team_Front_Frontend {
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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$env = null;
		$tmp = file_exists(plugin_dir_path(__DIR__) . 'env.json');
		if(file_exists(plugin_dir_path(__DIR__) . 'env.json')){
			$env = json_decode(file_get_contents(plugin_dir_path(__DIR__) . 'env.json'), true);
		}

		$this->mode = $env['mode'] ?: 'dev';
		$this->version = $version;
	}


	public function register_shortcode(){
		new A_Team_App_Shortcode();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_styles() {

		/**
         * Register shortcode styles but not enqueue
		 */

		wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), $this->version, 'all' );

		if($this->mode == 'dev'){
			wp_register_style( A_Team_App_Plugin::$plugin_name . '-shortcode', plugin_dir_url( __FILE__ ) . 'css/a-team-showcase-public.css', array(), $this->version, 'all' );
			wp_register_style( 'slick-carousel', plugin_dir_url( __FILE__ ) . 'css/slick.css', array(), $this->version, 'all' );
			wp_register_style( 'slick-carousel-theme', plugin_dir_url( __FILE__ ) . 'css/slick-theme.css', array(), $this->version, 'all' );
		}else{
			wp_register_style('all', plugin_dir_url( __FILE__ ) . 'css/all.min.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_scripts() {

        /**
         * Register shortcode scripts but not enqueue
         */

		if($this->mode == 'dev') {
			wp_register_script(A_Team_App_Plugin::$plugin_name . '-shortcode', plugin_dir_url(__FILE__) . 'js/a-team-showcase-public.js', array('jquery'), $this->version, false);
			wp_register_script('slick-carousel', plugin_dir_url(__FILE__) . 'js/vendor/slick.min.js', array('jquery'), $this->version, false);
			wp_register_script('viewport', plugin_dir_url(__FILE__) . 'js/vendor/viewport.js', array('jquery'), $this->version, false);
			wp_register_script('isotope', plugin_dir_url(__FILE__) . 'js/vendor/isotope.min.js', array('jquery'), $this->version, false);
		}else{
			wp_register_script('all', plugin_dir_url(__FILE__) . 'js/all.min.js', array('jquery'), $this->version, false);
		}

	}

}
