<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Product\Brand;
use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class RecycleBinController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('index', 'restoreProduct', 'restoreCategory', 'restoreBrand');
    }

    public function index(){
        $products = Product::onlyTrashed()->latest('id')->get();
        $categories = Category::onlyTrashed()->latest('id')->get();
        $brands = Brand::onlyTrashed()->latest('id')->get();

        return view('back.recycleBun.index', compact('products', 'categories', 'brands'));
    }

    public function restoreProduct($id){
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->deleted_at = null;
        $product->save();

        return redirect()->back()->with('success', 'One product restored from Recycle Bin!');
    }

    public function restoreCategory($id){
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->deleted_at = null;
        $category->save();

        return redirect()->back()->with('success', 'One category restored from Recycle Bin!');
    }

    public function restoreBrand($id){
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->deleted_at = null;
        $brand->save();

        return redirect()->back()->with('success', 'One brand restored from Recycle Bin!');
    }
}
