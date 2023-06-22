<?php
/*
Plugin Name: Plugins Language Switcher
Plugin URI: http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/
Description: It changes language locale value according to your selection for plugins interface at admin back-end only.
Version: 1.4.4
Author: Vladimir Garagulya
Author URI: http://www.shinephp.com
Text Domain: plugins-lang-switch
Domain Path: ./lang/
*/

/*
Copyright 2010-2013 Vladimir Garagulya  (email: vladimir@shinephp.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!function_exists("get_option")) {
  die;  // Silence is golden, direct call is prohibited
}

global $wp_version;

$exit_msg = __('Plugins Language Switcher requires WordPress 3.0 or newer.','plugins-lang-switch').'<a href="http://codex.wordpress.org/Upgrading_WordPress">'.__('Please update!','plugins-lang-switch').'</a>';

if (version_compare($wp_version,"3.0","<")) {
	return ($exit_msg);
}

require_once('plugins-lang-switch_lib.php');


function pls_optionsPage() {

  if (!current_user_can('edit_posts')) {
    die('action is forbidden');
  }

  global $pls_siteURL;
 
?>

<div class="wrap">
  <div class="icon32" id="icon-options-general"><br/></div>
    <h2><?php _e('Plugins Language Switcher', 'plugins-lang-switch'); ?></h2>
		<?php require_once('plugins-lang-switch_options.php'); ?>
  </div>
<?php

}
// end of pls_optionsPage()


// Install plugin
function pls_install() {
  // nothing to do at this moment
}
// end of pls_install()


function pls_init() {

  if (function_exists('register_setting')) {
    register_setting('plugins-lang-switch-options', 'plugins_lang_switch_language');
  }
  
  $pls_language = '';
  if (isset($_POST['action']) && $_POST['action']=='update' && isset($_POST['plugins_lang_switch_language'])) {
    $pls_language = $_POST['plugins_lang_switch_language'];      
  } else if (isset($_COOKIE['pls_language']) && $_COOKIE['pls_language']) {
    $pls_language = $_COOKIE['pls_language'];   
  }

  if (!empty($pls_language)) {
    setcookie('pls_language', $pls_language, time()+60*60*24*30, COOKIEPATH, COOKIE_DOMAIN);
  }
}
// end of pls_init()


function pls_action_links($links, $file) {
    if ($file == plugin_basename(dirname(__FILE__).'/plugins-language-switcher.php')){
        $settings_link = "<a href='tools.php?page=plugins-language-switcher.php'>".__('Settings','plugins-lang-switch')."</a>";
        array_unshift( $links, $settings_link );
    }
    return $links;
}
// end of pls_action_links


function pls_row_meta($links, $file) {
  if ($file == plugin_basename(dirname(__FILE__).'/plugins-language-switcher.php')){
		$links[] = '<a target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/#changelog">'.__('Changelog', 'plugins-lang-switch').'</a>';
	}
	return $links;
} // end of pls_row_meta


function pls_adminCssAction() {

  wp_enqueue_style('pls_admin_css', PLUGINS_LANG_SWITCH_PLUGIN_URL.'/css/plugins-lang-switch.css', array(), false, 'screen');

}
// end of pls_adminCssAction()


function pls_settings_menu() {
  
  if ( function_exists('add_management_page') ) {
    $pls_page = add_management_page('Plugins Language Switcher', 'Plugins Language Switcher', 'edit_posts', basename(__FILE__), 'pls_optionsPage');
    add_action( "admin_print_styles-$pls_page", 'pls_adminCssAction' );
  }
  
}
// end of pls_settings_menu()


function pls_locale($vLocale) {

  if (isset($_COOKIE['pls_language']) && $_COOKIE['pls_language']) {
    $pls_language = $_COOKIE['pls_language'];
    return $pls_language;
  } else {
    return $vLocale;
  }

}


if (is_admin()) {
  // activation action
  register_activation_hook(__FILE__, "pls_install");

  add_filter('locale', 'pls_locale', 10, 2);
  add_filter('plugin_locale', 'pls_locale', 10, 2);
  add_filter('plugin_action_links', 'pls_action_links', 10, 2);
  add_filter('plugin_row_meta', 'pls_row_meta', 10, 2);
  
  add_action('admin_init', 'pls_init');
  // add a Settings link in the installed plugins page  
  add_action('admin_menu', 'pls_settings_menu');  

  load_plugin_textdomain('plugins-lang-switch','', $pls_PluginDirName.'/lang');
}