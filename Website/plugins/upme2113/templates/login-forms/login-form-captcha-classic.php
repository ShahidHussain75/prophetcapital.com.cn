<?php
    global $upme_login_captcha_params;
    extract($upme_login_captcha_params);
?>

<div class="upme-classic-field upme-edit upme-edit-show">
	<div class="upme-classic-field-value">
        <?php echo $captcha_html; ?>
        <i class="upme-icon upme-icon-none"></i>
		<input type="hidden" name="captcha_plugin" value="<?php echo $captcha_plugin; ?>" />

	</div>
</div>
<div class="upme-clear"></div>