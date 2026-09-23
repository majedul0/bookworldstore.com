<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MissingOrder;
use App\Models\Product\Product;
use App\Repositories\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class APIController extends Controller
{
    public function missingOrder(Request $request){
        // $data = $request->all(); // Get all request data
        // $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        // Storage::disk('local')->put('all-data.json', $jsonContent);

        $api_1 = $request->api_1;
        $api_2 = $request->api_2;
        if(env('SAAS_API_KEY_1') != $api_1 || env('SAAS_API_KEY_2') != $api_2){
            return JsonResponse::onlyMessage('Unauthorized', false, 401);
        }

        $skip = $request->skip ?? 0;
        $take = $request->take ?? 30;
        $orders = MissingOrder::with('order_items');
        if($request->after){
            $orders->where('created_at', '>=', $request->after);
        }
        if($request->before){
            $orders->where('created_at', '<=', $request->before);
        }
        $orders = $orders->skip($skip)->take($take)->get();

        return JsonResponse::withData($orders);
    }

    public function products(Request $request){
        $api_1 = $request->api_1;
        $api_2 = $request->api_2;
        if(env('SAAS_API_KEY_1') != $api_1 || env('SAAS_API_KEY_2') != $api_2){
            return JsonResponse::onlyMessage('Unauthorized', false, 401);
        }

        $skip = $request->skip ?? 0;
        $take = $request->take ?? 30;
        $products = Product::with('ProductData', 'VariableProductData')->where('status', 1)->skip($skip)->take($take)->get();

        $products_arr = [];
        foreach($products as $key => $product){
            $products_arr[$key]['title'] = $product->title;
            $products_arr[$key]['image'] = $product->img_paths['original'];
            $products_arr[$key]['description'] = $product->description;
            $products_arr[$key]['type'] = $product->type;
            $products_arr[$key]['simple_product_data'] = null;
            $products_arr[$key]['variable_product_data'] = [];
            if($product->type == 'Simple'){
                $products_arr[$key]['simple_product_data'] = [
                    'sale_price' => $product->ProductData->sale_price ?? 1,
                    'regular_price' => $product->ProductData->regular_price ?? null,
                ];
            }else{
                foreach($product->VariableProductData as $product_data){
                    $products_arr[$key]['variable_product_data'][] = [
                        'sale_price' => $product_data->sale_price ?? 1,
                        'regular_price' => $product_data->regular_price ?? null,
                        'attributes' => $product_data->attribute_items_b_string ?? '',
                    ];
                }
            }
        }

        return JsonResponse::withData($products_arr);
    }
}
