//This file contains all functions used in the app.

function __calculate_amount(calculation_type, calculation_amount, amount) {
    var calculation_amount = parseFloat(calculation_amount);
    calculation_amount = isNaN(calculation_amount) ? 0 : calculation_amount;

    var amount = parseFloat(amount);
    amount = isNaN(amount) ? 0 : amount;

    switch (calculation_type) {
        case 'fixed':
            return parseFloat(calculation_amount);
        case 'percentage':
        case 'percent':
            var div = Decimal.div(calculation_amount, 100).toNumber();
            return Decimal.mul(div, amount).toNumber();
        default:
            return 0;
    }
}

//Add specified percentage to the input amount.
function __add_percent(amount, percentage = 0) {
    var amount = parseFloat(amount);
    var percentage = isNaN(percentage) ? 0 : parseFloat(percentage);

    var div = Decimal.div(percentage, 100).toNumber();
    var mul = Decimal.mul(div, amount).toNumber();
    return Decimal.add(amount, mul).toNumber();
}

//Substract specified percentage to the input amount.
function __substract_percent(amount, percentage = 0) {
    var amount = parseFloat(amount);
    var percentage = isNaN(percentage) ? 0 : parseFloat(percentage);

    var div = Decimal.div(percentage, 100).toNumber();
    var mul = Decimal.mul(div, amount).toNumber();
    return Decimal.sub(amount, mul).toNumber();
}

//Returns the principle amount for the calculated amount and percentage
function __get_principle(amount, percentage = 0, minus = false) {
    var amount = parseFloat(amount);
    var percentage = isNaN(percentage) ? 0 : parseFloat(percentage);
    var mul = Decimal.mul(100, amount).toNumber();
    var sum = 1;
    if (minus) {
        sum = Decimal.sub(100, percentage).toNumber();
    } else {
        sum = Decimal.add(100, percentage).toNumber();
    }
    return Decimal.div(mul, sum).toNumber();
}

//Returns the rate at which amount is calculated from principal
function __get_rate(principal, amount) {
    var principal = isNaN(principal) ? 0 : parseFloat(principal);
    var amount = isNaN(amount) ? 0 : parseFloat(amount);
    var interest = Decimal.sub(amount, principal).toNumber();
    var div = Decimal.div(interest, principal).toNumber();
    return Decimal.mul(div, 100).toNumber();
}

function __tab_key_up(e) {
    if (e.keyCode == 9) {
        return true;
    }
}

function __currency_trans_from_en(
    input,
    show_symbol = true,
    use_page_currency = false,
    precision = __currency_precision,
    is_quantity = false
) {
    if (use_page_currency && __p_currency_symbol) {
        var s = __p_currency_symbol;
        var thousand = __p_currency_thousand_separator;
        var decimal = __p_currency_decimal_separator;
    } else {
        var s = __currency_symbol;
        var thousand = __currency_thousand_separator;
        var decimal = __currency_decimal_separator;
    }

    symbol = '';
    var format = '%s%v';
    if (show_symbol) {
        symbol = s;
        format = '%s %v';
        if (__currency_symbol_placement == 'after') {
            format = '%v %s';
        }
    }

    if (is_quantity) {
        precision = __quantity_precision;
    }

    return accounting.formatMoney(input, symbol, precision, thousand, decimal, format);
}

function __currency_convert_recursively(element, use_page_currency = false) {
    element.find('.display_currency').each(function () {
        var value = $(this).text();

        var show_symbol = $(this).data('currency_symbol');
        if (show_symbol == undefined || show_symbol != true) {
            show_symbol = false;
        }

        //If data-use_page_currency is present in the element use_page_currency 
        //value will be over written 
        if (typeof $(this).data('use_page_currency') !== 'undefined') {
            use_page_currency = $(this).data('use_page_currency');
        }

        var highlight = $(this).data('highlight');
        if (highlight == true) {
            __highlight(value, $(this));
        }

        var is_quantity = $(this).data('is_quantity');
        if (is_quantity == undefined || is_quantity != true) {
            is_quantity = false;
        }

        if (is_quantity) {
            show_symbol = false;
        }

        $(this).text(__currency_trans_from_en(value, show_symbol, use_page_currency, __currency_precision, is_quantity));
    });
}

function __translate(str, obj = []) {
    var trans = LANG[str];
    $.each(obj, function (key, value) {
        trans = trans.replace(':' + key, value);
    });
    if (trans) {
        return trans;
    } else {
        return str;
    }
}

