<?php

namespace App\Http\Controllers;

use App\Damage;
use App\Product;
use App\VariationLocationDetails;
use App\ExpenseCategory;
use App\Transaction;
use App\Account;
use App\AccountTransaction;
use App\Utils\CashRegisterUtil;
use App\Utils\TransactionUtil;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DamageController extends Controller
{
    protected $transactionUtil;

    protected $cashRegisterUtil;

    public function __construct(TransactionUtil $transactionUtil, CashRegisterUtil $cashRegisterUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->cashRegisterUtil = $cashRegisterUtil;
    }

    public function index(Request $request)
    {
        // This view is just the add form; damages are viewed in the list() method
        $business_id = $request->session()->get('user.business_id');
        $payment_types = $this->transactionUtil->payment_types(null, false, $business_id);

        unset($payment_types['advance']);

        return view('damages.index')->with(compact('payment_types'));
    }

    /**
     * AJAX product search for autocomplete (returns up to 15 matches starting with query)
     */
    public function searchProducts(Request $request)
    {
        $q = $request->get('q');
        $business_id = $request->session()->get('user.business_id');

        if (empty($q)) {
            return response()->json([]);
        }

        $products = \App\Product::where('business_id', $business_id)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', $q . '%')
                    ->orWhere('sku', 'like', $q . '%');
            })
            ->select('id', 'name', 'sku')
            ->orderBy('name')
            ->limit(15)
            ->get();

        $results = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'text' => $p->name . ($p->sku ? ' (' . $p->sku . ')' : ''),
            ];
        });

        return response()->json($results);
    }

    /**
     * Return product details for a given product id to prefill the form.
     */
    public function productDetails($id, Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        $product = \App\Product::with(['product_locations', 'variations'])->where('business_id', $business_id)->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $default_variation = $product->variations->first();
        $default_location = $product->product_locations->first();

        $unit_cost = null;
        if ($default_variation) {
            $unit_cost = $default_variation->dpp_inc_tax ?? $default_variation->dpp ?? null;
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'default_variation_id' => $default_variation ? $default_variation->id : null,
            'default_location_id' => $default_location ? $default_location->id : null,
            'unit_cost' => $unit_cost,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_id' => 'nullable|integer',
            // accept either ID or name (text) from the form
            'product_id' => 'required|string',
            'variation_id' => 'nullable|string',
            'location_id' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.0001',
            'unit_cost' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        // Resolve product_id (allow name or ID). Prefer explicit hidden numeric `product_id`.
        $product = null;
        $provided_product_id = $data['product_id'] ?? null;
        $product_search = $request->input('product_search');

        if (!empty($provided_product_id) && is_numeric($provided_product_id)) {
            $product = Product::find((int) $provided_product_id);
        } elseif (!empty($product_search)) {
            $product = Product::where('business_id', $request->session()->get('user.business_id'))
                ->where(function ($q) use ($product_search) {
                    $q->where('name', $product_search)
                        ->orWhere('sku', $product_search);
                })->first();
        } elseif (!empty($provided_product_id)) {
            // provided value might be a name
            $product = Product::where('business_id', $request->session()->get('user.business_id'))
                ->where(function ($q) use ($provided_product_id) {
                    $q->where('name', $provided_product_id)
                        ->orWhere('sku', $provided_product_id);
                })->first();
        }

        if (!$product) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => ['product_id' => ['Product not found']]], 422);
            }

            return redirect()->back()->withErrors(['product_id' => 'Product not found'])->withInput();
        }

        $data['product_id'] = $product->id;

        // Resolve variation_id if provided
        if (!empty($data['variation_id'])) {
            if (is_numeric($data['variation_id'])) {
                $data['variation_id'] = (int) $data['variation_id'];
            } else {
                // try to find variation by name
                $variation = \App\Variation::where('name', $data['variation_id'])->first();
                $data['variation_id'] = $variation ? $variation->id : null;
            }
        } else {
            $data['variation_id'] = null;
        }

        // Resolve location_id if provided
        if (!empty($data['location_id'])) {
            if (is_numeric($data['location_id'])) {
                $data['location_id'] = (int) $data['location_id'];
            } else {
                $location = \App\BusinessLocation::where('name', $data['location_id'])->first();
                $data['location_id'] = $location ? $location->id : null;
            }
        } else {
            $data['location_id'] = null;
        }

        // Enforce backend cost price calculation
        if (!empty($data['variation_id'])) {
            $variation = \App\Variation::find($data['variation_id']);
            $data['unit_cost'] = $variation->dpp_inc_tax ?? $variation->dpp ?? 0;
        } elseif (!empty($data['product_id'])) {
            $product = Product::find($data['product_id']);
            $variation = $product->variations->first();
            $data['unit_cost'] = $variation->dpp_inc_tax ?? $variation->dpp ?? 0;
            $data['variation_id'] = $variation->id;
        }

        $data['total_cost'] = $data['quantity'] * $data['unit_cost'];
        $data['created_by'] = Auth::id() ?: null;
        $data['business_id'] = $request->session()->get('user.business_id');

        $damage_expense_category_name = 'Damaged Goods Expense';
        $damage_expense_category_code = 'AUTO_DAMAGE_EXPENSE';

        try {
            DB::beginTransaction();

            $damage = Damage::create($data);

            // Decrement stock in variation_location_details if product has enable_stock.
            $product = Product::find($data['product_id']);
            if ($product && $product->enable_stock == 1) {
                $vld = VariationLocationDetails::where('product_id', $data['product_id'])
                    ->where('variation_id', $data['variation_id'])
                    ->where('location_id', $data['location_id'])
                    ->first();

                if ($vld) {
                    $vld->qty_available = $vld->qty_available - $data['quantity'];
                    $vld->save();
                }
            }

            // Find or create a stable expense category used for damage losses.
            $expense_category = ExpenseCategory::where('business_id', $data['business_id'])
                ->where('code', $damage_expense_category_code)
                ->first();

            if (!$expense_category) {
                $expense_category = ExpenseCategory::where('business_id', $data['business_id'])
                    ->whereRaw('LOWER(name) = ?', [strtolower($damage_expense_category_name)])
                    ->first();
            }

            if (!$expense_category) {
                $expense_category = ExpenseCategory::create([
                    'business_id' => $data['business_id'],
                    'name' => $damage_expense_category_name,
                    'code' => $damage_expense_category_code,
                    'created_by' => $data['created_by'],
                ]);
            } elseif (empty($expense_category->code)) {
                $expense_category->code = $damage_expense_category_code;
                $expense_category->save();
            }

            $ref_count = $this->transactionUtil->setAndGetReferenceCount('expense', $data['business_id']);
            $ref_no = $this->transactionUtil->generateReferenceNumber('expense', $ref_count, $data['business_id']);

            $expense_transaction = Transaction::create([
                'business_id' => $data['business_id'],
                'location_id' => $data['location_id'],
                'type' => 'expense',
                'status' => 'final',
                'payment_status' => 'due',
                'expense_category_id' => $expense_category->id,
                'transaction_date' => now(),
                'total_before_tax' => $data['total_cost'],
                'final_total' => $data['total_cost'],
                'additional_notes' => 'Damage #' . $damage->id . ': ' . ($data['reason'] ?? ''),
                'ref_no' => $ref_no,
                'created_by' => $data['created_by'],
            ]);

            if ($data['payment_method'] !== 'due') {
                $payment = [
                    'amount' => $data['total_cost'],
                    'method' => $data['payment_method'],
                    'paid_on' => now()->toDateTimeString(),
                ];

                if ($data['payment_method'] === 'cheque') {
                    $payment['cheque_status'] = 'pending';
                }

                $this->transactionUtil->createOrUpdatePaymentLines(
                    $expense_transaction,
                    [$payment],
                    $data['business_id'],
                    $data['created_by'],
                    false
                );

                $this->transactionUtil->updatePaymentStatus($expense_transaction->id, $expense_transaction->final_total);

                if ($data['payment_method'] !== 'cheque') {
                    $this->cashRegisterUtil->addSellPayments($expense_transaction, [$payment]);
                }
            }

            DB::commit();

            \Log::info('Expense transaction created for damage', [
                'damage_id' => $damage->id,
                'transaction_id' => $expense_transaction->id,
                'amount' => $data['total_cost'],
                'payment_method' => $data['payment_method'],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to store damage with expense transaction', [
                'message' => $e->getMessage(),
                'business_id' => $data['business_id'] ?? null,
                'product_id' => $data['product_id'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ], 500);
            }

            return redirect()->back()->withErrors(['damage' => __('messages.something_went_wrong')])->withInput();
        }


        // If AJAX request, return the created damage data for frontend update
        if ($request->ajax()) {
            $damage->load('product');

            return response()->json([
                'success' => true,
                'msg' => 'Damage recorded successfully.',
                'damage' => [
                    'id' => $damage->id,
                    'created_at' => $damage->created_at->toDateTimeString(),
                    'product_name' => optional($damage->product)->name ?? __('lang_v1.unknown'),
                    'quantity' => (float) $damage->quantity,
                    'unit_cost' => (float) $damage->unit_cost,
                    'total_cost' => (float) $damage->total_cost,
                    'reason' => $damage->reason,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Damage recorded successfully.');
    }

    /**
     * Show the damages list view
     */
    public function list(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        $business_locations = \App\BusinessLocation::forDropdown($business_id, true);

        $products = \App\Product::where('business_id', $business_id)
            ->pluck('name', 'id')
            ->prepend(__('lang_v1.all'), '');

        return view('damages.list')
            ->with(compact('business_locations', 'products'));
    }

    /**
     * Provide data for DataTables AJAX request
     */
    public function listData(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        $damages = Damage::leftJoin('products', 'damages.product_id', '=', 'products.id')
            ->leftJoin('variations', 'damages.variation_id', '=', 'variations.id')
            ->leftJoin('business_locations', 'damages.location_id', '=', 'business_locations.id')
            ->leftJoin('users', 'damages.created_by', '=', 'users.id')
            ->where('damages.business_id', $business_id);

        // Apply location filter
        if ($request->has('location_id') && !empty($request->location_id)) {
            $damages->where('damages.location_id', $request->location_id);
        }

        // Apply date range filter
        if ($request->has('start_date') && !empty($request->start_date)) {
            $damages->whereDate('damages.created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $damages->whereDate('damages.created_at', '<=', $request->end_date);
        }

        // Apply product filter
        if ($request->has('product_id') && !empty($request->product_id)) {
            $damages->where('damages.product_id', $request->product_id);
        }

        $damages
            ->select(
                'damages.id',
                'damages.created_at',
                'damages.quantity',
                'damages.unit_cost',
                'damages.total_cost',
                'damages.reason',
                'products.name as product_name',
                'variations.name as variation_name',
                'business_locations.name as location_name',
                DB::raw("CONCAT(COALESCE(users.first_name, ''), ' ', COALESCE(users.last_name, '')) as added_by")
            );

        return datatables()->of($damages)
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d H:i');
            })
            ->editColumn('unit_cost', function ($row) {
                return number_format($row->unit_cost, 2);
            })
            ->editColumn('total_cost', function ($row) {
                return number_format($row->total_cost, 2);
            })
            ->addColumn('action', function ($row) {
                $html = '<button class="btn btn-xs btn-danger delete-damage" data-href="' . action([\App\Http\Controllers\DamageController::class, 'destroy'], [$row->id]) . '">' .
                    '<i class="glyphicon glyphicon-trash"></i> ' . __('messages.delete') .
                    '</button>';
                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Remove the specified damage record.
     */
    public function destroy($id, Request $request)
    {
        try {
            $damage = Damage::findOrFail($id);

            DB::beginTransaction();

            // Restore stock if applicable
            try {
                $product = Product::find($damage->product_id);
                if ($product && $product->enable_stock == 1) {
                    $vld = null;
                    if (!is_null($damage->variation_id) && !is_null($damage->location_id)) {
                        $vld = VariationLocationDetails::where('product_id', $damage->product_id)
                            ->where('variation_id', $damage->variation_id)
                            ->where('location_id', $damage->location_id)
                            ->first();
                    }

                    // fallback: match by product + location
                    if (!$vld && !is_null($damage->location_id)) {
                        $vld = VariationLocationDetails::where('product_id', $damage->product_id)
                            ->where('location_id', $damage->location_id)
                            ->first();
                    }

                    // fallback: any VLD for product
                    if (!$vld) {
                        $vld = VariationLocationDetails::where('product_id', $damage->product_id)->first();
                    }

                    if ($vld) {
                        $vld->qty_available = $vld->qty_available + $damage->quantity;
                        $vld->save();
                    }
                }
            } catch (\Exception $e) {
                // Log but don't stop deletion if stock update fails
                \Log::warning('Failed to restore stock when deleting damage: ' . $e->getMessage());
            }

            // Remove related transaction and account transactions created during store()
            // This block was malformed and is now removed as it was incomplete and likely incorrect.
            // The original intent of finding a transaction by 'additional_notes' and then logging a stock error
            // within that try block was illogical. The stock error logging is now correctly placed.

            // Delete associated account transactions (using note pattern since there's no damage_id column)
            AccountTransaction::where('note', 'like', 'Damage #' . $id . ':%')->delete();

            // Delete associated expense transaction
            Transaction::where('type', 'expense')
                ->where('additional_notes', 'like', 'Damage #' . $id . ':%')
                ->delete();

            // Delete the damage record
            $damage->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'msg' => __('lang_v1.deleted_success')]);
            }

            return redirect()->back()->with('success', __('lang_v1.deleted_success'));
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency('File:' . $e->getFile() . ' Line:' . $e->getLine() . ' Message:' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['success' => false, 'msg' => __('messages.something_went_wrong')], 500);
            }

            return redirect()->back()->with('error', __('messages.something_went_wrong'));
        }
    }
}
