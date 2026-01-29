<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Account
 *
 * @property int $id
 * @property int $business_id
 * @property string $name
 * @property string $account_number
 * @property array|null $account_details
 * @property int|null $account_type_id
 * @property string|null $note
 * @property int $created_by
 * @property int $is_closed
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\AccountType|null $account_type
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account notClosed()
 * @method static \Illuminate\Database\Eloquent\Builder|Account onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereIsClosed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Account withoutTrashed()
 */
	class Account extends \Eloquent {}
}

namespace App{
/**
 * App\AccountTransaction
 *
 * @property int $id
 * @property int $account_id
 * @property string $type
 * @property string|null $sub_type
 * @property string $amount
 * @property string|null $reff_no
 * @property \Illuminate\Support\Carbon $operation_date
 * @property int $created_by
 * @property int|null $transaction_id
 * @property int|null $transaction_payment_id
 * @property int|null $transfer_transaction_id
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Transaction|null $transaction
 * @property-read AccountTransaction|null $transfer_transaction
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereOperationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereReffNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereSubType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereTransactionPaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereTransferTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountTransaction withoutTrashed()
 */
	class AccountTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\AccountType
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_account_type_id
 * @property int $business_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read AccountType|null $parent_account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AccountType> $sub_types
 * @property-read int|null $sub_types_count
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereParentAccountTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountType whereUpdatedAt($value)
 */
	class AccountType extends \Eloquent {}
}

namespace App{
/**
 * App\Barcode
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float|null $width
 * @property float|null $height
 * @property float|null $paper_width
 * @property float|null $paper_height
 * @property float|null $top_margin
 * @property float|null $left_margin
 * @property float|null $row_distance
 * @property float|null $col_distance
 * @property int|null $stickers_in_one_row
 * @property int $is_default
 * @property int $is_continuous
 * @property int|null $stickers_in_one_sheet
 * @property int|null $business_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode query()
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereColDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereIsContinuous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereLeftMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode wherePaperHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode wherePaperWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereRowDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereStickersInOneRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereStickersInOneSheet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereTopMargin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Barcode whereWidth($value)
 */
	class Barcode extends \Eloquent {}
}

namespace App{
/**
 * App\Brands
 *
 * @property int $id
 * @property int $business_id
 * @property string $name
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Brands newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brands withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Brands withoutTrashed()
 */
	class Brands extends \Eloquent {}
}

namespace App{
/**
 * App\Business
 *
 * @property int $id
 * @property string $name
 * @property int $currency_id
 * @property string|null $start_date
 * @property string|null $tax_number_1
 * @property string|null $tax_label_1
 * @property string|null $tax_number_2
 * @property string|null $tax_label_2
 * @property string|null $code_label_1
 * @property string|null $code_1
 * @property string|null $code_label_2
 * @property string|null $code_2
 * @property int|null $default_sales_tax
 * @property float $default_profit_percent
 * @property int $owner_id
 * @property string $time_zone
 * @property int $fy_start_month
 * @property string $accounting_method
 * @property string|null $default_sales_discount
 * @property string $sell_price_tax
 * @property string|null $logo
 * @property string|null $sku_prefix
 * @property int $enable_product_expiry
 * @property string $expiry_type
 * @property string $on_product_expiry
 * @property int $stop_selling_before Stop selling expied item n days before expiry
 * @property int $enable_tooltip
 * @property int $purchase_in_diff_currency Allow purchase to be in different currency then the business currency
 * @property int|null $purchase_currency_id
 * @property string $p_exchange_rate
 * @property int $transaction_edit_days
 * @property int $stock_expiry_alert_days
 * @property string|null $keyboard_shortcuts
 * @property string|null $pos_settings
 * @property array $weighing_scale_setting used to store the configuration of weighing scale
 * @property int $enable_brand
 * @property int $enable_category
 * @property int $enable_sub_category
 * @property int $enable_price_tax
 * @property int|null $enable_purchase_status
 * @property int $enable_lot_number
 * @property int|null $default_unit
 * @property int $enable_sub_units
 * @property int $enable_racks
 * @property int $enable_row
 * @property int $enable_position
 * @property int $enable_editing_product_from_purchase
 * @property string|null $sales_cmsn_agnt
 * @property int $item_addition_method
 * @property int $enable_inline_tax
 * @property string $currency_symbol_placement
 * @property array|null $enabled_modules
 * @property string $date_format
 * @property string $time_format
 * @property int $currency_precision
 * @property int $quantity_precision
 * @property array|null $ref_no_prefixes
 * @property int|null $created_by
 * @property int $enable_rp rp is the short form of reward points
 * @property string|null $rp_name rp is the short form of reward points
 * @property string $amount_for_unit_rp rp is the short form of reward points
 * @property string $min_order_total_for_rp rp is the short form of reward points
 * @property int|null $max_rp_per_order rp is the short form of reward points
 * @property string $redeem_amount_per_unit_rp rp is the short form of reward points
 * @property string $min_order_total_for_redeem rp is the short form of reward points
 * @property int|null $min_redeem_point rp is the short form of reward points
 * @property int|null $max_redeem_point rp is the short form of reward points
 * @property int|null $rp_expiry_period rp is the short form of reward points
 * @property string $rp_expiry_type rp is the short form of reward points
 * @property array|null $email_settings
 * @property array|null $sms_settings
 * @property string|null $custom_labels
 * @property array|null $common_settings
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Currency $currency
 * @property-read mixed $business_address
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\BusinessLocation> $locations
 * @property-read int|null $locations_count
 * @property-read \App\User|null $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Printer> $printers
 * @property-read int|null $printers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Business newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business query()
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereAccountingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereAmountForUnitRp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCode1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCode2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCodeLabel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCodeLabel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCommonSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCurrencyPrecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCurrencySymbolPlacement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCustomLabels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDateFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDefaultProfitPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDefaultSalesDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDefaultSalesTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereDefaultUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEmailSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableEditingProductFromPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableInlineTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableLotNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnablePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnablePriceTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableProductExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnablePurchaseStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableRacks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableRp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableSubCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableSubUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnableTooltip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereEnabledModules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereExpiryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereFyStartMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereItemAdditionMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereKeyboardShortcuts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereMaxRedeemPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereMaxRpPerOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereMinOrderTotalForRedeem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereMinOrderTotalForRp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereMinRedeemPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereOnProductExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business wherePExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business wherePosSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business wherePurchaseCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business wherePurchaseInDiffCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereQuantityPrecision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereRedeemAmountPerUnitRp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereRefNoPrefixes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereRpExpiryPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereRpExpiryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereRpName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereSalesCmsnAgnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereSellPriceTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereSkuPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereSmsSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereStockExpiryAlertDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereStopSellingBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTaxLabel1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTaxLabel2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTaxNumber1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTaxNumber2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTimeFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereTransactionEditDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereWeighingScaleSetting($value)
 */
	class Business extends \Eloquent {}
}

