<?php

namespace App\Repositories;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductData;
use App\Models\Product\Review;

class ProductRepo
{
    public static function insertProductCategory($product_id, $category_id)
    {
        $category_relation = new ProductCategory;
        $category_relation->product_id = $product_id;
        $category_relation->category_id = $category_id;
        $category_relation->save();

        return $category_relation;
    }

    public static function filter($category, $brands, $prices, $discounts, $ratings, $skip, $take, $order, $brand = null)
    {
        $output = [
            'total_count' => 0,
            'items' => []
        ];

        $query = ProductCategory::query();
        $query->with('Product');
        $query->join('products', 'products.id', '=', 'product_categories.product_id');
        $query->join('product_data', 'product_data.product_id', '=', 'product_categories.product_id');
        $query->where('status', 1)->where('products.deleted_at', null)->where('products.stock', '>', 0);

        if ($category) {
            $query->where('product_categories.category_id', $category);
        }

        if (count((array)$brands)) {
            $query->whereIn('products.brand_id', (array)$brands);
        }
        if($brand){
            $query->where('products.brand_id', $brand);
        }

        if (count((array)$prices)) {
            $start_price = 0;
            $end_price = 0;
            if(isset($prices[0])){
                $start_price = explode('-', $prices[0])[0];
            }

            foreach($prices as $price_data){
                $end_price = explode('-', $price_data)[1];
            }
            $query->where('product_data.sale_price', '>=', $start_price)->where('product_data.sale_price', '<=', $end_price);
        }

        if (count((array)$ratings)) {
            $query->whereIn('products.average_rating', (array)$ratings);
        }

        if (count((array)$discounts)) {
            if (in_array('all', $discounts)) {
                $query->where('product_data.regular_price', '!=', null);
            } else {
                foreach ((array)$discounts as $discount) {
                    $query->where('product_data.discount_percent', '>', $discount);
                }
            }
        }

        if ($order == 'Price Low-High') {
            $query->orderby('product_data.sale_price', 'ASC');
        } elseif ($order == 'Price High-Low') {
            $query->orderby('product_data.sale_price', 'DESC');
        } elseif ($order == 'Highest Rated') {
            $query->orderby('products.average_rating', 'DESC');
        } else {
            $query->orderby('products.id', 'DESC');
        }

        $query->select('product_categories.product_id');
        $query->distinct('product_categories.product_id');

        $output['total_count'] = $query->count();
        $output['items'] = $query->skip($skip)->take($take)->get();

        return $output;
    }

    public static function flashReviewRating($product_id){
        $reviews = Review::where('product_id', $product_id)->active()->get();
        $rating = 0;

        foreach($reviews as $review){
            $rating += $review->rating;
        }

        if($rating > 0){
            $average_rating = $rating / count($reviews);
        }else{
            $average_rating = 0;
        }

        $product = Product::find($product_id);
        $product->average_rating = $average_rating;
        $product->total_review = count($reviews);
        $product->save();

        return true;
    }

    public static function index($product_id){
        $product = Product::find($product_id);

        if($product){
            $product->sale_price = $product->prices['sale_price'] ?? 0;
            $product->regular_price = $product->prices['regular_price'] ?? 0;

            if($product->type == 'Variable'){
                $array = array();
                $datas = $product->VariableProductData;
                foreach($datas as $data){
                    foreach($data->attribute_items_arr as $attribute_item){
                        $array[] = $attribute_item;
                    }
                }
                $product->attribute_items_id = json_encode($array);

                $total_stock = ProductData::where('product_id', $product->id)->where('type', 'Variable')->sum('stock');

                $product->fist_price = $product->VariableProductData->sortBy('sale_price')->first()->sale_price ?? null;
                $product->last_price = $product->VariableProductData->sortByDesc('sale_price')->first()->sale_price ?? null;
            }else{
                $total_stock = $product->ProductData->stock ?? 0;
            }

            $product->stock = $total_stock;
            $product->save();
        }

        return true;
    }

    public static function averagePurchasePrice($product_data_id, $in_user_id, $purchase_price_now, $purchase_quantity){
        $product_data = ProductData::where('user_id', $in_user_id)->find($product_data_id);
        if($product_data){
            $cost = $product_data->cost ?? 0;
            if($cost > 0 && $product_data->stock > 0){
                $allOldQuantity = $product_data->stock;
                $allNewQuantity = $purchase_quantity;

                $allOldPrices = $cost * $product_data->stock;
                $allNewPrices = $purchase_price_now * $purchase_quantity;

                $newQuantity = $allOldQuantity + $allNewQuantity;

                if ($newQuantity && $newQuantity > 0){
                    $averagePrice = ($allOldPrices + $allNewPrices) / $newQuantity;
                    $product_data->cost = $averagePrice;
                    $product_data->save();
                }
            }else{
                $product_data->cost = $purchase_price_now;
                $product_data->save();
            }

            return $product_data;
        }

        return null;
    }

    public static function sales($order_product, $in_quantity, $out_quantity, $note){
        // Stock Ledger
        if($order_product->Product && $order_product->Product->type == 'Bundle'){
            foreach($order_product->Product->bundle_product_items as $bundle_product_item){
                StockRepo::ledger($bundle_product_item->ProductData->id, $in_quantity, $out_quantity, $note);
            }
        }else{
            StockRepo::ledger($order_product->product_data_id, $in_quantity, $out_quantity, $note);
        }

        // Packing Material
        // if(count($order_product->Product->packing_materials)){
        //     foreach($order_product->Product->packing_materials as $packing_material){
        //         if($out_quantity > 0){
        //             StockRepo::ledger($packing_material->product_data_id, 0, ($packing_material->conversion_factor * $out_quantity), 'Packing Material Stock out on Sales');
        //         }
        //         if($in_quantity > 0){
        //             StockRepo::ledger($packing_material->product_data_id, ($packing_material->conversion_factor * $in_quantity), 0, 'Packing Material Stock in on Sales');
        //         }
        //     }
        // }

        return false;
    }
}