//If the value is positive, text-success class will be applied else text-danger
function __highlight(value, obj) {
    obj.removeClass('text-success').removeClass('text-danger');
    if (value > 0) {
        obj.addClass('text-success');
    } else if (value < 0) {
        obj.addClass('text-danger');
    }
}

//Unformats the currency/number
function __number_uf(input, use_page_currency = false) {
    if (use_page_currency && __currency_decimal_separator) {
        var decimal = __p_currency_decimal_separator;
    } else {
        var decimal = __currency_decimal_separator;
    }

    return accounting.unformat(input, decimal);
}

//Alias of currency format, formats number
function __number_f(
    input,
    show_symbol = false,
    use_page_currency = false,
    precision = __currency_precision
) {
    return __currency_trans_from_en(input, show_symbol, use_page_currency, precision);
}

//Read input and convert it into natural number
function __read_number(input_element, use_page_currency = false) {
    return __number_uf(input_element.val(), use_page_currency);
}

//Write input by converting to formatted number
function __write_number(
    input_element,
    value,
    use_page_currency = false,
    precision = __currency_precision
) {
    if (input_element.hasClass('input_quantity')) {
        precision = __quantity_precision;
    }

    input_element.val(__number_f(value, false, use_page_currency, precision));
}

//Return the font-awesome html based on class value
function __fa_awesome($class = 'fa-sync fa-spin fa-fw ') {
    return '<i class="fa ' + $class + '"></i>';
}

//Converts standard dates (YYYY-MM-DD) to human readable dates
function __show_date_diff_for_human(element) {
    moment.locale(app_locale);
    element.find('.time-to-now').each(function () {
        var string = $(this).text();
        $(this).text(moment(string).toNow(true));
    });

    element.find('.time-from-now').each(function () {
        var string = $(this).text();
        $(this).text(moment(string).from(moment()));
    });
}

//Rounds a number to Iraqi dinnar
function round_to_iraqi_dinnar(value) {
    //Adjsustment
    var remaining = value % 250;
    if (remaining >= 125) {
        value += 250 - remaining;
    } else {
        value -= remaining;
    }

    return value;
}

function __select2(selector) {
    if ($('html').attr('dir') == 'rtl') selector.select2({ dir: 'rtl' });
    else selector.select2();
}

function update_font_size() {
    var font_size = localStorage.getItem('upos_font_size');
    var font_size_array = [];
    font_size_array['s'] = ' - 3px';
    font_size_array['m'] = '';
    font_size_array['l'] = ' + 3px';
    font_size_array['xl'] = ' + 6px';
    if (typeof font_size !== 'undefined') {
        $('header').css('font-size', 'calc(100% ' + font_size_array[font_size] + ')');
        $('footer').css('font-size', 'calc(100% ' + font_size_array[font_size] + ')');
        $('section').each(function () {
            if (!$(this).hasClass('print_section')) {
                $(this).css('font-size', 'calc(100% ' + font_size_array[font_size] + ')');
            }
        });
        $('div.modal').css('font-size', 'calc(100% ' + font_size_array[font_size] + ')');
    }
}

function sum_table_col(table, class_name) {
    var sum = 0;
    table
        .find('tbody')
        .find('tr')
        .each(function () {
            if (
                parseFloat(
                    $(this)
                        .find('.' + class_name)
                        .data('orig-value')
                )
            ) {
                sum += parseFloat(
                    $(this)
                        .find('.' + class_name)
                        .data('orig-value')
                );
            }
        });

    return sum;
}

function __count_status(data, key) {
    var statuses = [];
    for (var r in data) {
        var element = $(data[r][key]);
        if (element.data('orig-value')) {
            var status_name = element.data('orig-value');
            if (!(status_name in statuses)) {
                statuses[status_name] = [];
                statuses[status_name]['count'] = 1;
                statuses[status_name]['display_name'] = element.data('status-name');
            } else {
                statuses[status_name]['count'] += 1;
            }
        }
    }

    //generate html
    var html = '<p class="text-left"><small>';
    for (var key in statuses) {
        html +=
            statuses[key]['display_name'] + ' - ' + statuses[key]['count'] + '</br>';
    }

    html += '</small></p>';

    return html;
}

