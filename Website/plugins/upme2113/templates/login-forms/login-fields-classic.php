<?php
    global $upme_login_field_params;
    extract($upme_login_field_params);

?>

<div class="upme-classic-field upme-<?php echo $meta; ?>">    
    
           
        <div class="upme-classic-field-value">
            <?php echo $upme_login_input_field; ?>
            <?php
                if($meta == 'user_login'){
                    echo '<i class="upme-icon upme-icon-user"></i>';
                }
                if($meta == 'login_user_pass'){
                    echo '<i class="upme-icon upme-icon-lock"></i>';
                }
            ?>
            
        </div>

</div>
