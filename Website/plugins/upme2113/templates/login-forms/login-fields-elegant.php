<?php
    global $upme_login_field_params;
    extract($upme_login_field_params);

?>

<div class="upme-elegant-field upme-<?php echo $meta; ?>">    
    
           
        <div class="upme-elegant-field-value">
            <?php echo $upme_login_input_field; ?>
            <?php
                if($meta == 'user_login'){
                    echo '<div class="upme-elegant-field-icon"><i class="upme-icon upme-icon-user"></i></div>';
                }
                if($meta == 'login_user_pass'){
                    echo '<div class="upme-elegant-field-icon"><i class="upme-icon upme-icon-lock"></i></div>';
                }
            ?>
            
        </div>

</div>
