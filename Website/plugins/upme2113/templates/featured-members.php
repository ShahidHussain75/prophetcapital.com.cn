<?php
    global $upme_admin,$upme_options,$upme_roles;

    $users = array();
    $users = get_users(array('fields'=>'ID'));
    
?>

<div class="upme-tab-content" id="upme-featured-members-settings-content" style="display:none;">
    <h3><?php _e('Manage Featured Member Features','upme');?>
        </h3>
        
        
    
    <div id="upme-featured-members-settings" class="upme-featured-members-screens" style="display:block">

        <form id="upme-featured-members-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'featured_members_enabled_status',
                                'featured_members_enabled_status',
                                __('Featured Members Status', 'upme'),
                                array('0' => __('Disabled','upme'), '1' => __('Enabled' , 'upme') ),
                                __('You can enable/disable the featured member feature on member list.', 'upme'),
                                __('If you are not using this feature, mark in as disabled to improve performence.', 'upme'),
                                array('class'=> 'chosen-admin_setting','init_value' => '')
                        );
                            
                        
                        

                    ?>


                    
                    <tr >
                        <th scope="row">
                            <label><?php _e('Update Featured Member Cache','upme'); ?></label>
                        </th>
                        <td id="">
                            <p><?php 
                                echo sprintf(__('You have total <span id="upme-total-user" style="font-weight: bold;">%s</span> users in your website.', 'upme'), count($users));
                            ?></p>
                                <p>
                            <?php 
                                _e('<p id="upme-processing-featured-tag" style="display:none;">Processing.... <span id="upme-completed-featured-users" style="display:none;"> users Completed</span> </p>','upme');
                            ?>
                            </p>
                            <p id="upme-upgrade-featured-success" style="display:none;">
                            <span style="color: green; font-weight: bold;"><?php _e('Featured Member Cache Updated.','upme')?></span>
                            </p>
                            <?php 

                                echo UPME_Html::button('button', 
                                            array(
                                                'name' => 'reset-options-fields',
                                                'id' => 'upme-update-featured-member-cache',
                                                'value' => __('Update Featured Member Cache', 'upme'),
                                                'class' => 'button button-primary'
                                            )
                                        );
                            ?>
                        </td>
                    </tr>
                    
                     <tr><th colspan="2"><div class="upme-module-settings-sub-title"><?php echo __('Featured Member Levels','upme'); ?></div></th></tr>
                   
                    <?php
                        $upme_admin->add_plugin_setting(
                                'input',
                                'featured_member_level_1_color',
                                __('Featured Level 1 Color', 'upme'), array(),
                                sprintf(__('Provide color for the featured level using valid color code (Ex:#FF1111)', 'upme'), ini_get('upload_max_filesize')),
                                __('Users with this featured level will have the profile header highlighted using the specified color.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                                'input',
                                'featured_member_level_2_color',
                                __('Featured Level 2 Color', 'upme'), array(),
                                sprintf(__('Provide color for the featured level using valid color code (Ex:#FF1111)', 'upme'), ini_get('upload_max_filesize')),
                                __('Users with this featured level will have the profile header highlighted using the specified color.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                                'input',
                                'featured_member_level_3_color',
                                __('Featured Level 3 Color', 'upme'), array(),
                                sprintf(__('Provide color for the featured level using valid color code (Ex:#FF1111)', 'upme'), ini_get('upload_max_filesize')),
                                __('Users with this featured level will have the profile header highlighted using the specified color.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                                'input',
                                'featured_member_level_4_color',
                                __('Featured Level 4 Color', 'upme'), array(),
                                sprintf(__('Provide color for the featured level using valid color code (Ex:#FF1111)', 'upme'), ini_get('upload_max_filesize')),
                                __('Users with this featured level will have the profile header highlighted using the specified color.', 'upme')
                        );

                        $upme_admin->add_plugin_setting(
                                'input',
                                'featured_member_level_5_color',
                                __('Featured Level 5 Color', 'upme'), array(),
                                sprintf(__('Provide color for the featured level using valid color code (Ex:#FF1111)', 'upme'), ini_get('upload_max_filesize')),
                                __('Users with this featured level will have the profile header highlighted using the specified color.', 'upme')
                        );
                    ?>
                    
                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-featured-members-settings', 'id'=>'save-upme-featured-members-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-featured-members-settings', 'id'=>'reset-upme-featured-members-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>