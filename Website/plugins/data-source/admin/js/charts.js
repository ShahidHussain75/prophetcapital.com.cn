jQuery(document).ready(function() {
    var dtsColumns = [];
    var dtsChartType = '';
    var dtsGroupType = '';
    var dtsFunctions = ['Count', 'Sum', 'Average', 'Minimum', 'Maximum'];
    var dtsChartOptions = {
        'pie': {
            'summary': 'summary-form',
            'regular': 'col1-form',
        },
        'bar': {
            'summary': 'summary-form',
            'regular': 'colN-form',
        },
        '2d': {
            'summary': false,
            'regular': 'col2-form',
        }
    };

    // Change palette option
    jQuery('input[name="palette"]').change(function() {
        if (jQuery(this).val() == 'custom') {
            jQuery('.palettes-list').show();
        } else {
            jQuery('.palettes-list').hide();
        }
    });

    jQuery('input[name="palette"]:checked').change();

    // Palette selected
    jQuery('.dts-palette').click(function() {
        jQuery('.dts-palette').removeClass('selected');
        jQuery(this).addClass('selected');

        jQuery('.palettes-list input.color').remove();
        var colors = jQuery(this).data('colors');
        jQuery(colors).each(function(index, color) {
            jQuery('.palettes-list').append('<input type="hidden" value="'+color+'" name="colors[]" class="color" />')
        });
    });

    // Toggle content for metaboxes
    jQuery('.hndle, .handlediv').click(function() {
        jQuery(this).parent().toggleClass('closed');
        if (jQuery(this).parent().hasClass('closed')) {

        }
    });

    // Manual reload preview handler
    jQuery('.chart-preview-btn').click(function() {
        loadPreview();
        return false;
    });

    // Load preview
    function loadPreview()
    {
        if (jQuery('#data-source').val()) {
            jQuery('#dts-chart-preview').slideDown();
            jQuery.post(
                jQuery('#dts-load-preview-url').val(),
                jQuery('#dts-add-chart-form').serialize(),
                function(data) {
                    jQuery('#dts-chart-preview .inside').html(data);
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
            jQuery('#dts-chart-type').slideDown();
            jQuery.get(jQuery('#data-source').data('url'), {'datasource': dataSource}, function(data) {
                dtsColumns = data.data;
                fillColumns();
                if (typeof callback !== 'undefined') {
                    callback();
                }
            }, 'json');
        }
    }

    // On data source change - reload columns or make them empty
    jQuery('#data-source').change(function() {
        loadColumns();
    });

    // Dynamically fill "sort by" and "group by" options
    function fillColumns()
    {
        jQuery('#dts-sortby option').each(function(index, item) {
            if (jQuery(item).attr('value')) {
                jQuery(item).remove();
            }
        });
        jQuery('#dts-group-by-columns').empty();

        jQuery(dtsColumns).each(function(index, item) {
            jQuery('#dts-sortby').append('<option value="'+item.name+'">'+item.name+'</option>');
            jQuery('#dts-group-by-columns').append('<p><label><input type="checkbox" value="'+item.name+'" name="groupby[]" /> '+item.name+'</label></p>');
        });

        if (jQuery('#dts-sortby').data('val')) {
            jQuery('#dts-sortby option[value="'+jQuery('#dts-sortby').data('val')+'"]').attr('selected', 'selected');
            jQuery('#dts-sortby').removeData('val');
        }
    }

    function fillColumnSelect(element)
    {
        jQuery('option[value!=""]', element).remove();

        jQuery(dtsColumns).each(function(index, item) {
            jQuery(element).append('<option value="'+item.name+'">'+item.name+'</option>');
        });
    }

    function fillFunctionSelect(element)
    {
        jQuery(element).empty();

        jQuery(dtsFunctions).each(function(index, item) {
            jQuery(element).append('<option value="'+item+'">'+item+'</option>');
        });
    }

    jQuery('.dts-chart-type').click(function() {
        jQuery('.dts-chart-type').removeClass('selected');
        jQuery(this).addClass('selected');
        jQuery('input[name=group]').removeAttr('checked', 'checked');
        jQuery('#dts-group-by-columns').hide();

        dtsChartType = jQuery(this).data('chart-type');
        jQuery('#dts-chart-name').val(dtsChartType);

        if (!dtsChartOptions[dtsChartType].summary) {
            jQuery('input[name=group][value=all]').attr('disabled', 'disabled');
        } else {
            jQuery('input[name=group][value=all]').removeAttr('disabled');
        }

        jQuery('#dts-chart-group').slideDown();

        jQuery('#dts-chart-columns, #dts-chart-styles, #dts-chart-preview').slideUp();

        jQuery('.chart-style-options > div').hide();
        jQuery('#styles-'+dtsChartType).show();

        return false;
    });

    jQuery('input[name=group]').change(function() {
        dtsGroupType = jQuery(this).val();

        if (jQuery(this).val() == 'fields') {
            jQuery('#dts-group-by-columns').show();
        } else {
            jQuery('#dts-group-by-columns').hide();
        }

        showColumnsForm();

        jQuery('#dts-chart-columns').slideDown();
        jQuery('#dts-chart-styles').slideDown();
    });

    function showColumnsForm()
    {
        var formType = 'regular';

        if (dtsGroupType == 'all') {
            formType = 'summary';
        }

        var formId = dtsChartOptions[dtsChartType][formType];

        jQuery('#dts-chart-columns .inside .dts-form-container').empty().append(jQuery('#'+formId).html());
        jQuery('#dts-chart-columns .inside .dts-form-container *').removeAttr('id');

        fillColumnSelect(jQuery('#dts-chart-columns .inside .dts-column-select'));
        fillFunctionSelect(jQuery('#dts-chart-columns .inside .dts-function-select'));
        replaceColumnNames();

        if (!dtsGroupType) {
            jQuery('#dts-chart-columns .inside .dts-form-container .dts-function-select').hide();
        }
    }

    function replaceColumnNames()
    {
        jQuery('#dts-chart-columns .inside .dts-field-container').each(function(num, container) {
            jQuery('select, input', jQuery(container)).each(function(index, element) {
                var name = jQuery(element).attr('name');
                if (name) {
                    jQuery(element).attr('name', name.replace('columns[]', 'columns['+num+']'));
                }
            });
        });
    }

    // Add new column button
    jQuery(document).on('click', '.dts-column-add', function() {
        var templateId = jQuery(this).data('column-template');
        var newField = jQuery('#'+templateId).clone().removeAttr('id');
        jQuery(this).before(newField);

        fillColumnSelect(jQuery('.dts-column-select', newField));
        fillFunctionSelect(jQuery('.dts-function-select', newField));
        replaceColumnNames();

        if (!dtsGroupType) {
            jQuery('#dts-chart-columns .inside .dts-form-container .dts-function-select').hide();
        }

        return false;
    });

    // Delete column button
    jQuery(document).on('click', '.dts-column-delete', function() {
        jQuery(this).parent().remove();
    });

    // Form submit handler
    jQuery('#dts-add-chart-form').submit(function() {
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

    // If it is an edit page
    if (jQuery('#dts-add-chart-form').hasClass('edit')) {
        loadColumns(function() {
            var columns = jQuery.parseJSON(jQuery('#dts-columns').val());
            var selectedGroup = jQuery('input[name=group]:checked').val();

            jQuery('.dts-chart-type.selected').click();
            jQuery('input[name=group][value="' + selectedGroup + '"]').attr('checked', 'checked').change();

            if (jQuery('#dts-sortby').data('val')) {
                jQuery('#dts-sortby option[value="'+jQuery('#dts-sortby').data('val')+'"]').attr('selected', 'selected');
                jQuery('#dts-sortby').removeData('val');
            }

            var groupby = jQuery.parseJSON(jQuery('#dts-groupby').val());
            jQuery.each(groupby, function(index, item) {
                jQuery('#dts-group-by-columns input[value="'+item+'"]').attr('checked', 'checked');
            });


            if (dtsChartType == '2d') {
                var container = jQuery('#dts-chart-columns .dts-field-container').first();
                jQuery('.c-column', container).val(columns[0].column);
                jQuery('.c-function', container).val(columns[0].function);

                container = jQuery('#dts-chart-columns .dts-field-container').last();
                jQuery('.c-column', container).val(columns[1].column);
                jQuery('.c-function', container).val(columns[1].function);
            } else {
                var intIndex = 0;
                jQuery.each(columns, function (index, item) {
                    if (index == "main") {
                        jQuery('#dts-chart-columns .c-main').val(item);
                    } else {
                        // Check if such column exists and create new one if not
                        if (intIndex > 0) {
                            if (jQuery('select[name="columns\\[' + intIndex + '\\]\\[column\\]"]').length) {
                            } else {
                                jQuery('#dts-chart-columns .dts-column-add').click();
                            }
                        }

                        // Fill in fields
                        var container = jQuery('#dts-chart-columns .dts-field-container').last();

                        // column, function, label
                        jQuery('.c-column', container).val(item.column);
                        jQuery('.c-function', container).val(item.function);
                        jQuery('.c-label', container).val(item.label);

                        intIndex++;
                    }
                });
            }
        });
    } else {
        loadColumns();
    }
});