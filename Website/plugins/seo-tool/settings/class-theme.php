<?php

class Daxiawp_Plugin_Theme {
	
	/**
	 * Hooks
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'menu_page' ), 999 );
	}
	
	/**
	 * Add menu page
	 */
	function menu_page() {
		add_submenu_page( 'dx_seo', 'manage_options', array( $this, 'menu_content' ) );
	}
	
	/**
	 * Show menu content
	 */
	function menu_content() {
?>

<script type="text/javascript" src="http://cbjs.baidu.com/js/m.js"></script>
<?php 
	$codes=array(
		'454435',
		'454451',
		'454453',
		'454454',
		'454455',
		'454457',
		'454459',
		'454461',
		'454462'
	);
?>

<?php		
	}
}

new Daxiawp_Plugin_Theme;