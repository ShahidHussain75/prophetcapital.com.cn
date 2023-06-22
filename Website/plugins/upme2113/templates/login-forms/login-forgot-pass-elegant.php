<?php
    global $upme_login_forgot_params;
    extract($upme_login_forgot_params);
?>
<div class="upme-elegant-forgot-pass" id="upme-forgot-pass-holder-<?php echo $login_code_count; ?>">
    <div class="upme-elegant-field upme-edit upme-edit-show">
        <div class="upme-elegant-field-value">
            <input type="text" placeholder="<?php echo __('Username or Email', 'upme'); ?>" class="upme-input" name="user_name_email" id="user_name_email-<?php echo $login_code_count; ?>" value="">
            <div class="upme-elegant-field-icon"><i class="upme-icon upme-icon-user"></i></div>
        </div>
    </div>

    <div class="upme-elegant-field upme-edit upme-edit-show">
        <label class="upme-field-type upme-blank-lable">&nbsp;</label>
        <div class="upme-elegant-field-value">
            <div class="upme-back-to-login">
            <a href="javascript:void(0);" title="<?php echo __('Back to Login', 'upme'); ?>" id="upme-back-to-login-<?php echo $login_code_count; ?>"><?php echo __('Back to Login', 'upme'); ?></a> <?php echo $register_link_forgot; ?>
            </div>

        <input type="button" name="upme-forgot-pass" id="upme-forgot-pass-btn-<?php echo $login_code_count; ?>" class="upme-clasic-btn" value="<?php echo __('Forgot Password', 'upme'); ?>">
        </div>
    </div>
</div>