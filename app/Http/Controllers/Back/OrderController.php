<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\FailedOrder;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\OrderTrack;
use App\Models\Product\AttributeItem;
use App\Models\Product\Product;
use App\Models\State;
use App\Models\User;
use App\Repositories\OrderRepo;
use App\Repositories\ProductRepo;
use App\Repositories\SMSRepo;
use App\Repositories\StockRepo;
use App\Repositories\UserRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Info;

class OrderController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'edit', 'update', 'index', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Order::where('admin_read', 2)->update(['admin_read' => 1]);
        return view('back.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $total_orders = 0;
        $complected_orders = 0;

        $sources = Info::Settings('general', 'order_sources') ?? 'Landing Page,Mobile Call,Messenger,WhatsApp,FB Group,Bulk SMS';
        $sources_arr = explode(',', $sources);
        if(request('failed_order')){
            $failed_order = FailedOrder::with('failed_order_items')->find(request('failed_order'));
        }else{
            $failed_order = null;
        }

        if(request('mobile_number')){
            $total_orders = Order::where('shipping_mobile_number', request('mobile_number'))->count();
            $complected_orders = Order::whereIn('status', ['Delivered', 'Completed'])->where('shipping_mobile_number', request('mobile_number'))->count();
        }

        return view('back.orders.create', compact('sources_arr', 'failed_order', 'total_orders', 'complected_orders'));
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
            'date' => 'required',
            'address' => 'required|max:255',
            'products' => 'required',
            // 'email' => 'nullable|max:255',
            'status' => 'required',
            // 'payment_status' => 'required',
            'discount' => 'required',
            'shipping' => 'required',
            'mobile_number' => 'required|max:191',
            'source' => 'nullable|max:255',
        ];
        $request->validate($v_data);

        $mobile_number = $request->mobile_number;
        $user = User::where('mobile_number', $mobile_number)->first();

        if(!$user){
            $user = new User;
            $user->admin_read = 1;
            $user->mobile_number = $mobile_number;
            $user->password = Hash::make(123456789);
        }
        $user->last_name = $request->name;
        $user->street = $request->address;
        $user->save();

        $order = DB::transaction(function () use($request, $user) {
            $order = new Order;
            $order->created_at = Carbon::parse($request->date);

            // Statuses
            $order->status = $request->status;
            $order->payment_status = 'Pending';

            // Customer Information
            $order->user_id = $user->id ?? null;
            // $order->first_name = $user->first_name;
            $order->last_name = $request->name;
            $order->street = $request->address;
            // $order->apartment = $user->apartment;
            // $order->city = $user->city;
            // $order->state = $user->state;
            // $order->zip = $user->zip;
            // $order->country = $user->country;
            $order->mobile_number = $request->mobile_number;
            $order->email = $request->email ?? null;

            // Customer Shipping Information
            $order->shipping_full_name = $request->name;
            $order->shipping_email = $user->custom_email ?? null;
            $order->shipping_mobile_number = $request->mobile_number;
            $order->shipping_street = $request->address;
            $order->shipping_email = $request->email ?? null;
            // $order->shipping_post_code = $user->zip;
            // $order->shipping_city = $user->city;
            $order->shipping_state_id = $request->district;
            // $order->shipping_country = $user->country;

            // Charges
            $order->product_total = $request->product_total;

            // $order->tax = Info::Settings('settings', 'tax') ?? 0;
            $order->tax_amount = $request->tax_amount ?? 0;

            $order->discount = $request->discount;
            $order->discount_amount = $request->discount;
            if($request->payment_method){
                $order->payment_method = $request->payment_method;
            }
            $order->paid_amount = $request->paid_amount ?? 0;

            // Shipping
            $order->shipping_charge = $request->shipping;
            // $order->shipping_weight = $cart_summary['shipping_weight'];

            // Notes
            $order->note = $request->note;
            $order->staff_note = $request->staff_note;

            // Order Source
            $order->source = $request->source;

            // Others
            $order->reference_no = $request->reference_no;

            // Attachment
            if ($request->file('attachment')){
                // $this->validate($request, [
                //     'image' => 'image|mimes:jpg,png,jpeg,gif'
                // ]);
                $file = $request->file('attachment');
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $destination = public_path() . '/uploads/order';
                $file->move($destination, $file_name);
                $order->attachment = $file_name;
            }

            $order->save();

            $new_data = array();
            $new_data['id'] = $order->id;
            $new_data['shipping_full_name'] = $order->shipping_full_name;
            $new_data['shipping_mobile_number'] = $order->shipping_mobile_number;
            $new_data['shipping_charge'] = $order->shipping_charge;
            $new_data['shipping_street'] = $order->shipping_street;
            // InventoryRepo::trackEmployeeActivity('Create', 'Admin', $user, [], $new_data);

            // if($request->payment_status == 'Paid'){
            //     OrderRepo::paid($order->id);

            //     AccountsRepo::accounts('Credit', $order->grand_total, "Order Payment #$order->id");
            // }

            // Insert Order Status
            OrderRepo::status($order->id, 'Order created', auth()->user()->full_name);

            // Insert order products
            foreach($request->products as $key => $product){
                if($request->quantity[$key] > 0){
                    // Simple Attributes
                    $simple_attribute = (array)$request[$product . '_simple_attributes'];
                    $attr_data = array();
                    if(count($simple_attribute)){
                        $attribute_items = AttributeItem::with('Attribute')->whereIn('id', $simple_attribute)->get();
                        $attr_string = '';
                        foreach($attribute_items as $a_ley => $attribute_item){
                            $attr_string .= $attribute_item->Attribute->name . ': ' . $attribute_item->name . ', ';
                            $attr_data['data'][$a_ley] = [
                                'attribute' => [
                                    'id' => $attribute_item->Attribute->id,
                                    'name' => $attribute_item->Attribute->name,
                                ],
                                'attribute_item' => [
                                    'id' => $attribute_item->id,
                                    'name' => $attribute_item->name,
                                ],
                            ];
                        }
                        $attr_data['string'] = $attr_string;
                    }

                    $order_product = OrderRepo::product($order->id, $product, $request->product_data_id[$key], $request->price[$key], $request->quantity[$key], $attr_data);

                    // Sales Track
                    ProductRepo::sales($order_product, 0, $order_product->quantity, ('Product order from admin panel #' . $order->id));
                }
            }

            $order->save();

            $order->sendSMS('on_create_order');

            // Customer Ledger
            // UserRepo::ledger($order->customer_id, $order->grand_total, 0, ('Order from admin panel #' . $order->id), $order->id, $order->reference_no);
            // if($order->paid_amount > 0){
            //     UserRepo::ledger($order->customer_id, 0, $order->paid_amount, ('Order payment from admin panel #' . $order->id), $order->id, $order->reference_no);
            // }

            if($request->failed_order){
                try{
                    FailedOrder::where('id', $request->failed_order)->orWhere('shipping_mobile_number', $order->shipping_mobile_number)->delete();
                }catch(\Exception $e){}
            }

            return $order;
        });

        return redirect()->route('back.orders.show', $order->id)->with('success-alert', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $op_returns = OrderProduct::where('order_id', $order->id)->where('return_quantity', '>', 0)->get();
        $order_products = OrderProduct::where('order_id', $order->id)->where('quantity', '>', 0)->get();
        $courier_config = Info::SettingsGroupKey('courier');

        $total_orders = Order::where('user_id', $order->user_id)->count();
        $completed_orders = Order::where('user_id', $order->user_id)->whereIn('status', ['Delivered', 'Completed'])->count();

        $sources = Info::Settings('general', 'order_sources') ?? 'Landing Page,Mobile Call,Messenger,WhatsApp,FB Group,Bulk SMS';
        $sources_arr = explode(',', $sources);
        if(!in_array($order->source, $sources_arr)){
            $collection = collect($sources_arr);
            $collection->push($order->source);
            $sources_arr = $collection->toArray();
        }

        return view('back.orders.show', compact('order', 'op_returns', 'order_products', 'courier_config', 'total_orders', 'completed_orders', 'sources_arr'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        // Track Order
        $order_track = new OrderTrack();
        $order_track->order_id = $order->id;
        $order_track->user_id = auth()->user()->id;
        $order_track->user_name = auth()->user()->last_name;
        $order_track->old_data = json_encode($order);
        $order_track->old_order_products = json_encode($order->OrderProducts);

        $request->validate([
            // 'status' => 'required',
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:255',
            'address' => 'required|max:255',
            'shipping_charge' => 'required',
            'paid_amount' => 'required|integer',
            // 'payment_method' => 'required',
        ]);

        DB::transaction(function() use($order, $request, $order_track){
            $old_status = $order->status;

            if($request->status){
                $order->status = $request->status;
            }
            $order->shipping_full_name = $request->name;
            $order->shipping_mobile_number = $request->mobile_number;
            $order->shipping_email = $request->email;
            $order->shipping_street = $request->address;
            $order->shipping_charge = $request->shipping_charge;
            $order->note = $request->note;
            $order->staff_note = $request->staff_note;
            $order->discount_amount = $request->discount;
            $order->reference_no = $request->reference_no;
            $order->shipping_state_id = $request->district;
            $order->paid_amount = $request->paid_amount;

            // Attachment
            if ($request->file('attachment')){
                $file = $request->file('attachment');
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $destination = public_path() . '/uploads/order';
                $file->move($destination, $file_name);
                $order->attachment = $file_name;
            }

            // Order Source
            $order->source = $request->source;

            if($order->status == 'Delivered' && $order->status != $old_status){
                $order->sendSMS('on_Delivered_order');
            }
            if($order->status == 'Completed' && $order->status != $old_status){
                $order->sendSMS('on_Completed_order');
            }
            if($order->status == 'In Courier' && $order->status != $old_status){
                $order->sendSMS('on_InCourier_order');
                $order->courier_submitted_at = now();
            }
            if($order->status == 'Canceled' && $order->status != $old_status){
                $order->sendSMS('on_Canceled_order');
            }
            if($order->status == 'Returned' && $order->status != $old_status){
                $order->sendSMS('on_Returned_order');
            }
            if($order->status == 'Confirmed' && $order->status != $old_status){
                $order->sendSMS('on_Confirmed_order');
            }
            if($order->status == 'Hold' && $order->status != $old_status){
                $order->sendSMS('on_Hold_order');
            }

            if($order->status == 'Canceled' || $order->status == 'Returned'){
                if($request->cancel_return_reason == 'Custom'){
                    $order->cancel_return_reason = $request->custom_cancel_return_reason;
                }else{
                    $order->cancel_return_reason = $request->cancel_return_reason;
                }
            }

            $order->save();

            // Insert order products
            foreach((array)$request->products as $key => $product){
                if($request->quantity[$key] > 0){
                    // Simple Attributes
                    $simple_attribute = (array)$request[$product . '_simple_attributes'];
                    $attr_data = array();
                    if(count($simple_attribute)){
                        $attribute_items = AttributeItem::with('Attribute')->whereIn('id', $simple_attribute)->get();
                        $attr_string = '';
                        foreach($attribute_items as $a_ley => $attribute_item){
                            $attr_string .= $attribute_item->Attribute->name . ': ' . $attribute_item->name . ', ';
                            $attr_data['data'][$a_ley] = [
                                'attribute' => [
                                    'id' => $attribute_item->Attribute->id,
                                    'name' => $attribute_item->Attribute->name,
                                ],
                                'attribute_item' => [
                                    'id' => $attribute_item->id,
                                    'name' => $attribute_item->name,
                                ],
                            ];
                        }
                        $attr_data['string'] = $attr_string;
                    }

                    $order_product = OrderRepo::product($order->id, $product, $request->product_data_id[$key], $request->price[$key], $request->quantity[$key]);

                    // Sales Track
                    ProductRepo::sales($order_product, 0, $order_product->quantity, ('Order product added from admin panel #' . $order->id));
                }
            }

            // Complete Order
            if($order->status == 'Completed' && $order->status != $old_status){
                OrderRepo::completed($order->id);
            }

            if($order->status == 'Canceled' && $order->status != $old_status){
                foreach($order->OrderProducts as $order_product){
                    if($order_product->quantity > 0){
                        ProductRepo::sales($order_product, $order_product->quantity, 0, ('Order cancled from admin panel #' . $order->id));
                    }
                }
            }

            if($order->status == 'Returned' && $order->status != $old_status){
                foreach($order->OrderProducts as $order_product){
                    if($order_product->quantity > 0){
                        ProductRepo::sales($order_product, $order_product->quantity, 0, ('Order Returned from admin panel #' . $order->id));

                        $order_product->return_quantity = $order_product->return_quantity + $order_product->quantity;
                        $order_product->quantity = 0;
                        $order_product->save();
                    }
                }
            }

            OrderRepo::index($order->id);

            $order_track->current_data = json_encode($order);
            $order_track->current_order_products = json_encode($order->OrderProducts);
            $order_track->save();

            return $order;
        });

        return redirect()->back()->with('success-alert', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Add Item
    public function addItem(Request $request){
        $product = Product::find($request->product_id);

        $settings = Info::Settings('general', 'stock_out_can_order');
        if($settings == 'Yes' || ($product && $product->stock > 0)){
            return view('back.orders.addItem', compact('product'))->render();
        }
        return 'false';
    }

    public function table(Request $request){
        // Get Data
        $columns = array(
            1 => 'id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')] ?? 'id';
        $dir = $request->input('order.0.dir');

        $query = Order::query();

        // Status Filter
        if($request->status == 'CompletedTax'){
            $query->where('status', 'Completed')->where('tax_amount', '!=', 0);
        }elseif($request->status == 'PaidCoupon'){
            $query->where('payment_status', 'Paid')->where('coupon_code', '!=', null)->where('discount_amount', '!=', 0);
        }elseif($request->status != 'All'){
            $query->where('status', $request->status);
        }

        // Customer Filter
        if($request->customer){
            $query->where('user_id', $request->customer);
        }

        // Coupon Filter
        if($request->coupon_code){
            $query->where('coupon_code', $request->coupon_code);
        }

        // Date Filter
        if($request->from_date){
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if($request->id_range){
            $ids = explode('-', $request->id_range);

            if(isset($ids[0]) && $ids[0]){
                $query->where('id', '>=', $ids[0]);
            }
            if(isset($ids[1]) && $ids[1]){
                $query->where('id', '<=', $ids[1]);
            }
        }

        // Search
        if($request->input('search.value')){
            $search = $request->input('search.value');
            $query->where(function($q) use ($search){
                $q->where('id', $search)
                ->orWhere('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('shipping_mobile_number', 'LIKE', "%{$search}%")
                ->orWhere('street', 'LIKE', "%{$search}%");
            });
        }

        // Count Items
        $totalFiltered = $query->count();
        if($limit == "-1"){
            $query->skip($start)->limit($totalFiltered);
        }else{
            $query->skip($start)->limit($limit);
        }
        $query = $query->orderBy($order, $dir)->get();

        $output = array();
        foreach ($query as $key => $data) {
            $nestedData['sl'] = ($start + $key) + 1;
            if($dir == 'desc'){
                $nestedData['sl_desc'] = $totalFiltered - ($start + $key);
            }else{
                $nestedData['sl_desc'] = ($start + $key) + 1;
            }
            $nestedData['select'] = '<input class="mt-1" name="orders[]" type="checkbox" value="'. $data->id .'" style="width: 20px;height:20px">';
            $nestedData['id'] = '<a href="'. route('back.orders.show', $data->id) .'">'. $data->id .'</a>';
            $nestedData['date'] = date('d/m/Y', strtotime($data->created_at)) . '<br/>' . date('h:ia', strtotime($data->created_at));
            $nestedData['coupon_code'] = $data->coupon_code;
            $nestedData['order_name'] = $data->shipping_full_name ?? 'N/A';
            $nestedData['full_address'] = $data->shipping_full_address ?? 'N/A';
            // $nestedData['country'] = $data->full_name;
            $nestedData['mobile_number'] = $data->shipping_mobile_number ?? 'N/A';
            $nestedData['total_amount'] = amount($data->grand_total);
            $nestedData['discount_amount'] = amount($data->discount_amount, 2);
            $nestedData['status'] = $data->status . ($data->printed_at ? '<i class="fas fa-print ml-1 small"></i>' : '');
            $nestedData['tax_amount'] = amount($data->tax_amount, 2);
            $nestedData['payment_status'] = $data->payment_status;
            $nestedData['action'] = '<div><a class="btn btn-success btn-sm" href="'. route('back.orders.show', $data->id) .'">Details</a></div>';
            $nestedData['staff_note'] = $data->staff_note;
            $output[] = $nestedData;
        }

        // Output
        $output = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $output
        );
        return response()->json($output);
    }

    public function returnRefund(Order $order){
        if($order->status == 'Returned' || $order->status == 'Partial'){
            return redirect()->route('back.orders.update', $order->id)->with('error-alert2', 'Your order already has return record!');
        }
        return view('back.orders.returnRefund', compact('order'));
    }

    public function selectCourierSubmit(Request $request, Order $order){
        if($order->shipping_id){
            return redirect()->route('back.orders.show', $order->id)->with('error', 'Courier already selected.');
        }

        $request->validate(['courier' => 'required']);
        $shipping = explode('::', $request->courier);

        $order->shipping_method = $shipping[0] ?? null;
        $order->hidden_shipping_charge = $shipping[1] ?? 0;
        $order->shipping_id = $shipping[2] ?? null;
        $order->save();

        return redirect()->route('back.orders.show', $order->id)->with('success', 'Courier selected successfully.');
    }

    public function customerDetails(Request $request){
        $customer = User::where('mobile_number', $request->mobile_number)->first();
        if($customer){
            $total_orders = Order::where('user_id', $customer->id)->count();
            $complected_orders = Order::where('user_id', $customer->id)->where('status', 'Completed')->count();
            return [
                'status' => true,
                'customer' => $customer,
                'total_orders' => $total_orders,
                'complected_orders' => $complected_orders
            ];
        }
        return [
            'status' => false
        ];
    }

    public function addProduct(Request $request, $order_id){
        $request->validate([
            'product' => 'required',
            'quantity' => 'required',
        ]);

        $product = Product::find($request->product);

        OrderRepo::product($order_id, $request->product, $product->ProductData->id, $product->ProductData->custom_sale_price, 1);

        OrderRepo::index($order_id);

        return redirect()->back()->with('success', 'Product Added!');
    }

    public function addQuantity(Request $request){
        $request->validate([
            'order_product' => 'required',
            'quantity' => 'required|integer',
        ]);

        if($request->quantity > 0){
            $order_product = OrderProduct::with('Order')->findOrFail($request->order_product);
            $order_product->quantity = $order_product->quantity + $request->quantity;
            $order_product->save();

            // Stock Ledger
            if($order_product->Product && $order_product->Product->type == 'Bundle'){
                foreach($order_product->Product->bundle_product_items as $bundle_product_item){
                    StockRepo::ledger($bundle_product_item->ProductData->id, 0, $request->quantity, ('Added order product Qty from admin panel #' . $order_product->order_id));
                }
            }else{
                StockRepo::ledger($order_product->product_data_id, 0, $request->quantity, ('Added order product Qty from admin panel #' . $order_product->order_id));
            }

            // Customer Ledger
            UserRepo::ledger($order_product->Order->user_id, ($order_product->sale_price * $request->quantity), 0, ('Added order product Qty from admin panel #' . $order_product->Order->id), $order_product->Order->id, $order_product->Order->reference_no);

            return redirect()->back()->with('success', 'Quantity added!');
        }
        return redirect()->back()->with('error', 'Quantity will not lower then 1!');
    }

    public function returnQuantity(Request $request){
        $request->validate([
            'order_product' => 'required',
            'quantity' => 'required|integer',
        ]);

        if($request->quantity > 0){
            $order_product = OrderProduct::with('Order')->findOrFail($request->order_product);

            if($order_product->quantity < $request->quantity){
                return redirect()->back()->with('error', 'Return quantity will not getter then current quantity!');
            }

            $order_product->quantity = $order_product->quantity - $request->quantity;
            $order_product->return_quantity = $order_product->return_quantity + $request->quantity;
            $order_product->save();

            // Stock Ledger
            if($order_product->Product && $order_product->Product->type == 'Bundle'){
                foreach($order_product->Product->bundle_product_items as $bundle_product_item){
                    StockRepo::ledger($bundle_product_item->ProductData->id, $request->quantity, 0, ('Order product return from admin panel #' . $order_product->order_id));
                }
            }else{
                StockRepo::ledger($order_product->product_data_id, $request->quantity, 0, ('Order product return from admin panel #' . $order_product->order_id));
            }

            // Customer Ledger
            UserRepo::ledger($order_product->Order->user_id, 0, ($order_product->sale_price * $request->quantity), ('Order product return from admin panel #' . $order_product->Order->id), $order_product->Order->id, $order_product->Order->reference_no);

            OrderRepo::index($order_product->order_id);

            return redirect()->back()->with('success', 'Quantity returned!');
        }
        return redirect()->back()->with('error', 'Quantity will not lower then 1!');
    }

    public function printList(Request $request){
        $orders_id = (array)$request->orders;
        if(!count($orders_id)){
            return redirect()->back()->with('error', 'Please select some order!');
        }

        $orders = Order::whereIn('id', $orders_id);
        $orders = $orders->latest('id')->get();

        if($request->type == 'status_update'){
            $status = $request->status;
            if(!$status){
                return redirect()->back()->with('error', 'Please select an status!');
            }

            if($status == 'Delete'){
                foreach($orders as $order){
                    $order->delete();
                }
                return redirect()->back()->with('success', 'Orders Deleted!');
            }else{
                foreach($orders as $order){
                    if($order->status != 'Delivered' && $order->status != 'Completed' && $order->status != 'Canceled' && $order->status != 'Returned' && $order->status != 'Returned'){
                        if($status == 'Completed' && $order->status != $status){
                            $order->paid_amount = $order->paid_amount + $order->due;

                            // Add Payment to Customer
                            if($order->due > 0){
                                UserRepo::ledger($order->user_id, 0, $order->due, ('Order payment on order Completed #' . $order->id), $order->id, $order->reference_no);
                            }
                        }

                        if($status == 'Canceled' && $order->status != $status){
                            foreach($order->OrderProducts as $order_product){
                                if($order_product->quantity > 0){
                                    // Stock Ledger
                                    if($order_product->Product && $order_product->Product->type == 'Bundle'){
                                        foreach($order_product->Product->bundle_product_items as $bundle_product_item){
                                            StockRepo::ledger($bundle_product_item->ProductData->id, $order_product->quantity, 0, ('Order cancled from admin panel #' . $order->id));
                                        }
                                    }else{
                                        StockRepo::ledger($order_product->product_data_id, $order_product->quantity, 0, ('Order cancled from admin panel #' . $order->id));
                                    }
                                }
                            }

                            // Return Payment to Customer
                            UserRepo::ledger($order->user_id, 0, $order->due, ('Order cancled from admin panel #' . $order->id), $order->id, $order->reference_no);
                        }

                        if($status == 'Returned' && $order->status != $status){
                            foreach($order->OrderProducts as $order_product){
                                if($order_product->quantity > 0){
                                    // Stock Ledger
                                    if($order_product->Product && $order_product->Product->type == 'Bundle'){
                                        foreach($order_product->Product->bundle_product_items as $bundle_product_item){
                                            StockRepo::ledger($bundle_product_item->ProductData->id, $order_product->quantity, 0, ('Order cancled from admin panel #' . $order->id));
                                        }
                                    }else{
                                        StockRepo::ledger($order_product->product_data_id, $order_product->quantity, 0, ('Order returned from admin panel #' . $order->id));
                                    }

                                    $order_product->return_quantity = $order_product->return_quantity + $order_product->quantity;
                                    $order_product->quantity = 0;
                                    $order_product->save();
                                }
                            }

                            // Return Payment to Customer
                            UserRepo::ledger($order->user_id, 0, $order->product_total, ('Order returned from admin panel #' . $order->id), $order->id, $order->reference_no);

                            // $order->refund_product_total = $order->product_total;
                            // $order->save();
                        }

                        $order->status = $status;
                        $order->save();
                    }
                }

                return redirect()->back()->with('success', 'Orders status updated!');
            }
        }

        Order::whereIn('id', $orders_id)->update([
            'printed_at' => Carbon::now()
        ]);

        return view('back.orders.printList', compact('orders'));
    }

    public function failed(Request $request){
        $failed_orders = FailedOrder::with('failed_order_items');
        if($request->from){
            $failed_orders->whereDate('created_at', '>=', $request->from);
        }
        if($request->to){
            $failed_orders->whereDate('created_at', '<=', $request->to);
        }
        $failed_orders = $failed_orders->latest('id')->get();

        return view('back.orders.failed', compact('failed_orders'));
    }

    public function getCustomerData(Request $request){
        $mobile_number = $request->mobile_number;

        $user = User::where('mobile_number', $mobile_number)->first();
        $completed_orders = 0;
        $total_orders = 0;

        if($user){
            $completed_orders = Order::where('user_id', $user->id)->whereIn('status', ['Delivered', 'Completed'])->count();
            $total_orders = Order::where('user_id', $user->id)->count();
            $all_link = route('back.orders.index') . '?customer_id=' . $user->id;

            return [
                'status' => true,
                'completed_orders' => $completed_orders,
                'total_orders' => $total_orders,
                'all_link' => $all_link,
                'user' => $user
            ];
        }
        return [
            'status' => false
        ];
    }
    public function failedDeleteMultiple(Request $request){
        $items = (array)$request->orders;
        if(!count($items)){
            return redirect()->back()->with('error', 'Please select some Items!');
        }

        $items = FailedOrder::whereIn('id', $items)->delete();

        return redirect()->back()->with('success', 'Items deleted!');
    }
}
