var global_brand_id = null;
var global_p_category_id = null;
var global_is_clear_local_storage = false;

// Enhanced empty state management - Global function
function updateEmptyState() {
    // Count actual product rows (excluding empty state row)
    var productRows = $('#pos_table tbody tr.product_row').length;
    var allRows = $('#pos_table tbody tr').length;
    var emptyRow = $('#empty_cart_row').length;

    /* debug removed */

    if (productRows === 0) {
        /* debug removed */
        $('#empty_cart_row').show().css({
            'display': 'table-row',
            'animation': 'fadeIn 0.5s ease forwards'
        });
    } else {
        /* debug removed */
        $('#empty_cart_row').hide().css({
            'display': 'none'
        });
    }
}

// Add loading animation for new rows - Global function
function addRowWithAnimation(htmlContent) {
    console.log('Adding row with animation:', htmlContent);
    var $newRow = $(htmlContent);
    $newRow.css({
        'opacity': '0',
        'transform': 'translateY(20px)',
        'transition': 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
    });

    var $tbody = $('#pos_table tbody');
    console.log('Table body found:', $tbody.length);
    $tbody.append($newRow);

    setTimeout(function () {
        $newRow.css({
            'opacity': '1',
            'transform': 'translateY(0)'
        });
    }, 50);

    return $newRow;
}

function getPosTaxDetails($selectOrOption) {
    var $option = $selectOrOption.is('option') ? $selectOrOption : $selectOrOption.find(':selected');
    var tax_rate = parseFloat($option.data('rate'));

    return {
        amount: isNaN(tax_rate) ? 0 : tax_rate,
        type: $option.data('type') || 'percentage',
    };
}

function posAddTax(amount, tax_details) {
    return amount + __calculate_amount(tax_details.type, tax_details.amount, amount);
}

function posRemoveTax(amount_inc_tax, tax_details) {
    if (tax_details.type == 'fixed') {
        var value = amount_inc_tax - tax_details.amount;

        return value < 0 ? 0 : value;
    }

    return __get_principle(amount_inc_tax, tax_details.amount);
}

