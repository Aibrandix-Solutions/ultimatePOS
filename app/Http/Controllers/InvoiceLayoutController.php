<?php

namespace App\Http\Controllers;

use App\InvoiceLayout;
use App\Utils\Util;
use Illuminate\Http\Request;
use Validator;

class InvoiceLayoutController extends Controller
{
    protected $commonUtil;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('invoice_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        $designs = $this->getDesigns();
        $common_settings = session()->get('business.common_settings');
        $is_warranty_enabled = !empty($common_settings['enable_product_warranty']) ? true : false;

        return view('invoice_layout.create')->with(compact('designs', 'is_warranty_enabled'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('invoice_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $validator = Validator::make($request->all(), [
                'logo' => 'mimes:jpeg,gif,png|1000',
            ]);

            $input = $request->only([
                'name',
                'header_text',
                'invoice_no_prefix',
                'invoice_heading',
                'sub_total_label',
                'discount_label',
                'tax_label',
                'total_label',
                'highlight_color',
                'footer_text',
                'invoice_heading_not_paid',
                'invoice_heading_paid',
                'total_due_label',
                'customer_label',
                'paid_label',
                'sub_heading_line1',
                'sub_heading_line2',
                'sub_heading_line3',
                'sub_heading_line4',
                'sub_heading_line5',
                'table_product_label',
                'table_qty_label',
                'table_unit_price_label',
                'table_subtotal_label',
                'client_id_label',
                'date_label',
                'quotation_heading',
                'quotation_no_prefix',
                'design',
                'client_tax_label',
                'cat_code_label',
                'cn_heading',
                'cn_no_label',
                'cn_amount_label',
                'sales_person_label',
                'prev_bal_label',
                'date_time_format',
                'common_settings',
                'change_return_label',
                'round_off_label',
                'qr_code_fields',
                'commission_agent_label',
            ]);

            $business_id = $request->session()->get('user.business_id');
            $input['business_id'] = $business_id;

            //Set value for checkboxes
            $checkboxes = [
                'show_business_name',
                'show_location_name',
                'show_landmark',
                'show_city',
                'show_state',
                'show_country',
                'show_zip_code',
                'show_mobile_number',
                'show_alternate_number',
                'show_email',
                'show_tax_1',
                'show_tax_2',
                'show_logo',
                'show_barcode',
                'show_payments',
                'show_customer',
                'show_client_id',
                'show_brand',
                'show_sku',
                'show_cat_code',
                'show_sale_description',
                'show_sales_person',
                'show_expiry',
                'show_lot',
                'show_previous_bal',
                'show_image',
                'show_reward_point',
                'show_qr_code',
                'show_commission_agent',
                'show_letter_head',
            ];
            foreach ($checkboxes as $name) {
                $input[$name] = !empty($request->input($name)) ? 1 : 0;
            }

            //Upload Logo
            $logo_name = $this->commonUtil->uploadFile($request, 'logo', 'invoice_logos', 'image');
            if (!empty($logo_name)) {
                $input['logo'] = $logo_name;
            }

            $letter_head = $this->commonUtil->uploadFile($request, 'letter_head', 'invoice_logos', 'image');
            if (!empty($letter_head)) {
                $input['letter_head'] = $letter_head;
            }

            if (!empty($request->input('is_default'))) {
                //get_default
                $default = InvoiceLayout::where('business_id', $business_id)
                    ->where('is_default', 1)
                    ->update(['is_default' => 0]);
                $input['is_default'] = 1;
            }

            //Module info
            if ($request->has('module_info')) {
                $input['module_info'] = json_encode($request->input('module_info'));
            }

            if (!empty($request->input('table_tax_headings'))) {
                $input['table_tax_headings'] = json_encode($request->input('table_tax_headings'));
            }
            $input['product_custom_fields'] = !empty($request->input('product_custom_fields')) ? $request->input('product_custom_fields') : null;
            $input['contact_custom_fields'] = !empty($request->input('contact_custom_fields')) ? $request->input('contact_custom_fields') : null;
            $input['location_custom_fields'] = !empty($request->input('location_custom_fields')) ? $request->input('location_custom_fields') : null;

            InvoiceLayout::create($input);
            $output = [
                'success' => 1,
                'msg' => __('invoice.layout_added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect('invoice-schemes')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InvoiceLayout  $invoiceLayout
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceLayout $invoiceLayout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\InvoiceLayout  $invoiceLayout
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('invoice_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        $invoice_layout = InvoiceLayout::findOrFail($id);

        //Module info
        $invoice_layout->module_info = json_decode($invoice_layout->module_info, true);
        $invoice_layout->table_tax_headings = !empty($invoice_layout->table_tax_headings) ? json_decode($invoice_layout->table_tax_headings) : ['', '', '', ''];

        $designs = $this->getDesigns();

        return view('invoice_layout.edit')
            ->with(compact('invoice_layout', 'designs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoiceLayout  $invoiceLayout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('invoice_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $validator = Validator::make($request->all(), [
                'logo' => 'mimes:jpeg,gif,png|1000',
            ]);

            $input = $request->only([
                'name',
                'header_text',
                'invoice_no_prefix',
                'invoice_heading',
                'sub_total_label',
                'discount_label',
                'tax_label',
                'total_label',
                'highlight_color',
                'footer_text',
                'invoice_heading_not_paid',
                'invoice_heading_paid',
                'total_due_label',
                'customer_label',
                'paid_label',
                'sub_heading_line1',
                'sub_heading_line2',
                'sub_heading_line3',
                'sub_heading_line4',
                'sub_heading_line5',
                'table_product_label',
                'table_qty_label',
                'table_unit_price_label',
                'table_subtotal_label',
                'client_id_label',
                'date_label',
                'quotation_heading',
                'quotation_no_prefix',
                'design',
                'client_tax_label',
                'cat_code_label',
                'cn_heading',
                'cn_no_label',
                'cn_amount_label',
                'sales_person_label',
                'prev_bal_label',
                'date_time_format',
                'change_return_label',
                'round_off_label',
                'commission_agent_label',
            ]);
            $business_id = $request->session()->get('user.business_id');

            $checkboxes = [
                'show_business_name',
                'show_location_name',
                'show_landmark',
                'show_city',
                'show_state',
                'show_country',
                'show_zip_code',
                'show_mobile_number',
                'show_alternate_number',
                'show_email',
                'show_tax_1',
                'show_tax_2',
                'show_logo',
                'show_barcode',
                'show_payments',
                'show_customer',
                'show_client_id',
                'show_brand',
                'show_sku',
                'show_cat_code',
                'show_sale_description',
                'show_sales_person',
                'show_expiry',
                'show_lot',
                'show_previous_bal',
                'show_image',
                'show_reward_point',
                'show_qr_code',
                'show_commission_agent',
                'show_letter_head',
            ];
            foreach ($checkboxes as $name) {
                $input[$name] = !empty($request->input($name)) ? 1 : 0;
            }

            //Upload Logo
            $logo_name = $this->commonUtil->uploadFile($request, 'logo', 'invoice_logos', 'image');
            if (!empty($logo_name)) {
                $input['logo'] = $logo_name;
            }

            //Upload letter head
            $letter_head = $this->commonUtil->uploadFile($request, 'letter_head', 'invoice_logos', 'image');
            if (!empty($letter_head)) {
                $input['letter_head'] = $letter_head;
            }

            if (!empty($request->input('is_default'))) {
                //get_default
                $default = InvoiceLayout::where('business_id', $business_id)
                    ->where('is_default', 1)
                    ->update(['is_default' => 0]);
                $input['is_default'] = 1;
            }

            //Module info
            if ($request->has('module_info')) {
                $input['module_info'] = json_encode($request->input('module_info'));
            }

            if (!empty($request->input('table_tax_headings'))) {
                $input['table_tax_headings'] = json_encode($request->input('table_tax_headings'));
            }

            $input['product_custom_fields'] = !empty($request->input('product_custom_fields')) ? json_encode($request->input('product_custom_fields')) : null;
            $input['contact_custom_fields'] = !empty($request->input('contact_custom_fields')) ? json_encode($request->input('contact_custom_fields')) : null;
            $input['location_custom_fields'] = !empty($request->input('location_custom_fields')) ? json_encode($request->input('location_custom_fields')) : null;
            $input['common_settings'] = !empty($request->input('common_settings')) ? json_encode($request->input('common_settings')) : null;
            $input['qr_code_fields'] = !empty($request->input('qr_code_fields')) ? json_encode($request->input('qr_code_fields')) : null;

            InvoiceLayout::where('id', $id)
                ->where('business_id', $business_id)
                ->update($input);
            $output = [
                'success' => 1,
                'msg' => __('invoice.layout_updated_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect('invoice-schemes')->with('status', $output);
    }

    /**
     * Return rendered preview HTML for a given invoice design.
     *
     * @param  string  $design
     * @return \Illuminate\Http\Response
     */
    public function preview($design)
    {
        if (!auth()->user()->can('invoice_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        $valid_designs = ['classic', 'elegant', 'detailed', 'columnize-taxes', 'slim', 'slim2', 'english-arabic', 'modern-slim', 'modern-slim-a4'];
        if (!in_array($design, $valid_designs)) {
            return response()->json(['error' => 'Invalid design'], 400);
        }

        // Build sample receipt_details object
        $receipt_details = new \stdClass();

        // Business info
        $receipt_details->logo = null;
        $receipt_details->letter_head = null;
        $receipt_details->header_text = '';
        $receipt_details->display_name = 'Sample Business Name';
        $receipt_details->address = '123 Main Street, Suite 100';
        $receipt_details->contact = '+91 98765 43210';
        $receipt_details->website = 'www.samplebusiness.com';
        $receipt_details->location_custom_fields = '';
        $receipt_details->sub_heading_line1 = 'GSTIN: 29ABCDE1234F1Z5';
        $receipt_details->sub_heading_line2 = '';
        $receipt_details->sub_heading_line3 = '';
        $receipt_details->sub_heading_line4 = '';
        $receipt_details->sub_heading_line5 = '';
        $receipt_details->tax_label1 = 'GSTIN:';
        $receipt_details->tax_info1 = '29ABCDE1234F1Z5';
        $receipt_details->tax_label2 = '';
        $receipt_details->tax_info2 = '';
        $receipt_details->invoice_heading = 'Invoice';
        $receipt_details->invoice_no_prefix = '<b>Invoice No:</b>';
        $receipt_details->invoice_no = 'INV-00125';
        $receipt_details->date_label = 'Date:';
        $receipt_details->invoice_date = date('d/m/Y h:i A');
        $receipt_details->due_date_label = '';
        $receipt_details->due_date = '';
        $receipt_details->sales_person_label = '';
        $receipt_details->sales_person = '';
        $receipt_details->commission_agent_label = '';
        $receipt_details->commission_agent = '';
        $receipt_details->customer_label = 'Customer:';
        $receipt_details->customer_info = 'Walk-In Customer';
        $receipt_details->client_id_label = '';
        $receipt_details->client_id = '';
        $receipt_details->customer_tax_label = '';
        $receipt_details->customer_tax_number = '';
        $receipt_details->customer_custom_fields = '';
        $receipt_details->customer_rp_label = '';
        $receipt_details->customer_total_rp = '';

        // Table labels
        $receipt_details->table_product_label = 'Product';
        $receipt_details->table_qty_label = 'Quantity';
        $receipt_details->table_unit_price_label = 'Unit Price';
        $receipt_details->table_subtotal_label = 'Subtotal';
        $receipt_details->item_discount_label = '';
        $receipt_details->discounted_unit_price_label = '';
        $receipt_details->cat_code_label = '';

        // Sample product lines
        $receipt_details->lines = [
            [
                'image' => null,
                'name' => '16 Medium Global Baby Diapers',
                'product_variation' => '',
                'variation' => '',
                'sub_sku' => 'SKU001',
                'brand' => '',
                'cat_code' => '',
                'product_custom_fields' => '',
                'product_description' => '',
                'sell_line_note' => '',
                'lot_number' => '',
                'lot_number_label' => '',
                'product_expiry' => '',
                'product_expiry_label' => '',
                'warranty_name' => '',
                'warranty_exp_date' => '',
                'warranty_description' => '',
                'quantity' => '1',
                'units' => 'Pc(s)',
                'unit_price_before_discount' => '900.00',
                'unit_price_inc_tax' => '900.00',
                'unit_price_exc_tax' => '900.00',
                'line_discount' => '0.00',
                'line_total' => '900.00',
                'base_unit_multiplier' => 1,
                'base_unit_name' => '',
                'base_unit_price' => '',
                'orig_quantity' => '1',
                'modifiers' => [],
            ],
        ];

        // Show base unit details flag
        $receipt_details->show_base_unit_details = false;

        // Totals
        $receipt_details->subtotal_label = '<b>Subtotal:</b>';
        $receipt_details->subtotal = '900.00';
        $receipt_details->discount_label = '<b>Discount:</b>';
        $receipt_details->discount = '';
        $receipt_details->total_line_discount = '';
        $receipt_details->line_discount_label = '';
        $receipt_details->tax_label = '<b>Tax:</b>';
        $receipt_details->tax = '';
        $receipt_details->total_label = '<b>Total:</b>';
        $receipt_details->total = '900.00';
        $receipt_details->total_in_words = 'Nine Hundred Only';
        $receipt_details->round_off_label = '';
        $receipt_details->round_off = '';
        $receipt_details->round_off_amount = 0;
        $receipt_details->total_exempt_uf = '';
        $receipt_details->total_exempt = '';

        // Quantity/items summary
        $receipt_details->total_quantity_label = '<b>Total Qty:</b>';
        $receipt_details->total_quantity = '1';
        $receipt_details->total_items_label = '<b>Total Items:</b>';
        $receipt_details->total_items = '1';

        // Shipping / Packing
        $receipt_details->shipping_charges = '';
        $receipt_details->shipping_charges_label = '';
        $receipt_details->packing_charge = '';
        $receipt_details->packing_charge_label = '';

        // Payments
        $receipt_details->payments = [
            [
                'method' => 'Cash',
                'amount' => '1,000.00',
                'date' => date('d/m/Y'),
            ],
        ];
        $receipt_details->total_paid_label = '<b>Total Paid:</b>';
        $receipt_details->total_paid = '1,000.00';
        $receipt_details->total_due_label = '';
        $receipt_details->total_due = '';
        $receipt_details->all_bal_label = '';
        $receipt_details->all_due = '';
        $receipt_details->receipt_show_due_breakdown = true;
        $receipt_details->receipt_current_bill_label = 'SUB TOTAL';
        $receipt_details->receipt_previous_due_label = 'Previous Due';
        $receipt_details->receipt_amount_payable_label = 'Amount Payable';
        $receipt_details->receipt_amount_paid_label = 'Amount Paid';
        $receipt_details->receipt_total_due_label = 'Total Due';
        $receipt_details->receipt_current_bill = '900.00';
        $receipt_details->receipt_previous_due = '7,650.00';
        $receipt_details->receipt_amount_payable = '8,550.00';
        $receipt_details->receipt_amount_paid = '1,000.00';
        $receipt_details->receipt_total_due = '7,550.00';
        $receipt_details->change_return_label = '<b>Change Return:</b>';
        $receipt_details->change_return = '0.00';

        // Additional / Service
        $receipt_details->additional_notes = '';
        $receipt_details->footer_text = '<p style="text-align:center;">Thank you for your business!</p>';
        $receipt_details->additional_expenses = [];
        $receipt_details->reward_point_label = '';
        $receipt_details->reward_point_amount = '';

        // Barcode / QR
        $receipt_details->show_barcode = false;
        $receipt_details->show_qr_code = false;
        $receipt_details->qr_code_text = '';

        // Tax summary
        $receipt_details->tax_summary_label = '';
        $receipt_details->taxes = [];
        $receipt_details->hide_price = false;

        // Types of service / Table / Waiter
        $receipt_details->types_of_service = '';
        $receipt_details->types_of_service_label = '';
        $receipt_details->types_of_service_custom_fields = [];
        $receipt_details->table_label = '';
        $receipt_details->table = '';
        $receipt_details->service_staff_label = '';
        $receipt_details->service_staff = '';

        // Repair fields
        $receipt_details->brand_label = '';
        $receipt_details->repair_brand = '';
        $receipt_details->device_label = '';
        $receipt_details->repair_device = '';
        $receipt_details->model_no_label = '';
        $receipt_details->repair_model_no = '';
        $receipt_details->serial_no_label = '';
        $receipt_details->repair_serial_no = '';
        $receipt_details->repair_status_label = '';
        $receipt_details->repair_status = '';
        $receipt_details->repair_warranty_label = '';
        $receipt_details->repair_warranty = '';

        // Shipping custom fields
        $receipt_details->shipping_custom_field_1_label = '';
        $receipt_details->shipping_custom_field_1_value = '';
        $receipt_details->shipping_custom_field_2_label = '';
        $receipt_details->shipping_custom_field_2_value = '';
        $receipt_details->shipping_custom_field_3_label = '';
        $receipt_details->shipping_custom_field_3_value = '';
        $receipt_details->shipping_custom_field_4_label = '';
        $receipt_details->shipping_custom_field_4_value = '';
        $receipt_details->shipping_custom_field_5_label = '';
        $receipt_details->shipping_custom_field_5_value = '';

        // Sale orders
        $receipt_details->sale_orders_invoice_no = '';
        $receipt_details->sale_orders_invoice_date = '';

        // Sell custom fields
        $receipt_details->sell_custom_field_1_label = '';
        $receipt_details->sell_custom_field_1_value = '';
        $receipt_details->sell_custom_field_2_label = '';
        $receipt_details->sell_custom_field_2_value = '';
        $receipt_details->sell_custom_field_3_label = '';
        $receipt_details->sell_custom_field_3_value = '';
        $receipt_details->sell_custom_field_4_label = '';
        $receipt_details->sell_custom_field_4_value = '';

        // Previous balance
        $receipt_details->prev_bal_label = '';
        $receipt_details->prev_bal = '';

        // Credit note
        $receipt_details->cn_heading = '';
        $receipt_details->cn_no_label = '';
        $receipt_details->cn_amount_label = '';

        // Repair checklist (for repair module)
        $receipt_details->repair_checklist = [];
        $receipt_details->repair_activities = [];

        // Pass common_settings for template-level toggles (e.g. DigiPartner branding)
        $receipt_details->common_settings = ['show_digipartner_website' => 1, 'show_digipartner_phone' => 1];

        $template = 'sale_pos.receipts.' . $design;

        $html = view($template, compact('receipt_details'))->render();

        return response()->json(['html' => $html]);
    }

    private function getDesigns()
    {
        return [
            'classic' => __('lang_v1.classic') . ' (' . __('lang_v1.for_normal_printer') . ')',
            'elegant' => __('lang_v1.elegant') . ' (' . __('lang_v1.for_normal_printer') . ')',
            'detailed' => __('lang_v1.detailed') . ' (' . __('lang_v1.for_normal_printer') . ')',
            'columnize-taxes' => __('lang_v1.columnize_taxes') . ' (' . __('lang_v1.for_normal_printer') . ')',
            'slim' => __('lang_v1.slim') . ' (' . __('lang_v1.recomended_for_80mm') . ')',
            'slim2' => __('lang_v1.slim') . ' 2 (' . __('lang_v1.recomended_for_58mm') . ')',
            'english-arabic' => 'English-Arabic (' . __('lang_v1.for_normal_printer') . ')',
            'modern-slim' => 'Modern Slim (' . __('lang_v1.recomended_for_80mm') . ')',
            'modern-slim-a4' => 'Modern Slim A4 (PDF / A4 Printer)',

        ];
    }
}