namespace App{
/**
 * App\BusinessLocation
 *
 * @property int $id
 * @property int $business_id
 * @property string|null $location_id
 * @property string $name
 * @property string|null $landmark
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $zip_code
 * @property int $invoice_scheme_id
 * @property int|null $sale_invoice_scheme_id
 * @property int $invoice_layout_id
 * @property int|null $sale_invoice_layout_id
 * @property int|null $selling_price_group_id
 * @property int|null $print_receipt_on_invoice
 * @property string $receipt_printer_type
 * @property int|null $printer_id
 * @property string|null $mobile
 * @property string|null $alternate_number
 * @property string|null $email
 * @property string|null $website
 * @property array|null $featured_products
 * @property int $is_active
 * @property string|null $default_payment_accounts
 * @property string|null $custom_field1
 * @property string|null $custom_field2
 * @property string|null $custom_field3
 * @property string|null $custom_field4
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $location_address
 * @property-read \App\SellingPriceGroup|null $price_group
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation active()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereAlternateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereDefaultPaymentAccounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereFeaturedProducts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereInvoiceLayoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereInvoiceSchemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereLandmark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation wherePrintReceiptOnInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation wherePrinterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereReceiptPrinterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereSaleInvoiceLayoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereSaleInvoiceSchemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereSellingPriceGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BusinessLocation whereZipCode($value)
 */
	class BusinessLocation extends \Eloquent {}
}

namespace App{
/**
 * App\CashDenomination
 *
 * @property int $id
 * @property int $business_id
 * @property string $amount
 * @property int $total_count
 * @property string $model_type
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereTotalCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereUpdatedAt($value)
 */
	class CashDenomination extends \Eloquent {}
}

namespace App{
/**
 * App\CashRegister
 *
 * @property int $id
 * @property int $business_id
 * @property int|null $location_id
 * @property int|null $user_id
 * @property string $status
 * @property string|null $closed_at
 * @property string $closing_amount
 * @property int $total_card_slips
 * @property int $total_cheques
 * @property array|null $denominations
 * @property string|null $closing_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\CashRegisterTransaction> $cash_register_transactions
 * @property-read int|null $cash_register_transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereClosingAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereClosingNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereDenominations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereTotalCardSlips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereTotalCheques($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegister whereUserId($value)
 */
	class CashRegister extends \Eloquent {}
}

namespace App{
/**
 * App\CashRegisterTransaction
 *
 * @property int $id
 * @property int $cash_register_id
 * @property string $amount
 * @property string|null $pay_method
 * @property string $type
 * @property string|null $transaction_type
 * @property int|null $transaction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereCashRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction wherePayMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashRegisterTransaction whereUpdatedAt($value)
 */
	class CashRegisterTransaction extends \Eloquent {}
}

namespace App{
/**
 * App\Category
 *
 * @property int $id
 * @property string $name
 * @property int $business_id
 * @property string|null $short_code
 * @property int $parent_id
 * @property int $created_by
 * @property string|null $category_type
 * @property string|null $description
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $sub_categories
 * @property-read int|null $sub_categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category onlyParent()
 * @method static \Illuminate\Database\Eloquent\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCategoryType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereShortCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App{
/**
 * App\Contact
 *
 * @property int $id
 * @property int $business_id
 * @property string $type
 * @property string|null $contact_type
 * @property string|null $land_mark
 * @property string|null $street_name
 * @property string|null $building_number
 * @property string|null $additional_number
 * @property string|null $supplier_business_name
 * @property string|null $name
 * @property string|null $prefix
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $contact_id
 * @property string $contact_status
 * @property string|null $tax_number
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $zip_code
 * @property string|null $dob
 * @property string $mobile
 * @property string|null $landline
 * @property string|null $alternate_number
 * @property int|null $pay_term_number
 * @property string|null $pay_term_type
 * @property string|null $credit_limit
 * @property int $created_by
 * @property string $balance
 * @property int $total_rp rp is the short form of reward points
 * @property int $total_rp_used rp is the short form of reward points
 * @property int $total_rp_expired rp is the short form of reward points
 * @property int $is_default
 * @property string|null $shipping_address
 * @property array|null $shipping_custom_field_details
 * @property int $is_export
 * @property string|null $export_custom_field_1
 * @property string|null $export_custom_field_2
 * @property string|null $export_custom_field_3
 * @property string|null $export_custom_field_4
 * @property string|null $export_custom_field_5
 * @property string|null $export_custom_field_6
 * @property string|null $position
 * @property int|null $customer_group_id
 * @property string|null $custom_field1
 * @property string|null $custom_field2
 * @property string|null $custom_field3
 * @property string|null $custom_field4
 * @property string|null $custom_field5
 * @property string|null $custom_field6
 * @property string|null $custom_field7
 * @property string|null $custom_field8
 * @property string|null $custom_field9
 * @property string|null $custom_field10
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Business $business
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\DocumentAndNote> $documentsAndnote
 * @property-read int|null $documents_andnote_count
 * @property-read mixed $contact_address_array
 * @property-read mixed $contact_address
 * @property-read mixed $full_name
 * @property-read mixed $full_name_with_business
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\User> $userHavingAccess
 * @property-read int|null $user_having_access_count
 * @method static \Illuminate\Database\Eloquent\Builder|Contact active()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlyCustomers()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlyOwnContact()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlySuppliers()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAdditionalNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereAlternateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereBuildingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereContactType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomField9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExportCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExportCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExportCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExportCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExportCustomField5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExportCustomField6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIsExport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLandMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLandline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePayTermNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePayTermType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereShippingCustomFieldDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereStreetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereSupplierBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTaxNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTotalRp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTotalRpExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereTotalRpUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withoutTrashed()
 */
	class Contact extends \Eloquent {}
}

