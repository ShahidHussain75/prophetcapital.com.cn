<?php

/**
 * Fired during plugin activation
 *
 * @link       http://looks-awesome.com
 * @since      1.0.0
 *
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    A_Team_Showcase
 * @subpackage A_Team_Showcase/includes
 * @author     Looks Awesome <hello@looks-awesome.com>
 */


class A_Team_App_Activator {

	/**
     * TODO Move Notice method to Awesome Lib
	 *
	 * @since    1.0.0
	 */
    public static function admin_message(){
        $message = sprintf(
            __('Plugin <b>%1$s</b> require PHP version %2$s or higher.'),
            A_Team_App_Plugin::$plugin_label,
            A_Team_App_Plugin::$require_php
        );
        $class = "error";
        echo"<div class=\"$class\"> <p>$message</p></div>";
    }

    /**
     * Activate plugin if PHP version compatible
     */
    public static function activate() {
        if(!self::compatible_version()){
            add_action('admin_notices', 'admin_message');
            deactivate_plugins(A_Team_App_Plugin::$plugin_name);
        }
	}

    /**
     * Compare PHP versions
     *
     * @return bool
     */
    public static function compatible_version(){
        if(version_compare(PHP_VERSION, A_Team_App_Plugin::$require_php) == -1 ){
            return false;
        }else{
            return true;
        }
    }

}