$(document).ready(function () {
    customer_set = false;

    // Add modern styling enhancements
    addModernStyling();

    //Prevent enter key function except texarea
    $('form').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13 && e.target.tagName != 'TEXTAREA') {
            e.preventDefault();
            return false;
        }
    });

    //For edit pos form
    if ($('form#edit_pos_sell_form').length > 0) {
        pos_total_row();
        pos_form_obj = $('form#edit_pos_sell_form');
    } else {
        pos_form_obj = $('form#add_pos_sell_form');
    }
    if ($('form#edit_pos_sell_form').length > 0 || $('form#add_pos_sell_form').length > 0) {
        initialize_printer();
    }

    $('select#select_location_id').change(function () {
        reset_pos_form();

        var default_price_group = $(this).find(':selected').data('default_price_group')
        if (default_price_group) {
            if ($("#price_group option[value='" + default_price_group + "']").length > 0) {
                $("#price_group").val(default_price_group);
                $("#price_group").change();
            }
        }

        //Set default invoice scheme for location
        if ($('#invoice_scheme_id').length) {
            if ($('input[name="is_direct_sale"]').length > 0) {
                //default scheme for sale screen
                var invoice_scheme_id = $(this).find(':selected').data('default_sale_invoice_scheme_id');
            } else {
                var invoice_scheme_id = $(this).find(':selected').data('default_invoice_scheme_id');
            }

            $("#invoice_scheme_id").val(invoice_scheme_id).change();
        }

        //Set default invoice layout for location
        if ($('#invoice_layout_id').length) {
            let invoice_layout_id = $(this).find(':selected').data('default_invoice_layout_id');
            $("#invoice_layout_id").val(invoice_layout_id).change();
        }

        //Set default price group
        if ($('#default_price_group').length) {
            var dpg = default_price_group ?
                default_price_group : 0;
            $('#default_price_group').val(dpg);
        }

        set_payment_type_dropdown();

        if ($('#types_of_service_id').length && $('#types_of_service_id').val()) {
            $('#types_of_service_id').change();
        }
    });

    //get customer
    $('select#customer_id').select2({
        ajax: {
            url: '/contacts/customers',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
        },
        templateResult: function (data) {
            var template = '';
            if (data.supplier_business_name) {
                template += data.supplier_business_name + "<br>";
            }
            template += data.text + "<br>" + LANG.mobile + ": " + data.mobile;

            if (typeof (data.total_rp) != "undefined") {
                var rp = data.total_rp ? data.total_rp : 0;
                template += "<br><i class='fa fa-gift text-success'></i> " + rp;
            }

            return template;
        },
        minimumInputLength: 1,
        language: {
            inputTooShort: function (args) {
                return LANG.please_enter + args.minimum + LANG.or_more_characters;
            },
            noResults: function () {
                var name = $('#customer_id')
                    .data('select2')
                    .dropdown.$search.val();
                return (
                    '<button type="button" data-name="' +
                    name +
                    '" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" style="color: #800000;" aria-hidden="true"></i>&nbsp; ' +
                    __translate('add_name_as_new_customer', { name: name }) +
                    '</button>'
                );
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        },
    });
    $('#customer_id').on('select2:select', function (e) {
        var data = e.params.data;
        if (data.pay_term_number) {
            $('input#pay_term_number').val(data.pay_term_number);
        } else {
            $('input#pay_term_number').val('');
        }

        if (data.pay_term_type) {
            $('#add_sell_form select[name="pay_term_type"]').val(data.pay_term_type);
            $('#edit_sell_form select[name="pay_term_type"]').val(data.pay_term_type);
        } else {
            $('#add_sell_form select[name="pay_term_type"]').val('');
            $('#edit_sell_form select[name="pay_term_type"]').val('');
        }

        update_shipping_address(data);
        $('#advance_balance_text').text(__currency_trans_from_en(data.balance), true);
        $('#advance_balance').val(data.balance);

        if (parseFloat(data.balance) > 0) {
            $('#advance_deduct_checkbox_wrapper').removeClass('hide');
        } else {
            $('#advance_deduct_checkbox_wrapper').addClass('hide');
            $('#deduct_from_advance').prop('checked', false);
        }

        if (data.price_calculation_type == 'selling_price_group') {
            $('#price_group').val(data.selling_price_group_id);
            $('#price_group').change();
        }
        //  else {
        //     $('#price_group').val(0);
        //     $('#price_group').change();
        // }
        get_contact_due(data.id);
        // store on customer change
        saveFormDataToLocalStorage();
    });

    set_default_customer();

    if ($('#search_product').length) {
        //Add Product
        $('#search_product')
            .autocomplete({
                delay: 1000,
                source: function (request, response) {
                    console.log('Autocomplete search triggered for:', request.term);
                    var price_group = '';
                    var search_fields = [];
                    $('.search_fields:checked').each(function (i) {
                        search_fields[i] = $(this).val();
                    });

                    if ($('#price_group').length > 0) {
                        price_group = $('#price_group').val();
                    }

                    var location_id = $('input#location_id').val();
                    console.log('Search parameters - location_id:', location_id, 'term:', request.term);

                    $.getJSON(
                        '/products/list',
                        {
                            price_group: price_group,
                            location_id: location_id,
                            term: request.term,
                            not_for_selling: 0,
                            search_fields: search_fields
                        },
                        response
                    );
                },
                minLength: 2,
                response: function (event, ui) {
                    console.log('Autocomplete response received:', ui.content.length, 'products');
                    if (ui.content.length > 0) {
                        console.log('First product:', ui.content[0]);
                    }
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];

                        var is_overselling_allowed = false;
                        if ($('input#is_overselling_allowed').length) {
                            is_overselling_allowed = true;
                        }
                        var for_so = false;
                        if ($('#sale_type').length && $('#sale_type').val() == 'sales_order') {
                            for_so = true;
                        }

                        if ((ui.item.enable_stock == 1 && ui.item.qty_available > 0) ||
                            (ui.item.enable_stock == 0) || is_overselling_allowed || for_so) {
                            $(this)
                                .data('ui-autocomplete')
                                ._trigger('select', 'autocompleteselect', ui);
                            $(this).autocomplete('close');
                        }
                    } else if (ui.content.length == 0) {
                        toastr.error(LANG.no_products_found);
                        if (!$('#__is_mobile').length) {
                            $('input#search_product').select();
                        }
                    }
                },
                focus: function (event, ui) {
                    if (ui.item.qty_available <= 0) {
                        return false;
                    }
                },
                select: function (event, ui) {
                    console.log('Product selected from autocomplete:', ui.item);
                    var searched_term = $(this).val();
                    var is_overselling_allowed = false;
                    if ($('input#is_overselling_allowed').length) {
                        is_overselling_allowed = true;
                    }
                    var for_so = false;
                    if ($('#sale_type').length && $('#sale_type').val() == 'sales_order') {
                        for_so = true;
                    }

                    var is_draft = false;
                    if ($('#status') && ($('#status').val() == 'quotation' ||
                        $('#status').val() == 'draft')) {
                        var is_draft = true;
                    }

                    console.log('Stock check - enable_stock:', ui.item.enable_stock);
                    console.log('Stock check - qty_available:', ui.item.qty_available);
                    console.log('Stock check - is_overselling_allowed:', is_overselling_allowed);
                    console.log('Stock check - for_so:', for_so);
                    console.log('Stock check - is_draft:', is_draft);

                    if (ui.item.enable_stock != 1 || ui.item.qty_available > 0 || is_overselling_allowed || for_so || is_draft) {
                        $(this).val(null);
                        console.log('Calling pos_product_row with variation_id:', ui.item.variation_id);

                        //Pre select lot number only if the searched term is same as the lot number
                        var purchase_line_id = ui.item.purchase_line_id && searched_term == ui.item.lot_number ? ui.item.purchase_line_id : null;
                        if (typeof __pos_show_batch_select === 'function' && !purchase_line_id) {
                            __pos_show_batch_select(ui.item.variation_id, 1);
                        } else {
                            pos_product_row(ui.item.variation_id, purchase_line_id);
                        }
                    } else {
                        console.log('Product out of stock, showing alert');
                        alert(LANG.out_of_stock);
                    }
                },
            })
            .autocomplete('instance')._renderItem = function (ul, item) {
                var is_overselling_allowed = false;
                if ($('input#is_overselling_allowed').length) {
                    is_overselling_allowed = true;
                }

                var for_so = false;
                if ($('#sale_type').length && $('#sale_type').val() == 'sales_order') {
                    for_so = true;
                }
                var is_draft = false;

                if ($('#status') && ($('#status').val() == 'quotation' ||
                    $('#status').val() == 'draft')) {
                    var is_draft = true;
                }

                if (item.enable_stock == 1 && item.qty_available <= 0 && !is_overselling_allowed && !for_so && !is_draft) {
                    var string = '<li class="ui-state-disabled">' + item.name;
                    if (item.type == 'variable') {
                        string += '-' + item.variation;
                    }
                    var selling_price = item.selling_price;
                    if (item.variation_group_price) {
                        selling_price = item.variation_group_price;
                    }
                    string +=
                        ' (' +
                        item.sub_sku +
                        ')' +
                        '<br> Price: ' +
                        __currency_trans_from_en(selling_price, false, false, __currency_precision, true) +
                        ' (Out of stock) </li>';
                    return $(string).appendTo(ul);
                } else {
                    var string = '<div>' + item.name;
                    if (item.type == 'variable') {
                        string += '-' + item.variation;
                    }

                    var selling_price = item.selling_price;
                    if (item.variation_group_price) {
                        selling_price = item.variation_group_price;
                    }

                    string += ' (' + item.sub_sku + ')' + '<br> Price: ' + __currency_trans_from_en(selling_price, false, false, __currency_precision, true);
                    if (item.enable_stock == 1) {
                        var qty_available = __currency_trans_from_en(item.qty_available, false, false, __currency_precision, true);
                        string += ' - ' + qty_available + item.unit;
                    }
                    string += '</div>';

                    return $('<li>')
                        .append(string)
                        .appendTo(ul);
                }
            };
    }

    //Update line total and check for quantity not greater than max quantity
    $('table#pos_table tbody').on('change', 'input.pos_quantity', function () {
        // comment line becouse it validate form at increment and decrement item
        // if (sell_form_validator) {
        //     sell_form.valid();
        // }
        if (pos_form_validator) {
            pos_form_validator.element($(this));
        }
        // var max_qty = parseFloat($(this).data('rule-max'));
        var entered_qty = __read_number($(this));

        var tr = $(this).parents('tr');

        // Show overselling warning if entered qty exceeds available stock
        var allow_overselling_qty = $(this).data('allow-overselling');
        if (allow_overselling_qty === true || allow_overselling_qty === 'true') {
            var qty_available = parseFloat($(this).data('qty_available'));
            if (!isNaN(qty_available) && entered_qty > qty_available) {
                toastr.warning(
                    '⚠️ Entered quantity (' + entered_qty + ') exceeds available stock (' + qty_available + '). Overselling is enabled — the excess will be adjusted from future stock.',
                    'Overselling Alert',
                    { timeOut: 6000, extendedTimeOut: 2000 }
                );
            }
        }

        var unit_price_inc_tax = __read_number(tr.find('input.pos_unit_price_inc_tax'));
        var line_total = entered_qty * unit_price_inc_tax;


        __write_number(tr.find('input.pos_line_total'), line_total, false);
        tr.find('span.pos_line_total_text').text(__currency_trans_from_en(line_total, true));

        //Change modifier quantity
        tr.find('.modifier_qty_text').each(function () {
            $(this).text(__currency_trans_from_en(entered_qty, false));
        });
        tr.find('.modifiers_quantity').each(function () {
            $(this).val(entered_qty);
        });

        pos_total_row();

        updatePosProductListStock();

        adjustComboQty(tr);
    });

    //If change in unit price update price including tax and line total
    $('table#pos_table tbody').on('change', 'input.pos_unit_price', function () {
        var $unitPriceInput = $(this);
        var unit_price = __read_number($unitPriceInput);

        var min_value = parseFloat($unitPriceInput.attr('data-rule-min-value'));
        if (!isNaN(min_value) && unit_price < min_value) {
            var msg = $unitPriceInput.attr('data-msg-min-value');
            if (typeof toastr !== 'undefined' && msg) {
                toastr.error(msg);
            }
            unit_price = min_value;
            __write_number($unitPriceInput, unit_price);
        }
        var tr = $(this).parents('tr');

        //calculate discounted unit price
        var discounted_unit_price = calculate_discounted_unit_price(tr);

        var tax_details = getPosTaxDetails(tr.find('select.tax_id'));
        var quantity = __read_number(tr.find('input.pos_quantity'));

        var unit_price_inc_tax = posAddTax(discounted_unit_price, tax_details);
        var line_total = quantity * unit_price_inc_tax;

        __write_number(tr.find('input.pos_unit_price_inc_tax'), unit_price_inc_tax);
        __write_number(tr.find('input.pos_line_total'), line_total);
        tr.find('span.pos_line_total_text').text(__currency_trans_from_en(line_total, true));
        pos_each_row(tr);
        pos_total_row();
        round_row_to_iraqi_dinnar(tr);
    });

    //If change in tax rate then update unit price according to it.
    $('table#pos_table tbody').on('change', 'select.tax_id', function () {
        var tr = $(this).parents('tr');

        var tax_details = getPosTaxDetails(tr.find('select.tax_id'));
        var unit_price_inc_tax = __read_number(tr.find('input.pos_unit_price_inc_tax'));

        var discounted_unit_price = posRemoveTax(unit_price_inc_tax, tax_details);
        var unit_price = get_unit_price_from_discounted_unit_price(tr, discounted_unit_price);
        __write_number(tr.find('input.pos_unit_price'), unit_price);
        pos_each_row(tr);
    });

    //If change in unit price including tax, update unit price
    $('table#pos_table tbody').on('change', 'input.pos_unit_price_inc_tax', function () {
        var $unitPriceIncTaxInput = $(this);
        var unit_price_inc_tax = __read_number($unitPriceIncTaxInput);

        var min_value = parseFloat($unitPriceIncTaxInput.attr('data-rule-min-value'));
        if (!isNaN(min_value) && unit_price_inc_tax < min_value) {
            var msg = $unitPriceIncTaxInput.attr('data-msg-min-value');
            if (typeof toastr !== 'undefined' && msg) {
                toastr.error(msg);
            }
            unit_price_inc_tax = min_value;
            __write_number($unitPriceIncTaxInput, unit_price_inc_tax);
        }

        if (iraqi_selling_price_adjustment) {
            unit_price_inc_tax = round_to_iraqi_dinnar(unit_price_inc_tax);
            __write_number($unitPriceIncTaxInput, unit_price_inc_tax);
        }

        var tr = $(this).parents('tr');

        var tax_details = getPosTaxDetails(tr.find('select.tax_id'));
        var quantity = __read_number(tr.find('input.pos_quantity'));

        var line_total = quantity * unit_price_inc_tax;
        var discounted_unit_price = posRemoveTax(unit_price_inc_tax, tax_details);
        var unit_price = get_unit_price_from_discounted_unit_price(tr, discounted_unit_price);

        __write_number(tr.find('input.pos_unit_price'), unit_price);
        __write_number(tr.find('input.pos_line_total'), line_total, false);
        tr.find('span.pos_line_total_text').text(__currency_trans_from_en(line_total, true));

        pos_each_row(tr);
        pos_total_row();
    });

    //Change max quantity rule if lot number changes
    $('table#pos_table tbody').on('change', 'select.lot_number', function () {
        var qty_element = $(this)
            .closest('tr')
            .find('input.pos_quantity');

        var tr = $(this).closest('tr');
        var multiplier = 1;
        var unit_name = '';
        var sub_unit_length = tr.find('select.sub_unit').length;
        if (sub_unit_length > 0) {
            var select = tr.find('select.sub_unit');
            multiplier = parseFloat(select.find(':selected').data('multiplier'));
            unit_name = select.find(':selected').data('unit_name');
        }
        var allow_overselling = qty_element.data('allow-overselling');
        if ($(this).val() && !allow_overselling) {
            var lot_qty = $('option:selected', $(this)).data('qty_available');
            var max_err_msg = $('option:selected', $(this)).data('msg-max');

            if (sub_unit_length > 0) {
                lot_qty = lot_qty / multiplier;
                var lot_qty_formated = __number_f(lot_qty, false);
                max_err_msg = __translate('lot_max_qty_error', {
                    max_val: lot_qty_formated,
                    unit_name: unit_name,
                });
            }

            qty_element.attr('data-rule-max-value', lot_qty);
            qty_element.attr('data-msg-max-value', max_err_msg);

            qty_element.rules('add', {
                'max-value': lot_qty,
                messages: {
                    'max-value': max_err_msg,
                },
            });
        } else {
            var default_qty = qty_element.data('qty_available');
            var default_err_msg = qty_element.data('msg_max_default');
            if (sub_unit_length > 0) {
                default_qty = default_qty / multiplier;
                var lot_qty_formated = __number_f(default_qty, false);
                default_err_msg = __translate('pos_max_qty_error', {
                    max_val: lot_qty_formated,
                    unit_name: unit_name,
                });
            }

            qty_element.attr('data-rule-max-value', default_qty);
            qty_element.attr('data-msg-max-value', default_err_msg);

            qty_element.rules('add', {
                'max-value': default_qty,
                messages: {
                    'max-value': default_err_msg,
                },
            });
        }
        qty_element.trigger('change');
    });

    //Change in row discount type or discount amount
    $('table#pos_table tbody').on(
        'change',
        'select.row_discount_type, input.row_discount_amount',
        function () {
            var tr = $(this).parents('tr');

            //calculate discounted unit price
            var discounted_unit_price = calculate_discounted_unit_price(tr);

            var tax_details = getPosTaxDetails(tr.find('select.tax_id'));
            var quantity = __read_number(tr.find('input.pos_quantity'));

            var unit_price_inc_tax = posAddTax(discounted_unit_price, tax_details);
            var line_total = quantity * unit_price_inc_tax;

            __write_number(tr.find('input.pos_unit_price_inc_tax'), unit_price_inc_tax);
            __write_number(tr.find('input.pos_line_total'), line_total, false);
            tr.find('span.pos_line_total_text').text(__currency_trans_from_en(line_total, true));
            pos_each_row(tr);
            pos_total_row();
            round_row_to_iraqi_dinnar(tr);
        }
    );

    //Remove row on click on remove row
    $('table#pos_table tbody').on('click', 'i.pos_remove_row', function () {
        $(this)
            .parents('tr')
            .remove();
        pos_total_row();

        updatePosProductListStock();
    });

    //Cancel the invoice
    $('button#pos-cancel').click(function () {
        swal({
            title: LANG.sure,
            text: LANG.are_you_sure || 'Do you really want to cancel this sale? This cannot be undone.',
            icon: 'warning',
            buttons: {
                cancel: {
                    text: LANG.no_go_back || 'No, Go Back',
                    visible: true,
                    closeModal: true,
                    className: 'btn btn-default swal-btn-cancel',
                },
                confirm: {
                    text: LANG.yes_cancel || 'Yes, Cancel',
                    closeModal: true,
                    className: 'btn btn-danger swal-btn-confirm',
                },
            },
            dangerMode: true,
        }).then(function (willCancel) {
            if (willCancel) {
                reset_pos_form();
            }
        });
    });

    //Save invoice as draft
    $('button#pos-draft').click(function () {
        //Check if product is present or not.
        if ($('table#pos_table tbody').find('.product_row').length <= 0) {
            toastr.warning(LANG.no_products_added);
            return false;
        }

        var is_valid = isValidPosForm();
        if (is_valid != true) {
            return;
        }

        // Ensure row-level edits (discount/price) are in inputs before serialize
        syncProductRowsToInputs();

        var data = pos_form_obj.serialize();
        data = data + '&status=draft';
        var url = pos_form_obj.attr('action');

        disable_pos_form_actions();
        $.ajax({
            method: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (result) {
                enable_pos_form_actions();
                if (result.success == 1) {
                    reset_pos_form();
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Save invoice as Quotation
    $('button#pos-quotation').click(function () {
        //Check if product is present or not.
        if ($('table#pos_table tbody').find('.product_row').length <= 0) {
            toastr.warning(LANG.no_products_added);
            return false;
        }

        var is_valid = isValidPosForm();
        if (is_valid != true) {
            return;
        }

        // Ensure row-level edits (discount/price) are in inputs before serialize
        syncProductRowsToInputs();

        var data = pos_form_obj.serialize();
        data = data + '&status=quotation';
        var url = pos_form_obj.attr('action');

        disable_pos_form_actions();
        $.ajax({
            method: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (result) {
                enable_pos_form_actions();
                if (result.success == 1) {
                    reset_pos_form();
                    toastr.success(result.msg);

                    //Check if enabled or not
                    if (result.receipt.is_enabled) {
                        pos_print(result.receipt);
                    }
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Finalize invoice, open payment modal
    $('button#pos-finalize').click(function () {
        //Check if product is present or not.
        if ($('table#pos_table tbody').find('.product_row').length <= 0) {
            toastr.warning(LANG.no_products_added);
            return false;
        }

        if ($('#reward_point_enabled').length) {
            var validate_rp = isValidatRewardPoint();
            if (!validate_rp['is_valid']) {
                toastr.error(validate_rp['msg']);
                return false;
            }
        }

        $('#modal_payment').modal('show');
    });

    $('#modal_payment').one('shown.bs.modal', function () {
        // Always start with due date hidden; it will show automatically if balance becomes due
        try {
            $('#pos_due_date_wrapper').addClass('hide');
            $('#pos_due_date').val('');
        } catch (err) {
            // ignore
        }

        $('#modal_payment')
            .find('input')
            .filter(':visible:first')
            .focus()
            .select();
        if ($('form#edit_pos_sell_form').length == 0) {
            $(this).find('#method_0').change();
        }
    });

    // Installment plan: toggle fields whenever payment modal opens
    $('#modal_payment').on('shown.bs.modal', function () {
        try {
            toggle_installment_plan_fields();
        } catch (err) {
            // ignore
        }
    });

    // Installment plan: toggle fields when checkbox changes
    $(document).on('change', '#enable_installment_plan', function () {
        try {
            toggle_installment_plan_fields();
            if (typeof calculate_balance_due === 'function') {
                calculate_balance_due();
            }
        } catch (err) {
            // ignore
        }
    });

    //Finalize without showing payment options
    // prevent duplicate express checkout triggers
    if (typeof window.__express_processing === 'undefined') {
        window.__express_processing = false;
    }
    $('button.pos-express-finalize').click(function () {
        if (window.__express_processing) {
            return false;
        }
        var $expressBtn = $(this);

        //Check if product is present or not.
        if ($('table#pos_table tbody').find('.product_row').length <= 0) {
            toastr.warning(LANG.no_products_added);
            return false;
        }

        if ($('#reward_point_enabled').length) {
            var validate_rp = isValidatRewardPoint();
            if (!validate_rp['is_valid']) {
                toastr.error(validate_rp['msg']);
                return false;
            }
        }

        // lock only after basic validations pass
        window.__express_processing = true;
        try { $expressBtn.prop('disabled', true); } catch (e) { }

        var pay_method = $(this).data('pay_method');

        //If pay method is credit sale submit form
        if (pay_method == 'credit_sale') {
            var default_customer_id = $('#default_customer_id').val();
            var current_customer_id = $('#customer_id').val();
            if (default_customer_id && current_customer_id == default_customer_id) {
                if (typeof swal === 'function') {
                    swal({
                        title: LANG.notice || 'Notice',
                        text: LANG.contact_register_required || 'Please register this customer to allow Credit Sale.',
                        icon: 'warning'
                    });
                } else if (typeof toastr !== 'undefined') {
                    toastr.warning(LANG.contact_register_required || 'Please register this customer to allow Credit Sale.');
                } else {
                    alert(LANG.contact_register_required || 'Please register this customer to allow Credit Sale.');
                }
                window.__express_processing = false;
                try { $expressBtn.prop('disabled', false); } catch (e) { }
                return false;
            }
            $('#is_credit_sale').val(1);
            pos_form_obj.submit();
            return true;
        } else {
            if ($('#is_credit_sale').length) {
                $('#is_credit_sale').val(0);
            }
        }

        //Check for remaining balance & add it in 1st payment row
        var total_payable = __read_number($('input#final_total_input'));
        var total_paying = __read_number($('input#total_paying_input'));
        if (total_payable > total_paying) {
            var bal_due = total_payable - total_paying;

            var first_row = $('#payment_rows_div')
                .find('.payment-amount')
                .first();
            var first_row_val = __read_number(first_row);
            first_row_val = first_row_val + bal_due;
            __write_number(first_row, first_row_val);
            first_row.trigger('change');
        }

        //Change payment method.
        var payment_method_dropdown = $('#payment_rows_div')
            .find('.payment_types_dropdown')
            .first();

        payment_method_dropdown.val(pay_method);
        payment_method_dropdown.change();
        if (pay_method == 'card') {
            // unlock so card modal action can proceed
            window.__express_processing = false;
            try { $expressBtn.prop('disabled', false); } catch (e) { }
            $('div#card_details_modal').modal('show');
        } else if (pay_method == 'suspend') {
            // unlock so suspend modal action can proceed
            window.__express_processing = false;
            try { $expressBtn.prop('disabled', false); } catch (e) { }
            $('div#confirmSuspendModal').modal('show');
        } else {
            pos_form_obj.submit();
        }
    });

    $('div#card_details_modal').on('shown.bs.modal', function (e) {
        $('input#card_number').focus();
        // Ensure select2 dropdowns render within the card modal context with proper width
        $(this)
            .find('.select2')
            .each(function () {
                try {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                } catch (err) { }
                $(this).select2({ dropdownParent: $('#card_details_modal'), width: '100%' });
            });
    });

    // If user closes card/suspend modal without completing, allow clicking again
    $('div#card_details_modal, div#confirmSuspendModal').on('hidden.bs.modal', function () {
        window.__express_processing = false;
        try { $('button.pos-express-finalize').prop('disabled', false); } catch (e) { }
    });

    $('div#confirmSuspendModal').on('shown.bs.modal', function (e) {
        $(this)
            .find('textarea')
            .focus();
    });

    //on save card details
    $('button#pos-save-card').click(function () {
        if (window.__express_processing) {
            return false;
        }
        $('input#card_number_0').val($('#card_number').val());
        $('input#card_holder_name_0').val($('#card_holder_name').val());
        $('input#card_transaction_number_0').val($('#card_transaction_number').val());
        $('select#card_type_0').val($('#card_type').val());
        $('input#card_month_0').val($('#card_month').val());
        $('input#card_year_0').val($('#card_year').val());
        $('input#card_security_0').val($('#card_security').val());

        $('div#card_details_modal').modal('hide');
        pos_form_obj.submit();
    });

    $('button#pos-suspend').click(function () {
        $('input#is_suspend').val(1);
        $('div#confirmSuspendModal').modal('hide');
        pos_form_obj.submit();
        $('input#is_suspend').val(0);
    });

    function __pos_update_cost_profit($row) {
        if (!$row || !$row.length) {
            return;
        }

        var basePurchasePriceInput = $row.find('input.pos_purchase_price_base');
        if (!basePurchasePriceInput.length) {
            return; // user doesn't have permission or panel not rendered
        }

        var basePurchasePrice = __read_number(basePurchasePriceInput);
        if (isNaN(basePurchasePrice)) {
            basePurchasePrice = 0;
        }

        var multiplierInput = $row.find('input.base_unit_multiplier');
        var multiplier = multiplierInput.length ? parseFloat(multiplierInput.val()) : 1;
        if (isNaN(multiplier) || multiplier <= 0) {
            multiplier = 1;
        }

        var unitCost = basePurchasePrice * multiplier;

        var unitPriceInput = $row.find('input.pos_unit_price_inc_tax');
        if (!unitPriceInput.length) {
            unitPriceInput = $row.find('input.pos_unit_price');
        }

        var unitSell = unitPriceInput.length ? __read_number(unitPriceInput) : 0;
        if (isNaN(unitSell)) {
            unitSell = 0;
        }

        var qty = __read_number($row.find('input.pos_quantity'));
        if (isNaN(qty)) {
            qty = 0;
        }

        var unitProfit = unitSell - unitCost;
        var totalProfit = unitProfit * qty;

        var $unitCostEl = $row.find('.pos_unit_cost');
        var $unitProfitEl = $row.find('.pos_unit_profit');
        var $totalProfitEl = $row.find('.pos_total_profit');

        var formatCurrency = function (val) {
            if (typeof __currency_trans_from_en === 'function') {
                return __currency_trans_from_en(val, true);
            }
            return val;
        };

        $unitCostEl.text(formatCurrency(unitCost));
        $unitProfitEl.text(formatCurrency(unitProfit));
        $totalProfitEl.text(formatCurrency(totalProfit));

        $unitProfitEl.toggleClass('text-danger', unitProfit < 0);
        $totalProfitEl.toggleClass('text-danger', totalProfit < 0);
    }

    $(document).on('click', 'button.toggle-cost-profit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $row = $(this).closest('tr.product_row');
        var $panel = $row.find('div.pos_cost_profit_panel');
        if (!$panel.length) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Cost/Profit panel not found for this row.');
            }
            return;
        }

        $panel.toggle();
        if ($panel.is(':visible')) {
            try {
                __pos_update_cost_profit($row);
            } catch (err) {
                if (window.console && console.error) {
                    console.error('Failed to update cost/profit', err);
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error('Could not calculate cost/profit. Check console for details.');
                }
            }
        }
    });

    $(document).on('change keyup', 'input.pos_unit_price_inc_tax, input.pos_unit_price, input.pos_quantity, select.sub_unit', function () {
        var $row = $(this).closest('tr.product_row');
        if ($row.find('div.pos_cost_profit_panel:visible').length) {
            __pos_update_cost_profit($row);
        }
    });

    //fix select2 input issue on modal
    $('#modal_payment')
        .find('.select2')
        .each(function () {
            $(this).select2({
                dropdownParent: $('#modal_payment'),
            });
        });

    $('button#add-payment-row').click(function () {
        var row_index = $('#payment_row_index').val();
        var location_id = $('input#location_id').val();
        $.ajax({
            method: 'POST',
            url: '/sells/pos/get_payment_row',
            data: { row_index: row_index, location_id: location_id },
            dataType: 'html',
            success: function (result) {
                if (result) {
                    var appended = $('#payment_rows_div').append(result);

                    var total_payable = __read_number($('input#final_total_input'));
                    var total_paying = __read_number($('input#total_paying_input'));
                    var b_due = total_payable - total_paying;
                    $(appended)
                        .find('input.payment-amount')
                        .focus();
                    $(appended)
                        .find('input.payment-amount')
                        .last()
                        .val(__currency_trans_from_en(b_due, false))
                        .change()
                        .select();
                    __select2($(appended).find('.select2'));

                    $(appended).find('.datetimepicker').datetimepicker({
                        format: moment_date_format + ' ' + moment_time_format,
                        ignoreReadonly: true,
                    });
                    $(appended).find('#method_' + row_index).change();
                    $('#payment_row_index').val(parseInt(row_index) + 1);
                }
            },
        });
    });

    $(document).on('click', '.remove_payment_row', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                $(this)
                    .closest('.payment_row')
                    .remove();
                calculate_balance_due();
            }
        });
    });

    pos_form_validator = pos_form_obj.validate({
        submitHandler: function (form) {
            // var total_payble = __read_number($('input#final_total_input'));
            // var total_paying = __read_number($('input#total_paying_input'));
            var cnf = true;

            var is_suspend = false;
            try {
                is_suspend = $('input#is_suspend').length && parseInt($('input#is_suspend').val() || '0') === 1;
            } catch (e) {
                is_suspend = false;
            }

            //Ignore if the difference is less than 0.5
            // When "Keep this payment on the current invoice" is unchecked, payment goes
            // to old dues — the current invoice is expected to remain due, so skip the alert.
            var keepOnCurrent = $('input[name="apply_payment_to_old_dues"]').length
                ? parseInt($('input[name="apply_payment_to_old_dues"]:checked').val() || $('input[name="apply_payment_to_old_dues"][type="hidden"]').val() || '0')
                : 1;
            var applyToOldDues = (keepOnCurrent !== 1);

            if (!is_suspend && !applyToOldDues && $('input#in_balance_due').val() >= 0.5) {
                // Require due date before confirming partial payment
                try {
                    var $wrapper = $('#pos_due_date_wrapper');
                    var $dueInput = $('#pos_due_date');

                    if ($wrapper.length) {
                        $wrapper.removeClass('hide');
                    }

                    if ($dueInput.length && typeof $dueInput.datepicker === 'function' && !$dueInput.data('datepicker')) {
                        $dueInput.datepicker({ autoclose: true });
                    }

                    if ($dueInput.length && ($dueInput.val() === null || $dueInput.val().toString().trim() === '')) {
                        // Default to invoice date + 30 days
                        var txDate = null;
                        if ($('#transaction_date').length && $('#transaction_date').data('DateTimePicker')) {
                            txDate = $('#transaction_date').data('DateTimePicker').date();
                        }
                        var dueMoment = (txDate ? txDate.clone() : moment()).add(30, 'days');
                        if (typeof $dueInput.datepicker === 'function') {
                            $dueInput.datepicker('update', dueMoment.toDate());
                        }
                    }

                    if ($dueInput.length && ($dueInput.val() === null || $dueInput.val().toString().trim() === '')) {
                        if (typeof toastr !== 'undefined') {
                            toastr.warning(LANG.due_date_is_required || 'Please select a due date for the pending balance.');
                        }
                        $dueInput.focus();
                        return false;
                    }
                } catch (err) {
                    // ignore and continue
                }

                cnf = confirm(LANG.paid_amount_is_less_than_payable);
                // if( total_payble > total_paying ){
                // 	cnf = confirm( LANG.paid_amount_is_less_than_payable );
                // } else if(total_payble < total_paying) {
                // 	alert( LANG.paid_amount_is_more_than_payable );
                // 	cnf = false;
                // }
            }

            var total_advance_payments = 0;
            $('#payment_rows_div').find('select.payment_types_dropdown').each(function () {
                if ($(this).val() == 'advance') {
                    total_advance_payments++
                };
            });

            if (total_advance_payments > 1) {
                alert(LANG.advance_payment_cannot_be_more_than_once);
                return false;
            }

            var is_msp_valid = true;
            //Validate minimum selling price if hidden
            $('.pos_unit_price_inc_tax').each(function () {
                if (!$(this).is(":visible") && $(this).data('rule-min-value')) {
                    var val = __read_number($(this));
                    var error_msg_td = $(this).closest('tr').find('.pos_line_total_text').closest('td');
                    error_msg_td.find('label.error').remove();
                    if (val < $(this).data('rule-min-value')) {
                        is_msp_valid = false;
                        error_msg_td.append('<label class="error">' + $(this).data('msg-min-value') + '</label>');
                    }
                }
            });

            if (!is_msp_valid) {
                return false;
            }

            if (cnf) {
                // Ensure all per-row values are in inputs before serialize
                syncProductRowsToInputs();
                disable_pos_form_actions();

                var data = $(form).serialize();
                data = data + '&status=final';
                var url = $(form).attr('action');
                $.ajax({
                    method: 'POST',
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                        if (result.success == 1) {
                            if (result.whatsapp_link) {
                                window.open(result.whatsapp_link);
                            }
                            $('#modal_payment').modal('hide');
                            toastr.success(result.msg);

                            reset_pos_form();

                            // Reload products grid to reflect correct database stock
                            if (typeof get_product_suggestion_list === 'function') {
                                get_product_suggestion_list(
                                    $('select#product_category').val(),
                                    $('select#product_brand').val(),
                                    $('input#location_id').val(),
                                    null
                                );
                            }

                            //Check if enabled or not
                            if (result.receipt.is_enabled) {
                                pos_print(result.receipt);
                            }
                        } else {
                            toastr.error(result.msg);
                        }

                        enable_pos_form_actions();
                    },
                });
            }
            return false;
        },
    });

    $(document).on('change keyup', '.payment-amount, .payment_amount', function () {
        calculate_balance_due();
    });

    //Update discount
    $('button#posEditDiscountModalUpdate').click(function () {

        //if discount amount is not valid return false
        if (!$("#discount_amount_modal").valid()) {
            return false;
        }
        //Close modal
        $('div#posEditDiscountModal').modal('hide');

        //Update values
        $('input#discount_type').val($('select#discount_type_modal').val());
        __write_number($('input#discount_amount'), __read_number($('input#discount_amount_modal')));

        if ($('#reward_point_enabled').length) {
            var reward_validation = isValidatRewardPoint();
            if (!reward_validation['is_valid']) {
                toastr.error(reward_validation['msg']);
                $('#rp_redeemed_modal').val(0);
                $('#rp_redeemed_modal').change();
            }
            updateRedeemedAmount();
        }

        pos_total_row();
    });

    //Shipping
    $('button#posShippingModalUpdate').click(function () {
        //Close modal
        $('div#posShippingModal').modal('hide');

        //update shipping details
        $('input#shipping_details').val($('#shipping_details_modal').val());

        $('input#shipping_address').val($('#shipping_address_modal').val());
        $('input#shipping_status').val($('#shipping_status_modal').val());
        $('input#delivered_to').val($('#delivered_to_modal').val());
        $('input#delivery_person').val($('#delivery_person_modal').val());

        //Update shipping charges
        __write_number(
            $('input#shipping_charges'),
            __read_number($('input#shipping_charges_modal'))
        );

        //$('input#shipping_charges').val(__read_number($('input#shipping_charges_modal')));

        pos_total_row();
    });

    $('#posShippingModal').on('shown.bs.modal', function () {
        $('#posShippingModal')
            .find('#shipping_details_modal')
            .filter(':visible:first')
            .focus()
            .select();

        // Initialize Select2 for delivery person dropdown
        $('#delivery_person_modal').select2({
            width: '100%',
            dropdownParent: $('#posShippingModal')
        });

        // $('.select2-selection__rendered').css('padding-right', '150px');
    });

    $('#posShippingModal').on('hidden.bs.modal', function () {
        // Destroy Select2 to prevent conflicts
        if ($('#delivery_person_modal').hasClass('select2-hidden-accessible')) {
            $('#delivery_person_modal').select2('destroy');
        }
    });

    $(document).on('shown.bs.modal', '.row_edit_product_price_model', function () {
        $('.row_edit_product_price_model')
            .find('input')
            .filter(':visible:first')
            .focus()
            .select();
    });

    //Update Order tax
    $('button#posEditOrderTaxModalUpdate').click(function () {
        //Close modal
        $('div#posEditOrderTaxModal').modal('hide');

        var tax_obj = $('select#order_tax_modal');
        var tax_id = tax_obj.val();
        var tax_rate = tax_obj.find(':selected').data('rate');
        var tax_type = tax_obj.find(':selected').data('type') || 'percentage';

        $('input#tax_rate_id').val(tax_id);

        __write_number($('input#tax_calculation_amount'), tax_rate);
        $('input#tax_calculation_type').val(tax_type);
        pos_total_row();
    });

    $(document).on('click', '.add_new_customer', function () {
        $('#customer_id').select2('close');
        var name = $(this).data('name');
        $('.contact_modal')
            .find('input#name')
            .val(name);
        $('.contact_modal')
            .find('select#contact_type')
            .val('customer')
            .closest('div.contact_type_div')
            .addClass('hide');
        $('.contact_modal').modal('show');
    });
    $('form#quick_add_contact')
        .submit(function (e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                contact_id: {
                    remote: {
                        url: '/contacts/check-contacts-id',
                        type: 'post',
                        data: {
                            contact_id: function () {
                                return $('#contact_id').val();
                            },
                            hidden_id: function () {
                                return $('#hidden_id').val() || '';
                            },
                        },
                    },
                },
                tax_number: {
                    remote: {
                        url: '/contacts/check-tax-number',
                        type: 'post',
                        data: {
                            contact_id: function () {
                                return $('#hidden_id').val();
                            },
                            tax_number: function () {
                                return $('#tax_number').val();
                            },
                        },
                        dataFilter: function (response) {
                            try {
                                var taxResult = JSON.parse(response);
                                if (taxResult && taxResult.is_tax_number_exists === true) {
                                    return '"' + (taxResult.msg || LANG.tax_number_already_exists) + '"';
                                }
                                return 'true';
                            } catch (e) {
                                return 'true';
                            }
                        }
                    }
                },
            },
            messages: {
                contact_id: {
                    required: LANG.contact_id_required,
                    remote: LANG.contact_id_already_exists,
                },
                tax_number: {
                    required: LANG.tax_number_required,
                },
            },
            submitHandler: function (form) {
                checkMobileAndSubmitQuick(form);
            },
        });

    function checkMobileAndSubmitQuick(form) {
        $.ajax({
            method: 'POST',
            url: base_path + '/check-mobile',
            dataType: 'json',
            data: {
                contact_id: function () {
                    return $('#hidden_id').val();
                },
                mobile_number: function () {
                    return $('#mobile').val();
                },
            },
            success: function (result) {
                if (result.is_mobile_exists == true) {
                    swal({
                        title: LANG.sure,
                        text: result.msg,
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    }).then(willContinue => {
                        if (willContinue) {
                            submitQuickContactForm(form);
                        } else {
                            $('#mobile').select();
                        }
                    });

                } else {
                    submitQuickContactForm(form);
                }
            },
        });
    }
    // allow clearing processing flag after flow completes/errors

    $('.contact_modal').on('hidden.bs.modal', function () {
        $('form#quick_add_contact')
            .find('button[type="submit"]')
            .removeAttr('disabled');
        $('form#quick_add_contact')[0].reset();
    });

    //Updates for add sell
    $('select#discount_type, input#discount_amount, input#shipping_charges, \
        input#rp_redeemed_amount').change(function () {
        pos_total_row();
    });
    $('select#tax_rate_id').change(function () {
        var selected_tax = $(this).find(':selected');
        var tax_rate = selected_tax.data('rate');
        var tax_type = selected_tax.data('type') || 'percentage';
        __write_number($('input#tax_calculation_amount'), tax_rate);
        $('input#tax_calculation_type').val(tax_type);
        pos_total_row();
    });

    if ($('select#tax_rate_id').length) {
        $('select#tax_rate_id').trigger('change');
    }
    if ($('select#order_tax_modal').length && $('input#tax_rate_id').length) {
        var default_tax_id = $('input#tax_rate_id').val();
        if (default_tax_id) {
            $('select#order_tax_modal').val(default_tax_id);
        }
        var selected_order_tax = $('select#order_tax_modal').find(':selected');
        if (selected_order_tax.length) {
            __write_number($('input#tax_calculation_amount'), selected_order_tax.data('rate') || 0);
            $('input#tax_calculation_type').val(selected_order_tax.data('type') || 'percentage');
        }
    }
    //Datetime picker
    $('#transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });

    //Direct sell submit
    sell_form = $('form#add_sell_form');
    if ($('form#edit_sell_form').length) {
        sell_form = $('form#edit_sell_form');
        pos_total_row();
    }
    sell_form_validator = sell_form.validate({
        rules: {
            invoice_no: {
                remote: {
                    url: '/sell/check-invoice-number',
                    type: 'post',
                    data: {
                        invoice_no: function () {
                            return $('#invoice_no').val();
                        },
                        transaction_id: function () {
                            var id = '';
                            var editForm = $('form#edit_sell_form');
                            if (editForm.length) {
                                id = editForm.data('transaction-id');
                            }
                            return id || '';
                        }
                    }
                }
            },
        },
        messages: {
            invoice_no: {
                remote: LANG.invoice_number_already_exists,
            },
        },
    });

    $('button#submit-sell, button#save-and-print').click(function (e) {
        //Check if product is present or not.
        if ($('table#pos_table tbody').find('.product_row').length <= 0) {
            toastr.warning(LANG.no_products_added);
            return false;
        }

        var is_msp_valid = true;
        //Validate minimum selling price if hidden
        $('.pos_unit_price_inc_tax').each(function () {
            if (!$(this).is(":visible") && $(this).data('rule-min-value')) {
                var val = __read_number($(this));
                var error_msg_td = $(this).closest('tr').find('.pos_line_total_text').closest('td');
                error_msg_td.find('label.error').remove();
                if (val < $(this).data('rule-min-value')) {
                    is_msp_valid = false;
                    error_msg_td.append('<label class="error">' + $(this).data('msg-min-value') + '</label>');
                }
            }
        });

        if (!is_msp_valid) {
            return false;
        }

        if ($(this).attr('id') == 'save-and-print') {
            $('#is_save_and_print').val(1);
        } else {
            $('#is_save_and_print').val(0);
        }

        if ($('#reward_point_enabled').length) {
            var validate_rp = isValidatRewardPoint();
            if (!validate_rp['is_valid']) {
                toastr.error(validate_rp['msg']);
                return false;
            }
        }

        if ($('.enable_cash_denomination_for_payment_methods').length) {
            var payment_row = $('.enable_cash_denomination_for_payment_methods').closest('.payment_row');
            var is_valid = true;
            var payment_type = payment_row.find('.payment_types_dropdown').val();
            var denomination_for_payment_types = JSON.parse($('.enable_cash_denomination_for_payment_methods').val());
            if (denomination_for_payment_types.includes(payment_type) && payment_row.find('.is_strict').length && payment_row.find('.is_strict').val() === '1') {
                var payment_amount = __read_number(payment_row.find('.payment-amount'));
                var total_denomination = payment_row.find('input.denomination_total_amount').val();
                if (payment_amount != total_denomination) {
                    is_valid = false;
                }
            }

            if (!is_valid) {
                payment_row.find('.cash_denomination_error').removeClass('hide');
                toastr.error(payment_row.find('.cash_denomination_error').text());
                e.preventDefault();
                return false;
            } else {
                payment_row.find('.cash_denomination_error').addClass('hide');
            }
        }

        if (sell_form.valid()) {
            window.onbeforeunload = null;
            $(this).attr('disabled', true);
            sell_form.submit();
        }
    });

    //REPAIR MODULE:check if repair module field is present send data to filter product
    var is_enabled_stock = null;
    if ($("#is_enabled_stock").length) {
        is_enabled_stock = $("#is_enabled_stock").val();
    }

    var device_model_id = null;
    if ($("#repair_model_id").length) {
        device_model_id = $("#repair_model_id").val();
    }

    //Show product list.
    get_product_suggestion_list(
        global_p_category_id,
        global_brand_id,
        $('input#location_id').val(),
        null,
        is_enabled_stock,
        device_model_id
    );

    $('select#select_location_id').on('change', function (e) {
        $('input#suggestion_page').val(1);
        var location_id = $('input#location_id').val();
        if (location_id != '' || location_id != undefined) {
            get_product_suggestion_list(
                global_p_category_id,
                global_brand_id,
                $('input#location_id').val(),
                null
            );
        }
        get_featured_products();
    });

    // on click sub category in category drawer
    $('.product_category').on('click', function (e) {
        global_p_category_id = $(this).data('value');
        $('input#suggestion_page').val(1);
        var location_id = $('input#location_id').val();
        if (location_id != '' || location_id != undefined) {
            get_product_suggestion_list(
                global_p_category_id,
                global_brand_id,
                $('input#location_id').val(),
                null
            );
        }
        get_featured_products();
        $('.overlay-category').trigger('click');
    });

    //  function for show sub category 
    $('.main-category').on('click', function () {

        global_p_category_id = $(this).data('value');
        parent = $(this).data('parent');

        if (parent == 0) {
            get_product_suggestion_list(
                global_p_category_id,
                global_brand_id,
                $('input#location_id').val(),
                null
            );
            get_featured_products();
            $('.overlay-category').trigger('click');
        }
        else {
            var main_category = $(this).data('value');

            $('.main-category-div').hide();
            $('.' + main_category).fadeIn();
            $('.category_heading').text('Sub Category ' + $(this).data('name'));
            $('.category-back').fadeIn();
        }
    })

    // function for back button in category 
    $('.category-back').on('click', function () {
        $('.main-category-div').fadeIn();
        $('.main-category-all').fadeIn();
        $('.all-sub-category').hide();
        $('.category-back').hide();
        $('.category_heading').text('Category');
    });

    // on click brand in brand drawer 
    $('.product_brand').on('click', function (e) {
        global_brand_id = $(this).data('value');
        $('input#suggestion_page').val(1);
        var location_id = $('input#location_id').val();

        if (location_id != '' || location_id != undefined) {
            get_product_suggestion_list(
                global_p_category_id,
                global_brand_id,
                $('input#location_id').val(),
                null
            );
        }
        get_featured_products();
        $('.overlay-brand').trigger('click');
    });

    // close side bar 

    $('.close-side-bar-category').on('click', function () {
        $('.overlay-category').trigger('click');
    });

    $('.close-side-bar-brand').on('click', function () {
        $('.overlay-brand').trigger('click');
    });




    $(document).on('click', 'div.product_box', function () {
        //Check if location is not set then show error message.
        if ($('input#location_id').val() == '') {
            toastr.warning(LANG.select_location);
        } else {
            var variation_id = $(this).data('variation_id');
            if (typeof __pos_show_batch_select === 'function') {
                __pos_show_batch_select(variation_id, 1);
            } else {
                pos_product_row(variation_id);
            }
        }
    });

    $(document).on('shown.bs.modal', '.row_description_modal', function () {
        $(this)
            .find('textarea')
            .first()
            .focus();
    });

    //Press enter on search product to jump into last quantty and vice-versa
    $('#search_product').keydown(function (e) {
        var key = e.which;
        if (key == 9) {
            // the tab key code
            e.preventDefault();
            if ($('#pos_table tbody tr').length > 0) {
                $('#pos_table tbody tr:last')
                    .find('input.pos_quantity')
                    .focus()
                    .select();
            }
        }
    });
    $('#pos_table').on('keypress', 'input.pos_quantity', function (e) {
        var key = e.which;
        if (key == 13) {
            // the enter key code
            if (!$('#__is_mobile').length) {
                $('#search_product').focus();
            }
        }
    });

    $('#exchange_rate').change(function () {
        var curr_exchange_rate = 1;
        if ($(this).val()) {
            curr_exchange_rate = __read_number($(this));
        }
        var total_payable = __read_number($('input#final_total_input'));
        var shown_total = total_payable * curr_exchange_rate;
        $('span#total_payable').text(__currency_trans_from_en(shown_total, false));
    });

    $('select#price_group').change(function () {
        $('input#hidden_price_group').val($(this).val());
    });

    //Quick add product
    $(document).on('click', 'button.pos_add_quick_product', function () {
        var url = $(this).data('href');
        var container = $(this).data('container');
        $.ajax({
            url: url + '?product_for=pos',
            dataType: 'html',
            success: function (result) {
                $(container)
                    .html(result)
                    .modal('show');
                $('.os_exp_date').datepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy',
                    clearBtn: true,
                });
            },
        });
    });

    $(document).on('change', 'form#quick_add_product_form input#single_dpp', function () {
        var unit_price = __read_number($(this));
        $('table#quick_product_opening_stock_table tbody tr').each(function () {
            var input = $(this).find('input.unit_price');
            __write_number(input, unit_price);
            input.change();
        });
    });

    $(document).on('quickProductAdded', function (e) {
        //Check if location is not set then show error message.
        if ($('input#location_id').val() == '') {
            toastr.warning(LANG.select_location);
        } else {
            var variation_id = e.variation.id;
            if (typeof __pos_show_batch_select === 'function') {
                __pos_show_batch_select(variation_id, 1);
            } else {
                pos_product_row(variation_id);
            }
        }
    });

    $('div.view_modal').on('show.bs.modal', function () {
        __currency_convert_recursively($(this));
    });

    $('table#pos_table').on('change', 'select.sub_unit', function () {
        var tr = $(this).closest('tr');
        var selected_option = $(this).find(':selected');
        var multiplier = parseFloat(selected_option.data('multiplier'));
        var allow_decimal = parseInt(selected_option.data('allow_decimal'));

        var current_multiplier = parseFloat(tr.find('input.base_unit_multiplier').val()) || 1;
        tr.find('input.base_unit_multiplier').val(multiplier);

        var multiplier_ratio = 1;
        if (current_multiplier !== 0) {
           multiplier_ratio = multiplier / current_multiplier;
        }

        var sp_element = tr.find('input.pos_unit_price');
        var current_unit_sp = __read_number(sp_element);
        var new_unit_sp = current_unit_sp * multiplier_ratio;
        
        __write_number(sp_element, new_unit_sp);

        var inc_tax_element = tr.find('input.pos_unit_price_inc_tax');
        if (inc_tax_element.length) {
            var current_inc_tax = __read_number(inc_tax_element);
            __write_number(inc_tax_element, current_inc_tax * multiplier_ratio);
        }

        if (typeof tr.data('modal-base-unit-price') !== 'undefined') {
            tr.data('modal-base-unit-price', tr.data('modal-base-unit-price') * multiplier_ratio);
        }

        // Scale minimum selling price if present
        var min_value = parseFloat(sp_element.attr('data-rule-min-value'));
        if (!isNaN(min_value)) {
            sp_element.attr('data-rule-min-value', min_value * multiplier_ratio);
        }
        if (inc_tax_element.length) {
            var min_value_inc_tax = parseFloat(inc_tax_element.attr('data-rule-min-value'));
            if (!isNaN(min_value_inc_tax)) {
                inc_tax_element.attr('data-rule-min-value', min_value_inc_tax * multiplier_ratio);
            }
        }

        // Scale fixed discount if present
        var row_discount_type = tr.find('.row_discount_type').length ? tr.find('.row_discount_type').val() : (tr.data('modal-discount-type') || 'fixed');
        if (row_discount_type === 'fixed') {
            if (tr.find('.row_discount_amount').length) {
                var current_discount = __read_number(tr.find('.row_discount_amount'));
                if (current_discount) __write_number(tr.find('.row_discount_amount'), current_discount * multiplier_ratio);
            }
            if (typeof tr.data('modal-discount-amount') !== 'undefined') {
                tr.data('modal-discount-amount', parseFloat(tr.data('modal-discount-amount')) * multiplier_ratio);
            }
        }

        sp_element.change();
        
        if (typeof pos_each_row !== 'undefined') pos_each_row(tr);
        if (typeof pos_total_row !== 'undefined') pos_total_row();

        var qty_element = tr.find('input.pos_quantity');
        var base_max_avlbl = qty_element.data('qty_available');
        var error_msg_line = 'pos_max_qty_error';

        if (tr.find('select.lot_number').length > 0) {
            var lot_select = tr.find('select.lot_number');
            if (lot_select.val()) {
                base_max_avlbl = lot_select.find(':selected').data('qty_available');
                error_msg_line = 'lot_max_qty_error';
            }
        } else if (tr.find('input.row_batch_id').length && String(tr.find('input.row_batch_id').val() || '').length > 0) {
            base_max_avlbl = qty_element.data('qty_available');
            error_msg_line = 'lot_max_qty_error';
        }

        qty_element.attr('data-decimal', allow_decimal);
        var abs_digit = true;
        if (allow_decimal) {
            abs_digit = false;
        }
        qty_element.rules('add', {
            abs_digit: abs_digit,
        });

        if (base_max_avlbl) {
            var max_avlbl = parseFloat(base_max_avlbl) / multiplier;
            var formated_max_avlbl = __number_f(max_avlbl);
            var unit_name = selected_option.data('unit_name');
            var max_err_msg = __translate(error_msg_line, {
                max_val: formated_max_avlbl,
                unit_name: unit_name,
            });
            qty_element.attr('data-rule-max-value', max_avlbl);
            qty_element.attr('data-msg-max-value', max_err_msg);
            qty_element.rules('add', {
                'max-value': max_avlbl,
                messages: {
                    'max-value': max_err_msg,
                },
            });
            qty_element.trigger('change');
        }
        adjustComboQty(tr);
    });

    //Confirmation before page load.
    window.onbeforeunload = function () {
        if ($('form#edit_pos_sell_form').length == 0) {
            if ($('table#pos_table tbody tr').length > 0) {
                return LANG.sure;
            } else {
                return null;
            }
        }
    }
    $(window).resize(function () {
        var win_height = $(window).height();
        div_height = __calculate_amount('percentage', 63, win_height);
        // $('div.pos_product_div').css('min-height', div_height + 'px');
        // $('div.pos_product_div').css('max-height', div_height + 'px');
    });

    //Used for weighing scale barcode
    $('#weighing_scale_modal').on('shown.bs.modal', function (e) {

        //Attach the scan event
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // enter-key expected at the end of a scan
            reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
            onScan: function (sCode, iQty) {
                console.log('Scanned: ' + iQty + 'x ' + sCode);
                $('input#weighing_scale_barcode').val(sCode);
                $('button#weighing_scale_submit').trigger('click');
            },
            onScanError: function (oDebug) {
                console.log(oDebug);
            },
            minLength: 2
            // onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
            //     console.log('Pressed: ' + iKeyCode);
            // }
        });

        $('input#weighing_scale_barcode').focus();
    });

    $('#weighing_scale_modal').on('hide.bs.modal', function (e) {
        //Detach from the document once modal is closed.
        onScan.detachFrom(document);
    });

    $('button#weighing_scale_submit').click(function () {

        var price_group = '';
        if ($('#price_group').length > 0) {
            price_group = $('#price_group').val();
        }

        if ($('#weighing_scale_barcode').val().length > 0) {
            pos_product_row(null, null, $('#weighing_scale_barcode').val());
            $('#weighing_scale_modal').modal('hide');
            $('input#weighing_scale_barcode').val('');
        } else {
            $('input#weighing_scale_barcode').focus();
        }
    });

    $('#show_featured_products').click(function () {
        if (!$('#featured_products_box').is(':visible')) {
            $('#featured_products_box').fadeIn();
        } else {
            $('#featured_products_box').fadeOut();
        }
    });
    validate_discount_field();
    set_payment_type_dropdown();
    if ($('#__is_mobile').length) {
        $('.pos_form_totals').css('margin-bottom', $('.pos-form-actions').height() - 30);
    }

    setInterval(function () {
        if ($('span.curr_datetime').length) {
            $('span.curr_datetime').html(__current_datetime());
        }
    }, 60000);

    set_search_fields();
});

