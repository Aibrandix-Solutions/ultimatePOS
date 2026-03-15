{{-- Invoice Layout Design Preview Partial --}}
<div class="box box-solid" id="invoice_preview_box">
    <div class="box-header with-border" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
        <h3 class="box-title" style="color: #fff; font-weight: 600;">
            <i class="fa fa-eye"></i> @lang('lang_v1.design') Preview
        </h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: #fff;">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body" style="background: #f0f2f5; padding: 25px;">
        {{-- Design name badge --}}
        <div style="text-align: center; margin-bottom: 15px;">
            <span id="preview_design_badge" style="
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: #fff;
                padding: 5px 18px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            ">
                <i class="fa fa-file-text-o"></i> <span id="preview_design_name">Classic</span>
            </span>
        </div>

        {{-- Preview container --}}
        <div id="invoice_preview_wrapper" style="
            max-width: 750px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            overflow: hidden;
            transition: all 0.3s ease;
        ">
            {{-- Loading spinner --}}
            <div id="preview_loading" style="
                text-align: center;
                padding: 60px 20px;
                display: none;
            ">
                <div style="
                    display: inline-block;
                    width: 40px;
                    height: 40px;
                    border: 3px solid #e0e0e0;
                    border-top-color: #667eea;
                    border-radius: 50%;
                    animation: preview-spin 0.8s linear infinite;
                "></div>
                <p style="margin-top: 15px; color: #888; font-size: 14px;">Loading preview...</p>
            </div>

            {{-- Rendered preview content --}}
            <div id="invoice_preview_content" style="padding: 20px 25px;">
                <p style="text-align: center; color: #aaa; padding: 40px 0;">
                    <i class="fa fa-file-text-o" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                    Select a design to see the preview
                </p>
            </div>
        </div>

        {{-- Sample data notice --}}
        <div style="text-align: center; margin-top: 12px;">
            <small style="color: #999; font-size: 11px;">
                <i class="fa fa-info-circle"></i> This preview uses sample data for illustration purposes
            </small>
        </div>
    </div>
</div>

<style>
    @keyframes preview-spin {
        to { transform: rotate(360deg); }
    }
    #invoice_preview_wrapper:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.18);
    }
    #invoice_preview_content .row {
        margin-left: 0;
        margin-right: 0;
    }
    /* ===== Common slim receipt styles ===== */
    .slim-preview,
    .slim2-preview {
        border-radius: 2px !important;
        border: 1px solid #ccc !important;
        border-top: 2px dashed #999 !important;
        border-bottom: 2px dashed #999 !important;
        box-shadow: 2px 3px 12px rgba(0,0,0,0.10) !important;
        overflow: hidden !important;
    }
    .slim-preview #invoice_preview_content,
    .slim2-preview #invoice_preview_content {
        overflow: hidden;
    }
    /* Force all content to scale inside the container */
    .slim-preview #invoice_preview_content > * ,
    .slim2-preview #invoice_preview_content > * {
        max-width: 100%;
    }
    /* Tables: auto layout so columns size to content */
    .slim-preview table,
    .slim2-preview table {
        table-layout: auto !important;
        width: 100% !important;
    }
    /* Number columns should never wrap */
    .slim-preview td:last-child,
    .slim-preview td:nth-last-child(2),
    .slim-preview td:nth-last-child(3),
    .slim-preview th:last-child,
    .slim-preview th:nth-last-child(2),
    .slim-preview th:nth-last-child(3),
    .slim2-preview td:last-child,
    .slim2-preview td:nth-last-child(2),
    .slim2-preview td:nth-last-child(3),
    .slim2-preview th:last-child,
    .slim2-preview th:nth-last-child(2),
    .slim2-preview th:nth-last-child(3) {
        white-space: nowrap !important;
        text-align: right !important;
    }
    /* Compact cell padding */
    .slim-preview td,
    .slim-preview th {
        padding: 2px 3px !important;
        font-size: 11px !important;
    }
    .slim2-preview td,
    .slim2-preview th {
        padding: 1px 2px !important;
        font-size: 9px !important;
    }
    /* Compact headings */
    .slim-preview h2, .slim-preview h3, .slim-preview h4 {
        font-size: 13px !important;
        margin: 4px 0 !important;
    }
    .slim2-preview h2, .slim2-preview h3, .slim2-preview h4 {
        font-size: 11px !important;
        margin: 2px 0 !important;
    }
    /* Compact paragraphs */
    .slim-preview p {
        margin: 2px 0 !important;
        font-size: 11px !important;
    }
    .slim2-preview p {
        margin: 1px 0 !important;
        font-size: 9px !important;
    }
    /* Remove bootstrap column padding */
    .slim-preview [class*="col-"],
    .slim2-preview [class*="col-"] {
        padding-left: 2px !important;
        padding-right: 2px !important;
    }
    /* Remove row margins */
    .slim-preview .row,
    .slim2-preview .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    /* Compact small/span text */
    .slim-preview small, .slim-preview span {
        font-size: 10px !important;
    }
    .slim2-preview small, .slim2-preview span {
        font-size: 8px !important;
    }

    /* ===== Slim (80mm) specific ===== */
    #invoice_preview_wrapper.slim-preview {
        width: 290px;
        max-width: 290px;
    }
    #invoice_preview_wrapper.slim-preview #invoice_preview_content {
        padding: 6px 8px;
        font-size: 11px;
    }

    /* ===== Slim2 (58mm) specific ===== */
    #invoice_preview_wrapper.slim2-preview {
        width: 225px;
        max-width: 225px;
    }
    #invoice_preview_wrapper.slim2-preview #invoice_preview_content {
        padding: 4px 6px;
        font-size: 9px;
    }
</style>

<script>
    // Preview loading function - will be called from the page JS
    function loadInvoicePreview(design) {
        var $wrapper = $('#invoice_preview_wrapper');
        var $content = $('#invoice_preview_content');
        var $loading = $('#preview_loading');
        var $badge = $('#preview_design_name');

        // Update badge with design name
        var designNames = {
            'classic': 'Classic',
            'elegant': 'Elegant',
            'detailed': 'Detailed',
            'columnize-taxes': 'Columnize Taxes',
            'slim': 'Slim (80mm)',
            'slim2': 'Slim 2 (58mm)',
            'english-arabic': 'English-Arabic',
            'modern-slim': 'Modern Slim (80mm)'
        };
        $badge.text(designNames[design] || design);

        // Adjust wrapper width for slim designs
        $wrapper.removeClass('slim-preview slim2-preview');
        if (design === 'slim' || design === 'modern-slim') {
            $wrapper.addClass('slim-preview');
        } else if (design === 'slim2') {
            $wrapper.addClass('slim2-preview');
        }

        // Show loading, hide content
        $content.hide();
        $loading.show();

        $.ajax({
            url: '/invoice-layouts/preview/' + design,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $loading.hide();
                $content.html(response.html).fadeIn(300);
            },
            error: function() {
                $loading.hide();
                $content.html(
                    '<p style="text-align:center; color:#e74c3c; padding:40px 0;">' +
                    '<i class="fa fa-exclamation-triangle" style="font-size:30px; display:block; margin-bottom:10px;"></i>' +
                    'Failed to load preview. Please try again.</p>'
                ).fadeIn(300);
            }
        });
    }
</script>
