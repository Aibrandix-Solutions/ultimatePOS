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

/**
 * Resolve the receipt mount node. When duplicate ids exist in the DOM,
 * document.getElementById returns the first node (often wrong/hidden).
 * The canonical receipt target is the last #receipt_section in tree order.
 */
function __getReceiptPrintElement(section_id) {
    var sid = section_id || 'receipt_section';
    if (typeof jQuery !== 'undefined' && jQuery('#' + sid).length) {
        var $last = jQuery('#' + sid).last();
        if ($last.length) {
            return $last[0];
        }
    }
    return document.getElementById(sid);
}

function __print_receipt(section_id = null) {
    var rid = section_id || 'receipt_section';
    var receiptEl = __getReceiptPrintElement(rid);
    if (!receiptEl || !receiptEl.innerHTML.trim()) {
        return;
    }

    var receiptHtml = receiptEl.innerHTML;
    var imgs = receiptEl.getElementsByTagName('img');
    var img_len = imgs.length;
    var jobStarted = false;

    function escAttr(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/</g, '&lt;');
    }

    /**
     * Print via a dedicated iframe document. Samsung / Android Chrome often
     * rasterizes a blank page if the iframe has 0×0 size, visibility:hidden,
     * or print() runs before stylesheets / images have loaded in the iframe.
     */
    function printReceiptInIframe() {
        if (jobStarted) {
            return;
        }
        jobStarted = true;

        var iframe = document.createElement('iframe');
        iframe.setAttribute('title', 'Receipt');
        iframe.setAttribute('aria-hidden', 'true');
        // Non-zero layout box off-screen — critical for Android print preview.
        iframe.style.cssText =
            'position:fixed;left:-9999px;top:0;width:100mm;min-width:280px;' +
            'min-height:120mm;height:auto;border:0;margin:0;padding:0;' +
            'opacity:0.01;pointer-events:none;z-index:0;overflow:hidden;background:#fff';

        document.body.appendChild(iframe);

        var win = iframe.contentWindow;
        var doc = win.document;
        var baseNode = document.querySelector('base');
        var baseHref = (baseNode && baseNode.href) ? baseNode.href : window.location.href.split('#')[0];

        var isAndroid = /Android/i.test(navigator.userAgent || '');

        var inlineBaseCss =
            '@page{margin:5mm}' +
            'html,body{margin:0;padding:8px;background:#fff!important;color:#111!important;' +
            'font:14px/1.35 Arial,Helvetica,sans-serif;-webkit-print-color-adjust:exact;print-color-adjust:exact}' +
            '*{box-sizing:border-box}' +
            'table{width:100%;border-collapse:collapse}' +
            'td,th{padding:2px 4px;vertical-align:top}' +
            'img{max-width:100%!important;height:auto!important;display:inline-block}' +
            '.text-center{text-align:center}.text-left{text-align:left}.text-right{text-align:right}';

        doc.open();
        doc.write('<!DOCTYPE html><html><head><meta charset="utf-8">');
        doc.write('<meta name="viewport" content="width=device-width, initial-scale=1">');
        doc.write('<base href="' + escAttr(baseHref) + '">');
        doc.write('<style>' + inlineBaseCss + '</style>');

        var linkNodes = document.querySelectorAll('link[rel="stylesheet"]');
        for (var li = 0; li < linkNodes.length; li++) {
            var href = linkNodes[li].href;
            if (href) {
                var media = linkNodes[li].getAttribute('media') || 'all';
                doc.write(
                    '<link rel="stylesheet" href="' +
                        escAttr(href) +
                        '" media="' +
                        escAttr(media) +
                        '">'
                );
            }
        }

        doc.write('</head><body>');
        doc.write(receiptHtml);
        doc.write('</body></html>');
        doc.close();

        var printed = false;

        function clearHostReceipt() {
            var rs = __getReceiptPrintElement(rid);
            if (rs) {
                setTimeout(function () {
                    rs.innerHTML = '';
                }, 800);
            }
        }

        function removeFrame() {
            try {
                if (iframe.parentNode) {
                    iframe.parentNode.removeChild(iframe);
                }
            } catch (e) {
                /* ignore */
            }
        }

        function invokePrint() {
            if (printed) {
                return;
            }
            printed = true;
            try {
                win.focus();
                win.print();
            } catch (e) {
                /* ignore */
            }
        }

        var iframeBodyWaitAttempts = 0;

        function waitIframeImagesThenPrint() {
            var body = doc.body;
            if (!body) {
                iframeBodyWaitAttempts++;
                if (iframeBodyWaitAttempts < 100) {
                    setTimeout(waitIframeImagesThenPrint, 50);
                } else {
                    schedulePrintAfterPaint();
                }
                return;
            }

            var iImgs = body.getElementsByTagName('img');
            var n = iImgs.length;
            if (!n) {
                schedulePrintAfterPaint();
                return;
            }

            var done = 0;
            var fired = false;

            function tick() {
                if (fired) {
                    return;
                }
                done++;
                if (done >= n) {
                    fired = true;
                    schedulePrintAfterPaint();
                }
            }

            for (var ii = 0; ii < n; ii++) {
                var im = iImgs[ii];
                if (im.complete) {
                    tick();
                } else {
                    im.addEventListener(
                        'load',
                        function () {
                            tick();
                        },
                        false
                    );
                    im.addEventListener(
                        'error',
                        function () {
                            tick();
                        },
                        false
                    );
                }
            }

            setTimeout(function () {
                if (!fired) {
                    fired = true;
                    schedulePrintAfterPaint();
                }
            }, 8000);
        }

        var printPipelineStarted = false;
        function schedulePrintAfterPaint() {
            if (printPipelineStarted) {
                return;
            }
            printPipelineStarted = true;

            function waitFontsThenDelay() {
                var delayAfterFonts = isAndroid ? 650 : 200;

                function runPrint() {
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () {
                            invokePrint();
                        });
                    });
                }

                if (doc.fonts && doc.fonts.ready && typeof doc.fonts.ready.then === 'function') {
                    doc.fonts.ready.then(function () {
                        setTimeout(runPrint, delayAfterFonts);
                    }).catch(function () {
                        setTimeout(runPrint, delayAfterFonts + 200);
                    });
                } else {
                    setTimeout(runPrint, isAndroid ? 900 : 350);
                }
            }

            if (doc.readyState === 'complete') {
                waitFontsThenDelay();
            } else {
                win.addEventListener('load', waitFontsThenDelay);
                setTimeout(waitFontsThenDelay, isAndroid ? 3500 : 2000);
            }
        }

        win.addEventListener('afterprint', function () {
            removeFrame();
            clearHostReceipt();
        });

        if (doc.readyState === 'complete') {
            waitIframeImagesThenPrint();
        } else {
            win.addEventListener('load', waitIframeImagesThenPrint);
            setTimeout(waitIframeImagesThenPrint, isAndroid ? 5000 : 3000);
        }

        setTimeout(function () {
            if (iframe.parentNode) {
                removeFrame();
                clearHostReceipt();
            }
        }, 180000);
    }

    if (img_len) {
        var img_counter = 0;
        var checkDone = function () {
            img_counter++;
            if (img_counter >= img_len) {
                printReceiptInIframe();
            }
        };
        [].forEach.call(imgs, function (img) {
            if (img.complete) {
                checkDone();
            } else {
                img.addEventListener('load', checkDone, false);
                img.addEventListener('error', checkDone, false);
            }
        });
        setTimeout(function () {
            if (!jobStarted) {
                printReceiptInIframe();
            }
        }, 5000);
    } else {
        setTimeout(printReceiptInIframe, 200);
    }
}


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