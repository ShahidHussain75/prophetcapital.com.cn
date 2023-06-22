<!-- edit field -->
<?php 
    global $field_builder_edit_data,$upme_admin;
    extract($field_builder_edit_data);

    extract($array);

    if(!isset($required))
        $required = 0;

    if(!isset($fonticon))
        $fonticon = '';

    $ajax_for_custom_fields = TRUE;
?>
<!--
$pos
$type
$field 
$meta 
$social 
$private 
$array
$custom_file_field_types
-->

    <form method="post" action="" class="upme-custom-field-edit upme-custom-field-edit-<?php echo $meta; ?> ">
    <div class="upme-edit-table-column column-columnname" colspan="3">
        <p>
            <?php 

                $type_value = '';
                if('usermeta' == $type){
                    $type_value = __('Profile Field','upme'); 
                }else{
                    $type_value = __('Separator','upme');
                }
                ?>
            <label for="upme_<?php echo $pos; ?>_type"><?php _e('Field Type','upme');echo ": <strong>".$type_value."</strong>"; ?>
            </label> 


            <input type="hidden" name="upme_<?php echo $pos; ?>_type" class="upme-edit-field-type" 
                id="upme_<?php echo $pos; ?>_type" value="<?php echo $type;?>" />

        </p>
        <p>
            <label for="upme_<?php echo $pos; ?>_position"><?php _e('Position','upme'); ?>
            </label> <input name="upme_<?php echo $pos; ?>_position"
                type="text" disabled id="upme_<?php echo $pos; ?>_position"
                value="<?php echo $pos; ?>" class="small-text" /> <i
                class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Please use a unique position. Position lets you place the new field in the place you want exactly in Profile view.','upme'); ?>"></i>
        </p>



        <?php if ($type != 'separator') { 
                $display_field_input = 'block';
                $display_field_meta = 'block';
                $disabled_field_input = null;
                $disabled_field_meta = null;

                $display_field_status = 'block';
                $disabled_field_status = null;



              }else{
                $display_field_input = 'none';
                $display_field_meta = 'none';
                $disabled_field_input = 'disabled="disabled"';
                $disabled_field_meta = 'disabled="disabled"';

                $field = isset($field) ? $field : '';
                $meta = isset($meta) ? $meta : '';
                $social = isset($social) ? $social : '';
                $private = isset($private) ? $private : '0';

                $display_field_status = 'none';
                $disabled_field_status = 'disabled="disabled"';

              }

        ?>

        <p class="upme-inputtype" style="display:<?php echo $display_field_input; ?>">
            <label for="upme_<?php echo $pos; ?>_field"><?php _e('Field Input','upme'); ?>
            </label> <select <?php echo $disabled_field_input ?> name="upme_<?php echo $pos; ?>_field" 
                id="upme_<?php echo $pos; ?>_field" class="upme_edit_field_type upme_edit_field-<?php echo $pos; ?>" >
                <?php global $upme; foreach($upme->allowed_inputs as $input=>$label) { ?>
                <option value="<?php echo $input; ?>"
                <?php selected($input, $field); ?>>
                    <?php echo $label; ?>
                </option>
                <?php } ?>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Choose what type of field you would like to add.','upme'); ?>"></i>
        </p>

        <p>
            <label for="upme_<?php echo $pos; ?>_name"><?php _e('Label / Name','upme'); ?>
            </label> <input name="upme_<?php echo $pos; ?>_name" type="text"
                id="upme_<?php echo $pos; ?>_name" value="<?php echo $name; ?>" />
            <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','upme'); ?>"></i>
        </p>

        <p style="display:<?php echo $display_field_meta; ?>">
            <label for="upme_<?php echo $pos; ?>_meta"><?php _e('Choose Meta Field','upme'); ?>
            </label> <select <?php echo $disabled_field_meta ?> name="upme_<?php echo $pos; ?>_meta" 
                id="upme_<?php echo $pos; ?>_meta">
                <option value="">
                    <?php _e('Choose a user field','upme'); ?>
                </option>
                <?php
                $current_user = wp_get_current_user();
                if( $all_meta_for_user = get_user_meta( $current_user->ID ) ) {
                    ksort($all_meta_for_user);
                    foreach($all_meta_for_user as $user_meta => $user_meta_array) {
                        if($user_meta!='_upme_search_cache')
                        {
                        ?>
                <option value="<?php echo $user_meta; ?>"
                <?php selected($user_meta, $meta); ?>>
                    <?php echo $user_meta; ?>
                </option>
                <?php
                        }
                    }
                }
                ?>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Choose from a predefined/available list of meta fields (usermeta) or skip this to define a new custom meta key for this field below.','upme'); ?>"></i>
        </p>


        <p>
            <?php 
                $meta_custom_label = '';
                $meta_custom_help = '';
                if ($type != 'separator') { 
                    $meta_custom_label = __('Custom Meta Field','upme');
                    $meta_custom_help = PROFILE_HELP;
                }else{
                    $meta_custom_label = __('Meta Key','upme');
                    $meta_custom_help = SEPARATOR_HELP;
                }

            ?>
            <label for="upme_<?php echo $pos; ?>_meta_custom"><?php echo $meta_custom_label; ?>
            </label>



            <?php if ($type != 'separator') { ?>
            <input name="upme_<?php echo $pos; ?>_meta_custom" 
                type="text" id="upme_<?php echo $pos; ?>_meta_custom"
                value="<?php if (!isset($all_meta_for_user[$meta])) echo $meta; ?>" />
            <?php  }else{ ?>
            <input name="upme_<?php echo $pos; ?>_meta_custom" 
                type="text" id="upme_<?php echo $pos; ?>_meta_custom"
                value="<?php if (isset($meta)) echo $meta; ?>" />
            <?php  } ?>


            <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php echo $meta_custom_help; ?>"></i>
        </p>

    </div>
    <div class="upme-edit-table-column column-columnname" colspan="3"><?php //if ($type != 'separator') { ?>

        <?php if ($social == 1) { ?>
        <p style="display:<?php echo $display_field_status; ?>">
            <label for="upme_<?php echo $pos; ?>_tooltip"><?php _e('Tooltip Text','upme'); ?>
            </label> <input <?php echo $disabled_field_status; ?> name="upme_<?php echo $pos; ?>_tooltip" type="text"
                id="upme_<?php echo $pos; ?>_tooltip"
                value="<?php echo isset($tooltip) ? $tooltip : ''; ?>" /> <i
                class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('A tooltip text can be useful for social buttons on profile header.','upme'); ?>"></i>
        </p> <?php } ?> 

        <?php if ($field != 'password') { 

            $display_social = "";
            if($field == 'fileupload' || in_array($field, $custom_file_field_types) )
               $display_social = 'style="display:none;"';

        ?>
        <p <?php echo $display_social; ?> style="display:<?php echo $display_field_status; ?>">
            <label for="upme_<?php echo $pos; ?>_social"><?php _e('This field is social','upme'); ?>
            </label> <select <?php echo $disabled_field_status; ?> name="upme_<?php echo $pos; ?>_social"
                id="upme_<?php echo $pos; ?>_social">
                <option value="0" <?php selected(0, $social); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $social); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('A social field can show a button with your social profile in the head of your profile. Such as Facebook page, Twitter, etc.','upme'); ?>"></i>
        </p> <?php } ?> <?php 
        if(!isset($can_edit))
            $can_edit = '1';
        ?>
        <p style="display:<?php echo $display_field_status; ?>">
            <label for="upme_<?php echo $pos; ?>_can_edit"><?php _e('User can edit','upme'); ?>
            </label> <select <?php echo $disabled_field_status; ?> name="upme_<?php echo $pos; ?>_can_edit"
                id="upme_<?php echo $pos; ?>_can_edit">
                <option value="1" <?php selected(1, $can_edit); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
                <option value="0" <?php selected(0, $can_edit); ?>>
                    <?php _e('No','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Users can edit this profile field or not.','upme'); ?>"></i>
        </p> 

        <?php 
            if (!isset($array['allow_html'])) { 
                $allow_html = 0;
            } 

            $display_allow_html = "";
            if($field == 'fileupload' || in_array($field, $custom_file_field_types) )
               $display_allow_html = 'style="display:none;"';
        ?>
        <p <?php echo $display_allow_html; ?> style="display:<?php echo $display_field_status; ?>">
            <label for="upme_<?php echo $pos; ?>_allow_html"><?php _e('Allow HTML','upme'); ?>
            </label> <select <?php echo $disabled_field_status; ?> name="upme_<?php echo $pos; ?>_allow_html"
                id="upme_<?php echo $pos; ?>_allow_html">
                <option value="0" <?php selected(0, $allow_html); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $allow_html); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('If yes, users will be able to write HTML code in this field.','upme'); ?>"></i>
        </p> <?php if ($private != 1) { 

            if(!isset($can_hide))
                $can_hide = '0';
            ?>
        <p style="display:block">
            <label for="upme_<?php echo $pos; ?>_can_hide"><?php _e('User can hide','upme'); ?>
            </label> <select name="upme_<?php echo $pos; ?>_can_hide" class="upme_can_hide" id="upme_<?php echo $pos; ?>_can_hide">
                <?php if($type != 'separator'){ ?> 
                    <option value="1" <?php selected(1, $can_hide); ?>>
                        <?php _e('Yes','upme'); ?>
                    </option>
                <?php } ?>

                <option value="0" <?php selected(0, $can_hide); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="2" <?php selected(2, $can_hide); ?>>
                    <?php _e('Always Hide from Public','upme'); ?>
                </option>
                <option value="3" <?php selected(3, $can_hide); ?>>
                    <?php _e('Always Hide from Guests','upme'); ?>
                </option>
                <option value="4" <?php selected(4, $can_hide); ?>>
                    <?php _e('Always Hide from Members','upme'); ?>
                </option>  
                <option value="5" <?php selected(5, $can_hide); ?>>
                    <?php _e('Always Hide from User Roles','upme'); ?>
                </option> 
                <?php 
                    $can_hide_custom_filter_options = array('can_hide' => $can_hide, 'meta' => $meta);
                    echo apply_filters('upme_can_hide_custom_filter_options','', $can_hide_custom_filter_options); 

                ?>

            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Allow users to hide this profile field from public viewing or not. Selecting No will cause the field to always be publicly visible if you have public viewing of profiles enabled. Selecting Yes will give the user a choice if the field should be publicy visible or not. Private fields are not affected by this option.','upme'); ?>"></i>
        </p> <?php } ?> 



        <?php
            $upme_can_hide_role_list_display = 'none';
            if( "5" == $can_hide){
                $upme_can_hide_role_list_display = 'block';
            }else{
                $upme_can_hide_role_list_display = 'none';
            }

        ?>
        <div style='display:<?php echo $upme_can_hide_role_list_display; ?>'>
            <label for="upme_<?php echo $pos; ?>_can_hide_role_list"><?php _e('Select User Roles','upme'); ?>
            </label> 
            <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('This field will be hidden from logged in users with specified user roles.','upme'); ?>"></i>

            <?php 
                global $upme_roles;
                $roles = $upme_roles->upme_get_available_user_roles();

                if(isset($can_hide_role_list) && !is_array($can_hide_role_list)){
                    $can_hide_role_list = explode(',', $can_hide_role_list);
                }else{
                    $can_hide_role_list = array();
                }	

                foreach($roles as $role_key => $role_display){
                    $hide_role_checked = '';
                    if(in_array($role_key, $can_hide_role_list)){
                        $hide_role_checked = 'checked';
                    }
            ?>
                    <div class='upme-user-roles-list'>
                        <input type='checkbox' class='upme_<?php echo $pos; ?>_can_hide_role_list' <?php echo $hide_role_checked; ?> 
                        name='upme_<?php echo $pos; ?>_can_hide_role_list[]' id='upme_<?php echo $pos; ?>_can_hide_role_list' value='<?php echo $role_key; ?>' />
                        <label class='upme-role-name'><?php echo $role_display; ?></label>
                    </div>
            <?php
                }
            ?>

        </div>




        <?php 
        if(!isset($private))
            $private = '0';
        ?>
        <p style="display:<?php echo $display_field_status; ?>">
            <label for="upme_<?php echo $pos; ?>_private"><?php _e('This field is private','upme'); ?>
            </label> <select <?php echo $disabled_field_status; ?> name="upme_<?php echo $pos; ?>_private"
                id="upme_<?php echo $pos; ?>_private">
                <option value="0" <?php selected(0, $private); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $private); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Make this field Private. Only admins can see private fields.','upme'); ?>"></i>
        </p> <?php 
        if(!isset($required))
            $required = '0';
        ?>


        <?php 
        $display_required = "";
