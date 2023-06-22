<?php
    global $upme_login_captcha_params;
    extract($upme_login_captcha_params);
?>

<div class="upme-modern-field upme-edit upme-edit-show">
	<label class="upme-field-type" >
        <span><?php echo $form_text; ?></span>
    </label>

	<div class="upme-modern-field-value">
        <?php echo $captcha_html; ?>
        
		<input type="hidden" name="captcha_plugin" value="<?php echo $captcha_plugin; ?>" />

	</div>
	<div class="upme-clear"></div>
</div>
<div class="upme-clear"></div>