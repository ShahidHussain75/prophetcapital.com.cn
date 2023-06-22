<?php
    global $upme_login_field_params;
    extract($upme_login_field_params);
?>

<div class="upme-field upme-edit upme-edit-show upme-<?php echo $meta; ?>">    
    
<?php    
        if($field_label_meta != ''){
?>
            <label class="upme-field-type" for="<?php echo $meta; ?>">
                <?php echo $upme_login_field_icon; ?>                            
                <span><?php echo $upme_login_label; ?></span>
            </label>
<?php
        }else{
?>
            <label class="upme-field-type">&nbsp;</label>
<?php                        
        }
?>
            
        <div class="upme-field-value">
            <?php echo $upme_login_input_field; ?>
        </div>

</div>
<div class="upme-clear"></div>