namespace App{
/**
 * App\Currency
 *
 * @property int $id
 * @property string $country
 * @property string $currency
 * @property string $code
 * @property string $symbol
 * @property string $thousand_separator
 * @property string $decimal_separator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDecimalSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereThousandSeparator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 */
	class Currency extends \Eloquent {}
}

namespace App{
/**
 * App\CustomerGroup
 *
 * @property int $id
 * @property int $business_id
 * @property string $name
 * @property float $amount
 * @property string|null $price_calculation_type
 * @property int|null $selling_price_group_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup wherePriceCalculationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereSellingPriceGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerGroup whereUpdatedAt($value)
 */
	class CustomerGroup extends \Eloquent {}
}

namespace App{
/**
 * App\Damage
 *
 * @property int $id
 * @property int|null $business_id
 * @property int $product_id
 * @property int|null $variation_id
 * @property int|null $location_id
 * @property float $quantity
 * @property float $unit_cost
 * @property float $total_cost
 * @property string|null $reason
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User|null $createdBy
 * @property-read \App\BusinessLocation|null $location
 * @property-read \App\Product|null $product
 * @property-read \App\Variation|null $variation
 * @method static \Illuminate\Database\Eloquent\Builder|Damage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Damage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Damage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Damage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereTotalCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereUnitCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage whereVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Damage withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Damage withoutTrashed()
 */
	class Damage extends \Eloquent {}
}

namespace App{
/**
 * App\DashboardConfiguration
 *
 * @property int $id
 * @property int $business_id
 * @property int $created_by
 * @property string $name
 * @property string $color
 * @property string|null $configuration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereConfiguration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DashboardConfiguration whereUpdatedAt($value)
 */
	class DashboardConfiguration extends \Eloquent {}
}

namespace App{
/**
 * App\Discount
 *
 * @property int $id
 * @property string $name
 * @property int $business_id
 * @property int|null $brand_id
 * @property int|null $category_id
 * @property int|null $location_id
 * @property int|null $priority
 * @property string|null $discount_type
 * @property string $discount_amount
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property int $is_active
 * @property string|null $spg Applicable in specified selling price group only. Use of applicable_in_spg column is discontinued
 * @property int|null $applicable_in_cg
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Variation> $variations
 * @property-read int|null $variations_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereApplicableInCg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereSpg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 */
	class Discount extends \Eloquent {}
}

namespace App{
/**
 * App\DocumentAndNote
 *
 * @property int $id
 * @property int $business_id
 * @property int $notable_id
 * @property string $notable_type
 * @property string|null $heading
 * @property string|null $description
 * @property int $is_private
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\User $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notable
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote query()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereIsPrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereNotableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereNotableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentAndNote whereUpdatedAt($value)
 */
	class DocumentAndNote extends \Eloquent {}
}

namespace App{
/**
 * App\ExpenseCategory
 *
 * @property int $id
 * @property string $name
 * @property int $business_id
 * @property string|null $code
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ExpenseCategory> $sub_categories
 * @property-read int|null $sub_categories_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory onlyParent()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpenseCategory withoutTrashed()
 */
	class ExpenseCategory extends \Eloquent {}
}

namespace App{
/**
 * App\GroupSubTax
 *
 * @property int $group_tax_id
 * @property int $tax_id
 * @property-read \App\TaxRate $tax_rate
 * @method static \Illuminate\Database\Eloquent\Builder|GroupSubTax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupSubTax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupSubTax query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupSubTax whereGroupTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupSubTax whereTaxId($value)
 */
	class GroupSubTax extends \Eloquent {}
}

