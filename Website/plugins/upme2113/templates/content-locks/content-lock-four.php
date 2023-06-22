<?php
    global $upme_content_lock_params;
    extract($upme_content_lock_params);
?>

<div class="upme-content-lock-four">
    <div class="upme-content-lock-icon"><img src="<?php echo upme_url; ?>img/<?php echo $icon; ?>.png" /></div>
    <div class="upme-content-lock-msg"><?php echo $message; ?></div>
    <div class="upme-clear"></div>
</div>