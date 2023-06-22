<?php
/* 
 * Plugins Language Switcher plugin Settings form
 * Author: Vladimir Garagulya vladimir@shinephp.com
 */

if (!defined('PLUGINS_LANG_SWITCH_PLUGIN_URL')) {
  die;  // Silence is golden, direct call is prohibited
}

global $current_user;

if (empty($current_user) && function_exists('get_currentuserinfo')) {
  get_currentuserinfo();
}

$pls_languages = pls_get_plugins_languages();
$pls_options = get_option(PLUGINS_LANG_SWITCH_OPTION);
$pls_options = unserialize($pls_options);

if (isset($_POST['action']) && $_POST['action']=='update' && isset($_POST['plugins_lang_switch_language'])) {
  $pls_language = $_POST['plugins_lang_switch_language'];  
} else if (isset($_COOKIE['pls_language']) && $_COOKIE['pls_language']) {
  //$pls_language = $pls_options[$current_user->ID];
  $pls_language = $_COOKIE['pls_language'];
} else {
  $pls_language = WPLANG;
}


function pls_displayBoxStart($title, $style='') {
?>
			<div class="postbox" style="<?php echo $style; ?>" >
				<h3 style="cursor:default;"><span><?php echo $title; ?></span></h3>
				<div class="inside">
<?php
}
// 	end of pls_displayBoxStart()


function pls_displayBoxEnd() {
?>
				</div>
			</div>
<?php
}
// end of pls_displayBoxEnd()


$mess = '';
$shinephpFavIcon = PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/vladimir.png';

?>  
				<div id="poststuff" class="metabox-holder">
					<div class="has-sidebar" >
            <div id="post-body-content" class="has-sidebar-content">
  <form method="post" action="tools.php?page=plugins-language-switcher.php">
<?php
    settings_fields('plugins-lang-switch-options');
?>


<?php
	pls_displayBoxStart(__('Select Language', 'plugins-lang-switch'));
?>
              <select name="plugins_lang_switch_language" id="plugins_lang_switch_language">
<?php
  foreach ($pls_languages as $key=>$lang) {
?>
                <option value="<?php echo $key; ?>" <?php echo pls_optionSelected($pls_language, $key); ?> ><?php echo $lang; ?></option>
<?php
  }
?>
              </select>
<?php
  pls_displayBoxEnd();
?>
      <div class="fli submit" style="padding-top: 0px;">
          <input type="submit" name="submit" value="<?php _e('Update', 'plugins-lang-switch'); ?>" title="<?php _e('Save Changes', 'plugins-lang-switch'); ?>" />
      </div>
    </form>          
			</div>
						<div id="pls-about" style="clear: both;">
									<?php pls_displayBoxStart(__('About this Plugin:', 'plugins-lang-switch'),'float: left; display: block; width: 200px;'); ?>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo $shinephpFavIcon; ?>);" target="_blank" href="http://www.shinephp.com/"><?php _e("Author's website", 'plugins-lang-switch'); ?></a>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/plswitch-icon.png'; ?>" target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/"><?php _e('Plugin webpage', 'plugins-lang-switch'); ?></a>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/changelog-icon.png'; ?>);" target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/#changelog"><?php _e('Changelog', 'plugins-lang-switch'); ?></a>
											<a class="plugins_lang_switch_rsb_link" style="background-image:url(<?php echo PLUGINS_LANG_SWITCH_PLUGIN_URL.'/images/faq-icon.png'; ?>)" target="_blank" href="http://www.shinephp.com/plugins-language-switcher-wordpress-plugin/#faq"><?php _e('FAQ', 'plugins-lang-switch'); ?></a>
                      <hr />
                      <div style="text-align: center;">
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

                      </div>
									<?php pls_displayBoxEnd(); ?>
						</div>

  </div>
  </div>
  </div>
    