namespace App{
/**
 * App\InvoiceLayout
 *
 * @property int $id
 * @property string $name
 * @property string|null $header_text
 * @property string|null $invoice_no_prefix
 * @property string|null $quotation_no_prefix
 * @property string|null $invoice_heading
 * @property string|null $sub_heading_line1
 * @property string|null $sub_heading_line2
 * @property string|null $sub_heading_line3
 * @property string|null $sub_heading_line4
 * @property string|null $sub_heading_line5
 * @property string|null $invoice_heading_not_paid
 * @property string|null $invoice_heading_paid
 * @property string|null $quotation_heading
 * @property string|null $sub_total_label
 * @property string|null $discount_label
 * @property string|null $tax_label
 * @property string|null $total_label
 * @property string|null $round_off_label
 * @property string|null $total_due_label
 * @property string|null $paid_label
 * @property int $show_client_id
 * @property string|null $client_id_label
 * @property string|null $client_tax_label
 * @property string|null $date_label
 * @property string|null $date_time_format
 * @property int $show_time
 * @property int $show_brand
 * @property int $show_sku
 * @property int $show_cat_code
 * @property int $show_expiry
 * @property int $show_lot
 * @property int $show_image
 * @property int $show_sale_description
 * @property string|null $sales_person_label
 * @property int $show_sales_person
 * @property string|null $table_product_label
 * @property string|null $table_qty_label
 * @property string|null $table_unit_price_label
 * @property string|null $table_subtotal_label
 * @property string|null $cat_code_label
 * @property string|null $logo
 * @property int $show_logo
 * @property int $show_business_name
 * @property int $show_location_name
 * @property int $show_landmark
 * @property int $show_city
 * @property int $show_state
 * @property int $show_zip_code
 * @property int $show_country
 * @property int $show_mobile_number
 * @property int $show_alternate_number
 * @property int $show_email
 * @property int $show_tax_1
 * @property int $show_tax_2
 * @property int $show_barcode
 * @property int $show_payments
 * @property int $show_customer
 * @property string|null $customer_label
 * @property string|null $commission_agent_label
 * @property int $show_commission_agent
 * @property int $show_reward_point
 * @property string|null $highlight_color
 * @property string|null $footer_text
 * @property string|null $module_info
 * @property array|null $common_settings
 * @property int $is_default
 * @property int $business_id
 * @property int $show_letter_head
 * @property string|null $letter_head
 * @property int $show_qr_code
 * @property array|null $qr_code_fields
 * @property string|null $design
 * @property string|null $cn_heading cn = credit note
 * @property string|null $cn_no_label
 * @property string|null $cn_amount_label
 * @property string|null $table_tax_headings
 * @property int $show_previous_bal
 * @property string|null $prev_bal_label
 * @property string|null $change_return_label
 * @property array|null $product_custom_fields
 * @property array|null $contact_custom_fields
 * @property array|null $location_custom_fields
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\BusinessLocation> $locations
 * @property-read int|null $locations_count
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCatCodeLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereChangeReturnLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereClientIdLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereClientTaxLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCnAmountLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCnHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCnNoLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCommissionAgentLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCommonSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereContactCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereCustomerLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereDateLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereDateTimeFormat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereDesign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereDiscountLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereFooterText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereHeaderText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereHighlightColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereInvoiceHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereInvoiceHeadingNotPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereInvoiceHeadingPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereInvoiceNoPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereLetterHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereLocationCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereModuleInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout wherePaidLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout wherePrevBalLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereProductCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereQrCodeFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereQuotationHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereQuotationNoPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereRoundOffLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSalesPersonLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowAlternateNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowCatCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowCommissionAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowLandmark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowLetterHead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowLocationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowLot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowMobileNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowPayments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowPreviousBal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowQrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowRewardPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowSaleDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowSalesPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowTax1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowTax2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereShowZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSubHeadingLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSubHeadingLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSubHeadingLine3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSubHeadingLine4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSubHeadingLine5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereSubTotalLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTableProductLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTableQtyLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTableSubtotalLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTableTaxHeadings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTableUnitPriceLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTaxLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTotalDueLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereTotalLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceLayout whereUpdatedAt($value)
 */
	class InvoiceLayout extends \Eloquent {}
}

namespace App{
/**
 * App\InvoiceScheme
 *
 * @property int $id
 * @property int $business_id
 * @property string $name
 * @property string $scheme_type
 * @property string $number_type
 * @property string|null $prefix
 * @property int|null $start_number
 * @property int $invoice_count
 * @property int|null $total_digits
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereInvoiceCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereNumberType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereSchemeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereStartNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereTotalDigits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceScheme whereUpdatedAt($value)
 */
	class InvoiceScheme extends \Eloquent {}
}

namespace App{
/**
 * App\Media
 *
 * @property int $id
 * @property int $business_id
 * @property string $file_name
 * @property string|null $description
 * @property int|null $uploaded_by
 * @property string $model_type
 * @property string|null $model_media_type
 * @property int $model_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $display_name
 * @property-read mixed $display_path
 * @property-read mixed $display_url
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $mediable
 * @property-read \App\User|null $uploaded_by_user
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelMediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUploadedBy($value)
 */
	class Media extends \Eloquent {}
}

namespace App{
/**
 * App\NotificationTemplate
 *
 * @property int $id
 * @property int $business_id
 * @property string $template_for
 * @property string|null $email_body
 * @property string|null $sms_body
 * @property string|null $whatsapp_text
 * @property string|null $subject
 * @property string|null $cc
 * @property string|null $bcc
 * @property int $auto_send
 * @property int $auto_send_sms
 * @property int $auto_send_wa_notif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereAutoSend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereAutoSendSms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereAutoSendWaNotif($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereBcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereEmailBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereSmsBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereTemplateFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationTemplate whereWhatsappText($value)
 */
	class NotificationTemplate extends \Eloquent {}
}

namespace App{
/**
 * App\PaymentAccount
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAccount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAccount withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentAccount withoutTrashed()
 */
	class PaymentAccount extends \Eloquent {}
}

namespace App{
/**
 * App\Printer
 *
 * @property int $id
 * @property int $business_id
 * @property string $name
 * @property string $connection_type
 * @property string $capability_profile
 * @property string|null $char_per_line
 * @property string|null $ip_address
 * @property string|null $port
 * @property string|null $path
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCapabilityProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCharPerLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereConnectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Printer whereUpdatedAt($value)
 */
	class Printer extends \Eloquent {}
}