function __sum_status(table, class_name) {
    var statuses = [];
    var status_html = [];
    table
        .find('tbody')
        .find('tr')
        .each(function () {
            element = $(this).find('.' + class_name);
            if (element.data('orig-value')) {
                var status_name = element.data('orig-value');
                if (!(status_name in statuses)) {
                    statuses[status_name] = [];
                    statuses[status_name]['count'] = 1;
                    statuses[status_name]['display_name'] = element.data('status-name');
                } else {
                    statuses[status_name]['count'] += 1;
                }
            }
        });

    return statuses;
}

function __sum_status_html(table, class_name) {
    var statuses_sum = __sum_status(table, class_name);
    var status_html = '<p class="text-left"><small>';
    for (var key in statuses_sum) {
        status_html +=
            statuses_sum[key]['display_name'] + ' - ' + statuses_sum[key]['count'] + '</br>';
    }

    status_html += '</small></p>';

    return status_html;
}

function __sum_stock(table, class_name, label_direction = 'right') {
    var stocks = [];
    table
        .find('tbody')
        .find('tr')
        .each(function () {
            element = $(this).find('.' + class_name);
            if (element.data('orig-value')) {
                var unit_name = element.data('unit');
                if (!(unit_name in stocks)) {
                    stocks[unit_name] = parseFloat(element.data('orig-value'));
                } else {
                    stocks[unit_name] += parseFloat(element.data('orig-value'));
                }
            }
        });
    var stock_html = '<p class="text-left"><small>';

    for (var key in stocks) {
        if (label_direction == 'left') {
            stock_html +=
                key +
                ' : <span class="display_currency" data-is_quantity="true">' +
                stocks[key] +
                '</span> ' +
                '</br>';
        } else {
            stock_html +=
                '<span class="display_currency" data-is_quantity="true">' +
                stocks[key] +
                '</span> ' +
                key +
                '</br>';
        }
    }

    stock_html += '</small></p>';

    return stock_html;
}

function __isMobileDevice() {
    var ua = navigator.userAgent || '';
    var uaLooksMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(ua);
    var coarsePointer = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
    var narrowScreen = Math.min(window.innerWidth || 0, window.innerHeight || 0) <= 767;

    return uaLooksMobile || (coarsePointer && narrowScreen);
}

function __print_receipt(section_id = null) {
    var $allReceiptSections = $('.print_section#receipt_section');
    var $sourceSection = section_id ? $('#' + section_id).filter(function () {
        return $.trim($(this).html() || '').length > 0;
    }).first() : $allReceiptSections.filter(function () {
        return $.trim($(this).html() || '').length > 0;
    }).first();

    var $targetSection = $allReceiptSections.last();
    if (!$targetSection.length) {
        return;
    }

    if ($sourceSection.length && $sourceSection[0] !== $targetSection[0]) {
        $targetSection.html($sourceSection.html());
    }

    $('.print_section').removeClass('print-target');
    $targetSection.addClass('print-target');

    // Mobile browsers often block iframe printing. Show a receipt preview first,
    // then let the user's tap on "Print" open the OS print/share sheet.
    if (__isMobileDevice()) {
        var receiptHtml = $targetSection.html();
        var $mobileModal = $('#mobile_receipt_modal');
        var $mobileContent = $('#mobile_receipt_content');

        if ($mobileModal.length && $.trim(receiptHtml || '').length) {
            $mobileContent.html(receiptHtml);
            $mobileModal.show();
            $('body').addClass('is-printing-receipt');
        } else if ($.trim(receiptHtml || '').length) {
            // Fallback when the modal markup is missing for any reason.
            var win = window.open('', '_blank');
            if (win) {
                win.document.write('<html><head><title>Receipt</title><style>body{font-family:Arial,sans-serif;padding:12px;}</style></head><body>' + receiptHtml + '</body></html>');
                win.document.close();
                win.focus();
                setTimeout(function () { win.print(); }, 600);
            }
        }
        return;
    }

    $('body').addClass('is-printing-receipt');

    __receipt_print_target = $targetSection;
    __receipt_print_started = false;

    var imgs = $targetSection[0].getElementsByTagName('img');

    img_len = imgs.length;
    if (img_len) {
        img_counter = 0;

        [].forEach.call(imgs, function (img) {
            var imageReadyHandled = false;
            var handleImageReady = function () {
                if (imageReadyHandled) {
                    return;
                }
                imageReadyHandled = true;
                incrementImageCounter();
            };

            if (img.complete) {
                handleImageReady();
            } else {
                img.addEventListener('load', handleImageReady, false);
                img.addEventListener('error', handleImageReady, false);
            }
        });
    } else {
        setTimeout(function () {
            __execute_receipt_print();

        }, 1000);
    }
}

