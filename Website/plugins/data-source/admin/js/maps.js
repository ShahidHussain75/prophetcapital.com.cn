jQuery(document).ready(function() {
    var dtsColumns = [];
    var dtsMapType = '';
    var dtsGroupType = '';

    // Toggle content for metaboxes
    jQuery('.hndle, .handlediv').click(function() {
        jQuery(this).parent().toggleClass('closed');
        if (jQuery(this).parent().hasClass('closed')) {

        }
    });

    // Manual reload preview handler
    jQuery('.map-preview-btn').click(function() {
        loadPreview();
        return false;
    });

    // Init color picker
    jQuery(".dts-color").spectrum({
        showInput: true,
        preferredFormat: 'hex',
        allowEmpty: true
    });

    // Load preview
    function loadPreview()
    {
        if (jQuery('#data-source').val()) {
            jQuery('#dts-map-preview').slideDown();
            jQuery.post(
                jQuery('#dts-load-preview-url').val(),
                jQuery('#dts-add-map-form').serialize(),
                function(data) {
                    jQuery('#dts-map-preview .inside').html(data);
                },
                'html'
            );
        }
    }

    function loadColumns(callback)
    {
        var dataSource = jQuery('#data-source').val();

        // Hide all meta boxes
        jQuery('.dts-meta-box-container').slideUp();

        if (!dataSource) {
            dtsColumns = [];
            fillColumns();
        } else {
            jQuery('#dts-map-type').slideDown();
            jQuery.get(jQuery('#data-source').data('url'), {'datasource': dataSource}, function(data) {
                dtsColumns = data.data;
                fillColumns();
                fillColumnSelect(jQuery('.dts-column-select-common'));
                if (typeof callback !== 'undefined') {
                    callback();
                }
            }, 'json');
        }
    }

    // Dynamically fill "sort by" and "group by" options
    function fillColumns()
    {
        jQuery('#dts-group-by-columns').empty();

        jQuery(dtsColumns).each(function(index, item) {
            jQuery('#dts-group-by-columns').append('<p><label><input type="checkbox" value="'+item.name+'" name="groupby[]" /> '+item.name+'</label></p>');
        });
    }

    function fillColumnSelect(element)
    {
        jQuery('option[value!=""]', element).remove();

        jQuery(dtsColumns).each(function(index, item) {
            jQuery(element).append('<option value="'+item.name+'">'+item.name+'</option>');
        });
    }

    // On data source change - reload columns or make them empty
    jQuery('#data-source').change(function() {
        loadColumns();
    });

    jQuery('input[name="map_name"]').change(function() {
        jQuery('input[name=group]').removeAttr('checked', 'checked');
        jQuery('#dts-group-by-columns').hide();
        jQuery('#dts-map-group').slideDown();

        if (jQuery(this).val() == 'geochart') {
            jQuery('.geochart-settings').show();
            jQuery('.googlemap-settings').hide();
        } else {
            jQuery('.geochart-settings').hide();
            jQuery('.googlemap-settings').show();
        }

        jQuery('#dts-map-columns, #dts-map-styles, #dts-map-preview').slideUp();
        return false;
    });

    jQuery('input[name=group]').change(function() {
        dtsGroupType = jQuery(this).val();

        if (jQuery(this).val() == 'fields') {
            jQuery('#dts-group-by-columns').show();
            jQuery('.dts-function-select-common').show();
        } else {
            jQuery('#dts-group-by-columns').hide();
            jQuery('.dts-function-select-common').hide();
        }

        jQuery('#dts-map-columns').slideDown();
        jQuery('#dts-map-styles').slideDown();
    });

    // GeoChart address type switch
    jQuery('input[name="address_type"]').change(function() {
        if (jQuery(this).val() == 'latlng') {
            jQuery('#dts-address-type-latlng').show();
            jQuery('#dts-address-type-text').hide();
        } else {
            jQuery('#dts-address-type-latlng').hide();
            jQuery('#dts-address-type-text').show();
        }
    });

    // Form submit handler
    jQuery('#dts-add-map-form').submit(function() {
        clearErrors();
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

    if (jQuery('#dts-add-map-form').hasClass('edit')) {
        loadColumns(function() {
            var selectedGroup = jQuery('input[name=group]:checked').val();

            jQuery('input[name="map_name"]:checked').change();
            jQuery('input[name=group][value="' + selectedGroup + '"]').attr('checked', 'checked').change();

            jQuery('.dts-column-select-common, .dts-function-select-common').each(function(index, item) {
                jQuery('option[value="'+jQuery(item).data('val')+'"]', item).attr('selected', 'selected');
                jQuery(item).removeData('val');
            });

            if (jQuery('#dts-groupby').val()) {
                var groupby = jQuery.parseJSON(jQuery('#dts-groupby').val());
                jQuery.each(groupby, function(index, item) {
                    jQuery('#dts-group-by-columns input[value="'+item+'"]').attr('checked', 'checked');
                });
            }

            jQuery('input[name="address_type"]:checked').change();
        });
    } else {
        loadColumns();
    }
});