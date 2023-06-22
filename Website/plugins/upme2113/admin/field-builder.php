<div class="wrap">
    <h2><?php _e('UPME - Custom Fields','upme');?></h2>
    
    <form method="post" action="" id="upme-custom-field-add">
    
    <?php 

        $current_option = get_option('upme_options');
        $ajax_for_custom_fields = TRUE;

        $fields = get_option('upme_profile_fields');
        ksort($fields);
        
        
        $last_ele = end($fields);
        
        $new_position = key($fields)+1;


        $custom_file_field_types_params = array();
        $custom_file_field_types = apply_filters('upme_custom_file_field_types',array(), $custom_file_field_types_params );



        ?>
        <h3>
        	<?php _e('Profile Fields Cutomizer','upme'); ?>
        </h3>
        <p>
        	<?php _e('Organize profile fields, add custom fields to profiles, control privacy of each field, and more using the following customizer. You can drag and drop the fields to change the order in which they are displayed on profiles and the registration form.','upme'); ?>
        </p>
        
        <a href="#upme-add-form" class="button button-secondary upme-toggle"><i
        	class="upme-icon upme-icon-plus"></i>&nbsp;&nbsp;<?php _e('Click here to add new field','upme'); ?>
        </a>
        
        <table class="form-table upme-add-form">
        
        	<tr valign="top" style="display: none;">
        		<th scope="row"><label for="up_position"><?php _e('Position','upme'); ?>
        		</label></th>
        		<td><input name="up_position" type="text" id="up_position"
        			value="<?php if (isset($_POST['up_position']) && isset($this->errors) && count($this->errors)>0) echo $_POST['up_position']; else echo $new_position; ?>"
        			class="small-text" /> <i
        			class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Please use a unique position. Position lets you place the new field in the place you want exactly in Profile view.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_type"><?php _e('Type','upme'); ?> </label>
        		</th>
        		<td><select name="up_type" id="up_type">
        				<option value="usermeta">
        					<?php _e('Profile Field','upme'); ?>
        				</option>
        				<option value="separator">
        					<?php _e('Separator','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('You can create a separator or a usermeta (profile field)','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_field"><?php _e('Editor / Input Type','upme'); ?>
        		</label></th>
        		<td><select name="up_field" id="up_field">
        				<?php global $upme; foreach($upme->allowed_inputs as $input=>$label) { ?>
        				<option value="<?php echo $input; ?>">
        					<?php echo $label; ?>
        				</option>
        				<?php } ?>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Choose what type of field you would like to add.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	
        
        	<tr valign="top">
        		<th scope="row"><label for="up_name"><?php _e('Label','upme'); ?> </label>
        		</th>
        		<td><input name="up_name" type="text" id="up_name"
        			value="<?php if (isset($_POST['up_name']) && isset($this->errors) && count($this->errors)>0) echo $_POST['up_name']; ?>"
        			class="regular-text" /> <i
        			class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Enter the label / name of this field as you want it to appear in front-end (Profile edit/view)','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_meta"><?php _e('Existing Meta Key / Field','upme'); ?>
        		</label></th>
        		<td><select name="up_meta" id="up_meta">
        				<option value="">
        					<?php _e('Choose a Meta Key','upme'); ?>
        				</option>
        				<optgroup label="--------------">
        					<option value="1">
        						<?php _e('New Custom Meta Key','upme'); ?>
        					</option>
        				</optgroup>
        				<optgroup label="-------------">
        					<?php
        					$current_user = wp_get_current_user();
        					if( $all_meta_for_user = get_user_meta( $current_user->ID ) ) {
        
        					    ksort($all_meta_for_user);
        
        					    foreach($all_meta_for_user as $user_meta => $array) {
        					        if($user_meta!='_upme_search_cache')
        					        {
        					        
        					        ?>
        					<option value="<?php echo $user_meta; ?>">
        						<?php echo $user_meta; ?>
        					</option>
        					<?php
        					        }
        					    }
        					}
        					?>
        				</optgroup>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Choose from a predefined/available list of meta fields (usermeta) or skip this to define a new custom meta key for this field below.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<?php 
        	$meta_custom_value = '';
        	$meta_custom_display = 'none';
        	if (isset($_POST['up_meta_custom']) && isset($this->errors) && count($this->errors)>0)
        	{
        	    $meta_custom_value = $_POST['up_meta_custom'];
        	    $meta_custom_display = '';
        	}
        	?>
        
        	<tr valign="top" style="display:<?php echo $meta_custom_display;?>;">
        		<th scope="row"><label for="up_meta_custom"><?php _e('New Custom Meta Key','upme'); ?>
        		</label></th>
        		<td><input name="up_meta_custom" type="text" id="up_meta_custom"
        			value="<?php echo $meta_custom_value; ?>" class="regular-text" /> <i
        			class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php echo PROFILE_HELP; ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_tooltip"><?php _e('Tooltip Text','upme'); ?>
        		</label></th>
        		<td><input name="up_tooltip" type="text" id="up_tooltip"
        			value="<?php if (isset($_POST['up_tooltip']) && isset($this->errors) && count($this->errors)>0) echo $_POST['up_tooltip']; ?>"
        			class="regular-text" /> <i
        			class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('A tooltip text can be useful for social buttons on profile header.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_social"><?php _e('This field is social','upme'); ?>
        		</label></th>
        		<td><select name="up_social" id="up_social">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('A social field can show a button with your social profile in the head of your profile. Such as Facebook page, Twitter, etc.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_can_edit"><?php _e('User can edit','upme'); ?>
        		</label></th>
        		<td><select name="up_can_edit" id="up_can_edit">
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Users can edit this profile field or not.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_allow_html"><?php _e('Allow HTML Content','upme'); ?>
        		</label></th>
        		<td><select name="up_allow_html" id="up_allow_html">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('If yes, users will be able to write HTML code in this field.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_can_hide"><?php _e('User can hide','upme'); ?>
        		</label></th>
        		<td><select name="up_can_hide" id="up_can_hide">
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
                        <option value="2">
                            <?php _e('Always Hide from Public','upme'); ?>
                        </option>
                        <option value="3">
                            <?php _e('Always Hide from Guests','upme'); ?>
                        </option>
                        <option value="4">
                            <?php _e('Always Hide from Members','upme'); ?>
                        </option>
                        <option value="5">
                            <?php _e('Always Hide from User Roles','upme'); ?>
                        </option>
                        <?php 
                            $can_hide_custom_default_options = array();
                            echo apply_filters('upme_can_hide_custom_filter_default_options','', $can_hide_custom_default_options);                             
                        ?>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Allow users to hide this profile field from public viewing or not. Selecting No will cause the field to always be publicly visible if you have public viewing of profiles enabled. Selecting Yes will give the user a choice if the field should be publicy visible or not. Private fields are not affected by this option.','upme'); ?>"></i>
        		</td>
        	</tr>
            <tr valign="top" style="display:none" >
        		<th scope="row"><label for="up_can_hide_role_list"><?php _e('Select User Roles','upme'); ?>
        		</label></th>
        		<td>
        		<?php global $upme_roles;
        			  $roles = 	$upme_roles->upme_get_available_user_roles();
        			  foreach($roles as $role_key => $role_display){
        		?>
        			  <input type='checkbox' name='up_can_hide_role_list[]' id='up_can_hide_role_list' value='<?php echo $role_key; ?>' />
        			  <label class='upme-role-name'><?php echo $role_display; ?></label>
        		<?php
        			  }
        		?>
        		 <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('This field will be hidden from logged in users with specified user roles.','upme'); ?>"></i>
        		</td>
        	</tr>
            
        
        	<tr valign="top">
        		<th scope="row"><label for="up_private"><?php _e('This field is private','upme'); ?>
        		</label></th>
        		<td><select name="up_private" id="up_private">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Make this field Private. Only admins can see private fields.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        
        	<tr valign="top">
        		<th scope="row"><label for="up_private"><?php _e('This field is required','upme'); ?>
        		</label></th>
        		<td><select name="up_required" id="up_required">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Selecting yes will force user to provide a value for this field at registeration and edit profile. Registration or profile edits will not be accepted if this field is left empty.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        
        
        	<tr valign="top">
        		<th scope="row"><label for="up_show_in_register"><?php _e('Show on Registration form','upme'); ?>
        		</label></th>
        		<td><select name="up_show_in_register" id="up_show_in_register">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Show this field on the registration form? If you choose no, this field will be shown on edit profile only and not on the registration form. Most users prefer fewer fields when registering, so use this option with care.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top">
        		<th scope="row"><label for="up_show_to_user_role"><?php _e('Display by User Role','upme'); ?>
        		</label></th>
        		<td><select name="up_show_to_user_role" id="up_show_to_user_role">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('If no, this field will be displayed on profiles of all User Roles. Select yes to display this field only on profiles of specific User Roles.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        
        	<tr valign="top" style="display:none" >
        		<th scope="row"><label for="up_show_to_user_role_list"><?php _e('Select User Roles','upme'); ?>
        		</label></th>
        		<td>
        		<?php global $upme_roles;
        			  $roles = 	$upme_roles->upme_get_available_user_roles();
        			  foreach($roles as $role_key => $role_display){
        		?>
        			  <input type='checkbox' name='up_show_to_user_role_list[]' id='up_show_to_user_role_list' value='<?php echo $role_key; ?>' />
        			  <label class='upme-role-name'><?php echo $role_display; ?></label>
        		<?php
        			  }
        		?>
        		 <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('This field will only be displayed on users of the selected User Roles.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top" >
        		<th scope="row"><label for="up_edit_by_user_role"><?php _e('Editable by Users of Role','upme'); ?>
        		</label></th>
        		<td><select name="up_edit_by_user_role" id="up_edit_by_user_role">
        				<option value="0">
        					<?php _e('No','upme'); ?>
        				</option>
        				<option value="1">
        					<?php _e('Yes','upme'); ?>
        				</option>
        		</select> <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('If yes, available user roles will be displayed for selection.','upme'); ?>"></i>
        		</td>
        	</tr>
        
        	<tr valign="top" style="display:none" >
        		<th scope="row"><label for="up_edit_by_user_role_list"><?php _e('Select Roles that can Edit.','upme'); ?>
        		</label></th>
        		<td>
        		<?php global $upme_roles;
        			  $roles = 	$upme_roles->upme_get_available_user_roles("edit");
        			  foreach($roles as $role_key => $role_display){
        		?>
        			  <input type='checkbox' name='up_edit_by_user_role_list[]' id='up_edit_by_user_role_list' value='<?php echo $role_key; ?>' />
        			  <label class='upme-role-name'><?php echo $role_display; ?></label>
        		<?php
        			  }
        		?>
        		 <i class="upme-icon upme-icon-question-circle upme-tooltip2"
        			title="<?php _e('Selected user roles will have the permission to edit this field.','upme'); ?>"></i>
        		</td>
        	</tr>
        
            <tr valign="top">
                <th scope="row"><label for="up_help_text"><?php _e('Help Text','upme'); ?>
                </label></th>
                <td>
                    <textarea class="upme-help-text" id="up_help_text" name="up_help_text" title="<?php _e('A help text can be useful for provide information about the field.','upme'); ?>" ><?php if (isset($_POST['up_help_text']) && isset($this->errors) && count($this->errors)>0) echo $_POST['up_help_text']; ?></textarea>
                    <i class="upme-icon upme-icon-question-circle upme-tooltip2"
                                title="<?php _e('Show this help text under the profile field.','upme'); ?>"></i>
                </td>
            </tr>


        	<tr valign="top" class="upme-icons-holder">
        		<th scope="row"><label><?php _e('Icon for this field','upme'); ?> </label>
        		</th>
        		<td><label class="upme-icons"><input type="radio" name="up_icon"
        				value="0" /> <?php _e('None','upme'); ?> </label> <?php foreach($this->fontawesome as $icon) { ?>
        			<label class="upme-icons"><input type="radio" name="up_icon"
        				value="<?php echo $icon; ?>" /><i
        				class="upme-icon upme-icon-<?php echo $icon; ?> upme-tooltip3"
        				title="<?php echo $icon; ?>"></i> </label> <?php } ?>
        		</td>
        	</tr>

            <?php
                $btn_type = 'submit';
                $field_create_class = '';
                if($ajax_for_custom_fields){
                    $field_create_class = 'upme-field-create';
                    $btn_type = 'button';
                }
            ?>
        
        	<tr valign="top">
        		<th scope="row"></th>
        		<td><input type="<?php echo $btn_type; ?>" name="upme-add" id="upme-add"
        			value="<?php _e('Submit New Field','upme'); ?>"
        			class="button button-primary <?php echo $field_create_class; ?>" /> <input type="reset"
        			class="button button-secondary upme-add-form-cancel"
        			value="<?php _e('Cancel','upme'); ?>" />
                    <span id="upme_create_processing" class='update_processing'></span>
        		</td>
        	</tr>
        
        </table>

    <?php
        if($ajax_for_custom_fields){
            echo '</form>';
        }
    ?>
        
        <!-- show customizer -->
        
        <div class="widefat fixed upme-table" cellspacing="0"
        	id="upme-form-customizer-table">
  
        
        
        		<div class="upme-field-table-headers ignore">
        			<div class="upme-field-table-header manage-column column-columnname" scope="col" width="3%"><?php _e('Icon','upme'); ?>
        			</div>
                    
        			<div class="upme-field-table-header upme-field-table-header-extend manage-column column-columnname" scope="col"><?php _e('Label','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip" 
        				title="<?php _e('The label that appears in front-end profile view or edit.','upme'); ?>"></i>
        			</div>
        
        			<div class="upme-field-table-header upme-field-table-header-extend manage-column column-columnname" scope="col"><?php _e('Meta Key','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip" 
        				title="<?php _e('This is the meta field that stores this specific profile data (e.g. first_name stores First Name)','upme'); ?>"></i>
        			</div>
        
        			<div class="upme-field-table-header upme-field-table-header-extend manage-column column-columnname"  scope="col"><?php _e('Field Input','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('This column tells you the field input that will appear to user for this data.','upme'); ?>"></i>
        			</div>
        
        			<div class="upme-field-table-header upme-field-table-header-extend manage-column column-columnname "  scope="col"><?php _e('Field Type','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Separator is a section title. A Profile Field can hold data and can be assigned to any user meta field.','upme'); ?>"></i>
        			</div>
        
                    <!--
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Tooltip','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Please note that tooltips can be activated only for Social buttons such as Facebook, E-mail. Enter tooltip text here.','upme'); ?>"></i>
        			</div>
                    -->

        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Social','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Make a field Social to have it appear as a button on the head of profile such as Facebook, Twitter, Google+ buttons.','upme'); ?>"></i>
        			</div>
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('User can edit','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Allow or do not allow user to edit this field.','upme'); ?>"></i>
        			</div>

                    <!--
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Allow HTML','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('If yes, users will be able to write HTML code in this field.','upme'); ?>"></i>
        			</div>
                    -->

        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('User can hide','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Allow user to show/hide this profile field from public view.','upme'); ?>"></i>
        			</div>
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Private','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Only admins can see private fields.','upme'); ?>"></i>
        			</div>
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Required','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('This is mandatory field for registration and edit profile.','upme'); ?>"></i>
        			</div>
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Show in Registration','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Show this field on the registration form? If you choose no, this field will be shown on edit profile only and not on the registration form. Most users prefer fewer fields when registering, so use this option with care.','upme'); ?>"></i>
        			</div>
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Edit','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Click to edit this profile field.','upme'); ?>"></i>
        			</div>
        			<div class="upme-field-table-header manage-column column-columnname" scope="col"><?php _e('Trash','upme'); ?><i
        				class="upme-icon upme-icon-question-circle upme-tooltip"
        				title="<?php _e('Click to remove this profile field.','upme'); ?>"></i>
        			</div>

                    <div style='clear:both'></div>
        		</div>

        		
                <?php
                    if($ajax_for_custom_fields){
                        //echo '<form method="post" action="" id="upme-custom-field-edit">';
                    }
                ?>
        
                
                <ul id="upme-form-customizer-table-data">
        		<?php
        
        
        		$i = 0;
        		
        		foreach($fields as $pos => $array) {
//        		      echo "<pre>";print_r($fields);exit;
        		 
        		    extract($array); $i++;
        
        		    if(!isset($required))
        		        $required = 0;
        
        		    if(!isset($fonticon))
        		        $fonticon = '';
        
        	   
        		    ?>
        
        		<li
        			class="<?php if ($i %2) { echo 'alternate'; } else { echo ''; } ?> upme-field-setting-row"
        			id="value-holder-tr-<?php echo $pos; ?>" data-field-item-meta="<?php echo $meta; ?>" >
        
        			<!--  <td class="column-columnname"><?php #echo $pos; ?></td>  -->
        
        			<div class="upme-field-table-data column-columnname"><?php
        			if (isset($array['icon']) && $array['icon']) {
        			    echo '<i class="upme-icon upme-icon-'.$icon.'"></i>';
        			} else {
        			    echo '&mdash;';
        			}
        			?>
        			</div>
        
        
        			<div class="upme-field-table-data upme-field-table-data-extend  column-columnname"><?php
        			if (isset($array['name']) && $array['name'])
        			    echo  esc_html(__($array['name'],'upme'));
        			//if ($name) echo $name;
        			?>
        			</div>
        
        
        			<div class="upme-field-table-data upme-field-table-data-extend  column-columnname"><?php
        			if (isset($array['meta']) && $array['meta']) {
        			    echo esc_html($meta);
        			} else {
        			    echo '&mdash;';
        			}
        			?>
        			</div>
        
        
        			<div class="upme-field-table-data upme-field-table-data-extend  column-columnname"><?php
        			if (isset($array['field']) && $array['field']) {
        			    echo $field;
        			} else {
        			    echo '&mdash;';
        			}
        			?>
        			</div>
        
        			<div class="upme-field-table-data upme-field-table-data-extend  column-columnname"><?php
        			if ($type == 'separator') {
        			    echo __('Separator','upme');
        			} else {
        			    echo __('Profile Field','upme');
        			}
        			?>
        			</div>
        
                    <!--
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['tooltip']) && $array['tooltip']) $tooltip = $array['tooltip']; else $tooltip = '&mdash;';
        			echo $tooltip;
        			?>
        			</div>
                    -->
        
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['social'])) {
        			    if ($social == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
        
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['can_edit'])) {
        			    if ($can_edit == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
        
                    <!--
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['allow_html'])) {
        			    if ($allow_html == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
                    -->

        
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['can_hide']) && isset($array['private']) && $private != 1) {
        			    if ($can_hide == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
        
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['private'])) {
        			    if ($private == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
        
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['required'])) {
        			    if ($required == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
        
        
        
        			<div class="upme-field-table-data  column-columnname"><?php
        			if (isset($array['show_in_register'])) {
        			    if ($show_in_register == 1) {
        			        echo '<i class="upme-ticked"></i>';
        			    }
        			}
        			?>
        			</div>
        
        			<div class="upme-field-table-data  column-columnname">
                        <a href="#quick-edit" class="upme-edit" data-field-meta="<?php echo $meta; ?>" ><i
        					class="upme-icon upme-icon-pencil"></i> </a>
        			</div>
        
        			<div class="upme-field-table-data  column-columnname">
        				<?php if( isset($array['meta']) && ('user_pass' == $array['meta'] || 'user_pass_confirm' == $array['meta'] )){ 
        					echo '&mdash;';
        				}else{ ?>
        					<a
        						href="<?php echo esc_url(add_query_arg( array ('trash_field' => $pos ) )); ?>"
        						class="upme-trash" onclick="return confirmAction()"><i
        						class="upme-icon upme-icon-remove"></i> </a>
        				<?php } ?>
        			</div>
                    <div style="clear:both"></div>
        		
                
        
        		<!-- edit field -->
                
        		<div class="upme-editor" id="value-editor-tr-<?php echo $pos; ?>">                
                    <div style="height:40px;padding:20px;text-align:center;" class="upme-field-edit-loader "><?php _e('Processing....','upme'); ?></div>
                </div>

        		</li>

                

        		<?php } ?>
                
                </ul>

                <?php
                    if($ajax_for_custom_fields){
                        //echo '</form>';
                    }
                ?>
                

                <div class="upme-field-table-footers ignore">
        
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Icon','upme'); ?>
                    </div>
        
                    <div class="upme-field-table-footer-extend upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Label','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('The label that appears in front-end profile view or edit.','upme'); ?>"></i>
                    </div>
        
                    <div class="upme-field-table-footer-extend upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Meta Key','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('This is the meta field that stores this specific profile data (e.g. first_name stores First Name)','upme'); ?>"></i>
                    </div>
        
                    <!--
                    <div class="upme-field-table-footer-extend upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Field Input','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('This column tells you the field input that will appear to user for this data.','upme'); ?>"></i>
                    </div>
                    -->
        
                    <div class="upme-field-table-footer-extend upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Field Type','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Separator is a section title. A Profile Field can hold data and can be assigned to any user meta field.','upme'); ?>"></i>
                    </div>
        
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Tooltip','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Please note that tooltips can be activated only for Social buttons such as Facebook, E-mail. Enter tooltip text here.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Social','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Make a field Social to have it appear as a button on the head of profile such as Facebook, Twitter, Google+ buttons.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('User can edit','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Allow or do not allow user to edit this field.','upme'); ?>"></i>
                    </div>

                    <!--
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Allow HTML','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('If yes, users will be able to write HTML code in this field.','upme'); ?>"></i>
                    </div>
                    -->

                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('User can hide','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Allow user to show/hide this profile field from public view.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Private','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Only admins can see private fields.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Required','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('This is mandatory field for registration and edit profile.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Show in Registration','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Show this field on the registration form? If you choose no, this field will be shown on edit profile only and not on the registration form. Most users prefer fewer fields when registering, so use this option with care.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Edit','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Click to edit this profile field.','upme'); ?>"></i>
                    </div>
                    <div class="upme-field-table-footer manage-column column-columnname" scope="col"><?php _e('Trash','upme'); ?><i
                        class="upme-icon upme-icon-question-circle upme-tooltip"
                        title="<?php _e('Click to remove this profile field.','upme'); ?>"></i>
                    </div>

                    <div style="clear:both"></div>
                </div>
        

        
        </div>
        <table>
            <tr>
                <td style="padding-top: 15px;">
                    <?php 

                        $btn_type = 'submit';
                        $all_field_update_class = '';
                        if($ajax_for_custom_fields){
                            $btn_type = 'button';
                            $all_field_update_class = 'upme-all-field-update';
                        }
    
                        echo UPME_Html::button($btn_type, array(
                            'name' => 'submit',
                            'id' => 'submit',
                            'value' => __('Save Field Order', 'upme'),
                            'class' => 'button button-primary upme-custom-field-order'
                        ));

//                        echo UPME_Html::button($btn_type, array(
//                            'name' => 'submit',
//                            'id' => 'submit',
//                            'value' => __('Save Changes', 'upme'),
//                            'class' => 'button button-primary '.$all_field_update_class
//                        ));
                        echo '&nbsp;&nbsp;&nbsp;'; 
                        echo UPME_Html::button($btn_type, array(
                           'name' => 'reset-options-fields',
                           'value' => __('Reset to Default Fields', 'upme'),
                           'class' => 'button button-secondary upme-field-reset'
                        ));
                    ?>

                    <span id="upme_all_update_processing" class='update_processing'></span>        
                </td>
            </tr>
        </table>
        
    <?php
        if(!$ajax_for_custom_fields){
            //echo '</form>';
        }
    ?>    
        
   
</div>