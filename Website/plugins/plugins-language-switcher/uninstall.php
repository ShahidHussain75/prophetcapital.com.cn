<?php
/* 
 * Thank You Counter Button plugin uninstall script
 * Author: vladimir@shinephp.com
 *
 */


if (!defined('ABSPATH') || !defined('WP_UNINSTALL_PLUGIN')) {
	 exit();  // silence is golden
}

require_once('plugins-lang-switch_lib.php');

delete_option(PLUGINS_LANG_SWITCH_OPTION);

?>