namespace App{
/**
 * App\Product
 *
 * @property int $id
 * @property string $name
 * @property int $business_id
 * @property string|null $type
 * @property int|null $unit_id
 * @property int|null $secondary_unit_id
 * @property array|null $sub_unit_ids
 * @property int|null $brand_id
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $tax
 * @property string $tax_type
 * @property int $enable_stock
 * @property string|null $alert_quantity
 * @property string $sku
 * @property string|null $barcode_type
 * @property string|null $expiry_period
 * @property string|null $expiry_period_type
 * @property int $enable_sr_no
 * @property string|null $weight
 * @property string|null $product_custom_field1
 * @property string|null $product_custom_field2
 * @property string|null $product_custom_field3
 * @property string|null $product_custom_field4
 * @property string|null $product_custom_field5
 * @property string|null $product_custom_field6
 * @property string|null $product_custom_field7
 * @property string|null $product_custom_field8
 * @property string|null $product_custom_field9
 * @property string|null $product_custom_field10
 * @property string|null $product_custom_field11
 * @property string|null $product_custom_field12
 * @property string|null $product_custom_field13
 * @property string|null $product_custom_field14
 * @property string|null $product_custom_field15
 * @property string|null $product_custom_field16
 * @property string|null $product_custom_field17
 * @property string|null $product_custom_field18
 * @property string|null $product_custom_field19
 * @property string|null $product_custom_field20
 * @property string|null $image
 * @property string|null $product_description
 * @property int $created_by
 * @property int|null $preparation_time_in_minutes
 * @property int|null $warranty_id
 * @property int $is_inactive
 * @property int $not_for_selling
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Brands|null $brand
 * @property-read \App\Category|null $category
 * @property-read string $image_path
 * @property-read string $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $modifier_products
 * @property-read int|null $modifier_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $modifier_sets
 * @property-read int|null $modifier_sets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\BusinessLocation> $product_locations
 * @property-read int|null $product_locations_count
 * @property-read \App\TaxRate|null $product_tax
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\ProductVariation> $product_variations
 * @property-read int|null $product_variations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\PurchaseLine> $purchase_lines
 * @property-read int|null $purchase_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\ProductRack> $rack_details
 * @property-read int|null $rack_details_count
 * @property-read \App\Unit|null $second_unit
 * @property-read \App\Category|null $sub_category
 * @property-read \App\Unit|null $unit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Variation> $variations
 * @property-read int|null $variations_count
 * @property-read \App\Warranty|null $warranty
 * @method static \Illuminate\Database\Eloquent\Builder|Product active()
 * @method static \Illuminate\Database\Eloquent\Builder|Product forLocation($location_id)
 * @method static \Illuminate\Database\Eloquent\Builder|Product inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product productForSales()
 * @method static \Illuminate\Database\Eloquent\Builder|Product productNotForSales()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAlertQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcodeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEnableSrNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEnableStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExpiryPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExpiryPeriodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsInactive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNotForSelling($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePreparationTimeInMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField13($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField14($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField15($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField16($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField17($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField18($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField19($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField20($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductCustomField9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSecondaryUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSubUnitIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTaxType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWarrantyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 */
	class Product extends \Eloquent {}
}

namespace App{
/**
 * App\ProductRack
 *
 * @property int $id
 * @property int $business_id
 * @property int $location_id
 * @property int $product_id
 * @property string|null $rack
 * @property string|null $row
 * @property string|null $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereRack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereRow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRack whereUpdatedAt($value)
 */
	class ProductRack extends \Eloquent {}
}

namespace App{
/**
 * App\ProductVariation
 *
 * @property int $id
 * @property int|null $variation_template_id
 * @property string $name
 * @property int $product_id
 * @property int $is_dummy
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\VariationTemplate|null $variation_template
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Variation> $variations
 * @property-read int|null $variations_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereIsDummy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariation whereVariationTemplateId($value)
 */
	class ProductVariation extends \Eloquent {}
}

namespace App{
/**
 * App\PurchaseLine
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $product_id
 * @property int $variation_id
 * @property float $quantity
 * @property string $secondary_unit_quantity
 * @property string $pp_without_discount Purchase price before inline discounts
 * @property string $discount_percent Inline discount percentage
 * @property string $purchase_price
 * @property string $purchase_price_inc_tax
 * @property string $item_tax Tax for one quantity
 * @property int|null $tax_id
 * @property int|null $purchase_requisition_line_id
 * @property int|null $purchase_order_line_id
 * @property string $quantity_sold Quanity sold from this purchase line
 * @property string $quantity_adjusted Quanity adjusted in stock adjustment from this purchase line
 * @property string $quantity_returned
 * @property string $po_quantity_purchased
 * @property string $mfg_quantity_used
 * @property string|null $mfg_date
 * @property string|null $exp_date
 * @property string|null $lot_number
 * @property int|null $sub_unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $quantity_remaining
 * @property-read float $quantity_used
 * @property-read \App\TaxRate|null $line_tax
 * @property-read \App\Product $product
 * @property-read PurchaseLine|null $purchase_order_line
 * @property-read PurchaseLine|null $purchase_requisition_line
 * @property-read \App\Unit|null $sub_unit
 * @property-read \App\Transaction $transaction
 * @property-read \App\Variation $variations
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereExpDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereItemTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereLotNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereMfgDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereMfgQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine wherePoQuantityPurchased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine wherePpWithoutDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine wherePurchaseOrderLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine wherePurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine wherePurchasePriceIncTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine wherePurchaseRequisitionLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereQuantityAdjusted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereQuantityReturned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereQuantitySold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereSecondaryUnitQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereSubUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseLine whereVariationId($value)
 */
	class PurchaseLine extends \Eloquent {}
}

namespace App{
/**
 * App\ReferenceCount
 *
 * @property int $id
 * @property string $ref_type
 * @property int $ref_count
 * @property int $business_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount whereRefCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount whereRefType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReferenceCount whereUpdatedAt($value)
 */
	class ReferenceCount extends \Eloquent {}
}

namespace App\Restaurant{
/**
 * App\Restaurant\Booking
 *
 * @property int $id
 * @property int $contact_id
 * @property int|null $waiter_id
 * @property int|null $table_id
 * @property int|null $correspondent_id
 * @property int $business_id
 * @property int $location_id
 * @property string $booking_start
 * @property string $booking_end
 * @property int $created_by
 * @property string $booking_status
 * @property string|null $booking_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Business $business
 * @property-read \App\User|null $correspondent
 * @property-read \App\Contact $customer
 * @property-read \App\BusinessLocation|null $location
 * @property-read \App\Restaurant\ResTable|null $table
 * @property-read \App\User|null $waiter
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookingEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookingNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookingStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBookingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCorrespondentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereWaiterId($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Restaurant{
/**
 * App\Restaurant\ResTable
 *
 * @property int $id
 * @property int $business_id
 * @property int $location_id
 * @property string $name
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ResTable withoutTrashed()
 */
	class ResTable extends \Eloquent {}
}

namespace App{
/**
 * App\SellingPriceGroup
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $business_id
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup active()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SellingPriceGroup withoutTrashed()
 */
	class SellingPriceGroup extends \Eloquent {}
}

namespace App{
/**
 * App\StockAdjustmentLine
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $product_id
 * @property int $variation_id
 * @property string $quantity
 * @property string $secondary_unit_quantity
 * @property string|null $unit_price Last purchase unit price
 * @property int|null $removed_purchase_line
 * @property int|null $lot_no_line_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\PurchaseLine|null $lot_details
 * @property-read \App\Variation $variation
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereLotNoLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereRemovedPurchaseLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereSecondaryUnitQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockAdjustmentLine whereVariationId($value)
 */
	class StockAdjustmentLine extends \Eloquent {}
}