// Block partial payments for Walk-In Customer
$(document).on('click', '#pos-save', function (e) {
    try {
        // Ensure latest balance is calculated (user may not blur the amount field)
        try {
            calculate_balance_due();
        } catch (errCalc) {
            // ignore
        }

        var installmentEnabled = $('#enable_installment_plan').length && $('#enable_installment_plan').is(':checked');

        var default_customer_id = $('#default_customer_id').val();
        var current_customer_id = $('#customer_id').val();
        if (!installmentEnabled && default_customer_id && current_customer_id == default_customer_id) {
            // Calculate total payments entered
            var totalEntered = 0;
            $('input.payment-amount, input.payment_amount').each(function () {
                var v = __number_uf($(this).val());
                totalEntered += isNaN(v) ? 0 : v;
            });
            // Compare with final total
            var finalTotalText = $('#final_total_input').val() || $('.final_total_span').data('orig-value');
            var finalTotal = __number_uf(finalTotalText);
            if (finalTotal > 0 && totalEntered + 0.0001 < finalTotal) {
                e.preventDefault();
                var msg = LANG.contact_register_required_partial || 'Partial payments are not allowed for Walk-In Customer. Please register the customer or pay in full.';
                if (typeof swal === 'function') {
                    swal({ title: LANG.notice || 'Notice', text: msg, icon: 'warning' });
                } else if (typeof toastr !== 'undefined') {
                    toastr.warning(msg);
                } else {
                    alert(msg);
                }
                return false;
            }
        }

        // Require due date for partial payments
        var bal_due = __number_uf($('input#in_balance_due').val());
        if (!isNaN(bal_due) && bal_due >= 0.5) {
            var $wrapper = $('#pos_due_date_wrapper');
            var $dueInput = $('#pos_due_date');

            if ($wrapper.length) {
                $wrapper.removeClass('hide');
            }

            // Initialize datepicker once
            if ($dueInput.length && typeof $dueInput.datepicker === 'function' && !$dueInput.data('datepicker')) {
                try {
                    $dueInput.datepicker({ autoclose: true });
                } catch (err) {
                    // ignore
                }
            }

            // Default to invoice date + 30 days for normal due payments.
            if ($dueInput.length && ($dueInput.val() === null || $dueInput.val().toString().trim() === '')) {
                try {
                    updatePosDueDate(30, 'auto-submit-default');
                } catch (err2) {
                    // ignore
                }
            }

            // If still empty, block submit and ask user to choose
            if ($dueInput.length && ($dueInput.val() === null || $dueInput.val().toString().trim() === '')) {
                e.preventDefault();
                if (typeof toastr !== 'undefined') {
                    toastr.warning(LANG.due_date_is_required || 'Please select a due date for the pending balance.');
                }
                $dueInput.focus();
                return false;
            }
        } else {
            // Fully paid: hide and clear due date
            $('#pos_due_date_wrapper').addClass('hide');
            $('#pos_due_date').val('');
            $('#pos_due_date').removeAttr('data-due-date-source');
        }

        if (installmentEnabled) {
            var $installmentDueInput = $('#installment_first_due_date');
            if ($installmentDueInput.length && ($installmentDueInput.val() === null || $installmentDueInput.val().toString().trim() === '')) {
                updateInstallmentFirstDueDate();
            }
        }
    } catch (err) {
        // fail open to avoid blocking sale unexpectedly
    }
});

