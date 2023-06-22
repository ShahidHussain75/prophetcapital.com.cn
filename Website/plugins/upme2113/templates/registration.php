<?php
    global $upme_registration_params;
    extract($upme_registration_params);

    $user_role = isset($user_role) ? $user_role : '';

    extract($upme_registration_params['args']);
?>


<?php if('1' == $users_can_register){ ?>


<div id="upme-registration" class="upme-wrap upme-registration upme-registration-<?php echo $name; ?> <?php echo $sidebar_class; ?> ">
    <div class="upme-inner upme-registration-wrapper upme-clearfix">

        <?php if($display_errors_status){ ?>

            <!-- UPME Filters for before registration head section -->
            <?php $register_before_head_params = array('name' => $name,'user_role' => $user_role);
                  echo apply_filters( 'upme_register_before_head', '', $register_before_head_params); ?>
            <!-- End Filters -->

            <!-- UPME Filters for customizing head section -->
            <?php 
                $registration_head_params = array('name' => $name,'user_role' => $user_role);
                if($hide_header != 'yes'){ 
                    echo apply_filters( 'upme_registration_head', $display_head , $registration_head_params);
                }
            ?>
            <!-- End Filters -->

        <?php } ?>

        <!-- UPME Filters for after registration head section -->
        <?php $register_after_head_params = array('name' => $name, 'user_role' => $user_role, 'hide_social_login' => $args['hide_social_login']);
            echo apply_filters( 'upme_register_after_head', '', $register_after_head_params); ?>
        <!-- End Filters -->

        <div class="upme-main">
            <div class="upme-errors" style="display:none;" id="pass_err_holder">
                <span class="upme-error upme-error-block" id="pass_err_block">
                    <i class="upme-icon upme-icon-remove"></i>
                    <?php echo __('Please enter a username.','upme'); ?>
                </span>
            </div>

            <?php echo  $display_reg_post_errors; ?>
            <?php echo  $register_form; ?>

        </div>

        <!-- UPME Filters for after registration fields section -->
        <?php 
            $register_after_fields_params = array('name' => $name,'user_role' => $user_role, 'hide_social_login' => $args['hide_social_login'], 'social_login_template' => $args['social_login_template'] );
            echo apply_filters( 'upme_register_after_fields', '', $register_after_fields_params); ?>
        <!-- End Filters -->

    </div>
</div>

<?php  } else { ?>

<div id="upme-registration" class="upme-wrap upme-registration <?php echo $sidebar_class; ?> ">
    <div class="upme-inner upme-clearfix">
        <div class="upme-head">
            <?php echo  $registration_blocked_message; ?>
        </div>
    </div>
</div>

<?php  } ?>