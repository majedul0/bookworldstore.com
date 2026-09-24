<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product\Cart;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductData;
use App\Repositories\CartRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function cart(){
        // Refresh
        CartRepo::refresh();

        $carts = CartRepo::summary();

        // $p_ids = $carts['carts']->pluck('product_id')->toArray();
        // $cids = ProductCategory::whereIn('product_id', $p_ids)->pluck('category_id')->toArray();

        // $related_products = ProductCategory::with('Product')
        //     ->join('products', 'products.id', '=', 'product_categories.product_id')
        //     ->where('products.status', 1)
        //     ->where('products.deleted_at', null)
        //     ->whereIn('category_id', $cids)
        //     ->whereNotIn('product_categories.product_id', $p_ids)
        //     ->select('product_categories.product_id')
        //     ->distinct()
        //     ->latest('product_categories.product_id')
        //     ->take(24)->get();
        // $related_products = $related_products->pluck('Product');

        return view('front.cart', compact('carts'));
    }

    public function checkout(){
        // Refresh
        CartRepo::refresh();

        $carts = CartRepo::summary();
        $payment_methods = \App\Models\PaymentMethod::active()->orderBy('position')->get();

        return view('front.checkout', compact('carts', 'payment_methods'));
    }

    public function cartInfo(){
        $cart_summary = CartRepo::summary();

        $cart_items = '';
        foreach($cart_summary['carts'] as $cart){
            $cart_items .= view('front.layouts.cart-loop', compact('cart'))->render();
        }

        return [
            'status' => true,
            'cart_count' => $cart_summary['count'],
            'cart_amount' => number_format($cart_summary['product_total'], 2),
            'cart_items' => $cart_items,
            'message' => 'Cart fetch success'
        ];
    }

    public function add(Request $request){
        $quantity = $request->quantity ?? 1;
        $summary = $this->storeCart($request->product_id, $quantity, $request->product_variation);

        $cart_items = '';
        foreach($summary['carts'] as $cart){
            $cart_items .= view('front.layouts.cart-loop', compact('cart'))->render();
        }

        return [
            'status' => true,
            'cart_count' => $summary['count'] ?? 0,
            'cart_amount' => $summary['product_total'],
            'cart_items' => $cart_items,
            'message' => 'Add to cart success',
        ];
    }

    public function directOrder(request $request){
        if(!$request->product){
            abort(404);
        }

        $quantity = $request->quantity ?? 1;
        $this->storeCart($request->product, $quantity, $request->product_data_id);

        return redirect()->route('cart');
    }

    public function storeCart($product_id, $quantity, $data_id = null){
        $product = Product::find($product_id);
        if($data_id){
            $product_data = ProductData::find($data_id);
        }else{
            if($product->type == 'Variable'){
                $product_data = $product->VariableProductData[0];
            }else{
                $product_data = $product->ProductData;
            }
        }
        // if($product_data && $product_data->stock < 1){
        //     return [
        //         'status' => false,
        //         'text' => 'Out of Stock!',
        //         'cart_summary' => false,
        //         'new_item' => ''
        //     ];
        // }

        $session_id = Session::getId();

        $cart = Cart::query();
        if(auth()->check()){
            $cart->where('user_id', auth()->user()->id);
        }else{
            $cart->where('session_id', $session_id);
        }
        $cart = $cart->where('product_id', $product_id)->where('product_data_id', $product_data->id)->first();

        if($cart){
            return CartRepo::summary();
        }

        $cart = new Cart;

        if(auth()->check()){
            $cart->user_id = auth()->user()->id;
        }else{
            $cart->session_id = $session_id;
        }
        $cart->product_id = $product_id;
        $cart->product_data_id = $product_data->id;
        $cart->quantity = $quantity > 0 ? $quantity : 1;
        $cart->save();

        return CartRepo::summary();
    }

    public function remove($id){
        $cart = Cart::find($id);

        if($cart){
            $cart->delete();

            // $total_cart = CartRepo::get();
            // $cart_summary = CartRepo::summary();

            // return [
            //     'status' => true,
            //     'text' => 'Cart deleted success.',
            //     'count' => count($total_cart),
            //     'cart_summary' => $cart_summary,
            //     'product_total' =>$cart_summary['product_total']
            // ];
        }
        return redirect()->back()->with('success-alert', 'Cart deleted!');

        // return [
        //     'status' => false,
        //     'text' => 'Something wring!'
        // ];
    }

    public function update(Request $request){
        $cart = Cart::find($request->cart_id);
        if($cart){
            $cart->quantity = $request->quantity;
            $cart->save();
        }

        return [
            'summary' => CartRepo::summary(),
            'single_amount' => $cart->ProductData->sale_price * $cart->quantity
        ];
    }
}
