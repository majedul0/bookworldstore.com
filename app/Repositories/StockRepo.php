<?php

namespace App\Repositories;

use App\Models\Product\ProductData;
use App\Models\ProductStockLedger;

class StockRepo {
    public static function ledger($product_data_id, $in_quantity, $out_quantity, $note = null){
        $stock_conversion_fact = null;

        $product_data = ProductData::find($product_data_id);
        if($product_data){
            if($product_data->type == 'Simple' && $product_data->stock_product_data){
                $stock_conversion_fact = $product_data->stock_conversion_fact;
                $product_data = $product_data->stock_product_data;
            }
            $stock_ledger = new ProductStockLedger;
            $stock_ledger->product_id = $product_data->product_id;
            $stock_ledger->product_data_id = $product_data_id;
            if($stock_conversion_fact && $stock_conversion_fact > 0){
                if($in_quantity > 0){
                    $in_quantity = $in_quantity * $stock_conversion_fact;
                }
                if($out_quantity > 0){
                    $out_quantity = $out_quantity * $stock_conversion_fact;
                }
            }
            $stock_ledger->in_quantity = $in_quantity;
            $stock_ledger->out_quantity = $out_quantity;
            $stock_ledger->previous_quantity = $product_data->stock;
            $stock_ledger->current_quantity = $product_data->stock + ($in_quantity - $out_quantity);
            $stock_ledger->note = $note;
            $stock_ledger->save();

            $product_data->stock = $stock_ledger->current_quantity;
            $product_data->save();

            ProductRepo::index($product_data->product_id);

            return $stock_ledger;
        }

        return null;
    }
    // public static function flashQuantities($product_data_id){
    //     $total_stock = Stock::groupBy('product_data_id')->where('product_data_id', $product_data_id)->where('current_quantity', '!=', 0)->selectRaw('sum(current_quantity) as sum')->first();

    //     $product_data = ProductData::find($product_data_id);
    //     if($product_data){
    //         $product_data->stock = $total_stock ? $total_stock->sum : 0;
    //         $product_data->save();

    //         // Update Product
    //         if($product_data->type == 'Simple'){
    //             $product = Product::find($product_data->product_id);

    //             if($product){
    //                 $product->stock = $product_data->stock;
    //                 $product->save();
    //             }
    //             // dd($product);
    //         }else{
    //             $product_total_stock = ProductData::where('type', 'Variable')->groupBy('product_id')->where('product_id', $product_data->product_id)->selectRaw('sum(stock) as sum')->first();

    //             if($product_total_stock){
    //                 $product = Product::find($product_data->product_id);

    //                 if($product){
    //                     $product->stock = $product_total_stock->sum;
    //                     $product->save();
    //                 }
    //             }
    //         }
    //         // dd($product);
    //     }

    //     return true;
    // }

    // public static function stockSale($product_data_id, $quantity, $order_product_id = null){
    //     $stocks = Stock::where('current_quantity', '>', 0)->where('product_data_id', $product_data_id)->orderBy('id')->get();
    //     // dd($stocks);

    //     $remaining_quantity = $quantity;
    //     foreach($stocks as $stock){
    //         if($remaining_quantity > 0){
    //             $current_stock = $stock->current_quantity;
    //             $new_current_stock = $stock->current_quantity - $remaining_quantity;
    //             $remaining_quantity = $remaining_quantity - $current_stock;

    //             if($new_current_stock < 0){
    //                 $stock->current_quantity = 0;
    //             }else{
    //                 $stock->current_quantity = $new_current_stock;
    //             }
    //             $stock->save();

    //             // Add Order Product Stock
    //             if($order_product_id){
    //                 $order_product_stock = new OrderProductStock;
    //                 $order_product_stock->order_product_id = $order_product_id;
    //                 $order_product_stock->stock_id = $stock->id;
    //                 $order_product_stock->quantity = $current_stock - $new_current_stock;
    //                 $order_product_stock->save();
    //             }
    //         }
    //     }

    //     return true;
    // }

    // public static function adjustment($total_amount, $note = null){
    //     $adjustment = new Adjustment;
    //     $adjustment->total_amount = $total_amount;
    //     $adjustment->added_by = auth()->user()->full_name;
    //     $adjustment->note = $note;
    //     $adjustment->save();

    //     return $adjustment;
    // }

    // public static function stock($adjustment_id, $product_id, $product_data_id, $purchase_quantity, $current_quantity, $purchase_price, $note = null){
    //     $stock = new Stock;
    //     $stock->adjustment_id = $adjustment_id;
    //     $stock->product_id = $product_id;
    //     $stock->product_data_id = $product_data_id;

    //     $stock->purchase_quantity = $purchase_quantity;
    //     $stock->current_quantity = $current_quantity;
    //     $stock->purchase_price = $purchase_price;

    //     $stock->note = $note;
    //     $stock->save();

    //     // Flash Quantities
    //     (new static)->flashQuantities($stock->product_data_id);

    //     return $stock;
    // }
}