function incrementImageCounter() {
    img_counter++;
    if (img_counter >= img_len) {
        __execute_receipt_print();
    }
}

var img_len = 0;
var img_counter = 0;
var __receipt_print_target = null;
var __receipt_print_started = false;
function __execute_receipt_print() {
    if (!__receipt_print_target || !__receipt_print_target.length) {
        return;
    }
    if (__receipt_print_started) {
        return;
    }
    __receipt_print_started = true;

    try {
        window.print();
    } catch (err) {
        __cleanup_receipt_print();
        return;
    }

    setTimeout(function () {
        if (__receipt_print_target) {
            __cleanup_receipt_print();
        }
    }, 30000);
}

function __cleanup_receipt_print() {
    $('.print_section').removeClass('print-target');
    $('.print_section#receipt_section').html('');
    $('body').removeClass('is-printing-receipt');
    __receipt_print_target = null;
    __receipt_print_started = false;
}

// === Mobile receipt modal: wire up the Print and Close buttons.
// Delegated handlers so they keep working even if the modal markup is
// re-rendered or replaced.
$(function () {
    if (window.addEventListener) {
        window.addEventListener('afterprint', function () {
            if (!$('#mobile_receipt_modal:visible').length) {
                __cleanup_receipt_print();
            }
        });
    }

    $(document).on('click', '#mobile_receipt_print_btn', function () {
        $('body').addClass('is-printing-receipt');
        try {
            window.print();
        } catch (err) {
            // Some embedded mobile browsers throw on window.print(); ignore.
        }
    });

    $(document).on('click', '#mobile_receipt_close_btn', function () {
        var $modal = $('#mobile_receipt_modal');
        $modal.hide();
        $('#mobile_receipt_content').html('');
        $('body').removeClass('is-printing-receipt');
        // Also clear the hidden receipt section so the next sale starts clean.
        $('.print_section#receipt_section').html('');
    });
});

function __getUnitMultiplier(row) {
    multiplier = row.find('select.sub_unit').find(':selected').data('multiplier');
    if (multiplier == undefined) {
        return 1;
    } else {
        return parseFloat(multiplier);
    }
}

//Rounds a number to the nearest given multiple
function __round(number, multiple = 0) {

    rounded_number = number;
    if (multiple > 0) {
        x = new Decimal(number)
        rounded_number = x.toNearest(multiple);
    }

    var output = {
        number: rounded_number,
        diff: rounded_number - number
    }

    return output;
}

//This method removes unwanted get parameter from the data.
function __datatable_ajax_callback(data) {
    for (var i = 0, len = data.columns.length; i < len; i++) {
        if (!data.columns[i].search.value) delete data.columns[i].search;
        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
    }
    delete data.search.regex;

    return data;
}

//Confirmation before page load.
function __page_leave_confirmation(form) {
    var form_obj = $(form);
    var orig_form_data = form_obj.serialize();

    setTimeout(function () { orig_form_data = form_obj.serialize(); }, 1000);

    $(document).on("submit", "form", function (event) {
        window.onbeforeunload = null;
    });
    window.onbeforeunload = function () {
        if (form_obj.serialize() != orig_form_data) {
            return LANG.sure;
        }
    }
}

//initialize tinyMCE editor for invoice template
function init_tinymce(editor_id) {
    tinymce.init({
        selector: 'textarea#' + editor_id,
        plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'table template paste help'
        ],
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |' +
            ' bullist numlist outdent indent | link image | print preview fullpage | ' +
            'forecolor backcolor',
        menu: {
            favs: { title: 'My Favorites', items: 'code | searchreplace' }
        },
        menubar: 'favs file edit view insert format tools table help'
    });
}

function getSelectedRows() {
    var selected_rows = [];
    var i = 0;
    $('.row-select:checked').each(function () {
        selected_rows[i++] = $(this).val();
    });

    return selected_rows;
}

function __is_online() {
    return true;

    //if localhost always return true
    if ($('#__is_localhost').length > 0) {
        return true;
    }

    return window.navigator.onLine;
}

function __disable_submit_button(element) {
    if (__is_online()) {
        element.attr('disable', true);
    }
}

function __current_datetime() {
    return moment().format(moment_date_format + ' ' + moment_time_format);
}