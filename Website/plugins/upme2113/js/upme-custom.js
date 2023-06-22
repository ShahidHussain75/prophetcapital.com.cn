jQuery(document).ready(function($) {
    /* Nice file upload */
    // Calling hidden and native element's action
    $('.upme-fileupload').click(function(){
        if($('#file_'+$(this).attr('id')).length > 0)
            $('#file_'+$(this).attr('id')).click();
    });

    // replace selected image path in custom div
    $('.upme-fileupload-field').change(function(){
        if($('#'+$(this).attr('name')).length > 0)
            $('#'+$(this).attr('name')).text($(this).val());
		
    });

    /* Tooltips */
    if($.isFunction($.fn.tipsy)){
        if($('.upme-tooltip').length > 0)
        {
            $('.upme-tooltip').tipsy({
                trigger: 'hover',
                offset: 4
            });
        }
    }
	

    if($('.upme-go-to-page').length > 0)
    {
        $('.upme-go-to-page').on('change', function(){
        	if($(this).val() != 0)
        	{
            	jQuery('#userspage').val($(this).val());
            	jQuery( "#upme_search_form" ).submit();
        	}
        });
    }
	
	
    /* Check/uncheck */
    $('.upme-hide-from-public').click(function(e){
        e.preventDefault();
        if ($(this).find('i').hasClass('upme-icon-square-o')) {
            $(this).find('i').addClass('upme-icon-check-square-o').removeClass('upme-icon-square-o');
            $(this).find('input[type=hidden]').val(1);
        } else {
            $(this).find('i').addClass('upme-icon-square-o').removeClass('upme-icon-check-square-o');
            $(this).find('input[type=hidden]').val(0);
        }
    });

    $('.upme-rememberme').click(function(e){
        e.preventDefault();
        if ($(this).find('i').hasClass('upme-icon-square-o')) {
            $(this).find('i').addClass('upme-icon-check-square-o').removeClass('upme-icon-square-o');
            $(this).find('input[type=hidden]').val(1);
        } else {
            $(this).find('i').addClass('upme-icon-square-o').removeClass('upme-icon-check-square-o');
            $(this).find('input[type=hidden]').val(0);
        }
    });
	
		
    /* Toggle edit inline */
    $('.upme-field-edit a.upme-fire-editor').click(function(e){
        e.preventDefault();
        toggle_edit_inline($(this));
        
    });

    // Check URL paremeter to trigger edit view, when profile is displayed using modal popup link
    var url = window.location.href;
    if(url.indexOf('upme_modal_target_link=yes') != -1){
        if(jQuery('.upme-field-edit a.upme-fire-editor').length > 0){
            jQuery('.upme-field-edit a.upme-fire-editor').trigger('click');  
        }        
    }
	
    /* Registration Form: Blur on email */
    jQuery('.upme-registration').find('#reg_user_email').change(function(){
        var new_user_email = $(this).val();
        jQuery.get(
            UPMECustom.AdminAjax, 
            {
                'action': 'upme_load_user_pic',
                'email':   new_user_email
            }, 
            function(response){
                $('.upme-registration .upme-pic').html(response);               
            }
        );
        //jQuery('.upme-registration .upme-pic').load(UPMECustom.UPMEUrl+'ajax/upme-get-avatar.php?email=' + new_user_email );
    });
	
    /* Change display name as User type in */
    jQuery('.upme-registration').find('#reg_user_login').bind('change keydown keyup',function(){
        if(UPMECustom.RegFormTitleUsername == '1'){
            jQuery('.upme-registration .upme-name .upme-field-name').html( jQuery('#reg_user_login').val() );
        }
    });


    // New Password request JS Code

    jQuery('[id^=upme-forgot-pass-]').on('click', function(){

        var counter = jQuery(this).attr('id').replace('upme-forgot-pass-','');
        
        jQuery('#upme-login-form-'+counter).css('display','none');
        jQuery('#upme-forgot-pass-holder-'+counter).css('display','block');
        jQuery('#login-heading-'+counter).html(UPMECustom.ForgotPass);
        
    });
    
    jQuery('[id^=upme-back-to-login-]').on('click', function(){

        var counter = jQuery(this).attr('id').replace('upme-back-to-login-',''); 
        
        jQuery('#upme-login-form-'+counter).css('display','block');
        jQuery('#upme-forgot-pass-holder-'+counter).css('display','none');
        jQuery('#login-heading-'+counter).html(UPMECustom.Login);
        
    });
    
    jQuery('[id^=upme-forgot-pass-btn-]').on('click', function(){

        var counter = jQuery(this).attr('id').replace('upme-forgot-pass-btn-','');
        
        if(jQuery('#user_name_email-'+counter).val() == '')
        {
            alert(UPMECustom.Messages.EnterDetails);
        }
        else
        {
            jQuery.post(
                UPMECustom.AdminAjax,
                {
                    'action': 'request_password',
                    'user_details':   jQuery('#user_name_email-'+counter).val()
                },
                function(response){

                    var forgot_pass_msg=
                    {
                        "invalid_email" : UPMECustom.Messages.ValidEmail,
                        "invalid"       : UPMECustom.Messages.ValidEmail,
                        "not_allowed"   : UPMECustom.Messages.NotAllowed,
                        "mail_error"    : UPMECustom.Messages.EmailError,
                        "success"       : UPMECustom.Messages.PasswordSent,
                        "default"       : UPMECustom.Messages.WentWrong
                    }

                    if(typeof(forgot_pass_msg[response]) == 'undefined')
                    {
                        alert(forgot_pass_msg['default']);
                    }
                    else
                    {
                        alert(forgot_pass_msg[response]);
                        if(response == 'success')
                            jQuery('#upme-back-to-login-'+counter).trigger('click');
                    }
    				    	
                }
                );
        }
    });

    jQuery("[id^=upme-forgot-pass-holder-]").css('display','none');


    /* Registration Form: Validate email on focus out */
    $('.upme-registration').find('#reg_user_email').blur(function(){

        var newUserEmail = $(this).val();
        var email = $(this);
        var email_reg = /^([a-zA-Z0-9+_\.\-])+\@(([a-zA-Z0-9+\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var message;

        $("#upme-reg-email-img").remove();
        $("#upme-reg-email-msg").remove();

        if('' == newUserEmail){
            message = UPMECustom.Messages.RegEmptyEmail;
            $(email).addClass('error');
            $(email).after('<div id="upme-reg-email-msg" class="upme-input-text-inline-error" ><i id="upme-reg-email-img" original-title="Invalid" class="upme-icon upme-icon-remove upme-input-text-font-cancel" ></i>' + message + '</div>');
               
        }else if(!email_reg.test(newUserEmail)){
            message = UPMECustom.Messages.RegInvalidEmail;
            $(email).addClass('error');
            $(email).after('<div id="upme-reg-email-msg" class="upme-input-text-inline-error" ><i id="upme-reg-email-img" original-title="Invalid" class="upme-icon upme-icon-remove upme-input-text-font-cancel" ></i>' + message + '</div>');
               
        }else{
            

        jQuery.post(
            UPMECustom.AdminAjax,
            {
                'action': 'validate_register_email',
                'user_email':   newUserEmail
            },
            function(response){

                
                switch(response.msg){
                    case 'RegExistEmail':
                        message = UPMECustom.Messages.RegExistEmail;
                        break;
                    case 'RegValidEmail':
                        message = UPMECustom.Messages.RegValidEmail;
                        break;
                    case 'RegInvalidEmail':
                        message = UPMECustom.Messages.RegInvalidEmail;
                        break;
                    case 'RegEmptyEmail':
                        message = UPMECustom.Messages.RegEmptyEmail;
                        break;
                }

                if(response.status){
                    $(email).addClass('error');
                    $(email).after('<div id="upme-reg-email-msg" class="upme-input-text-inline-error" ><i id="upme-reg-email-img" original-title="Invalid" class="upme-icon upme-icon-remove upme-input-text-font-cancel" ></i>' + message + '</div>');
                }else{
                    $(email).after('<div id="upme-reg-email-msg" class="upme-input-text-inline-success" ><i id="upme-reg-email-img" original-title="Valid" class="upme-icon upme-icon-ok upme-input-text-font-accept" ></i>' + message + '</div>');
                }

            },"json");
        }

        
    });

    /* Registration Form: Validate username on focus out */
    $('.upme-registration').find('#reg_user_login').blur(function(){

        var newUserLogin = $(this).val();
        var login = $(this);

        $("#upme-reg-login-img").remove();
        $("#upme-reg-login-msg").remove();

        if('' == newUserLogin){
            message = UPMECustom.Messages.RegEmptyUsername;
            $(login).addClass('error');
            $(login).after('<div id="upme-reg-login-msg" class="upme-input-text-inline-error" ><i id="upme-reg-login-img" original-title="Invalid" class="upme-icon upme-icon-remove upme-input-text-font-cancel" ></i>' + message + '</div>');
                  
        }else{
            jQuery.post(
            UPMECustom.AdminAjax,
            {
                'action': 'validate_register_username',
                'user_login':   newUserLogin
            },
            function(response){

                var message;
                switch(response.msg){
                    case 'RegExistUsername':
                        message = UPMECustom.Messages.RegExistUsername;
                        break;
                    case 'RegValidUsername':
                        message = UPMECustom.Messages.RegValidUsername;
                        break;
                    case 'RegEmptyUsername':
                        message = UPMECustom.Messages.RegEmptyUsername;
                        break;
                    case 'RegInValidUsername':
                        message = UPMECustom.Messages.RegInValidUsername;
                        break;
                }
                
 
                if(response.status){
                    $(login).addClass('error');
                    $(login).after('<div id="upme-reg-login-msg" class="upme-input-text-inline-error" ><i id="upme-reg-login-img" original-title="Invalid" class="upme-icon upme-icon-remove upme-input-text-font-cancel" ></i>' + message + '</div>');
                }else{
                    $(login).after('<div id="upme-reg-login-msg" class="upme-input-text-inline-success" ><i id="upme-reg-login-img" original-title="Valid" class="upme-icon upme-icon-ok upme-input-text-font-accept" ></i>' + message + '</div>');
                }

            },"json");
        }

        
    });

    // Clear error messages on focus
    $('.upme-registration').find('#reg_user_login').focus(function(){
        $("#upme-reg-login-img").remove();
        $("#upme-reg-login-msg").remove();

        $(this).removeClass('error');
    });

    $('.upme-registration').find('#reg_user_email').focus(function(){
        $("#upme-reg-email-img").remove();
        $("#upme-reg-email-msg").remove();

        $(this).removeClass('error');
    });

    $('.upme-registration').find('#reg_user_pass').focus(function(){
        $(this).removeClass('error');
    });

    $('.upme-registration').find('#reg_user_pass_confirm').focus(function(){
        $(this).removeClass('error');
    });


    //  Delete uploaded images from edit profile screen
    $('body').on("click",".upme-delete-image-wrapper",function(){

        var userAction =confirm(UPMECustom.Messages.DelPromptMessage);
        if (userAction==true){
            var userId = $(this).attr("upme-data-user-id");
            var fieldName = $(this).attr("upme-data-field-name");
            var imgObject = $(this);

            $('#upme-spinner-'+fieldName).show();

            jQuery.post(
                UPMECustom.AdminAjax,
                {
                    'action': 'upme_delete_profile_images',
                    'user_id':   userId,
                    'field_name' : fieldName
                },
                function(response){
 
                    if(response.status){
                        $(imgObject).parent().parent().find('.upme-file-upload-status').val('');
                        $(imgObject).parent().remove();
                    }

                    $('#upme-spinner-'+fieldName).hide();

                },"json");
        }        
    });

    //  Delete user pic edit profile and image upload screens
    $('body').on("click",".upme-delete-userpic-wrapper",function(){

        var userAction =confirm(UPMECustom.Messages.DelPromptMessage);
        if (userAction==true){
            var userId = $(this).attr("upme-data-user-id");
            var fieldName = $(this).attr("upme-data-field-name");
            var imgObject = $(this);


            $('#upme-spinner-'+fieldName).show();

            jQuery.post(
                UPMECustom.AdminAjax,
                {
                    'action': 'upme_delete_profile_images',
                    'user_id':   userId,
                    'field_name' : fieldName
                },
                function(response){
 
                    if(response.status){
                        $(imgObject).parent().remove();
                    }

                    $('#upme-spinner-'+fieldName).hide();

                },"json");
        }        
    });

    // Submit the form on Crop link click
    //$('#upme-crop-submit').click(function(){
    //    $("#upme-crop-frm").submit();
    //});

    // Submit the form to initialize the cropping functionality
    $('#upme-crop-request').click(function(){
        var userId = $(this).attr("upme-data-user-id");
        $('#upme-crop-request-'+ userId).remove();
        $(this).append('<input id="upme-crop-request-'+ userId + '" type="hidden" name="upme-crop-request-'+ userId + '" value="1" />');
        $("#upme-crop-frm").submit();
    });

    // Validate the file upload field and submit the form to upload user picture
    $('#upme-upload-image').click(function(){

        $("#upme-crop-upload-err-block").html('');
        $("#upme-crop-upload-err-holder").hide();

        var dataMeta = $(this).attr("upme-data-meta");
        var dataId = $(this).attr("upme-data-id");
        var fileFieldValue = $('#file_'+ dataMeta + '-' + dataId).val();

        if("" == fileFieldValue){
            $("#upme-crop-upload-err-block").html('<span class="upme-error upme-error-block"><i class="upme-icon upme-icon-remove"></i> '+UPMECustom.Messages.UploadEmptyMessage+'</span>');
            $("#upme-crop-upload-err-holder").show();
        }else{
            $("#upme-crop-upload-err-holder").hide();
            $('#file_'+ dataMeta + '-' + dataId).append('<input id="upme-upload-submit-'+ dataId + '" type="hidden" name="upme-upload-submit-'+ dataId + '" value="1" />');
        
            $("#upme-crop-frm").submit(); 
        }
        
    });

    $(function(){
       if (window.location.hash){
          var hash = window.location.hash.substring(1);
          if (hash == "open"){
            toggle_edit_inline($('.upme-field-edit a.upme-fire-editor'));
          }
       }

    });
    
    
    $('#upme-reset-search').click(function(){
    	window.location = window.location;
    });

    $('.upme-search-reset').click(function(){
        window.location = window.location;
    });


    // Load fancybox modal for profiles using a ajax request
    function upme_load_modal(modal,user_id)
    {

        $.post(
            UPMECustom.AdminAjax,
            {
                'action': 'upme_initialize_profile_modal',
                'upme_id':   user_id,
                'upme_modal_profile' : 'yes'
            },
            function(response){

                $('#upme_inner_modal').html(response);
                $('#upme_inner_modal_loader').hide();
                
                $(modal).fancybox({
                    'maxHeight' : 450,
                    'minWidth' : '90%',
                    'maxWidth' : 900,
                    'autoSize': true,
                }).click();
                
                $(modal).attr('upme-data-modal-active','INACTIVE');
        });        
        // fire the click event after initializing fancybox on this element
        // this should open the fancybox
    }

    // Enable fancybox ajax request on profiles
    // use .one() so that the handler is executed at most once per element
    $('a[href="#upme_inner_modal"]').on('click', function ()
    {
        
        var user_id = $(this).attr('upme-data-user-id');
        if($(this).attr('upme-data-modal-active') == 'INACTIVE'){
            $('#upme_inner_modal').html('');
  
            
            $('#upme_inner_modal_loader').show();
            $(this).attr('upme-data-modal-active','ACTIVE');
            upme_load_modal(this,user_id);
            
        }else{
            
        }        
        return false;
    });

    $('.upme_delete_profile').click(function(){
    	var user_id = $(this).attr('data-user-id');
        if(confirm(UPMECustom.confirmDeleteProfile)){
            jQuery.post(
                UPMECustom.AdminAjax,
                {
                    'action': 'upme_profile_delete_request',
                    'user_id':   user_id
                },
                function(response){
                    $('#upme-profile-view-msg-holder').hide();
                    if(response.status == 'success'){
                        $('#upme-profile-view-msg-holder').removeClass('upme-success').removeClass('upme-errors');
                        $('#upme-profile-view-msg-holder').html(response.msg).addClass('upme-success').show();
                    }
                    

                },"json");
        }else{
            
        }
    });
    
    /* Version 2.0.29
    // Enable fancybox ajax request on popup login form
    // use .one() so that the handler is executed at most once per element
    $('a[href=#upme_login_modal]').one('click', function ()
    {
        $('#upme_inner_modal_loader').show();
        upme_load_login_modal(this);
        return false;
    });
    
    // Load fancybox modal for login form using a ajax request
    function upme_load_login_modal(modal)
    {

        $.post(
            UPMECustom.AdminAjax,
            {
                'action': 'upme_initialize_login_modal',
                'upme_modal_profile' : 'yes'
            },
            function(response){

                $('#upme_login_modal').html(response);
                $('#upme_inner_modal_loader').hide();

                $(modal).fancybox({
                    'maxHeight' : 450,
                    'minWidth' : '60%',
                    'maxWidth' : 900,
                    'autoSize': true,
                }).click();
        });        
        // fire the click event after initializing fancybox on this element
        // this should open the fancybox
    }
    */
    
    jQuery('body').on('click','.upme-separator-collapse-icon.upme-icon-arrow-down',function(){
        jQuery(this).addClass('upme-icon-arrow-up').removeClass('upme-icon-arrow-down');
        collapse_separator_fields_downwards(jQuery(this));
    });
    
    jQuery('body').on('click','.upme-separator-collapse-icon.upme-icon-arrow-up',function(){
        jQuery(this).addClass('upme-icon-arrow-down').removeClass('upme-icon-arrow-up');
        collapse_separator_fields_upwards(jQuery(this));
    });
    
    
    jQuery('.upme-separator').each(function(){
        var sepobj = jQuery(this);
     
        var collapse_up = sepobj.find('.upme-separator-collapse-icon.upme-icon-arrow-up');
        var collapse_down = sepobj.find('.upme-separator-collapse-icon.upme-icon-arrow-down');

  
        if($('#upme-edit-profile-form').hasClass('upme-fire-editor-view')){
            if(collapse_down.length > 0){
                collapse_separator_fields_upwards(collapse_down);
            }
            if(collapse_up.length > 0){
                collapse_separator_fields_downwards(collapse_up);
            }
        }
    });
    
    
    
});

function change_page(page_num)
{
	
	if(jQuery( "#upme-pagination-form" ).length > 0)
	{
		jQuery('#upme-pagination-form-per-page').val(page_num);
		jQuery( "#upme-pagination-form" ).submit();
	}else if(jQuery( "#upme_search_form" ).length > 0)
    {
        jQuery('#userspage').val(page_num);
        jQuery( "#upme_search_form" ).submit();
    }
}

function toggle_edit_inline(obj){
    // Hide success message on edit or profile button click
        
        obj.closest('.upme-view-panel').find('.upme-user-profile-tab-panel').trigger('click');
       
        jQuery('.upme-success').remove();
    
        var formObj = obj.parent().parent().parent().parent().parent();
    
        obj.parent().parent().parent().parent().parent().find('.upme-separator').each(function(){
            var sepobj = jQuery(this);

            collapse_up = sepobj.find('.upme-separator-collapse-icon.upme-icon-arrow-up');
            collapse_down = sepobj.find('.upme-separator-collapse-icon.upme-icon-arrow-down');


            if(collapse_down.length > 0){
                collapse_separator_fields_upwards(collapse_down);
            }
            if(collapse_up.length > 0){
                collapse_separator_fields_downwards(collapse_up);
            }
        });

        this_form = obj.parent().parent().parent().parent().parent();

//        if (jQuery(this_form).find('.upme-edit').is(':hidden')) {
          if(obj.hasClass('upme-fire-editor-view')){
            obj.removeClass('upme-fire-editor-view').addClass('upme-fire-editor-edit');
              
            if (jQuery(this_form).find('.upme-view').length > 0) {
                
                // Hide post container
                jQuery(this_form).find('.upme-post-head').hide();
                // Hide custom containers
                jQuery(this_form).find('.upme-custom-head').hide();

                jQuery(this_form).find('.upme-view').slideUp(function() {
                    jQuery(this_form).find('.upme-main').show();
                    jQuery(this_form).find('.upme-edit').slideDown();
                    jQuery(this_form).find('.upme-field-edit a.upme-fire-editor').html(UPMECustom.ViewProfile);
                    jQuery('.upme-inner').removeClass('upme-view-panel').addClass('upme-edit-panel');
                });
            } else {
                
                jQuery(this_form).find('.upme-main').show();

                jQuery(this_form).find('.upme-edit').slideDown();
                jQuery(this_form).find('.upme-field-edit a.upme-fire-editor').html(UPMECustom.ViewProfile);
                jQuery('.upme-inner').removeClass('upme-view-panel').addClass('upme-edit-panel');
                // Show post container
                jQuery(this_form).find('.upme-post-head').show();
                // Hide custom containers
                jQuery(this_form).find('.upme-custom-head').show();
            }
        } else {
            
            obj.addClass('upme-fire-editor-view').removeClass('upme-fire-editor-edit');

            // Show post container
            jQuery(this_form).find('.upme-post-head').show();
            // Hide custom containers
            jQuery(this_form).find('.upme-custom-head').show();
            
            jQuery(this_form).find('.upme-edit').slideUp(function() {
                if (jQuery(this_form).find('.upme-main').hasClass('upme-main-compact')) {
                    jQuery(this_form).find('.upme-main').hide();
                }
                jQuery(this_form).find('.upme-view').slideDown();
                jQuery(this_form).find('.upme-field-edit a.upme-fire-editor').html(UPMECustom.EditProfile);
            
                jQuery('.upme-inner').removeClass('upme-edit-panel').addClass('upme-view-panel');
            });
        }

        
    
        // Hide all the edit form error messages when switchin between edit and view
        jQuery('#upme-edit-form-err-holder').html('').hide();
        jQuery('#upme-edit-profile-form .error').removeClass('error');

        
}



/* Profile Tabs related codes */
jQuery(document).ready(function($) {
    $(".upme-profile-tab").click(function(){
        
        var tab_panel_container = $(this).parent().parent().parent();
        
        tab_panel_container.find(".upme-profile-tab-panel").hide();
        tab_panel_container.find(".upme-profile-tab").removeClass('upme-profile-tab-active');
        var current_panel = $(this).attr("data-tab-id");
        $(this).addClass('upme-profile-tab-active');
        tab_panel_container.find("."+current_panel).show();
        
        if(current_panel == 'upme-woocommerce-panel'){
            tab_panel_container.find('.upme-woo-account-navigation-item').show();
            tab_panel_container.find('.upme-woo-account-info').show();
        }
        
        if(current_panel == 'upme-profile-panel'){
            tab_panel_container.find('.upme-post-head').show();
            tab_panel_container.find('.upme-post-list-field').show();
            tab_panel_container.find('.upme-tab-contents').show();
            
        }else{
            tab_panel_container.find('.upme-post-head').hide();
            tab_panel_container.find('.upme-post-list-field').hide();
            tab_panel_container.find('.upme-tab-contents').hide();
        }
    });

    $(".upme-profile-tabs-panel").on("click",".upme-profile-tab-open i",function(){
        var tab_open = $(this);
        var tabs_panel = $(this).parent().parent();
        if($(this).hasClass("upme-icon-arrow-circle-down")){
            tab_open.removeClass("upme-icon-arrow-circle-down");
            tab_open.addClass("upme-icon-arrow-circle-up");
            tabs_panel.find(".upme-profile-tabs").slideDown();
        }else{
            tab_open.addClass("upme-icon-arrow-circle-down");
            tab_open.removeClass("upme-icon-arrow-circle-up");
            tabs_panel.find(".upme-profile-tabs").slideUp();
        }


    });

});

function collapse_separator_fields_upwards(obj){ 
    
        
        var ele_id = obj.attr('id');
        obj.parent().parent().find('.'+ele_id+'-field').slideUp().addClass('upme-display-none');

}

function collapse_separator_fields_downwards(obj){
    
        var ele_id = obj.attr('id');
        obj.parent().parent().find('.'+ele_id+'-field').slideDown().removeClass('upme-display-none');

}

