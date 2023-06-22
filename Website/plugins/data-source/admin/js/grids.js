jQuery(document).ready(function() {
    var dtsColumns = [];

    // Toggle content for metaboxes
    jQuery('.hndle, .handlediv').click(function() {
        jQuery(this).parent().toggleClass('closed');
        if (jQuery(this).parent().hasClass('closed')) {

        }
    });

    // Manual reload preview handler
    jQuery('.grid-preview-btn').click(function() {
        loadPreview();
        return false;
    });

    // Init color picker
    jQuery(".dts-color").spectrum({
        showInput: true,
        preferredFormat: 'hex',
        allowEmpty: true
    });

    // Add background color
    jQuery('.dts-add-bg-color').click(function() {
        jQuery('#dts-background-colors tbody').append('<tr><td><input class="dts-color" type="text" name="background[]" /> <a href="#">[ X ]</a></td></tr>');
        jQuery("#dts-background-colors tbody .dts-color").last().spectrum({
            showInput: true,
            preferredFormat: 'hex',
            allowEmpty: true
        });
        return false;
    });

    // Remove background color
    jQuery(document).on('click', '#dts-background-colors tbody a', function() {
        jQuery(this).parent().parent().remove();
        return false;
    });

    // Update editor content
    function updateEditorContent()
    {
        if (!jQuery("#grid_template").is(':visible')) {
            var editor = tinyMCE.get('grid_template');
            jQuery("#grid_template").val(editor.getContent());
        }
    }

    // Load preview
    function loadPreview()
    {
        if (jQuery('#data-source').val()) {
            updateEditorContent();
            jQuery.post(
                jQuery('#dts-load-preview-url').val(),
                jQuery('#dts-add-grid-form').serialize(),
                function(data) {
                    jQuery('#dts-grid-preview .inside').html(data);
                },
                'html'
            );
        }
    }

    // Dynamically fill "sort by" and "placeholders"
    function fillColumns()
    {
        jQuery('option[value!=""]', jQuery('#dts-sortby')).remove();
        jQuery('#side-placeholders .inside').empty();

        jQuery(dtsColumns).each(function(index, item) {
            jQuery('#side-placeholders .inside').append('<p><a href="#">%'+item.name+'%</a></p>');
            jQuery('#dts-sortby').append('<option value="'+item.name+'">'+item.name+'</option>');
        });

        jQuery('#side-placeholders .inside a').click(function() {
            if (!jQuery("#grid_template").is(':visible')) {
                var editor = tinymce.get('grid_template');
                editor.insertContent(jQuery(this).text());
            } else {
                var caretPos = document.getElementById("grid_template").selectionStart;
                var textAreaTxt = jQuery("#grid_template").val();
                var txtToAdd = jQuery(this).text();
                jQuery("#grid_template").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
            }

            return false;
        });
    }

    // Load columns
    function loadColumns(callback)
    {
        var dataSource = jQuery('#data-source').val();

        // Hide all meta boxes
        if (!dataSource) {
            jQuery('.dts-meta-box-container').slideUp();
        } else {
            jQuery('.dts-meta-box-container').slideDown();
        }

        if (!dataSource) {
            dtsColumns = [];
            fillColumns();
        } else {
            jQuery.get(jQuery('#data-source').data('url'), {'datasource': dataSource}, function(data) {
                dtsColumns = data.data;
                fillColumns();
                if (typeof callback !== 'undefined') {
                    callback();
                }
            }, 'json');
        }
    }

    // On data source change - reload columns and placeholders
    jQuery('#data-source').change(function() {
        loadColumns();
    });

    // Form submit handler
    jQuery('#dts-add-grid-form').submit(function() {
        clearErrors();
        updateEditorContent();
        jQuery.post(
            jQuery(this).attr('action'),
            jQuery(this).serialize(),
            function(data) {
                if (data.status) {
                    window.location = data.redirect_url;
                } else {
                    displayErrors(data.errors);
                }
            },
            'json'
        );
        return false;
    });

    // Display errors
    function displayErrors(errors)
    {
        if (!errors) return;
        var offsetTop = 0;
        jQuery.each(errors, function(name, error) {
            var elements = jQuery.find('[name="'+name+'"]');
            if (elements.length) {
                jQuery('[name="'+name+'"]').last().parents('.dts-field-container').first().append('<p class="dts-error-notice">'+error+'</p>');
                if (!offsetTop) {
                    offsetTop = jQuery('[name="'+name+'"]').last().parents('.dts-field-container').first().offset().top;
                }
            }
        });
        if (offsetTop) {
            jQuery(window).scrollTop(offsetTop);
        }
    }

    // Remove error notices
    function clearErrors()
    {
        jQuery('.dts-error-notice').remove();
    }

    // Check if current form is edit form
    if (jQuery('#dts-add-grid-form').hasClass('edit')) {
        loadColumns(function() {
            if (jQuery('#dts-sortby').data('val')) {
                jQuery('#dts-sortby option[value="'+jQuery('#dts-sortby').data('val')+'"]').attr('selected', 'selected');
                jQuery('#dts-sortby').removeData('val');
            }
        });
    }
});