namespace App{
/**
 * App\System
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|System newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|System newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|System query()
 * @method static \Illuminate\Database\Eloquent\Builder|System whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|System whereValue($value)
 */
	class System extends \Eloquent {}
}

namespace App{
/**
 * App\TaxRate
 *
 * @property int $id
 * @property int $business_id
 * @property string $name
 * @property float $amount
 * @property int $is_tax_group
 * @property int $for_tax_group
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TaxRate> $sub_taxes
 * @property-read int|null $sub_taxes_count
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate excludeForTaxGroup()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereForTaxGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereIsTaxGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TaxRate withoutTrashed()
 */
	class TaxRate extends \Eloquent {}
}

namespace App{
/**
 * App\Transaction
 *
 * @property int $id
 * @property int $business_id
 * @property int|null $location_id
 * @property int $is_kitchen_order
 * @property int|null $res_table_id fields to restaurant module
 * @property int|null $res_waiter_id fields to restaurant module
 * @property string|null $res_order_status
 * @property string|null $type
 * @property string|null $sub_type
 * @property string $status
 * @property string|null $sub_status
 * @property int $is_quotation
 * @property string|null $payment_status
 * @property string|null $adjustment_type
 * @property int|null $contact_id
 * @property int|null $customer_group_id used to add customer group while selling
 * @property string|null $invoice_no
 * @property string|null $ref_no
 * @property string|null $source
 * @property string|null $subscription_no
 * @property string|null $subscription_repeat_on
 * @property string $transaction_date
 * @property string $total_before_tax Total before the purchase/invoice tax, this includeds the indivisual product tax
 * @property int|null $tax_id
 * @property string $tax_amount
 * @property string|null $discount_type
 * @property string|null $discount_amount
 * @property int $rp_redeemed rp is the short form of reward points
 * @property string $rp_redeemed_amount rp is the short form of reward points
 * @property string|null $shipping_details
 * @property string|null $shipping_address
 * @property string|null $delivery_date
 * @property string|null $shipping_status
 * @property string|null $delivered_to
 * @property int|null $delivery_person
 * @property string $shipping_charges
 * @property string|null $shipping_custom_field_1
 * @property string|null $shipping_custom_field_2
 * @property string|null $shipping_custom_field_3
 * @property string|null $shipping_custom_field_4
 * @property string|null $shipping_custom_field_5
 * @property string|null $additional_notes
 * @property string|null $staff_note
 * @property int $is_export
 * @property array|null $export_custom_fields_info
 * @property string $round_off_amount Difference of rounded total and actual total
 * @property string|null $additional_expense_key_1
 * @property string $additional_expense_value_1
 * @property string|null $additional_expense_key_2
 * @property string $additional_expense_value_2
 * @property string|null $additional_expense_key_3
 * @property string $additional_expense_value_3
 * @property string|null $additional_expense_key_4
 * @property string $additional_expense_value_4
 * @property string $final_total
 * @property int|null $expense_category_id
 * @property int|null $expense_sub_category_id
 * @property int|null $expense_for
 * @property int|null $commission_agent
 * @property string|null $document
 * @property int $is_direct_sale
 * @property int $is_suspend
 * @property string $exchange_rate
 * @property string|null $total_amount_recovered Used for stock adjustment.
 * @property int|null $transfer_parent_id
 * @property int|null $return_parent_id
 * @property int|null $opening_stock_product_id
 * @property int $created_by
 * @property array|null $purchase_requisition_ids
 * @property string|null $prefer_payment_method
 * @property int|null $prefer_payment_account
 * @property array|null $sales_order_ids
 * @property array|null $purchase_order_ids
 * @property string|null $custom_field_1
 * @property string|null $custom_field_2
 * @property string|null $custom_field_3
 * @property string|null $custom_field_4
 * @property int|null $import_batch
 * @property string|null $import_time
 * @property int|null $types_of_service_id
 * @property string|null $packing_charge
 * @property string|null $packing_charge_type
 * @property string|null $service_custom_field_1
 * @property string|null $service_custom_field_2
 * @property string|null $service_custom_field_3
 * @property string|null $service_custom_field_4
 * @property string|null $service_custom_field_5
 * @property string|null $service_custom_field_6
 * @property int $is_created_from_api
 * @property int $rp_earned rp is the short form of reward points
 * @property string|null $order_addresses
 * @property int $is_recurring
 * @property float|null $recur_interval
 * @property string|null $recur_interval_type
 * @property int|null $recur_repetitions
 * @property string|null $recur_stopped_on
 * @property int|null $recur_parent_id
 * @property string|null $invoice_token
 * @property int|null $pay_term_number
 * @property string|null $pay_term_type
 * @property int|null $selling_price_group_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $due_date
 * @property-read \App\Business $business
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\CashRegisterTransaction> $cash_register_payments
 * @property-read int|null $cash_register_payments_count
 * @property-read \App\Contact|null $contact
 * @property-read \App\User|null $delivery_person_user
 * @property-read mixed $document_name
 * @property-read mixed $document_path
 * @property-read mixed $log_properties
 * @property-read \App\BusinessLocation|null $location
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\TransactionPayment> $payment_lines
 * @property-read int|null $payment_lines_count
 * @property-read \App\Account|null $preferredAccount
 * @property-read \App\SellingPriceGroup|null $price_group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\PurchaseLine> $purchase_lines
 * @property-read int|null $purchase_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Transaction> $recurring_invoices
 * @property-read int|null $recurring_invoices_count
 * @property-read Transaction|null $recurring_parent
 * @property-read Transaction|null $return_parent
 * @property-read Transaction|null $return_parent_sell
 * @property-read \App\User|null $sale_commission_agent
 * @property-read \App\User $sales_person
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\TransactionSellLine> $sell_lines
 * @property-read int|null $sell_lines_count
 * @property-read \App\User|null $service_staff
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\StockAdjustmentLine> $stock_adjustment_lines
 * @property-read int|null $stock_adjustment_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Transaction> $subscription_invoices
 * @property-read int|null $subscription_invoices_count
 * @property-read \App\Restaurant\ResTable|null $table
 * @property-read \App\TaxRate|null $tax
 * @property-read \App\User|null $transaction_for
 * @property-read \App\TypesOfService|null $types_of_service
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction overDue()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseKey1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseKey2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseKey3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseKey4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseValue1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseValue2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseValue3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalExpenseValue4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdditionalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAdjustmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCommissionAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomerGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDeliveredTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDeliveryPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereExchangeRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereExpenseCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereExpenseFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereExpenseSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereExportCustomFieldsInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFinalTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereImportBatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereImportTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereInvoiceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereInvoiceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsCreatedFromApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsDirectSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsExport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsKitchenOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsQuotation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereIsSuspend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOpeningStockProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOrderAddresses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePackingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePackingChargeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePayTermNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePayTermType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePreferPaymentAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePreferPaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePurchaseOrderIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePurchaseRequisitionIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRecurInterval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRecurIntervalType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRecurParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRecurRepetitions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRecurStoppedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRefNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereResOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereResTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereResWaiterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereReturnParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRoundOffAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRpEarned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRpRedeemed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereRpRedeemedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSalesOrderIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSellingPriceGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCustomField5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereServiceCustomField6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingCharges($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingCustomField5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereShippingStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStaffNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSubStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSubType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSubscriptionNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSubscriptionRepeatOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTotalAmountRecovered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTotalBeforeTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransferParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTypesOfServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App{
/**
 * App\TransactionPayment
 *
 * @property int $id
 * @property int|null $transaction_id
 * @property int|null $business_id
 * @property int $is_return Used during sales to return the change
 * @property string $amount
 * @property string|null $method
 * @property string|null $payment_type
 * @property string|null $transaction_no
 * @property string|null $card_transaction_number
 * @property string|null $card_number
 * @property string|null $card_type
 * @property string|null $card_holder_name
 * @property string|null $card_month
 * @property string|null $card_year
 * @property string|null $card_security
 * @property string|null $cheque_number
 * @property string|null $cheque_issue_date
 * @property string|null $cheque_passing_date
 * @property string|null $cheque_bank_name
 * @property string|null $cheque_status
 * @property string|null $bank_account_number
 * @property string|null $paid_on
 * @property int|null $created_by
 * @property int $paid_through_link
 * @property string|null $gateway
 * @property int $is_advance
 * @property int|null $payment_for stores the contact id
 * @property int|null $parent_id
 * @property string|null $note
 * @property string|null $document
 * @property string|null $payment_ref_no
 * @property int|null $account_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionPayment> $child_payments
 * @property-read int|null $child_payments_count
 * @property-read \App\User|null $created_user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\CashDenomination> $denominations
 * @property-read int|null $denominations_count
 * @property-read mixed $document_name
 * @property-read mixed $document_path
 * @property-read \App\Account|null $payment_account
 * @property-read \App\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardSecurity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardTransactionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCardYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereChequeBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereChequeIssueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereChequeNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereChequePassingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereChequeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereGateway($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereIsAdvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereIsReturn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment wherePaidOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment wherePaidThroughLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment wherePaymentFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment wherePaymentRefNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereTransactionNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionPayment whereUpdatedAt($value)
 */
	class TransactionPayment extends \Eloquent {}
}

