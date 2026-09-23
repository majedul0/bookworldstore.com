<?php

namespace App\Http\Controllers\Back;

use App\Exports\OrderExport;
use App\Exports\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\Product\Review;
use App\Models\PurchaseItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Info;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\App;

class ReportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('overview', 'product', 'productDetails', 'orders');
    }

    // Overview
    public function overview(){

        $now = Carbon::now();
        $order_overview = Order::where('status', 'Completed')
        ->whereYear('created_at', '>=', $now->subMonth(11)->year)
        ->select(DB::raw('SUM((shipping_charge + product_total + tax_amount + other_cost) - discount_amount) as `amount`'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') date"))
        ->groupby('date')
        ->orderByDesc('date')
        ->get();

        // Yearly Overview
        $months = array();
        $amounts = array();
        for($i = 0; $i < 12; $i++){
            $new_date = Carbon::now()->subMonth(11 - $i);
            $date = date('Y-m', strtotime($new_date));
            $months[$i] = date('M', strtotime($new_date));
            $amounts[$i] = 0;

            foreach($order_overview as $order){
                if($order->date == $date){
                    $amounts[$i] = $order->amount;
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

        $days = array();
        $monthly_amounts = array();
        for($i = 0; $i < 30; $i++){
            $new_date = Carbon::now()->subDay(29 - $i);
            $date = date('d', strtotime($new_date));
            $days[$i] = date('d', strtotime($new_date));
            $monthly_amounts[$i] = 0;

            foreach($order_overview_monthly as $order_monthly){
                if($order_monthly->date == $date){
                    $monthly_amounts[$i] = $order_monthly->amount;
                }
            }
        }

        // Statistics
        $total_orders = Order::where('status', 'Completed')->count();
        $total_customers = User::active('id', 'customer')->count();
        $products = Product::active()->count();
        $reviews = Review::active()->count();

        return view('back.report.overview', compact('months', 'amounts', 'days', 'monthly_amounts', 'total_orders', 'total_customers', 'products', 'reviews'));
    }

    public function product(Request $request){
        $type = $request->type ?? 'filter';

        if($type == 'filter'){
            return view('back.report.product.index');
        }else{
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 3600);

            $query = Product::query();
            $query = $query->orderBy('id')->get();
            if($type == 'excel'){
                return Excel::download(new ProductExport($query, $request->from_date, $request->to_date), 'Product_report_'. date("Y-m-d-H-i-s") .'.xlsx');
            }else{
                $html = view('back.report.product.exportPDF')->with([
                    'products' => $query,
                    'from_date' => $request->from_date,
                    'to_date' => $request->to_date,
                ])->render();

                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($html)->setPaper('a4', 'landscape');
                return $pdf->stream();
            }
        }
    }
    public function productTable(Request $request){
        // Get Data
        $columns = array(
            0 => 'id',
            1 => 'title',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = Product::query();

        // Search
        if ($request->input('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('title', 'LIKE', "%{$search}%");
            });
        }

        // Count Items
        $totalFiltered = $query->count();
        if ($limit == "-1") {
            $query->skip($start)->limit($totalFiltered);
        } else {
            $query->skip($start)->limit($limit);
        }
        $query = $query->orderBy($order, $dir)->get();

        $output = array();
        foreach ($query as $key => $data) {
            $purchase = PurchaseItem::query();
            // Date Filter
            if($request->from_date){
                $purchase->whereDate('created_at', '>=', $request->from_date);
            }
            if($request->to_date){
                $purchase->whereDate('created_at', '<=', $request->to_date);
            }
            $purchase->where('product_id', $data->id)->select(DB::raw('SUM(purchase_price * purchase_quantity) as `amount`'));
            $purchase = $purchase->groupby('product_id')->orderByDesc('product_id')->first();

            $sold = OrderProduct::query();
            // Date Filter
            if($request->from_date){
                $sold->whereDate('created_at', '>=', $request->from_date);
            }
            if($request->to_date){
                $sold->whereDate('created_at', '<=', $request->to_date);
            }
            $sold->where('product_id', $data->id)->select(DB::raw('SUM(sale_price * quantity) as `amount`'));
            $sold = $sold->groupby('product_id')->orderByDesc('product_id')->first();

            $nestedData['sl'] = $key + $start + 1;
            $nestedData['name'] = '<a href="#" onclick="return detailItem(`product`, '. $data->id .');">'. $data->title .'</a>';
            $nestedData['available_stock'] = $data->stock;
            $nestedData['purchase'] = amount($purchase->amount ?? 0);
            $nestedData['sold'] = amount($sold->amount ?? 0);
            $nestedData['profit_loss'] = '<div class="text-right">'. amount(($sold->amount ?? 0) - ($purchase->amount ?? 0), 2) .'</div>';
            $nestedData['action'] = '<div class="text-right"><a href="'. route('back.report.productDetails', $data->id) .'" class="btn btn-sm btn-success">View Details</a></div>';
            $output[] = $nestedData;
        }

        // Output
        $output = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $output
        );
        return response()->json($output);
    }
    public function productDetails(Product $product, Request $request){
        if($request->start){
            $startDate = Carbon::parse($request->start);
        }else{
            $startDate = Carbon::now()->subDay(7);
        }
        if($request->end){
            $endDate = Carbon::parse($request->end)->endOfDay();
        }else{
            $endDate = Carbon::now()->endOfDay();
        }

        $reports = DB::table('orders')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->select('orders.status', DB::raw('SUM(order_products.quantity) as total_quantity'))
            ->where('order_products.product_id', $product->id)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->groupBy('orders.status')
            ->get();

        // Return Report
        $return_quantity = OrderProduct::where('product_id', $product->id)->whereBetween('created_at', [$startDate, $endDate])->sum('return_quantity');

        // Total Report
        $total_quantity = OrderProduct::where('product_id', $product->id)->whereBetween('created_at', [$startDate, $endDate])->sum('quantity');
        $total_quantity = $total_quantity + $return_quantity;

        return view('back.report.product.details', compact('product', 'reports', 'return_quantity', 'total_quantity'));
    }

    public function orders(Request $request){
        if($request->customer){
            $customer = User::find($request->customer);
        }else{
            $customer = null;
        }

        $query = Order::query();

        // Customer Filter
        if($request->customer){
            $query->where('user_id', $request->customer);
        }

        // Status filter
        if($request->status && $request->status != 'All'){
            $query->where('status', $request->status);
        }

        // Date Filter
        if($request->from_date){
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if($request->to_date){
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if($request->id_range){
            $ids = explode('-', $request->id_range);

            if(isset($ids[0]) && $ids[0]){
                $query->where('id', '>=', $ids[0]);
            }
            if(isset($ids[1]) && $ids[1]){
                $query->where('id', '<=', $ids[1]);
            }
        }

        $type = $request->type ?? 'filter';
        if($type == 'filter'){
            // Count Items
            $total_orders = $query->count();
            $query = $query->select('status', DB::raw('count(*) as total'), DB::raw('SUM((shipping_charge + product_total + tax_amount + other_cost) - (discount_amount + refund_total_amount)) as total_amount'))->groupby('status')->get();

            $statuses = [
                'Total' => $total_orders,
                'Processing' => 0,
                'Delivered' => 0,
                'Completed' => 0,
                'Returned' => 0
            ];
            $total_amount = 0;

            foreach($query as $data){
                $statuses[$data->status] = $data->total;

                $total_amount += $data->total_amount;
            }

            return view('back.report.order.index', compact('customer', 'statuses', 'total_amount'));
        }else{
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 3600);

            $orders = $query->get();
            if($type == 'excel'){
                return Excel::download(new OrderExport($orders), 'Order_report_'. date("Y-m-d-H-i-s") .'.xlsx');
            }else{
                $logo_path = public_path('uploads/info/' . Info::Settings('general', 'logo'));
                $title = Info::Settings('general', 'title');
                $html = view('back.report.order.exportPDF', compact('orders', 'logo_path', 'title'))->render();

                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($html)->setPaper('a4', 'landscape');
                return $pdf->stream();
            }
        }
    }
}
