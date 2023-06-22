<?php
    global $upme_login_captcha_params;
    extract($upme_login_captcha_params);
?>

<div class="upme-elegant-field upme-edit upme-edit-show">
	<div class="upme-elegant-field-value">
        <?php echo $captcha_html; ?>
        
		<input type="hidden" name="captcha_plugin" value="<?php echo $captcha_plugin; ?>" />

	</div>
</div>
<div class="upme-clear"></div>