//This file contains all functions used products tab

$(document).ready(function () {
    function init_tax_select_with_direct_input($root) {
        var $targets = $root && $root.length ? $root.find('select#tax') : $('select#tax');
        if (!$targets.length) {
            return;
        }

        $targets.each(function () {
            var $tax = $(this);
            if ($tax.hasClass('select2-hidden-accessible')) {
                $tax.select2('destroy');
            }

            var select2_config = {
                tags: true,
                minimumResultsForSearch: 0,
                createTag: function (params) {
                    var term = $.trim(params.term || '');
                    if (term === '') {
                        return null;
                    }

                    var normalized = term.replace(/,/g, '');
                    var value = parseFloat(normalized);
                    if (isNaN(value) || value < 0) {
                        return null;
                    }

                    var rounded = parseFloat(value.toFixed(2));
                    return {
                        id: '__direct_tax__' + rounded,
                        text: 'Direct Fixed Tax (' + __currency_trans_from_en(rounded) + ')',
                        direct_custom_tax: true,
                        tax_rate: rounded,
                    };
                },
                insertTag: function (data, tag) {
                    data.push(tag);
                },
            };

            var $modal = $tax.closest('.modal');
            if ($modal.length) {
                select2_config.dropdownParent = $modal;
            }

            $tax.select2(select2_config);
        });
    }

    function get_selected_direct_tax_rate() {
        var selected_tax = $('select#tax').find(':selected');
        if (selected_tax.attr('data-direct-custom') !== '1') {
            return null;
        }

        var tax_rate = parseFloat(selected_tax.data('rate'));
        if (isNaN(tax_rate)) {
            tax_rate = parseFloat(selected_tax.attr('data-rate'));
        }

        return isNaN(tax_rate) ? null : tax_rate;
    }

    function parse_direct_tax_input(raw_value) {
        var text = $.trim((raw_value || '').toString());
        if (text === '') {
            return null;
        }

        var normalized = text.replace(/,/g, '');
        var numeric_pattern = /^\d*\.?\d+$/;
        if (!numeric_pattern.test(normalized)) {
            return null;
        }

        var value = parseFloat(normalized);
        if (isNaN(value) || value < 0) {
            return null;
        }

        return parseFloat(value.toFixed(2));
    }

    function normalize_selected_typed_tax(selected_data) {
        var $tax = $('select#tax');
        var $selected_option = $tax.find(':selected');

        var has_data_rate = $selected_option.attr('data-rate') !== undefined;
        if (has_data_rate) {
            return;
        }

        if (selected_data && selected_data.direct_custom_tax && !isNaN(selected_data.tax_rate)) {
            $selected_option
                .attr('data-rate', selected_data.tax_rate)
                .attr('data-type', 'fixed')
                .attr('data-direct-custom', '1')
                .data('rate', selected_data.tax_rate)
                .data('type', 'fixed');
            return;
        }

        var input_value = selected_data && selected_data.text ? selected_data.text : $selected_option.text();
        var parsed_value = parse_direct_tax_input(input_value);

        if (parsed_value === null) {
            $selected_option.remove();
            $tax.val(null).trigger('change');
            toastr.error('Please enter a valid numeric tax value.');
            return;
        }

        var direct_value = '__direct_tax__' + parsed_value;
        $selected_option
            .val(direct_value)
            .text('Direct Fixed Tax (' + __currency_trans_from_en(parsed_value) + ')')
            .attr('data-rate', parsed_value)
            .attr('data-type', 'fixed')
            .attr('data-direct-custom', '1')
            .data('rate', parsed_value)
            .data('type', 'fixed');

        $tax.val(direct_value).trigger('change');
    }

    function sync_direct_tax_to_custom_fields() {
        var direct_tax_rate = get_selected_direct_tax_rate();
        if (direct_tax_rate === null) {
            return;
        }

        $('#is_custom_tax_calc').val(1);
        $('#custom_tax_kilogram').val('');
        $('#custom_tax_quantity').val(1);
        $('#custom_tax_amount').val(direct_tax_rate.toFixed(2));
        __write_number($('#custom_tax_per_piece_display'), direct_tax_rate, false, 2);
        $('#custom_tax_per_piece').val(direct_tax_rate.toFixed(2));
    }

    function sync_tax_payload_before_submit() {
        if (get_selected_direct_tax_rate() !== null) {
            sync_direct_tax_to_custom_fields();
            return;
        }

        if ($('#is_custom_tax_calc').val() === '1') {
            update_custom_tax_per_piece();
        }
    }

    function get_product_tax_details() {
        var selected_tax = $('select#tax').find(':selected');
        var tax_rate = parseFloat(selected_tax.data('rate'));
        if (isNaN(tax_rate)) {
            tax_rate = parseFloat(selected_tax.attr('data-rate'));
        }

        var tax_type = selected_tax.data('type');
        if (!tax_type) {
            tax_type = selected_tax.attr('data-type');
        }

        return {
            amount: isNaN(tax_rate) ? 0 : tax_rate,
            type: tax_type || 'percentage',
        };
    }

    function add_product_tax(amount, tax_details) {
        return amount + __calculate_amount(tax_details.type, tax_details.amount, amount);
    }

    function remove_product_tax(amount_inc_tax, tax_details) {
        if (tax_details.type == 'fixed') {
            var amount = amount_inc_tax - tax_details.amount;
            return amount < 0 ? 0 : amount;
        }

        return __get_principle(amount_inc_tax, tax_details.amount);
    }

    function set_custom_tax_mode(is_enabled) {
        $('#is_custom_tax_calc').val(is_enabled ? 1 : 0);
        $('#custom_tax_calc_fields').toggleClass('hide', !is_enabled);
        $('#custom_tax_quantity, #custom_tax_amount').prop('required', is_enabled);
        $('#toggle_custom_tax_calc i')
            .toggleClass('fa-chevron-down', !is_enabled)
            .toggleClass('fa-chevron-up', is_enabled);

        if (!is_enabled) {
            $('#custom_tax_kilogram, #custom_tax_quantity, #custom_tax_amount, #custom_tax_per_piece_display, #custom_tax_per_piece').val('');
            $('select#tax option[data-custom-tax="1"]').remove();
            $('select#tax').trigger('change');
        }
    }

    function sync_custom_tax_to_dropdown(tax_per_piece) {
        if (!tax_per_piece || tax_per_piece <= 0) {
            return;
        }

        var tax_name = 'Auto Fixed Tax (' + __currency_trans_from_en(tax_per_piece) + ')';
        var existing_option = $('select#tax option[data-custom-tax="1"]');

        if (existing_option.length) {
            existing_option
                .attr('data-rate', tax_per_piece)
                .attr('data-type', 'fixed')
                .data('rate', tax_per_piece)
                .data('type', 'fixed')
                .text(tax_name)
                .val('__custom_tax_rate__');
        } else {
            var custom_option = new Option(tax_name, '__custom_tax_rate__', true, true);
            $(custom_option)
                .attr('data-rate', tax_per_piece)
                .attr('data-type', 'fixed')
                .attr('data-custom-tax', '1')
                .data('rate', tax_per_piece)
                .data('type', 'fixed');
            $('select#tax').append(custom_option);
        }

        $('select#tax').val('__custom_tax_rate__').trigger('change');
    }

    function update_custom_tax_per_piece() {
        var quantity = __read_number($('#custom_tax_quantity'));
        var amount = __read_number($('#custom_tax_amount'));

        quantity = quantity == undefined ? 0 : quantity;
        amount = amount == undefined ? 0 : amount;

        if (quantity > 0 && amount >= 0) {
            var tax_per_piece = amount / quantity;
            __write_number($('#custom_tax_per_piece_display'), tax_per_piece, false, 4);
            $('#custom_tax_per_piece').val(tax_per_piece.toFixed(4));
            sync_custom_tax_to_dropdown(tax_per_piece);
        } else {
            $('#custom_tax_per_piece_display, #custom_tax_per_piece').val('');
            $('select#tax option[data-custom-tax="1"]').remove();
            $('select#tax').trigger('change');
        }
    }

    function recalculate_single_selling_from_margin() {
        var tax_details = get_product_tax_details();
        var tax_type = $('#tax_type').val();
        var purchase_exc_tax = __read_number($('input#single_dpp'));
        var purchase_inc_tax = __read_number($('input#single_dpp_inc_tax'));
        var profit_percent = __read_number($('input#profit_percent'));

        purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;
        purchase_inc_tax = purchase_inc_tax == undefined ? 0 : purchase_inc_tax;
        profit_percent = profit_percent == undefined ? 0 : profit_percent;

        var selling_price = 0;
        var selling_price_inc_tax = 0;

        if (tax_type == 'inclusive') {
            selling_price_inc_tax = __add_percent(purchase_inc_tax, profit_percent);
            selling_price = remove_product_tax(selling_price_inc_tax, tax_details);
        } else {
            selling_price = __add_percent(purchase_exc_tax, profit_percent);
            selling_price_inc_tax = add_product_tax(selling_price, tax_details);
        }

        __write_number($('input#single_dsp'), selling_price);
        __write_number($('input#single_dsp_inc_tax'), selling_price_inc_tax);

        var $minSellPrice = $('input#single_min_sell_price_inc_tax');
        if ($minSellPrice.length && ($minSellPrice.val() === '' || $minSellPrice.val() === null)) {
            __write_number($minSellPrice, selling_price_inc_tax);
        }
    }

    $(document).on('ifChecked', 'input#enable_stock', function () {
        $('div#alert_quantity_div').show();
        $('div#quick_product_opening_stock_div').show();

        //Enable expiry selection
        if ($('#expiry_period_type').length) {
            $('#expiry_period_type').removeAttr('disabled');
        }

        if ($('#opening_stock_button').length) {
            $('#opening_stock_button').removeAttr('disabled');
        }
    });
    $(document).on('ifUnchecked', 'input#enable_stock', function () {
        $('div#alert_quantity_div').hide();
        $('div#quick_product_opening_stock_div').hide();
        $('input#alert_quantity').val(0);

        //Disable expiry selection
        if ($('#expiry_period_type').length) {
            $('#expiry_period_type')
                .val('')
                .change();
            $('#expiry_period_type').attr('disabled', true);
        }
        if ($('#opening_stock_button').length) {
            $('#opening_stock_button').attr('disabled', true);
        }
    });

    $(document).on('click', '#toggle_custom_tax_calc', function () {
        set_custom_tax_mode($('#is_custom_tax_calc').val() !== '1');
    });

    $(document).on('change keyup', '#custom_tax_quantity, #custom_tax_amount', function () {
        update_custom_tax_per_piece();
    });

    $(document).on('select2:open', 'select#tax', function () {
        if ($('#custom_tax_calc_fields').length) {
            set_custom_tax_mode(true);
        }
    });

    $(document).on('select2:select', 'select#tax', function (e) {
        var selected_data = e.params && e.params.data ? e.params.data : null;

        if (selected_data && selected_data.direct_custom_tax && !isNaN(selected_data.tax_rate)) {
            var $option = $('select#tax')
                .find('option')
                .filter(function () {
                    return $(this).val() === selected_data.id;
                });

            $option
                .attr('data-rate', selected_data.tax_rate)
                .attr('data-type', 'fixed')
                .attr('data-direct-custom', '1')
                .data('rate', selected_data.tax_rate)
                .data('type', 'fixed');

            sync_direct_tax_to_custom_fields();
            return;
        }

        normalize_selected_typed_tax(selected_data);

        if (!selected_data || !selected_data.direct_custom_tax) {
            if (get_selected_direct_tax_rate() !== null) {
                sync_direct_tax_to_custom_fields();
            }
        }
    });

    //Start For product type single

    //If purchase price exc tax is changed
    $(document).on('change', 'input#single_dpp', function (e) {
        var purchase_exc_tax = __read_number($('input#single_dpp'));
        purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

        var tax_details = get_product_tax_details();

        var purchase_inc_tax = add_product_tax(purchase_exc_tax, tax_details);
        __write_number($('input#single_dpp_inc_tax'), purchase_inc_tax);
        recalculate_single_selling_from_margin();
    });

    //If tax rate is changed
    $(document).on('change', 'select#tax', function () {
        if ($('select#type').val() == 'single') {
            var purchase_exc_tax = __read_number($('input#single_dpp'));
            purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

            var tax_details = get_product_tax_details();

            var purchase_inc_tax = add_product_tax(purchase_exc_tax, tax_details);
            __write_number($('input#single_dpp_inc_tax'), purchase_inc_tax);
            recalculate_single_selling_from_margin();
        }
    });

    //If purchase price inc tax is changed
    $(document).on('change', 'input#single_dpp_inc_tax', function (e) {
        var purchase_inc_tax = __read_number($('input#single_dpp_inc_tax'));
        purchase_inc_tax = purchase_inc_tax == undefined ? 0 : purchase_inc_tax;

        var tax_details = get_product_tax_details();

        var purchase_exc_tax = remove_product_tax(purchase_inc_tax, tax_details);
        __write_number($('input#single_dpp'), purchase_exc_tax);
        recalculate_single_selling_from_margin();
    });

    $(document).on('change', 'input#profit_percent', function (e) {
        recalculate_single_selling_from_margin();
    });

    $(document).on('change', 'input#single_dsp', function (e) {
        var tax_details = get_product_tax_details();

        var selling_price = __read_number($('input#single_dsp'));
        var selling_price_inc_tax = add_product_tax(selling_price, tax_details);
        __write_number($('input#single_dsp_inc_tax'), selling_price_inc_tax);

        var purchase_exc_tax = __read_number($('input#single_dpp'));
        var purchase_inc_tax = __read_number($('input#single_dpp_inc_tax'));
        var profit_percent = __read_number($('input#profit_percent'));
        var tax_type = $('#tax_type').val();

        //if purchase price not set
        if ((tax_type == 'inclusive' && purchase_inc_tax == 0) || (tax_type != 'inclusive' && purchase_exc_tax == 0)) {
            profit_percent = 0;
        } else if (tax_type == 'inclusive') {
            profit_percent = __get_rate(purchase_inc_tax, selling_price_inc_tax);
        } else {
            profit_percent = __get_rate(purchase_exc_tax, selling_price);
        }

        __write_number($('input#profit_percent'), profit_percent);

        var $minSellPrice = $('input#single_min_sell_price_inc_tax');
        if ($minSellPrice.length && ($minSellPrice.val() === '' || $minSellPrice.val() === null)) {
            __write_number($minSellPrice, selling_price_inc_tax);
        }
    });

    $(document).on('change', 'input#single_dsp_inc_tax', function (e) {
        var tax_details = get_product_tax_details();
        var selling_price_inc_tax = __read_number($('input#single_dsp_inc_tax'));

        var selling_price = remove_product_tax(selling_price_inc_tax, tax_details);
        __write_number($('input#single_dsp'), selling_price);
        var purchase_exc_tax = __read_number($('input#single_dpp'));
        var purchase_inc_tax = __read_number($('input#single_dpp_inc_tax'));
        var profit_percent = __read_number($('input#profit_percent'));
        var tax_type = $('#tax_type').val();

        //if purchase price not set
        if ((tax_type == 'inclusive' && purchase_inc_tax == 0) || (tax_type != 'inclusive' && purchase_exc_tax == 0)) {
            profit_percent = 0;
        } else if (tax_type == 'inclusive') {
            profit_percent = __get_rate(purchase_inc_tax, selling_price_inc_tax);
        } else {
            profit_percent = __get_rate(purchase_exc_tax, selling_price);
        }

        __write_number($('input#profit_percent'), profit_percent);

        var $minSellPrice = $('input#single_min_sell_price_inc_tax');
        if ($minSellPrice.length && ($minSellPrice.val() === '' || $minSellPrice.val() === null)) {
            __write_number($minSellPrice, selling_price_inc_tax);
        }
    });

    if ($('#product_add_form').length) {
        $('form#product_add_form').validate({
            rules: {
                sku: {
                    remote: {
                        url: '/products/check_product_sku',
                        type: 'post',
                        data: {
                            sku: function () {
                                return $('#sku').val();
                            },
                            product_id: function () {
                                if ($('#product_id').length > 0) {
                                    return $('#product_id').val();
                                } else {
                                    return '';
                                }
                            },
                        },
                    },
                },
                name: {
                    remote: {
                        url: '/products/check_product_name',
                        type: 'post',
                        data: {
                            name: function () {
                                return $('#name').val();
                            },
                            product_id: function () {
                                if ($('#product_id').length > 0) {
                                    return $('#product_id').val();
                                } else {
                                    return '';
                                }
                            },
                        },
                    },
                },
                expiry_period: {
                    required: {
                        depends: function (element) {
                            return (
                                $('#expiry_period_type')
                                    .val()
                                    .trim() != ''
                            );
                        },
                    },
                },
            },
            messages: {
                sku: {
                    remote: LANG.sku_already_exists,
                },
                name: {
                    remote: LANG.name_already_exists,
                },
            },
        });
    }

        init_tax_select_with_direct_input();
        setTimeout(function () {
            init_tax_select_with_direct_input();
        }, 150);

        $(window).on('load', function () {
            init_tax_select_with_direct_input();
        });

    $(document).on('click', '.submit_product_form', function (e) {
        e.preventDefault();

        var is_valid_product_form = true;

        var variation_skus = [];

        var submit_type = $(this).attr('value');

        $('#product_form_part').find('.input_sub_sku').each(function () {
            var element = $(this);
            var row_variation_id = '';
            if ($(this).closest('tr').find('.row_variation_id')) {
                row_variation_id = $(this).closest('tr').find('.row_variation_id').val();
            }

            variation_skus.push({ sku: element.val(), variation_id: row_variation_id });

        });

        if (variation_skus.length > 0) {
            $.ajax({
                method: 'post',
                url: '/products/validate_variation_skus',
                data: { skus: variation_skus },
                success: function (result) {
                    if (result.success == true) {
                        $('#submit_type').val(submit_type);
                        if ($('form#product_add_form').valid()) {
                            $('form#product_add_form').submit();
                        }
                    } else {
                        toastr.error(__translate('skus_already_exists', { sku: result.sku }));
                        return false;
                    }
                },
            });
        } else {
            $('#submit_type').val(submit_type);
            if ($('form#product_add_form').valid()) {
                $('form#product_add_form').submit();
            }
        }

    });

    $(document).on('submit', 'form#quick_add_product_form', function () {
        sync_tax_payload_before_submit();
    });

    $(document).on('shown.bs.modal', '.quick_add_product_modal', function () {
        init_tax_select_with_direct_input($(this));
    });

    //End for product type single

    //Start for product type Variable
    //If purchase price exc tax is changed
    $(document).on('change', 'input.variable_dpp', function (e) {
        var tr_obj = $(this).closest('tr');

        var purchase_exc_tax = __read_number($(this));
        purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

        var tax_details = get_product_tax_details();

        var purchase_inc_tax = add_product_tax(purchase_exc_tax, tax_details);
        __write_number(tr_obj.find('input.variable_dpp_inc_tax'), purchase_inc_tax);

        var profit_percent = __read_number(tr_obj.find('input.variable_profit_percent'));
        var selling_price = __add_percent(purchase_exc_tax, profit_percent);
        __write_number(tr_obj.find('input.variable_dsp'), selling_price);

        var selling_price_inc_tax = add_product_tax(selling_price, tax_details);
        __write_number(tr_obj.find('input.variable_dsp_inc_tax'), selling_price_inc_tax);
    });

    //If purchase price inc tax is changed
    $(document).on('change', 'input.variable_dpp_inc_tax', function (e) {
        var tr_obj = $(this).closest('tr');

        var purchase_inc_tax = __read_number($(this));
        purchase_inc_tax = purchase_inc_tax == undefined ? 0 : purchase_inc_tax;

        var tax_details = get_product_tax_details();

        var purchase_exc_tax = remove_product_tax(purchase_inc_tax, tax_details);
        __write_number(tr_obj.find('input.variable_dpp'), purchase_exc_tax);

        var profit_percent = __read_number(tr_obj.find('input.variable_profit_percent'));
        var selling_price = __add_percent(purchase_exc_tax, profit_percent);
        __write_number(tr_obj.find('input.variable_dsp'), selling_price);

        var selling_price_inc_tax = add_product_tax(selling_price, tax_details);
        __write_number(tr_obj.find('input.variable_dsp_inc_tax'), selling_price_inc_tax);
    });

    $(document).on('change', 'input.variable_profit_percent', function (e) {
        var tax_details = get_product_tax_details();

        var tr_obj = $(this).closest('tr');
        var profit_percent = __read_number($(this));

        var purchase_exc_tax = __read_number(tr_obj.find('input.variable_dpp'));
        purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

        var selling_price = __add_percent(purchase_exc_tax, profit_percent);
        __write_number(tr_obj.find('input.variable_dsp'), selling_price);

        var selling_price_inc_tax = add_product_tax(selling_price, tax_details);
        __write_number(tr_obj.find('input.variable_dsp_inc_tax'), selling_price_inc_tax);
    });

    $(document).on('change', 'input.variable_dsp', function (e) {
        var tax_details = get_product_tax_details();

        var tr_obj = $(this).closest('tr');
        var selling_price = __read_number($(this));
        var purchase_exc_tax = __read_number(tr_obj.find('input.variable_dpp'));

        var profit_percent = __read_number(tr_obj.find('input.variable_profit_percent'));

        //if purchase price not set
        if (purchase_exc_tax == 0) {
            profit_percent = 0;
        } else {
            profit_percent = __get_rate(purchase_exc_tax, selling_price);
        }

        __write_number(tr_obj.find('input.variable_profit_percent'), profit_percent);

        var selling_price_inc_tax = add_product_tax(selling_price, tax_details);
        __write_number(tr_obj.find('input.variable_dsp_inc_tax'), selling_price_inc_tax);
    });
    $(document).on('change', 'input.variable_dsp_inc_tax', function (e) {
        var tr_obj = $(this).closest('tr');
        var selling_price_inc_tax = __read_number($(this));

        var tax_details = get_product_tax_details();

        var selling_price = remove_product_tax(selling_price_inc_tax, tax_details);
        __write_number(tr_obj.find('input.variable_dsp'), selling_price);

        var purchase_exc_tax = __read_number(tr_obj.find('input.variable_dpp'));
        var profit_percent = __read_number(tr_obj.find('input.variable_profit_percent'));
        //if purchase price not set
        if (purchase_exc_tax == 0) {
            profit_percent = 0;
        } else {
            profit_percent = __get_rate(purchase_exc_tax, selling_price);
        }

        __write_number(tr_obj.find('input.variable_profit_percent'), profit_percent);
    });

    $(document).on('click', '.add_variation_value_row', function () {
        var variation_row_index = $(this)
            .closest('.variation_row')
            .find('.row_index')
            .val();
        var variation_value_row_index = $(this)
            .closest('table')
            .find('tr:last .variation_row_index')
            .val();

        if (
            $(this)
                .closest('.variation_row')
                .find('.row_edit').length >= 1
        ) {
            var row_type = 'edit';
        } else {
            var row_type = 'add';
        }

        var table = $(this).closest('table');

        $.ajax({
            method: 'GET',
            url: '/products/get_variation_value_row',
            data: {
                variation_row_index: variation_row_index,
                value_index: variation_value_row_index,
                row_type: row_type,
            },
            dataType: 'html',
            success: function (result) {
                if (result) {
                    table.append(result);
                    toggle_dsp_input();
                }
            },
        });
    });
    $(document).on('change', '.variation_template_values', function () {
        var tr_obj = $(this).closest('tr');
        var val = $(this).val();
        tr_obj.find('.variation_value_row').each(function () {
            if (val.includes($(this).attr('data-variation_value_id'))) {
                $(this).removeClass('hide');
                $(this).find('.is_variation_value_hidden').val(0);
            } else {
                $(this).addClass('hide');
                $(this).find('.is_variation_value_hidden').val(1);
            }
        })
    });
    $(document).on('change', '.variation_template', function () {
        tr_obj = $(this).closest('tr');

        if ($(this).val() !== '') {
            tr_obj.find('input.variation_name').val(
                $(this)
                    .find('option:selected')
                    .text()
            );

            var template_id = $(this).val();
            var row_index = $(this)
                .closest('tr')
                .find('.row_index')
                .val();
            $.ajax({
                method: 'POST',
                url: '/products/get_variation_template',
                dataType: 'json',
                data: { template_id: template_id, row_index: row_index },
                success: function (result) {
                    if (result) {
                        if (result.values.length > 0) {
                            tr_obj.find('.variation_template_values').select2();
                            tr_obj.find('.variation_template_values').empty();
                            tr_obj.find('.variation_template_values').select2({ data: result.values, closeOnSelect: false });
                            tr_obj.find('.variation_template_values_div').removeClass('hide');
                            tr_obj.find('.variation_template_values').select2('open');
                        } else {
                            tr_obj.find('.variation_template_values_div').addClass('hide');
                        }
                        tr_obj
                            .find('table.variation_value_table')
                            .find('tbody')
                            .html(result.html);

                        toggle_dsp_input();
                    }
                },
            });
        }
    });

    $(document).on('click', '.delete_complete_row', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                $(this)
                    .closest('.variation_row')
                    .remove();
            }
        });
    });

    $(document).on('click', '.remove_variation_value_row', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var count = $(this)
                    .closest('table')
                    .find('.remove_variation_value_row').length;
                if (count === 1) {
                    $(this)
                        .closest('.variation_row')
                        .remove();
                } else {
                    $(this)
                        .closest('tr')
                        .remove();
                }
            }
        });
    });

    //If tax rate is changed
    $(document).on('change', 'select#tax', function () {
        if ($('select#type').val() == 'variable') {
            var tax_details = get_product_tax_details();

            $('table.variation_value_table > tbody').each(function () {
                $(this)
                    .find('tr')
                    .each(function () {
                        var purchase_exc_tax = __read_number($(this).find('input.variable_dpp'));
                        purchase_exc_tax = purchase_exc_tax == undefined ? 0 : purchase_exc_tax;

                        var purchase_inc_tax = add_product_tax(purchase_exc_tax, tax_details);
                        __write_number(
                            $(this).find('input.variable_dpp_inc_tax'),
                            purchase_inc_tax
                        );

                        var selling_price = __read_number($(this).find('input.variable_dsp'));
                        var selling_price_inc_tax = add_product_tax(selling_price, tax_details);
                        __write_number(
                            $(this).find('input.variable_dsp_inc_tax'),
                            selling_price_inc_tax
                        );
                    });
            });
        }
    });
    //End for product type Variable
    $(document).on('change', '#tax_type', function (e) {
        toggle_dsp_input();
        recalculate_single_selling_from_margin();
    });
    toggle_dsp_input();

    // Enforce single editable selling price based on tax type
    function set_price_editability() {
        var tax_type = $('#tax_type').val();
        if (tax_type == 'inclusive') {
            $('#single_dsp').prop('readonly', true);
            $('#single_dsp_inc_tax').prop('readonly', false);
            $('.variable_dsp').prop('readonly', true);
            $('.variable_dsp_inc_tax').prop('readonly', false);
        } else {
            $('#single_dsp').prop('readonly', false);
            $('#single_dsp_inc_tax').prop('readonly', true);
            $('.variable_dsp').prop('readonly', false);
            $('.variable_dsp_inc_tax').prop('readonly', true);
        }
    }
    $(document).on('change', '#tax_type', set_price_editability);
    set_price_editability();

    $(document).on('change', '#expiry_period_type', function (e) {
        if ($(this).val()) {
            $('input#expiry_period').prop('disabled', false);
        } else {
            $('input#expiry_period').val('');
            $('input#expiry_period').prop('disabled', true);
        }
    });

    $(document).on('click', 'a.view-product', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'html',
            success: function (result) {
                $('#view_product_modal')
                    .html(result)
                    .modal('show');
                __currency_convert_recursively($('#view_product_modal'));
            },
        });
    });
    var img_fileinput_setting = {
        showUpload: false,
        showPreview: true,
        browseLabel: LANG.file_browse_label,
        removeLabel: LANG.remove,
        previewSettings: {
            image: { width: 'auto', height: 'auto', 'max-width': '100%', 'max-height': '100%' },
        },
    };
    $('#upload_image').fileinput(img_fileinput_setting);

    if ($('textarea#product_description').length > 0) {
        tinymce.init({
            selector: 'textarea#product_description',
            height: 250
        });
    }

    init_tax_select_with_direct_input();
    setTimeout(function () {
        init_tax_select_with_direct_input();
    }, 150);

    $(window).on('load', function () {
        init_tax_select_with_direct_input();
    });
});

