<?php
/*
  Plugin Name: User Profiles Made Easy
  Plugin URI: http://codecanyon.net/item/user-profiles-plugin-for-wordpress/4109874?ref=ThemeFluent
  Description: An awesome user profiles plugin for WordPress.
  Version: 2.1.13
  Author: ThemeFluent
  Author URI: https://null24.net
 */
define('upme_url', plugin_dir_url(__FILE__));
define('upme_path', plugin_dir_path(__FILE__));
define('upme_php_version_status', version_compare(phpversion(), '5.3'));


if (class_exists('FUNCAPTCHA'))
    define('FUNCAPTCHA_LOADED', true);
else
    define('FUNCAPTCHA_LOADED', false);

// Get plugin version from header
function upme_get_plugin_version() {
    $default_headers = array('Version' => 'Version');
    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');
    return $plugin_data['Version'];
}

// Add settings link on plugin page
function upme_settings_link($links) {
    $settings_link = '<a href="admin.php?page=upme-settings">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'upme_settings_link');

// load the text domain
load_plugin_textdomain('upme', false, '/upme/l10n');

/* Loading Function */
require_once upme_path . 'function/functions.php';
require_once upme_path . 'function/compatible_plugins_filters.php';
require_once upme_path . 'function/compatible_plugins_actions.php';

// New Actions for UPME Search to Update Search Cache for User
add_action('upme_profile_update','upme_update_user_cache');
add_action('upme_user_register','upme_update_user_cache');

// Adding Action Hook to use for WP Cron
add_action('upme_process_cache_cron', 'upme_cron_user_cache');
register_activation_hook( __FILE__, 'upme_activation' );
register_deactivation_hook( __FILE__, 'upme_deactivation' );

/* Init */
require_once upme_path . 'init/init.php';


// Module related files
require_once upme_path . 'modules/class-upme-modules.php';

require_once upme_path . 'modules/class-upme-verify-site-restrictions.php';
require_once upme_path . 'modules/class-upme-email-templates.php';
require_once upme_path . 'modules/class-upme-woocommerce.php';
require_once upme_path . 'modules/class-upme-custom-fields.php';
require_once upme_path . 'modules/class-upme-import-export.php';
require_once upme_path . 'modules/social/upme-social.php';
require_once upme_path . 'modules/class-upme-seo.php';
require_once upme_path . 'modules/class-upme-posts-pages.php';
require_once upme_path . 'modules/class-upme-featured-members.php';


/* Classes */
require_once upme_path . 'classes/class-upme-options.php';
require_once upme_path . 'classes/class-upme-template-loader.php';
require_once upme_path . 'classes/class-upme-html.php';
require_once upme_path . 'classes/class-upme-captcha-loader.php';
require_once upme_path . 'classes/class-upme-predefined.php';
require_once upme_path . 'classes/class-upme-roles.php';
require_once upme_path . 'classes/class-upme.php';
require_once upme_path . 'classes/class-upme-save.php';
require_once upme_path . 'classes/class-upme-register.php';
require_once upme_path . 'classes/class-upme-login.php';
require_once upme_path . 'classes/class-upme-reset-password.php';
require_once upme_path . 'classes/class-upme-profile-fields.php';
require_once upme_path . 'classes/class-upme-profile.php';
require_once upme_path . 'classes/class-upme-private-content.php';
require_once upme_path . 'classes/class-upme-cards.php';
require_once upme_path . 'classes/class-upme-list-cards.php';
require_once upme_path . 'classes/class-upme-scripts-styles.php';
require_once upme_path . 'classes/class-upme-profile-header.php';

// BuddyPress avatar integration
require_once(upme_path . 'integrated_plugins/buddypress/buddypress.php');


require_once upme_path . 'classes/class-upme-api.php';

/* Shortcodes */
require_once upme_path . 'shortcodes/shortcode-init.php';
require_once upme_path . 'shortcodes/shortcodes.php';

/* Registration customizer */

require_once upme_path . 'registration/upme-register.php';

/* Profile customizer */
require_once upme_path . 'profile/upme-profile.php';

/* Function for Forgot Password Feature */
require_once upme_path . 'forgotpass/upme-forgot-pass.php';


/* Widgets */
require_once upme_path . 'widgets/upme-widgets.php';
require_once upme_path . 'widgets/upme-login-widget.php';

/* Scripts - dynamic css */
add_action('wp_footer', 'upme_custom_scripts');

function upme_custom_scripts() {
    
    $upme_options = get_option('upme_options');
    $reg_form_title_username = isset($upme_options['register_form_title_type_username']) ? $upme_options['register_form_title_type_username'] : '1';

    wp_register_script('upme_custom', upme_url . 'js/upme-custom.js', array('jquery'));
    wp_enqueue_script('upme_custom');

    $custom_js_strings = array(
        'ViewProfile' => __('View Profile', 'upme'),
        'EditProfile' => __('Edit Profile', 'upme'),
        'UPMEUrl' => upme_url,
        'ForgotPass' => __('Forgot Password', 'upme'),
        'Login' => __('Login', 'upme'),
        'Messages' => array(
            'EnterDetails' => __('Please enter your username or email to reset password.', 'upme'),
            'EnterEmail' => __('Please enter your email address.', 'upme'),
            'ValidEmail' => __('Please enter valid username or email address.', 'upme'),
            'NotAllowed' => __('Password changes are not allowed for this user.', 'upme'),
            'EmailError' => __('We are unable to deliver email to your email address. Please contact site admin.', 'upme'),
            'PasswordSent' => __('We have sent a password reset link to your email address.', 'upme'),
            'WentWrong' => __('Something went wrong, please try again', 'upme'),
            'RegExistEmail' => __('Email is already registered.', 'upme'),
            'RegValidEmail' => __('Email is available', 'upme'),
            'RegInvalidEmail' => __('Invalid email.', 'upme'),
            'RegEmptyEmail' => __('Email is empty.', 'upme'),
            'RegExistUsername' => __('Username is already registered.', 'upme'),
            'RegValidUsername' => __('Username is available.', 'upme'),
            'RegEmptyUsername' => __('Username is empty.', 'upme'),
            'RegInValidUsername' => __('Invalid username.', 'upme'),
            'DelPromptMessage' => __('Are you sure you want to delete this image?', 'upme'),
        ),
        'AdminAjax' => admin_url('admin-ajax.php'),
        'RegFormTitleUsername' => $reg_form_title_username,
        'confirmDeleteProfile' => __('Do you want to delete the profile','upme'),
    );

    /* UPME Filter for modifying custom js messgaes */
    $custom_js_strings = apply_filters('upme_custom_js_strings',$custom_js_strings);
    // End Filter

    wp_localize_script('upme_custom', 'UPMECustom', $custom_js_strings);
}

add_filter( 'upme_profile_edit_bar', array($upme, 'profile_edit_bar_buttons'), 10,3);

/* Admin panel */
if (is_admin ()) {

    
    // Module related files
    require_once upme_path . 'modules/class-upme-site-restrictions.php';
    require_once upme_path . 'classes/class-upme-updater.php';
    require_once(upme_path . 'classes/class-upme-admin.php');
    require_once(upme_path . 'classes/class-upme-sync-woocommerce.php');
    require_once(upme_path . 'admin/admin-icons.php');
    

}


add_action('init','upme_load_vc');
function upme_load_vc(){
    if ( defined( 'WPB_VC_VERSION' ) ) {
        global $upme_options;
        if($upme_options->upme_settings['visual_composer_shortcodes'] == '1'){
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_extend.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_login.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_registration.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_search.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_member.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_non_member.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_logout.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_reset_password.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_profile.php');
            require_once(upme_path . 'integrated_plugins/visual_composer/vc_upme_member_list.php');
        }    
    }
}
// Test Branch Master

