<?php

namespace App\Http\Controllers\Back\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\Stock;
use App\Models\Product\StockHistory;
use App\Models\User;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stocks = Stock::latest('id')->get();

        return view('back.product.stock.index', compact('stocks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::active('id', 'supplier')->get();

        return view('back.product.stock.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'required',
            'product_id' => 'required',
            'product_data_id' => 'required',
            'quantity' => 'required',
            'price' => 'required'
        ]);

        foreach($request->product_id as $key => $product_id){
            $stock = new Stock;
            $stock->product_id = $product_id;
            $stock->product_data_id = $request->product_data_id[$key];
            $stock->purchase_quantity = $request->quantity[$key];
            $stock->current_quantity = $request->quantity[$key];
            $stock->purchase_price = $request->price[$key];
            $stock->user_id = $request->supplier;
            $stock->save();
        }

        return redirect()->route('back.stocks.index')->with('success-alert', 'Stock added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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

        if($request->category && $request->category != 'All'){
            $category_id = $request->category;
            $query->whereHas('Categories', function($c) use ($category_id){
                $c->where('categories.id', $category_id);
            });
        }

        // Search
        if($request->input('search.value')){
            $search = $request->input('search.value');
            $query->where(function($q) use ($search){
                $q->where('id', $search)
                ->orWhere('title', 'LIKE', "%{$search}%");
            });
        }

        // Count Items
        $totalFiltered = $query->count();
        if($limit == "-1"){
            $query->skip($start)->limit($totalFiltered);
        }else{
            $query->skip($start)->limit($limit);
        }
        $query = $query->orderBy($order, $dir)->get();

        $output = array();
        foreach ($query as $data) {
            $nestedData['id'] = $data->id;
            $nestedData['name'] = $data->title;
            $nestedData['image'] = '<img src="'. $data->img_paths['small'] .'" style="width:35px">';
            $nestedData['type'] = $data->type;
            $nestedData['action'] = '<div class="text-right"><button type="button" class="btn btn-success btn-sm add_item" data-id="'. $data->id .'">Add item</button></div>';
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

    // Add Item
    public function addItem(Request $request){
        $product = Product::find($request->id);

        if($product){
            return view('back.product.stock.addItem', compact('product'))->render();
        }
        return '';
    }

    public function preAlert(){
        return view('back.product.stock.preAlert');
    }

    public function alert(){
        return view('back.product.stock.alert');
    }

    public function out(){
        return view('back.product.stock.out');
    }

    public function history(){
        return view('back.product.stock.history');
    }
    public function historyTable(Request $request){
        // Get Data
        $columns = array(
            0 => 'id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = StockHistory::query();

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
            $nestedData['date'] = date('d m Y h:ia', strtotime($data->created_at));
            $nestedData['product'] = $data->Product->title ?? 'N/A';
            $nestedData['variation'] = $data->ProductData->attribute_items_string ?? 'N/A';
            $nestedData['quantity'] = '<div class="text-center">'. $data->quantity .'</div>';
            $nestedData['type'] = $data->type;
            $nestedData['note'] = $data->note;
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
}
