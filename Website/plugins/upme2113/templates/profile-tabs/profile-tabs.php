<?php
    global $upme_profile_tabs_params;
    $params = $upme_profile_tabs_params;
    extract($params);

    if('enabled' == $initial_display) {
        $tabs_status = "display:block;";
    }else{
        $tabs_status = "display:none;";
    }
?>

<div  class="upme-profile-tabs-panel">
    <div  class="upme-profile-tabs" style="<?php echo $tabs_status; ?>">
        <div  class="upme-user-profile-tab-panel upme-profile-tab" data-tab-id="upme-profile-panel" >
            <?php echo apply_filters('upme_profile_tab_items_profile','<i class="upme-profile-icon upme-icon-user"></i>',$params); ?>
        </div>
        
        <?php echo apply_filters('upme_profile_tab_items','',$params); ?>

    </div>
    <div class="upme-clear"></div>
            
    <div class="upme-profile-tab-open upme-profile-tab-button">
        <?php if('enabled' == $initial_display) { ?>
            <i class="upme-profile-icon upme-icon-arrow-circle-up "></i>
        <?php } else { ?>
            <i class="upme-profile-icon upme-icon-arrow-circle-down "></i>
        <?php }  ?>
        
    </div>
    <div class="upme-clear"></div>
            
</div>