function set_payment_type_dropdown() {
    var payment_settings = $('#location_id').data('default_payment_accounts');
    payment_settings = payment_settings ? payment_settings : [];
    enabled_payment_types = [];
    for (var key in payment_settings) {
        if (payment_settings[key] && payment_settings[key]['is_enabled']) {
            enabled_payment_types.push(key);
        }
    }
    $(".payment_types_dropdown > option").each(function () {
        var payment_type = $(this).val();
        //skip if advance or custom payment
        if (payment_type && payment_type != 'advance') {
            // Hide custom payments (custom_pay_1 through custom_pay_7)
            if (payment_type.startsWith('custom_pay_')) {
                $(this).addClass('hide');
            } else if (enabled_payment_types.length) {
                if (enabled_payment_types.indexOf(payment_type) != -1) {
                    $(this).removeClass('hide');
                } else {
                    $(this).addClass('hide');
                }
            }
        }
    });
}

function get_featured_products() {
    var location_id = $('#location_id').val();
    if (location_id && $('#featured_products_box').length > 0) {
        $.ajax({
            method: 'GET',
            url: '/sells/pos/get-featured-products/' + location_id,
            dataType: 'html',
            success: function (result) {
                if (result) {
                    $('#feature_product_div').removeClass('hide');
                    $('#featured_products_box').html(result);
                } else {
                    $('#feature_product_div').addClass('hide');
                    $('#featured_products_box').html('');
                }

                updatePosProductListStock();
            },
        });
    } else {
        $('#feature_product_div').addClass('hide');
        $('#featured_products_box').html('');
    }
}

function get_product_suggestion_list(category_id, brand_id, location_id, url = null, is_enabled_stock = null, repair_model_id = null) {
    if ($('div#product_list_body').length == 0) {
        return false;
    }

    if (url == null) {
        url = '/sells/pos/get-product-suggestion';
    }
    $('#suggestion_page_loader').fadeIn(700);
    var page = $('input#suggestion_page').val();
    if (page == 1) {
        $('div#product_list_body').html('');
    }
    if ($('div#product_list_body').find('input#no_products_found').length > 0) {
        $('#suggestion_page_loader').fadeOut(700);
        return false;
    }
    $.ajax({
        method: 'GET',
        url: url,
        data: {
            category_id: category_id,
            brand_id: brand_id,
            location_id: location_id,
            page: page,
            is_enabled_stock: is_enabled_stock,
            repair_model_id: repair_model_id
        },
        dataType: 'html',
        success: function (result) {
            $('div#product_list_body').append(result);
            $('#suggestion_page_loader').fadeOut(700);

            updatePosProductListStock();
        },
    });
}

function getCartQtyByVariationInBaseUnits() {
    var qtyMap = {};

    if ($('#pos_table').length === 0) {
        return qtyMap;
    }

    $('#pos_table tbody tr.product_row').each(function () {
        var $row = $(this);
        var variationId = $row.find('input.row_variation_id').val();
        if (!variationId) {
            return;
        }

        var qty = __read_number($row.find('input.pos_quantity'));
        if (isNaN(qty)) {
            qty = 0;
        }

        var multiplier = parseFloat($row.find('input.base_unit_multiplier').val());
        if (isNaN(multiplier) || multiplier <= 0) {
            multiplier = 1;
        }

        var qtyInBase = qty * multiplier;
        if (!qtyMap[variationId]) {
            qtyMap[variationId] = 0;
        }
        qtyMap[variationId] += qtyInBase;
    });

    return qtyMap;
}

function updatePosProductListStock() {
    // UI-only live stock indicator for product cards.
    // Stock is finally deducted on save, this only reflects cart quantities.

    var qtyMap = getCartQtyByVariationInBaseUnits();

    $('.product_box[data-variation_id]').each(function () {
        var $box = $(this);
        var enableStock = parseInt($box.data('enable_stock')) === 1;
        if (!enableStock) {
            return;
        }

        var variationId = $box.data('variation_id');
        if (!variationId) {
            return;
        }

        var origQty = parseFloat($box.data('orig_qty_available'));
        if (isNaN(origQty)) {
            return;
        }

        var qtyInCart = qtyMap[String(variationId)] || qtyMap[parseInt(variationId)] || 0;
        var availableQty = origQty - qtyInCart;
        if (availableQty < 0) {
            availableQty = 0;
        }

        var formattedQty = availableQty;
        if (typeof __number_f === 'function') {
            formattedQty = __number_f(availableQty, false);
        }

        $box.find('span.js_pos_product_stock_qty').text(formattedQty);
    });
}

//Get recent transactions
function get_recent_transactions(status, element_obj) {
    if (element_obj.length == 0) {
        return false;
    }
    var transaction_sub_type = $("#transaction_sub_type").val();
    $.ajax({
        method: 'GET',
        url: '/sells/pos/get-recent-transactions',
        data: { status: status, transaction_sub_type: transaction_sub_type },
        dataType: 'html',
        success: function (result) {
            element_obj.html(result);
            __currency_convert_recursively(element_obj);
        },
    });
}

/**
 * Fetches available batches for a variation at the current location and,
 * depending on how many there are:
 *   - 0 batches → adds to cart normally (no modal; never shown).
 *   - 1 batch  → adds with that purchase_line_id (no modal; never shown).
 *   - 2+ batches → opens the batch-select modal only after data is ready.
 */
function __pos_show_batch_select(variation_id, quantity) {
    var location_id = $('input#location_id').val();
    if (!location_id) {
        toastr.error('Location not selected. Please select a location first.');
        return;
    }

    var $modal = $('#pos_batch_select_modal');

    // If the modal was never rendered (feature disabled server-side), fall through.
    if (!$modal.length) {
        window.__batch_select_in_progress = true;
        try { pos_product_row(variation_id, null, null, quantity); }
        finally { window.__batch_select_in_progress = false; }
        return;
    }

    // Do not open the modal until we know there are 2+ batches — avoids flash on 0/1 batch products.
    $.ajax({
        method: 'GET',
        url: '/sells/pos/get-batches',
        data: { variation_id: variation_id, location_id: location_id },
        dataType: 'json',
    }).done(function (res) {
        var batches = (res && res.batches) ? res.batches : [];

        if (!batches.length) {
            window.__batch_select_in_progress = true;
            try { pos_product_row(variation_id, null, null, quantity); }
            finally { window.__batch_select_in_progress = false; }
            return;
        }

        if (batches.length === 1) {
            window.__batch_select_in_progress = true;
            try { pos_product_row(variation_id, null, null, quantity, batches[0].id); }
            finally { window.__batch_select_in_progress = false; }
            return;
        }

        // Two or more batches: populate and show the modal (first time it becomes visible).
        var $body = $modal.find('tbody').empty();
        $modal.find('#pos_batch_select_loading').hide();
        $modal.find('#pos_batch_select_empty').hide();

        var labels = {
            batch: $modal.find('thead th:eq(1)').text().trim(),
            price: $modal.find('thead th:eq(2)').text().trim(),
            stock: $modal.find('thead th:eq(3)').text().trim(),
            date: $modal.find('thead th:eq(4)').text().trim()
        };

        batches.forEach(function (b, idx) {
            var priceRaw = (b.display_sell_price_inc_tax !== null && b.display_sell_price_inc_tax !== undefined)
                ? b.display_sell_price_inc_tax
                : ((b.batch_selling_price_inc_tax !== null && b.batch_selling_price_inc_tax !== undefined)
                    ? b.batch_selling_price_inc_tax
                    : null);
            var price = priceRaw !== null
                ? __currency_trans_from_en(priceRaw, true)
                : '—';
            var row = '<tr>' +
                '<td data-label="#">' + (idx + 1) + '</td>' +
                '<td data-label="' + labels.batch + '"><strong>' + (b.batch_number || (LANG.standard_restock_short || 'Batch 1')) + '</strong>' +
                    (b.lot_number ? '<br><small class="text-muted">Lot: ' + b.lot_number + '</small>' : '') +
                '</td>' +
                '<td data-label="' + labels.price + '">' + price + '</td>' +
                '<td data-label="' + labels.stock + '">' + (b.remaining || 0) + '</td>' +
                '<td data-label="' + labels.date + '">' + (b.transaction_date || '') + '</td>' +
                '<td><button type="button" class="btn btn-primary btn-sm pos_batch_pick_btn" ' +
                    'data-batch_id="' + b.id + '" ' +
                    'data-variation_id="' + variation_id + '" ' +
                    'data-quantity="' + (quantity || 1) + '">' +
                    (LANG.select || 'Select') +
                '</button></td>' +
                '</tr>';
            $body.append(row);
        });

        $modal.find('#pos_batch_select_table').show();
        $modal.modal('show');
    }).fail(function () {
        toastr.error('Could not load batches.');
        window.__batch_select_in_progress = true;
        try { pos_product_row(variation_id, null, null, quantity); }
        finally { window.__batch_select_in_progress = false; }
    });
}

// Reset modal DOM when closed (e.g. user dismissed without choosing).
$(document).on('hidden.bs.modal', '#pos_batch_select_modal', function () {
    var $m = $('#pos_batch_select_modal');
    $m.find('#pos_batch_select_table').hide();
    $m.find('tbody').empty();
    $m.find('#pos_batch_select_loading').hide();
    $m.find('#pos_batch_select_empty').hide();
});

// Delegated handler: picks a batch and adds it to cart with its price.
$(document).on('click', '.pos_batch_pick_btn', function () {
    var pick_batch_id = $(this).data('batch_id');
    var variation_id = $(this).data('variation_id');
    var quantity = $(this).data('quantity') || 1;

    $('#pos_batch_select_modal').modal('hide');

    window.__batch_select_in_progress = true;
    try {
        pos_product_row(variation_id, null, null, quantity, pick_batch_id);
    } finally {
        window.__batch_select_in_progress = false;
    }
});

