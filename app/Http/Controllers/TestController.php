<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductData;
use App\Models\PurchaseItem;
use App\Models\User;
use App\Repositories\PathaoRepo;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function test(Request $request){
        $products = Product::where('type', 'Variable')->get();
        foreach($products as $product){
            ProductRepo::index($product->id);
        }
        dd('Done');

        $response = Http::get(env('SAAS_API_BASE_PATH') . 'products/89?inventory_id=' . env('SAAS_USER_ID'));
        if ($response->successful()) {
            $data = $response->json();
            if($data['success']){
                $product = $data['data'];
                $default_variation = (object) $product['product_data'];

                return view('landing.watchesShowOTP', compact('product', 'default_variation'));
            }

            abort(404);
        } else {
            return response()->json(['error' => 'Error from API!'], $response->status());
        }


        $products_data = ProductData::get();
        foreach($products_data as $product_data){
            $total_purchase = PurchaseItem::where('product_data_id', $product_data->id)->sum('purchase_quantity');
            $total_sales = OrderProduct::where('product_data_id', $product_data->id)->whereHas('Order', function($q){
                $q->whereNotIn('status', ['Canceled', 'Returned']);
            })->sum('quantity');

            $product_data->stock = $total_purchase - $total_sales;
            $product_data->save();

            ProductRepo::index($product_data->id);
        }
        dd('Stock updated');

        // ProductData::withTrashed()->update()
        $products = Product::with('Gallery')->latest('id')->get();
        return view('landing.index', compact('products'));

        Artisan::call('backup:run');
        dd(123);

        $request_data = array();
        $request_data['store_id'] = $request->store;
        $request_data['merchant_order_id'] = $id;
        $request_data['recipient_name'] = $order->order_name;
        $request_data['recipient_phone'] = str_replace('88', '', str_replace('+88', '', $request->phone_number));
        $request_data['recipient_address'] = $request->address;
        $request_data['recipient_city'] = $locations[2];
        $request_data['recipient_zone'] = $locations[1];
        $request_data['recipient_area'] = $locations[0];
        $request_data['item_weight'] = $request->weight;
        $request_data['delivery_type'] = 48;
        $request_data['item_type'] = 2;
        $request_data['item_quantity'] = count($order->Products);
        $request_data['special_instruction'] = $request->note;
        $request_data['amount_to_collect'] = $order->Due();

        $response = PathaoRepo::create('POST', 'aladdin/api/v1/orders', $request_data);
        dd($response);

        $products = Product::get();
        foreach ($products as $key => $product) {
            ProductRepo::index($product->id);
        }
        dd('done product indexing');
    }

    // Config
    public function config(){
        $admin = User::where('email', 'admin@me.com')->first();
        if(!$admin){
            $admin = new User;
            $admin->type = 'admin';
            $admin->last_name = 'Admin';
            $admin->email = 'admin@me.com';
            $admin->mobile_number = '123456789';
            $admin->password = Hash::make(123456789);
        }else{
            $admin->password = Hash::make(123456789);
        }

        $admin->save();

        // Some Settings
        $where = array();
        $where['group'] = 'general';

        $where['name'] = 'title';
        $insert['value'] = env('APP_NAME');
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'mobile_number';
        $insert['value'] = '123456789';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'email';
        $insert['value'] = 'admin@me.com';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'copyright';
        $insert['value'] = 'Copyright ' . date('Y');
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'slogan';
        $insert['value'] = env('APP_NAME');
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'city';
        $insert['value'] = 'city';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'state';
        $insert['value'] = 'state';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'country';
        $insert['value'] = 'country';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'zip';
        $insert['value'] = 'zip';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'street';
        $insert['value'] = 'street';
        DB::table('settings')->updateOrInsert($where, $insert);

        dd('success');
    }

    public function cacheClear(){
        Artisan::call('cache:clear');

        return redirect()->route('homepage');
    }

    public function cacheClearAdmin(){
        Artisan::call('cache:clear');

        return redirect()->route('dashboard_d')->with('success', 'Cache cleared!');
    }
}
