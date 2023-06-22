<?php
    global $upme_login_params;
    extract($upme_login_params);

    extract($upme_login_params['args']);
?>

<div class="upme-login-classic-head ">
	<?php if($hide_header != 'yes'){ ?> 
	    <div class="upme-header-panel">
	        <div class="" id="login-heading-<?php echo $login_code_count; ?>"> <?php echo $login_form_title; ?></div>
	    </div>
    <?php } ?>
    <div class="upme-right"></div><div class="upme-clear"></div>
</div>

