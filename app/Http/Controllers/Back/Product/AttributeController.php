<?php

namespace App\Http\Controllers\Back\Product;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Product\Attribute;
use App\Models\Product\AttributeItem;
use App\Models\Product\ProductData;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('index', 'store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attributes = Attribute::orderBy('name')->get();

        return view('back.product.attribute.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required|max:255'
        ]);

        $attribute = new Attribute();
        $attribute->name = $request->name;
        $attribute->save();

        return redirect()->back()->with('success-alert', 'Attribute created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return view('back.product.attribute.show', compact('attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        return view('back.product.attribute.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $attribute->name = $request->name;
        $attribute->save();

        return redirect()->back()->with('success-alert', 'Attribute updated successfully.');
    }
    public function updateModal(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $attribute = Attribute::findOrFail($request->id);

        $attribute->name = $request->name;
        $attribute->save();

        return redirect()->back()->with('success-alert', 'Attribute updated successfully.');
    }

    public function updateAjax(Request $request){
        $attribute = Attribute::find($request->id);
        if(!$attribute){
            return 'false';
        }

        $attribute->name = $request->name;
        $attribute->save();

        return 'true';
    }

    public function updateItemAjax(Request $request){
        $attribute_item = AttributeItem::find($request->id);
        if(!$attribute_item){
            return 'false';
        }

        $attribute_item->name = $request->name;
        $attribute_item->save();

        return 'true';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return redirect()->back()->with('success-alert', 'Attribute deleted successfully.');
    }

    // Store Items
    public function itemStore(Request $request){
        $request->validate([
            'attribute' => 'required',
            'items' => 'required|max:255'
        ]);

        $items_arr = explode(',', $request->items);
        foreach($items_arr as $item){
            $attribute_item = AttributeItem::where('attribute_id', $request->attribute)->where('name', $item)->first();

            if(!$attribute_item){
                $attribute_item = new AttributeItem;
                $attribute_item->name = $item;
                $attribute_item->attribute_id = $request->attribute;
                $attribute_item->save();
            }
        }

        return redirect()->back()->with('success-alert', 'Attribute item created successfully.');
    }

    // Item Delete
    public function itemDestroy($id){
        $attribute_item = AttributeItem::findOrFail($id);
        $attribute_item->delete();

        return redirect()->back()->with('success-alert', 'Attribute item deleted successfully.');
    }

    // Item Edit
    public function itemEdit($id){
        $attribute_item = AttributeItem::findOrFail($id);

        return view('back.product.attribute.itemEdit', compact('attribute_item'));
    }
    public function itemUpdate(Request $request){
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $attribute_item = AttributeItem::findOrFail($request->id);
        $attribute_item->name = $request->name;
        $attribute_item->save();
        return redirect()->back()->with('success-alert', 'Attribute item updated successfully.');
    }
}