//variation_id is null when weighing_scale_barcode is used.
function pos_product_row(variation_id = null, purchase_line_id = null, weighing_scale_barcode = null, quantity = 1, batch_id = null) {

    // Batch-pricing: when enabled, intercept the add-to-cart flow and
    // prompt the cashier to pick a specific batch if more than one exists.
    // We only intercept when the caller did NOT already supply purchase_line_id
    // or batch_id (lot-dropdown path uses purchase_line_id).
    var batch_pricing_on = window.__enable_batch_pricing === true
        || window.__enable_batch_pricing === 1
        || window.__enable_batch_pricing === '1';

    var incoming_batch = (batch_id !== null && batch_id !== undefined && batch_id !== '') ? String(batch_id) : '';

    if (batch_pricing_on && variation_id && !purchase_line_id && incoming_batch === '' && !weighing_scale_barcode) {
        if (!window.__batch_select_in_progress) {
            __pos_show_batch_select(variation_id, quantity);
            return;
        }
        // __batch_select_in_progress: invoked from the batch modal with batch_id set — continue.
    }

    //Get item addition method
    var item_addtn_method = 0;
    var add_via_ajax = true;

    if (variation_id != null && $('#item_addition_method').length) {
        item_addtn_method = $('#item_addition_method').val();
    }

    if (item_addtn_method == 0) {
        add_via_ajax = true;
    } else {
        var is_added = false;

        //Search for variation id in each row of pos table
        $('#pos_table tbody')
            .find('tr')
            .each(function () {
                var row_v_id = $(this)
                    .find('.row_variation_id')
                    .val();
                if (batch_pricing_on && String(row_v_id) == String(variation_id)) {
                    var row_batch_el = $(this).find('input.row_batch_id');
                    var row_batch = row_batch_el.length ? String(row_batch_el.val() || '') : '';
                    if (incoming_batch !== row_batch) {
                        return;
                    }
                    var incoming_lot = purchase_line_id ? String(purchase_line_id) : '';
                    var row_lot = '';
                    var $lotSel = $(this).find('select.lot_number');
                    if ($lotSel.length) {
                        row_lot = String($lotSel.val() || '');
                    } else {
                        var $lotH = $(this).find('input.lot_no_line_id_batch_purchase_line');
                        if ($lotH.length) {
                            row_lot = String($lotH.val() || '');
                        }
                    }
                    if (incoming_lot !== '' && incoming_lot !== row_lot) {
                        return;
                    }
                }
                var enable_sr_no = $(this)
                    .find('.enable_sr_no')
                    .val();
                var modifiers_exist = false;
                if ($(this).find('input.modifiers_exist').length > 0) {
                    modifiers_exist = true;
                }
                if (
                    row_v_id == variation_id &&
                    enable_sr_no !== '1' &&
                    !modifiers_exist &&
                    !is_added
                ) {
                    add_via_ajax = false;
                    is_added = true;

                    //Increment product quantity
                    qty_element = $(this).find('.pos_quantity');
                    var qty = __read_number(qty_element);
                    __write_number(qty_element, qty + 1);
                    qty_element.change();

                    round_row_to_iraqi_dinnar($(this));

                    if (!$('#__is_mobile').length) {
                        $('input#search_product')
                            .focus()
                            .select();
                    }
                }
            });
    }

    if (add_via_ajax) {
        var product_row = $('input#product_row_count').val();
        var location_id = $('input#location_id').val();
        var customer_id = $('select#customer_id').val();

        /* debug removed */
        /* debug removed */

        if (!location_id) {
            console.error('Location ID is missing!');
            toastr.error('Location not selected. Please select a location first.');
            return;
        }
        var is_direct_sell = false;
        if (
            $('input[name="is_direct_sale"]').length > 0 &&
            $('input[name="is_direct_sale"]').val() == 1
        ) {
            is_direct_sell = true;
        }

        var disable_qty_alert = false;

        if ($('#disable_qty_alert').length) {
            disable_qty_alert = true;
        }

        var is_sales_order = $('#sale_type').length && $('#sale_type').val() == 'sales_order' ? true : false;

        var price_group = '';
        if ($('#price_group').length > 0) {
            price_group = parseInt($('#price_group').val());
        }

        //If default price group present
        if ($('#default_price_group').length > 0 &&
            price_group === '') {
            price_group = $('#default_price_group').val();
        }

        //If types of service selected give more priority
        if ($('#types_of_service_price_group').length > 0 &&
            $('#types_of_service_price_group').val()) {
            price_group = $('#types_of_service_price_group').val();
        }

        var is_draft = false;
        if ($('#status') && ($('#status').val() == 'quotation' ||
            $('#status').val() == 'draft')) {
            is_draft = true;
        }

        var is_serial_no = false;

        if (
            $('input[name="is_serial_no"]').length > 0 &&
            $('input[name="is_serial_no"]').val() == 1
        ) {
            is_serial_no = true;
        }

        /* debug removed */
        /* debug removed */

        $.ajax({
            method: 'GET',
            url: '/sells/pos/get_product_row/' + variation_id + '/' + location_id,
            async: false,
            data: {
                product_row: product_row,
                customer_id: customer_id,
                is_direct_sell: is_direct_sell,
                is_serial_no: is_serial_no,
                price_group: price_group,
                purchase_line_id: purchase_line_id,
                batch_id: incoming_batch,
                weighing_scale_barcode: weighing_scale_barcode,
                quantity: quantity,
                is_sales_order: is_sales_order,
                disable_qty_alert: disable_qty_alert,
                is_draft: is_draft
            },
            dataType: 'json',
            success: function (result) {
                /* debug removed */
                if (result.success) {
                    /* debug removed */

                    // Try the new animation method first
                    try {
                        var $newRow = $(result.html_content);
                        $newRow.css({
                            'opacity': '0',
                            'transform': 'translateY(20px)',
                            'transition': 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                        });

                        var $tableBody = $('table#pos_table tbody');
                        /* debug removed */
                        $tableBody.append($newRow);

                        // Animate the row in
                        setTimeout(function () {
                            $newRow.css({
                                'opacity': '1',
                                'transform': 'translateY(0)'
                            });
                        }, 50);
                    } catch (e) {
                        console.error('Animation method failed, using fallback:', e);
                        // Fallback to original method
                        $('table#pos_table tbody').append(result.html_content);
                    }

                    //increment row count
                    $('input#product_row_count').val(parseInt(product_row) + 1);
                    var this_row = $('table#pos_table tbody')
                        .find('tr')
                        .last();
                    /* debug removed */
                    pos_each_row(this_row);

                    // Check if modal elements are present
                    var modalCount = $('.row_edit_product_price_model').length;
                    /* debug removed */

                    // Immediately update empty state
                    /* debug removed */
                    updateEmptyState();

                    // ── Overselling alert ──────────────────────────────────────
                    // Read attributes from the newly added row's quantity input
                    var $qtyInput = this_row.find('input.pos_quantity');
                    var rowAllowOverselling = $qtyInput.data('allow-overselling');
                    if (rowAllowOverselling === true || rowAllowOverselling === 'true') {
                        var rowQtyAvailable = parseFloat($qtyInput.data('qty_available'));
                        if (!isNaN(rowQtyAvailable) && rowQtyAvailable <= 0) {
                            toastr.warning(
                                '⚠️ This product is out of stock (Available: ' + rowQtyAvailable + '). Overselling is enabled — the sold quantity will be adjusted automatically from future stock.',
                                'Overselling Alert',
                                { timeOut: 7000, extendedTimeOut: 3000, closeButton: true }
                            );
                        }
                    }
                    // ──────────────────────────────────────────────────────────

                    //For initial discount if present
                    var line_total = __read_number(this_row.find('input.pos_line_total'));
                    this_row.find('span.pos_line_total_text').text(line_total);

                    pos_total_row();

                    updatePosProductListStock();

                    //Check if multipler is present then multiply it when a new row is added.
                    if (__getUnitMultiplier(this_row) > 1) {
                        this_row.find('select.sub_unit').trigger('change');
                    }

                    if (result.enable_sr_no == '1') {
                        var new_row = $('table#pos_table tbody')
                            .find('tr')
                            .last();
                        new_row.find('.row_edit_product_price_model').modal('show');
                    }

                    round_row_to_iraqi_dinnar(this_row);
                    __currency_convert_recursively(this_row);

                    if (!$('#__is_mobile').length) {
                        $('input#search_product')
                            .focus()
                            .select();
                    }

                    //Used in restaurant module
                    if (result.html_modifier) {
                        $('table#pos_table tbody')
                            .find('tr')
                            .last()
                            .find('td:first')
                            .append(result.html_modifier);
                    }

                    // Update empty state after a short delay to ensure DOM is updated
                    setTimeout(function () {
                        /* debug removed */
                        updateEmptyState();
                    }, 200);

                    //scroll bottom of items list
                    $(".pos_product_div").animate({ scrollTop: $('.pos_product_div').prop("scrollHeight") }, 1000);
                } else {
                    toastr.error(result.msg);
                    if (!$('#__is_mobile').length) {
                        $('input#search_product')
                            .focus()
                            .select();
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Response:', xhr.responseText);
                toastr.error('Error adding product: ' + error);
            }
        });
    }
}

//Update values for each row
function pos_each_row(row_obj) {
    // Get tax rate first
    var tax_details = getPosTaxDetails(row_obj.find('select.tax_id'));

    // Determine base unit price (pre-tax)
    var base_unit_price = row_obj.data('modal-base-unit-price');
    if (typeof base_unit_price === 'undefined' || isNaN(base_unit_price) || base_unit_price === 0) {
        base_unit_price = __read_number(row_obj.find('input.pos_unit_price'));
        if (base_unit_price === 0) {
            var inc_tax_price = __read_number(row_obj.find('input.pos_unit_price_inc_tax'));
            base_unit_price = posRemoveTax(inc_tax_price, tax_details);
        }
    }

    // Apply row discount on the base unit price
    // Discount values may be hidden; fallback to data attrs
    var row_discount_type = (row_obj.find('.row_discount_type').length ? row_obj.find('.row_discount_type').val() : null);
    if (!row_discount_type) {
        row_discount_type = row_obj.data('modal-discount-type') || 'fixed';
    }
    var row_discount_amount = (row_obj.find('.row_discount_amount').length ? __read_number(row_obj.find('.row_discount_amount')) : null);
    if (row_discount_amount === null || isNaN(row_discount_amount)) {
        row_discount_amount = row_obj.data('modal-discount-amount') || 0;
    }
    var discounted_unit_price = base_unit_price;
    if (row_discount_amount) {
        if (row_discount_type === 'fixed') {
            discounted_unit_price = base_unit_price - row_discount_amount;
        } else {
            discounted_unit_price = __substract_percent(base_unit_price, row_discount_amount);
        }
    }

    // Recompute tax-inclusive price
    var unit_price_inc_tax = posAddTax(discounted_unit_price, tax_details);
    __write_number(row_obj.find('input.pos_unit_price_inc_tax'), unit_price_inc_tax);

    // Line total
    var qty = __read_number(row_obj.find('input.pos_quantity'));
    var line_total = qty * unit_price_inc_tax;
    __write_number(row_obj.find('input.pos_line_total'), line_total);
    row_obj.find('span.pos_line_total_text').text(__currency_trans_from_en(line_total, true));

    // Item tax per unit
    __write_number(row_obj.find('input.item_tax'), unit_price_inc_tax - discounted_unit_price);
}

function pos_total_row() {
    var total_quantity = 0;
    var price_total = get_subtotal();
    $('table#pos_table tbody tr').each(function () {
        total_quantity = total_quantity + __read_number($(this).find('input.pos_quantity'));
    });

    //updating shipping charges
    $('span#shipping_charges_amount').text(
        __currency_trans_from_en(__read_number($('input#shipping_charges_modal')), false)
    );

    $('span.total_quantity').each(function () {
        $(this).html(__number_f(total_quantity));
    });

    //$('span.unit_price_total').html(unit_price_total);
    $('span.price_total').html(__currency_trans_from_en(price_total, false));
    calculate_billing_details(price_total);

    if (
        $('input[name="is_serial_no"]').length > 0 &&
        $('input[name="is_serial_no"]').val() == 1
    ) {
        update_serial_no();
    }
    // store on any update
    saveFormDataToLocalStorage();

}

function get_subtotal() {
    var price_total = 0;

    $('table#pos_table tbody tr').each(function () {
        price_total = price_total + __read_number($(this).find('input.pos_line_total'));
    });

    //Go through the modifier prices.
    $('input.modifiers_price').each(function () {
        var modifier_price = __read_number($(this));
        var modifier_quantity = $(this).closest('.product_modifier').find('.modifiers_quantity').val();
        var modifier_subtotal = modifier_price * modifier_quantity;
        price_total = price_total + modifier_subtotal;
    });

    return price_total;
}

function calculate_billing_details(price_total) {
    var discount = pos_discount(price_total);
    if ($('#reward_point_enabled').length) {
        total_customer_reward = $('#rp_redeemed_amount').val();
        discount = parseFloat(discount) + parseFloat(total_customer_reward);

        if ($('input[name="is_direct_sale"]').length <= 0) {
            $('span#total_discount').text(__currency_trans_from_en(discount, false));
        }
    }

    var order_tax = pos_order_tax(price_total, discount);

    //Add shipping charges.
    var shipping_charges = __read_number($('input#shipping_charges'));

    var additional_expense = 0;
    //calculate additional expenses
    if ($('input#additional_expense_value_1').length > 0) {
        additional_expense += __read_number($('input#additional_expense_value_1'));
    }
    if ($('input#additional_expense_value_2').length > 0) {
        additional_expense += __read_number($('input#additional_expense_value_2'))
    }
    if ($('input#additional_expense_value_3').length > 0) {
        additional_expense += __read_number($('input#additional_expense_value_3'))
    }
    if ($('input#additional_expense_value_4').length > 0) {
        additional_expense += __read_number($('input#additional_expense_value_4'))
    }

    //Add packaging charge
    var packing_charge = 0;
    if ($('#types_of_service_id').length > 0 &&
        $('#types_of_service_id').val()) {
        packing_charge = __calculate_amount($('#packing_charge_type').val(),
            __read_number($('input#packing_charge')), price_total);

        $('#packing_charge_text').text(__currency_trans_from_en(packing_charge, false));
    }

    var total_payable = price_total + order_tax - discount + shipping_charges + packing_charge + additional_expense;

    // Subtract exchange credit if in exchange mode
    if ($('#exchange_credit').length > 0) {
        var exchange_credit = __read_number($('#exchange_credit'));
        total_payable = total_payable - exchange_credit;
    }

    var rounding_multiple = $('#amount_rounding_method').val() ? parseFloat($('#amount_rounding_method').val()) : 0;
    var round_off_data = __round(total_payable, rounding_multiple);
    var total_payable_rounded = round_off_data.number;

    var round_off_amount = round_off_data.diff;
    if (round_off_amount != 0) {
        $('span#round_off_text').text(__currency_trans_from_en(round_off_amount, false));
    } else {
        $('span#round_off_text').text(0);
    }
    $('input#round_off_amount').val(round_off_amount);

    __write_number($('input#final_total_input'), total_payable_rounded);
    var curr_exchange_rate = 1;
    if ($('#exchange_rate').length > 0 && $('#exchange_rate').val()) {
        curr_exchange_rate = __read_number($('#exchange_rate'));
    }
    var shown_total = total_payable_rounded * curr_exchange_rate;
    $('span#total_payable').text(__currency_trans_from_en(shown_total, false));

    $('span.total_payable_span').text(__currency_trans_from_en(total_payable_rounded, true));

    //Check if edit form then don't update price.
    if ($('form#edit_pos_sell_form').length == 0 && $('form#edit_sell_form').length == 0) {
        __write_number($('.payment-amount').first(), total_payable_rounded);
    }

    $(document).trigger('invoice_total_calculated');

    calculate_balance_due();
}

function pos_discount(total_amount) {
    var calculation_type = $('#discount_type').val();
    var calculation_amount = __read_number($('#discount_amount'));

    var discount = __calculate_amount(calculation_type, calculation_amount, total_amount);

    $('span#total_discount').text(__currency_trans_from_en(discount, false));

    return discount;
}

function pos_order_tax(price_total, discount) {
    var tax_rate_id = $('#tax_rate_id').val();
    var calculation_type = $('#tax_calculation_type').val() || 'percentage';
    var calculation_amount = __read_number($('#tax_calculation_amount'));
    var total_amount = price_total - discount;

    if (tax_rate_id) {
        var order_tax = __calculate_amount(calculation_type, calculation_amount, total_amount);
    } else {
        var order_tax = 0;
    }

    $('span#order_tax').text(__currency_trans_from_en(order_tax, false));

    return order_tax;
}

function calculate_balance_due() {
    var total_payable = __read_number($('#final_total_input'));
    var total_paying = 0;
    $('#payment_rows_div')
        .find('.payment-amount')
        .each(function () {
            var v = __read_number($(this));
            if (!isNaN(v) && v) {
                total_paying += v;
            }
        });
    var keep_on_current_invoice = $('#apply_payment_to_old_dues').length && $('#apply_payment_to_old_dues').is(':checked');
    var apply_to_old_dues = !keep_on_current_invoice;

    // Check if the selected customer is the walk-in customer
    var is_walk_in = false;
    if ($('#customer_id').length && $('#default_customer_id').length) {
        if ($('#customer_id').val() == $('#default_customer_id').val()) {
            is_walk_in = true;
        }
    }

    // Determine past due amounts BEFORE evaluating the UI rules for walk-ins
    var past_due = 0;
    if ($('.contact_due_text').length && !$('.contact_due_text').hasClass('hide')) {
        var due_text = $('.contact_due_text').find('span').text();
        if (due_text) {
            past_due = __number_uf(due_text);
            if (isNaN(past_due) || past_due < 0) {
                past_due = 0;
            }
        }
    }

    // Hide/show the "Keep payment on current invoice" wrapper based on whether they have past due
    // Walk-ins inherently shouldn't have past due applying logic exposed to them anyway
    if (past_due > 0 && !is_walk_in) {
        $('.apply_to_old_dues_wrapper').removeClass('hide');
    } else {
        $('.apply_to_old_dues_wrapper').addClass('hide');
    }

    // Force payment to current invoice for walk-in customers (preventing it from applying to old dues)
    if (is_walk_in) {
        apply_to_old_dues = false;
        $('#apply_payment_to_old_dues').prop('checked', true);
    } else if ($('.apply_to_old_dues_wrapper').hasClass('hide')) {
        // If it's a registered customer but the wrapper is hidden (no past due),
        // we default it to checked so any overpayment becomes change instead of being un-applied.
        // Or actually, if they don't have past due, it doesn't matter, but setting to checked is safer.
        apply_to_old_dues = false;
        $('#apply_payment_to_old_dues').prop('checked', true);
    } else {
        // If it's a registered customer WITH a past due (wrapper IS visible),
        // we want to ensure it defaults to UNCHECKED to prioritize paying old dues,
        // UNLESS the user explicitly checked it. But wait, we shouldn't wipe their manual check.
        // Let's only uncheck it if they JUST switched to this customer. We can tracking that by
        // checking if the past_due was just populated.
        // Actually, if we just let the UI handle it, we shouldn't force uncheck it every time
        // calculate is called, otherwise they can never check it!
        // So we only force it to unchecked if it was previously forced to checked by the walk-in logic.
        // Let's add a data attribute when we force check it.
        if ($('#apply_payment_to_old_dues').data('forced_checked')) {
            $('#apply_payment_to_old_dues').prop('checked', false);
            $('#apply_payment_to_old_dues').data('forced_checked', false);
            apply_to_old_dues = true; // since we just unchecked it
        }
    }

    if (is_walk_in) {
        $('#apply_payment_to_old_dues').data('forced_checked', true);
    }

    var amount_payable_preview = total_payable + past_due;
    if (!is_walk_in) {
        $('#pos_receipt_previous_due').text(__currency_trans_from_en(past_due, true));
        $('#pos_receipt_amount_payable').text(__currency_trans_from_en(amount_payable_preview, true));
        $('#pos_receipt_due_preview').removeClass('hide');
    } else {
        $('#pos_receipt_due_preview').addClass('hide');
    }

    var payment_for_old_dues = 0;
    var payment_for_current = total_paying;

    if (apply_to_old_dues && past_due > 0) {
        payment_for_old_dues = Math.min(total_paying, past_due);
        payment_for_current = total_paying - payment_for_old_dues;
    }

    // Calculate balance due and change return based on the amount remaining for the current invoice
    var raw_bal_due = total_payable - payment_for_current;
    var advance_to_use = 0;
    var advance_balance = parseFloat($('#advance_balance').val()) || 0;
    var deduct_checked = $('#deduct_from_advance').is(':checked');

    if (advance_balance > 0 && raw_bal_due > 0 && !is_walk_in && deduct_checked) {
        advance_to_use = Math.min(raw_bal_due, advance_balance);
    }

    var bal_due = raw_bal_due - advance_to_use;
    var change_return = 0;

    if (bal_due < 0 || Math.abs(bal_due) < 0.05) {
        change_return = bal_due * -1;
        bal_due = 0;
    }

    __write_number($('input#change_return'), change_return);
    $('span.change_return_span').text(__currency_trans_from_en(change_return, true));

    if (change_return !== 0) {
        $('#change_return_payment_data').removeClass('hide');
    } else {
        $('#change_return_payment_data').addClass('hide');
    }

    __write_number($('input#total_paying_input'), total_paying);
    $('span.total_paying').text(__currency_trans_from_en(total_paying, true));

    __write_number($('input#in_balance_due'), bal_due);
    $('span.balance_due').text(__currency_trans_from_en(bal_due, true));

    // Hide balance row if balance is <= 0
    if (bal_due <= 0) {
        $('.balance_due_row').addClass('hide');
    } else {
        $('.balance_due_row').removeClass('hide');
    }

    // Show/hide due date field depending on balance
    try {
        if (bal_due >= 0.5) {
            var $wrapper = $('#pos_due_date_wrapper');
            var $dueInput = $('#pos_due_date');

            $wrapper.removeClass('hide');

            if ($dueInput.length && typeof $dueInput.datepicker === 'function' && !$dueInput.data('datepicker')) {
                $dueInput.datepicker({ autoclose: true });
            }

            if ($dueInput.length && ($dueInput.val() === null || $dueInput.val().toString().trim() === '')) {
                var dueDays = $('#pos_due_date_dropdown').length && $('#pos_due_date_dropdown').val() !== 'custom'
                    ? parseInt($('#pos_due_date_dropdown').val(), 10)
                    : 60;
                updatePosDueDate(dueDays, 'auto-balance-default');
            }
        } else {
            $('#pos_due_date_wrapper').addClass('hide');
            $('#pos_due_date').val('');
            $('#pos_due_date').removeAttr('data-due-date-source');
        }
    } catch (err) {
        // ignore
    }

    __highlight(bal_due * -1, $('span.balance_due'));
    __highlight(change_return * -1, $('span.change_return_span'));

    // Advance deduction hint (inline)
    try {
        var $hint_text = $('#advance_auto_deduct_text');
        if ($hint_text.length && advance_to_use > 0) {
            var msg = '';
            if (bal_due > 0.01) {
                msg = '(Covers: ' + __currency_trans_from_en(advance_to_use, true) + ' — Remaining: ' + __currency_trans_from_en(bal_due, true) + ')';
            } else {
                msg = '(Covers everything — Fully Paid)';
            }
            $hint_text.text(msg);
        } else if ($hint_text.length) {
            $hint_text.text('');
        }
    } catch (e) { /* ignore */ }

    // store payment details
    saveFormDataToLocalStorage();
}

function toggle_installment_plan_fields() {
    var enabled = $('#enable_installment_plan').length && $('#enable_installment_plan').is(':checked');
    var $wrapper = $('#installment_plan_fields_wrapper');
    if (!$wrapper.length) {
        return;
    }

    if (enabled) {
        $wrapper.removeClass('hide');

        // Initialize installment first due date separately from payment due date.
        try {
            var $installmentDueInput = $('#installment_first_due_date');
            if ($installmentDueInput.length && typeof $installmentDueInput.datepicker === 'function' && !$installmentDueInput.data('datepicker')) {
                $installmentDueInput.datepicker({ autoclose: true });
            }

            if ($installmentDueInput.length && ($installmentDueInput.val() === null || $installmentDueInput.val().toString().trim() === '')) {
                updateInstallmentFirstDueDate();
            }
        } catch (e) {
            // ignore
        }
    } else {
        $wrapper.addClass('hide');
    }

    // Update first due date when interval fields change
    if (enabled) {
        $(document).off('change', '#installment_interval').on('change', '#installment_interval', function () {
            updateInstallmentFirstDueDate();
        });

        $(document).off('change', '#installment_interval_type').on('change', '#installment_interval_type', function () {
            updateInstallmentFirstDueDate();
        });
    }
}

function isValidPosForm() {
    flag = true;
    $('span.error').remove();

    if ($('select#customer_id').val() == null) {
        flag = false;
        error = '<span class="error">' + LANG.required + '</span>';
        $(error).insertAfter($('select#customer_id').parent('div'));
    }

    if ($('tr.product_row').length == 0) {
        flag = false;
        error = '<span class="error">' + LANG.no_products + '</span>';
        $(error).insertAfter($('input#search_product').parent('div'));
    }

    // Prevent walk-in customers from making partial payments
    if ($('#customer_id').length && $('#default_customer_id').length) {
        if ($('#customer_id').val() == $('#default_customer_id').val()) {
            var bal_due = __read_number($('input#in_balance_due'));
            if (bal_due > 0) {
                flag = false;
                toastr.error('Walk-In Customers must pay the full amount. Partial payments are not allowed.');
            }
        }
    }

    return flag;
}

function reset_pos_form() {

    //If on edit page then redirect to Add POS page
    if ($('form#edit_pos_sell_form').length > 0) {
        setTimeout(function () {
            window.location = $("input#pos_redirect_url").val();
        }, 4000);
        return true;
    }

    //reset all repair defects tags
    if ($("#repair_defects").length > 0) {
        tagify_repair_defects.removeAllTags();
    }

    if (pos_form_obj[0]) {
        pos_form_obj[0].reset();
    }
    if (sell_form[0]) {
        sell_form[0].reset();
    }
    set_default_customer();
    set_location();

    $('tr.product_row').remove();
    $('span.total_quantity, span.price_total, span#total_discount, span#order_tax, span#total_payable, span#shipping_charges_amount').text(0);
    $('span.total_payable_span', 'span.total_paying', 'span.balance_due').text(0);

    updatePosProductListStock();

    $('#modal_payment').find('.remove_payment_row').each(function () {
        $(this).closest('.payment_row').remove();
    });

    // Reset due date field in payment modal
    try {
        $('#pos_due_date_wrapper').addClass('hide');
        $('#pos_due_date').val('');
        $('#pos_due_date').removeAttr('data-due-date-source');
        $('#pos_due_date_dropdown').val('60');
        $('#custom_due_days_wrapper').addClass('hide');
        $('#custom_due_days').val('');
    } catch (err) {
        // ignore
    }

    // Reset installment plan fields in payment modal
    try {
        $('#enable_installment_plan').prop('checked', false);
        $('#installment_plan_fields_wrapper').addClass('hide');
        $('#installment_first_due_date').val('');
    } catch (err2) {
        // ignore
    }

    if ($('#is_credit_sale').length) {
        $('#is_credit_sale').val(0);
    }

    //Reset discount
    __write_number($('input#discount_amount'), $('input#discount_amount').data('default'));
    $('input#discount_type').val($('input#discount_type').data('default'));

    //Reset tax rate
    $('input#tax_rate_id').val($('input#tax_rate_id').data('default'));
    __write_number($('input#tax_calculation_amount'), $('input#tax_calculation_amount').data('default'));
    $('input#tax_calculation_type').val($('input#tax_calculation_type').data('default') || 'percentage');

    $('select.payment_types_dropdown').val('cash').trigger('change');
    $('#price_group').trigger('change');

    //Reset shipping
    __write_number($('input#shipping_charges'), $('input#shipping_charges').data('default'));
    $('input#shipping_details').val($('input#shipping_details').data('default'));
    $('input#shipping_address, input#shipping_status, input#delivered_to').val('');
    if ($('input#is_recurring').length > 0) {
        $('input#is_recurring').iCheck('update');
    };
    if ($('input#is_kitchen_order').length > 0) {
        $('input#is_kitchen_order').iCheck('update');
    };
    if ($('#invoice_layout_id').length > 0) {
        $('#invoice_layout_id').trigger('change');
    };
    $('span#round_off_text').text(0);

    //repair module extra  fields reset
    if ($('#repair_device_id').length > 0) {
        $('#repair_device_id').val('').trigger('change');
    }

    //Status is hidden in sales order
    if ($('#status').length > 0 && $('#status').is(":visible")) {
        $('#status').val('').trigger('change');
    }
    if ($('#transaction_date').length > 0) {
        $('#transaction_date').data("DateTimePicker").date(moment());
    }
    if ($('.paid_on').length > 0) {
        $('.paid_on').data("DateTimePicker").date(moment());
    }
    if ($('#commission_agent').length > 0) {
        $('#commission_agent').val('').trigger('change');
    }

    //reset contact due
    $('.contact_due_text').find('span').text('');
    $('.contact_due_text').addClass('hide');

    $(document).trigger('sell_form_reset');

    // Set global_is_clear_local_storage to true to clear local storage
    global_is_clear_local_storage = true;
    saveFormDataToLocalStorage();
}

//POS: when applying payment to old dues, recalc totals immediately
$(document).on('change', '#apply_payment_to_old_dues', function () {
    if (typeof calculate_balance_due === 'function') {
        calculate_balance_due();
    }
});

//POS: when manual advance deduction toggled, recalc totals
$(document).on('change', '#deduct_from_advance', function () {
    if (typeof calculate_balance_due === 'function') {
        calculate_balance_due();
    }
});

function set_default_customer() {
    var default_customer_id = $('#default_customer_id').val();
    var default_customer_name = $('#default_customer_name').val();
    // Fallback label to ensure the Select2 shows a readable default option
    if (!default_customer_name || default_customer_name.trim() === '') {
        default_customer_name = 'Walk-In Customer';
    }
    var default_customer_balance = $('#default_customer_balance').val();
    var default_customer_address = $('#default_customer_address').val();
    var exists = default_customer_id ? $('select#customer_id option[value=' + default_customer_id + ']').length : 0;
    if (exists == 0 && default_customer_id) {
        $('select#customer_id').append(
            $('<option>', { value: default_customer_id, text: default_customer_name })
        );
    }
    $('#advance_balance_text').text(__currency_trans_from_en(default_customer_balance), true);
    $('#advance_balance').val(default_customer_balance);

    if (parseFloat(default_customer_balance) > 0) {
        $('#advance_deduct_checkbox_wrapper').removeClass('hide');
    } else {
        $('#advance_deduct_checkbox_wrapper').addClass('hide');
        $('#deduct_from_advance').prop('checked', false);
    }
    $('#shipping_address_modal').val(default_customer_address);
    if (default_customer_address) {
        $('#shipping_address').val(default_customer_address);
    }
    $('select#customer_id')
        .val(default_customer_id)
        .trigger('change');

    if ($('#default_selling_price_group').length) {
        $('#price_group').val($('#default_selling_price_group').val());
        $('#price_group').change();
    }

    //initialize tags input (tagify)
    if ($("textarea#repair_defects").length > 0 && !customer_set) {
        let suggestions = [];
        if ($("input#pos_repair_defects_suggestion").length > 0 && $("input#pos_repair_defects_suggestion").val().length > 2) {
            suggestions = JSON.parse($("input#pos_repair_defects_suggestion").val());
        }
        let repair_defects = document.querySelector('textarea#repair_defects');
        tagify_repair_defects = new Tagify(repair_defects, {
            whitelist: suggestions,
            maxTags: 100,
            dropdown: {
                maxItems: 100,           // <- mixumum allowed rendered suggestions
                classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                enabled: 0,             // <- show suggestions on focus
                closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
            }
        });
    }

    customer_set = true;
}

//Set the location and initialize printer
function set_location() {
    if ($('select#select_location_id').length == 1) {
        $('input#location_id').val($('select#select_location_id').val());
        $('input#location_id').data(
            'receipt_printer_type',
            $('select#select_location_id')
                .find(':selected')
                .data('receipt_printer_type')
        );
        $('input#location_id').data(
            'default_payment_accounts',
            $('select#select_location_id')
                .find(':selected')
                .data('default_payment_accounts')
        );

        $('input#location_id').attr(
            'data-default_price_group',
            $('select#select_location_id')
                .find(':selected')
                .data('default_price_group')
        );
    }

    if ($('input#location_id').val()) {
        $('input#search_product')
            .prop('disabled', false);
        if (!$('#__is_mobile').length) {
            $('input#search_product').focus();
        }
    } else {
        $('input#search_product').prop('disabled', true);
    }

    initialize_printer();
}

function initialize_printer() {
    if ($('input#location_id').data('receipt_printer_type') == 'printer') {
        initializeSocket();
    }
}

$('body').on('click', 'label', function (e) {
    var field_id = $(this).attr('for');
    if (field_id) {
        if ($('#' + field_id).hasClass('select2')) {
            $('#' + field_id).select2('open');
            return false;
        }
    }
});

$('body').on('focus', 'select', function (e) {
    var field_id = $(this).attr('id');
    if (field_id) {
        if ($('#' + field_id).hasClass('select2')) {
            $('#' + field_id).select2('open');
            return false;
        }
    }
});

function round_row_to_iraqi_dinnar(row) {
    if (iraqi_selling_price_adjustment) {
        var element = row.find('input.pos_unit_price_inc_tax');
        var unit_price = round_to_iraqi_dinnar(__read_number(element));
        __write_number(element, unit_price);
        element.change();
    }
}

function pos_print(receipt) {
    function fallback_browser_print() {
        if (receipt.html_content != '') {
            var title = document.title;
            if (typeof receipt.print_title != 'undefined') {
                document.title = receipt.print_title;
            }
            $('#receipt_section').last().html(receipt.html_content);
            __currency_convert_recursively($('#receipt_section').last());
            __print_receipt('receipt_section');
            setTimeout(function () {
                document.title = title;
            }, 1200);
        } else {
            toastr.error(LANG.unable_to_connect_to_qz);
        }
    }

    //If printer type then connect with websocket
    if (receipt.print_type == 'printer') {
        var content = receipt;
        content.type = 'print-receipt';

        //Check if ready or not, then print.
        if (socket != null && socket.readyState == 1) {
            socket.send(JSON.stringify(content));
        } else {
            initializeSocket();
            setTimeout(function () {
                if (socket != null && socket.readyState == 1) {
                    socket.send(JSON.stringify(content));
                } else {
                    fallback_browser_print();
                }
            }, 700);
        }

    } else if (receipt.html_content != '') {
        fallback_browser_print();
    }
}

function calculate_discounted_unit_price(row) {
    // Determine base unit price (pre-tax)
    var tax_details = getPosTaxDetails(row.find('select.tax_id'));

    var base_unit_price = row.data('modal-base-unit-price');
    if (typeof base_unit_price === 'undefined' || isNaN(base_unit_price) || base_unit_price === 0) {
        base_unit_price = __read_number(row.find('input.pos_unit_price'));
        if (base_unit_price === 0) {
            var inc_tax_price = __read_number(row.find('input.pos_unit_price_inc_tax'));
            base_unit_price = posRemoveTax(inc_tax_price, tax_details);
        }
    }

    var row_discount_type = (row.find('.row_discount_type').length ? row.find('.row_discount_type').val() : null);
    if (!row_discount_type) {
        row_discount_type = row.data('modal-discount-type') || 'fixed';
    }
    var row_discount_amount = (row.find('.row_discount_amount').length ? __read_number(row.find('.row_discount_amount')) : null);
    if (row_discount_amount === null || isNaN(row_discount_amount)) {
        row_discount_amount = row.data('modal-discount-amount') || 0;
    }
    var row_discounted_unit_price = base_unit_price;
    if (row_discount_amount) {
        if (row_discount_type == 'fixed') {
            row_discounted_unit_price = base_unit_price - row_discount_amount;
        } else {
            row_discounted_unit_price = __substract_percent(base_unit_price, row_discount_amount);
        }
    }

    return row_discounted_unit_price;
}

function get_unit_price_from_discounted_unit_price(row, discounted_unit_price) {
    var this_unit_price = discounted_unit_price;
    var row_discount_type = (row.find('.row_discount_type').length ? row.find('.row_discount_type').val() : null);
    if (!row_discount_type) {
        row_discount_type = row.data('modal-discount-type') || 'fixed';
    }
    var row_discount_amount = (row.find('.row_discount_amount').length ? __read_number(row.find('.row_discount_amount')) : null);
    if (row_discount_amount === null || isNaN(row_discount_amount)) {
        row_discount_amount = row.data('modal-discount-amount') || 0;
    }
    if (row_discount_amount) {
        if (row_discount_type == 'fixed') {
            this_unit_price = discounted_unit_price + row_discount_amount;
        } else {
            this_unit_price = __get_principle(discounted_unit_price, row_discount_amount, true);
        }
    }

    return this_unit_price;
}

//Update quantity if line subtotal changes
$('table#pos_table tbody').on('change', 'input.pos_line_total', function () {

    var subtotal = __read_number($(this));
    var tr = $(this).parents('tr');
    var quantity_element = tr.find('input.pos_quantity');
    var unit_price_inc_tax = __read_number(tr.find('input.pos_unit_price_inc_tax'));
    var quantity = subtotal / unit_price_inc_tax;
    __write_number(quantity_element, quantity);

    __write_number($(this), subtotal, false);


    if (sell_form_validator) {
        sell_form_validator.element(quantity_element);
    }
    if (pos_form_validator) {
        pos_form_validator.element(quantity_element);
    }
    tr.find('span.pos_line_total_text').text(__currency_trans_from_en(subtotal, true));

    pos_total_row();
});

$('div#product_list_body').on('scroll', function () {


    if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
        var page = parseInt($('#suggestion_page').val());
        page += 1;
        $('#suggestion_page').val(page);
        var location_id = $('input#location_id').val();
        var category_id = global_p_category_id;
        var brand_id = global_brand_id;

        var is_enabled_stock = null;
        if ($("#is_enabled_stock").length) {
            is_enabled_stock = $("#is_enabled_stock").val();
        }

        var device_model_id = null;
        if ($("#repair_model_id").length) {
            device_model_id = $("#repair_model_id").val();
        }

        get_product_suggestion_list(category_id, brand_id, location_id, null, is_enabled_stock, device_model_id);
    }
});

$(document).on('ifChecked', '#is_recurring', function () {
    $('#recurringInvoiceModal').modal('show');
});

$(document).on('shown.bs.modal', '#recurringInvoiceModal', function () {
    $('input#recur_interval').focus();
});

$(document).on('click', '#select_all_service_staff', function () {
    var val = $('#res_waiter_id').val();
    $('#pos_table tbody')
        .find('select.order_line_service_staff')
        .each(function () {
            $(this)
                .val(val)
                .change();
        });
});

$(document).on('click', '.print-invoice-link', function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('href') + "?check_location=true",
        dataType: 'json',
        success: function (result) {
            if (result.success == 1) {
                //Check if enabled or not
                if (result.receipt.is_enabled) {
                    pos_print(result.receipt);
                }
            } else {
                toastr.error(result.msg);
            }

        },
    });
});

