<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\eCourierLocations;
use App\Models\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Info;
use Illuminate\Support\Facades\Artisan;
use App\Repositories\PathaoRepo;
use App\Repositories\RedxRepo;
use App\Repositories\SteadFastRepo;
use App\Repositories\eCourierRepo;
use App\Repositories\PaperflyRepo;

class CourierController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('config', 'update');
    }

    public function config(){
        $courier_config = Info::SettingsGroupKey('courier');
        return view('back.courier.config', compact('courier_config'));
    }

    public function update(Request $request){
        // $request->validate([
        //     'courier' => 'required'
        // ]);

        $where = array();
        $where['group'] = 'general';

        // // Save Courier
        // $where['name'] = 'courier';
        // $insert['value'] = $request->courier;
        // DB::table('settings')->updateOrInsert($where, $insert);

        // Save Credentials
        $where['group'] = 'courier';

        // Pathao
        if($request->pathao_enabled == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'pathao_enabled';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'pathao_client_id';
        $insert['value'] = $request->pathao_client_id;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'pathao_client_secret';
        $insert['value'] = $request->pathao_client_secret;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'pathao_username';
        $insert['value'] = $request->pathao_username;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'pathao_password';
        $insert['value'] = $request->pathao_password;
        DB::table('settings')->updateOrInsert($where, $insert);

        // REDX
        if($request->redx_enabled == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'redx_enabled';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'redx_api_token';
        $insert['value'] = $request->redx_api_token;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Steadfast
        if($request->steadfast_enabled == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'steadfast_enabled';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'steadfast_api_key';
        $insert['value'] = $request->steadfast_api_key;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'steadfast_secret_key';
        $insert['value'] = $request->steadfast_secret_key;
        DB::table('settings')->updateOrInsert($where, $insert);

        // eCourier
        if($request->ecourier_enabled == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'ecourier_enabled';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'ecourier_api_key';
        $insert['value'] = $request->ecourier_api_key;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'ecourier_secret_key';
        $insert['value'] = $request->ecourier_secret_key;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'ecourier_user_id';
        $insert['value'] = $request->ecourier_user_id;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Paperfly
        if($request->paperfly_enabled == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'paperfly_enabled';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'paperfly_api_key';
        $insert['value'] = $request->paperfly_api_key;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'paperfly_username';
        $insert['value'] = $request->paperfly_username;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'paperfly_password';
        $insert['value'] = $request->paperfly_password;
        DB::table('settings')->updateOrInsert($where, $insert);

        // Pidex
        if($request->pidex_enabled == 'Yes'){
            $insert['value'] = 'Yes';
        }else{
            $insert['value'] = 'No';
        }
        $where['name'] = 'pidex_enabled';
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'pidex_merchant_id';
        $insert['value'] = $request->pidex_merchant_id;
        DB::table('settings')->updateOrInsert($where, $insert);

        $where['name'] = 'pidex_api_token';
        $insert['value'] = $request->pidex_api_token;
        DB::table('settings')->updateOrInsert($where, $insert);

        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'Courier Credentials Updated!');
    }

    public function getPathaoInfo(Request $request){
        $locations_html = '';
        $stores = '';

        // Get Stores
        $get_stores = PathaoRepo::send('GET', 'aladdin/api/v1/stores', []);
        if(!$get_stores['status']){
            return [
                'status' => false
            ];
        }

        if(isset($get_stores['response']['type']) && $get_stores['response']['type'] == 'success'){
            foreach($get_stores['response']['data']['data'] as $store){
                $stores .= '<option value="'. $store['store_id'] .'">'. $store['store_name'] .'</option>';
            }
        }else{
            return [
                'status' => false
            ];
        }
        $location_json = file_get_contents(public_path('pathao-locations.json'));
        $locations = json_decode($location_json, true);
        $locations_html = view('back.orders.pathaoLocations', compact('locations'))->render();

        return [
            'status' => true,
            'stores' => $stores,
            'locations_html' => $locations_html
        ];
    }

    public function updateCourierStatus($id){
        $order = Order::findOrFail($id);
        if(!$order->courier || !$order->courier_invoice){
            return redirect()->back()->with('error', 'Please submit to a courier first!');
        }

        try{
            if($order->courier == 'Pathao'){
                $get_order = PathaoRepo::send('GET', "aladdin/api/v1/orders/{$order->courier_invoice}");
                if($get_order['status'] && $get_order['response']['type'] == 'success'){
                    $order->courier_status = $get_order['response']['data']['order_status'];
                    $order->save();

                    return redirect()->back()->with('success', 'Courier Status Updated!');
                }
            }elseif($order->courier == 'REDX'){
                $get_order = RedxRepo::curl("parcel/track/{$order->courier_invoice}", 'GET');

                if($get_order['tracking'] && count($get_order['tracking'])){
                    $last_index = count($get_order['tracking']) - 1;
                    $order->courier_status = $get_order['tracking'][$last_index]['message_en'];
                    $order->save();

                    return redirect()->back()->with('success', 'Courier Status Updated!');
                }
            }elseif($order->courier == 'Steadfast'){
                $get_order = SteadFastRepo::status($order->courier_invoice);

                if(isset($status['delivery_status'])){
                    $order->courier_status = $status['delivery_status'];
                    $order->save();

                    return redirect()->back()->with('success', 'Courier Status Updated!');
                }
            }elseif($order->courier == 'eCourier'){
                $post_fields = array(
                    'ecr' => $order->courier_invoice
                );

                $get_order = eCourierRepo::curl('track', $post_fields);

                if(isset($get_order['success']) && $get_order['success']){
                    $order->courier_status = $get_order['query_data']['status'][0]['status'];
                    $order->save();

                    return redirect()->back()->with('success', 'Courier Status Updated!');
                }
            }

            return redirect()->back()->with('error', 'Courier API Error!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Courier API Error!');
        }
    }

    public function sendPathaoOrder($id, Request $request){
        $v_data = [
            'store' => 'required',
            'address' => 'required',
            'weight' => 'required',
            'collect_amount' => 'required',
            'location' => 'required',
            'note' => 'nullable|max:255'
        ];
        $request->validate($v_data);

        $locations = explode('::', $request->location);
        $order = Order::findOrFail($id);

        try{
            if($order->courier_invoice){
                return redirect()->back()->with('error', 'Sorry! Courier already submitted.');
            }

            $request_data = array();
            $request_data['store_id'] = $request->store;
            $request_data['merchant_order_id'] = $id;
            $request_data['recipient_name'] = $order->shipping_full_name;
            $request_data['recipient_phone'] = str_replace('88', '', str_replace('+88', '', $request->phone_number));
            $request_data['recipient_address'] = $request->address;
            $request_data['recipient_city'] = $locations[2];
            $request_data['recipient_zone'] = $locations[1];
            $request_data['recipient_area'] = $locations[0];
            $request_data['item_weight'] = $request->weight;
            $request_data['delivery_type'] = 48;
            $request_data['item_type'] = 2;
            $request_data['item_quantity'] = 1;
            $request_data['special_instruction'] = $request->note;
            $request_data['amount_to_collect'] = $request->collect_amount ?? $order->due;

            $create_order = PathaoRepo::send('POST', 'aladdin/api/v1/orders', $request_data);

            if($create_order['status']){
                if($create_order['response']['type'] == 'error'){
                    $errors = array();

                    foreach((array)$create_order['response']['errors'] as $error){
                        $errors[] = $error[0] ?? '';
                    }

                    return redirect()->back()->with('error', ('Pathao API error! ' . implode(', ', $errors)));
                }

                // Update Order
                $order->courier = 'Pathao';
                $order->courier_invoice = $create_order['response']['data']['consignment_id'];
                $order->shipping_street = $request->address;
                $order->courier_status = 'Pending';
                $order->shipping_mobile_number = $request->phone_number;
                $order->note = $request->note;
                $order->save();

                return redirect()->back()->with('success', 'Pathao submitted success!');
            }
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Pathao API error!');
        }
    }

    public function getRedexInfo(Request $request){
        // Get Areas
        $get_areas = RedxRepo::curl('areas');
        if(!isset($get_areas['areas'])){
            return [
                'status' => false
            ];
        }
        $areas = $get_areas['areas'];

        // Get Stores
        $get_stores = RedxRepo::curl('pickup/stores');
        $stores = $get_stores['pickup_stores'];

        $html = view('back.orders.redexInfo', compact('areas', 'stores'))->render();

        return [
            'status' => true,
            'html' => $html,
        ];
    }

    public function sendRedexOrder($id, Request $request){
        $request->validate([
            'store' => 'required',
            'area' => 'required',
            'address' => 'required',
            'collect_amount' => 'required',
            'phone_number' => 'required',
            'weight' => 'required',
            'note' => 'nullable|max:255'
        ]);

        $order = Order::findOrFail($id);

        if($order->courier_invoice){
            return redirect()->back()->with('error', 'Sorry! Courier already submitted.');
        }

        $area_arr = explode('::', $request->area);
        $area_id = $area_arr[0] ?? '';
        $area_name = $area_arr[1] ?? '';

        $request_data_string = '{
            "customer_name": "'. $order->shipping_full_name .'",
            "customer_phone": "'. str_replace('88', '', str_replace('+88', '', $request->phone_number)) .'",
            "delivery_area": "'. $area_name .'",
            "delivery_area_id": '. $area_id .',
            "customer_address": "'. $request->address .'",
            "merchant_invoice_id": "'. $id . time() .'",
            "cash_collection_amount": "'. $request->collect_amount ?? $order->due .'",
            "parcel_weight": '. $request->weight .',
            "value": 100,
            "instruction": "'. $request->note .'",
            "pickup_store_id": '. $request->store .'
        }';

        $create_order = RedxRepo::curl('parcel', 'POST', $request_data_string);

        if(!isset($create_order['tracking_id'])){
            return redirect()->back()->with('error', 'Error from api!');
        }

        // Update Order
        $order->courier_invoice = $create_order['tracking_id'];
        $order->courier = 'REDX';
        $order->courier_status = 'Package is created successfully';
        $order->shipping_street = $request->address;
        $order->shipping_mobile_number = $request->phone_number;
        $order->note = $request->note;
        $order->save();

        return redirect()->back()->with('success', 'Courier submitted success!');
    }

    public function sendSteadfastOrder($id, Request $request){
        $request->validate([
            'address' => 'required',
            'collect_amount' => 'required',
            'phone_number' => 'required',
            'note' => 'nullable|max:255'
        ]);

        $order = Order::findOrFail($id);

        if($order->courier_invoice){
            return redirect()->back()->with('error', 'Sorry! Courier already submitted.');
        }

        try{
            $courier = SteadFastRepo::createOrder($order->id, $order->shipping_full_name, $request->phone_number, $request->address, ($request->collect_amount ?? $order->due), $request->note);

            if(isset($courier['status']) && $courier['status'] == 200){
                // Update Order
                $order->courier_invoice = $courier['consignment']['invoice'];
                $order->courier = 'Steadfast';
                $order->courier_status = 'in_review';
                $order->shipping_street = $request->address;
                $order->shipping_mobile_number = $request->phone_number;
                $order->note = $request->note;
                $order->save();

                return redirect()->back()->with('success', 'Courier submitted success!');
            }else{
                return redirect()->back()->with('error', 'Steadfast API error!');
            }
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Steadfast API error!');
        }
    }

    public function getECourierInfo(){
        try{
            $info = eCourierRepo::getInfo();

            $packages = $info['packages'];
            // $cities = $info['cities'];
            // $thana_list = $info['thana_list'];

            $packages_html = view('back.orders.eCourierInfo', compact('packages'))->render();
            // $cities_html = '<option value="">Select City</option>';
            // $thana_html = '<option value="">Select Thana</option>';
            // foreach($thana_list as $thana){
            //     $thana_html .= '<option value="'. ($thana['value']) .'">'. ($thana['name']) .'</option>';
            // }

            return [
                'status' => true,
                'packages' => $packages_html,
                // 'cities' => $cities_html,
                // 'thana' => $thana_html
            ];
        }catch(\Exception $e){
            return [
                'status' => false
            ];
        }
    }

    public function eCourierSearchLocation(Request $request){
        $search = $request->q;
        $query = eCourierLocations::where('all_name', 'LIKE', "%{$search}%")->take(100)->get();

        // Output
        $output = array();
        foreach ($query as $data){
            $output[] = ['id' => $data->id, 'text' => $data->all_name];
        }

        return response()->json($output);
    }

    public function sendECourierOrder($id, Request $request){
        $request->validate([
            'location' => 'required',
            'address' => 'required',
            'payment_method' => 'required',
            'package' => 'required',
            'collect_amount' => 'required',
            'phone_number' => 'required',
            'note' => 'nullable|max:255'
        ]);

        $order = Order::findOrFail($id);

        if($order->courier_invoice){
            return redirect()->back()->with('error', 'Sorry! Courier already submitted.');
        }

        try{
            $location = eCourierLocations::find($request->location);
            if(!$location){
                return redirect()->back()->with('error', 'Location not found!');
            }
            $city = $location->city_value;
            $thana = $location->thana_value;
            $area = $location->area_value;
            $zip = $location->zip_value;

            $post_fields = array(
                'recipient_name' => $order->shipping_full_name,
                'recipient_mobile' => $request->phone_number,
                'recipient_city' => $city,
                'recipient_thana' => $thana,
                'recipient_area' => $area,
                'recipient_address' => $request->address,
                'package_code' => $request->package,
                'product_price' => ($request->collect_amount ?? $order->due),
                'payment_method' => $request->payment_method,
                'product_id' => $order->id,
                'comments' => $request->note,
                'recipient_zip' => $zip
            );

            $eCourier_order = eCourierRepo::storeOrder($post_fields);
            if($eCourier_order['status']){
                // Update Order
                $order->courier_invoice = $eCourier_order['id'];
                $order->courier = 'eCourier';
                $order->courier_status = 'Initiated';
                $order->shipping_street = $request->address;
                $order->shipping_mobile_number = $request->phone_number;
                $order->note = $request->note;
                $order->save();

                return redirect()->back()->with('success', 'eCourier submitted success!');
            }
            return redirect()->back()->with('error', 'eCourier API error!');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Steadfast API error!');
        }
    }

    public function sendPaperflyOrder($id, Request $request){
        $request->validate([
            'phone_number' => 'required',
            'thana' => 'required',
            'district' => 'required',
            'address' => 'required',
            'collect_amount' => 'required',
            'size' => 'required',
            'delivery_option' => 'required',
            'weight' => 'required',
            'note' => 'nullable|max:255'
        ]);

        $order = Order::findOrFail($id);
        return redirect()->back()->with('error', 'Paperfly API error!');

        if($order->courier_invoice){
            return redirect()->back()->with('error', 'Sorry! Courier already submitted.');
        }

        // try{

            $post_fields = array(
                'merOrderRef' => $order->id,
                'productSizeWeight' => $request->size,
                'productBrief' => $request->note,
                'packagePrice' => ($request->collect_amount ?? $order->due),
                'deliveryOption' => $request->delivery_option,
                'custname' => $order->shipping_full_name,
                'custaddress' => $request->address,
                'customerThana' => $request->thana,
                'customerDistrict' => $request->district,
                'custPhone' => $request->phone_number,
                'max_weight' => $request->weight
            );

            $eCourier_order = PaperflyRepo::curl('OrderPlacement', $post_fields);
            if($eCourier_order['status']){
                // Update Order
                $order->courier_invoice = $eCourier_order['id'];
                $order->courier = 'eCourier';
                $order->courier_status = 'Initiated';
                $order->shipping_street = $request->address;
                $order->shipping_mobile_number = $request->phone_number;
                $order->note = $request->note;
                $order->save();

                return redirect()->back()->with('success', 'Paperfly submitted success!');
            }
            return redirect()->back()->with('error', 'Paperfly API error!');
        // }catch(\Exception $e){
        //     return redirect()->back()->with('error', 'Steadfast API error!');
        // }
    }
}