namespace App{
/**
 * App\TransactionSellLine
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $product_id
 * @property int $variation_id
 * @property float $quantity
 * @property string $secondary_unit_quantity
 * @property string $quantity_returned
 * @property string $unit_price_before_discount
 * @property string|null $unit_price Sell price excluding tax
 * @property string|null $line_discount_type
 * @property string $line_discount_amount
 * @property string|null $unit_price_inc_tax Sell price including tax
 * @property string $item_tax Tax for one quantity
 * @property int|null $tax_id
 * @property int|null $discount_id
 * @property int|null $lot_no_line_id
 * @property string|null $sell_line_note
 * @property int|null $so_line_id
 * @property string $so_quantity_invoiced
 * @property int|null $res_service_staff_id
 * @property string|null $res_line_order_status
 * @property int|null $parent_sell_line_id
 * @property string $children_type Type of children for the parent, like modifier or combo
 * @property int|null $sub_unit_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\TaxRate|null $line_tax
 * @property-read \App\PurchaseLine|null $lot_details
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TransactionSellLine> $modifiers
 * @property-read int|null $modifiers_count
 * @property-read \App\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\TransactionSellLinesPurchaseLines> $sell_line_purchase_lines
 * @property-read int|null $sell_line_purchase_lines_count
 * @property-read \App\User|null $service_staff
 * @property-read TransactionSellLine|null $so_line
 * @property-read \App\Unit|null $sub_unit
 * @property-read \App\Transaction $transaction
 * @property-read \App\Variation $variations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Warranty> $warranties
 * @property-read int|null $warranties_count
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereChildrenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereItemTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereLineDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereLineDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereLotNoLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereParentSellLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereQuantityReturned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereResLineOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereResServiceStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereSecondaryUnitQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereSellLineNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereSoLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereSoQuantityInvoiced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereSubUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereTaxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereUnitPriceBeforeDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereUnitPriceIncTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLine whereVariationId($value)
 */
	class TransactionSellLine extends \Eloquent {}
}

namespace App{
/**
 * App\TransactionSellLinesPurchaseLines
 *
 * @property int $id
 * @property int|null $sell_line_id id from transaction_sell_lines
 * @property int|null $stock_adjustment_line_id id from stock_adjustment_lines
 * @property int $purchase_line_id id from purchase_lines
 * @property string $quantity
 * @property string $qty_returned
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\PurchaseLine|null $purchase_line
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines query()
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines wherePurchaseLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereQtyReturned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereSellLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereStockAdjustmentLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TransactionSellLinesPurchaseLines whereUpdatedAt($value)
 */
	class TransactionSellLinesPurchaseLines extends \Eloquent {}
}

