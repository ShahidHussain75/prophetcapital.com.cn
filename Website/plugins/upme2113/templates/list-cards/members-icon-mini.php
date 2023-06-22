<?php 
    global $upme_list_card_params,$upme;     
    extract($upme_list_card_params);
   
?>

<div class="<?php echo $css_class; ?>">
    <?php
        foreach($results as $user){ 
            $user_id = $user->ID;
            $user_image = $upme->pic($user_id, 50);
            $link = $upme->profile_link($user_id);
    ?>
        <div class="upme-profile-single">
            <a href="<?php echo $link; ?>" ><?php echo $user_image; ?></a>
        </div>
    <?php
        }
    ?>
    <div class="upme-clear"></div>
</div>
