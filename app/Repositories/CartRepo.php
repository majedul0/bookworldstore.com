<?php

namespace App\Repositories;

use App\Models\Coupon;
use App\Models\Order\Order;
use App\Models\Product\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Info;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class CartRepo {
    public static function get(){
        $session_id = Session::getId();

        $cart = Cart::query();
        $cart->with('Product', 'ProductData');
        $cart->whereHas('Product', function($p){
            $p->where('status', 1)->where('deleted_at', null);
        });
        if(auth()->check()){
            $cart->where('user_id', auth()->user()->id);
        }else{
            $cart->where('session_id', $session_id);
        }
        $cart = $cart->latest('id')->get();

        return $cart;
    }

    public static function summary($shipping_charge = 0, $shipping_method = 'N/A', $shipping_id = '', $hidden_shipping_charge = ''){
        $carts = (new static)->get();
        $product_total = 0;
        $only_discount = 0;
        $discount = 0;
        $shipping_weight = 0;
        $tax_amount = 0;

        foreach($carts as $cart){
            $product_total += $cart->ProductData->custom_sale_price * $cart->quantity;
            if($cart->ProductData->shipping_weight){
                $shipping_weight += $cart->ProductData->shipping_weight * $cart->quantity;
            }
            $tax_amount += $cart->ProductData ? ($cart->ProductData->calculated_tax_amount_discount * $cart->quantity) : 0;
        }

        return [
            'product_total' => $product_total,
            'tax_amount' => $tax_amount,
            'discount' => $discount,
            'only_discount' => $only_discount,
            'shipping_charge' => $shipping_charge,
            'n_shipping_weight' => $shipping_weight,
            'subtotal' => ($product_total + $tax_amount + $shipping_charge) - $discount,
            'shipping_method' => $shipping_method,
            'shipping_service_id' => $shipping_id,
            'hidden_shipping_charge' => $hidden_shipping_charge,
            'carts' => $carts,
            'count' => count($carts)
        ];
    }

    public static function refresh(){
        $carts = (new static)->get();

        foreach($carts as $cart){
            if(!$cart->Product){
                $cart->delete();
            }
            // else{
            //     if($cart->ProductData->stock < 1){
            //         $cart->delete();
            //     }else{
            //         if($cart->quantity > $cart->ProductData->stock){
            //             $cart->quantity = $cart->ProductData->stock;
            //             $cart->save();
            //         }
            //     }
            // }
        }

        return true;
    }

    public static function updateRelation($relation_id, $type = 'login'){
        $new_session_id = Session::getId();
        if($type == 'login'){
            $carts = Cart::where('user_id', null)->where('session_id', $relation_id)->latest('id')->get();
            foreach($carts as $cart){
                // Check old cart
                $check_old = Cart::where('user_id', auth()->user()->id)->where('product_id', $cart->product_id)->where('product_data_id', $cart->product_data_id)->first();

                if($check_old){
                    // Update old cart
                    $check_old->quantity = $cart->quantity;
                    $check_old->session_id = $new_session_id;
                    $check_old->save();

                    // Delete new cart
                    $cart->delete();
                }else{
                    // Update new cart
                    $cart->user_id = auth()->user()->id;
                    $cart->session_id = $new_session_id;
                    $cart->save();
                }
            }
        }else{
            DB::table('carts')->where('user_id', $relation_id)->update([
                'session_id' => $new_session_id
            ]);
        }

        return true;
    }

    public static function checkCoupon($code){
        $minutes = 15;
        $product_total = 0;

        // Check Use
        $order = Order::where('user_id', auth()->user()->id)->where('coupon_code', $code)->first();

        if($order){
            return [
                'status' => false,
                'text' => 'You have already used this coupon!'
            ];
        }

        // $cart_summary = (new static)->summary();
        $carts = (new static)->get();
        foreach($carts as $cart){
            $product_total += $cart->ProductData->sale_price * $cart->quantity;
        }

        $coupon = Coupon::where('code', $code)->where('status', 1)->first();

        if($coupon){
            // Check user
            if($coupon->user_id){
                if(!auth()->check()){
                    Cookie::queue('coupon', null, $minutes);

                    return [
                        'status' => false,
                        'text' => 'You are not allow to use this coupon!'
                    ];
                }

                if($coupon->user_id != auth()->user()->id){
                    Cookie::queue('coupon', null, $minutes);

                    return [
                        'status' => false,
                        'text' => 'You are not allow to use this coupon!'
                    ];
                }
            }

            // Check Use
            if($coupon->maximum_use && $coupon->maximum_use <= $coupon->total_use){
                Cookie::queue('coupon', null, $minutes);

                return [
                    'status' => false,
                    'text' => 'The coupon is not available!'
                ];
            }

            // Check date
            if($coupon->expiry_date){
                if($coupon->expiry_date < Carbon::now()){
                    Cookie::queue('coupon', null, $minutes);

                    return [
                        'status' => false,
                        'text' => 'Your coupon has expired!'
                    ];
                }
            }

            // Check minimum spent
            if($coupon->minimum_spend){
                if($product_total < $coupon->minimum_spend){
                    $minimum_string = (Info::Settings('general', 'currency_name') ?? '$') . $coupon->minimum_spend;

                    Cookie::queue('coupon', null, $minutes);

                    return [
                        'status' => false,
                        'text' => "Product price must be getter then or equal $minimum_string to use tis coupon!"
                    ];
                    // return redirect()->back()->with('error', "Product price must be getter then or equal $minimum_string to use tis coupon!");
                    // $coupon_status = false;
                }
            }

            // Check maximum spent
            if($coupon->maximum_spend){
                if($product_total > $coupon->maximum_spend){
                    $maximum_string = (Info::Settings('general', 'currency_name') ?? '$') . $coupon->maximum_spend;

                    Cookie::queue('coupon', null, $minutes);

                    return [
                        'status' => false,
                        'text' => "Product price must be lower then or equal $maximum_string to use tis coupon!"
                    ];
                    // return redirect()->back()->with('error', "Product price must be lower then or equal $maximum_string to use tis coupon!");
                    // $coupon_status = false;
                }
            }

            // Cookie::queue('coupon', $code, $minutes);
            if($coupon->discount_type == 'fixed'){
                $discount = $coupon->amount;
            }else{
                $discount =  ($product_total * $coupon->amount) / 100;
            }

            return [
                'status' => true,
                'text' => 'Coupon applied successfully.',
                'discount' => $discount,
                'only_discount' => $coupon->amount,
                'coupon' => $coupon
            ];
            // return redirect()->back()->with('success-alert', 'Coupon applied successfully.');
        }

        Cookie::queue('coupon', null, $minutes);
        return [
            'status' => false,
            'text' => 'Coupon is not active!'
        ];
        // return redirect()->back()->with('error-alert', 'Coupon is not active!');
    }
}

