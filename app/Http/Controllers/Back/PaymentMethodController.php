<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class);
    }

    public function index()
    {
        $payment_methods = PaymentMethod::orderBy('position')->get();

        return view('back.paymentMethods.index', compact('payment_methods'));
    }

    public function create()
    {
        return view('back.paymentMethods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'number' => 'required|max:255',
        ]);

        $payment_method = new PaymentMethod;
        $payment_method->name = $request->name;
        $payment_method->number = $request->number;
        $payment_method->instructions = $request->instructions;
        $payment_method->status = $request->has('status') ? 1 : 0;
        $payment_method->save();

        return redirect()->route('back.paymentMethods.index')->with('success-alert', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('back.paymentMethods.edit', ['payment_method' => $paymentMethod]);
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'name' => 'required|max:255',
            'number' => 'required|max:255',
        ]);

        $paymentMethod->name = $request->name;
        $paymentMethod->number = $request->number;
        $paymentMethod->instructions = $request->instructions;
        $paymentMethod->status = $request->has('status') ? 1 : 0;
        $paymentMethod->save();

        return redirect()->route('back.paymentMethods.index')->with('success-alert', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('back.paymentMethods.index')->with('success-alert', 'Payment method deleted successfully.');
    }
}
