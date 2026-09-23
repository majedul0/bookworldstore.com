<?php

namespace App\Http\Controllers\Back\Product;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Product\Brand;
use App\Repositories\MediaRepo;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'edit', 'update', 'index', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::orderBy('title')->get();

        return view('back.product.brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.product.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_data = [
            'title' => 'required|max:255'
        ];

        if($request->file('image')){
            $v_data['image'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $brand = new Brand;
        $brand->title = $request->title;
        $brand->short_description = $request->short_description;
        $brand->description = $request->description;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_tags = $request->meta_tags;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $brand->image = $uploaded_file['full_file_name'];
            $brand->media_id = $uploaded_file['media_id'];
        }

        $brand->save();

        return redirect()->back()->with('success-alert', 'Brand created successfully.');
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
    public function edit(Brand $brand)
    {
        return view('back.product.brand.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        if($brand->title != $request->title){
            $slug = SlugService::createSlug(Brand::class, 'slug', $request->title);
            $brand->slug = $slug;
        }

        $brand->title = $request->title;
        $brand->short_description = $request->short_description;
        $brand->description = $request->description;
        $brand->meta_title = $request->meta_title;
        $brand->meta_description = $request->meta_description;
        $brand->meta_tags = $request->meta_tags;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $brand->image = $uploaded_file['full_file_name'];
            $brand->media_id = $uploaded_file['media_id'];
        }

        $brand->save();

        return redirect()->back()->with('success-alert', 'Brand updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        // Delete Menu Relation
        DB::table('menu_items')->where('relation_with', 'brand')->where('relation_id', $brand->id)->delete();

        return redirect()->route('back.brands.index')->with('success-alert', 'Brand deleted successfully.');
    }
}