function toggle_dsp_input() {
    var tax_type = $('#tax_type').val();
    if (tax_type == 'inclusive') {
        $('.dsp_label').each(function () {
            $(this).text(LANG.inc_tax);
        });
        $('#single_dsp').addClass('hide');
        $('#single_dsp_inc_tax').removeClass('hide');

        // Keep MSP input visible in all tax modes; it stores inc-tax minimum.
        $('#single_min_sell_price_inc_tax').removeClass('hide');
        $('.min_sell_price_help_text').removeClass('hide');

        // Toggle help text visibility
        $('.dsp_help_text').addClass('hide');
        $('.dsp_inc_tax_help_text').removeClass('hide');

        $('.add-product-price-table')
            .find('.variable_dsp_inc_tax')
            .each(function () {
                $(this).removeClass('hide');
            });
        $('.add-product-price-table')
            .find('.variable_dsp')
            .each(function () {
                $(this).addClass('hide');
            });
    } else if (tax_type == 'exclusive') {
        $('.dsp_label').each(function () {
            $(this).text(LANG.exc_tax);
        });
        $('#single_dsp').removeClass('hide');
        $('#single_dsp_inc_tax').addClass('hide');

        // Keep MSP input visible in all tax modes; it stores inc-tax minimum.
        $('#single_min_sell_price_inc_tax').removeClass('hide');
        $('.min_sell_price_help_text').removeClass('hide');

        // Toggle help text visibility
        $('.dsp_help_text').removeClass('hide');
        $('.dsp_inc_tax_help_text').addClass('hide');

        $('.add-product-price-table')
            .find('.variable_dsp_inc_tax')
            .each(function () {
                $(this).addClass('hide');
            });
        $('.add-product-price-table')
            .find('.variable_dsp')
            .each(function () {
                $(this).removeClass('hide');
            });
    }
}

