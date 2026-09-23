<?php

namespace App\Http\Controllers;

use App\Models\FailedOrder;
use App\Models\FailedOrderItem;
use App\Models\Order\Order;
use App\Models\OTP;
use App\Models\Product\Product;
use App\Models\Product\ProductData;
use App\Models\User;
use App\Repositories\FBConversionRepo;
use App\Repositories\OrderRepo;
use App\Repositories\SMSRepo;
use App\Repositories\StockRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class LandingController extends Controller
{
    public function index()
    {
        $productIds = [24866, 24867, 24868];

        $products = [];
        // $defaultVariations = [];

        foreach ($productIds as $id) {
            $productResponse = $this->getCachedProductData($id);

            if (!$productResponse || !$productResponse['success']) {
                Cache::forget('products_inventory_' . env('SAAS_USER_ID') . '_' . $id);
                continue;
            }

            $productData = $productResponse['data'];
            $products[] = $productData;
        }

        // dd($products);

        if (empty($products)) {
            return response()->json(['error' => 'No valid product data found from API']);
        }

        return view('landing.index', compact('products'));
    }

    private function getCachedProductData($productId)
    {
        $cacheKey = 'products_inventory_' . env('SAAS_USER_ID') . '_' . $productId;

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($productId) {
            $url = env('SAAS_API_BASE_PATH') . 'products/' . $productId . '?inventory_id=' . env('SAAS_USER_ID');
            $response = Http::get($url);

            return $response->successful() ? $response->json() : null;
        });
    }

    public function productSlug($product_slug)
    {
        $product = Product::where('slug', $product_slug)->active()->firstOrFail();

        $default_variation = $product->ProductData;

        return view('landing.details', compact('product', 'default_variation'));
    }

    public function order($id, Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:25',
            'address' => 'required|max:255',
            'note' => 'max:2555'
        ];

        $request->validate($v_data);

        // Check Number
        $check_order = Order::where('mobile_number', $request->mobile_number)->where('created_at', '>', Carbon::now()->subHour(24))->first();
        if ($check_order) {
            return redirect()->back()->with('error-alert', 'Your order already submitted!');
        }

        $order = DB::transaction(function () use ($request, $id) {
            // Client
            $client = User::where('mobile_number', $request->mobile_number)->first();
            if (!$client && $request->email) {
                $client = User::where('email', $request->email)->first();
            }
            if (!$client) {
                $client = new User;
                $client->street = $request->address;
                $client->password = Hash::make(123456789);
            }
            $client->last_name = $request->name;
            $client->mobile_number = $request->mobile_number;
            $client->save();

            $product_data = ProductData::find($request->variation);

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

            // Charges
            $order->product_total = $product_data->sale_price;

            // Shipping
            $order->shipping_charge = $request->delivery_charge ?? 0;
            $order->shipping_method = 'Cash On delivery';
            $order->shipping_weight = 0;

            $order->save();

            // Insert Order Status
            OrderRepo::status($order->id, 'Order created', 'Customer');

            // Insert order products
            $order_product = OrderRepo::product($order->id, $id, $product_data->id, $product_data->sale_price, 1);

            if ($order_product->Product && $order_product->Product->type == 'Bundle') {
                foreach ($order_product->Product->bundle_product_items as $bundle_product_item) {
                    StockRepo::ledger($bundle_product_item->ProductData->id, 0, 1, ('Bundle Product order from Landing Page #' . $order->id));
                }
            } else {
                StockRepo::ledger($product_data->id, 0, 1, ('Product order from Landing Page #' . $order->id));
            }

            if ($request->uu_id) {
                try {
                    FailedOrder::where('uid', $request->uu_id)->orWhere('shipping_mobile_number', $request->mobile_number)->delete();
                } catch (\Exception $e) {
                }
            }

            return $order;
        });

        return redirect()->route('landing.orderComDetails', $order->id)->with('success-alert', 'Order created success.');
    }

    public function orderSaas(Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:25',
            'address' => 'required|max:255'
        ];

        $request->validate($v_data);

        $body_data = $request->all();
        $body_data['_fbc'] = request()->cookie('_fbc');
        $body_data['_fbp'] = request()->cookie('_fbp');
        $body_data['user_ip'] = $request->ip();
        $body_data['source_web'] = $request->host();
        $body_data['user_agent'] = $request->header('User-Agent');
        $body_data['check_duplicate_order'] = 'No';
        $body_data['order_items'] = [];
        $variations = (array)$request->variation;
        if (empty($variations)) {
            return redirect()->back()->withInput()->with('error-alert', 'Please select product variation!');
        }
        foreach ($variations as $variation) {
            $body_data['order_items'][] = [
                'product_data_id' => $variation,
                'price' => $request->input('price_' . $variation),
                'quantity' => 1,
            ];
        }
        $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory?inventory_id=' . env('SAAS_USER_ID')), $body_data);

        if ($response->successful()) {
            $data = $response->json();
            if ($data['success']) {
                $order = $data['data'];

                return redirect()->route('landing.orderComDetailsSaas', $order['id'])->with('success-alert', 'Order created success.');
            }

            return redirect()->back()->withInput()->with('error-alert', $data['message']);
        } else {
            return response()->json(['error' => 'Error from API!'], $response->status());
        }
    }

    public function orderComDetails($id)
    {
        $order = Order::findOrFail($id);

        if ($order->order_tracked == 0) {
            $track = true;

            $order->order_tracked = 1;
            $order->save();
        } else {
            $track = false;
        }

        return view('landing.orderComDetails', compact('order', 'track'));
    }

    public function orderComDetailsSaas($id)
    {
        $response = Http::get(env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory/' . $id . '?inventory_id=' . env('SAAS_USER_ID'));
        if ($response->successful()) {
            $data = $response->json();
            if ($data['success']) {
                $order = $data['data'];
                if ($order['order_tracked']) {
                    $track = true;
                } else {
                    $track = false;
                }

                return view('landing.orderComDetailsSaas', compact('order', 'track'));
            }

            abort(404);
        } else {
            return response()->json(['error' => 'Error from API!'], $response->status());
        }
    }

    //     $order = Order::findOrFail($id);

    //     if($order->order_tracked == 0){
    //         $track = true;

    //         $order->order_tracked = 1;
    //         $order->save();
    //     }else{
    //         $track = false;
    //     }

    //     return view('landing.orderComDetails', compact('order', 'track'));
    // }

    public function fbTrackLanding(Request $request)
    {
        if (env('PIXEL_ID') && env('PIXEL_ACCESS_TOKEN')) {
            $additinal_data = array();

            if ($request->currency) {
                $additinal_data['currency'] = $request->currency;
            }
            if ($request->content_type) {
                $additinal_data['content_type'] = $request->content_type;
            }
            if ($request->content_ids) {
                $additinal_data['content_ids'] = $request->content_ids;
            }
            if ($request->contents) {
                $additinal_data['contents'] = $request->contents;
            }
            if ($request->value) {
                $additinal_data['value'] = $request->value;
            }

            if (!count($additinal_data)) {
                $additinal_data = null;
            }
            $phone = $request->phone ?? null;
            $name = $request->name ?? null;
            $external_id = $request->external_id ?? null;
            $event_id = $request->event_id ?? null;

            return FBConversionRepo::track($request->track_type, $additinal_data, $phone, $name, $external_id, $event_id);
        }

        return 'false';
    }

    public function failedTrackSaas(Request $request)
    {
        $request_data = $request->all();
        $request_data['_fbc'] = request()->cookie('_fbc');
        $request_data['_fbp'] = request()->cookie('_fbp');
        $request_data['user_ip'] = $request->ip();
        $request_data['user_agent'] = $request->header('User-Agent');
        $request_data['source_web'] = $request->host();

        $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/failed-track'), $request_data);

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

    public function OTPRequest(Request $request)
    {
        if ($request->check_duplicate_order) {
            $order = Order::where('shipping_mobile_number', $request->mobile_number)->where('created_at', '>', Carbon::now()->subHour(24))->first();

            if ($order) {
                return [
                    'success' => false,
                    'code' => 100,
                    'time' => date('d/m/Y h:ia', strtotime($order->created_at)),
                    'message' => 'Duplicate Order!'
                ];
            }
        }
        $otp = OTP::where('email_or_number', $request->mobile_number)->where('created_at', '>=', now()->subMinute(5))->first();

        if (!$otp) {
            $otp = new OTP;
            $otp->email_or_number = $request->mobile_number;
            $otp->otp = random_int(100000, 999999);
            $otp->save();
        } else {
            $otp->created_at = now();
            $otp->save();
        }

        $body = "Your order confirmation OTP are: " . $otp->otp;
        $status = SMSRepo::sendSms($request->mobile_number, $body);

        if ($status) {
            return [
                'success' => true,
                'message' => 'OTP Send Success!'
            ];
        }

        return [
            'success' => false,
            'message' => 'OTP Send Failed!'
        ];
    }

    public function OTPCheck(Request $request)
    {
        $otp = OTP::where('useable_for', 'Order')->where('using', 'Mobile Number')->where('email_or_number', $request->mobile_number)->where('otp', $request->otp)->where('created_at', '>=', now()->subMinute(5))->first();

        if ($otp) {
            // Delete OTP
            OTP::where('using', 'Mobile Number')->where('email_or_number', $request->mobile_number)->delete();

            return [
                'success' => true,
                'message' => 'OTP Verified!'
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid OTP!'
        ];
    }
}
