<?php
/*
    Plugin Name: Data Source
    Plugin URI: http://datasource.codetiburon.com/
    Description: Build charts, tables, maps and grids using virtually any data source
    Version: 1.2.3
    Author: CodeTiburon
    Author URI: http://codetiburon.com
*/

define( 'DTS_VERSION', '1.2.3' );
define( 'DTS_REQUIRED_WP_VERSION', '3.8' );
define( 'DTS_PLUGIN', __FILE__ );
define( 'DTS_PLUGIN_BASENAME', plugin_basename( DTS_PLUGIN ) );
define( 'DTS_PLUGIN_NAME', trim( dirname( DTS_PLUGIN_BASENAME ), '/' ) );
define( 'DTS_PLUGIN_DIR', untrailingslashit( dirname( DTS_PLUGIN ) ) );
define( 'DTS_PLUGIN_MODULES_DIR', DTS_PLUGIN_DIR . '/modules' );

require_once DTS_PLUGIN_DIR . '/bootstrap.php';
$dtsBootstrap = new \DataSource\Bootstrap();
if ( is_admin() ) {
    $dtsBootstrap->loadAdmin();
}

/**
 * Get plugin URL
 * @param string $path
 * @return string
 */
function dts_plugin_url( $path = '' ) {
    $url = plugins_url( $path, DTS_PLUGIN );

    if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
        $url = 'https:' . substr( $url, 5 );
    }

    return $url;
}

/**
 * Admin panel CSS
 */
add_action( 'admin_enqueue_scripts', 'dts_admin_enqueue_scripts' );
function dts_admin_enqueue_scripts( $hook_suffix ) {
    wp_enqueue_style( 'data-source-admin',
        dts_plugin_url( 'admin/css/styles.css' ),
        array(), DTS_VERSION, 'all' );

    if ( false !== strpos( $hook_suffix, 'dts' ) )
    {
        wp_enqueue_style( 'data-source-main',
            dts_plugin_url( 'admin/css/main.css' ),
            array(), DTS_VERSION, 'all' );

        wp_enqueue_style( 'data-source-spectrum',
            dts_plugin_url( 'admin/js/spectrum/spectrum.css' ),
            array(), DTS_VERSION, 'all' );
    }
}

add_action( 'wp_enqueue_scripts', 'dts_public_enqueue_scripts' );
function dts_public_enqueue_scripts() {
    wp_enqueue_script('jquery');
}

add_action('wp_footer', 'dts_public_print_scripts');
function dts_public_print_scripts() {
    global $dtsBootstrap;
    $dtsBootstrap->printScripts();
}

/**
 * Activate plugin hook
 */
register_activation_hook( __FILE__, 'data_source_activate' );

function data_source_activate()
{
    $styles = get_option( '_dts_table_styles' );
    if (!$styles) {
        $designs = '{"_Black Striped":{"table_border":"#dddddd","table_borders_type":"horizontal","table_font":"","header_background":"#000000","header_text":"#ffffff","body_odd_background":"#f9f9f9","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Black Striped"},"_Red Striped":{"table_border":"#dddddd","table_borders_type":"horizontal","table_font":"","header_background":"#ba0000","header_text":"#ffffff","body_odd_background":"#f9f9f9","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Red Striped"},"_Blue Striped":{"table_border":"#dddddd","table_borders_type":"horizontal","table_font":"","header_background":"#0024c2","header_text":"#ffffff","body_odd_background":"#f9f9f9","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Blue Striped"},"_Orange Striped":{"table_border":"#dddddd","table_borders_type":"horizontal","table_font":"","header_background":"#ed710e","header_text":"#ffffff","body_odd_background":"#f9f9f9","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Orange Striped"},"_Black":{"table_border":"#dddddd","table_borders_type":"all","table_font":"","header_background":"#000000","header_text":"#ffffff","body_odd_background":"#ffffff","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Black"},"_Red":{"table_border":"#dddddd","table_borders_type":"all","table_font":"","header_background":"#ba0000","header_text":"#ffffff","body_odd_background":"#ffffff","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Red"},"_Blue":{"table_border":"#dddddd","table_borders_type":"all","table_font":"","header_background":"#0024c2","header_text":"#ffffff","body_odd_background":"#ffffff","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Blue"},"_Orange":{"table_border":"#dddddd","table_borders_type":"all","table_font":"","header_background":"#ed710e","header_text":"#ffffff","body_odd_background":"#ffffff","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_Orange"},"_White":{"table_border":"#dddddd","table_borders_type":"all","table_font":"","header_background":"#ffffff","header_text":"#333333","body_odd_background":"#ffffff","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_background":"#f5f5f5","name":"_White"},"_White Striped":{"table_border":"#dddddd","table_borders_type":"horizontal","table_font":"","header_background":"#ffffff","header_text":"#333333","body_odd_background":"#f9f9f9","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_selected_color":"","pagination_background":"#f5f5f5","name":"_White Striped"},"_Green":{"table_border":"#dddddd","table_borders_type":"all","table_font":"","header_background":"#0ad209","header_text":"#ffffff","body_odd_background":"#ffffff","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_selected_color":"#ffffff","pagination_background":"#0ad209","name":"_Green"},"_Green Striped":{"table_border":"#dddddd","table_borders_type":"horizontal","table_font":"","header_background":"#0ad209","header_text":"#ffffff","body_odd_background":"#f9f9f9","body_even_background":"#ffffff","body_hover_background":"#f5f5f5","body_text":"#333333","pagination_color":"#333333","pagination_selected_color":"#ffffff","pagination_background":"#0ad209","name":"_Green Striped"}}';
        update_option( '_dts_table_styles', $designs );
    }

    $version = get_option( '_dts_version' );

    if ((!$version) || (version_compare($version, DTS_VERSION) < 0)) {
        update_option( '_dts_version', DTS_VERSION );
    }
}

/**
 * Registering translations path
 */
add_action('plugins_loaded', 'dts_load_textdomain');
function dts_load_textdomain() {
    load_plugin_textdomain( 'data-source', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

add_filter('upload_mimes', 'dts_allow_xml_uploads');

function dts_allow_xml_uploads($mimes) {
    $mimes = array_merge($mimes, array('xml' => 'application/xml'));
    return $mimes;
}

function dts_json_encode($input)
{
    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
        },
        json_encode($input)
    );
}
?>