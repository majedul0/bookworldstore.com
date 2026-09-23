<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Favorite;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Adjustment;
use App\Models\Product\Cart;
use App\Models\Product\Product;
use App\Models\Product\ProductQuotes;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherPageController extends Controller
{
    public function dashboard(){
        $recent_orders = Order::where('created_at', '>', Carbon::now()->subDays(15))->latest('id')->get();
        $now = Carbon::now();

        // Yearly Overview
        $order_overview = Order::where('status', 'Completed')
        ->whereYear('created_at', '>=', $now->subMonth(11)->year)
        ->select(DB::raw('SUM((shipping_charge + product_total + tax_amount + other_cost) - discount_amount) as `amount`'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') date"))
        ->groupby('date')
        ->orderByDesc('date')
        ->get();
        $purchase_overview = Purchase::whereYear('created_at', '>=', $now->subMonth(11)->year)
        ->select(DB::raw('SUM(grand_total) as `amount`'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') date"))
        ->groupby('date')
        ->orderByDesc('date')
        ->get();
        $months = array();
        $amounts = array();
        $purchase_amounts = array();
        for($i = 0; $i < 12; $i++){
            $new_date = Carbon::now()->subMonth(11 - $i);
            $date = date('Y-m', strtotime($new_date));
            $months[$i] = date('M', strtotime($new_date));
            $amounts[$i] = 0;
            $yearly_purchase_amounts[$i] = 0;

            foreach($order_overview as $order){
                if($order->date == $date){
                    $amounts[$i] = $order->amount;
                }
            }

            // Purchase
            foreach($purchase_overview as $purchase){
                if($purchase->date == $date){
                    $yearly_purchase_amounts[$i] = $purchase->amount;
                }
            }
        }

        // Monthly Overview
        $order_overview_monthly = Order::where('status', 'Completed')
        ->whereDate('created_at', '>=', Carbon::now()->subDay(29))
        ->select(DB::raw('SUM((shipping_charge + product_total + tax_amount + other_cost) - discount_amount) as `amount`'), DB::raw("DATE_FORMAT(created_at, '%d') date"))
        ->groupby('date')
        ->orderByDesc('date')
        ->get();
        $purchase_overview_monthly = Purchase::whereDate('created_at', '>=', Carbon::now()->subDay(29))
        ->select(DB::raw('SUM(grand_total) as `amount`'), DB::raw("DATE_FORMAT(created_at, '%d') date"))
        ->groupby('date')
        ->orderByDesc('date')
        ->get();

        $days = array();
        $monthly_amounts = array();
        for($i = 0; $i < 30; $i++){
            $new_date = Carbon::now()->subDay(29 - $i);
            $date = date('d', strtotime($new_date));
            $days[$i] = date('d', strtotime($new_date));
            $monthly_amounts[$i] = 0;
            $monthly_purchase_amounts[$i] = 0;

            foreach($order_overview_monthly as $order_monthly){
                if($order_monthly->date == $date){
                    $monthly_amounts[$i] = $order_monthly->amount;
                }
            }

            // Purchase
            foreach($purchase_overview_monthly as $purchase_monthly){
                if($purchase_monthly->date == $date){
                    $monthly_purchase_amounts[$i] = $purchase_monthly->amount;
                }
            }
        }

        $top_products = $this->topProductsQuery('Daily');


        // Statistics
        $total_orders = Order::where('status', 'Completed')->count();
        $pending_orders = Order::where('status', 'Pending')->count();
        // $this_week_orders = Order::where('status', 'Completed')->where('created_at', '>=', Carbon::now()->subDay(7))->count();
        $total_customers = User::active('id', 'customer')->count();
        $todays_orders = Order::whereDate('created_at', '>=', Carbon::now())->count();

        return view('back.dashboard', compact('recent_orders', 'months', 'amounts', 'days', 'monthly_amounts', 'top_products', 'total_orders', 'pending_orders', 'total_customers', 'todays_orders', 'monthly_purchase_amounts', 'yearly_purchase_amounts'));
    }

    public function topProducts(Request $request){
        $top_products = $this->topProductsQuery($request->type);

        $output = '';
        foreach($top_products as $top_product){
            $output .= '<tr>
                            <td><img src="'. $top_product->Product->img_paths['small'] .'" class="img_sm"></td>
                            <td>
                                <a target="_blank" href="'. ($top_product->Product->route ?? '#') .'">'. ($top_product->Product->title ?? 'N/A') .'</a>
                                <br>
                                <small>'. amount($top_product->Product->prices['sale_price'] ?? 0) .'</small>
                            </td>
                            <td class="text-right">
                                '. amount($top_product->amount) .'
                                <br>
                                <small>'. $top_product->total_sales .' Sold</small>
                            </td>
                        </tr>';
        }

        return $output;
    }

    public function topProductsQuery($type){
        if($type == 'Weekly'){
            $date = Carbon::now()->subDay(7);
        }elseif($type == 'Monthly'){
            $date = Carbon::now()->subDay(30);
        }else{
            $date = Carbon::now();
        }

        $top_products = OrderProduct::query();
        $top_products->with('Product');
        if($type == 'Daily'){
            $top_products->whereDate('created_at', '>=', $date);
        }else{
            $top_products->where('created_at', '>=', $date);
        }
        $top_products->whereHas('Order', function($q){
            $q->where('status', 'Completed');
        })
        ->select(DB::raw('SUM(sale_price) as `amount`'), DB::raw('SUM(quantity) as `total_sales`'), 'product_id')
        ->groupby('product_id')
        ->orderByDesc('total_sales');

        $top_products = $top_products->take(15)->get();

        return $top_products;
    }

    public function carts(){
        Cart::where('admin_read', 2)->update(['admin_read' => 1]);
        $carts = Cart::with('User', 'Product', 'ProductData')->get();

        return view('back.others.carts', compact('carts'));
    }
    public function cartDelete(Cart $cart){
        $cart->delete();

        return redirect()->back()->with('success-alert', 'Cart deleted successfully.');
    }

    public function wishlists(){
        Favorite::where('admin_read', 2)->update(['admin_read' => 1]);
        $wishlists = Favorite::with('User', 'Product')->get();

        return view('back.others.wishlists', compact('wishlists'));
    }
    public function wishlistDelete(Favorite $favorite){
        $favorite->delete();

        return redirect()->back()->with('success-alert', 'Wishlist deleted successfully.');
    }
    public function productQuotes(){
        ProductQuotes::where('admin_read', 2)->update(['admin_read' => 1]);
        $quotes = ProductQuotes::latest('id')->get();

        return view('back.others.productQuotes', compact('quotes'));
    }

    public function contactMessages(){
        ContactMessage::where('admin_read', 2)->update(['admin_read' => 1]);
        $messages = ContactMessage::latest('id')->get();

        return view('back.others.contactMessages', compact('messages'));
    }

    public function contactMessageDelete(ContactMessage $contact_message){
        $contact_message->delete();

        return redirect()->back();
    }

    public function show(Request $request){
        if($request->type == 'product'){
            $product = Product::find($request->item_id);

            if($product)
                return view('back.product.showAjax', compact('product'));
        }elseif($request->type == 'adjustment'){
            $adjustment = Adjustment::find($request->item_id);

            if($adjustment)
                return view('back.product.adjustment.showAjax', compact('adjustment'));
        }
    }
}
