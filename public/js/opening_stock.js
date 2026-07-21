$(document).ready(function() {
    $(document).on('change input', '.purchase_quantity', function() {
        update_table_total($(this).closest('table'));
    });
    $(document).on('change input', '.unit_price', function() {
        var row = $(this).closest('tr');
        update_os_sell_price_from_margin(row);
        update_table_total($(this).closest('table'));
    });

    $(document).on('change input', '.profit_percent', function() {
        update_os_sell_price_from_margin($(this).closest('tr'));
    });

    $(document).on('change input', '.default_sell_price', function() {
        update_os_margin_from_sell_price($(this).closest('tr'));
    });

    $('.os_exp_date').datepicker({
        autoclose: true,
        format: datepicker_date_format,
    });

    $(document).on('click', '.add_stock_row', function() {
        var $btn = $(this);
        var $firstRow = $btn.closest('tr');
        var variationId = $firstRow.data('variation-id');
        var locationId = $firstRow.data('location-id');
        var key = parseInt($btn.attr('data-sub-key') || $btn.data('sub-key'), 10);
        if (isNaN(key)) {
            key = 0;
        }

        var templateEl = $btn.siblings('template.os_stock_row_template').get(0);
        var tr = '';
        if (templateEl) {
            tr = templateEl.innerHTML;
        } else {
            tr = $btn.attr('data-row-html') || '';
        }
        if (!tr) {
            return;
        }

        tr = tr.replace(/__subkey__/g, key);

        var nextBatch = $btn.attr('data-next-batch') || '';
        if (nextBatch) {
            tr = tr.replace(/__batch_number__/g, nextBatch);
        }

        $btn.attr('data-sub-key', key + 1);

        // Append after the last row of this variation+location (keeps Batch 1,2,3… order)
        var $groupRows = os_variation_rows($firstRow);
        var $lastRow = $groupRows.last();
        var $newRow = $(tr).insertAfter($lastRow);

        $newRow.find('.os_exp_date').datepicker({
            autoclose: true,
            format: datepicker_date_format,
        });

        $newRow.find('.os_date').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });

        if ($newRow.find('.batch_number_input').length) {
            var batchLabel = nextBatch;
            if (!batchLabel) {
                batchLabel = os_next_batch_from_siblings($firstRow);
            }
            $newRow.find('.batch_number_input').val(batchLabel);
            $btn.attr('data-next-batch', os_increment_batch_label(batchLabel));
        }

        if ($newRow.find('.profit_percent').length) {
            update_os_sell_price_from_margin($newRow);
        }

        os_refresh_row_actions($firstRow);
        update_table_total($btn.closest('table'));
    });

    $(document).on('click', '.remove_stock_row', function() {
        var $row = $(this).closest('tr');
        var $table = $row.closest('table');
        var locationId = $row.data('location-id');
        var variationId = $row.data('variation-id');
        var $groupRows = os_variation_rows($row);

        if ($groupRows.length <= 1) {
            return;
        }

        var $firstRow = $groupRows.first();
        var removingFirst = $row.get(0) === $firstRow.get(0);
        var $addBtn = $firstRow.find('.add_stock_row').detach();
        var $template = $firstRow.find('template.os_stock_row_template').detach();
        var nextBatch = $addBtn.attr('data-next-batch');
        var subKey = $addBtn.attr('data-sub-key');

        $row.remove();

        $groupRows = $table.find('tr.os_stock_row').filter(function() {
            return String($(this).data('location-id')) === String(locationId)
                && String($(this).data('variation-id')) === String(variationId);
        });

        if (!$groupRows.length) {
            return;
        }

        var $newFirst = $groupRows.first();
        var $actions = $newFirst.find('td.os_row_actions');

        if (removingFirst || !$newFirst.find('.add_stock_row').length) {
            if ($template.length) {
                $actions.prepend($template);
            }
            if ($addBtn.length) {
                $actions.prepend($addBtn);
                if (nextBatch) {
                    $addBtn.attr('data-next-batch', nextBatch);
                }
                if (subKey) {
                    $addBtn.attr('data-sub-key', subKey);
                }
            }
        }

        os_refresh_row_actions($newFirst);
        update_table_total($table);
    });

    $(document).on('click', '.add-opening-stock', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function(result) {
                $('#opening_stock_modal')
                    .html(result)
                    .modal('show');
            },
        });
    });
});

//Re-initialize data picker on modal opening
 $('#opening_stock_modal').on('shown.bs.modal', function(e) {
    $('#opening_stock_modal .os_exp_date').datepicker({
        autoclose: true,
        format: datepicker_date_format,
    });
    $('#opening_stock_modal .os_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
        }
    });
 });

$(document).on('click', 'button#add_opening_stock_btn', function(e) {
    e.preventDefault();
    var btn = $(this);
    var $form = $('form#add_opening_stock_form');
    var $modal = $('#opening_stock_modal');

    $.ajax({
        method: 'POST',
        url: $form.attr('action'),
        dataType: 'json',
        data: $form.serialize(),
        beforeSend: function(xhr) {
            __disable_submit_button(btn);
        },
        success: function(result) {
            if (result.success == 1 || result.success === true) {
                $modal.modal('hide');
                $modal.empty();
                toastr.success(result.msg);

                if (typeof product_table !== 'undefined') {
                    product_table.ajax.reload();
                }
            } else {
                toastr.error(result.msg || (LANG && LANG.something_went_wrong ? LANG.something_went_wrong : 'Something went wrong.'));
                btn.prop('disabled', false).removeAttr('disable');
            }
        },
        error: function() {
            toastr.error(LANG && LANG.something_went_wrong ? LANG.something_went_wrong : 'Something went wrong.');
            btn.prop('disabled', false).removeAttr('disable');
        },
    });
    return false;
});

