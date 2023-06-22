jQuery(document).ready(function() {

    // Toggle content for metaboxes
    jQuery('.hndle, .handlediv').click(function() {
        jQuery(this).parent().toggleClass('closed');
        if (jQuery(this).parent().hasClass('closed')) {

        }
    });

    jQuery('.dts-meta-box-container').hide();

    jQuery('.dts-type-options-container input').change(function() {
        if (jQuery(this).val() == 'mysql') {
            jQuery('#mysql-table').change();
        }

        jQuery('.dts-meta-box-container').slideUp();
        var container = jQuery(this).data('container');

        jQuery('#dts-'+container).slideDown();
    });

    jQuery('#mysql-table').change(function() {
        var table = jQuery(this).val();

        if (table == -1) {
            if (!jQuery('#mysql-query').val()) {
                var firstTable = jQuery('option[value!=-1][value!=""]', this).first().val();
                jQuery('#mysql-query').val('SELECT * FROM `'+firstTable+'`');
            }

            jQuery('#mysql-query').parent().show();
        } else {
            jQuery('#mysql-query').parent().hide();
        }
    });


    function loadPreview()
    {
        myCodeMirror.save();
        var url = jQuery('#dts-load-preview-url').val();
        jQuery.post(url, jQuery('#dts-add-source-form').serialize(), function(data) {
            jQuery('#dts-preview .inside').html(data);
            jQuery('#dts-preview').show();
        });
    }

    // CSV, XLS, XML and Google Spreadsheet files
    jQuery('#upload-csv, #upload-xls, #upload-xml, #google-spreadsheet').blur(function() {
        // Check if changed
        loadPreview();
    });

    jQuery('#upload-xml').change(function() {
        jQuery('#xml-parent-path option[value!=""]').remove();
    });

    jQuery('#xml-parent-path').change(function() {
        loadPreview();
    });

    jQuery('.preview-btn').click(function() {
        loadPreview();
    });

    jQuery('#wordpress-post-type').change(function() {
        loadPreview();
    });

    jQuery('#mysql-custom').change(function() {
        if (jQuery(this).is(':checked')) {
            jQuery('#dts-selected-db-tab').hide();
            jQuery('#dts-select-db-tab').show();
        } else {
            jQuery('#mysql-table').html(jQuery('#mysql-table-temp').html());
            jQuery('#dts-selected-db-tab').show();
            jQuery('#dts-select-db-tab').hide();
        }
    });

    jQuery('.check-db-btn').click(function() {
        clearErrors();
        jQuery.post(jQuery('#dts-check-database-url').val(), jQuery('#dts-select-db-tab input').serialize(), function(data) {
            if (data.status) {
                jQuery('#dts-selected-db-tab').show();
                jQuery('#mysql-table option').each(function (index, item) {
                    if (jQuery(item).attr('value') && jQuery(item).attr('value') != '-1') {
                        jQuery(item).remove();
                    }
                });
                jQuery(data.data).each(function(index, table) {
                    jQuery('#mysql-table').append('<option value="'+table[0]+'">'+table[0]+'</option>');
                });

                var oldTable = jQuery('#old-mysql-table').val();
                if (oldTable) {
                    jQuery('#mysql-table option[value='+oldTable+']').attr('selected', 'selected');
                }
            } else {
                // display error messages
                displayErrors(data.errors);
            }
        }, 'json');
    });


    // Uploading files
    var file_frames = {
        xls: false,
        csv: false,
        xml: false,
    };

    jQuery('.dts-upload').on('click', function( event ){
        var targetField = jQuery( this ).data( 'target-field' );
        var targetType = jQuery( this ).data( 'type' );

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frames[targetType] ) {
            // Set the post ID to what we want
            file_frames[targetType].open();
            return;
        }

        // Create the media frame.
        file_frames[targetType] = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'dialog-title' ),
            button: {
                text: jQuery( this ).data( 'button-text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frames[targetType].on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frames[targetType].state().get('selection').first().toJSON();

            // Do something with attachment.id and/or attachment.url here
            jQuery("#"+targetField).val(attachment.url).blur();
        });

        // Finally, open the modal
        file_frames[targetType].open();
    });

    jQuery('#dts-add-source-form').submit(function() {
        clearErrors();
        myCodeMirror.save();
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

    function clearErrors()
    {
        jQuery('.dts-error-notice').remove();
    }

    if (jQuery('.dts-type-options-container input:checked').length == 0) {
        jQuery('.dts-type-options-container input').first().attr('checked','checked').change();
    } else {
        jQuery('.dts-type-options-container input:checked').change();
    }

    if (jQuery('#old-mysql-table').val()) {
        jQuery('.check-db-btn').click();
    }

    var myCodeMirror = CodeMirror.fromTextArea(
        jQuery('#mysql-query').get(0),
        {
            mode: 'text/x-mysql',
            lineWrapping: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            matchBrackets : true
        }
    );

    if (jQuery('#dts-add-source-form').hasClass('edit')) {
        loadPreview();
    }
});