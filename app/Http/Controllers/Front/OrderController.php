<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\MissingOrder;
use App\Models\MissingOrderItem;
use App\Models\Order\Order;
use App\Models\Product\ProductData;
use App\Models\User;
use App\Repositories\CartRepo;
use App\Repositories\OrderRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkout()
    {
        // Refresh
        CartRepo::refresh();

        $carts = CartRepo::summary();

        return view('front.checkout', compact('carts'));
    }

    public function order(Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:25',
            'address' => 'required|max:255',
            'note' => 'nullable|max:2555'
        ];

        if($request->payment_method && $request->payment_method != 'Cash on Delivery'){
            $v_data['payment_sender_number'] = 'required|max:25';
            $v_data['payment_claimed_amount'] = 'required|numeric';
            $v_data['payment_transaction_id'] = 'required|max:100';
        }

        $request->validate($v_data);

        // Refresh
        CartRepo::refresh();

        // Carts
        // $cart_summary = CartRepo::summary($request->shipping_charge ?? 0);
        $carts = CartRepo::get();
        if (!count($carts)) {
            return redirect()->route('cart')->with('error-alert', 'Your cart is empty!');
        }

        // $body_data = $request->all();
        // $body_data['check_duplicate_order'] = 'No';
        // $body_data['user_ip'] = $request->ip();
        // $body_data['user_agent'] = $request->header('User-Agent');
        // $body_data['source_web'] = request()->getHost();
        // $body_data['order_items'] = array();

        // // Insert order products
        $product_total = 0;
        foreach ($carts as $key => $cart) {
            $product = $cart->Product;
            if ($product) {
                // $body_data['order_items'][$key]['type'] = 'Title';
                // $body_data['order_items'][$key]['product_title'] = $product->title;
                // $body_data['order_items'][$key]['price'] = $cart->ProductData->custom_sale_price;
                // $body_data['order_items'][$key]['quantity'] = $cart->quantity;
                // if ($cart->ProductData->type == 'Variable') {
                //     $body_data['order_items'][$key]['attributes'] = $cart->ProductData->attribute_items_b_string;
                // } else {
                //     $body_data['order_items'][$key]['attributes'] = '';
                // }

                $product_total += $cart->ProductData->sale_price * $cart->quantity;
            }
        }

        // try {
        //     $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory?inventory_id=' . env('SAAS_USER_ID')), $body_data);

        //     if ($response->successful()) {
        //         $data = $response->json();
        //         if ($data['success']) {
        //             $order = $data['data'];

        //             // Delete Cart
        //             $session_id = Session::getId();
        //             if (auth()->check()) {
        //                 DB::table('carts')->where('user_id', auth()->user()->id)->delete();
        //             } else {
        //                 DB::table('carts')->where('session_id', $session_id)->delete();
        //             }

        //             return redirect()->route('orderComDetails', $order['id'])->with('success-alert', 'Order created success.');
        //         }

        //         return redirect()->back()->withInput()->with('error-alert', $data['message']);
        //     } else {
        //         return $this->storeMissingOrder($request, $body_data['order_items']);
        //     }
        // } catch (\Exception $e) {
        //     return $this->storeMissingOrder($request, $body_data['order_items']);
        // }

        if (auth()->user()) {
            $client = auth()->user();
        } else {
            $client = User::where('mobile_number', $request->mobile_number)->first();
            if (!$client && $request->email) {
                $client = User::where('email', $request->email)->first();
            }
            if (!$client) {
                $client = new User;
                $client->last_name = $request->name;
                $client->mobile_number = $request->mobile_number;
                $client->email = $request->email;
                $client->street = $request->address;
                $client->password = Hash::make(123456789);
                $client->save();
            }
        }

        $order = new Order();

        // Customer Information
        $order->user_id = $client->id;
        $order->last_name = $request->name;
        $order->street = $request->address;
        $order->mobile_number = $request->mobile_number;
        $order->email = $request->email;
        $order->note = $request->note;

        // Customer Shipping Information
        $order->shipping_full_name = $request->name;
        $order->shipping_email = $request->email;
        $order->shipping_mobile_number = $request->mobile_number;
        $order->shipping_street = $request->address;
        // $order->shipping_state_id = $request->change_area;
        // $order->shipping_city = $request->city;
        // Charges
        $order->product_total = $product_total;
        $order->tax_amount = 0;
        // Shipping
        $order->shipping_charge = $request->delivery_charge ?? 0;
        $order->shipping_method = 'Cash On delivery';
        $order->shipping_weight = 0;

        // Payment
        $order->payment_method = $request->payment_method ?: 'Cash on Delivery';
        if($order->payment_method != 'Cash on Delivery'){
            $order->payment_sender_number = $request->payment_sender_number;
            $order->payment_claimed_amount = $request->payment_claimed_amount;
            $order->payment_transaction_id = $request->payment_transaction_id;
        }

        $order->save();

        // Insert order products
        foreach ($carts as $cart) {
            $order_product = OrderRepo::product($order->id, $cart->product_id, $cart->product_data_id, $cart->ProductData->custom_sale_price, $cart->quantity);
        }
        $order->save();

        return redirect()->route('orderComDetails', $order->id)->with('success-alert', 'Order created success.');
    }

    // order form duct details
    public function order_form_product($id, Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:25',
            'address' => 'required|max:255'
        ];

        if (!preg_match('/^01[3-9]\d{8}$/', $request->mobile_number)) {
            return redirect()->back()->withInput()->with('error-alert', 'Invalid mobile number format!');
        }

        $request->validate($v_data);

        $body_data = $request->all();
        $body_data['check_duplicate_order'] = 'Yes';
        $body_data['user_ip'] = $request->ip();
        $body_data['user_agent'] = $request->header('User-Agent');

        $quantity = $request->quantity ?? 1;
        if ($quantity < 1) {
            $quantity = 1;
        }

        $product_data = ProductData::find($request->product_data_id);

        $body_data['order_items'] = [
            [
                'type' => "Title",
                'product_title' => $product_data->Product->title,
                'price' => $request->sales_price,
                'quantity' => $quantity,
                'attributes' => $product_data->type == 'Variable' ? $product_data->attribute_items_b_string :  "",
            ]
        ];

        try {
            $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory?inventory_id=' . env('SAAS_USER_ID')), $body_data);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['success']) {
                    $order = $data['data'];

                    // Delete Cart
                    $session_id = Session::getId();
                    if (auth()->check()) {
                        DB::table('carts')->where('user_id', auth()->user()->id)->delete();
                    } else {
                        DB::table('carts')->where('session_id', $session_id)->delete();
                    }

                    return redirect()->route('orderComDetails', $order['id'])->with('success-alert', 'Order created success.');
                }

                return redirect()->back()->withInput()->with('error-alert', $data['message']);
            } else {
                return $this->storeMissingOrder($request, $body_data['order_items']);
            }
        } catch (\Exception $e) {
            return $this->storeMissingOrder($request, $body_data['order_items']);
        }
    }


    public function orderFailedTrackSaas(Request $request)
    {
        $body_data = $request->all();
        $body_data['inventory_id'] = env('SAAS_USER_ID');
        $body_data['source_web'] = request()->getHost();
        $body_data['product_datas'] = array();

        $carts = CartRepo::get();

        // Insert order products
        foreach ($carts as $key => $cart) {
            $product = $cart->Product;
            if ($product) {
                $body_data['product_datas'][$key]['type'] = 'Title';
                $body_data['product_datas'][$key]['product_title'] = $product->title;
                $body_data['product_datas'][$key]['selling_price'] = $cart->ProductData->custom_sale_price;
                $body_data['product_datas'][$key]['quantity'] = $cart->quantity;
            }
        }

        $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/failed-track'), $body_data);

        if ($response->successful()) {
            $data = $response->json();

            if ($data['success']) {
                return $data['message'];
            }

            return 'false';
        } else {
            return 'false';
        }
    }

    public function storeMissingOrder($request, $order_items)
    {
        $order = MissingOrder::where('mobile_number', $request->mobile_number)->where('created_at', '>', now()->subHour(24))->first();
        if ($order) {
            return redirect()->back()->withInput()->with('error-alert', 'Your order already submitted!');
        }

        $missing_order = new MissingOrder;
        $missing_order->name = $request->name;
        $missing_order->mobile_number = $request->mobile_number;
        $missing_order->address = $request->address;
        $missing_order->shipping_charge = $request->delivery_charge ?? 0;
        $missing_order->ip = $request->ip();
        $missing_order->user_agent = $request->header('User-Agent');
        $missing_order->uid = $request->uu_id;
        $missing_order->save();

        foreach ($order_items as $order_item) {
            $missing_order_item = new MissingOrderItem;
            $missing_order_item->missing_order_id = $missing_order->id;
            $missing_order_item->product_title = $order_item['product_title'];
            $missing_order_item->price = $order_item['price'];
            $missing_order_item->quantity = $order_item['quantity'];
            $missing_order_item->save();
        }

        return redirect()->route('orderComDetailsMissing', $missing_order->id)->with('success-alert', 'Order created success.');
    }

    public function orderComDetails($id)
    {
        $order = Order::with('OrderProducts', 'OrderProducts.Product', 'OrderProducts.ProductData')->findOrFail($id);

        if ($order->order_tracked) {
            $track = true;
            $order->order_tracked = 1;
            $order->save();
        } else {
            $track = false;
        }

        return view('front.orderComDetails', compact('order', 'track'));

        $response = Http::get(env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory/' . $id . '?inventory_id=' . env('SAAS_USER_ID'));
        if ($response->successful()) {
            $data = $response->json();
            if ($data['success']) {
                $order = $data['data'];
                if (($data['others']['pixel_track'] ?? 'false') == 'true') {
                    $track = true;
                } else {
                    $track = false;
                }

                return view('front.orderComDetails', compact('order', 'track'));
            }

            abort(404);
        } else {
            return response()->json(['error' => 'Error from API!'], $response->status());
        }
    }

    public function orderComDetailsMissing($id)
    {
        $order = MissingOrder::findOrFail($id);

        return view('front.orderComDetailsMissing', compact('order'));
    }

    public function track(Request $request)
    {
        $order = null;
        if ($request->mobile_number) {
            $order = Order::where('mobile_number', $request->mobile_number)->first();
        }

        return view('front.orderTrack', compact('order'));
    }
}
