<?php

namespace App\Repositories;

use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderStatus;
use App\Models\OrderPayment;
use App\Models\Product\ProductData;

class OrderRepo {
    public static function paid($order_id){
        // $income = 0;
        $order = Order::find($order_id);

        // foreach($order->OrderProducts as $order_product){
        //     foreach($order_product->OrderProductStocks as $order_product_stock){
        //         if($order_product_stock->Stock->purchase_price){
        //             $profit_per_item = $order_product->sale_price - $order_product_stock->Stock->purchase_price;

        //             $income += $profit_per_item * $order_product_stock->quantity;
        //         }
        //     }
        //     // $income += 0;
        // }

        // dd($income);

        // Income
        if($order->grand_total > 0){
            AccountsRepo::income($order->grand_total, "Income from order #$order_id");
        }
    }

    public static function payment($id, $amount, $gateway_order_id, $txn_number, $note = null, $card_number = null){
        $order_payment = new OrderPayment;
        $order_payment->order_id = $id;
        $order_payment->amount = $amount;
        $order_payment->gateway_order_id = $gateway_order_id;
        $order_payment->txn_number = $txn_number;
        $order_payment->note = $note;
        $order_payment->card_number = $card_number;
        $order_payment->save();

        return $order_payment;
    }

    // Insert order status
    public static function status($order_id, $status, $added_by = null){
        $order_status = new OrderStatus;
        $order_status->order_id = $order_id;
        $order_status->status = $status;
        $order_status->added_by = $added_by;
        $order_status->save();
    }

    // Insert order products
    public static function product($order_id, $product_id, $product_data_id, $sale_price, $quantity, $simple_attr_data = null){
        $product_data = ProductData::find($product_data_id);

        $order_product = new OrderProduct;
        $order_product->order_id = $order_id;

        $order_product->product_id = $product_id;
        $order_product->product_data_id = $product_data->id;
        $order_product->sale_price = $sale_price;
        $order_product->quantity = $quantity;

        $order_product->title = $product_data->Product->title ?? '';
        $order_product->attribute_item_ids = $product_data->attribute_item_ids;

        $order_product->shipping_weight = $product_data->shipping_weight ?? 0;

        // TAX
        $order_product->tax_amount = $product_data->tax_amount;
        $order_product->tax_type = $product_data->tax_type;
        $order_product->tax_method = $product_data->tax_method;

        $order_product->simple_attributes = json_encode($simple_attr_data);

        $order_product->save();

        return $order_product;
    }

    public static function index($order_id){
        $order = Order::find($order_id);

        $order_products = OrderProduct::where('order_id', $order_id)->get();

        $product_total = 0;
        foreach($order_products as $order_product){
            $product_total += $order_product->sale_price * $order_product->quantity;
        }

        $order->product_total = $product_total;
        $order->save();

        return true;
    }

    public static function completed($order_id){
        $order = Order::find($order_id);
        if($order && $order->status != 'Completed'){
            $order->paid_amount = $order->paid_amount + $order->due;
            $order->status = 'Completed';

            // Add Payment to Customer
            // if($order->due > 0){
            //     UserRepo::ledger($order->customer_id, 0, $order->due, ('Order payment on order Completed #' . $order->id), $order->id, $order->reference_no);
            // }

            $order->save();
        }

        return $order;
    }
}
