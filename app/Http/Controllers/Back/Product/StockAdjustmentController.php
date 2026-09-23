<?php

namespace App\Http\Controllers\Back\Product;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Product\Adjustment;
use App\Models\Product\Product;
use App\Models\Product\ProductData;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SupplierPayment;
use App\Models\SupplierPaymentItem;
use App\Models\User;
use App\Repositories\StockRepo;
use App\Repositories\UserRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('index', 'show', 'create', 'store');
    }

    public function index(Request $request){
        $purchases = Purchase::with('user');
        if($request->from_date){
            $purchases->where('created_at', '>=', Carbon::parse($request->from_date));
        }
        if($request->to_date){
            $purchases->where('created_at', '<=', Carbon::parse($request->to_date));
        }
        if($request->supplier){
            $supplier_id = $request->supplier;
            $purchases->whereHas('user', function($q) use($supplier_id){
                $q->where('user_id', $supplier_id);
            });
        }
        $purchases = $purchases->latest('id')->withTrashed()->get();
        $suppliers = User::where('type', 'supplier')->orderBy('last_name')->get();

        return view('back.product.adjustment.index', compact('purchases', 'suppliers'));
    }

    public function create(Request $request){
        if($request->product_id){
            $product = Product::findOrFail($request->product_id);
        }else{
            $product = null;
        }
        $suppliers = User::where('type', 'supplier')->orderBy('last_name')->get();

        return view('back.product.adjustment.create', compact('product', 'suppliers'));
    }

    public function addItem(Request $request){
        $product = Product::find($request->id);

        if($product){
            return view('back.product.adjustment.addItem', compact('product'))->render();
        }
        return '';
    }

    public function getCost(Request $request){
        $data = ProductData::where('id', $request->variation_id)->first();

        if($data){
            return $data->cost;
        }
        return '';
    }

    public function store(Request $request){
        $request->validate([
            'grand_total' => 'required',
            'product' => 'required',
            'supplier' => 'required',
            'paid_amount' => 'required',
            'date' => 'required',
            'invoice_no' => 'nullable|max:255',
        ]);

        // Adjustment
        $purchase = new Purchase();
        $purchase->user_id = $request->supplier;
        $purchase->grand_total = $request->grand_total;
        $purchase->paid_amount = $request->paid_amount;
        $purchase->note = $request->note;
        $purchase->invoice_no = $request->invoice_no;
        $purchase->created_at = Carbon::parse($request->date);
        $purchase->save();

        foreach($request->product as $key => $product_id){
            $stock = new PurchaseItem();
            $stock->purchase_id = $purchase->id;
            $stock->product_id = $product_id;
            $stock->product_data_id = $request->product_data_id[$key];

            $stock->purchase_quantity = $request->quantity[$key];

            $stock->purchase_price = $request->price[$key] ?? 0;
            $stock->save();

            // Generate Average Purchase Price Per Unit
            $product_data = ProductData::find($stock->product_data_id);
            if($product_data){
                if($product_data->cost > 0 && $product_data->stock > 0){
                    $purchasePrice = $stock->purchase_price;
                    $allOldQuantity = $product_data->stock;
                    $allNewQuantity = $stock->purchase_quantity;

                    $allOldPrices = $product_data->cost * $product_data->stock;
                    $allNewPrices = $purchasePrice * $stock->purchase_quantity;

                    $newQuantity = $allOldQuantity + $allNewQuantity;

                    if ($newQuantity && $newQuantity > 0){
                        $averagePrice = ($allOldPrices + $allNewPrices) / $newQuantity;
                        $product_data->cost = $averagePrice;
                        $product_data->save();
                    }
                }else{
                    $product_data->cost = $stock->purchase_price;
                    $product_data->save();
                }
            }
        }

        // Stock Ledger
        StockRepo::ledger($stock->product_data_id, $stock->purchase_quantity, 0, ('Product Purchased #' . $purchase->id));

        // Supplier Ledger
        UserRepo::ledger($purchase->user_id, 0, $purchase->grand_total, ('Product Purchased #' . $purchase->id), $purchase->id, $purchase->id, $purchase->invoice_no);
        if($purchase->paid_amount > 0){
            UserRepo::ledger($purchase->user_id, $purchase->paid_amount, 0, ('Payment on Product Purchased #' . $purchase->id), $purchase->id, $purchase->invoice_no);

            // Supplier Payment
            $supplier_payment = new SupplierPayment;
            $supplier_payment->amount = $purchase->paid_amount;
            $supplier_payment->date = $request->date;
            $supplier_payment->remark = 'Payment on Product Purchased #' . $purchase->id;
            $supplier_payment->payment_method = 'Cash';
            $supplier_payment->save();

            $get_supplier = User::find($purchase->user_id);
            $supplier_payment_item = new SupplierPaymentItem();
            $supplier_payment_item->supplier_payment_id = $supplier_payment->id;
            $supplier_payment_item->supplier_id = $get_supplier->id;
            $supplier_payment_item->supplier_name = $get_supplier->full_name ?? 'N/A';
            $supplier_payment_item->amount = $purchase->paid_amount;
            $supplier_payment_item->save();
        }

        return redirect()->route('back.adjustments.index')->with('success-alert', 'Stock added successfully.');
    }

    public function show($id){
        $adjustment = Purchase::with('purchase_items', 'purchase_items.product', 'purchase_items.product_data')->findOrFail($id);

        return view('back.product.adjustment.show', compact('adjustment'));
    }

    // public function edit(Adjustment $adjustment){

    //     return view('back.product.adjustment.edit', compact('adjustment'));
    // }

    public function delete(Request $request){
        $request->validate([
            'adjustment_id' => 'required',
            'delete_note' => 'required|max:255'
        ]);

        $adjustment = Adjustment::findOrFail($request->adjustment_id);
        $adjustment->delete_note = $request->delete_note;
        $adjustment->save();

        foreach($adjustment->Stocks as $stock){
            StockRepo::stockSale($stock->product_data_id, $stock->purchase_quantity);

            // Flash Quantities
            StockRepo::flashQuantities($stock->product_data_id);
        }

        $adjustment->delete();

        return view('back.product.adjustment.edit', compact('adjustment'))->with('success-alert', 'Adjustment deleted successfully!');
    }
}