function getCustomerRewardPoints() {
    if ($('#reward_point_enabled').length <= 0) {
        return false;
    }
    var is_edit = $('form#edit_sell_form').length ||
        $('form#edit_pos_sell_form').length ? true : false;
    if (is_edit && !customer_set) {
        return false;
    }

    var customer_id = $('#customer_id').val();

    $.ajax({
        method: 'POST',
        url: '/sells/pos/get-reward-details',
        data: {
            customer_id: customer_id
        },
        dataType: 'json',
        success: function (result) {
            $('#available_rp').text(result.points);
            $('#rp_redeemed_modal').data('max_points', result.points);
            updateRedeemedAmount();
            $('#rp_redeemed_amount').change()
        },
    });
}

function updateRedeemedAmount(argument) {
    var points = $('#rp_redeemed_modal').val().trim();
    points = points == '' ? 0 : parseInt(points);
    var amount_per_unit_point = parseFloat($('#rp_redeemed_modal').data('amount_per_unit_point'));
    var redeemed_amount = points * amount_per_unit_point;
    $('#rp_redeemed_amount_text').text(__currency_trans_from_en(redeemed_amount, true));
    $('#rp_redeemed').val(points);
    $('#rp_redeemed_amount').val(redeemed_amount);
}

$(document).on('change', 'select#customer_id', function () {
    var default_customer_id = $('#default_customer_id').val();
    if ($(this).val() == default_customer_id) {
        //Disable reward points for walkin customers
        if ($('#rp_redeemed_modal').length) {
            $('#rp_redeemed_modal').val('');
            $('#rp_redeemed_modal').change();
            $('#rp_redeemed_modal').attr('disabled', true);
            $('#available_rp').text('');
            updateRedeemedAmount();
            pos_total_row();
        }
    } else {
        if ($('#rp_redeemed_modal').length) {
            $('#rp_redeemed_modal').removeAttr('disabled');
        }
        getCustomerRewardPoints();
    }

    get_sales_orders();
});

$(document).on('change', '#rp_redeemed_modal', function () {
    var points = $(this).val().trim();
    points = points == '' ? 0 : parseInt(points);
    var amount_per_unit_point = parseFloat($(this).data('amount_per_unit_point'));
    var redeemed_amount = points * amount_per_unit_point;
    $('#rp_redeemed_amount_text').text(__currency_trans_from_en(redeemed_amount, true));
    var reward_validation = isValidatRewardPoint();
    if (!reward_validation['is_valid']) {
        toastr.error(reward_validation['msg']);
        $('#rp_redeemed_modal').select();
    }
});

$(document).on('change', '.direct_sell_rp_input', function () {
    updateRedeemedAmount();
    pos_total_row();
});

function isValidatRewardPoint() {
    var element = $('#rp_redeemed_modal');
    var points = element.val().trim();
    points = points == '' ? 0 : parseInt(points);

    var max_points = parseInt(element.data('max_points'));
    var is_valid = true;
    var msg = '';

    if (points == 0) {
        return {
            is_valid: is_valid,
            msg: msg
        }
    }

    var rp_name = $('input#rp_name').val();
    if (points > max_points) {
        is_valid = false;
        msg = __translate('max_rp_reached_error', { max_points: max_points, rp_name: rp_name });
    }

    var min_order_total_required = parseFloat(element.data('min_order_total'));

    var order_total = __read_number($('#final_total_input'));

    if (order_total < min_order_total_required) {
        is_valid = false;
        msg = __translate('min_order_total_error', { min_order: __currency_trans_from_en(min_order_total_required, true), rp_name: rp_name });
    }

    var output = {
        is_valid: is_valid,
        msg: msg,
    }

    return output;
}

function adjustComboQty(tr) {
    if (tr.find('input.product_type').val() == 'combo') {
        var qty = __read_number(tr.find('input.pos_quantity'));
        var multiplier = __getUnitMultiplier(tr);

        tr.find('input.combo_product_qty').each(function () {
            $(this).val($(this).data('unit_quantity') * qty * multiplier);
        });
    }
}

$(document).on('change', '#types_of_service_id', function () {
    var types_of_service_id = $(this).val();
    var location_id = $('#location_id').val();

    if (types_of_service_id) {
        $.ajax({
            method: 'POST',
            url: '/sells/pos/get-types-of-service-details',
            data: {
                types_of_service_id: types_of_service_id,
                location_id: location_id
            },
            dataType: 'json',
            success: function (result) {
                //reset form if price group is changed
                var prev_price_group = $('#types_of_service_price_group').val();
                if (result.price_group_id) {
                    $('#types_of_service_price_group').val(result.price_group_id);
                    $('#price_group_text').removeClass('hide');
                    $('#price_group_text span').text(result.price_group_name);
                } else {
                    $('#types_of_service_price_group').val('');
                    $('#price_group_text').addClass('hide');
                    $('#price_group_text span').text('');
                }
                $('#types_of_service_id').val(types_of_service_id);
                $('.types_of_service_modal').html(result.modal_html);

                if (prev_price_group != result.price_group_id) {
                    if ($('form#edit_pos_sell_form').length > 0) {
                        $('table#pos_table tbody').html('');
                        pos_total_row();
                    } else {
                        reset_pos_form();
                    }
                } else {
                    pos_total_row();
                }

                $('.types_of_service_modal').modal('show');
            },
        });
    } else {
        $('.types_of_service_modal').html('');
        $('#types_of_service_price_group').val('');
        $('#price_group_text').addClass('hide');
        $('#price_group_text span').text('');
        $('#packing_charge_text').text('');
        if ($('form#edit_pos_sell_form').length > 0) {
            $('table#pos_table tbody').html('');
            pos_total_row();
        } else {
            reset_pos_form();
        }
    }
});

$(document).on('change', 'input#packing_charge, #additional_expense_value_1, #additional_expense_value_2, \
        #additional_expense_value_3, #additional_expense_value_4', function () {
    pos_total_row();
});

$(document).on('click', '.service_modal_btn', function (e) {
    if ($('#types_of_service_id').val()) {
        $('.types_of_service_modal').modal('show');
    }
});

$(document).on('focus', '.payment_types_dropdown', function () {
    $(this).data('prev_payment_type', $(this).val());
});

$(document).on('change', '.payment_types_dropdown', function (e) {
    if ($(this).data('skip_cheque_walkin_check') === true) {
        return;
    }

    var default_accounts = $('select#select_location_id').length ?
        $('select#select_location_id')
            .find(':selected')
            .data('default_payment_accounts') : $('#location_id').data('default_payment_accounts');
    var payment_type = $(this).val();
    var payment_row = $(this).closest('.payment_row');

    //Walk-in customers are not allowed to pay by cheque
    if (payment_type === 'cheque') {
        var default_customer_id = $('#default_customer_id').val();
        var current_customer_id = $('#customer_id').val();

        if (default_customer_id && current_customer_id == default_customer_id) {
            var prev_payment_type = $(this).data('prev_payment_type') || 'cash';
            $(this).data('skip_cheque_walkin_check', true);
            $(this).val(prev_payment_type).trigger('change');
            $(this).data('skip_cheque_walkin_check', false);

            if (typeof toastr !== 'undefined') {
                toastr.error(LANG.cheque_payment_requires_registered_customer);
            } else {
                alert(LANG.cheque_payment_requires_registered_customer);
            }

            //Open quick add customer modal
            $('#customer_id').select2('close');
            $('.contact_modal').find('input#name').val('');
            $('.contact_modal')
                .find('select#contact_type')
                .val('customer')
                .closest('div.contact_type_div')
                .addClass('hide');
            $('.contact_modal').modal('show');

            return;
        }
    }

    if (payment_type && payment_type != 'advance') {
        var default_account = default_accounts && default_accounts[payment_type]['account'] ?
            default_accounts[payment_type]['account'] : '';
        var row_index = payment_row.find('.payment_row_index').val();

        var account_dropdown = payment_row.find('select#account_' + row_index);
        if (account_dropdown.length && default_accounts) {
            account_dropdown.val(default_account);
            account_dropdown.change();
        }
    }

    //Validate max amount and disable account if advance 
    amount_element = payment_row.find('.payment-amount');
    account_dropdown = payment_row.find('.account-dropdown');
    if (payment_type == 'advance') {
        max_value = $('#advance_balance').val();
        msg = $('#advance_balance').data('error-msg');
        amount_element.rules('add', {
            'max-value': max_value,
            messages: {
                'max-value': msg,
            },
        });
        if (account_dropdown) {
            account_dropdown.prop('disabled', true);
            account_dropdown.closest('.form-group').addClass('hide');
        }
    } else {
        amount_element.rules("remove", "max-value");
        if (account_dropdown) {
            account_dropdown.prop('disabled', false);
            account_dropdown.closest('.form-group').removeClass('hide');
        }
    }
});

$(document).on('show.bs.modal', '#recent_transactions_modal', function () {
    get_recent_transactions('final', $('div#tab_final'));
});
$(document).on('shown.bs.tab', 'a[href="#tab_quotation"]', function () {
    get_recent_transactions('quotation', $('div#tab_quotation'));
});
$(document).on('shown.bs.tab', 'a[href="#tab_draft"]', function () {
    get_recent_transactions('draft', $('div#tab_draft'));
});