function get_product_details(rowData) {
    var div = $('<div/>')
        .addClass('loading')
        .text('Loading...');

    $.ajax({
        url: '/products/' + rowData.id,
        dataType: 'html',
        success: function (data) {
            div.html(data).removeClass('loading');
        },
    });

    return div;
}

//Quick add unit
$(document).on('submit', 'form#quick_add_unit_form', function (e) {
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();

    $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function (xhr) {
            __disable_submit_button(form.find('button[type="submit"]'));
        },
        success: function (result) {
            if (result.success == true) {
                var newOption = new Option(result.data.short_name, result.data.id, true, true);
                // Append it to the select
                $('#unit_id')
                    .append(newOption)
                    .trigger('change');
                $('div.view_modal').modal('hide');
                toastr.success(result.msg);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

//Quick add brand
$(document).on('submit', 'form#quick_add_brand_form', function (e) {
    e.preventDefault();
    var form = $(this);
    var data = form.serialize();

    $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function (xhr) {
            __disable_submit_button(form.find('button[type="submit"]'));
        },
        success: function (result) {
            if (result.success == true) {
                var newOption = new Option(result.data.name, result.data.id, true, true);
                // Append it to the select
                $('#brand_id')
                    .append(newOption)
                    .trigger('change');
                $('div.view_modal').modal('hide');
                toastr.success(result.msg);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('click', 'button.apply-all', function () {
    var val = $(this).closest('.input-group').find('input').val();
    var target_class = $(this).data('target-class');
    $(this).closest('tbody').find('tr').each(function () {
        element = $(this).find(target_class);
        element.val(val);
        element.change();
    });
});