function update_table_total(table) {
    var total_subtotal = 0;
    table.find('tbody tr.os_stock_row').each(function() {
        var qty = __read_number($(this).find('.purchase_quantity'));
        var unit_price = __read_number($(this).find('.unit_price'));
        var row_subtotal = qty * unit_price;
        $(this)
            .find('.row_subtotal_before_tax')
            .text(__number_f(row_subtotal));
        total_subtotal += row_subtotal;
    });
    table.find('tfoot tr #total_subtotal').text(__currency_trans_from_en(total_subtotal, true));
    table.find('tfoot tr #total_subtotal_hidden').val(total_subtotal);
}

function os_variation_rows($row) {
    var locationId = $row.data('location-id');
    var variationId = $row.data('variation-id');
    return $row.closest('tbody').find('tr.os_stock_row').filter(function() {
        return String($(this).data('location-id')) === String(locationId)
            && String($(this).data('variation-id')) === String(variationId);
    });
}

function os_refresh_row_actions($anyRowInGroup) {
    if (!$anyRowInGroup || !$anyRowInGroup.length) {
        return;
    }
    var $rows = os_variation_rows($anyRowInGroup);
    var canDelete = $rows.length > 1;
    $rows.each(function() {
        var $btn = $(this).find('.remove_stock_row');
        if (canDelete) {
            $btn.show();
        } else {
            $btn.hide();
        }
    });
}

function os_table_tax_meta($row) {
    var $table = $row.closest('table');
    return {
        tax_percent: parseFloat($table.attr('data-tax-percent')) || parseFloat($table.data('tax-percent')) || 0,
        tax_calc_type: $table.attr('data-tax-calc-type') || $table.data('tax-calc-type') || 'percentage',
        selling_price_tax_type: $table.attr('data-selling-price-tax-type') || $table.data('selling-price-tax-type') || 'exclusive',
    };
}

function os_purchase_price_inc_tax(purchase_exc, meta) {
    if (meta.tax_calc_type === 'fixed') {
        return purchase_exc + meta.tax_percent;
    }
    return purchase_exc + (purchase_exc * meta.tax_percent) / 100;
}

function os_sell_show_from_inc(sell_inc, meta) {
    if (meta.selling_price_tax_type === 'inclusive') {
        return sell_inc;
    }
    if (meta.tax_calc_type === 'fixed') {
        return Math.max(0, sell_inc - meta.tax_percent);
    }
    if (meta.tax_percent == 0) {
        return sell_inc;
    }
    return (sell_inc * 100) / (100 + meta.tax_percent);
}

function os_sell_inc_from_show(sell_show, meta) {
    if (meta.selling_price_tax_type === 'inclusive') {
        return sell_show;
    }
    if (meta.tax_calc_type === 'fixed') {
        return sell_show + meta.tax_percent;
    }
    return sell_show + (sell_show * meta.tax_percent) / 100;
}

function update_os_sell_price_from_margin(row) {
    if (!row.find('input.profit_percent').length || !row.find('input.default_sell_price').length) {
        return;
    }
    var meta = os_table_tax_meta(row);
    var purchase_exc = __read_number(row.find('input.unit_price'));
    var profit_percent = __read_number(row.find('input.profit_percent'));
    var purchase_inc = os_purchase_price_inc_tax(purchase_exc, meta);
    var sell_inc = purchase_inc + (purchase_inc * profit_percent) / 100;
    var sell_show = os_sell_show_from_inc(sell_inc, meta);
    __write_number(row.find('input.default_sell_price'), sell_show);
}

function update_os_margin_from_sell_price(row) {
    if (!row.find('input.profit_percent').length || !row.find('input.default_sell_price').length) {
        return;
    }
    var meta = os_table_tax_meta(row);
    var purchase_exc = __read_number(row.find('input.unit_price'));
    var sell_show = __read_number(row.find('input.default_sell_price'));
    var purchase_inc = os_purchase_price_inc_tax(purchase_exc, meta);
    var sell_inc = os_sell_inc_from_show(sell_show, meta);
    var profit_percent = 0;
    if (purchase_inc != 0) {
        profit_percent = ((sell_inc - purchase_inc) / purchase_inc) * 100;
    }
    __write_number(row.find('input.profit_percent'), profit_percent);
}

function os_increment_batch_label(label) {
    var match = String(label || '').match(/^Batch\s+(\d+)$/i);
    if (match) {
        return 'Batch ' + (parseInt(match[1], 10) + 1);
    }
    return 'Batch 1';
}

function os_next_batch_from_siblings($row) {
    var max = 0;
    os_variation_rows($row).find('.batch_number_input').each(function() {
        var match = String($(this).val() || '').match(/^Batch\s+(\d+)$/i);
        if (match) {
            max = Math.max(max, parseInt(match[1], 10));
        }
    });
    return 'Batch ' + (max + 1);
}