function disable_pos_form_actions() {
    if (!window.navigator.onLine) {
        return false;
    }

    $('div.pos-processing').show();
    $('#pos-save').attr('disabled', 'true');
    $('div.pos-form-actions').find('button').attr('disabled', 'true');
}

function enable_pos_form_actions() {
    $('div.pos-processing').hide();
    $('#pos-save').removeAttr('disabled');
    $('div.pos-form-actions').find('button').removeAttr('disabled');

    // Always clear express lock when actions are re-enabled
    window.__express_processing = false;
    try { $('button.pos-express-finalize').prop('disabled', false); } catch (e) { }
}

$(document).on('change', '#recur_interval_type', function () {
    if ($(this).val() == 'months') {
        $('.subscription_repeat_on_div').removeClass('hide');
    } else {
        $('.subscription_repeat_on_div').addClass('hide');
    }
});

function validate_discount_field() {
    discount_element = $('#discount_amount_modal');
    discount_type_element = $('#discount_type_modal');

    if ($('#add_sell_form').length || $('#edit_sell_form').length) {
        discount_element = $('#discount_amount');
        discount_type_element = $('#discount_type');
    }
    var max_value = parseFloat(discount_element.data('max-discount'));
    if (discount_element.val() != '' && !isNaN(max_value)) {
        if (discount_type_element.val() == 'fixed') {
            var subtotal = get_subtotal();
            //get max discount amount
            max_value = __calculate_amount('percentage', max_value, subtotal)
        }

        discount_element.rules('add', {
            'max-value': max_value,
            messages: {
                'max-value': discount_element.data('max-discount-error_msg'),
            },
        });
    } else {
        discount_element.rules("remove", "max-value");
    }
    discount_element.trigger('change');
}

$(document).on('change', '#discount_type_modal, #discount_type', function () {
    validate_discount_field();
});

// Write row data attributes back into real inputs before submit, so invoice has values
function syncProductRowsToInputs() {
    $('table#pos_table tbody tr.product_row').each(function () {
        var $row = $(this);

        // Base unit price -> pos_unit_price if available, else rebuild inc tax from base and tax
        var tax_details = getPosTaxDetails($row.find('select.tax_id'));

        var base_price = $row.data('modal-base-unit-price');
        if (typeof base_price === 'undefined' || isNaN(base_price)) {
            // Fallback derive base from existing inputs
            base_price = __read_number($row.find('input.pos_unit_price'));
            if (!base_price || base_price === 0) {
                var inc_tax_existing = __read_number($row.find('input.pos_unit_price_inc_tax'));
                base_price = posRemoveTax(inc_tax_existing, tax_details);
            }
        }

        // Ensure pos_unit_price input exists and has base (pre-discount, pre-tax)
        if ($row.find('input.pos_unit_price').length) {
            __write_number($row.find('input.pos_unit_price'), base_price);
        } else {
            var rowIndex = $row.data('row_index');
            if (typeof rowIndex !== 'undefined') {
                var nameBase = 'products[' + rowIndex + '][unit_price]';
                $('<input type="hidden" class="pos_unit_price input_number"/>')
                    .attr('name', nameBase)
                    .appendTo($row.find('td').eq(1));
                __write_number($row.find('input.pos_unit_price'), base_price);
            }
        }

        // Compute discounted base and inc-tax for submission
        // Use the same discount consumption logic as row calc
        var dtype = ($row.find('select.row_discount_type').length ? $row.find('select.row_discount_type').val() : ($row.data('modal-discount-type') || 'fixed'));
        var damount = ($row.find('input.row_discount_amount').length ? __read_number($row.find('input.row_discount_amount')) : ($row.data('modal-discount-amount') || 0));
        var discounted_base = base_price;
        if (damount) {
            discounted_base = (dtype === 'fixed') ? (base_price - damount) : __substract_percent(base_price, damount);
        }
        var inc_tax = posAddTax(discounted_base, tax_details);

        // Ensure pos_unit_price_inc_tax exists and holds discounted inc-tax
        if ($row.find('input.pos_unit_price_inc_tax').length) {
            __write_number($row.find('input.pos_unit_price_inc_tax'), inc_tax);
        } else {
            var rowIndex2 = $row.data('row_index');
            if (typeof rowIndex2 !== 'undefined') {
                var nameInc = 'products[' + rowIndex2 + '][unit_price_inc_tax]';
                $('<input type="hidden" class="pos_unit_price_inc_tax input_number"/>')
                    .attr('name', nameInc)
                    .appendTo($row.find('td').eq(2));
                __write_number($row.find('input.pos_unit_price_inc_tax'), inc_tax);
            }
        }

        // Discount type/amount -> hidden/visible inputs if they exist
        var dtypeData = $row.data('modal-discount-type');
        var damountData = $row.data('modal-discount-amount');
        var rowIndex3 = $row.data('row_index');
        if ($row.find('select.row_discount_type').length) {
            if (typeof dtypeData !== 'undefined') { $row.find('select.row_discount_type').val(dtypeData); }
        } else if (typeof rowIndex3 !== 'undefined' && typeof dtypeData !== 'undefined') {
            var nameDt = 'products[' + rowIndex3 + '][line_discount_type]';
            $('<input type="hidden" class="row_discount_type"/>')
                .attr('name', nameDt)
                .val(dtypeData)
                .appendTo($row);
        }
        if ($row.find('input.row_discount_amount').length) {
            if (typeof damountData !== 'undefined') { __write_number($row.find('input.row_discount_amount'), damountData); }
        } else if (typeof rowIndex3 !== 'undefined' && typeof damountData !== 'undefined') {
            var nameDa = 'products[' + rowIndex3 + '][line_discount_amount]';
            $('<input type="hidden" class="row_discount_amount input_number"/>')
                .attr('name', nameDa)
                .appendTo($row);
            __write_number($row.find('input.row_discount_amount'), damountData);
        }
    });
}

function update_shipping_address(data) {
    if ($('#shipping_address_div').length) {
        var shipping_address = '';
        if (data.supplier_business_name) {
            shipping_address += data.supplier_business_name;
        }
        if (data.name) {
            shipping_address += ',<br>' + data.name;
        }
        if (data.text) {
            shipping_address += ',<br>' + data.text;
        }
        shipping_address += ',<br>' + data.shipping_address;
        $('#shipping_address_div').html(shipping_address);
    }
    if ($('#billing_address_div').length) {
        var address = [];
        if (data.supplier_business_name) {
            address.push(data.supplier_business_name);
        }
        if (data.name) {
            address.push('<br>' + data.name);
        }
        if (data.text) {
            address.push('<br>' + data.text);
        }
        if (data.address_line_1) {
            address.push('<br>' + data.address_line_1);
        }
        if (data.address_line_2) {
            address.push('<br>' + data.address_line_2);
        }
        if (data.city) {
            address.push('<br>' + data.city);
        }
        if (data.state) {
            address.push(data.state);
        }
        if (data.country) {
            address.push(data.country);
        }
        if (data.zip_code) {
            address.push('<br>' + data.zip_code);
        }
        var billing_address = address.join(', ');
        $('#billing_address_div').html(billing_address);
    }

    if ($('#shipping_custom_field_1').length) {
        let shipping_custom_field_1 = data.shipping_custom_field_details != null ? data.shipping_custom_field_details.shipping_custom_field_1 : '';
        $('#shipping_custom_field_1').val(shipping_custom_field_1);
    }

    if ($('#shipping_custom_field_2').length) {
        let shipping_custom_field_2 = data.shipping_custom_field_details != null ? data.shipping_custom_field_details.shipping_custom_field_2 : '';
        $('#shipping_custom_field_2').val(shipping_custom_field_2);
    }

    if ($('#shipping_custom_field_3').length) {
        let shipping_custom_field_3 = data.shipping_custom_field_details != null ? data.shipping_custom_field_details.shipping_custom_field_3 : '';
        $('#shipping_custom_field_3').val(shipping_custom_field_3);
    }

    if ($('#shipping_custom_field_4').length) {
        let shipping_custom_field_4 = data.shipping_custom_field_details != null ? data.shipping_custom_field_details.shipping_custom_field_4 : '';
        $('#shipping_custom_field_4').val(shipping_custom_field_4);
    }

    if ($('#shipping_custom_field_5').length) {
        let shipping_custom_field_5 = data.shipping_custom_field_details != null ? data.shipping_custom_field_details.shipping_custom_field_5 : '';
        $('#shipping_custom_field_5').val(shipping_custom_field_5);
    }

    //update export fields
    if (data.is_export) {
        $('#is_export').prop('checked', true);
        $('div.export_div').show();
        if ($('#export_custom_field_1').length) {
            $('#export_custom_field_1').val(data.export_custom_field_1);
        }
        if ($('#export_custom_field_2').length) {
            $('#export_custom_field_2').val(data.export_custom_field_2);
        }
        if ($('#export_custom_field_3').length) {
            $('#export_custom_field_3').val(data.export_custom_field_3);
        }
        if ($('#export_custom_field_4').length) {
            $('#export_custom_field_4').val(data.export_custom_field_4);
        }
        if ($('#export_custom_field_5').length) {
            $('#export_custom_field_5').val(data.export_custom_field_5);
        }
        if ($('#export_custom_field_6').length) {
            $('#export_custom_field_6').val(data.export_custom_field_6);
        }
    } else {
        $('#export_custom_field_1, #export_custom_field_2, #export_custom_field_3, #export_custom_field_4, #export_custom_field_5, #export_custom_field_6').val('');
        $('#is_export').prop('checked', false);
        $('div.export_div').hide();
    }

    $('#shipping_address_modal').val(data.shipping_address);
    $('#shipping_address').val(data.shipping_address);
}

function get_sales_orders() {
    if ($('#sales_order_ids').length) {
        if ($('#sales_order_ids').hasClass('not_loaded')) {
            $('#sales_order_ids').removeClass('not_loaded');
            return false;
        }
        var customer_id = $('select#customer_id').val();
        var location_id = $('input#location_id').val();
        $.ajax({
            url: '/get-sales-orders/' + customer_id + '?location_id=' + location_id,
            dataType: 'json',
            success: function (data) {
                $('#sales_order_ids').select2('destroy').empty().select2({ data: data });
                $('table#pos_table tbody').find('tr').each(function () {
                    if (typeof ($(this).data('so_id')) !== 'undefined') {
                        $(this).remove();
                    }
                });
                pos_total_row();
            },
        });
    }
}

$("#sales_order_ids").on("select2:select", function (e) {
    var sales_order_id = e.params.data.id;
    var product_row = $('input#product_row_count').val();
    var location_id = $('input#location_id').val();
    $.ajax({
        method: 'GET',
        url: '/get-sales-order-lines',
        async: false,
        data: {
            product_row: product_row,
            sales_order_id: sales_order_id
        },
        dataType: 'json',
        success: function (result) {
            if (result.html) {
                var html = result.html;
                $(html).find('tr').each(function () {
                    $('table#pos_table tbody')
                        .append($(this))
                        .find('input.pos_quantity');

                    var this_row = $('table#pos_table tbody')
                        .find('tr')
                        .last();
                    pos_each_row(this_row);

                    product_row = parseInt(product_row) + 1;

                    //For initial discount if present
                    var line_total = __read_number(this_row.find('input.pos_line_total'));
                    this_row.find('span.pos_line_total_text').text(line_total);

                    //Check if multipler is present then multiply it when a new row is added.
                    if (__getUnitMultiplier(this_row) > 1) {
                        this_row.find('select.sub_unit').trigger('change');
                    }

                    round_row_to_iraqi_dinnar(this_row);
                    __currency_convert_recursively(this_row);
                });

                set_so_values(result.sales_order);

                //increment row count
                $('input#product_row_count').val(product_row);

                pos_total_row();

            } else {
                toastr.error(result.msg);
                $('input#search_product')
                    .focus()
                    .select();
            }
        },
    });
});

function set_so_values(so) {
    $('textarea[name="sale_note"]').val(so.additional_notes);
    if ($('#shipping_details').is(':visible')) {
        $('#shipping_details').val(so.shipping_details);
    }
    $('#shipping_address').val(so.shipping_address);
    $('#delivered_to').val(so.delivered_to);
    $('#shipping_charges').val(__number_f(so.shipping_charges));
    $('#shipping_status').val(so.shipping_status);
    if ($('#shipping_custom_field_1').length) {
        $('#shipping_custom_field_1').val(so.shipping_custom_field_1);
    }
    if ($('#shipping_custom_field_2').length) {
        $('#shipping_custom_field_2').val(so.shipping_custom_field_2);
    }
    if ($('#shipping_custom_field_3').length) {
        $('#shipping_custom_field_3').val(so.shipping_custom_field_3);
    }
    if ($('#shipping_custom_field_4').length) {
        $('#shipping_custom_field_4').val(so.shipping_custom_field_4);
    }
    if ($('#shipping_custom_field_5').length) {
        $('#shipping_custom_field_5').val(so.shipping_custom_field_5);
    }
}

$("#sales_order_ids").on("select2:unselect", function (e) {
    var sales_order_id = e.params.data.id;
    $('table#pos_table tbody').find('tr').each(function () {
        if (typeof ($(this).data('so_id')) !== 'undefined'
            && $(this).data('so_id') == sales_order_id) {
            $(this).remove();
            pos_total_row();
        }
    });
});

$(document).on('click', '#add_expense', function () {
    $.ajax({
        url: '/expenses/create',
        data: {
            location_id: $('#select_location_id').val()
        },
        dataType: 'html',
        success: function (result) {
            $('#expense_modal').html(result);
            $('#expense_modal').modal('show');
        },
    });
});

$(document).on('shown.bs.modal', '#expense_modal', function () {
    $('#expense_transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });
    $('#expense_modal .paid_on').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });
    $(this).find('.select2').select2();
    $('#add_expense_modal_form').validate();
});

$(document).on('hidden.bs.modal', '#expense_modal', function () {
    $(this).html('');
});