namespace App{
/**
 * App\TypesOfService
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $business_id
 * @property array|null $location_price_group
 * @property string|null $packing_charge
 * @property string|null $packing_charge_type
 * @property int $enable_custom_fields
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService query()
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereEnableCustomFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereLocationPriceGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService wherePackingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService wherePackingChargeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TypesOfService whereUpdatedAt($value)
 */
	class TypesOfService extends \Eloquent {}
}

namespace App{
/**
 * App\Unit
 *
 * @property int $id
 * @property int $business_id
 * @property string $actual_name
 * @property string $short_name
 * @property int $allow_decimal
 * @property int|null $base_unit_id
 * @property string|null $base_unit_multiplier
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Unit|null $base_unit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Unit> $sub_units
 * @property-read int|null $sub_units_count
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereActualName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereAllowDecimal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereBaseUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereBaseUnitMultiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit withoutTrashed()
 */
	class Unit extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $user_type
 * @property string|null $surname
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property string|null $email
 * @property string|null $password
 * @property string $language
 * @property string|null $contact_no
 * @property string|null $address
 * @property string|null $remember_token
 * @property int|null $business_id
 * @property string|null $available_at Service staff avilable at. Calculated from product preparation_time_in_minutes
 * @property string|null $paused_at Service staff available time paused at, Will be nulled on resume.
 * @property string|null $max_sales_discount_percent
 * @property int $allow_login
 * @property string $status
 * @property int $is_enable_service_staff_pin
 * @property string|null $service_staff_pin
 * @property int|null $crm_contact_id
 * @property int $is_cmmsn_agnt
 * @property string $cmmsn_percent
 * @property int $selected_contacts
 * @property string|null $dob
 * @property string|null $gender
 * @property string|null $marital_status
 * @property string|null $blood_group
 * @property string|null $contact_number
 * @property string|null $alt_number
 * @property string|null $family_number
 * @property string|null $fb_link
 * @property string|null $twitter_link
 * @property string|null $social_media_1
 * @property string|null $social_media_2
 * @property string|null $permanent_address
 * @property string|null $current_address
 * @property string|null $guardian_name
 * @property string|null $custom_field_1
 * @property string|null $custom_field_2
 * @property string|null $custom_field_3
 * @property string|null $custom_field_4
 * @property string|null $bank_details
 * @property string|null $id_proof_name
 * @property string|null $id_proof_number
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Business|null $business
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Contact> $contactAccess
 * @property-read int|null $contact_access_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\DocumentAndNote> $documentsAndnote
 * @property-read int|null $documents_andnote_count
 * @property-read string $image_url
 * @property-read mixed $role_name
 * @property-read string $user_full_name
 * @property-read \App\Media|null $media
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyPermittedLocations()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User user()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAllowLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAltNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvailableAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBankDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCmmsnPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereContactNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCrmContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomField1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomField2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomField3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCustomField4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFamilyNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFbLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGuardianName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdProofName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdProofNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsCmmsnAgnt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsEnableServiceStaffPin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaxSalesDiscountPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePausedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermanentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSelectedContacts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereServiceStaffPin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialMedia1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialMedia2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwitterLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\UserContactAccess
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserContactAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserContactAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserContactAccess query()
 */
	class UserContactAccess extends \Eloquent {}
}

namespace App{
/**
 * App\Variation
 *
 * @property int $id
 * @property string $name
 * @property int $product_id
 * @property string|null $sub_sku
 * @property int $product_variation_id
 * @property int|null $variation_value_id
 * @property string|null $default_purchase_price
 * @property string $dpp_inc_tax
 * @property string $profit_percent
 * @property string|null $default_sell_price
 * @property string|null $sell_price_inc_tax Sell price including tax
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property array|null $combo_variations Contains the combo variation details
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\VariationGroupPrice> $group_prices
 * @property-read int|null $group_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Product $product
 * @property-read \App\ProductVariation $product_variation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\TransactionSellLine> $sell_lines
 * @property-read int|null $sell_lines_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\VariationLocationDetails> $variation_location_details
 * @property-read int|null $variation_location_details_count
 * @method static \Illuminate\Database\Eloquent\Builder|Variation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Variation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereComboVariations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereDefaultPurchasePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereDefaultSellPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereDppIncTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereProductVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereProfitPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereSellPriceIncTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereSubSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation whereVariationValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variation withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Variation withoutTrashed()
 */
	class Variation extends \Eloquent {}
}

namespace App{
/**
 * App\VariationGroupPrice
 *
 * @property int $id
 * @property int $variation_id
 * @property int $price_group_id
 * @property string $price_inc_tax
 * @property string $price_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $calculated_price
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice wherePriceGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice wherePriceIncTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice wherePriceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationGroupPrice whereVariationId($value)
 */
	class VariationGroupPrice extends \Eloquent {}
}

namespace App{
/**
 * App\VariationLocationDetails
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_variation_id id from product_variations table
 * @property int $variation_id
 * @property int $location_id
 * @property string $qty_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereProductVariationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereQtyAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationLocationDetails whereVariationId($value)
 */
	class VariationLocationDetails extends \Eloquent {}
}

namespace App{
/**
 * App\VariationTemplate
 *
 * @property int $id
 * @property string $name
 * @property int $business_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\VariationValueTemplate> $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationTemplate whereUpdatedAt($value)
 */
	class VariationTemplate extends \Eloquent {}
}

namespace App{
/**
 * App\VariationValueTemplate
 *
 * @property int $id
 * @property string $name
 * @property int $variation_template_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\VariationTemplate $variationTemplate
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariationValueTemplate whereVariationTemplateId($value)
 */
	class VariationValueTemplate extends \Eloquent {}
}

namespace App{
/**
 * App\Warranty
 *
 * @property int $id
 * @property string $name
 * @property int $business_id
 * @property string|null $description
 * @property int $duration
 * @property string $duration_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $display_name
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereDurationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warranty whereUpdatedAt($value)
 */
	class Warranty extends \Eloquent {}
}

