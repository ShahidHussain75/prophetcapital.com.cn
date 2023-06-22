<?php
/**
 * Template name: User login & control panel
 */

get_header(); ?>
<style>
 .transparent.navigation-header .featured > a
{
	color: #fff;
}
</style>
<div id="main-content" class="registration">

		
		
			<div class="vc_row-fluid window_height centered-content">
				<div class="container">
					<div class="vc_col-sm-12">
						<div class="light reg-page-header">
							<div id="reg_box" class="col-sm-4 col-sm-offset-4">
          <h4 class="text-center"><?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Login<?php } else { ?>登录名<?php } ?></h4>
          
          
          
       <?php echo do_shortcode('[upme_login]'); ?>
							</div>
						</div>	
					</div>	
				</div>	
			</div>
			
	
	
</div>

<?php wp_footer(); ?>