$(document).on('submit', 'form#add_expense_modal_form', function (e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function (result) {
            if (result.success == true) {
                $('#expense_modal').modal('hide');
                toastr.success(result.msg);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

function get_contact_due(id) {
    $.ajax({
        method: 'get',
        url: /get-contact-due/ + id,
        dataType: 'text',
        success: function (result) {
            if (result != '') {
                $('.contact_due_text').find('span').text(result);
                $('.contact_due_text').removeClass('hide');
            } else {
                $('.contact_due_text').find('span').text('');
                $('.contact_due_text').addClass('hide');
            }

            if (typeof calculate_balance_due === 'function') {
                calculate_balance_due();
            }
        },
    });
}

function submitQuickContactForm(form) {
    var data = $(form).serialize();
    $.ajax({
        method: 'POST',
        url: $(form).attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function (xhr) {
            __disable_submit_button($(form).find('button[type="submit"]'));
        },
        success: function (result) {
            if (result.success == true) {
                var name = result.data.name;

                if (result.data.supplier_business_name) {
                    name += result.data.supplier_business_name;
                }

                $('select#customer_id').append(
                    $('<option>', { value: result.data.id, text: name })
                );
                $('select#customer_id')
                    .val(result.data.id)
                    .trigger('change');
                $('div.contact_modal').modal('hide');
                update_shipping_address(result.data)
                toastr.success(result.msg);
            } else {
                toastr.error(result.msg);
            }
        },
    });
}

$(document).on('click', '#send_for_sell_return', function (e) {
    var invoice_no = $('#send_for_sell_return_invoice_no').val();

    if (invoice_no) {
        $.ajax({
            method: 'get',
            url: /validate-invoice-to-return/ + encodeURI(invoice_no),
            dataType: 'json',
            success: function (result) {
                if (result.success == true) {
                    window.location = result.redirect_url;
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    }
})

$(document).on('click', '#send_for_sercice_staff_replacement', function (e) {
    var invoice_no = $('#send_for_sell_service_staff_invoice_no').val();

    if (invoice_no) {
        $.ajax({
            method: 'get',
            url: /validate-invoice-to-service-staff-replacement/ + encodeURI(invoice_no),
            dataType: 'json',
            success: function (result) {
                if (result.success == true) {
                    $('#service_staff_replacement').popover('hide');
                    $('#service_staff_modal').html(result.msg);
                    $('#service_staff_modal').modal('show');

                } else {
                    toastr.error(result.msg);
                }
            },
        });
    }
});

$(document).on('shown.bs.modal', '#service_staff_modal', function () {
    $('#change_service_staff').validate();
});


$(document).on('submit', 'form#change_service_staff', function (e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function (result) {
            if (result.success == true) {
                $('#service_staff_modal').modal('hide');
                toastr.success(result.msg);
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('ifChanged', 'input[name="search_fields[]"]', function (event) {
    var search_fields = [];
    $('input[name="search_fields[]"]:checked').each(function () {
        search_fields.push($(this).val());
    });

    localStorage.setItem('pos_search_fields', search_fields);
});

function set_search_fields() {
    if ($('input[name="search_fields[]"]').length == 0) {
        return false;
    }

    var pos_search_fields = localStorage.getItem('pos_search_fields');

    if (pos_search_fields === null) {
        pos_search_fields = ['name', 'sku', 'lot'];
    }

    $('input[name="search_fields[]"]').each(function () {
        if (pos_search_fields.indexOf($(this).val()) >= 0) {
            $(this).iCheck('check');
        } else {
            $(this).iCheck('uncheck');
        }
    });
}

$(document).on('click', '#show_service_staff_availability', function () {
    loadServiceStaffAvailability();
})
$(document).on('click', '#refresh_service_staff_availability_status', function () {
    loadServiceStaffAvailability(false);
})
$(document).on('click', 'button.pause_resume_timer', function (e) {
    $('.view_modal').find('.overlay').removeClass('hide');
    $.ajax({
        method: 'get',
        url: $(this).attr('data-href'),
        dataType: 'json',
        success: function (result) {
            loadServiceStaffAvailability(false);
        },
    });
})

$(document).on('click', '.mark_as_available', function (e) {
    e.preventDefault()
    $('.view_modal').find('.overlay').removeClass('hide');
    $.ajax({
        method: 'get',
        url: $(this).attr('href'),
        dataType: 'json',
        success: function (result) {
            loadServiceStaffAvailability(false);
        },
    });
})
var service_staff_availability_interval = null;

function loadServiceStaffAvailability(show = true) {
    var location_id = $('[name="location_id"]').val();
    $.ajax({
        method: 'get',
        url: $('#show_service_staff_availability').attr('data-href'),
        dataType: 'html',
        data: { location_id: location_id },
        success: function (result) {
            $('.view_modal').html(result);
            if (show) {
                $('.view_modal').modal('show')

                //auto refresh service staff availabilty if modal is open
                service_staff_availability_interval = setInterval(function () {
                    loadServiceStaffAvailability(false);
                }, 60000);
            }
        },
    });
}

$(document).on('hidden.bs.modal', '.view_modal', function () {
    if (service_staff_availability_interval !== null) {
        clearInterval(service_staff_availability_interval);
    }
    service_staff_availability_interval = null;
});


$(document).on('change', '#res_waiter_id', function (e) {
    var is_enable = $(this).find('option:selected').data('is_enable');

    if (is_enable) {
        swal({
            text: LANG.enter_pin_here,
            buttons: true,
            dangerMode: true,
            content: {
                element: "input",
                attributes: {
                    placeholder: LANG.enter_pin_here,
                    type: "password",
                },
            },
        })
            .then((inputValue) => {
                if (inputValue !== null) {
                    $.ajax({
                        method: 'get',
                        url: '/modules/data/check-staff-pin',
                        dataType: 'json',
                        data: {
                            service_staff_pin: inputValue,
                            user_id: $("#res_waiter_id").val(),
                        },
                        success: (result) => {

                            if (result == false) {
                                toastr.error(LANG.authentication_failed);
                                $("#res_waiter_id").val('');
                            } else {
                                // AJAX request succeeded, resolve
                                toastr.success(LANG.authentication_successfull);
                            }
                        },
                    });
                } else {
                    // Handle the "Cancel" action
                    $("#res_waiter_id").val('');
                }
            });

    }
})

// update serial number of product item
function update_serial_no() {
    $('.product_row').each(function (index) {
        // Add the serial number to the first <td> of each row (index + 1 to start from 1)
        if ($(this).find('td:first').hasClass('serial_no')) {
            $(this).find('td:first').text(index + 1);
        }
    });
}


/**
 * Saves the serialized form data from #add_pos_sell_form into LocalStorage.
 */
function saveFormDataToLocalStorage() {


    // Check if global_is_clear_local_storage is true and reset it to false if so
    if (global_is_clear_local_storage) {
        localStorage.setItem("pos_form_data_array", JSON.stringify([]));
        global_is_clear_local_storage = false;
        return false; // Exit the function early if global_is_clear_local_storage was true
    }

    // var storedArrayData = JSON.parse(localStorage.getItem("pos_form_data_array"));

    // console.log("All data afer clear:", storedArrayData);

    let form = $('form#add_pos_sell_form'); // Select the form by ID
    // Check if the form exists in the DOM
    if (form.length === 0) {
        console.error("Error: Form #add_pos_sell_form not found.");
        return;
    }
    // Serialize form data into an array of objects: [{name: 'input_name', value: 'input_value'}, ...]
    let formArray = form.serializeArray();

    // Find if "price_total" already exists in the array
    let priceIndex = formArray.findIndex(item => item.name === "price_total");

    if (priceIndex !== -1) {
        // If exists, update the value
        formArray[priceIndex].value = get_subtotal();
    } else {
        // If not exists, push new entry
        formArray.push({ name: "price_total", value: get_subtotal() });
    }

    // Find if "order_tax" already exists in the array
    let textIndex = formArray.findIndex(item => item.name === "order_tax");

    if (priceIndex !== -1) {
        // If exists, update the value
        formArray[textIndex].value = $("#order_tax").text().trim();
    } else {
        // If not exists, push new entry
        formArray.push({ name: "order_tax", value: $("#order_tax").text().trim() });
    }

    // Find if "shipping_charges_amount" already exists in the array
    let shipping_charges_amount = formArray.findIndex(item => item.name === "shipping_charges_amount");

    if (priceIndex !== -1) {
        // If exists, update the value
        formArray[shipping_charges_amount].value = $("#shipping_charges_amount").text().trim();
    } else {
        // If not exists, push new entry
        formArray.push({ name: "shipping_charges_amount", value: $("#shipping_charges_amount").text().trim() });
    }

    // Find if "total_paying_input" already exists in the array
    let total_paying_input = formArray.findIndex(item => item.name === "total_paying_input");

    if (priceIndex !== -1) {
        // If exists, update the value
        formArray[total_paying_input].value = $("#total_paying_input").val();
    } else {
        // If not exists, push new entry
        formArray.push({ name: "total_paying_input", value: $("#total_paying_input").val() });
    }

    // Find if "change_return" already exists in the array
    let change_return = formArray.findIndex(item => item.name === "change_return");
    if (priceIndex !== -1) {
        // If exists, update the value
        formArray[change_return].value = $("#change_return").val();
    } else {
        // If not exists, push new entry
        formArray.push({ name: "change_return", value: $("#change_return").val() });
    }
    // Find if "in_balance_due" already exists in the array
    let in_balance_due = formArray.findIndex(item => item.name === "in_balance_due");
    if (priceIndex !== -1) {
        // If exists, update the value
        formArray[in_balance_due].value = $("#in_balance_due").val();
    } else {
        // If not exists, push new entry
        formArray.push({ name: "in_balance_due", value: $("#in_balance_due").val() });
    }
    // Store serialized data in LocalStorage as a JSON string
    localStorage.setItem("pos_form_data_array", JSON.stringify(formArray));

    // console.log("Form data successfully saved to LocalStorage.");
}

// Modern Styling Enhancements
function addModernStyling() {
    // Add hover effects to input groups
    $('.input-group').hover(
        function () {
            $(this).css('transform', 'translateY(-2px)');
            $(this).css('box-shadow', '0 4px 8px rgba(22,17,96,0.25)');
        },
        function () {
            $(this).css('transform', 'translateY(0)');
            $(this).css('box-shadow', '0 2px 4px rgba(22,17,96,0.2)');
        }
    );

    // Add focus effects to form controls
    $('.form-control').on('focus', function () {
        $(this).closest('.input-group').css('box-shadow', '0 4px 12px rgba(22, 17, 96, 0.4)');
        $(this).closest('.input-group').css('transform', 'translateY(-2px)');
    }).on('blur', function () {
        $(this).closest('.input-group').css('box-shadow', '0 2px 4px rgba(22,17,96,0.2)');
        $(this).closest('.input-group').css('transform', 'translateY(0)');
    });

    // Add smooth transitions
    $('.input-group, .form-control, .btn').css('transition', 'all 0.3s ease');

    // Enhanced modern styling for table rows
    $('#pos_table tbody tr').hover(
        function () {
            $(this).css('background', 'linear-gradient(135deg, rgba(22,17,96,0.08) 0%, rgba(42,36,128,0.12) 100%)');
            $(this).css('transform', 'translateY(-2px) scale(1.005)');
            $(this).css('box-shadow', '0 4px 12px rgba(22,17,96,0.15)');
            $(this).css('border-left', '4px solid #161160');
            $(this).css('transition', 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)');
        },
        function () {
            $(this).css('background', '');
            $(this).css('transform', 'translateY(0) scale(1)');
            $(this).css('box-shadow', '');
            $(this).css('border-left', '');
        }
    );

    // Add modern styling to individual table cells
    $('#pos_table tbody td').css({
        'padding': '16px 12px',
        'vertical-align': 'middle',
        'border-bottom': '1px solid rgba(22,17,96,0.08)',
        'transition': 'all 0.2s ease',
        'position': 'relative'
    });

    // Add subtle animation when rows are added
    $('#pos_table tbody tr').each(function (index) {
        $(this).css({
            'animation': 'fadeInUp 0.5s ease forwards',
            'animation-delay': (index * 0.1) + 's',
            'opacity': '0',
            'transform': 'translateY(20px)'
        });
    });

    // Add modern focus effects to form controls within table
    $('#pos_table tbody input, #pos_table tbody select, #pos_table tbody textarea').on('focus', function () {
        $(this).closest('td').css({
            'background-color': 'rgba(22,17,96,0.05)',
            'box-shadow': 'inset 0 0 0 2px rgba(22,17,96,0.2)'
        });
    }).on('blur', function () {
        $(this).closest('td').css({
            'background-color': '',
            'box-shadow': ''
        });
    });



    // Call empty state check on page load
    updateEmptyState();

    // Test function for debugging (can be called from console)
    window.testProductAddition = function () {
        console.log('Testing product addition...');
        console.log('Table body exists:', $('#pos_table tbody').length);
        console.log('Search input exists:', $('#search_product').length);
        /* debug removed */

        // Test with a dummy variation ID (this will fail but show us the error)
        pos_product_row(1, null, null, 1);
    };

    // Debug function to check current state
    window.debugEmptyState = function () {
        console.log('=== Empty State Debug ===');
        console.log('Product rows:', $('#pos_table tbody tr.product_row').length);
        console.log('All rows:', $('#pos_table tbody tr').length);
        console.log('Empty row visible:', $('#empty_cart_row').is(':visible'));
        console.log('Empty row display:', $('#empty_cart_row').css('display'));
        console.log('Table body HTML:', $('#pos_table tbody').html());
    };

    // Update empty state when rows are removed
    $(document).on('click', '.pos_remove_row', function () {
        setTimeout(function () {
            updateEmptyState();
        }, 300);
    });

    // Handle modal events for product price editing
    $(document).on('click', '[data-target="#row_edit_product_price_modal"]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $this = $(this);
        var rowIndex = $this.data('row-index');
        console.log('Opening modal for row:', rowIndex);

        // Get the product row data
        var $productRow = $('tr[data-row_index="' + rowIndex + '"]');
        if ($productRow.length === 0) {
            console.error('Product row not found for index:', rowIndex);
            return;
        }

        // Extract current values from the row (numeric-safe)
        var currentUnitPrice = null;
        var priceField = null;
        if ($productRow.find('input.pos_unit_price').length > 0) {
            currentUnitPrice = __read_number($productRow.find('input.pos_unit_price'));
            priceField = 'pos_unit_price';
        }
        if ((currentUnitPrice === null || currentUnitPrice === 0) && $productRow.find('input.pos_unit_price_inc_tax').length > 0) {
            currentUnitPrice = __read_number($productRow.find('input.pos_unit_price_inc_tax'));
            priceField = 'pos_unit_price_inc_tax';
        }
        // Discount: prefer inline controls; fallback to row data attrs
        var currentDiscountType = ($productRow.find('select.row_discount_type').length ? $productRow.find('select.row_discount_type').val() : null);
        if (!currentDiscountType) {
            currentDiscountType = $productRow.data('modal-discount-type') || 'fixed';
        }
        var currentDiscountAmount = ($productRow.find('input.row_discount_amount').length ? __read_number($productRow.find('input.row_discount_amount')) : null);
        if (currentDiscountAmount === null || isNaN(currentDiscountAmount)) {
            currentDiscountAmount = $productRow.data('modal-discount-amount') || 0;
        }
        var currentNote = $productRow.find('textarea[name*="sell_line_note"]').val() || '';

        // Get product name for modal title
        var productName = $productRow.find('span.text-link').text().replace(/\s*&nbsp;.*$/, '').trim();

        // Populate product image in modal (if available)
        try {
            var imgUrl = $productRow.find('input.pos_product_image_url').val();
            if (imgUrl && String(imgUrl).trim() !== '') {
                $('#modal_product_image').attr('src', imgUrl);
                $('#modal_product_image_wrapper').show();
            } else {
                $('#modal_product_image').attr('src', '');
                $('#modal_product_image_wrapper').hide();
            }
        } catch (err) {
            $('#modal_product_image').attr('src', '');
            $('#modal_product_image_wrapper').hide();
        }

        console.log('Product data:', {
            unitPrice: currentUnitPrice,
            discountType: currentDiscountType,
            discountAmount: currentDiscountAmount,
            note: currentNote,
            productName: productName
        });

        // Compute base (pre-discount) unit price for the modal
        var tax_details_open = getPosTaxDetails($productRow.find('select.tax_id'));
        var inc_tax_now = __read_number($productRow.find('input.pos_unit_price_inc_tax'));
        var discounted_base_now = posRemoveTax(inc_tax_now, tax_details_open);
        var base_before_discount_now = get_unit_price_from_discounted_unit_price($productRow, discounted_base_now);

        // Prefer any previously saved base price
        var saved_base = $productRow.data('modal-base-unit-price');
        var base_for_modal = (typeof saved_base !== 'undefined' && !isNaN(saved_base)) ? saved_base : base_before_discount_now;

        // Populate modal with current values
        $('#row_edit_product_price_modal_label').text(productName);
        __write_number($('#modal_unit_price'), base_for_modal);
        $('#modal_discount_type').val(currentDiscountType);
        __write_number($('#modal_discount_amount'), currentDiscountAmount);
        $('#modal_sell_line_note').val(currentNote);

        // Warranty dropdown (if enabled and present in row)
        var $rowWarrantySelect = $productRow.find('select.row_warranty_id');
        if ($rowWarrantySelect.length > 0) {
            var $modalWarrantySelect = $('#modal_warranty_id');
            $modalWarrantySelect.empty();
            $rowWarrantySelect.find('option').each(function () {
                $modalWarrantySelect.append($(this).clone());
            });
            $modalWarrantySelect.val($rowWarrantySelect.val() || '');
            $('#modal_warranty_wrapper').show();
        } else {
            $('#modal_warranty_wrapper').hide();
            $('#modal_warranty_id').empty();
        }

        // Store row index for saving
        $('#row_edit_product_price_modal').data('row-index', rowIndex);
        $('#row_edit_product_price_modal').data('price-field', priceField);
        $('#row_edit_product_price_modal').data('base-unit-price', base_for_modal);

        // Show the modal
        $('#row_edit_product_price_modal').modal('show');
    });

    // Handle save changes in modal
    $(document).on('click', '#modal_save_changes', function () {
        var rowIndex = $('#row_edit_product_price_modal').data('row-index');
        var $productRow = $('tr[data-row_index="' + rowIndex + '"]');

        if ($productRow.length === 0) {
            console.error('Product row not found for saving');
            return;
        }

        // Get values from modal
        var newUnitPrice = __read_number($('#modal_unit_price'));
        var newDiscountType = $('#modal_discount_type').val();
        var newDiscountAmount = __read_number($('#modal_discount_amount'));
        var newNote = $('#modal_sell_line_note').val();
        var newWarrantyId = ($('#modal_warranty_wrapper').is(':visible') ? ($('#modal_warranty_id').val() || '') : null);

        // MSP enforcement (uses min value attached to the row inc-tax input)
        var $rowIncTaxInput = $productRow.find('input.pos_unit_price_inc_tax');
        var minIncTax = parseFloat($rowIncTaxInput.data('rule-min-value'));
        if (!isNaN(minIncTax)) {
            var tax_details = getPosTaxDetails($productRow.find('select.tax_id'));

            var discounted = newUnitPrice;
            if (newDiscountType === 'percentage') {
                discounted = discounted - (discounted * (newDiscountAmount / 100));
            } else {
                discounted = discounted - newDiscountAmount;
            }
            if (discounted < 0) {
                discounted = 0;
            }

            var expectedIncTax = posAddTax(discounted, tax_details);
            if (expectedIncTax < minIncTax) {
                var msg = $rowIncTaxInput.attr('data-msg-min-value') || (LANG.minimum_selling_price_error || 'Price cannot be below the minimum selling price.');

                // Improve clarity: show min price and computed final price after discount
                try {
                    var minFmt = __currency_trans_from_en(minIncTax, true);
                    var finalFmt = __currency_trans_from_en(expectedIncTax, true);
                    if (typeof msg === 'string' && msg.indexOf(':price') !== -1) {
                        msg = msg.replace(':price', minFmt);
                    }
                    msg = msg + ' Final price after discount (' + finalFmt + ') cannot be below minimum selling price (' + minFmt + ').';
                } catch (e) { }

                if (typeof toastr !== 'undefined' && msg) {
                    toastr.error(msg);
                }
                return;
            }
        }


        // Update the product row with new values
        // Try to update pos_unit_price first, if not available, update pos_unit_price_inc_tax
        var savedPriceField = $('#row_edit_product_price_modal').data('price-field');
        if (savedPriceField === 'pos_unit_price' && $productRow.find('input.pos_unit_price').length > 0) {
            __write_number($productRow.find('input.pos_unit_price'), newUnitPrice);
        } else if (savedPriceField === 'pos_unit_price_inc_tax' && $productRow.find('input.pos_unit_price_inc_tax').length > 0) {
            // If only inc_tax field is available, we still store base in data and let calc rebuild inc tax
            $productRow.data('modal-base-unit-price', newUnitPrice);
        } else if ($productRow.find('input.pos_unit_price').length > 0) {
            __write_number($productRow.find('input.pos_unit_price'), newUnitPrice);
        } else {
            $productRow.data('modal-base-unit-price', newUnitPrice);
        }

        // Always persist base price for reliable future edits
        $productRow.data('modal-base-unit-price', newUnitPrice);
        if ($productRow.find('.row_discount_type').length) {
            $productRow.find('.row_discount_type').val(newDiscountType);
        } else {
            $productRow.data('modal-discount-type', newDiscountType);
        }

        if ($productRow.find('.row_discount_amount').length) {
            __write_number($productRow.find('.row_discount_amount'), newDiscountAmount);
        } else {
            $productRow.data('modal-discount-amount', newDiscountAmount);
        }
        $productRow.find('textarea[name*="sell_line_note"]').val(newNote);

        // Persist warranty selection back to row (for form submission)
        if (newWarrantyId !== null) {
            var $rowWarrantySelect = $productRow.find('select.row_warranty_id');
            if ($rowWarrantySelect.length > 0) {
                $rowWarrantySelect.val(newWarrantyId).trigger('change');
            }
        }


        // Trigger change events to update calculations
        if (savedPriceField === 'pos_unit_price' && $productRow.find('input.pos_unit_price').length > 0) {
            $productRow.find('input.pos_unit_price').trigger('change');
        } else if (savedPriceField === 'pos_unit_price_inc_tax' && $productRow.find('input.pos_unit_price_inc_tax').length > 0) {
            // Trigger a recompute using stored base
            pos_each_row($productRow);
        } else if ($productRow.find('input.pos_unit_price').length > 0) {
            $productRow.find('input.pos_unit_price').trigger('change');
        } else {
            pos_each_row($productRow);
        }
        $productRow.find('select.row_discount_type').trigger('change');
        $productRow.find('input.row_discount_amount').trigger('change');

        // Ensure deterministic recompute regardless of listeners
        pos_each_row($productRow);
        round_row_to_iraqi_dinnar($productRow);
        pos_total_row();

        // Close the modal
        $('#row_edit_product_price_modal').modal('hide');

    });

    // Handle modal show event
    $(document).on('show.bs.modal', '.row_edit_product_price_model', function () {
        $(this).css('z-index', '9999');
    });

    // Handle modal hidden event
    $(document).on('hidden.bs.modal', '.row_edit_product_price_model', function () {

        // Clean up backdrop and body classes
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
    });

    // Add loading state to buttons
    $('.btn').on('click', function () {
        var $btn = $(this);
        if (!$btn.hasClass('disabled')) {
            $btn.css('opacity', '0.7');
            setTimeout(function () {
                $btn.css('opacity', '1');
            }, 200);
        }
    });
}

// POS Due Date Dropdown Logic
$(document).on('change', '#pos_due_date_dropdown', function () {
    var val = $(this).val();
    if (val === 'custom') {
        $('#custom_due_days_wrapper').removeClass('hide');
        $('#custom_due_days').focus();
    } else {
        $('#custom_due_days_wrapper').addClass('hide');
        $('#custom_due_days').val('');
        updatePosDueDate(parseInt(val, 10), 'manual-dropdown');
    }
});

$(document).on('input', '#custom_due_days', function () {
    var val = $(this).val();
    if (val && !isNaN(val)) {
        updatePosDueDate(parseInt(val, 10), 'manual-custom');
    }
});

function updatePosDueDate(days, source) {
    if (isNaN(days)) return;

    var baseDate = moment();
    if ($('#transaction_date').length && $('#transaction_date').data('DateTimePicker')) {
        var dpDate = $('#transaction_date').data('DateTimePicker').date();
        if (dpDate) {
            baseDate = dpDate.clone();
        }
    }

    var calculatedDate = baseDate.add(days, 'days');

    var $dueInput = $('#pos_due_date');
    if ($dueInput.length) {
        if (typeof $dueInput.datepicker === 'function') {
            $dueInput.datepicker('update', calculatedDate.toDate());
        } else {
            // fallback if datepicker isn't initialized
            $dueInput.val(calculatedDate.format('MM/DD/YYYY'));
        }
        $dueInput.attr('data-due-date-source', source || 'manual');
    }
}

$(document).on('change', '#pos_due_date', function () {
    if ($(this).val()) {
        $(this).attr('data-due-date-source', 'manual-date');
    }
});

function updateInstallmentFirstDueDate(days) {
    if (days === undefined || days === null) {
        // Calculate days based on interval and interval type
        var interval = parseInt($('#installment_interval').val()) || 1;
        var intervalType = $('#installment_interval_type').val() || 'months';

        // Convert interval to days based on type
        if (intervalType === 'days') {
            days = interval;
        } else if (intervalType === 'weeks') {
            days = interval * 7;
        } else { // months
            days = null; // Use addMonths instead of addDays
        }
    }

    var baseDate = moment();
    if ($('#transaction_date').length && $('#transaction_date').data('DateTimePicker')) {
        var dpDate = $('#transaction_date').data('DateTimePicker').date();
        if (dpDate) {
            baseDate = dpDate.clone();
        }
    }

    var calculatedDate;
    if (days !== null) {
        calculatedDate = baseDate.add(days, 'days');
    } else {
        // For months, use addMonths instead of addDays
        var interval = parseInt($('#installment_interval').val()) || 1;
        calculatedDate = baseDate.clone().add(interval, 'months');
    }

    var $installmentDueInput = $('#installment_first_due_date');
    if ($installmentDueInput.length) {
        if (typeof $installmentDueInput.datepicker === 'function') {
            $installmentDueInput.datepicker('update', calculatedDate.toDate());
        } else {
            $installmentDueInput.val(calculatedDate.format('MM/DD/YYYY'));
        }
    }
}
