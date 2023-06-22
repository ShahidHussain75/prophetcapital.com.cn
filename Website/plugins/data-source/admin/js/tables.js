jQuery(document).ready(function() {
    // Toggle content for metaboxes
    jQuery('.hndle, .handlediv').click(function() {
        jQuery(this).parent().toggleClass('closed');
        if (jQuery(this).parent().hasClass('closed')) {

        }
    });

    // Toggle for Show all
    jQuery(document).on('change', '#dts-show-all-columns', function() {
        if (jQuery(this).is(':checked')) {
            jQuery('.dts-columns-table input.dts-show-checkbox').attr('checked', 'checked').change();
        } else {
            jQuery('.dts-columns-table input.dts-show-checkbox').removeAttr('checked').change();
        }
    });

    // Disable/enable row fields on "show" change
    jQuery(document).on('change', 'input.dts-show-checkbox', function() {
        var enable = jQuery(this).is(':checked');
        jQuery('input, select', jQuery(this).parent().parent()).each(function(index, item) {
            if (!jQuery(item).hasClass('dts-show-checkbox')) {
                if (enable) {
                    jQuery(item).removeAttr('disabled');
                } else {
                    jQuery(item).attr('disabled', 'disabled');
                }
            }
        });
    });

    // Form switcher for design type toggle
    jQuery('#dts-table-design-type').change(function() {
        var id = jQuery('option:selected', this).data('tab');

        jQuery('.dts-table-styles-tab').hide();
        jQuery('#'+id).show();
    });

    // Init color picker
    jQuery(".dts-color").spectrum({
        showInput: true,
        preferredFormat: 'hex',
        allowEmpty: true
    });

    // Save styles
    jQuery('#dts-save-styles').click(function() {
        jQuery.post(
            jQuery('#dts-save-styles-url').val(),
            jQuery('.dts-options-table input, .dts-options-table select').serialize(),
            function(data) {
                if (data.status) {
                    displayStyles(data.data);
                } else {
                    alert(data.errors[0]);
                }
                // reload table styles
            },
            'json'
        );
    });

    // Change table design action
    jQuery(document).on('click', '.dts-table-design-sample', function() {
        jQuery('.dts-table-design-sample').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('#dts-existing-table-style').val(jQuery(this).data('style-name'));
        loadPreview();
        fillCustomizeStylesForm(jQuery(this).data('styles'));
    });

    // Apply preview options to table preview
    function updatePreviewOptions()
    {
        if (jQuery('#dts-header').is(':checked')) {
            jQuery('.dts-table-preview table thead').show();
        } else {
            jQuery('.dts-table-preview table thead').hide();
        }

        if (jQuery('#dts-footer').is(':checked')) {
            jQuery('.dts-table-preview table tfoot').show();
        } else {
            jQuery('.dts-table-preview table tfoot').hide();
        }

        if (jQuery('#dts-pagination').is(':checked')) {
            jQuery('.dts-table-preview .dts-pagination').show();
        } else {
            jQuery('.dts-table-preview .dts-pagination').hide();
        }
    }

    // Manual reload preview handler
    jQuery('.table-preview-btn').click(function() {
        loadPreview();
    });

    // Dynamically apply preview options
    jQuery('#table-options-container input[type="checkbox"]').change(function() {
        updatePreviewOptions();
    });

    // Fill customize styles form
    function fillCustomizeStylesForm(styles)
    {
        jQuery('.dts-color').val('');
        jQuery('.dts-color').spectrum({'color': '', allowEmpty: true, showInput: true, preferredFormat: 'hex'});

        jQuery.each(styles, function(name, value) {
            var element = jQuery('[name="design\\['+name+'\\]"]');
            element.val(value);
            if (element.hasClass('dts-color')) {
                element.spectrum({'color': value, allowEmpty: true, showInput: true, preferredFormat: 'hex'});
            }
        });
    }

    // Display styles
    function displayStyles(styles)
    {
        jQuery('.dts-table-designs-container .dynamic').remove();
        jQuery.each(styles, function (index, item) {
            // Copy table template
            var div = jQuery('#dts-table-sample-template').clone();

            div.data('styles', item);
            div.data('style-name', item.name);

            div.removeAttr('id').addClass('dynamic').show().prependTo('.dts-table-designs-container');

            var table = jQuery('table', div);

            if (item.table_border) {
                jQuery(table).css('border', '1px solid '+item.table_border);
            }

            if (item.header_background) {
                jQuery('th', table).css('background', item.header_background);
            }

            if (item.header_text) {
                jQuery('th', table).css('color', item.header_text);
            }

            // Body styles
            if (item.body_text) {
                jQuery('td', table).css('color', item.body_text);
            }

            if (item.table_borders_type == 'horizontal') {
                jQuery('td, th', table).css('border-top', '1px solid '+item.table_border);
            } else {
                jQuery('td, th', table).css('border', '1px solid '+item.table_border);
            }

            if (item.body_even_background) {
                jQuery('tr td', table).css('background', item.body_even_background);
            }

            if (item.body_odd_background) {
                jQuery('tr:nth-of-type(2n+1) td', table).css('background', item.body_odd_background);
            }

            // Pagination styles
            if (item.pagination_color) {
                jQuery('.dts-table-design-pagination', div).css('color', item.pagination_color);
            }

            if (item.pagination_background) {
                jQuery('.dts-table-design-selected', div).css('background', item.pagination_background);
            }

            if (item.pagination_selected_color) {
                jQuery('.dts-table-design-selected', div).css('color', item.pagination_selected_color);
            }
        });

        if ( jQuery('#dts-existing-table-style').val() && (jQuery('#dts-table-design-type').val()=='dts_existing') ) {
            var className = jQuery('#dts-existing-table-style').val();

            jQuery('.dts-table-design-sample.dynamic').each(function(index, item) {
                if (jQuery(item).data('style-name') == className) {
                    jQuery(item).click();
                }
            });
        }
    }

    // Load styles
    function loadStyles(callback)
    {
        jQuery.get(jQuery('#dts-load-styles-url').val(), function(data) {
            var styles = data.data;
            displayStyles(styles);
            if (typeof callback !== 'undefined') {
                callback();
            }
        }, 'json');
    }

    // Load preview
    function loadPreview()
    {
        if (jQuery('#data-source').val()) {
            myCodeMirror.save();
            jQuery.post(
                jQuery('#dts-load-preview-url').val(),
                jQuery('#dts-add-table-form').serialize(),
                function(data) {
                    jQuery('#dts-table-preview .inside').html(data);
                    updatePreviewOptions();
                },
                'html'
            );
        }
    }

    // On data source change - reload columns or make them empty
    jQuery('#data-source').change(function() {
        var dataSource = jQuery(this).val();

        if (!dataSource) {
            // Remove preview and columns
            jQuery('#dts-table-columns .inside').empty();
            jQuery('#dts-table-preview .inside').empty();
            fillSortBy();
        } else {
            jQuery.get(jQuery(this).data('url'), {'datasource': dataSource}, function(data) {
                jQuery('#dts-table-columns .inside').html(data);
                jQuery( "#dts-table-columns .inside table tbody" ).sortable({
                    placeholder: 'dts-state-highlight'
                });
                jQuery('.dts-columns-table input.dts-show-checkbox').change();
                fillSortBy();
                loadPreview();
            }, 'html');
        }
    });

    // Dynamically fill "sort by" options
    function fillSortBy()
    {
        jQuery('#dts-sortby option').each(function(index, item) {
            if (jQuery(item).attr('value')) {
                jQuery(item).remove();
            }
        });

        jQuery('.dts-column-name').each(function(index, item) {
            var val = jQuery(item).text();
            jQuery('#dts-sortby').append('<option value="'+val+'">'+val+'</option>');
        });

        if (jQuery('#dts-sortby').data('val')) {
            jQuery('#dts-sortby option[value="'+jQuery('#dts-sortby').data('val')+'"]').attr('selected', 'selected');
            jQuery('#dts-sortby').removeData('val');
        }
    }

    // Form submit handler
    jQuery('#dts-add-table-form').submit(function() {
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

    // Initial state
    jQuery('#dts-table-design-type').trigger('change');

    if (jQuery('#dts-table-design-type').val() == 'dts_custom') {
        fillCustomizeStylesForm(jQuery.parseJSON(jQuery('#dts-current-design').val()));
    }

    // Check if current form is edit form
    if (jQuery('#dts-add-table-form').hasClass('edit')) {
        jQuery('#data-source').change();
    }

    // Load styles and set first one as active
    loadStyles(function() {
        if (jQuery('#dts-table-design-type').val()=='dts_existing' && !jQuery('#dts-existing-table-style').val()) {
            jQuery('.dts-table-design-sample.dynamic').first().click();
        }
    });

    var myCodeMirror = CodeMirror.fromTextArea(
        jQuery('#table-css').get(0),
        {
            mode: 'text/css',
            lineWrapping: true,
            extraKeys: {"Ctrl-Space": "autocomplete"},
            matchBrackets : true
        }
    );
});