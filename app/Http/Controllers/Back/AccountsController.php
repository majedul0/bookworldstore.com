<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\CourierReceive;
use App\Models\Order\Order;
use App\Models\OtherExpense;
use App\Models\SupplierPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('report', 'otherExpenses', 'otherExpensesStore', 'otherExpensesDelete', 'otherExpensesEdit', 'courier', 'courierReceive', 'courierDelete');
    }

    public function otherExpenses(Request $request){
        $expenses = OtherExpense::query();
        if($request->from_date){
            $expenses->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $expenses->whereDate('created_at', '<=', $request->to_date);
        }
        $expenses = $expenses->withTrashed()->get();

        return view('back.accounts.otherExpenses', compact('expenses'));
    }

    public function otherExpensesStore(Request $request){
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'purpose' => 'required',
        ]);

        $other_expense = new OtherExpense;
        $other_expense->amount = $request->amount;
        $other_expense->note = $request->purpose;
        $other_expense->created_at = Carbon::parse($request->date);
        $other_expense->save();

        return redirect()->back()->with('success', 'Expense created!');
    }

    public function otherExpensesDelete($id){
        $other_expense = OtherExpense::findOrFail($id);

        $other_expense->delete();

        return redirect()->back()->with('success', 'Expense deleted!');
    }

    public function otherExpensesEdit($id){
        $expense = OtherExpense::findOrFail($id);

        return view('back.accounts.otherExpensesEdit', compact('expense'));
    }

    public function otherExpensesUpdate($id, Request $request){
        $expense = OtherExpense::findOrFail($id);

        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'purpose' => 'required',
        ]);

        $expense->amount = $request->amount;
        $expense->note = $request->purpose;
        $expense->created_at = Carbon::parse($request->date);
        $expense->save();

        return redirect()->route('back.accounts.otherExpenses')->with('success', 'Expense updated!');
    }

    public function report(Request $request){
        $hold_amount = Order::where('status', 'In Courier');
        if($request->from_date){
            $hold_amount->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $hold_amount->whereDate('created_at', '<=', $request->to_date);
        }
        $hold_amount = $hold_amount->sum(DB::raw('(shipping_charge + product_total + tax_amount + other_cost) - (discount_amount + refund_total_amount)'));
        $receivable_amount = Order::where('status', 'Delivered');
        if($request->from_date){
            $receivable_amount->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $receivable_amount->whereDate('created_at', '<=', $request->to_date);
        }
        $receivable_amount = $receivable_amount->sum(DB::raw('(shipping_charge + product_total + tax_amount + other_cost) - (discount_amount + refund_total_amount)'));

        $received_amount_q = CourierReceive::query();
        if($request->from_date){
            $received_amount_q->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $received_amount_q->whereDate('created_at', '<=', $request->to_date);
        }
        $received_amount = $received_amount_q->sum('amount');

        // Supplier Payment
        $supplier_payments_q = SupplierPayment::query();
        if($request->from_date){
            $supplier_payments_q->whereDate('date', '>=', $request->from_date);
        }
        if($request->to_date){
            $supplier_payments_q->whereDate('date', '<=', $request->to_date);
        }
        $supplier_payment_amount = $supplier_payments_q->sum('amount');

        // Total Sales
        $total_sales_q = Order::where('status', 'Completed');
        if($request->from_date){
            $total_sales_q->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $total_sales_q->whereDate('created_at', '<=', $request->to_date);
        }
        $total_sales = $total_sales_q->sum(DB::raw('(shipping_charge + product_total + tax_amount + other_cost) - (discount_amount + refund_total_amount)'));

        // Total Shipping Charge
        $total_shipping_charge_q = Order::where('status', 'Completed');
        if($request->from_date){
            $total_shipping_charge_q->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $total_shipping_charge_q->whereDate('created_at', '<=', $request->to_date);
        }
        $total_shipping_charge = $total_shipping_charge_q->sum('shipping_charge');

        return view('back.accounts.report', compact('hold_amount', 'receivable_amount', 'received_amount', 'supplier_payment_amount', 'total_sales', 'total_shipping_charge'));
    }

    public function courier(Request $request){
        $receives = CourierReceive::query();
        if($request->from_date){
            $receives->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $receives->whereDate('created_at', '<=', $request->to_date);
        }
        $receives = $receives->withTrashed()->get();
        $hold_amount = Order::where('status', 'In Courier')->sum(DB::raw('(shipping_charge + product_total + tax_amount + other_cost) - (discount_amount + refund_total_amount)'));
        $receivable_amount = Order::where('status', 'Delivered')->sum(DB::raw('(shipping_charge + product_total + tax_amount + other_cost) - (discount_amount + refund_total_amount)'));

        return view('back.accounts.courier', compact('receives', 'hold_amount', 'receivable_amount'));
    }

    public function courierReceive(Request $request){
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'note' => 'required',
        ]);

        $courier_receive = new CourierReceive();
        $courier_receive->amount = $request->amount;
        $courier_receive->note = $request->note;
        $courier_receive->created_at = Carbon::parse($request->date);
        $courier_receive->save();

        return redirect()->back()->with('success', 'Courier received!');
    }

    public function courierDelete($id){
        $courier_receive = CourierReceive::findOrFail($id);
        $courier_receive->delete();

        return redirect()->back()->with('success', 'Entry deleted!');
    }
}