//        if(in_array($field, $custom_file_field_types) )
//            $display_required = 'style="display:none;"';
        ?>
        <p <?php echo $display_required; ?> style="display:<?php echo $display_field_status; ?>" >
            <label for="upme_<?php echo $pos; ?>_required"><?php _e('This field is Required','upme'); ?>
            </label> <select <?php echo $disabled_field_status; ?> name="upme_<?php echo $pos; ?>_required"
                id="upme_<?php echo $pos; ?>_required"   >
                <option value="0" <?php selected(0, $required); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $required); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Selecting yes will force user to provide a value for this field at registeration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','upme'); ?>"></i>
        </p> 
        <?php //} ?>




        <?php 
            if (!isset($array['show_to_user_role'])) { 
                $show_to_user_role = 0;
            } 



        if( !('user_pass_confirm' == $meta || 'user_pass' == $meta)){				

        ?>
        <p>
            <label for="upme_<?php echo $pos; ?>_show_to_user_role"><?php _e('Display by User Role','upme'); ?>
            </label> <select  name="upme_<?php echo $pos; ?>_show_to_user_role"
                id="upme_<?php echo $pos; ?>_show_to_user_role" class="upme_show_to_user_role">
                <option value="0" <?php selected(0, $show_to_user_role); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $show_to_user_role); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('If no, this field will be displayed on profiles of all User Roles. Select yes to display this field only on profiles of specific User Roles.','upme'); ?>"></i>
        </p>

        <?php
            $upme_show_role_list_display = 'none';
            if( "1" == $show_to_user_role){
                $upme_show_role_list_display = 'block';
            }else{
                $upme_show_role_list_display = 'none';
            }

        ?>	
        <div style='display:<?php echo $upme_show_role_list_display; ?>'>
            <label for="upme_<?php echo $pos; ?>_show_to_user_role_list"><?php _e('Select User Roles','upme'); ?>
            </label> 
            <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('This field will only be displayed on users of the selected User Roles.','upme'); ?>"></i>

            <?php 
                global $upme_roles;
                $roles = $upme_roles->upme_get_available_user_roles();

                if(isset($show_to_user_role_list) && !is_array($show_to_user_role_list)){
                    $show_to_user_role_list = explode(',', $show_to_user_role_list);
                }else{
                    $show_to_user_role_list = array();
                }			  			

                foreach($roles as $role_key => $role_display){
                    $show_role_checked = '';
                    if(in_array($role_key, $show_to_user_role_list)){
                        $show_role_checked = 'checked';
                    }
            ?>
                    <div class='upme-user-roles-list'>
                        <input type='checkbox' class='upme_<?php echo $pos; ?>_show_to_user_role_list' <?php echo $show_role_checked; ?> 
                        name='upme_<?php echo $pos; ?>_show_to_user_role_list[]' id='upme_<?php echo $pos; ?>_show_to_user_role_list' value='<?php echo $role_key; ?>' />
                        <label class='upme-role-name'><?php echo $role_display; ?></label>
                    </div>
            <?php
                }
            ?>

        </div>


        <?php }	?>

        <?php if (!isset($array['edit_by_user_role'])) { 
            $edit_by_user_role = 0;

        } 

        if( !( 'separator' == $type || 'user_pass_confirm' == $meta || 'user_pass' == $meta)){				
        ?>
        <p>
            <label for="upme_<?php echo $pos; ?>_edit_by_user_role"><?php _e('Editable by Users of Role','upme'); ?>
            </label> <select name="upme_<?php echo $pos; ?>_edit_by_user_role"
                id="upme_<?php echo $pos; ?>_edit_by_user_role" class="upme_edit_by_user_role" >
                <option value="0" <?php selected(0, $edit_by_user_role); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $edit_by_user_role); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('If yes, available user roles will be displayed for selection.','upme'); ?>"></i>
        </p>


        <?php
            $upme_edit_role_list_display = 'none';
            if( "1" == $edit_by_user_role){
                $upme_edit_role_list_display = 'block';
            }else{
                $upme_edit_role_list_display = 'none';
            }

        ?>	
        <div style='display:<?php echo $upme_edit_role_list_display; ?>'>
            <label for="upme_<?php echo $pos; ?>_edit_by_user_role_list"><?php _e('Select Roles that can Edit','upme'); ?>
            </label> 
            <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Selected user roles will have the permission to edit this field.','upme'); ?>"></i>

            <?php 
                global $upme_roles;
                $roles = 	$upme_roles->upme_get_available_user_roles("edit");

                if(isset($edit_by_user_role_list) && !is_array($edit_by_user_role_list)){
                    $edit_by_user_role_list = explode(',', $edit_by_user_role_list);
                }else{
                    $edit_by_user_role_list = array();
                }


                foreach($roles as $role_key => $role_display){
                    $edit_role_checked = '';
                    if(in_array($role_key, $edit_by_user_role_list)){
                        $edit_role_checked = 'checked';
                    }
            ?>
                    <div class='upme-user-roles-list'>
                        <input type='checkbox' class='upme_<?php echo $pos; ?>_edit_by_user_role_list' <?php echo $edit_role_checked; ?> 
                        name='upme_<?php echo $pos; ?>_edit_by_user_role_list[]' id='upme_<?php echo $pos; ?>_edit_by_user_role_list' value='<?php echo $role_key; ?>' />
                        <label class='upme-role-name'><?php echo $role_display; ?></label>
                    </div>			  				
            <?php
                }
            ?>

        </div>


        <?php }	?>

        <?php

        /* Show Registration field only when below condition fullfill
         1) Field is not private
        2) meta is not for email field
        3) field is not fileupload */
        if(!isset($private))
            $private = 0;

        if(!isset($meta))
            $meta = '';

        if(!isset($field))
            $field = '';

        //if ((isset($private) && $private != 1) && $meta != 'user_email' && $field != 'fileupload' )
        if($type == 'separator' ||  ($private != 1 && $meta != 'user_email' ))
        {
            if(!isset($show_in_register))
                $show_in_register= 0;
            ?>
        <p>
            <label for="upme_<?php echo $pos; ?>_show_in_register"><?php _e('Show on Registration Form','upme'); ?>
            </label> <select name="upme_<?php echo $pos; ?>_show_in_register"
                id="upme_<?php echo $pos; ?>_show_in_register">
                <option value="0" <?php selected(0, $show_in_register); ?>>
                    <?php _e('No','upme'); ?>
                </option>
                <option value="1" <?php selected(1, $show_in_register); ?>>
                    <?php _e('Yes','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Show this profile field on the registration form','upme'); ?>"></i>
        </p> <?php } ?>

        <?php if(!isset($help_text))
                $help_text = '';

        if( 'separator' != $type ){

        ?>
        <p>
            <label for="upme_<?php echo $pos; ?>_help_text"><?php _e('Help Text','upme'); ?>
            </label> 
            <textarea class="upme-input" name="upme_<?php echo $pos; ?>_help_text"
                id="upme_<?php echo $pos; ?>_help_text" title="<?php _e('A help text can be useful for provide information about the field.','upme'); ?>" ><?php echo wp_unslash($help_text); ?></textarea>
             <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Show this help text under the profile field.','upme'); ?>"></i>
        </p>

        <?php 
        }
        ?>

    </div>
    <div class="upme-edit-table-column-last column-columnname" colspan="9"><?php if ($type != 'separator') { ?>

        <?php if (in_array($field, array('select','radio','checkbox','chosen_select','chosen_multiple','select2_tag_multiple','select2_tag_single'))) {
            $show_choices = null;
        } else { $show_choices = 'upme-hide';
        } ?>

        <p class="upme-choices <?php echo $show_choices; ?>">
            <label for="upme_<?php echo $pos; ?>_choices"
                style="display: block"><?php _e('Available Choices','upme'); ?> </label>
            <textarea  name="upme_<?php echo $pos; ?>_choices" type="text" id="upme_<?php echo $pos; ?>_choices" class="large-text"><?php if (isset($array['choices'])) echo upme_stripslashes_deep(trim($choices)); ?></textarea>
            <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('Enter one choice per line please. The choices will be available for front end user to choose from.','upme'); ?>"></i>
        </p> <?php if (!isset($array['predefined_loop'])) $predefined_loop = 0; ?>

        <p  class="upme-choices <?php echo $show_choices; ?>">
            <label for="upme_<?php echo $pos; ?>_predefined_loop"
                style="display: block"><?php _e('Enable Predefined Choices','upme'); ?>
            </label> <select  name="upme_<?php echo $pos; ?>_predefined_loop"
                id="upme_<?php echo $pos; ?>_predefined_loop">
                <option value="0" <?php selected(0, $predefined_loop); ?>>
                    <?php _e('None','upme'); ?>
                </option>
                <option value="countries"
                <?php selected('countries', $predefined_loop); ?>>
                    <?php _e('List of Countries','upme'); ?>
                </option>
            </select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                title="<?php _e('You can enable a predefined filter for choices. e.g. List of countries It enables country selection in profiles and saves you time to do it on your own.','upme'); ?>"></i>
        </p>

        <p >

            <span style="display: block; font-weight: bold; margin: 0 0 10px 0"><?php _e('Field Icon:','upme'); ?>&nbsp;&nbsp;
                <?php if ($icon) { ?><i class="upme-icon upme-icon-<?php echo $icon; ?>"></i>
                <?php } else { _e('None','upme'); 
                } ?>&nbsp;&nbsp; <a href="#changeicon"
                class="button button-secondary upme-inline-icon-upme-edit"><?php _e('Change Icon','upme'); ?>
            </a> </span> <label class="upme-icons"><input  type="radio"
                name="upme_<?php echo $pos; ?>_icon" value=""
                <?php checked('', $fonticon); ?> /> <?php _e('None','upme'); ?> </label>

            <?php foreach($upme_admin->fontawesome as $fonticon) { ?>
            <label class="upme-icons "><input class="upme_<?php echo $pos; ?>_icons"  type="radio"
                name="upme_<?php echo $pos; ?>_icon"
                value="<?php echo $fonticon; ?>"
                <?php checked($fonticon, $icon); ?> /><i
                class="upme-icon upme-icon-<?php echo $fonticon; ?> upme-tooltip3"
                title="<?php echo $fonticon; ?>"></i> </label>
            <?php } ?>

        </p>
        <div class="clear"></div> <?php } ?>


        <?php
            $btn_type = 'submit';
            $field_update_class = '';
            if($ajax_for_custom_fields){
                $btn_type = 'button';
                $field_update_class = 'upme-field-update';
            }
        ?>
        <p>
            <input type="<?php echo $btn_type; ?>" id="submit_field_<?php echo $pos; ?>" name="submit"
                value="<?php _e('Update','upme'); ?>"
                class="button button-primary <?php echo $field_update_class; ?>  " data-upme-field-pos="<?php echo $pos; ?>" data-upme-field-meta="<?php echo $meta; ?>" /> 
            <input type="reset"
                value="<?php _e('Cancel','upme'); ?>"
                class="button button-secondary upme-inline-cancel" />
            <span class='upme_single_update_processing update_processing'></span>
        </p>

    </div>
    <div class="clear"></div>
</form>
