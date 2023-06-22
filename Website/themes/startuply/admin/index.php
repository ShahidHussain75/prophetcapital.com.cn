
<?php
/**
 * The template for displaying all pages
 */

get_header();
?>

<style>
#footer, #sub-footer
{
	display:none;
}
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
<h4 class="text-center"><?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Control Panel<?php } else { ?>控制面板<?php } ?>
</h4>
                        
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>">
        <div class="entry-content entry">
        <div class="vivaco-form show" id="login-form">
            <?php the_content(); ?>
            <?php if ( !is_user_logged_in() ) : ?>
                    <p class="warning">
                        <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                    </p><!-- .warning -->
            <?php else : ?>
                <?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                	<div class="form form-overlay">
                    <div class="form-username form-group col-xs-12">
                        <input class="text-input" placeholder="<?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Fullname<?php } else { ?>名称<?php } ?>" name="fullname" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
                    </div><!-- .form-username -->
             
                    <div class="form-email form-group col-xs-12">
                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
                    </div><!-- .form-email -->
         
                    <div class="form-password form-group col-xs-12">
                        <input placeholder="<?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Password *<?php } else { ?>密码 *<?php } ?>" class="text-input" name="pass1" type="password" id="pass1" />
                    </div><!-- .form-password -->
                    <div class="form-password form-group col-xs-12">
                        <input placeholder="<?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Repeat Password *<?php } else { ?>重复密码 *<?php } ?>" class="text-input" name="pass2" type="password" id="pass2" />
                    </div><!-- .form-password -->
                    <div class="form-textarea form-group col-xs-12">
                        <textarea placeholder="<?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Description<?php } else { ?>描述<?php } ?>" name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
                    </div><!-- .form-textarea -->

                    <?php 
                        //action hook for plugin and extra fields
                        do_action('edit_user_profile',$current_user); 
                    ?>
                    <div class="form-submit form-group col-xs-12">
                        <?php echo $referer; ?>
                        <input style="margin-left: 0px;" name="updateuser" type="submit" id="updateuser" class="wpcf7-form-control wpcf7-submit btn base_clr_bg btn-solid" value="<?php if (ICL_LANGUAGE_CODE == 'en') {  ?>Update<?php } else { ?>更新<?php } ?>" />
                        <?php wp_nonce_field( 'update-user' ) ?>
                        <input name="action" type="hidden" id="action" value="update-user" />
                    </div><!-- .form-submit -->
                    </div>
                </form><!-- #adduser -->
            <?php endif; ?>
        </div><!-- .entry-content -->
    </div><!-- .hentry .post -->
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">
        <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
    </p><!-- .no-data -->
<?php endif; ?>

                        	</div>
							</div>
						</div>	
					</div>	
				</div>	
			</div>
			

	
</div>




<?php get_footer(); ?>