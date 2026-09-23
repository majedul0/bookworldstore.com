<?php

namespace App\Http\Controllers\back\Product;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Purchase;
use App\Models\SupplierPayment;
use App\Models\SupplierPaymentItem;
use App\Models\User;
use App\Models\UserLedger;
use App\Repositories\UserRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class SupplerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'index', 'edit', 'update', 'ledger', 'addPayment', 'paymentDetails');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('type', 'supplier')->active()->get();
        return view('back.supplier.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('type', 'admin')->active()->get();

        return view('back.supplier.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:255|unique:users',
            'email' => 'required|max:255|unique:users',
            'street' => 'required|max:255',
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $user = new User;
        $user->type = 'supplier';
        $user->last_name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->street;
        $user->zip = $request->zip;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        $user->password = Hash::make(123456);

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $total_purchase = Purchase::where('user_id', $id)->sum('grand_total');
        $ledgers = UserLedger::where('user_id', $id)->orderBy('id')->get();

        return view('back.supplier.show', compact('user', 'total_purchase', 'ledgers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('back.supplier.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:255|unique:users,mobile_number,' . $id,
            'email' => 'required|max:255|unique:users,email,' . $id,
            'street' => 'required|max:255',
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }
        // if($request->password){
        //     $v_data['password'] = 'min:8|confirmed';
        // }

        $request->validate($v_data);

        $user = User::findOrFail($id);
        $user->last_name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->street;
        $user->zip = $request->zip;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country = $request->country;
        // if($request->password){
        //     $user->password = Hash::make($request->password);
        // }

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            // Delete old
            if($user->profile){
                $img = public_path() . '/uploads/user/' . $user->profile;
                if (file_exists($img)) {
                    unlink($img);
                }
            }

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user->id == auth()->user()->id){
            return redirect()->back()->with('error-alert', 'Sorry! You can not delete your own account!');
        }

        // Delete Image
        if($user->image){
            $img = public_path() . '/uploads/user/' . $user->image;
            if (file_exists($img)) {
                unlink($img);
            }
        }

        $user->delete();

        return redirect()->route('back.suppliers.index')->with('success-alert', 'Supplier deleted successfully.');
    }

    public function payable(){
        $users = User::where('type', 'supplier')->where('balance', '<', 0)->active()->get();

        return view('back.supplier.payable', compact('users'));
    }

    public function payments(Request $request){
        $suppliers = User::where('type', 'supplier')->active()->get();

        $supplier_payments = SupplierPayment::with('supplier_payment_items', 'supplier_payment_items.supplier');
        if($request->from_date){
            $supplier_payments->where('created_at', '>=', Carbon::parse($request->from_date));
        }
        if($request->to_date){
            $supplier_payments->where('created_at', '<=', Carbon::parse($request->to_date));
        }
        if($request->supplier){
            $supplier_id = $request->supplier;
            $supplier_payments->whereHas('supplier_payment_items', function($q) use($supplier_id){
                $q->where('supplier_id', $supplier_id);
            });
        }
        $supplier_payments = $supplier_payments->latest('id')->get();

        return view('back.supplier.payments', compact('suppliers', 'supplier_payments'));
    }

    public function addPayment(){
        $suppliers = User::where('type', 'supplier')->active()->get();

        return view('back.supplier.addPayment', compact('suppliers'));
    }

    public function addPaymentSubmit(Request $request){
        $request->validate([
            'date' => 'required',
            'supplier' => 'required',
            'amount' => 'required',
            'total_amount' => 'required'
        ]);

        $suppliers = (array)$request->supplier;
        if(!count($suppliers)){
            return redirect()->back()->with('error', 'Please select supplier.');
        }

        $supplier_payment = new SupplierPayment;
        $supplier_payment->voucher_no = $request->voucher_no;
        $supplier_payment->amount = $request->total_amount;
        $supplier_payment->date = $request->date;
        $supplier_payment->remark = $request->remark;
        $supplier_payment->payment_method = 'Cash';
        $supplier_payment->save();

        foreach($suppliers as $key => $supplier){
            $get_supplier = User::find($supplier);

            $supplier_payment_item = new SupplierPaymentItem();
            $supplier_payment_item->supplier_payment_id = $supplier_payment->id;
            $supplier_payment_item->supplier_id = $supplier;
            $supplier_payment_item->supplier_name = $get_supplier->full_name ?? 'N/A';
            $supplier_payment_item->amount = $request->amount[$key] ?? 0;
            $supplier_payment_item->save();

            UserRepo::ledger($supplier, $supplier_payment_item->amount, 0, ($request->remark ?? 'Supplier Payment'));
        }

        return redirect()->back()->with('success', 'Supplier payment successfully.');
    }

    public function getInfo(Request $request){
        $supplier = User::find($request->supplier);

        return $supplier;
    }

    public function paymentDetails($id){
        $supplier_payment = SupplierPayment::with('supplier_payment_items')->find($id);

        return view('back.supplier.paymentDetails', compact('supplier_payment'));
    }
}
