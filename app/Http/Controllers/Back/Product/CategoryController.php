<?php

namespace App\Http\Controllers\Back\Product;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Repositories\MediaRepo;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
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
        $categories = Category::with('Categories', 'Categories.Categories')->withCount('Products')->where('category_id', null)->where('for', 'product')->orderBy('title')->get();

        return view('back.product.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('category_id', null)->where('for', 'product')->latest('id')->get();
        return view('back.product.category.create', compact('categories'));
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

        $category = new Category;
        $category->title = $request->title;
        if($request->category_id){
            $category->category_id = $request->category_id;
        }
        if($request->for){
            $category->for = $request->for;
        }
        $category->icon = $request->icon;
        $category->description = $request->description;
        $category->short_description = $request->short_description;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_tags = $request->meta_tags;
        $category->background_color = $request->background_color;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $category->image = $uploaded_file['full_file_name'];
            $category->media_id = $uploaded_file['media_id'];
        }

        $category->save();

        return redirect()->back()->with('success-alert', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('back.product.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('back.product.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        if($category->title != $request->title){
            $slug = SlugService::createSlug(Category::class, 'slug', $request->title);
            $category->slug = $slug;
        }

        $category->icon = $request->icon;
        $category->title = $request->title;
        $category->short_description = $request->short_description;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_tags = $request->meta_tags;
        $category->background_color = $request->background_color;
        if($request->feature_position)
            $category->feature_position = $request->feature_position;

        if($request->file('image')){
            $uploaded_file = MediaRepo::upload($request->file('image'));
            $category->image = $uploaded_file['full_file_name'];
            $category->media_id = $uploaded_file['media_id'];
        }

        $category->save();

        return redirect()->back()->with('success-alert', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        // Delete Menu Relation
        DB::table('menu_items')->where('relation_with', 'category')->where('relation_id', $category->id)->delete();

        return redirect()->back()->with('success-alert', 'Category deleted successfully.');
    }

    public function delete(Category $category){
        $category->delete();

        return redirect()->route('back.categories.index')->with('success-alert', 'Category deleted successfully.');
    }

    public function getSubOptions(Request $request){
        if($request->categories_id){
            $categories = Category::where('for', 'product')->whereIn('category_id', $request->categories_id)->active()->get();
        }else{
            $categories = Category::where('for', 'product')->where('category_id', $request->category_id)->active()->get();
        }

        $output = '<option value="">Select category</option>';
        foreach($categories as $category){
            $output .= "<option value='$category->id'>$category->title</option>";
        }

        return $output;
    }

    public function changeParentCategory(Request $request){
        $request->validate([
            'parent_category' => 'required'
        ]);

        if($request->category == $request->parent_category){
            return redirect()->back()->with('error-alert2', 'You selected same category as parent category!');
        }

        $category = Category::find($request->category);
        $category->category_id = $request->parent_category;
        $category->save();

        return redirect()->back()->with('success-alert', 'Category updated successfully.');
    }

    public function changeProductCategory(Request $request){
        $request->validate([
            'product' => 'required'
        ]);

        $product = Product::find($request->product);
        $category = Category::find($request->category);
        if($product && $category){
            // DB::table('product_categories')->where('product_id', $product->id)->delete();

            if($category->Category && $category->Category->Category){
                ProductRepo::insertProductCategory($product->id, $category->Category->Category->id);
            }
            if($category->Category){
                ProductRepo::insertProductCategory($product->id, $category->Category->id);
            }
            ProductRepo::insertProductCategory($product->id, $category->id);
        }

        return redirect()->back()->with('success-alert', 'Product updated successfully.');
    }

    public function removeProduct($product, $category){
        ProductCategory::where('product_id', $product)->where('category_id', $category)->delete();

        return redirect()->route('back.categories.index')->with('success', 'Product removed from category.');
    }
}
