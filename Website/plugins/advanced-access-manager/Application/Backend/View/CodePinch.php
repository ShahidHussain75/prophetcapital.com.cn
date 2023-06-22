<?php

/**
 * Copyright (C) <2015>  Vasyl Martyniuk <vasyl@vasyltech.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * CodePinch banner
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Backend_View_CodePinch {

    /**
     * Plugin slug
     */
    const SLUG = 'WP Error Fix';
    
    /**
     * Cached plugin info
     * 
     * @var array
     * 
     * @access protected
     * @static 
     */
    protected static $info = null;
    
    /**
     * Check CodePinch plugin status
     * 
     * @return boolean
     * 
     * @access public
     * @static
     */
    public static function installed() {
        $info = self::getInfo();
        
        return (!empty($info['status']) && $info['status'] != 'install');
    }
    
    /**
     * Get installation URL
     * 
     * @return string
     * 
     * @access public
     * @static
     */
    public static function getURL() {
        $info = self::getInfo();
        
        return (!empty($info['url']) ? $info['url'] : '');
    }
    
    /**
     * Get plugin info
     * 
     * @return mixed
     * 
     * @access public
     * @static
     */
    public static function getInfo() {
        if (is_null(self::$info)) {
            self::query();
        }
        
        return self::$info;
    }
    
    /**
     * Query plugin status
     * 
     * @return void
     * 
     * @access protected
     * @static
     */
    protected static function query() {
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        
        $plugin = plugins_api('plugin_information', array('slug' => self::SLUG));
        $result = install_plugin_install_status( $plugin);
        
        if (!empty($result)) {
            self::$info = $result;
        }
    }
    
}