<?php
    global $upme_login_forgot_params;
    extract($upme_login_forgot_params);
?>
<div class="upme-modern-forgot-pass" id="upme-forgot-pass-holder-<?php echo $login_code_count; ?>">
    <div class="upme-modern-field upme-edit upme-edit-show">
        <label class="upme-field-type" >
            <span><?php echo __('Username or Email', 'upme'); ?></span>
        </label>
        <div class="upme-modern-field-value">
            <input type="text" class="upme-input" name="user_name_email" id="user_name_email-<?php echo $login_code_count; ?>" value="">
            
        </div>
        <div class="upme-clear"></div>
    </div>

    <div class="upme-modern-field " style="border:none;">
        
        <div class="upme-modern-field-forgot-form">
            <div class="upme-back-to-login">
            <a href="javascript:void(0);" title="<?php echo __('Back to Login', 'upme'); ?>" id="upme-back-to-login-<?php echo $login_code_count; ?>"><?php echo __('Back to Login', 'upme'); ?></a> <?php echo $register_link_forgot; ?>
            </div>

        <input type="button" name="upme-forgot-pass" id="upme-forgot-pass-btn-<?php echo $login_code_count; ?>" class="upme-modern-btn" value="<?php echo __('Forgot Password', 'upme'); ?>">
        </div>
        <div class="upme-clear"></div>
    </div>
</div>