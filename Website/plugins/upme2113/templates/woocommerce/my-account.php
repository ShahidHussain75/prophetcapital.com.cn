<?php
global $upme_template_loader;  

$current_user = wp_get_current_user();

wc_print_notices(); 

$account_tab_status     = apply_filters('upme_woo_account_tab_status',true,array( 'user_id' => $current_user->ID ));
$review_tab_status      = apply_filters('upme_woo_reviews_tab_status',true,array( 'user_id' => $current_user->ID ));
$downloads_tab_status   = apply_filters('upme_woo_downloads_tab_status',true,array( 'user_id' => $current_user->ID ));
$orders_tab_status      = apply_filters('upme_woo_orders_tab_status',true,array( 'user_id' => $current_user->ID ));

?>

<div  class="upme-woo-account woocommerce">
    
    <div class="upme-woo-account-navigation" >
        
        <?php if($account_tab_status) { ?>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-account-info" >
            <?php echo __('Account Info','upme'); ?>
        </div>
        <?php } ?>
        
        <?php if($review_tab_status) { ?>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-my-reviews" >
            <?php echo __('My Reviews','upme'); ?>
        </div>
        <?php } ?>
        
        <?php if($downloads_tab_status) { ?>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-my-downloads" >
            <?php echo __('My Downloads','upme'); ?>
        </div>
        <?php } ?>
        
        <?php if($orders_tab_status) { ?>
        <div class="upme-woo-account-navigation-item" data-nav-ietm-id="upme-woo-my-orders" >
            <?php echo __('My Orders','upme'); ?>
        </div>
        <?php } ?>
        
        <div class="upme-woo-clear"></div>
    </div>
    
    <?php do_action( 'woocommerce_before_my_account' ); ?>
    
    
    <?php if($account_tab_status) { ?>
    <div class="upme-woo-account-info myaccount_user upme-woo-account-navigation-content">
        <?php
        printf(
            __( 'Hello <strong>%1$s</strong> (not %1$s? <a href="%2$s">Sign out</a>).', 'woocommerce' ) . ' ',
            $current_user->display_name,
            wp_logout_url( get_permalink( wc_get_page_id( 'myaccount' ) ) )
        );

        printf( __( 'From your account dashboard you can view your recent orders, manage your shipping and billing addresses and edit your password and account details.', 'upme' ));
        ?>
        
        
        <?php $upme_template_loader->get_template_part('my-address'); ?>
    </div>
    <?php } ?>

    <?php if($downloads_tab_status) { ?>
    <div id="upme-woo-my-downloads" class="upme-woo-my-downloads upme-woo-account-navigation-content" style="display:none" >
        <?php $upme_template_loader->get_template_part('my-downloads');  ?>
    </div>
    <?php } ?>

    <?php if($review_tab_status) { ?>
    <div id="upme-woo-my-reviews" class="upme-woo-my-reviews upme-woo-account-navigation-content" style="display:none" >
        <?php $upme_template_loader->get_template_part('my-reviews');  ?>
    </div>
    <?php } ?>

    <?php if($orders_tab_status) { ?>
    <div id="upme-woo-my-orders" class="upme-woo-my-orders upme-woo-account-navigation-content" style="display:none"  >
        <?php $upme_template_loader->get_template_part('my-orders');  ?>
    </div>
    <?php } ?>

    <?php do_action( 'woocommerce_after_my_account' ); ?>
    
</div>
