<?php
    global $upme_login_field_params;
    extract($upme_login_field_params);

?>

<div class="upme-modern-field upme-<?php echo $meta; ?>">    
    
        <?php    
        if($field_label_meta != ''){
        ?>
            <label class="upme-field-type" for="<?php echo $meta; ?>">
                <span><?php echo $upme_login_label; ?></span>
            </label>
           
        <?php
        }else{
        ?>
            <label class="upme-field-type">&nbsp;</label>

        <?php                        
        }
        ?>
        <div class="upme-modern-field-value">
            <?php echo $upme_login_input_field; ?>
            
            
        </div>
        <div class="upme-clear"></div>
</div>
<div class="upme-clear"></div>
