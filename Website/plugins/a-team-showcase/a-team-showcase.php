<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://looks-awesome.com
 * @since             1.0.0
 * @package           A_Team_Showcase
 *
 * @wordpress-plugin
 * Plugin Name:       Team Builder
 * Plugin URI:        http://team.looks-awesome.com/
 * Description:       Awesome Team Builder plugin
 * Version:           1.1
 * Author:            Looks Awesome
 * Author URI:        http://looks-awesome.com
 * Text Domain:       a-team-showcase
 * Domain Path:       /languages
 */



// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-a-team-showcase-activator.php
 */
function activate_a_team_showcase() {
    A_Team_App_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-a-team-showcase-deactivator.php
 */
function deactivate_a_team_showcase() {
	A_Team_App_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_a_team_showcase' );
register_deactivation_hook( __FILE__, 'deactivate_a_team_showcase' );



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_a_team_showcase() {
    new A_Team_App_Plugin();
}

/**
 * Plugin Autoload
 *
 * @param $class_dirty
 * @internal param $class_name
 */
function awesome_autoloader( $class_dirty ) {
    // include only classes with name contains 'App' or 'Awesome'
    if ( false !== strpos( $class_dirty, 'Awesome' ) || false !== strpos( $class_dirty, 'A_Team' ) ) {
        $plugin_name = 'a-team-showcase';
        // explode class name to array
        $parts = explode('_', $class_dirty);

        // split class name to clear Class and Namespace parts
        $class_name = end($parts);
        $class_path = substr($class_dirty, 0, strlen($class_dirty) - strlen($class_name));

        // remove App namespace from Class name and replace some predefined constants

        $class_path = str_replace('A_Team', '', $class_path);
        $class_path = str_replace('App', 'includes', $class_path);
        $class_path = str_replace('Back', 'admin', $class_path);
        $class_path = str_replace('Front', 'public', $class_path);
        $class_path = str_replace('Awesome', DIRECTORY_SEPARATOR .'includes' . DIRECTORY_SEPARATOR .'vendor' . DIRECTORY_SEPARATOR . 'Awesome', $class_path);
        $class_path = str_replace('_', DIRECTORY_SEPARATOR, $class_path);


        // Path to Wordpress plugin
        $dir = WP_PLUGIN_DIR  . DIRECTORY_SEPARATOR . $plugin_name;
        $class_file = $dir . $class_path . $class_name . '.php';
        //var_dump($class_file);
        if(file_exists($class_file)){
            require_once $class_file;
        }
    }
}

spl_autoload_register( 'awesome_autoloader' );

function at_debug_to_console($data) {
	if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}

run_a_team_showcase();